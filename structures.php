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

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
$dir = './documents/';
if(!is_dir($dir)) mkdir($dir);
$racine = $dir;
$dir = (isset($_GET['dir']) && !empty($_GET['dir']))?$_GET['dir']:'./documents/';
$b = './documents/';
$vowels = array(".", "/", "%", "\\");
$tmp = explode($b,$dir);
if(empty($tmp[1])) $dir = $b;
if(substr($tmp[1],-1,1)!='/') $tmp[1] = $tmp[1].'/';
$a = explode('/',$tmp[1]);
$c = explode('/',$b);
$d = $b;
$j = (count($a)-1<=0)?1:count($a)-1;
$chemin = '<ul class="breadcrumb chemin"> <li> <i class="icon-home"></i> <a href="'.$_SERVER['PHP_SELF'].'?dir='.$b.'">'.$c[1].'</a> </li>';
for($i=0; $i<$j-1; $i++)
{
  if(!empty($a[$i]))
  {
    $e = str_replace($vowels,'',$a[$i]);
    if(!empty($e))
    {
      $d .= $e.'/';
      $chemin .= ($j>1 && is_dir($d))?'<li>  <a href="'.$_SERVER['PHP_SELF'].'?dir='.$d.'"><i class="icon-folder"></i> '.$e.'</a> </li>':'';
    }
  }
}

if($j<=1) $i=0;
$a[$i] = str_replace($vowels,'',$a[$i]);
$chemin .= (is_dir($d.$a[$i]) && !empty($a[$i]))?'<li class="current"> <i class="icon-folder-open"></i>&nbsp;<!--<a href="javascript:void(0);" title="">-->'.$a[$i].'<!--</a>--> </li></ul>':'';

$dir = $d.$a[$i];
if(substr($dir,-1,1)!='/') $dir = $dir.'/';

/* formatage de la taille */
function formatSize($s) {
  /* unités */
  $u = array('octets','Ko','Mo','Go','To');
  /* compteur de passages dans la boucle */
  $i = 0;
  /* nombre à afficher */
  $m = 0;
  /* division par 1024 */
  while($s >= 1) {
    $m = $s;
    $s /= 1024;
    $i++;
  }
  if(!$i) $i=1;
  $d = explode(".",$m);
  /* s'il y a des décimales */
  if($d[0] != $m) {
    $m = number_format($m, 2, ",", " ");
  }
  return $m." ".$u[$i-1];
}

/* formatage de nombre de fichier dans le dossier */
function formatSizeFolder($s) {
  $ext = substr(strrchr($s, "."), 1);
  switch ($ext)
  {
    default:
    return '<i class="icon-file"></i>&nbsp;';
    break;

    case 'jpeg':
    case 'jpg':
    case 'png':
    case 'gif':
    return '<i class="icon-file-image-o"></i>&nbsp;';
    break;

    case 'doc':
    case 'docx':
    return '<i class="icon-file-word-o"></i>&nbsp;';
    break;

    case 'xls':
    case 'xlsx':
    return '<i class="icon-file-excel-o"></i>&nbsp;';
    break;

    case 'pdf':
    return '<i class="icon-file-pdf-o"></i>&nbsp;';
    break;

    case 'txt':
    return '<i class="icon-file-text-o"></i>&nbsp;';
    break;

    case 'zip':
    case 'rar':
    return '<i class="icon-file-archive-o"></i>&nbsp;';
    break;
  }
}

/* formatage de type de fichier */
function formatTypeFile($s) {
  if(is_dir($s))
  {
    if($dir = opendir($s))
    {
      $i=0;
      while($file = readdir($dir)) if(is_file($s.'/'.$file) && !in_array($file, array("index.php",".")))
      $i++;
    }
    return ($i==0)?'Vide':(($i>1)?"$i elements":"$i element");
  }
  else return;
}
$dossier = substr($dir,0,strlen($dir)-1);

if ((isset($_GET["id_sup"]) && !empty($_GET["id_sup"]))) {
  $id = ($_GET["id_sup"]);
  //$url_site = './attachment/dano/';
  $Result1 = false;
  if(is_dir($id))
  {
    //if(!in_array($id,$private_folder))
    //{
      if($handle = opendir($id)) while($file = readdir($handle)) unlink($id.'/'.$file);
      rmdir($id);
      $Result1 = true;
    //}
  }
  elseif(file_exists($id))
  {
    unlink($id);
    $Result1 = true;
  }
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  $insertGoTo .= "&dir=$dir";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{ //Upload file
  if ((isset($_FILES['fichier']['name'])) && count($_FILES['fichier']['name'])>0) {
    $ext_autorisees=array('docx','doc','pdf', 'xls', 'xlsx', 'jpeg', 'jpg', 'zip', 'rar'); //Extensions autorisées
    $url_site = $dir;
    $Result1 = false; $link = array();
    for($i=0;$i<count($_FILES['fichier']['name']);$i++)
    {
      $ext = substr(strrchr($_FILES['fichier']['name'][$i], "."), 1);
      if(in_array($ext,$ext_autorisees))
      {
        $Result1 = move_uploaded_file($_FILES['fichier']['tmp_name'][$i],
        $url_site.$_FILES['fichier']['name'][$i]);
        if($Result1) array_push($link,$_FILES['fichier']['name'][$i]);
      }
    }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok&link=".implode('|',$link); else $insertGoTo .= "?insert=no";
    $insertGoTo .= "&dir=$dir";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  else
  {
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    $insertGoTo .= "&dir=$dir";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{ //Creation de dossier
    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
      if ((isset($_POST['dossier'])) && !empty($_POST['dossier'])) {
      $url_site = $dir;
      $Result1 = false; $link = array();
      $Result1 = mkdir($url_site.$_POST['dossier']);
      if($Result1) array_push($link,$_POST['dossier']);

      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?insert=ok&link=".implode('|',$link); else $insertGoTo .= "?insert=no";
      $insertGoTo .= "&dir=$dir";
      header(sprintf("Location: %s", $insertGoTo));  exit();
      }
    }
  //Renommer un dossier
  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
      if ((isset($_POST['dossier'])) && !empty($_POST['dossier'])) {
      $url_site = $dir;
      $Result1 = false; $link = array();
      //if(!in_array($url_site.$_POST['MM_update'],$private_folder))
      $Result1 = rename($url_site.$_POST['MM_update'],$url_site.$_POST["dossier"]);
      if($Result1) array_push($link,$_POST['dossier']);

      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?insert=ok&link=".implode('|',$link); else $insertGoTo .= "?insert=no";
      $insertGoTo .= "&dir=$dir";
      header(sprintf("Location: %s", $insertGoTo));  exit();
    }
  }
}

if ((isset($_GET["id_sup_structure"]) && intval($_GET["id_sup_structure"])>0)) {
  $id = intval($_GET["id_sup_structure"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."structure WHERE code_structure=%s",
                       GetSQLValueString($id, "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{ //structure
    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."structure (code_structure, nom_structure, sigle, slogan, adresse, contact, service, id_personnel) VALUES (%s, %s, %s, %s, %s, %s, %s, '$personnel')",
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['sigle'], "text"),
                         GetSQLValueString($_POST['adresse'], "text"),
                         GetSQLValueString($_POST['slogan'], "text"),
                         GetSQLValueString($_POST['contact'], "text"),
                         GetSQLValueString($_POST['service'], "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    if($Result1){ //$nb = mysql_insert_id();
    include "includes/class.upload.php";
    $handle = new upload($_FILES['logo']);
    if ($handle->uploaded)
    {
      //resize to 250 px
      $handle->file_new_name_body = 'img_'.$_POST['code'];
      $handle->image_resize = true;
      $handle->image_x = 250;
      $handle->image_y = 250;
      $handle->file_auto_rename = true;
      $handle->image_ratio = true;
      $handle->image_convert = 'jpg';
      $handle->file_overwrite = true;
      $handle->process('./images/structure/');   /*
      if ($handle->processed)
      {
        $img_full_name = $handle->file_dst_name_body.".".$handle->file_dst_name_ext;
      }  */
      //terminé
      $handle->clean();
    }
    }

    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."structure WHERE code_structure=%s",
                         GetSQLValueString($id, "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
    $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."structure SET code_structure=%s, nom_structure=%s, sigle=%s, adresse=%s, slogan=%s, contact=%s, service=%s, date_modification='$date', modifier_par='$structure' WHERE code_structure=%s",
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['sigle'], "text"),
                         GetSQLValueString($_POST['adresse'], "text"),
                         GetSQLValueString($_POST['slogan'], "text"),
                         GetSQLValueString($_POST['contact'], "text"),
                         GetSQLValueString($_POST['service'], "text"),
                         GetSQLValueString($id, "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    if($Result1){
    include "includes/class.upload.php";
    $handle = new upload($_FILES['logo']);
    if ($handle->uploaded)
    {
      //resize to 250 px
      $handle->file_new_name_body = 'img_'.$_POST['code'];
      $handle->image_resize = true;
      $handle->image_x = 250;
      $handle->image_y = 250;
      $handle->file_auto_rename = true;
      $handle->image_ratio = true;
      $handle->image_convert = 'jpg';
      $handle->file_overwrite = true;
      $handle->process('./images/structure/');   /*
      if ($handle->processed)
      {
        $img_full_name = $handle->file_dst_name_body.".".$handle->file_dst_name_ext;
      }  */
      //terminé
      $handle->clean();
    }
    }

    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}


if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form4"))
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
      $handle->process('./images/structure/');   /*
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

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form5"))
{ //structure details
    $date=date("Y-m-d"); $structure=$_SESSION['clp_id'];

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
    $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."structure SET info_plus=%s WHERE code_structure=%s",
                         GetSQLValueString($_POST['details'], "text"),
                         GetSQLValueString($id, "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

//structure
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_structure = "SELECT * FROM ".$database_connect_prefix."structure WHERE code_structure='".$_SESSION["clp_structure"]."'";
$structure = mysql_query($query_structure, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_structure = mysql_fetch_assoc($structure);
$totalRows_structure = mysql_num_rows($structure);

/*//Region
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_region = "SELECT * FROM ".$database_connect_prefix."region ";
$region = mysql_query($query_region, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_region = mysql_fetch_assoc($region);
$totalRows_region = mysql_num_rows($region);
$liste_region_array = array();
if($totalRows_region>0){  do{
$liste_region_array[$row_region["id_region"]]=$row_region["nom_region"];
}while($row_region  = mysql_fetch_assoc($region));  } */

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
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 8px 8px;background: #EBEBEB;}
.chemin {padding: 0px; margin: 0px; background-color: transparent; float: left;}
</style>
<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Structures </h4>
<?php if (isset ($_SESSION["clp_id"]) && ($_SESSION["clp_id"] == "admin")) {?>
<?php /*
echo do_link("","","Ajout de structure","<i class=\"icon-plus\"> Nouvelle Structure </i>","","./","pull-right p11","get_content('new_structure.php','','modal-body_add',this.title);",1,"",$nfile);  */
?>
<?php } ?>
</div>

<div class="widget-content" style="display: block;">
<?php $i = 0; do
{ $id = $row_structure['id_structure']; $code = $row_structure['code_structure']; $info = $row_structure['info_plus'];
?>

<div class="col-md-6" style="<?php echo ($totalRows_structure==1)?"float:none; margin:auto; padding:auto;":""; ?>" >
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo $row_structure['sigle']." (".$row_structure['code_structure'].")"; ?></h4>
<?php if(isset($_SESSION["clp_id"]) && $_SESSION["clp_id"]=='admin' || $_SESSION["clp_niveau"]==1){  ?>
<?php
/*if($_SESSION["clp_id"]=='admin')
echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$code,"Supprimer","","del","./","pull-right p11","return confirm('Voulez-vous vraiment supprimer cette structure ".$row_structure['sigle']."');",0,"",$nfile); */

echo do_link("","","Modifier structure ".$row_structure['sigle'],"","edit","./","pull-right p11","get_content('new_structure.php','id=$code','modal-body_add',this.title);",1,"",$nfile);
?>
<?php }  ?>
</div>
<div class="widget-content" style="display: block;">
  <table border="0" cellpadding="5" cellspacing="5" width="100%" align="CENTER">
  <tr>
  <td valign="middle" align="center" width="100"><img src="<?php echo (file_exists("./images/structure/img_$code.jpg"))?"./images/structure/img_$code.jpg":"./images/structure/none.png"; ?>" width="130" height="130" alt=""><br /><?php if(isset($_SESSION["clp_id"]) && $_SESSION["clp_id"]=='admin' || $_SESSION["clp_niveau"]==1){ ?>
<?php
echo do_link("","","Actualiser l'image de la structure ".$row_structure['sigle'],"","reload","./","","get_content('edit_structure_photo.php','id=$id&code=$code','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?></td>
<td valign="top"><div class="scontent"><b><?php echo $row_structure['nom_structure']; $nom = $row_structure['nom_structure']; ?></b><br>
<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-striped  table-responsive dataTable">
<tr><td width="30%">Sigle :</td><td><b><?php echo $row_structure['sigle']; ?></b></td></tr>
<tr><td width="30%">Adresse :</td><td><b><?php echo $row_structure['adresse']; ?></b></td></tr>
<tr><td>Contact :</td><td><b><?php echo $row_structure['contact']; ?></b></td></tr>
<tr><td>Services :</td><td><b><?php $a = implode(' ;',explode(';',$row_structure['service'])); echo implode(' - ',explode(';',$row_structure['service'])); ?></b></td></tr>
<tr><td colspan="2"><i><?php echo $row_structure['slogan']; ?></i></td></tr>
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
<?php $i++; } while($row_structure  = mysql_fetch_assoc($structure)); ?>
<div class="clear h0">&nbsp;</div>
<?php if($totalRows_structure==1){ ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo $nom; ?></h4>
<?php if (isset ($_SESSION["clp_id"]) && ($_SESSION["clp_id"] == "admin")) {?>
<?php
echo do_link("","","Ajout de projet","Description","","./","pull-right p11","get_content('new_structure_info.php','id=$code','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div>
<div class="widget-content">
<?php echo $info; ?>
</div> </div>

<div class="widget box ">
 <div class="widget-header"> <h4><!--<i class="icon-reorder"></i>--> <?php echo $chemin; ?></h4>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
  echo do_link("","","Ajout de fichier","Ajout de fichier","","./","pull-right p11","get_content('new_document.php','dir=$dir&racine=$racine&page=structures.php&parent=1','modal-body_add',this.title,'iframe','','',300);",1,"",$nfile);

  echo do_link("","","Cr&eacute;er un dossier","Cr&eacute;er un dossier","","./","pull-right p11","get_content('new_document.php','folder=1&dir=$dir&racine=$racine&page=structures.php&parent=1','modal-body_add',this.title,'iframe','','',300);",1,"",$nfile);
?>
<?php } ?>
</div>
<div class="widget-content">
<!--<div class="l_float"><?php //echo $chemin; ?></div><div class="clear">&nbsp;</div> -->

<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable dataTable" align="center" id="mtable" >
            <thead>
                <tr>
                  <td><strong>Nom</strong></td>
                  <td width="50"><strong>Date</strong></td>
                  <td width="80"><strong>Taile</strong></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center" width="90" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
            <!--Dossiers -->
                <?php $d = $dir;
                $link = (isset($_GET['link']))?explode('|',$_GET['link']):array();
                if(is_dir($d) && $handle = opendir($d)) { $i=0;
                    while($file = readdir($handle)) {
                      if(is_dir($d.$file) && !in_array($file, array(".",".."))){
                        $id = $d.$file;
                ?>
                <tr>
                  <td <?php echo (in_array($file,$link))?'style="background-color: #F6ED0C;"':''; ?>><a href="<?php echo $_SERVER['PHP_SELF']."?dir=$dir$file/"; ?>" title="Ouvrir"><i class="icon-folder"></i>&nbsp;<?php echo $file; ?></a></td>
                  <td><?php echo date("d/m/Y à H:i:s", filemtime($d.$file)); ?></td>
                  <td align="center"><?php echo formatSizeFolder($d.$file); ?></td>

<td align="center">
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<?php
  echo do_link("","","Renommer le dossier","","edit","./","","get_content('new_document.php','&folder=1&dossier=$file&dir=$dir&racine=$racine&page=structures.php&parent=1','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

  echo do_link("",$_SERVER['PHP_SELF']."?id_sup=$id&dir=$dir","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce dossier ?');",0,"margin:0px 5px;",$nfile);
?>
<?php } ?>
 </td>
				  </tr>
                <?php } } closedir($handle); } ?>
            <!--Fichiers -->
                <?php $d = $dir; $dossier = substr($d,0,strlen($d)-1);
                $link = (isset($_GET['link']))?explode('|',$_GET['link']):array();
                if(is_dir($d) && $handle = opendir($d)) { $i=0;
                    while($file = readdir($handle)) {
                      if(is_file($d.$file) && !in_array($file, array("index.php","."))){
                        $id = $d.$file;
                ?>
                <tr>
                  <td <?php echo (in_array($file,$link))?'style="background-color: #F6ED0C;"':''; ?>> <?php echo formatSizeFolder($file).$file; ?></td>
                  <td><?php echo date("d/m/Y à H:i:s", filemtime($d.$file)); ?></td>
                  <td align="right"><?php echo formatSize(filesize($d.$file)); ?></td>

<td align="center">
<?php
  echo do_link("","./download_file.php?file=$id","T&eacute;l&eacute;charger","","download","./","","return confirm('Voulez-vous vraiment T&eacute;l&eacute;charger ce fichier ?');",0,"margin:0px 5px;",$nfile);
?>
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<?php
  //echo do_link("","","Renommer le fichier","","edit","./","","get_content('new_document.php','dossier=$file&dir=$dir&racine=$racine&page=structures.php&parent=1','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

  echo do_link("",$_SERVER['PHP_SELF']."?id_sup=$id&dir=$dir","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce dossier ?');",0,"margin:0px 5px;",$nfile);
?>
<?php } ?>
 </td>

				  </tr>
                <?php } } closedir($handle); } ?>
              </table>
<div class="clear h0">&nbsp;</div>
</div></div>

<?php } ?>

</div>
<!-- Fin Site contenu ici -->
 </div>
            </div>
        </div>

        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>