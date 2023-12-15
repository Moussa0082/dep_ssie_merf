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

 if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Suivi_SYGRI_1er_Niveau.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Suivi_SYGRI_1er_Niveau.rtf"); } 

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//annee en cours
  if(isset($_GET['annee'])) $annee=$_GET['annee']; else $annee=date("Y");
//annee precedent
 $anneep=$annee-1;
 $annee_courant=$annee;

//annee
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_annee = "SELECT distinct annee FROM ".$database_connect_prefix."ptba where projet='".$_SESSION["clp_projet"]."' and annee<=$annee order by annee desc";
$liste_annee = mysql_query($query_liste_annee, $pdar_connexion) or die(mysql_error());
$tableauAnnee=array();
while($ligne=mysql_fetch_assoc($liste_annee)){$tableauAnnee[]=$ligne['annee'];}
mysql_free_result($liste_annee);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_composante = "SELECT * FROM activite_projet WHERE niveau=1 and projet='".$_SESSION["clp_projet"]."'";
$composante  = mysql_query($query_composante , $pdar_connexion) or die(mysql_error());
$row_composante  = mysql_fetch_assoc($composante);
$totalRows_composante  = mysql_num_rows($composante);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_indicateur_sygri = "SELECT * FROM  beneficiaire_sygri ORDER BY ordre";
$liste_indicateur_sygri  = mysql_query($query_liste_indicateur_sygri , $pdar_connexion) or die(mysql_error());
$row_liste_indicateur_sygri = mysql_fetch_assoc($liste_indicateur_sygri );
$totalRows_liste_indicateur_sygri = mysql_num_rows($liste_indicateur_sygri );

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_composante = "SELECT * FROM activite_projet WHERE niveau=1 and projet='".$_SESSION["clp_projet"]."'";
$liste_composante  = mysql_query($query_liste_composante , $pdar_connexion) or die(mysql_error());
$row_liste_composante = mysql_fetch_assoc($liste_composante);
$totalRows_liste_composante  = mysql_num_rows($liste_composante);
$liste_composante_array = array();
do{  $liste_composante_array[$row_liste_composante["code"]] = $row_liste_composante["intitule"];
}while($row_liste_composante = mysql_fetch_assoc($liste_composante));

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind, mode_calcul, mode_suivi  FROM referentiel_indicateur";
$liste_ind_ref  = mysql_query($query_liste_ind_ref , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref);
$totalRows_liste_ind_ref  = mysql_num_rows($liste_ind_ref);
$liste_ind_ref_array = array();
$unite_ind_ref_array = array();
$mode_calcul_ind_ref_array = array();
$mode_suivi_ind_ref_array = array();
do{  $liste_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]] = $row_liste_ind_ref["intitule_ref_ind"];
 $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];
 $mode_calcul_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["mode_calcul"];
  $mode_suivi_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["mode_suivi"];
}while($row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref));


//cible unique
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cible_ind_ref = " SELECT indicateur_cr	, ptba.annee, sum(cible) as valeur_cible FROM   ptba, indicateur_tache, cible_indicateur_trimestre where id_indicateur_tache=indicateur and id_activite=id_ptba and ptba.projet='".$_SESSION["clp_projet"]."' group by annee, indicateur_cr ";

$cible_ind_ref  = mysql_query($query_cible_ind_ref , $pdar_connexion) or die(mysql_error());
$row_cible_ind_ref = mysql_fetch_assoc($cible_ind_ref);
$totalRows_cible_ind_ref  = mysql_num_rows($cible_ind_ref);
$cible_ind_ref_array = array();
do{  $cible_ind_ref_array[$row_cible_ind_ref["indicateur_cr"]][$row_cible_ind_ref["annee"]] = $row_cible_ind_ref["valeur_cible"];
}while($row_cible_ind_ref = mysql_fetch_assoc($cible_ind_ref));

//print_r($cible_ind_ref_array);
//exit;

//Suivi unique
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_suivi_ind_ref = " SELECT * FROM  referentiel_indicateur";
$suivi_ind_ref  = mysql_query($query_suivi_ind_ref , $pdar_connexion) or die(mysql_error());
$row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref);
$totalRows_suivi_ind_ref  = mysql_num_rows($suivi_ind_ref);
$req_ind_ref_array =$suivi_ind_ref_array =$code_ind_ref_array = array();
							$suivief_an_ref_array = array();

do{ 
   $suivi_ind_ref_array[$row_suivi_ind_ref["id_ref_ind"]] = $row_suivi_ind_ref["code_ref_ind"];
     // $code_ind_ref_array[$row_suivi_ind_ref["id_ref_ind"]] = $row_suivi_ind_ref["code_ref_ind"];

    $req_ind_ref_array[$row_suivi_ind_ref["id_ref_ind"]] = $row_suivi_ind_ref["requete_sql_an"];

	
		                mysql_select_db($database_pdar_connexion, $pdar_connexion); //".$_SESSION["clp_where"]." and
							$query_liste_val_ref = $row_suivi_ind_ref["requete_sql_an"];
		                   // $query_liste_val_ref =str_replace("crp", "", $query_liste_val_ref);
							$query_liste_val_ref =str_replace("crp", '', $query_liste_val_ref);
                            $query_liste_val_ref =str_replace("prdc", $_SESSION["clp_projet"], $query_liste_val_ref);
							//echo $query_liste_val_ref;
							$liste_val_ref  = mysql_query($query_liste_val_ref , $pdar_connexion);
							 if ($liste_val_ref)
								{
								  // Traitement de l'erreur
								
							$row_liste_val_ref  = mysql_fetch_assoc($liste_val_ref );
							$totalRows_liste_val_ref  = mysql_num_rows($liste_val_ref);
								if($totalRows_liste_val_ref>0){ do{
							  $suivief_an_ref_array[$row_suivi_ind_ref["id_ref_ind"]][$row_liste_val_ref["annee"]]=$row_liste_val_ref["val"];
							   }while($row_liste_val_ref  = mysql_fetch_assoc($liste_val_ref)); 
							  } 
							  
							   }
				

}while($row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref));

//print_r($suivief_an_ref_array);
//exit;


//cible unique
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_indicateur_sygri_fida = "SELECT * FROM liste_indicateur_sygri";
$liste_indicateur_sygri_fida  = mysql_query($query_liste_indicateur_sygri_fida , $pdar_connexion) or die(mysql_error());
$row_liste_indicateur_sygri_fida = mysql_fetch_assoc($liste_indicateur_sygri_fida);
$totalRows_liste_indicateur_sygri_fida  = mysql_num_rows($liste_indicateur_sygri_fida);
$liste_indicateur_sygri_array = $liste_referentiel_sygri_array = array();
do{
$liste_indicateur_sygri_array[$row_liste_indicateur_sygri_fida["id_indicateur_sygri_fida"]] = $row_liste_indicateur_sygri_fida["intitule_indicateur_sygri_fida"];
$liste_referentiel_sygri_array[$row_liste_indicateur_sygri_fida["id_indicateur_sygri_fida"]] = $row_liste_indicateur_sygri_fida["referentiel"];
}while($row_liste_indicateur_sygri_fida = mysql_fetch_assoc($liste_indicateur_sygri_fida));

 mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_realise_sygri = "SELECT ireferentiel, sum(valeur_realise) as valeur_realise, annee FROM realise_sygri_n1 group by annee, ireferentiel"; 
$liste_realise_sygri = mysql_query($query_liste_realise_sygri, $pdar_connexion) or die(mysql_error());
$row_liste_realise_sygri = mysql_fetch_assoc($liste_realise_sygri);
$totalRows_liste_realise_sygri = mysql_num_rows($liste_realise_sygri);
$realise_array_sygri= array();
if($totalRows_liste_realise_sygri>0){ 
do{ $realise_array_sygri[$row_liste_realise_sygri["ireferentiel"]][$row_liste_realise_sygri["annee"]]=$row_liste_realise_sygri["valeur_realise"];}
while($row_liste_realise_sygri  = mysql_fetch_assoc($liste_realise_sygri));}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_realise = "SELECT ireferentiel, sum(valeur_realise) as valeur_realise, annee FROM realise_cmr_produit group by annee, ireferentiel"; 
$liste_realise = mysql_query($query_liste_realise, $pdar_connexion) or die(mysql_error());
$row_liste_realise = mysql_fetch_assoc($liste_realise);
$totalRows_liste_realise = mysql_num_rows($liste_realise);
$realise_arrayp= array();
if($totalRows_liste_realise>0){ 
do{ $realise_arrayp[$row_liste_realise["ireferentiel"]][$row_liste_realise["annee"]]=$row_liste_realise["valeur_realise"];}
while($row_liste_realise  = mysql_fetch_assoc($liste_realise));}

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_req_ind_ref = " SELECT * FROM  referentiel_indicateur";
$req_ind_ref  = mysql_query($query_req_ind_ref , $pdar_connexion) or die(mysql_error());
$row_req_ind_ref = mysql_fetch_assoc($req_ind_ref);
$totalRows_req_ind_ref  = mysql_num_rows($req_ind_ref);
$req_an_ind_ref_arrayp= $req_total_ind_ref_arrayp= array();
if($totalRows_req_ind_ref>0){ 
do{ $req_an_ind_ref_arrayp[$row_req_ind_ref["ireferentiel"]]=$row_req_ind_ref["valeur_realise"];}
while($row_req_ind_ref  = mysql_fetch_assoc($req_ind_ref));}*/
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
  <meta name="author" content="<?php print $config->MetaAuthor; ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/login.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
  <link href='<?php print $config->theme_folder; ?>/css.css' rel='stylesheet' type='text/css'>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/lodash.compat.min.js"></script>
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
.Style23{color: white;}

</style>
<div class="contenu">
  <div id="msg" align="center" class="red"></div>
<?php if(!isset($_GET["down"])){  ?>
  <div class="l_float"><?php //include("content/annee_ptba.php"); ?></div>
  <div class="r_float"><a href="s_sygri.php?annee=<?php echo $annee; ?>" class="button">Retour</a></div>
  <div class="r_float" style="margin-right: 10px;"><a href="<?php echo 'Classes\sygri_'.date('m').'_'.$annee.'.xlsx'; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<br />
<?php } ?>
<h4 align="center">Indicateurs de 1<sup>er</sup> niveau SYGRI en <?php echo $annee;?></h4>


<table width="100%" border="1" align="center" cellspacing="0" bordercolor="#000">

  <tr bgcolor="#CCCCCC">
    <td nowrap="nowrap" colspan="4"><strong>Indicateurs par cat&eacute;gorie </strong></td>
  </tr>
<?php
// $id_cp=$row_composante['id_composante'];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_ind = "SELECT 0 as id, '' as code, ' ' as intitule, code_groupe, nom_groupe, referentiel, id_indicateur_sygri_niveau1_projet, cible_projet, cible_rmp, indicateur_sygri1_projet.ordre, id_sygri,intitule_indicateur_sygri_fida  FROM groupe_indicateur, liste_indicateur_sygri, indicateur_sygri1_projet where id_groupe=groupe_indicateur and id_sygri=id_indicateur_sygri_fida and code_groupe='08' and  indicateur_sygri1_projet.projet='".$_SESSION["clp_projet"]."'
					union
					SELECT id, code, intitule, code_groupe, nom_groupe, referentiel, id_indicateur_sygri_niveau1_projet,  cible_projet, cible_rmp, indicateur_sygri1_projet.ordre, id_sygri,intitule_indicateur_sygri_fida  FROM activite_projet, groupe_indicateur, liste_indicateur_sygri, indicateur_sygri1_projet WHERE  id_groupe=groupe_indicateur and id_sygri=id_indicateur_sygri_fida and code_groupe!='08' and scomposante=code and  indicateur_sygri1_projet.projet=activite_projet.projet and indicateur_sygri1_projet.projet='".$_SESSION["clp_projet"]."' order by code, code_groupe, ordre";
				
$ind  = mysql_query($query_ind , $pdar_connexion) or die(mysql_error());
$row_ind  = mysql_fetch_assoc($ind);
$totalRows_ind  = mysql_num_rows($ind);

?>
  <!-- <tr <?php if($o2%2==0) echo 'bgcolor="#FFF"'; $o2=$o2+1;?>>
                           <td colspan="4" align="left" valign="middle"><span class="Style51"><?php echo $row_composante['code_composante'].": ".$row_composante['intitule_composante']; ?></span></td>
                         </tr>-->
  <tr >
    <td valign="top" colspan="4"><table width="100%" border="1" align="left" cellspacing="0">
      <tr class="titrecorps2">
        <td width="70%" rowspan="2" align="center">R&eacute;sultats </td>
        <td width="10%" rowspan="2" align="center">&nbsp;Unit&eacute;&nbsp;</td>
        <?php foreach($tableauAnnee as $anp){?>
        <?php if($anp==$annee){ ?>
        <td colspan="3"><div align="center">Fin p&eacute;riode <?php echo $anp; ?></div></td>
        <?php }else{ ?>
        <td colspan="3"><div align="center"><?php echo $anp; ?></div></td>
        <?php } ?>
        <?php } ?>
        <td width="2%" rowspan="2" align="center" bgcolor="#000000">&nbsp;</td>
        <td width="10%" colspan="3" align="center" bgcolor="#999999"><strong>Cumulatif</strong> </td>
      </tr>
      <tr class="titrecorps2">
        <?php foreach($tableauAnnee as $anp){?>
        <?php if($anp==$annee){ ?>
        <td><span class="Style22">&nbsp;PTBA</span></td>
        <td><span class="Style22">&nbsp;R&eacute;alis&eacute;</span></td>
        <td><span class="Style22">&nbsp;<span class="Style19">%PTBA</span></span></td>
        <?php }else{ ?>
        <td colspan="3"><span class="Style22">&nbsp;R&eacute;alis&eacute;</span></td>
        <?php } } ?>
        <td width="10%" align="center" bgcolor="#999999"><strong>Objectifs DCP</strong> </td>
        <td width="10%" align="center" bgcolor="#999999"><strong>R&eacute;alis&eacute;</strong> </td>
        <td width="10%" align="center" bgcolor="#999999"><span class="Style22">&nbsp;<span class="Style19">%DCP</span></span></td>
      </tr>
      <?php $beneficiaire_array = $cmp_array = $data_array = array(); $state = 0; if($totalRows_ind>0) {$i=0; $p1="j"; ?>
      <?php  $p11="j"; $p111="j"; $k=1; do {  $tot_cib=$tot_real=0; $val_real_an=$val_cib=$val_real=0; $cible_deno=$cible_denos=1; $cible_nums=$cible_num=0;?>

      <?php  if($p11!=$row_ind['code'] && $row_ind['id']>0) {?>
	    
	  
      <tr <?php  echo 'bgcolor="#D2E2B1"'; ?>>
        <td align="center" colspan="23"><div align="left">
          <?php  if($p11!=$row_ind['code']) {echo "<b>Sous composante ".$row_ind['code'].":</b> ".$row_ind['intitule']; $cmp_array[$row_ind['code']]["value"]["title"] = "Sous composante ".$row_ind['code'].": ".$row_ind['intitule']; $state++; }$p11=$row_ind['code']; ?>
        </div></td>
      </tr>
      <?php } ?>
      <?php  if($p1!=$row_ind['code_groupe'] ) { $k++; ?>
      <tr <?php  echo 'bgcolor="#CCCCCC"'; ?>>
        <td align="center" colspan="23"><div align="left">
          <?php  if($p1!=$row_ind['code_groupe']) {echo $row_ind['nom_groupe']; $i=0; $beneficiaire_array[$row_ind['code_groupe']]["title"] = $row_ind['nom_groupe']; }$p1=$row_ind['code_groupe'];  ?>
        </div></td>
      </tr>
      <?php }$p1=$row_ind['code_groupe']; ?>
      <tr <?php if($i%2==0) echo 'bgcolor="#F9F9F7"'; $i=$i+1;?>>
        <td width="70%"><div align="left" class="Style51"><?php echo $row_ind['intitule_indicateur_sygri_fida']; $data_array[] = $row_ind['intitule_indicateur_sygri_fida']; //echo   $req_ind_ref_array[$row_ind["referentiel"]]?></div>
              <div align="left" class="Style51"> </div></td>
        <td width="10%"><div align="center">
          <?php  //if(isset($unite_ind_ref_array[$row_ind['referentiel']])){ echo " (".$unite_ind_ref_array[$row_ind['referentiel']].")"; $data_array[] = $unite_ind_ref_array[$row_ind['referentiel']]; } ?>
		    <?php  if(isset($liste_referentiel_sygri_array[$row_ind["id_sygri"]]) && isset($unite_ind_ref_array[$liste_referentiel_sygri_array[$row_ind["id_sygri"]]])){ echo " (".$unite_ind_ref_array[$liste_referentiel_sygri_array[$row_ind["id_sygri"]]].")"; $data_array[] = $unite_ind_ref_array[$liste_referentiel_sygri_array[$row_ind["id_sygri"]]]; } else {echo "ND"; $data_array[] = "ND";} ?>
        </div></td>
        <?php
							
					   foreach($tableauAnnee as $anp){ ?>
<?php if($anp==$annee){ ?>
        <td nowrap="nowrap"><div align="center">
                    <?php 
						if(isset($cible_ind_ref_array[$row_ind["referentiel"]][$anp])) {
						echo number_format($cible_ind_ref_array[$row_ind["referentiel"]][$anp], 0, ',', ' '); $val_cib=$cible_ind_ref_array[$row_ind["referentiel"]][$anp]; $data_array[] = $cible_ind_ref_array[$row_ind["referentiel"]][$anp];}
						 else{ echo ""; $data_array[] = ""; } 

						?>
        </div></td>
        <td nowrap="nowrap"><div align="center">
          <?php 
							 
							   								  
								  if(isset($suivief_an_ref_array[$row_ind["referentiel"]][$anp]))
								  {
								   $val_real_an=$val_real=$suivief_an_ref_array[$row_ind["referentiel"]][$anp];
								  } elseif(isset($realise_array_sygri[$row_ind["referentiel"]][$anp]))
								  {
							    $val_real_an=$val_real=$realise_array_sygri[$row_ind["referentiel"]][$anp];
								}  elseif(isset($realise_arrayp[$row_ind["referentiel"]][$anp]))
								{
								 $val_real_an=$val_real=$realise_arrayp[$row_ind["referentiel"]][$anp];
								}
						
				
							?>
          <?php if($val_real>0) { echo number_format($val_real, 0, ',', ' '); $tot_real=$tot_real+$val_real; $data_array[] = $tot_real;}  else { $data_array[] = "";}  ?>
        </div></td>
        <td nowrap="nowrap"><div align="center"> <span class="Style32">
          <?php if($val_cib>0 && $val_real>0) {echo number_format(100*$val_real/$val_cib, 0, ',', ' ')." %"; $data_array[] = ($val_cib>0 && $val_real>0)?number_format(100*$val_real/$val_cib, 0, ',', ' ')." %":'';} else {  $data_array[] = "";}$val_cib=$val_real=0; ?>
        </span></div></td>
        <?php  }else{ 
		
							 
							   								  
								  if(isset($suivief_an_ref_array[$row_ind["referentiel"]][$anp]))
								  {
								  $val_real=$suivief_an_ref_array[$row_ind["referentiel"]][$anp];
								  } elseif(isset($realise_array_sygri[$row_ind["referentiel"]][$anp]))
								  {
							    $val_real=$realise_array_sygri[$row_ind["referentiel"]][$anp];
								}  elseif(isset($realise_arrayp[$row_ind["referentiel"]][$anp]))
								{
								 $val_real=$realise_arrayp[$row_ind["referentiel"]][$anp];
								}
						
				
							?>
        
		
        <td nowrap="nowrap" colspan="3"><div align="center">  <?php if($val_real>0) { echo number_format($val_real, 0, ',', ' '); $tot_real=$tot_real+$val_real; }  else "";  $val_cib=$val_real=0; ?></div></td>
<?php } } ?>
        <td width="2%" rowspan="nowrap" align="center" bgcolor="#000000"><?php //$data_array[] = 0; ?></td>
        <td width="10%" nowrap="nowrap"><div align="center" class="Style51">
          <?php if(isset($row_ind['cible_rmp']) && ($row_ind['cible_rmp']!=$row_ind['cible_projet'] && $row_ind['cible_rmp']>0)){ echo number_format($row_ind['cible_rmp'], 0, ',', ' '); $data_array[] = $row_ind['cible_rmp']; } else{ echo number_format($row_ind['cible_projet'], 0, ',', ' '); $data_array[] = number_format($row_ind['cible_projet'], 0, ',', ' '); } ?>
        </div></td>
        <td width="10%" nowrap="nowrap"><div align="center"><?php if($tot_real>0){ echo number_format($tot_real, 0, ',', ' '); $data_array[] = $tot_real; } else $data_array[]=""; ?> </div></td>
        <td width="10%" nowrap="nowrap"><div align="center">
          <?php 
								 if($tot_real>0 && isset($row_ind['cible_rmp']) && ($row_ind['cible_rmp']!=$row_ind['cible_rmp'] && $row_ind['cible_rmp']>0)){ echo number_format(100*$tot_real/$row_ind['cible_rmp'], 0, ',', ' ')." %"; $data_array[] = number_format(100*$tot_real/$row_ind['cible_rmp'], 0, ',', ' ')." %"; }
				elseif($tot_real>0 && isset($row_ind['cible_projet']) && $row_ind['cible_projet']>0){ echo number_format(100*$tot_real/$row_ind['cible_projet'], 0, ',', ' ')." %";  $data_array[] = number_format(100*$tot_real/$row_ind['cible_projet'], 0, ',', ' ')." %"; } else $data_array[] = "";
								 //else echo "n/a";

								 ?>
        </div></td>
<?php
 //$data_array[] = "t";
 if($tot_real>0 ){ $data_array[] = $tot_real-$val_real_an; } else $data_array[]=""; 
if($state==0) $beneficiaire_array[$row_ind['code_groupe']]["value"][] = $data_array; else $cmp_array[$row_ind['code']]["value"][] = $data_array; $data_array = array();
?>
      </tr>
      <?php } while ($row_ind = mysql_fetch_assoc($ind));  mysql_free_result($ind);?>
      <tr>
        <td colspan="5"><div align="center" class="Style2">
          <?php if(!$totalRows_ind>0) echo "<br />Aucun indicateur enregistr&eacute;";?>
        </div></td>
      </tr>
    </table></td>
  </tr>
  <?php } ?>
</table>

<?php
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_scomposante = "SELECT * FROM activite_projet WHERE niveau=1 and projet='".$_SESSION["clp_projet"]."' order by code";
$scomposante  = mysql_query($query_scomposante , $pdar_connexion) or die(mysql_error());
$row_scomposante  = mysql_fetch_assoc($scomposante);
$totalRows_scomposante  = mysql_num_rows($scomposante);
$cp_array = array();
if($totalRows_scomposante>0){
  do{ $cp_array[$row_scomposante["code"]]=$row_scomposante["intitule"]; } while($row_scomposante  = mysql_fetch_assoc($scomposante));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_scomposante = "SELECT * FROM activite_projet WHERE niveau=2 and projet='".$_SESSION["clp_projet"]."' order by code";
$scomposante  = mysql_query($query_scomposante , $pdar_connexion) or die(mysql_error());
$row_scomposante  = mysql_fetch_assoc($scomposante);

//Suivi unique
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_suivi_ind_ref = " SELECT * FROM  referentiel_indicateur";
$suivi_ind_ref  = mysql_query($query_suivi_ind_ref , $pdar_connexion) or die(mysql_error());
$row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref);
$totalRows_suivi_ind_ref  = mysql_num_rows($suivi_ind_ref);
$suivi_ind_ref_array = array();
do{ //$feuille=$row_suivi_ind_ref["feuille"]; $col=$row_suivi_ind_ref["colonne"];

   $suivi_ind_ref_array[$row_suivi_ind_ref["id_ref_ind"]] = $row_suivi_ind_ref["id_ref_ind"];


}while($row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref));

//Suivi Somme Moyenne
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_suivi_ind_ref = " SELECT * FROM  referentiel_indicateur, soutien_indicateur_sygri2, calcul_indicateur_simple_ref  where id_ref_ind=referentiel and indicateur_ref=id_ref_ind and mode_calcul<>'Ratio'";
$suivi_ind_ref  = mysql_query($query_suivi_ind_ref , $pdar_connexion) or die(mysql_error());
$row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref);
$totalRows_suivi_ind_ref  = mysql_num_rows($suivi_ind_ref);

$suivi_somme_ind_ref_array = $suivi_moyenne_ind_ref_array = array();

do{ $ref=$row_suivi_ind_ref["indicateur_ref"]; $ind=explode(",",$row_suivi_ind_ref["indicateur_simple"]); $formule=$row_suivi_ind_ref["formule_indicateur_simple"];
if($formule=="Somme"){
 foreach($ind as $indicateur){
   if(isset($suivi_ind_ref_array[$indicateur])){
      //foreach($suivi_ind_ref_array as $indicateur_referentiel){
        if(isset($suivi_somme_ind_ref_array[$ref])) $suivi_somme_ind_ref_array[$ref]+=$suivi_ind_ref_array[$indicateur];
        else $suivi_somme_ind_ref_array[$ref]=$suivi_ind_ref_array[$indicateur];

      // }
   }
 }         }

if($formule=="Moyenne"){
 foreach($ind as $indicateur){
   if(isset($suivi_ind_ref_array[$indicateur])){
      //foreach($suivi_ind_ref_array as $indicateur_referentiel){
        if(isset($suivi_moyenne_ind_ref_array[$ref])) $suivi_moyenne_ind_ref_array[$ref]+=$suivi_ind_ref_array[$indicateur];
        else $suivi_moyenne_ind_ref_array[$ref]=$suivi_ind_ref_array[$indicateur];

       //}
   }
 }

if(isset($suivi_ind_ref_array[$indicateur])){
//foreach($suivi_ind_ref_array as $indicateur_referentiel){
      if((count($ind)-1)>0) $suivi_moyenne_ind_ref_array[$ref]=$suivi_moyenne_ind_ref_array[$ref]/(count($ind)-1); //}
       }      }

}while($row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref)); //print_r($suivi_moyenne_ind_ref_array); exit;


//Suivi Ratio
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_suivi_ind_ref = " SELECT * FROM  referentiel_indicateur, soutien_indicateur_sygri2, ratio_indicateur_ref  where id_ref_ind=referentiel and indicateur_ref=id_ref_ind and mode_calcul='Ratio'";
$suivi_ind_ref  = mysql_query($query_suivi_ind_ref , $pdar_connexion) or die(mysql_error());
$row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref);
$totalRows_suivi_ind_ref  = mysql_num_rows($suivi_ind_ref);

$liste_num_ratio_array = array();
$liste_deno_ratio_array = array();

do{
$ref=$row_suivi_ind_ref["indicateur_ref"]; $numerateur=$row_suivi_ind_ref["numerateur"]; $denominateur=$row_suivi_ind_ref["denominateur"]; $coef=$row_suivi_ind_ref["coefficient"];

$liste_num_ratio_array[$ref]=$numerateur;
$liste_deno_ratio_array[$ref]=$denominateur;

}while($row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref)); //print_r($cible_ind_ref_array); exit;

?>
<h4 align="center">Indicateurs de 2<sup>&egrave;me</sup> niveau SYGRI en <?php echo $annee;?></h4>

<table border="1" align="center" cellspacing="0" width="100%">
  <tr class="titrecorps2">
        <td align="center" width="50%">R&eacute;sultats </td>
        <td align="center">&nbsp;Objectifs DCP &nbsp;</td>
        <td align="center">&nbsp;Taux<br />pond&eacute;ration&nbsp;</td>
        <td align="center">R&eacute;alis&eacute; </td>
        <td align="center">%Exe</td>
        <td align="center">Bar&egrave;me</td>
        <td align="center">&nbsp;</td>
      </tr>
<?php $cmp1_array = $data_array = array(); if($totalRows_scomposante>0) {$o2=0; $p111="j"; do { ?>
<?php
  $id_scp=$row_scomposante['code'];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
 $query_ind = "SELECT id_sygri, id_indicateur_sygri_niveau2_projet, cible, proportion, id_indicateur_soutien, soutien_indicateur_sygri2.referentiel, intitule_indicateur_soutien, code_ind_sygri2  FROM activite_projet, indicateur_sygri2_projet LEFT JOIN soutien_indicateur_sygri2 ON indicateur_sygri_niveau2=id_indicateur_sygri_niveau2_projet WHERE niveau=2 and sous_composante=code and sous_composante='$id_scp' order by code_ind_sygri2";
  $ind  = mysql_query($query_ind , $pdar_connexion) or die(mysql_error());
  $row_ind  = mysql_fetch_assoc($ind);
  $totalRows_ind  = mysql_num_rows($ind);
?>
  <?php if($totalRows_ind>0) {$i=0; $p1="j"; ?>
  <?php  if($p111!=substr($row_scomposante['code'],0,3)) {?>
      <tr <?php  echo 'bgcolor="#CCCCCC"'; ?>>
        <td colspan="7" align="center"><div align="left">
          <?php  if($p111!=$row_scomposante['code']) {echo "<b>Composante ".substr($row_scomposante['code'],0,1).":</b> ".(isset($cp_array[substr($row_scomposante['code'],0,1)])?$cp_array[substr($row_scomposante['code'],0,1)]:"ND"); $cmp1_array[$row_scomposante['code']]["title"] = "Composante ".substr($row_scomposante['code'],0,1).": ".(isset($cp_array[substr($row_scomposante['code'],0,1)])?$cp_array[substr($row_scomposante['code'],0,1)]:"ND"); }$p111=substr($row_scomposante['code'],0,1); ?>
        </div></td>
      </tr>
      <?php } ?>
  <tr <?php if($o2%2==0) echo 'bgcolor="#D2E2B1"'; $o2=$o2+1;?>>
    <td colspan="7" valign="top"><span class="Style51"><?php echo "<b>Sous composante ".$row_scomposante['code'].":</b> ".$row_scomposante['intitule']; $cmp1_array[$row_scomposante['code']]["value"]["title"] = "Sous composante ".$row_scomposante['code'].": ".$row_scomposante['intitule']; ?></span></td>
  </tr>
      <?php do { ?>
      <?php								//semestre courant
      $indics=$row_ind['id_indicateur_soutien'];
      $indicsygri=$row_ind['id_indicateur_sygri_niveau2_projet'];
      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $query_resultat_soutien = "SELECT id_suivi_soutien_indicateur, resultat FROM suivi_indicateur_soutien where annee='$annee' and indicateur_soutien='$indics'";
      $resultat_soutien  = mysql_query($query_resultat_soutien , $pdar_connexion) or die(mysql_error());
      $row_resultat_soutien = mysql_fetch_assoc($resultat_soutien );
      $totalRows_resultat_soutien = mysql_num_rows($resultat_soutien );

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $query_tresultat_soutien = " SELECT sum((if(resultat>cible,proportion/100, (resultat / cible)*proportion/100) ) ) AS rtotal
      FROM soutien_indicateur_sygri2, suivi_indicateur_soutien
      WHERE annee =$annee AND indicateur_soutien = id_indicateur_soutien AND indicateur_sygri_niveau2 =$indicsygri";
      $tresultat_soutien  = mysql_query($query_tresultat_soutien , $pdar_connexion) or die(mysql_error());
      $row_tresultat_soutien = mysql_fetch_assoc($tresultat_soutien );
      $totalRows_tresultat_soutien = mysql_num_rows($tresultat_soutien );
      ?>
      <?php  if($p1!=$row_ind['code_ind_sygri2']) {?>
      <tr bgcolor="#666633">
        <td colspan="3" align="center" bgcolor="#666633"><div align="left" class="Style23">
          <?php  if($p1!=$row_ind['code_ind_sygri2']) { if(isset($liste_indicateur_sygri_array[$row_ind["id_sygri"]])) {echo $liste_indicateur_sygri_array[$row_ind["id_sygri"]]; $data_array[] =  $liste_indicateur_sygri_array[$row_ind["id_sygri"]];} else {echo $row_ind["id_sygri"]; $data_array[] =  $row_ind["id_sygri"];} $data_array[] = -1; $cmp1_array[$row_scomposante['code']]["value"][] = $data_array; $data_array = array(); $i=0; }$p1=$row_ind['code_ind_sygri2']; ?>
        </div></td>
        <td align="center" bgcolor="#666633" class="Style53">&nbsp;</td>
        <td align="center" bgcolor="#666633"><span class="Style53"><?php echo number_format(100*$row_tresultat_soutien['rtotal'], 0, ',', ' '); ?></span></td>
        <td align="center" bgcolor="#666633"><span class="Style53">
          <?php if(((100*$row_tresultat_soutien['rtotal'])/16.5)<1 && ((100*$row_tresultat_soutien['rtotal'])/16.5)>0) echo 1; else echo number_format((100*$row_tresultat_soutien['rtotal'])/16.5, 0, ',', ' '); ?>
        </span></td>
        <td align="center" bgcolor="#666633">&nbsp;</td>
      </tr>
      <?php } ?>
      <tr <?php if($i%2==0) echo 'bgcolor="#F9F9F7"'; $i=$i+1;?>>
        <td width="50%"><div align="left" class="Style51"><?php echo $row_ind['intitule_indicateur_soutien']; $data_array[] =  $row_ind['intitule_indicateur_soutien']; ?></div>
              <div align="left" class="Style51"> </div></td>
        <td ><div align="center"><span class="Style51"><?php echo $row_ind['cible']; ?>
                    <?php if(isset($unite_ind_ref_array[$row_ind["referentiel"]])) echo " (".$unite_ind_ref_array[$row_ind["referentiel"]].")"; ?>
        </span></div></td>
        <td ><div align="center"><span class="Style51"><?php echo $row_ind['proportion']; $data_array[] = $row_ind['proportion']; ?>%</span></div></td>
        <td ><div align="center"><strong><strong><span class="Style16">
          <?php if(isset($suivi_ind_ref_array[$row_ind["referentiel"]])) { echo $suivi_ind_ref_array[$row_ind["referentiel"]]; }elseif(isset($suivi_somme_ind_ref_array[$row_ind["referentiel"]])){ echo $suivi_somme_ind_ref_array[$row_ind["referentiel"]]; } ?>
          </span><strong><span class="Style16">

          </span></strong><span class="Style16"> </span></strong></strong></div></td>
        <td ><div align="center">
          <?php
							   if(isset($suivi_ind_ref_array[$row_ind["referentiel"]]) && $row_ind['cible']>0)
							   { $tex=100*$suivi_ind_ref_array[$row_ind["referentiel"]]/$row_ind['cible'];}
							    elseif(isset($suivi_ind_ref_array[$row_ind["referentiel"]]) && $suivi_ind_ref_array[$row_ind["referentiel"]]>$row_ind['cible'])
							   { $tex=100;}
							   if(isset($suivi_ind_ref_array[$row_ind["referentiel"]])) {if($tex>100) $tex=100; echo number_format($tex, 0, ',', ' ')." %";} ?>
        </div></td>
        <td >&nbsp;</td>
        <td align="center"></td>
      </tr>
      <?php $cmp1_array[$row_scomposante['code']]["value"][] = $data_array; $data_array = array();  } while ($row_ind = mysql_fetch_assoc($ind)); ?>
      <tr>
        <td colspan="7"><div align="center" class="Style2">
          <?php if(!$totalRows_ind>0) echo "Aucun indicateur enregistr&eacute;: ";?>
        </div></td>
      </tr>
  <?php } ?>
  <?php } while ($row_scomposante = mysql_fetch_assoc($scomposante)); ?>
  <?php } else {?>
  <tr>
    <td colspan="7" nowrap="nowrap"><div align="center"><em><strong>Aucune composante enregistr&eacute;e </strong></em></div></td>
  </tr>
  <?php } ?>
</table>

<?php
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_indicateur = "SELECT * FROM liste_indicateur_sygri, indicateur_sygri3_projet where id_indicateur_sygri_fida=id_sygri and indicateur_sygri3_projet.projet='".$_SESSION["clp_projet"]."' order by indicateur_sygri3_projet.ordre asc";
//echo $query_liste_indicateur;
//exit;
$liste_indicateur  = mysql_query($query_liste_indicateur , $pdar_connexion) or die(mysql_error());
$row_liste_indicateur = mysql_fetch_assoc($liste_indicateur );
$totalRows_liste_indicateur = mysql_num_rows($liste_indicateur );

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
$query_ind_cmr_og = "SELECT referentiel, valeur_reelle, valeur_reelle1 FROM indicateur_objectif_global_cmr, indicateur_objectif_global where id_indicateur_objectif_global=indicateur_og order by id_indicateur_objectif_global, code_cmr, id_indicateur";
$ind_cmr_og  = mysql_query($query_ind_cmr_og , $pdar_connexion) or die(mysql_error());
$row_ind_cmr_og  = mysql_fetch_assoc($ind_cmr_og);
$totalRows_ind_cmr_og  = mysql_num_rows($ind_cmr_og);
$valeur_reel_ind_array = $valeur_reel_ind_array1 = array();
do{  $valeur_reel_ind_array[$row_ind_cmr_og["referentiel"]] = $row_ind_cmr_og["valeur_reelle"];
$valeur_reel_ind_array1[$row_ind_cmr_og["referentiel"]] = $row_ind_cmr_og["valeur_reelle1"];
}while($row_ind_cmr_og = mysql_fetch_assoc($ind_cmr_og));
?>
<h4 align="center">Indicateurs de 3<sup>&egrave;me</sup> niveau SYGRI en <?php echo $annee;?></h4>

<table width="100%" border="0" align="center"  cellpadding="0" cellspacing="0" >
  <tr>
    <td><table width="98%" border="1" cellspacing="0" >
      <?php $t=0;  if($totalRows_liste_indicateur>0) { ?>
      <tr class="titrecorps2">
        <td>Indicateurs </td>
        <td><span class="Style22">Unit&eacute;</span></td>
        <td nowrap="nowrap"><span class="Style22">Situation de r&eacute;f&eacute;rence</span></td>
        <td nowrap="nowrap"><span class="Style22">Mi parcours</span></td>
        <td><span class="Style22">Ach&egrave;vement</span></td>
        <td nowrap="nowrap"><span class="Style22">Objectifs DCP</span></td>
        </tr>

      <?php $cmp2_array = $data_array = array(); $p1="j"; $t=0; $i=0;do { ?>
      <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"';  $t=$t+1; $i=$i+1;?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='#ECF0DF';">
        <td ><u><span class="Style46"><?php echo $row_liste_indicateur['intitule_indicateur_sygri_fida']; $data_array[] = $row_liste_indicateur['intitule_indicateur_sygri_fida']; ?></span></u> </td>
        <td align="center"><span class="Style46"><?php echo (isset($unite_ind_ref_array[$row_liste_indicateur['referentiel']]))?$unite_ind_ref_array[$row_liste_indicateur['referentiel']]:""; $data_array[] = (isset($unite_ind_ref_array[$row_liste_indicateur['referentiel']]))?$unite_ind_ref_array[$row_liste_indicateur['referentiel']]:"" ?></span></td>

        <td ><div align="center">
            <div align="center">
              <?php echo $row_liste_indicateur["reference"]; $data_array[] = $row_liste_indicateur["reference"];  ?>
             </div>
        </div></td>

        <td><div align="center"><strong><strong><span class="Style16">
            <?php if(isset($valeur_reel_ind_array1[$row_liste_indicateur["referentiel"]])) {echo $valeur_reel_ind_array1[$row_liste_indicateur["referentiel"]]; ?>
          &nbsp;<?php echo (isset($unite_ind_ref_array[$row_liste_indicateur['referentiel']]))?$unite_ind_ref_array[$row_liste_indicateur['referentiel']]:""; $data_array[] = $valeur_reel_ind_array1[$row_liste_indicateur["referentiel"]]; } else $data_array[] = ""; ?> </span><span class="Style16"> </span><strong><span class="Style16"> </span></strong><span class="Style16">

        </span></strong></strong></div></td>

        <td><div align="center"><strong><strong><span class="Style16">
            <?php if(isset($valeur_reel_ind_array[$row_liste_indicateur["referentiel"]])) {echo $valeur_reel_ind_array[$row_liste_indicateur["referentiel"]]; ?>
          &nbsp;<?php echo (isset($unite_ind_ref_array[$row_liste_indicateur['referentiel']]))?$unite_ind_ref_array[$row_liste_indicateur['referentiel']]:""; $data_array[] = $valeur_reel_ind_array[$row_liste_indicateur["referentiel"]]; } else $data_array[] = ""; ?> </span><span class="Style16"> </span><strong><span class="Style16"> </span></strong><span class="Style16">

        </span></strong></strong></div></td>
        <td><div align="center">
<?php echo $row_liste_indicateur['cible']; $data_array[] = $row_liste_indicateur['cible']; ?> </div></td>
        </tr>
      <?php $cmp2_array[] = $data_array; $data_array = array(); } while ($row_liste_indicateur = mysql_fetch_assoc($liste_indicateur)); ?>
      <?php } else echo "<h3>Aucune valeur mesur&eacute;e</h3>" ;?>
    </table></td>
  </tr>
</table>  </div>


<?php
$col = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","R","T","U","V","W","X","Y","Z");
function frenchMonthName($monthnum)
{
  $monthnum = intval($monthnum);
      $armois=array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
      if ($monthnum>0 && $monthnum<13) {
          return $armois[$monthnum];
      } else {
          return $monthnum;
      }
}
function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}
require_once 'Classes/PHPExcel.php';
$objPHPExcel = new PHPExcel();
//Feuille 1
$objPHPExcel->createSheet();
// Set properties
$objPHPExcel->getProperties()->setCreator("RUCHE")
				->setLastModifiedBy("SSE RUCHE PARSAT")
				->setTitle("Indicateurs SYGRI du 1er Niveau")
				->setSubject("Indicateurs SYGRI")
				->setDescription("Indicateurs SYGRI du 1er, 2ème et 3ème Niveau, generated by RUCHE.")
				->setKeywords("RUCHE Indicateurs SYGRI")
				->setCategory("Indicateurs SYGRI");
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Données Clés');

$objPHPExcel->getActiveSheet()        // Format as date and time
    ->getStyle('B11')
    ->getNumberFormat()
    ->setFormatCode('DD-mmm');
$ExcelDateValue = PHPExcel_Shared_Date::stringToExcel(date('d/m/Y'));
$objPHPExcel->setActiveSheetIndex(0)
            ->mergeCells('A1:F1')
            ->setCellValue('B2', 'Jour')
            ->setCellValue('C2', 'Mois')
            ->setCellValue('D2', 'Année')
            ->setCellValue('A3', 'Date de soumission')
            ->setCellValue('B3', date('d'))
            ->setCellValue('C3', frenchMonthName(date('m')))
            ->setCellValue('D3', date('Y'))
            ->setCellValue('A5', 'Nom pays')
            ->setCellValue('B5', 'Tchad')
            ->mergeCells('B5:E5')
            ->setCellValue('A7', 'Nom projet')
           ->setCellValue('B7', utf8_decode($_SESSION["clp_projet_nom"])) 
            ->mergeCells('B7:E7')
            ->setCellValue('A9', 'Année en cours')
            ->setCellValue('B9', 'PY'.date('y'))
            ->setCellValue('A11', 'Fin année fiscale')
            ->setCellValue('B11', "31-déc");

$objPHPExcel->getActiveSheet()->getStyle("A1:A11")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("B2:D2")->getFont()->setItalic(true);
$styleArray = array(
  'alignment' => array(
  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
  ),
);
$objPHPExcel->getActiveSheet()->getStyle("A1:A11")->applyFromArray($styleArray);
$styleArray = array(
  'alignment' => array(
  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
  ),
);
cellColor("A1:A11",'A9A9A9');
cellColor("A2:F12",'7FFFD4');
cellColor("B3:D3",'FFFACD'); cellColor("B5",'FFFACD'); cellColor("B7",'FFFACD'); cellColor("B9",'FFFACD');
cellColor("B11",'FFFACD');
$objPHPExcel->getActiveSheet()->getStyle("B1:F11")->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18.71);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(7.71);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(7.71);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(7.71);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(32.29);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(2.29);
$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THICK
    )
  ),
);
$objPHPExcel->getActiveSheet()->getStyle("B3")->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("C3")->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("D3")->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("B5:E5")->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("B7:E7")->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("B9")->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("B11")->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale(140);

//Feuille 2
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1);
$objPHPExcel->getActiveSheet()->setTitle('Premier Niveau');

$objPHPExcel->setActiveSheetIndex(1)
            ->mergeCells('A4:B5')
            ->setCellValue('A3', 'RÉSULTATS DE PREMIER NIVEAU')
            ->setCellValue('C4', 'Résultats')
            ->mergeCells('C4:C5')
            ->setCellValue('D4', 'Unité')
            ->mergeCells('D4:D5');
            $j = 4; for($i=$tableauAnnee[0]; $i<$tableauAnnee[count($tableauAnnee)-1]; $i++)
            {
              $objPHPExcel->setActiveSheetIndex(1)
              ->setCellValue($col[$j]."4", $i)
              ->setCellValue($col[$j]."5", 'Réalisé');
              //->mergeCells($col[$j]."4".":".$col[$j]."5");
              $j++;
            }
$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue($col[$j].'4', 'Fin de la période'.$annee)
            ->setCellValue($col[$j].'5', 'PTBA');
            $j++;
$objPHPExcel->setActiveSheetIndex(1)
            ->mergeCells($col[$j-1].'4:'.$col[$j+1].'4')
            ->setCellValue($col[$j].'5', 'Réalisé '.$annee);
            $j++;
$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue($col[$j].'5', '% de PTBA');
            $j++;
$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue($col[$j]."4", 'Cumulatif')
            ->mergeCells($col[$j]."4".":".$col[$j+2]."4")
            ->setCellValue($col[$j]."5", 'Préévaluation');
            $j++;
$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue($col[$j]."5", 'Réalisé');
            $j++;
$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue($col[$j]."5", '% Pré-évaluation');
            $j++;
$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue($col[$j]."4", 'Total des années précédentes')
            ->mergeCells($col[$j]."4".":".$col[$j]."5")
            ->mergeCells("A3".":".$col[$j]."3");
            $limite = $j;

$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  ),
  'alignment' => array(
  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
  ),
);
cellColor("A3:".$col[$j]."3",'D3D3D3');
cellColor("A4:".$col[$j]."5",'DCDCDC');
$objPHPExcel->getActiveSheet()->getStyle("A3:".$col[$j]."5")->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("A3:".$col[$j]."5")->getFont()->setBold(true);

//Données bénéficiares
$k = 6;
if(isset($beneficiaire_array) && count($beneficiaire_array)>0)
{

  foreach($beneficiaire_array as $a=>$b)
  {
    if(isset($b["title"]) && !empty($b["title"]))
    {
      $objPHPExcel->setActiveSheetIndex(1)
                  ->setCellValue("A$k", utf8_encode(trim($b["title"])))
                  ->mergeCells("A4:B5");
      $objPHPExcel->getActiveSheet()->getStyle("A$k".":"."B$k")->getFont()->setBold(true);
                  $j = 2;
    }
    if(isset($b["value"]) && is_array($b["value"]))
    {
      foreach($b["value"] as $c=>$d)
      {
        if(is_array($d))
        {
          foreach($d as $e=>$f)
          {
            $objPHPExcel->setActiveSheetIndex(1)
                        ->setCellValue($col[$j].$k, utf8_encode(trim($f)));
                        $j++;
          }
          if(in_array($annee,$tableauAnnee) && $annee>$tableauAnnee[0])
          {
           // $objPHPExcel->setActiveSheetIndex(1)
                       // ->setCellValue($col[$j].$k, "=SUM(".$col[4].$k.":".$col[count($tableauAnnee)+2].$k.")");
          }
          $k++; $j = 2;
        }
      }
    }
  }

$objPHPExcel->setActiveSheetIndex(1)
            ->mergeCells("A6:B".($k-1));

}

$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue("A".$k, 'Composante')
            ->setCellValue("B".$k, 'Sous-composante');
$objPHPExcel->getActiveSheet()->getStyle("A$k:"."B$k")->getFont()->setBold(true);
cellColor("A$k:".$col[$limite].$k,'D3D3D3');  $k++;

$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue("A".$k, 'Nom de la composante')
            ->setCellValue("B".$k, 'Nom de la sous-composante');
$objPHPExcel->getActiveSheet()->getStyle("A$k:"."B$k")->getFont()->setBold(true);
cellColor("A$k",'FFFF66'); cellColor("B$k",'FFFF66');

            $j = 2; $k++;

//Données Composante/S-composante
$first = 0; $mg = $k; $lg = $k;
if(isset($cmp_array) && count($cmp_array)>0)
{
  foreach($cmp_array as $a=>$b)
  {
  $ccp=substr($a,0,1);
    //composante
    $objPHPExcel->setActiveSheetIndex(1)
                  ->setCellValue("A$k", "Composante $ccp: ".(isset($cp_array[substr($a,0,1)])?utf8_encode(trim($cp_array[substr($a,0,1)])):""));
                  //->mergeCells("A$k".":"."B$k");
      //$objPHPExcel->getActiveSheet()->getStyle("A$k".":"."B$k")->getFont()->setBold(true);

      if($first>0)
      {
        $objPHPExcel->setActiveSheetIndex(1)
            ->mergeCells("A".($lg).":"."A".($mg-1));
        $mg = $k; $first = 0; $lg = $k;
      }

    /*foreach($b as $c=>$d){
    if(isset($d["title"]) && !empty($d["title"]))
    {
      $objPHPExcel->setActiveSheetIndex(1)
                  ->setCellValue("A$k", utf8_encode(trim($d["title"])));
                  //->mergeCells("A$k".":"."B$k");
      $objPHPExcel->getActiveSheet()->getStyle("A$k".":"."B$k")->getFont()->setBold(true);
                  $j = 1; //$k++;
      if($first>0)
      {
        $objPHPExcel->setActiveSheetIndex(1)
            ->mergeCells("A".($lg).":"."A".($mg-1));
        $mg = $k; $first = 0; $lg = $k;
      }
    }  } */

    if(isset($b["value"]) && is_array($b["value"]))
    {
      foreach($b["value"] as $c=>$d)
      {
        if(is_array($d))
        {
          foreach($d as $e=>$f)
          {
            $objPHPExcel->setActiveSheetIndex(1)
                        ->setCellValue($col[$j].$k, utf8_encode(trim($f)));
                        $j++;
          }
          if(in_array($annee,$tableauAnnee) && $annee>$tableauAnnee[0])
          {
           // $objPHPExcel->setActiveSheetIndex(1)
                       // ->setCellValue($col[$j].$k, "=SUM(".$col[4].$k.":".$col[count($tableauAnnee)+2].$k.")");
          }
          $k++; $j = 2;
        }
        else
        {
          $objPHPExcel->setActiveSheetIndex(1)
                      ->setCellValue("B$k", utf8_encode(trim($d)))
                      ->mergeCells("B$k".":"."B".($k+count($b["value"])-2));
          $objPHPExcel->getActiveSheet()->getStyle("A$k".":"."B$k")->getFont()->setBold(true);
                      $j = 2; //$k++;
        }
      }
      $mg+= count($b["value"])-1; $first++;
    }
  }
  $objPHPExcel->setActiveSheetIndex(1)
              ->mergeCells("A".($lg).":"."A".($mg-1));
}

//Fin

$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(11);
$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  ),
  'alignment' => array(
 // 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
  ),
);
$objPHPExcel->getActiveSheet()->getStyle('A3:B'.$k)
    ->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle($col[$limite].'4')
    ->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A3:".$col[$limite].($k-1))->applyFromArray($styleArray);
foreach(range('D',$col[$limite-1]) as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
$objPHPExcel->getActiveSheet()->getColumnDimension($col[$limite])->setWidth(18);
$objPHPExcel->getActiveSheet()->removeRow(1); $objPHPExcel->getActiveSheet()->removeRow(1);

//Feuille 3
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(2);
$objPHPExcel->getActiveSheet()->setTitle('Deuxième Niveau');

$objPHPExcel->setActiveSheetIndex(2)
            ->mergeCells('A1:D1')
            ->setCellValue('A1', 'RÉSULTATS DU DEUXIÈME NIVEAU')
            ->setCellValue('A2', 'Composantes')
            ->setCellValue('B2', 'Sous-composante')
            ->setCellValue('C2', 'Résultat')
            ->setCellValue('D2', 'Barème');

$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  ),
  'alignment' => array(
  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
  ),
);
cellColor("A1",'D3D3D3');
cellColor("A2:D2",'DCDCDC');
$objPHPExcel->getActiveSheet()->getStyle("A1:D2")->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("A1:D2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(27);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(67);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);

$objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue("A3", 'Nom de la composante')
            ->setCellValue("B3", 'Nom de la sous-composante');
$objPHPExcel->getActiveSheet()->getStyle("A3:"."B3")->getFont()->setBold(true);
cellColor("A3",'FFFF66'); cellColor("B3",'FFFF66');

//Données Composante/S-composante
$k = 4; $first = 0; $mg = $k; $lg = $k;


if(isset($cmp1_array) && count($cmp1_array)>0)
{
  foreach($cmp1_array as $a=>$b)
  {
    if(isset($b["title"]) && !empty($b["title"]))
    {
      $objPHPExcel->setActiveSheetIndex(2)
                  ->setCellValue("A$k", utf8_encode(trim($b["title"])));
                  //->mergeCells("A$k".":"."B$k");
      $objPHPExcel->getActiveSheet()->getStyle("A$k".":"."B$k")->getFont()->setBold(true);
                  $j = 1; //$k++;
      if($first>0)
      {
        $objPHPExcel->setActiveSheetIndex(2)
            ->mergeCells("A".($lg).":"."A".($mg-1));
        $mg = $k; $first = 0; $lg = $k;
      }
    }
    if(isset($b["value"]) && is_array($b["value"]))
    {
      foreach($b["value"] as $c=>$d)
      {
        if(is_array($d))
        {
          foreach($d as $e=>$f)
          {
            $objPHPExcel->setActiveSheetIndex(2)
                        ->setCellValue($col[$j].$k, (($f==-1)?"":utf8_encode(trim($f))));
            if($f==-1) $objPHPExcel->getActiveSheet()->getStyle($col[($j-1)].$k)->getFont()->setBold(true);
                        $j++;
          }
          $k++; $j = 2;
        }
        else
        {
          $objPHPExcel->setActiveSheetIndex(2)
                      ->setCellValue("B$k", utf8_encode(trim($d)))
                      ->mergeCells("B$k".":"."B".($k+count($b["value"])-2));
          $objPHPExcel->getActiveSheet()->getStyle("A$k".":"."B$k")->getFont()->setBold(true);
                      $j = 2; //$k++;
        }
      }
      $mg+= count($b["value"])-1; $first++;
    }
  }
  $objPHPExcel->setActiveSheetIndex(2)
              ->mergeCells("A".($lg).":"."A".($mg-1));
}
$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  ),
  'alignment' => array(
  //'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
  ),
);
$objPHPExcel->getActiveSheet()->getStyle('A3:B'.$k)
    ->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A3:D".($k-1))->applyFromArray($styleArray);

//Feuille 4
$objPHPExcel->setActiveSheetIndex(3);
$objPHPExcel->getActiveSheet()->setTitle('Troisième Niveau');

$objPHPExcel->setActiveSheetIndex(3)
            ->mergeCells('A1:F1')
            ->setCellValue('A1', 'RÉSULTATS DU TROISIÈME NIVEAU')
            ->setCellValue('A2', 'Indicateur')
            ->setCellValue('B2', 'Unité')
            ->setCellValue('C2', 'Référence')
            ->setCellValue('D2', 'Mi-parcours')
            ->setCellValue('E2', 'Achèvement')
            ->setCellValue('F2', 'Objectif');

$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  ),
  'alignment' => array(
  'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
  ),
);
cellColor("A1",'D3D3D3');
cellColor("A2:F2",'DCDCDC');
$objPHPExcel->getActiveSheet()->getStyle("A1:F2")->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("A1:F2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(67);

//Données Composante/S-composante
$k = 3; $j = 0;
if(isset($cmp2_array) && count($cmp2_array)>0)
{
  foreach($cmp2_array as $a=>$b)
  {
    if(is_array($b))
    {
      foreach($b as $c=>$d)
      {
        $objPHPExcel->setActiveSheetIndex(3)
                    ->setCellValue($col[$j].$k, utf8_encode(trim($d)));
                    $j++;
      }
    }
    $k++; $j = 0;
  }
}
$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  ),
  'alignment' => array(
  //'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
  ),
);
$objPHPExcel->getActiveSheet()->getStyle('A3:A'.$k)
    ->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle("A3:F".($k-1))->applyFromArray($styleArray);
//Init
$objPHPExcel->setActiveSheetIndex(0);

require_once 'Classes/PHPExcel/IOFactory.php';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// If you want to output e.g. a PDF file, simply do:
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
$objWriter->save('Classes/sygri_'.date('m').'_'.date('Y').'.xlsx');
?>

<!-- Fin Site contenu ici -->

            </div>

        </div>



        </div>

    </div>

    <?php include_once ("includes/footer.php");?>

</div>

</body>

</html>