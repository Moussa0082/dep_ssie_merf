<?php
//var_dump($_POST);

if(isset($_POST)){extract($_POST);
require_once '../api/Fonctions.php';
foreach (FC_Rechercher_Code("SELECT * FROM t_feuille WHERE Code_Feuille=".$Code_Feuille) as $row4) 
{PC_Enregistrer_Code("UPDATE t_feuille_ligne SET Formulaire=0 WHERE (Code_Feuille=".$Code_Feuille.")");
 
 if(isset($Select_Form) AND !empty($Select_Form))
 	 {$ind=1;
 	for ($i=1; $i<=count($Formulaires); $i++)
 	{
 	 
 	 if(isset($Select_Form[$i]) AND !empty($Select_Form[$i]))
 	 {
 	 	for ($j=0; $j<count($Select_Form[$i]); $j++)
 		{PC_Enregistrer_Code("UPDATE t_feuille_ligne SET Formulaire=".($ind)." WHERE (Code_Feuille=".$Code_Feuille." AND Code_Feuille_Ligne=".$Select_Form[$i][$j].")");}
 	$ind++;
 		
	}

 }}



}
}
 ?>