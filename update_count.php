<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////

session_start();
include_once 'system/configuration.php';
$config = new Config;

if (strchr($_SERVER["HTTP_REFERER"],"http://ruche-pask.org/")!="") {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
header('Content-Type: text/html; charset=UTF-8');

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sync = "SELECT count(id) as nbr FROM ".$database_connect_prefix."ruche_sync WHERE code is not null ";
$sync = mysql_query($query_sync, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_sync = mysql_fetch_assoc($sync);
//$totalRows_sync = mysql_num_rows($sync);
if(!empty($row_sync["nbr"])) echo $row_sync["nbr"]; else echo "";
?>