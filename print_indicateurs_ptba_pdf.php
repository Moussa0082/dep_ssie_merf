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

$annee=(isset($_GET['annee']))?$_GET['annee']:date("Y");
$array_indic = array("OUI/NON","texte");
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_ms = "SELECT code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=1 and projet='".$_SESSION["clp_projet"]."' ORDER BY code asc";
$edit_ms = mysql_query_ruche($query_edit_ms, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_ms = mysql_fetch_assoc($edit_ms);
$totalRows_edit_ms = mysql_num_rows($edit_ms);

mysql_select_db($database_pdar_connexion, $pdar_connexion); //code_activite='$code_act' and annee=$annee and
$query_indicateur = "SELECT * FROM ".$database_connect_prefix."indicateur_tache ORDER BY code_indicateur_ptba asc";
$indicateur  = mysql_query_ruche($query_indicateur , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_indicateur = mysql_fetch_assoc($indicateur);
$totalRows_indicateur  = mysql_num_rows($indicateur);
$indicateur_array = $unite_array = array();
if($totalRows_indicateur>0) { do {
$indicateur_array[$row_indicateur["id_activite"]][$row_indicateur["id_indicateur_tache"]] = $row_indicateur;
$unite_array[$row_indicateur["id_indicateur_tache"]] = $row_indicateur["unite"];
} while ($row_indicateur = mysql_fetch_assoc($indicateur));  }

//semestre precedent
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cible_indicateur = "SELECT * FROM ".$database_connect_prefix."cible_indicateur_trimestre";
$cible_indicateur  = mysql_query_ruche($query_cible_indicateur , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_cible_indicateur = mysql_fetch_assoc($cible_indicateur );
$totalRows_cible_indicateur = mysql_num_rows($cible_indicateur );
$tableau_cible_indicateur_array = array();
  if($totalRows_cible_indicateur>0){  do{
    if(!isset($tableau_cible_indicateur_array[$row_cible_indicateur["indicateur"]][$row_cible_indicateur["trimestre"]]))
    $tableau_cible_indicateur_array[$row_cible_indicateur["indicateur"]][$row_cible_indicateur["trimestre"]] = $row_cible_indicateur[(!in_array($unite_array[$row_cible_indicateur["indicateur"]],$array_indic))?"cible":"cible_txt"];
    else
    $tableau_cible_indicateur_array[$row_cible_indicateur["indicateur"]][$row_cible_indicateur["trimestre"]] += $row_cible_indicateur[(!in_array($unite_array[$row_cible_indicateur["indicateur"]],$array_indic))?"cible":"cible_txt"];
  }while($row_cible_indicateur = mysql_fetch_assoc($cible_indicateur));  }
?>
<style>#sp_hr {margin:0px; }
.r_float{float: right;}
.Style11 { font-weight: bold;color: #FFFFFF;}
.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; } .marquer{background: #FFFF00!important; }
</style>

<div class="well well-sm"><strong>Chronogramme des Indicateurs du PTBA <?php echo "$annee"; ?></strong></div>

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-responsive">
            <!--<thead>-->
            <tr bgcolor="<?php echo (!isset($_GET["down"]))?'':'#E4E4E4'; ?>">
              <td rowspan="2" align="left">ACTIVITES</td>
              <!--<td rowspan="2" align="center"><b>N&deg;&nbsp;</b></td> -->
              <td rowspan="2" align="left">INDICATEUR</td>
              <td rowspan="2" align="left">UNITE</td>
              <td colspan="4" ><center>VALEUR CIBLE</center></td>
              <td rowspan="2" ><center>TOTAL</center></td>
            </tr>
            <tr bgcolor="<?php echo (!isset($_GET["down"]))?'':'#E4E4E4'; ?>">
              <?php for($j=1;$j<=4;$j++){ ?>
              <td align="center"><center>Trimestre <?php echo $j; ?></center></td>
              <?php } ?>
            </tr>
            <!--</thead> -->
<?php  if($totalRows_edit_ms>0) { do {
$code = $row_edit_ms['code'];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."ptba where projet='".$_SESSION["clp_projet"]."' and annee='$annee' and code_activite_ptba like '$code%' ORDER By code_activite_ptba asc";
$liste_rec = mysql_query_ruche($query_liste_rec, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_rec = mysql_fetch_assoc($liste_rec);
$totalRows_liste_rec = mysql_num_rows($liste_rec);
if($totalRows_liste_rec>0) {$i=0; $t=0; $p2=$p1="j"; ?>
            <tr bgcolor="#BED694">
              <td colspan="8" align="center" style="background-color: #BED694;">
                <b><?php echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?></b>
              </td>
            </tr>
<?php $row=""; do { $code_act = $row_liste_rec['code_activite_ptba']; $id_act = $row_liste_rec['id_ptba'];
if(isset($indicateur_array[$id_act])){ $k=0;
foreach($indicateur_array[$id_act] as $a=>$b){ $total = 0; $div = 0; ?>
            <tr>
<?php if(/*$row*/$k==0/*$b["code_indicateur_ptba"]*/){ ?>
<td rowspan="<?php echo count($indicateur_array[$id_act]); ?>"><?php echo "<b>".$row_liste_rec['code_activite_ptba'].'</b> : '.$row_liste_rec['intitule_activite_ptba']; ?></td>
<?php } ?>
<!--<td><?php //echo $b['code_indicateur_ptba']; ?></td>-->
<td><?php echo $b['intitule_indicateur_tache']; ?></td>
<td><?php echo $b['unite']; ?></td>
<?php for($j=1;$j<=4;$j++){ if(!in_array($b['unite'],$array_indic) && isset($tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j])) $total+= $tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j]; ?>
<td valign="middle" align="center"><?php if(isset($tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j])) echo $tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j]; if(!empty($tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j])) $div++; ?></td>
<?php } ?>
<td valign="middle" align="center"><?php $total = ($b['unite']=="%")?$total/$div:$total; echo (!in_array($b['unite'],$array_indic))?$total:'-'; ?></td>
            </tr>
            <?php $row=$b["code_indicateur_ptba"]; $k++; } } else { ?>
<tr>
  <td><?php echo "<b>".$row_liste_rec['code_activite_ptba'].'</b> : '.$row_liste_rec['intitule_activite_ptba']; ?></td>
  <td colspan="7"><div align="center"><span class="Style4"><em><strong>Aucun indicateur ! </strong></em></span></div></td>
</tr>
            <?php }  ?>
<tr class="even">
  <td colspan="8"><div align="center" style="background-color:#CCCCCC; height: 2px;">&nbsp;</div></td>
</tr>
			<?php } while ($row_liste_rec= mysql_fetch_assoc($liste_rec)); ?>
            <?php } else { ?>
<!--            <tr>
              <td colspan="8"><div align="center"><span class="Style4"><em><strong>Aucune activit&eacute; enregistr&eacute;e dans la composante <?php //echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?> ! </strong></em></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span></div></td>
            </tr>-->
            <?php }  ?>
      <?php } while ($row_edit_ms = mysql_fetch_assoc($edit_ms)); } else { ?>
      <tr>
        <td colspan="8" align="center"><strong><em>Aucune composante trouv&eacute;e!</em></strong></td>
      </tr>
      <?php } ?>
</table>