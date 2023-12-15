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
header('Content-Type: text/html; charset=UTF-8');

$mois = array("","01"=>"Janvier","02"=>"Février","03"=>"Mars","04"=>"Avril","05"=>"Mai","06"=>"Juin","07"=>"Juillet","08"=>"Ao&ucirc;t","09"=>"Septembre","10"=>"Octobre","11"=>"Novembre","12"=>"Decembre");
$tableauMois= array('T1','T2','T3','T4');
$structure_array = array("01"=>"RCI","02"=>"AIPH","03"=>"APROMAC","04"=>"INTERCOTON","05"=>"CCC","06"=>"CCA","07"=>"RCI");
$annee=(isset($_GET['annee']))?$_GET['annee']:date("Y");
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_bailleur = "SELECT t.code_type, t.bailleur, sum(b.montant) as montant, b.activite FROM ".$database_connect_prefix."part_bailleur b, ".$database_connect_prefix."type_part t, ".$database_connect_prefix."ptba p where t.code_type=b.type_part and b.activite=p.id_ptba and b.annee='$annee' and b.projet='".$_SESSION["clp_projet"]."' and p.projet='".$_SESSION["clp_projet"]."' and p.annee='$annee' and b.montant is not null GROUP BY b.activite,t.bailleur";
$liste_bailleur = mysql_query_ruche($query_liste_bailleur, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
$totalRows_liste_bailleur = mysql_num_rows($liste_bailleur);
$destinateur_array = $parts_array = $tableauCoutSaisi = array();
if($totalRows_liste_bailleur>0){ do{
  $destinateur_array[$row_liste_bailleur["bailleur"]] = $row_liste_bailleur["bailleur"];
  $parts_array[$row_liste_bailleur["bailleur"]] = $row_liste_bailleur["code_type"];

if(!isset($tableauCoutSaisi[$row_liste_bailleur["activite"]][$row_liste_bailleur["bailleur"]]))
$tableauCoutSaisi[$row_liste_bailleur["activite"]][$row_liste_bailleur["bailleur"]]=$row_liste_bailleur["montant"];
else $tableauCoutSaisi[$row_liste_bailleur["activite"]][$row_liste_bailleur["bailleur"]]+=$row_liste_bailleur["montant"];

if(!isset($tableauCoutSaisi[$row_liste_bailleur["activite"]]["total"])) $tableauCoutSaisi[$row_liste_bailleur["activite"]]["total"]=$row_liste_bailleur["montant"];
else $tableauCoutSaisi[$row_liste_bailleur["activite"]]["total"]+=$row_liste_bailleur["montant"];

}while($row_liste_bailleur = mysql_fetch_assoc($liste_bailleur));
    $rows = mysql_num_rows($liste_bailleur);
    if($rows > 0) {
        mysql_data_seek($liste_bailleur, 0);
  	  $row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
    }
}

asort($destinateur_array);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_bailleur = "SELECT * FROM ".$database_connect_prefix."partenaire ";
$liste_bailleur = mysql_query_ruche($query_liste_bailleur, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
$totalRows_liste_bailleur = mysql_num_rows($liste_bailleur);
$bailleur_array = array();
  if($totalRows_liste_bailleur>0){  do{
    $bailleur_array[$row_liste_bailleur["code"]] = $row_liste_bailleur["sigle"];
  }while($row_liste_bailleur = mysql_fetch_assoc($liste_bailleur));  }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_prestataire = "SELECT * FROM ".$database_connect_prefix."acteur where structure='".$_SESSION["clp_structure"]."' ";
$liste_prestataire = mysql_query_ruche($query_liste_prestataire, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_prestataire = mysql_fetch_assoc($liste_prestataire);
$totalRows_liste_prestataire = mysql_num_rows($liste_prestataire);
$acteur_array = array();
  if($totalRows_liste_prestataire>0){  do{
    $acteur_array[$row_liste_prestataire["code_acteur"]] = $row_liste_prestataire["sigle"];
  }while($row_liste_prestataire = mysql_fetch_assoc($liste_prestataire));  }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_ms = "SELECT code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=1 and ".$_SESSION["clp_where"]." ORDER BY code asc";
$edit_ms = mysql_query_ruche($query_edit_ms, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_ms = mysql_fetch_assoc($edit_ms);
$totalRows_edit_ms = mysql_num_rows($edit_ms);
?>
<style>#sp_hr {margin:0px; }
.r_float{float: right;}
.Style11 { font-weight: bold;color: #FFFFFF;}
.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; } .marquer{background: #FFFF00!important; }
</style>

<div class="well well-sm"><strong>Chronogramme des acivit&eacute;s du PTBA <?php echo "$annee"; ?></strong></div>

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-bordered table-responsive">
            <!--<thead> -->
            <tr bgcolor="<?php echo (!isset($_GET["down"]))?'':'#E4E4E4'; ?>">
              <td rowspan="3" align="center">Code</td>
              <td rowspan="3" align="center">Activit&eacute;s</td>
              <td rowspan="3">Responsables de r&eacute;alisation</td>
              <td rowspan="2" colspan="<?php echo count($tableauMois); ?>">Chronogramme</td>
              <?php if(count($destinateur_array)>0){ ?>
              <td colspan="<?php echo count($destinateur_array)+1; ?>">Budget global annuel (XOF)</td>
              <?php } ?>
            </tr>
            <tr bgcolor="<?php echo (!isset($_GET["down"]))?'':'#E4E4E4'; ?>">
              <?php if(count($destinateur_array)>0){ ?>
              <td colspan="<?php echo count($destinateur_array); ?>">Part Bailleurs</td>
              <?php } ?>
              <td rowspan="2" widtd="100">Budget total</td>
            </tr>
            <tr bgcolor="<?php echo (!isset($_GET["down"]))?'':'#E4E4E4'; ?>">
              <?php foreach($tableauMois as $vmois){ ?>
              <td widtd="20"><?php echo $vmois; ?></td>
              <?php } ?>
              <?php foreach($destinateur_array as $a){ ?>
              <td widtd="100"><?php echo isset($bailleur_array[$a])?$bailleur_array[$a]:$a; ?></td>
              <?php } ?>
            </tr>
            <!--</thead>-->
<?php number_format(0, 0, ',', ' '); if($totalRows_edit_ms>0) { $total = array(); do {
$code = $row_edit_ms['code'];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."ptba where projet='".$_SESSION["clp_projet"]."' and annee='$annee' and code_activite_ptba like '$code%' ORDER By code_activite_ptba asc";
$liste_rec = mysql_query_ruche($query_liste_rec, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_rec = mysql_fetch_assoc($liste_rec);
$totalRows_liste_rec = mysql_num_rows($liste_rec);
if($totalRows_liste_rec>0) {$i=0; $t=0; $p2=$p1="j"; $titre = $row_edit_ms['intitule']; ?>
            <tr bgcolor="#BED694">
              <td colspan="<?php echo count($destinateur_array)+count($tableauMois)+4; ?>" align="center" style="background-color: #BED694;">
                <b><?php echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?></b>
              </td>
            </tr>
<?php do { $code_act = $row_liste_rec['code_activite_ptba']; ?>
            <tr>
<td><?php echo "<b>".$row_liste_rec['code_activite_ptba'].'</b>'; ?></td>
<td><?php echo $row_liste_rec['intitule_activite_ptba']; ?></td>
<td valign="middle"><?php if(!empty($row_liste_rec['responsable'])) echo "<strong>".$row_liste_rec['responsable']."</strong> "; elseif($row_liste_rec['acteur_conserne']=="0") echo "<strong>".$config->structure."</strong> "; elseif(isset($acteur_array[$row_liste_rec["acteur_conserne"]])) echo "<strong>".$acteur_array[$row_liste_rec["acteur_conserne"]]."</strong> "; else echo "Non identifié"; ?></td>
<?php foreach($tableauMois as $vmois){
$amois = explode('<>',$vmois);
$imois = $amois[0];
$a = explode(",", $row_liste_rec['debut']);
?>
<td <?php if(in_array($imois, $a, TRUE)) echo "bgcolor='#FFFF00'"; ?>>&nbsp;</td>
<?php }
if(isset($tableauCoutSaisi[$row_liste_rec["id_ptba"]]))  $cout_saisi=$tableauCoutSaisi[$row_liste_rec["id_ptba"]]["total"]; else $cout_saisi="";
  //if(isset($cout_array[$row_liste_rec["code_activite_ptba"]]))  $cout_importe=$cout_array[$row_liste_rec["code_activite_ptba"]]; else $cout_importe="";
?>
<?php foreach($destinateur_array as $a=>$b){ ?>
<td align="right"><?php if(isset($tableauCoutSaisi[$row_liste_rec["id_ptba"]][$a])){ echo number_format($tableauCoutSaisi[$row_liste_rec["id_ptba"]][$a], 0, ',', ' '); if(!isset($total[$a])) $total[$a] = $tableauCoutSaisi[$row_liste_rec["id_ptba"]][$a]; else $total[$a] += $tableauCoutSaisi[$row_liste_rec["id_ptba"]][$a];  } else echo "-"; ?></td>
<?php } ?>
<td align="right"><?php if($cout_saisi!=""){ echo number_format($cout_saisi, 0, ',', ' '); if(!isset($total["total"])) $total["total"] = $cout_saisi; else $total["total"] += $cout_saisi; } else echo "-"; ?></td>
            </tr>
            <?php } while ($row_liste_rec= mysql_fetch_assoc($liste_rec)); ?>
            <?php } else { ?>
<!--            <tr>
              <td colspan="<?php echo count($destinateur_array)+count($tableauMois)+4; ?>"><div align="center"><span class="Style4"><em><strong>Aucune activit&eacute; enregistr&eacute;e dans la composante <?php //echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?> ! </strong></em></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span></div></td>
            </tr>-->
            <?php }  ?>
      <?php } while ($row_edit_ms = mysql_fetch_assoc($edit_ms)); ?>
      <tr>
        <td colspan="<?php echo count($tableauMois)+3; ?>" align="center"><strong><?php echo $titre; ?></strong></td>
        <?php foreach($destinateur_array as $a=>$b){ ?>
        <td align="right"><strong><?php if(isset($total[$a])) echo number_format($total[$a], 0, ',', ' '); else echo "-"; ?></strong></td>
        <?php } ?>
        <td align="right"><strong><?php if(isset($total["total"])) echo number_format($total["total"], 0, ',', ' '); else echo "-"; ?></strong></td>
      </tr>
      <?php } else { ?>
      <tr>
        <td colspan="<?php echo count($destinateur_array)+count($tableauMois)+4; ?>" align="center"><strong><em>Aucune composante trouv&eacute;e!</em></strong></td>
      </tr>
      <?php } ?>
</table>