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

$tableauMois= array('Jan','Fev','Mar','Avr','Mai','Juin','Juil','Aout','Sep','Oct','Nov','Dec');
//$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Ao&ucirc;t","Septembre","Octobre","Novembre","D&eacute;cembre");
$annee=(isset($_GET['annee']))?$_GET['annee']:date("Y");
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_ms = "SELECT code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=1 and projet='".$_SESSION["clp_projet"]."' ORDER BY code asc";
$edit_ms = mysql_query($query_edit_ms, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_ms = mysql_fetch_assoc($edit_ms);
$totalRows_edit_ms = mysql_num_rows($edit_ms);

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);  //
$query_act = "SELECT * FROM ".$database_connect_prefix."ptba where annee='$annee' and projet='".$_SESSION["clp_projet"]."' ";
$query_act .= " order by code_activite_ptba asc";
$act = mysql_query($query_act, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_act = mysql_fetch_assoc($act);
$totalRows_act = mysql_num_rows($act);*/

mysql_select_db($database_pdar_connexion, $pdar_connexion); //code_activite='$code_act' and annee=$annee and
$query_tache = "select * FROM ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."ptba where id_ptba=id_activite and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."ptba.annee=$annee ORDER BY code_tache ASC";
$tache  = mysql_query($query_tache , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_tache = mysql_fetch_assoc($tache);
$totalRows_tache  = mysql_num_rows($tache);
//echo $totalRows_tache;
//exit;
$tache_array = array();
$ttt=0; $maxt=0; $idmaxt=0; if($totalRows_tache>0) { do {
$tache_array[$row_tache["id_ptba"]][$row_tache["id_groupe_tache"]] = array("id_ptba"=>$row_tache["id_ptba"],"code_tache"=>$row_tache["code_tache"],"proportion"=>$row_tache["proportion"],"intitule_tache"=>$row_tache["intitule_tache"],"periode"=>$row_tache["debut"],"cout_tache"=>0);
} while ($row_tache = mysql_fetch_assoc($tache));  }
?>
<style>#sp_hr {margin:0px; }
.r_float{float: right;}
.Style11 { font-weight: bold;color: #FFFFFF;}
.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
</style>

<div class="well well-sm"><strong>T&Acirc;CHES DES ACTIVITES DU PTBA <?php echo "$annee"; ?></strong></div>

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive">
           <!-- <thead>-->
            <tr>
              <th align="left">ACTIVITES</th>
              <th align="left">TACHES</th>
              <th >PROPORTION</th>
              <?php foreach($tableauMois as $vmois){
             // $amois = explode('<>',$vmois); ?>
              <th align="center"><?php echo $vmois; ?> </th>
              <?php } ?>
            </tr>
            <!--</thead>  -->
<?php  if($totalRows_edit_ms>0) { do {
$code = $row_edit_ms['code'];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."ptba where projet='".$_SESSION["clp_projet"]."' and annee='$annee' and code_activite_ptba like '$code%' ORDER By code_activite_ptba asc";
$liste_rec = mysql_query($query_liste_rec, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_rec = mysql_fetch_assoc($liste_rec);
$totalRows_liste_rec = mysql_num_rows($liste_rec);
$totalRows_liste_rec = mysql_num_rows($liste_rec); ?>
            <tr bgcolor="#BED694">
              <td colspan="15" align="center" style="background-color: #BED694;">
                <b><?php echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?></b>
              </td>
            </tr>
		<?php	if($totalRows_liste_rec>0 && $totalRows_tache>0) {$i=0; $t=0; $p2=$p1="j"; ?>
<?php $row=""; do { $code_act = $row_liste_rec['id_ptba']; if(isset($tache_array[$code_act])){

foreach($tache_array[$code_act] as $a=>$b){ ?>
            <tr>
<?php if($row!=$b["id_ptba"]){ ?>
<td rowspan="<?php echo count($tache_array[$code_act]); ?>"><?php echo "<b>".$row_liste_rec['code_activite_ptba'].'</b> : '.$row_liste_rec['intitule_activite_ptba']; ?></td>
<?php } 

?>
<td><?php echo $b['intitule_tache']; ?></td>
<td width="50" align="center"><?php echo $b['proportion'].'%'; ?></td>
<?php foreach($tableauMois as $vmois){
$amois = explode('<>',$vmois); $periode = isset($b['periode'])?explode(',',$b['periode']):array(); ?>
<td width="50" class="<?php echo (in_array($amois[0],$periode))?"marquer":""; ?>" valign="middle" align="center"><?php echo (in_array($amois[0],$periode))?"<b></b>":""; ?></td>
<?php } ?>
            </tr>
            <?php $row=$b["id_ptba"]; } }  } while ($row_liste_rec= mysql_fetch_assoc($liste_rec)); ?>
            <?php } else { ?>
           <tr>
              <td colspan="9"><em><strong>Aucune tâche/activit&eacute; enregistr&eacute;e! </strong></em></td>
            </tr>
            <?php }  ?>
      <?php } while ($row_edit_ms = mysql_fetch_assoc($edit_ms)); } else { ?>
      <tr>
        <td colspan="9" align="center"><strong><em>Aucune composante trouv&eacute;e!</em></strong></td>
      </tr>
      <?php } ?>
</table>