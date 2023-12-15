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















$personnel = $_SESSION["clp_id"];







$date = date("Y-m-d");







//categorie de marches







if (isset($_GET["id_sup"])) {







  $id = ($_GET["id_sup"]);







  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."categorie_marche WHERE code_categorie=%s",







                       GetSQLValueString($id, "text"));















  mysql_select_db($database_pdar_connexion, $pdar_connexion);







  $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));







  $insertGoTo = $_SERVER['PHP_SELF'];







  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";







  header(sprintf("Location: %s", $insertGoTo)); exit();







}















if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))







{ //Fonction







  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {







  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."categorie_marche (nom_categorie, code_categorie, id_personnel, date_enregistrement) VALUES (%s, %s,'$personnel', '$date')",







						  GetSQLValueString($_POST['nom_categorie'], "text"),







					   GetSQLValueString($_POST['code_categorie'], "text"));















  mysql_select_db($database_pdar_connexion, $pdar_connexion);







  $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));







  $insertGoTo = $_SERVER['PHP_SELF'];







  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";







  header(sprintf("Location: %s", $insertGoTo));







  }















  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {







    $id = ($_POST["MM_delete"]);















//$query_sup_categorie = "DELETE FROM ".$database_connect_prefix."categorie_marche WHERE code_categorie='$id'";















 $insertSQL = sprintf("DELETE from ".$database_connect_prefix."categorie_marche WHERE code_categorie=%s",







                         GetSQLValueString($id, "text"));







    mysql_select_db($database_pdar_connexion, $pdar_connexion);







    $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));







    $insertGoTo = $_SERVER['PHP_SELF'];







    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";







    header(sprintf("Location: %s", $insertGoTo)); exit();







  }















  if (isset($_POST["MM_update"]) && !empty($_POST["MM_update"])) {







    $id = ($_POST["MM_update"]);















  	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."categorie_marche SET nom_categorie=%s, code_categorie=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE code_categorie='$id'",







					   GetSQLValueString($_POST['nom_categorie'], "text"),







					   GetSQLValueString($_POST['code_categorie'], "text"));

    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];


  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";







  header(sprintf("Location: %s", $insertGoTo));







  }







}





//Modele de passation de marches

if (isset($_GET["id_supsm"])) {
  $id = ($_GET["id_supsm"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."type_activite WHERE id_type=%s",
                       GetSQLValueString($id, "text"));
      try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form11"))

{ //Fonction
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
//if(intval($_POST['montant_min'])>intval($_POST['montant_max']) && intval($_POST['montant_max'])>0) {$max=$_POST['montant_min']; $min=$_POST['montant_max'];} else {$max=$_POST['montant_max']; $min=$_POST['montant_min'];}
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."type_activite (type_activite, categorie, id_personnel, date_enregistrement) VALUES (%s, %s, '$personnel', '$date')",
						   GetSQLValueString($_POST['type_activite'], "text"),
						  GetSQLValueString($_POST['categorie'], "text")//,
					     //  GetSQLValueString(implode(', ',$_POST['methode']), "text"),
						  // GetSQLValueString($_POST['description_type'], "text")
						   );
      try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo));
  }

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."type_activite WHERE id_type=%s",
                         GetSQLValueString($id, "text"));
	    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if (isset($_POST["MM_update"]) && !empty($_POST["MM_update"])) {
    $id = ($_POST["MM_update"]);
//if(intval($_POST['montant_min'])>intval($_POST['montant_max']) && intval($_POST['montant_max'])>0) {$max=$_POST['montant_min']; $min=$_POST['montant_max'];} else {$max=$_POST['montant_max']; $min=$_POST['montant_min'];}
  	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."type_activite SET type_activite=%s, categorie=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_type='$id'",
					       GetSQLValueString($_POST['type_activite'], "text"),
						   GetSQLValueString($_POST['categorie'], "text")//,
					      // GetSQLValueString(implode(', ',$_POST['methode']), "text"),
						  // GetSQLValueString($_POST['description_type'], "text")
						   );
      try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
	
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo));
  }
}

//insertion version

$query_liste_modele = "SELECT * FROM ".$database_connect_prefix."type_activite ORDER BY categorie asc";
try{
    $liste_modele = $pdar_connexion->prepare($query_liste_modele);
    $liste_modele->execute();
    $row_liste_modele = $liste_modele ->fetchAll();
    $totalRows_liste_modele = $liste_modele->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_etape = "SELECT sum(proportion) as netape, count(id_groupe_tache) as ntache, type_activite FROM type_tache  group by type_activite";
try{
    $liste_etape = $pdar_connexion->prepare($query_liste_etape);
    $liste_etape->execute();
    $row_liste_etape = $liste_etape ->fetchAll();
    $totalRows_liste_etape = $liste_etape->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$nb_etape_array = $nb_tache_array =array();
if($totalRows_liste_etape>0){ foreach($row_liste_etape as $row_liste_etape){
 $nb_etape_array[$row_liste_etape["type_activite"]]=$row_liste_etape["netape"];
  $nb_tache_array[$row_liste_etape["type_activite"]]=$row_liste_etape["ntache"];
} }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <title><?php print $config->sitename; ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="keywords" content="<?php print $config->MetaKeys; ?>" />







  <meta name="description" content="<?php print $config->MetaDesc; ?>" />







  <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->







  <meta name="author" content="<?php print $config->MetaAuthor; ?>" />







  <!--<meta charset="utf-8">-->







  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>















  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>







  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->







  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>







  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>







  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>







  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>







  <link href="<?php print $config->theme_folder; ?>/login.css" rel="stylesheet" type="text/css"/>







  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">







  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->
theme_folder; ?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->







  <!--[if IE 8]><link href="<?php print $config->theme_folder; ?>/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->







  <link href='<?php print $config->theme_folder; ?>/css.css' rel='stylesheet' type='text/css'>







  <!--<link rel="stylesheet" href="<?php print $config->theme_folder; ?>/table.css" type="text/css" > -->







  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>







  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>







  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>







  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/lodash.compat.min.js"></script>







  <!--[if lt IE 9]><script src="<?php print $config->script_folder; ?>/libs/html5shiv.js"></script><![endif]-->







  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>







  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>







  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>







  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>







  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/breakpoints.js"></script>







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







  <script type="text/javascript" src="<?php print $config->script_folder; ?>/app.js"></script>







  <script type="text/javascript" src="<?php print $config->script_folder; ?>/plugins.js"></script>







  <script type="text/javascript" src="<?php print $config->script_folder; ?>/plugins.form-components.js"></script>







<!--







  <script type="text/javascript" src="<?php print $config->script_folder; ?>/custom.js"></script>







  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/pages_calendar.js"></script>







  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_filled_blue.js"></script>







  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_simple.js"></script>-->







 <script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>







 <script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>







 <script type="text/javascript" src="<?php print $config->script_folder; ?>/login.js"></script>







 <script type="text/javascript" src="<?php print $config->script_folder; ?>/myscript.js"></script>







 <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/ui_general.js"></script>







 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>







</head>







<body>
 <header class="header navbar navbar-fixed-top" role="banner">
    <?php include_once("includes/header.php"); ?>
 </header>
<div id="container">
    <div id="sidebar" class="sidebar-fixed">

        <div id="sidebar-content">

            <?php include_once("includes/menu_top.php"); ?>

        </div>

        <div id="divider" class="resizeable"></div>

    </div>
     <div id="content">
        <div class="container">
            <div class="crumbs">
                <?php include_once("includes/sous_menu.php"); ?>
            </div>
        <div class="page-header">
            <div class="p_top_5">
<!-- Site contenu ici -->
<?php include_once 'modal_add.php'; ?>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
</style>
<div class="widget box">

<div style="padding-top:20px;">
<div class="col-md-12">
<div class="widget box ">
<div class="widget-header">
 <h4><i class="icon-reorder"></i> T&acirc;ches par types d'activit&eacute;s </h4>
 <div class="toolbar no-padding"><div class="btn-group"><span class="btn btn-xs widget-collapse pull-right"><i class="icon-angle-down"></i></span>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
echo do_link("","","Type d'actvit&eacute;s","<i class=\"icon-plus\"> Ajouter un type </i>","","./","pull-right p11","get_content('new_type_activite.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div></div>
</div>
<div class="widget-content">
<table class="table table-striped table-bordered table-hover table-responsive table-colvis datatable dataTable" id="mtable" aria-describedby="DataTables_Table_0_info">
      <thead>
        <tr>
          <td><div align="left"><strong>Code</strong></div></td>
          <td><div align="left"><strong>Type d'activit&eacute;  </strong></div></td>
         <!-- <td><div align="left"><strong>Description </strong></div></td>-->
          <td><strong>T&acirc;ches</strong></td>
          <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
          <td align="center" width="80" ><strong>Actions</strong></td>
          <?php } ?>
        </tr>
      </thead>
      <?php if($totalRows_liste_modele>0){ $i=0; foreach($row_liste_modele as $row_liste_modele){$id = $row_liste_modele['id_type']; 

	   ?>
      <tr>
    <td><div align="left"><?php echo $row_liste_modele['categorie'];  ?></div></td>
        <td ><?php echo $row_liste_modele['type_activite']; ?></td>
       <!-- <td ><?php echo $row_liste_modele['description_type']; ?></td>-->
        <td nowrap="nowrap" ><div align="center"><a onclick="get_content('./liste_tache_par_activite.php','<?php echo "id_type=".$row_liste_modele['id_type']?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="T&acirc;ches par par type d'activit&eacute;s" class="thickbox" dir="">
          <?php if(isset($nb_tache_array[$row_liste_modele["id_type"]])) echo "(".$nb_tache_array[$row_liste_modele["id_type"]].") "; ?>
          T&acirc;ches <?php if(isset($nb_etape_array[$row_liste_modele["id_type"]])) echo "(".$nb_etape_array[$row_liste_modele["id_type"]]."%) "; ?></a></div></td>
        <?php if(isset($_SESSION['clp_id']) && ($_SESSION['clp_id']=="admin")) { ?>
        <td align="center" nowrap="nowrap"><?php
echo do_link("","","Modifier un type d'activit&eacute;","","edit","./","","get_content('new_type_activite.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);
echo do_link("",$_SERVER['PHP_SELF']."?id_supsm=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce type ?');",0,"margin:0px 5px;",$nfile);
?>          </td>
        <?php } ?>
      </tr>
      <?php } } ?>
    </table>
    </div>
</div></div>



















<div class="clear h0">&nbsp;</div>















  </div>







</div>















<!-- Fin Site contenu ici -->







            </div>







        </div>















        </div>







    </div>    <?php include_once 'modal_add.php'; ?>







    <?php include_once("includes/footer.php"); ?>







</div>







</body>







</html>