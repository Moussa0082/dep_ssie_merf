<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & D&eacute;veloppement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=ISO-8859-15');

if(isset($_GET["id"]) && intval($_GET["id"])>0)
{
  $id=intval($_GET["id"]);
  //mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_personnel = "SELECT * FROM ".$database_connect_prefix."sous_secteur_activite WHERE id_sous_secteur=$id ";
  try{
  $listepersonnel = $pdar_connexion->prepare($query_liste_personnel);
  $listepersonnel->execute();
  $row_liste_personnel = $listepersonnel ->fetch();
  $totalRows_liste_personnel = $listepersonnel->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
}

//programmes_2qc
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_partenaire = "SELECT * FROM ".$database_connect_prefix."programmes_2qc ";
$partenaire = mysql_query_ruche($query_partenaire, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_partenaire = mysql_fetch_assoc($partenaire);
$totalRows_partenaire = mysql_num_rows($partenaire);*/
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
        $(".select2-select-00").select2({allowClear:true});
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification domaine d'activit&eacute;":"Nouveau domaine d'activit&eacute;"; ?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form12" id="form12" novalidate="novalidate">
<table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    
    <tr>
      <td valign="top" width="50%">
        <div class="form-group">
          <label for="code_sous_secteur" class="col-md-10 control-label">Code <span class="required">*</span></label>
          <div class="col-md-12">
          <input type="text" <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?'readonly="readonly"':''; ?> name="code_sous_secteur" id="id" value="<?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_liste_personnel['code_sous_secteur']:''; ?>" size="32" class="form-control required" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_personnel['code_sous_secteur']."'"; ?>) check_code('verif_code.php?t=sous_secteur_activite&','w=code_sous_secteur='+this.value+' ','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
            </div>
        </div>
      </td>
      <td width="50%" valign="top">
        <div class="form-group">
          <label for="nom_sous_secteur" class="col-md-10 control-label">Intitul&eacute; <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea name="nom_sous_secteur" cols="40" rows="2" class="form-control required input-datepicker" id="nom_sous_secteur"><?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_liste_personnel['nom_sous_secteur']:''; ?></textarea>
          </div>
        </div>
        </td>
    </tr>
   <!-- <tr>
      <td valign="top" colspan="2">
       <div class="form-group">
          <label for="programmes_2qc" class="col-md-10 control-label">Programmes </label>
          <div class="col-md-12">
            <select id="programmes_2qc" name="programmes_2qc[]" class="select2-select-00 col-md-12 full-width-fix required" multiple size="5">
              <?php //if($totalRows_partenaire>0){ $elem = isset($row_liste_personnel["programmes_2qc"])?explode(',',$row_liste_personnel["programmes_2qc"]):array(); do { ?>
              <option value="<?php //echo $row_partenaire['id_programmes_2qc']; ?>" <?php //if (isset($row_partenaire['programmes_2qc']) && in_array($row_partenaire['programmes_2qc'],$elem)) {echo "SELECTED";} ?>>Programme 2QC <?php //echo "(".$row_partenaire['annee_debut']." - ".$row_partenaire['annee_fin'].")"; ?></option>
                <?php  //} while ($row_partenaire = mysql_fetch_assoc($partenaire)); } ?>
            </select>
          </div>
        </div>
      </td>
    </tr>-->
     <tr>
      <td colspan="2" valign="top">
       <div class="form-group">
          <label for="description_sous_secteur" class="col-md-10 control-label">Description</label>
          <div class="col-md-12">
            <textarea name="description_sous_secteur" cols="40" rows="2" class="form-control required input-datepicker" id="description_sous_secteur"><?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_liste_personnel['description_sous_secteur']:''; ?></textarea>
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
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce domaine d\'intervention ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form12" size="32" alt="">
</div>
</form>

</div> </div>