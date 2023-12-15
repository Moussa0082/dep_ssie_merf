<?php

session_start();

$path = '../';

 require($path."includes/zip.lib.php" ) ; //indiquez le chemin d'accès à la lib

 $zip = new zipfile( ) ; //on crée une nouvelle instance zip

//Chemin vers le fichier RTF

//$filename="ordre_mission_vierge.rtf";

$filename = 'ordre_mission_vierge.rtf';

include_once $path.'system/configuration.php';

$config = new Config;



function ReplaceEncodage($txt)

{

    $carimap = array(utf8_encode("é"), utf8_encode("è"), utf8_encode("ê"), utf8_encode("ë"), utf8_encode("ç"), utf8_encode("à"), utf8_encode("&nbsp;"), utf8_encode("À"), utf8_encode("É"), "'", utf8_encode("oe"), utf8_encode(""));

    $carhtml = array("é", "è", "ê", "ë", "ç", "à", "&nbsp;", "À", "É", "'", "oe", "");

    $txt = str_replace($carimap, $carhtml, $txt);



    return $txt;

}



if (!isset ($_SESSION["clp_id"])) {

  //header(sprintf("Location: %s", "./"));

  exit;

}

include_once $path.$config->sys_folder . "/database/db_connexion.php";

header('Content-Type: text/html; charset=ISO-8859-15');

if(isset($_GET["id"])) $id=$_GET["id"];

if(isset($_GET["numero"])) $numero=$_GET["numero"];

if(isset($_GET["fonction"])) $fonction=$_GET["fonction"];



$query_actom = "SELECT * FROM ".$database_connect_prefix."ateliers where id_atelier='$id'";
try{
    $actom = $pdar_connexion->prepare($query_actom);
    $actom->execute();
    $row_actom = $actom ->fetch();
    $totalRows_actom = $actom->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }






$query_participant = "SELECT titre, nom, prenom, ".$database_connect_prefix."fonction.fonction, ".$database_connect_prefix."fonction.description   FROM ".$database_connect_prefix."personnel, ".$database_connect_prefix."fonction where ".$database_connect_prefix."fonction.fonction=".$database_connect_prefix."personnel.fonction";
try{
    $participant = $pdar_connexion->prepare($query_participant);
    $participant->execute();
    $row_participant = $participant ->fetchAll();
    $totalRows_participant = $participant->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$participant_array = array();
$fonction_array = array();
if($totalRows_participant>0){  foreach($row_participant as $row_participant){
  $participant_array[$row_participant["fonction"]] = $row_participant["titre"].". ".$row_participant['nom']." ".$row_participant['prenom'];
  $fonction_array[$row_participant["fonction"]] = $row_participant['description'];
} }



$query_liste_ugl = "SELECT * FROM ".$database_connect_prefix."ugl  order by code_ugl asc";
try{
    $liste_ugl = $pdar_connexion->prepare($query_liste_ugl);
    $liste_ugl->execute();
    $row_liste_ugl = $liste_ugl ->fetchAll();
    $totalRows_liste_ugl = $liste_ugl->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$ugl_array = array();

if($totalRows_liste_ugl>0){foreach($row_liste_ugl as $row_liste_ugl){

  $ugl_array[$row_liste_ugl["id_ugl"]] = $row_liste_ugl["nom_ugl"];

} }





if(file_exists($filename)){

 //On ouvre le modele

 $fp = fopen ($filename, 'r');

 $content = fread($fp, filesize($filename));

 fclose ($fp); 



if(isset($participant_array[$fonction])) $titre_nom=$participant_array[$fonction]; else $titre_nom="NaN"; //$row_participant['titre'].". ".$row_participant['nom']." ".$row_participant['prenom'];

  if(isset($participant_array[$row_actom['donneur_ordre']])) $donneurordre=$participant_array[$row_actom['donneur_ordre']]; else $donneurordre="NaN";

 $lieumission=$row_actom['lieu'];

 $objet=strtr($row_actom['objectif'],array('['=>'«',']'=>'»'));

  $moyen_transport=$row_actom['moyen_transport'];



 // $al = explode("|",$row_actom['lieu']); if(count($al)>0){ foreach($al as $bl)   }

 $lieu=str_replace("|","; ",$row_actom['lieu']);

 //$num="FAVRAT";

 //On remplace les champs automatiques du modèle

$content=str_replace("[numero]",$numero,$content);

$content=str_replace("[fonctiondonneur]",(isset($fonction_array[$row_actom['donneur_ordre']]))?$fonction_array[$row_actom['donneur_ordre']]:$row_actom['donneur_ordre'],$content);

$content=str_replace("[donneur]",$donneurordre,$content);

$content=str_replace("[date]",date('d/m/Y', strtotime($row_actom['debut'])),$content);

$content=str_replace("[titrenom]",$titre_nom,$content);

$content=str_replace("[fonctionparticipant]",(isset($fonction_array[$fonction]))?$fonction_array[$fonction]:$fonction,$content);

$content=str_replace("[lieu]",$lieu,$content);

$content=str_replace("[moyen]",$row_actom['moyen_transport'],$content);

$content=str_replace("[objet]",$objet,$content);

$content=str_replace("[responsable]",((isset($ugl_array[$row_actom['responsable']])?$ugl_array[$row_actom['responsable']]:$row_actom['responsable'])),$content);

$content=str_replace("[datea]",date('d/m/Y', strtotime($row_actom['debut'])),$content);

$content=str_replace("[dater]",date('d/m/Y', strtotime($row_actom['fin'])),$content);









//On crée le fichier généré

/*$newFileHandler = fopen("ordre_mission_".$numero."_".$fonction.".rtf","a");

fwrite($newFileHandler,$content);

fclose($newFileHandler);*/



 

 // $nom_fichier = "mon_document.doc" ; //nom du fichier à compresser

 // $nom_fichier = "ordre_mission_".$numero."_".$fonction.".rtf";



 //$fo = fopen($nom_fichier,'r') ; //on ouvre le fichier

 //$contenu = fread($fo, filesize($nom_fichier)) ; //on enregistre le contenu

 //fclose($fo) ; //on ferme le fichier

 

 //$zip->addfile($contenu, $nom_fichier) ; //on ajoute le fichier

 //$archive = $zip->file() ; //on associe l'archive

 

 

 // code à insérer à la place des 3lignes ( fopen, fwrite, fclose )

 //header('Content-Type: application/x-zip') ; //on détermine les en-tête

 //header("Content-Disposition: inline; filename=ordre_mission_".$numero."_".$fonction.".rtf.zip") ;

 

 //echo $archive ;

 //readfile($archive);

   /* Suppression du document 

   unlink($nom_fichier);*/

//On affiche le document word

header("Content-Type: application/msword" );

header("Content-Disposition: attachment; filename=ordre_mission_".$numero."_".$fonction.".doc");

 header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

 

echo $content;

}

?>