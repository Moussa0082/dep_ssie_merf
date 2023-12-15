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
  /*$datep=$periodes= ""; $valind=0; $liste_source_array=$query_liste_val_ref="";
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_composante = sprintf("SELECT * FROM periode_indicateur where ref_indicateur=".GetSQLValueString($code_indicateur, "text"))." order by date_validation asc";
//echo $query_liste_composante ;
//exit;
$liste_composante  = mysql_query($query_liste_composante, $pdar_connexion) or die(mysql_error());
$row_liste_composante  = mysql_fetch_assoc($liste_composante);
$totalRows_liste_composante  = mysql_num_rows($liste_composante);
$periode_array = ""; $val_periode_array = $tab_source_array = array();
if($totalRows_liste_composante>0){ do{
if(empty($row_liste_composante["valeur_periode"])) $row_liste_composante["valeur_periode"]=0;
$tab_source_array[$row_liste_composante['source_donnees']] = $row_liste_composante['source_donnees'];
//if(in_array($row_liste_composante['source_donnees'],$tab_source_array)) { } else $liste_source_array.=$row_liste_composante['source_donnees'].", ";
// $periode_array[$row_liste_composante["id_marche"]] = $row_liste_composante["nom_marche"];
//if(isset($row_liste_composante["date_validation"]) && $row_liste_composante["date_validation"]!=$row_liste_collecte["periode"]) $datep=$row_liste_composante["date_validation"];
  $periode_array.=$row_liste_composante["valeur_periode"].",";
  $val_periode_array[$row_liste_composante["id_periode"]]=$row_liste_composante["valeur_periode"];
   $periodes.="'".$row_liste_composante["periode_collecte"]."',";
}while($row_liste_composante = mysql_fetch_assoc($liste_composante));
    $rows = mysql_num_rows($liste_composante);
    if($rows > 0) {
        mysql_data_seek($liste_composante, 0);
  	  $row_liste_composante = mysql_fetch_assoc($liste_composante);
    }
}
//print_r($tab_source_array);
//exit;
$periode_array=substr($periode_array, 0, -1); */

//typologie de l indicateur
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_periode = "SELECT region, year(date_mp_sub) as annee_sub, count(`id_projet`) as nb_projet FROM cercle, commune, village, microprojet where id_cercle=cercle and id_commune=commune and id_village=village and nature_mp='AGR' group BY annee_sub, region desc ORDER BY `cercle`.`region` asc, annee_sub desc";	
$liste_periode  = mysql_query($query_liste_periode , $pdar_connexion) or die(mysql_error());
$row_liste_periode = mysql_fetch_assoc($liste_periode);
$totalRows_liste_periode  = mysql_num_rows($liste_periode);
$valeur_periode_array =$tab_region_val_array = array(); $np=0;
if($totalRows_liste_periode>0){ do{
$np++;
$valeur_periode_array[$row_liste_periode["annee_sub"]][$row_liste_periode["region"]]=$row_liste_periode["nb_projet"];
$tab_region_val_array[$row_liste_periode["region"]]=$row_liste_periode["region"];

}while($row_liste_periode = mysql_fetch_assoc($liste_periode));}

//print_r($valeur_periode_array);
//exit;
/*
//typologie de l indicateur
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_classe = sprintf("SELECT * FROM typologie_indicateur where ref_indicateur=".GetSQLValueString($code_indicateur, "text"))."";
$liste_classe  = mysql_query($query_liste_classe , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_classe = mysql_fetch_assoc($liste_classe);
$totalRows_liste_classe  = mysql_num_rows($liste_classe);
$borne_classe_array = array();
if($totalRows_liste_classe>0){ do{
//if(!isset($nb_classe_array[$row_liste_classe["id_typologie"]])) $nb_classe_array[$row_liste_classe["id_typologie"]]=0;

$borne_classe_array[$row_liste_classe["id_typologie"]]=$row_liste_classe["nom_classe"];

}while($row_liste_classe = mysql_fetch_assoc($liste_classe));}


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_classe = sprintf("SELECT sum(resultat_nationale.valeur_periode) as valeur_periode, resultat_nationale.classe, periode_indicateur.source_donnees, id_periode FROM resultat_nationale, periode_indicateur where id_periode=periode and ref_indicateur=".GetSQLValueString($code_indicateur, "text"))." group by resultat_nationale.classe, periode_indicateur.source_donnees, id_periode";
//echo $query_liste_composante ;
//exit;
$liste_classe  = mysql_query($query_liste_classe , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_classe = mysql_fetch_assoc($liste_classe);
$totalRows_liste_classe  = mysql_num_rows($liste_classe);
$nb_classe_array=$val_classe_array =$tab_source_array=array();
if($totalRows_liste_classe>0){ do{
$tab_source_array[$row_liste_classe['source_donnees']] = $row_liste_classe['source_donnees'];
//if(!isset($nb_classe_array[$row_liste_classe["id_typologie"]])) $nb_classe_array[$row_liste_classe["id_typologie"]]=0;
$nb_classe_array[$row_liste_classe["classe"]]=$row_liste_classe["valeur_periode"];
$val_classe_array[$row_liste_classe["id_periode"]][$row_liste_classe["classe"]]=$row_liste_classe["valeur_periode"];
}while($row_liste_classe = mysql_fetch_assoc($liste_classe));}*/

//print_r($nb_classe_array);
//exit;

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
 $query_liste_region = "SELECT distinct id_region, nom_region FROM region   order by id_region asc ";
  $liste_region  = mysql_query($query_liste_region , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_region = mysql_fetch_assoc($liste_region);
  $totalRows_liste_region  = mysql_num_rows($liste_region);
  $tab_region_array=array(); $liste_region_array="";
if($totalRows_liste_region>0){ do{
if(isset($tab_region_val_array[$row_liste_region["id_region"]])) {
$liste_region_array.="'".$row_liste_region["nom_region"]."',";
$tab_region_array[$row_liste_region["id_region"]]=$row_liste_region["nom_region"];
}}while($row_liste_region = mysql_fetch_assoc($liste_region));}	

$titretext="AGR cr\u00e9\u00e9es/consolid\u00e9es";
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

<div id="graph_indAGR_annee_region_nbre" style="max-width: 500px;height: 300px;  margin: 0 auto"></div>
<script type="text/javascript">
Highcharts.chart('graph_indAGR_annee_region_nbre', {
    chart: {
		 backgroundColor: {
                linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
    stops: [
        [0, '#FFFFFF'],
        [1, '#C1FFC1']
    ]
            },
            type: 'bar'
        },
    
    title: {
        text: '<?php echo $titretext;  ?>'
    },
	subtitle: {
    text: 'Nombre des projets financ\u00e9s par r\u00e9gion et par ann\u00e9e'
},
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
                text: 'RUCHE FIER : <?php echo date("d/m/Y H:i"); ?>',
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