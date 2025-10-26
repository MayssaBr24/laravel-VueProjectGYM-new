<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export des Paiements</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
        }
        .summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }
        .summary-item {
            text-align: center;
        }
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        .summary-label {
            font-size: 12px;
            color: #6c757d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #2c3e50;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .status-success {
            color: #28a745;
            font-weight: bold;
        }
        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
        .status-failed {
            color: #dc3545;
            font-weight: bold;
        }
        .amount {
            text-align: right;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #6c757d;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport des Paiements</h1>
        <p>Export généré le {{ $exportDate }}</p>
    </div>

   <div class="summary">
    <div class="summary-item flex flex-row items-center justify-start space-x-8">
        <div class="summary-value text-center">
            <div class="text-2xl font-bold">{{ $totalCount }}</div>
            <div class="summary-label text-sm text-gray-500">Total Paiements</div>
        </div>
        <div class="summary-value text-center">
            <div class="text-2xl font-bold">{{ number_format($totalRevenue) }} TND</div>
            <div class="summary-label text-sm text-gray-500">Chiffre d'Affaires</div>
        </div>
    </div>
</div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Membre</th>
                <th>Email</th>
                <th>Type</th>
                <th>Contenu</th>
                <th class="text-center">Montant</th>
                <th class="text-center">Date Paiement</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>#{{ $payment->id }}</td>
                <td>{{ $payment->user->name ?? 'Inconnu' }}</td>
                <td>{{ $payment->user->email ?? '' }}</td>
                <td>
                    @if($payment->payment_type === 'course')
                        Cours
                    @else
                        Abonnement
                    @endif
                </td>
                <td>
                    @if($payment->payment_type === 'course' && $payment->courseType)
                        {{ $payment->courseType->name }}
                    @elseif($payment->payment_type === 'subscription' && $payment->subscriptionType)
                        {{ $payment->subscriptionType->name }}
                    @else
                        -
                    @endif
                </td>
                <td class="amount">{{ number_format($payment->amount, 2, ',', ' ') }}TND</td>
                <td class="text-center">
                    {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : 'Non payé' }}
                </td>
                
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Document généré automatiquement le {{ $exportDate }} | 
        Total : {{ $totalCount }} paiements | 
        CA Total : {{ number_format($totalRevenue, 2, ',', ' ') }}TND
    </div>
</body>
</html>