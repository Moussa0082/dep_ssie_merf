<?php 
	require_once '../api/Fonctions.php';

		$output=0;
		$sigle_programme=isset($_POST['sigle_programme'])?$_POST['sigle_programme']:'';
		$nom_programme=isset($_POST['nom_programme'])?$_POST['nom_programme']:'';
	    $vision=isset($_POST['vision'])?$_POST['vision']:'';
    	$objectif=isset($_POST['objectif'])?$_POST['objectif']:'';
    	$annee_debut=isset($_POST['annee_debut'])?$_POST['annee_debut']:'';
		$annee_fin=isset($_POST['annee_fin'])?$_POST['annee_fin']:'';
        
		$nom_programme= FC_Formater(ucfirst($nom_programme));
		$sigle_programme= FC_Formater($sigle_programme);
		$vision= FC_Formater($vision);
		$objectif = FC_Formater($objectif);
		$annee_debut = FC_Formater($annee_debut);
		$annee_fin = FC_Formater($annee_fin);
		$statut_programme='En cours';
		
		
        if (!empty($nom_programme) && !empty($sigle_programme) && !empty($vision) && !empty($objectif) && !empty($annee_debut)&& !empty($annee_fin)) { 


			$requete=PC_Enregistrer_Code('INSERT INTO t_programme (nom_programme, sigle_programme, vision, objectif, annee_debut, annee_fin, statut_programme) VALUES (\''.$nom_programme.'\',\''.$sigle_programme .'\',\''.$vision.'\',\''.$objectif.'\',\''.$annee_debut.'\',\''.$annee_fin.'\',\''.$statut_programme.'\')');

			if ($requete) {$output=1;}
           else{$output=0;}}
	echo $output;
 ?>