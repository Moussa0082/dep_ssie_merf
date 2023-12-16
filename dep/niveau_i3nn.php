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
if(isset($_GET['niveau']) && $_GET['niveau']!="") {$_SESSION["niveau"]=$_GET['niveau']; $niveau=$_SESSION["niveau"];} else { $_SESSION["niveau"]=0; $niveau=$_SESSION["niveau"]; }



$where = ($niveau==0)?" niveau =1":" niveau = ".$niveau." ";
if(isset($_GET['cmp']) && $_GET['cmp']!="") $wh = " and code=".GetSQLValueString($_GET['cmp'], "text"); else $wh = "";

$editFormAction = $_SERVER['PHP_SELF'];
$currentPage = $_SERVER['PHP_SELF']."?niveau=$niveau";
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//import
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form0"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert"))
  {
    $poids_max=2048576; //Poids maximal du fichier en octets
    $extensions_autorisees=array('xls','xlsx'); //Extensions autorisées ,'csv'
    $url_site='./attachment/'; //Adresse où se trouve le fichier upload.
    $page = $_SERVER['PHP_SELF'];
    $ext = substr(strrchr($_FILES['fichier']['name'], "."), 1);

    if(in_array($ext,$extensions_autorisees))
    {
      if($_FILES['fichier']['size']>$poids_max)
      {
        $message='Un ou plusieurs fichiers sont trop lourds !';
        echo $message;
      }
      elseif(isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0)
      {
        $inputFileName=$url_site.$_FILES['fichier']['name'];
        move_uploaded_file($_FILES['fichier']['tmp_name'],$inputFileName);

        require_once('Classes/PHPExcel.php');
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
            . '": ' . $e->getMessage());
        }

        $query_sup_import_annee = sprintf("DELETE from ".$database_connect_prefix."cadre_i3n WHERE niveau=%s",
                             GetSQLValueString(intval($_GET["niveau"])+1, "int"));
  try{
        $Result1 = $pdar_connexion->prepare($query_sup_import_annee);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = 5; $row <= $highestRow; $row++)
        {
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
            NULL, TRUE, FALSE);
            if(!empty($rowData[0][2]) && $rowData[0][2]!='Code')
            {
              $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."cadre_i3n (intitule,niveau,code,parent, id_personnel) VALUES (%s, %s, %s, %s, '$personnel')",
                                   GetSQLValueString(trim($rowData[0][4]), "text"),
                                   GetSQLValueString(intval($_GET["niveau"])+1, "int"),
            					     GetSQLValueString(trim($rowData[0][2]), "text"),
                                   GetSQLValueString((intval($_GET['niveau'])==0)?0:trim($tableauValeurs[2]), "text"));
  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
              }
          }
          unlink($inputFileName);
          if($Result1) $insertGoTo = $page."?import=ok";
          else $insertGoTo = $page."?import=no";
          header(sprintf("Location: %s", $insertGoTo)); exit();
        }
    }
    else
    {
      $insertGoTo = $page."?import=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }
  }
}

//Suppression multiple
if ((isset($_POST["id_val"]) && !empty($_POST["id_val"]))) {
      $id = implode(',',$_POST["id_val"]);
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."cadre_i3n WHERE code in ($id)");

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
        $insertGoTo = $_SERVER['PHP_SELF']."?niveau=".intval($_GET["niveau"]);
      if ($Result1) $insertGoTo .= "&del=ok"; else $insertGoTo .= "&del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
}
 

    //suppression
    if ((isset($_GET["id_sup"]) && !empty($_GET["id_sup"]))) {
      $id = ($_GET["id_sup"]);
      $query_sup_activite = "DELETE from ".$database_connect_prefix."cadre_i3n WHERE code='$id'";

  try{
        $Result1 = $pdar_connexion->prepare($query_sup_activite);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  $insertGoTo .= "&niveau=$niveau";
  header(sprintf("Location: %s", $insertGoTo));
    }
    // ------
  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
      $id = ($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."cadre_i3n WHERE code=%s",
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

// if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
// {

//   if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
//    $personnel=$_SESSION['clp_id'];
//         $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."cadre_i3n (intitule,niveau,code,parent, id_personnel) VALUES (%s, %s, %s, %s, '$personnel')",
//                          GetSQLValueString($_POST['intitule'], "text"),
//                          GetSQLValueString($_POST['niveau'], "int"),
//   					     GetSQLValueString($_POST['code'], "text"),
//                          GetSQLValueString($_POST['parent'], "text"));

//   try{
//         $Result1 = $pdar_connexion->prepare($insertSQL);
//         $Result1->execute();
//   }catch(Exception $e){ die(mysql_error_show_message($e)); }
    
//     $insertGoTo = $_SERVER['PHP_SELF']."?niveau=".intval($_GET["niveau"]);
//     if ($Result1) $insertGoTo .= "&insert=ok&add_new=1"; else $insertGoTo .= "&insert=no";
//     $insertGoTo .= (isset($_POST['sc']))?"&sc=".$_POST['sc']:"";
//     header(sprintf("Location: %s", $insertGoTo));
//   }

  // if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
  //     $id = ($_POST["MM_delete"]);
  //     $insertSQL = sprintf("DELETE from ".$database_connect_prefix."cadre_i3n WHERE code=%s",
  //                          GetSQLValueString($id, "text"));

  // try{
  //       $Result1 = $pdar_connexion->prepare($insertSQL);
  //       $Result1->execute();
  // }catch(Exception $e){ die(mysql_error_show_message($e)); }
  //       $insertGoTo = $_SERVER['PHP_SELF']."?niveau=".intval($_GET["niveau"]);
  //     if ($Result1) $insertGoTo .= "&del=ok"; else $insertGoTo .= "&del=no";
  //     header(sprintf("Location: %s", $insertGoTo)); exit();
  //   }

//   if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
//     $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id'];

//   	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."cadre_i3n SET intitule=%s, niveau=%s, code=%s, parent=%s, modifier_par='$personnel', modifier_le='$date' WHERE code='$c'",
//                          GetSQLValueString($_POST['intitule'], "text"),
//                          GetSQLValueString($_POST['niveau'], "int"),
//   					     GetSQLValueString($_POST['code'], "text"),
//                          GetSQLValueString($_POST['parent'], "text"));


//   try{
//         $Result1 = $pdar_connexion->prepare($insertSQL);
//         $Result1->execute();
//   }catch(Exception $e){ die(mysql_error_show_message($e)); }
//      // }
//    // else $Result1 = false;

//     $insertGoTo = $_SERVER['PHP_SELF']."?niveau=".intval($_GET["niveau"]);
//     if ($Result1) $insertGoTo .= "&update=ok"; else $insertGoTo .= "&update=no";
//     $insertGoTo .= (isset($_POST['sc']))?"&sc=".$_POST['sc']:"";
//     header(sprintf("Location: %s", $insertGoTo));
//   }
// }
// action ajout
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1")) {

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
      $personnel = $_SESSION['clp_id'];
      $intitule = $_POST['intitule'];
      $niveau = $_POST['niveau'];
      $code = $_POST['code'];
      $parent = $_POST['parent'];
      $budget = $_POST['budget'];

      $insertSQL = "INSERT INTO " . $database_connect_prefix . "cadre_i3n (code, intitule, niveau, parent, budget, id_personnel, date_enregistrement, modifier_le, modifier_par) 
                    VALUES (:code, :intitule, :niveau, :parent, :budget, :personnel, NOW(), NULL, NULL)";

      try {
          $stmt = $pdar_connexion->prepare($insertSQL);
          // $stmt->bindParam(':projet', $_POST['projet'], PDO::PARAM_STR);
          $stmt->bindParam(':code', $code, PDO::PARAM_STR);
          $stmt->bindParam(':intitule', $intitule, PDO::PARAM_STR);
          $stmt->bindParam(':niveau', $niveau, PDO::PARAM_INT);
          $stmt->bindParam(':parent', $parent, PDO::PARAM_STR);
          $stmt->bindParam(':budget', $budget, PDO::PARAM_STR);
          $stmt->bindParam(':personnel', $personnel, PDO::PARAM_STR);


          $stmt->execute();
      } catch (Exception $e) {
          die(mysql_error_show_message($e));
      }

      $insertGoTo = $_SERVER['PHP_SELF'] . "?niveau=" . intval($_GET["niveau"]);
      if ($stmt) $insertGoTo .= "&insert=ok&add_new=1"; else $insertGoTo .= "&insert=no";
      $insertGoTo .= (isset($_POST['sc'])) ? "&sc=" . $_POST['sc'] : "";
      header(sprintf("Location: %s", $insertGoTo));
  }}

  // Modification
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1")) {

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
      $date = date("Y-m-d");
      $personnel = $_SESSION['clp_id'];
      $c = $_POST['id'];
      $intitule = $_POST['intitule'];
      $niveau = $_POST['niveau'];
      $budget = $_POST['budget'];
      $code = $_POST['code'];
      $parent = $_POST['parent'];

      $updateSQL = "UPDATE " . $database_connect_prefix . "cadre_i3n 
                    SET intitule=:intitule, niveau=:niveau, code=:code,  parent=:parent,budget=:budget, modifier_par=:personnel, modifier_le=:date 
                    WHERE code=:c";

      try {
          $stmt = $pdar_connexion->prepare($updateSQL);
          $stmt->bindParam(':intitule', $intitule, PDO::PARAM_STR);
          $stmt->bindParam(':niveau', $niveau, PDO::PARAM_INT);
          $stmt->bindParam(':code', $code, PDO::PARAM_STR);
          $stmt->bindParam(':parent', $parent, PDO::PARAM_STR);
          $stmt->bindParam(':budget', $budget, PDO::PARAM_STR);
          $stmt->bindParam(':personnel', $personnel, PDO::PARAM_STR);
          $stmt->bindParam(':date', $date, PDO::PARAM_STR);
          $stmt->bindParam(':c', $c, PDO::PARAM_STR);

          $stmt->execute();
      } catch (Exception $e) {
          die(mysql_error_show_message($e));
      }

      $insertGoTo = $_SERVER['PHP_SELF'] . "?niveau=" . intval($_GET["niveau"]);
      if ($stmt) $insertGoTo .= "&update=ok"; else $insertGoTo .= "&update=no";
      $insertGoTo .= (isset($_POST['sc'])) ? "&sc=" . $_POST['sc'] : "";
      header(sprintf("Location: %s", $insertGoTo));
  }

}



// fin action 1


if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  $form= $_POST['form']; $noms= $form['lib']; $nombre=count($noms); $types= $form['indic'];

    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."cadre_config_i3n (nombre, libelle, type) VALUES (%s, %s, %s)",
                        GetSQLValueString($nombre, "int"),
  					    GetSQLValueString(implode(",",$noms), "text"),
                        GetSQLValueString(implode(",",$types), "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $insertGoTo = $_SERVER['PHP_SELF']."?niveau=".intval($_GET["niveau"]);
    if ($Result1) $insertGoTo .= "&insert=ok"; else $insertGoTo .= "&insert=no";
    header(sprintf("Location: %s", $insertGoTo));
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  $form= $_POST['form']; $noms= $form['lib']; $nombre=count($noms); $types= $form['indic'];

    $query_liste_actpa = sprintf("SELECT nombre FROM ".$database_connect_prefix."cadre_config_i3n");
	      	try{
    $liste_actpa = $pdar_connexion->prepare($query_liste_actpa);
    $liste_actpa->execute();
    $row_liste_actpa = $liste_actpa ->fetch();
    $totalRows_liste_actpa = $liste_actpa->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


    if($totalRows_liste_actpa>0 && $row_liste_actpa["nombre"]==$nombre+1){
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."cadre_i3n WHERE niveau=%s",
                           GetSQLValueString($nombre+1, "int"));

  try{
        $Result0 = $pdar_connexion->prepare($insertSQL);
        $Result0->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
    }

  	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."cadre_config_i3n SET nombre=%s, libelle=%s, type=%s",
  					   GetSQLValueString($nombre, "int"),
  					   GetSQLValueString(implode(",",$noms), "text"),
                       GetSQLValueString(implode(",",$types), "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $insertGoTo = $_SERVER['PHP_SELF']."?niveau=".intval($_GET["niveau"]);
    if ($Result1) $insertGoTo .= "&update=ok"; else $insertGoTo .= "&update=no";
    header(sprintf("Location: %s", $insertGoTo));
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

  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->
theme_folder;?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->

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
<script>
	$(document).ready(function() {
	  <?php if(isset($_GET["add_new"]) && $_GET["add_new"]==1){ ?>
	  $("#new_cadre").click();
      <?php } ?>
    });
</script>
<div class="widget box ">
<!--<div class="widget-header1"> <center><h4><?php if(!empty($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>-->
<div class="widget-header"> <h4><i class="icon-reorder"></i> Niveau sectoriel </h4>
<?php
$query_entete = "SELECT * FROM ".$database_connect_prefix."cadre_config_i3n LIMIT 1";
      	try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$libelle = array();
if($totalRows_entete>0){ $libelle=explode(",",$row_entete["libelle"]); $type=explode(",",$row_entete["type"]);
  if(isset($libelle[0]) && !empty($libelle[0])){ $i=count($libelle)-1; $libelle = array_reverse($libelle); foreach($libelle as $lib){
  echo do_link("",$_SERVER['PHP_SELF']."?niveau=".$i,"$lib","<i> $lib </i>","","./","pull-right p11","",0,"",$nfile);
  $i--; } echo '<div class="clear h0"></div>'; }
$libelle = array_reverse($libelle);
if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2){  
if(isset($niveau)){ $lib = $libelle[$niveau]; if($niveau<count($libelle)) {
  //echo do_link("","","Importation d&eacute;puis un format excel","<i class=\"icon-plus\"> Importer </i>","","./","pull-right p11","get_content('import.php','id=plan_analytique&niveau=".($niveau+1)."','modal-body_add',this.title);",1,"",$nfile);
  echo do_link("new_cadre_niveau_i3n","","Ajout $lib","<i class=\"icon-plus\" btn-warning> Ajouter $lib </i>","","./","pull-right p11","get_content('new_cadre_i3n.php','niveau=".($niveau+1)."','modal-body_add',this.title);",1,"",$nfile);
  //echo do_link("","plan_analytique_projet.php","Retour","Retour","","./","pull-right p11","",0,"",$nfile);
  }
}
if(isset($_SESSION["clp_niveau"]) && $_SESSION["clp_niveau"]==1)
  echo do_link("","","G&eacute;rer les niveaux","<i class=\"icon-plus\"> Gestion des niveaux </i>","","./","pull-right p11","get_content('new_cadre_niveau_i3n.php','','modal-body_add',this.title);",1,"",$nfile);
    }
?>
</div>

<div class="widget-content" style="display: block;">
<?php $where = ($niveau==0)?" niveau =1":" niveau = ".($niveau+1)." ";

$query_liste_activite_1 = "SELECT * FROM ".$database_connect_prefix."cadre_i3n WHERE $where  ORDER BY niveau,code ASC";
      	try{
    $liste_activite_1 = $pdar_connexion->prepare($query_liste_activite_1);
    $liste_activite_1->execute();
    $row_liste_activite_1 = $liste_activite_1 ->fetchAll();
    $totalRows_liste_activite_1 = $liste_activite_1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
/*if($type[$niveau]==1)
{



} */
  $query_projet = "SELECT code,intitule FROM ".$database_connect_prefix."cadre_i3n WHERE niveau=".($niveau)." ";
        	try{
    $projet = $pdar_connexion->prepare($query_projet);
    $projet->execute();
    $row_projet = $projet ->fetchAll();
    $totalRows_projet = $projet->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $liste_loc_array = array();
  if($totalRows_projet>0){  foreach($row_projet as $row_projet){ 
    $liste_loc_array[$row_projet["code"]] = $row_projet["code"];
  } }

  $query_entete = "SELECT libelle FROM ".$database_connect_prefix."cadre_config_i3n LIMIT 1";
  try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $libelle1 = array();
  if($totalRows_entete>0){ $libelle1=explode(",",$row_entete["libelle"]);}
?>
<form name="form1" action="" method="post">
<table id="example" border="0" align="center" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive  table-colvis table-checkable datatable dataTable" >
<?php if(count($libelle)>0 && $niveau<count($libelle)){ ?>
                <thead>
                  <tr>
                    <th class="checkbox-column"> <input type="checkbox" class="uniform"> </th>
                    <td width="120"><strong>Code</strong></td>
                    <td width="120"><strong>Budget</strong></td>
<?php if($niveau!=0) { ?>
                    <td><?php echo "<strong>".$libelle1[$niveau-1]."</strong>"; ?></td>
<?php } ?>
                    <td><?php echo "<strong>$libelle[$niveau]</strong>"; ?></td>
                    
                    <?php if(isset($_SESSION['clp_id']) && ($_SESSION['clp_id']=='admin')) { ?>
                    <td width="80"><strong>Actions</strong></td>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody>
<?php if($totalRows_liste_activite_1>0){ foreach($row_liste_activite_1 as $row_liste_activite_1){  $id = $row_liste_activite_1["id"]; $code = $row_liste_activite_1["code"]; $parent = $row_liste_activite_1["parent"]; $budget = $row_liste_activite_1["budget"]; ?>
                <tr>
                    <td class="checkbox-column"> <input type="checkbox" name="id_val[]" value="<?php echo $code; ?>" class="uniform"> </td>
                    <td><?php echo $code; ?></td>
                    <td><?php echo $budget>0 ? $budget : "Ancun budget allouer"; ?></td>
<?php if($niveau!=0) { ?>
                    <td><?php echo $liste_loc_array[$parent]; ?></td>
<?php } ?>
                    <td><?php echo $row_liste_activite_1["intitule"]; ?></td>

                    <?php if(isset($_SESSION['clp_id']) && ($_SESSION['clp_id']=='admin')) { ?>
                    <td class=" " align="center">
                    <?php
if(isset($_SESSION["clp_id"]) && $_SESSION["clp_id"]=='admin'){
                    echo do_link("","","Modifier ".$libelle[$niveau],"","edit","./","","get_content('new_cadre_i3n.php','id=$code&niveau=".($niveau+1)."','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);
                    echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$code."&niveau=$niveau","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ".$libelle[$niveau]."');",0,"margin:0px 5px;",$nfile);
}
                    ?>                    </td>
<?php } ?>
                </tr>
<?php } } ?>
                </tbody>
<?php } else { ?>
                <tr>
                  <td><div align="center" class=""><h2>Aucun R&eacute;sultat</h2></div></td>
                </tr>
                <?php } ?>
            </table>
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<div class="row"> <div class="table-footer"> <div class="col-md-6"> <div class="table-actions"> <label>Pour la s&eacute;lection :</label> <select onchange="if(confirm('Vous confirmez la suppression multiple ?')) form1.submit();" class="select2" data-minimum-results-for-search="-1" data-placeholder="S&eacute;lection..."> <option value=""></option> <option value="Delete">Supprimer</option>  </select> </div> </div></div> </div>
<?php } ?>
</form>
<?php } else {
if(isset($_SESSION["clp_id"]) && $_SESSION["clp_id"]=='admin')
  echo do_link("","","G&eacute;rer les niveaux","<i class=\"icon-plus\"> Gestion des niveaux </i>","","./","pull-right p11","get_content('new_cadre_niveau_i3n.php','','modal-body_add',this.title);",1,"",$nfile);

echo ("<h2><center>Aucun niveau !</center></h2>"); } ?>

    </div>
</div>

<!-- Fin Site contenu ici -->

            </div>

        </div>



        </div>

    </div>  <?php include_once 'modal_add.php'; ?>

    <?php include_once ("includes/footer.php");?>

</div>

</body>

</html>