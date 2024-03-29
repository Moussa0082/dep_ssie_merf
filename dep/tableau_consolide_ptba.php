<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";

extract($_GET); $statut = isset($statut)?$statut:0;


$query_liste_projet = "SELECT * FROM projet P WHERE actif=$statut  ORDER BY code_projet asc";

try{

$liste_projet = $pdar_connexion->prepare($query_liste_projet);

$liste_projet->execute();

$row_projet = $liste_projet ->fetchAll();

$totalRows_projet = $liste_projet->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); 

}


// nombre  activite                                             
$query_liste_act = "SELECT COUNT(code_activite_ptba) as act from ptba GROUP BY projet";

try{

$liste_act = $pdar_connexion->prepare($query_liste_act);

$liste_act->execute();

$row_projet_act = $liste_act ->fetchAll();

$totalRows_projet_act = $liste_act->rowCount();

}catch(Exception $e){
   die(mysql_error_show_message($e)); 

}


//requete pour recuperer la somme des budgets par projets
$query_liste_part = "SELECT count(bailleur) as nbbail, sum(montant) as partb, type_part.projet FROM ".$database_connect_prefix."partenaire, ".$database_connect_prefix."type_part WHERE code=bailleur  GROUP BY type_part.projet";
try{
    $liste_part = $pdar_connexion->prepare($query_liste_part);
    $liste_part->execute();
    $row_liste_part = $liste_part ->fetchAll();
    $totalRows_liste_part = $liste_part->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_liste_part>0){ foreach($row_liste_part as $row_liste_part){ 
$bailleur[$row_liste_part["projet"]]=$row_liste_part["partb"]; 
} 
} 




$query_liste_ind = "SELECT ptba.projet as projet,   COUNT(ptba.code_activite_ptba) as nb_act , COUNT(indicateur_tache.id_activite) as nb_ind FROM ptba,indicateur_tache
 WHERE ptba.id_ptba=indicateur_tache.id_activite GROUP by ptba.projet";
//Liste indicateur
try{
    $liste_ind = $pdar_connexion->prepare($query_liste_ind);
    $liste_ind->execute();
    $row_liste_ind = $liste_ind ->fetchAll();
    $totalRows_liste_ind = $liste_ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$indicateur=array();
if($totalRows_liste_ind>0){ foreach($row_liste_ind as $row_liste_ind){ 
  $indicateur[$row_liste_ind["projet"]]=$row_liste_ind["nb_ind"]; 
  // $act[$row_liste_ind["projet"]] = $row_liste_ind["nb_act"];
  
}
}

$query_nb_tache = "SELECT projet, COUNT(code_activite_ptba) as act from ptba GROUP BY projet" ;
//  Liste indicateur
try{
    $liste_nb_tache = $pdar_connexion->prepare($query_nb_tache);
    $liste_nb_tache->execute();
    $row_nb_tache = $liste_nb_tache ->fetchAll();
    $totalRows_nb_tache = $liste_nb_tache->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $act=array();
if($totalRows_nb_tache>0){ foreach($row_nb_tache as $row_nb_tache){ 
  $act[$row_nb_tache["projet"]]=$row_nb_tache["act"]; 
  // $act[$row_liste_ind["projet"]] = $row_liste_ind["nb_act"];
  
}
}


?>





<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

  <title><?php print $config->sitename;?></title>

  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone;?>" />

  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

  <meta name="keywords" content="<?php print $config->MetaKeys;?>" />

  <meta name="description" content="<?php print $config->MetaDesc;?>" />

  <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->

  <meta name="author" content="<?php print $config->MetaAuthor;?>" />

  <!--<meta charset="utf-8">-->

  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>



  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->

  <link href="<?php print $config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/plugins.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/responsive.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/icons.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/login.css" rel="stylesheet" type="text/css"/>

  <link rel="stylesheet" href="<?php print $config->theme_folder;?>/fontawesome/font-awesome.min.css">

  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->
theme_folder;?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->

  <!--[if IE 8]><link href="<?php print $config->theme_folder;?>/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->

  <link href='<?php print $config->theme_folder;?>/css.css' rel='stylesheet' type='text/css'>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/jquery-1.10.2.min.js"></script>

  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>

  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/lodash.compat.min.js"></script>

  <!--[if lt IE 9]><script src="<?php print $config->script_folder;?>/libs/html5shiv.js"></script><![endif]-->

  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>

  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>

  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>

  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/breakpoints.js"></script>

  <script type="text/javascript" src="plugins/respond/respond.min.js"></script>

  <script type="text/javascript" src="plugins/cookie/jquery.cookie.min.js"></script>

  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.min.js"></script>

  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>

  <!--[if lt IE 9]><script type="text/javascript" src="plugins/flot/excanvas.min.js"></script><![endif]-->

  <!--<script type="text/javascript" src="plugins/sparkline/jquery.sparkline.min.js"></script>

  <script type="text/javascript" src="plugins/flot/jquery.flot.min.js"></script>

  <script type="text/javascript" src="plugins/flot/jquery.flot.tooltip.min.js"></script>

  <script type="text/javascript" src="plugins/flot/jquery.flot.resize.min.js"></script>

  <script type="text/javascript" src="plugins/flot/jquery.flot.time.min.js"></script>

  <script type="text/javascript" src="plugins/flot/jquery.flot.growraf.min.js"></script>

  <script type="text/javascript" src="plugins/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

  <script type="text/javascript" src="plugins/daterangepicker/moment.min.js"></script>

  <script type="text/javascript" src="plugins/daterangepicker/daterangepicker.js"></script>-->

  <script type="text/javascript" src="plugins/blockui/jquery.blockUI.min.js"></script>

  <script type="text/javascript" src="plugins/pickadate/picker.js"></script>

  <script type="text/javascript" src="plugins/pickadate/picker.date.js"></script>

  <script type="text/javascript" src="plugins/pickadate/picker.time.js"></script>

  <script type="text/javascript" src="plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>

  <script type="text/javascript" src="plugins/fullcalendar/fullcalendar.min.js"></script>

  <script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>

  <script type="text/javascript" src="plugins/noty/layouts/top.js"></script>

  <script type="text/javascript" src="plugins/noty/themes/default.js"></script>

  <script type="text/javascript" src="plugins/uniform/jquery.uniform.min.js"></script>

  <script type="text/javascript" src="plugins/select2/select2.min.js"></script>

  <script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>

  <script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>

  <script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/app.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/plugins.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/plugins.form-components.js"></script>

<!--

  <script type="text/javascript" src="<?php print $config->script_folder;?>/custom.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/pages_calendar.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/charts/chart_filled_blue.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/charts/chart_simple.js"></script>-->

 <script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>

 <script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/login.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/ui_general.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/form_validation.js"></script>
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>

</head>

<body>

 <header class="header navbar navbar-fixed-top" role="banner">

    <?php include_once ("includes/header.php");?>

 </header>

<div id="container">

    <div id="sidebar" class="sidebar-fixed">

        <div id="sidebar-content">

            <?php include_once ("includes/menu_top.php");?>

        </div>

        <div id="divider" class="resizeable"></div>

    </div>



    <div id="content">

        <div class="container">

            <div class="crumbs">

                <?php include_once ("includes/sous_menu.php");?>

            </div>

        <div class="page-header">

            <div class="p_top_5">

<!-- Site contenu ici -->
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
</style>
<script>

</script>

<div class="widget box ">
<!--<div class="widget-header1"> <center><h4><?php if(!empty($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>-->
<div class="widget-header"> <h4><i class="icon-reorder"></i> Tableau synoptique de suivi des PTBA </h4>
<?php

?>
</div>

<div class="widget-content" style="display: block;">
<?php 

?>
<form name="form1" action="" method="post">

    
    
<table id="example" border="0" align="center" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive  table-colvis table-checkable datatable dataTable" >
                <thead>
                    <tr>
                    <!-- <th class="checkbox-column"> <input type="checkbox" class="uniform"> </th> -->
                    <td width="120"><strong>Titre du projet</strong></td>
                    <td width="120"><strong>Nombre d'activités</strong></td>
                    <td> <strong>Nombre d'indicateurs</strong> </td>
                    
                    <td width="80"><strong>Budget</strong></td>
                </tr>
            </thead>
            <tbody>
                    <?php $i=0; $row_projet_array = $row_projet_act; foreach($row_projet_act as $row_projet_act) { 
                       ?>
                    <?php $i=0; $row_projet_array = $row_projet; foreach($row_projet as $row_projet) { $id = $row_projet['code_projet']; ?>
                <tr>
                    <td> <?php echo $row_projet['sigle_projet'] ?> </td>
                    <td> <?php  if(isset($act[$id])) echo $act[$id]  ?></td>
                    <td>   <?php  if(isset($indicateur[$id])) echo $indicateur[$id]  ?>
 </td>

               <td class=" " align="center">
               <?php  if(isset($bailleur[$id])) echo "&nbsp;&nbsp;<span title=\"".number_format($bailleur[$id], 0, ',', ' ')." USD\">".number_format($bailleur[$id], 0, ',', ' ')."&nbsp;&nbsp;</span>";  else echo ""; ?>
                  </td>
                </tr>
                <?php } ?>
                <?php } ?>

              
                </tbody>
                <!-- <tr>
                  <td><div align="center" class=""><h2>Aucun R&eacute;sultat</h2></div></td>
                </tr> -->
            </table>

</form>


    </div>
</div>

<!-- Fin Site contenu ici -->

            </div>

        </div>



        </div>

    </div>  <?php include_once 'modal_add.php'; ?>

    <?php include_once ("includes/footer.php");?>

</div>

</body>

</html>