<?php
if($_POST){
require_once '../fonctions/php/Fonctions.php';	
extract($_POST);
PC_Enregistrer_Code('CALL PC_ACTIVER_THEME('.$theme.')');}
?>