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

    $query_liste_projet = "SELECT * FROM ".$database_connect_prefix."projet WHERE id_projet=$id ";

    try{

        $liste_projet = $pdar_connexion->prepare($query_liste_projet);

        $liste_projet->execute();

        $row_liste_projet = $liste_projet ->fetch();

        $totalRows_liste_projet = $liste_projet->rowCount();

    }catch(Exception $e){ die(mysql_error_show_message($e)); }

}



//Structure

$query_structure = "SELECT * FROM ".$database_connect_prefix."ugl ";

try{

    $structure = $pdar_connexion->prepare($query_structure);

    $structure->execute();

    $ligne = $structure ->fetchAll();

    $totalRows_ligne = $structure->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }

$tableauStructure=array();

foreach($ligne as $ligne){$tableauStructure[]=$ligne['code_ugl']."<>".$ligne['nom_ugl']; $tableauStructureV[$ligne['code_ugl']]=$ligne['nom_ugl'];}



$query_liste_ugl= "SELECT * FROM ".$database_connect_prefix."ugl order by nom_ugl";

try{

    $liste_ugl = $pdar_connexion->prepare($query_liste_ugl);

    $liste_ugl->execute();

    $ligne = $liste_ugl ->fetchAll();

    $totalRows_ligne = $liste_ugl->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }
/*
$tableauUgl=$tableauUglV=array();

foreach($ligne as $ligne){$tableauUgl[]=$ligne['code_ugl']."<>".$ligne['abrege_ugl']; $tableauUglV[$ligne['code_ugl']]=$ligne['nom_ugl'];}
*/


/*mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_region= "SELECT * FROM ".$database_connect_prefix."projet_region";

$liste_region = mysql_query($query_liste_region, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$tableauProjetRegion=array();

while($ligne=mysql_fetch_assoc($liste_region)){ $tableauProjetRegion[$ligne['structure']][$ligne['code_projet']]=$ligne['region'];}   */

$query_liste_acteur = "SELECT id_acteur, code_acteur, nom_acteur FROM acteur order by code_acteur, nom_acteur";
try{
    $liste_acteur = $pdar_connexion->prepare($query_liste_acteur);
    $liste_acteur->execute();
    $row_liste_acteur = $liste_acteur ->fetchAll();
    $totalRows_liste_acteur = $liste_acteur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

?>

<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>


<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".row-border").validate();
        $(".select2-select-00").select2({allowClear:true});
	});
</script>

<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) { ?>

<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification projet":"Nouveau projet"?></h4> </div>

<div class="widget-content">

<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">

<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

    <tr>

      <td valign="top">

        <div class="form-group">

          <label for="code_projet" class="col-md-10 control-label">Code <span class="required">*</span></label>

          <div class="col-md-11">

            <input class="form-control required" type="text" name="code_projet" id="code_projet" <?php if(isset ($_SESSION["clp_id"]) && ($_SESSION["clp_id"] != "admin") && isset($_GET["id"])) echo 'readonly="readonly"'; ?> value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_projet['code_projet']; ?>" size="32" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_projet['code_projet']."'"; ?>) check_code('verif_code.php?t=projet&','w=code_projet='+this.value+' and structure=<?php echo $_SESSION["clp_structure"]; ?>','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />

            <span class="help-block h0" id="code_zone_text">&nbsp;</span>          </div>
        </div>      </td>

      <td valign="to">

        <div class="form-group">

          <label for="sigle_projet" class="col-md-10 control-label">Sigle <span class="required">*</span></label>

          <div class="col-md-11">

            <input class="form-control required" type="text" name="sigle_projet" id="sigle_projet" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_projet['sigle_projet']; ?>" size="32" />
          </div>
        </div>      </td>
    </tr>

    <tr>

      <td colspan="2" valign="top">

        <div class="form-group">

          <label for="intitule_projet" class="col-md-10 control-label">Intitul&eacute; du projet <span class="required">*</span></label>

          <div class="col-md-12">

            <textarea class="form-control required" name="intitule_projet" id="intitule_projet" rows="2" cols="25"><?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_liste_projet['intitule_projet']:''; ?></textarea>
          </div>
        </div>      </td>
    </tr>

    <tr>

      <td valign="top">

        <div class="form-group">

          <label for="annee_debut" class="col-md-10 control-label">Année début <span class="required">*</span></label>

          <div class="col-md-11">

            <input class="form-control required" type="text" name="annee_debut" id="annee_debut" maxlength="4" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_projet['annee_debut']; ?>" size="32" />
          </div>
        </div>      </td>

      <td valign="to">

        <div class="form-group">

          <label for="annee_fin" class="col-md-10 control-label">Année fin <span class="required">*</span></label>

          <div class="col-md-11">

            <input class="form-control required" type="text" name="annee_fin" id="annee_fin" maxlength="4" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_projet['annee_fin']; ?>" size="32" />
          </div>
        </div>      </td>
    </tr>
	 <tr><td colspan="2">&nbsp;</td></tr>
    <tr>
      <td colspan="2" valign="top">    <div class="form-group">
          <label for="acteur" class="col-md-12 control-label">Structures porteuses <span class="required">*</span></label>
          <div class="col-md-12">
            <select style="width: 100%;" name="acteur"  class="col-md-9 select2-select-00 required" data-placeholder="S&eacute;lectionnez <?php //echo (isset($loc[$niveau-1]) && !empty($loc[$niveau-1]))?$loc[$niveau-1]:"Parent"; ?>" >
              			   <option value=" ">Selectionnez</option>
			  <?php  if($totalRows_liste_acteur>0) { foreach($row_liste_acteur as $row_liste_acteur){ ?>

                                <option value="<?php echo $row_liste_acteur['code_acteur']?>"<?php if (isset($row_liste_projet['structure']) && $row_liste_projet['structure']==$row_liste_acteur['code_acteur']) {echo "SELECTED";} ?>><?php echo $row_liste_acteur['nom_acteur']?></option>
                                <?php
						} } ?></select>
          </div>
        </div></td>
      </tr>

    <tr><td colspan="2">&nbsp;</td></tr>
  <tr>
      <td colspan="2" valign="top">    <div class="form-group">
          <label for="ugl" class="col-md-12 control-label">Unit&eacutes de gestion  <?php
						if(isset($_GET['id'])) echo $row_liste_projet["ugl"]; ?><span class="required">*</span></label>
          <div class="col-md-12">
            <select style="width: 100%;" name="ugl[]" multiple="multiple" class="col-md-9 select2-select-00 required" data-placeholder="S&eacute;lectionnez <?php //echo (isset($loc[$niveau-1]) && !empty($loc[$niveau-1]))?$loc[$niveau-1]:"Parent"; ?>" >
              			   <option value=" ">Selectionnez</option>
			  <?php  if($totalRows_ligne>0) {
$expl = (isset($row_liste_projet["ugl"]) && !empty($row_liste_projet["ugl"]))?explode(',',$row_liste_projet["ugl"]):array();
				  foreach($ligne as $ligne){ ?>

     <option value="<?php echo $ligne['code_ugl']?>"<?php if(isset($_GET['id'])) {if(in_array($ligne['code_ugl'], $expl, TRUE)) {echo "SELECTED";} } ?>><?php echo $ligne['abrege_ugl']?></option>
                                <?php
						} } ?></select>
          </div>
        </div></td>
      </tr>
    <tr><td colspan="2">&nbsp;</td></tr>

<!--    <tr bgcolor="#FFFFCC" >

      <td colspan="2" valign="top">

        <div class="form-group">

          <label for="" class="col-md-3 control-label">Structures <span class="required">*</span></label>

          <div class="col-md-9">

          <?php  /*

          if(isset($_GET["id"]) && intval($_GET["id"])>0) $a = explode("|", $row_liste_projet['structure']);  ?>

          <table width="100%" >

          <tr id="st_<?php echo $i; ?>">

          <?php $i = 1; foreach($tableauStructure as $vstructure){?>

          <?php

          $astructure = explode('<>',$vstructure);

          $istructure = $astructure[0];

          ?>

          <td><label for="structure_<?php echo $i; ?>" title="<?php echo $tableauStructureV[$istructure]; ?>" ><?php if(isset($astructure[1])) echo $astructure[1]; ?></label>

          <input title="<?php echo $tableauStructureV[$istructure]; ?>" name='structure[]' id='structure_<?php echo $i; ?>' type="checkbox"   <?php if(isset($_GET["id"]) && intval($_GET["id"])>0) { if(in_array($istructure, $a, TRUE)) echo "checked"; }?> size="5" value="<?php if(isset($istructure)) echo $istructure; ?>" onclick="if(this.checked==false) uncheck_this1(this,'st_<?php echo $i; ?>');"/></td>

          <?php $i++; }?>

          </tr>

          </table>

          <?php $i++; }*/?>

          </div>

        </div>

      </td>

    </tr>-->

<!--    <tr valign="top" bgcolor="#FFFFCC"  onclick="jQuery('.tr_toggle').toggleClass('hide');" style="cursor: pointer;" title="Cliquer pour Afficher/Masquer">

      <td colspan="2">

        <div class="form-group">

          <div class="col-md-3" align="center" ><b>Structures</b></div>

          <div class="col-md-9" align="center"><b>Zones d'intervention</b></div>

        </div>

      </td>

    </tr>-->

<?php /*$i = 1; foreach($tableauStructure as $vstructure){?>

<?php

$astructure = explode('<>',$vstructure);

$istructure = $astructure[0];

if(isset($_GET["id"]) && intval($_GET["id"])>0) $a = explode("|", $row_liste_projet['structure']); else $a =array();

?>

    <!--<tr valign="top" class="tr_toggle hide">

      <td colspan="2">

        <div class="form-group" style="border-bottom: solid 1px #d9d9d9;">

          <label class="col-md-3 control-label" title="<?php echo $tableauStructureV[$istructure]; ?>" style="background-color: #FFFFCC;"><?php if(isset($astructure[1])) echo $astructure[1]; ?>

          <input title="<?php echo $tableauStructureV[$istructure]; ?>" name='structure[]' id='structure_<?php echo $i; ?>' type="checkbox"   <?php if(isset($_GET['id'])) { if(in_array($istructure, $a, TRUE)) echo "checked"; }?> size="5" value="<?php if(isset($istructure)) echo $istructure; ?>" onclick="if(this.checked==false) uncheck_this1(this,'st_<?php echo $i; ?>');"/>

</label>

          <div class="col-md-9">

          <?php

          if(isset($_GET["id"]) && intval($_GET["id"])>0) $a = explode("|", $tableauProjetRegion[$istructure][$row_liste_projet['code_projet']]);  ?>

          <table width="100%" >

          <tr id="st_<?php echo $i; ?>">

          <?php $j = 1; foreach($tableauRegion as $vregion){?>

          <?php

          $aregion = explode('<>',$vregion);

          $iregion = $aregion[0];

          ?>

          <td><label title="<?php echo $tableauRegionV[$iregion]; ?>" for="region_<?php echo $i.'_'.$j; ?>" class="control-label"><?php if(isset($aregion[1])) echo $aregion[1]; ?></label>

          <input title="<?php echo $tableauRegionV[$iregion]; ?>" name='region<?php if(isset($istructure)) echo $istructure; ?>[]' id='region_<?php echo $i.'_'.$j; ?>' type="checkbox"   <?php if(isset($_GET['id'])) { if(in_array($iregion, $a, TRUE)) echo "checked"; }?> size="5" value="<?php if(isset($iregion)) echo $iregion; ?>"/></td>

          <?php $j++; }?>

          </tr>

          </table>

          </div>

        </div>

      </td>

    </tr>-->

    <!--<tr valign="top" bgcolor="#FFFFCC">

      <td colspan="2">

        <div class="form-group">

          <label for="region" class="col-md-3 control-label">Zone d'intervention <span class="required">*</span></label>

          <div class="col-md-9">

          <?php

          if(isset($_GET["id"]) && intval($_GET["id"])>0) $a = explode("|", $row_liste_projet['region']); ?>

          <table width="100%">

          <tr>

          <?php foreach($tableauRegion as $vregion){?>

          <?php

          $aregion = explode('<>',$vregion);

          $iregion = $aregion[0];

          ?>

          <td><label title="<?php echo $tableauRegionV[$iregion]; ?>" for="region_<?php echo $i; ?>" class="control-label"><?php if(isset($aregion[1])) echo $aregion[1]; ?></label>

          <input title="<?php echo $tableauRegionV[$iregion]; ?>" name='region[]' id='region_<?php echo $i; ?>' type="checkbox"   <?php if(isset($_GET['id'])) { if(in_array($iregion, $a, TRUE)) echo "checked"; }?> size="5" value="<?php if(isset($iregion)) echo $iregion; ?>"/></td>

          <?php $i++; } ?>

          </tr>

          </table>

          </div>

        </div>

      </td>

    </tr>-->

<?php $i++; }*/  ?>

<!--    <tr>

      <td valign="top" colspan="2">

        <div class="form-group">

          <label for="adresse" class="col-md-10 control-label">Adresse <span class="required">*</span></label>

          <div class="col-md-12">

            <textarea class="form-control required" name="adresse" id="adresse" rows="1" cols="25"><?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_liste_projet['adresse']:''; ?></textarea>

          </div>

        </div>

      </td>

    </tr>

    <tr>

      <td valign="top" colspan="2">

        <div class="form-group">

          <label for="contact" class="col-md-10 control-label">Contact <span class="required">*</span></label>

          <div class="col-md-12">

            <textarea class="form-control" name="contact" id="contact" rows="1" cols="25"><?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_liste_projet['contact']:''; ?></textarea>

          </div>

        </div>

      </td>

    </tr>-->

    <tr>

      <td valign="top" colspan="2">

        <div class="form-group">

          <label for="contact" class="col-md-2 control-label">Actif <span class="required">*</span></label>

          <div class="col-md-6">

            Oui <input type="radio" name="actif" value="0" <?php if(isset($row_liste_projet['actif'])){ if (!(strcmp("0", $row_liste_projet['actif']))) {echo 'checked="checked"';} if(!isset($_GET["id"])) echo 'checked="checked"'; } ?> >&nbsp;Non <input type="radio" name="actif" value="1" <?php if(isset($row_liste_projet['actif'])){ if (!(strcmp("1", $row_liste_projet['actif']))) {echo 'checked="checked"';} } ?>>
          </div>
        </div>      </td>
    </tr>

<!--    <tr>

      <td valign="top" colspan="2">

        <div class="form-group">

          <label for="photo" class="col-md-3 control-label">Photo <span class="required">*</span></label>

          <div class="col-md-3 pull-left">

          <div id="photo_prev">

          <?php if(isset($_GET["id"]) && intval($_GET["id"])>0 && file_exists("./images/projet/img_".intval($_GET["id"]).".jpg")) { ?>

          <img src="<?php echo "./images/projet/img_".$row_liste_projet['code_projet'].".jpg"; ?>" width='80' height='80' alt='preview'>

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

    <?php if(isset($_GET["id"])){ ?>

  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />

  <?php } ?>

  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />

  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">

<?php if(isset($_GET["id"]) && intval($_GET["id"])>0 && isset($_SESSION["clp_niveau"]) && $_SESSION["clp_niveau"]==1) { ?>

<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">

<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce projet ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />

<?php } ?>

<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">

  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->

</div>

</form>



</div> </div>

<?php } ?>