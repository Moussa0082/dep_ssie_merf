<?php  //ini_set("display_errors",1);
if(!isset($config))
{
  //include_once $path.'system/configuration.php';
  $config = new Config;
}
//header('Content-Type: text/html; charset=UTF-8');

//include_once $path.$config->sys_folder . "/database/db_connexion.php";

if(!isset($tableau_projet))
{
  //projet
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_projet = "SELECT * FROM ".$database_connect_prefix."projet' ";
  $liste_projet  = mysql_query($query_liste_projet , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_projet  = mysql_fetch_assoc($liste_projet);
  $totalRows_liste_projet  = mysql_num_rows($liste_projet);
  $tableau_projet = array();
  if($totalRows_liste_projet>0){ do{
  $tableau_projet[$row_liste_projet["code_projet"]] = array("sigle"=>$row_liste_projet["sigle_projet"],"intitule"=>$row_liste_projet["intitule_projet"]);
  }while($row_liste_projet  = mysql_fetch_assoc($liste_projet));  }
}

$liste_projet_array = array();
foreach($tableau_projet as $a=>$b) $liste_projet_array[] = $a;
number_format(0, 0, ',', ' ');

foreach($liste_projet_array as $projet_code){
  if(!empty($projet_code) && isset($tableau_projet[$projet_code])){
$sigle = $tableau_projet[$projet_code]["sigle"]; $projet = $tableau_projet[$projet_code]["intitule"];

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_conv = "SELECT * FROM ".$database_connect_prefix."suivi_dno ORDER BY id_suivi desc";
$liste_conv = mysql_query($query_liste_conv, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_conv = mysql_fetch_assoc($liste_conv);
$totalRows_liste_conv = mysql_num_rows($liste_conv);
$phase_array = array();
$tableau_date_envoi = array();
$tableau_date_ano = array();
$tableau_date_rejet = array();
$tableau_date_renvoi = array();
$tableau_stat = $tableau_obs = $tableau_persp = array();
$tableau_phase_dano= array();
$tableau_date_phase_dano= array();
$tableau_obs = $tableau_persp = array();
if($totalRows_liste_conv>0){ do{
if(!isset($phase_array[$row_liste_conv["dno"]]))
$phase_array[$row_liste_conv["dno"]] = $row_liste_conv;
if($row_liste_conv["phase"]=="ANO") $tableau_date_ano[$row_liste_conv["dno"]]=$row_liste_conv["date_phase"];
if($row_liste_conv["phase"]=="Envoi au bailleur") $tableau_date_envoi[$row_liste_conv["dno"]]=$row_liste_conv["date_phase"];
  if($row_liste_conv["phase"]=="Objection du bailleur") $tableau_date_rejet[$row_liste_conv["dno"]]=$row_liste_conv["date_phase"];
  if($row_liste_conv["phase"]=="Renvoi au bailleur") $tableau_date_renvoi[$row_liste_conv["dno"]]=$row_liste_conv["date_phase"];

if(!isset($tableau_obs[$row_liste_conv["dno"]])) $tableau_obs[$row_liste_conv["dno"]]="";
$tableau_stat[$row_liste_conv["dno"]]=$row_liste_conv["phase"];
$tableau_persp[$row_liste_conv["dno"]]=$row_liste_conv["observation"];
$tableau_obs[$row_liste_conv["dno"]].="<u>".implode('-',array_reverse(explode('-',$row_liste_conv["date_phase"])))."</u>: (<b>".$row_liste_conv["phase"]."</b>)<i> ".$row_liste_conv["observation"]."&nbsp;    </br></i>";

$tableau_phase_dano[$row_liste_conv["dno"]]=$row_liste_conv["phase"];
$tableau_date_phase_dano[$row_liste_conv["dno"]]=$row_liste_conv["date_phase"];

if(!isset($tableau_obs[$row_liste_conv["dno"]])) $tableau_obs[$row_liste_conv["dno"]]="";
$tableau_persp[$row_liste_conv["dno"]]=$row_liste_conv["observation"];
}while($row_liste_conv = mysql_fetch_assoc($liste_conv)); }

mysql_select_db($database_pdar_connexion, $pdar_connexion); //and traitement=0
$query_liste_dno = "SELECT * FROM ".$database_connect_prefix."dno where numero in (SELECT dno FROM ".$database_connect_prefix."suivi_dno GROUP BY dno ORDER BY id_suivi desc) and projet='".$projet_code."' ORDER BY numero desc";
$liste_dno = mysql_query($query_liste_dno, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_dno = mysql_fetch_assoc($liste_dno);
$totalRows_liste_dno = mysql_num_rows($liste_dno);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_filiere = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE projet='".$projet_code."' and niveau=2";
$liste_filiere  = mysql_query($query_liste_filiere , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_filiere  = mysql_fetch_assoc($liste_filiere);
$totalRows_liste_filiere  = mysql_num_rows($liste_filiere);
$codef_array = array();
if($totalRows_liste_filiere>0){ do{ $codef_array[$row_liste_filiere["code"]]=$row_liste_filiere["intitule"]; }while($row_liste_filiere  = mysql_fetch_assoc($liste_filiere)); }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print $config->sitename;?></title>
</head>
<body style="background-image: url(http://www.suivi.prodaf.net/images/ruche.png); background-position: bottom right; background-repeat: no-repeat;">
<style type="text/css">
BODY {font-family:verdana, arial, helvetica, sans-serif;font-size:12px;color:#000000;}
.nofollow {text-decoration: none;}
.h1 { color: #000; padding-bottom: 0px; margin-bottom: 0px;}
table, table tr td { border-collapse: collapse;}
HR.end {width:100%;margin-top:10px;margin-bottom:0px;border-left:#fff;border-right:#fff;border-top:#fff;border-bottom:2px dotted #ccc;}
.subHeadingEoa {font-family:verdana, arial, helvetica, sans-serif;font-size:15px;font-weight:bold;color:#000000;}
.heading {font-family:verdana, arial, helvetica, sans-serif;font-size:18px;font-weight:bold;color:#003366;}
.Footer {font-family:verdana, arial, helvetica, sans-serif;font-size:11px;color:#aaaaaa;}
</style>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody>
<tr valign="top">
<td align="center"><a class="nofollow" rel="nofollow" title="PNF" target="_blank" href="http://www.suivi.prodaf.net/"><img src="http://www.suivi.prodaf.net/images/bg1.png" border="0" alt="PNF" width="150" height="60"></a>
<a class="nofollow" rel="nofollow" target="_blank" href="http://ruche-pnf.org/"><img src="http://ruche-pnf.org/new/images/bg3.png" border="0" alt="PNF" width="250" height="60" usemap="#m_slogo"></a>
<map name="m_slogo" id="m_slogo">
<area shape="rect" coords="0,0,50,60" href="https://www.i3n.org/" target="_blank" title="I3N" alt="GEF" />
<area shape="rect" coords="50,0,100,60" href="http://www.ifad.org/" target="_blank" title="FIDA - IFAD" alt="FIDA" />
<area shape="rect" coords="50,0,150,60" href="http://www.gef.org/" target="_blank" title="GEF" alt="GEF" />
<area shape="rect" coords="50,0,200,60" href="www.banquemondiale.org/" target="_blank" title="Banque Mondiale" alt="Banque Mondiale" />
<!--<area shape="rect" coords="0,0,200,60" href="http://www.niger.ng/" target="_blank" title="République du Niger" alt="NG" />-->
</map>
</td>
</tr>
</tbody>
</table>

<h1 style="width: 98%;" align="center" class="h1">PROJET <?php echo $sigle." : ".(isset($structure_array_val[$st_val])?$structure_array_val[$st_val]:$key[$st_val]); ?></h1>
<h2 style="width: 98%;" class="h1" align="center"><?php echo $projet; ?></h2>
<hr style="width: 98%;" color="#008000" />
<div style="width: 98%; margin: auto; padding: auto;">
<div class="heading" align="center"><?php echo ($total==0)?"":"Voici les dilligences de la base de données du PNF. <a href='http://www.suivi.prodaf.net' target='_blank' title='Lien vers la base de données du PNF'>Cliquez ici</a> pour acceder à la base de données du PNF"; ?></div>
<br><br>
<?php echo "<h2 style='margin: 0px; padding: 0px;'>Bonjour, Voici le log des DANO de la base de données du PNF</h2>"; ?>

<div class="well well-sm"><strong>Tableau de bord de suivi des DANO en instance, actualis&eacute; au <?php echo date("d/m/Y"); ?></strong></div>

<h3>1.	DANO en instance chez les PTFs</h3>

<table width="100%" border="1" cellspacing="0" class="">
<thead>
<tr bgcolor="#E4E4E4">
  <td rowspan="2"><center>N&deg;<br/>DANO </center></td>
  <td rowspan="2">Fili&egrave;re</td>
  <td rowspan="2">Objet</td>
  <td rowspan="2" widtd="80"><center>Date de <br/>soumission </center></td>
  <td rowspan="2" widtd="80"><center>Date de <br/>r&eacute;soumission </center></td>
  <?php $nb3=$nb35=$nb5=$total=array(); foreach($destinateur_array as $a=>$b){ echo '<td rowspan="2"><center>Avis &agrave; donner par '.$b.'</center></td>'; $nb3[$a]=$nb35[$a]=$nb5[$a]=$total[$a]=0; } ?>
  <td rowspan="2"><center>Observations</center></td>
  <td colspan="3"><div align="center">Statut</div></td>
</tr>
<tr bgcolor="#E4E4E4">
  <td nowrap="nowrap" widtd="60"> &lt;3j </td>
  <td nowrap="nowrap" widtd="60"> 3j&lt; &lt;5j </td>
  <td nowrap="nowrap" widtd="60"> &gt;5j </td>
</tr>
</thead>

<tbody class="">

<?php $totalGeneral = 0; if($totalRows_liste_dno>0) { $i=0; do { $id = $row_liste_dno['numero'];
if(isset($phase_array[$id]) && ($phase_array[$id]["phase"]=='Renvoi au bailleur' || $phase_array[$id]["phase"]=='Envoi au bailleur')) { $row_liste_conv = $phase_array[$id];
$Nombres_jours=0;  number_format($Nombres_jours, 0, ',', ' '); $code_bailleur = 0; $code = "";
/*if(!isset($tableau_phase_dano[$id]) || ($tableau_phase_dano[$id]!="ANO" && $tableau_phase_dano[$id]!="Objection du bailleur") )
{ */
 /*if(isset($tableau_date_envoi[$row_liste_dno['numero']])) $denvoi=$tableau_date_envoi[$row_liste_dno['numero']]; else $denvoi=date("Y-m-d");  if($denvoi>=$row_liste_dno['date_initialisation'])$Nombres_jourse = NbJours($row_liste_dno['date_initialisation'], $denvoi); else $Nombres_jourse="  ???";*/ ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo $row_liste_dno['numero']; ?></td>

<td class=" "><?php if(isset($codef_array[substr($row_liste_dno['code_activite'],0,2)])) echo $codef_array[substr($row_liste_dno['code_activite'],0,2)]; else echo "Non defini"; ?></td>
<td class=" "><?php echo $row_liste_dno['objet']; ?></td>

<td class=" "><?php if(isset($tableau_date_envoi[$row_liste_dno['numero']])) $denvoi=$tableau_date_envoi[$row_liste_dno['numero']]; else $denvoi = $row_liste_dno["date_initialisation"]; echo date_reg($denvoi,'/'); $Nombres_jours=NbJours($denvoi, date("Y-m-d")); ?> </td>

<td class=" "><?php if(isset($tableau_date_renvoi[$id])) { echo date_reg($tableau_date_renvoi[$id],'/'); $Nombres_jours=NbJours($tableau_date_renvoi[$id], date("Y-m-d")); } ?></td>
<?php $exp = (isset($row_liste_dno["destinataire"]) && !empty($row_liste_dno["destinataire"]))?explode(',',$row_liste_dno["destinataire"]):array(); foreach($destinateur_array as $a=>$b){ ?>
<td class=" " style="<?php echo (isset($exp[0]) && $exp[0]==$a)?"background-color: #EEEEEE;":""; ?>"><div align="center"><?php echo (isset($exp[0]) && $exp[0]==$a)?"X":""; if($code_bailleur==0){ $code = (isset($exp[0]) && $exp[0]==$a)?$a:""; if(!empty($code)) $code_bailleur=1; } ?></div></td>
<?php } if(!empty($code)) $total[$code]++; ?>
<td class=" "><?php echo $row_liste_conv['observation'];/*(isset($tableau_persp[$row_liste_dno["numero"]]))?$tableau_persp[$row_liste_dno["numero"]]:"<div align='center'>Aucun suivi</div>"*/; ?></td>
<td class=" " style="<?php echo ($Nombres_jours<3)?"background-color: #9ACD32;":""; ?>"><div align="center"><?php
/*if(isset($tableau_date_rejet[$id]) && isset($tableau_phase_dano[$id]) && $tableau_phase_dano[$id]=='Renvoi au bailleur') $date_envoi=$tableau_date_phase_dano[$id]; elseif(isset($tableau_date_phase_dano[$id]) && isset($tableau_phase_dano[$id]) && $tableau_phase_dano[$id]=='Envoi au bailleur') $date_envoi=$tableau_date_phase_dano[$id]; $Nombres_jours = NbJours($date_envoi, date("Y-m-d"));*/
// Affiche 2

if($Nombres_jours<3) { if(!empty($code)) $nb3[$code]++; echo  "<div style=\"color:#FFFFFF;\">".number_format($Nombres_jours, 0, ',', ' ')."</div>";} ?>
</div></td>

<td class=" " style="<?php echo ($Nombres_jours>=3 && $Nombres_jours<=5)?"background-color: #FFD700;":""; ?>"><div align="center">

  <?php // Affiche 2

if($Nombres_jours>=3 && $Nombres_jours<=5) { if(!empty($code)) $nb35[$code]++; echo  "<div style=\"color:#FFFFFF;\">".number_format($Nombres_jours, 0, ',', ' ')."</div>";} ?>

</div></td>

<td class=" " style="<?php echo ($Nombres_jours>5)?"background-color: #FF0000;":""; ?>"><div align="center">
  <?php

// Affiche 2

if($Nombres_jours>5) { if(!empty($code)) $nb5[$code]++; echo  "<div style=\"color:#FFFFFF;\">".number_format($Nombres_jours, 0, ',', ' ')."</div>";} ?>
</div></td>
</tr>
<?php $totalGeneral++; $i++; } }while($row_liste_dno = mysql_fetch_assoc($liste_dno));
$rows = mysql_num_rows($liste_dno);
  if($rows > 0) {
      mysql_data_seek($liste_dno, 0);
	  $row_liste_dno = mysql_fetch_assoc($liste_dno);
}
} else { ?>

<tr>

<td colspan="<?php echo (9+count($destinateur_array)); ?>"><h2 align="center">Aucune donn&eacute;e !</h2></td>
</tr>

<?php }
if($totalGeneral==0){ ?>
<tr>
<td colspan="<?php echo (9+count($destinateur_array)); ?>"><h2 align="center">Aucune donn&eacute;e !</h2></td>
</tr>
<?php } ?>
</tbody></table>
<?php if($totalGeneral>0) { ?>
<div class="well well-sm"><br>
<div>Synth&egrave;se :
<?php foreach($destinateur_array as $a=>$b){ ?>
<br /><u><strong><?php echo $b; ?> :</strong></u><br />
<b><?php echo $total[$a]; ?></b> en instance dont : <strong><?php echo $nb3[$a]; ?></strong> DANO depuis moins de 3 jours; <strong><?php echo $nb35[$a]; ?></strong> DANO depuis entre 3 et 5 jours; <strong><?php echo $nb5[$a]; ?></strong> DANO depuis plus de 5 jours</div>
<?php } ?>
</div>
<?php } ?>


<h3>2.	DANO en instance  &agrave; l'UCP/AEP pour traitement</h3>

<table width="100%" border="1" cellspacing="0" class="">
<thead>
<tr bgcolor="#E4E4E4">
  <td rowspan="2"><center>N&deg;<br/>DANO </center></td>
  <td rowspan="2">Objet</td>
  <td rowspan="2" widtd="80"><center>Date de <br/>r&eacute;ponse des PTFs</center></td>
  <td rowspan="2"><center>Observations</center></td>
  <td colspan="3"><div align="center">Statut</div></td>
</tr>
<tr role="row" bgcolor="#DCDCDC">
  <td nowrap="nowrap" widtd="60"> &lt;3j </td>
  <td nowrap="nowrap" widtd="60"> 3j&lt; &lt;5j </td>
  <td nowrap="nowrap" widtd="60"> &gt;5j </td>
</tr>
</thead>

<tbody class="">
<?php $total = 0; if($totalRows_liste_dno>0) { $i=$nb3=$nb35=$nb5=$nba=$nbo=0; do { $id = $row_liste_dno['numero']; $Nombres_jours=0; number_format($Nombres_jours, 0, ',', ' ');
if(isset($phase_array[$id]) && $phase_array[$id]["phase"]=='Retour du bailleur'){
$row_liste_conv = $phase_array[$id];
?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo $row_liste_conv['dno']; ?></td>
<td class=" "><?php echo $row_liste_dno['objet']; ?></td>
<td class=" "><?php echo date_reg($row_liste_conv["date_phase"],'/'); $Nombres_jours=NbJours($row_liste_conv["date_phase"], date("Y-m-d")); ?></td>
<td class=" "><?php echo $row_liste_conv['observation']; ?></td>
<td class=" " style="<?php echo ($Nombres_jours<3)?"background-color: #9ACD32;":""; ?>">
<div align="center"><?php
/*if(isset($tableau_date_rejet[$id]) && isset($tableau_phase_dano[$id]) && $tableau_phase_dano[$id]=='Renvoi au bailleur') $date_envoi=$tableau_date_phase_dano[$id]; elseif(isset($tableau_date_phase_dano[$id]) && isset($tableau_phase_dano[$id]) && $tableau_phase_dano[$id]=='Envoi au bailleur') $date_envoi=$tableau_date_phase_dano[$id]; $Nombres_jours = NbJours($date_envoi, date("Y-m-d"));*/
// Affiche 2

if($Nombres_jours<3) {$nb3++; echo  "<div style=\"color:#FFFFFF;\">".number_format($Nombres_jours, 0, ',', ' ')."</div>";} ?>
</div></td>

<td class=" " style="<?php echo ($Nombres_jours>=3 && $Nombres_jours<=5)?"background-color: #FFD700;":""; ?>"><div align="center">

  <?php // Affiche 2

if($Nombres_jours>=3 && $Nombres_jours<=5) { $nb35++; echo  "<div style=\"color:#FFFFFF;\">".number_format($Nombres_jours, 0, ',', ' ')."</div>";} ?>

</div></td>

<td class=" " style="<?php echo ($Nombres_jours>5)?"background-color: #FF0000;":""; ?>"><div align="center">
  <?php

// Affiche 2

if($Nombres_jours>5) { $nb5++; echo  "<div style=\"color:#FFFFFF;\">".number_format($Nombres_jours, 0, ',', ' ')."</div>";} ?>
</div></td>
</tr>
<?php $total++; $i++; } }while($row_liste_dno = mysql_fetch_assoc($liste_dno)); } else { ?>

<tr>

<td colspan="7"><h2 align="center">Aucune donn&eacute;e !</h2></td>
</tr>

<?php } ?>
<?php if($total==0) { ?>
<tr>
<td colspan="7"><h2 align="center">Aucune donn&eacute;e !</h2></td>
</tr>
<?php } ?>
</tbody></table>
<?php if($total>0) { ?>
<div class="well well-sm"><br>
<div>Synth&egrave;se : <strong><?php echo $total; ?></strong> DANO au niveau de l'UCP pour traitement dont : <strong><?php echo $nb3; ?></strong> DANO depuis moins de 3 jours; <strong><?php echo $nb35; ?></strong> DANO depuis entre 3 et 5 jours; <strong><?php echo $nb5; ?></strong> DANO depuis plus de 5 jours</div>
</div>
<?php } ?>
<?php if($totalGeneral==0 && $total==0){ $do_not = false; } else $do_not = true;  ?>

<br><br>Cordialement,<br><br><i>Ruche PNF</i><hr class="end">
<p class="Footer">Veuillez ne pas répondre à cet email. Les messages reçus à cette adresse ne sont pas lus et ne reçoivent donc aucune réponse. Pour obtenir de l'aide, <a rel="nofollow" target="_blank" href="http://www.suivi.prodaf.net">connectez-vous</a> à votre compte et cliquez sur le lien Aide en haut à droite de chaque page de la base de données. <br><br>Pour recevoir des notifications par email au format texte et non en HTML, <a rel="nofollow" target="_blank" href="javascript:void(0);">mettez vos préférences à jour</a>.</p>
<br><span class="Footer">Copyright © 2016 PNF. Tous droits réservés.<br><br>Programme Niger FIDA - Maradi Tahoua Zinder<br>Niamey, <br />Tél: (+227) 00 00 00 00 / (+227) 00 00 00 00<br />Email: <a href="mailto:info@prodaf.net">info@prodaf.net.org</a></span>
</div>
</body>

</html>
<?php } } ?>