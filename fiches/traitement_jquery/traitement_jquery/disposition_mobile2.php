<?php
if(isset($_POST)){extract($_POST);
require_once '../fonctions/php/Fonctions.php';
foreach (FC_Rechercher_Code("SELECT * FROM t_feuille WHERE Code_Feuille=".$Code_Feuille) as $row4) 
{
 for ($i=0; $i<count($nom_Ligne); $i++)
 	{
 		PC_Enregistrer_Code("UPDATE t_feuille_ligne SET Formulaire=".$disposition[$i]." WHERE (Code_Feuille=".$Code_Feuille." AND Code_Feuille_Ligne=".$Code_Feuille_Ligne[$i].")");

 }
}
}
 ?>