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

    $query_liste_projet = $db ->prepare('SELECT P.* FROM t_projets P, t_projet_users D WHERE P.id_projet=D.projet_up '.((isset($_SESSION['niveau']) && $_SESSION['niveau']!=0)?'and D.structure_up='.$_SESSION["structure"]:'').'  '.((isset($_SESSION['niveau']) && $_SESSION['niveau']!=0)?'and FIND_IN_SET('.$_SESSION["id"].',D.personnel_up)':'').' and P.id_projet=:id_projet'
/*'SELECT * FROM t_projets P '*/);
    $query_liste_projet->execute(array(':id_projet' => $id));
    $row_liste_projet = $query_liste_projet ->fetch();
    $totalRows_liste_projet = $query_liste_projet->rowCount();

    if($totalRows_liste_projet > 0)
    {
        $insertSQL = $db->prepare('UPDATE t_users SET projet_active=:projet_active WHERE id_user=:id_user');
        $Result1 = $insertSQL->execute(
        array(':projet_active' => $id,':id_user' => $_SESSION['id']
        ));
        if($Result1)
        {
            $_SESSION["projet"] = $row_liste_projet['id_projet'];
            $_SESSION["projet_code"] = ($row_liste_projet["code_projet"]);
            $_SESSION["projet_sigle"] = ($row_liste_projet["sigle_projet"]);
            $_SESSION["projet_nom"] = ($row_liste_projet["intitule_projet"]);
            $_SESSION["projet_agence_lead"] = ($row_liste_projet["agence_lead"]);
            $_SESSION["projet_autres_agence"] = ($row_liste_projet["autres_agences_recipiendaires"]);
            $_SESSION["autres_partenaires_execution"] = ($row_liste_projet["autres_partenaires_execution"]);
            $_SESSION["projet_duree"] = ($row_liste_projet['duree']);
            $_SESSION["projet_date_demarrage"] = $row_liste_projet['date_demarrage'];
            $_SESSION["projet_date_signature"] = $row_liste_projet['date_signature'];
            $_SESSION["projet_actif"] = ("Projet ".$row_liste_projet["code_projet"]." (".$row_liste_projet['sigle_projet'].")");
            $_SESSION["where_p"] = " projet='".$_SESSION['projet']."'";
        }
    }

    $insertGoTo = (isset($page))?$page:"./?";
    $sup = strchr($insertGoTo,'?')?"&":"?";
    if ($Result1 && $totalRows > 0) $insertGoTo .= $sup."update=ok";
    else $insertGoTo .= $sup."update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
}
?>