<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
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

if(isset($_GET["id"]) && intval($_GET["id"])>0)
{
  $id=intval($_GET["id"]); $id_os=($_GET["id_os"]); $id_s=($_GET["id_s"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_prd = "SELECT * FROM produit where effet=$id ";
  $query_liste_prd .= (!empty($id_os))?" and sous_composante='$id_os' ":'';
  $query_liste_prd .= " group by id_produit ";
  $liste_prd  = mysql_query($query_liste_prd , $pdar_connexion) or die(mysql_error());
  $row_liste_prd  = mysql_fetch_assoc($liste_prd);
  $totalRows_liste_prd  = mysql_num_rows($liste_prd);

  if($totalRows_liste_prd>0)
  { ?>
    <option value="">Selectionnez</option>
    <?php do { ?>
    <option value="<?php echo $row_liste_prd['id_produit']; ?>" <?php if ($row_liste_prd['id_produit']==$id_s) {echo "SELECTED";} ?>><?php echo $row_liste_prd['intitule_produit']; ?></option>
  <?php }while($row_liste_prd  = mysql_fetch_assoc($liste_prd));
  }
}

?>