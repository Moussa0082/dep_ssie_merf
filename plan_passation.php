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

//fonction calcul nb jour
function NbJours($debut, $fin) {

  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);

  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) -
          mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);

  return(($diff / 86400)+1);

}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if(isset($_GET["id_mar"])) { $id_mar=$_GET["id_mar"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_marche = "SELECT * FROM categorie_marche,methode_marche,plan_marche where methode=id_methode and code_categorie=categorie and id_marche='$id_mar'";
//$query_edit_marche = "SELECT * FROM plan_marche where id_marche='$id_mar'";
$edit_marche = mysql_query($query_edit_marche, $pdar_connexion) or die(mysql_error());
$row_edit_marche = mysql_fetch_assoc($edit_marche);
$totalRows_edit_marche = mysql_num_rows($edit_marche);

$per=$row_edit_marche['periode'];      
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_la_periode = "SELECT * FROM periode_marche where id_periode='$per'";
$la_periode = mysql_query($query_la_periode, $pdar_connexion) or die(mysql_error());
$row_la_periode = mysql_fetch_assoc($la_periode);
$totalRows_la_periode = mysql_num_rows($la_periode);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_etape_plan_marche = "SELECT * FROM etape_plan_marche where marche='$id_mar'";
$etape_plan_marche = mysql_query($query_etape_plan_marche, $pdar_connexion) or die(mysql_error());
$row_etape_plan_marche = mysql_fetch_assoc($etape_plan_marche);
$totalRows_etape_plan_marche = mysql_num_rows($etape_plan_marche);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_etape_composante = "SELECT * FROM activite_projet WHERE ".$_SESSION["clp_where"]." and niveau=1";
$etape_composante = mysql_query($query_etape_composante, $pdar_connexion) or die(mysql_error());
$row_etape_composante = mysql_fetch_assoc($etape_composante);
$totalRows_etape_composante = mysql_num_rows($etape_composante);
$array_composante = array();
if($totalRows_etape_composante>0){ do{ $array_composante[$row_etape_composante["code"]]=$row_etape_composante["code"].": ".$row_etape_composante["intitule"]; }while($row_etape_composante = mysql_fetch_assoc($etape_composante));  }

}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  $insertSQL = sprintf("INSERT INTO etape_plan_marche (marche, etape, date_prevue, id_personnel, date_enregistrement) VALUES (%s, %s, %s,'$personnel', '$date')",
                       GetSQLValueString($_GET['id_mar'], "int"),
					   GetSQLValueString($_POST['etape'], "int"),
					   GetSQLValueString(implode('-',array_reverse(explode('-',$_POST['date_prevue']))), "date"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?id_mar=$id_mar&insert=ok"; else $insertGoTo .= "?id_mar=$id_mar&insert=no";
  if (isset($_GET["cat"])) $insertGoTo .= "&cat=".$_GET["cat"];
  if (isset($_GET["methode"])) $insertGoTo .= "&methode=".$_GET["methode"];
  header(sprintf("Location: %s", $insertGoTo));
}


if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sup_et = "DELETE FROM etape_plan_marche WHERE id_etape_plan='$id'";
$Result1 = mysql_query($query_sup_et, $pdar_connexion) or die(mysql_error());
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?id_mar=$id_mar&del=ok"; else $insertGoTo .= "?id_mar=$id_mar&del=no";
  if (isset($_GET["cat"])) $insertGoTo .= "&cat=".$_GET["cat"];
  if (isset($_GET["methode"])) $insertGoTo .= "&methode=".$_GET["methode"];
  header(sprintf("Location: %s", $insertGoTo));
}

$cat=$row_edit_marche['categorie'];
$methode=$row_edit_marche['id_methode'];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_etape = "SELECT * FROM etape_marche where categorie='$cat' and `ordre`=1 ORDER BY ordre asc, id_etape asc LIMIT 1";
$liste_etape  = mysql_query($query_liste_etape , $pdar_connexion) or die(mysql_error());
$row_liste_etape  = mysql_fetch_assoc($liste_etape);
$totalRows_liste_etape  = mysql_num_rows($liste_etape);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_etape_plan = "SELECT * FROM etape_plan_marche, etape_marche where etape_marche.id_etape=etape_plan_marche.etape and marche='$id_mar' ORDER BY ordre asc, id_etape asc";
$liste_etape_plan  = mysql_query($query_liste_etape_plan , $pdar_connexion) or die(mysql_error());
$row_liste_etape_plan  = mysql_fetch_assoc($liste_etape_plan);
$totalRows_liste_etape_plan  = mysql_num_rows($liste_etape_plan);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_etape_min = "SELECT * FROM etape_marche where categorie='$cat' ORDER BY ordre asc LIMIT 1";
$liste_etape_min  = mysql_query($query_liste_etape_min , $pdar_connexion) or die(mysql_error());
$row_liste_etape_min  = mysql_fetch_assoc($liste_etape_min);
$totalRows_liste_etape_min  = mysql_num_rows($liste_etape_min);
$min_ordre=1;
if($totalRows_liste_etape_min>0){ $min_ordre = (isset($liste_etape_min["min"]))?$liste_etape_min["min"]:1;  }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_etape_plan1 = "SELECT * FROM etape_marche  inner join methode_etape on id_etape=etapei  where categorie='$cat' and ordre>$min_ordre and methodei=$methode ORDER BY ordre asc";
$liste_etape_plan1  = mysql_query($query_liste_etape_plan1 , $pdar_connexion) or die(mysql_error());
$row_liste_etape_plan1  = mysql_fetch_assoc($liste_etape_plan1);
$totalRows_liste_etape_plan1  = mysql_num_rows($liste_etape_plan1);


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_methode = "SELECT * FROM methode_marche where categorie_concerne like '%$cat%' ORDER BY sigle asc";
$liste_methode  = mysql_query($query_liste_methode , $pdar_connexion) or die(mysql_error());
$row_liste_methode  = mysql_fetch_assoc($liste_methode);
$totalRows_liste_methode  = mysql_num_rows($liste_methode);
$methode_array=array();
if($totalRows_liste_methode>0){
do {$methode_array[$row_liste_methode['id_methode']]=$row_liste_methode['sigle'];
} while ($row_liste_methode = mysql_fetch_assoc($liste_methode)); }


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
<div class="widget-header"> <h4><i class="icon-reorder"></i> Plan de passation de March&eacute; </h4>
    <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){?>
<?php
echo do_link("",((isset($_GET["page"]) && $_GET["page"]!="")?$_GET["page"]:"plan_marche.php").((isset($_GET['id_mar']))?"?periode=".$row_edit_marche['periode']."&id_mar=".$_GET["id_mar"]:"").((isset($_GET['methode']))?"&methode=".$_GET["methode"]:"")."&cat=".((isset($_GET['cat']))?$_GET['cat']:""),"Retour","Retour","","./","pull-right p11","",1,"margin:0px 5px;",$nfile);
echo do_link("","suivi_passation.php?id_mar=".$row_edit_marche['id_marche']."&page=plan_passation.php&cat=".((isset($_GET['cat']))?$_GET['cat']:"")."&methode=".((isset($_GET['methode']))?$_GET['methode']:""),"Suivi","Suivi","","./","pull-right p11","",1,"margin:0px 5px;",$nfile); ?>
    <?php } ?>
<!--<a href="suivi_passation.php?id_mar=<?php echo $row_edit_marche['id_marche'];?>&page=plan_passation.php&cat=<?php if(isset($_GET['cat'])) echo $_GET['cat']; ?>&methode=<?php if(isset($_GET['methode'])) echo $_GET['methode']; ?>">Suivi</a>
<a href="<?php if(isset($_GET["page"]) && $_GET["page"]!="") echo $_GET["page"]; else echo "plan_marche.php"; if(isset($_GET['id_mar'])) echo "?periode=".$row_edit_marche['periode']."&id_mar=".$_GET["id_mar"]; if(isset($_GET['methode'])) echo "&methode=".$_GET["methode"]; ?>&cat=<?php if(isset($_GET['cat'])) echo $_GET['cat'];  ?>" class="btn btn-success pull-right">Retour</a>-->
</div>
<div class="widget-content">

        <div class="col-md-6">
        <table border="0" align="left" width="100%" cellpadding="1" cellspacing="3" bordercolor="#D9D9D9" bgcolor="#D9D9D9">
                    <tr>
                      <th colspan="2" scope="col"><div align="right"><strong>P&eacute;riode&nbsp;&nbsp;</strong></div></th>
                      <th bgcolor="#FFFFFF" scope="col"><div align="left">
                          <?php if(isset($_GET['id_mar'])) echo $row_la_periode['debut']." - ".$row_la_periode['fin'];  ?>
                      </div></th>
                    </tr>
                    <tr>
                      <td colspan="2"><div align="right"><strong>Intitul&eacute; du march&eacute;&nbsp;&nbsp;</strong></div></td>
                      <td bgcolor="#FFFFFF"><?php if(isset($_GET['id_mar'])) echo $row_edit_marche['intitule'];  ?></td>
                    </tr>
                    <tr>
                      <td colspan="2"><div align="right"><strong>Nombre de lots &nbsp;&nbsp;</strong></div></td>
                      <td bgcolor="#FFFFFF"><?php if(isset($_GET['id_mar'])) echo $row_edit_marche['lot'];  ?></td>
                    </tr>
                    <tr>
                      <td colspan="2"><div align="right"><strong>Cat&eacute;gorie&nbsp;&nbsp;</strong></div></td>
                      <td bgcolor="#FFFFFF"><div align="left">
                          <?php if(isset($_GET['id_mar'])) echo $row_edit_marche['nom_categorie'];  ?>
                      </div></td>
                    </tr>
                    <tr>
                      <td colspan="2"><div align="right"><strong>M&eacute;thode&nbsp;&nbsp;</strong></div></td>
                      <td bgcolor="#FFFFFF"><?php if(isset($_GET['id_mar']) && isset($methode_array[$row_edit_marche['methode']])) echo $methode_array[$row_edit_marche['methode']];  ?></td>
                    </tr>
                    <tr>
                      <td colspan="2"><div align="right"><strong>Composante&nbsp;&nbsp; </strong></div></td>
                      <td bgcolor="#FFFFFF"><div align="left">
                        <?php if(isset($_GET['id_mar']) && isset($array_composante[$row_edit_marche['composante']])) echo $array_composante[$row_edit_marche['composante']];  ?>
                      </div></td>
                    </tr>
                    <tr>
                      <td colspan="2"><div align="right"><strong>Description&nbsp;&nbsp; </strong></div></td>
                      <td bgcolor="#FFFFFF"><div align="left">
                        <?php if(isset($_GET['id_mar'])) echo $row_edit_marche['description'];  ?>
                      </div></td>
                    </tr>
                    <tr>
                      <td bgcolor="#B1C3D9"><div align="right"><strong>Montant estimatif&nbsp;&nbsp; </strong></div></td>
                      <td nowrap="nowrap" bgcolor="#B1C3D9"><em><strong>FCFA </strong></em></td>
                      <td nowrap="nowrap" bgcolor="#FFFFFF"><div align="left">
                        <?php if(isset($_GET['id_mar'])) echo number_format($row_edit_marche['montant_local'], 0, ',', ' ');  ?>
                      </div></td>
                    </tr>
                    <tr>
                      <td bgcolor="#B1C3D9"><div align="right"><strong>Montant estimatif&nbsp;&nbsp; </strong></div></td>
                      <td nowrap="nowrap" bgcolor="#B1C3D9"><em><strong>US ($) </strong></em></td>
                      <td nowrap="nowrap" bgcolor="#FFFFFF"><div align="left">
                        <?php if(isset($_GET['id_mar'])) echo number_format($row_edit_marche['montant_usd'], 0, ',', ' ');  ?>
                      </div></td>
                    </tr>
                  </table>
                </div>
<div class="col-md-6">
                  <table width="100%" border="0" align="left" cellspacing="3">
                    <?php $i=0; if($totalRows_liste_etape_plan>0) {?>
                    <tr class="titrecorps2" style="background-color: grey; color: white;">
                      <td><div align="left"><span class="Style5"><strong>Intitul&eacute; de l'&eacute;tape </strong></span></div></td>
                      <td><div align="center"><span class="Style5"><strong>Date pr&eacute;vue</strong></span></div></td>
                      <td><strong class="Style5">Dur&eacute;e (J)</strong></td>
                      <td><span class="Style5"></span><span class="Style5"><strong>Editer</strong></span></td>
                    </tr>
                    <?php $duree_totale=0; $etape_0="00-00-0000"; do { $i++; $date_start = $row_liste_etape_plan['date_prevue'];  ?>
                    <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; else echo 'bgcolor="#F9F9F7"'; ?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='<?php if($i%2==0) echo "#ECF0DF"; else echo "#F9F9F7";?>'">
                      <td><div align="left"><span class="Style5"><?php echo $row_liste_etape_plan['intitule']; ?></span></div></td>
                      <td><div align="center"><span class="Style5"><strong><?php echo date("d/m/Y", strtotime($row_liste_etape_plan['date_prevue']));
					  ?></strong></span></div></td>
                      <td><div align="center"><span class="Style5"><span class="Style6">
                        <?php if(isset($row_liste_etape_plan['date_prevue']) && $row_liste_etape_plan['date_prevue']>=$etape_0 && $etape_0!="00-00-0000") { $Nombres_jours = NbJours($etape_0, $row_liste_etape_plan['date_prevue']);
echo number_format($Nombres_jours-1, 0, ',', ' '); $duree_totale=$duree_totale+number_format($Nombres_jours, 0, ',', ' ')-1;} if($etape_0=="00-00-0000") echo "-"; $etape_0=$row_liste_etape_plan['date_prevue']; ?></span></span></div></td>
                      <td>
                          <div align="center"><a href="<?php  echo $_SERVER['PHP_SELF']."?id_sup=".$row_liste_etape_plan['id_etape_plan']."&id_mar=".$id_mar?>&cat=<?php if(isset($_GET['cat'])) echo $_GET['cat']; ?>&methode=<?php if(isset($_GET['methode'])) echo $_GET['methode']; ?>" onclick="return confirm('Voulez-vous vraiment supprimer l\'&eacute;tape: <?php echo $row_liste_etape_plan['intitule']; ?> ?');" /><img src="images/delete.png" width="15" border="0"/></a> </div></td>
                    </tr>

                    <?php } while ($row_liste_etape_plan = mysql_fetch_assoc($liste_etape_plan)); ?>

                    <?php $date_start = date("Y-m-d", strtotime($date_start)); if(isset($date_start) && $totalRows_liste_etape_plan1>0){
                      do { $i++; $date_next = strtotime($date_start." +".$row_liste_etape_plan1['duree_etape']."days"); $etape_0=date("Y-m-d",$date_next);   ?>
                    <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; else echo 'bgcolor="#F9F9F7"'; ?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='<?php if($i%2==0) echo "#ECF0DF"; else echo "#F9F9F7";?>'">
                      <td><div align="left"><span class="Style5"><?php echo $row_liste_etape_plan1['intitule']; ?></span></div></td>
                      <td><div align="center"><span class="Style5"><strong><?php echo date("d/m/Y",strtotime($etape_0)); ?></strong></span></div></td>
                      <td><div align="center"><span class="Style5"><span class="Style6">
                        <?php $Nombres_jours = NbJours($date_start,$etape_0); $date_start = $etape_0;
echo number_format($Nombres_jours-1, 0, ',', ' '); $duree_totale=$duree_totale+number_format($Nombres_jours, 0, ',', ' ')-1; if($etape_0=="00-00-0000") echo "-";
//$etape_0=$row_liste_etape_plan1['date_prevue']; ?></span></span></div></td>
<td>
                          <div align="center">&nbsp;</div></td>

                    </tr>

                    <?php } while ($row_liste_etape_plan1 = mysql_fetch_assoc($liste_etape_plan1)); } ?>

					 <tr >
                      <td colspan="2" bgcolor="#CCCCCC"><div align="right" class="Style6"><strong>Dur&eacute;e totale pr&eacute;vue </strong></div></td>
                      <td bgcolor="#CCCCCC"><div align="center" class="Style6"><strong><?php echo $duree_totale;?></strong></div></td>
                      <td bgcolor="#CCCCCC">&nbsp;</td>
                    </tr>
                    <?php } ?>
                  </table>
				</div>
                <?php if($totalRows_liste_etape_plan==0) {?>
<div class="col-md-6">
				    <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2" onSubmit="return verifform(this,1);">
                      <div id="special">
                        <h3 style="padding: 0px; margin: 0px;" align="center">                           Planification
                        </h3>
                        <table align="center">
                          <tr valign="baseline">
                            <td align="right" valign="top"><span class="Style5"><strong>1<sup>&egrave;re</sup> Etape</strong></span></td>
                            <td colspan="2"><div align="left">
                            <input type="text" size="32" name="etape1" value="<?php echo $row_liste_etape['intitule'];  ?>" class="form-control" />
                            <input type="hidden" name="etape" value="<?php echo $row_liste_etape['id_etape'];  ?>" />
                            </div></td>
                          </tr>
                          <tr valign="baseline">
                            <td align="right" valign="top" nowrap="nowrap"><span class="Style5"><strong>Date pr&eacute;vue </strong></span></td>
                            <td colspan="2"><div align="left">
                                <input type="text" name="date_prevue" value="<?php echo (isset($row_etape_plan_marche["date_prevue"]))?date("d/m/Y",strtotime($row_etape_plan_marche["date_prevue"])):date("d-m-Y"); ?>" size="15" class="form-control" />
                            </div></td>
                          </tr>
                          <?php if(!isset($row_etape_plan_marche["date_prevue"])){ ?>
                          <tr valign="middle">
                            <td align="right" nowrap="nowrap">&nbsp;</td>
                            <td><div align="right">
                                <input name="Envoyer" type="submit" class="inputsubmit" value="<?php if(isset($row_etape_plan_marche["date_prevue"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
                            </div></td>
                            <td><div align="left"> <a title="Annuler la modification" href="<?php echo (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF']."?id_mar=$id_mar"; ?>">
                                <input name="Submit" type="reset" class="inputsubmit" value="Annuler" />
                            </a> </div></td>
                          </tr> <?php } ?>
                        </table>
                        <input type="hidden" name="<?php if(isset($row_etape_plan_marche["date_prevue"])) echo "MM_update"; else echo "MM_insert";  ?>" value="form2" />
                      </div>
				      </form>
                    </div>
                <?php } ?>
                <div class="clear h0">&nbsp;</div>
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