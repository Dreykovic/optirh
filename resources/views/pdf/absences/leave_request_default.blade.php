<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Demande de Congé Maladie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .content {
            margin-top: 20px;
        }

        .content table {
            width: 100%;
            border-collapse: collapse;
        }

        .content table th,
        .content table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Demande de Congé Maladie</h2>
        <p><strong>Type d'absence:</strong> Congé maladie</p>
    </div>

    <div class="content">
        <table>
            <tr>
                <th>Date de début</th>
                <td>{{ $leaveRequest->start_date }}</td>
            </tr>
            <tr>
                <th>Date de fin</th>
                <td>{{ $leaveRequest->end_date }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ ucfirst($leaveRequest->status) }}</td>
            </tr>
            <tr>
                <th>Motif</th>
                <td>{{ $leaveRequest->reason }}</td>
            </tr>
        </table>
    </div>

</body>

</html>
