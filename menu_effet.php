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

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]); $id_os=($_GET["id_os"]); $id_s=($_GET["id_s"]);
  $query_liste_effet = "SELECT * FROM resultat where composante='$id' ";
  $query_liste_effet .= (!empty($id_os))?" and composante='$id_os' ":'';
  $query_liste_effet .= " group by id_resultat ";  
  try{
    $liste_effet = $pdar_connexion->prepare($query_liste_effet);
    $liste_effet->execute();
    $row_liste_effet = $liste_effet ->fetchAll();
    $totalRows_liste_effet = $liste_effet->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


  if($totalRows_liste_effet>0)
  { ?>
    <option value="">Selectionnez</option>
	<?php foreach($row_liste_effet as $row_liste_effet){ ?>

    <option value="<?php echo $row_liste_effet['id_resultat']; ?>" <?php if ($row_liste_effet['id_resultat']==$id_s) {echo "SELECTED";} ?>><?php echo $row_liste_effet['intitule_resultat']; ?></option>
  <?php }  }
}

?>