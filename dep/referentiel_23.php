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

if(isset($_GET['composante']) && $_GET['composante']!="") {$_SESSION["composante"]=$_GET['composante']; $composante=$_GET['composante']; $filiere=$_SESSION["composante"];} else {$filiere=0; $composante=0; $_GET['composante']=0;}
if(isset($_GET['composante']) && $_GET['composante']==""){ $_GET['composante']=""; unset($_SESSION["composante"]); $composante=0; }
$where = ($filiere==0)?"":" and composante = ".$filiere." ";
$where .= ($composante==0)?"":" and id_composante=".$composante." ";
if(isset($_GET["sc"])) $sc = $_GET["sc"]; else $sc = 0;
$editFormAction = $_SERVER['PHP_SELF'];
$currentPage = $_SERVER['PHP_SELF']."?composante=$filiere&sc=$sc";
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
$query_sup_activite = "DELETE FROM referentiel_indicateur WHERE id_ref_ind='$id'";
try{
    $Result1 = $pdar_connexion->prepare($query_sup_activite);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok&composante=$composante"; else $insertGoTo .= "?del=no&composante=$composante";
  $insertGoTo .= (isset($_GET['prefecture']))?"&prefecture=".$_GET['prefecture']:"";
  $insertGoTo .= (isset($_POST['sc']))?"&sc=".$_POST['sc']:"";
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
   $personnel=$_SESSION['clp_id'];
    $insertSQL = sprintf("INSERT INTO referentiel_indicateur (intitule_ref_ind,code_ref_ind, type_ref_ind,unite,mode_calcul,mode_suivi,beneficiaire,categorie, id_personnel) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, '$personnel')",
            GetSQLValueString($_POST['intitule_ref_ind'], "text"),
            // GetSQLValueString($_POST['composante'], "text"),
            GetSQLValueString($_POST['code_ref_ind'], "text"),
            GetSQLValueString($_POST['type_ref_ind'], "int"),
            GetSQLValueString($_POST['unite'], "text"),
            GetSQLValueString($_POST['mode_calcul'], "text"),
            GetSQLValueString($_POST['mode_suivi'], "int"),
            GetSQLValueString($_POST['beneficiaire'], "int"),
            GetSQLValueString($_POST['categorie'], "int"));
    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok&composante=$filiere"; else $insertGoTo .= "?insert=no&composante=$filiere";
    $insertGoTo .= (isset($_POST['prefecture']))?"&prefecture=".$_POST['prefecture']:"";
    $insertGoTo .= (isset($_POST['sc']))?"&sc=".$_POST['sc']:"";
    header(sprintf("Location: %s", $insertGoTo));
  }
  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id'];
  	$insertSQL = sprintf("UPDATE referentiel_indicateur SET intitule_ref_ind=%s, code_ref_ind=%s, type_ref_ind=%s, unite=%s,mode_calcul=%s,mode_suivi=%s,beneficiaire=%s,categorie=%s, modifier_par='$personnel', modifier_le='$date' WHERE id_ref_ind='$c'",
            GetSQLValueString($_POST['intitule_ref_ind'], "text"),
            // GetSQLValueString($_POST['composante'], "text"),
            GetSQLValueString($_POST['code_ref_ind'], "text"),
            GetSQLValueString($_POST['type_ref_ind'], "int"),
            GetSQLValueString($_POST['unite'], "text"),
            GetSQLValueString($_POST['mode_calcul'], "text"),
            GetSQLValueString($_POST['mode_suivi'], "int"),
            GetSQLValueString($_POST['beneficiaire'], "int"),
            GetSQLValueString($_POST['categorie'], "int"));
    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok&composante=$filiere"; else $insertGoTo .= "?update=no&composante=$filiere";
    $insertGoTo .= (isset($_POST['composante']))?"&composante=".$_POST['composante']:"";
    $insertGoTo .= (isset($_POST['sc']))?"&sc=".$_POST['sc']:"";
    header(sprintf("Location: %s", $insertGoTo));
  }
  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
      $id = intval($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from referentiel_indicateur WHERE id_ref_ind=%s",
                           GetSQLValueString($id, "int"));
      try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
      }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form5"))
{
   //insertion indicateur
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $idsy = $_POST['id'];
  $query_sup_ind = "DELETE FROM calcul_indicateur_simple_ref WHERE indicateur_ref='$idsy'";
  try{
        $Result1 = $pdar_connexion->prepare($query_sup_ind);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $indicateur_simple="";
  if(!empty($_POST['indicateur_simple'])) { foreach($_POST['indicateur_simple'] as $vindicateur_simple) { $indicateur_simple=$indicateur_simple.",".$vindicateur_simple; } }
    $insertSQL = sprintf("INSERT INTO calcul_indicateur_simple_ref (indicateur_ref, formule_indicateur_simple, indicateur_simple, id_personnel, date_enregistrement) VALUES (%s, %s, %s,'$personnel', '$date')",
            GetSQLValueString($idsy, "int"),
            GetSQLValueString($_POST['formule_indicateur_simple'], "text"),
            GetSQLValueString($indicateur_simple, "text"));
    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok";  else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
    }
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form6"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert") && isset($_POST['denominateur']) && isset($_POST['numerateur'])) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $idsy = $_POST['id'];
  $query_sup_ind = "DELETE FROM ratio_indicateur_ref WHERE indicateur_ref='$idsy'";
  try{
        $Result1 = $pdar_connexion->prepare($query_sup_ind);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertSQL = sprintf("INSERT INTO ratio_indicateur_ref (indicateur_ref, numerateur, denominateur, coefficient, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s,'$personnel', '$date')",
            GetSQLValueString($idsy, "int"),
            GetSQLValueString($_POST['numerateur'], "text"),
            GetSQLValueString($_POST['denominateur'], "text"),
            GetSQLValueString($_POST['coefficient'], "text"));
    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok$param_url";  else $insertGoTo .= "?insert=no$param_url";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1f"))
{
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $idsy = $_POST['idref'];
  	$insertSQL = sprintf("UPDATE referentiel_indicateur SET classeur=%s, feuille=%s, colonne=%s, mode_calcul_fiche=%s, critere=%s, modifier_fiche='$personnel', modifier_fiche_date='$date' WHERE id_ref_ind='$idsy'",
        GetSQLValueString($_POST['classeur'], "int"),
        GetSQLValueString($_POST['feuille'], "text"),
        GetSQLValueString($_POST['colonne'], "text"),
        GetSQLValueString($_POST['mode_calcul'], "text"),
        GetSQLValueString($_POST['critere'], "text"));
  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok$param_url";  else $insertGoTo .= "?insert=no$param_url";
    header(sprintf("Location: %s", $insertGoTo)); exit();
}
$query_liste_composante = "SELECT * FROM  referentiel_indicateur  ORDER BY type_ref_ind, code_ref_ind";
try{
    $liste_composante = $pdar_connexion->prepare($query_liste_composante);
    $liste_composante->execute();
    $row_liste_composante = $liste_composante ->fetchAll();
    $totalRows_liste_composante = $liste_composante->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_indicateur_calcul = "SELECT indicateur_ref, id_ref_ind, code_ref_ind, intitule_ref_ind FROM referentiel_indicateur, calcul_indicateur_simple_ref
WHERE FIND_IN_SET( id_ref_ind, indicateur_simple ) and mode_calcul = 'Unique' ORDER BY indicateur_ref";
try{
    $liste_indicateur_calcul = $pdar_connexion->prepare($query_liste_indicateur_calcul);
    $liste_indicateur_calcul->execute();
    $row_liste_indicateur_calcul = $liste_indicateur_calcul ->fetchAll();
    $totalRows_liste_indicateur_calcul = $liste_indicateur_calcul->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_indicateur_simple_array=array();
if($totalRows_liste_indicateur_calcul>0){
foreach($row_liste_indicateur_calcul as $row_liste_indicateur_calcul){ $liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']]=(isset($liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']]))?$liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']].$row_liste_indicateur_calcul['code_ref_ind'].",":$row_liste_indicateur_calcul['code_ref_ind'].",";} }

$query_liste_ind_ratio = "SELECT indicateur_ref, numerateur, denominateur, coefficient FROM ratio_indicateur_ref order by indicateur_ref";
try{
    $liste_ind_ratio = $pdar_connexion->prepare($query_liste_ind_ratio);
    $liste_ind_ratio->execute();
    $row_liste_ind_ratio = $liste_ind_ratio ->fetchAll();
    $totalRows_liste_ind_ratio = $liste_ind_ratio->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_num_ratio_array = array();
$liste_deno_ratio_array = array();
foreach($row_liste_ind_ratio as $row_liste_ind_ratio){
 $liste_num_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = $row_liste_ind_ratio["numerateur"];
  $liste_deno_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = ($row_liste_ind_ratio["denominateur"]==-1)?$row_liste_ind_ratio["coefficient"]." / 1)":$row_liste_ind_ratio["denominateur"];
}

$query_liste_code_ref = "SELECT code_ref_ind, id_ref_ind FROM referentiel_indicateur order by code_ref_ind";
try{
    $liste_code_ref = $pdar_connexion->prepare($query_liste_code_ref);
    $liste_code_ref->execute();
    $row_liste_code_ref = $liste_code_ref ->fetchAll();
    $totalRows_liste_code_ref = $liste_code_ref->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_code_ref_array = array();
foreach($row_liste_code_ref as $row_liste_code_ref){
 $liste_code_ref_array[$row_liste_code_ref["id_ref_ind"]] = $row_liste_code_ref["code_ref_ind"];
}

/*$query_edit_composante = "SELECT * FROM activite_projet WHERE niveau=1 order by code";
try{
    $edit_composante = $pdar_connexion->prepare($query_edit_composante);
    $edit_composante->execute();
    $row_edit_composante = $edit_composante ->fetchAll();
    $totalRows_edit_composante = $edit_composante->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_edit_nom = "SELECT * FROM activite_projet WHERE niveau=1 order by code";
try{
    $edit_nom = $pdar_connexion->prepare($query_edit_nom);
    $edit_nom->execute();
    $row_edit_nom = $edit_nom ->fetchAll();
    $totalRows_edit_nom = $edit_nom->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$composante_array = array(); $composante_code_array = array();
if($totalRows_edit_nom>0){
foreach($row_edit_nom as $row_edit_nom){
$composante_array[$row_edit_nom["code"]]=$row_edit_nom["intitule"];
$composante_code_array[$row_edit_nom["code"]]=$row_edit_nom["code"];
} }*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print $config->sitename;?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone;?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
 <script>$(document).ready(function(){Login.init()});</script>
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
$(document).ready(function() {
      /*
     * Insert a 'details' column to the table
     */
    /*var nCloneTh = document.createElement( 'th' );
    var nCloneTd = document.createElement( 'td' );
    nCloneTd.innerHTML = '<img src="./images/plus.gif">';
    nCloneTd.className = "center";
    $('#mytable thead tr').each( function () {
        this.insertBefore( nCloneTh, this.childNodes[0] );
    } );
    $('#mytable tbody tr').each( function () {
        this.insertBefore(  nCloneTd.cloneNode( true ), this.childNodes[0] );
    } );  */

var oTable = $('#mytable').dataTable();
//Delete the datable object first
if(oTable != null)oTable.fnDestroy();
//Remove all the DOM elements
//$('#mytable').empty();
var oTable = $('#mytable').dataTable( {
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ -1 ] }
        ],
        sDom:"<'row'<'dataTables_header clearfix'<'col-md-7'lT><'col-md-5'Cf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
        oTableTools:{aButtons:["copy","print","csv","xls",{"sExtends": "pdf","sPdfOrientation": "landscape"}],sSwfPath:"./swf/copy_csv_xls_pdf.swf"},
        "aaSorting": [],
        //"aLengthMenu":[[25, 50, 100, 200, -1],[25, 50, 100, 200, "TOUS1"]],
        "iDisplayLength": -1,
        paging: false
    });
/* Formating function for row details */
function fnFormatDetails ( oTable, nTr )
{
    var aData = oTable.fnGetData( nTr );
    return aData[0];
}
                       //img[id="plus"]
$('#mytable tbody td').on('click', function () {
        var nTr = $(this).parents('tr')[0];
        if ( oTable.fnIsOpen(nTr) )
        {
            /* This row is already open - close it */
            //this.src = "./images/plus.png";
            oTable.fnClose( nTr );
        }
        else
        {
            /* Open this row */
            //this.src = "./images/moins.png";
            oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'details' );
        }
    } );
} );
/*]]>*/
</script>
</head>
<body>
 <header class="header navbar navbar-fixed-top" role="banner">
    <?php include_once ("includes/header.php");?>
 </header>
<div id="container">
    <div id="sidebar" class="sidebar-fixed">
        <div id="sidebar-content">
            <?php include_once ("includes/menu_top.php");?>
        </div>
        <div id="divider" class="resizeable"></div>
    </div>

    <div id="content">
        <div class="container">
            <div class="crumbs">
                <?php include_once ("includes/sous_menu.php");?>
            </div>
        <div class="page-header">
            <div class="p_top_5">
<!-- Site contenu ici -->
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
</style>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> R&eacute;f&eacute;rentiel </h4>
    <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2){?>
<!--<a onclick="get_content('new_indicateur_ref.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Ajouter un indicateur;" class="pull-right p11"><i class="icon-plus"> Ajouter un indicateur </i></a>
<a onclick="get_content('new_composante.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Ajouter un m&eacute;tier" class="pull-right p11"><i class="icon-plus"> Ajouter un m&eacute;tier </i></a>
<a onclick="get_content('new_filiere.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Ajouter une fili&egrave;re" class="pull-right p11"><i class="icon-plus"> Ajouter une fili&egrave;re </i></a>-->
<a onclick="get_content('new_indicateur_ref.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Ajouter un indicateur r&eacute;f&eacute;rentiel" class="pull-right p11"><i class="icon-plus"> Ajouter un indicateur </i></a>
<?php include_once 'modal_add.php'; ?>
    <?php } ?>
</div>
<div class="widget-content">
<table id="mytable" class="table table-striped table-bordered table-hover table-responsive table-tabletools table-colvis datatable dataTable hide_befor_load" id="DataTables_Table" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Code</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Indicateur </th>
<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">composante</th>-->
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Type</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Unit&eacute; </th>
<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Type</th>-->
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Suivi par</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><div align="center">Mode</div></th>
<th  class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><div align="center">Fiche</div></th>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="90">Actions</th>
<?php } ?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php if($totalRows_liste_composante>0) { $i=0; foreach($row_liste_composante as $row_liste_composante) { if(isset($row_liste_composante['id_ref_ind']) && !empty($row_liste_composante['id_ref_ind']))$id = $row_liste_composante['id_ref_ind']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo $row_liste_composante['code_ref_ind']; ?></td>
<td class=" "><?php echo $row_liste_composante['intitule_ref_ind']; ?></td>
<td class=" "><?php if(isset($row_liste_composante['type_ref_ind']) &&  $row_liste_composante['type_ref_ind']==1) echo "produit"; elseif(isset($row_liste_composante['type_ref_ind']) &&  $row_liste_composante['type_ref_ind']==2) echo "Effet"; elseif(isset($row_liste_composante['type_ref_ind']) &&  $row_liste_composante['type_ref_ind']==3) echo "Impact"; ?></td>
<td class=" "><?php echo $row_liste_composante['unite']; ?></td>
<!--<td class=" "><?php if(isset($row_liste_composante['type_ref_ind']) &&  $row_liste_composante['type_ref_ind']==1) echo "produit"; elseif(isset($row_liste_composante['type_ref_ind']) &&  $row_liste_composante['type_ref_ind']==2) echo "Effet"; elseif(isset($row_liste_composante['type_ref_ind']) &&  $row_liste_composante['type_ref_ind']==3) echo "Impact"; ?></td>-->
<td class=" "><?php  if(isset($row_liste_composante['mode_suivi']) &&  $row_liste_composante['mode_suivi']==1) echo "Fiches de collecte";  elseif(isset($row_liste_composante['mode_suivi']) &&  $row_liste_composante['mode_suivi']==2) echo "Rapports"; else "ND"; ?></td>
<td class=" " align="center"><?php if(isset($row_liste_composante['mode_calcul']) && $row_liste_composante['mode_calcul']=="Unique") { echo "Unique"; } elseif (isset($row_liste_composante['mode_calcul']) && $row_liste_composante['mode_calcul']=="Ratio") {?>
<a onclick="get_content('edit_ratio_indicateur_ref.php','iden=<?php echo $row_liste_composante['id_ref_ind']."&type_ind=".$row_liste_composante['type_ref_ind']; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Elements de calcul d'indicateur " class="thickbox Add"  dir="">
<?php echo $row_liste_composante['mode_calcul'];
					  if(isset($liste_num_ratio_array[$row_liste_composante['id_ref_ind']])
					  && isset($liste_deno_ratio_array[$row_liste_composante['id_ref_ind']])
					  && isset($liste_code_ref_array[$liste_num_ratio_array[$row_liste_composante['id_ref_ind']]])
					  && isset($liste_code_ref_array[$liste_deno_ratio_array[$row_liste_composante['id_ref_ind']]]))
					   echo " (".$liste_code_ref_array[$liste_num_ratio_array[$row_liste_composante['id_ref_ind']]]." / ".$liste_code_ref_array[$liste_deno_ratio_array[$row_liste_composante['id_ref_ind']]].")"; elseif(isset($liste_num_ratio_array[$row_liste_composante['id_ref_ind']])
					  && isset($liste_code_ref_array[$liste_num_ratio_array[$row_liste_composante['id_ref_ind']]])) echo " (".$liste_code_ref_array[$liste_num_ratio_array[$row_liste_composante['id_ref_ind']]]."*".$liste_deno_ratio_array[$row_liste_composante['id_ref_ind']]; ?></a>
                     <?php } else {?>
                     <a onclick="get_content('edit_calcul_indicateur_ref.php','iden=<?php echo $row_liste_composante['id_ref_ind']."&type_ind=".$row_liste_composante['type_ref_ind']; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Elements de calcul d'indicateur " class="thickbox Add"  dir=""><?php echo $row_liste_composante['mode_calcul']; ?>
					 <?php if(isset($liste_indicateur_simple_array[$row_liste_composante['id_ref_ind']])) echo " (".substr($liste_indicateur_simple_array[$row_liste_composante['id_ref_ind']],0,strlen($liste_indicateur_simple_array[$row_liste_composante['id_ref_ind']])-1).")"; ?></a>
                     <?php }?></td>
<td class=" " align="center" ><?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1 && $row_liste_composante['mode_calcul']=="Unique" && $row_liste_composante['mode_suivi']==1){ ?>D&eacute;tails<?php //echo do_link("","./referentiel_feuille.php?idref=$id","Liaison des fiches de collecte","D&eacute;tails","","./","pull-right p11","",0,"",$nfile); ?><?php } ?></td>

<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
<td class=" " align="center">
<?php
echo do_link("","","Modifier Indicateur ".$row_liste_composante['intitule_ref_ind'],"","edit","./","","get_content('new_indicateur_ref.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);
echo do_link("",$_SERVER['PHP_SELF']."?id_sup=$id","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cet indicateur ?');",0,"margin:0px 5px;",$nfile);
?>
</td>
</tr>
<?php } } } ?>
</tbody></table>
    </div>
</div>
<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>
    <?php include_once ("includes/footer.php");?>
</div>
</body>
</html>