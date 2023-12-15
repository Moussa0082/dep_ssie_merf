<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {       
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
$dir = (isset($_GET['dir']) && !empty($_GET['dir']))?$_GET['dir']:'./attachment/';
$private_folder = array('./attachment/dano','./attachment/dano/mails','./attachment/mission_atelier','./attachment/reunion_rencontre','./attachment/supervision','./attachment/workflow','./attachment/ptba','./attachment/cmr','./attachment/fiches_dynamiques');
$private_folder_not_show = array('dano',/*'mission_atelier',*/'reunion_rencontre','supervision','workflow'/*,'ptba'*/,'cmr','fiches_dynamiques');
$b = './attachment/';
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

if ((isset($_GET["id_sup"]) && !empty($_GET["id_sup"]))) {
  $id = ($_GET["id_sup"]);
  //$url_site = './attachment/dano/';
  $Result1 = false;
  if(is_dir($id))
  {
    if(!in_array($id,$private_folder))
    {
      if($handle = opendir($id)) while($file = readdir($handle)) unlink($id.'/'.$file);
      rmdir($id);
      $Result1 = true;
    }
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
    $ext_autorisees=array('docx','doc','pdf', 'xls', 'xlsx', 'jpeg', 'jpg', 'png', 'gif', 'zip', 'rar'); //Extensions autorisées
    $url_site = $dir;//'./attachment/dano/';
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
      $url_site = $dir; //'./attachment/dano/';
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
      $url_site = $dir; //'./attachment/dano/';
      $Result1 = false; $link = array();
      if(!in_array($url_site.$_POST['MM_update'],$private_folder))
      $Result1 = rename($url_site.$_POST['MM_update'],$url_site.$_POST["dossier"]);
      if($Result1) array_push($link,$_POST['dossier']);

      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?insert=ok&link=".implode('|',$link); else $insertGoTo .= "?insert=no";
      $insertGoTo .= "&dir=$dir"; 
      header(sprintf("Location: %s", $insertGoTo));  exit();
    }
  }
}

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
<script>
	$().ready(function() {
        $(".dataTable").dataTable({"iDisplayLength": -1});
	});
</script>
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
<div class="widget-content" style="display: block;">
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><!--<i class="icon-reorder"></i>--> <?php echo $chemin; ?></h4>
<?php if(isset($_SESSION['clp_niveau'])){ ?>
<?php
if(!in_array($dossier,$private_folder))
{
  echo do_link("","","Ajout de fichier","Ajout de fichier","","./","pull-right p11","get_content('new_document.php','dir=$dir','modal-body_add',this.title,'iframe');",1,"",$nfile);

  echo do_link("","","Cr&eacute;er un dossier","Cr&eacute;er un dossier","","./","pull-right p11","get_content('new_document.php','folder=1&dir=$dir','modal-body_add',this.title,'iframe');",1,"",$nfile);
}
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
                  <?php //if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center" width="120" ><strong>Actions</strong></td>
                  <?php //} ?>
                </tr>
            </thead>
            <!--Dossiers -->
                <?php $d = $dir;
                $link = (isset($_GET['link']))?explode('|',$_GET['link']):array();
                if(is_dir($d) && $handle = opendir($d)) { $i=0;
                    while($file = readdir($handle)) {  if(!in_array($file,$private_folder_not_show)){
                      if(is_dir($d.$file) && !in_array($file, array(".",".."))){
                        $id = $d.$file;
                ?>
                <tr>
                  <td <?php echo (in_array($file,$link))?'style="background-color: #F6ED0C;"':''; ?>><a href="<?php echo $_SERVER['PHP_SELF']."?dir=$dir$file/"; ?>" title="Ouvrir"><i class="icon-folder"></i>&nbsp;<?php echo $file; ?></a></td>
                  <td><?php echo date("d/m/Y à H:i:s", filemtime($d.$file)); ?></td>
                  <td align="center"><?php echo formatSizeFolder($d.$file); ?></td>

<td align="center">
<?php if(isset($_SESSION['clp_niveau'])) { ?>
<?php
if(!in_array($id,$private_folder) && !in_array($d,$private_folder))
{
  echo do_link("","","Renommer le dossier","","edit","./","","get_content('new_document.php','&folder=1&dossier=$file&dir=$dir','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

  echo do_link("","./liste_document.php?id_sup=$id&dir=$dir","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce dossier ?');",0,"margin:0px 5px;",$nfile);
}
?>
<?php } ?>
 </td>
				  </tr>
                <?php } } } closedir($handle); } ?>
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
  echo do_link("","$id","Afficher ce fichier","","view","./","","",0,"margin:0px 5px;",$nfile,"","_blanck");
  echo do_link("","./download_file.php?file=$id","T&eacute;l&eacute;charger","","download","./","","return confirm('Voulez-vous vraiment T&eacute;l&eacute;charger ce fichier ?');",0,"margin:0px 5px;",$nfile);
?>
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<?php
if(!in_array($dossier,$private_folder))
{
  //echo do_link("","","Renommer le fichier","","edit","./","","get_content('new_document.php','dossier=$file&dir=$dir','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

  echo do_link("","./liste_document.php?id_sup=$id&dir=$dir","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce dossier ?');",0,"margin:0px 5px;",$nfile);
}
?>
<?php } ?>
 </td>

				  </tr>
                <?php } } closedir($handle); } ?>
              </table>
<div class="clear h0">&nbsp;</div>
</div></div>
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