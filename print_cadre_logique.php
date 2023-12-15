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
 if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Cadre_logique_Projet.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Cadre_logique_Projet.doc"); }

$editFormAction = $_SERVER['PHP_SELF'];
/*if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}*/

// query og
$query_og = "SELECT * FROM objectif_global WHERE projet='".$_SESSION["clp_projet"]."'";
try{
    $og = $pdar_connexion->prepare($query_og);
    $og->execute();
    $row_og = $og ->fetchAll();
    $totalRows_og = $og->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

// query indicateur
$query_ind = "SELECT * FROM indicateur_objectif_global WHERE projet='".$_SESSION["clp_projet"]."' order by id_indicateur_objectif_global";
try{
    $ind = $pdar_connexion->prepare($query_ind);
    $ind->execute();
    $row_ind = $ind ->fetchAll();
    $totalRows_ind = $ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

// query source de verification
$query_src = "SELECT * FROM source_og WHERE projet='".$_SESSION["clp_projet"]."' order by id_source";
try{
    $src = $pdar_connexion->prepare($query_src);
    $src->execute();
    $row_src = $src ->fetchAll();
    $totalRows_src = $src->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

// query hypothese
$query_hyp = "SELECT * FROM hypothese_og WHERE projet='".$_SESSION["clp_projet"]."' order by id_hypothese";
try{
    $hyp = $pdar_connexion->prepare($query_hyp);
    $hyp->execute();
    $row_hyp = $hyp ->fetchAll();
    $totalRows_hyp = $hyp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

// Partie objectif specifique

// objectif specifique
$query_os = "SELECT * FROM objectif_specifique WHERE projet='".$_SESSION["clp_projet"]."' order by id_objectif_specifique";
try{
    $os = $pdar_connexion->prepare($query_os);
    $os->execute();
    $row_os = $os ->fetchAll();
    $totalRows_os = $os->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

// Partie resultat

//composante

// requete composante
$query_cp = "SELECT * FROM activite_projet WHERE projet='".$_SESSION["clp_projet"]."' and niveau=1 order by code";
try{
    $cp = $pdar_connexion->prepare($query_cp);
    $cp->execute();
    $row_cp = $cp ->fetchAll();
    $totalRows_cp = $cp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<?php if(!isset($_GET["down"])){  ?>
  <title><?php print $config->sitename;?></title>

  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone;?>" />
  <?php }  ?>

  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php if(!isset($_GET["down"])){  ?>

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
 <?php } ?>
</head>
<body>
 <header class="header navbar navbar-fixed-top" role="banner">
    <?php if(!isset($_GET["down"])) include_once("includes/header.php"); ?>
 </header>
<div id="container">
    <div id="sidebar" class="sidebar-fixed">
        <div id="sidebar-content">
            <?php if(!isset($_GET["down"])) include_once("includes/menu_top.php"); ?>
        </div>
        <div id="divider" class="resizeable"></div>
    </div>

    <div id="content">
        <div class="container">
            <div class="crumbs">
                <?php if(!isset($_GET["down"])) include_once("includes/sous_menu.php"); ?>
            </div>
        <div class="page-header">
            <div class="p_top_5">
<!-- Site contenu ici -->
<style>#sp_hr {margin:0px; }
.r_float{float: right;}

.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
</style>

<?php if(!isset($_GET["down"])){  ?>
<div class="well well-sm r_float"><div class="r_float"><a href="./s_suivi_resultat.php" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."?down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."?down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a target="_blank" title="Imprimer" href="<?php echo $editFormAction."?down=1"; ?>" class="button"><img src="./images/print.png" width='20' height='20' alt='Modifier' /></a></div></div>
<div class="clear h0">&nbsp;</div>
<?php } else { ?>

<center><?php //include "./includes/print_header.php"; ?></center>

<?php } ?>
<div class="widget box box_projet">
<div class="widget-header1"> <center><h4><?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Cadre Logique: <span style="color:#FF9900">Aper&ccedil;u global</span> </h4>
   
</div>
<div class="widget-content" style="display: block;">

<table width="100%"  border="0" cellspacing="0" >

          <tr>

            <th scope="col"><div align="left">

              </div></th>

          </tr>

          <tr>

            <td><table width="100%" border="0" align="center" cellspacing="1" class="table table-striped table-bordered table-responsive">
              <tr bgcolor="#D2E2B1">
                <td colspan="4" valign="top"><div align="center"><strong>Cadre Logique du <?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> au <?php echo date("d-m-Y"); ?></strong> </div></td>
              </tr>
              <tr>
                <td valign="top" bgcolor="#FFFFFF" colspan="4"><strong> 1. <span class="Style22">OBJECTIF GLOBAL</span> (But) </strong></td>
              </tr>
              <tr bgcolor="#D9D9D9">
                <td valign="middle" width="25%"><strong> R&eacute;sum&eacute; descriptif </strong></td>
                <td valign="middle" width="25%"><strong> Indicateurs objectivement v&eacute;rifiables</strong> </td>
                <td valign="middle" width="25%"><strong> Source d&rsquo;information</strong> </td>
                <td valign="middle" width="25%"><strong> Risques/hypoth&egrave;ses</strong> </td>
              </tr>
              <tr>
                <td valign="top"><div align="left">
					<?php if($totalRows_og>0) { $i=0; foreach($row_og as $row_og){  ?>
                    <?php echo $row_og['intitule_objectif_global']; ?>
                    <?php } }?>
                </div></td>
                <td valign="top"><table border="0" cellspacing="0">
					<?php if($totalRows_ind>0) { $i=0; foreach($row_ind as $row_ind){  ?>
                    <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; $i=$i+1;?>>
               <td ><div align="left"><?php echo "- ".$row_ind['intitule_indicateur_objectif_global']; ?>
                        </div></td>
                    </tr>
                    <?php } }?>
                </table></td>
                <td valign="top"><table border="0" cellspacing="0">
					<?php if($totalRows_src>0) { $i=0; foreach($row_src as $row_src){  ?>
                    <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; $i=$i+1;?>>
                      <td ><div align="left"><?php echo "- ".$row_src['intitule_source']; ?></div></td>
                    </tr>
                    <?php } }?>
                </table></td>
                <td valign="top"><div align="center">
                    <table border="0" align="left" cellspacing="0">
  					<?php if($totalRows_hyp>0) { $i=0; foreach($row_hyp as $row_hyp){  ?>
                      <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; $i=$i+1;?>>
                        <td><div align="left"><?php echo "- ".$row_hyp['intitule_hypothese']; ?></div></td>
                      </tr>
                      <?php } } ?>
                    </table>
                </div></td>
              </tr>
            </table></td>

          </tr>

          <tr>

            <td><div align="left">
              <table width="100%" border="0" align="left" cellspacing="1" class="table table-striped table-bordered table-responsive">
              <tr>
                <td valign="top" bgcolor="#FFFFFF" colspan="4"><strong> 2. <span class="Style22">OBJECTIF DE DEVELOPPEMENT</span></strong></td>
              </tr>
                <tr>
                  <td nowrap="nowrap" bgcolor="" width="25%"><strong>R&eacute;sum&eacute; descriptif</strong></td>
                  <td bgcolor="" width="25%"><strong>Indicateurs objectivement v&eacute;rifiables</strong> </td>
                  <td bgcolor="" width="25%"><strong>Source d&rsquo;information</strong></td>
                  <td bgcolor="" width="25%"><strong>Risques/hypoth&egrave;ses</strong></td>
                </tr>
			     <?php if($totalRows_os>0) { $o=0; foreach($row_os as $row_os){  ?>
                <tr <?php if($o%2==0) echo 'bgcolor="#ECF0DF"'; $o=$o+1;?>>
                  <td valign="top"><div align="left"><?php echo $row_os['intitule_objectif_specifique']; ?> </div></td>
                  <td valign="top"><table border="0" align="left" cellspacing="0">
                      <?php

				    $id_os=$row_os['id_objectif_specifique'];	
					// requete indicateur OS
					$query_indos = "SELECT * FROM indicateur_objectif_specifique where objectif_specifique='$id_os'";
					try{
						$indos = $pdar_connexion->prepare($query_indos);
						$indos->execute();
						$row_indos = $indos ->fetchAll();
						$totalRows_indos = $indos->rowCount();
					}catch(Exception $e){ die(mysql_error_show_message($e)); }

				  ?>
	  			     <?php if($totalRows_indos>0) { $i=0; foreach($row_indos as $row_indos){  ?>
                      <tr <?php if($i%2==0) echo 'bgcolor="#FFFFFF"'; $i=$i+1;?>>
                        <td><div align="left"><?php echo "- ".$row_indos['intitule_indicateur_objectif_specifique']; ?>
                        </div></td>
                      </tr>
                      <?php } } ?>
                      <tr>
                        <td><div align="center" class="Style2">
                            <?php if(!$totalRows_indos>0) echo "Aucun indicateur enregistr&eacute;: "; ?>
                        </div></td>
                      </tr>
                  </table></td>
                  <td valign="top"><table border="0" align="left" cellspacing="0">
                      <?php
				    $id_os=$row_os['id_objectif_specifique'];		
					$query_srcos = "SELECT * FROM source_os where objectif_specifique='$id_os'";
					try{
						$srcos = $pdar_connexion->prepare($query_srcos);
						$srcos->execute();
						$row_srcos = $srcos ->fetchAll();
						$totalRows_srcos = $srcos->rowCount();
					}catch(Exception $e){ die(mysql_error_show_message($e)); }
				  ?>
  	  			     <?php if($totalRows_srcos>0) { $i=0; foreach($row_srcos as $row_srcos){  ?>
                      <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; $i=$i+1;?>>
                        <td><div align="left"><?php echo "- ".$row_srcos['intitule_source']; ?></div></td>
                      </tr>
                      <?php } } ?>
                      <tr>
                        <td><div align="center">
                            <?php if(!$totalRows_srcos>0) echo "Aucune source enregistr&eacute;e: "; ?>
                        </div></td>
                      </tr>
                  </table></td>
                  <td valign="top"><table border="0" align="left" cellspacing="0">
                      <?php

				   // $id_os=$row_os['id_objectif_specifique'];				
					$query_hypos = "SELECT * FROM hypothese_os where objectif_specifique='$id_os'";
					try{
						$hypos = $pdar_connexion->prepare($query_hypos);
						$hypos->execute();
						$row_hypos = $hypos ->fetchAll();
						$totalRows_hypos = $hypos->rowCount();
					}catch(Exception $e){ die(mysql_error_show_message($e)); }

				  ?>
  	  			     <?php if($totalRows_hypos>0) { $i=0; foreach($row_hypos as $row_hypos){  ?>
                      <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; $i=$i+1;?>>
                        <td><div align="left"><?php echo "- ".$row_hypos['intitule_hypothese']; ?></div></td>
                      </tr>
                      <?php } } ?>
                      <tr>
                        <td><div align="center" class="Style2">
                            <?php if(!$totalRows_hypos>0) echo "Aucune hypothese enregistr&eacute;e: ";?>
                        </div></td>
                      </tr>
                  </table></td>
                </tr>
                <?php } ?>
                <?php } else {?>
                <tr>
                  <td colspan="4" nowrap="nowrap"><div align="center"><em><strong>Aucun objectif sp&eacute;cifique enregistr&eacute; </strong></em></div></td>
                </tr>
                <?php } ?>
              </table>
            </div></td>

          </tr>

		  <tr><td><table width="100%" border="0" align="left" cellspacing="1" class="table table-striped table-bordered table-responsive">
              <tr>
                <td valign="top" bgcolor="#FFFFFF" colspan="4"><strong> 3. <span class="Style22">R&eacute;sultats / Produits par effet et par composante</span></strong></td>
              </tr>
		     <?php if($totalRows_cp>0) { $i=0; foreach($row_cp as $row_cp){  ?>
            <tr bgcolor="#009900">
              <td colspan="4" style="color: white; background-color: #009900" bgcolor="#009900" valign="top" align="left"><?php echo $row_cp['code'].": ".$row_cp['intitule']; ?>&nbsp;</td>
            </tr>
            <tr>
              <td nowrap="nowrap" bgcolor="" width="25%"><div align="left"><strong>Effets</strong></div></td>
              <td bgcolor="" width="25%"><strong>Indicateurs objectivement v&eacute;rifiables</strong> </td>
              <td bgcolor="" width="25%"><strong>Source d&rsquo;information</strong></td>
              <td bgcolor="" width="25%"><strong>Risques/hypoth&egrave;ses</strong></td>
            </tr>
            <?php

		  //debut de ligne

					$id_cp=$row_cp['code'];
					$query_res = "SELECT * FROM resultat where composante='$id_cp' and projet='".$_SESSION["clp_projet"]."' order by code_resultat";
					try{
						$res = $pdar_connexion->prepare($query_res);
						$res->execute();
						$row_res = $res ->fetchAll();
						$totalRows_res = $res->rowCount();
					}catch(Exception $e){ die(mysql_error_show_message($e)); }

				?>
		     <?php if($totalRows_res>0) { $i=0; foreach($row_res as $row_res){  ?>
            <tr <?php if($o%2==0) echo 'bgcolor="#ECF0DF"'; $o=$o+1;?>>
              <td valign="top"><div align="left"><span class="Style11"><?php echo "<b>Effet ".$row_res['code_resultat']."</b>: ".$row_res['intitule_resultat']; ?></span> </div></td>
              <td valign="top"><table border="0" align="left" cellspacing="0">
                  <?php

				    $id_res=$row_res['id_resultat'];				
					$query_indres = "SELECT * FROM indicateur_resultat where resultat='$id_res'";
					try{
						$indres = $pdar_connexion->prepare($query_indres);
						$indres->execute();
						$row_indres = $indres ->fetchAll();
						$totalRows_indres = $indres->rowCount();
					}catch(Exception $e){ die(mysql_error_show_message($e)); }

				  ?>
	  		     <?php if($totalRows_indres>0) { $b=0; foreach($row_indres as $row_indres){  ?>
                  <tr <?php if($b%2==0) echo 'bgcolor="#FFFFFF"'; $b=$b+1;?>>
                    <td><div align="left" class="Style11">
                        <?php echo "- ".$row_indres['intitule_indicateur_resultat']; ?>
                    </div></td>
                  </tr>
                  <?php } } ?>
                  <tr>
                    <td><div align="center" class="Style2">
                        <?php if(!$totalRows_ind>0) echo "Aucun indicateur enregistr&eacute;: "; ?>
                    </div></td>
                  </tr>
              </table></td>
              <td valign="top"><table border="0" align="left" cellspacing="0">
                  <?php

				   // $id_res=$row_res['id_resultat'];				
					$query_srcres = "SELECT * FROM source_res where resultat='$id_res'";
					try{
						$srcres = $pdar_connexion->prepare($query_srcres);
						$srcres->execute();
						$row_srcres = $srcres ->fetchAll();
						$totalRows_srcres = $src->rowCount();
					}catch(Exception $e){ die(mysql_error_show_message($e)); }

				  ?>
  	  		     <?php if($totalRows_srcres>0) { $i=0; foreach($row_srcres as $row_srcres){  ?>
                  <tr <?php if($i%2==0) echo 'bgcolor="#FFFFFF"'; $i=$i+1;?>>
                    <td><div align="left" class="Style11"><?php echo "- ".$row_srcres['intitule_source']; ?></div></td>
                  </tr>
                  <?php } } ?>
                  <tr>
                    <td><div align="center" class="Style2">
                        <?php if(!$totalRows_srcres>0) echo "Aucune source enregistr&eacute;e: ";  ?>
                    </div></td>
                  </tr>
              </table></td>
              <td valign="top"><table border="0" align="left" cellspacing="0">
                  <?php

				   // $id_res=$row_res['id_resultat'];				
					$query_hypres = "SELECT * FROM hypothese_res where resultat='$id_res'";
					try{
						$hypres = $pdar_connexion->prepare($query_hypres);
						$hypres->execute();
						$row_hypres = $hypres ->fetchAll();
						$totalRows_hypres = $hypres->rowCount();
					}catch(Exception $e){ die(mysql_error_show_message($e)); }

				  ?>
   	  		     <?php if($totalRows_hypres>0) { $i=0; foreach($row_hypres as $row_hypres){  ?>
                  <tr <?php if($i%2==0) echo 'bgcolor="#FFFFFF"'; $i=$i+1;?>>
                    <td><div align="left" class="Style11"><?php echo "- ".$row_hypres['intitule_hypothese']; ?></div></td>
                  </tr>
                  <?php } } ?>
                  <tr>
                    <td><div align="center" class="Style2">
                        <?php if(!$totalRows_hypres>0) echo "Aucune hypothese enregistr&eacute;e: ";   ?>
                    </div></td>
                  </tr>
              </table></td>
            </tr>
            <?php

		  //produit				
					$query_produit = "SELECT * FROM produit where effet='$id_res' order by code_produit";
					try{
						$produit = $pdar_connexion->prepare($query_produit);
						$produit->execute();
						$row_produit = $produit ->fetchAll();
						$totalRows_produit = $produit->rowCount();
					}catch(Exception $e){ die(mysql_error_show_message($e)); }

				?>
  		     <?php if($totalRows_produit>0) { $op=0; foreach($row_produit as $row_produit){  ?>
            <tr <?php if($op%2==0) echo 'bgcolor="#ECF0DF"'; $op=$op+1;?>>
           <td valign="top"><div align="left"><span class="Style11"><?php echo "<b>Produit ".$row_produit['code_produit']."</b>: ".$row_produit['intitule_produit']; ?></span><br />
              </div></td>
              <td valign="top"><table border="0" align="left" cellspacing="0">
                  <?php

				   $id_prd=$row_produit['id_produit'];					
					$query_indp = "SELECT * FROM indicateur_produit where produit='$id_prd'";
					try{
						$indp = $pdar_connexion->prepare($query_indp);
						$indp->execute();
						$row_indp = $indp ->fetchAll();
						$totalRows_indp = $indp->rowCount();
					}catch(Exception $e){ die(mysql_error_show_message($e)); }

				  ?>
   		          <?php if($totalRows_indp>0) { $i=0; foreach($row_indp as $row_indp){  ?>
                  <tr <?php if($b%2==0) echo 'bgcolor="#FFFFFF"'; $b=$b+1;?>>
                    <td><div align="left" class="Style11">
                        <?php echo "- ".$row_indp['intitule_indicateur_produit']; ?>
                    </div></td>
                  </tr>
                  <?php } } ?>
                  <tr>
                    <td><div align="center" class="Style2">
                        <?php if(!$totalRows_indp>0) echo "Aucun indicateur enregistr&eacute;: ";  ?>
                    </div></td>
                  </tr>
              </table></td>
              <td valign="top"><table border="0" align="left" cellspacing="0">
                  <?php
					$query_srcp = "SELECT * FROM source_produit where produit='$id_prd'";
					try{
						$srcp = $pdar_connexion->prepare($query_srcp);
						$srcp->execute();
						$row_srcp = $srcp ->fetchAll();
						$totalRows_srcp = $srcp->rowCount();
					}catch(Exception $e){ die(mysql_error_show_message($e)); }
					
				  ?>
   		          <?php if($totalRows_srcp>0) { $i=0; foreach($row_srcp as $row_srcp){  ?>
                  <tr <?php if($i%2==0) echo 'bgcolor="#FFFFFF"'; $i=$i+1;?>>
                    <td><div align="left" class="Style11"><?php echo "- ".$row_srcp['intitule_source']; ?></div></td>
                  </tr>
                  <?php } } ?>
                  <tr>
                    <td><div align="center" class="Style2">
                        <?php if(!$totalRows_srcp>0) echo "Aucune source enregistr&eacute;e: ";  ?>
                    </div></td>
                  </tr>
              </table></td>
              <td valign="top"><table border="0" align="left" cellspacing="0">
                  <?php

				    //$id_res=$row_res['id_resultat'];					
					$query_hypp = "SELECT * FROM hypothese_produit where produit='$id_prd'";
					try{
						$hypp = $pdar_connexion->prepare($query_hypp);
						$hypp->execute();
						$row_hypp = $hypp ->fetchAll();
						$totalRows_hypp = $hypp->rowCount();
					}catch(Exception $e){ die(mysql_error_show_message($e)); }

				  ?>
   		          <?php if($totalRows_hypp>0) { $i=0; foreach($row_hypp as $row_hypp){  ?>
                  <tr <?php if($i%2==0) echo 'bgcolor="#FFFFFF"'; $i=$i+1;?>>
                    <td><div align="left" class="Style11"><?php echo "- ".$row_hypp['intitule_hypothese']; ?></div></td>
                  </tr>
                  <?php } } ?>
                  <tr>
                    <td><div align="center" class="Style2">
                        <?php if(!$totalRows_hypp>0) echo "Aucune hypothese enregistr&eacute;e: ";   ?>
                    </div></td>
                  </tr>
              </table></td>
            </tr>
            <?php } } ?>
            <tr>
              <td colspan="4"><div align="center" class="Style2">
                  <?php if(!$totalRows_produit>0) echo "Aucun produit enregistr&eacute;: ";  ?>
              </div></td>
            </tr>
            <?php //fin produit?>
            <?php } } ?>
            <tr>
              <td colspan="4"><div align="center" class="Style2">
                  <?php if(!$totalRows_res>0) echo "Aucun effet enregistr&eacute;: "; ?>
              </div></td>
            </tr>
            <?php } } else {?>
            <tr>
              <td colspan="4" nowrap="nowrap"><div align="center"><em><strong>Aucune composante enregistr&eacute;e; </strong></em></div></td>
            </tr>
            <?php } ?>
          </table></td></tr>


        </table>
</div>

</div></div>

<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>    <?php if(!isset($_GET["down"])) include_once 'modal_add.php'; ?>
    <?php if(!isset($_GET["down"])) include_once("includes/footer.php"); ?>
</div>

</body>
</html>