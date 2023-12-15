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

$interdit_array = array("classeur","LKEY","projet","structure","id_personnel","date_enregistrement","modifier_le","modifier_par","etat");

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]); $id_s=isset($_GET["id_s"])?$_GET["id_s"]:'';
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


if(count($table_array0)>0){ echo '<option value="">Selectionnez</option>'; foreach($table_array0 as $tb){

mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$tb'";
  $entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error());
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);
  $entete_array = array(); $choix_array = $table_feuille = $table_feuille_col = array(); $libelle = array();
  if($totalRows_entete>0){  $entete_array=explode("|",$row_entete["show"]); $libelle=explode("|",$row_entete["libelle"]);       }

$libelle_array=array(); foreach ($libelle as $k=>$v){ $a=explode("=",$v); $libelle_array[$a[0]]=(isset($a[1]))?$a[1]:"ND"; }

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_feuille = "DESCRIBE ".$database_connect_prefix.$tb;
  $liste_feuille  = mysql_query($query_liste_feuille , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_feuille  = mysql_fetch_assoc($liste_feuille);
  $totalRows_liste_feuille  = mysql_num_rows($liste_feuille);

  if($totalRows_liste_feuille>0)
  { ?>

    <?php do { if(!in_array($row_liste_feuille['Field'],$interdit_array)){ ?>
 <option value="<?php echo $row_liste_feuille['Field']."/".$tb; ?>" <?php if ($row_liste_feuille['Field']."/".$tb==$id_s) {echo "SELECTED";} ?>>
 <?php
if($row_liste_feuille['Field']!="annee")
echo (isset($libelle_array[$row_liste_feuille['Field']]) && isset($libelle_array[$tb]))?utf8_encode($libelle_array[$row_liste_feuille['Field']])." => ".utf8_encode($libelle_array[$tb]):$row_liste_feuille['Field']." => ".$tb;
else
echo (isset($libelle_array[$tb]))?utf8_encode("Année")." => ".utf8_encode($libelle_array[$tb]):utf8_encode("Année")." => ".$tb;

?></option>
  <?php } }while($row_liste_feuille  = mysql_fetch_assoc($liste_feuille));
  }

}  }

}

?>