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
$dir = (isset($_GET['dir']) && !empty($_GET['dir']))?$_GET['dir']:'./attachment/dano/';
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
    if($handle = opendir($id)) while($file = readdir($handle)) unlink($id.'/'.$file);
    rmdir($id);
    $Result1 = true;
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->
<link rel="stylesheet" type="text/css" href="<?php print $config->theme_folder;?>/plugins/jquery-ui.css"/>
<link href="<?php print $config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder;?>/responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder;?>/icons.css" rel="stylesheet" type="text/css"/>
<link href='<?php print $config->theme_folder;?>/css.css' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
<link href="<?php print $config->theme_folder; ?>/plugins/datatables_bootstrap.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>
<script type="text/javascript" src="plugins/noty/layouts/top.js"></script>
<script type="text/javascript" src="plugins/noty/themes/default.js"></script>
<script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<?php if(!isset($_GET['add'])) { ?>
<script type="text/javascript" src="plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>
<script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>
<?php } ?>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
        $("#form2").validate();
<?php if(!isset($_GET['add'])) { ?>
$(".dataTable").dataTable({"iDisplayLength": -1});

<?php } ?>
<?php /*if(isset($_GET['add'])) { ?>
<!--        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});-->
<?php }*/ ?>
	});
</script>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 8px 8px;background: #EBEBEB;}
.chemin {padding: 0px; margin: 0px; background-color: transparent; float: left;}

.dataTables_length, .dataTables_info { float: left;} .dataTables_paginate, .dataTables_filter { float: right;}
.dataTables_length, .dataTables_paginate { display: none;}

@media(min-width:558px){.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}
</style>
</head>
<body>
<?php if(isset($_GET['add'])) { //Transfert de fichier ?>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php
if(isset($_GET['folder']))
echo (isset($_GET["dossier"]) && !empty($_GET["dossier"]))?"Modifier un dossier":"Nouveau dossier";
else
echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modifier un document":"Nouveau document"; ?></h4>
<a href="<?php echo $_SERVER['PHP_SELF']."?dir=$dir"; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<?php if(isset($_GET['folder'])) { //creation de dossier ?>
<form action="<?php echo $_SERVER['PHP_SELF']."?dir=$dir"; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr>
      <td valign="middle">
        <div class="form-group">
          <label for="dossier" class="col-md-5 control-label">Nom du dossier <span class="required">*</span></label>
          <div class="col-md-6">
            <input class="form-control required" type="text" name="dossier" id="dossier" value="<?php if(isset($_GET['dossier'])) echo $_GET['dossier']; ?>" size="32" />
            <span class="help-block h0" id="code_zone_text">Chemin : <?php echo $dir; ?></span>
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["dossier"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["dossier"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["dossier"])) echo $_GET["dossier"]; else echo "MM_insert" ; ?>" size="32" alt="">
<a href="<?php echo $_SERVER['PHP_SELF']."?dir=$dir"; ?>" class="btn pull-right" title="Annuler" >Annuler</a>
<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>
<?php } else { //Transfert de fichier ?>
<form action="<?php echo $_SERVER['PHP_SELF']."?dir=$dir"; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr>
      <td valign="middle">
        <div class="form-group">
          <label for="fichier" class="col-md-5 control-label">Fichier &agrave; uploader <span class="required">*</span></label>
          <div class="col-md-6">
            <input class="form-control required" type="file" name="fichier[]" id="fichier" value="" size="32" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/pdf,application/vnd.ms-word,image/jpeg,.doc,.docx,.zip,.rar" multiple />
            <span class="help-block h0" id="code_zone_text">Chemin : <?php echo $dir; ?></span>
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="Enregistrer" />
<a href="<?php echo $_SERVER['PHP_SELF']."?dir=$dir"; ?>" class="btn pull-right" title="Annuler" >Annuler</a>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>
<?php } ?>
</div> </div>
<?php } }else{ //liste des documents ?>
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> Documentation</h4>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<a href="<?php echo $_SERVER['PHP_SELF']."?add=1&dir=$dir"; ?>" title="Ajout de fichier" class="pull-right p11"><img src="./images/add.png" width="20" height="20" alt="Fichier" title="Ficihier">Ajout de fichier</a>
<a href="<?php echo $_SERVER['PHP_SELF']."?add=1&folder=1&dir=$dir"; ?>" title="Cr&eacute;er un dossier" class="pull-right p11"><img src="./images/folder_add.png" width="20" height="20" alt="Dossier" title="Dossier">Cr&eacute;er un dossier</a>
<?php } ?>
</div>
<div class="widget-content">
<div class="l_float"><?php echo $chemin; ?></div>
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
                <?php $d = $dir; //"./attachment/dano/";
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
				   <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<td align="center">
<!--<a onclick="get_content('edit_suivi_dno.php','dno=<?php echo $id; ?>&annee=<?php echo $annee; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Suivi de la DNO <?php echo $row_liste_conv['numero']; ?>" class="thickbox Add"  dir="">Suivre</a>  -->
<a href="<?php echo $_SERVER['PHP_SELF']."?add=1&folder=1&dossier=$file&dir=$dir"; ?>" title="Modifier document" style="margin:0px 5px;"><img src="./images/edit.png" width="20" height="20" alt="Modifier" title="Modifier"></a><a href="<?php echo $_SERVER['PHP_SELF']."?id_sup=$id&dir=$dir"; ?>" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer ce dossier ?');" style="margin:0px 5px;"><img src="./images/folder_delete.png" width="20" height="20" alt="Supprimer" title="Supprimer"></a>
<!--<a href="./download_file.php?file=<?php echo $id; ?>" title="Télécharger" style="margin:0px 5px;"><img src="./images/download.png" width="20" height="20" alt="Télécharger" title="Télécharger"></a> -->
 </td>
                   <?php } ?>
				  </tr>
                <?php } } closedir($handle); } ?>
            <!--Fichiers -->
                <?php $d = $dir; //"./attachment/dano/";
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
				   <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<td align="center">
<!--<a onclick="get_content('edit_suivi_dno.php','dno=<?php echo $id; ?>&annee=<?php echo $annee; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Suivi de la DNO <?php echo $row_liste_conv['numero']; ?>" class="thickbox Add"  dir="">Suivre</a>
<!--<a href="<?php echo $_SERVER['PHP_SELF']."?id=$id&add=1&doc=1&annee=$annee&dno=$dno&cp=$cp"; ?>" title="Modifier document" style="margin:0px 5px;"><img src="./images/edit.png" width="20" height="20" alt="Modifier" title="Modifier"></a>--><a href="<?php echo $_SERVER['PHP_SELF']."?id_sup=$id&dir=$dir"; ?>" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer ce document ?');" style="margin:0px 5px;"><img src="./images/file_delete.png" width="20" height="20" alt="Supprimer" title="Supprimer"></a>
<a href="./download_file.php?file=<?php echo $id; ?>" title="Télécharger" style="margin:0px 5px;"><img src="./images/download.png" width="20" height="20" alt="Télécharger" title="Télécharger"></a>
 </td>
                   <?php } ?>
				  </tr>
                <?php } } closedir($handle); } ?>
              </table>
<div class="clear h0">&nbsp;</div>
</div></div>
</div>
<?php } ?>
<?php include_once 'modal_add.php'; ?>
</body>
</html>