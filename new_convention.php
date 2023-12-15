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
  $query_liste_part = "SELECT * FROM ".$database_connect_prefix."type_part WHERE id_part=".GetSQLValueString($id, "int");
  try{
        $liste_part = $pdar_connexion->prepare($query_liste_part);
        $liste_part->execute();
        $row_liste_part = $liste_part ->fetch();
        $totalRows_liste_part = $liste_part->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
}                                                         

//Partenaires
$query_liste_bailleur = "SELECT id_partenaire,sigle,code FROM ".$database_connect_prefix."partenaire ORDER BY sigle ASC";
try{
    $liste_bailleur = $pdar_connexion->prepare($query_liste_bailleur);
    $liste_bailleur->execute();
    $row_liste_bailleur = $liste_bailleur ->fetchAll();
    $totalRows_liste_bailleur = $liste_bailleur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

?>
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>
<script>
    $("#ui-datepicker-div").remove();
	$(document).ready(function() {
		// validate the comment form when it is submitted
		$("#form3").validate();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
	});
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) { ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification convention":"Nouvelle convention"?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">
<table border="0" id="" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="code_type" class="col-md-10 control-label">Code <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control required" type="text" name="code_type" id="code_type" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_part['code_type']; ?>" size="32" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_part['code_type']."'"; ?>) check_code('verif_code.php?t=type_part&','w=code_type='+this.value+' and projet=<?php echo $_SESSION["clp_projet"]; ?> ','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div>
      </td>
      <td valign="top">
        <div class="form-group">
          <label for="intitule" class="col-md-10 control-label">Intitul&eacute; <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control required" type="text" name="intitule" id="intitule" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_part['intitule']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="bailleur" class="col-md-10 control-label">Bailleur <span class="required">*</span></label>
          <div class="col-md-12">
            <select name="bailleur" id="bailleur" class="form-control required" >
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_bailleur>0) { foreach($row_liste_bailleur as $row_liste_bailleur){ ?>
              <option value="<?php echo $row_liste_bailleur['code']; ?>" <?php if (isset($row_liste_part['bailleur']) && $row_liste_bailleur['code']==$row_liste_part['bailleur']) {echo "SELECTED";} ?>><?php echo $row_liste_bailleur['code'].": ".$row_liste_bailleur['sigle']; ?></option>
              <?php } } ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="montant" class="col-md-10 control-label">Montant (FCFA) <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control required" type="text" name="montant" id="montant" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_part['montant']; ?>" size="32" />
          </div>
        </div>
      </td>
      <td valign="top">
        <div class="form-group">
          <label for="date_accord" class="col-md-10 control-label">Date de l'accord <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control inlinepicker required" type="text" name="date_accord" id="date_accord" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',$row_liste_part['date_accord'])));  else echo date("d/m/Y"); ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
<!--    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="observation" class="col-md-3 control-label">Observation </label>
          <div class="col-md-9">
            <input class="form-control" type="text" name="observation" id="observation" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"]))echo $row_liste_bailleur['observation']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>-->
</table>
<div class="form-actions">
    <?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION["clp_niveau"]) && $_SESSION["clp_niveau"]==0) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette convention ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>