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
$("#mbreadcrumb").html(<?php $link = ""; if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) $link .= '<div class="btn-circle-zone">'.do_link("btn_rapport","#myModal","Enregistrer ce rapport","<span title='Enregistrer ce rapport' class='glyphicon glyphicon-ok'></span>","simple","./","btn btn-success btn-circle mgr-5","",1,"",$nfile);
$link .= '</div>';
echo GetSQLValueString($link, "text"); } ?>);
</script>

<div style="border:1px solid silver;background: #FFF;padding: 10px;">
<form id="form_rapport_dynamique" action="traitement_jquery\inserer_rapport_simple.php" method="POST">

   <div class="row" style="">
      <div class="col-md-8 col-lg-8">
        <label style="float: left;">Nom du rapport</label>
         <input type="text" name="nom_rapport" id="nom_rapport" class="form-control" placeholder="Nom du rapport">
      </div> 
      <div class="col-md-4 col-lg-4"><br>
         <button style="width: 150px" type="button" id="Boutton_Ajouter_Critere" disabled <?php echo 'class="btn '.$Boutton_Style.'"'; ?>>Ajouter un critère</button>
      </div>
   </div><br>
   <div class="row" style="">
      <div class="col-md-8 col-lg-8">
         <div class="row">
            <div class="col-md-6 col-lg-6">
                <label style="float: left;">Classeur 1</label>
                    <select class="form-control" name="select_classeur" id="select_classeur">
      <option value=""></option>
      <?php foreach (FC_Rechercher_Code("SELECT * FROM t_classeur WHERE Id_Projet='".$_SESSION['clp_projet']."'") as $row3) 
      {echo '<option value="'.$row3["Code_Classeur"].'">'.$row3["Libelle_Classeur"].'</option>';
        
      } ?>
    </select>
            </div> 
            <div class="col-md-6 col-lg-6">
                <label style="float: left;">Classeur 2</label>
      <select class="form-control" name="select_classeur2" id="select_classeur2">
      <option value=""></option>
      <?php foreach (FC_Rechercher_Code("SELECT * FROM t_classeur WHERE Id_Projet='".$_SESSION['clp_projet']."'") as $row3) 
      {echo '<option value="'.$row3["Code_Classeur"].'">'.$row3["Libelle_Classeur"].'</option>';
        
      } ?>
    </select>
            </div>
            <div class="col-md-6 col-lg-6">
                <label style="float: left;">Feuille 1</label>
                <select class="form-control" name="select_feuille" id="select_feuille">
      
    </select>
            </div> 
            <div class="col-md-6 col-lg-6">
                <label style="float: left;">Feuille 2</label>
                    <select id="feuille_jointure" name="feuille_jointure" class="form-control" disabled></select> 
            </div> 
            <div class="col-md-6 col-lg-6">
                <label style="float: left;">Colonne à lier</label>
                <select name="attribut_jointure_fp" id="attribut_jointure_fp"  class="form-control" required disabled>
    </select>
            </div> 
            <div class="col-md-6 col-lg-6">
                <label style="float: left;">Colonne à lier</label>
                <select name="attribut_jointure_fs" id="attribut_jointure_fs"  class="form-control" required disabled>
    </select>
            </div>  
         </div> 
      </div>
      <div class="col-md-4 col-lg-4">
        <label style="float: left;">Reprouper par</label>
         <select name="input_regrouper_par" id="input_regrouper_par" class="form-control">
    </select>
         <label style="float: left;"> Colonne de valeur</label>
         <select name="input_valeur" id="input_valeur" class="form-control">
    </select>
         <label style="float: left;">Opération</label>
         <select id="operation" name="operation" class="form-control">
      <option value=""></option>
      <option value="COUNT">COMPTER</option>
      <option value="SUM">SOMME</option>
      <option value="AVG">MOYENNE</option>
    </select>
      </div>
   </div>

<br>
<div id="div_criteres" style="background-color:beige; margin:5px; border-radius: 5px; border:1px solid red">
<div class="row"><div class="col-lg-11" align="center"><h2>Critères</h2></div></div></div>

<br><br>
<section id="Champs">



</section>


</form>
</div>  

</center>

<script type="text/javascript">
var CHAMPS_FP_FS="";
var COLS_FP="";
var COLS_FS="";

document.getElementById("select_classeur").addEventListener("change", function(e){

document.getElementById("operation").value="";

VIDER_DIV_CRITERE();

$("#input_regrouper_par").html("");
$("#input_valeur").html("");
  document.getElementById("select_classeur2").value="";
  document.getElementById("feuille_jointure").disabled=true; document.getElementById("feuille_jointure").value="";
  document.getElementById("attribut_jointure_fp").disabled=true; document.getElementById("attribut_jointure_fp").value="";
  document.getElementById("attribut_jointure_fs").disabled=true; document.getElementById("attribut_jointure_fs").value="";

  if(document.getElementById("select_classeur").value!="")
    {$.ajax({url:"traitement_jquery/liste_feuille_par_classeur.php", method:"POST", data:$('#form_rapport_dynamique').serialize(), success:function (data) {
    if(data!='')
    {$("#select_feuille").html(data);}
else 
{}}});
 }
  else {$("#select_feuille").html(""); }

});



document.getElementById("select_classeur2").addEventListener("change", function(e){


  document.getElementById("feuille_jointure").value="";
  document.getElementById("attribut_jointure_fp").value="";
  document.getElementById("attribut_jointure_fs").value="";

  if(document.getElementById("select_classeur2").value!="")
    {$.ajax({url:"traitement_jquery/liste_feuille_par_classeur_2.php", method:"POST", data:$('#form_rapport_dynamique').serialize(), success:function (data) {
    if(data!='')
    {$("#feuille_jointure").html(data);}
else 
{}}});
 }
  else {$("#feuille_jointure").html(""); }

});


document.getElementById("select_feuille").addEventListener("change", function(e){
VIDER_DIV_CRITERE();
document.getElementById("input_regrouper_par").value="";
document.getElementById("input_valeur").value="";
document.getElementById("operation").value="";
document.getElementById("attribut_jointure_fs").value="";
document.getElementById("attribut_jointure_fp").value="";
document.getElementById("feuille_jointure").value="";
  if(document.getElementById("select_feuille").value!="")
    {$.ajax({url:"traitement_jquery/liste_colonnes_par_feuille.php", method:"POST", data:$('#form_rapport_dynamique').serialize(), success:function (data) {
    if(data!='')
    {
    	COLS_FP = data;

     $("#input_regrouper_par").html(COLS_FP + COLS_FS.replace('<option value=""></option>','')); 
     $("#input_valeur").html(COLS_FP + COLS_FS.replace('<option value=""></option>',''));
     $("#attribut_jointure_fp").html(COLS_FP);  

  document.getElementById("feuille_jointure").disabled=false;



$.ajax({url:"traitement_jquery/liste_champ_critere_par_feuille.php", method:"POST", data:$('#form_rapport_dynamique').serialize(), success:function (data) {
    if(data!=''){CHAMPS_FP_FS=data;} else {}}});


}
else 
{}}});
  document.getElementById("Boutton_Ajouter_Critere").disabled=false;
 }
  else {$("#input_regrouper_par").html(""); $("#input_valeur").html(""); $("#attribut_jointure_fp").html();
document.getElementById("feuille_jointure").disabled=true; document.getElementById("feuille_jointure").value="";
document.getElementById("Boutton_Ajouter_Critere").disabled=true;
}
});


document.getElementById("feuille_jointure").addEventListener("change", function(e){
  VIDER_DIV_CRITERE();
 if(document.getElementById("feuille_jointure").value!="")
    {document.getElementById("attribut_jointure_fp").disabled=false; document.getElementById("attribut_jointure_fs").disabled=false;
$.ajax({url:"traitement_jquery/liste_colonnes_par_feuille_jointure.php", method:"POST", data:$('#form_rapport_dynamique').serialize(), success:function (data) {
    if(data!='')
    {

     COLS_FS = data;

     $("#input_regrouper_par").html(COLS_FP + COLS_FS.replace('<option value=""></option>','')); 
     $("#input_valeur").html(COLS_FP + COLS_FS.replace('<option value=""></option>',''));
     $("#attribut_jointure_fs").html(COLS_FS);

     

  $.ajax({url:"traitement_jquery/liste_champ_critere_par_feuille.php", method:"POST", data:$('#form_rapport_dynamique').serialize(), success:function (data) {
    if(data!=''){CHAMPS_FP_FS=data;} else {}}});

}
else 
{}}});

}
else{document.getElementById("attribut_jointure_fp").disabled=true; document.getElementById("attribut_jointure_fp").value="";
     document.getElementById("attribut_jointure_fs").disabled=true; document.getElementById("attribut_jointure_fs").value="";
}
});

</script>



<script type="text/javascript">
var class_select_champ= document.getElementsByName("class_select_champ[]");
var champ_input= document.getElementsByName("champ_input[]");
var class_group_by= document.getElementsByClassName("class_group_by");
var class_colonne_valeur= document.getElementsByClassName("class_colonne_valeur");


</script>

<!-- Vendor scripts -->

<script type="text/javascript">
   $(document).ready(function(){
  $(document).on('click','#btn_rapport', function(){
  
if(document.getElementById("nom_rapport").value=="" || document.getElementById("select_feuille").value=="" || document.getElementById("input_regrouper_par").value=="" || document.getElementById("input_valeur").value=="" || document.getElementById("operation").value=="")
{alert("Veuillez renseigner tous les champs");}
else if(document.getElementById("feuille_jointure").value!="" && (document.getElementById("attribut_jointure_fp").value=="" || document.getElementById("attribut_jointure_fs").value=="")){alert("Veuillez renseigner tous les champs");}
else{
var champ_criteres=document.getElementById('champ_criteres[]');
var condition_criteres=document.getElementById('condition_criteres[]');
var valeur_criteres=document.getElementById('valeur_criteres[]');
/*for(i=0; i<champ_criteres.length; i++)
  {if(champ_criteres[i].value!="" && valeur_criteres[i].value==""){alert("Veuillez remplir le champ Valeur"); return ;}}*/

  $.ajax({url:"traitement_jquery/inserer_rapport_simple.php", method:"POST", data:$('#form_rapport_dynamique').serialize(), success:function (data) {  
    window.location.href="rapports_dynamiques.php";
  }});
  //$('#form_rapport_dynamique').submit();

}
      });

    });

var INDEX=0;

function Ajouter_Critere()
{INDEX++;
  $("#div_criteres").append('<div id="criteres_'+INDEX+'">'+'<div class="row"><div class="col-lg-2" align="center"><label></label></div><div class="col-lg-2" align="center"><label>ET / OU</label></div><div class="col-lg-2" align="center"><label>Champ</label></div><div class="col-lg-2" align="center"><label>Condition</label></div><div class="col-lg-2" align="center"><label>Valeur</label></div><div class="col-lg-2" align="center"><label></label></div></div><div class="row"><div class="col-lg-2" align="center"></div><div class="col-lg-2" align="center"><select  id="et_ou_criteres[]" name="et_ou_criteres[]" class="form-control"><option>ET</option><option>OU</option></select></div><div class="col-lg-2" align="center"><select  id="champ_criteres[]" name="champ_criteres[]" class="form-control">'+CHAMPS_FP_FS+'</select></div><div class="col-lg-2" align="center"><select  id="condition_criteres[]" name="condition_criteres[]" class="form-control"><option value="=">Egal (=)</option><option value=">">Supérieur (&gt;)</option><option value="<">Inférieur (&lt;)</option><option value=">=">Supérieur ou égal (&gt;=)</option><option value="<=">Inférieur ou égal (&lt;=)</option><option value="<>">Différent (!= / &lt;&gt;)</option><option value="%x%">Contenant (%x%)</option><option value="x%">Commençant par (x%)</option><option value="%x">Terminant par (%x)</option></select></div><div class="col-lg-2" align="center"><input type="text" name="valeur_criteres[]" id="valeur_criteres[]" placeholder="Valeur" class="form-control"></div><div class="col-lg-2" align="center"><a onclick="document.getElementById(\'criteres_'+INDEX+'\').innerHTML=\'\'"><span class="glyphicon glyphicon-remove text-danger"></span></a></div></div></div>');
}

document.getElementById("Boutton_Ajouter_Critere").addEventListener("click", Ajouter_Critere);

function VIDER_DIV_CRITERE(){$("#div_criteres").html('<div class="row"><div class="col-lg-11" align="center"><h2>Critères</h2></div></div>'); CHAMPS_FP_FS="";}
</script>


        </div>
    </div>
    <?php //require_once "./theme_components/footer.php"; ?>
</div>

</body>
</html>