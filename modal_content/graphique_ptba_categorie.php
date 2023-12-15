<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
$path = '../';
include_once $path . 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"])) {
//header(sprintf("Location: %s", "./"));
  exit;
}
include_once $path . $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');

$tableauCp = array();
$tableauCoutCp = array();
if(isset($_GET['annee'])) $annee=$_GET['annee']; else $annee=date("Y");
if(isset($_GET['scp'])) $scp=$_GET['scp'];  else $scp=0;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
/*if($acteur==0) $query_taux_annee = "SELECT  SUM(cout_prevu) as taux  FROM  ppaao_code_analytique,ppaao_activite where ppaao_code_analytique.annee='$annee' and ppaao_code_analytique.code_budget<>'NA' and ppaao_activite.code_ppaao_activite=ppaao_code_analytique.code_activite_ptba";*/

//if($scp==0)
$query_taux_annee = "SELECT SUM(montant) AS taux FROM ".$database_connect_prefix."part_bailleur where  annee=$annee and projet='".$_SESSION["clp_projet"]."' order by type_part";
/*else $query_taux_annee = "SELECT SUM(montant) AS taux FROM ".$database_connect_prefix."part_bailleur where  annee=$annee and projet='".$_SESSION["clp_projet"]."' order by type_part";*/
$taux_annee  = mysql_query($query_taux_annee , $pdar_connexion) or die(mysql_error());
$row_taux_annee  = mysql_fetch_assoc($taux_annee);
$totalRows_taux_annee  = mysql_num_rows($taux_annee);
mysql_select_db($database_pdar_connexion, $pdar_connexion);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT code_number FROM ".$database_connect_prefix."niveau_config WHERE ".$_SESSION["clp_where"]." LIMIT 1";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);
$tmp = explode(',',$row_entete["code_number"]);
$niveau = isset($tmp[0])?$tmp[0]:1;

//if($scp==0)
$query_liste_cp = "SELECT SUM( montant ) AS ct, LEFT(activite,$niveau) as cp  FROM ".$database_connect_prefix."part_bailleur where  annee=$annee and ".$database_connect_prefix."part_bailleur.projet='".$_SESSION["clp_projet"]."' and activite
in(select code_activite_ptba from ".$database_connect_prefix."ptba, ".$database_connect_prefix."activite_projet
where code=code_activite_ptba and annee=$annee and ".$database_connect_prefix."activite_projet.projet='".$_SESSION["clp_projet"]."') group by cp";  

/*else $query_liste_cp = "SELECT SUM( montant ) AS ct, type_part as cp  FROM ".$database_connect_prefix."part_bailleur where  annee=$annee and ".$database_connect_prefix."part_bailleur.projet='".$_SESSION["clp_projet"]."' and activite
in(select code_activite_ptba from ".$database_connect_prefix."ptba, ".$database_connect_prefix."activite_projet
where code_activite_ptba LIKE CONCAT(code,'%') and annee=$annee and ".$database_connect_prefix."activite_projet.projet='".$_SESSION["clp_projet"]."') group by cp"; */
$liste_cp  = mysql_query($query_liste_cp , $pdar_connexion) or die(mysql_error());
$row_liste_cp  = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp  = mysql_num_rows($liste_cp);

$acteur_act=$pour="";

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_categorie_depense1 = "SELECT * FROM ".$database_connect_prefix."type_part ORDER BY code_type asc";
$liste_categorie_depense1 = mysql_query($query_liste_categorie_depense1, $pdar_connexion) or die(mysql_error());
$row_liste_categorie_depense1 = mysql_fetch_assoc($liste_categorie_depense1);
$totalRows_liste_categorie_depense1 = mysql_num_rows($liste_categorie_depense1);
$catdep_array = array();
  if($totalRows_liste_categorie_depense1>0){
do{ $catdep_array[$row_liste_categorie_depense1["code_type"]]=(strlen($row_liste_categorie_depense1["intitule"])>20)?substr($row_liste_categorie_depense1["intitule"],0,20)."...":$row_liste_categorie_depense1["intitule"];  }
while($row_liste_categorie_depense1  = mysql_fetch_assoc($liste_categorie_depense1));}


$taux_annuel=$row_taux_annee['taux'];
$cout=0;
if($totalRows_liste_cp>0 && $taux_annuel>0){
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_sous_categorie = "SELECT * FROM sous_categorie_depense WHERE projet_id=$projet order by code_sous_categorie";
$liste_sous_categorie  = mysql_query($query_liste_sous_categorie , $pdar_connexion) or die(mysql_error());
$row_liste_sous_categorie  = mysql_fetch_assoc($liste_sous_categorie);
$totalRows_liste_sous_categorie  = mysql_num_rows($liste_sous_categorie);
$sous_cat=array();
if($totalRows_liste_sous_categorie>0){ do{ $sous_cat[$row_liste_sous_categorie["code_sous_categorie"]]=$row_liste_sous_categorie["categorie"]; }while($row_liste_sous_categorie  = mysql_fetch_assoc($liste_sous_categorie)); }*/
mysql_select_db($database_pdar_connexion, $pdar_connexion);  // where projet='".$_SESSION["clp_projet"]."'
$query_liste_convention = "SELECT nom_categorie as categorie_depense, convention_concerne as convention, dotation FROM ".$database_connect_prefix."categorie_depense order by convention";
$liste_convention = mysql_query($query_liste_convention, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_convention = mysql_fetch_assoc($liste_convention);
$totalRows_liste_convention = mysql_num_rows($liste_convention);
$liste_liste_convention_array = array();  $liste_liste_convention_arrayV = array();
if($totalRows_liste_convention>0){  do{
if(!isset($liste_liste_convention_array[$row_liste_convention["categorie_depense"]])) $liste_liste_convention_array[$row_liste_convention["categorie_depense"]]=array();
array_push($liste_liste_convention_array[$row_liste_convention["categorie_depense"]],$row_liste_convention["convention"]);
//$liste_liste_convention_array[$row_liste_convention["categorie_depense"]].=" - ".$row_liste_convention["convention"];
}while($row_liste_convention  = mysql_fetch_assoc($liste_convention));  }
?>
<!DOCTYPE HTML>
<html>
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Coûts (Ouguiya) des activités par cat&eacute;gories en <?php echo "$annee $acteur_act";  ?></title>

		<script type="text/javascript" src="<?php print $path.$config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
		<script type="text/javascript">
$(function () {
    var chart;

    $(document).ready(function () {
         //$(".modal-dialog", window.parent.document).width(700);
    	// Build the chart
        $('#container_graph').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '<?php echo utf8_decode("Coûts Prévus par catégories en ")."$annee $acteur_act";  ?>'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.0f}%</b><br /><u>Montant total</u>: <b><?php echo number_format($taux_annuel, 0, ',', ' ');  ?> Ouguiya</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '{point.name}: <b>{point.percentage:.0f} %</b>'
                },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: 'Part:',
                data: [
<?php
$data = "";
$categorie=array();
if($totalRows_liste_cp>0) {do {
  foreach($liste_liste_convention_array as $dep=>$val){
  $ct=$row_liste_cp['ct']; $cp=$row_liste_cp['cp'];

	$const=$taux_annuel;
	$cout=0;
    $nombre = ($const>0)?$ct:0;
	if(in_array($cp,$val) && intval($nombre)>0)
    {
        if(!isset($categorie[$dep]))
        $categorie[$dep]=$nombre;
        else
        $categorie[$dep]+=$nombre;
	}         }
} while ($row_liste_cp = mysql_fetch_assoc($liste_cp)); }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_categorie = "SELECT * FROM ".$database_connect_prefix."categorie_depense_convention WHERE projet='".$_SESSION["clp_projet"]."' GROUP BY categorie_depense order by categorie_depense";
$liste_categorie  = mysql_query($query_liste_categorie , $pdar_connexion) or die(mysql_error());
$row_liste_categorie  = mysql_fetch_assoc($liste_categorie);
$totalRows_liste_categorie  = mysql_num_rows($liste_categorie);
if($totalRows_liste_categorie>0){
  do{   if(isset($categorie[$row_liste_categorie["categorie_depense"]]) && intval($categorie[$row_liste_categorie["categorie_depense"]])>0){
  $data .= "['".((strlen($row_liste_categorie['categorie_depense'])>25)?utf8_encode(substr($row_liste_categorie['categorie_depense'],0,25)."..."):utf8_encode($row_liste_categorie['categorie_depense']))."',  ".((isset($categorie[$row_liste_categorie["categorie_depense"]]))?$categorie[$row_liste_categorie["categorie_depense"]]:0)."],"; }
  }while($row_liste_categorie  = mysql_fetch_assoc($liste_categorie));
}
echo substr($data,0,strlen($data)-1);
  ?>
                ]
            }]
        });
    });

});
		</script>
	</head>
	<body>
<script src="<?php print $path.$config->script_folder; ?>/highcharts.js"></script>
<!--<script src="<?php print $path.$config->script_folder; ?>/modules/exporting.js"></script>-->

<div id="container_graph" style=" margin: 0 auto"></div>
<?php }else echo '<h1 align="center"><br /><br />'.utf8_encode("Aucun co&ucirc;t import&eacute; en $annee").' '.$pour.' !</h1>';  ?>
	</body>
</html>
