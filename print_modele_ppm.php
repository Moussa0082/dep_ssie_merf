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

header("Content-Type: application/vnd.ms-excel charset=ISO-8859-15'");

header("Expires: 0");

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header("content-disposition: attachment;filename=tableau_suivi_dano.xls"); }

else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){

header("Content-Type: application/vnd.ms-word charset=ISO-8859-15'");

header("Expires: 0");

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header("content-disposition: attachment;filename=tableau_suivi_dano.doc"); }

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



if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else {$annee=date("Y");}
if(isset($_GET['version'])) {$version=$_GET['version'];} else {$version=0;}
if(isset($_GET['modele'])) {$modele=$_GET['modele'];} else {$modele="0";}
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


//mysql_select_db($database_pdar_connexion, $pdar_connexion);
//$query_liste_etape_plan = "SELECT ".$database_connect_prefix."etape_marche.* FROM ".$database_connect_prefix."etape_marche, ".$database_connect_prefix."modele_marche where  FIND_IN_SET(".$database_connect_prefix."modele_marche.code, modele_concerne) and ".$database_connect_prefix."modele_marche.code='$modele'  ORDER BY ".$database_connect_prefix."etape_marche.code asc";
$query_liste_etape_plan = "SELECT * FROM ".$database_connect_prefix."etape_marche where modele_concerne=$modele ORDER BY code asc";
           try{
    $liste_etape_plan = $pdar_connexion->prepare($query_liste_etape_plan);
    $liste_etape_plan->execute();
    $row_liste_etape_plan = $liste_etape_plan ->fetchAll();
    $totalRows_liste_etape_plan = $liste_etape_plan->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


$query_liste_modele = "SELECT * FROM ".$database_connect_prefix."modele_marche where id_modele=$modele ORDER BY code asc";
           try{
    $liste_modele = $pdar_connexion->prepare($query_liste_modele);
    $liste_modele->execute();
    $row_liste_modele = $liste_modele ->fetchAll();
    $totalRows_liste_modele = $liste_modele->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$modele_array =$montant_modele_array = array();
    if($totalRows_liste_modele>0){
	foreach($row_liste_modele as $row_liste_modele){  $modele_array[$row_liste_modele["id_modele"]]=$row_liste_modele['code']." - ".$row_liste_modele['categorie']." - ".$row_liste_modele['methode_concerne']." - ".$row_liste_modele['examen'];
	 $montant_modele_array[$row_liste_modele["id_modele"]]=":  De ".$row_liste_modele['montant_min']." &agrave; ".$row_liste_modele['montant_max']; }
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

<div class="well well-sm r_float"><div class="r_float"><a href="./s_ppm.php" class="button">Retour</a></div>

<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction1."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>

<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction1."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>

<!--<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format PDF" href="<?php echo $editFormAction1."&down=1&t=p"; ?>" class="button"><img src="./images/pdf.png" width='20' height='20' alt='Modifier' /></a></div>--></div>

<div class="clear h0">&nbsp;</div>

<?php } else { ?>



<center><?php //include "./includes/print_header.php"; ?></center>



<?php } ?>

<div class="well well-sm"><strong>Liste des &eacute;tapes de mod&egrave;le de passation de march&eacute; , actualis&eacute; au <?php echo date("d/m/Y"); ?></strong></div>

<h3><?php if(isset($modele_array[$modele])) echo $modele_array[$modele]; if(isset($montant_modele_array[$modele]) && $montant_modele_array[$modele]!=":  De 0 � 0") echo $montant_modele_array[$modele] ?></h3>

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive dataTable">
<thead>
<tr role="row" <?php echo (!isset($_GET["down"]))?'':'bgcolor="#DCDCDC"'; ?>>
<th><center>N&deg;<br/>
</center></th>
<th>Intitul&eacute; de l'&eacute;tape </th>
<th width="80"><center>
  Dur&eacute;e pr&eacute;vue 
</center></th>
<th>&nbsp;</th>
</tr>
</thead>

<tbody class="">

<?php if($totalRows_liste_etape_plan>0) { $p1="j"; foreach($row_liste_etape_plan as $row_liste_etape_plan){ $Nombres_jours=0;  number_format($Nombres_jours, 0, ',', ' '); 


?>


<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><div align="center"><?php echo "<strong>".$row_liste_etape_plan['code']."</strong> "; ?></div></td>

<td class=" "><?php echo $row_liste_etape_plan['intitule']; ?></td>

<td class=" "><div align="center"><?php echo $row_liste_etape_plan['duree']; ?></div></td>

<td class=" ">&nbsp;</td>
</tr>
<?php  } } else { ?>

<tr>

<td colspan="4"><h2 align="center">Aucune donn&eacute;e !</h2></td>
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