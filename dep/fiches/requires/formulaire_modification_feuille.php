<div id="modif_feuille_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div id="reponse"></div>
      <div class="modal-dialog modal-feuille modal-body" role="document" >
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button id="fermer" type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="./images/close.png" alt="Fermer"></button>
               <center><h4 class="modal-title">Modification de la feuille</h4></center>
          </div>
          <br>
           <form action="traitement_jquery/affichage_mobile.php" method="POST" enctype="multipart/form-data" id="form_feuille_modification">
             <div class="col-md-12 col-lg-12">
                <div class="panel panel-default" >
                    <div class="panel-heading">
                      <span class="text-primary"></span>
                       <button <?php echo 'class="btn '.$Boutton_Style.'"'; ?> type="button" style="margin-left: 50px;" id="boutton_nouvelle_feuille_perso"><span class="glyphicon glyphicon-plus" style=""> Ajouter une colonne</span></button> 
                    </div>
                    <div class="panel-body" id="conteneur_form_modification_feuille">


                    </div>
                </div>
            </div>
           <div>
            <div class="row">
                <div class="col-sm-4 col-md-4 mb-4" align="center">
                <button style="width: 150px" <?php echo 'class="btn '.$Boutton_Style.'"'; ?> id="submit" type="submit">Modifier</button></div>
              </div><br>
     <details>

       <summary> Aide</summary>
               
              <div class="row" id="div_conseil">
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