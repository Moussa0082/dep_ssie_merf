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
  $insertSQL = sprintf("DELETE FROM indicateur_sygri1_projet WHERE id_indicateur_sygri_niveau1_projet=%s",
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

  $insertSQL = sprintf("INSERT INTO  indicateur_sygri1_projet (projet, id_sygri, scomposante,cible_projet, cible_rmp, ordre, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, '$personnel', '$date')",

                       GetSQLValueString($_SESSION["clp_projet"], "text"),
					   GetSQLValueString($_POST['id_sygri'], "text"),
					 //  GetSQLValueString($_POST['referentiel'], "int"),
                       GetSQLValueString($_POST['scomposante'], "text"),
					 //  GetSQLValueString($_POST['groupe_indicateur'], "int"),
					   GetSQLValueString($_POST['cible_projet'], "double"),
                       GetSQLValueString($_POST['cible_rmp'], "double"),
					   GetSQLValueString($_POST['ordre'], "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo));  exit();

  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE FROM indicateur_sygri1_projet WHERE id_indicateur_sygri_niveau1_projet=%s",
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
$insertSQL = sprintf("UPDATE indicateur_sygri1_projet SET id_sygri=%s, scomposante=%s, cible_projet=%s, cible_rmp=%s, ordre=%s,modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur_sygri_niveau1_projet=$id",

                       GetSQLValueString($_POST['id_sygri'], "text"),
                       GetSQLValueString($_POST['scomposante'], "text"),
                      // GetSQLValueString($_POST['referentiel'], "int"),
					  // GetSQLValueString($_POST['groupe_indicateur'], "int"),
					   GetSQLValueString($_POST['cible_projet'], "double"),
                       GetSQLValueString($_POST['cible_rmp'], "double"),
					   GetSQLValueString($_POST['ordre'], "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());

    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))

{   $id_ind=$_POST['id'];

  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

  $annee=$_POST['annee'];

  $valind=$_POST['valind'];

 // $id_region=$_POST['id_region'];


  //suppression



  mysql_select_db($database_pdar_connexion, $pdar_connexion);

  //$idzone=$id_zone[$key];

  $query_sup_cible_indicateur = "DELETE FROM ".$database_connect_prefix."cible_sygri_niveau1 WHERE indicateur_sygri='$id_ind' and projet='".$_SESSION["clp_projet"]."'";

  $Result1 = mysql_query_ruche($query_sup_cible_indicateur, $pdar_connexion) or die(mysql_error());



 //echo "je suis la";

  // `indicateur` int(11) NOT NULL,   `mois` int(11) DEFAULT NULL,  `cible` float DEFAULT '0',

  foreach ($annee as $key => $value)

  {

  	if(isset($valind[$key]) && $valind[$key]!=NULL) {

  	if(trim(strtolower($valind[$key]=="oui"))) $valind[$key] = "0";

  elseif(trim(strtolower($valind[$key]=="non"))) $valind[$key] = "1";



    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."cible_sygri_niveau1  (indicateur_sygri, projet, annee, valeur_cible, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, '$personnel', '$date')",

  					   GetSQLValueString($id_ind, "text"), 

  					    GetSQLValueString($_SESSION["clp_projet"], "text"),

  					   GetSQLValueString($annee[$key], "text"),

  					   GetSQLValueString($valind[$key], "double"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);

    $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error());

      }

    }

    /*$insertGoTo = $_SERVER['PHP_SELF'];

      if ($Result1) $insertGoTo .= "?insert=ok&id_ind=$id_ind"; else $insertGoTo .= "?insert=no&id_ind=$id_ind";

    header(sprintf("Location: %s", $insertGoTo)); 
*/ 

}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind FROM referentiel_indicateur";
$liste_ind_ref  = mysql_query($query_liste_ind_ref , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref);
$totalRows_liste_ind_ref  = mysql_num_rows($liste_ind_ref);
$liste_ind_ref_array = array();
$unite_ind_ref_array = array();
do{  $liste_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]] = $row_liste_ind_ref["intitule_ref_ind"]; $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];
}while($row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref));

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_activite = "SELECT id,code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=2 and projet='".$_SESSION["clp_projet"]."' order by code asc";
$liste_activite  = mysql_query_ruche($query_liste_activite, $pdar_connexion) or die(mysql_error());
$row_liste_activite  = mysql_fetch_assoc($liste_activite);
$totalRows_liste_activite  = mysql_num_rows($liste_activite);
$scomposante = array();
if($totalRows_liste_activite>0){
  do{
    $scomposante[$row_liste_activite["code"]] = $row_liste_activite["code"].": ".substr($row_liste_activite["intitule"],0,15)."...";
  }while($row_liste_activite  = mysql_fetch_assoc($liste_activite));
}
//$rows = mysql_num_rows($liste_activite);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_groupe_indicateur = "SELECT * FROM ".$database_connect_prefix."groupe_indicateur order by code_groupe asc";
$liste_groupe_indicateur  = mysql_query_ruche($query_liste_groupe_indicateur, $pdar_connexion) or die(mysql_error());
$row_liste_groupe_indicateur  = mysql_fetch_assoc($liste_groupe_indicateur);
$totalRows_liste_groupe_indicateur  = mysql_num_rows($liste_groupe_indicateur);
$sgroupe = array();
if($totalRows_liste_groupe_indicateur>0){
  do{
    $sgroupe[$row_liste_groupe_indicateur["id_groupe"]] = $row_liste_groupe_indicateur["nom_groupe"];
  }while($row_liste_groupe_indicateur  = mysql_fetch_assoc($liste_groupe_indicateur));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_ind = "SELECT * FROM liste_indicateur_sygri, indicateur_sygri1_projet where indicateur_sygri1_projet.projet='".$_SESSION["clp_projet"]."' and id_indicateur_sygri_fida=id_sygri order by scomposante, indicateur_sygri1_projet.ordre";
$ind  = mysql_query($query_ind , $pdar_connexion) or die(mysql_error());
$row_ind  = mysql_fetch_assoc($ind);
$totalRows_ind  = mysql_num_rows($ind);

//include_once 'modal_add.php';
//exit;
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cible_indicateur = "SELECT indicateur_sygri, sum(valeur_cible) as valeur_cible, avg(valeur_cible) as valeur_ciblem  FROM   ".$database_connect_prefix."cible_sygri_niveau1 where projet='".$_SESSION["clp_projet"]."' group by indicateur_sygri";
$cible_indicateur  = mysql_query_ruche($query_cible_indicateur , $pdar_connexion) or die(mysql_error());
$row_cible_indicateur = mysql_fetch_assoc($cible_indicateur );
$totalRows_cible_indicateur = mysql_num_rows($cible_indicateur );
$cible_array =$ciblem_array = array();
if($totalRows_cible_indicateur>0){ 
 do{
  $cible_array[$row_cible_indicateur["indicateur_sygri"]]=$row_cible_indicateur["valeur_cible"];
  $ciblem_array[$row_cible_indicateur["indicateur_sygri"]]=$row_cible_indicateur["valeur_ciblem"];
   } while($row_cible_indicateur  = mysql_fetch_assoc($cible_indicateur));}
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
<div class="widget-header"> <h4><i class="icon-reorder"></i> Indicateurs SYGRI 1<sup>er</sup> Niveau</h4>
<?php if (isset ($_SESSION["clp_id"])) {?>
<a onclick="get_content('new_sygri_niveau1_projet.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Ajout d'indicateur" class="btn btn-sm btn-warning pull-right p11" dir=""><i class="icon-plus"> Nouvel indicateur </i></a>
<a href="sygri_niveau3_projet.php" title="Editer les SYGRI Niveau 3" class="pull-right p11"><b><i> SYGRI 3<sup>&egrave;me</sup> Niveau </i> </b></a>
<!--<a href="soutien_sygri_niveau2_projet.php" title="Editer les indicateurs de soutien du 2ème Niveau SYGRI" class="pull-right p11"> Indicateurs de soutien </a>-->
<a href="sygri_niveau2_projet.php" title="Editer le 2&egrave;me Niveau SYGRI" class="pull-right p11"> <b><i>SYGRI 2<sup>&egrave;me</sup> Niveau</i> </b></a>
<?php include_once 'modal_add.php'; ?>
<?php } ?>
</div>
<div class="widget-content">

<table class="table table-striped table-bordered table-hover table-responsive table-checkable table-tabletools table-colvis datatable dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Sous composante</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><div align="center">N&deg;</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Indicateur 1<sup>er</sup> Niveau SYGRI </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Unit&eacute;</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Cible DCP </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Cible RMP</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Cibles annuelles </th>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="60">Edit</th>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="60">Suppr.</th>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">

<?php if($totalRows_ind>0) { ?>
<?php $i=0; do { $id=$row_ind['id_indicateur_sygri_niveau1_projet']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td nowrap="nowrap" class=" "><?php if(isset($scomposante[$row_ind["scomposante"]])) echo $scomposante[$row_ind["scomposante"]]; elseif(isset($sgroupe[$row_ind["groupe_indicateur"]])) echo $sgroupe[$row_ind["groupe_indicateur"]];  ?></td>
<td class=" "><?php echo $row_ind['ordre']; ?></td>
<td class=" "  <?php if(!isset($unite_ind_ref_array[$row_ind["referentiel"]])) {?>style="color:#FF0000"<?php } ?>><?php echo $row_ind['intitule_indicateur_sygri_fida']; ?></td>
<td class=" "><?php if(isset($unite_ind_ref_array[$row_ind["referentiel"]])) echo " (".$unite_ind_ref_array[$row_ind["referentiel"]].")"; ?></td>
<td class=" "><?php echo $row_ind['cible_projet']; ?></td>
<td class=" "><?php echo $row_ind['cible_rmp']; ?></td>
 <?php
				if(isset($unite_ind_ref_array[$row_ind["referentiel"]]) && isset($cible_array[$id]) && ($unite_ind_ref_array[$row_ind["referentiel"]])!="%")
				 $vcible=number_format($cible_array[$id], 0, ',', ' ');
				 elseif(isset($unite_ind_ref_array[$row_ind["referentiel"]]) && isset($ciblem_array[$id]) && ($unite_ind_ref_array[$row_ind["referentiel"]])=="%")
				  $vcible=number_format($ciblem_array[$id], 0, ',', ' ');
				  else  $vcible="Détail";

				  ?>
<td class=" "><a onclick="get_content('./edit_cible_sygri.php','<?php echo "id_ind=".$id."&code_act=".$row_ind['scomposante']; ?>','modal-body_add',this.title,'');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="'<?php echo str_replace("'","\'",$row_ind['intitule_indicateur_sygri_fida']);?>'" class="thickbox" dir=""><?php echo $vcible; ?></a></td>
<td class=" " align="center"><a onclick="get_content('new_sygri_niveau1_projet.php','id=<?php echo $id; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modification d'indicateur" class="thickbox Add"  dir=""><img src="images/edit.png" width='20' height='20' alt='Modifier' /></a></td>
<td class=" " align="center"><a href="<?php echo $_SERVER['PHP_SELF']."?id_sup_ind=".$id; ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet Indicateur ?');" /><img src="images/delete.png" width="15" border="0"/></a></td>
</tr>
<?php } while ($row_ind = mysql_fetch_assoc($ind));  mysql_free_result($ind);?>
<?php }  ?>
</tbody></table>

</div> </div>
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