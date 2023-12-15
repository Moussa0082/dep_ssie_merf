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

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $idc=($_GET["id"]); $id_s=isset($_GET["id_s"])?$_GET["id_s"]:'';
   //echo '<option value="">'.$id_s.'</option>';

  $interdit_array = array("classeur","LKEY","projet","structure","id_personnel","date_enregistrement","modifier_le","modifier_par","etat");

  //select all classeur
//$query_liste_cp = "SHOW tables";
  $query_liste_cp = "SELECT * FROM ".$database_connect_prefix."t_feuille WHERE `Code_Classeur`='$idc'";

try{
    $liste_cp = $pdar_connexion->prepare($query_liste_cp);
    $liste_cp->execute();
    $row_liste_cp = $liste_cp ->fetchAll();
    $totalRows_liste_cp = $liste_cp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$table_array0=array();
if($totalRows_liste_cp>0) {
foreach($row_liste_cp as $row_liste_cp){ $table_array0[]=$row_liste_cp["Table_Feuille"]; } }

if(count($table_array0)>0){   echo '<option></option>';
foreach($table_array0 as $id){

  $query_entete = "SELECT t_feuille_ligne.*, t_feuille.Libelle_Feuille, t_feuille.Table_Feuille FROM ".$database_connect_prefix."t_feuille_ligne, t_feuille WHERE t_feuille_ligne.Code_Feuille=t_feuille.Code_Feuille and `Table_Feuille`='$id'";
try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetchAll();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $libelle_array = array(); $nomcol="";
  if($totalRows_entete>0){   $nomcol=$row_entete["Libelle_Feuille"];//$libelle=explode("|",$row_entete["Libelle_Ligne"]);
  foreach($row_entete as $row_entete)
  {
  $nomcol=$row_entete["Libelle_Feuille"];
   // $lib=explode("=",$llib1);
    $libelle_array[$row_entete["Nom_Collone"]]=$row_entete["Libelle_Ligne"];
  }
//print_r($libelle_array);
  $query_liste_feuille = "DESCRIBE ".$database_connect_prefix."$id ";
try{
    $liste_feuille = $pdar_connexion->prepare($query_liste_feuille);
    $liste_feuille->execute();
    $row_liste_feuille = $liste_feuille ->fetchAll();
    $totalRows_liste_feuille = $liste_feuille->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  if($totalRows_liste_feuille>0){ ?>

    <?php echo $totalRows_entete."<optgroup label='".$nomcol."'>"; foreach($row_liste_feuille as $row_liste_feuille){ if(!in_array($row_liste_feuille['Field'],$interdit_array)){  ?>
    <option value="<?php echo $row_liste_feuille['Field']."/".$id; ?>" <?php $tem=$row_liste_feuille['Field']."/".$id; if(isset($id_s) && !empty($id_s)) $id_s1=explode(";",$id_s); else $id_s1=array(); if (in_array($tem,$id_s1)) {echo "SELECTED";} ?>><?php
//if($row_liste_feuille['Field']!="annee")
echo "<small>".((isset($libelle_array[$row_liste_feuille['Field']])/* && isset($libelle_array[$id])*/)?($libelle_array[$row_liste_feuille['Field']])/*." => ".utf8_encode($libelle_array[$id])*/:$row_liste_feuille['Field'])."</small>"/*." => ".$nomcol*/;
/*else
echo (isset($libelle_array[$id]))?utf8_encode("Année")." => ".utf8_encode($libelle_array[$id]):utf8_encode("Année")." => ".$id;*/
 ?></option>
  <?php } } echo "</optgroup>";
  } }
}      }

}

?>