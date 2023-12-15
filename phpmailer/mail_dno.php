<?php

$path="./"; $_GET["path"]="./";
$fichier = $_GET["fichier"];//"../mail_liste_dno.php";
$sample = "./contents_dno.html";
$template = (isset($_GET["template"]))?$_GET["template"]:"./template.html";
$sujet = (isset($_GET["titre"]) && !empty($_GET["titre"]))?$_GET["titre"]:"SSE DANO";
$recipients = $attachment = array();
$replay = (isset($_GET["replay"]) && !empty($_GET["replay"]))?$_GET["replay"]:"";
$a = explode("|",$_GET["adresse"]);
$From = (isset($_GET["from"]) && !empty($_GET["from"]))?$_GET["from"]:"psac@psac-ci.org";
if(count($a)>0)
{
  foreach($a as $b)
  {
    $c = explode(',',$b);
    if(empty($c[1])) $c[1] = "PSAC";
    if(!empty($c[0])) $recipients[$c[0]] = $c[1];
  }
}
/*$recipients = array(
   //'fousseyni.sangare@gmail.com' => 'Person One',
   //'fousseyni_sangare@yahoo.fr' => 'Person Two',
   //'kone.lacine@gmail.com' => 'Koné',
   'dano@psac-ci.org' => 'PSAC',
); */
$dir = './attachment/dano/';
if(!isset($_GET["attachment"])) unset($attachment);
else
{
  $a = explode("|",$_GET["attachment"]);
  if(count($a)>0)
  {
    foreach($a as $b)
    {
      if(!empty($b)) array_push($attachment,$dir.$b);
    }
  }
}
//$attachment = array('cisco.gif');
if(!empty($fichier))
{
  ob_start(); // turn on output buffering
  include($fichier);
  $message = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering

  ob_start(); // turn on output buffering
  include($sample);
  $content = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering


  $content = str_replace('{titre}',$sujet,$content);
  $content = str_replace('{contenu}',$message,$content);
  $content = str_replace('<>','&',$content);
  // Assurons nous que le fichier est accessible en écriture
  if (is_writable($template)) {
      if (!$handle = fopen($template, 'w')) {
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

include("./send_mail.php");  
?>