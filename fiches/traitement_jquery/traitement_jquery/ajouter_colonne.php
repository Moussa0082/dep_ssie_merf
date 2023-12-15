<?php
if(isset($_POST)){extract($_POST);
require_once '../fonctions/php/Fonctions.php';
foreach (FC_Rechercher_Code("SELECT * FROM t_feuille WHERE Code_Feuille=".$Code_Feuille) as $row4) 
{$Nb_Col=0;
 $Nom_Table=$row4['Table_Feuille'];
 foreach (FC_Rechercher_Code("SELECT MAX(CONVERT(SUBSTR(Nom_Collone,4),UNSIGNED INTEGER))+1 AS NB FROM t_feuille_ligne WHERE Code_Feuille=".$Code_Feuille) as $row5) 
 {$Nb_Col=$row5['NB'];}
 
 for ($i=0; $i<count($nom_Ligne); $i++){

switch ($type_Ligne[$i]) {

	case 'TEXT':
	PC_Enregistrer_Code('ALTER TABLE '.$Nom_Table.' ADD col'.($i+$Nb_Col).' TEXT DEFAULT NULL');
	PC_Enregistrer_Code("INSERT INTO t_feuille_ligne(Code_Feuille, Nom_Ligne, Libelle_Ligne, Type_Ligne, Requis, Afficher, Nom_Collone) VALUES ($Code_Feuille,'".addslashes($nom_Ligne[$i])."','".addslashes($libelle_Ligne[$i])."', '".addslashes($type_Ligne[$i])."', '".$requis[$i]."','".$afficher[$i]."','col".($i+$Nb_Col)."')");
		break;

	case 'INT':
	PC_Enregistrer_Code('ALTER TABLE '.$Nom_Table.' ADD col'.($i+$Nb_Col).' BIGINT(20) DEFAULT NULL');
	PC_Enregistrer_Code("INSERT INTO t_feuille_ligne(Code_Feuille, Nom_Ligne, Libelle_Ligne, Type_Ligne, Requis, Afficher, Nom_Collone) VALUES ($Code_Feuille,'".addslashes($nom_Ligne[$i])."','".addslashes($libelle_Ligne[$i])."', '".addslashes($type_Ligne[$i])."', '".$requis[$i]."','".$afficher[$i]."','col".($i+$Nb_Col)."')");
		break;

	case 'DOUBLE':
	PC_Enregistrer_Code('ALTER TABLE '.$Nom_Table.' ADD col'.($i+$Nb_Col).' DECIMAL(14,2) DEFAULT NULL');
	PC_Enregistrer_Code("INSERT INTO t_feuille_ligne(Code_Feuille, Nom_Ligne, Libelle_Ligne, Type_Ligne, Requis, Afficher, Nom_Collone) VALUES ($Code_Feuille,'".addslashes($nom_Ligne[$i])."','".addslashes($libelle_Ligne[$i])."', '".addslashes($type_Ligne[$i])."', '".$requis[$i]."','".$afficher[$i]."','col".($i+$Nb_Col)."')");

		break;

	case 'DATE':
	PC_Enregistrer_Code('ALTER TABLE '.$Nom_Table.' ADD col'.($i+$Nb_Col).' DATE DEFAULT NULL');
	PC_Enregistrer_Code("INSERT INTO t_feuille_ligne(Code_Feuille, Nom_Ligne, Libelle_Ligne, Type_Ligne, Requis, Afficher, Nom_Collone) VALUES ($Code_Feuille,'".addslashes($nom_Ligne[$i])."','".addslashes($libelle_Ligne[$i])."', '".addslashes($type_Ligne[$i])."', '".$requis[$i]."','".$afficher[$i]."','col".($i+$Nb_Col)."')");
		break;

	case 'CHOIX':
	 $Structure_Champs="col".($i+$Nb_Col);
	$Table_Choix=null;
	$Table_Choix=explode(';', $valeur[$i]);
	$Structure_Champs.=' ENUM(';
	foreach ($Table_Choix as $k1) {$Structure_Champs.='\''.trim($k1).'\',';}
	$Structure_Champs=substr($Structure_Champs, 0, strlen($Structure_Champs)-1);
	$Structure_Champs.=' )';
	PC_Enregistrer_Code('ALTER TABLE '.$Nom_Table.' ADD '.$Structure_Champs);
	PC_Enregistrer_Code("INSERT INTO t_feuille_ligne(Code_Feuille, Nom_Ligne, Libelle_Ligne, Type_Ligne, Requis, Afficher, Nom_Collone) VALUES ($Code_Feuille,'".addslashes($nom_Ligne[$i])."','".addslashes($libelle_Ligne[$i])."', '".addslashes($type_Ligne[$i])."', '".$requis[$i]."','".$afficher[$i]."','col".($i+$Nb_Col)."')");

	PC_Enregistrer_Code('INSERT INTO t_feuille_etrangere(Nom_Table, Nom_Colonne, Valeur) VALUES (\''.$Nom_Table.'\',\''.'col'.($i+$Nb_Col).'\',\''.trim($valeur[$i]).'\')');
		break;

	case 'COULEUR':
	PC_Enregistrer_Code('ALTER TABLE '.$Nom_Table.' ADD col'.($i+$Nb_Col).' VARCHAR(50) DEFAULT NULL');
	PC_Enregistrer_Code("INSERT INTO t_feuille_ligne(Code_Feuille, Nom_Ligne, Libelle_Ligne, Type_Ligne, Requis, Afficher, Nom_Collone) VALUES ($Code_Feuille,'".addslashes($nom_Ligne[$i])."','".addslashes($libelle_Ligne[$i])."', '".addslashes($type_Ligne[$i])."', '".$requis[$i]."','".$afficher[$i]."','col".($i+$Nb_Col)."')");
		break;

	case 'FICHIER':
	PC_Enregistrer_Code('ALTER TABLE '.$Nom_Table.' ADD col'.($i+$Nb_Col).' TEXT DEFAULT NULL');
	PC_Enregistrer_Code("INSERT INTO t_feuille_ligne(Code_Feuille, Nom_Ligne, Libelle_Ligne, Type_Ligne, Requis, Afficher, Nom_Collone) VALUES ($Code_Feuille,'".addslashes($nom_Ligne[$i])."','".addslashes($libelle_Ligne[$i])."', '".addslashes($type_Ligne[$i])."', '".$requis[$i]."','".$afficher[$i]."','col".($i+$Nb_Col)."')");
		break;

	case 'FEUILLE':
	PC_Enregistrer_Code('ALTER TABLE '.$Nom_Table.' ADD col'.($i+$Nb_Col).' TEXT DEFAULT NULL');
	PC_Enregistrer_Code("INSERT INTO t_feuille_ligne(Code_Feuille, Nom_Ligne, Libelle_Ligne, Type_Ligne, Requis, Afficher, Nom_Collone) VALUES ($Code_Feuille,'".addslashes($nom_Ligne[$i])."','".addslashes($libelle_Ligne[$i])."', '".addslashes($type_Ligne[$i])."', '".$requis[$i]."','".$afficher[$i]."','col".($i+$Nb_Col)."')");
	PC_Enregistrer_Code('INSERT INTO t_feuille_etrangere(Nom_Table, Nom_Colonne, Valeur) VALUES (\''.$Nom_Table.'\',\'col'.($i+$Nb_Col).'\',\''.trim($valeur[$i]).'\');');
		break;


	case 'RAPPORT':
	PC_Enregistrer_Code("INSERT INTO t_feuille_ligne(Code_Feuille, Nom_Ligne, Libelle_Ligne, Type_Ligne, Requis, Afficher, Nom_Collone) VALUES ($Code_Feuille,'".addslashes($nom_Ligne[$i])."','".addslashes($libelle_Ligne[$i])."', '".addslashes($type_Ligne[$i])."', '".$requis[$i]."','".$afficher[$i]."','col".($i+$Nb_Col)."')");
	PC_Enregistrer_Code('INSERT INTO t_feuille_etrangere(Nom_Table, Nom_Colonne, Valeur) VALUES (\''.$Nom_Table.'\',\''."col".($i+$Nb_Col).'\',\''.trim($valeur[$i]).'\');');	

		break;


	case 'SOMME':
	PC_Enregistrer_Code("INSERT INTO t_feuille_ligne(Code_Feuille, Nom_Ligne, Libelle_Ligne, Type_Ligne, Requis, Afficher, Nom_Collone) VALUES ($Code_Feuille,'".addslashes($nom_Ligne[$i])."','".addslashes($libelle_Ligne[$i])."', '".addslashes($type_Ligne[$i])."', '".$requis[$i]."','".$afficher[$i]."','col".($i+$Nb_Col)."')");
	PC_Enregistrer_Code('INSERT INTO t_feuille_etrangere(Nom_Table, Nom_Colonne, Valeur) VALUES (\''.$Nom_Table.'\',\''."col".($i+$Nb_Col).'\',\''.trim($valeur[$i]).'\');');
		break;

	case 'DIFFERENCE':
	PC_Enregistrer_Code("INSERT INTO t_feuille_ligne(Code_Feuille, Nom_Ligne, Libelle_Ligne, Type_Ligne, Requis, Afficher, Nom_Collone) VALUES ($Code_Feuille,'".addslashes($nom_Ligne[$i])."','".addslashes($libelle_Ligne[$i])."', '".addslashes($type_Ligne[$i])."', '".$requis[$i]."','".$afficher[$i]."','col".($i+$Nb_Col)."')");
	PC_Enregistrer_Code('INSERT INTO t_feuille_etrangere(Nom_Table, Nom_Colonne, Valeur) VALUES (\''.$Nom_Table.'\',\''."col".($i+$Nb_Col).'\',\''.trim($valeur[$i]).'\');');
		
		break;

	case 'PRODUIT':
	PC_Enregistrer_Code("INSERT INTO t_feuille_ligne(Code_Feuille, Nom_Ligne, Libelle_Ligne, Type_Ligne, Requis, Afficher, Nom_Collone) VALUES ($Code_Feuille,'".addslashes($nom_Ligne[$i])."','".addslashes($libelle_Ligne[$i])."', '".addslashes($type_Ligne[$i])."', '".$requis[$i]."','".$afficher[$i]."','col".($i+$Nb_Col)."')");
	PC_Enregistrer_Code('INSERT INTO t_feuille_etrangere(Nom_Table, Nom_Colonne, Valeur) VALUES (\''.$Nom_Table.'\',\''."col".($i+$Nb_Col).'\',\''.trim($valeur[$i]).'\');');		
		break;

	case 'MOYENNE':
	PC_Enregistrer_Code("INSERT INTO t_feuille_ligne(Code_Feuille, Nom_Ligne, Libelle_Ligne, Type_Ligne, Requis, Afficher, Nom_Collone) VALUES ($Code_Feuille,'".addslashes($nom_Ligne[$i])."','".addslashes($libelle_Ligne[$i])."', '".addslashes($type_Ligne[$i])."', '".$requis[$i]."','".$afficher[$i]."','col".($i+$Nb_Col)."')");
	PC_Enregistrer_Code('INSERT INTO t_feuille_etrangere(Nom_Table, Nom_Colonne, Valeur) VALUES (\''.$Nom_Table.'\',\''."col".($i+$Nb_Col).'\',\''.trim($valeur[$i]).'\');');	
		break;

	case 'COMPTER':
	PC_Enregistrer_Code("INSERT INTO t_feuille_ligne(Code_Feuille, Nom_Ligne, Libelle_Ligne, Type_Ligne, Requis, Afficher, Nom_Collone) VALUES ($Code_Feuille,'".addslashes($nom_Ligne[$i])."','".addslashes($libelle_Ligne[$i])."', '".addslashes($type_Ligne[$i])."', '".$requis[$i]."','".$afficher[$i]."','col".($i+$Nb_Col)."')");
		break;
	default:
		# code...
		break;
}
}
$Nom_View=str_replace("t", "v", $Nom_Table);
PC_Enregistrer_Code("DROP VIEW ".$Nom_View);
$Structure_View="CREATE VIEW $Nom_View AS SELECT Id AS Id, Stat AS Stat, ";
$i=0;
$Res=FC_Rechercher_Code("SELECT * FROM t_feuille_ligne WHERE Code_Feuille=".$Code_Feuille);
 foreach ($Res as $row7) 
{
switch ($row7["Type_Ligne"]) {

	case 'TEXT':
		  $Structure_View.=" ".$row7['Nom_Collone'].",";
		break;

	case 'INT':
		  $Structure_View.=" ".$row7['Nom_Collone'].",";
		break;

	case 'DOUBLE':
		  $Structure_View.=" ".$row7['Nom_Collone'].",";

		break;

	case 'DATE':
		  $Structure_View.=" ".$row7['Nom_Collone'].",";
		break;

	case 'CHOIX':
	  $Structure_View.=" ".$row7['Nom_Collone'].",";
		break;

	case 'COULEUR':
		  $Structure_View.=" ".$row7['Nom_Collone'].",";
		break;

	case 'FICHIER':
		  $Structure_View.=" ".$row7['Nom_Collone'].",";
		break;

	case 'FEUILLE':	
	  $Structure_View.=" ".$row7['Nom_Collone'].",";
		break;


	case 'RAPPORT':
	$Valeur="";
 foreach (FC_Rechercher_Code("SELECT * FROM t_feuille_etrangere WHERE (Nom_Table='".$Nom_Table."' AND Nom_Colonne='".$row7['Nom_Collone']."')") as $row8) 
{$Valeur=$row8['Valeur'];}
	$Structure_View.=" ((";
	$Table_Choix=null;
	$Table_Choix=explode(';', $Valeur);
foreach ($Table_Choix as $k2)
{foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE Code_Feuille='.$Code_Feuille.' AND Nom_Ligne=\''.trim($k2).'\'') as $row9) 
{$Structure_View.=$row9['Nom_Collone'].'/';}
}
	$Structure_View=substr($Structure_View, 0, strlen($Structure_View)-1);
	$Structure_View.=" )*100) AS ".$row7['Nom_Collone'].",";
		break;


	case 'SOMME':
	$Valeur="";
 foreach (FC_Rechercher_Code("SELECT * FROM t_feuille_etrangere WHERE (Nom_Table='".$Nom_Table."' AND Nom_Colonne='".$row7['Nom_Collone']."')") as $row8) 
{$Valeur=$row8['Valeur'];}
	$Structure_View.=" (";
	$Table_Choix=null;
	$Table_Choix=explode(';', $Valeur);
foreach ($Table_Choix as $k2)
{foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE Code_Feuille='.$Code_Feuille.' AND Nom_Ligne=\''.trim($k2).'\'') as $row9) 
{$Structure_View.=$row9['Nom_Collone'].'+';}
}
	$Structure_View=substr($Structure_View, 0, strlen($Structure_View)-1);
	$Structure_View.=" ) AS ".$row7['Nom_Collone'].",";
		break;

	case 'DIFFERENCE':
		$Valeur="";
 foreach (FC_Rechercher_Code("SELECT * FROM t_feuille_etrangere WHERE (Nom_Table='".$Nom_Table."' AND Nom_Colonne='".$row7['Nom_Collone']."')") as $row8) 
{$Valeur=$row8['Valeur'];}
	$Structure_View.=" (";
	$Table_Choix=null;
	$Table_Choix=explode(';', $Valeur);
foreach ($Table_Choix as $k2)
{foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE Code_Feuille='.$Code_Feuille.' AND Nom_Ligne=\''.trim($k2).'\'') as $row9) 
{$Structure_View.=$row9['Nom_Collone'].'-';}
}
	$Structure_View=substr($Structure_View, 0, strlen($Structure_View)-1);
	$Structure_View.=" ) AS ".$row7['Nom_Collone'].",";
		
		break;

	case 'PRODUIT':
		$Valeur="";
 foreach (FC_Rechercher_Code("SELECT * FROM t_feuille_etrangere WHERE (Nom_Table='".$Nom_Table."' AND Nom_Colonne='".$row7['Nom_Collone']."')") as $row8) 
{$Valeur=$row8['Valeur'];}
	$Structure_View.=" (";
	$Table_Choix=null;
	$Table_Choix=explode(';', $Valeur);
foreach ($Table_Choix as $k2)
{foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE Code_Feuille='.$Code_Feuille.' AND Nom_Ligne=\''.trim($k2).'\'') as $row9) 
{$Structure_View.=$row9['Nom_Collone'].'*';}
}
	$Structure_View=substr($Structure_View, 0, strlen($Structure_View)-1);
	$Structure_View.=" ) AS ".$row7['Nom_Collone'].",";		
		break;

	case 'MOYENNE':
		$Valeur="";
 foreach (FC_Rechercher_Code("SELECT * FROM t_feuille_etrangere WHERE (Nom_Table='".$Nom_Table."' AND Nom_Colonne='".$row7['Nom_Collone']."')") as $row8) 
{$Valeur=$row8['Valeur'];}
	$Structure_View.=" ((";
	$Table_Choix=null;
	$ui=0;
	$Table_Choix=explode(';', $Valeur);
	foreach ($Table_Choix as $k2)
{$ui++; foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE Code_Feuille='.$Code_Feuille.' AND Nom_Ligne=\''.trim($k2).'\'') as $row9) 
{$Structure_View.=$row9['Nom_Collone'].'+';}
}
	$Structure_View=substr($Structure_View, 0, strlen($Structure_View)-1);
	$Structure_View.=")/$ui) AS ".$row7['Nom_Collone'].",";	
		break;

	case 'COMPTER':
	$Structure_View.=" COUNT(";
	$Structure_View.='Id';
	$Structure_View.=") AS ".$row7['Nom_Collone'].",";	
	
		break;
	default:
		break;
}
$i++;
}
$Structure_View=substr($Structure_View, 0, strlen($Structure_View)-1);
$Structure_View.=" FROM $Nom_Table GROUP BY Id ;";
$Resultat=PC_Enregistrer_Code($Structure_View);
PC_Enregistrer_Code("UPDATE t_feuille SET Structure_View='".$Structure_View."' WHERE Code_Feuille=".$Code_Feuille);


}

}
?>