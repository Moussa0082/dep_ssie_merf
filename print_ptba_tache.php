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

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Ruche');
$pdf->SetTitle('PTBA');
$pdf->SetSubject('PTBA');
$pdf->SetKeywords('PDF, ptba');

// set default header data //PDF_HEADER_LOGO
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

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
  include("./print_ptba_pdf.php");
  $content = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering

$html = utf8_encode($content);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('print_ptba.pdf', 'D');
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
 if(isset($_GET['annee'])) $annee=$_GET['annee']; else $annee=date("Y");
 if(isset($_GET['cp'])) $composante=$_GET['cp'];
 if(isset($_GET['region'])) $unite_gestion=$_GET['region'];
?>
<?php


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

		
					
$tgptba=0;


if(isset($_SESSION["clp_zone"])) {$ug=$_SESSION["clp_zone"];} else $ug="";
if(isset($_GET['ug'])) {$ug=$_GET['ug'];} //else $ug="";

mysql_select_db($database_pdar_connexion, $pdar_connexion);
if($_SESSION['clp_niveau']==1) $query_liste_ug = "SELECT * FROM ucp_ugl ORDER BY code_ugl asc";
else $query_liste_ug = "SELECT * FROM ucp_ugl  where id_region='$ug'";
//$query_liste_mois= "SELECT * FROM mois order by num_mois";
$liste_ug = mysql_query($query_liste_ug, $pdar_connexion) or die(mysql_error());
	$tableauUg=array();
	while($ligneug=mysql_fetch_assoc($liste_ug)){$tableauUg[]=$ligneug['id_ugl']."<>".$ligneug['nom_ugl'];}
	mysql_free_result($liste_ug);


$pcent = 100;
$tableauMois=array('01<>Jan<>J','02<>Fev<>F','03<>Mars<>M','04<>Avril<>A','05<>Mai<>M','06<>Juin<>J','07<>Juil<>J','08<>Aout<>A','09<>Sep<>S','10<>Oct<>O','11<>Nov<>N','12<>Dec<>D');

$a=number_format(10, 0, ',', ' ');
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
  <meta name="author" content="<?php print $config->MetaAuthor; ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
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
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init();$("#container").addClass("sidebar-closed");});</script>
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
<div class="well well-sm r_float"><div class="r_float"><a href="./s_supervision.php" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format PDF" href="<?php echo $editFormAction."&down=1&t=p"; ?>" class="button"><img src="./images/pdf.png" width='20' height='20' alt='Modifier' /></a></div></div>
<div class="clear h0">&nbsp;</div>
<?php } else { ?>

<center><?php include "./includes/print_header.php"; ?></center>

<?php } ?>
<div class="well well-sm"><strong>PTBA </strong><?php echo $annee;?>&nbsp;&nbsp;Editer le : <span class="Style5"><u><?php echo date("d/m/Y"); ?></u></span></div>

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive">
  <tr bgcolor="#FFFFFF">
    <td ><span class="Style14"><strong>Activit&eacute;s</strong></span><span class="Style14"></span></td>

      <td width="2%" align="center" nowrap="nowrap"><b>N&deg;&nbsp;</b></td>
      <td > <div align="center"><strong>T&acirc;ches </strong></div> </td>
      <td  align="center"><b>Proportion</b></td>


      <td  align="center" nowrap="nowrap"><b>Co&ucirc;t (Ouguiya) </b></td>
     <?php foreach($tableauMois as $vmois){
$amois = explode('<>',$vmois); ?>
<td align="center" width="30"><?php echo $amois[2]; ?> </td>
<?php } ?>
<td align="center">R&eacutesultats attendus </td>
  </tr>

<?php $tgptba=0; if( isset($annee)) {
//Activit&eacute;s de la sous composante
//$cp=$cp; if(isset($_GET['id_act'])) $ac=$_GET['id_act']; else $ac=" ";
//$id_scp=$row_rscp['id_sous_composante'];

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = "SELECT * FROM ".$database_connect_prefix."ptba where annee='$annee' order by code_activite_ptba asc";
$act  = mysql_query($query_act , $pdar_connexion) or die(mysql_error());
$row_act  = mysql_fetch_assoc($act);
$totalRows_act  = mysql_num_rows($act);	$tcscp=0;

if($totalRows_act>0) {$p11="k"; $p1="j"; $o=0; $mi=0; $fcode_act="ca"; do {
$id_act=$row_act['code_activite_ptba'];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_tache = "select * FROM ".$database_connect_prefix."groupe_tache where code_activite='$id_act' ORDER BY code_tache ASC";
$liste_tache  = mysql_query($query_liste_tache , $pdar_connexion) or die(mysql_error());
$row_liste_tache  = mysql_fetch_assoc($liste_tache);
$totalRows_liste_tache  = mysql_num_rows($liste_tache);
?>
<tr >
  <td colspan="18"><span class="Style14"><?php echo "<strong>".$row_act['code_activite_ptba'].":</strong> ".$row_act['intitule_activite_ptba']; ?></span></td>
</tr>
   <?php if($totalRows_liste_tache>0) {$m=0; $sp=0;?>

<?php  $ii=0; $pp=0; $tind=0;$mi=0; $mm=0; do {
$code_tache=$row_liste_tache['code_tache'];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind = "select * FROM ".$database_connect_prefix."indicateur_tache where tache='$code_tache' ORDER BY code_indicateur_ptba ASC";
$liste_ind  = mysql_query($query_liste_ind , $pdar_connexion) or die(mysql_error());
$row_liste_ind  = mysql_fetch_assoc($liste_ind);
$totalRows_liste_ind  = mysql_num_rows($liste_ind);

?>
<tr <?php if($o%2==0) echo 'bgcolor="#ECF0DF"'; $o=$o+1;?>>
<?php //if($pp==0){ ?>
<td rowspan="<?php echo $totalRows_liste_ind+1; ?>">&nbsp;</td>
<?php // }  ?>

<td width="2%" rowspan="<?php echo $totalRows_liste_ind+1; ?>" align="center"><span class="Style12"><?php echo $row_liste_tache['code_tache']; ?></span></td>
<td rowspan="<?php echo $totalRows_liste_ind+1; ?>" ><span class="Style12"><?php if($totalRows_liste_tache>0){ echo $row_liste_tache['intitule_tache']; $sp=$sp+$row_liste_tache["proportion"]; }else echo "Aucune"; ?></span></td>

<td rowspan="<?php echo $totalRows_liste_ind+1; ?>"  align="center"><?php if($totalRows_liste_tache>0){ echo $row_liste_tache["proportion"]." %";  } ?>&nbsp;</td>

<td rowspan="<?php echo $totalRows_liste_ind+1; ?>"  align="center" nowrap="nowrap"><?php if($totalRows_liste_tache>0 ){ echo number_format($row_liste_tache['cout_tache'], 0, ',', ' ');} ?></td>
<?php foreach($tableauMois as $vmois){
$amois = explode('<>',$vmois);
$imois = $amois[0];
$a = explode(",", $row_liste_tache['periode']);
?>
<td width="30" rowspan="<?php echo $totalRows_liste_ind+1; ?>" class=" "><a style="display: block; background-color:<?php if(in_array($imois, $a, TRUE)) echo "#CCCCCC"; ?> ">&nbsp;</a></td>
<?php } ?>
<?php if($totalRows_liste_ind==0){ ?>
<td align="center">&nbsp;</td>
<?php } ?>
<?php  if($fcode_act!=$row_act['code_activite_ptba']){ $pp=0;  } $pp++; $fcode_act=$row_act['code_activite_ptba']; ?>
</tr>
<?php for($l=1;$l<=$totalRows_liste_ind;$l++){ ?>
<tr <?php if($o%2==0) echo 'bgcolor="#ECF0DF"'; $o=$o+1;?>>
 <!--<td align="center">&nbsp;</td> --><td align="center">R&eacutesultats attendus </td>
</tr>
<?php } ?>

<?php  } while ($row_liste_tache = mysql_fetch_assoc($liste_tache)); ?>
<!-- <tr>
<td colspan="18" align="left">&nbsp;</td>
</tr>-->
<?php } ?>

              <?php $tg=0; $i=0; $ttmp=0;?>

          <?php // }   ?>

       <?php    } while ($row_act = mysql_fetch_assoc($act)); ?>
          <?php } ?>
           <?php } ?>
</table>  
                <div class="clear h0"></div></div>

<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>    <?php if(!isset($_GET["down"])) include_once 'modal_add.php'; ?>
    <?php if(!isset($_GET["down"])) include_once("includes/footer.php"); ?>
</div>

</body>
</html>