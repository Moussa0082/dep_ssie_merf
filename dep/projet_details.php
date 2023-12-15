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
extract($_GET);
if (!isset ($id)) {
        header(sprintf("Location: %s", "./partenaires.php"));  exit();
}
include_once $config->sys_folder . "/database/db_connexion.php";

$personnel = $_SESSION["clp_id"];  ini_set("display_errors",1);
$date = date("Y-m-d");

//projet
$query_projet = $db ->prepare('SELECT T.* FROM projet T WHERE T.id_projet=:id_projet');
$query_projet->execute(array(':id_projet' => $id));
$row_projet = $query_projet ->fetch();
$totalRows_projet = $query_projet->rowCount();

$dir = "./images/projet/";
if(!is_dir($dir)) mkdir($dir);

$onglet_array = array(0=>"Projets en cours",1=>"Projets clôturés");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print $config->sitename;?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone;?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
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
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo /*$row_projet['code_projet']." : ".*/$row_projet['sigle_projet']; ?> </h4>
<?php /*
echo do_link("view_switcher","javascript:void(0);","Affichage Liste/Grille","<span id='view_switcher_span' title='Affichage Liste/Grille' class='glyphicon glyphicon-th'></span>","","./","pull-right p11","$('#view_switcher_span').toggleClass('glyphicon-th');$('#view_switcher_span').toggleClass('glyphicon-th-list');$('.projects$statut').toggle();$('.projects-list$statut').toggle();",0,"",$nfile);
if(isset($_SESSION["clp_niveau"]) && $_SESSION["clp_niveau"]==1) {?>
<?php
echo do_link("","","Ajout de projet","<i class=\"icon-plus\"> Nouveau projet </i>","","./","pull-right p11","get_content('new_projet.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php }*/ ?>
</div>
<div class="widget-content" style="display: block;">
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
</style>

<script>
$().ready(function() {
        //init_tabs();
        //$( "iframe .load" ).hide();
});
</script>

<?php if($totalRows_projet>0){ ?>
        <div class="row">
            <div class="col-md-12">
                <div class="font-bold m-b-sm"><?php echo $row_projet['intitule_projet']; ?><!--</div>
                <div class="hpanel">
                    <div class="panel-body">-->
<script>
function resizeIframe(iframe) {
    var addHeight = 20; //or whatever size is being cut off
    iframe.height = iframe.contentWindow.document.body.scrollHeight + addHeight + "px";
  }
</script>
<div class="iframecontainer">
    <iframe id="iframe" src="https://<?php echo $row_projet['sigle_projet']; ?>.fidamali.org/recap_projet.php" class="iframeclass" width="100%" height="100%" onload="resizeIframe(this);"></iframe>
</div>
<style>
.iframecontainer {
    background-image: url('./images/loading.gif');
    background-position: center;
    background-repeat: no-repeat;
}
.iframecontainer iframe {
    background: transparent;
}
.iframeclass{
    position: absolute;
    top: 0;
    width: 100%;
    border:none;
}

.iframecontainer{
    position: relative;
    width: 100%;
    height: auto;
    padding-top: 61%;
}
</style>
<!--                    </div>
                </div>
            </div>-->
            </div>
<?php } else{ ?>
<div class="col-md-12 col-lg-12">
        <div>
                <div class="panel-heading">
                        <div class="panel-tools">
                                <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                        </div>
                    <span class="text-primary"><i class="fa fa-reorder"></i> Projet détail</span>
                </div>
                <div class="panel-body">
                        <h1 align="center">Aucun projet selectionnée !</h1>
                </div>
        </div>
</div>
<?php } ?>

<div class="clear h0">&nbsp;</div>
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