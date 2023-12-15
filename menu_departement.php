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
  $query_liste_cercle = "SELECT * FROM ".$database_connect_prefix."departement where region='$id' ";
  $liste_cercle  = mysql_query($query_liste_cercle , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_cercle  = mysql_fetch_assoc($liste_cercle);
  $totalRows_liste_cercle  = mysql_num_rows($liste_cercle);
  if($totalRows_liste_cercle>0)
  { ?>
    <option value="">Selectionnez</option>
    <?php do { ?>
    <option value="<?php echo $row_liste_cercle['code_departement']; ?>" <?php if ($row_liste_cercle['code_departement']==$id_s) {echo "SELECTED";} ?>><?php echo utf8_encode($row_liste_cercle['nom_departement']); ?></option>
  <?php }while($row_liste_cercle  = mysql_fetch_assoc($liste_cercle));
  }
}

?>