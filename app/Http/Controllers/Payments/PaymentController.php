<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Payment;
use App\Models\SubscriptionType;
use App\Models\CourseType;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $payments = Payment::with(['user', 'courseType', 'subscriptionType'])
            ->latest()
            ->get();

        // Statistiques
        $totalRevenue = $payments->where('status', 'succeeded')->sum('amount');
        $successCount = $payments->where('status', 'succeeded')->count();
        $pendingCount = $payments->where('status', 'pending')->count();
        $failedCount = $payments->where('status', 'failed')->count();

        $data = [
            'payments' => $payments,
            'totalRevenue' => $totalRevenue,
            'successCount' => $successCount,
            'pendingCount' => $pendingCount,
            'failedCount' => $failedCount,
            'exportDate' => now()->format('d/m/Y à H:i'),
            'totalCount' => $payments->count(),
        ];

        $pdf = PDF::loadView('exports.payments-pdf', $data)
                  ->setPaper('a4', 'landscape')
                  ->setOptions([
                      'defaultFont' => 'sans-serif',
                      'isHtml5ParserEnabled' => true,
                      'isRemoteEnabled' => true,
                  ]);

        return $pdf->download('paiements_' . now()->format('Y-m-d_H-i') . '.pdf');
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

    /**
     * Traduit le statut en français
     */
    private function translateStatus($status)
    {
        $statuses = [
            'succeeded' => 'Réussi',
            'pending' => 'En attente',
            'failed' => 'Échoué',
            'refunded' => 'Remboursé'
        ];

        return $statuses[$status] ?? $status;
    }

    /**
     * Traduit le type de paiement en français
     */
    private function translatePaymentType($type)
    {
        $types = [
            'course' => 'Cours',
            'subscription' => 'Abonnement'
        ];

        return $types[$type] ?? $type;
    }
}