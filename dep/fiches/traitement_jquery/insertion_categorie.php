<?php 
	require_once '../api/Fonctions.php';

		$output=0;
		$nom_categorie=isset($_POST['nom_categorie'])?$_POST['nom_categorie']:'';
	    $description_categorie=isset($_POST['description_categorie'])?$_POST['description_categorie']:'';
	
		$nom_categorie= FC_Formater($nom_categorie);
		$description_categorie= FC_Formater($description_categorie);
		
        if (!empty($nom_categorie) && !empty($description_categorie)) { 

			$requete=PC_Enregistrer_Code('INSERT INTO t_categorie_indicateur (nom_categorie_indicateur, description) VALUES (\''.$nom_categorie.'\',\''.$description_categorie.'\')');
			if ($requete) {$output=1;}
           else{$output=0;}}
	echo $output;
 ?>