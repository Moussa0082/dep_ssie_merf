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

$poids_max=2048576; //Poids maximal du fichier en octets
$extensions_autorisees=array('rar','doc','pdf', 'zip', 'docx'); //Extensions autorisées
$url_site='./attachment/'; //Adresse où se trouve le fichier upload.php

//fonction calcul nb jour
function NbJours($debut, $fin) {

  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);

  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) -
          mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);

  return(($diff / 86400)+1);

}
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
//if(isset($_GET["id_ct"])) {$id_ct=$_GET['id_ct'];}
if(isset($_GET["id"])) { $id_ind=$_GET["id"];}

//if(isset($_GET['trimestre'])) {$trimestre=$_GET['trimestre'];}
$annee=date("Y");

$page = $_SERVER['PHP_SELF'];

 
if(isset($_GET["id_ind"])) { $id_ind=$_GET["id_ind"];}
 mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$query_indicateur_courant = "SELECT intitule_indicateur_cmr_og, intitule_indicateur_objectif_global, cible_cmr, valeur_reelle, valeur_reelle1, indicateur_objectif_global_cmr.source, observation, unite FROM indicateur_objectif_global, indicateur_objectif_global_cmr WHERE ".$_SESSION["clp_where"]." and  id_indicateur_objectif_global=indicateur_og  and  id_indicateur=$id_ind";
	$indicateur_courant  = mysql_query($query_indicateur_courant , $pdar_connexion) or die(mysql_error());
	$row_indicateur_courant  = mysql_fetch_assoc($indicateur_courant);
	$totalRows_indicateur_courant  = mysql_num_rows($indicateur_courant);					  
	  

 mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$query_lobjectif = "SELECT intitule_objectif_global FROM objectif_global WHERE ".$_SESSION["clp_where"]." limit 1";
	$lobjectif  = mysql_query($query_lobjectif , $pdar_connexion) or die(mysql_error());
	$row_lobjectif  = mysql_fetch_assoc($lobjectif);
	$totalRows_lobjectif  = mysql_num_rows($lobjectif);

//insertion suivi plan de decaissement
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

$valeurr = "";
 $valeurr = $_POST['valeur_reelle'];
 $valeurr1 = $_POST['valeur_reelle1'];


if(isset($_FILES['fichier1']) && $_FILES['fichier1']['error'] == 0 && $_FILES['fichier1']['size']>$poids_max)
	{
		$message='Un ou plusieurs fichiers sont trop lourds !';
		echo $message;
	}
	elseif(isset($_FILES['fichier1']) && $_FILES['fichier1']['error'] == 0)
	{

            $nom1='../attachment/'.$_FILES['fichier1']['name'];
			move_uploaded_file($_FILES['fichier1']['tmp_name'],$nom1);
			
		  $insertSQL = sprintf("UPDATE indicateur_objectif_global_cmr SET  valeur_reelle=%s,valeur_reelle1=%s, source=%s, observation=%s, modifier_par='$personnel', modifier_le='$date' WHERE  id_indicateur=%s" ,
					   GetSQLValueString($valeurr, "double"),
                       GetSQLValueString($valeurr1, "double"),
					    GetSQLValueString($_FILES['fichier1']['name'], "text"),
					   GetSQLValueString($_POST['commentaire'], "text"),
   					   GetSQLValueString($id_ind, "int"));

	}elseif(isset($_POST['valeur_reelle'])) {

	  $insertSQL = sprintf("UPDATE indicateur_objectif_global_cmr SET  valeur_reelle=%s,valeur_reelle1=%s, observation=%s, modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur=%s" ,
					   GetSQLValueString($valeurr, "double"),
                       GetSQLValueString($valeurr1, "double"),
					   GetSQLValueString($_POST['commentaire'], "text"),
   					   GetSQLValueString($id_ind, "int"));

  }

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?id_ind=$id_ind&insert=ok"; else $insertGoTo .= "?id_ind=$id_ind&insert=no"; 
  header(sprintf("Location: %s", $insertGoTo));
}
//}


$annee_courant=date("Y");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="../css/cbcscbindex.css" type="text/css" >
<link rel="stylesheet" href="../css/css.css" type="text/css" >
<script src="../script/jquery-latest.js" type="text/javascript"></script>
<script type="text/javascript" src="../script/function.php"></script>
<script type="text/javascript" src="../script/iepngfix_tilebg.js"></script>
  <style type="text/css">
<!--
.Style18 {font-size: 12px; font-weight: bold; }
.Style2 {	font-size: 13px;
	color: #990000;
	font-weight: bold;
}
.Style16 {color: #000000; font-style: italic; }
.Style19 {color: #000000}
.Style21 {color: #000000; font-weight: bold; }
-->
  </style>
</head>

<body>
  <div id="corps">

<div id="msg" align="center" class="red"></div>

<div id="special">
  <div id="msg1" align="center" class="red"></div>
  <div align="center">
    <table border="0" align="center" cellspacing="5" cellpadding="0" >
	 <tr>
          <td><span class="Style19"><strong>Objectif global:</strong></span><span class="Style16"><?php echo $row_lobjectif['intitule_objectif_global']; ?></span></td>
        </tr>
		 <tr>
          <td><span class="Style21">Indicateur CL</span><span class="Style2">:
            
            <em><?php echo $row_indicateur_courant['intitule_indicateur_objectif_global']; ?></em></span></td>
        </tr>
		 <tr>
          <td><span class="Style21">Indicateur impact</span><span class="Style2">:
            
            <em><?php echo $row_indicateur_courant['intitule_indicateur_cmr_og']; ?></em></span></td>
        </tr>
      <tr>
        <td valign="top"><table border="0" align="center" cellspacing="1">

          <?php if($totalRows_indicateur_courant>0) {?>
          <tr class="titrecorps2" >
            <td><span class="Style18">&nbsp; </span></td>
            <td><div align="center"><span class="Style18">&nbsp;&nbsp;</span></div></td>
            </tr>
         
          <tr><form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3" <?php if($row_indicateur_courant['source']=="") {?>onSubmit="return verifform(this,1);" <?php } ?> enctype="multipart/form-data">
            <td><div align="center"><span class="Style18">Cible DCP </span></div></td>
            <td>&nbsp;&nbsp;<strong>
              <?php if(isset($row_indicateur_courant['cible_cmr']))   echo $row_indicateur_courant['cible_cmr']." (".$row_indicateur_courant['unite'].")";?>
            </strong></td>
           
         
          <tr>
            <td><div align="right"><span class="Style18">R&eacute;alis&eacute;e (Mi parcours)</span></div></td>
            <td>
              <div align="left">
                <input type="text" style="text-align:center" name="valeur_reelle1" value="<?php  if(isset($row_indicateur_courant['valeur_reelle1']))   echo $row_indicateur_courant['valeur_reelle1']; //else echo "0"; ?>" size="8" />
                </div></td>
            </tr>
            <tr>
            <td><div align="right"><span class="Style18">R&eacute;alis&eacute;e (Fin projet)</span></div></td>
            <td>
              <div align="left">
                <input type="text" style="text-align:center" name="valeur_reelle" value="<?php  if(isset($row_indicateur_courant['valeur_reelle']))   echo $row_indicateur_courant['valeur_reelle']; //else echo "0"; ?>" size="8" />
                <input type="hidden" name="<?php echo "MM_insert";  ?>" value="form3" />
                </div></td>
            </tr>
          <tr>
            <td><div align="right"><span class="Style18">Sources</span></div></td>
            <td><div align="left">
              <?php  if(isset($row_indicateur_courant['source'])) { $rep="../attachment/"; $extension=substr(strrchr($row_indicateur_courant['source'], '.')  ,1); if ($extension=="doc" || $extension=="docx") { echo("<a href='".$rep.$row_indicateur_courant['source']."'><img src='../images/doc.jpg' width='15'/> </a>");
										} elseif ($extension=="xls" || $extension=="xlsx") { echo("<a href='".$rep.$row_indicateur_courant['source']."'><img src='../images/xls.jpg' width='15'/> </a>");} elseif ($extension=="pdf") { echo("<a href='".$rep.$row_indicateur_courant['source']."'><img src='../images/pdf.jpg' width='15'/> </a>");} elseif ($extension=="zip") { echo("<a href='".$rep.$row_indicateur_courant['source']."'><img src='../images/zipicon.jpg' width='15'/> </a>");
			} elseif ($extension=="jpg") { echo("<a href='".$rep.$row_indicateur_courant['source']."'><img src='../images/overedit.png' width='15'/> </a>");
										} }?>
              <input type="file" name="fichier1" id="fichier1" size="5" />
              <input type="hidden" name="MAX_FILE_SIZE"  value="20485760" />
            </div></td>
            </tr>
          <tr>
            <td>Observations</td>
            <td><textarea name="commentaire" cols="30" rows="2" style="text-align:left"><?php  if(isset($row_indicateur_courant['observation']))   echo $row_indicateur_courant['observation']; //else echo "0"; ?>
            </textarea></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input name="Envoyer" type="submit"  value="Valider" style="background-color:#FFFF00" />
              <!--<input name="Submit" type="reset" class="inputsubmit" value="Quitter" onclick="parent.tb_remove();" />--></td>
            </tr>
         </form>
         
		   <tr class="titrecorps2" >
            <td >&nbsp;</td>
            <td > <div align="right">&nbsp;</div></td>
            </tr>
          <?php } ?>
        </table></td>
        </tr>
        <tr>
          <td><div align="center">&nbsp;&nbsp;          </div></td>
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