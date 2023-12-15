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

//if($scp==0)
$query_taux_annee = "SELECT SUM(montant) AS taux FROM ".$database_connect_prefix."part_bailleur where  annee=$annee and projet='".$_SESSION["clp_projet"]."' order by type_part";
     try{
  $taux_annee = $pdar_connexion->prepare($query_taux_annee);
    $taux_annee->execute();
    $row_taux_annee = $taux_annee ->fetch();
    $totalRows_taux_annee = $taux_annee->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


$query_entete = "SELECT code_number FROM ".$database_connect_prefix."niveau_config WHERE projet='".$_SESSION["clp_projet"]."' LIMIT 1";
     try{
  $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$tmp = explode(',',$row_entete["code_number"]);
$niveau = isset($tmp[0])?$tmp[0]:1;

$query_liste_cp = "SELECT SUM( montant ) AS ct, LEFT(code_activite_ptba,$niveau) as cp  FROM ".$database_connect_prefix."part_bailleur, ".$database_connect_prefix."ptba  where  ".$database_connect_prefix."ptba.annee=$annee and ".$database_connect_prefix."part_bailleur.activite=".$database_connect_prefix."ptba.id_ptba and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' group by cp";

//echo $query_liste_cp; 
     try{
  $liste_cp = $pdar_connexion->prepare($query_liste_cp);
    $liste_cp->execute();
    $row_liste_cp = $liste_cp ->fetchAll();
    $totalRows_liste_cp = $liste_cp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$acteur_act=$pour="";

$query_liste_categorie_depense1 = "SELECT code FROM ".$database_connect_prefix."activite_projet WHERE niveau=1 and projet='".$_SESSION["clp_projet"]."' ORDER BY code asc";
     try{
  $liste_categorie_depense1 = $pdar_connexion->prepare($query_liste_categorie_depense1);
    $liste_categorie_depense1->execute();
    $row_liste_categorie_depense1 = $liste_categorie_depense1 ->fetchAll();
    $totalRows_liste_categorie_depense1 = $liste_categorie_depense1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$catdep_array = array();
  if($totalRows_liste_categorie_depense1>0){
foreach($row_liste_categorie_depense1 as $row_liste_categorie_depense1){   $catdep_array[$row_liste_categorie_depense1["code"]]=(strlen($row_liste_categorie_depense1["code"])>20)?substr($row_liste_categorie_depense1["code"],0,20)."...":$row_liste_categorie_depense1["code"];  } }

//print_r($catdep_array);exit;

$taux_annuel=$row_taux_annee['taux'];
$cout=0;
//echo $taux_annuel; exit;

if($totalRows_liste_cp>0 && $taux_annuel>0){
  $query_entete = "SELECT libelle,code_number FROM ".$database_connect_prefix."niveau_config WHERE projet='".$_SESSION["clp_projet"]."' LIMIT 1"; 
     try{
  $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $code_len = explode(',',$row_entete["code_number"]);
  $libelle=explode(",",$row_entete["libelle"]);
  $libelle = (isset($libelle[0]) && !empty($libelle[0]))?$libelle[0]:((isset($libelle[1]) && !empty($libelle[1]))?$libelle[1]:"Composante")
?>
<!DOCTYPE HTML>
<html>
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Co&ucirc;t des activités par <?php echo $libelle; ?>s en <?php echo "$annee $acteur_act";  ?></title>

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
                text: '<?php echo utf8_decode("Budget PTBA par $libelle"."s"." en ")."$annee $acteur_act";  ?>'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.0f}%</b><br /><u>Montant total</u>: <b><?php echo number_format($taux_annuel, 0, ',', ' ');  ?> </b>'
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
if($totalRows_liste_cp>0) {foreach($row_liste_cp as $row_liste_cp){
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

$query_liste_categorie = "SELECT code FROM ".$database_connect_prefix."activite_projet WHERE niveau=1 and projet='".$_SESSION["clp_projet"]."' ORDER BY code asc";
     try{
  $liste_categorie = $pdar_connexion->prepare($query_liste_categorie);
    $liste_categorie->execute();
    $row_liste_categorie = $liste_categorie ->fetchAll();
    $totalRows_liste_categorie = $liste_categorie->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if($totalRows_liste_categorie>0){
  foreach($row_liste_categorie as $row_liste_categorie){   if(isset($categorie[$row_liste_categorie["code"]]) && intval($categorie[$row_liste_categorie["code"]])>0){
  $data .= "['".((strlen($row_liste_categorie['code'])>25)?((isset($libelle))?$libelle." ":"Composante ").utf8_encode(substr($row_liste_categorie['code'],0,25)."..."):((isset($libelle))?$libelle." ":"Composante ").utf8_encode($row_liste_categorie['code']))."',  ".((isset($categorie[$row_liste_categorie["code"]]))?$categorie[$row_liste_categorie["code"]]:0)."],"; }
  }
}
echo substr($data,0,strlen($data)-1);
  ?>
                ]
            }]
        });
    });

});

		</script>
		<?php //print_r($catdep_array); echo $nombre;?>
	</head>
	<body>
<script src="<?php print $path.$config->script_folder; ?>/highcharts.js"></script>
<script src="<?php print $path.$config->script_folder; ?>/modules/exporting.js"></script>
<script src="<?php print $path.$config->script_folder; ?>/modules/offline-exporting.js"></script>

<div id="container_graph" style=" margin: 0 auto"></div>
<?php }else echo '<h1 align="center"><br /><br />'.utf8_encode("Aucun co&ucirc;t import&eacute; en $annee").' '.$pour.' !</h1>';  ?>
	</body>
</html>
