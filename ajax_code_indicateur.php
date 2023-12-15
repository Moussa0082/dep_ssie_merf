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

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "SELECT nombre FROM ".$database_connect_prefix."niveau_config WHERE projet='".$_SESSION["clp_projet"]."' LIMIT 1";
  $entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);
  $niveau = $row_entete["nombre"];

/*if(isset($_POST['query'])) $q = $_POST['query']; else $q = "";
if(!empty($q))
{  */
    //if(!isset($_GET['annee'])) $annee=date("Y"); else $annee=intval($_GET['annee']);
  $where = "niveau=$niveau"; $resultat = "";
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
 // $query_liste_activite_1 = "SELECT * FROM ".$database_connect_prefix."indicateur_cadre_resultat WHERE $where and ".$_SESSION["clp_where"]."  and code not in (select code_activite_ptba from ".$database_connect_prefix."ptba where annee=$annee and projet='".$_SESSION["clp_projet"]."')";
    $query_liste_indicateur = "SELECT * FROM ".$database_connect_prefix."indicateur_cadre_resultat";
  $liste_indicateur  = mysql_query($query_liste_indicateur , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_indicateur  = mysql_fetch_assoc($liste_indicateur );
  $totalRows_liste_indicateur  = mysql_num_rows($liste_indicateur );
  if($totalRows_liste_indicateur>0)
  {
    $resultat = "[";
    do
    {
      $code = $row_liste_indicateur["code_indicateur_cr"];
      $title = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row_liste_indicateur["intitule_indicateur_cr"]);
      $resultat .= '{"id":"'.$code.'","title":"'.$code.': '.$title.'"},';
    }while($row_liste_indicateur  = mysql_fetch_assoc($liste_indicateur));
    $resultat = substr($resultat, 0, -1)."]";
  }
  echo $resultat;
//}
?>