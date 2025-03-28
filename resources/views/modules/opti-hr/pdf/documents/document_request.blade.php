<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type" />
    <style type="text/css">
        body {
            font-family: "Lucida Bright", serif;
            font-size: 14pt;
            line-height: 1.5;
            color: #000000;
            margin: 0;
            padding: 40px;
            background-color: #ffffff;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px 50px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .title {
            font-size: 22pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .separator {
            text-align: center;
            font-weight: bold;
            margin: 20px 0;
        }

        .content {
            text-align: justify;
            margin-bottom: 30px;
        }

        .bold {
            font-weight: bold;
        }

        .date {
            text-align: right;
            margin-right: 80px;
            margin-bottom: 60px;
        }

        .signature {
            text-align: right;
            margin-right: 80px;
            text-decoration: underline;
            font-weight: bold;
        }

        .logo {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <!-- Logo placeholder -->
            <img class="logo" src="{{ public_path('assets/img/logo.png') }}" alt="Logo ARCOP" />
            <div class="title">{{ strtoupper($documentRequest->document_type->label) }}</div>
        </div>

        <div class="separator">------------------------------------------</div>

        <div class="content">
            Je soussigné, <span class="variable">{{ "{$dg->last_name} {$dg->first_name}" }}</span>, <span
                class="variable">{{ $dgJob->description }}</span> de l'Autorité de régulation de la commande publique
            (ARCOP), atteste que <span class="bold">Monsieur <span
                    class="variable">{{ " {$documentRequest->duty->employee->last_name} {$documentRequest->duty->employee->first_name}" }}</span></span>
            travaille en qualité de <span
                class="variable">{{ " {$documentRequest->duty->job->title} à la {$documentRequest->duty->job->department->description}" }}</span>
            de l'ARCOP depuis le <span
                class="variable">{{ formatDateRange($documentRequest->start_date, $documentRequest->end_date) }}</span>
            à ce jour.
        </div>

        <div class="content">
            En foi de quoi, la présente attestation est délivrée à <span class="bold">Monsieur <span
                    class="variable">{{ " {$documentRequest->duty->employee->last_name} {$documentRequest->duty->employee->first_name}" }}</span></span>
            pour servir et valoir ce que de droit.
        </div>

        <div class="date">
            Fait à Lomé, <span class="variable">@formatDateOnly($documentRequest->date_of_approval)</span>
        </div>

        <div class="signature">
            <span class="variable">{{ "{$dg->last_name} {$dg->first_name}" }}</span>
        </div>
    </div>
</body>

</html>
