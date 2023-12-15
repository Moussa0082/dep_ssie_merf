<?php
if(isset($_GET["P"]) AND !empty($_GET["P"]))
{
$Serveur_Web="http://localhost/dompdf/";
$Code_Produit=$_GET["P"];
$Srv = "http://127.0.0.19";

require '../Dir_Fonctions/PHP/Fonctions.php';
$Nom_Produit = "";
$Nom_Pays=""; 
$Indicatif=""; 
$Drapeau_Pays="";

$Resultat = FC_Rechercher_Code("SELECT * FROM `t_produit` INNER JOIN `t_compte` ON (t_produit.Id_Compte = t_compte.Id_Compte) WHERE (`Code_Produit` = ".$Code_Produit." AND `Statut_Produit`='Actif')");
if($Resultat != null AND ($Resultat -> rowCount())>0){
foreach ($Resultat as $row1) {
$Nom_Produit = $row1["Nom_Produit"];
foreach (FC_Rechercher_Code("SELECT * FROM `t_pays` WHERE `Code_Pays`='".$row1["Code_Pays"]."'") as $row2) 
{
$Nom_Pays=$row2["Nom_Pays"];
$Indicatif=$row2["Indicatif"];
$Drapeau_Pays=$row2["Drapeau_Pays"];
}?>


<!DOCTYPE html>
<html>
<head>
	<title>Koumi</title>
	<style type="text/css">
		html{ margin-top: 0px; padding: 0px; }
		body{ margin-top: 0px; padding: 0px; }
	</style>
</head>
<body>
<header>
<div>
	<table width="100%">
		<tr>
			<td width="125px">
			<img src="../Dir_Images/logo.jpg" width="120px">
			</td>

			<td>
				<div style="border: 1px solid #ff8800; padding: 10px; font-size: 22px; font-weight: bold; text-align: center; border-radius: 10px">
					<?php echo $row1['Nom_Produit']; ?>
				</div>
			</td>
		</tr>
	</table>
</div>
</header>
<footer>
	
</footer>
<main>
<div id="body">

	<div>
		<table width="100%" style="font-size: 18px">
			<tr>
				<td colspan="2" style="font-size: 20px; font-weight: bold; text-align: center;">
					<div style="border:1px solid #ff8800; border-radius: 5px 5px 0px 0px; background: beige; padding: 5px">
					<?php 
					switch ($row1["Operation_Produit"]) 
							{
								case 'PRODUCTION': echo strtoupper('Producteur'); break;
								case 'TRANSPORTATION': echo strtoupper('Transporteur'); break;
								case 'TRANSFORMATION': echo strtoupper('Transformateur'); break;
								case 'COMMERCIALISATION': echo strtoupper('CommerÇant'); break;
							}
					 ?>
					 	
					 </div>
				</td>
			</tr>
			
				
					<?php 
  
if($row1["Type_Licence"]=="PARTICULIER")
{echo '<tr><td  style="border-bottom: 1px solid #ff8800">Nom et prénoms : </td> <td   style="border-bottom: 1px solid #ff8800">';
  echo strtoupper($row1["Nom_Compte"]).' '.ucfirst($row1["Prenoms_Compte"]);
  echo '</td></tr>';
}
else 
{ 
echo '<tr><td style="border-bottom: 1px solid #ff8800;">Sigle : </td> <td style="border-bottom: 1px solid #ff8800">';
  echo strtoupper($row1["Sigle_Compagnie"]);
  echo '</td></tr>';

 echo '<tr><td style="border-bottom: 1px solid #ff8800;">Structure : </td> <td style="border-bottom: 1px solid #ff8800">';
  echo $row1["Nom_Compagnie"];
  echo '</td></tr>';
if(!empty($row1["Autres_Infos"])){
  echo '<tr><td style="border-bottom: 1px solid #ff8800;">Autres informations : </td> <td style="border-bottom: 1px solid #ff8800">';
  echo $row1["Autres_Infos"];
  echo '</td></tr>';}
}


	if (file_exists('../Dir_Params/Dir_Logo/'.$row1["Logo_Compagnie"]) AND is_file('../Dir_Params/Dir_Logo/'.$row1["Logo_Compagnie"]))
{echo '<tr><td style="border-bottom: 1px solid #ff8800"></td> <td style="border-bottom: 1px solid #ff8800"><img src="../Dir_Params/Dir_Logo/'.$row1["Logo_Compagnie"].'" width="50px" alt="image..."></td></tr>';}

  echo '<tr><td style="border-bottom: 1px solid #ff8800">Pays : </td> <td style="border-bottom: 1px solid #ff8800">';
  if(isset($GLOBALS["Drapeau_Pays"]) AND !empty($GLOBALS["Drapeau_Pays"])){
  	echo '<img src="../Dir_Images/Drapeau/'.strtolower($GLOBALS["Drapeau_Pays"]).'.png'.'" width="20px" alt="image..." style="margin-right:5px">';
  	}
  	echo $GLOBALS["Nom_Pays"];
  echo '</td></tr>';

  echo '<tr><td style="border-bottom: 1px solid #ff8800">Téléphone : </td> <td style="border-bottom: 1px solid #ff8800">';
  echo '<a href="tel:+'.$GLOBALS["Indicatif"].$row1["Num_Telephone"].'">+'.$GLOBALS["Indicatif"].' '.$row1["Num_Telephone"].'</a>';
  echo '</td></tr>';

if(!empty($row1["Adresse_Compagnie"])){
	echo '<tr><td style="border-bottom: 1px solid #ff8800;">Adresse : </td> <td style="border-bottom: 1px solid #ff8800">';
  echo '<a target="1" href="https://www.google.com/maps/search/?api=1&query='.$row1["Coord_Compagnie"].'">'.$row1["Adresse_Compagnie"].'</a>';
  echo '</td></tr>';}

					 ?>
<tr>
	<td colspan="2" style="font-size: 20px; font-weight: bold; text-align: center;">
		<div style="border:1px solid #ff8800; border-radius: 5px 5px 0px 0px; background: beige; padding: 5px; margin-top: 20px">
			PRODUIT
		</div>
	</td>
</tr>
<tr>
	<td style="border-bottom: 1px solid #ff8800;">Code du produit : </td>
	<td style="border-bottom: 1px solid #ff8800"><?php echo $row1["Code_Produit"] ?></td>
</tr>

<tr>
	<td style="border-bottom: 1px solid #ff8800">Nom du produit : </td>
	<td style="border-bottom: 1px solid #ff8800"><?php echo $row1["Nom_Produit"] ?></td>
</tr>

<tr style="display: none">
	<td style="border-bottom: 1px solid #ff8800">Description : </td>
	<td style="border-bottom: 1px solid #ff8800"><?php echo $row1["Description"] ?></td>
</tr>
<tr>
	<td style="border-bottom: 1px solid #ff8800">Famille : </td>
	<td style="border-bottom: 1px solid #ff8800"><?php foreach (FC_Rechercher_Code("SELECT * FROM `t_categorie` INNER JOIN t_groupe_categorie ON (t_categorie.Code_Groupe_Categorie = t_groupe_categorie.Code_Groupe_Categorie)  WHERE `Code_Categorie` = ".$row1["Code_Categorie"]) as $row4) 
		{echo '<strong>'.$row4["Nom_Groupe_Categorie"].'</strong> > '.$row4["Nom_Categorie"];} ?>
</td>
</tr>

<?php 

if($row1["Operation_Produit"]=="TRANSFORMATION" OR $row1["Operation_Produit"]=="COMMERCIALISATION")
{foreach (FC_Rechercher_Code("SELECT * FROM `t_type_produit` WHERE `Code_Type_Produit` = ".$row1["Code_Type_Produit"]) as $row5) 
{echo '<tr><td  style="border-bottom: 1px solid #ff8800">Forme : </td><td  style="border-bottom: 1px solid #ff8800">'.$row5["Nom_Type_Produit"].'</td></tr>';}}

if($row1["Operation_Produit"]=="COMMERCIALISATION")
{echo '<tr><td>Prix unitaire : </td><td>'.$row1["Prix_Unitaire"].'</td></tr>';}

if($row1["Operation_Produit"]!="TRANSPORTATION")
{
if($row1["Operation_Produit"]=="PRODUCTION"){
echo '<tr>
	<td colspan="2" style="font-size: 20px; font-weight: bold; text-align: center;">
		<div style="border:1px solid #ff8800; border-radius: 5px 5px 0px 0px; background: beige; padding: 5px; margin-top: 20px">
			PRODUCTION
		</div>
	</td>
</tr>';

echo '<tr>
	<td style="border-bottom: 1px solid #ff8800">Intrants : </td>
	<td style="border-bottom: 1px solid #ff8800">'.$row1["Intrants"]; '</td>
</tr>';

echo '<tr>
	<td style="border-bottom: 1px solid #ff8800">Date de production : </td>
	<td style="border-bottom: 1px solid #ff8800">'.$row1["Date_Production"]; '</td>
</tr>';
	
}

if($row1["Operation_Produit"]=="PRODUCTION")
{foreach (FC_Rechercher_Code("SELECT * FROM `t_zone_production` WHERE `Code_Zone_Production` = ".$row1["Code_Zone_Production"]) as $row3) 
{
	echo '<tr>
	<td>Zone de production : </td>
	<td><a target="1" href="https://www.google.com/maps/search/?api=1&query='.$row3["Coordonnees_Zone_Production"].'">'.$row3["Nom_Zone_Production"].'</a></td>
</tr>';	}
} 
else if($row1["Operation_Produit"]=="TRANSFORMATION" OR $row1["Operation_Produit"]=="COMMERCIALISATION"){

echo '<tr>
	<td colspan="2" style="font-size: 20px; font-weight: bold; text-align: center;">
		<div style="border:1px solid #ff8800; border-radius: 5px 5px 0px 0px; background: beige; padding: 5px; margin-top: 20px">
			'.strtoupper('Reception des produits').'
		</div>
	</td>
</tr>';

echo '<tr>
	<td style="border-bottom: 1px solid #ff8800">Date de réception / déchargement : </td>
	<td style="border-bottom: 1px solid #ff8800">'.$row1["Date_Dechargement"]; '</td>
</tr>';

if(!empty($row1["Produit_Parent"]) AND $row1["Produit_Parent"]!="")
{ echo '<tr><td   style="border-bottom: 1px solid #ff8800">Produit concerné : </td><td  style="border-bottom: 1px solid #ff8800">';
$Tab1 = explode(",", $row1["Produit_Parent"]);
for ($i=0; $i < count($Tab1); $i++) {
$Res = FC_Rechercher_Code("SELECT * FROM `t_produit` WHERE `Code_Produit` = ".trim($Tab1[$i]));
if($Res != null)
{
foreach ($Res as $row6) 
{//echo '<span><a target="1" href="'.$Serveur_Web.'?P='.$row6["Code_Produit"].'">'.$row6["Code_Produit"].' / '.$row6["Nom_Produit"].'</a></span>';

echo '<a target="1" href="'.$Srv.'/recherche.php?P='.$row6["Code_Produit"].'">'.$row6["Nom_Produit"].'</a>';
if($i < (count($Tab1)-1)){ echo ' - ';}
}
}
}
echo '</td></tr>';}

echo '<tr>
	<td style="border-bottom: 1px solid #ff8800">Autres informations sur l’origine du produit : </td>
	<td style="border-bottom: 1px solid #ff8800">'.$row1["PTT"]; '</td>
</tr>';

if($row1["Operation_Produit"]=="COMMERCIALISATION" AND $row1["Date_Peremption"] != '1970-12-31')
{echo '<tr>
	<td>Date de péremption : </td>
	<td>';
echo $row1["Date_Peremption"];}
echo '</td>
</tr>';	

}
}
else
{
	echo '<tr>
	<td colspan="2" style="font-size: 20px; font-weight: bold; text-align: center;">
		<div style="border:1px solid #ff8800; border-radius: 5px 5px 0px 0px; background: beige; padding: 5px; margin-top: 20px">
			'.strtoupper('Enlevement').'
		</div>
	</td>
</tr>';
echo '<tr>
	<td style="border-bottom: 1px solid #ff8800">Date d\'enlèvement / chargement : </td>
	<td style="border-bottom: 1px solid #ff8800">'.$row1["Date_Chargement"]; '</td>
</tr>';

if(!empty($row1["Produit_Parent"]) AND $row1["Produit_Parent"]!="")
{ echo '<tr><td  style="border-bottom: 1px solid #ff8800">Produit concerné : </td><td  style="border-bottom: 1px solid #ff8800">';
$Tab1 = explode(",", $row1["Produit_Parent"]);
for ($i=0; $i < count($Tab1); $i++) {
$Res = FC_Rechercher_Code("SELECT * FROM `t_produit` WHERE `Code_Produit` = ".trim($Tab1[$i]));
if($Res != null)
{
foreach ($Res as $row6) 
{//echo '<span><a target="1" href="'.$Serveur_Web.'?P='.$row6["Code_Produit"].'">'.$row6["Code_Produit"].' / '.$row6["Nom_Produit"].'</a></span>';

echo '<details><summary>'.$row6["Nom_Produit"].'</summary><iframe src="get_prod.php?P='.$row6["Code_Produit"].'" style="border: 1px; height: 90vh" width="100%" allow="fullscreen" high></iframe>	</details>';
//if($i < (count($Tab1)-1)){ echo ' - ';}
}
}
}
echo '</td></tr>';}
echo '<tr>
	<td style="border-bottom: 1px solid #ff8800">Autres informations sur l’origine du produit : </td>
	<td style="border-bottom: 1px solid #ff8800">'.$row1["PTT"]; '</td>
</tr>';
}



	echo '<tr>
	<td colspan="2" style="font-size: 20px; font-weight: bold; text-align: center;">
		<div style="border:1px solid #ff8800; border-radius: 5px 5px 0px 0px; background: beige; padding: 5px; margin-top: 20px">
			';
switch ($row1["Operation_Produit"]) 
{
	case 'PRODUCTION': echo strtoupper("Conditionnement et stockage"); break;

	case 'TRANSPORTATION': echo strtoupper("Conditionnement et transport"); break;

	case 'TRANSFORMATION': echo strtoupper("Conditionnement et stockage a l'arrivee"); break;

	case 'COMMERCIALISATION': echo strtoupper("Conditionnement et stockage"); break;
}

		echo	'
		</div>
	</td>
</tr>';

echo '<tr>
	<td style="border-bottom: 1px solid #ff8800">Produit utilisé pour le conditionnement : </td>
	<td style="border-bottom: 1px solid #ff8800">'.$row1["Produits_Conditionnement"]; '</td>
</tr>';	

echo '<tr>
	<td style="border-bottom: 1px solid #ff8800">'; 

switch ($row1["Operation_Produit"]) 
{
	case 'PRODUCTION': echo 'Autres conditions de stockage : '; break;

	case 'TRANSPORTATION': echo 'Autres conditions de transport : '; break;

	case 'TRANSFORMATION': echo 'Autres conditions de stockage : '; break;

	case 'COMMERCIALISATION': echo 'Autres conditions de stockage : '; break;
}
echo '</td>
	<td style="border-bottom: 1px solid #ff8800">'.$row1["Autres_Conditions"]; '</td>
</tr>';

if(!empty($row1["Photo_Conditions"]))
{
if (file_exists('../Dir_Params/Dir_Images/'.$row1["Photo_Conditions"]) AND is_file('../Dir_Params/Dir_Images/'.$row1["Photo_Conditions"])){
	echo '<tr><td colspan="2"><center>Photo : </center></td></tr>
	<tr> <td colspan="2"><center><img src="../Dir_Params/Dir_Images/'.$row1["Photo_Conditions"].'" width="500px" alt="image..."></center></td>
</tr>';	

}
}

if($row1["Operation_Produit"]=="TRANSFORMATION"){
	echo '<tr>
	<td colspan="2" style="font-size: 20px; font-weight: bold; text-align: center;">
		<div style="border:1px solid #ff8800; border-radius: 5px 5px 0px 0px; background: beige; padding: 5px; margin-top: 20px">
			';
echo strtoupper('Production et transformation');
		echo	'
		</div>
	</td>
</tr>';

echo '<tr>
	<td style="border-bottom: 1px solid #ff8800">Intrants : </td>
	<td style="border-bottom: 1px solid #ff8800">'.$row1["Intrants"]; '</td>
</tr>';	

echo '<tr>
	<td  style="border-bottom: 1px solid #ff8800">Date de production : </td>
	<td  style="border-bottom: 1px solid #ff8800">'.$row1["Date_Production"]; '</td>
</tr>';	

echo '<tr>
	<td  style="border-bottom: 1px solid #ff8800">Date de péremption : </td>
	<td  style="border-bottom: 1px solid #ff8800">';
if($row1["Date_Peremption"] != '1970-12-31')
{echo $row1["Date_Peremption"];}
echo '</td>
</tr>';		
}


if($row1["Operation_Produit"]=="TRANSPORTATION"){
	echo '<tr>
	<td colspan="2" style="font-size: 20px; font-weight: bold; text-align: center;">
		<div style="border:1px solid #ff8800; border-radius: 5px 5px 0px 0px; background: beige; padding: 5px; margin-top: 20px">
			';
			echo strtoupper("Dechargement");
		echo'
		</div>
	</td>
</tr>';	

echo '<tr>
	<td style="border-bottom: 1px solid #ff8800">Date déchargement / chargement : </td>
	<td style="border-bottom: 1px solid #ff8800">'.$row1["Date_Dechargement"]; '</td>
</tr>';	

echo '<tr>
	<td style="border-bottom: 1px solid #ff8800">Lieu : </td>
	<td style="border-bottom: 1px solid #ff8800"><a target="1" href="https://www.google.com/maps/search/?api=1&query='.$row1["Coordonnees"].'">'.$row1["Lieu_Dechargement"].'</a></td>
</tr>';	

if(!empty($row1["Photo_Dechargement"]))
{
if (file_exists('../Dir_Params/Dir_Images/'.$row1["Photo_Dechargement"]) AND is_file('../Dir_Params/Dir_Images/'.$row1["Photo_Dechargement"])){
echo '<tr><td colspan="2"><center>Photo : </center></td></tr>
	<tr><td  colspan="2"><center><img src="../Dir_Params/Dir_Images/'.$row1["Photo_Dechargement"].'" width="500px" alt="image..."></center></td>
</tr>';
}
}
}


if($row1["Operation_Produit"]=="TRANSFORMATION")
{
	echo '<tr>
	<td colspan="2" style="font-size: 20px; font-weight: bold; text-align: center;">
		<div style="border:1px solid #ff8800; border-radius: 5px 5px 0px 0px; background: beige; padding: 5px; margin-top: 20px">
			';
			echo strtoupper("Conditionnement et stockage apres transformation");
		echo'
		</div>
	</td>
</tr>';

echo '<tr>
	<td style="border-bottom: 1px solid #ff8800">Produit utilisé pour le conditionnement : </td>
	<td style="border-bottom: 1px solid #ff8800">'.$row1["Produits_Conditionnement_Ap_Trans"]; '</td>
</tr>';

echo '<tr>
	<td style="border-bottom: 1px solid #ff8800">Autres conditions de stockage : </td>
	<td style="border-bottom: 1px solid #ff8800">'.$row1["Autres_Conditions_Stockage_Ap_Trans"]; '</td>
</tr>';


if(!empty($row1["Photo_Conditionnement_Ap_Trans"]))
{
if (file_exists('../Dir_Params/Dir_Images/'.$row1["Photo_Conditionnement_Ap_Trans"]) AND is_file('../Dir_Params/Dir_Images/'.$row1["Photo_Conditionnement_Ap_Trans"])){
echo '<tr><td colspan="2"><center>Photo : </center></td></tr>
	<tr><td colspan="2"><center><img src="../Dir_Params/Dir_Images/'.$row1["Photo_Conditionnement_Ap_Trans"].'" width="500px" alt="image..."></center></td>
</tr>';	
}
}
	
}


	echo '<tr>
	<td colspan="2" style="font-size: 20px; font-weight: bold; text-align: center;">
		<div style="border:1px solid #ff8800; border-radius: 5px 5px 0px 0px; background: beige; padding: 5px; margin-top: 20px">
			';
			echo strtoupper("Commentaire");
		echo'
		</div>
	</td>
</tr>';

echo '<tr>
	<td>Commentaire : </td>
	<td>'.$row1["Commentaire"]; '</td>
</tr>';
 ?>

			
		</table>
	</div>
	
</div>
</main>

</body>


</html>

<?php

}
}
else {echo 'Aucun résultat trouvé!';}
}
 ?>