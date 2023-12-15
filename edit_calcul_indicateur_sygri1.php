<?php
session_start();
require_once('../Connections/pdar_connexion.php');
//include_once $path_racine."configurations.php";
//$config = new MSIConfig();
if(isset($_GET['id_v'])) $id_v=$_GET['id_v']; else $id_v=0; //else $id_ind=0; if(isset($_GET['ad_ind'])) $ex_ind=$_GET['ad_ind']; else $ex_ind=0;
if(isset($_GET['iden'])) $idsy=$_GET['iden']; else $idsy=0;
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING']))
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$page = $_SERVER['PHP_SELF'];


//insertion indicateur
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sup_ind = "DELETE FROM calcul_indicateur_simple_sygri1 WHERE indicateur_sygri1='$idsy'";
$Result1 = mysql_query($query_sup_ind, $pdar_connexion) or die(mysql_error());

$indicateur_simple="";
if(!empty($_POST['indicateur_simple'])) { foreach($_POST['indicateur_simple'] as $vindicateur_simple) { $indicateur_simple=$indicateur_simple.",".$vindicateur_simple; } }

  $insertSQL = sprintf("INSERT INTO calcul_indicateur_simple_sygri1 (indicateur_sygri1, formule_indicateur_simple, indicateur_simple, id_personnel, date_enregistrement) VALUES (%s, %s, %s,'$personnel', '$date')",
                       GetSQLValueString($idsy, "int"),
					   GetSQLValueString($_POST['formule_indicateur_simple'], "text"),
					   GetSQLValueString($indicateur_simple, "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = "../sygri_niveau1_projet.php";

  if ($Result1) $insertGoTo .= "?insert=ok";  else $insertGoTo .= "?insert=no";
    ?>
  <script type="text/javascript">
  parent.location.href = "<?php echo $insertGoTo; ?>";
  </script>
  <?php exit(0);
}
/*
//update indicateur
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form3")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_GET['iden'];
$indicateur_simple="";
if(!empty($_POST['indicateur_simple'])) { foreach($_POST['indicateur_simple'] as $vindicateur_simple) { $indicateur_simple=$indicateur_simple.",".$vindicateur_simple; } }
  $insertSQL = sprintf("UPDATE calcul_indicateur_simple_cmr SET formule_indicateur_simple=%s, indicateur_simple=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_calcul='$c'",
					   GetSQLValueString($_POST['formule_indicateur_simple'], "text"),
					   GetSQLValueString($indicateur_simple, "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $insertGoTo = "../indicateur_realisation.php";
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or  $insertGoTo .= "?update=no";
  if ($Result1) $insertGoTo .= "?update=ok"; //else
    ?>
  <script type="text/javascript">

  parent.location.href = "<?php echo $insertGoTo; ?>";

  </script>
  <?php exit(0);
}



if(isset($_GET["cl"])){
 $insertGoTo = "../indicateur_realisation.php";
 ?>
  <script type="text/javascript">

  parent.location.href = "<?php echo $insertGoTo; ?>";

  </script>
  <?php exit(0);
}*/
if(isset($_GET["iden"])) { $idsy=$_GET["iden"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_data = "SELECT * FROM calcul_indicateur_simple_sygri1 where indicateur_sygri1='$idsy'";
$edit_data = mysql_query($query_edit_data, $pdar_connexion) or die(mysql_error());
$row_edit_data = mysql_fetch_assoc($edit_data);
$totalRows_edit_data = mysql_num_rows($edit_data);

if(isset($row_edit_data['indicateur_simple'])) $ais = explode(",", $row_edit_data['indicateur_simple']); else $ais=array();

}

mysql_select_db($database_pdar_connexion, $pdar_connexion);

/*$query_liste_indicateur = "SELECT * FROM indicateur_resultat_cmr, indicateur_resultat where id_indicateur_resultat=indicateur_res and resultat='$rres' order by indicateur_resultat.code_ires, groupe_indicateur, code_cmr";*/
$query_liste_indicateur = "SELECT id_indicateur_sygri_niveau1_projet	, cible_projet, intitule_indicateur_sygri_fida, indicateur_sygri_fida.unite, type FROM indicateur_sygri1_projet, indicateur_sygri_fida where  indicateur_sygri_niveau1=id_indicateur_sygri_fida and volet='$id_v' and niveau_sygri=1 and type='unique' order by ordre";
$liste_indicateur  = mysql_query($query_liste_indicateur , $pdar_connexion) or die(mysql_error());
$row_liste_indicateur = mysql_fetch_assoc($liste_indicateur );
$totalRows_liste_indicateur = mysql_num_rows($liste_indicateur );

mysql_select_db($database_pdar_connexion, $pdar_connexion);
//$query_edit_ic = "SELECT * FROM indicateur_resultat_cmr where id_indicateur='$idicmr'";
$query_edit_ic = "SELECT id_indicateur_sygri_niveau1_projet	, cible_projet, intitule_indicateur_sygri_fida, indicateur_sygri_fida.unite, type FROM indicateur_sygri1_projet, indicateur_sygri_fida where  indicateur_sygri_niveau1=id_indicateur_sygri_fida and volet='$id_v' and niveau_sygri=1 and id_indicateur_sygri_niveau1_projet='$idsy'";
$edit_ic = mysql_query($query_edit_ic, $pdar_connexion) or die(mysql_error());
$row_edit_ic = mysql_fetch_assoc($edit_ic);
$totalRows_edit_ic = mysql_num_rows($edit_ic);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Indicateurs PSR calcul&eacute;s</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="../css/cbcscbindex.css" type="text/css" >
<link rel="stylesheet" href="../css/css.css" type="text/css" >
<script type="text/javascript" src="../script/function.php"></script>
<script src="../script/jquery-latest.js" type="text/javascript"></script>
<script type="text/javascript" src="../script/iepngfix_tilebg.js"></script>
  <style type="text/css">
<!--
.Style1 {
	color: #FF0000;
	font-weight: bold;
}
-->
  </style>
</head>

<body>
  <div id="corps">

<div id="msg" align="center" class="red"></div>

<div id="special" class="special_center">
  <p align="center" <?php if(isset($_GET['iden'])) echo "style='color:yellow;'"; ?> ><?php if(isset($_GET['iden'])) echo "Modification d'indicateur  SYGRI calculé"; else echo "Nouveau indicateur SYGRI calculé " ; ?> </p>       
  <div id="msg1" align="center" class="red"></div>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3" onSubmit="return verif_form_simple(this,'msg1');">
    <table border="0" align="center" cellspacing="0" cellpadding="5" width="80%">
      <tr valign="baseline">
        <td align="right" valign="middle"><label for="intitule_indicateur_calcule">Indicateur calcul&eacute; </label></td>
        <td valign="top"><div align="left"><?php echo $row_edit_ic['intitule_indicateur_sygri_fida'];?></div></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle">Formule de calcul</td>
        <td valign="top"><select name="formule_indicateur_simple">
          <option value="Somme" <?php if(isset($_GET['iden']) && $row_edit_data['formule_indicateur_simple']=="Somme") echo 'selected="selected"'; ?>>Somme</option>
          <option value="Moyenne" <?php if(isset($_GET['iden']) && $row_edit_data['formule_indicateur_simple']=="Moyenne") echo 'selected="selected"'; ?>>Moyenne</option>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle">Liste des indicateurs simples </td>
        <td valign="top"><select name="indicateur_simple[]" multiple="multiple" size="6">
		          <option value=" "<?php if(isset($_GET['iden'])) {if(in_array(" ", $ais, TRUE)) {echo "SELECTED";} } ?>>-- Aucun --</option>

          <?php if($totalRows_liste_indicateur>0) { ?>
          <?php
							do {  
							?>
          <option value="<?php echo $row_liste_indicateur['id_indicateur_sygri_niveau1_projet']?>"<?php if(isset($_GET['iden'])) {if(in_array($row_liste_indicateur['id_indicateur_sygri_niveau1_projet'], $ais, TRUE)) {echo "SELECTED";} } ?>><?php echo substr($row_liste_indicateur['intitule_indicateur_sygri_fida'],0, 80)?></option>
          <?php
						} while ($row_liste_indicateur = mysql_fetch_assoc($liste_indicateur));} ?>
        </select></td>
      </tr>

      <tr valign="baseline">
        <td colspan="2" align="right" nowrap="nowrap"><div align="center">
            <input type="submit" value="<?php if(isset($_GET['iden'])) echo "Valider"; else echo "Ajouter" ; ?>"  class="inputsubmit" />
&nbsp;
<input name="Submit" type="reset" class="inputsubmit" value="Quitter" onClick="parent.tb_remove();" />
</div></td>
      </tr>
    </table>
    <input type="hidden" name="<?php echo "MM_insert";  ?>" value="form3" />
  </form>
</div>

  </div>
  </br>
<?php if(isset($_GET['insert'])){ ?>
<script type="text/javascript">
afficher_msg('insert','<?php echo $_GET['insert']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['update'])){ ?>
<script type="text/javascript">
afficher_msg('update','<?php echo $_GET['update']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['del'])){ ?>
<script type="text/javascript">
afficher_msg('del','<?php echo $_GET['del']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['statut'])){ ?>
<script type="text/javascript">
afficher_msg('statut','<?php echo $_GET['statut']; ?>');
</script>
<?php }?>
</body>

</html>