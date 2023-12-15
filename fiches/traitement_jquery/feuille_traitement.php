<?php
if(isset($_POST)){extract($_POST);}
$Icone="";
$photo_name="";
if((isset($_FILES['Icone']['tmp_name']) and !empty($_FILES['Icone']['tmp_name'])) and (isset($_FILES['Icone']['size']) and !empty($_FILES['Icone']['size'])) and (isset($_FILES['Icone']['name']) and !empty($_FILES['Icone']['name'])))
{ $ext= strtolower(substr($_FILES['Icone']['name'], strrpos($_FILES['Icone']['name'], ".")+1));
if ($ext=='jpg' OR $ext== 'jpeg' OR $ext== 'png' OR $ext== 'gif')
{$tmp_name=$_FILES['Icone']['tmp_name'];
$photo_name=time().'.'.$ext;
if (move_uploaded_file($tmp_name, "../images/".$photo_name)) 
{$Icone=$photo_name;}
else{$Icone="";}}
else{$Icone="";}}

$Ind = array(' ');
require_once '../api/Fonctions.php';
$Nom_Table="t_".time();
$Nom_View=str_replace("t", "v", $Nom_Table);
$Structure_Champs="";
$Structure_Table="";
$Structure_View="CREATE VIEW $Nom_View AS SELECT Id AS Id, Stat AS Stat, Date_Insertion AS Date_Insertion, Login AS Login, LG AS LG, LT AS LT, ";
for ($i=0; $i<count($nom_Ligne); $i++){

switch ($type_Ligne[$i]) {

	case 'TEXT':
	  $Structure_Champs.="col$i";
		$Structure_Champs.=' TEXT DEFAULT NULL, ';
		  $Structure_View.=" col$i,";
		break;

	case 'QRCODE':
	  $Structure_Champs.="col$i";
		$Structure_Champs.=' TEXT DEFAULT NULL, ';
		  $Structure_View.=" col$i,";
		break;	

	case 'INT':
	  $Structure_Champs.="col$i";
		$Structure_Champs.=' BIGINT(20) DEFAULT NULL, ';
		  $Structure_View.=" col$i,";
		break;

	case 'DOUBLE':
	  $Structure_Champs.="col$i";
		$Structure_Champs.=' DECIMAL(14,2) DEFAULT NULL, ';
		  $Structure_View.=" col$i,";

		break;

	case 'DATE':
	  $Structure_Champs.="col$i";
		$Structure_Champs.=' DATE DEFAULT NULL, ';
		  $Structure_View.=" col$i AS col$i,";
		break;

	case 'CHOIX':
	 $Structure_Champs.="col$i";
	$Table_Choix=null;
	$Table_Choix=explode(';', $valeur[$i]);
	$Structure_Champs.=' ENUM(';
	foreach ($Table_Choix as $k1) {$Structure_Champs.='\''.trim(addslashes($k1)).'\',';}
	$Structure_Champs=substr($Structure_Champs, 0, strlen($Structure_Champs)-1);
	$Structure_Champs.=' ), ';
	PC_Enregistrer_Code('INSERT INTO t_feuille_etrangere(Nom_Table, Nom_Colonne, Valeur) VALUES (\''.$Nom_Table.'\',\''."col$i".'\',\''.trim($valeur[$i]).'\');');
	  $Structure_View.=" col$i,";
		break;

	case 'CHOIX MULTIPLES':
	 $Structure_Champs.="col$i";
	$Table_Choix=null;
	$Table_Choix=explode(';', $valeur[$i]);
	$Structure_Champs.=' ENUM(';
	foreach ($Table_Choix as $k1) {$Structure_Champs.='\''.trim(addslashes($k1)).'\',';}
	$Structure_Champs=substr($Structure_Champs, 0, strlen($Structure_Champs)-1);
	$Structure_Champs.=' ), ';
	PC_Enregistrer_Code('INSERT INTO t_feuille_etrangere(Nom_Table, Nom_Colonne, Valeur) VALUES (\''.$Nom_Table.'\',\''."col$i".'\',\''.trim(addslashes($valeur[$i])).'\');');
	  $Structure_View.=" col$i,";
		break;

	case 'COULEUR':
	  $Structure_Champs.="col$i";
		$Structure_Champs.=' VARCHAR(50) DEFAULT NULL, ';
		  $Structure_View.=" col$i,";
		break;

	case 'FICHIER':
	  $Structure_Champs.="col$i";
		$Structure_Champs.=' TEXT DEFAULT NULL, ';
		  $Structure_View.=" col$i,";
		break;
		
	case 'SIGNATURE':
	  $Structure_Champs.="col$i";
		$Structure_Champs.=' TEXT DEFAULT NULL, ';
		  $Structure_View.=" col$i,";
		break;

	case 'FEUILLE':
	  $Structure_Champs.="col$i";
	$Structure_Champs.=' TEXT DEFAULT NULL, ';
	PC_Enregistrer_Code('INSERT INTO t_feuille_etrangere(Nom_Table, Nom_Colonne, Valeur) VALUES (\''.$Nom_Table.'\',\''."col$i".'\',\''.trim($valeur[$i]).'\');');	
	  $Structure_View.=" col$i,";
		break;


	case 'RAPPORT':
	$Structure_View.=" ((";
	$Table_Choix=null;
	$Table_Choix=explode(';', $valeur[$i]);
	foreach ($Table_Choix as $k2) {$Structure_View.='col'.array_search(trim($k2), $nom_Ligne).'/';}
	$Structure_View=substr($Structure_View, 0, strlen($Structure_View)-1);
	$Structure_View.=" )*100) AS col$i,";
	PC_Enregistrer_Code('INSERT INTO t_feuille_etrangere(Nom_Table, Nom_Colonne, Valeur) VALUES (\''.$Nom_Table.'\',\''."col$i".'\',\''.trim($valeur[$i]).'\');');	

		break;


	case 'SOMME':
	$Structure_View.=" (";
	$Table_Choix=null;
	$Table_Choix=explode(';', $valeur[$i]);
	foreach ($Table_Choix as $k2) {$Structure_View.='IFNULL(col'.array_search(trim($k2), $nom_Ligne).',0) +';}
	$Structure_View=substr($Structure_View, 0, strlen($Structure_View)-1);
	$Structure_View.=" ) AS col$i,";
	PC_Enregistrer_Code('INSERT INTO t_feuille_etrangere(Nom_Table, Nom_Colonne, Valeur) VALUES (\''.$Nom_Table.'\',\''."col$i".'\',\''.trim($valeur[$i]).'\');');
		break;

	case 'DIFFERENCE':
	$Structure_View.=" (";
	$Table_Choix=null;
	$Table_Choix=explode(';', $valeur[$i]);
	foreach ($Table_Choix as $k2) {$Structure_View.='IFNULL(col'.array_search(trim($k2), $nom_Ligne).',0) -';}
	$Structure_View=substr($Structure_View, 0, strlen($Structure_View)-1);
	$Structure_View.=" ) AS col$i,";
	PC_Enregistrer_Code('INSERT INTO t_feuille_etrangere(Nom_Table, Nom_Colonne, Valeur) VALUES (\''.$Nom_Table.'\',\''."col$i".'\',\''.trim($valeur[$i]).'\');');
		
		break;

	case 'PRODUIT':
	$Structure_View.=" (";
	$Table_Choix=null;
	$Table_Choix=explode(';', $valeur[$i]);
	foreach ($Table_Choix as $k2) {$Structure_View.='IFNULL(col'.array_search(trim($k2), $nom_Ligne).',0) *';}
	$Structure_View=substr($Structure_View, 0, strlen($Structure_View)-1);
	$Structure_View.=" ) AS col$i,";
	PC_Enregistrer_Code('INSERT INTO t_feuille_etrangere(Nom_Table, Nom_Colonne, Valeur) VALUES (\''.$Nom_Table.'\',\''."col$i".'\',\''.trim($valeur[$i]).'\');');		
		break;

	case 'MOYENNE':
	$Structure_View.=" ((";
	$Table_Choix=null;
	$ui=0;
	$Table_Choix=explode(';', $valeur[$i]);
	foreach ($Table_Choix as $k2) {$ui++; $Structure_View.='IFNULL(col'.array_search(trim($k2), $nom_Ligne).',0) +';}
	$Structure_View=substr($Structure_View, 0, strlen($Structure_View)-1);
	$Structure_View.=")/$ui) AS col$i,";
	PC_Enregistrer_Code('INSERT INTO t_feuille_etrangere(Nom_Table, Nom_Colonne, Valeur) VALUES (\''.$Nom_Table.'\',\''."col$i".'\',\''.trim($valeur[$i]).'\');');	
		break;

	case 'COMPTER':
	$Structure_View.=" COUNT(";
	$Table_Choix=null;
	$Table_Choix=explode(';', $valeur[$i]);
	foreach ($Table_Choix as $k2) {$Structure_View.='Id'; /*array_search(trim($k2), $nom_Ligne);*/}
	$Structure_View.=") AS col$i,";	
	
		break;
	default:
		# code...
		break;
}
}
$Structure_Table='CREATE TABLE '.$Nom_Table.'(Id BIGINT(20) AUTO_INCREMENT PRIMARY KEY, Stat INT(1) DEFAULT 0, Date_Insertion TIMESTAMP DEFAULT CURRENT_TIMESTAMP, Login VARCHAR(255), LG VARCHAR(255), LT VARCHAR(255) ,';
$Structure_Champs=trim($Structure_Champs);
$Structure_Champs=substr($Structure_Champs, 0, strlen($Structure_Champs)-1);
$Structure_Table.=$Structure_Champs.' ) ENGINE=InnoDB;';
$Structure_View=substr($Structure_View, 0, strlen($Structure_View)-1);
$Structure_View.=" FROM $Nom_Table GROUP BY Id ;";
PC_Enregistrer_Code($Structure_Table);
PC_Enregistrer_Code($Structure_View);
PC_Enregistrer_Code("INSERT INTO t_feuille(Code_Classeur, Nom_Feuille, Libelle_Feuille, Nb_Ligne_Impr, Icone, Note, Table_Feuille, Structure_Table ,Structure_View) VALUES ($Code_Classeur, '".addslashes($Nom_Feuille)."', '".addslashes($Libelle_Feuille)."', $Nb_Ligne_Impr, '$Icone', '".addslashes($Note)."', '$Nom_Table', '' ,'$Structure_View')");
$Code_Feuille="";
foreach (FC_Rechercher_Code('SELECT MAX(Code_Feuille) AS Code_Feuille FROM t_feuille') as $row4) 
{$Code_Feuille=$row4['Code_Feuille'];}
for ($i=0; $i<count($nom_Ligne); $i++)
{PC_Enregistrer_Code("INSERT INTO t_feuille_ligne(Code_Feuille, Nom_Ligne, Libelle_Ligne, Type_Ligne, Requis, Afficher, Nom_Collone) VALUES ($Code_Feuille,'".addslashes($nom_Ligne[$i])."','".addslashes($libelle_Ligne[$i])."', '".addslashes($type_Ligne[$i])."', '".$requis[$i]."','".$afficher[$i]."','col$i')");}
?>