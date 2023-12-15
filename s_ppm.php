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
$liste_annee = mysql_query_ruche($query_liste_annee, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_annee = mysql_fetch_assoc($liste_annee);
$totalRows_liste_annee = mysql_num_rows($liste_annee);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = "SELECT * FROM ".$database_connect_prefix."ptba where ".$database_connect_prefix."ptba.annee='$annee' and projet='".$_SESSION["clp_projet"]."'  order by  code_activite_ptba asc";
$act  = mysql_query_ruche($query_act , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_act  = mysql_fetch_assoc($act);
$totalRows_act  = mysql_num_rows($act);*/

/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_classeur = "SELECT * FROM ".$database_connect_prefix."classeur WHERE ".$_SESSION["clp_where"]."";
$liste_classeur = mysql_query_ruche($query_liste_classeur, $pdar_connexion) or die(mysql_error());
$row_liste_classeur = mysql_fetch_assoc($liste_classeur);
$totalRows_liste_classeur = mysql_num_rows($liste_classeur);*/
$query_liste_periode = "SELECT * FROM ".$database_connect_prefix."version_plan_marche order by date_version desc";
           try{
    $liste_periode = $pdar_connexion->prepare($query_liste_periode);
    $liste_periode->execute();
    $row_liste_periode = $liste_periode ->fetch();
    $totalRows_liste_periode = $liste_periode->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$lversion=$row_liste_periode['id_version'];
//$query_liste_modele = "SELECT * FROM ".$database_connect_prefix."modele_marche where id_modele in(select modele_marche from plan_marche where projet='".$_SESSION["clp_projet"]."')  ORDER BY code asc";
$query_liste_modele = "SELECT * FROM ".$database_connect_prefix."categorie_marche  ORDER BY nom_categorie asc";
           try{
    $liste_modele = $pdar_connexion->prepare($query_liste_modele);
    $liste_modele->execute();
    $row_liste_modele = $liste_modele ->fetchAll();
    $totalRows_liste_modele = $liste_modele->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$query_liste_type_modele = "SELECT * FROM ".$database_connect_prefix."modele_marche where id_modele in(select modele_marche from plan_marche where projet='".$_SESSION["clp_projet"]."')  ORDER BY code asc";
           try{
    $liste_type_modele = $pdar_connexion->prepare($query_liste_type_modele);
    $liste_type_modele->execute();
    $row_liste_type_modele = $liste_type_modele ->fetchAll();
    $totalRows_liste_type_modele = $liste_type_modele->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
/*
$query_liste_type_contrat = "SELECT * FROM ".$database_connect_prefix."type_marche   ORDER BY type_marche asc";
           try{
    $liste_type_contrat = $pdar_connexion->prepare($query_liste_type_contrat);
    $liste_type_contrat->execute();
    $row_liste_type_contrat = $liste_type_contrat ->fetchAll();
    $totalRows_liste_type_contrat = $liste_type_contrat->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }*/
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
  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/login.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
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
<div class="widget-header1"> <center><h4><?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>
<div class="widget-content" style="display: block;">
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Plan de Passation des March&eacute;s (PPM) </h4></div>
  <div class="widget-content" style="display: block;">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="5" >
 <tr>
            <td>
<div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu1">PPM </label></div>
<form name="form1ms" id="form1ms">
<select name="menu1" id="menu1" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
<option value="">Selectionnez</option>
<?php if($totalRows_liste_modele>0) { foreach($row_liste_modele as $row_liste_modele1){  ?>
<option value="<?php echo "print_ppm.php?version=$lversion&modele=".$row_liste_modele1['code_categorie']?>"><?php echo  $row_liste_modele1['nom_categorie'];?></option>
<?php }} ?>
</select></form><div class="clear h0">&nbsp;</div></div>
            </td>
 <td>
<div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu1">Suivi PPM </label></div>
<form name="form1ms" id="form1ms">
<select name="menu1" id="menu1" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
<option value="">Selectionnez</option>
<?php if($totalRows_liste_modele>0) { foreach($row_liste_modele as $row_liste_modele1){  ?>
<option value="<?php echo "print_suivi_ppm.php?version=$lversion&modele=".$row_liste_modele1['code_categorie']?>"><?php echo  $row_liste_modele1['nom_categorie'];?></option>
<?php } } ?>
</select></form><div class="clear h0">&nbsp;</div></div>
            </td>
            <td>
<div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu1">Situation des March&eacute;s </label></div>
<form name="form1ms" id="form1ms">
<select name="menu1" id="menu1" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
<option value="">--</option>
<?php //if($totalRows_liste_periode>0) { foreach($row_liste_periode as $row_liste_periode1){ ?>
<option value="<?php echo "print_situation_tableau_ppm.php?annee=".$row_liste_periode['id_version']?>"><?php echo $row_liste_periode['numero_version'];?></option>
<?php //} } ?>
</select></form><div class="clear h0">&nbsp;</div></div>
            </td>
            </tr>
<tr>
            <td>
<div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu1">Etapes par mod&egrave;le de passation </label>
</div>
<form name="form1ms" id="form1ms">
  <select name="select" id="select" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
    <option value="">Selectionnez</option>
    <?php if($totalRows_liste_type_modele>0) { foreach($row_liste_type_modele as $row_liste_type_modele1){ ?>
    <option value="<?php echo "print_modele_ppm.php?version=$lversion&modele=".$row_liste_type_modele1['id_modele']?>"><?php echo  $row_liste_type_modele1['code'];?></option>
    <?php } } ?>
  </select>
</form><div class="clear h0">&nbsp;</div></div>
            </td>
 <td>
<div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu1">Avancement du  PPM </label>
</div>
<form name="form1ms" id="form1ms">
  <select name="select2" id="select2" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
    <option value="">--</option>
    <?php //if($totalRows_liste_periode>0) { foreach($row_liste_periode as $row_liste_periode1){ ?>
    <option value="<?php echo "print_avancement_ppm.php?version=".$row_liste_periode['id_version']?>"><?php echo  $row_liste_periode['numero_version'];?></option>
    <?php //} } ?>
  </select>
</form><div class="clear h0">&nbsp;</div></div>
            </td>
            <td>
<div class="well well-sm"><div class="pull-left p11"><img src="images/player_play.png" width="19" height="21" />&nbsp;<label for="menu1">Situation des contrats </label>
</div>
<form name="form1ms" id="form1ms">
<select name="menu1" id="menu1" onchange="MM_jumpMenu('parent',this,0)" class="btn pull-right">
<option value="">--</option>
<?php if($totalRows_liste_modele>0) {  foreach($row_liste_modele as $row_liste_type_contrat){ ?>
<option value="<?php echo "print_situation_contrat.php?type=".$row_liste_type_contrat['code_categorie']?>"><?php echo $row_liste_type_contrat['nom_categorie'];?></option>
<?php } } ?>
</select></form><div class="clear h0">&nbsp;</div></div>
            </td>
            </tr>

        </table>
    </div>
</div></div> </div> 
<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>   <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>

</body>
</html>