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
  include_once $config->sys_folder."/database/db_connexion.php";

  $dir = './images/avatar/';
  if(!is_dir($dir)) mkdir($dir);

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{
  if ((isset($_POST["MM_update"]))) {

    $id=$_SESSION["clp_id"];
    include "includes/class.upload.php";
    $handle = new upload($_FILES['photo']);
    if ($handle->uploaded && !empty($id))
    {
      //resize to 250 px
      $handle->file_new_name_body = 'img_'.$id;
      $handle->image_resize = true;
      $handle->image_x = 250;
      $handle->image_y = 250;
      $handle->file_auto_rename = true;
      $handle->image_ratio = true;
      $handle->image_convert = 'jpg';
      $handle->file_overwrite = true;
      $handle->process('./images/avatar/');
      if ($handle->processed)
      {
        $img_full_name = $handle->file_dst_name_body.".".$handle->file_dst_name_ext;
        $insertSQL = sprintf("UPDATE ".$database_connect_prefix."personnel SET avatar=%s WHERE id_personnel=%s",
                             GetSQLValueString('./images/avatar/'.$img_full_name, "text"),
                             GetSQLValueString($id, "text"));
        try{
            $Result1 = $pdar_connexion->prepare($insertSQL);
            $Result1->execute();
        }catch(Exception $e){ die(mysql_error_show_message($e)); }
      }
      //terminé
      $handle->clean();
    }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if($handle->processed) $insertGoTo .= "?insert=ok";
    else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

  if ((isset($_POST["MM_update"])))
  {
    $id=$_SESSION["clp_id"];    //, fonction=%s, niveau=%s
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."personnel SET nom=%s, prenom=%s, contact=%s, email=%s, description_fonction=%s, titre=%s WHERE id_personnel=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['prenom'], "text"),
                         GetSQLValueString($_POST['contact'], "int"),
                         GetSQLValueString($_POST['email'], "text"),/*
                         GetSQLValueString($_POST['fonction'], "text"),
  					     GetSQLValueString($_POST['niveau'], "int"), */
                         GetSQLValueString($_POST['description_fonction'], "text"),
                         GetSQLValueString($_POST['titre'], "text"),
                         GetSQLValueString($id, "text"));

    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }

    if($Result1)
    {
      $_SESSION["clp_nom"] = htmlentities($_POST['nom']);
      $_SESSION["clp_prenom"] = htmlentities($_POST['prenom']);
      $_SESSION["clp_mail"] = htmlentities($_POST['email']);
      $_SESSION["clp_contact"] = htmlentities($_POST['contact']);
    }
    if($Result1) $insertGoTo = $_SERVER['PHP_SELF']."?update=ok";
    else $insertGoTo = $_SERVER['PHP_SELF']."?update=no&etape2=etape2&id=$id";
    header(sprintf("Location: %s", $insertGoTo));
  }
  $id_personnel=$_SESSION["clp_id"];

  $query_liste_personnel = "SELECT * FROM ".$database_connect_prefix."personnel WHERE id_personnel='$id_personnel'";
try{
    $liste_personnel = $pdar_connexion->prepare($query_liste_personnel);
    $liste_personnel->execute();
    $row_liste_personnel = $liste_personnel ->fetch();
    $totalRows_liste_personnel = $liste_personnel->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $query_fonction_user = "SELECT * FROM ".$database_connect_prefix."fonction ORDER BY fonction";
try{
    $fonction_user = $pdar_connexion->prepare($query_fonction_user);
    $fonction_user->execute();
    $row_fonction_user = $fonction_user ->fetchAll();
    $totalRows_fonction_user = $fonction_user->rowCount();
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
<style>#mtable tr td {vertical-align: top;}
</style>
<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Mon profil</h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
<tr><td><br /></td></tr>
<tr valign="top">
<td rowspan="5" width="20%" align="center">
<div align="center"><h3><u>Photo de profil</u></h3></div>
<a href="#myModal_add" data-toggle="modal" data-backdrop="static" data-keyboard="false" title="Actualiser l'image de l'utilisateur" onclick="get_content('edit_user_photo.php','<?php echo "id=".$row_liste_personnel['N']; ?>','modal-body_add',this.title);"><img src="<?php  echo (isset($row_liste_personnel["avatar"]) && file_exists($row_liste_personnel["avatar"]))?$row_liste_personnel["avatar"]:"./images/avatar/none.png"; ?>" width="150" height="150" alt="<?php echo $row_liste_personnel['fonction']; ?>"></a>
</td>
      <td>
        <div class="form-group">
          <label for="id" class="col-md-3 control-label">Identifiant <span class="required">*</span></label>
          <div class="col-md-9">
          <input type="text" readonly="readonly" name="id_personnel" id="id" value="<?php echo $row_liste_personnel['id_personnel']; ?>" size="32" class="form-control required" />
          </div>
        </div>
      </td>
      <td>
        <div class="form-group">
          <label for="titre" class="col-md-3 control-label">Titre <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="titre" id="titre" class="form-control required" >
              <option value="M" <?php if (isset($row_liste_personnel['titre']) && !(strcmp("M", $row_liste_personnel['titre']))) {echo "SELECTED";}  ?>>Monsieur</option>
              <option value="Mme" <?php if (isset($row_liste_personnel['titre']) && !(strcmp("Mme", $row_liste_personnel['titre']))) {echo "SELECTED";}  ?>>Madame</option>
			  <option value="Mlle" <?php if (isset($row_liste_personnel['titre']) && !(strcmp("Mlle", $row_liste_personnel['titre']))) {echo "SELECTED";}  ?>>Mademoiselle</option>
			  <option value="Pr" <?php if (isset($row_liste_personnel['titre']) && !(strcmp("Pr", $row_liste_personnel['titre']))) {echo "SELECTED";}  ?>>Professeur</option>
			  <option value="Dr" <?php if (isset($row_liste_personnel['titre']) && !(strcmp("Dr", $row_liste_personnel['titre']))) {echo "SELECTED";}  ?>>Docteur</option>
            </select>
          </div>
        </div>
      </td>
   </tr>

    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="nom" class="col-md-3 control-label">Nom <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="nom" id="nom" value="<?php echo $row_liste_personnel['nom']; ?>" size="32" />
          </div>
        </div>
      </td>
      <td>
        <div class="form-group">
          <label for="prenom" class="col-md-3 control-label">Prenom <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="prenom" id="prenom" value="<?php echo $row_liste_personnel['prenom']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="contact" class="col-md-3 control-label">Contact <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" value="<?php echo $row_liste_personnel['contact']; ?>" name="contact" id="contact" size="32"/>
          </div>
        </div>
      </td>
      <td>
        <div class="form-group">
          <label for="mail" class="col-md-3 control-label">Email <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" value="<?php echo $row_liste_personnel['email']; ?>" name="email" id="mail" size="32"/>
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="fonction" class="col-md-3 control-label">Fonction <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="fonction" id="fonction" class="form-control required">
      <?php foreach($row_fonction_user as $row_fonction_user){ ?>
              <option title="<?php echo $row_fonction_user["description"]; ?>" value="<?php echo $row_fonction_user['fonction']; ?>" <?php if(isset($row_liste_personnel['fonction']) && $row_liste_personnel['fonction']==$row_fonction_user['fonction']) echo 'selected="selected"'; ?>><?php echo $row_fonction_user['fonction']; ?></option> <?php } ?>
            </select>
          </div>
        </div>
    </td>
      <td>
        <div class="form-group">
          <label for="niveau" class="col-md-3 control-label">Niveau d'acc&egrave;s <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="niveau" id="niveau" class="form-control required" disabled="disabled" >
              <option value="2" <?php if (isset($row_liste_personnel['niveau']) && !(strcmp("2", $row_liste_personnel['niveau']))) {echo "SELECTED";}  ?>>Utilisateur</option>
              <option value="1" <?php if (isset($row_liste_personnel['niveau']) && !(strcmp("1", $row_liste_personnel['niveau']))) {echo "SELECTED";}  ?>>Administrateur</option>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <div class="form-group">
          <label for="description_fonction" class="col-md-3 control-label">Description </label>
          <div class="col-md-9">
            <textarea class="form-control" name="description_fonction" id="description_fonction" cols="25"><?php echo $row_liste_personnel['description_fonction']; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr><td><br /></td></tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="Modifier" />
  <input name="MM_update" type="hidden" value="form1" size="32" alt="*">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div> <?php include_once("modal_add.php"); ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>