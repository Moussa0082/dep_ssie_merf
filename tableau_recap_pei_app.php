<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & D�veloppement: BAMASOFT */
///////////////////////////////////////////////
//session_start();
include_once 'system/configuration.php';
$config = new Config;

/*if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
} */
include_once $config->sys_folder . "/database/db_connexion.php";
$annee = (isset($_GET["annee"]))?intval($_GET["annee"]):date("Y");


$query_liste_ugl = "SELECT * FROM departement   order by code_departement asc";
try{
    $liste_ugl = $pdar_connexion->prepare($query_liste_ugl);
    $liste_ugl->execute();
    $row_liste_ugl = $liste_ugl ->fetchAll();
    $totalRows_liste_ugl = $liste_ugl->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableauRegion = array(); $nbregi=0;
foreach($row_liste_ugl as $row_liste_ugl1){
  $tableauRegion[$row_liste_ugl1['code_departement']] = $row_liste_ugl1['nom_departement']; $nbregi=$nbregi+1;
}

$query_liste_indicateur_ref = "SELECT count(distinct col18) as nb_bene, col1, col11 FROM t_1646217521 group by col1, col11";
try{
    $liste_indicateur_ref = $pdar_connexion->prepare($query_liste_indicateur_ref);
    $liste_indicateur_ref->execute();
    $row_liste_indicateur_ref = $liste_indicateur_ref ->fetchAll();
    $totalRows_liste_indicateur_ref = $liste_indicateur_ref->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$TableauJeunes =$TableauAdultes = $TableauPref = array(); 
if($totalRows_liste_indicateur_ref>0){ foreach($row_liste_indicateur_ref as $row_liste_indicateur_ref1){
$TableauJeunes[$row_liste_indicateur_ref1["col1"]][$row_liste_indicateur_ref1["col11"]] = $row_liste_indicateur_ref1["nb_bene"];
$TableauAdultes[$row_liste_indicateur_ref1["col11"]] = $row_liste_indicateur_ref1["col11"];
$TableauPref[$row_liste_indicateur_ref1["col1"]] = $row_liste_indicateur_ref1["col1"];
/*$TableauPlant[$row_liste_indicateur_ref1["reg"]] = $row_liste_indicateur_ref1["Jeunes"]+$row_liste_indicateur_ref1["Adultes"];*/
 } }

//echo $code_len[0];
 //print_r($TableauJeunes); exit;

if($totalRows_liste_indicateur_ref>0) {
?>
<!DOCTYPE HTML>



<html>



	<head>



       
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<title>Ouvrages<?php ///echo "PTBA $annee par ".((isset($libelle[0])?$libelle[0]:"composante"));      ?></title>

  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/login.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
  <link href="<?php print $config->theme_folder; ?>/plugins/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins/wysiwyg-color.css" rel="stylesheet" type="text/css"/>
  <link href='<?php print $config->theme_folder; ?>/css.css' rel='stylesheet' type='text/css'>

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
		<style>
#datatable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; } .DTTT, .TableTools { display: none!important; }
        .Style1 {
	font-size: 16px;
	font-weight: bold;
}
        </style>
</head>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
	<body>
    <div align="center" class="Style1">Nombre de PEI par préfecture </div>
    <table class="table table-striped table-bordered table-hover table-responsive dataTable v_align" id="datatable">
  <thead>
    <tr>
      <th >Préfectures</th>
	   <?php  foreach($TableauAdultes as $a1=>$b1){ /*if(isset($TableauPlant[$a])) {*/  ?>
      <th  class="center"><strong>  <?php echo $a1;  ?> </strong></th>
	   <?php }  ?>
      <th  class="center"><strong>Total</strong></th>
    </tr>
  </thead>
  <tbody>
    <?php if($totalRows_liste_ugl>0){$it=0; foreach($TableauPref as $a=>$b){ if(isset($TableauJeunes[$b]) && array_sum($TableauJeunes[$b])>0) {  ?>
    <tr>
      <td><?php echo $b;   ?></td>
       <?php  foreach($TableauAdultes as $a1=>$b1){ /*if(isset($TableauPlant[$a])) {*/  ?>
      <td  class="center"><strong>  <?php if(isset($TableauJeunes[$b][$b1])) echo $TableauJeunes[$b][$b1];  //else echo 0;   ?> </strong></td>
	   <?php }  ?>
      <td  class="center"><?php if(isset($TableauJeunes[$b])) echo array_sum($TableauJeunes[$b]) ;     ?></td>
    </tr>
    <?php  } } ?>
  </tbody>
  <?php } ?>
</table>
<!-- debut graph typologie -->
<div id="plants" style="height: 350px;  margin: 0 auto"></div>
<script type="text/javascript">

Highcharts.chart('plants', {
   
    data: {
        table: 'datatable'
    },
	
   chart: {
        type: 'column'
    },
	  title: {
        text: 'Répartition des  <?php if(isset($TableauJeunes)) echo number_format(array_sum($TableauJeunes), 0, '.', ' ');     ?> PEI par Préfecture'
    },
  legend: {
     enabled: true
    },

   /* subtitle: {
        text: 'Source: WorldClimate.com'
    },*/
    yAxis: {
	 min: 0,
     //max: 4000,
        allowDecimals: false,
        title: {
            text: 'Nombre'
        }
    },
	      plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '<b><span style="color:#000000">{point.y:.0f}</span></b>'
                        }
                    },
                    column: {
                    pointPadding: 0.2,
                    borderWidth: 0
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
    tooltip: {
        formatter: function () {
            return '<b>' + this.series.name + '</b><br/>' +
                this.point.y + ' ' + this.point.name.toLowerCase();
        }
    }
});
</script>
<?php // } ?>
	</body>
</html>
<?php } else echo "Aucun cout prevu";?>