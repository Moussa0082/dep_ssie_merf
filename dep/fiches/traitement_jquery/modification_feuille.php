<?php
if(isset($_POST)){extract($_POST);



require_once '../api/Fonctions.php';
$Icone="";
$photo_name="";
if((isset($_FILES['Icone']['tmp_name']) and !empty($_FILES['Icone']['tmp_name'])) and (isset($_FILES['Icone']['size']) and !empty($_FILES['Icone']['size'])) and (isset($_FILES['Icone']['name']) and !empty($_FILES['Icone']['name'])))
{ $ext= strtolower(substr($_FILES['Icone']['name'], strrpos($_FILES['Icone']['name'], ".")+1));
if ($ext=='jpg' OR $ext== 'jpeg' OR $ext== 'png' OR $ext== 'gif')
{$tmp_name=$_FILES['Icone']['tmp_name'];
$photo_name=time().'.'.$ext;
if (move_uploaded_file($tmp_name, "../images/".$photo_name)) 
{$Icone=$photo_name;}
else{$Icone="";}}
else{$Icone="";}}
if($Icone=="")
{
PC_Enregistrer_Code("UPDATE t_feuille SET Nom_Feuille='".addslashes($Nom_Feuille)."', Libelle_Feuille='".addslashes($Libelle_Feuille)."', Nb_Ligne_Impr=$Nb_Ligne_Impr, Note='".addslashes($Note)."'  , Code_Classeur='".addslashes($edit_code_classeur)."' WHERE Code_Feuille=".$Code_Feuille);

}

else
{PC_Enregistrer_Code("UPDATE t_feuille SET Nom_Feuille='".addslashes($Nom_Feuille)."', Libelle_Feuille='".addslashes($Libelle_Feuille)."', Nb_Ligne_Impr=$Nb_Ligne_Impr, Note='".addslashes($Note)."', Icone='".$Icone."' , Code_Classeur='".addslashes($edit_code_classeur)."' WHERE Code_Feuille=".$Code_Feuille);
}

}
?>