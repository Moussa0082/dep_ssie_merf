<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"]) && !isset($_GET["id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}


/*include_once $config->sys_folder . "/database/db_connexion.php";

mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_edit_dossier = "SELECT * FROM ".$database_connect_prefix."commune ";
  $edit_dossier = mysql_query($query_edit_dossier, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_edit_dossier = mysql_fetch_assoc($edit_dossier);
  $totalRows_liste_docworkflow  = mysql_num_rows($edit_dossier);


if ($totalRows_liste_docworkflow>0) { do{
  $id=$row_edit_dossier['id_commune']; $code = $row_edit_dossier["code_commune"];
  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."village SET commune=%s WHERE commune=%s",
                       GetSQLValueString($code, "text"),
                       GetSQLValueString($id, "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
   }while($row_edit_dossier = mysql_fetch_assoc($edit_dossier));

}
     echo "<h1>good!</h1>";   */

     //test mail
?>
<?php session_start(); ini_set("display_errors",1);
//fonction calcul nb jour

function NbJours($debut, $fin) {
  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);
  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
  return (($diff / 86400)+1);
}

include_once 'system/configuration.php';
if(!isset($config)) $config = new Config;
header('Content-Type: text/html; charset=UTF-8');
include_once $config->sys_folder . "/database/db_connexion.php";

echo "<h1>PNF</h1>";
echo "<h1>Section alerte de la base de données</h1>";
/*include_once 'system/configuration.php';
if(!isset($config)) $config = new Config;
header('Content-Type: text/html; charset=UTF-8'); */
if(!isset($config)) $config = new Config;

//include_once $config->sys_folder . "/database/db_connexion.php";

if(!class_exists('PHPMailer'))
require 'phpmailer/PHPMailerAutoload.php';


//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = 'mail.ruche-pnf.org';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6
//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;
//Set the encryption system to use - ssl (deprecated) or tls
//$mail->SMTPSecure = 'tls';
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = "info@ruche-pnf.org";
//Password to use for SMTP authentication
$mail->Password = "programmefida";
//Set who the message is to be sent from
$mail->setFrom('info@ruche-pnf.org', 'PNF ALERTES');
//Set list of adresse to be sent to
//$email = "foussyni.sangare@gmail.com";
$recipients = array();
$recipients[$email] = "";
$email = "alassanesangare2003@gmail.com";
$mail->addAddress($email/*, $row_liste_respo["nom"]." ".$row_liste_respo["prenom"]*/);
/*$mail->addCC("fousseyni.sangare@gmail.com");
$mail->addCC("tidianecamara50@gmail.com");
//$mail->addCC("kone.lacine@gmail.com");
$mail->addCC("sanogozp@yahoo.fr");*/
$mail->Subject = 'Notification du PNF - '.$row_liste_respo["fonction"];

$contenu = "bonjour "; // get the contents of the output buffer
 //  clean (erase) the output buffer and turn off output buffering
$mail->msgHTML($contenu);
$contenu = trim($contenu);

  if(!isset($_GET["send"]))
  {
    //send the message, check for errors
    if (!$mail->send()) {
      //if (!isset($recipients)) {
      //echo "Mailer Error: " . $mail->ErrorInfo;
      echo "<h1 align='center'>Impossible d'envoyer le mail à l'adress '$email' !</h1>
      <h1 align='center'>Une erreur s'est produite !</h1>";
    }
    else
    {
      $msg_sent = true;
      echo "<h1 align='center'>Message envoy&eacute;!</h1>";

    }
  }


?>