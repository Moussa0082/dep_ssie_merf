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
$id_cp = (isset($_GET["id_cp"]) && !empty($_GET["id_cp"]))?($_GET["id_cp"]):'';
/*
if ((isset($_GET["id_sup"]) && intval($_GET["id_sup"])>0)) {
  $id = intval($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from indicateur_resultat WHERE id_indicateur_resultat=%s",
                       GetSQLValueString($id, "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = "./cadre_logique_edit.php";
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}*/

if(isset($_GET["id"]) && intval($_GET["id"])>0)
{
  $id=intval($_GET["id"]);
  $query_liste_iog = "SELECT * FROM indicateur_resultat WHERE id_indicateur_resultat=$id ";
try{
    $liste_iog = $pdar_connexion->prepare($query_liste_iog);
    $liste_iog->execute();
    $row_liste_iog = $liste_iog ->fetch();
    $totalRows_liste_iog = $liste_iog->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
    /*
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_effet = "SELECT resultat.* FROM resultat, indicateur_resultat where id_resultat=resultat ";
  $query_liste_effet .= (!empty($id_os))?" and composante=$id_os ":'';
  $query_liste_effet .= " group by id_resultat ";
  $liste_effet  = mysql_query($query_liste_effet , $pdar_connexion) or die(mysql_error());
  $row_liste_effet  = mysql_fetch_assoc($liste_effet);
  $totalRows_liste_effet  = mysql_num_rows($liste_effet);  */
}

$query_liste_cmp = "SELECT * FROM activite_projet WHERE projet='".$_SESSION["clp_projet"]."' and niveau=1 order by code";
try{
    $liste_cmp = $pdar_connexion->prepare($query_liste_cmp);
    $liste_cmp->execute();
    $row_liste_cmp = $liste_cmp ->fetchAll();
    $totalRows_liste_cmp = $liste_cmp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_unite = "SELECT * FROM unite_indicateur order by id_unite";
try{
    $liste_unite  = $pdar_connexion->prepare($query_liste_unite );
    $liste_unite ->execute();
    $row_liste_unite = $liste_unite  ->fetchAll();
    $totalRows_liste_unite  = $liste_unite ->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal2").validate();
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification":"Nouvel"; echo " Indicateur Objectif V&eacute;rifiable";?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal2 row-border" method="post" enctype="multipart/form-data" name="form10" id="form10" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
<tr><td colspan="3"><br /></td></tr>
    <tr valign="top">
      <td colspan="3">
        <div class="form-group">
          <label for="code" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="code" id="code" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_iog['code_ir']; ?>" size="32" />
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td colspan="3">
        <div class="form-group">
          <label for="composante" class="col-md-3 control-label">Composante <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="composante" id="composante" class="form-control required" onchange="get_content('menu_effet.php','id='+this.value,'resultat','');" >
              <option value="">Selectionnez</option>
		    <?php if($totalRows_liste_cmp>0) { $o=0; foreach($row_liste_cmp as $row_liste_cmp){ ?>
              <option value="<?php echo $row_liste_cmp['code']; ?>" <?php if(!empty($id_cp)) {if ($row_liste_cmp['code']==$id_cp) {echo "SELECTED";}} ?>><?php echo $row_liste_cmp['intitule']; ?></option>
              <?php }} ?>
            </select>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td colspan="3">
        <div class="form-group">
          <label for="effet" class="col-md-3 control-label">Effet <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="resultat" id="resultat" class="form-control required" >
              <option value="">Selectionnez</option>
            </select>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td colspan="3">
        <div class="form-group">
          <label for="nom" class="col-md-3 control-label">Intitul&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" name="nom" id="nom" cols="25" rows="3"><?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_iog['intitule_indicateur_resultat']; ?></textarea>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td colspan="3"><div class="form-group">
          <label for="unite" class="col-md-3 control-label">Unit&eacute; de mesure <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="unite" id="unite" class="form-control required" >
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_unite>0) {  foreach($row_liste_unite as $row_liste_unite){ ?>
              <option value="<?php echo $row_liste_unite['unite']; ?>" <?php if(!empty($row_liste_iog['unite'])) {if ($row_liste_unite['unite']==$row_liste_iog['unite']) {echo "SELECTED";}} ?>><?php echo $row_liste_unite['definition']; ?></option>
              <?php }} ?>
            </select>
          </div>
      </div></td>
    </tr>
    <tr valign="top">
      <td colspan="3" bgcolor="#CCCCCC"><div align="center"><em><strong>Donn&eacute;es</strong></em></div></td>
    </tr>
    <tr valign="top">
      <td><div class="form-group">
          <label for="reference" class="col-md-9 control-label">R&eacute;f&eacute;rence <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control required" type="text" name="reference" id="reference" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_iog['reference']; ?>" size="32" />
          </div>
      </div></td>
      <td><div class="form-group">
          <label for="mi_parcours" class="col-md-9 control-label">Mi-Parcours <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control required" type="text" name="mi_parcours" id="mi_parcours" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_iog['mi_parcours']; ?>" size="32" />
          </div>
      </div></td>
      <td><div class="form-group">
          <label for="cible_dp" class="col-md-9 control-label">Fin <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control required" type="text" name="cible_dp" id="cible_dp" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_iog['cible_dp']; ?>" size="32" />
          </div>
      </div></td>
    </tr>
    <tr valign="top">
      <td colspan="3" bgcolor="#CCCCCC"><div align="center"><em><strong>Moyens de v&eacute;rification</strong></em></div></td>
    </tr>
    <tr valign="top">
      <td><div class="form-group">
          <label for="source" class="col-md-9 control-label">Source <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea name="source" cols="32" class="form-control required" id="source"><?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_iog['source']; ?>
    </textarea>
          </div>
      </div></td>
      <td><div class="form-group">
          <label for="periodicite" class="col-md-9 control-label">Fr&eacute;quence<span class="required">*</span></label>
          <div class="col-md-12">
            <textarea name="periodicite" cols="32" class="form-control required" id="periodicite"><?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_iog['periodicite']; ?> 
    </textarea>
          </div>
      </div></td>
      <td><div class="form-group">
          <label for="responsable" class="col-md-9 control-label">Responsables <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea name="responsable" cols="32" class="form-control required" id="responsable"><?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_iog['responsable']; ?>
    </textarea>
          </div>
      </div></td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cet Indicateur ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form10" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

<script type="text/javascript" >
get_content('menu_effet.php','id=<?php echo $id_cp.'&id_s='.((isset($row_liste_iog["resultat"]))?$row_liste_iog["resultat"]:$id_os); ?>','resultat','');
</script>

</div> </div>