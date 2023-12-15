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

?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
	});
</script>
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
          <?php if(isset($_SESSION["clp_id"]) && !empty($_SESSION["clp_id"]) && file_exists("./images/avatar/img_".($_SESSION["clp_id"]).".jpg")) { ?>
          <img src="<?php echo "./images/avatar/img_".($_SESSION["clp_id"]).".jpg"; ?>" width='160' height='160' alt='preview'>
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
    <?php if(isset($_GET["id"])){ ?>
    <input class="form-control required" type="hidden" name="id" id="id" value="<?php echo ($_SESSION["clp_id"]); ?>" size="32" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">
</div>
</form>

</div> </div>