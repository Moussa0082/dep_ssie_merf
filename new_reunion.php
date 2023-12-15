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

$dir = './attachment/reunion_rencontre/';
if(isset($_GET['annee'])) $annee=intval($_GET['annee']); else $annee = date("Y");

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_activite = "SELECT * FROM ".$database_connect_prefix."reunions WHERE id_reunion='$id'";
  $liste_activite  = mysql_query($query_liste_activite , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_activite  = mysql_fetch_assoc($liste_activite);
  $totalRows_liste_activite  = mysql_num_rows($liste_activite);
}
?>
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap-typeahead.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
$("input.typeahead").typeahead({
    onSelect: function(item) {
        //console.log(item);
    },
    ajax: {
        url: "./ajax_code_activite_ptba.php?path=./",
        timeout: 300,
        displayField: "title",
        valueField: "id",
        triggerLength: 1,
        method: "GET",
        //loadingClass: "loading-circle",
        preDispatch: function (query) {
            //showLoadingMask(true);
            return {
                search: query
            }
        },
        preProcess: function (data) {
            //showLoadingMask(false);
            if (data.success === false) {
                // Hide the list, there was some error
                return false;
            }
            // We good!
            return data;
        }
    }
});
	});
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<4) { ?>
<?php if(!isset($_GET['rapport'])) { ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php if(isset($_GET['id'])) echo "Modification de r&eacute;union"; else echo "Nouvelle r&eacute;union"; ?></h4></div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
  <table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:10px;">
    <tr valign="top">
      <td valign="top" colspan="2"><div class="form-group">
          <label for="objectif" class="col-md-12 control-label">Objectif <span class="required">*</span></label>
          <div class="col-md-12">
<textarea class="form-control required" id="objectif" name="objectif" cols="25" rows="2"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['objectif'];?></textarea>
          </div>
      </div>
      </td>
    </tr>
    <tr>
      <td valign="top"><div class="form-group">
          <label for="type_reunion" class="col-md-12 control-label">Type de r&eacute;union <span class="required">*</span></label>
          <div class="col-md-12">
            <select name="type_reunion" id="type_reunion" class="form-control required">
              <option value="">Selectionnez</option>
              <option value="Zone projet" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite["type_reunion"]=="Zone projet") {echo "SELECTED";} ?>>Zone projet</option>
              <option value="Hors zone projet" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite["type_reunion"]=="Hors zone projet") {echo "SELECTED";} ?>>Hors zone projet</option>
              <option value="A l'&eacute;tranger" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite["type_reunion"]=="A l'&eacute;tranger") {echo "SELECTED";} ?>>A l'&eacute;tranger</option>
            </select>
          </div>
      </div></td>
      <td valign="top"><div class="form-group">
          <label for="lieu" class="col-md-12 control-label">Lieu <span class="required">*</span></label>
          <div class="col-md-12">
            <input type="text" class="form-control required" name="lieu" id="lieu" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['lieu'];?>" />
          </div>
      </div>
      </td>
    </tr>
    <tr valign="top">
      <td valign="top"><div class="form-group">
          <label for="participants" class="col-md-12 control-label">Participants </label>
          <div class="col-md-12">
            <textarea class="form-control" id="participants" name="participants" rows="1" cols="25"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['participants']; ?></textarea>
          </div>
      </div>
      </td>
      <td valign="top"><div class="form-group">
          <label for="debut" class="col-md-12 control-label">Date  <span class="required">*</span></label>
          <div class="col-md-12">
             <input type="text" class="form-control datepicker required" name="debut" id="debut" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_liste_activite['debut']))))); else echo date("d/m/Y"); ?>">
          </div>
      </div>
      </td>
    </tr>

    <tr valign="top">
      <td colspan="2"><div class="form-group">
          <label for="recommandation" class="col-md-12 control-label">Recommandations </label>
          <div class="col-md-12">
            <textarea class="form-control" id="recommandation" name="recommandation" rows="3" cols="25"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['recommandation']; ?></textarea>
          </div>
      </div></td>
    </tr>
  </table>
<div class="form-actions">
<input name="annee" id="annee" type="hidden" value="<?php if(isset($_GET["annee"])) echo $_GET["annee"];?>" size="32" alt="">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "Modifier";else echo "Enregistrer";?>" />
  <input name="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "MM_update";else echo "MM_insert";?>" type="hidden" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]);else echo "MM_insert";?>" size="32" alt="">
<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) {?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette r&eacute;union ?','<?php echo ($_GET["id"]);?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php }?>

  <input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
</div>
</form>

</div> </div>
<?php } else { //Rapport ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php if(isset($_GET['id'])) echo "Ajout de document"; ?></h4></div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
  <table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:10px;">
    <tr>
      <td colspan="2" valign="middle"><div class="form-group">
          <label for="rapport" class="col-md-4 control-label">Document <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && !empty($row_liste_activite['rapport'])) echo ""; else echo '<span class="required">*</span>'; ?></label>
          <div class="col-md-8">
            <input class="form-control <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && !empty($row_liste_activite['rapport'])) echo ""; else echo "required"; ?>" type="file" name="rapport" id="rapport" value="" size="32" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/pdf,application/vnd.ms-word,image/jpeg,.doc,.docx,.zip,.rar"  />
          </div>
      </div></td>
    </tr>
    <tr valign="top">
      <td colspan="2"><div class="form-group">
          <label for="recommandation" class="col-md-12 control-label">Recommandations </label>
          <div class="col-md-12">
            <textarea class="form-control" id="recommandation" name="recommandation" rows="3" cols="25"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['recommandation']; ?></textarea>
          </div>
      </div></td>
    </tr>
  </table>
<div class="form-actions">
<input name="annee" id="annee" type="hidden" value="<?php if(isset($_GET["annee"])) echo $_GET["annee"];?>" size="32" alt="">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "Modifier";else echo "Enregistrer";?>" />
  <input name="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "MM_update";else echo "MM_insert";?>" type="hidden" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]);else echo "MM_insert";?>" size="32" alt="">

  <input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">
</div>
</form>

</div> </div>
<?php } } ?>