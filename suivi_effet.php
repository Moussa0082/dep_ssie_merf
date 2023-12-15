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



if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form5") && $_SESSION['clp_niveau']<4) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

//suppression
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$an=$_POST['annee'];
$idos=$_POST['ind'];
$vcible=$_POST['valeur_cible'];

$query_sup_set = "DELETE FROM cible_indos_cmr WHERE indicateur_oscmr='$idos'";
$Result1 = mysql_query($query_sup_set, $pdar_connexion) or die(mysql_error());
//fin suppression
foreach ($an as $key => $value)
{
	if(isset($vcible[$key]) && $vcible[$key]!=NULL) {
	
  $insertSQL = sprintf("INSERT INTO cible_indos_cmr (annee, indicateur_oscmr, valeur_cible, id_personnel, date_enregistrement) VALUES (%s, %s, %s,'$personnel', '$date')",
                       GetSQLValueString($an[$key], "int"),
   					   GetSQLValueString($idos, "int"),
					   GetSQLValueString($vcible[$key], "double"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  }
  }
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no"; 
  header(sprintf("Location: %s", $insertGoTo));
}



// Partie objectif specifique
// objectif specifique
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_os = "SELECT * FROM objectif_specifique WHERE ".$_SESSION["clp_where"]." order by id_objectif_specifique";
$os  = mysql_query($query_os , $pdar_connexion) or die(mysql_error());
$row_os  = mysql_fetch_assoc($os);
$totalRows_os  = mysql_num_rows($os);


if(isset($_GET["id_sup_indos"]) && $_SESSION['clp_niveau']<4) { $idios=$_GET["id_sup_indos"];
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


              <table width="100%" border="1" align="left" cellspacing="0" bordercolor="#D9D9D9">

                <?php if($totalRows_os>0) {$o=0;do { ?>
                <tr <?php if($o%2==0) echo 'bgcolor="#ECF0DF"'; $o=$o+1;?>>
                  <td valign="top"><div align="left"><span class="Style27"><strong>OS</strong></span> <span class="Style27"><?php echo $row_os['code_os'].": ".$row_os['intitule_objectif_specifique']; ?></span>
                                            </div></td>
                  </tr>
                <tr >
                  <td valign="top"><table border="0" width="100%" cellspacing="1">
				     <?php 
				    $id_os=$row_os['id_objectif_specifique'];
				    mysql_select_db($database_pdar_connexion, $pdar_connexion);
					$query_indos = "SELECT * FROM indicateur_objectif_specifique_cmr, indicateur_objectif_specifique where id_indicateur_objectif_specifique=indicateur_os and objectif_specifique='$id_os'";
					$indos  = mysql_query($query_indos , $pdar_connexion) or die(mysql_error());
					$row_indos  = mysql_fetch_assoc($indos);
					$totalRows_indos  = mysql_num_rows($indos);				  
				  ?>
                    <?php if($totalRows_indos>0) { ?>
                    <tr class="titrecorps2" >
                      <td rowspan="2" ><span class="Style23">Indicateur</span></td>
                      <td rowspan="2" ><span class="Style23">Unit&eacute;</span></td>
                      <td rowspan="2" >&nbsp;</td>
                      <td colspan="2" ><span class="Style23">R&eacute;f&eacute;rences </span></td>
                      
                      <td >&nbsp;</td>
                      <td colspan="2"><span class="Style23">Valeurs cibles  </span></td>
                      <td>&nbsp;</td>
                      <td colspan="2"><span class="Style32">R&eacute;alis&eacute;es </span></td>
                      <td rowspan="2">&nbsp;</td>
                      <td rowspan="2">Variation<br />(%)</td>
                      <td colspan="2" rowspan="2"><span class="Style23">Responsable</span></td>
                      <td colspan="2" rowspan="2"><span class="Style23">Editer</span></td>
                      </tr>
                    <tr class="titrecorps2" >
                      <td ><span class="Style23">Ann&eacute;e&nbsp;</span></td>
                     				 
                      <td ><span class="Style23">&nbsp;Situation</span></td>
                      <td >&nbsp;</td>
                      <td><span class="Style23">DCP</span></td>
                      <td><span class="Style23">RMP</span></td>
                      <td>&nbsp;</td>
                      <td><span class="Style32">RMP</span></td>
                      <td><span class="Style30">EFP</span></td>
                      </tr>
                    <?php $i=0; $p1="j"; do { ?>
                    <?php  if($p1!=$row_indos['id_indicateur_objectif_specifique']) {?>
                    <tr bgcolor="#ECF000">
                      <td colspan="<?php  echo 17; ?>" align="center" bgcolor="#D2E2B1"><div align="left" class="Style27"><strong> <u>
                          <?php  if($p1!=$row_indos['id_indicateur_objectif_specifique']) {echo $row_indos['code_ios'].". ".$row_indos['intitule_indicateur_objectif_specifique']; $i=0; }$p1=$row_indos['id_indicateur_objectif_specifique']; ?>
                      </u> </strong></div></td>
                    </tr>
                    <?php } ?>
                    <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; else echo 'bgcolor="#FFFFFF"'; $i=$i+1;?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='<?php if($i%2!=0) echo '#ECF0DF';?>';">
                      <td ><div align="left"><span class="Style20">.&nbsp;</span><span class="Style22"><?php echo $row_indos['intitule_indicateur_cmr_os']; ?></span></div></td>
                      <td ><div align="center"><span class="Style22"><?php if(isset($row_indos['referentiel']) && isset($unite_ind_ref_array[$row_indos['referentiel']])) echo $unite_ind_ref_array[$row_indos['referentiel']];?></span></div></td>
                      <td bgcolor="#506429" >&nbsp;</td>
                      <td ><div align="center" class="Style22"><strong><?php echo $row_indos['annee_reference']; ?></strong></div></td>
					  
                      
                     
                      <td ><div align="center"><span class="Style22"><?php if(isset($row_indos['reference_cmr']) && $row_indos['reference_cmr']>0) echo $row_indos['reference_cmr']."<em> ".$row_indos['unite_cmr']."</em>"; ?></span></div></td>
                      <td bgcolor="#506429" >&nbsp;</td>
                      <td><div align="center"><span class="Style22"><?php if(isset($row_indos['cible_cmr']) && $row_indos['cible_cmr']>0) echo $row_indos['cible_cmr']." <em>".$row_indos['unite_cmr']."</em>"; ?></span></div></td>
                      <td><div align="center"><span class="Style22"><?php if(isset($row_indos['cible_rmp']) && $row_indos['cible_rmp']>0) echo $row_indos['cible_rmp']." <em>".$row_indos['unite_cmr']."</em>"; ?></span></div></td>
                      <td bgcolor="#506429">&nbsp;</td>
                      <td><span class="Style22"><?php if(isset($row_indos['realise_rmp']) && $row_indos['realise_rmp']>0) echo $row_indos['realise_rmp']." <em>".$row_indos['unite_cmr']."</em>"; ?></span></td>
                      <td><span class="Style22"><?php if(isset($row_indos['realise_efp']) && $row_indos['realise_efp']>0) echo $row_indos['realise_efp']." <em>".$row_indos['unite_cmr']."</em>"; ?></span></td>
                      <td bgcolor="#506429">&nbsp;</td>
                      <td><div align="center"><span class="Style29">
                        <?php if(isset($row_indos['reference_cmr']) && $row_indos['reference_cmr']>0 && isset($row_indos['realise_efp'])) echo number_format((100*($row_indos['realise_efp']-$row_indos['reference_cmr'])/$row_indos['reference_cmr']), 2, ',', ' ')." <em>".$row_indos['unite']."</em>";  elseif(isset($row_indos['reference_cmr']) && $row_indos['reference_cmr']>0 && isset($row_indos['realise_rmp'])) echo number_format((100*($row_indos['realise_rmp']-$row_indos['reference_cmr'])/$row_indos['reference_cmr']), 2, ',', ' ')." <em>".$row_indos['unite']."</em>";?>
                      </span></div></td>
                      <td colspan="2"><span class="Style22">&nbsp;</span><span class="Style22">
                        <?php  
			$as = explode(",", $row_indos['responsable_collecte']); 	$lacteur=implode("','", $as);
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
                      <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<4) {?>
                      <td valign="middle"><div align="center">
<a onclick="get_content('modal_content/realise_cmr_effet_rmp.php','<?php echo "&id_ind=".$row_indos['id_indicateur']."&annee=$annee&izone=$iug"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add"  title="Les Indicateurs" class="thickbox" dir="">
                      <strong>RMP</strong></a> &nbsp;&nbsp;</div></td>
                      <td valign="middle"><div align="center">
<a onclick="get_content('modal_content/realise_cmr_effet_final.php','<?php echo "&id_ind=".$row_indos['id_indicateur']."&annee=$annee&izone=$iug"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add"  title="Les Indicateurs" class="thickbox" dir="">
                      <strong>EFP</strong></a> </div></td>
                      <?php }?>

                    </tr>
                    <?php } while ($row_indos = mysql_fetch_assoc($indos)); mysql_free_result($indos); ?>
                    <?php } ?>
                  </table>
                  </td>
                </tr>
                <?php } while ($row_os = mysql_fetch_assoc($os)); mysql_free_result($os); ?>
                <?php } ?>
              </table>

<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>

<?php if(isset($_GET['acteur'])){ ?>
<script type="text/javascript">
show_tab('amontrer<?php echo $_GET["acteur"]; ?>');;
</script>
<?php }?>
</body>
</html>