<?php
if(isset($_GET))
{extract($_GET);
require_once '../api/Fonctions.php';
foreach (FC_Rechercher_Code("SELECT * FROM t_feuille WHERE Code_Feuille=".$Code_Feuille) as $row4) 
{
$Nb=0;
foreach (FC_Rechercher_Code("SELECT * FROM t_feuille_etrangere WHERE Valeur LIKE '%".$Code_Feuille."%'") as $row5)
{
$Valeur="";
$Valeur=$row5['Valeur'];
$Table_Choix=null;
$Table_Choix=explode(';', $Valeur);
	if(trim($Table_Choix[0])==$Code_Feuille)
	{foreach (FC_Rechercher_Code("SELECT * FROM t_feuille WHERE (Table_Feuille='".$row5['Nom_Table']."')") as $row6)
{echo "Echec de suppression! Cette feuille est utilisée par ".$row6['Nom_Feuille'] ;}
return 0;
	$Nb++;}
}

if($Nb>0){}
else{

PC_Enregistrer_Code("DROP VIEW ".str_replace("t", "v",$row4['Table_Feuille']));
/*PC_Enregistrer_Code("DROP TABLE ".$row4['Table_Feuille']);*/
//PC_Enregistrer_Code("RENAME TABLE ".str_replace("t", "v",$row4['Table_Feuille'])." TO ".str_replace("t", "v",$row4['Table_Feuille'])."_sup");
PC_Enregistrer_Code("RENAME TABLE ".$row4["Table_Feuille"]." TO ".$row4["Table_Feuille"]."_sup");
PC_Enregistrer_Code("DELETE FROM t_feuille WHERE Code_Feuille=".$Code_Feuille);
}
}
}	

?>