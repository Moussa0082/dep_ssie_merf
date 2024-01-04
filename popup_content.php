<?php
session_start();

include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";

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
    <input type="hidden" id="annee_act" name="annee_act" value="">
    <input type="hidden" id="id_activite" name="id_activite" value="">
    <input type="hidden" id="commune" name="commune" value="">
    <input type="hidden" id="source_financement" name="source_financement" value="">
    <input type="hidden" id="date_collecte" name="date_collecte" value="">
    <input type="hidden" id="numero_facture" name="numero_facture" value="">
    <input type="hidden" id="projet" name="projet" value="">
    
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
    $('.statut-radio[value="' + statut + '"]').prop('checked', true);
    var commune = $('#commune').val();
    var montant = $('#montant').val();
    var projet = $('#projet').val();
    var annee_act = $('#annee_act').val();
    var id_activite = $('#id_activite').val();
    var source_financement = $('#source_financement').val();
    var date_collecte = $('#date_collecte').val();
    var numero_facture = $('#numero_facture').val();
    
    
    console.log("showDecaissementPopup called with id on  widget:", decaissementId, "statut:", statut, "montant:", montant, "annee_act:", annee_act, "id_activité:", id_activite, "source_financement:", source_financement, "commune : ", commune, "date_collecte: ", date_collecte, "numero_facture:", numero_facture, "projet:", projet );
    // Envoyer ces données au serveur (vous devrez créer une page PHP pour gérer cela)
    $.ajax({
        type: 'POST',
        url: 'suivi_decaissement_ptba.php', // Page PHP pour mettre à jour le statut du décaissement
        data: {
            decaissementId: decaissementId,
           montant:montant,
            statut: statut,
            annee_act : annee_act,
            id_activite : id_activite,
            source_financement : source_financement,
            commune : commune,
            date_collecte : date_collecte,
            numero_facture : numero_facture,
            projet : projet
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

 





    function insertDecaissementData() {
        var decaissementId = $('#decaissementId').val();
    var statut = $('[name="statut"]:checked').val();
    $('.statut-radio[value="' + statut + '"]').prop('checked', true);
    var commune = $('#commune').val();
    var montant = $('#montant').val();
    var projet = $('#projet').val();
    var annee_act = $('#annee_act').val();
    var id_activite = $('#id_activite').val();
    var source_financement = $('#source_financement').val();
    var date_collecte = $('#date_collecte').val();
    var numero_facture = $('#numero_facture').val();

    $.ajax({
        type: 'POST',
        url: 'popup_content.php', // Assurez-vous d'avoir une page pour l'insertion
        data: {
            decaissementId: decaissementId,
            statut: statut,
            annee_act : annee_act,
            id_activite : id_activite,
            source_financement : source_financement,
            commune : commune,
            date_collecte : date_collecte,
            numero_facture : numero_facture,
            projet : projet
        },
        success: function(response) {
            alert('Données du décaissement insérées avec succès.');
            $('#decaissementPopup').modal('hide');
        },
        error: function() {
            alert('Une erreur s\'est produite lors de l\'insertion des données du décaissement.');
        }
    });
}

  

// Événement de clic pour le bouton Enregistrer dans le pop-up
$('#saveButton').click(function() {
    updateDecaissementStatus();
    // insertDecaissementData();
});
</script>

<!-- method="POST" action="popup_content.php" -->