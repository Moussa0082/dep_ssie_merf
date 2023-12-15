<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & DÃ©veloppement: BAMASOFT */
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
<div style="padding-top:20px;">


<div id="cpanel_ac">
<div class="icon"><a href="fiche_ong.php">&nbsp;Identification des ONG de facilitation</a></div></div>
<div id="cpanel_ac">
<div class="icon"><a href="fiche_op.php">&nbsp;Fiche d'identification des groupements</a></div></div>
<div id="cpanel_ac">
<div class="icon"><a href="fiche_sensibilisation_jeune.php">&nbsp;Fiche des sessions de sensibilisation</a></div></div>
<div id="cpanel_ac">
<div class="icon"><a href="fiche_generale_formation.php">&nbsp;Fiche de collecte sur les formations</a></div></div>


<!--<div id="cpanel_ac">
<div class="icon"><a href="fiche_donnees_base_agriculture.php">Fiche données de base agriculture</a></div></div>
<div id="cpanel_ac">
<div class="icon"><a href="fiche_donnees_base_elevage.php">Fiche données de base élévage</a></div></div>
<div id="cpanel_ac">
<div class="icon"><a href="fiche_donnees_base_peche.php">Fiche données de base p&ecirc;che</a></div></div>
<div id="cpanel_ac">
<div class="icon"><a href="fiche_donnees_base_agr.php">Fiche données de base AGR</a></div></div>

<div id="cpanel_ac">
<div class="icon"><a href="fiche_donnees_base_partenariat.php">Fiche données sur les accords de financement</a></div></div>
<div id="cpanel_ac">
<div class="icon"><a href="fiche_exploitation_mp_social.php">Fiche données sur les microprojets de nature sociale</a></div></div>


<div class="col-md-6"><a href="fiche_suivi_himo.php">HIMO</a></div>
<div class="col-md-6"><a href="fiche_formation_profess_metiers.php">Formation professionnelle Métiers</a></div>
<div class="col-md-6"><a href="fiche_suivi_formation.php">Suivi des Formations</a></div>
<div class="col-md-6"><a href="fiche_suivi_aglc.php">Suivi AGLC</a></div>
<div class="col-md-6"><a href="fiche_suivi_union_op.php">Suivi des UNION OP</a></div>
<div class="col-md-6"><a href="fiche_suivi_ong.php">Suivi des ONG</a></div>
<div class="col-md-6"><a href="fiche_suivi_ccc.php">Suivi des CCC</a></div>
<div class="col-md-6"><a href="fiche_suivi_faie.php">Suivi des FAIE</a></div>
<div class="col-md-6"><a href="fiche_suivi_appui_conseil.php">Suivi des Appuis conseils</a></div>
<div class="col-md-6"><a href="fiche_suivi_bois_village.php">Suivi des Bois village</a></div>
<div class="col-md-6"><a href="fiche_suivi_perimetre_pastoraux.php">Suivi des périmètres  pastoraux</a></div>
<div class="col-md-6"><a href="fiche_suivi_kit_goutte.php">Suivi des Kits goutte</a></div>
<div class="col-md-6"><a href="fiche_suivi_visite_echange.php">Suivi des visites d'échange</a></div>
<div class="col-md-6"><a href="fiche_suivi_technique_conservation.php">Suivi des techniques de conservation</a></div>
<div class="col-md-6"><a href="fiche_suivi_technique_gestion.php">Suivi des techniques de gestion</a></div>
<div class="col-md-6"><a href="fiche_suivi_source_energie.php">Suivi des sources d'energie</a></div>
<div class="col-md-6"><a href="fiche_suivi_fertilisation_organique.php">Suivi des fertilisations organiques</a></div>
<div class="col-md-6"><a href="fiche_suivi_distribution_semence.php">Suivi des distributions semences</a></div>
<div class="col-md-6"><a href="fiche_suivi_bonne_pratique.php">Suivi des Bonnes pratiques</a></div>
<div class="col-md-6"><a href="fiche_suivi_moyen_subsistance.php">Suivi des moyens de subsistance</a></div>
<div class="col-md-6"><a href="fiche_suivi_production.php">Suivi des Productions</a></div>
<div class="col-md-6"><a href="fiche_suivi_echelle_piezometre.php">Suivi des Echelles Piezomètres</a></div>
<div class="col-md-6"><a href="fiche_suivi_fourrage.php">Suivi des Fourrages</a></div>
<div class="col-md-6"><a href="fiche_suivi_alphabetisation.php">Suivi des sessions d'alphabétisation</a></div>
<div class="col-md-6"><a href="fiche_suivi_iec.php">Suivi des sessions IEC</a></div>
<div class="col-md-6"><a href="fiche_suivi_fiec.php">Suivi des FIEC</a></div>
<div class="col-md-6"><a href="fiche_suivi_sensibilisation.php">Suivi des Sensibilisation CC</a></div>
<div class="col-md-6"><a href="fiche_suivi_animation.php">Suivi des Animation CCC</a></div>

<div class="col-md-6">&nbsp;&nbsp;</div>

<div class="col-md-6">&nbsp;&nbsp;</div>
<div class="col-md-6"><a href="etat_beneficiaire_globaux.php">Etat bénéficiaires globaux</a></div>
<div class="col-md-6"><a href="etat_beneficiaire_genre.php">Etat  b&eacute;n&eacute;ficiaires du projet en genre</a></div>
<div class="col-md-6"><a href="etat_site_intervention.php">Etat sites d'intervention</a></div>
<div class="col-md-6"><a href="etat_beneficiaire_genre_activite.php">Etat Bénéficiaires par types d'activités</a></div>


<div class="col-md-6">&nbsp;&nbsp;</div>

<div class="col-md-6">&nbsp;&nbsp;</div>
<div class="col-md-6"><?php
echo do_link("","","Resultat indicateur","REQUETE 24 :Evolution production","","./","pull-right p11","get_content('indicateur_S209_production.php','','modal-body_add',this.title,'iframe');",1,"",$nfile);?></div>

</div>-->

<script>

	$().ready(function() {

		// validate the comment form when it is submitted
		//$(".form-horizontal").validate();
        $(".select2-select-00").select2({allowClear:true});

	});

</script>
<!-- Fin Site contenu ici -->
</div>
            </div>

        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>