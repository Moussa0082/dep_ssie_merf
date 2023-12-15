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
?>
  <?php
  
  $query_liste_cp = "select * from ugl where nom_ugl!='DCI' order by code_ugl";
try{
    $liste_cp = $pdar_connexion->prepare($query_liste_cp);
    $liste_cp->execute();
    $row_liste_cp = $liste_cp ->fetchAll();
    $totalRows_liste_cp = $liste_cp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$nom_antenne_array =array(); 

if($totalRows_liste_cp>0){  foreach($row_liste_cp as $row_liste_cp){ $nom_antenne_array[$row_liste_cp["code_ugl"]]=$row_liste_cp["nom_ugl"]; } }


  $query_liste_cp = "select * from departement  order by code_departement";
try{
    $liste_cp = $pdar_connexion->prepare($query_liste_cp);
    $liste_cp->execute();
    $row_liste_cp = $liste_cp ->fetchAll();
    $totalRows_liste_cp = $liste_cp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$nom_cercle_array =array(); 

if($totalRows_liste_cp>0){  foreach($row_liste_cp as $row_liste_cp){ $nom_cercle_array[$row_liste_cp["code_departement"]]=$row_liste_cp["nom_departement"]; } }

//typologie de l indicateur
$query_liste_periode = "select  count(distinct id_op) as nb_mp, count(membre_groupement.id_membre) as cout, fiche_ong.code_ugl from liste_op, fiche_ong, membre_groupement where fiche_ong.id_ong=liste_op.faitiere and membre_groupement.groupement=liste_op.id_op group by fiche_ong.code_ugl order by code_ugl desc";
try{
    $liste_periode = $pdar_connexion->prepare($query_liste_periode);
    $liste_periode->execute();
    $row_liste_periode = $liste_periode ->fetchAll();
    $totalRows_liste_periode = $liste_periode->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$zone_periode_array =array(); $np=0; $tab_zone="";
if($totalRows_liste_periode>0){ foreach($row_liste_periode as $row_liste_periode){
$np++; if(isset($nom_antenne_array[$row_liste_periode["code_ugl"]])) $row_liste_periode["code_ugl"]=$nom_antenne_array[$row_liste_periode["code_ugl"]];
$tab_zone.="{ name:'".$row_liste_periode["code_ugl"]."/".$row_liste_periode["nb_mp"]."', y: ".$row_liste_periode["cout"].", drilldown:'".$row_liste_periode["code_ugl"]."'} ,";
$zone_periode_array[$row_liste_periode["code_ugl"]]=$row_liste_periode["code_ugl"];
} }
$tab_zone=substr($tab_zone, 0, -1);
//echo $tab_zone;
 $query_liste_region = " select  count(distinct id_op) as nb_mp, count(membre_groupement.id_membre) as cout, fiche_ong.code_ugl, left(village, 4) as cercle from liste_op, fiche_ong, membre_groupement where fiche_ong.id_ong=liste_op.faitiere and membre_groupement.groupement=liste_op.id_op group by fiche_ong.code_ugl, left(village, 4) order by code_ugl desc";
try{
    $liste_region = $pdar_connexion->prepare($query_liste_region);
    $liste_region->execute();
    $row_liste_region = $liste_region ->fetchAll();
    $totalRows_liste_region = $liste_region->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $tab_region_array=array(); $liste_region_array=""; 
if($totalRows_liste_region>0){ $zone="j";
 foreach($row_liste_region as $row_liste_region){
if(isset($nom_cercle_array[$row_liste_region["cercle"]])) $row_liste_region["cercle"]=$nom_cercle_array[$row_liste_region["cercle"]];
if(isset($nom_antenne_array[$row_liste_region["code_ugl"]])) $row_liste_region["code_ugl"]=$nom_antenne_array[$row_liste_region["code_ugl"]];
 if(!isset($tab_region_array[$row_liste_region["code_ugl"]])) $tab_region_array[$row_liste_region["code_ugl"]]="{name: '".$row_liste_region["code_ugl"]."', id: '".$row_liste_region["code_ugl"]."', data:[ ";
//$liste_region_array.="'".$row_liste_region["nom_region"]."',";
$tab_region_array[$row_liste_region["code_ugl"]].="['".$row_liste_region["cercle"]."/".$row_liste_region["nb_mp"]."',".$row_liste_region["cout"]."],"; 
 //if($zone!=$row_liste_region['reference']) { $tab_region_array[$row_liste_region["reference"]].="]";$zone==$row_liste_region['reference'];}
} }
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
<div id="graph_indRC" style="max-width: 500px;  height: 300px; margin: 0 auto"></div>
<script type="text/javascript">
function Arrondir( nomber, nbApVirg ) { 
return ( parseInt(nomber * Math.pow(10,nbApVirg) + 0.5) ) / Math.pow(10,nbApVirg); 
} 
function formatMillier(nombre) { 
var nbrArrnd = Arrondir(nombre, 2); 
return new Intl.NumberFormat().format( nbrArrnd ); 
} 
Highcharts.chart('graph_indRC', {
    chart: {
		/* backgroundColor: {
                linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
    stops: [
        [0, '#FFFFFF'],
        [1, '#C1FFC1']
    ]
            },*/
			
            type: 'pie'
        },
    title: {
        text: ''
    },
    subtitle: {
        text: 'Groupement par antenne'
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
					 s='<b>'+tabname[0]+' </b><br/> Groupement: <b>'+tabname[1]+'</b>  <br/> Membre:<b>'+ formatMillier(this.point.y)+'<b/>' ;
            return s;
        },
		
        shared: true
    },
	
    series: [{
        name: 'Antenne',
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