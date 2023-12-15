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

$sigle=$sigleprojet="FIER";
 if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=PTBA_$sigle.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=PTBA_$sigle.rtf"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="p"){

include("pdf/mpdf.php");
$mpdf=new mPDF('win-1252','A4-L','','',15,10,16,10,10,10);//A4 page in portrait for landscape add -L.
$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetDisplayMode('fullpage');
ob_start();
include "print_ptba_tache_pdf.php";
$html = ob_get_contents();
ob_end_clean();
$mpdf->WriteHTML($html);
$mpdf->Output();
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=PTBA_$sigle.pdf");
exit;

 } ?>
<?php
 if(isset($_GET['annee'])) $annee=$_GET['annee']; else $annee=date("Y");
 if(isset($_GET['cp'])) $composante=$_GET['cp'];
 if(isset($_GET['region'])) $unite_gestion=$_GET['region'];
?>
<?php


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

	
$pcent = 100;

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_activite = "SELECT code_activite_ptba, intitule_activite_ptba FROM ".$database_connect_prefix."ptba WHERE annee='$annee' and projet='".$_SESSION["clp_projet"]."'";
  $liste_activite  = mysql_query($query_liste_activite , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_activite  = mysql_fetch_assoc($liste_activite );
  $totalRows_liste_activite  = mysql_num_rows($liste_activite );
  $activite_ptba_array = array();
  if($totalRows_liste_activite>0){  do{
    $activite_ptba_array[$row_liste_activite["code_activite_ptba"]] = $row_liste_activite["intitule_activite_ptba"];
  }while($row_liste_activite = mysql_fetch_assoc($liste_activite));  }
  

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cible_tindicateur = "SELECT sum(cible) as cible_total, indicateur, activite, trimestre FROM   ".$database_connect_prefix."cible_indicateur_trimestre where annee='$annee' and projet='".$_SESSION["clp_projet"]."' group by indicateur, activite, trimestre";
$cible_tindicateur  = mysql_query($query_cible_tindicateur , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_cible_tindicateur = mysql_fetch_assoc($cible_tindicateur );
$totalRows_cible_tindicateur = mysql_num_rows($cible_tindicateur );
$tableau_cible_tindicateur_array = array();
  if($totalRows_cible_tindicateur>0){  do{
    $tableau_cible_tindicateur_array[$row_cible_tindicateur["indicateur"]][$row_cible_tindicateur["activite"]][$row_cible_tindicateur["trimestre"]] = $row_cible_tindicateur["cible_total"];
  }while($row_cible_tindicateur = mysql_fetch_assoc($cible_tindicateur));  }

$tableauMois= array('1','2','3','4');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php if(!isset($_GET["down"])){  ?>
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
 <script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
 <script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/login.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/myscript.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/ui_general.js"></script>
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init();$("#container").addClass("sidebar-closed");});</script>
</head>
<?php }  ?>
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

</style>
<div class="contenu">
  <div id="msg" align="center" class="red"></div>
  <?php if(!isset($_GET["down"])){  ?>
  <div class="r_float"><a href="s_ptba.php?annee=<?php echo $annee; ?>" class="button">Retour</a></div>
    <div class="r_float" style="margin-right: 10px;"><a href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.jpg" width='20' height='20' alt='Modifier' /></a></div>
  <div class="r_float" style="margin-right: 10px;"><a href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.jpg" width='20' height='20' alt='Modifier' /></a></div>
<br />
<?php } ?>
<div align="left"><strong>PTBA  <?php echo $annee;?>&nbsp;&nbsp;Editer le:<span class="Style5"><u><?php echo date("d/m/Y"); ?></u></span></strong></div>

                <table width="100%"  border="1" align="left" cellspacing="0">
                  <tr bgcolor="#FFFFFF">
                    <td width="35%" colspan="5" ><span class="Style14"><strong>Activit&eacute;s</strong></span><span class="Style14"></span></td>
                    <td width="10%" ><div align="center"><span class="Style14"><strong>Proportion</strong></span><span class="Style14"></span></div></td>

                      <td width="10%" nowrap="nowrap" ><div align="center"><strong>Co&ucirc;t (Ouguiya) </strong></div></td>
                      
                      <td width="35%"> <div align="center"><strong>R&eacute;sultat </strong></div> </td>
                      <td width="35%"><div align="center"><strong>Unit&eacute;</strong></div></td>
                     <?php foreach($tableauMois as $vmois){  ?>
<td width="35%"> <div align="center"><?php echo "T".$vmois; ?> </td>
<?php } ?>
                      <td width="10%" align="center"><strong>Cible global </strong></td>
                      <!--<td width="20%" align="center"><b>Responsables</b></td>-->
                  </tr>

				   <?php $tgptba=0; if( isset($annee)) {  


				//Activit&eacute;s de la sous composante
					

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_tache = "SELECT * FROM ".$database_connect_prefix."groupe_tache where projet='".$_SESSION["clp_projet"]."' and annee='$annee' order by code_activite, code_tache asc";
$liste_tache  = mysql_query($query_liste_tache , $pdar_connexion) or die(mysql_error());
$row_liste_tache  = mysql_fetch_assoc($liste_tache);
$totalRows_liste_tache  = mysql_num_rows($liste_tache);	$tcscp=0;

				?>
<?php if($totalRows_liste_tache>0) {$p11="k"; $p1="j"; $o=0; $mi=0; $fcode_act="ca"; do { ?>
<?php
$code_act=$row_liste_tache['code_activite'];
$code_tache=$row_liste_tache['code_tache'];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_ind = "select code_indicateur_ptba, intitule_indicateur_tache, unite FROM ".$database_connect_prefix."indicateur_tache where code_activite='$code_act' and projet='".$_SESSION["clp_projet"]."' and tache='$code_tache' ORDER BY code_indicateur_ptba ASC";
$ind  = mysql_query($query_ind , $pdar_connexion) or die(mysql_error());
$row_ind  = mysql_fetch_assoc($ind);
$totalRows_ind  = mysql_num_rows($ind);
//if($totalRows_ind>0){
?>
                   
				       <?php if($totalRows_ind>=0) {$m=0; $sp=0;?>

					     <?php  $ii=0; $pp=0; $tind=0;$mi=0; $mm=0; do {


					        ?>
<?php if($ii==0 && $p1!=$code_act){ $ii=1; ?>
					<tr>
                      <td width="5">&nbsp;</td>
                      <td width="5">&nbsp;</td>
					  <td colspan="<?php echo 8; ?>" align="left" bgcolor="#E1E1C1"><div align="left"><strong><?php echo ((isset($activite_ptba_array[$code_act])?$activite_ptba_array[$code_act]:'Activite non reconnue')); ?></strong></div></td>
				    </tr>
                    <?php } $p1=$code_act; ?>
<tr <?php if($o%2==0) echo 'bgcolor="#ECF0DF"'; $o=$o+1;?>>
<?php if($pp==0){ ?>
                  <td rowspan="<?php echo $totalRows_ind; ?>" width="5">&nbsp;</td>
                  <td rowspan="<?php echo $totalRows_ind; ?>" width="5">&nbsp;</td>
                  <td rowspan="<?php echo $totalRows_ind; ?>" width="5">&nbsp;</td>
                  <td rowspan="<?php echo $totalRows_ind; ?>" valign="middle" colspan="2" width="400"><span class="Style14"><?php echo "<strong>".$row_liste_tache['code_tache'].":</strong> ".$row_liste_tache['intitule_tache']; ?></span></td>
<td rowspan="<?php echo $totalRows_ind; ?>" valign="middle" width="100"><div align="center"><span class="Style14"><?php echo $row_liste_tache['proportion']." %"; ?></span></div></td>

                         <td rowspan="<?php echo $totalRows_ind; ?>" valign="middle" width="100"><div align="center"><span class="Style14"><?php if(isset($row_liste_tache["cout_tache"]) && $row_liste_tache["cout_tache"]>0) echo number_format($row_liste_tache["cout_tache"], 0, ',', ' '); else echo ""; ?></span></div></td>
                         <?php } ?>

                 
                <td width="40%"><span class="Style12"><?php if($totalRows_ind>0){ echo $row_ind['code_indicateur_ptba'].": ".$row_ind['intitule_indicateur_tache']; $sp=$sp+$row_liste_tache["proportion"]; }else echo "Aucune"; ?></span></td>

                <td width="40%"><div align="center"><span class="Style12">
                  <?php if($totalRows_ind>0){ echo $row_ind['unite']; }else echo "N/A"; ?>
                </span></div></td>
                <?php $tcib=0; foreach($tableauMois as $vmois){  ?>
<td width="35%"> <div align="center"><?php if(isset($tableau_cible_tindicateur_array[$row_ind["code_indicateur_ptba"]][$code_act][$vmois])){ echo $tableau_cible_tindicateur_array[$row_ind["code_indicateur_ptba"]][$code_act][$vmois]; $tcib=$tcib+$tableau_cible_tindicateur_array[$row_ind["code_indicateur_ptba"]][$code_act][$vmois]; } else echo "-"; ?> </td>
<?php } ?>
                <td width="10%" align="center"><?php if($tcib>0){ echo $tcib; } else echo "-"; ?> </td>
                <!--<td width="48%" align="center">Resultat CR</td>-->

<?php  if($fcode_act!=$row_liste_tache['code_activite']){ $pp=0;  } $pp++; $fcode_act=$row_liste_tache['code_activite']; ?>
                        </tr>

<?php  } while ($row_ind = mysql_fetch_assoc($ind)); ?>
 <tr>
<td colspan="13" align="left"></hr></td>
</tr>
					   <?php } ?>

                      <?php $tg=0; $i=0; $ttmp=0;?>

                

               <?php    } while ($row_liste_tache = mysql_fetch_assoc($liste_tache)); ?>
                  <?php } ?>
				    <?php } ?>
</table>  
                <div class="clear h0"></div></div>

<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>    <?php if(!isset($_GET["down"])) include_once 'modal_add.php'; ?>
    <?php if(!isset($_GET["down"])) include_once("includes/footer.php"); ?>
</div>

</body>
</html>