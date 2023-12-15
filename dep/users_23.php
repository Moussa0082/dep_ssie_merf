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

if ((isset($_GET["id_sup"]) && intval($_GET["id_sup"])>0)) {
  $id = intval($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."personnel WHERE N=%s",
                       GetSQLValueString($id, "int"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
	  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{ //personnel
    $date=date("Y-m-d");
	/*$a=implode(",",$_POST['projet']);
	echo $a;
	echo "==";*/
    $query_liste_projet_ugl = "SELECT code_projet FROM ".$database_connect_prefix."projet ";
		try{
    $liste_projet_ugl = $pdar_connexion->prepare($query_liste_projet_ugl);
    $liste_projet_ugl->execute();
    $row_liste_projet_ugl = $liste_projet_ugl ->fetchAll();
    $totalRows_liste_projet_ugl = $liste_projet_ugl->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

	$liste_projet_array = array();
	if($totalRows_liste_projet_ugl>0){  foreach($row_liste_projet_ugl as $row_liste_projet_ugl){
 if(in_array($row_liste_projet_ugl["code_projet"],$_POST['projet'])) $liste_projet_array[]=$row_liste_projet_ugl["code_projet"];
} }
//exit;

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {

   // mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $query_liste_personnel = sprintf("SELECT * FROM ".$database_connect_prefix."personnel WHERE id_personnel=%s",
                           GetSQLValueString($_POST['id_personnel'], "text"));
try{
    $liste_personnel = $pdar_connexion->prepare($query_liste_personnel);
    $liste_personnel->execute();
    $row_liste_personnel = $liste_personnel ->fetch();
    $totalRows_liste_personnel = $liste_personnel->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

    if($totalRows_liste_personnel==0)
    {
      $password=md5($_POST['password']); $pactive = (isset($liste_projet_array[0]))?$liste_projet_array[0]:"000";
      $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."personnel (titre, nom, prenom, id_personnel, pass, contact, email, fonction, niveau, structure, projet, projet_active, description_fonction, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, '$pactive', %s, '$date')",
                           GetSQLValueString($_POST['titre'], "text"),
						   GetSQLValueString($_POST['nom'], "text"),
                           GetSQLValueString($_POST['prenom'], "text"),
                           GetSQLValueString($_POST['id_personnel'], "text"),
                           GetSQLValueString($password, "text"),
                           GetSQLValueString($_POST['contact'], "int"),
                           GetSQLValueString($_POST['email'], "text"),
                           GetSQLValueString($_POST['fonction'], "text"),
    					   GetSQLValueString($_POST['niveau'], "int"),
                           GetSQLValueString($_POST["structure"], "text"),
                           GetSQLValueString(implode('|',$liste_projet_array)."|", "text"),
                           GetSQLValueString($_POST['description_fonction'], "text"));

      try{
            $Result1 = $pdar_connexion->prepare($insertSQL);
            $Result1->execute();
      }catch(Exception $e){ die(mysql_error_show_message($e)); }

      $id = $pdar_connexion->lastInsertId();
	  
      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
      $insertGoTo .= "&ida=$id";
      header(sprintf("Location: %s", $insertGoTo));  exit();
    }
    else
    {
      $insertGoTo = $_SERVER['PHP_SELF'];
      $insertGoTo .= "?doublon=Cet Identifiant";
      header(sprintf("Location: %s", $insertGoTo));  exit();
    }
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."personnel WHERE N=%s",
                         GetSQLValueString($id, "int"));

     try{
            $Result1 = $pdar_connexion->prepare($insertSQL);
            $Result1->execute();
      }catch(Exception $e){ die(mysql_error_show_message($e)); }

     // $id = $pdar_connexion->lastInsertId();
	      $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]); $ph=md5($_POST['password']);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."personnel SET titre=%s, nom=%s, prenom=%s, contact=%s, email=%s, fonction=%s, niveau=%s, structure=%s, projet=%s, description_fonction=%s %s WHERE N=%s",
                         GetSQLValueString($_POST['titre'], "text"),
						 GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['prenom'], "text"),
                         GetSQLValueString($_POST['contact'], "int"),
                         GetSQLValueString($_POST['email'], "text"),
                         GetSQLValueString($_POST['fonction'], "text"),
  					     GetSQLValueString($_POST['niveau'], "int"),
                         GetSQLValueString($_POST['structure'], "text"),
                         GetSQLValueString(implode('|',$liste_projet_array)."|", "text"),
                         GetSQLValueString($_POST['description_fonction'], "text"),
                         ((!empty($_POST['password']))?", pass='".$ph."'":''),
                         GetSQLValueString($id, "int"));

     try{
            $Result1 = $pdar_connexion->prepare($insertSQL);
            $Result1->execute();
      }catch(Exception $e){ die(mysql_error_show_message($e)); }

      //$id = $pdar_connexion->lastInsertId();
	  
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    $insertGoTo .= "&ida=$id";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{ //personnel Authorisation
    $date=date("Y-m-d");

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {

      $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."user_access (id_personnel, page_interd, page_edit, page_verif, page_valid, personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, '$date')",
                           GetSQLValueString($_POST['id_personnel'], "text"),
                           GetSQLValueString(implode('|',$_POST['auth'])."|", "text"),
                           GetSQLValueString(implode('|',$_POST['page_edit'])."|", "text"),
                           GetSQLValueString(implode('|',$_POST['page_verif'])."|", "text"),
                           GetSQLValueString(implode('|',$_POST['page_valid'])."|", "text"),
                           GetSQLValueString($_SESSION["clp_n"], "int"));

     try{
            $Result1 = $pdar_connexion->prepare($insertSQL);
            $Result1->execute();
      }catch(Exception $e){ die(mysql_error_show_message($e)); }

     // $id = $pdar_connexion->lastInsertId();
	        $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
      header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."user_access WHERE id=%s",
                         GetSQLValueString($id, "int"));

     try{
            $Result1 = $pdar_connexion->prepare($insertSQL);
            $Result1->execute();
      }catch(Exception $e){ die(mysql_error_show_message($e)); }

     // $id = $pdar_connexion->lastInsertId();
	      $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."user_access SET id_personnel=%s, page_interd=%s, page_edit=%s, page_verif=%s, page_valid=%s, personnel=%s, date_modification='$date' WHERE id=%s",
                         GetSQLValueString($_POST['id_personnel'], "text"),
                         GetSQLValueString(implode('|',$_POST['auth'])."|", "text"),
                         GetSQLValueString(implode('|',$_POST['page_edit'])."|", "text"),
                         GetSQLValueString(implode('|',$_POST['page_verif'])."|", "text"),
                         GetSQLValueString(implode('|',$_POST['page_valid'])."|", "text"),
                         GetSQLValueString($_SESSION["clp_n"], "int"),
                         GetSQLValueString($id, "int"));

     try{
            $Result1 = $pdar_connexion->prepare($insertSQL);
            $Result1->execute();
      }catch(Exception $e){ die(mysql_error_show_message($e)); }

    //  $id = $pdar_connexion->lastInsertId();
	  
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no&ida=$id";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

//personnel
$query_personnel = "SELECT * FROM ".$database_connect_prefix."personnel ORDER BY date_enregistrement desc ";
try{
    $personnel = $pdar_connexion->prepare($query_personnel);
    $personnel->execute();
    $row_personnel = $personnel ->fetchAll();
    $totalRows_personnel = $personnel->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//Structure
$query_structure = "SELECT * FROM ".$database_connect_prefix."ugl ";
try{
    $fonction = $pdar_connexion->prepare($query_structure);
    $fonction->execute();
    $row_structure = $fonction ->fetchAll();
    $totalRows_structure = $fonction->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$liste_structure_array = $liste_structure_arrayV = array();
if($totalRows_structure>0){ foreach($row_structure as $row_structure){
$liste_structure_array[$row_structure["code_ugl"]]=$row_structure["nom_ugl"];
$liste_structure_arrayV[$row_structure["code_ugl"]]=$row_structure["nom_ugl"];
} }

//Projet
$query_projet = "SELECT * FROM ".$database_connect_prefix."projet";
try{
    $projet = $pdar_connexion->prepare($query_projet);
    $projet->execute();
    $row_projet = $projet ->fetchAll();
    $totalRows_projet = $projet->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_projet_array = $liste_projet_arrayV = array();
if($totalRows_projet>0){ foreach($row_projet as $row_projet){
$liste_projet_arrayV[$row_projet["code_projet"]]=$row_projet["intitule_projet"];
$liste_projet_array[$row_projet["code_projet"]]=$row_projet["sigle_projet"];
} }

$query_fonction = "SELECT * FROM ".$database_connect_prefix."fonction ";
try{
    $fonction = $pdar_connexion->prepare($query_fonction);
    $fonction->execute();
    $row_fonction = $fonction ->fetchAll();
    $totalRows_fonction = $fonction->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$fonction_array = array();
if($totalRows_fonction>0){ foreach($row_fonction as $row_fonction){
  $fonction_array[$row_fonction["fonction"]]=$row_fonction["description"];
   }
}

$liste_niveau_array = array('Aucun','Edition','Visiteur');

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
<?php include_once 'modal_add.php'; ?>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
</style>
<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Utilisateurs</h4>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
echo do_link("","","Ajout d'utilisateur","<i class=\"icon-plus\"> Nouvel Utilisateur </i>","","./","pull-right p11","get_content('new_user.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
<?php
echo do_link("","./fonctions.php","Fonctions","<i class=\"icon-plus\"> Fonctions Utilisateur </i>","","./","pull-right p11","",0,"",$nfile);
?>
</div>
<div class="widget-content">

<table class="table table-striped table-bordered table-hover table-responsive table-checkable table-tabletools table-colvis datatable dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Pr&eacute;nom & Nom</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Login</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Unit&eacute; de gestion  </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Guichets </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Fonction </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Niveau d'acc&egrave;s</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Contact</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">E-mail</th>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
<th class="sorting" role="" tabindex="0" aria-controls="" aria-label="" width="120">Actions</th>
<?php } ?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php if($totalRows_personnel>0) { $i=0; foreach($row_personnel as $row_personnel){ $id = $row_personnel['N']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo $row_personnel['prenom']." ".$row_personnel['nom']; ?></td>
<td class=" "><?php echo $row_personnel['id_personnel']; ?></td>
<td class=" "><?php if(isset($liste_structure_arrayV[$row_personnel['structure']])) echo "<span title='".$liste_structure_arrayV[$row_personnel['structure']]."'>".$liste_structure_array[$row_personnel['structure']]."</span>"; else echo "ND"; ?></td>
<td class=" "><?php $a = explode("|",$row_personnel['projet']); if(count($a)>0){ $c = array(); foreach($a as $b) if(isset($liste_projet_array[$b])) $c[]="<span title=\"".$liste_projet_array[$b]."\">".$liste_projet_array[$b]."</span>"; echo implode('; ',$c); } else echo "Aucun"; ?></td>
<td class=" " title="<?php echo (isset($fonction_array[$row_personnel["fonction"]]))?$fonction_array[$row_personnel["fonction"]]:$row_personnel["fonction"]; ?>"><?php echo $row_personnel["fonction"]; ?></td>
<td class=" "><?php echo $liste_niveau_array[$row_personnel['niveau']]; ?></td>
<td class=" "><?php echo $row_personnel['contact']; ?></td>
<td class=" "><?php echo $row_personnel['email']; ?></td>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
<td class=" " align="center">
<?php
echo do_link("new_user_auth_$id","","Droits d'utilisateur ".$row_personnel['nom'],"","access","./","","get_content('new_user_auth.php','id=$id','modal-body_add',this.title);",1,"margin:0px 10px 0 0;",$nfile);
//if($row_personnel['id_personnel']!="admin")
echo do_link("","","Modifier utilisateur ".$row_personnel['id_personnel'],"","edit","./","","get_content('new_user.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);
if($row_personnel['id_personnel']!="admin")
echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cet utilisateur ".$row_personnel['id_personnel']."');",0,"margin:0px 5px;",$nfile);
?>
</td>
<?php } ?>
</tr>
<?php } } ?>
</tbody></table>

</div> </div>
<script type="text/javascript" >
<?php if(isset($_GET['ida']) && intval($_GET['ida'])>0) { ?>
$("#new_user_auth_<?php echo intval($_GET['ida']); ?>").click();
<?php } ?>
</script>

<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>