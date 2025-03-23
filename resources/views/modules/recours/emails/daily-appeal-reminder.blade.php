<!-- resources/views/emails/daily_appeal_reminder.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Rappel: Recours en cours</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        h2 {
            color: #444;
            text-align: center;
        }
        p {
            line-height: 1.6;
        }
        .appeal-item {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        .appeal-item:last-child {
            border-bottom: none;
        }
        .label {
            color: #666;
            font-weight: 600;
        }
        .text-danger {
            color: #d9534f;
            font-weight: bold;
        }
        ul {
            padding: 0;
            list-style: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🔔 Rappel: Recours en cours</h2>
    <p>Bonjour,</p>
    <p>Voici les recours qui sont en cours depuis plus de sept (7) jours :</p>

    <ul>
        @foreach ($appeals as $appeal)
            <li class="appeal-item">
                <span class="label">Durée écoulée :</span> <span class="text-danger">{{ $appeal->day_count }} jours</span> <br>
                <span class="label">Objet Recours :</span> {{ $appeal->object }} <br>
                <span class="label">Requérant :</span> {{ $appeal->applicant->name }} <br>
                <span class="label">Marché :</span> {{ $appeal->dac->reference }}
            </li>
        @endforeach
    </ul>

    <p>Merci de prendre les mesures nécessaires.</p>
</div>

</body>
</html>
