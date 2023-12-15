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

include_once $config->sys_folder . "/database/db_connexion.php";

if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else {$annee=date("Y");}
//gestion revision
      $query_liste_mission = "SELECT * FROM ".$database_connect_prefix."version_ptba WHERE id_version_ptba='$annee'  ";
  try{
    $liste_mission = $pdar_connexion->prepare($query_liste_mission);
    $liste_mission->execute();
    $row_liste_mission = $liste_mission ->fetch();
    $totalRows_liste_mission = $liste_mission->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $annee_version_ptba=$row_liste_mission['annee_ptba'];
  $lib_version_ptba=$row_liste_mission['annee_ptba']." ".$row_liste_mission['version_ptba'];
  
 $wheract_periode= $wheract_suivi="";
if(isset($_GET['trim']) && !empty($_GET['trim'])) $trim = $_GET['trim']; else  $trim='%';
$date_t1=$annee_version_ptba."-03-31"; $date_t2=$annee_version_ptba."-06-30"; $date_t3=$annee_version_ptba."-09-30"; $date_t4=$annee_version_ptba."-12-31";
if($trim=="trim1") {$periode=" au ".date("d/m/Y", strtotime($date_t1)); /* $wheract_periode="AND groupe_tache.date_fin<='$date_t1'";*/ $wheract_suivi="AND decaissement_activite.date_collecte<='$date_t1'";}
elseif($trim=="trim2") {$periode=" au ".date("d/m/Y", strtotime($date_t2)); /* $wheract_periode="AND groupe_tache.date_fin<='$date_t2'";*/  $wheract_suivi="AND decaissement_activite.date_collecte<='$date_t2'";}
elseif($trim=="trim3") {$periode=" au ".date("d/m/Y", strtotime($date_t3)); /* $wheract_periode="AND groupe_tache.date_fin<='$date_t3'";*/  $wheract_suivi="AND decaissement_activite.date_collecte<='$date_t3'";}
elseif($trim=="trim4") {$periode=" au ".date("d/m/Y", strtotime($date_t4)); /* $wheract_periode="AND groupe_tache.date_fin<='$date_t4'"; */  $wheract_suivi="AND decaissement_activite.date_collecte<='$date_t4'";}
else $periode=" ";

//exit;
$wh="";
if(isset($_GET['cmp']) && $_GET['cmp']!="") $wh = " and code=".GetSQLValueString($_GET['cmp'], "text"); else $wh = "";
$ugl=(isset($_GET['ugl']))?$_GET['ugl']:$_SESSION["clp_structure"];


if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Avancement_budgetaire_PTBA.xls"); }

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
  
   if(isset($_GET['niveau']) && $_GET['niveau']!="") {$_SESSION["niveau"]=$_GET['niveau']; $niveau=$_SESSION["niveau"];} 
   //elseif(isset($max_niveau)) {$_SESSION["niveau"]=$max_niveau; $niveau=$_SESSION["niveau"];} 
   else { //unset($_SESSION["niveau"],$niveau);  
   $_SESSION["niveau"]=10; $niveau=$_SESSION["niveau"];} 
$where = (!isset($niveau) || $niveau==0)?" niveau =1":" niveau = ".$niveau." ";





if(isset($_GET['niveau']) && $_GET['niveau']<10) 
{
if(isset($_GET["acteur"]) && $_GET["acteur"]!="" && $_GET["acteur"]==0) 
  $query_liste_couta = "SELECT left(code_activite_ptba,'".$nb_code[$niveau]."')  as code, SUM( if(cout_realise>0, cout_realise,0) ) AS realise, SUM( if(cout_engage>0, cout_engage,0) ) AS engage FROM ".$database_connect_prefix."code_activite, ptba WHERE  ptba.code_activite_ptba=code_activite.code and  ptba.projet=code_activite.projet and ptba.projet='".$_SESSION["clp_projet"]."' and ptba.annee=$annee $wheract_suivi group by code";
  else
    $query_liste_couta = "SELECT left(code_activite_ptba,'".$nb_code[$niveau]."')  as code, SUM( if(cout_realise>0, cout_realise,0) ) AS realise, SUM( if(cout_engage>0, cout_engage,0) ) AS engage FROM ".$database_connect_prefix."code_activite, ptba WHERE  ptba.code_activite_ptba=code_activite.code and  ptba.projet=code_activite.projet and ptba.projet='".$_SESSION["clp_projet"]."' and ptba.annee=$annee $wheract_suivi group by code";
}  
  else
  {
  if(isset($_GET["acteur"]) && $_GET["acteur"]!="" && $_GET["acteur"]==0) 
    $query_liste_couta = "SELECT id_ptba  as code, SUM( if(cout_realise>0, cout_realise,0) ) AS realise, SUM( if(cout_engage>0, cout_engage,0) ) AS engage FROM ".$database_connect_prefix."code_activite, ptba WHERE  ptba.code_activite_ptba=code_activite.code  and  ptba.projet=code_activite.projet and ptba.projet='".$_SESSION["clp_projet"]."' and ptba.annee=$annee $wheract_suivi group by code";
	else
	    $query_liste_couta = "SELECT id_ptba  as code, SUM( if(cout_realise>0, cout_realise,0) ) AS realise, SUM( if(cout_engage>0, cout_engage,0) ) AS engage FROM ".$database_connect_prefix."code_activite, ptba WHERE  ptba.code_activite_ptba=code_activite.code and  ptba.projet=code_activite.projet and ptba.projet='".$_SESSION["clp_projet"]."' and ptba.annee=$annee $wheract_suivi group by code";
	}
    try{
    $liste_couta = $pdar_connexion->prepare($query_liste_couta);
    $liste_couta->execute();
    $row_liste_couta = $liste_couta ->fetchAll();
    $totalRows_liste_couta = $liste_couta->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//$prevu_arrayas = array();
$realise_arrayas =$realise_arrayae =$total_realise_array = array();
if($totalRows_liste_couta>0){
foreach($row_liste_couta as $row_liste_couta){
 //$prevu_arrayas[$row_liste_couta["code"]]=$row_liste_couta["prevu"]; 
 $realise_arrayas[$row_liste_couta["code"]]=$row_liste_couta["realise"]; 
 $realise_arrayae[$row_liste_couta["code"]]=$row_liste_couta["engage"]; 
 $total_realise_array[$row_liste_couta["code"]]=$row_liste_couta["engage"]+$row_liste_couta["realise"]; 
 }} 

//prevision
if(isset($_GET['niveau']) && $_GET['niveau']<10)  
{
if(isset($_GET["acteur"]) && $_GET["acteur"]!="" && $_GET["acteur"]==0) 
$query_liste_couta = "SELECT left(code_activite_ptba,'".$nb_code[$niveau]."')  as code, SUM( if(part_bailleur.observation>0, part_bailleur.observation,0) ) AS montant  FROM part_bailleur, ptba where  activite=id_ptba and  ptba.projet=part_bailleur.projet and ptba.projet='".$_SESSION["clp_projet"]."' and ptba.annee=$annee group by code";
else
$query_liste_couta = "SELECT left(code_activite_ptba,'".$nb_code[$niveau]."')  as code, SUM( if(montant>0, montant,0) ) AS montant  FROM part_bailleur, ptba where  activite=id_ptba and  ptba.projet=part_bailleur.projet and ptba.projet='".$_SESSION["clp_projet"]."' and ptba.annee=$annee group by code";
}
 else
 {
 if(isset($_GET["acteur"]) && $_GET["acteur"]!="" && $_GET["acteur"]==0) 
$query_liste_couta = "SELECT activite as code, SUM( if(part_bailleur.observation>0, part_bailleur.observation,0) ) AS montant  FROM part_bailleur where  annee=$annee and projet='".$_SESSION["clp_projet"]."'  group by code";
else
$query_liste_couta = "SELECT activite as code, SUM( if(montant>0, montant,0) ) AS montant  FROM part_bailleur where  annee=$annee and projet='".$_SESSION["clp_projet"]."'  group by code";

}
    try{
    $liste_couta = $pdar_connexion->prepare($query_liste_couta);
    $liste_couta->execute();
    $row_liste_couta = $liste_couta ->fetchAll();
    $totalRows_liste_couta = $liste_couta->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$prevu_arrayas = array();
if($totalRows_liste_couta>0){
foreach($row_liste_couta as $row_liste_couta){ $prevu_arrayas[$row_liste_couta["code"]]=$row_liste_couta["montant"]; }} 
//echo $query_liste_couta;
//exit;

/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_taux_tache = "SELECT sum(proportions) as taux_tact, left(code_activite_ptba,'".$nb_code[$niveau]."') as code FROM
 (SELECT SUM(s.proportion) as proportions,  id_ptba, code_activite_ptba FROM ".$database_connect_prefix."ptba, ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."suivi_tache s WHERE id_ptba=".$database_connect_prefix."groupe_tache.id_activite and id_groupe_tache=id_tache  and s.valider=1 and ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' group by id_ptba, code_activite_ptba) AS alias_sr  group by code";
$liste_taux_tache  = mysql_query_ruche($query_liste_taux_tache , $pdar_connexion) or die(mysql_error());
$row_liste_taux_tache  = mysql_fetch_assoc($liste_taux_tache);
$totalRows_liste_taux_tache = mysql_num_rows($liste_taux_tache);
$realise_tache = array();
if($totalRows_liste_taux_tache>0){
do{
 $realise_tache[$row_liste_taux_tache["code"]]=$row_liste_taux_tache["taux_tact"]; 
 //$realise_arrayas[$row_liste_couta["code"]]=$row_liste_couta["realise"]+$row_liste_couta["engage"]; 
 }
while($row_liste_taux_tache  = mysql_fetch_assoc($liste_taux_tache));} 


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_taux_ind_ptba = "select sum(taux) as taux_niveau, left(code_activite_ptba,'".$nb_code[$niveau]."') as code from ".$database_connect_prefix."ptba inner join  (
SELECT Avg(if(Total_cible>0,Total_suivi/Total_cible,0)) AS Taux, ".$database_connect_prefix."indicateur_tache.id_activite
FROM (".$database_connect_prefix."indicateur_tache INNER JOIN (SELECT SUM( ".$database_connect_prefix."cible_indicateur_trimestre.cible ) AS Total_cible, ".$database_connect_prefix."cible_indicateur_trimestre.indicateur as indicateur
FROM ".$database_connect_prefix."cible_indicateur_trimestre
GROUP BY ".$database_connect_prefix."cible_indicateur_trimestre.indicateur)  AS Cible_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Cible_indicateur.indicateur) INNER JOIN (SELECT SUM( ".$database_connect_prefix."suivi_indicateur_tache.valeur_suivi ) AS Total_suivi,  ".$database_connect_prefix."suivi_indicateur_tache.indicateur as indicateur
FROM  ".$database_connect_prefix."suivi_indicateur_tache
GROUP BY  ".$database_connect_prefix."suivi_indicateur_tache.indicateur)  AS Valeur_Suivi_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Valeur_Suivi_indicateur.indicateur
GROUP BY ".$database_connect_prefix."indicateur_tache.id_activite) as taux_ptba  ON ".$database_connect_prefix."ptba.id_ptba = taux_ptba.id_activite where ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."'group by code";
$liste_taux_ind_ptba  = mysql_query_ruche($query_liste_taux_ind_ptba , $pdar_connexion) or die(mysql_error());
$row_liste_taux_ind_ptba  = mysql_fetch_assoc($liste_taux_ind_ptba);
$totalRows_liste_taux_ind_ptba  = mysql_num_rows($liste_taux_ind_ptba);
$taux_ind_ptba = array();
//$tab_ptba_ind = array();
if($totalRows_liste_taux_ind_ptba>0){
do{$taux_ind_ptba[$row_liste_taux_ind_ptba["code"]]=$row_liste_taux_ind_ptba["taux_niveau"];
//$tab_ptba_ind[]=$row_liste_ind_ptba["code_activite_ptba"]."<!>".$row_liste_ind_ptba["id_ptba"]."<!>".$row_liste_ind_ptba["id_indicateur_tache"];
//$n_ptba_ind[$row_liste_ind_ptba["id_ptba"]]=$row_liste_ind_ptba["nacti"];
}while($row_liste_taux_ind_ptba  = mysql_fetch_assoc($liste_taux_ind_ptba));} */



//Requete 1
/*

SELECT ".$database_connect_prefix."indicateur_tache.id_activite, AVG( IF( Total_cible >0, Total_suivi / Total_cible, 0 ) ) AS Taux
FROM (
".$database_connect_prefix."indicateur_tache
INNER JOIN (

SELECT SUM( ".$database_connect_prefix."cible_indicateur_trimestre.cible ) AS Total_cible, ".$database_connect_prefix."cible_indicateur_trimestre.indicateur AS indicateur
FROM ".$database_connect_prefix."cible_indicateur_trimestre
GROUP BY ".$database_connect_prefix."cible_indicateur_trimestre.indicateur
) AS Cible_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Cible_indicateur.indicateur
)
INNER JOIN (

SELECT SUM( ".$database_connect_prefix."suivi_indicateur_tache.valeur_suivi ) AS Total_suivi, ".$database_connect_prefix."suivi_indicateur_tache.indicateur AS indicateur
FROM ".$database_connect_prefix."suivi_indicateur_tache
GROUP BY ".$database_connect_prefix."suivi_indicateur_tache.indicateur
) AS Valeur_Suivi_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Valeur_Suivi_indicateur.indicateur
GROUP BY ".$database_connect_prefix."indicateur_tache.id_activite
ORDER BY ".$database_connect_prefix."indicateur_tache.id_activite
LIMIT 0 , 30
*/

//Requete 2
/*
select avg(taux) as taux_niveau, code_activite_ptba from ".$database_connect_prefix."ptba inner join  (
SELECT Avg(IIf([Total_cible]>0,[Total_suivi]/[Total_cible],0)) AS Taux, ".$database_connect_prefix."indicateur_tache.id_activite
FROM (".$database_connect_prefix."indicateur_tache INNER JOIN (SELECT SUM( ".$database_connect_prefix."cible_indicateur_trimestre.cible ) AS Total_cible, ".$database_connect_prefix."cible_indicateur_trimestre.indicateur as indicateur
FROM ".$database_connect_prefix."cible_indicateur_trimestre
GROUP BY ".$database_connect_prefix."cible_indicateur_trimestre.indicateur)  AS Cible_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Cible_indicateur.indicateur) INNER JOIN (SELECT SUM( ".$database_connect_prefix."suivi_indicateur_tache.valeur_suivi ) AS Total_suivi,  ".$database_connect_prefix."suivi_indicateur_tache.indicateur as indicateur
FROM  ".$database_connect_prefix."suivi_indicateur_tache
GROUP BY  ".$database_connect_prefix."suivi_indicateur_tache.indicateur)  AS Valeur_Suivi_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Valeur_Suivi_indicateur.indicateur
GROUP BY ".$database_connect_prefix."indicateur_tache.id_activite) as taux_ptba  ON ".$database_connect_prefix."ptba.id_ptba = taux_ptba.id_activite group by code_activite_ptba

*/


  
  //exit;
  
  if(isset($_GET["acteur"]) && $_GET["acteur"]!="") {$iactget=$_GET["acteur"]; $wheract="AND fin=$iactget"; } else {$wheract=""; $iactget="";} 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">


<?php if(!isset($_GET["down"])){  ?>
<head>



  <title><?php print $config->sitename;?></title>



  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone;?>" />



  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />



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



  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->
theme_folder;?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->



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
<?php } ?>


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
<!--<form name="form<?php //echo $annee; ?>1" id="form<?php //echo $annee; ?>1" method="get" action="<?php //echo "print_suivi_budget_ptba_projet.php?annee=".$annee; ?>" class="pull-left">

 <select name="acteur" onchange="form<?php //echo $annee; ?>1.submit();" style="background-color: #FFFF00; padding: 7px;" class="btn p11">

            <option value="">-- Choisissez une partie --</option>
			 <option value="0">Partenaires</option>
			 <option value="1">PDAIG uniquement</option>
 <option value="">Toutes les activités</option>
  </select>
  <input type="hidden" name="annee" value="<?php //echo $annee; ?>" />
  <?php //if(isset($niveau) && $niveau>0) { ?>
    <input type="hidden" name="niveau" value="<?php //echo $niveau; ?>" />
  <?php //} ?>
</form>-->

<!--<form name="form<?php //echo $annee; ?>" id="form<?php //echo $annee; ?>" method="get" action="<?php //echo "print_suivi_budget_ptba_projet.php?annee=".$annee; ?>" class="pull-left"> Période :&nbsp;

<select name="trim" onchange="form<?php echo $annee; ?>.submit();" style="background-color:#FF9933; padding: 7px; width: 300px;" class="btn p11">

  <option value="trim1" <?php //if((isset($_GET["trim"]) && 'trim1'==$_GET["trim"])) echo "selected='SELECTED'"; ?>>1er Trimestre</option>
  <option value="trim2" <?php //if((isset($_GET["trim"]) && 'trim2'==$_GET["trim"])) echo "selected='SELECTED'"; ?>>2ème Trimestre</option>
  <option value="trim3" <?php //if((isset($_GET["trim"]) && 'trim3'==$_GET["trim"])) echo "selected='SELECTED'"; ?>>3ème Trimestre</option>
  <option value="trim4" <?php //if((isset($_GET["trim"]) && 'trim4'==$_GET["trim"])) echo "selected='SELECTED'"; ?>>4ème Trimestre</option>
  <option value="%" <?php //if((!isset($_GET["trim"]) && '%'==$trim)) echo "selected='SELECTED'"; ?>>Tout <?php //echo $lib_version_ptba; ?></option>

</select>
<input type="hidden" name="annee" value="<?php //echo $annee; ?>" />
<input type="hidden" name="acteur" value="<?php //echo $iactget; ?>" />

</form>-->
<div class="well well-sm r_float"><div class="r_float"><a href="./s_programmation.php" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a target="_blank" title="Imprimer" href="<?php echo $editFormAction."&down=1"; ?>" class="button"><img src="./images/print.png" width='20' height='20' alt='Modifier' /></a></div>
<!--<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
-->
</div>
<?php }  ?>
<div class="clear h0">&nbsp;</div>
<div class="widget box box_projet">

<div class="widget-header1"> <center><h4><?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?>
</h4>
</center></div>

<div class="widget-header"> <h4><i class="icon-reorder"></i> Suivi budgetaire: PTBA  <strong><?php echo "$lib_version_ptba"; ?></strong>&nbsp;&nbsp;<strong><span style="background-color:#FFCC33">
  <?php if(isset($_GET["acteur"]) && $_GET["acteur"]==0 && $_GET["acteur"]!="") echo  "&nbsp;(<u>Partenaires</u>)&nbsp;"; elseif(isset($_GET["acteur"]) && $_GET["acteur"]==1) echo  "&nbsp;(<u>PDAIG</u>)&nbsp;"; ?>
</span><?php echo $periode; ?></strong></h4>
<?php if(!isset($_GET["down"])){  
    echo do_link("",$_SERVER['PHP_SELF']."?annee=".$annee,"PTBA","<i> PTBA</i>","","./","pull-right p11","",0,"",$nfile);

  if(isset($libelle[0]) && !empty($libelle[0])){ $i=count($libelle)-1; $libelle1 = array_reverse($libelle); foreach($libelle1 as $lib){
 if(isset($_GET['ugl']))  echo do_link("",$_SERVER['PHP_SELF']."?niveau=".$i."&annee=".$annee."&ugl=".$ugl,"$lib","<i> $lib </i>","","./","pull-right p11","",0,"",$nfile);
 else
  echo do_link("",$_SERVER['PHP_SELF']."?niveau=".$i."&annee=".$annee,"$lib","<i> $lib </i>","","./","pull-right p11","",0,"",$nfile);

  $i--; } 

  echo '<div class="clear h0"></div>'; }


}

?>

</div>



<div class="widget-content" style="display: block;">



<?php if(!isset($niveau)){


 $n = count($libelle); ?>
<div class="clear h0">&nbsp;</div>

    </div>

<?php } else { //autre niveau

$where = ($niveau==0)?" niveau =1":" niveau = ".($niveau+1)." ";


//if(isset($_GET["acteur"]) && $_GET["acteur"]!="") {$iactget=$_GET["acteur"]; $wheract="AND fin=$iactget"; } else $wheract="";

if(isset($_GET['niveau']) && $_GET['niveau']<10) 

$query_liste_activite_1 = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE $where and projet='".$_SESSION["clp_projet"]."' and code in (select left(code_activite_ptba,'".$nb_code[$niveau]."') from ".$database_connect_prefix."ptba where ".$database_connect_prefix."ptba.annee='$annee' and projet='".$_SESSION["clp_projet"]."') ORDER BY niveau,code ASC";
else
$query_liste_activite_1 = "SELECT id_ptba as code, code_activite_ptba, intitule_activite_ptba as intitule, 0 as parent FROM  ".$database_connect_prefix."ptba where ".$database_connect_prefix."ptba.annee='$annee' and projet='".$_SESSION["clp_projet"]."' ORDER BY code_activite_ptba ASC";
  try{
    $liste_activite_1 = $pdar_connexion->prepare($query_liste_activite_1);
    $liste_activite_1->execute();
    $row_liste_activite_1 = $liste_activite_1 ->fetchAll();
    $totalRows_liste_activite_1 = $liste_activite_1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//echo $query_liste_activite_1;
//exit;
?>

<form name="form1" action="" method="post">

<table id="example" border="1" align="center" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive table-checkable table-tabletools table-colvis datatable dataTable" >

<?php if(count($libelle)>0){ ?>

                <thead>

                  <tr>
<?php if(!isset($_GET["down"])){  ?>
                    <th class="checkbox-column"> <input type="checkbox" class="uniform"> </th>
<?php } ?>
                     <?php if(isset($_GET['niveau']) && $_GET['niveau']>0 && $_GET['niveau']<10) { ?>

                    <td ><?php echo $libelle[$niveau-1]; ?></td>

                    <?php } ?>

                    <td >Code  <?php if(isset($_GET['niveau']) && $_GET['niveau']<10 ) echo $libelle[$niveau]; ?></td>

                    <td ><?php if(isset($_GET['niveau']) && $_GET['niveau']<10) echo $libelle[$niveau]; else echo "Activité"; ?></td>

                   <td  align="center"><strong><span class="Style8">Pr&eacute;vu (FCFA)</span></strong></td>
                   <td  align="center"><strong><span class="Style8">D&eacute;caiss&eacute; (F CFA)</span></strong></td>
				   <td  align="center"><strong>% D&eacute;caiss&eacute;</strong></td>
				   <td  align="center"><strong>Engag&eacute; (F CFA) </strong></td>
				   <td  align="center"><strong><span class="Style8">Taux global (%) </span></strong></td>
                  </tr>
                </thead>

                <tbody>

<?php if($totalRows_liste_activite_1>0){ $totalp=$totald=$totale=0; foreach($row_liste_activite_1 as $row_liste_activite_1){ $code = $row_liste_activite_1["code"]; $parent = $row_liste_activite_1["parent"]; ?>

                <tr>
<?php if(!isset($_GET["down"])){  ?>
                    <td class="checkbox-column"> <input type="checkbox" name="id_val[]" value="<?php echo $code; ?>" class="uniform"> </td>
<?php }  ?>
                    <?php if(isset($_GET['niveau'])  && $_GET['niveau']>0 && $_GET['niveau']<10 ) { ?>

                    <td><?php echo $row_liste_activite_1["parent"]; ?></td>

                    <?php } ?>

                    <td><?php if(isset($_GET['niveau']) && $_GET['niveau']<10) echo $row_liste_activite_1["code"]; else echo $row_liste_activite_1["code_activite_ptba"]; ?></td>

                    <td><?php echo $row_liste_activite_1["intitule"]; ?></td>

                    <td align="center" nowrap="nowrap"><div align="right"><strong>
                      <?php  if(isset($prevu_arrayas[$row_liste_activite_1["code"]]) && $prevu_arrayas[$row_liste_activite_1["code"]]>0) {echo number_format($prevu_arrayas[$row_liste_activite_1["code"]], 0, ',', ' '); $totalp=$totalp+$prevu_arrayas[$row_liste_activite_1["code"]];} ?>
                    </strong></div></td>
                  <td  align="center" nowrap="nowrap"><div align="right"><strong>
     <?php  if(isset($realise_arrayas[$row_liste_activite_1["code"]]) && $realise_arrayas[$row_liste_activite_1["code"]]>0) {echo number_format($realise_arrayas[$row_liste_activite_1["code"]], 0, ',', ' ');  $totald=$totald+$realise_arrayas[$row_liste_activite_1["code"]];} ?>
                  </strong></div></td>
				   <td  align="center" nowrap="nowrap"><strong>
				     <?php  if(isset($prevu_arrayas[$row_liste_activite_1["code"]]) && isset($realise_arrayas[$row_liste_activite_1["code"]]) && $prevu_arrayas[$row_liste_activite_1["code"]]>0) echo number_format(100*$realise_arrayas[$row_liste_activite_1["code"]]/$prevu_arrayas[$row_liste_activite_1["code"]], 2, ',', ' ')." %"; ?>
				   </strong></td>
				   <td  align="center" nowrap="nowrap"><strong>
				     <?php  if(isset($realise_arrayae[$row_liste_activite_1["code"]]) && $realise_arrayae[$row_liste_activite_1["code"]]>0 && isset($realise_arrayas[$row_liste_activite_1["code"]])) {echo number_format($realise_arrayae[$row_liste_activite_1["code"]]-$realise_arrayas[$row_liste_activite_1["code"]], 0, ',', ' '); $totale=$totale+$realise_arrayae[$row_liste_activite_1["code"]]-$realise_arrayas[$row_liste_activite_1["code"]];} ?>
				   </strong></td>
				   <td  align="center" nowrap="nowrap"><strong>
				     
				     <?php  if(isset($prevu_arrayas[$row_liste_activite_1["code"]]) && isset($realise_arrayae[$row_liste_activite_1["code"]]) && $prevu_arrayas[$row_liste_activite_1["code"]]>0 && $realise_arrayae[$row_liste_activite_1["code"]]>0) echo number_format(100*$realise_arrayae[$row_liste_activite_1["code"]]/$prevu_arrayas[$row_liste_activite_1["code"]], 2, ',', ' ')." %"; elseif(isset($realise_arrayas[$row_liste_activite_1["code"]]) && $realise_arrayas[$row_liste_activite_1["code"]]>0) {?><span class="btn-danger">&nbsp;&nbsp;!!!&nbsp;&nbsp;</span><?php } ?>
			        </strong></td>
                </tr>
               
<?php } ?>
 <tr style="background-color:#CCCCCC">
  <?php if(!isset($_GET["down"])){  ?>
                    <td class="checkbox-column">&nbsp;  </td>
<?php }  ?>
                  <td colspan="<?php if(isset($_GET['niveau']) && $_GET['niveau']>0) echo 3; else echo 2; ?>" class="checkbox-column"><div align="right"><strong>Total PTBA <?php echo "$lib_version_ptba"; ?></strong></div></td>
                  <td align="center" nowrap="nowrap"><div align="right"><strong>
                    <?php  if(isset($totalp) && $totalp>0) echo number_format($totalp, 0, ',', ' '); ?>
                  </strong></div></td>
                  <td  align="center" nowrap="nowrap"><div align="right"><strong>
                    <?php  if(isset($totald) && $totald>0) echo number_format($totald, 0, ',', ' '); ?>
                  </strong></div></td>
                  <td  align="center" nowrap="nowrap"><strong>
                    <?php  if(isset($totalp) && isset($totald) && $totalp>0) echo number_format(100*$totald/$totalp, 2, ',', ' ')." %"; ?>
                  </strong></td>
                  <td  align="center" nowrap="nowrap"><strong>
                    <?php  if(isset($totale) && $totale>0 && isset($totald)) {echo number_format($totale, 0, ',', ' ');} ?>
                  </strong></td>
                  <td  align="center" nowrap="nowrap"><strong>
                    <?php  if(isset($totalp) && isset($totald) && $totalp>0) echo number_format(100*($totald+$totale)/$totalp, 2, ',', ' ')." %"; ?>
                  </strong></td>
                </tr>
                </tbody>

<?php } } else { ?>

                <tr>

                  <td><div align="center" class="">
                    <h2>Aucune activit&eacute;</h2>
                  </div></td>
                </tr>

                <?php } ?>
            </table>


</form>

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