<?php
    class Config
    {
        /* Site Settings */
        var $sitename = "Coordination des Projets PBF/PACoP ";
        var $shortname = "Système Informatisé de Suivi-évaluation ";
        var $sitetititle = "ST/PBF";   
        var $siteshortname = "Coordination des Projets PBF/PACOP";
        var $siteshortdescription = "Système de Suivi-Evaluation\nCoordination des Projets PBF/PACoP";
        var $siteurl = "http://www.sise-stpbf.org/";
        /* dev env */
        var $db = null;
        /* Folders Settings */
        var $class_folder = "classes";
        var $config_folder = "api";
        var $img_folder = "images";
        var $icon_folder = "icons";
        var $script_folder = "scripts";
        /* Meta Settings */
        var $MetaDesc = "Système de Suivi - Evaluation - Bamasoft";
        var $MetaKeys = "Système de Suivi - Evaluation, SSE, Suivi - Evaluation, PBF, Côte d'Ivoire";
        var $MetaTitle = "Système de Suivi - Evaluation";
        var $MetaAuthor = "BASE - BAMASOFT";
        var $FaveIcone = "images/favico.ico";
        var $LogoEntete = "assets/img/entete.jpg";
        var $LogoPied = "assets/img/pied.jpg";
        /* Session Setting */
        var $session_handler = "";
        var $maxtime = 1000; //minutes
    }
    $nfile = basename ($_SERVER["PHP_SELF"]);
    $QUERY_STRING = $_SERVER["QUERY_STRING"];
    $getfile = explode("&", $_SERVER["QUERY_STRING"]);
    $config = new Config;
    $path = (isset($path))?$path:"./";
    include_once $path.$config->config_folder."/db.php";
    $config->db = $db;
    $MENU = array(
    1 =>array("localites.php"=>"Localités|Les localités (Régions, Départements, S/Préfecture et Villages) de la Côte d'Ivoire","users.php"=>"Utilisateurs|Les utilisateurs du système","programmes.php"=>"Programmes|Les programes",/*"structures.php"=>array("structures.php"=>"Agences récipiendaires|Les agences partenaires du PBF","structures_details.php"=>"Détails Structures|Détails de l'agence partenaire selectionné"),*/"partenaires.php"=>array("partenaires.php"=>"Partenaires|Les partenaires du PBF","partenaires_details.php"=>"Détails Partenaire|Détails du partenaire selectionné"),"projets.php"=>array("projets.php"=>"Projets|Les projets du système","projet_details.php"=>"Détails Projet|Détails du projet selectionné"),"categorie_depense.php"=>"Catégories de dépense|Les catégories de dépense par projet"/*,"plan_analytique_projet.php"=>"Plan Analytique|Plan Analytique par projet","plan_budgetaire_projet.php"=>"Plan Budgetaire","categorie_marche.php"=>"Marchés"*/,"autres_parametres.php"=>"Autres paramètres|Autres paramètres utilisés dans les differentes modules"/*,"liste_zone_collecte.php"=>"Zones de collecte","partenaires.php"=>"Partenaires","programmes_2qc.php"=>"Programmes","directions.php"=>"Services et directions", "categorie_indicateur.php"=>"Catégories d'indicateurs"*/),
    2 => array("niveau_cs.php"=>"Cadre stratégique|Le cadre stratégique par programme","indicateur_cs.php"=>"Indicateurs Programmes|Les indicateurs du cadre stratégique par programme","niveau_cr.php"=>"Cadre de résultat|Le cadre de résultat par projets","indicateur_cr.php"=>"Indicateurs Projets|Les indicateurs du cadre de résultat par projets"/*,"niveau_cmr.php"=>"CMR","fiches_projets.php"=>"Sources de financement","referentiel.php"=>array("referentiel.php"=>"Indicateurs sectoriels"),"cadre_sectoriel.php"=>"Thématiques","liste_tache_activite.php"=>"Tâches par activités"*/),
    3 => array("liste_convention.php"=>"Conventions|Conventions cadres","plan_ptba.php"=>"PTA|Programme de Travail Annuel (PTA)","liste_recommandation.php"=>"Recommandations|Suivi des recommandations des ateliers et missions","type_activites.php"=>"Types d'activités|Tâches par types d'activités"/*,"suivi_indicateur_ptba.php"=>"Suivi du PTBA","plan_marche.php"=>"PPM","analyse_budgetaire.php"=>"Analyse budgetaire","gestion_contrat_prestation.php"=>"Contrats de prestation","gestion_decompte.php"=>"Décomptes"*/), 
    4 => array("fiches_dynamiques.php"=>array("fiches_dynamiques.php"=>"Classeurs dynamiques|Les Classeurs des fiches dynamiques","classeur_details.php"=>"Fiches dynamiques|Les Fiches dynamiques","disposition_mobile_formulaire.php"=>"Fiches dynamiques|Disposition Mobile"),"suivi_indicateur_cr.php"=>array("suivi_indicateur_cr.php"=>"Suivi indicateurs Projets|Suivi des indicateurs du Projet","suivi_referentiel.php"=>"Détails suivi des indicateurs|Détails suivi des indicateurs selectionnés"),"suivi_plan_ptba.php"=>"Suivi du PTA|Suivi de l'exécution technique et budgetaire des PTA","edition_rapport_projet.php"=>"Rapports périodiques|Edition des rapports périodiques de Projets"),
    5 => array("etat_ptba_projet.php"=>"Etats des PTA|Etats et Rapports sur les différents PTA","etat_recap_projets.php"=>"Tableau de bord Projets|Cadre de Résultats, Etats et documents de Projets","tableau_recap_projets.php"=>"Rapports du PACoP|Tableaux récapitulatifs des projets/Programmes","rapports_dynamiques.php"=>array("rapports_dynamiques.php"=>"Rapports dynamiques|Création de rapport à partir des fiches dynamiques","rapports_dynamiques_simple_creation.php"=>"Rapports dynamiques simples|Création de rapport simple à partir des fiches dynamiques","rapports_dynamiques_simple_modification.php"=>"Rapports dynamiques simple|Modification de rapport dynamique simple","rapport_details_simple.php"=>"Rapports dynamiques|Rapport simple","rapports_dynamiques_croise_creation.php"=>"Rapports dynamiques croisés|Création de rapport croisé à partir des fiches dynamiques","rapports_dynamiques_croise_modification.php"=>"Rapports dynamiques croisé|Modification de rapport dynamique croisé","rapport_details_croise.php"=>"Rapports dynamiques|Rapport croisé"),"rapports_indicateur.php"=>"Rapports indicateurs|Rapports des Indicateurs"/*,"resultat_recherche_indicateur.php"=>"Etat par indicateurs","tab_indicateurs.php"=>"Tableau de bord","croisement_recherche_indicateur.php"=>"Analyse croisée"*/),/*,
    6 => array("liste_document.php"=>"Documents","actualites.php"=>"Actualités","gestion_reunion.php"=>"Réunions et Atéliers"),*/
    7 => array("map.php"=>"Cartographie|La cartographie des réalisations"),
    );
    $MENU_TITLE = array(
    1 => array("Paramétrage","gear","parametrage.php") ,
    2 => array("Cadre de résultat","twitch","cadre_resultat.php"),
    3 => array("Programmation","cubes","programmation.php"),
    4 => array("Suivi des résultats","cubes","suivi_resultat.php"),
    5 => array("Etats & Rapports","tasks","rapport.php"),/*,
    6 => array("Documentation","newspaper-o","document.php"),*/
    7 => array("Cartographie","map-marker",'cartographie.php'),
    );
?>