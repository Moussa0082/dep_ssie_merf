<?php
if(isset($_POST))
{
require_once '../api/Fonctions.php';

if(isset($_POST['Code'])){
for ($i=0; $i<count($_POST['Code']); $i++) 
{PC_Enregistrer_Code("UPDATE ".$_POST['Feuille']." SET Stat=1 WHERE Id=".$_POST['Code'][$i]);}
}
else if(isset($_POST['Tout'])){
PC_Enregistrer_Code("UPDATE ".$_POST['Feuille']." SET Stat=1");	
}

}
?>