<?php
session_start();

include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";

// Assurez-vous d'avoir une connexion à la base de données
$date=date("Y-m-d");
 $personnel=$_SESSION['clp_id'];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['decaissementId']) && isset($_POST['statut'])) {
    $decaissementId = $_POST['decaissementId'];
    $statut = $_POST['statut'];
    $montant = $_POST['montant'];

    // Vous devrez peut-être valider et nettoyer les données avant de les utiliser dans la requête

    // Exécutez la requête d'insertion
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."decaissement_activite (annee_act, id_activite, source_financement, commune,  date_collecte, statut, cout_realise, numero_facture, projet, date_enregistrement, id_personnel) VALUES (%s, %s, %s, %s, %s, %s, '$montant', %s, %s, '$date', '$personnel')");

    // Utilisez la connexion à la base de données pour exécuter la requête
    // $result = mysqli_query($votre_connexion, $insertSQL);
    
    // Gérez le résultat selon vos besoins
    if ($result) {
        echo "Insertion réussie.";
    } else {
        echo "Erreur lors de l'insertion : " . mysqli_error($votre_connexion);
    }
} else {
    echo "Requête non valide.";
}
?>


<!-- popup_content.php -->
<div id="decaissementPopup" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Contenu du pop-up -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modifier le statut du décaissement</h4>
            </div>
            <div class="modal-body">
              
            <form id="decaissementForm">
    <div class="form-group">
        <label for="montant" class="col-md-3 control-label">Montant</label>
        <div class="col-md-9">
            <input type="text" class="form-control" id="montant" name="montant" readonly>
        </div>
    </div>

    <div class="form-group">
        <label>Statut</label>
        <div>
            <label>
                <input type="radio" class="popup-trigger" name="statut" value="0" > Engagé
            </label>
            <label>
                <input type="radio" class="popup-trigger" name="statut" value="1" > Ordonnancé
            </label>
            <label>
                <input type="radio" class="popup-trigger" name="statut" value="2" > Réalisé
            </label>
        </div>
    </div>

    <input type="hidden" id="decaissementId" name="decaissementId" value="">
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" onclick="updateDecaissementStatus()">Enregistrer</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
    </div>
</form>
            </div>
        </div>
    </div>
</div>

<script>
    $('.popup-trigger').hover(function() {
  $(this).css('cursor', 'pointer');
}, function() {
  $(this).css('cursor', 'auto');
});


        

// Fonction pour mettre à jour le statut du décaissement
function updateDecaissementStatus() {
    var decaissementId = $('#decaissementId').val();
    var statut = $('[name="statut"]:checked').val();
     // Mettre à jour le statut du bouton radio
     $('.statut-radio[value="' + statut + '"]').prop('checked', true);
    // var selectedStatut = $('input[name="statut"]:checked').val();
    // if(selectedStatut==0){
    //     $('[data-statut]').hide();
    // }
   
    // Envoyer ces données au serveur (vous devrez créer une page PHP pour gérer cela)
    $.ajax({
        type: 'POST',
        url: 'suivi_decaissement_ptba.php', // Page PHP pour mettre à jour le statut du décaissement
        data: {
            decaissementId: decaissementId,
            statut: statut
        },
        success: function(response) {
            // Gérer la réponse du serveur
            alert('Statut du décaissement mis à jour avec succès.');
            $('#decaissementPopup').modal('hide'); // Fermer le pop-up après la mise à jour
        },
        error: function() {
            alert('Une erreur s\'est produite lors de la mise à jour du statut du décaissement.');
        }
    });
}

var decaissementId = $(this).data('id');
            var statut = $(this).data('statut');
            var montant = $(this).data('montant');
            var annee_act = $(this).data('annee_act');
            var id_activite = $(this).data('id_activite');
            var source_financement = $(this).data('source_financement');
            var commune = $(this).data('commune');
            var date_collecte = $(this).data('date_collecte');
            var numero_facture = $(this).data('numero_facture');
            var projet = $(this).data('projet');

            // Affiche les données dans la console pour déboguer
    console.log("Decaissement ID:", decaissementId);
    console.log("Statut:", statut);
    console.log("Montant:", montant);
    console.log("Année act :", annee_act);
    console.log("Id_activité :", id_activite);
    console.log("source_financement :", source_financement);
    console.log("commune :", commune);
    console.log("date_collecte :", date_collecte);
    console.log("numero_facture :", numero_facture);
    console.log("projet :", projet);

  

// Événement de clic pour le bouton Enregistrer dans le pop-up
$('#saveButton').click(function() {
    updateDecaissementStatus();
});
</script>

<!-- method="POST" action="popup_content.php" -->