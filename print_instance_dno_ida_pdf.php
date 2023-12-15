<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
//session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  echo "<h1>Une erreur s'est produite !</h1>";
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";

//if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else {$annee=date("Y");}

//fonction calcul nb jour
/*function NbJours($debut, $fin) {
  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);
  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
  return(($diff / 86400)+1);
}*/


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_conv = "SELECT distinct ".$database_connect_prefix."dno.* FROM ".$database_connect_prefix."dno where ".$database_connect_prefix."dno.projet='".$_SESSION["clp_projet"]."'  ORDER BY numero desc";
$liste_conv = mysql_query($query_liste_conv, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_conv = mysql_fetch_assoc($liste_conv);
$totalRows_liste_conv = mysql_num_rows($liste_conv);


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_bailleur = "SELECT code, sigle, definition FROM ".$database_connect_prefix."partenaire WHERE dno=1 ";
$liste_bailleur = mysql_query($query_liste_bailleur, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
$totalRows_liste_bailleur = mysql_num_rows($liste_bailleur);
$destinateur_array = array();
if($totalRows_liste_bailleur>0){ do{
  $destinateur_array[$row_liste_bailleur["code"]] = $row_liste_bailleur["sigle"];
}while($row_liste_bailleur = mysql_fetch_assoc($liste_bailleur)); }



mysql_select_db($database_pdar_connexion, $pdar_connexion);
//$id=$row_liste_conv['id_dno'];  mysql_error_show_message(mysql_error())
$query_edit_ano = "SELECT dno, phase, date_phase FROM ".$database_connect_prefix."suivi_dno";
$edit_ano = mysql_query($query_edit_ano, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_ano = mysql_fetch_assoc($edit_ano);
$totalRows_edit_ano = mysql_num_rows($edit_ano);
$tableau_date_envoi = array();
$tableau_date_ano = array();
$tableau_date_rejet = array();
if($totalRows_edit_ano>0){  do{
  if($row_edit_ano["phase"]=="ANO") $tableau_date_ano[$row_edit_ano["dno"]]=$row_edit_ano["date_phase"];
  if($row_edit_ano["phase"]=="Envoi au bailleur") $tableau_date_envoi[$row_edit_ano["dno"]]=$row_edit_ano["date_phase"];
    if($row_edit_ano["phase"]=="Objection du bailleur") $tableau_date_rejet[$row_edit_ano["dno"]]=$row_edit_ano["date_phase"];

   }while($row_edit_ano = mysql_fetch_assoc($edit_ano));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rubrique = "select * from ".$database_connect_prefix."suivi_dno order by dno, date_phase desc";
$liste_rubrique = mysql_query($query_liste_rubrique, $pdar_connexion) or die(mysql_error());
$row_liste_rubrique = mysql_fetch_assoc($liste_rubrique);
$totalRows_liste_rubrique = mysql_num_rows($liste_rubrique);
$tableau_stat = $tableau_obs = $tableau_persp = array();
if($totalRows_liste_rubrique>0){  do{ if(!isset($tableau_obs[$row_liste_rubrique["dno"]])) $tableau_obs[$row_liste_rubrique["dno"]]="";
  $tableau_stat[$row_liste_rubrique["dno"]]=$row_liste_rubrique["phase"];
  $tableau_persp[$row_liste_rubrique["dno"]]=$row_liste_rubrique["observation"];
  $tableau_obs[$row_liste_rubrique["dno"]].="<u>".implode('-',array_reverse(explode('-',$row_liste_rubrique["date_phase"])))."</u>: (<b>".$row_liste_rubrique["phase"]."</b>)<i> ".$row_liste_rubrique["observation"]."&nbsp;    </br></i>"; }while($row_liste_rubrique = mysql_fetch_assoc($liste_rubrique));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_phase_dano = "select * from ".$database_connect_prefix."suivi_dno order by dno, date_phase, id_suivi asc";
$liste_phase_dano = mysql_query($query_liste_phase_dano, $pdar_connexion) or die(mysql_error());
$row_liste_phase_dano = mysql_fetch_assoc($liste_phase_dano);
$totalRows_liste_phase_dano = mysql_num_rows($liste_phase_dano);
$tableau_phase_dano= array();
$tableau_date_phase_dano= array();
if($totalRows_liste_phase_dano>0){  do{ //if(!isset($tableau_obs[$row_liste_rubrique["dno"]])) $tableau_obs[$row_liste_rubrique["dno"]]="";
  $tableau_phase_dano[$row_liste_phase_dano["dno"]]=$row_liste_phase_dano["phase"];
   $tableau_date_phase_dano[$row_liste_phase_dano["dno"]]=$row_liste_phase_dano["date_phase"];
  }while($row_liste_phase_dano = mysql_fetch_assoc($liste_phase_dano));
}

?>

<style>#sp_hr {margin:0px; }
.r_float{float: right;}
.Style11 { font-weight: bold;color: #FFFFFF;}
.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
</style>

<div class="well well-sm"><strong>P&eacute;riode et </strong><strong>Les ANO en instances avec le bailleur</strong></div>

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive">
<!--<thead>-->
<tr role="row">
<th rowspan="2">Num&eacute;ro</th>
<th rowspan="2">Objet</th>
<th rowspan="2">Date d'envoi / Renvoi </th>
<th colspan="3"><div align="center">Dur&eacute;e (J)</div></th>
</tr>
<tr role="row">
  <th nowrap="nowrap">Plus de 5 j </th>
  <th nowrap="nowrap">Entre 3 et 5 j </th>
  <th nowrap="nowrap">Moins de 3 j </th>
</tr>
<!--</thead>-->
<tbody class="hide_befor_load">
<?php if($totalRows_liste_conv>0) { $i=0; do { $id = $row_liste_conv['numero'];
 /*if(isset($tableau_date_envoi[$row_liste_conv['numero']])) $denvoi=$tableau_date_envoi[$row_liste_conv['numero']]; else $denvoi=date("Y-m-d");  if($denvoi>=$row_liste_conv['date_initialisation'])$Nombres_jourse = NbJours($row_liste_conv['date_initialisation'], $denvoi); else $Nombres_jourse="  ???";*/
?>

<?php if(isset($tableau_phase_dano[$id]) && $tableau_phase_dano[$id]!='ANO'  && $tableau_phase_dano[$id]!='Objection du bailleur' && $tableau_phase_dano[$id]!='Retour du bailleur'){  ?>

<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo $row_liste_conv['numero']; ?></td>
<td class=" "><?php echo $row_liste_conv['objet']; ?></td>
<td class=" "><?php 
if(isset($tableau_phase_dano[$id]) && ($tableau_phase_dano[$id])=="Retour du bailleur")
{ if(date("Y-m-d")>=$tableau_date_phase_dano[$id] && NbJours($tableau_date_phase_dano[$id], date("Y-m-d"))>3) echo " <span style=\"background-color:#FF0000; color:#FFFFFF; display:block;\"><span class=\"hidden\">1</span>En attente de renvoi</div>"; elseif(date("Y-m-d")<$tableau_date_phase_dano[$id]) echo " <span style=\"color:#FF0000;\">?...?</div>"; else echo "En attente de renvoi";
 }
elseif(isset($tableau_phase_dano[$id]) && ($tableau_phase_dano[$id])=="Renvoi au bailleur")
{
 echo date_reg($tableau_date_phase_dano[$id],'/'); $denvoi=$tableau_date_phase_dano[$id];
 }
elseif(isset($tableau_phase_dano[$id]) && ($tableau_phase_dano[$id])=="Envoi au bailleur")
{ echo date_reg($tableau_date_phase_dano[$id],'/'); $denvoi=$tableau_date_phase_dano[$id];
}
elseif(isset($tableau_phase_dano[$id]) && ($tableau_phase_dano[$id]=="ANO" || $tableau_phase_dano[$id]=="Objection du bailleur") )
{
if(isset($tableau_date_renvoi[$row_liste_conv['numero']])) {$denvoi=$tableau_date_renvoi[$row_liste_conv['numero']]; }
  elseif(isset($tableau_date_envoi[$row_liste_conv['numero']])) $denvoi=$tableau_date_envoi[$row_liste_conv['numero']];
 echo date_reg($denvoi,'/'); 
}
else 
{ if(date("Y-m-d")>=$row_liste_conv['date_initialisation'] && NbJours($row_liste_conv['date_initialisation'], date("Y-m-d"))>3) echo " <span style=\"background-color:#FF0000; color:#FFFFFF; display:block;\"><span class=\"hidden\">2</span>En attente d'envoi</div>"; elseif(date("Y-m-d")<$row_liste_conv['date_initialisation']) echo " <span style=\"color:#FF0000;\">?...?</div>"; else echo "En attente d'envoi";
 }
 ?></td>
<td class=" "><div align="center">
  <?php if(isset($tableau_date_phase_dano[$id]) && isset($tableau_phase_dano[$id]) && $tableau_phase_dano[$id]=='Renvoi au bailleur') $date_envoi=$tableau_date_phase_dano[$id]; elseif(isset($tableau_date_phase_dano[$id]) && isset($tableau_phase_dano[$id]) && $tableau_phase_dano[$id]=='Envoi au bailleur') $date_envoi=$tableau_date_phase_dano[$id]; $Nombres_jours = NbJours($date_envoi, date("Y-m-d"));
// Affiche 2
if($Nombres_jours>5) {echo  "<div style=\"width: 40%; background-color:#FF0000; color:#FFFFFF;\">".number_format($Nombres_jours, 0, ',', ' ')."</div>";} ?>
</div></td>
<td class=" "><div align="center">
  <?php // Affiche 2
if($Nombres_jours>=3 && $Nombres_jours<=5) echo number_format($Nombres_jours, 0, ',', ' '); ?>
</div></td>
<td class=" "><div align="center">
  <?php 
// Affiche 2
if($Nombres_jours<3) echo number_format($Nombres_jours, 0, ',', ' '); ?>
</div></td>
</tr>
<?php } ?>
<?php }while($row_liste_conv  = mysql_fetch_assoc($liste_conv)); } else { ?>
<tr>
<td colspan="7"><h2 align="center">Aucune donn&eacute;e !</h2></td>
</tr>

<?php } ?>
</tbody></table>