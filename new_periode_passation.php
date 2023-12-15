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
$query_edit_etape = "SELECT * FROM ".$database_connect_prefix."periode_marche WHERE id_periode='$id'";
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
<script type="text/javascript">
function activer_cellule(id)
{
  if(document.getElementById(id).disabled)
  {
    document.getElementById(id).disabled=false;
    //document.getElementById(id).value=id_projet;
  }
  else
  {
    document.getElementById(id).disabled=true;
  }
}
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
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2p" id="form2p" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="debut" class="col-md-3 control-label">D&eacute;but <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" name="debut" id="debut" type="text" value="<?php if(isset($_GET['id'])) echo $row_edit_etape['debut'];  ?>" size="10"  />
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="fin" class="col-md-3 control-label">Fin <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" name="fin" id="fin" type="text" value="<?php if(isset($_GET['id'])) echo $row_edit_etape['fin'];  ?>" size="10"  />
          </div>
        </div>      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette p&eacute;riode; ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form2p" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>