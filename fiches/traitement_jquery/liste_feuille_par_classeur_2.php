<?php
if($_POST){
require_once '../api/Fonctions.php';	
extract($_POST);
echo '<option value=""></option>';
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE (Code_Classeur='.$select_classeur2.')') as $row4){
	echo '<option value="'.$row4['Code_Feuille'].'">'.$row4['Libelle_Feuille'].'</option>';
}
}
?>