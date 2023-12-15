<?php 
if(isset($_GET['Code_Article']) AND !empty($_GET['Code_Article']))	
{extract($_GET);
	require_once '../api/Fonctions.php';

    PC_Enregistrer_Code("UPDATE t_rapport_article SET Validation='Oui' WHERE Code_Article=".$Code_Article);
 } ?>
 
