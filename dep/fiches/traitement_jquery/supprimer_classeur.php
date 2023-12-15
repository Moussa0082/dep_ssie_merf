<?php
if(isset($_GET)){extract($_GET);
require_once '../api/Fonctions.php';

PC_Enregistrer_Code("DELETE FROM t_classeur WHERE Code_Classeur=".$Code_Classeur);

}
?>