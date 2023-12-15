
<?php  
if($_GET){
extract($_GET);
require_once '../api/Fonctions.php';
$Filtre_Admin='';
    /*if(strtolower(trim($_SESSION["clp_id"])) == "admin"){$Filtre_Admin='';}
    else {$Filtre_Admin=" AND Login = '".$_SESSION["clp_id"]."' ";}*/
echo '<div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" class="close" data-dismiss="modal"><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></button>
               <center><h4 class="modal-title" id="note_fiche">Insertion des données </h4></center>
          </div>
          <br>
           <form action="traitement_jquery/traitement_auto_formulaire.php" method="POST" enctype="multipart/form-data" id="form_personnalise"><div class="modal-body">
             <div class="col-md-12 col-lg-12">
              
                <div class="panel panel-default" >
                    <div class="panel-heading">
                      <span class="text-primary">Formulaire</span> 
                    </div>
                    <div class="panel-body">';

foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne INNER JOIN t_feuille ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE (t_feuille_ligne.Code_Feuille='.$Code.' AND Type_Ligne NOT IN(\'SOMME\', \'DIFFERENCE\', \'PRODUIT\', \'RAPPORT\', \'MOYENNE\', \'COMPTER\')) ORDER BY Rang') AS $row8) 
{echo '<script type="text/javascript">document.getElementById("note_fiche").innerHTML="'.addslashes($row8['Note']).'";</script>';
  if($row8['Type_Ligne']!="SOMME" AND $row8['Type_Ligne']!="DIFFERENCE" AND $row8['Type_Ligne']!="PRODUIT" AND $row8['Type_Ligne']!="RAPPORT" AND $row8['Type_Ligne']!="MOYENNE" AND $row8['Type_Ligne']!="COMPTER" AND $row8['Type_Ligne']!="SIGNATURE"){echo '<div class="row">
<div class="col-sm-4 col-md-4 mb-4"><label>'.$row8['Libelle_Ligne'];
if($row8['Requis']=='Oui'){echo '<font style="color: red" >*</font>';}
echo'</label></div><div class="col-sm-8 col-md-8 mb-8">';}
switch ($row8['Type_Ligne'])
{
  case 'TEXT':
    echo '<textarea style="max-height:40px" class="form-control"';if($row8['Requis']=='Oui'){echo " required ";} echo ' name="'.$row8['Nom_Collone'].'" id=""></textarea>
    <input type="hidden" name="Colonne[]" value="'.$row8['Nom_Collone'].'">
    <input type="hidden" name="Type[]" value="'.$row8['Type_Ligne'].'">';
    break;

      case 'QRCODE':
    echo '<textarea style="max-height:40px" class="form-control"';if($row8['Requis']=='Oui'){echo " required ";} echo ' name="'.$row8['Nom_Collone'].'" id=""></textarea>
    <input type="hidden" name="Colonne[]" value="'.$row8['Nom_Collone'].'">
    <input type="hidden" name="Type[]" value="'.$row8['Type_Ligne'].'">';
    break;

  case 'INT':
    echo '<input type="number" min="0" step="1" class="form-control"';if($row8['Requis']=='Oui'){echo " required ";} echo ' name="'.$row8['Nom_Collone'].'" id="">
      <input type="hidden" name="Colonne[]" value="'.$row8['Nom_Collone'].'">
      <input type="hidden" name="Type[]" value="'.$row8['Type_Ligne'].'">
    ';
    break;

  case 'DOUBLE':
    echo '<input type="number" step="0.001" class="form-control"';if($row8['Requis']=='Oui'){echo " required ";} echo ' name="'.$row8['Nom_Collone'].'" id="">
<input type="hidden" name="Colonne[]" value="'.$row8['Nom_Collone'].'">
<input type="hidden" name="Type[]" value="'.$row8['Type_Ligne'].'">
    ';
    break;

   case 'DATE':
    echo '<input type="date" class="form-control"';if($row8['Requis']=='Oui'){echo " required ";} echo ' name="'.$row8['Nom_Collone'].'" id="">
<input type="hidden" name="Colonne[]" value="'.$row8['Nom_Collone'].'">
<input type="hidden" name="Type[]" value="'.$row8['Type_Ligne'].'">
    ';
    break;

     case 'COULEUR':
    echo '<input type="color" class="form-control"';if($row8['Requis']=='Oui'){echo " required ";} echo ' name="'.$row8['Nom_Collone'].'" id="">
<input type="hidden" name="Colonne[]" value="'.$row8['Nom_Collone'].'">
<input type="hidden" name="Type[]" value="'.$row8['Type_Ligne'].'">
    ';
    break;

    case 'CHOIX':
    echo '<SELECT class="form-control selectpicker bts_select " data-live-search="true" ';if($row8['Requis']=='Oui'){echo " required ";} echo ' name="'.$row8['Nom_Collone'].'" id="">';
  $Valeur="";
   foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_feuille_etrangere ON (t_feuille.Table_Feuille=t_feuille_etrangere.Nom_Table) WHERE (t_feuille_etrangere.Nom_Table=\''.$row8['Table_Feuille'].'\' AND t_feuille_etrangere.Nom_Colonne=\''.$row8['Nom_Collone'].'\')') AS $row9) 
{$Valeur=$row9['Valeur'];} 
$Table_Choix=null;
$Table_Choix=explode(';', $Valeur);
echo '<option value=""></option>';
for ($i=0; $i<count($Table_Choix); $i++) {echo '<option value="'.trim($Table_Choix[$i]).'">'.trim($Table_Choix[$i]).'</option>';}
    echo ' </select>
<input type="hidden" name="Colonne[]" value="'.$row8['Nom_Collone'].'">
<input type="hidden" name="Type[]" value="'.$row8['Type_Ligne'].'">
  ';
    break;

        case 'CHOIX MULTIPLES':
    echo '<SELECT class="form-control selectpicker bts_select " multiple data-live-search="true" ';if($row8['Requis']=='Oui'){echo " required ";} echo ' name="'.$row8['Nom_Collone'].'[]" id="">';
  $Valeur="";
   foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_feuille_etrangere ON (t_feuille.Table_Feuille=t_feuille_etrangere.Nom_Table) WHERE (t_feuille_etrangere.Nom_Table=\''.$row8['Table_Feuille'].'\' AND t_feuille_etrangere.Nom_Colonne=\''.$row8['Nom_Collone'].'\')') AS $row9) 
{$Valeur=$row9['Valeur'];} 
$Table_Choix=null;
$Table_Choix=explode(';', $Valeur);
//echo '<option value=""></option>';
for ($i=0; $i<count($Table_Choix); $i++) {echo '<option value="'.trim($Table_Choix[$i]).'">'.trim($Table_Choix[$i]).'</option>';}
    echo ' </select>
<input type="hidden" name="Colonne[]" value="'.$row8['Nom_Collone'].'">
<input type="hidden" name="Type[]" value="'.$row8['Type_Ligne'].'">
  ';
    break;

  case 'FICHIER':
    echo '<input type="file" class="form-control"';if($row8['Requis']=='Oui'){echo " required ";} echo ' name="'.$row8['Nom_Collone'].'[]" id="">
<input type="hidden" name="Colonne[]" value="'.$row8['Nom_Collone'].'">
<input type="hidden" name="Type[]" value="'.$row8['Type_Ligne'].'">
    ';
    break;

  case 'FEUILLE':
    echo '<SELECT class="form-control selectpicker bts_select " data-live-search="true" ';if($row8['Requis']=='Oui'){echo " required ";} echo ' name="'.$row8['Nom_Collone'].'" id="">';
  $Valeur="";

   foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_feuille_etrangere ON (t_feuille.Table_Feuille=t_feuille_etrangere.Nom_Table) WHERE (t_feuille_etrangere.Nom_Table=\''.$row8['Table_Feuille'].'\' AND t_feuille_etrangere.Nom_Colonne=\''.$row8['Nom_Collone'].'\')') AS $row9) 
{$Valeur=$row9['Valeur'];} 
$Table_Choix=null;
$Table_Choix=explode(';', $Valeur);
$Nom_View="";
   foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE Code_Feuille='.str_replace(' ', '', $Table_Choix[0])) AS $row10) 
{$Nom_View=$row10["Table_Feuille"];} 
$Nom_Col="";
   foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE Code_Feuille_Ligne='.str_replace(' ', '', $Table_Choix[1])) AS $row11) 
{$Nom_Col=$row11["Nom_Collone"];} 
echo '<option value=""></option>';
foreach (FC_Rechercher_Code('SELECT * FROM '.str_replace("t", "v", $Nom_View)." WHERE (Stat=1 ".$Filtre_Admin.')') AS $row10) 
{echo '<option value="'.$row10["".$Nom_Col].'">'.$row10["".$Nom_Col].'</option>';} 

    echo ' </select>
<input type="hidden" name="Colonne[]" value="'.$row8['Nom_Collone'].'">
<input type="hidden" name="Type[]" value="'.$row8['Type_Ligne'].'">
    ';
    break;

  default:
    # code...
    break;
}


echo '<input type="hidden" name="Table_Feuille" value="'.$row8['Table_Feuille'].'">
</div></div><br>

';

}
echo ' </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 col-md-4 mb-4">
                <button style="width: 150px" class="btn btn-success" id="submit" type="submit">Insérer</button></div>
              </div><br>
              </div>
          </form>';

}
?>
