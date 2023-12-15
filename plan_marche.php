<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
 // header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";

$personnel=$_SESSION["clp_id"];
$date=date("Y-m-d");

$query_liste_version = "SELECT * FROM ".$database_connect_prefix."version_plan_marche ORDER BY date_version asc";
           try{
    $liste_version = $pdar_connexion->prepare($query_liste_version);
    $liste_version->execute();
    $row_liste_version = $liste_version ->fetchAll();
    $totalRows_liste_version = $liste_version->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$TableauVersion = array(); $version_array = array();
if($totalRows_liste_version>0) { foreach($row_liste_version as $row_liste_version){  
$max_version=$row_liste_version["id_version"];
$TableauVersion[]=$row_liste_version["id_version"]."<>".$row_liste_version["numero_version"];
$version_array[$row_liste_version["numero_version"]] = $row_liste_version["id_version"];
  }  }



if(isset($_GET['annee'])) {$annee=$_GET['annee'];} elseif($totalRows_liste_version>0) $annee=$max_version; else  $annee=1;
//if(isset($_GET['cp'])) {$cp=$_GET['cp'];} else $cp=0;
//if(isset($_GET['scp'])) {$scp=$_GET['scp'];} else $scp=0;
$cmp ="0";
if(isset($_GET['cmp'])) $cmp = ($_GET['cmp']);

if(isset($_GET["id_sup_act"]))
{
  $id=$_GET["id_sup_act"];
 // mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_sup_act = "DELETE FROM ".$database_connect_prefix."plan_marche WHERE id_marche='$id'";
     try{
    $Result1 = $pdar_connexion->prepare($query_sup_act);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok&annee=$annee";
  else $insertGoTo .= "?del=no&annee=$annee";
  mysql_free_result($Result1);
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{ //PPM
//$id_cp=$_POST["id_cp"];
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {

  $modele_m="0"; $annee=$_POST['annee'];

$query_liste_modele = "SELECT * FROM ".$database_connect_prefix."modele_marche ORDER BY code asc";
       try{
    $liste_modele = $pdar_connexion->prepare($query_liste_modele);
    $liste_modele->execute();
    $row_liste_modele = $liste_modele ->fetchAll();
    $totalRows_liste_modele = $liste_modele->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


 if($totalRows_liste_modele>0){  foreach($row_liste_modele as $row_liste_modele){  

 if($_POST['examen_banque']==$row_liste_modele["examen"]
  && $row_liste_modele['montant_max']!=0
  && $row_liste_modele['categorie']==$_POST["categorie"]
  && $_POST['montant_usd']>=$row_liste_modele["montant_min"]
  && $row_liste_modele['montant_max']>=$_POST["montant_usd"]
  && in_array($_POST['methode'],explode(", ", $row_liste_modele['methode_concerne'])))  $modele_m=$row_liste_modele["id_modele"];

  elseif($_POST['examen_banque']==$row_liste_modele["examen"]
  && $row_liste_modele['montant_max']==0
  && $row_liste_modele['categorie']==$_POST["categorie"]
  && in_array($_POST['methode'],explode(", ", $row_liste_modele['methode_concerne'])))  $modele_m=$row_liste_modele["id_modele"];
    //$acteur_array[$row_liste_prestataire["code_acteur"]] = $row_liste_prestataire["sigle"];
  } }

 // $responsable=""; //$idcl=$_POST['isous_composante'];
 // $code = explode(":",$_POST['code_activite_ptba']);
// echo $_POST['version'];
// exit;
$version = array(); $a = explode(',',$_POST['version']);  foreach($a as $b){ if(isset($version_array[$b])) $version[] = $version_array[$b]; }
//$var=implode(",",$version); echo $var;
//exit;

  $personnel=$_SESSION['clp_id'];
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."plan_marche (projet, categorie, modele_marche, periode, code_marche, composante, intitule, lot, montant_usd,  methode, examen_banque, examen_dncmp, date_prevue, description, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, '$personnel', '$date')",
   					   GetSQLValueString($_SESSION["clp_projet"], "text"),
					   GetSQLValueString($_POST['categorie'], "text"),
					   GetSQLValueString($modele_m, "int"),
					   GetSQLValueString(implode(",",$version), "text"),
					   GetSQLValueString($_POST['code_marche'], "text"),
                       GetSQLValueString($_POST['composante'], "text"),
					   GetSQLValueString($_POST['intitule'], "text"),
					  // GetSQLValueString($_POST['nb_marche'], "int"),
                       GetSQLValueString($_POST['lot'], "int"),
					   GetSQLValueString($_POST['montant_usd'], "double"),
                       GetSQLValueString($_POST['methode'], "text"),
                       GetSQLValueString($_POST['examen_banque'], "text"),
                       GetSQLValueString($_POST['examen_dncmp'], "text"),
                       GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_prevue']))), "date"),
                       GetSQLValueString($_POST['description'], "text"));

	    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
	
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    $insertGoTo .= "&annee=$annee";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."plan_marche WHERE id_marche=%s",
                         GetSQLValueString($id, "int"));

		    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    $insertGoTo .= "&annee=$annee";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if (isset($_POST["MM_update"]) && !empty($_POST["MM_update"])) {
  $id = $_POST["MM_update"]; $personnel=$_SESSION['clp_id'];	$modele_m="N/A"; $annee=$_POST['annee'];
 
  $query_liste_modele = "SELECT * FROM ".$database_connect_prefix."modele_marche ORDER BY code asc";
       try{
    $liste_modele = $pdar_connexion->prepare($query_liste_modele);
    $liste_modele->execute();
    $row_liste_modele = $liste_modele ->fetchAll();
    $totalRows_liste_modele = $liste_modele->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

 if($totalRows_liste_modele>0){  foreach($row_liste_modele as $row_liste_modele){  

 if($_POST['examen_banque']==$row_liste_modele["examen"]
  && $row_liste_modele['montant_max']!=0
  && $row_liste_modele['categorie']==$_POST["categorie"]
  && $_POST['montant_usd']>=$row_liste_modele["montant_min"]
  && $row_liste_modele['montant_max']>=$_POST["montant_usd"]
  && in_array($_POST['methode'],explode(", ", $row_liste_modele['methode_concerne'])))  $modele_m=$row_liste_modele["id_modele"];

  elseif($_POST['examen_banque']==$row_liste_modele["examen"]
  && $row_liste_modele['montant_max']==0
  && $row_liste_modele['categorie']==$_POST["categorie"]
  && in_array($_POST['methode'],explode(", ", $row_liste_modele['methode_concerne'])))  $modele_m=$row_liste_modele["id_modele"];
    //$acteur_array[$row_liste_prestataire["code_acteur"]] = $row_liste_prestataire["sigle"];
  }  }

$version = array(); $a = explode(',',$_POST['version']);  foreach($a as $b){ if(isset($version_array[$b])) $version[] = $version_array[$b]; }
//$var=implode(",",$version); echo $var;
//exit;
$insertSQL = sprintf("UPDATE ".$database_connect_prefix."plan_marche SET code_marche=%s, categorie=%s, modele_marche=%s, composante=%s, intitule=%s, periode=%s, lot=%s, montant_usd=%s, methode=%s,  examen_banque=%s, examen_dncmp=%s, date_prevue=%s, description=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_marche=%s",
       				   GetSQLValueString($_POST['code_marche'], "text"),
					   GetSQLValueString($_POST['categorie'], "text"),
					   GetSQLValueString($modele_m, "int"),
                       GetSQLValueString($_POST['composante'], "text"),
					   GetSQLValueString($_POST['intitule'], "text"),
					   GetSQLValueString(implode(",",$version), "text"),
                       GetSQLValueString($_POST['lot'], "int"),
					   GetSQLValueString($_POST['montant_usd'], "double"),
                       GetSQLValueString($_POST['methode'], "text"),
                       GetSQLValueString($_POST['examen_banque'], "text"),
                       GetSQLValueString($_POST['examen_dncmp'], "text"),
  					   GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_prevue']))), "date"),
                       GetSQLValueString($_POST['description'], "text"),
					   GetSQLValueString($id, "text"));
	    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    $insertGoTo .= "&annee=$annee";
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
<div class="widget-header"> <h4><i class="icon-reorder"></i> Plan de Passation de March&eacute;</h4>

</div>

<div class="widget-content" style="display: block;">

<div class="tabbable tabbable-custom" >
  <ul class="nav nav-tabs" >

  <?php foreach($TableauVersion as $vversion){ $aversion = explode('<>',$vversion); ?>
   <li title="" class="<?php echo ($aversion[0]==$annee)?"active":""; ?>"><a href="#tab_feed_<?php echo $aversion[0]; ?>" data-toggle="tab"><?php echo implode('-',array_reverse(explode('-',$aversion[1]))); ?></a></li>
              <?php } ?>
  </ul>
  <div class="tab-content">
    <?php foreach($TableauVersion as $vversion){ $aversion = explode('<>',$vversion); ?>
   <div class="tab-pane <?php echo ($aversion[0]==$annee)?"active":""; ?>" id="tab_feed_<?php echo $aversion[0]; ?>" data-target="./plan_marche_content.php?annee=<?php echo $aversion[0]."&cmp=$cmp"; ?>"></div>
	          <?php } ?>
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