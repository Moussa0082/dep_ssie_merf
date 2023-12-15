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

if(isset($_GET["id"]) && intval($_GET["id"])>0)
{
  $id=intval($_GET["id"]); $id_s=intval($_GET["id_s"]);
  if($id==1)
  { //resultat intermediare
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $query_entete = "SELECT type FROM ".$database_connect_prefix."cadre_config WHERE ".$_SESSION["clp_where"]." LIMIT 1";
    $entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $row_entete  = mysql_fetch_assoc($entete);
    //$totalRows_entete  = mysql_num_rows($entete);
    $data1 = array();  $where = "";
    $data = explode(',',$row_entete["type"]);
    $i=1; foreach($data as $a){ if($a==1) array_push($data1,$i); $i++; }
    if(count($data1)>0) $where = " and niveau in (".implode(',',$data1).")";

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $query_liste_activite_1 = "SELECT * FROM ".$database_connect_prefix."cadre_logique WHERE ".$_SESSION["clp_where"]." $where ORDER BY code ASC";
    $liste_activite_1 = mysql_query($query_liste_activite_1 , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $row_liste_activite_1 = mysql_fetch_assoc($liste_activite_1);
    $totalRows_liste_activite_1 = mysql_num_rows($liste_activite_1);
  }
  elseif($id==4)
  { //Indicateur
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $query_liste_activite_1 = "SELECT code_ref_ind as code, intitule_ref_ind as intitule FROM ".$database_connect_prefix."referentiel_indicateur WHERE type_ref_ind=1 ORDER BY intitule_ref_ind ASC";
    $liste_activite_1 = mysql_query($query_liste_activite_1 , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $row_liste_activite_1 = mysql_fetch_assoc($liste_activite_1);
    $totalRows_liste_activite_1 = mysql_num_rows($liste_activite_1);
  }
  if($totalRows_liste_activite_1>0)
  { ?>
    <option value="">Selectionnez</option>
    <?php do { ?>
    <option value="<?php echo $row_liste_activite_1['code']; ?>" <?php if (isset($_GET["id_s"]) && $row_liste_activite_1['code']==$id_s) {echo "SELECTED";} ?>><?php echo $row_liste_activite_1['intitule']; ?></option>
  <?php }while($row_liste_activite_1  = mysql_fetch_assoc($liste_activite_1));
  }
}

?>