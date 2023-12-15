<?php
//var_dump($_POST);
if(isset($_POST)){extract($_POST);
require_once '../api/Fonctions.php';
$Nom_Table="";
foreach (FC_Rechercher_Code("SELECT * FROM t_feuille WHERE Code_Feuille=".$Code_Feuille) as $row4) 
{
for($i=0; $i<count($CheckBox_Delete_Colonne); $i++)
{PC_Enregistrer_Code("DELETE FROM t_feuille_ligne WHERE (Nom_Collone='".$CheckBox_Delete_Colonne[$i]."' AND Code_Feuille=".$Code_Feuille.")");
PC_Enregistrer_Code("DELETE FROM t_feuille_etranger WHERE (Nom_Collone='".$CheckBox_Delete_Colonne[$i]."' AND Nom_Table=".$row4['Table_Feuille'].")");
PC_Enregistrer_Code("ALTER TABLE ".$row4['Table_Feuille']." DROP ".$CheckBox_Delete_Colonne[$i]);

}
$Nom_Table=$row4['Table_Feuille'];
$Nom_View=str_replace("t", "v", $Nom_Table);
PC_Enregistrer_Code("DROP VIEW ".$Nom_View);
$Structure_View="CREATE VIEW $Nom_View AS SELECT Id AS Id, Stat AS Stat, Date_Insertion AS Date_Insertion, Login AS Login, LG AS LG, LT AS LT, ";
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

	case 'SIGNATURE':
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