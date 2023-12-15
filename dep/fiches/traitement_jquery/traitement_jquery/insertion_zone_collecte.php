<?php 
	require_once '../fonctions/PHP/Fonctions.php';

		$output=0;
		$reference_zone=isset($_POST['reference_zone'])?$_POST['reference_zone']:'';
	    $type_zone=isset($_POST['type_zone'])?$_POST['type_zone']:'';
	    $superficie_zone=isset($_POST['superficie_zone'])?$_POST['superficie_zone']:'';
    	$couche_zone=isset($_POST['couche_zone'])?$_POST['couche_zone']:'';
	    $longitude_zone=isset($_POST['longitude_zone'])?$_POST['longitude_zone']:'';
	    $latitude_zone=isset($_POST['latitude_zone'])?$_POST['latitude_zone']:'';
	    $description_zone=isset($_POST['description_zone'])?$_POST['description_zone']:'';
	    $date_enregistrement=date('Y-m-d h:i:s');
	
		$reference_zone= FC_Formater($reference_zone);
		$description_zone= FC_Formater($description_zone);
		$couche_zone= FC_Formater($couche_zone);
		
        if (!empty($reference_zone) && !empty($couche_zone)) { 

			$requete=PC_Enregistrer_Code('INSERT INTO t_zone_collecte (reference, type_zone, superficie, couche,  	longitude, latitude, description,date_enregistrement) VALUES (\''.$reference_zone.'\',\''.$type_zone .'\',\''.$superficie_zone.'\',\''.$couche_zone.'\',\''.$longitude_zone .'\',\''.$latitude_zone.'\',\''.$description_zone.'\',\''.$date_enregistrement.'\')');
			if ($requete) {$output=1;}
           else{$output=0;}}
	echo $output;
 ?>