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
  <div class="col-lg-1"></div>
  <div class="col-lg-2">Login</div>
  <div class="col-lg-3">Latitude</div>
  <div class="col-lg-3">Longitude</div>
  <div class="col-lg-3">Date d'insertion</div>
</div>
<br>
 <div class="row">
  <?php 

foreach (FC_Rechercher_Code('SELECT * FROM '.$row4["Table_Feuille"].' WHERE (Id='.$Id.')') as $row6)
{
  echo '<div class="col-lg-1">';
  if(!empty($row6["LG"]) AND !empty($row6["LT"])){echo '<a href="https://www.google.fr/maps/@'.$row6["LT"].','.$row6["LG"].'" target="1"><span class="glyphicon glyphicon-map-marker"></span></a>';}
  echo '</div>
    <div class="col-lg-2">'.$row6["Login"].'</div>
    <div class="col-lg-3">'.$row6["LT"].'</div>
    <div class="col-lg-3">'.$row6["LG"].'</div>
    <div class="col-lg-3">'.$row6["Date_Insertion"].'</div>';}
   ?>
  
</div>

</div>
<?php
  }
?>

<?php  } ?>