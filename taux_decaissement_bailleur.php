<?php

///////////////////////////////////////////////

/*                 SSE                       */

/*	Conception & Développement: BAMASOFT */

///////////////////////////////////////////////

session_start();

include_once 'system/configuration.php';

$config = new Config;



if (!isset ($_SESSION["clp_id"])) {

  //header(sprintf("Location: %s", "./"));

  exit;

}

include_once $config->sys_folder . "/database/db_connexion.php";

//header('Content-Type: text/html; charset=UTF-8');



if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y");

if(isset($_GET['code_act'])) { $code_act = $_GET['code_act']; }


function frenchMonthName($monthnum) {

      $armois=array("", "Jan", "Fév", "Mars", "Avril", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc");

      if ($monthnum>0 && $monthnum<13) {

          return $armois[$monthnum];

      } else {

          return $monthnum;

      }

  }



$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {

  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);

}

$page = $_SERVER['PHP_SELF'];


$annee_array=$taux_annuel=$bailleurs=array();

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_l_annee = "SELECT distinct annee FROM code_convention  order by annee asc";
$l_annee = mysql_query($query_l_annee, $pdar_connexion) or die(mysql_error());
$row_l_annee = mysql_fetch_assoc($l_annee);
$totalRows_l_annee = mysql_num_rows($l_annee);
if($totalRows_l_annee>0){   do{ $annee_array[$row_l_annee["annee"]]=$row_l_annee["annee"]; }while($row_l_annee = mysql_fetch_assoc($l_annee));}


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_bailleur = "SELECT sigle, id_partenaire,code_type, intitule from partenaire,type_part WHERE bailleur=partenaire.code and type_part.projet='".$_SESSION["clp_projet"]."' ORDER BY sigle asc";
$liste_bailleur = mysql_query($query_liste_bailleur, $pdar_connexion) or die(mysql_error());
$row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
$totalRows_liste_bailleur = mysql_num_rows($liste_bailleur);
$bailleur_array = array();
if($totalRows_liste_bailleur>0){
do{ $bailleur_array[$row_liste_bailleur["code_type"]]=$row_liste_bailleur["intitule"];  }
while($row_liste_bailleur  = mysql_fetch_assoc($liste_bailleur));}

 //for($an1=$_SESSION["annee_debut_projet"];$an1<=date("Y");$an1++){ if($an1<=$_SESSION["annee_fin_projet"]) { 

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_taux_annee = "SELECT SUM( if(cout_realise>0, cout_realise,0) ) AS taux, annee FROM code_convention where projet='".$_SESSION["clp_projet"]."' group by annee";
$taux_annee  = mysql_query($query_taux_annee , $pdar_connexion) or die(mysql_error());
$row_taux_annee  = mysql_fetch_assoc($taux_annee);
$totalRows_taux_annee  = mysql_num_rows($taux_annee);
if($totalRows_taux_annee>0) {do {
    $taux_annuel[$row_taux_annee['annee']]=$row_taux_annee['taux'];
} while ($row_taux_annee = mysql_fetch_assoc($taux_annee)); }

//$taux_annuel=$row_taux_annee['taux'];

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SELECT SUM( if(cout_realise>0, cout_realise,0) ) AS ct, code as cp , annee FROM code_convention where projet='".$_SESSION["clp_projet"]."' group by cp, annee";
//echo $query_liste_cp;
//exit;
$liste_cp  = mysql_query($query_liste_cp , $pdar_connexion) or die(mysql_error());
$row_liste_cp  = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp  = mysql_num_rows($liste_cp);
//$data = "";
if($totalRows_liste_cp>0) {do {
	//$const=$taux_annuel;
	//$cout=0;
    $cout_an = (isset($taux_annuel[$row_liste_cp['annee']]))?$taux_annuel[$row_liste_cp['annee']]:0;
    if($cout_an>0) $bailleurs[$row_liste_cp['cp']][$row_liste_cp['annee']]=$row_liste_cp['ct']; else $bailleurs[$row_liste_cp['cp']][$row_liste_cp['annee']]=0;
} while ($row_liste_cp = mysql_fetch_assoc($liste_cp)); }

//} }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
//$query_liste_bailleurs = "SELECT sigle, id_partenaire,code_type, intitule from partenaire,type_part WHERE bailleur=id_partenaire and projet=$projet ORDER BY sigle asc";
$query_liste_bailleurs = "SELECT distinct sigle, id_partenaire,code_type, intitule from partenaire,type_part WHERE bailleur=partenaire.code and type_part.projet='".$_SESSION["clp_projet"]."' ORDER BY sigle asc";
//echo $query_liste_bailleurs;
//exit;
$liste_bailleurs  = mysql_query($query_liste_bailleurs , $pdar_connexion) or die(mysql_error());
$row_liste_bailleurs  = mysql_fetch_assoc($liste_bailleurs);
$totalRows_liste_bailleurs  = mysql_num_rows($liste_bailleurs);
if($totalRows_liste_bailleurs>0){ $i=1; do{
foreach($annee_array as $an){
   $data_array[$i]=((isset($bailleur_array[$row_liste_bailleurs["code_type"]]))?$bailleur_array[$row_liste_bailleurs["code_type"]]:$row_liste_bailleurs["code_type"])."/ ".$an."\t ".((isset($bailleurs[$row_liste_bailleurs["code_type"]][$an]))?($bailleurs[$row_liste_bailleurs["code_type"]][$an]):"0")."%";

echo  $data_array[$i];
$i++; 
 }

  }while($row_liste_bailleurs  = mysql_fetch_assoc($liste_bailleurs)); }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

<!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->

<link rel="stylesheet" type="text/css" href="<?php print $config->theme_folder;?>/plugins/jquery-ui.css"/>

<link href="<?php print $config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>

<link href="<?php print $config->theme_folder;?>/responsive.css" rel="stylesheet" type="text/css"/>

<link href="<?php print $config->theme_folder;?>/icons.css" rel="stylesheet" type="text/css"/>

<link href='<?php print $config->theme_folder;?>/css.css' rel='stylesheet' type='text/css'>

<link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">

<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>

<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>

<script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>

<script type="text/javascript" src="plugins/noty/layouts/top.js"></script>

<script type="text/javascript" src="plugins/noty/themes/default.js"></script>

<script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>

<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>

<script type="text/javascript" src="plugins/pickadate/picker.js"></script>

<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>

<?php if(!isset($_GET['add'])) { ?>

<script type="text/javascript" src="plugins/select2/select2.min.js"></script>

<script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>

<script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>

<?php } ?>

<script>

	$().ready(function() {

	  $(".modal-dialog", window.parent.document).width(800);

		// validate the comment form when it is submitted

		$("#form1").validate();

<?php if(!isset($_GET['add'])) { ?>

$(".dataTable").dataTable({"iDisplayLength": -1});



<?php } ?>


	});

</script>

<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {

  border-spacing: 0px !important; border-collapse: collapse; font-size: small;

} .table tbody tr td {vertical-align: middle; }

#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}

.dataTables_length, .dataTables_info { float: left;} .dataTables_paginate, .dataTables_filter { float: right;}

.dataTables_length, .dataTables_paginate { display: none;}

@media(min-width:558px){.col-md-12 {width: 100%;}.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-4 {width: 30%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}

</style>

<script src="assets/js/highcharts.js"></script>
<script src="assets/js/modules/data.js"></script>
<script src="assets/js/modules/drilldown.js"></script>
<script type="text/javascript" src="assets/js/modules/exporting.js"></script>

<script type="text/javascript">

$(function () {

    Highcharts.data({
        csv: document.getElementById('tsv').innerHTML,
        itemDelimiter: '\t',
        parsed: function (columns) {

            var brands = {},
                brandsData = [],
                versions = {},
                drilldownSeries = [];

            // Parse percentage strings
            columns[1] = $.map(columns[1], function (value) {
                if (value.indexOf('%') === value.length - 1) {
                    value = parseFloat(value);
                }
                return value;
            });

            $.each(columns[0], function (i, name) {
                var brand,
                    version;

                
                if (i > 0) {

                    // Remove special edition notes

                    // Split into brand and version
                    version = name.split('/')[1];
                    name = name.split('/')[0];
                    /*if (version) {
                        version = version[0];
                    }*/
                    brand = name.replace(version, '');

                    // Create the main data
                    if (!brands[brand]) {
                        brands[brand] = columns[1][i];
                    } else {
                        brands[brand] += columns[1][i];
                    }

                    // Create the version data
                    if (version !== null) {
                        if (!versions[brand]) {
                            versions[brand] = [];
                        }
                        versions[brand].push([version, columns[1][i]]);
                    }
                }

            });

            $.each(brands, function (name, y) {
                brandsData.push({
                    name: name,
                    y: y,
                    drilldown: versions[name] ? name : null
                });
            });
            $.each(versions, function (key, value) {
                drilldownSeries.push({
                    name: key,
                    id: key,
                    data: value
                });
            });

            // Create the chart
            $('#container1').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Exécutions par Régions / Communes du PTBA <?php echo $annee; ?>'
                },
                subtitle: {
                    text: 'Cliquez sur une région pour voir les détails!'
                },
                xAxis: {
                    type: 'category'
                },
                yAxis: {
                    title: {
                        text: 'Montant réalisé'
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
                            format: '<b><span style="color:#000000">{point.y:,.0f} FCFA</span></b>'
                        }
                    }
                },

              credits: {
                enabled: true,
                href: 'http:#',
                text: 'Ruche PNF: <?php echo date("d/m/Y H:i"); ?>',
                style: {
                cursor: 'pointer',
                color: '#6633FF',
                fontSize: '10px',
                margin: '10px'

                }
                 },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:,.0f} FCFA</b> <br/>'
                },

                series: [{
                    name: 'Montant réalisé par région',
                    colorByPoint: true,
                    data: brandsData
                }],
                drilldown: {
                    series: drilldownSeries
                }
            })

        }
    });
});


		</script>

</head>

<body>

<div>

<div class="widget box ">
  <div class="widget-content">
  
 <?php if(isset($data_array) && count($data_array)>0){   ?>

<br />

<div id="container" style="width: 100%; height: 400px; margin: 0 auto"></div></div>

<pre id="tsv" style="display:none">Bailleurs/annees	Valeur
<?php foreach($data_array as $ccom){ echo $ccom."\n"; }  ?>
      ?>
</pre>

  <?php }else echo "<br /><br /><h1 align='center'>Aucun co&ucirc;t import&eacute; en $annee</h1>";
     ?>
  </div>
</div>

</div>


</body>

</html>