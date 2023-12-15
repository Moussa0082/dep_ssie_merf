<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
$path = './';
include_once $path.'system/configuration.php';
$config = new Config;
       /*
if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
} */
header('Content-Type: text/html; charset=UTF-8');
$annee=date("Y");
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
//echo  $data_array[$i];
$i++; 
 }
  }while($row_liste_bailleurs  = mysql_fetch_assoc($liste_bailleurs)); }
//exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print $config->sitename;?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone;?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="keywords" content="<?php print $config->MetaKeys;?>" />
  <meta name="description" content="<?php print $config->MetaDesc;?>" />
  <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
  <meta name="author" content="<?php print $config->MetaAuthor;?>" />
  <!--<meta charset="utf-8">-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->
  <link href="<?php print $config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder;?>/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder;?>/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder;?>/icons.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder;?>/login.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php print $config->theme_folder;?>/fontawesome/font-awesome.min.css">
  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->theme_folder;?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->
  <!--[if IE 8]><link href="<?php print $config->theme_folder;?>/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->
  <link href='<?php print $config->theme_folder;?>/css.css' rel='stylesheet' type='text/css'>
  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/lodash.compat.min.js"></script>
  <!--[if lt IE 9]><script src="<?php print $config->script_folder;?>/libs/html5shiv.js"></script><![endif]-->
  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>
  <script type="text/javascript" src="plugins/bootstrap-switch/bootstrap-switch.min.js"></script>
  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/breakpoints.js"></script>
  <script type="text/javascript" src="plugins/respond/respond.min.js"></script>
  <script type="text/javascript" src="plugins/cookie/jquery.cookie.min.js"></script>
  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.min.js"></script>
  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>
  <!--[if lt IE 9]><script type="text/javascript" src="plugins/flot/excanvas.min.js"></script><![endif]-->
  <!--<script type="text/javascript" src="plugins/sparkline/jquery.sparkline.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.tooltip.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.resize.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.time.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.growraf.min.js"></script>
  <script type="text/javascript" src="plugins/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
  <script type="text/javascript" src="plugins/daterangepicker/moment.min.js"></script>
  <script type="text/javascript" src="plugins/daterangepicker/daterangepicker.js"></script>-->
  <script type="text/javascript" src="plugins/blockui/jquery.blockUI.min.js"></script>
  <script type="text/javascript" src="plugins/pickadate/picker.js"></script>
  <script type="text/javascript" src="plugins/pickadate/picker.date.js"></script>
  <script type="text/javascript" src="plugins/pickadate/picker.time.js"></script>
  <script type="text/javascript" src="plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
  <script type="text/javascript" src="plugins/fullcalendar/fullcalendar.min.js"></script>
  <script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>
  <script type="text/javascript" src="plugins/noty/layouts/top.js"></script>
  <script type="text/javascript" src="plugins/noty/themes/default.js"></script>
  <script type="text/javascript" src="plugins/uniform/jquery.uniform.min.js"></script>
  <script type="text/javascript" src="plugins/select2/select2.min.js"></script>
  <script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>
  <script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder;?>/app.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder;?>/plugins.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder;?>/plugins.form-components.js"></script>
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>
</head>
<body>
 <header class="header navbar navbar-fixed-top" role="banner">
    <?php include_once ("includes/header.php");?>
 </header>
<div id="container">
    <div id="sidebar" class="sidebar-fixed">
        <div id="sidebar-content">
            <?php include_once ("includes/menu_top.php");?>
        </div>
        <div id="divider" class="resizeable"></div>
    </div>

    <div id="content">
        <div class="container">
            <div class="crumbs">
                <?php include_once ("includes/sous_menu.php");?>
            </div>
        <div class="page-header">
            <div class="p_top_5">
<div class="widget box ">
  <div class="widget-content">
<script src="<?php print $config->script_folder; ?>/highcharts.js"></script>
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
                    text: 'Décaissement par Categories / Bailleurs'
                },
                subtitle: {
                    text: 'Cliquez sur un bailleur pour voir les détails!'
                },
                xAxis: {
                    type: 'category'
                },
                yAxis: {
                    title: {
                        text: 'Montant décaissé'
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
                            format: '<b><span style="color:#000000">{point.y:,.0f} </span></b>'
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
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:,.0f}</b> <br/>'
                },
                series: [{
                    name: 'Montant décaissé par bailleur',
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
 <?php if(isset($data_array) && count($data_array)>0){   ?>
<br />
<script src="assets/js/highcharts.js"></script>
<script src="assets/js/modules/data.js"></script>
<script src="assets/js/modules/drilldown.js"></script>
<script src="assets/js/modules/exporting.js"></script>
<div id="container1" style="width: 100%; height: 400px; margin: 0 auto"></div></div>
<pre id="tsv" style="display:none">Bailleurs/annees	Valeur
<?php foreach($data_array as $ccom){ echo $ccom."\n"; }  ?>
 <?php } else echo "<br /><br /><h1 align='center'>Aucun co&ucirc;t import&eacute;</h1>";
      ?>
</pre>

</div>
<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>
    <?php include_once ("includes/footer.php");?>
</div>
</body>
</html>
