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
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."pde WHERE code_pde=%s",
                       GetSQLValueString($id, "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
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

        mysql_select_db($database_pdar_connexion, $pdar_connexion);
        $query_sup_import_annee = "DELETE FROM ".$database_connect_prefix."pde ";// WHERE structure='".$_SESSION["clp_structure"]."'";
        $Result1 = mysql_query($query_sup_import_annee, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

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
              $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."pde (code_pde, nom_pde, id_personnel) VALUES (%s, %s, '$personnel')",
                              GetSQLValueString(trim($rowData[0][9]), "text"),
            				  GetSQLValueString(trim($rowData[0][2]), "text"));
							 
              $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
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
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."pde (couleur, zone_pde, code_pde, nom_pde, region, id_personnel) VALUES (%s, %s, %s, %s, %s, '$personnel')",
                        GetSQLValueString($_POST['couleur'], "text"),
                        GetSQLValueString(implode(',',$_POST['zone_pde']), "text"),
  					    GetSQLValueString($_POST['code_pde'], "text"),
                        GetSQLValueString($_POST['nom_pde'], "text"),
  					    GetSQLValueString($_POST['region'], "text"));


    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    $insertGoTo = $_SERVER['PHP_SELF'];
    if($Result1) $insertGoTo = "?insert=ok";
    else $insertGoTo = "?insert=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
      $id = ($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."ugl WHERE code_ugl=%s",
                           GetSQLValueString($id, "text"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];  $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."pde SET couleur=%s, zone_pde=%s, code_pde=%s, nom_pde=%s, region=%s, modifier_par='$personnel', modifier_le='$date' WHERE code_pde=%s",
                        GetSQLValueString($_POST['couleur'], "text"),
                        GetSQLValueString(implode(',',$_POST['zone_pde']), "text"),
  					  	GetSQLValueString($_POST['code_pde'], "text"),
						GetSQLValueString($_POST['nom_pde'], "text"),
                        GetSQLValueString($_POST['region'], "text"),
                        GetSQLValueString($id, "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

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

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    $insertGoTo = $_SERVER['PHP_SELF'];
    if($Result1) $insertGoTo = "?update=ok";
    else $insertGoTo = "?update=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();;
  }
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ugl = "SELECT * FROM ".$database_connect_prefix."pde";
$liste_ugl = mysql_query($query_liste_ugl, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_ugl = mysql_fetch_assoc($liste_ugl);
$totalRows_liste_ugl = mysql_num_rows($liste_ugl);

//les regions
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_region = "SELECT * FROM ".$database_connect_prefix."departement ";
$liste_region = mysql_query($query_liste_region, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_region = mysql_fetch_assoc($liste_region);
$totalRows_liste_region = mysql_num_rows($liste_region);
$liste_liste_region_array = array();  $liste_liste_region_arrayV = array();
if($totalRows_liste_region>0){  do{
$liste_liste_region_arrayV[$row_liste_region["code_departement"]]=$row_liste_region["nom_departement"];
$liste_liste_region_array[$row_liste_region["code_departement"]]=$row_liste_region["code_departement"];
}while($row_liste_region  = mysql_fetch_assoc($liste_region));  }


//Chef lieu departement
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_le_departement = "SELECT * FROM ".$database_connect_prefix."ugl";
$le_departement = mysql_query($query_le_departement, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_le_departement = mysql_fetch_assoc($le_departement);
$totalRows_le_departement = mysql_num_rows($le_departement);
$le_departement_array = array();
if($totalRows_le_departement>0){  do{
  $le_departement_array[$row_le_departement["code_ugl"]]=$row_le_departement["nom_ugl"];
}while($row_le_departement  = mysql_fetch_assoc($le_departement));  }

//communes polaris褳
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_les_communes = "SELECT * FROM ".$database_connect_prefix."commune";
$les_communes = mysql_query($query_les_communes, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_les_communes = mysql_fetch_assoc($les_communes);
$totalRows_les_communes = mysql_num_rows($les_communes);
$les_communes_array = array();
if($totalRows_les_communes>0){  do{
  $les_communes_array[$row_les_communes["code_commune"]]=$row_les_communes["nom_commune"];
}while($row_les_communes  = mysql_fetch_assoc($les_communes));  }
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
<div class="widget-header"> <h4><i class="icon-reorder"></i> Liste des PDE</h4>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
//echo do_link("","","Importation d&eacute;puis un format excel","<i class=\"icon-plus\"> Importer </i>","","./","pull-right p11","get_content('import.php','id=ugl','modal-body_add',this.title);",1,"",$nfile);

echo do_link("","","Ajout de PDE","<i class=\"icon-plus\"> Nouveau PDE </i>","","./","pull-right p11","get_content('new_pde.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div>

<div class="widget-content">
<table class="table table-striped table-bordered table-hover table-responsive table-tabletools table-colvis datatable dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
  <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Unit&eacute; de gestion </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Code</th>

<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Abr&eacute;viation</th>-->
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Nom du PDE </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Communes polaris&eacute;  </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Couleur </th>
<th  role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Shapes files </th>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="80">Actions</th>
<?php } ?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php if($totalRows_liste_ugl>0) { $i=0;   do { $c = array(); $id = $row_liste_ugl['code_pde']; $code = $row_liste_ugl['code_pde']; $sigleu = $row_liste_ugl['nom_pde']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
  <td class=" "><?php if(isset($le_departement_array[$row_liste_ugl["region"]])) echo $le_departement_array[$row_liste_ugl["region"]]; else echo $row_liste_ugl['region']; ?></td>
<td class=" "><?php echo $code; ?></td>

<!--<td class=" "><?php echo $row_liste_ugl['nom_pde']; ?></td>-->
<td class=" "><?php echo $row_liste_ugl['nom_pde']; ?></td>
<td class=" "><?php $al = explode(",",$row_liste_ugl['zone_pde']); if(count($al)>0){ $j=1; foreach($al as $bl){ echo isset($les_communes_array[$bl])?$les_communes_array[$bl].";&nbsp;":""; if($j%5==0) echo "<br />"; $j++; } }  ?></td>
<td class=" "><div class="progress-bar progress-bar-info" style="width: 100%;background-color: <?php echo $row_liste_ugl['couleur']; ?>;height: 20px;"><?php echo $row_liste_ugl['couleur']; ?></div></td>
<td class=" ">&nbsp;<?php if(file_exists("map/pde/".$row_liste_ugl['code_pde'].".shp")) echo '<span style="color:#339966;" ><b>Oui</b></span>'; else  echo '<span style="color:#CC0033;" ><b>Non</b></span>'; ?></td>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){
  //if(in_array($_SESSION["clp_structure"],explode("|",$row_liste_ugl['structure']))){ ?>
<td class=" " align="center">
<?php
echo do_link("","","Modifier le PDE ".$row_liste_ugl['nom_pde'],"","edit","./","","get_content('new_pde.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce PDE ".$row_liste_ugl['nom_pde']."');",0,"margin:0px 5px;",$nfile);
?></td>
<?php } ?>
</tr>
<?php }while($row_liste_ugl  = mysql_fetch_assoc($liste_ugl)); } ?>
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