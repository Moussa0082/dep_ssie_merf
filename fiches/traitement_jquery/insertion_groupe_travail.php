<?php 
	require_once '../api/Fonctions.php';

		$output=0;
		$code_groupes_travail=isset($_POST['code_groupes_travail'])?$_POST['code_groupes_travail']:'';
	    $nom_groupes_travail=isset($_POST['nom_groupes_travail'])?$_POST['nom_groupes_travail']:'';
	    $partenaire_groupe=isset($_POST['partenaire_groupe'])?$_POST['partenaire_groupe']:'';
    	$thematiques=isset($_POST['thematiques'])?$_POST['thematiques']:'';
	    $secretaire_groupe=isset($_POST['secretaire_groupe'])?$_POST['secretaire_groupe']:'';
	    $date_creation_groupe=isset($_POST['date_creation_groupe'])?$_POST['date_creation_groupe']:'';
	    $description_groupe=isset($_POST['description_groupe'])?$_POST['description_groupe']:'';
        
		$code_groupes_travail= FC_Formater($code_groupes_travail);
		$nom_groupes_travail= FC_Formater($nom_groupes_travail);
		$description_groupe= FC_Formater($description_groupe);
		
        if (!empty($code_groupes_travail) && !empty($nom_groupes_travail) && !empty($partenaire_groupe)) { 

			$requete=PC_Enregistrer_Code('INSERT INTO t_groupes_travail (code_groupes_travail, nom_groupes_travail, partenaire, thematiques, secretaire, date_creation, description) VALUES (\''.$code_groupes_travail.'\',\''.$nom_groupes_travail .'\',\''.$partenaire_groupe.'\',\''.$thematiques.'\',\''.$secretaire_groupe .'\',\''.$date_creation_groupe.'\',\''.$description_groupe.'\')');

			if ($requete) {$output=1;}
           else{$output=0;}}
	echo $output;
 ?>