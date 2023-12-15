<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*  Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
if (!isset ($_SESSION["clp_id"])) {
    header(sprintf("Location: %s", "/"));  exit();
}
include_once 'api/configuration.php';
$config = new Config;

require_once 'api/Fonctions.php';
require_once 'theme_components/theme_style.php';
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <title><?php print $config->sitename;?></title>
    <link rel="shortcut icon" type="image/ico" href="<?php print $config->icon_folder;?>/favicon.ico" />
    <meta name="keywords" content="<?php print $config->MetaKeys;?>" />
    <meta name="description" content="<?php print $config->MetaDesc;?>" />
    <meta name="author" content="<?php print $config->MetaAuthor;?>" />

    <!-- Vendor styles -->
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.css" />
    <link rel="stylesheet" href="vendor/metisMenu/dist/metisMenu.css" />
    <link rel="stylesheet" href="vendor/animate.css/animate.css" />
    <link rel="stylesheet" href="vendor/bootstrap/dist/css/bootstrap.css" />

    <!-- App styles -->
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/helper.css" />
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/style_fst.css">

    <!-- Vendor scripts -->
    <script src="vendor/jquery/dist/jquery.min.js"></script>
    <script src="vendor/jquery-ui/jquery-ui.min.js"></script>
    <script src="vendor/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="vendor/jquery-flot/jquery.flot.js"></script>
    <script src="vendor/jquery-flot/jquery.flot.resize.js"></script>
    <script src="vendor/jquery-flot/jquery.flot.pie.js"></script>
    <script src="vendor/flot.curvedlines/curvedLines.js"></script>
    <script src="vendor/jquery.flot.spline/index.js"></script>
    <script src="vendor/metisMenu/dist/metisMenu.min.js"></script>
    <script src="vendor/iCheck/icheck.min.js"></script>
    <script src="vendor/peity/jquery.peity.min.js"></script>
    <script src="vendor/sparkline/index.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="vendor/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.fr.min.js"></script>

    <!-- DataTables -->
    <script src="vendor/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- DataTables buttons scripts -->
    <script src="vendor/pdfmake/build/pdfmake.min.js"></script>
    <script src="vendor/pdfmake/build/vfs_fonts.js"></script>
    <script src="vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>

    <!-- App scripts -->
    <script src="scripts/homer.js"></script>

</head>
<body class="fixed-navbar fixed fixed-footer sidebar-scroll">
    <?php require_once "./theme_components/header.php"; ?>
    <?php //require_once "./theme_components/main-menu.php"; ?>
<!-- Main Wrapper -->
<div id="">
<?php require_once "./theme_components/sub-header.php"; ?>
    <div class="content animate-panel">
        <div class="row">
<script>
$("#search").hide();
//$(document).ready(function(){$(".modal").off( "hidden.bs.modal", null );});
$("#mbreadcrumb").html(<?php $link = ""; if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) $link .= '<div class="btn-circle-zone">'/*.do_link("btn_rapport","./rapports_indicateur_creation.php","Création de rapport","<span title='Créer un nouveau rapport' class='glyphicon glyphicon-plus'></span>","simple","./","btn btn-success btn-circle mgr-5","",1,"",$nfile)*/;
$link .= '<a class="dropdown-toggle label-menu-corner btn btn-success btn-circle mgr-5" href="#" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span></a>';
if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1)
$link .= '<ul class="dropdown-menu hdropdown animated flipInX"><li>'.do_link("","./rapports_indicateur_creation.php","Création de rapport","<span class='glyphicon glyphicon-stats text-info'></span><span class='text-default'>&nbsp;Rapport d'indicateur</span>","simple","./","","",1,"",$nfile).'</li><!--<li id="btn_article">'.do_link("","#","Création d'article","<span class='glyphicon glyphicon-comment text-info'></span><span class='text-default'>&nbsp;Article</span>","simple","./","","",1,"",$nfile).'</li>--></ul>';
$link .= '</div>';
echo GetSQLValueString($link, "text"); } ?>);
</script>

<form id="Form_Perso">
    
</form>
<?php require_once 'requires/formulaire_insertion_123.php';  ?>
<script type="text/javascript">
    function Supprimer_Rapport(Code)
 { 
  if(confirm("Voulez-vous supprimer ce Rapport?")){$.ajax({url:"traitement_jquery/supprimer_rapport_indicateur.php?Rapport="+Code, method:"POST", data:$('#Form_Perso').serialize(), success:function (data) {
    if(data!='')
    {window.location.href='rapports_indicateur.php';}
    else {}}});}
  

 }

  function Afficher_Commentaire(Code)
 {   
    $.ajax({url:"traitement_jquery/affichage_rapport_commentaires.php?Code_Rapport="+Code, method:"POST", data:$('#form_form').serialize(), success:function (data) {if(data!='')
        {
        $("#commentaire_contenu").html(data);

         $("#add_commentaire").modal('show');
    }else {}}});

 }

   function Modifier_Article(Code)
 {   
    $.ajax({url:"traitement_jquery/formulaire_rapport_article_modification.php?Code_Article="+Code, method:"POST", data:$('#form_form').serialize(), success:function (data) {if(data!='')
        {
        $("#commentaire_contenu").html(data);

         $("#add_commentaire").modal('show');
         document.getElementById('form_article_modif').addEventListener('submit', function(e){
    e.preventDefault();
Modifier_Article_1();

    function Modifier_Article_1(){

/*$.ajax({url:"traitement_jquery/rapport_article_insertion.php", method:"POST", data:$('#form_article').serialize(), success:function (data) {
var Url=window.location.pathname.substring(1); window.location.href='';}});*/

var form = $("#form_article_modif").get(0);
    var formData = new FormData(form);
    var $file = $("input[type=file]");
    var file = $file[0].files[0];
    formData.append('photo_article', file);
$.ajax({url:"traitement_jquery/rapport_article_modification.php", method:"POST", processData:false, contentType:false, data:formData, success:function (data) {
var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1]; window.location.href='';}});

}
});
    }else {}}});

 }


   function Supprimer_Commentaire(Code)
 {  if(confirm('Voulez-vous supprimer ce commentaire?')){
    $.ajax({url:"traitement_jquery/supprimer_rapport_commentaires.php?Code_Commentaire="+Code, method:"POST", data:$('#form_form').serialize(), success:function (data) {window.location.href='';}});
} 
 }

   function Supprimer_Article(Code)
 {  if(confirm('Voulez-vous supprimer cet article?')){
    $.ajax({url:"traitement_jquery/supprimer_rapport_article.php?Code_Article="+Code, method:"POST", data:$('#form_form').serialize(), success:function (data) {window.location.href='';}});
} 
 }


    function Valider_Article(Code)
 {  if(confirm('Voulez-vous valider cet article?')){
    $.ajax({url:"traitement_jquery/valider_rapport_article.php?Code_Article="+Code, method:"POST", data:$('#form_form').serialize(), success:function (data) {window.location.href='';}});
} 
 }


</script>
 <div class="content">

<?php
$ii=0;
$ui=0;
foreach (FC_Rechercher_Code("SELECT * FROM t_rapport_indicateur WHERE Id_Projet='".$_SESSION['clp_projet']."' ORDER BY Code_Rapport DESC") as $row3)
{if($ii%3==0){echo '<div class="row projects">';}
$ui++;
$Nom_Feuille="";
$Nom_Classeur="";
foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_classeur ON (t_feuille.Code_Classeur=t_classeur.Code_Classeur) WHERE (t_feuille.Code_Feuille='.$row3["Code_Feuille"].')') as $row4)
{$Nom_Feuille=$row4["Nom_Feuille"];
 $Nom_Classeur=$row4["Libelle_Classeur"];}
echo ' <div class="col-lg-4">
                <div class="hpanel " style="border-top: 2px solid '.$Panel_Item_Style.'">

                    <div class="panel-body">
                    ';



             /*'<a href="rapport_details_croise.php?r='.base64_encode($row3['Code_Rapport']).'" style="font-size:16px">'.*/ 
foreach (FC_Rechercher_Code("SELECT `code_ref_ind` AS code, `intitule_ref_ind` AS intitule FROM referentiel_indicateur  WHERE (code_ref_ind='".$row3["Indicateur"]."')") as $row5)
{echo $row5['intitule'];}                
                


                 /*.'</a>'*/ ;

                        if(strstr($row3['Date_Insertion'], date('Y-m-d'))){echo '<span class="label '.$Label_Style.' pull-right">NEW</span>';}
                        echo '<div class="row" style="text-align: left">
                            <div class="col-sm-10">
                                <hr class="color-line">

                                <p>Classeur : '.$Nom_Classeur.'</p>

                                <div class="row">

                                    <div class="col-sm-10">
                                        <div class="project-label"><small>Feuilles : '.$Nom_Feuille.'</small>';

                                        echo '</div>

                                    </div>
                                    <div class="col-sm-2">
                                        <div class="project-label"><div class="" style="background-color:; border-radius: 50%; width: 20px; height: 20px">';

                echo '<span class="glyphicon glyphicon-equalizer text-info"></span>';
                                        echo '</div></div>

                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-2 project-info">
                                <div class="project-action m-t-md">
                                    <div class="btn-group" style="font-size: 18px">
                                        <button class="btn btn-xs btn-default" ';

                                        echo 'onclick="window.location.href=\'rapports_indicateur_modification.php?r='.base64_encode($row3['Code_Rapport']).'\'"';

                                        echo ' id="" title="Modifier"><span class="nav-label glyphicon glyphicon-pencil text-info" ></span></button>
                                    </div>

                                    <div class="btn-group" style="font-size: 18px">
                                        <button class="btn btn-xs btn-default" onclick="Supprimer_Rapport(\''.$row3['Code_Rapport'].'\')" id="" title="Supprimer"><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></button>
                                    </div>


                                </div>




                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">';



                    //echo '<a href="rapport_details_croise.php?r='.base64_encode($row3['Code_Rapport']).'" >Ouvrir le rapport</a>';
                    echo '</div>
                </div>
            </div>';

if($ui%3==0){echo ' </div>'; $ui=0;}
 $ii++;
}

      ?>

  </div>





   <div class="content">

<?php
$ii=0;
$ui=0;
foreach (FC_Rechercher_Code('SELECT * FROM t_rapport_article ORDER BY Date_Insertion DESC') as $row9)
{if($ii%3==0){echo '<div class="row projects">';}
$ui++;

echo ' <div class="col-lg-4">
                <div class="hpanel " style="border-top: 2px solid '.$Panel_Item_Style.'">

                    <div class="panel-body">';


                        echo htmlspecialchars_decode($row9['Titre_Article']);
foreach (FC_Rechercher_Code('SELECT * FROM v_indicateurs INNER JOIN t_rapport_indicateur ON (code=Indicateur) WHERE (Code_Rapport='.$row9["Code_Rapport"].')') as $row45)
{echo '<div class="col-sm-12">Indicateur : <span class="text-primary">'.$row45['intitule'].'</span></div>';} 

             /*'<a href="rapport_details_croise.php?r='.base64_encode($row9['Code_Rapport']).'" style="font-size:16px">'.*/                
                


                 /*.'</a>'*/ ;

                        if(strstr($row9['Date_Insertion'], date('Y-m-d'))){echo '<span class="label '.$Label_Style.' pull-right">NEW</span>';}
                        echo '<div class="row" style="text-align: left">
                            <div class="col-sm-10">
                                

                                <p><img src="images/'.$row9['Photo'].'" width="90%" height="90%" alt="..."></p>

                                <div class="row">

                                    <div class="col-sm-10">
                                        <div class="project-label"><small>'.htmlspecialchars_decode($row9['Description_Article']).'</small>';

                                        echo '</div>

                                    </div>
                                    <div class="col-sm-2">
                                        <div class="project-label"><div class="" style="background-color:; border-radius: 50%; width: 20px; height: 20px">';

                echo '<span class="glyphicon glyphicon-equalizer text-info"></span>';
                                        echo '</div></div>

                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-2 project-info">
                                <div class="project-action m-t-md">';

                               if($row9['Validation']=="Non"){ echo '<div class="btn-group" style="font-size: 18px">
                                        <button class="btn btn-xs btn-default" onclick="Valider_Article(\''.$row9['Code_Article'].'\')" id="" title="Valider"><span class="nav-label glyphicon glyphicon-ok-circle text-success" ></span></button>
                                    </div> 
                                    <div class="btn-group" style="font-size: 18px">
                                        <button class="btn btn-xs btn-default" onclick="Modifier_Article(\''.$row9['Code_Article'].'\')" id="" title="Modifier"><span class="nav-label glyphicon glyphicon-pencil text-info" ></span></button>
                                    </div>';}
                            else{echo '<div class="btn-group" style="font-size: 18px">
                                        <button class="btn btn-xs btn-default" onclick="Afficher_Commentaire(\''.$row9['Code_Article'].'\')" id="" title="Commentaires"><span class="nav-label glyphicon glyphicon-comment text-default" ></span></button>
                                    </div>';}

                                    
                                    


                                        echo ' 

                                    <div class="btn-group" style="font-size: 18px">
                                        <button class="btn btn-xs btn-default" onclick="Supprimer_Article(\''.$row9['Code_Article'].'\')" id="" title="Supprimer"><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></button>
                                    </div>




                                </div>




                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">';



                    //echo '<a href="rapport_details_croise.php?r='.base64_encode($row9['Code_Rapport']).'" >Ouvrir le rapport</a>';
                    echo '</div>
                </div>
            </div>';

if($ui%3==0){echo ' </div>'; $ui=0;}
 $ii++;
}

      ?>

  </div>

        </div>
    </div>

<center>
    <div id="add_commentaire" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div id="reponse"></div>
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" onclick="window.location.href=''" class="close" data-dismiss="modal" aria-hidden="true"><img src="./images/close.png" alt="Fermer"></button>
               <center><h4 class="modal-title">Rapport </h4></center>
          </div>
          <div class="modal-body" id="commentaire_contenu">



</div>
<div class="modal-footer"> <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.href=''">Fermer</button></div>
</div>
</div>
</div>
</center>
<form id="form_form"></form>


<div id="add_classeur_article" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div id="reponse"></div>
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button type="button" onclick="window.location.href=''" class="close" data-dismiss="modal" aria-hidden="true"><img src="./images/close.png" alt="Fermer"></button>
               <center><h4 class="modal-title">Ajouter un article </h4></center>
          </div>
          <div class="modal-body">

   <form method="POST" action="" id="form_article">
    <div class="row">    

        <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Rapport d'indicateur</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
         <select class="form-control" required name="code_rapport" id="code_rapport">
          <option value=""></option>
      <?php foreach (FC_Rechercher_Code("SELECT Code_Rapport, code, intitule  FROM v_indicateurs INNER JOIN t_rapport_indicateur ON (v_indicateurs.code=t_rapport_indicateur.Indicateur) WHERE Affichage='Tous'") as $row3) 
      {echo '<option value="'.$row3["Code_Rapport"].'">'.$row3["intitule"].'</option>';
        
      } ?>  
        </select>
       </div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label >Titre</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <input type="text" class="form-control" required placeholder="Titre" name="titre_article" id="titre_article" maxlength="255">
      </div>
    </div><br>
    <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label>Description</label></div>
      <div class="col-sm-8 col-md-8 mb-3"><textarea class="form-control" maxlength="2500" required placeholder="Description" name="description_article" id="description_article"></textarea></div>
    </div><br>

        <div class="row">
      <div class="col-sm-4 col-md-4 mb-3"><label >Photo (2Mo Maximum)</label></div>
      <div class="col-sm-8 col-md-8 mb-3">
        <input type="file" accept="image/*" class="form-control" name="photo_article" id="photo_article">
      </div>
    </div><br>

    <div class="row">
     <div class="col-sm-4 col-md-4 mb-3"></div>
      <div class="col-sm-8 col-md-8 mb-3">
      <br><button style="width: 150px" class="btn btn-info" id="submit" type="submit">Inserer</button></div>
    </div><br>
  </form>


</div>
<div class="modal-footer"> <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.href=''">Fermer</button></div>
</div>
</div>
</div>
<div class="modal fade" id="error_data_modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

  </div>
</div>

<script type="text/javascript">
   $(document).ready(function(){
  $(document).on('click','#btn_article', function(){
  
        $("#add_classeur_article").modal('show');
      });

    });
</script>

<script type="text/javascript">
document.getElementById('form_article').addEventListener('submit', function(e){
    e.preventDefault();
Envoyer_Article();

    function Envoyer_Article(){

/*$.ajax({url:"traitement_jquery/rapport_article_insertion.php", method:"POST", data:$('#form_article').serialize(), success:function (data) {
var Url=window.location.pathname.substring(1); window.location.href='';}});*/

var form = $("#form_article").get(0);
    var formData = new FormData(form);
    var $file = $("input[type=file]");
    var file = $file[0].files[0];
    formData.append('photo_article', file);
$.ajax({url:"traitement_jquery/rapport_article_insertion.php", method:"POST", processData:false, contentType:false, data:formData, success:function (data) {
var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1]; window.location.href='';}});

}
});








</script>

    <?php require_once "./theme_components/footer.php"; ?>
</div>

</body>
</html>