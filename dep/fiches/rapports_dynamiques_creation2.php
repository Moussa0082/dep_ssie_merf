<?php require_once 'fonctions/php/Fonctions.php'; 


      require_once 'requires/theme_style.php';
?>

<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <title>Rapports dynamiques</title>

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" type="image/ico" href="favicon.ico" />


    <!-- Vendor styles -->
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.css" />
    <link rel="stylesheet" href="vendor/metisMenu/dist/metisMenu.css" />
    <link rel="stylesheet" href="vendor/animate.css/animate.css" />
    <link rel="stylesheet" href="vendor/bootstrap/dist/css/bootstrap.css" />

    <!-- App styles -->
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/helper.css" />
    <link rel="stylesheet" href="styles/style.css">


</head>
<body>

<!-- Simple splash screen-->
<div class="splash"> <div class="color-line"></div><div class="splash-title">
    <h1>Homer - Responsive Admin Theme</h1>
    <p>Special AngularJS Admin Theme for small and medium webapp with very clean and aesthetic style and feel. </p>
    <div class="spinner"> <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div> </div> </div> </div>
<!--[if lt IE 7]>
<p class="alert alert-danger">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<?php require_once 'requires/header.php'; ?>
<?php require_once 'requires/nav.php'; ?>


<!-- Main Wrapper -->
<div id="wrapper">
<center>
  <div class="content animate-panel">
    <div style="margin-top: 0px"></div>
</div>
<div class="small-header bar_header">
    <div class="hpanel">
        <div class="panel-body">
            <a class="small-header-action" href="">
                <div class="clip-header">
                    <i class="fa fa-arrow-down"></i>
                </div>
            </a>
             <div class="col-sm-1 col-md-1 col-lg-1 btn_ajouter" align="left" style="">
      <button <?php echo 'class="btn '.$Boutton_Style.'"'; ?>  type="button" data-toggle="modal" data-target="#myModal" id="btn_rapport" on><span class="glyphicon glyphicon-ok"></span> </button>
    </div>
            <div id="mbreadcrumb" class="pull-left">&nbsp;</div>

            <div id="hbreadcrumb" class="pull-right">

                <ol class="hbreadcrumb breadcrumb">
                    <li><a href="menu.php">Accueil</a></li>
                    <li>
                        <span>Etat et Rapports</span>
                    </li>
                    <li class="active">
                        <span>Rapports dynamiques</span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Rapports dynamiques
            </h2>
            <small>Création de rapport</small>
        </div>
    </div>
</div>

<script src="vendor/jquery/dist/jquery.min.js"></script>
<script src="vendor/slimScroll/jquery.slimscroll.min.js"></script>
<script src="vendor/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="vendor/metisMenu/dist/metisMenu.min.js"></script>
<script src="vendor/jquery-ui/jquery-ui.min.js"></script>

<!-- App scripts -->
<script src="scripts/homer.js"></script>



<div style="border:1px solid silver">
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
    <input type="text" name="input_regrouper_par" id="input_regrouper_par" class="form-control" readonly placeholder="Regrouper par">
  </section></div>

  <div class="col-lg-2" align="center"><section style="background: white;">
    <input type="text" name="input_valeur" id="input_valeur" class="form-control" readonly placeholder="Colonne de valeur">
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
document.getElementById("input_regrouper_par").value="";
document.getElementById("input_valeur").value="";
document.getElementById("operation").value="";
$("#Champs").html("");

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
    {$("#Champs").html(data);

}
else 
{}}});
 }
  else {$("#Champs").html("");}
});

</script>



<script type="text/javascript">
var class_select_champ= document.getElementsByName("class_select_champ[]");
var champ_input= document.getElementsByName("champ_input[]");
var class_group_by= document.getElementsByClassName("class_group_by");
var class_colonne_valeur= document.getElementsByClassName("class_colonne_valeur");


//alert(champ_input[0].value);



//alert(class_select_champ[i].value);
function Select_Champ_Event(ind)
{
    switch(class_select_champ[ind].value)
    { case "" : 
      if(document.getElementById("input_regrouper_par").value==champ_input[ind].value)
      {document.getElementById("input_regrouper_par").value="";
      for(i=0; i<class_select_champ.length; i++)
      {document.getElementById("class_group_by_"+i).disabled=false; }

      }
      else if(document.getElementById("input_valeur").value==champ_input[ind].value)
      {document.getElementById("input_valeur").value="";
      for(i=0; i<class_select_champ.length; i++)
      {document.getElementById("class_colonne_valeur_"+i).disabled=false; }
  }
      break;

      case "group_by" : 
      document.getElementById("input_regrouper_par").value=champ_input[ind].value;
      for(i=0; i<class_select_champ.length; i++)
      {if(i!=ind){ document.getElementById("class_group_by_"+i).disabled=true; }}
       break;

      case "valeur" : 
      document.getElementById("input_valeur").value=champ_input[ind].value;
      for(i=0; i<class_select_champ.length; i++)
      {if(i!=ind){ document.getElementById("class_colonne_valeur_"+i).disabled=true; }}
       break;

      default : 
      

      ; break
    }
}


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


<?php require_once 'requires/sidebar_droit.php'; 
require_once'requires/formulaire_modification.php';
require_once'requires/formulaire_insertion.php';
?>

<?php require_once 'requires/footer.php'; ?>

</div>

</body>
</html>