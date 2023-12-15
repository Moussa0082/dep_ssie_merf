<?php 
require_once 'fonctions/php/Fonctions.php'; 
require_once 'fonctions/php/Session.php';

if($ACCESS=="O"){
$Code= "[";
foreach (FC_Rechercher_Code("SELECT * FROM `t_classeur` INNER JOIN v_users_conventions ON(t_classeur.Id_Projet=v_users_conventions.projet) WHERE (v_users_conventions.login='".$_POST["Login"]."')") as $row1)
{
	$Code.="{\"Code\" : \"INSERT INTO t_convention(Code_Convention, Libelle_Convention) VALUES (".$row1['id_convention'].", '".str_replace("'", "''", $row1['intitule'])."');\"}, ";

	$Code.="{\"Code\" : \"INSERT INTO t_classeur(Code_Classeur, Code_Convention, Libelle_Classeur, Note_Classeur, Couleur_Classeur, Date_Insertion) VALUES (".$row1['Code_Classeur'].", ".$row1['id_convention'].", '".str_replace("'", "''", $row1['Libelle_Classeur'])."', '".str_replace("'", "''",$row1['Note_Classeur'])."', '".$row1['Couleur_Classeur']."', '".$row1['Date_Insertion']."');\"}, ";


foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE Code_Classeur='.$row1["Code_Classeur"]) as $row2)
{$Structure_Table="";
$ind=0;
 $Structure_Table.="CREATE TABLE ".$row2['Table_Feuille']." (Id INTEGER PRIMARY KEY AUTOINCREMENT, Stat INTEGER DEFAULT 0,  SENT INTEGER DEFAULT 0, LOGIN VARCHAR(255) DEFAULT NULL, LG VARCHAR(255) DEFAULT NULL, LT VARCHAR(255) DEFAULT NULL ,";
	foreach (FC_Rechercher_Code('DESCRIBE '.$row2['Table_Feuille']) as $row4)
	{if($ind>=6){
		if(stripos($row4['Type'], "enum")===false){$Structure_Table.=$row4['Field']." ".str_replace("'", "''",$row4['Type'])." DEFAULT NULL, ";}
		else{$Structure_Table.=$row4['Field']." VARCHAR(255) DEFAULT NULL, ";}
	}
		$ind++;
		//print_r($row4);
	}
$Structure_Table=substr($Structure_Table, 0, strlen($Structure_Table)-2);
$Structure_Table=str_replace("bigint(20)", "INTEGER", $Structure_Table);
$Structure_Table.=");";	
	$Code.="{\"Code\" : \"INSERT INTO t_feuille(Code_Feuille, Code_Classeur, Nom_Feuille, Libelle_Feuille, Nb_Ligne_Impr, Icone, Note, Table_Feuille, Structure_Table, Structure_View, Source_Donnees) VALUES (".$row2['Code_Feuille'].", ".$row2['Code_Classeur'].", '".str_replace("'", "''",$row2['Nom_Feuille'])."', '".str_replace("'", "''",$row2['Libelle_Feuille'])."', ".$row2['Nb_Ligne_Impr'].", '".$row2['Icone']."', '".str_replace("'", "''",$row2['Note'])."', '".$row2['Table_Feuille']."', '".$Structure_Table."', '".$row2['Structure_View']."' , '".$row2['Source_Donnees']."');\"}, ";

	$Code.="{\"Code\" : \"DROP TABLE IF EXISTS ".$row2["Table_Feuille"]." ;\"}, ";
	$Code.="{\"Code\" : \"".$Structure_Table."\"}, ";

	

foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE (Mobile=\'Oui\' AND Code_Feuille='.$row2["Code_Feuille"].')') as $row3)
{
	$Code.="{\"Code\" : \"INSERT INTO t_feuille_ligne(Code_Feuille_Ligne, Code_Feuille, Nom_Ligne, Libelle_Ligne, Type_Ligne, Requis, Afficher, Nom_Collone, Rang, Formulaire) VALUES (".$row3['Code_Feuille_Ligne'].", ".$row3['Code_Feuille'].", '".str_replace("'", "''",$row3['Nom_Ligne'])."', '".str_replace("'", "''",$row3['Libelle_Ligne'])."', '".$row3['Type_Ligne']."', '".$row3['Requis']."', '".$row3['Afficher']."', '".$row3['Nom_Collone']."', ".$row3['Rang'].", ".$row3['Formulaire'].");\"}, ";

}

foreach (FC_Rechercher_Code("SELECT * FROM t_feuille_etrangere WHERE Nom_Table='".$row2["Table_Feuille"]."'") as $row5)
{
	$Code.="{\"Code\" : \"INSERT INTO t_feuille_etrangere(Nom_Table, Nom_Colonne, Valeur) VALUES ('".$row5['Nom_Table']."','".$row5['Nom_Colonne']."', '".str_replace("'", "''",$row5['Valeur'])."');\"}, ";

}

foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE (Source_Donnees=\'Oui\' AND Code_Feuille='.$row2["Code_Feuille"].')') as $row6)
{
	foreach (FC_Rechercher_Code('SELECT * FROM '.$row6['Table_Feuille'].' WHERE Stat=1') as $row7)
	{$Code_Insertion="";
	 
	 $Code_Insertion="INSERT INTO ".$row6['Table_Feuille']." (";	
		foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne INNER JOIN t_feuille ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE (t_feuille_ligne.Code_Feuille='.$row6['Code_Feuille'].' AND Type_Ligne NOT IN(\'SOMME\', \'DIFFERENCE\', \'PRODUIT\', \'RAPPORT\', \'MOYENNE\', \'COMPTER\') AND Mobile=\'Oui\')') as $row9)
		{$Code_Insertion.=$row9["Nom_Collone"].", ";}
	
	


		$Code_Insertion=substr($Code_Insertion, 0, strlen($Code_Insertion)-2);
		$Code_Insertion.=") VALUES(";

		foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne INNER JOIN t_feuille ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE (t_feuille_ligne.Code_Feuille='.$row6['Code_Feuille'].' AND Type_Ligne NOT IN(\'SOMME\', \'DIFFERENCE\', \'PRODUIT\', \'RAPPORT\', \'MOYENNE\', \'COMPTER\') AND Mobile=\'Oui\')') as $row10)
		{
			if($row10['Type_Ligne']=="INT" OR $row10['Type_Ligne']=="DOUBLE")
				{$Code_Insertion.=addslashes(trim($row7[$row10["Nom_Collone"]])).", ";}
			else {$Code_Insertion.="'".addslashes(trim($row7[$row10["Nom_Collone"]]))."', ";}

			
		}

		$Code_Insertion=substr($Code_Insertion, 0, strlen($Code_Insertion)-2);
		$Code_Insertion.=");";

		$Code.="{\"Code\" : \"".$Code_Insertion."\"}, ";
	}

}

}
}
$Code=substr($Code, 0, strlen($Code)-2);
$Code.= "]";

if(strlen($Code) > 10){echo $Code;}



}

 ?>
