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

//if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y"); if(isset($_GET['cp'])) {$cp=$_GET['cp'];} else $cp=0;
if(isset($_GET['id_ind'])) { $id_ind = $_GET['id_ind']; }
//if(isset($_GET['code_act'])) {$code_activite = $_GET['code_act'];} else $code_activite="";

function frenchMonthName($monthnum) {
      $armois=array("", "Jan", "Fév", "Mars", "Avril", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc");
      if ($monthnum>0 && $monthnum<13) {
          return $armois[$monthnum];
      } else {
          return $monthnum;
      }
  }

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$page = $_SERVER['PHP_SELF'];

$lien = $lien1 = $_SERVER['PHP_SELF'];
$lien .= "?id_ind=$id_ind";
$lien1 .= "?id_ind=$id_ind";

if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sup_tache = sprintf("DELETE from soutien_indicateur_sygri2 WHERE id_indicateur_soutien=%s",
                         GetSQLValueString($id, "text"));
  $Result = mysql_query($query_sup_tache, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $insertGoTo = $lien;
  if ($Result1) $insertGoTo .= "&del=ok"; else $insertGoTo .= "&del=no";
  header(sprintf("Location: %s", $lien)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  $insertSQL = sprintf("INSERT INTO soutien_indicateur_sygri2 (indicateur_sygri_niveau2, intitule_indicateur_soutien, proportion, cible, referentiel, ordre, id_personnel, date_enregistrement) VALUES ($id_ind,%s, %s, %s, %s,  %s, '$personnel', '$date')",
                       
						
						GetSQLValueString($_POST['intitule_indicateur_soutien'], "text"),
						GetSQLValueString($_POST['proportion'], "double"),
						GetSQLValueString($_POST['cible'], "double"),
						GetSQLValueString($_POST['referentiel'], "int"),
                        GetSQLValueString($_POST['ordre'], "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok&id_ind=$id_ind";
  else $insertGoTo .= "?insert=no&id_ind=$id_ind";
  header(sprintf("Location: %s", $insertGoTo));
}

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from soutien_indicateur_sygri2 WHERE id_indicateur_soutien=%s",
                         GetSQLValueString($id, "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok&id_ind=$id_ind";
    else $insertGoTo .= "?del=no&id_ind=$id_ind";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['MM_update'];
if($_POST['n_lot']<1) $_POST['n_lot']=1;
	$insertSQL = sprintf("UPDATE soutien_indicateur_sygri2 SET intitule_indicateur_soutien=%s, proportion=%s, cible=%s, referentiel=%s, ordre=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur_soutien='$c'",
					   GetSQLValueString($_POST['intitule_indicateur_soutien'], "text"),
					   GetSQLValueString($_POST['proportion'], "double"),
					   GetSQLValueString($_POST['cible'], "double"),
					   GetSQLValueString($_POST['referentiel'], "int"),
					   GetSQLValueString($_POST['ordre'], "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?update=ok&id_ind=$id_ind";
  else $insertGoTo .= "?update=no&id_ind=$id_ind";
  header(sprintf("Location: %s", $insertGoTo));
}
}
//activite
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_indicateur_s2 = "SELECT * FROM indicateur_sygri2_projet where id_indicateur_sygri_niveau2_projet='$id_ind'";
$indicateur_s2  = mysql_query($query_indicateur_s2 , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_indicateur_s2  = mysql_fetch_assoc($indicateur_s2);
$totalRows_indicateur_s2  = mysql_num_rows($indicateur_s2);
//$lib=$row_indicateur_s2['intitule_indicateur_sygri2'];

//query liste
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_indicateur = "select * FROM soutien_indicateur_sygri2 where indicateur_sygri_niveau2='$id_ind' ORDER BY ordre ASC";
$liste_indicateur  = mysql_query($query_liste_indicateur, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_indicateur = mysql_fetch_assoc($liste_indicateur);
$totalRows_liste_indicateur  = mysql_num_rows($liste_indicateur);

$pcent = 100;

if(isset($_GET["id"])) { $id=$_GET["id"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_indicateur = "SELECT * FROM soutien_indicateur_sygri2 WHERE id_indicateur_soutien='$id'";
$edit_indicateur = mysql_query($query_edit_indicateur, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_indicateur = mysql_fetch_assoc($edit_indicateur);
$totalRows_edit_indicateur = mysql_num_rows($edit_indicateur);
//$pcent = 100+$row_edit_indicateur["cible"];
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_referentiel = "SELECT * FROM referentiel_indicateur WHERE type_ref_ind!=3";
$liste_referentiel = mysql_query($query_liste_referentiel, $pdar_connexion) or die(mysql_error());
$row_liste_referentiel = mysql_fetch_assoc($liste_referentiel);
$totalRows_liste_referentiel = mysql_num_rows($liste_referentiel);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind FROM referentiel_indicateur";
$liste_ind_ref  = mysql_query($query_liste_ind_ref , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref);
$totalRows_liste_ind_ref  = mysql_num_rows($liste_ind_ref);
$liste_ind_ref_array = array();
$unite_ind_ref_array = array();
do{  $liste_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]] = $row_liste_ind_ref["intitule_ref_ind"]; $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];
}while($row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->
  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>
<script type="text/javascript" src="plugins/noty/layouts/top.js"></script>
<script type="text/javascript" src="plugins/noty/themes/default.js"></script>
<script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>
  <script type="text/javascript" src="plugins/select2/select2.min.js"></script>
<script>

	$().ready(function() {

		// validate the comment form when it is submitted

		$("#form0").validate();

        $("#tabs").tabs();

        $(".modal-dialog", window.parent.document).width(780);

        $(".select2-select-00").select2({allowClear:true});

	});

</script>
<style>
@media(min-width:558px){.col-md-12 {width: 100%;}.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}
</style>
</head>
<body>
<?php if(!isset($_GET['add'])) { ?>
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i>  <strong>Indicateurs de soutien </strong><span class="Style18">
 </span></h4>
   <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<a href="<?php echo $lien1."&add=1"; ?>" title="Ajouter" class="pull-right p11"><i class="icon-plus"> Nouvel indicateur </i></a>

<?php } ?>
</div>
<div class="widget-content">
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >
            <thead>
                <tr>
                  <td>Indicateur</td>
                  <td>Unit&eacute;</td>
                  <td><div align="center" title="Proportion">Valeur cible </div></td>
                  
                  <td><div align="center">Poids (%) </div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center" width="80" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
 <?php $t=0;  if($totalRows_liste_indicateur>0) {
   $p1="j"; $sp=0; $i=0;do { $i=$i+1; $t=$t+1; ?>
      <tr>
        <td <?php if(!isset($unite_ind_ref_array[$row_liste_indicateur["referentiel"]])) {?>style="color:#FF0000"<?php } ?>><?php echo $row_liste_indicateur['ordre'].": ".$row_liste_indicateur['intitule_indicateur_soutien']; ?></td>
    
        <td><?php if(isset($unite_ind_ref_array[$row_liste_indicateur["referentiel"]])) echo $unite_ind_ref_array[$row_liste_indicateur["referentiel"]]; ?></td>
        <td align="center" title="cible"><?php echo $row_liste_indicateur['cible'];  ?>        </td>
     
        <td align="center" title="Proportion"><?php echo $row_liste_indicateur['proportion'];  ?></td>
        <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
       <td align="center"><a href="<?php echo $lien."&id=".$row_liste_indicateur['id_indicateur_soutien']."&add=1"; ?>" title="Modifier l'indicateur" ><img align="center" src='./images/edit.png' width='20' height='20' alt='Modifier' style="margin:0px 5px 0px 0px;"></a>
<a onClick="return confirm('Voulez vous vraiment suppimer cette t&acirc;che ?');" href="<?php echo $lien."&id_sup=".$row_liste_indicateur['id_indicateur_soutien'].""; ?>" title="Supprimer l'indicateur" ><img align="center" src='./images/delete.png' width='20' height='20' alt='Supprimer' style="margin:0px 5px 0px 0px;"></a></td>
      <?php } ?>
      </tr>
    <?php } while ($row_liste_indicateur = mysql_fetch_assoc($liste_indicateur)); ?>
  <?php } else { ?> <tr><td align="center" colspan="5"><h2>Aucun indicateur de soutien !</h2></td></tr><?php } ?>
  </table>

</div></div>
</div>
<?php } else { ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification d'indicateur":"Nouvel indicateur"; ?></h4>
<a href="<?php echo $lien1; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">

<form action="<?php echo $lien1; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
   
	    <tr valign="top">
      <td>
      <div class="form-group">
          <label for="ordre" class="col-md-3 control-label">Num&eacute;ro d'ordre  <span class="required">*</span></label>
          <div class="col-md-3">
        <input class="required" type="text" name="ordre" id="ordre" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_indicateur['ordre']; ?>" size="10" />
          </div>
        </div>      </td>
	   </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="intitule_indicateur_soutien" class="col-md-3 control-label">Indicateur de soutien  <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="2" type="text" name="intitule_indicateur_soutien" id="intitule_indicateur_soutien"><?php if(isset($_GET['id'])) echo $row_edit_indicateur['intitule_indicateur_soutien']; ?></textarea>
          </div>
        </div>      </td>
    </tr>
	

	    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="cible" class="col-md-3 control-label">Valeur cible <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="required" type="text" name="cible" id="cible" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_indicateur['cible']; ?>" size="10" />
            </div>
        </div></td>
	   </tr>
	       <tr valign="top">
      <td>
        <div class="form-group">
          <label for="proportion" class="col-md-3 control-label">Poids(%) <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="required" type="text" name="proportion" id="proportion" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_indicateur['proportion']; ?>" size="10" />
            </div>
        </div></td>
	   </tr>
	 <tr valign="top">
      <td>
      <div class="form-group">
          <label for="referentiel" class="col-md-3 control-label">R&eacute;f&eacute;rentiel <span class="required">*</span></label>
          <div class="col-md-9">
             <select name="referentiel" id="referentiel" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez un indicateur">
              <option></option>
         <option value="0" <?php if (isset($row_edit_data["referentiel"]) && $row_edit_data['referentiel']=="0") {echo "SELECTED";} ?>>Non-d&eacute;finie</option>
              <?php if($totalRows_liste_referentiel>0){ do { ?>
              <option value="<?php echo $row_liste_referentiel['id_ref_ind']; ?>" <?php if (isset($row_edit_indicateur["referentiel"]) && $row_liste_referentiel['id_ref_ind']==$row_edit_indicateur["referentiel"]) {echo "SELECTED";} ?>><?php echo $row_liste_referentiel['code_ref_ind'].": ".$row_liste_referentiel['intitule_ref_ind']; ?></option>
                <?php  } while ($row_liste_referentiel = mysql_fetch_assoc($liste_referentiel)); } ?>
            </select>
          </div>
        </div>     </td>
	   </tr>
    </table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo ($_GET["id"]); ?>" />
<?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
<a href="<?php echo $lien1; ?>" class="btn pull-right" title="Annuler" >Annuler</a>
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2)) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette t&acirc;che ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>
<?php include_once 'modal_add.php'; ?>
</body>
</html>