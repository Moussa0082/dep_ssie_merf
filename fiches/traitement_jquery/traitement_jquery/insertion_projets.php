<?php 
	require_once '../fonctions/PHP/Fonctions.php';
		$output=0;
		extract($_POST);
		$sql=FC_Rechercher_Code('SELECT MAX(id_projet) AS maxi FROM t_projets')
		$rep=$sql->fetch();
		$max=(int)$rep['maxi'];
		$code_projet_new=($max+1);
		if ($code_projet_new < 10) {
			$code_projet_new = '0'.$code_projet_new;
		}
		$sigle_projet_new= FC_Formater($sigle_projet_new);
		$intitule_new= FC_Formater($intitule_new);
		$partenaire_signataire_new = FC_Formater($partenaire_signataire_new);
		$partenaire_execution_new = FC_Formater($partenaire_execution_new);
		$zone_new= FC_Formater($zone_new);
		$modalite_financement_new= FC_Formater($modalite_financement_new);
		$type_fond_fidicuaire_new= FC_Formater($type_fond_fidicuaire_new);
		$nom_fond_fidicuaire_new= FC_Formater($nom_fond_fidicuaire_new);
		$agence_lead_new= FC_Formater($agence_lead_new);
		$agence_recipiendaire_new= FC_Formater($agence_recipiendaire_new);
		$partenaire_signataire_new= FC_Formater($partenaire_signataire_new);
		$partenaire_execution_new= FC_Formater($partenaire_execution_new);
		$fenetre_pbf_new= FC_Formater($fenetre_pbf_new);
		$description_new= FC_Formater($description_new);
		$processus_consultation_new= FC_Formater($processus_consultation_new);
		$pourcentage_budget_genre_new= FC_Formater($pourcentage_budget_genre_new);
		$domaine_intervention_prioritaire_new= FC_Formater($domaine_intervention_prioritaire_new);
		$resultat_undaf_new= FC_Formater($resultat_undaf_new);
		$objectif_odd_new= FC_Formater($objectif_odd_new);
		$etat_new= FC_Formater($etat_new);
		$description_marqueur_genre_new= FC_Formater($description_marqueur_genre_new);
		$date_signature_new=isset($_POST['date_signature_new'])?$_POST['date_signature_new']:'';
		$date_demarrage_new=isset($_POST['date_demarrage_new'])?$_POST['date_demarrage_new']:'';
		
		
        if (!empty($code_projet_new) && !empty($sigle_projet_new) && !empty($intitule_new) && !empty($duree_new) && !empty($date_signature_new) && !empty($partenaire_signataire_new) && !empty($partenaire_execution_new) && !empty($domaine_intervention_prioritaire_new) && !empty($zone_new) && !empty($nature_new) && !empty($date_demarrage_new)) { 


			$requete=PC_Enregistrer_Code('INSERT INTO t_projets(code_projet, sigle_projet, intitule_projet, date_signature, modalite_financement, type_fonds_fidicuiare, nom_fonds_fidicuaire, agence_lead, autres_agences_recipiendaires, partenaires_signataires, autres_partenaires_execution, fenetre_pbf, zone, nature, date_demarrage, duree, description_projet, processus_consultation, pourcentage_budget_genre, description_marqueur_genre, domaine_intervention_prioritaire, resultat_undaf, objectif_odd, id_personnel, date_enregistrement, etat) VALUES (\''.$code_projet_new.'\', \''.$sigle_projet_new.'\', \''.$intitule_new.'\', \''.$date_signature_new.'\', \''.$modalite_financement_new.'\', \''.$type_fond_fidicuaire_new.'\', \''.$nom_fond_fidicuaire_new.'\', \''.$agence_lead_new.'\', \''.$agence_recipiendaire_new.'\', \''.$partenaire_signataire_new.'\', \''.$partenaire_execution_new.'\', \''.$fenetre_pbf_new.'\', \''.$zone_new.'\', \''.$nature_new.'\', \''.$date_demarrage_new.'\', '.$duree_new.', \''.$description_new.'\', \''.$processus_consultation_new.'\', \''.$pourcentage_budget_genre_new.'\', \''.$description_marqueur_genre_new.'\', \''.$domaine_intervention_prioritaire_new.'\', \''.$resultat_undaf_new.'\', \''.$objectif_odd_new.'\', \''.$_SESSION['id'].'\', NOW(), \''.$etat_new.'\')'); 

			if ($requete) {$output=1;}
           else{$output=0;}}
	echo $output;
 ?>