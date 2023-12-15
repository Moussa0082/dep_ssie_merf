<?php 
if(isset($_GET['Code_Commentaire']) AND !empty($_GET['Code_Commentaire']))	
{extract($_GET);
	require_once '../api/Fonctions.php';

    PC_Enregistrer_Code("DELETE FROM t_rapport_commentaire WHERE Code_Commentaire=".$Code_Commentaire);
 } ?>
 
