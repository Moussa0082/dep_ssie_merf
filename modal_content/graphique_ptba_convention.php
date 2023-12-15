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

$query_taux_annee = "SELECT SUM(montant) AS taux FROM ".$database_connect_prefix."part_bailleur where  annee=$annee and projet='".$_SESSION["clp_projet"]."' order by type_part";
     try{
  $taux_annee = $pdar_connexion->prepare($query_taux_annee);
    $taux_annee->execute();
    $row_taux_annee = $taux_annee ->fetch();
    $totalRows_taux_annee = $taux_annee->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_cp = "SELECT SUM( montant ) AS ct, type_part as cp  FROM ".$database_connect_prefix."part_bailleur, ".$database_connect_prefix."ptba  where  ".$database_connect_prefix."ptba.annee=$annee and ".$database_connect_prefix."part_bailleur.activite=".$database_connect_prefix."ptba.id_ptba and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' group by cp";
     try{
  $liste_cp = $pdar_connexion->prepare($query_liste_cp);
    $liste_cp->execute();
    $row_liste_cp = $liste_cp ->fetchAll();
    $totalRows_liste_cp = $liste_cp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$acteur_act=$pour="";

$query_liste_categorie_depense1 = "SELECT * FROM ".$database_connect_prefix."type_part where ".$database_connect_prefix."type_part.projet='".$_SESSION["clp_projet"]."' ORDER BY code_type asc";
     try{
  $liste_categorie_depense1 = $pdar_connexion->prepare($query_liste_categorie_depense1);
    $liste_categorie_depense1->execute();
    $row_liste_categorie_depense1 = $liste_categorie_depense1 ->fetchAll();
    $totalRows_liste_categorie_depense1 = $liste_categorie_depense1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$catdep_array = array();
  if($totalRows_liste_categorie_depense1>0){
foreach($row_liste_categorie_depense1 as $row_liste_categorie_depense1){  $catdep_array[$row_liste_categorie_depense1["code_type"]]=(strlen($row_liste_categorie_depense1["intitule"])>20)?substr($row_liste_categorie_depense1["intitule"],0,20)."...":$row_liste_categorie_depense1["intitule"];  }}


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
?>
<!DOCTYPE HTML>
<html>
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Coûts des activités par conventions en <?php echo "$annee $acteur_act";  ?></title>

		<script type="text/javascript" src="<?php print $path.$config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
		<script type="text/javascript">
$(function () {
    var chart;

    $(document).ready(function () {
         $(".modal-dialog", window.parent.document).width(800);
    	// Build the chart
        $('#container_graph').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
				width: 700,
				height: 450,
            },
			
			
            title: {
                text: '<?php echo utf8_decode("Budget PTBA par conventions en ")."$annee $acteur_act";  ?>'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.0f}%</b><br /><u>Montant total</u>: <b><?php echo number_format($taux_annuel, 0, ',', ' ');  ?> FCFA</b>'
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
            series: [{
                type: 'pie',
                name: 'Part:',
                data: [
<?php
$data = "";
$categorie=array();
if($totalRows_liste_cp>0) { foreach($row_liste_cp as $row_liste_cp){
  $ct=$row_liste_cp['ct']; $cp=$row_liste_cp['cp'];

	$const=$taux_annuel;
	$cout=0;
    $nombre = ($const>0)?$ct:0;
	if(isset($catdep_array[$cp]) && intval($nombre)>0)
    {
        if(!isset($categorie[$cp]))
        $categorie[$cp]=$nombre;
        else
        $categorie[$cp]+=$nombre;
	}
}  }

$query_liste_categorie = "SELECT * FROM ".$database_connect_prefix."type_part WHERE projet='".$_SESSION["clp_projet"]."' order by code_type";
     try{
  $liste_categorie = $pdar_connexion->prepare($query_liste_categorie);
    $liste_categorie->execute();
    $row_liste_categorie = $liste_categorie ->fetchAll();
    $totalRows_liste_categorie = $liste_categorie->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if($totalRows_liste_categorie>0){
   foreach($row_liste_categorie as $row_liste_categorie){    if(isset($categorie[$row_liste_categorie["code_type"]]) && intval($categorie[$row_liste_categorie["code_type"]])>0){
  $data .= "['".((strlen($row_liste_categorie['intitule'])>25)?(substr($row_liste_categorie['intitule'],0,25)."..."):($row_liste_categorie['intitule']))."',  ".((isset($categorie[$row_liste_categorie["code_type"]]))?$categorie[$row_liste_categorie["code_type"]]:0)."],"; }
  }}
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
<script src="<?php print $path.$config->script_folder; ?>/modules/exporting.js"></script>
<script src="<?php print $path.$config->script_folder; ?>/modules/offline-exporting.js"></script>

<div id="container_graph" style=" margin: 0 auto"></div>
<?php }else echo '<h1 align="center"><br /><br />'.utf8_encode("Aucun co&ucirc;t saisi en $annee").' '.$pour.' !</h1>';  ?>
	</body>
</html>
