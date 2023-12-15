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

include_once $config->sys_folder . "/database/db_connexion.php";

?>



<?php

$plog=$_SESSION["clp_id"];

$date=date("Y-m-d");

//if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else $annee=date("Y");
//if(isset($_GET['version'])) {$version=intval($_GET['version']);} else $version=1;
/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_version = "SELECT * FROM ".$database_connect_prefix."version_ptba ORDER BY date_validation asc";
$liste_version  = mysql_query_ruche($query_liste_version , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_version  = mysql_fetch_assoc($liste_version);
$totalRows_liste_version  = mysql_num_rows($liste_version);
$TableauVersionP = array(); $version_array = array();
if($totalRows_liste_version>0){ do{
$max_version=$row_liste_version["id_version_ptba"];
$TableauVersionP[]=$row_liste_version["id_version_ptba"]."<>".$row_liste_version["version_ptba"]."<>".$row_liste_version["annee_ptba"];
$version_array[$row_liste_version["version_ptba"]] = $row_liste_version["id_version_ptba"];
 }
while($row_liste_version  = mysql_fetch_assoc($liste_version)); }


if(isset($_GET['version'])) {$version=$_GET['version'];} elseif($totalRows_liste_version>0) $version=$max_version; else  $version=1;*/

$cmp ="";
if(isset($_GET['cmp']) && !empty($_GET['cmp'])) $cmp = $_GET['cmp'];

  $query_liste_region= "SELECT code_ugl, nom_ugl FROM ".$database_connect_prefix."ugl order by code_ugl";
  try{
    $liste_region = $pdar_connexion->prepare($query_liste_region);
    $liste_region->execute();
    $row_liste_region = $liste_region ->fetchAll();
    $totalRows_liste_region = $liste_region->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $tableauRegion=array();
 if($totalRows_liste_region>0) { foreach($row_liste_region as $row_liste_region){  
$tableauRegion[$row_liste_region["code_ugl"]]=$row_liste_region["nom_ugl"]; } }
 

$query_liste_version = "SELECT * FROM ".$database_connect_prefix."version_ptba  ORDER BY date_validation asc";
try{
    $liste_version = $pdar_connexion->prepare($query_liste_version);
    $liste_version->execute();
    $row_liste_version = $liste_version ->fetchAll();
    $totalRows_liste_version = $liste_version->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$TableauVersionP = array(); $version_array = array();
 if($totalRows_liste_version>0) { foreach($row_liste_version as $row_liste_version){  
$max_version=$row_liste_version["id_version_ptba"];
 if($row_liste_version["version_ptba"]==1) $row_liste_version["version_ptba"]="Initiale"; elseif($row_liste_version["version_ptba"]==2) $row_liste_version["version_ptba"]="R&eacute;vis&eacute;e";
$TableauVersionP[]=$row_liste_version["id_version_ptba"]."<>".$row_liste_version["version_ptba"]."<>".$row_liste_version["annee_ptba"];
$version_array[$row_liste_version["version_ptba"]] = $row_liste_version["id_version_ptba"];
 } }


if(isset($_GET['annee'])) {$annee=$_GET['annee'];} elseif($totalRows_liste_version>0) $annee=$max_version; else  $annee=1;


//echo $version;
//exit;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

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

 <style type="text/css">

<!--

#demo-container{padding:2px 15px 2 15px;/*background:#67A897;*/}

ul#simple-menu{list-style-type:none;width:100%;position:relative;height:20px;font-family:"Trebuchet MS",Arial,sans-serif;font-size:13px;font-weight:bold;margin:0;padding:0;}

ul#simple-menu li{display:block;float:left;margin:0 0 0 4px;height:20px;}

ul#simple-menu li.left{margin:0;}

ul#simple-menu li a{display:block;float:left;color:#fff;background:#4A6867;text-decoration:none;padding:3px 18px;}

ul#simple-menu li a.right{padding-right:19px;}

ul#simple-menu li a:hover{background:#2E4560;}

ul#simple-menu li a.current{color:#FFF;background:#ff0000;}

ul#simple-menu li a.current:hover{color:#FFF;background:#ff0000;}

-->

</style>

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

$().ready(function() {

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

<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i> Suivi du PTBA</h4>
</div>



<div class="widget-content" style="display: block;">



<div class="tabbable tabbable-custom" >

  <ul class="nav nav-tabs" >

  
  
     <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>
   <li title="" class="<?php echo ($aversionP[0]==$annee)?"active":""; ?>"><a href="#tab_feed_<?php echo $aversionP[0]; ?>" data-toggle="tab"><?php echo $aversionP[2]." ".$aversionP[1]; ?></a></li>
              <?php }  ?>

  </ul>

  <div class="tab-content">

 
  
    <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>
   <div class="tab-pane <?php echo ($aversionP[0]==$annee)?"active":""; ?>" id="tab_feed_<?php echo $aversionP[0]; ?>" data-target="./suivi_indicateur_ptba_content.php?cmp=<?php echo $cmp; ?>&annee=<?php echo $aversionP[0]."&version=".$aversionP[1]; ?>"></div>
	          <?php } //echo $version; exit; ?>

  </div>

</div>



</div>



</div></div>



<!-- Fin Site contenu ici -->


        </div>



        </div>

    </div>    <?php include_once 'modal_add.php'; ?>

    <?php include_once("includes/footer.php"); ?>

</div>



</body>

</html>