<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email - OPTIRH</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 30px -30px;
            text-align: center;
        }
        .success-badge {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .info-box {
            background-color: #f0f4f8;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
        }
        .config-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .config-table th,
        .config-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .config-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #667eea;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .timestamp {
            color: #999;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 OPTIRH - Test Email</h1>
        </div>
        
        <div class="success-badge">✅ Test Réussi</div>
        
        <h2>Félicitations !</h2>
        <p>
            Le système d'envoi d'emails de <strong>OPTIRH</strong> fonctionne correctement.
            Cet email de test confirme que votre configuration est opérationnelle.
        </p>
        
        <div class="info-box">
            <strong>📧 Email de destination :</strong> {{ $testEmail }}<br>
            <strong>📅 Date et heure :</strong> {{ $timestamp }}
        </div>
        
        <h3>Configuration Actuelle</h3>
        <table class="config-table">
            <tr>
                <th>Paramètre</th>
                <th>Valeur</th>
            </tr>
            <tr>
                <td>Driver Mail</td>
                <td>{{ $config['driver'] ?? 'Non configuré' }}</td>
            </tr>
            <tr>
                <td>Serveur SMTP</td>
                <td>{{ $config['host'] ?? 'Non configuré' }}</td>
            </tr>
            <tr>
                <td>Port SMTP</td>
                <td>{{ $config['port'] ?? 'Non configuré' }}</td>
            </tr>
            <tr>
                <td>Email Expéditeur</td>
                <td>{{ $config['from'] ?? 'Non configuré' }}</td>
            </tr>
            <tr>
                <td>Driver Queue</td>
                <td>{{ $config['queue'] ?? 'sync' }}</td>
            </tr>
        </table>
        
        <div class="info-box" style="background-color: #e8f5e9; border-left-color: #4CAF50;">
            <strong>✨ Système Amélioré</strong><br>
            Le système d'envoi d'emails inclut maintenant :
            <ul style="margin: 10px 0;">
                <li>Système de retry automatique (3 tentatives)</li>
                <li>Fallback vers les logs en cas d'échec</li>
                <li>Validation des adresses email</li>
                <li>Logging détaillé de toutes les opérations</li>
                <li>Support de la file d'attente pour l'envoi asynchrone</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>
                Cet email a été envoyé automatiquement par le système OPTIRH.<br>
                <span class="timestamp">{{ $timestamp }}</span>
            </p>
            <p style="margin-top: 20px;">
                <strong>OPTIRH</strong> - Système de Gestion des Ressources Humaines<br>
                © {{ date('Y') }} Tous droits réservés
            </p>
        </div>
    </div>
</body>
</html>