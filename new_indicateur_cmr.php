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
//$query_edit_ind = "SELECT * FROM indicateur_objectif_global_cmr WHERE id_indicateur='$id'";
  $query_edit_ind = "SELECT * FROM ".$database_connect_prefix."indicateur_cmr WHERE id_ref_ind='$id'";
      	try{
    $edit_ind = $pdar_connexion->prepare($query_edit_ind);
    $edit_ind->execute();
    $row_edit_ind = $edit_ind ->fetch();
    $totalRows_edit_ind = $edit_ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//if(isset($row_edit_ind['responsable_collecte'])) $as = explode(",", $row_edit_ind['responsable_collecte']); else $as=array();
}


$query_liste_acteur = "SELECT id_acteur, nom_acteur FROM acteur where type_partenaire!=3 order by code_acteur, nom_acteur";
      	try{
    $liste_acteur = $pdar_connexion->prepare($query_liste_acteur);
    $liste_acteur->execute();
    $row_liste_acteur = $liste_acteur ->fetchAll();
    $totalRows_liste_acteur = $liste_acteur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//$query_liste_referentiel = "SELECT * FROM referentiel_indicateur WHERE type_ref_ind=3 ORDER BY code_ref_ind";
/*(isset($id))?"SELECT * FROM referentiel_indicateur WHERE id_ref_ind not in (select referentiel from indicateur_objectif_global_cmr, indicateur_objectif_global, objectif_global  where indicateur_og=id_objectif_global and id_indicateur_objectif_global=indicateur_og and id_indicateur!=$id) and type_ref_ind=3":*/
$query_liste_referentiel = "SELECT * FROM referentiel_indicateur order by code_ref_ind";
      	try{
    $liste_referentiel = $pdar_connexion->prepare($query_liste_referentiel);
    $liste_referentiel->execute();
    $row_liste_referentiel = $liste_referentiel ->fetchAll();
    $totalRows_liste_referentiel = $liste_referentiel->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_cmr = "SELECT * FROM ".$database_connect_prefix."indicateur_cadre_resultat where  projet='".$_SESSION["clp_projet"]."'";
      	try{
    $liste_cmr = $pdar_connexion->prepare($query_liste_cmr);
    $liste_cmr->execute();
    $row_liste_cmr = $liste_cmr ->fetchAll();
    $totalRows_liste_cmr = $liste_cmr->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_unite = "select * FROM ".$database_connect_prefix."unite_indicateur order by unite ASC";
    	   try{
    $liste_unite = $pdar_connexion->prepare($query_liste_unite);
    $liste_unite->execute();
    $row_liste_unite = $liste_unite ->fetchAll();
    $totalRows_liste_unite = $liste_unite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

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
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification indicateur du CMR":"Nouveau indicateur du CMR"?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

  <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="code_ref_ind" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" type="text" name="code_ref_ind" id="code_ref_ind" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_ind['code_ref_ind']; ?>" size="32" onblur="if(this.value!='') check_code('verif_code.php?t=indicateur_cmr&','w=code_ref_ind='+this.value+' and projet=<?php echo $_SESSION["clp_projet"]; ?>','code_zone');" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="intitule_ref_ind" class="col-md-3 control-label">Intitul&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="2" type="text" name="intitule_ref_ind" id="intitule_ref_ind"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_ind['intitule_ref_ind']; ?></textarea>
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
             <?php if($totalRows_liste_referentiel>0){ foreach($row_liste_referentiel as $row_liste_referentiel){  ?>
              <option value="<?php echo $row_liste_referentiel['id_ref_ind']; ?>" <?php if (isset($row_edit_ind["referentiel"]) && $row_liste_referentiel['id_ref_ind']==$row_edit_ind["referentiel"]) {echo "SELECTED";} ?>><?php echo $row_liste_referentiel['code_ref_ind'].": ".$row_liste_referentiel['intitule_ref_ind']; ?></option>
                <?php  }  } ?>
            </select>
          </div>
        </div>      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="indicateur_cl" class="col-md-3 control-label">Indicateur de r&eacute;sultat <span class="required">*</span></label>
          <div class="col-md-9">
            <select style="width: 460px;" name="resultat" id="resultat" class="select2-select-00 required" data-placeholder="S&eacute;lectionnez un indicateur de r&eacute;sultat">
              <option></option>
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_cmr>0){  foreach($row_liste_cmr as $row_liste_cmr){  ?>
              <option value="<?php echo $row_liste_cmr['code_indicateur_cr']; ?>" <?php if(isset($row_edit_ind['resultat']) && $row_edit_ind['resultat']==$row_liste_cmr["code_indicateur_cr"]) {echo "SELECTED";} ?>><?php echo $row_liste_cmr['code_indicateur_cr']." : ".$row_liste_cmr['intitule_indicateur_cr']; ?></option>
              <?php  }  } ?>
            </select>
          </div>
        </div>      </td>
    </tr>

    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="annee_reference" class="col-md-3 control-label">Ann&eacute;e de r&eacute;f&eacute;rence<span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" type="text" name="annee_reference" id="annee_reference" value="<?php echo (isset($row_edit_ind['annee_reference']))?$row_edit_ind['annee_reference']:"2018"; ?>" size="32" />
          </div>
		   <label for="reference_cmr" class="col-md-4 control-label">Situation de r&eacute;f&eacute;rence<span class="required">*</span></label>
          <div class="col-md-2">
            <input class="form-control required" type="text" name="reference_cmr" id="reference_cmr" value="<?php echo (isset($row_edit_ind['reference_cmr']))?$row_edit_ind['reference_cmr']:"ND"; ?>" size="32" />
          </div>
        </div>          </td>
      </tr>

    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="cible_dp" class="col-md-3 control-label">Valeur cible<span class="required">*</span></label>
          <div class="col-md-3">
           <input class="form-control required" type="text" name="cible_dp" id="cible_dp" value="<?php if(isset($_GET['id']))  {if($row_edit_ind['cible_cmr']==0 && isset($unite) && $unite=="Oui/Non") echo "Oui"; elseif($row_edit_ind['cible_cmr']==1 && isset($unite) && $unite=="Oui/Non") echo "Non"; elseif($row_edit_ind['cible_cmr']==-1) echo "n/a"; else echo $row_edit_ind['cible_cmr']; } else echo "N/A"; ?>" size="32" />
          </div>
		   <label for="unite_cmr" class="col-md-3 control-label">Unit&eacute; de mesure<span class="required">*</span></label>
          <div class="col-md-3">
             <select name="unite_cmr" id="unite_cmr" class="form-control required">
            <option value="">Selectionnez</option>
            <?php if($totalRows_liste_unite>0) { foreach($row_liste_unite as $row_liste_unite){   ?>
                <option value="<?php echo $row_liste_unite['unite'];?>"<?php if(isset($row_edit_ind['unite_cmr'])) {if (!(strcmp($row_liste_unite['unite'], $row_edit_ind['unite_cmr']))) {echo "SELECTED";} } ?>><?php echo $row_liste_unite['unite'];?></option>
                <?php
            }  }  ?>
            </select>
          </div>
        </div>          </td>
      </tr>

  
    <tr valign="top">
          <td>
        <div class="form-group">
          <label for="fonction_agregat" class="col-md-3 control-label">Mode de calcul <span class="required">*</span></label>
          <div class="col-md-3">
           <select name="fonction_agregat" id="fonction_agregat" class="form-control required">
            <option value="">Selectionnez</option>
              <option value="Somme" <?php if(isset($row_edit_ind['fonction_agregat'])) {if ($row_edit_ind['fonction_agregat']=="Somme") {echo "SELECTED";} } ?>>Somme</option>
              <option value="Moyenne" <?php if(isset($row_edit_ind['fonction_agregat'])) {if ($row_edit_ind['fonction_agregat']=="Moyenne") {echo "SELECTED";} } ?>>Moyenne</option>
              <option value="Report" <?php if(isset($row_edit_ind['fonction_agregat'])) {if ($row_edit_ind['fonction_agregat']=="Report") {echo "SELECTED";} } ?>>Report</option>
            </select>
          </div>
      
		   <label for="accueil1" class="col-md-3 control-label">Page d'accueil <span class="required">*</span></label>
          <div class="col-md-3"><span class="col-md-12">
                 Oui <input type="radio" name="accueil" id="accueil" value="1" <?php if ( isset($row_edit_ind['accueil']) && !(strcmp("1", $row_edit_ind['accueil']))) {echo 'checked="checked"';}  echo 'checked="checked"';  ?> >&nbsp;Non <input type="radio" name="accueil" id="accueil" value="0" <?php if (!isset($row_edit_ind['accueil']) || !(strcmp("0", $row_edit_ind['accueil']))) {echo 'checked="checked"';}  ?>>
          </span></div>
        </div> </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="intitule_activite" class="col-md-3 control-label">Responsables <span class="required">*</span></label>
          <div class="col-md-9">
           <select name="acteur[]" id="acteur" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez" multiple>
              <option></option>
              <option value="">Non-d&eacute;fini</option>
               <?php if($totalRows_liste_acteur>0) {  $expl = (isset($row_edit_ind["responsable_collecte"]) && !empty($row_edit_ind["responsable_collecte"]))?explode(',',$row_edit_ind["responsable_collecte"]):array(); foreach($row_liste_acteur as $row_liste_acteur){ ?>
              <option value="<?php echo $row_liste_acteur['id_acteur']; ?>" <?php if(in_array($row_liste_acteur['id_acteur'],$expl)) {echo "SELECTED";} ?>><?php echo $row_liste_acteur['nom_acteur']; ?></option>
                <?php  } } ?>
            </select>
          </div>
        </div>      </td>
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