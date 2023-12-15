<style type="text/css">
 .modal input, .modal select,.modal textarea{border: 2px solid <?php echo $Panel_Item_Style ; ?>;}
</style>

<?php 
switch (substr($_SERVER['PHP_SELF'], strripos($_SERVER['PHP_SELF'], "/")+1, strripos($_SERVER['PHP_SELF'], "php")-2))
{
case 'fonction':
 ?>
<!-- Fonction -->
<div id="modif_fonct_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" class="close" data-dismiss="modal"><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></button>
               <center><h4 class="modal-title">Modifier une fonction </h4></center>
          </div>
          <div class="modal-body">
   <form method="POST" action="" id="modif_fonct_form">
    <div class="row">
       <div class="col-sm-4 col-md-4 mb-3"><label>Description Fonction</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Description" name="f_descriptions" id="disc_fonct"></textarea><input type="hidden" name="id_fonctions" id="id_fonctions"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Nom Fonction</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Fonction" name="f_fonctions" id="f_fonctions"></div>
    </div><br>
    <div class="row">
     

        <div class="col-sm-4 col-md-4 mb-3"><label >Service</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <input type="text" class="form-control" placeholder="Service" name="f_services" id="f_services">
      
      <br><button style="width: 150px" <?php echo 'class="btn '.$Boutton_Style.'"'; ?> id="submit" type="submit">Modifier</button></div>
    </div><br>
  </form>
</div>
<div class="modal-footer"> <button id="fermer" type="button" class="btn btn-default" data-dismiss="modal">Fermer</button></div>
</div>
</div>
</div>
  <?php 
  break;
  case 'niveau':
 ?>
<!-- Niveau -->
<div id="modif_niveau_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" class="close" data-dismiss="modal"><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></button>
               <center><h4 class="modal-title">Modifier un niveau </h4></center>
          </div>
          <div class="modal-body">
   <form method="POST" action="" id="modif_form_niveau">
      <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Nombre Niveau</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="number" class="form-control" placeholder="Nombre" name="niveau_nombres" id="niveau_nombres"> <input type="hidden" name="id_niveau" id="id_niveau"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Libelle Niveau</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Libelle" name="niveau_libelles" id="niveau_libelles"></div>
    </div><br>
     <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Code Niveau</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Code Number" name="niveau_codes" id="niveau_codes"></div>
    </div><br>
     <div class="row">
     <div class="col-sm-4 col-md-4 mb-3"><label >Projet</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <select class="form-control" placeholder="" name="niveau_projets" id="niveau_projets">
          <option></option>
          <?php foreach(FC_Rechercher_Code('SELECT * FROM t_projet') as $rows){
             echo'<option value="'.$rows['id_projet'].'">'.htmlspecialchars_decode($rows['sigle_projet']).'</option>';}
          ?>
        </select></div>
    </div><br>
    <div class="row">
     <div class="col-sm-4 col-md-4 mb-3"><label >Programme</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <select class="form-control" placeholder="" name="niveau_programmes" id="niveau_programmes">
          <option></option>
          <?php foreach(FC_Rechercher_Code('SELECT * FROM t_programme') as $rows){
             echo'<option value="'.$rows['id_programme'].'">'.htmlspecialchars_decode($rows['sigle_programme']).'</option>';}
          ?>
        </select>
      <br><button style="width: 150px" <?php echo 'class="btn '.$Boutton_Style.'"'; ?> id="submit" type="submit">Modifier</button></div>
    </div><br>
  </form>
</div>
</div>
</div>
</div>
   <?php 
  break;
  case 'partenaire':
 ?>
<!-- partenaire -->
<div id="modif_partenaire_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" class="close" data-dismiss="modal"><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></button>
               <center><h4 class="modal-title">Modifier un partenaire </h4></center>
          </div>
          <div class="modal-body">
   <form method="POST" action="" id="modif_partenaire_form">
      <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Nom Partenaire</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="hidden" class="form-control" placeholder="Nom" name="id_partenaires" id="id_partenaire_modif"><input type="text" class="form-control" placeholder="Nom" name="nom_partenaires" id="nom_partenaire_modif"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Code Partenaire</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Code" name="code_partenaires" id="code_partenaire_modif"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Type Partenaire</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <select class="form-control" placeholder="" name="type_partenaires" id="type_partenaire_modif">
          <option></option>
           <?php foreach(FC_Rechercher_Code('SELECT * FROM t_type_partenaire') as $rows){
             echo'<option value="'.$rows['id_type_partenaire'].'">'.htmlspecialchars_decode($rows['nom_type_partenaire']).'</option>';}
          ?>
        </select>
      </div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Adresse Partenaire</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Adresse" name="adresse_partenaires" id="adresse_partenaire_modif"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Contact Partenaire</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Contact" name="contact_partenaires" id="contact_partenaire_modif"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Site web</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Site web" name="site_webs" id="site_web_modif"></div>
    </div><br>
     <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Email Partenaire</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Email" name="email_partenaires" id="email_partenaire_modif"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Map Partenaire</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Map" name="map_partenaires" id="map_partenaire_modif"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Description</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Description" name="descriptions" id="desc_partenaire_modif"></textarea>
      <br><button style="width: 150px" <?php echo 'class="btn '.$Boutton_Style.'"'; ?> id="submit" type="submit">Modifier</button></div>
    </div><br>
  </form>
</div>
</div>
</div>
</div>
   <?php 
  break;
  case 'type_partenaire':
 ?>
<!-- type partenaire -->
<div id="modif_type_partenaire_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" class="close" data-dismiss="modal"><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></button>
               <center><h4 class="modal-title">Modifier le type de partenaire </h4></center>
          </div>
          <div class="modal-body">
   <form method="POST" action="" id="modif_form_type_partenaire">
      <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Nom Partenaire</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Nom" name="nom_type_partenaires" id="nom_type_partenaires"><input type="hidden" name="id_type_partenaires" id="id_type_partenaires"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Description</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Description" name="desc_type_partenaires" id="desc_type_partenaires"></textarea>
      <br><button style="width: 150px" <?php echo 'class="btn '.$Boutton_Style.'"'; ?> id="submit" type="submit">Modifier</button></div>
    </div><br>
  </form>
</div>
</div>
</div>
</div>
   <?php 
  break;
  case 'programme':
 ?>
<!-- programme -->
<div id="modif_programme_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" class="close" data-dismiss="modal"><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></button>
               <center><h4 class="modal-title">Modifier un programme </h4></center>
          </div>
          <div class="modal-body">
  <form method="POST" action="" id="modif_form_programme">
      <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Sigle programme</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Sigle" name="sigle_programmes" id="sigle_programmes"><input type="hidden" name="id_programmes" id="id_programmes"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Nom Programme</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Nom" name="nom_programmes" id="nom_programmes"></textarea></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Vision</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Vision" name="visions" id="visions"></textarea></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Objectif</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Objectif" name="objectif_prog" id="objectif_prog"></textarea></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Statut</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <select class="form-control"  name="statut_prog" id="statut_prog">
          <option></option>
          <option>Nouveau</option>
          <option>En cours</option>
          <option>Clôturé</option>
        </select></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Année début</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Année début" name="annee_debut_prog" id="annee_debut_prog"></div>
    </div><br> 
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Année fin</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Année fin" name="annee_fin_prog" id="annee_fin_prog"></div>
    </div><br>

    <div class="row">
     <div class="col-sm-4 col-md-4 mb-3"></div>
      <div class="col-sm-8 col-md-8 mb-3">
      <br><button style="width: 150px" <?php echo 'class="btn '.$Boutton_Style.'"'; ?> id="submit" type="submit">Modifier</button></div>
    </div><br>
  </form>
</div>
</div>
</div>
</div>
   <?php 
  break;
  case 'groupe_travail':
 ?>
<!-- groupe de travail -->
<div id="modif_grp_travail_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" class="close" data-dismiss="modal"><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></button>
               <center><h4 class="modal-title">Modifier un groupe de travail </h4></center>
          </div>
          <div class="modal-body">
   <form method="POST" action="" id="modif_grp_travail_form">
      <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Code Groupe de travail</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Code" name="code_groupes_travails" id="code_groupes_travails" maxlength="10"><input type="hidden" name="id_groupes_travails" id="id_groupes_travails"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Nom Groupe de travail</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Nom" name="nom_groupes_travails" id="nom_groupes_travails"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Partenaire</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <select class="form-control" placeholder="" name="partenaires_grp" id="partenaires_grp">
          <option></option>
           <?php  
         foreach(FC_Rechercher_Code('SELECT * FROM t_partenaire') as $rows) 
          {
           echo'<option value="'.$rows['id_partenaire'].'">'.htmlspecialchars_decode($rows['nom_partenaire']).'</option>';
          }?> 
        </select>
      </div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Thématique</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Thématique" name="thematiques_grp" id="thematiques_grp"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Sécretaire</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="number" class="form-control" placeholder="Sécretaire" name="secretaires_grp" id="secretaires_grp"></div>
    </div><br> 
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Date de création</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="date" class="form-control" placeholder="Date de création" name="date_creations_grp" id="date_creations_grp"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Description</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Description" name="descriptions_grp" id="descriptions_grp"></textarea>
     
      <br><button style="width: 150px" <?php echo 'class="btn '.$Boutton_Style.'"'; ?> id="submit" type="submit">Modifier</button></div>
    </div><br>
  </form>
</div>
</div>
</div>
</div>
   <?php 
  break;
  case 'zone_collecte':
 ?>
<!-- zone de collecte -->
<div id="modif_zone_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" class="close" data-dismiss="modal"><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></button>
               <center><h4 class="modal-title">Modifier une zone de collecte </h4></center>
          </div>
          <div class="modal-body">
   <form method="POST" action="" id="modif_form_zone">
    <div class="row">
      <div class="col-sm-12 col-md-12 mb-3">
        <input type="hidden" class="form-control" name="id_zones" id="id_zones"></div>
    </div><br>
      <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Reference</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Reference" name="reference_modif" id="reference_modif"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Type de zone</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <select class="form-control" id="type_zone_modif" name="type_zone_modif">
          <option></option>
             <?php  
         foreach(FC_Rechercher_Code('SELECT * FROM t_type_zone') as $rows) 
          {
           echo'<option value="'.$rows['id_type'].'">'.$rows['definition'].'</option>';
          }?>
        </select>
      </div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Superficie</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="number" step="0.1" class="form-control" placeholder="Superficie" name="superficie_modif" id="superficie_modif"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Couche</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Couche" name="couche_modif" id="couche_modif"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Longitude</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="number" step="0.1" class="form-control" placeholder="Longitude" name="longitude_modif" id="longitude_modif"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Latitude</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="number" step="0.1" class="form-control" placeholder="Latitude" name="latitude_modif" id="latitude_modif"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Description</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Description" name="description_zone_modif" id="description_zone_modif"></textarea>
      <br><button style="width: 150px" <?php echo 'class="btn '.$Boutton_Style.'"'; ?> id="submit" type="submit">Modifier</button></div>
    </div><br>
  </form>
</div>
</div>
</div>
</div>

<?php 
  break;
  case 'type_zone':
 ?>
<!-- type zone -->
<div id="modif_type_zone_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" class="close" data-dismiss="modal"><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></button>
               <center><h4 class="modal-title">Modifiers le type de zone </h4></center>
          </div>
          <div class="modal-body">
   <form method="POST" action="" id="modif_type_zone_form">
      <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Définition de la zone</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Définition de zone" name="def_type_zones" id="def_type_zones"><input type="hidden" name="id_type_zones" id="id_type_zones"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Description</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Description de la zone" name="desc_type_zones" id="desc_type_zones"></textarea>
      <br><button style="width: 150px" <?php echo 'class="btn '.$Boutton_Style.'"'; ?> id="submit" type="submit">Modifier</button></div>
    </div><br>
  </form>
</div>
</div>
</div>
</div>
   <?php 
  break;
  case 'periode':
 ?>
<!-- periode -->
<div id="modif_periode_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" class="close" data-dismiss="modal"><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></button>
               <center><h4 class="modal-title">Modifier la période </h4></center>
          </div>
          <div class="modal-body">
   <form method="POST" action="" id="modif_periode_form">
      <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Période de collecte</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Période de collecte" name="periode_collectes" id="periode_collectes"></div>
    </div><br>
       <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Source de données</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Source de données" name="source_donneess" id="source_donneess"></div>
    </div><br>
      <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Date de validation</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="date" class="form-control" placeholder="Date de validation" name="date_validations" id="date_validations"></div>
    </div><br>
      <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>valeur de période</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="number" class="form-control" placeholder="valeur de période" name="valeur_periodes" id="valeur_periodes"></div>
    </div><br>
     <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Observation</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Observation" name="observation_periodes" id="observation_periodes"></textarea>
        </div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Réf d'indicateur</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Réf d'indicateur" name="ref_indicateurs" id="ref_indicateurs">
      <br><button style="width: 150px" <?php echo 'class="btn '.$Boutton_Style.'"'; ?> id="submit" type="submit">Modifier</button></div>
    </div><br>
  </form>
</div>
</div>
</div>
</div>
   <?php 
  break;
  case 'categorie_indicateur':
 ?>
<!--Catégorie d'indicateur -->
<div id="modif_cat_ind_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div id="reponse"></div>
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" class="close" data-dismiss="modal"><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></button>
               <center><h4 class="modal-title">Modifier une catégorie d'indicateur </h4></center>
          </div>
          <div class="modal-body">
   <form method="POST" action="" id="form_modif_cat_indicateur">
     <div class="row">
      <div class="col-sm-12 col-md-12 mb-3">
        <input type="hidden" class="form-control" name="id_categorie_indicateurs" id="id_categorie_indicateurs"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Nom Catégorie d'indicateur</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Nombre" name="nom_categories" id="nom_categories"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Description</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Description" name="description_categories" id="description_categories"></textarea>
      <br><button style="width: 150px" <?php echo 'class="btn '.$Boutton_Style.'"'; ?> id="submit" type="submit">Modifier</button></div>
    </div><br>
  </form>
</div>
</div>
</div>
</div>
   <?php 
  break;
  case 'localite':
 ?>
<!--localité -->
<div id="modif_localite_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div id="reponse"></div>
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" class="close" data-dismiss="modal"><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></button>
               <center><h4 class="modal-title">Modifier une localité </h4></center>
          </div>
          <div class="modal-body">
   <form method="POST" action="" id="modif_form_localite">
    <div class="row">
      <div class="col-sm-12 col-md-12 mb-3">
        <input type="hidden" class="form-control" name="id_resultats" id="id_resultats"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Période</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="number" class="form-control" placeholder="Période" name="periodes_modif" id="periode_modif"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Commune</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="number" class="form-control" placeholder="Commune" name="communes_modif" id="commune_modif"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Valeur de période</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="number" class="form-control" placeholder="Valeur de période" name="valeur_periodes_modif" id="valeur_periode_modif"></div>
    </div><br>
      <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Date de collecte</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="date" class="form-control" placeholder="Date de collecte" name="date_collectes_modif" id="date_collecte_modif"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Observation</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Observation" name="observations_modif" id="observation_modif"></textarea>
      <br><button style="width: 150px" <?php echo 'class="btn '.$Boutton_Style.'"'; ?> id="submit" type="submit">Modifier</button></div>
    </div><br>
  </form>
</div>
</div>
</div>
</div>
   <?php 
  break;
  case 'projet':
 ?>

<!--Projet -->
<div id="modif_projet_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div id="reponse"></div>
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" class="close" data-dismiss="modal"><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></button>
               <center><h4 class="modal-title">Modifier un Projet </h4></center>
          </div>
          <div class="modal-body">
   <form method="POST" action="" id="modif_form_projet">
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Code</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Code" name="code_projets" id="code_projets"><input type="hidden" name="id_projets" id="id_projets"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Sigle</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Sigle" name="sigle_projets" id="sigle_projets"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Intitulé</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Intitulé" name="intitules" id="intitules"></textarea></div>
    </div><br>
      <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Durée</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="number" step="1" min="0" class="form-control" placeholder="Durée" name="durees" id="durees"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Date Signature</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="date" class="form-control" placeholder="Date Signature" name="date_signatures" id="date_signatures"></div>
    </div><br>
     <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Statut</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <select class="form-control"  name="statuts" id="statuts">
          <option></option>
          <option>Nouveau</option>
          <option>En cours</option>
          <option>Clôturé</option>
        </select></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Partenaire Financier</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <select class="form-control"  name="id_partenaire_financiers" id="id_partenaire_financiers">
           <?php  
         foreach(FC_Rechercher_Code('SELECT * FROM t_Partenaire') as $rows) 
          {
           echo'<option value="'.$rows['id_partenaire'].'">'.htmlspecialchars_decode($rows['nom_partenaire']).'</option>';
          }?> 
        </select></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label >Partenaire Execution</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <select class="form-control"  name="id_partenaire_executions" id="id_partenaire_executions">
           <?php  
         foreach(FC_Rechercher_Code('SELECT * FROM t_Partenaire') as $rows) 
          {
           echo'<option value="'.$rows['id_partenaire'].'">'.htmlspecialchars_decode($rows['nom_partenaire']).'</option>';
          }?> 
        </select></div>
    </div><br>
        <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label >Domaine</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <textarea class="form-control" placeholder="Domaine" name="domaines" id="domaines"></textarea></div>
    </div><br>
      <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label >Zone</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <textarea class="form-control" placeholder="Zone" name="zones" id="zone_projets"></textarea></div>
    </div><br>
      <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label >Nature</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <textarea class="form-control" placeholder="Nature" name="natures" id="nature_projets"></textarea></div>
    </div><br>
      <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label >Date de demarrage</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
      <input type="date" class="form-control" placeholder="Date de demarrage" name="date_demarrages" id="date_demarrages"></div>
    </div><br>
    <div class="row">
     <div class="col-sm-4 col-md-4 mb-3"></div>
      <div class="col-sm-8 col-md-8 mb-3">
      <br><button style="width: 150px" <?php echo 'class="btn '.$Boutton_Style.'"'; ?> id="submit" type="submit">Modifier</button></div>
    </div><br>
  </form>
</div>
</div>
</div>
</div>   <?php 
  break;
  case 'utilisateur':
 ?>
<!-- Utilisateur -->

<div id="modif_utilisateur_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" class="close" data-dismiss="modal"><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></button>
               <center><h4 class="modal-title">Modifier un utilisateur</h4></center>
          </div>
          <div class="modal-body">
   <form method="POST" action="" id="modif_form_personnel" enctype="multipart/form-data"> 
      <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Id Personnel</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Id Personnel" name="id_personnels" id="id_personnels"><input type="hidden" name="num" id="num"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Titre</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Titre" name="titres_personnel" id="titres_personnel"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Mot de passe</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <input type="password" class="form-control" placeholder="Mot de passe" name="mot_de_passes" id="mot_de_passes">
      </div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Nom</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Nom" name="noms_personnel" id="noms_personnel"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Prénom</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Prénom" name="prenoms_personnel" id="prenoms_personnel"></div>
    </div><br> 
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Contact</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="text" class="form-control" placeholder="Contact" name="contacts_personnel" id="contacts_personnel"></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Email</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="email" class="form-control" placeholder="Email" name="emails_personnel" id="emails_personnel">
      </div>
    </div>
    <br>
    <div class="row">
       <div class="col-sm-4 col-md-4 mb-3"><label >Fonction</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <option></option>
        <select class="form-control" name="fonctions_personnel" id="fonctions_personnel">
          <?php  
         foreach(FC_Rechercher_Code('SELECT * FROM t_fonction') as $rows) 
          {
           echo'<option value="'.$rows['id_fonction'].'">'.htmlspecialchars_decode($rows['fonction']).'</option>';
          }?> 
        </select>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Description</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" placeholder="Description" name="description_utilisateurs" id="description_utilisateurs"></textarea>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Avatar</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><div style="width: 48%; height:35px;float: left; "><img  id="img" style="width: 100%; height:35px; " title="Ancien avatar"></div><input type="file" accept="image/*"  style="height: 35px; width: 50%; border: none;" name="avatars" id="avatars">
      <input type="hidden" name="ancien_avatar" id="ancien_avatar">
      </div>
    </div>
    <br>
    <div class="row">
       <div class="col-sm-4 col-md-4 mb-3"><label >Niveau</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <option></option>
        <select class="form-control" name="niveaus_personnel" id="niveaus_personnel">
          <?php  
         foreach(FC_Rechercher_Code('SELECT * FROM t_niveau_config') as $rows) 
          {
           echo'<option value="'.$rows['id'].'">'.htmlspecialchars_decode($rows['libelle']).'</option>';
          }?> 
        </select>
      </div>
    </div>
    <br>
    <div class="row">
       <div class="col-sm-4 col-md-4 mb-3"><label >Partenaire</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <select class="form-control" name="partenaires_personnel" id="partenaires_personnel">
          <option></option>
          <?php  
         foreach(FC_Rechercher_Code('SELECT * FROM t_partenaire') as $rows) 
          {
           echo'<option value="'.$rows['id_partenaire'].'">'.htmlspecialchars_decode($rows['nom_partenaire']).'</option>';
          }?> 
        </select>
      </div>
    </div>
    <br>
    <div class="row">
       <div class="col-sm-4 col-md-4 mb-3"><label >Groupe</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <select class="form-control" name="groupes_personnel" id="groupes_personnel">
          <option></option>
           <?php  
         foreach(FC_Rechercher_Code('SELECT * FROM t_groupes_travail') as $rows) 

          {
           echo'<option value="'.$rows['id_groupes_travail'].'">'.htmlspecialchars_decode($rows['nom_groupes_travail']).'</option>';
          }?> 
        </select>
      </div>
    </div>
    <br>
    <div class="row">
       <div class="col-sm-4 col-md-4 mb-3"><label >Programme</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <select class="form-control" name="programmes_personnel" id="programmes_personnel">
          <option></option>
           <?php  
         foreach(FC_Rechercher_Code('SELECT * FROM t_programme') as $rows) 
          {
           echo'<option value="'.$rows['id_programme'].'">'.htmlspecialchars_decode($rows['nom_programme']).'</option>';
          }?> 
        </select>
      </div>
    </div>

    <br>
    <div class="row">
        <div class="col-sm-4 col-md-4 mb-3"><label ></label></div>
        <div class="col-sm-8 col-md-8 mb-3">
      <br><button style="width: 150px" <?php echo 'class="btn '.$Boutton_Style.'"'; ?> id="submit" type="submit">Modifier</button></div>
    </div><br>
  </form>
</div>
</div>
</div>
  
</div>
 <?php 
break;
default : 
break;
}
 ?>