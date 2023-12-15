<?php
if($_POST){
require_once '../api/Fonctions.php';
$photo_name="";
$Icone="";
extract($_POST);


if((isset($_FILES['photo_commentaire']['tmp_name']) and !empty($_FILES['photo_commentaire']['tmp_name'])) and (isset($_FILES['photo_commentaire']['size']) and !empty($_FILES['photo_commentaire']['size'])) and (isset($_FILES['photo_commentaire']['name']) and !empty($_FILES['photo_commentaire']['name'])))
{ $ext= strtolower(substr($_FILES['photo_commentaire']['name'], strrpos($_FILES['photo_commentaire']['name'], ".")+1));
if ($ext=='jpg' OR $ext== 'jpeg' OR $ext== 'png' OR $ext== 'gif')
{$tmp_name=$_FILES['photo_commentaire']['tmp_name'];
$photo_name='commentaire_'.time().'.'.$ext;
if (move_uploaded_file($tmp_name, "../images/".$photo_name)) 
{$Icone=$photo_name;}
else{$Icone="";}}
else{$Icone="";}}

PC_Enregistrer_Code("INSERT INTO t_rapport_commentaire(Code_Article, Commentaire, Photo) VALUES ($code_article, '".FC_Formater($commentaire)."', '".$Icone."')");}
?>
