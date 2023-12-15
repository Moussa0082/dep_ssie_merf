<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & DÃ©veloppement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
$personnel = $_SESSION["clp_id"];
$date = date("Y-m-d"); $annee = date('Y');
if(isset($_GET['tab'])) $tab=$_GET['tab']; else $tab=0;
//categorie de marches
if (isset($_GET["id_sup"])) {
  $id = $_GET["id_sup"];
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."acteur WHERE id_partenaire=%s",
                       GetSQLValueString($id, "text"));

 try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $personnel=$_SESSION['clp_id'];
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."acteur (map_partenaire, adresse_partenaire, type_partenaire, contact_partenaire, site_web, email_partenaire, description, code_partenaire, nom_partenaire, id_personnel) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, '$personnel')",
                        GetSQLValueString($_POST['map_partenaire'], "text"),
					    GetSQLValueString($_POST['adresse_partenaire'], "text"),
                        GetSQLValueString($_POST['type_partenaire'], "int"),
                        GetSQLValueString($_POST["contact_partenaire"], "text"),
						GetSQLValueString($_POST["site_web"], "text"),
						GetSQLValueString($_POST["email_partenaire"], "text"),
  					    GetSQLValueString($_POST['description'], "text"),
                        GetSQLValueString($_POST['code'], "text"),
  					    GetSQLValueString($_POST['nom'], "text"));

 try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
    if($Result1) $insertGoTo = $page."?insert=ok";
    else $insertGoTo = $page."&insert=no";
    //$insertGoTo .= (isset($_POST['categorie']))?"&categorie=".$_POST['categorie']:"";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
      $id = $_POST["MM_delete"];
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."acteur WHERE id_partenaire=%s",
                           GetSQLValueString($id, "text"));

 try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
        $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];  $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."acteur SET map_partenaire=%s, adresse_partenaire=%s, type_partenaire=%s, contact_partenaire=%s, site_web=%s, email_partenaire=%s,  description=%s, code_partenaire=%s, nom_partenaire=%s, modifier_par='$personnel', modifier_le='$date' WHERE id_partenaire=%s",
                        GetSQLValueString($_POST['map_partenaire'], "text"),
                        GetSQLValueString($_POST['adresse_partenaire'], "text"),
                        GetSQLValueString($_POST['type_partenaire'], "int"),
                        GetSQLValueString($_POST["contact_partenaire"], "text"),
					   	GetSQLValueString($_POST["site_web"], "text"),
						GetSQLValueString($_POST["email_partenaire"], "text"),
  					    GetSQLValueString($_POST['description'], "text"),
                        GetSQLValueString($_POST['code'], "text"),
  					    GetSQLValueString($_POST['nom'], "text"),
                        GetSQLValueString($id, "text"));

 try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
    if($Result1) $insertGoTo = $page."?update=ok";
    else $insertGoTo = $page."&update=no";
  //$insertGoTo .= (isset($_POST['categorie']))?"&categorie=".$_POST['categorie']:"";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
$query_liste_type = "SELECT * FROM ".$database_connect_prefix."type_partenaire ORDER BY id_type_partenaire asc";
try{
    $liste_type = $pdar_connexion->prepare($query_liste_type);
    $liste_type->execute();
    $row_liste_type = $liste_type ->fetchAll();
    $totalRows_liste_type = $liste_type->rowCount();
	}catch(Exception $e){ die(mysql_error_show_message($e)); }
	$type_part_array = array();
if($totalRows_liste_type>0){
foreach($row_liste_type as $row_liste_type){ 
 $type_part_array[$row_liste_type["id_type_partenaire"]] = $row_liste_type["nom_type_partenaire"]; $type_part[$row_liste_type["id_type_partenaire"]] = $row_liste_type["nom_type_partenaire"]; } }
//$tab_array = array("Secteurs"=>"sous_secteur_content.php","Sous/secteurs"=>"domaine_content.php")
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
  <link href="<?php print $config->theme_folder; ?>/plugins/select2.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins/wysiwyg-color.css" rel="stylesheet" type="text/css"/>
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
 <script type="text/javascript" src="plugins/bootstrap-wysihtml5/wysihtml5.min.js"></script>
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>
</head>
<body>
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
<script>     /*
$(".tab-pane").slimscroll({
                        height: "100%",
                        wheelStep: 7
                    });  */
function show_tab(tab) {
    if (tab.html()) {
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
$(function () {
    init_tabs();
});
</script>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
</style>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Acteurs du MERF </h4>

</div>
<div class="widget-content">
<div class="tabbable tabbable-custom" >
  <ul class="nav nav-tabs" >
  <?php $j=0; foreach($type_part_array as $a=>$b){ ?>
    <li title="" class="<?php echo ($j==$tab)?"active":""; ?>"><a href="#tab_feed_<?php echo $j; ?>" data-toggle="tab"><?php echo $b; ?></a></li>
  <?php $j++; } ?>
  </ul>
  <div class="tab-content">
  <?php $j=0; foreach($type_part_array as $a=>$b){ ?>
  <div class="tab-pane <?php echo ($j==$tab)?"active":""; ?>" id="tab_feed_<?php echo $j; ?>" data-target="./partenaire_content.php?type=<?php echo $a; ?>" >
  </div>
  <?php $j++; } ?>
  </div>
</div>
</div></div>
</div>
<!-- Fin Site contenu ici -->
            </div>

        </div>

        </div>

        </div>

    </div> <?php include_once 'modal_add.php'; ?>

    <?php include_once ("includes/footer.php");?>

</div>
</body>
</html>