<?php
if($_GET){
  extract($_GET);
require_once '../api/Fonctions.php';
  foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE Code_Feuille='.$feuille) as $row4)
  {echo '<div class="row">
<div class="col-sm-1 col-md-1 mb-1"><label>Nom Feuille<font style="color: red" >*</font></label></div>
<div class="col-sm-5 col-md-5 mb-5"><input readonly type="text" required class="form-control" placeholder="Nom feuille" name="Nom_Feuille" value="'.$row4['Nom_Feuille'].'" id="Nom_Feuille"></div>
<input type="hidden" name="Code_Feuille" value="'.$feuille.'">
<div class="col-sm-1 col-md-1 mb-1"><label> Libelle feuille<font style="color: red" >*</font></label></div>
<div class="col-sm-4 col-md-4 mb-4"><textarea required class="form-control" placeholder="Libelle" name="Libelle_Feuille" id="Libelle_Feuille" readonly>'.$row4['Libelle_Feuille'].'</textarea></div><input type="hidden" name="Code_Classeur" value="'.base64_decode($classeur).'"><div class="col-sm-1 col-md-1 mb-1"></div></div><br><div class="row"><div class="col-sm-1 col-md-1 mb-1"><label>Nb. ligne (impr.)<font style="color: red" >*</font></label></div><div class="col-sm-2 col-md-2 mb-2"><input type="number" min="1" step="1" readonly value="'.$row4['Nb_Ligne_Impr'].'" required class="form-control" placeholder="Nombre de ligne à imprimer" name="Nb_Ligne_Impr" id="Nb_Ligne_Impr"></div><div class="col-sm-1 col-md-1 mb-1"><label> Icone<font style="color: red" >*</font> </label></div><div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-1 col-md-1 mb-1"><label> Note<font style="color: red" >*</font></label></div><div class="col-sm-4 col-md-4 mb-4"><textarea readonly class="form-control" placeholder="Note" name="Note" id="Note">'.$row4['Note'].'</textarea></div><div class="col-sm-1 col-md-1 mb-1"></div></div><br><div class="row">

<div class="col-sm-2 col-md-2 mb-2"><label> Formulaire </label></div>

<div class="col-sm-10 col-md-10 mb-10"><label> Colonnes </label></div>

</div>';

foreach (FC_Rechercher_Code('SELECT DISTINCT Formulaire FROM t_feuille_ligne INNER JOIN t_feuille ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE t_feuille_ligne.Code_Feuille='.$feuille." ORDER BY Formulaire") as $row9) 
{echo '<div class="row">
<div class="col-sm-2 col-md-2 mb-2">
<div class="btn btn-xs btn-default" title="Supprimer" >';

if($row9['Formulaire']==0)
{echo '<input type="text" readonly value="'.$row9['Formulaire'].'" class="form-control" name="Formulaires[]">';}
else
{echo '<input type="number" min="1" step="1" value="'.$row9['Formulaire'].'" class="form-control" name="Formulaires[]">';}

echo '</div></div>

  <div class="col-sm-10 col-md-10 mb-10">
<script src="js/jquery.js"></script>
<select class="selectpicker bts_select" multiple data-live-search="true">
  <option>Mustard</option>
  <option>Ketchup</option>
  <option>Relish</option>
</select>
  </div>
';



 echo '</div>';

}
$Compte=0;
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne INNER JOIN t_feuille ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE t_feuille_ligne.Code_Feuille='.$feuille." ORDER BY Rang") as $row5) 
{$Compte++;
  echo '<div class=" div_ligne_feuille" id="div_ligne_feuille_'.$Compte.'">
  <div class="row">
<div class="col-sm-2 col-md-2 mb-2">
<div class="btn btn-xs btn-default" title="Supprimer" >
<input type="number" min="1" step="1" value="'.$row5['Formulaire'].'" class="form-control" name="disposition[]">
';


echo '
<input type="hidden" name="Code_Feuille_Ligne[]" value="'.$row5['Code_Feuille_Ligne'].'">
</select>
</div></div>
  <div class="col-sm-2 col-md-2 mb-2">
<input type="text" readonly class="form-control" required placeholder="Nom Colonne" name="nom_Ligne[]" id="nom_Ligne[]" value="'.$row5['Nom_Ligne'].'"></div><div class="col-sm-2 col-md-1.5 mb-2"><input type="text" readonly class="form-control" required placeholder="Libelle" name="libelle_Ligne[]" id="libelle_Ligne[]" value="'.$row5['Libelle_Ligne'].'"></div><div class="col-sm-2 col-md-2 mb-2">
<input type="text" class="form-control" value="'.$row5['Type_Ligne'].'" readonly></div>';

echo'
<div class="col-sm-2 col-md-2 mb-2">
<input type="text" class="form-control" readonly value="'.$row5['Requis'].'">
</div>';
echo '<div class="col-sm-2 col-md-2 mb-2">
<input type="text" class="form-control" placeholder="" readonly required value="'.$row5['Afficher'].'"></div>';

echo '<div class="col-sm-1 col-md-1 mb-1"></div>

</div>
<div class="row" id="Under_Div_'.$Compte.'">';
switch ($row5['Type_Ligne'])
{
  case 'CHOIX':
    echo '<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text" readonly class="form-control" required placeholder="Sepaper les valeurs par point-virgule \';\' Exemple: HOMME ; FEMME"  onkeyup="document.getElementById(\'Valeur_'.$Compte.'\').value=this.value" value="';
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_feuille_etrangere ON (t_feuille.Table_Feuille=t_feuille_etrangere.Nom_Table) WHERE (t_feuille_etrangere.Nom_Table=\''.$row4['Table_Feuille'].'\' AND t_feuille_etrangere.Nom_Colonne=\''.$row5['Nom_Collone'].'\')') as $row8) 
{echo $row8['Valeur'];}
    echo '"></div>
<input type="hidden" value="'.$row8['Valeur'].'" name="valeur[]" id="Valeur_"'.$Compte.'>
';
    break;

  case 'SOMME': 
echo '<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text" class="form-control"  style="font-size:12px" readonly required placeholder="Sepaper les noms de colonne par point-virgule \';\'  Colonne1+Colonne2 : Colonne1 ; Colonne2"  onkeyup="document.getElementById(\'Valeur_'.$Compte.'\').value=this.value" value="';
$Valeur="";
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_feuille_etrangere ON (t_feuille.Table_Feuille=t_feuille_etrangere.Nom_Table) WHERE (t_feuille_etrangere.Nom_Table=\''.$row4['Table_Feuille'].'\' AND t_feuille_etrangere.Nom_Colonne=\''.$row5['Nom_Collone'].'\')') as $row8) 
{$Valeur= $row8['Valeur'];}
echo $Valeur;
echo '"></div>
<input type="hidden" value="'.$Valeur.'" name="valeur[]" id="Valeur_"'.$Compte.'>';
      break;
  
  case 'DIFFERENCE': 
echo '<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text"  style="font-size:12px" class="form-control" readonly required placeholder="Sepaper les noms de colonne par point-virgule \';\' Colonne1-Colonne2 : Colonne1 ; Colonne2"  onkeyup="document.getElementById(\'Valeur_'.$Compte.'\').value=this.value" value="';

$Valeur="";
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_feuille_etrangere ON (t_feuille.Table_Feuille=t_feuille_etrangere.Nom_Table) WHERE (t_feuille_etrangere.Nom_Table=\''.$row4['Table_Feuille'].'\' AND t_feuille_etrangere.Nom_Colonne=\''.$row5['Nom_Collone'].'\')') as $row8) 
{$Valeur= $row8['Valeur'];}
echo $Valeur;
echo '"></div>
<input type="hidden" value="'.$Valeur.'" name="valeur[]" id="Valeur_"'.$Compte.'>';
      break;
  
  case 'PRODUIT': 
echo '<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text"  style="font-size:12px" class="form-control" readonly required placeholder="Sepaper les noms de colonne par point-virgule \';\' Colonne1*Colonne2 : Colonne1 ; Colonne2"  onkeyup="document.getElementById(\'Valeur_'.$Compte.'\').value=this.value" value="';
$Valeur="";
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_feuille_etrangere ON (t_feuille.Table_Feuille=t_feuille_etrangere.Nom_Table) WHERE (t_feuille_etrangere.Nom_Table=\''.$row4['Table_Feuille'].'\' AND t_feuille_etrangere.Nom_Colonne=\''.$row5['Nom_Collone'].'\')') as $row8) 
{$Valeur= $row8['Valeur'];}
echo $Valeur;

echo '"></div>
<input type="hidden" value="'.$Valeur.'" name="valeur[]" id="Valeur_"'.$Compte.'>';
      break;
  
  case 'RAPPORT': 
echo '<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text"  style="font-size:12px" class="form-control" readonly required placeholder="Sepaper les noms de colonne par point-virgule \';\' Colonne1/Colonne2 : Colonne1 ; Colonne2"  onkeyup="document.getElementById(\'Valeur_'.$Compte.'\').value=this.value" value="';
$Valeur="";
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_feuille_etrangere ON (t_feuille.Table_Feuille=t_feuille_etrangere.Nom_Table) WHERE (t_feuille_etrangere.Nom_Table=\''.$row4['Table_Feuille'].'\' AND t_feuille_etrangere.Nom_Colonne=\''.$row5['Nom_Collone'].'\')') as $row8) 
{$Valeur= $row8['Valeur'];}
echo $Valeur;

echo '"></div><input type="hidden" value="'.$Valeur.'" name="valeur[]" id="Valeur_"'.$Compte.'>';
      break;
  
  case 'MOYENNE':
echo '<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text"  style="font-size:12px" class="form-control" readonly required placeholder="Sepaper les noms de colonne par point-virgule \';\' Colonne1,Colonne2,Colonne3 : Colonne1 ; Colonne2 ; Colonne3"  onkeyup="document.getElementById(\'Valeur_'.$Compte.'\').value=this.value" value="';
$Valeur="";
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_feuille_etrangere ON (t_feuille.Table_Feuille=t_feuille_etrangere.Nom_Table) WHERE (t_feuille_etrangere.Nom_Table=\''.$row4['Table_Feuille'].'\' AND t_feuille_etrangere.Nom_Colonne=\''.$row5['Nom_Collone'].'\')') as $row8) 
{$Valeur= $row8['Valeur'];}
echo $Valeur;

echo '"></div><input type="hidden" value="'.$Valeur.'" name="valeur[]" id="Valeur_"'.$Compte.'>';    
    break;

    case 'FEUILLE':
    echo '<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-3 col-md-3 mb-3"><select name="" id="Col_Nom2_'.$Compte.'" readonly onchange="Charger_Col_Nom(this.value, \'Col_Nom_'.$Compte.'\')" id="" required class="form-control"><option value="0">Feuille</option>';
  $Valeur="";
  foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_feuille_etrangere ON (t_feuille.Table_Feuille=t_feuille_etrangere.Nom_Table) WHERE (t_feuille_etrangere.Nom_Table=\''.$row4['Table_Feuille'].'\' AND t_feuille_etrangere.Nom_Colonne=\''.$row5['Nom_Collone'].'\')') as $row8) 
{$Valeur= $row8['Valeur'];}
$Table_Choix=null;
$Table_Choix=explode(';', $Valeur);
foreach (FC_Rechercher('t_feuille') AS $row8) 
{if(trim($Table_Choix[0])==$row8['Code_Feuille']){echo '<option value="'.$row8['Code_Feuille'].'" selected>'.$row8['Nom_Feuille'].'</option>';}
else{echo '<option value="'.$row8['Code_Feuille'].'">'.$row8['Nom_Feuille'].'</option>';}}
echo '</select></div><div class="col-sm-3 col-md-3 mb-3"><select readonly name="" onchange="document.getElementById(\'Valeur_'.$Compte.'\').value=(document.getElementById(\'Col_Nom2_'.$Compte.'\').value+\';\'+this.value)"  id="Col_Nom_'.$Compte.'" required class="form-control"><option value="">Champ</option>';
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE Code_Feuille='.$Table_Choix[0]."  ORDER BY Rang") AS $row8) 
{if(trim($Table_Choix[1])==$row8['Code_Feuille_Ligne']){echo '<option value="'.$row8['Code_Feuille_Ligne'].'" selected>'.$row8['Nom_Ligne'].'</option>';}
else{echo '<option value="'.$row8['Code_Feuille_Ligne'].'">'.$row8['Nom_Ligne'].'</option>';}}
echo '</select></div>
<input type="hidden" value="'.$Valeur.'" name="valeur[]" id="Valeur_"'.$Compte.'>
';
    break;
  
  default:
    echo '<input type="hidden" name="valeur[]" id="Valeur_"'.$Compte.'>';
    break;
}
echo '</div></div>';
}
?>

<?php } } ?>