<?php

///////////////////////////////////////////////

/*                 SSE                       */

/*	Conception & DÃ©veloppement: BAMASOFT */

///////////////////////////////////////////////

session_start();

include_once 'system/configuration.php';

$config = new Config;

if (!isset ($_SESSION["clp_id"])) {

  header(sprintf("Location: %s", "./"));

  exit;

}

include_once $config->sys_folder . "/database/db_connexion.php";
$wh="";
if(isset($_GET['cmp']) && $_GET['cmp']!="") $wh = " and code=".GetSQLValueString($_GET['cmp'], "text"); else $wh = "";
$ugl=(isset($_GET['ugl']))?$_GET['ugl']:$_SESSION["clp_structure"];
if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else {$annee=date("Y");}

if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Avancement_Global_PTBA.xls"); }


  $query_entete = "SELECT * FROM ".$database_connect_prefix."niveau_config WHERE projet='".$_SESSION["clp_projet"]."' LIMIT 1";
        try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $libelle = array();  $nb_code = array();
  if($totalRows_entete>0){$libelle=explode(",",$row_entete["libelle"]); $nb_code=explode(",",$row_entete["code_number"]); }
   $max_niveau=$row_entete["nombre"]-1;
  
   if(isset($_GET['niveau']) && $_GET['niveau']!="") {$_SESSION["niveau"]=$_GET['niveau']; $niveau=$_SESSION["niveau"];} elseif(isset($max_niveau)) {$_SESSION["niveau"]=$max_niveau; $niveau=$_SESSION["niveau"];} else { unset($_SESSION["niveau"],$niveau); } 
$where = (!isset($niveau) || $niveau==0)?" niveau =1":" niveau = ".$niveau." ";


if(isset($_GET['niveau'])) 
  $query_liste_couta = "SELECT left(code,'".$nb_code[$niveau]."')  as codea, SUM( if(cout_prevu>0, cout_prevu,0) ) AS prevu, SUM( if(cout_realise>0, cout_realise,0) ) AS realise, SUM( if(cout_engage>0, cout_engage,0) ) AS engage, sum(cout_realise) as cout FROM ".$database_connect_prefix."code_activite WHERE  code_activite.projet='".$_SESSION["clp_projet"]."' and code_activite.annee=$annee group by codea";
    else
   // $query_liste_couta = "SELECT id_ptba  as codea, SUM( if(cout_prevu>0, cout_prevu,0) ) AS prevu, SUM( if(cout_realise>0, cout_realise,0) ) AS realise, SUM( if(cout_engage>0, cout_engage,0) ) AS engage, sum(cout_realise) as cout FROM ".$database_connect_prefix."code_activite, ptba WHERE  ptba.code_activite_ptba=code_activite.code and  ptba.projet=code_activite.projet and ptba.projet='".$_SESSION["clp_projet"]."' and ptba.annee=$annee group by codea";
	  $query_liste_couta = "SELECT left(code,'".$nb_code[$max_niveau]."')  as codea, SUM( if(cout_prevu>0, cout_prevu,0) ) AS prevu, SUM( if(cout_realise>0, cout_realise,0) ) AS realise, SUM( if(cout_engage>0, cout_engage,0) ) AS engage, sum(cout_realise) as cout FROM ".$database_connect_prefix."code_activite WHERE  code_activite.projet='".$_SESSION["clp_projet"]."' and code_activite.annee=$annee group by codea";
   try{
    $liste_couta = $pdar_connexion->prepare($query_liste_couta);
    $liste_couta->execute();
    $row_liste_couta = $liste_couta ->fetchAll();
    $totalRows_liste_couta = $liste_couta->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$realise_arrayas =$realise_arrayae =$total_realise_array = array();
if($totalRows_liste_couta>0){
foreach($row_liste_couta as $row_liste_couta){
 $realise_arrayas[$row_liste_couta["codea"]]=doubleval($row_liste_couta["realise"]); 
 $realise_arrayae[$row_liste_couta["codea"]]=doubleval($row_liste_couta["engage"]); 
 $total_realise_array[$row_liste_couta["codea"]]=doubleval($row_liste_couta["engage"])+doubleval($row_liste_couta["realise"]); 
 $prevu_arrayas[$row_liste_couta["codea"]]=doubleval($row_liste_couta["prevu"]); 
 }} 

//prevision
/*if(isset($_GET['niveau']))  
//$query_liste_couta = "SELECT left(code_activite_ptba,'".$nb_code[$niveau]."')  as code, SUM( if(montant>0, montant,0) ) AS montant  FROM part_bailleur, ptba where  activite=id_ptba and  ptba.projet=part_bailleur.projet and ptba.projet='".$_SESSION["clp_projet"]."' and ptba.annee=$annee group by code";

$query_liste_couta = "SELECT left(code_activite_ptba,'".$nb_code[$niveau]."')  as code, SUM( if(quantite_cu>0 && prix_unitaire>0, prix_unitaire*quantite_cu*quantite_act,0) ) AS montant  FROM cout_unitaire_ptba, ptba where  id_ptba = ptba_activite and  ptba.projet=cout_unitaire_ptba.projet_id and ptba.projet='".$_SESSION["clp_projet"]."' and ptba.annee=$annee group by left(code_activite_ptba,'".$nb_code[$niveau]."')";

  else	
//$query_liste_couta="SELECT activite as code, SUM(if(montant>0, montant,0)) AS montant FROM part_bailleur where annee=$annee and projet='".$_SESSION["clp_projet"]."' group by code";
$query_liste_couta = "SELECT id_ptba as code, SUM( if(quantite_cu>0 && prix_unitaire>0, prix_unitaire*quantite_cu*quantite_act,0) ) AS montant  FROM cout_unitaire_ptba, ptba where    id_ptba = ptba_activite and  ptba.projet=cout_unitaire_ptba.projet_id and  ptba.projet='".$_SESSION["clp_projet"]."' and ptba.annee=$annee  group by code";
   try{
    $liste_couta = $pdar_connexion->prepare($query_liste_couta);
    $liste_couta->execute();
    $row_liste_couta = $liste_couta ->fetchAll();
    $totalRows_liste_couta = $liste_couta->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$prevu_arrayas = array();
//$realise_arrayas =$realise_arrayae =$total_realise_array = array();
if($totalRows_liste_couta>0){
foreach($row_liste_couta as $row_liste_couta){
 $prevu_arrayas[$row_liste_couta["code"]]=$row_liste_couta["montant"]; 
 } } */
//echo $query_liste_couta;
//exit;
if(isset($_GET['niveau'])) 
  $query_liste_actpa = "SELECT left(code_activite_ptba,'".$nb_code[$niveau]."') as code, count(id_ptba) as nactivitep FROM ".$database_connect_prefix."ptba where ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY code"; 
  else 
    $query_liste_actpa = "SELECT left(code_activite_ptba,'".$nb_code[$max_niveau]."') as code, count(id_ptba) as nactivitep FROM ".$database_connect_prefix."ptba where ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY code"; 
	 // }
   try{
    $liste_actpa = $pdar_connexion->prepare($query_liste_actpa);
    $liste_actpa->execute();
    $row_liste_actpa = $liste_actpa ->fetchAll();
    $totalRows_liste_actpa = $liste_actpa->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $activitep_array = array(); $ttact=0;
  if($totalRows_liste_actpa>0){
foreach($row_liste_actpa as $row_liste_actpa){$activitep_array[$row_liste_actpa["code"]] = $row_liste_actpa["nactivitep"]; $ttact=$ttact+$row_liste_actpa["nactivitep"]; 
  } }
  

  //print_r($activitep_array);  // exit;
//poids activite
 /* $query_liste_poids = "SELECT  code, poids_an FROM ".$database_connect_prefix."poids_activite where (poids_activite.annee='$annee' or poids_activite.annee=0) and ".$database_connect_prefix."poids_activite.projet='".$_SESSION["clp_projet"]."' GROUP BY code";
   try{
    $liste_poids = $pdar_connexion->prepare($query_liste_poids);
    $liste_poids->execute();
    $row_liste_poids = $liste_poids ->fetchAll();
    $totalRows_liste_poids= $liste_poids->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $poids_act_array = array(); $ttact=0;
  if($totalRows_liste_poids>0){
foreach($row_liste_poids as $row_liste_poids){$poids_act_array[$row_liste_poids["code"]] = $row_liste_poids["poids_an"]; 
  } } 

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_taux_ind_ptba = "select sum(taux) as taux_niveau, left(code_activite_ptba,'".$nb_code[$niveau]."') as code from ".$database_connect_prefix."ptba inner join  (
SELECT Avg(if(Total_cible>0,Total_suivi/Total_cible,0)) AS Taux, ".$database_connect_prefix."indicateur_tache.id_activite
FROM (".$database_connect_prefix."indicateur_tache INNER JOIN (SELECT SUM( ".$database_connect_prefix."cible_indicateur_trimestre.cible ) AS Total_cible, ".$database_connect_prefix."cible_indicateur_trimestre.indicateur as indicateur
FROM ".$database_connect_prefix."cible_indicateur_trimestre
GROUP BY ".$database_connect_prefix."cible_indicateur_trimestre.indicateur)  AS Cible_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Cible_indicateur.indicateur) INNER JOIN (SELECT SUM( ".$database_connect_prefix."suivi_indicateur_tache.valeur_suivi ) AS Total_suivi,  ".$database_connect_prefix."suivi_indicateur_tache.indicateur as indicateur
FROM  ".$database_connect_prefix."suivi_indicateur_tache
GROUP BY  ".$database_connect_prefix."suivi_indicateur_tache.indicateur)  AS Valeur_Suivi_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Valeur_Suivi_indicateur.indicateur
GROUP BY ".$database_connect_prefix."indicateur_tache.id_activite) as taux_ptba  ON ".$database_connect_prefix."ptba.id_ptba = taux_ptba.id_activite where ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."'group by code";
$liste_taux_ind_ptba  = mysql_query_ruche($query_liste_taux_ind_ptba , $pdar_connexion) or die(mysql_error());
$row_liste_taux_ind_ptba  = mysql_fetch_assoc($liste_taux_ind_ptba);
$totalRows_liste_taux_ind_ptba  = mysql_num_rows($liste_taux_ind_ptba);
$taux_ind_ptba = array();
//$tab_ptba_ind = array();
if($totalRows_liste_taux_ind_ptba>0){
do{$taux_ind_ptba[$row_liste_taux_ind_ptba["code"]]=$row_liste_taux_ind_ptba["taux_niveau"];
//$tab_ptba_ind[]=$row_liste_ind_ptba["code_activite_ptba"]."<!>".$row_liste_ind_ptba["id_ptba"]."<!>".$row_liste_ind_ptba["id_indicateur_tache"];
//$n_ptba_ind[$row_liste_ind_ptba["id_ptba"]]=$row_liste_ind_ptba["nacti"];
}while($row_liste_taux_ind_ptba  = mysql_fetch_assoc($liste_taux_ind_ptba));} */



//Requete 1
/*

SELECT ".$database_connect_prefix."indicateur_tache.id_activite, AVG( IF( Total_cible >0, Total_suivi / Total_cible, 0 ) ) AS Taux
FROM (
".$database_connect_prefix."indicateur_tache
INNER JOIN (

SELECT SUM( ".$database_connect_prefix."cible_indicateur_trimestre.cible ) AS Total_cible, ".$database_connect_prefix."cible_indicateur_trimestre.indicateur AS indicateur
FROM ".$database_connect_prefix."cible_indicateur_trimestre
GROUP BY ".$database_connect_prefix."cible_indicateur_trimestre.indicateur
) AS Cible_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Cible_indicateur.indicateur
)
INNER JOIN (

SELECT SUM( ".$database_connect_prefix."suivi_indicateur_tache.valeur_suivi ) AS Total_suivi, ".$database_connect_prefix."suivi_indicateur_tache.indicateur AS indicateur
FROM ".$database_connect_prefix."suivi_indicateur_tache
GROUP BY ".$database_connect_prefix."suivi_indicateur_tache.indicateur
) AS Valeur_Suivi_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Valeur_Suivi_indicateur.indicateur
GROUP BY ".$database_connect_prefix."indicateur_tache.id_activite
ORDER BY ".$database_connect_prefix."indicateur_tache.id_activite
LIMIT 0 , 30
*/

//Requete 2
 if(isset($_GET['niveau'])) 
// $query_liste_taux_tache = "SELECT ROUND(SUM(s.proportion)) as taux_tact, left(code_activite_ptba,'".$nb_code[$niveau]."') as code FROM ptba, groupe_tache, suivi_tache s, type_tache WHERE  id_ptba=id_activite  and groupe_tache.id_groupe_tache=type_tache.id_groupe_tache and groupe_tache.id_groupe_tache=id_tache and activite_ptba=id_activite  and s.valider=1  GROUP BY code"
 
  $query_liste_taux_tache = "select sum(total) as taux_tact, left(code_activite_ptba,'".$nb_code[$niveau]."') as code from (SELECT ROUND(SUM(if(n_lot>0 && valider=1, proportion*jalon/n_lot,0))) as total, id_ptba, code_activite_ptba FROM ptba left join groupe_tache s  ON ptba.id_ptba = s.id_activite where ptba.annee='$annee' and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY id_ptba, code_activite_ptba) as r1  group by code";
else	
//$query_liste_taux_tache = "SELECT ROUND(SUM(s.proportion)) as taux_tact, id_activite as code FROM ptba, groupe_tache s WHERE id_ptba=id_activite  and s.valider=1 and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY id_activite";
  $query_liste_taux_tache = "select sum(total) as taux_tact, left(code_activite_ptba,'".$nb_code[$max_niveau]."') as code from (SELECT ROUND(SUM(if(n_lot>0 && valider=1, proportion*jalon/n_lot,0))) as total, id_ptba, code_activite_ptba FROM ptba left join groupe_tache s  ON ptba.id_ptba = s.id_activite where ptba.annee='$annee' and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY id_ptba, code_activite_ptba) as r1  group by code";

 $reqv=$query_liste_taux_tache;
 
  try{
    $liste_taux_tache = $pdar_connexion->prepare($query_liste_taux_tache);
    $liste_taux_tache->execute();
    $row_liste_taux_tache = $liste_taux_tache ->fetchAll();
    $totalRows_liste_taux_tache = $liste_taux_tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$realise_tache_niveau = array();
if($totalRows_liste_taux_tache>0){ foreach($row_liste_taux_tache as $row_liste_taux_tache){ if($row_liste_taux_tache["taux_tact"]>0) $realise_tache_niveau[$row_liste_taux_tache["code"]]=$row_liste_taux_tache["taux_tact"];} } 

//Requete 3
 if(isset($_GET['niveau'])) 
 $query_liste_taux_ind_ptba = "select sum(if(Taux>1, 1,Taux)) as taux_cp, left(code_activite_ptba,'".$nb_code[$niveau]."') as code from ".$database_connect_prefix."ptba inner join  (
SELECT Avg(if(Total_cible>0,Total_suivi/Total_cible,0)) AS Taux, ".$database_connect_prefix."indicateur_tache.id_activite
FROM (".$database_connect_prefix."indicateur_tache INNER JOIN 
(SELECT SUM(cible_indicateur_trimestre.cible) AS Total_cible
, ".$database_connect_prefix."cible_indicateur_trimestre.indicateur as indicateur
FROM ".$database_connect_prefix."cible_indicateur_trimestre
GROUP BY ".$database_connect_prefix."cible_indicateur_trimestre.indicateur)  AS Cible_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Cible_indicateur.indicateur) INNER JOIN 
(SELECT  SUM(suivi_indicateur_tache.valeur_suivi)  AS Total_suivi
,  ".$database_connect_prefix."suivi_indicateur_tache.indicateur as indicateur
FROM  ".$database_connect_prefix."suivi_indicateur_tache
GROUP BY  ".$database_connect_prefix."suivi_indicateur_tache.indicateur)  AS Valeur_Suivi_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Valeur_Suivi_indicateur.indicateur
GROUP BY ".$database_connect_prefix."indicateur_tache.id_activite) as taux_ptba  ON ".$database_connect_prefix."ptba.id_ptba = taux_ptba.id_activite where ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' group by code";
else	
//$query_liste_taux_ind_ptba = "SELECT id_ptba as code, avg(if(tsuivi>0, if((100*(tsuivi+0)/tcible)>100,100,tsuivi/tcible),0)) as taux_cp FROM (SELECT ptba.id_ptba, code_activite_ptba, indicateur_tache.id_indicateur_tache, sum(cible_indicateur_trimestre.cible) AS tcible FROM ptba INNER JOIN (indicateur_tache INNER JOIN  cible_indicateur_trimestre ON indicateur_tache.id_indicateur_tache= cible_indicateur_trimestre.indicateur ) ON ptba.id_ptba = indicateur_tache.id_activite where ptba.annee='$annee' and cible>0 and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY ptba.id_ptba, code_activite_ptba, indicateur_tache.id_indicateur_tache) AS cible LEFT JOIN (SELECT indicateur_tache.id_indicateur_tache, SUM(suivi_indicateur_tache.valeur_suivi) AS tsuivi FROM ptba INNER JOIN (indicateur_tache LEFT JOIN suivi_indicateur_tache ON indicateur_tache.id_indicateur_tache= suivi_indicateur_tache.indicateur) ON ptba.id_ptba = indicateur_tache.id_activite where ptba.annee='$annee' and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY indicateur_tache.id_indicateur_tache) AS suivi ON cible.id_indicateur_tache= suivi.id_indicateur_tache GROUP BY code";

 $query_liste_taux_ind_ptba = "select sum(if(Taux>1, 1,Taux)) as taux_cp, left(code_activite_ptba,'".$nb_code[$max_niveau]."') as code from ".$database_connect_prefix."ptba inner join  (
SELECT Avg(if(Total_cible>0,Total_suivi/Total_cible,0)) AS Taux, ".$database_connect_prefix."indicateur_tache.id_activite
FROM (".$database_connect_prefix."indicateur_tache INNER JOIN 
(SELECT SUM(cible_indicateur_trimestre.cible) AS Total_cible
, ".$database_connect_prefix."cible_indicateur_trimestre.indicateur as indicateur
FROM ".$database_connect_prefix."cible_indicateur_trimestre
GROUP BY ".$database_connect_prefix."cible_indicateur_trimestre.indicateur)  AS Cible_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Cible_indicateur.indicateur) INNER JOIN 
(SELECT  SUM(suivi_indicateur_tache.valeur_suivi)  AS Total_suivi
,  ".$database_connect_prefix."suivi_indicateur_tache.indicateur as indicateur
FROM  ".$database_connect_prefix."suivi_indicateur_tache
GROUP BY  ".$database_connect_prefix."suivi_indicateur_tache.indicateur)  AS Valeur_Suivi_indicateur ON ".$database_connect_prefix."indicateur_tache.id_indicateur_tache = Valeur_Suivi_indicateur.indicateur
GROUP BY ".$database_connect_prefix."indicateur_tache.id_activite) as taux_ptba  ON ".$database_connect_prefix."ptba.id_ptba = taux_ptba.id_activite where ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' group by code";
  try{
    $liste_taux_ind_ptba = $pdar_connexion->prepare($query_liste_taux_ind_ptba);
    $liste_taux_ind_ptba->execute();
    $row_liste_taux_ind_ptba = $liste_taux_ind_ptba ->fetchAll();
    $totalRows_liste_taux_ind_ptba = $liste_taux_ind_ptba->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$taux_ind_ptba_niveau = array();
if($totalRows_liste_taux_ind_ptba>0){  foreach($row_liste_taux_ind_ptba as $row_liste_taux_ind_ptba){if($row_liste_taux_ind_ptba["taux_cp"]>0) $taux_ind_ptba_niveau[$row_liste_taux_ind_ptba["code"]]=100*$row_liste_taux_ind_ptba["taux_cp"];} } 

//print_r($realise_tache_niveau);
//exit;
//gestion revision
  //gestion revision
  $query_liste_mission = "SELECT * FROM ".$database_connect_prefix."version_ptba WHERE id_version_ptba='$annee'  ";
  try{
    $liste_mission = $pdar_connexion->prepare($query_liste_mission);
    $liste_mission->execute();
    $row_liste_mission = $liste_mission ->fetch();
    $totalRows_liste_mission = $liste_mission->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $lib_version_ptba=$row_liste_mission['annee_ptba']." ".$row_liste_mission['version_ptba'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">



<head>

<?php if(!isset($_GET["down"])){  ?>

  <title><?php print $config->sitename;?></title>



  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone;?>" />

<?php } ?>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php if(!isset($_GET["down"])){  ?>

  <meta name="keywords" content="<?php print $config->MetaKeys;?>" />



  <meta name="description" content="<?php print $config->MetaDesc;?>" />



  <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->



  <meta name="author" content="<?php print $config->MetaAuthor;?>" />



  <!--<meta charset="utf-8">-->



  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>







  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>



  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->



  <link href="<?php print $config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>



  <link href="<?php print $config->theme_folder;?>/plugins.css" rel="stylesheet" type="text/css"/>



  <link href="<?php print $config->theme_folder;?>/responsive.css" rel="stylesheet" type="text/css"/>



  <link href="<?php print $config->theme_folder;?>/icons.css" rel="stylesheet" type="text/css"/>



  <link href="<?php print $config->theme_folder;?>/login.css" rel="stylesheet" type="text/css"/>



  <link rel="stylesheet" href="<?php print $config->theme_folder;?>/fontawesome/font-awesome.min.css">



  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->
theme_folder;?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->



  <!--[if IE 8]><link href="<?php print $config->theme_folder;?>/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->



  <link href='<?php print $config->theme_folder;?>/css.css' rel='stylesheet' type='text/css'>



  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/jquery-1.10.2.min.js"></script>



  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>



  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>



  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/lodash.compat.min.js"></script>



  <!--[if lt IE 9]><script src="<?php print $config->script_folder;?>/libs/html5shiv.js"></script><![endif]-->



  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>



  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>



  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>



  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>



  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/breakpoints.js"></script>



  <script type="text/javascript" src="plugins/respond/respond.min.js"></script>



  <script type="text/javascript" src="plugins/cookie/jquery.cookie.min.js"></script>



  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.min.js"></script>



  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>
  <script type="text/javascript" src="plugins/blockui/jquery.blockUI.min.js"></script>
  <script type="text/javascript" src="plugins/pickadate/picker.js"></script>
  <script type="text/javascript" src="plugins/pickadate/picker.date.js"></script>
  <script type="text/javascript" src="plugins/pickadate/picker.time.js"></script>
  <script type="text/javascript" src="plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
  <script type="text/javascript" src="plugins/fullcalendar/fullcalendar.min.js"></script>
  <script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>
  <script type="text/javascript" src="plugins/noty/layouts/top.js"></script>
  <script type="text/javascript" src="plugins/noty/themes/default.js"></script>
  <script type="text/javascript" src="plugins/uniform/jquery.uniform.min.js"></script>
  <script type="text/javascript" src="plugins/select2/select2.min.js"></script>
  <script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>
  <script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder;?>/app.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder;?>/plugins.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder;?>/plugins.form-components.js"></script>
 <script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>



 <script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>



 <script type="text/javascript" src="<?php print $config->script_folder;?>/login.js"></script>



 <script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>



 <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/ui_general.js"></script>



 <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/form_validation.js"></script>

 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>



</head>
<?php } ?>


<body>



 <header class="header navbar navbar-fixed-top" role="banner">



<?php if(!isset($_GET["down"])) include_once ("includes/header.php");?>



 </header>



<div id="container">



    <div id="sidebar" class="sidebar-fixed">



        <div id="sidebar-content">



<?php if(!isset($_GET["down"])) include_once ("includes/menu_top.php");?>



        </div>



        <div id="divider" class="resizeable"></div>



    </div>







    <div id="content">



        <div class="container">



            <div class="crumbs">



<?php if(!isset($_GET["down"])) include_once ("includes/sous_menu.php");?>



            </div>



        <div class="page-header">



            <div class="p_top_5">



<!-- Site contenu ici -->

<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {

  border-spacing: 0px !important; border-collapse: collapse;

} .table tbody tr td {vertical-align: middle; }

</style>

<style>#sp_hr {margin:0px; }
.r_float{float: right;}
.Style11 { font-weight: bold;color: #FFFFFF;}
.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; } .marquer{background: #EBEBEB!important; }
</style>

<div class="contenu">
<?php if(!isset($_GET["down"])){ //echo $reqv; ?>
<div class="well well-sm r_float"><div class="r_float"><a href="./s_programmation.php" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a target="_blank" title="Imprimer" href="<?php echo $editFormAction."&down=1"; ?>" class="button"><img src="./images/print.png" width='20' height='20' alt='Modifier' /></a></div>
<!--<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
-->
</div>
<?php }  ?>
<div class="clear h0">&nbsp;</div>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Suivi budgetaire: PTBA  <strong><?php echo "$lib_version_ptba"; ?></strong>&nbsp;&nbsp;</h4>
<?php if(!isset($_GET["down"])){  
   // echo do_link("",$_SERVER['PHP_SELF']."?annee=".$annee,"PTBA","<i> PTBA</i>","","./","pull-right p11","",0,"",$nfile);

  if(isset($libelle[0]) && !empty($libelle[0])){ $i=count($libelle)-1; $libelle1 = array_reverse($libelle); foreach($libelle1 as $lib){
 if(isset($_GET['ugl']))  echo do_link("",$_SERVER['PHP_SELF']."?niveau=".$i."&annee=".$annee."&ugl=".$ugl,"$lib","<i> $lib </i>","","./","pull-right p11","",0,"",$nfile);
 else
  echo do_link("",$_SERVER['PHP_SELF']."?niveau=".$i."&annee=".$annee,"$lib","<i> $lib </i>","","./","pull-right p11","",0,"",$nfile);

  $i--; } 

  echo '<div class="clear h0"></div>'; }


}

?>

</div>



<div class="widget-content" style="display: block;">
  <span class="widget-content" style="display: block;">
  <?php if(!isset($niveau)){


 $n = count($libelle); ?>
  </span>
  <div class="clear h0">&nbsp;</div>

    </div>

<?php } else { //autre niveau

$where = ($niveau==0)?" niveau =1":" niveau = ".($niveau+1)." ";


if(isset($_GET['niveau']) ) 
$query_liste_activite_1 = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE $where and projet='".$_SESSION["clp_projet"]."' and code in (select left(code_activite_ptba,'".$nb_code[$niveau]."') from ".$database_connect_prefix."ptba where ".$database_connect_prefix."ptba.annee='$annee' and projet='".$_SESSION["clp_projet"]."') ORDER BY niveau,code ASC";
else
//$query_liste_activite_1 = "SELECT id_ptba as code, code_activite_ptba, intitule_activite_ptba as intitule, 0 as parent FROM  ".$database_connect_prefix."ptba where ".$database_connect_prefix."ptba.annee='$annee' and projet='".$_SESSION["clp_projet"]."' ORDER BY code_activite_ptba ASC";
$query_liste_activite_1 = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE $where and projet='".$_SESSION["clp_projet"]."' and code in (select left(code_activite_ptba,'".$nb_code[$max_niveau]."') from ".$database_connect_prefix."ptba where ".$database_connect_prefix."ptba.annee='$annee' and projet='".$_SESSION["clp_projet"]."') ORDER BY niveau,code ASC";

  try{
    $liste_activite_1 = $pdar_connexion->prepare($query_liste_activite_1);
    $liste_activite_1->execute();
    $row_liste_activite_1 = $liste_activite_1 ->fetchAll();
    $totalRows_liste_activite_1 = $liste_activite_1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//echo $query_liste_activite_1;
//exit;
?>

<form name="form1" action="" method="post">

<table id="example" border="1" align="center" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive table-checkable table-tabletools table-colvis datatable dataTable" >

<?php if(count($libelle)>0 && $niveau<count($libelle)){ ?>

                <thead>

                  <tr>
<?php if(!isset($_GET["down"])){  ?>
                    <th class="checkbox-column"> <input type="checkbox" class="uniform"> </th>
<?php } ?>
                     <?php if(isset($niveau) && $niveau>0) { ?>

                    <td ><?php echo $libelle[$niveau-1]; ?></td>

                    <?php } ?>

                    <td >Code  <?php /*if(isset($_GET['niveau']) )*/ echo $libelle[$niveau]; ?></td>

                    <td ><?php /*if(isset($_GET['niveau']) )*/ echo $libelle[$niveau]; /*else echo "Activité";*/ ?></td>

                   
                    <td  align="center"><strong><span class="Style8">T&acirc;ches</span></strong></td>
                    <td  align="center"><strong>Indicateurs</strong></td>
                    <!--<td  align="center"><strong><span class="Style8">Indicateurs</span></strong></td>-->
				   <td  align="center"><strong>% D&eacute;caiss&eacute;</strong></td>
				  
				   <td  align="center"><strong><span class="Style8">% avec engagement </span></strong></td>
                  </tr>
                </thead>

                <tbody>

<?php if($totalRows_liste_activite_1>0){ $totalp=$totald=$totale=$taux_cumul=$taux_cumulind=0; foreach($row_liste_activite_1 as $row_liste_activite_1){ $code = $row_liste_activite_1["code"]; $parent = $row_liste_activite_1["parent"]; ?>

                <tr>
<?php if(!isset($_GET["down"])){  ?>
                    <td class="checkbox-column"> <input type="checkbox" name="id_val[]" value="<?php echo $code; ?>" class="uniform"> </td>
<?php }  ?>
                    <?php if(isset($niveau)  && $niveau>0 ) { ?>

                    <td><?php echo $row_liste_activite_1["parent"]; ?></td>

                    <?php } ?>

                    <td><?php /*if(isset($_GET['niveau']) )*/ echo $row_liste_activite_1["code"]; /*else echo $row_liste_activite_1["code_activite_ptba"];*/ ?></td>

                    <td><?php echo $row_liste_activite_1["intitule"]; ?></td>

                   
                    <td align="center" nowrap="nowrap"><div align="right"><strong><span class="Style8">
                      <?php if(!isset($_GET['niveau']) && isset($realise_tache_niveau[$row_liste_activite_1["code"]])) {echo number_format($realise_tache_niveau[$row_liste_activite_1["code"]], 0, ',', ' ')." %"; $taux_cumul=$taux_cumul+$realise_tache_niveau[$row_liste_activite_1["code"]];} elseif(isset($realise_tache_niveau[$row_liste_activite_1["code"]]) && isset($activitep_array[$row_liste_activite_1["code"]]) && $activitep_array[$row_liste_activite_1["code"]]>0) {echo number_format($realise_tache_niveau[$row_liste_activite_1["code"]]/$activitep_array[$row_liste_activite_1["code"]], 0, ',', ' ')." %"; $taux_cumul=$taux_cumul+($realise_tache_niveau[$row_liste_activite_1["code"]]);} ?>
                    </span></strong></div></td>
                   
                    <!----> <td  align="center" nowrap="nowrap"><div align="right"><strong><span class="Style8">
                      <?php if(!isset($_GET['niveau']) && isset($taux_ind_ptba_niveau[$row_liste_activite_1["code"]])) {echo number_format($taux_ind_ptba_niveau[$row_liste_activite_1["code"]], 0, ',', ' ')." %"; $taux_cumulind=$taux_cumulind+$taux_ind_ptba_niveau[$row_liste_activite_1["code"]];} elseif(isset($taux_ind_ptba_niveau[$row_liste_activite_1["code"]]) && isset($activitep_array[$row_liste_activite_1["code"]]) && $activitep_array[$row_liste_activite_1["code"]]>0) {echo number_format($taux_ind_ptba_niveau[$row_liste_activite_1["code"]]/$activitep_array[$row_liste_activite_1["code"]], 0, ',', ' ')." %"; $taux_cumulind=$taux_cumulind+$taux_ind_ptba_niveau[$row_liste_activite_1["code"]];} ?>
                    </span></strong></div></td>
				   <td  align="center" nowrap="nowrap"><strong>
				     <?php  if(isset($prevu_arrayas[$row_liste_activite_1["code"]]) && isset($realise_arrayas[$row_liste_activite_1["code"]]) && $prevu_arrayas[$row_liste_activite_1["code"]]>0) {echo number_format(100*$realise_arrayas[$row_liste_activite_1["code"]]/$prevu_arrayas[$row_liste_activite_1["code"]], 2, ',', ' ')." %";}
					 
					 if(isset($prevu_arrayas[$row_liste_activite_1["code"]])) $totalp=$totalp+$prevu_arrayas[$row_liste_activite_1["code"]];
					  if(isset($realise_arrayas[$row_liste_activite_1["code"]])) $totald=$totald+$realise_arrayas[$row_liste_activite_1["code"]];
					  
					  if(isset($total_realise_array[$row_liste_activite_1["code"]])) $totale=$totale+$total_realise_array[$row_liste_activite_1["code"]];
					  
					   ?>
				   </strong></td>
				 
				   <td  align="center" nowrap="nowrap"><strong>
				     
				     <?php  if(isset($prevu_arrayas[$row_liste_activite_1["code"]]) && isset($realise_arrayae[$row_liste_activite_1["code"]]) && $prevu_arrayas[$row_liste_activite_1["code"]]>0 && $realise_arrayae[$row_liste_activite_1["code"]]>0) echo number_format(100*$realise_arrayae[$row_liste_activite_1["code"]]/$prevu_arrayas[$row_liste_activite_1["code"]], 2, ',', ' ')." %"; elseif(isset($realise_arrayas[$row_liste_activite_1["code"]]) && $realise_arrayas[$row_liste_activite_1["code"]]>0) {?><span class="btn-danger">&nbsp;&nbsp;!!!&nbsp;&nbsp;</span><?php } ?>
			        </strong></td>
                </tr>
               
<?php } ?>
 <tr style="background-color:#CCCCCC">
 <?php if(!isset($_GET["down"])){  ?>
                    <td class="checkbox-column">&nbsp;  </td>
<?php }  ?>
                  <td colspan="<?php if(isset($_GET['niveau']) && $_GET['niveau']>0) echo 3; else echo 2; ?>" class="checkbox-column"><div align="right"><strong>Total PTBA <?php echo "$lib_version_ptba"; ?></strong></div></td>
                  <td align="center" nowrap="nowrap"><div align="right"><strong>
                    <?php  if(isset($ttact) && $ttact>0) echo number_format($taux_cumul/$ttact, 2, ',', ' ')." %"; ?>
                  </strong></div></td>
                  <!----><td  align="center" nowrap="nowrap"><div align="right"><strong>
                    <?php  if(isset($ttact) && $ttact>0) echo number_format($taux_cumulind/$ttact, 2, ',', ' ')." %"; ?>
                  </strong></div></td>
                  <td  align="center" nowrap="nowrap"><strong>
                    <?php  if(isset($totalp) && isset($totald) && $totalp>0) echo number_format(100*$totald/$totalp, 2, ',', ' ')." %"; ?>
                  </strong></td>
                 
                
                  <td  align="center" nowrap="nowrap"><strong>
                    <?php  if(isset($totalp) && isset($totale) && $totalp>0) echo number_format(100*($totale)/$totalp, 2, ',', ' ')." %"; ?>
                  </strong></td>
 </tr>
                </tbody>

<?php } } else { ?>

                <tr>

                  <td><div align="center" class="">
                    <h2>Aucune activit&eacute;</h2>
                  </div></td>
                </tr>

                <?php } ?>
            </table>


</form>

<?php } ?>



<!-- Fin Site contenu ici -->

        </div>

            </div>



        </div>



 </div>



        </div>



    </div>  <?php if(!isset($_GET["down"])) include_once 'modal_add.php'; ?>



<?php if(!isset($_GET["down"])) include_once ("includes/footer.php");?>



</div>



</body>



</html>