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
  $interdit_array = array("classeur","LKEY","annee","projet","structure","id_personnel","date_enregistrement","modifier_le","modifier_par","etat");

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
    <option value="">Selectionnez</option>
    <?php $i=0; do { if(!in_array($row_liste_feuille['Field'],$interdit_array)){ if($i>0){ ?>
    <option value="<?php echo $row_liste_feuille['Field']; ?>" <?php if ($row_liste_feuille['Field']==$id_s) {echo "SELECTED";} ?>><?php echo (isset($libelle_array[$row_liste_feuille['Field']]))?utf8_encode($libelle_array[$row_liste_feuille['Field']]):$row_liste_feuille['Field']; ?></option>
  <?php } $i++; } }while($row_liste_feuille  = mysql_fetch_assoc($liste_feuille));
  }
}

?>