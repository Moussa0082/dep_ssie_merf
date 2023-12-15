<?php

///////////////////////////////////////////////

/*                 SSE                       */

/*	Conception & Développement: BAMASOFT */

///////////////////////////////////////////////

session_start();

include_once 'system/configuration.php';

$config = new Config;

if (!isset ($_SESSION["clp_id"])) {

  header(sprintf("Location: %s", "./"));

  exit;

}

include_once $config->sys_folder . "/database/db_connexion.php";

$editFormAction = $_SERVER['PHP_SELF'];

$currentPage = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {

  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);

}

$page = $_SERVER['PHP_SELF'];

if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];

$query_sup_tache = "DELETE FROM ".$database_connect_prefix."projet WHERE id_projet='$id'";

try{

    $Result = $pdar_connexion->prepare($query_sup_tache);

    $Result->execute();

}catch(Exception $e){ die(mysql_error_show_message($e)); }

if($Result) $lien =$page."?del=ok"; else $lien=$page."?del=no";

header(sprintf("Location: %s", $lien));

}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))

{

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {

  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."projet (structure, code_projet, sigle_projet, annee_debut, annee_fin, ugl, adresse, contact, intitule_projet, actif) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",

  			   GetSQLValueString($_POST['acteur'], "int"),

               GetSQLValueString($_POST['code_projet'], "text"),

  			   GetSQLValueString($_POST['sigle_projet'], "text"),

               GetSQLValueString($_POST['annee_debut'], "int"),

               GetSQLValueString($_POST['annee_fin'], "int"),
			   GetSQLValueString(implode(',',$_POST['ugl']), "text"),

  			   //GetSQLValueString(implode('|',$_POST['ugl'])."|", "text"),

  			   GetSQLValueString($_POST['adresse'], "text"),

               GetSQLValueString($_POST['contact'], "text"),

               GetSQLValueString($_POST['intitule_projet'], "text"),

               GetSQLValueString($_POST['actif'], "int"));

  try{

    $Result1 = $pdar_connexion->prepare($insertSQL);

    $Result1->execute();

  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  if($Result1){  $id = 0;//mysql_insert_id();

    include "includes/class.upload.php";

    $handle = new upload($_FILES['photo']);

    if ($handle->uploaded)

    {

      //resize to 250 px

      $handle->file_new_name_body = 'img_'.$_POST['code_projet'];

      $handle->image_resize = true;

      $handle->image_x = 250;

      $handle->image_y = 250;

      $handle->file_auto_rename = true;

      $handle->image_ratio = true;

      $handle->image_convert = 'jpg';

      $handle->file_overwrite = true;

      $handle->process('./images/projet/');   /*

      if ($handle->processed)

      {

        $img_full_name = $handle->file_dst_name_body.".".$handle->file_dst_name_ext;

      }  */

      //terminé

      $handle->clean();

    }

  }

  $insertGoTo = $page;

  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";

  header(sprintf("Location: %s", $insertGoTo));   exit(0);

  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {

      $id = intval($_POST["MM_delete"]);

      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."projet WHERE id_projet=%s",

                           GetSQLValueString($id, "int"));

      try{

        $Result1 = $pdar_connexion->prepare($insertSQL);

        $Result1->execute();

      }catch(Exception $e){ die(mysql_error_show_message($e)); }

      $insertGoTo = $_SERVER['PHP_SELF'];

      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";

      header(sprintf("Location: %s", $insertGoTo)); exit();

    }  

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {

  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id'];

  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."projet SET structure=%s, code_projet=%s, sigle_projet=%s, annee_debut=%s, annee_fin=%s, ugl=%s, adresse=%s, contact=%s, intitule_projet=%s, actif=%s WHERE id_projet='$c'",

  			   GetSQLValueString($_POST['acteur'], "text"),

               GetSQLValueString($_POST['code_projet'], "text"),

  			   GetSQLValueString($_POST['sigle_projet'], "text"),

               GetSQLValueString($_POST['annee_debut'], "int"),

               GetSQLValueString($_POST['annee_fin'], "int"),

  			   GetSQLValueString(implode(',',$_POST['ugl']), "text"),

  			   GetSQLValueString($_POST['adresse'], "text"),

               GetSQLValueString($_POST['contact'], "text"),

               GetSQLValueString($_POST['intitule_projet'], "text"),

               GetSQLValueString($_POST['actif'], "int"));

  try{

        $Result1 = $pdar_connexion->prepare($insertSQL);

        $Result1->execute();

  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  if($Result1){

    include "includes/class.upload.php";

    $handle = new upload($_FILES['photo']);

    if ($handle->uploaded)

    {

      //resize to 250 px

      $handle->file_new_name_body = 'img_'.$_POST['code_projet'];

      $handle->image_resize = true;

      $handle->image_x = 250;

      $handle->image_y = 250;

      $handle->file_auto_rename = true;

      $handle->image_ratio = true;

      $handle->image_convert = 'jpg';

      $handle->file_overwrite = true;

      $handle->process('./images/projet/');   /*

      if ($handle->processed)

      {

        $img_full_name = $handle->file_dst_name_body.".".$handle->file_dst_name_ext;

      }  */

      //terminé

      $handle->clean();

    }

  }

  $insertGoTo = $page;

  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";

  header(sprintf("Location: %s", $insertGoTo)); exit(0);

  }

}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))

{

  if ((isset($_POST["MM_update"]))) {

    $id=$_POST['id'];

    include "includes/class.upload.php";

    $handle = new upload($_FILES['photo']);

    if ($handle->uploaded && !empty($id))

    {

      //resize to 250 px

      $handle->file_new_name_body = 'img_'.$id;

      $handle->image_resize = true;

      $handle->image_x = 250;

      $handle->image_y = 250;

      $handle->file_auto_rename = true;

      $handle->image_ratio = true;

      $handle->image_convert = 'jpg';

      $handle->file_overwrite = true;

      $handle->process('./images/projet/');   /*

      if ($handle->processed)

      {

        $img_full_name = $handle->file_dst_name_body.".".$handle->file_dst_name_ext;

      }  */

      //terminé

      $handle->clean();

    }

    if($handle->processed) $insertGoTo = $page."?insert=ok";

    else $insertGoTo = $page."&insert=no";

    header(sprintf("Location: %s", $insertGoTo));  exit();

  }

}

/*

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))

{

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {

    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."projet_region (structure, code_projet, region, id_personnel, date_enregistrement) VALUES (%s, %s, %s, '$personnel', '$date')",

      			   GetSQLValueString($_POST['structure'], "text"),

                   GetSQLValueString($_POST['projet'], "text"),

      			   GetSQLValueString(implode('|',$_POST['region'.$st])."|", "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);

    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    $insertGoTo = $page;

    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";

    header(sprintf("Location: %s", $insertGoTo));   exit(0);

  }

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {

      $id = ($_POST["projet"]);

      $structure = ($_POST["structure"]);

      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."projet_region WHERE code_projet=%s and structure=%s",

                           GetSQLValueString($id, "text"),

                           GetSQLValueString($structure, "text"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);

      $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

      $insertGoTo = $_SERVER['PHP_SELF'];

      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";

      header(sprintf("Location: %s", $insertGoTo)); exit();

  }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {

    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['MM_update'];

    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."projet_region SET region=%s, modifier_le='$date', modifier_par='$personnel' WHERE structure=%s and code_projet=%s",

                      GetSQLValueString(implode('|',$_POST['region'.$_POST['structure']])."|", "text"),

                      GetSQLValueString($c, "text"),

                      GetSQLValueString($_POST['projet'], "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);

    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    $insertGoTo = $page;

    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";

    header(sprintf("Location: %s", $insertGoTo)); exit(0);

  }

} */

$mySqlQuery = "SELECT * FROM ".$database_connect_prefix."projet  ORDER BY code_projet";

try{

    $qh = $pdar_connexion->prepare($mySqlQuery);

    $qh->execute();

    $data = $qh ->fetchAll();

    $num = $qh->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }

$retData["nbr"] = $num;

    $i = 0;

    if($num>0){

      foreach($data as $data){

        $retData[$i]["id_projet"] = $data["id_projet"];

        $retData[$i]["structure"] = $data["structure"];

        $retData[$i]["intitule_projet"] = $data["intitule_projet"];

        $retData[$i]["sigle_projet"] = $data["sigle_projet"];

		$retData[$i]["code_projet"] = $data["code_projet"];

        //$retData[$i]["slogan_projet"] = $data["slogan_projet"];

        $retData[$i]["annee_debut"] = $data["annee_debut"];

        $retData[$i]["annee_fin"] = $data["annee_fin"];

        $retData[$i]["ugl"] = $data["ugl"];

        //$retData[$i]["adresse"] = $data["adresse"];

        //$retData[$i]["icone"] = $data["icone"];

        $retData[$i]["actif"] = $data["actif"];

        $i++;

      }

    }

//Region

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_region = "SELECT id as id_region,intitule as nom_region,code,abrege,structure,projet FROM ".$database_connect_prefix."localite_projet where niveau=1 ";

$region = mysql_query($query_region, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_region = mysql_fetch_assoc($region);

$totalRows_region = mysql_num_rows($region);

$liste_region_array = array();

if($totalRows_region>0){  do{

$liste_region_arrayV[$row_region["id_region"]]=$row_region["nom_region"];

$liste_region_array[$row_region["id_region"]]=$row_region["abrege"];

}while($row_region  = mysql_fetch_assoc($region));  }*/

//Structure

$query_structure = "SELECT * FROM ".$database_connect_prefix."acteur ";

try{

    $structure = $pdar_connexion->prepare($query_structure);

    $structure->execute();

    $row_structure = $structure ->fetchAll();

    $totalRows_structure = $structure->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }

$liste_structure_array = array();  $liste_structure_arrayV = array();

if($totalRows_structure>0){ foreach($row_structure as $row_structure){

$liste_structure_arrayV[$row_structure["code_acteur"]]=$row_structure["nom_acteur"];

$liste_structure_array[$row_structure["code_acteur"]]=$row_structure["nom_acteur"];

} }

//Projet/Structure

$query_structure = "SELECT distinct structure, code_projet FROM ".$database_connect_prefix."projet ";

try{

    $structure = $pdar_connexion->prepare($query_structure);

    $structure->execute();

    $row_structure = $structure ->fetchAll();

    $totalRows_structure = $structure->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }

$liste_projet_array = array();

if($totalRows_structure>0){ foreach($row_structure as $row_structure){

  if(!isset($liste_projet_array[$row_structure["code_projet"]])) $liste_projet_array[$row_structure["code_projet"]] = array();

  array_push($liste_projet_array[$row_structure["code_projet"]],$row_structure["structure"]);

} }

$query_liste_ugl= "SELECT * FROM ".$database_connect_prefix."ugl order by nom_ugl";

try{

    $liste_ugl = $pdar_connexion->prepare($query_liste_ugl);

    $liste_ugl->execute();

    $ligne = $liste_ugl ->fetchAll();

    $totalRows_ligne = $liste_ugl->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }

$tableauUgl=$tableauUglV=array();

foreach($ligne as $ligne){$tableauUgl[$ligne['code_ugl']]=$ligne['abrege_ugl']; $tableauUglV[$ligne['code_ugl']]=$ligne['nom_ugl'];}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

  <title><?php print $config->sitename;?></title>

  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone;?>" />

  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

  <meta name="keywords" content="<?php print $config->MetaKeys;?>" />

  <meta name="description" content="<?php print $config->MetaDesc;?>" />

  <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->

  <meta name="author" content="<?php print $config->MetaAuthor;?>" />

  <!--<meta charset="utf-8">-->

  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>



  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->

  <link href="<?php print $config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/plugins.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/responsive.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/icons.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/login.css" rel="stylesheet" type="text/css"/>

  <link rel="stylesheet" href="<?php print $config->theme_folder;?>/fontawesome/font-awesome.min.css">

  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->theme_folder;?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->

  <!--[if IE 8]><link href="<?php print $config->theme_folder;?>/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->

  <link href='<?php print $config->theme_folder;?>/css.css' rel='stylesheet' type='text/css'>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/jquery-1.10.2.min.js"></script>

  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>

  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/lodash.compat.min.js"></script>

  <!--[if lt IE 9]><script src="<?php print $config->script_folder;?>/libs/html5shiv.js"></script><![endif]-->

  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>

  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>

  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>

  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/breakpoints.js"></script>

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

  <script type="text/javascript" src="<?php print $config->script_folder;?>/app.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/plugins.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/plugins.form-components.js"></script>

<!--

  <script type="text/javascript" src="<?php print $config->script_folder;?>/custom.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/pages_calendar.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/charts/chart_filled_blue.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/charts/chart_simple.js"></script>-->

 <script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>

 <script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/login.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/ui_general.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/form_validation.js"></script>

 <script>$(document).ready(function(){Login.init()});</script>

 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>

</head>

<body>

 <header class="header navbar navbar-fixed-top" role="banner">

    <?php include_once ("includes/header.php");?>

 </header>

<div id="container">

    <div id="sidebar" class="sidebar-fixed">

        <div id="sidebar-content">

            <?php include_once ("includes/menu_top.php");?>

        </div>

        <div id="divider" class="resizeable"></div>

    </div>



    <div id="content">

        <div class="container">

            <div class="crumbs">

                <?php include_once ("includes/sous_menu.php");?>

            </div>

        <div class="page-header">

            <div class="p_top_5">

<!-- Site contenu ici -->

<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i>Liste des Projets </h4>

  <?php if (isset ($_SESSION["clp_id"]) && ($_SESSION["clp_id"] == "admin")) {?>

<?php

echo do_link("","","Ajout de projet","<i class=\"icon-plus\"> Nouveau projet </i>","","./","pull-right p11","get_content('new_projet.php','','modal-body_add',this.title);",1,"",$nfile);

?>

<?php } ?>

</div>

<div class="widget-content" style="display: block;">

<?php for ($i = 0; $i < $retData["nbr"]; $i++)

  {  $id = $retData[$i]["id_projet"]; $code = $retData[$i]["code_projet"];

?>

<div class="col-md-6">

<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo $retData[$i]['sigle_projet']." (".$retData[$i]['code_projet'].")"; ?></h4>

<?php if(isset($_SESSION["clp_id"]) && ($_SESSION["clp_id"] == "admin")){  ?>

<?php

//echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id,"Supprimer","","del","./","pull-right p11","return confirm('Voulez-vous vraiment supprimer ce projet ?');",0,"",$nfile);

echo do_link("","","Modifier Projet ".$retData[$i]['sigle_projet'],"","edit","./","pull-right p11","get_content('new_projet.php','id=$id','modal-body_add',this.title);",1,"",$nfile);

?>

<?php }  ?>

</div>

<div class="widget-content" style="display: block; <?php echo ($retData[$i]['actif']==0)?"":"background-color: #DCDCDC;"; ?>">

  <table border="0" cellpadding="5" cellspacing="5" width="100%" align="CENTER">

  <tr>

  <td valign="middle" align="center" width="100"><img src="<?php  echo (is_file("./images/projet/img_".$code.".jpg"))?'./images/projet/img_'.$code.".jpg":'./images/projet/none.png'; ?>" width="130" height="130" alt="<?php echo $retData[$i]['intitule_projet']; ?>"><br /><?php if(isset($_SESSION["clp_niveau"]) && $_SESSION["clp_niveau"]==1 && $retData[$i]['actif']==0){ ?>

<?php

echo do_link("","","Actualiser l'image du projet ".$retData[$i]['sigle_projet'],"","reload","./","","get_content('edit_projet_photo.php','id=$id&code=$code','modal-body_add',this.title);",1,"",$nfile);

?>

<?php } ?></td>

<td valign="top"><div class="scontent"><b><?php echo $retData[$i]['intitule_projet']; ?></b><br>

<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-striped  table-responsive dataTable">

<!----><tr>
  <td width="30%">Acteurs :</td>
  <td><b><?php if(isset($retData[$i]['structure'])) echo "<span title=\"".$liste_structure_arrayV[$retData[$i]['structure']]."\">".$liste_structure_array[$retData[$i]['structure']]."</span>";

 else echo "Aucune"; ?></b></td></tr>

<tr><td width="30%">Entit&eacute;s de gestion :</td><td><b><?php $a = explode(",",$retData[$i]['ugl']); if(count($a)>0 && !empty($retData[$i]['ugl'])){ $c=array(); foreach($a as $b) if(isset($tableauUgl[$b])) $c[]="<span title=\"".$tableauUglV[$b]."\">".$tableauUgl[$b]."</span>";//do_link("","","Entit&eacute; de gestion de : ".$tableauUgl[$b],"<span title=\"".$tableauUglV[$b]."\">".$tableauUgl[$b]."</span>","","./","","get_content('new_projet_zone.php','id=$b&projet=$code','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

if(count($c)>0) echo implode('; &nbsp;',$c); else echo "Aucune"; } else echo "Aucune"; ?></b></td></tr>

<!--<tr><td>Adresse :</td><td><b><?php //echo $retData[$i]['adresse'].' - '.$retData[$i]['contact']; ?></b></td></tr>-->

<tr><td>P&eacute;riode :</td><td><b>

<?php echo $retData[$i]['annee_debut'].' - '.$retData[$i]['annee_fin']; ?>

</b></td></tr>

</table>

</div>

  </td>

  </tr>

</table>

<!--<div class="clear h0">&nbsp;</div>-->

 </div>

</div> </div>

<?php if($i%2!=0) { ?>

<div class="clear h0">&nbsp;</div>

<?php } ?>

<?php } ?>

<div class="clear h0">&nbsp;</div>

</div>

<!-- Fin Site contenu ici -->

            </div>

        </div>

        </div>

        </div>

    </div> <?php include_once 'modal_add.php'; ?>

    <?php include_once ("includes/footer.php");?>

</div>

</body>

</html>