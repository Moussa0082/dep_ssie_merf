<?php
  class Config
  {
    /* Site Settings */
    var $sitename = "Syst&egrave;me de Suivi - Evaluation";
    var $shortname = "RUCHE";
    var $sitetititle = "Syst&egrave;me de Suivi - Evaluation";
    var $siteshortname = "Projets FIDA au Tchad";

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
    var $MetaDesc = "Syst&egrave;me de Suivi - Evaluation - Bamasoft";
    var $MetaKeys = "Syst&egrave;me de Suivi - Evaluation, SSE, Suivi - Evaluation";
    var $MetaTitle = "Syst&egrave;me de Suivi - Evaluation";
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
  include_once $path.$config->sys_folder."/database/db_connexion.php";
  $MENU = array(
  1 =>array("localites.php"=>"Localit�s/ Sites","unite_gestion.php"=>"Entit�s de gestion","projets.php"=>"Projets"/*,"fonctions.php"=>"Fonction"*/,"users.php"=>array("users.php"=>"Utilisateurs","fonctions.php"=>"Fonction"),"acteurs.php"=>"Partenaires strat�giques","bailleurs.php"=>"Bailleurs","categorie_depense.php"=>"Cat�gories de d�pense","plan_analytique_projet.php"=>"Plan Analytique","plan_budgetaire_projet.php"=>"Plan Budgetaire","categorie_marche.php"=>"March�s","autres_parametres.php"=>"Autres param�tres"/*,"sygri_niveau1_projet.php"=>"SYGRI 1<sup>er</sup> niveau","sygri_niveau2_projet.php"=>"SYGRI 2<sup>�me</sup> niveau","soutien_sygri_niveau2_projet.php"=>"Soutien 2<sup>�me</sup> niveau","indicateur_sygri_fida.php"=>"SYGRI 3<sup>�me</sup> niveau"*/),
  2 => array("referentiel.php"=>array("referentiel.php"=>"Indicateurs r�f�rentiels","referentiel_feuille.php"=>"Lien Fiches -Indicateurs"),"niveau_cr.php"=>"Niveaux cadre logique","cadre_logique.php"=>"Cadre logique"/*,"niveau_i3n.php"=>"Niveaux I3N","indicateur_i3n.php"=>"Indicateurs I3N"*/,"niveau_cosop.php"=>"Niveaux COSOP","indicateur_cosop.php"=>"Indicateurs COSOP", "edit_cadre_mesure_resultat.php"=>"Cadre de mesure de r�sultat","sygri_niveau1_projet.php"=>array("sygri_niveau1_projet.php"=>"Indicateurs SYGRI","sygri_niveau2_projet.php"=>"SYGRI 2�me Niveau","soutien_sygri_niveau2_projet.php"=>"Indicateurs de soutien SYGRI II","print_indicateur_sygri_niveau3.php"=>"SYGRI 3�me Niveau"), "repertoire_sygri.php"=>"R�pertoire SYGRI"/*,"appendice4.php"=>"Appendice 4"*/),
  3 => array(/*"liste_convention.php"=>"Conventions cadres",*/"plan_ptba.php"=>"PTBA"/*,"plan_marche.php"=>array("plan_marche.php"=>"PPM","plan_passation.php"=>"Planification du PPM","suivi_passation.php"=>"Suivi du PPM")*/,"analyse_budgetaire.php"=>"Analyse budgetaire","jalon_activite.php"=>"Jalons des activit�s"),
  4 => array(/*"suivi_referentiel.php"=>"Suivi Indicateurs r�f�rentiels",*/"suivi_indicateur_ptba.php"=>"Suivi du PTBA","fiches_dynamiques.php"=>"Fiches dynamiques"),
 // 5 => array("mission_supervision.php"=>"Mission de supervision","liste_dno.php"=>"DANO","agenda.php"=>"Agenda","parametres_gestion_projet.php"=>"Param�tres"),
 // 6 => array("s_supervision.php"=>"Gestion de projet","s_programmation.php"=>"Programmation"/*,"s_suivi_resultat.php"=>"Suivi des r�sultats"*/,"s_sygri.php"=>"Rapports SYGRI","rapports_dynamiques.php"=>"Rapports dynamiques"),
  7 => array("liste_document.php"=>"Documents"),
 // 8 => array("map.php"=>"Cartographie")
  );
  $MENU_TITLE = array(     //signal
  1 => array("Param�trage","gear","parametrage.php"),
 2 => array("Cadre de r�sultat","twitch","cadre_resultat.php"),
 3 => array("Programmation","calculator","programmation.php"),
 4 => array("Suivi des r�sultats","cubes","suivi_resultat.php"),
 // 5 => array("Gestion du projet","exchange","suivi_exec.php"),
 // 6 => array("Rapports","tasks","rapport.php"),
  7 => array("Documentation","newspaper-o","document.php"),
 // 8 => array("Cartographie","map-marker",'cartographie.php'),
  );
?>