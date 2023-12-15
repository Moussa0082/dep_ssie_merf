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
header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET["id"])) { $id=$_GET["id"];
$query_edit_ind = "SELECT * FROM indicateur_objectif_global_cmr WHERE id_indicateur='$id'";
try{
    $edit_ind = $pdar_connexion->prepare($query_edit_ind);
    $edit_ind->execute();
    $row_edit_ind = $edit_ind ->fetch();
    $totalRows_edit_ind = $edit_ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if(isset($row_edit_ind['responsable_collecte'])) $as = explode(",", $row_edit_ind['responsable_collecte']); else $as=array();

}
//indicateur resultat
$query_liste_ind_cl = "SELECT id_indicateur_objectif_global, intitule_indicateur_objectif_global FROM indicateur_objectif_global WHERE projet='".$_SESSION["clp_projet"]."'";
try{
    $liste_ind_cl = $pdar_connexion->prepare($query_liste_ind_cl);
    $liste_ind_cl->execute();
    $row_liste_ind_cl = $liste_ind_cl ->fetchAll();
    $totalRows_liste_ind_cl = $liste_ind_cl->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_acteur = "SELECT id_acteur, nom_acteur FROM acteur order by categorie,code_acteur, nom_acteur";
try{
    $liste_acteur = $pdar_connexion->prepare($query_liste_acteur);
    $liste_acteur->execute();
    $row_liste_acteur = $liste_acteur ->fetchAll();
    $totalRows_liste_acteur = $liste_acteur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_referentiel = "SELECT * FROM referentiel_indicateur WHERE type_ref_ind=3";
try{
    $liste_referentiel = $pdar_connexion->prepare($query_liste_referentiel);
    $liste_referentiel->execute();
    $row_liste_referentiel = $liste_referentiel ->fetchAll();
    $totalRows_liste_referentiel = $liste_referentiel->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_indicateur = "SELECT * FROM indicateur_objectif_global WHERE ".$_SESSION["clp_where"]."";
$liste_indicateur = mysql_query($query_liste_indicateur, $pdar_connexion) or die(mysql_error());
$row_liste_indicateur = mysql_fetch_assoc($liste_indicateur);
$totalRows_liste_indicateur = mysql_num_rows($liste_indicateur);*/

?>
<script>
	$().ready(function() {
		$("#form1").validate();
		 $(".modal-dialog", window.parent.document).width(700);
        $(".select2-select-00").select2({allowClear:true});
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});});

</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) { ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification indicateur d'impact du CMR":"Nouveau indicateur d'impact du CMR"?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="indicateur" class="col-md-3 control-label">Indicateur d'impact <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea name="indicateur" cols="32" rows="2" class="form-control required" id="indicateur"><?php echo (isset($row_edit_ind['intitule_indicateur_cmr_og']))?$row_edit_ind['intitule_indicateur_cmr_og']:""; ?></textarea>
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
         <option value="0" <?php if (isset($row_edit_ind["referentiel"]) && $row_edit_ind['referentiel']=="0") {echo "SELECTED";} ?>>Non-d&eacute;finie</option>
  			   <?php  if($totalRows_liste_referentiel>0) { foreach($row_liste_referentiel as $row_liste_referentiel){ ?>
              <option value="<?php echo $row_liste_referentiel['id_ref_ind']; ?>" <?php if (isset($row_edit_ind["referentiel"]) && $row_liste_referentiel['id_ref_ind']==$row_edit_ind["referentiel"]) {echo "SELECTED";} ?>><?php echo $row_liste_referentiel['code_ref_ind'].": ".$row_liste_referentiel['intitule_ref_ind']; ?></option>
                <?php  }  } ?>
            </select>
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="indicateur_cl" class="col-md-3 control-label">Indicateur OG <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="indicateur_cl" id="indicateur_cl" class="form-control required" >
              <option value="">Selectionnez</option>
  			   <?php  if($totalRows_liste_ind_cl>0) { foreach($row_liste_ind_cl as $row_liste_indicateur){ ?>
              <option value="<?php echo $row_liste_indicateur['id_indicateur_objectif_global']; ?>" <?php if (isset($row_edit_ind['indicateur_og']) && $row_liste_indicateur['id_indicateur_objectif_global']==$row_edit_ind['indicateur_og']) {echo "SELECTED";} ?>><?php echo (strlen($row_liste_indicateur['intitule_indicateur_objectif_global'])>55)?substr($row_liste_indicateur['intitule_indicateur_objectif_global'],0,55)." ...":$row_liste_indicateur['intitule_indicateur_objectif_global']; ?></option>
              <?php }} ?>
            </select>
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="annee_reference" class="col-md-5 control-label">Ann&eacute;e de r&eacute;f&eacute;rence <span class="required">*</span></label>
          <div class="col-md-5">
            <input class="form-control required" type="text" name="annee_reference" id="annee_reference" value="<?php echo (isset($row_edit_ind['annee_reference']))?$row_edit_ind['annee_reference']:""; ?>" size="32" />
          </div>
        </div>
      </td>
      <td>
        <div class="form-group">
          <label for="reference_cmr" class="col-md-5 control-label">Situation de r&eacute;f&eacute;rence <span class="required">*</span></label>
          <div class="col-md-5">
            <input class="form-control required" type="text" name="reference_cmr" id="reference_cmr" value="<?php echo (isset($row_edit_ind['reference_cmr']))?$row_edit_ind['reference_cmr']:""; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="cible_dp" class="col-md-3 control-label">Valeur cible <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="cible_dp" id="cible_dp" value="<?php if(isset($_GET['id']))  {if($row_edit_ind['cible_cmr']==0 && isset($unite) && $unite=="Oui/Non") echo "Oui"; elseif($row_edit_ind['cible_cmr']==1 && isset($unite) && $unite=="Oui/Non") echo "Non"; elseif($row_edit_ind['cible_cmr']==-1) echo "n/a"; else echo $row_edit_ind['cible_cmr']; } else echo "N/A"; ?>" size="32" />&nbsp;&nbsp;<strong>(<em><span class="Style3">NB</span>: la valeur cible du document du projet</em>) </strong>
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="intitule_activite" class="col-md-3 control-label">Responsables <span class="required">*</span></label>
          <div class="col-md-9">
            <select class="form-control required" cols="200" rows="3" type="text" name="acteur[]" multiple="multiple" size="5">
  			   <?php  if($totalRows_liste_acteur>0) { foreach($row_liste_acteur as $row_liste_acteur){ ?>

                                <option value="<?php echo $row_liste_acteur['id_acteur']?>"<?php if(isset($_GET['id'])) {if(in_array($row_liste_acteur['id_acteur'], $as, TRUE)) {echo "SELECTED";} } ?>><?php echo $row_liste_acteur['nom_acteur']?></option>
                                <?php
						} } ?></select>
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