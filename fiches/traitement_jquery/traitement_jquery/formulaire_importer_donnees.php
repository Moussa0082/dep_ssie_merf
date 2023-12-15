<?php
if($_GET){
  extract($_GET);
require_once '../fonctions/php/Fonctions.php';
  foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE Code_Feuille='.$feuille) as $row4)
  {
?>
<style type="text/css">
  .importer_feuille_perso{width: 90%!important}
</style>
<div class="modal-body ">
 <div class="row" style="overflow-x: auto">
    <div class="col-lg-12">
    <table border="1" style="border:1px solid silver; margin-bottom: 10px; border-radius: 3px; width: 100%; overflow-x: auto">
      <tr style="background: beige">
    <?php 
    $Code_TD="";
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE ( Code_Feuille='.$feuille.') ORDER BY Rang') as $row6)
    {echo '<th>'.$row6['Libelle_Ligne'].'</th>'; $Code_TD.='<td>'.$row6['Type_Ligne']."</td>"; }
     ?></tr>
     <tr">
       <?php echo $Code_TD; ?>
     </tr>
</table>
  </div>

 </div>

   <form method="POST" action="" id="importation_form">
    <div class="row">
      <input type="hidden" name="Code_Classeur">
      <div class="col-sm-4 col-md-4 mb-3"><label >Type d'importation</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
      	<input type="hidden" name="t" value="<?php echo $row4['Code_Feuille'] ?>">
        <select name="Option" class="form-control">
          <option value="CONSERVER">CONSERVER</option>
          <option value="ECRASER">ECRASER</option>
        </select>
      </div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Note</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="file" name="Fichier" class="form-control" required></div>
    </div><br>
    <div class="row">
     <div class="col-sm-4 col-md-4 mb-3"></div>
      <div class="col-sm-8 col-md-8 mb-3">
      <br><button style="width: 150px" class="btn btn-success" id="submit" type="submit">Importer</button></div>
    </div><br>
  </form>
</div>
<?php
  }
?>

<?php  } ?>