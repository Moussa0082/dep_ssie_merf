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
$annee_array=$taux_annuel=$bailleurs=array();

$query_l_annee = "SELECT distinct annee FROM code_convention  order by annee asc";
  try{
    $l_annee = $pdar_connexion->prepare($query_l_annee);
    $l_annee->execute();
    $row_l_annee = $l_annee ->fetchAll();
    $totalRows_l_annee = $l_annee->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_l_annee>0){   foreach($row_l_annee as $row_l_annee){   $annee_array[$row_l_annee["annee"]]=$row_l_annee["annee"]; }}


$query_liste_bailleur = "SELECT sigle, id_partenaire,code_type, intitule from partenaire,type_part WHERE bailleur=partenaire.code and type_part.projet='".$_SESSION["clp_projet"]."' ORDER BY sigle asc";
  try{
    $liste_bailleur = $pdar_connexion->prepare($query_liste_bailleur);
    $liste_bailleur->execute();
    $row_liste_bailleur = $liste_bailleur ->fetchAll();
    $totalRows_liste_bailleur = $liste_bailleur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$bailleur_array = array();
if($totalRows_liste_bailleur>0){
 foreach($row_liste_bailleur as $row_liste_bailleur){ $bailleur_array[$row_liste_bailleur["code_type"]]=$row_liste_bailleur["intitule"];  }}

 //for($an1=$_SESSION["annee_debut_projet"];$an1<=date("Y");$an1++){ if($an1<=$_SESSION["annee_fin_projet"]) { 

$query_taux_annee = "SELECT SUM( if(cout_realise>0, cout_realise,0) ) AS taux, annee FROM code_convention where projet='".$_SESSION["clp_projet"]."' group by annee";
  try{
    $taux_annee = $pdar_connexion->prepare($query_taux_annee);
    $taux_annee->execute();
    $row_taux_annee = $taux_annee ->fetchAll();
    $totalRows_taux_annee = $taux_annee->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_taux_annee>0) { foreach($row_taux_annee as $row_taux_annee){
    $taux_annuel[$row_taux_annee['annee']]=$row_taux_annee['taux'];
}  }

//$taux_annuel=$row_taux_annee['taux'];

$query_liste_cp = "SELECT SUM( if(cout_realise>0, cout_realise,0) ) AS ct, code as cp , annee FROM code_convention where projet='".$_SESSION["clp_projet"]."' group by cp, annee";
  try{
    $liste_cp = $pdar_connexion->prepare($query_liste_cp);
    $liste_cp->execute();
    $row_liste_cp = $liste_cp ->fetchAll();
    $totalRows_liste_cp = $liste_cp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
//$data = "";
if($totalRows_liste_cp>0) {foreach($row_liste_cp as $row_liste_cp){
	//$const=$taux_annuel;
	//$cout=0;
    $cout_an = (isset($taux_annuel[$row_liste_cp['annee']]))?$taux_annuel[$row_liste_cp['annee']]:0;
    if($cout_an>0) $bailleurs[$row_liste_cp['cp']][$row_liste_cp['annee']]=$row_liste_cp['ct']; else $bailleurs[$row_liste_cp['cp']][$row_liste_cp['annee']]=0;
}  }

//} }

$query_liste_bailleurs = "SELECT distinct sigle, id_partenaire,code_type, intitule from partenaire,type_part WHERE bailleur=partenaire.code and type_part.projet='".$_SESSION["clp_projet"]."' ORDER BY sigle asc";
  try{
    $liste_bailleurs = $pdar_connexion->prepare($query_liste_bailleurs);
    $liste_bailleurs->execute();
    $row_liste_bailleurs = $liste_bailleurs ->fetchAll();
    $totalRows_liste_bailleurs = $liste_bailleurs->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if($totalRows_liste_bailleurs>0){ $i=1; foreach($row_liste_bailleurs as $row_liste_bailleurs){
foreach($annee_array as $an){
   $data_array[$i]=((isset($bailleur_array[$row_liste_bailleurs["code_type"]]))?$bailleur_array[$row_liste_bailleurs["code_type"]]:$row_liste_bailleurs["code_type"])."/ ".$an."\t ".((isset($bailleurs[$row_liste_bailleurs["code_type"]][$an]))?($bailleurs[$row_liste_bailleurs["code_type"]][$an]):"0")."%";
$i++; 
 }
  } }
//exit;
?>






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

   



