<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: COSIT */
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
$page = $_SERVER['PHP_SELF'];
if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
$query_sup_tache = "DELETE FROM ".$database_connect_prefix."programmes_ccc WHERE id_programmes='$id'";
try{
    $Result = $pdar_connexion->prepare($query_sup_tache);
    $Result->execute();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($Result) $lien =$page."?del=ok"; else $lien=$page."?del=no";
header(sprintf("Location: %s", $lien));
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."programmes_ccc (sigle_programme, nom_programme, vision, objectif, annee_debut, annee_fin, actif) VALUES (%s, %s, %s,%s, %s, %s, %s)",
               GetSQLValueString($_POST['sigle_programme'], "text"),
			   GetSQLValueString($_POST['nom_programme'], "text"),
			   GetSQLValueString($_POST['vision'], "text"),
			   GetSQLValueString($_POST['objectif'], "text"),
               GetSQLValueString($_POST['annee_debut'], "int"),
               GetSQLValueString($_POST['annee_fin'], "int"),
               GetSQLValueString($_POST['actif'], "int"));
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  if($Result1){  $id = mysql_insert_id();
    include "includes/class.upload.php";
    $handle = new upload($_FILES['photo']);
    if ($handle->uploaded)
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
      $handle->process('./images/programmes_2qc/');   /*
      if ($handle->processed)
      {
        $img_full_name = $handle->file_dst_name_body.".".$handle->file_dst_name_ext;
      }  */
      //terminé
      $handle->clean();
    }
  }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo));   exit(0);
  }
  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
      $id = intval($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."programmes_ccc WHERE id_programmes=%s",
                           GetSQLValueString($id, "int"));
      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }
  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id'];
  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."programmes_ccc SET sigle_programme=%s, nom_programme=%s, vision=%s, objectif=%s, annee_debut=%s, annee_fin=%s, actif=%s WHERE id_programmes='$c'",
               GetSQLValueString($_POST['sigle_programme'], "text"),
			   GetSQLValueString($_POST['nom_programme'], "text"),
			   GetSQLValueString($_POST['vision'], "text"),
			   GetSQLValueString($_POST['objectif'], "text"),
               GetSQLValueString($_POST['annee_debut'], "int"),
               GetSQLValueString($_POST['annee_fin'], "int"),
               GetSQLValueString($_POST['actif'], "int"));
 /* mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
*/
  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
 /* $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);*/


if($Result1){
    include "includes/class.upload.php";
    $handle = new upload($_FILES['photo']);
    if ($handle->uploaded)
    {
      //resize to 250 px
      $handle->file_new_name_body = 'img_'.$c;
      $handle->image_resize = true;
      $handle->image_x = 250;
      $handle->image_y = 250;
      $handle->file_auto_rename = true;
      $handle->image_ratio = true;
      $handle->image_convert = 'jpg';
      $handle->file_overwrite = true;
      $handle->process('./images/programmes_2qc/');   /*
      if ($handle->processed)
      {
        $img_full_name = $handle->file_dst_name_body.".".$handle->file_dst_name_ext;
      }  */
      //terminé
      $handle->clean();
    }
  }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{
  if ((isset($_POST["MM_update"]))) {
    $id=$_POST['id'];
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
      $handle->process('./images/programmes_2qc/');   /*
      if ($handle->processed)
      {
        $img_full_name = $handle->file_dst_name_body.".".$handle->file_dst_name_ext;
      }  */
      //terminé
      $handle->clean();
    }
    if($handle->processed) $insertGoTo = $page."?insert=ok";
    else $insertGoTo = $page."&insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$mySqlQuery = "SELECT * FROM ".$database_connect_prefix."programmes_ccc ORDER BY id_programmes";
$qh = mysql_query_ruche($mySqlQuery, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$data = mysql_fetch_assoc($qh);
$num = mysql_num_rows($qh);*/

$query_liste_programme = "SELECT * FROM ".$database_connect_prefix."programmes_ccc ORDER BY id_programmes";
try{
  $liste_programme = $pdar_connexion->prepare($query_liste_programme);
  $liste_programme->execute();
  $row_liste_programme = $liste_programme ->fetchAll();
  $totalRows_liste_programme = $liste_programme->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$retData["nbr"] = $totalRows_liste_programme;
    $i = 0;
    if($totalRows_liste_programme>0){
     foreach($row_liste_programme as $row_liste_programme){
        $retData[$i]["id_programmes"] = $row_liste_programme["id_programmes"];
		$retData[$i]["sigle_programme"] = $row_liste_programme["sigle_programme"];
		$retData[$i]["nom_programme"] = $row_liste_programme["nom_programme"];
		$retData[$i]["vision"] = $row_liste_programme["vision"];
        $retData[$i]["objectif"] = $row_liste_programme["objectif"];
        $retData[$i]["annee_debut"] = $row_liste_programme["annee_debut"];
        $retData[$i]["annee_fin"] = $row_liste_programme["annee_fin"];
        $retData[$i]["actif"] = $row_liste_programme["actif"];
        $i++;
      } //while($data = mysql_fetch_assoc($qh));
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
<div class="widget-header"> <h4><i class="icon-reorder"></i> Liste des programmes et plans nationaux  </h4>
  <?php if(isset($_SESSION["clp_id"]) && $_SESSION["clp_id"]=='admin') {?>
<?php
//echo do_link("","","Ajout de programme/plan","<i class=\"icon-plus\"> Nouveau programme/plan </i>","","./","pull-right p11","get_content('new_programmes_2qc.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div>
<div class="widget-content" style="display: block;">
<?php for ($i = 0; $i < $retData["nbr"]; $i++)
  {  $id = $retData[$i]["id_programmes"]; //$code = $retData[$i]["code_programmes_2qc"];
?>
<div class="col-md-6">
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo $retData[$i]['sigle_programme']; ?></h4>
  <?php if(isset($_SESSION["clp_id"]) && $_SESSION["clp_id"]=='admin'){  ?>
<?php
//echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id,"Supprimer","","del","./","pull-right p11","return confirm('Voulez-vous vraiment supprimer ce programme ?');",0,"",$nfile);
echo do_link("","","Modifier programme/plan ".$retData[$i]['id_programmes'],"","edit","./","pull-right p11","get_content('new_programmes_2qc.php','id=$id','modal-body_add',this.title);",1,"",$nfile);
?>
<?php }  ?>
</div>
<div class="widget-content" style="display: block; <?php echo ($retData[$i]['actif']==0)?"":"background-color: #DCDCDC;"; ?>">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="CENTER">
<tr>
<td valign="top"><div class="scontent">
<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0" class="table dataTable">
<tr>
  <td>Intitul&eacute;</td>
  <td><b><?php echo $retData[$i]['nom_programme']; ?></b></td>
</tr>
<tr>
  <td>Vision:</td>
  <td><b><?php echo $retData[$i]['vision']; ?></b></td>
</tr>
<tr>
<td width="20%">Objectifs :</td>
<td><b><?php echo $retData[$i]['objectif']; ?></b></td>
</tr>
<tr><td>P&eacute;riode :</td>
<td><b><?php echo $retData[$i]['annee_debut'].' - '.$retData[$i]['annee_fin']; ?>
</b></td>
</tr>
</table>
</div>
</td>
</tr>
</table>
<!--<div class="clear h0">&nbsp;</div>-->
 </div>
</div> </div>
<?php if($i%2!=0) { ?>
<div class="clear h0">&nbsp;</div>
<?php } ?>
<?php } ?>
<div class="clear h0">&nbsp;</div>
</div>
<!-- Fin Site contenu ici -->
            </div>
        </div>
        </div>
        </div>
    </div> <?php include_once 'modal_add.php'; ?>
    <?php include_once ("includes/footer.php");?>
</div>
</body>
</html>