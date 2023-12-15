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
if(isset($_GET["vers"])) $versionN = $_GET["vers"]; else $versionN = "ND";
if(isset($_GET["annee"])) $annee = intval($_GET["annee"]); elseif(date("m")<4) $annee = date("Y")-1; else  $annee = date("Y");
 if(isset($_GET["cmp"])) $cmp = $_GET["cmp"]; else $cmp = "%%";
/*if(isset($_GET["cmp"]) && $_GET["cmp"]!=0) {$cmp=$_GET["cmp"]; $wheract_tache="AND ugl like '$cmp'"; } else $wheract_tache="";
if(isset($_GET["cmp"]) && $_GET["cmp"]!=0) {$cmp=$_GET["cmp"];  $whercible="and cible_indicateur_trimestre.region like '$cmp'"; $whersuivi="and suivi_indicateur_tache.ugl like '$cmp'"; } else {$wheract=""; $whercible=$whersuivi="";}*/
 $wheract_tache="";
$whercible=$whersuivi="";
//$rows = mysql_num_rows($liste_activite);
/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);
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
   $max_niveau=$row_entete["nombre"]-1;
   
//Nombre d'activités
if(isset($nb_code[0])){
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_actpa = "SELECT left(code_activite_ptba,'".$nb_code[0]."') as code, count(id_ptba) as nactivitep FROM ".$database_connect_prefix."ptba where ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' and region like '%$cmp%' GROUP BY code";
  $liste_actpa  = mysql_query_ruche($query_liste_actpa , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_actpa = mysql_fetch_assoc($liste_actpa);
  $totalRows_liste_actpa = mysql_num_rows($liste_actpa);
  $activitep_array = array();
  if($totalRows_liste_actpa>0){
  do{$activitep_array[$row_liste_actpa["code"]] = $row_liste_actpa["nactivitep"];
  }while($row_liste_actpa = mysql_fetch_assoc($liste_actpa)); }
//Cout par composante
//if(isset($nb_code[0])){
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_actpa = "SELECT left(code_activite_ptba,'".$nb_code[0]."') as code, sum(montant) as cout_cp FROM ".$database_connect_prefix."ptba, part_bailleur where ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' and activite=id_ptba GROUP BY code";
  $liste_actpa  = mysql_query_ruche($query_liste_actpa , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_actpa = mysql_fetch_assoc($liste_actpa);
  $totalRows_liste_actpa = mysql_num_rows($liste_actpa);
  $cout_cp_array = array();
  if($totalRows_liste_actpa>0){
  do{$cout_cp_array[$row_liste_actpa["code"]] = $row_liste_actpa["cout_cp"];
  }while($row_liste_actpa = mysql_fetch_assoc($liste_actpa)); }*/
//suivi du budget
  $query_liste_actpa = "SELECT count(distinct id_activite) as nb , region as id_sd FROM cible_indicateur_trimestre, indicateur_tache where id_indicateur_tache=indicateur and id_activite in (select id_ptba from ptba where ptba.annee='$annee') group by id_sd";
try{
    $liste_actpa = $pdar_connexion->prepare($query_liste_actpa);
    $liste_actpa->execute();
    $row_liste_actpa = $liste_actpa ->fetchAll();
    $totalRows_liste_actpa = $liste_actpa->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $activitep_array = array();
  if($totalRows_liste_actpa>0){
  foreach($row_liste_actpa as $row_liste_actpa){$activitep_array[$row_liste_actpa["id_sd"]] = $row_liste_actpa["nb"];
  } }

$query_liste_activite = "SELECT *   FROM ugl   order by code_ugl";
try{
    $liste_activite = $pdar_connexion->prepare($query_liste_activite);
    $liste_activite->execute();
    $row_liste_activite = $liste_activite ->fetchAll();
    $totalRows_liste_activite = $liste_activite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$composante = array();
$nom_composante = array();
if($totalRows_liste_activite>0){ foreach($row_liste_activite as $row_liste_activite){
  /*if(isset($activitep_array[$row_liste_activite["code"]]) && $activitep_array[$row_liste_activite["code"]]>0) { */
   // $composante[$row_liste_activite["id_sd"]] = "'".$row_liste_activite["abrege"]."',";
    $nom_composante[$row_liste_activite["code_ugl"]]=$row_liste_activite["abrege_ugl"];
	 //$indicateur[$row_liste_activite["id_sd"]]=0;
	 /*}*/
  }
}
/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);
//$query_liste_couta = "SELECT left(code,'".$nb_code[0]."') as code, SUM( if(cout_prevu>0, cout_prevu,0) ) AS prevu, SUM( if(cout_realise>0, cout_realise,0) ) AS realise, SUM( if(cout_engage>0, cout_engage,0) ) AS engage FROM ".$database_connect_prefix."code_activite WHERE ".$database_connect_prefix."code_activite.projet='".$_SESSION["clp_projet"]."'  and annee=$annee and code!='Code' and code!='fichiers'    group by left(code,'".$nb_code[0]."') ORDER BY code ASC";
  $query_liste_couta = "SELECT left(code_activite_ptba,'".$nb_code[0]."') as code, SUM( if(decaissement_activite.statut=0, cout_realise,0) ) AS realise, SUM( if(decaissement_activite.statut!=0, cout_realise,0) ) AS engage FROM ".$database_connect_prefix."decaissement_activite, ptba WHERE  id_activite=id_ptba and  ptba.projet=decaissement_activite.projet and ptba.projet='".$_SESSION["clp_projet"]."' and ptba.annee=$annee group by code";
  
$liste_couta = mysql_query_ruche($query_liste_couta , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_couta = mysql_fetch_assoc($liste_couta);
$totalRows_liste_couta = mysql_num_rows($liste_couta);
if($totalRows_liste_couta>0){
do{
if(isset($cout_cp_array[$row_liste_couta["code"]]) && $cout_cp_array[$row_liste_couta["code"]]>0) $tab_cout[$row_liste_couta["code"]]=100*($row_liste_couta["realise"]/$cout_cp_array[$row_liste_couta["code"]]);
//else $tab_cout[$row_liste_couta["code"]]=0;
 //$depense_array[$row_liste_couta["code"]]=$row_liste_couta["realise"]+$row_liste_couta["engage"];
 }
while($row_liste_couta  = mysql_fetch_assoc($liste_couta));}*/
/*//Taux tache
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_taux_tache = "SELECT sum(proportions) as taux_tact, left(code_activite_ptba,'".$nb_code[0]."') as code FROM
 (SELECT SUM(s.proportion) as proportions,  id_ptba, code_activite_ptba FROM ".$database_connect_prefix."ptba, ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."suivi_tache s WHERE id_ptba=".$database_connect_prefix."groupe_tache.id_activite and id_groupe_tache=id_tache  and s.valider=1 and ugl='$cmp' and ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' group by id_ptba, code_activite_ptba) AS alias_sr  group by code";
//echo $query_liste_taux_tache;
//exit;
$liste_taux_tache  = mysql_query_ruche($query_liste_taux_tache , $pdar_connexion) or die(mysql_error());
$row_liste_taux_tache  = mysql_fetch_assoc($liste_taux_tache);
$totalRows_liste_taux_tache = mysql_num_rows($liste_taux_tache);
$tache = array();
if($totalRows_liste_taux_tache>0){
do{
 if(isset($activitep_array[$row_liste_taux_tache["code"]]) && $activitep_array[$row_liste_taux_tache["code"]]>0) $tache[$row_liste_taux_tache["code"]]=$row_liste_taux_tache["taux_tact"]/$activitep_array[$row_liste_taux_tache["code"]];
 //$realise_arrayas[$row_liste_couta["code"]]=$row_liste_couta["realise"]+$row_liste_couta["engage"];
 }
while($row_liste_taux_tache  = mysql_fetch_assoc($liste_taux_tache));}*/
/*
// Taux indicateurs
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_taux_ind_ptba = "select sum(taux) as taux_niveau, left(code_activite_ptba,'".$nb_code[0]."') as code from ".$database_connect_prefix."ptba inner join  (
SELECT Avg(if(Total_cible>0,Total_suivi/Total_cible,0)) AS Taux, ".$database_connect_prefix."indicateur_tache.id_activite
FROM (".$database_connect_prefix."indicateur_tache INNER JOIN 
(SELECT SUM(if(region='$cmp',".$database_connect_prefix."cible_indicateur_trimestre.cible,0) ) AS Total_cible
, ".$database_connect_prefix."cible_indicateur_trimestre.indicateur as indicateur
FROM ".$database_connect_prefix."cible_indicateur_trimestre
GROUP BY ".$database_connect_prefix."cible_indicateur_trimestre.indicateur)  AS Cible_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Cible_indicateur.indicateur) INNER JOIN 
(SELECT  SUM(if(ugl='$cmp',".$database_connect_prefix."suivi_indicateur_tache.valeur_suivi,0))  AS Total_suivi
,  ".$database_connect_prefix."suivi_indicateur_tache.indicateur as indicateur
FROM  ".$database_connect_prefix."suivi_indicateur_tache
GROUP BY  ".$database_connect_prefix."suivi_indicateur_tache.indicateur)  AS Valeur_Suivi_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Valeur_Suivi_indicateur.indicateur
GROUP BY ".$database_connect_prefix."indicateur_tache.id_activite) as taux_ptba  ON ".$database_connect_prefix."ptba.id_ptba = taux_ptba.id_activite where ".
$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."'group by code";
//echo $query_liste_taux_ind_ptba;
//exit;
$liste_taux_ind_ptba  = mysql_query_ruche($query_liste_taux_ind_ptba , $pdar_connexion) or die(mysql_error());
$row_liste_taux_ind_ptba  = mysql_fetch_assoc($liste_taux_ind_ptba);
$totalRows_liste_taux_ind_ptba  = mysql_num_rows($liste_taux_ind_ptba);
$indicateur = array();
//$tab_ptba_ind = array();
if($totalRows_liste_taux_ind_ptba>0){
do{
 if(isset($activitep_array[$row_liste_taux_ind_ptba["code"]]) && $activitep_array[$row_liste_taux_ind_ptba["code"]]>0) $indicateur[$row_liste_taux_ind_ptba["code"]]=100*$row_liste_taux_ind_ptba["taux_niveau"]/$activitep_array[$row_liste_taux_ind_ptba["code"]];
//$tab_ptba_ind[]=$row_liste_ind_ptba["code_activite_ptba"]."<!>".$row_liste_ind_ptba["id_ptba"]."<!>".$row_liste_ind_ptba["id_indicateur_tache"];
//$n_ptba_ind[$row_liste_ind_ptba["id_ptba"]]=$row_liste_ind_ptba["nacti"];
//echo $indicateur[$row_liste_taux_ind_ptba["code"]];
}while($row_liste_taux_ind_ptba  = mysql_fetch_assoc($liste_taux_ind_ptba));}
// requete sous_composante
//exit;
    }  */ 
/*
// Taux tache
mysql_select_db($database_pdar_connexion, $pdar_connexion);
//$query_liste_taux_tache_cp = "select sum(if(tauxx>0 && montant>0,tauxx*montant,0)) as taux_cp, left(code_activite_ptba,1) as cp from (SELECT id_ptba, code_activite_ptba, sum(tsuivi)/avg(tcible) as tauxx FROM (SELECT ptba.id_ptba, code_activite_ptba, groupe_tache.id_groupe_tache, count(distinct ugl) AS tcible FROM ptba INNER JOIN (groupe_tache INNER JOIN tache_ugl ON groupe_tache.id_groupe_tache= tache_ugl.tache ) ON ptba.id_ptba = groupe_tache.id_activite where ptba.annee='$annee' $wheract_tache and tlot>0 and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY ptba.id_ptba, code_activite_ptba, groupe_tache.id_groupe_tache) AS cible left JOIN (SELECT groupe_tache.id_groupe_tache, SUM(suivi_tache.proportion) AS tsuivi FROM ptba INNER JOIN (groupe_tache LEFT JOIN suivi_tache ON groupe_tache.id_groupe_tache= suivi_tache.id_tache) ON ptba.id_ptba = groupe_tache.id_activite where ptba.annee='$annee' $wheract_tache and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY groupe_tache.id_groupe_tache) AS suivi ON cible.id_groupe_tache= suivi.id_groupe_tache GROUP BY id_ptba) as r1, part_bailleur where id_ptba=activite group by cp";
$query_liste_taux_tache_cp = "select avg(total) as taux_cp, ugl  as cp from
 (SELECT ROUND(SUM(if(s.valider=1, s.proportion, 0))) as total, id_ptba, ugl FROM ptba left join (groupe_tache inner JOIN suivi_tache s ON groupe_tache.id_groupe_tache = s.id_tache and activite_ptba=id_activite)  ON ptba.id_ptba = groupe_tache.id_activite where  ptba.annee='$annee'  GROUP BY id_ptba, ugl) 
as r1 group by cp order by taux_cp desc";
//echo $query_liste_taux_tache_cp;
//exit;
$liste_taux_tache_cp  = mysql_query_ruche($query_liste_taux_tache_cp , $pdar_connexion) or die(mysql_error());
$row_liste_taux_tache_cp  = mysql_fetch_assoc($liste_taux_tache_cp);
$totalRows_liste_taux_tache_cp  = mysql_num_rows($liste_taux_tache_cp);
//$tache = array();
if($totalRows_liste_taux_tache_cp>0){
do{ 
 if(isset($nom_composante[$row_liste_taux_tache_cp["cp"]])) $composante[$row_liste_taux_tache_cp["cp"]]="'".$nom_composante[$row_liste_taux_tache_cp["cp"]]."',";
if($row_liste_taux_tache_cp["taux_cp"]>0) $tache[$row_liste_taux_tache_cp["cp"]]=$row_liste_taux_tache_cp["taux_cp"];
}while($row_liste_taux_tache_cp  = mysql_fetch_assoc($liste_taux_tache_cp));}*/
// Taux indicateurs
$query_liste_taux_ind_ptba = "select sum(tauxx) as taux_cp, region as cp from
 (SELECT region, id_ptba, avg(if(tsuivi>0, if((100*(tsuivi+0)/tcible)>100,100,100*tsuivi/tcible),0)) as tauxx FROM 
(SELECT ptba.id_ptba, indicateur_tache.id_indicateur_tache, sum(cible_indicateur_trimestre.cible) AS tcible, cible_indicateur_trimestre.region  FROM ptba INNER JOIN (indicateur_tache INNER JOIN  cible_indicateur_trimestre ON indicateur_tache.id_indicateur_tache= cible_indicateur_trimestre.indicateur ) ON ptba.id_ptba = indicateur_tache.id_activite where ptba.annee='$annee' and cible>0  GROUP BY ptba.id_ptba, indicateur_tache.id_indicateur_tache, region) AS cible LEFT JOIN (SELECT indicateur_tache.id_indicateur_tache, SUM(suivi_indicateur_tache.valeur_suivi) AS tsuivi, ugl FROM ptba INNER JOIN (indicateur_tache LEFT JOIN suivi_indicateur_tache ON indicateur_tache.id_indicateur_tache= suivi_indicateur_tache.indicateur) ON ptba.id_ptba = indicateur_tache.id_activite where ptba.annee='$annee'  GROUP BY indicateur_tache.id_indicateur_tache, ugl) AS suivi ON cible.id_indicateur_tache= suivi.id_indicateur_tache and ugl=region GROUP BY id_ptba, region) as r1 group by cp order by taux_cp";
//echo $query_liste_taux_ind_ptba;
//exit;
try{
    $liste_taux_ind_ptba = $pdar_connexion->prepare($query_liste_taux_ind_ptba);
    $liste_taux_ind_ptba->execute();
    $row_liste_taux_ind_ptba = $liste_taux_ind_ptba ->fetchAll();
    $totalRows_liste_taux_ind_ptba = $liste_taux_ind_ptba->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
//$indicateur = array();
if($totalRows_liste_taux_ind_ptba>0){ foreach($row_liste_taux_ind_ptba as $row_liste_taux_ind_ptba){
 if(isset($nom_composante[$row_liste_taux_ind_ptba["cp"]]) && $row_liste_taux_ind_ptba["taux_cp"]>0 && isset($activitep_array[$row_liste_taux_ind_ptba["cp"]])) $composante_i[$row_liste_taux_ind_ptba["cp"]]="'".$nom_composante[$row_liste_taux_ind_ptba["cp"]]."',";
if(isset($nom_composante[$row_liste_taux_ind_ptba["cp"]]) && $row_liste_taux_ind_ptba["taux_cp"]>0 && isset($activitep_array[$row_liste_taux_ind_ptba["cp"]])) $indicateur[$row_liste_taux_ind_ptba["cp"]]=$row_liste_taux_ind_ptba["taux_cp"]/$activitep_array[$row_liste_taux_ind_ptba["cp"]];
} }
//print_r($indicateur);
//exit;
//tache
?>
<!DOCTYPE HTML>
<html>
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo "PTBA $annee par antenne";      ?></title>
       
</head>
	<body>
<div id="tab_feed3_<?php echo $annee; ?>" style="height: 320px; max-height: 320px; margin: 0 auto"></div>
<script type="text/javascript">
//composante
$(function () {
        $('#tab_feed3_<?php echo $annee; ?>').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: '<?php echo ("");  ?>'
            },
            subtitle: {
                text: '<?php echo ("PTBA $annee par antenne"); ?>'
            },
            xAxis: {
                categories: [ <?php foreach($composante_i as $c) echo $c;  ?> ]
            },
            yAxis: {
                min: 0,
                max: 100,
                title: {
                    text: '<?php echo ("Pourcentage d\'\u00e9x\u00e9cution"); ?>'
                }
            },
            tooltip: {
               pointFormat:'{series.name}: {point.y:.0f} %'
                /*headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.0f} %</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true */
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
            series: [{
                name: '<?php echo ("R\u00e9sultats"); ?>',
                data: [<?php foreach($indicateur as $c) echo $c.",";  ?>]
            }]
        });
    });
</script>
	</body>
</html>