<?php
switch (substr((substr($_SERVER['PHP_SELF'], strripos($_SERVER['PHP_SELF'], "/")+1, strripos($_SERVER['PHP_SELF'], "php")-2)),0,-4))
{
  case 'fiches_dynamiques':
?>
<!--Classeur -->
<div id="add_classeur_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div id="reponse"></div>
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="./images/close.png" alt="Fermer"></button>
               <center><h4 class="modal-title">Ajouter un classeur </h4></center>
          </div>
          <div class="modal-body">

<?php 

if(isset($_SESSION['clp_projet']) AND !empty($_SESSION['clp_projet']))
{?>
   <form method="POST" action="" id="form_classeur">
    <div class="row">
      <input type="hidden" name="Code_Classeur">
      <div class="col-sm-4 col-md-4 mb-3"><label >Libellé</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <input type="hidden" name="id_projet" <?php echo 'value="'.$_SESSION['clp_projet'].'"'; ?>>
        <textarea class="form-control" required placeholder="Libellé" name="libelle_classeur" id="libelle_classeur"></textarea>
      </div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Note</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" required placeholder="Note" name="note_classeur" id="note_classeur"></textarea></div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Couleur</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><input type="color" value="#F1F3F6" class="form-control" placeholder="Couleur" name="couleur" id="couleur"> </div>
    </div><br>
    <div class="row">
     <div class="col-sm-4 col-md-4 mb-3"></div>
      <div class="col-sm-8 col-md-8 mb-3">
      <br><button style="width: 150px" <?php echo 'class="btn '.$Boutton_Style.'"'; ?> id="submit" type="submit">Inserer</button></div>
    </div><br>
  </form>

<?php  }
else
{?>
<div class="row ">
<div align="center"><h2 align="center"><span style="color:red;">Veuillez s&eacute;lectionner un Projet</span></h2><img src="./images/dialog-warning.png" width="auto" height="auto" style="margin-top: 27px;" ></div>
</div>
<?php }
 ?>

</div>
<div class="modal-footer"> <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button></div>
</div>
</div>
</div>
<?php 

  break;
  case 'classeur_details':
?>
<!--feuille-->
<div id="add_feuille_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div id="reponse"></div>
      <div class="modal-dialog modal-feuille modal-body" role="document" >
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="./images/close.png" alt="Fermer"></button>
               <center><h4 class="modal-title">Ajouter une nouvelle feuille </h4></center>
          </div>
          <br>
           <form action="" method="POST" enctype="multipart/form-data" id="form_feuille_insertion">
             <div class="col-md-12 col-lg-12">
                <div class="panel panel-default" >
                    <div class="panel-heading">
                      <span class="text-primary">Nouvelle feuille</span>
                       <button <?php echo 'class="btn '.$Boutton_Style.'"'; ?> type="button" style="margin-left: 50px;" id="boutton_nouvelle_feuille"><span class="glyphicon glyphicon-plus" style=""> Ajouter une colonne</span></button> 
                    </div>
                    <div class="panel-body" id="panel-body">
                       
                          <div class="row">
                              <div class="col-sm-1 col-md-1 mb-1"><label>Nom Feuille<font style="color: red" >*</font></label></div>
                              <div class="col-sm-5 col-md-5 mb-5"><input type="text" maxlength="30" required class="form-control" placeholder="Nom feuille " name="Nom_Feuille" id="Nom_Feuille"></div>
                              <div class="col-sm-1 col-md-1 mb-1"><label> Libelle feuille<font style="color: red" >*</font></label></div>
                              <div class="col-sm-4 col-md-4 mb-4"><textarea required class="form-control" placeholder="Libelle" name="Libelle_Feuille" id="Libelle_Feuille"></textarea></div>
                              <input type="hidden" name="Code_Classeur" <?php if(isset($_GET['c'])){echo ' value="'.base64_decode($_GET['c']).'"';} ?>>
                              <div class="col-sm-1 col-md-1 mb-1"></div>
                            </div><br>
                            <div class="row">
                              <div class="col-sm-1 col-md-1 mb-1"><label>Nb. ligne (impr.)</label></div>
                              <div class="col-sm-2 col-md-2 mb-2"><input type="number" min="1" step="1" value="10" class="form-control" placeholder="Nombre de ligne à imprimer" name="Nb_Ligne_Impr" id="Nb_Ligne_Impr"></div>
                              <div class="col-sm-1 col-md-1 mb-1"><label> Icone</label></div>
                              <div class="col-sm-2 col-md-2 mb-2"><input type="file" accept="image/*" class="form-control" style="height: 35px; width: 100%; border: none;" name="Icone" id="Icone"></div>
                               <div class="col-sm-1 col-md-1 mb-1"><label> Note</label></div>
                              <div class="col-sm-4 col-md-4 mb-4"> <textarea  class="form-control" placeholder="Note" name="Note" id="Note"></textarea>
                              </div>
                              <div class="col-sm-1 col-md-1 mb-1"></div>
                            </div>
                            <br>
                             <div class="row">
                                <div class="col-sm-2 col-md-2 mb-2">
                                  <label> Nom Colonne </label>
                                 
                                </div>
                                <div class="col-sm-2 col-md-1.5 mb-2">
                                  <label> Libelle Colonne </label>
                                 
                                </div>
                                <div class="col-sm-2 col-md-2 mb-2">
                                  <label> Type Colonne </label>
                                </div>
                                 <div class="col-sm-2 col-md-2 mb-2">
                                  <label > Requis </label>
                                  
                                </div>
                                <div class="col-sm-2 col-md-2 mb-2"><label > Afficher</label>
                                  
                                </div>
                                <div class="col-sm-1 col-md-1 mb-1"></div>
                                <div class="col-sm-1 col-md-1 mb-1">
                                  
                                </div>
                              </div>

                              <div class=" div_ligne_feuille" id="div_ligne_feuille_1">
                              <div class="row">
                                <div class="col-sm-2 col-md-2 mb-2">
                                    <textarea type="text" class="form-control"  style="height: 50px !important;" required placeholder="Nom Colonne " name="nom_Ligne[]" id="nom_Ligne[]"></textarea>
                                </div>
                                <div class="col-sm-2 col-md-1.5 mb-2">
                                    <textarea type="text" class="form-control" required  style="height: 50px !important;" placeholder="Libelle" name="libelle_Ligne[]" id="libelle_Ligne[]"></textarea>
                                </div>
                                
                                <div class="col-sm-2 col-md-2 mb-2">
                                  <select class="form-control" onchange="Charger_Div(this.value, 1)" required name="type_Ligne[]" id="type_Ligne[]">
                                     
                                     <?php foreach (FC_Rechercher('t_feuille_ligne_type') as $row7)
                                     {
                                       echo '<option value="'.$row7['Valeur_Feuille_Ligne_Type'].'">'.$row7['Valeur_Feuille_Ligne_Type'].'</option>';
                                     } ?>
                                  </select>
                                </div>
                                 <div class="col-sm-2 col-md-2 mb-2">
                                  <select class="form-control" placeholder="" required name="requis[]" id="requis[]">
                                     <option value="Oui">Oui</option>
                                     <option value="Non">Non</option>
                                  </select>
                                </div>
                                <div class="col-sm-2 col-md-2 mb-2">
                                  <select class="form-control" placeholder="" required name="afficher[]" id="afficher[]">
                                    <option value="Oui">Oui</option>
                                    <option value="Non">Non</option>
                                  </select>
                                </div>

                                <div class="col-sm-1 col-md-1 mb-1"></div>
                                <div class="col-sm-1 col-md-1 mb-1">
                                  <input type="hidden" name="valeur[]" id="Valeur_1">
                                  
                                  <div class="btn btn-xs btn-default" title="Supprimer" ><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></div>
                                </div>

                              </div>
                              
                              <div class="row" id="Under_Div_1">
                              </div>
                            </div>

                    </div>
                </div>
            </div>
           <div>
            <div class="row">
                <div class="col-sm-4 col-md-4 mb-4" align="center">
                <button style="width: 150px" <?php echo 'class="btn '.$Boutton_Style.'"'; ?> id="submit" type="submit">Enregistrer</button></div>
              </div><br>

             
     <details>

       <summary> Aide</summary>
       
              <div class="row">
                <div class="col-sm-1 col-md-1 mb-1"></div>
                <div class="col-sm-10 col-md-10 mb-10" style="" align="left">
                  <ul style="font-size: 14px; border:1px solid silver; border-radius: 3px">
                    <li class="text text-danger">Nom Colonne :  
                      <span class="text text-primary"> le nom de la colonne</span></li>

                      <li class="text text-danger">Libelle Colonne :  
                      <span class="text text-primary"> le text qui sera affiché lors de l'affichage ou de la saisie des données</span></li>

                      <li class="text text-danger">Type Colonne :  
                      <span class="text text-primary"> le type de données que va contenir la colonne</span></li>

                      <li class="text text-danger">Requis :  
                      <span class="text text-primary"> si la saisie de la colonne est obligatoire</span></li>
                      <li class="text text-danger">Afficher :  
                      <span class="text text-primary"> si la colonne doit être affichée</span></li>
                  </ul>

                  <ul style="font-size: 14px; border:1px solid silver; border-radius: 3px">
                    Ces types de données doivent être utilisés comme suite :

                    <li class="text text-danger">CHOIX :  
                      <span class="text text-primary">Exemple pour Homme et Femme : Homme ; Femme</span></li>

                    <li class="text text-danger">RAPPORT :  
                      <span class="text text-primary">Colonne1/Colonne2 : Colonne1 ; Colonne2</span></li>
                  
                    <li class="text text-danger">PRODUIT :  
                      <span class="text text-primary">Colonne1*Colonne2 : Colonne1 ; Colonne2</span></li>

                    <li class="text text-danger">SOMME :  
                      <span class="text text-primary">Colonne1+Colonne2 : Colonne1 ; Colonne2</span></li>

                    <li class="text text-danger">DIFFERENCE :  
                      <span class="text text-primary">Colonne1-Colonne2 : Colonne1 ; Colonne2</span></li>

                    <li class="text text-danger">MOYENNE :  
                      <span class="text text-primary">Colonne1,Colonne2,Colonne3 : Colonne1 ; Colonne2 ; Colonne3</span></li>
                  </ul>
                </div>
              </div>
</details> 
              <br>

              </div>
          </form>
<div class="modal-footer"> <button id="fermer" type="button" class="btn btn-default" data-dismiss="modal">Fermer</button></div>          
</div>
</div>
</div>

<?php  
  default:
    # code...
    break;
}
require_once 'popup.php';?>