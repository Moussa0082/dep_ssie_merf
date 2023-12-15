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
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; 
if (isset($_POST["MM_form"]) && $_SESSION["clp_fonction"]!="Administrateur") {
  header(sprintf("Location: %s", $_SERVER['PHP_SELF'].'?auth=no'));
  exit;
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{ //Objectif Global
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO objectif_global (intitule_objectif_global, code_og, id_personnel, date_enregistrement) VALUES (%s, %s,'$personnel', '$date')",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from objectif_global WHERE id_objectif_global=%s",
                         GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE objectif_global SET intitule_objectif_global=%s, code_og=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_objectif_global=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "int"),
                         GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{ //Indicateur Objectif Global
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO indicateur_objectif_global (intitule_indicateur_objectif_global, code_iog, id_personnel, date_enregistrement) VALUES (%s, %s,'$personnel', '$date')",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from indicateur_objectif_global WHERE id_indicateur_objectif_global=%s",
                         GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE indicateur_objectif_global SET intitule_indicateur_objectif_global=%s, code_iog=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur_objectif_global=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "int"),
                         GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{ //Source Objectif Global
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO source_og (intitule_source, id_personnel, date_enregistrement) VALUES (%s,'$personnel', '$date')",
                         GetSQLValueString($_POST['nom'], "text"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from source_og WHERE id_source=%s",
                         GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE source_og SET intitule_source=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_source=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form4"))
{ //Hypothèse Objectif Global
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO hypothese_og (intitule_hypothese, id_personnel, date_enregistrement) VALUES (%s,'$personnel', '$date')",
                         GetSQLValueString($_POST['nom'], "text"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from hypothese_og WHERE id_hypothese=%s",
                         GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE hypothese_og SET intitule_hypothese=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_hypothese=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form5"))
{ //Objectif Spécifique
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO objectif_specifique (intitule_objectif_specifique, code_os, id_personnel, date_enregistrement) VALUES (%s, %s,'$personnel', '$date')",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from objectif_specifique WHERE id_objectif_specifique=%s",
                         GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE objectif_specifique SET intitule_objectif_specifique=%s, code_os=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_objectif_specifique=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "int"),
                         GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form6"))
{ //Indicateur Objectif Spécifique
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO indicateur_objectif_specifique (intitule_indicateur_objectif_specifique, code_ios, objectif_specifique, id_personnel, date_enregistrement) VALUES (%s, %s, %s, '$personnel', '$date')",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "int"),
                         GetSQLValueString($_POST['objectif'], "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from indicateur_objectif_specifique WHERE id_indicateur_objectif_specifique=%s",
                         GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE indicateur_objectif_specifique SET intitule_indicateur_objectif_specifique=%s, code_ios=%s, objectif_specifique=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur_objectif_specifique=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "int"),
                         GetSQLValueString($_POST['objectif'], "int"),
                         GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form7"))
{ //Source Objectif Spécifique
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO source_os (intitule_source, objectif_specifique, id_personnel, date_enregistrement) VALUES (%s, %s, '$personnel', '$date')",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['objectif'], "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from source_os WHERE id_source=%s",
                         GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE source_os SET intitule_source=%s, objectif_specifique=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_source=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['objectif'], "int"),
                         GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form8"))
{ //Hypothèse Objectif Spécifique
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO hypothese_os (intitule_hypothese, objectif_specifique, id_personnel, date_enregistrement) VALUES (%s, %s, '$personnel', '$date')",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['objectif'], "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from hypothese_os WHERE id_hypothese=%s",
                         GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE hypothese_os SET intitule_hypothese=%s, objectif_specifique=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_hypothese=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['objectif'], "int"),
                         GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form9"))
{ //Hypothèse Objectif Spécifique
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO resultat (intitule_resultat, code_resultat, composante, id_personnel, date_enregistrement) VALUES (%s, %s, %s, '$personnel', '$date')",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "int"),
                         GetSQLValueString($_POST['composante'], "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from resultat WHERE id_resultat=%s",
                         GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE resultat SET intitule_resultat=%s, code_resultat=%s, composante=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_resultat=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "int"),
                         GetSQLValueString($_POST['composante'], "int"),
                         GetSQLValueString($id, "int"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
$editFormAction = $_SERVER['PHP_SELF'];
if (isset ($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
// query og
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_og = "SELECT * FROM objectif_global";
$og = mysql_query($query_og, $pdar_connexion) or die(mysql_error());
$row_og = mysql_fetch_assoc($og);
$totalRows_og = mysql_num_rows($og);
// query indicateur
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_ind = "SELECT * FROM indicateur_objectif_global order by id_indicateur_objectif_global";
$ind = mysql_query($query_ind, $pdar_connexion) or die(mysql_error());
$row_ind = mysql_fetch_assoc($ind);
$totalRows_ind = mysql_num_rows($ind);
// query source de verification
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_src = "SELECT * FROM source_og order by id_source";
$src = mysql_query($query_src, $pdar_connexion) or die(mysql_error());
$row_src = mysql_fetch_assoc($src);
$totalRows_src = mysql_num_rows($src);
// query hypothese
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_hyp = "SELECT * FROM hypothese_og order by id_hypothese";
$hyp = mysql_query($query_hyp, $pdar_connexion) or die(mysql_error());
$row_hyp = mysql_fetch_assoc($hyp);
$totalRows_hyp = mysql_num_rows($hyp);
// Partie objectif specifique
// objectif specifique
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_os = "SELECT * FROM objectif_specifique order by id_objectif_specifique";
$os = mysql_query($query_os, $pdar_connexion) or die(mysql_error());
$row_os = mysql_fetch_assoc($os);
$totalRows_os = mysql_num_rows($os);
// Partie resultat
//composante
// requete composante
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cp = "SELECT * FROM composante order by id_composante";
$cp = mysql_query($query_cp, $pdar_connexion) or die(mysql_error());
$row_cp = mysql_fetch_assoc($cp);
$totalRows_cp = mysql_num_rows($cp);
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cpr = "SELECT * FROM composante order by id_composante";
$cpr = mysql_query($query_cpr, $pdar_connexion) or die(mysql_error());
$row_cpr = mysql_fetch_assoc($cpr);
$totalRows_cpr = mysql_num_rows($cpr);
include_once 'modal_add.php';
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
<div class="widget-header"> <h4><i class="icon-reorder"></i> Cadre Logique </h4>
    <?php if (isset ($_SESSION['clp_niveau']) && $_SESSION['clp_niveau'] < 2) {?>
<a href="objectif_general_edit.php" title="Editer l'Objectif général" class="pull-right p11"><i class="icon-plus"> Objectif général </i></a>
<a href="objectif_developpement_edit.php" title="Editer les objectifs de développement" class="pull-right p11"><i class="icon-plus"> ODP </i></a>
<a href="effet_edit.php" title="Editer les effets" class="pull-right p11"><i class="icon-plus"> Effets </i></a>
<a href="produit_edit.php" title="Editer les produits" class="pull-right p11"><i class="icon-plus"> Produits </i></a>
    <?php }?>
</div>
</div>
<table width="100%"  border="0" cellspacing="0">
          <tr>
            <th scope="col"><div align="left">
              </div></th>
          </tr>
          <tr>
            <td><table width="100%" border="1" align="center" cellspacing="0">
              <tr bgcolor="#D2E2B1">
                <td colspan="4" valign="top"><div align="center"><strong>Cadre Logique du FIER au <?php echo date("d-m-Y");?></strong> </div></td>
              </tr>
              <tr bgcolor="#FFFFFF">
                <td colspan="4" valign="top">&nbsp;</td>
              </tr>
              <tr bgcolor="#D9D9D9">
                <td valign="middle" width="25%"><strong> R&eacute;sum&eacute; descriptif </strong></td>
                <td valign="middle" width="25%"><strong> Indicateurs objectivement v&eacute;rifiables</strong> </td>
                <td valign="middle" width="25%"><strong> Source d&rsquo;information</strong> </td>
                <td valign="middle" width="25%"><strong> Risques/hypoth&egrave;ses</strong> </td>
              </tr>
              <tr>
                <td valign="top" bgcolor="#FFFFFF" width="25%"><strong> 1. <span class="Style22">OBJECTIF GLOBAL</span> (But) </strong></td>
                <td valign="top" bgcolor="#ADADAD" width="25%">&nbsp;</td>
                <td valign="top" bgcolor="#ADADAD" width="25%">&nbsp;</td>
                <td valign="top" bgcolor="#ADADAD" width="25%">&nbsp;</td>
              </tr>
              <tr>
                <td valign="top"><div align="left">
                    <?php if ($totalRows_og > 0) {$i = 0;do {$id = $row_og['id_objectif_global'];?>
<a onclick="get_content('new_objectif_global.php','id=<?php echo $id;?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><span style=""><span class="Style10">
                <?php echo $row_og['intitule_objectif_global'];?>
                </span></span></a>&nbsp;<a onclick="return confirm('Voulez-vous vraiment supprimer cet Objectif ?');" href="new_objectif_global.php?id_sup=<?php echo $id;?>" title="Supprimer" class="thickbox Add"  dir=""><img src="images/delete.png" width="15" border="0"/></a>
                    <?php } while ($row_og = mysql_fetch_assoc($og));}?>
                </div>
                <?php if ($totalRows_og <= 0) {?>
                      <div align="center" class="clear"><a onclick="get_content('new_objectif_global.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div>
                <?php }?>
                </td>
                <td valign="top"><table border="0" cellspacing="0">
                    <?php if ($totalRows_ind > 0) {$i = 0;do {$id = $row_ind["id_indicateur_objectif_global"];?>
                    <tr <?php if ($i % 2 == 0) echo 'bgcolor="#ECF0DF"';$i = $i + 1;?>>
                      <td ><div align="left">
<a onclick="get_content('new_indicateur_objectif_global.php','id=<?php echo $id;?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><span style=""><span class="Style10">
                <?php echo "- " . $row_ind['intitule_indicateur_objectif_global'] . " (" . $row_ind['unite'] . ")";?>
                </span></span></a>&nbsp;<a onclick="return confirm('Voulez-vous vraiment supprimer cet Indicateur ?');" href="new_indicateur_objectif_global.php?id_sup=<?php echo $id;?>" title="Supprimer" class="thickbox Add"  dir=""><img src="images/delete.png" width="15" border="0"/></a>
                      </div></td>
                    </tr>
                    <?php } while ($row_ind = mysql_fetch_assoc($ind));?>
                    <?php }?>
                </table>
                      <div align="center" class="clear"><a onclick="get_content('new_indicateur_objectif_global.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div>
                </td>
                <td valign="top"><table border="0" cellspacing="0">
                    <?php if ($totalRows_src > 0) {$i = 0;do {$id = $row_src['id_source'];?>
                    <tr <?php if ($i % 2 == 0) echo 'bgcolor="#ECF0DF"';$i = $i + 1;?>>
                      <td ><div align="left">
<a onclick="get_content('new_source_objectif_global.php','id=<?php echo $id;?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><span style=""><span class="Style10">
                <?php echo "- " . $row_src['intitule_source'];?>
                </span></span></a>&nbsp;<a onclick="return confirm('Voulez-vous vraiment supprimer cette Source ?');" href="new_source_objectif_global.php?id_sup=<?php echo $id;?>" title="Supprimer" class="thickbox Add"  dir=""><img src="images/delete.png" width="15" border="0"/></a>
                      </div></td>
                    </tr>
                    <?php } while ($row_src = mysql_fetch_assoc($src));?>
                    <?php }?>
                </table>
                      <div align="center" class="clear"><a onclick="get_content('new_source_objectif_global.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div>
                </td>
                <td valign="top"><div align="center">
                    <table border="0" align="left" cellspacing="0">
                      <?php if ($totalRows_hyp > 0) {$i = 0;do {$id = $row_hyp['id_hypothese'];?>
                      <tr <?php if ($i % 2 == 0) echo 'bgcolor="#ECF0DF"';$i = $i + 1;?>>
                        <td><div align="left">
<a onclick="get_content('new_hypothese_objectif_global.php','id=<?php echo $id;?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><span style=""><span class="Style10">
                <?php echo "- " . $row_hyp['intitule_hypothese'];?>
                </span></span></a>&nbsp;<a onclick="return confirm('Voulez-vous vraiment supprimer cette Hypothese ?');" href="new_hypothese_objectif_global.php?id_sup=<?php echo $id;?>" title="Supprimer" class="thickbox Add"  dir=""><img src="images/delete.png" width="15" border="0"/></a>
                        </div></td>
                      </tr>
                      <?php } while ($row_hyp = mysql_fetch_assoc($hyp));?>
                      <?php }?>
                    </table>
                      <div align="center" class="clear"><a onclick="get_content('new_hypothese_objectif_global.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div>
                </div></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><div align="left">
              <table width="100%" border="1" align="left" cellspacing="1">
                <tr>
                  <td nowrap="nowrap" bgcolor="#FFFFFF" width="25%"><strong>2. <span class="Style21">OBJECTIF DE DEVELOPPEMENT </span></strong></td>
                  <td bgcolor="#ADADAD" width="25%"><strong>Indicateurs objectivement v&eacute;rifiables</strong> </td>
                  <td bgcolor="#ADADAD" width="25%"><strong>Source d&rsquo;information</strong></td>
                  <td bgcolor="#ADADAD" width="25%"><strong>Risques/hypoth&egrave;ses</strong></td>
                </tr>
                <?php
                if ($totalRows_os > 0) {
                  $o = 0;
                  do {
                    $id = $row_os['id_objectif_specifique'];
                    ?>
                <tr     <?php
     if ($o % 2 == 0)
       echo 'bgcolor="#ECF0DF"';
     $o = $o + 1;
     ?>
>
                  <td valign="top"><div align="left"><a onclick="get_content('new_objectif_specifique.php','id=<?php echo $id; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><?php echo $row_os['intitule_objectif_specifique']; ?>
</a>&nbsp;<a onclick="return confirm('Voulez-vous vraiment supprimer cet Objectif ?');" href="new_objectif_specifique.php?id_sup=<?php echo $id; ?>" title="Supprimer" class="thickbox Add"  dir=""><img src="images/delete.png" width="15" border="0"/></a></div>
</td>
                  <td valign="top"><table border="0" align="left" cellspacing="0">
                          <?php
                          $id_os = $row_os['id_objectif_specifique'];
                          mysql_select_db($database_pdar_connexion, $pdar_connexion);
                          $query_ind = "SELECT * FROM indicateur_objectif_specifique where objectif_specifique='$id_os'";
                          $ind = mysql_query($query_ind, $pdar_connexion) or die(mysql_error());
                          $row_ind = mysql_fetch_assoc($ind);
                          $totalRows_ind = mysql_num_rows($ind);
                          ?>
                      <?php if ($totalRows_ind > 0) {$i = 0;do {$id = $row_ind['id_indicateur_objectif_specifique'];?>
                      <tr <?php if ($i % 2 == 0) echo 'bgcolor="#FFFFFF"';$i = $i + 1;?>>
                        <td><div align="left"><a onclick="get_content('new_indicateur_objectif_specifique.php','id=<?php echo $id;?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><?php echo "- " . $row_ind['intitule_indicateur_objectif_specifique'] . " : (" . $row_ind['unite'] . ")";?><?php if (isset ($row_ind['niveau_sygri']) && $row_ind['niveau_sygri'] == 1) {?>
                        <span class="Style5">*</span> <?php }?></a><a onclick="return confirm('Voulez-vous vraiment supprimer cet Indicateur ?');" href="new_indicateur_objectif_specifique.php?id_sup=<?php echo $id;?>" title="Supprimer" class="thickbox Add"  dir=""><img src="images/delete.png" width="15" border="0"/></a></div></td>
                      </tr>
                              <?php
                            } while ($row_ind = mysql_fetch_assoc($ind));
                            ?>
                            <?php
                          }
                          ?>
                      <tr>
                        <td><div align="center" class="Style2">
                                <?php
                                if (!$totalRows_ind > 0)
                                  echo "Aucun indicateur enregistr&eacute;: ";
                                ?>
                        </div></td>
                      </tr>
                          <?php
                          if (isset ($_GET['ad_ind']) && $_GET['ad_ind'] == $row_os['id_objectif_specifique']) {
                            ?>
                            <?php
                          }
                          ?>
                  </table><div align="center" class="clear"><a onclick="get_content('new_indicateur_objectif_specifique.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div></td>
                  <td valign="top"><table border="0" align="left" cellspacing="0">
                          <?php
                          $id_os = $row_os['id_objectif_specifique'];
                          $query_src = "SELECT * FROM source_os where objectif_specifique='$id_os'";
                          $src = mysql_query($query_src, $pdar_connexion) or die(mysql_error());
                          $row_src = mysql_fetch_assoc($src);
                          $totalRows_src = mysql_num_rows($src);
                          ?>
                      <?php if ($totalRows_src > 0) {$i = 0;do {$id = $row_src['id_source'];?>
                      <tr <?php if ($i % 2 == 0) echo 'bgcolor="#ECF0DF"';$i = $i + 1;?>>
                        <td><div align="left"><a onclick="get_content('new_source_objectif_specifique.php','id=<?php echo $id;?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><?php echo "- " . $row_src['intitule_source'];?></a>&nbsp;<a onclick="return confirm('Voulez-vous vraiment supprimer cette Source ?');" href="new_source_objectif_specifique.php?id_sup=<?php echo $id; ?>" title="Supprimer" class="thickbox Add"  dir=""><img src="images/delete.png" width="15" border="0"/></a></div></td>
                      </tr>
                              <?php
                            } while ($row_src = mysql_fetch_assoc($src));
                            ?>
                            <?php
                          }
                          ?>
                      <tr>
                        <td><div align="center">
                                <?php
                                if (!$totalRows_src > 0)
                                  echo "Aucune source enregistr&eacute;e: ";
                                ?>
                        </div></td>
                      </tr>
                          <?php
                          if (isset ($_GET['ad_src']) && $_GET['ad_src'] == $row_os['id_objectif_specifique']) {
                            ?>
                            <?php
                          }
                          ?>
                  </table><div align="center" class="clear"><a onclick="get_content('new_source_objectif_specifique.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div></td>
                  <td valign="top"><table border="0" align="left" cellspacing="0">
                          <?php
                          $id_os = $row_os['id_objectif_specifique'];
                          $query_hyp = "SELECT * FROM hypothese_os where objectif_specifique='$id_os'";
                          $hyp = mysql_query($query_hyp, $pdar_connexion) or die(mysql_error());
                          $row_hyp = mysql_fetch_assoc($hyp);
                          $totalRows_hyp = mysql_num_rows($hyp);
                          ?>
                      <?php if ($totalRows_hyp > 0) {$i = 0;do {$id = $row_hyp['id_hypothese'];?>
                      <tr <?php if ($i % 2 == 0) echo 'bgcolor="#ECF0DF"';$i = $i + 1;?>>
                        <td><div align="left"><a onclick="get_content('new_hypothese_objectif_specifique.php','id=<?php echo $id;?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><?php echo "- " . $row_hyp['intitule_hypothese'];?></a>&nbsp;<a onclick="return confirm('Voulez-vous vraiment supprimer cette Hypoth&egrave;se ?');" href="new_hypothese_objectif_specifique.php?id_sup=<?php echo $id;?>" title="Supprimer" class="thickbox Add"  dir=""><img src="images/delete.png" width="15" border="0"/></a></div></td>
                      </tr>
                      <?php } while ($row_hyp = mysql_fetch_assoc($hyp));?>
                      <?php }?>
                      <tr>
                        <td><div align="center" class="Style2">
                            <?php if (!$totalRows_hyp > 0) echo "Aucune hypothese enregistr&eacute;e: ";?>
                        </div></td>
                      </tr>
                      <?php if (isset ($_GET['ad_hyp']) && $_GET['ad_hyp'] == $row_os['id_objectif_specifique']) {?>
                      <?php }?>
                  </table><div align="center" class="clear"><a onclick="get_content('new_hypothese_objectif_specifique.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div></td>
                </tr>
                    <?php
                  } while ($row_os = mysql_fetch_assoc($os));
                  ?>
                  <?php
                }
                else {
                  ?>
                <tr>
                  <td colspan="4" nowrap="nowrap"><div align="center"><em><strong>Aucun objectif sp&eacute;cifique enregistr&eacute; </strong></em></div></td>
                </tr>
                  <?php
                }
                ?>
              <tr>
                  <td colspan="4" nowrap="nowrap"><div align="center" class="clear"><a onclick="get_content('new_objectif_specifique.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter un objectif de d&eacute;veloppement</a></div></td>
                </tr>
              </table>
            </div></td>
          </tr>
		  <tr>
		    <td bgcolor="#FFFFFF"><strong>3. R&eacute;sultats / Produits </strong></td>
		  </tr>
		  <tr><td><table width="100%" border="1" align="left" cellspacing="0">
            <tr bgcolor="#D9D9D9">
              <td colspan="4"><div align="center"><strong>R&eacute;sultats / produits par effet et par composante </strong></div></td>
            </tr>
            <?php if ($totalRows_cp > 0) { $c = 0;  do { ?>
            <tr bgcolor="#B1C3D9">
              <td colspan="4" valign="top"><div align="left" class="Style17"><?php echo $row_cp['id_composante'] . ": " . $row_cp['intitule_composante']; ?>&nbsp;</div></td>
             <!--<td colspan="2" valign="top"><div align="right" class="clear"><a onclick="get_content('new_resultat.php','composante=<?php echo $row_cp['id_composante']; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter un effet</a></div></td>-->
            </tr>
            <tr>
              <td nowrap="nowrap" bgcolor="#FFFFFF" width="25%"><div align="left"><strong>Effets</strong></div></td>
              <td bgcolor="#ADADAD" width="25%"><strong>Indicateurs objectivement v&eacute;rifiables</strong> </td>
              <td bgcolor="#ADADAD" width="25%"><strong>Source d&rsquo;information</strong></td>
              <td bgcolor="#ADADAD" width="25%"><strong>Risques/hypoth&egrave;ses</strong></td>
            </tr>
                <?php
            //debut de ligne
                $id_cp = $row_cp['id_composante'];
                mysql_select_db($database_pdar_connexion, $pdar_connexion);
                $query_res = "SELECT * FROM resultat where composante='$id_cp'";
                $res = mysql_query($query_res, $pdar_connexion) or die(mysql_error());
                $row_res = mysql_fetch_assoc($res);
                $totalRows_res = mysql_num_rows($res);
                ?>
                <?php
                if ($totalRows_res > 0) {
                  $o = 0;
                  do {
                    $id = $row_res['id_resultat'];
                    ?>
            <tr         <?php
         if ($o % 2 == 0)
           echo 'bgcolor="#ECF0DF"';
         $o = $o + 1;
         ?>
>
              <td valign="top"><div align="left"><a onclick="get_content('new_resultat.php','id=<?php echo $id; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><?php echo $row_res['intitule_resultat']; ?></a>&nbsp;<a onclick="return confirm('Voulez-vous vraiment supprimer cet Effet ?');" href="new_resultat.php?id_sup=<?php echo $id; ?>" title="Supprimer" class="thickbox Add"  dir=""><img src="images/delete.png" width="15" border="0"/></a></div>
<div align="center"><a onclick="get_content('new_resultat.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div>
</td>
              <td valign="top"><table border="0" align="left" cellspacing="0">
                <?php
                $id_res = $row_res['id_resultat'];
                mysql_select_db($database_pdar_connexion, $pdar_connexion);
                $query_ind = "SELECT * FROM indicateur_resultat where resultat='$id_res'";
                $ind = mysql_query($query_ind, $pdar_connexion) or die(mysql_error());
                $row_ind = mysql_fetch_assoc($ind);
                $totalRows_ind = mysql_num_rows($ind);
                ?>
                  <?php if ($totalRows_ind > 0) {$b = 0;do {$id = $row_ind['id_indicateur_resultat'];?>
                  <tr <?php if ($b % 2 == 0) echo 'bgcolor="#FFFFFF"';$b = $b + 1;?>>
                    <td><div align="left" class="Style11"><a onclick="get_content('new_indicateur_resultat.php','id=<?php echo $id;?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><?php echo "- " . $row_ind['intitule_indicateur_resultat'] . " : (" . $row_ind['unite'] . ")";?><?php if (isset ($row_ind['niveau_sygri']) && $row_ind['niveau_sygri'] == 1) {?>
                        <span class="Style5">*</span> <?php }?></a>&nbsp;<a onclick="return confirm('Voulez-vous vraiment supprimer cet Idicateur ?');" href="new_indicateur_resultat.php?id_sup=<?php echo $id; ?>" title="Supprimer" class="thickbox Add"  dir=""><img src="images/delete.png" width="15" border="0"/></a>
                    </div></td>
                  </tr>
                              <?php
                            } while ($row_ind = mysql_fetch_assoc($ind));
                            ?>
                            <?php
                          }
                          ?>
                  <tr>
                    <td><div align="center" class="Style2">
                                <?php
                                if (!$totalRows_ind > 0)
                                  echo "Aucun indicateur enregistr&eacute;: ";
                                ?>
                    </div></td>
                  </tr>
              </table><div align="center" class="clear"><a onclick="get_content('new_indicateur_resultat.php','composante=<?php echo $row_cp['id_composante']; ?>&resultat=<?php echo $id_res; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div></td>
              <td valign="top"><table border="0" align="left" cellspacing="0">
                          <?php
                          $id_res = $row_res['id_resultat'];
                          $query_src = "SELECT * FROM source_res where resultat='$id_res'";
                          $src = mysql_query($query_src, $pdar_connexion) or die(mysql_error());
                          $row_src = mysql_fetch_assoc($src);
                          $totalRows_src = mysql_num_rows($src);
                          ?>
                  <?php if ($totalRows_src > 0) {$i = 0;do {$id = $row_src['id_source'];?>
                  <tr <?php if ($i % 2 == 0) echo 'bgcolor="#FFFFFF"';$i = $i + 1;?>>
                    <td><div align="left" class="Style11"><a onclick="get_content('new_source_resultat.php','id=<?php echo $id; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><?php echo "- " . $row_src['intitule_source']; ?></a>&nbsp;<a onclick="return confirm('Voulez-vous vraiment supprimer cette Source ?');" href="new_source_resultat.php?id_sup=<?php echo $id; ?>" title="Supprimer" class="thickbox Add"  dir=""><img src="images/delete.png" width="15" border="0"/></a></div></td>
                  </tr>
                              <?php
                            } while ($row_src = mysql_fetch_assoc($src));
                            ?>
                            <?php
                          }
                          ?>
                  <tr>
                    <td><div align="center" class="Style2">
                                <?php
                                if (!$totalRows_src > 0)
                                  echo "Aucune source enregistr&eacute;e: ";
                                ?>
                    </div></td>
                  </tr>
              </table><div align="center" class="clear"><a onclick="get_content('new_source_resultat.php','composante=<?php echo $row_cp['id_composante']; ?>&resultat=<?php echo $id_res; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div></td>
              <td valign="top"><table border="0" align="left" cellspacing="0">
                <?php
                $id_res = $row_res['id_resultat'];
                $query_hyp = "SELECT * FROM hypothese_res where resultat='$id_res'";
                $hyp = mysql_query($query_hyp, $pdar_connexion) or die(mysql_error());
                $row_hyp = mysql_fetch_assoc($hyp);
                $totalRows_hyp = mysql_num_rows($hyp);
                ?>
                  <?php if ($totalRows_hyp > 0) {$i = 0;do {$id = $row_hyp['id_hypothese'];?>
                  <tr             <?php
             if ($i % 2 == 0)
               echo 'bgcolor="#FFFFFF"';
             $i = $i + 1;
             ?>
>
                    <td><div align="left" class="Style11"><a onclick="get_content('new_hypothese_resultat.php','id=<?php echo $id; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir="">
            <?php
            echo "- " . $row_hyp['intitule_hypothese'];
            ?>
</a>&nbsp;<a onclick="return confirm('Voulez-vous vraiment supprimer cette Hypoth&eacute;se ?');" href="new_hypothese_resultat.php?id_sup=<?php echo $id; ?>" title="Supprimer" class="thickbox Add"  dir=""><img src="images/delete.png" width="15" border="0"/></a></div></td>
                  </tr>
                              <?php
                            } while ($row_hyp = mysql_fetch_assoc($hyp));
                            ?>
                            <?php
                          }
                          ?>
                  <tr>
                    <td><div align="center" class="Style2">
                                <?php
                                if (!$totalRows_hyp > 0)
                                  echo "Aucune hypothese enregistr&eacute;e: ";
                                ?>
                    </div></td>
                  </tr>
              </table><div align="center" class="clear"><a onclick="get_content('new_hypothese_resultat.php','composante=<?php echo $row_cp['id_composante']; ?>&resultat=<?php echo $id_res; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div></td>
            </tr>
                    <?php
            //produit
                    mysql_select_db($database_pdar_connexion, $pdar_connexion);
                    $query_produit = "SELECT * FROM produit where effet='$id_res'";
                    $produit = mysql_query($query_produit, $pdar_connexion) or die(mysql_error());
                    $row_produit = mysql_fetch_assoc($produit);
                    $totalRows_produit = mysql_num_rows($produit);
                    ?>
                    <?php
                    if ($totalRows_produit > 0) {
                      $op = 0;
                      do {
                        $id = $row_produit['id_produit'];
                        ?>
            <tr             <?php
             if ($op % 2 == 0)
               echo 'bgcolor="#ECF0DF"';
             $op = $op + 1;
             ?>
>
              <td valign="top"><div align="left"><a onclick="get_content('new_produit.php','id=<?php echo $id; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><?php echo $row_produit['intitule_produit']; ?></a>&nbsp;<a onclick="return confirm('Voulez-vous vraiment supprimer cet Produit ?');" href="new_produit.php?id_sup=<?php echo $id; ?>" title="Supprimer" class="thickbox Add"  dir=""><img src="images/delete.png" width="15" border="0"/></a><br />
              </div></td>
              <td valign="top"><table border="0" align="left" cellspacing="0">
                  <?php
                  $id_prd = $row_produit['id_produit'];
                  mysql_select_db($database_pdar_connexion, $pdar_connexion);
                  $query_indp = "SELECT * FROM indicateur_produit where produit='$id_prd'";
                  $indp = mysql_query($query_indp, $pdar_connexion) or die(mysql_error());
                  $row_indp = mysql_fetch_assoc($indp);
                  $totalRows_indp = mysql_num_rows($indp);
                  ?>
                  <?php if ($totalRows_indp > 0) {$b = 0;do {$id = $row_indp['id_indicateur_produit'];?>
                  <tr <?php if ($b % 2 == 0) echo 'bgcolor="#FFFFFF"';$b = $b + 1;?>>
                    <td><div align="left" class="Style11"><a onclick="get_content('new_indicateur_produit.php','id=<?php echo $id;?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir="">
                                        <?php
                                        echo "- " . $row_indp['intitule_indicateur_produit'] . " (" . $row_indp['unite'] . ")";
                                        ?>
                <?php
                                        if (isset ($row_ind['niveau_sygri']) && $row_ind['niveau_sygri'] == 1) {
                                          ?>
                        <span class="Style5">*</span>                   <?php
                 }
                 ?>
</a>&nbsp;<a onclick="return confirm('Voulez-vous vraiment supprimer cet Indicateur ?');" href="new_indicateur_produit.php?id_sup=<?php echo $id; ?>" title="Supprimer" class="thickbox Add"  dir=""><img src="images/delete.png" width="15" border="0"/></a>
                    </div></td>
                  </tr>
                                  <?php
                                } while ($row_indp = mysql_fetch_assoc($indp));
                                ?>
                                <?php
                              }
                              ?>
                  <tr>
                    <td><div align="center" class="Style2">
                                    <?php
                                    if (!$totalRows_indp > 0)
                                      echo "Aucun indicateur enregistr&eacute;: ";
                                    ?>
                    </div></td>
                  </tr>
              </table><div align="center" class="clear"><a onclick="get_content('new_indicateur_produit.php','composante=<?php echo $row_cp['id_composante']; ?>&resultat=<?php echo $id_res; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div></td>
              <td valign="top"><table border="0" align="left" cellspacing="0">
                              <?php
                  //$id_res=$row_res['id_resultat'];
                              mysql_select_db($database_pdar_connexion, $pdar_connexion);
                              $query_srcp = "SELECT * FROM source_produit where produit='$id_prd'";
                              $srcp = mysql_query($query_srcp, $pdar_connexion) or die(mysql_error());
                              $row_srcp = mysql_fetch_assoc($srcp);
                              $totalRows_srcp = mysql_num_rows($srcp);
                              ?>
                  <?php if ($totalRows_srcp > 0) {$i = 0;do {$id = $row_srcp['id_source'];?>
                  <tr                 <?php
                 if ($i % 2 == 0)
                   echo 'bgcolor="#FFFFFF"';
                 $i = $i + 1;
                 ?>
>
                    <td><div align="left" class="Style11"><a onclick="get_content('new_source_produit.php','id=<?php echo $id; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><?php echo "- " . $row_srcp['intitule_source']; ?></a>&nbsp;<a onclick="return confirm('Voulez-vous vraiment supprimer cette Source ?');" href="new_source_produit.php?id_sup=<?php echo $id; ?>" title="Supprimer" class="thickbox Add"  dir=""><img src="images/delete.png" width="15" border="0"/></a></div></td>
                  </tr>
                                  <?php
                                } while ($row_srcp = mysql_fetch_assoc($srcp));
                                ?>
                                <?php
                              }
                              ?>
                  <tr>
                    <td><div align="center" class="Style2">
                                    <?php
                                    if (!$totalRows_srcp > 0)
                                      echo "Aucune source enregistr&eacute;e: ";
                                    ?>
                    </div></td>
                  </tr>
              </table><div align="center" class="clear"><a onclick="get_content('new_source_produit.php','composante=<?php echo $row_cp['id_composante']; ?>&resultat=<?php echo $id_res; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div></td>
              <td valign="top"><table border="0" align="left" cellspacing="0">
                      <?php
          //$id_res=$row_res['id_resultat'];
                      mysql_select_db($database_pdar_connexion, $pdar_connexion);
                      $query_hypp = "SELECT * FROM hypothese_produit where produit='$id_prd'";
                      $hypp = mysql_query($query_hypp, $pdar_connexion) or die(mysql_error());
                      $row_hypp = mysql_fetch_assoc($hypp);
                      $totalRows_hypp = mysql_num_rows($hypp);
                      ?>
                  <?php if ($totalRows_hypp > 0) {$i = 0;do {$id = $row_hypp['id_hypothese'];?>
                  <tr <?php if ($i % 2 == 0) echo 'bgcolor="#FFFFFF"';$i = $i + 1;?>>
                    <td><div align="left" class="Style11"><a onclick="get_content('new_hypothese_produit.php','id=<?php echo $id;?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><?php echo "- " . $row_hypp['intitule_hypothese'];?></a>&nbsp;<a onclick="return confirm('Voulez-vous vraiment supprimer cette Hypoth&egrave;se ?');" href="new_hypothese_produit.php?id_sup=<?php echo $id;?>" title="Supprimer" class="thickbox Add"  dir=""><img src="images/delete.png" width="15" border="0"/></a></div></td>
                  </tr>
                  <?php } while ($row_hypp = mysql_fetch_assoc($hypp));?>
                  <?php }?>
                  <tr>
                    <td><div align="center" class="Style2">
                        <?php if (!$totalRows_hypp > 0) echo "Aucune hypothese enregistr&eacute;e: ";?>
                    </div></td>
                  </tr>
              </table><div align="center" class="clear"><a onclick="get_content('new_hypothese_produit.php','composante=<?php echo $row_cp['id_composante'];?>&resultat=<?php echo $id_res;?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div></td>
            </tr>
            <?php } while ($row_produit = mysql_fetch_assoc($produit));?>
            <?php }?>
            <tr>
              <td colspan="4"><div align="center" class="Style2">
                  <?php if (!$totalRows_produit > 0) echo "Aucun produit enregistr&eacute;: ";?>
              </div></td>
            </tr>
            <?php ?>
            <?php } while ($row_res = mysql_fetch_assoc($res));?>
            <tr>
              <td colspan="4"><div align="center" class="clear"><a onclick="get_content('new_produit.php','composante=<?php echo $row_cp['id_composante'];?>&resultat=<?php echo $id_res;?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter un produit</a></div></td>
            </tr>
            <?php }?>
            <tr>
              <td colspan="4"><div align="center" class="Style2">
                  <?php if (!$totalRows_res > 0) echo "Aucun effet enregistr&eacute;: ";?>
                  <div align="center"><a onclick="get_content('new_resultat.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter un effet</a></div>
              </div></td>
            </tr>
            <?php } while ($row_cp = mysql_fetch_assoc($cp));?>
            <?php }else {?>
            <tr>
              <td colspan="4" nowrap="nowrap"><div align="center"><em><strong>Aucune composante enregistr&eacute;e; </strong></em></div></td>
            </tr>
            <?php }?>
          </table></td></tr>

        </table>
<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>
    <?php include_once ("includes/footer.php");?>
</div>
</body>
</html>