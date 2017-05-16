<?php
namespace Controllers\Tools;

use Controllers\Tools\Mail\PHPMailer;
/**
 * Class Mail
 * Rassemble les outils d'envoie mail
 */
class Mail
{
    /**
     * Fonction qui permet l'envoie d'un mail selon plusieurs paramètres
     * @param $subject                  : Objet du mail
     * @param $email                    : Email du destinataire
     * @param $pseudo                   : Pseudo du destinataire
     * @param $contentMessage           : Contenu du message
     * @param $nameButton               : Texte qui figurera dans le bouton de redirection
     * @param $link                     : Lien du bouton de redirection
     * @return int                      : Si erreur dans l'envoie 0 sinon 1
     *
     * @throws Mail\PHPMailerException
     */
    public static function sendMail($subject, $email, $redirection, $contentMessage)
    {
        $content = '<html> <head> <meta content="text/html; charset=utf-8" http-equiv="Content-Type" /> </head> <body style="margin: 0;mso-line-height-rule: exactly;padding: 0;min-width: 100%"> <table class="preheader" style="border-collapse: collapse;border-spacing: 0;width: 100%;background-color: #edeee9"> <tbody> '.$redirection.' <tr> <td class="preheader-buffer" colspan="2" style="padding: 0;vertical-align: top;background-color: #f4f5f2;font-size: 20px;line-height: 20px">&nbsp;</td> </tr> </tbody> </table> <table class="one-col-bg" style= "border-collapse: collapse;border-spacing: 0;width: 100%;background-color: #f4f5f2"> <tbody> <tr> <td align="center" style="padding: 0;vertical-align: top"> <table class="one-col" style= "border-collapse: collapse;border-spacing: 0;Margin-left: auto;Margin-right: auto;width: 600px;border-radius: 6px;-moz-border-radius: 6px"> <tbody> <tr> <td class="column" style= "padding: 0;vertical-align: top;text-align: left"> <table class="contents" style= "border-collapse: collapse;border-spacing: 0;width: 100%;border-radius: 6px;-moz-border-radius: 6px;background-color: #ffffff"> <tbody> <tr> <td style= "padding: 0;vertical-align: top"> <div class= "rounded-image-bleed"> <div class="image" style= "Margin-bottom: 20px; color: #808285; font-family: sans-serif; font-size: 12px; text-align: center"> <a href= "http://libiakot-test.test.fundp.ac.be" style= "color: #808285"><img class= "gnd-corner-image gnd-corner-image-center gnd-corner-image-top" height="250px" src= "http://img4.hostingpics.net/pics/143000mail.png" style= "border: 0;-ms-interpolation-mode: bicubic;display: block;border-top-left-radius: 6px;-moz-border-top-left-radius: 6px;border-top-right-radius: 6px;-moz-border-top-right-radius: 6px;max-width: 900px" width="600" /></a> </div> </div> '.$contentMessage.' <div class="column-bottom" style= "font-size: 10px;line-height: 10px"> &nbsp; </div> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> <div class="separator" style= "font-size: 20px;line-height: 20px;background-color: #f4f5f2"> &nbsp; </div> <table class="footer" style= "border-collapse: collapse;border-spacing: 0;table-layout: fixed;width: 100%;background-color: #edeee9"> <tbody> <tr> <td style= "padding: 0;vertical-align: top;text-align: left;color: #9a9c9e;font-family: sans-serif"> &nbsp; </td> <td class="inner" style= "padding: 0;vertical-align: top;text-align: left;color: #9a9c9e;font-family: sans-serif;padding-top: 40px;padding-bottom: 75px;width: 560px"> <table style= "border-collapse: collapse;border-spacing: 0;width: 100%"> <tbody> <tr> <td class="campaign" style= "padding: 0;vertical-align: top;text-align: left;color: #9a9c9e;font-family: sans-serif;font-size: 12px;line-height: 20px"> <div class="address" style= "Margin-bottom: 17px">Université de Namur<br/>Service logement<br/>rue Bruno, 7 - 5000 Namur<br/><br/> Contact : 081/72 50 82<br/><a href="mailto:cathy.jentgen@unamur.be" target="_blank" style="text-decoration: none;color:#55ab26;">cathy.jentgen@unamur.be</a></div> </td> <td class="social" style= "padding: 0;vertical-align: top;color: #9a9c9e;text-align: left;font-family: sans-serif;text-transform: uppercase;width: 190px"> <div class="padded" style= "padding-left: 40px;padding-right: 40px;Margin-left: 20px;padding: 0"> <div style="Margin-bottom: 10px"> <a href="https://www.facebook.com/UniversitedeNamur/?fref=ts" style= "color: #9a9c9e;border-bottom: none;display: block;font-size: 12px;letter-spacing: 0.05em;line-height: 14px;text-decoration: none"> <img alt="" height="30" src= "http://i4.createsend1.com/static/eb/master/10-hue/images/facebook-dark.png" style= "border: 0;-ms-interpolation-mode: bicubic;vertical-align: middle" width="38" /><span style= "mso-text-raise: 11px">Like</span></a> </div> <div style="Margin-bottom: 10px"> <a href="http://libiakot-test.test.fundp.ac.be" lang="en" style= "color: #9a9c9e;border-bottom: none;display: block;font-size: 12px;letter-spacing: 0.05em;line-height: 14px;text-decoration: none"> <img alt="" height="30" src= "http://i6.createsend1.com/static/eb/master/10-hue/images/forward-dark.png" style= "border: 0;-ms-interpolation-mode: bicubic;vertical-align: middle" width="38" /><span style= "mso-text-raise: 11px">LIBIAKOT</span></a> </div> </div> </td> </tr> </tbody> </table> </td> <td style= "padding: 0;vertical-align: top;text-align: left;color: #9a9c9e;font-family: sans-serif"> &nbsp; </td> </tr> </tbody> </table> </body></html>';
        

        $mail = new PHPMailer;
        $mail->isSMTP();                                    // Set mailer to use SMTP
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = false;                             // Enable SMTP authentication
        $mail->Host = 'smtp.unamur.be';                     // Specify main and backup SMTP servers
        $mail->Username = 'logement@unamur.be';             // SMTP username
        $mail->Password = '';                               // SMTP password
        $mail->SMTPSecure = '';                             // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 25;                                   // TCP port to connect to

        $mail->setFrom('logement@unamur.be', 'Service logement UNamur');
        $mail->AddAddress($email);
        $mail->addReplyTo('logement@unamur.be', 'Service logement UNamur');

        $mail->isHTML(true);                                // Set email format to HTML
        $mail->Subject = utf8_decode($subject);
        $mail->Body    = utf8_decode($content);
        return (!$mail->send()) ? 0 : 1;
    }
}
