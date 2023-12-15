<?php
//var_dump($_POST);
require_once '../api/Fonctions.php';
if($_POST)
{$Nom_View="";$Structure_View=""; $Nom_View_FP=""; $Nom_View_FS=""; $FEUILLE_JOINTURE="NULL";
$t=time();
$Struc_ANNEE="";
$input_regrouper_par_val="";
	extract($_POST);
	if(isset($_GET["action"]) AND $_GET["action"]=="modif"){
foreach (FC_Rechercher_Code('SELECT * FROM t_rapport_indicateur WHERE Code_Rapport='.base64_decode($Code_Rapport)) as $row44)
{PC_Enregistrer_Code("DELETE FROM t_rapport_indicateur WHERE Code_Rapport=".base64_decode($Code_Rapport));
 PC_Enregistrer_Code("DROP VIEW ".$row44["Nom_View"]);}
}
  if(isset($nom_rapport) AND isset($select_classeur) AND isset($select_feuille) AND isset($input_regrouper_par) AND isset($input_valeur) AND isset($operation) AND isset($affichage))
  {foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE Code_Feuille='.$select_feuille) as $row4)
	{$Nom_View_FP=str_replace("t", "v", $row4["Table_Feuille"]);
		$Structure_View.="CREATE VIEW ".str_replace("t", "vw_r", $row4["Table_Feuille"])."_".$t." AS SELECT ";
	for ($i=0; $i <count($input_regrouper_par) ; $i++) 
	{ 
		
	$input_regrouper_par_val.=$input_regrouper_par[$i].';';

	if($input_regrouper_par[$i]=="ANNEE")
		{
			$Struc_ANNEE = "YEAR(".$Nom_View_FP.".Date_Insertion) AS ANNEE, ";

			foreach (FC_Rechercher_Code("SELECT * FROM `t_feuille_ligne` WHERE (`Code_Feuille` = $select_feuille AND `Nom_Ligne`='Date Collecte' AND `Type_Ligne`='DATE') LIMIT 1") as $key) 
			{$Struc_ANNEE = "YEAR(".$Nom_View_FP.".".$key["Nom_Collone"].") AS ANNEE, ";}

			$Structure_View.=$Struc_ANNEE; 

		}
	else{
		/*foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE (Code_Feuille='.$select_feuille." AND Nom_Ligne='".$input_regrouper_par."')") as $row6)
		{$Structure_View.=$Nom_View_FP.".".$row6["Nom_Collone"]." AS ".$row6["Nom_Collone"].", ";}*/
		$Structure_View.=$input_regrouper_par[$i]." AS `".$input_regrouper_par[$i]."`, ";
		}
	}
	$input_regrouper_par_val = rtrim($input_regrouper_par_val,";");
	$Structure_View.=strtoupper($operation)."(";

	/*foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE (Code_Feuille='.$select_feuille." AND Nom_Ligne='".$input_valeur."')") as $row5)
		{$Structure_View.=$Nom_View_FP.".".$row5["Nom_Collone"].") AS Valeur";}*/

		$Structure_View.=$input_valeur.") AS Valeur";




	$Structure_View.=" FROM ".str_replace("t", "v", $row4["Table_Feuille"]);

	if(isset($_POST["feuille_jointure"]) AND !empty($_POST["feuille_jointure"]))
{$FEUILLE_JOINTURE=$feuille_jointure;

foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE Code_Feuille='.$feuille_jointure) as $row10)
	{$Nom_View_FS=str_replace("t", "v", $row10["Table_Feuille"]);}

	$Structure_View.=" INNER JOIN $Nom_View_FS ON(";
/*foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE (Code_Feuille='.$select_feuille." AND Nom_Ligne='".$attribut_jointure_fp."')") as $row8)
		{$Structure_View.=$Nom_View_FP.".".$row8["Nom_Collone"];}*/
$Structure_View.=$attribut_jointure_fp;
$Structure_View.=" = ";

/*foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE (Code_Feuille='.$feuille_jointure." AND Nom_Ligne='".$attribut_jointure_fs."')") as $row9)
		{$Structure_View.=$Nom_View_FS.".".$row9["Nom_Collone"];}*/
$Structure_View.=$attribut_jointure_fs;
$Structure_View.=") ";

}
	else
	{}

	$Structure_View.=" WHERE (($Nom_View_FP.Stat=1) ";
	if(isset($champ_criteres)){
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
	}}

	$Structure_View.=" ";

	$Structure_View.=") ";
	
if(count($input_regrouper_par)>0)
{$Structure_View.=" GROUP BY ";
for ($i=0; $i <count($input_regrouper_par) ; $i++) 
	{ 
	if($input_regrouper_par[$i]=="ANNEE"){$Structure_View.= str_replace(" AS ANNEE,", "", $Struc_ANNEE);}

	else {
		/*foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE (Code_Feuille='.$select_feuille." AND Nom_Ligne='".$input_regrouper_par."')") as $row7)
		{$Structure_View.=$Nom_View_FP.".".$row7["Nom_Collone"];}*/
		//$Structure_View.=$Nom_View_FP.".".$input_regrouper_par[$i];
		$Structure_View.=$input_regrouper_par[$i];
		}
	if(($i+1) < count($input_regrouper_par)){$Structure_View.=', ';}
	}
}


	PC_Enregistrer_Code($Structure_View);
	PC_Enregistrer_Code("INSERT INTO t_rapport_indicateur(Nom_Rapport, Code_Feuille, Group_By, Valeur, Operation,  Nom_View, Structure_View, Feuille_Jointure, Attribut_Jointure_FP, Attribut_Jointure_FS, Id_Projet, Affichage, Indicateur) VALUES (NULL, $select_feuille, '".$input_regrouper_par_val."', '".$input_valeur."', '".$operation."', '".str_replace("t", "vw_r", $row4["Table_Feuille"])."_".$t."', '', $FEUILLE_JOINTURE, '$attribut_jointure_fp', '$attribut_jointure_fs', '".$_SESSION['clp_projet']."', '$affichage','".$nom_rapport."')");
$IND=0;
	foreach (FC_Rechercher_Code("SELECT MAX(Code_Rapport) AS MAX FROM t_rapport_indicateur") AS $row11) 
	{
		if(isset($CRITERES_TAB)){foreach ($CRITERES_TAB as $k1) {PC_Enregistrer_Code(str_replace("XXXXX", $row11["MAX"], $CRITERES_TAB[$IND])); $IND++;}}

	}

	}


  }
  //echo $Structure_View;
  //echo "<br>";
  //echo "INSERT INTO t_rapport(Nom_Rapport, Code_Feuille, Group_By, Valeur, Operation, Autres_Colonnes, Critere_A_Inclure, Critere_A_Exclure, Nom_View, Structure_View) VALUES ('".$nom_rapport."', $select_feuille, '".$input_regrouper_par_val."', '".$input_valeur."', '".$operation."', '', '', '', '".str_replace("t", "vw_r", $row4["Table_Feuille"])."', '')";
}
 ?>
