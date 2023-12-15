<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & DÃ©veloppement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else {$annee=date("Y");}
$ugl=(isset($_GET['ugl']))?$_GET['ugl']:$_SESSION["clp_structure"];

/* mysql_select_db($database_pdar_connexion, $pdar_connexion);

  $query_liste_region= "SELECT code_ugl, nom_ugl FROM ".$database_connect_prefix."ugl order by code_ugl";

  $liste_region = mysql_query_ruche($query_liste_region, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

  $tableauRegion=array();

  while($ligne=mysql_fetch_assoc($liste_region))

  { $tableauRegion[$ligne["code_ugl"]]=$ligne["nom_ugl"];}*/

if(isset($_GET['ugl']) && isset( $tableauRegion[$ugl])) $nom_ugl=$tableauRegion[$ugl]; else $nom_ugl="_";

 // mysql_free_result($liste_region);
  
if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Avancement_global_ptba_".$annee."_".$nom_ugl.".xls"); }

include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=ISO-8859-15');

/*if(isset($_GET['niveau']) && $_GET['niveau']!="") {$_SESSION["niveau"]=$_GET['niveau']; $niveau=$_SESSION["niveau"];} else { unset($_SESSION["niveau"],$niveau); }
$where = (!isset($niveau) || $niveau==0)?" niveau =1":" niveau = ".$niveau." ";*/
if(isset($_GET['cmp']) && $_GET['cmp']!="") $wh = " and code=".GetSQLValueString($_GET['cmp'], "text"); else $wh = "";


$ugl=(isset($_GET['ugl']))?$_GET['ugl']:$_SESSION["clp_structure"];



$editFormAction = $_SERVER['PHP_SELF'];
$currentPage = $_SERVER['PHP_SELF']."?niveau=".((isset($niveau)?$niveau:""));
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$personnel=$_SESSION['clp_id'];

//import

  $query_entete = "SELECT * FROM ".$database_connect_prefix."niveau_config WHERE projet='".$_SESSION["clp_projet"]."' LIMIT 1";
        try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $libelle = array();  $nb_code = array();
  if($totalRows_entete>0){$libelle=explode(",",$row_entete["libelle"]); $nb_code=explode(",",$row_entete["code_number"]); }
  
   $max_niveau=$row_entete["nombre"]-1;
  
 
 if(isset($_GET['niveau']) && $_GET['niveau']!="") {$_SESSION["niveau"]=$_GET['niveau']; $niveau=$_SESSION["niveau"];}  else { unset($_SESSION["niveau"],$niveau); } 
$where = (!isset($niveau) || $niveau==0)?" niveau =1":" niveau = ".$niveau." ";

  $query_liste_activite = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE niveau =1 and projet='".$_SESSION["clp_projet"]."' ORDER BY niveau,code ASC";
      try{
    $liste_activite = $pdar_connexion->prepare($query_liste_activite);
    $liste_activite->execute();
    $row_liste_activite = $liste_activite ->fetchAll();
    $totalRows_liste_activite = $liste_activite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

 


$prevu_arrayas =   $activitep_array = $realise_tache = $taux_ind_ptba = array();
$realise_arrayas =$realise_arrayae =$total_realise_array = array();

  for($j=0;$j<=$max_niveau;$j++){ 
  // bbudget
  $query_liste_couta = "SELECT left(code,'".$nb_code[$j]."')  as codea, SUM( if(cout_realise>0, cout_realise,0) ) AS realise, SUM( if(cout_engage>0, cout_engage,0) ) AS engage FROM ".$database_connect_prefix."code_activite WHERE  projet='".$_SESSION["clp_projet"]."' and code_activite.annee=$annee group by codea";  
    try{
    $liste_couta = $pdar_connexion->prepare($query_liste_couta);
    $liste_couta->execute();
    $row_liste_couta = $liste_couta ->fetchAll();
    $totalRows_liste_couta = $liste_couta->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_liste_couta>0){
 foreach($row_liste_couta as $row_liste_couta){
 $realise_arrayas[$row_liste_couta["codea"]]=$row_liste_couta["realise"]; 
 $realise_arrayae[$row_liste_couta["codea"]]=$row_liste_couta["engage"]-$row_liste_couta["realise"]; 
  $total_realise_array[$row_liste_couta["codea"]]=$row_liste_couta["engage"]+$row_liste_couta["realise"]; 
 } } 

//prevision cout
$query_liste_couta = "SELECT left(code_activite_ptba,'".$nb_code[$j]."')  as code, SUM( if(montant>0, montant,0) ) AS montant  FROM part_bailleur, ptba where  activite=id_ptba and  ptba.projet=part_bailleur.projet and ptba.projet='".$_SESSION["clp_projet"]."' and ptba.annee=$annee group by code";
    try{
    $liste_couta = $pdar_connexion->prepare($query_liste_couta);
    $liste_couta->execute();
    $row_liste_couta = $liste_couta ->fetchAll();
    $totalRows_liste_couta = $liste_couta->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
//$prevu_arrayas = array();
if($totalRows_liste_couta>0){
 foreach($row_liste_couta as $row_liste_couta){ $prevu_arrayas[$row_liste_couta["code"]]=$row_liste_couta["montant"];} }

//suivi des taches
/* $query_liste_taux_tache = "SELECT sum(proportions) as taux_tact, left(code_activite_ptba,'".$nb_code[$j]."') as code FROM
 (SELECT SUM(s.proportion) as proportions,  id_ptba, code_activite_ptba FROM ".$database_connect_prefix."ptba, ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."suivi_tache s WHERE id_ptba=".$database_connect_prefix."groupe_tache.id_activite and id_groupe_tache=id_tache  and s.valider=1  and ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."'  group by id_ptba, code_activite_ptba) AS alias_sr  group by code";*/
 
 //nombre d'activite
    $query_liste_actpa = "SELECT left(code_activite_ptba,'".$nb_code[$j]."') as code, COUNT(id_ptba) as nactivitep FROM  `ptba` where ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY  `code`";
    try{
    $liste_actpa = $pdar_connexion->prepare($query_liste_actpa);
    $liste_actpa->execute();
    $row_liste_actpa = $liste_actpa ->fetchAll();
    $totalRows_liste_actpa = $liste_actpa->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  if($totalRows_liste_actpa>0){
 foreach($row_liste_actpa as $row_liste_actpa){ $activitep_array[$row_liste_actpa["code"]] = $row_liste_actpa["nactivitep"];  } }
 

 $query_liste_taux_tache = "select sum(total) as taux_tact, left(code_activite_ptba,'".$nb_code[$j]."') as code from (SELECT ROUND(SUM(if(n_lot>0 && valider=1, proportion*jalon/n_lot,0))) as total, id_ptba, code_activite_ptba FROM ptba left join groupe_tache  ON ptba.id_ptba = groupe_tache.id_activite where ptba.annee='$annee' and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY id_ptba, code_activite_ptba) as r1  group by code";
 //$query_liste_taux_tache = "select sum(if(montant>0,total*montant,0)) as taux_tact, left(code_activite_ptba,'".$nb_code[$j]."') as code from (SELECT ROUND(SUM(if(s.valider=1, s.proportion, 0))) as total, id_ptba, code_activite_ptba FROM ptba left join (groupe_tache inner JOIN suivi_tache s ON groupe_tache.id_groupe_tache = s.id_tache)  ON ptba.id_ptba = groupe_tache.id_activite where ptba.annee='$annee' and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY id_ptba, code_activite_ptba) as r1 ,part_bailleur where id_ptba=activite group by code";
    try{
    $liste_taux_tache = $pdar_connexion->prepare($query_liste_taux_tache);
    $liste_taux_tache->execute();
    $row_liste_taux_tache = $liste_taux_tache ->fetchAll();
    $totalRows_liste_taux_tache = $liste_taux_tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_liste_taux_tache>0){
 foreach($row_liste_taux_tache as $row_liste_taux_tache){
 if(isset($activitep_array[$row_liste_taux_tache["code"]]) && $activitep_array[$row_liste_taux_tache["code"]]>0) $realise_tache[$row_liste_taux_tache["code"]]=$row_liste_taux_tache["taux_tact"]/$activitep_array[$row_liste_taux_tache["code"]]; 
 } } 


  
//taux indicateurs
/* $query_liste_taux_ind_ptba = "select sum(if(tauxx>0 && montant>0,tauxx*montant,0)) as taux_cp, left(code_activite_ptba,'".$nb_code[$j]."') as code from (SELECT id_ptba, code_activite_ptba, avg(if(tsuivi>0, if((100*(tsuivi+0)/tcible)>100,100,100*tsuivi/tcible),0)) as tauxx FROM (SELECT ptba.id_ptba, code_activite_ptba, indicateur_tache.id_indicateur_tache, sum(cible_indicateur_trimestre.cible) AS tcible FROM ptba INNER JOIN (indicateur_tache INNER JOIN  cible_indicateur_trimestre ON indicateur_tache.id_indicateur_tache= cible_indicateur_trimestre.indicateur ) ON ptba.id_ptba = indicateur_tache.id_activite where ptba.annee='$annee' and cible>0 and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY ptba.id_ptba, code_activite_ptba, indicateur_tache.id_indicateur_tache) AS cible LEFT JOIN (SELECT indicateur_tache.id_indicateur_tache, SUM(suivi_indicateur_tache.valeur_suivi) AS tsuivi FROM ptba INNER JOIN (indicateur_tache LEFT JOIN suivi_indicateur_tache ON indicateur_tache.id_indicateur_tache= suivi_indicateur_tache.indicateur) ON ptba.id_ptba = indicateur_tache.id_activite where ptba.annee='$annee' and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY indicateur_tache.id_indicateur_tache) AS suivi ON cible.id_indicateur_tache= suivi.id_indicateur_tache GROUP BY id_ptba) as r1 , part_bailleur where id_ptba=activite group by code";*/
 
 $query_liste_taux_ind_ptba = "select sum(if(taux>1, 1,taux)) as taux_cp, left(code_activite_ptba,'".$nb_code[$j]."') as code from ".$database_connect_prefix."ptba inner join  (
SELECT Avg(if(Total_cible>0,Total_suivi/Total_cible,0)) AS Taux, ".$database_connect_prefix."indicateur_tache.id_activite
FROM (".$database_connect_prefix."indicateur_tache INNER JOIN 
(SELECT SUM(cible_indicateur_trimestre.cible) AS Total_cible
, ".$database_connect_prefix."cible_indicateur_trimestre.indicateur as indicateur
FROM ".$database_connect_prefix."cible_indicateur_trimestre
GROUP BY ".$database_connect_prefix."cible_indicateur_trimestre.indicateur)  AS Cible_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Cible_indicateur.indicateur) INNER JOIN 
(SELECT  SUM(suivi_indicateur_tache.valeur_suivi)  AS Total_suivi
,  ".$database_connect_prefix."suivi_indicateur_tache.indicateur as indicateur
FROM  ".$database_connect_prefix."suivi_indicateur_tache
GROUP BY  ".$database_connect_prefix."suivi_indicateur_tache.indicateur)  AS Valeur_Suivi_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Valeur_Suivi_indicateur.indicateur
GROUP BY ".$database_connect_prefix."indicateur_tache.id_activite) as taux_ptba  ON ".$database_connect_prefix."ptba.id_ptba = taux_ptba.id_activite where ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' group by code";

  try{
    $liste_taux_ind_ptba = $pdar_connexion->prepare($query_liste_taux_ind_ptba);
    $liste_taux_ind_ptba->execute();
    $row_liste_taux_ind_ptba = $liste_taux_ind_ptba ->fetchAll();
    $totalRows_liste_taux_ind_ptba = $liste_taux_ind_ptba->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_liste_taux_ind_ptba>0){
 foreach($row_liste_taux_ind_ptba as $row_liste_taux_ind_ptba){ if(isset($activitep_array[$row_liste_taux_ind_ptba["code"]]) && $activitep_array[$row_liste_taux_ind_ptba["code"]]>0) $taux_ind_ptba[$row_liste_taux_ind_ptba["code"]]=100*$row_liste_taux_ind_ptba["taux_cp"]/$activitep_array[$row_liste_taux_ind_ptba["code"]];
}} 
}
/*print_r($realise_tache);
echo "<br/>";
print_r($activitep_array);

exit;*/
//print_r($prevu_arrayas);
//exit;
//gestion revision
 $query_liste_mission = "SELECT * FROM ".$database_connect_prefix."version_ptba WHERE id_version_ptba='$annee'  ";
  try{
    $liste_mission = $pdar_connexion->prepare($query_liste_mission);
    $liste_mission->execute();
    $row_liste_mission = $liste_mission ->fetch();
    $totalRows_liste_mission = $liste_mission->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $lib_version_ptba=$row_liste_mission['annee_ptba']." ".$row_liste_mission['version_ptba'];
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<?php if(!isset($_GET["down"])){  ?>
<head>

  <title><?php print $config->sitename;?></title>

  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone;?>" />
<?php } ?>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if(!isset($_GET["down"])){  ?>
  <meta name="keywords" content="<?php print $config->MetaKeys;?>" />

  <meta name="description" content="<?php print $config->MetaDesc;?>" />

  <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->

  <meta name="author" content="<?php print $config->MetaAuthor;?>" />

  <!--<meta charset="utf-8">-->

  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>



  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->

  <link href="<?php print $config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/plugins.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/responsive.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/icons.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/login.css" rel="stylesheet" type="text/css"/>

  <link rel="stylesheet" href="<?php print $config->theme_folder;?>/fontawesome/font-awesome.min.css">

  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->theme_folder;?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->

  <!--[if IE 8]><link href="<?php print $config->theme_folder;?>/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->

  <link href='<?php print $config->theme_folder;?>/css.css' rel='stylesheet' type='text/css'>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/jquery-1.10.2.min.js"></script>

  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>

  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/lodash.compat.min.js"></script>

  <!--[if lt IE 9]><script src="<?php print $config->script_folder;?>/libs/html5shiv.js"></script><![endif]-->

  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>

  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>

  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>

  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/breakpoints.js"></script>

  <script type="text/javascript" src="plugins/respond/respond.min.js"></script>

  <script type="text/javascript" src="plugins/cookie/jquery.cookie.min.js"></script>

  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.min.js"></script>

  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>

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

  <script type="text/javascript" src="<?php print $config->script_folder;?>/app.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/plugins.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/plugins.form-components.js"></script>

 <script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>

 <script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/login.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/ui_general.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/form_validation.js"></script>
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>

</head>
<?php }  ?>
<body>

 <header class="header navbar navbar-fixed-top" role="banner">

   <?php if(!isset($_GET["down"])) include_once ("includes/header.php");?>

 </header>

<div id="container">

    <div id="sidebar" class="sidebar-fixed">

        <div id="sidebar-content">

           <?php if(!isset($_GET["down"])) include_once ("includes/menu_top.php");?>

        </div>

        <div id="divider" class="resizeable"></div>

    </div>



    <div id="content">

        <div class="container">

            <div class="crumbs">

             <?php if(!isset($_GET["down"])) include_once ("includes/sous_menu.php");?>

            </div>

        <div class="page-header">

            <div class="p_top_5">

<!-- Site contenu ici -->
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
</style>
<style>#sp_hr {margin:0px; }
.r_float{float: right;}
.Style11 { font-weight: bold;color: #FFFFFF;}
.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; } .marquer{background: #EBEBEB!important; }
</style>

<div class="contenu">
<?php if(!isset($_GET["down"])){  ?>
<div class="well well-sm r_float"><div class="r_float"><a href="./s_programmation.php" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a target="_blank" title="Imprimer" href="<?php echo $editFormAction."&down=1"; ?>" class="button"><img src="./images/print.png" width='20' height='20' alt='Modifier' /></a></div>
<!--<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
-->
</div>
<?php } ?>
<div class="clear h0">&nbsp;</div>
<div class="widget box ">

<?php if(!isset($_GET["down"])){  ?>
<div class="widget-header"> <h4><i class="icon-reorder"></i> Suivi par palier du PTBA <strong><?php echo "$lib_version_ptba"; ?></strong></h4>

</div>
<?php } ?>
<div class="widget-content" style="display: block;">

<?php if(!isset($niveau)){
 if(!isset($_GET["down"])){ 
if(isset($libelle[0]) && !empty($libelle[0])){  ?>
              <table border="0" align="center" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive dataTable" style="width:50%;">
                <tr>
                  <td><div align="left" class=""><?php echo $libelle[0]; ?>&nbsp;</div></td>
                  <td valign="middle"><form name="form38" id="form38" method="get" action="<?php echo $_SERVER['PHP_SELF']."?annee=$annee"; ?>">
                    <select name="cmp" onchange="form38.submit();" style="background-color: #FFFF00">
                      <option value="">-- Choisissez <?php echo $libelle[0]; ?> --</option>
                      <?php  foreach($row_liste_activite as $row_liste_activite){ ?>
                  <option value="<?php echo $row_liste_activite["code"]; ?>" <?php if(isset($_GET["cmp"]) && $row_liste_activite["code"]==$_GET["cmp"]) echo "selected='SELECTED'"; ?>><?php echo $row_liste_activite["code"]." : ".$row_liste_activite["intitule"]; ?></option>
                      <?php } ?>
                    </select>
					<input type="hidden" name="annee" value="<?php echo $annee; ?>" />
                  </form></td>
                </tr>
              </table>
<?php } } $n = count($libelle); ?>
<?php //}  ?>
              <table width="99%" border="1" align="left" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive dataTable">
                <tr class="titrecorps2">
                  <!--<td align="center"><div class="Style8"><b>Niveau</b></div></td> -->
<!--                  <td align="center" colspan="<?php echo count($libelle)+1; ?>"><div class="Style8"><b>Code</b></div></td>      -->
                  <td colspan="<?php echo $n+1; ?>"><div align="left"><strong><span class="Style8">Activit&eacute;s </span></strong></div></td>
                 <?php //if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
                  <td width="80" align="center"><strong><span class="Style8">T&acirc;ches (I) </span></strong></td>
				   <td width="80" align="center"><strong><span class="Style8">Indicateurs</span></strong></td>
				    <td width="80" align="center"><strong><span class="Style8">Budget<br/>
				      (F CFA)</span></strong></td>
					 <td width="80" align="center"><strong><span class="Style8">D&eacute;caiss&eacute; <br/>
				      (F CFA)</span></strong></td>
					  <td width="80" align="center"><strong><span class="Style8">% D&eacute;caiss&eacute; (II) </span></strong></td>
					   <td width="80" align="center"><strong><span class="Style8">Ecart (I-II) <br/>
				       (%)</span></strong></td>
					    <td width="80" align="center"><strong><span class="Style8">% avec engagement</span></strong></td>
				  <?php //}?>
                </tr>
                <?php $t=0; $i=0; if($totalRows_liste_activite>0) { ?>


<?php
function trace_tr($niveau,$j,$n,$budget, $realise,$engage,$taux_tache,$taux_indicateur,$libelle1,$session,$nfile="")
{
  $activitep_array = array(); $id = $libelle1['id']; $code = $libelle1['code'];
  $data = "";
  $data .= "<tr>";
  for($k=0;$k<$j;$k++){ $data .= "<td width='30' align='right'>&nbsp;</td>"; }
  $data .= "<td colspan='".($n-$j+1)."'><b>".$libelle1["code"]." :</b> ".$libelle1["intitule"]."</td>";
//if($session<2) {
//colonnes taches
$data .= "<td align='center' width='80'>";
 if(isset($taux_tache) && $taux_tache>0)  $data .= number_format($taux_tache, 0, ',', ' ')." %"; else $data .="";
//if(!isset($activitep_array[$libelle1['code']])) {
//$data .= do_link("",$_SERVER['PHP_SELF']."?id_sup=$id&niveau=".$libelle1['niveau'],"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer $libelle ?');",0,"margin:0px 5px;",$nfile);
//}
$data .= "</td>";
//colonnes indicateurs
$data .= "<td align='center' width='80'>";
 if(isset($taux_indicateur) && $taux_indicateur>0)  $data .= number_format($taux_indicateur, 0, ',', ' ')." %"; else $data .="";
//$data .= $n;
$data .= "</td>";
// colonnes budget
$data .= "<td align='center'  nowrap='nowrap' >";
// $data .= $prevu_arrayas[$libelle1["code"]]; //else $data .= "";
 if(isset($budget) && $budget>0)  $data .= number_format($budget, 0, ',', ' '); else $data .="";

$data .= "</td>";

// colonnes realise
$data .= "<td align='center' nowrap='nowrap' >";
// $data .= $prevu_arrayas[$libelle1["code"]]; //else $data .= "";
 if(isset($realise) && $realise>0)  $data .= number_format($realise, 0, ',', ' '); else $data .="";

$data .= "</td>";

// colonnes taux realise
$data .= "<td align='center'  nowrap='nowrap' >";
// $data .= $prevu_arrayas[$libelle1["code"]]; //else $data .= "";
if(isset($budget) && isset($realise) && $budget>0 && $realise>0)  $data .= number_format(100*$realise/$budget, 2, ',', ' ')." %"; else $data .="";
$data .= "</td>";

// colonnes engage
$data .= "<td align='center'  nowrap='nowrap' >";
// $data .= $prevu_arrayas[$libelle1["code"]]; //else $data .= "";

if(isset($taux_tache) && $taux_tache>0 && isset($budget) && isset($realise) && $budget>0 && $realise>0) 
{ if(abs($taux_tache-(100*$realise/$budget))>10) $data .= "<b style=\"color:#FF0000\">".number_format(abs($taux_tache-(100*$realise/$budget)), 1, ',', ' ')." %</b>";  
else $data .= number_format(abs($taux_tache-(100*$realise/$budget)), 1, ',', ' ')." %"; }
 
  else $data .=""; 

$data .= "</td>";

// colonnes taux avec engagement
$data .= "<td align='center' width='80'>";
// $data .= $prevu_arrayas[$libelle1["code"]]; //else $data .= "";
// $data .= $budget;
 if(isset($budget) && $budget>0 && (intval($realise)+intval($engage))>0)  $data .= number_format(100*($realise+$engage)/$budget, 2, ',', ' ')."%"; else $data .="";

$data .= "</td>";

$data .= "</tr>";
  return $data;
}
//$niveau_indent limite = 6;
$niveau_indent = $n;   $k = 0;
$query_liste_activite_1 = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE $where $wh and projet='".$_SESSION["clp_projet"]."' ORDER BY niveau,code ASC";
  try{
    $liste_activite_1 = $pdar_connexion->prepare($query_liste_activite_1);
    $liste_activite_1->execute();
    $row_liste_activite_1 = $liste_activite_1 ->fetchAll();
    $totalRows_liste_activite_1 = $liste_activite_1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
foreach($row_liste_activite_1 as $row_liste_activite_1){
{
  $niveau_indent = $n; $k = $j = 0;
  if($niveau_indent-$j>0 && isset($activitep_array[$row_liste_activite_1["code"]]))
  {
    $code_1 = $row_liste_activite_1["code"]; $id_1 = $row_liste_activite_1["id"];
	if(isset($prevu_arrayas[$row_liste_activite_1["code"]])) $budget_a=$prevu_arrayas[$row_liste_activite_1["code"]]; else $budget_a="";
	if(isset($realise_arrayas[$row_liste_activite_1["code"]])) $realise_a=$realise_arrayas[$row_liste_activite_1["code"]]; else $realise_a="";
	if(isset($realise_arrayae[$row_liste_activite_1["code"]])) $engage_a=$realise_arrayae[$row_liste_activite_1["code"]]; else $engage_a="";
	//tache
	if(isset($realise_tache[$row_liste_activite_1["code"]]) && isset($activitep_array[$row_liste_activite_1["code"]]) && $activitep_array[$row_liste_activite_1["code"]]>0) $taux_tache_a=$realise_tache[$row_liste_activite_1["code"]]; else $taux_tache_a="";
	//indicateur
	if(isset($taux_ind_ptba[$row_liste_activite_1["code"]])) $taux_indicateur_a=$taux_ind_ptba[$row_liste_activite_1["code"]]; else $taux_indicateur_a="";
    //traitement ici
	
    echo trace_tr($k,$j,$n,$budget_a,$realise_a,$engage_a,$taux_tache_a,$taux_indicateur_a,$row_liste_activite_1,$_SESSION['clp_niveau'],$nfile);

$query_liste_activite_2 = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE niveau=".($j+2)." and parent='$code_1' and projet='".$_SESSION["clp_projet"]."' ORDER BY niveau,code ASC";
  try{
    $liste_activite_2 = $pdar_connexion->prepare($query_liste_activite_2);
    $liste_activite_2->execute();
    $row_liste_activite_2 = $liste_activite_2 ->fetchAll();
    $totalRows_liste_activite_2 = $liste_activite_2->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

    if($totalRows_liste_activite_2>0) {foreach($row_liste_activite_2 as $row_liste_activite_2){
      $j=1; $k=1;
      if($niveau_indent-$j>0 && isset($activitep_array[$row_liste_activite_2["code"]]))
      {
        $code_2 = $row_liste_activite_2["code"]; $id_2 = $row_liste_activite_2["id"];
		if(isset($prevu_arrayas[$row_liste_activite_2["code"]])) $budget_a=$prevu_arrayas[$row_liste_activite_2["code"]]; else $budget_a="";
		if(isset($realise_arrayas[$row_liste_activite_2["code"]])) $realise_a=$realise_arrayas[$row_liste_activite_2["code"]]; else $realise_a="";
		if(isset($realise_arrayae[$row_liste_activite_2["code"]])) $engage_a=$realise_arrayae[$row_liste_activite_2["code"]]; else $engage_a="";
		//tache
	if(isset($realise_tache[$row_liste_activite_2["code"]]) && isset($activitep_array[$row_liste_activite_2["code"]]) && $activitep_array[$row_liste_activite_2["code"]]>0) $taux_tache_a=$realise_tache[$row_liste_activite_2["code"]]; else $taux_tache_a="";
	//indicateur
	if(isset($taux_ind_ptba[$row_liste_activite_2["code"]])) $taux_indicateur_a=$taux_ind_ptba[$row_liste_activite_2["code"]]; else $taux_indicateur_a="";
        //traitement ici
		
		
        echo trace_tr($k,$j,$n,$budget_a,$realise_a,$engage_a,$taux_tache_a,$taux_indicateur_a,$row_liste_activite_2,$_SESSION['clp_niveau'],$nfile);
		
$query_liste_activite_3 = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE niveau=".($j+2)." and parent='$code_2' and projet='".$_SESSION["clp_projet"]."' ORDER BY niveau,code ASC";
  try{
    $liste_activite_3 = $pdar_connexion->prepare($query_liste_activite_3);
    $liste_activite_3->execute();
    $row_liste_activite_3 = $liste_activite_3 ->fetchAll();
    $totalRows_liste_activite_3 = $liste_activite_3->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
        if($totalRows_liste_activite_3>0) { foreach($row_liste_activite_3 as $row_liste_activite_3){
          if($niveau_indent-$j>0 && isset($activitep_array[$row_liste_activite_3["code"]]))
          {
            $j=2; $k=2;
            $code_3 = $row_liste_activite_3["code"]; $id_3 = $row_liste_activite_3["id"];
			if(isset($prevu_arrayas[$row_liste_activite_3["code"]])) $budget_a=$prevu_arrayas[$row_liste_activite_3["code"]]; else $budget_a="";
			if(isset($realise_arrayas[$row_liste_activite_3["code"]])) $realise_a=$realise_arrayas[$row_liste_activite_3["code"]]; else $realise_a="";
			if(isset($realise_arrayae[$row_liste_activite_3["code"]])) $engage_a=$realise_arrayae[$row_liste_activite_3["code"]]; else $engage_a="";
					//tache
	if(isset($realise_tache[$row_liste_activite_3["code"]]) && isset($activitep_array[$row_liste_activite_3["code"]]) && $activitep_array[$row_liste_activite_3["code"]]>0) $taux_tache_a=$realise_tache[$row_liste_activite_3["code"]]; else $taux_tache_a="";
	//indicateur
	if(isset($taux_ind_ptba[$row_liste_activite_3["code"]])) $taux_indicateur_a=$taux_ind_ptba[$row_liste_activite_3["code"]]; else $taux_indicateur_a="";
            //traitement ici
            echo trace_tr($k,$j,$n,$budget_a,$realise_a,$engage_a,$taux_tache_a,$taux_indicateur_a,$row_liste_activite_3,$_SESSION['clp_niveau'],$nfile);

$query_liste_activite_4 = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE niveau=".($j+2)." and parent='$code_3' and projet='".$_SESSION["clp_projet"]."' ORDER BY niveau,code ASC";
  try{
    $liste_activite_4 = $pdar_connexion->prepare($query_liste_activite_4);
    $liste_activite_4->execute();
    $row_liste_activite_4 = $liste_activite_4 ->fetchAll();
    $totalRows_liste_activite_4 = $liste_activite_4->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
            if($totalRows_liste_activite_4>0) { foreach($row_liste_activite_4 as $row_liste_activite_4){
              if($niveau_indent-$j>0 && isset($activitep_array[$row_liste_activite_4["code"]]))
              {
                $j=3; $k=3;
                $code_4 = $row_liste_activite_4["code"]; $id_4 = $row_liste_activite_4["id"];
				if(isset($prevu_arrayas[$row_liste_activite_4["code"]])) $budget_a=$prevu_arrayas[$row_liste_activite_4["code"]]; else $budget_a="";
				if(isset($realise_arrayas[$row_liste_activite_4["code"]])) $realise_a=$realise_arrayas[$row_liste_activite_4["code"]]; else $realise_a="";
				if(isset($realise_arrayae[$row_liste_activite_4["code"]])) $engage_a=$realise_arrayae[$row_liste_activite_4["code"]]; else $engage_a="";
				//tache
	if(isset($realise_tache[$row_liste_activite_4["code"]]) && isset($activitep_array[$row_liste_activite_4["code"]]) && $activitep_array[$row_liste_activite_4["code"]]>0) $taux_tache_a=$realise_tache[$row_liste_activite_4["code"]]; else $taux_tache_a="";
	//indicateur
	if(isset($taux_ind_ptba[$row_liste_activite_4["code"]])) $taux_indicateur_a=$taux_ind_ptba[$row_liste_activite_4["code"]]; else $taux_indicateur_a="";
                //traitement ici
                echo trace_tr($k,$j,$n,$budget_a,$realise_a,$engage_a,$taux_tache_a,$taux_indicateur_a,$row_liste_activite_4,$_SESSION['clp_niveau'],$nfile);

$query_liste_activite_5 = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE niveau=".($j+2)." and parent='$code_4' and projet='".$_SESSION["clp_projet"]."' ORDER BY niveau,code ASC";
  try{
    $liste_activite_5 = $pdar_connexion->prepare($query_liste_activite_5);
    $liste_activite_5->execute();
    $row_liste_activite_5 = $liste_activite_5 ->fetchAll();
    $totalRows_liste_activite_5 = $liste_activite_5->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
                if($totalRows_liste_activite_5>0) { foreach($row_liste_activite_5 as $row_liste_activite_5){
                  if($niveau_indent-$j>0 && isset($activitep_array[$row_liste_activite_5["code"]]))
                  {
                    $j=4; $k=4;
                    $code_5 = $row_liste_activite_5["code"]; $id_5 = $row_liste_activite_5["id"];
					if(isset($prevu_arrayas[$row_liste_activite_5["code"]])) $budget_a=$prevu_arrayas[$row_liste_activite_5["code"]]; else $budget_a="";
					if(isset($realise_arrayas[$row_liste_activite_5["code"]])) $realise_a=$realise_arrayas[$row_liste_activite_5["code"]]; else $realise_a="";
					if(isset($realise_arrayae[$row_liste_activite_5["code"]])) $engage_a=$realise_arrayae[$row_liste_activite_5["code"]]; else $engage_a="";
						//tache
	if(isset($realise_tache[$row_liste_activite_5["code"]]) && isset($activitep_array[$row_liste_activite_5["code"]]) && $activitep_array[$row_liste_activite_5["code"]]>0) $taux_tache_a=$realise_tache[$row_liste_activite_5["code"]]; else $taux_tache_a="";
	//indicateur
	if(isset($taux_ind_ptba[$row_liste_activite_5["code"]])) $taux_indicateur_a=$taux_ind_ptba[$row_liste_activite_5["code"]]; else $taux_indicateur_a="";
                    //traitement ici
                    echo trace_tr($k,$j,$n,$budget_a, $realise_a,$engage_a,$taux_tache_a,$taux_indicateur_a,$row_liste_activite_5,$_SESSION['clp_niveau'],$nfile);

$query_liste_activite_6 = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE niveau=".($j+2)." and parent='$code_5' and projet='".$_SESSION["clp_projet"]."' ORDER BY niveau,code ASC";
  try{
    $liste_activite_6 = $pdar_connexion->prepare($query_liste_activite_6);
    $liste_activite_6->execute();
    $row_liste_activite_6 = $liste_activite_6 ->fetchAll();
    $totalRows_liste_activite_6 = $liste_activite_6->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

                    if($totalRows_liste_activite_6>0) { foreach($row_liste_activite_6 as $row_liste_activite_6){
                      //activite limite ici Ã  niveau 6
					  if(isset($activitep_array[$row_liste_activite_6["code"]])) {
                      $code_6 = $row_liste_activite_6["code"];
                      $id_6 = $row_liste_activite_6["id"];
					  if(isset($prevu_arrayas[$row_liste_activite_6["code"]])) $budget_a=$prevu_arrayas[$row_liste_activite_6["code"]]; else $budget_a="";
  					  if(isset($realise_arrayas[$row_liste_activite_6["code"]])) $realise_a=$realise_arrayas[$row_liste_activite_6["code"]]; else $realise_a="";
					  if(isset($realise_arrayae[$row_liste_activite_6["code"]])) $engage_a=$realise_arrayae[$row_liste_activite_6["code"]]; else $engage_a="";
					  //tache
	if(isset($realise_tache[$row_liste_activite_6["code"]]) && isset($activitep_array[$row_liste_activite_6["code"]]) && $activitep_array[$row_liste_activite_6["code"]]>0) $taux_tache_a=$realise_tache[$row_liste_activite_6["code"]]; else $taux_tache_a="";
	//indicateur
	if(isset($taux_ind_ptba[$row_liste_activite_6["code"]])) $taux_indicateur_a=$taux_ind_ptba[$row_liste_activite_6["code"]]; else $taux_indicateur_a="";
                      //traitement ici
                      echo trace_tr($k,$j,$n,$budget_a, $realise_a, $engage_a,$taux_tache_a, $taux_indicateur_a, $row_liste_activite_6,$_SESSION['clp_niveau'],$nfile);

                   } }  }
                  }
                } }
              }
            }  }
          }
        }  }
      }
    }  }
  }
} }

?>

                <?php }else{ ?>
                <tr>
                  <td colspan="<?php echo $n+2; ?>"><div align="center" class=""><b>Aucune activit&eacute;</b></div></td>
                </tr>
                <?php } ?>
              </table>
<div class="clear h0">&nbsp;</div>
    </div>
<?php } ?>

<!-- Fin Site contenu ici -->

            </div>
</div>
        </div>

 </div>

        </div>

    </div>  <?php if(!isset($_GET["down"])) include_once 'modal_add.php'; ?>

  <?php if(!isset($_GET["down"])) include_once ("includes/footer.php");?>

</div>

</body>

</html>