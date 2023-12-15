<?php
   ///////////////////////////////////////////////
  /*                 SSE                       */
 /*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////

  session_start();
  include_once 'system/configuration.php';
  $config = new Config;
  /*
  include_once $config->sys_folder."/database/credential.php";
  include_once $config->sys_folder."/database/essentiel.php";
  */
  if(!isset($_SESSION["clp_id"]))
  {
    header(sprintf("Location: %s", "./")); exit;
  }
  include_once $config->sys_folder."/database/db_connexion.php";

  $filtre = (isset($_GET['q']) && !empty($_GET['q']))?(((!get_magic_quotes_gpc()) ? addslashes(utf8_decode($_GET['q'])) : utf8_decode($_GET['q']))):"";
  $filtre_affiche = (isset($_GET['q']) && !empty($_GET['q']))?utf8_decode($_GET['q']):"";

  function keyword($keyword,$filtre,$cible)
  {
    if(is_array($keyword) && count($keyword) > 0 && !empty($filtre))
    {
      $i = 0; $j = 0; $not_first = false; $query_liste_gv = "";
      foreach($keyword as $a => $b)
      {
        if(strstr($a, $cible))
        {
          if($i==0) { $i++; $query_liste_gv .= " AND ( "; $j=1; }
          if(!empty($filtre))//if(!empty($b))
          {
            if($not_first == false)
            {
              $query_liste_gv .= $a." LIKE '%".$filtre."%' ";
              $not_first = true;
            }
            else
            {
              $query_liste_gv .= " OR ".$a." LIKE '%".$filtre."%' ";
            }
          }
        }
        elseif(strstr($a, ".")==false)
        {
          if($j==1) $not_first = true;
          if($i==0) { $i++; $query_liste_gv .= " AND ( "; $j=1; }
          if(!empty($filtre))//if(!empty($b))
          {
            if($not_first == false)
            {
              $query_liste_gv .= $a." LIKE '%".$filtre."%' ";
              $not_first = true;
            }
            else
            {
              $query_liste_gv .= " OR ".$a." LIKE '%".$filtre."%' ";
            }
          }
        }
      }
      if($j==1) $query_liste_gv .= ") ";
    }
    return $query_liste_gv;
  }

  $keyword = array("nom_departement"=>"","nom_sous_prefecture"=>"","activite"=>"","intitule_filiere"=>"",/*"nom_village"=>"","date_mise_oeuvre"=>"","date_approbation"=>"","maillon"=>"","filiere"=>"",*/"promoteur_individu.nom_prenom"=>""/*,"type_promoteur"=>""*//*,"promoteur_individu.titre_microprojet"=>""/*,"site_microprojet"=>"","sigle"=>""*/,/*"nom_departement"=>"","nom_commune"=>"","nom_village"=>"","date_mise_oeuvre"=>"","date_approbation"=>"","maillon"=>"","filiere"=>"","promoteur"=>"","type_promoteur"=>"",*/"titre_microprojet"=>""/*,"sigle"=>""*/,"groupement.denomination"=>"","groupement.sigle"=>"","nom_village"=>"");
/*
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_gv = "SELECT nom_cercle, nom_commune, nom_village, microprojet.* FROM ".$database_connect_prefix."cercle, ".$database_connect_prefix."commune, ".$database_connect_prefix."village, ".$database_connect_prefix."microprojet, ".$database_connect_prefix."promoteur_individu, ".$database_connect_prefix."filiere F, ".$database_connect_prefix."activite_principale where id_cercle=cercle and id_commune=village.commune and id_village=microprojet.village and type_promoteur='individu' and promoteur=id_promoteur and maillon=id_activite and F.id_filiere=filiere ";
  $query_liste_gv .= keyword($keyword,$filtre,"promoteur_individu.");
  $query_liste_gv .= " UNION ";
  $query_liste_gv .= "SELECT nom_cercle, nom_commune, nom_village, microprojet.* FROM cercle, commune, village, microprojet, groupement, filiere F, activite_principale where id_cercle=cercle and id_commune=village.commune and id_village=microprojet.village and type_promoteur='groupement' and promoteur=id_groupement and maillon=id_activite and F.id_filiere=filiere ";
  $query_liste_gv .= keyword($keyword,$filtre,"groupement.");
  $liste_gv  = mysql_query($query_liste_gv , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_gv  = mysql_fetch_assoc($liste_gv);
  $totalRows_liste_gv  = mysql_num_rows($liste_gv);

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_promoteur_promoteur = "SELECT id_promoteur, nom_prenom FROM promoteur_individu ORDER BY id_promoteur desc";
  $liste_promoteur_promoteur  = mysql_query($query_liste_promoteur_promoteur , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_promoteur_promoteur  = mysql_fetch_assoc($liste_promoteur_promoteur );
  $totalRows_liste_promoteur_promoteur  = mysql_num_rows($liste_promoteur_promoteur );
  $tableau_nom_promoteur = array();
  if($totalRows_liste_promoteur_promoteur>0){  do{
    $tableau_nom_promoteur[$row_liste_promoteur_promoteur["id_promoteur"]]=$row_liste_promoteur_promoteur["nom_prenom"];
    }while($row_liste_promoteur_promoteur = mysql_fetch_assoc($liste_promoteur_promoteur));
  }

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_promoteur_groupement = "SELECT id_groupement, sigle, contact, denomination FROM groupement ORDER BY  id_groupement desc";
  $liste_promoteur_groupement  = mysql_query($query_liste_promoteur_groupement , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_promoteur_groupement  = mysql_fetch_assoc($liste_promoteur_groupement );
  $totalRows_liste_promoteur_groupement  = mysql_num_rows($liste_promoteur_groupement );
  $tableau_sigle_groupement = array();
  $tableau_nom_groupement = array();
  if($totalRows_liste_promoteur_groupement>0){  do{
   $tableau_sigle_groupement[$row_liste_promoteur_groupement["id_groupement"]]=$row_liste_promoteur_groupement["sigle"];
    $tableau_nom_groupement[$row_liste_promoteur_groupement["id_groupement"]]=$row_liste_promoteur_groupement["denomination"];
    }while($row_liste_promoteur_groupement = mysql_fetch_assoc($liste_promoteur_groupement));
  }

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_type_mp = "SELECT id_type, type_mp FROM type_mp ORDER BY type_mp";
  $liste_type_mp  = mysql_query($query_liste_type_mp , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_type_mp  = mysql_fetch_assoc($liste_type_mp);
  $totalRows_liste_type_mp= mysql_num_rows($liste_type_mp);
  $tableau_type_mp = array();
  if($totalRows_liste_type_mp>0){  do{
   $tableau_type_mp[$row_liste_type_mp["id_type"]]=$row_liste_type_mp["type_mp"];
    }while($row_liste_type_mp = mysql_fetch_assoc($liste_type_mp));
  }


  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_filiere = "SELECT id_filiere, intitule_filiere FROM filiere ORDER BY intitule_filiere";
  $liste_filiere  = mysql_query($query_liste_filiere , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_filiere  = mysql_fetch_assoc($liste_filiere);
  $totalRows_liste_filiere= mysql_num_rows($liste_filiere);
  $tableau_filiere = array();
  if($totalRows_liste_filiere>0){  do{
   $tableau_filiere[$row_liste_filiere["id_filiere"]]=$row_liste_filiere["intitule_filiere"];
    }while($row_liste_filiere = mysql_fetch_assoc($liste_filiere));
  }

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_maillon = "SELECT id_activite, activite FROM activite_principale ORDER BY code_activite";
  $liste_maillon  = mysql_query($query_liste_maillon , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_maillon  = mysql_fetch_assoc($liste_maillon);
  $totalRows_liste_maillon= mysql_num_rows($liste_maillon);
  $tableau_maillon = array();
  if($totalRows_liste_maillon>0){  do{
   $tableau_maillon[$row_liste_maillon["id_activite"]]=$row_liste_maillon["activite"];
    }while($row_liste_maillon = mysql_fetch_assoc($liste_maillon));
  }

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_prestataire = "SELECT id_secteur_prive, sigle, adresse FROM secteur_prive ORDER BY sigle";
  $liste_prestataire  = mysql_query($query_liste_prestataire , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_prestataire  = mysql_fetch_assoc($liste_prestataire);
  $totalRows_liste_prestataire= mysql_num_rows($liste_prestataire);
  $tableau_prestataire = array();
  $contact_prestataire = array();
  if($totalRows_liste_prestataire>0){  do{
   $tableau_prestataire[$row_liste_prestataire["id_secteur_prive"]]=$row_liste_prestataire["sigle"];
    $contact_prestataire[$row_liste_prestataire["id_secteur_prive"]]=$row_liste_prestataire["adresse"];
    }while($row_liste_prestataire = mysql_fetch_assoc($liste_prestataire));
  }

  if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_sup_gv = "DELETE FROM microprojet WHERE id_projet='$id'";
  $Result1 = mysql_query($query_sup_gv, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo));
  }   */

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
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init();$("#container").addClass("sidebar-closed");});</script>
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
<style>#mtable tr td, .table thead tr th {vertical-align: middle; text-align: center; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }

</style>
<div class="widget box box_projet">
<div class="widget-header1"> <center><h4><?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> R&eacute;sultat de la recherche dans le r&eacute;pertoire des microprojets <?php echo (!empty($filtre_affiche))?"pour \"".$filtre_affiche."\"":""; ?> </h4>
</div>
<div class="widget-content">

<table class="table table-striped table-bordered table-hover table-responsive dataTable" id="" >
<thead>
<tr role="row">
<th rowspan="2" colspan="1" aria-label="Trier">D&eacute;partement</th>
<th rowspan="2" colspan="1" aria-label="Trier">Sou-pr&eacute;fecture</th>
<th rowspan="2" colspan="1" aria-label="Trier">Village</th>
<th rowspan="2" colspan="1" aria-label="Trier">Titre du microprojet</th>
<th rowspan="2" colspan="1" aria-label="Trier">Promoteur / Groupement</th>
<th rowspan="2" colspan="1" aria-label="Trier">Fili&egrave;re</th>
<th rowspan="2" colspan="1" aria-label="Trier">Maillon</th>
<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="2" aria-label="Trier">Dates</th>
<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="2" aria-label="Trier">Prestataire</th>
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="2" aria-label="Trier">Editer</th>
<?php } ?>
</tr>
<tr role="row">
<th rowspan="1" colspan="1" aria-label="Trier">Approbation</th>
<th rowspan="1" colspan="1" aria-label="Trier">Mise en oeuvre</th>
<th rowspan="1" colspan="1" aria-label="Trier">Sigle</th>
<th rowspan="1" colspan="1" aria-label="Trier">Contact</th>
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Trier">Actions</th>
<?php } ?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php
if($totalRows_liste_gv>0) {  do{ $id = $row_liste_gv['id_projet'];
?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo $row_liste_gv['nom_departement']; ?></td>
<td class=" "><?php echo $row_liste_gv['nom_sous_prefecture']; ?></td>
<td class=" "><?php echo $row_liste_gv['nom_village']; ?></td>
<td class=" "><?php echo $row_liste_gv['titre_microprojet']; ?></td>
<td class=" ">
<?php if(isset($row_liste_gv['type_promoteur']) && $row_liste_gv['type_promoteur']=="individu")
{
  if(isset($tableau_nom_promoteur[$row_liste_gv['promoteur']])) echo $tableau_nom_promoteur[$row_liste_gv['promoteur']]; $tab = array("mp_promoteur.php","microprojet individuel");
} else if(isset($row_liste_gv['type_promoteur']) && $row_liste_gv['type_promoteur']=="groupement")
{
  if(isset($tableau_sigle_groupement[$row_liste_gv['promoteur']])) echo $tableau_sigle_groupement[$row_liste_gv['promoteur']];
  if(isset($tableau_nom_groupement[$row_liste_gv['promoteur']])) echo $tableau_nom_groupement[$row_liste_gv['promoteur']]; $tab = array("mp_groupement.php","microprojet groupement");
}
else echo "-";   ?>
</td>
<td class=" "><?php  if(isset($tableau_filiere[$row_liste_gv['filiere']])) echo $tableau_filiere[$row_liste_gv['filiere']]; ?></td>
<td class=" "><?php  if(isset($tableau_maillon[$row_liste_gv['maillon']])) echo $tableau_maillon[$row_liste_gv['maillon']]; ?></td>
<td class=" "><?php echo date("d/m/y", strtotime($row_liste_gv['date_approbation'])); ?></td>
<td class=" "><?php echo date("d/m/y", strtotime($row_liste_gv['date_mise_oeuvre'])); ?></td>
<td class=" "><?php  if(isset($tableau_prestataire[$row_liste_gv['prestataire']])) echo $tableau_prestataire[$row_liste_gv['prestataire']]; ?></td>
<td class=" "><?php  if(isset($contact_prestataire[$row_liste_gv['prestataire']])) echo $contact_prestataire[$row_liste_gv['prestataire']]; ?></td>
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<td class=" " align="center">
<a onclick="get_content('<?php echo $tab[0]; ?>','id=<?php echo $id; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modification de <?php echo $tab[1]; ?>" class="thickbox Add"  dir=""><img src="images/edit.png" width='20' height='20' alt='Modifier' /></a>
<a href="<?php echo $_SERVER['PHP_SELF']."?id_sup=".$id.""?>" title="Supprimer ce microprojet" onclick="return confirm('Voulez-vous vraiment supprimer ce microprojet ?');" /><img src="images/delete.png" width="20" height="20" border="0"/></a>
</td>
<?php }?>
</tr>
<?php $i++; } while($row_liste_gv = mysql_fetch_assoc($liste_gv)); } else {  ?>
<tr>
<td align="center" colspan="<?php echo (isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1))?13:11; ?>" class=" "><h2>Aucun r&eacute;sultat <?php echo (!empty($filtre_affiche))?"pour \"".$filtre_affiche."\"":""; ?> !</h2></td>
</tr>
<?php } ?>
</tbody></table>

</div> </div>
</div>
<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div> <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>