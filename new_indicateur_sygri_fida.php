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
$query_edit_data = "SELECT * FROM indicateur_sygri_fida where id_indicateur_sygri_fida='$id'";
$edit_data = mysql_query($query_edit_data, $pdar_connexion) or die(mysql_error());
$row_edit_data = mysql_fetch_assoc($edit_data);
$totalRows_edit_data = mysql_num_rows($edit_data);
if(isset($row_edit_data['indicateur_niveau1'])) $ais = explode(",", $row_edit_data['indicateur_niveau1']); else $ais=array();
}else $id=0;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_referentiel = "SELECT * FROM referentiel_indicateur where id_ref_ind not in (select referentiel from indicateur_sygri_fida where id_indicateur_sygri_fida!=$id) and type_ref_ind=3";
$liste_referentiel = mysql_query($query_liste_referentiel, $pdar_connexion) or die(mysql_error());
$row_liste_referentiel = mysql_fetch_assoc($liste_referentiel);
$totalRows_liste_referentiel = mysql_num_rows($liste_referentiel);

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_scp = "SELECT * FROM composante,sous_composante WHERE composante=id_composante ORDER BY code_sous_composante";
$liste_scp  = mysql_query($query_liste_scp , $pdar_connexion) or die(mysql_error());
$row_liste_scp = mysql_fetch_assoc($liste_scp);
$totalRows_liste_scp  = mysql_num_rows($liste_scp);  */

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_indicateur = "SELECT id_indicateur_sygri_niveau1_projet, indicateur_sygri_niveau1, referentiel FROM indicateur_sygri1_projet ORDER BY indicateur_sygri1_projet.ordre";
$liste_indicateur  = mysql_query($query_liste_indicateur , $pdar_connexion) or die(mysql_error());
$row_liste_indicateur = mysql_fetch_assoc($liste_indicateur );
$totalRows_liste_indicateur = mysql_num_rows($liste_indicateur );   */

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind, mode_calcul FROM referentiel_indicateur";
$liste_ind_ref  = mysql_query($query_liste_ind_ref , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref);
$totalRows_liste_ind_ref  = mysql_num_rows($liste_ind_ref);
$unite_ind_ref_array = array();
do{
 $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];
}while($row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref));   */


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
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification Indicateurs SYGRI 3<sup>&egrave;me</sup> Niveau":"Nouveau Indicateurs SYGRI 3<sup>&egrave;me</sup> Niveau"?></h4> </div>
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
          <label for="reference" class="col-md-3 control-label">Référence <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="reference" id="reference" value="<?php echo (isset($row_edit_data['reference']))?$row_edit_data['reference']:""; ?>" size="32" />
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
              <option></option>
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