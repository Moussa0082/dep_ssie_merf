<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;
$page = $_SERVER['PHP_SELF'];
if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";

?>

<?php
$plog=$_SESSION["clp_id"];
$date=date("Y-m-d");
$query_liste_version = "SELECT * FROM ".$database_connect_prefix."version_ptba  ORDER BY date_validation asc";
						try{
    $liste_version = $pdar_connexion->prepare($query_liste_version);
    $liste_version->execute();
    $row_liste_version = $liste_version ->fetchAll();
    $totalRows_liste_version = $liste_version->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$TableauVersionP = array(); $version_array = array();
 if($totalRows_liste_version>0) { foreach($row_liste_version as $row_liste_version){  
 if($row_liste_version["version_ptba"]==1) $row_liste_version["version_ptba"]="Initiale"; elseif($row_liste_version["version_ptba"]==2) $row_liste_version["version_ptba"]="R&eacute;vis&eacute;e";
$max_version=$row_liste_version["id_version_ptba"];
$TableauVersionP[]=$row_liste_version["id_version_ptba"]."<>".$row_liste_version["version_ptba"]."<>".$row_liste_version["annee_ptba"];
$version_array[$row_liste_version["version_ptba"]] = $row_liste_version["id_version_ptba"];
 } }

if(isset($_GET['annee'])) {$version=$_GET['annee'];} elseif($totalRows_liste_version>0) $version=$max_version; else  $version=1;
if(isset($_GET['actc'])) {$actc=$_GET['actc'];} else $actc=0;
//if(isset($_GET['cp'])) {$cp=$_GET['cp'];} else $cp=0;
//if(isset($_GET['scp'])) {$scp=$_GET['scp'];} else $scp=0;
$cmp = 0;
if(isset($_GET['cmp']) && intval($_GET['cmp'])>0) $cmp = intval($_GET['cmp']);

if(isset($_GET["id_sup_act"]))
{
  $id=$_GET["id_sup_act"];
  if(isset($_GET['annee'])) {$annee=$_GET['annee'];}
  $query_sup_act = "DELETE FROM ".$database_connect_prefix."ptba WHERE id_ptba='$id'";
  
      try{
    $Result1 = $pdar_connexion->prepare($query_sup_act);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      $insertGoTo .= "&annee=$annee";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }
  

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{ //ptba
//$id_cp=$_POST["id_cp"];
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $responsable=""; //$idcl=$_POST['isous_composante'];
  if(isset($_POST['annee'])) {$annee=$_POST['annee'];}
  $personnel=$_SESSION['clp_id'];
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."ptba (projet, annee, code_activite_ptba, intitule_activite_ptba, debut, region, acteur_conserne, responsable, isous_composante, observation, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, '$personnel', '$date')",
   					   GetSQLValueString($_SESSION["clp_projet"], "text"), 
                      // GetSQLValueString($_POST['acteur_ptba'], "text"),
					   GetSQLValueString($annee, "int"),
					   GetSQLValueString($_POST['code_activite_ptba'], "text"),
                       GetSQLValueString($_POST['intitule_activite_ptba'], "text"),
					   GetSQLValueString(implode(',',$_POST['mois']), "text"), 
					   GetSQLValueString(implode(',',$_POST['region']), "text"),
					   GetSQLValueString(implode(',',$_POST['acteur_conserne']), "text"),
  					  // GetSQLValueString(implode(',',$_POST['commune_conserne']), "text"),
                       GetSQLValueString($_POST['responsable'], "text"),
					   //GetSQLValueString($_POST['categorie_depense'], "text"),
					   GetSQLValueString($_POST['type_act'], "int"),
                       GetSQLValueString($_POST['observation'], "text"));
  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
	$idactfrits = $db->lastInsertId();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  	//$idactfrits = mysql_insert_id();
  if ($Result1) $insertGoTo .= "?insert=ok&actc=$idactfrits"; else $insertGoTo .= "?insert=no";
      $insertGoTo .= "&annee=$annee";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
    $id = ($_POST["MM_delete"]);
	    if(isset($_POST['annee'])) {$annee=$_POST['annee'];} 
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."ptba WHERE id_ptba=%s",
                         GetSQLValueString($id, "text")); 
    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      $insertGoTo .= "&annee=$annee";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }

  if (isset($_POST["MM_update"]) && !empty($_POST["MM_update"])) {
    $id = $_POST["MM_update"];
    if(isset($_POST['annee'])) {$annee=$_POST['annee'];} 
    $personnel=$_SESSION['clp_id'];
    //$partenaire="";
    $responsable="";

   $insertSQL = sprintf("UPDATE ".$database_connect_prefix."ptba SET  acteur_ptba=%s, code_activite_ptba=%s, intitule_activite_ptba=%s, debut=%s, region=%s, observation=%s, acteur_conserne=%s, responsable=%s, isous_composante=%s,  etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_ptba=%s",
      /* GetSQLValueString($_POST['type_act'], "int"),
	    GetSQLValueString($_POST['ca_budget'], "text"),*/
   		GetSQLValueString($_POST['acteur_ptba'], "text"),
        GetSQLValueString($_POST['code_activite_ptba'], "text"),
        GetSQLValueString($_POST['intitule_activite_ptba'], "text"),
        GetSQLValueString(implode(',',$_POST['mois']), "text"),
        GetSQLValueString(implode(',',$_POST['region']), "text"),
        GetSQLValueString($_POST['observation'], "text"),
	    GetSQLValueString(implode(',',$_POST['acteur_conserne']), "text"),
	   // GetSQLValueString(implode(',',$_POST['commune_conserne']), "text"),
        GetSQLValueString($_POST['responsable'], "text"),
		//GetSQLValueString($_POST['categorie_depense'], "text"),
		GetSQLValueString($_POST['type_act'], "int"),
        //GetSQLValueString(implode(',',$_POST['commune_conserne']), "text"),
       // GetSQLValueString($_POST['seuil'], "double"),
       // GetSQLValueString($responsable, "text"),
        GetSQLValueString($id, "text"));
 
      try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  $idactfrits = $id;
  if ($Result1) $insertGoTo .= "?update=ok&actc=$idactfrits"; else $insertGoTo .= "?update=no";
      $insertGoTo .= "&annee=$annee";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }
}    


if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1rev"))
{ //ptba
//$id_cp=$_POST["id_cp"];
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
//print_r($_POST['region']);
    if(isset($_POST['annee'])) {$annee=$_POST['annee'];} 
 foreach($_POST['sous_secteur'] as $idact){
  //echo $idact;
	//insertion indicateur
	 $personnel=$_SESSION['clp_id'];
	 
$insertSQL= "INSERT INTO ptba (projet, annee,  code_activite_ptba, isous_composante, intitule_activite_ptba, debut, region, acteur_conserne, responsable, observation, id_personnel, date_enregistrement)
				   SELECT projet, ". $_POST['anneerev'].",  code_activite_ptba, isous_composante, intitule_activite_ptba, debut, region, acteur_conserne, responsable, observation, '$personnel', '$date' FROM ptba where  id_ptba='$idact'";
				   
				         try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
	//$idactins = mysql_insert_id();
	 $idactins = $db->lastInsertId();
	
	
	//insertion des couts
$insertSQL2c= "INSERT INTO part_bailleur (projet, structure, activite, annee, type_part, montant, observation, id_personnel, date_enregistrement)
				   SELECT projet, structure,'$idactins' , ". $_POST['anneerev'].", type_part, montant, observation, '$personnel', '$date' FROM part_bailleur where  activite='$idact'";
	      try{
    $Result1 = $pdar_connexion->prepare($insertSQL2c);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
	
	//selection tache activite en cours
	$query_liste_ntache = "SELECT *  FROM groupe_tache WHERE  id_activite='$idact'";
	         try{
  $liste_ntache = $pdar_connexion->prepare($query_liste_ntache);
    $liste_ntache->execute();
    $row_liste_ntache = $liste_ntache ->fetchAll();
    $totalRows_liste_ntache = $liste_ntache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
	
	//echo $query_liste_ntache; 
	if($totalRows_liste_ntache>0){
	foreach($row_liste_ntache as $row_liste_ntache){
	 
	 $idtache=$row_liste_ntache["id_groupe_tache"];
	 //insertion des taches
$insertSQL2= "INSERT INTO groupe_tache (id_groupe_tache, id_activite, intitule_tache, proportion, code_tache,   n_lot, jalon, valider, responsable,entite, id_personnel, date_enregistrement)
				   SELECT '$idtache', '$idactins', intitule_tache, proportion, code_tache,  n_lot, jalon, valider,  responsable,entite, '$personnel', '$date' FROM groupe_tache where  id_groupe_tache='$idtache' and id_activite='$idact'";
		      try{
    $Result1 = $pdar_connexion->prepare($insertSQL2);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
	
	//echo $insertSQL2;	exit;
	  
	  } }
	
	//selection des indicateurs activite en cours
	$query_liste_ntacheind = "SELECT *  FROM indicateur_tache WHERE  id_activite='$idact'";
			         try{
  $liste_ntacheind = $pdar_connexion->prepare($query_liste_ntacheind);
    $liste_ntacheind->execute();
    $row_liste_ntacheind = $liste_ntacheind ->fetchAll();
    $totalRows_liste_ntacheind = $liste_ntacheind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

	
	if($totalRows_liste_ntacheind>0){
	foreach($row_liste_ntacheind as $row_liste_ntacheind){
	 
	 $idtacheind=$row_liste_ntacheind["id_indicateur_tache"];
	 //insertion des indicateurs
$insertSQL2i= "INSERT INTO indicateur_tache (code_indicateur_ptba, id_activite, unite, indicateur_cr, tache,  intitule_indicateur_tache, id_personnel, date_enregistrement)
				   SELECT code_indicateur_ptba, '$idactins', unite, indicateur_cr, tache,  intitule_indicateur_tache, '$personnel', '$date' FROM indicateur_tache where  id_indicateur_tache='$idtacheind'";
			      try{
    $Result1 = $pdar_connexion->prepare($insertSQL2i);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  	
	$query_liste_ntachelind = "SELECT LAST_INSERT_ID() as val";	
   try{
  $liste_ntachelind = $pdar_connexion->prepare($query_liste_ntachelind);
    $liste_ntachelind->execute();
    $row_liste_ntachelind = $liste_ntachelind ->fetch();
    $totalRows_liste_ntachelind = $liste_ntachelind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

	
	$idtacheinsi = $row_liste_ntachelind["val"];
	 
		 //insertion  des cibles trimestrielles par UGL
$insertSQL23i= "INSERT INTO cible_indicateur_trimestre (indicateur, region, trimestre, cible, id_personnel, date_enregistrement)
				   SELECT $idtacheinsi, region, trimestre, cible, '$personnel', '$date' FROM cible_indicateur_trimestre where  indicateur='$idtacheind'";
				      try{
    $Result1 = $pdar_connexion->prepare($insertSQL23i);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
  	 
	 //insertion suivi des indicateurs
/**/$insertSQL22i2= "INSERT INTO suivi_indicateur_tache (ugl, indicateur, date_suivi, valeur_suivi, commune, personnel, date_enregistrement)
				   SELECT ugl, $idtacheinsi, date_suivi, valeur_suivi, commune, '$personnel', '$date' FROM suivi_indicateur_tache where  indicateur='$idtacheind'";
    try{
    $Result1 = $pdar_connexion->prepare($insertSQL22i2);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
  
	  } }

	 }
	 
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    $insertGoTo .= "&annee=$annee";
    header(sprintf("Location: %s", $insertGoTo));  exit();  

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
 <style type="text/css">
<!--
#demo-container{padding:2px 15px 2 15px;/*background:#67A897;*/}
ul#simple-menu{list-style-type:none;width:100%;position:relative;height:20px;font-family:"Trebuchet MS",Arial,sans-serif;font-size:13px;font-weight:bold;margin:0;padding:0;}
ul#simple-menu li{display:block;float:left;margin:0 0 0 4px;height:20px;}
ul#simple-menu li.left{margin:0;}
ul#simple-menu li a{display:block;float:left;color:#fff;background:#4A6867;text-decoration:none;padding:3px 18px;}
ul#simple-menu li a.right{padding-right:19px;}
ul#simple-menu li a:hover{background:#2E4560;}
ul#simple-menu li a.current{color:#FFF;background:#ff0000;}
ul#simple-menu li a.current:hover{color:#FFF;background:#ff0000;}
-->
</style>
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
<script>
$().ready(function() {
    init_tabs();
});

function show_tab(tab) {
    if (!tab.html()) {
        tab.load(tab.attr('data-target'));
    }
}

function init_tabs() {
    show_tab($('.tab-pane.active'));
    $('a[data-toggle="tab"]').click('show', function(e) {
        tab = $('#' + $(e.target).attr('href').substr(1));
        show_tab(tab);
    });
}
</script>

<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> PTBA  </h4>
<?php if (isset ($_SESSION["clp_niveau"]) && ($_SESSION["clp_niveau"] == 0)) {
echo do_link("","","Edition de version de PTBA","Versions de PTBA","simple","./","pull-right p11","get_content('new_version_ptba.php','','modal-body_add',this.title,'iframe');",1,"",$nfile);
 } ?>
</div>

<div class="widget-content" style="display: block;">

<div class="tabbable tabbable-custom" >
  <ul class="nav nav-tabs" >

  
   <?php  foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>
   <li title="" class="<?php echo ($aversionP[0]==$version)?"active":""; ?>"><a href="#tab_feed_<?php echo $aversionP[0]; ?>" data-toggle="tab"><?php echo $aversionP[2]." ".$aversionP[1]; ?></a></li>
              <?php } ?>
  </ul>
  <div class="tab-content">

  
    <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP);  ?>
   <div class="tab-pane <?php echo ($aversionP[0]==$version)?"active":""; ?>" id="tab_feed_<?php echo $aversionP[0]; ?>" data-target="./plan_ptba_content.php?annee=<?php echo $aversionP[0]."&version=".$aversionP[1]."&actc=$actc&cmp=$cmp"; ?>"></div>
	          <?php } ?>
  </div>
</div>

</div>

</div></div>

<!-- Fin Site contenu ici -->
           
        </div>

        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>

</body>
</html>