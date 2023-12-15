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

if ((isset($_GET["id_sup"]) && !empty($_GET["id_sup"]))) {
  $id = ($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."convention WHERE code_convention=%s",
                       GetSQLValueString($id, "text"));

 try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $personnel=$_SESSION['clp_id']; $date=date("Y-m-d");
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."convention (code_convention, partenaire, intitule, reference, montant, champs_app, date_signature, debut, fin, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s,%s, %s, %s, %s,'$personnel', '$date')",
	                   GetSQLValueString($_POST['code_convention'], "text"),
                       GetSQLValueString($_POST['partenaire'], "int"),
					   GetSQLValueString($_POST['intitule'], "text"),
					   GetSQLValueString($_POST['reference'], "text"),
                       GetSQLValueString($_POST['montant'], "double"),
					   GetSQLValueString($_POST['champs_app'], "text"),
					   GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_signature']))), "date"),
					   GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['debut']))), "date"),
					   GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['fin']))), "date"));

 try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
  if($Result1) $insertGoTo = $page."?insert=ok";
  else $insertGoTo = $page."&insert=no";
 // $insertGoTo .= (isset($_POST['categorie']))?"&categorie=".$_POST['categorie']:"";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
      $id = intval($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."convention WHERE code_convention=%s",
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
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."convention SET code_convention=%s, partenaire=%s, intitule=%s, reference=%s, montant=%s,  champs_app=%s, date_signature=%s, debut=%s, fin=%s,  etat='ModifiÃ©', modifier_par='$personnel', modifier_le='$date' WHERE code_convention=%s", 
                       GetSQLValueString($_POST['code_convention'], "text"),
					   GetSQLValueString($_POST['partenaire'], "int"),
					   GetSQLValueString($_POST['intitule'], "text"),
					   GetSQLValueString($_POST['reference'], "text"),
                       GetSQLValueString($_POST['montant'], "double"),
					   GetSQLValueString($_POST['champs_app'], "text"),
					   GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_signature']))), "date"),
					   GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['debut']))), "date"),
					   GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['fin']))), "date"),
                       GetSQLValueString($id, "text"));

 try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
  if($Result1) $insertGoTo = $page."?update=ok";
  else $insertGoTo = $page."&update=no";
 // $insertGoTo .= (isset($_POST['categorie']))?"&categorie=".$_POST['categorie']:"";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['MM_update'];
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."convention SET region_concerne=%s, modifier_le='$date', modifier_par='$personnel' WHERE  code_convention=%s",
                      GetSQLValueString(implode('|',$_POST['region'])."|", "text"),                     
                      GetSQLValueString($_POST['convention'], "text"));

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

$query_liste_conv = "SELECT id_acteur, ".$database_connect_prefix."convention.*, ".$database_connect_prefix."acteur.nom_acteur, ".$database_connect_prefix."acteur.nom_acteur as sigle FROM ".$database_connect_prefix."convention, ".$database_connect_prefix."acteur where id_acteur=partenaire ORDER BY nom_acteur asc, ".$database_connect_prefix."convention.intitule asc";
        try{
    $liste_conv = $pdar_connexion->prepare($query_liste_conv);
    $liste_conv->execute();
    $row_liste_conv = $liste_conv ->fetchAll();
    $totalRows_liste_conv = $liste_conv->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


//les regions
$query_liste_region = "SELECT * FROM ".$database_connect_prefix."region ";
        try{
    $liste_region = $pdar_connexion->prepare($query_liste_region);
    $liste_region->execute();
    $row_liste_region = $liste_region ->fetchAll();
    $totalRows_liste_region = $liste_region->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_liste_region_array = array();  $liste_liste_region_arrayV = array();
if($totalRows_liste_region>0){  foreach($row_liste_region as $row_liste_region){
$liste_liste_region_arrayV[$row_liste_region["code_region"]]=$row_liste_region["nom_region"];
$liste_liste_region_array[$row_liste_region["code_region"]]=$row_liste_region["abrege_region"];
}}

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
<div class="widget-header"> <h4><i class="icon-reorder"></i> Conventions avec les partenaires</h4>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==0){ ?>
<?php
echo do_link("","","Ajout de convention","<i class=\"icon-plus\"> Nouvelle convention </i>","","./","pull-right p11","get_content('modal_content/edit_convention.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div>

<div class="widget-content">
<table class="table table-striped table-bordered table-hover table-responsive datatable  dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
  <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Code</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">N&deg;R&eacute;f.</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Convention</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Partenaire</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Montant (FCFA) </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Date de Signature</th>
<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">UGL</th>-->
<th colspan="3" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><div align="center">R&eacute;sultats attendus</div></th>
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==0)) { ?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="90">Actions</th>
<?php }?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php if($totalRows_liste_conv>0) { $i=0;  foreach($row_liste_conv as $row_liste_conv){ $c = array();  $code = $row_liste_conv['code_convention']; $sigleu=$row_liste_conv['reference']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
  <td class=" "><?php echo $row_liste_conv['code_convention']; ?></td>
<td class=" "><?php echo $row_liste_conv['reference']; ?></td>
<td class=" "><?php echo $row_liste_conv['intitule']; ?> </br>(<?php echo date_reg($row_liste_conv['debut'],'/')." au ".date_reg($row_liste_conv['fin'],'/');?>)</td>
<td class=" "><?php echo $row_liste_conv['nom_acteur']; ?></td>
<td class=" "><?php echo number_format($row_liste_conv['montant'], 0, ',', ' '); ?></td>
<td class=" "><?php echo date_reg($row_liste_conv['date_signature'],'/'); ?></td>
<!--<td class=" "> <?php //if(isset($row_liste_conv["region_concerne"]) && $row_liste_conv["region_concerne"]!="|") {$a = explode("|",$row_liste_conv["region_concerne"]); if(isset($a) && count($a)>0){  foreach($a as $b) if(isset($liste_liste_region_array[$b])) $c[]=do_link("","","Edition de la zone d'intervention ","<span title=\"".$liste_liste_region_arrayV[$b]."\">".$liste_liste_region_array[$b]."</span>","","./","","get_content('new_region_convention.php','id=$code&sigleu=$sigleu','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile); echo implode('; &nbsp;',$c); }} else { $c[]=do_link("","","Edition de la zone d'intervention ","<span title=\"Edition\">Editer</span>","","./","","get_content('new_region_convention.php','id=$code&sigleu=$sigleu','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile); echo implode('; &nbsp;',$c); } ?></td>-->
<td class=" "><a onclick="get_content('liste_resultat_convention.php','<?php echo "code_cv=".$row_liste_conv['code_convention']; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add"  title="Les r&eacute;sultats attendus de la convention '<?php echo "<u>".str_replace("'","\'",$row_liste_conv['intitule']." (".$row_liste_conv['reference'].")")."</u>";?>'" class="thickbox" dir="">R&eacute;sultats</a></td>
<td class=" "><a onclick="get_content('liste_activite_convention.php','<?php echo "code_cv=".$row_liste_conv['code_convention']; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add"  title="Les activit&eacute;s de la convention '<?php echo "<u>".str_replace("'","\'",$row_liste_conv['intitule']." (".$row_liste_conv['reference'].")")."</u>";?>'" class="thickbox" dir="">Activit&eacute;s</a></td>
<td class=" "><a onclick="get_content('liste_indicateur_convention.php','<?php echo "code_cv=".$row_liste_conv['code_convention']; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add"  title="Les indicateurs de la convention '<?php echo "<u>".str_replace("'","\'",$row_liste_conv['intitule']." (".$row_liste_conv['reference'].")")."</u>";?>'" class="thickbox" dir="">Indicateurs</a></td>
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==0)) { ?>
<td class=" " align="center">
<?php
echo do_link("","","Modifier Convention ".$row_liste_conv['intitule'],"","edit","./","","get_content('modal_content/edit_convention.php','id=".$code."','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$code,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette Convention ?');",0,"margin:0px 5px;",$nfile);
?>
</td>
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