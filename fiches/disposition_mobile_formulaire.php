<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
if (!isset ($_SESSION["clp_id"])) {
    header(sprintf("Location: %s", "./login.php"));  exit();
}
include_once 'api/configuration.php';
$config = new Config;

require_once 'api/Fonctions.php';
require_once 'theme_components/theme_style.php';

      if(isset($_GET['c']) AND !empty($_GET['c']))
      {$Code_Classeur=base64_decode($_GET['c']);
        $Nom_Classeur="";
        $ii=0;
        foreach (FC_Rechercher_Code('SELECT * FROM t_classeur WHERE Code_Classeur=\''.$Code_Classeur.'\'') as $row4) 
        {$ii++; $uuu=0;
          $Nom_Classeur=$row4['Libelle_Classeur'];
        if(isset($_GET['f']) AND !empty($_GET['f']))
            {
              foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE Code_Feuille=\''.$_GET['f'].'\'') as $rowx) {$uuu++;}
        if($uuu==0){header('location:fiches_dynamiques.php');}} 
          
        if($ii==0){header('location:fiches_dynamiques.php');}

      }
    }
    else{header('location:fiches_dynamiques.php');}
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
    <link rel="stylesheet" href="styles/bootstrap-select.css" />

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
    <script src="scripts/bootstrap-select.js"></script>

<style type="text/css">
    .active .active_navbar_a{border-top: 3px solid <?php echo $Panel_Item_Style; ?>!important}
     @media (min-width: 820px) {
  .modal-feuille {
    width: 80%!important;
    margin: 30px auto!important;
  }}
</style>
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
$("#mbreadcrumb").html(<?php $link = '<div class="btn-circle-zone"><a href="#" class="btn btn-success btn-circle mgr-5" style="transform: rotate(180deg);" title="Retour à la fiche" onclick="window.location.href=\'classeur_details.php?c='.$_GET["c"].'&f='.$_GET["f"].'\'"><span title="Retour à la fiche" class="glyphicon glyphicon-share-alt"></span></a>'; //if(isset($_SESSION['niveau']) && $_SESSION['niveau']==1) $link .= do_link("btn_feuille","#add_feuille_modal","Ajout de feuille","<span title='Nouvelle fiche' class='glyphicon glyphicon-plus'></span>","simple","./","btn btn-success btn-circle mgr-5","",1,"",$nfile);
$link .= '</div>';
echo GetSQLValueString($link, "text"); ?>);
//$("#btn_feuille").attr("data-toggle","modal");$("#btn_feuille").attr("data-target","#add_feuille_modal");
</script>
<?php echo '<script type="text/javascript" charset="utf-8" > var Code_Classeur="'.base64_encode($Code_Classeur).'";</script>'; ?>

<form id="Form_Perso">
    
</form>
<?php require_once 'requires/formulaire_insertion_123.php';  ?>

 <div class="content" style="background: #FFF;padding: 10px;" align="center">

<form id="form_disposition_mobile" action="traitement_jquery/disposition_mobile.php" method="POST">

<?php
if($_GET){
  extract($_GET);
  foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE Code_Feuille='.$f) as $row4)
  {echo '<div class="row">
<div class="col-sm-1 col-md-1 mb-1"><label>Nom Feuille<font style="color: red" >*</font></label></div>
<div class="col-sm-5 col-md-5 mb-5"><input readonly type="text" required class="form-control" placeholder="Nom feuille" name="Nom_Feuille" value="'.$row4['Nom_Feuille'].'" id="Nom_Feuille"></div>
<input type="hidden" name="Code_Feuille" value="'.$f.'">
<div class="col-sm-1 col-md-1 mb-1"><label> Libelle feuille<font style="color: red" >*</font></label></div>
<div class="col-sm-4 col-md-4 mb-4"><textarea required class="form-control" placeholder="Libelle" name="Libelle_Feuille" id="Libelle_Feuille" readonly>'.$row4['Libelle_Feuille'].'</textarea></div><input type="hidden" name="Code_Classeur" value="'.base64_decode($c).'"><div class="col-sm-1 col-md-1 mb-1"></div></div><br><div class="row"><div class="col-sm-1 col-md-1 mb-1"><label>Nb. ligne (impr.)<font style="color: red" >*</font></label></div><div class="col-sm-2 col-md-2 mb-2"><input type="number" min="1" step="1" readonly value="'.$row4['Nb_Ligne_Impr'].'" required class="form-control" placeholder="Nombre de ligne à imprimer" name="Nb_Ligne_Impr" id="Nb_Ligne_Impr"></div><div class="col-sm-1 col-md-1 mb-1"><label> Icone<font style="color: red" >*</font> </label></div><div class="col-sm-2 col-md-2 mb-2"></div><div class="col-sm-1 col-md-1 mb-1"><label> Note<font style="color: red" >*</font></label></div><div class="col-sm-4 col-md-4 mb-4"><textarea readonly class="form-control" placeholder="Note" name="Note" id="Note">'.$row4['Note'].'</textarea></div><div class="col-sm-1 col-md-1 mb-1"></div></div><br><div class="row">

<div class="col-sm-2 col-md-2 mb-2"><label> Formulaire </label></div>

<div class="col-sm-10 col-md-10 mb-10"><label> Colonnes </label></div>

</div>';
$Nb_Formulaire_0=0;
$Dernier_Formulaire=0;
foreach (FC_Rechercher_Code('SELECT DISTINCT Formulaire FROM t_feuille_ligne INNER JOIN t_feuille ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE t_feuille_ligne.Code_Feuille='.$f." ORDER BY Formulaire") as $row9) 
{echo '<div class="row">
<div class="col-sm-2 col-md-2 mb-2">';

if($row9['Formulaire']==0)
{echo '<input type="text" readonly value="Formulaire par défaut" class="form-control">'; $Nb_Formulaire_0++;}
else
{echo '<input type="text" readonly value="Formulaire '.$row9['Formulaire'].'" class="form-control">'; $Dernier_Formulaire=$row9['Formulaire'];}

echo '<input type="hidden" name="Formulaires[]" value="'.$row9['Formulaire'].'">';

echo '</div>';

if($row9['Formulaire']==0)
{

echo '<div class="col-sm-10 col-md-10 mb-10">
<select class="selectpicker bts_select form-control" multiple data-live-search="true">';

foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne INNER JOIN t_feuille ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE (t_feuille_ligne.Code_Feuille='.$f." AND Formulaire=".$row9['Formulaire'].") ORDER BY Formulaire ") as $row10) 
{
 echo '<option value="'.$row10["Code_Feuille_Ligne"].'" selected disabled>'.$row10["Libelle_Ligne"].'</option>';

}
echo '</select></div>';
}

else
{

echo '<div class="col-sm-10 col-md-10 mb-10">
<select class="selectpicker bts_select form-control" multiple data-live-search="true" name="Select_Form['.($Dernier_Formulaire).'][]">';

foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne INNER JOIN t_feuille ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE (t_feuille_ligne.Code_Feuille='.$f." AND (Formulaire=".$row9['Formulaire']." OR Formulaire=0)) ORDER BY Formulaire ") as $row10) 
{
    if($row9['Formulaire']==$row10['Formulaire'])
    {echo '<option value="'.$row10["Code_Feuille_Ligne"].'" selected>'.$row10["Libelle_Ligne"].'</option>';}
    else
    {echo '<option value="'.$row10["Code_Feuille_Ligne"].'">'.$row10["Libelle_Ligne"].'</option>';} 
}
echo '</select></div>';





}

 echo '</div>';
}

if($Nb_Formulaire_0>=1)
{
    echo '<div class="row">
<div class="col-sm-2 col-md-2 mb-2">';
echo '<input type="text" readonly value="Formulaire '.($Dernier_Formulaire+1).'" class="form-control" style="background-color:lightgreen"> ';

echo '<input type="hidden" name="Formulaires[]" value="'."".'"></div>';

echo '<div class="col-sm-10 col-md-10 mb-10">
<select class="selectpicker bts_select form-control" multiple data-live-search="true" name="Select_Form['.($Dernier_Formulaire+1).'][]">';

foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne INNER JOIN t_feuille ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE (t_feuille_ligne.Code_Feuille='.$f." AND Formulaire=0) ORDER BY Formulaire ") as $row10) 
{
    
echo '<option value="'.$row10["Code_Feuille_Ligne"].'">'.$row10["Libelle_Ligne"].'</option>';
}
echo '</select></div>


';

}

echo '<br><br>
<div class="row"><div class="col-sm-2 col-md-2 mb-2"></div>
<div class="row"><div class="col-sm-10 col-md-10 mb-10">
<button style="width: 150px" class="btn '.$Boutton_Style.'" id="submit_btn">Valider</button>
</div>';

} } ?>

</form>
  </div>

<script type="text/javascript">    
  document.getElementById('submit_btn').addEventListener('click',function(e){
  e.preventDefault();
$.ajax({url:"traitement_jquery/disposition_mobile.php", method:"POST", data:$('#form_disposition_mobile').serialize(), success:function (data) {
    if(data=='')
{var pathArray = window.location.pathname.split('/');var Url = pathArray.length>2?pathArray[pathArray.length-1]:pathArray[1];//var Url=window.location.pathname.substring(1);
    <?php echo 'window.location.href=(Url+"?c="+"'.$_GET['c'].'"+"&f="+"'.$_GET['f'].'");'; ?>
}
        else {}}});

    });
  
</script>

        </div>
    </div>
    <?php //require_once "./theme_components/footer.php"; ?>
</div>

<?php
require_once'requires/formulaire_modification.php';
require_once'requires/formulaire_insertion.php';
?>

</body>
</html>