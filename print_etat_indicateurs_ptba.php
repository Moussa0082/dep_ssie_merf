<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & DÃ©veloppement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=ISO-8859-15');

if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=etat_indicateurs_ptba.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=etat_indicateurs_ptba.doc"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="p"){

require_once('./tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$PDF_HEADER_TITLE = "Situation Indicateurs des activités du PTBA";
$PDF_HEADER_STRING = "Situation Indicateurs des activités du PTBA";

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Ruche');
$pdf->SetTitle($PDF_HEADER_TITLE);
$pdf->SetSubject($PDF_HEADER_STRING);
$pdf->SetKeywords('PDF, Résultat, Situation Indicateurs des activités du PTBA');

// set default header data //PDF_HEADER_LOGO
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE, $PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}
// set font
//$pdf->SetFont('dejavusans', '', 10);
$pdf->AddPage();

  ob_start(); // turn on output buffering
  /*$_GET["id"]="0001";
  $_GET["down"]=5; */
  include("./print_etat_indicateurs_ptba_pdf.php");
  $content = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering

$html = utf8_encode($content);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('etat_indicateurs_ptba.pdf', 'D');
/*
include("pdf/mpdf.php");
$mpdf=new mPDF('win-1252','A4-L','','',15,10,16,10,10,10);//A4 page in portrait for landscape add -L.
$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetDisplayMode('fullpage');
ob_start();
include "print_etat_recommandation_mission_pdf.php";
$html = ob_get_contents();
ob_end_clean();
$mpdf->WriteHTML($html);
$mpdf->Output();
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Recommandation_mission.pdf"); */
exit;

} ?>
<?php
$annee=(isset($_GET['annee']))?$_GET['annee']:date("Y");
$ugl=(isset($_GET['ugl']))?$_GET['ugl']:$_SESSION["clp_structure"];
$array_indic = array("OUI/NON","texte");
$query_edit_ms = "SELECT code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=1 and projet='".$_SESSION["clp_projet"]."' ORDER BY code asc";
  try{
    $edit_ms = $pdar_connexion->prepare($query_edit_ms);
    $edit_ms->execute();
    $row_edit_ms = $edit_ms ->fetchAll();
    $totalRows_edit_ms = $edit_ms->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  if(isset($_GET['ugl']))
$query_indicateur = "SELECT * FROM ptba, ".$database_connect_prefix."indicateur_tache where id_ptba=id_activite and ptba.annee=$annee and ptba.projet='".$_SESSION["clp_projet"]."' and id_indicateur_tache in (select indicateur from cible_indicateur_trimestre where region='$ugl') ORDER BY id_activite asc";
else
$query_indicateur = "SELECT * FROM ptba, ".$database_connect_prefix."indicateur_tache where id_ptba=id_activite and ptba.annee=$annee and ptba.projet='".$_SESSION["clp_projet"]."' ORDER BY id_activite asc";
  try{
    $indicateur = $pdar_connexion->prepare($query_indicateur);
    $indicateur->execute();
    $row_indicateur = $indicateur ->fetchAll();
    $totalRows_indicateur = $indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$indicateur_array = $unite_array = array();
if($totalRows_indicateur>0) { foreach($row_indicateur as $row_indicateur){
$indicateur_array[$row_indicateur["id_activite"]][$row_indicateur["id_indicateur_tache"]] = $row_indicateur;
$unite_array[$row_indicateur["id_indicateur_tache"]] = $row_indicateur["unite"];
}  }



//semestre precedent
  if(isset($_GET['ugl']))
$query_cible_indicateur = "SELECT cible_indicateur_trimestre.* FROM cible_indicateur_trimestre, indicateur_tache, ptba where id_ptba=id_activite and 	id_indicateur_tache=indicateur and ptba.annee=$annee and ptba.projet='".$_SESSION["clp_projet"]."' and cible_indicateur_trimestre.region='$ugl'";
else
$query_cible_indicateur = "SELECT cible_indicateur_trimestre.* FROM cible_indicateur_trimestre, indicateur_tache, ptba where id_ptba=id_activite and 	id_indicateur_tache=indicateur and ptba.annee=$annee and ptba.projet='".$_SESSION["clp_projet"]."'"; 
    try{
    $cible_indicateur = $pdar_connexion->prepare($query_cible_indicateur);
    $cible_indicateur->execute();
    $row_cible_indicateur = $cible_indicateur ->fetchAll();
    $totalRows_cible_indicateur = $cible_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableau_cible_indicateur_array = array();
  if($totalRows_cible_indicateur>0){  foreach($row_cible_indicateur as $row_cible_indicateur){      //(!in_array($unite_array[$row_cible_indicateur["indicateur"]],$array_indic))?   :"cible_txt"
    if(!isset($tableau_cible_indicateur_array[$row_cible_indicateur["indicateur"]][$row_cible_indicateur["trimestre"]]))
    $tableau_cible_indicateur_array[$row_cible_indicateur["indicateur"]][$row_cible_indicateur["trimestre"]] = $row_cible_indicateur["cible"];
    else 
    $tableau_cible_indicateur_array[$row_cible_indicateur["indicateur"]][$row_cible_indicateur["trimestre"]] += $row_cible_indicateur["cible"];
  } }


  //PTBA
/*  $appendice_array =array();
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_appendice4 = "SELECT intitule_indicateur_tache,code_activite_ptba,intitule_activite_ptba,id_indicateur_tache, unite FROM ".$database_connect_prefix."ptba, ".$database_connect_prefix."indicateur_tache where code_activite_ptba=code_activite and code_activite_ptba='$code_act' and ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' ORDER BY code_activite_ptba,intitule_indicateur_tache";
  $appendice4  = mysql_query_ruche($query_appendice4 , $pdar_connexion) or die(mysql_error());
  $row_appendice4  = mysql_fetch_assoc($appendice4);
  $totalRows_appendice4  = mysql_num_rows($appendice4);
  if($totalRows_appendice4>0){ do{
  $appendice_array[$row_appendice4["id_indicateur_tache"]]=$row_appendice4["intitule_indicateur_tache"]."|".$row_appendice4["unite"];  }while($row_appendice4  = mysql_fetch_assoc($appendice4)); }  */


//dynamique
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT * FROM ".$database_connect_prefix."indicateur_config WHERE projet='".$_SESSION["clp_projet"]."' and type='PTBA' and ind in (SELECT id_indicateur_tache FROM ".$database_connect_prefix."ptba, ".$database_connect_prefix."indicateur_tache where id_ptba=id_activite and ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."')";
$entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);

$cmr_realise = $indicateur_dynamique = array();
$mode_calcul = array("SOMME"=>"SUM","MOYENNE"=>"AVG","COMPTER"=>"COUNT");
if($totalRows_entete>0){ do{ $id=$row_entete["ind"]; $col =trim($row_entete["col"]); $table = $row_entete["id_fiche"]; $table1 = substr($table,0,strlen($table)-8); $tmp = explode('_',$table); $classeur = intval($tmp[1]); $feuille = $table;
$indicateur_dynamique[$id]["feuille"] = $database_connect_prefix.$feuille;
$indicateur_dynamique[$id]["classeur"] = $classeur;
if(isset($indicateur_dynamique[$id]["lib"])) $indicateur_dynamique[$id]["lib"] = "";
list($indicateur_dynamique[$id]["lib"],$indicateur_dynamique[$id]["unite"]) = ($row_entete['type']=="CMR" && isset($cmr_array[$row_entete['ind']]))?explode('|',$cmr_array[$row_entete['ind']]):(($row_entete['type']=="PTBA" && isset($appendice_array[$row_entete['ind']]))?explode('|',$appendice_array[$row_entete['ind']]):'NaN');
if(isset($indicateur_dynamique[$id]["color"])) $indicateur_dynamique[$id]["color"] = "";
$indicateur_dynamique[$id]["color"] = !empty($row_entete['couleur'])?$row_entete['couleur']:(isset($classeur_color_array[$classeur])?$classeur_color_array[$classeur]:'');
$type=""; $formule = (!empty($row_entete['mode_calcul']) && isset($mode_calcul[$row_entete['mode_calcul']]))?$mode_calcul[$row_entete['mode_calcul']]:$mode_calcul["SOMME"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "DESCRIBE `".$database_connect_prefix."$table`";
$liste_cp = mysql_query_ruche($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);
if($totalRows_liste_cp>0){ do{ if($row_liste_cp["Field"]==$col) $type=$row_liste_cp["Type"]; }while($row_liste_cp = mysql_fetch_assoc($liste_cp)); }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
if(strchr($table,"_details")!="")
{
  //`$table`.annee=$annee and  group by departement.region   ".$database_connect_prefix."validation_fiche.niveau1=1 and ".$database_connect_prefix."validation_fiche.niveau2=1 and
  $query_data = "SELECT $formule(`".$database_connect_prefix."$table`.$col) as nb FROM `".$database_connect_prefix."$table`,".$database_connect_prefix."validation_fiche WHERE ".$database_connect_prefix."validation_fiche.id_lkey=`".$database_connect_prefix."$table`.LKEY and ".$database_connect_prefix."validation_fiche.nom_fiche='".$database_connect_prefix."$table'";
  $data  = mysql_query_ruche($query_data , $pdar_connexion);
  if($data){
  $row_data  = mysql_fetch_assoc($data);
  $totalRows_data  = mysql_num_rows($data);
  if($totalRows_data>0){ do{
    if(isset($indicateur_dynamique[$id]["val"])) $indicateur_dynamique[$id]["val"] += $row_data["nb"];
    else $indicateur_dynamique[$id]["val"] = $row_data["nb"]; }while($row_data  = mysql_fetch_assoc($data)); }       }
}
   }while($row_entete  = mysql_fetch_assoc($entete)); }


unset($taux_progress,$taux,$tauxG,$tauxT);*/

//$query_liste_ind_ptba = "SELECT unite, id_indicateur_tache, intitule_indicateur_tache, code_indicateur_ptba, ".$database_connect_prefix."indicateur_tache.id_activite FROM ".$database_connect_prefix."indicateur_tache, ".$database_connect_prefix."cible_indicateur_trimestre where ".$database_connect_prefix."indicateur_tache.code_activite=".$database_connect_prefix."cible_indicateur_trimestre.activite and ".$database_connect_prefix."indicateur_tache.projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."cible_indicateur_trimestre.projet='".$_SESSION["clp_projet"]."' and id_indicateur_tache=indicateur group by ".$database_connect_prefix."indicateur_tache.code_activite,id_indicateur_tache";
  if(isset($_GET['ugl']))
$query_liste_ind_ptba = "SELECT indicateur_tache.unite, id_indicateur_tache, intitule_indicateur_tache, code_indicateur_ptba, indicateur_tache.id_activite FROM cible_indicateur_trimestre, indicateur_tache, ptba where id_ptba=id_activite and 	id_indicateur_tache=indicateur and ptba.annee=$annee and ptba.projet='".$_SESSION["clp_projet"]."' and cible_indicateur_trimestre.region='$ugl' ";
else
$query_liste_ind_ptba = "SELECT indicateur_tache.unite, id_indicateur_tache, intitule_indicateur_tache, code_indicateur_ptba, indicateur_tache.id_activite FROM cible_indicateur_trimestre, indicateur_tache, ptba where id_ptba=id_activite and 	id_indicateur_tache=indicateur and ptba.annee=$annee and ptba.projet='".$_SESSION["clp_projet"]."' ";
  try{
    $liste_ind_ptba = $pdar_connexion->prepare($query_liste_ind_ptba);
    $liste_ind_ptba->execute();
    $row_liste_ind_ptba = $liste_ind_ptba ->fetchAll();
    $totalRows_liste_ind_ptba = $liste_ind_ptba->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//Valeur cible
//$query_cible = "SELECT sum(cible) as valeur_cible, avg(cible) as valeur_cible_avg, activite as id_activite, indicateur FROM ".$database_connect_prefix."cible_indicateur_trimestre where projet='".$_SESSION["clp_projet"]."' and projet='".$_SESSION["clp_projet"]."' group by id_activite,indicateur";
  if(isset($_GET['ugl']))
$query_cible = "SELECT sum(cible) as valeur_cible, avg(cible) as valeur_cible_avg, id_ptba as id_activite, indicateur FROM cible_indicateur_trimestre, indicateur_tache, ptba where id_ptba=id_activite and 	id_indicateur_tache=indicateur and ptba.annee=$annee and ptba.projet='".$_SESSION["clp_projet"]."' and cible_indicateur_trimestre.region='$ugl'   group by id_activite, indicateur";
else
$query_cible = "SELECT sum(cible) as valeur_cible, avg(cible) as valeur_cible_avg, id_ptba as id_activite, indicateur FROM cible_indicateur_trimestre, indicateur_tache, ptba where id_ptba=id_activite and 	id_indicateur_tache=indicateur and ptba.annee=$annee and ptba.projet='".$_SESSION["clp_projet"]."'   group by id_activite, indicateur";
  try{
    $cible = $pdar_connexion->prepare($query_cible);
    $cible->execute();
    $row_cible = $cible ->fetchAll();
    $totalRows_cible = $cible->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$row_cible_ind = array();
if($totalRows_cible>0){ foreach($row_cible as $row_cible){  $id = $row_cible["indicateur"]; $id_act = $row_cible["id_activite"];
  if(isset($row_cible_ind["sum"][$id_act][$id])) $row_cible_ind["sum"][$id_act][$id] += $row_cible["valeur_cible"];
  else $row_cible_ind["sum"][$id_act][$id] = $row_cible["valeur_cible"];
  if(isset($row_cible_ind["avg"][$id_act][$id])) $row_cible_ind["avg"][$id_act][$id] += $row_cible["valeur_cible_avg"];
  else $row_cible_ind["avg"][$id_act][$id] = $row_cible["valeur_cible_avg"];
} }


//Valeur reelle
  if(isset($_GET['ugl']))
$query_valeur_suivi_ind = "SELECT sum(valeur_suivi) as valeur_reelle, avg(valeur_suivi) as valeur_reelle_avg, indicateur FROM suivi_indicateur_tache, indicateur_tache, ptba where id_ptba=id_activite and 	id_indicateur_tache=indicateur and ptba.annee=$annee and ptba.projet='".$_SESSION["clp_projet"]."' and suivi_indicateur_tache.ugl='$ugl'  group by indicateur";
else
$query_valeur_suivi_ind = "SELECT sum(valeur_suivi) as valeur_reelle, avg(valeur_suivi) as valeur_reelle_avg, indicateur FROM suivi_indicateur_tache, indicateur_tache, ptba where id_ptba=id_activite and 	id_indicateur_tache=indicateur and ptba.annee=$annee and ptba.projet='".$_SESSION["clp_projet"]."' group by indicateur";
  try{
    $valeur_suivi_ind = $pdar_connexion->prepare($query_valeur_suivi_ind);
    $valeur_suivi_ind->execute();
    $row_valeur_suivi_ind = $valeur_suivi_ind ->fetchAll();
    $totalRows_valeur_suivi_ind = $valeur_suivi_ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$row_suivi_ind = array();
if($totalRows_valeur_suivi_ind>0){  foreach($row_valeur_suivi_ind as $row_valeur_suivi_ind){ $id = $row_valeur_suivi_ind["indicateur"];
  if(isset($row_suivi_ind["sum"][$id])) $row_suivi_ind["sum"][$id] += $row_valeur_suivi_ind["valeur_reelle"];
  else $row_suivi_ind["sum"][$id] = $row_valeur_suivi_ind["valeur_reelle"];
  if(isset($row_suivi_ind["avg"][$id])) $row_suivi_ind["avg"][$id] += $row_valeur_suivi_ind["valeur_reelle_avg"];
  else $row_suivi_ind["avg"][$id] = $row_valeur_suivi_ind["valeur_reelle_avg"];
} }


$tauxG=$tauxT=$taux_tache=$valeur_tache=array();  $taux_progress = $tauxGG = 0;

if($totalRows_liste_ind_ptba>0) {$m=0; foreach($row_liste_ind_ptba as $row_liste_ind_ptba){
//Cible
$row_suivi_ind['valeur_reelle']="-";
$id_ind=$row_liste_ind_ptba['id_activite'];
$id_ind_tache=$row_liste_ind_ptba['id_indicateur_tache'];
$unite=$row_liste_ind_ptba['unite']; $fn = ($unite=="%")?'avg':'sum';
if(!isset($tauxG[$id_ind])) $tauxG[$id_ind] = 0; if(!isset($tauxT[$id_ind])) $tauxT[$id_ind] = 0;
if(!isset($taux_tache[$id_ind_tache])) $taux_tache[$id_ind_tache] = 0;
if(!isset($valeur_tache[$id_ind_tache])) $valeur_tache[$id_ind_tache] = 0;

//suivi indicateur
if(isset($row_suivi_ind["sum"][$id_ind_tache]))
{

  $totalRows_suivi_ind = 1;
  $row_suivi_ind['valeur_reelle'] = $row_suivi_ind["sum"][$id_ind_tache];
  if(in_array($unite,$array_indic)){ $totalRows_liste_ind_ptba--; $tauxT[$id_ind]--; }
}

if(in_array($unite,$array_indic)){ $valeur_tache[$id_ind_tache] = $row_suivi_ind["sum"][$id_ind_tache]; }
elseif(isset($row_suivi_ind['valeur_reelle'])) $valeur_tache[$id_ind_tache] = $row_suivi_ind['valeur_reelle'];
$taux = 0;

if(isset($row_cible_ind[$fn][$id_ind][$id_ind_tache]) && intval($row_cible_ind[$fn][$id_ind][$id_ind_tache])>0 && isset($row_suivi_ind['valeur_reelle']) && intval($row_suivi_ind['valeur_reelle'])>0 && isset($totalRows_suivi_ind) && $totalRows_suivi_ind>0)
 {$taux=100*$row_suivi_ind['valeur_reelle']/$row_cible_ind[$fn][$id_ind][$id_ind_tache];
 $taux = ($taux>100)?100:$taux;  }
$tauxG[$id_ind] += $taux; $tauxGG+=$taux; $taux_tache[$id_ind_tache] = $taux;
$tauxT[$id_ind] += 1;
} }
if($totalRows_liste_ind_ptba>0) $taux_progress = $tauxGG/$totalRows_liste_ind_ptba;

//exit;
$query_liste_prestataire = "SELECT * FROM ".$database_connect_prefix."ugl order by code_ugl";
  try{
    $liste_prestataire = $pdar_connexion->prepare($query_liste_prestataire);
    $liste_prestataire->execute();
    $row_liste_prestataire = $liste_prestataire ->fetchAll();
    $totalRows_liste_prestataire = $liste_prestataire->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$acteur_array = $Nacteur_array= array();
  if($totalRows_liste_prestataire>0){ 
   foreach($row_liste_prestataire as $row_liste_prestataire1){
    $Nacteur_array[$row_liste_prestataire1["code_ugl"]] = $row_liste_prestataire1["nom_ugl"];
	$acteur_array[] = $row_liste_prestataire1["code_ugl"]."!!".$row_liste_prestataire1["nom_ugl"];
  }}//while($row_liste_prestataire = mysql_fetch_assoc($liste_prestataire)); 
  
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
<?php if(!isset($_GET["down"])){  ?>
<head>
  <title><?php print $config->sitename; ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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
.Style11 { font-weight: bold;color: #FFFFFF;}
.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; } .marquer{background: #FFFF00!important; }
</style>
<div class="contenu">
<?php if(!isset($_GET["down"])){  ?>
<form name="form<?php echo $annee; ?>" id="form<?php echo $annee; ?>" method="get" action="<?php echo "print_etat_indicateurs_ptba.php?annee=".$annee; ?>" class="pull-left"> P&eacute;riode :&nbsp;

<select name="trim" onchange="form<?php echo $annee; ?>.submit();" style="background-color:#FF9933; padding: 7px; width: 300px;" class="btn p11">

  <option value="trim1" <?php if((isset($_GET["trim"]) && 'trim1'==$_GET["trim"])) echo "selected='SELECTED'"; ?>>1er Trimestre</option>
  <option value="trim2" <?php if((isset($_GET["trim"]) && 'trim2'==$_GET["trim"])) echo "selected='SELECTED'"; ?>>2&egrave;me Trimestre</option>
  <option value="trim3" <?php if((isset($_GET["trim"]) && 'trim3'==$_GET["trim"])) echo "selected='SELECTED'"; ?>>3&egrave;me Trimestre</option>
  <option value="trim4" <?php if((isset($_GET["trim"]) && 'trim4'==$_GET["trim"])) echo "selected='SELECTED'"; ?>>4&egrave;me Trimestre</option>
  <option value="%" <?php if((!isset($_GET["trim"]) && '%'==$trim)) echo "selected='SELECTED'"; ?>>Tout <?php echo $lib_version_ptba; ?></option>

</select>
<input type="hidden" name="annee" value="<?php echo $annee; ?>" />
<input type="hidden" name="acteur" value="<?php echo $iactget; ?>" />

</form>
<div class="well well-sm r_float"><div class="r_float"><a href="./<?php if(isset($_GET['ugl'])) echo  "s_programmation.php"; else echo "s_programmation.php"; ?>" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<!--<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format PDF" href="<?php echo $editFormAction."&down=1&t=p"; ?>" class="button"><img src="./images/pdf.png" width='20' height='20' alt='Modifier' /></a></div>-->
<div class="r_float" style="margin-right: 20px;"><a target="_blank" title="Imprimer" href="<?php echo $editFormAction."&down=1"; ?>" class="button"><img src="./images/print.png" width='20' height='20' alt='Modifier' /></a></div>
</div>
<div class="clear h0">&nbsp;</div>
<?php } else { ?>

<!--<center><?php //include "./includes/print_header.php"; ?></center>       -->

<?php } ?>
<div class="well well-sm"><strong>Situation des Indicateurs du PTBA <?php echo "$lib_version_ptba"; ?>&nbsp;&nbsp;<span style="background-color:#FFCC33">
  <?php if(isset($_GET["acteur"]) && $_GET["acteur"]==0 && $_GET["acteur"]!="") echo  "&nbsp;(<u>Partenaires</u>)&nbsp;"; elseif(isset($_GET["acteur"]) && $_GET["acteur"]==1) echo  "&nbsp;(<u>PDAIG</u>)&nbsp;"; ?>
</span></strong></div>

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-responsive">
            <thead>
            <tr bgcolor="<?php echo (!isset($_GET["down"]))?'':'#E4E4E4'; ?>">
              <th rowspan="2" align="left">ACTIVITES</th>
              <!--<th rowspan="2" align="center"><b>N&deg;&nbsp;</b></th>-->
              <th rowspan="2" align="left">INDICATEUR</th>
              <th rowspan="2" align="left"><center>UNITE</center></th>
              <!-- <th rowspan="2" align="center"><center>TAUX (%)</center></th>-->
              <!--<th colspan="4" ><center>VALEUR REELLE</center></th> -->
              <th colspan="3" align="center"><center>
                SUIVI DE L'EXECUTION</center></th>
              <th rowspan="2" align="center"><div align="center">TAUX</div>                <center>
                  TOTAL (%)
                </center></th>
            </tr>
            <tr bgcolor="<?php echo (!isset($_GET["down"]))?'':'#E4E4E4'; ?>">
              <?php //for($j=1;$j<=4;$j++){ ?>
              <!--<th align="center"><center>Trimestre <?php echo $j; ?></center></th>-->
              <?php //} ?>
              <th align="center"><center>VALEUR CIBLE</center></th>
              <th align="center"><center>VALEUR REELLE</center></th>
              <th align="center"><div align="center">TAUX (%)</div></th>
              </tr>
            </thead>
<?php  if($totalRows_edit_ms>0) { foreach($row_edit_ms as $row_edit_ms){
$code = $row_edit_ms['code'];
  //if(isset($_GET['ugl']))
  if(isset($_GET["acteur"]) && $_GET["acteur"]!="") {$iactget=$_GET["acteur"]; $wheract="AND fin=$iactget"; } else $wheract="";
$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."ptba where projet='".$_SESSION["clp_projet"]."' and annee='$annee' and code_activite_ptba like '$code%'  $wheract ORDER By code_activite_ptba asc";
  try{
    $liste_rec = $pdar_connexion->prepare($query_liste_rec);
    $liste_rec->execute();
    $row_liste_rec = $liste_rec ->fetchAll();
    $totalRows_liste_rec = $liste_rec->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if($totalRows_liste_rec>0) {$i=0; $t=0; $p2=$p1="j"; ?>
            <tr bgcolor="#BED694">
              <td colspan="8" align="center" style="background-color: #BED694;">
                <b><?php echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?></b>              </td>
            </tr>
<?php $row="";  foreach($row_liste_rec as $row_liste_rec){ $code_act = $row_liste_rec['code_activite_ptba']; $id_act = $row_liste_rec['id_ptba'];
if(isset($indicateur_array[$id_act])){ $k=0;
foreach($indicateur_array[$id_act] as $a=>$b){ $total = 0; $div = 0; ?>
            <tr>
<?php if($k==0){ ?>
<td rowspan="<?php echo count($indicateur_array[$id_act]); ?>"><?php echo "<b>".$row_liste_rec['code_activite_ptba'].'</b> : '.$row_liste_rec['intitule_activite_ptba']; ?></td>
<?php } ?>
<!--<td><?php //echo $b['code_indicateur_ptba']; ?></td>-->
<?php for($j=1;$j<=4;$j++){ if(!in_array($b['unite'],$array_indic) && isset($tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j])) $total+= $tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j]; ?>
<?php } ?>
<td><?php echo $b['intitule_indicateur_tache']; ?></td>
<td width="100"><?php echo $unite_array[$b["id_indicateur_tache"]]; $total = ($b['unite']=="%")?$total:$total; $realval=(isset($valeur_tache[$b['id_indicateur_tache']]))?$valeur_tache[$b['id_indicateur_tache']]:"0"; ?></td>
 <!--<td align="center" width="70">&nbsp;</td>-->

<td width="100" valign="middle" align="center"><?php if($b['unite']=="Oui/Non" && $total==0) echo "Oui"; else  echo (!in_array($b['unite'],$array_indic))?$total:'-'; ?></td>
<td width="70" valign="middle" align="center" ><?php echo (isset($valeur_tache[$b['id_indicateur_tache']]))?$valeur_tache[$b['id_indicateur_tache']]:"-"; ?></td>
<td width="70" valign="middle" align="center" ><?php echo ($total>0 && $realval>0)?number_format(100*$realval/$total, 0, ',', ' ')." %":"-"; ?></td>
<?php if($k==0){ ?>
<td rowspan="<?php echo count($indicateur_array[$id_act]); ?>" width="70" valign="middle" align="center" ><?php echo (isset($tauxG[$id_act]) && $tauxT[$id_act]>0 && $tauxG[$id_act]>0)?number_format($tauxG[$id_act]/$tauxT[$id_act], 0, ',', ' ')." %":""; ?></td>
<?php } ?>
            </tr>
            <?php $row=$b["id_indicateur_tache"]; $k++; }  }else {  ?>
<tr class="even">
<td colspan="1"><?php echo "<b>".$row_liste_rec['code_activite_ptba'].'</b> : '.$row_liste_rec['intitule_activite_ptba']; ?></td>
<td colspan="7"><span style="background-color:#FF3300; color:#FFFFFF"><b>Aucun indicateur planifi�</b></span></td>
</tr>
 <?php }  ?>
<tr class="even">

  <td colspan="8"><div align="center" style="background-color:#CCCCCC; height: 2px;">&nbsp;</div></td>
</tr>
			<?php }  ?>
            <?php } else { ?>
<!--            <tr>
              <td colspan="7"><div align="center"><span class="Style4"><em><strong>Aucune activit&eacute; enregistr&eacute;e dans la composante <?php //echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?> ! </strong></em></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span></div></td>
            </tr>-->
            <?php }  ?>
      <?php }  } else { ?>
      <tr>
        <td colspan="8" align="center"><strong><em>Aucune composante trouv&eacute;e!</em></strong></td>
      </tr>
      <?php } ?>
</table>

</div>
<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>   <?php if(!isset($_GET["down"])) include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>

</body>
</html>