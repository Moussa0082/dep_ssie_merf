<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start(); $path="../";
include_once $path.'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"]) && !isset($_GET["id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}

include_once $path.$config->sys_folder . "/database/db_connexion.php";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$page = $_SERVER['PHP_SELF'];
$projet = (isset($_GET["idp"]) && !empty($_GET["idp"]))?$_GET["idp"]:0;
$lien = $lien1 = $page."?idp=".$projet;

//insertion
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."phase (code_phase, structure, projet, annee_debut, annee_fin, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, '$personnel', '$date')",
					   GetSQLValueString($_POST['code_phase'], "text"),
                       GetSQLValueString($_SESSION["clp_structure"], "text"),
					   GetSQLValueString($_POST['idp'], "text"),
					   GetSQLValueString($_POST['annee_debut'], "int"),
					   GetSQLValueString($_POST['annee_fin'], "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = $lien;
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $lien));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id'];
	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."phase set code_phase=%s, structure=%s, projet=%s, annee_debut=%s, annee_fin=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_phase='$c'",
					   GetSQLValueString($_POST['code_phase'], "text"),
                       GetSQLValueString($_SESSION["clp_structure"], "text"),
					   GetSQLValueString($_POST['idp'], "text"),
					   GetSQLValueString($_POST['annee_debut'], "int"),
					   GetSQLValueString($_POST['annee_fin'], "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = $lien;
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $lien));
}



if(isset($_GET["cl"])){
 $insertGoTo = $path."projets.php";
 ?>
  <script type="text/javascript">

  parent.location.href = "<?php echo $insertGoTo; ?>";

  </script>
  <?php exit(0);
}


if(isset($_GET["id"])) { $id=$_GET["id"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_gv = "SELECT * FROM ".$database_connect_prefix."projet, ".$database_connect_prefix."phase WHERE projet=id_projet and id_phase='$id'";
$edit_gv = mysql_query($query_edit_gv, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_gv = mysql_fetch_assoc($edit_gv);
$totalRows_edit_gv = mysql_num_rows($edit_gv);
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_phase = "SELECT * FROM ".$database_connect_prefix."projet, ".$database_connect_prefix."phase WHERE projet=id_projet and projet='$projet'";
$liste_phase = mysql_query($query_liste_phase, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_phase = mysql_fetch_assoc($liste_phase);
$totalRows_liste_phase = mysql_num_rows($liste_phase);

if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sup_tache = "DELETE FROM ".$database_connect_prefix."phase WHERE id_phase='$id'";
$Result = mysql_query($query_sup_tache, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
header(sprintf("Location: %s", $lien));
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title><?php echo $config->site_name; ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="<?php echo $path; ?>css/cbcscbindex.css" type="text/css" >
<link rel="stylesheet" href="<?php echo $path; ?>css/css.css" type="text/css" >
<link href="<?php echo $path; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $path.$config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo $path; ?>script/jquery-latest.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $path; ?>script/function.php"></script>
<script type="text/javascript" src="<?php echo $path; ?>script/iepngfix_tilebg.js"></script>
  <style type="text/css">
<!--
.Style1 {	font-size: 12px;
	font-weight: bold;
}
.Style2 {font-size: 12px}
.Style9 {font-size: 12}
.titrecorps2 {
  font-weight: bold;
}

-->
  </style>
</head>

<body style="height: auto;">
  <div id="corps">

<!--<div id="msg" align="center" class="red"></div>-->

<div id="special">
  <div id="msg1" align="center" class="red"></div>
<div align="center">
<?php if(!isset($_GET["add_phase"])) { ?>
        <div align="center">
          <table border="0" width="80%" cellspacing="1" class="table table-striped table-bordered table-hover table-responsive dataTable">
            <?php $t=0;  if($totalRows_liste_phase>0) { ?>
            <tr class="titrecorps2">
              <td>Réf. phase</td>
              <td>Période</td>
               <td width="80" align="center">Actions</td>
              </tr>
            <?php $p1="j"; $sp=0; $i=0;do { $id = $row_liste_phase['id_phase']; ?>
                <tr>
                <td ><u><span class="Style12"><?php echo $row_liste_phase['code_phase']; $sp=$sp+$row_liste_phase['code_phase']; ?></span></u></td>
                <form name="form1" id="form1" method="post" action="">
<?php $ts=0; $mois_cum = "";  ?>
                <td align="center" width="120"><?php echo $row_liste_phase['annee_debut']." - ".$row_liste_phase['annee_fin'];  ?>&nbsp;</td>
                <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<3) {?>

                 <td align="center" width="50">
<?php
echo do_link("","$lien&id=$id&add_phase=0","Modifier Phase ","","edit","../","","",0,"margin:0px 5px;",$nfile);

echo do_link("","$lien&id_sup=$id","Supprimer","","del","../","","return confirm('Voulez-vous vraiment supprimer cette phase ?');",0,"margin:0px 5px;",$nfile);
?>
                </td>

                   </form>
                <?php } ?>
                </tr>
              <?php } while ($row_liste_phase = mysql_fetch_assoc($liste_phase)); ?>
            <?php } else echo "<h3>Aucune phase</h3>" ;?>
            </table>
          </div>
<div align="center"><br /><a class="btn btn-warnig pull-right" title="Ajouter une tâche" href="<?php echo $lien;  ?>&add_phase=0"><b>Ajouter une phase</b></a>&nbsp;&nbsp;<a class="btn btn-danger pull-left" href="<?php echo $page; ?>?cl">Quitter</a></div>
<?php } ?>

<?php if(isset($_GET["add_phase"])) { ?>
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification phase":"Nouvelle phase"?></h4> </div>
<div class="widget-content">
<form action="<?php echo $lien1; ?>" method="post" name="form2" id="form2" class="form-horizontal row-border" enctype="multipart/form-data" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="code_phase" class="col-md-3 control-label">Réf. phase <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="code_phase" id="code_phase" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_edit_gv['code_phase']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="annee_debut" class="col-md-3 control-label">Année début <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="annee_debut" id="annee_debut" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_edit_gv['annee_debut']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top" colspan="2">
      <td colspan="2">
        <div class="form-group">
          <label for="annee_fin" class="col-md-3 control-label">Année fin </label>
          <div class="col-md-9">
            <input class="form-control " type="text" name="annee_fin" id="annee_fin" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_edit_gv['annee_fin']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET['idp'])) {  ?><input type="hidden" name="idp" id="idp" value="<?php if(isset($_GET['idp'])) echo $_GET['idp'];  ?>" /><?php }  ?>
<?php if(isset($_GET['id'])) {  ?><input type="hidden" name="id" id="id" value="<?php if(isset($_GET['id'])) echo $_GET['id'];  ?>" /><?php }  ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
<a class="btn btn-warnig pull-right" href="<?php echo $lien1; ?>">Annuler</a>
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="form2" size="32" alt="">
</div>
</form>

</div>  </div>
     <?php } ?>
</div>

  </div>
</div>

</body>

</html>