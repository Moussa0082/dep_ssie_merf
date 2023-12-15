<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}

include_once $config->sys_folder . "/database/db_connexion.php";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $acteur="";
  	if(!empty($_POST['acteur'])) { foreach($_POST['acteur'] as $vacteur) { $acteur=$acteur.",".$vacteur; } }
    $insertSQL = sprintf("INSERT INTO indicateur_objectif_specifique_cmr (indicateur_os, referentiel, intitule_indicateur_cmr_os, cible_cmr,annee_reference,reference_cmr, responsable_collecte, personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, '$personnel', '$date')",
  					   GetSQLValueString($_POST['indicateur_cl'], "int"),
                         GetSQLValueString((isset($_POST['referentiel'])?$_POST['referentiel']:0), "int"),
  	   				   GetSQLValueString($_POST['indicateur'], "text"),
     					   GetSQLValueString($_POST['cible_dp'], "text"),
                         GetSQLValueString($_POST['annee_reference'], "int"),
  					   GetSQLValueString($_POST['reference_cmr'], "text"),
  					   GetSQLValueString($acteur, "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));   exit(0);
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
      $id = intval($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from indicateur_objectif_specifique_cmr WHERE id_indicateur=%s",
                           GetSQLValueString($id, "int"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id']; $acteur="";
  	if(!empty($_POST['acteur'])) { foreach($_POST['acteur'] as $vacteur) { $acteur=$acteur.",".$vacteur; } }
    $insertSQL = sprintf("UPDATE indicateur_objectif_specifique_cmr SET  indicateur_os=%s, referentiel=%s, intitule_indicateur_cmr_os=%s, cible_cmr=%s, annee_reference=%s, reference_cmr=%s, responsable_collecte=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur='$c'",
  					   GetSQLValueString($_POST['indicateur_cl'], "int"),
                         GetSQLValueString((isset($_POST['referentiel'])?$_POST['referentiel']:0), "int"),
  	   				   GetSQLValueString($_POST['indicateur'], "text"),
  					   GetSQLValueString($_POST['cible_dp'], "text"),
                         GetSQLValueString($_POST['annee_reference'], "int"),
  					   GetSQLValueString($_POST['reference_cmr'], "text"),
     					   GetSQLValueString($acteur, "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));   exit(0);
  }

}



// query og
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_og = "SELECT * FROM objectif_specifique WHERE ".$_SESSION["clp_where"]."";
$og  = mysql_query($query_og , $pdar_connexion) or die(mysql_error());
$row_og  = mysql_fetch_assoc($og);
$totalRows_og  = mysql_num_rows($og);

// query indicateur
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_ind = "SELECT indicateur_objectif_specifique_cmr.*, indicateur_objectif_specifique.* FROM indicateur_objectif_specifique_cmr, indicateur_objectif_specifique,objectif_specifique WHERE ".$_SESSION["clp_where"]." and objectif_specifique=id_objectif_specifique and id_indicateur_objectif_specifique=indicateur_os  order by id_indicateur_objectif_specifique, code_cmr";
$ind  = mysql_query($query_ind , $pdar_connexion) or die(mysql_error());
$row_ind  = mysql_fetch_assoc($ind);
$totalRows_ind  = mysql_num_rows($ind);


if(isset($_GET["id_sup_indos"]) && $_SESSION['clp_niveau']==1) { $idios=$_GET["id_sup_indos"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sup_ind = "DELETE FROM indicateur_objectif_specifique_cmr WHERE id_indicateur='$idios'";
$Result1 = mysql_query($query_sup_ind, $pdar_connexion) or die(mysql_error());
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo));
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


include_once 'modal_add.php';

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

  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->theme_folder;?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->

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

 <script>$(document).ready(function(){Login.init()});</script>

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
<style>
.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
</style>
<div class="widget box box_projet">
<div class="widget-header1"> <center><h4><?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Indicateurs Objectif de d&eacute;v&eacute;loppement </h4>
<?php
$libelle = array("cmr_produit.php"=>"Indicateurs de produit","cmr_resultat.php"=>"Indicateurs d'effet","cmr_effet.php"=>"Indicateurs ODP","cmr_impact.php"=>"Indicateurs d'impact");
foreach($libelle as $key=>$lib){
  echo do_link("",$key,"$lib","<i> $lib </i>","","./","pull-right p11","",0,"",$nfile);
  $i--; }
?>
<!--<a href="cmr_produit.php" title="Indicateurs de produit" class="pull-right p11"><i class="icon-plus"> Indicateurs de produit </i></a>
<a href="cmr_resultat.php" title="Indicateurs d'effet" class="pull-right p11"><i class="icon-plus"> Indicateurs d'effet </i></a>
<a href="cmr_effet.php" title="Indicateurs ODP" class="pull-right p11"><i class="icon-plus"> Indicateurs ODP </i></a>
<a href="cmr_impact.php" title="Indicateurs d'impact" class="pull-right p11"><i class="icon-plus"> Indicateurs d'impact </i></a>-->
</div>
<div class="widget-content" style="display: block;">

<table width="100%" border="0" align="center" cellspacing="1" class="table table-striped table-bordered table-responsive">
                      <?php if($totalRows_og>0) {$i=0;do { ?>
                      <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; $i=$i+1;?>>
                        <td><div align="left"><span class="Style27"><strong>Objectif de D&eacute;veloppement: </strong></span> <span class="Style27"><?php echo $row_og['intitule_objectif_specifique']; ?></span></div><br />
                        <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
<?php
echo do_link("","","Ajout d'indicateur &agrave; l'OS","Ajouter un indicateur","","./","pull-right","get_content('edit_indicateur_oscmr.php','os=".$row_og['id_objectif_specifique']."','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile); ?>
<!--<a onclick="get_content('edit_indicateur_oscmr.php','os=<?php echo $row_og['id_objectif_specifique']; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Ajout d'indicateur &agrave; l'OS" class="thickbox Add"  dir=""><span style="background-color:#ebebeb">&nbsp;<span class="Style5">Ajouter un indicateur</span>&nbsp;</span></a> -->
                            <?php } ?></td>
                      </tr>
                      <tr>
                        <td><table border="0" cellspacing="1" width="100%" class="table table-striped table-bordered table-hover table-responsive">
                          <?php if($totalRows_ind>0) { ?>
                          <thead>
                          <tr>
                            <td rowspan="2" align="center" >Indicateur</td>
                            <td rowspan="2" align="center" >Unit&eacute;</td>
                            <td colspan="2" align="center" >R&eacute;f&eacute;rences </td>
                            <td rowspan="2" align="center" >Objectifs </td>
							<td colspan="2" align="center" >Collecte des donn&eacute;es et rapports </td>
                            <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
                            <td rowspan="2" align="center">Actions</td>
                            <?php }?>
                            </tr>
                          <tr>
                            <td align="center" >Ann&eacute;e</td>
					        <td align="center" >Situation</td>
					        <td align="center" >P&eacute;riodicit&eacute;</td>
                            <td align="center" >Responsable</td>
                          </tr>
                          </thead>
						   <?php $i=0; $p1="j"; do { $id = $row_ind['id_indicateur']; ?>
						   <?php  if($p1!=$row_ind['id_indicateur_objectif_specifique']) {?>
                          <tr bgcolor="#ECF000">
                            <td colspan="<?php  echo 10; ?>" align="center" bgcolor="#D2E2B1"><div align="left"><strong> <u>
                                <?php  if($p1!=$row_ind['id_indicateur_objectif_specifique']) {echo $row_ind['code_ios'].". ".$row_ind['intitule_indicateur_objectif_specifique']; $i=0; }$p1=$row_ind['id_indicateur_objectif_specifique']; ?>
                            </u> </strong></div></td>
                          </tr>
                          <?php } ?>
                          <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; else echo 'bgcolor="#FFFFFF"'; $i=$i+1;?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='<?php if($i%2!=0) echo '#ECF0DF';?>';">
                            <td ><div align="left"><span class="Style20">.&nbsp;</span><span class="Style22"><?php echo $row_ind['intitule_indicateur_cmr_os']; ?></span></div></td>
                            <td ><div align="center"><span class="Style22"><?php echo (isset($unite_ind_ref_array[$row_ind['referentiel']]))?$unite_ind_ref_array[$row_ind['referentiel']]:""; ?></span></div></td>
                            <td ><div align="center" class="Style22"><strong><?php echo $row_ind['annee_reference']; ?></strong></div></td>




							 <td ><div align="center"><span class="Style22"><?php echo $row_ind['reference_cmr']; ?></span></div></td>
							 <td><div align="center"><span class="Style22"><?php echo $row_ind['cible_cmr']; ?></span></div></td>
                            <td><span class="Style22">Au d&eacute;but, et &agrave; la fin&nbsp; </span><span class="Style22">&nbsp; </span></td>
                            <td><span class="Style22">
                              <?php
			$as = explode(",", $row_ind['responsable_collecte']); 	$lacteur=implode("','", $as);
			mysql_select_db($database_pdar_connexion, $pdar_connexion);
			$query_liste_acteur = "SELECT id_acteur, nom_acteur FROM acteur where id_acteur in ('$lacteur') ORDER BY categorie,code_acteur, nom_acteur";
			$liste_acteur   = mysql_query($query_liste_acteur , $pdar_connexion) or die(mysql_error());
			$row_liste_acteur   = mysql_fetch_assoc($liste_acteur );
			$totalRows_liste_acteur  = mysql_num_rows($liste_acteur );
           //affichage
		    if($totalRows_liste_acteur>0) { 	do {  echo $row_liste_acteur['nom_acteur']." - "; 	} while ($row_liste_acteur= mysql_fetch_assoc($liste_acteur)); mysql_free_result($liste_acteur);}
			else {echo "Aucun"; }
	  ?>
                            </span></td>
                            <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
<td align="center">
<?php
echo do_link("","","Modifier indicateur : ".$row_ind['intitule_indicateur_cmr_os'],"","edit","./","","get_content('edit_indicateur_oscmr.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup_indos=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cet indicateur ?');",0,"margin:0px 5px;",$nfile);
?>
<!--<a onclick="get_content('edit_indicateur_oscmr.php','id=<?php echo $row_ind['id_indicateur']; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><img src='images/edit.png' width='20' height='20' alt='Modifier'></a>
<a href="<?php echo $_SERVER['PHP_SELF']."?id_sup_indos=".$row_ind['id_indicateur'].""?>" onclick="return confirm('Voulez-vous vraiment supprimer cet Indicateur ?');" /><img src="images/delete.png" width="15"/></a>-->
</td>
                            <?php }?>
                          </tr>
                          <?php } while ($row_ind = mysql_fetch_assoc($ind)); //mysql_free_result($ind); ?>
                          <?php } ?>
                        </table></td>
                      </tr>
                      <?php } while ($row_og = mysql_fetch_assoc($og)); mysql_free_result($og); ?>
                      <?php } ?>
                    </table>
</div>

</div></div>

<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>

</body>
</html>