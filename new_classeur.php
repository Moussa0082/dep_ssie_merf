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

$date=date("Y-m-d");
if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y");

if(isset($_GET["id"]) && !empty($_GET["id"]))
{                                                   
  $id=$_GET["id"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_classeur = "SELECT * FROM ".$database_connect_prefix."classeur WHERE id_classeur=$id";
  $liste_classeur  = mysql_query($query_liste_classeur , $pdar_connexion) or die(mysql_error());
  $row_liste_classeur  = mysql_fetch_assoc($liste_classeur);
  $totalRows_liste_classeur  = mysql_num_rows($liste_classeur);


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SHOW tables";
$liste_cp = mysql_query($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);

$cp_array=array();
if($totalRows_liste_cp>0) {
do {  if(strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="fiche_config" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche_".$id."_details_")!=""){  $cp_array[]=$row_liste_cp["Tables_in_$database_pdar_connexion"];
}
} while ($row_liste_cp = mysql_fetch_assoc($liste_cp));
$rows = mysql_num_rows($liste_cp);
if($rows > 0) {
mysql_data_seek($liste_cp, 0);
$row_liste_cp = mysql_fetch_assoc($liste_cp);
}}

}


?>
<style>
.hide1 {
  visibility: hidden;
}
.show1 {
  visibility: visible;
}
.firstcapitalize:first-letter{
  text-transform: capitalize;
}
</style>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
<script>
	$(document).ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
        $(".colorpicker").remove();
        $(".bs-colorpicker").colorpicker();
        $(".colorpicker").attr("style","z-index:10060");
         });
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification classeur":"Nouveau classeur"?></h4></div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="libelle" class="col-md-10 control-label">Libellé <?php echo (isset($id) && isset($libelle[$id]))?$libelle[$id]:""; ?><span class="required">*</span></label>
          <div class="col-md-12">
            <textarea name="libelle" id="libelle" required="required" class="form-control required"><?php echo (isset($row_liste_classeur["libelle"]))?$row_liste_classeur["libelle"]:""; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="note" class="col-md-10 control-label">Note </label>
          <div class="col-md-12">
            <textarea name="note" id="note" class="form-control"><?php echo ((isset($row_liste_classeur["note"])))?$row_liste_classeur["note"]:"";?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <div class="form-group">
          <label for="couleur" class="col-md-10 control-label">Couleur</label>
          <div class="col-md-12">
            <input data-colorpicker-guid="1" data-color-format="hex" class="form-control bs-colorpicker" type="text" name="couleur" id="couleur" value="<?php echo isset($row_liste_classeur['couleur'])?$row_liste_classeur['couleur']:""; ?>" size="32" />
          </div>
        </div>      </td>
    </tr>
    <!--<tr>
      <td colspan="2">
        <div class="form-group">
          <label for="couleur" class="col-md-10 control-label">Icon (pour carte)</label>
          <div class="col-md-12">
            <div id="photo_prev">
          <?php if(isset($row_liste_classeur['icone']) && file_exists($row_liste_classeur['icone'])) { ?>
          <img src="<?php echo $row_liste_classeur['icone']; ?>" width='20' height='20' alt='preview'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Supprimer l'ic&ocirc;ne ? <input type="checkbox" name="del_img" value="1" /><?php } ?>
          </div>
            <input type="file" class="form-control" name="icone" id="icone" value="" data-style="fileinput" alt='preview' onchange="readImgURL(this,'photo_prev',20,20);" size="32" accept="image/x-png" />

          </div>
        </div>      </td>
    </tr>-->

</table>
<div class="form-actions">
<input type="hidden" id="form_elem_del" name="form_elem_del" value="" />
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo isset($_GET["id"])?$_GET["id"]:""; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2) &&(isset($cp_array) && count($cp_array)<=0)) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce classeur ?','<?php echo isset($_GET["id"])?$_GET["id"]:""; ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>