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

$sygri=(isset($_GET["sygri"]))?intval($_GET["sygri"]):0;

if(isset($_GET["id"])) { $id=$_GET["id"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_data = "SELECT * FROM soutien_indicateur_sygri2 where id_indicateur_soutien=$id";
$edit_data = mysql_query($query_edit_data, $pdar_connexion) or die(mysql_error());
$row_edit_data = mysql_fetch_assoc($edit_data);
$totalRows_edit_data = mysql_num_rows($edit_data);
if(isset($row_edit_data['indicateur_sygri_niveau2'])) $ais = explode(",", $row_edit_data['indicateur_sygri_niveau2']); else $ais=array();
}else $id=0;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_referentiel = "SELECT * FROM referentiel_indicateur where id_ref_ind not in (select referentiel from indicateur_sygri2_projet where id_indicateur_sygri_niveau2_projet!=$id) and (type_ref_ind=1 or type_ref_ind=2)";
$liste_referentiel = mysql_query($query_liste_referentiel, $pdar_connexion) or die(mysql_error());
$row_liste_referentiel = mysql_fetch_assoc($liste_referentiel);
$totalRows_liste_referentiel = mysql_num_rows($liste_referentiel);

$sommes=0;
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_data_sommes = "SELECT SUM(proportion) as sommes FROM soutien_indicateur_sygri2 where indicateur_sygri_niveau2=$sygri";
$edit_data_sommes = mysql_query($query_edit_data_sommes, $pdar_connexion) or die(mysql_error());
$row_edit_data_sommes = mysql_fetch_assoc($edit_data_sommes);
$totalRows_edit_data_sommes = mysql_num_rows($edit_data_sommes);
if(isset($row_edit_data_sommes["sommes"])) $sommes = $row_edit_data_sommes["sommes"];

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind, mode_calcul FROM referentiel_indicateur";
$liste_ind_ref  = mysql_query($query_liste_ind_ref , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref);
$totalRows_liste_ind_ref  = mysql_num_rows($liste_ind_ref);
$unite_ind_ref_array = array();
do{
 $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];
}while($row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref));


?>
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
	});
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification Indicateurs de soutien":"Nouveau Indicateurs de soutien"?></h4> </div>
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
          <label for="intitule_indicateur_soutien" class="col-md-3 control-label">Indicateur <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="intitule_indicateur_soutien" id="intitule_indicateur_soutien" value="<?php echo (isset($row_edit_data['intitule_indicateur_soutien']))?$row_edit_data['intitule_indicateur_soutien']:""; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="proportion" class="col-md-3 control-label">Proportion (%) <span class="required">*</span></label>
          <div class="col-md-4">
           <input class="form-control required" type="text" name="proportion" id="proportion" value="<?php echo (isset($row_edit_data['proportion']))?$row_edit_data['proportion']:""; ?>" size="32" /><i>Reste (<?php echo 100-$sommes; ?>)</i>
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="referentiel" class="col-md-3 control-label">R&eacute;f&eacute;rentiel <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="referentiel" id="referentiel" class="form-control required" >
              <option value="">Selectionnez</option>
              <?php do { ?>
              <option value="<?php echo $row_liste_referentiel['id_ref_ind']; ?>" <?php if (isset($row_edit_data['referentiel']) && $row_liste_referentiel['id_ref_ind']==$row_edit_data['referentiel']) {echo "SELECTED";} ?>><?php echo $row_liste_referentiel['code_ref_ind'].": ".$row_liste_referentiel['intitule_ref_ind']; ?></option>
              <?php }while($row_liste_referentiel  = mysql_fetch_assoc($liste_referentiel)); ?>
            </select>
          </div>
        </div>
      </td>
    </tr>

   <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="cible" class="col-md-3 control-label">Valeur cible <span class="required">*</span></label>
          <div class="col-md-4">
           <input class="form-control required" type="text" name="cible" id="cible" value="<?php echo (isset($row_edit_data['cible']))?$row_edit_data['cible']:""; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>


</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
  <?php } ?>
<?php if(isset($_GET["sygri"])){ ?>
  <input type="hidden" name="sygri" value="<?php echo $_GET["sygri"]; ?>" />
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