<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"]) && !isset($_GET["id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}

include_once $config->sys_folder."/essentiel.php";

extract($_GET);
if ((isset($id) && $id!='')) {
  $id=$_SESSION['clp_id'];
  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."personnel SET programme_active=%s WHERE id_personnel=%s",
                       GetSQLValueString($_GET["id"], "text"),
                       GetSQLValueString($id, "int"));

  $insertSQL = $pdar_connexion->prepare($insertSQL);
  $Result1 = $insertSQL->execute();
  if($Result1)
  {
    $mySqlQuery = "SELECT * FROM ".$database_connect_prefix."programmes_ccc where id_programmes=".GetSQLValueString($_GET["id"], "text");
    $qh = $pdar_connexion ->prepare($mySqlQuery);
    $qh->execute();
    $data = $qh ->fetch();
    $totalRows = $qh->rowCount();
    if($totalRows > 0)
    {
      $_SESSION["clp_programmes_2qc"] = $data['id_programmes'];
	  $_SESSION["clp_programmes_2qc_sigle_programme"] = htmlentities($data["sigle_programme"]);
	  $_SESSION["clp_programmes_2qc_nom_programme"] = htmlentities($data["nom_programme"]);
	  $_SESSION["clp_programmes_2qc_vision"] = htmlentities($data["vision"]);
      $_SESSION["clp_programmes_2qc_objectif"] = htmlentities($data['objectif']);
      $_SESSION["clp_programmes_2qc_annee_debut"] = $data['annee_debut'];
      $_SESSION["clp_programmes_2qc_annee_fin"] = $data['annee_fin'];
      $_SESSION["clp_programmes_2qc_actif"] = "Programme 2QC (".$data['annee_debut']." - ".$data['annee_fin'].")";
      $_SESSION["clp_where"] = " programmes_2qc='".$_SESSION['clp_structure']."'";
    }
  }

  $insertGoTo = (isset($_GET['page']))?$_GET['page']:"./?";
  $sup = strchr($insertGoTo,'?')?"&":"?";
  if ($Result1 && $totalRows > 0) $insertGoTo .= $sup."update=ok"; else $insertGoTo .= $sup."update=no";
  header(sprintf("Location: %s", $insertGoTo));  exit();
}

?>