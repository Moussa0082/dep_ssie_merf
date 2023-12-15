<?php
if($_GET){
  extract($_GET);
require_once '../api/Fonctions.php';
  foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE Code_Feuille='.$feuille) as $row4) { ?>
<div class="row">
<div class="col-sm-1 col-md-1 mb-1"><label>Nom Feuille<font style="color: red" >*</font></label></div>
<div class="col-sm-5 col-md-5 mb-5">
  <input type="text" required  maxlength="30"  class="form-control" placeholder="Nom feuille" name="Nom_Feuille" value="<?= $row4['Nom_Feuille'] ?>" id="Nom_Feuille">
</div>
<input type="hidden" name="Code_Feuille" value="<?= $feuille ?>">
<div class="col-sm-1 col-md-1 mb-1"><label> Libelle feuille<font style="color: red" >*</font></label></div>

<div class="col-sm-4 col-md-4 mb-4"><textarea required class="form-control" placeholder="Libelle" name="Libelle_Feuille" id="Libelle_Feuille"><?= $row4['Libelle_Feuille'] ?></textarea></div><input type="hidden" name="Code_Classeur" value="<?= base64_decode($classeur) ?>"><div class="col-sm-1 col-md-1 mb-1"></div></div><br><div class="row"><div class="col-sm-1 col-md-1 mb-1"><label>Nb. ligne (impr.)</label></div><div class="col-sm-2 col-md-2 mb-2"><input type="number" min="1" step="1" value="<?= $row4['Nb_Ligne_Impr'] ?>" class="form-control" placeholder="Nombre de ligne à imprimer" name="Nb_Ligne_Impr" id="Nb_Ligne_Impr"></div><div class="col-sm-1 col-md-1 mb-1"><label> Icone </label></div><div class="col-sm-2 col-md-2 mb-2"><input type="file" accept="image/*" class="form-control" style="height: 35px; width: 100%; border: none;" name="Icone" id="Icone"></div><div class="col-sm-1 col-md-1 mb-1"><label> Note</label></div><div class="col-sm-4 col-md-4 mb-4"><textarea  class="form-control" placeholder="Note" name="Note" id="Note"><?= $row4['Note'] ?></textarea></div><div class="col-sm-1 col-md-1 mb-1"></div></div><br>

    <div class="col-sm-12">
      <div class="form-group">
        <label for="">Déplacer feuille </label>
        <select name="edit_code_classeur" class="form-control" id="" value="<?= $row4['Code_Classeur'] ?>"  >
          <option value="">-</option>
          <?php
          $Id_Projet=(isset($_SESSION['clp_projet']) AND !empty($_SESSION['clp_projet']))?$_SESSION['clp_projet']:"NULL";
          foreach (FC_Rechercher_Code("SELECT * FROM t_classeur WHERE (Id_Projet = '".$Id_Projet."') ORDER BY Code_Classeur DESC") as $row3) : ?>
            <option value="<?=  $row3['Code_Classeur']  ?>"  <?php if ($row3['Code_Classeur'] == $row4['Code_Classeur']){echo 'selected="true"';}?>" > <?=  $row3['Code_Classeur']  ?> <?php  echo $row3['Libelle_Classeur'] ?></option>
          <?php  endforeach; ?>
        </select>
      </div>
    </div>

    <?php
    $Compte=0;

     echo '';
}
?>

<?php  } ?>