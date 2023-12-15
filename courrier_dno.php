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

$dir = './attachment/dano/';

if(isset($_GET["dno"])){ $dno=$_GET['dno'];}
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

if ((isset($_GET["id_archive"]) && intval($_GET["id_archive"])>0)) {
  $id = intval($_GET["id_archive"]);
  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."mail_dno SET traitement=1 where id_mail=%s",
                       GetSQLValueString($id, "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $insertGoTo = $_SERVER['PHP_SELF'];
  //if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  $insertGoTo .= "?pane=".$_GET["pane"];
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_GET["id_sup"]) && intval($_GET["id_sup"])>0)) {
  $id = intval($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."suivi_dno WHERE id_suivi=%s",
                       GetSQLValueString($id, "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  //$insertGoTo .= "&dno=$dno&annee=$annee";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{ //Suivi DANO
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $id_mail = intval($_POST["id_mail"]); //$numero = $_POST["numero"];
      $numero = explode(":",$_POST['numero']); $dno=$numero[0];
      $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."suivi_dno (dno, date_phase, phase, observation, documents, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, '$personnel', '$date')",
                           GetSQLValueString($dno, "text"),
                           GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_phase']))), "date"),
                           GetSQLValueString($_POST['phase'], "text"),
                           GetSQLValueString($_POST['observation'], "text"),
                           GetSQLValueString($_POST['documents'], "text"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

      //Traitement de document
      $doc = explode('|',$_POST['documents']); $doc2 = array();
      foreach($doc as $doc1){ if(!empty($doc1)) $doc2[] = $dir.$doc1; }
      $link = implode("|",$doc2);
      //if($Result1) mysql_query("DOC".$link, $pdar_connexion,1);
      //Fin Traitement de document

      if($Result1)
      {
        $query_liste_sdno = "UPDATE ".$database_connect_prefix."mail_dno SET traitement=1 where id_mail=$id_mail";
        $liste_sdno  = mysql_query($query_liste_sdno , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
        if($_POST['phase']!="ANO" && $_POST['phase']!="Objection du bailleur")
        {
          $query_liste_sdno = "UPDATE ".$database_connect_prefix."dno SET traitement=1 where numero='$dno'";
          $liste_sdno  = mysql_query($query_liste_sdno , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
        }
      }

      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
      $insertGoTo .= "&dno=$dno&annee=$annee&pane=3";
      header(sprintf("Location: %s", $insertGoTo));  exit();
 
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form4"))
{
  $annee = (isset($_POST["annee"]))?$_POST["annee"]:date("Y"); $id_mail = intval($_POST["id_mail"]);
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $personnel=$_SESSION['clp_id']; $date=date("Y-m-d");
  $code = explode(":",$_POST['code_activite']);
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."dno (code_activite, numero, type_requete, observation_ptba, observation_ppm, destinataire, date_initialisation, objet, message, documents, traitement, expediteur, observation, projet, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,%s,'$personnel', '$date')",

		  			   GetSQLValueString($code[0], "text"),
					   GetSQLValueString($_POST['numero'], "text"),
					    GetSQLValueString($_POST['type_requete'], "text"),
   					   GetSQLValueString($_POST['observation_ptba'], "text"),
					    GetSQLValueString($_POST['observation_ppm'], "text"),
   					   GetSQLValueString($_POST['destinataire'], "text"),
                       GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_initialisation']))), "date"),
					   GetSQLValueString($_POST['objet'], "text"),
                       GetSQLValueString($_POST['message'], "text"),
                       GetSQLValueString($_POST['documents'], "text"),
                       GetSQLValueString(1, "int"),
                       GetSQLValueString($_POST['expediteur'], "text"),
   					   GetSQLValueString($_POST['observation'], "text"),
					   GetSQLValueString($_SESSION["clp_projet"], "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    //Traitement de document
    $doc = explode('|',$_POST['documents']); $doc2 = array();
    foreach($doc as $doc1){ if(!empty($doc1)) $doc2[] = $dir.$doc1; }
    $link = implode("|",$doc2);
    //if($Result1) mysql_query("DOC".$link, $pdar_connexion,1);
    //Fin Traitement de document

    $query_liste_sdno = "UPDATE ".$database_connect_prefix."mail_dno SET traitement=1 where id_mail=$id_mail";
    $liste_sdno  = mysql_query($query_liste_sdno , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    /*mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $query_liste_bailleur = "SELECT adresse_mail FROM ".$database_connect_prefix."partenaire WHERE dno=1 and code=".GetSQLValueString($_POST['destinataire'], "text");
    $liste_bailleur = mysql_query($query_liste_bailleur, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
    $mail = $row_liste_bailleur["adresse_mail"];
    $mail .= "&replay=dano@psac-ci.org";
    $mail .= "&titre=".$_POST['objet'];
    $mail .= "&attachment=".implode('|',$_POST['documents']);
    $template = "./phpmailer/template.html";
    $handle = fopen($template, 'w');
    fwrite($handle, trim($_POST['message']));    */

    if($Result1)
    {
      $query_liste_sdno = "UPDATE ".$database_connect_prefix."mail_dno SET dno='".$code[0]."', traitement=1 where id_mail=$id_mail";
      $liste_sdno  = mysql_query($query_liste_sdno , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    }

    $insertGoTo = $_SERVER['PHP_SELF'];
    if($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    //$insertGoTo .= "&dno=$dno&annee=$annee&send=$mail";
    $insertGoTo .= "&dno=$dno&annee=$annee&pane=2";
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
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
</style>
<script>
$().ready(function() {
  <?php if(isset($_GET["show"]) && intval($_GET["show"])>0) echo '$("#msg_00").click();'; ?>
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
<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Courrier DANO </h4>
<?php //if (isset ($_SESSION["clp_id"]) && ($_SESSION["clp_id"] == "admin")) {?>
<?php if(isset($_GET["show"]) && intval($_GET["show"])>0)
echo do_link("msg_00","","Contenu du message","Aper&ccedil;u","","./","hidden","get_content('body_mail_dno.php','id=".intval($_GET["show"])."','modal-body_add',this.title,'iframe');",1,"",$nfile)
//echo do_link("","./courrier_dno_archive.php","Archives","Archives","","./","pull-right p11","",0,"margin:0px 5px;",$nfile);
?>
<?php //} ?>
</div>

<div class="widget-content" style="display: block;">

<div class="tabbable tabbable-custom" >
  <ul class="nav nav-tabs" >
    <li title="" class="<?php echo ((!isset($_GET["pane"])) || (isset($_GET["pane"]) && intval($_GET["pane"])==1))?"active":""; ?>"><a href="#tab_feed_1" data-toggle="tab">R&eacute;ponse bailleurs &agrave; DANO</a></li>
    <li title="" class="<?php echo (isset($_GET["pane"]) && intval($_GET["pane"])==2)?"active":""; ?>"><a href="#tab_feed_2" data-toggle="tab">Courriers pour DANO</a></li>
    <li title="" class="<?php echo (isset($_GET["pane"]) && intval($_GET["pane"])==3)?"active":""; ?>"><a href="#tab_feed_3" data-toggle="tab">DANO en instance</a></li>
    <li title="" class="<?php echo (isset($_GET["pane"]) && intval($_GET["pane"])==4)?"active":""; ?>"><a href="#tab_feed_4" data-toggle="tab">Archives</a></li>
  </ul>
  <div class="tab-content">
  <?php $j=1; for($j=1;$j<=4;$j++){ ?>
  <div class="tab-pane <?php echo ((!isset($_GET["pane"]) && $j==1) || (isset($_GET["pane"]) && intval($_GET["pane"])==$j))?"active":""; ?>" id="tab_feed_<?php echo $j; ?>" data-target="./<?php echo ($j==3)?"liste_dno_content_courrier.php?dano_":"courrier_dno_content.php?"; ?>id=<?php echo $j; ?>"></div>
  <?php }  ?>
  </div>
</div>

</div>
<!-- Fin Site contenu ici -->
 </div>
            </div>
        </div>

        </div>
    </div>    <?php include 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>