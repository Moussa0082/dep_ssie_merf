<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";

$dir = './attachment/workflow/';
if(!is_dir($dir)) mkdir($dir);

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

if ((isset($_GET["id_sup"]) && !empty($_GET["id_sup"]))) {
  $id = intval($_GET["id_sup"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_edit_dossier = "SELECT numero FROM ".$database_connect_prefix."workflow WHERE id_dossier='$id'";
  $edit_dossier = mysql_query($query_edit_dossier, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_edit_dossier = mysql_fetch_assoc($edit_dossier);
  $numero = $row_edit_dossier["numero"];

  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."workflow WHERE id_dossier=%s",
                       GetSQLValueString($id, "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  if($Result1)
  {
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."suivi_workflow WHERE numero=%s",
                         GetSQLValueString($numero, "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result2 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  }

  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $personnel=$_SESSION['clp_id']; $date = date("Y-m-d");
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."workflow (nom, numero, type_dossier, expediteur, message, documents, projet, structure, date_dossier, id_personnel) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, '$date', '$personnel')",
                        GetSQLValueString($_POST['nom'], "text"),
  					    GetSQLValueString($_POST['numero'], "text"),
                        GetSQLValueString($_POST['type_dossier'], "text"),
                        GetSQLValueString($_POST['destinataire'], "text"),
                        GetSQLValueString($_POST['message'], "text"),
                        GetSQLValueString($_POST['documents'], "text"),
                        GetSQLValueString($_SESSION["clp_projet"], "text") ,
                        GetSQLValueString($_SESSION["clp_structure"], "text"));
                                                            
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    //Traitement de document
    $doc = explode('|',$_POST['documents']); $doc2 = array();
    foreach($doc as $doc1){ if(!empty($doc1)) $doc2[] = $dir.$doc1; }
    $link = implode("|",$doc2);
    //if($Result1) mysql_query("DOC".$link, $pdar_connexion,1);
    //Fin Traitement de document

    $insertGoTo = $_SERVER['PHP_SELF'];
    if($Result1) $insertGoTo = "?insert=ok"; else $insertGoTo = $page."?insert=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
      $id = $_POST["MM_delete"];
      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $query_edit_dossier = "SELECT numero FROM ".$database_connect_prefix."workflow WHERE id_dossier='$id'";
      $edit_dossier = mysql_query($query_edit_dossier, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
      $row_edit_dossier = mysql_fetch_assoc($edit_dossier);
      $numero = $row_edit_dossier["numero"];

      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."workflow WHERE id_dossier=%s",
                           GetSQLValueString($id, "text"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
      if($Result1)
      {
        $insertSQL = sprintf("DELETE from ".$database_connect_prefix."suivi_workflow WHERE numero=%s",
                             GetSQLValueString($numero, "text"));

        mysql_select_db($database_pdar_connexion, $pdar_connexion);
        $Result2 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
      }

      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];  $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."workflow SET nom=%s, numero=%s, type_dossier=%s, expediteur=%s, message=%s, documents=%s, modifier_par='$personnel', modifier_le='$date' WHERE id_dossier=%s",
                        GetSQLValueString($_POST['nom'], "text"),
  					    GetSQLValueString($_POST['numero'], "text"),
                        GetSQLValueString($_POST['type_dossier'], "text"),
                        GetSQLValueString($_POST['destinataire'], "text"),
                        GetSQLValueString($_POST['message'], "text"),
                        GetSQLValueString($_POST['documents'], "text"),
                        GetSQLValueString($id, "int"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    //Traitement de document
    $doc = explode('|',$_POST['documents']); $doc2 = array();
    foreach($doc as $doc1){ if(!empty($doc1)) $doc2[] = $dir.$doc1; }
    $link = implode("|",$doc2);
    //if($Result1) mysql_query("DOC".$link, $pdar_connexion,1);
    //Fin Traitement de document

    $insertGoTo = $_SERVER['PHP_SELF'];
    if($Result1) $insertGoTo = "?update=ok"; else $insertGoTo = $page."?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
</style>
<script>
$().ready(function() {
  <?php if(isset($_GET["show"]) && intval($_GET["show"])>0) echo '$("#msg_00").click();'; ?>
    init_tabs();
});

function show_tab(tab) {
    if (!tab.html()) {
        tab.load(tab.attr('data-target'));
    }
}

function init_tabs() {
    show_tab($('.tab-pane.active'));
    $('a[data-toggle="tab"]').click('show', function(e) {
        tab = $('#' + $(e.target).attr('href').substr(1));
        show_tab(tab);
    });
}
</script>
<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box box_projet">
<div class="widget-header1"> <center><h4><?php
mysql_select_db($database_connect_transfert, $connect_transfert);
$mySqlQuery = "SELECT * FROM ".$database_connect_prefix."ugl where code_ugl='".$_SESSION['clp_structure']."'";
$qh = mysql_query_ruche($mySqlQuery, $connect_transfert) or die(mysql_error_show_message(mysql_error()));
$data = mysql_fetch_assoc($qh);
$totalRows_clp = mysql_num_rows($qh);

if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_sigle"]; if(isset($data["nom_ugl"])) echo "<span style='color:yellow; padding-left:150px'>( ".$data["abrege_ugl"]." )</span>"; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Workflow (Gestion des dossiers) </h4>
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) {
echo do_link("","","Nouveau dossier","Nouveau dossier","","./","pull-right p11","get_content('new_workflow.php','','modal-body_add',this.title,'iframe');",1,"margin:0px 5px;",$nfile);
?>
<?php } ?>
<?php if(isset($_GET["show"]) && intval($_GET["show"])>0)
echo do_link("msg_00","","Contenu du message","Aper&ccedil;u","","./","hidden","get_content('body_workflow.php','id=".intval($_GET["show"]).(isset($_GET["doc"])?"&doc=1":"")."','modal-body_add',this.title,'iframe');",1,"",$nfile)
//echo do_link("","./courrier_dno_archive.php","Archives","Archives","","./","pull-right p11","",0,"margin:0px 5px;",$nfile);
?>
</div>

<div class="widget-content" style="display: block;">

<div class="tabbable tabbable-custom" >
  <ul class="nav nav-tabs" >
    <li title="" class="<?php echo ((!isset($_GET["pane"])) || (isset($_GET["pane"]) && intval($_GET["pane"])==1))?"active":""; ?>"><a href="#tab_feed_1" data-toggle="tab">Dossiers en cours</a></li>
    <li title="" class="<?php echo (isset($_GET["pane"]) && intval($_GET["pane"])==2)?"active":""; ?>"><a href="#tab_feed_2" data-toggle="tab">Dossiers archives</a></li>
  </ul>
  <div class="tab-content">
  <?php $j=1; for($j=1;$j<=2;$j++){ ?>
  <div class="tab-pane <?php echo ((!isset($_GET["pane"]) && $j==1) || (isset($_GET["pane"]) && intval($_GET["pane"])==$j))?"active":""; ?>" id="tab_feed_<?php echo $j; ?>" data-target="<?php echo "./workflow_content.php?pane=$j"; ?>"></div>
  <?php }  ?>
  </div>
</div>

</div>
<!-- Fin Site contenu ici -->
 </div>
            </div></div>
        </div>

        </div>
    </div>    <?php include 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>