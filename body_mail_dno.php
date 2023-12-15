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
//header('Content-Type: text/html; charset=UTF-8');
if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  if(isset($_GET["dano"]))
  {
    //Contenu DANO
    $id = intval($_GET["id"]);
    $query_liste_sdno = "SELECT * FROM ".$database_connect_prefix."suivi_dno where id_suivi='$id' ";
	                 try{
    $liste_sdno = $pdar_connexion->prepare($query_liste_sdno);
    $liste_sdno->execute();
    $row_liste_sdno = $liste_sdno ->fetch();
    $totalRows_liste_sdno = $liste_sdno->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

    echo "<b>Date</b> : ".date_reg($row_liste_sdno["date_phase"],'/');
    //echo "<br><b>Exp&eacute;diteur</b> : ".$row_liste_sdno["expediteur"];
    //echo "<br><b>Objet</b> : ".$row_liste_sdno["objet"];
    if(!empty($row_liste_sdno["documents"]))
    {
      $dir = './attachment/dano/';
      $a = explode('|',$row_liste_sdno["documents"]);
      echo "<br><b>Pi&egrave;ces jointes</b> : ";
      foreach($a as $b)
      if(!empty($b)) echo "<a style='' href=\"./download_file.php?file=$dir$b\" title='Télécharger' alt='$b'>$b</a>&nbsp;&nbsp;&nbsp;";
      //echo "<div style='clear:both; height:0px;'><hr></div>";
    }
    echo "<br><b>Observation</b> : ".$row_liste_sdno["observation"];
  }
  else
  {
    //Mail DANO
    $id = intval($_GET["id"]);
    $query_liste_sdno = "SELECT * FROM ".$database_connect_prefix."mail_dno where id_mail=$id";

		                 try{
    $liste_sdno = $pdar_connexion->prepare($query_liste_sdno);
    $liste_sdno->execute();
    $row_liste_sdno = $liste_sdno ->fetch();
    $totalRows_liste_sdno = $liste_sdno->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

    $d = explode(' ',$row_liste_sdno["date"]);
    echo "<b>Date</b> : ".date_reg(date("Y-m-d",strtotime($row_liste_sdno["date"])),'/')." &agrave; $d[1]";
    echo "<br><b>Exp&eacute;diteur</b> : ".$row_liste_sdno["expediteur"];
    echo "<br><b>Objet</b> : ".$row_liste_sdno["objet"];
    if(!empty($row_liste_sdno["attachments"]))
    {
      $dir = './attachment/dano/mails/';
      $a = explode('|',$row_liste_sdno["attachments"]);
      echo "<br><b>Pi&egrave;ces jointes</b> : ";
      foreach($a as $b)
      if(!empty($b)) echo "<a style='' href=\"./download_file.php?file=$dir$b\" title='Télécharger' alt='$b'>$b</a>&nbsp;&nbsp;&nbsp;";
      //echo "<div style='clear:both; height:0px;'><hr></div>";
    }
    echo "<br><b>Message</b> : ".$row_liste_sdno["message"];
    if($totalRows_liste_sdno>0)
    {
      $query_liste_sdno = "UPDATE ".$database_connect_prefix."mail_dno SET statut=1 where id_mail=$id";
      $liste_sdno  = mysql_query($query_liste_sdno , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    }
  }
}
else echo "<h1 align='center'>Rien &agrave; afficher ici !</h1>";
?>      