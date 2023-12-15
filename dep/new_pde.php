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
header('Content-Type: text/html; charset=ISO-8859-15');

if(isset($_GET["id"]))
{
  $id=$_GET["id"];
  $query_liste_ugl = "SELECT * FROM ".$database_connect_prefix."pde WHERE code_pde='$id'";
        try{
    $liste_ugl = $pdar_connexion->prepare($query_liste_ugl);
    $liste_ugl->execute();
    $row_liste_ugl = $liste_ugl ->fetch();
    $totalRows_liste_ugl = $liste_ugl->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}

  $query_liste_departement = "SELECT * FROM ".$database_connect_prefix."ugl order by code_ugl ";
      try{
    $liste_departement = $pdar_connexion->prepare($query_liste_departement);
    $liste_departement->execute();
    $row_liste_departement = $liste_departement ->fetchAll();
    $totalRows_liste_departement = $liste_departement->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  
  $query_liste_do = "SELECT * FROM ".$database_connect_prefix."departement order by nom_departement ";
    try{
    $liste_do = $pdar_connexion->prepare($query_liste_do);
    $liste_do->execute();
    $row_liste_do = $liste_do ->fetchAll();
    $totalRows_liste_do = $liste_do->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  
  $query_liste_filiere = "SELECT * FROM ".$database_connect_prefix."filiere_agricole order by nom_filiere "; 
  try{
    $liste_filiere = $pdar_connexion->prepare($query_liste_filiere);
    $liste_filiere->execute();
    $row_liste_filiere = $liste_filiere ->fetchAll();
    $totalRows_liste_filiere = $liste_filiere->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


 /* mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_region= "SELECT * FROM ".$database_connect_prefix."region order by nom_region";
  $liste_region = mysql_query($query_liste_region, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $tableauRegion=$tableauRegionV=array();
  while($ligne=mysql_fetch_assoc($liste_region)){$tableauRegion[]=$ligne['code_region']."<>".$ligne['abrege_region']; $tableauRegionV[$ligne['code_region']]=$ligne['nom_region'];}*/
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
	    $(".select2-select-00").select2({allowClear:true});
        $(".colorpicker").remove();
        $(".bs-colorpicker").colorpicker();
        $(".colorpicker").attr("style","z-index:10060");
	});
</script>

<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
	});
</script>

<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]))?"Modification PDA":"Nouveau PDA"?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="code_pde" class="col-md-9 control-label">Code <span class="required">*</span></label>
          <div class="col-md-11">
            <input class="form-control required" type="text" name="code_pde" id="code_pde" value="<?php echo isset($row_liste_ugl['code_pde'])?$row_liste_ugl['code_pde']:""; ?>" size="32" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_ugl['code_pde']."'"; ?>) check_code('verif_code.php?t=pde&','w=code_pde='+this.value+'','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>          </div>
        </div>      </td>
      <td valign="top">
        <div class="form-group">
          <label for="nom_pde" class="col-md-9 control-label">Nom du PDA  <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control required" type="text" name="nom_pde" id="nom_pde" value="<?php echo isset($row_liste_ugl['nom_pde'])?$row_liste_ugl['nom_pde']:""; ?>" size="32" />
          </div>
        </div>      </td>
    </tr>
    <tr>
      <td colspan="2" valign="top">&nbsp;<div class="form-group">
          <label for="zone_pde" class="col-md-2 control-label">Communes<span class="required">*</span></label>
          <div class="col-md-10">
            <select name="zone_pde[]" id="zone_pde" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez une commune" multiple>
              <option></option>
              <?php if($totalRows_liste_do>0){ $expl = (isset($row_liste_ugl["zone_pde"]) && !empty($row_liste_ugl["zone_pde"]))?explode(',',$row_liste_ugl["zone_pde"]):array(); foreach($row_liste_do as $row_liste_do){ ?>
              <option value="<?php echo $row_liste_do['code_departement']; ?>" <?php if(in_array($row_liste_do['code_departement'],$expl)) {echo "SELECTED";} ?>><?php echo $row_liste_do['nom_departement']; ?></option>
                <?php  } } ?>
            </select>
          </div>
      </div></td>
      </tr>
	  
	      <tr>
      <td colspan="2" valign="top">&nbsp;<div class="form-group">
          <label for="filieres" class="col-md-2 control-label">Filières concernées<span class="required">*</span></label>
          <div class="col-md-10">
            <select name="filieres[]" id="filieres" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez une commune" multiple>
              <option></option>
              <?php if($totalRows_liste_filiere>0){ $expl = (isset($row_liste_ugl["filieres"]) && !empty($row_liste_ugl["filieres"]))?explode(',',$row_liste_ugl["filieres"]):array(); foreach($row_liste_filiere as $row_liste_filiere){  ?>
              <option value="<?php echo $row_liste_filiere['code_filiere']; ?>" <?php if(in_array($row_liste_filiere['code_filiere'],$expl)) {echo "SELECTED";} ?>><?php echo $row_liste_filiere['nom_filiere']; ?></option>
                <?php  } } ?>
            </select>
          </div>
      </div></td>
      </tr>
	  
    <tr>
      <td colspan="2" valign="top">&nbsp;</td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && ((!isset($row_liste_ugl['id_pde']) && !isset($row_liste_ugl['id_pde'])))) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce PDE ?',<?php echo $_GET["id"]; ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
</div>
</form>

</div> </div>