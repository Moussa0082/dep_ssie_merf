<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
if (!isset ($_SESSION["id"])) {
    header(sprintf("Location: %s", "./login.php"));  exit();
}
require_once 'api/Fonctions.php';
require_once 'api/essentiel.php';
require_once 'theme_components/theme_style.php';

extract($_GET);
$id= $_SESSION['projet'];
if(isset($id_per) && !empty($id_per))
{
    $query_liste_projet = $db ->prepare('SELECT * FROM t_projets P WHERE P.id_projet=:id_projet');
    $query_liste_projet->execute(array(':id_projet' => $id));
    $row_liste_projet = $query_liste_projet ->fetch();
    $totalRows_liste_projet = $query_liste_projet->rowCount();
	
	$query_liste_rapport = $db ->prepare('SELECT * FROM t_rapportage_projet P WHERE P.projet_id=:projet_id and P.id_rapport=:id_rapport');
    $query_liste_rapport->execute(array(':projet_id' => $id, ':id_rapport' => $id_per));
    $row_liste_rapport = $query_liste_rapport ->fetch();
    $totalRows_liste_rapport = $query_liste_rapport->rowCount();
}
if(isset($totalRows_liste_projet) && $totalRows_liste_projet>0){

    //Programme
    $query_liste_programme = $db ->prepare('SELECT * FROM t_programmes P WHERE P.id_programme=:id_programme');
    $query_liste_programme->execute(array(':id_programme' => $row_liste_projet["programme"]));
    $row_liste_programme = $query_liste_programme ->fetch();
    $totalRows_liste_programme = $query_liste_programme->rowCount();

    //Partenaires
    $query_partenaire = $db ->prepare('SELECT * FROM t_partenaires ');
    $query_partenaire->execute();
    $row_liste_partenaire = $query_partenaire ->fetchAll();
    $totalRows_liste_partenaire = $query_partenaire->rowCount();
    $liste_structure_arrayV = $liste_structure_array =$liste_sigle_structure_array = array();
    if($totalRows_liste_partenaire>0){  foreach($row_liste_partenaire as $row_liste_partenaire1){
    if($row_liste_partenaire1["type_partenaire"]==1)
    {
        $liste_structure_arrayV[$row_liste_partenaire1["id_partenaire"]]=$row_liste_partenaire1["nom_partenaire"];
        $liste_structure_array[$row_liste_partenaire1["id_partenaire"]]=$row_liste_partenaire1["sigle_partenaire"];
		$liste_sigle_structure_array[$row_liste_partenaire1["sigle_partenaire"]]=$row_liste_partenaire1["id_partenaire"];
    }
    } }

    //Parametres
    $query_parametres = $db ->prepare('SELECT * FROM t_dp_fpbf_dcs');
    $query_parametres->execute();
    $row_liste_parametre = $query_parametres ->fetchAll();
    $totalRows_liste_parametre = $query_parametres->rowCount();

    //Montant total projet
    $query_projet_cout = $db ->prepare('SELECT sum(montant) as montant FROM t_repartition_projet_budget WHERE projet_bud=:id_projet group by projet_bud');
    $query_projet_cout->execute(array(':id_projet' => $row_liste_projet['id_projet']));
    $row_projet_cout = $query_projet_cout ->fetch();
    $totalRows_projet_cout = $query_projet_cout->rowCount();
    $projet_cout_total_val = "";
    if($totalRows_projet_cout>0) $projet_cout_total_val = number_format($row_projet_cout["montant"], 0, ',', ',').' $US';

    //Montant projet bailleur
    $query_projet_cout = $db ->prepare('SELECT sum(montant) as montant, structure_bud FROM t_repartition_projet_budget WHERE projet_bud=:id_projet group by structure_bud');
    $query_projet_cout->execute(array(':id_projet' => $row_liste_projet['id_projet']));
    $row_projet_cout = $query_projet_cout ->fetchAll();
    $totalRows_projet_cout = $query_projet_cout->rowCount();

    //Montant projet bailleur tranche
    $query_projet_tranche = $db ->prepare('SELECT montant, structure_bud, tranche FROM t_repartition_projet_budget WHERE projet_bud=:id_projet group by structure_bud,tranche order by structure_bud');
    $query_projet_tranche->execute(array(':id_projet' => $row_liste_projet['id_projet']));
    $row_projet_tranche = $query_projet_tranche ->fetchAll();
    $totalRows_projet_tranche = $query_projet_tranche->rowCount();
    $tranche_array = $tranche_agence_array = array();
    if($totalRows_projet_tranche>0){ $i=1; foreach($row_projet_tranche as $row_projet_tranche1){
        $tranche_array[$row_projet_tranche1["structure_bud"]][$row_projet_tranche1["tranche"]] = $row_projet_tranche1["montant"];
        if(!isset($tranche_agence_array[$row_projet_tranche1["structure_bud"]]))
        $tranche_agence_array[$row_projet_tranche1["structure_bud"]] = isset($liste_structure_array[$row_projet_tranche1["structure_bud"]])?$liste_structure_array[$row_projet_tranche1["structure_bud"]]:"-";
    } }
    $tableauTranche=array(1,2,3,4);

// Include classes
include_once('./libs/tbs_us/plugin_opentbs/demo/tbs_class.php'); // Load the TinyButStrong template engine
include_once('./libs/tbs_us/plugin_opentbs/tbs_plugin_opentbs.php'); // Load the OpenTBS plugin
//include_once('./libs/tbs_us/plugin_opentbs/tbs_plugin_html.php');

// prevent from a PHP configuration problem when using mktime() and date()
if (version_compare(PHP_VERSION,'5.1.0')>=0) {
    if (ini_get('date.timezone')=='') {
        date_default_timezone_set('UTC');
    }
}

// Initialize the TBS instance
$TBS = new clsTinyButStrong; // new instance of TBS
$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN); // load the OpenTBS plugin
$TBS->NoErr = true;

// -----------------
// Load the template
// -----------------
$template = './libs/templates/rapport_doc_projet.docx';
$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

$intitule_projet = !empty($row_liste_projet["intitule_projet"])?$row_liste_projet["intitule_projet"]:"-";
$code_projet = !empty($row_liste_projet["code_projet"])?$row_liste_projet["code_projet"]:"-";
$pays_projet = !empty($row_liste_programme["pays"])?$row_liste_programme["pays"]:"-";
$partenaires_execution=$agence_lead =$agence_0=$agence_1=$agence_2=$agence_3=$agence="";

if($row_liste_projet["autres_agences_recipiendaires"]!="" && !empty($row_liste_projet["autres_agences_recipiendaires"])) $agences=explode(",",$row_liste_projet["agence_lead"].",".$row_liste_projet["autres_agences_recipiendaires"]);
else $agences=explode(",",$row_liste_projet["agence_lead"]);
$it=0; foreach($agences as $row_liste_structure1){
 if(isset($liste_structure_array[$row_liste_structure1])) 
 {
 $agence_lead.=$liste_structure_array[$row_liste_structure1].", ";
 //$agence."_".$it=$liste_structure_array[$row_liste_structure1];
     if($it==0) $agence_0=$liste_structure_array[$row_liste_structure1];
 elseif($it==1) $agence_1=$liste_structure_array[$row_liste_structure1];
 elseif($it==2) $agence_2=$liste_structure_array[$row_liste_structure1];
 elseif($it==3) $agence_3=$liste_structure_array[$row_liste_structure1];
 $it++;
 }
 }
 /*
 echo $agence_0;
 exit;*/
 $liste_parteanire_execution=explode(",",$row_liste_projet["autres_partenaires_execution"]);
 foreach($liste_parteanire_execution as $pe){
 if(isset($liste_structure_arrayV[$pe])) $partenaires_execution.=$liste_structure_arrayV[$pe].", ";
 }
/*if($totalRows_liste_partenaire>0){ $elem = isset($row_liste_projet["agence_lead"])?explode(',',$row_liste_projet["agence_lead"]):array(); foreach($row_liste_partenaire as $row_liste_partenaire1) { if($row_liste_partenaire1['id_partenaire']==$row_liste_projet["agence_lead"]){ $agence_lead = $row_liste_partenaire1['sigle_partenaire']; break; } } }*/

//if(isset($liste_structure_array[$row_liste_projet["agence_lead"]])) $agence_0=$liste_structure_array[$row_liste_projet["agence_lead"]];
//$agence_0 = isset($liste_structure_array[$row_liste_projet["agence_lead"]])?$liste_structure_array[$row_liste_projet["agence_lead"]]:"-";
$modalite_financement_array = array('IRF','PRF','Autre');
$modalite_financement = $row_liste_projet["modalite_financement"];
$date_demarrage = date_reg($row_liste_projet['date_demarrage'],"/");

//rapport periodique
$etat_global_mo = !empty($row_liste_rapport["etat_global_mo"])?$row_liste_rapport["etat_global_mo"]:"-";
$pertinence_projet = !empty($row_liste_rapport["pertinence_projet"])?$row_liste_rapport["pertinence_projet"]:"-";
$unicite_innovation = !empty($row_liste_rapport["unicite_innovation"])?$row_liste_rapport["unicite_innovation"]:"-";
$evaluation_progres_global = !empty($row_liste_rapport["evaluation_progres_global"])?$row_liste_rapport["evaluation_progres_global"]:"-";
$progres_resultat_majeur = !empty($row_liste_rapport["progres_resultat_majeur"])?$row_liste_rapport["progres_resultat_majeur"]:"-";
$impact_humain_reel = !empty($row_liste_rapport["impact_humain_reel"])?$row_liste_rapport["impact_humain_reel"]:"-";
$principaux_defis = !empty($row_liste_rapport["principaux_defis"])?$row_liste_rapport["principaux_defis"]:"-";
$evaluation_retard = !empty($row_liste_rapport["evaluation_retard"])?$row_liste_rapport["evaluation_retard"]:"-";
$etat_actuel_R1 = !empty($row_liste_rapport["etat_actuel_R1"])?$row_liste_rapport["etat_actuel_R1"]:"-";
$resume_progres_R1 = !empty($row_liste_rapport["resume_progres_R1"])?$row_liste_rapport["resume_progres_R1"]:"-";
$etat_actuel_R2 = !empty($row_liste_rapport["etat_actuel_R2"])?$row_liste_rapport["etat_actuel_R2"]:"-";
$resume_progres_R2 = !empty($row_liste_rapport["resume_progres_R2"])?$row_liste_rapport["resume_progres_R2"]:"-";
$etat_actuel_R3 = !empty($row_liste_rapport["etat_actuel_R3"])?$row_liste_rapport["etat_actuel_R3"]:"-";
$resume_progres_R3 = !empty($row_liste_rapport["resume_progres_R3"])?$row_liste_rapport["resume_progres_R3"]:"-";
$etat_actuel_R4 = !empty($row_liste_rapport["etat_actuel_R4"])?$row_liste_rapport["etat_actuel_R4"]:"-";
$resume_progres_R4 = !empty($row_liste_rapport["resume_progres_R4"])?$row_liste_rapport["resume_progres_R4"]:"-";
$appropriation_nationale = !empty($row_liste_rapport["appropriation_nationale"])?$row_liste_rapport["appropriation_nationale"]:"-";
$suivi_eval_projet = !empty($row_liste_rapport["suivi_eval_projet"])?$row_liste_rapport["suivi_eval_projet"]:"-";
$evaluation_externe = !empty($row_liste_rapport["evaluation_externe"])?$row_liste_rapport["evaluation_externe"]:"-";
$effet_catalytique_financier = !empty($row_liste_rapport["effet_catalytique_financier"])?$row_liste_rapport["effet_catalytique_financier"]:"-";
$effet_catalytique_non_financier = !empty($row_liste_rapport["effet_catalytique_non_financier"])?$row_liste_rapport["effet_catalytique_non_financier"]:"-";
$startegie_sortie_durabilite = !empty($row_liste_rapport["startegie_sortie_durabilite"])?$row_liste_rapport["startegie_sortie_durabilite"]:"-";
$prises_risques = !empty($row_liste_rapport["prises_risques"])?$row_liste_rapport["prises_risques"]:"-";
$egalite_entre_sexe = !empty($row_liste_rapport["egalite_entre_sexe"])?$row_liste_rapport["egalite_entre_sexe"]:"-";
$autre_point = !empty($row_liste_rapport["autre_point"])?$row_liste_rapport["autre_point"]:"-";
$etat_depense_financiere = !empty($row_liste_rapport["etat_depense_financiere"])?$row_liste_rapport["etat_depense_financiere"]:"-";
$commentaire_tranche_budget = !empty($row_liste_rapport["commentaire_tranche_budget"])?$row_liste_rapport["commentaire_tranche_budget"]:"-";
$commentaire_demande_tranche = !empty($row_liste_rapport["commentaire_demande_tranche"])?$row_liste_rapport["commentaire_demande_tranche"]:"-";
$commentaire_retard_depense = !empty($row_liste_rapport["commentaire_retard_depense"])?$row_liste_rapport["commentaire_retard_depense"]:"-";
$montant_activite_egalite_sexe = !empty($row_liste_rapport["montant_activite_egalite_sexe"])?$row_liste_rapport["montant_activite_egalite_sexe"]:"-";

$rapport_excercice_prealable = !empty($row_liste_rapport["rapport_excercice_prealable"])?$row_liste_rapport["rapport_excercice_prealable"]:"-";
$commentaire_pbf = !empty($row_liste_rapport["commentaire_pbf"])?$row_liste_rapport["commentaire_pbf"]:"-";
$revu_pbf = !empty($row_liste_rapport["revu_pbf"])?$row_liste_rapport["revu_pbf"]:"-";
$approuve_par = !empty($row_liste_rapport["approuve_par"])?$row_liste_rapport["approuve_par"]:"-";
$prepare_par = !empty($row_liste_rapport["prepare_par"])?$row_liste_rapport["prepare_par"]:"-";
$periode_concerne = !empty($row_liste_rapport["periode_concerne"])?$row_liste_rapport["periode_concerne"]:"-";
$b = !empty($row_liste_rapport["type_rapport"])?$row_liste_rapport["type_rapport"]:"-";
$tab_p=explode("_",$b); $type_r = (isset($tab_p[0]) && !empty($tab_p[0]))?$tab_p[0]:"-";

/*$nombreMois = $row_liste_projet['duree'];$nb_duree = intval($nombreMois / 12);$mois = intval(($nombreMois % 12)); $duree = "$nb_duree an".($nb_duree>1?"s":"").($mois>0?" $mois mois":"")." ()";*/
setlocale (LC_TIME, 'fr_FR.utf8','fra');$time = strtotime($row_liste_projet['date_demarrage']);$final = date("Y-m-d", strtotime("+".$row_liste_projet['duree']." month", $time));
$duree = $row_liste_projet['duree']." mois (".date("t ", strtotime($final)).ucfirst(strftime("%B %Y", strtotime($final))).")";
$nom_fonds_fidicuaire_type_nom = !empty($row_liste_projet["nom_fonds_fidicuaire"])?explode('/',$row_liste_projet["nom_fonds_fidicuaire"]):array("","");
$nom_fonds_fidicuaire["nom"] = isset($nom_fonds_fidicuaire_type_nom[1])?$nom_fonds_fidicuaire_type_nom[1]:""; $nom_fonds_fidicuaire["type"] = isset($nom_fonds_fidicuaire_type_nom[0])?$nom_fonds_fidicuaire_type_nom[0]:"";
$pourcentage_budget_genre = !empty($row_liste_projet["pourcentage_budget_genre"])?$row_liste_projet["pourcentage_budget_genre"]:0;
$score = 1; $score_data = $score_data_val = array();
if($totalRows_liste_parametre>0){ foreach($row_liste_parametre as $row_liste_parametre1) { if($row_liste_parametre1['type_parametre']==2) { $elems = explode('%',$row_liste_parametre1['libelle_parametre']); $score_data[] = (isset($elems[0])?intval(substr($elems[0],-2)):0); $score_data_val[] = intval(substr(trim($row_liste_parametre1['libelle_parametre']),6,1)); } } }
if(count($score_data_val)>0){asort($score_data_val);foreach($score_data_val as $a=>$b){ if(isset($score_data[$a]) && $pourcentage_budget_genre<$score_data[$a] && (isset($score_data[$a+1]) && $pourcentage_budget_genre>=$score_data[$a+1]) || !isset($score_data[$a+1])){ $score = $b; break; } } }
$fenetre_pbf = "";
if($totalRows_liste_parametre>0){ foreach($row_liste_parametre as $row_liste_parametre1) { if($row_liste_parametre1['type_parametre']==1 && $row_liste_parametre1['id_parametre']==$row_liste_projet["fenetre_pbf"]) { $fenetre_pbf = $row_liste_parametre1['libelle_parametre']; } } }
$zone = !empty($row_liste_projet["zone"])?$row_liste_projet["zone"]:"";
$processus_consultation = !empty($row_liste_projet["processus_consultation"])?strip_tags($row_liste_projet["processus_consultation"]):"";
$description_marqueur_genre = !empty($row_liste_projet["description_marqueur_genre"])?strip_tags($row_liste_projet["description_marqueur_genre"]):"";
$description_projet = !empty($row_liste_projet["description_projet"])?strip_tags($row_liste_projet["description_projet"]):"";
$domaine_intervention_prioritaire = "";
if($totalRows_liste_parametre>0){ foreach($row_liste_parametre as $row_liste_parametre1) { if($row_liste_parametre1['type_parametre']==4 && $row_liste_parametre1['id_parametre']==$row_liste_projet["domaine_intervention_prioritaire"]) { $elems = explode('.',$row_liste_parametre1['libelle_parametre']); $domaine_intervention_prioritaire = (isset($elems[0])?substr($elems[0],-1):'').".".(isset($elems[1])?substr($elems[1],0,1):''); } } }
$resultat_undaf = !empty($row_liste_projet["resultat_undaf"])?strip_tags($row_liste_projet["resultat_undaf"]):"";
$objectif_odd = !empty($row_liste_projet["objectif_odd"])?strip_tags($row_liste_projet["objectif_odd"]):"";
$niveau_risque = 0;
if($totalRows_liste_parametre>0){ foreach($row_liste_parametre as $row_liste_parametre1) { if($row_liste_parametre1['type_parametre']==3 && $row_liste_parametre1['id_parametre']==$row_liste_projet["niveau_risque"]) { $niveau_risque = substr($row_liste_parametre1['description_parametre'],0,1); } } }
$projet_cout_total = $projet_cout_total_val;
if (isset($_POST['debug']) && ($_POST['debug']=='current')) $TBS->Plugin(OPENTBS_DEBUG_XML_CURRENT, true); // Display the intented XML of the current sub-file, and exit.
if (isset($_POST['debug']) && ($_POST['debug']=='info'))    $TBS->Plugin(OPENTBS_DEBUG_INFO, true); // Display information about the document, and exit.
if (isset($_POST['debug']) && ($_POST['debug']=='show'))    $TBS->Plugin(OPENTBS_DEBUG_XML_SHOW); // Tells TBS to display information when the document is merged. No exit.
$data = array();
if($totalRows_liste_partenaire>0){ $elem = isset($row_liste_projet["autres_partenaires_execution"])?explode(',',$row_liste_projet["autres_partenaires_execution"]):array(); foreach($row_liste_partenaire as $row_liste_partenaire1) {
if(in_array(2,explode(",",$row_liste_partenaire1['type_partenaire'])) && in_array($row_liste_partenaire1['id_partenaire'],$elem)) $data[] = array('nom'=> $row_liste_partenaire1['nom_partenaire']." ;"); } if(isset($data[count($data)-1]['nom'])) $data[count($data)-1]['nom'] = substr($data[count($data)-1]['nom'],0,-2)."."; } else $data[] = array('nom'=> "");
$TBS->MergeBlock('autres_partenaires_execution,b', $data);

$data = array();
if($totalRows_liste_partenaire>0){ $elem = isset($row_liste_projet["autres_partenaires_execution"])?explode(',',$row_liste_projet["autres_partenaires_execution"]):array(); foreach($row_liste_partenaire as $row_liste_partenaire1) {
if(in_array(3,explode(",",$row_liste_partenaire1['type_partenaire'])) && in_array($row_liste_partenaire1['id_partenaire'],$elem)) $data[] = array('nom'=> $row_liste_partenaire1['nom_partenaire']." ;"); } if(isset($data[count($data)-1]['nom'])) $data[count($data)-1]['nom'] = substr($data[count($data)-1]['nom'],0,-2)."."; } else $data[] = array('nom'=> "");
$TBS->MergeBlock('partenaires_signataires,b', $data);
$cout_agence_0=$cout_agence_1=$cout_agence_2=$cout_agence_3="";
$data = array();
if($totalRows_projet_cout>0){ $i=1; foreach($row_projet_cout as $row_projet_cout){ $data[] = array('nom'=>$liste_structure_array[$row_projet_cout["structure_bud"]]?$liste_structure_array[$row_projet_cout["structure_bud"]]:'-','libelle'=> "Budget total ".($liste_structure_array[$row_projet_cout["structure_bud"]]?$liste_structure_array[$row_projet_cout["structure_bud"]]:'-')." :", 'val'=>number_format($row_projet_cout["montant"], 0, ',', ',').' $US');

if(isset($liste_structure_array[$row_projet_cout["structure_bud"]]) && $liste_structure_array[$row_projet_cout["structure_bud"]]==$agence_0) $cout_agence_0=$row_projet_cout["montant"];
elseif(isset($liste_structure_array[$row_projet_cout["structure_bud"]]) && $liste_structure_array[$row_projet_cout["structure_bud"]]==$agence_1) $cout_agence_1=$row_projet_cout["montant"];
elseif(isset($liste_structure_array[$row_projet_cout["structure_bud"]]) && $liste_structure_array[$row_projet_cout["structure_bud"]]==$agence_2) $cout_agence_2=$row_projet_cout["montant"];
elseif(isset($liste_structure_array[$row_projet_cout["structure_bud"]]) && $liste_structure_array[$row_projet_cout["structure_bud"]]==$agence_3) $cout_agence_3=$row_projet_cout["montant"];

} } else $data[] = array("nom"=>"","libelle"=>"","val"=>"");
$TBS->MergeBlock('budget_agence,b', $data);

$data = array();
$data[] = array("nom"=>"","val"=>"");
$TBS->MergeBlock('budget_autre,b', $data);

$data = array();
$bailleur_projet=explode(",",$row_liste_projet['bailleur']);
//$structure_projet=explode(",",$row_liste_projet['agence_lead'].",".$row_liste_projet['autres_agences_recipiendaires']);
$query_liste_bailleur = $db ->prepare('SELECT * FROM t_partenaires WHERE FIND_IN_SET(:type_partenaire,type_partenaire)  ORDER BY nom_partenaire asc');
$query_liste_bailleur->execute(array(':type_partenaire' => 4));
$row_liste_bailleur = $query_liste_bailleur ->fetchAll();
$totalRows_liste_bailleur = $query_liste_bailleur->rowCount();
if($totalRows_liste_bailleur>0){
    foreach($row_liste_bailleur as $row_liste_bailleur1){ if (in_array($row_liste_bailleur1['id_partenaire'],$bailleur_projet)) { $bailleur = $row_liste_bailleur1['sigle_partenaire'];
            $sub_data = array();
           for($i=1;$i<=count($tableauTranche);$i++){
               $sub_data[$i] = "$bailleur $i".($i==1?'ère':'ième')." tranche : \n\n";
                if(count($tranche_agence_array)>0) {
                    foreach($tranche_agence_array as $id_structure=>$nom_structure){
                        if(isset($tranche_array[$id_structure][$i]))
                        $sub_data[$i] .= "$nom_structure :".' $ '.number_format($tranche_array[$id_structure][$i], 0, ',', ',')."\n";
                    }
                }
           }
           $data[] = array('agence_tranche1'=>isset($sub_data[1])?$sub_data[1]:'','agence_tranche2'=>isset($sub_data[2])?$sub_data[2]:'','agence_tranche3'=>isset($sub_data[3])?$sub_data[3]:'','agence_tranche4'=>isset($sub_data[4])?$sub_data[4]:'');
        }
    }
} else $data[] = array("agence_tranche1"=>"","agence_tranche2"=>"","agence_tranche3"=>"","agence_tranche4"=>"");
$TBS->MergeBlock('tranche,b', $data);

$data = array();
$where = " niveau =1";
$wh = "";
$projet = $row_liste_projet["id_projet"];
$query_entete = $db ->prepare('SELECT * FROM t_config_cadre_resultat_projet WHERE projet=:projet LIMIT 1');
$query_entete->execute(array(':projet' => $projet));
$row_entete = $query_entete ->fetch();
$totalRows_entete = $query_entete->rowCount();
$libelle = array();
if($totalRows_entete>0){ $libelle=explode(",",$row_entete["libelle"]);
if(isset($libelle[0]) && !empty($libelle[0])){
$query_liste_activite = $db ->prepare('SELECT * FROM t_cadre_resultat WHERE niveau =1 and projet=:projet ORDER BY niveau,code ASC');
$query_liste_activite->execute(array(':projet' => $projet));
$row_liste_activite = $query_liste_activite ->fetchAll();
$totalRows_liste_activite = $query_liste_activite->rowCount();
} $n = count($libelle);
$t=0; $i=0; if(isset($totalRows_liste_activite) && $totalRows_liste_activite>0) {
function trace_tr($niveau,$j,$n,$libelle,$libelle1)
{
    $marge = ""; $data1 = array();
    for($k=0;$k<$j;$k++) $marge .= "\t";
    $data1 = array('marge'=> $marge,'code'=> $libelle." ".$libelle1["code"],'nom'=> $libelle1["intitule"]);
    return $data1;
}
//$niveau_indent limite = 6;
$niveau_indent = $n;   $k = 0;
$resultat1=$resultat2=$resultat3=$resultat4="   ";
$query_liste_activite_1 = $db ->prepare('SELECT * FROM t_cadre_resultat WHERE '.$where.' '.$wh.' and projet=:projet ORDER BY niveau,code ASC');
$query_liste_activite_1->execute(array(':projet' => $projet));
$row_liste_activite_1 = $query_liste_activite_1 ->fetchAll();
$totalRows_liste_activite_1 = $query_liste_activite_1->rowCount();
$itr=1; foreach($row_liste_activite_1 as $row_liste_activite_1)
{

if($itr==1) $resultat1=$row_liste_activite_1["code"].": ".$row_liste_activite_1["intitule"];
elseif($itr==2) $resultat2=$row_liste_activite_1["code"].": ".$row_liste_activite_1["intitule"];
elseif($itr==3) $resultat3=$row_liste_activite_1["code"].": ".$row_liste_activite_1["intitule"];
elseif($itr==4) $resultat4=$row_liste_activite_1["code"].": ".$row_liste_activite_1["intitule"];
$itr++;

    $niveau_indent = $n; $k = $j = 0;
	//$resultat1=$row_liste_activite_1["code"].": ".$row_liste_activite_1["intitule"];
    if($niveau_indent-$j>0)
    {
        $code_1 = $row_liste_activite_1["code"]; $id_1 = $row_liste_activite_1["id_cadre_resultat"];
		
		
        //traitement ici
        $data[] = trace_tr($k,$j,$n,$libelle[$k],$row_liste_activite_1);

        $query_liste_activite_2 = $db ->prepare('SELECT * FROM t_cadre_resultat WHERE niveau=:niveau and parent=:parent and projet=:projet ORDER BY niveau,code ASC');
        $query_liste_activite_2->execute(array(':niveau' => $j+2,':parent' => $id_1,':projet' => $projet));
        $row_liste_activite_2 = $query_liste_activite_2 ->fetchAll();
        $totalRows_liste_activite_2 = $query_liste_activite_2->rowCount();
        if($totalRows_liste_activite_2>0) { foreach($row_liste_activite_2 as $row_liste_activite_2)
        {
            $j=1; $k=1;
            if($niveau_indent-$j>0)
            {
                $code_2 = $row_liste_activite_2["code"]; $id_2 = $row_liste_activite_2["id_cadre_resultat"];
				//$resultat2=$row_liste_activite_2["code"].": ".$row_liste_activite_2["intitule"];
                //traitement ici
                $data[] = trace_tr($k,$j,$n,$libelle[$k],$row_liste_activite_2);

                $query_liste_activite_3 = $db ->prepare('SELECT * FROM t_cadre_resultat WHERE niveau=:niveau and parent=:parent and projet=:projet ORDER BY niveau,code ASC');
                $query_liste_activite_3->execute(array(':niveau' => $j+2,':parent' => $id_2,':projet' => $projet));
                $row_liste_activite_3 = $query_liste_activite_3 ->fetchAll();
                $totalRows_liste_activite_3 = $query_liste_activite_3->rowCount();
                if($totalRows_liste_activite_3>0) { foreach($row_liste_activite_3 as $row_liste_activite_3)
                {
                    if($niveau_indent-$j>0)
                    {
                        $j=2; $k=2;
                        $code_3 = $row_liste_activite_3["code"]; $id_3 = $row_liste_activite_3["id_cadre_resultat"];
						//$resultat3=$row_liste_activite_3["code"].": ".$row_liste_activite_3["intitule"];
                        //traitement ici
                        $data[] = trace_tr($k,$j,$n,$libelle[$k],$row_liste_activite_3);
                        $query_liste_activite_4 = $db ->prepare('SELECT * FROM t_cadre_resultat WHERE niveau=:niveau and parent=:parent and projet=:projet ORDER BY niveau,code ASC');
                        $query_liste_activite_4->execute(array(':niveau' => $j+2,':parent' => $id_3,':projet' => $projet));
                        $row_liste_activite_4 = $query_liste_activite_4 ->fetchAll();
                        $totalRows_liste_activite_4 = $query_liste_activite_4->rowCount();
                        if($totalRows_liste_activite_4>0) { foreach($row_liste_activite_4 as $row_liste_activite_4)
                        {
                            if($niveau_indent-$j>0)
                            {
                                $j=3; $k=3;
                                $code_4 = $row_liste_activite_4["code"]; $id_4 = $row_liste_activite_4["id_cadre_resultat"];
								//$resultat4=$row_liste_activite_4["code"].": ".$row_liste_activite_4["intitule"];
                                //traitement ici
                                $data[] = trace_tr($k,$j,$n,$libelle[$k],$row_liste_activite_4);

                                $query_liste_activite_5 = $db ->prepare('SELECT * FROM t_cadre_resultat WHERE niveau=:niveau and parent=:parent and projet=:projet ORDER BY niveau,code ASC');
                                $query_liste_activite_5->execute(array(':niveau' => $j+2,':parent' => $id_4,':projet' => $projet));
                                $row_liste_activite_5 = $query_liste_activite_5 ->fetchAll();
                                $totalRows_liste_activite_5 = $query_liste_activite_5->rowCount();
                                if($totalRows_liste_activite_5>0) { foreach($row_liste_activite_5 as $row_liste_activite_5)
                                {
                                    if($niveau_indent-$j>0)
                                    {
                                        $j=4; $k=4;
                                        $code_5 = $row_liste_activite_5["code"]; $id_5 = $row_liste_activite_5["id_cadre_resultat"];
										//$resultat5=$row_liste_activite_5["code"].": ".$row_liste_activite_5["intitule"];
                                        //traitement ici
                                        $data[] = trace_tr($k,$j,$n,$libelle[$k],$row_liste_activite_5);
                                        $query_liste_activite_6 = $db ->prepare('SELECT * FROM t_cadre_resultat WHERE niveau=:niveau and parent=:parent and projet=:projet ORDER BY niveau,code ASC');
                                        $query_liste_activite_6->execute(array(':niveau' => $j+2,':parent' => $id_5,':projet' => $projet));
                                        $row_liste_activite_6 = $query_liste_activite_6 ->fetchAll();
                                        $totalRows_liste_activite_6 = $query_liste_activite_6->rowCount();
                                        if($totalRows_liste_activite_6>0) { foreach($row_liste_activite_6 as $row_liste_activite_6)
                                        {
                                            //activite limite ici à niveau 6
                                            $code_6 = $row_liste_activite_6["code"];
                                            $id_6 = $row_liste_activite_6["id_cadre_resultat"];
                                            //traitement ici
                                            $data[] = trace_tr($k,$j,$n,$libelle[$k],$row_liste_activite_6);

                                        } }
                                    }
                                } }
                            }
                        } }
                    }
                } }
            }
        } }
    }
}
} } else $data[] = array('marge'=> "",'code'=> "",'nom'=> "Aucun cadre de résultat programmé");
if(count($data)<=0) $data[] = array('marge'=> "",'code'=> "",'nom'=> "Aucun cadre de résultat programmé");
// Merge data in the body of the document
$TBS->MergeBlock('cadre_resultat,b', $data);

if($totalRows_liste_programme>0) $output_file_name = 'RAPPORT_PROJET_'.str_replace('.', '_', $row_liste_programme["sigle_programme"]).'_'.str_replace('.', '_', $row_liste_projet["sigle_projet"]).'_'.date('dmY').'.docx';
else $output_file_name = 'RAPPORT_PROJET_'.date('dmY').'.docx';
$TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); exit();
} else exit("Aucun projet trouvé !");
?>