<?php
if($_POST){
require_once '../api/Fonctions.php';	
extract($_POST);
PC_Enregistrer_Code('CALL PC_ACTIVER_THEME('.$theme.')');}
?>