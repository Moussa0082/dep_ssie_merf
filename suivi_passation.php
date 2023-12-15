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

$poids_max=2048576; //Poids maximal du fichier en octets
$extensions_autorisees=array('rar','doc','pdf', 'zip', 'docx', 'xlsx'); //Extensions autorisées
$url_site='./attachment/'; //Adresse où se trouve le fichier upload.php

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
$edit_marche = mysql_query($query_edit_marche, $pdar_connexion) or die(mysql_error());
$row_edit_marche = mysql_fetch_assoc($edit_marche);
$totalRows_edit_marche = mysql_num_rows($edit_marche);

$per=$row_edit_marche['periode'];
$methode=$row_edit_marche['id_methode'];
$cat=$row_edit_marche['categorie'];

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_la_periode = "SELECT * FROM periode_marche where id_periode='$per'";
$la_periode = mysql_query($query_la_periode, $pdar_connexion) or die(mysql_error());
$row_la_periode = mysql_fetch_assoc($la_periode);
$totalRows_la_periode = mysql_num_rows($la_periode);


if(isset($_GET["id_sup_doc"])){ $id_doc = $_GET["id_sup_doc"];

$query_sup_set = "DELETE FROM document_suivi_marche WHERE id_document_suivi='$id_doc'";

$Result1 = mysql_query($query_sup_set, $pdar_connexion) or die(mysql_error());
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?id_mar=$id_mar&insert=ok"; else $insertGoTo .= "?id_mar=$id_mar&insert=no";
  if (isset($_GET["cat"])) $insertGoTo .= "&cat=".$_GET["cat"];
  if (isset($_GET["methode"])) $insertGoTo .= "&methode=".$_GET["methode"];
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$e=$_POST['etape'];
$m=$_GET['id_mar'];
$query_sup_set = "DELETE FROM suivi_plan_marche WHERE etape='$e' and marche='$m'";
$Result1 = mysql_query($query_sup_set, $pdar_connexion) or die(mysql_error());

  $insertSQL = sprintf("INSERT INTO suivi_plan_marche (marche, etape, date_reelle, id_personnel, date_enregistrement) VALUES (%s, %s, %s,'$personnel', '$date')",

                       GetSQLValueString($_GET['id_mar'], "int"),
					   GetSQLValueString($_POST['etape'], "int"),
					   GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_reelle']))), "date"));


  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?id_mar=$id_mar&insert=ok"; else $insertGoTo .= "?id_mar=$id_mar&insert=no";
  if (isset($_GET["cat"])) $insertGoTo .= "&cat=".$_GET["cat"];
  if (isset($_GET["methode"])) $insertGoTo .= "&methode=".$_GET["methode"];
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {

if($_FILES['fichier1']['size']>$poids_max)
	{
		$message='Un ou plusieurs fichiers sont trop lourds !';
		echo $message;
	}
	else
	{
            $nom1='attachment/'.$_FILES['fichier1']['name'];
			move_uploaded_file($_FILES['fichier1']['tmp_name'],$nom1);

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$m=$_GET['id_mar'];
$query_sup_smm = "DELETE FROM suivi_montant_marche WHERE marche='$m'";
$Result1 = mysql_query($query_sup_smm, $pdar_connexion) or die(mysql_error());

  $insertSQL = sprintf("INSERT INTO suivi_montant_marche (marche, montant_local, date_validation, proces_verbal, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s,'$personnel', '$date')",

                       GetSQLValueString($_GET['id_mar'], "int"),
					   GetSQLValueString($_POST['montant_local'], "double"),
					   GetSQLValueString(date("Y-m-d"), "date"),
					   GetSQLValueString($_FILES['fichier1']['name'], "text"));
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());

  if(isset($_FILES['fichier1']['name'])){

  $insertSQL = sprintf("INSERT INTO document_suivi_marche (marche, proces_verbal, id_personnel, date_enregistrement) VALUES (%s, %s,'$personnel', '$date')",
                       GetSQLValueString($_GET['id_mar'], "int"),
					   GetSQLValueString($_FILES['fichier1']['name'], "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error()); }
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?id_mar=$id_mar&insert=ok"; else $insertGoTo .= "?id_mar=$id_mar&insert=no";
  if (isset($_GET["cat"])) $insertGoTo .= "&cat=".$_GET["cat"];
  if (isset($_GET["methode"])) $insertGoTo .= "&methode=".$_GET["methode"];
  header(sprintf("Location: %s", $insertGoTo));
}}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_m_marche = "SELECT * FROM suivi_montant_marche where marche='$id_mar'";
$edit_m_marche = mysql_query($query_edit_m_marche, $pdar_connexion) or die(mysql_error());
$row_edit_m_marche = mysql_fetch_assoc($edit_m_marche);
$totalRows_edit_m_marche = mysql_num_rows($edit_m_marche);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_m_marche_doc = "SELECT * FROM document_suivi_marche where marche='$id_mar'";
$edit_m_marche_doc = mysql_query($query_edit_m_marche_doc, $pdar_connexion) or die(mysql_error());
$row_edit_m_marche_doc = mysql_fetch_assoc($edit_m_marche_doc);
$totalRows_edit_m_marche_doc = mysql_num_rows($edit_m_marche_doc);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_etape_plan = "SELECT * FROM etape_marche inner join etape_plan_marche on etape_plan_marche.marche='$id_mar' where categorie='$cat'  and ordre=1 GROUP BY id_etape ORDER BY ordre asc";
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
   }

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
<div class="widget-header"> <h4><i class="icon-reorder"></i> Suivi du plan de passation de March&eacute; </h4>
    <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){?>
<?php
echo do_link("",((isset($_GET["page"]) && $_GET["page"]!="")?$_GET["page"]:"plan_marche.php").((isset($_GET['id_mar']))?"?periode=".$row_edit_marche['periode']."&id_mar=".$_GET["id_mar"]:"").((isset($_GET['methode']))?"&methode=".$_GET["methode"]:"")."&cat=".((isset($_GET['cat']))?$_GET['cat']:""),"Retour","Retour","","./","pull-right p11","",1,"margin:0px 5px;",$nfile); ?>
    <?php } ?>
<!--<div class="r_float" style="float: right" id="add_box"><a href="<?php if(isset($_GET["page"]) && $_GET["page"]!="") echo $_GET["page"]; else echo "plan_marche.php"; if(isset($_GET['id_mar'])) echo "?periode=".$row_edit_marche['periode']."&id_mar=".$_GET["id_mar"]; if(isset($_GET['methode'])) echo "&methode=".$_GET["methode"]; ?>&cat=<?php if(isset($_GET['cat'])) echo $_GET['cat'];  ?>" class="btn btn-success pull-right">Retour</a></div> -->
</div>
<div class="widget-content">
        <table width="100%" border="0" align="center" cellspacing="0">

          <tr>

            <td valign="top">              <div align="center">

            <table border="0" width="100%" align="center" cellpadding="1" cellspacing="3" bordercolor="#D9D9D9" bgcolor="#D9D9D9">

            <tr>

            <td width="40%">

                      <table border="0" width="80%" align="center" cellpadding="1" cellspacing="3" bordercolor="#D9D9D9" bgcolor="#D9D9D9">

                      <tr>

                      <th scope="col"><div align="right"><strong>P&eacute;riode&nbsp;&nbsp;</strong></div></th>

                      <th nowrap="nowrap" bgcolor="#FFFFFF" scope="col"><div align="left">

                        <?php if(isset($_GET['id_mar'])) echo $row_la_periode['debut']." - ".$row_la_periode['fin'];  ?>

                      </div></th>

                    </tr>

                    <tr>

                      <td><div align="right"><strong>Intitul&eacute; du march&eacute;&nbsp;&nbsp;</strong></div></td>

                      <td bgcolor="#FFFFFF"><?php if(isset($_GET['id_mar'])) echo $row_edit_marche['intitule'];  ?></td>

                    </tr>

                    <tr>

                      <td><div align="right"><strong>Nb lot &nbsp;&nbsp;</strong></div></td>

                      <td bgcolor="#FFFFFF"><?php if(isset($_GET['id_mar'])) echo $row_edit_marche['lot'];  ?></td>

                    </tr>

                    <tr>

                      <td><div align="right"><strong>Cat&eacute;gorie&nbsp;&nbsp;</strong></div></td>

                      <td bgcolor="#FFFFFF"><div align="left">

                          <?php if(isset($_GET['id_mar'])) echo $row_edit_marche['nom_categorie'];  ?>

                      </div></td>

                    </tr>

                    <tr>

                      <td><div align="right"><strong>M&eacute;thode&nbsp;&nbsp;</strong></div></td>

                      <td bgcolor="#FFFFFF"><?php if(isset($_GET['id_mar']) && isset($methode_array[$row_edit_marche['methode']])) echo $methode_array[$row_edit_marche['methode']];  ?></td>

                    </tr>

                    <tr>

                      <td><div align="right"><strong>Description&nbsp;&nbsp;</strong></div></td>

                      <td bgcolor="#FFFFFF"><?php if(isset($_GET['id_mar'])) echo $row_edit_marche['description'];  ?></td>

                    </tr>
                    <tr>

                      <td><div align="right"><strong>Montant estimatif US ($)&nbsp;&nbsp;</strong></div></td>

                      <td bgcolor="#FFFFFF"><?php if(isset($_GET['id_mar'])) echo number_format($row_edit_marche['montant_usd'], 0, ',', ' ');  ?></td>

                    </tr></table>

                      </td>

            <td width="40%" valign="top"><form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3" enctype="multipart/form-data">
                          <table width="100%" border="0" align="center" cellspacing="3">

                              <tr valign="baseline" bgcolor="#B1C3D9">

                                <td align="right" valign="top"><strong>Montant</strong></td>

                                <td><strong>Estimatifs</strong></td>

                                <td><strong>R&eacute;els</strong></td>

                                <td><strong>Ecart</strong></td>

                              </tr>

                              <tr valign="baseline" bgcolor="#FFFFFF">

                                <td align="right" valign="top"><strong>(F CFA)</strong></td>

                                <td><div align="center"><strong>

                                  <?php if(isset($_GET['id_mar'])) echo number_format($row_edit_marche['montant_local'], 0, ',', ' ');  ?>

                                </strong></div></td>

                                <td><div align="left">

                                  <input type="text" name="montant_local" value="<?php if(isset($row_edit_m_marche['montant_local'])) echo $row_edit_m_marche['montant_local'];  ?>" size="15" />

                              &nbsp;</div></td>

                                <td><div align="center"><span class="red Style9"><strong></strong></span><span <?php if(isset($row_edit_m_marche['montant_local']) && $row_edit_m_marche['montant_local']>$row_edit_marche['montant_local']) { echo "style=\"color:#FF0000\"";} else {echo "style=\"color:#339900\"";}?> ><strong>

                                  <?php if(isset($_GET['id_mar']) && isset($row_edit_m_marche['montant_local'])) echo number_format($row_edit_m_marche['montant_local']-$row_edit_marche['montant_local'], 0, ',', ' '); //echo "&nbsp;&nbsp;(".number_format(($row_edit_m_marche['montant_local'])/$row_edit_marche['montant_local'], 2, ',', ' ')."%)";  ?>

                                </strong></span></div></td>

                              </tr>

                              <tr valign="baseline" bgcolor="#FFFFFF">

                                <td rowspan="2" align="right" valign="middle" nowrap="nowrap"><span class="Style7">Documents</span></td>

                                <td colspan="3"><strong><span class="Style10">

                                </span></strong></td>

                              </tr>

                              <tr valign="baseline">

                                <td colspan="3" bgcolor="#fff"><input type="file" name="fichier1" id="fichier1" size="5" />

                          <input type="hidden" name="MAX_FILE_SIZE" value="20485760" /></td>

                              </tr>

                              <tr valign="baseline" bgcolor="#fff">

                                <td colspan="4" align="right" nowrap="nowrap"><span class="Style7">&nbsp;</span></td>

                              </tr>

                              <tr valign="baseline" bgcolor="#fff">

                                <td align="center" nowrap="nowrap">&nbsp;</td>

                                <td colspan="4"><div align="center">

                                  <input name="Envoyer" type="submit" class="btn btn-success " value="Valider" />

                                </div></td>

                              </tr>

                            </table>

                            <input type="hidden" name="<?php if(isset($_GET['id_par'])) echo "MM_update"; else echo "MM_insert";  ?>" value="form3" />



                      </form></td>

                     <td width="20%" valign="top" align="center"><u>Les pi&egrave;ces justificatives</u><br />

                     <table border="0" width="100%" cellspacing="3">



                     <?php

                     if(isset($totalRows_edit_m_marche_doc) && $totalRows_edit_m_marche_doc>0){ $j=0; do{  echo ($j%2==0)?"<tr bgcolor='#F5F5F5'>":"<tr>"; $del='<a href="'.$_SERVER['PHP_SELF'].'?id_sup_doc='.$row_edit_m_marche_doc['id_document_suivi'].'&id_mar='.$_GET["id_mar"].'" onclick="return confirm(\'Voulez-vous vraiment supprimer le fichier:'.$row_edit_m_marche_doc['proces_verbal'].'\');" /><img src="images/delete.png" width="15" border="0"/></a>';

                      if(isset($row_edit_m_marche_doc['proces_verbal'])) { $rep="./attachment/"; $extension=substr(strrchr($row_edit_m_marche_doc['proces_verbal'], '.')  ,1); if ($extension=="doc" || $extension=="docx") { echo("<td><a href='".$rep.$row_edit_m_marche_doc['proces_verbal']."'><img src='images/doc.png' width='15'/> </a></td><td>".$row_edit_m_marche_doc['proces_verbal']."</td><td>".$del."</td>");

										} elseif ($extension=="xls" || $extension=="xlsx") { echo("<td><a href='".$rep.$row_edit_m_marche_doc['proces_verbal']."'><img src='images/xls.png' width='15'/> </a></td><td>".$row_edit_m_marche_doc['proces_verbal']."</td><td>".$del."</td>");} elseif ($extension=="pdf") { echo("<td><a href='".$rep.$row_edit_m_marche_doc['proces_verbal']."'><img src='images/pdf.png' width='15'/> </a></td><td>".$row_edit_m_marche_doc['proces_verbal']."</td><td>".$del."</td>");} elseif ($extension=="zip") { echo("<td><a href='".$rep.$row_edit_m_marche_doc['proces_verbal']."'><img src='images/zipicon.jpg' width='15'/> </a></td><td>".$row_edit_m_marche_doc['proces_verbal']."</td><td>".$del."</td>");

										} }  echo "&nbsp;&nbsp;";

                     echo "</tr>"; $j++; }while($row_edit_m_marche_doc = mysql_fetch_assoc($edit_m_marche_doc));  }else echo "<tr><td align='center' colspan='3'>Aucun !</td><tr>";  ?>

                     </table>

                     </td>

            </tr>

                  </table>

              <table border="0" width="100%" cellspacing="3">

				<tr>

				  <td valign="top"><table width="100%" border="0" align="left" cellspacing="3">

                    <?php $i=0; if(isset($totalRows_liste_etape_plan) && $totalRows_liste_etape_plan>0) {?>

                    <tr class="titrecorps2" style="background-color: grey; color: white;">

                      <td rowspan="2"><div align="left"><span class="Style5"><strong>Intitul&eacute; de l'&eacute;tape </strong></span></div></td>

                      <td rowspan="2" bgcolor="#D2E2B1">&nbsp;</td>

                      <td colspan="2"><div align="center"><span class="Style5"><strong>Pr&eacute;vision</strong></span></div></td>

                      <td rowspan="2" bgcolor="#D2E2B1">&nbsp;</td>

                      <td colspan="2"><span class="Style5"><strong>Suivi</strong></span></td>

                      <td rowspan="2" nowrap="nowrap" bgcolor="#D2E2B1">&nbsp;</td>

                      <td rowspan="2" nowrap="nowrap"><span class="Style5"></span><span class="Style5"><strong>Ecart (J) </strong></span></td>

                    </tr>

                    <tr class="titrecorps2" style="background-color: grey; color: white;">

                      <td><strong class="Style5">Date </strong></td>

                      <td nowrap="nowrap" class="Style7">Dur&eacute;e (j) </td>

                      <td><strong class="Style5">Date </strong></td>

                      <td nowrap="nowrap"><span class="Style7">Dur&eacute;e (j)</span></td>

                    </tr>

                    <?php $duree_totale=0; $etape_0="00-00-0000"; $duree_suivie=0; $etape_0s="00-00-0000"; do { $i++; $date_start = $row_liste_etape_plan['date_prevue'];  ?>

                    <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; else echo 'bgcolor="#F9F9F7"'; ?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='<?php if($i%2==0) echo "#ECF0DF"; else echo "#F9F9F7";?>'">

                      <td><div align="left"><span class="Style5"><?php echo $row_liste_etape_plan['intitule']; ?></span></div></td>

                      <td nowrap="nowrap" bgcolor="#D2E2B1">&nbsp;</td>

                      <td><div align="center"><span class="Style5"><strong><?php echo date("d/m/Y", strtotime($row_liste_etape_plan['date_prevue']));

					  ?></strong></span></div></td>

                      <td><div align="center"><span class="Style5"><span class="Style6">

                        <?php if(isset($row_liste_etape_plan['date_prevue']) && $row_liste_etape_plan['date_prevue']>=$etape_0 && $etape_0!="00-00-0000") { $Nombres_jours = NbJours($etape_0, $row_liste_etape_plan['date_prevue']);

echo number_format($Nombres_jours-1, 0, ',', ' '); $duree_totale=$duree_totale+number_format($Nombres_jours, 0, ',', ' ')-1;} if($etape_0=="00-00-0000") echo "-"; $etape_0=$row_liste_etape_plan['date_prevue']; ?></span></span></div></td>

                      <td bgcolor="#D2E2B1">&nbsp;</td>



<?php



$etape = $row_liste_etape_plan['id_etape'];

mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_duree = "SELECT * FROM suivi_plan_marche where etape='$etape' and marche='$id_mar'";

$liste_duree  = mysql_query($query_liste_duree , $pdar_connexion) or die(mysql_error());

$row_liste_duree  = mysql_fetch_assoc($liste_duree);

$totalRows_liste_duree  = mysql_num_rows($liste_duree);  ?>

                      <td><form style="margin: 0px;" action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">



                          <table align="center">

                            <tr valign="baseline">

                              <td><div align="left">

                                  <input type="text" class="" name="date_reelle" <?php if(!isset($row_liste_duree['date_reelle']) || $row_liste_duree['date_reelle']=="0000-00-00") {?>style="border-color:#FF0000"<?php }?> value="<?php if(isset($row_liste_duree['date_reelle']) && $row_liste_duree['etape']==$row_liste_etape_plan['id_etape']) echo implode('/',array_reverse(explode('-',$row_liste_duree['date_reelle']))); else echo date("d/m/Y"); ?>" size="10" />

                                                          </div></td>

                              <td valign="middle"><input name="Envoyer" type="submit"  value=">>" style="background-color:#FFFF00 " /></td>

                            </tr>

                          </table>

                          <input type="hidden" name="<?php echo "MM_insert";  ?>" value="form2" />

                          <input type="hidden" name="etape" value="<?php echo $row_liste_etape_plan['id_etape']; ?>" />

                      </form></td>

                      <td><div align="center"><span class="Style6">

                        <?php if(isset($row_liste_duree['date_reelle'])  && $row_liste_duree['date_reelle']>=$etape_0s && $etape_0s!="00-00-0000") { $Nombres_jours_s = NbJours($etape_0s, $row_liste_duree['date_reelle']);

echo number_format($Nombres_jours_s-1, 0, ',', ' '); $duree_suivie=$Nombres_jours_s-1;} if($etape_0s=="00-00-0000" || !isset($etape_0s)) echo "-"; if(isset($row_liste_duree['date_reelle'])) $etape_0s=$row_liste_duree['date_reelle']; ?>

                      </span></div></td>

                      <td bgcolor="#D2E2B1">&nbsp;</td>

                      <td> <div align="center"><strong><span  <?php if(isset($row_liste_duree['date_reelle']) && $row_liste_duree['date_reelle']>$row_liste_etape_plan['date_prevue']) { echo "style=\"color:#FF0000\"";} else {echo "style=\"color:#339900\"";}?> >

                        <?php if (isset($row_liste_etape_plan['date_prevue'])&& isset($row_liste_duree['date_reelle']) && $row_liste_duree['date_reelle']!="0000-00-00") { $Nombres_jours = NbJours($row_liste_etape_plan['date_prevue'], $row_liste_duree['date_reelle']);

// Affiche 2

if(0>($Nombres_jours-1)) echo number_format(-1*($Nombres_jours-1), 0, ',', ' '); else echo number_format($Nombres_jours-1, 0, ',', ' ');} ?>

                      </span></strong></div></td>

                    </tr>



                    <?php } while ($row_liste_etape_plan = mysql_fetch_assoc($liste_etape_plan)); ?>



                    <?php $date_start = date("Y-m-d", strtotime($date_start)); if(isset($date_start) && $totalRows_liste_etape_plan1>0){ do { $i++;  $date_next = strtotime($date_start." +".$row_liste_etape_plan1['duree_etape']."days"); $etape_0=date("Y-m-d",$date_next);   ?>

                    <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; else echo 'bgcolor="#F9F9F7"'; ?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='<?php if($i%2==0) echo "#ECF0DF"; else echo "#F9F9F7";?>'">

                      <td><div align="left"><span class="Style5"><?php echo $row_liste_etape_plan1['intitule']; ?></span></div></td>

                      <td nowrap="nowrap" bgcolor="#D2E2B1">&nbsp;</td>

                      <td><div align="center"><span class="Style5"><strong><?php echo date("d/m/Y",strtotime($etape_0)); $etape_s=date("Y-m-d",strtotime($etape_0)); ?></strong></span></div></td>

                      <td><div align="center"><span class="Style5"><span class="Style6">

                        <?php $Nombres_jours = NbJours($date_start,$etape_0); $date_start = $etape_0;

echo number_format($Nombres_jours-1, 0, ',', ' '); $duree_totale=$duree_totale+number_format($Nombres_jours, 0, ',', ' ')-1; if($etape_0=="00-00-0000") echo "-"; ?></span></span></div></td>

                    <td nowrap="nowrap" bgcolor="#D2E2B1">&nbsp;</td>

                    <?php

$etape = $row_liste_etape_plan1['id_etape'];

mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_duree = "SELECT * FROM suivi_plan_marche where etape='$etape' and marche='$id_mar'";

$liste_duree  = mysql_query($query_liste_duree , $pdar_connexion) or die(mysql_error());

$row_liste_duree  = mysql_fetch_assoc($liste_duree);

$totalRows_liste_duree  = mysql_num_rows($liste_duree);

                      ?>

                    <td><form style="margin: 0px;" action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">



                          <table align="center">

                            <tr valign="baseline">

                              <td><div align="left">

                                  <input type="text" name="date_reelle" <?php if(!isset($row_liste_duree['date_reelle']) || $row_liste_duree['date_reelle']=="0000-00-00") {?>style="border-color:#FF0000"<?php }?> value="<?php if(isset($row_liste_duree['date_reelle']) && $row_liste_duree['etape']==$row_liste_etape_plan1['id_etape']) echo implode('/',array_reverse(explode('-',$row_liste_duree['date_reelle']))); else echo date("d/m/Y"); ?>" size="10" class="form-control" />

                                                          </div></td>

                              <td valign="middle"><input name="Envoyer" type="submit"  value=">>" style="background-color:#FFFF00 " /></td>

                            </tr>

                          </table>

                          <input type="hidden" name="<?php echo "MM_insert";  ?>" value="form2" />

                          <input type="hidden" name="etape" value="<?php echo $row_liste_etape_plan1['id_etape']; ?>" />

                      </form></td>

                      <td><div align="center"><span class="Style6">

                        <?php if(isset($row_liste_duree['date_reelle'])) { $Nombres_jours_s = NbJours($etape_0s, $row_liste_duree['date_reelle']); $etape_0s=$row_liste_duree['date_reelle'];

echo number_format($Nombres_jours_s-1, 0, ',', ' '); $duree_suivie+=$Nombres_jours_s-1;} if($etape_0s=="00-00-0000" || !isset($etape_0s)) echo "-"; ?>

                      </span></div></td>

                      <td bgcolor="#D2E2B1">&nbsp;</td>

                      <td> <div align="center"><strong><span  <?php if(isset($row_liste_duree['date_reelle']) && $row_liste_duree['date_reelle']<$etape_0s) { echo "style=\"color:#FF0000\"";} else {echo "style=\"color:#339900\"";}?> >

                        <?php if (isset($etape_0s)&& isset($row_liste_duree['date_reelle']) && $row_liste_duree['date_reelle']!="0000-00-00") { $Nombres_jours = NbJours($etape_0s, $row_liste_duree['date_reelle']);

// Affiche 2

$Nombres_jours = NbJours($etape_s, $row_liste_duree['date_reelle']);

if(0>($Nombres_jours-1)) echo number_format(-1*($Nombres_jours-1), 0, ',', ' '); else echo number_format($Nombres_jours-1, 0, ',', ' ');} ?>

                      </span></strong></div></td>

                    </tr>



                    <?php } while ($row_liste_etape_plan1 = mysql_fetch_assoc($liste_etape_plan1)); } ?>

					 <tr>

                      <td nowrap="nowrap" bgcolor="#CCCCCC"><div align="right"><strong>Dur&eacute;e totale (Jours) </strong></div></td>

                      <td nowrap="nowrap" bgcolor="#CCCCCC">&nbsp;</td>

                      <td colspan="2" nowrap="nowrap" bgcolor="#CCCCCC"><div align="center">

                        <div align="center" class="Style6"><strong><?php echo $duree_totale;?></strong></div>

                      </div></td>

                      <td bgcolor="#D2E2B1">&nbsp;</td>

                      <td colspan="2" bgcolor="#CCCCCC"><div align="center">

                          <div align="center" class="Style6"><strong><?php echo number_format($duree_suivie, 0, ',', ' ');?></strong></div>

                      </div></td>

                      <td bgcolor="#D2E2B1" <?php if($duree_suivie>$duree_totale) { echo "style=\"color:#FF0000\"";} else {echo "style=\"color:#339900\"";}?>>&nbsp;</td>

                      <td bgcolor="#CCCCCC" <?php if($duree_suivie>$duree_totale) { echo "style=\"color:#FF0000\"";} else {echo "style=\"color:#339900\"";}?>>

                          <div align="center"><strong><?php echo number_format($duree_totale-$duree_suivie, 0, ',', ' ');?></strong></div></td>

                    </tr>

                    <?php } ?>

                  </table></td>

				</tr>

				<tr>

				  <td><div align="center">

				    </div></td>

				  </tr>

              </table>

            </div></td>

            <td valign="top">

              </td>

          </tr>

        </table>
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