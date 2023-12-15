<?php 
	require_once '../fonctions/PHP/Fonctions.php';
		$output=0;
		$code_projet=isset($_POST['code_projet'])?$_POST['code_projet']:'';
		$sigle_projet=isset($_POST['sigle_projet'])?$_POST['sigle_projet']:'';		
	    $intitule=isset($_POST['intitule'])?$_POST['intitule']:'';
    	$duree=isset($_POST['duree'])?$_POST['duree']:'';
    	$date_signature=isset($_POST['date_signature'])?$_POST['date_signature']:'';
		$id_partenaire_financier=isset($_POST['id_partenaire_financier'])?$_POST['id_partenaire_financier']:'';
        $id_partenaire_execution=isset($_POST['id_partenaire_execution'])?$_POST['id_partenaire_execution']:'';
        $domaine=isset($_POST['domaine'])?$_POST['domaine']:'';
		$zone=isset($_POST['zone'])?$_POST['zone']:'';		
	    $nature=isset($_POST['nature'])?$_POST['nature']:'';
    	$date_demarrage=isset($_POST['date_demarrage'])?$_POST['date_demarrage']:'';

		$code_projet= FC_Formater(ucfirst($code_projet));
		$sigle_projet= FC_Formater($sigle_projet);
		$intitule= FC_Formater($intitule);
		$id_partenaire_financier = FC_Formater($id_partenaire_financier);
		$id_partenaire_execution = FC_Formater($id_partenaire_execution);
		$domaine= FC_Formater(ucfirst($domaine));
		$zone= FC_Formater($zone);
		$nature= FC_Formater($nature);
		
		$statut_projet ='Nouveau';
		
		
        if (!empty($code_projet) && !empty($sigle_projet) && !empty($intitule) && !empty($duree) && !empty($date_signature)&& !empty($id_partenaire_financier)&& !empty($id_partenaire_execution) && !empty($domaine) && !empty($zone) && !empty($nature)&& !empty($date_demarrage)) { 


			$requete=PC_Enregistrer_Code('INSERT INTO t_projet (code_projet, sigle_projet, intitule_projet, duree, date_signature, statut_projet, partenaire_financier, partenaires_execution, domaine, zone, nature, date_demarrage) VALUES (\''.$code_projet.'\', \''.$sigle_projet.'\', \''.$intitule.'\', '.$duree.', \''.$date_signature.'\', \''.$statut_projet.'\', \''.$id_partenaire_financier.'\', \''.$id_partenaire_execution.'\', \''.$domaine.'\', \''.$zone.'\', \''.$nature.'\', \''.$date_demarrage.'\')'); 

			if ($requete) {$output=1;}
           else{$output=0;}}
	echo $output;
 ?>