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

if(isset($_GET['niveau']) && intval($_GET['niveau'])>0) $niveau = intval($_GET['niveau']); else $niveau = 0;

if(isset($_GET["id"]))
{
  $id=($_GET["id"]);
 // $query_liste_indicateur = "SELECT * FROM ".$database_connect_prefix."indicateur_cadre_resultat WHERE code_indicateur_cosop='$id'  and structure=%s and projet=%s and code_cr=%s and niveau=%s";
    $query_liste_indicateur = "SELECT * FROM ".$database_connect_prefix."indicateur_cosop WHERE code_indicateur_cosop='$id'";
          try{
    $liste_indicateur = $pdar_connexion->prepare($query_liste_indicateur);
    $liste_indicateur->execute();
    $row_liste_indicateur = $liste_indicateur ->fetch();
    $totalRows_liste_indicateur = $liste_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}

$query_liste_activite_1 = "SELECT code,intitule FROM ".$database_connect_prefix."cadre_cosop WHERE niveau=".($niveau+1)."  ";
          try{
    $liste_activite_1 = $pdar_connexion->prepare($query_liste_activite_1);
    $liste_activite_1->execute();
    $row_liste_activite_1 = $liste_activite_1 ->fetchAll();
    $totalRows_liste_activite_1 = $liste_activite_1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


$query_liste_referentiel = "SELECT * FROM referentiel_indicateur";
          try{
    $liste_referentiel = $pdar_connexion->prepare($query_liste_referentiel);
    $liste_referentiel->execute();
    $row_liste_referentiel = $liste_referentiel ->fetchAll();
    $totalRows_liste_referentiel = $liste_referentiel->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $query_entete = "SELECT * FROM ".$database_connect_prefix."cadre_config_cosop LIMIT 1";
            try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

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
<div class="widget-header"> <h4><i class="icon-reorder"></i><?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification d'indicateur de COSOP":"Nouvel indicateur de COSOP"?></h4> 
</div> 
<div class="widget-content">
<form action="indicateur_cosop.php" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="code_indicateur_cosop" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="code_indicateur_cosop" id="code_indicateur_cosop" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_indicateur['code_indicateur_cosop']; ?>" size="32" onblur="if(this.value!='') check_code('verif_code.php?t=indicateur_cosop&','w=code_indicateur_cosop='+this.value+' and niveau=<?php echo $niveau; ?> ','code_zone');" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="intitule_indicateur_cosop" class="col-md-3 control-label">Intitul&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="2" type="text" name="intitule_indicateur_cosop" id="intitule_indicateur_cosop"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_indicateur['intitule_indicateur_cosop']; ?></textarea>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="os_cosop" class="col-md-3 control-label"><?php echo (isset($libelle1[$niveau]) && !empty($libelle1[$niveau]))?$libelle1[$niveau]:"Objectif"; ?><span class="required">*</span></label>
          <div class="col-md-9">
            <select name="os_cosop" id="os_cosop" class="form-control required" >
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_activite_1>0) { foreach($row_liste_activite_1 as $row_liste_activite_1){  ?>
              <option value="<?php echo $row_liste_activite_1['code']; ?>" <?php if (isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite_1['code']==$row_liste_indicateur['os_cosop']) {echo "SELECTED";} ?>><?php echo $row_liste_activite_1['code'].": ".$row_liste_activite_1['intitule']; ?></option>
              <?php } } ?>
            </select>
          </div>
        </div>      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="referentiel" class="col-md-3 control-label">R&eacute;f&eacute;rentiel <span class="required">*</span></label>
          <div class="col-md-9">
             <select name="referentiel" id="referentiel" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez un indicateur">
              <option></option>
         <option value="0" <?php if (isset($row_liste_indicateur["referentiel"]) && $row_liste_indicateur['referentiel']=="0") {echo "SELECTED";} ?>>Non-d&eacute;finie</option>
              <?php if($totalRows_liste_referentiel>0){ foreach($row_liste_referentiel as $row_liste_referentiel){  ?>
              <option value="<?php echo $row_liste_referentiel['id_ref_ind']; ?>" <?php if (isset($row_liste_indicateur["referentiel"]) && $row_liste_referentiel['id_ref_ind']==$row_liste_indicateur["referentiel"]) {echo "SELECTED";} ?>><?php echo $row_liste_referentiel['code_ref_ind'].": ".$row_liste_referentiel['intitule_ref_ind']; ?></option>
                <?php  } } ?>
            </select>
          </div>
        </div>      </td>
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