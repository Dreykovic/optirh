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

                            <strong>Salut !</strong>

                        </div>

                        <div style="padding-bottom: 30px">
                            Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe
                            pour votre compte OptiRh.
                            Pour procéder à la réinitialisation du mot de passe, veuillez cliquer sur le bouton
                            ci-dessous
                        </div>

                        <div style="padding-bottom: 40px; text-align:center;">
                            <a href=" {{ $resetLink }}" rel="noopener" target="_blank"
                                style="text-decoration:none;display:inline-block;text-align:center;padding:0.75575rem 1.3rem;font-size:0.925rem;line-height:1.5;border-radius:0.35rem;color:#ffffff;background-color:#82b690;border:0px;margin-right:0.75rem!important;font-weight:600!important;outline:none!important;vertical-align:middle"
                                target="_blank">
                                Réinitialiser le mot de passe
                            </a>
                        </div>

                        <div style="padding-bottom: 30px">
                            Ce lien de réinitialisation du mot de passe expirera dans 15 minutes.

                            Si vous n'avez pas demandé de réinitialisation de mot de passe, aucune autre action n'est
                            requise
                        </div>

                        <div style="border-bottom: 1px solid #eeeeee; margin: 15px 0"></div>

                        <div style="padding-bottom: 50px; word-wrap: break-all;">
                            <p style="margin-bottom: 10px;">
                                Le bouton ne fonctionne pas ? Essayez de copier ce lien dans votre navigateur :
                            </p>

                            <a href=" {{ $resetLink }}" rel="noopener" target="_blank"
                                style="text-decoration:none;color: #82b690">
                                {{ $resetLink }}
                            </a>
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

                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</div>
