<?php  var_dump($_POST);
	require_once '../api/Fonctions.php';

		$output=0;
		$periode_collecte=isset($_POST['periode_collecte'])?$_POST['periode_collecte']:'';
	    $source_donnees=isset($_POST['source_donnees'])?$_POST['source_donnees']:'';
	    $date_validation=isset($_POST['date_validation'])?$_POST['date_validation']:'';
	    $valeur_periode=isset($_POST['valeur_periode'])?$_POST['valeur_periode']:'';
    	$observation_periode=isset($_POST['observation_periode'])?$_POST['observation_periode']:'';
    	$ref_indicateur=isset($_POST['ref_indicateur'])?$_POST['ref_indicateur']:'';
	    $id_personnel="admin";
	    $date_enregistrement=date('Y-m-d');
	    echo($date_enregistrement).'<br>';
	    echo($id_personnel).'<br>';
		$periode_collecte= FC_Formater($periode_collecte);
		$source_donnees= FC_Formater($source_donnees);
		$observation_periode= FC_Formater($observation_periode);
		$ref_indicateur= FC_Formater($ref_indicateur);
		
		
        if (!empty($periode_collecte) && !empty($source_donnees) && !empty($date_validation) && !empty($observation_periode)) { 

			$requete=PC_Enregistrer_Code('INSERT INTO `t_periode_indicateur` (`periode_collecte`, `source_donnees`, `date_validation`, `valeur_periode`, `observation`, `ref_indicateur`, `id_personnel`, `date_enregistrement`, `etat`, `modifier_le`, `modifier_par`) VALUES (\''.$periode_collecte.'\', \''.$source_donnees.'\', '.$date_validation.', '.$valeur_periode.', \''.$observation_periode.'\', \''.$ref_indicateur.'\', \''.$id_personnel.'\', '.$date_enregistrement.', NULL, NULL, NULL)');

			if ($requete) {$output=1;}
           else{$output=0;}}
	echo $output;
 ?>