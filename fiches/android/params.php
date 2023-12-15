<?php
require_once 'fonctions/php/Fonctions.php'; 
require_once 'fonctions/php/Session.php';

if($ACCESS=="O")
{
	if(isset($_POST["Params"]))
	{
		$HOST="185.98.138.190";
		$USER="admin_fiche";
		$PASS="rvwqjBSSE8#3";
		echo '[';

		echo '{"HOST":"'.$HOST.'","USER":"'.$USER.'","PASSWORD":"'.$PASS.'"}';

		echo ']';
	}
}
 ?>
