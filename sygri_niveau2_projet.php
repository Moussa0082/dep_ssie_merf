<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & DÃ©veloppement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";

if ((isset($_GET["id_sup_ind"]) && intval($_GET["id_sup_ind"])>0)) {
  $id = intval($_GET["id_sup_ind"]);
  $insertSQL = sprintf("DELETE FROM indicateur_sygri2_projet WHERE id_indicateur_sygri_niveau2_projet=%s",
                       GetSQLValueString($id, "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{ //personnel
    $date=date("Y-m-d");

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {

    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
    $indicateur_simple="";
if(!empty($_POST['indicateur_simple'])) { foreach($_POST['indicateur_simple'] as $vindicateur_simple) { $indicateur_simple=$indicateur_simple.",".$vindicateur_simple; } }

  $insertSQL = sprintf("INSERT INTO  indicateur_sygri2_projet (projet, id_sygri, sous_composante, indicateur_niveau1, code_ind_sygri2, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s,  %s,'$personnel', '$date')",

                       GetSQLValueString($_SESSION["clp_projet"], "text"),
					   GetSQLValueString($_POST['id_sygri'], "int"),
					   GetSQLValueString($_POST['sous_composante'], "text"),
                       //GetSQLValueString($_POST['sous_composante'], "text"),
                       GetSQLValueString($indicateur_simple, "text"),
					   GetSQLValueString($_POST['ordre'], "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo));  exit();

  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE FROM indicateur_sygri2_projet WHERE id_indicateur_sygri_niveau2_projet=%s",
                         GetSQLValueString($id, "int"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
    $indicateur_simple="";
if(!empty($_POST['indicateur_simple'])) { foreach($_POST['indicateur_simple'] as $vindicateur_simple) { $indicateur_simple=$indicateur_simple.",".$vindicateur_simple; } }

$insertSQL = sprintf("UPDATE indicateur_sygri2_projet SET id_sygri=%s, sous_composante=%s, indicateur_niveau1=%s, code_ind_sygri2=%s WHERE id_indicateur_sygri_niveau2_projet=$id",

                       GetSQLValueString($_POST['id_sygri'], "int"),
					   GetSQLValueString($_POST['sous_composante'], "text"),
                       GetSQLValueString($indicateur_simple, "text"),
                      // GetSQLValueString($_POST['referentiel'], "int"),
					   GetSQLValueString($_POST['ordre'], "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());

    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind FROM referentiel_indicateur where type_ref_ind!=3";
$liste_ind_ref  = mysql_query($query_liste_ind_ref , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref);
$totalRows_liste_ind_ref  = mysql_num_rows($liste_ind_ref);
$liste_ind_ref_array = array();
$unite_ind_ref_array = array();
do{  $liste_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]] = $row_liste_ind_ref["intitule_ref_ind"]; $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];
}while($row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref));


//query liste
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cindicateur = "select count(id_indicateur_soutien) as nb, indicateur_sygri_niveau2 FROM ".$database_connect_prefix."soutien_indicateur_sygri2 group by indicateur_sygri_niveau2  ORDER BY indicateur_sygri_niveau2 ASC";
$liste_cindicateur  = mysql_query($query_liste_cindicateur, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_cindicateur = mysql_fetch_assoc($liste_cindicateur); 
$totalRows_liste_cindicateur  = mysql_num_rows($liste_cindicateur);
$nb_soutien_array = array();
do{  $nb_soutien_array[$row_liste_cindicateur["indicateur_sygri_niveau2"]] = $row_liste_cindicateur["nb"];
}while($row_liste_cindicateur = mysql_fetch_assoc($liste_cindicateur));

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_activite = "SELECT id,code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=2 and projet='".$_SESSION["clp_projet"]."' order by code asc";
$liste_activite  = mysql_query_ruche($query_liste_activite, $pdar_connexion) or die(mysql_error());
$row_liste_activite  = mysql_fetch_assoc($liste_activite);
$totalRows_liste_activite  = mysql_num_rows($liste_activite);
$scomposante = array();
if($totalRows_liste_activite>0){
  do{
    $scomposante[$row_liste_activite["code"]] = $row_liste_activite["code"].": ".substr($row_liste_activite["intitule"],0,20)."...";
  }while($row_liste_activite  = mysql_fetch_assoc($liste_activite));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_ind = "SELECT * FROM liste_indicateur_sygri, indicateur_sygri2_projet where id_indicateur_sygri_fida=id_sygri and indicateur_sygri2_projet.projet='".$_SESSION["clp_projet"]."' order by sous_composante, code_ind_sygri2 asc";
$ind  = mysql_query($query_ind , $pdar_connexion) or die(mysql_error());
$row_ind  = mysql_fetch_assoc($ind);
$totalRows_ind  = mysql_num_rows($ind);
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
  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/login.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
  <link href='<?php print $config->theme_folder; ?>/css.css' rel='stylesheet' type='text/css'>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/lodash.compat.min.js"></script>
  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>
  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/breakpoints.js"></script>
  <script type="text/javascript" src="plugins/respond/respond.min.js"></script>
  <script type="text/javascript" src="plugins/cookie/jquery.cookie.min.js"></script>
  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.min.js"></script>
  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>
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
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }

</style>
<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box box_projet">
<div class="widget-header1"> <center><h4><?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Indicateurs SYGRI 2<sup>&egrave;me</sup> Niveau</h4>
<?php if (isset ($_SESSION["clp_id"])) {?>
<a onclick="get_content('new_sygri_niveau2_projet.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Ajout d'indicateur" class="btn btn-sm btn-warning pull-right p11" dir=""><i class="icon-plus"> Nouvel indicateur </i></a>
<a href="sygri_niveau3_projet.php" title="Editer les SYGRI niveau 3" class="pull-right p11"> <i> <b>SYGRI 3<sup>&egrave;me</sup> Niveau</b></i>  </a>
<!--<a href="soutien_sygri_niveau2_projet.php" title="Editer les indicateurs de soutien du 2ème Niveau SYGRI" class="pull-right p11"> Indicateurs de soutien </a>-->
<a href="sygri_niveau1_projet.php" title="Editer le 1er Niveau SYGRI" class="pull-right p11"> <i> <b>SYGRI 1<sup>er</sup> Niveau </b></i> </a>
<?php include_once 'modal_add.php'; ?>
<?php } ?>
</div>
<div class="widget-content">

<table class="table table-striped table-bordered table-hover table-responsive table-checkable table-tabletools table-colvis datatable dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Sous composante</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><div align="center">N&deg;</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Indicateur 2<sup>&egrave;me</sup> Niveau SYGRI </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Bar&egrave;me</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><div align="center">Indicateurs de soutien </div></th>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="60">Edit</th>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="60">Suppr.</th>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php if($totalRows_ind>0) { ?>
<?php $i=0; do { $id=$row_ind['id_indicateur_sygri_niveau2_projet']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php if(isset($scomposante[$row_ind["sous_composante"]])) echo $scomposante[$row_ind["sous_composante"]]; ?></td>
<td class=" "><div align="center"><?php echo $row_ind['code_ind_sygri2']; ?></div></td>
<td class=" "  <?php if(!isset($unite_ind_ref_array[$row_ind["referentiel"]])) {?>style="color:#FF0000"<?php } ?>><?php echo $row_ind['intitule_indicateur_sygri_fida']; ?></td>
<td class=" "><?php  echo " 1 - 6"; ?></td>
<td class=" "><div align="center"><a onclick="get_content('./plan_indicateur_soutien.php','<?php echo "id_ind=".$id."#os"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="'<?php echo str_replace("'","\'",$row_ind['intitule_indicateur_sygri_fida']);?>'" class="thickbox" dir=""><?php if(isset($nb_soutien_array[$id])) echo $nb_soutien_array[$id]." </br>(Indicateurs)"; else echo "Ajouter";?></a></div></td>
<td class=" " align="center"><a onclick="get_content('new_sygri_niveau2_projet.php','id=<?php echo $id; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modification d'indicateur" class="thickbox Add"  dir=""><img src="images/edit.png" width='20' height='20' alt='Modifier' /></a></td>
<td class=" " align="center"><a href="<?php echo $_SERVER['PHP_SELF']."?id_sup_ind=".$id; ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet Indicateur ?');" /><img src="images/delete.png" width="15" border="0"/></a></td>
</tr>
<?php } while ($row_ind = mysql_fetch_assoc($ind));  mysql_free_result($ind);?>
<?php } ?>
</tbody></table>

</div> </div>

<!-- Fin Site contenu ici -->
            </div>
			</div>
        </div>

        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>