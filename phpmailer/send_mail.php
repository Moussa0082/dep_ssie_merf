<?php
/**
 * This example shows settings to use when sending via Google's Gmail servers.
 */

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');
$path = (isset($path))?$path:"./";
require $path.'PHPMailerAutoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;

//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';

//Set the hostname of the mail server
$mail->Host = ' 	vps93923.serveur-vps.net';
//$mail->Host = 'smtp.gmail.com';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;
$mail->SMTPAuth = true;
//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';

/*//Whether to use SMTP authentication
//Whether to use SMTP authentication
$mail->SMTPAuth = true; */

//SSL security off
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = (isset($Username))?$Username:"admin@ssise-merf.org";

//Password to use for SMTP authentication
$mail->Password = (isset($Password))?$Password:"VEUYNoMvyYA!l";

//Set who the message is to be sent from
$mail->setFrom((isset($From))?$From:'admin@ssise-merf.org', 'MERF');

//Set an alternative reply-to address
//$mail->addReplyTo('replyto@example.com', 'First Last');
if(isset($replay) && !empty($replay))
$mail->addReplyTo($replay, 'MERF');

//Set who the message is to be sent to
//$mail->addAddress('fousseyni.sangare@gmail.com', 'Diesel');

//Set list of adresse to be sent to
/*$recipients = array(
   'fousseyni.sangare@gmail.com' => 'Person One',
   'fousseyni_sangare@yahoo.fr' => 'Person Two',
   'kone.lacine@gmail.com' => 'Koné',
   // ..
); */
foreach($recipients as $email => $name)
{
   $mail->addAddress($email, $name);
}
foreach($cc as $email => $name)
{
   $mail->addCC($email, $name);
}

//Set the subject line
$mail->Subject = (isset($sujet))?$sujet:'MERF Mail';

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
if(isset($fichier))
$mail->msgHTML(file_get_contents($fichier), dirname(__FILE__));
else
$mail->msgHTML(file_get_contents($path.'contents.html'), dirname(__FILE__));

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML("This is a plain-text message");

//Replace the plain text body with one created manually
//$mail->AltBody = 'Version texte simple';

//Attach file
if(isset($attachment))
{
  foreach($attachment as $file)
  {
    if(file_exists($file))
    $mail->addAttachment($file);
  }
}

//send the message, check for errors
if (!$mail->send()) {
//if (!isset($recipients)) {
    //echo "Mailer Error: " . $mail->ErrorInfo;
    echo "<h1 align='center'>Impossible d'envoyer le mail !</h1>
    <h1 align='center'>Une erreur s'est produite !</h1>";
} else {  $msg_sent = true;
    echo "<h1 align='center'>Message envoy&eacute;!</h1><ul>";
    foreach($recipients as $email => $name)
    {
      echo (!empty($email))?"<li>$name = $email</li>":"";
    }
    if(isset($cc))
    {
      echo "<li>Copie envoy&eacute; :</li>";
      foreach($cc as $email => $name)
      {
        echo (!empty($email))?"<li>$name = $email</li>":"";
      }
    }
    echo "</ul>";
}
echo "<br>";

?>