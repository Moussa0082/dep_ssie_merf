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

if(isset($_GET["id_inds"])) { $id_inds=$_GET["id_inds"]; 
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_cppro = "SELECT id_indicateur_sygri_niveau1_projet, intitule_indicateur_sygri_fida, indicateur_sygri_fida.unite, cible_projet FROM indicateur_sygri1_projet, indicateur_sygri_fida where  indicateur_sygri_niveau1=id_indicateur_sygri_fida and id_indicateur_sygri_niveau1_projet='$id_inds' and niveau_sygri=1 order by ordre";
$edit_cppro = mysql_query($query_edit_cppro, $pdar_connexion) or die(mysql_error());
$row_edit_cppro = mysql_fetch_assoc($edit_cppro);
$totalRows_edit_cppro = mysql_num_rows($edit_cppro);
}


$page = $_SERVER['PHP_SELF'];
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  $insertSQL = sprintf("INSERT INTO  indicateur_sygri1_cl (indicateur_niveau1, indicateur_cl, id_personnel, date_enregistrement) VALUES (%s, %s,'$personnel', '$date')",
                       GetSQLValueString($id_inds, "int"),
					   GetSQLValueString($_POST['indicateur_cl'], "int"));
					   
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());

  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok&id_inds=$id_inds"; else $insertGoTo .= "?insert=ok&id_inds=$id_inds";
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



//}



mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_icl = "SELECT id_indicateur_produit, intitule_indicateur_produit, id_indicateur_sygri_niveau1_cl FROM indicateur_produit, indicateur_sygri1_cl where id_indicateur_produit=indicateur_cl and indicateur_niveau1='$id_inds' and niveau_sygri=1";
$liste_icl  = mysql_query($query_liste_icl , $pdar_connexion) or die(mysql_error());
$row_liste_icl  = mysql_fetch_assoc($liste_icl);
$totalRows_liste_icl  = mysql_num_rows($liste_icl);



if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sup_mp = "DELETE FROM indicateur_sygri1_cl WHERE id_indicateur_sygri_niveau1_cl='$id'";
$Result1 = mysql_query($query_sup_mp, $pdar_connexion) or die(mysql_error());
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?id_inds=$id_inds&del=ok"; else $insertGoTo .= "?id_inds=$id_inds&del=no";
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_cl = "SELECT id_indicateur_produit, intitule_indicateur_produit FROM indicateur_produit, produit, sous_composante where id_sous_composante=sous_composante and id_produit=produit and niveau_sygri=1";
$liste_ind_cl  = mysql_query($query_liste_ind_cl , $pdar_connexion) or die(mysql_error());
$row_liste_ind_cl  = mysql_fetch_assoc($liste_ind_cl);
$totalRows_liste_ind_cl  = mysql_num_rows($liste_ind_cl);
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
.Style14 {color: #FF0000}
.Style11 {color: #FFFFFF; font-weight: bold; }
.Style16 {color: #FF0000; font-style: italic; }
.Style1 {	font-size: 12px;
	font-weight: bold;
}
.Style2 {font-size: 12px}
.Style9 {font-size: 12}
.Style17 {	color: #CC0000;
	font-weight: bold;
}
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
                    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
                      <div id="special">
                        <p align="center"> <a name="nmp" id="nmp"></a>
                            <?php if(isset($_GET['id'])) echo "Modifier l'indicateur CL"; else echo "Nouveau Indicateur CL" ; ?>
                        </p>
                       
                        <table border="0" align="center" cellspacing="0" cellpadding="5">
                          <tr valign="baseline">
                            <td align="right" valign="middle"><strong>
                              <label for="id_composante">Indicateur SYGRI 1er Niveau: </label>
                            </strong></td>
                            <td valign="top"><div align="left" class="Style17">
                                <?php if(isset($_GET['id_inds'])) echo $row_edit_cppro['intitule_indicateur_sygri_fida']." (". $row_edit_cppro['cible_projet']." ". $row_edit_cppro['unite'].")";?>
                            </div></td>
                          </tr>
                          <tr valign="baseline">
                            <td align="right" valign="middle">Indicateur du Cadre logique </td>
                            <td valign="top"><select name="indicateur_cl">
                                <option value="">-- Choisissez --</option>
                                <option value="0">-- Aucun --</option>
                                <?php if($totalRows_liste_ind_cl>0) { ?>
                                <?php
do {
?>
                                <option value="<?php echo $row_liste_ind_cl['id_indicateur_produit']?>"><?php echo substr($row_liste_ind_cl['intitule_indicateur_produit'],0, 70)." ...";?></option>
                                <?php
} while ($row_liste_ind_cl = mysql_fetch_assoc($liste_ind_cl));}
else {
echo '<optgroup label="Aucun indicateur disponible"></optgroup>'; } ?>
                            </select></td>
                          </tr>
                          <tr valign="baseline">
                            <td colspan="2" align="right" nowrap="nowrap"><div align="center">
                                <input name="submit" type="submit"  class="inputsubmit" value="<?php if(isset($_GET['iden'])) echo "Modifier"; else echo "Ajouter" ; ?>" />
                              &nbsp;&nbsp;<a class="button" href="<?php echo $page; ?>?cl">Quitter</a> </div></td>
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
          <?php $i=0; if($totalRows_liste_icl>0) {?>
          <tr class="titrecorps2">
            <td><span class="Style1">Nom indicateur </span></td>
            <td><span class="Style2"></span><span class="Style2"><strong>Editer</strong></span></td>
          </tr>
          <?php do { ?>
          <tr <?php if($i%2==0) echo 'bgcolor="#FFFFFF"'; $i=$i+1;?>>
            <td><span class="Style2"><?php echo $row_liste_icl['intitule_indicateur_produit']; ?></span></td>
            <td bgcolor="#D9D9D9"><div align="center"><a href="<?php echo $_SERVER['PHP_SELF']."?id_inds=$id_inds&id_sup=".$row_liste_icl['id_indicateur_sygri_niveau1_cl'];?>" onClick="return confirm('Voulez-vous vraiment retirer  <?php echo $row_liste_icl['intitule_indicateur_produit']; ?> ?');" /><img src="../images/delete.png" width="20" border="0"/></a> </div></td>
            </tr>
          <?php } while ($row_liste_icl= mysql_fetch_assoc($liste_icl)); ?>
          <?php } ?>
        </table></td>
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