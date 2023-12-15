<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

if(isset($_GET['t']) AND !empty($_GET['t'])){
    require_once '../../api/Fonctions.php';
    $Filtre_Admin='';
    $Tab_Col_Entete = array();
    $Tab_Col_Entete_Type = array();
    /*if(strtolower(trim($_SESSION["clp_id"])) == "admin"){$Filtre_Admin=' WHERE (Stat=1) ';}
    else {$Filtre_Admin=" WHERE (Stat=1 AND Login = '".$_SESSION["clp_id"]."') ";}*/
$Nom_Feuille="";$compte=0;

foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE (Code_Feuille=\''.$_GET['t'].'\')') as $row5)
    {$Nom_Feuille=str_replace(' ','_',$row5['Nom_Feuille']).'.xlsx';
     $A="A"; $Nb_Col=0; $i=0;
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE ( Code_Feuille=\''.$row5['Code_Feuille'].'\' AND Afficher=\'Oui\') ORDER BY Rang') as $row6)
    {$sheet->setCellValue($A.'1', $row6['Libelle_Ligne']); 
    $sheet->getStyle($A.'1', $row6['Libelle_Ligne'])->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
    $Tab_Col_Entete[]=$row6['Nom_Collone']; $Tab_Col_Entete_Type[]=$row6['Type_Ligne'];
    //$sheet->getColumnDimension($A.'1', $row6['Libelle_Ligne'])->setAutoSize(true);
    

    $sheet->getStyle($A.'1', $row6['Libelle_Ligne'])->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('F0F0F0');

$A++; $Nb_Col++;}



/*foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne INNER JOIN ('.str_replace("t", "v", $row5['Table_Feuille']).' INNER JOIN t_feuille ON (t_feuille.Table_Feuille=\''.$row5['Table_Feuille'].'\')) ON (t_feuille_ligne.Code_Feuille=t_feuille.Code_Feuille) WHERE (Afficher=\'Oui\' '.$Filtre_Admin.') ORDER BY Id DESC, Rang ASC') as $row6)
    {
        
    if($row6['Afficher']=='Oui' AND $row6['Stat']=='1'){
        if($i%$Nb_Col==0){$A='A';$compte++;}else{}
        switch ($row6['Type_Ligne']){
            case 'TEXT' : case 'DATE' : case 'CHOIX' : case 'FEUILLE' :
            $sheet->setCellValue($A.($compte+1), $row6[$Tab_Col_Entete[$j]]);    
                break;
            case 'INT' :
            $sheet->setCellValue($A.($compte+1), number_format($row6[$Tab_Col_Entete[$j]],0, '',' '));  
                break;
            case 'DOUBLE' :
            $sheet->setCellValue($A.($compte+1), number_format($row6[$Tab_Col_Entete[$j]],2, '.',' ')); 
                break;
            case 'SOMME' : case 'MOYENNE' : case 'DIFFERENCE' : case 'PRODUIT' :
             $sheet->setCellValue($A.($compte+1), number_format($row6[$Tab_Col_Entete[$j]],2, '.',' '));  
                break;
            case 'RAPPORT' :
            $sheet->setCellValue($A.($compte+1), number_format($row6[$Tab_Col_Entete[$j]],2, '.',' '));  
                break;  

            case 'COULEUR' :
            $sheet->setCellValue($A.($compte+1), " ");
                break;
            case 'FICHIER' :
            $sheet->setCellValue($A.($compte+1), " ");
                break;  

            case 'COMPTER' :
             $sheet->setCellValue($A.($compte+1), number_format($compte,0, '',' '));
                break;
            
            default:
                break;
        }
      $A++;$i++;
    }

}*/
$Res=FC_Rechercher_Code('SELECT * FROM '.str_replace("t", "v", $row5['Table_Feuille']).$Filtre_Admin.' ORDER BY Stat ASC, Id DESC');
    //if($Res!=null){
    foreach ($Res as $row6)
    {$A='A';$compte++;

      for($j=0; $j<count($Tab_Col_Entete); $j++){

        switch ($Tab_Col_Entete_Type[$j]){
            case 'TEXT' : case 'DATE' : case 'CHOIX' : case 'FEUILLE' : case 'QRCODE' :
            $sheet->setCellValue($A.($compte+1), $row6[$Tab_Col_Entete[$j]]);    
                break;
            case 'INT' :
            $sheet->setCellValue($A.($compte+1), number_format($row6[$Tab_Col_Entete[$j]],0, '',' '));  
                break;
            case 'DOUBLE' :
            $sheet->setCellValue($A.($compte+1), number_format($row6[$Tab_Col_Entete[$j]],2, '.',' ')); 
                break;
            case 'SOMME' : case 'MOYENNE' : case 'DIFFERENCE' : case 'PRODUIT' :
             $sheet->setCellValue($A.($compte+1), number_format($row6[$Tab_Col_Entete[$j]],2, '.',' '));  
                break;
            case 'RAPPORT' :
            $sheet->setCellValue($A.($compte+1), number_format($row6[$Tab_Col_Entete[$j]],2, '.',' '));  
                break;  

            case 'COULEUR' :
            $sheet->setCellValue($A.($compte+1), " ");
                break;
            case 'FICHIER' :
            $sheet->setCellValue($A.($compte+1), " ");
                break;  

            case 'COMPTER' :
             $sheet->setCellValue($A.($compte+1), number_format($compte,0, '',' '));
                break;
            
            default:
                break;
        }
      $A++;$i++;
        
      }

    echo '</tr>';

    }
  //}

$spreadsheet->getActiveSheet()->setTitle(str_replace(".xlsx", "", $Nom_Feuille));
$writer = new Xlsx($spreadsheet);
$writer->save($Nom_Feuille);

$new_file = '../../telechargements/'.$Nom_Feuille;
rename($Nom_Feuille, $new_file);
header("location:../../index.php");
}
}
?>