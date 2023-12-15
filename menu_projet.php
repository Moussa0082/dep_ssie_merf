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
header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]); $id_s=explode("|",$_GET["id_s"]); $i = 1;
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_cercle = "SELECT * FROM ".$database_connect_prefix."projet"; 
  $liste_cercle  = mysql_query($query_liste_cercle , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_cercle  = mysql_fetch_assoc($liste_cercle);
  $totalRows_liste_cercle  = mysql_num_rows($liste_cercle);
  if($totalRows_liste_cercle>0)
  { ?>
      <table width="100%">
      <tr>
    <?php do { ?>
      <td><label title="<?php echo $row_liste_cercle['intitule_projet']; ?>" for="projet_<?php echo $i; ?>" class="control-label"><?php echo $row_liste_cercle['sigle_projet']; ?></label>
      <input title="<?php echo $row_liste_cercle['intitule_projet']; ?>" name='projet[]' id='projet_<?php echo $i; ?>' type="checkbox" <?php if(isset($_GET['id'])) { if(in_array($row_liste_cercle['code_projet'], $id_s, TRUE)) echo "checked"; } ?> size="5" value="<?php echo $row_liste_cercle['code_projet']; ?>"/></td>
  <?php $i++; }while($row_liste_cercle  = mysql_fetch_assoc($liste_cercle)); }  ?>
      </tr>
      </table>
<?php } ?>