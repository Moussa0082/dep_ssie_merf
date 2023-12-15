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

//categorie de marches



/*if(isset($_GET['niveau']) && $_GET['niveau']!="") {$_SESSION["niveau"]=$_GET['niveau']; $niveau=$_SESSION["niveau"];} else { $_SESSION["niveau"]=$niveau=0; }

$where = (!isset($niveau) || $niveau==0)?" niveau =1":" niveau = ".$niveau." ";

if(isset($_GET['cmp']) && $_GET['cmp']!="") $wh = " and code=".GetSQLValueString($_GET['cmp'], "text"); else $wh = "";



//Liste catÃ©gorie

mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_categorie = "SELECT * FROM ".$database_connect_prefix."categorie_marche ORDER BY nom_categorie asc";

$liste_categorie  = mysql_query_ruche($query_liste_categorie , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_liste_categorie  = mysql_fetch_assoc($liste_categorie);

$totalRows_liste_categorie  = mysql_num_rows($liste_categorie);



//liste methode

mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_convention = "SELECT * FROM ".$database_connect_prefix."type_part WHERE  ".$_SESSION["clp_where"]." ";

$liste_convention  = mysql_query_ruche($query_liste_convention , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_liste_convention  = mysql_fetch_assoc($liste_convention);

$totalRows_liste_convention  = mysql_num_rows($liste_convention);



//liste Etpas passation

mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_etape = "SELECT * FROM ".$database_connect_prefix."etape_marche ORDER BY code asc";

$liste_etape  = mysql_query_ruche($query_liste_etape , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_liste_etape  = mysql_fetch_assoc($liste_etape);

$totalRows_liste_etape  = mysql_num_rows($liste_etape);



//liste situation marché

mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_situation = "SELECT * FROM ".$database_connect_prefix."situation_marche ORDER BY code asc";

$liste_situation  = mysql_query_ruche($query_liste_situation , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_liste_situation  = mysql_fetch_assoc($liste_situation);

$totalRows_liste_situation  = mysql_num_rows($liste_situation);



mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_annee = "SELECT distinct annee FROM ".$database_connect_prefix."ptba order by annee asc";

$liste_annee = mysql_query_ruche($query_liste_annee, $pdar_connexion) or die(mysql_error());

$tableauAnnee=array();

while($ligne=mysql_fetch_assoc($liste_annee)){$tableauAnnee[]=$ligne['annee'];}

mysql_free_result($liste_annee);



$annee_c=date("Y");



mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_cout = "SELECT annee, right(code_categorie,2) as cat, SUM( if(cout_prevu>0, cout_prevu,0) ) AS prevu, SUM( if(cout_realise>0, cout_realise,0) ) AS realise, SUM( if(cout_engage>0, cout_engage,0) ) AS engage FROM ".$database_connect_prefix."code_analytique group by annee, cat";

$liste_cout = mysql_query_ruche($query_liste_cout, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_liste_cout = mysql_fetch_assoc($liste_cout);

$totalRows_liste_cout = mysql_num_rows($liste_cout);

$prevu_array = array();

$realise_array = array();

$engage_array = array();

if($totalRows_liste_cout>0){

do{

 $prevu_array[$row_liste_cout["annee"]][$row_liste_cout["cat"]]=$row_liste_cout["prevu"];

 $realise_array[$row_liste_cout["annee"]][$row_liste_cout["cat"]]=$row_liste_cout["realise"];

 $engage_array[$row_liste_cout["annee"]][$row_liste_cout["cat"]]=$row_liste_cout["engage"];

  }

while($row_liste_cout  = mysql_fetch_assoc($liste_cout));}



 mysql_select_db($database_pdar_connexion, $pdar_connexion);

  $query_liste_cat_depense= "SELECT code, nom_categorie FROM ".$database_connect_prefix."categorie_depense order by code";

  $liste_cat_depense = mysql_query_ruche($query_liste_cat_depense, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

  $tableauCatDepense=array();

  $nbcatdep=0;

  while($lignecat_depense=mysql_fetch_assoc($liste_cat_depense)){$tableauCatDepense[]=$lignecat_depense['code']."<>".$lignecat_depense['nom_categorie']; $nbcatdep=$nbcatdep+1;}

  mysql_free_result($liste_cat_depense);

  

mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_cout_cat = "SELECT annee, code_categorie, SUM( if(cout_prevu>0, cout_prevu,0) ) AS prevu, SUM( if(cout_realise>0, cout_realise,0) ) AS realise, SUM( if(cout_engage>0, cout_engage,0) ) AS engage FROM ".$database_connect_prefix."code_analytique group by annee, code_categorie";

$liste_cout_cat = mysql_query_ruche($query_liste_cout_cat, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_liste_cout_cat = mysql_fetch_assoc($liste_cout_cat);

$totalRows_liste_cout_cat = mysql_num_rows($liste_cout_cat);

$prevu_cat_array = array();

$realise_cat_array = array();

$engage_cat_array = array();

if($totalRows_liste_cout_cat>0){

do{

 $prevu_cat_array[$row_liste_cout_cat["annee"]][$row_liste_cout_cat["code_categorie"]]=$row_liste_cout_cat["prevu"];

 $realise_cat_array[$row_liste_cout_cat["annee"]][$row_liste_cout_cat["code_categorie"]]=$row_liste_cout_cat["realise"];

 $engage_cat_array[$row_liste_cout_cat["annee"]][$row_liste_cout_cat["code_categorie"]]=$row_liste_cout_cat["engage"];

  }

while($row_liste_cout_cat  = mysql_fetch_assoc($liste_cout_cat));}



mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_code_budgetp = "SELECT annee,intitule_activite_budget FROM ".$database_connect_prefix."code_analytique WHERE code_categorie='fichiers' ";

$liste_code_budgetp   = mysql_query_ruche($query_liste_code_budgetp , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_liste_code_budgetp   = mysql_fetch_assoc($liste_code_budgetp );

$totalRows_liste_code_budgetp   = mysql_num_rows($liste_code_budgetp );

$fichier_array = array();

if($totalRows_liste_code_budgetp>0){

do{

 $fichier_array[$row_liste_code_budgetp["annee"]]=$row_liste_code_budgetp["intitule_activite_budget"];

  }

while($row_liste_code_budgetp  = mysql_fetch_assoc($liste_code_budgetp));}



mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_activite = "SELECT * FROM ".$database_connect_prefix."plan_budget_projet WHERE niveau =1 and ".$_SESSION["clp_where"]." ORDER BY niveau,code ASC";

$liste_activite  = mysql_query_ruche($query_liste_activite , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_liste_activite  = mysql_fetch_assoc($liste_activite );

$totalRows_liste_activite  = mysql_num_rows($liste_activite );



mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_entete = "SELECT * FROM ".$database_connect_prefix."plan_budget_config WHERE ".$_SESSION["clp_where"]." LIMIT 1";

$entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_entete  = mysql_fetch_assoc($entete);

$totalRows_entete  = mysql_num_rows($entete);

$libelle = array();

if($totalRows_entete>0){ $libelle=explode(",",$row_entete["libelle"]);}*/

$tab_array = array("Taux de d&eacute;caissement"=>"taux_decaissement.php","Situation par bailleur"=>"situation_bailleur.php","Situation par type d'activit&eacute;s"=>"situation_type_activite.php","Importation du budget"=>"importation_budget.php","Analyse des activit&eacute;s import&eacute;es"=>"situation_import.php")

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

  <script src="<?php print $config->script_folder; ?>/highcharts.js"></script>

  <script src="<?php print $config->script_folder; ?>/modules/exporting.js"></script>
    <script src="<?php print $config->script_folder; ?>/modules/data.js"></script>
  <script src="<?php print $config->script_folder; ?>/modules/drilldown.js"></script>



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

<?php include_once 'modal_add.php'; ?>

<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {

  border-spacing: 0px !important; border-collapse: collapse;

} .table tbody tr td {vertical-align: middle; }



</style>

<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i> Analyse budgetaire </h4>

</div>

<div class="widget-content">

<div class="tabbable tabbable-custom" >

  <ul class="nav nav-tabs" >

  <?php $j=0; foreach($tab_array as $a=>$b){ ?>

    <li title="" class="<?php echo ($j==$annee || $j==0)?"active":""; ?>"><a href="#tab_feed_<?php echo $j; ?>" data-toggle="tab"><?php echo $a; ?></a></li>

  <?php $j++; } ?>

  </ul>

  <div class="tab-content">

  <?php $j=0; foreach($tab_array as $a=>$b){ ?>

  <div class="tab-pane <?php echo ($j==$annee || $j==0)?"active":""; ?>" id="tab_feed_<?php echo $j; ?>" data-target="./content/<?php echo $b; ?>" >

  </div>

  <?php $j++; } ?>

  </div>

</div>

</div></div>



</div>

<!-- Fin Site contenu ici -->



        </div>



        </div>

    </div>    <?php include_once 'modal_add.php'; ?>

    <?php include_once("includes/footer.php"); ?>

</div>

</body>

</html>