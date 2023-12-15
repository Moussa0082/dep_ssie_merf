<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & DÃƒÂ©veloppement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
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
/*$query_liste_version = "SELECT * FROM ".$database_connect_prefix."version_ptba";
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
 } }*/
//print_r($nb_classe_array);
//exit;
 $query_liste_region = "SELECT distinct code_activite.annee FROM mtz.code_activite WHERE code!='Code' and code!='fichiers' group by annee order by annee asc";
  try{
    $liste_region = $pdar_connexion->prepare($query_liste_region);
    $liste_region->execute();
    $row_liste_region = $liste_region ->fetchAll();
    $totalRows_liste_region = $liste_region->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $tab_region_array=array(); $liste_region_array="";
if($totalRows_liste_region>0){ foreach($row_liste_region as $row_liste_region){
//if(isset($version_array[$row_liste_region["annee"]])) $row_liste_region["annee"]=$version_array[$row_liste_region["annee"]];
$liste_region_array.="'".$row_liste_region["annee"]."',";
$tab_region_array[$row_liste_region["annee"]]=$row_liste_region["annee"];
$nb_op_array[$row_liste_region["annee"]]=$nb_union_array[$row_liste_region["annee"]]=0;
//}
}}	
//echo  $query_liste_region; echo $liste_region_array;exit;
$query_liste_periode = "SELECT sum(cout_realise) as cout, code_activite.annee FROM mtz.code_activite WHERE  code!='Code' and code!='fichiers' group by annee order by annee asc";
  try{
    $liste_periode = $pdar_connexion->prepare($query_liste_periode);
    $liste_periode->execute();
    $row_liste_periode = $liste_periode ->fetchAll();
    $totalRows_liste_periode = $liste_periode->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tab_region_val_array = array();  $np=0; 
if($totalRows_liste_periode>0){ foreach($row_liste_periode as $row_liste_periode){
$np++; 
//if(isset($version_array[$row_liste_periode["annee"]])) $row_liste_periode["annee"]=$version_array[$row_liste_periode["annee"]];
$nb_op_array[$row_liste_periode["annee"]]=$row_liste_periode["cout"]/1000000;
$nb_union_array[$row_liste_periode["annee"]]=$row_liste_periode["cout"]/1000000;
}}
/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_periode = "SELECT sum(cout_realise) as cout, ptba.annee FROM ".$database_connect_prefix."decaissement_activite, ptba WHERE decaissement_activite.id_activite=id_ptba  group by ptba.annee order by annee asc";	
$liste_periode  = mysql_query($query_liste_periode , $pdar_connexion) or die(mysql_error());
$row_liste_periode = mysql_fetch_assoc($liste_periode);
$totalRows_liste_periode  = mysql_num_rows($liste_periode);
$nb_union_array = array(); $npu=0;
if($totalRows_liste_periode>0){ do{
$npu++;
$nb_union_array[$row_liste_periode["annee"]]=$row_liste_periode["cout"];
//$tab_region_val_array[$row_liste_periode["id_federation"]]=$row_liste_periode["id_federation"];
}while($row_liste_periode = mysql_fetch_assoc($liste_periode));}*/
//$titretext="Unions/Groupements cr\u00e9\u00e9s";
$titretext="D\u00e9caissement par ann\u00e9e (M F CFA) du ProDAF MTZ";
$liste_region_array=substr($liste_region_array, 0, -1); 
//foreach($al as $bl){ if(isset($speculation_array[$bl])) {echo $speculation_array[$bl].";<br />"; $j=1;}  } }} 
//print_r($nb_op_array);
//print_r($nb_union_array);
//exit;
if(!isset($include_data)){
?>

<!DOCTYPE HTML>
<html>
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Graph OP<?php ///echo "PTBA $annee par ".((isset($libelle[0])?$libelle[0]:"composante"));      ?></title>
        <style type="text/css">
<!--
.table {  border-spacing: 0px !important; border-collapse: collapse; width: 100%!important;
}
.table {border-spacing: 0px !important; border-collapse: collapse; font-size: small; }
-->
.highcharts-figure, .highcharts-data-table table {
    min-width: 310px; 
    max-width: 800px;
    margin: 1em auto;
}
#container {
    height: 400px;
}
.highcharts-data-table table {
	font-family: Verdana, sans-serif;
	border-collapse: collapse;
	border: 1px solid #EBEBEB;
	margin: 10px auto;
	text-align: center;
	width: 100%;
	max-width: 500px;
}
.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}
.highcharts-data-table th {
	font-weight: 600;
    padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
    padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}
.highcharts-data-table tr:hover {
    background: #f1f7ff;
}
        </style>
</head>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
	<body>
<?php } ?>
<!-- debut graph typologie -->
<div id="graph_mtz_fed" style="<?php if(isset($include_data)){ ?>min-width: 310px; height: 700px; margin: -5px auto;width:100%;<?php } else{ ?>height: 300px;  margin: 0 auto<?php } ?>"></div>
<script type="text/javascript">
Highcharts.chart('graph_mtz_fed', {
    chart: {
				
        zoomType: 'xy'
    },
	colors: [
                '#910000',
                '#8bbc21',
               
                '#DB843D',
                '#92A8CD',
                '#A47D7C',
                '#B5CA92'
                ],
    title: {
        text: '<?php echo $titretext;  ?>',
		  align: 'center'
          <?php if(isset($include_data)){ ?>,
            useHTML: true,
            style: {
                fontSize: '20px'
            }
          <?php } ?>
    },
   /* subtitle: {
        text: 'Source: WorldClimate.com'
    },*/
    xAxis: [{
        categories: [<?php echo $liste_region_array;  ?>],
        crosshair: true
        <?php if(isset($include_data)){ ?>,
        labels: {
            style: {
                    fontSize: '20px'
            }
        }
        <?php } ?>
    }],
    yAxis: [{ // Primary yAxis
        labels: {
           // format: '{value}°C',
            style: {
               // color: Highcharts.getOptions().colors[1]
               <?php if(isset($include_data)){ ?>
                    fontSize: '20px'
                <?php } ?>
            }
        },
        title: {
            text: 'D\u00e9caissement',
            style: {
               // color: Highcharts.getOptions().colors[1]
               <?php if(isset($include_data)){ ?>
                    fontSize: '20px'
                <?php } ?>
            }
        }
    }, { // Secondary yAxis
        title: {
            text: 'D\u00e9caissement',
            style: {
                color: Highcharts.getOptions().colors[0]
                <?php if(isset($include_data)){ ?>,
                    fontSize: '20px'
                <?php } ?>
            }
        },
        labels: {
           // format: '{value} mm',
            style: {
                color: Highcharts.getOptions().colors[0]
                <?php if(isset($include_data)){ ?>,
                    fontSize: '20px'
                <?php } ?>
            }
        },
        opposite: true
    }],
    tooltip: {
        shared: true
    },
    legend: {
     enabled: false
    },
	 tooltip: {
        	    pointFormat: '{series.name}: <b>{point.y:.0f} M FCFA</b>'
            },
	
		 credits: {
                enabled: true,
                href: 'http:#',
                text: 'PRODAF MTZ : <?php echo date("d/m/Y H:i"); ?>',
                style: {
                cursor: 'pointer',
                color: '#6633FF',
                fontSize: '10px',
                margin: '10px'
                }
             },
    series: [{
        name: 'Courbe',
        type: 'line',
        yAxis: 1,
        data: [<?php foreach($nb_union_array as $a=>$b){  echo $b.",";}   ?>],
        tooltip: {
            valueSuffix: ''
        }
    }, {
        name: 'Histogramme',
        type: 'column',
        data: [<?php foreach($nb_op_array as $a=>$b){  echo $b.",";}   ?>],
        tooltip: {
            valueSuffix: ''
        }
    }]
});
</script>
	
<?php // } ?>
<?php if(!isset($include_data)){  ?>
	</body>
</html>
<?php  } ?>