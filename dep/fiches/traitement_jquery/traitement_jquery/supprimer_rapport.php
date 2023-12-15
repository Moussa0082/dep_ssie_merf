<?php
if($_GET['Rapport']){
require_once '../fonctions/php/Fonctions.php';	
extract($_GET);
foreach (FC_Rechercher_Code('SELECT * FROM t_rapport WHERE Code_Rapport='.$Rapport) as $row4)
{$Nom_View="";
 $Nom_View=$row4["Nom_View"];
	PC_Enregistrer_Code("DELETE FROM t_rapport WHERE Code_Rapport=".$Rapport);
	PC_Enregistrer_Code("DROP VIEW IF EXISTS ".$Nom_View);
}

echo $Nom_View;
}
?>