<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Inertia\Inertia;
use App\Models\Payment;
use App\Models\SubscriptionType;
use App\Models\CourseType;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function create()
    {
        return response()->json([
            'clients' => User::where('role', 'client')
                         ->select(['id', 'name', 'email'])
                         ->get(),
            'courseTypes' => CourseType::select(['id', 'name', 'price'])
                                  ->get(),
            'subscriptionTypes' => SubscriptionType::select(['id', 'name', 'price'])
                                               ->get()
        ]);
    }

    public function index(Request $request)
    {
        $filter = $request->input('status');

        $query = Payment::with('user')
            ->when($filter, function ($q) use ($filter) {
                return $q->where('status', $filter);
            });

        $payments = $query->latest()->get();

        // Transforme en tableau simple pour la vue
        $paymentsData = $payments->map(function ($payment) {
            return [
                'id' => $payment->id,
                'member_name' => $payment->user->name ?? 'Membre inconnu',
                'amount' => $payment->amount,
                'date' => $payment->paid_at ? $payment->paid_at->format('Y-m-d') : 'Date inconnue',
                'status' => $payment->status,
            ];
        });

        // Calcul du revenu des 30 derniers jours
        $thirtyDaysAgo = now()->subDays(30);
        $totalRevenueLast30Days = Payment::where('status', 'succeeded')
            ->where('paid_at', '>=', $thirtyDaysAgo)
            ->sum('amount');

        return Inertia::render('Admin/Payments/Index', [
            'payments' => $paymentsData,
            'totalRevenue' => $totalRevenueLast30Days,
        ]);
    }

    public function export()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=payments.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $payments = Payment::with('user')->latest()->get();

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');
            // Entêtes
            fputcsv($file, ['ID', 'Nom', 'Montant', 'Date', 'Statut']);

            foreach ($payments as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->user->name ?? 'Inconnu',
                    $p->amount,
                    $p->paid_at ? $p->paid_at->format('Y-m-d') : '',
                    $p->status,
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'payment_type' => 'required|in:course,subscription',
            'course_type_id' => 'required_if:payment_type,course|exists:course_types,id',
            'subscription_type_id' => 'required_if:payment_type,subscription|exists:subscription_types,id',
        ]);

        try {
            if ($validated['payment_type'] === 'course') {
                $courseType = CourseType::findOrFail($validated['course_type_id']);
                $amount = $courseType->price;
            } else {
                $subscriptionType = SubscriptionType::findOrFail($validated['subscription_type_id']);
                $amount = $subscriptionType->price;
            }

            $payment = Payment::create([
                'user_id' => $validated['user_id'],
                'payment_type' => $validated['payment_type'],
                'course_type_id' => $validated['payment_type'] === 'course' ? $validated['course_type_id'] : null,
                'subscription_type_id' => $validated['payment_type'] === 'subscription' ? $validated['subscription_type_id'] : null,
                'amount' => $amount,
                'status' => 'succeeded',
                'paid_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'payment' => $payment,
                'message' => 'Paiement enregistré avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
