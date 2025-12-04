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
                            <strong>Notification de sécurité</strong>
                        </div>

                        <div style="padding-bottom: 30px">
                            Bonjour {{ $userName }},
                        </div>

                        <div style="padding-bottom: 30px">
                            {{ $contextMessage }}
                        </div>

                        <div
                            style="padding-bottom: 30px; background-color: #f8f9fa; padding: 20px; border-radius: 6px;">
                            <p><strong>Date et heure :</strong> {{ $changedAt }}</p>
                        </div>

                        <div
                            style="padding: 20px; background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 6px; margin-bottom: 30px;">
                            <p style="margin: 0; color: #856404;">
                                <strong>Important :</strong> Si vous n'êtes pas à l'origine de ce changement ou si vous
                                n'avez pas été prévenu(e), veuillez contacter immédiatement les administrateurs de la
                                plateforme.
                            </p>
                        </div>

                        <div style="border-bottom: 1px solid #eeeeee; margin: 15px 0"></div>

                        <div style="padding-bottom: 50px; color: #6d6e7c; font-size: 13px;">
                            Cet email a été envoyé automatiquement pour vous informer d'un changement sur votre compte.
                            Merci de ne pas répondre à ce message.
                        </div>
                        <!--end:Email content-->

                        <div style="padding-bottom: 10px">
                            OptiRH<br>
                            ARCOP DSAF.
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td align="center" valign="center"
                    style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
                    <p>ARCOP.</p>
                </td>
            </tr>
        </tbody>
    </table>
</div>
