<?php 

var_dump($_POST);
var_dump($_FILES);
require_once 'fonctions/php/Fonctions.php'; 
if(isset($_POST['image']) AND isset($_POST['nom']))
{
file_put_contents("../pieces/".$_POST['nom'], base64_decode($_POST['image']));

//PC_Enregistrer_Code("INSERT INTO a_image(image) VALUES('".$_POST["image"]."')");
//echo "true";
}
 ?>
