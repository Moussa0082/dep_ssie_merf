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
/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$an_cour=date("Y");
$query_liste_annee = "SELECT distinct annee FROM ".$database_connect_prefix."ptba where  projet='".$_SESSION["clp_projet"]."' order by annee desc";
$liste_annee = mysql_query_ruche($query_liste_annee, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_annee = mysql_fetch_assoc($liste_annee);
$totalRows_liste_annee = mysql_num_rows($liste_annee);
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = "SELECT * FROM ".$database_connect_prefix."ptba where ".$database_connect_prefix."ptba.annee='$annee' and projet='".$_SESSION["clp_projet"]."'  order by  code_activite_ptba asc";
$act  = mysql_query_ruche($query_act , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_act  = mysql_fetch_assoc($act);
$totalRows_act  = mysql_num_rows($act);*/
 /* mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_region= "SELECT code_ugl, nom_ugl FROM ".$database_connect_prefix."ugl order by code_ugl";
  $liste_region = mysql_query_ruche($query_liste_region, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $tableauRegion=array();
  while($ligne=mysql_fetch_assoc($liste_region))
  { $tableauRegion[$ligne["code_ugl"]]=$ligne["nom_ugl"];}
  mysql_free_result($liste_region);*/
  
$query_liste_version = "SELECT * FROM ".$database_connect_prefix."version_ptba  ORDER BY annee_ptba desc, date_validation desc";
           try{
    $liste_version = $pdar_connexion->prepare($query_liste_version);
    $liste_version->execute();
    $row_liste_version = $liste_version ->fetchAll();
    $totalRows_liste_version = $liste_version->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$TableauVersionP = array(); $version_array = array();
if($totalRows_liste_version>0){foreach($row_liste_version as $row_liste_version){ 
$max_version=$row_liste_version["id_version_ptba"];
$TableauVersionP[]=$row_liste_version["id_version_ptba"]."<>".$row_liste_version["version_ptba"]."<>".$row_liste_version["annee_ptba"];
$version_array[$row_liste_version["version_ptba"]] = $row_liste_version["id_version_ptba"];
 } }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print $config->sitename; ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="keywords" content="<?php print $config->MetaKeys; ?>" />
  <meta name="description" content="<?php print $config->MetaDesc; ?>" />
  <meta name="author" content="<?php print $config->MetaAuthor; ?>" />
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
<div class="widget box box_projet">
<div class="widget-content" style="display: block;">
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Programmation du PTBA</h4></div>
  <div class="widget-content" style="display: block;">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="5" >
          <tr>
            <td>
<div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu1">Chronogramme des activit&eacute;s du PTBA </label>
    &nbsp;</div>
<form name="form1ms" id="form1ms">
<select name="menu1" id="menu1" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
<option value="">Selectionnez</option>
 <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>
<option value="<?php echo "print_activite_ptba.php?ugl=".$_SESSION["clp_structure"]."&annee=".$aversionP[0]?>"><?php echo  $aversionP[2]." ".$aversionP[1];?></option>
              <?php }  ?>
</select></form><div class="clear h0">&nbsp;</div>
</div></td>
            <td>
<div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu1">Chronogramme des T&acirc;ches du PTBA </label>&nbsp;</div>
<form name="form1ms" id="form1ms">
<select name="menu1" id="menu1" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
<option value="">Selectionnez</option>
 <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>
<option value="<?php echo "print_taches_activite_ptba.php?ugl=".$_SESSION["clp_structure"]."&annee=".$aversionP[0]?>"><?php echo  $aversionP[2]." ".$aversionP[1];?></option>
              <?php }  ?>
</select></form><div class="clear h0">&nbsp;</div>
</div></td>
            <td>
<div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu1">Chronogramme des Indicateurs du PTBA </label>&nbsp;</div>
<form name="form1ms" id="form1ms">
<select name="menu1" id="menu1" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
<option value="">Selectionnez</option>
 <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>
<option value="<?php echo "print_indicateurs_ptba.php?ugl=".$_SESSION["clp_structure"]."&annee=".$aversionP[0]?>"><?php echo  $aversionP[2]." ".$aversionP[1];?></option>
              <?php }  ?>
</select></form> <div class="clear h0">&nbsp;</div>
</div></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td><div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu1">T&acirc;ches par responsable</label>&nbsp;</div>
<form name="form1ms" id="form1ms">
<select name="menu1" id="menu1" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
<option value="">Selectionnez</option>
 <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>
<option value="<?php echo "print_taches_activite_semaine.php?ugl=".$_SESSION["clp_structure"]."&annee=".$aversionP[0]?>"><?php echo  $aversionP[2]." ".$aversionP[1];?></option>
              <?php }  ?>
</select></form> <div class="clear h0">&nbsp;</div>
</div></td>
            <td>&nbsp;</td>
          </tr>
        </table>
    </div>
</div>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i>Suivi du PTBA</h4></div>
  <div class="widget-content" style="display: block;">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="5" >
          <tr>
            <td>
<div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu1">Avancement budgetaire du PTBA</label> </div>
<form name="form1ms" id="form1ms">
  <select name="select" id="select" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
    <option value="">Selectionnez</option>
 <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>
<option value="<?php echo "print_suivi_budget_ptba_projet.php?ugl=".$_SESSION["clp_structure"]."&annee=".$aversionP[0]?>"><?php echo  $aversionP[2]." ".$aversionP[1];?></option>
              <?php }  ?>
  </select>
</form>    <div class="clear h0">&nbsp;</div>
</div></td>
            <td>
<div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu1">Avancement  des T&acirc;ches du PTBA </label>
    &nbsp;</div>
<form name="form1ms" id="form1ms">
<select name="menu1" id="menu1" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
<option value="">Selectionnez</option>
 <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>
<option value="<?php echo "print_suivi_taches_activite_ptba.php?ugl=".$_SESSION["clp_structure"]."&annee=".$aversionP[0]?>"><?php echo  $aversionP[2]." ".$aversionP[1];?></option>
              <?php }  ?>
</select></form> <div class="clear h0">&nbsp;</div>
</div></td>
            <td>
<div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu1">Avancements des Indicateurs du PTBA </label>
    &nbsp;</div>
<form name="form1ms" id="form1ms">
<select name="menu1" id="menu1" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
<option value="">Selectionnez</option>
 <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>
<option value="<?php echo "print_etat_indicateurs_ptba.php?acteur=&annee=".$aversionP[0]?>"><?php echo  $aversionP[2]." ".$aversionP[1];?></option>
              <?php }  ?>
</select></form> <div class="clear h0">&nbsp;</div>
</div></td>
            </tr>
          <tr>
            <td>
<div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu1">Avancement global  du PTBA par palier </label>
    &nbsp;</div>
<form name="form1ms" id="form1ms">
<select name="menu1" id="menu1" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
<option value="">Selectionnez</option>
 <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>
<option value="<?php echo "suivi_plan_analytique_projet.php?ugl=".$_SESSION["clp_structure"]."&annee=".$aversionP[0]?>"><?php echo  $aversionP[2]." ".$aversionP[1];?></option>
              <?php }  ?>
</select></form> <div class="clear h0">&nbsp;</div>
</div></td>
            <td>
<div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu1">Avancement global du PTBA par niveau </label> &nbsp;</div>
<form name="form1ms" id="form1ms">
  <select name="select" id="select" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
    <option value="">Selectionnez</option>
 <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>
<option value="<?php echo "print_suivi_ptba_projet_pa.php?niveau=0&annee=".$aversionP[0]?>"><?php echo  $aversionP[2]." ".$aversionP[1];?></option>
              <?php }  ?>
  </select></form>  <div class="clear h0">&nbsp;</div>
</div></td>
            <td>
<!--<div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu1">Recapitulatif de l'ex&eacute;cution du PTBA  </label>
    &nbsp;</div>  
<div class="clear h0">&nbsp;</div>
<form name="form1ms" id="form1ms">
  <select name="select" id="select" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
    <option value="">Selectionnez</option>
 <?php //foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>
<option value="<?php //echo "recap_taches_activite_ptba.php?ugl=".$_SESSION["clp_structure"]."&annee=".$aversionP[0]?>"><?php echo  $aversionP[2]." ".$aversionP[1];?></option>
              <?php //}  ?>
  </select>
</form>
</div>--></td>
            </tr>
        </table>
    </div>
</div>
</div> </div>
<!-- Fin Site contenu ici -->
            </div>
        </div>
        </div>
    </div>  <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>