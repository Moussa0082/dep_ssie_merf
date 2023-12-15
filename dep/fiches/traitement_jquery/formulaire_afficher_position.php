<?php
if($_GET){
  extract($_GET);
require_once '../api/Fonctions.php';
  foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE Code_Feuille='.$feuille) as $row4)
  {
?>
<style type="text/css">
  .importer_feuille_perso{width: 90%!important}
</style>
<div class="modal-body ">
 <div class="row" style="color:black">
  <div class="col-lg-1 col-md-2 col-sm-1"></div>
  <div class="col-lg-1 col-md-2 col-sm-1">Login</div>
  <div class="col-lg-2 col-md-2 col-sm-2">Latitude</div>
  <div class="col-lg-2 col-md-2 col-sm-2">Longitude</div>
  

  <?php
  $Nb_Col=0;
  $Index_Col= array();
  $Res= FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE ( Code_Feuille=\''.$row4['Code_Feuille'].'\' AND Afficher=\'Oui\') ORDER BY Rang LIMIT 2');
  $Nb_Col=$Res->rowCount();      
  foreach ($Res as $row7)
    {echo '<div class="col-lg-2 col-md-2 col-sm-2">'.$row7['Libelle_Ligne'].'</div>'; $Index_Col[]=$row7['Nom_Collone'];} ?>
  <div class="col-lg-2 col-md-2 col-sm-2">Date d'insertion</div>
</div>
<br>
 <div class="row">
  <?php 

foreach (FC_Rechercher_Code('SELECT * FROM '.$row4["Table_Feuille"].' WHERE (Id='.$Id.')') as $row6)
{
  echo '<div class="col-lg-1 col-md-1 col-sm-1">';
  if(!empty($row6["LG"]) AND !empty($row6["LT"])){echo '<a href="https://www.google.com/maps/search/?api=1&query='.$row6["LT"].','.$row6["LG"].'" target="1"><span class="glyphicon glyphicon-map-marker"></span></a>';}
  echo '</div>
    <div class="col-lg-1 col-md-1 col-sm-1">'.$row6["Login"].'</div>
    <div class="col-lg-2  col-md-2 col-sm-2">'.$row6["LT"].'</div>
    <div class="col-lg-2  col-md-2 col-sm-2">'.$row6["LG"].'</div>';
    
    if($Nb_Col==1){echo '<div class="col-lg-2  col-md-2 col-sm-2">'.$row6[$Index_Col[0]].'</div>';}
  else if($Nb_Col==2){echo '<div class="col-lg-2  col-md-2 col-sm-2">'.$row6[$Index_Col[0]].'</div>';echo '<div class="col-lg-2  col-md-2 col-sm-2">'.$row6[$Index_Col[1]].'</div>';}
echo '<div class="col-lg-2  col-md-2 col-sm-2">'.$row6["Date_Insertion"].'</div>';
  }
   ?>
  
</div>

</div>
<?php
  }
?>

<?php  } ?>