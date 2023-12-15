<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*  Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
//var_dump($_SESSION);
if (!isset ($_SESSION["clp_id"])) {
    header(sprintf("Location: %s", "./login.php"));  exit();
}
include_once 'api/configuration.php';
$config = new Config;

require_once 'api/Fonctions.php';
require_once 'theme_components/theme_style.php';

if(isset($_GET['c']) AND !empty($_GET['c']))
{   $Filtre_Admin='';
    $Code_Feuille_Carte="9";
if(strtolower(trim($_SESSION["clp_id"])) == "admin"){$Filtre_Admin='';}
    else {$Filtre_Admin=" AND Login = '".$_SESSION["clp_id"]."' ";}
    
    $Code_Classeur=base64_decode($_GET['c']);
    $Nom_Classeur="";
    $Note_Classeur="";
    $ii=0;
    foreach (FC_Rechercher_Code('SELECT * FROM t_classeur WHERE Code_Classeur=\''.$Code_Classeur.'\'') as $row4)
    {$ii++; $uuu=0;
      $Nom_Classeur=$row4['Libelle_Classeur']; $Note_Classeur=$row4["Note_Classeur"];
    if(isset($_GET['f']) AND !empty($_GET['f']))
        {
          foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE (Code_Feuille=\''.$_GET['f'].'\' AND Statut = 1)') as $rowx) {$uuu++;}
    if($uuu==0){header('location:classeur_details.php?c='.$_GET['c']);}}

    if($ii==0){header('location:fiches_dynamiques.php');}

    }
}
else
{header('location:fiches_dynamiques.php');}
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
    <link rel="stylesheet" href="vendor/datatables.net-bs/css/dataTables.bootstrap.min.css" />
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

<style type="text/css">
    .active .active_navbar_a{border-top: 3px solid <?php echo $Panel_Item_Style; ?>!important}
     @media (min-width: 820px) {
  .modal-feuille {
    width: 80%!important;
    margin: 30px auto!important;
  }}


details {display:block;margin:5px 0;}
summary {display:block;background:white;color:#D76230;border-radius:5px;padding:5px;cursor:pointer;font-weight:bold;font-size: 16px;}
summary::-webkit-details-marker {display: none}
summary::-moz-details-marker {display: none}
summary::-ms-details-marker {display: none}
summary::-o-details-marker {display: none}
summary::details-marker {display: none}
summary:after {content: "+";transition: 2s!important;color: #D76230;float: left;font-size:20px;font-weight: bold;margin: -2px 5px 0 0;padding: 0;text-align: center; }
details[open] summary:after {content: "─";transition: 2s!important}
fieldset > details{display: block;}
</style>
</head>
<body class="">
    <?php require_once "./theme_components/header.php"; ?>
    <?php //require_once "./theme_components/main-menu.php"; ?>
<!-- Main Wrapper -->
<div id="">
<?php require_once "./theme_components/sub-header.php"; ?>
    <div class="content animate-panel" style="margin:0px!important; padding: 0px!important">
      <center style="font-size: 18px"><span>Classeur : <strong><?php echo $Nom_Classeur ?> / <strong style="font-size: 12px"><?php echo $Note_Classeur; ?></strong></strong></span></center>
      
        <div class="row" style="margin:0px!important; padding: 0px!important">
<script>
$("#search").hide();
$(document).ready(function(){$(".modal").off( "hidden.bs.modal", null );});
$("#mbreadcrumb").html(<?php $link = '<div class="btn-circle-zone"><a href="#" class="btn btn-success btn-circle mgr-5" title="Retour aux classeurs" onclick="history.back(1);"><span title="Retour à la liste des classeurs" class="glyphicon glyphicon-arrow-left"></span></a>'; if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) $link .= do_link("btn_feuille","#add_feuille_modal","Ajout de feuille","<span title='Nouvelle fiche' class='glyphicon glyphicon-plus'></span>","simple","./","btn btn-success btn-circle mgr-5","",1,"",$nfile);
$link .= '</div>';
echo GetSQLValueString($link, "text"); ?>);
$("#btn_feuille").attr("data-toggle","modal");$("#btn_feuille").attr("data-target","#add_feuille_modal");
</script>
<?php echo '<script type="text/javascript" charset="utf-8" > var Code_Classeur="'.base64_encode($Code_Classeur).'";</script>'; ?>

<?php require_once'requires/formulaire_modification_feuille.php'; ?>
<form id="form_perso_feuille_donnees">
</form>
<div id="add_form_perso_modal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
      <div class="modal-dialog "  role="document"  >
        <div class="modal-content" id="add_form_perso_modal_body2">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button id="fermer" type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="./images/close.png" alt="Fermer"></button>
               <center><h4 class="modal-title" id="add_form_perso_modal_titre2">Metadonnées</h4></center>
          </div>
       <div id="Form_Contenu_Perso2">
       
       </div>
       <div class="modal-footer"> <button id="fermer" type="button" class="btn btn-default" data-dismiss="modal">Fermer</button></div>
</div>
</div>
</div>

<div id="add_form_perso_modalCarte" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
      <div class="modal-dialog "  role="document"  >
        <div class="modal-content" id="add_form_perso_modal_bodyCarte">
          <div class="modal-header" style="height:auto; margin: 0; padding: 0">
               <button id="fermer" type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="./images/close.png" alt="Fermer"></button>
               <center><h4 class="modal-title" id="add_form_perso_modal_titreCarte">Carte</h4></center>
          </div>
       <div id="Form_Contenu_PersoCarte">
      <iframe src="" id="iframeCarte" style="border: 1px; height: 300px" width="100%" allow="fullscreen" high></iframe> 
       </div>
       <div class="modal-footer">
        <a href="#" style="" id="aCarte" class="btn btn-default">Télécharger la carte</a> 
        <button id="fermer" type="button" class="btn btn-default" data-dismiss="modal">Fermer</button></div>
</div>
</div>
</div>
<script type="text/javascript">
    var feuille_active=1;
</script>

<ul class="nav nav-tabs" style="border-bottom:1px solid #F1F3F6; margin:0px!important; padding: 0px!important">
    <?php
$ind=0;
    foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE (Code_Classeur=\''.$Code_Classeur.'\' AND Statut=1 )') as $row5)
    {$ind++;$Test=0;
    if(isset($_GET['f']) AND !empty($_GET['f']))
    {if($row5['Code_Feuille']==$_GET['f']){
      $Test++;
      echo '<li class="active"><a data-toggle="tab" onclick="feuille_active='.$row5['Code_Feuille'].';" href="#feuille'.$row5['Code_Feuille'].'" class="active_navbar_a">'.$row5['Libelle_Feuille'].'</a></li>';}
     else{echo '<li><a data-toggle="tab" onclick="feuille_active='.$row5['Code_Feuille'].';" href="#feuille'.$row5['Code_Feuille'].'" class="active_navbar_a">'.$row5['Libelle_Feuille'].'</a></li>';}}
else{

     if($ind==1){echo '<li class="active"><a data-toggle="tab" onclick="feuille_active='.$row5['Code_Feuille'].';" href="#feuille'.$row5['Code_Feuille'].'" class="active_navbar_a">'.$row5['Libelle_Feuille'].'</a></li>';}
     else{echo '<li><a data-toggle="tab" onclick="feuille_active='.$row5['Code_Feuille'].';" href="#feuille'.$row5['Code_Feuille'].'" class="active_navbar_a">'.$row5['Libelle_Feuille'].'</a></li>';}}


    }
    echo '<script> var feuille_total='.$ind.' ;</script>';
    ?>

</ul>
<?php require_once 'requires/formulaire_insertion_123.php';  ?>
<div class="tab-content" style="background: #FFF;padding: 0 10px;">
    <?php
$ind=0;
    foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE (Code_Classeur=\''.$Code_Classeur.'\'  AND Statut = 1)') as $row5)
    {$ind++;
     if(isset($_GET['f']) AND !empty($_GET['f'])){
        if($row5['Code_Feuille']==$_GET['f']){echo '<div id="feuille'.$row5['Code_Feuille'].'" class="tab-pane fade in active">';}
     else{echo '<div id="feuille'.$row5['Code_Feuille'].'" class="tab-pane fade in">';}
     }
     else
     {if($ind==1){echo '<div id="feuille'.$row5['Code_Feuille'].'" class="tab-pane fade in active">';}
     else{echo '<div id="feuille'.$row5['Code_Feuille'].'" class="tab-pane fade in">';}}
?>

<script type="text/javascript"> function Afficher_Formulaire_Modification(Cod){
//modif_feuille_modal
   $.ajax({url:"traitement_jquery/formulaire_modification_feuille.php?feuille="+Cod+"&classeur=<?php echo $_GET['c']; ?>", method:"POST", data:$('#form_feuille_insertion').serialize(), success:function (data) {if(data!='')
        {$("#conteneur_form_modification_feuille").html(data);
         $("#modif_feuille_modal").modal('show');

  document.getElementById('form_personnalise').addEventListener('submit',function(e){
    e.preventDefault();
    Envoyer_Form_Auto();
  });
        }else {}}});
        }
function Envoyer_Form_Auto(){
$.ajax({url:"traitement_jquery/traitement_auto_formulaire.php", method:"POST", data:$('#form_personnalise').serialize(), success:function (data) {if(data=='')
        {
var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];/*var Url=window.location.pathname.substring(1);*/ window.location.href=(Url+"?c="+Code_Classeur+"&f="+feuille_active);
        }else {}}});}
</script>
<script type="text/javascript">
    function Telecharger_Fichier_Excel(Code, Nom)
    {$.ajax({url:"libs/phpspreadsheet/?t="+Code, method:"POST", data:$('#form_perso_feuille_donnees').serialize(), success:function (data) {
        if(data!='')
        {
        document.getElementById("form_perso_feuille_donnees").action="telechargements/"+Nom+".xlsx";
        document.getElementById("form_perso_feuille_donnees").submit();}
                                                }
    });}
</script>
<script type="text/javascript">

    function Generer_Code(Code, Id)
    {
      //window.location.href=("libs/dompdf/?Code="+Id);
     $.ajax({url:"libs/dompdf/generation_carte.php?Code="+Id, method:"POST", data:$('#form_feuille_insertion').serialize(), success:function (data) {if(data!='')
        {
        
          document.getElementById("iframeCarte").src="libs/dompdf/generation_carte.php?Code="+Id;
          document.getElementById("aCarte").href="libs/dompdf/?Code="+Id;
         $("#add_form_perso_modalCarte").modal('show');
    }else {}}});
    }
</script>
<script type="text/javascript">
 function Modifier_Feuille(Code)
 {   $.ajax({url:"traitement_jquery/formulaire_modification_feuille.php?feuille="+Code+"&classeur=<?php echo $_GET['c']; ?>", method:"POST", data:$('#form_feuille_insertion').serialize(), success:function (data) {if(data!='')
        {$("#conteneur_form_modification_feuille").html(data);
         document.getElementById("boutton_nouvelle_feuille_perso").style.display="none";
         document.getElementById("div_conseil").style.display="none";
         $("#modif_feuille_modal").modal('show');

  document.getElementById('form_feuille_modification').addEventListener('submit',function(e){
    e.preventDefault();
    var form = $("#form_feuille_modification").get(0);
    var formData = new FormData(form);
    var $file = $("input[type=file]");
    var file = $file[0].files[0];
    formData.append('Icone', file);
    $.ajax({url:"traitement_jquery/modification_feuille.php", method:"POST", processData:false, contentType:false, data:formData, success:function (data) {
        if(data=='')
        {var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];/*var Url=window.location.pathname.substring(1);*/ window.location.href=(Url+"?c="+Code_Classeur+"&f="+feuille_active);}
        else{}
                                                }
    });
    });
        }else {}}});

 }


  function Ajouter_Colonne(Code)
 {$.ajax({url:"traitement_jquery/formulaire_ajouter_colonne.php?feuille="+Code+"&classeur=<?php echo $_GET['c']; ?>", method:"POST", data:$('#form_feuille_insertion').serialize(), success:function (data) {if(data!='')
        {$("#conteneur_form_modification_feuille").html(data);
         //document.getElementById("boutton_nouvelle_feuille_perso").style.display="none";
         //document.getElementById("div_conseil").style.display="none";
         $("#modif_feuille_modal").modal('show');

         document.getElementById("boutton_nouvelle_feuille_perso").addEventListener('click', function(e){
            $("#formulaire_ajouter_colone_conteneur").append('<div class="div_ligne_feuille" id="div_ligne_feuille_'+(document.getElementsByClassName("div_ligne_feuille").length+1)+'"><div class="row" ><br><div class="col-sm-2 col-md-2 mb-2"><input type="text" class="form-control" placeholder="Nom Ligne" required  name="nom_Ligne[]" id="nom_Ligne[]"></div><div class="col-sm-2 col-md-1.5 mb-2"><input type="text" required class="form-control" placeholder="Libelle" name="libelle_Ligne[]" id="libelle_Ligne[]"></div><div class="col-sm-2 col-md-2 mb-2"><select class="form-control" required placeholder="" onchange="Charger_Div(this.value, '+(document.getElementsByClassName("div_ligne_feuille").length+1)+')" name="type_Ligne[]" id="type_Ligne[]"><option value="TEXT">TEXT</option><option value="INT">INT</option><option value="DOUBLE">DOUBLE</option><option value="DATE">DATE</option><option value="CHOIX">CHOIX</option><option value="COULEUR">COULEUR</option><option value="FICHIER">FICHIER</option><option value="FEUILLE">FEUILLE</option><option value="RAPPORT">RAPPORT</option><option value="SOMME">SOMME</option><option value="DIFFERENCE">DIFFERENCE</option><option value="PRODUIT">PRODUIT</option><option value="MOYENNE">MOYENNE</option><option value="COMPTER">COMPTER</option></select></div><div class="col-sm-2 col-md-2 mb-2"><select class="form-control" placeholder="" required name="requis[]" id="requis[]"><option value="Oui">Oui</option><option value="Non">Non</option></select><input type="hidden" name="valeur[]" id="Valeur_'+(document.getElementsByClassName("div_ligne_feuille").length+1)+'"></div><div class="col-sm-2 col-md-2 mb-2"><select class="form-control" placeholder="" required name="afficher[]" id="afficher[]"><option value="Oui">Oui</option><option value="Non">Non</option></select></div><div class="col-sm-1 col-md-1 mb-1"></div><div class="col-sm-1 col-md-1 mb-1"><div class="btn btn-xs btn-default" onclick="Supprimer_Item(\''+(document.getElementsByClassName("div_ligne_feuille").length+1)+'\')" title="Supprimer" ><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></div></div><br></div><div class="row" id="Under_Div_'+(document.getElementsByClassName("div_ligne_feuille").length+1)+'"></div></div>');
         });
  document.getElementById('form_feuille_modification').addEventListener('submit',function(e){
    e.preventDefault();
    document.getElementById("submit").disabled=true;
    var form = $("#form_feuille_modification").get(0);
    var formData = new FormData(form);
    var $file = $("input[type=file]");
    var file = $file[0].files[0];
    formData.append('Icone', file);
    $.ajax({url:"traitement_jquery/ajouter_colonne.php", method:"POST", processData:false, contentType:false, data:formData, success:function (data) {
        if(data=='')
        {var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];/*var Url=window.location.pathname.substring(1);*/ window.location.href=(Url+"?c="+Code_Classeur+"&f="+feuille_active);}
        else{
            if(confirm("Erreur! \n Veuillez supprimer la colonne que vous venez de créer!"))
            {var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];/*var Url=window.location.pathname.substring(1);*/ window.location.href=(Url+"?c="+Code_Classeur+"&f="+feuille_active);}
        }
                                                }
    });
    });
        }else {}}});


 }


 function Supprimer_Colonne(Code)
 {   $.ajax({url:"traitement_jquery/formulaire_supprimer_colonne.php?feuille="+Code+"&classeur=<?php echo $_GET['c']; ?>", method:"POST", data:$('#form_feuille_insertion').serialize(), success:function (data) {if(data!='')
        {$("#conteneur_form_modification_feuille").html(data);
         document.getElementById("boutton_nouvelle_feuille_perso").style.display="none";
         document.getElementById("div_conseil").style.display="none";
         $("#modif_feuille_modal").modal('show');

  document.getElementById('form_feuille_modification').addEventListener('submit',function(e){
    e.preventDefault();
    var CheckBox_Delete_Colonne= document.getElementsByName("CheckBox_Delete_Colonne[]");
    if(CheckBox_Delete_Colonne.length<1)
    {alert("Veuillez cocher une case!");}
else
{var Nb=0;
for(i=0; i<CheckBox_Delete_Colonne.length; i++)
    {if(CheckBox_Delete_Colonne[i].checked==true){Nb++;}}
if(Nb<1)
    {alert("Veuillez cocher une case!");}
else {
    var form = $("#form_feuille_modification").get(0);
    var formData = new FormData(form);
    var $file = $("input[type=file]");
    var file = $file[0].files[0];
    formData.append('Icone', file);
    $.ajax({url:"traitement_jquery/supprimer_colonne.php", method:"POST", processData:false, contentType:false, data:formData, success:function (data) {
        if(data=='')
        {var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];/*var Url=window.location.pathname.substring(1);*/ window.location.href=(Url+"?c="+Code_Classeur+"&f="+feuille_active);}
        else{

        }
                                                }
    });

}
}
});
        }else {}}});

 }




 function Modifier_Colonne(Code)
 {   $.ajax({url:"traitement_jquery/formulaire_modifier_colonne.php?feuille="+Code+"&classeur=<?php echo $_GET['c']; ?>", method:"POST", data:$('#form_feuille_insertion').serialize(), success:function (data) {if(data!='')
        {$("#conteneur_form_modification_feuille").html(data);
         document.getElementById("boutton_nouvelle_feuille_perso").style.display="none";
         //document.getElementById("div_conseil").style.display="none";
         $("#modif_feuille_modal").modal('show');

  document.getElementById('form_feuille_modification').addEventListener('submit',function(e){
   e.preventDefault();
   document.getElementById("submit").disabled=true;
    var form = $("#form_feuille_modification").get(0);
    var formData = new FormData(form);
    var $file = $("input[type=file]");
    var file = $file[0].files[0];
    formData.append('Icone', file);
    $.ajax({url:"traitement_jquery/modifier_colonne.php", method:"POST", processData:false, contentType:false, data:formData, success:function (data) {
        if(data=='')
        {var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];/*var Url=window.location.pathname.substring(1);*/ window.location.href=(Url+"?c="+Code_Classeur+"&f="+feuille_active);}
        else{
            if(confirm("Erreur!"))
            {var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];/*var Url=window.location.pathname.substring(1);*/ window.location.href=(Url+"?c="+Code_Classeur+"&f="+feuille_active);}
        }
                                                }
    });


   });
        }else {}}});

 }




 function Deplacer_Colonne(Code)
 {   $.ajax({url:"traitement_jquery/formulaire_deplacer_colonne.php?feuille="+Code+"&classeur=<?php echo $_GET['c']; ?>", method:"POST", data:$('#form_feuille_insertion').serialize(), success:function (data) {if(data!='')
        {$("#conteneur_form_modification_feuille").html(data);
         document.getElementById("boutton_nouvelle_feuille_perso").style.display="none";
         document.getElementById("div_conseil").style.display="none";
         $("#modif_feuille_modal").modal('show');
         $("#Div_Deplacer_Colonne").sortable();
         $("#Div_Deplacer_Colonne").disableSelection();

  document.getElementById('form_feuille_modification').addEventListener('submit',function(e){
  e.preventDefault();
    var form = $("#form_feuille_modification").get(0);
    var formData = new FormData(form);
    var $file = $("input[type=file]");
    var file = $file[0].files[0];
    formData.append('Icone', file);
    $.ajax({url:"traitement_jquery/deplacer_colonne.php", method:"POST", processData:false, contentType:false, data:formData, success:function (data) {
        if(data=='')
        {var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];/*var Url=window.location.pathname.substring(1);*/ window.location.href=(Url+"?c="+Code_Classeur+"&f="+feuille_active);}
                                                }
    });

});
        }else {}}});

 }


 function Supprimer_Feuille(Code)
 {   if(confirm("Voulez-vous supprimer cette feuille?")){
    var form = $("#form_perso_feuille_donnees").get(0);
    var formData = new FormData(form);
    var $file = $("input[type=file]");
    var file = $file[0].files[0];
    formData.append('Icone', file);
    $.ajax({url:"traitement_jquery/supprimer_feuille.php?Code_Feuille="+Code, method:"POST", processData:false, contentType:false, data:formData, success:function (data) {
        if(data=='')
        {var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];/*var Url=window.location.pathname.substring(1);*/ window.location.href=(Url+"?c="+Code_Classeur+"&f="+feuille_active);}
      else{alert(data);}
                                                }
    });
}
        }


 function Importer_Donnes(Code)
 {   $.ajax({url:"traitement_jquery/formulaire_importer_donnees.php?feuille="+Code, method:"POST", data:$('#form_feuille_insertion').serialize(), success:function (data) {if(data!='')
        {$("#Form_Contenu_Perso2").html(data);
         document.getElementById("boutton_nouvelle_feuille_perso").style.display="none";
         document.getElementById("div_conseil").style.display="none";
         $("#add_form_perso_modal2").modal('show');

  document.getElementById('importation_form').addEventListener('submit',function(e){
  e.preventDefault();
    var form = $("#importation_form").get(0);
    var formData = new FormData(form);
    var $file = $("input[type=file]");
    var file = $file[0].files[0];
    formData.append('Fichier', file);
    $.ajax({url:"libs/phpspread/", method:"POST", processData:false, contentType:false, data:formData, success:function (data) {
        if(data.trim()=='')
        {var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];/*var Url=window.location.pathname.substring(1);*/ window.location.href=(Url+"?c="+Code_Classeur+"&f="+feuille_active);}
                                                }
    });
});
        }else {}}});

 }


  function Afficher_Position(Code, Id)
 {   $.ajax({url:"traitement_jquery/formulaire_afficher_position.php?Id="+Id+"&feuille="+Code, method:"POST", data:$('#form_feuille_insertion').serialize(), success:function (data) {if(data!='')
        {$("#Form_Contenu_Perso2").html(data);
         document.getElementById("boutton_nouvelle_feuille_perso").style.display="none";
         document.getElementById("div_conseil").style.display="none";
         $("#add_form_perso_modal2").modal('show');
    }else {}}});

 }


 function Affichage_Mobile(Code)
 {   $.ajax({url:"traitement_jquery/formulaire_affichage_mobile.php?feuille="+Code+"&classeur=<?php echo $_GET['c']; ?>", method:"POST", data:$('#form_feuille_insertion').serialize(), success:function (data) {if(data!='')
        {$("#conteneur_form_modification_feuille").html(data);
         document.getElementById("boutton_nouvelle_feuille_perso").style.display="none";
         document.getElementById("div_conseil").style.display="none";
         $("#modif_feuille_modal").modal('show');

  document.getElementById('form_feuille_modification').addEventListener('submit',function(e){
    e.preventDefault();

    var form = $("#form_feuille_modification").get(0);
    var formData = new FormData(form);
    var $file = $("input[type=file]");
    var file = $file[0].files[0];
    formData.append('Icone', file);
    $.ajax({url:"traitement_jquery/affichage_mobile.php", method:"POST", processData:false, contentType:false, data:formData, success:function (data) {
        if(data=='')
        {var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];/*var Url=window.location.pathname.substring(1);*/ window.location.href=(Url+"?c="+Code_Classeur+"&f="+feuille_active);}
        else{

        }
                                                }
    });

});
        }else {}}});

 }


  function Partenaires(Code)
 {   $.ajax({url:"traitement_jquery/formulaire_partenaire_mobile.php?feuille="+Code+"&classeur=<?php echo $_GET['c']; ?>", method:"POST", data:$('#form_feuille_insertion').serialize(), success:function (data) {if(data!='')
        {$("#conteneur_form_modification_feuille").html(data);
         document.getElementById("boutton_nouvelle_feuille_perso").style.display="none";
         document.getElementById("div_conseil").style.display="none";
         $("#modif_feuille_modal").modal('show');

  document.getElementById('form_feuille_modification').addEventListener('submit',function(e){
    e.preventDefault();

    var form = $("#form_feuille_modification").get(0);
    var formData = new FormData(form);
    var $file = $("input[type=file]");
    var file = $file[0].files[0];
    formData.append('Icone', file);
    $.ajax({url:"traitement_jquery/partenaire_mobile.php?feuille="+Code, method:"POST", processData:false, contentType:false, data:formData, success:function (data) {
        if(data=='')
        {var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];/*var Url=window.location.pathname.substring(1);*/ window.location.href=(Url+"?c="+Code_Classeur+"&f="+feuille_active);}
        else{

        }
                                                }
    });

});
        }else {}}});

 }




  function Disposition_Mobile(Code)
 {   window.location.href="disposition_mobile_formulaire.php?f="+Code+"&c=<?php echo $_GET['c']; ?>";}




  function Source_Donnees(Code)
 {   if(confirm("Voulez-vous Activer/Desactiver Source de données pour cette feuille?")){
    var form = $("#form_perso_feuille_donnees").get(0);
    var formData = new FormData(form);
    var $file = $("input[type=file]");
    var file = $file[0].files[0];
    formData.append('Icone', file);
    $.ajax({url:"traitement_jquery/source_donnees.php?Code_Feuille="+Code, method:"POST", processData:false, contentType:false, data:formData, success:function (data) {
        if(data=='')
        {var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];/*var Url=window.location.pathname.substring(1);*/ window.location.href=(Url+"?c="+Code_Classeur+"&f="+feuille_active);}
      else{alert(data);}
                                                }
    });
}
        }
</script>
<div class="row" style="font-size: 14px" align="left">
    <!--<br>-->
   <div class="col-lg-2 col-md-2 col-sm-2" style="cursor: pointer; margin: 0px!important; padding: 0px!important"><span class="dropdown label ">
   <a class="dropdown-toggle label-menu-corner <?php echo $Text_Style; ?>" href="#" data-toggle="dropdown"><span class="glyphicon glyphicon-asterisk <?php echo $Text_Style; ?>">
   </span>Edition de la feuille</a>
                    <ul class="dropdown-menu hdropdown animated flipInX">
                        <div class="title" style="color:black;">

                        </div>
                         <li>
                            <a onClick="<?php echo 'Modifier_Feuille(\''.$row5['Code_Feuille'].'\')'; ?>"><span class="glyphicon glyphicon-pencil text-info"></span>
                                <span class="text-info">Modifier la feuille</span>
                            </a>
                        </li>
                         <li>
                            <a onClick="<?php echo 'Supprimer_Feuille(\''.$row5['Code_Feuille'].'\')'; ?>">
                                <span class="glyphicon glyphicon-trash text-danger"></span>
                                <span class="text-danger">Supprimer la feuille</span>
                            </a>
                        </li>

                        <li>
                            <a onClick="<?php echo 'Ajouter_Colonne(\''.$row5['Code_Feuille'].'\')'; ?>">
                                <span class="glyphicon glyphicon-plus-sign"></span>
                                Ajouter une colonne
                            </a>
                        </li>
                        <li>
                            <a onClick="<?php echo 'Supprimer_Colonne(\''.$row5['Code_Feuille'].'\')'; ?>">
                                <span class="glyphicon glyphicon-trash text-danger"></span>
                                <span class="text-danger">Supprimer une colonne</span>
                            </a>
                        </li>
                        <li>
                            <a onClick="<?php echo 'Modifier_Colonne(\''.$row5['Code_Feuille'].'\')'; ?>">
                                <span class="glyphicon glyphicon-pencil text-info"></span>
                                <span class="text-info">Modifier une colonne</span>
                            </a>
                        </li>
                        <li>
                            <a onClick="<?php echo 'Deplacer_Colonne(\''.$row5['Code_Feuille'].'\')'; ?>">
                                <span class="glyphicon glyphicon-transfer"></span>
                                Deplacer une colonne
                            </a>
                        </li>
                        <li>
                            <a onClick="<?php echo 'Source_Donnees(\''.$row5['Code_Feuille'].'\')'; ?>">
                                <span class="glyphicon glyphicon-share text-warning"></span>
                                <span class="text-warning">
                                <?php if($row5['Source_Donnees']=="Oui"){echo "Desactiver : Source de données";}
                                else{echo "Activer : Source de données";} ?>
                                </span>

                            </a>
                        </li>
                    </ul></span>
   </div>

    <div class="col-lg-2 col-md-2 col-sm-2" style="cursor: pointer">
      <span class="dropdown label " >
        <a class="dropdown-toggle label-menu-corner <?php echo $Text_Style; ?>" href="#" data-toggle="dropdown"><span class="glyphicon glyphicon-asterisk <?php echo $Text_Style; ?>"></span>Affichage sur mobile</a>
<ul class="dropdown-menu hdropdown animated flipInX">
                        <div class="title" style="color:black;">

                        </div>
                        <li>
                            <a <?php echo 'onclick="Partenaires(\''.$row5['Code_Feuille'].'\')"'; ?>>
                              <span class="glyphicon glyphicon-user"></span> | <span class="glyphicon glyphicon-user text-info"></span>
                                <span class="text-info">Partenaires</span>
                            </a>
                        </li>
                         <li>
                            <a <?php echo 'onclick="Affichage_Mobile(\''.$row5['Code_Feuille'].'\')"'; ?>>
                              <span class="glyphicon glyphicon-eye-open text-success"></span> | <span class="glyphicon glyphicon-eye-close text-danger"></span>
                                <span class="text-default">Affichage des colonnes</span>
                            </a>
                        </li>
                         <li>
                            <a <?php echo 'onclick="Disposition_Mobile(\''.$row5['Code_Feuille'].'\')"'; ?>>
                                <span class="glyphicon glyphicon-triangle-bottom text-info"></span> | <span class="glyphicon glyphicon-triangle-top text-info"></span>
                                <span class="text-default">Disposition des colonnes</span>
                            </a>
                        </li>
                    </ul>
    </span></div>

    <div class="col-lg-2 col-md-2 col-sm-2"></div>

    <div class="col-lg-2 col-md-2 col-sm-2" style="cursor: pointer"><span class=" label <?php echo $Text_Style; ?>" <?php echo 'onclick="Importer_Donnes(\''.$row5['Code_Feuille'].'\')"'; ?>><span class="glyphicon glyphicon-asterisk <?php echo $Text_Style; ?>"></span>Importer</span></div>

    <div class="col-lg-2 col-md-2 col-sm-2" style="cursor: pointer"><span class=" label <?php echo $Text_Style; ?>" <?php echo 'onclick="Telecharger_Fichier_Excel(\''.$row5['Code_Feuille'].'\',\''.str_replace(' ','_',$row5['Nom_Feuille']).'\')"'; ?>><span class="glyphicon glyphicon-asterisk <?php echo $Text_Style; ?>"></span>Exporter</span></div>
<script type="text/javascript">
  function Afficher_Formulaire_Insertion(Cod){
    $.ajax({url:"traitement_jquery/generation_formulaire.php?Code="+Cod, method:"POST", data:$('#form_feuille_modification').serialize(), success:function (data) {if(data!='')
        {$("#add_form_perso_modal_body").html(data);
            $("#add_form_perso_modal").modal('show');
  document.getElementById('form_personnalise').addEventListener('submit',function(e){
    e.preventDefault();
    document.getElementById("submit").disabled=true;
    Envoyer_Form_Auto();
  });
        }else {}}});
        }
function Envoyer_Form_Auto(){
     var form = $("#form_personnalise").get(0);
    var formData = new FormData(form);
    var $file = $("input[type=file]");
    var file = $file[0].files[0];
    //formData.append('Fichier', file);
    formData.append("File",$file[0].files);
$.ajax({url:"traitement_jquery/traitement_auto_formulaire.php", method:"POST", processData:false, contentType:false, data:formData, success:function (data) {
//alert(data);
  if(data=='')
        {
var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];
window.location.href=(Url+"?c="+Code_Classeur+"&f="+feuille_active);

        }else {}}});}

function Envoyer_Form_Auto_Modif(){
     var form = $("#form_personnalise").get(0);
    var formData = new FormData(form);
    var $file = $("input[type=file]");
    var file = $file[0].files[0];
    formData.append("File",$file[0].files);
$.ajax({url:"traitement_jquery/traitement_auto_formulaire_modification.php", method:"POST", processData:false, contentType:false, data:formData, success:function (data) {//alert(data);
  if(data=='')
        {
var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];
window.location.href=(Url+"?c="+Code_Classeur+"&f="+feuille_active);

        }else {}}});}


function Afficher_Formulaire_Insertion_Donnees(Feuille, Id)
{
  $.ajax({url:"traitement_jquery/generation_formulaire_modification.php?Code="+Feuille+"&Id="+Id, method:"POST", data:$('#form_feuille_modification').serialize(), success:function (data) {if(data!='')
        { $("#add_form_perso_modal_body").html(data);

          $("#add_form_perso_modal").modal('show');
  document.getElementById('form_personnalise').addEventListener('submit',function(e){
    e.preventDefault();
    Envoyer_Form_Auto_Modif();
  });
        }else {}}});

}

</script>
    <div class="col-lg-2 col-md-2 col-sm-2" style="cursor: pointer;" <?php echo 'onclick="Afficher_Formulaire_Insertion(\''.$row5['Code_Feuille'].'\')"'; ?>><span class=" label <?php echo $Text_Style; ?>"><span class="glyphicon glyphicon-asterisk <?php echo $Text_Style; ?>"></span>Nouvelle donnée</span></div>
</div>

<?php
     echo '<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
            <div class="panel-heading">
               <div align="right" style="">
               <a  class="btn '.$Boutton_Style.'" id="Btn_Valider_Donnees_'.$row5['Code_Feuille'].'" style="display: none;">Valider<span class=""></span></a>

               <a  class="btn '.$Boutton_Style.'" id="Btn_Valider_Tout_Donnees_'.$row5['Code_Feuille'].'" onclick="" style="">Valider tout<span class=""></span></a>


               </div>
               <div class="panel-tools">

                    <!--
                    <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                    <a class="closebox"><i class="fa fa-times"></i></a>-->
                </div>
            </div>
            <div class="panel-body" style="padding:0px!important; margin:0px!important">
                <div class="table-responsive">
                <table class="table table-striped table-sm" style="margin:0px!important; padding:0px!important"  id="tab_'.$row5['Code_Feuille'].'">
                  <thead>  <tr style=" background-color:#F1F3F6; text-align: center">';
echo '<th style="text-align: center;"><input type="checkbox" name="" id="checkbox_valider_th'.$row5['Code_Feuille'].'" class="" style="display: none;"></th>';
    echo '<th>#</th>';
    echo '<th>#</th>';
    echo '<th style="border-right:1px solid silver;">#</th>';
    if($row5['Code_Feuille'] == $Code_Feuille_Carte){echo '<th style="border-right:1px solid silver;">Carte</th>';}
 $Nb_Col=0;   $compte=0;
       foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne WHERE ( Code_Feuille=\''.$row5['Code_Feuille'].'\' AND Afficher=\'Oui\') ORDER BY Rang') as $row6)
    {echo '<th>'.$row6['Libelle_Ligne'].'</th>';$Nb_Col++;}
    
    echo '</tr></thead><tbody>';
    $indd=0; $indu=0; $i=0;
    try{

      if($row5["Source_Donnees"] == 'Oui')
        {$Res=FC_Rechercher_Code('SELECT * FROM t_feuille_ligne INNER JOIN ('.str_replace("t", "v", $row5['Table_Feuille']).' INNER JOIN t_feuille ON (t_feuille.Table_Feuille=\''.$row5['Table_Feuille'].'\')) ON (t_feuille_ligne.Code_Feuille=t_feuille.Code_Feuille) WHERE (Afficher=\'Oui\') ORDER BY Stat ASC, Id DESC, Rang ASC');}
      else {$Res=FC_Rechercher_Code('SELECT * FROM t_feuille_ligne INNER JOIN ('.str_replace("t", "v", $row5['Table_Feuille']).' INNER JOIN t_feuille ON (t_feuille.Table_Feuille=\''.$row5['Table_Feuille'].'\')) ON (t_feuille_ligne.Code_Feuille=t_feuille.Code_Feuille) WHERE (Afficher=\'Oui\' '.$Filtre_Admin.') ORDER BY Stat ASC, Id DESC, Rang ASC');}
    
    if($Res!=null){
    foreach ($Res as $row6)
    {
    if($indd%$Nb_Col==0){echo '<tr style="margin:0px!important; padding:0px!important">'; $compte++;
    echo '<td style="background-color:#F1F3F6;  border-left:1px solid silver; width:20px" width="20px!important">';
    if($row6['Stat']=='0'){echo '<input type="checkbox" value="'.$row6['Id'].'" name="checkbox_valider_td'.$row5['Code_Feuille'].'[]" id="" class="">';}
    echo '</td>';
    echo '
<td style="background-color:#F1F3F6; text-align: center; width: 20px"><span class="glyphicon glyphicon-map-marker text-primary" style="cursor: pointer; width:20px" onclick="Afficher_Position(\''.$row5['Code_Feuille'].'\','.$row6['Id'].')"></span></td>
<td style="background-color:#F1F3F6; text-align: center; width: 20px"><span class="glyphicon glyphicon-pencil text-info" style="cursor: pointer; width:20px" onclick="Afficher_Formulaire_Insertion_Donnees(\''.$row5['Code_Feuille'].'\','.$row6['Id'].')"></span></td>
<td style="border-right:1px solid silver; background-color:#F1F3F6; text-align: center; width: 20px"><span class="glyphicon glyphicon-remove text-danger" style="cursor: pointer; width:20px" onclick="if(confirm(\'Voulez-vous supprimer cette ligne?\')){window.location.href=\'traitement_jquery/feuille_donnees_supprimer.php?c='.$_GET['c'].'&f='.$row5['Code_Feuille'].'&d='.$row6['Id'].'&tab='.$row5['Table_Feuille'].'\'}"></span></td>';
if($row5['Code_Feuille'] == $Code_Feuille_Carte){echo '<th style="border-right:1px solid silver; background-color:#F1F3F6; text-align: center; width: 20px"><span class="glyphicon glyphicon-user text-success" style="cursor: pointer; width:20px" onclick="Generer_Code(\''.$row5['Code_Feuille'].'\','.$row6['Id'].')"></span></th>';}
    $indu=0; $i=0;} $indd++;
    if($row6['Afficher']=='Oui'){

        switch ($row6['Type_Ligne']){
            case 'FEUILLE' :
            echo '<td><a href="classeur_details.php?c=';
             $Valeur="";
   foreach (FC_Rechercher_Code('SELECT * FROM t_feuille INNER JOIN t_feuille_etrangere ON (t_feuille.Table_Feuille=t_feuille_etrangere.Nom_Table) WHERE (t_feuille_etrangere.Nom_Table=\''.$row6['Table_Feuille'].'\' AND t_feuille_etrangere.Nom_Colonne=\''.$row6['Nom_Collone'].'\')') AS $rowxx)
{$Valeur=$rowxx['Valeur'];}
$Table_Choix=null;
$Table_Choix=explode(';', $Valeur);
   foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE Code_Feuille='.str_replace(' ', '', $Table_Choix[0])) AS $row10xx)
{echo base64_encode($row10xx["Code_Classeur"]).'&f='.$row10xx["Code_Feuille"];}

            echo '&search='.urlencode($row6[$row6['Nom_Collone']]).'"><span  style="color:blue; font-weight: bold">'.$row6[$row6['Nom_Collone']].'</span></a></td>';

              break;
            case 'TEXT' : case 'DATE' : case 'CHOIX' : case 'QRCODE' :
            echo '<td>'.$row6[$row6['Nom_Collone']].'</td>';
                break;
            case 'INT' :
            echo '<td>'.number_format($row6[$row6['Nom_Collone']],0, '',' ').'</td>';
                break;
            case 'DOUBLE' :
            echo '<td>'.number_format($row6[$row6['Nom_Collone']],2, '.',' ').'</td>';
                break;
            case 'SOMME' : case 'MOYENNE' : case 'DIFFERENCE' : case 'PRODUIT' :
            echo '<td>'.number_format($row6[$row6['Nom_Collone']],2, '.',' ').'</td>';
                break;
            case 'RAPPORT' :
            echo '<td>'.number_format($row6[$row6['Nom_Collone']],2, '.',' ').'%</td>';
                break;

            case 'COULEUR' :
            echo '<td><div style="border-radius: 50% ;height: 20px ; background-color:'.$row6[$row6['Nom_Collone']].'"></div></td>';
                break;

            case 'FICHIER' :
            echo '<td align="center">';
            if(!empty($row6[$row6['Nom_Collone']]))
                {echo '<a href="pieces/'.$row6[$row6['Nom_Collone']].'" download="'.$row6[$row6['Nom_Collone']].'" style="font-size: 14px"><span class="glyphicon glyphicon-paperclip"></span></a>';}
            echo '</td>';
                break;

            case 'COMPTER' :
            echo '<td>'.number_format($compte,0, '',' ').'</td>';
                break;

            default:
                # code...
                break;
        }


        $indu++;}
    if($indu%$Nb_Col==0){

        echo '</tr>'; $indu=0; }
    $i++;
    }}
}
catch(Exception $e){}
    echo '</tbody></table></div>

            </div>
            <div class="panel-footer">
            '.$compte.'   ligne(s)
            </div>
        </div>
    </div></div>
  </div>';

echo '
<script type="text/javascript">
$("#tab_'.$row5['Code_Feuille'].'").dataTable(
{"order" : [],
  "autoWidth" : false,';

if((isset($_GET["f"]) AND $_GET["f"]==$row5['Code_Feuille']) AND (isset($_GET["search"]) AND !empty($_GET["search"])))
{
  echo 'search : {search : "'.urldecode($_GET["search"]).'", regex : false, smart : false},';
}


echo '"columnDefs":[{"targets" : [0,1,2,3], "orderable" : false}]});
var Checkbox_valider_th'.$row5['Code_Feuille'].' = document.getElementById(\'checkbox_valider_th'.$row5['Code_Feuille'].'\');
var Checkbox_valider_td'.$row5['Code_Feuille'].' = document.getElementsByName(\'checkbox_valider_td'.$row5['Code_Feuille'].'[]\');

if(Checkbox_valider_td'.$row5['Code_Feuille'].'.length>0)
{Btn_Valider_Donnees_'.$row5['Code_Feuille'].'.style.display="inline-block";
 Checkbox_valider_th'.$row5['Code_Feuille'].'.style.display="table";
}

Checkbox_valider_th'.$row5['Code_Feuille'].'.addEventListener(\'click\', function(e){
    if(this.checked==true)
    {   for(i=0; i<Checkbox_valider_td'.$row5['Code_Feuille'].'.length; i++)
        {Checkbox_valider_td'.$row5['Code_Feuille'].'[i].checked=true;}
    }
    else
    {
        for(i=0; i<Checkbox_valider_td'.$row5['Code_Feuille'].'.length; i++)
        {Checkbox_valider_td'.$row5['Code_Feuille'].'[i].checked=false;}}
});


Btn_Valider_Donnees_'.$row5['Code_Feuille'].'.addEventListener(\'click\', function(e){
  var check=0;
    for(i=0; i<Checkbox_valider_td'.$row5['Code_Feuille'].'.length; i++)
    {if(Checkbox_valider_td'.$row5['Code_Feuille'].'[i].checked==true){check++;}
    }
if(check==0){alert("Veuillez cocher une case");}
else{
    var Forms = document.getElementById(\'form_perso_feuille_donnees\');
        Forms.innerHTML+=\'<input type="hidden" name="Feuille" value="'.$row5['Table_Feuille'].'">\';
    for(i=0; i<Checkbox_valider_td'.$row5['Code_Feuille'].'.length; i++)
    {if(Checkbox_valider_td'.$row5['Code_Feuille'].'[i].checked==true)
    {Forms.innerHTML+=\'<input type="hidden" name="Code[]" value="\'+Checkbox_valider_td'.$row5['Code_Feuille'].'[i].value+\'">\';}
    }
   $.ajax({url:"traitement_jquery/feuille_donnees_valider.php", method:"POST", data:$(\'#form_perso_feuille_donnees\').serialize(), success:function (data) {if(data==\'\')
        {
var pathArray = window.location.pathname.split(\'/\');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];/*var Url=window.location.pathname.substring(1);*/ window.location.href=(Url+"?c="+Code_Classeur+"&f="+feuille_active);
        }else {}}});
    }});
 Btn_Valider_Tout_Donnees_'.$row5['Code_Feuille'].'.addEventListener(\'click\', function(e){


    var Forms = document.getElementById(\'form_perso_feuille_donnees\');
        Forms.innerHTML+=\'<input type="hidden" name="Feuille" value="'.$row5['Table_Feuille'].'">\';
        Forms.innerHTML+=\'<input type="hidden" name="Tout" value="Oui">\';

   $.ajax({url:"traitement_jquery/feuille_donnees_valider.php", method:"POST", data:$(\'#form_perso_feuille_donnees\').serialize(), success:function (data) {if(data==\'\')
        {
var pathArray = window.location.pathname.split(\'/\');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];/*var Url=window.location.pathname.substring(1);*/ window.location.href=(Url+"?c="+Code_Classeur+"&f="+feuille_active);
        }else {}}});
    });
';   
?>


</script>

<?php
    }
    ?>
</div>


<script type="text/javascript">

var DIV_FEUILLE='';

<?php
echo 'DIV_FEUILLE=\'';
foreach (FC_Rechercher('t_feuille') AS $row8)
{echo '<option value="'.addslashes($row8['Code_Feuille']).'">'.addslashes($row8['Nom_Feuille']).'</option>';}
echo '\'; ';
?>

function Charger_Col_Nom(Valeur, Col)
{if(Valeur>=1)
{
$.ajax({url:"traitement_jquery/check_champs.php?Code="+Valeur, method:"POST", data:$('#form_classeur').serialize(), success:function (data) {if(data==''){}else {document.getElementById(Col).innerHTML=data;}}});
}
else
{document.getElementById(Col).innerHTML='<option value="">Champ</option>';}
}

function Charger_Div(Id, Code)
{    var date=new Date();
    var stamp=date.getTime();
    if(Id=="CHOIX")
 {var DIV_CHOIX='<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text" class="form-control" required placeholder="Sepaper les valeurs par point-virgule \';\' Exemple: HOMME ; FEMME"  onkeyup="document.getElementById(\'Valeur_'+Code+'\').value=this.value"></div>';
    document.getElementById('Under_Div_'+Code).innerHTML=DIV_CHOIX;}
 else if(Id=="FEUILLE")
 {document.getElementById('Under_Div_'+Code).innerHTML='<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-3 col-md-3 mb-3"><select name="" id="Col_Nom2_'+stamp+'" onchange="Charger_Col_Nom(this.value, \'Col_Nom_'+stamp+'\')" id="" required class="form-control"><option value="0">Feuille</option>'+DIV_FEUILLE+'</select></div><div class="col-sm-3 col-md-3 mb-3"><select name="" onchange="document.getElementById(\'Valeur_'+Code+'\').value=(document.getElementById(\'Col_Nom2_'+stamp+'\').value+\';\'+this.value)"  id="Col_Nom_'+stamp+'" required class="form-control"><option value="">Champ</option></select></div>';}

 else if(Id=="SOMME")
 {document.getElementById('Under_Div_'+Code).innerHTML='<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text" class="form-control"  style="font-size:12px" required placeholder="Sepaper les noms de colonne par point-virgule \';\'  Colonne1+Colonne2 : Colonne1 ; Colonne2"  onkeyup="document.getElementById(\'Valeur_'+Code+'\').value=this.value"></div>';}
 else if(Id=="DIFFERENCE")
 {document.getElementById('Under_Div_'+Code).innerHTML='<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text"  style="font-size:12px" class="form-control" required placeholder="Sepaper les noms de colonne par point-virgule \';\' Colonne1-Colonne2 : Colonne1 ; Colonne2"  onkeyup="document.getElementById(\'Valeur_'+Code+'\').value=this.value"></div>';}
 else if(Id=="PRODUIT")
 {document.getElementById('Under_Div_'+Code).innerHTML='<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text"  style="font-size:12px" class="form-control" required placeholder="Sepaper les noms de colonne par point-virgule \';\' Colonne1*Colonne2 : Colonne1 ; Colonne2"  onkeyup="document.getElementById(\'Valeur_'+Code+'\').value=this.value"></div>';}
 else if(Id=="RAPPORT")
 {document.getElementById('Under_Div_'+Code).innerHTML='<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text"  style="font-size:12px" class="form-control" required placeholder="Sepaper les noms de colonne par point-virgule \';\' Colonne1/Colonne2 : Colonne1 ; Colonne2"  onkeyup="document.getElementById(\'Valeur_'+Code+'\').value=this.value"></div>';}
 else if(Id=="MOYENNE")
 {document.getElementById('Under_Div_'+Code).innerHTML='<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text"  style="font-size:12px" class="form-control" required placeholder="Sepaper les noms de colonne par point-virgule \';\' Colonne1,Colonne2,Colonne3 : Colonne1 ; Colonne2 ; Colonne3"  onkeyup="document.getElementById(\'Valeur_'+Code+'\').value=this.value"></div>';}
 else
 {document.getElementById('Under_Div_'+Code).innerHTML='';}

}


function Charger_Div2(Id, Code)
{    var date=new Date();
    var stamp=date.getTime();
    if(Id=="CHOIX")
 {var DIV_CHOIX='<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text" class="form-control" required placeholder="Sepaper les valeurs par point-virgule \';\' Exemple: HOMME ; FEMME"  onkeyup="document.getElementById(\'Valeur_'+Code+'\').value=this.value"></div>';
    document.getElementById('Under_Div_'+Code).innerHTML=DIV_CHOIX;}
 else if(Id=="FEUILLE")
 {document.getElementById('Under_Div_'+Code).innerHTML='<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-3 col-md-3 mb-3"><select name="" id="Col_Nom2_'+stamp+'" onchange="Charger_Col_Nom(this.value, \'Col_Nom_'+stamp+'\')" id="" required class="form-control"><option value="0">Feuille</option>'+DIV_FEUILLE+'</select></div><div class="col-sm-3 col-md-3 mb-3"><select name="" onchange="document.getElementById(\'Valeur_'+Code+'\').value=(document.getElementById(\'Col_Nom2_'+stamp+'\').value+\';\'+this.value)"  id="Col_Nom_'+stamp+'" required class="form-control"><option value="">Champ</option></select></div>';}

 else if(Id=="SOMME")
 {document.getElementById('Under_Div_'+Code).innerHTML='<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text" class="form-control"  style="font-size:12px" required placeholder="Sepaper les noms de colonne par point-virgule \';\'  Colonne1+Colonne2 : Colonne1 ; Colonne2"  onkeyup="document.getElementById(\'Valeur_'+Code+'\').value=this.value" ></div>';}
 else if(Id=="DIFFERENCE")
 {document.getElementById('Under_Div_'+Code).innerHTML='<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text"  style="font-size:12px" class="form-control" required placeholder="Sepaper les noms de colonne par point-virgule \';\' Colonne1-Colonne2 : Colonne1 ; Colonne2"  onkeyup="document.getElementById(\'Valeur_'+Code+'\').value=this.value"></div>';}
 else if(Id=="PRODUIT")
 {document.getElementById('Under_Div_'+Code).innerHTML='<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text"  style="font-size:12px" class="form-control" required placeholder="Sepaper les noms de colonne par point-virgule \';\' Colonne1*Colonne2 : Colonne1 ; Colonne2"  onkeyup="document.getElementById(\'Valeur_'+Code+'\').value=this.value"></div>';}
 else if(Id=="RAPPORT")
 {document.getElementById('Under_Div_'+Code).innerHTML='<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text"  style="font-size:12px" class="form-control" required placeholder="Sepaper les noms de colonne par point-virgule \';\' Colonne1/Colonne2 : Colonne1 ; Colonne2"  onkeyup="document.getElementById(\'Valeur_'+Code+'\').value=this.value"></div>';}
 else if(Id=="MOYENNE")
 {document.getElementById('Under_Div_'+Code).innerHTML='<div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-6 col-md-6 mb-6"><input type="text"  style="font-size:12px" class="form-control" required placeholder="Sepaper les noms de colonne par point-virgule \';\' Colonne1,Colonne2,Colonne3 : Colonne1 ; Colonne2 ; Colonne3"  onkeyup="document.getElementById(\'Valeur_'+Code+'\').value=this.value"></div>';}
 else
 {document.getElementById('Under_Div_'+Code).innerHTML='';}

}
</script>

        </div>
    </div>
    <?php //require_once "./theme_components/footer.php"; ?>
</div>

<?php
require_once'requires/formulaire_modification.php';
require_once'requires/formulaire_insertion.php';
?>

<script type="text/javascript">
var Liste_Code='';
var Conteneur = document.getElementById('panel-body');
function Ajouter_A_La_Liste()
{$("#panel-body").append('<div class="div_ligne_feuille" id="div_ligne_feuille_'+(document.getElementsByClassName("div_ligne_feuille").length+1)+'"><div class="row" ><br><div class="col-sm-2 col-md-2 mb-2"><input type="text" class="form-control" placeholder="Nom Ligne" required  name="nom_Ligne[]" id="nom_Ligne[]"></div><div class="col-sm-2 col-md-1.5 mb-2"><input type="text" required class="form-control" placeholder="Libelle" name="libelle_Ligne[]" id="libelle_Ligne[]"></div><div class="col-sm-2 col-md-2 mb-2"><select class="form-control" required placeholder="" onchange="Charger_Div(this.value, '+(document.getElementsByClassName("div_ligne_feuille").length+1)+')" name="type_Ligne[]" id="type_Ligne[]"><option value="TEXT">TEXT</option><option value="INT">INT</option><option value="DOUBLE">DOUBLE</option><option value="DATE">DATE</option><option value="CHOIX">CHOIX</option><option value="COULEUR">COULEUR</option><option value="FICHIER">FICHIER</option><option value="FEUILLE">FEUILLE</option><option value="RAPPORT">RAPPORT</option><option value="SOMME">SOMME</option><option value="DIFFERENCE">DIFFERENCE</option><option value="PRODUIT">PRODUIT</option><option value="MOYENNE">MOYENNE</option><option value="COMPTER">COMPTER</option></select></div><div class="col-sm-2 col-md-2 mb-2"><select class="form-control" placeholder="" required name="requis[]" id="requis[]"><option value="Oui">Oui</option><option value="Non">Non</option></select><input type="hidden" name="valeur[]" id="Valeur_'+(document.getElementsByClassName("div_ligne_feuille").length+1)+'"></div><div class="col-sm-2 col-md-2 mb-2"><select class="form-control" placeholder="" required name="afficher[]" id="afficher[]"><option value="Oui">Oui</option><option value="Non">Non</option></select></div><div class="col-sm-1 col-md-1 mb-1"></div><div class="col-sm-1 col-md-1 mb-1"><div class="btn btn-xs btn-default" onclick="Supprimer_Item(\''+(document.getElementsByClassName("div_ligne_feuille").length+1)+'\')" title="Supprimer" ><span class="nav-label glyphicon glyphicon-remove text-danger" ></span></div></div><br></div><div class="row" id="Under_Div_'+(document.getElementsByClassName("div_ligne_feuille").length+1)+'"></div></div>');}


document.getElementById('boutton_nouvelle_feuille').addEventListener('click', Ajouter_A_La_Liste);

function Supprimer_Item(Code)
{document.getElementById('div_ligne_feuille_'+Code).innerHTML='';}
document.getElementById('form_feuille_insertion').addEventListener('submit', function(e){e.preventDefault();
var form = $("#form_feuille_insertion").get(0);
    var formData = new FormData(form);
    var $file = $("input[type=file]");
    var file = $file[0].files[0];
    formData.append('Icone', file);
$.ajax({url:"traitement_jquery/feuille_traitement.php", method:"POST", processData:false, contentType:false, data:formData, success:function (data) {if(data==''){
var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];/*var Url=window.location.pathname.substring(1);*/ window.location.href=(Url+"?c="+Code_Classeur+"&f="+(feuille_total+1));}else {}}});
});

</script>

</body>
</html>