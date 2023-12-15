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

$editFormAction = $_SERVER['PHP_SELF'];
$currentPage = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {

  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);

}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  $insertSQL = sprintf("INSERT INTO groupe_indicateur (code_groupe, nom_groupe) VALUES (%s, %s)",
					   GetSQLValueString($_POST['code_groupe'], "text"),
					   GetSQLValueString($_POST['nom_groupe'], "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_GET['id'];

	$insertSQL = sprintf("UPDATE groupe_indicateur SET code_groupe=%s, nom_groupe=%s WHERE id_groupe='$c'",
					   GetSQLValueString($_POST['code_groupe'], "text"),
					   GetSQLValueString($_POST['nom_groupe'], "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_unite = "SELECT * FROM groupe_indicateur ORDER BY code_groupe asc";
$liste_unite  = mysql_query($query_liste_unite , $pdar_connexion) or die(mysql_error());
$row_liste_unite = mysql_fetch_assoc($liste_unite);
$totalRows_liste_unite  = mysql_num_rows($liste_unite);

if(isset($_GET["id"])) { $id=$_GET["id"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_unite = "SELECT * FROM groupe_indicateur WHERE id_groupe='$id'";
$edit_unite = mysql_query($query_edit_unite, $pdar_connexion) or die(mysql_error());
$row_edit_unite = mysql_fetch_assoc($edit_unite);
$totalRows_edit_unite = mysql_num_rows($edit_unite);
}

if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sup_cat = "DELETE FROM groupe_indicateur WHERE id_groupe='$id'";
$Result1 = mysql_query($query_sup_cat, $pdar_connexion) or die(mysql_error());
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo));
}

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
<div class="widget-header"> <h4><i class="icon-reorder"></i> Cat&eacute;gorie d'indicateur </h4>
    <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2){?>
<a href="groupe_indicateur.php?add=yes" title="Ajouter un groupe" class="pull-right p11"><i class="icon-plus"> Ajouter une cat&eacute;gorie </i></a>
    <?php } ?>
</div>
</div>

<table width="80%" border="0" align="center" cellspacing="0">
          <tr>
            <td  width="80%" valign="top">              <div align="center">
              <table border="0" width="50%" cellspacing="3" style="border: solid 1px;">
                <tr class="titrecorps2">
                  <td nowrap="NOWRAP"><div align="left"><strong>Code</strong></div></td>
                  <td nowrap="NOWRAP"><div align="left"><strong>Cat&eacute;gorie d'indicateur</strong></div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?> <td colspan="2"><strong>Editer</strong></td> <?php } ?>
                  </tr>
                <?php if($totalRows_liste_unite>0) {$i=0;do { ?>
                <tr <?php if($i%2==0) echo 'bgcolor="#F9F9F7"'; ?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='<?php if($i%2==0) echo '#F9F9F7';  $i=$i+1;?>';">
                  <td><div align="left"><?php echo $row_liste_unite['code_groupe']; ?></div></td>
                  <td><div align="left"><?php echo $row_liste_unite['nom_groupe']; ?></div></td>
				   <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td bgcolor="#D9D9D9"><?php echo "<a href=".$_SERVER['PHP_SELF']."?id=".$row_liste_unite['id_groupe']."><img src='images/edit.png' width='20' height='20' alt='Modifier'></a>" ?></td>
                  <td bgcolor="#D9D9D9"><div align="center"><a href="<?php echo $_SERVER['PHP_SELF']."?id_sup=".$row_liste_unite['id_groupe'].""?>" onclick="return confirm('Voulez-vous vraiment supprimer ?');" /><img src="images/delete.png" width="15"/></a></div></td>
                   <?php } ?>
				  </tr>

                <?php } while ($row_liste_unite = mysql_fetch_assoc($liste_unite)); ?>
                <?php } ?>
              </table>
            </div></td>
            <td  width="50%" <?php if(!isset($_GET['id']) && !isset($_GET['add'])) {?> class="hidden" <?php }?> valign="top" id="add_box1"><div align="center">
              <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="return verifform(this,1);">
                <div id="special">
                  <p>
                    <?php if(isset($_GET['id'])) echo "Modifier la cat&eacute;gorie d'indicateur"; else echo "Nouvelle cat&eacute;gorie d'indicateur" ; ?>
                  </p>
                  <br />
                  <table align="center">
                    <tr valign="baseline">
                      <td align="right" valign="top" nowrap="nowrap"><span class="Style1">Code</span></td>
                      <td colspan="3">
                        <div align="left">
                          <input name="code_groupe" type="text" value="<?php if(isset($_GET['id'])) echo $row_edit_unite['code_groupe'];  ?>" size="32" />
                        </div></td>
                      </tr>
                    <tr valign="baseline">
                      <td align="right" valign="top" nowrap="nowrap"><span class="Style1">Cat&eacute;gorie</span></td>
                      <td colspan="3"><div align="left">
                        <textarea name="nom_groupe" rows="4" cols="50"><?php if(isset($_GET['id'])) echo $row_edit_unite['nom_groupe'];  ?></textarea>
                      </div></td>
                    </tr>
                    <tr valign="baseline">
                      <td colspan="2" align="right" nowrap="nowrap"><div align="right">
                      </div></td>
                      <td>
                        <div align="right">
                          <input name="Envoyer" type="submit" class="inputsubmit" value="<?php if(isset($_GET['id'])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
                        </div></td>
                      <td><div align="left"><a class="button" href="<?php echo $currentPage; ?>">Annuler</a> </div></td>
                    </tr>
                  </table>
                  <input type="hidden" name="<?php if(isset($_GET['id'])) echo "MM_update"; else echo "MM_insert";  ?>" value="form1" />
                </div>
              </form>
            </div></td>
          </tr>
        </table>

<!-- Fin Site contenu ici -->

            </div>

        </div>



        </div>

    </div>

    <?php include_once ("includes/footer.php");?>

</div>

</body>

</html>