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
                            <strong>Bienvenue sur OptiRh !</strong>
                        </div>

                        <div style="padding-bottom: 30px">
                            Nous avons le plaisir de vous informer que votre compte OptiRh a été créé avec succès.
                            Veuillez trouver ci-dessous vos identifiants de connexion :
                        </div>

                        <div
                            style="padding-bottom: 30px; background-color: #f8f9fa; padding: 20px; border-radius: 6px;">
                            <p><strong>Adresse email :</strong> {{ $email }}</p>
                            <p><strong>Mot de passe :</strong> {{ $password }}</p>
                        </div>

                        <div style="padding-bottom: 40px; text-align:center; margin-top: 30px;">
                            <a href="{{ $loginLink }}" rel="noopener" target="_blank"
                                style="text-decoration:none;display:inline-block;text-align:center;padding:0.75575rem 1.3rem;font-size:0.925rem;line-height:1.5;border-radius:0.35rem;color:#ffffff;background-color:#82b690;border:0px;margin-right:0.75rem!important;font-weight:600!important;outline:none!important;vertical-align:middle"
                                target="_blank">
                                Se connecter maintenant
                            </a>
                        </div>

                        <div style="padding-bottom: 30px">
                            Pour des raisons de sécurité, nous vous recommandons vivement de modifier votre mot de passe
                            lors de votre première connexion.
                        </div>

                        <div style="border-bottom: 1px solid #eeeeee; margin: 15px 0"></div>

                        <div style="padding-bottom: 50px; word-wrap: break-all;">
                            <p style="margin-bottom: 10px;">
                                Le bouton ne fonctionne pas ? Essayez de copier ce lien dans votre navigateur :
                            </p>

                            <a href="{{ $loginLink }}" rel="noopener" target="_blank"
                                style="text-decoration:none;color: #82b690">
                                {{ $loginLink }}
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
                </td>
            </tr>
        </tbody>
    </table>
</div>
