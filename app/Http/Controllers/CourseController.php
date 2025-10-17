<?php

namespace App\Http\Controllers;

use App\Events\NewCoursePublished;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\User;
use App\Notifications\NewCourseNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $courses = Course::all();
        return Inertia::render('Courses/Index', ['courses' => $courses]);
    }

    public function create()
    {
        return Inertia::render('Courses/Create', [
            'types' => CourseType::all(),
            'coaches' => User::where('role', 'coach')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'coach_name' => 'required|string',
            'max_participants' => 'required|integer|min:1',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'course_type_id' => 'required|exists:course_types,id'
        ]);

        $courseType = CourseType::findOrFail($request->course_type_id);

        $course = Course::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'coach_id' => auth()->id(),
            'coach_name' => $validated['coach_name'],
            'type' => $courseType->name,
            'price' => $courseType->price,
            'max_participants' => $validated['max_participants'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'course_type_id' => $validated['course_type_id'],
        ]);

        // 🔹 Génération du QR code
        $this->generateQrCode($course);

        // Notifications aux clients
        $users = User::where('role', 'client')
            ->where('notification_preference', true)
            ->get();

        foreach ($users as $user) {
            $user->notify(new NewCourseNotification($course));
        }

        broadcast(new NewCoursePublished($course))->toOthers();

        return redirect()->route('courses.index')->with('success', 'Cours ajouté et QR code généré !');
    }

    public function edit(Course $course)
    {
        return Inertia::render('Courses/Edit', ['course' => $course]);
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'coach_name' => 'required|string',
            'max_participants' => 'required|integer|min:1',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'course_type_id' => 'required|exists:course_types,id'
        ]);

        $courseType = CourseType::findOrFail($request->course_type_id);

        $course->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'coach_name' => $validated['coach_name'],
            'max_participants' => $validated['max_participants'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'type' => $courseType->name,
            'price' => $courseType->price,
            'course_type_id' => $validated['course_type_id'],
        ]);

        // 🔹 Régénération du QR code
        $this->generateQrCode($course);

        return redirect()->route('courses.index')->with('success', 'Cours mis à jour !');
    }

    public function destroy(Course $course)
    {
        // Supprimer QR code si existe
        if ($course->qr_code && Storage::disk('public')->exists($course->qr_code)) {
            Storage::disk('public')->delete($course->qr_code);
        }

        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Cours supprimé.');
    }

   public function userIndex()
{
    $courses = Course::with('coach')
        ->withCount('reservations')
        ->where('start_time', '>', now())
        ->orderBy('start_time')
        ->get();

    return Inertia::render('Courses/UserIndex', [
        'courses' => $courses->map(function ($course) {
            return [
                'id' => $course->id,
                'name' => $course->name,
                'coach_name' => $course->coach_name,
                'description' => $course->description,
                'start_time' => $course->start_time,
                'end_time' => $course->end_time,
                'max_participants' => $course->max_participants,
                'current_participants' => $course->reservations_count,
                // 🔹 Chemin relatif vers storage
                'qr_code_url' => $course->qr_code ? "/storage/{$course->qr_code}" : null,
                'qr_code_pdf_url' => $course->qr_code ? route('courses.downloadQrPdf', $course->id) : null,
            ];
        })
    ]);
}


    public function downloadQr(Course $course)
    {
        if (!$course->qr_code || !Storage::disk('public')->exists($course->qr_code)) {
            return back()->with('error', 'QR code non disponible.');
        }

        $filePath = storage_path("app/public/{$course->qr_code}");
        return response()->download($filePath, "course_{$course->id}_qr.png");
    }

  public function downloadQrPdf(Course $course)
{
    if (!$course->qr_code || !Storage::disk('public')->exists($course->qr_code)) {
        return back()->with('error', 'QR code non disponible.');
    }

    // Chemin absolu du fichier QR code
    $qrPath = Storage::disk('public')->path($course->qr_code);
    
    // Convertir l'image en base64 pour DomPDF
    $qrBase64 = base64_encode(file_get_contents($qrPath));
    $qrDataUri = 'data:image/png;base64,' . $qrBase64;

    // Génération PDF avec la vue
    $pdf = Pdf::loadView('courses.qr_pdf', [
        'qrDataUri' => $qrDataUri,
        'course' => $course
    ]);

    return $pdf->download("course_{$course->id}_qr.pdf");
}

private function generateQrCode(Course $course)
{
    $qrData = "coach_id={$course->coach_id}&course_id={$course->id}";

    // Instanciation simple du QR code
    $qrCode = new QrCode($qrData);

    // Writer PNG
    $writer = new PngWriter();

    // Chemin de stockage
    Storage::disk('public')->makeDirectory('qrcodes');
    $fileName = "qrcodes/course_{$course->id}.png";

    // Génération et sauvegarde
    $writer->write($qrCode)->saveToFile(Storage::disk('public')->path($fileName));

    // Mise à jour du modèle
    $course->update(['qr_code' => $fileName]);
}
}
