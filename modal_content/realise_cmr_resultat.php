<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
$path = '../';
include_once $path . 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"])) {
//header(sprintf("Location: %s", "./"));
  exit;
}
include_once $path . $config->sys_folder . "/database/db_connexion.php";

  if(isset($_GET['annee'])) $annee=$_GET['annee']; //else $annee=date("Y");

$poids_max=2048576; //Poids maximal du fichier en octets
$extensions_autorisees=array('rar','doc','pdf', 'zip', 'docx'); //Extensions autorisées
$url_site='./attachment/'; //Adresse où se trouve le fichier upload.php


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if(isset($_GET["id_ind"])){ $id_ind=$_GET['id_ind']; // $annee=$_GET['annee'];
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$query_indicateur_courant = "SELECT intitule_indicateur_cmr_res , intitule_indicateur_resultat, intitule_resultat FROM indicateur_resultat_cmr, indicateur_resultat, resultat  WHERE ".$_SESSION["clp_where"]." and id_indicateur_resultat=indicateur_res and id_resultat=resultat and id_indicateur=$id_ind";
	$indicateur_courant  = mysql_query($query_indicateur_courant , $pdar_connexion) or die(mysql_error());
	$row_indicateur_courant  = mysql_fetch_assoc($indicateur_courant);
	$totalRows_indicateur_courant  = mysql_num_rows($indicateur_courant);	
    
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$query_liste_reference = "SELECT * FROM realise_cmr_resultat where indicateur_rescmr=$id_ind  order by annee";
	$liste_reference = mysql_query($query_liste_reference, $pdar_connexion) or die(mysql_error());
	$row_liste_reference = mysql_fetch_assoc($liste_reference);
	$totalRows_liste_reference = mysql_num_rows($liste_reference);
  }


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
  $insertSQL = sprintf("INSERT INTO realise_cmr_resultat (indicateur_rescmr , valeur_realise, annee, source, commentaire, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s,'$personnel', '$date')",
                       GetSQLValueString($id_ind, "int"),
					   GetSQLValueString($_POST['valeur_realise'], "double"),
					   GetSQLValueString($_POST['annee'], "int"),
					    GetSQLValueString($_FILES['fichier1']['name'], "text"),
					   GetSQLValueString($_POST['commentaire'], "text"));

}else {
	
   $insertSQL = sprintf("INSERT INTO realise_cmr_resultat (indicateur_rescmr , valeur_realise, annee, commentaire, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s,'$personnel', '$date')",
                       GetSQLValueString($id_ind, "int"),
					   GetSQLValueString($_POST['valeur_realise'], "double"),
					   GetSQLValueString($_POST['annee'], "int"),
					   GetSQLValueString($_POST['commentaire'], "text"));

  }
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  if($Result1) $insertGoTo = $page."?insert=ok&id_ind=$id_ind";
  else $insertGoTo = $page."&insert=no&id_ind=$id_ind";
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
  $insertSQL = sprintf("UPDATE realise_cmr_resultat SET valeur_realise=%s, annee=%s, source=%s, commentaire=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_realise_cr='$c'",

					   GetSQLValueString($_POST['valeur_realise'], "double"),
					   GetSQLValueString($_POST['annee'], "int"),
					    GetSQLValueString($_FILES['fichier1']['name'], "text"),
					   GetSQLValueString($_POST['commentaire'], "text"));
}else
{
  $insertSQL = sprintf("UPDATE realise_cmr_resultat SET  valeur_realise=%s, annee=%s, commentaire=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_realise_cr='$c'",

					   GetSQLValueString($_POST['valeur_realise'], "double"),
					   GetSQLValueString($_POST['annee'], "int"),
					   GetSQLValueString($_POST['commentaire'], "text"));
}
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  if($Result1) $insertGoTo = $page."?insert=ok&id_ind=$id_ind";
  else $insertGoTo = $page."&insert=no&id_ind=$id_ind";
  header(sprintf("Location: %s", $insertGoTo));
}


if(isset($_GET["id_supref"])){  $id_ref=$_GET["id_supref"];  
  $query_sup_ref = "delete from realise_cmr_resultat WHERE id_realise_cr='$id_ref'";
  $Result1 = mysql_query($query_sup_ref, $pdar_connexion) or die(mysql_error());
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok&id_ind=$id_ind"; else $insertGoTo .= "?del=no&id_ind=$id_ind";
  header(sprintf("Location: %s", $insertGoTo));
}

if(isset($_GET["id"])) { $id=$_GET["id"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_reference = "SELECT * FROM realise_cmr_resultat WHERE id_realise_cr='$id'";
$edit_reference = mysql_query($query_edit_reference, $pdar_connexion) or die(mysql_error());
$row_edit_reference = mysql_fetch_assoc($edit_reference);
$totalRows_edit_reference = mysql_num_rows($edit_reference);
}
else $id=-1;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_zone = "select annee FROM realise_cmr_resultat where indicateur_rescmr=$id_ind and id_realise_cr!=$id ORDER BY annee";
$liste_zone  = mysql_query($query_liste_zone , $pdar_connexion) or die(mysql_error());
$row_liste_zone  = mysql_fetch_assoc($liste_zone );
$totalRows_liste_zone  = mysql_num_rows($liste_zone );
$liste_zone_array = array();
if($totalRows_liste_zone>0) { do { $liste_zone_array[] = $row_liste_zone["annee"]; } while ($row_liste_zone = mysql_fetch_assoc($liste_zone)); } 

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
.Style16 {color: #000000; font-style: italic; }
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
        <td><span class="Style16"><?php echo $row_indicateur_courant['intitule_resultat']; ?></span>&nbsp;<br />
          &nbsp;&nbsp;<strong>R&eacute;sultat: </strong><span class="Style14"><em><?php echo $row_indicateur_courant['intitule_indicateur_resultat']; ?></em></span>&nbsp;&nbsp;<br />
           &nbsp;&nbsp;&nbsp;&nbsp;<strong>Indicateur résultat: </strong><span class="Style14"><em><?php echo $row_indicateur_courant['intitule_indicateur_cmr_res']; ?></em></span>&nbsp;&nbsp;</td>
      </tr>
      <tr>
        <td><div align="center">
            <table  border="0" align="center" cellspacing="0">
              <tr>
                <td><div align="center">
                    <div id="special">
                      <p align="center" >
                        <?php  echo "Nouvelle valeur annuelle réalisée"  ; ?>
                      </p>
                      <div id="msg1" align="center" class="red"></div>
               <form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3" <?php if(!isset($_GET['id'])) {?>onSubmit="return verifform(this,1);" <?php } ?> enctype="multipart/form-data">
                        <table border="0" align="center" cellspacing="0" cellpadding="0" width="100%">
                          <tr valign="baseline">
                            <td align="right" valign="middle" nowrap="nowrap"><strong>Ann&eacute;e(<span class="Style14">*</span>) : </strong></td>
                            <td valign="top" nowrap="nowrap"><div align="left">
                                <select name="annee" id="annee"  alt="*," >
			                  <option value="" <?php if(isset($_GET['id'])) {if (!(strcmp("", $row_edit_reference['annee']))) {echo "SELECTED";} } ?>>-- Choisissez --</option>
            <?php for($j=$_SESSION["annee_debut_projet"];$j<=$_SESSION["annee_fin_projet"];$j++){ if(!in_array($j,$liste_zone_array)){ ?>
                                  <option value="<?php echo $j; ?>" <?php if(isset($_GET['id'])) {if (!(strcmp($j, $row_edit_reference['annee']))) {echo "SELECTED";} } ?>><?php echo $j; ?></option>
                                  <?php } } ?>
                                </select></div></td>
                          </tr>
                          <tr valign="baseline">
                            <td align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFF99"><label for="nbh"><strong>Valeur r&eacute;alis&eacute;e :</strong>&nbsp;</label></td>
                            <td valign="middle" bgcolor="#FFFF99"><div align="left">&nbsp;
                              <label for="nbf"></label>
                       <input name="valeur_realise" type="text" id="valeur_realise" value="<?php if(isset($_GET['id'])) echo $row_edit_reference['valeur_realise'];  ?>" size="8" />
                                &nbsp;&nbsp;
                                <strong>Observations:</strong>
                                <textarea name="commentaire" cols="20" rows="1" id="commentaire"><?php if(isset($_GET['id'])) echo $row_edit_reference['commentaire'];  ?></textarea>
                            </div></td>
                          </tr>
                          <tr valign="baseline">
                            <td align="right" valign="middle" nowrap="nowrap"><label for="nbh"><strong>Source : </strong></label></td>
                            <td valign="middle">
                              <div align="left">
                                <?php  if(isset($source_array[$row_liste_zone['annee']])) { $rep="../attachment/"; $extension=substr(strrchr($source_array[$row_liste_zone['annee']], '.')  ,1); if ($extension=="doc" || $extension=="docx") { echo("<a href='".$rep.$source_array[$row_liste_zone['annee']]."'><img src='../images/doc.jpg' width='15'/> </a>");
										} elseif ($extension=="xls" || $extension=="xlsx") { echo("<a href='".$rep.$source_array[$row_liste_zone['annee']]."'><img src='../images/xls.jpg' width='15'/> </a>");} elseif ($extension=="pdf") { echo("<a href='".$rep.$source_array[$row_liste_zone['annee']]."'><img src='../images/pdf.jpg' width='15'/> </a>");} elseif ($extension=="zip") { echo("<a href='".$rep.$source_array[$row_liste_zone['annee']]."'><img src='../images/zipicon.jpg' width='15'/> </a>");
			} elseif ($extension=="jpg") { echo("<a href='".$rep.$source_array[$row_liste_zone['annee']]."'><img src='../images/overedit.png' width='15'/> </a>");
										} }?>
                                <input type="file" name="fichier1" id="fichier1"  size="5" />
                                <input type="hidden" name="MAX_FILE_SIZE"  value="20485760" />
                              </div>
                              </td>
                          </tr>
                          <tr valign="baseline">
                            <td align="right" valign="middle" nowrap="nowrap"><label for="annee"></label></td>
                            <td valign="top"><div align="left">
                               
                                <input name="Envoyer" type="submit" class="inputsubmit" value="<?php if(isset($_GET['id'])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
                                <!--<input name="Submit" type="reset" class="inputsubmit" value="Quitter" onclick="parent.tb_remove();" />   -->
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
                <td><div align="center"><strong>Ann&eacute;e</strong></div></td>
                <td><div align="center"><strong>Valeur</strong> r&eacute;alis&eacute;e </div></td>
                <td><strong>Source</strong><strong></strong></td>
                <td colspan="2"><strong>Editer</strong></td>
                </tr>
              
              <?php $i=0; $th=0; $tf=0; $tj=0; $tg=0; do { ?>
              <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; $i=$i+1;?>>
                <td><div align="center"><?php echo $row_liste_reference['annee'];  ?></div></td>
                <td><div align="center"><?php echo $row_liste_reference['valeur_realise'];?></div></td>
				<td><div align="center">
				  <?php  if(isset($row_liste_reference['source'])) { $rep="../attachment/"; $extension=substr(strrchr($row_liste_reference['source'], '.')  ,1); if ($extension=="doc" || $extension=="docx") { echo("<a href='".$rep.$row_liste_reference['source']."'><img src='../images/doc.jpg' width='15'/> </a>");
										} elseif ($extension=="xls" || $extension=="xlsx") { echo("<a href='".$rep.$row_liste_reference['source']."'><img src='../images/xls.jpg' width='15'/> </a>");} elseif ($extension=="pdf") { echo("<a href='".$rep.$row_liste_reference['source']."'><img src='../images/pdf.jpg' width='15'/> </a>");} elseif ($extension=="zip") { echo("<a href='".$rep.$row_liste_reference['source']."'><img src='../images/zipicon.jpg' width='15'/> </a>");
			} elseif ($extension=="jpg") { echo("<a href='".$rep.$row_liste_reference['source']."'><img src='../images/overedit.png' width='15'/> </a>");
										} }?>
				</div></td>
                <td><?php echo "<a href=".$_SERVER['PHP_SELF']."?id=".$row_liste_reference['id_realise_cr']."&id_ind=$id_ind><img src='../images/edit.png' width='20' height='20' alt='Modifier'></a>" ?></td>
                <td><span class="Style11"><a href="<?php echo $_SERVER['PHP_SELF']."?id_supref=".$row_liste_reference['id_realise_cr']."&id_ind=$id_ind"?>" onClick="return confirm('Voulez-vous vraiment supprimer la zone <<?php echo $row_liste_reference['sigle']; ?>> ?');"><img src="../images/delete.png" width="15" border="0"/></a></span></td>
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