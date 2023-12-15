<?php
//var_dump($_POST);
//var_dump($_FILES);
session_start();
if(isset($_POST))
{require_once '../api/Fonctions.php';
$Structure="INSERT INTO ".$_POST['Table_Feuille']."(Login,";
for ($i=0; $i<count($_POST['Colonne']); $i++)
{$Structure.=$_POST['Colonne'][$i].", ";}
$Structure=substr($Structure, 0, strlen($Structure)-2);
$Structure.=") VALUES('".$_SESSION["clp_id"]."', ";
$Fichier="";
for ($i=0; $i<count($_POST['Colonne']); $i++)
{
if($_POST['Type'][$i]=="FICHIER" OR $_POST['Type'][$i]=="SIGNATURE"){
$Fichier="";
if((isset($_FILES[$_POST['Colonne'][$i]]['tmp_name'][0]) and !empty($_FILES[$_POST['Colonne'][$i]]['tmp_name'][0])) and (isset($_FILES[$_POST['Colonne'][$i]]['size'][0]) and !empty($_FILES[$_POST['Colonne'][$i]]['size'][0])) and (isset($_FILES[$_POST['Colonne'][$i]]['name'][0]) and !empty($_FILES[$_POST['Colonne'][$i]]['name'][0])))
{ $ext= strtolower(substr($_FILES[$_POST['Colonne'][$i]]['name'][0], strrpos($_FILES[$_POST['Colonne'][$i]]['name'][0], ".")+1));
$tmp_name=$_FILES[$_POST['Colonne'][$i]]['tmp_name'][0];
$Temps=time();
if (move_uploaded_file($tmp_name, "../pieces/".$Temps."_".str_replace(" ","_",$_FILES[$_POST['Colonne'][$i]]['name'][0])))
{$Fichier=$Temps."_".str_replace(" ","_",$_FILES[$_POST['Colonne'][$i]]['name'][0]);}
else{$Fichier="";}

}
$Structure.="'".addslashes($Fichier)."', ";
}
else if($_POST['Type'][$i]=="INT" OR $_POST['Type'][$i]=="DOUBLE")
{if(empty($_POST[$_POST['Colonne'][$i]])){$Structure.="NULL, ";} else{$Structure.=$_POST[$_POST['Colonne'][$i]].", ";}}
else if ($_POST['Type'][$i]=="CHOIX MULTIPLES")
{
	if(!empty($_POST[$_POST['Colonne'][$i]]) AND count($_POST[$_POST['Colonne'][$i]]) > 0){
		$Structure.="'".addslashes(trim(implode(";",$_POST[$_POST['Colonne'][$i]])))."', ";
		 }
	else {$Structure.="NULL, ";}
}
else {if(empty(trim($_POST[$_POST['Colonne'][$i]])))
	{$Structure.="NULL, ";} else{$Structure.="'".addslashes(trim($_POST[$_POST['Colonne'][$i]]))."', ";}}
}
$Structure=substr($Structure, 0, strlen($Structure)-2);
$Structure.=");";
PC_Enregistrer_Code($Structure);
}
?>