<?php

///////////////////////////////////////////////

/*                 SSE                       */

/*	Conception & Développement: SEYA SERVICES */

///////////////////////////////////////////////

session_start();

$path = '../';

include_once $path.'system/configuration.php';

$config = new Config;

       /*

if (!isset ($_SESSION["clp_id"])) {

  header(sprintf("Location: %s", "./"));

  exit;

} */

header('Content-Type: text/html; charset=ISO-8859-15');

$annee=date("Y");

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_region = "SELECT * FROM partenaire";
$liste_region = mysql_query($query_liste_region, $pdar_connexion) or die(mysql_error());
$row_liste_region = mysql_fetch_assoc($liste_region);
$totalRows_liste_region = mysql_num_rows($liste_region);
$region_array = array();
if($totalRows_liste_region>0){
do{ $region_array[($row_liste_region["code"])]=$row_liste_region["sigle"]; }
while($row_liste_region  = mysql_fetch_assoc($liste_region));}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_categorie= "SELECT convention_concerne, code, nom_categorie, bailleur 
FROM ".$database_connect_prefix."categorie_depense, ".$database_connect_prefix."type_part
WHERE code_type = convention_concerne AND ".$database_connect_prefix."type_part.projet = ".$database_connect_prefix."categorie_depense.projet
AND ".$database_connect_prefix."categorie_depense.projet='".$_SESSION["clp_projet"]."' AND convention_concerne IS NOT NULL ORDER BY  bailleur, `categorie_depense`.`code` ASC";
$liste_categorie = mysql_query($query_liste_categorie, $pdar_connexion) or die(mysql_error());
$row_liste_categorie = mysql_fetch_assoc($liste_categorie);
$totalRows_liste_categorie = mysql_num_rows($liste_categorie);
$bailleur_cat_array = $commune_array = array();
if($totalRows_liste_categorie>0){
do{ 
$bailleur_cat_array[$row_liste_categorie["convention_concerne"]."".$row_liste_categorie["code"]]=$row_liste_categorie["bailleur"]; 
$commune_array[$row_liste_categorie["convention_concerne"]."".$row_liste_categorie["code"]]=$row_liste_categorie["nom_categorie"]; }
while($row_liste_categorie  = mysql_fetch_assoc($liste_categorie));}

$taux_annuel=0;
$diff=0;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_commune = "SELECT left(code,2) as region, SUM( if(cout_realise>0, cout_realise,0) ) as realise, code  FROM ".$database_connect_prefix."code_categorie where ".$database_connect_prefix."code_categorie.projet='".$_SESSION["clp_projet"]."'  and code!='Code' and code!='fichiers' group by code, region";
$liste_commune = mysql_query($query_liste_commune, $pdar_connexion) or die(mysql_error());
$row_liste_commune = mysql_fetch_assoc($liste_commune);
$totalRows_liste_commune = mysql_num_rows($liste_commune);
$data_array = array();
$region=array();
if($totalRows_liste_commune>0){
  $i=0; $autres=array();
do{
$taux_annuel+=$row_liste_commune["realise"];

if(isset($bailleur_cat_array[$row_liste_commune["code"]]) && isset($region_array[$bailleur_cat_array[$row_liste_commune["code"]]])){
  $data_array[$i]=$region_array[$bailleur_cat_array[$row_liste_commune["code"]]]."/ ".((isset($commune_array[($row_liste_commune["code"])]))?$commune_array[($row_liste_commune["code"])]:"Comm. ".$row_liste_commune["code"])."\t ".(($row_liste_commune["realise"]))."%";
    if(isset($region_array[$bailleur_cat_array[$row_liste_commune["code"]]]) && isset($region[$bailleur_cat_array[$row_liste_commune["code"]]])) $region[$bailleur_cat_array[$row_liste_commune["code"]]]+=$row_liste_commune["realise"];
    elseif(isset($region_array[$bailleur_cat_array[$row_liste_commune["code"]]])) $region[$bailleur_cat_array[$row_liste_commune["code"]]]=$row_liste_commune["realise"]; }
else {
  $data_array[$i]=$row_liste_commune["region"].((isset($commune_array[($row_liste_commune["code"])]))?$commune_array[($row_liste_commune["code"])]:"Comm. ".$row_liste_commune["code"])."\t ".(($row_liste_commune["realise"]))."%"; }

if(!isset($commune_array[($row_liste_commune["code"])]) && isset($bailleur_cat_array[$row_liste_commune["code"]]) && isset($region_array[$bailleur_cat_array[$row_liste_commune["code"]]])){
  if(isset($autres[$region_array[$bailleur_cat_array[$row_liste_commune["code"]]]][0])) $autres[$region_array[$bailleur_cat_array[$row_liste_commune["code"]]]][0]+=$row_liste_commune["realise"];
  else $autres[$region_array[$bailleur_cat_array[$row_liste_commune["code"]]]][0]=$row_liste_commune["realise"];
  $autres[$region_array[$bailleur_cat_array[$row_liste_commune["code"]]]][1]=$region_array[$bailleur_cat_array[$row_liste_commune["code"]]];  }
$i++;  }
while($row_liste_commune  = mysql_fetch_assoc($liste_commune));}

?>


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


<div class="widget box ">
  <div class="widget-content">
  
 <?php  if($totalRows_liste_commune>0){
  ?>

<br />

<div id="container1" style="width: 100%; height: 400px; margin: 0 auto"></div></div>

<pre id="tsv" style="display:none">Région/Commune	Valeur
<?php foreach($data_array as $ccom){  echo $ccom."\n"; }
if($autres>0){ foreach($autres as $r=>$v) echo $r."/ Communes ND\t ".$v[0]."%\n";  }
?>
</pre>

  <?php }else echo "<br /><br /><h1 align='center'>Aucun co&ucirc;t import&eacute; en $annee</h1>";
     ?>
  </div>
</div>

</div>

