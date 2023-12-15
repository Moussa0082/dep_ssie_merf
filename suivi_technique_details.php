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
?>

<?php
$plog=$_SESSION["clp_id"];
$date=date("Y-m-d");
/*if(isset($_SESSION['annee']) && !isset($_GET['annee'])) {$annee=$_SESSION['annee'];}
elseif(isset($_GET['annee'])) {$annee=$_GET['annee']; $_SESSION['annee']=$annee;}
elseif(!isset($_GET['annee']) && isset($_SESSION['annee'])) $annee=$_SESSION['annee'];
else $annee=date("Y"); */

if(isset($_GET['annee'])) $annee=$_GET['annee']; else $annee=date("Y");

//if(isset($_SESSION["cp"]) && !isset($_GET['cp'])){$cp=$_SESSION["cp"];}
if(isset($_GET['cp'])){$cp=$_GET['cp'];  }
//elseif(!isset($_GET['cp']) && isset($_SESSION['cp'])) $_GET['cp']=$cp;
if(isset($_GET["id_fiche"])) $id_fiche=$_GET["id_fiche"]; else $id_fiche=0;


if(isset($_GET["id_sup"]))
{
  $id=$_GET["id_sup"]; $id_fiche=$_GET["id_fiche"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_sup_act = "DELETE FROM $cp WHERE LKEY='$id'";
  $Result1 = mysql_query($query_sup_act, $pdar_connexion) or die(mysql_error());
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok&id_fiche=$id_fiche&cp=$cp";
  else $insertGoTo .= "?del=no&id_fiche=$id_fiche&cp=$cp";
  mysql_free_result($Result1);
  header(sprintf("Location: %s", $insertGoTo));
}


if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{  $cp = $_POST["id_cp"];  $annee=$_POST["annee"];
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $sql='' ; $titre="";

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "DESCRIBE $cp";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);
$type_field = array();
if($totalRows_entete>0){ do{ $type_field[$row_entete["Field"]]=$row_entete["Type"]; }while($row_entete  = mysql_fetch_assoc($entete)); }

  if(isset($_POST["field_name"])){ $i=0; foreach($_POST["field_name"] as $name){ $titre.="`$name`,";if(isset($type_field[$name]) && strchr($type_field[$name],"date")!="") $sql.='"'.implode('-',array_reverse(explode('-',$_POST[$name]))).'",'; else $sql.='"'.$_POST[$name].'",'; $i++; }  }   $id_fiche=$_POST["fiche"];
  $sql=substr($sql,0,strlen($sql)-1);
    $insertSQL = 'INSERT INTO '.$cp.' ('.substr($titre,0,strlen($titre)-1).') VALUES ('.$sql.')';

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok&cp=$cp&id_fiche=$id_fiche"; else $insertGoTo .= "?insert=no&cp=$cp&id_fiche=$id_fiche";
    header(sprintf("Location: %s", $insertGoTo));
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=intval($_POST["MM_update"]); $sql='' ; $id_fiche=$_POST["fiche"];

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "DESCRIBE $cp";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);
$type_field = array();
if($totalRows_entete>0){ do{ $type_field[$row_entete["Field"]]=$row_entete["Type"]; }while($row_entete  = mysql_fetch_assoc($entete)); }


  if(isset($_POST["field_name"])){ foreach($_POST["field_name"] as $name){ if(isset($type_field[$name]) && strchr($type_field[$name],"date")!="") $sql.=$name.'='.GetSQLValueString(implode('-',array_reverse(explode('-',$_POST[$name]))), "date").','; else $sql.=$name.'='.GetSQLValueString($_POST[$name], "text").',';   }   }
  $sql=substr($sql,0,strlen($sql)-1);
  	$insertSQL = "UPDATE $cp SET $sql WHERE LKEY=$c";

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok&cp=$cp&id_fiche=$id_fiche"; else $insertGoTo .= "?update=no&cp=$cp&id_fiche=$id_fiche";
    header(sprintf("Location: %s", $insertGoTo));
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
      $id = intval($_POST["MM_delete"]);  $id_fiche=$_POST["fiche"];
      $insertSQL = sprintf("DELETE from $cp WHERE LKEY=%s",
                           GetSQLValueString($id, "int"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok&cp=$cp&id_fiche=$id_fiche"; else $insertGoTo .= "?del=no&cp=$cp&id_fiche=$id_fiche";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

}




// query fonction

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_mois= "SELECT * FROM mois order by num_mois";
$liste_mois = mysql_query($query_liste_mois, $pdar_connexion) or die(mysql_error());
	$tableauMois=array();
	while($ligne=mysql_fetch_assoc($liste_mois)){$tableauMois[]=$ligne['num_mois']."<>".$ligne['abrege'];}
	mysql_free_result($liste_mois);


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SHOW tables";
$liste_cp = mysql_query($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);

$cp_array=array();
if($totalRows_liste_cp>0) {
do {  if(strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="fiche_config"){  $cp_array[]=$row_liste_cp["Tables_in_$database_pdar_connexion"];
}
} while ($row_liste_cp = mysql_fetch_assoc($liste_cp));
$rows = mysql_num_rows($liste_cp);
if($rows > 0) {
mysql_data_seek($liste_cp, 0);
$row_liste_cp = mysql_fetch_assoc($liste_cp);
}}

if(!in_array($cp,$cp_array)) unset($cp);

$entete_array = array(); $libelle = array();

if(isset($cp) && !empty($cp))
{
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = "SELECT * FROM $cp WHERE fiche=$id_fiche";
$act  = mysql_query($query_act , $pdar_connexion) or die(mysql_error());
$row_act  = mysql_fetch_assoc($act);
$totalRows_act  = mysql_num_rows($act);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT * FROM fiche_config WHERE `table`='$cp'";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);

if($totalRows_entete>0){ $entete_array=explode(",",$row_entete["show"]); $libelle=explode(",",$row_entete["libelle"]); }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "DESCRIBE $cp";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);
$num=0;
if($totalRows_entete>0){ do{ if(in_array($row_entete["Field"],$entete_array)) $num++; }while($row_entete  = mysql_fetch_assoc($entete));  }

$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}

}



//}
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
  <!--<script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>
  <script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>-->
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
 <style>
.firstcapitalize:first-letter{
  text-transform: capitalize;
}
</style>
</head>
<body >

<div id="container">

    <div id="content">
        <div class="container">

        <div class="page-header">
            <div class="p_top_5">
<!-- Site contenu ici -->
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
</style>
<script type="text/javascript">
$(".modal-dialog", window.parent.document).width(800);
</script>

				   <?php
				 if(isset($cp)) {//requete groupe d'activite
                                ?>

<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box">
<div class="widget-header"> <h4 style="width: 49%"><i class="icon-reorder"></i><strong><?php ?><!--<a onclick="get_content('new_fiche.php','<?php echo "id=".$cp."&annee=".$annee; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add"  title="Modification <?php echo substr($cp,6);?>" class="thickbox" dir=""></a>--></strong></h4><h4 align="right" style="width: 49%"><?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<4 && isset($cp) && !empty($cp)) {?><a onclick="get_content('modal_content/new_fiche_data.php','<?php echo "id_fiche=".$id_fiche."&id_cp=".$cp."&annee=".$annee."#os"; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Ajout" dir=""><i class="icon-plus"> Ajouter <?php //echo substr($cp,6); ?> </i></a><?php }?></h4>

<?php include_once 'modal_add.php'; ?>

</div>
<div class="widget-content">
<?php if($num>0 && $totalRows_act>0){ ?>
<table class="table table-striped table-bordered table-hover table-responsive datatable dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<?php if($totalRows_entete>0){ $i=0; do{ if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array)){
if(isset($libelle[$i])){
$lib=explode("=",$libelle[$i]);
$libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";   }
  ?>
<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize"><?php echo (isset($libelle_array[$row_entete["Field"]]))?$libelle_array[$row_entete["Field"]]:str_replace("_"," ",$row_entete["Field"]); ?></div></th>
<?php $i++; }  }while($row_entete  = mysql_fetch_assoc($entete)); }
$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
} ?>

<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="60">Edit</th>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="60">Suppr.</th>
</tr>
</thead>

<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php $i=0; if($totalRows_act>0) { do { $id = $row_act['LKEY']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<?php if($totalRows_entete>0){ do{ if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array)){
if(strtolower($row_entete["Field"])=="village" && intval($row_act[$row_entete["Field"]])>0){ $village=$row_act[$row_entete["Field"]];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_region = "SELECT nom_village,nom_commune FROM commune,village WHERE commune=code_commune and code_village='$village'";
$region = mysql_query($query_region, $pdar_connexion) or die(mysql_error());
$row_region = mysql_fetch_assoc($region);
$totalRows_region = mysql_num_rows($region);
$lib_vill = $row_region["nom_commune"]." / ".$row_region["nom_village"];
mysql_free_result($region);
}
 ?>
<td <?php if((strchr($row_entete["Field"],"date")!="")) echo 'nowrap="nowrap"'; ?> class=" "><?php if(strtolower($row_entete["Type"])=="date") echo implode('-',array_reverse(explode('-',$row_act[$row_entete["Field"]]))); else echo (strtolower($row_entete["Field"])=="village" && isset($row_region["nom_village"]) && isset($lib_vill))?$lib_vill:$row_act[$row_entete["Field"]]; unset($lib_vill); ?></td>
<?php } }while($row_entete  = mysql_fetch_assoc($entete));
$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}
} ?>

<td class=" " align="center"><?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<4) {?><a onclick="get_content('modal_content/new_fiche_data.php','<?php echo "id_fiche=".$id_fiche."&id=".$row_act['LKEY']."&id_cp=".$cp."#os"; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="<?php echo substr($cp,6); ?>" class="thickbox Add"  dir=""><img src="images/edit.png" width='20' height='20' alt='Modifier' /></a><?php } ?></td>
<td class=" " align="center"><?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<4) {?><a href="<?php echo $_SERVER['PHP_SELF']."?&id_sup=".$row_act['LKEY']."&ad_act=".$cp."&id_fiche=".$id_fiche."&cp=".$cp; ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?');" /><img src="images/delete.png" width="15" border="0"/></a><?php } ?></td>
</tr>
<?php $i++; } while ($row_act = mysql_fetch_assoc($act)); } ?>
</tbody></table><?php }elseif($num==0) echo "<h1 align='center'>Aucune colonne &agrave; afficher dans la fiche ".substr(str_replace("_"," ",$cp),6)."!</h1>"; else echo "<h1 align='center'>Aucune donn&eacute;e!</h1>"; ?>

</div> </div>

				   <?php } ?>


<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
</div>

</body>
</html>