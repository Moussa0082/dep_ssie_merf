<?php 
	require_once '../fonctions/PHP/Fonctions.php';

		$output=0;
		$def_type_zone=isset($_POST['def_type_zone'])?$_POST['def_type_zone']:'';
		$desc_type_zone=isset($_POST['desc_type_zone'])?$_POST['desc_type_zone']:'';
        $date_enregistrement=date('Y-m-d');
        
		$def_type_zone= FC_Formater(ucfirst($def_type_zone));
		$desc_type_zone = FC_Formater(ucfirst($desc_type_zone));
		
        if (!empty($def_type_zone) && !empty($desc_type_zone)) { 


			$requete=PC_Enregistrer_Code('INSERT INTO t_type_zone (definition, description, date_enregistrement) VALUES (\''.$def_type_zone.'\',\''.$desc_type_zone.'\',\''.$date_enregistrement.'\')');

			if ($requete) {$output=1;}
           else{$output=0;}}
	echo $output;
 ?>