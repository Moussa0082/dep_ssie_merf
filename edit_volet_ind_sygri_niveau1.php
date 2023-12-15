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



if(isset($_GET["id_v"])) { $idv=$_GET["id_v"]; 

mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_edit_cppro = "SELECT volet FROM volet_sygri where id_volet='$idv'";

$edit_cppro = mysql_query($query_edit_cppro, $pdar_connexion) or die(mysql_error());

$row_edit_cppro = mysql_fetch_assoc($edit_cppro);

$totalRows_edit_cppro = mysql_num_rows($edit_cppro);

}





//insertion os

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];



  $insertSQL = sprintf("INSERT INTO  indicateur_sygri1_projet (volet, indicateur_sygri_niveau1, cible_projet, cible_rmp, unite, ordre, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s,'$personnel', '$date')",

					   GetSQLValueString($idv, "int"),
					   GetSQLValueString($_POST['indicateur_sygri_niveau1'], "text"),
					   GetSQLValueString($_POST['cible_projet'], "double"),
   					   GetSQLValueString($_POST['cible_rmp'], "double"),
					   GetSQLValueString($_POST['unite'], "text"),
					   GetSQLValueString($_POST['ordre'], "int"));

					   

  mysql_select_db($database_pdar_connexion, $pdar_connexion);

  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());

  $insertGoTo = "../sygri_niveau1_projet.php";

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

  $insertSQL = sprintf("UPDATE indicateur_sygri1_projet SET indicateur_sygri_niveau1=%s, cible_projet=%s, cible_rmp=%s, unite=%s, ordre=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur_sygri_niveau1_projet='$c'",

					   GetSQLValueString($_POST['indicateur_sygri_niveau1'], "text"),
					   GetSQLValueString($_POST['cible_projet'], "double"),
   					   GetSQLValueString($_POST['cible_rmp'], "double"),
					   GetSQLValueString($_POST['unite'], "text"),
					   GetSQLValueString($_POST['ordre'], "int"));



  mysql_select_db($database_pdar_connexion, $pdar_connexion);

  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());

  $insertGoTo = "../sygri_niveau1_projet.php";

  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";

    ?>

  <script type="text/javascript">



  parent.location.href = "<?php echo $insertGoTo; ?>";



  </script>

  <?php exit(0);

}





if(isset($_GET["cl"])){ 

 $insertGoTo = "../sygri_niveau1_projet.php";

 ?>

  <script type="text/javascript">



  parent.location.href = "<?php echo $insertGoTo; ?>";



  </script>

  <?php exit(0);

}

if(isset($_GET["iden"])) { $idi=$_GET["iden"];

mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_edit_data = "SELECT * FROM indicateur_sygri1_projet where id_indicateur_sygri_niveau1_projet='$idi'";

$edit_data = mysql_query($query_edit_data, $pdar_connexion) or die(mysql_error());

$row_edit_data = mysql_fetch_assoc($edit_data);

$totalRows_edit_data = mysql_num_rows($edit_data);

} else

{

$idi=0;

}



/*mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_indicateur = "SELECT id_indicateur_sygri_fida, intitule_indicateur_sygri_fida, unite FROM indicateur_sygri_fida where niveau_sygri=1

 and id_indicateur_sygri_fida not in (select indicateur_sygri_niveau1 from indicateur_sygri1_projet where id_indicateur_sygri_niveau1_projet!='$idi' )order by ordre";

$liste_indicateur  = mysql_query($query_liste_indicateur , $pdar_connexion) or die(mysql_error());

$row_liste_indicateur = mysql_fetch_assoc($liste_indicateur);

$totalRows_liste_indicateur  = mysql_num_rows($liste_indicateur);*/





	  
mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_unite = "SELECT unite FROM unite_indicateur";

$liste_unite = mysql_query($query_liste_unite, $pdar_connexion) or die(mysql_error());

$row_liste_unite = mysql_fetch_assoc($liste_unite);

$totalRows_liste_unite = mysql_num_rows($liste_unite);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"

    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">



<head>

  <title>scp</title>

<link rel="stylesheet" href="../css/cbcscbindex.css" type="text/css" >

<link rel="stylesheet" href="../css/css.css" type="text/css" >

<script type="text/javascript" src="../script/function.php"></script>

<script src="../script/jquery-latest.js" type="text/javascript"></script>

<script type="text/javascript" src="../script/iepngfix_tilebg.js"></script>

<style type="text/css">

<!--

.Style1 {

	color: #CC0000;

	font-weight: bold;

}

-->

</style>

</head>



<body>

  <div id="corps">



<div id="msg" align="center" class="red"></div>



<div id="special" class="special_center">

  <p align="center" <?php if(isset($_GET['iden'])) echo "style='color:yellow;'"; ?> ><?php if(isset($_GET['iden'])) echo "Modification"; else echo "Nouveau indicateur SYGRI 1er Niveau" ; ?> </p>

  <div id="msg1" align="center" class="red"></div>

  <form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3" onSubmit="return verifform(this,1);">

    <table border="0" align="center" cellspacing="0" cellpadding="3" width="100%">

      <tr valign="baseline">

        <td align="right" valign="middle"><strong>

          <label for="id_composante">Volet: </label>

        </strong></td>

        <td valign="top"><div align="left" class="Style1">

           <?php if(isset($_GET['id_v'])) echo $row_edit_cppro['volet'];?>

        </div></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle"><label for="intitule_sous_composante">Lib&eacute;ll&eacute; de l'indicateur </label></td>
        <td valign="top"><div align="left">
            <textarea name="indicateur_sygri_niveau1" cols="30" id="indicateur_sygri_niveau1" alt="*,"><?php if(isset($_GET['iden'])) echo $row_edit_data['indicateur_sygri_niveau1'];?>
        </textarea>
        </div></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle">Unit&eacute;</td>
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
        <td align="right" valign="middle"><label for="id_composante">Valeurs cibles </label></td>
        <td valign="top"><div align="left"> RPE:
          <input name="cible_projet" type="text" id="cible_projet"  value="<?php if(isset($_GET['iden'])) echo $row_edit_data['cible_projet'];?>" size="10" alt="*," />
          RMP:
          <input name="cible_rmp" type="text" id="cible_rmp"  value="<?php if(isset($_GET['iden'])) echo $row_edit_data['cible_rmp'];?>" size="10" alt="*," />
        </div></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle">N&deg;</td>
        <td valign="top"><input name="ordre" type="text" id="ordre" value="<?php if(isset($_GET['iden'])) echo $row_edit_data['ordre']; ?>" size="10" alt="*," />
            <i>(Pour le tri)</i></td>
      </tr>



      <tr valign="baseline">

        <td colspan="2" align="right" nowrap="nowrap"><div align="center">

            <input type="submit" value="<?php if(isset($_GET['iden'])) echo "Modifier"; else echo "Ajouter" ; ?>"  class="inputsubmit" />

&nbsp;<a title="Annuler la modification" href="">
<input name="Submit" type="reset" class="inputsubmit" value="Annuler" onclick="parent.tb_remove();" />
</a>&nbsp; </div></td>
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