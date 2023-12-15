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

$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Ao&ucirc;t","Septembre","Octobre","Novembre","Decembre");
$annee=(isset($_GET['annee']))?$_GET['annee']:date("Y");
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_ms = "SELECT code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=1 and ".$_SESSION["clp_where"];
$edit_ms = mysql_query($query_edit_ms, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_ms = mysql_fetch_assoc($edit_ms);
$totalRows_edit_ms = mysql_num_rows($edit_ms);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = "SELECT * FROM ".$database_connect_prefix."ptba where annee='$annee' and projet='".$_SESSION["clp_projet"]."' ";
$query_act .= " order by code_activite_ptba asc";
$act = mysql_query($query_act, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_act = mysql_fetch_assoc($act);
$totalRows_act = mysql_num_rows($act);

//Calcul
$statut_act = array();
if(isset($totalRows_act) && $totalRows_act>0) { $i=0; do { $id_act=$row_act['id_ptba']; $code_act = $row_act['code_activite_ptba'];
//suivi tache
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_suivi_tache = "SELECT proportion as valeur_suivi, tache
FROM ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."suivi_tache where id_groupe_tache=tache and activite_ptba='$code_act' and ".$database_connect_prefix."groupe_tache.projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."suivi_tache.projet='".$_SESSION["clp_projet"]."' and observation is not null GROUP BY tache";
$suivi_tache  = mysql_query($query_suivi_tache , $pdar_connexion) or die(mysql_error());
$row_suivi_tache  = mysql_fetch_assoc($suivi_tache);
$totalRows_suivi_tache  = mysql_num_rows($suivi_tache);
$taux_tache = $taux_progress = 0;
$ttt=0; $maxt=0; $idmaxt=0; if($totalRows_suivi_tache>0) { do {
$taux_tache+=$row_suivi_tache["valeur_suivi"];
} while ($row_suivi_tache = mysql_fetch_assoc($suivi_tache));  }
if(isset($taux_tache) && $taux_tache>0 && $totalRows_suivi_tache>0) {
$ttt=$taux_tache; $taux_progress = $ttt; $stat = $ttt; }

if($totalRows_suivi_tache>=0) { $taux=$ttt;
if (isset($taux_progress) && $taux_progress>0 && $taux_progress<=100) $percent = $taux_progress;
elseif (isset($taux_progress) && $taux_progress>100) $percent = 100;
else $percent = 0;

if(isset($stat)){ if($stat==0 && $annee==date("Y")) $statut_act[$code_act]="Non entam&eacute;e"; elseif($stat>0 && $stat<100) $statut_act[$code_act]="En cours"; elseif($stat>=100) $statut_act[$code_act]="Ex&eacute;cut&eacute;e"; else $statut_act[$code_act]="Non ex&eacute;cut&eacute;e"; } else $statut_act[$code_act]="Non entam&eacute;e";
unset($stat);
$i++; } } while ($row_act = mysql_fetch_assoc($act)); }
?>
<style>#sp_hr {margin:0px; }
.r_float{float: right;}
.Style11 { font-weight: bold;color: #FFFFFF;}
.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
</style>

<div class="well well-sm"><strong>SITUATION DES ACTIONS DU PTBA <?php echo "$annee"; ?> AU <?php echo date("t")." ".strtoupper($mois[date("m")]); ?></strong></div>

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive">
            <!--<thead> -->
            <tr>
              <th rowspan="2" align="center">ACTIVITES</th>
              <th colspan="7">STATUTS</th>
              <th rowspan="2">OBSERVATIONS (raisons du retard, du report ou de l'annulation)</th>
            </tr>
            <tr>
              <th>Activit&eacute;s compl&egrave;tement ex&eacute;cut&eacute;es </th>
              <th>Activit&eacute;s en cours  ex&eacute;cution mais qui s'ach&egrave;vera au <?php echo "31/12/$annee"; ?> </th>
              <th>Activit&eacute;s en cours  ex&eacute;cution mais qui s'ach&egrave;vera en <?php echo $annee+1; ?></th>
              <th>Activit&eacute;s non encore  ex&eacute;cut&eacute;es mais qui s'ach&egrave;vera au <?php echo "31/12/$annee"; ?> </th>
              <th>Activit&eacute;s non encore initi&eacute;es  et donc report&eacute;e &agrave; <?php echo $annee+1; ?> </th>
              <th>Activit&eacute;s non encore initi&eacute;es et pr&eacute;vue pour &ecirc;tre annul&eacute;es </th>
              <th>Autres activit&eacute;s &agrave; statut incertain ou probl&eacute;matiques</th>
            </tr>
           <!-- </thead>-->
<?php  if($totalRows_edit_ms>0) { do {
$code = $row_edit_ms['code'];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."ptba where projet='".$_SESSION["clp_projet"]."' and annee='$annee' and code_activite_ptba like '$code%' ORDER By code_activite_ptba asc";
$liste_rec = mysql_query($query_liste_rec, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_rec = mysql_fetch_assoc($liste_rec);
$totalRows_liste_rec = mysql_num_rows($liste_rec);
if($totalRows_liste_rec>0) {$i=0; $t=0; $p2=$p1="j"; ?>
            <tr bgcolor="#BED694">
              <td colspan="9" align="center" style="background-color: #BED694;">
                <b><?php echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?></b>
              </td>
            </tr>
<?php do { $code_act = $row_liste_rec['code_activite_ptba']; ?>
            <tr>
<td><?php echo "<b>".$row_liste_rec['code_activite_ptba'].'</b> : '.$row_liste_rec['intitule_activite_ptba']; ?></td>
<td width="10%" class="<?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="Ex&eacute;cut&eacute;e")?"marquer":""; ?>" valign="middle" align="center"><?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="Ex&eacute;cut&eacute;e")?"<b>x</b>":""; ?></td>
<td width="10%" class="<?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="En cours" && !strrchr($row_liste_rec['statut'],"Reporté"))?"marquer":""; ?>" valign="middle" align="center"><?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="En cours" && !strrchr($row_liste_rec['statut'],"Reporté"))?"<b>x</b>":""; ?></td>
<td width="10%" class="<?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="En cours" && strrchr($row_liste_rec['statut'],"Reporté"))?"marquer":""; ?>" valign="middle" align="center"><?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="En cours" && strrchr($row_liste_rec['statut'],"Reporté"))?"<b>x</b>":""; ?></td>
<td width="10%" class="<?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="Non ex&eacute;cut&eacute;e" && !strrchr($row_liste_rec['statut'],"Reporté"))?"marquer":""; ?>" valign="middle" align="center"><?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="Non ex&eacute;cut&eacute;e" && !strrchr($row_liste_rec['statut'],"Reporté"))?"<b>x</b>":""; ?></td>
<td width="10%" class="<?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="Non entam&eacute;e" && strrchr($row_liste_rec['statut'],"Reporté"))?"marquer":""; ?>" valign="middle" align="center"><?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="Non entam&eacute;e" && strrchr($row_liste_rec['statut'],"Reporté"))?"<b>x</b>":""; ?></td>
<td width="10%" class="<?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="Non entam&eacute;e" && strrchr($row_liste_rec['statut'],"Annulé"))?"marquer":""; ?>" valign="middle" align="center"><?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="Non entam&eacute;e" && strrchr($row_liste_rec['statut'],"Annulé"))?"<b>x</b>":""; ?></td>
<td width="10%" class="<?php echo ($row_liste_rec['statut']=="Incertain")?"marquer":""; ?>" valign="middle" align="center"><?php echo ($row_liste_rec['statut']=="Incertain")?"<b>x</b>":""; ?></td>
<td><?php echo $row_liste_rec['observation']; ?></td>
            </tr>
            <?php } while ($row_liste_rec= mysql_fetch_assoc($liste_rec)); ?>
            <?php } else { ?>
<!--            <tr>
              <td colspan="9"><em><strong>Aucune activit&eacute; enregistr&eacute;e! </strong></em></td>
            </tr>-->
            <?php }  ?>
      <?php } while ($row_edit_ms = mysql_fetch_assoc($edit_ms)); } else { ?>
      <tr>
        <td colspan="9" align="center"><strong><em>Aucune composante trouv&eacute;e!</em></strong></td>
      </tr>
      <?php } ?>
</table>