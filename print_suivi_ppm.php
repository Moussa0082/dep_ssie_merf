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


 $query_liste_marche = "SELECT *   FROM ".$database_connect_prefix."plan_marche where periode like '%$version%' and categorie='$modele' and projet='".$_SESSION["clp_projet"]."' order by  categorie, code_marche asc";
             try{
    $liste_marche = $pdar_connexion->prepare($query_liste_marche);
    $liste_marche->execute();
    $row_liste_marche = $liste_marche ->fetchAll();
    $totalRows_liste_marche = $liste_marche->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//echo  $query_liste_marche; exit;
$query_liste_filiere = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE projet='".$_SESSION["clp_projet"]."' and niveau=2";
           try{
    $liste_filiere = $pdar_connexion->prepare($query_liste_filiere);
    $liste_filiere->execute();
    $row_liste_filiere = $liste_filiere ->fetchAll();
    $totalRows_liste_filiere = $liste_filiere->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$codef_array = array();
if($totalRows_liste_filiere>0){ foreach($row_liste_filiere as $row_liste_filiere){  $codef_array[$row_liste_filiere["code"]]=$row_liste_filiere["intitule"]; } }


$query_liste_etape_modele = "SELECT * FROM ".$database_connect_prefix."etape_marche, etape_plan_marche, plan_marche where code=code_etape and id_marche=marche and modele_marche=modele_concerne  ORDER BY code asc";
           try{
    $liste_etape_modele = $pdar_connexion->prepare($query_liste_etape_modele);
    $liste_etape_modele->execute();
    $row_liste_etape_modele = $liste_etape_modele ->fetchAll();
    $totalRows_liste_etape_modele = $liste_etape_modele->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$TableauEtapeM = array();
$DureeEtape = array();
if($totalRows_liste_etape_modele>0){ foreach($row_liste_etape_modele as $row_liste_etape_modele){ 
$TableauEtapeM[$row_liste_etape_modele["groupe"]][$row_liste_etape_modele["marche"]]=$row_liste_etape_modele["duree_prevue"];
 } }

$query_liste_etape_plan = "SELECT * FROM ".$database_connect_prefix."groupe_etape where categorie_groupe like '%$modele%' ORDER BY num_groupe, code_groupe asc";
           try{
    $liste_etape_plan = $pdar_connexion->prepare($query_liste_etape_plan);
    $liste_etape_plan->execute();
    $row_liste_etape_plan = $liste_etape_plan ->fetchAll();
    $totalRows_liste_etape_plan = $liste_etape_plan->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$TableauEtape = array();
if($totalRows_liste_etape_plan>0){ foreach($row_liste_etape_plan as $row_liste_etape_plan){
$TableauEtape[]=$row_liste_etape_plan["id_groupe"]."<>".$row_liste_etape_plan["libelle_groupe"]."<>".$row_liste_etape_plan["code_groupe"];
 } }


$query_liste_modele = "SELECT * FROM ".$database_connect_prefix."categorie_marche where code_categorie='$modele'";
           try{
    $liste_modele = $pdar_connexion->prepare($query_liste_modele);
    $liste_modele->execute();
    $row_liste_modele = $liste_modele ->fetch();
    $totalRows_liste_modele = $liste_modele->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


$query_liste_date_etape = "SELECT * FROM ".$database_connect_prefix."suivi_plan_marche";
           try{
    $liste_date_etape = $pdar_connexion->prepare($query_liste_date_etape);
    $liste_date_etape->execute();
    $row_liste_date_etape = $liste_date_etape ->fetch();
    $totalRows_liste_date_etape = $liste_date_etape->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$etape_array = array();
    if($totalRows_liste_date_etape>0){
	 foreach($row_liste_date_etape as $row_liste_date_etape){ if(isset($GroupeEtape[$row_liste_date_etape["etape"]])) $etape_array[$row_liste_date_etape["marche"]][$GroupeEtape[$row_liste_date_etape["etape"]]]=$row_liste_date_etape['date_reelle']; }}
	
$query_liste_etape_marche = "SELECT duree_prevue, idetape, marche FROM ".$database_connect_prefix."etape_plan_marche ORDER BY marche, code_etape asc";
           try{
    $liste_etape_marche = $pdar_connexion->prepare($query_liste_etape_marche);
    $liste_etape_marche->execute();
    $row_liste_etape_marche = $liste_etape_marche ->fetchAll();
    $totalRows_liste_etape_marche = $liste_etape_marche->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_etape_marche_array = array();
    if($totalRows_liste_etape_marche>0){
	foreach($row_liste_etape_marche as $row_liste_etape_marche){ $liste_etape_marche_array[$row_liste_etape_marche["idetape"]][$row_liste_etape_marche["marche"]]=$row_liste_etape_marche['duree_prevue'];}}

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

<div class="well well-sm"><strong>Tableau du suivi plan de passation des march&eacute;s, actualis&eacute; au <?php echo date("d/m/Y"); ?></strong></div>

<h3><?php if(isset($modele_array[$modele])) echo $modele_array[$modele]; ?></h3>

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive dataTable">
<thead>
<tr role="row" <?php echo (!isset($_GET["down"]))?'':'bgcolor="#DCDCDC"'; ?>>
<th><center>N&deg;<br/>
</center></th>
<th>Intitul&eacute; du march&eacute; </th>
<th width="80"><center>
  Cat&eacute;gorie </center></th>
<th width="80"><center>
  M&eacute;thode </center></th>
<th><center>
  N&deg;AO
</center></th>
<th>Co&ucirc;t (XOF) </th>

<th>D&eacute;marrage pr&eacute;vu </th>
<th>&nbsp;</th>
 <?php $ib=0; foreach($TableauEtape as $vmois){
  $amois = explode('<>',$vmois);
  if($ib==0) { $first_etape=$amois[0]; $ib=1;}
              ?>
              <th align="center"><?php echo $amois[2]; ?> </th>
              <?php  $last_etape=$amois[0]; } ?>
<th>&nbsp;</th>
</tr>
</thead>

<tbody class="">

<?php if($totalRows_liste_marche>0) { $date_ep=array();  $p1="j"; foreach($row_liste_marche as $row_liste_marche){ $id = $row_liste_marche['id_marche']; $Nombres_jours=0; $duree_totale=0;  number_format($Nombres_jours, 0, ',', ' '); 
 $date_start = $row_liste_marche['date_prevue'];
if(in_array($version, explode(",", $row_liste_marche['periode']))){
?>

 <?php if($p1!=$row_liste_marche['composante']) {?>
          <tr bgcolor="#FF9934">
            <td colspan="<?php echo count($TableauEtape)+8; ?>" align="center" bgcolor="#D2E2B1"><div align="left" class="Style4" style="background-color:#D2E2B1"><strong>

                      <?php if(isset($codef_array[$row_liste_marche['composante']])) echo $codef_array[$row_liste_marche['composante']]; else echo $row_liste_marche['composante'];
                      $p1=$row_liste_marche['composante']; ?>
                        </strong></div></td>
            </tr>
          <?php } ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td rowspan="2" class=" "><?php echo "<strong>".$row_liste_marche['id_marche']."</strong> "; ?></td>

<td rowspan="2" class=" "><?php echo $row_liste_marche['intitule']; ?></td>
<td rowspan="2" class=" "><?php echo $row_liste_marche['categorie']; ?></td>

<td rowspan="2" class=" "><?php echo $row_liste_marche['methode']; ?></td>

<td rowspan="2" class=" " style="<?php echo (isset($exp[0]) && $exp[0]==$a)?"background-color: #EEEEEE;":""; ?>">&nbsp;</td>
<td rowspan="2" nowrap="nowrap" class=" " style="<?php echo (isset($exp[0]) && $exp[0]==$a)?"background-color: #EEEEEE;":""; ?>"><?php  echo number_format($row_liste_marche["montant_usd"], 0, ',', ' ');  ?></td>

<td rowspan="2" class=" " style="<?php echo (isset($exp[0]) && $exp[0]==$a)?"background-color: #EEEEEE;":""; ?>"><span class=" " style="<?php echo (isset($exp[0]) && $exp[0]==$a)?"background-color: #EEEEEE;":""; ?>">
  <?php  echo implode('/',array_reverse(explode('-',$row_liste_marche['date_prevue'])));  ?>
</span></td>
<td class=" ">Plan</td>
<?php $i=0;  foreach($TableauEtape as $vmois){
$amois = explode('<>',$vmois);
//$ad=array();
/* if(isset($amois[2])) {
		   $as = explode(",", $amois[2]); 
		   $b = explode(",", $amois[3]); $i=0;
          foreach($as as $a){ if(!empty($a)) $ad[$a]=$b[$i]; $i++; }
		   } */
		    $i++;
			if(isset($TableauEtapeM[$amois[0]][$row_liste_marche["id_marche"]])) $duree_totale=$duree_totale+$TableauEtapeM[$amois[0]][$row_liste_marche["id_marche"]]; 
			//else $duree_totale=$duree_totale+$amois[2]; 
			
			 ?>
<td width="50"  valign="middle" align="center"><?php
if( isset($amois[2]) && isset($TableauEtapeM[$amois[0]][$row_liste_marche["id_marche"]])) {
 echo  date("d/m/Y", strtotime('+'.$duree_totale.'days', strtotime($date_start))); $date_ep[$amois[0]]=date("Y-m-d", strtotime('+'.$duree_totale.'days', strtotime($date_start)));
  }  else echo "N/A";?></td>
<?php } ?><td class=" "><span>
  <?php  echo $duree_totale;  ?>
</span></td>
</tr>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
  <td class=" " ><span>Suivi</span></td>
   <?php foreach($TableauEtape as $vmois){
              $amois = explode('<>',$vmois); ?>
              <td align="center">
			 <?php 
			// echo $amois[0]." ddd";
			   if(isset($date_ep[$amois[0]]) && isset($etape_array[$row_liste_marche["id_marche"]][$amois[0]]) 
			   && $etape_array[$row_liste_marche["id_marche"]][$amois[0]]>$date_ep[$amois[0]])
			    {echo "<span style=\"background-color:#990000; color:#FFFFFF\">&nbsp;&nbsp;".implode('/',array_reverse(explode('-',$etape_array[$row_liste_marche["id_marche"]][$amois[0]])))."&nbsp;&nbsp;</span>";}
			  elseif(isset($date_ep[$amois[0]]) && isset($etape_array[$row_liste_marche["id_marche"]][$amois[0]]) 
			   && $etape_array[$row_liste_marche["id_marche"]][$amois[0]]<$date_ep[$amois[0]])
			   {echo "<span style=\"background-color:#006600; color:#FFFFFF\">&nbsp;&nbsp;".implode('/',array_reverse(explode('-',$etape_array[$row_liste_marche["id_marche"]][$amois[0]])))."&nbsp;&nbsp;</span>";}
			   elseif(isset($date_ep[$amois[0]]) && isset($etape_array[$row_liste_marche["id_marche"]][$amois[0]]))
			    echo  implode('/',array_reverse(explode('-',$etape_array[$row_liste_marche["id_marche"]][$amois[0]])));
			  
			   ?>			  </td>
			   
              <?php } ?>
  <td ><?php  //echo "fe=".$first_etape." le=".$last_etape;
  
  if(isset($etape_array[$row_liste_marche["id_marche"]][$first_etape])) 
			{if(isset($etape_array[$row_liste_marche["id_marche"]][$last_etape])) $ntjs=NbJours($etape_array[$row_liste_marche["id_marche"]][$first_etape], $etape_array[$row_liste_marche["id_marche"]][$last_etape])-1; else  $ntjs=NbJours($etape_array[$row_liste_marche["id_marche"]][$first_etape], date("Y-m-d"))-1;
			
			if($duree_totale<$ntjs) {echo "<span style=\"background-color:#990000; color:#FFFFFF\">&nbsp;&nbsp;".number_format(($ntjs), 0, ',', ' ')."&nbsp;&nbsp;</span>";}
			  elseif($duree_totale>$ntjs){echo "<span style=\"background-color:#006600; color:#FFFFFF\">&nbsp;&nbsp;".number_format(($ntjs), 0, ',', ' ')."&nbsp;&nbsp;</span>";}
			  else echo  number_format(($ntjs), 0, ',', ' '); 
			}
    ?></td>
</tr>
<?php } } } else { ?>

<tr>

<td colspan="<?php echo (9+count($destinateur_array)); ?>"><h2 align="center">Aucune donn&eacute;e !</h2></td>
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