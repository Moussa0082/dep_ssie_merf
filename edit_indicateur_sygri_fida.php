<?php
session_start();
require_once('../Connections/pdar_connexion.php');//include_once $path_racine."configurations.php";
//include_once $path_racine."configurations.php";
//$config = new MSIConfig();
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
  $insertSQL = sprintf("INSERT INTO indicateur_sygri_fida (intitule_indicateur_sygri_fida, unite, niveau_sygri, groupe_indicateur, description, ordre, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s,'$personnel', '$date')",
                       GetSQLValueString($_POST['intitule_indicateur_sygri_fida'], "text"),
					   GetSQLValueString($_POST['unite'], "text"),
					   GetSQLValueString(3, "int"),
					    GetSQLValueString($_POST['groupe_indicateur'], "int"),
					   GetSQLValueString($_POST['description'], "text"),
					   GetSQLValueString($_POST['ordre'], "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = "../indicateur_sygri_fida.php";
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    ?>
  <script type="text/javascript">
  parent.location.href = "<?php echo $insertGoTo; ?>";
  </script>
  <?php exit(0);
}

//update indicateur
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form3")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_GET['iden'];
  $insertSQL = sprintf("UPDATE indicateur_sygri_fida SET intitule_indicateur_sygri_fida=%s, unite=%s, groupe_indicateur=%s, description=%s, ordre=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur_sygri_fida='$c'",
                       GetSQLValueString($_POST['intitule_indicateur_sygri_fida'], "text"),                                                 
					   GetSQLValueString($_POST['unite'], "text"),
					    GetSQLValueString($_POST['groupe_indicateur'], "int"),
					   GetSQLValueString($_POST['description'], "text"),
					   GetSQLValueString($_POST['ordre'], "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = "../indicateur_sygri_fida.php";
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    ?>
  <script type="text/javascript">

  parent.location.href = "<?php echo $insertGoTo; ?>";

  </script>
  <?php exit(0);
}



if(isset($_GET["cl"])){
 $insertGoTo = "../indicateur_sygri_fida.php";
 ?>
  <script type="text/javascript">

  parent.location.href = "<?php echo $insertGoTo; ?>";

  </script>
  <?php exit(0);
}
if(isset($_GET["iden"])) { $id=$_GET["iden"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_data = "SELECT * FROM indicateur_sygri_fida where id_indicateur_sygri_fida='$id'";
$edit_data = mysql_query($query_edit_data, $pdar_connexion) or die(mysql_error());
$row_edit_data = mysql_fetch_assoc($edit_data);
$totalRows_edit_data = mysql_num_rows($edit_data);
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_unite = "SELECT * FROM unite_indicateur order by unite";
$liste_unite  = mysql_query($query_liste_unite , $pdar_connexion) or die(mysql_error());
$row_liste_unite = mysql_fetch_assoc($liste_unite );
$totalRows_liste_unite = mysql_num_rows($liste_unite );

mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_scp = "SELECT id_groupe, nom_groupe from groupe_indicateur order by code_groupe";

$liste_scp  = mysql_query($query_liste_scp , $pdar_connexion) or die(mysql_error());

$row_liste_scp = mysql_fetch_assoc($liste_scp);

$totalRows_liste_scp  = mysql_num_rows($liste_scp);	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>IOG</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="../css/cbcscbindex.css" type="text/css" >
<link rel="stylesheet" href="../css/css.css" type="text/css" >
<script type="text/javascript" src="../script/function.js"></script>
<script type="text/javascript" src="../script/iepngfix_tilebg.js"></script>
<script src="../script/jquery-latest.js" type="text/javascript"></script>
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

<div id="special">
  <p align="center" <?php if(isset($_GET['iden'])) echo "style='color:yellow;'"; ?> ><?php if(isset($_GET['iden'])) echo "Modification d'indicateur SYGRI FIDA"; else echo "Nouveau indicateur SYGRI FIDA" ; ?> </p>
  <div id="msg1" align="center" class="red"></div>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3" onSubmit="return verifform(this,1);">
    <table border="0" align="center" cellspacing="0" cellpadding="5">
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap"><label for="intitule_indicateur_objectif_global">Indicateur</label></td>
        <td valign="top"><div align="left"><textarea name="intitule_indicateur_sygri_fida" cols="40" id="intitule_indicateur_sygri_fida" alt="*,"><?php if(isset($_GET['iden'])) echo $row_edit_data['intitule_indicateur_sygri_fida'];?></textarea></div></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap"><label for="unite">Groupe</label></td>
        <td valign="top"><div align="left"><strong>
          <select name="groupe_indicateur" style="border-color:#FF0000 ">
            <?php if($totalRows_liste_scp>0) { ?>
            <option value="">-- Choisissez --</option>
            <?php

				do {  

				?>
            <option value="<?php echo $row_liste_scp['id_groupe'];?>"<?php if(isset($_GET['iden'])) {if (!(strcmp($row_edit_data['groupe_indicateur'], $row_liste_scp['id_groupe']))) {echo "SELECTED";} } ?>><?php echo $row_liste_scp['nom_groupe'];?></option>
            <?php

				} while ($row_liste_scp = mysql_fetch_assoc($liste_scp));} 

				else {

				 echo '<optgroup label="Aucun groupe disponible"></optgroup>'; } ?>
          </select>
        </strong></div></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap"><label for="unite">Unit&eacute;</label></td>
        <td valign="top"><div align="left"><strong>
          <select name="unite" style="border-color:#FF0000 ">
            <?php if($totalRows_liste_unite>0) { ?>
            <option value="">-- Choisissez --</option>
            <?php
				do {  
				?>
            <option value="<?php echo $row_liste_unite['unite'];?>"<?php if(isset($_GET['iden'])) {if (!(strcmp($row_edit_data['unite'], $row_liste_unite['unite']))) {echo "SELECTED";} } ?>><?php echo $row_liste_unite['unite'];?></option>
            <?php
				} while ($row_liste_unite = mysql_fetch_assoc($liste_unite));} 
				else {
				 echo '<optgroup label="Aucune unit&eacute; disponible"></optgroup>'; } ?>
          </select>
        </strong></div></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">Description</td>
        <td valign="top"><textarea name="description" cols="40" id="description" alt="*,"><?php if(isset($_GET['iden'])) echo $row_edit_data['description'];?></textarea></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" nowrap="nowrap">Ordre d'affichage </td>
        <td valign="top"><input name="ordre" type="text" id="ordre" value="<?php if(isset($_GET['iden'])) echo $row_edit_data['ordre'];?>" size="10" alt="*," /></td>
      </tr>
      <tr valign="baseline">
        <td colspan="2" align="right" nowrap="nowrap"><div align="center">
            <input type="submit" value="<?php if(isset($_GET['iden'])) echo "Modifier"; else echo "Ajouter" ; ?>"  class="inputsubmit" />
&nbsp;&nbsp;<a class="button" href="<?php echo $page; ?>?cl">Quitter</a> </div></td>
      </tr>
    </table>
    <input type="hidden" name="<?php if(isset($_GET['iden'])) echo "MM_update"; else echo "MM_insert";  ?>" value="form3" />
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