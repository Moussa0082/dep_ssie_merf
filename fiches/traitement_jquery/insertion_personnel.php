<?php 


	require_once '../api/Fonctions.php';

		$output=0;
		$id_personnel=isset($_POST['id_personnel'])?$_POST['id_personnel']:'';
	    $titre=isset($_POST['titre'])?$_POST['titre']:'';
	    $mot_de_passe=isset($_POST['mot_de_passe'])?$_POST['mot_de_passe']:'';
    	$nom=isset($_POST['nom'])?$_POST['nom']:'';
    	$prenom=isset($_POST['prenom'])?$_POST['prenom']:'';
		$contact=isset($_POST['contact'])?$_POST['contact']:'';
		$email=isset($_POST['email'])?$_POST['email']:'';
		$fonction=isset($_POST['fonction'])?$_POST['fonction']:'';
		$description=isset($_POST['description'])?$_POST['description']:'';
		$niveau=isset($_POST['niveau'])?$_POST['niveau']:'';
		$partenaire=isset($_POST['partenaire'])?$_POST['partenaire']:'';
		$groupe=isset($_POST['groupe'])?$_POST['groupe']:'';
		$programme=isset($_POST['programme'])?$_POST['programme']:'';
		$date_enregistrement=date('Y-m-d h:i:s');
		$avatar='';
        
		$id_personnel= FC_Formater($id_personnel);
		$titre= FC_Formater(ucfirst($titre));
		$mot_de_passe= FC_Formater($mot_de_passe);
		$nom = FC_Formater(strtoupper($nom));
		$prenom = FC_Formater(ucfirst($prenom));
		$contact = FC_Formater($contact);
		$email = FC_Formater(strtolower($email));
		$description = FC_Formater($description);
		
        if (!empty($id_personnel) && !empty($titre) && !empty($mot_de_passe) && !empty($nom) && !empty($prenom)&& !empty($contact) && !empty($fonction) && !empty($description) && !empty($niveau) && !empty($partenaire) && !empty($groupe) && !empty($programme)) { 


	   $nbre= FC_Rechercher_Code('SELECT count(N) as nb FROM t_personnel');
	   $data=$nbre->fetch();
	   $num= $data['nb'] + 1;
	   if ($num < 9) {
	   	$num = '0'.$num;
	   }
	    $Ancien=$_FILES['avatar']['tmp_name'];
		$Nouveau=$_FILES['avatar']['name'];
		$Ext=substr($Nouveau, strripos($Nouveau, '.')+1);
		$chemin='../avatar/';
		$Nouveau='avatar-'.$num.'.'.$Ext;
		$Test=move_uploaded_file($Ancien, $chemin.$Nouveau);
		if($Test == 1){
			$avatar=$Nouveau;
		}else{
			$avatar='';
		}

			$requete=PC_Enregistrer_Code('INSERT INTO t_personnel(id_personnel,titre,pass, nom,prenom,contact,email,fonction,description_fonction,avatar,niveau, partenaire, groupe_travail,programme_active,date_enregistrement) VALUES (\''.$id_personnel.'\',\''.$titre .'\',\''.$mot_de_passe.'\',\''.$nom.'\',\''.$prenom.'\',\''.$contact.'\',\''.$email.'\',\''.$fonction.'\',\''.$description.'\',\''.$avatar.'\',\''.$niveau.'\',\''.$partenaire.'\',\''.$groupe.'\',\''.$programme.'\',\''.$date_enregistrement.'\')');

			if ($requete) {$output=1;}
           else{$output=0;}}
	echo $output;
 ?>