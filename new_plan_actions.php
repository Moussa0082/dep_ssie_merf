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

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_plan = "SELECT * FROM plan_actions WHERE id_plan_actions=".GetSQLValueString($id, "int");
  $plan  = mysql_query($query_plan , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_plan  = mysql_fetch_assoc($plan);
  $totalRows_plan  = mysql_num_rows($plan);
}


?>
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>
<script>
    $("#ui-datepicker-div").remove();
	$(document).ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
	});
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) { ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification plan d'action":"Nouveau plan d'action"?></h4> </div>
<div class="widget-content">
<?php if(!isset($_GET['valid'])) { ?>
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr>
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="intitule" class="col-md-10 control-label">Intitul&eacute; <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control" name="intitule" id="intitule" cols="25"><?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_plan['tache']:''; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="responsable" class="col-md-10 control-label">Responsables <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control" name="responsable" id="responsable" cols="25"><?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_plan['responsable']:''; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td valign="top" width="50%">
        <div class="form-group">
          <label for="statut" class="col-md-10 control-label">Statut <span class="required">*</span></label>
          <div class="col-md-11">
            <select name="statut" id="statut" class="form-control required" >
                <option value="">Selectionnez</option>
              <option value="1" <?php if(isset($_GET["id"]) && !empty($_GET["id"])) if($row_plan['statut']==1) {echo "SELECTED";}  ?>>En cours</option>
               <option value="2" <?php if(isset($_GET["id"]) && !empty($_GET["id"])) if($row_plan['statut']==2) {echo "SELECTED";}  ?>>En rétard</option>
              <option value="3" <?php if(isset($_GET["id"]) && !empty($_GET["id"])) if($row_plan['statut']==3) {echo "SELECTED";}  ?>>Terminé</option>
            </select>
          </div>
        </div>
      </td>
      <td valign="top">
        <div class="form-group">
          <label for="date_execution" class="col-md-10 control-label">Date d'exécution <span class="required">*</span></label>
          <div class="col-md-11">
            <input class="form-control inlinepicker required" type="text" name="date_execution" id="date_execution" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',$row_plan['date_execution'])));  else echo date("d/m/Y"); ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
    <?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION["clp_niveau"]) && $_SESSION["clp_niveau"]==1) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce plan ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>
<?php }else{ //Validation du plan ?>
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr>
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="observation" class="col-md-10 control-label"><?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_plan['tache']:''; ?></label>
        </div>
      </td>
    </tr>
    <tr>
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="observation" class="col-md-10 control-label">Observation <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control" name="observation" id="observation" cols="25"><?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_plan['observation']:''; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td valign="top" width="50%">
        <div class="form-group">
          <label for="statut" class="col-md-10 control-label">Statut <span class="required">*</span></label>
          <div class="col-md-11">
            <select name="statut" id="statut" class="form-control required" >
                <option value="">Selectionnez</option>
<!--              <option value="1" <?php if(isset($_GET["id"]) && !empty($_GET["id"])) if($row_plan['statut']==1) {echo "SELECTED";}  ?>>En cours</option>  -->
               <option value="2">En rétard</option>
              <option value="3">Terminé</option>
            </select>
          </div>
        </div>
      </td>
      <td valign="top">
        <div class="form-group">
          <label for="date_fin" class="col-md-10 control-label">Date de fin <span class="required">*</span></label>
          <div class="col-md-11">
            <input class="form-control inlinepicker required" type="text" name="date_fin" id="date_fin" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',$row_plan['date_fin'])));  else echo date("d/m/Y"); ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
    <?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
  <input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>
<?php } ?>

</div> </div>
<?php } ?>