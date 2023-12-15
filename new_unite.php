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

if(isset($_GET["id"]) && intval($_GET["id"])>0)
{
    $id=intval($_GET["id"]);
    $query_liste_unite = "SELECT * FROM ".$database_connect_prefix."unite_indicateur WHERE id_unite=$id ";
    try{
        $liste_unite = $pdar_connexion->prepare($query_liste_unite);
        $liste_unite->execute();
        $row_liste_unite = $liste_unite ->fetch();
        $totalRows_liste_unite = $liste_unite->rowCount();
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
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification Unit&eacute;":"Nouvel Unit&eacute;"?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="unite" class="col-md-3 control-label">Sigle <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="unite" id="unite" value="<?php if(isset($row_liste_unite['unite'])) echo $row_liste_unite['unite']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">  
      <td>
        <div class="form-group">
          <label for="definition" class="col-md-3 control-label">D&eacute;finition <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" name="definition" id="definition" cols="25" rows="5"><?php if(isset($row_liste_unite['definition'])) echo $row_liste_unite['definition']; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cet unit&eacute; ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>