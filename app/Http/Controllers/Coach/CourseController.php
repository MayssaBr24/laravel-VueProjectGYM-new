<?php

namespace App\Http\Controllers\Coach;
use App\Events\NewCoursePublished ;
use App\Models\User;
use App\Notifications\NewCourseNotification;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Reservation;
use App\Models\CourseType;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'role:coach']);
    }

    // Liste des cours du coach
  public function index()
{
    // Calcul des statistiques
    $stats = [
        'active_courses' => Course::where('coach_id', auth()->id())
                                ->where('start_time', '>', now())
                                ->count(),
        'total_participants' => Reservation::whereHas('course', function($query) {
                                    $query->where('coach_id', auth()->id());
                                })->count(),
        'fill_rate' => (int) Course::where('coach_id', auth()->id())
                                ->withCount('reservations')
                                ->get()
                                ->avg(function($course) {
                                    return ($course->reservations_count / $course->max_participants) * 100;
                                })
    ];

    // Récupération des cours récents
    $recentCourses = Course::where('coach_id', auth()->id())
                         ->withCount('reservations')
                         ->latest()
                         ->take(5)
                         ->get()
                         ->map(function ($course) {
                             return [
                                 'id' => $course->id,
                                 'name' => $course->name,
                                 'description' => $course->description,
                                 'start_time' => $course->start_time,
                                 'end_time' => $course->end_time,
                                 'max_participants' => $course->max_participants,
                                 'current_participants' => $course->reservations_count,
                                 'remaining_slots' => $course->max_participants - $course->reservations_count
                             ];
                         });

  
    return Inertia::render('Coach/Dashboard', [
        'stats' => $stats,
        'recentCourses' => $recentCourses,
    ]);
}


    // Formulaire de création
    public function create()
    {
        return Inertia::render('Coach/Courses/Create',[
                        'types' => CourseType::all()

        ]);
        
    }

    // Stocker un nouveau cours
   public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'max_participants' => 'required|integer|min:1',
        'start_time' => 'required|date|after:now',
        'end_time' => 'required|date|after:start_time',
        'course_type_id' => 'required|exists:course_types,id' 

    ]);
        $courseType = CourseType::find($validated['course_type_id']);


    //  On crée le cours et on stocke dans $course
    $course = Course::create([
        ...$validated,
        'coach_id' => auth()->id(),
        'coach_name' => auth()->user()->name,
        'type' => $courseType->name,
        'price' => $courseType->price,
    ]);
    // Générer le QR code automatiquement
$this->generateQrCode($course);
    //  Notifier tous les clients intéressés
    $users = User::where('role', 'client')
                ->where('notification_preference', true)
                ->get();

    foreach ($users as $user) {
        $user->notify(new NewCourseNotification($course));
    }

    // Diffuser l'événement en temps réel
    broadcast(new NewCoursePublished($course))->toOthers();

    return redirect()->route('coach.courses.index')
        ->with('success', 'Cours créé avec succès!');
}

    

    // Afficher un cours avec participants
    public function show(Course $course)
    {
        $this->authorize('view', $course);

        $course->load(['reservations.user']);

        return Inertia::render('Coach/Courses/Show', [
            'course' => [
                'id' => $course->id,
                'name' => $course->name,
                'description' => $course->description,
                'start_time' => $course->start_time,
                'end_time' => $course->end_time,
                'max_participants' => $course->max_participants,
                'course_type_id' => 'required|exists:course_types,id',

                'current_participants' => $course->reservations->count(),
                'remaining_slots' => $course->max_participants - $course->reservations->count(),
                'participants' => $course->reservations->map(function ($reservation) {
                    return [
                        'id' => $reservation->user->id,
                        'name' => $reservation->user->name,
                        'email' => $reservation->user->email,
                        'reserved_at' => $reservation->created_at,
                    ];
                }),
            ]
        ]);
    }

    // Supprimer un cours
    public function destroy(Course $course)
{
    // Vérification simple que le cours appartient au coach connecté
    if ($course->coach_id !== auth()->id()) {
        abort(403, 'Vous n\'êtes pas autorisé à supprimer ce cours');
    }
    
    $course->delete();
    
    return redirect()->route('coach.courses.index')
        ->with('success', 'Cours supprimé avec succès!');
}
private function generateQrCode(Course $course)
{
    $qrData = "coach_id={$course->coach_id}&course_id={$course->id}";

    $qrCode = new \Endroid\QrCode\QrCode($qrData);
    $writer = new \Endroid\QrCode\Writer\PngWriter();

    Storage::disk('public')->makeDirectory('qrcodes');
    $fileName = "qrcodes/course_{$course->id}.png";

    $writer->write($qrCode)->saveToFile(Storage::disk('public')->path($fileName));

    $course->update(['qr_code' => $fileName]);
}
public function downloadQrPdf(Course $course)
{
    if (!$course->qr_code || !Storage::disk('public')->exists($course->qr_code)) {
        return back()->with('error', 'QR code non disponible.');
    }

    $qrPath = Storage::disk('public')->path($course->qr_code);
    $qrBase64 = base64_encode(file_get_contents($qrPath));
    $qrDataUri = 'data:image/png;base64,' . $qrBase64;

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('courses.qr_pdf', [
        'qrDataUri' => $qrDataUri,
        'course' => $course
    ]);

    return $pdf->download("course_{$course->id}_qr.pdf");
}

}