<?php
require_once "src/bootstrap.php";
require_once "src/Models/Entities/Language.php";
require_once "src/Models/Entities/Translation.php";
require_once "src/Models/Entities/Label.php";

use Controllers\Tools\Navi;
use Controllers\Tools\Language;
use Models\Session;

if (!isset($_GET['t'])) header('Location: index.php?p=error');

session_start();
Session::set('Language', $_GET['l']);
$translation = Language::translation("mail");
$contentMessage = Navi::getContentMail($translation, true, $_GET['fn'], $_GET['m'], $link = "http://localhost/Projet/index.php?p=user.confirmation&t=".$_GET['t']);

$content = '<html> <head> <meta content="text/html; charset=utf-8" http-equiv="Content-Type" /> </head> <body style="margin: 0;mso-line-height-rule: exactly;padding: 0;min-width: 100%"> <table class="preheader" style="border-collapse: collapse;border-spacing: 0;width: 100%;background-color: #edeee9"> <tbody><tr> <td class="preheader-buffer" colspan="2" style="padding: 0;vertical-align: top;background-color: #f4f5f2;font-size: 20px;line-height: 20px">&nbsp;</td> </tr> </tbody> </table> <table class="one-col-bg" style= "border-collapse: collapse;border-spacing: 0;width: 100%;background-color: #f4f5f2"> <tbody> <tr> <td align="center" style="padding: 0;vertical-align: top"> <table class="one-col" style= "border-collapse: collapse;border-spacing: 0;Margin-left: auto;Margin-right: auto;width: 600px;border-radius: 6px;-moz-border-radius: 6px"> <tbody> <tr> <td class="column" style= "padding: 0;vertical-align: top;text-align: left"> <table class="contents" style= "border-collapse: collapse;border-spacing: 0;width: 100%;border-radius: 6px;-moz-border-radius: 6px;background-color: #ffffff"> <tbody> <tr> <td style= "padding: 0;vertical-align: top"> <div class= "rounded-image-bleed"> <div class="image" style= "Margin-bottom: 20px; color: #808285; font-family: sans-serif; font-size: 12px; text-align: center"> <a href= "http://localhost/Projet/" style= "color: #808285"><img class= "gnd-corner-image gnd-corner-image-center gnd-corner-image-top" height="250px" src= "http://img4.hostingpics.net/pics/143000mail.png" style= "border: 0;-ms-interpolation-mode: bicubic;display: block;border-top-left-radius: 6px;-moz-border-top-left-radius: 6px;border-top-right-radius: 6px;-moz-border-top-right-radius: 6px;max-width: 900px" width="600" /></a> </div> </div> '.$contentMessage.' <div class="column-bottom" style= "font-size: 10px;line-height: 10px"> &nbsp; </div> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> <div class="separator" style= "font-size: 20px;line-height: 20px;background-color: #f4f5f2"> &nbsp; </div> <table class="footer" style= "border-collapse: collapse;border-spacing: 0;table-layout: fixed;width: 100%;background-color: #edeee9"> <tbody> <tr> <td style= "padding: 0;vertical-align: top;text-align: left;color: #9a9c9e;font-family: sans-serif"> &nbsp; </td> <td class="inner" style= "padding: 0;vertical-align: top;text-align: left;color: #9a9c9e;font-family: sans-serif;padding-top: 40px;padding-bottom: 75px;width: 560px"> <table style= "border-collapse: collapse;border-spacing: 0;width: 100%"> <tbody> <tr> <td class="campaign" style= "padding: 0;vertical-align: top;text-align: left;color: #9a9c9e;font-family: sans-serif;font-size: 12px;line-height: 20px"> <div class="address" style= "Margin-bottom: 17px">Université de Namur<br/>Service logement<br/>rue Bruno, 7 - 5000 Namur<br/><br/> Contact : 081/72 50 82<br/>cathy.jentgen@unamur.be</div> </td> <td class="social" style= "padding: 0;vertical-align: top;color: #9a9c9e;text-align: left;font-family: sans-serif;text-transform: uppercase;width: 190px"> <div class="padded" style= "padding-left: 40px;padding-right: 40px;Margin-left: 20px;padding: 0"> <div style="Margin-bottom: 10px"> <a href="https://www.facebook.com/UniversitedeNamur/?fref=ts" style= "color: #9a9c9e;border-bottom: none;display: block;font-size: 12px;letter-spacing: 0.05em;line-height: 14px;text-decoration: none"> <img alt="" height="30" src= "http://i4.createsend1.com/static/eb/master/10-hue/images/facebook-dark.png" style= "border: 0;-ms-interpolation-mode: bicubic;vertical-align: middle" width="38" /><span style= "mso-text-raise: 11px">Like</span></a> </div> <div style="Margin-bottom: 10px"> <a href="http://localhost/Projet/" lang="en" style= "color: #9a9c9e;border-bottom: none;display: block;font-size: 12px;letter-spacing: 0.05em;line-height: 14px;text-decoration: none"> <img alt="" height="30" src= "http://i6.createsend1.com/static/eb/master/10-hue/images/forward-dark.png" style= "border: 0;-ms-interpolation-mode: bicubic;vertical-align: middle" width="38" /><span style= "mso-text-raise: 11px">LIBIAKOT</span></a> </div> </div> </td> </tr> </tbody> </table> </td> <td style= "padding: 0;vertical-align: top;text-align: left;color: #9a9c9e;font-family: sans-serif"> &nbsp; </td> </tr> </tbody> </table> </body></html>';

echo $content;
/*echo '

<html>
   <head>
      <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
   </head>
   <body style="margin: 0;mso-line-height-rule: exactly;padding: 0;min-width: 100%">
      <table class="preheader" style="border-collapse: collapse;border-spacing: 0;width: 100%;background-color: #edeee9">
         <tbody>
            <tr>
               <td class="webversion" style="padding: 9px;vertical-align: top;font-size: 11px;line-height: 16px;text-align: center;width: 250px;color: #9a9c9e;font-family: sans-serif">Si cet email ne s\'affiche pas correctement <a href="http://hephaistos.sytes.net/mail.php" style="color: #9a9c9e;text-decoration: none;font-weight: bold">cliquez ici</a> </td>
            </tr>
            <tr>
               <td class="preheader-buffer" colspan="2" style="padding: 0;vertical-align: top;background-color: #f4f5f2;font-size: 20px;line-height: 20px">&nbsp;</td>
            </tr>
         </tbody>
      </table>
      <table class="one-col-bg" style= "border-collapse: collapse;border-spacing: 0;width: 100%;background-color: #f4f5f2">
         <tbody>
            <tr>
               <td align="center" style="padding: 0;vertical-align: top">
                                          <div class= "rounded-image-bleed">
                                             <div class="image" style= "Margin-bottom: 20px; color: #808285; font-family: sans-serif; font-size: 12px; text-align: center"> <a href= "http://localhost/Projet/" style= "color: #808285"><img class= "gnd-corner-image gnd-corner-image-center gnd-corner-image-top" height="250px" src= "web/pictures/mail.png" style= "border: 0;-ms-interpolation-mode: bicubic;display: block;border-top-left-radius: 6px;-moz-border-top-left-radius: 6px;border-top-right-radius: 6px;-moz-border-top-right-radius: 6px;max-width: 900px" width="600" /></a> </div>
                                          </div>
                                          <table style= "border-collapse: collapse;border-spacing: 0;width: 100%" width="100%">
                                             <tbody>
                                                <tr>
                                                   <td class= "padded" style= "padding: 0;vertical-align: top;padding-left: 40px;padding-right: 40px;text-align: left">
                                                   
                                                      <h1 style= "Margin-top: 0;font-weight: normal;color: #808285;font-family: sans-serif;font-size: 19px;Margin-bottom: 20px;text-align : center;line-height: 22px"> <strong style="font-weight: bold"> Bonjour'.$pseudo.'</strong> </h1>
                                                      <p style= "Margin-top: 0;font-weight: normal;color: #808285;font-family: sans-serif;font-size: 14px;Margin-bottom: 20px;text-align:center;line-height: 22px">'.$contentMessage.'</p>
                                                      </p> 
                                                   </td>
                                                </tr>
                                             </tbody>
                                          </table>
                                          <table style= "border-collapse: collapse;border-spacing: 0;width: 100%" width="100%">
                                             <tbody>
                                                <tr>
                                                   <td class= "padded" style= "padding: 0;vertical-align: top;padding-left: 40px;padding-right: 40px;text-align: left">
                                                      <div class= "btn" style="Margin-bottom: 20px;text-align: center">
                                                         <a href="'.$link.'" style= "display: inline-block;text-align: center;text-decoration: none;border-radius: 6px;-moz-border-radius: 6px;color: #fff;font-size: 16px;line-height: 24px;font-family: sans-serif;background-color: #55ab26;padding: 15px 60px">Je confirme mon inscription</a>
                                                      </div>
                                                   </td>
                                                </tr>
                                             </tbody>
                                          </table>
                                          <div class="column-bottom" style= "font-size: 10px;line-height: 10px"> &nbsp; </div>
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
      <div class="separator" style= "font-size: 20px;line-height: 20px;background-color: #f4f5f2"> &nbsp; </div>
      <table class="footer" style= "border-collapse: collapse;border-spacing: 0;table-layout: fixed;width: 100%;background-color: #edeee9">
         <tbody>
            <tr>
               <td style= "padding: 0;vertical-align: top;text-align: left;color: #9a9c9e;font-family: sans-serif"> &nbsp; </td>
               <td class="inner" style= "padding: 0;vertical-align: top;text-align: left;color: #9a9c9e;font-family: sans-serif;padding-top: 40px;padding-bottom: 75px;width: 560px">
                  <table style= "border-collapse: collapse;border-spacing: 0;width: 100%">
                     <tbody>
                        <tr>
                           <td class="campaign" style= "padding: 0;vertical-align: top;text-align: left;color: #9a9c9e;font-family: sans-serif;font-size: 12px;line-height: 20px">
                              <div class="address" style= "Margin-bottom: 17px">Université de Namur<br/>Service logement<br/>rue Bruno, 7 - 5000 Namur<br/><br/> Contact : 081/72 50 82<br/>cathy.jentgen@unamur.be</div>
                           </td>
                           <td class="social" style= "padding: 0;vertical-align: top;color: #9a9c9e;text-align: left;font-family: sans-serif;text-transform: uppercase;width: 190px">
                              <div class="padded" style= "padding-left: 40px;padding-right: 40px;Margin-left: 20px;padding: 0">
                                 <div style="Margin-bottom: 10px"> <a href="https://www.facebook.com/UniversitedeNamur/?fref=ts" style= "color: #9a9c9e;border-bottom: none;display: block;font-size: 12px;letter-spacing: 0.05em;line-height: 14px;text-decoration: none"> <img alt="" height="30" src= "http://i4.createsend1.com/static/eb/master/10-hue/images/facebook-dark.png" style= "border: 0;-ms-interpolation-mode: bicubic;vertical-align: middle" width="38" /><span style= "mso-text-raise: 11px">Like</span></a> </div>
                                 <div style="Margin-bottom: 10px"> <a href="http://localhost/Projet/" lang="en" style= "color: #9a9c9e;border-bottom: none;display: block;font-size: 12px;letter-spacing: 0.05em;line-height: 14px;text-decoration: none"> <img alt="" height="30" src= "http://i6.createsend1.com/static/eb/master/10-hue/images/forward-dark.png" style= "border: 0;-ms-interpolation-mode: bicubic;vertical-align: middle" width="38" /><span style= "mso-text-raise: 11px">Site Officiel</span></a> </div>
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
               <td style= "padding: 0;vertical-align: top;text-align: left;color: #9a9c9e;font-family: sans-serif"> &nbsp; </td>
            </tr>
         </tbody>
      </table>
   </body>
</html>';*/