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

if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){

//header("Content-Type: application/vnd.ms-excel charset=ISO-8859-15'");

header("Expires: 0");

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header("content-disposition: attachment;filename=tableau_statistique_marche.xls"); }

else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){

header("Content-Type: application/vnd.ms-word charset=ISO-8859-15'");

header("Expires: 0");

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header("content-disposition: attachment;filename=tableau_statistique_marche.doc"); }

else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="p"){



require_once('./tcpdf/tcpdf.php');



// create new PDF document

$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$PDF_HEADER_TITLE = "Tableau de suivi des DANO";

$PDF_HEADER_STRING = "Suivi DANO";



// set document information

$pdf->SetCreator(PDF_CREATOR);

$pdf->SetAuthor('Ruche');

$pdf->SetTitle($PDF_HEADER_TITLE);

$pdf->SetSubject($PDF_HEADER_STRING);

$pdf->SetKeywords('PDF, DANO, dno');



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

  //include("./print_instance_dno_ida_pdf.php");

  $content = ob_get_contents(); // get the contents of the output buffer

  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering



$html = utf8_encode($content);

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('tableau_suivi_dano.pdf', 'D');

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

header("content-disposition: attachment;filename=ano_instance_bailleur.pdf"); */

exit;



 } ?>

<?php



if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else {$annee=0;}
$cmp = 0;
if(isset($_GET['cmp']) && intval($_GET['cmp'])>0) $cmp = intval($_GET['cmp']);



//fonction calcul nb jour

function NbJours($debut, $fin) {

  $tDeb = explode("-", $debut);

  $tFin = explode("-", $fin);

  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);

  return (($diff / 86400)+1);

}

$editFormAction1 = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction1 .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//liste situation marché
$query_liste_situation = "SELECT * FROM ".$database_connect_prefix."situation_marche ORDER BY code asc";
             try{
    $liste_situation = $pdar_connexion->prepare($query_liste_situation);
    $liste_situation->execute();
    $row_liste_situation1 = $liste_situation ->fetchAll();
    $totalRows_liste_situation = $liste_situation->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


$query_liste_categorie = "SELECT * FROM ".$database_connect_prefix."categorie_marche ORDER BY nom_categorie asc";
             try{
    $liste_categorie = $pdar_connexion->prepare($query_liste_categorie);
    $liste_categorie->execute();
    $row_liste_categorie = $liste_categorie ->fetchAll();
    $totalRows_liste_categorie = $liste_categorie->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$TableauEtape = array();
if($totalRows_liste_categorie>0){ foreach($row_liste_categorie as $row_liste_categorie){
$TableauEtape[]=$row_liste_categorie["code_categorie"]."<>".$row_liste_categorie["nom_categorie"]; } }

 $query_liste_marche = "SELECT count(code_marche) as nmarche, etape, categorie, sum(montant_usd) as montant   FROM ".$database_connect_prefix."plan_marche, ".$database_connect_prefix."suivi_plan_marche where id_marche=marche and projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."plan_marche.periode=$annee
  and etape in(select max(etape) from ".$database_connect_prefix."suivi_plan_marche where date_reelle is not null group by marche)  group by etape, categorie  order by  etape asc";
               try{
    $liste_marche = $pdar_connexion->prepare($query_liste_marche);
    $liste_marche->execute();
    $row_liste_marche = $liste_marche ->fetchAll();
    $totalRows_liste_marche = $liste_marche->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $N_marche_array = array();
  $M_marche_array = array();
  $C_marche_array = array();
if($totalRows_liste_marche>0){ foreach($row_liste_marche as $row_liste_marche){
 $C_marche_array[$row_liste_marche["etape"]]=$row_liste_marche["etape"]; 
 $N_marche_array[$row_liste_marche["etape"]][$row_liste_marche["categorie"]]=$row_liste_marche["nmarche"]; 
 $M_marche_array[$row_liste_marche["etape"]][$row_liste_marche["categorie"]]=$row_liste_marche["montant"]; 
} }

   $query_total_marche = "SELECT count(code_marche) as nmarche, sum(montant_usd) as montant FROM ".$database_connect_prefix."plan_marche where projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."plan_marche.periode=$annee"; 
                 try{
    $total_marche = $pdar_connexion->prepare($query_total_marche);
    $total_marche->execute();
    $row_total_marche = $total_marche ->fetch();
    $totalRows_total_marche = $total_marche->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
//where FIND_IN_SET(etape,'".$row_liste_situation['etape_concerne']."')

$query_liste_version = "SELECT * FROM ".$database_connect_prefix."version_plan_marche ORDER BY date_version desc";
                 try{
    $liste_version = $pdar_connexion->prepare($query_liste_version);
    $liste_version->execute();
    $row_liste_version = $liste_version ->fetchAll();
    $totalRows_liste_version = $liste_version->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

 $Date_periode_array = array();
if($totalRows_liste_version>0){ foreach($row_liste_version as $row_liste_version){
 $Date_periode_array[$row_liste_version["id_version"]]=$row_liste_version["date_version"]; 
}}

 $query_liste_marche_non_d = "SELECT count(code_marche) as nmarche, categorie, sum(montant_usd) as montant   FROM ".$database_connect_prefix."plan_marche where  projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."plan_marche.periode=$annee and id_marche not in (select marche from ".$database_connect_prefix."suivi_plan_marche)  group by categorie  order by  categorie asc";
                   try{
    $liste_marche_non_d = $pdar_connexion->prepare($query_liste_marche_non_d);
    $liste_marche_non_d->execute();
    $row_liste_marche_non_d1 = $liste_marche_non_d ->fetchAll();
    $totalRows_liste_marche_non_d = $liste_marche_non_d->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $N_marche_nd_array = array();
  $M_marche_nd_array = array();
if($totalRows_liste_marche_non_d>0){ foreach($row_liste_marche_non_d as $row_liste_marche_non_d){
 $N_marche_nd_array[$row_liste_marche_non_d["categorie"]]=$row_liste_marche_non_d["nmarche"]; 
 $M_marche_nd_array[$row_liste_marche_non_d["categorie"]]=$row_liste_marche_non_d["montant"]; 
}}
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


  <link href='<?php print $config->theme_folder; ?>/css.css' rel='stylesheet' type='text/css'>


  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>

  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>

  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/lodash.compat.min.js"></script>


  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>

  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>

  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>

  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/breakpoints.js"></script>

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

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/app.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/plugins.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/plugins.form-components.js"></script>

<!--

  <script type="text/javascript" src="<?php print $config->
script_folder; ?>/custom.js"></script>

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

} .table tbody tr td {vertical-align: middle; }

</style>

<div class="contenu">

<?php if(!isset($_GET["down"])){  ?>

<div class="well well-sm r_float"><div class="r_float"><a href="./s_ppm.php" class="button">Retour</a></div>

<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction1."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>

<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction1."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>

<!--<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format PDF" href="<?php echo $editFormAction1."&down=1&t=p"; ?>" class="button"><img src="./images/pdf.png" width='20' height='20' alt='Modifier' /></a></div>--></div>

<div class="clear h0">&nbsp;</div>

<?php } else { ?>



<center><?php //include "./includes/print_header.php"; ?></center>



<?php } ?>

<div class="well well-sm"><strong>Tableau du plan de passation des march&eacute;s, actualis&eacute; au <span style="background-color:#FFFF00"><?php if(isset($Date_periode_array[$annee])) echo implode('/',array_reverse(explode('-',$Date_periode_array[$annee]))); ?></span></strong></div>

<h3>1. Nombre de march&eacute;s	</h3>

<table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" align="center" >
  <thead>
 
 
    <tr>
      <td><div align="left"><strong>Code</strong></div></td>
      <td><div align="left"><strong>Libell&eacute;</strong></div></td>
      
	   <td>&nbsp;</td>
	   <?php foreach($TableauEtape as $vmois){
              $amois = explode('<>',$vmois); ?>
              <td align="center"><?php echo $amois[0]; ?> </td>
              <?php } ?>
     
      <td align="center" width="80" ><div align="center"><strong>Total</strong></div></td>
      <td align="center" width="80" ><div align="center"><strong>Proportion</strong></div></td>
    </tr>
	
  </thead>
    <?php  if($totalRows_liste_marche_non_d>0) { ?>
  <tr>
    <td><strong>00</strong></td>
    <td><strong>March&eacute;s non d&eacute;marr&eacute;s</strong></td>
    <td>&nbsp;</td>
    <?php $ntmnd=0; foreach($TableauEtape as $vmois){
              $amois = explode('<>',$vmois); ?>
              <td align="center"><?php if(isset($N_marche_nd_array[$amois[0]])) {echo $N_marche_nd_array[$amois[0]]; $ntmnd=$ntmnd+$N_marche_nd_array[$amois[0]];} ?> </td>
              <?php } ?>
    <td align="center"><?php if($ntmnd>0) echo $ntmnd; ?></td>
    <td align="left" nowrap="nowrap"><strong>
 <div style="width: <?php $color="#FFD700"; if($row_total_marche['nmarche']>0 && $ntmnd>0) echo $ntmnd*100/$row_total_marche['nmarche']; ?>%; background-color: <?php echo $color; ?>; color:#FFD700"><?php if($row_total_marche['nmarche']>0 && $ntmnd>0) echo "&nbsp;"; ?></div><?php if($row_total_marche['nmarche']>0 && $ntmnd>0) echo number_format($ntmnd*100/$row_total_marche['nmarche'], 0, ',', ' ')." %"; ?>
    </strong></td>
  </tr>
   <?php } ?>
  <?php if($totalRows_liste_situation>0) {$i=0; $total_cat=array(); foreach($row_liste_marche_non_d1 as $row_liste_situation){ $id = $row_liste_situation['code'];  ?>
  
  <tr>
    <td><div align="left"><?php echo $row_liste_situation['code'];  ?></div></td>
    <td><div align="left"><?php echo $row_liste_situation['intitule']; ?></div></td>
    <td>&nbsp; </td>
    <?php   $tetape=explode(', ',$row_liste_situation['etape_concerne']);
	//print_r($tetape);
	//exit;
	  $tntm=0; foreach($TableauEtape as $vmois){ $amois = explode('<>',$vmois);  ?>
	
	     <td align="center"><?php $ntm=0; foreach($tetape as $vetape){ if(isset($N_marche_array[$vetape][$amois[0]]))
		  { // echo $N_marche_array[$vetape][$amois[0]]; 
		  
		  if(isset($total_cat[$amois[0]])) $total_cat[$amois[0]]=$total_cat[$amois[0]]+$N_marche_array[$vetape][$amois[0]];
		   else $total_cat[$amois[0]]=$N_marche_array[$vetape][$amois[0]]; 
		  
		   $ntm=$ntm+$N_marche_array[$vetape][$amois[0]]; } } $tntm=$tntm+$ntm;  if($ntm>0) echo $ntm; ?> </td>
			   		  
              <?php } ?>
    
    <td align="center"> <?php if($tntm>0) echo $tntm; ?>&nbsp;</td>
    <td align="left" nowrap="nowrap"><strong>
 <div style="width: <?php $color="#FFD700"; if($row_total_marche['nmarche']>0 && $tntm>0) echo $tntm*100/$row_total_marche['nmarche']; ?>%; background-color: <?php echo $color; ?>; color:#FFD700"><?php if($row_total_marche['nmarche']>0 && $tntm>0) echo "&nbsp;"; ?></div><?php if($row_total_marche['nmarche']>0 && $tntm>0) echo  number_format($tntm*100/$row_total_marche['nmarche'], 0, ',', ' ')." %"; ?>
    </strong></td>
  </tr>
 
  <?php } ?>
   <tr>
    <td colspan="3"><div align="right"><strong>Total</strong></div></td>
    <?php foreach($TableauEtape as $vmois){
              $amois = explode('<>',$vmois); ?>
              <td align="center"><?php if(isset($N_marche_nd_array[$amois[0]])) $ncnd=$N_marche_nd_array[$amois[0]]; else $ncnd=0;  if(isset($total_cat[$amois[0]])) $ncd=$total_cat[$amois[0]]; else  $ncd=0; if($ncd+$ncnd!=0) echo $ncd+$ncnd;  ?> </td>
              <?php } ?>
    <td align="center"><?php if($row_total_marche['nmarche']>0) echo $row_total_marche['nmarche']; ?></td>
    <td align="center">&nbsp;</td>
   </tr>
  <?php } ?>
</table>
</div>

            </div>

        <h3>2. Montant des march&eacute;s	</h3>
        <table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" align="center" >
          <thead>
		  
            <tr>
              <td><div align="left"><strong>Code</strong></div></td>
              <td><div align="left"><strong>Libell&eacute;</strong></div></td>
              <td>&nbsp;</td>
              <?php foreach($TableauEtape as $vmois){
              $amois = explode('<>',$vmois); ?>
              <td align="center"><?php echo $amois[0]; ?> </td>
              <?php } ?>
              <td align="center" width="80" ><div align="center"><strong>Total</strong></div></td>
              <td align="center" width="80" ><div align="center"><strong>Proportion</strong></div></td>
            </tr>
          </thead>
		    <?php  if($totalRows_liste_marche_non_d>0) { ?>
		   <tr>
    <td><strong>00</strong></td>
    <td><strong>March&eacute;s non d&eacute;marr&eacute;s</strong></td>
    <td>&nbsp;</td>
    <?php $mtmnd=0; foreach($TableauEtape as $vmois){
              $amois = explode('<>',$vmois); ?>
              <td align="center" nowrap="nowrap"><?php if(isset($M_marche_nd_array[$amois[0]])) {echo number_format($M_marche_nd_array[$amois[0]], 0, ',', ' '); $mtmnd=$mtmnd+$M_marche_nd_array[$amois[0]];} ?> </td>
              <?php } ?>
    <td align="center" nowrap="nowrap"><?php if($mtmnd>0) echo number_format($mtmnd, 0, ',', ' '); ?></td>
    <td align="left" nowrap="nowrap"><strong>
 <div style="width: <?php $color="#FFD700"; if($row_total_marche['montant']>0 && $mtmnd>0) echo $mtmnd*100/$row_total_marche['montant']; ?>%; background-color: <?php echo $color; ?>; color:#FFD700"><?php if($row_total_marche['montant']>0 && $mtmnd>0) echo "&nbsp;"; ?></div><?php if($row_total_marche['montant']>0 && $mtmnd>0) echo number_format($mtmnd*100/$row_total_marche['montant'], 0, ',', ' ')." %"; ?>
    </strong></td>
  </tr>
    <?php  } ?>
          <?php if($totalRows_liste_situation>0) {$i=0; $montant_cat=array(); foreach($row_liste_situation1 as $row_liste_situation){ $id = $row_liste_situation['code'];  ?>
          <tr>
            <td><div align="left"><?php echo $row_liste_situation['code'];  ?></div></td>
            <td><div align="left"><?php echo $row_liste_situation['intitule']; ?></div></td>
            <td>&nbsp;  </td>
            <?php  $tetape=explode(', ',$row_liste_situation['etape_concerne']); $ntmt=0; foreach($TableauEtape as $vmois){ $amois = explode('<>',$vmois);  ?>
            <td align="center" nowrap="nowrap"><?php $ntm=0; foreach($tetape as $vetape){ if(isset($M_marche_array[$vetape][$amois[0]]))
		  { //echo number_format($M_marche_array[$vetape][$amois[0]], 0, ',', ' ');
		   if(isset($montant_cat[$amois[0]])) $montant_cat[$amois[0]]=$montant_cat[$amois[0]]+$M_marche_array[$vetape][$amois[0]]; else $montant_cat[$amois[0]]=$M_marche_array[$vetape][$amois[0]]; $ntm=$ntm+$M_marche_array[$vetape][$amois[0]];}}  $ntmt=$ntmt+$ntm; if($ntm>0) echo number_format($ntm, 0, ',', ' '); ?>            </td>
            <?php } ?>
            <td align="center" nowrap="nowrap"><?php if($ntmt>0) echo number_format($ntmt, 0, ',', ' '); ?>
              &nbsp;</td>
            <td align="left">
              <strong>
 <div style="width: <?php $color="#FFD700"; if($row_total_marche['montant']>0 && $ntmt>0) echo $ntmt*100/$row_total_marche['montant']; ?>%; background-color: <?php echo $color; ?>; color:#FFD700"><?php if($row_total_marche['montant']>0 && $ntmt>0) echo "&nbsp;"; ?></div><?php if($row_total_marche['montant']>0 && $ntmt>0) echo number_format($ntmt*100/$row_total_marche['montant'], 0, ',', ' ')." %"; ?>
    </strong>
            </td>
          </tr>
          <?php }?>
          <tr>
            <td colspan="3"><div align="right"><strong>Total</strong></div></td>
            <?php foreach($TableauEtape as $vmois){
              $amois = explode('<>',$vmois); ?>
            <td align="center" nowrap="nowrap"><?php //if(isset($montant_cat[$amois[0]])) echo number_format($montant_cat[$amois[0]], 0, ',', ' '); ?>
            <?php if(isset($M_marche_nd_array[$amois[0]])) $mcnd=$M_marche_nd_array[$amois[0]]; else $mcnd=0;  if(isset($montant_cat[$amois[0]])) $mcd=$montant_cat[$amois[0]]; else  $mcd=0; if($mcd+$mcnd!=0) echo number_format($mcd+$mcnd, 0, ',', ' ');  ?></td>
            <?php } ?>
            <td align="center" nowrap="nowrap"><?php if($row_total_marche['montant']>0) echo number_format($row_total_marche['montant'], 0, ',', ' '); ?></td>
            <td align="center">&nbsp;</td>
          </tr>
          <?php } ?>
        </table>
        </div>



        </div>

    </div>   <?php if(!isset($_GET["down"])) include_once 'modal_add.php'; ?>

    <?php include_once("includes/footer.php"); ?>

</div>



</body>

</html>