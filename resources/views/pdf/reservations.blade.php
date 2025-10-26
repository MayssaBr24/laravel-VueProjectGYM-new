<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des Réservations</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #1e293b;
            margin: 0;
            font-size: 24px;
        }
        .filters {
            background: #f8fafc;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #3b82f6;
            color: white;
            text-align: left;
            padding: 12px 8px;
            font-weight: bold;
        }
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .total {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
            color: #1e293b;
        }
        .date {
            text-align: right;
            color: #64748b;
            font-size: 10px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Liste des Réservations de <strong>{{ $coachFilter }}</strong></h1>
        @if($coachFilter)
           
        @endif
        <p>Généré le : {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    

    <table>
        <thead>
            <tr>
                <th>Client</th>
                <th>Email</th>
                <th>Cours</th>
                <th>Coach</th>
                <th>Date Réservation</th>
                <th>Heure</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $reservation)
            <tr>
                <td>{{ $reservation->user->name ?? 'Utilisateur inconnu' }}</td>
                <td>{{ $reservation->user->email ?? 'N/A' }}</td>
                <td>{{ $reservation->course->name ?? 'Cours inconnu' }}</td>
                <td>{{ $reservation->course->coach_name ?? 'Coach inconnu' }}</td>
                <td>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}</td>
                <td>
                    @if($reservation->course)
                        {{ \Carbon\Carbon::parse($reservation->course->start_time)->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($reservation->course->end_time)->format('H:i') }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total des réservations : {{ $reservations->count() }}
    </div>

    <div class="date">
        Document généré le {{ now()->format('d/m/Y à H:i') }}
    </div>
</body>
</html>