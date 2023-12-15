<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & D�veloppement: BAMASOFT */
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

if(isset($_GET['niveau']) && intval($_GET['niveau'])>0) $niveau = intval($_GET['niveau']); else $niveau = 0;

if(isset($_GET["id"]))
{
  $id=($_GET["id"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
 // $query_liste_indicateur = "SELECT * FROM ".$database_connect_prefix."indicateur_cadre_resultat WHERE code_indicateur_cosop='$id'  and structure=%s and projet=%s and code_cr=%s and niveau=%s";
    $query_liste_indicateur = "SELECT * FROM ".$database_connect_prefix."indicateur_cosop WHERE code_indicateur_cosop='$id'";
  $liste_indicateur  = mysql_query($query_liste_indicateur , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_indicateur  = mysql_fetch_assoc($liste_indicateur);
  $totalRows_liste_indicateur  = mysql_num_rows($liste_indicateur);
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_activite_1 = "SELECT code,intitule FROM ".$database_connect_prefix."cadre_cosop WHERE niveau=".($niveau+1)."  ";
$liste_activite_1 = mysql_query($query_liste_activite_1 , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_activite_1 = mysql_fetch_assoc($liste_activite_1);
$totalRows_liste_activite_1 = mysql_num_rows($liste_activite_1);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_referentiel = (isset($id))?"SELECT * FROM referentiel_indicateur where id_ref_ind not in (select referentiel from indicateur_sygri1_projet where id_indicateur_sygri_niveau1_projet!=$id) and type_ref_ind=1":"SELECT * FROM referentiel_indicateur where type_ref_ind=1";
$liste_referentiel = mysql_query($query_liste_referentiel, $pdar_connexion) or die(mysql_error());
$row_liste_referentiel = mysql_fetch_assoc($liste_referentiel);
$totalRows_liste_referentiel = mysql_num_rows($liste_referentiel);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "SELECT * FROM ".$database_connect_prefix."cadre_config_cosop LIMIT 1";
  $entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);
  $libelle1 = array();
  if($totalRows_entete>0){ $libelle1=explode(",",$row_entete["libelle"]);}
?>

<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
        $(".modal-dialog", window.parent.document).width(700);
        $(".select2-select-00").select2({allowClear:true});
	});
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification d'indicateur de r�sultat":"Nouvel indicateur de r�sultat"?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="code_indicateur_cosop" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="code_indicateur_cosop" id="code_indicateur_cosop" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_indicateur['code_indicateur_cosop']; ?>" size="32" onblur="if(this.value!='') check_code('verif_code.php?t=indicateur_cosop&','w=code_indicateur_cosop='+this.value+' and niveau=<?php echo $niveau; ?> ','code_zone');" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="intitule_indicateur_cosop" class="col-md-3 control-label">Intitul&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="2" type="text" name="intitule_indicateur_cosop" id="intitule_indicateur_cosop"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_indicateur['intitule_indicateur_cosop']; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="os_cosop" class="col-md-3 control-label"><?php echo (isset($libelle1[count($libelle1)-1]))?$libelle1[count($libelle1)-1]:"ND"; ?> <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="os_cosop" id="os_cosop" class="form-control required" >
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_activite_1>0) { do { ?>
              <option value="<?php echo $row_liste_activite_1['code']; ?>" <?php if (isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite_1['code']==$row_liste_indicateur['os_cosop']) {echo "SELECTED";} ?>><?php echo $row_liste_activite_1['code'].": ".$row_liste_activite_1['intitule']; ?></option>
              <?php }while($row_liste_activite_1 = mysql_fetch_assoc($liste_activite_1)); } ?>
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
              <option></option>
         <option value="0" <?php if (isset($row_liste_indicateur["referentiel"]) && $row_liste_indicateur['referentiel']=="0") {echo "SELECTED";} ?>>Non-d&eacute;finie</option>
              <?php if($totalRows_liste_referentiel>0){ do { ?>
              <option value="<?php echo $row_liste_referentiel['id_ref_ind']; ?>" <?php if (isset($row_liste_indicateur["referentiel"]) && $row_liste_referentiel['id_ref_ind']==$row_liste_indicateur["referentiel"]) {echo "SELECTED";} ?>><?php echo $row_liste_referentiel['code_ref_ind'].": ".$row_liste_referentiel['intitule_ref_ind']; ?></option>
                <?php  } while ($row_liste_referentiel = mysql_fetch_assoc($liste_referentiel)); } ?>
            </select>
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="valeur_cible" class="col-md-3 control-label">Valeur cible </label>
          <div class="col-md-9">
            <input class="form-control " type="text" name="valeur_cible" id="valeur_cible" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_indicateur['valeur_cible']; ?>" size="32" />
            </div>
        </div>
      </td>
    </tr>
   
<tr valign="top">
      <td>
        <div class="form-group">
          <label for="description" class="col-md-3 control-label">Description </label>
          <div class="col-md-9">
            <textarea class="form-control " cols="200" rows="1" type="text" name="description" id="description"><?php echo isset($row_liste_indicateur['description'])?$row_liste_indicateur['description']:""; ?></textarea>
          </div>
        </div>      </td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET["id"]) && !empty($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) ) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"])) {?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer l\'indicateur ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php }?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>

<?php } ?>