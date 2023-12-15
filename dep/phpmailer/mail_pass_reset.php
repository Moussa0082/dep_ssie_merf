<?php

$path=$_GET["path"]="./phpmailer/";
$fichier = $path."contents.html";
$template = (isset($_GET["template"]))?$_GET["template"]:$path."contents_simple.html";
$sujet = (isset($_GET["titre"]) && !empty($_GET["titre"]))?$_GET["titre"]:"Re-initialisation de mot de passe MERF";
$message = (isset($_GET["message"]) && !empty($_GET["message"]))?$_GET["message"]:"";
$recipients = $attachment = array();
$replay = (isset($_GET["replay"]) && !empty($_GET["replay"]))?$_GET["replay"]:"";
$a = explode("|",$_GET["adresse"]);
foreach($a as $b) { list($c,$d) = explode(',',$b); $recipients[$c] = isset($d)?$d:""; unset($c,$d); }
$a = explode("|",$_GET["adresse_cc"]);
foreach($a as $b) { list($c,$d) = explode(',',$b); $cc[$c] = isset($d)?$d:""; unset($c,$d); }
$Username = "admin@ssise-merf.org";
$Password = "VEUYNoMvyYA!l";
if(!empty($fichier))
{
  ob_start(); // turn on output buffering
  include($fichier);
  $content = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering

  $content = str_replace('{titre}',utf8_decode($sujet),$content);
  $content = str_replace('{contenu}',utf8_decode($message),$content);
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