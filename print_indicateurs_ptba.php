<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & DÃ©veloppement: SEYA SERVICES */
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
header("content-disposition: attachment;filename=indicateurs_ptba.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=indicateurs_ptba.doc"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="p"){

require_once('./tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$PDF_HEADER_TITLE = "Indicateurs des activités du PTBA";
$PDF_HEADER_STRING = "Indicateurs des activités du PTBA";

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Ruche');
$pdf->SetTitle($PDF_HEADER_TITLE);
$pdf->SetSubject($PDF_HEADER_STRING);
$pdf->SetKeywords('PDF, Résultat, Indicateurs des activités du PTBA');

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
  include("./print_indicateurs_ptba_pdf.php");
  $content = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering

$html = utf8_encode($content);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('indicateurs_ptba.pdf', 'D');
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
$annee=(isset($_GET['annee']))?$_GET['annee']:date("Y");
$ugl=(isset($_GET['ugl']))?$_GET['ugl']:$_SESSION["clp_structure"];
$array_indic = array("OUI/NON","texte");
$query_edit_ms = "SELECT code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=1 and projet='".$_SESSION["clp_projet"]."' ORDER BY code asc";
  try{
    $edit_ms = $pdar_connexion->prepare($query_edit_ms);
    $edit_ms->execute();
    $row_edit_ms = $edit_ms ->fetchAll();
    $totalRows_edit_ms = $edit_ms->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//if(isset($_GET["acteur"])) {$iactget=$_GET["acteur"]; $wheract="AND FIND_IN_SET('$iactget', region)"; $whercible="where region='$iactget'"; } else {$wheract=""; $whercible="";}
$wheract=""; $whercible="";
$query_indicateur = "SELECT ".$database_connect_prefix."indicateur_tache.* FROM ".$database_connect_prefix."indicateur_tache, ".$database_connect_prefix."ptba where  id_ptba=id_activite and ptba.projet='".$_SESSION["clp_projet"]."' and ptba.annee='$annee' $wheract ORDER BY code_indicateur_ptba asc";
  try{
    $indicateur = $pdar_connexion->prepare($query_indicateur);
    $indicateur->execute();
    $row_indicateur = $indicateur ->fetchAll();
    $totalRows_indicateur = $indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$indicateur_array = $unite_array = array();
if($totalRows_indicateur>0) { foreach($row_indicateur as $row_indicateur){
$indicateur_array[$row_indicateur["id_activite"]][$row_indicateur["id_indicateur_tache"]] = $row_indicateur;
$unite_array[$row_indicateur["id_indicateur_tache"]] = $row_indicateur["unite"];
}  }

//semestre precedent
$query_cible_indicateur = "SELECT * FROM ".$database_connect_prefix."cible_indicateur_trimestre $whercible";
  try{
    $cible_indicateur = $pdar_connexion->prepare($query_cible_indicateur);
    $cible_indicateur->execute();
    $row_cible_indicateur = $cible_indicateur ->fetchAll();
    $totalRows_cible_indicateur = $cible_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableau_cible_indicateur_array = array();
  if($totalRows_cible_indicateur>0){  foreach($row_cible_indicateur as $row_cible_indicateur){      //(!in_array($unite_array[$row_cible_indicateur["indicateur"]],$array_indic))?   :"cible_txt"
    if(!isset($tableau_cible_indicateur_array[$row_cible_indicateur["indicateur"]][$row_cible_indicateur["trimestre"]]))
    $tableau_cible_indicateur_array[$row_cible_indicateur["indicateur"]][$row_cible_indicateur["trimestre"]] = $row_cible_indicateur["cible"];
    else 
    $tableau_cible_indicateur_array[$row_cible_indicateur["indicateur"]][$row_cible_indicateur["trimestre"]] += $row_cible_indicateur["cible"];
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
.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; } .marquer{background: #FFFF00!important; }
</style>
<div class="contenu">
<?php if(!isset($_GET["down"])){  ?>
<!--<form name="form<?php// echo $annee; ?>" id="form<?php //echo $annee; ?>" method="get" action="<?php //echo "print_indicateurs_ptba.php?annee=".$annee; ?>" class="pull-left">

 <select name="acteur" onchange="form<?php //echo $annee; ?>.submit();" style="background-color: #FFFF00; padding: 7px;" class="btn p11">

              <option value="">-- Choisissez une partie --</option>
			 <option value="0">Partrenaires </option>
			 <option value="1">PDAIG uniquement</option>
         <?php 				 // if($totalRows_liste_prestataire>0) { do {
?>
            <option <?php //if(isset($id_ms) && $id_ms==$row_liste_prestataire['id_acteur']) {echo 'SELECTED="selected"';  $nom=$row_liste_prestataire['objet'];}  ?> value="<?php //echo  $row_liste_prestataire['code_ugl']; ?>"> <?php //echo "<b>".$row_liste_prestataire['nom_ugl']."</b> ";?>
            </option>
            <?php //} }
?>
 <option value="">Toutes les activit�s</option>
   </select>
  <input type="hidden" name="annee" value="<?php //echo $annee; ?>" />

</form>-->
<div class="well well-sm r_float"><div class="r_float"><a href="./<?php echo  "s_programmation.php"; ?>" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a target="_blank" title="Imprimer" href="<?php echo $editFormAction."&down=1"; ?>" class="button"><img src="./images/print.png" width='20' height='20' alt='Modifier' /></a></div>
</div>
<div class="clear h0">&nbsp;</div>
<?php } else { ?>

<!--<center><?php //include "./includes/print_header.php"; ?></center>       -->

<?php } ?>
<div class="well well-sm"><strong>Chronogramme des Indicateurs du PTBA <?php echo "$lib_version_ptba"; ?>&nbsp;&nbsp;<span style="background-color:#FFCC33">
  <?php if(isset($_GET["acteur"]) && $_GET["acteur"]==0 && $_GET["acteur"]!="") echo  "&nbsp;(<u>Partenaires</u>)&nbsp;"; elseif(isset($_GET["acteur"]) && $_GET["acteur"]==1) echo  "&nbsp;(<u>PDAIG</u>)&nbsp;"; ?>
</span></strong></div>

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-responsive">
            <thead>
            <tr bgcolor="<?php echo (!isset($_GET["down"]))?'':'#E4E4E4'; ?>">
              <th rowspan="2" align="left">ACTIVITES</th>
              <!--<th rowspan="2" align="center"><b>N&deg;&nbsp;</b></th>-->
              <th rowspan="2" align="left">INDICATEUR</th>
              <th rowspan="2" align="left">UNITE</th>
              <th colspan="4" ><center>VALEUR CIBLE</center></th>
              <th rowspan="2" ><center>TOTAL</center></th>
            </tr>
            <tr bgcolor="<?php echo (!isset($_GET["down"]))?'':'#E4E4E4'; ?>">
              <?php for($j=1;$j<=4;$j++){ ?>
              <th align="center"><center>Trimestre <?php echo $j; ?></center></th>
              <?php } ?>
            </tr>
            </thead>
<?php  if($totalRows_edit_ms>0) {  foreach($row_edit_ms as $row_edit_ms){
$code = $row_edit_ms['code'];
//if(isset($_GET["acteur"])) {$iactget=$_GET["acteur"]; $wheract="AND FIND_IN_SET('$iactget', region)"; } else $wheract="";
if(isset($_GET["acteur"]) && $_GET["acteur"]!="") {$iactget=$_GET["acteur"]; $wheract="AND fin=$iactget"; } else $wheract="";
$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."ptba where projet='".$_SESSION["clp_projet"]."' and annee='$annee' and code_activite_ptba like '$code%' $wheract ORDER by code_activite_ptba asc";
  try{
    $liste_rec = $pdar_connexion->prepare($query_liste_rec);
    $liste_rec->execute();
    $row_liste_rec = $liste_rec ->fetchAll();
    $totalRows_liste_rec = $liste_rec->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if($totalRows_liste_rec>0) {$i=0; $t=0; $p2=$p1="j"; ?>
            <tr bgcolor="#BED694">
              <td colspan="8" align="center" style="background-color: #BED694;">
                <b><?php echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?></b>
              </td>
            </tr>
<?php $row=""; foreach($row_liste_rec as $row_liste_rec){ $code_act = $row_liste_rec['code_activite_ptba']; $id_act = $row_liste_rec['id_ptba'];
if(isset($indicateur_array[$id_act])){ $k=0;
foreach($indicateur_array[$id_act] as $a=>$b){ $total = 0; $div = 0; ?>
            <tr>
<?php if(/*$row*/$k==0/*$b["code_indicateur_ptba"]*/){ ?>
<td rowspan="<?php echo count($indicateur_array[$id_act]); ?>"><?php echo "<b>".$row_liste_rec['code_activite_ptba'].'</b> : '.$row_liste_rec['intitule_activite_ptba']; ?></td>
<?php } ?>
<!--<td><?php //echo $b['code_indicateur_ptba']; ?></td>-->
<td><?php echo $b['intitule_indicateur_tache']; ?></td>
<td><?php echo $b['unite']; ?></td>
<?php for($j=1;$j<=4;$j++){ if(!in_array(strtoupper($b['unite']),$array_indic) && isset($tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j])) $total+= $tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j]; 
$vciblea=-1;

if(strtoupper($b['unite'])=="OUI/NON" && isset($tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j]) && $tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j]==0) $vciblea="Non"; elseif(strtoupper($b['unite'])=="OUI/NON" && isset($tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j]) && $tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j]==1) $vciblea="Oui"; elseif( isset($tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j])) $vciblea=$tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j];
?>
<td width="50" valign="middle" align="center"><?php  if(isset($vciblea) && $vciblea!=-1) echo $vciblea; if($vciblea!=-1) $div++; ?></td>
<?php } ?>
<td width="50" valign="middle" align="center"><?php $total = ($b['unite']=="%")?$total/$div:$total; echo (!in_array(strtoupper($b['unite']),$array_indic))?$total:'-'; ?></td>
            </tr>
            <?php $row=$b["code_indicateur_ptba"]; $k++; } } else { ?>
            <tr>
<td><?php echo "<b>".$row_liste_rec['code_activite_ptba'].'</b> : '.$row_liste_rec['intitule_activite_ptba']; ?></td>
              <td colspan="7"><div align="center"><span class="Style4"><em><strong>Aucun indicateur ! </strong></em></span></div></td>
            </tr>
            <?php }  ?>
<tr class="even">
  <td colspan="8"><div align="center" style="background-color:#CCCCCC; height: 2px;">&nbsp;</div></td>
</tr>
			<?php }  ?>
            <?php } else { ?>
<!--            <tr>
              <td colspan="8"><div align="center"><span class="Style4"><em><strong>Aucune activit&eacute; enregistr&eacute;e dans la composante <?php //echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?> ! </strong></em></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span></div></td>
            </tr>-->
            <?php }  ?>
      <?php }  } else { ?>
      <tr>
        <td colspan="8" align="center"><strong><em>Aucune composante trouv&eacute;e!</em></strong></td>
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