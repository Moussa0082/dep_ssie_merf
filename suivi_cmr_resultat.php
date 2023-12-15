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
//annee en cours
  if(isset($_GET['annee'])) $annee=$_GET['annee']; else $annee=date("Y");
//annee precedent
 $anneep=$annee-1;
//


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


//if(isset($_GET['idcl'])) $idcl=$_GET['idcl']; else $idcl=0;

// requete resultat
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_res = "SELECT * FROM resultat, activite_projet WHERE resultat.projet='".$_SESSION["clp_projet"]."' and activite_projet.projet='".$_SESSION["clp_projet"]."' and niveau=1 and code=composante order by intitule_resultat";
$liste_res  = mysql_query($query_liste_res , $pdar_connexion) or die(mysql_error());
$row_liste_res  = mysql_fetch_assoc($liste_res);
$totalRows_liste_res  = mysql_num_rows($liste_res);

//annee
$an_courant=date("Y");
$tableauAnnee=array();
for($i=$_SESSION["annee_debut_projet"];$i<=$_SESSION["annee_fin_projet"];$i++) $tableauAnnee[]=$i;

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$query_liste_reference = "SELECT indicateur_rescmr, valeur_cible, annee FROM cible_indres_cmr order by annee";
	$liste_reference = mysql_query($query_liste_reference, $pdar_connexion) or die(mysql_error());
	$row_liste_reference = mysql_fetch_assoc($liste_reference);
	$totalRows_liste_reference = mysql_num_rows($liste_reference);
	$cible_array = array();
    if($totalRows_liste_reference>0){ 
	 do{ 
	 $cible_array[$row_liste_reference["annee"]][$row_liste_reference["indicateur_rescmr"]]=$row_liste_reference["valeur_cible"]; 
	 }
	while($row_liste_reference  = mysql_fetch_assoc($liste_reference));}
	
	  mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$query_liste_realise = "SELECT indicateur_rescmr, valeur_realise, annee FROM realise_cmr_resultat order by annee";
	$liste_realise = mysql_query($query_liste_realise, $pdar_connexion) or die(mysql_error());
	$row_liste_realise = mysql_fetch_assoc($liste_realise);
	$totalRows_liste_realise = mysql_num_rows($liste_realise);
	$realise_array = array();
    if($totalRows_liste_realise>0){ 
	 do{ 
	 $realise_array[$row_liste_realise["annee"]][$row_liste_realise["indicateur_rescmr"]]=$row_liste_realise["valeur_realise"]; 
	 }
	while($row_liste_realise  = mysql_fetch_assoc($liste_realise));}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind FROM referentiel_indicateur";
$liste_ind_ref  = mysql_query($query_liste_ind_ref , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref);
$totalRows_liste_ind_ref  = mysql_num_rows($liste_ind_ref);
$liste_ind_ref_array = array();
$unite_ind_ref_array = array();
do{  $liste_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]] = $row_liste_ind_ref["intitule_ref_ind"]; $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];
}while($row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref));
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
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init();$("#container").addClass("sidebar-closed");});</script>
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
.titrecorps2 {background-color: #999999; color:white; }
</style>
<div class="widget box box_projet">
<div class="widget-header1"> <center><h4><?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>
<div class="widget-header"> <h4><i class="icon-reorder"></i> Indicateurs d'effet </h4>
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
<table border="0" cellspacing="1" width="100%" class="table table-striped table-bordered table-responsive">
                <?php if($totalRows_liste_res>0) {$c=0;do { ?>
                <tr bgcolor="#ADADAD">
                  	<td valign="top" bgcolor="#D9D9D9"><div align="left" class="Style27"><strong><br /><?php echo $row_liste_res['intitule_resultat']; ?></strong></div></td>
                  </tr>
                <tr >
                  <td valign="top">
<table border="0" cellspacing="1" width="100%" class="table table-striped table-bordered table-hover table-responsive">
                    <?php 
				   $id_res=$row_liste_res['id_resultat'];
				    mysql_select_db($database_pdar_connexion, $pdar_connexion);
					//$query_indres = "SELECT * FROM resultat, indicateur_resultat where id_resultat=resultat and composante='$id_cp'	order by code_resultat, id_indicateur_resultat";
					$query_indres = "SELECT * FROM indicateur_resultat_cmr, indicateur_resultat where id_indicateur_resultat=indicateur_res and resultat='$id_res'
					 order by id_indicateur_resultat";
					$indres  = mysql_query($query_indres , $pdar_connexion) or die(mysql_error());
					$row_indres  = mysql_fetch_assoc($indres);
					$totalRows_indres  = mysql_num_rows($indres);
				  ?>
                    <?php if($totalRows_indres>0) { ?>
                    <thead>
                    <tr>
                      <td rowspan="2" align="center" >Indicateur</td>
                      <td colspan="1" align="center" rowspan="2" >Unit&eacute;</td>
                      <td rowspan="2" align="center" >Cible<br />RMP</td>
                      <?php foreach($tableauAnnee as $vannee){?>
                      <td colspan="3" align="center" <?php if(isset($vannee) && $vannee==date("Y")) echo "style=\"background-color:#FFFF00; color:#000000\""; ?>>
					  <?php 
					   // $aug = explode('<>',$vug); //$iug = $aug[0]; 
						echo $vannee; 
						?></td>
						  <!--<td rowspan="1"></td>-->
                          <?php } ?>
					     <td colspan="3" align="center">Total</td>
                      <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
                      <td rowspan="2" align="center">Actions</td>
                      <?php }?>
                    </tr>
                    <tr>
                     <?php foreach($tableauAnnee as $vannee){?>
                       <td >Pr&eacute;vue</td>
                        <td >&nbsp;R&eacute;alis&eacute;e&nbsp;</td>
                        <td >&nbsp;%&nbsp;</td>
                        <!--<td rowspan="1"></td>-->
                       <?php } ?>
                        <td>Cible</td>
                        <td>R&eacute;alis&eacute;e</td>
                        <td>%</td>
                      </tr>
                    </thead>
                    <?php $i=0; $j=0; $p1="j"; $p11="k"; do { $tric=$tcic=0; ?>
					 
                    
                    <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; else echo 'bgcolor="#FFFFFF"'; $i=$i+1;?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='<?php if($i%2!=0) echo '#FFFFFF';?>';">
                      <td width="40%"><div align="left"><span class="Style20"><?php echo "."; ?>&nbsp;</span><span class="Style22"><?php echo $row_indres['intitule_indicateur_cmr_res']; ?></span></div></td>
                      <td colspan="1" ><div align="center"><span class="Style22">
                      <?php if(isset($row_indres['referentiel']) && isset($unite_ind_ref_array[$row_indres['referentiel']])) echo $unite_ind_ref_array[$row_indres['referentiel']];?>

                      </span></div></td>
                      <td ><div align="center"><span class="Style29">
                        <?php if(isset($row_indres['cible_rmp'])) echo $row_indres['cible_rmp']; else echo $row_indres['cible_rmp'] ?>
                      </span></div></td>
                      <?php foreach($tableauAnnee as $vannee){
						  // $aug = explode('<>',$vug);
						//$iug = $aug[0];
						  ?>
                       <td nowrap="nowrap" > <div align="center"><?php if(isset($cible_array[$vannee][$row_indres["id_indicateur"]])) {
					   echo $cible_array[$vannee][$row_indres["id_indicateur"]]; $tcic=$tcic+$cible_array[$vannee][$row_indres["id_indicateur"]];}?>
                        </div></td>
                            <td nowrap="nowrap" ><div align="center"><?php if(isset($realise_array[$vannee][$row_indres["id_indicateur"]])){ echo $realise_array[$vannee][$row_indres["id_indicateur"]];  $tric=$tric+$realise_array[$vannee][$row_indres["id_indicateur"]];}?>
                              </div>                              </td>
													 <td nowrap="nowrap" ><div align="center"><span class="Style31 Style22"><strong>
													   <?php if(isset($cible_array[$vannee][$row_indres["id_indicateur"]]) && isset($realise_array[$vannee][$row_indres["id_indicateur"]]) && $cible_array[$vannee][$row_indres["id_indicateur"]]>0) echo number_format((100*$realise_array[$vannee][$row_indres["id_indicateur"]]/$cible_array[$vannee][$row_indres["id_indicateur"]]), 1, ',', ' ')." %"; else echo "-";?>
                              </strong></span></div>                                                     </td>
                            <!--<td rowspan="1" bgcolor="#506429"></td>-->
                            <?php } ?>
							<td><div align="center"><strong>
                              <?php if($row_indres['unite']!="%" && $row_indres['unite']!="Oui/Non") 
				echo number_format($tcic, 0, ',', ' ');?>
                            </strong></div></td>
                            <td><div align="center"><strong>
                              <?php if($row_indres['unite']!="%" && $row_indres['unite']!="Oui/Non") 
				echo number_format($tric, 0, ',', ' ');?>
                            </strong></div></td>
                            <td><div align="center"><strong>
                              <span class="Style31">
                              <?php if(isset($tcic) && isset($tric) && $tcic>0) echo number_format((100*$tric/$tcic), 1, ',', ' ')." %"; else echo "-";?></span>
                            </strong></div></td>
                      <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<4) {?>
                      <td align="center">
<?php
echo do_link("","","Suivi indicateur : ".$row_indres['intitule_indicateur_cmr_res'],"Suivi","","./","","get_content('modal_content/realise_cmr_resultat.php','id_ind=".$row_indres['id_indicateur']."','modal-body_add',this.title,'iframe');",1,"",$nfile);
?>
<!--<a onclick="get_content('modal_content/realise_cmr_resultat.php','<?php echo "&id_ind=".$row_indres['id_indicateur']; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add"  title="Les Indicateurs" class="thickbox" dir=""><strong>Suivi</strong></a>   -->
</td>
                     
                      <?php }?>
                    </tr>
					<!--</tbody>-->
                    <?php } while ($row_indres = mysql_fetch_assoc($indres)); mysql_free_result($indres);?>
                    <?php } ?>
                  </table></td>
                  </tr>
			
                <?php } while ($row_liste_res = mysql_fetch_assoc($liste_res));  mysql_free_result($liste_res); ?>
                <?php } else {?>
                <tr>
                  <td nowrap="nowrap"><div align="center"><em><strong>Aucun resultat enregistré  </strong></em></div></td>
                </tr>
                <?php } ?>
              </table>

<div class="clearfix"></div>

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