<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & DÃƒÂ©veloppement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

/*if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}*/
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');
$array_indic = array("OUI/NON","texte");
//number_format(0, 0, ',', ' ');
?>
<?php
$tab_cout =$tache =$indicateur = array();
if(isset($_GET["annee"])) $annee = intval($_GET["annee"]); else $annee = date("Y")-1;
if(isset($_GET["vers"])) $anneevers = intval($_GET["vers"]); else $anneevers = date("Y");
if(isset($_GET["cmp"])) $cmp = $_GET["cmp"]; else $cmp = "%";
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_activite = "SELECT id,code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=1 and projet='".$_SESSION["clp_projet"]."' order by code asc";
$liste_activite  = mysql_query_ruche($query_liste_activite, $pdar_connexion) or die(mysql_error());
$row_liste_activite  = mysql_fetch_assoc($liste_activite);
$totalRows_liste_activite  = mysql_num_rows($liste_activite);
$composante = array();
$id_composante = array();
if($totalRows_liste_activite>0){
  do{
    $composante[$row_liste_activite["code"]] = "'".((isset($libelle[0])?$libelle[0]:"Composante"))." ".$row_liste_activite["code"]."',";
    $id_composante[$row_liste_activite["code"]]=$row_liste_activite["id"];
	 $tab_cout[$row_liste_activite["code"]]=$tache[$row_liste_activite["code"]]=$indicateur[$row_liste_activite["code"]]=0;
  }while($row_liste_activite  = mysql_fetch_assoc($liste_activite));
}
$rows = mysql_num_rows($liste_activite);*/
	 $taux_cout=$taux_tache=$taux_indicateur=0;
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT nombre,libelle,code_number FROM ".$database_connect_prefix."niveau_config WHERE projet='".$_SESSION["clp_projet"]."'  LIMIT 1";
$entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);
  $libelle = array();  $nb_code = array();
    if($totalRows_entete>0){$libelle=explode(",",$row_entete["libelle"]); $nb_code=explode(",",$row_entete["code_number"]); 
   $max_niveau=$row_entete["nombre"]-1;
$code_len = explode(',',$row_entete["code_number"]);
$libelle=explode(",",$row_entete["libelle"]);
//$cmp = $row_entete["nombre"];
}
   $max_niveau=$row_entete["nombre"]-1;*/
   
//Nombre d'activités
//if(isset($nb_code[0])){
  $query_liste_actpa = "SELECT count(id_ptba) as nactivitep FROM ".$database_connect_prefix."ptba where ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."'";
try{
    $liste_actpa = $pdar_connexion->prepare($query_liste_actpa);
    $liste_actpa->execute();
    $row_liste_actpa = $liste_actpa ->fetch();
    $totalRows_liste_actpa = $liste_actpa->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $activitep_array = 0;
  if($totalRows_liste_actpa>0){ $activitep_array = $row_liste_actpa["nactivitep"]; } 
  
 // echo $activitep_array ;
//suivi du budget
//if(isset($cmp) && $cmp=='%') {
$query_liste_couta = "SELECT SUM( if(cout_prevu>0, cout_prevu,0) ) AS prevu, SUM( if(cout_realise>0, cout_realise,0) ) AS realise, SUM( if(cout_engage>0, cout_engage,0) ) AS engage FROM ".$database_connect_prefix."code_activite WHERE ".$database_connect_prefix."code_activite.projet='".$_SESSION["clp_projet"]."'  and annee=$annee and code!='Code' and code!='fichiers'";
try{
    $liste_couta = $pdar_connexion->prepare($query_liste_couta);
    $liste_couta->execute();
    $row_liste_couta = $liste_couta ->fetchAll();
    $totalRows_liste_couta = $liste_couta->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_liste_couta>0){ foreach($row_liste_couta as $row_liste_couta){
if($row_liste_couta["prevu"]>0) $taux_cout=100*($row_liste_couta["realise"]/$row_liste_couta["prevu"]);
//else $tab_cout[$row_liste_couta["code"]]=0;
 //$depense_array[$row_liste_couta["code"]]=$row_liste_couta["realise"]+$row_liste_couta["engage"];
} }
//Taux tache
/*$query_liste_taux_tache = "SELECT sum(proportions) as taux_tact FROM
 (SELECT SUM(s.proportion) as proportions,  id_ptba, code_activite_ptba FROM ".$database_connect_prefix."ptba, ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."suivi_tache s WHERE id_ptba=".$database_connect_prefix."groupe_tache.id_activite and id_groupe_tache=id_tache  and s.valider=1  and ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' group by id_ptba, code_activite_ptba) AS alias_sr ";*/
 
 $query_liste_taux_tache = "select sum(total) as taux_cp from (SELECT ROUND(SUM(if(n_lot>0 && valider=1, proportion*jalon/n_lot,0))) as total, id_ptba, code_activite_ptba FROM ptba left join groupe_tache  ON ptba.id_ptba = groupe_tache.id_activite where ptba.annee='$annee' and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY id_ptba, code_activite_ptba) as r1";
 
 //echo $query_liste_taux_tache;  exit;
 
try{
    $liste_taux_tache = $pdar_connexion->prepare($query_liste_taux_tache);
    $liste_taux_tache->execute();
    $row_liste_taux_tache = $liste_taux_tache ->fetchAll();
    $totalRows_liste_taux_tache = $liste_taux_tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tache = array();
if($totalRows_liste_taux_tache>0){ foreach($row_liste_taux_tache as $row_liste_taux_tache){
 if(isset($activitep_array) && $activitep_array>0 && isset($row_liste_taux_tache["taux_cp"])) $taux_tache=$row_liste_taux_tache["taux_cp"]/$activitep_array;
 //$realise_arrayas[$row_liste_couta["code"]]=$row_liste_couta["realise"]+$row_liste_couta["engage"];
 } }
//}
//echo $taux_tache;
// Taux indicateurs
$query_liste_taux_ind_ptba = "select sum(if(taux>1, 1,taux))  as taux_niveau from ".$database_connect_prefix."ptba inner join  (
SELECT Avg(if(Total_cible>0,Total_suivi/Total_cible,0)) AS Taux, ".$database_connect_prefix."indicateur_tache.id_activite
FROM (".$database_connect_prefix."indicateur_tache INNER JOIN 
(SELECT SUM(".$database_connect_prefix."cible_indicateur_trimestre.cible) AS Total_cible
, ".$database_connect_prefix."cible_indicateur_trimestre.indicateur as indicateur
FROM ".$database_connect_prefix."cible_indicateur_trimestre
GROUP BY ".$database_connect_prefix."cible_indicateur_trimestre.indicateur)  AS Cible_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Cible_indicateur.indicateur) INNER JOIN 
(SELECT  SUM(".$database_connect_prefix."suivi_indicateur_tache.valeur_suivi)  AS Total_suivi
,  ".$database_connect_prefix."suivi_indicateur_tache.indicateur as indicateur
FROM  ".$database_connect_prefix."suivi_indicateur_tache
GROUP BY  ".$database_connect_prefix."suivi_indicateur_tache.indicateur)  AS Valeur_Suivi_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Valeur_Suivi_indicateur.indicateur
GROUP BY ".$database_connect_prefix."indicateur_tache.id_activite) as taux_ptba  ON ".$database_connect_prefix."ptba.id_ptba = taux_ptba.id_activite where ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."'";
try{
    $liste_taux_ind_ptba = $pdar_connexion->prepare($query_liste_taux_ind_ptba);
    $liste_taux_ind_ptba->execute();
    $row_liste_taux_ind_ptba = $liste_taux_ind_ptba ->fetchAll();
    $totalRows_liste_taux_ind_ptba = $liste_taux_ind_ptba->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$indicateur = array();
//$tab_ptba_ind = array();
if($totalRows_liste_taux_ind_ptba>0){ foreach($row_liste_taux_ind_ptba as $row_liste_taux_ind_ptba){
 if(isset($activitep_array) && $activitep_array>0) $taux_indicateur=100*$row_liste_taux_ind_ptba["taux_niveau"]/$activitep_array;
//$tab_ptba_ind[]=$row_liste_ind_ptba["code_activite_ptba"]."<!>".$row_liste_ind_ptba["id_ptba"]."<!>".$row_liste_ind_ptba["id_indicateur_tache"];
//$n_ptba_ind[$row_liste_ind_ptba["id_ptba"]]=$row_liste_ind_ptba["nacti"];
//echo $indicateur[$row_liste_taux_ind_ptba["code"]];
} }

//echo " ind=".$$taux_tache;
// requete sous_composante
//exit;
   // }   
//tache
//if($ntatc>0) $tache[$row_liste_activite['code']] = number_format($ttrc/$ntatc, 2, '.', ' ');
//else $tache[$row_liste_activite['code']] = 0;
//indicateur
//if($ntac>0) $indicateur[$row_liste_activite['code']] = number_format($tgic/$ntac, 2, '.', ' ');
//else $indicateur[$row_liste_activite['code']] = 0;
//$tgi=$tgi+$trai; $tgic=$tgic+$trai; $tgiptba=$tgiptba+$trai;
//cout
//if(isset($cout_array[$row_liste_activite["code"]]) && isset($depense_array[$row_liste_activite["code"]]) && $cout_array[$row_liste_activite["code"]]>0) $tab_cout[$row_liste_activite['code']] = number_format(100*($depense_array[$row_liste_activite["code"]]/$cout_array[$row_liste_activite["code"]]), 2, '.', ' ');
//else $tab_cout[$row_liste_activite['code']] = 0;
  $query_liste_versiona = "SELECT * FROM ".$database_connect_prefix."version_ptba ORDER BY date_validation asc";
try{
    $liste_versiona = $pdar_connexion->prepare($query_liste_versiona);
    $liste_versiona->execute();
    $row_liste_versiona = $liste_versiona ->fetchAll();
    $totalRows_liste_versiona = $liste_versiona->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$TableauVersionPA = array(); //$version_array = array();
if($totalRows_liste_versiona>0){ foreach($row_liste_versiona as $row_liste_versiona){
//$max_version=$row_liste_version["id_version_ptba"];
$TableauVersionPA[$row_liste_versiona["id_version_ptba"]]=$row_liste_versiona["annee_ptba"]." ".$row_liste_versiona["version_ptba"];
//$version_array[$row_liste_version["version_ptba"]] = $row_liste_version["id_version_ptba"];
 } }
 
 if(isset($TableauVersionPA[$annee])) $anneevers=$TableauVersionPA[$annee];
?>
<!DOCTYPE HTML>
<html>
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo "PTBA $annee ";      ?></title>
</head>
	<body>
<div id="tab_feed2_<?php echo $annee; ?>" style="height: 300px; max-height: 300px; margin: 0 auto"></div>
<script type="text/javascript">
//composante
$(function () {
        $('#tab_feed2_<?php echo $annee; ?>').highcharts({
            chart: {
                type: 'column'
            },
		colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
            title: {
                text: '<?php echo ("");  ?>'
            },
            subtitle: {
                text: '<?php echo ("Exécution globale PTBA $anneevers"); ?>'
            },
            xAxis: {
                categories: [ <?php echo "' '";  ?>  ]
            },
            yAxis: {
                min: 0,
                max: 100,
                title: {
                    text: '<?php echo ("Pourcentage d\'exécution"); ?>'
                }
            },
            tooltip: {
               pointFormat:'{series.name}: {point.y:.0f} %'
            },
            plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '<b><span style="color:#000000">{point.y:.0f}%</span></b>'
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
            series: [<?php //if(isset($cmp) && $cmp=='%') {?> {
                name: '<?php echo ("Avancement"); ?>',
                data: [<?php echo $taux_tache;  ?>]
            }, <?php //} ?> {
                name: '<?php echo ("Réalisation"); ?>',
                data: [<?php  echo $taux_indicateur;  ?>]
            }<?php //if(isset($cmp) && $cmp=='%') {?>, {
                name: '<?php echo ("Décaissement"); ?>',
                data: [<?php echo $taux_cout;  ?>]
            }<?php //} ?>]
        });
    });
</script>
	</body>
</html>