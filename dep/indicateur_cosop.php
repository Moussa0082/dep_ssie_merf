<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
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
$currentPage = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$query_entete = "SELECT libelle FROM ".$database_connect_prefix."cadre_config_cosop  LIMIT 1";
      try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$libelle=explode(",",$row_entete["libelle"]);

if(isset($_GET['niveau']) && intval($_GET['niveau'])>0) $niveau = intval($_GET['niveau']); else $niveau = 0;

//$niveau=count($libelle)-1;
$niveaui=$niveau+1;

if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
$niveaui=$niveau+1;
$query_sup_activite = "DELETE FROM ".$database_connect_prefix."indicateur_cosop WHERE code_indicateur_cosop='$id' and niveau=$niveaui";
  try{
        $Result1 = $pdar_connexion->prepare($query_sup_activite);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  $insertGoTo .= "&niveau=$niveau";
  header(sprintf("Location: %s", $insertGoTo));
}

  

//  code d'insertion 
  if (isset($_POST['MM_form']) && $_POST['MM_form'] === 'form1') {
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
      $date = date("Y-m-d");
      $personnel = $_SESSION['clp_id'];

      $insertSQL = "INSERT INTO " . $database_connect_prefix . "indicateur_cosop (os_cosop, niveau, code_indicateur_cosop, intitule_indicateur_cosop, referentiel, description, date_enregistrement, id_personnel) VALUES (:os_cosop, :niveau, :code_indicateur_cosop, :intitule_indicateur_cosop, :referentiel, :description, '$date', '$personnel')";

      try {
          $stmt = $pdar_connexion->prepare($insertSQL);
          $stmt->bindParam(':os_cosop', $_POST['os_cosop'], PDO::PARAM_STR);
          $stmt->bindParam(':niveau', $niveaui, PDO::PARAM_INT);
          $stmt->bindParam(':code_indicateur_cosop', $_POST['code_indicateur_cosop'], PDO::PARAM_STR);
          $stmt->bindParam(':intitule_indicateur_cosop', $_POST['intitule_indicateur_cosop'], PDO::PARAM_STR);
          $stmt->bindParam(':referentiel', $_POST['referentiel'], PDO::PARAM_INT);
          $stmt->bindParam(':description', $_POST['description'], PDO::PARAM_STR);

          $stmt->execute();

          $insertGoTo = $_SERVER['PHP_SELF'];
          if ($stmt) {
            
              $insertGoTo .= "?code_cr=$code_cr&niveau=$niveau&insert=ok";
          } else {
            
              $insertGoTo .= "?code_cr=$code_cr&niveau=$niveau&insert=no";
          }
          header(sprintf("Location: %s", $insertGoTo));
          exit();
      } catch (Exception $e) {
          die(mysql_error_show_message($e));
      }
  }
 
  }



    // code de modification 
if (isset($_POST["MM_update"]) && !empty($_POST["MM_update"])) {
  $date = date("Y-m-d");
  $personnel = $_SESSION['clp_id'];
  $c = $_POST['id'];

  $updateSQL = "UPDATE " . $database_connect_prefix . "indicateur_cosop SET 
                  os_cosop = :os_cosop,
                  niveau = :niveau,
                  code_indicateur_cosop = :code_indicateur_cosop,
                  intitule_indicateur_cosop = :intitule_indicateur_cosop,
                  referentiel = :referentiel,
                  description = :description,
                  modifier_par = :modifier_par,
                  modifier_le = :modifier_le
                  WHERE code_indicateur_cosop = :code";

  try {
      $stmt = $pdar_connexion->prepare($updateSQL);
      $stmt->bindParam(':os_cosop', $_POST['os_cosop'], PDO::PARAM_STR);
      $stmt->bindParam(':niveau', $niveaui, PDO::PARAM_INT);
      $stmt->bindParam(':code_indicateur_cosop', $_POST['code_indicateur_cosop'], PDO::PARAM_STR);
      $stmt->bindParam(':intitule_indicateur_cosop', $_POST['intitule_indicateur_cosop'], PDO::PARAM_STR);
      $stmt->bindParam(':referentiel', $_POST['referentiel'], PDO::PARAM_INT);
      $stmt->bindParam(':description', $_POST['description'], PDO::PARAM_STR);
      $stmt->bindParam(':modifier_par', $personnel, PDO::PARAM_STR);
      $stmt->bindParam(':modifier_le', $date, PDO::PARAM_STR);
      $stmt->bindParam(':code', $c, PDO::PARAM_STR);

      $stmt->execute();

      $updateGoTo = (isset($_GET['page'])) ? $_GET['page'] : $_SERVER['PHP_SELF'];
      $updateGoTo .= ($stmt) ? "?update=ok" : "?update=no";
      $updateGoTo .= "&niveau=$niveau";
      header(sprintf("Location: %s", $updateGoTo));
  } catch (Exception $e) {
      die(mysql_error_show_message($e));
  }

 } 
  



// if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
// {
//   if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
//    $date=date("Y-m-d");  $personnel=$_SESSION['clp_id'];    //source, responsable,  %s, %s,
//     $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."indicateur_cosop (os_cosop, niveau, code_indicateur_cosop, intitule_indicateur_cosop, referentiel, description,  date_enregistrement, id_personnel) VALUES (%s, %s, %s, %s, %s, %s, '$date', '$personnel')",
	                     
// 						 GetSQLValueString($_POST['os_cosop'], "text"),
//                          GetSQLValueString($niveau+1, "int"),
// 						 GetSQLValueString($_POST['code_indicateur_cosop'], "text"),
//                          GetSQLValueString($_POST['intitule_indicateur_cosop'], "text"),
// 						 GetSQLValueString($_POST['referentiel'], "int"),
// 						 /*GetSQLValueString($_POST['source'], "text"),
// 						 GetSQLValueString($_POST['responsable'], "text"),*/
// 						 GetSQLValueString($_POST['description'], "text"));
//             }

//   try{
//         $Result1 = $pdar_connexion->prepare($insertSQL);
//         $Result1->execute();
//   }catch(Exception $e){ die(mysql_error_show_message($e)); }
//     $insertGoTo = $_SERVER['PHP_SELF'];
//     if ($Result1) $insertGoTo .= "?code_cr=$code_cr&niveau=$niveau&insert=ok"; else $insertGoTo .= "?code_cr=$code_cr&niveau=$niveau&insert=no";
//     header(sprintf("Location: %s", $insertGoTo));  exit();
//   }

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
      $id = ($_POST["MM_delete"]);
	  $niveaui=$niveau+1;
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."indicateur_cosop WHERE code_indicateur_cosop=%s and niveau=$niveaui",
                           GetSQLValueString($id, "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
        $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      $insertGoTo .= "&niveau=$niveau";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  // if (isset($_POST["MM_update"]) && !empty($_POST["MM_update"])) {
  // $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id'];   //source=%s, responsable=%s,

  // 	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."indicateur_cosop SET os_cosop=%s, niveau=%s, code_indicateur_cosop=%s, intitule_indicateur_cosop=%s, referentiel=%s, description=%s,  modifier_par='$personnel', modifier_le='$date' WHERE code_indicateur_cosop='$c'",
                                              
	// 					 GetSQLValueString($_POST['os_cosop'], "text"),
  //                        GetSQLValueString($niveau+1, "int"),
	// 					 GetSQLValueString($_POST['code_indicateur_cosop'], "text"),
  //                        GetSQLValueString($_POST['intitule_indicateur_cosop'], "text"),
	// 					 GetSQLValueString($_POST['referentiel'], "int"),
	// 					 /*GetSQLValueString($_POST['source'], "text"),
	// 					 GetSQLValueString($_POST['responsable'], "text"),*/
	// 					 GetSQLValueString($_POST['description'], "text"));

  // try{
  //       $Result1 = $pdar_connexion->prepare($insertSQL);
  //       $Result1->execute();
  // }catch(Exception $e){ die(mysql_error_show_message($e)); }
  //     $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  //   if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  //   $insertGoTo .= "&niveau=$niveau";  
  //   header(sprintf("Location: %s", $insertGoTo));
  // }


//$query_liste_indicateur = sprintf("SELECT * FROM ".$database_connect_prefix."indicateur_cosop WHERE structure=%s and projet=%s and code_cr=%s and niveau=%s ORDER BY code_indicateur_cosop",
$query_liste_indicateur = sprintf("SELECT * FROM ".$database_connect_prefix."indicateur_cosop where niveau=".($niveau+1)." ORDER BY os_cosop,code_indicateur_cosop  ");
      try{
    $liste_indicateur = $pdar_connexion->prepare($query_liste_indicateur);
    $liste_indicateur->execute();
    $row_liste_indicateur = $liste_indicateur ->fetchAll();
    $totalRows_liste_indicateur = $liste_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_activite_1 = "SELECT code,intitule FROM ".$database_connect_prefix."cadre_cosop WHERE niveau=".($niveau+1)." ";
      try{
    $liste_activite_1 = $pdar_connexion->prepare($query_liste_activite_1);
    $liste_activite_1->execute();
    $row_liste_activite_1 = $liste_activite_1 ->fetchAll();
    $totalRows_liste_activite_1 = $liste_activite_1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$niveau_array = array();
if($totalRows_liste_activite_1>0)
{
   foreach($row_liste_activite_1 as $row_liste_activite_1){  $niveau_array[$row_liste_activite_1["code"]]=$row_liste_activite_1["code"].":".$row_liste_activite_1["intitule"]; }
}

  $query_projet = "SELECT code,intitule FROM ".$database_connect_prefix."cadre_cosop WHERE niveau=".($niveau+1)." ";
        try{
    $projet = $pdar_connexion->prepare($query_projet);
    $projet->execute();
    $row_projet = $projet ->fetchAll();
    $totalRows_projet = $projet->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $liste_loc_array = array();
  if($totalRows_projet>0){   foreach($row_projet as $row_projet){ 
    $liste_loc_array[$row_projet["code"]] = $row_projet["code"].": ".$row_projet["intitule"];
  }  }

    $query_projet = "SELECT * FROM ".$database_connect_prefix."referentiel_indicateur"; 
        try{
    $projet = $pdar_connexion->prepare($query_projet);
    $projet->execute();
    $row_projet = $projet ->fetchAll();
    $totalRows_projet = $projet->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $liste_ref_array = array();
  if($totalRows_projet>0){   foreach($row_projet as $row_projet){ 
    $liste_ref_array[$row_projet["id_ref_ind"]] = $row_projet["code_ref_ind"];
  } 
 }
  
      $query_liste_nb_ind_ppa = "SELECT activite_pa, count(id_plan) as nindppa FROM ".$database_connect_prefix."plan_pluri_indicateur GROUP BY activite_pa";
try{
    $liste_nb_ind_ppa = $pdar_connexion->prepare($query_liste_nb_ind_ppa);
    $liste_nb_ind_ppa->execute();
    $row_liste_nb_ind_ppa = $liste_nb_ind_ppa ->fetchAll();
    $totalRows_liste_nb_ind_ppa = $liste_nb_ind_ppa->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $liste_nb_ind_ppa_array = array();
  if($totalRows_liste_nb_ind_ppa>0){ foreach($row_liste_nb_ind_ppa as $row_liste_nb_ind_ppa){
    $liste_nb_ind_ppa_array[$row_liste_nb_ind_ppa["activite_pa"]] = $row_liste_nb_ind_ppa["nindppa"];
  }
 }
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
       // sDom:"<'row'<'dataTables_header clearfix'<'col-md-7'lT><'col-md-5'Cf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
       // oTableTools:{aButtons:["copy","print","csv","xls",{"sExtends": "pdf","sPdfOrientation": "landscape"}],sSwfPath:"./swf/copy_csv_xls_pdf.swf"},
       // "aaSorting": [],
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
<div class="widget box ">
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Indicateurs COSOP </h4>
    <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2){?>
<?php   echo do_link("","","Ajout un indicateur cosop","<i class=\"icon-plus\"> Ajouter un indicateur </i>","","./","pull-right p11","get_content('new_indicateur_cosop.php','niveau=$niveau','modal-body_add',this.title);",1,"",$nfile); ?>

<form name="form38" id="form38" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="pull-right">
  <select name="niveau" onchange="form38.submit();" style="background-color: #FFFF00; padding: 7px; width: 150px;" class="btn p11">
    <!----><option value="">-- Niveau --</option>
    <?php for($i=0;$i<count($libelle);$i++) { ?>
    <option value="<?php echo $i; ?>" <?php if($i==$niveau) echo "selected='SELECTED'"; ?>><?php echo $libelle[$i]; ?></option>
    <?php } ?>
  </select>
</form>
    <?php } ?>
</div>

<?php

if($totalRows_entete>0){
?>

<div class="widget-content">
<div align="center"></div>
<table class="table table-striped table-bordered table-hover table-responsive table-colvis datatable dataTable hide_befor_load" id="mytable" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">composante</th>-->
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><?php echo $libelle[$niveau]; ?> </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Code</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Indicateur </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Indicateurs d'étapes </th>
<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">P&eacute;riodicit&eacute; </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Source</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Responsable</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Description</th>-->
<?php if(isset($_SESSION['clp_id']) && ($_SESSION['clp_id']=='admin')) { ?>
<th class="sorting" role="" tabindex="0" aria-controls="" aria-label="" width="80">Actions</th>
<?php } ?>

</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php if($totalRows_liste_indicateur>0) { $i=0; foreach($row_liste_indicateur as $row_liste_indicateur){ if(isset($row_liste_indicateur['code_indicateur_cosop']) && !empty($row_liste_indicateur['code_indicateur_cosop'])) $id = $row_liste_indicateur['code_indicateur_cosop']; if(isset($liste_nb_ind_ppa_array[$row_liste_indicateur["code_indicateur_cosop"]])) $textppa=$liste_nb_ind_ppa_array[$row_liste_indicateur["code_indicateur_cosop"]]." saisi(s)"; else $textppa="Editer"; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" " title="<?php if(isset($liste_loc_array[$row_liste_indicateur['os_cosop']])) echo $liste_loc_array[$row_liste_indicateur['os_cosop']]; ?>"><?php if(isset($liste_loc_array[$row_liste_indicateur['os_cosop']])) echo ($liste_loc_array[$row_liste_indicateur['os_cosop']]); ?></td>
<td class=" "><?php echo $row_liste_indicateur['code_indicateur_cosop']; ?></td>
<td class=" "><?php echo $row_liste_indicateur['intitule_indicateur_cosop']; ?></td>
<td class=" "><?php //if(isset($liste_ref_array[$row_liste_indicateur['referentiel']])) echo $liste_ref_array[$row_liste_indicateur['referentiel']]; else echo "-"; ?>
  <strong><a onclick="get_content('plan_indicateur_ppa.php','<?php echo "id_act=".urlencode($row_liste_indicateur['code_indicateur_cosop']); ?>','modal-body_add','<?php echo urlencode($row_liste_indicateur["code_indicateur_cosop"]); ?>','iframe');"
 data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false"  title="" class="thickbox" dir=""><?php echo $textppa; ?></a></strong></td>
<!--<td class=" "><?php echo $row_liste_indicateur['periodicite']; ?></td>
<td class=" "><?php echo $row_liste_indicateur['source']; ?></td>
<td class=" "><?php echo $row_liste_indicateur['responsable']; ?></td>
<td class=" "><?php echo $row_liste_indicateur['description']; ?></td>-->
<?php if(isset($_SESSION['clp_id']) && $_SESSION['clp_id']=='admin') { ?>
<td class=" " align="center" nowrap="nowrap"><?php
echo do_link("","","Modifier Indicateur ".$row_liste_indicateur['intitule_indicateur_cosop'],"","edit","./","","get_content('new_indicateur_cosop.php','id=$id&niveau=$niveau','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id."&niveau=$niveau","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cet indicateur ?');",0,"margin:0px 5px;",$nfile);
?></td>
<?php } ?>
</tr>
<?php } } ?>
</tbody></table>

    </div>
<?php } else echo ("<h2><center>Aucun niveau !</center></h2>"); ?>
</div>
</div>

<!-- Fin Site contenu ici -->

            </div>

        </div>



        </div>

    </div> <?php include_once 'modal_add.php'; ?>

    <?php include_once ("includes/footer.php");?>

</div>

</body>

</html>