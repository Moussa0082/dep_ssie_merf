<!-- programme -->
<div id="new_programme_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" class="close" data-dismiss="modal"><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></button>
               <center><h4 class="modal-title">Ajouter un programme </h4></center>
          </div>
          <div class="modal-body">
   <form method="POST" action="" id="new_form_programme">
      <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Sigle programme</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Sigle" name="sigle_programme_new" id="sigle_programme_new"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Nom Programme</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Nom" name="nom_programme_new" id="nom_programme_new"></textarea></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Pays programme</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Pays" name="pays_programme_new" id="pays_programme_new"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Type programme</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Type" name="type_programme_new" id="type_programme_new"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Vision</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Vision" name="vision_new" id="vision_new"></textarea></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Objectif</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Objectif" name="objectif_new" id="objectif_new"></textarea></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Date début</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="date" class="form-control" name="date_debut_new" id="date_debut_new"></div>
    </div><br> 
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Date fin</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="date" class="form-control" name="date_fin_new" id="date_fin_new"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Budget estimatif</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="number" class="form-control" placeholder="Budget estimatif" name="budget_estimatif_programme_new" minlength="100000" step="5000" id="budget_estimatif_programme_new"></div>
    </div><br>
    <div class="row">
     <div class="col-sm-4 col-md-4 mb-3"></div>
      <div class="col-sm-8 col-md-8 mb-3">
      <br><button style="width: 150px" <?php echo 'class="btn '.$Boutton_Style.'"'; ?> id="submit" type="submit">Insérer</button></div>
    </div><br>
  </form>
</div>
</div>
</div>
</div>