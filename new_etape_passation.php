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
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_etape1 = "SELECT * FROM ".$database_connect_prefix."methode_etape WHERE etapei='$id'";
$edit_etape1  = mysql_query($query_edit_etape1 , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_etape1  = mysql_fetch_assoc($edit_etape1 );
$totalRows_edit_etape1  = mysql_num_rows($edit_etape1 );
if($totalRows_edit_etape1>0){
  do{

  $as[$row_edit_etape1['methodei']] = $row_edit_etape1['duree_etape'];

  }while($row_edit_etape1  = mysql_fetch_assoc($edit_etape1 )); }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_etape = "SELECT * FROM ".$database_connect_prefix."etape_marche WHERE id_etape='$id'";
$edit_etape  = mysql_query($query_edit_etape , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_etape  = mysql_fetch_assoc($edit_etape );
$totalRows_edit_etape  = mysql_num_rows($edit_etape );

}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_methode = "SELECT * FROM ".$database_connect_prefix."methode_marche ORDER BY sigle asc";
$liste_methode  = mysql_query($query_liste_methode , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_methode  = mysql_fetch_assoc($liste_methode);
$totalRows_liste_methode  = mysql_num_rows($liste_methode);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_categorie = "SELECT * FROM categorie_marche ORDER BY nom_categorie asc";
$liste_categorie  = mysql_query($query_liste_categorie , $pdar_connexion) or die(mysql_error());
$row_liste_categorie  = mysql_fetch_assoc($liste_categorie);
$totalRows_liste_categorie  = mysql_num_rows($liste_categorie);

//liste groupes d'entete
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_groupe = "SELECT * FROM ".$database_connect_prefix."groupe_etape ORDER BY id_groupe asc";
$liste_groupe  = mysql_query($query_liste_groupe , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_groupe  = mysql_fetch_assoc($liste_groupe);
$totalRows_liste_groupe  = mysql_num_rows($liste_groupe);
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
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]))?"Modification":"Nouvel ajout"; ?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2e" id="form2e" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="categorie" class="col-md-3 control-label">Cat&eacute;gorie <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="categorie" id="categorie" class="form-control required"  >
              <option value="">Selectionnez</option>
                            <?php if($totalRows_liste_categorie>0){
do { $libelle = (strlen($row_liste_categorie['nom_categorie'])>70)?substr($row_liste_categorie['nom_categorie'],0,70)."...":$row_liste_categorie['nom_categorie'];
?>
                            <option value="<?php echo $row_liste_categorie['code_categorie']?>"<?php if(isset($row_edit_etape["categorie"])) {if (!(strcmp($row_liste_categorie['code_categorie'], $row_edit_etape["categorie"]))) {echo "SELECTED";} } ?>><?php echo $libelle; ?></option>
                            <?php
} while ($row_liste_categorie = mysql_fetch_assoc($liste_categorie)); }
  $rows = mysql_num_rows($liste_categorie);
  if($rows > 0) {
      mysql_data_seek($liste_categorie, 0);
	  $row_liste_categorie = mysql_fetch_assoc($liste_categorie);
  }
?>
            </select>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="intitule" class="col-md-3 control-label">Etape <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" name="intitule" id="intitule" cols="32" rows="1"><?php if(isset($_GET['id'])) echo $row_edit_etape['intitule'];  ?></textarea>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="groupe" class="col-md-3 control-label">Groupe d'ent&ecirc;te </label>
          <div class="col-md-9">
            <select name="groupe" id="groupe" class="form-control"  >
              <option value="">Selectionnez</option>
                            <?php if($totalRows_liste_groupe>0){
do { $libelle = (strlen($row_liste_groupe['libelle_groupe'])>70)?substr($row_liste_groupe['libelle_groupe'],0,70)."...":$row_liste_groupe['libelle_groupe'];
?>
                            <option value="<?php echo $row_liste_groupe['id_groupe']?>"<?php if(isset($row_edit_etape["groupe"])) {if ($row_liste_groupe['id_groupe']==$row_edit_etape["groupe"]) {echo "SELECTED";} } ?>><?php echo $libelle; ?></option>
                            <?php
} while ($row_liste_groupe = mysql_fetch_assoc($liste_groupe)); }
?>
            </select>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="methode" class="col-md-3 control-label">M&eacute;thodes concernées <span class="required">*</span></label>
          <div class="col-md-9">
<div style="height: 250px;overflow:scroll;">
<?php if($totalRows_liste_methode>0) { $p=0; ?>
<table border="1" align="left" cellspacing="0" bordercolor="#F0F0F0">
<tr>
      <td align="left">&nbsp;</td>
      <td align="left"><div align="center"><span class="Style1">Dur&eacute;e (J)</span></div></td>
    </tr>
<?php do { ?>
    
    <tr>
    <td align="left"><input onChange="activer_cellule('duree_<?php echo $row_liste_methode['id_methode']; ?>');" <?php if(is_array($as) && isset($as[$row_liste_methode['id_methode']])) echo 'checked="checked"'; else  ?>  type="checkbox" id="methode_<?php echo $row_liste_methode['id_methode']; ?>" name="methode[]" value="<?php echo $row_liste_methode['id_methode']; ?>" /><label for="methode_<?php echo $row_liste_methode['id_methode']; ?>" title="<?php echo $row_liste_methode['description']; ?>"><?php echo $row_liste_methode['sigle']; ?></label> </td>
    <td align="left"><input name="duree[]" id="duree_<?php echo $row_liste_methode['id_methode']; ?>" <?php if(is_array($as) && isset($as[$row_liste_methode['id_methode']])) echo ''; else echo 'disabled="disabled"'; ?> style="border:ridge; height:auto" type="text" value="<?php echo (is_array($as) && isset($as[$row_liste_methode['id_methode']]))?$as[$row_liste_methode['id_methode']]:''; ?>" size="5" alt="*," class="form-control required" /></td>
    </tr>
<?php $p++; } while ($row_liste_methode = mysql_fetch_assoc($liste_methode)); ?>
</table>
<?php } ?>
</div>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="code" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" name="code" id="code" type="text" value="<?php if(isset($_GET['id'])) echo $row_edit_etape['id_etape'];  ?>" size="10" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_edit_etape['id_etape']."'"; ?>) check_code('verif_code.php?t=etape_marche&','w=ordre='+this.value+' and categorie='+$('#categorie').val()+'' ,'code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div>      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette étape; ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form2e" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>