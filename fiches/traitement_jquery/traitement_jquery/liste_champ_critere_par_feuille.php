<?php
if($_POST){
require_once '../fonctions/php/Fonctions.php';	
extract($_POST);
$ind=0;
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne INNER JOIN t_feuille ON(t_feuille.Code_Feuille=t_feuille_ligne.Code_Feuille) WHERE t_feuille_ligne.Code_Feuille='.$select_feuille) as $row4)
{
echo '<option value="'.str_replace("t", "v", $row4["Table_Feuille"]).'.'.$row4["Nom_Collone"].'" id="">'.$row4["Nom_Feuille"].'.'.$row4["Nom_Ligne"].'</option>';
$ind++;
}

if(isset($feuille_jointure) AND !empty($feuille_jointure)){
	foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne INNER JOIN t_feuille ON(t_feuille.Code_Feuille=t_feuille_ligne.Code_Feuille) WHERE t_feuille_ligne.Code_Feuille='.$feuille_jointure) as $row5)
{
echo '<option value="'.str_replace("t", "v", $row5["Table_Feuille"]).'.'.$row5["Nom_Collone"].'" id="">'.$row5["Nom_Feuille"].'.'.$row5["Nom_Ligne"].'</option>';
$ind++;
}
}
}
?>