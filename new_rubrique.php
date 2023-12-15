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
    $query_liste_rubrique = "SELECT * FROM ".$database_connect_prefix."rubrique_projet WHERE code_rub=$id ";
    try{
        $liste_rubrique = $pdar_connexion->prepare($query_liste_rubrique);
        $liste_rubrique->execute();
        $row_liste_rubrique = $liste_rubrique ->fetch();
        $totalRows_liste_rubrique = $liste_rubrique->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
}
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification Rubrique":"Nouveau Rubrique"?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="code" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-9">
            <input onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_rubrique['code_rub']."'"; ?>) check_code('verif_code.php?t=rubrique_projet&','w=code_rub='+this.value+' ','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" class="form-control required" type="text" name="code" id="code" value="<?php if(isset($_GET["id"])) echo $row_liste_rubrique['code_rub']; ?>" size="32" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">  
      <td>
        <div class="form-group">
          <label for="nom_rubrique" class="col-md-3 control-label">Nom <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" name="nom_rubrique" id="nom_rubrique" cols="25" rows="5"><?php if(isset($_GET["id"])) echo $row_liste_rubrique['nom_rubrique']; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce Rubrique ?',<?php echo ($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>