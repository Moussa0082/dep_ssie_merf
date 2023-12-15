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

$dir = './map/leaflet.shapefile/';

if (isset($_GET["id_sup"])) {
  $id = $_GET["id_sup"];
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."t_zone WHERE id_zone=%s",
                       GetSQLValueString($id, "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
 // if($Result1 && file_exists('./map/leaflet.shapefile/zone_'.$id.'.shp'))
 // unlink('./map/leaflet.shapefile/zone_collecte/zone_'.$id.'.shp');
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $personnel=$_SESSION['clp_id'];
  
    if ((isset($_FILES['couche']['name']))) {

    $ext_autorisees=array('zip'); //Extensions autoris&eacute;es

    $Result1 = false; $link = "";

    $ext = substr(strrchr($_FILES['couche']['name'], "."), 1);

    if(in_array($ext,$ext_autorisees))

    {

      $Result1 = move_uploaded_file($_FILES['couche']['tmp_name'],

      $dir.$_FILES['couche']['name']);

      if($Result1) $fichier = $_FILES['couche']['name'];

      //if($Result1) mysql_query_ruche("DOC".$dir.$link, $pdar_connexion,1);

    } } else $fichier="";
	
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."t_zone (nom_zone, titre, couleur, coord_gps, shapefile) VALUES (%s, %s, %s, %s, %s)",
                        GetSQLValueString($_POST['reference'], "text"),
						GetSQLValueString($_POST['titre'], "text"),
						GetSQLValueString($_POST['couleur'], "text"),
						GetSQLValueString($_POST['coord_gps'], "text"),
					    GetSQLValueString($fichier, "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
    if($Result1) $insertGoTo = $page."?insert=ok";
    else $insertGoTo = $page."&insert=no";
    //$insertGoTo .= (isset($_POST['categorie']))?"&categorie=".$_POST['categorie']:"";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
      $id = $_POST["MM_delete"];
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."t_zone WHERE id_zone=%s",
                           GetSQLValueString($id, "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
        $insertGoTo = $_SERVER['PHP_SELF'];
     // if($Result1 && file_exists('./map/shapefiles/zone_collecte/zone_'.$id.'.shp'))
     // unlink('./map/shapefiles/zone_collecte/zone_'.$id.'.shp');
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];  $id = ($_POST["MM_update"]);
    $couche_name = "";
  if ((isset($_FILES['couche']['name']))) {

    $ext_autorisees=array('zip'); //Extensions autoris&eacute;es

    $ext = substr(strrchr($_FILES['couche']['name'], "."), 1);

    if(in_array($ext,$ext_autorisees))

    {

      $Result1 = move_uploaded_file($_FILES['couche']['tmp_name'],

      $dir.$_FILES['couche']['name']);

      if($Result1) $couche_name = $_FILES['couche']['name'];

     // if($Result1) mysql_query_ruche("DOC".$dir.$link, $pdar_connexion,1);      

    }

  }
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."t_zone SET nom_zone=%s, titre=%s, couleur=%s, coord_gps=%s".(($Result1)?", shapefile=".GetSQLValueString($couche_name, "text"):"")." WHERE id_zone=%s",
                        GetSQLValueString($_POST['reference'], "text"),
						GetSQLValueString($_POST['titre'], "text"),
						GetSQLValueString($_POST['couleur'], "text"),
						GetSQLValueString($_POST['coord_gps'], "text"),
                        GetSQLValueString($id, "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
    if($Result1) $insertGoTo = $page."?update=ok";
    else $insertGoTo = $page."&update=no";
  //$insertGoTo .= (isset($_POST['categorie']))?"&categorie=".$_POST['categorie']:"";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

$personnel=$_SESSION['clp_id'];

$query_liste_poste = "SELECT * FROM ".$database_connect_prefix."t_zone order by nom_zone ";
try{
    $liste_zone = $pdar_connexion->prepare($query_liste_poste);
    $liste_zone->execute();
    $row_liste_zone = $liste_zone ->fetchAll();
    $totalRows_liste_poste = $liste_zone->rowCount();
	}catch(Exception $e){ die(mysql_error_show_message($e)); }
/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_type = "SELECT * FROM ".$database_connect_prefix."type_zone where  id_personnel='$personnel'";
$liste_type = mysql_query_ruche($query_liste_type, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_type = mysql_fetch_assoc($liste_type);
$totalRows_liste_type = mysql_num_rows($liste_type);
$liste_type_array = array();
if($totalRows_liste_type>0){  do{
  $liste_type_array[$row_liste_type["id_type"]]=$row_liste_type["definition"];
   }while($row_liste_type = mysql_fetch_assoc($liste_type));
}*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print $config->sitename; ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->
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
<?php //include_once("modal_add.php"); ?>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
.menu_head {
  padding: 5px; cursor: pointer; background-color: #060; color: #FFF;
}
</style>
<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Liste des zones territoriales de collecte  </h4>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<3){ ?>
<?php
//echo do_link("","","Importation dépuis un format excel","<i class=\"icon-plus\"> Importer </i>","","./","pull-right p11","get_content('import.php','id=partenaire','modal-body_add',this.title);",1,"",$nfile);

echo do_link("","","Ajout de zones de collecte","<i class=\"icon-plus\"> Nouvelle zone </i>","","./","pull-right p11","get_content('new_zone_collecte.php','','modal-body_add',this.title);",1,"",$nfile);
//echo do_link("","","Ajout de type de zones","<i class=\"icon-plus\"> Nouveau type </i>","","./","pull-right p11","get_content('new_type_zone.php','','modal-body_add',this.title);",1,"",$nfile);

?>
<?php } ?>
</div>

<div class="widget-content">
<table class="table table-striped table-bordered table-hover table-responsive  table-colvis datatable dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">N&deg;</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Nom ou r&eacute;f&eacute;rence </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Titre</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Couleur</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Coordonnées</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Couches</th>
<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Catégorie </th>-->
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="80">Actions</th>
<?php } ?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php if($totalRows_liste_poste>0) { $i=0; foreach($row_liste_zone as $row_liste_zone){  $i++; $id = $row_liste_zone['id_zone']; $code = $row_liste_zone['id_zone']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo $i; ?></td>
<td class=" "><?php echo $row_liste_zone['nom_zone']; ?></td>
<td class=" "><?php echo $row_liste_zone['titre']; ?></td>
<td class=" "><div class="progress-bar progress-bar-info" style="width: 100%;background-color: <?php echo $row_liste_zone['couleur']; ?>;height: 20px;"><?php echo $row_liste_ugl['couleur']; ?></div></td>
<td class=" "><?php echo $row_liste_zone['coord_gps']; ?></td>
<td class=" "><?php echo (!empty($row_liste_zone['shapefile']) && file_exists('./map/leaflet.shapefile/'.$row_liste_zone['shapefile']))?"<a title='Télécharger le fichier SHP' href='./map/leaflet.shapefile/".$row_liste_zone['shapefile']."'>".$row_liste_zone['nom_zone']."</a>":" - "; ?></td>   
<!--<td class=" "><?php //echo $row_liste_zone['categorie']; ?></td>-->
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<3){
  //if(in_array($_SESSION["clp_structure"],explode("|",$row_liste_zone['structure']))){ ?>
<td class=" " align="center">
<?php
echo do_link("","","Modifier zone de collecte ".$row_liste_zone['nom_zone'],"","edit","./","","get_content('new_zone_collecte.php','id=$code','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$code,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette zone ".$row_liste_zone['nom_zone']."');",0,"margin:0px 5px;",$nfile);
?></td>
<?php } ?>
</tr>
<?php } } ?>
</tbody></table>
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