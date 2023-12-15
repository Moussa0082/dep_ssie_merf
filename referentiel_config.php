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

if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y");

if ((isset($_GET["id_sup"]) && !empty($_GET["id_sup"]))) {
  $id = ($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."indicateur_config WHERE id=%s",
                       GetSQLValueString($id, "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $personnel=$_SESSION['clp_id']; $date=date("Y-m-d");
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."indicateur_config (id_fiche, col, type, ind, mode_calcul, couleur, etat,  projet, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, '$personnel', '$date')",
	                   GetSQLValueString($_POST['feuille'], "text"),
                       GetSQLValueString($_POST['colonne'], "text"),
					   GetSQLValueString($_POST['type'], "text"),
                       GetSQLValueString($_POST['indicateur'], "text"),
                       GetSQLValueString($_POST['mode_calcul'], "text"),
                       GetSQLValueString($_POST['couleur'], "text"),
                       GetSQLValueString($_POST['etat'], "int"),
                       GetSQLValueString($_SESSION['clp_projet'], "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    $insertGoTo = $_SERVER['PHP_SELF'];
    if($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
      $id = intval($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."indicateur_config WHERE id=%s",
                           GetSQLValueString($id, "int"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];  $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."indicateur_config SET id_fiche=%s, col=%s, type=%s, ind=%s, mode_calcul=%s, couleur=%s, etat=%s, modifier_par='$personnel', modifier_le='$date' WHERE id=%s",
	                   GetSQLValueString($_POST['feuille'], "text"),
                       GetSQLValueString($_POST['colonne'], "text"),
					   GetSQLValueString($_POST['type'], "text"),
                       GetSQLValueString($_POST['indicateur'], "text"),
                       GetSQLValueString($_POST['mode_calcul'], "text"),
                       GetSQLValueString($_POST['couleur'], "text"),
                       GetSQLValueString($_POST['etat'], "int"),
                       GetSQLValueString($id, "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    $insertGoTo = $_SERVER['PHP_SELF'];
    if($Result1) $insertGoTo .= $page."?update=ok"; else $insertGoTo .= $page."?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_conf = "SELECT * FROM ".$database_connect_prefix."indicateur_config WHERE projet='".$_SESSION["clp_projet"]."'";
$liste_conf = mysql_query_ruche($query_liste_conf, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_conf = mysql_fetch_assoc($liste_conf);
$totalRows_liste_conf = mysql_num_rows($liste_conf);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_classeur = "SELECT * FROM ".$database_connect_prefix."classeur ";
$liste_classeur = mysql_query_ruche($query_liste_classeur, $pdar_connexion) or die(mysql_error());
$row_liste_classeur = mysql_fetch_assoc($liste_classeur);
$totalRows_liste_classeur = mysql_num_rows($liste_classeur);
$liste_classeur_array = $classeur_color_array = array();
if($totalRows_liste_classeur>0){  do{
$liste_classeur_array[$row_liste_classeur["id_classeur"]]=$row_liste_classeur["libelle"];
$classeur_color_array[$row_liste_classeur["id_classeur"]]=$row_liste_classeur["couleur"];
}while($row_liste_classeur  = mysql_fetch_assoc($liste_classeur));  }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config ";
$entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);
$entete_array = $nom_array = array();
if($totalRows_entete>0){ do{
  $entete_array[$row_entete["table"]]=$row_entete["nom"]; $libelle=explode("|",$row_entete["libelle"]);
  foreach($libelle as $llib1)
  {
    $lib=explode("=",$llib1);
    $libelle_array[$row_entete["table"]][$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";
  }
}while($row_entete  = mysql_fetch_assoc($entete)); }

  //cmr
  $cmr_array =array();
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_cmr = "SELECT * FROM ".$database_connect_prefix."referentiel_indicateur WHERE type_ref_ind=1 and mode_calcul='Unique' and projet='".$_SESSION["clp_projet"]."' order by intitule_ref_ind";
  //$query_cmr = "SELECT * FROM ".$database_connect_prefix."referentiel_indicateur, ".$database_connect_prefix."produit, ".$database_connect_prefix."indicateur_produit, ".$database_connect_prefix."sous_composante WHERE id_produit=produit and indicateur_prd=id_indicateur_produit and sous_composante=id_sous_composante order by code_sous_composante,code_produit";
  $cmr  = mysql_query_ruche($query_cmr , $pdar_connexion) or die(mysql_error());
  $row_cmr  = mysql_fetch_assoc($cmr);
  $totalRows_cmr  = mysql_num_rows($cmr);
  if($totalRows_cmr>0){ do{
  $cmr_array[$row_cmr["id_ref_ind"]]=$row_cmr["intitule_ref_ind"]." (".$row_cmr["unite"].")";  }while($row_cmr  = mysql_fetch_assoc($cmr)); }

  //sygri
  /*$sygri_array =array();
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_sygri = "SELECT * FROM ".$database_connect_prefix."indicateur_sygri1_projet,composante WHERE composante=id_composante order by code_composante,ordre";
  $sygri  = mysql_query_ruche($query_sygri , $pdar_connexion) or die(mysql_error());
  $row_sygri  = mysql_fetch_assoc($sygri);
  $totalRows_sygri  = mysql_num_rows($sygri);
  if($totalRows_sygri>0){ do{
  $sygri_array[$row_sygri["id_indicateur_sygri_niveau1_projet"]]=$row_sygri["indicateur_sygri_niveau1"]." (".$row_sygri["unite"].")";    }while($row_sygri  = mysql_fetch_assoc($sygri)); }  */

  //PTBA
  $appendice_array = $appendice_annee_array = array();
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_appendice4 = "SELECT intitule_indicateur_tache,code_activite_ptba,intitule_activite_ptba,id_indicateur_tache, ".$database_connect_prefix."indicateur_tache.annee FROM ".$database_connect_prefix."ptba, ".$database_connect_prefix."indicateur_tache  where code_activite_ptba=code_activite "/*and ".$database_connect_prefix."ptba.annee='$annee'*/." and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' ORDER BY code_activite_ptba,intitule_indicateur_tache";
  $appendice4  = mysql_query_ruche($query_appendice4 , $pdar_connexion) or die(mysql_error());
  $row_appendice4  = mysql_fetch_assoc($appendice4);
  $totalRows_appendice4  = mysql_num_rows($appendice4);
  if($totalRows_appendice4>0){ do{
  $appendice_array[$row_appendice4["id_indicateur_tache"]]=$row_appendice4["code_activite_ptba"].": ".$row_appendice4["intitule_indicateur_tache"]; $appendice_annee_array[$row_appendice4["id_indicateur_tache"]]=$row_appendice4["annee"];  }while($row_appendice4  = mysql_fetch_assoc($appendice4)); }

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
<?php include_once("modal_add.php"); ?>
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
<div class="widget box box_projet">
<div class="widget-header1"> <center><h4><?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>  
<div class="widget-header"> <h4><i class="icon-reorder"></i> Liaison fiche - indicateur</h4>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
echo do_link("","","Ajout d'&eacute;l&eacute;ment","<i class=\"icon-plus\"> Nouveau &eacute;l&eacute;ment </i>","","./","pull-right p11","get_content('new_referentiel_config.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div>

<div class="widget-content">
<table class="table table-striped table-bordered table-hover table-responsive datatable table-tabletools table-colvis dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
  <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Type</th>
  <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Indicateur</th>
  <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Classeur</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Feuille</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">El&eacute;ment</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Calcul</th>
<th width="100" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Couleur</th>
<th width="100" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Acceuil</th>
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) {?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="90">Actions</th>
<?php }?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php if($totalRows_liste_conf>0) { $i=0; do { $id = $row_liste_conf['id']; $tmp = explode('_',$row_liste_conf['id_fiche']); $classeur = intval($tmp[1]); $feuille = $row_liste_conf['id_fiche']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo $row_liste_conf['type'].' '.(($row_liste_conf['type']=="PTBA" && isset($appendice_annee_array[$row_liste_conf['ind']]))?"<b>".$appendice_annee_array[$row_liste_conf['ind']]."</b>":''); ?></td>
<td class=" "><?php echo ($row_liste_conf['type']=="CMR" && isset($cmr_array[$row_liste_conf['ind']]))?$cmr_array[$row_liste_conf['ind']]:(($row_liste_conf['type']=="PTBA" && isset($appendice_array[$row_liste_conf['ind']]))?$appendice_array[$row_liste_conf['ind']]:'NaN'); ?></td>
  <td class=" "><?php echo (isset($liste_classeur_array[$classeur]))?$liste_classeur_array[$classeur]:'NaN'; ?></td>
<td class=" "><?php echo (isset($entete_array[$feuille]))?$entete_array[$feuille]:'NaN'; ?></td>
<td class=" "><?php echo (isset($libelle_array[$feuille][$row_liste_conf['col']]))?$libelle_array[$feuille][$row_liste_conf['col']]:$row_liste_conf['col']; ?></td>
<td class=" "><?php echo (!empty($row_liste_conf['mode_calcul']))?$row_liste_conf['mode_calcul']:"SOMME"; ?></td>
<td align="center"><div align="center" class="progress-bar progress-bar-info" style="width: 100%;background-color: <?php echo !empty($row_liste_conf['couleur'])?$row_liste_conf['couleur']:(isset($classeur_color_array[$classeur])?$classeur_color_array[$classeur]:''); ?>;height: 20px;"><?php echo !empty($row_liste_conf['couleur'])?$row_liste_conf['couleur']:(isset($classeur_color_array[$classeur])?$classeur_color_array[$classeur]:''); ?></div></td>
<td class=" " align="center"><?php echo ($row_liste_conf['etat']==0)?'Non':'Oui'; ?></td>
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) {?>
<td class=" " align="center">
<?php
echo do_link("","","Modifier r&eacute;ference&eacute;ment ","","edit","./","","get_content('new_referentiel_config.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=$id","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce r&eacute;ference&eacute;ment ?');",0,"margin:0px 5px;",$nfile);
?>
</td>
<?php }?>
</tr>
<?php }while($row_liste_conf  = mysql_fetch_assoc($liste_conf)); } ?>
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