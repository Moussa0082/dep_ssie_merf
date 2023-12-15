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

if(isset($_GET["annee"])) {$annee=$_GET['annee']; $idsygri=$_GET['idsygri'];}

$page = $_SERVER['PHP_SELF'];
//insertion
if(isset($_GET["cl"])){ 
 $insertGoTo = "../suivi_indicateur_sygri_niveau1.php";
 ?>
  <script type="text/javascript">

  parent.location.href = "<?php echo $insertGoTo; ?>";

  </script>
  <?php exit(0);
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_isygri = "SELECT id_indicateur_sygri_niveau1_projet, indicateur_sygri_niveau1, unite FROM indicateur_sygri1_projet 
where  id_indicateur_sygri_niveau1_projet='$idsygri'";
$edit_isygri = mysql_query($query_edit_isygri, $pdar_connexion) or die(mysql_error());
$row_edit_isygri = mysql_fetch_assoc($edit_isygri);
$totalRows_edit_isygri = mysql_num_rows($edit_isygri);


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rec = "SELECT id_ptba,code_activite_ptba, intitule_activite_ptba,id_indicateur_tache, intitule_indicateur_tache, sum(cible_indicateur_trimestre.cible) as valeur_cible FROM cible_indicateur_trimestre, indicateur_tache, ptba WHERE cible_indicateur_trimestre.indicateur=id_indicateur_tache and indicateur_sygri='$idsygri' AND id_ptba=indicateur_tache.activite  and annee ='$annee' group by  code_activite_ptba, intitule_activite_ptba,id_indicateur_tache, intitule_indicateur_tache ORDER BY code_activite_ptba asc";
$liste_rec = mysql_query($query_liste_rec, $pdar_connexion) or die(mysql_error());
$row_liste_rec = mysql_fetch_assoc($liste_rec);
$totalRows_liste_rec = mysql_num_rows($liste_rec);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title><?php echo $config->site_name; ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="../css/cbcscbindex.css" type="text/css" >
<link rel="stylesheet" href="../css/css.css" type="text/css" >
<script type="text/javascript" src="../script/function.js"></script>
<script type="text/javascript" src="../script/iepngfix_tilebg.js"></script>
<script src="../script/jquery-latest.js" type="text/javascript"></script>
  <style type="text/css">
<!--
.Style14 {color: #FF0000}
.Style2 {font-size: 12px}
.Style9 {font-size: 12}
.Style5 {	font-size: 13px;
	font-weight: bold;
}
.Style16 {
	font-size: 12px;
	color: #990000;
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
        <td valign="top"><div align="left"><span class="Style2"><strong>          PTBA: <span class="Style14">
          <?php if(isset($annee)) echo $annee;?>
        </span><br />Indicateur SYGRI:
                  <span class="Style14">
                  <?php if(isset($row_edit_isygri['indicateur_sygri_niveau1'])) echo $row_edit_isygri['indicateur_sygri_niveau1']." (".$row_edit_isygri['unite'].")";?>
              </span>            <strong><br />
                  </strong> </strong></span>
          </div>          
        <div align="center">
          </div></td>
        </tr>
     
      <tr>
        <td><hr /></td>
      </tr>
      <tr>
        <td><div align="center">
          <table border="1" align="center" cellspacing="0">
            <tr class="titrecorps2">
              <td>&nbsp;</td>
              <td><span class="Style5 Style3">Indicateur de PTBA  </span></td>
              <td><span class="Style5 Style3">Valeur Cible  </span></td>
              <td><span class="Style5 Style3">Valeur r&eacute;elle </span></td>
            </tr>
            <?php $t=0; if($totalRows_liste_rec>0) { $p1="j";$t=0; $i=0; $tc=0; $tr=0; do { ?>
			 <?php  if($p1!=$row_liste_rec['id_ptba']) {?>
          <tr bgcolor="#BED694">
            <td colspan="7" align="center"><div align="left" class="Style4 Style2"><strong>
              <?php  if($p1!=$row_liste_rec['id_ptba']) {echo $row_liste_rec['code_activite_ptba'].": ".$row_liste_rec['intitule_activite_ptba']; $i=0; }$p1=$row_liste_rec['id_ptba']; ?>
                        </strong></div></td>
            </tr>
          <?php } ?>
		  
		  <?php 
		   $ind_tache= $row_liste_rec['id_indicateur_tache'];
		    mysql_select_db($database_pdar_connexion, $pdar_connexion);
			$query_edit_real_ip = "SELECT sum( valeur_suivi ) AS valeur_reelle FROM suivi_indicateur_tache  WHERE suivi_indicateur_tache.indicateur='$ind_tache'";
			$edit_real_ip = mysql_query($query_edit_real_ip, $pdar_connexion) or die(mysql_error());
			$row_edit_real_ip = mysql_fetch_assoc($edit_real_ip);
			$totalRows_edit_real_ip = mysql_num_rows($edit_real_ip);
		  ?>
            <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; $i=$i+1; $t=$t+1;?> onMouseOver="this.bgColor='#CCFFFF';" onMouseOut="this.bgColor='#ECF0DF';">
              <td ><span class="Style2"><?php echo $t; ?></span></td>
              <td><span class="Style2"><?php echo $row_liste_rec['intitule_indicateur_tache']; ?></span></td>
              <td><div align="center"><span class="Style2"><?php echo number_format($row_liste_rec['valeur_cible'], 0, ',', ' '); $tc=$tc+$row_liste_rec['valeur_cible']; ?></span></div></td>
              <td><div align="center"><span class="Style2"><?php echo number_format($row_edit_real_ip['valeur_reelle'], 0, ',', ' '); $tr=$tr+$row_edit_real_ip['valeur_reelle']; ?></span></div></td>
            </tr>
           
            <?php } while ($row_liste_rec= mysql_fetch_assoc($liste_rec)); ?>
			 <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; $i=$i+1; $t=$t+1;?> onMouseOver="this.bgColor='#CCFFFF';" onMouseOut="this.bgColor='#ECF0DF';">
              <td colspan="2" ><div align="right" class="Style2"><strong>Total</strong>&nbsp;</div></td>
              <td><div align="center"><span class="Style16"><?php echo number_format($tc, 0, ',', ' '); ?></span></div></td>
              <td><div align="center"><span class="Style16"><?php echo number_format($tr, 0, ',', ' ');  ?></span></div></td>
            </tr>
            <?php } else { ?>
            <tr>
              <td colspan="4"><div align="center"><span class="Style9"><em><strong>Aucun suivi effectu&eacute;! </strong></em></span></div></td>
              </tr>
            <?php }  ?>
          </table>
        </div></td>
      </tr>
      <tr>
        <td><div align="center"><a title="Annuler la modification" href="">
          <input name="Submit" type="reset" class="inputsubmit" value="Quitter" onClick="parent.tb_remove();" />
        </a></div></td>
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