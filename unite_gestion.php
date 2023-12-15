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

if (isset($_GET["id_sup"])) {
  $id = ($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."ugl WHERE code_ugl=%s",
                       GetSQLValueString($id, "text"));
    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
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

        $query_sup_import_annee = "DELETE FROM ".$database_connect_prefix."ugl ";// WHERE structure='".$_SESSION["clp_structure"]."'";
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
              $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."ugl (ccouleur, structure, abrege_ugl, code_ugl, nom_ugl, id_personnel) VALUES (%s, %s, %s, %s, %s, '$personnel')",
                              GetSQLValueString(trim($rowData[0][2]), "text"),
                              GetSQLValueString($_SESSION["clp_structure"], "text"),
            				  GetSQLValueString(trim($rowData[0][5]), "text"),
                              GetSQLValueString(trim($rowData[0][9]), "text"),
            				  GetSQLValueString(trim($rowData[0][2]), "text"));
                try{
                    $Result1 = $pdar_connexion->prepare($insertSQL);
                    $Result1->execute();
                }catch(Exception $e){ /*die(mysql_error_show_message($e));*/ }
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

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $personnel=$_SESSION['clp_id'];
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."ugl (couleur, structure, abrege_ugl, code_ugl, nom_ugl, chef_lieu, id_personnel) VALUES (%s, %s, %s, %s, %s, %s, '$personnel')",
                        GetSQLValueString($_POST['couleur'], "text"),
                        GetSQLValueString($_SESSION["clp_structure"], "text"),
  					    GetSQLValueString($_POST['sigle'], "text"),
                        GetSQLValueString($_POST['code'], "text"),
  					    GetSQLValueString($_POST['nom'], "text"),
						GetSQLValueString($_POST['chef_lieu'], "text"));
    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }

    $insertGoTo = $_SERVER['PHP_SELF'];
    if($Result1) $insertGoTo = "?insert=ok";
    else $insertGoTo = "?insert=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
      $id = ($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."ugl WHERE code_ugl=%s",
                           GetSQLValueString($id, "text"));
      try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
      }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];  $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."ugl SET couleur=%s, abrege_ugl=%s, code_ugl=%s, nom_ugl=%s, chef_lieu=%s, modifier_par='$personnel', modifier_le='$date' WHERE code_ugl=%s",
                        GetSQLValueString($_POST['couleur'], "text"),
                        //GetSQLValueString(implode('|',$_POST['structure'])."|", "text"),
  					    GetSQLValueString($_POST['sigle'], "text"),
                        GetSQLValueString($_POST['code'], "text"),
  					    GetSQLValueString($_POST['nom'], "text"),
						GetSQLValueString($_POST['chef_lieu'], "text"),
                        GetSQLValueString($id, "text"));
    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }

    $insertGoTo = $_SERVER['PHP_SELF'];
    if($Result1) $insertGoTo = "?update=ok";
    else $insertGoTo = "?update=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['MM_update'];
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."ugl SET region_concerne=%s, modifier_le='$date', modifier_par='$personnel' WHERE  code_ugl=%s",
                      GetSQLValueString(implode('|',$_POST['region'])."|", "text"),
                      GetSQLValueString($_POST['ugl'], "text"));
    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }

    $insertGoTo = $_SERVER['PHP_SELF'];
    if($Result1) $insertGoTo = "?update=ok";
    else $insertGoTo = "?update=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();;
  }
}

$query_liste_ugl = "SELECT * FROM ".$database_connect_prefix."ugl ";
try{
    $liste_ugl = $pdar_connexion->prepare($query_liste_ugl);
    $liste_ugl->execute();
    $row_liste_ugl = $liste_ugl ->fetchAll();
    $totalRows_liste_ugl = $liste_ugl->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//les regions
$query_liste_region = "SELECT * FROM ".$database_connect_prefix."departement ";
try{
    $liste_region = $pdar_connexion->prepare($query_liste_region);
    $liste_region->execute();
    $row_liste_region = $liste_region ->fetchAll();
    $totalRows_liste_region = $liste_region->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_liste_region_array = array();  $liste_liste_region_arrayV = array();
if($totalRows_liste_region>0){ foreach($row_liste_region as $row_liste_region){
$liste_liste_region_arrayV[$row_liste_region["code_departement"]]=$row_liste_region["nom_departement"];
$liste_liste_region_array[$row_liste_region["code_departement"]]=$row_liste_region["code_departement"];
} }
//Chef lieu departement
$query_le_departement = "SELECT distinct code_commune, nom_commune FROM ".$database_connect_prefix."commune";
try{
    $le_departement = $pdar_connexion->prepare($query_le_departement);
    $le_departement->execute();
    $row_le_departement = $le_departement ->fetchAll();
    $totalRows_le_departement = $le_departement->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$le_departement_array = array();
if($totalRows_le_departement>0){ foreach($row_le_departement as $row_le_departement){
  $le_departement_array[$row_le_departement["code_commune"]]=$row_le_departement["nom_commune"];
} }
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
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>
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
<?php include_once("modal_add.php"); ?>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
.menu_head {
  padding: 5px; cursor: pointer; background-color: #060; color: #FFF;
}
</style>
<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Listes des zones</h4>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
//echo do_link("","","Importation d&eacute;puis un format excel","<i class=\"icon-plus\"> Importer </i>","","./","pull-right p11","get_content('import.php','id=ugl','modal-body_add',this.title);",1,"",$nfile);

echo do_link("","","Ajout de zones","<i class=\"icon-plus\"> Nouvel zone </i>","","./","pull-right p11","get_content('new_ugl.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div>

<div class="widget-content">
<table class="table table-striped table-bordered table-hover table-responsive table-colvis datatable dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Code</th>

<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Abr&eacute;viation</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Libell&eacute;</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Zone d'intervention  </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Couleur </th>
<!--<th  role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Shapes files </th>-->
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="80">Actions</th>
<?php } ?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php if($totalRows_liste_ugl>0) { $i=0; foreach($row_liste_ugl as $row_liste_ugl){ $c = array(); $id = $row_liste_ugl['code_ugl']; $code = $row_liste_ugl['code_ugl']; $sigleu = $row_liste_ugl['abrege_ugl']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo $code; ?></td>

<td class=" "><?php echo $row_liste_ugl['abrege_ugl']; $ab=$row_liste_ugl['abrege_ugl']; ?></td>
<td class=" "><?php echo $row_liste_ugl['nom_ugl']; ?></td>
<td class=" ">&nbsp;
  <b>
  <?php if(isset($row_liste_ugl["region_concerne"]) && $row_liste_ugl["region_concerne"]!="|" && $row_liste_ugl["region_concerne"]!="") {$a = explode("|",$row_liste_ugl["region_concerne"]); if(isset($a) && sizeof($a)>0){   $c[]=do_link("","","Edition de la zone d'intervention $ab ","<span title=\""."Edition des pr&eacute;fectures "."\">".(sizeof($a)-1)." pr&eacute;fectures</span>","","./","","get_content('new_region_ugl.php','id=$code&sigleu=$sigleu','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile); echo implode('; &nbsp;',$c); }} else { $c[]=do_link("","","Edition de la zone d'intervention $ab ","<span title=\"Edition des pr&eacute;fectures\">Editer</span>","","./","","get_content('new_region_ugl.php','id=$code&sigleu=$sigleu','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile); echo implode('; &nbsp;',$c); } ?>
  </b><?php if(isset($le_departement_array[$row_liste_ugl["chef_lieu"]])) echo "(<i>".$le_departement_array[$row_liste_ugl["chef_lieu"]]."</i>)"; ?></td>
<td class=" "><div class="progress-bar progress-bar-info" style="width: 100%;background-color: <?php echo $row_liste_ugl['couleur']; ?>;height: 20px;"><?php echo $row_liste_ugl['couleur']; ?></div></td>
<!--<td class=" ">&nbsp;</td>-->
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){
  //if(in_array($_SESSION["clp_structure"],explode("|",$row_liste_ugl['structure']))){ ?>
<td class=" " align="center">
<?php
echo do_link("","","Modifier zone ".$row_liste_ugl['nom_ugl'],"","edit","./","","get_content('new_ugl.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette zone ".$row_liste_ugl['nom_ugl']."');",0,"margin:0px 5px;",$nfile);
?></td>
<?php } ?>
</tr>
<?php } } ?>
</tbody></table>
</div>

</div>

<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>