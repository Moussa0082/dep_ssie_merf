<?php
function Charger_db()
{$bd_name='c0ssi4627';
$host_name='host=localhost';
$user_name='c0ssi4627';
$password='dbUjCa#X8oN8';
try{$Connexion=new PDO('mysql:'.$host_name.';dbname='.$bd_name,$user_name,$password,array(PDO::ATTR_PERSISTENT=>true));$Connexion->exec('SET CHARACTER SET utf8');return $Connexion;}
catch (Exception $e)
	{return null;}}
?>