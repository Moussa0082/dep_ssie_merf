<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT NETWORK */
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

$editFormAction = $_SERVER['PHP_SELF'];
/*if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}*/

if(isset($_GET["id_mar"])) { $id=$_GET["id_mar"];
$query_edit_marche = "SELECT * FROM ".$database_connect_prefix."plan_marche WHERE id_marche='$id'";
       try{
    $edit_marche = $pdar_connexion->prepare($query_edit_marche);
    $edit_marche->execute();
    $row_edit_marche = $edit_marche ->fetch();
    $totalRows_edit_marche = $edit_marche->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_etape_plan_marche = "SELECT * FROM ".$database_connect_prefix."etape_plan_marche where marche='$id'";
$etape_plan_marche = mysql_query($query_etape_plan_marche, $pdar_connexion) or die(mysql_error());
$row_etape_plan_marche = mysql_fetch_assoc($etape_plan_marche);
$totalRows_etape_plan_marche = mysql_num_rows($etape_plan_marche);*/
}

$query_liste_categorie = "SELECT * FROM ".$database_connect_prefix."categorie_marche ORDER BY code_categorie asc";
       try{
    $liste_categorie = $pdar_connexion->prepare($query_liste_categorie);
    $liste_categorie->execute();
    $row_liste_categorie = $liste_categorie ->fetchAll();
    $totalRows_liste_categorie = $liste_categorie->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_methode = "SELECT * FROM ".$database_connect_prefix."methode_marche ORDER BY sigle asc";
       try{
    $liste_methode = $pdar_connexion->prepare($query_liste_methode);
    $liste_methode->execute();
    $row_liste_methode = $liste_methode ->fetchAll();
    $totalRows_liste_methode = $liste_methode->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_composante = "SELECT code,intitule FROM ".$database_connect_prefix."activite_projet WHERE projet='".$_SESSION["clp_projet"]."' and niveau=2 order by code";
       try{
    $liste_composante = $pdar_connexion->prepare($query_liste_composante);
    $liste_composante->execute();
    $row_liste_composante = $liste_composante ->fetchAll();
    $totalRows_liste_composante = $liste_composante->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_etape = "SELECT * FROM ".$database_connect_prefix."etape_marche ORDER BY groupe, code asc, id_etape asc LIMIT 1";
try{
    $liste_etape = $pdar_connexion->prepare($query_liste_etape);
    $liste_etape->execute();
    $row_liste_etape = $liste_etape ->fetchAll();
    $totalRows_liste_etape = $liste_etape->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//liste version
$query_liste_version = "SELECT * FROM ".$database_connect_prefix."version_plan_marche order by numero_version asc";
try{
    $liste_version = $pdar_connexion->prepare($query_liste_version);
    $liste_version->execute();
    $row_liste_version = $liste_version ->fetchAll();
    $totalRows_liste_version = $liste_version->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

?>

<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
        $(".modal-dialog", window.parent.document).width(800);
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
<?php $datal=""; if($totalRows_liste_version>0){ foreach($row_liste_version as $row_liste_version1){   $datal .= '"'.trim($row_liste_version1['numero_version']).'",';  } $datal = substr($datal,0,-1); }  ?>
        $("#version").select2({tags:[<?php echo $datal; ?>]});
	});
</script>
<style>
div#s2id_code_activite .select2-choice { margin-top: -6px; border: none;}
.select2-container-multi .select2-choices { margin-top: -6px; border: none;}
</style>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id_mar"]))?"Modification":"Nouveau"; echo " march&eacute; "; ?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

    <tr valign="top">
      <td width="50%">
      <div class="form-group">
          <label for="code_marche" class="col-md-5 control-label">Code<span class="required">*</span></label>
          <div class="col-md-6">
            <input type="text" class="form-control required" name="code_marche" value="<?php if(isset($_GET["id_mar"]) ) echo $row_edit_marche['code_marche'];  ?>">
          </div>
        </div>
      </td>
      <td width="50%">
      <div class="form-group">
          <label for="composante" class="col-md-5 control-label">Sous/Composante<span class="required">*</span></label>
          <div class="col-md-6">
            <select name="composante" id="composante" class="form-control required"  >
              <option value="">Selectionnez</option>
                            <?php
foreach($row_liste_composante as $row_liste_composante1){ $libelle = (strlen($row_liste_composante1['intitule'])>70)?$row_liste_composante1['code'].": ".substr($row_liste_composante1['intitule'],0,70)."...":$row_liste_composante1['code'].": ".$row_liste_composante1['intitule'];?>
                            <option value="<?php echo $row_liste_composante1['code']?>"<?php if(isset($row_edit_marche["composante"])) {if (!(strcmp($row_liste_composante1['code'], $row_edit_marche["composante"]))) {echo "SELECTED";} } ?>><?php echo $libelle; ?></option>
                            <?php }    ?>
            </select>
          </div>
        </div>
        </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="intitule" class="col-md-3 control-label">Intitul&eacute; du march&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" name="intitule" cols="25" rows="2"><?php if(isset($_GET["id_mar"]) ) echo $row_edit_marche['intitule'];  ?></textarea>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="montant_usd" class="col-md-5 control-label">Montant Estimatif (F CFA)  <span class="required">*</span></label>
          <div class="col-md-6">
            <input type="text" class="form-control required" name="montant_usd" value="<?php if(isset($_GET["id_mar"]) ) echo $row_edit_marche['montant_usd'];  ?>">
          </div>
        </div>      </td>
      <td>
        <div class="form-group">
          <label for="lot" class="col-md-5 control-label">Nb de lots <span class="required">*</span></label>
          <div class="col-md-6">
            <input type="text" class="form-control required" name="lot" value="<?php if(isset($_GET["id_mar"]) ) echo $row_edit_marche['lot'];  ?>">
          </div>
        </div>      </td>
    </tr>
	<tr valign="top">
      <td>
        <div class="form-group">
          <label for="categorie" class="col-md-5 control-label">Cat&eacute;gorie <span class="required">*</span></label>
          <div class="col-md-6">
            <select name="categorie" id="categorie" class="form-control required"  >
              <option value="">Selectionnez</option>
                            <?php if($totalRows_liste_categorie>0){
foreach($row_liste_categorie as $row_liste_categorie1){ $libelle = (strlen($row_liste_categorie1['nom_categorie'])>70)?substr($row_liste_categorie1['nom_categorie'],0,70)."...":$row_liste_categorie1['nom_categorie']; ?>
                            <option value="<?php echo $row_liste_categorie1['code_categorie']?>"<?php if(isset($row_edit_marche["categorie"])) {if (!(strcmp($row_liste_categorie1['code_categorie'], $row_edit_marche["categorie"]))) {echo "SELECTED";} } ?>><?php echo $libelle; ?></option>
                            <?php }  } ?>
            </select>
          </div>
        </div>      </td>
      <td>
             </td>
    </tr>

    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="methode" class="col-md-5 control-label">M&eacute;thode <span class="required">*</span></label>
          <div class="col-md-6">
            <select name="methode" id="methode" class="form-control required"  >
              <option value="">Selectionnez</option>
                            <?php if($totalRows_liste_methode>0){
foreach($row_liste_methode as $row_liste_methode1){  $libelle = (strlen($row_liste_methode1['sigle'])>70)?substr($row_liste_methode1['sigle'],0,70)."...":$row_liste_methode1['sigle']; ?>
                            <option value="<?php echo $row_liste_methode1['sigle']?>"<?php if(isset($row_edit_marche["methode"])) {if (!(strcmp($row_liste_methode1['sigle'], $row_edit_marche["methode"]))) {echo "SELECTED";} } ?>><?php echo $libelle; ?></option>
                            <?php }  } ?>
            </select>
          </div>
        </div>      </td>
      <td>
        <div class="form-group">
          <label for="examen_banque" class="col-md-5 control-label">Revue<span class="required">*</span></label>
          <div class="col-md-6">
            <select name="examen_banque" id="examen_banque" class="form-control required"  >
              <option value="">-- Choisissez --</option>
	<option value="A PRIORI" <?php if(isset($_GET['id_mar'])) {if (!(strcmp($row_edit_marche['examen_banque'], "A PRIORI"))) {echo "SELECTED";} } ?>>A PRIORI</option>
    <option value="A POSTERIORI" <?php if(isset($_GET['id_mar'])) {if (!(strcmp($row_edit_marche['examen_banque'], "A POSTERIORI"))) {echo "SELECTED";} } ?>>A POSTERIORI</option>
            </select>
          </div>
        </div>      </td>
    </tr>

    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="examen_dncmp" class="col-md-5 control-label">Qualification <span class="required">*</span></label>
          <div class="col-md-6">
            <select name="examen_dncmp" id="examen_dncmp" class="form-control required"  >
              <option value="">-- Choisissez --</option>

			<option value="Forfait" <?php if(isset($_GET['id_mar'])) {if (!(strcmp($row_edit_marche['examen_dncmp'], "Forfait"))) {echo "SELECTED";} } ?>>Forfait</option>
		<option value="Quantite" <?php if(isset($_GET['id_mar'])) {if (!(strcmp($row_edit_marche['examen_dncmp'], "Quantite"))) {echo "SELECTED";} } ?>>Quantité</option>
				<option value="Temps passe" <?php if(isset($_GET['id_mar'])) {if (!(strcmp($row_edit_marche['examen_dncmp'], "Temps passe"))) {echo "SELECTED";} } ?>>Temps passé</option>       

            </select>
          </div>
        </div>
        </td>
      <td>
        <div class="form-group">
          <label for="date_prevue" class="col-md-5 control-label">Date demarrage pr&eacute;vue <span class="required">*</span></label>
          <div class="col-md-6">
            <input type="text" class="form-control datepicker required" name="date_prevue" value="<?php if(isset($_GET["id_mar"]) && !empty($_GET["id_mar"])) echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_edit_marche['date_prevue']))))); else echo date("d/m/Y"); ?>">
          </div>
        </div>
      </td>
    </tr>
	 <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="description" class="col-md-3 control-label">Description <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea name="description" cols="32" rows="1" class="form-control required" id="description"><?php if(isset($_GET["id_mar"]) ) echo $row_edit_marche['description']; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
	 <tr>
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="version" class="col-md-3 control-label">Version <span class="required">*</span></label>
          <div class="col-md-9">
            <input id="version" name="version" class="select2 form-control required" multiple data-placeholder="Taper une version" type="hidden" value="<?php if($totalRows_liste_version>0){ $datal = ""; $expl = (isset($row_edit_marche["periode"]) && !empty($row_edit_marche["periode"]))?explode(',',$row_edit_marche["periode"]):array(); foreach($row_liste_version as $row_liste_version){  if(isset($row_edit_marche["periode"]) && in_array($row_liste_version['id_version'],$expl)) { $datal .= $row_liste_version['numero_version'].",";  } }  $datal = substr($datal,0,-1); } echo $datal; ?>">
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
<input name="annee" id="annee" type="hidden" value="<?php echo $annee; ?>" size="32" alt="">
<input name="periode" id="periode" type="hidden" value="<?php echo intval($_GET["periode"]); ?>" size="32" alt="">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id_mar"]) ) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id_mar"]) ) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id_mar"]) ) echo ($_GET["id_mar"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id_mar"]) ) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce march&eacute; ?',<?php echo ($_GET["id_mar"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
</div>
</form>

</div> </div>