<?php ini_set("data.timezone","UTC");

  class Config

  {

    /* Site Settings */

    var $sitename = "Syst&egrave;me de Suivi-Evaluation MERF";

    var $shortname = "RUCHE";

    var $sitetititle = "Syst&egrave;me de Suivi-Evaluation Projets MERF";

    var $siteshortname = "MERF";

     var $siteshortdescription = "Ministère de l'Environnement et de la Ressource Forestière";

    var $siteurl = "https://environnement.gouv.tg/";
    // var $siteurl = "https://environnement.gouv.tg/";

    var $lien = "http://localhost/php/SSISE-MERF/";
    // var $lien = "https://ssise-merf.org/";



    /* dev env */

    var $host = "";

    var $user = "";

    var $password = "";

    var $db_name = "";

    var $db_prefix = "";





    /* Folders Settings */

    var $class_folder = "classes";

    var $sys_folder = "system";

    var $img_folder = "assets/img";

    var $theme_folder = "assets/css";

    var $script_folder = "assets/js";



    /* Meta Settings */

    var $MetaDesc = "Syst&egrave;me de Suivi - Evaluation - COSIT";

    var $MetaKeys = "Syst&egrave;me de Suivi - Evaluation, SSE, Suivi - Evaluation, MERF, Togo";

    var $MetaTitle = "Syst&egrave;me de Suivi - Evaluation";

    var $MetaAuthor = "COSIT Mali";

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

  include_once $path.$config->sys_folder."/database/db_connexion.php"; 

  $MENU = array(

  1 =>array("localites.php"=>"Localités/ Sites",/*"unite_gestion.php"=>"Zones du projet","projets.php"=>"Projets","fonctions.php"=>"Fonction",*/"users.php"=>array("users.php"=>"Utilisateurs","fonctions.php"=>"Fonction"),"partenaires.php"=>"Acteurs","bailleurs.php"=>"Bailleurs","categorie_depense.php"=>"Catégories de dépense","plan_analytique_projet.php"=>"Plan Analytique","plan_budgetaire_projet.php"=>"Plan Budgetaire",/*"categorie_marche.php"=>"Marchés",*/"autres_parametres.php"=>"Autres paramètres"/*,"sygri_niveau1_projet.php"=>"SYGRI 1<sup>er</sup> niveau","sygri_niveau2_projet.php"=>"SYGRI 2<sup>ème</sup> niveau","soutien_sygri_niveau2_projet.php"=>"Soutien 2<sup>ème</sup> niveau","indicateur_sygri_fida.php"=>"SYGRI 3<sup>ème</sup> niveau"*/),

  2 => array("referentiel.php"=>"Indicateurs r&eacute;f&eacute;rentiels","niveau_cr.php"=>"Niveaux de résultats","cadre_logique.php"=>"Cadre de résultats", "edit_cadre_mesure_resultat.php"=>"Cadre de mesure de r&eacute;sultats"/*,"appendice4.php"=>"Appendice 4"*/),

 3 => array("liste_convention.php"=>"Conventions cadres","plan_ptba.php"=>"PTBA","agenda.php"=>"Agenda",/*"plan_marche.php"=>array("plan_marche.php"=>"PPM","plan_passation.php"=>"Planification du PPM","suivi_passation.php"=>"Suivi du PPM"),*/"analyse_budgetaire.php"=>"Analyse budgetaire"/*,"liste_tache_activite.php"=>"Type d'activités","print_liste_tache_activite.php"=>"T&aring;ches par activités"*/),

 4 => array("suivi_indicateur_ptba.php"=>"Suivi du PTBA",/*"base_de_donnees.php"=>"Fiches de collecte",*/"fiches_dynamiques.php"=>"Fiches dynamiques", "suivi_referentiel.php"=>"Résultats obtenus",/*"carte_jce.php"=>"Carte des JCE","carte_beneficaires.php"=>"Carte des PEI",*/ "parametres_mp.php"=>"Autres param&egrave;tres"),

  5 => array("gestion_mission_supervision.php"=>"Mission de supervision",/*"liste_dno.php"=>"DANO",*/"gestion_mission_terrain.php"=>"Missions terrain","liste_document.php"=>"Documentation"/*, "parametres_gestion_projet.php"=>"Paramètres"*/),

  6 => array("s_supervision.php"=>array("s_supervision.php"=>"Gestion de projet","print_recommandation_mission.php"=>"Liste des recommandations","print_etat_recommandation_mission.php"=>"Tableau de suivi des recommandations","graph_recommandation_mission.php"=>"Synthèse de suivi des recommandations","print_stat_connexion.php"=>"Situation des connexions"),"s_programmation.php"=>array("s_programmation.php"=>"Etats PTBA","print_activite_ptba.php"=>"Chronogramme activit&eacute;s","print_taches_activite_ptba.php"=>"Chronogramme des t&acirc;ches","print_indicateurs_ptba.php"=>"Chronogramme des indicateurs","print_taches_activite_semaine.php"=>"Plan t&acirc;che par responsable","print_suivi_budget_ptba_projet.php"=>"Suivi budegt PTBA","print_suivi_ptba_projet_pa.php"=>"Suivi par axe"),/*"s_ppm.php"=>"Etats PPM",*/ "s_suivi_resultat.php"=>"Suivi des résultats"/*,"s_sygri.php"=>"Rapports SYGRI"*/,"rapports_dynamiques.php"=>"Rapports dynamiques","rapports_indicateur.php"=>"Rapports indicateurs"),

 //7 => array("liste_document.php"=>"Documents"),  

//   8 => array("map.php"=>"Cartographie")

   8 => array("map.php"=>"Cartographie","liste_zone_collecte.php"=>"Couches de donn&eacute;es","rapports_cartographie_config.php"=>"fiches de donn&eacute;es")

  );

  header("Access-Control-Allow-Origin: *");

  $MENU_TITLE = array(     //signal

  1 => array("Paramétrage","gear","parametrage.php"),

 2 => array("Cadre de résultat","twitch","cadre_resultat.php"),

 3 => array("Programmation","calculator","programmation.php"),

 4 => array("Suivi des résultats","cubes","suivi_resultat.php"),

 5 => array("Gestion du projet","exchange","suivi_exec.php"),

 6 => array("Rapports","tasks","rapport.php"),

// 7 => array("Documentation","newspaper-o","document.php"),

 8 => array("Cartographie","map-marker",'map.php'),

  );

?>