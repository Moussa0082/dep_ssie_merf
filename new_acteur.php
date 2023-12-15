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
  $id=$_GET["id"];
  $query_liste_acteur = "SELECT * FROM ".$database_connect_prefix."acteur WHERE code_acteur='$id' ";
  try{
        $liste_acteur = $pdar_connexion->prepare($query_liste_acteur);
        $liste_acteur->execute();
        $row_liste_acteur = $liste_acteur ->fetch();
        $totalRows_liste_acteur = $liste_acteur->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
}

?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
	});
</script>

<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]))?"Modification acteur":"Nouvel acteur"; ?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="code" class="col-md-9 control-label">Code <span class="required">*</span></label>
          <div class="col-md-11">
            <input class="form-control required" type="text" name="code" id="code" value="<?php echo (isset($row_liste_acteur['code_acteur']))?$row_liste_acteur['code_acteur']:""; ?>" size="32" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_acteur['code_acteur']."'"; ?>) check_code('verif_code.php?t=acteur&','w=code_acteur='+this.value+'','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div>
      </td>
      <td valign="top">
        <div class="form-group">
          <label for="nom" class="col-md-9 control-label">Sigle <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control required" type="text" name="nom" id="nom" value="<?php  if(isset($_GET["id"])) echo $row_liste_acteur['nom_acteur']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <div class="form-group">
          <label for="description" class="col-md-10 control-label">Description <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control required" name="description" id="description" rows="1" cols="40"><?php if(isset($_GET["id"])) echo $row_liste_acteur['description']; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
   <!-- <tr>
      <td colspan="2">
        <div class="form-group">
          <label for="categorie" class="col-md-10 control-label">Cat&eacute;gorie <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control required" name="categorie" id="categorie" rows="1" cols="25"><?php if(isset($_GET["id"])) echo $row_liste_acteur['categorie']; ?></textarea>
          </div>
        </div>
      </td>

    </tr>
    <tr bgcolor="#FFFFCC">
      <td colspan="4" valign="middle" >
        <div class="form-group">
          <label for="structure" class="col-md-2 control-label">Structure <span class="required">*</span></label>
          <div class="col-md-10">
          <?php /*
          if(isset($_GET["id"]) && intval($_GET["id"])>0) $a = explode("|", $row_liste_acteur['structure']); ?>
          <table width="100%">
          <tr>
          <?php $i = 1; foreach($tableauStructure as $vregion){?>
          <?php
          $aregion = explode('<>',$vregion);
          $iregion = $aregion[0];
          ?>
          <td><label title="<?php echo $tableauStructureV[$iregion]; ?>" for="structure_<?php echo $i; ?>" class="control-label"><?php if(isset($aregion[1])) echo $aregion[1]; ?></label>
          <input title="<?php echo $tableauStructureV[$iregion]; ?>" name='structure[]' id='structure_<?php echo $i; ?>' type="checkbox"   <?php if(isset($_GET['id'])) { if(in_array($iregion, $a, TRUE)) echo "checked"; }?> size="5" value="<?php if(isset($iregion)) echo $iregion; ?>"/></td>
          <?php $i++; } */?>
          </tr>
          </table>
          </div>
        </div>
      </td>
    </tr>-->
<!--    <tr>
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="photo" class="col-md-3 control-label">Logo <span class="required">*</span></label>
          <div class="col-md-3 pull-left">
          <div id="photo_prev">
          <?php if(isset($_GET["id"]) && intval($_GET["id"])>0 && file_exists("./images/partenaire/img_".$row_liste_acteur['code_acteur'].".jpg")) { ?>
          <img src="<?php echo "./images/partenaire/img_".$row_liste_acteur['code_acteur'].".jpg"; ?>" width='80' height='80' alt='preview'>
          <?php } ?>
          </div>
          </div>
          <div class="col-md-6 pull-left">
            <input class="form-control <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"":'required'; ?>" type="file" name="photo" id="photo" value="" onchange="readImgURL(this,'photo_prev',80,80);" size="32" />
          </div>
        </div>
      </td>
    </tr>-->
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && ((!isset($activitep_array[$row_liste_acteur['code_acteur']]) && !isset($activitep_array2[$row_liste_acteur['code_acteur']])))) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cet acteur ?','<?php echo $_GET["id"]; ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>