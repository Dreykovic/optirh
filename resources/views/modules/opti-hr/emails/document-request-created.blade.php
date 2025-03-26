<style>
    html,
    body {
        padding: 0;
        margin: 0;
    }
</style>

<div
    style="font-family:Arial,Helvetica,sans-serif; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
        style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
        <tbody>
            <tr>
                <td align="center" valign="center" style="text-align:center; padding: 40px">
                    <a href="#" rel="noopener" target="_blank">
                        <img alt="Logo" src="{{ $message->embed(public_path('assets/img/logo.png')) }}"
                            style="max-height: 50px;">
                    </a>
                </td>
            </tr>

            <tr>
                <td align="left" valign="center">
                    <div
                        style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">

                        <!--begin:Email content-->
                        <div style="padding-bottom: 30px; font-size: 17px;">
                            <strong>Bonjour {{ $receiverName }} !</strong>
                        </div>

                        <div style="padding-bottom: 30px">
                            Une nouvelle demande de document de {{ $requesterName }} est en attente de votre
                            approbation.
                            <br><br>
                            <strong>Détails de la demande :</strong><br>
                            - Type de document: {{ $documentType }}<br>
                            - Date de demande: {{ $dateOfApplication }}<br>
                            @if ($startDate)
                                - Date de début: {{ $startDate }}<br>
                            @endif
                            @if ($endDate)
                                - Date de fin: {{ $endDate }}<br>
                            @endif
                            @if ($reasons)
                                - Motif: {{ $reasons }}<br>
                            @endif
                            <br>
                            Veuillez consulter cette demande et prendre les mesures nécessaires pour la suite du
                            processus.
                        </div>

                        <div style="padding-bottom: 40px; text-align:center;">
                            <a href="{{ $url }}" rel="noopener" target="_blank"
                                style="text-decoration:none;display:inline-block;text-align:center;padding:0.75575rem 1.3rem;font-size:0.925rem;line-height:1.5;border-radius:0.35rem;color:#ffffff;background-color:#5BB18A;border:0px;margin-right:0.75rem!important;font-weight:600!important;outline:none!important;vertical-align:middle"
                                target="_blank">
                                Traiter la demande
                            </a>
                        </div>

                        <div style="padding-bottom: 20px">
                            Si le bouton ne fonctionne pas, vous pouvez accéder à la demande en cliquant sur le lien
                            suivant ou en le copiant dans votre navigateur :
                            <br>
                            <a href="{{ $url }}" style="color: #5BB18A;">{{ $url }}</a>
                        </div>

                        <div style="padding-bottom: 10px">
                            Cordialement,<br>
                            OptiHR<br>
                            Espace Collaboratif.
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td align="center" valign="center"
                    style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
                    <p>© {{ date('Y') }} OptiHR. Tous droits réservés.</p>
                </td>
            </tr>
        </tbody>
    </table>
</div>
