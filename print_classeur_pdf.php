<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
//session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"]) || !isset($_GET['classeur'])) {
  echo "<h1>Une erreur s'est produite !</h1>";
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";

if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else {$annee=date("Y");}

$classeur=intval($_GET['classeur']);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_classeur = "SELECT * FROM ".$database_connect_prefix."classeur WHERE id_classeur=$classeur";
$liste_classeur = mysql_query($query_liste_classeur, $pdar_connexion) or die(mysql_error());
$row_liste_classeur = mysql_fetch_assoc($liste_classeur);
$totalRows_liste_classeur = mysql_num_rows($liste_classeur);

if($totalRows_liste_classeur>0){                            
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_config = "SELECT `table` FROM ".$database_connect_prefix."fiche_config WHERE `table` LIKE 'fiche_$classeur%'";
$liste_config = mysql_query($query_liste_config, $pdar_connexion) or die(mysql_error());
$row_liste_config = mysql_fetch_assoc($liste_config);
$totalRows_liste_config = mysql_num_rows($liste_config);

$nbr_array=array();
if($totalRows_liste_config>0) {
do { $nbr_array[] = $row_liste_config["table"];
   } while ($row_liste_config = mysql_fetch_assoc($liste_config));
 }
}
?>

<?php  if(isset($nbr_array) && count($nbr_array)>0) { ?>
<div class="well well-sm"><strong><?php echo $row_liste_classeur["libelle"]; ?></strong><br /><?php echo $row_liste_classeur["note"]; ?></div>
      <?php  foreach($nbr_array as $f) { ?>
        <?php
        $feuille = $database_connect_prefix.$f;
        include "print_fiches_dynamiques_content_pdf.php"; ?>
      <br>
      <?php }  } else echo "<div class='well well-sm'><h1 align='center'>Classeur introuvable !</h1></div>"; ?>