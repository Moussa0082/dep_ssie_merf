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

$id_os = (isset($_GET["id_os"]) && intval($_GET["id_os"])>0)?intval($_GET["id_os"]):'';
/*
if ((isset($_GET["id_sup"]) && intval($_GET["id_sup"])>0)) {
  $id = intval($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from source_os WHERE id_source=%s",
                       GetSQLValueString($id, "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = "./objectif_general_edit.php";
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}*/

if(isset($_GET["id"]) && intval($_GET["id"])>0)
{
  $id=intval($_GET["id"]); 
   $query_liste_src = "SELECT * FROM source_os WHERE id_source=$id ";
  try{
    $liste_src = $pdar_connexion->prepare($query_liste_src);
    $liste_src->execute();
    $row_liste_src = $liste_src ->fetch();
    $totalRows_liste_src = $liste_src->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}

$query_liste_os = "SELECT * FROM objectif_specifique WHERE projet='".$_SESSION["clp_projet"]."' order by id_objectif_specifique";
try{
    $liste_os = $pdar_connexion->prepare($query_liste_os);
    $liste_os->execute();
    $row_liste_os = $liste_os ->fetchAll();
    $totalRows_liste_os = $liste_os->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification":"Nouvelle"; echo " Source Objectif Sp&eacute;cifique";?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
<tr><td><br /></td></tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="objectif" class="col-md-3 control-label">Objectif sp&eacute;cifique <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="objectif" id="objectif" class="form-control required" onchange="get_content('menu_cercle.php','id='+this.value,'cercle','');" >
              <option value="">Selectionnez</option>
			    <?php if($totalRows_liste_os>0) { $o=0; foreach($row_liste_os as $row_liste_os){ ?>

              <option value="<?php echo $row_liste_os['id_objectif_specifique']; ?>" <?php if(isset($_GET["id"]) && intval($_GET["id"])>0) {if ($row_liste_os['id_objectif_specifique']==$row_liste_src['objectif_specifique']) {echo "SELECTED";}} elseif(!empty($id_os)) {if ($row_liste_os['id_objectif_specifique']==$id_os) {echo "SELECTED";}} ?>><?php echo $row_liste_os['intitule_objectif_specifique']; ?></option>
              <?php }} ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="nom" class="col-md-3 control-label">Intitul&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" name="nom" id="nom" cols="25" rows="5"><?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_src['intitule_source']; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr><td><br /></td></tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette Source Objectif sp&eacute;cifique ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form7" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>