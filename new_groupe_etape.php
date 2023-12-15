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

$as=array();
if(isset($_GET["id"]))
{
    $id=($_GET["id"]);
    $query_edit_modele = "SELECT * FROM ".$database_connect_prefix."groupe_etape WHERE id_groupe='$id'";
    try{
        $edit_modele = $pdar_connexion->prepare($query_edit_modele);
        $edit_modele->execute();
        $row_edit_modele = $edit_modele ->fetch();
        $totalRows_edit_modele = $edit_modele->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
}
//Catégorie
$query_liste_categorie = "SELECT * FROM ".$database_connect_prefix."categorie_marche ORDER BY code_categorie asc";
try{
    $liste_categorie = $pdar_connexion->prepare($query_liste_categorie);
    $liste_categorie->execute();
    $row_liste_categorie = $liste_categorie ->fetchAll();
    $totalRows_liste_categorie = $liste_categorie->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
		$(".select2-select-00").select2({allowClear:true});
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]))?"Modification":"Nouvel ajout"?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2ge" id="form2ge" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="code_groupe" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-6">
            <input class="form-control required" name="code_groupe" id="code_groupe" type="text" value="<?php if(isset($_GET['id'])) echo $row_edit_modele['code_groupe'];  ?>" size="30" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_edit_modele['code_groupe']."'"; ?>) check_code('verif_code.php?t=groupe_etape&','w=code_groupe='+this.value+' ','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>          </div>
        </div>      </td>
    </tr>
	<tr valign="top">
      <td nowrap="nowrap">
        <div class="form-group">
          <label for="libelle_groupe" class="col-md-3 control-label">Etape <span class="required">*</span></label>
          <div class="col-md-6">
  <textarea name="libelle_groupe" cols="60" rows="2" class="form-control required" id="libelle_groupe"><?php if(isset($_GET['id'])) echo $row_edit_modele['libelle_groupe'];  ?></textarea>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="num_groupe" class="col-md-3 control-label">N° <span class="required">*</span></label>
          <div class="col-md-6">
      <input name="num_groupe" type="text" class="form-control required" id="num_groupe" value="<?php if(isset($_GET['id'])) echo $row_edit_modele['num_groupe'];  ?>" size="30" />
          </div>
        </div>      </td>
    </tr>
	   <tr>
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="categorie" class="col-md-3 control-label">Type de march&eacute;s <span class="required">*</span></label>
          <div class="col-md-9">
           	 <select name="categorie[]" id="categorie" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez un acteur" multiple>
              <option></option>
              <option value="">Non-d&eacute;fini</option>
               <?php if($totalRows_liste_categorie>0) {  $expl = (isset($row_edit_modele["categorie_groupe"]) && !empty($row_edit_modele["categorie_groupe"]))?explode(',',$row_edit_modele["categorie_groupe"]):array(); foreach($row_liste_categorie as $row_liste_categorie){ ?>
              <option value="<?php echo $row_liste_categorie['code_categorie']; ?>" <?php if(in_array($row_liste_categorie['code_categorie'],$expl)) {echo "SELECTED";} ?>><?php echo $row_liste_categorie['nom_categorie']; ?></option>
                <?php  } } ?>
            </select>
          </div>
        </div>      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce modele; ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form2ge" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>