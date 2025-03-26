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
                        style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">

                        <!-- Header with accent color -->
                        <div
                            style="margin:-40px -40px 30px -40px; padding: 20px 40px; background-color:#5BB18A; border-radius: 6px 6px 0 0;">
                            <h2 style="color: #ffffff; margin: 0; font-size: 22px;">Notification OptiHR</h2>
                        </div>

                        <!--begin:Email content-->
                        <div style="padding-bottom: 30px; font-size: 17px;">
                            <strong>Salut {{ $receiverName }} !!!</strong>
                        </div>

                        <div
                            style="padding-bottom: 30px; background-color: #f8f9fa; padding: 20px; border-radius: 6px; border-left: 4px solid #5BB18A;">
                            Une nouvelle demande d'absence de {{ $text }} nécessite votre action pour permettre
                            la poursuite du
                            processus. Nous vous invitons à consulter la demande et à prendre les mesures nécessaires.
                            <br><br>
                            Cliquez sur le bouton ci-dessous pour accéder directement à la demande :
                        </div>

                        <div style="padding: 30px 0; text-align:center;">
                            <a href="{{ $url }}" rel="noopener" target="_blank"
                                style="text-decoration:none;display:inline-block;text-align:center;padding:0.75575rem 1.3rem;font-size:0.925rem;line-height:1.5;border-radius:0.35rem;color:#ffffff;background-color:#5BB18A;border:0px;margin-right:0.75rem!important;font-weight:600!important;outline:none!important;vertical-align:middle;box-shadow: 0 2px 6px rgba(91,177,138,0.4);transition: all 0.2s ease;"
                                target="_blank">
                                Consulter
                            </a>
                        </div>

                        <div style="padding-bottom: 20px; font-size: 14px; color: #6d6e7c; text-align: center;">
                            Si le bouton ne fonctionne pas : <a href="{{ $url }}"
                                style="color: #5BB18A; text-decoration: underline;">Accéder à la demande</a>
                        </div>

                        <div style="padding-top: 20px; border-top: 1px solid #e9ecef; margin-top: 20px;">
                            <div style="padding-bottom: 10px">
                                Cordialement,<br>
                                <strong style="color: #5BB18A;">OPTIRH</strong><br>
                                ARCOP DSAF.
                            </div>
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
