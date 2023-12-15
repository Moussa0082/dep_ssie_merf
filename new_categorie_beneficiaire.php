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

if(isset($_GET["id"]) && intval($_GET["id"])>0)
{
    $id=intval($_GET["id"]);
    $query_liste_cat = "SELECT * FROM ".$database_connect_prefix."categorie_beneficiaire WHERE id_categorie=$id ";
    try{
        $liste_cat = $pdar_connexion->prepare($query_liste_cat);
        $liste_cat->execute();
        $row_liste_cat = $liste_cat ->fetch();
        $totalRows_liste_cat = $liste_cat->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
}
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form4").validate();
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification Cat&eacute;gorie":"Nouvelle Cat&eacute;gorie"?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form4" id="form4" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="code_cat" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="code_cat" id="code_cat" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_cat['code_cat']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="categorie" class="col-md-3 control-label">Cat&eacute;gorie <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" name="categorie" id="categorie" cols="25" rows="5"><?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_cat['categorie']; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette cat&eacute;gorie ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form4" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>