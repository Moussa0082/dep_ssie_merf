<?php
require_once 'Config.php';
session_start();
function FC_Rechercher_Code($code)
{ $Connexion = Charger_db();
	if($Connexion!=NULL)
 	{try{return $Connexion->query($code);}
	catch(Exception $e){return NULL;}}
	else{return NULL;}
}
function PC_Enregistrer_Code($code)
{$Connexion = Charger_db();
	if($Connexion!=NULL)
 	{try{return $Connexion->exec($code);}
	catch(Exception $e){return NULL;}}
	else{return NULL;}
}
 
function FC_Rechercher($code)
{$Connexion = Charger_db();
	if($Connexion!=NULL)
 	{try{return $Connexion->query('SELECT * FROM '.$code);}
	catch(Exception $e){return NULL;}}
	else{return NULL;}
}

function FC_Formater($texte)
{return addslashes(htmlspecialchars(htmlentities($texte)));}

function FC_Reformater($texte)
{return html_entity_decode($texte);}

function FC_Get_IP() {
	if (isset($_SERVER['HTTP_CLIENT_IP']))
	{return $_SERVER['HTTP_CLIENT_IP'];}
	elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	{return $_SERVER['HTTP_X_FORWARDED_FOR'];}
	else
	{return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');}
}
?>