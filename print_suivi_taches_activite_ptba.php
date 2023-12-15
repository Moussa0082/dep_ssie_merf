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

 if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=taches_activites_ptba.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=taches_activites_ptba.doc"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="p"){

require_once('./tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$PDF_HEADER_TITLE = "Tâches des activités du PTBA";
$PDF_HEADER_STRING = "Tâches des activités du PTBA";

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Ruche');
$pdf->SetTitle($PDF_HEADER_TITLE);
$pdf->SetSubject($PDF_HEADER_STRING);
$pdf->SetKeywords('PDF, mission, Tâches des activités du PTBA');

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
  include("./print_taches_activite_ptba_pdf.php");
  $content = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering

$html = utf8_encode($content);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('taches_activites_ptba.pdf', 'D');
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
$tableauMois=array('01<>Jan<>J','02<>Fev<>F','03<>Mars<>M','04<>Avril<>A','05<>Mai<>M','06<>Juin<>J','07<>Juil<>J','08<>Aout<>A','09<>Sep<>S','10<>Oct<>O','11<>Nov<>N','12<>D&eacute;c<>D');
$tableauMois2= array('Jan','Fev','Mar','Avr','Mai','Juin','Juil','Aout','Sep','Oct','Nov','Dec');
//$tableauMois2= array('J','F','M','A','M','J','J','A','S','O','N','D');
$annee=(isset($_GET['annee']))?$_GET['annee']:date("Y");
//gestion version
      $query_liste_mission = "SELECT * FROM ".$database_connect_prefix."version_ptba WHERE id_version_ptba='$annee'  ";
  try{
    $liste_mission = $pdar_connexion->prepare($query_liste_mission);
    $liste_mission->execute();
    $row_liste_mission = $liste_mission ->fetch();
    $totalRows_liste_mission = $liste_mission->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $annee_version_ptba=$row_liste_mission['annee_ptba'];
  if($row_liste_mission["version_ptba"]==1) $row_liste_mission["version_ptba"]="Initiale"; elseif($row_liste_mission["version_ptba"]==2) $row_liste_mission["version_ptba"]="R&eacute;vis&eacute;e";
  $lib_version_ptba=$row_liste_mission['annee_ptba']." ".$row_liste_mission['version_ptba'];
//$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Ao&ucirc;t","Septembre","Octobre","Novembre","D&eacute;cembre");

 $wheract_periode= $wheract_suivi="";
if(isset($_GET['trim']) && !empty($_GET['trim'])) $trim = $_GET['trim']; else  $trim='%';
$date_t1=$annee_version_ptba."-03-31"; $date_t2=$annee_version_ptba."-06-30"; $date_t3=$annee_version_ptba."-09-30"; $date_t4=$annee_version_ptba."-12-31";
if($trim=="trim1") {$periode=" au ".date("d/m/Y", strtotime($date_t1)); /**/ $wheract_periode="AND groupe_tache.date_fin<='$date_t1'"; $wheract_suivi="AND date_reelle<='$date_t1'";}
elseif($trim=="trim2") {$periode=" au ".date("d/m/Y", strtotime($date_t2)); /**/ $wheract_periode="AND groupe_tache.date_fin<='$date_t2'";  $wheract_suivi="AND date_reelle<='$date_t2'";}
elseif($trim=="trim3") {$periode=" au ".date("d/m/Y", strtotime($date_t3)); /**/ $wheract_periode="AND groupe_tache.date_fin<='$date_t3'";  $wheract_suivi="AND date_reelle<='$date_t3'";}
elseif($trim=="trim4") {$periode=" au ".date("d/m/Y", strtotime($date_t4)); /**/ $wheract_periode="AND groupe_tache.date_fin<='$date_t4'";   $wheract_suivi="AND date_reelle<='$date_t4'";}
else $periode=" ";

$query_edit_ms = "SELECT code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=2 and projet='".$_SESSION["clp_projet"]."' ORDER BY code asc";
  try{
    $edit_ms = $pdar_connexion->prepare($query_edit_ms);
    $edit_ms->execute();
    $row_edit_ms = $edit_ms ->fetchAll();
    $totalRows_edit_ms = $edit_ms->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_prestataire = "SELECT fonction, nom, prenom FROM ".$database_connect_prefix."personnel order by fonction";
  try{
    $liste_prestataire = $pdar_connexion->prepare($query_liste_prestataire);
    $liste_prestataire->execute();
    $row_liste_prestataire = $liste_prestataire ->fetchAll();
    $totalRows_liste_prestataire = $liste_prestataire->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$acteur_array = $Nacteur_array= array();
  if($totalRows_liste_prestataire>0){  foreach($row_liste_prestataire as $row_liste_prestataire1){
    $Nacteur_array[$row_liste_prestataire1["fonction"]] = $row_liste_prestataire1["nom"]." ".$row_liste_prestataire1["prenom"];
	$acteur_array[] = $row_liste_prestataire1["fonction"]."!!".$row_liste_prestataire1["nom"]." ".$row_liste_prestataire1["prenom"];
  }}//while($row_liste_prestataire = mysql_fetch_assoc($liste_prestataire)); 



//if(isset($_GET["acteur"]) && $_GET["acteur"]!=0) {$iactget=$_GET["acteur"]; $wheract_tache="AND ugl='$iactget'"; } else {$wheract_tache=""; $iactget=0;}
$wheract_tache=""; //$iactget=0;
$query_tache = "select id_ptba, id_groupe_tache, groupe_tache.responsable,  code_tache, proportion, intitule_tache, date_debut, date_fin, n_lot, jalon	 FROM ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."ptba where id_ptba=id_activite $wheract_tache $wheract_periode  and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."ptba.annee=$annee group by id_ptba, id_groupe_tache, responsable, code_tache, proportion, intitule_tache, date_debut, date_fin, n_lot	 ORDER BY code_tache ASC";
//echo $query_tache;
//$query_tache = "select * FROM ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."ptba where id_ptba=id_activite and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."ptba.annee=$annee ORDER BY code_tache ASC";
  try{
    $tache = $pdar_connexion->prepare($query_tache);
    $tache->execute();
    $row_tache = $tache ->fetchAll();
    $totalRows_tache2 = $tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
//echo $query_tache;
//exit;
$tache_array = array();
$ttt=0; $maxt=0; $idmaxt=0; if($totalRows_tache2>0) { foreach($row_tache as $row_tache){
$tache_array[$row_tache["id_ptba"]][$row_tache["id_groupe_tache"]] = array("id_ptba"=>$row_tache["id_ptba"],"responsable"=>$row_tache["responsable"],"id_groupe_tache"=>$row_tache["id_groupe_tache"],"code_tache"=>$row_tache["code_tache"],"proportion"=>$row_tache["proportion"],"intitule_tache"=>$row_tache["intitule_tache"],"date_debut"=>$row_tache["date_debut"],"date_fin"=>$row_tache["date_fin"],"n_lot"=>$row_tache["n_lot"],"jalon"=>$row_tache["jalon"]); 	
}   }

//print_r($tache_array);
//exit;

$query_tache_proportion = "SELECT ROUND(avg(proportion)) as total, max(n_lot) as nlotr, id_groupe_tache FROM ".$database_connect_prefix."groupe_tache WHERE  valider=1 $wheract_tache $wheract_periode $wheract_suivi  GROUP BY id_groupe_tache";

 try{
    $tache_proportion = $pdar_connexion->prepare($query_tache_proportion);
    $tache_proportion->execute();
    $row_tache_proportion = $tache_proportion ->fetchAll();
    $totalRows_tache_proportion = $tache_proportion->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$realiser_tache_array =$lot_tache_array = array(); $proportion=0;
if($totalRows_tache_proportion>0){
foreach($row_tache_proportion as $row_tache_proportion){
  $realiser_tache_array[$row_tache_proportion["id_groupe_tache"]] = $row_tache_proportion["total"];
   $lot_tache_array[$row_tache_proportion["id_groupe_tache"]] = $row_tache_proportion["nlotr"];
   } };

$query_total_proportion = "SELECT ROUND(sum(proportion)) as total, id_activite FROM ".$database_connect_prefix."groupe_tache WHERE valider=1 $wheract_tache $wheract_periode $wheract_suivi  GROUP BY id_activite";
 try{
    $total_proportion = $pdar_connexion->prepare($query_total_proportion);
    $total_proportion->execute();
    $row_total_proportion = $total_proportion ->fetchAll();
    $totalRows_total_proportion = $total_proportion->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$prop_tab = array(); //$proportion=0;
if($totalRows_total_proportion>0){ foreach($row_total_proportion as $row_total_proportion){ $prop_tab[$row_total_proportion["id_activite"]] = $row_total_proportion["total"]; } };

$query_tache_cp = "select id_ptba, count(distinct ugl) as crp_c   FROM tache_ugl, ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."ptba where id_groupe_tache=tache and id_ptba=id_activite and tlot>0 $wheract_tache $wheract_periode and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."ptba.annee=$annee group by id_ptba";

 try{
    $tache_cp = $pdar_connexion->prepare($query_tache_cp);
    $tache_cp->execute();
    $row_tache_cp = $tache_cp ->fetchAll();
    $totalRows_tache_cp = $tache_cp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$lot_tache_cp_array = array();
 if($totalRows_tache_cp>0) { foreach($row_tache_cp as $row_tache_cp){ 
$lot_tache_cp_array[$row_tache_cp["id_ptba"]] =$row_tache_cp["crp_c"] ;
} }

$query_liste_taux_tache = "SELECT sum(proportions) as taux_tact, id_ptba FROM
 (SELECT SUM(proportion) as proportions,  id_ptba, code_activite_ptba FROM ptba, groupe_tache WHERE id_ptba=groupe_tache.id_activite and valider=1 and ptba.annee='$annee' $wheract_tache $wheract_periode $wheract_suivi and ptba.projet='".$_SESSION["clp_projet"]."' group by id_ptba, code_activite_ptba) AS alias_sr  group by id_ptba";
 try{
    $liste_taux_tache = $pdar_connexion->prepare($query_liste_taux_tache);
    $liste_taux_tache->execute();
    $row_liste_taux_tache = $liste_taux_tache ->fetchAll();
    $totalRows_liste_taux_tache = $liste_taux_tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$realise_tache = array();
if($totalRows_liste_taux_tache>0){
foreach($row_liste_taux_tache as $row_liste_taux_tache){ 
if(isset($lot_tache_cp_array[$row_liste_taux_tache["id_ptba"]]) && $lot_tache_cp_array[$row_liste_taux_tache["id_ptba"]]>0)  $realise_tache[$row_liste_taux_tache["id_ptba"]]=$row_liste_taux_tache["taux_tact"]/$lot_tache_cp_array[$row_liste_taux_tache["id_ptba"]]; 
 //$realise_arrayas[$row_liste_couta["code"]]=$row_liste_couta["realise"]+$row_liste_couta["engage"]; 
 } } 


// Taux tache
$query_liste_taux_tache_ptba = "SELECT sum(proportions) as taux_tact, id_ptba  FROM
 (SELECT SUM(proportion) as proportions,  id_ptba, code_activite_ptba FROM ptba, groupe_tache WHERE id_ptba=groupe_tache.id_activite and valider=1  $wheract_tache $wheract_periode $wheract_suivi and ptba.annee='$annee' and ptba.projet='".$_SESSION["clp_projet"]."' group by id_ptba, code_activite_ptba) AS alias_sr  group by id_ptba";
 try{
    $liste_taux_tache_ptba = $pdar_connexion->prepare($query_liste_taux_tache_ptba);
    $liste_taux_tache_ptba->execute();
    $row_liste_taux_tache_ptba = $liste_taux_tache_ptba ->fetchAll();
    $totalRows_liste_taux_tache_ptba = $liste_taux_tache_ptba->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$ttache_tab = array();
if($totalRows_liste_taux_tache_ptba>0){
foreach($row_liste_taux_tache_ptba as $row_liste_taux_tache_ptba){ 
if(isset($lot_tache_cp_array[$row_liste_taux_tache_ptba["id_ptba"]])) $ttache_tab[$row_liste_taux_tache_ptba["id_ptba"]]=$row_liste_taux_tache_ptba["taux_tact"]/$lot_tache_cp_array[$row_liste_taux_tache_ptba["id_ptba"]];
}} 

/**/

// Taux tache
/*$query_liste_taux_tache_act = "SELECT id_ptba, code_activite_ptba, sum(tsuivi)/avg(tcible) as tauxx FROM (SELECT ptba.id_ptba, code_activite_ptba, groupe_tache.id_groupe_tache, count(distinct ugl) AS tcible
FROM ptba INNER JOIN (groupe_tache INNER JOIN tache_ugl ON groupe_tache.id_groupe_tache= tache_ugl.tache
) ON ptba.id_ptba = groupe_tache.id_activite where ptba.annee='$annee' $wheract_tache $wheract_periode  and tlot>0 and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY ptba.id_ptba, code_activite_ptba, groupe_tache.id_groupe_tache) AS cible
INNER JOIN (SELECT groupe_tache.id_groupe_tache, SUM(s.proportion) AS tsuivi FROM ptba INNER JOIN (groupe_tache
LEFT JOIN suivi_tache s ON groupe_tache.id_groupe_tache= s.id_tache) ON ptba.id_ptba = groupe_tache.id_activite where s.valider=1 and ptba.annee='$annee' $wheract_tache  $wheract_suivi and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY groupe_tache.id_groupe_tache) AS suivi ON cible.id_groupe_tache= suivi.id_groupe_tache GROUP BY id_ptba";*/

$query_liste_taux_tache_act = "SELECT ROUND(SUM(if(n_lot>0, proportion*jalon/n_lot,0))) as total, id_activite FROM ".$database_connect_prefix."groupe_tache WHERE valider=1 $wheract_tache  $wheract_suivi  GROUP BY id_activite";
 try{
    $liste_taux_tache_act = $pdar_connexion->prepare($query_liste_taux_tache_act);
    $liste_taux_tache_act->execute();
    $row_liste_taux_tache_act = $liste_taux_tache_act ->fetchAll();
    $totalRows_liste_taux_tache_act = $liste_taux_tache_act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$ttache_act_tab = array();
if($totalRows_liste_taux_tache_act>0){
foreach($row_liste_taux_tache_act as $row_liste_taux_tache_act){  $ttache_act_tab[$row_liste_taux_tache_act["id_activite"]]=$row_liste_taux_tache_act["total"];
}} 

//print_r($ttache_act_tab);
//exit;

// Taux tache
/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_taux_tache_cp = "select avg(if(tauxx>0,tauxx,0)) as taux_cp, left(code_activite_ptba,1) as cp from (SELECT id_ptba, code_activite_ptba, sum(tsuivi)/avg(tcible) as tauxx FROM (SELECT ptba.id_ptba, code_activite_ptba, groupe_tache.id_groupe_tache, count(distinct ugl) AS tcible
FROM ptba INNER JOIN (groupe_tache INNER JOIN tache_ugl ON groupe_tache.id_groupe_tache= tache_ugl.tache
) ON ptba.id_ptba = groupe_tache.id_activite where ptba.annee='$annee' $wheract_tache $wheract_periode  and tlot>0 and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY ptba.id_ptba, code_activite_ptba, groupe_tache.id_groupe_tache) AS cible
left JOIN (SELECT groupe_tache.id_groupe_tache, SUM(s.proportion) AS tsuivi FROM ptba INNER JOIN (groupe_tache
LEFT JOIN suivi_tache s ON groupe_tache.id_groupe_tache= s.id_tache  ) ON ptba.id_ptba = groupe_tache.id_activite where ptba.annee='$annee' $wheract_tache $wheract_suivi and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY groupe_tache.id_groupe_tache) AS suivi ON cible.id_groupe_tache= suivi.id_groupe_tache GROUP BY id_ptba) as r1 group by cp";

$liste_taux_tache_cp  = mysql_query_ruche($query_liste_taux_tache_cp , $pdar_connexion) or die(mysql_error());
$row_liste_taux_tache_cp  = mysql_fetch_assoc($liste_taux_tache_cp);
$totalRows_liste_taux_tache_cp  = mysql_num_rows($liste_taux_tache_cp);
$ttache_cp_tab = array();
if($totalRows_liste_taux_tache_cp>0){
do{ $ttache_cp_tab[$row_liste_taux_tache_cp["cp"]]=$row_liste_taux_tache_cp["taux_cp"];
}while($row_liste_taux_tache_cp  = mysql_fetch_assoc($liste_taux_tache_cp));} */

if(isset($_GET["acteur"]) && $_GET["acteur"]!="" && $_GET["acteur"]==0) 
$query_liste_cout_saisi = "SELECT activite, SUM( if(observation>0, observation,0) ) AS montant  FROM part_bailleur where  annee=$annee and projet='".$_SESSION["clp_projet"]."'  group by activite";
else
$query_liste_cout_saisi = "SELECT activite, SUM( if(montant>0, montant,0) ) AS montant  FROM part_bailleur where  annee=$annee and projet='".$_SESSION["clp_projet"]."'  group by activite";
 try{
    $liste_cout_saisi = $pdar_connexion->prepare($query_liste_cout_saisi);
    $liste_cout_saisi->execute();
    $row_liste_cout_saisi = $liste_cout_saisi ->fetchAll();
    $totalRows_liste_cout_saisi = $liste_cout_saisi->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$tableauCoutSaisi=array();
if($totalRows_liste_cout_saisi>0){ foreach($row_liste_cout_saisi as $row_liste_cout_saisi){ 
$tableauCoutSaisi[$row_liste_cout_saisi["activite"]]=$row_liste_cout_saisi["montant"];
}  }

//$query_g_tache = "select * FROM type_tache where type_activite='$cat' ORDER BY ordre ASC";
$query_g_tache= "SELECT tache, sum(tlot) as tlot FROM tache_ugl group by tache ";
 try{
    $g_tache = $pdar_connexion->prepare($query_g_tache);
    $g_tache->execute();
    $row_g_tache = $g_tache ->fetchAll();
    $totalRows_g_tache = $g_tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$nb_lot_tab =$proport_tache= array();
if($totalRows_tache2>0){
foreach($row_g_tache as $row_g_tache){ 
$nb_lot_tab[$row_g_tache["tache"]]=$row_g_tache["tlot"];
}} 

if(isset($_GET["acteur"]) && $_GET["acteur"]!="") {$iactget=$_GET["acteur"]; $wheract="AND fin=$iactget"; } else {$wheract=""; $iactget="";}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print $config->sitename; ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <?php if(!isset($_GET["down"])){  ?>
  <meta name="keywords" content="<?php print $config->MetaKeys; ?>" />
  <meta name="description" content="<?php print $config->MetaDesc; ?>" />
  <meta name="author" content="<?php print $config->MetaAuthor; ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/login.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
  <link href='<?php print $config->theme_folder; ?>/css.css' rel='stylesheet' type='text/css'>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/lodash.compat.min.js"></script>
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
 <?php }  ?>
</head>

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
} .table tbody tr td {vertical-align: middle; } .marquer{background: #EBEBEB!important; }
</style>
<div class="contenu">
<?php if(!isset($_GET["down"])){  ?>
<!--<form name="form<?php //echo $annee; ?>1" id="form<?php //echo $annee; ?>1" method="get" action="<?php //echo "print_suivi_taches_activite_ptba.php?annee=".$annee; ?>" class="pull-left">

<select name="acteur" onchange="form<?php //echo $annee; ?>1.submit();" style="background-color: #FFFF00; padding: 7px;" class="btn p11">

              <option value="">-- Choisissez une partie --</option>
			 <option value="0">PARTENAIRES</option>
			 <option value="1">PDAIG uniquement</option>
            <!--<?php 	  //if($totalRows_liste_prestataire>0) { foreach($row_g_tache as $row_g_tache){ 
?>
            <option <?php //if(isset($id_ms) && $id_ms==$row_liste_prestataire['id_acteur']) {echo 'SELECTED="selected"';  $nom=$row_liste_prestataire['objet'];}  ?> value="<?php //echo  $row_liste_prestataire['code_ugl']; ?>"> <?php //echo "<b>".$row_liste_prestataire['nom_ugl']."</b> ";?>
            </option>
            <?php //}} ?>
 <option value="">Toutes les activit�s</option>
  </select>
  <input type="hidden" name="annee" value="<?php //echo $annee; ?>" />

</form>-->

<form name="form<?php echo $annee; ?>" id="form<?php echo $annee; ?>" method="get" action="<?php echo "print_suivi_taches_activite_ptba.php?annee=".$annee; ?>" class="pull-left"> P&eacute;riode :&nbsp;

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

<div class="well well-sm r_float"><div class="r_float"><a href="./s_programmation.php" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a target="_blank" title="Imprimer" href="<?php echo $editFormAction."&down=1"; ?>" class="button"><img src="./images/print.png" width='20' height='20' alt='Modifier' /></a></div>
</div>
<div class="clear h0">&nbsp;</div>
<?php } else { ?>

<center><?php //include "./includes/print_header.php"; ?></center>

<?php } ?>
<div class="well well-sm"><strong>SUIVI DES T&Acirc;CHES DES ACTIVITES DU PTBA <?php echo "$lib_version_ptba"; ?>&nbsp;&nbsp; <span style="background-color:#FFCC33">  <?php if(isset($_GET["acteur"]) && $_GET["acteur"]==0 && $_GET["acteur"]!="") echo  "&nbsp;(<u>Partenaires</u>)&nbsp;"; elseif(isset($_GET["acteur"]) && $_GET["acteur"]==1) echo  "&nbsp;(<u>PDAIG</u>)&nbsp;"; ?></span><?php echo $periode; ?></strong></div> 

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive">
            <thead>
            <tr>
              <th align="left">ACTIVITES</th>
			  <?php //if(!isset($_GET["down"])){  ?>
              <th align="left">TACHES</th>
              <th align="left">Responsable</th>
              <th align="left">Date butoir </th>
              <th >PROPORTION</th>
              <th >Lot pr&eacute;vu </th>
              <th >Lot R&eacute;alis&eacute;</th>
			  <?php //}  ?>
              <th nowrap="nowrap" ><div align="center">Taux (%) </div></th>
              <?php //foreach($tableauMois2 as $vmois){
             // $amois = explode('<>',$vmois); ?>
              <th align="center">Co&ucirc;t 
                <?php if(isset($_SESSION["clp_monnaie"])) echo "(".$_SESSION["clp_monnaie"].")"; ?>
             </th>
              <?php //} ?>
            </tr>
            </thead>
<?php  $totalptba=$totalcoutptba=$taux_cumulptba=0; if($totalRows_edit_ms>0) { foreach($row_edit_ms as $row_edit_ms){ 
$code = $row_edit_ms['code']; 
//if(isset($_GET["acteur"]) && $_GET["acteur"]!=0) {$iactget=$_GET["acteur"]; $wheract="AND FIND_IN_SET('$iactget', region)"; } else $wheract="";
$query_liste_rec = "SELECT ptba.* FROM ".$database_connect_prefix."ptba where  ptba.projet='".$_SESSION["clp_projet"]."' and ptba.annee='$annee' and ptba.code_activite_ptba like '$code%' $wheract ORDER By ptba.code_activite_ptba asc";
//echo $query_liste_rec ;
 try{
    $liste_rec = $pdar_connexion->prepare($query_liste_rec);
    $liste_rec->execute();
    $row_liste_rec = $liste_rec ->fetchAll();
    $totalRows_liste_rec = $liste_rec->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//echo  $totalRows_liste_rec ;
?>
            <tr bgcolor="#BED694">
              <td <?php  echo 'colspan="9"';  ?> align="center" style="background-color: #BED694;">
                <div align="left"><b><?php echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?></b> </div></td>
            </tr>
		<?php	if($totalRows_liste_rec>0 && $totalRows_tache2>0) {$i=0; $t=0; $p2=$p1="j"; ?>

<?php $row=""; $totalcout=$taux_cumul=0; $nacttache=0; foreach($row_liste_rec as $row_liste_rec){  $code_act = $row_liste_rec['id_ptba']; $nacttache++; $totalptba++; if(isset($tache_array[$code_act])){  if(isset($tableauCoutSaisi[$row_liste_rec["id_ptba"]]))  $cout_saisi=$tableauCoutSaisi[$row_liste_rec["id_ptba"]]; else $cout_saisi=0; $totalcout=$totalcout+$cout_saisi; 

$txpre=0; foreach($tache_array[$code_act] as $a=>$b){ ?>
            <tr>
<?php if($row!=$b["id_ptba"]){ $a = explode(",", $row_liste_rec['region']); ?>
<td rowspan="<?php echo count($tache_array[$code_act]); ?>"><?php echo "<b>".$row_liste_rec['code_activite_ptba'].'</b> : '.$row_liste_rec['intitule_activite_ptba']; //." /".count($a); ?></td>
<?php } ?>
<?php //if(!isset($_GET["down"])){  ?>
<td><?php echo $b['intitule_tache']; ?></td>
<td><?php echo $b['responsable']; ?></td>
<td nowrap="nowrap" <?php if(date("Y-m-d")>$b['date_fin'] && !isset($realiser_tache_array[$b['id_groupe_tache']])) { ?> style="background-color:#CC0000; color:#FFFFFF;"<?php } ?>><b><?php echo date("d/m/y", strtotime($b['date_fin']));  ?></b></td>
<td width="50" align="center"><?php echo $b['proportion'].'%'; ?></td>
<td width="50" align="center"><?php if(isset($b['n_lot'])) echo $b['n_lot']; ?></td>
<td width="50" align="center"><?php if(isset($b['n_lot']) && isset($realiser_tache_array[$b['id_groupe_tache']])) echo $b['jalon']; ?></td>
<?php //} ?>
<?php if($row!=$b["id_ptba"]){ $id_act=$b["id_ptba"]; ?>
<td rowspan="<?php echo count($tache_array[$code_act]); ?>">
<div align="center"><?php //suivi tache

$color = "danger";

$tauxp=0;

 if(isset($ttache_act_tab[$id_act]))  { $tauxp=$ttache_act_tab[$id_act]; if($tauxp<99) $color = "warning";  elseif($tauxp>=99) $color = "success"; }
  elseif(in_array($id_act,$ttache_act_tab)){ $ttache_act_tab[$id_act]  = 0; $color = "warning"; } ?>

<div> <span id="stat_<?php echo $annee.$row_act['id_ptba'];  ?>" >
<div class="progress"> <div class="progress-bar progress-bar-<?php echo $color; ?>" style="width: <?php  echo "100"; ?>%; background-position:center"><?php if(isset($ttache_act_tab[$id_act]))  { echo number_format($ttache_act_tab[$id_act], 0, ',', ' ')." %"; $taux_cumul=$taux_cumul+($ttache_act_tab[$id_act]*1); } else echo "0%"; ?></div> </div>
</span> </div></div></td>
<?php } ?>
<?php //foreach($tableauMois as $vmois){
//$amois = explode('<>',$vmois);  $tab_debut=explode("-",$b['date_debut']); $md=$tab_debut[1]; $tab_fin=explode("-",$b['date_fin']); $mf=$tab_fin[1]; //echo $md; exit; ?>
<?php if($row!=$b["id_ptba"]){ ?>
<td rowspan="<?php if(isset($tache_array[$code_act])) echo count($tache_array[$code_act]); else echo 1; ?>" nowrap="nowrap"><?php  echo number_format($cout_saisi, 0, ',', ' '); //echo "<br/>".$taux_cumul;  ?></td>
<?php } ?>
<?php //} ?>
            </tr>
			
           <?php $row=$b["id_ptba"]; }} else { ?>
						<tr >
  <td colspan="1"><div align="left"><?php echo "<b>".$row_liste_rec['code_activite_ptba'].'</b> : '.$row_liste_rec['intitule_activite_ptba']; ?></div></td>
    <td colspan="7"><div align="center"><?php echo "<b>Aucune t&acirc;che programm&eacute;e</b>"  ?></div></td>
	<td colspan="1" nowrap="nowrap"><div align="center"><?php  if(isset($tableauCoutSaisi[$row_liste_rec["id_ptba"]]))  {echo number_format($tableauCoutSaisi[$row_liste_rec["id_ptba"]], 0, ',', ' ');  $totalcout=$totalcout+$tableauCoutSaisi[$row_liste_rec["id_ptba"]];} else echo 0;  ?></div></td>
</tr>
			 <?php  } ?>
		
			 <?php }  ?>
			  <tr>
        <td <?php  echo 'colspan="7"';  ?> align="center"><div align="right" style="background-color:#CCCCCC"><b>Taux d'avancement <?php echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?></b></div></td>
        <td align="center" style="background-color: #BED694;"><b><?php if($nacttache>0) echo number_format($taux_cumul/$nacttache, 2, ',', ' ')."%"; //if(isset($ttache_cp_tab[$row_edit_ms["code"]])) echo number_format($ttache_cp_tab[$row_edit_ms["code"]], 0, ',', ' ')." %"; else echo "-"; ?></b></td>
        <td align="center" nowrap="nowrap"><?php  echo number_format($totalcout, 0, ',', ' '); $totalcoutptba=$totalcoutptba+$totalcout; $taux_cumulptba=$taux_cumulptba+$taux_cumul; //echo "<br/>".$taux_cumul;  ?></td>
			  </tr>
           <?php } else { ?>
		    <tr>
        <td <?php  echo 'colspan="9"';  ?> align="center"><strong><em>Aucune activit&eacute; planifi&eacute;e!</em></strong></td>
      </tr>
            <?php }  ?>
      <?php }  } else { ?>
      <tr>
        <td <?php echo 'colspan="9"';  ?> align="center"><strong><em>Aucune composante trouv&eacute;e!</em></strong></td>
      </tr>
      <?php } ?>
  <tr>
        <td <?php  echo 'colspan="7"';  ?> align="center"><div align="right" style="background-color:#FFFF33"><b>Taux d'avancement PTBA <?php echo "$lib_version_ptba"; ?></b></div></td>
        <td align="center" style="background-color: #FFFF33;"><b><?php if($totalptba>0) echo number_format($taux_cumulptba/$totalptba, 2, ',', ' ')."%";  ?></b></td>
        <td align="center" nowrap="nowrap"><b><?php  echo number_format($totalcoutptba, 0, ',', ' '); //echo "<br/>".$taux_cumul;  ?></b></td>
			  </tr>
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