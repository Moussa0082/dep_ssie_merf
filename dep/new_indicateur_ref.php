<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & D&eacute;veloppement: SEYA SERVICES */
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

$domact="";

if(isset($_GET['niveau']) && intval($_GET['niveau'])>=0) { $niveau=intval($_GET['niveau'])+1; }
if(isset($_GET["id"]) && intval($_GET["id"])>0)
{
  $id=intval($_GET["id"]);
  $query_liste_indicateur = "SELECT * FROM referentiel_indicateur WHERE id_ref_ind='$id'";
  try{
    $liste_indicateur = $pdar_connexion->prepare($query_liste_indicateur);
    $liste_indicateur->execute();
    $row_liste_indicateur = $liste_indicateur ->fetch();
    $totalRows_liste_indicateur = $liste_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}
/*
mysql_select_db($database_pdar_connexion, $pdar_connexion); //groupe_indicateur
$query_liste_cmr = "SELECT * FROM ".$database_connect_prefix."cmr WHERE projet='".$_SESSION["clp_programmes"]."'".((isset($niveau))?" and niveau=$niveau":"");
$liste_cmr  = mysql_query($query_liste_cmr , $pdar_connexion) or die(mysql_error());
$row_liste_cmr  = mysql_fetch_assoc($liste_cmr);
$totalRows_liste_cmr  = mysql_num_rows($liste_cmr);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT * FROM ".$database_connect_prefix."cmr_config WHERE projet='".$_SESSION["clp_programmes"]."' LIMIT 1";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);
$libelle = array(); $type = array();
if($totalRows_entete>0){ $libelle=explode(",",$row_entete["libelle"]); $type=explode(",",$row_entete["type"]); }*/

//partenaire
$query_partenaire = "SELECT * FROM ".$database_connect_prefix."acteur ";
try{
    $partenaire = $pdar_connexion->prepare($query_partenaire);
    $partenaire->execute();
    $row_partenaire = $partenaire ->fetchAll();
    $totalRows_partenaire = $partenaire->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//indicateur CMR
$query_indicateur = "SELECT * FROM ".$database_connect_prefix."referentiel_indicateur WHERE 1=1 ".($totalRows_liste_indicateur>0?" and id_ref_ind<>'$id'":"");
try{
    $indicateur = $pdar_connexion->prepare($query_indicateur);
    $indicateur->execute();
    $row_indicateur = $indicateur ->fetchAll();
    $totalRows_indicateur = $indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_unite = "SELECT unite FROM unite_indicateur";
try{
    $liste_unite = $pdar_connexion->prepare($query_liste_unite);
    $liste_unite->execute();
    $row_liste_unite = $liste_unite ->fetchAll();
    $totalRows_liste_unite = $liste_unite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete_p = "SELECT nombre FROM ".$database_connect_prefix."cadre_config WHERE projet='".$_SESSION["clp_programmes_2qc"]."' LIMIT 1";
$entete_p  = mysql_query_ruche($query_entete_p , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_entete_p  = mysql_fetch_assoc($entete_p);
$totalRows_entete_p  = mysql_num_rows($entete_p);
//$tab_niveau = array();

if($totalRows_entete_p>0){ $niveau=$row_entete_p["nombre"];}*/
//$niveau=1;
/*if(isset($_GET['niveau'])) $query_liste_activite_1 = "SELECT id, code,intitule FROM cadre_logique WHERE niveau=".($niveau)." and projet=".$_SESSION['clp_programmes_2qc']." ORDER BY niveau,code ASC";
else $query_liste_activite_1 = "SELECT id, code,intitule FROM cadre_logique WHERE  projet=".$_SESSION['clp_programmes_2qc']." ORDER BY niveau,code ASC";*/
$query_liste_activite_1 = sprintf("SELECT id_indicateur_i3n	 as id_indicateur_cr, code_indicateur_i3n as code, intitule_indicateur_i3n as intitule FROM ".$database_connect_prefix."indicateur_i3n  ORDER BY code_indicateur_i3n");
try{
    $liste_activite_1 = $pdar_connexion->prepare($query_liste_activite_1);
    $liste_activite_1->execute();
    $row_liste_activite_1 = $liste_activite_1 ->fetchAll();
    $totalRows_liste_activite_1 = $liste_activite_1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//echo $totalRows_liste_activite_1;
//exit;
 /*mysql_select_db($database_pdar_connexion, $pdar_connexion); //".$_SESSION["clp_where"]." and
$query_liste_composante = "SELECT * FROM  referentiel_indicateur where domaine like '%$domact%'  ORDER BY domaine, responsable";
$liste_composante  = mysql_query($query_liste_composante , $pdar_connexion) or die(mysql_error());
$row_liste_composante  = mysql_fetch_assoc($liste_composante );
$totalRows_liste_composante  = mysql_num_rows($liste_composante );

 mysql_select_db($database_pdar_connexion, $pdar_connexion);
  //$query_liste_region_concerne= "SELECT * FROM ".$database_connect_prefix."groupes_travail order by code_groupes_travail";
  $query_liste_region_concerne = "SELECT * FROM ".$database_connect_prefix."sous_direction ORDER BY direction, code_sd asc ";
  $liste_region_concerne = mysql_query_ruche($query_liste_region_concerne, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_region_concerne  = mysql_fetch_assoc($liste_region_concerne );
  $totalRows_liste_region_concerne  = mysql_num_rows($liste_region_concerne );
  
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_service = "SELECT * FROM ".$database_connect_prefix."service order by sous_direction, code_service";
  $liste_service  = mysql_query_ruche($query_liste_service , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_service  = mysql_fetch_assoc($liste_service);
  $totalRows_liste_service  = mysql_num_rows($liste_service);*/
$query_liste_zone = "SELECT * FROM type_zone";
try{
    $liste_zone = $pdar_connexion->prepare($query_liste_zone);
    $liste_zone->execute();
    $row_liste_zone = $liste_zone ->fetchAll();
    $totalRows_liste_zone = $liste_zone->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_site = "SELECT * FROM acteur";
try{
    $liste_site = $pdar_connexion->prepare($query_liste_site);
    $liste_site->execute();
    $row_liste_site = $liste_site ->fetchAll();
    $totalRows_liste_site = $liste_site->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>
<style>
table.fixed {table-layout:fixed; width:100%;}/*Setting the table width is important!*/
/*table.fixed td {overflow:hidden;}/*Hide text outside the cell.*/
</style>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
  $().ready(function() {
    $("#form1").validate();
	   $(".modal-dialog", window.parent.document).width(700);
        $(".select2-select-00").select2({allowClear:true});
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});});
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==0) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification":"Nouveau"?></h4> </div>
<div class="widget-content">
<form action="" class="" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
  <table border="0" align="center" cellspacing="5" cellpadding="0" width="100%" style="font-size:10px;" class="fixed">
    <tr valign="top">
      <td colspan="2">
       
          <label for="code_ref_ind" class="col-md-4 control-label">Code <span class="required">*</span></label>
          <div class="col-md-8">
            <input class="form-control required" type="text" name="code_ref_ind" id="code_ref_ind" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_indicateur['code_ref_ind']; ?>" size="32" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_indicateur['code_ref_ind']."'"; ?>) check_code('verif_code.php?t=referentiel_indicateur&','w=code_ref_ind='+this.value+' ','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
          <span class="help-block h0" id="code_zone_text">&nbsp;</span>          </div>               </td>
    </tr>
   
	    <tr valign="top">
      <td colspan="2">
          <label for="intitule_ref_ind" class="col-md-2 control-label">Intitul&eacute;</label>
          <div class="col-md-10">
            <textarea class="form-control "  rows="2" type="text" name="intitule_ref_ind" id="intitule_ref_ind"><?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_indicateur['intitule_ref_ind']; ?></textarea>
          </div>      </td>
    </tr>
	  <!--  <tr valign="top">
      <td colspan="2">
      <div class="form-group">
          <label for="indicateur_cr" class="col-md-3 control-label">Objectif CR<span class="required">*</span></label>
          <div class="col-md-9">
            <select style="width: 100%;" name="indicateur_cr" id="indicateur_cr" class="select2-select-00 required" data-placeholder="S&eacute;lectionnez un indicateur">
              <option></option>
              <?php //if($totalRows_liste_activite_1>0){ foreach($row_liste_activite_1 as $row_liste_activite_1){ ?>
              <option value="<?php //echo $row_liste_activite_1['id_indicateur_cr']; ?>" <?php //if (isset($row_liste_indicateur["indicateur_cr"]) && $row_liste_activite_1['id_indicateur_cr']==$row_liste_indicateur["indicateur_cr"]) {echo "SELECTED";} ?>><?php //echo $row_liste_activite_1['code'].": ".$row_liste_activite_1['intitule']; ?></option>
                <?php  //}  } ?>
            </select>
          </div>
        </div>                </td>
      </tr>-->
    <tr valign="top">
      <td colspan="2">
          <div class="form-group col-md-4">
              <label for="type_ref_ind" class="col-md-12 control-label">Type<span class="required">*</span></label>
             <select style="width: 100%" name="type_ref_ind" id="type_ref_ind" class="select2-select-00 required " title="Choisissez un type" data-placeholder="Choisissez un type">
                <option value="" ></option>
           <option value="Primaire" <?php if(isset($_GET['id'])) {if (!(strcmp("Primaire", $row_liste_indicateur['type_ref_ind']))) {echo "SELECTED";} } ?>>Primaire</option>
           <option value="Secondaire" <?php if(isset($_GET['id'])) {if (!(strcmp("Secondaire", $row_liste_indicateur['type_ref_ind']))) {echo "SELECTED";} } ?>>Secondaire</option>
			  </select>
          </div>

          <div class="form-group col-md-4">
              <label for="unite" class="col-md-12 control-label">Unit&eacute;<span class="required">*</span></label>
              <select style="width: 100%" name="unite" id="unite" class="select2-select-00 required " title="Choisissez une unit&eacute; de mesure" data-placeholder="Choisissez une unit&eacute; de mesure">
                <option value="" ></option>
            <?php if($totalRows_liste_unite>0) { foreach($row_liste_unite as $row_liste_unite){  ?>
                <option value="<?php echo $row_liste_unite['unite']?>" <?php if(isset($_GET['id'])) {if (!(strcmp($row_liste_unite['unite'], $row_liste_indicateur['unite']))) {echo "SELECTED";} } ?>><?php echo $row_liste_unite['unite'];?></option>
            <?php }  } ?>
              </select>
          </div>

          <div class="form-group col-md-4">
              <label for="periode" class="col-md-12 control-label">P&eacute;riodicit&eacute;<span class="required">*</span></label>
              <select style="width: 100%" name="periode" id="periode" class="select2-select-00 required " title="Choisissez une p&eacute;riodicit&eacute; / fr&eacute;quence de la mesure ou de collecte" data-placeholder="Choisissez une p&eacute;riodicit&eacute; / fr&eacute;quence de la mesure ou de collecte">
                <option value="" ></option>
                <option value="Mensuelle" <?php if(isset($_GET['id'])) {if (!(strcmp("Mensuelle", $row_liste_indicateur['periode']))) {echo "SELECTED";} } ?>>Mensuelle</option>
        <option value="Trimestrielle" <?php if(isset($_GET['id'])) {if (!(strcmp("Trimestrielle", $row_liste_indicateur['periode']))) {echo "SELECTED";} } ?>>Trimestrielle</option>
           <option value="Semestrielle" <?php if(isset($_GET['id'])) {if (!(strcmp("Semestrielle", $row_liste_indicateur['periode']))) {echo "SELECTED";} } ?>>Semestrielle</option>
                <option value="Annuelle" <?php if(isset($_GET['id'])) {if (!(strcmp("Annuelle", $row_liste_indicateur['periode']))) {echo "SELECTED";} } ?>>Annuelle</option>
	<option value="Bisannuelle" <?php if(isset($_GET['id'])) {if (!(strcmp("Bisannuelle", $row_liste_indicateur['periode']))) {echo "SELECTED";} } ?>>Bisannuelle</option>
                <option value="Autre" <?php if(isset($_GET['id'])) {if (!(strcmp("Autre", $row_liste_indicateur['periode']))) {echo "SELECTED";} } ?>>Autre</option>
              </select>
          </div>      </td>
    </tr>
    <tr><td colspan="2"><div style="margin:10px 0; height: 1px; background: #00a5f4;"><h4 style="position: relative;top: -9px;background: #FFF;color: #a9a5a5;margin: 0 30%;padding: 0;padding:0px;text-align: center;">M&eacute;thode de collecte</h4></div></td></tr>
    <tr>
      <td valign="top" colspan="2">
       <div class="form-group">
          <label for="sources" class="col-md-10 control-label">Source de donn&eacute;es <!--<span class="required">*</span>--></label>
          <div class="col-md-12">
            <select id="sources" name="sources[]" class="select2-select-00 col-md-12 full-width-fix" multiple size="5" title="Choisissez un ou plusieurs Source/cible" data-placeholder="Choisissez un ou plusieurs Source/cible">
              <option value="" ></option>
              <?php if($totalRows_liste_site>0){ $elem = isset($row_liste_indicateur["sources"])?explode(',',$row_liste_indicateur["sources"]):array(); foreach($row_liste_site as $row_partenaire){  ?>
              <option value="<?php echo $row_partenaire['code_acteur']; ?>" <?php if (in_array($row_partenaire['code_acteur'],$elem)) {echo "SELECTED";} ?>><?php echo $row_partenaire['nom_acteur']; ?></option>
              <?php } } ?>
            </select>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
          <label for="moyen_collecte" class="col-md-3 control-label">Moyen collecte / M&eacute;thodologie</label>
          <div class="col-md-9">
            <textarea class="form-control "  rows="2" type="text" name="moyen_collecte" id="moyen_collecte"><?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_indicateur['moyen_collecte']; ?></textarea>
          </div>      </td>
    </tr>
    <tr>
      <td valign="top" colspan="2">
       <div class="form-group">
          <label for="lien_indicateur" class="col-md-10 control-label">Liens avec d'autres indicateurs <!--<span class="required">*</span>--></label>
          <div class="col-md-12">
            <select id="lien_indicateur" name="lien_indicateur[]" class="select2-select-00 col-md-12 full-width-fix" multiple size="5" title="Choisissez un ou plusieurs indicateur" data-placeholder="Choisissez un ou plusieurs indicateurs">
              <option value="" ></option>
              <option value="0" <?php if ($totalRows_indicateur<=0 || (isset($row_liste_indicateur["lien_indicateur"]) && $row_liste_indicateur["lien_indicateur"]==0)) {echo "SELECTED";} ?> >Aucun</option>
              <?php if($totalRows_indicateur>0){ $elem = isset($row_liste_indicateur["lien_indicateur"])?explode(',',$row_liste_indicateur["lien_indicateur"]):array(); foreach($row_indicateur as $row_indicateur){  ?>
              <option value="<?php echo $row_indicateur['id_ref_ind']; ?>" <?php if (in_array($row_indicateur['id_ref_ind'],$elem)) {echo "SELECTED";} ?>><?php echo $row_indicateur['code_ref_ind']." : ".$row_indicateur['intitule_ref_ind']; ?></option>
              <?php } } ?>
            </select>
          </div>
        </div>      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
            <div class="form-group col-md-6">
              <label for="risque" class="col-md-12 control-label">Risques / Difficult&eacute;s<!--<span class="required">*</span>--></label>
              <textarea class="form-control" cols="200" rows="1" type="text" name="risque" id="risque"><?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_indicateur['risque']; ?></textarea>
          </div>     
		  
		   <div class="form-group col-md-6">
              <label for="limites_biais" class="col-md-12 control-label">m&eacute;thode de calcul<!--<span class="required">*</span>--></label>
              <textarea class="form-control" cols="200" rows="1" type="text" name="limites_biais" id="limites_biais"><?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_indicateur['limites_biais']; ?></textarea>
          </div> </td>
    </tr>
    <tr><td colspan="2"><div style="margin:10px 0; height: 1px; background: #00a5f4;"><h4 style="position: relative;top: -9px;background: #FFF;color: #a9a5a5;margin: 0 30%;padding: 0;padding:0px;text-align: center;">Gestion des donn&eacute;es</h4></div></td></tr>
    <tr>
      <td valign="top" colspan="2">
       <div class="form-group">
          <label for="collecte" class="col-md-12 control-label">Services ou organismes responsables de
la collecte des donn&eacute;es <!--<span class="required">*</span>--></label>
          <div class="col-md-12">
            <select id="collecte" name="collecte[]" class="select2-select-00 col-md-12 full-width-fix" multiple size="5" title="Choisissez un ou plusieurs organismes" data-placeholder="Choisissez un ou plusieurs services/organismes">
              <option value="" ></option>
              <?php if($totalRows_liste_site>0){ $elem = isset($row_liste_indicateur["collecte"])?explode(',',$row_liste_indicateur["collecte"]):array(); foreach($row_liste_site as $row_liste_site1){ ?>
              <option value="<?php echo $row_liste_site1['code_acteur']; ?>" <?php if (in_array($row_liste_site1['code_acteur'],$elem)) {echo "SELECTED";} ?>><?php echo $row_liste_site1['nom_acteur']; ?></option>
              <?php }   } ?>
            </select>
          </div>
        </div>      </td>
    </tr>

    <tr>
      <td valign="top">  <div class="form-group">
    <label for="validation" class="col-md-12 control-label">Validation des donn&eacute;es<!--<span class="required">*</span>--></label>
        <div class="col-md-12">
            <select id="validation" name="validation" class="select2-select-00 full-width-fix" size="5" title="Choisissez un service/organisme" data-placeholder="Choisissez un service/organisme">
                <option value="" ></option>
              <?php if($totalRows_liste_site>0){ foreach($row_liste_site as $row_liste_site2){ ?>
              <option value="<?php echo $row_liste_site2['code_acteur']; ?>" <?php if (isset($row_liste_indicateur["validation"]) && $row_liste_site2['code_acteur']==$row_liste_indicateur["validation"]) {echo "SELECTED";} ?>><?php echo $row_liste_site2['nom_acteur']; ?></option>
                <?php }   } ?>
            </select>
        </div>
        </div> </td>
      <td valign="top">  <div class="form-group">
            <label for="diffusion" class="col-md-12 control-label">Diffusion<!--<span class="required">*</span>--></label>
            <div class="col-md-12">
            <select id="diffusion" name="diffusion[]" class="select2-select-00 col-md-12 full-width-fix" multiple size="5" title="Choisissez un ou plusieurs organismes" data-placeholder="Choisissez un ou plusieurs services/organismes">
                <option value="" ></option>
              <?php if($totalRows_liste_site>0){ $elem = isset($row_liste_indicateur["diffusion"])?explode(',',$row_liste_indicateur["diffusion"]):array(); foreach($row_liste_site as $row_liste_site){ ?>
              <option value="<?php echo $row_liste_site['code_acteur']; ?>" <?php if (in_array($row_liste_site['code_acteur'],$elem)) {echo "SELECTED";} ?>><?php echo $row_liste_site['nom_acteur']; ?></option>
                <?php }  } ?>
            </select>
          </div>
        </div>  </td>
    </tr>
       <tr valign="top">
     <td colspan="2">
          <div class="form-group col-md-5">
              <label for="echelle" class="col-md-12 control-label">Echelle<span class="required">*</span></label>
            	<select name="echelle" id="echelle" class="select2-select-00 col-md-12 full-width-fix required" title="Choisissez" data-placeholder="Choisissez">
       <option value=""></option>
              <option value="01" <?php if(isset($_GET['id'])) {if ($row_liste_indicateur['echelle']=="01") {echo "SELECTED";} } ?>>Nationale</option>
              <option value="02" <?php if(isset($_GET['id'])) {if ($row_liste_indicateur['echelle']=="02") {echo "SELECTED";} } ?>>R&eacute;gionale</option>
			  <option value="03" <?php if(isset($_GET['id'])) {if ($row_liste_indicateur['echelle']=="03") {echo "SELECTED";} } ?>>Sous/Prefectorale</option>
			   <option value="04" <?php if(isset($_GET['id'])) {if ($row_liste_indicateur['echelle']=="04") {echo "SELECTED";} } ?>>Communale</option>
			    <option value="05" <?php if(isset($_GET['id'])) {if ($row_liste_indicateur['echelle']=="05") {echo "SELECTED";} } ?>>Cantonale</option>
				 <option value="06" <?php if(isset($_GET['id'])) {if ($row_liste_indicateur['echelle']=="06") {echo "SELECTED";} } ?>>Villageoise</option>
          <!--<?php //if($totalRows_liste_zone>0) { ?>
            <?php
                 // foreach($row_liste_zone as $row_liste_zone){ ?>
      <option value="<?php //echo $row_liste_zone['id_type']?>"<?php //if(isset($_GET['id'])) {if (!(strcmp($row_liste_zone['id_type'], $row_liste_indicateur['echelle']))) {echo "SELECTED";} } ?>><?php //echo $row_liste_zone['definition'];?></option>
            <?php
                 // } }
                   ?>-->
            </select>
          </div>

          <div class="form-group col-md-4">
              <label for="type_representation" class="col-md-12 control-label">Mod&egrave;le<span class="required">*</span></label>
                <select name="type_representation" id="type_representation" class="select2-select-00 col-md-12 full-width-fix required" title="Choisissez" data-placeholder="Choisissez">
                 <option value="" ></option>
              <option value="va" <?php if(isset($_GET['id'])) {if ($row_liste_indicateur['type_representation']=="va") {echo "SELECTED";} } ?>>Valeur absolue</option>
             <!-- <option value="vr" <?php //if(isset($_GET['id'])) {if ($row_liste_indicateur['type_representation']=="vr") {echo "SELECTED";} } ?>>Valeur relative</option>-->
              <option value="tn" <?php if(isset($_GET['id'])) {if ($row_liste_indicateur['type_representation']=="tn") {echo "SELECTED";} } ?>>Typologie quantitative</option>
              <option value="tt" <?php if(isset($_GET['id'])) {if ($row_liste_indicateur['type_representation']=="tt") {echo "SELECTED";} } ?>>Typologie qualitative</option>
              </select>
          </div> <!--  -->
		  
		   <div class="form-group col-md-3">
              <label for="paccueil" class="col-md-12 control-label">Cl&eacute; ?<span class="required">*</span></label>
             <select name="paccueil" id="paccueil" class="select2-select-00 col-md-12 full-width-fix required" title="Choisissez" data-placeholder="Choisissez">
                 <option value="" ></option>
                <option value="0" <?php if(isset($_GET['id'])) {if (!(strcmp("0", $row_liste_indicateur['paccueil']))) {echo "SELECTED";} } ?>>Non</option>
            <option value="1" <?php if(isset($_GET['id'])) {if (!(strcmp("1", $row_liste_indicateur['paccueil']))) {echo "SELECTED";} } ?>>Oui</option>
              </select>
          </div>		      </td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
<?php } ?>
<?php if(isset($_GET["niveau"])){ ?>
  <input type="hidden" name="niveau" value="<?php echo $_GET["niveau"]; ?>" />
<?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) {?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer l\'indicateur ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php }?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>
</div> </div>
<?php } ?>