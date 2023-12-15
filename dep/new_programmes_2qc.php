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
//header('Content-Type: text/html; charset=ISO-8859-15');

if(isset($_GET["id"]) && intval($_GET["id"])>0)
{
  $id=intval($_GET["id"]);
 /* mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_programmes_2qc = "SELECT * FROM ".$database_connect_prefix."programmes_ccc WHERE id_programmes=$id ";
  $liste_programmes_2qc  = mysql_query_ruche($query_liste_programmes_2qc , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_programmes_2qc  = mysql_fetch_assoc($liste_programmes_2qc);
  $totalRows_liste_programmes_2qc  = mysql_num_rows($liste_programmes_2qc);*/
  
  $query_liste_programmes_2qc = "SELECT * FROM ".$database_connect_prefix."programmes_ccc WHERE id_programmes=$id ";
  try{
  $liste_programmes_2qc = $pdar_connexion->prepare($query_liste_programmes_2qc);
  $liste_programmes_2qc->execute();
  $row_liste_programmes_2qc = $liste_programmes_2qc ->fetch();
  $totalRows_liste_programmes_2qc = $liste_programmes_2qc->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
	});
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) { ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification programme":"Nouveau programme"?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr>
      <td valign="top"> <div class="form-group">
          <label for="sigle_programme" class="col-md-10 control-label">Sigle <span class="required">*</span></label>
          <div class="col-md-12">
            <input style="width: 80px;" class="form-control required" type="text" name="sigle_programme" id="sigle_programme" value="<?php echo (isset($row_liste_programmes_2qc['sigle_programme']))?$row_liste_programmes_2qc['sigle_programme']:""; ?>" size="32" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_programmes_2qc['sigle_programme']."'"; ?>) check_code('verif_code.php?t=programmes_ccc&','w=sigle_programme='+this.value+'','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div></td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><div class="form-group">
          <label for="nom_programme" class="col-md-10 control-label">Intitul&eacute; <span class="required">*</span></label>
          <div class="col-md-12">
      <textarea class="form-control required" name="nom_programme" id="nom_programme" rows="1"><?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_liste_programmes_2qc['nom_programme']:''; ?></textarea>
          </div>
        </div></td>
    </tr>
    <tr>
      <td colspan="2" valign="top"> <div class="form-group">
          <label for="vision" class="col-md-10 control-label">Vision <span class="required">*</span></label>
          <div class="col-md-12">
      <textarea class="form-control required" name="vision" id="vision" rows="1"><?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_liste_programmes_2qc['vision']:''; ?></textarea>
          </div>
        </div></td>
    </tr>
    <tr>
      <td colspan="2" valign="top">
        <div class="form-group">
          <label for="objectif" class="col-md-10 control-label">Objectifs <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control required" name="objectif" id="objectif" rows="1"><?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_liste_programmes_2qc['objectif']:''; ?></textarea>
          </div>
        </div>      </td>
    </tr>
    <tr>
      <td valign="top" width="50%">
        <div class="form-group">
          <label for="annee_debut" class="col-md-6 control-label">Ann&eacute;e d&eacute;but <span class="required">*</span></label>
          <div class="col-md-4">
            <input class="form-control required" type="text" name="annee_debut" id="annee_debut" maxlength="4" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_programmes_2qc['annee_debut']; ?>" size="32" />
          </div>
        </div>      </td>
      <td valign="top" width="50%">
        <div class="form-group">
          <label for="annee_fin" class="col-md-6 control-label">Ann&eacute;e fin <span class="required">*</span></label>
          <div class="col-md-4">
            <input class="form-control required" type="text" name="annee_fin" id="annee_fin" maxlength="4" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_programmes_2qc['annee_fin']; ?>" size="32" />
          </div>
        </div>      </td>
    </tr>
    <tr>
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="actif" class="col-md-2 control-label">Actif <span class="required">*</span></label>
          <div class="col-md-6">
            Oui <input type="radio" name="actif" value="0" <?php if (!(strcmp("0", $row_liste_programmes_2qc['actif']))) {echo 'checked="checked"';} if(!isset($_GET["id"])) echo 'checked="checked"';  ?> >&nbsp;Non <input type="radio" name="actif" value="1" <?php if (!(strcmp("1", $row_liste_programmes_2qc['actif']))) {echo 'checked="checked"';}  ?>>
          </div>
        </div>      </td>
    </tr>
</table>
<div class="form-actions">
  <?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && intval($_GET["id"])>0 && isset($_SESSION["clp_niveau"]) && $_SESSION["clp_niveau"]==1) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce programme 2QC ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>