<!-- Navigation -->
<aside id="menu" style="overflow-y: auto; margin-bottom: 40px; position: fixed">
    <div id="navigation">
        <div class="profile-picture">

            <div class="stats-label text-color">
                <span class="font-extra-bold font-uppercase">
                    
                    <?php echo substr($_SESSION["prenom"],0, 1).".".$_SESSION["nom"];?>
                </span>
                
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                        <small class="text-muted"><b class="caret"></b></small>
                    </a>
                    <ul class="dropdown-menu animated flipInX m-t-xs">
                        <li><a href="#">Profil</a></li>
                        <li><a href="#">Préférences</a></li>
                    </ul>
                </div>


            </div>
        </div>
<style type="text/css">
    #side-menu li a {font-size: 10px; font-family:arial}
    #side-menu .active2 > a{color: black!important;}
    #side-menu .active2 > .nav-second-level{height: auto}
</style>
        <ul class="nav" id="side-menu">
            <li class="nav-item" id="Nav_Menu_Accueil">
                <a href="menu.php"> <span class="nav-label glyphicon glyphicon-home"></span>
                    <!--<span class="label label-success pull-right">start</span>--> Accueil</a>
            </li>
            <li class="nav-item" id="Nav_Menu_Parametrage">
                <a href="#"> <span class="nav-label glyphicon glyphicon-cog " > </span> Paramétrage <b class="caret" id="act"></b></a>
                <ul class="nav nav-second-level">
                        <li id="Nav_Sous_Menu_Localite"><a href="localite.php" title="Localités">Localités</a></li>
                        <li id="Nav_Sous_Menu_Zone_Collecte"><a href="zone_collecte.php" title="Zones de collecte">Zones de collecte</a></li>
                        <li id="Nav_Sous_Menu_Partenaire"><a href="partenaire.php" title="Partenaires">Partenaires</a></li>
                        <li id="Nav_Sous_Menu_Utilisateur"><a href="utilisateur.php" title="Utilisateurs">Utilisateurs</a></li>
                        <li id="Nav_Sous_Menu_Fonction"><a href="fonction.php" title="Fonction">Fonction</a></li>
                        <li id="Nav_Sous_Menu_Programme"><a href="programmes.php" title="Programmes">Programmes</a></li>
                        <li id="Nav_Sous_Menu_Projet"><a href="projets.php" title="Projets">Projets</a></li>
                        <li id="Nav_Sous_Menu_"><a href="" title="Services et directions">Services et directions</a></li>
                        <li id="Nav_Sous_Menu_Categorie_Indicateur"><a href="categorie_indicateur.php" title="Catégories d'indicateurs">Catégories d'indicateurs</a></li>
                        <li id="Nav_Sous_Menu_"><a href="" title="Autres paramétres">Autres paramétres</a></li>
                    </ul>
            </li>

             <li class="nav-item" id="Nav_Menu_">
                    <a href="#" class="nav-link"><span class="nav-label glyphicon glyphicon-object-align-vertical"></span> Cadre de résultat</a>
                </li>

                 <li class="nav-item" id="Nav_Menu_Suivi_Resultats">
                    <a href="#" class="nav-link"><span class="nav-label glyphicon glyphicon-object-align-bottom"></span> Suivi des résultats <b class="caret"></b></a>
                    <ul class="nav nav-second-level">
                        <li id="Nav_Sous_Menu_Fiches_Dynamiques"><a href="fiches_dynamiques.php" title="Localités">Fiches dynamiques</a></li>
                    </ul>
                </li>

                <li class="nav-item" id="Nav_Menu_Rapports">
                    <a href="#" class="nav-link"><span class="nav-label glyphicon glyphicon-tasks"></span> Etat & Rapports <b class="caret"></b></a>
                    <ul class="nav nav-second-level">
                        <li id="Nav_Sous_Menu_Rapports_Dynamiques"><a href="rapports_dynamiques.php" title="Localités">Rapports dynamiques</a></li>
                    </ul>
                </li>
                
                <li class="nav-item" id="Nav_Menu_">
                    <a href="" class="nav-link"><span class="nav-label glyphicon glyphicon-book"></span> Documentation</a>
                </li>
                
                
                
                <li class="nav-item" id="Nav_Menu_">
                    <a href="" class="nav-link"><span class="nav-label glyphicon glyphicon-map-marker"></span> Catographie</a>
                </li>
        </ul>
    </div>
</aside>
<script>var Url=window.location.pathname.substring(1); switch (Url)

{case 'menu.php' : document.getElementById('Nav_Menu_Accueil').classList.add("active"); 
                    document.getElementById('Nav_Menu_Accueil').classList.add("active2");
break;

case 'localite.php' : 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active"); 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active2");
                    document.getElementById('Nav_Sous_Menu_Localite').classList.add("active"); 
                    document.getElementById('Nav_Sous_Menu_Localite').classList.add("active2");
break;

case 'zone_collecte.php' : 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active"); 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active2");
                    document.getElementById('Nav_Sous_Menu_Zone_Collecte').classList.add("active"); 
                    document.getElementById('Nav_Sous_Menu_Zone_Collecte').classList.add("active2");
break;

case 'partenaire.php' : 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active"); 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active2");
                    document.getElementById('Nav_Sous_Menu_Partenaire').classList.add("active"); 
                    document.getElementById('Nav_Sous_Menu_Partenaire').classList.add("active2");
break;

case 'type_partenaire.php' : 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active"); 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active2");
                    document.getElementById('Nav_Sous_Menu_Partenaire').classList.add("active"); 
                    document.getElementById('Nav_Sous_Menu_Partenaire').classList.add("active2");
break;

case 'utilisateur.php' : 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active"); 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active2");
                    document.getElementById('Nav_Sous_Menu_Utilisateur').classList.add("active"); 
                    document.getElementById('Nav_Sous_Menu_Utilisateur').classList.add("active2");
break;

case 'fonction.php' : 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active"); 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active2");
                    document.getElementById('Nav_Sous_Menu_Fonction').classList.add("active"); 
                    document.getElementById('Nav_Sous_Menu_Fonction').classList.add("active2");
break;

case 'programmes.php' : 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active"); 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active2");
                    document.getElementById('Nav_Sous_Menu_Programme').classList.add("active"); 
                    document.getElementById('Nav_Sous_Menu_Programme').classList.add("active2");
break;

case 'projets.php' : 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active"); 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active2");
                    document.getElementById('Nav_Sous_Menu_Projet').classList.add("active"); 
                    document.getElementById('Nav_Sous_Menu_Projet').classList.add("active2");
break;

case 'projet_details.php' : 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active"); 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active2");
                    document.getElementById('Nav_Sous_Menu_Projet').classList.add("active"); 
                    document.getElementById('Nav_Sous_Menu_Projet').classList.add("active2");
break;

case 'categorie_indicateur.php' : 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active"); 
                    document.getElementById('Nav_Menu_Parametrage').classList.add("active2");
                    document.getElementById('Nav_Sous_Menu_Categorie_Indicateur').classList.add("active"); 
                    document.getElementById('Nav_Sous_Menu_Categorie_Indicateur').classList.add("active2");
break;

case 'fiches_dynamiques.php' : 
                    document.getElementById('Nav_Menu_Suivi_Resultats').classList.add("active"); 
                    document.getElementById('Nav_Menu_Suivi_Resultats').classList.add("active2");
                    document.getElementById('Nav_Sous_Menu_Fiches_Dynamiques').classList.add("active"); 
                    document.getElementById('Nav_Sous_Menu_Fiches_Dynamiques').classList.add("active2");
break;

case 'classeur_details.php' : 
                    document.getElementById('Nav_Menu_Suivi_Resultats').classList.add("active"); 
                    document.getElementById('Nav_Menu_Suivi_Resultats').classList.add("active2");
                    document.getElementById('Nav_Sous_Menu_Fiches_Dynamiques').classList.add("active"); 
                    document.getElementById('Nav_Sous_Menu_Fiches_Dynamiques').classList.add("active2");
break;

case 'disposition_mobile_formulaire.php' : 
                    document.getElementById('Nav_Menu_Suivi_Resultats').classList.add("active"); 
                    document.getElementById('Nav_Menu_Suivi_Resultats').classList.add("active2");
                    document.getElementById('Nav_Sous_Menu_Fiches_Dynamiques').classList.add("active"); 
                    document.getElementById('Nav_Sous_Menu_Fiches_Dynamiques').classList.add("active2");
break;

case 'rapports_dynamiques.php' : 
                    document.getElementById('Nav_Menu_Rapports').classList.add("active"); 
                    document.getElementById('Nav_Menu_Rapports').classList.add("active2");
                    document.getElementById('Nav_Sous_Menu_Rapports_Dynamiques').classList.add("active"); 
                    document.getElementById('Nav_Sous_Menu_Rapports_Dynamiques').classList.add("active2");
break;

case 'rapports_dynamiques_creation.php' : 
                    document.getElementById('Nav_Menu_Rapports').classList.add("active"); 
                    document.getElementById('Nav_Menu_Rapports').classList.add("active2");
                    document.getElementById('Nav_Sous_Menu_Rapports_Dynamiques').classList.add("active"); 
                    document.getElementById('Nav_Sous_Menu_Rapports_Dynamiques').classList.add("active2");
break;

case 'rapport_details.php' : 
                    document.getElementById('Nav_Menu_Rapports').classList.add("active"); 
                    document.getElementById('Nav_Menu_Rapports').classList.add("active2");
                    document.getElementById('Nav_Sous_Menu_Rapports_Dynamiques').classList.add("active"); 
                    document.getElementById('Nav_Sous_Menu_Rapports_Dynamiques').classList.add("active2");
break;









 default:break;}</script>