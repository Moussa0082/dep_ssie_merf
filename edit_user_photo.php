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

$id_personnel=$_SESSION["clp_id"];


$query_liste_personnel = "SELECT * FROM ".$database_connect_prefix."personnel WHERE id_personnel='$id_personnel'";
$liste_personnel = $db ->prepare($query_liste_personnel);
$liste_personnel->execute(array(':id_programme' => $id));
$row_liste_personnel = $liste_personnel ->fetch();
$totalRows_liste_personnel = $liste_personnel->rowCount();

?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
	});
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) { ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification photo de profil":"Nouvelle photo de profil"?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
<tr><td><br /></td></tr>
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="photo" class="col-md-3 control-label">Image <span class="required">*</span></label>
          <div class="col-md-9">
          <div id="photo_prev">
          <?php if(isset($row_liste_personnel["avatar"]) && file_exists($row_liste_personnel["avatar"])) { ?>
          <img src="<?php echo $row_liste_personnel["avatar"]; ?>" width='160' height='160' alt='preview'>
          <?php } ?>
          </div>
            <input class="form-control required" type="file" name="photo" id="photo" value="" onchange="readImgURL(this,'photo_prev',160,160);" size="32" accept="image/x-png, image/gif, image/jpeg"  />
          </div>
        </div>
      </td>
    </tr>
    <tr><td><br /></td></tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>