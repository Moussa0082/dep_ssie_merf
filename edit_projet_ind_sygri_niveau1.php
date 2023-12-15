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

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_edit_cppro = "SELECT id_composante FROM composante where id_composante='$idcp'";

$edit_cppro = mysql_query($query_edit_cppro, $pdar_connexion) or die(mysql_error());

$row_edit_cppro = mysql_fetch_assoc($edit_cppro);

$totalRows_edit_cppro = mysql_num_rows($edit_cppro);*/

}

//sous composante
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_sous_composante = "SELECT * FROM composante ORDER BY code_composante";
$liste_sous_composante = mysql_query($query_liste_sous_composante, $pdar_connexion) or die(mysql_error());
$row_liste_sous_composante = mysql_fetch_assoc($liste_sous_composante);
$totalRows_liste_sous_composante = mysql_num_rows($liste_sous_composante);




//insertion os

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];



  $insertSQL = sprintf("INSERT INTO  indicateur_sygri1_projet (indicateur_sygri_niveau1, composante, groupe_indicateur, cible_projet, cible_rmp, unite, beneficiaire, type_suivi, ordre, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s,  %s,'$personnel', '$date')",

                       GetSQLValueString($_POST['indicateur_sygri_niveau1'], "text"),

					   GetSQLValueString($_POST['composante'], "int"),
					    GetSQLValueString($_POST['groupe_indicateur'], "int"),

					   GetSQLValueString($_POST['cible_projet'], "double"),
					   
					   GetSQLValueString($_POST['cible_rmp'], "double"),
					   GetSQLValueString($_POST['unite'], "text"),
					    GetSQLValueString($_POST['beneficiaire'], "int"),
						 GetSQLValueString($_POST['type_suivi'], "int"),
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

  $insertSQL = sprintf("UPDATE indicateur_sygri1_projet SET indicateur_sygri_niveau1=%s, composante=%s, groupe_indicateur=%s,  cible_projet=%s, cible_rmp=%s, unite=%s, beneficiaire=%s, type_suivi=%s, ordre=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur_sygri_niveau1_projet='$c'",

					   GetSQLValueString($_POST['indicateur_sygri_niveau1'], "text"),
					   GetSQLValueString($_POST['composante'], "int"),
					   GetSQLValueString($_POST['groupe_indicateur'], "int"),
					   GetSQLValueString($_POST['cible_projet'], "double"),
   					   GetSQLValueString($_POST['cible_rmp'], "double"),
					   GetSQLValueString($_POST['unite'], "text"),
					    GetSQLValueString($_POST['beneficiaire'], "int"),
 				       GetSQLValueString($_POST['type_suivi'], "int"),
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

$query_liste_indicateur = "SELECT id_indicateur_sygri_fida, intitule_indicateur_sygri_fida, unite FROM indicateur_sygri_fida where id_indicateur_sygri_fida not in (select indicateur_sygri_niveau1 from indicateur_sygri1_projet where id_indicateur_sygri_niveau1_projet!='$idi') and niveau_sygri=1 order by ordre";

$liste_indicateur  = mysql_query($query_liste_indicateur , $pdar_connexion) or die(mysql_error());

$row_liste_indicateur = mysql_fetch_assoc($liste_indicateur);

$totalRows_liste_indicateur  = mysql_num_rows($liste_indicateur);*/



mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_scp = "SELECT id_groupe, nom_groupe from groupe_indicateur order by code_groupe";

$liste_scp  = mysql_query($query_liste_scp , $pdar_connexion) or die(mysql_error());

$row_liste_scp = mysql_fetch_assoc($liste_scp);

$totalRows_liste_scp  = mysql_num_rows($liste_scp);	



/*mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_ind_cl = "SELECT id_indicateur_produit, intitule_indicateur_produit FROM indicateur_produit, produit, sous_composante where id_sous_composante=sous_composante and id_produit=produit and composante='$idcp' and niveau_sygri=1";

$liste_ind_cl  = mysql_query($query_liste_ind_cl , $pdar_connexion) or die(mysql_error());

$row_liste_ind_cl  = mysql_fetch_assoc($liste_ind_cl);

$totalRows_liste_ind_cl  = mysql_num_rows($liste_ind_cl);	*/		  

mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_unite = "SELECT unite FROM unite_indicateur";

$liste_unite = mysql_query($query_liste_unite, $pdar_connexion) or die(mysql_error());

$row_liste_unite = mysql_fetch_assoc($liste_unite);

$totalRows_liste_unite = mysql_num_rows($liste_unite);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_indicateur = "SELECT id_indicateur_beneficiaire, intitule_indicateur_beneficiaire, unite FROM beneficiaire_sygri where id_indicateur_beneficiaire<10 order by ordre";
$liste_indicateur  = mysql_query($query_liste_indicateur , $pdar_connexion) or die(mysql_error());
$row_liste_indicateur = mysql_fetch_assoc($liste_indicateur);
$totalRows_liste_indicateur  = mysql_num_rows($liste_indicateur);

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
.Style2 {font-size: 12px}
.Style4 {font-size: 12px; font-weight: bold; }

-->

  </style>

</head>



<body>

  <div id="corps">



<div id="msg" align="center" class="red"></div>



<div id="special" class="special_center">

  <p align="center" <?php if(isset($_GET['iden'])) echo "style='color:yellow;'"; ?> ><?php if(isset($_GET['iden'])) echo "Modification"; else echo "Nouvelle indicateur SYGRI 1er Niveau" ; ?> </p>

  <div id="msg1" align="center" class="red"></div>

  <form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3" onSubmit="return verifform(this,1);">

    <table border="0" align="center" cellspacing="0" cellpadding="2">

      <tr valign="baseline">

        <td align="right" valign="middle"><span class="Style4">
          <label for="id_composante">Composante</label>
        </span></td>

        <td valign="top"><div align="left">
          <select name="composante">
            <?php if($totalRows_liste_sous_composante>0) { ?>
            <option value=""<?php if(isset($_GET['iden'])){if (!(strcmp("", $row_edit_data['composante']))) {echo "SELECTED";}} ?>>-- Choisissez --</option>
            <?php
									do { $libelle = (strlen($row_liste_sous_composante['intitule_composante'])>50)?substr($row_liste_sous_composante['intitule_composante'],0,50)."...":$row_liste_sous_composante['intitule_composante']; ?>
            <option value="<?php echo $row_liste_sous_composante['id_composante']?>"<?php if(isset($_GET['iden'])) {if (!(strcmp($row_liste_sous_composante['id_composante'], $row_edit_data['composante']))) {echo "SELECTED";} } elseif(isset($_GET['id_cp'])) {if (!(strcmp($row_liste_sous_composante['id_composante'], $_GET['id_cp']))) {echo "SELECTED";} } ?>><?php echo $row_liste_sous_composante['code_composante'].": ".$libelle;?></option>
            <?php
									} while ($row_liste_sous_composante = mysql_fetch_assoc($liste_sous_composante));}
									else {
									echo '<optgroup label="Aucune unit&eacute; disponible"></optgroup>'; } ?>
          </select>
        </div></td>
      </tr>

      <tr valign="baseline">
        <td align="right" valign="middle"><span class="Style4">
          <label for="intitule_sous_composante">Lib&eacute;ll&eacute; de l'indicateur </label>
        </span></td>
        <td valign="top"><div align="left">
            <textarea name="indicateur_sygri_niveau1" cols="30" id="indicateur_sygri_niveau1" alt="*,"><?php if(isset($_GET['iden'])) echo $row_edit_data['indicateur_sygri_niveau1'];?></textarea>
        </div></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle"><span class="Style4">Unit&eacute;</span></td>
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

        <td align="right" valign="middle"><span class="Style4">
          <label for="id_composante">Groupe</label>
        </span></td>

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
        <td align="right" valign="middle"><span class="Style4">
          <label for="id_composante">Valeurs cibles </label>
        </span></td>
        <td valign="top"><div align="left">
            RPE: 
            <input name="cible_projet" type="text" id="cible_projet"  value="<?php if(isset($_GET['iden'])) echo $row_edit_data['cible_projet'];?>" size="10" alt="*," />
            RMP:
            <input name="cible_rmp" type="text" id="cible_rmp"  value="<?php if(isset($_GET['iden'])) echo $row_edit_data['cible_rmp'];?>" size="10" alt="*," />
        </div></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" class="Style2 Style4"><strong>Type de b&eacute;n&eacute;ficiaire </strong></td>
        <td valign="top"><strong>
          <select name="beneficiaire" style="border-color:#FF0000 ">
            <option value="0">-- Choisissez --</option>
            <?php if($totalRows_liste_indicateur>0) { ?>
            <?php
				do {  
				?>
            <option value="<?php echo $row_liste_indicateur['id_indicateur_beneficiaire'];?>"<?php if(isset($_GET['iden'])) {if (!(strcmp($row_edit_data['beneficiaire'], $row_liste_indicateur['id_indicateur_beneficiaire']))) {echo "SELECTED";} } ?>><?php echo substr($row_liste_indicateur['intitule_indicateur_beneficiaire'],0, 60)."... (".$row_liste_indicateur['unite'].")";?></option>
            <?php
				} while ($row_liste_indicateur = mysql_fetch_assoc($liste_indicateur));} 
				else {
				 echo '<optgroup label="Aucun indicateur de b&eacute;n&eacute;ficiaire disponible"></optgroup>'; } ?>
          </select>
        </strong></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle" class="Style2 Style4"><span class="Style4">Mode de suivi </span></td>
        <td valign="top"><strong class="Style4"><strong class="Style4"><strong>
          <select name="type_suivi">
            <option value="0"<?php if(isset($_GET['iden'])){if (!(strcmp(0, $row_edit_data['type_suivi']))) {echo "SELECTED";}} ?>>R&eacute;alisation</option>
            <option value="2"<?php if(isset($_GET['iden'])){if (!(strcmp(2, $row_edit_data['type_suivi']))) {echo "SELECTED";}} ?>>Rapports d'&eacute;tudes</option>
          </select>
        </strong></strong></strong></td>
      </tr>
      <tr valign="baseline">
        <td align="right" valign="middle"><span class="Style4">N&deg;</span></td>
        <td valign="top"><input name="ordre" type="text" id="ordre" value="<?php if(isset($_GET['iden'])) echo $row_edit_data['ordre']; ?>" size="10" alt="*," />
          <i>(Pour le tri)</i></td>
      </tr>



      <tr valign="baseline">

        <td colspan="2" align="right" nowrap="nowrap"><div align="center">

            <input type="submit" value="<?php if(isset($_GET['iden'])) echo "Modifier"; else echo "Ajouter" ; ?>"  class="inputsubmit" />

&nbsp;&nbsp; <a title="Annuler la modification" href="">
<input name="Submit" type="reset" class="inputsubmit" value="Annuler" onClick="parent.tb_remove();" />
</a></div></td>
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