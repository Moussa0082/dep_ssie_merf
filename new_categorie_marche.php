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
if(isset($_GET["id"]))
{
    $id=($_GET["id"]);
    $query_edit_categorie = "SELECT * FROM ".$database_connect_prefix."categorie_marche WHERE code_categorie='$id'";
    try{
        $edit_categorie = $pdar_connexion->prepare($query_edit_categorie);
        $edit_categorie->execute();
        $row_edit_categorie = $edit_categorie ->fetch();
        $totalRows_edit_categorie = $edit_categorie->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
}
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]))?"Modification":"Nouvel ajout"?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="code_categorie" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" name="code_categorie" id="code_categorie" type="text" value="<?php if(isset($_GET['id'])) echo $row_edit_categorie['code_categorie'];  ?>" size="10" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_edit_categorie['code_categorie']."'"; ?>) check_code('verif_code.php?t=categorie_marche&','w=code_categorie='+this.value+' ','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">  
      <td>
        <div class="form-group">
          <label for="nom_categorie" class="col-md-3 control-label">Libell&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" name="nom_categorie" id="nom_categorie" cols="32" rows="2"><?php if(isset($_GET['id'])) echo $row_edit_categorie['nom_categorie'];  ?></textarea>
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette categorie; ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>
</div> </div>