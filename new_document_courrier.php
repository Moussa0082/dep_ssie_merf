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
////header('Content-Type: text/html; charset=UTF-8');

$page = (isset($_GET["page"]))?$_GET["page"]:"./liste_document.php";
if (isset($_SERVER['QUERY_STRING'])) {
  if(isset($_GET["page"]))
  $_SERVER['QUERY_STRING'] = str_replace("page=".$_GET["page"],'',$_SERVER['QUERY_STRING']);
  $page .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$page = (!isset($_GET["page"]))?"./liste_document.php?dir=$dir":$page;
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

?>

<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
	});
</script>

<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php
if(isset($_GET['folder']))
echo (isset($_GET["dossier"]) && !empty($_GET["dossier"]))?"Modifier un dossier":"Nouveau dossier";
else
echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modifier un document":"Nouveau document"; ?></h4>
</div>
<div class="widget-content">
<?php if(isset($_GET['folder'])) { //creation de dossier ?>
<form action="<?php echo $page; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">
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
  <input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>
<?php } else { //Transfert de fichier ?>
<form action="<?php echo $page; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
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
  <input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>
<?php } ?>
</div></div>