<?php ini_set("display_errors",0);
if(!class_exists('Config')){
    class Config
    {
        /* Site Settings */
        var $sitename = "SSISE DPSSE/MERF";
        var $shortname = "RUCHE";
        var $sitetititle = "Syst&egrave;me de Suivi - Evaluation";
        var $siteshortname = "DPSSE du MERF";
        var $siteurl = "https://ssise-merf.org";
        var $base_host = "https://dep.ssise-merf.org";
        var $structure = "MERF";
        var $structure_code = "01";
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
        var $MetaDesc = "Syst&egrave;me de Suivi - Evaluation - COSIT - MALI";
        var $MetaKeys = "Syst&egrave;me de Suivi - Evaluation, SSE, Suivi - Evaluation";
        var $MetaTitle = "Syst&egrave;me de Suivi - Evaluation";
        var $MetaAuthor = "COSIT";
        var $FaveIcone = "images/favico.ico";
        var $LogoEntete = "assets/img/entete.jpg";
        var $LogoPied = "assets/img/pied.jpg";
        /* Session Setting */
        var $session_handler = "";
        var $maxtime = 3600; //secondes
    }
    $nfile = basename ($_SERVER["PHP_SELF"]);
    $QUERY_STRING = $_SERVER["QUERY_STRING"];
    $getfile = explode("&", $_SERVER["QUERY_STRING"]);
    if(!isset($config)) $config = new Config;
    $path = (isset($path))?$path:"./";
    include_once $path.$config->sys_folder."/database/db_connexion.php";
    $MENU = array(
    1 =>array("localites.php"=>"Localités","programmes_2qc.php"=>"Stratégies","projets.php"=>"Projets","fonctions.php"=>"Fonction","users.php"=>array("users.php"=>"Utilisateurs","fonctions.php"=>"Fonction"),"partenaires.php"=>"Acteurs clés","projets.php"=>"Projets MERF","groupes_travail.php"=>"Groupes de travail","cadre_sectoriel.php"=>"Domaines et thématiques","autres_parametres.php"=>"Autres paramètres"), 
    2 => array("referentiel.php"=>"Indicateurs reférentiels","niveau_i3nn.php"=>"Niveaux sectoriel","indicateur_i3n.php"=>"Indicateurs sectoriels", "niveau_cosop.php"=>"Cadre analytique","indicateur_cosop.php"=>"Indicateurs analytique","fiches_dynamiques.php"=>"Fiches dynamiques"),
3 => array("bailleurs.php"=>"Sources de financement","index.php"=>"Fiche des projets"/*,"suivi_indicateur_ptba.php"=>"Suivi du PTBA","plan_marche.php"=>"PPM","analyse_budgetaire.php"=>"Analyse budgetaire","gestion_contrat_prestation.php"=>"Contrats de prestation","gestion_decompte.php"=>"Décomptes"*/),
4 => array("tableau_consolide_ptba.php"=>"Tableau consolidé PTBA","tableau_suivi_consolide_ptba.php"=>"Tableau suivi consolidé PTBA"),
   // 4 => array("mission_supervision.php"=>"Mission de supervision", "gestion_mission_terrain.php"=>"Missions de terrain", "agenda.php"=>"Agenda","gestion_ateliers.php"=>"Ateliers et rencontres","gestion_reunion.php"=>"Réunions de coordination","liste_dno.php"=>"DANO","courrier_dno.php"=>"Courrier DANO","workflow.php"=>"Workflow","parametres_gestion_projet.php"=>"Paramètres"),
 //   5 => array("s_cadre_logique.php"=>"Cadre de résultat","s_supervision.php"=>"Gestion de projet","s_programmation.php"=>"Etats PTBA","s_ppm.php"=>"Etats PPM"),
    5 => array("liste_document.php"=>"Documents"),
   // 7 => array("map.php"=>"Cartographie"), /**/
    6 => array("map.php"=>"Cartographie","liste_zone_collecte.php"=>"Couches de donn&eacute;es","rapports_cartographie_config.php"=>"fiches de donn&eacute;es")
    );
    /*if(isset($_SESSION["clp_projet"]) && $_SESSION["clp_projet"]=="01")
    {
        //Consolider
        $a_tmp = array_keys($MENU[5]); $a_tmp[] = "s_consolider.php";
        $b_tmp = array_values($MENU[5]); $b_tmp[] = "Etats consolidés";
        $MENU[5] = array_combine($a_tmp,$b_tmp);
    }*/
    $MENU_TITLE = array(     //signal
    1 => array("Paramétrage","gear","parametrage.php"),
    2 => array("Cadre de résultat","twitch","cadre_resultat.php"),
    3 => array("Programmation","cubes","programmation.php"),
    
    4 => array("Suivi des prjets","twitch","menu_suivi_resultat_ptba.php"),
    /*
    4 => array("Gestion du projet","exchange","suivi_exec.php"),
    5 => array("Rapports","tasks","rapport.php"),*/
    5 => array("Documentation","newspaper-o","liste_document.php"),
    6 => array("Cartographie","map-marker",'cartographie.php'),
    );
}
?>