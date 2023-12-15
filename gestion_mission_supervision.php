<?php

///////////////////////////////////////////////

/*                 SSE                       */

/*	Conception & Développement: SEYA SERVICES */

///////////////////////////////////////////////

session_start();

include_once 'system/configuration.php';

$config = new Config;



if (!isset ($_SESSION["clp_id"])) {

  header(sprintf("Location: %s", "./"));

  exit;

}

include_once $config->sys_folder . "/database/db_connexion.php";

?>



<?php

$plog=$_SESSION["clp_id"];

$date=date("Y-m-d");

if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else $annee=date("Y");
$id_ms = 0; if(isset($_GET['id_ms']) && intval($_GET['id_ms'])>0) $id_ms = intval($_GET['id_ms']);

$dir = './attachment/supervision/';
$plog=$_SESSION["clp_id"];

if(isset($_GET["id_ms"])) { //and projet='".$_SESSION["clp_projet"]."'$c=(isset($_GET["id"]))?$_GET["id"]:$_SESSION["id_ms"];
$_SESSION["id_ms"] = (isset($_GET["id_ms"]))?$_GET["id_ms"]:$_SESSION["id_ms"];
$query_edit_ms = "SELECT * FROM ".$database_connect_prefix."mission_supervision WHERE year(debut)='$annee' and id_mission='".$_GET["id_ms"]."' ";
} else
{  //and projet='".$_SESSION["clp_projet"]."'
$query_edit_ms = "SELECT * FROM ".$database_connect_prefix."mission_supervision WHERE year(debut)='$annee'  order by debut desc limit 1";
}
 try{
    $edit_ms = $pdar_connexion->prepare($query_edit_ms);
    $edit_ms->execute();
    $row_edit_ms = $edit_ms ->fetch();
    $totalRows_edit_ms = $edit_ms->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$nom=(isset($row_edit_ms["objet"]))?$row_edit_ms["objet"]:"";

//if(isset($_GET['id']))$id=$_GET['id']; else $id=0;
if(isset($_GET['id_ms']) && $_GET['id_ms']!="") $id_ms=$_GET['id_ms']; //elseif(isset($row_edit_ms['id_ms'])) $id_ms=$row_edit_ms['id_ms'];
if(isset($id_ms)){       //and projet='".$_SESSION["clp_projet"]."' and structure='".$_SESSION["clp_structure"]."'
$query_liste_rec = "SELECT recommandation_mission.* FROM ".$database_connect_prefix."recommandation_mission,mission_supervision where mission=id_mission and mission='$id_ms' and year(debut)='$annee'  ORDER BY ref_no asc"; 
 try{
    $liste_rec = $pdar_connexion->prepare($query_liste_rec);
    $liste_rec->execute();
    $row_liste_rec = $liste_rec ->fetch();
    $totalRows_liste_rec = $liste_rec->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}


if(isset($_GET["id_sup"]))
{
  $id=$_GET["id_sup"];
  $query_sup_act = "DELETE FROM ".$database_connect_prefix."recommandation_mission WHERE id_recommandation='$id'";
  	    try{
    $Result1 = $pdar_connexion->prepare($query_sup_act);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
     /* $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();*/
	
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  $insertGoTo .= "?id_ms=$id_ms";
  if ($Result1) $insertGoTo .= "&del=ok&annee=$annee";   else $insertGoTo .= "&del=no&annee=$annee";
  //mysql_free_result($Result1);
    header(sprintf("Location: %s", $insertGoTo));  exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form4"))
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
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."recommandation_mission SET rapport=".(($Result1)?GetSQLValueString($link, "text"):"null").", etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_recommandation='$id'");

		    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    $insertGoTo .= "?id_ms=$id_ms";
    if ($Result1) $insertGoTo .= "&update=ok"; else $insertGoTo .= "&update=no";
    $insertGoTo .= "&annee=$annee";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{ //Mission supervision

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
    $id = ($_POST["MM_update"]); //, projet=%s
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."mission_supervision SET code_ms=%s, type=%s, objet=%s, resume=%s, debut=%s, fin=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date'  WHERE code_ms=%s",
                          GetSQLValueString($_POST['code_ms'], "text"),
						 GetSQLValueString($_POST['type'], "text"),
                         GetSQLValueString($_POST['objet'], "text"),
                         GetSQLValueString($_POST['resume'], "text"),
                         GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['debut']))), "date"),
                         GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['fin']))), "date"),
                         //GetSQLValueString($_SESSION["clp_projet"], "text"),
                         GetSQLValueString($id, "text"));

		    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  
  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."mission_supervision WHERE code_ms=%s",
                         GetSQLValueString($id, "text"));

		    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];$code_ms=$_POST['mission'];

  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."recommandation_mission (mission, volet_recommandation, responsable_interne, rubrique, numero, ref_no, type, recommandation, date_buttoir, responsable, observation, projet, structure, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,'$personnel', '$date')",

                       GetSQLValueString($code_ms, "text"),
                       GetSQLValueString((empty($_POST['volet_recommandation'])?"RAS":$_POST['volet_recommandation']), "text"),
					   GetSQLValueString($_POST['responsable_interne'], "text"),
					   GetSQLValueString($_POST['rubrique'], "text"),
                       GetSQLValueString($_POST['numero'], "text"),
                       GetSQLValueString($_POST['ref_no'], "int"),
					   GetSQLValueString($_POST['type'], "text"),
					   GetSQLValueString($_POST['recommandation'], "text"),
					   GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_buttoir']))), "date"),
                       GetSQLValueString($_POST['responsable'], "text"),
                       GetSQLValueString($_POST['observation'], "text"),
                       GetSQLValueString($_SESSION["clp_projet"], "text"),
                       GetSQLValueString($_SESSION["clp_structure"], "text"));

  	    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
    $insertGoTo .= "?id_ms=$id_ms";
    if ($Result1) $insertGoTo .= "&insert=ok"; else $insertGoTo .= "&insert=no";
    $insertGoTo .= "&annee=$annee";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

    if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = $_POST["MM_delete"]; $code_ms=$_POST['mission'];
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."recommandation_mission WHERE id_recommandation=%s",
                         GetSQLValueString($id, "int"));
		    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
    $insertGoTo = $_SERVER['PHP_SELF'];
    $insertGoTo .= "?id_ms=$id_ms";
    if ($Result1){ $insertGoTo .= "&del=ok"; }  else $insertGoTo .= "&del=no";
    $insertGoTo .= "&annee=$annee";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $id = $_POST["MM_update"]; $code_ms=$_POST["mission"];
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."recommandation_mission SET volet_recommandation=%s, responsable_interne=%s, rubrique=%s, numero=%s, ref_no=%s, type=%s, recommandation=%s, date_buttoir=%s, responsable=%s, observation=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_recommandation='$id'",

                      GetSQLValueString((empty($_POST['volet_recommandation'])?"RAS":$_POST['volet_recommandation']), "text"),
					   GetSQLValueString($_POST['responsable_interne'], "text"),
                       GetSQLValueString($_POST['rubrique'], "text"),
					   GetSQLValueString($_POST['numero'], "text"),
                       GetSQLValueString($_POST['ref_no'], "int"),
					   GetSQLValueString($_POST['type'], "text"),
					   GetSQLValueString($_POST['recommandation'], "text"),
					   GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_buttoir']))), "date"),
                       GetSQLValueString($_POST['responsable'], "text"),
                       GetSQLValueString($_POST['observation'], "text"));

	    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    $insertGoTo .= "?id_ms=$id_ms";
    if ($Result1) $insertGoTo .= "&update=ok"; else $insertGoTo .= "&update=no";
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

 <style type="text/css">

<!--

#demo-container{padding:2px 15px 2 15px;/*background:#67A897;*/}

ul#simple-menu{list-style-type:none;width:100%;position:relative;height:20px;font-family:"Trebuchet MS",Arial,sans-serif;font-size:13px;font-weight:bold;margin:0;padding:0;}

ul#simple-menu li{display:block;float:left;margin:0 0 0 4px;height:20px;}

ul#simple-menu li.left{margin:0;}

ul#simple-menu li a{display:block;float:left;color:#fff;background:#4A6867;text-decoration:none;padding:3px 18px;}

ul#simple-menu li a.right{padding-right:19px;}

ul#simple-menu li a:hover{background:#2E4560;}

ul#simple-menu li a.current{color:#FFF;background:#ff0000;}

ul#simple-menu li a.current:hover{color:#FFF;background:#ff0000;}

-->

</style>

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

<div class="widget-header"> <h4><i class="icon-reorder"></i> Mission supervision </h4>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
echo do_link("","","Edition de mission","Mission","","./","pull-right p11","get_content('new_mission.php','','modal-body_add',this.title,'iframe');",1,"",$nfile);
?>
<?php } ?></div>

<div class="widget-content" style="display: block;">

<div class="tabbable tabbable-custom" >

  <ul class="nav nav-tabs" >

  <?php for($j=$_SESSION["annee_debut_projet"];$j<=date("Y");$j++){ if($j<=$_SESSION["annee_fin_projet"]) { ?>

    <li title="" class="<?php echo ($j==$annee)?"active":""; ?>"><a href="#tab_feed_<?php echo $j; ?>" data-toggle="tab"><?php echo $j; ?></a></li>

  <?php } } ?>

  </ul>

  <div class="tab-content">

  <?php for($j=$_SESSION["annee_debut_projet"];$j<=date("Y");$j++){ if($j<=$_SESSION["annee_fin_projet"]) { ?>

  <div class="tab-pane <?php echo ($j==$annee)?"active":""; ?>" id="tab_feed_<?php echo $j; ?>" data-target="./mission_supervision_content.php?annee=<?php echo $j."&id_ms=$id_ms"; ?>"></div>

  <?php }}  ?>

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