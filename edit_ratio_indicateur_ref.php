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
header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET['type_ind'])) $type_ind=$_GET['type_ind']; else $type_ind="inconnu";
if(isset($_GET['id_v'])) $id_v=$_GET['id_v']; else $id_v=0;
if(isset($_GET['iden'])) $idsy=$_GET['iden']; else $idsy=0;

if(isset($_GET["iden"])) { $idsy=$_GET["iden"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_data = "SELECT * FROM ratio_indicateur_ref where indicateur_ref='$idsy'";
$edit_data = mysql_query($query_edit_data, $pdar_connexion) or die(mysql_error());
$row_edit_data = mysql_fetch_assoc($edit_data);
$totalRows_edit_data = mysql_num_rows($edit_data);

}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_ic = "SELECT * FROM  referentiel_indicateur  where id_ref_ind=$idsy";
$edit_ic = mysql_query($query_edit_ic, $pdar_connexion) or die(mysql_error());
$row_edit_ic = mysql_fetch_assoc($edit_ic);
$totalRows_edit_ic = mysql_num_rows($edit_ic);

//if(isset($row_edit_ic['composante'])) $cpeind=$row_edit_ic['composante']; else $row_edit_ic=0;

//echo "type= ".$type_ind; echo "volet= ".$id_v;
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_indicateur = "SELECT * FROM  referentiel_indicateur  where type_ref_ind='$type_ind' and id_ref_ind <> '$idsy'  ORDER BY code_ref_ind";
$liste_indicateur  = mysql_query($query_liste_indicateur , $pdar_connexion) or die(mysql_error());
$row_liste_indicateur = mysql_fetch_assoc($liste_indicateur );
$totalRows_liste_indicateur = mysql_num_rows($liste_indicateur );
?>
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form6").validate();
	});
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) { ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["iden"]) && intval($_GET["iden"])>0)?"Ratio d'indicateur":"Ratio d'indicateur" ?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form6" id="form6" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="indicateur" class="col-md-3 control-label">Ratio de:</label>
          <div class="col-md-9">
            <?php echo $row_edit_ic['intitule_ref_ind'];?>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="indicateur" class="col-md-3 control-label">Coefficient <span class="required">*</span></label>
          <div class="col-md-2">
          <input class="form-control required" type="text" size="5" name="coefficient" value=" <?php if(isset($_GET['iden'])) echo $row_edit_data['coefficient']; ?>" />
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="indicateur" class="col-md-3 control-label">Num&eacute;rateur</label>
          <div class="col-md-9">
           <div style="height: 130px;overflow:scroll;">

          <?php if($totalRows_liste_indicateur>0) { ?>
          <?php  do {   ?> <input <?php if(isset($_GET['iden'])) {if(isset($row_edit_data['numerateur']) && $row_edit_data['numerateur']== $row_liste_indicateur['id_ref_ind']) {echo 'checked="checked"';} } ?> type="radio" id="numerateur_<?php echo $row_liste_indicateur['id_ref_ind']; ?>" name="numerateur" value="<?php echo $row_liste_indicateur['id_ref_ind']?>" title="<?php echo $row_liste_indicateur['code_ref_ind'].": ".substr($row_liste_indicateur['intitule_ref_ind'],0, 80)?>"  /><label for="numerateur_<?php echo $row_liste_indicateur['id_ref_ind']; ?>"><?php echo $row_liste_indicateur['code_ref_ind'].": ".substr($row_liste_indicateur['intitule_ref_ind'],0, 70)." (".$row_liste_indicateur['unite'].")"?></label><br />

          <?php

						} while ($row_liste_indicateur = mysql_fetch_assoc($liste_indicateur));
  $rows = mysql_num_rows($liste_indicateur);
  if($rows > 0) {
      mysql_data_seek($liste_indicateur, 0);
	  $row_liste_indicateur = mysql_fetch_assoc($liste_indicateur);
  }}
						?>       </div>
        </div></div>
      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="indicateur" class="col-md-3 control-label">D&eacute;nominateur</label>
          <div class="col-md-9">
           <div style="height: 130px;overflow:scroll;">
        <input <?php if(isset($_GET['iden'])) { echo 'checked="checked"';} ?> type="radio" name="denominateur" id="denominateur" value="-1"  /><label for="denominateur">Aucun</label><br />
        <?php if($totalRows_liste_indicateur>0) { ?>
          <?php  do {   ?> <input <?php if(isset($_GET['iden'])) {if(isset($row_edit_data['denominateur']) && $row_edit_data['denominateur']== $row_liste_indicateur['id_ref_ind']) {echo 'checked="checked"';} } ?> type="radio" id="denominateur_<?php echo $row_liste_indicateur['id_ref_ind']; ?>" name="denominateur" value="<?php echo $row_liste_indicateur['id_ref_ind']?>" title="<?php echo $row_liste_indicateur['code_ref_ind'].": ".substr($row_liste_indicateur['intitule_ref_ind'],0, 80)?>"  /><label for="denominateur_<?php echo $row_liste_indicateur['id_ref_ind']; ?>"><?php echo $row_liste_indicateur['code_ref_ind'].": ".substr($row_liste_indicateur['intitule_ref_ind'],0, 70)." (".$row_liste_indicateur['unite'].")"?></label><br />

          <?php
												} while ($row_liste_indicateur = mysql_fetch_assoc($liste_indicateur));
  $rows = mysql_num_rows($liste_indicateur);
  if($rows > 0) {
      mysql_data_seek($liste_indicateur, 0);
	  $row_liste_indicateur = mysql_fetch_assoc($liste_indicateur);
  }}

						 ?></div>
        </div></div>
      </td>
    </tr>

</table>
<div class="form-actions">
<?php if(isset($_GET["iden"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["iden"]; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["iden"]) && intval($_GET["iden"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="MM_insert" type="hidden" value="MM_insert" size="32" alt="">
<input name="MM_form" id="MM_form" type="hidden" value="form6" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>