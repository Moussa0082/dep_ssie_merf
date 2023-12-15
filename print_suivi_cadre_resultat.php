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



 if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){

header("Content-Type: application/vnd.ms-excel");

header("Expires: 0");

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header("content-disposition: attachment;filename=Cadre_resultat.xls"); }

else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){

header("Content-Type: application/vnd.ms-word");

header("Expires: 0");

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header("content-disposition: attachment;filename=Cadre_resultat.doc"); }

?>

<?php





$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {

  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);

}

$mode_calcul = array("SOMME"=>"SUM","MOYENNE"=>"AVG","COMPTER"=>"COUNT","COMPTAGE DISTINCTEMENT"=>"COUNT","COMPTER TOUT"=>"COUNT");
$array_indic = array("OUI/NON","texte");


$query_liste_volet = "SELECT * FROM ".$database_connect_prefix."cadre_logique WHERE projet='".$_SESSION["clp_projet"]."'  ORDER BY code ASC";
      	try{
    $liste_volet = $pdar_connexion->prepare($query_liste_volet);
    $liste_volet->execute();
    $row_liste_volet = $liste_volet ->fetchAll();
    $totalRows_liste_volet = $liste_volet->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableau_niveau_volet = array(); $nivo="";
if($totalRows_liste_volet>0){ 
  foreach($row_liste_volet as $row_liste_volet){
  if($row_liste_volet["niveau"]==1) $nivo="Impacts"; elseif($row_liste_volet["niveau"]==2) $nivo="Effets"; elseif($row_liste_volet["niveau"]==3) $nivo="Produits (".$row_liste_volet["code"].")";
  $tableau_volet[$row_liste_volet["code"]]=$nivo.": ".$row_liste_volet["intitule"];
   $tableau_niveau_volet[$row_liste_volet["code"]]=$row_liste_volet["parent"];
   } }
 
//Cible indicateur à sommer
$query_cible_indicateur = "SELECT indicateur_produit, annee, sum(valeur_cible) as valeur_cible, avg(valeur_cible) as valeur_ciblem  FROM   ".$database_connect_prefix."cible_cmr_produit group by annee, indicateur_produit";
      	try{
    $cible_indicateur = $pdar_connexion->prepare($query_cible_indicateur);
    $cible_indicateur->execute();
    $row_cible_indicateur = $cible_indicateur ->fetchAll();
    $totalRows_cible_indicateur = $cible_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$cible_array = array(); $ciblem_array = array();
if($totalRows_cible_indicateur>0){
 foreach($row_cible_indicateur as $row_cible_indicateur){
  $cible_array[$row_cible_indicateur["indicateur_produit"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_cible"];
  $ciblem_array[$row_cible_indicateur["indicateur_produit"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_ciblem"];
   } }

$query_liste_code_ref = sprintf("SELECT code_ref_ind, id_ref_ind, fonction_agregat FROM ".$database_connect_prefix."indicateur_cmr WHERE projet=%s order by code_ref_ind",
   // GetSQLValueString($_SESSION['clp_structure'], "text"),
    GetSQLValueString($_SESSION['clp_projet'], "text"));
  	try{
    $liste_code_ref = $pdar_connexion->prepare($query_liste_code_ref);
    $liste_code_ref->execute();
    $row_liste_code_ref = $liste_code_ref ->fetchAll();
    $totalRows_liste_code_ref = $liste_code_ref->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_code_ref_array = array(); $liste_agregat_ref_array = array();
 foreach($row_liste_code_ref as $row_liste_code_ref){
 $liste_code_ref_array[$row_liste_code_ref["id_ref_ind"]] = $row_liste_code_ref["code_ref_ind"];
 $liste_agregat_ref_array[$row_liste_code_ref["id_ref_ind"]] = $row_liste_code_ref["fonction_agregat"];
}

$query_liste_indicateur_calcul = sprintf("SELECT indicateur_ref, code_ref_ind, code_ref_ind, intitule_ref_ind FROM ".$database_connect_prefix."indicateur_cmr, ".$database_connect_prefix."calcul_indicateur_simple_ref WHERE FIND_IN_SET( code_ref_ind, indicateur_simple ) and fonction_agregat = 'Unique'  and projet=%s ORDER BY indicateur_ref",
    //GetSQLValueString($_SESSION['clp_structure'], "text"),
    GetSQLValueString($_SESSION['clp_projet'], "text"));
	try{
    $liste_indicateur_calcul = $pdar_connexion->prepare($query_liste_code_ref);
    $liste_indicateur_calcul->execute();
    $row_liste_indicateur_calcul = $liste_indicateur_calcul ->fetchAll();
    $totalRows_liste_indicateur_calcul = $liste_indicateur_calcul->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_indicateur_simple_array=array();
if($totalRows_liste_indicateur_calcul>0){
 foreach($row_liste_indicateur_calcul as $row_liste_indicateur_calcul){
$liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']]=(isset($liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']]))?$liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']].$row_liste_indicateur_calcul['code_ref_ind'].",":$row_liste_indicateur_calcul['code_ref_ind'].",";
} }

//echo "je suis la";

$query_liste_ind_ratio = "SELECT indicateur_ref, numerateur, denominateur, coefficient FROM ".$database_connect_prefix."ratio_indicateur_ref order by indicateur_ref";
	try{
    $liste_ind_ratio = $pdar_connexion->prepare($query_liste_ind_ratio);
    $liste_ind_ratio->execute();
    $row_liste_ind_ratio = $liste_ind_ratio ->fetchAll();
    $totalRows_liste_ind_ratio = $liste_ind_ratio->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_num_ratio_array = array(); $liste_deno_ratio_array = array();
 foreach($row_liste_ind_ratio as $row_liste_ind_ratio){
 $liste_num_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = $row_liste_ind_ratio["numerateur"];
  $liste_deno_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = ($row_liste_ind_ratio["denominateur"]==-1)?$row_liste_ind_ratio["coefficient"]." / 1)":$row_liste_ind_ratio["denominateur"];
}
//les nons unique
$query_liste_calcule_ind = sprintf("SELECT * FROM ".$database_connect_prefix."calcul_indicateur_simple_ref ");
	try{
    $liste_calcule_ind = $pdar_connexion->prepare($query_liste_calcule_ind);
    $liste_calcule_ind->execute();
    $row_liste_calcule_ind = $liste_calcule_ind ->fetchAll();
    $totalRows_liste_calcule_ind = $liste_calcule_ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$indicateur_calcule = array();
if($totalRows_liste_calcule_ind>0){
 foreach($row_liste_calcule_ind as $row_liste_calcule_ind){
  $les_ind = explode(",",$row_liste_calcule_ind["indicateur_simple"]);
  $formule=$row_liste_calcule_ind["formule_indicateur_simple"];

  if($formule=="Somme"){ foreach($les_ind as $idindicateur){ for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ if(!isset($indicateur_calcule[$row_liste_calcule_ind["indicateur_ref"]][$i])) $indicateur_calcule[$row_liste_calcule_ind["indicateur_ref"]][$i]=0;  if(isset($cible_array[$idindicateur][$i])) $indicateur_calcule[$row_liste_calcule_ind["indicateur_ref"]][$i]+=(isset($liste_agregat_ref_array[$idindicateur]) && $liste_agregat_ref_array[$idindicateur]=="Moyenne")?$ciblem_array[$idindicateur][$i]:$cible_array[$idindicateur][$i]; } /*echo $idindicateur." - ";*/ } }
  elseif($formule=="Moyenne"){ foreach($les_ind as $idindicateur){ for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ if(!isset($indicateur_calcule[$row_liste_calcule_ind["indicateur_ref"]][$i])) $indicateur_calcule[$row_liste_calcule_ind["indicateur_ref"]][$i]=0;  if(isset($cible_array[$idindicateur][$i])) $indicateur_calcule[$row_liste_calcule_ind["indicateur_ref"]][$i]+=(isset($liste_agregat_ref_array[$idindicateur]) && $liste_agregat_ref_array[$idindicateur]=="Moyenne")?$ciblem_array[$idindicateur][$i]:$cible_array[$idindicateur][$i]; } /*echo $idindicateur." - ";*/ }
for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){
if(isset($indicateur_calcule[$row_liste_calcule_ind["indicateur_ref"]][$i]) && count($les_ind)>0) $indicateur_calcule[$row_liste_calcule_ind["indicateur_ref"]][$i]=$indicateur_calcule[$row_liste_calcule_ind["indicateur_ref"]][$i]/(count($les_ind));
}
} } }

$query_liste_ind_ratio = "SELECT indicateur_ref, numerateur, denominateur, coefficient FROM ".$database_connect_prefix."ratio_indicateur_ref order by indicateur_ref";
		try{
    $liste_ind_ratio = $pdar_connexion->prepare($query_liste_ind_ratio);
    $liste_ind_ratio->execute();
    $row_liste_ind_ratio = $liste_ind_ratio ->fetchAll();
    $totalRows_liste_ind_ratio = $liste_ind_ratio->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_num_ratio_array = array(); $liste_deno_ratio_array = array(); $liste_coef_ratio_array = array();
 foreach($row_liste_ind_ratio as $row_liste_ind_ratio){
    $liste_coef_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = $row_liste_ind_ratio["coefficient"];
    $liste_num_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = $row_liste_ind_ratio["numerateur"];
    $liste_deno_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = $row_liste_ind_ratio["denominateur"];
}
/////suivi

$query_liste_composante = sprintf("SELECT * FROM ".$database_connect_prefix."indicateur_cmr, ".$database_connect_prefix."indicateur_cadre_resultat
 WHERE code_indicateur_cr=resultat  and ".$database_connect_prefix."indicateur_cmr.projet=%s ORDER BY niveau, resultat, code_ref_ind",
    GetSQLValueString($_SESSION['clp_projet'], "text"));
		try{
    $liste_composante = $pdar_connexion->prepare($query_liste_composante);
    $liste_composante->execute();
    $row_liste_composante = $liste_composante ->fetchAll();
    $totalRows_liste_composante = $liste_composante->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$liste_indicateur_mode_array = $liste_indicateur_id_array = array();
if($totalRows_liste_composante > 0) { 
 foreach($row_liste_composante as $row_liste_composante1){
 $liste_indicateur_mode_array[$row_liste_composante1['code_ref_ind']] = $row_liste_composante1['fonction_agregat'];
 $liste_indicateur_id_array[$row_liste_composante1['code_ref_ind']] = $row_liste_composante1['id_ref_ind'];
}}



$query_liste_indicateur_calcul = sprintf("SELECT indicateur_ref, id_ref_ind, code_ref_ind, intitule_ref_ind FROM ".$database_connect_prefix."indicateur_cmr, ".$database_connect_prefix."calcul_indicateur_simple_ref WHERE FIND_IN_SET( code_ref_ind, indicateur_simple ) and fonction_agregat = 'Unique' ORDER BY indicateur_ref",
    GetSQLValueString($_SESSION['clp_projet'], "text"));
	try{
    $liste_indicateur_calcul = $pdar_connexion->prepare($query_liste_indicateur_calcul);
    $liste_indicateur_calcul->execute();
    $row_liste_indicateur_calcul = $liste_indicateur_calcul ->fetchAll();
    $totalRows_liste_indicateur_calcul = $liste_indicateur_calcul->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_indicateur_calcul = "SELECT indicateur_ref, id_ref_ind, code_ref_ind, intitule_ref_ind FROM indicateur_cmr, calcul_indicateur_simple_ref
WHERE FIND_IN_SET( id_ref_ind, indicateur_simple ) and fonction_agregat = 'Unique' ORDER BY indicateur_ref";
	try{
    $liste_indicateur_calcul = $pdar_connexion->prepare($query_liste_indicateur_calcul);
    $liste_indicateur_calcul->execute();
    $row_liste_indicateur_calcul = $liste_indicateur_calcul ->fetchAll();
    $totalRows_liste_indicateur_calcul = $liste_indicateur_calcul->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$liste_indicateur_simple_array=array();
if($totalRows_liste_indicateur_calcul>0){
 foreach($row_liste_indicateur_calcul as $row_liste_indicateur_calcul){
$liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']]=(isset($liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']]))?$liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']].$row_liste_indicateur_calcul['code_ref_ind'].",":$row_liste_indicateur_calcul['code_ref_ind'].",";} }

$query_liste_ind_ratio = "SELECT indicateur_ref, numerateur, denominateur, coefficient FROM ratio_indicateur_ref order by indicateur_ref";
	try{
    $liste_ind_ratio = $pdar_connexion->prepare($query_liste_code_ref);
    $liste_ind_ratio->execute();
    $row_liste_ind_ratio = $liste_ind_ratio ->fetchAll();
    $totalRows_liste_ind_ratio = $liste_ind_ratio->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$liste_num_ratio_array = array();
$liste_deno_ratio_array = array();
 foreach($row_liste_ind_ratio as $row_liste_ind_ratio){
 $liste_num_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = $row_liste_ind_ratio["numerateur"];
  $liste_deno_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = ($row_liste_ind_ratio["denominateur"]==-1)?$row_liste_ind_ratio["coefficient"]." / 1)":$row_liste_ind_ratio["denominateur"];
}


$query_liste_code_ref = "SELECT code_ref_ind, id_ref_ind FROM indicateur_cmr order by code_ref_ind";
	try{
    $liste_code_ref = $pdar_connexion->prepare($query_liste_code_ref);
    $liste_code_ref->execute();
    $row_liste_code_ref = $liste_code_ref ->fetchAll();
    $totalRows_liste_code_ref = $liste_code_ref->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$liste_code_ref_array = array();
 foreach($row_liste_code_ref as $row_liste_code_ref){
 $liste_code_ref_array[$row_liste_code_ref["id_ref_ind"]] = $row_liste_code_ref["code_ref_ind"];
}


//Unite indicateur
$query_liste_unite = "SELECT * FROM ".$database_connect_prefix."unite_indicateur ";
	try{
    $liste_unite = $pdar_connexion->prepare($query_liste_unite);
    $liste_unite->execute();
    $row_liste_unite = $liste_unite ->fetchAll();
    $totalRows_liste_unite = $liste_unite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_unite_array = array();
 foreach($row_liste_unite as $row_liste_unite){
 $liste_unite_array[$row_liste_unite["id_unite"]] = $row_liste_unite["unite"];
}

//Valeur de suivi
$cible_val_array = $cible_val_txt_array = array();

$query_cible_indicateur = "SELECT s.indicateur_cr, s.annee, sum(s.valeur_suivi) as valeur_suivi, s.valeur_txt, r.unite_cmr FROM   ".$database_connect_prefix."suivi_indicateur_cmr s, ".$database_connect_prefix."indicateur_cmr r WHERE s.projet='".$_SESSION['clp_projet']."' and r.id_ref_ind = s.indicateur_cr group by s.annee, s.indicateur_cr";
	try{
    $cible_indicateur = $pdar_connexion->prepare($query_cible_indicateur);
    $cible_indicateur->execute();
    $row_cible_indicateur = $cible_indicateur ->fetchAll();
    $totalRows_cible_indicateur = $cible_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if($totalRows_cible_indicateur>0){
 foreach($row_cible_indicateur as $row_cible_indicateur){
 if(isset($liste_unite_array[$row_cible_indicateur["unite_cmr"]])) $unite_ind=$liste_unite_array[$row_cible_indicateur["unite_cmr"]]; else $unite_ind="Nbre";
   if(!in_array(strtoupper($unite_ind),$array_indic))
   {
     if(!isset($cible_val_array[$row_cible_indicateur["indicateur_cr"]][$row_cible_indicateur["annee"]])) $cible_val_array[$row_cible_indicateur["indicateur_cr"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_suivi"];
     else $cible_val_array[$row_cible_indicateur["indicateur_cr"]][$row_cible_indicateur["annee"]]+=$row_cible_indicateur["valeur_suivi"];
   }
   else
   {
     $cible_val_txt_array[$row_cible_indicateur["indicateur_cr"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_txt"];
   }
}}

//print_r($cible_val_txt_array);
//exit;
 //cmr


  //sygri
 
  //PTBA

//WHERE ".$_SESSION["clp_where"]."


//print_r($indicateur_dynamique);

//exit;   

//Type indicateur
$query_liste_type = "SELECT * FROM ".$database_connect_prefix."indicateur_cadre_resultat";
	try{
    $liste_type = $pdar_connexion->prepare($query_liste_type);
    $liste_type->execute();
    $row_liste_type = $liste_type ->fetchAll();
    $totalRows_liste_type = $liste_type->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_type_array = array();
 foreach($row_liste_type as $row_liste_type){
 $liste_type_array[$row_liste_type["code_indicateur_cr"]] = $row_liste_type["niveau"];
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<?php if(!isset($_GET["down"])){  ?>

<head>

  <title><?php print $config->sitename; ?></title>

  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />

  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

  <meta name="keywords" content="<?php print $config->MetaKeys; ?>" />

  <meta name="description" content="<?php print $config->MetaDesc; ?>" />

  <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->

  <meta name="author" content="<?php print $config->MetaAuthor; ?>" />

  <!--<meta charset="utf-8">-->

  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>



  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->

  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder; ?>/login.css" rel="stylesheet" type="text/css"/>

  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">

  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->

  <!--[if IE 8]><link href="<?php print $config->theme_folder; ?>/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->

  <link href='<?php print $config->theme_folder; ?>/css.css' rel='stylesheet' type='text/css'>

  <!--<link rel="stylesheet" href="<?php print $config->theme_folder; ?>/table.css" type="text/css" > -->

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>

  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>

  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/lodash.compat.min.js"></script>

  <!--[if lt IE 9]><script src="<?php print $config->script_folder; ?>/libs/html5shiv.js"></script><![endif]-->

  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>

  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>

  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>

  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/breakpoints.js"></script>

  <script type="text/javascript" src="plugins/respond/respond.min.js"></script>

  <script type="text/javascript" src="plugins/cookie/jquery.cookie.min.js"></script>

  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.min.js"></script>

  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>

  <!--[if lt IE 9]><script type="text/javascript" src="plugins/flot/excanvas.min.js"></script><![endif]-->

  <!--<script type="text/javascript" src="plugins/sparkline/jquery.sparkline.min.js"></script>

  <script type="text/javascript" src="plugins/flot/jquery.flot.min.js"></script>

  <script type="text/javascript" src="plugins/flot/jquery.flot.tooltip.min.js"></script>

  <script type="text/javascript" src="plugins/flot/jquery.flot.resize.min.js"></script>

  <script type="text/javascript" src="plugins/flot/jquery.flot.time.min.js"></script>

  <script type="text/javascript" src="plugins/flot/jquery.flot.growraf.min.js"></script>

  <script type="text/javascript" src="plugins/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

  <script type="text/javascript" src="plugins/daterangepicker/moment.min.js"></script>

  <script type="text/javascript" src="plugins/daterangepicker/daterangepicker.js"></script>-->

  <script type="text/javascript" src="plugins/blockui/jquery.blockUI.min.js"></script>

  <script type="text/javascript" src="plugins/pickadate/picker.js"></script>

  <script type="text/javascript" src="plugins/pickadate/picker.date.js"></script>

  <script type="text/javascript" src="plugins/pickadate/picker.time.js"></script>

  <script type="text/javascript" src="plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>

  <script type="text/javascript" src="plugins/fullcalendar/fullcalendar.min.js"></script>

  <script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>

  <script type="text/javascript" src="plugins/noty/layouts/top.js"></script>

  <script type="text/javascript" src="plugins/noty/themes/default.js"></script>

  <script type="text/javascript" src="plugins/uniform/jquery.uniform.min.js"></script>

  <script type="text/javascript" src="plugins/select2/select2.min.js"></script>

  <script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>

  <script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>

  <script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/app.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/plugins.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/plugins.form-components.js"></script>

<!--

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/custom.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/pages_calendar.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_filled_blue.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_simple.js"></script>-->

 <script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>

 <script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder; ?>/login.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder; ?>/myscript.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/ui_general.js"></script>

  <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init();$("#container").addClass("sidebar-closed");});</script>

</head>

<?php } ?>

<body>

 <header class="header navbar navbar-fixed-top" role="banner">

    <?php if(!isset($_GET["down"])) include_once("includes/header.php"); ?>

 </header>

<div id="container">

    <div id="sidebar" class="sidebar-fixed">

        <div id="sidebar-content">

            <?php if(!isset($_GET["down"])) include_once("includes/menu_top.php"); ?>

        </div>

        <div id="divider" class="resizeable"></div>

    </div>



    <div id="content">

        <div class="container">

            <div class="crumbs">

                <?php if(!isset($_GET["down"])) include_once("includes/sous_menu.php"); ?>

            </div>

        <div class="page-header">

            <div class="p_top_5">

<!-- Site contenu ici -->

<style>#sp_hr {margin:0px; }

.r_float{float: right;}

.Style11 { font-weight: bold;color: #FFFFFF;}

.well {margin-bottom: 5px;}

#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {

  border-spacing: 0px !important; border-collapse: collapse; font-size: small;

} .table tbody tr td {vertical-align: middle; }

</style>

<div class="contenu">

  <div id="msg" align="center" class="red"></div>



<?php if(!isset($_GET["down"])){  ?>

<div class="well well-sm r_float"><div class="r_float"><a href="./s_suivi_resultat.php" class="button">Retour</a></div>

<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."?down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>

<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."?down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>


<div class="clear h0">&nbsp;</div>

<?php } else { ?>



<center><?php //include "./includes/print_header.php"; ?></center>



<?php } ?>




<div class="widget-header1"> <center><h4><?php if(!empty($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>
<div class="well well-sm"><strong>Suivi du Cadre de R&eacute;sultat <?php if(isset($ugl)) echo "(".$row_nom_ugl["nom_ugl"].")"; else  echo "Projet"; ?> </strong><strong></strong></div>

     

     

       

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive">

            <?php if($totalRows_liste_composante>0) {$i=0; $t=0; $p2=$p1="j"; ?>

            <thead>

            <tr>
              

              <th rowspan="3" align="center"><strong>R&eacute;f.</strong></th>

              <th rowspan="3"><div align="left"><strong>Indicateurs de r&eacute;sultats  </strong></div></th>

              <th rowspan="3">Unit&eacute;s</th>
              <th colspan="<?php echo 4*($_SESSION["annee_fin_projet"]-$_SESSION["annee_debut_projet"]+1); ?>"><div align="center"><strong>Valeurs cibles annuelles </strong></div></th>

              <th style="font-size:10px" rowspan="3">Cible Total</th>
              <th style="font-size:10px" rowspan="3">Total r&eacute;alis&eacute;</th>
			  <th style="font-size:10px" rowspan="3">%</th>
            </tr>
            <tr>
               <?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
 <th <?php if($i==date("Y")) { ?>style="background-color:#FFCC33"   <?php } ?> colspan="4"><div align="center" style="font-size:10px"><strong>
				      <?php
					  
						 echo $i-$_SESSION["annee_debut_projet"]+1; ?>e Ann&eacute;e
                  </strong>&nbsp;</div></th>
                       <?php } ?>
            </tr>
            <tr>
             <?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
               <th style="font-size:10px">Cible</th>
			   <th style="font-size:10px">R&eacute;alis&eacute;</th>
			    <th style="font-size:10px">%</th>
				<th style="background-color:#CCCCCC; width:2">&nbsp;</th>
                       <?php } ?>
            </tr>
            </thead>

            <?php $i=0;  foreach($row_liste_composante as $row_liste_composante){ $id = $row_liste_composante['code_ref_ind'];
			
			if(1==1) {
			?>
			
			 <?php  if($p1!=$row_liste_composante["code_cr"]) {?>

            <tr >

              <td colspan="<?php echo 4*($_SESSION["annee_fin_projet"]-$_SESSION["annee_debut_projet"]+1)+7; ?>" align="center"><div align="left" style=" background-color:#BED694"><strong>  <?php  if($p1!=$row_liste_composante["code_cr"]) {if(isset($tableau_volet[$row_liste_composante["code_cr"]])){ echo $tableau_volet[$row_liste_composante["code_cr"]];} else echo "N/A";  } $p1=$row_liste_composante['code_cr']; ?>

              </strong></div></td>
            </tr>

            <?php } ?>

            <tr>
            

              <td nowrap="nowrap"><div align="center"><strong><?php echo $row_liste_composante['code_ref_ind']; ?></strong></div></td>

              <td><div align="left" class="Style4"><?php echo $row_liste_composante['intitule_ref_ind']; ?></div></td>

              <td> <?php if(isset($liste_unite_array[$row_liste_composante["unite_cmr"]])) $unite = $liste_unite_array[$row_liste_composante["unite_cmr"]]; else  $unite=""; echo $unite; ?><!--</a>--></td>
               <?php $val_reel = 0; $cible = 0; $diviseur = 0; $val_indt=""; $val_ind=0;  for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ $cible_val = -1; ?>
           <td nowrap="nowrap" ><span class="sorting">
             <?php
if (isset($row_liste_composante['fonction_agregat']) && $row_liste_composante['fonction_agregat']=="Ratio") {
/*if(isset($cible_array[$liste_deno_ratio_array[$row_liste_composante["id_ref_ind"]]][$i]) && $cible_array[$liste_deno_ratio_array[$row_liste_composante["id_ref_ind"]]][$i]>0){*/ if(isset($liste_num_ratio_array[$row_liste_composante["id_ref_ind"]]) && isset($cible_array[$liste_num_ratio_array[$row_liste_composante["id_ref_ind"]]][$i]) && $cible_array[$liste_num_ratio_array[$row_liste_composante["id_ref_ind"]]][$i]>0){
  if($liste_deno_ratio_array[$row_liste_composante["id_ref_ind"]]!=-1){
//Calcul de la Somme du denominateur
$deno = 1;
if(isset($indicateur_calcule[$liste_deno_ratio_array[$row_liste_composante["id_ref_ind"]]][$i]) && $indicateur_calcule[$liste_deno_ratio_array[$row_liste_composante["id_ref_ind"]]][$i]>0){ $deno = $indicateur_calcule[$liste_deno_ratio_array[$row_liste_composante["id_ref_ind"]]][$i]/100; }
//echo $deno;
echo number_format((((isset($liste_agregat_ref_array[$liste_num_ratio_array[$row_liste_composante["id_ref_ind"]]]) && $liste_agregat_ref_array[$liste_num_ratio_array[$row_liste_composante["id_ref_ind"]]]=="Moyenne")?$ciblem_array[$liste_num_ratio_array[$row_liste_composante["id_ref_ind"]]][$i]:$cible_array[$liste_num_ratio_array[$row_liste_composante["id_ref_ind"]]][$i])*$liste_coef_ratio_array[$row_liste_composante['id_ref_ind']])/$deno, 0, ',', ' '); $cible_val = (((isset($liste_agregat_ref_array[$liste_num_ratio_array[$row_liste_composante["id_ref_ind"]]]) && $liste_agregat_ref_array[$liste_num_ratio_array[$row_liste_composante["id_ref_ind"]]]=="Moyenne")?$ciblem_array[$liste_num_ratio_array[$row_liste_composante["id_ref_ind"]]][$i]:$cible_array[$liste_num_ratio_array[$row_liste_composante["id_ref_ind"]]][$i])*$liste_coef_ratio_array[$row_liste_composante['id_ref_ind']])/$deno; }else{ echo number_format((((isset($liste_agregat_ref_array[$liste_num_ratio_array[$row_liste_composante["id_ref_ind"]]]) && $liste_agregat_ref_array[$liste_num_ratio_array[$row_liste_composante["id_ref_ind"]]]=="Moyenne")?$ciblem_array[$liste_num_ratio_array[$row_liste_composante["id_ref_ind"]]][$i]:$cible_array[$liste_num_ratio_array[$row_liste_composante["id_ref_ind"]]][$i])*$liste_coef_ratio_array[$row_liste_composante['id_ref_ind']])/1, 0, ',', ' '); $cible_val = ($cible_array[$liste_num_ratio_array[$row_liste_composante["id_ref_ind"]]][$i]*$liste_coef_ratio_array[$row_liste_composante['id_ref_ind']])/1; } } else { echo ""; $cible_val=0; } //} else { echo "0"; $cible_val=0; }
} else {
				
                if(trim(strtolower($unite))=="oui/non"){ if(isset($cible_array[$row_liste_composante["id_ref_ind"]][$i])){ echo ($cible_array[$row_liste_composante["id_ref_ind"]][$i]==1)?"Non":"Oui"; $cible = ($cible_array[$row_liste_composante["id_ref_ind"]][$i]==1)?"Non":"Oui"; } else { /*echo ($row_liste_composante["id_ref_ind"]==1)?"Non":"Oui";*/$cible_val = -1; } }
				  elseif(isset($indicateur_calcule[$row_liste_composante["id_ref_ind"]][$i]) && $indicateur_calcule[$row_liste_composante["id_ref_ind"]][$i]>0){  echo number_format($indicateur_calcule[$row_liste_composante["id_ref_ind"]][$i], 0, ',', ' '); $cible_val = $indicateur_calcule[$row_liste_composante["id_ref_ind"]][$i]; }
				
				elseif(isset($cible_array[$row_liste_composante["id_ref_ind"]][$i])){
				 echo number_format(($row_liste_composante["fonction_agregat"]=="Moyenne")?$ciblem_array[$row_liste_composante["id_ref_ind"]][$i]:$cible_array[$row_liste_composante["id_ref_ind"]][$i], ($row_liste_composante["fonction_agregat"]=="Moyenne")?0:0, ',', ' ');
                 $cible_val = ($row_liste_composante["fonction_agregat"]=="Moyenne")?$ciblem_array[$row_liste_composante["id_ref_ind"]][$i]:$cible_array[$row_liste_composante["id_ref_ind"]][$i];
                 }else $cible_val=0;
				   }
if(trim(strtolower($unite))!="oui/non"){
switch($row_liste_composante["fonction_agregat"])
{
  default: $cible+= $cible_val; break;
  case 'Somme': $cible+= $cible_val; break;
  case 'Moyenne': if($cible_val>0){ $cible+= $cible_val; $diviseur++; } break;
  case 'Report': if($cible_val>0) $cible= $cible_val; break;
}    }
				  ?>
           </span></td>
		    <td valign="top" nowrap="nowrap"><strong id="ind_<?php echo $i."_".$id; ?>">
		      <?php $valeur = 0;  $n = 0; $s = ""; $deno =  0; $nume = 0; $val_ind="";
if(!isset($indicateur_dynamique[$liste_indicateur_id_array[$id]]) && $row_liste_composante['fonction_agregat']=='Unique')
{
  // if(!in_array($row_liste_composante["unite_cmr"],$array_indic))
         if(!in_array(trim(strtoupper($unite)),$array_indic))

   {
      if(isset($cible_val_array[$liste_indicateur_id_array[$id]][$i]))
      {
       // $val_reel += $cible_val_array[$liste_indicateur_id_array[$id]][$i];
        echo number_format($cible_val_array[$liste_indicateur_id_array[$id]][$i], 0, ',', ' ');
		$val_ind=$cible_val_array[$liste_indicateur_id_array[$id]][$i];
      }
      else echo "-";
   }
   else
   {
      if(isset($cible_val_txt_array[$liste_indicateur_id_array[$id]][$i]))
      {
       // $val_reel += $cible_val_txt_array[$liste_indicateur_id_array[$id]][$i];
        echo $cible_val_txt_array[$liste_indicateur_id_array[$id]][$i];
		$val_indt=$cible_val_txt_array[$liste_indicateur_id_array[$id]][$i];
      }
      else echo "-";
   }


}
else{
if($row_liste_composante['fonction_agregat']=='Unique') $list_indic_select = array($row_liste_composante['code_ref_ind']);
elseif($row_liste_composante['fonction_agregat']=='Ratio'){ }
elseif($row_liste_composante['fonction_agregat']=='Moyenne'){ }
else
{
  if(isset($liste_indicateur_simple_array[$row_liste_composante['id_ref_ind']])) $list_indic_select = explode(',',$liste_indicateur_simple_array[$row_liste_composante['id_ref_ind']]);
}
if(isset($list_indic_select))
{
  foreach($list_indic_select as $indic_select)
  {
    if(!empty($indic_select) && isset($indicateur_dynamique[$liste_indicateur_id_array[$indic_select]]))
    {
      foreach($indicateur_dynamique[$liste_indicateur_id_array[$indic_select]] as $indic)
      {
        switch(strtoupper($row_liste_composante['fonction_agregat']))
        {
          default:
          $valeur += (isset($indic["val"][$i]))?$indic["val"][$i]:0;
          break;
          case "RATIO":
          if(isset($sign) && $sign == "ratio")
          {
            if($nume==0) $nume = (isset($indic["val"][$i]))?$indic["val"][$i]:0;
            elseif($deno==0) $deno = (isset($indic["val"][$i]))?$indic["val"][$i]:0;
          }
          else
          {
            if($nume==0) $nume = (isset($indic["val"][$i]))?$indic["val"][$i]:0;
            elseif($deno==0){ $deno = 1; } $deno *= (isset($indic["val"][$i]))?$indic["val"][$i]:0;
          }
          break;
        }
      }
    }
    else
    {
      if(isset($liste_indicateur_id_array[$indic_select]) && isset($cible_val_array[$liste_indicateur_id_array[$indic_select]]))
      {
        if(isset($cible_val_array[$liste_indicateur_id_array[$indic_select]][$i]))
        $valeur += $cible_val_array[$liste_indicateur_id_array[$indic_select]][$i];
      }
    }
  }
}
switch(strtoupper($row_liste_composante['fonction_agregat']))
{
  default:
  //$valeur += (isset($indic["val"][$i]))?$indic["val"][$i]:0;
  $n = 0; $s = "";
  break;
  case "MOYENNE":
  if(isset($list_indic_select)) $valeur = $valeur/count($list_indic_select);
  $n = 2; $s = ".";
  break;
  case "RATIO":
  if($deno>0)
  {
    if(isset($sign) && $sign == "ratio")
    $valeur = $nume/$deno;
    else  $valeur = $nume*$deno;
  }
  else $valeur = 0;
  if(isset($sign) && $sign == "ratio")
  {
    $n = 2; $s = ".";
  }
  else
  {
    $n = 0; $s = "";
  }
  break;
}


//$val_reel += $valeur;
echo ($valeur>0)?number_format($valeur, $n, $s, ' '):'-';
$val_ind=$valeur;
 }

//if(trim(strtolower($unite))!="oui/non"){
if(!in_array(trim(strtoupper($unite)),$array_indic)) {
switch($row_liste_composante["fonction_agregat"])
{
  default: $val_reel+= $val_ind; break;
  case 'Somme': $val_reel+= $val_ind; break;
  case 'Moyenne': if($val_ind>0){ $val_reel+= $val_ind; $diviseur++; } break;
  case 'Report': if($val_ind>0) $val_reel= $val_ind; break;
}    } else $val_reel= $val_indt; 


//$val_ind=$cible_val_txt_array[$liste_indicateur_id_array[$id]][$i];
 ?>
		    </strong></td>
			 <td valign="top" nowrap="nowrap" style="color:#990000; font-weight:bold"><?php
				 if(!in_array(trim(strtoupper($unite)),$array_indic)) {
				if(isset($cible_val) && $cible_val>0 && $val_ind!=0) echo number_format(100*$val_ind/$cible_val, 2, ',', ' ')."%";
				
				} else
				{
				 if(trim(strtolower($unite))=="oui/non"){ if(isset($cible_array[$row_liste_composante["id_ref_ind"]][$i]) && $cible_array[$row_liste_composante["id_ref_ind"]][$i]!=1 && trim(strtoupper($val_indt))=='OUI'){ echo "100%";} elseif(isset($cible_array[$row_liste_composante["id_ref_ind"]][$i]) && $cible_array[$row_liste_composante["id_ref_ind"]][$i]==1 && trim(strtoupper($val_indt))=='NON'){echo "100%";} }
				}
				
				//if(trim(strtoupper($val_indt))=='OUI') echo "100%";
				
				?></td>
			  <td style="background-color:#CCCCCC; width:2">&nbsp;</td>
                       <?php  } ?>
              <td valign="top">
<?php  echo (is_double($cible))?number_format($cible, 0, ',', ' '):$cible; ?></td>

              <td valign="top"><?php 
			 //if(in_array(trim(strtoupper($unite)),$array_indic)) {if(isset($val_reel)){ echo ($val_reel==1)?"Non":"Oui"; }} else
			  if("$val_reel"!="0") echo $val_reel; ?></td>
			    <td valign="top"><span style="color:#990000; font-weight:bold">
			      <?php
				   if(!in_array(trim(strtoupper($unite)),$array_indic)) {
				if(isset($cible) && isset($val_reel) && $cible>0 && $val_reel!=0 ) echo number_format(100*$val_reel/$cible, 0, ',', ' ')."%"; 
				
				} else
				{
				if(isset($cible) && isset($val_reel) && $cible==$val_reel) echo "100%";} ?>
			    </span></td>
            </tr>

            <?php }  ?>
            <?php }  ?>

            <?php } else { ?>

            <tr>

              <td colspan="7"><div align="center"><span class="Style4"><em><strong>Aucun indicateur! </strong></em></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span></div></td>
            </tr>

            <?php }  ?>
        </table>

        <hr id="sp_hr" />

    



</div>

<!-- Fin Site contenu ici -->

            </div>

        </div>



        </div>

    </div>

    <?php if(!isset($_GET["down"])) include_once("includes/footer.php"); ?>

</div>



</body>

</html>