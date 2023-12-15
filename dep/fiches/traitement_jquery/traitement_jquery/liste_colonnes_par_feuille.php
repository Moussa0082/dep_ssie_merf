<?php
if($_POST){
require_once '../fonctions/php/Fonctions.php';	
extract($_POST);
$ind=0;
echo '<option value=""></option>';
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE Code_Feuille='.$select_feuille) as $row4){
echo '<option value="'.$row4["Nom_Ligne"].'" class="option_champ_'.str_replace(" ", "", $row4["Nom_Ligne"]).'" id="">'.$row4["Nom_Ligne"].'</option>';
$ind++;
}
}
?>