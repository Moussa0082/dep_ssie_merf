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

$as=array();
if(isset($_GET["id"]))
{
    $id=($_GET["id"]);
    $query_edit_modele = "SELECT * FROM ".$database_connect_prefix."modele_marche WHERE code='$id'";
    try{
        $edit_modele = $pdar_connexion->prepare($query_edit_modele);
        $edit_modele->execute();
        $row_edit_modele = $edit_modele ->fetch();
        $totalRows_edit_modele = $edit_modele->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    if(isset($row_edit_modele['methode_concerne'])) $as = explode(", ", $row_edit_modele['methode_concerne']);
}

//Méthodes
$query_liste_methode = "SELECT * FROM ".$database_connect_prefix."methode_marche ORDER BY sigle asc";
try{
    $liste_methode = $pdar_connexion->prepare($query_liste_methode);
    $liste_methode->execute();
    $row_liste_methode = $liste_methode ->fetchAll();
    $totalRows_liste_methode = $liste_methode->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//Catégories
$query_liste_categorie = "SELECT * FROM ".$database_connect_prefix."categorie_marche ORDER BY code_categorie asc";
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
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2sm" id="form2sm" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="code" class="col-md-6 control-label">Code <span class="required">*</span></label>
          <div class="col-md-6">
            <input class="form-control required" name="code" id="code" type="text" value="<?php if(isset($_GET['id'])) echo $row_edit_modele['code'];  ?>" size="30" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_edit_modele['code']."'"; ?>) check_code('verif_code.php?t=modele_marche&','w=code='+this.value+' ','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div>      </td>
    </tr>


    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="methode" class="col-md-3 control-label">Methode concernées <span class="required">*</span></label>
          <div class="col-md-9">
<div style="height: 100px;overflow:scroll;">
          <?php if($totalRows_liste_methode>0) { ?>
          <?php foreach($row_liste_methode as $row_liste_methode){ ?> <input <?php if(isset($_GET['id'])) { if(in_array($row_liste_methode['sigle'], $as, TRUE)) echo 'checked="checked"'; } ?> type="checkbox" name="methode[]" id="methode_<?php echo $row_liste_methode['sigle']; ?>" value="<?php echo $row_liste_methode['sigle']; ?>" title="<?php echo $row_liste_methode['sigle'].": ".substr($row_liste_methode['description'],0, 80); ?>"  />&nbsp;<label for="methode_<?php echo $row_liste_methode['sigle']; ?>"><?php echo $row_liste_methode['sigle'].": ".substr($row_liste_methode['description'],0, 80); ?></label><br />
                        <?php } } ?>
</div>
          </div>
        </div>      </td>
    </tr>
    <tr>
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="categorie" class="col-md-3 control-label">Cat&eacute;gories de march&eacute;s <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="categorie" id="categorie" class="form-control required" >
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_categorie>0) { foreach($row_liste_categorie as $row_liste_categorie){ ?>
              <option value="<?php echo $row_liste_categorie['code_categorie']; ?>" <?php if (isset($row_edit_modele['categorie']) && $row_liste_categorie['code_categorie']==$row_edit_modele['categorie']) {echo "SELECTED";} ?>><?php echo $row_liste_categorie['code_categorie'].": ".$row_liste_categorie['nom_categorie']; ?></option>
              <?php } } ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
<tr valign="top">
      <td>
        <div class="form-group">
          <label for="examen" class="col-md-3 control-label">Revue <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="examen" id="examen" class="form-control required">
               <option value="">Selectionnez</option>
             <option value="A PRIORI" <?php if (isset($row_edit_modele['examen']) && $row_edit_modele['examen']=="A PRIORI") {echo "SELECTED";} ?> >A PRIORI</option>
             <option value="A POSTERIORI" <?php if (isset($row_edit_modele['examen']) && $row_edit_modele['examen']=="A POSTERIORI") {echo "SELECTED";} ?> >A POSTERIORI</option>
            </select>
          </div>
      </div>     </td>
    </tr>
	<tr valign="top">
      <td nowrap="nowrap">
        <div class="form-group">
          <label for="montant_min" class="col-md-3 control-label">Montant (FCFA): Min <span class="required">*</span></label>
          <div class="col-md-3">
   <input class="form-control required" name="montant_min" id="montant_min" type="text" value="<?php if(isset($_GET['id'])) { if($row_edit_modele['montant_min']>0) echo $row_edit_modele['montant_min']; else echo "-";} else echo "-";  ?>" size="15" />
          </div>
        </div>      </td>
    </tr>
	<tr valign="top">
      <td nowrap="nowrap">
        <div class="form-group">
          <label for="montant_max" class="col-md-3 control-label">Montant (FCFA): Max <span class="required">*</span></label>
          <div class="col-md-3">
   <input class="form-control required" name="montant_max" id="montant_max" type="text" value="<?php if(isset($_GET['id'])) { if($row_edit_modele['montant_max']>0) echo $row_edit_modele['montant_max']; else echo "-";} else echo "-";   ?>" size="15" />
          </div>
        </div>      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce modele; ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form2sm" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>