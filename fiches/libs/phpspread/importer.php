<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

// Ouvrir un fichier Excel en lecture
$objPHPExcel = PhpOffice\PhpSpreadsheet\IOFactory::load("../phpspreadsheet/Employe.xlsx");
//$objPHPExcel = $objReader->load("../phpspreadsheet/Employe.xlsx");
$sheet = $objPHPExcel->getActiveSheet() ;

// Premiere facon d'acceder au contenu de cellules du classeur Excel
$cell_1 = $sheet->getCell('A2') ;
$cell_2 = $sheet->getCell('B2') ;
$cell_3 = $sheet->getCell('C2') ;

echo 'Value : '.$cell_1->getValue()."\r\n" ;
echo 'Calculated Value : '.$cell_1->getCalculatedValue()."\r\n" ;
echo 'Formatted Value : '.$cell_1->getFormattedValue()."\r\n" ;

echo 'Value : '.$cell_2->getValue()."\r\n" ;
echo 'Calculated Value : '.$cell_2->getCalculatedValue()."\r\n" ;
echo 'Formatted Value : '.$cell_2->getFormattedValue()."\r\n" ;

echo 'Value : '.$cell_3->getValue()."\r\n" ;
echo 'Calculated Value : '.$cell_3->getCalculatedValue()."\r\n" ;
echo 'Formatted Value : '.$cell_3->getFormattedValue()."\r\n" ;

// Deuxieme facon d'acceder au contenu de cellules du classeur Excel
$cell_1 = $sheet->getCellByColumnAndRow(0, 1) ;
$cell_2 = $sheet->getCellByColumnAndRow(1, 1) ;
$cell_3 = $sheet->getCellByColumnAndRow(2, 1) ;

echo 'Value : '.$cell_1->getValue()."\r\n" ;
echo 'Calculated Value : '.$cell_1->getCalculatedValue()."\r\n" ;
echo 'Formatted Value : '.$cell_1->getFormattedValue()."\r\n" ;

echo 'Value : '.$cell_2->getValue()."\r\n" ;
echo 'Calculated Value : '.$cell_2->getCalculatedValue()."\r\n" ;
echo 'Formatted Value : '.$cell_2->getFormattedValue()."\r\n" ;

echo 'Value : '.$cell_3->getValue()."\r\n" ;
echo 'Calculated Value : '.$cell_3->getCalculatedValue()."\r\n" ;
echo 'Formatted Value : '.$cell_3->getFormattedValue()."\r\n" ;

// Cas d'une cellule au format date
/*$cell = $sheet->getCell('A2') ;

echo 'Date Value : '.$cell->getValue()."\r\n" ;
echo 'Date Calculated Value : '.$cell->getCalculatedValue()."\r\n" ;
echo 'Date Formatted Value : '.$cell->getFormattedValue()."\r\n" ;
$timestamp = PHPExcel_Shared_Date::ExcelToPHP($cell->getValue()); // (Unix time)
$date = date('Y-m-d', $timestamp); // AAAA-MM-DD (formatted date)
echo 'Date AAAA-MM-DD : '.$date."\r\n" ;*/

?>
