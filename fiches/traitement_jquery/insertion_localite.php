<?php  
	require_once '../api/Fonctions.php';

		$output=0;
		$periode_loc=isset($_POST['periode_loc'])?$_POST['periode_loc']:'';
	    $commune_loc=isset($_POST['commune_loc'])?$_POST['commune_loc']:'';
	    $valeur_periode_loc=isset($_POST['valeur_periode_loc'])?$_POST['valeur_periode_loc']:'';
    	$date_collecte_loc=isset($_POST['date_collecte_loc'])?$_POST['date_collecte_loc']:'';
	    $observation_loc=isset($_POST['observation_loc'])?$_POST['observation_loc']:'';
	    $id_personnel_loc='admin';
        $date_enregistrement = date('Y-m-d');
	
		$observation_loc= FC_Formater($observation_loc);
		$id_personnel_loc= FC_Formater($id_personnel_loc);
		
        if (!empty($periode_loc) && !empty($commune_loc)  && !empty($date_collecte_loc)) { 

			$requete=PC_Enregistrer_Code('INSERT INTO t_resultat_localite (periode, commune, valeur_periode, date_collecte, observation, id_personnel, date_enregistrement ) VALUES (\''.$periode_loc.'\',\''.$commune_loc .'\',\''.$valeur_periode_loc.'\',\''.$date_collecte_loc.'\',\''.$observation_loc .'\',\''.$id_personnel_loc.'\',\''.$date_enregistrement.'\')');

			if ($requete) {$output=1;}
           else{$output=0;}}
	echo $output;
 ?>