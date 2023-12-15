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
  $query_liste_personnel = "SELECT * FROM ".$database_connect_prefix."domaine_activite WHERE id_domaine=$id ";
  try{
  $listepersonnel = $pdar_connexion->prepare($query_liste_personnel);
  $listepersonnel->execute();
  $row_liste_personnel = $listepersonnel ->fetch();
  $totalRows_liste_personnel = $listepersonnel->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
}
//Structure
$query_structure = "SELECT * FROM ".$database_connect_prefix."sous_secteur_activite order by code_sous_secteur ";
  try{
  $structure = $pdar_connexion->prepare($query_structure);
  $structure->execute();
  $row_structure = $structure ->fetchAll();
  $totalRows_structure = $structure->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

//partenaire
$query_partenaire = "SELECT * FROM ".$database_connect_prefix."partenaire ";
  try{
  $partenaire = $pdar_connexion->prepare($query_partenaire);
  $partenaire->execute();
  $row_partenaire = $partenaire ->fetchAll();
  $totalRows_partenaire = $partenaire->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form13").validate();
        $(".select2-select-00").select2({allowClear:true});
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification th&eacute;matiques":"Nouvelle th&eacute;matiques"; ?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form13" id="form13" novalidate="novalidate">
<table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    
    <tr>
      <td valign="top" width="50%">
        <div class="form-group">
          <label for="code_domaine" class="col-md-10 control-label">Code th&eacute;matique  <span class="required">*</span></label>
          <div class="col-md-12">
          <input type="text" <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?'readonly="readonly"':''; ?> name="code_domaine" id="id" value="<?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_liste_personnel['code_domaine']:''; ?>" size="32" class="form-control required" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_personnel['code_domaine']."'"; ?>) check_code('verif_code.php?t=domaine_activite&','w=code_domaine='+this.value+' ','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>          </div>
        </div>
      </td>
      <td valign="top" width="50%">
      <div class="form-group">
          <label for="sous_secteur" class="col-md-10 control-label">Domaines d'activit&eacute;s <span class="required">*</span></label>
          <div class="col-md-12">
            <select name="sous_secteur" id="sous_secteur" class="select2-select-00 col-md-12 full-width-fix required" placholder="Secteur">
			    <option title="Choisissez un secteur" value="" <?php if(isset($row_liste_personnel['sous_secteur']) && $row_liste_personnel['sous_secteur']=="") echo 'selected="selected"'; ?>>--Choisissez--</option>
      <?php foreach($row_structure as $row_structure){  ?>
              <option title="<?php echo $row_structure["nom_sous_secteur"]; ?>" value="<?php echo $row_structure['id_sous_secteur']; ?>" <?php if(isset($row_liste_personnel['sous_secteur']) && $row_liste_personnel['sous_secteur']==$row_structure['id_sous_secteur']) echo 'selected="selected"'; ?>><?php echo $row_structure['nom_sous_secteur']; ?></option>
			   <?php } //while ($row_structure = mysql_fetch_assoc($structure));  ?>
            </select>
          </div>
        </div>
      </td>
   </tr>
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="nom_domaine" class="col-md-10 control-label">Intitul&eacute; <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea name="nom_domaine" cols="40" rows="2" class="form-control required input-datepicker" id="nom_domaine"><?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_liste_personnel['nom_domaine']:''; ?></textarea>
          </div>
        </div>
        </td>
      <td valign="top">
       <div class="form-group">
          <label for="description_domaine" class="col-md-10 control-label">Description</label>
          <div class="col-md-12">
            <textarea name="description_domaine" cols="40" rows="2" class="form-control required input-datepicker" id="description_domaine"><?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_liste_personnel['description_domaine']:''; ?></textarea>
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
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette th&eacute;matique ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form13" size="32" alt="">
</div>
</form>

</div> </div>