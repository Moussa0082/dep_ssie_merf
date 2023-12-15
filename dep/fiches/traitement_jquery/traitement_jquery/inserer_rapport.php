<?php 
//var_dump($_POST);
require_once '../api/Fonctions.php';	
if($_POST)
{$Nom_View="";$Structure_View="";
$t=time();
	extract($_POST);
  if(isset($nom_rapport) AND isset($select_classeur) AND isset($select_feuille) AND isset($input_regrouper_par) AND isset($input_valeur) AND isset($operation))
  {foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE Code_Feuille='.$select_feuille) as $row4)
	{$Structure_View.="CREATE VIEW ".str_replace("t", "vw_r", $row4["Table_Feuille"])."_".$t." AS SELECT ";

	foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE (Code_Feuille='.$select_feuille." AND Nom_Ligne='".$input_regrouper_par."')") as $row6)
		{$Structure_View.=$row6["Nom_Collone"]." AS ".$row6["Nom_Collone"].", ";}

	$Structure_View.=strtoupper($operation)."(";

	foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE (Code_Feuille='.$select_feuille." AND Nom_Ligne='".$input_valeur."')") as $row5)
		{$Structure_View.=$row5["Nom_Collone"].") AS ".$row5["Nom_Collone"];}




	$Structure_View.=" FROM ".str_replace("t", "v", $row4["Table_Feuille"])." WHERE Stat=1 GROUP BY ";

foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE (Code_Feuille='.$select_feuille." AND Nom_Ligne='".$input_regrouper_par."')") as $row7)
		{$Structure_View.=$row7["Nom_Collone"];}

	PC_Enregistrer_Code($Structure_View);
	PC_Enregistrer_Code("INSERT INTO t_rapport(Nom_Rapport, Code_Feuille, Group_By, Valeur, Operation, Autres_Colonnes, Critere_A_Inclure, Critere_A_Exclure, Nom_View, Structure_View) VALUES ('".$nom_rapport."', $select_feuille, '".$input_regrouper_par."', '".$input_valeur."', '".$operation."', '', '', '', '".str_replace("t", "vw_r", $row4["Table_Feuille"])."_".$t."', '')");

	}


  }
  /*echo $Structure_View;
  echo "<br>";
  echo "INSERT INTO t_rapport(Nom_Rapport, Code_Feuille, Group_By, Valeur, Operation, Autres_Colonnes, Critere_A_Inclure, Critere_A_Exclure, Nom_View, Structure_View) VALUES ('".$nom_rapport."', $select_feuille, '".$input_regrouper_par."', '".$input_valeur."', '".$operation."', '', '', '', '".str_replace("t", "vw_r", $row4["Table_Feuille"])."', '')";*/
}
 ?>
