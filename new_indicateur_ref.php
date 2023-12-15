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
if(isset($_GET['secteur']) && $_GET['secteur']!="") {$_SESSION["secteur"]=$_GET['secteur']; $filiere=$_SESSION["secteur"];} else {$filiere=0; $_GET['secteur']=0;}
$where = ($filiere==0)?"":" and secteur = ".$filiere." ";
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
/*mysql_select_db($database_pdar_connexion, $pdar_connexion); //".$_SESSION["clp_where"]." and
$query_liste_composante = "SELECT * FROM activite_projet WHERE niveau=1 order by code";
$liste_composante  = mysql_query($query_liste_composante , $pdar_connexion) or die(mysql_error());
$row_liste_composante  = mysql_fetch_assoc($liste_composante);
$totalRows_liste_composante  = mysql_num_rows($liste_composante);
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_actpa = "SELECT code_ref_ind_ptba, count(id_ptba) as nindicateurp FROM ptba GROUP BY code_ref_ind_ptba";
$liste_actpa  = mysql_query($query_liste_actpa , $pdar_connexion) or die(mysql_error());
$row_liste_actpa = mysql_fetch_assoc($liste_actpa);
$totalRows_liste_actpa = mysql_num_rows($liste_actpa);
$indicateurp_array = array();
do{
  $indicateurp_array[$row_liste_actpa["code_ref_ind_ptba"]] = $row_liste_actpa["nindicateurp"];
}while($row_liste_actpa = mysql_fetch_assoc($liste_actpa));*/

$query_liste_unite = "SELECT unite FROM unite_indicateur";
try{
    $liste_unite = $pdar_connexion->prepare($query_liste_unite);
    $liste_unite->execute();
    $row_liste_unite = $liste_unite ->fetchAll();
    $totalRows_liste_unite = $liste_unite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_categorie = "SELECT * FROM categorie_beneficiaire";
$liste_categorie = mysql_query($query_liste_categorie, $pdar_connexion) or die(mysql_error());
$row_liste_categorie = mysql_fetch_assoc($liste_categorie);
$totalRows_liste_categorie = mysql_num_rows($liste_categorie); */
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		$("#form1").validate();
        $(".select2-select-00").select2({allowClear:true});
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});});
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification indicateur r&eacute;f&eacute;rentiel":"Nouvel indicateur r&eacute;f&eacute;rentiel"?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="code_ref_ind" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" type="text" name="code_ref_ind" id="code_ref_ind" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_indicateur['code_ref_ind']; ?>" size="10" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_indicateur['code_ref_ind']."'"; ?>) check_code('verif_code.php?t=referentiel_indicateur&','w=code_ref_ind='+this.value+' ','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div>
      </td>
    </tr>
 
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="intitule_ref_ind" class="col-md-3 control-label">Intitul&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="2" type="text" name="intitule_ref_ind" id="intitule_ref_ind"><?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_indicateur['intitule_ref_ind']; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td><label for="type_ref_ind" class="col-md-3 control-label">Type <span class="required">*</span></label>
        <div class="col-md-9">
          <select name="type_ref_ind" id="type_ref_ind" class="form-control required" >
            <option value="1" <?php if(isset($_GET["id"]) && intval($_GET["id"])>0) {if ("1"==$row_liste_indicateur['type_ref_ind']) {echo "SELECTED";} }?>>Produit</option>
            <option value="2" <?php if(isset($_GET["id"]) && intval($_GET["id"])>0) {if ("2"==$row_liste_indicateur['type_ref_ind']) {echo "SELECTED";} }?>>Effet</option>
            <option value="3" <?php if(isset($_GET["id"]) && intval($_GET["id"])>0) {if ("3"==$row_liste_indicateur['type_ref_ind']) {echo "SELECTED";} }?>>Impact</option>
          </select>
        </div>
      </td>
      <td><label for="unite" class="col-md-3 control-label">Unit&eacute; <span class="required">*</span></label>
        <div class="col-md-9">
          <select name="unite" id="unite" class="form-control required">
            <?php if($totalRows_liste_unite>0) { ?>
            <option value=""<?php if(isset($_GET['id'])){if (!(strcmp("", $row_liste_indicateur['unite']))) {echo "SELECTED";}} ?>>-- Choisissez --</option>
            <?php foreach($row_liste_unite as $row_liste_unite) { ?>
            <option value="<?php echo $row_liste_unite['unite']?>"<?php if(isset($_GET['id'])) {if (!(strcmp($row_liste_unite['unite'], $row_liste_indicateur['unite']))) {echo "SELECTED";} } ?>><?php echo $row_liste_unite['unite'];?></option>
            <?php } } else { echo '<optgroup label="Aucune unit&eacute; disponible"></optgroup>'; } ?>
          </select>
        </div>
      </td>
    </tr>
     <tr valign="top">
      <td><label for="mode_calcul" class="col-md-3 control-label">Calcul&eacute;? <span class="required">*</span></label>
        <div class="col-md-9">
          <select name="mode_calcul" id="mode_calcul" class="form-control required">
            <option value="Unique" <?php if(isset($_GET['id'])) {if ($row_liste_indicateur['mode_calcul']=="Unique") {echo "SELECTED";} } ?>>Unique</option>
            <option value="Somme" <?php if(isset($_GET['id'])) {if ($row_liste_indicateur['mode_calcul']=="Somme") {echo "SELECTED";} } ?>>Somme</option>
            <option value="Moyenne" <?php if(isset($_GET['id'])) {if ($row_liste_indicateur['mode_calcul']=="Moyenne") {echo "SELECTED";} } ?>>Moyenne</option>
            <option value="Ratio" <?php if(isset($_GET['id'])) {if ($row_liste_indicateur['mode_calcul']=="Ratio") {echo "SELECTED";} } ?>>Ratio</option>
          </select>
        </div>
      </td>
      <td><label for="fonction_agregat" class="col-md-3 control-label">Agr&eacute;gat<span class="required">*</span></label>
        <div class="col-md-9">
          <select name="fonction_agregat" id="fonction_agregat" class="form-control required">
            <option value="Somme" <?php if(isset($_GET['id'])) {if ($row_liste_indicateur['fonction_agregat']=="Somme") {echo "SELECTED";} } ?>>Somme</option>
            <option value="Moyenne" <?php if(isset($_GET['id'])) {if ($row_liste_indicateur['fonction_agregat']=="Moyenne") {echo "SELECTED";} } ?>>Moyenne</option>
            <option value="Report" <?php if(isset($_GET['id'])) {if ($row_liste_indicateur['fonction_agregat']=="Report") {echo "SELECTED";} } ?>>Report</option>
          </select>
        </div>
      </td>
    </tr>
     <tr valign="top">
      <td><label for="mode_suivi" class="col-md-3 control-label">Suivi par&nbsp;<span class="required">*</span></label>
        <div class="col-md-9">
          <select name="mode_suivi" class="form-control required">
           
            <option value="1"<?php if(isset($_GET['id'])) {if (!(strcmp("1", $row_liste_indicateur['mode_suivi']))) {echo "SELECTED";} } ?>>Fiches de collecte</option>
			 <option value="2"<?php if(isset($_GET['id'])) {if (!(strcmp("2", $row_liste_indicateur['mode_suivi']))) {echo "SELECTED";} } ?>>Rapports d'activités</option>
			<option value="3"<?php if(isset($_GET['id'])) {if (!(strcmp("3", $row_liste_indicateur['mode_suivi']))) {echo "SELECTED";} } ?>>Etudes et enquêtes</option>
			 <option value="4"<?php if(isset($_GET['id'])) {if (!(strcmp("4", $row_liste_indicateur['mode_suivi']))) {echo "SELECTED";} } ?>>Rapports d'atelier</option>
            <!--<option value="3"<?php if(isset($_GET['id'])){if (!(strcmp("3", $row_liste_indicateur['mode_suivi']))) {echo "SELECTED";}} ?>>Impact</option>-->
          </select>
        </div>
      </td>
       <td><label for="beneficiaire" class="col-md-3 control-label">Type B&eacute;n&eacute;ficiaire?<span class="required">*</span></label>
        <div class="col-md-9">&nbsp;&nbsp;&nbsp;&nbsp;Non&nbsp;
          <input name="beneficiaire" id="beneficiaire" type="radio" value="0" <?php if(isset($_GET['id']) && $row_liste_indicateur['beneficiaire']==0) echo 'checked="checked"'; else echo 'checked="checked"'; ?> size="18" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Oui&nbsp;
<input name="beneficiaire" type="radio"  value="1" <?php if(isset($_GET['id']) && $row_liste_indicateur['beneficiaire']==1) echo 'checked="checked"'; else echo 0; ?> size="18" /></div>
        </td>
    </tr>
      <!--<tr valign="baseline">
        <td colspan="2"><label for="categorie" class="col-md-3 control-label">Cat&eacute;gorie b&eacute;n&eacute;ficiaire&nbsp;</label>
        <div class="col-md-9"><select name="categorie" id="categorie" class="form-control">
          <?php /*if($totalRows_liste_categorie>0) { ?>
          <option value="0"<?php if(isset($_GET['id'])){if (!(strcmp("", $row_liste_indicateur['categorie']))) {echo "SELECTED";}} ?>>-- Choisissez --</option>
          <?php do {?>
          <option value="<?php echo $row_liste_categorie['id_categorie']?>"<?php if(isset($_GET['id'])) {if (!(strcmp($row_liste_categorie['id_categorie'], $row_liste_indicateur['categorie']))) {echo "SELECTED";} } ?>><?php echo $row_liste_categorie['categorie'];?></option>
          <?php } while ($row_liste_categorie = mysql_fetch_assoc($liste_categorie));}
									else {  echo '<optgroup label="Aucune cat&eacute;gorie disponible"></optgroup>'; }*/ ?>
        </select></div></td>
      </tr>-->
</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
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