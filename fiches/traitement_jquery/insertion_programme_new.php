<?php 
	require_once '../api/Fonctions.php';

		$output=0;
		extract($_POST);
        
		$nom_programme_new= FC_Formater(ucfirst($nom_programme_new));
		$sigle_programme_new= FC_Formater($sigle_programme_new);
		$vision_new= FC_Formater($vision_new);
		$objectif_new = FC_Formater($objectif_new);
		$type_programme_new = FC_Formater($type_programme_new);
		$budget_estimatif_programme_new = $budget_estimatif_programme_new;
		$pays_programme_new = FC_Formater($pays_programme_new);
		$date_debut_new=isset($_POST['date_debut_new'])?$_POST['date_debut_new']:'';
		$date_fin_new=isset($_POST['date_fin_new'])?$_POST['date_fin_new']:'';
		
		
        if (!empty($nom_programme_new) && !empty($sigle_programme_new) && !empty($vision_new) && !empty($objectif_new) && !empty($date_debut_new)&& !empty($date_fin_new)) { 
        	$sql=FC_Rechercher_Code('SELECT MAX(id_programme) AS maxi FROM t_programmes');
			$rep=$sql->fetch();
			$max=(int)$rep['maxi'];
			$code_programme_new=($max+1);
			if ($code_programme_new<10) {
				$code_programme_new='0'.$code_programme_new;
			}

			$requete=PC_Enregistrer_Code('INSERT INTO t_programmes(code_programme, sigle_programme, nom_programme, pays, vision, objectif, date_debut, date_fin, budget_estimatif, type_programme, id_personnel, date_enregistrement) VALUES ('.$code_programme_new.',\''.$sigle_programme_new .'\',\''.$nom_programme_new.'\',\''.$pays_programme_new.'\',\''.$vision_new.'\',\''.$objectif_new.'\',\''.$date_debut_new.'\',\''.$date_fin_new.'\',\''.$budget_estimatif_programme_new.'\',\''.$type_programme_new.'\','.$_SESSION['id'].',NOW())');

			if ($requete) {$output=1;}
           else{$output=0;}}
	echo $output;
 ?>