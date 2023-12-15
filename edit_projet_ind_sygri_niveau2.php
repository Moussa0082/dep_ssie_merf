<?php
session_start();
require_once('../Connections/pdar_connexion.php');
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

//include_once $path_racine."configurations.php";
//$config = new MSIConfig();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING']))
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$page = $_SERVER['PHP_SELF'];

if(isset($_GET["id_cp"])) { $idcp=$_GET["id_cp"]; 
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_cppro = "SELECT code_composante FROM composante where id_composante='$idcp'";
$edit_cppro = mysql_query($query_edit_cppro, $pdar_connexion) or die(mysql_error());
$row_edit_cppro = mysql_fetch_assoc($edit_cppro);
$totalRows_edit_cppro = mysql_num_rows($edit_cppro);
}


//insertion os
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
//$arr = explode("_", $_POST['indicateur_cl']);
  $insertSQL = sprintf("INSERT INTO  indicateur_sygri2_projet (composante, intitule_indicateur_sygri2, unite, id_personnel, date_enregistrement) VALUES (%s, %s, %s,'$personnel', '$date')",
					   GetSQLValueString($_POST['scp'], "text"),
					   GetSQLValueString($_POST['intitule_indicateur_sygri2'], "text"),
					   GetSQLValueString($_POST['unite'], "text"));
					   
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = "../sygri_niveau2_projet.php";
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
  $insertSQL = sprintf("UPDATE indicateur_sygri2_projet SET composante=%s, intitule_indicateur_sygri2=%s, unite=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur_sygri_niveau2_projet='$c'",
					  
					   GetSQLValueString($_POST['scp'], "text"),
					   GetSQLValueString($_POST['intitule_indicateur_sygri2'], "text"),
					   GetSQLValueString($_POST['unite'], "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = "../sygri_niveau2_projet.php";
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    ?>
  <script type="text/javascript">

  parent.location.href = "<?php echo $insertGoTo; ?>";

  </script>
  <?php exit(0);
}

if(isset($_GET["cl"])){ 
 $insertGoTo = "../sygri_niveau2_projet.php";
 ?>
  <script type="text/javascript">

  parent.location.href = "<?php echo $insertGoTo; ?>";

  </script>
  <?php exit(0);
}


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_scp = "SELECT id_composante, code_composante, intitule_composante from composante order by code_composante ";
$liste_scp  = mysql_query($query_liste_scp , $pdar_connexion) or die(mysql_error());
$row_liste_scp = mysql_fetch_assoc($liste_scp);
$totalRows_liste_scp  = mysql_num_rows($liste_scp);	

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_cl = "SELECT id_indicateur_resultat as id_indicateur, intitule_indicateur_resultat as intitule_indicateur, 'resultat' as niveau_cl, unite FROM indicateur_resultat, resultat where id_resultat=resultat and composante='$idcp' and niveau_sygri=1 and id_indicateur_resultat not in (select indicateur_cl from indicateur_sygri2_projet  where niveau_cl='resultat')
union SELECT id_indicateur_objectif_specifique as id_indicateur, intitule_indicateur_objectif_specifique as intitule_indicateur, 'objectif specifique' as niveau_cl, unite FROM indicateur_objectif_specifique where niveau_sygri=1 and id_indicateur_objectif_specifique not in (select indicateur_cl from indicateur_sygri2_projet where niveau_cl='objectif specifique')";
$liste_ind_cl  = mysql_query($query_liste_ind_cl , $pdar_connexion) or die(mysql_error());
$row_liste_ind_cl  = mysql_fetch_assoc($liste_ind_cl);
$totalRows_liste_ind_cl  = mysql_num_rows($liste_ind_cl);	*/		  

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_unite = "SELECT unite FROM unite_indicateur";
$liste_unite = mysql_query($query_liste_unite, $pdar_connexion) or die(mysql_error());
$row_liste_unite = mysql_fetch_assoc($liste_unite);
$totalRows_liste_unite = mysql_num_rows($liste_unite);

if(isset($_GET["iden"])) { $idi=$_GET["iden"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_data = "SELECT * FROM indicateur_sygri2_projet where id_indicateur_sygri_niveau2_projet='$idi'";
$edit_data = mysql_query($query_edit_data, $pdar_connexion) or die(mysql_error());
$row_edit_data = mysql_fetch_assoc($edit_data);
$totalRows_edit_data = mysql_num_rows($edit_data);
} else
{
$idi=0;
}

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_indicateur = "SELECT id_indicateur_sygri_fida, intitule_indicateur_sygri_fida, unite FROM indicateur_sygri_fida where id_indicateur_sygri_fida not in (select indicateur_sygri_niveau2 from indicateur_sygri2_projet where id_indicateur_sygri_niveau2_projet!=$idi) and niveau_sygri=2 order by ordre";
$liste_indicateur  = mysql_query($query_liste_indicateur , $pdar_connexion) or die(mysql_error());
$row_liste_indicateur = mysql_fetch_assoc($liste_indicateur);
$totalRows_liste_indicateur  = mysql_num_rows($liste_indicateur);*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>scp</title>
<link rel="stylesheet" href="../css/cbcscbindex.css" type="text/css" >
<link rel="stylesheet" href="../css/css.css" type="text/css" >
<script type="text/javascript" src="../script/function.js"></script>
<script type="text/javascript" src="../script/iepngfix_tilebg.js"></script>
<script src="../script/jquery-latest.js" type="text/javascript"></script>
  <style type="text/css">
<!--
.Style8 {font-size: 12px; font-weight: bold; }
-->
  </style>
</head>

<body>
  <div id="corps">

<div id="msg" align="center" class="red"></div>

<div id="special" class="special_center">
  <p align="center" <?php if(isset($_GET['iden'])) echo "style='color:yellow;'"; ?> ><?php if(isset($_GET['iden'])) echo "Modification"; else echo "Nouveau indicateur SYGRI 2ème Niveau" ; ?> </p>
  <div id="msg1" align="center" class="red"></div>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3" onSubmit="return verifform(this,1);">
    <table border="0" align="center" cellspacing="0" cellpadding="5">
      <tr valign="baseline">
        <td align="right" valign="middle"><span class="Style8">Composante</span></td>
        <td valign="top"><div align="left"><strong>
          <select name="scp" style="border-color:#FF0000 ">
            <?php if($totalRows_liste_scp>0) { ?>
            <option value="">-- Choisissez --</option>
            <?php
				do {  
				?>
            <option value="<?php echo $row_liste_scp['id_composante'];?>" <?php if(isset($_GET['iden'])) {if (!(strcmp($row_edit_data['composante'], $row_liste_scp['id_composante']))) {echo "SELECTED";} } ?>><?php echo $row_liste_scp['code_composante'].": ".$row_liste_scp['intitule_composante'];?></option>
            <?php
				} while ($row_liste_scp = mysql_fetch_assoc($liste_scp));} 
				else {
				 echo '<optgroup label="Aucune scp disponible"></optgroup>'; } ?>
          </select>
          </strong></div></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle"><span class="Style8">
          <label for="intitule_indicateur_sygri2">Intitul&eacute;</label>
        </span></td>
        <td valign="top"><div align="left">
            <textarea name="intitule_indicateur_sygri2" cols="30" id="intitule_indicateur_sygri2" alt="*,"><?php if(isset($_GET['iden'])) echo $row_edit_data['intitule_indicateur_sygri2'];?></textarea>
        </div></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" class="Style8">Unit&eacute;</td>
        <td valign="top"><select name="unite">
            <?php if($totalRows_liste_unite>0) { ?>
            <option value=""<?php if(isset($_GET['iden'])){if (!(strcmp("", $row_edit_data['unite']))) {echo "SELECTED";}} ?>>-- Choisissez --</option>
            <?php
									do {?>
            <option value="<?php echo $row_liste_unite['unite']?>"<?php if(isset($_GET['iden'])) {if (!(strcmp($row_liste_unite['unite'], $row_edit_data['unite']))) {echo "SELECTED";} } ?>><?php echo $row_liste_unite['unite'];?></option>
            <?php
									} while ($row_liste_unite = mysql_fetch_assoc($liste_unite));}
									else {
									echo '<optgroup label="Aucune unit&eacute; disponible"></optgroup>'; } ?>
        </select></td>
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