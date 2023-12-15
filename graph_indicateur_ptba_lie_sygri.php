<?php
session_start();

require_once('../Connections/pdar_connexion.php');

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if(isset($_GET["idsygri"])) { $idsygri=$_GET['idsygri'];} else $idsygri=0;

$page = $_SERVER['PHP_SELF'];

//total indicateur
$query_liste_indicateur = "SELECT * FROM indicateur_sygri1_projet, groupe_indicateur where id_groupe=groupe_indicateur and id_indicateur_sygri_niveau1_projet='$idsygri'";
$liste_indicateur = mysql_query($query_liste_indicateur, $pdar_connexion) or die(mysql_error());
$row_liste_indicateur = mysql_fetch_assoc($liste_indicateur);
$totalRows_liste_indicateur = mysql_num_rows($liste_indicateur);
/*if($totalRows_liste_indicateur>0){  do{
$idsygri=$row_liste_indicateur['id_indicateur_sygri_niveau1_projet'];*/
$totalPIS=array(); $totalPIC=array(); $regionC=array(); $regionS=array();
//suivi
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_vares = "SELECT sum( valeur_suivi ) AS valeur_reelle,annee,zone as region FROM suivi_indicateur_tache, indicateur_tache, ptba
WHERE indicateur_sygri='$idsygri' and id_indicateur_tache = suivi_indicateur_tache.indicateur AND id_ptba=indicateur_tache.activite GROUP BY annee,region ORDER BY annee ASC,region ASC";
$vares = mysql_query($query_vares, $pdar_connexion) or die(mysql_error());
$row_vares = mysql_fetch_assoc($vares);
$totalRows_vares = mysql_num_rows($vares);
if($totalRows_vares>0){  do{ $regionS[$row_vares["annee"]][$row_vares["region"]]=$row_vares["valeur_reelle"];
if(isset($row_vares["valeur_reelle"])){ if(isset($totalPIS[$row_vares['annee']])) $totalPIS[$row_vares['annee']]+=$row_vares["valeur_reelle"]; else $totalPIS[$row_vares['annee']]=$row_vares["valeur_reelle"]; }
else $totalPIS[$row_vares['annee']]=0;  }while($row_vares = mysql_fetch_assoc($vares));}
//cible
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cible_ptba = "SELECT sum(cible_indicateur_trimestre.cible) AS cible_ind_ptba,annee,region FROM cible_indicateur_trimestre, indicateur_tache, ptba
WHERE  cible_indicateur_trimestre.indicateur=id_indicateur_tache and indicateur_sygri='$idsygri' AND id_ptba=indicateur_tache.activite GROUP BY annee,region ORDER BY annee ASC,region ASC";
$cible_ptba  = mysql_query($query_cible_ptba , $pdar_connexion) or die(mysql_error());
$row_cible_ptba = mysql_fetch_assoc($cible_ptba );
$totalRows_cible_ptba = mysql_num_rows($cible_ptba );

if($totalRows_cible_ptba>0){  do{ $regionC[$row_cible_ptba["annee"]][$row_cible_ptba["region"]]=$row_cible_ptba["cible_ind_ptba"];
if(isset($row_cible_ptba["cible_ind_ptba"])){ if(isset($totalPIC[$row_cible_ptba['annee']])) $totalPIC[$row_cible_ptba['annee']]+=$row_cible_ptba["cible_ind_ptba"]; else $totalPIC[$row_cible_ptba['annee']]=$row_cible_ptba["cible_ind_ptba"];  }
else $totalPIC[$row_cible_ptba['annee']]=0;  }while($row_cible_ptba = mysql_fetch_assoc($cible_ptba ));}
//  }while($row_liste_indicateur = mysql_fetch_assoc($liste_indicateur));  }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_annee = "SELECT DISTINCT annee FROM ptba ORDER BY annee asc";
$liste_annee = mysql_query($query_liste_annee, $pdar_connexion) or die(mysql_error());
$row_liste_annee = mysql_fetch_assoc($liste_annee);
$totalRows_liste_annee = mysql_num_rows($liste_annee);
$annee = ""; $annees = array();
if($totalRows_liste_annee>0){ do{ $annees[]=$row_liste_annee["annee"]; $annee.="'Suivi ".$row_liste_annee["annee"]."',";  }while($row_liste_annee = mysql_fetch_assoc($liste_annee)); }
$annee = substr($annee,0,strlen($annee)-1);

 ?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Graphique</title>

		<script type="text/javascript" src="../js/jquery.min.js"></script>
		<script type="text/javascript">
$(function () {
        $('#container').highcharts({

            chart: {
                type: 'column'
            },

            title: {
                text: "<?php echo utf8_encode($row_liste_indicateur["indicateur_sygri_niveau1"]); ?>"
            },

            xAxis: {
                categories: [<?php echo $annee; ?>]
            },

            yAxis: {
                allowDecimals: false,
                min: 0,
                title: {
                    text: 'Total des valeurs suivis'
                }
            },

            legend: {
                enabled: false
            },

            tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        this.series.name +': '+ this.y +'<br/>'+
                        'Total: '+ this.point.stackTotal;
                }
            },

            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            },

            series: [
<?php
//cible
/*$data = "";
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_region = "SELECT * FROM region ORDER BY code asc";
$liste_region = mysql_query($query_liste_region, $pdar_connexion) or die(mysql_error());
$row_liste_region = mysql_fetch_assoc($liste_region);
$totalRows_liste_region = mysql_num_rows($liste_region);

if($totalRows_liste_region>0){ do{
$data.= "{name: '".$row_liste_region["abrege"]."',data: [";
$data1 = "";
foreach($annees as $an){
  if(isset($regionC[$an][$row_liste_region["id_region"]])) $data1.=$regionC[$an][$row_liste_region["id_region"]].",";
  else $data1.="0,";    }
$data.=substr($data1,0,strlen($data1)-1);
$data.="],stack: 'cible'},\n";
 }while($row_liste_region = mysql_fetch_assoc($liste_region)); }

echo substr($data,0,strlen($data)-1);*/
//suivi
$data = "";
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_region = "SELECT * FROM region ORDER BY code asc";
$liste_region = mysql_query($query_liste_region, $pdar_connexion) or die(mysql_error());
$row_liste_region = mysql_fetch_assoc($liste_region);
$totalRows_liste_region = mysql_num_rows($liste_region);

if($totalRows_liste_region>0){ do{
$data.= "{name: '".$row_liste_region["abrege"]."',data: [";
$data1 = "";
foreach($annees as $an){
  if(isset($regionS[$an][$row_liste_region["id_region"]])) $data1.=$regionS[$an][$row_liste_region["id_region"]].",";
  else $data1.="0,";    }
$data.=substr($data1,0,strlen($data1)-1);
$data.="],stack: 'suivi'},\n";
 }while($row_liste_region = mysql_fetch_assoc($liste_region)); }

echo substr($data,0,strlen($data)-1);
?>
            ]
        });
    });

		</script>
	</head>
	<body>
<script src="../js/highcharts.js"></script>
<script src="../js/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

	</body>
</html>
