<?php
if($_POST){
require_once '../fonctions/php/Fonctions.php';	
extract($_POST);
PC_Enregistrer_Code("INSERT INTO t_classeur(Libelle_Classeur, Note_Classeur, Couleur_Classeur) VALUES ('".addslashes($libelle_classeur)."', '".addslashes($note_classeur)."', '$couleur')");}
?>