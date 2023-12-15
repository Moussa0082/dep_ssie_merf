<?php

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
if($_POST){
$NbCol=0;
$Index=0;
$Entete= array();
$Nom_Colonne=null;
$Type_Colonne=null;
$Nom_Feuille="";
$Table_Feuille;
$Structure="INSERT IGNORE INTO ";
$Donnees="";
$Nb_Col_Ignore = 0;


session_start();
require_once '../../api/Fonctions.php';
$Filtre_Admin='';
    if(strtolower(trim($_SESSION["clp_id"])) == "admin"){$Filtre_Admin='';}
    else {$Filtre_Admin=" AND Login = '".$_SESSION["clp_id"]."' ";}
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE (Code_Feuille=\''.$_POST['t'].'\')') as $row5)
{$Nom_Feuille='../../telechargements/'.str_replace(" ", "_",$row5['Nom_Feuille']);

if((isset($_FILES['Fichier']['tmp_name']) and !empty($_FILES['Fichier']['tmp_name'])) and (isset($_FILES['Fichier']['size']) and !empty($_FILES['Fichier']['size'])) and (isset($_FILES['Fichier']['name']) and !empty($_FILES['Fichier']['name'])))
{ $ext= strtolower(substr($_FILES['Fichier']['name'], strrpos($_FILES['Fichier']['name'], ".")+1));
if ($ext=='xlsx')
{$tmp_name=$_FILES['Fichier']['tmp_name'];
$photo_name=time().'.'.$ext;
if (move_uploaded_file($tmp_name, $Nom_Feuille.".".$ext))
{


$Table_Feuille=$row5['Table_Feuille'];
$Structure.=$row5['Table_Feuille']." (Login, ";
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($Nom_Feuille.".xlsx");
$worksheet = $spreadsheet->getActiveSheet();

foreach ($worksheet->getRowIterator() as $row) 
{if($Index==0){
  $cellIterator = $row->getCellIterator(); $cellIterator->setIterateOnlyExistingCells(FALSE);
  $cells = [];                                                
    foreach ($cellIterator as $cell)
    {$cells[] = $cell->getValue();}
    $nombre=count($cells);
    for ($i=0; $i < $nombre; $i++) 
    {if ($cells[$i] !='') {
      if($cells[$i]=='__id' OR $cells[$i]=='__utilisateur' OR $cells[$i]=='__dateInsertion' OR $cells[$i]=='__longitude' OR $cells[$i]=='__latitude')
        {$Nb_Col_Ignore++;} else{$NbCol++; $Entete[]=$cells[$i];}
    }
    }
    $Index++;}
}
for($i=0; $i<count($Entete); $i++)
{$Col=""; $Type="";
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE (Code_Feuille='.$_POST['t']." AND Libelle_Ligne='".addslashes(trim($Entete[$i]))."') LIMIT 1") as $row6)
{$Col=addslashes($row6['Nom_Collone']); $Type=$row6['Type_Ligne'];
if($row6['Type_Ligne']!="COMPTER" AND $row6['Type_Ligne']!="SOMME" AND $row6['Type_Ligne']!="DIFFERENCE" AND $row6['Type_Ligne']!="PRODUIT" AND $row6['Type_Ligne']!="RAPPORT" AND $row6['Type_Ligne']!="MOYENNE"){
if($row6['Type_Ligne']=="INT" OR $row6['Type_Ligne']=="DOUBLE")
{$Structure.=addslashes($row6['Nom_Collone']).", ";}
else {$Structure.=addslashes($row6['Nom_Collone']).", ";}
}
}
$Nom_Colonne[$i]=$Col;
$Type_Ligne[$i]=$Type;
}
$Structure=substr($Structure, 0, strlen($Structure)-2);
$Structure.=") VALUES ";

 ?>




<?php

if($_POST['Option']=="ECRASER"){PC_Enregistrer_Code("DELETE FROM ".$Table_Feuille.$Filtre_Admin);}
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($Nom_Feuille.".xlsx");
$worksheet = $spreadsheet->getActiveSheet();
$Ind=0;

foreach ($worksheet->getRowIterator() as $row) 
{if($Ind>0)
{$Cpt = 0;
  $Donnees="";
  $Donnees.=" ('".strtoupper($_SESSION["clp_id"])."', ";
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                                                       //    even if a cell value is not set.
                                                       // By default, only cells that have a value
       $cells = [];                                                //    set will be iterated.
    foreach ($cellIterator as $cell)
    {$Cpt++;
      if($Cpt > $Nb_Col_Ignore)
      $cells[] = $cell->getValue(); 
          
    }
    $nombre=count($cells);
    for ($i=0; $i < $NbCol; $i++) 
    { 
    if($Type_Ligne[$i]!="COMPTER" AND $Type_Ligne[$i]!="SOMME" AND $Type_Ligne[$i]!="DIFFERENCE" AND $Type_Ligne[$i]!="PRODUIT" AND $Type_Ligne[$i]!="RAPPORT" AND $Type_Ligne[$i]!="MOYENNE" AND $Type_Ligne[$i]!="SIGNATURE"){
  if($Type_Ligne[$i]=="INT")
  {
    if(empty(str_replace(" ", "",$cells[$i]))){$Donnees.="NULL, ";} else{$Donnees.= (int) str_replace(" ", "",$cells[$i]).", ";}
  }
else  if($Type_Ligne[$i]=="DOUBLE")
  {
    if(empty(str_replace(" ", "",$cells[$i]))){$Donnees.="NULL, ";} else{$Donnees.= (double) str_replace(" ", "",$cells[$i]).", ";}
  }
else  if($Type_Ligne[$i]=="DATE")
  {
    if(empty(str_replace(" ", "",strtotime($cells[$i])))){$Donnees.="NULL, ";} else{$Donnees.= " CAST('".date('Y-m-d',strtotime($cells[$i]))."' AS DATE), ";}
  }
  else {
if(empty(trim($cells[$i])))
  {$Donnees.="NULL, ";} else{$Donnees.="'".addslashes(trim($cells[$i]))."', ";}
}
}

    }
$Donnees=substr($Donnees, 0, strlen($Donnees)-2);
    $Donnees.=");";
//echo $Structure.$Donnees;
PC_Enregistrer_Code($Structure.$Donnees);
}

$Ind++;
}
}
else{}}
else{}}
}


}

?>
