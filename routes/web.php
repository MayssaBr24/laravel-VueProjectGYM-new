<?php
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\CourseController ;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Coach\CourseManagementController;
use App\Http\Controllers\Coach\CourseDetailController;
use App\Http\Controllers\Coach\CourseController as CoachCourseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CoachController as AdminCoachController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\PlanningController as AdminPlanningController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Payments\PaymentController;
use App\Http\Controllers\Payments\CoursePaymentController;
use App\Http\Controllers\Payments\AttendanceController;
use App\Http\Controllers\Payments\PendingAttendancesController;
use App\Http\Controllers\ClientQrCodeController;
use App\Http\Controllers\Admin\AdminQrController ;
use App\Events\NewCoursePublished ;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Admin\CourseTypeController;
use App\Http\Controllers\Admin\SubscriptionTypeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Client\QrScannerController;
use App\Http\Controllers\Coach\IntrusController;

//page d'acceuil
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => true,
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

//Redirection après login selon rôle

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'coach' => redirect()->route('coach.dashboard'),
        'client' => redirect()->route('client.dashboard'),
        default => abort(403),
    };
})->name('dashboard');

//Routes réservées aux administrateurs

Route::prefix('admin')->middleware(['auth', 'verified', 'role:admin'])->name('admin.')->group(function () {

    // Dashboard principal
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Gestion des utilisateurs
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [AdminUserController::class, 'store'])->name('users.store');

    // Gestion des coachs
    Route::get('/coaches', [AdminCoachController::class, 'index'])->name('coaches.index');

    // Gestion des membres
    Route::get('/members', [AdminMemberController::class, 'index'])->name('members.index');
    Route::get('/members/{member}/edit', [AdminMemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{member}', [AdminMemberController::class, 'update'])->name('members.update');
    Route::delete('/members/{member}', [AdminMemberController::class, 'destroy'])->name('members.destroy');

    // Planning
    Route::get('/planning', [AdminPlanningController::class, 'index'])->name('planning.index');

    // Réservations admin
    Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');

});
// type de cours pour l'admin
Route::middleware(['auth', 'role:admin'])->prefix('course-types')->group(function () {
    Route::get('/create', [CourseTypeController::class, 'create'])->name('course-types.create');
    Route::post('/', [CourseTypeController::class, 'store'])->name('course-types.store');
});


//Routes pour les clients
Route::prefix('client')->middleware(['auth', 'verified', 'role:client'])->name('client.')->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'clientDashboard'])->name('dashboard');
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations');
    Route::get('/courses', [CourseController::class, 'userIndex'])->name('courses');
});

Route::prefix('coach')->middleware(['auth', 'verified', 'role:coach'])->name('coach.')->group(function () {
    Route::get('/dashboard', [CoachCourseController::class, 'index'])->name('dashboard');
    
// Routes pour les cours du coach
    Route::resource('courses', CoachCourseController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::get('/mes-cours', [CourseManagementController::class, 'mesCours'])->name('courses.mescours');
    Route::get('/courses/{course}/details', [CourseDetailController::class, 'show'])->name('courses.details');
    Route::get('/courses/{course}/export', [CourseDetailController::class, 'exportParticipants'])->name('courses.export'); // Export
  
});

// Routes générales

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('courses', CourseController::class)->except(['destroy']);
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

    // Réservations globales
    Route::get('/reservations/stats', [ReservationController::class, 'stats'])->name('reservations.stats');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/my-reservations', [ReservationController::class, 'index'])->name('reservations.my');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
});

//gestion de profile client,admin,coach
Route::middleware(['auth'])->group(function () {
    // Route pour afficher le profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    
    // Route pour mettre à jour le profil
    Route::match(['put', 'post'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Route pour l'avatar
    Route::get('/storage/avatars/{filename}', function ($filename) {
        $path = storage_path('app/public/avatars/' . $filename);
        
        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        return response($file, 200)->header('Content-Type', $type);
    })->name('avatar.show');
});

// paiments


Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    
    // Route principale du dashboard admin — C'EST CELLE-CI QUI DOIT FOURNIR pendingAttendances
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Marquer la présence (POST)
   Route::post('/attendances/{attendance}/verify', [AttendanceController::class, 'markAsAttended'])
        ->name('admin.attendance.verify');
});
//Afficher les paiements en attente pour le client
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/payments/pending', [CoursePaymentController::class, 'pending'])->name('payments.pending');
    Route::post('/payments/pay', [CoursePaymentController::class, 'pay'])->name('payments.pay');
    Route::middleware(['auth'])->group(function () {
    Route::post('/payments/prepare', [CoursePaymentController::class, 'prepare'])->name('payments.prepare');
    Route::post('/payments/finalize', [CoursePaymentController::class, 'finalize'])->name('payments.finalize');
    

Route::post('/attendance/mark/{attendance}', [AttendanceController::class, 'markAsAttended'])->name('attendance.mark');

});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/payments/intent', [PaymentController::class, 'createPaymentIntent']);
    Route::post('/payments/confirm', [PaymentController::class, 'confirmPayment']);
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::get('/payments/export', [PaymentController::class, 'exportPayments']);
});
 Route::get('/payments/new', function () {
        return Inertia::render('PaymentForm'); // ou Payment/Create selon ton nom
    })->name('payments.create');

//codeqr
 Route::middleware(['role:client'])->group(function () {
        Route::get('/client/qrcode', [ClientQrCodeController::class, 'show'])
            ->name('client.qrcode');
    });

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/scan-qr', [AdminQrController::class, 'showScanPage'])->name('scan.qr');
    Route::post('/verify-attendance', [AdminQrController::class, 'verifyAttendance']);
});
//notification
Broadcast::channel('public.courses', function () {
    return true; 
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/notification-preference', function (Request $request) {
        return response()->json([
            'enabled' => $request->user()->notification_preference
        ]);
    });
    
    Route::post('/user/notification-preference', function (Request $request) {
        $request->user()->update([
            'notification_preference' => $request->enabled
        ]);
        
        return response()->json(['success' => true]);
    });
});

//calendrier 
Route::middleware('auth:sanctum')->get('/calendar/courses', [CalendarController::class, 'index']);
});
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/calendar', [CalendarController::class, 'index'])->name('Calendrier/index');
});
Route::middleware(['auth', 'role:admin'])->prefix('course-types')->name('admin.course-types.')->group(function () {
    Route::get('/', [CourseTypeController::class, 'index'])->name('index');
    Route::get('/create', [CourseTypeController::class, 'create'])->name('create');
    Route::post('/', [CourseTypeController::class, 'store'])->name('store');

});
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('subscription-types', [SubscriptionTypeController::class, 'index'])->name('subscription-types.index');
    Route::post('subscription-types', [SubscriptionTypeController::class, 'store'])->name('subscription-types.store');
});





// API route
Route::middleware('auth:sanctum')->get('/api/course-types', [CourseTypeController::class, 'apiIndex']);


// Route pour obtenir les données du formulaire
Route::get('/payment/create', [PaymentController::class, 'create']);

// Route pour enregistrer le paiement
Route::post('payment', [PaymentController::class, 'store']);

// Route pour rechercher des clients (optionnelle)
Route::get('payment/search-clients', [PaymentController::class, 'searchClients']);

Route::post('api/admin/payment', [PaymentController::class, 'store']);



// API route
Route::middleware('auth:sanctum')->get('/api/course-types', [CourseTypeController::class, 'apiIndex']);


// Route pour obtenir les données du formulaire
Route::get('/payment/create', [PaymentController::class, 'create']);

// Route pour enregistrer le paiement
Route::post('payment', [PaymentController::class, 'store']);

// Route pour rechercher des clients (optionnelle)
Route::get('payment/search-clients', [PaymentController::class, 'searchClients']);

Route::post('api/admin/payment', [PaymentController::class, 'store']);
//Route chat
Route::post('/api/chat', [ChatController::class, 'chat']);

//qr scanner pour le client 

// Afficher la page du scanner QR
Route::get('/client/scan-qr', [QrScannerController::class, 'showScannerPage'])
    ->name('client.scan.page');

//  Route protégée par authentification pour enregistrer la présence
Route::middleware(['auth'])->group(function () {
    Route::post('/client/scan-qr', [AttendanceController::class, 'markAttendance'])
        ->name('client.scan');
});

// Télécharger le QR Code PDF
Route::get('/courses/{id}/download-qr-pdf', [CourseController::class, 'downloadQrPdf'])
    ->name('courses.downloadQrPdf');


Route::prefix('coach')->group(function () {
    Route::get('/courses/{course}/attendances', [AttendanceController::class, 'show'])->name('coach.attendances.show');
    Route::post('/attendances/{attendance}/update-status', [AttendanceController::class, 'updateStatus'])->name('coach.attendances.update');
    Route::get('/courses/{course}/participants-json', [AttendanceController::class, 'participantsJson']);
});
