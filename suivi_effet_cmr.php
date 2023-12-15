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

if(!isset($_SESSION["reg"])) {$_SESSION["reg"]="*";}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//if(isset($_GET['reg'])) {$_SESSION["reg"]=$_GET['reg']; $reg=$_SESSION["reg"];} else {$reg=$_SESSION["reg"];}

//insertion cible indicateur objectif specifique CMR
if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

//suppression
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$an=$_POST['annee'];
$idcos=$_POST['ind'];
$vcible=$_POST['valeur_reelle'];

/*$query_sup_set = "DELETE FROM cible_indos_cmr WHERE indicateur_oscmr='$idcos'";
$Result1 = mysql_query($query_sup_set, $pdar_connexion) or die(mysql_error());*/
//fin suppression
foreach ($an as $key => $value)
{
	if(isset($vcible[$key])) {
	
	  $insertSQL = sprintf("UPDATE cible_indos_cmr SET  valeur_reelle=%s, etat='suivi', modifier_par='$personnel', modifier_le='$date' WHERE annee=%s and indicateur_oscmr=%s" ,
					   GetSQLValueString($vcible[$key], "double"),
					   GetSQLValueString($an[$key], "int"),
   					   GetSQLValueString($idcos, "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  }
  }
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no"; 
  header(sprintf("Location: %s", $insertGoTo));
}
}
// Partie objectif specifique
// objectif specifique
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_os = "SELECT * FROM objectif_specifique WHERE ".$_SESSION["clp_where"]." order by id_objectif_specifique";
$os  = mysql_query($query_os , $pdar_connexion) or die(mysql_error());
$row_os  = mysql_fetch_assoc($os);
$totalRows_os  = mysql_num_rows($os);

// Partie resultat
//composante

//annee
$annee_courante=date("Y");
$tableauAnnee=array();
for($i=$_SESSION["annee_debut_projet"];$i<=$_SESSION["annee_fin_projet"];$i++) $tableauAnnee[]=$i;
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
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; } td {vertical-align: middle!important; }
</style>
<div class="widget box box_projet">
<div class="widget-header1"> <center><h4><?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>
<div class="widget-header"> <h4><i class="icon-reorder"></i> Indicateurs ODP </h4>
    <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){?>
<?php
$libelle = array("suivi_produit.php"=>"Indicateurs de produit","suivi_cmr_resultat.php"=>"Indicateurs d'effet","suivi_effet_cmr.php"=>"Indicateurs ODP","suivi_impact.php"=>"Indicateurs d'impact");
foreach($libelle as $key=>$lib){
  echo do_link("",$key,"$lib","<i> $lib </i>","","./","pull-right p11","",0,"",$nfile);
  $i--; }
?>
<!--<a href="suivi_produit.php" title="Suivi des indicateurs de produit" class="pull-right p11"><i class="icon-plus"> Indicateurs de produit </i></a>
<a href="suivi_cmr_resultat.php" title="Suivi des indicateurs d'effet" class="pull-right p11"><i class="icon-plus"> Indicateurs d'effet </i></a>
<a href="suivi_effet_cmr.php" title="Suivi des indicateurs ODP" class="pull-right p11"><i class="icon-plus"> Indicateurs ODP </i></a>
<a href="suivi_impact.php" title="Suivi des indicateurs d'impact" class="pull-right p11"><i class="icon-plus"> Indicateurs d'impact </i></a>-->
    <?php } ?>
</div>

<div class="widget-content" style="display: block;">
              <table width="100%" border="0" cellspacing="1">
               <tr bgcolor="#579D43">
                  <td bgcolor="#579D43"><div align="left">
                      <p class="Style17 Style24" style="color: white;">AVANCEMENT TECHNIQUE <span class="Style25">DES EFFETS</span> DU CADRE DE MESURE DE RENDEMENT </p>
                      <span class="Style1"><u></u></span></div></td>
                </tr>
                <?php if($totalRows_os>0) {$o=0;do { ?>
                <tr <?php if($o%2==0) echo 'bgcolor="#ECF0DF"'; $o=$o+1;?>>
                  <td valign="top"><div align="left"><strong>OS : </strong> <?php echo $row_os['intitule_objectif_specifique']; ?> </div></td>
                  </tr>
                <tr >
                  <td valign="top"><table border="0" cellspacing="1" width="100%" class="table table-striped table-bordered table-hover table-responsive">
                    <?php 
				    $id_os=$row_os['id_objectif_specifique'];
				    mysql_select_db($database_pdar_connexion, $pdar_connexion);
					$query_ind = "SELECT * FROM indicateur_objectif_specifique where objectif_specifique='$id_os'";
					$ind  = mysql_query($query_ind , $pdar_connexion) or die(mysql_error());
					$row_ind  = mysql_fetch_assoc($ind);
					$totalRows_ind  = mysql_num_rows($ind);				  
				  ?>
                    <?php if($totalRows_ind>0){ ?>
                   <thead>
                    <tr>
                      <td rowspan="2" align="center">Indicateurs</td>
                      <td colspan="2" align="center">Donn&eacute;es du DP</td>
                      <td align="center" colspan="<?php echo count($tableauAnnee); ?>">Valeurs r&eacute;elles</td>
                      <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
                      <td rowspan="2" align="center">Actions</td>
                      <?php }?>
                      </tr>
                    <tr>
                      <td align="center">R&eacute;f&eacute;rence</td>
					  <td align="center">Cible</td>
					  <?php foreach($tableauAnnee as $anp){?>
                      <td align="center"><div align="center"><?php echo $anp; ?></div></td>
                    <?php } ?>
                    </tr>
                  </thead>
					 <?php $i=0;do { ?>
                    <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; $i=$i+1;?>>
                      <td><div align="left" class="Style22"><span class="Style17">.&nbsp;</span><?php echo $row_ind['intitule_indicateur_objectif_specifique']."(".$row_ind['unite'].")"; ?></div></td>
                      <td><div align="center" class="Style21"><?php echo $row_ind['reference']; ?></div></td>
                      <td><div align="center"><span class="Style21"><?php echo $row_ind['cible_dp']; ?></span></div></td>
					<form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3">
					 <?php foreach($tableauAnnee as $anp){?>
					  <?php 
					    $idioscmr=$row_ind['id_indicateur_objectif_specifique'];
					 // $pan=$row_liste_anneeos2['annee'];
					    mysql_select_db($database_pdar_connexion, $pdar_connexion);
						$query_vaos = "SELECT valeur_cible, valeur_reelle FROM cible_indos_cmr where annee='$anp' and indicateur_oscmr='$idioscmr'";
						$vaos = mysql_query($query_vaos, $pdar_connexion) or die(mysql_error());
						$row_vaos = mysql_fetch_assoc($vaos);
						$totalRows_vaos = mysql_num_rows($vaos);
					  ?>
					  
                      <td>
                <table align="center">
                  <tr valign="baseline">
                    <td><input type="text" name="valeur_reelle[]" <?php if(!isset($row_vaos['valeur_reelle'])) {?>style="border-color:#FF0000; text-align:center"<?php }?> value="<?php if(isset($row_vaos['valeur_reelle'])) echo $row_vaos['valeur_reelle']; else echo "0"; ?>" style="text-align:center" size="8" class="form-control" /></td>
                  </tr>
                </table>
                <input type="hidden" name="annee[]" value="<?php echo $anp; ?>" />
                <input type="hidden" name="ind" value="<?php echo $row_ind['id_indicateur_objectif_specifique']; ?>" />
                      </td>
                    <?php } ?>
					 <td align="center"><div align="right">
          <input type="submit"  name="Submit" value=">>" style="background-color:#FF9900; font-weight:bold" />
          <input type="hidden" name="<?php  echo "MM_insert";  ?>" value="form3" />
        </div></td> </form>
                      </tr>
                    <?php } while ($row_ind = mysql_fetch_assoc($ind)); ?>
                    <?php } ?>
                  </table></td>
                  </tr>
                <?php } while ($row_os = mysql_fetch_assoc($os)); ?>
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