<?php 
	require_once '../fonctions/PHP/Fonctions.php';

		$output=0;
		$nom_type_partenaire=isset($_POST['nom_type_partenaire'])?$_POST['nom_type_partenaire']:'';
		$desc_type_partenaire=isset($_POST['desc_type_partenaire'])?$_POST['desc_type_partenaire']:'';
        
		$nom_type_partenaire= FC_Formater(ucfirst($nom_type_partenaire));
		$desc_type_partenaire = FC_Formater($desc_type_partenaire);
		
        if (!empty($nom_type_partenaire) && !empty($desc_type_partenaire)) { 


			$requete=PC_Enregistrer_Code('INSERT INTO t_type_partenaire (nom_type_partenaire, description) VALUES (\''.$nom_type_partenaire.'\',\''.$desc_type_partenaire.'\')');

			if ($requete) {$output=1;}
           else{$output=0;}}
	echo $output;
 ?>