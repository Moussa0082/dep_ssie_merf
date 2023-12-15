<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
$path = '../';
include_once $path . 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"])) {
//header(sprintf("Location: %s", "./"));
  exit;
}
include_once $path . $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');

$cd=(isset($_GET["id_ms"]) && !empty($_GET["id_ms"]) && intval($_GET["id_ms"])>0)?intval($_GET["id_ms"]):1;
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

function NbJours($debut, $fin) {



  $tDeb = explode("-", $debut);

  $tFin = explode("-", $fin);



  $diff = (mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]));



  return(($diff / 86400)+1);



}



$editFormAction = $_SERVER['PHP_SELF'];
/*
if (isset($_SERVER['QUERY_STRING'])) {

  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);

}*/

$id_m=(isset($_GET['id_ms']))?$_GET['id_ms']:0;  //projet='".$_SESSION["clp_projet"]."' and
$query_edit_ms = "SELECT * FROM ".$database_connect_prefix."mission_supervision where  id_mission='$id_m'";
           try{
    $edit_ms = $pdar_connexion->prepare($query_edit_ms);
    $edit_ms->execute();
    $row_edit_ms = $edit_ms ->fetch();
    $totalRows_edit_ms = $edit_ms->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


if(isset($row_edit_ms['id_mission'])) $id=$row_edit_ms['id_mission']; else $id=0;
$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."recommandation_mission, ".$database_connect_prefix."rubrique_projet where mission='$id' and rubrique=code_rub ORDER BY code_rub asc, numero asc";
           try{
    $liste_rec = $pdar_connexion->prepare($query_liste_rec);
    $liste_rec->execute();
    $row_liste_rec = $liste_rec ->fetchAll();
    $totalRows_liste_rec = $liste_rec->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$non_execute = $non_entame = $encours = $execute = 0;

$t=0; if($totalRows_liste_rec>0) {foreach($row_liste_rec as $row_liste_rec){  $code_ms=$row_liste_rec["mission"];

    $query_suivi_plan_ms = "SELECT sum(proportion) as texrecms, code_rec  FROM ".$database_connect_prefix."mission_plan, ".$database_connect_prefix."recommandation_mission  where code_rec=id_recommandation and mission='$id' and valider=1 group by code_rec";
             try{
    $suivi_plan_ms = $pdar_connexion->prepare($query_suivi_plan_ms);
    $suivi_plan_ms->execute();
    $row_suivi_plan_ms = $suivi_plan_ms ->fetchAll();
    $totalRows_suivi_plan_ms = $suivi_plan_ms->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $prop_tab = array();
  if($totalRows_suivi_plan_ms>0){  foreach($row_suivi_plan_ms as $row_suivi_plan_ms){
    $prop_tab[$row_suivi_plan_ms["code_rec"]]=$row_suivi_plan_ms["texrecms"];
     }
  }

////statut gestion
$cd=$row_liste_rec["id_recommandation"];

if(isset($prop_tab[$cd])){ if($prop_tab[$cd]<100) $encours++; else $execute++; } elseif(date("Y-m-d")>$row_liste_rec['date_buttoir'] && $row_liste_rec['type']!="Continu") $non_execute++; else $non_entame++;


 } 
}
//if($totalRows_liste_cp>0 && $taux_annuel>0){
 if(isset($totalRows_liste_rec) && $totalRows_liste_rec>0){  
?>
<!DOCTYPE HTML>
<html>
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Statistiques des missions de supervisions></title>

		<script type="text/javascript" src="<?php print $path.$config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
		
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
                text: '<?php  echo $row_edit_ms['type']." du ".implode('-',array_reverse(explode('-',$row_edit_ms['debut'])))." au ".implode('-',array_reverse(explode('-',$row_edit_ms['fin']))); ?>'
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
            credits: {
                enabled: true,
                href: 'http:#',
                text: 'RUCHE : <?php echo date("d/m/Y H:i"); ?>',
                style: {
                cursor: 'pointer',
                color: '#6633FF',
                fontSize: '10px',
                margin: '10px'
                }
             },
            series: [{
                type: 'pie',
                name: '% Statut',
                data: [ ['EN COURS',  <?php echo $encours; ?>],['<?php echo "REALISE"; ?>',  <?php echo $execute;  ?>],['<?php echo utf8_decode("NON EXECUTE"); ?>',  <?php echo $non_execute;  ?>],['<?php echo utf8_decode("NON ENTAME"); ?>',  <?php echo $non_entame;  ?>] ]
            }]
        });
    });
});
		</script>
<?php //} ?>
	</head>
	<body>
<script src="<?php print $path.$config->script_folder; ?>/highcharts.js"></script>
<script src="<?php print $path.$config->script_folder; ?>/modules/exporting.js"></script>
<script src="<?php print $path.$config->script_folder; ?>/modules/offline-exporting.js"></script>

<div id="container1" style=" margin: 0 auto"></div>
<?php }else echo '<h1 align="center"><br /><br />'.utf8_encode("Aucune recommandation pour cette mission").' !</h1>';  ?>
	</body>
</html>
