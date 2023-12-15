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
header('Content-Type: text/html; charset=UTF-8');

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
$array_indic = array("OUI/NON","texte");
if(isset($_GET["acteur"])) {$iactget=$_GET["acteur"]; $wheract="AND FIND_IN_SET('$iactget', ptba.region)"; $whercible="and cible_indicateur_trimestre.region='$iactget'"; $whersuivi="and suivi_indicateur_tache.ugl='$iactget'"; } else {$wheract=""; $whercible=$whersuivi="";}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_ms = "SELECT code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=1 and projet='".$_SESSION["clp_projet"]."' ORDER BY code asc";
$edit_ms = mysql_query_ruche($query_edit_ms, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_ms = mysql_fetch_assoc($edit_ms);
$totalRows_edit_ms = mysql_num_rows($edit_ms);

mysql_select_db($database_pdar_connexion, $pdar_connexion); //code_activite='$code_act' and annee=$annee and
$query_indicateur = "SELECT * FROM ptba, ".$database_connect_prefix."indicateur_tache where id_ptba=id_activite and annee=$annee $wheract and projet='".$_SESSION["clp_projet"]."' ORDER BY id_activite asc";
$indicateur  = mysql_query_ruche($query_indicateur , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_indicateur = mysql_fetch_assoc($indicateur);
$totalRows_indicateur  = mysql_num_rows($indicateur);
$indicateur_array = $unite_array = array();
if($totalRows_indicateur>0) { do {
$indicateur_array[$row_indicateur["id_activite"]][$row_indicateur["id_indicateur_tache"]] = $row_indicateur;
$unite_array[$row_indicateur["id_indicateur_tache"]] = $row_indicateur["unite"];
} while ($row_indicateur = mysql_fetch_assoc($indicateur));  }

//semestre precedent
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cible_indicateur = "SELECT * FROM cible_indicateur_trimestre, indicateur_tache, ptba where id_ptba=id_activite  $whercible and 	id_indicateur_tache=indicateur and annee=$annee and projet='".$_SESSION["clp_projet"]."' $wheract ";
$cible_indicateur  = mysql_query_ruche($query_cible_indicateur , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_cible_indicateur = mysql_fetch_assoc($cible_indicateur );
$totalRows_cible_indicateur = mysql_num_rows($cible_indicateur );
$tableau_cible_indicateur_array = array();
  if($totalRows_cible_indicateur>0){  do{
    if(!isset($tableau_cible_indicateur_array[$row_cible_indicateur["indicateur"]][$row_cible_indicateur["trimestre"]]))
    $tableau_cible_indicateur_array[$row_cible_indicateur["indicateur"]][$row_cible_indicateur["trimestre"]] = $row_cible_indicateur[(!in_array($unite_array[$row_cible_indicateur["indicateur"]],$array_indic))?"cible":"cible_txt"];
    else
    $tableau_cible_indicateur_array[$row_cible_indicateur["indicateur"]][$row_cible_indicateur["trimestre"]] += $row_cible_indicateur[(!in_array($unite_array[$row_cible_indicateur["indicateur"]],$array_indic))?"cible":"cible_txt"];
  }while($row_cible_indicateur = mysql_fetch_assoc($cible_indicateur));  }


  //PTBA
/*  $appendice_array =array();
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_appendice4 = "SELECT intitule_indicateur_tache,code_activite_ptba,intitule_activite_ptba,id_indicateur_tache, unite FROM ".$database_connect_prefix."ptba, ".$database_connect_prefix."indicateur_tache where code_activite_ptba=code_activite and code_activite_ptba='$code_act' and ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' ORDER BY code_activite_ptba,intitule_indicateur_tache";
  $appendice4  = mysql_query_ruche($query_appendice4 , $pdar_connexion) or die(mysql_error());
  $row_appendice4  = mysql_fetch_assoc($appendice4);
  $totalRows_appendice4  = mysql_num_rows($appendice4);
  if($totalRows_appendice4>0){ do{
  $appendice_array[$row_appendice4["id_indicateur_tache"]]=$row_appendice4["intitule_indicateur_tache"]."|".$row_appendice4["unite"];  }while($row_appendice4  = mysql_fetch_assoc($appendice4)); }  */
/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_classeur = "SELECT * FROM ".$database_connect_prefix."classeur ";
$liste_classeur = mysql_query_ruche($query_liste_classeur, $pdar_connexion) or die(mysql_error());
$row_liste_classeur = mysql_fetch_assoc($liste_classeur);
$totalRows_liste_classeur = mysql_num_rows($liste_classeur);
$liste_classeur_array = $classeur_color_array = array();
if($totalRows_liste_classeur>0){  do{
//$liste_classeur_array[$row_liste_classeur["id_classeur"]]=$row_liste_classeur["libelle"];
$classeur_color_array[$row_liste_classeur["id_classeur"]]=$row_liste_classeur["couleur"];
}while($row_liste_classeur  = mysql_fetch_assoc($liste_classeur));  }

//dynamique
mysql_select_db($database_pdar_connexion, $pdar_connexion);
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

mysql_select_db($database_pdar_connexion, $pdar_connexion);
//$query_liste_ind_ptba = "SELECT unite, id_indicateur_tache, intitule_indicateur_tache, code_indicateur_ptba, ".$database_connect_prefix."indicateur_tache.id_activite FROM ".$database_connect_prefix."indicateur_tache, ".$database_connect_prefix."cible_indicateur_trimestre where ".$database_connect_prefix."indicateur_tache.code_activite=".$database_connect_prefix."cible_indicateur_trimestre.activite and ".$database_connect_prefix."indicateur_tache.projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."cible_indicateur_trimestre.projet='".$_SESSION["clp_projet"]."' and id_indicateur_tache=indicateur group by ".$database_connect_prefix."indicateur_tache.code_activite,id_indicateur_tache";

$query_liste_ind_ptba = "SELECT indicateur_tache.unite, id_indicateur_tache, intitule_indicateur_tache, code_indicateur_ptba, indicateur_tache.id_activite FROM cible_indicateur_trimestre, indicateur_tache, ptba where id_ptba=id_activite $whercible and 	id_indicateur_tache=indicateur and annee=$annee and projet='".$_SESSION["clp_projet"]."' $wheract ";
$liste_ind_ptba  = mysql_query_ruche($query_liste_ind_ptba , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ptba  = mysql_fetch_assoc($liste_ind_ptba);
$totalRows_liste_ind_ptba  = mysql_num_rows($liste_ind_ptba);

//Valeur cible
mysql_select_db($database_pdar_connexion, $pdar_connexion);
//$query_cible = "SELECT sum(cible) as valeur_cible, avg(cible) as valeur_cible_avg, activite as id_activite, indicateur FROM ".$database_connect_prefix."cible_indicateur_trimestre where projet='".$_SESSION["clp_projet"]."' and projet='".$_SESSION["clp_projet"]."' group by id_activite,indicateur";

$query_cible = "SELECT sum(cible) as valeur_cible, avg(cible) as valeur_cible_avg, id_ptba as id_activite, indicateur FROM cible_indicateur_trimestre, indicateur_tache, ptba where id_ptba=id_activite  $whercible and 	id_indicateur_tache=indicateur and annee=$annee and projet='".$_SESSION["clp_projet"]."' $wheract  group by id_activite, indicateur";
//echo $query_cible;
//exit;
$cible  = mysql_query_ruche($query_cible , $pdar_connexion) or die(mysql_error());
$row_cible  = mysql_fetch_assoc($cible);
$totalRows_cible  = mysql_num_rows($cible);
$row_cible_ind = array();
if($totalRows_cible>0){ do{ $id = $row_cible["indicateur"]; $id_act = $row_cible["id_activite"];
  if(isset($row_cible_ind["sum"][$id_act][$id])) $row_cible_ind["sum"][$id_act][$id] += $row_cible["valeur_cible"];
  else $row_cible_ind["sum"][$id_act][$id] = $row_cible["valeur_cible"];
  if(isset($row_cible_ind["avg"][$id_act][$id])) $row_cible_ind["avg"][$id_act][$id] += $row_cible["valeur_cible_avg"];
  else $row_cible_ind["avg"][$id_act][$id] = $row_cible["valeur_cible_avg"];
}while($row_cible  = mysql_fetch_assoc($cible)); }

/*
//Valeur reelle
mysql_select_db($database_pdar_connexion, $pdar_connexion);
//$query_valeur_suivi_ind = "SELECT sum(valeur_suivi) as valeur_reelle, avg(valeur_suivi) as valeur_reelle_avg, indicateur  FROM ".$database_connect_prefix."suivi_indicateur_tache where projet='".$_SESSION["clp_projet"]."' group by indicateur ";
$query_valeur_suivi_ind = "SELECT sum(valeur_suivi) as valeur_reelle, avg(valeur_suivi) as valeur_reelle_avg, indicateur FROM suivi_indicateur_tache, indicateur_tache, ptba where id_ptba=id_activite   $whersuivi	and id_indicateur_tache=indicateur and annee=$annee and projet='".$_SESSION["clp_projet"]."' $wheract group by indicateur";
//echo $query_valeur_suivi_ind;
//exit;
$valeur_suivi_ind  = mysql_query_ruche($query_valeur_suivi_ind , $pdar_connexion) or die(mysql_error());
$row_valeur_suivi_ind  = mysql_fetch_assoc($valeur_suivi_ind);
$totalRows_valeur_suivi_ind  = mysql_num_rows($valeur_suivi_ind);
$row_suivi_ind = array();
if($totalRows_valeur_suivi_ind>0){ do{ $id = $row_valeur_suivi_ind["indicateur"];
  if(isset($row_suivi_ind["sum"][$id])) $row_suivi_ind["sum"][$id] += $row_valeur_suivi_ind["valeur_reelle"];
  else $row_suivi_ind["sum"][$id] = $row_valeur_suivi_ind["valeur_reelle"];
  if(isset($row_suivi_ind["avg"][$id])) $row_suivi_ind["avg"][$id] += $row_valeur_suivi_ind["valeur_reelle_avg"];
  else $row_suivi_ind["avg"][$id] = $row_valeur_suivi_ind["valeur_reelle_avg"];
}while($row_valeur_suivi_ind  = mysql_fetch_assoc($valeur_suivi_ind)); }

//Valeur reelle textuel
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_valeur_suivi_ind = "SELECT valeur_txt as valeur_reelle, indicateur  FROM ".$database_connect_prefix."suivi_indicateur_tache where projet='".$_SESSION["clp_projet"]."' and valeur_txt is not null group by indicateur ";
$valeur_suivi_ind  = mysql_query_ruche($query_valeur_suivi_ind , $pdar_connexion) or die(mysql_error());
$row_valeur_suivi_ind  = mysql_fetch_assoc($valeur_suivi_ind);
$totalRows_valeur_suivi_ind  = mysql_num_rows($valeur_suivi_ind);
//$row_suivi_ind = array();
if($totalRows_valeur_suivi_ind>0){ do{ $id = $row_valeur_suivi_ind["indicateur"];
  $row_suivi_ind["sum"][$id] = $row_valeur_suivi_ind["valeur_reelle"];
}while($row_valeur_suivi_ind  = mysql_fetch_assoc($valeur_suivi_ind)); }   */
/*
$tauxG=$tauxT=$taux_tache=$valeur_tache=array();  $taux_progress = $tauxGG = 0;

if($totalRows_liste_ind_ptba>0) {$m=0;do {
//Cible
$row_suivi_ind['valeur_reelle']="-";
$id_ind=$row_liste_ind_ptba['id_activite'];
$id_ind_tache=$row_liste_ind_ptba['id_indicateur_tache'];
$unite=$row_liste_ind_ptba['unite']; $fn = ($unite=="%")?'avg':'sum';
if(!isset($tauxG[$id_ind])) $tauxG[$id_ind] = 0; if(!isset($tauxT[$id_ind])) $tauxT[$id_ind] = 0;
if(!isset($taux_tache[$id_ind_tache])) $taux_tache[$id_ind_tache] = 0;
if(!isset($valeur_tache[$id_ind_tache])) $valeur_tache[$id_ind_tache] = 0;
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cible_ind = "SELECT $fn(cible) as valeur_cible FROM ".$database_connect_prefix."cible_indicateur_trimestre where indicateur='$id_ind_tache' and projet='".$_SESSION["clp_projet"]."' and id_activite='$id_ind' and projet='".$_SESSION["clp_projet"]."' group by indicateur";
$cible_ind  = mysql_query_ruche($query_cible_ind , $pdar_connexion) or die(mysql_error());
$row_cible_ind  = mysql_fetch_assoc($cible_ind);
$totalRows_cible_ind  = mysql_num_rows($cible_ind);  */
//suivi indicateur
/*
if(in_array($id_ind_tache,array_keys($indicateur_dynamique)))
{
  $ind_dyn = $indicateur_dynamique[$id_ind_tache];
  $row_suivi_ind['valeur_reelle'] = $ind_dyn["val"];
  $totalRows_suivi_ind = 1;
}
elseif(isset($row_suivi_ind["sum"][$id_ind_tache]))
{
  /*mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_suivi_ind = "SELECT sum(valeur_suivi) as valeur_reelle  FROM ".$database_connect_prefix."suivi_indicateur_tache where indicateur='$id_ind_tache' and projet='".$_SESSION["clp_projet"]."' group by indicateur ";
  $suivi_ind  = mysql_query_ruche($query_suivi_ind , $pdar_connexion) or die(mysql_error());
  $row_suivi_ind  = mysql_fetch_assoc($suivi_ind);
  $totalRows_suivi_ind  = mysql_num_rows($suivi_ind);  */
/*  $totalRows_suivi_ind = 1;
  $row_suivi_ind['valeur_reelle'] = $row_suivi_ind["sum"][$id_ind_tache];
  if(in_array($unite,$array_indic)){ $totalRows_liste_ind_ptba--; $tauxT[$id_ind]--; }
}

if(in_array($unite,$array_indic)){ $valeur_tache[$id_ind_tache] = $row_suivi_ind["sum"][$id_ind_tache]; }
elseif(isset($row_suivi_ind['valeur_reelle'])) $valeur_tache[$id_ind_tache] = $row_suivi_ind['valeur_reelle'];
$taux = 0;
/*if(isset($row_cible_ind['valeur_cible']) && $row_cible_ind['valeur_cible']>0  && $totalRows_suivi_ind>0) {$taux=100*$row_suivi_ind['valeur_reelle']/$row_cible_ind['valeur_cible']; $taux = ($taux>100)?100:$taux;  }*/
//echo  "a=".$id_ind." i=".$id_ind_tache." t=".$row_cible_ind[$fn][$id_ind][$id_ind_tache];
//echo $row_suivi_ind['valeur_reelle'];
//echo "</br>";
/*if(isset($row_cible_ind[$fn][$id_ind][$id_ind_tache]) && $row_cible_ind[$fn][$id_ind][$id_ind_tache]>0  && isset($totalRows_suivi_ind) && $totalRows_suivi_ind>0) {$taux=100*$row_suivi_ind['valeur_reelle']/$row_cible_ind[$fn][$id_ind][$id_ind_tache]; $taux = ($taux>100)?100:$taux;  }
$tauxG[$id_ind] += $taux; $tauxGG+=$taux; $taux_tache[$id_ind_tache] = $taux;
$tauxT[$id_ind] += 1;
} while ($row_liste_ind_ptba = mysql_fetch_assoc($liste_ind_ptba));}
if($totalRows_liste_ind_ptba>0) $taux_progress = $tauxGG/$totalRows_liste_ind_ptba;*/
//exit;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_prestataire = "SELECT * FROM ".$database_connect_prefix."ugl order by code_ugl";
$liste_prestataire = mysql_query_ruche($query_liste_prestataire, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_prestataire = mysql_fetch_assoc($liste_prestataire);
$totalRows_liste_prestataire = mysql_num_rows($liste_prestataire);
$acteur_array = $Nacteur_array= array();
  if($totalRows_liste_prestataire>0){  do{
    $Nacteur_array[$row_liste_prestataire["code_ugl"]] = $row_liste_prestataire["nom_ugl"];
	$acteur_array[] = $row_liste_prestataire["code_ugl"]."!!".$row_liste_prestataire["nom_ugl"];
  }//while($row_liste_prestataire = mysql_fetch_assoc($liste_prestataire));  
  
  while ($row_liste_prestataire = mysql_fetch_assoc($liste_prestataire));
  $rows = mysql_num_rows($liste_prestataire);
  if($rows > 0) {
      mysql_data_seek($liste_prestataire, 0);
	  $row_liste_prestataire = mysql_fetch_assoc($liste_prestataire);
  }}
  
  $uglprojet=str_replace("|",",",$_SESSION["clp_projet_ugl"]);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ugl = "SELECT distinct code_departement, abrege_departement, nom_departement FROM ".$database_connect_prefix."departement order by code_departement";
$liste_ugl  = mysql_query($query_liste_ugl , $pdar_connexion) or die(mysql_error());
$row_liste_ugl  = mysql_fetch_assoc($liste_ugl);
$totalRows_liste_ugl  = mysql_num_rows($liste_ugl); 
$tableauDepartement = array(); $nbregi=0;
if(isset($totalRows_liste_ugl) && $totalRows_liste_ugl>0) {
do{
  $tableauDepartement[] = $row_liste_ugl['code_departement']."<>".$row_liste_ugl['nom_departement']; $nbregi=$nbregi+1;
}while($row_liste_ugl = mysql_fetch_assoc($liste_ugl));}

//Valeur reelle
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_valeur_suivi_ind_dep = "SELECT sum(valeur_suivi) as valeur_reelle, avg(valeur_suivi) as valeur_reelle_avg, indicateur, departement FROM suivi_indicateur_tache, commune where code_commune=commune and indicateur in (select id_indicateur_tache from indicateur_tache, ptba where id_ptba=id_activite and annee=$annee and projet='".$_SESSION["clp_projet"]."' $wheract) $whersuivi group by indicateur, departement";
$valeur_suivi_ind_dep  = mysql_query_ruche($query_valeur_suivi_ind_dep , $pdar_connexion) or die(mysql_error());
$row_valeur_suivi_ind_dep  = mysql_fetch_assoc($valeur_suivi_ind_dep);
$totalRows_valeur_suivi_ind_dep  = mysql_num_rows($valeur_suivi_ind_dep);
$tableauValDepartement = array(); //$nbregi=0;
if(isset($totalRows_liste_ugl) && $totalRows_liste_ugl>0){
do{

  $tableauValDepartement[$row_valeur_suivi_ind_dep['indicateur']][$row_valeur_suivi_ind_dep['departement']] = $row_valeur_suivi_ind_dep['valeur_reelle'];
}while($row_valeur_suivi_ind_dep = mysql_fetch_assoc($valeur_suivi_ind_dep));}
/*
echo $query_valeur_suivi_ind_dep;
print_r($tableauValDepartement);
exit;*/
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
<!--<form name="form<?php echo $annee; ?>" id="form<?php echo $annee; ?>" method="get" action="<?php echo "print_etat_indicateurs_ptba.php?annee=".$annee; ?>" class="pull-left">

 <select name="acteur" onchange="form<?php echo $annee; ?>.submit();" style="background-color: #FFFF00; padding: 7px;" class="btn p11">

            <option value="">-- Choisissez une unité --</option>
            <?php
				  if($totalRows_liste_prestataire>0) {
do {
?>
            <option <?php //if(isset($id_ms) && $id_ms==$row_liste_prestataire['id_acteur']) {echo 'SELECTED="selected"';  $nom=$row_liste_prestataire['objet'];}  ?> value="<?php echo  $row_liste_prestataire['code_ugl']; ?>"> <?php echo "<b>".$row_liste_prestataire['nom_ugl']."</b> ";?>
            </option>
            <?php
} while ($row_liste_prestataire = mysql_fetch_assoc($liste_prestataire));
  $rows = mysql_num_rows($liste_prestataire);
  if($rows > 0) {
      mysql_data_seek($liste_prestataire, 0);
	  $row_liste_prestataire = mysql_fetch_assoc($liste_prestataire);
  }}
?>
  </select>
  <input type="hidden" name="annee" value="<?php echo $annee; ?>" />

</form>-->
<div class="well well-sm r_float"><div class="r_float"><a href="./s_programmation.php" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>

<div class="r_float" style="margin-right: 20px;"><a target="_blank" title="Imprimer" href="<?php echo $editFormAction."&down=1"; ?>" class="button"><img src="./images/print.png" width='20' height='20' alt='Modifier' /></a></div>
</div>
<div class="clear h0">&nbsp;</div>
<?php } else { ?>

<!--<center><?php //include "./includes/print_header.php"; ?></center>       -->

<?php } ?>
<div class="well well-sm"><strong>Situation des Indicateurs du PTBA <?php echo "$annee"; ?> par d&eacute;partement&nbsp;&nbsp; <span style="background-color:#FFCC33"><?php if(isset($_GET["acteur"]) && isset( $Nacteur_array[$_GET["acteur"]])) echo  "Acteur: <u>".$Nacteur_array[$_GET["acteur"]]."</u>"; ?></span></strong></div>

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-responsive">
            <thead>
            <tr bgcolor="<?php echo (!isset($_GET["down"]))?'':'#E4E4E4'; ?>">
              <th rowspan="2" align="left">ACTIVITES</th>
              <!--<th rowspan="2" align="center"><b>N&deg;&nbsp;</b></th>-->
              <th rowspan="2" align="left">INDICATEUR</th>
              <th rowspan="2" align="left"><center>
                VALEUR CIBLE
              </center></th>
              <!-- <th rowspan="2" align="center"><center>TAUX (%)</center></th>-->
              <!--<th colspan="4" ><center>VALEUR REELLE</center></th> -->
              <th colspan="<?php echo $nbregi+2; ?>" align="center"><center>
                SUIVI DE L'EXECUTION PAR DEPARTEMENT 
              </center></th>
              <th rowspan="2" align="center"><div align="center">&nbsp;</div></th>
            </tr>
            <tr bgcolor="<?php echo (!isset($_GET["down"]))?'':'#E4E4E4'; ?>">
              <?php //for($j=1;$j<=4;$j++){ ?>
              <!--<th align="center"><center>Trimestre <?php echo $j; ?></center></th>-->
              <?php //} ?>
       <?php foreach($tableauDepartement as $vregion){?>  <th align="center" > <?php $aregion = explode('<>',$vregion); $iregion = $aregion[0]; echo $aregion[1]; ?> </th> <?php } ?>
              <th align="center"><center>
                TOTAL  REALISE
              </center></th>
              <th align="center"><div align="center">TAUX (%)</div></th>
              </tr>
            </thead>
<?php  if($totalRows_edit_ms>0) { do {
$code = $row_edit_ms['code'];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."ptba where projet='".$_SESSION["clp_projet"]."' and annee='$annee'  $wheract and code_activite_ptba like '$code%' ORDER By code_activite_ptba asc";
$liste_rec = mysql_query_ruche($query_liste_rec, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_rec = mysql_fetch_assoc($liste_rec);
$totalRows_liste_rec = mysql_num_rows($liste_rec);
if($totalRows_liste_rec>0) {$i=0; $t=0; $p2=$p1="j"; ?>
            <tr bgcolor="#BED694">
              <td colspan="<?php echo $nbregi+7; ?>" align="center" style="background-color: #BED694;">
                <b><?php echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?></b>              </td>
            </tr>
<?php $row=""; do { $code_act = $row_liste_rec['code_activite_ptba']; $id_act = $row_liste_rec['id_ptba'];
if(isset($indicateur_array[$id_act])){ $k=0;
foreach($indicateur_array[$id_act] as $a=>$b){ $realval=$total = 0; $div = 0; ?>
            <tr>
<?php if($k==0){ ?>
<td rowspan="<?php echo count($indicateur_array[$id_act]); ?>"><?php echo "<b>".$row_liste_rec['code_activite_ptba'].'</b> : '.$row_liste_rec['intitule_activite_ptba']; ?></td>
<?php } ?>
<!--<td><?php //echo $b['code_indicateur_ptba']; ?></td>-->
<?php for($j=1;$j<=4;$j++){ if(!in_array($b['unite'],$array_indic) && isset($tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j])) $total+= $tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j]; ?>
<?php } ?>
<td><?php echo $b['intitule_indicateur_tache']; ?><?php echo $unite_array[$b["id_indicateur_tache"]]; $total = ($b['unite']=="%")?$total:$total; //$realval=(isset($valeur_tache[$b['id_indicateur_tache']]))?$valeur_tache[$b['id_indicateur_tache']]:"0"; ?></td>
<td width="100"><?php  echo (!in_array($b['unite'],$array_indic))?$total:'-'; ?></td>
 <!--<td align="center" width="70">&nbsp;</td>-->

<?php foreach($tableauDepartement as $vregion){?>  <td align="center" > <?php  $aregion = explode('<>',$vregion); $iregion = $aregion[0]; if(isset($tableauValDepartement[$b['id_indicateur_tache']][$iregion])) { echo $tableauValDepartement[$b['id_indicateur_tache']][$iregion]; $realval=$realval+$tableauValDepartement[$b['id_indicateur_tache']][$iregion];} ?> </td> <?php } ?>
<td width="70" valign="middle" align="center" ><?php echo (isset($realval))?$realval:"-"; ?></td>
<td width="70" valign="middle" align="center" ><?php echo ($total>0 && $realval>0)?number_format(100*$realval/$total, 0, ',', ' ')." %":"-"; ?></td>
<?php if($k==0){ ?>
<td rowspan="<?php echo count($indicateur_array[$id_act]); ?>" width="70" valign="middle" align="center" ><?php //echo (isset($tauxG[$id_act]) && $tauxT[$id_act]>0 && $tauxG[$id_act]>0)?number_format($tauxG[$id_act]/$tauxT[$id_act], 0, ',', ' ')." %":""; ?>&nbsp;</td>
<?php } ?>
            </tr>
            <?php $row=$b["id_indicateur_tache"]; $k++; } } ?>
<tr class="even">
  <td colspan="<?php echo $nbregi+7; ?>"><div align="center" style="background-color:#CCCCCC; height: 2px;">&nbsp;</div></td>
</tr>
			<?php } while ($row_liste_rec= mysql_fetch_assoc($liste_rec)); ?>
            <?php } else { ?>
<!--            <tr>
              <td colspan="7"><div align="center"><span class="Style4"><em><strong>Aucune activit&eacute; enregistr&eacute;e dans la composante <?php //echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?> ! </strong></em></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span></div></td>
            </tr>-->
            <?php }  ?>
      <?php } while ($row_edit_ms = mysql_fetch_assoc($edit_ms)); } else { ?>
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