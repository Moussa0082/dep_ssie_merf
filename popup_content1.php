<?php
session_start();

include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}


include_once $config->sys_folder . "/database/db_connexion.php";



global $id_act;
global $code_act;
global $annee;

if(isset($_GET['id_act'])) { $id_act = $code_act = $_GET['id_act']; }
if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else $annee=date("Y");


if (isset($_POST['envoyer'])) {
    $decaissementId = $_POST['decaissementId'];
    $statut = $_POST['statut'];
    $annee_act = $_POST['annee_act'];
    $id_activite = $_POST['id_activite'];
    $source_financement = $_POST['source_financement'];
    $commune = $_POST['commune'];
    $date_collecte = date("Y-m-d");
    $numero_facture = $_POST['numero_facture'];
    $projet = $_POST["projet"];
    $montant = str_replace(' ', '', $_POST['montant']);
    $date = date("Y-m-d");

    // Vous devrez peut-être valider et nettoyer les données avant de les utiliser dans la requête

    // Exécutez la requête d'insertion avec des requêtes préparées
    $insertSQL = "INSERT INTO ".$database_connect_prefix."decaissement_activite 
                  (annee_act, id_activite, source_financement, commune, date_collecte, statut, cout_realise, numero_facture, projet, date_enregistrement, id_personnel) 
                  VALUES (:annee_act, :id_activite, :source_financement, :commune, :date_collecte, :statut, :montant, :numero_facture, :projet, :date, :personnel)";

    // Utilisez la connexion à la base de données pour exécuter la requête
    try {
        $stmt = $pdar_connexion->prepare($insertSQL);

        // Liaison des paramètres
        $stmt->bindParam(':annee_act', $annee_act, PDO::PARAM_INT);
        $stmt->bindParam(':id_activite', $id_activite, PDO::PARAM_STR);
        $stmt->bindParam(':source_financement', $source_financement, PDO::PARAM_STR);
        $stmt->bindParam(':commune', $commune, PDO::PARAM_STR);
        $stmt->bindParam(':date_collecte', $date_collecte, PDO::PARAM_STR);
        $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);
        $stmt->bindParam(':montant', $montant, PDO::PARAM_INT);
        $stmt->bindParam(':numero_facture', $numero_facture, PDO::PARAM_STR);
        $stmt->bindParam(':projet', $projet, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':personnel', $personnel, PDO::PARAM_STR);

        // Exécution de la requête
        $stmt->execute();

        exit();
    } catch (Exception $e) {
        die(mysql_error_show_message($e));
    }
    // $insertGoTo = $_SERVER['PHP_SELF']."?id_act=$id_act&code_act=$code_act&annee=$annee";
    header("Location: suivi_decaissement_ptba.php");
} 
?>