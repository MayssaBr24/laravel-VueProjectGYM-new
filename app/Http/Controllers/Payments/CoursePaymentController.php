<?php

namespace App\Http\Controllers\Payments;
use App\Http\Controllers\Controller;

use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Stripe\Stripe;
use Stripe\PaymentIntent;
class CoursePaymentController extends Controller
{
    public function pending(Request $request)
    {
        $payments = Payment::with('course')
            ->where('user_id', $request->user()->id)
            ->where('attendance_verified', true)
            ->whereNull('paid_at')
            ->get();

        return Inertia::render('Payments/Pending', [
            'payments' => $payments
        ]);
    }

   public function pay(Request $request)
{
    $payment = Payment::findOrFail($request->id);

    // Crée un PaymentIntent Stripe
    $stripeIntent = $request->user()->createSetupIntent();

    return Inertia::render('Payments/CheckoutModal', [
        'payment' => $payment,
        'client_secret' => $stripeIntent->client_secret
    ]);
}
public function prepare(Request $request)
{
    $payment = Payment::with('course')->findOrFail($request->id);
    $intent = $request->user()->createSetupIntent();

    return response()->json([
        'payment' => $payment,
        'client_secret' => $intent->client_secret,
    ]);
}







public function finalize(Request $request)
{
    $request->validate([
        'payment_id' => 'required|exists:payments,id',
        'payment_method' => 'required|string',
    ]);

    $payment = Payment::findOrFail($request->payment_id);
    $user = $request->user();

    $user->charge($payment->amount * 100, $request->payment_method);

    $payment->update([
        'payment_method' => $request->payment_method,
        'paid_at' => now(),
        'status' => 'succeeded',
    ]);

    return redirect()->route('payments.pending')->with('success', 'Paiement réussi !');
}
}