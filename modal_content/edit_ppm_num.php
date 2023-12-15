<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start(); $path="../";
include_once $path.'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"]) && !isset($_GET["id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}

include_once $path.$config->sys_folder . "/database/db_connexion.php";

$editFormAction = $_SERVER['PHP_SELF'];
/*if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}*/

$annee=$_GET['annee']; $edited = 0;
if(isset($_GET["id_mar"])) { $id_marche=$_GET["id_mar"];} else $id_marche=0;
$page = $_SERVER['PHP_SELF']."?id_mar=$id_marche&annee=$annee";
$edited=(isset($_GET["edited"]))?1:0;

//update
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."plan_marche set nao=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_marche=%s",
					   GetSQLValueString($_POST['nao'], "text"),
					   GetSQLValueString($_POST['id_marche'], "text"));
  
	    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
    $insertGoTo = $editFormAction."?id_mar=".$_POST['id_marche']."&annee=$annee";
  if ($Result1){ $insertGoTo .= "&update=ok&edited=1"; } else $insertGoTo .= "&update=no";
  header(sprintf("Location: %s", $insertGoTo));
}

//mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_gv = "SELECT nao FROM ".$database_connect_prefix."plan_marche WHERE id_marche='$id_marche'";
       try{
    $edit_gv = $pdar_connexion->prepare($query_edit_gv);
    $edit_gv->execute();
    $row_edit_gv = $edit_gv ->fetch();
    $totalRows_edit_gv = $edit_gv->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if(isset($row_edit_gv["nao"])) $marche_nao = $row_edit_gv["nao"]; else $marche_nao = "";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title><?php echo $config->site_name; ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="<?php echo $path; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $path.$config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php print $path.$config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="<?php print $path; ?>plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="<?php print $path; ?>plugins/validation/jquery.validate.min.js"></script>
  <style type="text/css">
<!--
.Style1 {	font-size: 12px;
	font-weight: bold;
}
.Style2 {font-size: 12px}
.Style9 {font-size: 12}
.titrecorps2 {
  font-weight: bold;
}

-->
  </style>
</head>

<body style="height: auto;">
  <div id="corps">
<div id="special">
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form2").validate();
        $(".modal-dialog", window.parent.document).width(700);
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Modification</h4> </div>
<div class="widget-content">
<form action="<?php echo $page; ?>" method="post" name="form2" id="form2" class="row-border" enctype="multipart/form-data" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="nao" class="col-md-3 control-label">N&deg; d'Appel d'offre <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="nao" id="nao" value="<?php if(isset($_GET["id_mar"]) && intval($_GET["id_mar"])>0) echo $row_edit_gv['nao']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
<input type="hidden" name="id_marche" id="id_marche" value="<?php echo $id_marche;  ?>" />
<input type="hidden" name="annee" id="annee" value="<?php echo $annee;  ?>" />
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id_mar"]) && intval($_GET["id_mar"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id_mar"]) && intval($_GET["id_mar"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="form2" size="32" alt="">
</div>
</form>

</div>  </div>

<script type="text/javascript">
<?php if($edited>0) { ?>
$().ready(function() {
        // reload parent frame
        $(".close", window.parent.document).click(function(){
          //window.parent.location.reload();
          $("#stat_<?php echo $annee.$id_marche; ?>", window.parent.document).html('<?php echo $marche_nao ?>');
        });
        $("button[data-dismiss='modal']", window.parent.document).click(function(){
          //window.parent.location.reload();
          $("#stat_<?php echo $annee.$id_marche; ?>", window.parent.document).html('<?php echo $marche_nao ?>');
        });
});
<?php } ?>
</script>

  </div>
</div>

</body>

</html>