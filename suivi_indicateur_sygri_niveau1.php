<?php session_start(); require_once('Connections/pdar_connexion.php'); $l=8; //if(!isset($_SESSION["id_projet"])) $_SESSION["id_projet"]=0; ?>
<?php
//fonction calcul nb jour
function NbJours($debut, $fin) {

  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);

  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - 
          mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
  
  return(($diff / 86400)+1);

}
//
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

//annee en cours
  if(isset($_GET['annee'])) $annee=$_GET['annee']; else $annee=date("Y");
//annee precedent
 $anneep=$annee-1;
//  

/*if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
$id_ind_sygri=$_POST['id_ind_sygri'];
$valprev=$_POST['valprev'];
$valreal=$_POST['valreal'];
//suppression
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sup_indicateur = "DELETE FROM suivi_indicateur_sygri_niveau1 WHERE annee='$annee' and indicateur_sygri1 in (select id_indicateur_sygri_niveau1_projet from indicateur_sygri1_projet, sous_composante, composante where sous_composante=id_sous_composante and id_composante=composante and projet='$a')";
$Result1 = mysql_query($query_sup_indicateur, $pdar_connexion) or die(mysql_error());
//
foreach ($id_ind_sygri as $key => $value)
{
	if(isset($valprev[$key]) && $valprev[$key]!=NULL && isset($valreal[$key]) && $valreal[$key]!=NULL) {
  $insertSQL = sprintf("INSERT INTO suivi_indicateur_sygri_niveau1  (annee, indicateur_sygri1, prevision, realisation, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, '$personnel', '$date')",
					   GetSQLValueString($annee, "int"),
					   GetSQLValueString($id_ind_sygri[$key], "int"),
					   GetSQLValueString($valprev[$key], "int"),
					   GetSQLValueString($valreal[$key], "int"));
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    }
  }
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?annee=$annee&insert=ok"; else $insertGoTo .= "?annee=$annee&insert=no"; 
  header(sprintf("Location: %s", $insertGoTo));
}*/


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_max_annee = "SELECT max(annee) as mannee FROM annee";
$max_annee  = mysql_query($query_max_annee , $pdar_connexion) or die(mysql_error());
$row_max_annee  = mysql_fetch_assoc($max_annee);
$totalRows_max_annee  = mysql_num_rows($max_annee);


// composante
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SELECT * FROM composante order by id_composante";
$liste_cp  = mysql_query($query_liste_cp , $pdar_connexion) or die(mysql_error());
$row_liste_cp  = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp  = mysql_num_rows($liste_cp);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_volet = "SELECT * FROM volet_sygri order by ordre";
$liste_volet  = mysql_query($query_liste_volet , $pdar_connexion) or die(mysql_error());
$row_liste_volet  = mysql_fetch_assoc($liste_volet);
$totalRows_liste_volet  = mysql_num_rows($liste_volet);



/*if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sup_indicateur = "DELETE FROM suivi_indicateur_sygri_niveau1 WHERE id_suivi_indicateur_sygri1='$id'";
$Result1 = mysql_query($query_sup_indicateur, $pdar_connexion) or die(mysql_error());
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?annee=$annee&del=ok"; else $insertGoTo .= "?annee=$annee&del=no";
  header(sprintf("Location: %s", $insertGoTo));
}*/


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Syst&egrave;me de Suivi &amp; Evaluation <?php if(isset($_SESSION['projet'])) echo 'du '.$_SESSION['projet']; ?>  | Resultats du projet</title>
<link rel="shortcut icon" href="images/favico.ico" >
<link rel="stylesheet" href="css/cbcscbindex.css" type="text/css" >
<link rel="stylesheet" href="css/css.css" type="text/css" >
<script type="text/javascript" src="script/function.js"></script>
<script type="text/javascript" src="script/iepngfix_tilebg.js"></script>
<SCRIPT LANGUAGE="JavaScript">

function verif_avis() {
 if(document.form1.type.value =="Exploitation Agricole") {
 document.form1.superficie.disabled = false; 
 // alert("Message: Veillez remplir correctement le formulaire !");
  //return false;
 }
 else 
 {
  document.form1.superficie.disabled = true;
 }
 
}

//-->
</SCRIPT>
<style type="text/css">
<!--
body {
	background-color: #D2E2B1;
}
.Style5 {font-size: 12px}
.Style13 {font-size: 12px; font-weight: bold; }
.Style16 {
	font-size: 12px;
	color: #C00000;
	font-weight: bold;
}
.Style4 {font-size: 14px}
-->
</style>
</head>

<body>
<?php include ("content/tete.php"); ?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="200" valign="top">
    <div id="menu">
      <?php include("content/sous_menu_suivi_technique.php"); ?>
    <hr>
    <img src="images/img_stock.png" width="100" height="100" alt="" />    </div>
    </td>
    <td valign="top">
      <?php if(isset($_SESSION['clp_id'])) echo '<div id="corps" align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="titrecorps"><tr><td valign="middle" width="50%" ><h4><span class=\"Style9\" style="color:#336666">Suivi Technique ></span><span class=\"Style9\" style="color:#FF0000">Suivi annuel: SYGRI 1er Niveau</span></h4></td><td valign="middle" align="right"><h4>Bienvenue '.$_SESSION['clp_nom'].' | <a href="logout.php" title="Fermer la session">D&eacute;connexion</a></h4></td></tr></table>'; else { ?>
    <div id="corps" align="center">
      <h3 class="titrecorps">R&eacute;sultats du projet: SYGRI 1er Niveau </h3>
      <?php } ?>
<?php if(isset($_SESSION['clp_id'])) { ?>
<div class="contenu">
<div id="msg" align="center" class="red"></div>
        <table width="100%" border="0" align="center" cellspacing="0">
          <tr>
            <td valign="top">              <div align="center">
              <table border="0" width="90%" cellspacing="3">
			  
			   <tr bgcolor="#D9D9D9">
			     <td valign="middle"><?php include("content/annee.php"); ?></td>
			     </tr>
				
				<?php  if(isset($annee)) { ?>
                <tr align="left">
                  <td> 
                     <div align="center"><a title="Annuler" href="<?php echo $_SERVER['PHP_SELF']."?annee=$annee"?>"></a>
                       <table border="1" align="center" cellspacing="0">
                      <tr bgcolor="#CCCCCC">
                        <td nowrap="nowrap" width="20%">&nbsp;</td>
                        <td nowrap="nowrap">&nbsp;</td>
                      </tr>
                       <?php if($totalRows_liste_volet>0) {$o2=0; $t=0; do { ?>
                      <tr <?php if($o2%2==0) echo 'bgcolor="#FFF"'; $o2=$o2+1;?>>
                        <td align="left" valign="middle"><div align="left"><span class="Style4"><?php echo $row_liste_volet['volet']; ?></span> <br />
                        </div></td>
						                      <?php 
		    $id_v=$row_liste_volet['id_volet'];
			mysql_select_db($database_pdar_connexion, $pdar_connexion);
				$query_liste_indicateur1 = "SELECT id_indicateur_sygri_niveau1_projet, cible_projet, intitule_indicateur_sygri_fida, indicateur_sygri_fida.unite, type FROM indicateur_sygri1_projet, indicateur_sygri_fida where  indicateur_sygri_niveau1=id_indicateur_sygri_fida and volet='$id_v' and niveau_sygri=1 order by ordre";
			$liste_indicateur1 = mysql_query($query_liste_indicateur1, $pdar_connexion) or die(mysql_error());
			$row_liste_indicateur1 = mysql_fetch_assoc($liste_indicateur1);
			$totalRows_liste_indicateur1 = mysql_num_rows($liste_indicateur1);
				  ?>

                        <td valign="top"><table width="100%" border="0" cellspacing="3">
                          <?php $t=0;  if($totalRows_liste_indicateur1>0) { ?>
                          <tr class="titrecorps2">
                            <td width="30%"><span class="Style13">&nbsp;Indicateur&nbsp;</span></td>
                            <td width="10%"><span class="Style13">&nbsp;Unit&eacute;&nbsp;</span></td>
                            <td width="10%"><span class="Style13">&nbsp;<?php echo "Prévision ".$annee; ?>&nbsp; </span></td>
                            <td width="10%"><span class="Style13">&nbsp;<?php echo "Réalisation ".$annee; ?>&nbsp; </span></td>
                            <td width="10%"><span class="Style13"> &nbsp;&nbsp;</span><span class="Style13">Pr&eacute;vision projet </span></td>
                            <td width="10%"><span class="Style13">R&eacute;alisation cumul&eacute;e </span></td>
                            <td bgcolor="#000000" width="10%"><span class="Style13">&nbsp;&nbsp;<?php echo "% exe ".$annee; ?>&nbsp;&nbsp; </span></td>
                            <td bgcolor="#000000" width="10%"><span class="Style13">&nbsp;&nbsp;% exe Projet&nbsp;&nbsp; </span></td>
                          </tr>
                          <?php $t=0; $i=0;do { ?>
                          <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"';  $t=$t+1;?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='<?php if($i%2==0) echo '#ECF0DF';?>';" <?php $i=$i+1;?> >
                            <td>
                               <span class="Style5"><?php echo $row_liste_indicateur1['intitule_indicateur_sygri_fida']; ?></span></td>
                            <td><div align="center"><span class="Style5"><?php echo $row_liste_indicateur1['unite']; ?></span></div></td>
                            <?php 
						//semestre courant
						$indic1=$row_liste_indicateur1['id_indicateur_sygri_niveau1_projet'];
						if($row_liste_indicateur1['type']=='unique') {
						
						mysql_select_db($database_pdar_connexion, $pdar_connexion);
  		                $query_cible_ptba1 = "SELECT sum(valeur_cible) AS cible_ind_ptba FROM indicateur_tache, ptba
					    WHERE  indicateur_sygri='$indic1' AND id_ptba=indicateur_tache.activite and annee ='$annee'";
						$cible_ptba1  = mysql_query($query_cible_ptba1 , $pdar_connexion) or die(mysql_error());
						$row_cible_ptba1 = mysql_fetch_assoc($cible_ptba1 );
						$totalRows_cible_ptba1 = mysql_num_rows($cible_ptba1 );
						
						
													
						mysql_select_db($database_pdar_connexion, $pdar_connexion);
  		                $query_realise_ptba1 = "SELECT max( valeur_suivi ) AS realise FROM suivi_indicateur_tache, indicateur_tache, ptba
					    WHERE indicateur_sygri ='$indic1' and id_indicateur_tache = suivi_indicateur_tache.indicateur AND id_ptba=indicateur_tache.activite and annee ='$annee'";
						$realise_ptba1  = mysql_query($query_realise_ptba1 , $pdar_connexion) or die(mysql_error());
						$row_realise_ptba1 = mysql_fetch_assoc($realise_ptba1 );
						$totalRows_realise_ptba1 = mysql_num_rows($realise_ptba1 );
						
						//cumul réalisé indicateur volet
						mysql_select_db($database_pdar_connexion, $pdar_connexion);
						$query_realisation_cumul1 = "SELECT max( valeur_suivi ) AS realise, id_indicateur_tache FROM suivi_indicateur_tache, indicateur_tache, ptba
					    WHERE indicateur_sygri='$indic1' and id_indicateur_tache = suivi_indicateur_tache.indicateur AND id_ptba=indicateur_tache.activite and annee <='$annee' group by id_indicateur_tache";
						//$query_p_cumul = "SELECT sum(realisation) as cumul_realisation FROM   suivi_indicateur_sygri_niveau1 where indicateur_sygri1='$indic'";
						$realisation_cumul1   = mysql_query($query_realisation_cumul1, $pdar_connexion) or die(mysql_error());
						$row_realisation_cumul1 = mysql_fetch_assoc($realisation_cumul1 );
						$totalRows_realisation_cumul1 = mysql_num_rows($realisation_cumul1 );
						$ttreal1=0;
						if(isset($totalRows_realisation_cumul1) && $totalRows_realisation_cumul1>0) {
						 do { 
							
		  						$ttreal1=$ttreal1+$row_realisation_cumul1['realise'];
           						} while ($row_realisation_cumul1 = mysql_fetch_assoc($realisation_cumul1)); mysql_free_result($realisation_cumul1);
								
						}
						}elseif($row_liste_indicateur1['type']=='somme') {
						
						mysql_select_db($database_pdar_connexion, $pdar_connexion);
  		                $query_cible_ptba1 = "SELECT  sum(valeur_cible) AS cible_ind_ptba FROM   indicateur_tache, somme_indicateur_sygri, ptba 
						where id_ptba=indicateur_tache.activite and annee ='$annee' 
						and  (indicateur_sygri=indicateur1 or indicateur_sygri=indicateur2)
						and somme_indicateur_sygri.indicateur='$indic1'";
						$cible_ptba1   = mysql_query($query_cible_ptba1 , $pdar_connexion) or die(mysql_error());
						$row_cible_ptba1 = mysql_fetch_assoc($cible_ptba1 );
						$totalRows_cible_ptba1 = mysql_num_rows($cible_ptba1 );
						
						
													
						mysql_select_db($database_pdar_connexion, $pdar_connexion);
  		                $query_realise_ptba1 = "SELECT max( valeur_suivi ) AS realise FROM somme_indicateur_sygri, suivi_indicateur_tache, indicateur_tache, ptba
					    WHERE (indicateur_sygri=indicateur1 or indicateur_sygri=indicateur2) and  somme_indicateur_sygri.indicateur='$indic1' and id_indicateur_tache = suivi_indicateur_tache.indicateur AND id_ptba=indicateur_tache.activite and annee ='$annee'";
						$realise_ptba1  = mysql_query($query_realise_ptba1 , $pdar_connexion) or die(mysql_error());
						$row_realise_ptba1 = mysql_fetch_assoc($realise_ptba1 );
						$totalRows_realise_ptba1 = mysql_num_rows($realise_ptba1 );
						
						//cumul réalisé indicateur volet
						mysql_select_db($database_pdar_connexion, $pdar_connexion);
						$query_realisation_cumul1 = "SELECT max( valeur_suivi ) AS realise, id_indicateur_tache FROM suivi_indicateur_tache, somme_indicateur_sygri, 
						indicateur_tache, ptba
					    WHERE (indicateur_sygri=indicateur1 or indicateur_sygri=indicateur2) 
						and somme_indicateur_sygri.indicateur='$indic1' and id_indicateur_tache = suivi_indicateur_tache.indicateur 
						AND id_ptba=indicateur_tache.activite and annee <='$annee' group by id_indicateur_tache";
						//$query_p_cumul = "SELECT sum(realisation) as cumul_realisation FROM   suivi_indicateur_sygri_niveau1 where indicateur_sygri1='$indic'";
						$realisation_cumul1  = mysql_query($query_realisation_cumul1, $pdar_connexion) or die(mysql_error());
						$row_realisation_cumul1 = mysql_fetch_assoc($realisation_cumul1 );
						$totalRows_realisation_cumul1 = mysql_num_rows($realisation_cumul1 );
						$ttreal1=0;
						if(isset($totalRows_realisation_cumul1) && $totalRows_realisation_cumul1>0) {
						 do { 
							
		  						$ttreal1=$ttreal1+$row_realisation_cumul1['realise'];
           						} while ($row_realisation_cumul1 = mysql_fetch_assoc($realisation_cumul1)); mysql_free_result($realisation_cumul1);
								
						}
						
						
						
						}
						
						?>
                            <td><div align="center"><span class="Style16">
                                <?php if(isset($row_cible_ptba1['cible_ind_ptba'])) { echo $row_cible_ptba1['cible_ind_ptba'];} ?>
                               
                            </span></div></td>
                            <td><div align="center" class="Style16">
                              <?php if(isset($row_realise_ptba1['realise'])) { echo $row_realise_ptba1['realise'];} ?>
                            </div></td>
                            <td ><div align="center"> <strong>  </strong><u><span class="Style5">
                              <?php if(isset($row_liste_indicateur1['cible_projet'])) { echo $row_liste_indicateur1['cible_projet'];} ?>
                            </span></u></div></td>
                            <td ><div align="center"><u><span class="Style5">
                              <?php if(isset($ttreal)) { echo $ttreal;} ?>
                            </span></u></div></td>
                            <td align="center" valign="middle" ><div align="center"><strong><span class="Style5">
                              <?php if(isset($row_cible_ptba1['cible_ind_ptba']) && $row_cible_ptba1['cible_ind_ptba']>0) echo  number_format((100*$row_realise_ptba1['realise']/$row_cible_ptba1['cible_ind_ptba']), 1, ',', ' ')." %"; ?>
                            </span></strong></div></td>
                            <td ><div align="center"><strong><span class="Style5">
                              <?php if(isset($row_liste_indicateur1['cible_projet']) && $row_liste_indicateur1['cible_projet']>0) echo  number_format((100*$ttreal1/$row_liste_indicateur1['cible_projet']), 2, ',', ' ')." %"; ?>
                            </span></strong></div></td>
                            <td bgcolor="#D9D9D9">&nbsp;</td>
                          </tr>
                          <?php } while ($row_liste_indicateur1 = mysql_fetch_assoc($liste_indicateur1));  mysql_free_result($realise_ptba1);  mysql_free_result($cible_ptba1);   mysql_free_result($liste_indicateur1);?>
                          <?php } ?>
                        </table></td>
                      </tr>
                      <?php } while ($row_liste_volet = mysql_fetch_assoc($liste_volet)); mysql_free_result($liste_volet); ?>
                      <?php } ?>
                      <tr bgcolor="#CCCCCC">
                        <td nowrap="nowrap" ><strong>Composante</strong></td>
                        <td nowrap="nowrap"><div align="center"><strong>Indicateurs par sous / composante </strong> </div></td>
                      </tr>
                      <?php if($totalRows_liste_cp>0) {$o2=0; $t=0; do { ?>
                      <tr <?php if($o2%2==0) echo 'bgcolor="#FFF"'; $o2=$o2+1;?>>
                        <td align="left" valign="middle"><div align="left"><span class="Style4"><?php echo $row_liste_cp['id_composante'].": ".$row_liste_cp['intitule_composante']; ?></span> <br />
                        </div></td>
						                      <?php 
		    $id_cp=$row_liste_cp['id_composante'];
			mysql_select_db($database_pdar_connexion, $pdar_connexion);
			$query_liste_indicateur = "SELECT id_indicateur_sygri_niveau1_projet, id_sous_composante, intitule_sous_composante, intitule_indicateur_sygri_fida, indicateur_sygri_fida.unite, cible_projet, type FROM indicateur_sygri1_projet, sous_composante, indicateur_sygri_fida where sous_composante=id_sous_composante and indicateur_sygri_niveau1=id_indicateur_sygri_fida and composante='$id_cp' and niveau_sygri=1 order by id_sous_composante, ordre";
			$liste_indicateur = mysql_query($query_liste_indicateur, $pdar_connexion) or die(mysql_error());
			$row_liste_indicateur = mysql_fetch_assoc($liste_indicateur);
			$totalRows_liste_indicateur = mysql_num_rows($liste_indicateur);
				  ?>

                        <td valign="top"><table width="100%" border="0" cellspacing="3">
                          <?php $t=0;  if($totalRows_liste_indicateur>0) { ?>
                          <tr class="titrecorps2">
                            <td width="30%"><span class="Style13">&nbsp;Indicateur&nbsp;</span></td>
                            <td width="10%"><span class="Style13">&nbsp;Unit&eacute;&nbsp;</span></td>
                            <td width="10%"><span class="Style13">&nbsp;<?php echo "Prévision ".$annee; ?>&nbsp; </span></td>
                            <td width="10%"><span class="Style13">&nbsp;<?php echo "Réalisation ".$annee; ?>&nbsp; </span></td>
                            <td width="10%"><span class="Style13"> &nbsp;&nbsp;</span><span class="Style13">Pr&eacute;vision projet </span></td>
                            <td width="10%"><span class="Style13">R&eacute;alisation cumul&eacute;e </span></td>
                            <td width="10%" bgcolor="#000000" ><span class="Style13">&nbsp;&nbsp;<?php echo "% exe ".$annee; ?>&nbsp;&nbsp; </span></td>
                            <td width="10%" bgcolor="#000000" ><span class="Style13">&nbsp;&nbsp;% exe Projet&nbsp;&nbsp; </span></td>
                          </tr>
                          <?php $p1="j"; $t=0; $i=0;do { ?>
                          <?php  if($p1!=$row_liste_indicateur['id_sous_composante']) {?>
                          <tr bgcolor="#ECF000">
                            <td colspan="8" align="center" bgcolor="#D2E2B1"><div align="left" class="Style5"><strong> <u>
                                <?php  if($p1!=$row_liste_indicateur['id_sous_composante']) {echo $row_liste_indicateur['id_sous_composante'].": ".$row_liste_indicateur['intitule_sous_composante']; $i=0; }$p1=$row_liste_indicateur['id_sous_composante']; ?>
                            </u> </strong></div></td>
                          </tr>
                          <?php } ?>
                          <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"';  $t=$t+1;?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='<?php if($i%2==0) echo '#ECF0DF';?>';" <?php $i=$i+1;?>>
                            <td>
                               <span class="Style5"><?php echo $row_liste_indicateur['intitule_indicateur_sygri_fida']; ?></span></td>
                            <td><div align="center"><span class="Style5"><?php echo $row_liste_indicateur['unite']; ?></span></div></td>
                            <?php 
						//semestre courant
						$indic=$row_liste_indicateur['id_indicateur_sygri_niveau1_projet'];
						if($row_liste_indicateur['type']=='unique') {
						mysql_select_db($database_pdar_connexion, $pdar_connexion);
  		                $query_cible_ptba = "SELECT sum(valeur_cible) AS cible_ind_ptba FROM indicateur_tache, ptba
					    WHERE indicateur_sygri='$indic' AND id_ptba=indicateur_tache.activite and annee ='$annee'";
						$cible_ptba  = mysql_query($query_cible_ptba , $pdar_connexion) or die(mysql_error());
						$row_cible_ptba = mysql_fetch_assoc($cible_ptba );
						$totalRows_cible_ptba = mysql_num_rows($cible_ptba );
						
						
													
						mysql_select_db($database_pdar_connexion, $pdar_connexion);
  		                $query_realise_ptba = "SELECT max( valeur_suivi ) AS realise FROM suivi_indicateur_tache, indicateur_tache, ptba
					    WHERE indicateur_sygri='$indic' and id_indicateur_tache = suivi_indicateur_tache.indicateur AND id_ptba=indicateur_tache.activite and annee ='$annee'";
						$realise_ptba  = mysql_query($query_realise_ptba , $pdar_connexion) or die(mysql_error());
						$row_realise_ptba = mysql_fetch_assoc($realise_ptba );
						$totalRows_realise_ptba = mysql_num_rows($realise_ptba );
						
						//cumul réalisé indicateur sygri sous/composante
						mysql_select_db($database_pdar_connexion, $pdar_connexion);
						$query_realisation_cumul = "SELECT max( valeur_suivi ) AS realise, id_indicateur_tache FROM suivi_indicateur_tache, indicateur_tache, ptba
					    WHERE indicateur_sygri='$indic' and id_indicateur_tache = suivi_indicateur_tache.indicateur AND id_ptba=indicateur_tache.activite and annee <='$annee'
						 group by id_indicateur_tache";
						//$query_p_cumul = "SELECT sum(realisation) as cumul_realisation FROM   suivi_indicateur_sygri_niveau1 where indicateur_sygri1='$indic'";
						$realisation_cumul  = mysql_query($query_realisation_cumul, $pdar_connexion) or die(mysql_error());
						$row_realisation_cumul = mysql_fetch_assoc($realisation_cumul );
						$totalRows_realisation_cumul = mysql_num_rows($realisation_cumul );
						$ttreal=0;
						if(isset($totalRows_realisation_cumul) && $totalRows_realisation_cumul>0) {
						 do { 
							
		  						$ttreal=$ttreal+$row_realisation_cumul['realise'];
           						} while ($row_realisation_cumul = mysql_fetch_assoc($realisation_cumul)); mysql_free_result($realisation_cumul);
								
						}

						}elseif($row_liste_indicateur['type']=='somme') {
						
						
						mysql_select_db($database_pdar_connexion, $pdar_connexion);
  		                $query_cible_ptba = "SELECT  sum(valeur_cible) AS cible_ind_ptba FROM   indicateur_tache, somme_indicateur_sygri, ptba 
						where id_ptba=indicateur_tache.activite and annee ='$annee' 
						and  (indicateur_sygri=indicateur1 or indicateur_sygri=indicateur2)
						and somme_indicateur_sygri.indicateur='$indic1'";
						$cible_ptba  = mysql_query($query_cible_ptba , $pdar_connexion) or die(mysql_error());
						$row_cible_ptba = mysql_fetch_assoc($cible_ptba );
						$totalRows_cible_ptba = mysql_num_rows($cible_ptba );
						
						
													
						mysql_select_db($database_pdar_connexion, $pdar_connexion);
  		                $query_realise_ptba = "SELECT max( valeur_suivi ) AS realise FROM somme_indicateur_sygri, suivi_indicateur_tache, indicateur_tache, ptba
					    WHERE (indicateur_sygri=indicateur1 or indicateur_sygri=indicateur2) and  somme_indicateur_sygri.indicateur='$indic1' and id_indicateur_tache = suivi_indicateur_tache.indicateur AND id_ptba=indicateur_tache.activite and annee ='$annee'";
						$realise_ptba  = mysql_query($query_realise_ptba , $pdar_connexion) or die(mysql_error());
						$row_realise_ptba = mysql_fetch_assoc($realise_ptba );
						$totalRows_realise_ptba = mysql_num_rows($realise_ptba );
						               
						//cumul réalisé indicateur volet
						mysql_select_db($database_pdar_connexion, $pdar_connexion);
						$query_realisation_cumul = "SELECT max( valeur_suivi ) AS realise, id_indicateur_tache FROM suivi_indicateur_tache, somme_indicateur_sygri, 
						indicateur_tache, ptba
					    WHERE (indicateur_sygri=indicateur1 or indicateur_sygri=indicateur2) 
						and somme_indicateur_sygri.indicateur='$indic1' and id_indicateur_tache = suivi_indicateur_tache.indicateur 
						AND id_ptba=indicateur_tache.activite and annee <='$annee' group by id_indicateur_tache";
						//$query_p_cumul = "SELECT sum(realisation) as cumul_realisation FROM   suivi_indicateur_sygri_niveau1 where indicateur_sygri1='$indic'";
						$realisation_cumul  = mysql_query($query_realisation_cumul, $pdar_connexion) or die(mysql_error());
						$row_realisation_cumul = mysql_fetch_assoc($realisation_cumul );
						$totalRows_realisation_cumul = mysql_num_rows($realisation_cumul );
						$ttreal=0;
						if(isset($totalRows_realisation_cumul) && $totalRows_realisation_cumul>0) {
						 do { 
							
		  						$ttreal=$ttreal+$row_realisation_cumul['realise'];
           						} while ($row_realisation_cumul = mysql_fetch_assoc($realisation_cumul)); mysql_free_result($realisation_cumul);
								
						}
						
						}
						?>
                            <td><div align="center"><span class="Style16">
                                <?php if(isset($row_cible_ptba['cible_ind_ptba'])) { echo $row_cible_ptba['cible_ind_ptba'];} ?>
                               
                            </span></div></td>
                            <td><div align="center" class="Style16">
                              <?php if(isset($row_realise_ptba['realise'])) { echo $row_realise_ptba['realise'];} ?>
                            </div></td>
                            <td ><div align="center"> <strong>  </strong><u><span class="Style5">
                              <?php if(isset($row_liste_indicateur['cible_projet'])) { echo $row_liste_indicateur['cible_projet'];} ?>
                            </span></u></div></td>
                            <td ><div align="center"><u><span class="Style5">
                              <?php if(isset($ttreal)) { echo $ttreal;} ?>
                            </span></u></div></td>
                            <td align="center" valign="middle" ><div align="center"><strong><span class="Style5">
                              <?php if(isset($row_cible_ptba['cible_ind_ptba']) && $row_cible_ptba['cible_ind_ptba']>0) echo  number_format((100*$row_realise_ptba['realise']/$row_cible_ptba['cible_ind_ptba']), 1, ',', ' ')." %"; ?>
                            </span></strong></div></td>
                            <td ><div align="center"><strong><span class="Style5">
                              <?php if(isset($row_liste_indicateur['cible_projet']) && $row_liste_indicateur['cible_projet']>0) echo  number_format((100*$ttreal/$row_liste_indicateur['cible_projet']), 2, ',', ' ')." %"; ?>
                            </span></strong></div></td>
                            <td bgcolor="#D9D9D9">&nbsp;</td>
                          </tr>
                          <?php } while ($row_liste_indicateur = mysql_fetch_assoc($liste_indicateur));  mysql_free_result($realise_ptba);  mysql_free_result($cible_ptba);  mysql_free_result($liste_indicateur); ?>
                          <?php } ?>
                        </table></td>
                      </tr>
                      <?php } while ($row_liste_cp = mysql_fetch_assoc($liste_cp));  mysql_free_result($liste_cp); ?>
                      <?php } ?>
                    </table>
                     </div>
				  </td>
                  </tr>
				<?php } ?>
              </table>
            </div></td>
            <td valign="top">
              </td>
          </tr>
          <tr>
            <td colspan="2" valign="top"><div align="center">
              </div></td>
            </tr>
        </table>
  </div>
<?php } else { ?>
<div class="contenu" align="center">
  <h1 class="contenuh1">Bienvenue dans le SSE 
    <?php if(isset($_SESSION['projet'])) echo 'du '.$_SESSION['projet']; ?>
  </h1>
  <div id="msg" align="center" class="red"></div>

<?php include ("content/connexion.php"); ?>

<h2 class="contenuh1">
  <?php if(isset($_SESSION['slogan'])) echo $_SESSION['slogan']; ?>
</h2>
</div>

<?php } ?>
<div class="titrecorps"></div>
    </div>

    </td>
  </tr>
  <tr>
    <td colspan="2">
    <div id="pied"><?php include("content/pied.php"); ?>
    </div>
    </td>
  </tr>
</table>
<?php if(isset($_GET['insert'])){ ?>
<script type="text/javascript">
afficher_msg('insert','<?php echo $_GET['insert']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['del'])){ ?>
<script type="text/javascript">
afficher_msg('del','<?php echo $_GET['del']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['update'])){ ?>
<script type="text/javascript">
afficher_msg('update','<?php echo $_GET['update']; ?>');
</script>
<?php }?>


</body>
</html>
<?php
//mysql_free_result($liste_village);
?>
