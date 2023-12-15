<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
header('Content-Type: text/html; charset=UTF-8');

session_start();
$path=(isset($_GET['path']))?$_GET['path']:"./"; //$_POST['query']="o";
include_once $path.'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}

include_once $path.$config->sys_folder . "/database/db_connexion.php";

/*if(isset($_POST['query'])) $q = $_POST['query']; else $q = "";
if(!empty($q))
{  */
  $where = "niveau=5"; $resultat = "";
  mysql_select_db($database_pdar_connexion, $pdar_connexion); 
  $query_liste_activite_1 = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE $where";
  $liste_activite_1  = mysql_query($query_liste_activite_1 , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_activite_1  = mysql_fetch_assoc($liste_activite_1 );
  $totalRows_liste_activite_1  = mysql_num_rows($liste_activite_1 );
  if($totalRows_liste_activite_1>0)
  {
    $resultat = "[";
    do
    {
      $code = $row_liste_activite_1["code"];
      $title = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row_liste_activite_1["intitule"]);
      $resultat .= '{"id":"'.$code.'","title":"'.$code.': '.$title.'"},';
    }while($row_liste_activite_1  = mysql_fetch_assoc($liste_activite_1));
    $resultat = substr($resultat, 0, -1)."]";
  }
  echo $resultat;
//}
?>