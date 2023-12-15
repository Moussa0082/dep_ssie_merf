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
    $query_liste_ugl = "SELECT * FROM ".$database_connect_prefix."ugl WHERE code_ugl=$id ";
    try{
        $liste_ugl = $pdar_connexion->prepare($query_liste_ugl);
        $liste_ugl->execute();
        $row_liste_ugl = $liste_ugl ->fetch();
        $totalRows_liste_ugl = $liste_ugl->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
}

  $query_liste_departement = "SELECT * FROM ".$database_connect_prefix."commune order by code_commune ";
try{
    $liste_departement = $pdar_connexion->prepare($query_liste_departement);
    $liste_departement->execute();
    $row_liste_departement = $liste_departement ->fetchAll();
    $totalRows_liste_departement = $liste_departement->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $query_liste_region= "SELECT * FROM ".$database_connect_prefix."region order by nom_region";
try{
    $liste_region = $pdar_connexion->prepare($query_liste_region);
    $liste_region->execute();
    $ligne = $liste_region ->fetchAll();
    $totalRows_ligne = $liste_region->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $tableauRegion=$tableauRegionV=array();
  foreach($ligne as $ligne){ $tableauRegion[]=$ligne['code_region']."<>".$ligne['abrege_region']; $tableauRegionV[$ligne['code_region']]=$ligne['nom_region']; }
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
        $(".colorpicker").remove();
        $(".bs-colorpicker").colorpicker();
        $(".colorpicker").attr("style","z-index:10060");
	});
</script>

<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
	});
</script>

<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]))?"Modification ugl":"Nouvel ugl"?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="code" class="col-md-9 control-label">Code <span class="required">*</span></label>
          <div class="col-md-11">
            <input class="form-control required" type="text" name="code" id="code" value="<?php echo isset($row_liste_ugl['code_ugl'])?$row_liste_ugl['code_ugl']:""; ?>" size="32" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_ugl['code_ugl']."'"; ?>) check_code('verif_code.php?t=ugl&','w=code_ugl='+this.value+' and structure=<?php echo $_SESSION["clp_structure"]; ?>','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>          </div>
        </div>      </td>
      <td valign="top">
        <div class="form-group">
          <label for="sigle" class="col-md-9 control-label">Abr&eacute;viation <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control required" type="text" name="sigle" id="sigle" value="<?php echo isset($row_liste_ugl['abrege_ugl'])?$row_liste_ugl['abrege_ugl']:""; ?>" size="32" />
          </div>
        </div>      </td>
    </tr>
    <tr>
      <td colspan="2">
        <div class="form-group">
          <label for="nom" class="col-md-10 control-label">Libell&eacute; <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control required" name="nom" id="nom" rows="1" cols="25"><?php echo (isset($_GET["id"]))?$row_liste_ugl['nom_ugl']:''; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
   
    <tr>
      <td colspan="2"> <div class="form-group">
          <label for="parent" class="col-md-3 control-label">Chef lieu <span class="required">*</span></label><br/>
          <div class="col-md-9">
            <div align="left">
              <select name="chef_lieu" id="chef_lieu" class="form-control required" >
                <option value="">Selectionnez</option>
                <?php if($totalRows_liste_departement>0) { foreach($row_liste_departement as $row_liste_departement) { ?>
                <option value="<?php echo $row_liste_departement['code_commune']; ?>" <?php if (isset($row_liste_ugl['chef_lieu']) && $row_liste_departement['code_commune']==$row_liste_ugl['chef_lieu']) {echo "SELECTED";} ?>><?php echo $row_liste_departement['code_commune'].": ".$row_liste_departement['nom_commune']; ?></option>
                <?php } } ?>
              </select>
              </div>
          </div>
        </div></td>
    </tr>
    <tr>
      <td colspan="2">
        <div class="form-group">
          <label for="categorie" class="col-md-10 control-label">Couleur <span class="required">*</span></label>
          <div class="col-md-12">
            <input data-colorpicker-guid="1" data-color-format="hex" class="form-control bs-colorpicker required" type="text" name="couleur" id="couleur" value="<?php echo isset($row_liste_ugl['couleur'])?$row_liste_ugl['couleur']:""; ?>" size="32" />
          </div>
        </div>      </td>
    </tr>
<!--    <tr bgcolor="#FFFFCC">
      <td colspan="4" valign="middle" >
        <div class="form-group">
          <label for="structure" class="col-md-2 control-label">Structure <span class="required">*</span></label>
          <div class="col-md-10">
          <?php /*
          if(isset($_GET["id"]) && intval($_GET["id"])>0) $a = explode("|", $row_liste_ugl['structure']); ?>
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
          <?php if(isset($_GET["id"]) && file_exists("./images/partenaire/img_".$row_liste_ugl['code_ugl'].".jpg")) { ?>
          <img src="<?php echo "./images/partenaire/img_".$row_liste_ugl['code_ugl'].".jpg"; ?>" width='80' height='80' alt='preview'>
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
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && ((!isset($activitep_array[$row_liste_ugl['id_ugl']]) && !isset($activitep_array2[$row_liste_ugl['id_ugl']])))) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cet ugl ?',<?php echo $_GET["id"]; ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>