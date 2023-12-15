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

//$as=array();
if(isset($_GET["id"]))
{
    $id=($_GET["id"]);
    $query_edit_version = "SELECT * FROM ".$database_connect_prefix."version_plan_marche WHERE id_version='$id'";
    try{
        $edit_version = $pdar_connexion->prepare($query_edit_version);
        $edit_version->execute();
        $row_edit_version = $edit_version ->fetch();
        $totalRows_edit_version = $edit_version->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
}

?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>
<script type="text/javascript">
	$().ready(function() {
		$(".row-border").validate();
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
	});
</script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]))?"Modification":"Nouvel ajout"?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2sv" id="form2sv" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="numero_version" class="col-md-3 control-label">Num&eacute;ro de version  <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" name="numero_version" id="numero_version" type="text" value="<?php if(isset($_GET['id'])) echo $row_edit_version['numero_version'];  ?>" size="30" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_edit_version['numero_version']."'"; ?>) check_code('verif_code.php?t=version_plan_marche&','w=numero_version='+this.value+' ','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">  
      <td>
        <div class="form-group">
          <label for="date_version" class="col-md-3 control-label">Date <span class="required">*</span></label>
          <div class="col-md-3">
           <input  class="form-control datepicker required" type="text" name="date_version" id="date_version" value="<?php if(isset($row_edit_version['date_version'])) echo implode('/',array_reverse(explode('-',$row_edit_version['date_version']))); ?>" />
          </div>
        </div>      </td>
    </tr>
   
    
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette étape; ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form2sv" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>