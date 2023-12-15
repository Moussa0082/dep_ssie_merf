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
  $query_liste_fonction = "SELECT * FROM ".$database_connect_prefix."fonction WHERE id_fonction=$id ";
  try{
        $liste_fonction = $pdar_connexion->prepare($query_liste_fonction);
        $liste_fonction->execute();
        $row_liste_fonction = $liste_fonction ->fetch();
        $totalRows_liste_fonction = $liste_fonction->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
}
//structure
$query_structure = "SELECT * FROM ".$database_connect_prefix."structure WHERE code_structure='".$_SESSION["clp_structure"]."'";
try{
    $structure = $pdar_connexion->prepare($query_structure);
    $structure->execute();
    $row_structure = $structure ->fetch();
    $totalRows_structure = $structure->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$service_array = array();
$a = explode(';',$row_structure["service"]); foreach($a as $b) $service_array[] = trim($b);
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification fonction":"Nouvelle fonction"?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="nom" class="col-md-3 control-label">Nom <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="nom" id="nom" value="<?php if(isset($row_liste_fonction['fonction'])) echo $row_liste_fonction['fonction']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td valign="top"><div class="form-group">
          <label for="service" class="col-md-3 control-label">Service <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="service" id="service" class="form-control required">
              <option value="">Selectionnez</option>
              
			   <option value="SAF" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_fonction["service"]=="SAF") {echo "SELECTED";} ?>>SAF</option>
			   <option value="RT" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_fonction["service"]=="RT") {echo "SELECTED";} ?>>RT</option>
			   <option value="PA" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_fonction["service"]=="PA") {echo "SELECTED";} ?>>PA</option>

            </select>
          </div>
      </div>
      </td>
    </tr>
    <tr valign="top">  
      <td>
        <div class="form-group">
          <label for="description" class="col-md-3 control-label">Description <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" name="description" id="description" cols="25" rows="5"><?php if(isset($row_liste_fonction['description'])) echo $row_liste_fonction['description']; ?></textarea>
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
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette fonction ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>