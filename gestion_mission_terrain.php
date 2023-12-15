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

header('Content-Type: text/html; charset=UTF-8');



if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y");



$dir = './attachment/mission_atelier/';

//if(!is_dir($dir)) mkdir($dir);



if(isset($_GET["id_sup"]))

{

  $id=intval($_GET["id_sup"]);


  $query_sup_act = "DELETE FROM ateliers WHERE id_atelier='$id'";

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

    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $lieu = array();

//TDR

  if ((isset($_FILES['tdr']['name']))) {

    $ext_autorisees=array('docx','doc','pdf', 'xls', 'xlsx', 'jpeg', 'jpg', 'zip', 'rar'); //Extensions autoris&eacute;es

    $Result1 = false; $link = "";

    $ext = substr(strrchr($_FILES['tdr']['name'], "."), 1);

    if(in_array($ext,$ext_autorisees))

    {

      $Result1 = move_uploaded_file($_FILES['tdr']['tmp_name'],

      $dir.$_FILES['tdr']['name']);

      if($Result1) $link = $_FILES['tdr']['name'];

      if($Result1) mysql_query_ruche("DOC".$dir.$link, $pdar_connexion,1);

    }

  /*$a = explode(',',$_POST['lieu']);  foreach($a as $b){ $c = explode(':',$b); if(isset($c[0]) && !empty($c[0])) $lieu[] = $c[0]; } */

  $a = explode('/',$_POST['debut']); $annee = isset($a[2])?$a[2]:date("Y");

  $insertSQL = sprintf("INSERT INTO ateliers (tdr, code_activite, code_mission, objectif, lieu, moyen_transport, responsable, donneur_ordre, participants, debut, fin, projet, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, '$personnel', '$date')",



                       GetSQLValueString($link, "text"),

                       GetSQLValueString($_POST['code_activite'], "text"),

					   GetSQLValueString($_POST['code_mission'], "text"),

                       GetSQLValueString($_POST['objectif'], "text"),

                       GetSQLValueString(implode(',',$_POST['lieu']), "text"),

                       GetSQLValueString($_POST['moyen_transport'], "text"),

                       GetSQLValueString($_POST['responsable'], "text"),

                       GetSQLValueString($_POST['donneur_ordre'], "text"),

	     			   GetSQLValueString(implode(',',$_POST['participants']), "text"),

					   GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['debut']))), "date"),

                       GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['fin']))), "date"),

					  // GetSQLValueString($_POST['observation'], "text"),

					   GetSQLValueString($_SESSION["clp_projet"], "text"));



  	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

    $insertGoTo = $_SERVER['PHP_SELF'];

    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";

    $insertGoTo .= "&annee=$annee";

    header(sprintf("Location: %s", $insertGoTo));  exit();

  }

  else

  {

    $insertGoTo = $_SERVER['PHP_SELF'];

    $insertGoTo .= "?insert=no&tdr=no&annee=$annee";

    header(sprintf("Location: %s", $insertGoTo));  exit();

  }

 //FIN TDR

  }



    if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {

    $id = $_POST["MM_delete"];

    $insertSQL = sprintf("DELETE from ateliers WHERE id_atelier=%s",

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

    $link = ""; $Result1 = false; $lieu = array();

//TDR

  if ((isset($_FILES['tdr']['name']))) {

    $ext_autorisees=array('docx','doc','pdf', 'xls', 'xlsx', 'jpeg', 'jpg', 'zip', 'rar'); //Extensions autoris&eacute;es

    $ext = substr(strrchr($_FILES['tdr']['name'], "."), 1);

    if(in_array($ext,$ext_autorisees))

    {

      $Result1 = move_uploaded_file($_FILES['tdr']['tmp_name'],

      $dir.$_FILES['tdr']['name']);

      if($Result1) $link[] = $_FILES['tdr']['name'];

      if($Result1) mysql_query_ruche("DOC".$dir.$link, $pdar_connexion,1);      

    }

  }

  

  

  /*$a = explode(',',$_POST['lieu']);  foreach($a as $b){ $c = explode(':',$b); if(isset($c[0]) && !empty($c[0])) $lieu[] = $c[0]; }*/

  $a = explode('/',$_POST['debut']); $annee = isset($a[2])?$a[2]:date("Y"); 

  $insertSQL = sprintf("UPDATE ateliers SET code_activite=%s, donneur_ordre=%s, objectif=%s, lieu=%s, moyen_transport=%s, responsable=%s, participants=%s, debut=%s, fin=%s, ".(($Result1)?" tdr=".GetSQLValueString(implode('|',$link), "text").", ":"")." etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_atelier='$id'",



                       GetSQLValueString($_POST['code_activite'], "text"),

					  GetSQLValueString($_POST['donneur_ordre'], "text"),

                       GetSQLValueString($_POST['objectif'], "text"),

                       GetSQLValueString(implode(',',$_POST['lieu']), "text"),

                       GetSQLValueString($_POST['moyen_transport'], "text"),

                       GetSQLValueString($_POST['responsable'], "text"),

	     			   GetSQLValueString(implode(',',$_POST['participants']), "text"),

					   GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['debut']))), "date"),

                       GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['fin']))), "date"));



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



if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))

{ //Rapport

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {

    $id = $_POST["MM_update"];

    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $Result1 = false;



//TDR

  if ((isset($_FILES['rapport']['name']))) {

    $ext_autorisees=array('docx','doc','pdf', 'xls', 'xlsx', 'jpeg', 'jpg', 'zip', 'rar'); //Extensions autoris&eacute;es

    $ext = substr(strrchr($_FILES['rapport']['name'], "."), 1);

    if(in_array($ext,$ext_autorisees))

    {

      $Result1 = move_uploaded_file($_FILES['rapport']['tmp_name'],

      $dir.$_FILES['rapport']['name']);

      if($Result1) $link = $_FILES['rapport']['name'];

    }

  }

  $insertSQL = sprintf("UPDATE ateliers SET date_aller=%s, date_retour=%s, observation=%s, ".(($Result1)?" rapport=".GetSQLValueString($link, "text").", ":"")." etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_atelier='$id'",



  					   GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_aller']))), "date"),

                       GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_retour']))), "date"),

					   GetSQLValueString($_POST['observation'], "text"));



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

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

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

<script>

$().ready(function() {

    init_tabs();

});



function show_tab(tab) {

    if (!tab.html()) {

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

</script>




<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i><strong>Missions de terrain </strong></h4>



</div>



<div class="widget-content" style="display: block;">



<div class="tabbable tabbable-custom" >

  <ul class="nav nav-tabs" >

  <?php for($j=$_SESSION["annee_debut_projet"];$j<=date("Y");$j++){ ?>

    <li title="" class="<?php echo ($j==$annee)?"active":""; ?>"><a href="#tab_feed_<?php echo $j; ?>" data-toggle="tab"><?php echo $j; ?></a></li>

  <?php }  ?>

  </ul>

  <div class="tab-content">

  <?php for($j=$_SESSION["annee_debut_projet"];$j<=date("Y");$j++){ ?>

  <div class="tab-pane <?php echo ($j==$annee)?"active":""; ?>" id="tab_feed_<?php echo $j; ?>" data-target="./gestion_mission_terrain_content.php?annee=<?php echo $j; ?>"></div>

  <?php }  ?>

  </div>

</div>



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