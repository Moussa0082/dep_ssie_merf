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
 // $query_liste_indicateur = "SELECT * FROM ".$database_connect_prefix."indicateur_cadre_resultat WHERE code_indicateur_cr='$id'  and structure=%s and projet=%s and code_cr=%s and niveau=%s";
    $query_liste_indicateur = "SELECT * FROM ".$database_connect_prefix."indicateur_cadre_resultat WHERE id_indicateur_cr='$id' and projet='".$_SESSION["clp_projet"]."'";
        	try{
    $liste_indicateur = $pdar_connexion->prepare($query_liste_indicateur);
    $liste_indicateur->execute();
    $row_liste_indicateur = $liste_indicateur ->fetch();
    $totalRows_liste_indicateur = $liste_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}

$query_liste_activite_1 = "SELECT code,intitule FROM ".$database_connect_prefix."cadre_logique WHERE niveau=".($niveau+1)." and  projet='".$_SESSION["clp_projet"]."' order by code";
      	try{
    $liste_activite_1 = $pdar_connexion->prepare($query_liste_activite_1);
    $liste_activite_1->execute();
    $row_liste_activite_1 = $liste_activite_1 ->fetchAll();
    $totalRows_liste_activite_1 = $liste_activite_1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
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
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification d'indicateur de résultat":"Nouvel indicateur de résultat"?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="code_indicateur_cr" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="code_indicateur_cr" id="code_indicateur_cr" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_indicateur['code_indicateur_cr']; ?>" size="32" onblur="if(this.value!='') check_code('verif_code.php?t=indicateur_cadre_resultat&','w=code_indicateur_cr='+this.value+' and niveau=<?php echo $niveau; ?> and projet=<?php echo $_SESSION["clp_projet"]; ?> ','code_zone');" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="intitule_indicateur_cr" class="col-md-3 control-label">Intitul&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="2" type="text" name="intitule_indicateur_cr" id="intitule_indicateur_cr"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_indicateur['intitule_indicateur_cr']; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="code_cr" class="col-md-3 control-label">Niveau de R&eacute;sultat <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="code_cr" id="code_cr" class="form-control required" >
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_activite_1>0) {  foreach($row_liste_activite_1 as $row_liste_activite_1){ ?>
              <option value="<?php echo $row_liste_activite_1['code']; ?>" <?php if (isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite_1['code']==$row_liste_indicateur['code_cr']) {echo "SELECTED";} ?>><?php echo $row_liste_activite_1['code'].": ".$row_liste_activite_1['intitule']; ?></option>
              <?php } } ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="periodicite" class="col-md-3 control-label">P&eacute;riodicit&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="1" type="text" name="periodicite" id="periodicite"><?php echo isset($row_liste_indicateur['periodicite'])?$row_liste_indicateur['periodicite']:""; ?></textarea>
          </div>
        </div>      </td>
    </tr>
   <tr valign="top">
      <td>
        <div class="form-group">
          <label for="source" class="col-md-3 control-label">Source de donn&eacute;es  <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="1" type="text" name="source" id="source"><?php echo isset($row_liste_indicateur['source'])?$row_liste_indicateur['source']:""; ?></textarea>
          </div>
        </div>      </td>
    </tr>
   <tr valign="top">
      <td>
        <div class="form-group">
          <label for="responsable" class="col-md-3 control-label">Responsable <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="1" type="text" name="responsable" id="responsable"><?php echo isset($row_liste_indicateur['responsable'])?$row_liste_indicateur['responsable']:""; ?></textarea>
          </div>
        </div>      </td>
    </tr>
   
<tr valign="top">
      <td>
        <div class="form-group">
          <label for="description" class="col-md-3 control-label">Description <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="1" type="text" name="description" id="description"><?php echo isset($row_liste_indicateur['description'])?$row_liste_indicateur['description']:""; ?></textarea>
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
  <input type="hidden" name="niveau" value="<?php echo $_GET["niveau"]; ?>" />
</div>
</form>

</div> </div>
<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && ((isset($row_liste_indicateur["type_ref_ind"]) && $row_liste_indicateur["type_ref_ind"]==1) || (isset($row_liste_indicateur["type_ref_ind"]) && $row_liste_indicateur["type_ref_ind"]==4))){ ?>
<script type="text/javascript" >
get_content('menu_indic_ref.php','id=<?php echo $row_liste_indicateur["type_ref_ind"].'&id_s='.((isset($row_liste_indicateur["resultat"]))?$row_liste_indicateur["resultat"]:0); ?>','resultat','');
</script>
<?php } ?>

<?php } ?>