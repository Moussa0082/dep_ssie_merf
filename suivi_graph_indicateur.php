<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
//session_start();
include_once 'system/configuration.php';
$config = new Config;
/*if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}*/
$anneecour=date("Y");
include_once $config->sys_folder . "/database/db_connexion.php";
////header('Content-Type: text/html; charset=UTF-8');
//if(isset($_GET['iref'])) $iref = $_GET['iref']; else $iref=0;
//if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else $annee=date("Y");
//if(isset($_GET['id_ref'])) { $iref = $_GET['id_ref']; }
/*$page1="";
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$page = $_SERVER['PHP_SELF'];
 $messind=0;*/
 
//$iref=1;
 
$letters = array('\'', ')', '(', '"');
$fruit   = array(' ', ' ', ' ', ' ');

//Cible indicateur à sommer
$sql_indicateurg=$nom_indicateurg=$structure="";
$query_cible_indicateurg = "SELECT structure, requete_sql, code_ref_ind, intitule_ref_ind,indicateur_produit, annee, sum(valeur_cible) as valeur_cible, avg(valeur_cible) as valeur_ciblem  FROM  ".$database_connect_prefix."cible_cmr_produit, ".$database_connect_prefix."indicateur_cmr WHERE referentiel=:indicateur and id_ref_ind=indicateur_produit and projet='".$_SESSION['clp_projet']."' group by annee, indicateur_produit order by code_ref_ind";
//echo $query_cible_indicateurg; exit;
try{
    $cible_indicateurg = $pdar_connexion->prepare($query_cible_indicateurg);
    $cible_indicateurg->execute(array(':indicateur' => isset($iref)?$iref:0));
    $row_cible_indicateurg = $cible_indicateurg ->fetchAll();
    $totalRows_cible_indicateurg = $cible_indicateurg->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$cible_arrayg = $ciblem_arrayg = array();
   if($totalRows_cible_indicateurg>0) { foreach($row_cible_indicateurg as $row_cible_indicateurg){
   $cible_arrayg[$iref][$row_cible_indicateurg["annee"]]=$row_cible_indicateurg["valeur_cible"];
  $ciblem_arrayg[$iref][$row_cible_indicateurg["annee"]]=$row_cible_indicateurg["valeur_ciblem"];
  $nom_indicateurg=$row_cible_indicateurg["code_ref_ind"].":".$row_cible_indicateurg["intitule_ref_ind"];
   $sql_indicateurg=$row_cible_indicateurg["requete_sql"];
   $structure=$row_cible_indicateurg["structure"];
   }}
  // print_r($cible_arrayg);
   
   $suivi_val_arrayg = $cible_val_arrayg = array();
   


//print_r($suivi_val_arrayg);
$query_suivi_indicateur_directg = "SELECT  s.annee, s.valeur_suivi, s.indicateur_cr FROM   ".$database_connect_prefix."suivi_indicateur_cmr s, ".$database_connect_prefix."referentiel_indicateur r WHERE  s.projet='".$_SESSION['clp_projet']."' and r.id_ref_ind = s.indicateur_cr group by s.annee, s.indicateur_cr";
try{
    $suivi_indicateur_directg = $pdar_connexion->prepare($query_suivi_indicateur_directg);
    $suivi_indicateur_directg->execute();
    $row_suivi_indicateur_directg = $suivi_indicateur_directg ->fetchAll();
    $totalRows_suivi_indicateur_directg = $suivi_indicateur_directg->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_suivi_indicateur_directg>0){
foreach($row_suivi_indicateur_directg as $row_suivi_indicateur_directg){
 $cible_val_arrayg[$row_suivi_indicateur_directg["indicateur_cr"]][$row_suivi_indicateur_directg["annee"]]=$row_suivi_indicateur_directg["valeur_suivi"];
}}

/*
$query_liste_calcule_ind = sprintf("SELECT * FROM ".$database_connect_prefix."calcul_indicateur_simple_ref ");
  try{
    $liste_calcule_ind = $pdar_connexion->prepare($query_liste_calcule_ind);
    $liste_calcule_ind->execute();
    $row_liste_calcule_ind = $liste_calcule_ind ->fetchAll();
    $totalRows_liste_calcule_ind = $liste_calcule_ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$indicateur_calculegp = array();
if($totalRows_liste_calcule_ind>0){ foreach($row_liste_calcule_ind as $row_liste_calcule_ind){
  $les_indgp = explode(",",$row_liste_calcule_ind["indicateur_simple"]);
  $formulegp=$row_liste_calcule_ind["formule_indicateur_simple"];

//echo  $row_liste_calcule_ind["indicateur_simple"]."</br>";
  if(trim($formulegp)=="Somme"){ foreach($les_indgp as $idindicateur){ for($i=$_SESSION["annee_debut_projet"]; $i<=$anneecour; $i++){ if(!isset($indicateur_calculegp[$row_liste_calcule_ind["indicateur_ref"]][$i])) $indicateur_calculegp[$row_liste_calcule_ind["indicateur_ref"]][$i]=0;  if(isset($suivi_val_arrayg[$idindicateur][$i])) $indicateur_calculegp[$row_liste_calcule_ind["indicateur_ref"]][$i]+=$suivi_val_arrayg[$idindicateur][$i]; elseif(isset($cible_val_arrayg[$idindicateur][$i])) $indicateur_calculegp[$row_liste_calcule_ind["indicateur_ref"]][$i]+=$cible_val_arrayg[$idindicateur][$i]; }  } } 
  
  elseif(trim($formulegp)==" Moyenne"){ foreach($les_indgp as $idindicateur){ for($i=$_SESSION["annee_debut_projet"]; $i<=$anneecour; $i++){ if(!isset($indicateur_calculegp[$row_liste_calcule_ind["indicateur_ref"]][$i])) $indicateur_calculegp[$row_liste_calcule_ind["indicateur_ref"]][$i]=0;  if(isset($suivi_val_arrayg[$idindicateur][$i])) $indicateur_calculegp[$row_liste_calcule_ind["indicateur_ref"]][$i]+=$suivi_val_arrayg[$idindicateur][$i]; elseif(isset($cible_val_arrayg[$idindicateur][$i])) $indicateur_calculegp[$row_liste_calcule_ind["indicateur_ref"]][$i]+=$cible_val_arrayg[$idindicateur][$i]; }   }
  
for($i=$_SESSION["annee_debut_projet"]; $i<=$anneecour; $i++){
if(isset($indicateur_calculegp[$row_liste_calcule_ind["indicateur_ref"]][$i]) && count($les_indgp)>0) $indicateur_calculegp[$row_liste_calcule_ind["indicateur_ref"]][$i]=$indicateur_calculegp[$row_liste_calcule_ind["indicateur_ref"]][$i]/(count($les_indgp)-1);
}
}
  } }*/
  //if(isset($indicateur_calculegp[$iref])) print_r($indicateur_calculegp[$iref]);
  //print_r($indicateur_calculegp);
  
 /*  $query_liste_view_indicateur = $db ->prepare('SELECT Nom_View, Indicateur FROM t_requete_indicateur C WHERE indicateur=:indicateur and Id_Projet=:Id_Projet');
$query_liste_view_indicateur->execute(array(':indicateur' => isset($iref)?$iref:0, ':Id_Projet' => isset($_SESSION["clp_projet"])?$_SESSION["clp_projet"]:0));
$row_liste_view_indicateur = $query_liste_view_indicateur ->fetch();
$totalRows_liste_view_indicateur = $query_liste_view_indicateur->rowCount();*/
//$liste_ind_view_array[$row_liste_view_indicateur["Indicateur"]] = $row_liste_view_indicateur["Nom_View"];

if(isset($sql_indicateurg) && !empty($sql_indicateurg) && $structure==1) {
$query_vval_annee = ''.$sql_indicateurg.'';
$query_vval_annee = $db->prepare($query_vval_annee); //$db needs to be PDO instance
$query_vval_annee->execute();
$row_vval_annee = $query_vval_annee ->fetchAll();
$totalRows_vval_annee = $query_vval_annee->rowCount();

if($totalRows_vval_annee>0){ foreach($row_vval_annee as $row_vval_annee) { $val_view_annee_array[$iref][$row_vval_annee["annee"]] = $row_vval_annee["valeur"]; $totavalind=$totavalind+$row_vval_annee["valeur"]; $totalrfd[$iref]=$row_vval_annee["valeur"];} }
}//}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->



</head>
<body>
<div class="widget box">
<div class="widget-content" style="display: block;">
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<!--<div>--><div style="height:2px; visibility:hidden; display:none">

    <table class="table table-striped table-bordered table-hover table-responsive dataTable v_align" id="datatable<?php echo $iref; ?>p">
  <thead>
    <tr>
      <th >Annee</th>
      <th  class="center"><strong>Cibles</strong></th>
	  <th  class="center"><strong>Réalisations</strong></th>
    </tr>
  </thead>
  <tbody>
	<?php $cummgcible=0; $cummg=0; for($i2=$_SESSION["annee_debut_projet"]; $i2<=$_SESSION["annee_fin_projet"]; $i2++) { if($cible_arrayg[$iref][$i2]) { ?>
    <tr>
      <td><?php echo $i2; ?></td>

	  
      <td  class="center"><?php
				if(isset($cible_arrayg[$iref][$i2])/* && $row_liste_composante['unite']!="%"*/)
				{$cummgcible=$cible_arrayg[$iref][$i2];  echo number_format($cummgcible, 0, '.', ' ');}
				 elseif(isset($ciblem_arrayg[$iref][$i2])/* && $row_liste_composante['unite']=="%"*/)
				{ $cummgcible=$ciblem_arrayg[$iref][$i2]; echo number_format($cummgcible, 0, '.', ' '); }
				  ?></td>
				        <td  class="center"><?php if(isset($suivi_val_arrayg[$iref][$i2])){ $cummg=$suivi_val_arrayg[$iref][$i2]; echo $cummg; } elseif(isset($cible_val_arrayg[$iref][$i2])){ $cummg=$cible_val_arrayg[$iref][$i2]; echo $cummg; }  elseif(isset($val_view_annee_array[$iref][$i2])){   $cummg=$val_view_annee_array[$iref][$i2]; echo $cummg; }
	   ?></td> 
    </tr>
    <?php  } } ?>
  </tbody>
  <?php //} ?>
</table>
</div>
<!-- debut graph typologie -->
<div id="plants<?php echo $iref; ?>p" style="height: 320px;  margin: 0 auto"></div>
<script type="text/javascript">
/*Highcharts.setOptions({
colors: ['#DDDF00','#50B432']
});*/
Highcharts.chart('plants<?php echo $iref; ?>p', {

    data: {
        table: 'datatable<?php echo $iref; ?>p'
    },
	
  /* chart: {
        type: 'column'
    },*/
	  series: [{type: 'column'},{type:'column'}],
	  title: {
        text: '<?php echo str_replace($letters, $fruit, trim($nom_indicateurg)); ?>'
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
            text: '<?php if(isset($liste_code_ref_array[$iref])) echo $liste_code_ref_array[$iref]; else echo "Unit&eacute;"; ?>'   
        }
    },
	      plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
							<?php if(isset($liste_code_ref_array[$iref]) && $liste_code_ref_array[$iref]=="%") {  ?>
                            format: '<b><span style="color:#000000">{point.y:.0f} %</span></b>'
							<?php } else {  ?>
							 format: '<b><span style="color:#000000">{point.y:.0f}</span></b>'
							<?php }  ?>
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
</div></div></body>
</html>
