<?php
if(isset($_POST)){extract($_POST);
require_once '../fonctions/php/Fonctions.php';

PC_Enregistrer_Code("UPDATE t_classeur SET Libelle_Classeur='".addslashes($libelle_classeur)."', Note_Classeur='".addslashes($note_classeur)."', Couleur_Classeur='".$couleur."' WHERE Code_Classeur=".$Code_Classeur);

}
?>