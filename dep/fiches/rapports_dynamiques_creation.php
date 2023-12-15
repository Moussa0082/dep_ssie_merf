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
    <?php require_once "./theme_components/main-menu.php"; ?>
<!-- Main Wrapper -->
<div id="wrapper">
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
<form id="form_rapport_dynamique" action="traitement_jquery\inserer_rapport.php" method="POST">
<div class="row">
  <div class="col-lg-1"></div>
  <div class="col-lg-3">
    <label>Nom du rapport</label>
  </div>
  <div class="col-lg-3">
    <label>Classeur</label>
  </div>
  <div class="col-lg-3">
    <label>Feuille</label>
  </div>
</div>

<div class="row">
  <div class="col-lg-1"></div>
  <div class="col-lg-3">
    <input type="text" name="nom_rapport" id="nom_rapport" class="form-control" placeholder="Nom du rapport">
  </div>
  <div class="col-lg-3">
    <select class="form-control" name="select_classeur" id="select_classeur">
      <option value=""></option>
      <?php foreach (FC_Rechercher_Code("SELECT * FROM t_classeur") as $row3) 
      {echo '<option value="'.$row3["Code_Classeur"].'">'.$row3["Libelle_Classeur"].'</option>';
        
      } ?>
    </select>
  </div>
  <div class="col-lg-3">
    <select class="form-control" name="select_feuille" id="select_feuille">
      
    </select>
  </div>
</div>
<br>
<div class="row">
  <div class="col-lg-2" align="center"><label>Regrouper par</label></div>

  <div class="col-lg-2" align="center"><label>Colonne de valeur</label></div>

  <div class="col-lg-2" align="center"><label>Opération</label></div>

  <div class="col-lg-2" align="center"><label>Autres colonnes à afficher</label></div>

  <div class="col-lg-2" align="center"><label>Critères à inclure</label></div>

  <div class="col-lg-2" align="center"><label>Critères à exclure</label></div>
</div>

<div class="row">
  <div class="col-lg-2" align="center"><section style="background: white;">
    <select name="input_regrouper_par" id="input_regrouper_par" class="form-control" onchange="Select_Champ_Event(this.value, 0)">
    </select>
  </section></div>

  <div class="col-lg-2" align="center"><section style="background: white;">
    <select name="input_valeur" id="input_valeur" class="form-control" onchange="Select_Champ_Event(this.value, 1)">
    </select>
  </section></div>

  <div class="col-lg-2" align="center"><section style="background: white;">
    <select id="operation" name="operation" class="form-control">
      <option value=""></option>
      <option value="COUNT">COMPTER</option>
      <option value="SUM">SOMME</option>
      <option value="AVG">MOYENNE</option>
    </select>
  </section></div>

  <div class="col-lg-2" align="center"><section style="width: 90%; background: white; height: 30px">
    <select id="" name="" class="form-control">

    </select> 
  </section></div>

  <div class="col-lg-2" align="center"><section style="width: 90%; background: white; height: 30px">
    <input type="text" name="" id=""  class="form-control" placeholder="Critères a inclure" readonly>
  </section></div>

  <div class="col-lg-2" align="center"><section style="width: 90%; background: white; height: 30px">
    <input type="text" name="" id=""  class="form-control" placeholder="Critères a exclure" readonly>
  </section></div>
</div>


<br><br>
<section id="Champs">



</section>


</form>
</div>  

</center>

<script type="text/javascript"> 
document.getElementById("select_classeur").addEventListener("change", function(e){

document.getElementById("operation").value="";
$("#input_regrouper_par").html("");
$("#input_valeur").html("");


  if(document.getElementById("select_classeur").value!="")
    {$.ajax({url:"traitement_jquery/liste_feuille_par_classeur.php", method:"POST", data:$('#form_rapport_dynamique').serialize(), success:function (data) {
    if(data!='')
    {$("#select_feuille").html(data);}
else 
{}}});
 }
  else {$("#select_feuille").html("");}
});


document.getElementById("select_feuille").addEventListener("change", function(e){
document.getElementById("input_regrouper_par").value="";
document.getElementById("input_valeur").value="";
document.getElementById("operation").value="";
  if(document.getElementById("select_feuille").value!="")
    {$.ajax({url:"traitement_jquery/liste_colonnes_par_feuille.php", method:"POST", data:$('#form_rapport_dynamique').serialize(), success:function (data) {
    if(data!='')
    {$("#input_regrouper_par").html(data); $("#input_valeur").html(data);

}
else 
{}}});
 }
  else {$("#input_regrouper_par").html(""); $("#input_valeur").html("");}
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
else{$.ajax({url:"traitement_jquery/inserer_rapport.php", method:"POST", data:$('#form_rapport_dynamique').serialize(), success:function (data) {
    window.location.href="rapports_dynamiques.php";}});

}
      });

    });
</script>


        </div>
    </div>
    <?php require_once "./theme_components/footer.php"; ?>
</div>

</body>
</html>