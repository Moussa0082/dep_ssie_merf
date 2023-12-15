<?php
if($_GET){
  extract($_GET);
require_once '../fonctions/php/Fonctions.php';
  foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE Code_Feuille='.$feuille) as $row4)
  {echo '<div class="row">
<div class="col-sm-1 col-md-1 mb-1"><label>Nom Feuille<font style="color: red" >*</font></label></div>
<div class="col-sm-5 col-md-5 mb-5"><input type="text" readonly required class="form-control" placeholder="Nom feuille" name="Nom_Feuille" value="'.$row4['Nom_Feuille'].'" id="Nom_Feuille"></div>
<input type="hidden" name="Code_Feuille" value="'.$feuille.'">
<div class="col-sm-1 col-md-1 mb-1"><label> Libelle feuille<font style="color: red" >*</font></label></div>
<div class="col-sm-4 col-md-4 mb-4"><textarea required class="form-control" readonly placeholder="Libelle" name="Libelle_Feuille" id="Libelle_Feuille">'.$row4['Libelle_Feuille'].'</textarea></div><input type="hidden" name="Code_Classeur" value="'.base64_decode($classeur).'"><div class="col-sm-1 col-md-1 mb-1"></div></div><br><div class="row"><div class="row"><div class="col-sm-1 col-md-1 mb-1"></div>
<div class="col-sm-2 col-md-2 mb-2"><label> Nom Colonne </label></div><div class="col-sm-2 col-md-1.5 mb-2"><label> Libelle Colonne </label></div><div class="col-sm-2 col-md-2 mb-2"><label> Type Colonne </label></div><div class="col-sm-2 col-md-2 mb-2"><label > Requis </label></div><div class="col-sm-2 col-md-2 mb-2"><label > Afficher</label></div><div class="col-sm-1 col-md-1 mb-1"></div><div class="col-sm-1 col-md-1 mb-1"></div></div>
<div id="Div_Deplacer_Colonne">
';
$Compte=0;
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne INNER JOIN t_feuille ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE t_feuille_ligne.Code_Feuille='.$feuille." ORDER BY Rang") as $row5) 
{$Compte++;
  echo '<div class=" div_ligne_feuille" id="div_ligne_feuille_">
  <div class="row">
<div class="col-sm-1 col-md-1 mb-1">
<div class="btn btn-xs btn-default" title="Deplacer" style="cursor: move">
<span class="glyphicon glyphicon-move"></span>
<input type="hidden" name="Code_Feuille_Ligne[]" value="'.$row5['Code_Feuille_Ligne'].'">
</div></div>
  <div class="col-sm-2 col-md-2 mb-2">
<input type="text" readonly class="form-control" required placeholder="Nom Colonne" value="'.$row5['Nom_Ligne'].'"></div><div class="col-sm-2 col-md-1.5 mb-2"><input type="text" readonly class="form-control" required placeholder="Libelle" value="'.$row5['Libelle_Ligne'].'"></div><div class="col-sm-2 col-md-2 mb-2">
<input type="text" class="form-control" value="'.$row5['Type_Ligne'].'" readonly></div>';

echo'
<div class="col-sm-2 col-md-2 mb-2">
<input type="text" class="form-control" readonly value="'.$row5['Requis'].'">
</div>';
echo '<div class="col-sm-2 col-md-2 mb-2">
<input type="text" class="form-control" placeholder="" readonly required value="'.$row5['Afficher'].'"></div>';

echo '<div class="col-sm-1 col-md-1 mb-1"></div>

</div>
<div class="row" id="Under_Div_">';
switch ($row5['Type_Ligne'])
{
  case 'CHOIX':
    echo '<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text" readonly class="form-control" required placeholder="Sepaper les valeurs par point-virgule \';\' Exemple: HOMME ; FEMME" value="';
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_feuille_etrangere ON (t_feuille.Table_Feuille=t_feuille_etrangere.Nom_Table) WHERE (t_feuille_etrangere.Nom_Table=\''.$row4['Table_Feuille'].'\' AND t_feuille_etrangere.Nom_Colonne=\''.$row5['Nom_Collone'].'\')') as $row8) 
{echo $row8['Valeur'];}
    echo '"></div>
<input type="hidden"  >
';
    break;

  case 'SOMME': 
echo '<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text" class="form-control"  style="font-size:12px" readonly required placeholder="Sepaper les noms de colonne par point-virgule \';\'  Colonne1+Colonne2 : Colonne1 ; Colonne2"  value="';
$Valeur="";
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_feuille_etrangere ON (t_feuille.Table_Feuille=t_feuille_etrangere.Nom_Table) WHERE (t_feuille_etrangere.Nom_Table=\''.$row4['Table_Feuille'].'\' AND t_feuille_etrangere.Nom_Colonne=\''.$row5['Nom_Collone'].'\')') as $row8) 
{$Valeur= $row8['Valeur'];}
echo $Valeur;
echo '"></div>
<input type="hidden" >';
      break;
  
  case 'DIFFERENCE': 
echo '<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text"  style="font-size:12px" class="form-control" readonly required placeholder="Sepaper les noms de colonne par point-virgule \';\' Colonne1-Colonne2 : Colonne1 ; Colonne2"  value="';

$Valeur="";
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_feuille_etrangere ON (t_feuille.Table_Feuille=t_feuille_etrangere.Nom_Table) WHERE (t_feuille_etrangere.Nom_Table=\''.$row4['Table_Feuille'].'\' AND t_feuille_etrangere.Nom_Colonne=\''.$row5['Nom_Collone'].'\')') as $row8) 
{$Valeur= $row8['Valeur'];}
echo $Valeur;
echo '"></div>
<input type="hidden" >';
      break;
  
  case 'PRODUIT': 
echo '<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text"  style="font-size:12px" class="form-control" readonly required placeholder="Sepaper les noms de colonne par point-virgule \';\' Colonne1*Colonne2 : Colonne1 ; Colonne2"  value="';
$Valeur="";
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_feuille_etrangere ON (t_feuille.Table_Feuille=t_feuille_etrangere.Nom_Table) WHERE (t_feuille_etrangere.Nom_Table=\''.$row4['Table_Feuille'].'\' AND t_feuille_etrangere.Nom_Colonne=\''.$row5['Nom_Collone'].'\')') as $row8) 
{$Valeur= $row8['Valeur'];}
echo $Valeur;

echo '"></div>
<input type="hidden" >';
      break;
  
  case 'RAPPORT': 
echo '<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text"  style="font-size:12px" class="form-control" readonly required placeholder="Sepaper les noms de colonne par point-virgule \';\' Colonne1/Colonne2 : Colonne1 ; Colonne2"  value="';
$Valeur="";
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_feuille_etrangere ON (t_feuille.Table_Feuille=t_feuille_etrangere.Nom_Table) WHERE (t_feuille_etrangere.Nom_Table=\''.$row4['Table_Feuille'].'\' AND t_feuille_etrangere.Nom_Colonne=\''.$row5['Nom_Collone'].'\')') as $row8) 
{$Valeur= $row8['Valeur'];}
echo $Valeur;

echo '"></div><input type="hidden" >';
      break;
  
  case 'MOYENNE':
echo '<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text"  style="font-size:12px" class="form-control" readonly required placeholder="Sepaper les noms de colonne par point-virgule \';\' Colonne1,Colonne2,Colonne3 : Colonne1 ; Colonne2 ; Colonne3"  value="';
$Valeur="";
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_feuille_etrangere ON (t_feuille.Table_Feuille=t_feuille_etrangere.Nom_Table) WHERE (t_feuille_etrangere.Nom_Table=\''.$row4['Table_Feuille'].'\' AND t_feuille_etrangere.Nom_Colonne=\''.$row5['Nom_Collone'].'\')') as $row8) 
{$Valeur= $row8['Valeur'];}
echo $Valeur;

echo '"></div><input type="hidden" >';    
    break;

    case 'FEUILLE':
    echo '<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-3 col-md-3 mb-3"><select name="" id="Col_Nom2_'.$Compte.'" readonly  id="" required class="form-control"><option value="0">Feuille</option>';
  $Valeur="";
  foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_feuille_etrangere ON (t_feuille.Table_Feuille=t_feuille_etrangere.Nom_Table) WHERE (t_feuille_etrangere.Nom_Table=\''.$row4['Table_Feuille'].'\' AND t_feuille_etrangere.Nom_Colonne=\''.$row5['Nom_Collone'].'\')') as $row8) 
{$Valeur= $row8['Valeur'];}
$Table_Choix=null;
$Table_Choix=explode(';', $Valeur);
foreach (FC_Rechercher('t_feuille') AS $row8) 
{if(trim($Table_Choix[0])==$row8['Code_Feuille']){echo '<option value="'.$row8['Code_Feuille'].'" selected>'.$row8['Nom_Feuille'].'</option>';}
else{echo '<option value="'.$row8['Code_Feuille'].'">'.$row8['Nom_Feuille'].'</option>';}}
echo '</select></div><div class="col-sm-3 col-md-3 mb-3"><select readonly name=""  id="Col_Nom_'.$Compte.'" required class="form-control"><option value="">Champ</option>';
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE Code_Feuille='.$Table_Choix[0]) AS $row8) 
{if(trim($Table_Choix[1])==$row8['Code_Feuille_Ligne']){echo '<option value="'.$row8['Code_Feuille_Ligne'].'" selected>'.$row8['Nom_Ligne'].'</option>';}
else{echo '<option value="'.$row8['Code_Feuille_Ligne'].'">'.$row8['Nom_Ligne'].'</option>';}}
echo '</select></div>
<input type="hidden" >
';
    break;
  
  default:
    echo '<input type="hidden">';
    break;
}
echo '</div><br></div>';
}

$Compte=0;

echo '';
}
?>

<?php  } ?>