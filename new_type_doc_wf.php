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

$as=$ad=$av=array();

if(isset($_GET["id"]))
{
  $id=($_GET["id"]);
  $query_edit_etape = "SELECT * FROM ".$database_connect_prefix."type_doc_workflow WHERE code='$id'";
             try{
    $edit_etape = $pdar_connexion->prepare($query_edit_etape);
    $edit_etape->execute();
    $row_edit_etape = $edit_etape ->fetch();
    $totalRows_edit_etape = $edit_etape->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  if(isset($row_edit_etape['responsable_concerne']))
  {
    $as = explode("|", $row_edit_etape['responsable_concerne']);
    $ad = explode("|", $row_edit_etape['duree']);
    foreach($as as $a=>$b) $av[$b] = $ad[$a];
  }
}

$query_liste_responsable = "SELECT distinct fonction FROM ".$database_connect_prefix."personnel ORDER BY fonction asc";
             try{
    $liste_responsable = $pdar_connexion->prepare($query_liste_responsable);
    $liste_responsable->execute();
    $row_liste_responsable = $liste_responsable ->fetchAll();
    $totalRows_liste_responsable = $liste_responsable->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".row-border").validate();
	});
</script>
<script type="text/javascript">
function activer_cellule(id)
{
  if(document.getElementById(id).disabled)
  {
    document.getElementById(id).disabled=false;
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
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form4" id="form4" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="code" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" name="code" id="code" type="text" value="<?php if(isset($_GET['id'])) echo $row_edit_etape['code'];  ?>" size="10" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_edit_etape['code']."'"; ?>) check_code('verif_code.php?t=type_doc_workflow&','w=code='+this.value+' ','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
          <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="intitule" class="col-md-3 control-label">Libell&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" name="intitule" id="intitule" cols="32" rows="1"><?php if(isset($_GET['id'])) echo $row_edit_etape['intitule'];  ?></textarea>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">  
      <td>
        <div class="form-group">
          <label for="description" class="col-md-3 control-label">Description <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" name="description" id="description" cols="32" rows="1"><?php if(isset($_GET['id'])) echo $row_edit_etape['description'];  ?></textarea>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">  
      <td>
        <div class="form-group">
          <label for="responsable" class="col-md-3 control-label">Responsables concernées <span class="required">*</span></label>
          <div class="col-md-9">
<div style="height: 150px;overflow:scroll;">
<?php if($totalRows_liste_responsable>0) { $p=0; ?>
<table border="1" align="left" cellspacing="0" bordercolor="#F0F0F0">
<tr>
      <td align="left"><div align="center"><span class="Style1">Responsable</span></div></td>
      <td align="left"><div align="center"><span class="Style1">Dur&eacute;e</span></div></td>
    </tr>
<?php foreach($row_liste_responsable as $row_liste_responsable){ ?>
    
    <tr>
    <td align="left"><input onChange="activer_cellule('duree_<?php echo $row_liste_responsable['fonction']; ?>');" <?php if(is_array($as) && in_array($row_liste_responsable['fonction'], $as, TRUE)) echo 'checked="checked"'; ?>  type="checkbox" id="responsable_<?php echo $row_liste_responsable['fonction']; ?>" name="responsable[]" value="<?php echo $row_liste_responsable['fonction']; ?>" /><label for="responsable_<?php echo $row_liste_responsable['fonction']; ?>" title="<?php echo $row_liste_responsable['fonction']; ?>"><?php echo $row_liste_responsable['fonction']; ?></label> </td>
    <td align="left"><input name="duree[]" id="duree_<?php echo $row_liste_responsable['fonction']; ?>" <?php if(!is_array($ad) || !in_array($row_liste_responsable['fonction'], $as, TRUE)) echo 'disabled="disabled"'; ?> style="border:ridge; height:auto" type="text" value="<?php echo (isset($av[$row_liste_responsable['fonction']]))?$av[$row_liste_responsable['fonction']]:''; ?>" size="5" class="form-control required" /></td>
    </tr>
<?php $p++; }  ?>
</table>
<?php } ?>
</div>
          </div>
        </div>      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce type de document; ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form4" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>