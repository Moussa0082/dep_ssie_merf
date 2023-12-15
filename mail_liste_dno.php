<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once $path.'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $path.$config->sys_folder . "/database/db_connexion.php";

if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else {$annee=date("Y");}

//fonction calcul nb jour
function NbJours($debut, $fin) {
  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);
  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
  return(($diff / 86400)+1);
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_conv = "SELECT distinct ".$database_connect_prefix."dno.*, ".$database_connect_prefix."ptba.intitule_activite_ptba FROM ".$database_connect_prefix."dno, ".$database_connect_prefix."ptba where ".$database_connect_prefix."dno.code_activite=".$database_connect_prefix."ptba.code_activite_ptba and annee=$annee and ".$database_connect_prefix."dno.projet='".$_SESSION["clp_projet"]."'  ORDER BY numero desc";
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
?>

<!-- Site contenu ici -->
<style>
table#mtable tr td, table#mtable thead tr th {vertical-align: middle; }
table#mtable tbody tr td {vertical-align: middle; }
table#mtable>thead>tr>th,table#mtable>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}
table#mtable {width: 100%; border-spacing: 0px !important; border-collapse: collapse; font-size: small;}
</style>
<h4><i class="icon-reorder"></i> Les Demandes des Avis Non Objections</h4>

<table class="" id="mtable" class="table" border="1">
<thead>
<tr role="row">
<th>Num&eacute;ro</th>
<th>Activit&eacute;</th>
<th>Destinataire</th>
<th>Objet</th>
<th>Date de soumission </th>
<th>Date ANO</th>
<th>Dur&eacute;e (J)</th>
<th>Observations</th>
</tr>
</thead>
<tbody class="hide_befor_load">
<?php if($totalRows_liste_conv>0) { $i=0; do { $id = $row_liste_conv['numero'];
 if(isset($tableau_date_envoi[$row_liste_conv['numero']])) $denvoi=$tableau_date_envoi[$row_liste_conv['numero']]; else $denvoi=date("Y-m-d");  if($denvoi>=$row_liste_conv['date_initialisation'])$Nombres_jourse = NbJours($row_liste_conv['date_initialisation'], $denvoi); else $Nombres_jourse="  ???";
?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo $row_liste_conv['numero']; ?></td>
<td class=" "><div title=" <?php echo $row_liste_conv['intitule_activite_ptba']; ?>"><?php if(isset($row_liste_conv["code_activite"])) echo $row_liste_conv["code_activite"]; ?></div></td>
<td class=" "><?php echo (isset($destinateur_array[$row_liste_conv['destinataire']]))?$destinateur_array[$row_liste_conv['destinataire']]:''; ?></td>
<td class=" "><?php echo $row_liste_conv['objet']; ?></td>
<td class=" "><?php if(isset($tableau_date_envoi[$row_liste_conv['numero']]) ) echo date_reg($tableau_date_envoi[$row_liste_conv['numero']],'/'); else echo "En attente";  ?></td>
<td class=" "><?php if(isset($tableau_date_ano[$row_liste_conv['numero']]) ) echo date_reg($tableau_date_ano[$row_liste_conv['numero']],'/'); elseif(isset($tableau_date_rejet[$row_liste_conv['numero']]) ) { echo "<div style=\"width: 80%; background-color:#FF0000; color:#FFFFFF;\">".date_reg($tableau_date_rejet[$row_liste_conv['numero']],'/')."</div>";}  else echo "<div align=\"center\" style=\"width: 80%; background-color:#FFFF00; \">-</div>"; ?></td>
<td class=" "><div align="center">
  <?php if(isset($tableau_date_ano[$row_liste_conv['numero']])) $dano=$tableau_date_ano[$row_liste_conv['numero']]; else $dano=date("Y-m-d"); if (isset($tableau_date_envoi[$row_liste_conv['numero']])) { $Nombres_jours = NbJours($tableau_date_envoi[$row_liste_conv['numero']], $dano);
// Affiche 2
if($dano>=$tableau_date_envoi[$row_liste_conv['numero']]) {echo number_format($Nombres_jours, 0, ',', ' ');}else echo "<div style=\"width: 40%; background-color:#FF0000; color:#FFFFFF;\">?...?</div>";} ?>
</div></td>
<td class=" "><?php echo (isset($tableau_obs[$row_liste_conv['numero']]))?$tableau_obs[$row_liste_conv['numero']]:"<div align='center'>Aucun suivi</div>"; ?></td>
</tr>
<?php }while($row_liste_conv  = mysql_fetch_assoc($liste_conv)); } else { ?>
<tr>
<td colspan="8"><h2 align="center">Aucune donn&eacute;e !</h2></td>
</tr>
<?php } ?>
</tbody></table>

<!-- Fin Site contenu ici -->