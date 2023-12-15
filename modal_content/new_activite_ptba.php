<?php

///////////////////////////////////////////////

/*                 SSE                       */

/*	Conception & Développement: SEYA SERVICES */

///////////////////////////////////////////////

session_start();

$path = '../';

include_once $path.'system/configuration.php';

$config = new Config;



if (!isset ($_SESSION["clp_id"])) {

  //header(sprintf("Location: %s", "./"));

  exit;

}

include_once $path.$config->sys_folder . "/database/db_connexion.php";

//header('Content-Type: text/html; charset=UTF-8');



if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y");

if(isset($_GET["id_act"])) { $id=$_GET["id_act"];}

//echo $id_cp;

$editFormAction = $_SERVER['PHP_SELF'];

/*if (isset($_SERVER['QUERY_STRING'])) {

  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);

}*/



$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

if(isset($_GET["id_act"]))

{

  $id=$_GET["id_act"];
  $query_edit_act = "SELECT * FROM ".$database_connect_prefix."ptba where id_ptba='$id'";
    	   try{
    $edit_act = $pdar_connexion->prepare($query_edit_act);
    $edit_act->execute();
    $row_edit_act = $edit_act ->fetch();
    $totalRows_edit_act = $edit_act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  //if(isset($row_edit_act['acteur_conserne'])) $as = explode(",", $row_edit_act['acteur_conserne']);

  //else $as=array();

}

else $id=-1;

 $ugl_projet = str_replace("|",",",$_SESSION["clp_projet_ugl"]);//implode(",",(explode("|", $_SESSION["clp_projet_ugl"]));

if(isset($annee))

{

 
/*
  $query_liste_region_concerne= "SELECT region_concerne FROM ".$database_connect_prefix."ugl where FIND_IN_SET( code_ugl, '".$ugl_projet."' )";
  	   try{
    $liste_region_concerne = $pdar_connexion->prepare($query_liste_region_concerne);
    $liste_region_concerne->execute();
    $row_liste_region_concerne = $liste_region_concerne ->fetchAll();
    $totalRows_liste_region_concerne = $liste_region_concerne->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$regionconcerne_array = array(); 
 if($totalRows_liste_region_concerne>0) { foreach($row_liste_region_concerne as $row_liste_region_concerne){  
    if(isset($row_liste_region_concerne["region_concerne"]) && $row_liste_region_concerne["region_concerne"]!="|") $regionconcerne_array[] = $row_liste_region_concerne["region_concerne"]; } }

  $tliste_region_concerne=(str_replace("|",",",implode(",",$regionconcerne_array)));*/

$query_liste_ugl = "SELECT * FROM ".$database_connect_prefix."ugl order by code_ugl  ";
    	   try{
    $liste_ugl = $pdar_connexion->prepare($query_liste_ugl);
    $liste_ugl->execute();
    $row_liste_ugl = $liste_ugl ->fetchAll();
    $totalRows_liste_ugl = $liste_ugl->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  
$query_liste_responsable = "SELECT distinct fonction FROM ".$database_connect_prefix."fonction where fonction!='Administrateur'";
    	   try{
    $liste_responsable = $pdar_connexion->prepare($query_liste_responsable);
    $liste_responsable->execute();
    $row_liste_responsable = $liste_responsable ->fetchAll();
    $totalRows_liste_responsable = $liste_responsable->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  
  $query_liste_region= "SELECT code_ugl, nom_ugl FROM ".$database_connect_prefix."ugl where FIND_IN_SET( code_ugl, '".$ugl_projet."' ) order by code_ugl";
	   try{
    $liste_region = $pdar_connexion->prepare($query_liste_region);
    $liste_region->execute();
    $row_liste_region = $liste_region ->fetchAll();
    $totalRows_liste_region = $liste_region->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableauRegion = array(); 
 if($totalRows_liste_region>0) { foreach($row_liste_region as $row_liste_region){  
	 $tableauRegion[]=$row_liste_region['code_ugl']."<>".$row_liste_region['nom_ugl'];
 } }
 
 
$query_entete = "SELECT nombre FROM ".$database_connect_prefix."niveau_config WHERE projet='".$_SESSION["clp_projet"]."' LIMIT 1";
    	   try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$code_len = $row_entete["nombre"];



$query_liste_activite = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE  niveau='$code_len' and projet='".$_SESSION["clp_projet"]."'";
	   try{
    $liste_activite = $pdar_connexion->prepare($query_liste_activite);
    $liste_activite->execute();
    $row_liste_activite = $liste_activite ->fetchAll();
    $totalRows_liste_activite = $liste_activite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$activite_array = array(); 
 if($totalRows_liste_activite>0) { foreach($row_liste_activite as $row_liste_activite1){  
  $activite_array[$row_liste_activite1["code"]] = $row_liste_activite1["intitule"];
 } }
 


                       //where structure='".$_SESSION["clp_structure"]."'
$query_liste_prestataire = "SELECT * FROM ".$database_connect_prefix."acteur order by code_acteur  ";
    	   try{
    $liste_prestataire = $pdar_connexion->prepare($query_liste_prestataire);
    $liste_prestataire->execute();
    $row_liste_prestataire = $liste_prestataire ->fetchAll();
    $totalRows_liste_prestataire = $liste_prestataire->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }



$query_liste_type_act = "SELECT * FROM type_activite ORDER BY categorie ASC";
 try{
    $liste_type_act = $pdar_connexion->prepare($query_liste_type_act);
    $liste_type_act->execute();
    $row_liste_type_act = $liste_type_act ->fetchAll();
    $totalRows_liste_type_act = $liste_type_act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


$tableauMois= array('T1','T2','T3','T4');
$tableauMois= array('Jan','Fev','Mar','Avr','Mai','Juin','Juil','Aout','Sep','Oct','Nov','Dec');
$tableauMois2= array('J','F','M','A','M','J','J','A','S','O','N','D');



 //foreach($regionconcerne_array as $brc) { echo $brc; }

 //$brc=explode("|",$regionconcerne_array);

 //explode("|", $regionconcerne_array)
}
?>

<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>

<script>

	$().ready(function() {

		// validate the comment form when it is submitted

		$(".form-horizontal").validate();

        $(".modal-dialog", window.parent.document).width(700);

        $(".select2-select-00").select2({allowClear:true});

	});

</script>



<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id_act"]) && intval($_GET["id_act"])>0)?"Modification":"Nouvelle"; echo " activit&eacute; de PTBA "; ?></h4> </div>

<div class="widget-content">

<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">

<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

	    

  
   <tr valign="top">

      <td colspan="2">

        <div class="form-group">

          <label for="intitule_activite_ptba" class="col-md-3 control-label">Libell&eacute; SE  <span class="required">*</span></label>

          <div class="col-md-9">

            <textarea name="intitule_activite_ptba" cols="32" rows="1" class="form-control required" id="intitule_activite_ptba"><?php if(isset($_GET["id_act"])) echo $row_edit_act['intitule_activite_ptba']; ?></textarea>
		  </div>
        </div>      </td>
    </tr>

  <tr valign="top">

      <td colspan="2">

        <div class="form-group">

          <label for="code_activite_ptba" class="col-md-3 control-label">Code analytique<span class="required">*</span></label>

          <div class="col-md-9">

            <select name="code_activite_ptba" id="code_activite_ptba" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez Activit&eacute;">

              <option></option>

              <option value="">Selectionnez</option>

              <option value="RAS">Non-d&eacute;finie</option>

              <?php if($totalRows_liste_activite>0) { foreach($row_liste_activite as $row_liste_activite){   ?>

              <option value="<?php echo $row_liste_activite['code']; ?>" <?php if (isset($row_edit_act["code_activite_ptba"]) && $row_liste_activite['code']==$row_edit_act["code_activite_ptba"]) {echo "SELECTED";} ?>><?php echo $row_liste_activite['code'].": ".$row_liste_activite['intitule']; ?></option>

                <?php  } } ?>
            </select>
          </div>
        </div>      </td>
    </tr>
  <tr valign="top">
    <td colspan="2"><div class="form-group">
          <label for="type_act" class="col-md-3 control-label">Type d'activit&eacute; <span class="required">*</span></label>
          <div class="col-md-6">
			  <select name="type_act" id="type_act" style="width: 460px;" class="required">
              <!-- <option></option>
              <option value="">Selectionnez</option>
              <?php //if($totalRows_liste_type_act>0){ foreach($row_liste_type_act as $row_liste_type_act){ ?>
              <option value="<?php //echo $row_liste_type_act['id_type']; ?>" <?php //if (isset($row_edit_act["isous_composante"]) && $row_liste_type_act['id_type']==$row_edit_act["isous_composante"]) {echo "SELECTED";} ?>><?php //echo $row_liste_type_act['categorie'].": ".$row_liste_type_act['type_activite']; ?></option>
              <?php // }  } ?>-->
 			   <option value="0" <?php if (isset($row_edit_act["isous_composante"]) && "0"==$row_edit_act["isous_composante"]) {echo "SELECTED";} ?>>Aucun type</option>
            </select>
          </div>
      </div></td>
  </tr>
  <tr valign="top">

      <td colspan="2">

       
<div class="form-group">
          <label for="acteur_conserne" class="col-md-3 control-label">Acteurs<span class="required">*</span></label>
          <div class="col-md-9">
            <select name="acteur_conserne[]" id="acteur_conserne" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez un acteur" multiple>
              <option></option>
              <option value="">Non-d&eacute;fini</option>
               <?php if($totalRows_liste_prestataire>0) {  $expl = (isset($row_edit_act["acteur_conserne"]) && !empty($row_edit_act["acteur_conserne"]))?explode(',',$row_edit_act["acteur_conserne"]):array(); foreach($row_liste_prestataire as $row_liste_prestataire){ ?>
              <option value="<?php echo $row_liste_prestataire['id_acteur']; ?>" <?php if(in_array($row_liste_prestataire['id_acteur'],$expl)) {echo "SELECTED";} ?>><?php echo $row_liste_prestataire['nom_acteur']; ?></option>
                <?php  } } ?>
            </select>
          </div>
      </div>        </td>
    </tr>

	 <tr valign="top">

      <td colspan="2">

        <div class="form-group">

          <label for="responsable" class="col-md-3 control-label">Responsable </label>

          <div class="col-md-9">
            <select name="responsable" id="responsable" class="form-control required">
            <option value="">Selectionnez</option>
             <?php if($totalRows_liste_responsable>0) { foreach($row_liste_responsable as $row_liste_responsable){   ?>
            <option value="<?php echo $row_liste_responsable['fonction'];?>" <?php if (isset($row_edit_act['responsable']) && $row_liste_responsable['fonction']==$row_edit_act['responsable']) {echo "SELECTED";} ?>><?php echo $row_liste_responsable['fonction'];?></option>
            <?php  }  } ?>
          </select>
          </div>
        </div>        </td>
    </tr>
	 <tr valign="top">

      <td colspan="2">

        <div class="form-group">

          <label for="acteur_ptba" class="col-md-3 control-label">Autres resp.</label>

          <div class="col-md-9">

    <textarea name="acteur_ptba" cols="32" rows="1" class="form-control" id="acteur_ptba"><?php if(isset($_GET["id_act"])) echo $row_edit_act['acteur_ptba']; else echo "Aucun";?></textarea>
		  </div>
        </div>      </td>
    </tr>

<!--    <tr valign="top">

      <td colspan="2">

        <div class="form-group">

          <label for="produit" class="col-md-3 control-label">Indicateur produit <span class="required">*</span></label>

          <div class="col-md-9">

          <select name="produit" id="produit" class="form-control required" >

              <option value="">Selectionnez</option>

<?php /*do { ?>

                            <option value="<?php echo $row_liste_produit['id_indicateur']?>"<?php if(isset($_GET["id_act"]) && intval($_GET["id_act"])>0) {if (!(strcmp($row_liste_produit['id_indicateur'], $row_edit_act['produit']))) {echo "SELECTED";} } ?>><?php echo substr($row_liste_produit['intitule_indicateur'],0, 70)."...";?></option>

                            <?php

} while ($row_liste_produit = mysql_fetch_assoc($liste_produit));

  $rows = mysql_num_rows($liste_produit);

  if($rows > 0) {

      mysql_data_seek($liste_produit, 0);

	  $row_liste_produit = mysql_fetch_assoc($liste_produit);

  }*/

?>

          </select>

          </div>

        </div>

      </td>

    </tr>-->

   

    <tr valign="top" bgcolor="#CCFFFF">

      <td colspan="2">

        <div class="form-group">

          <label for="seuil" class="col-md-3 control-label">Chronogramme</label>

          <div class="col-md-9">

          <?php

          if(isset($_GET["id_act"])) $a = explode(",", $row_edit_act['debut']); ?>

          <table>

          <tr>

          <?php $i = 1; foreach($tableauMois as $vmois){?>

          <?php

         // $amois = explode('<>',$vmois);

         // $imois = $amois[0];

          ?>

          <td><label for="mois_<?php echo $i; ?>" class="control-label"><?php if(isset($vmois)) echo $vmois; ?></label>

          <input name='mois[]' id='mois_<?php echo $i; ?>' type="checkbox"   <?php if(isset($_GET['id_act'])) { if(in_array($vmois, $a, TRUE)) echo "checked"; }?> size="5" value="<?php if(isset($vmois)) echo $vmois; ?>" class=""/></td>

          <?php $i++; } ?>
          </tr>
          </table>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">

      <td colspan="2">
	  <div class="form-group">
          <label for="acteur_conserne" class="col-md-3 control-label">UGL<span class="required">*</span></label>
          <div class="col-md-9">
            <select name="region[]" id="region" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez" multiple>
              <option></option>
              <option value="">Non-d&eacute;fini</option>
               <?php if($totalRows_liste_ugl>0) {  $expl = (isset($row_edit_act["region"]) && !empty($row_edit_act["region"]))?explode(',',$row_edit_act["region"]):array(); foreach($row_liste_ugl as $row_liste_ugl){ ?>
              <option value="<?php echo $row_liste_ugl['code_ugl']; ?>" <?php if(in_array($row_liste_ugl['code_ugl'],$expl)) {echo "SELECTED";} ?>><?php echo $row_liste_ugl['abrege_ugl']; ?></option>
                <?php  } } ?>
            </select>
          </div>
      </div>       </td>
    </tr>

	
	 <tr valign="top">

      <td colspan="2">

        <div class="form-group">

          <label for="observation" class="col-md-3 control-label">Observations </label>

          <div class="col-md-9">

     <textarea name="observation" cols="32" rows="1" class="form-control " id="observation"><?php if(isset($_GET["id_act"])) echo $row_edit_act['observation']; //else echo(str_replace("|",",",implode("|",$regionconcerne_array)));?></textarea>
          </div>
        </div>        </td>
    </tr>

    <tr><td><br /></td></tr>
</table>

<div class="form-actions">

<input name="annee" id="annee" type="hidden" value="<?php echo intval($_GET["annee"]); ?>" size="32" alt="">

  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id_act"]) && intval($_GET["id_act"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />

  <input name="<?php if(isset($_GET["id_act"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id_act"])) echo $_GET["id_act"]; else echo "MM_insert" ; ?>" size="32" alt="">

<?php if(isset($_GET["id_act"])) { ?>

<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">

<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce PTBA ?','<?php echo $_GET["id_act"]; ?>');" class="btn btn-danger pull-left" value="Supprimer" />

<?php } ?>

<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">

  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->

</div>

</form>



</div> </div>