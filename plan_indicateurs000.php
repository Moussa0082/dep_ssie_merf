<?php

///////////////////////////////////////////////

/*                 SSE                       */

/*	Conception & Développement: BAMASOFT */

///////////////////////////////////////////////

session_start();

include_once 'system/configuration.php';

$config = new Config;



if (!isset ($_SESSION["clp_id"])) {

  //header(sprintf("Location: %s", "./"));

  exit;

}

include_once $config->sys_folder . "/database/db_connexion.php";

//header('Content-Type: text/html; charset=UTF-8');



if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y");

if(isset($_GET['tab'])) $tab=$_GET['tab']; else $tab=1;

if(isset($_GET['code_act'])) {$code_act = $_GET['code_act'];} else $code_act = 0;

if(isset($_GET['id_act'])) {$id_act = $_GET['id_act'];} else $id_act="";



$editFormAction = $_SERVER['PHP_SELF'];

/*if (isset($_SERVER['QUERY_STRING'])) {

  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);

}*/

$page = $_SERVER['PHP_SELF'];

$array_indic = array("OUI/NON","texte");

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];



// query sup indicateur

if(isset($_GET["id_sup_ind"])) { $id=$_GET["id_sup_ind"];

$query_sup_ind = "DELETE FROM ".$database_connect_prefix."indicateur_tache WHERE id_indicateur_tache='$id'";
   try{
    $Result1 = $pdar_connexion->prepare($query_sup_ind);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

//Donnee trimestrielle

if($Result1)

{

  $query_sup_ind2 = "DELETE FROM ".$database_connect_prefix."cible_indicateur_trimestre WHERE indicateur='$id'";
     try{
    $Result12 = $pdar_connexion->prepare($query_sup_ind2);
    $Result12->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

}

  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];

  if ($Result1) $insertGoTo .= "?del=ok&annee=$annee&code_act=$code_act&id_act=$id_act"; else $insertGoTo .= "?del=no&annee=$annee&code_act=$code_act&id_act=$id_act";

  header(sprintf("Location: %s", $insertGoTo));

}



//insertion indicateur

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form0"))

{

    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {

      $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

      //$arr = explode("_", $_POST['ind_cl']);



			  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."indicateur_tache (code_indicateur_ptba, id_activite, unite, indicateur_cr, tache,  intitule_indicateur_tache, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s,'$personnel', '$date')",
								   GetSQLValueString($_POST['code_indicateur_ptba'], "text"),

								   GetSQLValueString($id_act, "text"),

								   GetSQLValueString($_POST['unite'], "text"),

								   GetSQLValueString($_POST['indicateur_cr'], "text"),

								   GetSQLValueString($_POST['tache'], "text"),

								   GetSQLValueString($_POST['indicateur'], "text"));		  
			     try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = $_SERVER['PHP_SELF'];

  if ($Result1) $insertGoTo .= "?insert=ok&annee=$annee&&code_act=$code_act&id_act=$id_act"; else $insertGoTo .= "?insert=no&annee=$annee&code_act=$code_act&id_act=$id_act";

  header(sprintf("Location: %s", $insertGoTo));

}



  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {

    $id = ($_POST["MM_delete"]);

    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."indicateur_tache WHERE id_indicateur_tache='$id'",

                         GetSQLValueString($id, "int"));


   try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

    //Donnee trimestrielle

    if($Result1)

    {

      $query_sup_ind = "DELETE FROM ".$database_connect_prefix."cible_indicateur_trimestre WHERE indicateur='$id'";

   try{
    $Result1 = $pdar_connexion->prepare($query_sup_ind);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

    }

    $insertGoTo = $_SERVER['PHP_SELF'];

    if ($Result1) $insertGoTo .= "?del=ok&annee=$annee&&code_act=$code_act&id_act=$id_act"; else $insertGoTo .= "?del=no&annee=$annee&code_act=$code_act&id_act=$id_act";

    header(sprintf("Location: %s", $insertGoTo));

  }



  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id_ind'];

//$arr = explode("_", $_POST['ind_cl']);



				  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."indicateur_tache SET code_indicateur_ptba=%s, unite=%s, tache=%s, indicateur_cr=%s, intitule_indicateur_tache=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur_tache='$c'",

				                       

									    GetSQLValueString($_POST['code_indicateur_ptba'], "text"),

										GetSQLValueString($_POST['unite'], "text"),

									    GetSQLValueString($_POST['tache'], "text"),

								        GetSQLValueString($_POST['indicateur_cr'], "text"),

									    GetSQLValueString($_POST['indicateur'], "text"));
//echo  $insertSQL ;exit;
   try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];

  if ($Result1) $insertGoTo .= "?update=ok&annee=$annee&code_act=$code_act&id_act=$id_act"; else $insertGoTo .= "?update=no&annee=$annee&code_act=$code_act&id_act=$id_act";

  header(sprintf("Location: %s", $insertGoTo));

}

}

//inservtion valeur cible

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1" || $_POST["MM_insert"] == "form2" || $_POST["MM_insert"] == "form3" || $_POST["MM_insert"] == "form4")) {

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

$iregion=$_POST['region'];

$valind=$_POST['valind'];

$id_ind=$_POST['id_ind'];

$trimestre=$_POST['trimestre'];

//suppression
//print_r($id_ind); exit;

foreach ($id_ind as $key => $value)

{

  $idin=$id_ind[$key];

  $query_sup_cible_indicateur = "DELETE FROM ".$database_connect_prefix."cible_indicateur_trimestre WHERE indicateur='$idin' and trimestre=".$_POST['trimestre'];
   try{
    $Result1 = $pdar_connexion->prepare($query_sup_cible_indicateur);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

}



$query_liste_unite = "SELECT unite FROM ".$database_connect_prefix."indicateur_tache WHERE id_indicateur_tache='$idin' ";
    	   try{
    $liste_unite = $pdar_connexion->prepare($query_liste_unite);
    $liste_unite->execute();
    $row_liste_unite = $liste_unite ->fetch();
    $totalRows_liste_unite = $liste_unite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$unite = (isset($row_liste_unite["unite"]))?strtoupper($row_liste_unite["unite"]):"";



// `indicateur` int(11) NOT NULL,   `mois` int(11) DEFAULT NULL,  `cible` float DEFAULT '0',

foreach ($id_ind as $key => $value)

{

	if(isset($valind[$key]) && $valind[$key]!=NULL) {
	   if(isset($unite) && strtoupper($unite)=="OUI/NON") {if(strtoupper($valind[$key])=="OUI") {$valind[$key]=1; } else $valind[$key]=0;} 
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."cible_indicateur_trimestre  (indicateur, region, trimestre, cible, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, '$personnel', '$date')",

					 			  
					   GetSQLValueString($id_ind[$key], "text"),

					   GetSQLValueString($iregion[$key], "text"),

                       GetSQLValueString($_POST['trimestre'], "int"),

					   GetSQLValueString($valind[$key],"double"));

   try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

    }

  }

  $insertGoTo = $_SERVER['PHP_SELF'];

    if ($Result1) $insertGoTo .= "?del=ok&annee=$annee&code_act=$code_act&id_act=$id_act"; else $insertGoTo .= "?del=no&annee=$annee&code_act=$code_act&id_act=$id_act";

    $insertGoTo .= "&tab=".GetSQLValueString($_POST['trimestre'], "int");

  header(sprintf("Location: %s", $insertGoTo));

}



//activite

$query_act = "SELECT * FROM ".$database_connect_prefix."ptba where id_ptba='$id_act'";
    	   try{
    $act = $pdar_connexion->prepare($query_act);
    $act->execute();
    $row_act = $act ->fetch();
    $totalRows_act = $act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$code_act=$row_act['code_activite_ptba'];



//indicateur resultat

if(isset($_GET['id_ind'])) $id_ind=$_GET['id_ind']; else $id_ind=0; if(isset($_GET['ad_ind'])) $ex_ind=$_GET['ad_ind']; else $ex_ind=0;
$query_liste_referentiel = "SELECT * FROM ".$database_connect_prefix."referentiel_indicateur where type_ref_ind=1 and mode_calcul='Unique' and mode_suivi=1 order by intitule_ref_ind ASC";
    	   try{
    $liste_referentiel = $pdar_connexion->prepare($query_liste_referentiel);
    $liste_referentiel->execute();
    $row_liste_referentiel = $liste_referentiel ->fetchAll();
    $totalRows_liste_referentiel = $liste_referentiel->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }



if(isset($_GET["id_ind"])){ $id=$_GET["id_ind"];

  $query_edit_ind = "SELECT * FROM ".$database_connect_prefix."indicateur_tache where id_indicateur_tache='$id'";
        	   try{
    $edit_ind = $pdar_connexion->prepare($query_edit_ind);
    $edit_ind->execute();
    $row_edit_ind = $edit_ind ->fetch();
    $totalRows_edit_ind = $edit_ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

}

$query_ind = "SELECT * FROM ".$database_connect_prefix."indicateur_tache where id_activite='$id_act' ORDER BY code_indicateur_ptba asc";
    	   try{
    $ind = $pdar_connexion->prepare($query_ind);
    $ind->execute();
    $row_ind = $ind ->fetchAll();
    $totalRows_ind = $ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$ugl_projet = str_replace("|",",",$row_act["region"]);//implode(",",(explode("|", $_SESSION["clp_projet_ugl"]));


$query_liste_region= "SELECT code_ugl, nom_ugl FROM ".$database_connect_prefix."ugl where FIND_IN_SET( code_ugl, '".$ugl_projet."' ) order by code_ugl";
    	   try{
    $liste_region = $pdar_connexion->prepare($query_liste_region);
    $liste_region->execute();
    $row_liste_region = $liste_region ->fetchAll();
    $totalRows_liste_region = $liste_region->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); } 
$tableauRegion=array(); $nbregi=0;
 if($totalRows_liste_region>0) { foreach($row_liste_region as $row_liste_region){  
$tableauRegion[]=$row_liste_region['code_ugl']."<>".$row_liste_region['nom_ugl']; $nbregi=$nbregi+1;
 } }
 

$query_liste_tache = "select * FROM ".$database_connect_prefix."groupe_tache where id_activite='$id_act' ORDER BY code_tache ASC";
    	   try{
    $liste_tache = $pdar_connexion->prepare($query_liste_tache);
    $liste_tache->execute();
    $row_liste_tache = $liste_tache ->fetchAll();
    $totalRows_liste_tache = $liste_tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


$query_liste_unite = "select * FROM ".$database_connect_prefix."unite_indicateur order by unite ASC";
    	   try{
    $liste_unite = $pdar_connexion->prepare($query_liste_unite);
    $liste_unite->execute();
    $row_liste_unite = $liste_unite ->fetchAll();
    $totalRows_liste_unite = $liste_unite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); } 

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

<script>

	$().ready(function() {

		// validate the comment form when it is submitted

		$("#form0").validate();

        $("#tabs").tabs();

        $(".modal-dialog", window.parent.document).width(780);

        $(".select2-select-00").select2({allowClear:true});

	});

</script>

<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {

  border-spacing: 0px !important; border-collapse: collapse; font-size: small;

} .table tbody tr td {vertical-align: middle; }

#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}

@media(min-width:558px){.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}

</style>

</head>

<body>

<?php if(!isset($_GET['ad_ind'])) { ?>

<div>

<div class="widget box ">

 <div class="widget-header"> <h4><i class="icon-reorder"></i> Indicateurs d'activit&eacute;s</h4>

  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>

<a href="<?php echo $_SERVER['PHP_SELF']."?ad_ind&id_act=".$id_act."&code_act=".$code_act."&annee=".$annee; ?>" class="pull-right p11" title="Ajout d'indicateurs" ><i class="icon-plus"> Nouvel indicateur </i></a>

<?php } ?>

</div>

<div class="widget-content">

<div class="tabbable tabbable-custom" >

<ul class="nav nav-tabs" >

<?php for($j=1;$j<=4;$j++){ ?>

<li title="Trimestre <?php echo $j; ?>" class="<?php echo ($j==$tab)?"active":""; ?>"><a href="#tab_feed_<?php echo $j; ?>" data-toggle="tab">Trimestre <?php echo $j; ?></a></li>

<?php } ?>

</ul>

<div class="tab-content">

<?php for($j=1;$j<=4;$j++){ ?>

<div class="tab-pane <?php echo ($j==$tab)?"active":""; ?>" id="tab_feed_<?php echo $j; ?>">

<div class="scroller hide_befor_load">

<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >

            <thead>

              <?php $t=0;  if($totalRows_ind>0) { ?>

              <tr>

                <?php foreach($tableauRegion as $vregion){?>

                <td align="center" >

            <?php

                $aregion = explode('<>',$vregion);

                $iregion = $aregion[0]; echo $aregion[1];

            ?>

                </td>

                <?php } ?>

                <td align="center">Valeur cible</td>

                <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>

                <td align="center" width="90">Actions</td>

                <?php } ?>

              </tr>

            </thead>
              <?php if($totalRows_ind>0) { $p1="j"; $t=0; $i=0; foreach($row_ind as $row_ind1){  $ind_courant=$row_ind1['id_indicateur_tache']; $unite = $row_ind1['unite']; 
  

//semestre precedent

$query_cible_indicateur = "SELECT * FROM ".$database_connect_prefix."cible_indicateur_trimestre where indicateur='$ind_courant' ";
  		try{
    $cible_indicateur = $pdar_connexion->prepare($query_cible_indicateur);
    $cible_indicateur->execute();
    $row_cible_indicateur = $cible_indicateur ->fetchAll();
    $totalRows_cible_indicateur = $cible_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableau_cible_indicateur_array = array();
 if($totalRows_cible_indicateur>0) { foreach($row_cible_indicateur as $row_cible_indicateur){  
   $tableau_cible_indicateur_array[$row_cible_indicateur["trimestre"]][$row_cible_indicateur["region"]] = $row_cible_indicateur["cible"]; } }

if(!in_array($unite,$array_indic))

{                                        

  $fn = ($unite=="%")?'avg':'sum';

  $query_cible_tindicateur = "SELECT $fn(cible) as cible_total, indicateur FROM   ".$database_connect_prefix."cible_indicateur_trimestre where indicateur='$ind_courant' group by indicateur";
    		try{
    $cible_tindicateur = $pdar_connexion->prepare($query_cible_tindicateur);
    $cible_tindicateur->execute();
    $row_cible_tindicateur = $cible_tindicateur ->fetchAll();
    $totalRows_cible_tindicateur = $cible_tindicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $tableau_cible_tindicateur_array = array();
 if($totalRows_cible_tindicateur>0) { foreach($row_cible_tindicateur as $row_cible_tindicateur){  
      $tableau_cible_tindicateur_array[$row_cible_tindicateur["indicateur"]] = $row_cible_tindicateur["cible_total"];
    } }

}

  ?>

  <tr <?php //if($i%2==0) echo 'bgcolor="#D2E2B1"';  $t=$t+1; ?>>

    <td colspan="<?php  echo $nbregi+1; ?>" ><b><u><?php echo $row_ind1['intitule_indicateur_tache']." (".$row_ind1['unite'].")"; ?></u></b></td>

    <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>

    <td align="center" nowrap="nowrap"><?php echo "<a href='".$_SERVER['PHP_SELF']."?ad_ind&id_ind=".$row_ind1['id_indicateur_tache']."&id_act=".$id_act."&code_act=".$code_act."&annee=".$annee."#s' style='margin:0px 5px;'><img src='./images/edit.png' width='20' height='20' alt='Modifier'></a>" ?>

    <a href="<?php echo $_SERVER['PHP_SELF']."?id_sup_ind=".$row_ind1['id_indicateur_tache']."&annee=".$annee."&id_act=".$id_act."&code_act=".$code_act.""?>" style='margin:0px 5px;' onClick="return confirm('Voulez-vous vraiment supprimer <?php echo str_replace("'","\'",$row_ind1['intitule_indicateur_tache']); ?>?');" /><img src="./images/delete.png" width="20" height="20"/></a></td>

    <?php }?>

  </tr>

<form name="form1" id="form1" method="post" action="">

  <tr <?php //if($i%2==0) echo 'bgcolor="#D2E2B1"';  $i=$i+1; $t=$t+1;?>>

    <?php foreach($tableauRegion as $vregion){?>

    <td align="center">

<?php

  $aregion = explode('<>',$vregion);

  $iregion = $aregion[0];

  //if(!in_array($row_ind1['unite'],$array_indic)){

?>

      <input name='valind[]' type="text" class="form-control" size="5" style="text-align:center"  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']>3) echo "disabled"; ?> value="<?php 
	  	   if(isset($tableau_cible_indicateur_array[$j][$iregion]) && strtoupper($row_ind1['unite'])=="OUI/NON") {if($tableau_cible_indicateur_array[$j][$iregion]==1) {echo "Oui"; } else echo "Non";}  elseif(isset( $tableau_cible_indicateur_array[$j][$iregion])) echo $tableau_cible_indicateur_array[$j][$iregion]; ?>"/>

<?php //} else { //OUI/NON ou text ?>

     <!-- <textarea class="form-control" name="valind[]" cols="25" rows="2" <?php //if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']>3) echo "disabled"; ?>><?php //if(isset( $tableau_cible_indicateur_array[$j][$iregion])) echo $tableau_cible_indicateur_array[$j][$iregion]; ?></textarea>

<?php //} ?>-->

      <input name="region[]" type="hidden" size="4" value="<?php echo $iregion; ?>"/>

      <input name="trimestre" type="hidden" size="4" value="<?php echo "$j"; ?>"/>

      <input name="id_ind[]" type="hidden" size="4" value="<?php echo $row_ind1['id_indicateur_tache']; ?>"/>

</td>

    <?php } ?>

    <td align="right">

      <b><?php if(isset($tableau_cible_tindicateur_array[$row_ind1["id_indicateur_tache"]]) &&  strtoupper($row_ind1['unite'])!="OUI/NON") echo number_format($tableau_cible_tindicateur_array[$row_ind1["id_indicateur_tache"]], 0, ',', ' '); ?></b>

    </td>



<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1) && $totalRows_ind>0) {?>

<td align="center">

      <div align="right">

        <input type="submit" class="btn btn-success pull-right" name="Submit" value="Enregistrer" />

        <!--<input name="Submit" class="inputsubmit" type="reset" value="Annuler" />-->

        <input type="hidden" name="<?php  echo "MM_insert";  ?>" value="form1" />

      </div>

</td>

<?php }  ?>

  </tr>

</form>

  <?php $i++;} }  //mysql_free_result($cible_indicateur); mysql_free_result($cible_tindicateur);  //mysql_free_result($ind);  ?>



<?php /*if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1) && $totalRows_ind>0) {?>

<!--      <div align="right">

        <input type="submit" class="btn btn-success pull-right" name="Submit" value="Enregistrer" />

        <input type="hidden" name="<?php  echo "MM_insert";  ?>" value="form1" />

      </div>-->

<?php } */

 ?>



  <?php } else echo "<h3 align='center'>Aucun indicateur planifi&eacute; pour cette activit&eacute;</h3>" ;?>

</table>



</div></div>

<?php } ?>

 </div>

</div>



</div>

</div></div>

<?php } else { ?>

<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id_ind"]) && !empty($_GET["id_ind"]))?"Modification d'indicateur":"Nouvel indicateur"; ?></h4>

<a href="<?php echo $_SERVER['PHP_SELF']."?code_act=".$code_act."&id_act=".$id_act."&annee=".$annee; ?>" class="pull-right p11" title="Annuler" >Annuler </a>

</div>

<div class="widget-content">



<!--<div class="clear" align="center"><br /><?php //if(!$totalRows_ind>0) {echo "Aucun indicateur";}  echo "<a href=".$_SERVER['PHP_SELF']."?ad_ind&code_act=".$code_act."&annee=".$annee." class=\"button\"><img src='../images/plus.gif' width='15' height='15' alt='Modifier'>Ajouter un indicateur</a>" ?></div><br /> -->



<form action="<?php echo $editFormAction."?code_act=".$code_act."&id_act=".$id_act."&annee=".$annee; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form0" id="form0" novalidate="novalidate">

<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

    <tr valign="top">

      <td>

        <div class="form-group">

          <label for="code_indicateur_ptba" class="col-md-3 control-label">N&deg; ordre <span class="required">*</span></label>

          <div class="col-md-3">

            <input name="code_indicateur_ptba" type="text" class="form-control required" id="code_indicateur_ptba" value="<?php if(isset($_GET['id_ind'])) echo $row_edit_ind['code_indicateur_ptba']; ?>" size="5" onblur="if(this.value!='' <?php if(isset($_GET["id_ind"]) && !empty($_GET["id_ind"])) echo "&& this.value!='".$row_edit_ind['code_indicateur_ptba']."'"; ?>) check_code('verif_code.php?t=indicateur_tache&','w=code_indicateur_ptba='+this.value+' and id_activite=<?php echo $id_act; ?>','code_zone'); <?php if(isset($_GET["id_ind"]) && !empty($_GET["id_ind"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />

            <span class="help-block h0" id="code_zone_text">&nbsp;</span>

          </div>

        </div>

      </td>

    </tr>

    <tr valign="top">

      <td>

        <div class="form-group">

          <label for="indicateur" class="col-md-3 control-label">Intitul&eacute; <span class="required">*</span></label>

          <div class="col-md-9">

            <textarea class="form-control required" cols="200" rows="2" type="text" name="indicateur" id="indicateur"><?php if(isset($_GET['id_ind'])) echo $row_edit_ind['intitule_indicateur_tache']; ?></textarea>

          </div>

        </div>

      </td>

    </tr>

      <tr valign="top">

      <td>

        <div class="form-group">

          <label for="unite" class="col-md-3 control-label">Unité <span class="required">*</span></label>

          <div class="col-md-9">

            <select name="unite" id="unite" class="form-control required">

            <option value="">Selectionnez</option>

            <?php if($totalRows_liste_unite>0) { foreach($row_liste_unite as $row_liste_unite){   ?>

                <option value="<?php echo $row_liste_unite['unite'];?>"<?php if(isset($_GET['id_ind'])) {if (!(strcmp($row_liste_unite['unite'], $row_edit_ind['unite']))) {echo "SELECTED";} } ?>><?php echo $row_liste_unite['unite'];?></option>

                <?php

            }  }  ?>

            </select>

          </div>

        </div>

      </td>

    </tr>

	<tr valign="top">

      <td>

        <div class="form-group">

          <label for="tache" class="col-md-3 control-label">Tâche concernée <span class="required">*</span></label>

          <div class="col-md-9">

            <select name="tache" id="tache" class="form-control required">

            <option value="0">Aucune</option>

            <?php if($totalRows_liste_tache>0) { foreach($row_liste_tache as $row_liste_tache){   ?>

                <option value="<?php echo $row_liste_tache['code_tache'];?>"<?php if(isset($_GET['id_ind'])) {if (!(strcmp($row_liste_tache['code_tache'], $row_edit_ind['tache']))) {echo "SELECTED";} } ?>><?php echo $row_liste_tache['code_tache'].": ".substr($row_liste_tache['intitule_tache'],0, 70);?></option>

                <?php

            }  }  ?>

            </select>

          </div>

        </div>

      </td>

    </tr>

	

	<tr valign="top">

      <td>

        <div class="form-group">

          <label for="indicateur_cr" class="col-md-3 control-label">R&eacute;f&eacute;rentiel </label>

          <div class="col-md-9">

            <select name="indicateur_cr" id="indicateur_cr" class="full-width-fix select2-select-00 required">

            <option value="">Selectionnez</option>
            <option value="0" <?php if(isset($_GET['id_ind'])) {if (!(strcmp(0, $row_edit_ind['indicateur_cr']))) {echo "SELECTED";} } ?>>Non-d&eacute;finie</option>
            <?php if($totalRows_liste_referentiel>0) { foreach($row_liste_referentiel as $row_liste_referentiel){   ?>

                <option value="<?php echo $row_liste_referentiel['id_ref_ind'];?>"<?php if(isset($_GET['id_ind'])) {if (!(strcmp($row_liste_referentiel['id_ref_ind'], $row_edit_ind['indicateur_cr']))) {echo "SELECTED";} } ?>><?php echo substr($row_liste_referentiel['code_ref_ind'].": ".$row_liste_referentiel['intitule_ref_ind'],0, 70)." (".$row_liste_referentiel['unite'].")";?></option>

                <?php

            }  }  ?>

            </select>

          </div>

        </div>

      </td>

    </tr>

</table>

<div class="form-actions">

<?php if(isset($_GET["id_ind"])){ ?>

  <input type="hidden" name="id_ind" value="<?php echo ($_GET["id_ind"]); ?>" />

<?php } ?>

  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id_ind"]) && !empty($_GET["id_ind"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />

<a href="<?php echo $_SERVER['PHP_SELF']."?code_act=".$code_act."&id_act=".$id_act."&annee=".$annee; ?>" class="btn pull-right" title="Annuler" >Annuler</a>

  <input name="<?php if(isset($_GET["id_ind"]) && !empty($_GET["id_ind"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id_ind"]) && !empty($_GET["id_ind"])) echo ($_GET["id_ind"]); else echo "MM_insert" ; ?>" size="32" alt="">

<?php if(isset($_GET["id_ind"]) && !empty($_GET["id_ind"]) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2)) { ?>

<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">

<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cet indicateur ?','<?php echo ($_GET["id_ind"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />

<?php } ?>

<input name="MM_form" id="MM_form" type="hidden" value="form0" size="32" alt="">

  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->

</div>

</form>

</div> </div>

<?php } ?>

<?php include_once 'modal_add.php'; ?>

</body>

</html>