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
header('Content-Type: text/html; charset=ISO-8859-15');

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]); $id_s=explode("|",$_GET["id_s"]); $i = 1;
  $query_liste_cercle = "SELECT * FROM ".$database_connect_prefix."projet where actif=0"; 
  try{
    $liste_cercle = $pdar_connexion->prepare($query_liste_cercle);
    $liste_cercle->execute();
    $row_liste_cercle = $liste_cercle ->fetchAll();
    $totalRows_liste_cercle = $liste_cercle->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); } 

  if($totalRows_liste_cercle>0)
  { ?>
      <table width="100%">
      <tr>
    <?php foreach($row_liste_cercle as $row_liste_cercle){  ?>
      <td><label title="<?php echo $row_liste_cercle['intitule_projet']; ?>" for="projet_<?php echo $i; ?>" class="control-label"><?php echo $row_liste_cercle['sigle_projet']; ?></label>
      <input title="<?php echo $row_liste_cercle['intitule_projet']; ?>" name='projet[]' id='projet_<?php echo $i; ?>' type="checkbox" <?php if(isset($_GET['id'])) { if(in_array($row_liste_cercle['code_projet'], $id_s, TRUE)) echo "checked"; } ?> size="5" value="<?php echo $row_liste_cercle['code_projet']; ?>"/></td>
  <?php $i++; } }  ?>
      </tr>
      </table>
<?php } ?>