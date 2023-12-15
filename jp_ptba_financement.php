<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();  $path = './';
include_once $path.'system/configuration.php';
$config = new Config;
       /*
if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
} */
include_once $path.$config->sys_folder . "/database/db_connexion.php";

$tableauCp = array();
$tableauCoutCp = array();
if(isset($_GET['annee'])) $annee=$_GET['annee']; else $annee=date("Y");

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_taux_annee = "SELECT SUM( if(cout_prevu>0, cout_prevu,0) ) AS taux FROM ".$database_connect_prefix."code_analytique where  annee=$annee";					
$taux_annee  = mysql_query($query_taux_annee , $pdar_connexion) or die(mysql_error());
$row_taux_annee  = mysql_fetch_assoc($taux_annee);
$totalRows_taux_annee  = mysql_num_rows($taux_annee);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SELECT SUM( if(cout_prevu>0, cout_prevu,0) ) AS ct, right(code_categorie,2) as cp  FROM ".$database_connect_prefix."code_analytique where  annee=$annee group by cp";
$liste_cp  = mysql_query($query_liste_cp , $pdar_connexion) or die(mysql_error());
$row_liste_cp  = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp  = mysql_num_rows($liste_cp);

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
if($scp!=0) $query_acteur_p = "SELECT  intitule_sous_composante  FROM  sous_composante where id_sous_composante='$scp'";
else $query_acteur_p = "SELECT  '' as intitule_sous_composante  FROM  sous_composante limit 1";
$acteur_p  = mysql_query($query_acteur_p , $pdar_connexion) or die(mysql_error());
$row_acteur_p  = mysql_fetch_assoc($acteur_p);
$totalRows_acteur_p  = mysql_num_rows($acteur_p);
$acteur_act=$row_acteur_p['intitule_sous_composante'];
//$pour="pour ".$row_acteur_p['intitule_sous_composante'];
if($scp!=0) {$acteur_act=" (".$row_acteur_p['intitule_sous_composante'].")"; $pour=" (".$row_acteur_p['intitule_sous_composante'].")";} else {$acteur_act=$pour="";}*/
//

 mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$query_liste_bailleur = "SELECT intitule, code_type from ".$database_connect_prefix."type_part ORDER BY code_type asc";
	$liste_bailleur = mysql_query($query_liste_bailleur, $pdar_connexion) or die(mysql_error());
	$row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
	$totalRows_liste_bailleur = mysql_num_rows($liste_bailleur);
	$bailleur_array = array();
    if($totalRows_liste_bailleur>0){ 
	 do{ $bailleur_array[$row_liste_bailleur["code_type"]]=$row_liste_bailleur["code_type"].": ".$row_liste_bailleur["intitule"];  }
	while($row_liste_bailleur  = mysql_fetch_assoc($liste_bailleur));}
//

$taux_annuel=$row_taux_annee['taux'];
$cout=0;
if($totalRows_liste_cp>0 && $taux_annuel>0){
$cout=$row_liste_cp['ct'];
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;">
		<title>Part de financement en <?php echo "$annee ";  ?></title>

		<script type="text/javascript" src="<?php print $config->script_folder; ?>/jquery.min.js"></script>
		<script type="text/javascript">
$(function () {
    var chart;

    $(document).ready(function () {

    	// Build the chart
        $('#container').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Part de financement des activités  en <?php echo "$annee";  ?>'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br /><u>Montant total</u>: <b><?php echo number_format($taux_annuel, 0, ',', ' ');  ?> Ouguiya</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.0f} %'
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: 'Part',
                data: [
<?php
$data = "";
if($totalRows_liste_cp>0) {do {
	$const=$taux_annuel;
	
    $nombre = ($const>0)?$row_liste_cp['ct']:0;
	//if(isset($row_liste_cp['ct'])) $cout=
	$data .= "['".$row_liste_cp['cp']."',  ".$nombre."],";
} while ($row_liste_cp = mysql_fetch_assoc($liste_cp)); }
echo substr($data,0,strlen($data)-1);
  ?>
                ]
            }]
        });
    });

});
		</script>
	</head>
	<body>
<script src="<?php print $config->script_folder; ?>/highcharts.js"></script>
<script src="<?php print $config->script_folder; ?>/modules/exporting.js"></script>

<div id="container" style="min-width: 400px; height: 300px; margin: 0 auto"></div>
Généré le <?php echo date("d/m/Y")." à ".date("H:i:s");  ?>

	</body>
</html>
<?php }else echo '<h1 align="center"><br /><br />Aucun coût planifié !</h1>';  ?>