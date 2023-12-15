<!--Insertion Projet -->
<div id="new_projets_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div id="reponse"></div>
      <div class="modal-dialog modal_dialog_pers" role="document">
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" class="close" data-dismiss="modal"><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></button>
               <center><h4 class="modal-title">Ajouter un Projet </h4></center>
          </div>
          <div class="modal-body">
   <form method="POST" action="" id="form_projet_news">
    <div class="row">
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
      <div class="col-sm-4 col-md-4 mb-3"><label>Sigle</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Sigle" name="sigle_projet_news" id="sigle_projet_news"></div>
    </div><br>
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
      <div class="col-sm-4 col-md-4 mb-3"><label>Intitulé</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Intitulé" name="intitule_news" id="intitule_news"></textarea></div>
    </div><br>      
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
      <div class="col-sm-4 col-md-4 mb-3"><label>Date Signature</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="date" class="form-control" placeholder="Date Signature" name="date_signature_news" id="date_signature_news"></div>
    </div><br>
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
      <div class="col-sm-4 col-md-4 mb-3"><label>Modalité de financement</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <select class="form-control" id="modalite_financement_news" name="modalite_financement_news">
          <option>Mensuel</option>
          <option>Trimestriel</option>
          <option>Annuel</option>
        </select></div>
    </div><br>
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
    <div class="col-sm-4 col-md-4 mb-3"><label>Type fond fidicuaire</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Type fond fidicuaire" name="type_fond_fidicuaire_news" id="type_fond_fidicuaire_news"></div>
    </div><br>
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
    <div class="col-sm-4 col-md-4 mb-3"><label>Nom fond fidicuaire</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Nom fond" name="nom_fond_fidicuaire_news" id="nom_fond_fidicuaire_news"></div>
    </div><br>
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
    <div class="col-sm-4 col-md-4 mb-3">Statut Projet</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="number" class="form-control" placeholder="Nom fond fidicuaire" name="nom_fond_fidicuaire_news" id="nom_fond_fidicuaire_news"></div>
    </div><br>
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
    <div class="col-sm-4 col-md-4 mb-3"><label>Agence Lead</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Agence Lead" name="agence_lead_news" id="agence_lead_news"></div>
    </div><br>
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
    <div class="col-sm-4 col-md-4 mb-3"><label>Autres agences recipiendaire</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Autres agences recipiendaire" name="agence_recipiendaire_news" id="agence_recipiendaire_news"></div>
    </div><br>
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
      <div class="col-sm-4 col-md-4 mb-3"><label>Partenaire signataire</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <select class="form-control" id="partenaire_signataire_news" name="partenaire_signataire_news">
          <option></option>
            <?php  
         foreach(FC_Rechercher_Code('SELECT * FROM t_partenaire') as $rows) 
          {
           echo'<option value="'.$rows['id_partenaire'].'">'.$rows['nom_partenaire'].'</option>';
          }?>
        </select></div>
    </div><br>
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
      <div class="col-sm-4 col-md-4 mb-3"><label>Autres artenaires d'execution</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <select class="form-control" id="partenaire_execution_news" name="partenaire_execution_news">
          <option></option>
            <?php  
         foreach(FC_Rechercher_Code('SELECT * FROM t_partenaire') as $rows) 
          {
           echo'<option value="'.$rows['id_partenaire'].'">'.$rows['nom_partenaire'].'</option>';
          }?>
        </select></div>
    </div><br>
     <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
     <div class="col-sm-4 col-md-4 mb-3"><label>Fenêtre PBF</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Fenêtre PBF" name="fenetre_pbf_news" id="fenetre_pbf_news"></div>
    </div><br>       
      <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
      <div class="col-sm-4 col-md-4 mb-3"><label>Zone</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <textarea class="form-control" placeholder="Zone" id="zone_news" name="zone_news"></textarea></div>
    </div><br>
      <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
      <div class="col-sm-4 col-md-4 mb-3"><label>Nature</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <textarea class="form-control" placeholder="Nature" id="nature_news" name="nature_news"></textarea></div>
    </div><br>
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
      <div class="col-sm-4 col-md-4 mb-3"><label>Date de demarrage</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
      <input type="date" class="form-control" placeholder="Date de demarrage" id="date_demarrage_news" name="date_demarrage_news"></div>
    </div><br>
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
    <div class="col-sm-4 col-md-4 mb-3"><label>Durée</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="number" class="form-control" placeholder="Durée" name="duree_news" id="duree_news"></div>
    </div><br> 
     <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
      <div class="col-sm-4 col-md-4 mb-3"><label>Description projet</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <textarea class="form-control" placeholder="Description" id="description_news" name="description_news"></textarea></div>
    </div><br>
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
      <div class="col-sm-4 col-md-4 mb-3"><label>Processus consultation</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <textarea class="form-control" placeholder="Processus consultation" id="processus_consultation_news" name="processus_consultation_news"></textarea></div>
    </div><br>
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
    <div class="col-sm-4 col-md-4 mb-3"><label>Pourcentage budget genre</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="number" class="form-control" placeholder="Pourcentage budget genre" name="pourcentage_budget_genre_news" id="pourcentage_budget_genre_news"></div>
    </div><br>
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
      <div class="col-sm-4 col-md-4 mb-3"><label>Description marqueur genre</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <textarea class="form-control" placeholder="Description marqueur genre" id="description_marqueur_genre_news" name="description_marqueur_genre_news"></textarea></div>
    </div><br> 
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
      <div class="col-sm-4 col-md-4 mb-3"><label>Domaine intervention prioritaire</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <textarea class="form-control" placeholder="Domaine intervention prioritaire" id="domaine_intervention_prioritaire_news" name="domaine_intervention_prioritaire_news"></textarea></div>
    </div><br>
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
      <div class="col-sm-4 col-md-4 mb-3"><label>Resultat undaf</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
      <input type="text" class="form-control" placeholder="Resultat undaf" id="resultat_undaf_news" name="resultat_undaf_news"></div>
    </div><br>
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
      <div class="col-sm-4 col-md-4 mb-3"><label>Objectif odd </label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <textarea class="form-control" placeholder="Objectif odd" id="objectif_odd_news" name="objectif_odd_news"></textarea></div>
    </div><br>
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
      <div class="col-sm-4 col-md-4 mb-3"><label>Etat</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
      <input type="text" class="form-control" placeholder="Etat" id="etat_news" name="etat_news"></div>
    </div><br>
    <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
     <div class="col-sm-4 col-md-4 mb-3"></div>
      <div class="col-sm-8 col-md-8 mb-3">
      <br><button style="width: 150px" <?php echo 'class="btn '.$Boutton_Style.'"'; ?> id="submit" type="submit">Inserer</button></div>
    </div><br>
    </div>
  </form>
</div>
</div>
</div>
</div>
