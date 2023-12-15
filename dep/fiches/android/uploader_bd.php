<?php 
require_once 'fonctions/php/Fonctions.php'; 
require_once 'fonctions/php/Session.php';

if($ACCESS=="O")
{
	if(isset($_POST["Script"]))
	{
		extract($_POST);
		PC_Enregistrer_Code($Script);
	}
}
 ?>
