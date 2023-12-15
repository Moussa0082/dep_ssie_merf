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

$as=$ad=array();

if(isset($_GET["id"]))
{
  $id=($_GET["id"]);
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_etape = "SELECT * FROM ".$database_connect_prefix."groupe_etape WHERE id_groupe='$id'";
$edit_etape  = mysql_query($query_edit_etape , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_etape  = mysql_fetch_assoc($edit_etape );
$totalRows_edit_etape  = mysql_num_rows($edit_etape );

}

?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
	});
</script>
<style type="text/css">
<!--
.Style1 {
	font-size: 12px;
	font-weight: bold;
}
-->
</style>

<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]))?"Modification":"Nouvel ajout"?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2gp" id="form2gp" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="libelle_groupe" class="col-md-3 control-label">Intitul&eacute; groupe <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" name="libelle_groupe" id="libelle_groupe" type="text" value="<?php if(isset($_GET['id'])) echo $row_edit_etape['libelle_groupe'];  ?>" size="32"  />
          </div>
        </div>      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce groupe d'ent&ecirc;te ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form2gp" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>