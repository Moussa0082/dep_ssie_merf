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
//header('Content-Type: text/html; charset=ISO-8859-15');
if(isset($_GET["id"]))
{
    $id = intval($_GET["id"]);
    $query_liste_dossier = "SELECT * FROM ".$database_connect_prefix."suivi_avenant where id_suivi='$id'";
	  	   try{
    $liste_dossier = $pdar_connexion->prepare($query_liste_dossier);
    $liste_dossier->execute();
    $row_liste_dossier = $liste_dossier ->fetch();
    $totalRows_liste_dossier = $liste_dossier->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

    echo "<b>Date</b> : ".date_reg($row_liste_dossier["date_avenant"],'/');
    //echo "<br><b>Exp&eacute;diteur</b> : ".$row_liste_dossier["expediteur"];
    //echo "<br><b>Objet</b> : ".$row_liste_dossier["objet"];
    if(!empty($row_liste_dossier["documents"]))
    {
      $dir = './attachment/avenant_contrat/';
      $a = explode('|',$row_liste_dossier["documents"]);
      echo "<br><b>Pi&egrave;ces jointes</b> : ";
      foreach($a as $b)
      if(!empty($b)) echo "<a style='' href=\"$dir$b\" target='_blank' title='Télécharger' alt=\"$b\">$b</a>&nbsp;&nbsp;&nbsp;";
      //echo "<div style='clear:both; height:0px;'><hr></div>";
    }
	/*$iac=$row_liste_dossier["action"]; 
	if($iac==1) $actionm="Ordre(s) de mission approuvé(s)"; elseif($iac==2) $actionm="Suivi du VISA";
	 elseif($iac==3) $actionm="Ordre(s) de mission visé(s)"; elseif($iac==4) $actionm="Fin du processus";*/
				  
     echo "<br><b>Montant</b> : ".$row_liste_dossier["montant"];
    echo "<br><b>Num&eacute;ro</b> : ".$row_liste_dossier["numero_avenant"];

}
else echo "<h1 align='center'>Rien &agrave; afficher ici !</h1>";

?>      