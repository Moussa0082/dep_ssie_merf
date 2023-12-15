<?php                                                                          
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";

$personnel=$_SESSION["clp_id"];
$date=date("Y-m-d"); $id = $_POST["id"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_agenda = "SELECT debut,fin FROM ".$database_connect_prefix."agenda_perso WHERE valider=0 and id_agenda=".GetSQLValueString($id, "int");
$liste_agenda  = mysql_query_ruche($query_liste_agenda , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_agenda  = mysql_fetch_assoc($liste_agenda);
$totalRows_liste_agenda  = mysql_num_rows($liste_agenda);

if(!isset($_GET["fin"]))
{ //drag
  if($totalRows_liste_agenda>0)
  {
    $d = intval($_POST['day']); $m = intval($_POST['min']); $date1 = strtotime($row_liste_agenda["debut"]);
    $date2 = strtotime($row_liste_agenda["fin"]);
    $date1 += (60*60*24*$d)+(60*$m); $date2 += (60*60*24*$d)+(60*$m);
  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."agenda_perso SET debut='".date("Y-m-d H:i:s",$date1)."', ".(($_POST["allday"]=="true")?"all_day=1, fin='0000-00-00 00:00:00'":"all_day=0, fin='".date("Y-m-d H:i:s",$date2)."'").", modifier_par='$personnel', modifier_le='$date' WHERE id_agenda=%s",
  	   GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  }
}
else
{ //resize
  if($totalRows_liste_agenda>0)
  {
    $d = strtotime($_POST['date']); $date1 = strtotime($row_liste_agenda["fin"]);
    $date1 += $d;
  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."agenda_perso SET fin='".date("Y-m-d H:i:s",$date1)."', modifier_par='$personnel', modifier_le='$date' WHERE id_agenda=%s",
  	   GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  }
}
if($Result1) echo "OK"; else echo "NO";
?>