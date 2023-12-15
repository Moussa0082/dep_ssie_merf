<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & DÃ©veloppement: SEYA SERVICES */
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

$id_m=(isset($_GET['id']))?$_GET['id']:0;  //projet='".$_SESSION["clp_projet"]."' and
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_ms = "SELECT * FROM ".$database_connect_prefix."mission_supervision where  code_ms='$id_m'";
$edit_ms = mysql_query($query_edit_ms, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_ms = mysql_fetch_assoc($edit_ms);
$totalRows_edit_ms = mysql_num_rows($edit_ms);
//echo $totalRows_edit_ms;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rubrique = "SELECT * FROM ".$database_connect_prefix."rubrique_projet order by code_rub";
$liste_rubrique = mysql_query($query_liste_rubrique, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_rubrique = mysql_fetch_assoc($liste_rubrique);
$totalRows_liste_rubrique = mysql_num_rows($liste_rubrique);
$tableau_rubrique = array();
if($totalRows_liste_rubrique>0){  do{ $tableau_rubrique[$row_liste_rubrique["code_rub"]]=$row_liste_rubrique["nom_rubrique"]; }while($row_liste_rubrique = mysql_fetch_assoc($liste_rubrique));
}

 mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_volet = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE ".$_SESSION["clp_where"]." and niveau=1 ORDER BY code ASC";
  $liste_volet  = mysql_query($query_liste_volet , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_volet  = mysql_fetch_assoc($liste_volet);
  $totalRows_liste_volet  = mysql_num_rows($liste_volet);
  $tableau_volet = array();
if($totalRows_liste_volet>0){  do{ $tableau_volet[$row_liste_volet["code"]]=$row_liste_volet["intitule"]; }while($row_liste_volet = mysql_fetch_assoc($liste_volet));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_respo_ugl = "SELECT id_personnel, fonction FROM ".$database_connect_prefix."personnel";
$liste_respo_ugl  = mysql_query($query_liste_respo_ugl , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_respo_ugl  = mysql_fetch_assoc($liste_respo_ugl );
$totalRows_liste_respo_ugl  = mysql_num_rows($liste_respo_ugl );
$respo_ugl=array();
if($totalRows_liste_respo_ugl>0){ do{ $respo_ugl[$row_liste_respo_ugl["id_personnel"]]=$row_liste_respo_ugl["fonction"];  }while($row_liste_respo_ugl  = mysql_fetch_assoc($liste_respo_ugl ));  }


mysql_select_db($database_pdar_connexion, $pdar_connexion);
if(isset($row_edit_ms['code_ms']))$id=$row_edit_ms['code_ms']; else $id=0;
$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."recommandation_mission where mission='$id' and projet='".$_SESSION["clp_projet"]."' and structure='".$_SESSION["clp_structure"]."' ORDER BY rubrique asc, numero asc";
$liste_rec = mysql_query($query_liste_rec, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_rec = mysql_fetch_assoc($liste_rec);
$totalRows_liste_rec = mysql_num_rows($liste_rec);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_etat_rec = "select ".$database_connect_prefix."suivi_recommandation_mission.* from ".$database_connect_prefix."suivi_recommandation_mission, ".$database_connect_prefix."recommandation_mission where numero=".$database_connect_prefix."suivi_recommandation_mission.recommandation and mission='$id' and projet='".$_SESSION["clp_projet"]."' and structure='".$_SESSION["clp_structure"]."' order by recommandation asc";
$liste_etat_rec = mysql_query($query_liste_etat_rec, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_etat_rec = mysql_fetch_assoc($liste_etat_rec);
$totalRows_liste_etat_rec = mysql_num_rows($liste_etat_rec);
$tableau_stat = $tableau_obs = $tableau_persp = array();
if($totalRows_liste_etat_rec>0){  do{ if(!isset($tableau_obs[$row_liste_etat_rec["recommandation"]])) $tableau_obs[$row_liste_etat_rec["recommandation"]]="";
  $tableau_stat[$row_liste_etat_rec["recommandation"]]=$row_liste_etat_rec["statut"];
  $tableau_persp[$row_liste_etat_rec["recommandation"]]=$row_liste_etat_rec["perspective"];
  $tableau_obs[$row_liste_etat_rec["recommandation"]].="<b>".date_reg($row_liste_etat_rec["date_execution"],'-').":</b><i> ".$row_liste_etat_rec["observation"]." (<u>Moyen de v&eacute;rification:</u> ".$row_liste_etat_rec["moyen_verification"].")<br></i>"; } while($row_liste_etat_rec = mysql_fetch_assoc($liste_etat_rec));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rubrique = "select recommandation, statut, date_execution  from ".$database_connect_prefix."suivi_recommandation_mission where date_execution in ( select max(date_execution) from ".$database_connect_prefix."recommandation_mission, ".$database_connect_prefix."suivi_recommandation_mission where numero=".$database_connect_prefix."suivi_recommandation_mission.recommandation and mission='$id' and projet='".$_SESSION["clp_projet"]."' and structure='".$_SESSION["clp_structure"]."' group by numero) order by recommandation desc";
$liste_rubrique = mysql_query($query_liste_rubrique, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_rubrique = mysql_fetch_assoc($liste_rubrique);
$totalRows_liste_rubrique = mysql_num_rows($liste_rubrique);
$tableau_stat = array();
$date_stat = array();
if($totalRows_liste_rubrique>0){  do{
  $tableau_stat[$row_liste_rubrique["recommandation"]]=$row_liste_rubrique["statut"];
  $date_stat[$row_liste_rubrique["recommandation"]]=$row_liste_rubrique["date_execution"];
   }while($row_liste_rubrique = mysql_fetch_assoc($liste_rubrique));
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

<div class="well well-sm"><strong>P&eacute;riode et </strong><strong>Objet de la mission</strong></div>
      <?php  if($totalRows_edit_ms>0) { do { ?>
      <div align="left" class="well well-sm">
            <?php  echo $row_edit_ms['type']." du ".implode('-',array_reverse(explode('-',$row_edit_ms['debut'])))." au ".implode('-',array_reverse(explode('-',$row_edit_ms['fin']))); ?>
        :&nbsp;&nbsp;<?php echo $row_edit_ms['observation']; ?></div>
<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive">
            <?php if($totalRows_liste_rec>0) {$i=0; $t=0; $p2=$p1="j"; ?>
            <!--<thead>-->
            <tr>
              <th rowspan="2" align="center"><strong>R&eacute;f.</strong></th>
              <th rowspan="2"><div align="center"><strong>Recommandation </strong></div></th>
              <th rowspan="2"><div align="center"><strong>Date buttoir </strong></div></th>
              <th colspan="2"><div align="center"><strong>Responsables</strong></div></th>
              <th rowspan="2"><div align="center"><strong>Statut </strong></div></th>
              <th rowspan="2"><div align="center"><strong>Observation </strong></div></th>
              <th rowspan="2"><div align="center"><strong>Perspective </strong></div></th>
            </tr>
            <tr>
              <th><strong>D&eacute;di&eacute;</strong></th>
              <th><strong>Autres</strong></th>
            </tr>
            <!--</thead>-->
            <?php do { ?>
            <?php  if($p2!=$row_liste_rec['volet_recommandation']) {?>
            <tr bgcolor="#BED694">
              <td colspan="8" align="center" style="background-color: #BED694;"><div align="left"><strong>
                <?php  if($p2!=$row_liste_rec['volet_recommandation']) {if(isset($tableau_volet[$row_liste_rec["volet_recommandation"]])) echo $tableau_volet[$row_liste_rec["volet_recommandation"]]; 
				else echo "N/A";  }$p2=$row_liste_rec['volet_recommandation']; ?>
              </strong></div></td>
            </tr>
			 <?php } ?>
			 <?php  if($p1!=$row_liste_rec['rubrique']) {?>
            <tr bgcolor="#BED694">
              <td colspan="8" align="center" style="background-color: #BED694;"><div align="left" class="Style4"><strong>  <?php  if($p1!=$row_liste_rec['rubrique']) {if(isset($tableau_rubrique[$row_liste_rec["rubrique"]])) echo $tableau_rubrique[$row_liste_rec["rubrique"]];
				else echo "N/A"; $i=0; }$p1=$row_liste_rec['rubrique']; ?>
              </strong></div></td>
            </tr>
            <?php } ?>
            <tr>
              <td><div align="center"><strong><?php echo $row_liste_rec['numero']; ?></strong></div></td>
              <td><div align="left" class="Style4"><?php echo $row_liste_rec['recommandation']; ?></div></td>
              <td valign="top"><div align="center"><span class="Style46">
                <?php if(isset($row_liste_rec['type']) && $row_liste_rec['type']=="Continu") echo "Continu"; else echo date("d/m/y", strtotime($row_liste_rec['date_buttoir']));?>
              </span></div></td>
              <td valign="top"><div align="left">
                <div align="left">
                  <?php if(isset($respo_ugl[$row_liste_rec["responsable_interne"]])) echo $respo_ugl[$row_liste_rec["responsable_interne"]]; ?>
                </div>
              </div></td>
              <td valign="top"><?php echo $row_liste_rec['responsable']; ?></td>
              <td width="100">
<?php
$color = "gray"; $stat="Non entam&eacute;e";
if(isset($tableau_stat[$row_liste_rec['numero']]) && isset($date_stat[$row_liste_rec['numero']])) {
 $stat=$tableau_stat[$row_liste_rec['numero']];
 $datestat=$date_stat[$row_liste_rec['numero']];
if($stat!="R&eacute;alis&eacute;" && $datestat>$row_liste_rec['date_buttoir'] && $row_liste_rec['type']!="Continu") $color = "red";
elseif($stat=="R&eacute;alis&eacute;") $color = "green";
elseif($stat=="En cours") $color = "yellow";}
elseif(!isset($tableau_stat[$row_liste_rec['numero']]) && date("Y-m-d")>$row_liste_rec['date_buttoir'] && $row_liste_rec['type']!="Continu") {
$color = "red";
}  ?>
<div class="meter <?php echo $color;  ?> nostripes">
    <span id="bar" style="text-align: center; color: black; "></span>
<span id="label"><?php echo $stat; ?><span></span></span></div></td>
              <td width="300"><div align="left" class="Style4"><?php echo (isset($tableau_obs[$row_liste_rec['numero']]))?$tableau_obs[$row_liste_rec['numero']]:"<div align='center'>Aucun suivi</div>"; ?></div></td>
              <td width="100"><div align="left" class="Style4"><?php echo (isset($tableau_persp[$row_liste_rec['numero']]))?$tableau_persp[$row_liste_rec['numero']]:"<div align='center'>Aucun suivi</div>"; ?></div></td>
            </tr>
            <?php } while ($row_liste_rec= mysql_fetch_assoc($liste_rec)); ?>
            <?php } else { ?>
            <tr>
              <td colspan="8"><div align="center"><span class="Style4"><em><strong>Aucune recommandation enregistr&eacute;e! </strong></em></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span></div></td>
            </tr>
            <?php }  ?>
        </table>
        <hr id="sp_hr" />
      <?php } while ($row_edit_ms = mysql_fetch_assoc($edit_ms)); } else { ?>
      <tr>
        <td align="center"><strong><em>Aucune mission effectu&eacute;e!</em></strong></td>
      </tr>
      <?php } ?>