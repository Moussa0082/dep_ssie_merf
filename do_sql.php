<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();  $path = './';
include_once $path.'system/configuration.php';
$config = new Config;
       /*
if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
} */
include_once $path.$config->sys_folder . "/database/db_connexion.php";
//".$database_connect_prefix."

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = $_get["sql"];
$act  = mysql_query($query_act , $pdar_connexion) or die(mysql_error());
$row_act  = mysql_fetch_assoc($act);
echo "OKAY";
?>
