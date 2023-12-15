<?php
if(isset($_GET))
{extract($_GET);
require_once '../fonctions/php/Fonctions.php';
foreach (FC_Rechercher_Code("SELECT * FROM t_feuille WHERE Code_Feuille=".$Code_Feuille) as $row4) 
{$Stat="";
if($row4["Source_Donnees"]=="Oui"){$Stat="Non";} else{$Stat="Oui";}

	PC_Enregistrer_Code("UPDATE t_feuille SET Source_Donnees='".$Stat."' WHERE Code_Feuille=".$Code_Feuille);}
}	

?>