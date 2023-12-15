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
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Recommandation_$sigle.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Recommandation_$sigle.doc"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="p"){

require_once('./tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$PDF_HEADER_TITLE = "Graphique des Recommandations de missions";
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
  include("./graph_recommandation_mission_pdf.php");
  $content = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering

$html = utf8_encode($content);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('graph_recommandation_mission_pdf.pdf', 'D');
/*
include("pdf/mpdf.php");
$mpdf=new mPDF('win-1252','A4-L','','',15,10,16,10,10,10);//A4 page in portrait for landscape add -L.
$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetDisplayMode('fullpage');
ob_start();
include "graph_recommandation_mission_pdf.php";
$html = ob_get_contents();
ob_end_clean();
$mpdf->WriteHTML($html);
$mpdf->Output();
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=graph_recommandation_mission_pdf.pdf"); */
exit;

 }

$cd=(isset($_GET["id"]) && !empty($_GET["id"]) && intval($_GET["id"])>0)?intval($_GET["id"]):1;
if(isset($_GET['annee']) && $_GET['annee']>0) {$annee=$_GET['annee'];} else {$annee=date("Y");}



$tab_encours = array();

$tab_execute = array();

$tab_non_execute = array();

$tab_non_entame = array();

//$id=0;



$encours = 0;

$execute = 0;

$non_execute = 0;

$non_entame = 0;



$currentPage = $_SERVER["PHP_SELF"];

// session_start();


//fonction calcul nb jour

function NbJours($debut, $fin) {



  $tDeb = explode("-", $debut);

  $tFin = explode("-", $fin);



  $diff = (mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]));



  return(($diff / 86400)+1);



}



$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {

  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);

}

$id_m=(isset($_GET['id']))?$_GET['id']:0;  //projet='".$_SESSION["clp_projet"]."' and
$query_edit_ms = "SELECT * FROM ".$database_connect_prefix."mission_supervision where  id_mission='$id_m'";
  try{
    $edit_ms = $pdar_connexion->prepare($query_edit_ms);
    $edit_ms->execute();
    $row_edit_ms = $edit_ms ->fetch();
    $totalRows_edit_ms = $edit_ms->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rubrique = "select ".$database_connect_prefix."suivi_recommandation_mission.* from ".$database_connect_prefix."suivi_recommandation_mission, ".$database_connect_prefix."recommandation_mission where numero=".$database_connect_prefix."suivi_recommandation_mission.recommandation and mission='$id_m' and projet='".$_SESSION["clp_projet"]."' and structure='".$_SESSION["clp_structure"]."' order by recommandation asc";
//$query_liste_rubrique = "select * from ".$database_connect_prefix."suivi_recommandation_mission order by recommandation asc";
$liste_rubrique = mysql_query($query_liste_rubrique, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_rubrique = mysql_fetch_assoc($liste_rubrique);
$totalRows_liste_rubrique = mysql_num_rows($liste_rubrique);
$tableau_stat = array();
if($totalRows_liste_rubrique>0){  do{
  $tableau_stat[$row_liste_rubrique["recommandation"]]=$row_liste_rubrique["statut"]; }while($row_liste_rubrique = mysql_fetch_assoc($liste_rubrique));
}*/

if(isset($row_edit_ms['id_mission'])) $id=$row_edit_ms['id_mission']; else $id=0;
$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."recommandation_mission, ".$database_connect_prefix."rubrique_projet where mission='$id' and rubrique=code_rub ORDER BY code_rub asc, numero asc";
		  try{
    $liste_rec = $pdar_connexion->prepare($query_liste_rec);
    $liste_rec->execute();
    $row_liste_rec = $liste_rec ->fetchAll();
    $totalRows_liste_rec = $liste_rec->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$non_execute = $non_entame = $encours = $execute = 0;

$t=0; if($totalRows_liste_rec>0) {  foreach($row_liste_rec as $row_liste_rec){   $code_ms=$row_liste_rec["mission"];

 // $query_suivi_plan_ms = "SELECT sum(proportion) as texrecms, code_rec  FROM ".$database_connect_prefix."mission_plan where code_ms='$code_ms' and valider=1 group by code_rec order by code_rec";
    $query_suivi_plan_ms = "SELECT sum(proportion) as texrecms, code_rec  FROM ".$database_connect_prefix."mission_plan, ".$database_connect_prefix."recommandation_mission  where code_rec=id_recommandation and mission='$id' and valider=1 group by code_rec";
 		  try{
    $suivi_plan_ms = $pdar_connexion->prepare($query_suivi_plan_ms);
    $suivi_plan_ms->execute();
    $row_suivi_plan_ms = $suivi_plan_ms ->fetchAll();
    $totalRows_suivi_plan_ms = $suivi_plan_ms->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $prop_tab = array();
  if($totalRows_suivi_plan_ms>0){  foreach($row_suivi_plan_ms as $row_suivi_plan_ms){ 
    $prop_tab[$row_suivi_plan_ms["code_rec"]]=$row_suivi_plan_ms["texrecms"];
     }  }

////statut gestion
$cd=$row_liste_rec["id_recommandation"];



//$color = "red"; $stat="Non ex&eacute;cut&eacute;";
//if(isset($prop_tab[$cd])) $stat=$prop_tab[$cd];


if(isset($prop_tab[$cd])){ if($prop_tab[$cd]<100) $encours++; else $execute++; } elseif(date("Y-m-d")>$row_liste_rec['date_buttoir'] && $row_liste_rec['type']!="Continu") $non_execute++; else $non_entame++;


/*if($stat=="Non ex&eacute;cut&eacute;") $non_execute++;
elseif($stat=="Mise en oeuvre") $execute++;
elseif($stat=="Partiellement mise en oeuvre") $encours++;
elseif($stat=="Délai d'exécution non échu") $non_entame++; */

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
.titrecorps2 { background-color: #999999;color: #FFFFFF;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
table.table tfoot {
    background: #EBEBEB; font-weight: bold;
}
hr {
    margin-top: 5px;
    margin-bottom: 5px;
}
</style>
<?php if(isset($totalRows_liste_rec) && $totalRows_liste_rec>0){  ?>
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function () {

    	// Build the chart
        $('#container1').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Situation de la mission'
            },
            tooltip: {
        	    pointFormat: '<br /><b>{point.percentage:.0f}% des recommandations</b><br /></b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '{point.name}: <b>{point.percentage:.0f} %</b>'
                },
                    showInLegend: true
                }
            },
            credits: {
                enabled: true,
                href: 'http:#',
                text: 'RUCHE : <?php echo date("d/m/Y H:i"); ?>',
                style: {
                cursor: 'pointer',
                color: '#6633FF',
                fontSize: '10px',
                margin: '10px'
                }
             },
            series: [{
                type: 'pie',
                name: '% Statut',
                data: [ ['EN COURS',  <?php echo $encours; ?>],['<?php echo utf8_decode("REALISE"); ?>',  <?php echo $execute;  ?>],['<?php echo utf8_decode("NON EXECUTE"); ?>',  <?php echo $non_execute;  ?>],['<?php echo utf8_decode("NON ENTAME"); ?>',  <?php echo $non_entame;  ?>] ]
            }]
        });
    });
});
		</script>
<?php } ?>
<?php if(isset($id)) {?>
<div class="contenu">
  <div id="msg" align="center" class="red"></div>
<?php if(!isset($_GET["down"])){  ?>
<div class="well well-sm r_float"><div class="r_float"><a href="./s_supervision.php" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format PDF" href="<?php echo $editFormAction."&down=1&t=p"; ?>" class="button"><img src="./images/pdf.png" width='20' height='20' alt='Modifier' /></a></div></div>
<div class="clear h0">&nbsp;</div>
<?php } else { ?>

<center><?php include "./includes/print_header.php"; ?></center>

<?php } ?>

<div class="col-md-6">
<div class="widget box">
<div class="widget-header" title=""> <h4><i class="icon-reorder"></i> <?php  echo $row_edit_ms['type']." du ".implode('-',array_reverse(explode('-',$row_edit_ms['debut'])))." au ".implode('-',array_reverse(explode('-',$row_edit_ms['fin']))); ?>&nbsp;&nbsp;&nbsp;Objet&nbsp;&nbsp;:<?php if(isset($row_edit_ms['observation'])) echo substr($row_edit_ms['observation'],0, 170)." ..."; ?></h4></div>
<div class="widget-content">
<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive">
    <thead>
    <tr>
      <th>Statut</th>
      <th>Nombre</th>
      <th>Taux</th>
    </tr>
    </thead>
    <tr>
      <td><div align="center">En cours</div></td>
      <td><div align="center"><?php echo $encours;  ?></div></td>
      <td><div align="center"><?php echo (isset($totalRows_liste_rec) && $totalRows_liste_rec>0)?number_format(($encours/$totalRows_liste_rec)*100, 0, ',', ' ')."%":"0%";  ?></div></td>
    </tr>
    <tr>
      <td><div align="center">R&eacute;alis&eacute;</div></td>
      <td><div align="center"><span class="Style2"><?php echo $execute;  ?></span></div></td>
      <td><div align="center"><span class="Style2"><?php echo (isset($totalRows_liste_rec) && $totalRows_liste_rec>0)?number_format(($execute/$totalRows_liste_rec)*100, 0, ',', ' ')."%":"0%";  ?></span></div></td>
    </tr>
    <tr>
      <td><div align="center"><span class="Style2">D&eacute;lai d'ex&eacute;cution non &eacute;chu</span></div></td>
      <td><div align="center"><span class="Style2"><?php echo $non_execute;  ?></span></div></td>
      <td><div align="center"><span class="Style2"><?php echo (isset($totalRows_liste_rec) && $totalRows_liste_rec>0)?number_format(($non_execute/$totalRows_liste_rec)*100, 0, ',', ' ')."%":"0%";  ?></span></div></td>
    </tr>
    <tr>
      <td><div align="center"><span class="Style2">Non ex&eacute;cut&eacute;</span></div></td>
      <td><div align="center"><span class="Style2"><?php echo $non_entame;  ?></span></div></td>
      <td><div align="center"><span class="Style2"><?php echo (isset($totalRows_liste_rec) && $totalRows_liste_rec>0)?number_format(($non_entame/$totalRows_liste_rec)*100, 0, ',', ' ')."%":"0%";  ?></span></div></td>
    </tr>
    <tfoot>
    <tr>
      <td><div align="center"><span class="Style2">Total</span></div></td>
      <td><div align="center"><span class="Style2"><?php echo (isset($totalRows_liste_rec) && $totalRows_liste_rec>0)?$totalRows_liste_rec:0;  ?></span></div></td>
      <td><div align="center"><span class="Style2"><?php echo (isset($totalRows_liste_rec) && $totalRows_liste_rec>0)?number_format(100, 0, ',', ' ')."%":"0%";  ?></span></div></td>
    </tr>
    </tfoot>
  </table>

</div></div>
</div>

<div class="col-md-6">
<div class="widget box">
<div class="widget-header" title=""> <h4><i class="icon-reorder"></i> Graphique de <?php  echo $row_edit_ms['type']." du ".implode('-',array_reverse(explode('-',$row_edit_ms['debut'])))." au ".implode('-',array_reverse(explode('-',$row_edit_ms['fin']))); ?> </h4></div>
<div class="widget-content">
<script src="assets/js/highcharts.js"></script>
<script src="assets/js/modules/exporting.js"></script>
<script src="assets/js/modules/offline-exporting.js"></script>
  <div id="container1" style="min-width: 310px; height: 250px; margin: 0 auto;"></div>
</div></div>
</div>
</div>
<?php }  ?>

<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>    <?php if(!isset($_GET["down"])) include_once 'modal_add.php'; ?>
    <?php if(!isset($_GET["down"])) include_once("includes/footer.php"); ?>
</div>

</body>
</html>