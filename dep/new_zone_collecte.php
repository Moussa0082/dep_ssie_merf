<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & D&eacute;veloppement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=ISO-8859-15');

if(isset($_GET["id"]))
{
  $id=$_GET["id"];
  $query_edit_station = "SELECT * FROM ".$database_connect_prefix."t_zone WHERE id_zone='$id' ";
  try{
    $edit_station = $pdar_connexion->prepare($query_edit_station);
    $edit_station->execute();
    $row_edit_zone = $edit_station ->fetch();
    $totalRows_edit_station = $edit_station->rowCount();
	}catch(Exception $e){ die(mysql_error_show_message($e)); }
}

  
?>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
		$(".colorpicker").remove();
        $(".bs-colorpicker").colorpicker();
        $(".colorpicker").attr("style","z-index:10060");
	});
</script>

<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]))?"Modification zone":"Nouvelle zone"; ?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="reference" class="col-md-9 control-label">Nom ou r&eacute;f&eacute;rence  <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea name="reference" cols="32" rows="1" class="form-control required" id="reference"><?php  if(isset($_GET["id"])) echo $row_edit_zone['nom_zone']; ?></textarea>
          </div>
        </div>      </td>
      </tr>
      <tr>
      <td valign="top">
        <div class="form-group">
          <label for="titre" class="col-md-9 control-label">Titre  <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea name="titre" cols="32" rows="1" class="form-control required" id="titre"><?php  if(isset($_GET["id"])) echo $row_edit_zone['titre']; ?></textarea>
          </div>
        </div>      </td>
      </tr>
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="couleur" class="col-md-10 control-label">Couleur <span class="required">*</span></label>
          <div class="col-md-12">
            <input data-colorpicker-guid="1" data-color-format="hex" class="form-control bs-colorpicker required" type="text" name="couleur" id="couleur" value="<?php echo isset($row_edit_zone['couleur'])?$row_edit_zone['couleur']:""; ?>" size="32" />
          </div>
        </div>      </td>
      </tr>
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="coord_gps" class="col-md-9 control-label">Coordonn&eacute;es (Latitude, Longitude)  <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea name="coord_gps" cols="32" rows="1" class="form-control required" id="coord_gps"><?php  if(isset($_GET["id"])) echo $row_edit_zone['coord_gps']; ?></textarea>
          </div>
        </div>      </td>
      </tr>
	<tr>
        <td valign="top"><div class="form-group">
          <label for="couche" class="col-md-9 control-label">Couche </label>
          <div class="col-md-12">
            <input class="form-control " type="file" name="couche" id="couche" value="" size="32" accept=".zip" />
            <?php  if(isset($_GET["id"])) echo file_exists('./map/leaflet.shapefile/'.$row_edit_zone['shapefile'])?"<a href='./map/leaflet.shapefile/".$row_edit_zone['shapefile']."'>".$row_edit_zone['shapefile']."</a>":""; ?>
            </div>
          </div></td>
        </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette zone ?','<?php echo $_GET["id"]; ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>