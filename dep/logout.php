<?php
   ///////////////////////////////////////////////
  /*                 SSE                       */
 /*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////

  session_start();
  include_once 'system/configuration.php';
  $config = new Config;
  include_once $config->sys_folder."/database/db_connexion.php";
  /*
  include_once $config->sys_folder."/database/credential.php";
  include_once $config->sys_folder."/database/essentiel.php";
  */
$page = (isset($_GET["page"]))?$_GET["page"]:"./";
if(isset($_SESSION['clp_id']))
{
  $date=date("Y-m-d H:i:s"); $personnel=$_SESSION["clp_n"];
  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."connecter SET date_deconnexion='$date' WHERE personnel='$personnel' and date_deconnexion='0000-00-00 00:00:00'");
    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }

  foreach($_SESSION as $a=>$b)
  {
    $_SESSION[$a] = null;
    //session_unset($_SESSION[$a]);
  }
  session_unset();
  if(isset($_GET["identifiant"]))
  header(sprintf("Location: %s", "$page?identifiant=".$_GET["identifiant"]));
  else header(sprintf("Location: %s", "$page?success=true")); exit;
}
else {header(sprintf("Location: %s", "$page")); exit;}
?>