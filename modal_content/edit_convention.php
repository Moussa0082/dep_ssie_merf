<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
$path = '../';
include_once $path . 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"])) {
//header(sprintf("Location: %s", "./"));
  exit;
}
include_once $path . $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');
if (isset ($_GET['id'])) {
  $id = $_GET['id'];
}
if (isset ($_GET["id"])) {
  $id = $_GET["id"];
  $query_edit_cv = "SELECT * FROM ".$database_connect_prefix."convention WHERE code_convention='$id'";
          try{
    $edit_cv = $pdar_connexion->prepare($query_edit_cv);
    $edit_cv->execute();
    $row_edit_cv = $edit_cv ->fetch();
    $totalRows_edit_cv = $edit_cv->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

}

$query_liste_operateur = "SELECT id_acteur, nom_acteur FROM ".$database_connect_prefix."acteur ORDER BY nom_acteur";
        try{
    $liste_operateur = $pdar_connexion->prepare($query_liste_operateur);
    $liste_operateur->execute();
    $row_liste_operateur = $liste_operateur ->fetchAll();
    $totalRows_liste_operateur = $liste_operateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_region= "SELECT * FROM ".$database_connect_prefix."region order by nom_region";
        try{
    $liste_region = $pdar_connexion->prepare($query_liste_region);
    $liste_region->execute();
    $row_liste_region = $liste_region ->fetchAll();
    $totalRows_liste_region = $liste_region->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableauRegion=array();
if($totalRows_liste_region>0){  foreach($row_liste_region as $row_liste_region){
$tableauRegion[]=$row_liste_region['id_region']."<>".$row_liste_region['abrege_region'];
}}
?>

<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
		  $(".modal-dialog", window.parent.document).width(700);
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
	});
</script>

<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset ($_GET["id"]) ) ? "Modification" : "Nouvelle";echo " convention";?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="code_convention" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-3">
            <input type="text" class="form-control required" name="code_convention" value="<?php if (isset ($_GET["id"])) echo $row_edit_cv['code_convention'];?>" >
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="reference" class="col-md-3 control-label">N&ordm; R&eacute;f <span class="required">*</span></label>
          <div class="col-md-9">
            <input type="text" class="form-control required" name="reference" value="<?php if (isset ($_GET["id"]) ) echo $row_edit_cv['reference'];?>" >
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="date_signature" class="col-md-3 control-label">Date de signature <span class="required">*</span></label>
          <div class="col-md-9">
            <input type="text" class="form-control datepicker required" name="date_signature" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',$row_edit_cv['date_signature']))); else echo date("d/m/Y"); ?>" >
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="intitule" class="col-md-3 control-label">Intitule / d&eacute;signation <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" name="intitule" cols="25" rows="2"><?php if (isset ($_GET["id"]) ) echo $row_edit_cv['intitule'];?></textarea>
          </div>
        </div>      </td>
    </tr>
	<tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="partenaire" class="col-md-3 control-label">Partenaire <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="partenaire" id="categorie_depense" class="form-control required"  >
              <option value="">Selectionnez</option>
                            <?php foreach($row_liste_operateur as $row_liste_operateur){ ?>
                            <option value="<?php echo $row_liste_operateur['id_acteur'];?>"<?php if(isset($_GET['id'])) {if (!(strcmp($row_liste_operateur['id_acteur'], $row_edit_cv['partenaire']))) {echo "SELECTED";} } ?>><?php echo $row_liste_operateur['nom_acteur']?></option>
                              <?php  }  ?>
            </select>
          </div>
        </div>      </td>
    </tr>
   
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="montant" class="col-md-3 control-label">Montant (FCFA) </label>
          <div class="col-md-9">
            <input type="text" class="form-control required" name="montant" value="<?php if (isset ($_GET["id"]) ) echo $row_edit_cv['montant']; ?>" >
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td colspan="2"> <div class="form-group"><label for="debut" class="col-md-3 control-label">D&eacute;but <span class="required">*</span></label>
          <div class="col-md-3">
            <input type="text" class="form-control datepicker required" name="debut" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',$row_edit_cv['debut']))); else echo date("d/m/Y"); ?>" >
          </div>
		   <label for="fin" class="col-md-2 control-label">Fin <span class="required">*</span></label>
          <div class="col-md-4">
            <input type="text" class="form-control datepicker required" name="fin" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',$row_edit_cv['fin']))); else echo date("d/m/Y"); ?>" >
          </div>
        </div>  </td>
    </tr>  
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="champs_app" class="col-md-3 control-label">Champs d'application <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea name="champs_app" rows="2" class="form-control required"><?php if (isset ($_GET["id"]) ) echo $row_edit_cv['champs_app']; ?></textarea>
          </div>
        </div>      </td>
    </tr>
   
 <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="observation" class="col-md-3 control-label">Observations </label>
          <div class="col-md-9">
            <textarea name="observation" cols="32" rows="1" class="form-control " id="observation"><?php if (isset ($_GET["id"]) ) echo $row_edit_cv['observation'];?></textarea>
          </div>
        </div>      </td>
    </tr>
    <tr><td><br /></td></tr>
</table>
<div class="form-actions">
<input name="id" id="id" type="hidden" value="<?php if(isset($_GET["id"])) echo ($_GET["id"]);?>" size="32" alt="">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "Modifier";else echo "Enregistrer";?>" />
  <input name="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "MM_update";else echo "MM_insert";?>" type="hidden" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert";?>" size="32" alt="">
<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) {?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce convention ?','<?php echo ($_GET["id"]);?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php }?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>