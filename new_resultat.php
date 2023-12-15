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

$id_cp = (isset($_GET["id_cp"]) && intval($_GET["id_cp"])>0)?intval($_GET["id_cp"]):'';
/*
if ((isset($_GET["id_sup"]) && intval($_GET["id_sup"])>0)) {
  $id = intval($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from resultat WHERE id_resultat=%s",
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
  $query_liste_iog = "SELECT * FROM resultat WHERE id_resultat=$id ";
try{
    $liste_iog = $pdar_connexion->prepare($query_liste_iog);
    $liste_iog->execute();
    $row_liste_iog = $liste_iog ->fetch();
    $totalRows_liste_iog = $liste_iog->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}

$query_liste_cmp = "SELECT * FROM activite_projet WHERE projet='".$_SESSION["clp_projet"]."' and niveau=1 order by code";
try{
    $liste_cmp = $pdar_connexion->prepare($query_liste_cmp);
    $liste_cmp->execute();
    $row_liste_cmp = $liste_cmp ->fetchAll();
    $totalRows_liste_cmp = $liste_cmp->rowCount();
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
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification":"Nouveau"; echo " Effet";?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form9" id="form9" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
<tr><td><br /></td></tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="code" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="code" id="code" value="<?php echo (isset($row_liste_iog['code_resultat']))?$row_liste_iog['code_resultat']:""; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="composante" class="col-md-3 control-label">Composante <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="composante" id="composante" class="form-control required" onchange="get_content('menu_cercle.php','id='+this.value,'cercle','');" >
              <option value="">Selectionnez</option>
		    <?php if($totalRows_liste_cmp>0) { $o=0; foreach($row_liste_cmp as $row_liste_cmp){ ?>
              <option value="<?php echo $row_liste_cmp['code']; ?>" <?php if(isset($_GET["id"]) && intval($_GET["id"])>0) {if ($row_liste_cmp['code']==$row_liste_iog['composante']) {echo "SELECTED";}} elseif(!empty($id_cp)) {if ($row_liste_cmp['code']==$id_cp) {echo "SELECTED";}} ?>><?php echo $row_liste_cmp['intitule']; ?></option>
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
            <textarea class="form-control required" name="nom" id="nom" cols="25" rows="5"><?php echo (isset($row_liste_iog['intitule_resultat']))?$row_liste_iog['intitule_resultat']:""; ?></textarea>
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
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cet Effet ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form9" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>