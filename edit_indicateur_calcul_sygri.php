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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


$page = $_SERVER['PHP_SELF'];
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  $insertSQL = sprintf("INSERT INTO somme_indicateur_sygri (indicateur, indicateur1, indicateur2, id_personnel, date_enregistrement) VALUES (%s, %s, %s, '$personnel', '$date')",
                       GetSQLValueString($_POST['indicateur'], "text"),
					   GetSQLValueString($_POST['indicateur1'], "text"),
					   GetSQLValueString($_POST['indicateur2'], "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or  $insertGoTo .= "?insert=ok";
  if ($Result1) $insertGoTo .= "?insert=ok"; //else $insertGoTo .= "?insert=ok";
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_GET['id'];
 
  $insertSQL = sprintf("UPDATE somme_indicateur_sygri SET indicateur=%s, indicateur1=%s, indicateur2=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_sum_indicateur_cec='$c'",
                       GetSQLValueString($_POST['indicateur'], "text"),
					   GetSQLValueString($_POST['indicateur1'], "text"),
					   GetSQLValueString($_POST['indicateur2'], "text"));
					   

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or $insertGoTo .= "?insert=ok";
  if ($Result1) $insertGoTo .= "?insert=ok"; //else $insertGoTo .= "?insert=ok";
  header(sprintf("Location: %s", $insertGoTo));
}



if(isset($_GET["cl"])){ 
 $insertGoTo = "../sygri_niveau1_projet.php";
 ?>
  <script type="text/javascript">

  parent.location.href = "<?php echo $insertGoTo; ?>";

  </script>
  <?php exit(0);
}

// Indicateur calculer
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_indicateur_calcule = "SELECT id_indicateur_sygri_niveau1_projet, intitule_indicateur_sygri_fida, indicateur_sygri_fida.unite FROM indicateur_sygri1_projet, indicateur_sygri_fida where  indicateur_sygri_niveau1=id_indicateur_sygri_fida  and niveau_sygri=1 and  type='somme' and id_indicateur_sygri_niveau1_projet not in (select indicateur from somme_indicateur_sygri) order by ordre";
$liste_indicateur_calcule  = mysql_query($query_liste_indicateur_calcule , $pdar_connexion) or die(mysql_error());
$row_liste_indicateur_calcule = mysql_fetch_assoc($liste_indicateur_calcule );
$totalRows_liste_indicateur_calcule = mysql_num_rows($liste_indicateur_calcule );

// Indicateur 1
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_tableau1 = "SELECT id_indicateur_sygri_niveau1_projet, intitule_indicateur_sygri_fida, indicateur_sygri_fida.unite FROM indicateur_sygri1_projet, indicateur_sygri_fida where  indicateur_sygri_niveau1=id_indicateur_sygri_fida  and niveau_sygri=1 and  type='unique'  order by ordre";
$liste_tableau1  = mysql_query($query_liste_tableau1 , $pdar_connexion) or die(mysql_error());
$row_liste_tableau1 = mysql_fetch_assoc($liste_tableau1 );
$totalRows_liste_tableau1 = mysql_num_rows($liste_tableau1 );


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_indicateur = "SELECT id_sum_indicateur, indicateur, indicateur1, indicateur2, intitule_indicateur_sygri_fida, indicateur_sygri_fida.unite FROM somme_indicateur_sygri, indicateur_sygri1_projet, indicateur_sygri_fida where indicateur_sygri_niveau1=id_indicateur_sygri_fida and id_indicateur_sygri_niveau1_projet=somme_indicateur_sygri.indicateur  ORDER BY indicateur1, indicateur2";
$liste_indicateur  = mysql_query($query_liste_indicateur , $pdar_connexion) or die(mysql_error());
$row_liste_indicateur = mysql_fetch_assoc($liste_indicateur );
$totalRows_liste_indicateur = mysql_num_rows($liste_indicateur );

/*if(isset($_GET["id"])) { $id=$_GET["id"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_ind = "SELECT * FROM somme_indicateur_cec WHERE id_sum_indicateur_cec='$id'";
$edit_ind = mysql_query($query_edit_ind, $pdar_connexion) or die(mysql_error());
$row_edit_ind = mysql_fetch_assoc($edit_ind);
$totalRows_edit_ind = mysql_num_rows($edit_ind);
}*/

if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sup_indicateur = "DELETE FROM somme_indicateur_sygri WHERE id_sum_indicateur='$id'";
$Result1 = mysql_query($query_sup_indicateur, $pdar_connexion) or die(mysql_error());
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo));
}
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
<script src="../script/jquery-latest.js" type="text/javascript"></script>
<script type="text/javascript" src="../script/iepngfix_tilebg.js"></script>
  <style type="text/css">
<!--
.Style1 {	font-size: 12px;
	font-weight: bold;
}
.Style2 {font-size: 12px}
.Style4 {font-size: 12px; font-weight: bold; color: #FF0000; }
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
        <td><div align="center">
            <table  border="0" align="center" cellspacing="0">
              <tr>
                <td><div align="center">
                    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onSubmit="return verifform(this,1);">
                      <div id="special">
                        <p align="center"> <a name="nmp" id="nmp"></a>
                            <?php if(isset($_GET['id'])) echo "Modifier l'indicateur calculé"; else echo "Nouveau indicateur calculé" ; ?>
                        </p>
                                                      
                        <table align="center">
                          <tr valign="baseline">
                            <td align="right" valign="top" nowrap="nowrap"><strong class="Style1">Indicateur</strong>&nbsp;</td>
                            <td><div align="left">
                              <select name="indicateur" style="border-color:#FF0000 ">
                                <?php if($totalRows_liste_indicateur_calcule>0) { ?>
                                <option value="">-- Choisissez --</option>
                                <?php
do {  
?>
                                <option value="<?php echo $row_liste_indicateur_calcule['id_indicateur_sygri_niveau1_projet'];?>"<?php if(isset($_GET['id'])) {if (!(strcmp($row_edit_ind['indicateur'], $row_liste_indicateur_calcule['id_indicateur_sygri_niveau1_projet']))) {echo "SELECTED";} } ?>><?php echo substr($row_liste_indicateur_calcule['intitule_indicateur_sygri_fida'],0, 60)." ... (".$row_liste_indicateur_calcule['unite'].")";?></option>
                                <?php
} while ($row_liste_indicateur_calcule = mysql_fetch_assoc($liste_indicateur_calcule));} 
else {
 echo '<optgroup label="Aucun indicateur disponible"></optgroup>'; } ?>
                              </select>
                            </div></td>
                          </tr>
                          <tr valign="baseline">
                            <td align="right" valign="top" nowrap="nowrap"><strong class="Style1">Op&eacute;rande 1 </strong></td>
                            <td><select name="indicateur1" style="border-color:#FF0000 ">
                                <?php if($totalRows_liste_tableau1>0) { ?>
                                <option value="0">-- Choisissez --</option>
                                <?php
do {  
?>
                                <option value="<?php echo $row_liste_tableau1['id_indicateur_sygri_niveau1_projet'];?>"<?php if(isset($_GET['id'])) {if (!(strcmp($row_edit_ind['indicateur1'], $row_liste_tableau1['id_indicateur_sygri_niveau1_projet']))) {echo "SELECTED";} } ?>><?php echo substr($row_liste_tableau1['intitule_indicateur_sygri_fida'],0, 60)." ... (".$row_liste_tableau1['unite'].")";?></option>
                                <?php
} while ($row_liste_tableau1 = mysql_fetch_assoc($liste_tableau1));
							 $rows = mysql_num_rows($liste_tableau1);
							  if($rows > 0) {
								  mysql_data_seek($liste_tableau1, 0);
								  $row_liste_tableau1 = mysql_fetch_assoc($liste_tableau1);}
							}
else {
 echo '<optgroup label="Aucun indicateur disponible"></optgroup>'; } ?>
                            </select></td>
                          </tr>
                          <tr valign="baseline">
                            <td align="right" valign="top" nowrap="nowrap"><strong class="Style1">Op&eacute;rande 2</strong></td>
                            <td><select name="indicateur2" style="border-color:#FF0000 ">
                                 <?php if($totalRows_liste_tableau1>0) { ?>
                                <option value="0">-- Choisissez --</option>
                                <?php
do {  
?>
                                <option value="<?php echo $row_liste_tableau1['id_indicateur_sygri_niveau1_projet'];?>"<?php if(isset($_GET['id'])) {if (!(strcmp($row_edit_ind['indicateur2'], $row_liste_tableau1['id_indicateur_sygri_niveau1_projet']))) {echo "SELECTED";} } ?>><?php echo substr($row_liste_tableau1['intitule_indicateur_sygri_fida'],0, 60)." ... (".$row_liste_tableau1['unite'].")";?></option>
                                <?php
} while ($row_liste_tableau1 = mysql_fetch_assoc($liste_tableau1));
							 $rows = mysql_num_rows($liste_tableau1);
							  if($rows > 0) {
								  mysql_data_seek($liste_tableau1, 0);
								  $row_liste_tableau1 = mysql_fetch_assoc($liste_tableau1);}
							} 
else {
 echo '<optgroup label="Aucun indicateur disponible"></optgroup>'; } ?>
                            </select></td>
                          </tr>
                          <tr valign="baseline">
                            <td align="right" nowrap="nowrap">&nbsp;</td>
                            <td><div align="center">
                                <input name="Envoyer" type="submit" class="inputsubmit" value="<?php if(isset($_GET['id'])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
                                <a title="Annuler" href="<?php echo (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF']?>">
                                <input name="Submit" type="reset" class="inputsubmit" value="Annuler" />
                            </a> </div></td>
                          </tr>
                        </table>
                        <input type="hidden" name="<?php if(isset($_GET['id'])) echo "MM_update"; else echo "MM_insert";  ?>" value="form1" />
                      </div>
                    </form>
                </div></td>
              </tr>
            </table>
        </div></td>
      </tr>
      <tr>
        <td><table border="0" align="center" cellspacing="3">
          <tr class="titrecorps2">
            <td colspan="2"><span class="Style1">Indicateurs &agrave; additionner </span></td>
            <td rowspan="2"><span class="Style1">Editer</span></td>
          </tr>
          <tr class="titrecorps2">
            <td><span class="Style1">Op&eacute;rande 1 </span></td>
            <td><span class="Style1">Op&eacute;rande 2 </span></td>
            </tr>
          <?php $t=0; if($totalRows_liste_indicateur>0) { $p1="j";$t=0; $i=0;do { ?>
         <?php  if($p1!=$row_liste_indicateur['indicateur']) {?>
                   
          <tr <?php  echo 'bgcolor="#FF9934"'; ?>>
          <td align="center" colspan="7"><div align="left">
          <?php  if($p1!=$row_liste_indicateur['indicateur']) {echo $row_liste_indicateur['intitule_indicateur_sygri_fida']." (".$row_liste_indicateur['unite'].")"; }$p1=$row_liste_indicateur['indicateur']; ?>
          </div></td>
          </tr>
          <?php } ?>

          <tr <?php if($i%2==0) echo 'bgcolor="#FFFFFF"';  $t=$t+1; $i=$i+1;?>>
            <td><span class="Style2">
              <?php
				   if(isset($row_liste_indicateur['indicateur1']) && $row_liste_indicateur['indicateur1']!=0) { $id1=$row_liste_indicateur['indicateur1'];
					mysql_select_db($database_pdar_connexion, $pdar_connexion);
					$query_edit_ind1 = "SELECT id_indicateur_sygri_niveau1_projet, cible_projet, intitule_indicateur_sygri_fida, indicateur_sygri_fida.unite FROM indicateur_sygri1_projet, indicateur_sygri_fida where  indicateur_sygri_niveau1=id_indicateur_sygri_fida and id_indicateur_sygri_niveau1_projet='$id1'";
					$edit_ind1 = mysql_query($query_edit_ind1, $pdar_connexion) or die(mysql_error());
					$row_edit_ind1 = mysql_fetch_assoc($edit_ind1);
					$totalRows_edit_ind1 = mysql_num_rows($edit_ind1);
					echo $row_edit_ind1['intitule_indicateur_sygri_fida']." (".$row_edit_ind1['unite'].")";
					}else echo "Aucun";
				   ?>
            </span></td>
            <td><span class="Style2">
              <?php
				   if(isset($row_liste_indicateur['indicateur2']) && $row_liste_indicateur['indicateur2']!=0) { $id2=$row_liste_indicateur['indicateur2'];
					mysql_select_db($database_pdar_connexion, $pdar_connexion);
					$query_edit_ind2 = "SELECT id_indicateur_sygri_niveau1_projet, cible_projet, intitule_indicateur_sygri_fida, indicateur_sygri_fida.unite FROM indicateur_sygri1_projet, indicateur_sygri_fida where  indicateur_sygri_niveau1=id_indicateur_sygri_fida and id_indicateur_sygri_niveau1_projet='$id2'";
					$edit_ind2 = mysql_query($query_edit_ind2, $pdar_connexion) or die(mysql_error());
					$row_edit_ind2 = mysql_fetch_assoc($edit_ind2);
					$totalRows_edit_ind2 = mysql_num_rows($edit_ind2);
					echo $row_edit_ind2['intitule_indicateur_sygri_fida']." (".$row_edit_ind2['unite'].")";
					}else echo "Aucun";
				   ?>
            </span></td>
            <td bgcolor="#D9D9D9"><a href="<?php echo $_SERVER['PHP_SELF']."?id_sup=".$row_liste_indicateur['id_sum_indicateur'].""?>" onClick="return confirm('Voulez-vous vraiment supprimer l\'indicateur <?php echo $row_liste_indicateur['intitule_indicateur_sygri_fida']; ?> ?');" /><img src="../images/delete.png" width="20"/></a></td>
            </tr>
          <?php } while ($row_liste_indicateur = mysql_fetch_assoc($liste_indicateur)); ?>
          <?php } ?>
        </table></td>
      </tr>
      <tr>
        <td><a class="button" href="<?php echo $page; ?>?cl">Quitter</a></td>
      </tr>

      <tr>
        <td><hr /></td>
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