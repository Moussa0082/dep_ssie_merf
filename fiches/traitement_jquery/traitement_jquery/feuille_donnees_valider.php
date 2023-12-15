<?php
if(isset($_POST))
{
require_once '../fonctions/php/Fonctions.php';
for ($i=0; $i<count($_POST['Code']); $i++) 
{PC_Enregistrer_Code("UPDATE ".$_POST['Feuille']." SET Stat=1 WHERE Id=".$_POST['Code'][$i]);}
}
?>