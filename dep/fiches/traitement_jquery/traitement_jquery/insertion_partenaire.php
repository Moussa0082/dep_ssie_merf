<?php 

	require_once '../fonctions/PHP/Fonctions.php';

		$output=0;
		$nom_partenaire=isset($_POST['nom_partenaire'])?$_POST['nom_partenaire']:'';
	    $code_partenaire=isset($_POST['code_partenaire'])?$_POST['code_partenaire']:'';
	    $type_partenaire=isset($_POST['type_partenaire'])?$_POST['type_partenaire']:'';
    	$adresse_partenaire=isset($_POST['adresse_partenaire'])?$_POST['adresse_partenaire']:'';
    	$contact_partenaire=isset($_POST['contact_partenaire'])?$_POST['contact_partenaire']:'';
		$site_web=isset($_POST['site_web'])?$_POST['site_web']:'';
		$email_partenaire=isset($_POST['email_partenaire'])?$_POST['email_partenaire']:'';
		$map_partenaire=isset($_POST['map_partenaire'])?$_POST['map_partenaire']:'';
		$description=isset($_POST['description'])?$_POST['description']:'';
        
		$nom_partenaire= FC_Formater(ucfirst($nom_partenaire));
		$code_partenaire= FC_Formater($code_partenaire);
		$type_partenaire= FC_Formater($type_partenaire);
		$adresse_partenaire = FC_Formater($adresse_partenaire);
		$contact_partenaire = FC_Formater($contact_partenaire);
		$site_web = FC_Formater($site_web);
		$email_partenaire = FC_Formater($email_partenaire);
		$map_partenaire = FC_Formater($map_partenaire);
		$description = FC_Formater($description);
		
        if (!empty($nom_partenaire) && !empty($type_partenaire)) { 


			$requete=PC_Enregistrer_Code('INSERT INTO t_partenaire (nom_partenaire, code_partenaire, type_partenaire, adresse_partenaire, contact_partenaire, site_web, email_partenaire, map_partenaire, description) VALUES (\''.$nom_partenaire.'\',\''.$code_partenaire.'\',\''.$type_partenaire.'\',\''.$adresse_partenaire.'\',\''.$contact_partenaire.'\',\''.$site_web.'\',\''.$email_partenaire.'\',\''.$map_partenaire.'\',\''.$description.'\')');

			if ($requete) {$output=1;}
           else{$output=0;}}
	echo $output;
 ?>