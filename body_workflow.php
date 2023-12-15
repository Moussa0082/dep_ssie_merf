<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
header('Content-Type: text/html; charset=UTF-8');
if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  if(isset($_GET["doc"]))
  {
    $id = intval($_GET["id"]);
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $query_liste_dossier = "SELECT * FROM ".$database_connect_prefix."workflow where id_dossier='$id' ";
    $liste_dossier  = mysql_query($query_liste_dossier , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $row_liste_dossier = mysql_fetch_assoc($liste_dossier);
    $totalRows_liste_dossier = mysql_num_rows($liste_dossier);
    if($totalRows_liste_dossier>0)
    {
      $query_liste_sdno = "UPDATE ".$database_connect_prefix."workflow SET `read`=1 where id_dossier='$id'";
      $liste_sdno  = mysql_query($query_liste_sdno , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    }

    echo "<b>Date</b> : ".date_reg($row_liste_dossier["date_dossier"],'/');
    //echo "<br><b>Exp&eacute;diteur</b> : ".$row_liste_dossier["expediteur"];
    //echo "<br><b>Objet</b> : ".$row_liste_dossier["objet"];
    if(!empty($row_liste_dossier["documents"]))
    {
      $dir = './attachment/workflow/';
      $a = explode('|',$row_liste_dossier["documents"]);
      echo "<br><b>Pi&egrave;ces jointes</b> : ";
      foreach($a as $b)
      if(!empty($b)) echo "<a style='' href=\"./download_file.php?file=$dir$b\" title='Télécharger' alt='$b'>$b</a>&nbsp;&nbsp;&nbsp;";
      //echo "<div style='clear:both; height:0px;'><hr></div>";
    }
    echo "<br><b>Description</b> : ".$row_liste_dossier["message"];
  }
  else
  {
    $id = intval($_GET["id"]);
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $query_liste_dossier = "SELECT * FROM ".$database_connect_prefix."suivi_workflow where id_suivi='$id'";
    $liste_dossier  = mysql_query($query_liste_dossier , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $row_liste_dossier = mysql_fetch_assoc($liste_dossier);
    $totalRows_liste_dossier = mysql_num_rows($liste_dossier);
    if($totalRows_liste_dossier>0)
    {
      $query_liste_sdno = "UPDATE ".$database_connect_prefix."suivi_workflow SET `read`=1 where id_suivi='$id'";
      $liste_sdno  = mysql_query($query_liste_sdno , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    }

    echo "<b>Date</b> : ".date_reg($row_liste_dossier["date_dossier"],'/');
    //echo "<br><b>Exp&eacute;diteur</b> : ".$row_liste_dossier["expediteur"];
    //echo "<br><b>Objet</b> : ".$row_liste_dossier["objet"];
    if(!empty($row_liste_dossier["documents"]))
    {
      $dir = './attachment/workflow/';
      $a = explode('|',$row_liste_dossier["documents"]);
      echo "<br><b>Pi&egrave;ces jointes</b> : ";
      foreach($a as $b)
      if(!empty($b)) echo "<a style='' href=\"./download_file.php?file=$dir$b\" title='Télécharger' alt='$b'>$b</a>&nbsp;&nbsp;&nbsp;";
      //echo "<div style='clear:both; height:0px;'><hr></div>";
    }
    echo "<br><b>Description</b> : ".$row_liste_dossier["message"];
  }
}
else echo "<h1 align='center'>Rien &agrave; afficher ici !</h1>";
?>      