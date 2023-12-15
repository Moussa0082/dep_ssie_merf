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
////header('Content-Type: text/html; charset=UTF-8');

$interdit_array = array("classeur","LKEY","annee","projet","structure","id_personnel","date_enregistrement","modifier_le","modifier_par","etat");

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_feuille = "SELECT * FROM ".$database_connect_prefix."indicateur_config WHERE id='$id' and projet='".$_SESSION["clp_projet"]."' ";
  $liste_feuille  = mysql_query_ruche($query_liste_feuille , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_feuille = mysql_fetch_assoc($liste_feuille);
  $totalRows_liste_feuille  = mysql_num_rows($liste_feuille);
  $tmp = explode('_',$row_liste_feuille['id_fiche']); $classeur = intval($tmp[1]);
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_classeur = "SELECT * FROM ".$database_connect_prefix."classeur ";
$liste_classeur = mysql_query_ruche($query_liste_classeur, $pdar_connexion) or die(mysql_error());
$row_liste_classeur = mysql_fetch_assoc($liste_classeur);
$totalRows_liste_classeur = mysql_num_rows($liste_classeur);
$mode_calcul = array("SOMME","MOYENNE","COMPTER");
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
        //$(".modal-dialog", window.parent.document).width(600);
        $(".colorpicker").remove();
        $(".bs-colorpicker").colorpicker();
        $(".colorpicker").attr("style","z-index:10060");
        <?php if(isset($_GET["id"]) && !empty($_GET["id"])){ ?>
        get_content('menu_feuille_indicateur.php','id=<?php echo $row_liste_feuille["type"]."&id_s=".$row_liste_feuille["ind"]; ?>','indicateur','');
        get_content('menu_feuille.php','id=<?php echo $classeur."&id_s=".$row_liste_feuille["id_fiche"]; ?>','feuille','');
        get_content('menu_feuille_colonne.php','id=<?php echo $row_liste_feuille["id_fiche"]."&id_s=".$row_liste_feuille["col"]; ?>','colonne','');
        <?php } ?>
	});
</script>

<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification":"Nouveau"; ?></h4>
</div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="type" class="col-md-3 control-label">Type <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="type" id="type" class="form-control required" onchange="get_content('menu_feuille_indicateur.php','id='+this.value,'indicateur','');" >
              <option value="">Selectionnez</option>
              <option value="PTBA" <?php if (isset($row_liste_feuille['type']) && $row_liste_feuille['type']=="PTBA") {echo "SELECTED";} ?>>Indicateurs PTBA</option>
              <option value="CMR" <?php if (isset($row_liste_feuille['type']) && $row_liste_feuille['type']=="CMR") {echo "SELECTED";} ?>>Indicateurs CMR</option>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="indicateur" class="col-md-3 control-label">Indicateur <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="indicateur" id="indicateur" class="form-control required"  >
              <option value="">Selectionnez</option>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="classeur" class="col-md-3 control-label">Classeur <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="classeur" id="classeur" class="form-control required" onchange="get_content('menu_feuille.php','id='+this.value,'feuille','');" >
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_classeur>0){ do { ?>
              <option value="<?php echo $row_liste_classeur['id_classeur'];?>" <?php if (isset($classeur) && $row_liste_classeur['id_classeur']==$classeur) {echo "SELECTED";} ?> ><?php echo $row_liste_classeur['libelle'];?></option>
                <?php  } while ($row_liste_classeur = mysql_fetch_assoc($liste_classeur)); } ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="feuille" class="col-md-3 control-label">Feuille <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="feuille" id="feuille" class="form-control required" onchange="get_content('menu_feuille_colonne.php','id='+this.value,'colonne','');" >
              <option value="">Selectionnez</option>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="colonne" class="col-md-3 control-label">Colonne <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="colonne" id="colonne" class="form-control required" >
              <option value="">Selectionnez</option>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="mode_calcul" class="col-md-3 control-label">Mode de calcul </label>
          <div class="col-md-9">
            <select name="mode_calcul" id="mode_calcul" class="form-control required" >
              <option value="">Selectionnez</option>
              <?php foreach($mode_calcul as $a){ ?>
              <option value="<?php echo $a; ?>" <?php if (isset($row_liste_feuille['mode_calcul']) && ($a==$row_liste_feuille['mode_calcul'] || empty($row_liste_feuille['mode_calcul']))) {echo "SELECTED";} ?> ><?php echo $a; ?></option>
                <?php  } ?>
            </select>
          </div>
        </div>
      </td>              
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="etat" class="col-md-3 control-label">Accueil </label>
          <div class="col-md-9">
            <select name="etat" id="etat" class="form-control required" >
              <option value="0" <?php if (isset($row_liste_feuille['etat']) && $row_liste_feuille['etat']==0) {echo "SELECTED";} ?>>Non</option>
              <option value="1" <?php if (isset($row_liste_feuille['etat']) && $row_liste_feuille['etat']==1) {echo "SELECTED";} ?>>Oui</option>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <div class="form-group">
          <label for="couleur" class="col-md-3 control-label">Couleur</label>
          <div class="col-md-9">
            <input data-colorpicker-guid="1" data-color-format="hex" class="form-control bs-colorpicker" type="text" name="couleur" id="couleur" value="<?php echo isset($row_liste_feuille['couleur'])?$row_liste_feuille['couleur']:""; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo ($_GET["id"]); ?>" />
<?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
  <?php if(isset($id) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2)) { ?>
  <input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
  <input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
  <?php } ?>
  <input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>