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
  $id=($_GET["id"]); $id_s=isset($_GET["id_s"])?$_GET["id_s"]:'';
   //echo '<option value="">'.$id_s.'</option>';

  $interdit_array = array("classeur","LKEY","projet","structure","id_personnel","date_enregistrement","modifier_le","modifier_par","etat");

  //select all classeur
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SHOW tables";
$liste_cp = mysql_query($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);

$table_array0=array();
if($totalRows_liste_cp>0) {
do {  if(strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!="" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!=$database_connect_prefix."fiche_config" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche_".$id."_details_")!=""){   $table_array0[]=$row_liste_cp["Tables_in_$database_pdar_connexion"];   }
} while ($row_liste_cp = mysql_fetch_assoc($liste_cp));
$rows = mysql_num_rows($liste_cp);
if($rows > 0) {
mysql_data_seek($liste_cp, 0);
$row_liste_cp = mysql_fetch_assoc($liste_cp);
}}


if(count($table_array0)>0){   echo '<option value="">Selectionnez</option>';
foreach($table_array0 as $id){

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$id'";
  $entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);

  if($totalRows_entete>0){ $libelle=explode("|",$row_entete["libelle"]); }
  foreach($libelle as $llib1)
  {
    $lib=explode("=",$llib1);
    $libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";
  }

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_feuille = "DESCRIBE ".$database_connect_prefix."$id ";
  $liste_feuille  = mysql_query($query_liste_feuille , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_feuille  = mysql_fetch_assoc($liste_feuille);    ;
  $totalRows_liste_feuille  = mysql_num_rows($liste_feuille);
  if($totalRows_liste_feuille>0)
  { ?>

    <?php  do { if(!in_array($row_liste_feuille['Field'],$interdit_array)){  ?>
    <option value="<?php echo $row_liste_feuille['Field']."/".$id; ?>" <?php $tem=$row_liste_feuille['Field']."/".$id; if(isset($id_s) && !empty($id_s)) $id_s1=explode(";",$id_s); else $id_s1=array(); if (in_array($tem,$id_s1)) {echo "SELECTED";} ?>><?php
if($row_liste_feuille['Field']!="annee")
echo (isset($libelle_array[$row_liste_feuille['Field']]) && isset($libelle_array[$id]))?utf8_encode($libelle_array[$row_liste_feuille['Field']])." => ".utf8_encode($libelle_array[$id]):$row_liste_feuille['Field']." => ".$id;
else
echo (isset($libelle_array[$id]))?utf8_encode("Année")." => ".utf8_encode($libelle_array[$id]):utf8_encode("Année")." => ".$id;       
 ?></option>
  <?php }   }while($row_liste_feuille  = mysql_fetch_assoc($liste_feuille));
  }
}      }

}

?>