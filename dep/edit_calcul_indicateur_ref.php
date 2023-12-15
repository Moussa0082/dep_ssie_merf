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

if(isset($_GET['type_ind'])) $type_ind=$_GET['type_ind']; else $type_ind="inconnu";
if(isset($_GET['id_v'])) $id_v=$_GET['id_v']; else $id_v=0;
if(isset($_GET['iden'])) $idsy=$_GET['iden']; else $idsy=0;

if(isset($_GET["iden"])) { $id=$_GET["iden"];
$query_edit_data = "SELECT * FROM calcul_indicateur_simple_ref where indicateur_ref='$idsy'";
try{
    $edit_data = $pdar_connexion->prepare($query_edit_data);
    $edit_data->execute();
    $row_edit_data = $edit_data ->fetch();
    $totalRows_edit_data = $edit_data->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if(isset($row_edit_data['indicateur_simple'])) $ais = explode(",", $row_edit_data['indicateur_simple']); else $ais=array();
}

$query_edit_ic = "SELECT * FROM  referentiel_indicateur  where id_ref_ind=$idsy";
try{
    $edit_ic = $pdar_connexion->prepare($query_edit_ic);
    $edit_ic->execute();
    $row_edit_ic = $edit_ic ->fetchAll();
    $totalRows_edit_ic = $edit_ic->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//if(isset($row_edit_ic['composante'])) $cpeind=$row_edit_ic['composante']; else $row_edit_ic=0;

$query_liste_indicateur = "SELECT * FROM referentiel_indicateur where mode_calcul='Unique' ORDER BY code_ref_ind";
 try{
    $liste_indicateur = $pdar_connexion->prepare($query_liste_indicateur);
    $liste_indicateur->execute();
    $row_liste_indicateur = $liste_indicateur ->fetchAll();
    $totalRows_liste_indicateur = $liste_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form5").validate();
	});
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) { ?>
<style type="text/css">
<!--
.Style1 {font-weight: bold}
-->
</style>

<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["iden"]) && intval($_GET["iden"])>0)?"Mode de calcul":"Mode de calcul" ?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form5" id="form5" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="indicateur" class="col-md-3 control-label">Indicateur:</label>
          <div class="col-md-9">
            <?php echo $row_edit_ic['intitule_ref_ind'];?>          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="indicateur" class="col-md-3 control-label">Formule</label>
          <div class="col-md-9">
          <input type="hidden" name="formule_indicateur_simple" value="<?php echo $row_edit_ic['mode_calcul']; ?>"/>
          <?php if(isset($row_edit_data['formule_indicateur_simple'])) echo $row_edit_data['formule_indicateur_simple']; else echo $row_edit_ic['mode_calcul']; ?>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td colspan="2" nowrap="nowrap" bgcolor="#CCCCCC">
       <div align="center"><span class="form-group">Indicateurs concern&eacute;s </span></div>
       </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <div class="col-md-12">
           <div style="height: 250px;overflow:scroll;">

          <?php if($totalRows_liste_indicateur>0) { ?>
          <?php foreach($row_liste_indicateur as $row_liste_indicateur) { ?> <input <?php if(isset($_GET['iden'])) {if(in_array($row_liste_indicateur['id_ref_ind'], $ais, TRUE)) {echo 'checked="checked"';} } ?> type="checkbox" id="indicateur_simple_<?php echo $row_liste_indicateur['id_ref_ind']; ?>" name="indicateur_simple[]" value="<?php echo $row_liste_indicateur['id_ref_ind']; ?>" title="<?php echo $row_liste_indicateur['code_ref_ind'].": ".substr($row_liste_indicateur['intitule_ref_ind'],0, 80)?>"  /><label for="indicateur_simple_<?php echo $row_liste_indicateur['id_ref_ind']; ?>"><?php echo $row_liste_indicateur['code_ref_ind'].": ".substr($row_liste_indicateur['intitule_ref_ind'],0, 80)?></label><br />
          <?php } } ?></div>
          </div>
        </div>      </td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET["iden"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["iden"]; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["iden"]) && intval($_GET["iden"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="MM_insert" type="hidden" value="MM_insert" size="32" alt="">
<input name="MM_form" id="MM_form" type="hidden" value="form5" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>