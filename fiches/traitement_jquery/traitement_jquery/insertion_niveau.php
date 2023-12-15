<?php 
	require_once '../fonctions/PHP/Fonctions.php';

		$output=0;
		$projet_niveau=isset($_POST['projet_niveau'])?$_POST['projet_niveau']:'';
	    $nombre_niveau=isset($_POST['nombre_niveau'])?$_POST['nombre_niveau']:'';
	    $libelle_niveau=isset($_POST['libelle_niveau'])?$_POST['libelle_niveau']:'';
	    $code_niveau=isset($_POST['code_niveau'])?$_POST['code_niveau']:'';
    	$programme_niveau=isset($_POST['programme_niveau'])?$_POST['programme_niveau']:'';
	 
		$libelle_niveau= FC_Formater($libelle_niveau);
		$code_niveau= FC_Formater($code_niveau);
		
		
        if (!empty($projet_niveau) && !empty($nombre_niveau) && !empty($libelle_niveau) && !empty($programme_niveau)) { 

			$requete=PC_Enregistrer_Code('INSERT INTO t_niveau_config (nombre, libelle, code_number, programmes, projet) VALUES (\''.$nombre_niveau.'\',\''.$libelle_niveau.'\',\''.$code_niveau.'\',\''.$programme_niveau.'\',\''.$projet_niveau.'\')');

			if ($requete) {$output=1;}
           else{$output=0;}}
	echo $output;
 ?>