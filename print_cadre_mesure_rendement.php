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
header("content-disposition: attachment;filename=Cadre_mesure_rendement.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Cadre_mesure_rendement.doc"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="p"){

require_once('./tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$PDF_HEADER_TITLE = "Mission de Récommandation";
$PDF_HEADER_STRING = "Mission de Récommandation";

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Ruche');
$pdf->SetTitle($PDF_HEADER_TITLE);
$pdf->SetSubject($PDF_HEADER_STRING);
$pdf->SetKeywords('PDF, mission, Mission de Récommandation, Récommandation');

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
  include("./print_recommandation_mission_pdf.php");
  $content = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering

$html = utf8_encode($content);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('Mission_Recommandation.pdf', 'D');
exit;

 } ?>
<?php


$editFormAction = $_SERVER['PHP_SELF'];
/*if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}*/
// query og

$query_og = "SELECT * FROM objectif_global WHERE projet='".$_SESSION["clp_projet"]."'";
try{
    $og = $pdar_connexion->prepare($query_og);
    $og->execute();
    $row_og = $og ->fetchAll();
    $totalRows_og = $og->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }



// query indicateur

$query_ind = "SELECT * FROM indicateur_objectif_global, indicateur_objectif_global_cmr WHERE indicateur_og=id_indicateur_objectif_global and projet='".$_SESSION["clp_projet"]."' order by id_indicateur_objectif_global";
try{
    $ind = $pdar_connexion->prepare($query_ind);
    $ind->execute();
    $row_ind = $ind ->fetchAll();
    $totalRows_ind = $ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }



// query source de verification

$query_src = "SELECT * FROM source_og WHERE projet='".$_SESSION["clp_projet"]."' order by id_source";
try{
    $src = $pdar_connexion->prepare($query_src);
    $src->execute();
    $row_src = $src ->fetchAll();
    $totalRows_src = $src->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }



// query hypothese

$query_hyp = "SELECT * FROM hypothese_og WHERE projet='".$_SESSION["clp_projet"]."' order by id_hypothese";
try{
    $hyp = $pdar_connexion->prepare($query_hyp);
    $hyp->execute();
    $row_hyp = $hyp ->fetchAll();
    $totalRows_hyp = $hyp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

// Partie objectif specifique

// objectif specifique

$query_os = "SELECT * FROM objectif_specifique WHERE projet='".$_SESSION["clp_projet"]."' order by id_objectif_specifique";
try{
    $os = $pdar_connexion->prepare($query_os);
    $os->execute();
    $row_os = $os ->fetchAll();
    $totalRows_os = $os->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }



// Partie resultat

//composante

// requete composante

$query_cp = "SELECT * FROM activite_projet WHERE projet='".$_SESSION["clp_projet"]."' and niveau=1 order by code";
try{
    $cp = $pdar_connexion->prepare($query_cp);
    $cp->execute();
    $row_cp = $cp ->fetchAll();
    $totalRows_cp = $cp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//produit
$query_cible_indicateur = "SELECT indicateur_produit, annee, sum(valeur_cible) as valeur_cible, avg(valeur_cible) as valeur_ciblem  FROM   ".$database_connect_prefix."cible_cmr_produit group by annee, indicateur_produit";
try{
    $cible_indicateur = $pdar_connexion->prepare($query_cible_indicateur);
    $cible_indicateur->execute();
    $row_cible_indicateur = $cible_indicateur ->fetchAll();
    $totalRows_cible_indicateur = $cible_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$ciblep_array = array();
$ciblemp_array = array();
if($totalRows_cible_indicateur>0){ 
foreach($row_cible_indicateur as $row_cible_indicateur){
  $ciblep_array[$row_cible_indicateur["indicateur_produit"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_cible"];
  $ciblemp_array[$row_cible_indicateur["indicateur_produit"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_ciblem"];
  } }
  //effet
$query_cible_indicateur = "SELECT indicateur_resultat, annee, sum(valeur_cible) as valeur_cible, avg(valeur_cible) as valeur_ciblem  FROM   ".$database_connect_prefix."cible_cmr_resultat group by annee, indicateur_resultat";
try{
    $cible_indicateur = $pdar_connexion->prepare($query_cible_indicateur);
    $cible_indicateur->execute();
    $row_cible_indicateur = $cible_indicateur ->fetchAll();
    $totalRows_cible_indicateur = $cible_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$cibleef_array = array();
$ciblemef_array = array();
if($totalRows_cible_indicateur>0){ 
foreach($row_cible_indicateur as $row_cible_indicateur){
  $cibleef_array[$row_cible_indicateur["indicateur_resultat"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_cible"];
  $ciblemef_array[$row_cible_indicateur["indicateur_resultat"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_ciblem"];
   } }
   
 //odp
$query_cible_indicateur = "SELECT indicateur_os, annee, sum(valeur_cible) as valeur_cible, avg(valeur_cible) as valeur_ciblem  FROM   ".$database_connect_prefix."cible_cmr_os group by annee, indicateur_os";
try{
    $cible_indicateur = $pdar_connexion->prepare($query_cible_indicateur);
    $cible_indicateur->execute();
    $row_cible_indicateur = $cible_indicateur ->fetchAll();
    $totalRows_cible_indicateur = $cible_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$cibleodp_array = array();
$ciblemodp_array = array();
if($totalRows_cible_indicateur>0){ 
foreach($row_cible_indicateur as $row_cible_indicateur){
  $cibleodp_array[$row_cible_indicateur["indicateur_os"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_cible"];
  $ciblemodp_array[$row_cible_indicateur["indicateur_os"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_ciblem"];
   } }
  
 //but ou objetif global 
$query_cible_indicateur = "SELECT indicateur_og, annee, sum(valeur_cible) as valeur_cible, avg(valeur_cible) as valeur_ciblem  FROM   ".$database_connect_prefix."cible_cmr_og group by annee, indicateur_og";
try{
    $cible_indicateur = $pdar_connexion->prepare($query_cible_indicateur);
    $cible_indicateur->execute();
    $row_cible_indicateur = $cible_indicateur ->fetchAll();
    $totalRows_cible_indicateur = $cible_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$cibleog_array = array();
$ciblemog_array = array();
if($totalRows_cible_indicateur>0){ 
foreach($row_cible_indicateur as $row_cible_indicateur){
  $cibleog_array[$row_cible_indicateur["indicateur_og"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_cible"];
  $ciblemog_array[$row_cible_indicateur["indicateur_og"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_ciblem"];
   } }
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

.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
</style>
<div class="contenu">
  <div id="msg" align="center" class="red"></div>

<?php if(!isset($_GET["down"])){  ?>
<div class="well well-sm r_float"><div class="r_float"><a href="./s_suivi_resultat.php" class="button">Retour</a></div>
<!--<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."?down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>-->
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."?down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a target="_blank" title="Imprimer" href="<?php echo $editFormAction."?down=1"; ?>" class="button"><img src="./images/print.png" width='20' height='20' alt='Modifier' /></a></div></div>
<div class="clear h0">&nbsp;</div>
<?php } else { ?>

<center><?php //include "./includes/print_header.php"; ?></center>

<?php } ?>



      
      <div align="left" class="well well-sm">
        <div align="center"><strong>Cadre de mesure de rendement du
      <?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b>
                    <?php } else { ?> Veuillez s&eacute;lectionner un projet<?php } ?>
                    au <?php echo date("d-m-Y"); ?></strong> </div></div>
       
        <table width="100%"  border="0" cellspacing="0" >
          <tr>
            <th scope="col"><div align="left"> </div></th>
          </tr>
          <tr>
            <td><table width="100%" border="0" align="center" cellspacing="1" class="table table-striped table-bordered table-responsive">
                
                <tr>
                  <td valign="top" bgcolor="#FFFFFF" colspan="4"><strong> 1. <span class="Style22">OBJECTIF GLOBAL</span> (But) </strong></td>
                </tr>
                <tr bgcolor="#D9D9D9">
                  <td valign="middle" width="10%"><strong> R&eacute;sum&eacute; descriptif </strong></td>
                  <td  valign="middle" colspan="<?php echo 1+$_SESSION["annee_fin_projet"]-$_SESSION["annee_debut_projet"]; ?>" ><strong> Indicateurs objectivement v&eacute;rifiables</strong> </td>
                  <td valign="middle" >&nbsp;</td>
                </tr>
               
                <tr>
                  <td valign="top" width="10%">&nbsp;</td>
                  <td valign="top"  colspan="<?php echo 1+$_SESSION["annee_fin_projet"]-$_SESSION["annee_debut_projet"]; ?>"><table  <?php if(isset($_GET["down"])) echo "border=\"1\""; ?> cellspacing="0" width="100%" class="table-bordered table-responsive">
                   
                  
                    <tr >
                      <td width="30%"><div align="left"></div></td>
                      <?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
                      <td valign="middle" align="center" width="5%"><?php echo $i; ?></td>
                      <?php } ?>
                    </tr>
                    
                  </table></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top" width="10%"><div align="left">
                      <?php if($totalRows_og>0) {$i=0; foreach($row_og as $row_og){ ?>
                      <?php echo $row_og['intitule_objectif_global']; ?>
                      <?php }  }?>
                  </div></td>
                  <td valign="top"  colspan="<?php echo 1+$_SESSION["annee_fin_projet"]-$_SESSION["annee_debut_projet"]; ?>"><table <?php if(isset($_GET["down"])) echo "border=\"1\""; ?> cellspacing="0" width="100%" class="table-bordered table-responsive">
                      <?php if($totalRows_ind>0) {$i=0; $p2=$p1="j"; foreach($row_ind as $row_ind){ ?>
					    <?php  if($p2!=$row_ind['id_indicateur_objectif_global']) {?>
            <tr bgcolor="#BED694">
              <td bgcolor="#BED694" width="30%" colspan="<?php echo 2+$_SESSION["annee_fin_projet"]-$_SESSION["annee_debut_projet"]; ?>" align="center"><div align="left" style="background-color: #BED694; "><strong>
                <?php  if($p2!=$row_ind['id_indicateur_objectif_global']) {echo $row_ind['intitule_indicateur_objectif_global'];} $p2=$row_ind['id_indicateur_objectif_global']; ?>
              </strong></div></td>
            </tr>
			   <?php } ?>
                      <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; $i=$i+1;?>>
                        <td width="30%"><div align="left"><?php echo "- ".$row_ind['intitule_indicateur_cmr_og']; ?></div></td>
                        <?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
		<td valign="middle" align="center" width="5%"> <?php if(isset($cibleog_array[$row_ind["indicateur_og"]][$i])) echo $cibleog_array[$row_ind["indicateur_og"]][$i]; else echo "&nbsp;"; ?></td>
				
				<?php } ?>
                      </tr>
                      <?php }  ?>
                      <?php }?>
                  </table></td>
                  <td valign="top">&nbsp;</td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td><div align="left">
                <table width="100%" border="0" align="left" cellspacing="1" class="table table-striped table-bordered table-responsive">
                  <tr>
                    <td valign="top" bgcolor="#FFFFFF" colspan="4"><strong> 2. <span class="Style22">OBJECTIF DE DEVELOPPEMENT</span></strong></td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" bgcolor="" width="10%"><strong>R&eacute;sum&eacute; descriptif</strong></td>
                    <td bgcolor="" ><strong>Indicateurs objectivement v&eacute;rifiables</strong> </td>
                    <td bgcolor="" >&nbsp;</td>
                  </tr>
                  <tr>
                    <td nowrap="nowrap" bgcolor="" width="10%">&nbsp;</td>
                    <td bgcolor=""><table <?php if(isset($_GET["down"])) echo "border=\"1\""; ?> cellspacing="0" width="100%" class="table-bordered table-responsive">
                      <tr >
                        <td width="30%"><div align="left"></div></td>
                        <?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
                        <td valign="middle" width="5%" align="center"><?php echo $i; ?></td>
                        <?php } ?>
                      </tr>
                    </table></td>
                    <td bgcolor="">&nbsp;</td>
                  </tr>
                  <?php if($totalRows_os>0) {$o=0; foreach($row_os as $row_os){ ?>
                  <tr <?php if($o%2==0) echo 'bgcolor="#ECF0DF"'; $o=$o+1;?>>
                    <td valign="top" width="10%"><div align="left"><?php echo $row_os['intitule_objectif_specifique']; ?> </div></td>
                    <td valign="top"><table <?php if(isset($_GET["down"])) echo "border=\"1\""; ?> align="left" cellspacing="0" width="100%" class="table-bordered table-responsive">
                        <?php

				    $id_os=$row_os['id_objectif_specifique'];
					$query_ind = "SELECT * FROM indicateur_objectif_specifique, indicateur_objectif_specifique_cmr where indicateur_os=id_indicateur_objectif_specifique 
					and objectif_specifique='$id_os'";
					try{
                     $ind = $pdar_connexion->prepare($query_ind);
                     $ind->execute();
                     $row_ind = $ind ->fetchAll();
                     $totalRows_ind = $ind->rowCount();
                     } catch(Exception $e){ die(mysql_error_show_message($e)); }

				  ?>
                        <?php if($totalRows_ind>0) {$i=0;$p2=$p1="j"; foreach($row_ind as $row_ind){ ?>
					    <?php  if($p2!=$row_ind['id_indicateur_objectif_specifique']) {?>
            <tr bgcolor="#BED694">
              <td bgcolor="#BED694" width="30%" colspan="<?php echo 2+$_SESSION["annee_fin_projet"]-$_SESSION["annee_debut_projet"]; ?>" align="center"><div align="left" style="background-color: #BED694; "><strong>
                <?php  if($p2!=$row_ind['id_indicateur_objectif_specifique']) {echo $row_ind['intitule_indicateur_objectif_specifique'];} $p2=$row_ind['id_indicateur_objectif_specifique']; ?>
              </strong></div></td>
             
            </tr>
			   <?php } ?>
                        <tr <?php if($i%2==0) echo 'bgcolor="#FFFFFF"'; $i=$i+1;?>>
                          <td width="30%"><div align="left"><?php echo "- ".$row_ind['intitule_indicateur_cmr_os']; ?>
                                <?php if(isset($row_ind['niveau_sygri']) && $row_ind['niveau_sygri']==1) {?>
                                  <span class="Style5">*</span>
                                  <?php }?>
                          </div></td>
                         <?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
<td valign="middle" width="5%" align="center"> <?php if(isset($cibleodp_array[$row_ind["indicateur_os"]][$i])) echo $cibleodp_array[$row_ind["indicateur_os"]][$i]; else echo "&nbsp;"; ?></td>
				
				<?php } ?>
                        </tr>
                        <?php }  ?>
                        <?php } ?>
                    </table></td>
                    <td valign="top">&nbsp;</td>
                  </tr>
                  <?php }  ?>
                  <?php } else {?>
                  <tr>
                    <td colspan="4" nowrap="nowrap"><div align="center"><em><strong>Aucun objectif sp&eacute;cifique enregistr&eacute; </strong></em></div></td>
                  </tr>
                  <?php } ?>
                </table>
            </div></td>
          </tr>
          <tr>
            <td><table width="100%" <?php if(isset($_GET["down"])) echo "border=\"1\""; ?> align="left" cellspacing="1" class="table table-striped table-bordered table-responsive">
                <tr>
                  <td valign="top" bgcolor="#FFFFFF" colspan="4"><strong> 3. <span class="Style22">R&eacute;sultats / Produits par effet et par composante</span></strong></td>
                </tr>
                <?php if($totalRows_cp>0) {$c=0;foreach($row_cp as $row_cp){ ?>
                <tr bgcolor="#009900">
                  <td colspan="4" style="color: white; background-color: #009900" bgcolor="#009900" valign="top" align="left"><?php echo $row_cp['code'].": ".$row_cp['intitule']; ?>&nbsp;</td>
                </tr>
                <tr>
                  <td nowrap="nowrap" bgcolor="" width="10%"><div align="left"><strong>Effets</strong></div></td>
                  <td bgcolor=""><strong>Indicateurs objectivement v&eacute;rifiables</strong> </td>
                  <td bgcolor="" >&nbsp;</td>
                </tr>
                <tr>
                  <td nowrap="nowrap" bgcolor="">&nbsp;</td>
                  <td ><div align="left">
                    <table <?php if(isset($_GET["down"])) echo "border=\"1\""; ?> cellspacing="0" width="100%" class="table-bordered table-responsive">
                      <tr >
                        <td width="30%"><div align="left"></div></td>
                        <?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
                        <td valign="middle" width="5%" align="center"><?php echo $i; ?></td>
                        <?php } ?>
                      </tr>
                    </table>
                  </div></td>
                  <td bgcolor="">&nbsp;</td>
                </tr>
                <?php

		  //debut de ligne

					$id_cp=$row_cp['code'];

					$query_res = "SELECT * FROM resultat where composante='$id_cp' and projet='".$_SESSION["clp_projet"]."' order by code_resultat";
										try{
                     $res = $pdar_connexion->prepare($query_res);
                     $res->execute();
                     $row_res = $res ->fetchAll();
                     $totalRows_res = $res->rowCount();
                     } catch(Exception $e){ die(mysql_error_show_message($e)); }

				?>
                <?php if($totalRows_res>0) {$o=0;foreach($row_res as $row_res){  ?>
                <tr <?php if($o%2==0) echo 'bgcolor="#ECF0DF"'; $o=$o+1;?>>
                  <td valign="top"><div align="left"><span class="Style11"><?php echo "<b>Effet ".$row_res['code_resultat']."</b>: ".$row_res['intitule_resultat']; ?></span> </div></td>
                  <td valign="top"><table <?php if(isset($_GET["down"])) echo "border=\"1\""; ?> align="left" cellspacing="0" width="100%" class="table-bordered table-responsive">
                      <?php

				    $id_res=$row_res['id_resultat'];
					$query_ind = "SELECT * FROM indicateur_resultat, indicateur_resultat_cmr where indicateur_res=id_indicateur_resultat and resultat='$id_res'";
					try{
                     $ind = $pdar_connexion->prepare($query_ind);
                     $ind->execute();
                     $row_ind = $ind ->fetchAll();
                     $totalRows_ind = $ind->rowCount();
                     } catch(Exception $e){ die(mysql_error_show_message($e)); }

				  ?>
                      <?php if($totalRows_ind>0) {$b=0;$p2=$p1="j"; foreach($row_ind as $row_ind){ ?>
					    <?php  if($p2!=$row_ind['id_indicateur_resultat']) {?>
            <tr bgcolor="#BED694">
              <td bgcolor="#BED694" width="30%" colspan="<?php echo 2+$_SESSION["annee_fin_projet"]-$_SESSION["annee_debut_projet"]; ?>" align="center"><div align="left" style="background-color: #BED694; "><strong>
                <?php  if($p2!=$row_ind['id_indicateur_resultat']) {echo $row_ind['intitule_indicateur_resultat'];} $p2=$row_ind['id_indicateur_resultat']; ?>
              </strong></div></td>
              
            </tr>
			   <?php } ?>
                      <tr <?php if($b%2==0) echo 'bgcolor="#FFFFFF"'; $b=$b+1;?>>
                        <td  width="30%"><div align="left" class="Style11"> <?php echo "- ".$row_ind['intitule_indicateur_cmr_res']; ?>
                              <?php if(isset($row_ind['niveau_sygri']) && $row_ind['niveau_sygri']==1) {?>
                                <span class="Style5">*</span>
                                <?php }?>
                        </div></td>
                         <?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
				<td valign="middle" width="5%" align="center"><?php if(isset($cibleef_array[$row_ind["id_indicateur"]][$i])) echo $cibleef_array[$row_ind["id_indicateur"]][$i]; else echo "&nbsp;"; ?></td>
				
				<?php } ?>
                      </tr>
                      <?php }  ?>
                      <?php } ?>
                    
                  </table></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <?php

		  //produit

					$query_produit = "SELECT * FROM produit where effet='$id_res' order by code_produit";
					try{
                     $produit = $pdar_connexion->prepare($query_produit);
                     $produit->execute();
                     $row_produit = $produit ->fetchAll();
                     $totalRows_produit = $produit->rowCount();
                     } catch(Exception $e){ die(mysql_error_show_message($e)); }

				?>
                <?php if($totalRows_produit>0) {$op=0;foreach($row_produit as $row_produit){ ?>
                <tr <?php if($op%2==0) echo 'bgcolor="#ECF0DF"'; $op=$op+1;?>>
                  <td valign="top"><div align="left"><span class="Style11"><?php echo "<b>Produit ".$row_produit['code_produit']."</b>: ".$row_produit['intitule_produit']; ?></span><br />
                  </div></td>
                  <td valign="top"><table <?php if(isset($_GET["down"])) echo "border=\"1\""; ?> align="left" cellspacing="0" width="100%" class="table-bordered table-responsive">
                      <?php

				   $id_prd=$row_produit['id_produit'];

					$query_indp = "SELECT * FROM indicateur_produit, indicateur_produit_cmr where indicateur_prd=id_indicateur_produit and produit='$id_prd'";
					try{
                     $indp = $pdar_connexion->prepare($query_indp);
                     $indp->execute();
                     $row_indp = $indp ->fetchAll();
                     $totalRows_indp = $indp->rowCount();
                     }catch(Exception $e){ die(mysql_error_show_message($e)); }

				  ?>
                      <?php if($totalRows_indp>0) {$b=0; $p2=$p1="j"; foreach($row_indp as $row_indp){ ?>
					    <?php  if($p2!=$row_indp['id_indicateur_produit']) {?>
            <tr bgcolor="#BED694">
              <td bgcolor="#BED694" width="30%" colspan="<?php echo 2+$_SESSION["annee_fin_projet"]-$_SESSION["annee_debut_projet"]; ?>" align="center"><div align="left" style="background-color: #BED694; "><strong>
                <?php  if($p2!=$row_indp['id_indicateur_produit']) {echo $row_indp['intitule_indicateur_produit'];} $p2=$row_indp['id_indicateur_produit']; ?>
              </strong></div></td>
             
            </tr>
			   <?php } ?>
                      <tr <?php if($b%2==0) echo 'bgcolor="#FFFFFF"'; $b=$b+1;?>>
                        <td  width="30%"><div align="left" class="Style11"> <?php echo "- ".$row_indp['intitule_indicateur']; ?>
                              <?php if(isset($row_ind['niveau_sygri']) && $row_ind['niveau_sygri']==1) {?>
                                <span class="Style5">*</span>
                                <?php }?>
                        </div></td>
                         <?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
				<td valign="middle" width="5%" align="center"> <?php if(isset($ciblep_array[$row_indp["id_indicateur"]][$i])) echo $ciblep_array[$row_indp["id_indicateur"]][$i]; else echo "&nbsp;"; ?></td>
				
				<?php } ?>
                      </tr>
                      <?php }  ?>
                      <?php } ?>
                    
                  </table></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <?php }  ?>
                <?php } ?>
                <tr>
                  <td colspan="4"><div align="center" class="Style2">
                      <?php if(!$totalRows_produit>0) echo "Aucun produit enregistr&eacute;: ";  ?>
                  </div></td>
                </tr>
                <?php //fin produit?>
                <?php }  ?>
                <?php } ?>
                <tr>
                  <td colspan="4"><div align="center" class="Style2">
                      <?php if(!$totalRows_res>0) echo "Aucun effet enregistr&eacute;: "; ?>
                  </div></td>
                </tr>
                <?php }  ?>
                <?php } else {?>
                <tr>
                  <td colspan="4" nowrap="nowrap"><div align="center"><em><strong>Aucune composante enregistr&eacute;e; </strong></em></div></td>
                </tr>
                <?php } ?>
            </table></td>
          </tr>
        </table>
      
     
     

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