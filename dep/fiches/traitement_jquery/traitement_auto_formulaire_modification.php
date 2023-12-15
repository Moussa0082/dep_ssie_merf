<?php
//var_dump($_POST);
if(isset($_POST))
{require_once '../api/Fonctions.php';
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

if($Fichier!=""){
PC_Enregistrer_Code("UPDATE ".$_POST['Table_Feuille']." SET ".$_POST['Colonne'][$i]." = '".$Fichier."',  Stat=0 WHERE Id=".$_POST['Id']);
}
}
else if($_POST['Type'][$i]=="INT" OR $_POST['Type'][$i]=="DOUBLE")
{if(empty($_POST[$_POST['Colonne'][$i]]))
{PC_Enregistrer_Code("UPDATE ".$_POST['Table_Feuille']." SET ".$_POST['Colonne'][$i]." = NULL,  Stat=0 WHERE Id=".$_POST['Id']);} 
else{PC_Enregistrer_Code("UPDATE ".$_POST['Table_Feuille']." SET ".$_POST['Colonne'][$i]." = ".$_POST[$_POST['Colonne'][$i]].",  Stat=0 WHERE Id=".$_POST['Id']);}
}
else if ($_POST['Type'][$i]=="CHOIX MULTIPLES")
{
	if(!empty($_POST[$_POST['Colonne'][$i]]) AND count($_POST[$_POST['Colonne'][$i]]) > 0){
		PC_Enregistrer_Code("UPDATE ".$_POST['Table_Feuille']." SET ".$_POST['Colonne'][$i]." = '".addslashes(trim(implode(";",$_POST[$_POST['Colonne'][$i]])))."',  Stat=0 WHERE Id=".$_POST['Id']);
		 }
	else {PC_Enregistrer_Code("UPDATE ".$_POST['Table_Feuille']." SET ".$_POST['Colonne'][$i]." = NULL,  Stat=0 WHERE Id=".$_POST['Id']);}
}
else {
	if(empty(trim($_POST[$_POST['Colonne'][$i]])))
	{PC_Enregistrer_Code("UPDATE ".$_POST['Table_Feuille']." SET ".$_POST['Colonne'][$i]." = NULL,  Stat=0 WHERE Id=".$_POST['Id']);} 
else{PC_Enregistrer_Code("UPDATE ".$_POST['Table_Feuille']." SET ".$_POST['Colonne'][$i]." = '".addslashes(trim($_POST[$_POST['Colonne'][$i]]))."',  Stat=0 WHERE Id=".$_POST['Id']);}

	}
}
}

?>