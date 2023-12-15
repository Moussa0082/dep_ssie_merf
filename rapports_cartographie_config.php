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

if ((isset($_GET["id_sup"]) && !empty($_GET["id_sup"]))) {
  $id = ($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."requete_carte_config WHERE id=%s",
                       GetSQLValueString($id, "int"));
  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1") && isset($_POST['colonneV']) && !empty($_POST['colonneV']))
{ $_POST['feuille']="";
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $personnel=$_SESSION['clp_id']; $date=date("Y-m-d"); $show_colonne=implode(";",$_POST['show_colonne']); $colonneV=implode(";",$_POST['colonneV']);
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."requete_carte_config (intitule, classeur, colonneV, show_colonne, projet, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, '$personnel', '$date')",
	                   GetSQLValueString($_POST['intitule'], "text"),
                       GetSQLValueString($_POST['classeur'], "int"),
					   GetSQLValueString($colonneV, "text"),
                       GetSQLValueString($show_colonne, "text"),
					   GetSQLValueString($_SESSION['clp_projet'], "text"));

    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    if($Result1)
    {
             //Create view
        $table_join = $lescolonneafficher = array();  $show_colonne="";
          $_GET['id_conf'] = $pdar_connexion->lastInsertId();
        if(isset($_GET['id_conf']) && intval($_GET['id_conf']>0)){  $id_conf=intval($_GET['id_conf']);
        $query_liste_conf = "SELECT * FROM ".$database_connect_prefix."requete_carte_config WHERE projet='".$_SESSION["clp_projet"]."' and id=$id_conf ";
        try{
            $liste_conf = $pdar_connexion->prepare($query_liste_conf);
            $liste_conf->execute();
            $row_liste_conf = $liste_conf ->fetch();
            $totalRows_liste_conf = $liste_conf->rowCount();
        }catch(Exception $e){ die(mysql_error_show_message($e)); }
	//nom feuille
	   $temp=explode("/",$row_liste_conf["colonneV"]);  $champ_coordonnees=$temp[0]; $table_coordonnees=$temp[1];
	   
	  // echo $table_coordonnees."  /  ";echo $query_entete."  /  ";
	     //gestion des jointure
        $query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table` = '$table_coordonnees'";
		
		 
        try{
            $entete = $pdar_connexion->prepare($query_entete);
            $entete->execute();
            $row_entete = $entete ->fetch();
            $totalRows_entete = $entete->rowCount();
        }catch(Exception $e){ die(mysql_error_show_message($e)); }

        if($totalRows_entete>0){ $libelle=explode("|",$row_entete["libelle"]); $nomcol=$row_entete["nom"]; }
  foreach($libelle as $llib1)
  {
    $lib=explode("=",$llib1);
    $libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";
  }
	
        $classeur=$row_liste_conf["classeur"];

		$temp0=explode(";",$row_liste_conf["show_colonne"]);
        foreach($temp0 as $t){ $temp=explode("/",$t); if(isset($temp[1])) {$champs=$temp[0];  $show_colonne.="`$champs` ,"; } }
     // echo  $show_colonne; exit;
	  // $temp=explode("/",$row_liste_conf["colonneV"]);  $champ_coordonnees=$temp[0]; $table_coordonnees=$temp[1];
	   $tempc=explode(";",$row_liste_conf["colonneV"]);
        foreach($tempc as $t){ $temp=explode("/",$t); if(isset($temp[1])) {$champs=$temp[0];  $champ_coordonnees.="`$champs` ,"; } }
	
     $query_view = "SELECT $show_colonne LG, LT FROM $table_coordonnees where 1=1";
	  
	// echo  $query_view; exit;
   
     $query_creation_view = "CREATE OR REPLACE VIEW `v_carte_".$_GET['id_conf']."` AS $query_view ";
        try{
            $creation_view = $pdar_connexion->prepare($query_creation_view);
            $creation_view->execute();
        }catch(Exception $e){ /*die(mysql_error_show_message($e));*/ }
    }
	
	//insertion nom vieu requete indicateur
	 $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."t_requete_carte (intitule, Nom_View,  Id_Projet, requete_conf, codeSQL, fiche_carto) VALUES (%s, %s, %s, %s, %s, %s)",
	                   GetSQLValueString($row_liste_conf["intitule"], "text"),
                       GetSQLValueString("v_carte_".$_GET['id_conf']."", "text"),
					   GetSQLValueString($_SESSION['clp_projet'], "text"),
					   GetSQLValueString($_GET['id_conf'], "int"),
					   GetSQLValueString($query_view, "text"),
					   GetSQLValueString($table_coordonnees, "text"));

    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
	// fin requete
}

    $insertGoTo = $_SERVER['PHP_SELF'];
    if($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
      $id = intval($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."requete_carte_config WHERE id=%s",
                           GetSQLValueString($id, "int"));

      try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
      }catch(Exception $e){ die(mysql_error_show_message($e)); }
      if($Result1)
      {
          $_GET['id_conf'] = $id;
            $query_act = "DROP VIEW IF EXISTS `rapport_v".$_GET['id_conf']."` ";
            try{
                $act = $pdar_connexion->prepare($query_act);
                $act->execute();
            }catch(Exception $e){ /*die(mysql_error_show_message($e));*/ }
      }
      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"])) && isset($_POST['colonne']) && isset($_POST['colonneV']) && !empty($_POST['colonne']) && !empty($_POST['colonneV'])) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];  $id = ($_POST["MM_update"]);  //$show_colonne=implode(";",$_POST['show_colonne']);
  
  
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."requete_carte_config SET  indicateur=%s, classeur=%s, colonne=%s, colonneV=%s, mode=%s, Colonnecritere1=%s, Colonnecritere2=%s, Colonnecritere3=%s, modeC1=%s, modeC2=%s, modeC3=%s, valeur_critere1=%s, valeur_critere2=%s, valeur_critere3=%s, cl1=%s, cl2=%s, cl3=%s, classeur2=%s, colonneC1=%s, colonneC2=%s,  etat=1, modifier_par='$personnel', modifier_le='$date' WHERE id=%s",
	                   GetSQLValueString($_POST['indicateur'], "text"),
                       GetSQLValueString($_POST['classeur'], "int"),
                       GetSQLValueString($_POST['colonne'], "text"),
					   GetSQLValueString($_POST['colonneV'], "text"),
                       GetSQLValueString($_POST['mode'], "text"),
                       GetSQLValueString($_POST['Colonnecritere1'], "text"),
                       GetSQLValueString($_POST['Colonnecritere2'], "text"),
					   GetSQLValueString($_POST['Colonnecritere3'], "text"),
                       GetSQLValueString($_POST['modeC1'], "text"),
                       GetSQLValueString($_POST['modeC2'], "text"),
                       GetSQLValueString($_POST['modeC3'], "text"),
                       GetSQLValueString($_POST['valeur_critere1'], "text"),
                       GetSQLValueString($_POST['valeur_critere2'], "text"),
                       GetSQLValueString($_POST['valeur_critere3'], "text"),
                       GetSQLValueString($_POST['cl1'], "text"),
                       GetSQLValueString($_POST['cl2'], "text"),
                       GetSQLValueString($_POST['cl3'], "text"),
                       GetSQLValueString($_POST['classeur2'], "int"),
                       GetSQLValueString($_POST['colonneC1'], "text"),
                       GetSQLValueString($_POST['colonneC2'], "text"),
					   GetSQLValueString($id, "text"));

    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    if($Result1)
    {
        //Create view
        $table_join = $lescolonneafficher = array();  $show_colonne="";
        $_GET['id_conf'] = $id;
        if(isset($_GET['id_conf']) && intval($_GET['id_conf']>0)){  $id_conf=intval($_GET['id_conf']);
        $query_liste_conf = "SELECT * FROM ".$database_connect_prefix."requete_carte_config WHERE projet='".$_SESSION["clp_projet"]."' and id=$id_conf ";
        try{
            $liste_conf = $pdar_connexion->prepare($query_liste_conf);
            $liste_conf->execute();
            $row_liste_conf = $liste_conf ->fetch();
            $totalRows_liste_conf = $liste_conf->rowCount();
        }catch(Exception $e){ die(mysql_error_show_message($e)); }
		
		// champ de regroupement
		$typecpt="";
        $classeur=$row_liste_conf["classeur"];
        $temp=explode("/",$row_liste_conf["colonne"]); $table_regroupement=$temp[1]; $champ_regroupement=$temp[0]; $groupe_by=" group by year($table_regroupement.$champ_regroupement)";
        $row_liste_conf["colonne"]=$temp[0]; if(isset($temp[1])){ $table_join[$temp[1]]=$temp[1]; $colonneTab=$temp[1]; }
        $temp=explode("/",$row_liste_conf["colonneV"]); $table_valeurf=$table_valeur=$temp[1]; $champ_valeur=$temp[0];
        $row_liste_conf["colonneV"]=$temp[0]; if(isset($temp[1])){ $table_join[$temp[1]]=$temp[1]; $colonneVTab=$temp[1]; }
		
		 if($row_liste_conf["mode"]=="somme") $formule="sum"; elseif($row_liste_conf["mode"]=="moyenne") $formule="avg"; elseif($row_liste_conf["mode"]=="compter") $formule="count"; 
     elseif($row_liste_conf["mode"]=="compteru") {$formule="count"; $typecpt="distinct ";}
	 // $query_view = "SELECT $formule($table_valeur.$champ_valeur) as valeur, year($table_regroupement.$champ_regroupement) as annee FROM $table_valeur where 1=1";
	  //jointure
		 $wherejointure="";
		    if(isset($row_liste_conf["classeur2"]) && !empty($row_liste_conf["classeur2"]) && isset($row_liste_conf["colonneC1"]) && !empty($row_liste_conf["colonneC1"]) && isset($row_liste_conf["colonneC2"]) && !empty($row_liste_conf["colonneC2"])){
     $temp=explode("/",$row_liste_conf["colonneC1"]); $table_cl1=$temp[1]; $champ_cl1=$temp[0];
	 $temp2=explode("/",$row_liste_conf["colonneC2"]); $table_cl2=$temp2[1]; $champ_cl2=$temp2[0]; //$modec2=$row_liste_conf["modeC2"]; $valc2=$row_liste_conf["valeur_critere2"];
	 $wherejointure=" and $table_cl1.$champ_cl1=$table_cl2.$champ_cl2";
	 $table_valeurf.=",".$table_cl2;
     }
     
	  $query_view = "SELECT $formule($typecpt$table_valeur.$champ_valeur) as valeur, year($table_regroupement.$champ_regroupement) as annee FROM $table_valeurf where 1=1  $wherejointure";
// les criteres
 $critere1= $critere2= $critere3="";
  if(isset($row_liste_conf["Colonnecritere1"]) && !empty($row_liste_conf["Colonnecritere1"])){
     $temp=explode("/",$row_liste_conf["Colonnecritere1"]); $table_c1=$temp[1]; $champ_c1=$temp[0]; $modec1=$row_liste_conf["modeC1"]; $valc1=$row_liste_conf["valeur_critere1"];
	 $criterec1=$row_liste_conf["cl1"];
	$critere1=" $criterec1 $table_c1.$champ_c1$modec1'$valc1'";
     }
	 
	   if(isset($row_liste_conf["Colonnecritere2"]) && !empty($row_liste_conf["Colonnecritere2"])){
     $temp=explode("/",$row_liste_conf["Colonnecritere2"]); $table_c2=$temp[1]; $champ_c2=$temp[0]; $modec2=$row_liste_conf["modeC2"]; $valc2=$row_liste_conf["valeur_critere2"];
	  $criterec2=$row_liste_conf["cl2"];
	 $critere2=" $criterec2 $table_c2.$champ_c2$modec2'$valc2'";
     }
	   if(isset($row_liste_conf["Colonnecritere3"]) && !empty($row_liste_conf["Colonnecritere3"]) && !is_null($row_liste_conf["Colonnecritere3"])){
     $temp=explode("/",$row_liste_conf["Colonnecritere3"]); $table_c3=$temp[1]; $champ_c3=$temp[0]; $modec3=$row_liste_conf["modeC3"]; $valc3=$row_liste_conf["valeur_critere3"];
	  $criterec3=$row_liste_conf["cl3"];
	 $critere3=" $criterec3 $table_c3.$champ_c3$modec3'$valc3'";
     }
	 
	  $query_view .= " $critere1 $critere2 $critere3  $groupe_by";
	  
	 echo  $query_view; exit;
   
     $query_creation_view = "CREATE OR REPLACE VIEW `v_resultat_".$_GET['id_conf']."` AS $query_view ";
        try{
            $creation_view = $pdar_connexion->prepare($query_creation_view);
            $creation_view->execute();
        }catch(Exception $e){ /*die(mysql_error_show_message($e));*/ }
    }
	
	//insertion nom vieu requete indicateur
	 $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."t_requete_indicateur (indicateur, Nom_View,  Id_Projet, requete_conf) VALUES (%s, %s, %s, %s)",
	                   GetSQLValueString($row_liste_conf["indicateur"], "int"),
                       GetSQLValueString("v_resultat_".$_GET['id_conf']."", "text"),
					   GetSQLValueString($_SESSION['clp_projet'], "text"),
					    GetSQLValueString($_GET['id_conf'], "int"));

    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
	// fin requete
}
    $insertGoTo = $_SERVER['PHP_SELF'];
    if($Result1) $insertGoTo .= $page."?update=ok"; else $insertGoTo .= $page."?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
//and h.projet='".$_SESSION["clp_projet"]."'
$query_liste_conf = "SELECT h.*, c.couleur as couleur_c FROM ".$database_connect_prefix."requete_carte_config h, ".$database_connect_prefix."classeur c WHERE projet='".$_SESSION["clp_projet"]."' and c.id_classeur=h.classeur ";
try{
    $liste_conf = $pdar_connexion->prepare($query_liste_conf);
    $liste_conf->execute();
    $row_liste_conf = $liste_conf ->fetchAll();
    $totalRows_liste_conf = $liste_conf->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
//WHERE ".$_SESSION["clp_where"]."
$query_liste_classeur = "SELECT * FROM ".$database_connect_prefix."classeur ";
try{
    $liste_classeur = $pdar_connexion->prepare($query_liste_classeur);
    $liste_classeur->execute();
    $row_liste_classeur = $liste_classeur ->fetchAll();
    $totalRows_liste_classeur = $liste_classeur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_classeur_array = array();
if($totalRows_liste_classeur>0){  foreach($row_liste_classeur as $row_liste_classeur){ 
$liste_classeur_array[$row_liste_classeur["id_classeur"]]=$row_liste_classeur["libelle"];
} }

$query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config ";
try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetchAll();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$entete_array = $nom_array = array();
if($totalRows_entete>0){ foreach($row_entete as $row_entete){
  $entete_array[$row_entete["table"]]=$row_entete["nom"]; $libelle=explode("|",$row_entete["libelle"]);
  foreach($libelle as $llib1)
  {
    $lib=explode("=",$llib1);
    $libelle_array[$row_entete["table"]][$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";
  }
} }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
 <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/form_validation.js"></script>
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>
</head>
<body>
 <header class="header navbar navbar-fixed-top" role="banner">
    <?php include_once("includes/header.php"); ?>
 </header>
<div id="container">
    <div id="sidebar" class="sidebar-fixed">
        <div id="sidebar-content">
            <?php include_once("includes/menu_top.php"); ?>
        </div>
        <div id="divider" class="resizeable"></div>
    </div>

    <div id="content">
        <div class="container">
            <div class="crumbs">
                <?php include_once("includes/sous_menu.php"); ?>
            </div>
        <div class="page-header">
            <div class="p_top_5">
<!-- Site contenu ici -->
<?php include_once("modal_add.php"); ?>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
.menu_head {
  padding: 5px; cursor: pointer; background-color: #060; color: #FFF;
}

</style>
<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box">
<!--<div class="widget-header1"> <center><h4><?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>-->
<div class="widget-header"> <h4><i class="icon-reorder"></i> Configuration des r&eacute;qu&ecirc;te dynamiques pour la carte </h4>

  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
  <?php
echo do_link("","","Ajout d'une r&eacute;qu&ecirc;te pour la carte","<i class=\"icon-plus\"> Nouvelle r&eacute;qu&ecirc;te pour la carte </i>","","./","pull-right p11","get_content('new_rapport_feuille_carto.php','','modal-body_add',this.title);",1,"",$nfile);
?>
  <?php } ?>
<a href="rapports_dynamiques.php" title="Retour" class="pull-right p11"><i class="icon-close">&nbsp;Retour </i></a></div>

<div class="widget-content">
<table class="table table-striped table-bordered table-hover table-responsive datatable  dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Libell&eacute;</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Classeur</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Feuille</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Colonne Coordonn&eacute;es </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Colonnes &agrave; afficher</th>
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) {?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="90">Actions</th>
<?php }?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php if($totalRows_liste_conf>0) { $i=0; foreach($row_liste_conf as $row_liste_conf){  $id = $row_liste_conf['id']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
  <td class=" "><?php echo $row_liste_conf['intitule']; ?></td>
  <td align="left"><?php echo (isset($liste_classeur_array[$row_liste_conf['classeur']]))?$liste_classeur_array[$row_liste_conf['classeur']]:'NaN'; ?></td>
<td class=" "><?php $f=explode("/",$row_liste_conf['colonne']); $fv=array();
if(isset($f[1])) $fv[$f[1]]=$f[1]; $f=explode("/",$row_liste_conf['colonneV']);
if(isset($f[1])) $fv[$f[1]]=$f[1]; $ff=explode(";",$row_liste_conf['show_colonne']);
foreach($ff as $f){ $f=explode("/",$f);  if(isset($f[1])) $fv[$f[1]]=$f[1]; } echo "<ul style='padding-left: 10px;'>";
foreach($fv as $nomtab){ echo "<li>".((isset($entete_array[$nomtab]))?"- ".$entete_array[$nomtab]:'NaN')."</li>"; } echo "</ul>"; ?></td>
<td align="left"><?php $f=explode("/",$row_liste_conf['colonneV']); echo (isset($libelle_array[$f[1]][$f[0]]))?"<span style='color:green !important;'><b>".$row_liste_conf['mode']."</b></span>( ".$libelle_array[$f[1]][$f[0]]." )":$f[0]; ?></td>
<td align="left"><?php
$fv=array(); $ff=explode(";",$row_liste_conf['show_colonne']);
foreach($ff as $f){ $f=explode("/",$f);  if(isset($f[0])) $fv[$f[0]]=$f[1]; } echo "<ul style='padding-left: 10px;'>";
foreach($fv as $f0=>$f1){ echo "<li>".((isset($libelle_array[$f1][$f0]))?$libelle_array[$f1][$f0]:$f0)."</li>"; } echo "</ul>"; ?></td>
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) {?>
<td class=" " align="center"><?php
echo do_link("","","Modifier une r&eacute;qu&ecirc;te ","","edit","./","","get_content('new_rapport_feuille_carto.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=$id","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cet &eacute;l&eacute;ment ?');",0,"margin:0px 5px;",$nfile);
?></td>
<?php }?>
</tr>
<?php } } ?>
</tbody></table>
</div>

</div>

<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>