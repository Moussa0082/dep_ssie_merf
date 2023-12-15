<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
//var_dump($_SESSION);
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
<body class="">
    <?php require_once "./theme_components/header.php"; ?>
    <?php //require_once "./theme_components/main-menu.php"; ?>
<!-- Main Wrapper -->
<div id="">
<?php require_once "./theme_components/sub-header.php"; ?>
    <div class="content animate-panel">
        <div class="row">
<script>
$("#search").hide();
$(document).ready(function(){$(".modal").off( "hidden.bs.modal", null );});
$("#mbreadcrumb").html(<?php $link = ""; if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2){ if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) $link .= '<div class="btn-circle-zone">'.do_link("btn_classeur","#add_classeur_modal","Ajout de classeur","<span title='Nouveau classeur' class='glyphicon glyphicon-plus'></span>","simple","./","btn btn-success btn-circle mgr-5","",1,"",$nfile);
$link .= '</div>';
echo GetSQLValueString($link, "text"); } ?>);
$("#btn_classeur").attr("data-toggle","modal");$("#btn_classeur").attr("data-target","#add_classeur_modal");
</script>

<form id="Form_Perso">

</form>
<?php require_once 'requires/formulaire_insertion_123.php';  ?>
<script type="text/javascript">
    function Modifier_Classeur(Code)
 {
    $.ajax({url:"traitement_jquery/formulaire_modification_classeur.php?classeur="+Code, method:"POST", data:$('#Form_Perso').serialize(), success:function (data) {

    if(data!='')
        {
         $("#add_form_perso_modal_titre").html("Modification");
         $("#Form_Contenu_Perso").html(data);
         $("#add_form_perso_modal").modal('show');

  document.getElementById('form_classeur').addEventListener('submit',function(e){
  e.preventDefault();
    var form = $("#form_classeur").get(0);
    var formData = new FormData(form);
    /*var $file = $("input[type=file]");
    var file = $file[0].files[0];
    formData.append('Icone', file); */
    $.ajax({url:"traitement_jquery/modification_classeur.php", method:"POST", processData:false, contentType:false, data:formData, success:function (data) {
        if(data=='')
        {var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];/*var Url=window.location.pathname.substring(1);*/ window.location.href=(Url);}
        else{}
                                                }
    });
    });
        }else {}}});


 }




     function Supprimer_Classeur(Code)
 {
    if(confirm('Voulez-vous définitivement Supprimer ce classeur?'))
 {   
    $.ajax({url:"traitement_jquery/supprimer_classeur.php?Code_Classeur="+Code, method:"POST", data:$('#Form_Perso').serialize(), success:function (data) {        if(data=='')
        {var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];/*var Url=window.location.pathname.substring(1);*/ window.location.href=(Url);}
        else{}
                            }

});

}
 }
</script>
 <div class="content">
<?php
$ii=0;
$ui=0;
$Id_Projet=(isset($_SESSION['clp_projet']) AND !empty($_SESSION['clp_projet']))?$_SESSION['clp_projet']:"NULL";
//$Id_Projet="NULL";
foreach (FC_Rechercher_Code("SELECT * FROM t_classeur WHERE (Id_Projet = '".$Id_Projet."') ORDER BY Code_Classeur DESC") as $row3)
{if($ii%3==0){echo '<div class="row projects">';}
$ui++;
echo ' <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="hpanel " style="border-top: 2px solid '.$Panel_Item_Style.'">
                    <div class="panel-body">
                       ';
                        if(strstr($row3['Date_Insertion'], date('Y-m-d'))){echo '<span class="label '.$Label_Style.' pull-right">NEW</span>';}
                        echo '<div class="row" style="text-align: left">
                            <div class="col-sm-10">
                                <h4><a href="classeur_details.php?c='.base64_encode($row3['Code_Classeur']).'">'.$row3['Libelle_Classeur'].'</a></h4>

                                <p>'.$row3['Note_Classeur'].'</p>

                                <div class="row">

                                    <div class="col-sm-10">
                                        <div class="project-label"><small>Nombre de feuilles : </small>';
foreach (FC_Rechercher_Code('SELECT COUNT(*) AS NB FROM t_feuille WHERE (Code_Classeur='.$row3['Code_Classeur']." AND Statut=1)") as $row4)
{echo $row4['NB']; echo '<script type="text/javascript"> var C_'.$row3['Code_Classeur'].'='.$row4['NB'].';</script>';}
                                        echo '</div>

                                    </div>
                                    <div class="col-sm-2">
                                        <div class="project-label"><div class="" style="background-color:'.$row3['Couleur_Classeur'].'; border-radius: 50%; width: 20px; height: 20px"></div></div>

                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-2 project-info">
                                <div class="project-action m-t-md">
                                    <div class="btn-group" style="font-size: 18px">';
                         if(strtolower(trim($_SESSION["clp_id"])) == "admin"){
                                    echo   ' <button class="btn btn-xs btn-default" onclick="Modifier_Classeur(\''.$row3['Code_Classeur'].'\')" id="" title="Modifier"><span class="nav-label glyphicon glyphicon-pencil text-info" ></span></button>';}
                                  echo ' </div>
                                    <div class="btn-group" style="font-size: 18px">';
                        if(strtolower(trim($_SESSION["clp_id"])) == "admin"){echo '   <button class="btn btn-xs btn-default" id="btn_del_'.$row3['Code_Classeur'].'" disabled onclick="Supprimer_Classeur(\''.$row3['Code_Classeur'].'\')" id="" title="Supprimer"><span class="nav-label glyphicon glyphicon-trash text-danger" ></span></button>';}
                                       echo '
<script type="text/javascript"> if (C_'.$row3['Code_Classeur'].' == 0) {document.getElementById("btn_del_'.$row3['Code_Classeur'].'").disabled=false;} </script>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="panel-footer"><a href="classeur_details.php?c='.base64_encode($row3['Code_Classeur']).'" >Ouvrir le classeur</a></div>
                </div>
            </div>';

if($ui%3==0){echo ' </div>'; $ui=0;}
 $ii++;
}

      ?>
</div>

        </div>
    </div>
    <?php //require_once "./theme_components/footer.php"; ?>
</div>
<?php
require_once'requires/formulaire_modification.php';
require_once'requires/formulaire_insertion.php';
?>
<script type="text/javascript">
document.getElementById('form_classeur').addEventListener('submit', function(e){
    e.preventDefault();
    if($("#libelle_classeur").val().trim()=="")
    {}
    else
    {document.getElementById("submit").disabled=true; Envoyer_Classeur();}
    function Envoyer_Classeur(){
$.ajax({url:"traitement_jquery/classeur_traitement.php", method:"POST", data:$('#form_classeur').serialize(), success:function (data) {if(data==''){
var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1]; window.location.href=Url;}else {}}});}
});
</script>

</body>
</html>