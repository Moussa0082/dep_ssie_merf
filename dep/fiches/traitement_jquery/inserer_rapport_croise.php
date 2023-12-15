<?php 
var_dump($_POST);
require_once '../api/Fonctions.php';	
if($_POST)
{$Nom_View="";$Structure_View=""; $Nom_View_FP=""; $Nom_View_FS=""; $FEUILLE_JOINTURE="NULL";
$t=time();
	extract($_POST);
	if(isset($_GET["action"]) AND $_GET["action"]=="modif")
		{
foreach (FC_Rechercher_Code('SELECT * FROM t_rapport WHERE Code_Rapport='.base64_decode($Code_Rapport)) as $row44)
{PC_Enregistrer_Code("DELETE FROM t_rapport WHERE Code_Rapport=".base64_decode($Code_Rapport));
 PC_Enregistrer_Code("DROP VIEW ".$row44["Nom_View"]);}
}
  if(isset($nom_rapport) AND isset($select_classeur) AND isset($select_feuille) AND isset($colonne_x) AND isset($colonne_y) AND isset($input_valeur) AND isset($operation))
  {foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE Code_Feuille='.$select_feuille) as $row4)
	{$Nom_View_FP=str_replace("t", "v", $row4["Table_Feuille"]);
		$Structure_View.="CREATE VIEW ".str_replace("t", "vw_r", $row4["Table_Feuille"])."_".$t." AS SELECT ";

	/*foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE (Code_Feuille='.$select_feuille." AND Nom_Ligne='".$colonne_x."')") as $row6)
		{$Structure_View.=$Nom_View_FP.".".$row6["Nom_Collone"]." AS ".$row6["Nom_Collone"].", ";}*/
		$Structure_View.=$colonne_x." AS `".$colonne_x."`, ";

	/*foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE (Code_Feuille='.$select_feuille." AND Nom_Ligne='".$colonne_y."')") as $row12)
		{$Structure_View.=$Nom_View_FP.".".$row12["Nom_Collone"]." AS ".$row12["Nom_Collone"].", ";}*/
		$Structure_View.=$colonne_y." AS `".$colonne_y."`, ";

	$Structure_View.=strtoupper($operation)."(";

	/*foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE (Code_Feuille='.$select_feuille." AND Nom_Ligne='".$input_valeur."')") as $row5)
		{$Structure_View.=$Nom_View_FP.".".$row5["Nom_Collone"].") AS valeur";}*/
		//{$Structure_View.=$Nom_View_FP.".".$row5["Nom_Collone"].") AS ".$row5["Nom_Collone"];}

		$Structure_View.=$input_valeur.") AS valeur";




	$Structure_View.=" FROM ".str_replace("t", "v", $row4["Table_Feuille"]);

	if(isset($_POST["feuille_jointure"]) AND !empty($_POST["feuille_jointure"]))
{$FEUILLE_JOINTURE=$feuille_jointure;

foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE Code_Feuille='.$feuille_jointure) as $row10)
	{$Nom_View_FS=str_replace("t", "v", $row10["Table_Feuille"]);}

	$Structure_View.=" INNER JOIN $Nom_View_FS ON (";
/*foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE (Code_Feuille='.$select_feuille." AND Nom_Ligne='".$attribut_jointure_fp."')") as $row8){$Structure_View.=$Nom_View_FP.".".$row8["Nom_Collone"];}*/
$Structure_View.=$attribut_jointure_fp;

$Structure_View.=" = ";

/*foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE (Code_Feuille='.$feuille_jointure." AND Nom_Ligne='".$attribut_jointure_fs."')") as $row9){$Structure_View.=$Nom_View_FS.".".$row9["Nom_Collone"];}*/
$Structure_View.=$attribut_jointure_fs;	
$Structure_View.=") ";

}
	else
	{}

	$Structure_View.=" WHERE (($Nom_View_FP.Stat=1) ";

	for($i=0; $i<count($champ_criteres); $i++)
	{
		if(!empty(trim($valeur_criteres[$i])))
		{if($et_ou_criteres[$i]=="ET"){$Structure_View.=" AND ";}
		 else{$Structure_View.=" OR ";}
			switch ($condition_criteres[$i]) {
				case '=': $Structure_View.=" (".$champ_criteres[$i]." LIKE '".trim($valeur_criteres[$i])."') " ; 
				$CRITERES_TAB[]="INSERT INTO t_rapport_critere(Code_Rapport, Critere_Colonne, Critere_Condition, Critere_Valeur, Critere_ET_OU) VALUES (XXXXX , '$champ_criteres[$i]', '=', '".trim($valeur_criteres[$i])."', '$et_ou_criteres[$i]')";
				break;
				case '>': $Structure_View.=" (".$champ_criteres[$i]."> '".trim($valeur_criteres[$i])."') " ; 
				$CRITERES_TAB[]="INSERT INTO t_rapport_critere(Code_Rapport, Critere_Colonne, Critere_Condition, Critere_Valeur, Critere_ET_OU) VALUES (XXXXX , '$champ_criteres[$i]', '>', '".trim($valeur_criteres[$i])."', '$et_ou_criteres[$i]')";
				break;
				case '<': $Structure_View.=" (".$champ_criteres[$i]."< '".trim($valeur_criteres[$i])."') " ; 
				$CRITERES_TAB[]="INSERT INTO t_rapport_critere(Code_Rapport, Critere_Colonne, Critere_Condition, Critere_Valeur, Critere_ET_OU) VALUES (XXXXX , '$champ_criteres[$i]', '<', '".trim($valeur_criteres[$i])."', '$et_ou_criteres[$i]')";
				break;
				case '>=': $Structure_View.=" (".$champ_criteres[$i].">= '".trim($valeur_criteres[$i])."') " ; 
				$CRITERES_TAB[]="INSERT INTO t_rapport_critere(Code_Rapport, Critere_Colonne, Critere_Condition, Critere_Valeur, Critere_ET_OU) VALUES (XXXXX , '$champ_criteres[$i]', '>=', '".trim($valeur_criteres[$i])."', '$et_ou_criteres[$i]')";
				break;
				case '<=': $Structure_View.=" (".$champ_criteres[$i]."<= '".trim($valeur_criteres[$i])."') " ; 
				$CRITERES_TAB[]="INSERT INTO t_rapport_critere(Code_Rapport, Critere_Colonne, Critere_Condition, Critere_Valeur, Critere_ET_OU) VALUES (XXXXX , '$champ_criteres[$i]', '<=', '".trim($valeur_criteres[$i])."', '$et_ou_criteres[$i]')";
				break;
				case '<>': $Structure_View.=" (".$champ_criteres[$i]."<> '".trim($valeur_criteres[$i])."') " ; 
				$CRITERES_TAB[]="INSERT INTO t_rapport_critere(Code_Rapport, Critere_Colonne, Critere_Condition, Critere_Valeur, Critere_ET_OU) VALUES (XXXXX , '$champ_criteres[$i]', '<>', '".trim($valeur_criteres[$i])."', '$et_ou_criteres[$i]')";
				break;
				case '%x%': $Structure_View.=" (".$champ_criteres[$i]." LIKE '%".trim($valeur_criteres[$i])."%') " ; 
				$CRITERES_TAB[]="INSERT INTO t_rapport_critere(Code_Rapport, Critere_Colonne, Critere_Condition, Critere_Valeur, Critere_ET_OU) VALUES (XXXXX , '$champ_criteres[$i]', '%x%', '".trim($valeur_criteres[$i])."', '$et_ou_criteres[$i]')";
				break;
				case 'x%': $Structure_View.=" (".$champ_criteres[$i]." LIKE '".trim($valeur_criteres[$i])."%') " ; 
				$CRITERES_TAB[]="INSERT INTO t_rapport_critere(Code_Rapport, Critere_Colonne, Critere_Condition, Critere_Valeur, Critere_ET_OU) VALUES (XXXXX , '$champ_criteres[$i]', 'x%', '".trim($valeur_criteres[$i])."', '$et_ou_criteres[$i]')";
				break;
				case '%x': $Structure_View.=" (".$champ_criteres[$i]." LIKE '%".trim($valeur_criteres[$i])."') " ; 
				$CRITERES_TAB[]="INSERT INTO t_rapport_critere(Code_Rapport, Critere_Colonne, Critere_Condition, Critere_Valeur, Critere_ET_OU) VALUES (XXXXX , '$champ_criteres[$i]', '%x', '".trim($valeur_criteres[$i])."', '$et_ou_criteres[$i]')";
				break;
				
				default:
					# code...
					break;
			}
		}
	}

	$Structure_View.=" ";

	$Structure_View.=") GROUP BY ";
	

/*foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE (Code_Feuille='.$select_feuille." AND Nom_Ligne='".$colonne_x."')") as $row7)
		{$Structure_View.=$Nom_View_FP.".".$row7["Nom_Collone"];}*/
$Structure_View.=$colonne_x;


/*foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE (Code_Feuille='.$select_feuille." AND Nom_Ligne='".$colonne_y."')") as $row13)
		{$Structure_View.=", ".$Nom_View_FP.".".$row13["Nom_Collone"];}*/
$Structure_View.=", ".$colonne_y;

	PC_Enregistrer_Code($Structure_View);
	PC_Enregistrer_Code("INSERT INTO t_rapport(Nom_Rapport, Code_Feuille, Colonne_X ,Colonne_Y, Valeur, Operation,  Nom_View, Structure_View, Type_Rapport, Feuille_Jointure, Attribut_Jointure_FP, Attribut_Jointure_FS, Id_Projet) VALUES ('".$nom_rapport."', $select_feuille, '".$colonne_x."', '".$colonne_y."', '".$input_valeur."', '".$operation."', '".str_replace("t", "vw_r", $row4["Table_Feuille"])."_".$t."', '', 'CROISE', $FEUILLE_JOINTURE, '$attribut_jointure_fp', '$attribut_jointure_fs','".$_SESSION['clp_projet']."')");
$IND=0;
	foreach (FC_Rechercher_Code("SELECT MAX(Code_Rapport) AS MAX FROM t_rapport") AS $row11) 
	{foreach ($CRITERES_TAB as $k1) {PC_Enregistrer_Code(str_replace("XXXXX", $row11["MAX"], $CRITERES_TAB[$IND])); $IND++;}

	}

	}


  }
  echo $Structure_View;
  //echo "<br>";
  /*echo "INSERT INTO t_rapport(Nom_Rapport, Code_Feuille, Group_By, Valeur, Operation, Autres_Colonnes, Critere_A_Inclure, Critere_A_Exclure, Nom_View, Structure_View) VALUES ('".$nom_rapport."', $select_feuille, '".$input_regrouper_par."', '".$input_valeur."', '".$operation."', '', '', '', '".str_replace("t", "vw_r", $row4["Table_Feuille"])."', '')";*/
}
 ?>
