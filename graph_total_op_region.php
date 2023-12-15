<?php



///////////////////////////////////////////////



/*                 SSE                       */



/*	Conception & DÃƒÂ©veloppement: SEYA SERVICES */



///////////////////////////////////////////////



//session_start();



include_once 'system/configuration.php';



$config = new Config;







/*if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}*/



include_once $config->sys_folder . "/database/db_connexion.php";



//header('Content-Type: text/html; charset=ISO-8859-15');

$array_indic = array("OUI/NON","texte");

//number_format(0, 0, ',', ' ');



?>



  <?php
//typologie de l indicateur
$query_liste_classe = sprintf("SELECT distinct col36 FROM `t_1615891739` WHERE `col36`!='' and `col37`!=''  ORDER BY `t_1615891739`.`col36` ASC");
try{
    $liste_classe = $pdar_connexion->prepare($query_liste_classe);
    $liste_classe->execute();
    $row_liste_classe = $liste_classe ->fetchAll();
    $totalRows_liste_classe = $liste_classe->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$borne_classe_array = array();
if($totalRows_liste_classe>0){ foreach($row_liste_classe as $row_liste_classe){
$borne_classe_array[$row_liste_classe["col36"]]=$row_liste_classe["col36"];

}}

//typologie de l indicateur
$query_liste_periode = "SELECT count(`Id`) as nb_op, col37   FROM t_1615891739 WHERE `col36`!='' and `col37`!=''  group by col37";	
try{
    $liste_periode = $pdar_connexion->prepare($query_liste_periode);
    $liste_periode->execute();
    $row_liste_periode = $liste_periode ->fetchAll();
    $totalRows_liste_periode = $liste_periode->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$zone_periode_array =array(); $np=0; $tab_zone="";
if($totalRows_liste_periode>0){ foreach($row_liste_periode as $row_liste_periode){
$np++;
$tab_zone.="{ name:'".$row_liste_periode["col37"]."', y: ".$row_liste_periode["nb_op"].", drilldown:'".$row_liste_periode["col37"]."'} ,";
$zone_periode_array[$row_liste_periode["col37"]]=$row_liste_periode["col37"];
}}
$tab_zone=substr($tab_zone, 0, -1); 

//print_r($nb_classe_array);
//exit;
 $query_liste_region = "SELECT count(`Id`) as nb_op, col37, col36   FROM t_1615891739 WHERE `col36`!='' and `col37`!='' group by col37, col36";	 
  try{
    $liste_region = $pdar_connexion->prepare($query_liste_region);
    $liste_region->execute();
    $row_liste_region = $liste_region ->fetchAll();
    $totalRows_liste_region = $liste_region->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $tab_region_array=array(); $liste_region_array="";
if($totalRows_liste_region>0){ $zone="j";
foreach($row_liste_region as $row_liste_region){
 if(!isset($tab_region_array[$row_liste_region["col37"]])) $tab_region_array[$row_liste_region["col37"]]="{name: '".$row_liste_region["col37"]."', id: '".$row_liste_region["col37"]."', data:[ ";
if(isset($borne_classe_array[$row_liste_region["col36"]])) $classenam=$borne_classe_array[$row_liste_region["col36"]]; else $classenam="NaN";  $tab_region_array[$row_liste_region["col37"]].="['".$classenam."',".$row_liste_region["nb_op"]."],"; 
}}	


		

//print_r($tab_region_array);
//exit;
//$liste_region_array=substr($liste_region_array, 0, -1); 
?>





<!DOCTYPE HTML>



<html>



	<head>



        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



		<title>Graph indicateur<?php ///echo "PTBA $annee par ".((isset($libelle[0])?$libelle[0]:"composante"));      ?></title>



        <style type="text/css">
<!--
.table {  border-spacing: 0px !important; border-collapse: collapse; width: 100%!important;
}
.table {border-spacing: 0px !important; border-collapse: collapse; font-size: small; }
-->
        </style>
</head>
<script src="<?php print $config->script_folder; ?>/new_highcharts.js"></script>
<script src="<?php print $config->script_folder; ?>/data.js"></script>
<script src="<?php print $config->script_folder; ?>/drilldown.js"></script>
	<body>


<!-- debut graph typologie -->

<div id="graph_indTotalMP" style="min-width: 310px; max-width: 470px; height: 300px; margin: 0 auto"></div>

<script type="text/javascript">
Highcharts.chart('graph_indTotalMP', {
    chart: {
		 backgroundColor: {
                linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
    stops: [
        [0, '#FFFFFF'],
        [1, '#C1FFC1']
    ]
            },
            type: 'column'
        },
   
    title: {
        text: 'Demande par FILIERES/MAILLONS'
    },
  /*  subtitle: {
        text: 'Nombre d\OP b\u00e9sn\u00e9sficiaires d\'intrants'
    },*/
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Nombre'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.0f}'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b><br/>'
    },

    series: [{
        name: 'FILIERES',
        colorByPoint: true,
        data: [<?php echo $tab_zone;  ?>]
    }],
	
	 credits: {
                enabled: true,
                href: '#',
                text: 'RUCHE  : <?php echo date("d/m/Y H:i"); ?>',
                style: {
                cursor: 'pointer',
                color: '#6633FF',
                fontSize: '10px',
                margin: '10px'
                }
             },
			 
    drilldown: {
        series: [  <?php foreach($zone_periode_array as $a=>$b){ if(isset($tab_region_array[$a])) echo $tab_region_array[$a]."] },"; }  ?>]
    }
});
</script>
  
	
<?php // } ?>


	</body>



</html>