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
include_once $config->sys_folder . "/database/db_connexion.php"; // ini_set("display_errors",1);
$beneficaire_array = array("fiche_437360142_details_435390002"=>"622990013");
$beneficaire_data_array = array("fiche_437360142_details_435390002"=>array("443250001"=>"nom","411890100"=>"prenom","622990013"=>"identifiant","427470003"=>"sexe","419580014"=>"age","427430046"=>"contact","447080125"=>"photo","419480034"=>"type","581120943"=>"village","584920970"=>"structure","429450019"=>"localite","433410117"=>"maillon"));

if(isset($_GET['annee']) && intval($_GET['annee'])>0) {$annee=intval($_GET['annee']);} else {$annee=date("Y");}
if(isset($_GET['classeur'])) {$classeur=$_GET['classeur'];}
if(isset($_GET['feuille'])) {$feuille=$_GET['feuille'];}
$cp=$feuille;

$libelle = array("D&eacute;partements","Communes","Arrondissements","Villages/Quartiers");
$loc = array("region","departement","commune","village");
//niveau=1 correspond à commune
//Localite
/*$niveau=1;
$val0 = $val1 = $val2 = "";
for($i=0; $i<$niveau; $i++) {
$val0 .= " TRIM(T$i.nom_".$loc[$i]."), ";
$val1 .= (($i>0)?" and":"")." T".($i+1).".".$loc[$i]."=T$i.code_".$loc[$i]." ";
$val2 .= $database_connect_prefix.$loc[$i]." T$i, ";
}
$val0 .= " T$i.* ";
$val1 = ($i>0)?" WHERE ".$val1:$val1;
$val2 .= $database_connect_prefix.$loc[$i]." T$i ";
$query_liste_activite_1 = "SELECT distinct $val0 FROM $val2 $val1 ORDER BY T$i.code_".$loc[$niveau]." ASC";
$commune_array = array();
try{
    $liste_activite_1 = $pdar_connexion->prepare($query_liste_activite_1);
    $liste_activite_1->execute();
    $row_liste_activite_1 = $liste_activite_1 ->fetchAll();
    $totalRows_liste_activite_1 = $liste_activite_1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_liste_activite_1>0){ foreach($row_liste_activite_1 as $row_liste_activite_1){
    $row_liste_activite_1["nom_".$loc[$niveau]] = trim(str_replace("&nbsp;","",$row_liste_activite_1["nom_".$loc[$niveau]]));
    $commune_array[trim($row_liste_activite_1["nom_".$loc[$niveau]])] = trim($row_liste_activite_1["code_".$loc[$niveau]]);
}}

    $query_liste_cp = "SHOW tables";
    try{
        $liste_cp = $pdar_connexion->prepare($query_liste_cp);
        $liste_cp->execute();
        $row_liste_cp = $liste_cp ->fetchAll();
        $totalRows_liste_cp = $liste_cp->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $cp_array=array();
    if($totalRows_liste_cp>0) {
    foreach($row_liste_cp as $row_liste_cp1) {  if(strchr($row_liste_cp1["Tables_in_$database_pdar_connexion"],"fiche")!="" && strchr($row_liste_cp1["Tables_in_$database_pdar_connexion"],"fiche")!="fiche_config"){  $cp_array[]=$row_liste_cp1["Tables_in_$database_pdar_connexion"];
    }
    } }

    //if(isset($cp) && !in_array($cp,$cp_array)) unset($cp);

    $entete_array = $libelle = array();

    list($a,$classeur)=explode("_",$feuille);
    $query_liste_classeur = "SELECT * FROM ".$database_connect_prefix."classeur WHERE id_classeur=$classeur ".((isset($_SESSION["clp_projet_sigle"]) && !empty($_SESSION["clp_projet_sigle"]))?"AND note=".GetSQLValueString($_SESSION["clp_projet_sigle"], "text"):"");
      try{
        $liste_classeur = $pdar_connexion->prepare($query_liste_classeur);
        $liste_classeur->execute();
        $row_liste_classeur = $liste_classeur ->fetch();
        $totalRows_liste_classeur = $liste_classeur->rowCount();
      }catch(Exception $e){ die(mysql_error_show_message($e)); }

    $query_act = "SELECT DISTINCT YEAR(submissionDate) as annee FROM $cp ORDER BY YEAR(submissionDate) asc ";
    try{
        $act = $pdar_connexion->prepare($query_act);
        $act->execute();
        $row_act = $act ->fetchAll();
        $totalRows_act = $act->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $annee_array = array();
    if($totalRows_act>0) {
        foreach($row_act as $row_act1) $annee_array[] = $row_act1["annee"];
    }

    $query_act = "SELECT ".(isset($beneficaire_data_array[$feuille]["structure"])?"DISTINCT ".$beneficaire_data_array[$feuille]["structure"]:"*")." FROM $cp ";
    try{
        $act = $pdar_connexion->prepare($query_act);
        $act->execute();
        $row_act = $act ->fetchAll();
        $totalRows_act = $act->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $beneficaire_data_array[$feuille] = array_flip($beneficaire_data_array[$feuille]);
    $structure_array = array();
    foreach($row_act as $row_act1){
        if(isset($row_act1[$beneficaire_data_array[$feuille]["structure"]]) && !empty($row_act1[$beneficaire_data_array[$feuille]["structure"]])) $structure_array[$row_act1[$beneficaire_data_array[$feuille]["structure"]]] = $row_act1[$beneficaire_data_array[$feuille]["structure"]];
    }

    $tab = substr($cp,strlen($database_connect_prefix));

    $query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$tab'";
    try{
        $entete = $pdar_connexion->prepare($query_entete);
        $entete->execute();
        $row_entete = $entete ->fetch();
        $totalRows_entete = $entete->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }

    if($totalRows_entete>0){ $choix_array = array(); $nomT=$row_entete["nom"]; $note=$row_entete["note"]; $entete_array=explode("|",$row_entete["show"]); $libelle=explode("|",$row_entete["libelle"]);
    $intitule=$row_entete["intitule"]; $colonne=$row_entete["colonnes"]; $lignetotal=$row_entete["lignetotal"]; $colnum=$row_entete["colnum"]; $detail_sexe=$row_entete["detail_sexe"]; $detail_menage=$row_entete["detail_menage"];
    if(!empty($row_entete["choix"])){ foreach(explode("|",$row_entete["choix"]) as $elem){ if(!empty($elem)){  $a=explode(";",$elem); $choix_array[$a[0]]=""; for($i=1;$i<count($a);$i++){ $choix_array[$a[0]].=(!empty($a[$i]))?$a[$i].";":""; } }   }  } }

    $count = count($libelle)-2;
    $count = explode("=",$libelle[$count]);
    $lib_nom_fich = "";
    if(isset($count[1]))
    $lib_nom_fich = $count[1];
    elseif(isset($count[0]))
    $lib_nom_fich = $count[0];

    if(empty($lib_nom_fich)) $lib_nom_fich = $cp;
    list($z,$lib_nom_fich)=explode("|",$note);

    $query_entete = "DESCRIBE $cp";
    try{
        $entete = $pdar_connexion->prepare($query_entete);
        $entete->execute();
        $row_entete = $entete ->fetchAll();
        $totalRows_entete = $entete->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $num=0;
    if($totalRows_entete>0){ foreach($row_entete as $row_entete1){ if(in_array($row_entete1["Field"],$entete_array)) $num++; } }

    unset($libelle_array);
    foreach($libelle as $a) { $b = explode('=',$a); if(isset($b[0])) $libelle_array[$b[0]]=(isset($b[1]))?$b[1]:"ND"; }
*/

//liste village
$query_liste_com = "SELECT code_departement,nom_departement  FROM departement  order by code_departement asc";
//echo $query_liste_com;
   try{
    $liste_com = $pdar_connexion->prepare($query_liste_com);
    $liste_com->execute();
    $row_liste_com = $liste_com ->fetchAll();
    $totalRows_liste_com = $liste_com->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$departement_array =$commune_array = array();
if($totalRows_liste_com>0){  foreach($row_liste_com as $row_liste_com){
  $commune_array[$row_liste_com["nom_departement"]] = $row_liste_com["nom_departement"];
  //$departement_array[$row_liste_village["code_commune"]] = $row_liste_village["nom_commune"];
}  }

$query_plus = "";
$query_plus .= (isset($_GET["commune"]) && !empty($_GET["commune"]) && $_GET["commune"]!="%")?" AND col2 in (select nom_commune from departement, commune where departement.code_departement=commune.departement and departement.nom_departement LIKE ".GetSQLValueString("%".$_GET["commune"]."%", "text").")":"";
//$query_plus .= (isset($_GET["structure"]) && !empty($_GET["structure"]) && $_GET["structure"]!="%")?" AND `".$beneficaire_data_array[$feuille]["structure"]."`=".GetSQLValueString($_GET["structure"], "text"):"";

if(isset($_GET["print"]) && intval($_GET["print"])>0)
$query_act = "SELECT * FROM t_1646217521 WHERE Id=".GetSQLValueString($_GET["print"], "text")." $query_plus";
else
{
if(isset($_GET["commune"]))  $query_act = "SELECT t_1646217521.*, code_commune, nom_commune, departement FROM t_1646217521, commune where nom_commune=col2 $query_plus";
else  $query_act = "SELECT t_1646217521.*, code_commune, nom_commune, departement FROM t_1646217521, commune where nom_commune=col2 and col2=-1";
//.((isset($_SESSION["clp_projet_sigle"]) && !empty($_SESSION["clp_projet_sigle"]))?"AND note=".GetSQLValueString($_SESSION["clp_projet_sigle"], "text"):"");// and projet='".$_SESSION["clp_projet"]."'";
}
//echo $query_act; exit;
try{
    $act = $pdar_connexion->prepare($query_act);
    $act->execute();
    $row_act = $act ->fetchAll();
    $totalRows_act = $act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print $config->sitename;?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone;?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="keywords" content="<?php print $config->MetaKeys;?>" />
  <meta name="description" content="<?php print $config->MetaDesc;?>" />
  <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
  <meta name="author" content="<?php print $config->MetaAuthor;?>" />
  <!--<meta charset="utf-8">-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->
  <link href="<?php print $config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder;?>/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder;?>/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder;?>/icons.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder;?>/login.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php print $config->theme_folder;?>/fontawesome/font-awesome.min.css">
  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->theme_folder;?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->
  <!--[if IE 8]><link href="<?php print $config->theme_folder;?>/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->
  <link href='<?php print $config->theme_folder;?>/css.css' rel='stylesheet' type='text/css'>
  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/lodash.compat.min.js"></script>
  <!--[if lt IE 9]><script src="<?php print $config->script_folder;?>/libs/html5shiv.js"></script><![endif]-->
  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>
  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/breakpoints.js"></script>
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
  <script type="text/javascript" src="<?php print $config->script_folder;?>/app.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder;?>/plugins.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder;?>/plugins.form-components.js"></script>
<!--
  <script type="text/javascript" src="<?php print $config->script_folder;?>/custom.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/pages_calendar.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/charts/chart_filled_blue.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/charts/chart_simple.js"></script>-->
 <script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
 <script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder;?>/login.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/ui_general.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/form_validation.js"></script>
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>
</head>
<body>
 <header class="header navbar navbar-fixed-top" role="banner">
    <?php include_once("includes/header.php"); ?>
 </header>
<div id="container">
    <div id="sidebar" class="sidebar-fixed">
        <div id="sidebar-content">
            <?php include_once("includes/menu_top.php"); ?>
        </div>
        <div id="divider" class="resizeable"></div>
    </div>

    <div id="content">
        <div class="container">
            <div class="crumbs">
                <?php include_once("includes/sous_menu.php"); ?>
            </div>
        <div class="page-header">
            <div class="p_top_5">
<!-- Site contenu ici -->
<script>
function printCarte(url) {
    var iframe = document.createElement('iframe');
    iframe.id = 'pdfIframe'
    iframe.className='pdfIframe'
    document.body.appendChild(iframe);
    iframe.style.visibility = "hidden";
    iframe.style.position = "absolute";
    iframe.style.top = "0px";
    iframe.style.left = "0px";
    //iframe.style.display = 'none';
    iframe.onload = function () {
        setTimeout(function () {
            iframe.focus();
            var onPrintFinished=function(printed){/*Terminé*/}
            //iframe.contentWindow.print();
            onPrintFinished(iframe.contentWindow.print());
            URL.revokeObjectURL(url);
        }, 1);
    };
    iframe.src = url;
    // URL.revokeObjectURL(url)
}
</script>
<style>
.title_fiche {
  text-align: left!important;
  height: 25px;
  color: #000000 !important;
}
.value_fiche {
  text-align: left;
}
.graph-image{display: none;}
.pdfIframe{
    visibility: hidden;
    z-index: -1;
    position: absolute;
    top: 0;
    left: 0;
}
.btn-print {
    margin-top: -20px;
}
@media print{
  .graph-image{display:inline;/*height: 70px;*/width: auto;}
  .col-md-4 {
        width: 50%;
        float: left;
        position: relative;
        min-height: 1px;
        padding-left: 15px;
        padding-right: 15px;
    }
    .widget-header1, .widget-header, .graph-image1{
        display: none;
    }
    .widget.box .widget-content{
        padding: 10px 0px;
    }
    .widget-content{
        color: #000000 !important;
        display: block !important;
    }
    .value_fiche {
      padding-left: 10px;
      padding-right: 10px;
    }
    .widget.box.box_projet, .widget.box_projet, .widget.box_projet1 {
        border: none;
    }
    .pagebreak {page-break-after:always;clear: both;}
}
</style>
<div class="widget box">
<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box box_projet1">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php if(!isset($_GET["id"]) || (isset($_GET["id"]) && empty($_GET["id"]))) echo "Les cartes des bénéficiares"; elseif(isset($row_liste_classeur["libelle"])) echo $row_liste_classeur["libelle"]." - ".$nomT." $annee"; else echo "Carte inconnue";?> </h4>
<form name="form<?php echo $annee; ?>" id="form<?php echo $annee; ?>" method="get" action="./carte_beneficaires.php" class="pull-right">
  <select name="commune" class="btn">
  <option value="">Pr&eacute;fecture</option>
   <option value="%">-- Toutes --</option>
  <?php if(count($commune_array)>0){  foreach($commune_array as $nom_com=>$code_com){   ?>
<option value="<?php echo trim($nom_com); ?>" <?php if(isset($_GET["commune"]) && trim($nom_com)==$_GET["commune"]) echo "selected='SELECTED'"; ?>><?php echo $nom_com; ?></option>
  <?php } } ?>

</select>
&nbsp;
<input type="submit" class="btn btn-success pull-right" value="Filtrer" />


</form>
&nbsp;
<a href="javascript:void(0);" onclick="window.print();" title="Tout Imprimer" class="btn-print1 pull-right p11 donot_print"><img class="graph-image1" src="./images/print.png" ></a>
</div>
<div class="widget-content" style="display: block;">
<?php
if($totalRows_act>0) { $i=0; /*$feuille_data = $_GET['feuille'];*/ foreach($row_act as $row_act1){ $id_data = $row_act1['Id'];
if(1==1){
$rmaxcodeind=isset($row_act1['nom_commune'])?$row_act1['nom_commune']:"0";
$code_gen = $row_act1["Id"];


$code_com=isset($row_act1['code_commune'])?$row_act1['code_commune']:"0"; 
$maxcodeind=isset($row_act1['Id'])?$row_act1['Id']:"0"; //$maxcodeind=intval($maxcodeind)+1;

if($maxcodeind<10) $maxcodeind=$code_com."_00".$maxcodeind; elseif($maxcodeind<100) $maxcodeind=$code_com."_0".$maxcodeind; 
elseif($maxcodeind<1000) $maxcodeind=$code_com."_".$maxcodeind; elseif($maxcodeind<10000) $maxcodeind=$code_com."_".$maxcodeind;
//elseif($maxcodeind<100000) $maxcodeind=$code_com."_00".$maxcodeind;
// $a = explode('/',$_POST['date_collecte']); $annee = isset($a[2])?$a[2]:date("Y"); 

  $insertSQL = sprintf("UPDATE t_1646217521 SET col18=%s WHERE Id='$code_gen'", GetSQLValueString($maxcodeind, "text"));
 try{   $Result1 = $pdar_connexion->prepare($insertSQL);  $Result1->execute(); }catch(Exception $e){ die(mysql_error_show_message($e)); }


//echo $query_act; exit;
$code_gen = $maxcodeind; //$row_act1[$beneficaire_data_array[$feuille_data]["identifiant"]];

$PNG_TEMP_DIR = './attachment/beneficiaires_qrcode/';
$filename = $PNG_TEMP_DIR.$id_data.'.png';
if(file_exists($filename))  unlink($filename);
if(!file_exists($filename))
{
    $PNG_WEB_DIR = './attachment/beneficiaires_qrcode/';
    include_once "./plugins/phpqrcode/qrlib.php";
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    $errorCorrectionLevel = 'H';
    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
        $errorCorrectionLevel = $_REQUEST['level'];

    $matrixPointSize = 5;
    if (isset($_REQUEST['size']))
        $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);

    QRcode::png($code_gen, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
}
?>
<div class="col-md-4">
  <div class="statbox widget box box-shadow">
    <div class="widget-content" style="overflow: hidden;height: 317px;"><a href="#">
<?php /*if(isset($row_act1["col21"]) && /*file_exists*//*!empty($row_act1["col21"])) { ?>
          <img class="visual" src="<?php echo $row_act1["col21"]; ?>" width='20' height='20' alt='preview'>
<?php }else{*/ ?>
      <div class="visual cyan" style="<?php echo (isset($row_act1["col21"]) && file_exists("./fiches/pieces/".$row_act1["col21"]))?"padding:0px;":""; ?>"><img src="<?php  echo (isset($row_act1["col12"]) && file_exists("./fiches/pieces/".$row_act1["col21"]))?"./fiches/pieces/".$row_act1["col12"]:"./images/avatar/none.png"; ?>" width='<?php  echo (isset($row_act1["col21"]) && file_exists("./fiches/pieces/".$row_act1["col12"]))?50:50; ?>' height='<?php  echo (isset($row_act1["col12"]) && file_exists("./fiches/pieces/".$row_act1["col12"]))?50:50; ?>' alt='preview'></div>
        <div class=""><?php echo (isset($row_act1["col5"]))?$row_act1["col5"]:""; //echo (isset($row_act1["col4"]))?" ".$row_act1["col4"]:""; ?></div>
      <?php //} ?></a>
      <a href="javascript:void(0);" onclick="printCarte('./carte_beneficaires.php?<?php echo "annee=$annee&print=".$row_act1['Id']; ?>');" title="Imprimer" class="btn-print pull-right donot_print"><img class="graph-image1" src="./images/print.png" ></a>
      <div class="title_fiche">Village<?php echo (isset($row_act1["col4"]))?" ".$row_act1["col4"]."":""; ?><br /></div>
      <div class="value_fiche"><font size="1">Identifiant : </font><span style="color:#CC0000; font-weight:bold"><?php echo $code_gen; ?></span></div>
      <div class="value_fiche" style="clear: both;"><font size="1">Commune : </font><span style="font-weight:bold"><?php echo (isset($row_act1["nom_commune"]))?$row_act1["nom_commune"]:""; ?></span></div>
      <div class="value_fiche" style="clear: both;"><font size="1">Contact : </font><span style="font-weight:bold"><?php echo (isset($row_act1["col9"]))?$row_act1["col9"]:""; ?></span></div>
      <div class="value_fiche" style="clear: both;text-align: center;" align="center">
        <div style="height: 200px;<?php if(file_exists($PNG_TEMP_DIR.basename($filename))) echo "background-image: url('".$PNG_TEMP_DIR.basename($filename)."');background-position: center;background-size: contain;background-repeat: no-repeat;"; else echo "background-color:#000"; ?>"><img class="graph-image" src="<?php echo $PNG_TEMP_DIR.basename($filename); ?>" width='200' height='200' alt='preview' align="center"></div>
      </div>
    </div>
  </div>
</div>
<?php $i++; if($i>0 && $i%6==0) echo "<div class='pagebreak h0'>&nbsp;</div>"; } } } else { echo "<h1 align='center'>Aucune carte dans ce classeur/fiche !</h1>"; } ?>
<div class="clear h0">&nbsp;</div>
</div>
</div></div>
<!-- Fin Site contenu ici -->
            </div>
        </div>
        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>