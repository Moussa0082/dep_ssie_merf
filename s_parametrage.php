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
include_once $config->sys_folder . "/database/db_connexion.php";


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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
 <style>
 .Style31{
   color: white;
 }


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
        <table width="400" border="0" align="center" cellpadding="0" cellspacing="0" >
          <tr align="center">
            <td width="32%"><div id="sousmenu">
                <p id="sp">Param&eacute;trages </p>
            </div></td>
            </tr>
          <tr>
            <td bgcolor="#506429"><span class="Style31">Acteurs </span></td>
          </tr>

          <tr>
            <td><table width="100%" align="center" cellpadding="0" cellspacing="0" bgcolor="#D2E2B1">
              <tr>
                <td width="10%"><strong><img src="./images/go_next_over.png" width="19" height="21" /></strong></td>
                <td class="Style30"><div align="left"><a id="sp_a" href="../print_users_excel.php">Liste des utilisateurs  </a></div></td>
              </tr>
            </table>
            </td>
            </tr>

          <tr>
            <td><table width="100%" align="center" cellpadding="0" cellspacing="0" bgcolor="#D2E2B1">
              <tr>
                <td width="10%"><strong><img src="./images/go_next_over.png" width="19" height="21" /></strong></td>
                <td class="Style30"><div align="left"><a id="sp_a" href="print_acteur.php">Liste des acteurs  </a></div></td>
              </tr>
            </table>
            </td>
            </tr>
          <tr>
            <td bgcolor="#506429"><span class="Style31">R&eacute;f&eacute;rentiels </span></td>
          </tr>

          <tr>
            <td><table width="100%" align="center" cellpadding="0" cellspacing="0" bgcolor="#D2E2B1">
              <tr>
                <td width="10%"><strong><img src="./images/go_next_over.png" width="19" height="21" /></strong></td>
                <td class="Style30"><div align="left"><a id="sp_a" href="liste_indicateur_ref.php">Tous les indicateurs référentiels du PNF</a> </div></td>
              </tr>
            </table>
              </td>
          </tr>
          <tr>
            <td><table width="100%" align="center" cellpadding="0" cellspacing="0" bgcolor="#ECF2DE">
              <tr>
                <td width="10%"><strong><img src="./images/go_next_over.png" width="19" height="21" /></strong></td>
                <td class="Style30"><div align="left"><a id="sp_a" href="liste_indicateur_ref_projet.php">CMR et indicateurs r&eacute;f&eacute;rentiels du Projet </a> </div></td>
              </tr>
            </table>
              </td>
          </tr>
          <tr>
            <td bgcolor="#506429"><span class="Style31">COSOP </span></td>
          </tr>

          <tr>
            <td><table width="100%" align="center" cellpadding="0" cellspacing="0" bgcolor="#D2E2B1">
              <tr>
                <td width="10%"><strong><img src="./images/go_next_over.png" width="19" height="21" /></strong></td>
                <td class="Style30"><div align="left"><a id="sp_a" href="liste_indicateur_resultat_cosop.php">Indicateurs d'effets (dont SYGRI Niveau II) </a> </div></td>
              </tr>
            </table>
              </td>
          </tr>
          <tr>
            <td><table width="100%" align="center" cellpadding="0" cellspacing="0" bgcolor="#ECF2DE">
              <tr>
                <td width="10%"><strong><img src="./images/go_next_over.png" width="19" height="21" /></strong></td>
                <td class="Style30"><div align="left"><a id="sp_a" href="liste_indicateur_produit_cosop.php">Indicateurs de produits (dont SYGRI Niveau I)  </a> </div></td>
              </tr>
            </table>
              </td>
          </tr>
           <tr>
            <td bgcolor="#506429"><span class="Style31">Initiative 3N </span></td>
          </tr>

          <tr>
            <td><table width="100%" align="center" cellpadding="0" cellspacing="0" bgcolor="#D2E2B1">
              <tr>
                <td width="10%"><strong><img src="./images/go_next_over.png" width="19" height="21" /></strong></td>
                <td class="Style30"><div align="left"><a id="sp_a" href="liste_indicateur_I3N_PNF.php">Indicateurs I3N du PNF </a> </div></td>
              </tr>
            </table>
              </td>
          </tr>

          <tr>
            <td><table align="center" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td><div id="sousmenu">
                      <p id="sp">&nbsp;</p>
                  </div></td>
                </tr>
            </table></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            </tr>
        </table>
<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div> <?php include_once("modal_add.php"); ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>