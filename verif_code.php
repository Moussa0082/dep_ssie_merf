<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET["t"]) && !empty($_GET["t"]) && isset($_GET["w"]) && !empty($_GET["w"]))
{
  $table = "`".$database_connect_prefix.$_GET["t"]."`"; $where = str_replace(" and","' and",str_replace("=","='",$_GET["w"]));
 // mysql_select_db($database_pdar_connexion, $pdar_connexion);
 // $query_liste_verif = "SELECT id_personnel FROM $table where $where' "; 
    $query_liste_verif = "SELECT id_personnel FROM $table where $where' "; 
  try{
    $row_liste_verif = $pdar_connexion->prepare($query_liste_verif);
    $row_liste_verif->execute();
    $row_liste_verif = $row_liste_verif ->fetchAll();
    $totalRows_liste_verif = $row_liste_verif->rowCount();
}catch(Exception $e){ die("has-error|<span class='label label-danger'>".mysql_error()."</span><input type='text' class='required' style='visibility: hidden;' value=''>"); }

  if($totalRows_liste_verif>0) echo "has-error|<span class='label label-danger'>Existe !</span><input type='text' class='required' style='visibility: hidden;' value=''>"; else echo "has-success|<span class='label label-success'>Valable</span>";
}
?>