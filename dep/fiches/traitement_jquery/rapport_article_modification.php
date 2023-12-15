<?php
if($_POST){
require_once '../api/Fonctions.php';
$photo_name="";
$Icone="";
extract($_POST);


if((isset($_FILES['photo_article']['tmp_name']) and !empty($_FILES['photo_article']['tmp_name'])) and (isset($_FILES['photo_article']['size']) and !empty($_FILES['photo_article']['size'])) and (isset($_FILES['photo_article']['name']) and !empty($_FILES['photo_article']['name'])))
{ $ext= strtolower(substr($_FILES['photo_article']['name'], strrpos($_FILES['photo_article']['name'], ".")+1));
if ($ext=='jpg' OR $ext== 'jpeg' OR $ext== 'png' OR $ext== 'gif')
{$tmp_name=$_FILES['photo_article']['tmp_name'];
$photo_name='article_'.time().'.'.$ext;
if (move_uploaded_file($tmp_name, "../images/".$photo_name)) 
{$Icone=$photo_name;}
else{$Icone="";}}
else{$Icone="";}}

if($Icone==""){PC_Enregistrer_Code("UPDATE t_rapport_article SET Code_Rapport = $code_rapport, Titre_Article = '".FC_Formater($titre_article)."', Description_Article = '".FC_Formater($description_article)."' WHERE(Code_Article=$code_article)");}
else {PC_Enregistrer_Code("UPDATE t_rapport_article SET Code_Rapport = $code_rapport, Titre_Article = '".FC_Formater($titre_article)."', Description_Article = '".FC_Formater($description_article)."', Photo = '".$Icone."' WHERE(Code_Article=$code_article)");}


}
?>




