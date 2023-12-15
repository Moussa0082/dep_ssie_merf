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
if(isset($_GET["id"]))
{
    $id=($_GET["id"]);
    $query_edit_methode = "SELECT * FROM ".$database_connect_prefix."methode_marche WHERE sigle='$id'";
    try{
        $edit_methode = $pdar_connexion->prepare($query_edit_methode);
        $edit_methode->execute();
        $row_edit_methode = $edit_methode ->fetch();
        $totalRows_edit_methode = $edit_methode->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    if(isset($row_edit_methode['categorie_concerne'])) $as = explode(", ", $row_edit_methode['categorie_concerne']); else $as=array();
}
//Catégories
$query_liste_categorie = "SELECT * FROM ".$database_connect_prefix."categorie_marche ORDER BY nom_categorie asc";
try{
    $liste_categorie = $pdar_connexion->prepare($query_liste_categorie);
    $liste_categorie->execute();
    $row_liste_categorie = $liste_categorie ->fetchAll();
    $totalRows_liste_categorie = $liste_categorie->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]))?"Modification":"Nouvel ajout"?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2m" id="form2m" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="sigle" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" name="sigle" id="sigle" type="text" value="<?php if(isset($_GET['id'])) echo $row_edit_methode['sigle'];  ?>" size="10" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_edit_methode['sigle']."'"; ?>) check_code('verif_code.php?t=methode_marche&','w=sigle='+this.value+' ','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">  
      <td>
        <div class="form-group">
          <label for="description" class="col-md-3 control-label">Libell&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" name="description" id="description" cols="32" rows="2"><?php if(isset($_GET['id'])) echo $row_edit_methode['description'];  ?></textarea>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">  
      <td>
        <div class="form-group">
          <label for="categorie" class="col-md-3 control-label">Catégories concernées <span class="required">*</span></label>
          <div class="col-md-9">
<div style="height: 100px;overflow:scroll;">
          <?php if($totalRows_liste_categorie>0) { ?>
          <?php foreach($row_liste_categorie as $row_liste_categorie){ ?> <input <?php if(isset($_GET['id'])) { if(in_array($row_liste_categorie['code_categorie'], $as, TRUE)) echo 'checked="checked"'; } ?> type="checkbox" name="categorie[]" id="categorie_<?php echo $row_liste_categorie['code_categorie']; ?>" value="<?php echo $row_liste_categorie['code_categorie']; ?>" title="<?php echo $row_liste_categorie['code_categorie'].": ".substr($row_liste_categorie['nom_categorie'],0, 80); ?>"  />&nbsp;<label for="categorie_<?php echo $row_liste_categorie['code_categorie']; ?>"><?php echo $row_liste_categorie['code_categorie'].": ".substr($row_liste_categorie['nom_categorie'],0, 80); ?></label><br />
                        <?php }  } ?>
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
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette méthode; ?','<?php echo $_GET["id"]; ?>')" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form2m" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>
</div> </div>