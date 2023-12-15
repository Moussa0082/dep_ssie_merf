<?php 
if(isset($_GET['Code_Article']) AND !empty($_GET['Code_Article']))	
{extract($_GET);
	require_once '../api/Fonctions.php';

foreach (FC_Rechercher_Code("SELECT * FROM t_rapport_article WHERE Code_Article=".$Code_Article) as $row9) 
{ ?>




   <form method="POST" action="" id="form_article_modif">
    <div class="row">    

        <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Rapport d'indicateur</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
      	<input type="hidden" name="code_article" <?php echo 'value="'.$row9['Code_Article'].'"'; ?>>
         <select class="form-control" required name="code_rapport" id="code_rapport">
          <option value=""></option>
      <?php foreach (FC_Rechercher_Code("SELECT Code_Rapport, id_indicateur_cr, intitule_indicateur_cr  FROM t_indicateur_cadre_resultat INNER JOIN t_rapport_indicateur ON (t_indicateur_cadre_resultat.id_indicateur_cr=t_rapport_indicateur.Indicateur) WHERE Affichage='Tous'") as $row3) 
      {
      	if($row3["Code_Rapport"]==$row9["Code_Rapport"]){echo '<option value="'.$row3["Code_Rapport"].'" selected>'.$row3["intitule_indicateur_cr"].'</option>';}
      	else {echo '<option value="'.$row3["Code_Rapport"].'">'.$row3["intitule_indicateur_cr"].'</option>';}
  }
        
       ?> 

        </select>
       </div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label >Titre</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <input type="text" class="form-control" required placeholder="Titre" <?php echo 'value="'.htmlspecialchars_decode($row9['Titre_Article']).'"'; ?> name="titre_article" id="titre_article" maxlength="255">
      </div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Description</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" maxlength="2500" required placeholder="Description" name="description_article" id="description_article"><?php echo htmlspecialchars_decode($row9['Description_Article']); ?></textarea></div>
    </div><br>


       <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label >Photo (2Mo Maximum)</label></div>
      <div class="col-sm-8 col-md-8 mb-3"> <?php if($row9['Photo']!=""){echo '<img src="images/'.$row9['Photo'].'" width="100px" height="100px" alt="...">';} ?>
        <input type="file" accept="image/*" class="form-control" name="photo_article" id="photo_article">
      </div>
    </div><br>

    <div class="row">
     <div class="col-sm-4 col-md-4 mb-3"></div>
      <div class="col-sm-8 col-md-8 mb-3">
      <br><button style="width: 150px" class="btn btn-info" id="submit" type="submit">Modifier</button></div>
    </div><br>
  </form>

<?php }

 } ?>
 
