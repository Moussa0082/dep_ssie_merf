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

if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y");
 

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$an_cour=date("Y");
$query_liste_annee = "SELECT distinct annee FROM ".$database_connect_prefix."ptba where  projet='".$_SESSION["clp_projet"]."' order by annee desc";
$liste_annee = mysql_query($query_liste_annee, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_annee = mysql_fetch_assoc($liste_annee);
$totalRows_liste_annee = mysql_num_rows($liste_annee);*/


$query_edit_ms = "SELECT id_mission, code_ms, debut, fin, type  FROM ".$database_connect_prefix."mission_supervision  order by debut desc";
  try{
    $edit_ms = $pdar_connexion->prepare($query_edit_ms);
    $edit_ms->execute();
    $row_edit_ms = $edit_ms ->fetchAll();
    $totalRows_edit_ms = $edit_ms->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

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
<style>#sp_hr {margin:0px; } .well { margin-bottom: 5px; }
</style>
<script type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<div class="widget-header"> <h4><i class="icon-reorder"></i> Gestion de projet </h4>
<div class="widget-content" style="display: block;">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >
          <tr>
            <td>
<div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu1">Recommandations de missions </label>&nbsp;</div>
<form name="form1ms" id="form1ms">
<select name="menu1" id="menu1" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
<option value="">Selectionnez</option>
<?php if($totalRows_edit_ms>0) { foreach($row_edit_ms as $row_edit_ms1){ ?>
<option value="<?php echo "print_recommandation_mission.php?id=".$row_edit_ms1['id_mission']?>"><?php echo  $row_edit_ms1['type']." / du ".implode('/',array_reverse(explode('-',$row_edit_ms1['debut'])))." au ".implode('/',array_reverse(explode('-',$row_edit_ms1['fin'])));?></option>
<?php }} ?>
</select></form><div class="clear h0">&nbsp;</div></div>            </td>
            </tr>
          <tr>
            <td>
<div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu11">Etat des recommandations missions </label>&nbsp;</div>
<form name="form1sms" id="form1sms">
<select name="menu1" id="menu11" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
<option value="">Selectionnez</option>
<?php  if($totalRows_edit_ms>0) { foreach($row_edit_ms as $row_edit_ms1){ ?>
<option value="<?php echo "print_etat_recommandation_mission.php?id=".$row_edit_ms1['id_mission']?>"><?php echo  $row_edit_ms1['type']." / du ".implode('/',array_reverse(explode('-',$row_edit_ms1['debut'])))." au ".implode('/',array_reverse(explode('-',$row_edit_ms1['fin'])));?></option>
<?php }} ?>
</select></form><div class="clear h0">&nbsp;</div></div>            </td>
            </tr>
          <tr>
            <td>
<div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu12">Graphique des Recommandations de missions</label>&nbsp;</div>
<form name="form1ms" id="form1ms">
<select name="menu1" id="menu12" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
<option value="">Selectionnez</option>
<?php if($totalRows_edit_ms>0) { foreach($row_edit_ms as $row_edit_ms){ ?>
<option value="<?php echo "graph_recommandation_mission.php?id=".$row_edit_ms['id_mission']?>"><?php echo  $row_edit_ms['type']." / du ".implode('/',array_reverse(explode('-',$row_edit_ms1['debut'])))." au ".implode('/',array_reverse(explode('-',$row_edit_ms1['fin'])));?></option>
<?php } } ?>
</select></form><div class="clear h0">&nbsp;</div></div>            </td>
            </tr>
			 <tr>
            <td bgcolor="#669933"><div class="pull-left p11">&nbsp;
                  <label for="label" style="color:#FFFFFF">Analyse des sessions</label>
              &nbsp;</div></td>
          </tr>
			 <tr>
            <td>
<div class="pull-left p11"><div class="well well-sm"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu13"><a href="print_stat_connexion.php">Statistiques des connexions</a></label>
&nbsp;</div></div>
              </td>
            </tr>
          <!--<tr>
            <td bgcolor="#669933"><div class="pull-left p11">&nbsp;
                  <label for="label" style="color:#FFFFFF">Situation des DANO</label>
              &nbsp;</div></td>
          </tr>
          <tr>
            <td>
<div class="pull-left p11"><div class="well well-sm"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu13"><a href="print_instance_dno_ida.php">ANO en instance avec le bailleur</a></label>
&nbsp;</div></div>

<div class="pull-left p11"><div class="well well-sm"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu13"><a href="print_dno_ressoumis_expediteur.php">ANO resoumis pour traitement</a></label>
&nbsp;</div></div>

<div class="pull-left p11"><div class="well well-sm"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu13"><a href="print_dno_non_soumis.php">DANO (re)soumettre au bailleur</a></label>
&nbsp;</div></div>
              </td>
            </tr>-->
        </table>
    </div>
</div>
<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>
    <?php include_once("includes/footer.php"); ?>
</div>

</body>
</html>