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
//header('Content-Type: text/html; charset=UTF-8');
?>

<?php
$dir = './attachment/supervision/';
$plog=$_SESSION["clp_id"];
$date=date("Y-m-d");
if(isset($_SESSION['annee']) && !isset($_GET['annee'])) {$annee=$_SESSION['annee'];}
elseif(isset($_GET['annee'])) {$annee=$_GET['annee']; $_SESSION['annee']=$annee;}
elseif(!isset($_GET['annee']) && isset($_SESSION['annee'])) $annee=$_SESSION['annee'];
else $annee=date("Y");

mysql_select_db($database_pdar_connexion, $pdar_connexion);
if(isset($_GET["code_ms"])) { //and projet='".$_SESSION["clp_projet"]."'$c=(isset($_GET["id"]))?$_GET["id"]:$_SESSION["code_ms"];
$_SESSION["code_ms"] = (isset($_GET["code_ms"]))?$_GET["code_ms"]:$_SESSION["code_ms"];
$query_edit_ms = "SELECT * FROM ".$database_connect_prefix."mission_supervision WHERE year(debut)='$annee' and code_ms='".$_GET["code_ms"]."' ";
} else
{  //and projet='".$_SESSION["clp_projet"]."'
$query_edit_ms = "SELECT * FROM ".$database_connect_prefix."mission_supervision WHERE year(debut)='$annee'  order by debut desc limit 1";
}
$edit_ms = mysql_query_ruche($query_edit_ms, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_ms = mysql_fetch_assoc($edit_ms);
$totalRows_edit_ms = mysql_num_rows($edit_ms);

$nom=(isset($row_edit_ms["objet"]))?$row_edit_ms["objet"]:"";

mysql_select_db($database_pdar_connexion, $pdar_connexion);
//if(isset($_GET['id']))$id=$_GET['id']; else $id=0;
if(isset($_GET['code_ms']) && $_GET['code_ms']!="") $code_ms=$_GET['code_ms']; //elseif(isset($row_edit_ms['code_ms'])) $code_ms=$row_edit_ms['code_ms'];
if(isset($code_ms)){       //and projet='".$_SESSION["clp_projet"]."' and structure='".$_SESSION["clp_structure"]."'
$query_liste_rec = "SELECT recommandation_mission.* FROM ".$database_connect_prefix."recommandation_mission,mission_supervision where mission=code_ms and mission='$code_ms' and year(debut)='$annee'  ORDER BY ref_no asc";
$liste_rec = mysql_query_ruche($query_liste_rec, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_rec = mysql_fetch_assoc($liste_rec);
$totalRows_liste_rec = mysql_num_rows($liste_rec);  }

if(isset($_GET["id_sup"]))
{
  $id=$_GET["id_sup"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_sup_act = "DELETE FROM ".$database_connect_prefix."recommandation_mission WHERE id_recommandation='$id'";
  $Result1 = mysql_query_ruche($query_sup_act, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  $insertGoTo .= "?code_ms=$code_ms";
  if ($Result1) $insertGoTo .= "&del=ok&annee=$annee";
  else $insertGoTo .= "&del=no&annee=$annee";
  mysql_free_result($Result1);
  header(sprintf("Location: %s", $insertGoTo));
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

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    $insertGoTo .= "?code_ms=$code_ms";
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

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  
  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."mission_supervision WHERE code_ms=%s",
                         GetSQLValueString($id, "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
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

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = $_SERVER['PHP_SELF'];
    $insertGoTo .= "?code_ms=$code_ms";
    if ($Result1) $insertGoTo .= "&insert=ok"; else $insertGoTo .= "&insert=no";
    $insertGoTo .= "&annee=$annee";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

    if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = $_POST["MM_delete"]; $code_ms=$_POST['mission'];
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."recommandation_mission WHERE id_recommandation=%s",
                         GetSQLValueString($id, "int"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = $_SERVER['PHP_SELF'];
    $insertGoTo .= "?code_ms=$code_ms";
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

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    $insertGoTo .= "?code_ms=$code_ms";
    if ($Result1) $insertGoTo .= "&update=ok"; else $insertGoTo .= "&update=no";
    $insertGoTo .= "&annee=$annee";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

}
      //and projet='".$_SESSION["clp_projet"]."'
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ms = "SELECT * FROM ".$database_connect_prefix."mission_supervision WHERE year(debut)='$annee'  order by code_ms desc";
$liste_ms = mysql_query_ruche($query_liste_ms, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_ms = mysql_fetch_assoc($liste_ms);
$totalRows_liste_ms = mysql_num_rows($liste_ms);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_respo_ugl = "SELECT id_personnel, fonction FROM ".$database_connect_prefix."personnel where structure='".$_SESSION["clp_structure"]."' and projet like '%".$_SESSION["clp_structure"]."|%' ";
$liste_respo_ugl  = mysql_query_ruche($query_liste_respo_ugl , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_respo_ugl  = mysql_fetch_assoc($liste_respo_ugl );
$totalRows_liste_respo_ugl  = mysql_num_rows($liste_respo_ugl );
$respo_ugl=array();
if($totalRows_liste_respo_ugl>0){ do{ $respo_ugl[$row_liste_respo_ugl["id_personnel"]]=$row_liste_respo_ugl["fonction"];  }while($row_liste_respo_ugl  = mysql_fetch_assoc($liste_respo_ugl ));  }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_code_ref = "SELECT * FROM ".$database_connect_prefix."rubrique_projet order by code_rub";
$liste_code_ref  = mysql_query_ruche($query_liste_code_ref , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_code_ref = mysql_fetch_assoc($liste_code_ref);
$totalRows_liste_code_ref  = mysql_num_rows($liste_code_ref);
$liste_rub_array = array();
$code_rub_array = array();
if($totalRows_liste_code_ref>0){ do{
  $liste_rub_array[$row_liste_code_ref["code_rub"]] = /*$row_liste_code_ref["code_rub"].": ".*/$row_liste_code_ref["nom_rubrique"];
  $code_rub_array[$row_liste_code_ref["code_rub"]] = $row_liste_code_ref["code_rub"];
}while($row_liste_code_ref = mysql_fetch_assoc($liste_code_ref)); }

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rubrique = "select recommandation, statut, date_execution  from ".$database_connect_prefix."suivi_recommandation_mission where date_execution in ( select max(date_execution) from ".$database_connect_prefix."recommandation_mission, ".$database_connect_prefix."suivi_recommandation_mission where numero=".$database_connect_prefix."suivi_recommandation_mission.recommandation and mission='$id' group by numero) order by recommandation desc";
$liste_rubrique = mysql_query_ruche($query_liste_rubrique, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_rubrique = mysql_fetch_assoc($liste_rubrique);
$totalRows_liste_rubrique = mysql_num_rows($liste_rubrique);
$tableau_stat = array();
$date_stat = array();
if($totalRows_liste_rubrique>0){  do{
  $tableau_stat[$row_liste_rubrique["recommandation"]]=$row_liste_rubrique["statut"];
  $date_stat[$row_liste_rubrique["recommandation"]]=$row_liste_rubrique["date_execution"];
   }while($row_liste_rubrique = mysql_fetch_assoc($liste_rubrique));
}*/
if(!empty($code_ms) && $totalRows_liste_ms>0 && $totalRows_liste_rec>0)
{
  mysql_select_db($database_pdar_connexion, $pdar_connexion);  //and projet='".$_SESSION["clp_projet"]."' and structure='".$_SESSION["clp_structure"]."'
  $query_act = "SELECT * FROM ".$database_connect_prefix."recommandation_mission where mission='$code_ms'  order by ref_no";
  $act  = mysql_query_ruche($query_act , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_act  = mysql_fetch_assoc($act);
  $totalRows_act  = mysql_num_rows($act);

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_suivi_plan_ms = "SELECT sum(proportion) as texrecms, code_rec  FROM ".$database_connect_prefix."mission_plan where code_ms='$code_ms' and valider=1 group by code_rec order by code_rec";
  $suivi_plan_ms  = mysql_query_ruche($query_suivi_plan_ms , $pdar_connexion) or die(mysql_error());
  $row_suivi_plan_ms = mysql_fetch_assoc($suivi_plan_ms);
  $totalRows_suivi_plan_ms  = mysql_num_rows($suivi_plan_ms);
  $prop_tab = array();
  if($totalRows_suivi_plan_ms>0){  do{
    $prop_tab[$row_suivi_plan_ms["code_rec"]]=$row_suivi_plan_ms["texrecms"];
     }while($row_suivi_plan_ms = mysql_fetch_assoc($suivi_plan_ms));
  }
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_progess = "SELECT code_rec FROM ".$database_connect_prefix."mission_plan where date_reelle is not null or phase_realiser is not null";
if(!empty($code_ms)) $query_progess .= " and code_ms='$code_ms' ";
$query_progess .= " group by code_rec";
$progess  = mysql_query_ruche($query_progess , $pdar_connexion) or die(mysql_error());
$row_progess = mysql_fetch_assoc($progess);
$totalRows_progess  = mysql_num_rows($progess);
$prop_stat = array();
if($totalRows_progess>0){  do{ $prop_stat[] = $row_progess["code_rec"]; }while($row_progess = mysql_fetch_assoc($progess));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ugl = "SELECT * FROM ".$database_connect_prefix."ugl";
$liste_ugl  = mysql_query_ruche($query_liste_ugl , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_ugl  = mysql_fetch_assoc($liste_ugl );
$totalRows_liste_ugl  = mysql_num_rows($liste_ugl );
$ugl_respo=array();
if($totalRows_liste_ugl>0){ do{ $ugl_respo[$row_liste_ugl["code_ugl"]]=$row_liste_ugl["abrege_ugl"];  }while($row_liste_ugl  = mysql_fetch_assoc($liste_ugl ));  }

 unset($path,$_GET['path']);

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
 <style>
.firstcapitalize:first-letter{
  text-transform: capitalize;
}
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
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
</style>
<script type="text/javascript">
$(document).ready(function() {
var oTable = $('#mytable').dataTable();
//Delete the datable object first
if(oTable != null)oTable.fnDestroy();
//Remove all the DOM elements
//$('#mytable').empty();

var oTable = $('#mytable').dataTable( {
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ -1 ] }
        ],
        sDom:"<'row'<'dataTables_header clearfix'<'col-md-7'lT><'col-md-5'Cf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
        oTableTools:{aButtons:["copy","print","csv","xls",{"sExtends": "pdf","sPdfOrientation": "landscape"}],sSwfPath:"./swf/copy_csv_xls_pdf.swf"},
        "aaSorting": [], 
        //"aLengthMenu":[[25, 50, 100, 200, -1],[25, 50, 100, 200, "TOUS1"]],
        "iDisplayLength": -1,
        paging: false
    });

<?php if(isset($_GET["click"])){ ?>
    $('#recommandation_<?php echo $_GET["click"]; ?>').click();
<?php } ?>
});
</script>
<div class="widget box ">
<!--<div class="widget-header1"> <center><h4><?php
mysql_select_db($database_connect_transfert, $connect_transfert);
$mySqlQuery = "SELECT * FROM ".$database_connect_prefix."ugl where code_ugl='".$_SESSION['clp_structure']."'";
$qh = mysql_query_ruche($mySqlQuery, $connect_transfert) or die(mysql_error_show_message(mysql_error()));
$data = mysql_fetch_assoc($qh);
$totalRows_clp = mysql_num_rows($qh);

//if(isset($_SESSION["clp_projet"])){ ?><b><?php /*echo $_SESSION["clp_projet_sigle"]; if(isset($data["nom_ugl"])) *//*echo "<span style='color:yellow; padding-left:150px'>( ".$data["abrege_ugl"]." )</span>";*/ ?></b><?php //} else { ?><!--Veuillez s&eacute;lectionner un projet--><?php //} ?> <!--</h4></center></div>-->
                <table width="100%"  border="0" align="left" cellspacing="2" cellpadding="2">
                <?php if(isset($_SESSION['clp_niveau'])) {?>
<?php if(isset($code_ms)){ ?>
                 <tr bgcolor="">
                   <td valign="middle" nowrap="nowrap"><div align="left">
                     <?php include("content/annee_projet_all.php"); ?></div></td>
                      <td align="right" width="150"><div align="right">
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
echo do_link("","","Edition de mission","Mission","","./","pull-right p11","get_content('new_mission.php','','modal-body_add',this.title,'iframe');",1,"",$nfile);
?>
<?php } ?>
</div></td>
<td align="right" width="200"><div align="right"> <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1 && $totalRows_edit_ms>0){ ?>
<?php echo do_link("","","$nom","<i class=\"icon-plus\"> Ajouter une recommandation </i>","","./","pull-right p11","get_content('edit_recommandation.php','mission=$code_ms&annee=$annee','modal-body_add',this.title);",1,"",$nfile); ?> <?php } ?> </div></td>
                    </tr><?php }else{ ?>
                  <tr bgcolor="">
                   <td colspan="2" valign="middle" nowrap="nowrap"><div align="left">
                     <?php include("content/annee_projet_all.php"); ?></div></td>
                      <td align="right" width="150"><div align="right"><?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<4) {?>
<?php
echo do_link("","","Edition de mission","Mission","","./","pull-right p11","get_content('new_mission.php','','modal-body_add',this.title,'iframe');",1,"",$nfile);
?>
<!-- <a onclick="get_content('new_mission.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Edition de mission" class="thickbox Add"  dir="">Mission</a>    -->
<?php } ?></div></td>
                    </tr>
                    <?php } ?>
                    <?php } ?>
                 <tr bgcolor="">
                   <td colspan="3" valign="middle" nowrap="nowrap"><div align="right">
                     <form name="form2" id="form2" method="get" action="" class="contenuh1">
                       <table   border="0" cellspacing="2">
                         <tr>
                           <th nowrap="nowrap" scope="col"><input type="hidden" name="annee" value="<?php echo $annee; ?>" /></th>
                           <th nowrap="nowrap" scope="col">Mission:
                             <select name="code_ms" style=" ">
                               <option value="">-- Choisissez une mission --</option>
            <?php
				  if($totalRows_liste_ms>0) {
do {
?>
            <option <?php if(isset($code_ms) && $code_ms==$row_liste_ms['code_ms']) echo 'SELECTED="selected"';   ?> value="<?php echo  $row_liste_ms['code_ms']; ?>">
            <?php echo "<b>".$row_liste_ms['code_ms'].":</b> "; if(isset($row_liste_ms['type'])) echo $row_liste_ms['type']." / du ".implode('-',array_reverse(explode('-',$row_liste_ms['debut'])))." au ".implode('-',array_reverse(explode('-',$row_liste_ms['fin']))); else echo "du ".implode('-',array_reverse(explode('-',$row_liste_ms['debut'])))." au ".implode('-',array_reverse(explode('-',$row_liste_ms['fin'])));?>
            </option>
            <?php
} while ($row_liste_ms = mysql_fetch_assoc($liste_ms));
  $rows = mysql_num_rows($liste_ms);
  if($rows > 0) {
      mysql_data_seek($liste_ms, 0);
	  $row_liste_ms = mysql_fetch_assoc($liste_ms);
  }}
?>
                             </select></th>
                           <th scope="col"><input type="submit" name="Submit" value="Rechercher" style="color:#FF0000 " /></th>
                         </tr>
                       </table>
                     </form>
                   </div></td>
                   </tr>
				   <?php 
				 if(isset($code_ms)) {//requete groupe d'activite
                                ?>
                </table>

<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i><strong>
<a onclick="get_content('new_mission.php','<?php echo "id=$code_ms&add=1&add2=1"; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modification de la mission" class="thickbox Add"  dir=""><?php echo $nom;?></a>
</strong></h4>

<?php include_once 'modal_add.php'; ?>

</div>
<div class="widget-content">
<table class="table table-striped table-bordered table-hover table-responsive table-tabletools table-colvis datatable dataTable hide_befor_load" id="mytable" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">N&deg;</div></th>
  <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Domaine</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">R&eacute;f.</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Recommandations</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Date buttoir</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Responsables </div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Plan d' actions </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Rapport</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Statut</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Observations</div></th>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="80">Actions</th>
<?php } ?>
</tr>
</thead>

<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php $i=0; if(isset($totalRows_act) && $totalRows_act>0) { $r1="j"; do { $id=$row_act["id_recommandation"];

if(isset($_GET["add"])){

$insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."mission_plan (code_ms, code_rec, ordre, phase, proportion, date_prevue, responsable, observation, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s,%s, '$date')",
              GetSQLValueString($row_act["mission"], "text"),
              GetSQLValueString($id, "text"),
              GetSQLValueString("1", "text"),
              GetSQLValueString("T&acirc;che 1", "text"),
              GetSQLValueString(100, "text"),
              GetSQLValueString($row_act["date_buttoir"], "date"),
              GetSQLValueString(((isset($respo_ugl[$row_act["responsable_interne"]]))?$respo_ugl[$row_act["responsable_interne"]]:$row_act["responsable_interne"]), "text"),
              GetSQLValueString("", "text"),
              GetSQLValueString($_SESSION['clp_id'], "text"));
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
    //$Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error());
 //echo $insertSQL;

$insertSQL = "UPDATE ".$database_connect_prefix."mission_plan SET date_reelle='2014-11-22' WHERE code_ms='".$row_act["mission"]."' and code_rec='".$id."'";
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
  //$Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error());

  if($row_act["ref_no"]>8){
  $insertSQL = "UPDATE ".$database_connect_prefix."mission_plan SET valider=0,observation='' WHERE code_ms='".$row_act["mission"]."' and code_rec='".$id."'";
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
  //$Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error());
    }
}


 ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
  <td><div align="left" title=""> <?php echo (isset($row_act["ref_no"]) && $row_act["ref_no"]>0)?$row_act["ref_no"]:$i+1;  ?></div></td>
  <td><div align="left" style="font-size:11px" title="<?php if(isset($liste_rub_array[$row_act["rubrique"]]))  echo $liste_rub_array[$row_act['rubrique']]; ?>"> <?php if(isset($liste_rub_array[$row_act["rubrique"]]))  echo $liste_rub_array[$row_act['rubrique']];  ?></div></td>
<td valign="middle"><div align="center"><strong><?php echo $row_act['numero']; ?></strong></div></td>
<td class="Style4"><div align="left" class="Style4"><?php echo $row_act['recommandation']; ?></div></td>
<td><div align="center"><span class="Style4">
<?php if(isset($row_act['type']) && $row_act['type']=="Continu") echo "Continu"; else echo date_reg($row_act['date_buttoir'],"/");  ?>
</span></div></td>
<td><div align="left" title="<?php if(isset($respo_ugl[$row_act["responsable_interne"]])) echo $respo_ugl[$row_act["responsable_interne"]]; ?>"><?php  if(isset($ugl_respo[$row_act["volet_recommandation"]])) echo $ugl_respo[$row_act["volet_recommandation"]]; ?>(<?php if(isset($respo_ugl[$row_act["responsable_interne"]])) echo $respo_ugl[$row_act["responsable_interne"]]; ?>/<?php if(isset($row_act['responsable'])) echo $row_act['responsable']; ?>)</div></td>
<td>&nbsp;<a onclick="get_content('./plan_mission_supervision.php','<?php echo "rec=".$row_act['id_recommandation']."&idms=$code_ms&annee=$annee"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add"  title="Plan d'action de suivi de reconmmandation" class="thickbox" dir="">T&acirc;ches</a></td>
<td align="center"><?php if(isset($row_act["rapport"]) && file_exists($dir.$row_act["rapport"])) echo "<a href='./download_file.php?file=".$dir.$row_act["rapport"]."' title='T&eacute;l&eacute;charger ".$row_act["rapport"]."' ><img src=\"./images/download.png\" width=\"20\" height=\"20\" alt=\"T&eacute;l&eacute;charger le rapport\" title=\"T&eacute;l&eacute;charger le rapport\"></a>"; ?>

<a href="#myModal_add" data-backdrop="static" data-keyboard="false" data-toggle="modal" title="Ajout de rapport de mission" onclick="get_content('edit_recommandation.php','<?php echo "id=$id&mission=$code_ms&annee=$annee&rapport=1"; ?>','modal-body_add',this.title);" style=""><?php if(isset($row_act["rapport"]) && file_exists($dir.$row_act["rapport"])) echo "Modifier"; else echo "Ajouter"; ?></a>

<?php //} ?></td>
<td valign="middle" nowrap="nowrap">
<?php
$color = "red";
$tauxp=0;

 if(isset($prop_tab[$id]))
 { $tauxp=$prop_tab[$id];
 if($tauxp<100) $color = "#FFD700";
 elseif($tauxp>=100) $color = "green";
    } elseif(in_array($id,$prop_stat)){ $prop_tab[$id] = 0; $color = "#FFD700"; }
	//if(date("Y-m-d")>$row_act['date_buttoir'] && $row_act['type']!="Continu" and $tauxp<100) $color = "red";
/*$color = "gray"; $stat="Non entam&eacute;e";
if(isset($tableau_stat[$row_act['numero']]) && isset($date_stat[$row_act['numero']])) {
 $stat=$tableau_stat[$row_act['numero']];
 $datestat=$date_stat[$row_act['numero']];
if($stat!="Réalisé" && $datestat>$row_act['date_buttoir'] && $row_act['type']!="Continu") $color = "red";
elseif($stat=="Réalisé") $color = "green";
elseif($stat=="En cours") $color = "yellow";}
elseif(!isset($tableau_stat[$row_act['numero']]) && date("Y-m-d")>$row_act['date_buttoir'] && $row_act['type']!="Continu") {
$color = "red";
}*/
//elseif($stat=="Non Ã©chu") $color = "gray";  ?>
<div> <a id="recommandation_<?php echo $row_act['id_recommandation']; ?>" style="display: block; border: solid 1px; background-color: #E8E8E8" onclick="get_content('suivi_plan_mission_supervision.php','<?php echo "rec=".$row_act['id_recommandation']."&idms=$code_ms&annee=$annee"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" title="Suivre la recommandation" class="thickbox Add"  dir=""><span id="stat_<?php echo $annee.$row_act['id_recommandation'];  ?>" ><div style="width: <?php if(isset($prop_tab[$id])) echo $prop_tab[$id]; ?>%; background-color: <?php echo $color; ?>; color:#FFFFFF;"><?php if(isset($prop_tab[$id])) echo $prop_tab[$id]." %"; elseif(date("Y-m-d")>$row_act['date_buttoir'] && $row_act['type']!="Continu") echo "Non entam&eacute;e"; else echo "Non &eacute;chu"; ?></div></span></a> </div></td>
<td><div align="left" style="font-size:11px" > <?php echo $row_act['observation'];  ?></div></td>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<td align="center" nowrap="nowrap" class=" ">
<?php
echo do_link("","",$nom,"","edit","./","","get_content('edit_recommandation.php','id=$id&mission=$code_ms&annee=$annee','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=$id&code_ms=$code_ms&annee=$annee","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette recommendation ?');",0,"margin:0px 5px;",$nfile);
?></td>
<?php } ?>
</tr>
<?php $i++; } while ($row_act = mysql_fetch_assoc($act)); } ?>
</tbody></table>

</div> </div>


				   <?php } else {?>
                  <tr>
                    <td colspan="21" nowrap="nowrap"><h1 align="center" class="Style5">Veuillez s&eacute;lectionnez une mission !!! </h1></td>
                    </tr>
                  </table>
                  <?php } ?>


<!-- Fin Site contenu ici -->
            </div>
        </div>
  </div>
        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>

</body>
</html>