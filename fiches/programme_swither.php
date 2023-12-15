<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
if (!isset ($_SESSION["id"])) {
    header(sprintf("Location: %s", "./login.php"));  exit();
}
include_once 'api/configuration.php';
include_once 'api/essentiel.php';
$config = new Config;

extract($_GET);
if ((isset($id) && $id!='')) {
  $insertSQL = $db->prepare('UPDATE t_users SET programme_active=:programme_active WHERE id_user=:id_user');
      $Result1 = $insertSQL->execute(array(
        ':programme_active' => $id,
        ':id_user' => $_SESSION['id']
      ));

  if($Result1)
  {
    $query_liste_programme = $db ->prepare('SELECT * FROM t_programmes P WHERE P.id_programme=:id_programme');
    $query_liste_programme->execute(array(':id_programme' => $id));
    $row_liste_programme = $query_liste_programme ->fetch();
    $totalRows_liste_programme = $query_liste_programme->rowCount();

    if($totalRows_liste_programme > 0)
    {
      $_SESSION["programme"] = $row_liste_programme['id_programme'];
      $_SESSION["programme_code"] = ($row_liste_programme["sigle_programme"]);
      $_SESSION["programme_sigle"] = ($row_liste_programme["sigle_programme"]);
      $_SESSION["programme_nom"] = ($row_liste_programme["nom_programme"]);
      $_SESSION["programme_vision"] = ($row_liste_programme["vision"]);
      $_SESSION["programme_objectif"] = ($row_liste_programme['objectif']);
      $_SESSION["programme_date_debut"] = $row_liste_programme['date_debut'];
      $_SESSION["programme_date_fin"] = $row_liste_programme['date_fin'];
      $_SESSION["programme_actif"] = ("Programme ".$row_liste_programme["sigle_programme"]." (".date_reg($row_liste_programme['date_debut'],"/")." au ".date_reg($row_liste_programme['date_fin'],"/").")");
      $_SESSION["where"] = " programme='".$_SESSION['programme']."'";

        //reset projet
        $_SESSION["projet"] = null;
        $_SESSION["projet_code"] = null;
        $_SESSION["projet_sigle"] = null;
        $_SESSION["projet_nom"] = null;
        $_SESSION["projet_agence_lead"] = null;
        $_SESSION["projet_autres_agence"] = null;
        $_SESSION["autres_partenaires_execution"] = null;
        $_SESSION["projet_duree"] = null;
        $_SESSION["projet_date_demarrage"] = null;
        $_SESSION["projet_date_signature"] = null;
        $_SESSION["projet_actif"] = null;
        $_SESSION["where_p"] = null;
    }
  }
  $insertGoTo = (isset($page))?$page:"./?";
  $sup = strchr($insertGoTo,'?')?"&":"?";
  if ($Result1 && $totalRows > 0) $insertGoTo .= $sup."update=ok";
  else $insertGoTo .= $sup."update=no";
  header(sprintf("Location: %s", $insertGoTo));  exit();
}
?>