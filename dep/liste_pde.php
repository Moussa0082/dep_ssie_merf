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

    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
	  $insertGoTo = $_SERVER['PHP_SELF'];
  if($Result1 && file_exists('./map/shapefiles/zone_collecte/zone_'.$id.'.shp'))
  unlink('./map/shapefiles/zone_collecte/zone_'.$id.'.shp');
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

      /*  mysql_select_db($database_pdar_connexion, $pdar_connexion);
        $query_sup_import_annee = "DELETE FROM ".$database_connect_prefix."pde ";// WHERE structure='".$_SESSION["clp_structure"]."'";
        $Result1 = mysql_query($query_sup_import_annee, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));*/

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

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $personnel=$_SESSION['clp_id'];
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."pde (couleur, zone_pde, filieres, code_pde, nom_pde, id_personnel) VALUES (%s, %s, %s, %s, %s, '$personnel')",
                        GetSQLValueString($_POST['couleur'], "text"),
                        GetSQLValueString(implode(',',$_POST['zone_pde']), "text"),
                        GetSQLValueString(implode(',',$_POST['filieres']), "text"),
  					    GetSQLValueString($_POST['code_pde'], "text"),
                        GetSQLValueString($_POST['nom_pde'], "text"));


    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
	
      if($Result1)
    {
     // $id = mysql_insert_id();
	  	$id = $db->lastInsertId();
      $couche_name = "";

      $dossier = './map/shapefiles/zone_collecte/'; $fichier = $_FILES['couche'];
      $fich='zone_'.$id.".shp";
      if(move_uploaded_file($fichier['tmp_name'],$dossier . $fich))
      {
        $couche_name = $fich;
      }
    }
    if($Result1) $insertGoTo = $page."?insert=ok";
    else $insertGoTo = $page."&insert=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
      $id = ($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."pde WHERE code_pde=%s",
                           GetSQLValueString($id, "text"));
	      try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
	
      $insertGoTo = $_SERVER['PHP_SELF'];
      if($Result1 && file_exists('./map/shapefiles/zone_collecte/zone_'.$id.'.shp'))
      unlink('./map/shapefiles/zone_collecte/zone_'.$id.'.shp');
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];  $id = ($_POST["MM_update"]);
   $couche_name = "";
      $dossier = './map/shapefiles/zone_collecte/'; $fichier = $_FILES['couche'];
    $fich='zone_'.$id.".shp";
    if(move_uploaded_file($fichier['tmp_name'],$dossier . $fich))
    {
      $couche_name = $fich;
    }
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."pde SET couleur=%s".(!empty($couche_name)?", shape=".GetSQLValueString($couche_name, "text"):"").", zone_pde=%s, code_pde=%s, nom_pde=%s, filieres=%s,  modifier_par='$personnel', modifier_le='$date' WHERE code_pde=%s",
                        GetSQLValueString($_POST['couleur'], "text"),
                        GetSQLValueString(implode(',',$_POST['zone_pde']), "text"),
  					  	GetSQLValueString($_POST['code_pde'], "text"),
						GetSQLValueString($_POST['nom_pde'], "text"),
                        GetSQLValueString(implode(',',$_POST['filieres']), "text"),
                        GetSQLValueString($id, "text"));

    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
	
    if($Result1) $insertGoTo = $page."?update=ok";
    else $insertGoTo = $page."&update=no";
  //$insertGoTo .= (isset($_POST['categorie']))?"&categorie=".$_POST['categorie']:"";
    header(sprintf("Location: %s", $insertGoTo));  exit();
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

$query_liste_pde = "SELECT * FROM ".$database_connect_prefix."pde order by code_pde";
try{
    $liste_pde = $pdar_connexion->prepare($query_liste_pde);
    $liste_pde->execute();
    $row_liste_pde = $liste_pde ->fetchAll();
    $totalRows_liste_pde = $liste_pde->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//les regions
$query_liste_region = "SELECT * FROM ".$database_connect_prefix."departement ";
try{
    $liste_region = $pdar_connexion->prepare($query_liste_region);
    $liste_region->execute();
    $row_liste_region = $liste_region ->fetchAll();
    $totalRows_liste_region = $liste_region->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_liste_region_arrayV =$liste_liste_region_array= array();
if($totalRows_liste_region>0){ foreach($row_liste_region as $row_liste_region){
$liste_liste_region_arrayV[$row_liste_region["code_departement"]]=$row_liste_region["nom_departement"];
$liste_liste_region_array[$row_liste_region["code_departement"]]=$row_liste_region["code_departement"];
   }
}



//Chef lieu departement
$query_le_departement = "SELECT * FROM ".$database_connect_prefix."ugl";
try{
    $le_departement = $pdar_connexion->prepare($query_le_departement);
    $le_departement->execute();
    $row_le_departement = $le_departement ->fetchAll();
    $totalRows_le_departement = $le_departement->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$le_departement_array = array();
if($totalRows_le_departement>0){ foreach($row_le_departement as $row_le_departement){
  $le_departement_array[$row_le_departement["code_ugl"]]=$row_le_departement["nom_ugl"];
   }
}


//communes polaris褳
$query_les_communes = "SELECT * FROM ".$database_connect_prefix."departement";
try{
    $les_communes = $pdar_connexion->prepare($query_les_communes);
    $les_communes->execute();
    $row_les_communes = $les_communes ->fetchAll();
    $totalRows_les_communes = $les_communes->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$les_communes_array = array();
if($totalRows_les_communes>0){ foreach($row_les_communes as $row_les_communes){
  $les_communes_array[$row_les_communes["code_departement"]]=$row_les_communes["nom_departement"];
   }
}


//communes polaris褳
$query_les_filieres = "SELECT * FROM ".$database_connect_prefix."filiere_agricole";
try{
    $les_filieres = $pdar_connexion->prepare($query_les_filieres);
    $les_filieres->execute();
    $row_les_filieres = $les_filieres ->fetchAll();
    $totalRows_les_filieres = $les_filieres->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$les_filieres_array = array();
if($totalRows_les_filieres>0){ foreach($row_les_filieres as $row_les_filieres){
  $les_filieres_array[$row_les_filieres["code_filiere"]]=$row_les_filieres["nom_filiere"];
   }
}

//exit;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print $config->sitename; ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />

  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

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
<div class="widget-header"> <h4><i class="icon-reorder"></i> Liste des PDA </h4>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
//echo do_link("","","Importation d&eacute;puis un format excel","<i class=\"icon-plus\"> Importer </i>","","./","pull-right p11","get_content('import.php','id=ugl','modal-body_add',this.title);",1,"",$nfile);

echo do_link("","","Ajout de PDA","<i class=\"icon-plus\"> Nouveau PDA </i>","","./","pull-right p11","get_content('new_pde.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div>

<div class="widget-content">
<table class="table table-striped table-bordered table-hover table-responsive table-tabletools table-colvis datatable dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
  <!-- <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Unit&eacute; de gestion </th>-->
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Code</th>

<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Abr&eacute;viation</th>-->
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Nom du PDA</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Fili&egrave;res concern&eacute;es </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Communes polaris&eacute;es  </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Couleur </th>
<th  role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Shapes files </th>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="80">Actions</th>
<?php } ?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php if($totalRows_liste_pde>0) { $i=0;   foreach($row_liste_pde as $row_liste_pde){ $c = array(); $id = $row_liste_pde['code_pde']; $code = $row_liste_pde['code_pde']; $sigleu = $row_liste_pde['nom_pde']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
 <!-- <td class=" "><?php if(isset($le_departement_array[$row_liste_pde["region"]])) echo $le_departement_array[$row_liste_pde["region"]]; else echo $row_liste_pde['region']; ?></td>-->
<td class=" "><?php echo $code; ?></td>

<!--<td class=" "><?php echo $row_liste_pde['nom_pde']; ?></td>-->
<td class=" "><?php echo $row_liste_pde['nom_pde']; ?></td>
<td class=" "><?php $al = explode(",",$row_liste_pde['filieres']); if(count($al)>0){ $j=1; foreach($al as $bl){ echo isset($les_filieres_array[$bl])?$les_filieres_array[$bl].";&nbsp;":""; if($j%5==0) echo "<br />"; $j++; } }  ?></td>
<td class=" "><?php $al = explode(",",$row_liste_pde['zone_pde']); if(count($al)>0){ $j=1; foreach($al as $bl){ echo isset($les_communes_array[$bl])?$les_communes_array[$bl].";&nbsp;":""; if($j%5==0) echo "<br />"; $j++; } }  ?></td>
<td class=" "><div class="progress-bar progress-bar-info" style="width: 100%;background-color: <?php echo $row_liste_pde['couleur']; ?>;height: 20px;"><?php echo $row_liste_pde['couleur']; ?></div></td>
<!--<td class=" ">&nbsp;<?php if(file_exists("map/pde/".$row_liste_pde['code_pde'].".shp")) echo '<span style="color:#339966;" ><b>Oui</b></span>'; else  echo '<span style="color:#CC0033;" ><b>Non</b></span>'; ?></td>-->
<td class=" "><center>
&nbsp;<?php echo (!empty($row_liste_pde['shape']) && file_exists('./map/shapefiles/zone_collecte/'.$row_liste_pde['shape']))?"<a title='T&eacute;l&eacute;charger le fichier SHP' href='./map/shapefiles/zone_collecte/".$row_liste_pde['shape']."'>".$row_liste_pde['code_pde']."</a>":" - "; ?>
</center></td>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){
  //if(in_array($_SESSION["clp_structure"],explode("|",$row_liste_pde['structure']))){ ?>
<td class=" " align="center">
<?php
echo do_link("","","Modifier le PDA ".$row_liste_pde['nom_pde'],"","edit","./","","get_content('new_pde.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce PDA ".$row_liste_pde['nom_pde']."');",0,"margin:0px 5px;",$nfile);
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