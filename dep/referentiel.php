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

//header('Content-Type: text/html; charset=ISO-8859-15');

if(isset($_GET["domact"])) { $domact=$_GET["domact"];} else $domact="%";

if(isset($_GET['composante']) && $_GET['composante']!="") {$_SESSION["composante"]=$_GET['composante']; $composante=$_GET['composante']; $filiere=$_SESSION["composante"];} else {$filiere=0; $composante=0; $_GET['composante']=0;}

if(isset($_GET['composante']) && $_GET['composante']==""){ $_GET['composante']=""; unset($_SESSION["composante"]); $composante=0; }

$where = ($filiere==0)?"":" and composante = ".$filiere." ";

$where .= ($composante==0)?"":" and id_composante=".$composante." ";

if(isset($_GET["sc"])) $sc = $_GET["sc"]; else $sc = 0;

$editFormAction = $_SERVER['PHP_SELF'];

$currentPage = $_SERVER['PHP_SELF']."?composante=$filiere&sc=$sc";

if (isset($_SERVER['QUERY_STRING'])) {

  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);

}

  if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))

{

   



  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {

   $personnel=$_SESSION['clp_id'];  $date=date("Y-m-d");

    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."referentiel_indicateur (intitule_ref_ind, echelle, code_ref_ind, type_representation, paccueil, type_ref_ind, unite, periode, sources, moyen_collecte, lien_indicateur, limites_biais, risque, collecte, validation, diffusion, projet, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, '$personnel', '$date')",

                GetSQLValueString($_POST['intitule_ref_ind'], "text"),

				GetSQLValueString($_POST['echelle'], "text"),

                GetSQLValueString($_POST['code_ref_ind'], "text"),

                GetSQLValueString($_POST['type_representation'], "text"),

               // GetSQLValueString($_POST['indicateur_cr'], "int"),

				GetSQLValueString($_POST['paccueil'], "int"),

                GetSQLValueString($_POST['type_ref_ind'], "text"),

                GetSQLValueString($_POST['unite'], "text"),

                GetSQLValueString($_POST['periode'], "text"),

                GetSQLValueString(implode(",",$_POST['sources']), "text"),

                GetSQLValueString($_POST['moyen_collecte'], "text"),

                GetSQLValueString(implode(",",$_POST['lien_indicateur']), "text"),

   // GetSQLValueString(strcmp(trim(strtolower($_POST['valeur_reference'])),"oui")==0?1:(strcmp(trim(strtolower($_POST['valeur_reference'])),"non")==0?0:$_POST['valeur_reference']), "double"),

    // GetSQLValueString(strcmp(trim(strtolower($_POST['valeur_cible'])),"oui")==0?1:(strcmp(trim(strtolower($_POST['valeur_cible'])),"non")==0?0:$_POST['valeur_cible']), "double"),

                GetSQLValueString($_POST['limites_biais'], "text"),

                GetSQLValueString($_POST['risque'], "text"),

                GetSQLValueString(implode(",",$_POST['collecte']), "text"),

                GetSQLValueString($_POST['validation'], "int"),

                GetSQLValueString(implode(",",$_POST['diffusion']), "text"),

                GetSQLValueString($_SESSION['clp_programmes_2qc'], "text"));

 try{

        $Result1 = $pdar_connexion->prepare($insertSQL);

        $Result1->execute();

    }catch(Exception $e){ die(mysql_error_show_message($e)); }

	    $insertGoTo = $_SERVER['PHP_SELF'];

    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no&";

    //$insertGoTo .= (isset($_POST['prefecture']))?"&prefecture=".$_POST['prefecture']:"";

    //$insertGoTo .= (isset($_POST['sc']))?"&sc=".$_POST['sc']:"";

 

    header(sprintf("Location: %s", $insertGoTo)); exit;

  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {

      $id = intval($_POST["MM_delete"]);

      $insertSQL = sprintf("DELETE from referentiel_indicateur WHERE id_ref_ind=%s",

                           GetSQLValueString($id, "int"));

 try{

        $Result1 = $pdar_connexion->prepare($insertSQL);

        $Result1->execute();

    }catch(Exception $e){ die(mysql_error_show_message($e)); }

	      $insertGoTo = $_SERVER['PHP_SELF'];

      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";

      header(sprintf("Location: %s", $insertGoTo)); exit();

    }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {

  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id'];

  $ancien_responsable = "";

 

 





  	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."referentiel_indicateur SET intitule_ref_ind=%s, echelle=%s, code_ref_ind=%s, type_representation=%s, paccueil=%s,  type_ref_ind=%s, unite=%s, periode=%s, sources=%s, moyen_collecte=%s, lien_indicateur=%s, limites_biais=%s, risque=%s, collecte=%s, validation=%s, diffusion=%s, modifier_par='$personnel', modifier_le='$date' WHERE id_ref_ind='$c'",

                GetSQLValueString($_POST['intitule_ref_ind'], "text"),

				GetSQLValueString($_POST['echelle'], "text"),

                GetSQLValueString($_POST['code_ref_ind'], "text"),

                GetSQLValueString($_POST['type_representation'], "text"),

               // GetSQLValueString($_POST['indicateur_cr'], "int"),

				GetSQLValueString($_POST['paccueil'], "int"),

                GetSQLValueString($_POST['type_ref_ind'], "text"),

                GetSQLValueString($_POST['unite'], "text"),

                GetSQLValueString($_POST['periode'], "text"),

                GetSQLValueString(implode(",",$_POST['sources']), "text"),

                GetSQLValueString($_POST['moyen_collecte'], "text"),

                GetSQLValueString(implode(",",$_POST['lien_indicateur']), "text"),

              //  GetSQLValueString(strcmp(trim(strtolower($_POST['valeur_reference'])),"oui")==0?1:(strcmp(trim(strtolower($_POST['valeur_reference'])),"non")==0?0:$_POST['valeur_reference']), "double"),

               // GetSQLValueString(strcmp(trim(strtolower($_POST['valeur_cible'])),"oui")==0?1:(strcmp(trim(strtolower($_POST['valeur_cible'])),"non")==0?0:$_POST['valeur_cible']), "double"),

                GetSQLValueString($_POST['limites_biais'], "text"),

                GetSQLValueString($_POST['risque'], "text"),

                GetSQLValueString(implode(",",$_POST['collecte']), "text"),

                GetSQLValueString($_POST['validation'], "int"),

                GetSQLValueString(implode(",",$_POST['diffusion']), "text"));

				

 try{

        $Result1 = $pdar_connexion->prepare($insertSQL);

        $Result1->execute();

    }catch(Exception $e){ die(mysql_error_show_message($e)); }

	    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];

    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";

  //  $insertGoTo .= (isset($_POST['composante']))?"&composante=".$_POST['composante']:"";

   // $insertGoTo .= (isset($_POST['sc']))?"&sc=".$_POST['sc']:"";

     header(sprintf("Location: %s", $insertGoTo)); exit;

  }

}

//Méta-données

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form4"))

{

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {

  $personnel=$_SESSION['clp_id'];

    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."meta_donnees (ref_indicateur, source_donnees, date_validation, observation, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, '$personnel', '$date')",

                        GetSQLValueString($_POST['ref_indicateur'], "text"),

              GetSQLValueString($_POST['source_donnees'], "text"),

                        GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_validation']))), "date"),

  					    GetSQLValueString($_POST['observation'], "text"));

 try{

        $Result1 = $pdar_connexion->prepare($insertSQL);

        $Result1->execute();

    }catch(Exception $e){ die(mysql_error_show_message($e)); }

	    if($Result1) $insertGoTo = $page."?insert=ok";

    else $insertGoTo = $page."&insert=no";

   // $insertGoTo .= "&periode=$annee&tb=2";

    header(sprintf("Location: %s", $insertGoTo)); exit();

  }

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {

      $id = $_POST["MM_delete"];

      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."meta_donnees WHERE id_meta_donnees=%s",

                           GetSQLValueString($id, "text"));

 try{

        $Result1 = $pdar_connexion->prepare($insertSQL);

        $Result1->execute();

    }catch(Exception $e){ die(mysql_error_show_message($e)); }

	      $insertGoTo = $_SERVER['PHP_SELF'];

      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";

     // $insertGoTo .= "&periode=$annee&tb=2";

      header(sprintf("Location: %s", $insertGoTo)); exit();

    }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {

    $id = ($_POST["MM_update"]);

    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."meta_donnees SET source_donnees=%s, date_validation=%s, observation=%s, modifier_par='$personnel', modifier_le='$date' WHERE id_meta_donnees=%s",

                      //  GetSQLValueString($_POST['ref_indicateur'], "text"),

              GetSQLValueString($_POST['source_donnees'], "text"),

                        GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_validation']))), "date"),

  					    GetSQLValueString($_POST['observation'], "text"),

                        GetSQLValueString($id, "text"));

 try{

        $Result1 = $pdar_connexion->prepare($insertSQL);

        $Result1->execute();

    }catch(Exception $e){ die(mysql_error_show_message($e)); }

	    if($Result1) $insertGoTo = $page."?update=ok";

    else $insertGoTo = $page."&update=no";

    $insertGoTo .= "&periode=$annee&tb=2";

    header(sprintf("Location: %s", $insertGoTo));  exit();

  }

}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1f"))

{

  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $idsy = $_POST['idref'];

  	$insertSQL = sprintf("UPDATE referentiel_indicateur SET classeur=%s, feuille=%s, colonne=%s, mode_calcul_fiche=%s, critere=%s, modifier_fiche='$personnel', modifier_fiche_date='$date' WHERE id_ref_ind='$idsy'",

                       GetSQLValueString($_POST['classeur'], "int"),

                       GetSQLValueString($_POST['feuille'], "text"),

             GetSQLValueString($_POST['colonne'], "text"),

             GetSQLValueString($_POST['mode_calcul'], "text"),

             GetSQLValueString($_POST['critere'], "text"));

 try{

        $Result1 = $pdar_connexion->prepare($insertSQL);

        $Result1->execute();

    }catch(Exception $e){ die(mysql_error_show_message($e)); }

	      $insertGoTo = $_SERVER['PHP_SELF'];

    if ($Result1) $insertGoTo .= "?insert=ok$param_url";  else $insertGoTo .= "?insert=no$param_url";

    header(sprintf("Location: %s", $insertGoTo)); exit();

}



$query_liste_composante = "SELECT * FROM  referentiel_indicateur   ORDER BY code_ref_ind";

try{

    $liste_composante = $pdar_connexion->prepare($query_liste_composante);

    $liste_composante->execute();

    $row_liste_composante = $liste_composante ->fetchAll();

    $totalRows_liste_composante = $liste_composante->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }





$query_liste_code_ref = "SELECT code_ref_ind, id_ref_ind FROM referentiel_indicateur order by code_ref_ind";

try{

    $liste_code_ref = $pdar_connexion->prepare($query_liste_code_ref);

    $liste_code_ref->execute();

    $row_liste_code_ref = $liste_code_ref ->fetchAll();

    $totalRows_liste_code_ref = $liste_code_ref->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }



$liste_code_ref_array = array();

foreach($row_liste_code_ref as $row_liste_code_ref){

 $liste_code_ref_array[$row_liste_code_ref["id_ref_ind"]] = $row_liste_code_ref["code_ref_ind"];

}

if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];

$query_sup_activite = "DELETE FROM referentiel_indicateur WHERE id_ref_ind='$id'";

 try{

        $Result1 = $pdar_connexion->prepare($query_sup_activite);

        $Result1->execute();

    }catch(Exception $e){ die(mysql_error_show_message($e)); }

	  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];

  if ($Result1) $insertGoTo .= "?del=ok&composante=$composante"; else $insertGoTo .= "?del=no&composante=$composante";

  $insertGoTo .= (isset($_GET['prefecture']))?"&prefecture=".$_GET['prefecture']:"";

  $insertGoTo .= (isset($_POST['sc']))?"&sc=".$_POST['sc']:"";

  header(sprintf("Location: %s", $insertGoTo));

}

$query_domaine = "SELECT *  FROM domaine_activite order by code_domaine";

try{

    $domaine = $pdar_connexion->prepare($query_domaine);

    $domaine->execute();

    $row_domaine = $domaine ->fetchAll();

    $totalRows_domaine = $domaine->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }



$domaine_array = array();

if(isset($totalRows_domaine ) && $totalRows_domaine>0) {

foreach($row_domaine as $row_domaine){

$domaine_array[$row_domaine["code_domaine"]]=$row_domaine["nom_domaine"];

} }



$query_type_ind = "SELECT * FROM categorie_indicateur";

try{

    $type_ind = $pdar_connexion->prepare($query_type_ind);

    $type_ind->execute();

    $row_type_ind = $type_ind ->fetchAll();

    $totalRows_type_ind = $type_ind->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }



$type_ind_array = array();

if(isset($totalRows_type_ind ) && $totalRows_type_ind>0) {

foreach($row_type_ind as $row_type_ind){

$type_ind_array[$row_type_ind["id_categorie_indicateur"]]=$row_type_ind["nom_categorie_indicateur"];

} }



$query_liste_structure = "SELECT *  FROM acteur order by nom_acteur";

try{

    $liste_structure = $pdar_connexion->prepare($query_liste_structure);

    $liste_structure->execute();

    $row_liste_structure = $liste_structure ->fetchAll();

    $totalRows_liste_structure = $liste_structure->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }

$structure_array = array();

if(isset($totalRows_liste_structure ) && $totalRows_liste_structure>0) {

foreach($row_liste_structure as $row_liste_structure){

$structure_array[$row_liste_structure["code_acteur"]]=$row_liste_structure["nom_acteur"];

} }



//type zone

$query_type_zone = "SELECT *  FROM type_zone";

try{

    $type_zone = $pdar_connexion->prepare($query_type_zone);

    $type_zone->execute();

    $row_type_zone = $type_zone ->fetchAll();

    $totalRows_type_zone = $type_zone->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }



$type_zone_array = array();

if(isset($totalRows_type_zone ) && $totalRows_type_zone>0) {

foreach($row_type_zone as $row_type_zone){

$type_zone_array[$row_type_zone["id_type"]]=$row_type_zone["definition"];

} }

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

<script language="JavaScript" type="text/javascript">

/*<![CDATA[*/

$(document).ready(function() {

      /*

     * Insert a 'details' column to the table

     */

    /*var nCloneTh = document.createElement( 'th' );

    var nCloneTd = document.createElement( 'td' );

    nCloneTd.innerHTML = '<img src="./images/plus.gif">';

    nCloneTd.className = "center";

    $('#mytable thead tr').each( function () {

        this.insertBefore( nCloneTh, this.childNodes[0] );

    } );

    $('#mytable tbody tr').each( function () {

        this.insertBefore(  nCloneTd.cloneNode( true ), this.childNodes[0] );

    } );  */



var oTable = $('#mytable').dataTable();

//Delete the datable object first

if(oTable != null)oTable.fnDestroy();

//Remove all the DOM elements

//$('#mytable').empty();

var oTable = $('#mytable').dataTable( {

        "aoColumnDefs": [

            { "bSortable": false, "aTargets": [ -1 ] }

        ],

        sDom:"<'row'<'dataTables_header clearfix'<'col-md-7'lT><'col-md-5'Cf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",

        oTableTools:{aButtons:["copy","print","csv","xls",{"sExtends": "pdf","sPdfOrientation": "landscape"}],sSwfPath:"./swf/copy_csv_xls_pdf.swf"},

        "aaSorting": [],

        //"aLengthMenu":[[25, 50, 100, 200, -1],[25, 50, 100, 200, "TOUS1"]],

        "iDisplayLength": -1,

        paging: false

    });

/* Formating function for row details */

function fnFormatDetails ( oTable, nTr )

{

    var aData = oTable.fnGetData( nTr );

    return aData[0];

}

                       //img[id="plus"]

$('#mytable tbody td').on('click', function () {

        var nTr = $(this).parents('tr')[0];

        if ( oTable.fnIsOpen(nTr) )

        {

            /* This row is already open - close it */

            //this.src = "./images/plus.png";

            oTable.fnClose( nTr );

        }

        else

        {

            /* Open this row */

            //this.src = "./images/moins.png";

            oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'details' );

        }

    } );

} );

/*]]>*/

</script>
</head>

<body>
 <header class="header navbar navbar-fixed-top" role="banner">

    <?php include_once ("includes/header.php");?>

 </header>

<div id="container">

    <div id="sidebar" class="sidebar-fixed">

        <div id="sidebar-content">

            <?php include_once ("includes/menu_top.php");?>

        </div>

        <div id="divider" class="resizeable"></div>

    </div>



    <div id="content">

        <div class="container">

            <div class="crumbs">

                <?php include_once ("includes/sous_menu.php");?>

            </div>

        <div class="page-header">

            <div class="p_top_5">

<!-- Site contenu ici -->

<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {

  border-spacing: 0px !important; border-collapse: collapse;

} .table tbody tr td {vertical-align: middle; }

</style>

<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i> Indicateurs référentiels </h4>

    <?php if(isset($_SESSION['clp_id']) && $_SESSION['clp_id']=='admin'){?>

<a onclick="get_content('new_indicateur_ref.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Ajouter un indicateur sectoriel" class="pull-right p11"><i class="icon-plus"> Ajouter un indicateur </i></a>
<?php include_once 'modal_add.php'; ?>
<!--<form name="form3dr" id="form3dr" method="get" action="<?php //echo $_SERVER['PHP_SELF']; ?>" class="pull-right">

<select name="domact" onchange="form3dr.submit();" style="background-color: #FFFF00; padding: 7px; width: 150px;" class="btn p11">

  <?php /*if($totalRows_domaine>0) { ?>

  <option value="%" <?php if($row_domaine['code_domaine']=="%") echo "selected='SELECTED'"; ?>>-- Domaines d'activité </option>

   <?php  do { 	?>

<option value="<?php echo $row_domaine['code_domaine']; ?>" <?php if($row_domaine['code_domaine']==$domact) echo "selected='SELECTED'"; ?>><?php echo $row_domaine['nom_domaine']; ?></option>

    <?php } while ($row_domaine = mysql_fetch_assoc($domaine));  mysql_free_result($domaine);?>

    <?php }*/  ?>

</select>

</form>-->

    <?php } ?>

</div>

<div class="widget-content">

<table  class="table table-striped table-bordered table-hover table-responsive  datatable hide_befor_load" id="DataTables_Table" aria-describedby="DataTables_Table_0_info">

<thead>

<tr role="row">

  <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Code  </th>

<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Libellé indicateur </th>

<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">composante</th>-->

<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Type</th>

<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Unité </th>

<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">P&eacute;riodicit&eacute;/ Echelle</th>

<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Responsable de collecte</th>

<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Type</th>-->

<th  class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><div align="center">Méta-données</div></th>

<?php if(isset($_SESSION['clp_id']) && $_SESSION['clp_id']=='admin') { ?>

<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="90">Actions</th>

<?php } ?>

</tr>

</thead>

<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">

<?php if($totalRows_liste_composante>0) { $i=0; foreach($row_liste_composante as $row_liste_composante){ if(isset($row_liste_composante['id_ref_ind']) && !empty($row_liste_composante['id_ref_ind']))$id = $row_liste_composante['id_ref_ind']; ?>

<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">

  <td class=" "><?php echo $row_liste_composante['code_ref_ind']; ?></td>

<td class=" "><?php echo $row_liste_composante['intitule_ref_ind']; ?></td>

<td class=" "><?php  echo $row_liste_composante["type_ref_ind"]; ?></br><?php if(isset($row_liste_composante['type_representation']) && ($row_liste_composante['type_representation']=="vr" || $row_liste_composante['type_representation']=="tn" || $row_liste_composante['type_representation']=="tt")){ ?> <a onclick="get_content('./typologie_indicateur.php','<?php echo "codeind=".$row_liste_composante['code_ref_ind']."&type=".$row_liste_composante['type_representation']; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="'<?php echo str_replace("'","\'",$row_liste_composante['intitule_ref_ind']);?>'" class="thickbox" dir="">(Typologie)</a> <?php }  ?></td>

<td class=" "><?php echo $row_liste_composante['unite']; ?></td>

<td class=" "><?php   echo $row_liste_composante["periode"]; ?>/<br />

  <?php if(isset($row_liste_composante['echelle']) && ($row_liste_composante['echelle']=="01")) echo "Nationale"; elseif(isset($row_liste_composante['echelle']) && ($row_liste_composante['echelle']=="02")) echo "Regionale"; elseif(isset($type_zone_array[$row_liste_composante["echelle"]])) echo $type_zone_array[$row_liste_composante["echelle"]];  ?></td>

<td class=" "><?php $as = explode(",",$row_liste_composante['collecte']); if(count($as)>0 && !empty($row_liste_composante['collecte'])){ $cs = array(); foreach($as as $bs){ if(isset($structure_array[$bs])) $cs[]=$structure_array[$bs]; }

if(count($cs)>0) echo implode('&nbsp;, ',$cs); else echo "<strong>Aucun</strong>"; } else echo "<strong>Aucun</strong>"; ?></td>

<!----><td class=" " align="center" ><a onclick="get_content('new_meta_donnees.php?<?php echo "codeind=".$row_liste_composante['code_ref_ind']; ?>','','modal-body_add',this.title);" data-backdrop="static" data-keyboard="false" data-toggle="modal" href="#myModal_add" title="Méta-donnée"> Editer</a></td>

<?php if(isset($_SESSION['clp_id']) && $_SESSION['clp_id']=='admin') { ?>

<td class=" " align="center">

<?php

echo do_link("","",$row_liste_composante['intitule_ref_ind'],"","edit","./","","get_content('new_indicateur_ref.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=$id","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cet indicateur ?');",0,"margin:0px 5px;",$nfile);

?></td>

</tr>

<?php } ?>

<?php } } ?>

</tbody></table>

    </div>

</div>

<!-- Fin Site contenu ici -->

           </div>

        </div>



        </div>

    </div>

    <?php include_once ("includes/footer.php");?>

</div>

</body>

</html>