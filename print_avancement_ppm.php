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

header("Content-Type: application/vnd.ms-excel charset=UTF-8'");

header("Expires: 0");

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header("content-disposition: attachment;filename=tableau_avancement_PPM.xls"); }

else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){

header("Content-Type: application/vnd.ms-word charset=UTF-8'");

header("Expires: 0");

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header("content-disposition: attachment;filename=tableau_avancement_ppm.doc"); }

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

$pdf->Output('tableau_suivi_ppm.pdf', 'D');

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



if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else {$annee=date("Y");}
if(isset($_GET['version'])) {$version=$_GET['version'];} else {$version=0;}
if(isset($_GET['modele'])) {$modele=$_GET['modele'];} else {$modele="0";}
$cmp = 0;
if(isset($_GET['cmp']) && intval($_GET['cmp'])>0) $cmp = intval($_GET['cmp']);

$query_l_version = "SELECT * FROM ".$database_connect_prefix."version_plan_marche where id_version=$version";
             try{
    $l_version = $pdar_connexion->prepare($query_l_version);
    $l_version->execute();
    $row_l_version = $l_version ->fetch();
    $totalRows_l_version = $l_version->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

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

 $query_liste_marche = "SELECT *   FROM ".$database_connect_prefix."plan_marche where projet='".$_SESSION["clp_projet"]."' order by  composante, categorie, code_marche asc";
             try{
    $liste_marche = $pdar_connexion->prepare($query_liste_marche);
    $liste_marche->execute();
    $row_liste_marche = $liste_marche ->fetchAll();
    $totalRows_liste_marche = $liste_marche->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


$query_liste_filiere = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE projet='".$_SESSION["clp_projet"]."' and niveau=2";
           try{
    $liste_filiere = $pdar_connexion->prepare($query_liste_filiere);
    $liste_filiere->execute();
    $row_liste_filiere = $liste_filiere ->fetchAll();
    $totalRows_liste_filiere = $liste_filiere->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$codef_array = array();
if($totalRows_liste_filiere>0){ foreach($row_liste_filiere as $row_liste_filiere){  $codef_array[$row_liste_filiere["code"]]=$row_liste_filiere["intitule"]; } }

$query_liste_etape_plan = "SELECT sum(duree) as duree, modele_concerne, max(code) as codef, min(code) as coded FROM ".$database_connect_prefix."etape_marche  group BY modele_concerne asc";
           try{
    $liste_etape_plan = $pdar_connexion->prepare($query_liste_etape_plan);
    $liste_etape_plan->execute();
    $row_liste_etape_plan = $liste_etape_plan ->fetchAll();
    $totalRows_liste_etape_plan = $liste_etape_plan->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$duree_totale=0;
$TableauMaxEtapeModele =$TableauMinEtapeModele =$TableauEtape = array();
$DureeEtape = array();
if($totalRows_liste_etape_plan>0){ foreach($row_liste_etape_plan as $row_liste_etape_plan){
 $TableauEtape[$row_liste_etape_plan["modele_concerne"]]=$row_liste_etape_plan["duree"]; 
 $TableauMaxEtapeModele[$row_liste_etape_plan["modele_concerne"]]=$row_liste_etape_plan["codef"]; 
 $TableauMinEtapeModele[$row_liste_etape_plan["modele_concerne"]]=$row_liste_etape_plan["coded"]; 
 } }

//max etape et date
$query_liste_detape_suivi = "SELECT MAX( date_reelle ) AS DATE, marche, MIN( date_reelle ) AS DATE_min FROM  ".$database_connect_prefix."suivi_plan_marche ,  ".$database_connect_prefix."etape_marche WHERE id_etape = etape GROUP BY marche";
 try{
    $liste_detape_suivi = $pdar_connexion->prepare($query_liste_detape_suivi);
    $liste_detape_suivi->execute();
    $row_liste_detape_suivi = $liste_detape_suivi ->fetchAll();
    $totalRows_liste_detape_suivi = $liste_detape_suivi->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$duree_totale=0;
$TableauIdmEtape =$TableauIdEtape =$TableauLibelleEtape = $TableauDateMin =$TableauDerniereDate = array();
$DureeEtape = array();
if($totalRows_liste_detape_suivi>0){ foreach($row_liste_detape_suivi as $row_liste_detape_suivi){ 
//derniere etape suivie
$marchetape=$row_liste_detape_suivi["marche"];
$dateetape=$row_liste_detape_suivi["DATE"];
 $query_liste_der_etape = "SELECT code, intitule, max(id_suivi_plan)   FROM  ".$database_connect_prefix."suivi_plan_marche ,  ".$database_connect_prefix."etape_marche WHERE id_etape = etape and date_reelle='$dateetape' and marche='$marchetape' group by intitule, code";
   try{
    $liste_der_etape = $pdar_connexion->prepare($query_liste_der_etape);
    $liste_der_etape->execute();
    $row_liste_der_etape = $liste_der_etape ->fetch();
    $totalRows_liste_der_etape = $liste_der_etape->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  
  //Première etape suivie
$datemetape=$row_liste_detape_suivi["DATE_min"];
 $query_liste_first_etape = "SELECT code, min(id_suivi_plan)   FROM  ".$database_connect_prefix."suivi_plan_marche ,  ".$database_connect_prefix."etape_marche WHERE id_etape = etape and date_reelle='$datemetape' and marche='$marchetape' group by code";
     try{
    $liste_first_etape = $pdar_connexion->prepare($query_liste_first_etape);
    $liste_first_etape->execute();
    $row_liste_first_etape = $liste_first_etape ->fetch();
    $totalRows_liste_first_etape = $liste_first_etape->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); } 
  
  
if(isset($row_liste_der_etape["intitule"])) $TableauLibelleEtape[$row_liste_detape_suivi["marche"]]=$row_liste_der_etape["intitule"];
if(isset($row_liste_der_etape["code"])) $TableauIdEtape[$row_liste_detape_suivi["marche"]]=$row_liste_der_etape["code"];

if(isset($row_liste_first_etape["code"])) $TableauIdmEtape[$row_liste_detape_suivi["marche"]]=$row_liste_first_etape["code"];

$TableauDateMin[$row_liste_detape_suivi["marche"]]=$row_liste_detape_suivi["DATE_min"];
$TableauDerniereDate[$row_liste_detape_suivi["marche"]]=$row_liste_detape_suivi["DATE"];
 } }

$query_liste_modele = "SELECT * FROM ".$database_connect_prefix."modele_marche ORDER BY code asc";
     try{
    $liste_modele = $pdar_connexion->prepare($query_liste_modele);
    $liste_modele->execute();
    $row_liste_modele = $liste_modele ->fetchAll();
    $totalRows_liste_modele = $liste_modele->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); } 

$modele_array = array();
    if($totalRows_liste_modele>0){
	 foreach($row_liste_modele as $row_liste_modele){  $modele_array[$row_liste_modele["id_modele"]]=$row_liste_modele['categorie']." - ".$row_liste_modele['examen']; } }
	
$query_liste_montant_marche = "SELECT montant_usd, marche FROM ".$database_connect_prefix."suivi_montant_marche ORDER BY marche asc";
     try{
    $liste_montant_marche = $pdar_connexion->prepare($query_liste_montant_marche);
    $liste_montant_marche->execute();
    $row_liste_montant_marche = $liste_montant_marche ->fetchAll();
    $totalRows_liste_montant_marche = $liste_montant_marche->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); } 

$liste_montant_marche_array = array();
    if($totalRows_liste_montant_marche>0){
	foreach($row_liste_montant_marche as $row_liste_montant_marche){ $liste_montant_marche_array[$row_liste_montant_marche["marche"]]=$row_liste_montant_marche['montant_usd']; }}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<?php if(!isset($_GET["down"])){  ?>

<head>

  <title><?php print $config->sitename; ?></title>

  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />

  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

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

} .table tbody tr td {vertical-align: middle; }

</style>

<div class="contenu">

<?php if(!isset($_GET["down"])){  ?>
<div class="pull-left"><?php include "content/version_ppm.php"; ?></div>
<div class="well well-sm r_float"><div class="r_float"><a href="./s_ppm.php" class="button">Retour</a></div>

<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction1."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>

<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction1."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>

<!--<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format PDF" href="<?php echo $editFormAction1."&down=1&t=p"; ?>" class="button"><img src="./images/pdf.png" width='20' height='20' alt='Modifier' /></a></div>--></div>

<div class="clear h0">&nbsp;</div>

<?php } else { ?>



<center><?php //include "./includes/print_header.php"; ?></center>



<?php } ?>

<div class="well well-sm"><strong>Tableau du plan de passation des march&eacute;s, actualis&eacute; au <?php echo implode('/',array_reverse(explode('-',$row_l_version['date_version'])))." (".$row_l_version['numero_version'].")" ; ?></strong></div>

<h3><?php if(isset($modele_array[$modele])) echo $modele_array[$modele]; ?></h3>

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive dataTable">
<thead>
<tr role="row" <?php echo (!isset($_GET["down"]))?'':'bgcolor="#DCDCDC"'; ?>>
<th rowspan="2"><center>N&deg;<br/>
</center></th>
<th rowspan="2">Intitul&eacute; du march&eacute; </th>
<th width="80" rowspan="2">Mod&egrave;le</th>

<th width="80" rowspan="2"><center>
  M&eacute;thode </center></th>
<th rowspan="2"><center>
  N&deg;AO
</center></th>
<th colspan="2"><div align="center">Co&ucirc;t (XOF) </div></th>

<th>&nbsp;</th>
<th colspan="2"><div align="center">D&eacute;lai pr&eacute;vu </div></th>
<th align="center">&nbsp;</th>

<th colspan="3" align="center"><div align="center">D&eacute;lai ex&eacute;cut&eacute; </div></th>
</tr>
<tr role="row" <?php echo (!isset($_GET["down"]))?'':'bgcolor="#DCDCDC"'; ?>>
  <th><div align="center">pr&eacute;vu</div></th>
  <th><div align="center">D&eacute;caiss&eacute;</div></th>
  <th>&nbsp;</th>
  <th>Date de d&eacute;marrage</th>
  <th>Dur&eacute;e</th>
  <th align="center">&nbsp;</th>

  <th align="center">Etape actuelle </th>
  <th align="center">Date</th>
  <th>Dur&eacute;e totale </th>
 
</tr>
</thead>

<tbody class="">

<?php if($totalRows_liste_marche>0) { $p1="j"; foreach($row_liste_marche as $row_liste_marche){ $id = $row_liste_marche['code_marche']; $Nombres_jours=0;  number_format($Nombres_jours, 0, ',', ' '); 

if(in_array($version, explode(",", $row_liste_marche['periode']))){
 $date_start = $row_liste_marche['date_prevue'];
?>

 <?php if($p1!=$row_liste_marche['composante']) {?>
          <tr>
            <td colspan="14" align="center"><div align="left" class="Style4" style="background-color:#D2E2B1"><strong>

                      <?php if(isset($codef_array[$row_liste_marche['composante']])) echo $codef_array[$row_liste_marche['composante']]; else echo $row_liste_marche['composante'];
                      $p1=$row_liste_marche['composante']; ?>
                        </strong></div></td>
            </tr>
          <?php } ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo "<strong>".$row_liste_marche['code_marche']."</strong> "; ?></td>

<td class=" "><?php echo $row_liste_marche['intitule']; ?></td>

<td class=" "><strong>
  <?php if(isset($modele_array[$row_liste_marche["modele_marche"]])) echo $modele_array[$row_liste_marche["modele_marche"]]; else echo "N/A";
                       ?>
</strong></td>
<td class=" "><?php echo $row_liste_marche['methode']; ?></td>

<td class=" " >&nbsp;</td>
<td nowrap="nowrap" class=" " ><?php  echo number_format($row_liste_marche["montant_usd"], 0, ',', ' ');  ?></td>

<td nowrap="nowrap" class=" " ><?php  if(isset($liste_montant_marche_array[$row_liste_marche["id_marche"]])) echo number_format( $liste_montant_marche_array[$row_liste_marche["id_marche"]], 0, ',', ' ');  ?></td>
<td class=" " >&nbsp;</td>
<td class=" " ><span class=" " >
  <?php  echo implode('/',array_reverse(explode('-',$row_liste_marche['date_prevue'])));  ?>
</span></td>
<td class=" " ><strong>
  <?php if(isset($TableauEtape[$row_liste_marche["modele_marche"]])) echo $TableauEtape[$row_liste_marche["modele_marche"]]; else echo "N/A";
                       ?>
</strong></td>

		   
<td valign="middle" align="center">&nbsp;</td>

<td  valign="middle" align="center"><strong>
  <?php if(isset($TableauLibelleEtape[$row_liste_marche["id_marche"]])) echo $TableauLibelleEtape[$row_liste_marche["id_marche"]]; elseif(date("Y-m-d")>$row_liste_marche['date_prevue']) echo "<span style=\"background-color:#FF3300; ; color:#FFFFFF\">&nbsp;<b>Non d&eacute;marr&eacute;&nbsp;</b></span>"; else echo "Non &eacute;chue";?>
</strong></td>

<td   valign="middle" align="center"><strong><?php if(isset($TableauDerniereDate[$row_liste_marche["id_marche"]])) echo implode('/',array_reverse(explode('-',$TableauDerniereDate[$row_liste_marche["id_marche"]]))); elseif(date("Y-m-d")>$row_liste_marche['date_prevue']) echo "<span style=\"background-color:#FF3300; ; color:#FFFFFF\">&nbsp;<b>&eacute;chue&nbsp;</b></span>"; else echo "Non &eacute;chue";?>
</strong></td>
<td class=" "><strong>
  <?php
  if(isset($TableauIdmEtape[$row_liste_marche["id_marche"]]) && isset($TableauMinEtapeModele[$row_liste_marche["modele_marche"]]) && isset($TableauDateMin[$row_liste_marche["id_marche"]]) && $TableauMinEtapeModele[$row_liste_marche["modele_marche"]]==$TableauIdmEtape[$row_liste_marche["id_marche"]]) $date_dem=$TableauDateMin[$row_liste_marche["id_marche"]];
      else $date_dem=$row_liste_marche['date_prevue'];
   
   
   if(isset($TableauIdEtape[$row_liste_marche["id_marche"]]) && isset($TableauMaxEtapeModele[$row_liste_marche["modele_marche"]]) && isset($TableauDerniereDate[$row_liste_marche["id_marche"]]) && $TableauMaxEtapeModele[$row_liste_marche["modele_marche"]]==$TableauIdEtape[$row_liste_marche["id_marche"]]) $date_finm=$TableauDerniereDate[$row_liste_marche["id_marche"]];
      else $date_finm=date("Y-m-d");
	  
   if(intval(NbJours($date_dem, $date_finm)-1)<0) echo "<span style=\"background-color:#D2E2B1;\">&nbsp;&nbsp;<b>".abs(intval(NbJours($date_dem, $date_finm)-1))."</b>&nbsp;</span>"; else echo intval(NbJours($date_dem, $date_finm)-1);
                    ?>
</strong></td>

</tr>
<?php } } } else { ?>

<tr>

<td colspan="14"><h2 align="center">Aucune donn&eacute;e !</h2></td>
</tr>

<?php } ?>
</tbody></table>
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