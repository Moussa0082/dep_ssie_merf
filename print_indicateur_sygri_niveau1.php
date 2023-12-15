<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";

 if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Suivi_SYGRI_1er_Niveau.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Suivi_SYGRI_1er_Niveau.rtf"); } 

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//annee en cours
  if(isset($_GET['annee'])) $annee=$_GET['annee']; else $annee=date("Y");
//annee precedent
 $anneep=$annee-1;
//  

// composante
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SELECT * FROM activite_projet WHERE niveau=1";
$liste_cp  = mysql_query($query_liste_cp , $pdar_connexion) or die(mysql_error());
$row_liste_cp  = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp  = mysql_num_rows($liste_cp);


$annee_courant=$annee;

//annee
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_anneeos1 = "SELECT * FROM annee where annee<='$annee_courant'";
$liste_anneeos1 = mysql_query($query_liste_anneeos1, $pdar_connexion) or die(mysql_error());
$row_liste_anneeos1 = mysql_fetch_assoc($liste_anneeos1);
$totalRows_liste_anneeos1 = mysql_num_rows($liste_anneeos1); */

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_annee = "SELECT distinct annee FROM ".$database_connect_prefix."ptba order by annee desc";
$liste_annee = mysql_query($query_liste_annee, $pdar_connexion) or die(mysql_error());
	$tableauAnnee=array();
	while($ligne=mysql_fetch_assoc($liste_annee)){$tableauAnnee[]=$ligne['annee'];}
	mysql_free_result($liste_annee);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_composante = "SELECT * FROM activite_projet WHERE ".$_SESSION["clp_where"]." and niveau=1";
$composante  = mysql_query($query_composante , $pdar_connexion) or die(mysql_error());
$row_composante  = mysql_fetch_assoc($composante);
$totalRows_composante  = mysql_num_rows($composante);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_indicateur_sygri = "SELECT * FROM  beneficiaire_sygri ORDER BY ordre";
$liste_indicateur_sygri  = mysql_query($query_liste_indicateur_sygri , $pdar_connexion) or die(mysql_error());
$row_liste_indicateur_sygri = mysql_fetch_assoc($liste_indicateur_sygri );
$totalRows_liste_indicateur_sygri = mysql_num_rows($liste_indicateur_sygri );

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_composante = "SELECT * FROM activite_projet WHERE ".$_SESSION["clp_where"]." and niveau=1";
$liste_composante  = mysql_query($query_liste_composante , $pdar_connexion) or die(mysql_error());
$row_liste_composante = mysql_fetch_assoc($liste_composante);
$totalRows_liste_composante  = mysql_num_rows($liste_composante);
$liste_composante_array = array();
do{  $liste_composante_array[$row_liste_composante["code"]] = $row_liste_composante["intitule"];
}while($row_liste_composante = mysql_fetch_assoc($liste_composante));

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind, mode_calcul, mode_suivi  FROM referentiel_indicateur";
$liste_ind_ref  = mysql_query($query_liste_ind_ref , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref);
$totalRows_liste_ind_ref  = mysql_num_rows($liste_ind_ref);
$liste_ind_ref_array = array();
$unite_ind_ref_array = array();
$mode_calcul_ind_ref_array = array();
$mode_suivi_ind_ref_array = array();
do{  $liste_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]] = $row_liste_ind_ref["intitule_ref_ind"];
 $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];
 $mode_calcul_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["mode_calcul"];
  $mode_suivi_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["mode_suivi"];
}while($row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref));
/*
//Les valeurs Cibles
//cible unique
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cible_ind_ref = " SELECT ptba.annee, referentiel, sum(cible) as valeur_cible FROM   cible_indicateur_trimestre, indicateur_tache, ptba  where id_indicateur_tache=cible_indicateur_trimestre.indicateur and id_ptba=indicateur_tache.activite  and cible_indicateur_trimestre.region in(select id_region from region) group by ptba.annee, referentiel ";
$cible_ind_ref  = mysql_query($query_cible_ind_ref , $pdar_connexion) or die(mysql_error());
$row_cible_ind_ref = mysql_fetch_assoc($cible_ind_ref);
$totalRows_cible_ind_ref  = mysql_num_rows($cible_ind_ref);
$cible_ind_ref_array = array();
do{  $cible_ind_ref_array[$row_cible_ind_ref["referentiel"]][$row_cible_ind_ref["annee"]] = $row_cible_ind_ref["valeur_cible"];
}while($row_cible_ind_ref = mysql_fetch_assoc($cible_ind_ref));

//cible somme
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_somme_ind_ref = " SELECT ptba.annee, indicateur_ref, sum(cible) as valeur_cible FROM   cible_indicateur_trimestre, indicateur_tache, ptba, referentiel_indicateur, calcul_indicateur_simple_ref  where id_indicateur_tache=cible_indicateur_trimestre.indicateur and id_ptba=indicateur_tache.activite and id_ref_ind=referentiel
 and  FIND_IN_SET( id_ref_ind, indicateur_simple ) AND mode_calcul =  'Unique' and cible_indicateur_trimestre.region in(select id_region from region) group by ptba.annee, indicateur_ref ";
$somme_ind_ref  = mysql_query($query_somme_ind_ref , $pdar_connexion) or die(mysql_error());
$row_somme_ind_ref = mysql_fetch_assoc($somme_ind_ref);

$totalRows_somme_ind_ref  = mysql_num_rows($somme_ind_ref);
$somme_ind_ref_array = array();
do{  $somme_ind_ref_array[$row_somme_ind_ref["indicateur_ref"]][$row_somme_ind_ref["annee"]] = $row_somme_ind_ref["valeur_cible"];
}while($row_somme_ind_ref = mysql_fetch_assoc($somme_ind_ref));

//cible moyenne
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_moyenne_ind_ref = " SELECT annee, indicateur_ref ,  avg(valeur_cible) as valeur_cible
					FROM (SELECT annee, referentiel, id_indicateur_tache, indicateur_ref, sum(cible) as valeur_cible FROM   cible_indicateur_trimestre, indicateur_tache, ptba, referentiel_indicateur, calcul_indicateur_simple_ref  where id_indicateur_tache=cible_indicateur_trimestre.indicateur and id_ptba=indicateur_tache.activite and id_ref_ind=referentiel  and  FIND_IN_SET( id_ref_ind, indicateur_simple ) AND mode_calcul =  'Unique' and cible_indicateur_trimestre.region in(select id_region from region ) group by annee, id_indicateur_tache, indicateur_ref, referentiel)  AS alias_sr group by annee, indicateur_ref ";
$moyenne_ind_ref  = mysql_query($query_moyenne_ind_ref , $pdar_connexion) or die(mysql_error());
$row_moyenne_ind_ref = mysql_fetch_assoc($moyenne_ind_ref);
$totalRows_moyenne_ind_ref  = mysql_num_rows($moyenne_ind_ref);
$moyenne_ind_ref_array = array();
do{  $moyenne_ind_ref_array[$row_moyenne_ind_ref["indicateur_ref"]][$row_moyenne_ind_ref["annee"]] = $row_moyenne_ind_ref["valeur_cible"];
}while($row_moyenne_ind_ref = mysql_fetch_assoc($moyenne_ind_ref)); */
/*
// Réalisation du PTBA
//Suivi unique
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_suivi_ind_ref = " SELECT ptba.annee, referentiel, sum(valeur_suivi) as valeur_reelle FROM   suivi_indicateur_tache, indicateur_tache, ptba  where id_ptba=indicateur_tache.activite and id_indicateur_tache=suivi_indicateur_tache.indicateur  and commune in(select id_commune from commune) group by ptba.annee, referentiel ";
$suivi_ind_ref  = mysql_query($query_suivi_ind_ref , $pdar_connexion) or die(mysql_error());
$row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref);
$totalRows_suivi_ind_ref  = mysql_num_rows($suivi_ind_ref);
$suivi_ind_ref_array = array();
do{  $suivi_ind_ref_array[$row_suivi_ind_ref["referentiel"]][$row_suivi_ind_ref["annee"]] = $row_suivi_ind_ref["valeur_reelle"];
}while($row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref));

//Suivi des sommes
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_suivi_somme_ind_ref = " SELECT ptba.annee, indicateur_ref, sum(valeur_suivi) as valeur_reelle FROM   suivi_indicateur_tache, indicateur_tache, ptba, calcul_indicateur_simple_ref, referentiel_indicateur where id_ptba=indicateur_tache.activite and id_indicateur_tache=suivi_indicateur_tache.indicateur  and id_ref_ind = referentiel and  FIND_IN_SET(id_ref_ind, indicateur_simple ) and commune in(select id_commune from commune) group by ptba.annee, indicateur_ref ";
$suivi_somme_ind_ref  = mysql_query($query_suivi_somme_ind_ref , $pdar_connexion) or die(mysql_error());
$row_suivi_somme_ind_ref = mysql_fetch_assoc($suivi_somme_ind_ref);
$totalRows_suivi_somme_ind_ref  = mysql_num_rows($suivi_somme_ind_ref);
$suivi_somme_ind_ref_array = array();
do{  $suivi_somme_ind_ref_array[$row_suivi_somme_ind_ref["indicateur_ref"]][$row_suivi_somme_ind_ref["annee"]] = $row_suivi_somme_ind_ref["valeur_reelle"];
}while($row_suivi_somme_ind_ref = mysql_fetch_assoc($suivi_somme_ind_ref));

//suivi des moyennes
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_suivi_moyenne_ind_ref = " SELECT ptba.annee, indicateur_ref ,  avg(valeur_reelle) as valeur_reelle
					FROM (SELECT ptba.annee, indicateur_ref, id_indicateur_tache, sum(valeur_suivi) as valeur_reelle FROM   suivi_indicateur_tache, indicateur_tache, ptba, calcul_indicateur_simple_ref, referentiel_indicateur  where  id_ref_ind=referentiel and id_ptba=indicateur_tache.activite and id_indicateur_tache=suivi_indicateur_tache.indicateur  and  FIND_IN_SET( id_ref_ind, indicateur_simple ) and commune in(select id_commune from commune) group by region, id_indicateur_tache, indicateur_ref)  AS alias_sr group by ptba.annee, indicateur_ref ";
$suivi_moyenne_ind_ref  = mysql_query($query_suivi_moyenne_ind_ref , $pdar_connexion) or die(mysql_error());
$row_suivi_moyenne_ind_ref = mysql_fetch_assoc($suivi_moyenne_ind_ref);
$totalRows_suivi_moyenne_ind_ref  = mysql_num_rows($suivi_moyenne_ind_ref);
$suivi_moyenne_ind_ref_array = array();
do{  $suivi_moyenne_ind_ref_array[$row_suivi_moyenne_ind_ref["indicateur_ref"]][$row_suivi_moyenne_ind_ref["annee"]] = $row_suivi_moyenne_ind_ref["valeur_reelle"];
}while($row_suivi_moyenne_ind_ref = mysql_fetch_assoc($suivi_moyenne_ind_ref));

///Les ratios!
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ratio = "SELECT indicateur_ref, numerateur, denominateur FROM ratio_indicateur_ref order by indicateur_ref";
$liste_ind_ratio  = mysql_query($query_liste_ind_ratio , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ratio = mysql_fetch_assoc($liste_ind_ratio);
$totalRows_liste_ind_ratio  = mysql_num_rows($liste_ind_ratio);
$liste_num_ratio_array = array();
$liste_deno_ratio_array = array();
do{
 $liste_num_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = $row_liste_ind_ratio["numerateur"];
  $liste_deno_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = $row_liste_ind_ratio["denominateur"];
}while($row_liste_ind_ratio = mysql_fetch_assoc($liste_ind_ratio));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$query_liste_realise_cmr = "SELECT indicateur_sygri, sum(valeur_realise) as valeur_realise, annee FROM realise_cmr_sygri, region where id_region=zone group by indicateur_sygri, annee";
	$liste_realise_cmr = mysql_query($query_liste_realise_cmr, $pdar_connexion) or die(mysql_error());
	$row_liste_realise_cmr = mysql_fetch_assoc($liste_realise_cmr);
	$totalRows_liste_realise_cmr = mysql_num_rows($liste_realise_cmr);
	$realise_cmr_array = array();
    if($totalRows_liste_realise_cmr>0){ 
	 do{ 
	 $realise_cmr_array[$row_liste_realise_cmr["annee"]][$row_liste_realise_cmr["indicateur_sygri"]]=$row_liste_realise_cmr["valeur_realise"]; 
	 }
	while($row_liste_realise_cmr  = mysql_fetch_assoc($liste_realise_cmr));}     */

//cible unique
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cible_ind_ref = " SELECT annee, referentiel, sum(valeur_cible) as valeur_cible FROM   cible_cmr_produit, indicateur_produit_cmr, referentiel_indicateur  where id_indicateur=indicateur_produit and referentiel=id_ref_ind   group by annee, referentiel ";
$cible_ind_ref  = mysql_query($query_cible_ind_ref , $pdar_connexion) or die(mysql_error());
$row_cible_ind_ref = mysql_fetch_assoc($cible_ind_ref);
$totalRows_cible_ind_ref  = mysql_num_rows($cible_ind_ref);
$cible_ind_ref_array = array();
do{  $cible_ind_ref_array[$row_cible_ind_ref["referentiel"]][$row_cible_ind_ref["annee"]] = $row_cible_ind_ref["valeur_cible"];
}while($row_cible_ind_ref = mysql_fetch_assoc($cible_ind_ref));

//Suivi unique
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_suivi_ind_ref = " SELECT referentiel_indicateur.id_ref_ind,referentiel_fiche_config.* FROM  referentiel_indicateur,referentiel_fiche_config  where id_ref_ind=referentiel and feuille<>'' ";
$suivi_ind_ref  = mysql_query($query_suivi_ind_ref , $pdar_connexion) or die(mysql_error());
$row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref);
$totalRows_suivi_ind_ref  = mysql_num_rows($suivi_ind_ref);
$suivi_ind_ref_array = array();
do{ $feuille=$row_suivi_ind_ref["feuille"]; $col=$row_suivi_ind_ref["colonne"];
//sur les feuilles
mysql_select_db($database_pdar_connexion, $pdar_connexion);
if($row_suivi_ind_ref["mode_calcul"]=="COMPTER")
$query_feuille = " SELECT COUNT($col) as nb, annee FROM  $feuille WHERE projet='".$_SESSION["clp_projet"]."' ";
elseif($row_suivi_ind_ref["mode_calcul"]=="MOYENNE")
$query_feuille = " SELECT AVG($col) as nb, annee FROM  $feuille WHERE projet='".$_SESSION["clp_projet"]."' ";
else
$query_feuille = " SELECT SUM($col) as nb, annee FROM  $feuille WHERE projet='".$_SESSION["clp_projet"]."' ";
$feuilles  = mysql_query($query_feuille , $pdar_connexion);
if(($feuilles)) {
$row_feuille = mysql_fetch_assoc($feuilles);
$totalRows_feuille  = mysql_num_rows($feuilles);
}
$feuille_array = array();
if(isset($totalRows_feuille) && $totalRows_feuille>0) {
do{
   $suivi_ind_ref_array[$row_suivi_ind_ref["id_ref_ind"]] = $row_feuille["nb"];
}while($row_feuille = mysql_fetch_assoc($feuilles));
}
}while($row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref));

/*
//Suivi Somme Moyenne
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_suivi_ind_ref = " SELECT * FROM  referentiel_indicateur, indicateur_sygri1_projet, calcul_indicateur_simple_ref  where id_ref_ind=referentiel and indicateur_ref=id_ref_ind and mode_calcul<>'Ratio'";
$suivi_ind_ref  = mysql_query($query_suivi_ind_ref , $pdar_connexion) or die(mysql_error());
$row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref);
$totalRows_suivi_ind_ref  = mysql_num_rows($suivi_ind_ref);

$suivi_somme_ind_ref_array = array();

do{ $ref=$row_suivi_ind_ref["indicateur_ref"]; $ind=explode(",",$row_suivi_ind_ref["indicateur_simple"]); $formule=$row_suivi_ind_ref["formule_indicateur_simple"];
if($formule=="Somme"){
 foreach($ind as $indicateur){
   if(isset($suivi_ind_ref_array[$indicateur])){
      foreach($suivi_ind_ref_array[$indicateur] as $indicateur_referentiel=>$nb){
        if(isset($suivi_somme_ind_ref_array[$ref][$indicateur_referentiel])) $suivi_somme_ind_ref_array[$ref][$indicateur_referentiel]+=$nb;
        else $suivi_somme_ind_ref_array[$ref][$indicateur_referentiel]=$nb;

        //cible
        if(isset($cible_ind_ref_array[$indicateur][$indicateur_referentiel])){
         if(isset($cible_ind_ref_array[$ref][$indicateur_referentiel])) $cible_ind_ref_array[$ref][$indicateur_referentiel]+=$cible_ind_ref_array[$indicateur][$indicateur_referentiel];
         else $cible_ind_ref_array[$ref][$indicateur_referentiel]=$cible_ind_ref_array[$indicateur][$indicateur_referentiel];
        }


       }
   }
 }         }

if($formule=="Moyenne"){
 foreach($ind as $indicateur){
   if(isset($suivi_ind_ref_array[$indicateur])){
      foreach($suivi_ind_ref_array[$indicateur] as $indicateur_referentiel=>$nb){
        if(isset($suivi_moyenne_ind_ref_array[$ref][$indicateur_referentiel])) $suivi_moyenne_ind_ref_array[$ref][$indicateur_referentiel]+=$nb;
        else $suivi_moyenne_ind_ref_array[$ref][$indicateur_referentiel]=$nb;

        //cible
        /*if(isset($cible_ind_ref_array[$indicateur][$indicateur_referentiel])){
         if(isset($cible_ind_ref_array[$ref][$indicateur_referentiel])) $cible_ind_ref_array[$ref][$indicateur_referentiel]+=$cible_ind_ref_array[$indicateur][$indicateur_referentiel];
         else $cible_ind_ref_array[$ref][$indicateur_referentiel]=$cible_ind_ref_array[$indicateur][$indicateur_referentiel];
        }   

       }
   }
 }

if(isset($suivi_ind_ref_array[$indicateur])){
foreach($suivi_ind_ref_array[$indicateur] as $indicateur_referentiel=>$nb){
      if((count($ind)-1)>0) $suivi_moyenne_ind_ref_array[$ref][$indicateur_referentiel]=$suivi_moyenne_ind_ref_array[$ref][$indicateur_referentiel]/(count($ind)-1); }
       }      }

}while($row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref)); //print_r($suivi_moyenne_ind_ref_array); exit;


//Suivi Ratio
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_suivi_ind_ref = " SELECT * FROM  referentiel_indicateur, indicateur_sygri1_projet, ratio_indicateur_ref  where id_ref_ind=referentiel and indicateur_ref=id_ref_ind and mode_calcul='Ratio'";
$suivi_ind_ref  = mysql_query($query_suivi_ind_ref , $pdar_connexion) or die(mysql_error());
$row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref);
$totalRows_suivi_ind_ref  = mysql_num_rows($suivi_ind_ref);

$liste_num_ratio_array = array();
$liste_deno_ratio_array = array();

do{
$ref=$row_suivi_ind_ref["indicateur_ref"]; $numerateur=$row_suivi_ind_ref["numerateur"]; $denominateur=$row_suivi_ind_ref["denominateur"]; $coef=$row_suivi_ind_ref["coefficient"];

$liste_num_ratio_array[$ref]=$numerateur;
$liste_deno_ratio_array[$ref]=$denominateur;

}while($row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref)); //print_r($cible_ind_ref_array); exit;
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php if(!isset($_GET["down"])){  ?>
<head>
  <title><?php print $config->sitename; ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="keywords" content="<?php print $config->MetaKeys; ?>" />
  <meta name="description" content="<?php print $config->MetaDesc; ?>" />
  <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
  <meta name="author" content="<?php print $config->MetaAuthor; ?>" />
  <!--<meta charset="utf-8">-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->
  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/login.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->
  <!--[if IE 8]><link href="<?php print $config->theme_folder; ?>/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->
  <link href='<?php print $config->theme_folder; ?>/css.css' rel='stylesheet' type='text/css'>
  <!--<link rel="stylesheet" href="<?php print $config->theme_folder; ?>/table.css" type="text/css" > -->
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/lodash.compat.min.js"></script>
  <!--[if lt IE 9]><script src="<?php print $config->script_folder; ?>/libs/html5shiv.js"></script><![endif]-->
  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>
  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/breakpoints.js"></script>
  <script type="text/javascript" src="plugins/respond/respond.min.js"></script>
  <script type="text/javascript" src="plugins/cookie/jquery.cookie.min.js"></script>
  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.min.js"></script>
  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>
  <!--[if lt IE 9]><script type="text/javascript" src="plugins/flot/excanvas.min.js"></script><![endif]-->
  <!--<script type="text/javascript" src="plugins/sparkline/jquery.sparkline.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.tooltip.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.resize.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.time.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.growraf.min.js"></script>
  <script type="text/javascript" src="plugins/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
  <script type="text/javascript" src="plugins/daterangepicker/moment.min.js"></script>
  <script type="text/javascript" src="plugins/daterangepicker/daterangepicker.js"></script>-->
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
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/app.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/plugins.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/plugins.form-components.js"></script>
<!--
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/custom.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/pages_calendar.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_filled_blue.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_simple.js"></script>-->
 <script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
 <script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/login.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/myscript.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/ui_general.js"></script>
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>
</head>
<?php }  ?>
<body>
 <header class="header navbar navbar-fixed-top" role="banner">
    <?php if(!isset($_GET["down"])) include_once("includes/header.php"); ?>
 </header>
<div id="container">
    <div id="sidebar" class="sidebar-fixed">
        <div id="sidebar-content">
            <?php if(!isset($_GET["down"])) include_once("includes/menu_top.php"); ?>
        </div>
        <div id="divider" class="resizeable"></div>
    </div>

    <div id="content">
        <div class="container">
            <div class="crumbs">
                <?php if(!isset($_GET["down"])) include_once("includes/sous_menu.php"); ?>
            </div>
        <div class="page-header">
            <div class="p_top_5">
<!-- Site contenu ici -->
<style>#sp_hr {margin:0px; }
.r_float{float: right;}
.Style23{color: white;}

</style>
<div class="contenu">
  <div id="msg" align="center" class="red"></div>
<?php if(!isset($_GET["down"])){  ?>
  <div class="l_float"><?php //include("content/annee_ptba.php"); ?></div>
  <div class="r_float"><a href="s_sygri.php?annee=<?php echo $annee; ?>" class="button">Retour</a></div>
  <!--<div class="r_float" style="margin-right: 10px;"><a href="<?php echo $editFormAction."&down=1&t=p"; ?>" class="button"><img src="./images/pdf.jpg" width='20' height='20' alt='Modifier' /></a></div>-->
    <div class="r_float" style="margin-right: 10px;"><a href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
  <div class="r_float" style="margin-right: 10px;"><a href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<br />
<?php } ?>
<h4 align="center">Indicateurs de 1<sup>er</sup> niveau SYGRI en <?php echo $annee;?></h4>


<table width="96%" border="1" align="center" cellspacing="0" bordercolor="#000">

  <tr bgcolor="#CCCCCC">
    <td nowrap="nowrap" colspan="4"><strong>Indicateurs par cat&eacute;gorie </strong></td>
  </tr>
  <?php 
				   // $id_cp=$row_composante['id_composante'];
				    mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_ind = "SELECT 0 as id, '' as code, ' ' as intitule, code_groupe, nom_groupe, id_indicateur_sygri_niveau1_projet, intitule_indicateur_sygri_fida, referentiel	, cible_projet, cible_rmp, indicateur_sygri1_projet.ordre, id_sygri  FROM groupe_indicateur, liste_indicateur_sygri, indicateur_sygri1_projet where id_groupe=groupe_indicateur and id_sygri=id_indicateur_sygri_fida and code_groupe='08' and indicateur_sygri1_projet.projet='".$_SESSION["clp_projet"]."'
					union
					SELECT id, code, intitule, code_groupe, nom_groupe, id_indicateur_sygri_niveau1_projet, intitule_indicateur_sygri_fida, referentiel	, cible_projet, cible_rmp, indicateur_sygri1_projet.ordre, id_sygri  FROM activite_projet, groupe_indicateur, liste_indicateur_sygri, indicateur_sygri1_projet WHERE niveau=2 and id_groupe=groupe_indicateur and id_sygri=id_indicateur_sygri_fida and code_groupe!='08' and scomposante=code and indicateur_sygri1_projet.projet='".$_SESSION["clp_projet"]."' order by code, code_groupe, ordre";
					$ind  = mysql_query($query_ind , $pdar_connexion) or die(mysql_error());
					$row_ind  = mysql_fetch_assoc($ind);
					$totalRows_ind  = mysql_num_rows($ind);				  
				  ?>
  <!-- <tr <?php if($o2%2==0) echo 'bgcolor="#FFF"'; $o2=$o2+1;?>>
                           <td colspan="4" align="left" valign="middle"><span class="Style51"><?php echo $row_composante['code_composante'].": ".$row_composante['intitule_composante']; ?></span></td>
                         </tr>-->
  <tr >
    <td valign="top" colspan="4"><table width="100%" border="1" align="left" cellspacing="0">
      <tr class="titrecorps2">
        <td width="70%" rowspan="2" align="center">R&eacute;sultats </td>
        <td width="10%" rowspan="2" align="center">&nbsp;Unit&eacute;&nbsp;</td>
        <?php foreach($tableauAnnee as $anp){?>
        <?php if($anp==$annee){ ?>
        <td colspan="3"><div align="center">Fin p&eacute;riode <?php echo $anp; ?></div></td>
        <?php }else{ ?>
        <td colspan="3"><div align="center"><?php echo $anp; ?></div></td>
        <?php } ?>
        <?php } ?>
        <td width="2%" rowspan="2" align="center" bgcolor="#000000">&nbsp;</td>
        <td width="10%" colspan="3" align="center" bgcolor="#999999"><strong>Cumulatif</strong> </td>
      </tr>
      <tr class="titrecorps2">
        <?php foreach($tableauAnnee as $anp){?>
        <?php if($anp==$annee){ ?>
        <td><span class="Style22">&nbsp;PTBA</span></td>
        <td><span class="Style22">&nbsp;R&eacute;alis&eacute;</span></td>
        <td><span class="Style22">&nbsp;<span class="Style19">%PTBA</span></span></td>
        <?php }else{ ?>
        <td colspan="3"><span class="Style22">&nbsp;R&eacute;alis&eacute;</span></td>
        <?php } } ?>
        <td width="10%" align="center" bgcolor="#999999"><strong>Objectifs DCP</strong> </td>
        <td width="10%" align="center" bgcolor="#999999"><strong>R&eacute;alis&eacute;</strong> </td>
        <td width="10%" align="center" bgcolor="#999999"><span class="Style22">&nbsp;<span class="Style19">%DCP</span></span></td>
      </tr>
      <?php if($totalRows_ind>0) {$i=0; $p1="j"; ?>
      <?php  $p11="j"; $p111="j"; $k=1; do {  $tot_cib=$tot_real=0; $val_cib=$val_real=0; $cible_deno=$cible_denos=1; $cible_nums=$cible_num=0;?>
      <?php  if($p111!=$row_ind['code'] && $row_ind['id']>0) {?>
      <!--<tr <?php  echo 'bgcolor="#CCCCCC"'; ?>>
        <td align="center" colspan="23"><div align="left">
          <?php  if($p111!=substr($row_ind['code'],0,1)) {echo "Composante ".$row_ind['code'].": ".$row_ind['intitule']; }$p111=$row_ind['code']; ?>
        </div></td>
      </tr>-->
      <?php } ?>
      <?php  if($p11!=$row_ind['code'] && $row_ind['id']>0) {?>
      <tr <?php  echo 'bgcolor="#D2E2B1"'; ?>>
        <td align="center" colspan="23"><div align="left">
          <?php  if($p11!=$row_ind['code']) {echo "<b>Sous composante ".$row_ind['code'].":</b> ".$row_ind['intitule']; }$p11=$row_ind['code']; ?>
        </div></td>
      </tr>
      <?php } ?>
      <?php  if($p1!=$row_ind['code_groupe'] && $k==1 && $row_ind['code_groupe']==8) { $k++; ?>
      <tr <?php  echo 'bgcolor="#CCCCCC"'; ?>>
        <td align="center" colspan="23"><div align="left">
          <?php  if($p1!=$row_ind['code_groupe']) {echo "<b>".$row_ind['nom_groupe']."</b>"; $i=0; }$p1=$row_ind['code_groupe']; ?>
        </div></td>
      </tr>
      <?php } ?>
      <tr <?php if($i%2==0) echo 'bgcolor="#F9F9F7"'; $i=$i+1;?>>
        <td width="70%"><div align="left" class="Style51"><?php echo $row_ind['intitule_indicateur_sygri_fida']; ?></div>
              <div align="left" class="Style51"> </div></td>
        <td width="10%"><div align="center">
          <?php  if(isset($unite_ind_ref_array[$row_ind['referentiel']])) echo " (".$unite_ind_ref_array[$row_ind['referentiel']].")"; ?>
        </div></td>
        <?php
							
					   foreach($tableauAnnee as $anp){ ?>
<?php if($anp==$annee){ ?>
        <td nowrap="nowrap"><div align="center">
          <?php  if(isset($mode_calcul_ind_ref_array[$row_ind["referentiel"]]) &&  $mode_calcul_ind_ref_array[$row_ind["referentiel"]]=='Unique') { ?>
          
          <?php 
						if(isset($cible_ind_ref_array[$row_ind["referentiel"]][$anp])) {
						echo number_format($cible_ind_ref_array[$row_ind["referentiel"]][$anp], 0, ',', ' '); $val_cib=$cible_ind_ref_array[$row_ind["referentiel"]][$anp];
						 } else echo " "; ?>
          
          <?php 
					 } elseif(isset($mode_calcul_ind_ref_array[$row_ind["referentiel"]]) && $mode_calcul_ind_ref_array[$row_ind["referentiel"]]=="Somme")
						 {
						 if(isset($cible_ind_ref_array[$row_ind["referentiel"]][$anp]))
						 {?>
           <?php echo number_format($cible_ind_ref_array[$row_ind["referentiel"]][$anp], 0, ',', ' ');?>
          <?php $val_cib=$cible_ind_ref_array[$row_ind["referentiel"]][$anp]; }
												 
						  } elseif(isset($mode_calcul_ind_ref_array[$row_ind["referentiel"]]) && $mode_calcul_ind_ref_array[$row_ind["referentiel"]]=="Ratio" && isset($liste_num_ratio_array[$row_ind["referentiel"]]) && isset($liste_deno_ratio_array[$row_ind["referentiel"]]))
						 {
						 //cas ou numerateur est une somme
						 if(isset($mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]] =="Somme" && isset($somme_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]][$anp]) )  $cible_num=$liste_coef_ratio_array[$row_ind["referentiel"]]*$somme_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]][$anp];
						 
						 
						  //cas ou denominateur est une somme
						 if(isset($mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]] =="Somme" && isset($somme_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]][$anp]) && $liste_deno_ratio_array[$row_ind["referentiel"]]>0 )   $cible_deno=$somme_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]][$anp];
						 						 
						  //cas ou num est unique
						  if(isset($mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]] =="Unique" && isset($cible_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]][$anp]) ) $cible_num=$liste_coef_ratio_array[$row_ind["referentiel"]]*$cible_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]][$anp]; 
						 
						  //cas ou deno est unique
						  if(isset($mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]] =="Unique" && isset($cible_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]][$anp]) && $liste_deno_ratio_array[$row_ind["referentiel"]]>0 )  $cible_deno=$cible_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]][$anp];
						  
						   //cas ou numerateur est une moyenne
						 if(isset($mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]] =="Moyenne" && isset($moyenne_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]][$anp]) )  $cible_num=$liste_coef_ratio_array[$row_ind["referentiel"]]*$moyenne_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]][$anp];
						 
						  //cas ou denominateur est une moyenne
						 if(isset($mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]] =="Moyenne" && isset($moyenne_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]][$anp]) && $liste_deno_ratio_array[$row_ind["referentiel"]]>0 )   $cible_deno=$moyenne_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]][$anp];
						  
						  
						 //if() echo $somme_ind_ref_array[$row_ind["referentiel"]];
						 if($cible_deno!=0) {echo number_format(100*$cible_num/$cible_deno, 0, ',', ' '); $val_cib=100*$cible_num/$cible_deno;}
						 
						 $cible_num=$cible_deno=0;
						 }
						  elseif(isset($mode_calcul_ind_ref_array[$row_ind["referentiel"]]) && $mode_calcul_ind_ref_array[$row_ind["referentiel"]]=="Moyenne")
						 {
						 if(isset($cible_ind_ref_array[$row_ind["referentiel"]][$anp]))
						 { echo number_format($cible_ind_ref_array[$row_ind["referentiel"]][$anp], 0, ',', ' ');  $val_cib=$cible_ind_ref_array[$row_ind["referentiel"]][$anp];
						 //$tcic=$tcic+$moyenne_ind_ref_array[$row_ind["referentiel"]][$anp];}
						 }
						 }
					  else echo " ";

						?>
        </div></td>
        <td nowrap="nowrap"><div align="center">
          <?php
							   if( isset($mode_calcul_ind_ref_array[$row_ind["referentiel"]]) &&  $mode_suivi_ind_ref_array[$row_ind["referentiel"]]==1)
							   {
							   //
							     if($mode_calcul_ind_ref_array[$row_ind["referentiel"]]=='Unique') {

								  if(isset($suivi_ind_ref_array[$row_ind["referentiel"]][$anp]))
								  {
								  $val_real=$suivi_ind_ref_array[$row_ind["referentiel"]][$anp];
								  }
							    //$val_real=$realise_ptba_array[$anp][$row_ind["referentiel"]];
								 } elseif($mode_calcul_ind_ref_array[$row_ind["referentiel"]]=="Somme")
						 {
						 if(isset($suivi_somme_ind_ref_array[$row_ind["referentiel"]][$anp]))
						 {  $val_real=$suivi_somme_ind_ref_array[$row_ind["referentiel"]][$anp];  }
												 
						   } elseif($mode_calcul_ind_ref_array[$row_ind["referentiel"]]=="Moyenne")
						 {
						 if(isset($suivi_moyenne_ind_ref_array[$row_ind["referentiel"]][$anp]))
						 {  $val_real=$suivi_moyenne_ind_ref_array[$row_ind["referentiel"]][$anp];}
												 
						  } elseif($mode_calcul_ind_ref_array[$row_ind["referentiel"]]=="Ratio" && isset($liste_num_ratio_array[$row_ind["referentiel"]]) && isset($liste_deno_ratio_array[$row_ind["referentiel"]]))
						 {
						  //cas ou numerateur est une somme
						 if(isset($mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]] =="Somme" && isset($suivi_somme_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]][$anp]) )   $cible_nums=$suivi_somme_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]][$anp];

						  //cas ou denominateur est une somme
						 if(isset($mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]] =="Somme" && isset($suivi_somme_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]][$anp]) )   $cible_denos=$suivi_somme_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]][$anp];

						  //cas ou num est unique
						  if(isset($mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]] =="Unique" && isset($suivi_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]][$anp]) ) $cible_nums=$suivi_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]][$anp];

						  //cas ou deno est unique
						  if(isset($mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]] =="Unique" && isset($suivi_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]][$anp]) )  $cible_denos=$suivi_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]][$anp];

						   //cas ou numerateur est une moyenne
						 if(isset($mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]] =="Moyenne" && isset($suivi_moyenne_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]][$anp]) )  $cible_nums=$suivi_moyenne_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]][$anp];

						  //cas ou denominateur est une moyenne
						 if(isset($mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]] =="Moyenne" && isset($suivi_moyenne_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]][$anp]) )   $cible_denos=$suivi_moyenne_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]][$anp];


						 //if() echo $somme_ind_ref_array[$row_ind["referentiel"]];
						 if($cible_denos!=0) { $val_real=100*$cible_nums/$cible_denos; }

						 $cible_nums=$cible_denos=0;
						 }
								 ?>
          <?php }
							  
							 elseif( isset($mode_calcul_ind_ref_array[$row_ind["referentiel"]]) && $mode_suivi_ind_ref_array[$row_ind["referentiel"]]==2)
							   { 
							   if(isset( $realise_cmr_array[$anp][$row_ind["referentiel"]]))
							   $val_real=$realise_cmr_array[$anp][$row_ind["referentiel"]];
							   } 
							?>
          <?php if($val_real>0) { echo number_format($val_real, 0, ',', ' '); $tot_real=$tot_real+$val_real;}  else ""; ?>
        </div></td>
        <td nowrap="nowrap"><div align="center"> <span class="Style32">
          <?php if($val_cib>0 && $val_real>0) echo number_format(100*$val_real/$val_cib, 0, ',', ' ')." %"; else echo "-"; $val_cib=$val_real=0;?>
        </span></div></td>
        <?php  }else{ ?>
        <td nowrap="nowrap" colspan="3"><div align="center">
          <?php
							   if( isset($mode_calcul_ind_ref_array[$row_ind["referentiel"]]) &&  $mode_suivi_ind_ref_array[$row_ind["referentiel"]]==1)
							   {
							   //
							     if($mode_calcul_ind_ref_array[$row_ind["referentiel"]]=='Unique') {

								  if(isset($suivi_ind_ref_array[$row_ind["referentiel"]][$anp]))
								  {
								  $val_real=$suivi_ind_ref_array[$row_ind["referentiel"]][$anp];
								  }
							    //$val_real=$realise_ptba_array[$anp][$row_ind["referentiel"]];
								 } elseif($mode_calcul_ind_ref_array[$row_ind["referentiel"]]=="Somme")
						 {
						 if(isset($suivi_somme_ind_ref_array[$row_ind["referentiel"]][$anp]))
						 {  $val_real=$suivi_somme_ind_ref_array[$row_ind["referentiel"]][$anp];  }

						   } elseif($mode_calcul_ind_ref_array[$row_ind["referentiel"]]=="Moyenne")
						 {
						 if(isset($suivi_moyenne_ind_ref_array[$row_ind["referentiel"]][$anp]))
						 {  $val_real=$suivi_moyenne_ind_ref_array[$row_ind["referentiel"]][$anp];}

						  } elseif($mode_calcul_ind_ref_array[$row_ind["referentiel"]]=="Ratio" && isset($liste_num_ratio_array[$row_ind["referentiel"]]) && isset($liste_deno_ratio_array[$row_ind["referentiel"]]))
						 {
						  //cas ou numerateur est une somme
						 if(isset($mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]] =="Somme" && isset($suivi_somme_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]][$anp]) )   $cible_nums=$suivi_somme_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]][$anp];

						  //cas ou denominateur est une somme
						 if(isset($mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]] =="Somme" && isset($suivi_somme_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]][$anp]) )   $cible_denos=$suivi_somme_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]][$anp];

						  //cas ou num est unique
						  if(isset($mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]] =="Unique" && isset($suivi_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]][$anp]) ) $cible_nums=$suivi_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]][$anp];

						  //cas ou deno est unique
						  if(isset($mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]] =="Unique" && isset($suivi_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]][$anp]) )  $cible_denos=$suivi_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]][$anp];

						   //cas ou numerateur est une moyenne
						 if(isset($mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]] =="Moyenne" && isset($suivi_moyenne_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]][$anp]) )  $cible_nums=$suivi_moyenne_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]][$anp];

						  //cas ou denominateur est une moyenne
						 if(isset($mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]] =="Moyenne" && isset($suivi_moyenne_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]][$anp]) )   $cible_denos=$suivi_moyenne_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]][$anp];


						 //if() echo $somme_ind_ref_array[$row_ind["referentiel"]];
						 if($cible_denos!=0) { $val_real=100*$cible_nums/$cible_denos; }

						 $cible_nums=$cible_denos=0;
						 }
								 ?>
          <?php }

							 elseif( isset($mode_calcul_ind_ref_array[$row_ind["referentiel"]]) && $mode_suivi_ind_ref_array[$row_ind["referentiel"]]==2)
							   {
							   if(isset( $realise_cmr_array[$anp][$row_ind["referentiel"]]))
							   $val_real=$realise_cmr_array[$anp][$row_ind["referentiel"]];
							   }
							?>
          <?php if($val_real>0) { echo number_format($val_real, 0, ',', ' '); $tot_real=$tot_real+$val_real;}  else ""; ?>
        </div></td>
<?php } } ?>
        <td width="2%" rowspan="nowrap" align="center" bgcolor="#000000"></td>
        <td width="10%" nowrap="nowrap"><div align="center" class="Style51">
          <?php if(isset($row_ind['cible_rmp']) && ($row_ind['cible_projet']!=$row_ind['cible_rmp'] && $row_ind['cible_rmp']>0))
								    echo number_format($row_ind['cible_rmp'], 0, ',', ' '); else echo number_format($row_ind['cible_projet'], 0, ',', ' '); ?>
        </div></td>
        <td width="10%" nowrap="nowrap"><div align="center"><?php if($tot_real>0) echo number_format($tot_real, 0, ',', ' ')?> </div></td>
        <td width="10%" nowrap="nowrap"><div align="center">
          <?php 
								 if($tot_real>0 && isset($row_ind['cible_rmp']) && ($row_ind['cible_rmp']!=$row_ind['cible_rmp'] && $row_ind['cible_rmp']>0)) echo number_format(100*$tot_real/$row_ind['cible_rmp'], 0, ',', ' ')." %";
				elseif($tot_real>0 && isset($row_ind['cible_projet']) && $row_ind['cible_projet']>0) echo number_format(100*$tot_real/$row_ind['cible_projet'], 0, ',', ' ')." %";
								 //else echo "n/a";

								 ?>
        </div></td>
       
      </tr>
      <?php } while ($row_ind = mysql_fetch_assoc($ind));  mysql_free_result($ind);?>
      <tr>
        <td colspan="5"><div align="center" class="Style2">
          <?php if(!$totalRows_ind>0) echo "<br />Aucun indicateur enregistr&eacute;";?>
        </div></td>
      </tr>
    </table></td>
  </tr>
  <?php } ?>
</table>  </div> 

<!-- Fin Site contenu ici -->

            </div>

        </div>



        </div>

    </div>

    <?php include_once ("includes/footer.php");?>

</div>

</body>

</html>