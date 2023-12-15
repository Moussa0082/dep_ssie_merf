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

include_once $config->sys_folder . "/database/db_connexion.php";


echo "<h1>Section alerte de la base de donn&eacute;es</h1>";
/*include_once 'system/configuration.php';
if(!isset($config)) $config = new Config;
header('Content-Type: text/html; charset=UTF-8'); */
if(!isset($config)) $config = new Config;

//include_once $config->sys_folder . "/database/db_connexion.php";

if(!class_exists('PHPMailer'))
require 'phpmailer/PHPMailerAutoload.php';

//projet
$query_liste_projet = "SELECT * FROM ".$database_connect_prefix."projet order by annee_debut asc";
   try{
  $liste_projet = $pdar_connexion->prepare($query_liste_projet);
    $liste_projet->execute();
    $row_liste_projet = $liste_projet ->fetchAll();
    $totalRows_liste_projet = $liste_projet->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$tableau_projet = $tableau_projet1 = array();
if($totalRows_liste_projet>0){ foreach($row_liste_projet as $row_liste_projet){  
$tableau_projet[$row_liste_projet["code_projet"]] = array("sigle"=>$row_liste_projet["sigle_projet"],"intitule"=>$row_liste_projet["intitule_projet"]);
$tableau_projet1[] = $row_liste_projet["sigle_projet"];
} }

$query_liste_respo = "SELECT distinct fonction, N, id_personnel, email, titre, nom, prenom, projet, structure FROM ".$database_connect_prefix."personnel where fonction!='Coordinateur'";
   try{
  $liste_respo = $pdar_connexion->prepare($query_liste_respo);
    $liste_respo->execute();
    $row_liste_respo = $liste_respo ->fetchAll();
    $totalRows_liste_respo = $liste_respo->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_liste_respo>0){ foreach($row_liste_respo as $row_liste_respo){ 
$_GET["fonction"] = $row_liste_respo["fonction"]; $_GET["id_personnel"] = $row_liste_respo["id_personnel"];
$_GET["id"] = $row_liste_respo["N"];
$_GET["nom"] = $row_liste_respo["titre"]." ".$row_liste_respo["nom"]." ".$row_liste_respo["prenom"];
$email = $_GET["email"]=$row_liste_respo["email"];
$_GET["projet"] = $row_liste_respo["projet"]; $_GET["structure"] = $row_liste_respo["structure"];
if(filter_var(trim($email), FILTER_VALIDATE_EMAIL)){ //Valide email

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
$mail->Host = 'mail29.lwspanel.com';
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
$mail->Username = "paiaj@ruche-demo.org";
//Password to use for SMTP authentication
$mail->Password = "gE5@Bt*jw*GXz_r";
//Set who the message is to be sent from
$mail->setFrom('paiaj@ruche-demo.org', 'SISE PAIAJ');
//Set list of adresse to be sent to
//$email = "foussyni.sangare@gmail.com";
$recipients = array();
$recipients[$email] = "";
$cc = array();
//$cc['kone.lacine@gmail.com'] = "kone.lacine@gmail.com";
$mail->addAddress($email/*, $row_liste_respo["nom"]." ".$row_liste_respo["prenom"]*/);
/**/$mail->AddBCC("kone.lacine@gmail.com");
$mail->addCC("hilaire.agbogan@yahoo.fr");
$mail->AddBCC("klsanou@gmail.com");
/*//$mail->addCC("sanogozp@yahoo.fr");*/
$mail->Subject = 'Notification du SISE - '.$row_liste_respo["fonction"];
$sample = "./alert_mail_content.php";
ob_start(); // turn on output buffering
include($sample);
$contenu = ob_get_contents(); // get the contents of the output buffer
ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering
$mail->msgHTML($contenu);
$contenu = trim($contenu);
if(!empty($contenu))
{
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
  echo "<ul>";
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
  echo $contenu;
  echo "<br>";
}
} else echo "<h2>email '$email' est invalide!</h2>";
}
}
unset($config);


//Bailleur DANO

/*$path = "./";
include_once $path.'system/configuration.php';
if(!isset($config)) $config = new Config; */

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SHOW tables";
$liste_cp = mysql_query($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);

$cp_array=array();
if($totalRows_liste_cp>0) {
do {  if(strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")=="" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="fiche_config"){  $cp_array[]=$row_liste_cp["Tables_in_$database_pdar_connexion"];
}
} while ($row_liste_cp = mysql_fetch_assoc($liste_cp));
$rows = mysql_num_rows($liste_cp);
if($rows > 0) {
mysql_data_seek($liste_cp, 0);
$row_liste_cp = mysql_fetch_assoc($liste_cp);
}}

$t=0; if(!in_array("partenaire",$cp_array)) $t=1;

if($t==1)
{
  echo mysql_error_show_message("Certaines table(s) sont introuvable dans la base de donn&eacute;e !<br /> Impossible de faire la consolidation !","./");
  exit;
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = " SELECT adresse_mail FROM ".$database_connect_prefix."partenaire where dno=1 and adresse_mail is not null and adresse_mail<>''";
$act = mysql_query($query_act, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_act = mysql_fetch_assoc($act);
$totalRows_act = mysql_num_rows($act);

if($totalRows_act>0)
{
  echo "<h1>Section Log DANO Bailleur</h1>";
  do{
  if(!class_exists('PHPMailer'))
  require $path.'phpmailer/PHPMailerAutoload.php';

  //projet
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_projet = "SELECT * FROM ".$database_connect_prefix."projet ";
  $liste_projet = mysql_query($query_liste_projet , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_projet = mysql_fetch_assoc($liste_projet);
  $totalRows_liste_projet = mysql_num_rows($liste_projet);
  $tableau_projet = array();
  if($totalRows_liste_projet>0){ do{
  $tableau_projet[$row_liste_projet["code_projet"]] = array("sigle"=>$row_liste_projet["sigle_projet"],"intitule"=>$row_liste_projet["intitule_projet"]);
  }while($row_liste_projet  = mysql_fetch_assoc($liste_projet));  }

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_bailleur = "SELECT code, sigle, definition FROM ".$database_connect_prefix."partenaire WHERE dno=1 ";
  $liste_bailleur = mysql_query($query_liste_bailleur, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
  $totalRows_liste_bailleur = mysql_num_rows($liste_bailleur);
  $destinateur_array = array();
  if($totalRows_liste_bailleur>0){ do{
    $destinateur_array[$row_liste_bailleur["code"]] = $row_liste_bailleur["sigle"];
  }while($row_liste_bailleur = mysql_fetch_assoc($liste_bailleur)); }
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  //$id=$row_liste_conv['id_dno'];  mysql_error_show_message(mysql_error())

  $email = $row_act["adresse_mail"];
  if(filter_var(trim($email), FILTER_VALIDATE_EMAIL)){ //Valide email

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
  $mail->Host = 'mail.ruche-pnf.org';
  // use
  // $mail->Host = gethostbyname('smtp.gmail.com');
  // if your network does not support SMTP over IPv6
  //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
  $mail->Port = 25;
  //Set the encryption system to use - ssl (deprecated) or tls
  //$mail->SMTPSecure = 'tls';
  //Whether to use SMTP authentication
  $mail->SMTPAuth = true;
  //Username to use for SMTP authentication - use full email address for gmail
  $mail->Username = "info@ruche-pnf.org ";
  //Password to use for SMTP authentication
  $mail->Password = "123pnf123";
  //Set who the message is to be sent from
  $mail->setFrom('info@ruche-pnf.org ', 'PNF ALERTES');
  //Set list of adresse to be sent to
  //$email = "foussyni.sangare@gmail.com";
  $mail->addAddress($email);
  /*$mail->addCC("fousseyni.sangare@gmail.com");
  $mail->addCC("tidianecamara50@gmail.com");
  //$mail->addCC("kone.lacine@gmail.com");
  $mail->addCC("sanogozp@yahoo.fr");*//*
  $mail->Subject = 'Notification du PNF';
  $sample = "./alert_mail_bailleur_content.php";
  ob_start(); // turn on output buffering
  include($sample);
  $contenu = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering
  $mail->msgHTML($contenu);
  $contenu = trim($contenu);
  if(!empty($contenu) && $do_not)
  {
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
    echo "<ul>";
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
    echo $contenu;
    echo "<br>";
  }
  } else echo "<h2>email '$email' est invalide!</h2>";
  }while($row_act = mysql_fetch_assoc($act));
  unset($config);
}   */
?>