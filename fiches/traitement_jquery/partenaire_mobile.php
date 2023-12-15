<?php
//var_dump($_POST);

if(isset($_POST)){extract($_POST);
require_once '../api/Fonctions.php';
$ind=0; 
 	 
 	 if((isset($Partenaire) AND !empty($Partenaire)))
 	 {
 	 	PC_Enregistrer_Code("DELETE FROM t_feuille_partenaire WHERE Code_Feuille='".$feuille."'");
 	 	for ($i=0; $i<count($Partenaire); $i++)
 		{
 	PC_Enregistrer_Code("INSERT INTO `t_feuille_partenaire`(`Code_Feuille`, `code`) VALUES ('".$feuille."','".$Partenaire[$i]."')");
 	$ind++;}
 	
 		
	}


}
 ?>