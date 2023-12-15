<?php
if($_POST){
require_once '../fonctions/php/Fonctions.php';	
extract($_POST);
$ind=0;
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE Code_Feuille='.$select_feuille) as $row4){
echo '<section class="row">
<section class="col-lg-1"></section>

<section class="col-lg-2" style="font-size: 16px; cursor: pointer;">
  <input type="text" class="form-control champ_input" readonly name="champ_input[]" id="champ_input[]" value="'.$row4["Nom_Ligne"].'">
</section>

<section class="col-lg-2" style="font-size: 16px; cursor: pointer;">
  <select  name="class_select_champ[]" class="form-control class_select_champ" id="class_select_champ[]" onchange="Select_Champ_Event('.$ind.')">
    <option value=""></option>
    <option value="group_by" class="class_group_by" id="class_group_by_'.$ind.'">Regrouper par</option>
    <option value="valeur" class="class_colonne_valeur" id="class_colonne_valeur_'.$ind.'">Colonne de valeur</option>
  </select>
</section>

</section>';
$ind++;
}
}
?>