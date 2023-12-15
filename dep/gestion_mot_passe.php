<?php
   ///////////////////////////////////////////////
  /*                 SSE                       */
 /*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////

  session_start();
  include_once 'system/configuration.php';
  $config = new Config;
  /*
  include_once $config->sys_folder."/database/credential.php";
  include_once $config->sys_folder."/database/essentiel.php";
  */
  if(!isset($_SESSION["clp_id"]))
  {
    header(sprintf("Location: %s", "./")); exit;
  }

  if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1"))
  {
    $l=$_GET['user']=$_SESSION["clp_id"];
    $p = md5($_POST['password']);
    $query_edit_user = "SELECT * FROM ".$database_connect_prefix."personnel WHERE id_personnel="."'$l' AND pass="."'$p' AND statut=0";
try{
    $edit_user = $pdar_connexion->prepare($query_edit_user);
    $edit_user->execute();
    $row_edit_user = $edit_user ->fetch();
    $totalRows_edit_user = $edit_user->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

    if($_POST['password_n']==$_POST['password_c'] && $totalRows_edit_user>0)
    {
      $pass = md5($_POST['password_n']);
      $date=date("Y-m-d H:i:s"); $c=$_SESSION["clp_id"];   //, login_user=%s, caisse_user=%s
      $insertSQL = sprintf("UPDATE ".$database_connect_prefix."personnel SET pass=%s,  date_modification='$date' WHERE id_personnel='$c'",
      					   GetSQLValueString($pass, "text"));

      try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
      }catch(Exception $e){ die(mysql_error_show_message($e)); }

      $insertGoTo =$_SERVEUR["PHP_SELF"];
      if ($Result1) $insertGoTo .= "?pass=ok"; else $insertGoTo .= "?pass=no";
      header(sprintf("Location: %s", $insertGoTo));
    }
    else $insertGoTo .= "?pass=no";
    header(sprintf("Location: %s", $insertGoTo));
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
<style>#mtable tr td {vertical-align: middle;}</style>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Changement de mot de passe</h4> </div>
<div class="widget-content">
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate" class="form-horizontal">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
<tr><td><br /></td></tr>
<tr>
      <td valign="top">
        <div class="form-group">
          <label for="password" class="col-md-5 control-label">Mot de passe actuel <span class="required">*</span></label>
          <div class="col-md-5">
          <input type="password" name="password" id="password" value="" size="32" class="form-control required" />
          </div>
        </div>
      </td>
      <td>
      </td>
   </tr>

    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="password_n" class="col-md-5 control-label">Nov. Mot de passe <span class="required">*</span></label>
          <div class="col-md-5">
            <input class="form-control required" type="password" name="password_n" id="password_n" value="" minlength="6" size="32" />
          </div>
        </div>
      </td>
      <td valign="top">
        <div class="form-group">
          <label for="password_c" class="col-md-5 control-label">Comfirmation <span class="required">*</span></label>
          <div class="col-md-5">
            <input class="form-control required" type="password" name="password_c" id="password_c" value="" equalTo="[name='password_n']" minlength="6" size="32" />
          </div>
        </div>
      </td>
    </tr>
    <tr><td><br /></td></tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="Enregistrer" />
  <input name="MM_update" type="hidden" value="form1" size="32" alt="*">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>