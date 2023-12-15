<?php
if($_GET){
  extract($_GET);
require_once '../api/Fonctions.php';
  foreach (FC_Rechercher_Code('SELECT * FROM t_classeur WHERE Code_Classeur='.$classeur) as $row4)
  {
?>
<div class="modal-body">
   <form method="POST" action="traitement_jquery/modifier_classeur.php" id="form_classeur">
    <div class="row">
      <input type="hidden" name="Code_Classeur">
      <div class="col-sm-4 col-md-4 mb-3"><label >Libellé</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
      	<input type="hidden" name="Code_Classeur" value="<?php echo $row4['Code_Classeur'] ?>">
        <textarea class="form-control" placeholder="Libellé" name="libelle_classeur" id="libelle_classeur"><?php echo $row4['Libelle_Classeur'] ?></textarea>
      </div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Note</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Note" name="note_classeur" id="note_classeur"><?php echo $row4['Note_Classeur'] ?></textarea></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Couleur</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="color" value="<?php echo $row4['Couleur_Classeur'] ?>" class="form-control" placeholder="Couleur" name="couleur" id="couleur"> </div>
    </div><br>
    <div class="row">
     <div class="col-sm-4 col-md-4 mb-3"></div>
      <div class="col-sm-8 col-md-8 mb-3">
      <br><button style="width: 150px" class="btn btn-success" id="submit" type="submit">Modifier</button></div>
    </div><br>
  </form>
</div>
<?php
  }
?>

<?php  } ?>