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
//$(document).ready(function(){$(".modal").off( "hidden.bs.modal", null );});
$("#mbreadcrumb").html(<?php $link = ""; if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) $link .= '<div class="btn-circle-zone">'/*.do_link("btn_rapport","./rapports_dynamiques_creation.php","Création de rapport","<span title='Créer un nouveau rapport' class='glyphicon glyphicon-plus'></span>","simple","./","btn btn-success btn-circle mgr-5","",1,"",$nfile)*/;
$link .= '<a class="dropdown-toggle label-menu-corner btn btn-success btn-circle mgr-5" href="#" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span></a>';
if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1)
$link .= '<ul class="dropdown-menu hdropdown animated flipInX"><li>'.do_link("","./rapports_dynamiques_simple_creation.php","Création de rapport","<span class='glyphicon glyphicon-stats text-info'></span><span class='text-default'>&nbsp;Rapport simple</span>","simple","./","","",1,"",$nfile).'</li><li>'.do_link("","./rapports_dynamiques_croise_creation.php","Création de rapport","<span class='glyphicon glyphicon-equalizer text-info'></span><span class='text-default'>&nbsp;Rapport croisé</span>","simple","./","","",1,"",$nfile).'</li><li>'.do_link("","./rapports_dynamiques_carto_creation.php","Création de rapport","<span class='glyphicon glyphicon-map-marker text-info'></span><span class='text-default'>&nbsp;Rapport carto</span>","simple","./","","",1,"",$nfile).'</li></ul>';
$link .= '</div>';
echo GetSQLValueString($link, "text"); } ?>);
</script>

<form id="Form_Perso">
    
</form>
<?php require_once 'requires/formulaire_insertion_123.php';  ?>
<script type="text/javascript">
    function Supprimer_Rapport(Code)
 { 
  if(confirm("Voulez-vous supprimer ce Rapport?")){$.ajax({url:"traitement_jquery/supprimer_rapport.php?Rapport="+Code, method:"POST", data:$('#Form_Perso').serialize(), success:function (data) {
    if(data!='')
    {window.location.href='rapports_dynamiques.php';}
    else {}}});}
  

 }

</script>
 <div class="content">

<?php
$ii=0;
$ui=0;
foreach (FC_Rechercher_Code("SELECT * FROM t_rapport WHERE Id_Projet='".$_SESSION['clp_projet']."' ORDER BY Code_Rapport DESC") as $row3)
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


                if($row3['Type_Rapport']=="SIMPLE"){echo '<a href="rapport_details_simple.php?r='.base64_encode($row3['Code_Rapport']).'" style="font-size:16px">'.$row3['Nom_Rapport'].'</a>';}
                else {echo '<a href="rapport_details_croise.php?r='.base64_encode($row3['Code_Rapport']).'" style="font-size:16px">'.$row3['Nom_Rapport'].'</a>';}

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

                if($row3['Type_Rapport']=="SIMPLE"){echo '<span class="glyphicon glyphicon-stats text-info"></span>';}
                else {echo '<span class="glyphicon glyphicon-equalizer text-info"></span>';}
                                        echo '</div></div>

                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-2 project-info">
                                <div class="project-action m-t-md"> 
                                <div class="btn-group" style="font-size: 18px">
                                        <button class="btn btn-xs btn-default" onclick="window.location.href=\'traitement_jquery/exporter_word.php?r='.$row3['Code_Rapport'].'\'" id="" title="Exporter"><span class="nav-label glyphicon glyphicon-export text-default" ></span></button>
                                    </div>
                                    <div class="btn-group" style="font-size: 18px">
                                        <button class="btn btn-xs btn-default" ';

                                        if($row3['Type_Rapport']=="SIMPLE"){echo 'onclick="window.location.href=\'rapports_dynamiques_simple_modification.php?r='.base64_encode($row3['Code_Rapport']).'\'"';}
                                        else {echo 'onclick="window.location.href=\'rapports_dynamiques_croise_modification.php?r='.base64_encode($row3['Code_Rapport']).'\'"';}

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



                    if($row3['Type_Rapport']=="SIMPLE")
                    {echo '<a href="rapport_details_simple.php?r='.base64_encode($row3['Code_Rapport']).'" >Ouvrir le rapport</a>';}
                else {echo '<a href="rapport_details_croise.php?r='.base64_encode($row3['Code_Rapport']).'" >Ouvrir le rapport</a>';}
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
    <?php //require_once "./theme_components/footer.php"; ?>
</div>

</body>
</html>