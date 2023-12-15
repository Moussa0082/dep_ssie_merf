<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & DÃƒÂ©veloppement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=ISO-8859-15');

 if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=ptba.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=ptba.doc"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="p"){

require_once('./tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$PDF_HEADER_TITLE = "Chronogramme des activitÃ©s du PTBA";
$PDF_HEADER_STRING = "Chronogramme des activitÃ©s du PTBA";

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Ruche');
$pdf->SetTitle($PDF_HEADER_TITLE);
$pdf->SetSubject($PDF_HEADER_STRING);
$pdf->SetKeywords('PDF, mission, Chronogramme des activitÃ©s du PTBA');

// set default header data //PDF_HEADER_LOGO
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE, $PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}
// set font
//$pdf->SetFont('dejavusans', '', 10);
$pdf->AddPage();

  ob_start(); // turn on output buffering
  /*$_GET["id"]="0001";
  $_GET["down"]=5; */
  include("./print_activite_ptba_pdf.php");
  $content = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering

$html = utf8_encode($content);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('ptba.pdf', 'D');
/*
include("pdf/mpdf.php");
$mpdf=new mPDF('win-1252','A4-L','','',15,10,16,10,10,10);//A4 page in portrait for landscape add -L.
$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetDisplayMode('fullpage');
ob_start();
include "print_etat_recommandation_mission_pdf.php";
$html = ob_get_contents();
ob_end_clean();
$mpdf->WriteHTML($html);
$mpdf->Output();
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Recommandation_mission.pdf"); */
exit;

 } ?>
<?php
$mois = array("","01"=>"Janvier","02"=>"FÃ©vrier","03"=>"Mars","04"=>"Avril","05"=>"Mai","06"=>"Juin","07"=>"Juillet","08"=>"Ao&ucirc;t","09"=>"Septembre","10"=>"Octobre","11"=>"Novembre","12"=>"Decembre");
//$tableauMois= array('T1','T2','T3','T4');
$tableauMoisc= array('Jan','Fev','Mar','Avr','Mai','Juin','Juil','Aout','Sep','Oct','Nov','Dec');
$tableauMois=array('01<>Jan<>J','02<>Fev<>F','03<>Mars<>M','04<>Avril<>A','05<>Mai<>M','06<>Juin<>J','07<>Juil<>J','08<>Aout<>A','09<>Sep<>S','10<>Oct<>O','11<>Nov<>N','12<>D&eacute;c<>D');
//$structure_array = array("01"=>"RCI","02"=>"AIPH","03"=>"APROMAC","04"=>"INTERCOTON","05"=>"CCC","06"=>"CCA","07"=>"RCI");
$annee=(isset($_GET['annee']))?$_GET['annee']:date("Y");
$whugl="";
//if(isset($_GET['ugl'])){$whugl="and b.structure=".$_GET['ugl']."";}
if(isset($_GET["acteur"]) && $_GET["acteur"]!="" && $_GET["acteur"]==0) 
$query_liste_bailleur = "SELECT t.code_type, t.bailleur, sum(b.observation) as montant, b.activite FROM ".$database_connect_prefix."part_bailleur b, ".$database_connect_prefix."type_part t where t.code_type=b.type_part and b.annee='$annee' and b.projet='".$_SESSION["clp_projet"]."' and t.projet='".$_SESSION["clp_projet"]."'  and  b.observation is not null GROUP BY b.activite,t.bailleur";

else

$query_liste_bailleur = "SELECT t.code_type, t.bailleur, sum(b.montant) as montant, b.activite FROM ".$database_connect_prefix."part_bailleur b, ".$database_connect_prefix."type_part t where t.code_type=b.type_part and b.annee='$annee' and b.projet='".$_SESSION["clp_projet"]."' and t.projet='".$_SESSION["clp_projet"]."'  and  b.montant is not null GROUP BY b.activite,t.bailleur";

  try{
    $liste_bailleur = $pdar_connexion->prepare($query_liste_bailleur);
    $liste_bailleur->execute();
    $row_liste_bailleur = $liste_bailleur ->fetchAll();
    $totalRows_liste_bailleur = $liste_bailleur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$destinateur_array = $parts_array = $tableauCoutSaisi = array();
if($totalRows_liste_bailleur>0){ foreach($row_liste_bailleur as $row_liste_bailleur){
  $destinateur_array[$row_liste_bailleur["bailleur"]] = $row_liste_bailleur["bailleur"];
  $parts_array[$row_liste_bailleur["bailleur"]] = $row_liste_bailleur["code_type"];

if(!isset($tableauCoutSaisi[$row_liste_bailleur["activite"]][$row_liste_bailleur["bailleur"]]))
$tableauCoutSaisi[$row_liste_bailleur["activite"]][$row_liste_bailleur["bailleur"]]=$row_liste_bailleur["montant"];
else $tableauCoutSaisi[$row_liste_bailleur["activite"]][$row_liste_bailleur["bailleur"]]+=$row_liste_bailleur["montant"];
if(!isset($tableauCoutSaisi[$row_liste_bailleur["activite"]]["total"])) $tableauCoutSaisi[$row_liste_bailleur["activite"]]["total"]=$row_liste_bailleur["montant"];
else $tableauCoutSaisi[$row_liste_bailleur["activite"]]["total"]+=$row_liste_bailleur["montant"];
}}

asort($destinateur_array);

$query_liste_bailleur = "SELECT * FROM ".$database_connect_prefix."partenaire  ";
  try{
    $liste_bailleur = $pdar_connexion->prepare($query_liste_bailleur);
    $liste_bailleur->execute();
    $row_liste_bailleur = $liste_bailleur ->fetchAll();
    $totalRows_liste_bailleur = $liste_bailleur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$bailleur_array = array();
  if($totalRows_liste_bailleur>0){  foreach($row_liste_bailleur as $row_liste_bailleur){
    $bailleur_array[$row_liste_bailleur["code"]] = $row_liste_bailleur["sigle"];
  } }


$query_liste_prestataire = "SELECT * FROM ".$database_connect_prefix."ugl order by code_ugl";
  try{
    $liste_prestataire = $pdar_connexion->prepare($query_liste_prestataire);
    $liste_prestataire->execute();
    $row_liste_prestataire = $liste_prestataire ->fetchAll();
    $totalRows_liste_prestataire = $liste_prestataire->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$acteur_array = $Nacteur_array= array();
  if($totalRows_liste_prestataire>0){  foreach($row_liste_prestataire as $row_liste_prestataire1){
    $Nacteur_array[$row_liste_prestataire1["code_ugl"]] = $row_liste_prestataire1["nom_ugl"];
	$acteur_array[] = $row_liste_prestataire1["code_ugl"]."!!".$row_liste_prestataire1["nom_ugl"];
  }}//while($row_liste_prestataire = mysql_fetch_assoc($liste_prestataire));  
  

$query_edit_ms = "SELECT code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=1 and projet='".$_SESSION["clp_projet"]."' ORDER BY code asc";
  try{
    $edit_ms = $pdar_connexion->prepare($query_edit_ms);
    $edit_ms->execute();
    $row_edit_ms = $edit_ms ->fetchAll();
    $totalRows_edit_ms = $edit_ms->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  
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
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>
</head>
<?php }  ?>
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
.well {margin-bottom: 5px;} thead tr th{vertical-align: middle; text-align: center; }
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; } .marquer{background: #EBEBEB!important; }
</style>
<div class="contenu">
<?php if(!isset($_GET["down"])){  ?>
<!--<form name="form<?php //echo $annee; ?>" id="form<?php //echo $annee; ?>" method="get" action="<?php //echo "print_activite_ptba.php?annee=".$annee; ?>" class="pull-left">

 <select name="acteur" onchange="form<?php //echo $annee; ?>.submit();" style="background-color: #FFFF00; padding: 7px;" class="btn p11">

            <option value="">-- Choisissez une partie --</option>
			 <option value="0">Partenaires </option>
			 <option value="1">INCLUSIF uniquement</option>
            <!--<?php //  if($totalRows_liste_prestataire>0) { foreach($row_liste_prestataire as $row_liste_prestataire){?>
            <option <?php //if(isset($id_ms) && $id_ms==$row_liste_prestataire['id_acteur']) {echo 'SELECTED="selected"';  $nom=$row_liste_prestataire['objet'];}  ?> value="<?php //echo  $row_liste_prestataire['code_ugl']; ?>"> <?php echo "<b>".$row_liste_prestataire['nom_ugl']."</b> ";?>
            </option>
            <?php //} }?>
 <option value="">Toutes les activités</option>
  </select>
  <input type="hidden" name="annee" value="<?php //echo $annee; ?>" />

</form>-->
<div class="well well-sm r_float"><div class="r_float"><a href="./s_programmation.php" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a target="_blank" title="Imprimer" href="<?php echo $editFormAction."&down=1"; ?>" class="button"><img src="./images/print.png" width='20' height='20' alt='Modifier' /></a></div>
</div>
<div class="clear h0">&nbsp;</div>
<?php } else { ?>

<center><?php //include "./includes/print_header.php"; ?></center>

<?php } ?>
<div class="well well-sm"><strong>Chronogramme des activit&eacute;s du PTBA <?php echo "$lib_version_ptba"; ?>&nbsp;&nbsp; <span style="background-color:#FFCC33"><?php if(isset($_GET["acteur"]) && $_GET["acteur"]==0 && $_GET["acteur"]!="") echo  "&nbsp;(<u>Partenaires</u>)&nbsp;"; elseif(isset($_GET["acteur"]) && $_GET["acteur"]==1) echo  "&nbsp;(<u>PDAIG</u>)&nbsp;"; ?></span></strong></div>

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-bordered table-responsive">
            <thead>
            <tr bgcolor="<?php echo (!isset($_GET["down"]))?'':'#E4E4E4'; ?>">
              <th rowspan="3" align="center">Code</th>
              <th rowspan="3" align="center">Activit&eacute;s</th>
              <th rowspan="3">Responsables de r&eacute;alisation</th>
              <th rowspan="2" colspan="<?php echo count($tableauMois); ?>">Chronogramme</th>
              <?php if(count($destinateur_array)>0){ ?>
              <th colspan="<?php echo count($destinateur_array)+1; ?>">Budget global annuel (F CFA)</th>
              <?php } ?>
            </tr>
            <tr bgcolor="<?php echo (!isset($_GET["down"]))?'':'#E4E4E4'; ?>">
              <?php if(count($destinateur_array)>0){ ?>
              <th colspan="<?php echo count($destinateur_array); ?>">Parts par Bailleurs</th>
              <?php } ?>
              <th rowspan="2" width="100">Budget total</th>
            </tr>
            <tr bgcolor="<?php echo (!isset($_GET["down"]))?'':'#E4E4E4'; ?>">
              <?php foreach($tableauMois as $vmois){ $amois = explode('<>',$vmois);

$nmois = $amois[2]; ?>
              <th width="20"><?php echo $nmois; ?></th>
              <?php } ?>
              <?php foreach($destinateur_array as $a){ ?>
              <th width="100"><?php echo isset($bailleur_array[$a])?$bailleur_array[$a]:$a; ?></th>
              <?php } ?>
            </tr>
            </thead>
<?php number_format(0, 0, ',', ' '); if($totalRows_edit_ms>0) { $totalg = array(); foreach($row_edit_ms as $row_edit_ms){  $total = array(); 
$code = $row_edit_ms['code'];
if(isset($_GET["acteur"]) && $_GET["acteur"]!="") {$iactget=$_GET["acteur"]; $wheract="AND fin=$iactget"; } else $wheract="";
$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."ptba where projet='".$_SESSION["clp_projet"]."' and annee='$annee' and code_activite_ptba like '$code%' $wheract ORDER By code_activite_ptba asc";
  try{
    $liste_rec = $pdar_connexion->prepare($query_liste_rec);
    $liste_rec->execute();
    $row_liste_rec = $liste_rec ->fetchAll();
    $totalRows_liste_rec = $liste_rec->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if($totalRows_liste_rec>0) {$i=0; $t=0; $p2=$p1="j"; $titre = $row_edit_ms['intitule'];  ?>
            <tr bgcolor="#BED694">
              <td colspan="<?php echo count($destinateur_array)+count($tableauMois)+4; ?>" align="center" style="background-color: #BED694;">
                <b><?php echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?></b>
              </td>
            </tr>
<?php foreach($row_liste_rec as $row_liste_rec){ $code_act = $row_liste_rec['code_activite_ptba'];  $actc = explode(",", $row_liste_rec['acteur_conserne']); ?>
<?php //if(isset($_GET["acteur"]) && in_array($_GET["acteur"], $actc, TRUE)) echo  "Opérateur: <u>".$Nacteur_array[$_GET["acteur"]]."</u>"; ?>
<?php //if(in_array($imois, $a, TRUE)) echo "bgcolor='#EAEAEA'"; ?>
            <tr>
<td><?php echo "<b>".$row_liste_rec['code_activite_ptba'].'</b>'; ?></td>
<td><?php echo $row_liste_rec['intitule_activite_ptba']; ?></td>
<td valign="middle">
<?php 

 $a = explode(",", $row_liste_rec['debut']);
 foreach($acteur_array as $vacteur){ $aacteur = explode('!!',$vacteur); $iacteur = $aacteur[0]; ?>
<?php echo (in_array($iacteur, $actc, TRUE))?$aacteur[1]."/ ":''; ?>
 <?php } echo $row_liste_rec["responsable"]; ?>
 </td>
<?php 
$a = explode(",", $row_liste_rec['debut']);
foreach($tableauMois as $vmois){
$amois = explode('<>',$vmois);
$imois = $amois[1];

?>
<td <?php if(in_array($imois, $a, TRUE)) echo "bgcolor='#EAEAEA'"; ?>>&nbsp;</td>
<?php }
if(isset($tableauCoutSaisi[$row_liste_rec["id_ptba"]]))  $cout_saisi=$tableauCoutSaisi[$row_liste_rec["id_ptba"]]["total"]; else $cout_saisi="";
  //if(isset($cout_array[$row_liste_rec["code_activite_ptba"]]))  $cout_importe=$cout_array[$row_liste_rec["code_activite_ptba"]]; else $cout_importe="";
?>
<?php foreach($destinateur_array as $a=>$b){ ?>
<td align="right" nowrap="nowrap"><?php if(isset($tableauCoutSaisi[$row_liste_rec["id_ptba"]][$a]) && $tableauCoutSaisi[$row_liste_rec["id_ptba"]][$a]>0){ echo number_format($tableauCoutSaisi[$row_liste_rec["id_ptba"]][$a], 0, ',', ' '); if(!isset($total[$a])) $total[$a] = $tableauCoutSaisi[$row_liste_rec["id_ptba"]][$a]; else $total[$a] += $tableauCoutSaisi[$row_liste_rec["id_ptba"]][$a]; 
 if(!isset($totalg[$a])) $totalg[$a] = $tableauCoutSaisi[$row_liste_rec["id_ptba"]][$a]; else $totalg[$a] += $tableauCoutSaisi[$row_liste_rec["id_ptba"]][$a];  } else echo "-"; ?></td>
<?php } ?>
<td align="right" nowrap="nowrap"><?php if($cout_saisi!=""){ echo number_format($cout_saisi, 0, ',', ' ');
 if(!isset($total["total"])) $total["total"] = $cout_saisi; else $total["total"] += $cout_saisi;
 if(!isset($totalg["total"])) $totalg["total"] = $cout_saisi; else $totalg["total"] += $cout_saisi; } else echo "-"; ?></td>
            </tr>
            <?php } ?>
			
			    <tr>
        <td colspan="<?php echo count($tableauMois)+3; ?>" align="center"><strong>Total </strong><u><?php echo $titre; ?></u>:</td>
        <?php foreach($destinateur_array as $a=>$b){ ?>
        <td align="right" nowrap="nowrap"><strong><?php if(isset($total[$a])) echo number_format($total[$a], 0, ',', ' '); else echo "-"; ?></strong></td>
        <?php } ?>
        <td align="right" nowrap="nowrap" style="background-color:#BED694; color:#FFFFFF"><strong><?php if(isset($total["total"])) echo number_format($total["total"], 0, ',', ' '); else echo "-"; ?></strong></td>
      </tr>
	  
            <?php } else { ?>
<!--            <tr>
              <td colspan="<?php echo count($destinateur_array)+count($tableauMois)+4; ?>"><div align="center"><span class="Style4"><em><strong>Aucune activit&eacute; enregistr&eacute;e dans la composante <?php //echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?> ! </strong></em></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span></div></td>
            </tr>-->
            <?php }  ?>
      <?php }  $titre="Total général"; ?>
      <tr>
        <td colspan="<?php echo count($tableauMois)+3; ?>" align="center"><strong><?php echo $titre; ?></strong></td>
        <?php foreach($destinateur_array as $a=>$b){ ?>
        <td align="right" nowrap="nowrap"><strong><?php if(isset($totalg[$a])) echo number_format($totalg[$a], 0, ',', ' '); else echo "-"; ?></strong></td>
        <?php } ?>
        <td align="right" nowrap="nowrap" style="background-color:#000000; color:#FFFFFF"><strong><?php if(isset($totalg["total"])) echo number_format($totalg["total"], 0, ',', ' '); else echo "-"; ?></strong></td>
      </tr>
      <?php } else { ?>
      <tr>
        <td colspan="<?php echo count($destinateur_array)+count($tableauMois)+4; ?>" align="center"><strong><em>Aucune composante trouv&eacute;e!</em></strong></td>
      </tr>
      <?php } ?>
</table>

</div>
<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>   <?php if(!isset($_GET["down"])) include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>

</body>
</html>