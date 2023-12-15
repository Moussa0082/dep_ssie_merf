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

function week2period($annee, $semaine)
{
    $lundi = new DateTime();
    $lundi->setISOdate($annee, $semaine);
 
    $dimanche = new DateTime();
    $dimanche->setISOdate($annee, $semaine);
    date_modify($dimanche, '+6 day');
 
    return $lundi->format('d/m/Y') . " au " . $dimanche->format('d/m/Y');
}

$date_test = date("Y-m-d");
$good_format=strtotime ($date_test);
$num_semainec= date('W',$good_format);
$num_semaine=(isset($_GET['semaine']))?$_GET['semaine']:$num_semainec;
//echo $num_semaine;
//exit;

 if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=taches_activites_ptba.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=taches_activites_ptba.doc"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="p"){

require_once('./tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$PDF_HEADER_TITLE = "Tâches des activités du PTBA";
$PDF_HEADER_STRING = "Tâches des activités du PTBA";

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Ruche');
$pdf->SetTitle($PDF_HEADER_TITLE);
$pdf->SetSubject($PDF_HEADER_STRING);
$pdf->SetKeywords('PDF, mission, Tâches des activités du PTBA');

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
  include("./print_taches_activite_ptba_pdf.php");
  $content = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering

$html = utf8_encode($content);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('taches_activites_ptba.pdf', 'D');
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
$tableauMois=array('01<>Jan<>J','02<>Fev<>F','03<>Mars<>M','04<>Avril<>A','05<>Mai<>M','06<>Juin<>J','07<>Juil<>J','08<>Aout<>A','09<>Sep<>S','10<>Oct<>O','11<>Nov<>N','12<>D&eacute;c<>D');
//$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Ao&ucirc;t","Septembre","Octobre","Novembre","D&eacute;cembre");
$annee=(isset($_GET['annee']))?$_GET['annee']:date("Y");
$ugl=(isset($_GET['ugl']))?$_GET['ugl']:$_SESSION["clp_structure"];
$query_edit_ms = "SELECT code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=1 and projet='".$_SESSION["clp_projet"]."' ORDER BY code asc";
  try{
    $edit_ms = $pdar_connexion->prepare($query_edit_ms);
    $edit_ms->execute();
    $row_edit_ms = $edit_ms ->fetchAll();
    $totalRows_edit_ms = $edit_ms->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


if(isset($_GET["acteur"]) && $_GET["acteur"]!="") {$iactget=$_GET["acteur"]; $wheract_tache="AND groupe_tache.responsable='$iactget'"; } else {$wheract_tache=""; $iactget=$_SESSION["clp_fonction"];}

//if(isset($_GET["acteur"])) {$iactget=$_GET["acteur"]; $wheract_tache="AND ugl='$iactget'"; } else $wheract_tache="";
//$wheract_tache="";
/*$query_tache = "select id_ptba, id_groupe_tache, groupe_tache.responsable, code_tache, proportion, intitule_tache, date_debut, date_fin, sum(tlot) as lot, count(id_tache_ugl) as crp_c   FROM tache_ugl, ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."ptba where id_groupe_tache=tache and id_ptba=id_activite $wheract_tache and tlot!=0 and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."ptba.annee=$annee group by id_ptba, id_groupe_tache, responsable, code_tache, proportion, intitule_tache, date_debut, date_fin ORDER BY code_tache ASC";*/

//$query_tache = "select id_ptba, type_tache.id_groupe_tache, groupe_tache.responsable, type_tache.ordre as code_tache, type_tache.proportion, type_tache.intitule_tache, date_debut, date_fin   FROM ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."ptba, type_tache where type_tache.id_groupe_tache=groupe_tache.id_groupe_tache and id_ptba=id_activite $wheract_tache   and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."ptba.annee=$annee group by id_ptba, type_tache.id_groupe_tache, responsable, type_tache.ordre, type_tache.proportion, type_tache.intitule_tache, date_debut, date_fin ORDER BY type_tache.ordre ASC";

$query_tache = "select * FROM  ".$database_connect_prefix."ptba, ".$database_connect_prefix."groupe_tache where id_ptba=id_activite and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' $wheract_tache and ".$database_connect_prefix."ptba.annee=$annee ORDER BY code_tache ASC";
/*else
$query_tache = "SELECT id_ptba, id_groupe_tache, groupe_tache.responsable, code_tache, proportion, intitule_tache, date_debut, date_fin,  code_activite_ptba
FROM groupe_tache, ptba WHERE id_ptba = id_activite AND ptba.projet ='".$_SESSION["clp_projet"]."' AND ptba.annee =$annee ORDER BY code_tache ASC";*/
  try{
    $tache = $pdar_connexion->prepare($query_tache);
    $tache->execute();
    $row_tache = $tache ->fetchAll();
    $totalRows_tache = $tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tache_array = array();
$ttt=0; $maxt=0; $idmaxt=0; if($totalRows_tache>0) { foreach($row_tache as $row_tache){

//$date_format=strtotime($row_tache["date_debut"]);
//$num_semaine= date('W',$good_format);
if(date('W',strtotime($row_tache["date_debut"]))==$num_semaine || date('W',strtotime($row_tache["date_fin"]))==$num_semaine) {
$tache_array[$row_tache["id_ptba"]][$row_tache["id_groupe_tache"]] = array("code_activite"=>$row_tache["id_ptba"],"code_tache"=>$row_tache["code_tache"],"proportion"=>$row_tache["proportion"],"intitule_tache"=>$row_tache["intitule_tache"],"periode"=>$row_tache["id_groupe_tache"],"responsable"=>$row_tache["responsable"],"date_debut"=>$row_tache["date_debut"],"date_fin"=>$row_tache["date_fin"]);
} }   }



if(isset($_GET['ugl']))
$query_periode0 = "SELECT * FROM ".$database_connect_prefix."tache_ugl where ugl='$ugl' group by tache ";
else
$query_periode0 = "SELECT * FROM ".$database_connect_prefix."tache_ugl  group by tache ";
  try{
    $periode0 = $pdar_connexion->prepare($query_periode0);
    $periode0->execute();
    $row_periode0 = $periode0 ->fetchAll();
    $totalRows_periode0 = $periode0->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$periode = array();
if($totalRows_periode0>0){ foreach($row_periode0 as $row_periode0){
 for($i=$row_periode0["tdebut"];$i<=$row_periode0["tfin"];$i++) $periode[$row_periode0["tache"]][]=(strlen($i)==1)?'0'.$i:$i; }  }
 
//$query_liste_prestataire = "SELECT * FROM ".$database_connect_prefix."ugl order by code_ugl";
$query_liste_prestataire = "SELECT fonction, nom, prenom FROM ".$database_connect_prefix."personnel order by fonction";
  try{
    $liste_prestataire = $pdar_connexion->prepare($query_liste_prestataire);
    $liste_prestataire->execute();
    $row_liste_prestataire = $liste_prestataire ->fetchAll();
    $totalRows_liste_prestataire = $liste_prestataire->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$acteur_array = $Nacteur_array= array();
  if($totalRows_liste_prestataire>0){  foreach($row_liste_prestataire as $row_liste_prestataire1){
    $Nacteur_array[$row_liste_prestataire1["fonction"]] = $row_liste_prestataire1["nom"]." ".$row_liste_prestataire1["prenom"];
	$acteur_array[] = $row_liste_prestataire1["fonction"]."!!".$row_liste_prestataire1["nom"]." ".$row_liste_prestataire1["prenom"];
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
  $annee_ptba=$row_liste_mission['annee_ptba'];
  $annees=$annee_ptba;
  
  //exit;
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
} .table tbody tr td {vertical-align: middle; } .marquer{background: #EBEBEB!important; }
</style>
<div class="contenu">

<?php if(!isset($_GET["down"])){  ?>
<form name="form<?php echo $annee; ?>" id="form<?php echo $annee; ?>" method="get" action="<?php echo "print_taches_activite_semaine.php?annee=".$annee; ?>" class="pull-left">

 <select name="acteur" onchange="form<?php echo $annee; ?>.submit();" style="background-color: #FFFF00; padding: 7px;" class="btn p11">
  <option value="">Choisissez un responsable</option>

            <?php
				  if($totalRows_liste_prestataire>0) { foreach($row_liste_prestataire as $row_liste_prestataire){ ?>
            <option <?php //if(isset($id_ms) && $id_ms==$row_liste_prestataire['id_acteur']) {echo 'SELECTED="selected"';  $nom=$row_liste_prestataire['objet'];}  ?> value="<?php echo  $row_liste_prestataire['fonction']; ?>"> <?php echo "<b>".$row_liste_prestataire['nom']." ".$row_liste_prestataire['prenom']." (".$row_liste_prestataire['fonction'].")"."</b> ";?>
            </option>
            <?php } } ?>
  </select>
  <input type="hidden" name="annee" value="<?php echo $annee; ?>" />

</form>

<form name="form<?php echo $annee; ?>1" id="form<?php echo $annee; ?>1" method="get" action="<?php echo "print_taches_activite_semaine.php?annee=".$annee; ?>" class="pull-left"> P�riode :&nbsp;

 <select onchange="form<?php echo $annee; ?>1.submit();" name="semaine" id="semaine" class="select2 required" data-placeholder="S&eacute;lectionnez une semaine" >
              <option></option>
  <?php $annees=$annee_ptba; if($annees==date("Y")) $limitsemaine=$num_semainec+2; else $limitsemaine=53;  for($j=1;$j<=$limitsemaine;$j++){ if($j<=53) {  ?>
  <option value="<?php echo $j; ?>" <?php if((isset($_GET["semaine"]) && $j==$num_semaine)) echo "selected='SELECTED'"; ?>>
  <?php  
  
  echo week2period($annees, $j);
/*  
echo "semaine".$j."&nbsp;&nbsp;"; 
 echo $annees; 
  // Cr�e un timestamp pour le 1/1/annee

$begin = mktime(0,0,0,1,1,$annees); 

// Ajoute les semaines
$offset = strtotime("+$j weeks",$begin);

// Le lundi
if (date('w',$offset) == 1){$lundi = date('Y-m-d',$offset);} else {
$offset = strtotime("last monday",$offset); $lundi = date('yyyy-m-d',$offset); }
//echo $lundi;
echo date('d/m/y',$lundi+6);
//exit;
echo "du ".date("d/m/Y", strtotime($lundi." + 0 days"))." au ".date("d/m/Y", strtotime($lundi." + 6 days"));*/
  
   ?>
  </option>
  <?php } } ?>
 </select>
<input type="hidden" name="annee" value="<?php echo $annee; ?>" />
<input type="hidden" name="acteur" value="<?php echo $iactget; ?>" />

</form>
<div class="well well-sm r_float"><div class="r_float"><a href="./<?php  echo  "s_programmation.php";  ?>" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
</div>
<div class="clear h0">&nbsp;</div>
<?php } else { ?>

<center><?php include "./includes/print_header.php"; ?></center>

<?php } ?>
<div class="well well-sm"><strong>T&Acirc;CHES DES ACTIVITES DU PTBA <?php echo "$lib_version_ptba"; ?> de la semaine  &nbsp;&nbsp;<span style="background-color:#FFCC33">
  <?php if(isset($_GET["acteur"])  && $_GET["acteur"]!="") echo  "&nbsp;(<u>".$_GET["acteur"]."</u>)&nbsp;"; //elseif(isset($_GET["acteur"]) && $_GET["acteur"]==1) echo  "&nbsp;(<u>ProCAD</u>)&nbsp;"; ?>
</span></strong></div>


<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-bordered table-responsive">

            <thead>
            <tr>
              <th align="left">ACTIVITES</th>
              <th align="left">TACHES</th>
              <th >Responsable</th>
              <th >Proportion</th>
              <th >D&eacute;but</th>
              <th >Fin</th>
              <?php foreach($tableauMois as $vmois){
              $amois = explode('<>',$vmois); ?>
              <th align="center"><?php echo $amois[2]; ?> </th>
              <?php } ?>
            </tr>
            </thead>
<?php  if($totalRows_edit_ms>0) { foreach($row_edit_ms as $row_edit_ms){
$code = $row_edit_ms['code'];
//mysql_select_db($database_pdar_connexion, $pdar_connexion);
/*if(isset($_GET['ugl']))
$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."ptba where projet='".$_SESSION["clp_projet"]."' and annee='$annee' and code_activite_ptba like '$code%' and ptba.region like '%$ugl%'  ORDER By code_activite_ptba asc";
else
$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."ptba where projet='".$_SESSION["clp_projet"]."' and annee='$annee' and code_activite_ptba like '$code%'  ORDER By code_activite_ptba asc";*/

//if(isset($_GET["acteur"])) {$iactget=$_GET["acteur"]; $wheract="AND FIND_IN_SET('$iactget', region)"; } else $wheract="";

$query_liste_rec = "SELECT ptba.* FROM ".$database_connect_prefix."ptba where  ptba.projet='".$_SESSION["clp_projet"]."' and ptba.annee='$annee' and ptba.code_activite_ptba like '$code%'  ORDER By ptba.code_activite_ptba asc";
//echo $query_liste_rec;
//exit;
  try{
    $liste_rec = $pdar_connexion->prepare($query_liste_rec);
    $liste_rec->execute();
    $row_liste_rec = $liste_rec ->fetchAll();
    $totalRows_liste_rec = $liste_rec->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_liste_rec>0) {$i=0; $t=0; $p2=$p1="j"; ?>
            <tr bgcolor="#BED694">
              <td colspan="18" align="center" style="background-color: #BED694;">
                <b><?php echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?></b>              </td>
            </tr>
<?php $row=$tem=""; foreach($row_liste_rec as $row_liste_rec){ $code_act = $row_liste_rec['id_ptba']; if(isset($tache_array[$code_act])){
  if($tem!=$code_act){
foreach($tache_array[$code_act] as $a=>$b){ ?>
            <tr>
<?php if($row!=$b["code_activite"]){ ?>
<td width="20%" rowspan="<?php echo count($tache_array[$code_act]); ?>"><?php echo "<b>".$row_liste_rec['code_activite_ptba'].'</b> : '.$row_liste_rec['intitule_activite_ptba']; ?></td>
<?php } ?>
<td><?php echo $b['intitule_tache']; ?></td>
<td width="50" align="center"><?php echo $b['responsable']; ?></td>
<td width="50" align="center"><?php echo $b['proportion'].'%'; ?></td>
<td width="50" align="center"><?php echo implode('/',array_reverse(explode('-',$b['date_debut']))); ?></td>
<td width="50" align="center" <?php if(substr($b['date_fin'], 0, -6)>date("Y")) echo "bgcolor='#FF6600'"; ?>><?php echo implode('/',array_reverse(explode('-',$b['date_fin']))); ?></td>
<?php foreach($tableauMois as $vmois){
$amois = explode('<>',$vmois);  $tab_debut=explode("-",$b['date_debut']); $md=$tab_debut[1]; $tab_fin=explode("-",$b['date_fin']); $mf=$tab_fin[1]; //echo $md; exit; ?>
<td  <?php if(($amois[0]==$md) || ($amois[0]==$mf) || (intval($amois[0])<intval($mf) && intval($amois[0])>intval($md))) echo "bgcolor='#EAEAEA'"; ?> valign="middle" align="center" ></td>
<?php } ?>
            </tr>
            <?php $row=$b["code_activite"]; } } } $tem=$code_act; ?>
			<?php if(isset($tache_array[$code_act]) && count($tache_array[$code_act])>0) { ?>
			<tr class="even">
  <td colspan="18"><div align="center" style="background-color:#CCCCCC; height: 2px;">&nbsp;</div></td>
</tr>
 <?php }  ?>
			 <?php }  ?>
            <?php } else { ?>
<!--            <tr>
              <td colspan="15"><div align="center"><span class="Style4"><em><strong>Aucune activit&eacute; enregistr&eacute;e dans la composante <?php //echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?> ! </strong></em></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span></div></td>
            </tr>-->
            <?php }  ?>
      <?php }  } else { ?>
      <tr>
        <td colspan="18" align="center"><strong><em>Aucune composante trouv&eacute;e!</em></strong></td>
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