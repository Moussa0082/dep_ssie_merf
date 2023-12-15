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
$query_liste_periode = "SELECT count(`id`) as superf, col3, col28 FROM t_1615891739 WHERE `col28`!='' and `col3`!='' and col3<6 group by col28, col3";	
try{
    $liste_periode = $pdar_connexion->prepare($query_liste_periode);
    $liste_periode->execute();
    $row_liste_periode = $liste_periode ->fetchAll();
    $totalRows_liste_periode = $liste_periode->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$valeur_periode_array =$tab_region_val_array = array(); $np=0;
if($totalRows_liste_periode>0){ foreach($row_liste_periode as $row_liste_periode){
$np++;
if($row_liste_periode["col3"]==1) $row_liste_periode["col3"]="Maritime";
elseif($row_liste_periode["col3"]==2) $row_liste_periode["col3"]="Plateaux";
elseif($row_liste_periode["col3"]==3) $row_liste_periode["col3"]="Centrale";
elseif($row_liste_periode["col3"]==4) $row_liste_periode["col3"]="Kara";
elseif($row_liste_periode["col3"]==5) $row_liste_periode["col3"]="Savanes";

$valeur_periode_array[$row_liste_periode["col28"]][$row_liste_periode["col3"]]=$row_liste_periode["superf"];
$tab_region_val_array[$row_liste_periode["col3"]]=$row_liste_periode["col3"];
}}


$query_liste_region = sprintf("SELECT distinct col3 FROM `t_1615891739` WHERE `col28`!='' and `col3`!='' and col3<6 ORDER BY `t_1615891739`.`col3` ASC");
try{
    $liste_region = $pdar_connexion->prepare($query_liste_region);
    $liste_region->execute();
    $row_liste_region = $liste_region ->fetchAll();
    $totalRows_liste_region = $liste_region->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$tab_region_array = array(); $liste_region_array="";
if($totalRows_liste_classe>0){ foreach($row_liste_region as $row_liste_region){


if($row_liste_region["col3"]==1) $row_liste_region["col3"]="Maritime";
elseif($row_liste_region["col3"]==2) $row_liste_region["col3"]="Plateaux";
elseif($row_liste_region["col3"]==3) $row_liste_region["col3"]="Centrale";
elseif($row_liste_region["col3"]==4) $row_liste_region["col3"]="Kara";
elseif($row_liste_region["col3"]==5) $row_liste_region["col3"]="Savanes";
if(isset($tab_region_val_array[$row_liste_region["col3"]])) {
$liste_region_array.="'".$row_liste_region["col3"]."',";
$tab_region_array[$row_liste_region["col3"]]=$row_liste_region["col3"];
}
}}

//$titretext="Projets cr\u00e9\u00e9es/consolid\u00e9es";
$liste_region_array=substr($liste_region_array, 0, -1); 


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
	<body>



<!-- debut graph typologie -->

<div id="graph_indMP_sp_region_nbre" style="max-width: 500px;height: 300px;  margin: 0 auto"></div>
<script type="text/javascript">
Highcharts.chart('graph_indMP_sp_region_nbre', {
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
        text: 'Nombre de demande par type et par R\u00e9gions'
    },
	/*subtitle: {
    text: 'Supeficie (ha) par sp\u00e9n\u00e9culation et par r\u00e9n\u00e9gion'
},*/
    xAxis: {
        categories: [<?php echo $liste_region_array;  ?>],
		 title: {
            text: 'R\u00e9gions'
        }
    },
    yAxis: {
        min: 0,
		 title: {
            text: ''
        }
       
    },
    legend: {
        reversed: true
    },
	   tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.0f}%</b>'
            },
    plotOptions: {
        series: {
            stacking: 'normal',
			 borderWidth: 0,
            dataLabels: {
                enabled: true,
                //format: '{point.y:.0f}'
				 format: ' <b>{point.y:.0f}</b>'
				}
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
    series: [
	 <?php $iv=0; foreach($valeur_periode_array as $a=>$b){ $iv++;    ?>
	{
        name: '<?php echo $a;   ?>',
        data: [
		 <?php foreach($tab_region_array as $ar=>$br){ if(isset($valeur_periode_array[$a][$ar])) echo $valeur_periode_array[$a][$ar].","; else echo "0,";}   ?>
		
		
		]
    } <?php if($np!=$iv) echo ","   ?>
	
	 <?php }   ?>
	]
});	</script>
	
<?php // } ?>


	</body>



</html>