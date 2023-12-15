<?php
$path=$_GET["path"]="./phpmailer/";
$fichier = $path."contents.html";
$template = (isset($_GET["template"]))?$_GET["template"]:$path."contents_simple.html";
$sujet = (isset($_GET["titre"]) && !empty($_GET["titre"]))?utf8_decode($_GET["titre"]):"Notification SISE MERF";
$message = (isset($_GET["message"]) && !empty($_GET["message"]))?$_GET["message"]:"";
$replay = (isset($_GET["replay"]) && !empty($_GET["replay"]))?$_GET["replay"]:"";
$recipients = $attachment = array();
$recipients = $email_to;
$cc = $email_cc;
$attachment = (isset($_GET["attachment"]) && !empty($_GET["attachment"]))?$_GET["attachment"]:array();
$Username = "admin@ssise-merf.org";
$Password = "VEUYNoMvyYA!l";
if(!empty($fichier))
{
  ob_start(); // turn on output buffering
  include($fichier);
  $content = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering

  $content = str_replace('{titre}',($sujet),$content);
  $content = str_replace('{contenu}',($message),$content);
  // Assurons nous que le fichier est accessible en écriture
  if (is_writable($template)) {
      if (!$handle = fopen($template, 'w+')) {
           echo "Impossible d'ouvrir le fichier ($template)";
           exit;
      }
      // Ecrivons quelque chose dans notre fichier.
      if (fwrite($handle, trim($content)) === FALSE) {
          echo "Impossible d'écrire dans le fichier ($template)";
          exit;
      }
      //echo "L'écriture de () dans le fichier ($template) a réussi";
      fclose($handle);
  } else {
      //echo "Le fichier $template n'est pas accessible en écriture."; exit();
  }
}
$fichier = $template;

include($path."send_mail.php");
?>