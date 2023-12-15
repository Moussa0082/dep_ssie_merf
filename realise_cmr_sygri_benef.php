<?php
session_start();
  if(isset($_GET['annee'])) $annee=$_GET['annee']; //else $annee=date("Y");

require_once('../Connections/pdar_connexion.php');
//include_once $path_racine."configurations.php";
//$config = new MSIConfig();
$poids_max=2048576; //Poids maximal du fichier en octets
$extensions_autorisees=array('rar','doc','pdf', 'zip', 'docx'); //Extensions autorisées
$url_site='./attachment/'; //Adresse où se trouve le fichier upload.php

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
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//if(isset($_GET["id_ind"])){ $id_ind=$_GET['id_ind']; // $annee=$_GET['annee'];
if(isset($_GET["annee"])) {$annee=$_GET['annee']; $idsygri=$_GET['idsygri'];}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_isygri = "SELECT * FROM  beneficiaire_sygri  where id_indicateur_beneficiaire='$idsygri'";
$edit_isygri = mysql_query($query_edit_isygri, $pdar_connexion) or die(mysql_error());
$row_edit_isygri = mysql_fetch_assoc($edit_isygri);
$totalRows_edit_isygri = mysql_num_rows($edit_isygri);
    
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$query_liste_reference = "SELECT * FROM realise_cmr_sygri_benef, region where id_region=zone and indicateur_sygri='$idsygri' and annee=$annee";
	$liste_reference = mysql_query($query_liste_reference, $pdar_connexion) or die(mysql_error());
	$row_liste_reference = mysql_fetch_assoc($liste_reference);
	$totalRows_liste_reference = mysql_num_rows($liste_reference);
  


$page = $_SERVER['PHP_SELF'];

//insertion village

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3"))
{
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; //$id_fr=$_GET['id_fr']; $annee=$_GET['annee'];
  
  if(isset($_FILES['fichier1']) && $_FILES['fichier1']['error'] == 0 && $_FILES['fichier1']['size']>$poids_max)
	{
		$message='Un ou plusieurs fichiers sont trop lourds !';
		echo $message;
	}
	elseif(isset($_FILES['fichier1']) && $_FILES['fichier1']['error'] == 0)
	{

            $nom1='../attachment/'.$_FILES['fichier1']['name'];
			move_uploaded_file($_FILES['fichier1']['tmp_name'],$nom1);
  $insertSQL = sprintf("INSERT INTO realise_cmr_sygri_benef (indicateur_sygri, zone , valeur_realise, valeur_cible, annee, source, commentaire, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s,'$personnel', '$date')",
                       GetSQLValueString($idsygri, "int"),
					   GetSQLValueString($_POST['ugl'], "int"),
					   GetSQLValueString($_POST['valeur_realise'], "double"),
					    GetSQLValueString($_POST['valeur_cible'], "double"),
					   GetSQLValueString($annee, "int"),
					    GetSQLValueString($_FILES['fichier1']['name'], "text"),
					   GetSQLValueString($_POST['commentaire'], "text"));

}else {
	
   $insertSQL = sprintf("INSERT INTO realise_cmr_sygri_benef (indicateur_sygri, zone , valeur_realise, valeur_cible, annee, commentaire, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s,'$personnel', '$date')",
                       GetSQLValueString($idsygri, "int"),
					   GetSQLValueString($_POST['ugl'], "int"),
					   GetSQLValueString($_POST['valeur_realise'], "double"),
  					   GetSQLValueString($_POST['valeur_cible'], "double"),
					   GetSQLValueString($annee, "int"),
					   GetSQLValueString($_POST['commentaire'], "text"));

  }
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  if($Result1) $insertGoTo = $page."?insert=ok&idsygri=$idsygri&annee=$annee";
  else $insertGoTo = $page."&insert=no&idsygri=$idsygri&annee=$annee";
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form3"))
{
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_GET['id'];//$id_fr=$_GET['id_fr']; $annee=$_GET['annee'];
 if(isset($_FILES['fichier1']) && $_FILES['fichier1']['error'] == 0 && $_FILES['fichier1']['size']>$poids_max)
	{
		$message='Un ou plusieurs fichiers sont trop lourds !';
		echo $message;
	}
	elseif(isset($_FILES['fichier1']) && $_FILES['fichier1']['error'] == 0)
	{

            $nom1='../attachment/'.$_FILES['fichier1']['name'];
			move_uploaded_file($_FILES['fichier1']['tmp_name'],$nom1);
  $insertSQL = sprintf("UPDATE realise_cmr_sygri_benef SET zone=%s, valeur_realise=%s, valeur_cible=%s, annee=%s, source=%s, commentaire=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_realise_cr='$c'",

					   GetSQLValueString($_POST['ugl'], "int"),
					   GetSQLValueString($_POST['valeur_realise'], "double"),
   					   GetSQLValueString($_POST['valeur_cible'], "double"),
					   GetSQLValueString($annee, "int"),
					    GetSQLValueString($_FILES['fichier1']['name'], "text"),
					   GetSQLValueString($_POST['commentaire'], "text"));
}else
{
  $insertSQL = sprintf("UPDATE realise_cmr_sygri_benef SET zone=%s, valeur_realise=%s, valeur_cible=%s, annee=%s, commentaire=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_realise_cr='$c'",

					   GetSQLValueString($_POST['ugl'], "int"),
					   GetSQLValueString($_POST['valeur_realise'], "double"),
   					   GetSQLValueString($_POST['valeur_cible'], "double"),
					   GetSQLValueString($annee, "int"),
					   GetSQLValueString($_POST['commentaire'], "text"));
}
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  if($Result1) $insertGoTo = $page."?insert=ok&idsygri=$idsygri&annee=$annee";
  else $insertGoTo = $page."&insert=no&idsygri=$idsygri&annee=$annee";
  header(sprintf("Location: %s", $insertGoTo));
}

/*if(isset($_GET["cl"])){  $annee=$_GET['annee'];
 $insertGoTo = "../suivi_formation.php?annee=$annee";
 ?>
  <script type="text/javascript">

  parent.location.href = "<?php echo $insertGoTo; ?>";

  </script>
  <?php exit(0);
}*/
if(isset($_GET["id_supref"])){  $idsygri=$_GET["id_supref"];  
  $query_sup_ref = "delete from realise_cmr_sygri_benef WHERE id_realise_cr='$idsygri'";
  $Result1 = mysql_query($query_sup_ref, $pdar_connexion) or die(mysql_error());
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok&idsygri=$idsygri&annee=$annee"; else $insertGoTo .= "?del=no&idsygri=$idsygri&annee=$annee";
  header(sprintf("Location: %s", $insertGoTo));
}

if(isset($_GET["id"])) { $id=$_GET["id"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_reference = "SELECT * FROM realise_cmr_sygri_benef WHERE id_realise_cr='$id'";
$edit_reference = mysql_query($query_edit_reference, $pdar_connexion) or die(mysql_error());
$row_edit_reference = mysql_fetch_assoc($edit_reference);
$totalRows_edit_reference = mysql_num_rows($edit_reference);
}
else $id=-1;

 mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_zone = "SELECT * FROM region where  id_region not in (select zone from realise_cmr_sygri_benef where annee=$annee and id_realise_cr!=$id and indicateur_sygri='$idsygri') order by code";
$liste_zone  = mysql_query($query_liste_zone , $pdar_connexion) or die(mysql_error());
$row_liste_zone  = mysql_fetch_assoc($liste_zone );
$totalRows_liste_zone  = mysql_num_rows($liste_zone );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title><?php echo $config->site_name; ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="../css/cbcscbindex.css" type="text/css" >
<link rel="stylesheet" href="../css/css.css" type="text/css" >
<script type="text/javascript" src="../script/function.php"></script>
<script type="text/javascript" src="../script/iepngfix_tilebg.js"></script>
<script src="../script/jquery-latest.js" type="text/javascript"></script>
  <style type="text/css">
<!--
.Style14 {color: #FF0000}
.Style11 {color: #FFFFFF; font-weight: bold; }
-->
  </style>
</head>

<body>
  <div id="corps">

<div id="msg" align="center" class="red"></div>

<div id="special">
  <div id="msg1" align="center" class="red"></div>
  <div align="center">
    <table border="0" align="center" cellspacing="5" cellpadding="0" width="100%" >
      <tr>
        <td><div align="left"><strong>PTBA: <span class="Style14">
        <?php if(isset($annee)) echo $annee;?>
        </span><br />
        Indicateur SYGRI: <span class="Style14">
        <?php if(isset($row_edit_isygri['intitule_indicateur_beneficiaire'])) echo $row_edit_isygri['intitule_indicateur_beneficiaire']." (".$row_edit_isygri['unite'].")";?>
        </span> <strong>
       
        </strong></strong></div></td>
      </tr>
        <td><div align="center">
            <table  border="0" align="center" cellspacing="0">
              <tr>
                <td><div align="center">
                    <div id="special">
                      <p align="center" ><?php  echo "Nouvelle valeur réalisée (".$annee.")"; ?>
                      </p>
                      <div id="msg1" align="center" class="red"></div>
<form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3" <?php if(!isset($_GET['id'])) {?>onSubmit="return verifform(this,1);" <?php } ?>enctype="multipart/form-data">
                        <table border="0" align="center" cellspacing="0" cellpadding="0" width="100%">
                          <tr valign="baseline">
                            <td align="right" valign="middle" nowrap="nowrap"><strong>Entit&eacute; de gestion (<span class="Style14">*</span>)</strong></td>
                            <td valign="top" nowrap="nowrap"><div align="left">
                              <select name="ugl" id="ugl"  alt="*," >
                                <option value="" <?php if(isset($_GET['id'])) {if (!(strcmp("", $row_edit_reference['annee']))) {echo "SELECTED";} } ?>>-- Choisissez --</option>
                                <?php
								

				   if($totalRows_liste_zone>0) { do {?>
                                <option value="<?php echo $row_liste_zone["id_region"]; ?>" <?php if(isset($_GET['id'])) {if (!(strcmp($row_liste_zone['id_region'], $row_edit_reference['zone']))) {echo "SELECTED";} } ?>><?php echo $row_liste_zone['nom_region']; ?></option>
                                <?php } while ($row_liste_zone = mysql_fetch_assoc($liste_zone)); } ?>
                              </select>
                              &nbsp;&nbsp;
                              <label for="nom_village"></label>
</div></td>
                          </tr>
                          <tr valign="baseline">
                            <td align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFF99"><label for="nbh"><strong>Cible  ( <span class="Style14"><?php echo $annee; ?></span>):</strong>&nbsp;</label></td>
                            <td valign="middle" bgcolor="#FFFF99"><div align="left">
                              <label for="nbf"></label>
                              <strong>
                              <input name="valeur_cible" type="text" id="valeur_cible" value="<?php if(isset($_GET['id'])) echo $row_edit_reference['valeur_cible'];  ?>" size="8" /> 
                              Valeur r&eacute;alis&eacute;e  (en <span class="Style14"><?php echo $annee; ?></span>):</strong>
                              <input name="valeur_realise" type="text" id="valeur_realise" value="<?php if(isset($_GET['id'])) echo $row_edit_reference['valeur_realise'];  ?>" size="8" />
                                &nbsp;&nbsp;</div></td>
                          </tr>
                          <tr valign="baseline">
                            <td align="right" valign="middle" nowrap="nowrap"><label for="nbh"><strong>Source:</strong></label></td>
                            <td valign="middle">
                              <div align="left">
                                <?php  if(isset($source_array[$row_liste_zone['id_region']])) { $rep="../attachment/"; $extension=substr(strrchr($source_array[$row_liste_zone['id_region']], '.')  ,1); if ($extension=="doc" || $extension=="docx") { echo("<a href='".$rep.$source_array[$row_liste_zone['id_region']]."'><img src='../images/doc.jpg' width='15'/> </a>");
										} elseif ($extension=="xls" || $extension=="xlsx") { echo("<a href='".$rep.$source_array[$row_liste_zone['id_region']]."'><img src='../images/xls.jpg' width='15'/> </a>");} elseif ($extension=="pdf") { echo("<a href='".$rep.$source_array[$row_liste_zone['id_region']]."'><img src='../images/pdf.jpg' width='15'/> </a>");} elseif ($extension=="zip") { echo("<a href='".$rep.$source_array[$row_liste_zone['id_region']]."'><img src='../images/zipicon.jpg' width='15'/> </a>");
			} elseif ($extension=="jpg") { echo("<a href='".$rep.$source_array[$row_liste_zone['id_region']]."'><img src='../images/overedit.png' width='15'/> </a>");
										} }?>
                                <input type="file" name="fichier1" id="fichier1"  size="5" />
                                <input type="hidden" name="MAX_FILE_SIZE"  value="20485760" />
                              </div>                              </td>
                          </tr>
                          <tr valign="baseline">
                            <td align="right" valign="middle" nowrap="nowrap"><strong>Observations:</strong></td>
                            <td valign="middle"><textarea name="commentaire" cols="30" rows="1" id="commentaire"><?php if(isset($_GET['id'])) echo $row_edit_reference['commentaire'];  ?></textarea></td>
                          </tr>
                          <tr valign="baseline">
                            <td align="right" valign="middle" nowrap="nowrap"><label for="annee"></label></td>
                            <td valign="top"><div align="left">
                               
                                <input name="Envoyer" type="submit" class="inputsubmit" value="<?php if(isset($_GET['id'])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
                                <input name="Submit" type="reset" class="inputsubmit" value="Quitter" onclick="parent.tb_remove();" />
                            </div></td>
                          </tr>
                        </table>
                        <input type="hidden" name="<?php if(isset($_GET['id'])) echo "MM_update"; else echo "MM_insert";  ?>" value="form3" />
                      </form>
                    </div>
                </div></td>
              </tr>
            </table>
        </div></td>
      </tr>

      <tr>
        <td><hr /></td>
      </tr>
        <tr>
        <td><div align="center"> <?php if(isset($totalRows_liste_reference) && $totalRows_liste_reference>0) { ?>
            <table border="1" cellspacing="0">
              <tr class="titrecorps2">
                <td rowspan="2" nowrap="nowrap"><strong>R&eacute;gion</strong></td>
                <td rowspan="2"><div align="center"><strong>Ann&eacute;e</strong></div></td>
                <td colspan="2"><div align="center"><strong>Valeur</strong>s</div></td>
                <td rowspan="2"><strong>Source</strong><strong></strong></td>
                <td colspan="2" rowspan="2"><strong>Editer</strong></td>
                </tr>
              <tr class="titrecorps2">
                <td><strong>Cible</strong></td>
                <td><strong>R&eacute;alis&eacute;e</strong> </td>
              </tr>
              
              <?php $i=0; $th=0; $tf=0; $tj=0; $tg=0; do { ?>
              <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; $i=$i+1;?>>
                <td><?php echo $row_liste_reference['abrege']; ?></td>
                <td><div align="center"><?php echo $row_liste_reference['annee'];  ?></div></td>
                <td><?php echo $row_liste_reference['valeur_cible'];?></td>
                <td><div align="center"><?php echo $row_liste_reference['valeur_realise'];?></div></td>
				<td><div align="center">
				  <?php  if(isset($row_liste_reference['source'])) { $rep="../attachment/"; $extension=substr(strrchr($row_liste_reference['source'], '.')  ,1); if ($extension=="doc" || $extension=="docx") { echo("<a href='".$rep.$row_liste_reference['source']."'><img src='../images/doc.jpg' width='15'/> </a>");
										} elseif ($extension=="xls" || $extension=="xlsx") { echo("<a href='".$rep.$row_liste_reference['source']."'><img src='../images/xls.jpg' width='15'/> </a>");} elseif ($extension=="pdf") { echo("<a href='".$rep.$row_liste_reference['source']."'><img src='../images/pdf.jpg' width='15'/> </a>");} elseif ($extension=="zip") { echo("<a href='".$rep.$row_liste_reference['source']."'><img src='../images/zipicon.jpg' width='15'/> </a>");
			} elseif ($extension=="jpg") { echo("<a href='".$rep.$row_liste_reference['source']."'><img src='../images/overedit.png' width='15'/> </a>");
										} }?>
				</div></td>                   
                <td><?php echo "<a href=".$_SERVER['PHP_SELF']."?id=".$row_liste_reference['id_realise_cr']."&idsygri=$idsygri&annee=$annee><img src='../images/edit.png' width='20' height='20' alt='Modifier'></a>" ?></td>
                <td><span class="Style11"><a href="<?php echo $_SERVER['PHP_SELF']."?id_supref=".$row_liste_reference['id_realise_cr']."&idsygri=$idsygri&annee=$annee"?>" onClick="return confirm('Voulez-vous vraiment supprimer la région <<?php echo $row_liste_reference['abrege']; ?>> ?');"><img src="../images/delete.png" width="15" border="0"/></a></span></td>
              </tr>
              <?php } while ($row_liste_reference = mysql_fetch_assoc($liste_reference)); ?>
            </table>
            <?php } ?>
        </div></td>
      </tr>
    </table>
  </div>
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