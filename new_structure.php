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

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_structure = "SELECT * FROM ".$database_connect_prefix."structure WHERE code_structure=$id ";
  $liste_structure  = mysql_query($query_liste_structure , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_structure  = mysql_fetch_assoc($liste_structure);
  $totalRows_liste_structure  = mysql_num_rows($liste_structure);
}
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form3").validate();
        $(".wysiwyg").each(function(){$(this).wysihtml5({parser: function(html) {return html;}});});
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification de structure":"Nouvelle structure"?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">
<table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td valign="top">
        <div class="form-group">
          <label for="code" class="col-md-10 control-label">Code <span class="required">*</span></label>
          <div class="col-md-11">
            <input class="form-control required" type="text" name="code" id="code" <?php if(isset ($_SESSION["clp_id"]) && ($_SESSION["clp_id"] != "admin")) echo 'readonly="readonly"'; ?>  value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_structure['code_structure']; ?>" size="32" />
          </div>
        </div>
      </td>
      <td valign="top">
        <div class="form-group">
          <label for="sigle" class="col-md-10 control-label">Sigle <span class="required">*</span></label>
          <div class="col-md-11">
            <input class="form-control required" type="text" name="sigle" id="sigle" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_structure['sigle']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="nom" class="col-md-10 control-label">Nom <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control required" name="nom" id="nom" rows="1" cols="25"><?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?$row_liste_structure['nom_structure']:''; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="adresse" class="col-md-10 control-label">Adresse <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control required" name="adresse" id="adresse" rows="1" cols="25"><?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?$row_liste_structure['adresse']:''; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="contact" class="col-md-10 control-label">Contact <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control" name="contact" id="contact" rows="1" cols="25"><?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?$row_liste_structure['contact']:''; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="slogan" class="col-md-10 control-label">Slogan </label>
          <div class="col-md-12">
            <textarea class="form-control" name="slogan" id="slogan" rows="1" cols="25"><?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?$row_liste_structure['slogan']:''; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="service" class="col-md-10 control-label">Services <span class="required">*</span></label>
          <div class="col-md-12" title="S&eacute;parer par des ;">
            <textarea placeholder="S&eacute;parer par des ;" class="form-control required" name="service" id="service" rows="1" cols="25"><?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?$row_liste_structure['service']:''; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette structure ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>