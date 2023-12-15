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

$cd=(isset($_GET["id"]) && !empty($_GET["id"]) && intval($_GET["id"])>0)?intval($_GET["id"]):1;
if(isset($_GET['annee']) && $_GET['annee']>0) {$annee=$_GET['annee'];} else {$annee=date("Y");}

$tab_encours = array();

$tab_execute = array();

$tab_non_execute = array();

$tab_non_entame = array();

//$id=0;



$encours = 0;

$execute = 0;

$non_execute = 0;

$non_entame = 0;



$currentPage = $_SERVER["PHP_SELF"];

// session_start();


//fonction calcul nb jour

/*function NbJours($debut, $fin) {



  $tDeb = explode("-", $debut);

  $tFin = explode("-", $fin);



  $diff = (mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]));



  return(($diff / 86400)+1);



}*/



$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {

  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);

}

$id_m=(isset($_GET['id']))?$_GET['id']:0;
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_ms = "SELECT * FROM ".$database_connect_prefix."mission_supervision where projet='".$_SESSION["clp_projet"]."' and code_ms='$id_m'";
$edit_ms = mysql_query($query_edit_ms, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_ms = mysql_fetch_assoc($edit_ms);
$totalRows_edit_ms = mysql_num_rows($edit_ms);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rubrique = "select ".$database_connect_prefix."suivi_recommandation_mission.* from ".$database_connect_prefix."suivi_recommandation_mission, ".$database_connect_prefix."recommandation_mission where numero=".$database_connect_prefix."suivi_recommandation_mission.recommandation and mission='$id_m' order by recommandation asc";
//$query_liste_rubrique = "select * from ".$database_connect_prefix."suivi_recommandation_mission order by recommandation asc";
$liste_rubrique = mysql_query($query_liste_rubrique, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_rubrique = mysql_fetch_assoc($liste_rubrique);
$totalRows_liste_rubrique = mysql_num_rows($liste_rubrique);
$tableau_stat = array();
if($totalRows_liste_rubrique>0){  do{
  $tableau_stat[$row_liste_rubrique["recommandation"]]=$row_liste_rubrique["statut"]; }while($row_liste_rubrique = mysql_fetch_assoc($liste_rubrique));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
if(isset($row_edit_ms['id_mission']))$id=$row_edit_ms['code_ms']; else $id=0;
$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."recommandation_mission, ".$database_connect_prefix."rubrique_projet where mission='$id' and ".$database_connect_prefix."recommandation_mission.rubrique=code_rub ORDER BY code_rub asc, numero asc";
$liste_rec = mysql_query($query_liste_rec, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_rec = mysql_fetch_assoc($liste_rec);
$totalRows_liste_rec = mysql_num_rows($liste_rec);

$non_execute = $non_entame = $encours = $execute = 0;

$t=0; if($totalRows_liste_rec>0) { do {
////statut gestion
$cd=$row_liste_rec["id_recommandation"];

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_rec = "SELECT statut FROM ".$database_connect_prefix."suivi_recommandation_mission WHERE recommandation=$cd ORDER BY date_execution DESC LIMIT 1";
$edit_rec = mysql_query($query_edit_rec, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_rec = mysql_fetch_assoc($edit_rec);
$totalRows_edit_rec = mysql_num_rows($edit_rec);

$color = "red"; $stat="Non entamé";

if(isset($tableau_stat[$row_liste_rec['numero']])) $stat=$tableau_stat[$row_liste_rec['numero']];

if($stat=="Non entamé") $non_entame++;
elseif($stat=="Réalisé") $execute++;
elseif($stat=="En cours") $encours++;
elseif($stat=="Non echu") $non_execute++;

 } while ($row_liste_rec = mysql_fetch_assoc($liste_rec));

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
<?php if(isset($totalRows_liste_rec) && $totalRows_liste_rec>0){  ?>
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function () {

    	// Build the chart
        $('#container1').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Situation de la mission'
            },
            tooltip: {
        	    pointFormat: '<br /><b>{point.percentage:.0f}% des recommandations</b><br /></b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '{point.name}: <b>{point.percentage:.0f} %</b>'
                },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: '% Statut',
                data: [ ['En cours',  <?php echo $encours; ?>],['Réalisé',  <?php echo $execute;  ?>],['<?php echo utf8_decode("Non echu"); ?>',  <?php echo $non_execute;  ?>],['Non entamé',  <?php echo $non_entame;  ?>] ]
            }]
        });
    });
});
		</script>
<?php } ?>
<?php if(isset($id)) {?>


<div class="col-md-6">
<div class="widget box">
<div class="widget-header" title=""> <h4><i class="icon-reorder"></i> <?php  echo $row_edit_ms['type']." du ".implode('-',array_reverse(explode('-',$row_edit_ms['debut'])))." au ".implode('-',array_reverse(explode('-',$row_edit_ms['fin']))); ?>&nbsp;&nbsp;&nbsp;Objet&nbsp;&nbsp;:<?php if(isset($row_edit_ms['observation'])) echo substr($row_edit_ms['observation'],0, 170)." ..."; ?></h4></div>
<div class="widget-content">
<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive">
    <!--<thead>-->
    <tr>
      <th>Statut</th>
      <th>Nombre</th>
      <th>Taux</th>
    </tr>
    <!--</thead>-->
    <tr>
      <td><div align="center">En cours</div></td>
      <td><div align="center"><?php echo $encours;  ?></div></td>
      <td><div align="center"><?php echo (isset($totalRows_liste_rec) && $totalRows_liste_rec>0)?number_format(($encours/$totalRows_liste_rec)*100, 0, ',', ' ')."%":"0%";  ?></div></td>
    </tr>
    <tr>
      <td><div align="center">R&eacute;alis&eacute;</div></td>
      <td><div align="center"><span class="Style2"><?php echo $execute;  ?></span></div></td>
      <td><div align="center"><span class="Style2"><?php echo (isset($totalRows_liste_rec) && $totalRows_liste_rec>0)?number_format(($execute/$totalRows_liste_rec)*100, 0, ',', ' ')."%":"0%";  ?></span></div></td>
    </tr>
    <tr>
      <td><div align="center"><span class="Style2">D&eacute;lai d'ex&eacute;cution non &eacute;chu</span></div></td>
      <td><div align="center"><span class="Style2"><?php echo $non_execute;  ?></span></div></td>
      <td><div align="center"><span class="Style2"><?php echo (isset($totalRows_liste_rec) && $totalRows_liste_rec>0)?number_format(($non_execute/$totalRows_liste_rec)*100, 0, ',', ' ')."%":"0%";  ?></span></div></td>
    </tr>
    <tr>
      <td><div align="center"><span class="Style2">Non ex&eacute;cut&eacute;</span></div></td>
      <td><div align="center"><span class="Style2"><?php echo $non_entame;  ?></span></div></td>
      <td><div align="center"><span class="Style2"><?php echo (isset($totalRows_liste_rec) && $totalRows_liste_rec>0)?number_format(($non_entame/$totalRows_liste_rec)*100, 0, ',', ' ')."%":"0%";  ?></span></div></td>
    </tr>
    <tfoot>
    <tr>
      <td><div align="center"><span class="Style2">Total</span></div></td>
      <td><div align="center"><span class="Style2"><?php echo (isset($totalRows_liste_rec) && $totalRows_liste_rec>0)?$totalRows_liste_rec:0;  ?></span></div></td>
      <td><div align="center"><span class="Style2"><?php echo (isset($totalRows_liste_rec) && $totalRows_liste_rec>0)?number_format(100, 0, ',', ' ')."%":"0%";  ?></span></div></td>
    </tr>
    </tfoot>
  </table>

</div></div>
</div>

<div class="col-md-6">
<div class="widget box">
<div class="widget-header" title=""> <h4><i class="icon-reorder"></i> Graphique de <?php  echo $row_edit_ms['type']." du ".implode('-',array_reverse(explode('-',$row_edit_ms['debut'])))." au ".implode('-',array_reverse(explode('-',$row_edit_ms['fin']))); ?> </h4></div>
<div class="widget-content">
  <script src="assets/js/highcharts.js"></script>
  <script src="assets/js/modules/exporting.js"></script>
  <div id="container1" style="min-width: 310px; height: 250px; margin: 0 auto;"></div>
</div></div>
</div>

<?php }  ?>
