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
  $id=($_GET["id"]); $id_s=($_GET["id_s"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_commune = "SELECT * FROM ".$database_connect_prefix."commune where departement='$id' ";
  $liste_commune  = mysql_query($query_liste_commune , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_commune  = mysql_fetch_assoc($liste_commune);
  $totalRows_liste_commune  = mysql_num_rows($liste_commune);
  if($totalRows_liste_commune>0)
  { ?>
    <option value="">Selectionnez</option>
    <?php do { ?>
    <option value="<?php echo $row_liste_commune['code_commune']; ?>" <?php if ($row_liste_commune['code_commune']==$id_s) {echo "SELECTED";} ?>><?php echo utf8_encode($row_liste_commune['nom_commune']); ?></option>
  <?php }while($row_liste_commune  = mysql_fetch_assoc($liste_commune));
  }
}

?>