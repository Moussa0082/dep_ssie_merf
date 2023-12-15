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
?>
<?php if(isset($_GET["iframe"])){ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->
<link rel="stylesheet" type="text/css" href="<?php print $config->theme_folder;?>/plugins/jquery-ui.css"/>
<link href="<?php print $config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder;?>/responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder;?>/icons.css" rel="stylesheet" type="text/css"/>
<link href='<?php print $config->theme_folder;?>/css.css' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
<link href="<?php print $config->theme_folder; ?>/plugins/datatables_bootstrap.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder; ?>/plugins/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder; ?>/plugins/wysiwyg-color.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>
<script type="text/javascript" src="plugins/noty/layouts/top.js"></script>
<script type="text/javascript" src="plugins/noty/themes/default.js"></script>
<script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="plugins/bootstrap-wysihtml5/wysihtml5.min.js"></script>
<script type="text/javascript" src="plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.min.js"></script>
<style>
@media(min-width:558px){.col-md-12 {width: 100%;}.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}
</style>
</head>
<body>
<?php } else { ?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<?php } ?>

<?php
$id_prd = (isset($_GET["prd"]) && !empty($_GET["prd"]))?intval($_GET["prd"]):0;

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $acteur="";
	if(!empty($_POST['acteur'])) { foreach($_POST['acteur'] as $vacteur) { $acteur=$acteur.",".$vacteur; } }
	$cible = $_POST['cible_cmr'];
if(trim(strtolower($cible))=="oui") $cible = 0;
if(trim(strtolower($cible))=="non") $cible = 1;
if(trim(strtolower($cible))=="n/a") $cible = -1;

$ciblermp = (isset($_POST['cible_rmp']))?$_POST['cible_rmp']:"";
if(trim(strtolower($ciblermp))=="oui") $ciblermp = 0;
if(trim(strtolower($ciblermp))=="non") $ciblermp = 1;
if(trim(strtolower($ciblermp))=="n/a") $ciblermp = -1;

/*$reference = $_POST['reference_cmr'];
if(trim(strtolower($reference))=="oui") $reference = 0;
if(trim(strtolower($reference))=="non") $reference = 1;
if(trim(strtolower($reference))=="n/a") $reference = -1;*/
  $insertSQL = sprintf("INSERT INTO indicateur_produit_cmr (indicateur_prd, referentiel, intitule_indicateur, cible_cmr,cible_rmp, responsable_collecte, cle, code_irprd, personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, '$personnel', '$date')",
					   GetSQLValueString($_POST['indicateur_cl'], "int"),
                       GetSQLValueString((isset($_POST['referentiel'])?$_POST['referentiel']:0), "int"),
	   				   GetSQLValueString($_POST['indicateur'], "text"),
					   GetSQLValueString($cible, "double"),
                       GetSQLValueString($ciblermp, "double"),
					   GetSQLValueString($acteur, "text"),
                       GetSQLValueString($_POST['cle'], "int"),
   					 // GetSQLValueString($_POST['beneficiaire'], "int"),
					   GetSQLValueString(trim($_POST['code_irprd']), "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    /*$insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));   exit(0); */
 ?>
  <script type="text/javascript">
  $("#acharger<?php echo $id_prd; ?>", window.parent.document).html(get_content('cmr_produit_reload.php','id=<?php echo $id_prd; ?>','acharger<?php echo $id_prd; ?>','','',1));
  $(".close", window.parent.document).click();
  </script>
  <?php exit(0);
}

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
      $id = intval($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from indicateur_produit_cmr WHERE id_indicateur=%s",
                           GetSQLValueString($id, "int"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
      /*$insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();  */
 ?>
  <script type="text/javascript">
  $("#acharger<?php echo $id_prd; ?>", window.parent.document).html(get_content('cmr_produit_reload.php','id=<?php echo $id_prd; ?>','acharger<?php echo $id_prd; ?>','','',1));
  $(".close", window.parent.document).click();
  </script>
  <?php exit(0);
}

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id']; $acteur="";
	if(!empty($_POST['acteur'])) { foreach($_POST['acteur'] as $vacteur) { $acteur=$acteur.",".$vacteur; } }
		$cible = $_POST['cible_cmr'];
if(trim(strtolower($cible))=="oui") $cible = 0;
if(trim(strtolower($cible))=="non") $cible = 1;
if(trim(strtolower($cible))=="n/a") $cible = -1;

/*$reference = $_POST['reference_cmr'];
if(trim(strtolower($reference))=="oui") $reference = 0;
if(trim(strtolower($reference))=="non") $reference = 1;
if(trim(strtolower($reference))=="n/a") $reference = -1;*/

$ciblermp = (isset($_POST['cible_rmp']))?$_POST['cible_rmp']:"";
if(trim(strtolower($ciblermp))=="oui") $ciblermp = 0;
if(trim(strtolower($ciblermp))=="non") $ciblermp = 1;
if(trim(strtolower($ciblermp))=="n/a") $ciblermp = -1;

  $insertSQL = sprintf("UPDATE indicateur_produit_cmr SET  indicateur_prd=%s, referentiel=%s,  intitule_indicateur=%s, cible_cmr=%s, cible_rmp=%s, responsable_collecte=%s, cle=%s,  code_irprd=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur='$c'",
					   GetSQLValueString($_POST['indicateur_cl'], "int"),
                       GetSQLValueString((isset($_POST['referentiel'])?$_POST['referentiel']:0), "int"),
	   				   GetSQLValueString($_POST['indicateur'], "text"),
					   GetSQLValueString($cible, "double"),
                       GetSQLValueString($ciblermp, "double"),
   					   GetSQLValueString($acteur, "text"),
                       GetSQLValueString($_POST['cle'], "int"),
   					 // GetSQLValueString($_POST['beneficiaire'], "int"),
					   GetSQLValueString(trim($_POST['code_irprd']), "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    /*$insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));   exit(0);  */
 ?>
  <script type="text/javascript">
  $("#acharger<?php echo $id_prd; ?>", window.parent.document).html(get_content('cmr_produit_reload.php','id=<?php echo $id_prd; ?>','acharger<?php echo $id_prd; ?>','','',1));
  $(".close", window.parent.document).click();
  </script>
  <?php exit(0);
}

}

if(isset($_GET["id"])) { $id=$_GET["id"];
$query_edit_ind = "SELECT * FROM indicateur_produit_cmr WHERE id_indicateur='$id'";
try{
    $edit_ind = $pdar_connexion->prepare($query_edit_ind);
    $edit_ind->execute();
    $row_edit_ind = $edit_ind ->fetch();
    $totalRows_edit_ind = $edit_ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if(isset($row_edit_ind['responsable_collecte'])) $as = explode(",", $row_edit_ind['responsable_collecte']); else $as=array();

}else $id=0;
//indicateur produit

$query_liste_ind_cl = "SELECT 	id_indicateur_produit, intitule_indicateur_produit, code_iprd  FROM resultat, produit, indicateur_produit WHERE resultat.projet='".$_SESSION["clp_projet"]."' and id_produit=produit and id_resultat=effet order by code_resultat, code_produit, code_iprd";
try{
    $liste_ind_cl = $pdar_connexion->prepare($query_liste_ind_cl);
    $liste_ind_cl->execute();
    $row_liste_ind_cl = $liste_ind_cl ->fetchAll();
    $totalRows_liste_ind_cl = $liste_ind_cl->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_acteur = "SELECT id_acteur, nom_acteur FROM acteur order by categorie,code_acteur, nom_acteur";
try{
    $liste_acteur = $pdar_connexion->prepare($query_liste_acteur);
    $liste_acteur->execute();
    $row_liste_acteur = $liste_acteur ->fetchAll();
    $totalRows_liste_acteur = $liste_acteur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_referentiel = "SELECT id_ref_ind, code_ref_ind, intitule_ref_ind, unite FROM referentiel_indicateur  order by code_ref_ind, intitule_ref_ind";
try{
    $liste_referentiel = $pdar_connexion->prepare($query_liste_referentiel);
    $liste_referentiel->execute();
    $row_liste_referentiel = $liste_referentiel ->fetchAll();
    $totalRows_liste_referentiel = $liste_referentiel->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


$query_liste_unite = "SELECT * FROM unite_indicateur order by id_unite";
try{
    $liste_unite = $pdar_connexion->prepare($query_liste_unite);
    $liste_unite->execute();
    $row_liste_unite = $liste_unite ->fetchAll();
    $totalRows_liste_unite = $liste_unite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>
<script>
	$().ready(function() {
		$("#form1").validate();
		 $(".modal-dialog", window.parent.document).width(700);
        $(".select2-select-00").select2({allowClear:true});
		});

</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification indicateur de produit du CMR":"Nouveau indicateur de produit du CMR"?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
 <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="code_irprd" class="col-md-3 control-label">Code indicateur <span class="required">*</span></label>
          <div class="col-md-3">
            <input name="code_irprd" type="text" class="form-control required" id="code_irprd" value="<?php echo (isset($row_edit_ind['code_irprd']))?$row_edit_ind['code_irprd']:""; ?>" size="32" maxlength="10" />
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="indicateur" class="col-md-3 control-label">Indicateur CMR <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="indicateur" id="indicateur" value="<?php echo (isset($row_edit_ind['intitule_indicateur']))?$row_edit_ind['intitule_indicateur']:""; ?>" size="32" />
          </div>
        </div>      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="referentiel" class="col-md-3 control-label">R&eacute;f&eacute;rentiel <span class="required">*</span></label>
          <div class="col-md-9">
             <select style="width: 385px;" name="referentiel" id="referentiel" class="col-md-9 select2-select-00 required" data-placeholder="S&eacute;lectionnez un indicateur">
              <option></option>
         <option value="0" <?php if (isset($row_edit_ind["referentiel"]) && $row_edit_ind['referentiel']=="0") {echo "SELECTED";} ?>>Non-d&eacute;finie</option>
		  			   <?php  if($totalRows_liste_referentiel>0) { foreach($row_liste_referentiel as $row_liste_referentiel){ ?>
              <option value="<?php echo $row_liste_referentiel['id_ref_ind']; ?>" <?php if (isset($row_edit_ind["referentiel"]) && $row_liste_referentiel['id_ref_ind']==$row_edit_ind["referentiel"]) {echo "SELECTED";} ?>><?php echo $row_liste_referentiel['code_ref_ind'].": ".$row_liste_referentiel['intitule_ref_ind']; ?></option>
                <?php  }  } ?>
            </select>
          </div>
        </div>      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="indicateur_cl" class="col-md-3 control-label"> Produit <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="indicateur_cl" id="indicateur_cl" class="form-control required" >
              <option value="">Selectionnez</option>
			   <?php  if($totalRows_liste_ind_cl>0) { foreach($row_liste_ind_cl as $row_liste_ind_cl){ ?>
              <option value="<?php echo $row_liste_ind_cl['id_indicateur_produit']; ?>" <?php if (isset($row_edit_ind['indicateur_prd']) && $row_liste_ind_cl['id_indicateur_produit']==$row_edit_ind['indicateur_prd']) {echo "SELECTED";} ?>><?php echo (strlen($row_liste_ind_cl['intitule_indicateur_produit'])>55)?substr($row_liste_ind_cl['intitule_indicateur_produit'],0,55)." ...":$row_liste_ind_cl['intitule_indicateur_produit']; ?></option>
              <?php }} ?>
            </select>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="cible_cmr" class="col-md-3 control-label">Cible DCP <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" type="text" name="cible_cmr" id="cible_cmr" value="<?php if(isset($_GET['id']))  {if($row_edit_ind['cible_cmr']==0 && isset($unite) &&  $unite=="Oui/Non") echo "Oui"; elseif($row_edit_ind['cible_cmr']==1 && isset($unite) && $unite=="Oui/Non") echo "Non"; elseif($row_edit_ind['cible_cmr']==-1) echo "n/a"; else echo $row_edit_ind['cible_cmr']; } else echo "N/A"; ?>" size="32" />
          </div>
          <label for="cible_rmp" class="col-md-3 control-label">Cible R&eacute;vue <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" type="text" name="cible_rmp" id="cible_rmp" value="<?php echo (isset($row_edit_ind['cible_rmp']))?$row_edit_ind['cible_rmp']:""; ?>" size="32" />
          </div>
        </div>      </td>
    </tr>
  <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="unite" class="col-md-3 control-label">Unit&eacute; de mesure <span class="required">*</span></label>
          <div class="col-md-3">
           <select name="unite" id="unite" class="form-control required" >
              <option value="">Selectionnez</option>
			   <?php  if($totalRows_liste_unite>0) { foreach($row_liste_unite as $row_liste_unite){ ?>
              <option value="<?php echo $row_liste_unite['unite']; ?>" <?php if (isset($row_edit_ind['unite_cmr']) && $row_liste_unite['unite']==$row_edit_ind['unite_cmr']) {echo "SELECTED";} ?>><?php echo $row_liste_unite['unite']; ?></option>
              <?php }} ?>
            </select>
			  </div>
          <label for="periodicite" class="col-md-3 control-label">Mode de calcul <span class="required">*</span></label>
          <div class="col-md-3">
           <select name="periodicite" id="periodicite" class="form-control required" >
              <option value=" ">Selectionnez</option>
              <option value="Unique" <?php if (isset($row_edit_ind['periodicite']) && "Unique"==$row_edit_ind['periodicite']) {echo "SELECTED";} ?>>Unique</option>
              <option value="Somme" <?php if (isset($row_edit_ind['periodicite']) && "Somme"==$row_edit_ind['periodicite']) {echo "SELECTED";} ?>>Somme</option>
              <option value="Moyenne" <?php if (isset($row_edit_ind['periodicite']) && "Moyenne"==$row_edit_ind['periodicite']) {echo "SELECTED";} ?>>Moyenne</option>
              <option value="Ratio" <?php if (isset($row_edit_ind['periodicite']) && "Ratio"==$row_edit_ind['periodicite']) {echo "SELECTED";} ?>>Ratio</option>

            </select>
			  </div>
        </div>       </td>
    </tr>
       <tr valign="top">
      <td colspan="2">  <div class="form-group">
          <label for="acteur" class="col-md-3 control-label">Responsable <span class="required">*</span></label>
          <div class="col-md-9">
             <select name="acteur[]" id="acteur" class="col-md-12 select2-select-00 required" multiple="multiple" data-placeholder="S&eacute;lectionnez un partenaire">
              <option></option>
		  			   <?php  if($totalRows_liste_acteur>0) { foreach($row_liste_acteur as $row_liste_acteur){ ?>
              <option value="<?php echo $row_liste_acteur['id_acteur']?>" <?php if(isset($_GET['id'])) {if(in_array($row_liste_acteur['id_acteur'], $as, TRUE)) {echo "SELECTED";} } ?>><?php echo $row_liste_acteur['nom_acteur']?></option>
                <?php  }  } ?>
            </select>
          </div>
        </div> </td>
    </tr>
	 <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="cle" class="col-md-3 control-label">Indicateur cl&eacute; <span class="required">*</span></label>
          <div class="col-md-9">Non&nbsp;<input name="cle" class="required" type="radio" checked="checked" value="0" <?php if(isset($_GET['id']) && $row_edit_ind['cle']==0) echo 'checked="checked"'; else echo 0; ?> />&nbsp;&nbsp;Oui&nbsp;
<input name="cle" class="required" type="radio" value="1" <?php if(isset($_GET['id']) && $row_edit_ind['cle']==1) echo 'checked="checked"'; else echo 0; ?> />
          </div>
        </div>      </td>
      </tr>
    <tr valign="top">
      <td colspan="2">&nbsp;
          </td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
  <input type="hidden" name="prd" value="<?php echo $id_prd; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && intval($_GET["id"])>0 && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2) ) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer l\'indicateur ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>
<?php if(isset($_GET["iframe"])){ ?>
</body>
</html>
<?php } ?>