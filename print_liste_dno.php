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

if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else {$annee=date("Y");}

//fonction calcul nb jour
function NbJours($debut, $fin) {
  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);
  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
  return(($diff / 86400)+1);
}

if ((isset($_GET["id_sup"]) && !empty($_GET["id_sup"]))) {
  $id = ($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."dno WHERE numero=%s",
                       GetSQLValueString($id, "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok&annee=$annee"; else $insertGoTo .= "?del=no&annee=$annee";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  $annee = (isset($_POST["annee"]))?$_POST["annee"]:date("Y");
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $personnel=$_SESSION['clp_id']; $date=date("Y-m-d");
 $code = explode(":",$_POST['code_activite']);
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."dno (code_activite, numero, destinataire, date_initialisation, objet, observation, projet, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s,%s,'$personnel', '$date')",

		  			   GetSQLValueString($code[0], "text"),
					   GetSQLValueString($_POST['numero'], "text"),
   					   GetSQLValueString($_POST['destinataire'], "text"),
                       GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_initialisation']))), "date"),
					   GetSQLValueString($_POST['objet'], "text"),
   					   GetSQLValueString($_POST['observation'], "text"),
					   GetSQLValueString($_SESSION["clp_projet"], "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

  if($Result1) $insertGoTo = $page."?insert=ok&annee=$annee";
  else $insertGoTo = $page."&insert=no&annee=$annee";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
      $id = ($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from dno WHERE numero=%s",
                           GetSQLValueString($id, "int"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok&annee=$annee"; else $insertGoTo .= "?del=no&annee=$annee";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];  $id = ($_POST["MM_update"]);
  $codea = explode(":",$_POST['code_activite']);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."dno SET code_activite=%s, numero=%s, destinataire=%s, date_initialisation=%s, objet=%s, observation=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE numero='$id'",

		  			   GetSQLValueString($codea[0], "text"),
					   GetSQLValueString($_POST['numero'], "text"),
  					   GetSQLValueString($_POST['destinataire'], "text"),
                       GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_initialisation']))), "date"),
					   GetSQLValueString($_POST['objet'], "text"),
   					   GetSQLValueString($_POST['observation'], "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

  if($Result1) $insertGoTo = $page."?update=ok&annee=$annee";
  else $insertGoTo = $page."&update=no&annee=$annee";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_conv = "SELECT distinct ".$database_connect_prefix."dno.*, ".$database_connect_prefix."ptba.intitule_activite_ptba FROM ".$database_connect_prefix."dno, ".$database_connect_prefix."ptba where ".$database_connect_prefix."dno.code_activite=".$database_connect_prefix."ptba.code_activite_ptba and annee=$annee and ".$database_connect_prefix."dno.projet='".$_SESSION["clp_projet"]."'  ORDER BY numero desc";
$liste_conv = mysql_query($query_liste_conv, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_conv = mysql_fetch_assoc($liste_conv);
$totalRows_liste_conv = mysql_num_rows($liste_conv);


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_bailleur = "SELECT code, sigle, definition FROM ".$database_connect_prefix."partenaire WHERE dno=1 ";
$liste_bailleur = mysql_query($query_liste_bailleur, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
$totalRows_liste_bailleur = mysql_num_rows($liste_bailleur);
$destinateur_array = array();
if($totalRows_liste_bailleur>0){ do{
  $destinateur_array[$row_liste_bailleur["code"]] = $row_liste_bailleur["sigle"];
}while($row_liste_bailleur = mysql_fetch_assoc($liste_bailleur)); }



mysql_select_db($database_pdar_connexion, $pdar_connexion);
//$id=$row_liste_conv['id_dno'];  mysql_error_show_message(mysql_error())
$query_edit_ano = "SELECT dno, phase, date_phase FROM ".$database_connect_prefix."suivi_dno";
$edit_ano = mysql_query($query_edit_ano, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_ano = mysql_fetch_assoc($edit_ano);
$totalRows_edit_ano = mysql_num_rows($edit_ano);
$tableau_date_envoi = array();
$tableau_date_ano = array();
$tableau_date_rejet = array();
if($totalRows_edit_ano>0){  do{
  if($row_edit_ano["phase"]=="ANO") $tableau_date_ano[$row_edit_ano["dno"]]=$row_edit_ano["date_phase"];
  if($row_edit_ano["phase"]=="Envoi au bailleur") $tableau_date_envoi[$row_edit_ano["dno"]]=$row_edit_ano["date_phase"];
    if($row_edit_ano["phase"]=="Objection du bailleur") $tableau_date_rejet[$row_edit_ano["dno"]]=$row_edit_ano["date_phase"];

   }while($row_edit_ano = mysql_fetch_assoc($edit_ano));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rubrique = "select * from ".$database_connect_prefix."suivi_dno order by dno, date_phase desc";
$liste_rubrique = mysql_query($query_liste_rubrique, $pdar_connexion) or die(mysql_error());
$row_liste_rubrique = mysql_fetch_assoc($liste_rubrique);
$totalRows_liste_rubrique = mysql_num_rows($liste_rubrique);
$tableau_stat = $tableau_obs = $tableau_persp = array();
if($totalRows_liste_rubrique>0){  do{ if(!isset($tableau_obs[$row_liste_rubrique["dno"]])) $tableau_obs[$row_liste_rubrique["dno"]]="";
  $tableau_stat[$row_liste_rubrique["dno"]]=$row_liste_rubrique["phase"];
  $tableau_persp[$row_liste_rubrique["dno"]]=$row_liste_rubrique["observation"];
  $tableau_obs[$row_liste_rubrique["dno"]].="<u>".implode('-',array_reverse(explode('-',$row_liste_rubrique["date_phase"])))."</u>: (<b>".$row_liste_rubrique["phase"]."</b>)<i> ".$row_liste_rubrique["observation"]."&nbsp;    </br></i>"; }while($row_liste_rubrique = mysql_fetch_assoc($liste_rubrique));
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
<?php include_once("modal_add.php"); ?>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
.menu_head {
  padding: 5px; cursor: pointer; background-color: #060; color: #FFF;
}

</style>
<h4><i class="icon-reorder"></i> Les Demandes des Avis Non Objections</h4>

<table class="table table-striped table-bordered table-hover table-responsive table-tabletools hide_befor_load" id="mytable" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<th>Num&eacute;ro</th>
<th>Activit&eacute;</th>
<th>Destinataire</th>
<th>Objet</th>
<th>Date de soumission </th>
<th>Date ANO</th>
<th>Dur&eacute;e (J)</th>
<th>Observations</th>
</tr>
</thead>
<tbody class="hide_befor_load">
<?php if($totalRows_liste_conv>0) { $i=0; do { $id = $row_liste_conv['numero'];
 if(isset($tableau_date_envoi[$row_liste_conv['numero']])) $denvoi=$tableau_date_envoi[$row_liste_conv['numero']]; else $denvoi=date("Y-m-d");  if($denvoi>=$row_liste_conv['date_initialisation'])$Nombres_jourse = NbJours($row_liste_conv['date_initialisation'], $denvoi); else $Nombres_jourse="  ???";
?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo $row_liste_conv['numero']; ?></td>
<td class=" "><div  title=" <?php echo $row_liste_conv['intitule_activite_ptba']; ?>"><?php if(isset($row_liste_conv["code_activite"])) echo $row_liste_conv["code_activite"]; ?></div></td>
<td class=" "><?php echo (isset($destinateur_array[$row_liste_conv['destinataire']]))?$destinateur_array[$row_liste_conv['destinataire']]:''; ?></td>
<td class=" "><?php echo $row_liste_conv['objet']; ?></td>
<td class=" "><?php if(isset($tableau_date_envoi[$row_liste_conv['numero']]) ) echo date_reg($tableau_date_envoi[$row_liste_conv['numero']],'/'); else echo "En attente";  ?></td>
<td class=" "><?php if(isset($tableau_date_ano[$row_liste_conv['numero']]) ) echo date_reg($tableau_date_ano[$row_liste_conv['numero']],'/'); elseif(isset($tableau_date_rejet[$row_liste_conv['numero']]) ) { echo "<div style=\"width: 80%; background-color:#FF0000; color:#FFFFFF;\">".date_reg($tableau_date_rejet[$row_liste_conv['numero']],'/')."</div>";}  else echo "<div align=\"center\" style=\"width: 80%; background-color:#FFFF00; \">-</div>"; ?></td>
<td class=" "><div align="center">
  <?php if(isset($tableau_date_ano[$row_liste_conv['numero']])) $dano=$tableau_date_ano[$row_liste_conv['numero']]; else $dano=date("Y-m-d"); if (isset($tableau_date_envoi[$row_liste_conv['numero']])) { $Nombres_jours = NbJours($tableau_date_envoi[$row_liste_conv['numero']], $dano);
// Affiche 2
if($dano>=$tableau_date_envoi[$row_liste_conv['numero']]) {echo number_format($Nombres_jours, 0, ',', ' ');}else echo "<div style=\"width: 40%; background-color:#FF0000; color:#FFFFFF;\">?...?</div>";} ?>
</div></td>
<td class=" "><?php echo (isset($tableau_obs[$row_liste_conv['numero']]))?$tableau_obs[$row_liste_conv['numero']]:"<div align='center'>Aucun suivi</div>"; ?></td>
</tr>
<?php }while($row_liste_conv  = mysql_fetch_assoc($liste_conv)); } else { ?>
<tr>
<td colspan="8"><h2 align="center">Aucune donn&eacute;e !</h2></td>
</tr>
<?php } ?>
</tbody></table>

<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>