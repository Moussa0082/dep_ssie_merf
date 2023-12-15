<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
header('Content-Type: text/html; charset=ISO-8859-15');


if(isset($_GET["id"])) { $id=$_GET["id"];
$query_edit_data = "SELECT * FROM type_activite where id_type=$id";
try{
    $edit_data = $pdar_connexion->prepare($query_edit_data);
    $edit_data->execute();
    $row_edit_data = $edit_data ->fetch();
    $totalRows_edit_data = $edit_data->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
//if(isset($row_edit_data['indicateur_sygri_niveau2'])) $ais = explode(",", $row_edit_data['indicateur_sygri_niveau2']); else $ais=array();
}else $id=0;

?>
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
	});
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification type d'activité":"Nouveau type d'activité"?></h4>  </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form11" id="form11" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="categorie" class="col-md-3 control-label">N&deg; d'ordre  <span class="required">*</span></label>
          <div class="col-md-4">
            <input class="form-control required" type="text" name="categorie" id="categorie" value="<?php if(isset($row_edit_data['categorie'])) echo $row_edit_data['categorie']; ?>" size="32" />
          </div>
        </div>      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="type_activite" class="col-md-3 control-label">Type d'activit&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="type_activite" id="type_activite" value="<?php if(isset($row_edit_data['type_activite'])) echo $row_edit_data['type_activite']; ?>" size="32" />
          </div>
        </div>      </td>
    </tr>
	   <!-- <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="description_type" class="col-md-3 control-label">Description <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea name="description_type" cols="32" class="form-control required" id="description_type"><?php if(isset($row_edit_data['description_type'])) echo $row_edit_data['description_type']; ?></textarea>
          </div>
        </div>      </td>
    </tr>-->
    <tr valign="top">
      <td colspan="2">&nbsp;</td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
  <?php } ?>

  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && intval($_GET["id"])>0 && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2) ) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer l\'activité ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form11" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>