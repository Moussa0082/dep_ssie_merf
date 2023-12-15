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
//header('Content-Type: text/html; charset=UTF-8');
$array_indic = array("OUI/NON","texte");
//number_format(0, 0, ',', ' ');
$chart_name=isset($_GET['chart_name'])?$_GET['chart_name']:"";
?>
  <?php

$query_liste_version = "SELECT * FROM ".$database_connect_prefix."version_ptba";
try{
    $liste_version = $pdar_connexion->prepare($query_liste_version);
    $liste_version->execute();
    $row_liste_version = $liste_version ->fetchAll();
    $totalRows_liste_version = $liste_version->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
 $version_array = array();
if($totalRows_liste_version>0){ foreach($row_liste_version as $row_liste_version){
//$version_array[]=."<>".$row_liste_version["version_ptba"]."<>".$row_liste_version["annee_ptba"];
$version_array[$row_liste_version["id_version_ptba"]] = $row_liste_version["annee_ptba"];
 } }
//typologie de l indicateur
$query_liste_periode = "SELECT sum(cout_realise) as cout, annee, sum(cout_prevu) as nb_mp FROM code_activite where code_activite.projet='".$_SESSION["clp_projet"]."' and code!='Code' and code!='fichiers' and annee in (select id_version_ptba FROM version_ptba) group by annee order by annee desc";
try{
    $liste_periode = $pdar_connexion->prepare($query_liste_periode);
    $liste_periode->execute();
    $row_liste_periode = $liste_periode ->fetchAll();
    $totalRows_liste_periode = $liste_periode->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$zone_periode_array =array(); $np=0; $tab_zone="";
if($totalRows_liste_periode>0){ foreach($row_liste_periode as $row_liste_periode){
$np++; if(isset($version_array[$row_liste_periode["annee"]])) $row_liste_periode["annee"]=$version_array[$row_liste_periode["annee"]];
$tab_zone.="{ name:'".$row_liste_periode["annee"]."/".$row_liste_periode["nb_mp"]."', y: ".$row_liste_periode["cout"].", drilldown:'".$row_liste_periode["annee"]."'} ,";
$zone_periode_array[$row_liste_periode["annee"]]=$row_liste_periode["annee"];
} }
$tab_zone=substr($tab_zone, 0, -1);
//echo $tab_zone;

//exit;
 $query_liste_region = " SELECT sum(cout_realise) as cout, annee, sum(cout_prevu) as nb_mp, left(code,1) as cp FROM code_activite where code_activite.projet='".$_SESSION["clp_projet"]."' and code!='Code' and code!='fichiers' and annee in (select id_version_ptba FROM version_ptba) group by annee, left(code,1) order by annee desc";
try{
    $liste_region = $pdar_connexion->prepare($query_liste_region);
    $liste_region->execute();
    $row_liste_region = $liste_region ->fetchAll();
    $totalRows_liste_region = $liste_region->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $tab_region_array=array(); $liste_region_array="";
if($totalRows_liste_region>0){ $zone="j";
 foreach($row_liste_region as $row_liste_region){
 $row_liste_region["cp"]="Composante ".$row_liste_region["cp"];
 if(isset($version_array[$row_liste_region["annee"]])) $row_liste_region["annee"]=$version_array[$row_liste_region["annee"]];
 //$zone=$row_liste_region['reference'];
 if(!isset($tab_region_array[$row_liste_region["annee"]])) $tab_region_array[$row_liste_region["annee"]]="{name: '".$row_liste_region["annee"]."', id: '".$row_liste_region["annee"]."', data:[ ";
//$liste_region_array.="'".$row_liste_region["nom_region"]."',";
$tab_region_array[$row_liste_region["annee"]].="['".$row_liste_region["cp"]."/".$row_liste_region["nb_mp"]."',".$row_liste_region["cout"]."],"; 
 //if($zone!=$row_liste_region['reference']) { $tab_region_array[$row_liste_region["reference"]].="]";$zone==$row_liste_region['reference'];}
} }
		
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
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
	<body>
<!-- debut graph typologie -->
<div id="graph_indRC<?php echo $chart_name; ?>" style="max-width: 500px;  height: 300px; margin: 0 auto"></div>
<script type="text/javascript">
function Arrondir( nomber, nbApVirg ) { 
return ( parseInt(nomber * Math.pow(10,nbApVirg) + 0.5) ) / Math.pow(10,nbApVirg); 
} 
function formatMillier(nombre) { 
var nbrArrnd = Arrondir(nombre, 2); 
return new Intl.NumberFormat().format( nbrArrnd ); 
} 
Highcharts.chart('graph_indRC<?php echo $chart_name; ?>', {
    chart: {
		 backgroundColor: {
                linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
    stops: [
        [0, '#FFFFFF'],
        [1, '#C1FFC1']
    ]
            },
			
            type: 'pie'
        },
    title: {
        text: ''
    },
    subtitle: {
        text: 'Part de d\u00e9caissement par ann\u00e9e'
    },
	
	  legend: {
            enabled: true,
       layout: 'horizontal',
	   itemDistance: 5,
           
	  labelFormatter: function () {
	   var tabnameserie = this.name.split('/');
            return tabnameserie[0];
        }
        },
    plotOptions: {
	
	pie: {
            size:'100%',
            dataLabels: {
				 backgroundColor: 'rgba(252, 255, 197, 0.7)'
            },
			showInLegend: true
        },
		
        series: {
            dataLabels: {
                enabled: true,
				 verticalAlign: 'top',
                    enabled: true,
                    color: '#000000',
                    connectorWidth: 1,
                    distance: 20,
                    connectorColor: '#000000',
					
                    formatter: function() {
						
                        return Math.round(this.percentage) + ' %';
						}
                
            }
        }
    },
  /*  tooltip: {
	 formatter: function() {
						 var tabname = this.point.name
                        //return Math.round(this.percentage) + ' %';
						}
       
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}"></span>: <b>{point.percentage:.2f}%</b> du total<br/>'
		 
    },*/
	
	   tooltip: {
        formatter: function () {
           // var s = '<b>' + this.x + '</b>';
			 var tabname = this.point.name.split('/');
					 s='<b>'+tabname[0]+' </b><br/> Décaissé: <b>'+tabname[1]+'</b>  <br/> Prévu:<b>'+ formatMillier(this.point.y)+'<b/>' ;
            return s;
        },
		
        shared: true
    },
	
    series: [{
        name: 'Ann\u00e9e',
        colorByPoint: true,
        data: [
		 <?php echo $tab_zone;  ?>
		
		]
    }],
	 credits: {
                enabled: true,
                href: '#',
                text: 'RUCHE : <?php echo date("d/m/Y H:i"); ?>',
                style: {
                cursor: 'pointer',
                color: '#6633FF',
                fontSize: '10px',
                margin: '10px'
                }
             },
    drilldown: {
        series: [
		
		 <?php foreach($zone_periode_array as $a=>$b){ if(isset($tab_region_array[$a])) echo $tab_region_array[$a]."] },"; }  ?>
	
		
		]
    }
});</script>
  
	
<?php // } ?>
	</body>
</html>