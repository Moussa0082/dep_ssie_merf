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

PC_Enregistrer_Code("INSERT INTO t_rapport_article(Code_Rapport, Titre_Article, Description_Article, Photo, Login) VALUES ($code_rapport, '".FC_Formater($titre_article)."', '".FC_Formater($description_article)."', '".$Icone."', '".$_SESSION["clp_n"]."')");}
?>




