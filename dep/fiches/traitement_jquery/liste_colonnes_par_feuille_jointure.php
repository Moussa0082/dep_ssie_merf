<?php
if($_POST){
require_once '../api/Fonctions.php';	
extract($_POST);
$ind=0;
echo '<optgroup label="Feuille 2">
<option value=""></option>';
foreach (FC_Rechercher_Code('SELECT t_feuille_ligne.*, Table_Feuille FROM `t_feuille_ligne` INNER JOIN `t_feuille` ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE t_feuille_ligne.Code_Feuille='.$feuille_jointure) as $row4){
echo '<option value="'.str_replace("t_", "v_", $row4["Table_Feuille"]).'.'.$row4["Nom_Collone"].'" class="option_champ_'.str_replace(" ", "", $row4["Nom_Ligne"]).'" id="">'.$row4["Nom_Ligne"].'</option>';
$ind++;
}
echo '</optgroup>';
}
?>