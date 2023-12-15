<?php
    class Config
    {
        /* Site Settings */
        var $sitename = "Ministère de l'Environnement et de la Ressource Forestière";
        var $shortname = "Système Informatisé de Suivi-évaluation ";
        var $sitetititle = "MERF";
        var $siteshortname = "Ministère de l'Environnement et de la Ressource Forestière";
        var $siteshortdescription = "Système de Suivi-Evaluation\nMinistère de l'Environnement et de la Ressource Forestière";
        var $siteurl = "https://ssise-merf.org/";
        /* dev env */
        var $db = null;
        /* Folders Settings */
        var $class_folder = "classes";
        var $config_folder = "api";
        var $img_folder = "images";
        var $icon_folder = "icons";
        var $script_folder = "scripts";
        /* Meta Settings */
        var $MetaDesc = "Système de Suivi - Evaluation - COSIT";
        var $MetaKeys = "Système de Suivi - Evaluation, SSE, Suivi - Evaluation, MERF, Togo";
        var $MetaTitle = "Système de Suivi - Evaluation";
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
    include_once $path.$config->config_folder."/db.php";
    $config->db = $db;
    $MENU = array( 
    4 => array("fiches_dynamiques.php"=>array("fiches_dynamiques.php"=>"Classeurs dynamiques|Les Classeurs des fiches dynamiques","classeur_details.php"=>"Fiches dynamiques|Les Fiches dynamiques","disposition_mobile_formulaire.php"=>"Fiches dynamiques|Disposition Mobile"),),
    5 => array("rapports_dynamiques.php"=>array("rapports_dynamiques.php"=>"Rapports dynamiques|Création de rapport à partir des fiches dynamiques","rapports_dynamiques_simple_creation.php"=>"Rapports dynamiques simples|Création de rapport simple à partir des fiches dynamiques","rapports_dynamiques_simple_modification.php"=>"Rapports dynamiques simple|Modification de rapport dynamique simple","rapport_details_simple.php"=>"Rapports dynamiques|Rapport simple","rapports_dynamiques_croise_creation.php"=>"Rapports dynamiques croisés|Création de rapport croisé à partir des fiches dynamiques","rapports_dynamiques_croise_modification.php"=>"Rapports dynamiques croisé|Modification de rapport dynamique croisé","rapport_details_croise.php"=>"Rapports dynamiques|Rapport croisé"),"rapports_indicateur.php"=>"Rapports indicateurs|Rapports des Indicateurs"),
    );
    $MENU_TITLE = array(
    4 => array("Suivi des résultats","cubes","suivi_resultat.php"),
    5 => array("Etats & Rapports","tasks","rapport.php"),
    );
?>