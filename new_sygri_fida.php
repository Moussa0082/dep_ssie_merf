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
//header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET["id"])) { $id=$_GET["id"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_data = "SELECT * FROM liste_indicateur_sygri where id_indicateur_sygri_fida='$id'";
$edit_data = mysql_query($query_edit_data, $pdar_connexion) or die(mysql_error());
$row_edit_data = mysql_fetch_assoc($edit_data);
$totalRows_edit_data = mysql_num_rows($edit_data);
}else $id=0;


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_gp = "SELECT id_groupe, nom_groupe from groupe_indicateur order by code_groupe";
$liste_gp  = mysql_query($query_liste_gp , $pdar_connexion) or die(mysql_error());
$row_liste_gp = mysql_fetch_assoc($liste_gp);
$totalRows_liste_gp  = mysql_num_rows($liste_gp);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_referentiel = "SELECT * FROM referentiel_indicateur where id_ref_ind not in (select referentiel from liste_indicateur_sygri where id_indicateur_sygri_fida!='$id' ) order by code_ref_ind";
$liste_referentiel = mysql_query($query_liste_referentiel, $pdar_connexion) or die(mysql_error());
$row_liste_referentiel = mysql_fetch_assoc($liste_referentiel);
$totalRows_liste_referentiel = mysql_num_rows($liste_referentiel);

?>
<script>
	$().ready(function() {
		$("#form1").validate();
		 $(".modal-dialog", window.parent.document).width(700);
        $(".select2-select-00").select2({allowClear:true});
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});});

</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification Indicateurs SYGRI":"Nouveau Indicateurs SYGRI"?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="ordre" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-4">
            <input class="form-control required" type="text" name="ordre" id="ordre" value="<?php echo (isset($row_edit_data['ordre']))?$row_edit_data['ordre']:""; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="intitule_indicateur_sygri_fida" class="col-md-3 control-label">Indicateur <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="intitule_indicateur_sygri_fida" id="intitule_indicateur_sygri_fida" value="<?php echo (isset($row_edit_data['intitule_indicateur_sygri_fida']))?$row_edit_data['intitule_indicateur_sygri_fida']:""; ?>" size="32" />
          </div>
        </div>
		
      </td>
    </tr>
	
	    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="niveau_sygri" class="col-md-3 control-label">Niveau <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="niveau_sygri" id="niveau_sygri" class="form-control required" >
              <option value="">Selectionnez</option>
              <option value="1" <?php if (isset($row_edit_data['niveau_sygri']) && 1==$row_edit_data['niveau_sygri']) {echo "SELECTED";} ?>>1er Niveau</option>
              <option value="2" <?php if (isset($row_edit_data['niveau_sygri']) && 2==$row_edit_data['niveau_sygri']) {echo "SELECTED";} ?>>2eme Niveau</option>
              <option value="3" <?php if (isset($row_edit_data['niveau_sygri']) && 3==$row_edit_data['niveau_sygri']) {echo "SELECTED";} ?>>3eme Niveau</option>
            </select>
          </div>
        </div>
      </td>
    </tr>
	
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="groupe_indicateur" class="col-md-3 control-label">Groupe <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="groupe_indicateur" id="groupe_indicateur" class="form-control required" >
              <option value="">Selectionnez</option>
              <?php do { ?>
              <option value="<?php echo $row_liste_gp['id_groupe']; ?>" <?php if (isset($row_edit_data['groupe_indicateur']) && $row_liste_gp['id_groupe']==$row_edit_data['groupe_indicateur']) {echo "SELECTED";} ?>><?php echo $row_liste_gp['nom_groupe']; ?></option>
              <?php }while($row_liste_gp = mysql_fetch_assoc($liste_gp)); ?>
            </select>
          </div>
        </div>
      </td>
    </tr>


      <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="referentiel" class="col-md-3 control-label">R&eacute;f&eacute;rentiel <span class="required">*</span></label>
          <div class="col-md-9">
             <select name="referentiel" id="referentiel" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez un indicateur">
             
         <option value="0" <?php if (isset($row_edit_data["referentiel"]) && $row_edit_data['referentiel']=="0") {echo "SELECTED";} ?>>Non-d&eacute;finie</option>
              <?php if($totalRows_liste_referentiel>0){ do { ?>
              <option value="<?php echo $row_liste_referentiel['id_ref_ind']; ?>" <?php if (isset($row_edit_data["referentiel"]) && $row_liste_referentiel['id_ref_ind']==$row_edit_data["referentiel"]) {echo "SELECTED";} ?>><?php echo $row_liste_referentiel['code_ref_ind'].": ".$row_liste_referentiel['intitule_ref_ind']; ?></option>
                <?php  } while ($row_liste_referentiel = mysql_fetch_assoc($liste_referentiel)); } ?>
            </select>
          </div>
        </div>
      </td>
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
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer l\'indicateur ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>