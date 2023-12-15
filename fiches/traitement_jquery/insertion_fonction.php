<?php 

	require_once '../api/Fonctions.php';

		$output='';
		$fonction=isset($_POST['fonction'])?$_POST['fonction']:'';
	    $description=isset($_POST['description'])?$_POST['description']:'';
	    $service=isset($_POST['service'])?$_POST['service']:'';
    	
        
		$fonction= FC_Formater($fonction);
		$description= FC_Formater($description);
		$service= FC_Formater($service);
		
        if (!empty($fonction) && !empty($description) && !empty($service)) { 

			$requete=PC_Enregistrer_Code('INSERT INTO t_fonction (fonction, description, service) VALUES (\''.$fonction.'\',\''.$description .'\',\''.$service.'\')');

			if ($requete) {$output='1';}
           else{$output='0';}}
	echo $output;
 ?>