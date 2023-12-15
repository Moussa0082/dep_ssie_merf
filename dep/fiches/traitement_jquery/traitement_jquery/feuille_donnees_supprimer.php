<?php
var_dump($_GET);
if(isset($_GET))
{extract($_GET);
require_once '../fonctions/php/Fonctions.php';
PC_Enregistrer_Code('DELETE FROM '.$tab.' WHERE Id='.$d);
header('location:../classeur_details.php?c='.$c.'&f='.$f);
}

?>