<?php

///////////////////////////////////////////////

/*                 SSE                       */

/*	Conception & DÃƒÂ©veloppement: SEYA SERVICES */

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



if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y");



$dir = './attachment/fiche_collecte/';

if(!is_dir($dir)) mkdir($dir);

//liste village
$query_liste_village = "SELECT code_commune,nom_commune  FROM commune  order by code_commune asc";
    	   try{
    $liste_village = $pdar_connexion->prepare($query_liste_village);
    $liste_village->execute();
    $row_liste_village = $liste_village ->fetchAll();
    $totalRows_liste_village = $liste_village->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$village_array = array();
if($totalRows_liste_village>0){  foreach($row_liste_village as $row_liste_village){ 
  $village_array[$row_liste_village["nom_commune"]] = $row_liste_village["code_commune"];
}  }

//import
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form0"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert"))
  {
    $poids_max=2048576; //Poids maximal du fichier en octets
    $extensions_autorisees=array('xls','xlsx'); //Extensions autorisées ,'csv'
    $url_site='./attachment/'; //Adresse où se trouve le fichier upload.
    $page = $_SERVER['PHP_SELF'];
    $ext = substr(strrchr($_FILES['fichier']['name'], "."), 1);

    $feuille = $_POST["feuille"]; 
    $annee=isset($_POST["annee"])?$_POST["annee"]:date("Y");
    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
    $interdit_array = array("classeur",$_POST["idf"],$_POST["ide"],"annee","projet","structure","code_activite","id_personnel","date_enregistrement","modifier_le","modifier_par","etat");

    $query_entete = "DESCRIBE ".$database_connect_prefix."$feuille";
	    	   try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetchAll();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

    $entete_array = array();
    if($totalRows_entete>0){  foreach($row_entete as $row_entete){ 
      $entete_array[$row_entete["Field"]]=$row_entete["Type"]; }
     }

    if(in_array($ext,$extensions_autorisees))
    {
      if($_FILES['fichier']['size']>$poids_max)
      {
        $message='Un ou plusieurs fichiers sont trop lourds !';
        echo $message;
      }
      elseif(isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0)
      {
        $inputFileName=$url_site.$_FILES['fichier']['name'];
        move_uploaded_file($_FILES['fichier']['tmp_name'],$inputFileName);

        require_once('Classes/PHPExcel.php');
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
            . '": ' . $e->getMessage());
        }
        if(isset($_POST["erase"]) && $_POST["erase"]==1)
        {
		
         // mysql_select_db($database_pdar_connexion, $pdar_connexion);
		   if(isset($_POST["vide"]) && $_POST["vide"]>0)   {
		   $nve=$_POST["ide"]; $valie=$_POST["vide"];
          $query_sup_import_annee = "DELETE FROM ".$database_connect_prefix."$feuille WHERE $nve=$valie";
		  }
		  else 
		  {
		 
            $query_sup_import_annee = "DELETE FROM ".$database_connect_prefix."$feuille";
			
		  }
 try{
    $Result1 = $pdar_connexion->prepare($query_sup_import_annee);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }    
      }

       /* mysql_select_db($database_pdar_connexion, $pdar_connexion);
        $query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$feuille' and intitule is null";
        $entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
        //$row_entete  = mysql_fetch_assoc($entete);
        $totalRows_entete  = mysql_num_rows($entete);*/
        $k = 2;
		
        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = $k; $row <= $highestRow; $row++)
        {
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
            NULL, TRUE, FALSE);
            if(!empty($rowData[0][0]) && strtolower(trim($rowData[0][0]))!="total")
            {
              $sql='' ; $titre="";  $key = date("ymdi").(date("s")+$row).$_SESSION['clp_n'];
               //$sql.= GetSQLValueString($key, "text").',';
			   if(isset($_POST["vide"]) && $_POST["vide"]>0)   { $sql.= GetSQLValueString( $_POST["vide"], "int").','; $nve=$_POST["ide"]; $titre.="`$nve`,";}
			  // if(isset($_POST["vide"]) && $_POST["vide"]>0)    $titre.= GetSQLValueString( $_POST["vide"], "int").',';
              $i=0; foreach($entete_array as $name=>$value){
if(!in_array($name,$interdit_array)){
$titre.="`$name`,";
//str_replace("'", "\'", $rowData[0][$i]);
// $village_array[$row_liste_village["nom_pde"]] = $row_liste_village["code_pde"];
 
/*if(isset($entete_array[$name]) && $name=="bassins" && isset($village_array[$rowData[0][$i]])) $sql.= GetSQLValueString(trim(utf8_decode($village_array[$rowData[0][$i]])),"text").',';
elseif(isset($entete_array[$name]) && $name=="bassins" && !isset($village_array[$rowData[0][$i]])) $sql.= GetSQLValueString("ND","text").',';
else*/
if(isset($entete_array[$name]) && (strtolower($entete_array[$name])=="int" || strtolower($entete_array[$name])=="double")) $sql.= GetSQLValueString(trim($rowData[0][$i]), strtolower($entete_array[$name])).',';
elseif(isset($entete_array[$name]) && strtolower($entete_array[$name])!="date") $sql.= GetSQLValueString(trim(utf8_decode(str_replace("'", "\'", $rowData[0][$i]))), "text").','; else{
if(isset($rowData[0][$i]) && !empty($rowData[0][$i]) && strchr(trim($rowData[0][$i]),"/")) $mdate = implode("-",array_reverse(explode("/",trim($rowData[0][$i])))); elseif(isset($rowData[0][$i]) && !empty($rowData[0][$i])) { $timestamp = PHPExcel_Shared_Date::ExcelToPHP(trim($rowData[0][$i])); $mdate = date('Y-m-d', $timestamp); } else $mdate = "0000-00-00"; $sql.= '"'.$mdate.'",'; } $i++; } }
$sql=substr($sql,0,strlen($sql)-1);


 if(isset($_POST["vide"]) && $_POST["vide"]>0)   { 
// mysql_select_db($database_pdar_connexion, $pdar_connexion);
$insertSQL = 'INSERT INTO '.$database_connect_prefix.$feuille.' ('.substr($titre,0,strlen($titre)-1).',`projet`,`date_enregistrement`,`id_personnel`) VALUES ('.$sql.',"'.$_SESSION['clp_projet'].'","'.$date.'","'.$personnel.'")';
 try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
 } else {
 
 //mysql_select_db($database_pdar_connexion, $pdar_connexion);
$insertSQL = 'INSERT INTO '.$database_connect_prefix.$feuille.' ('.substr($titre,0,strlen($titre)-1).',`projet`, `date_enregistrement`, `id_personnel`) VALUES ('.$sql.',"'.$_SESSION['clp_projet'].'","'.$date.'","'.$personnel.'")';
 try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
}

//echo $insertSQL."<br />";
           }
          }
          unlink($inputFileName);   //exit;
          if($Result1) $insertGoTo = $page."?import=ok";
          else $insertGoTo = $page."?import=no";
          $insertGoTo .= "&id=$classeur&feuille=$database_connect_prefix"."$feuille&annee=$annee";
          header(sprintf("Location: %s", $insertGoTo)); exit();
        }
    }
    else
    {
      $insertGoTo = $page."?import=no";
      $insertGoTo .= "&id=$classeur&feuille=$database_connect_prefix"."$feuille&annee=$annee";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }
  }
}

if(isset($_GET["id_sup"]))

{

  $id=intval($_GET["id_sup"]);

  $query_sup_act = "DELETE FROM fiche_ong WHERE id_ong='$id'";
 try{
    $Result1 = $pdar_connexion->prepare($query_sup_act);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];

  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";

  $insertGoTo .= "&annee=$annee";

 // mysql_free_result($Result1);

  header(sprintf("Location: %s", $insertGoTo));

}



if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))

{ //Atelier

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {

    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; //$lieu = array();

//TDR

  /*$a = explode(',',$_POST['lieu']);  foreach($a as $b){ $c = explode(':',$b); if(isset($c[0]) && !empty($c[0])) $lieu[] = $c[0]; } */

  $a = explode('/',$_POST['date_collecte']); $annee = isset($a[2])?$a[2]:date("Y");

  $insertSQL = sprintf("INSERT INTO fiche_ong (code_ugl, sigle_ong, adresse_ong, nom_ong, sexe, cercle_couvert, date_creation, nom_responsable, email, telephone, observation, lieu_elaboration, date_collecte, nom_collecteur, fonction, contact_ong, projet, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,%s, %s, '$personnel', '$date')",
                       GetSQLValueString($_POST['code_ugl'], "text"),
                       GetSQLValueString($_POST['sigle_ong'], "text"),
					     GetSQLValueString($_POST['adresse_ong'], "text"),
 					  GetSQLValueString($_POST['nom_ong'], "text"),
					  GetSQLValueString($_POST['sexe'], "text"),
					 GetSQLValueString(implode(',',$_POST['cercle_couvert']), "text"),
  					  GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_creation']))), "date"),
					   GetSQLValueString($_POST['nom_responsable'], "text"),
					  GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['telephone'], "text"),
                       GetSQLValueString($_POST['observation'], "text"),
					   GetSQLValueString($_POST['lieu_elaboration'], "text"),
  					  GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_collecte']))), "date"),
					    GetSQLValueString($_POST['nom_collecteur'], "text"),
                       GetSQLValueString($_POST['fonction'], "text"),
					   GetSQLValueString($_POST['contact_ong'], "text"),
					 // GetSQLValueString($_SESSION["clp_projet"], "text"),
					  GetSQLValueString($_SESSION["clp_projet"], "text"));
 try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";

    $insertGoTo .= "&annee=$annee";

    header(sprintf("Location: %s", $insertGoTo));  exit();


 //FIN TDR

  }




    if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {

    $id = $_POST["MM_delete"];

    $insertSQL = sprintf("DELETE from fiche_ong WHERE id_ong=%s",

                         GetSQLValueString($id, "text"));



 try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
    $insertGoTo = $_SERVER['PHP_SELF'];

    if ($Result1){ $insertGoTo .= "?del=ok"; }  else $insertGoTo .= "?del=no";

    $insertGoTo .= "&annee=$annee";

    header(sprintf("Location: %s", $insertGoTo)); exit();

  }



  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {

  $id = $_POST["MM_update"]; $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

   // $link = ""; $Result1 = false; $lieu = array();

  

  /*$a = explode(',',$_POST['lieu']);  foreach($a as $b){ $c = explode(':',$b); if(isset($c[0]) && !empty($c[0])) $lieu[] = $c[0]; }*/

  $a = explode('/',$_POST['date_collecte']); $annee = isset($a[2])?$a[2]:date("Y"); 

  $insertSQL = sprintf("UPDATE fiche_ong SET code_ugl=%s, sigle_ong=%s, adresse_ong=%s, nom_ong=%s, sexe=%s, cercle_couvert=%s, date_creation=%s, nom_responsable=%s, email=%s, telephone=%s, observation=%s, contact_ong=%s, lieu_elaboration=%s, date_collecte=%s, nom_collecteur=%s, fonction=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_ong='$id'",

                        GetSQLValueString($_POST['code_ugl'], "text"),
                       GetSQLValueString($_POST['sigle_ong'], "text"),
					     GetSQLValueString($_POST['adresse_ong'], "text"),
 					  GetSQLValueString($_POST['nom_ong'], "text"),
					  GetSQLValueString($_POST['sexe'], "text"),
					  GetSQLValueString(implode(',',$_POST['cercle_couvert']), "text"),
  					  GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_creation']))), "date"),
					   GetSQLValueString($_POST['nom_responsable'], "text"),
					  GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['telephone'], "text"), 
                       GetSQLValueString($_POST['observation'], "text"),
					    GetSQLValueString($_POST['contact_ong'], "text"),
					   GetSQLValueString($_POST['lieu_elaboration'], "text"),
  					  GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_collecte']))), "date"),
					    GetSQLValueString($_POST['nom_collecteur'], "text"),
                       GetSQLValueString($_POST['fonction'], "text"));


 try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }


    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];

    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";

    $insertGoTo .= "&annee=$annee";

    header(sprintf("Location: %s", $insertGoTo)); exit();



}
  }

//liste region
$query_liste_region = "SELECT *  FROM ugl where code_ugl!='01'  order by code_ugl   asc";
 try{
    $liste_region = $pdar_connexion->prepare($query_liste_region);
    $liste_region->execute();
    $row_liste_region = $liste_region ->fetchAll();
    $totalRows_liste_region = $liste_region->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$tab_array = array();
if($totalRows_liste_region>0){  foreach($row_liste_region as $row_liste_region){ 
  $tab_array[$row_liste_region["code_ugl"]] = $row_liste_region["abrege_ugl"];
}  }


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

  <title><?php print $config->sitename; ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />
  <meta name="keywords" content="<?php print $config->MetaKeys; ?>" />
  <meta name="description" content="<?php print $config->MetaDesc; ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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

<script>     /*

$(".tab-pane").slimscroll({

                        height: "100%",

                        wheelStep: 7

                    });  */



function show_tab(tab) {

    if (tab.html()) {

        tab.load(tab.attr('data-target'));

    }

}



function init_tabs() {

    show_tab($('.tab-pane.active'));

    $('a[data-toggle="tab"]').click('show', function(e) {

        tab = $('#' + $(e.target).attr('href').substr(1));

        show_tab(tab);

    });

}



$(function () {
    init_tabs();
});



</script>

<?php include_once 'modal_add.php'; ?>

<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {

  border-spacing: 0px !important; border-collapse: collapse;

} .table tbody tr td {vertical-align: middle; }



</style>

<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Fiche  d'identification des ONG de facilitation </h4>
  <?php echo do_link("","./base_de_donnees.php","Back","<u><i>Retour aux fiches de collecte</i></u>","","./","pull-right p11","",0,"",$nfile); 
  $feuille="fiche_ong";
$idf="id_ong";

// echo do_link("","","Importation depuis un format excel","<i class=\"icon-plus\"> Importer </i>","","./","pull-right p11","get_content('import_fiches_statiques.php','feuille=$feuille&idf=$idf&annee=$annee','modal-body_add',this.title);",1,"margin-top:-5px;",$nfile);?>
  <a onclick="get_content('new_fiche_ong.php','<?php echo "annee=$annee"; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Fiche d'identification des ONG de facilitation" class="pull-right p11" dir=""><i class="icon-plus"> Ajout d'ONG de facilitation  </i></a>
   <?php
//echo do_link("","","Requ�tes pr�d�finies","RECAP : Microprojets","","./","pull-right p11","get_content('requete_donnees_generale_mp.php','','modal-body_add',this.title,'iframe');",1,"",$nfile);?>
</div>

<div class="widget-content">

<div class="tabbable tabbable-custom" >

  <ul class="nav nav-tabs" >

  <?php $j=0; foreach($tab_array as $a=>$b){ ?>

    <li title="" class="<?php echo ($j==$annee || $j==0)?"active":""; ?>"><a href="#tab_feed_<?php echo $j; ?>" data-toggle="tab"><?php echo $b; ?></a></li>

  <?php $j++; } ?>

  </ul>

  <div class="tab-content">

  <?php $j=0; foreach($tab_array as $a=>$b){ ?>
 <div class="tab-pane <?php echo ($j==$annee || $j==0)?"active":""; ?>" id="tab_feed_<?php echo $j; ?>" data-target="./fiche_ong_content.php?r=<?php echo $a; ?>" >

  </div>

  <?php $j++; } ?>

  </div>

</div>

</div></div>



</div>

<!-- Fin Site contenu ici -->



        </div>



        </div>

    </div>    <?php include_once 'modal_add.php'; ?>

    <?php include_once("includes/footer.php"); ?>

</div>

</body>

</html>