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

header('Content-Type: text/html; charset=ISO-8859-15');

$tableauCp = array();
$tableauCoutCp = array();
if(isset($_GET['annee'])) $annee=$_GET['annee']; else $annee=date("Y");
if(isset($_GET['scp'])) $scp=$_GET['scp'];  else $scp=0;

$annee_courant=date("Y")+1;
$annee_array=array();
$an="";

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_phase = "SELECT min(annee_debut) as anneedebut, max(annee_fin) as anneefin FROM phase WHERE projet='$projet'";
$liste_phase = mysql_query($query_liste_phase, $pdar_connexion) or die(mysql_error());
$row_liste_phase = mysql_fetch_assoc($liste_phase);
$totalRows_liste_phase = mysql_num_rows($liste_phase);
$an1p=$row_liste_phase['anneedebut'];
$an2p=$row_liste_phase['anneefin'];

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_l_annee = "SELECT * FROM annee  where annee>='$an1p' and annee<='$an2p' and annee<='$annee_courant' order by annee asc";
$l_annee = mysql_query($query_l_annee, $pdar_connexion) or die(mysql_error());
$row_l_annee = mysql_fetch_assoc($l_annee);
$totalRows_l_annee = mysql_num_rows($l_annee);
if($totalRows_l_annee>0){   do{ $annee_array[$row_l_annee["annee"]]=$row_l_annee["annee"]; $an.="'".$row_l_annee["annee"]."',"; }while($row_l_annee = mysql_fetch_assoc($l_annee)); $an=substr($an,0,strlen($an)-1);  }

$bailleurs=array();

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_bailleur = "SELECT sigle, id_partenaire,code_type, intitule from partenaire,type_part WHERE bailleur=id_partenaire and projet=$projet ORDER BY sigle asc";
$liste_bailleur = mysql_query($query_liste_bailleur, $pdar_connexion) or die(mysql_error());
$row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
$totalRows_liste_bailleur = mysql_num_rows($liste_bailleur);
$bailleur_array = array();
if($totalRows_liste_bailleur>0){
do{ $bailleur_array[$row_liste_bailleur["code_type"]]=($row_liste_bailleur["sigle"]!=substr($row_liste_bailleur["intitule"],0,4))?$row_liste_bailleur["sigle"]." ".substr($row_liste_bailleur["intitule"],0,4):$row_liste_bailleur["sigle"];  }
while($row_liste_bailleur  = mysql_fetch_assoc($liste_bailleur));}

foreach($annee_array as $an1){

mysql_select_db($database_pdar_connexion, $pdar_connexion);
if($scp==0) $query_taux_annee = "SELECT SUM( if(cout_realise>0, cout_realise,0) ) AS taux FROM
code_analytique where code_analytique.annee=$an1 and
code_analytique.projet=$projet and code_analytique.code_activite_ptba
in(select code_activite_ptba from ptba, sous_composante, composante
where id_composante=sous_composante.composante and
id_sous_composante=ptba.isous_composante and projet=$projet)";

else $query_taux_annee = "SELECT SUM( if(cout_realise>0, cout_realise,0) ) AS taux FROM
code_analytique where code_analytique.annee=$an1 and
code_analytique.projet=$projet and code_analytique.code_activite_ptba
in(select code_activite_ptba from ptba, sous_composante, composante
where id_composante=sous_composante.composante and
id_sous_composante=ptba.isous_composante and isous_composante=$scp and projet=$projet)";

$taux_annee  = mysql_query($query_taux_annee , $pdar_connexion) or die(mysql_error());
$row_taux_annee  = mysql_fetch_assoc($taux_annee);
$totalRows_taux_annee  = mysql_num_rows($taux_annee);

$taux_annuel=$row_taux_annee['taux'];

mysql_select_db($database_pdar_connexion, $pdar_connexion);
if($scp==0) $query_liste_cp = "SELECT SUM( if(cout_realise>0, cout_realise,0) ) AS ct, left(code_analytique.code_budget,2) as cp  FROM code_analytique
                    where  code_analytique.annee=$an1 and code_analytique.projet=$projet and code_analytique.code_activite_ptba
in(select code_activite_ptba from ptba, sous_composante, composante
where id_composante=sous_composante.composante and
id_sous_composante=ptba.isous_composante and projet=$projet and ptba.annee=$an1) group by cp";

else $query_liste_cp = "SELECT SUM( if(cout_realise>0, cout_realise,0) ) AS ct, left(code_analytique.code_budget,2) as cp  FROM code_analytique
                    where  code_analytique.annee=$an1 and code_analytique.projet=$projet and code_analytique.code_activite_ptba
in(select code_activite_ptba from ptba, sous_composante, composante
where id_composante=sous_composante.composante and
id_sous_composante=ptba.isous_composante and isous_composante=$scp and projet=$projet and ptba.annee=$an1) group by cp";
$liste_cp  = mysql_query($query_liste_cp , $pdar_connexion) or die(mysql_error());
$row_liste_cp  = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp  = mysql_num_rows($liste_cp);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
if($scp!=0) $query_acteur_p = "SELECT  intitule_sous_composante  FROM  sous_composante where id_sous_composante='$scp'";
else $query_acteur_p = "SELECT  '' as intitule_sous_composante  FROM  sous_composante limit 1";
$acteur_p  = mysql_query($query_acteur_p , $pdar_connexion) or die(mysql_error());
$row_acteur_p  = mysql_fetch_assoc($acteur_p);
$totalRows_acteur_p  = mysql_num_rows($acteur_p);
$acteur_act=$row_acteur_p['intitule_sous_composante'];
//$pour="pour ".$row_acteur_p['intitule_sous_composante'];
if($scp!=0) {$acteur_act=" (".$row_acteur_p['intitule_sous_composante'].")"; $pour=" (".$row_acteur_p['intitule_sous_composante'].")";} else {$acteur_act=$pour="";}


//$data = "";
if($totalRows_liste_cp>0) {do {
	$const=$taux_annuel;
	$cout=0;
    $nombre = ($const>0)?$row_liste_cp['ct']:0;
    $bailleurs[$row_liste_cp['cp']][$an1]=$nombre;
} while ($row_liste_cp = mysql_fetch_assoc($liste_cp)); }


}

?>
<!DOCTYPE HTML>
<html>
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Syst&egrave;me de Suivi &amp; Evaluation <?php if(isset($_SESSION['projet'])) echo 'du '.$_SESSION['clp_sigle']; ?>  | Etats et Rapports</title>
<link rel="shortcut icon" href="../images/favico.ico" >
<link rel="stylesheet" href="../css/cbcscbindex.css" type="text/css" >
<link rel="stylesheet" href="../css/css.css" type="text/css" >
<link  href="../css/thickbox.css" rel="stylesheet" type="text/css" media="screen" />
<script src="../script/jquery-latest.js" type="text/javascript"></script>
<script type="text/javascript" src="../script/function.php"></script>
<script type="text/javascript" src="../script/iepngfix_tilebg.js"></script>
<script src="../script/thickbox.js" type="text/javascript"></script>
<style type="text/css">
<!--
body {
	background-color: #D2E2B1;
}
.Style1 {
	font-size: 12px;
	font-weight: bold;
}
.Style30 {font-weight: bold}
-->
</style>
		<script type="text/javascript" src="js/jquery.min.js"></script>
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

                /*if (i > 0) {

                    // Remove special edition notes
                    name = name.split('/')[0];

                    // Split into brand and version
                    version = name.split('/')[1];

                    brand = version;

                    // Create the main data
                    if (!brands[brand]) {
                        brands[brand] = version;
                    } else {
                        brands[brand] += version;
                    }

                    // Create the version data
                    if (version !== null) {
                        if (!versions[brand]) {
                            versions[brand] = [];
                        }
                        versions[brand].push([version, columns[1][i]]);
                    }
                }*/
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
            $('#container').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Exécutions par source de financement et par année '
                },
                subtitle: {
                    text: 'Cliquez sur une source de financement pour voir les détails!'
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
                            format: '{point.y:,.0f} FCFA'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:,.0f} FCFA</b> <br/>'
                },

                series: [{
                    name: 'Montant réalisé par source de financement',
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
<?php include ("content/tete.php"); ?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="200" valign="top">
    <div id="menu"><?php include("content/sous_menu_rapport.php"); ?>
    <hr>
    <img src="../images/img_stock.png" width="100" height="100" alt="" /> </div>
    </td>
    <td valign="top">
      <?php if(isset($_SESSION['clp_id'])) echo '<div id="corps" align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="titrecorps"><tr><td valign="middle" width="50%" ><h4><span class=\"Style9\" style="color:#336666"> </span><span class=\"Style9\" style="color:#FF0000">Compte rendu du SSE</span></h4></td><td valign="middle" align="right"><h4>Bienvenue '.$_SESSION['clp_nom'].' | <a href="logout.php" title="Fermer la session">D&eacute;connexion</a></h4></td></tr></table>'; else { ?>
    <div id="corps" align="center">
      <h2 class="titrecorps">&nbsp;</h2>
      <?php } ?>
<?php if(isset($_SESSION['clp_id'])) { ?>
<div class="contenu">
<div id="msg" align="center" class="red"></div>
<?php

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_bailleurs = "SELECT sigle, id_partenaire,code_type, intitule from partenaire,type_part WHERE bailleur=id_partenaire and projet=$projet ORDER BY sigle asc";
$liste_bailleurs  = mysql_query($query_liste_bailleurs , $pdar_connexion) or die(mysql_error());
$row_liste_bailleurs  = mysql_fetch_assoc($liste_bailleurs);
$totalRows_liste_bailleurs  = mysql_num_rows($liste_bailleurs);
if($totalRows_liste_bailleurs>0){ $i=1; do{
foreach($annee_array as $an){
   $data_array[$i]=((isset($bailleur_array[$row_liste_bailleurs["code_type"]]))?$bailleur_array[$row_liste_bailleurs["code_type"]]:$row_liste_bailleurs["code_type"])."/ ".$an."\t ".((isset($bailleurs[$row_liste_bailleurs["code_type"]][$an]))?($bailleurs[$row_liste_bailleurs["code_type"]][$an]):"0")."%";


$i++;
 }

  }while($row_liste_bailleurs  = mysql_fetch_assoc($liste_bailleurs)); }

if(isset($data_array) && count($data_array)>0){
  ?>
<script src="../js/highcharts.js"></script>
<script src="../js/modules/data.js"></script>
<script src="../js/modules/drilldown.js"></script>
<script src="../js/modules/exporting.js"></script>

<br />

<div id="container" style="width: 100%; height: 400px; margin: 0 auto"></div></div>

<pre id="tsv" style="display:none">Bailleurs/annees	Valeur
<?php foreach($data_array as $ccom){ echo $ccom."\n"; }  ?>
 <?php }else echo "<br /><br /><h1 align='center'>Aucun co&ucirc;t import&eacute;</h1>";
      ?>
</pre>

</div>

<?php } else { ?>
<div class="contenu" align="center"><h1 class="contenuh1">Bienvenue dans le SSE du
  <?php if(isset($_SESSION['projet'])) echo 'du '.$_SESSION['clp_sigle']; ?>
</h1>
<div id="msg" align="center" class="red"></div>

<?php include ("../content/connexion.php"); ?>

<h1 class="contenuh1">
  <?php if(isset($_SESSION['slogan'])) echo $_SESSION['slogan']; ?>
</h1>
</div>

<?php } ?>
<div class="titrecorps"></div>

    </td>
  </tr>
  <tr>
    <td colspan="2">
    <div id="pied"><?php include("content/pied.php"); ?>
    </div>
    </td>
  </tr>
</table>
<?php if(isset($_GET['insert'])){ ?>
<script type="text/javascript">
afficher_msg('insert','<?php echo $_GET['insert']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['del'])){ ?>
<script type="text/javascript">
afficher_msg('del','<?php echo $_GET['del']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['update'])){ ?>
<script type="text/javascript">
afficher_msg('update','<?php echo $_GET['update']; ?>');
</script>
<?php }?>


</body>
</html>