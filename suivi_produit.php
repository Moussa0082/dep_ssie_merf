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

  if(isset($_GET['annee'])) $annee=$_GET['annee']; else $annee=date("Y");


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


//if(isset($_GET['idcl'])) $idcl=$_GET['idcl']; else $idcl=0;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_res = "SELECT id_produit, intitule_produit, id_resultat, intitule_resultat  FROM resultat, produit WHERE ".$_SESSION["clp_where"]." and id_resultat=effet order by code_resultat, code_produit ";
$liste_res  = mysql_query($query_liste_res, $pdar_connexion) or die(mysql_error());
$row_liste_res  = mysql_fetch_assoc($liste_res);
$totalRows_liste_res  = mysql_num_rows($liste_res);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ug= "SELECT * FROM ugl order by code_ugl";
//$query_liste_mois= "SELECT * FROM mois order by num_mois";
    $liste_ug = mysql_query($query_liste_ug, $pdar_connexion) or die(mysql_error());
	$tableauUg=array();
	while($ligneug=mysql_fetch_assoc($liste_ug)){$tableauUg[]=$ligneug['code_ugl']."<>".$ligneug['nom_ugl'];}
	mysql_free_result($liste_ug);

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$query_liste_reference = "SELECT indicateur_produit, zone, valeur_cible, annee FROM cible_cmr_produit where annee=$annee";
	$liste_reference = mysql_query($query_liste_reference, $pdar_connexion) or die(mysql_error());
	$row_liste_reference = mysql_fetch_assoc($liste_reference);
	$totalRows_liste_reference = mysql_num_rows($liste_reference);
	$cible_array = array();
    if($totalRows_liste_reference>0){ 
	 do{ 
	 $cible_array[$row_liste_reference["zone"]][$row_liste_reference["indicateur_produit"]]=$row_liste_reference["valeur_cible"]; 
	 }
	while($row_liste_reference  = mysql_fetch_assoc($liste_reference));}
	
	  mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$query_liste_realise = "SELECT indicateur_produit, zone, valeur_realise, annee FROM realise_cmr_produit where annee=$annee";
	$liste_realise = mysql_query($query_liste_realise, $pdar_connexion) or die(mysql_error());
	$row_liste_realise = mysql_fetch_assoc($liste_realise);
	$totalRows_liste_realise = mysql_num_rows($liste_realise);
	$realise_array = array();
    if($totalRows_liste_realise>0){ 
	 do{ 
	 $realise_array[$row_liste_realise["zone"]][$row_liste_realise["indicateur_produit"]]=$row_liste_realise["valeur_realise"];
	 }
	while($row_liste_realise  = mysql_fetch_assoc($liste_realise));}

	 mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$query_liste_realise_ptba = "SELECT referentiel,region,id_indicateur_tache, sum(valeur_suivi) as valeur_realise FROM ptba, indicateur_tache, suivi_indicateur_tache
	 where ptba.code_activite_ptba=indicateur_tache.code_activite and indicateur_tache.id_indicateur_tache=suivi_indicateur_tache.indicateur and ptba.annee=$annee group by region,referentiel";
	$liste_realise_ptba = mysql_query($query_liste_realise_ptba, $pdar_connexion) or die(mysql_error());
	$row_liste_realise_ptba = mysql_fetch_assoc($liste_realise_ptba);
	$totalRows_liste_realise_ptba = mysql_num_rows($liste_realise_ptba);
	$realise_ptba_array = array();
    if($totalRows_liste_realise_ptba>0){
	 do{ 
	 $realise_ptba_array[$row_liste_realise_ptba["region"]][$row_liste_realise_ptba["referentiel"]]=$row_liste_realise_ptba["valeur_realise"];
	 }
	while($row_liste_realise_ptba  = mysql_fetch_assoc($liste_realise_ptba));}

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
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init();/*$("#container").addClass("sidebar-closed");*/});</script>
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
<div class="widget box box_projet">
<div class="widget-header1"> <center><h4><?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>
<div class="widget-header"> <h4><i class="icon-reorder"></i> Indicateurs de Produit </h4>
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
<table width="100%" border="0" cellspacing="0" class="table table-striped table-bordered table-responsive" >
                <tr>
                  <td nowrap="nowrap" ><?php include("content/annee_ptba.php"); ?></td>
                </tr>
                <?php if($totalRows_liste_res>0) {$c=0;do { ?>
                <tr bgcolor="#EBEBEB">
                  	<td valign="top"><strong><?php echo $row_liste_res['intitule_produit']; ?></strong></td>
                  </tr>
                <tr >
                  <td valign="top"><table border="0" cellspacing="1" width="100%" class="table table-striped table-bordered table-hover table-responsive">
                    <?php 
				   $id_prd=$row_liste_res['id_produit'];
				    mysql_select_db($database_pdar_connexion, $pdar_connexion);
					//$query_indprd = "SELECT * FROM produit, indicateur_produit where id_produit=produit and sous_composante='$id_scp'
					//order by code_produit, id_indicateur_produit";
					$query_indprd = "SELECT * FROM indicateur_produit, indicateur_produit_cmr where  id_indicateur_produit=indicateur_prd and produit='$id_prd'
					order by code_iprd, id_indicateur_produit";
					$indprd  = mysql_query($query_indprd , $pdar_connexion) or die(mysql_error());
					$row_indprd  = mysql_fetch_assoc($indprd);
					$totalRows_indprd  = mysql_num_rows($indprd);				  
				  ?>
                    <?php if($totalRows_indprd>0) { ?>
                    <thead>
                    <tr>
                      <td rowspan="2" align="center" >Indicateur</td>
                      <td colspan="1" rowspan="2" align="center" >Unit&eacute;</td>
                     <?php foreach($tableauUg as $vug){?>
                      <td colspan="3" align="center">
					  <?php 
					    $aug = explode('<>',$vug);
						$iug = $aug[0]; echo $aug[1]; ?></td>
						 <!-- <td rowspan="1"></td>-->
                       <?php } ?>
                        <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
                        <td rowspan="2" align="center" align="center">Actions</td>
                        <?php }?>
                    </tr>
                    <tr>
                      <?php foreach($tableauUg as $vug){?>
                       <td align="center">&nbsp;Pr&eacute;vue</td>
                        <td align="center">R&eacute;alis&eacute;e</td>
                        <td align="center">&nbsp;%&nbsp;</td>
                        <!--<td rowspan="1"></td>-->
                       <?php } ?>
                    </tr>
                  </thead>
                    
                    <?php $i=0; $j=0; $p1="j"; $p11="k"; $val_real=0; do { $tcic=0; ?>
                   <!-- <?php  if($p1!=$row_indprd['id_indicateur_produit']) {?>
                    <tr bgcolor="#ECF000">
                     <td colspan="19" align="center" bgcolor="#D2E2B1" onclick="show_tab('amontrer<?php echo $row_indprd['id_indicateur_produit'] ?>');"><div align="left" class="Style29"> <u>
                          <?php  if($p1!=$row_indprd['id_indicateur_produit']) {echo "<p class=\"menu_head\">".$row_indprd['intitule_indicateur_produit']."<span class=\"plusminus\">+</span></p>"; $i=0; }$p1=$row_indprd['id_indicateur_produit']; ?>
                      </u> </div></td>
                    </tr>
                    <?php } ?>
					 <tbody id="amontrer<?php echo $row_indprd['id_indicateur_produit']."_".$i ?>" class="<?php echo "hide";?>">-->
					 
                    
                    <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; else echo 'bgcolor="#FFFFFF"'; $i=$i+1;?> >
                      <td ><div align="left"><span class="Style20"><?php echo "."; ?>&nbsp;</span><span class="Style22"><?php echo $row_indprd['intitule_indicateur']; ?></span></div></td>
                      <td colspan="1" ><span class="Style22"><?php if(isset($row_indprd['referentiel']) && isset($unite_ind_ref_array[$row_indprd['referentiel']])) echo $unite_ind_ref_array[$row_indprd['referentiel']];?></span></td>
                     <?php foreach($tableauUg as $vug){
						   $aug = explode('<>',$vug);
						$iug = $aug[0];
						  ?>
                       <td nowrap="nowrap" ><div align="center">
                         <?php if(isset($cible_array[$iug][$row_indprd["id_indicateur"]]))   { ?>
<a onclick="get_content('modal_content/indicateur_ptba_lie_cmr.php','<?php echo "&id_ind=".$row_indprd['id_indicateur']."&referentiel=".$row_indprd['referentiel']."&annee=$annee&izone=$iug"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add"  title="Les Indicateurs" class="thickbox" dir=""><?php  echo number_format($cible_array[$iug][$row_indprd["id_indicateur"]], 0, ',', ' ');  ?> </a>
<?php } ?>
                       </div></td>
                            <td nowrap="nowrap" ><div align="center">
                              <?php
                              if( isset($realise_ptba_array[$iug][$row_indprd["referentiel"]]))
							   {
							    $val_real=$realise_ptba_array[$iug][$row_indprd["referentiel"]];
                                 echo number_format($val_real, 0, ',', ' ');
							   }
							  elseif( isset($realise_array[$iug][$row_indprd["id_indicateur"]]))
							   {
							   $val_real=$realise_array[$iug][$row_indprd["id_indicateur"]];
							   echo $val_real;
							   }
							   else echo $val_real="";
							 // if($val_real>0) echo number_format($val_real, 0, ',', ' '); else echo "";
							  
							    
							   ?>
                            </div></td>
													 <td nowrap="nowrap" ><div align="center">
													   <span class="Style32">
													   <?php if(isset($cible_array[$iug][$row_indprd["id_indicateur"]]) && ($val_real>0) && $cible_array[$iug][$row_indprd["id_indicateur"]]>0) echo number_format((100*$val_real/$cible_array[$iug][$row_indprd["id_indicateur"]]), 1, ',', ' ')." %"; else echo "-";?>
                                                     </span></div></td>
                           <!-- <td rowspan="1" bgcolor="#506429"></td> -->
                       <?php } ?>
                      <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
                      <td align="center">
                      <?php if($row_indprd['type_suivi']==2) {?>
<a onclick="get_content('modal_content/realise_cmr_produit.php','<?php echo "&id_ind=".$row_indprd['id_indicateur']."&annee=$annee"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add"  title="Les Indicateurs" class="thickbox" dir="">
<strong>Suivi</strong></a>
                        <?php }?>
                        </td>
                      <?php }?>
                    </tr>
					<!--</tbody>-->
                    <?php } while ($row_indprd = mysql_fetch_assoc($indprd)); mysql_free_result($indprd);?>
                    <?php } ?>
                  </table></td>
                  </tr>
			
                <?php } while ($row_liste_res = mysql_fetch_assoc($liste_res));  mysql_free_result($liste_res); ?>
                <?php } else {?>
                <tr>
                  <td nowrap="nowrap"><div align="center"><em><strong>Aucun produit enregistré  </strong></em></div></td>
                </tr>
                <?php } ?>
              </table>
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