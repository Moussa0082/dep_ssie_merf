<?php
if($_GET){
require_once '../fonctions/php/Fonctions.php';	
extract($_GET);
echo '<option value="">Champ</option>';
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE Code_Feuille='.$Code) as $row4){
	echo '<option value="'.$row4['Code_Feuille_Ligne'].'">'.$row4['Nom_Ligne'].'</option>';
}
}
?>