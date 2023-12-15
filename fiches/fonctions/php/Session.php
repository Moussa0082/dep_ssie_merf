<?php
if ((isset($_SESSION['login']) and !empty($_SESSION['login'])) and (isset($_SESSION['pass']) and !empty($_SESSION['pass'])))
{	$ind = array("'", "<", ">", " ", "--", "/", '"', "%", "$", "*");
$Identifiant=addslashes($_SESSION['login']);
$Mot_de_passe=addslashes(base64_decode(base64_decode($_SESSION['pass'])));
$Identifiant=str_replace($ind, "", $Identifiant);
$Mot_de_passe=str_replace($ind, "", $Mot_de_passe);
$Res1=FC_Rechercher_Code('SELECT COUNT(*) AS RES FROM user WHERE (Identifiant=\''.$Identifiant.'\' AND Mot_de_passe=\''.$Mot_de_passe.'\')');
	$cpt='';
	foreach ($Res1 as $row)
	{$cpt=$row['RES'];}
	if ($cpt=='1')
	{}
	else
	{header('location:deconnexion.php');}
}
else{header('location:deconnexion.php');}
?>