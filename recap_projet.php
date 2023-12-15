<?php
   ///////////////////////////////////////////////
  /*                 SSE                       */
 /*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////

 // session_start();
  include 'system/configuration.php';
  $config = new Config;
  //exit();
 if(date("m")<4) $annee = date("Y")-1; else  $annee = date("Y");
/* $ugl=$_SESSION["clp_structure"];
if($_SESSION["clp_structure"]=='01') {$cmp=$ugl="%";} else  {$cmp =$ugl=$_SESSION["clp_structure"];}

if(isset($_GET['cmp'])) {$ugl=$cmp = $_GET['cmp'];}

$uglprojet=str_replace("|",",",$_SESSION["clp_projet_ugl"]); */
$uglprojet="";
$query_liste_ugl = "SELECT distinct code_ugl, abrege_ugl, nom_ugl FROM ".$database_connect_prefix."ugl, projet /*where  FIND_IN_SET(code_ugl,'".$uglprojet."')*/   order by code_ugl";
try{
    $liste_ugl = $pdar_connexion->prepare($query_liste_ugl);
    $liste_ugl->execute();
    $row_liste_ugl = $liste_ugl ->fetchAll();
    $totalRows_liste_ugl = $liste_ugl->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableauRegion = array(); $nbregi=0;
foreach($row_liste_ugl as $row_liste_ugl1){
  $tableauRegion[] = $row_liste_ugl1['code_ugl']."<>".$row_liste_ugl1['abrege_ugl']; $nbregi=$nbregi+1;
}
/*if($totalRows_liste_cat_dep_init>0){
$taux = ($row_edit_sanom['dotation']>0)?($row_edit_sanom['montant_paye']+$row_liste_cat_dep_init["decaissement"])/$row_edit_sanom['dotation']:0; $taux = $taux*100;
  }
else{   */
//$taux = ($dotation>0)?$montant_paye/$dotation:0; $taux = $taux*100; //}
//".$_SESSION["clp_where"]." and
$query_liste_indicateur_ref = "SELECT intitule_indicateur as intitule_ref_ind, id_ref_ind, requete_sql, unite, numfeuille, numclasseur  FROM  referentiel_indicateur, indicateur_produit_cmr where referentiel=id_ref_ind and cle=1  and type_ref_ind=1 ORDER BY type_ref_ind, code_ref_ind";//and projet_prd='".$_SESSION["clp_projet"]."'
try{
    $liste_indicateur_ref = $pdar_connexion->prepare($query_liste_indicateur_ref);
    $liste_indicateur_ref->execute();
    $row_liste_indicateur_ref = $liste_indicateur_ref ->fetchAll();
    $totalRows_liste_indicateur_ref = $liste_indicateur_ref->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$query_cible_indicateur = "SELECT referentiel, sum(valeur_cible) as valeur_cible, avg(valeur_cible) as valeur_ciblem  FROM   ".$database_connect_prefix."cible_cmr_produit, indicateur_produit_cmr where id_indicateur=indicateur_produit group by referentiel";//and projet_prd='".$_SESSION["clp_projet"]."'
try{
    $cible_indicateur = $pdar_connexion->prepare($query_cible_indicateur);
    $cible_indicateur->execute();
    $row_cible_indicateur = $cible_indicateur ->fetchAll();
    $totalRows_cible_indicateur = $cible_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$cible_array = array();
$ciblem_array = array();
if($totalRows_cible_indicateur>0){ foreach($row_cible_indicateur as $row_cible_indicateur){
  $cible_array[$row_cible_indicateur["referentiel"]]=$row_cible_indicateur["valeur_cible"];
  $ciblem_array[$row_cible_indicateur["referentiel"]]=$row_cible_indicateur["valeur_ciblem"];
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
<body id="newBodyW">
<script>     /*
$(".tab-pane").slimscroll({
                        height: "100%",
                        wheelStep: 7
                    });  */

function show_tab(tab) {
    if (tab.html()) {
        tab.load(tab.attr('data-target'));
    }
}

function init_tabs() {
    show_tab($('.tab-pane.active'));
    $('a[data-toggle="tab"]').click('show', function(e) {
        tab = $('#' + $(e.target).attr('href').substr(1));
        show_tab(tab);
    });
	
	 show_tab($('#second_tab .tab-pane.active'));
    $('#second_tab a[data-toggle="tab"]').click('show', function(e) {
        tab = $('#' + $(e.target).attr('href').substr(1));
        show_tab(tab);
    });
	
	 show_tab($('#first_tab .tab-pane.active'));
    $('#first_tab a[data-toggle="tab"]').click('show', function(e) {
        tab = $('#' + $(e.target).attr('href').substr(1));
        show_tab(tab);
    });
}

$(function () {
    init_tabs();
    $(parent.window).height($(document).height());
});

</script>
<style>
.feeds li {
  background-color: #FFF;
}
.tabs-right.tabbable-custom .nav-tabs1>li a, .tabs-left.tabbable-custom .nav-tabs1>li a{
  padding: 9px 8px;
}
.statbox .visual {
  float: none;
  margin: -10px!important;
  margin-bottom: 5px!important;
}

.visual {
  /*height: 50px;*/
  height:4px!important;
  min-height: 4px!important;
  padding:0px!important;
}
.rowSP {
  background-color: #f9f9f9;
  border-top: 1px solid #d9d9d9;
  border-bottom: 1px solid #d9d9d9;
}
</style>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; } .DTTT, .TableTools { display: none!important; }
#madiv{height:325px;overflow:auto} 
#madiv2{height:200px;overflow:auto} 
</style>
<div class="col-lg-6" style="margin-bottom: 10px;">
  <div class="hpanel" style="border-top: 2px solid <?php echo $Panel_Item_Style; ?>!important;">
    <div class="panel-body" style="padding: 15px 0;<?php echo ($row_projet['statut']==0)?"":"background-color: #DCDCDC;"; ?>">
      <div class="row0" style="text-align: left">
        <div class="col-sm-7">
          <h4 style="margin-top: 0px;"><a href="javascript:void(0);"><span style="color:#000;">
            <?php  if(isset($row_projet['nom_abrege'])) echo $row_projet['nom_abrege']; else echo $row_projet['sigle_projet']; ?>
            </span><br />
            <span style="font-size: 10px;text-transform: uppercase;"><?php echo $row_projet['programme']; ?></span></a></h4>
          <p><?php echo $row_projet['intitule_projet']; ?></p>
        </div>
        <div class="col-sm-5 project-info">
          <div class="project-action" align="right">
            <div class="btn-group" style="font-size: 18px"> <?php //echo do_link_modern("","./projet_details.php?id=".$id,"Afficher","","view","./","btn btn-xs btn-default","",0,"",$nfile);

if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){

echo do_link_modern("","","Actualiser l'icone du projet ".$row_projet['code_projet'],"","reload","./","btn btn-xs btn-default","get_content('edit_projet_photo.php','id=$id&code=".$row_projet['code_projet']."','modal-body_add',this.title);",1,"",$nfile);

echo do_link_modern("",$nfile."?statut=".($row_projet['statut']==0?1:0)."&id_actif=".$id,"Supprimer","","active","./","btn btn-xs btn-default","return confirm('Voulez-vous vraiment ".($row_projet['statut']==0?"Désactiver":"Activer")." ce projet ".$row_projet['code_projet']."');",0,"",$nfile);

echo do_link_modern("","","Modifier projet ".$row_projet['code_projet'],"","edit","./","btn btn-xs btn-default","get_content('./new_projet.php','id=$id','modal-body_add',this.title);",1,"",$nfile);

echo do_link_modern("",$nfile."?id_sup=".$id,"Supprimer","","del","./","btn btn-xs btn-default","return confirm('Voulez-vous vraiment supprimer ce projet ".$row_projet['code_projet']."');",0,"",$nfile);

} ?> </div>
          </div>
          <div class="project-value m-t-md">
            <!-- <img class="img-circle m-b" <?php //echo 'style="border: solid 1px '.$Panel_Item_Style.'"'; ?> src="<?php //echo (file_exists("./images/projet/img_$id.jpg"))?"./images/projet/img_$id.jpg":"./images/projet/none.png"; ?>" width="130" height="130" alt="">-->
            <h5 class="text-warning">
              <?php if(isset($tableauCoutDecaisse[$id]) && isset($projet_cout_array[$id]) && $projet_cout_array[$id]>0) {
										echo "Décaissement: ".number_format(100*$tableauCoutDecaisse[$id]/$projet_cout_array[$id], 0, ',', ' ')." %";} ?>
            </h5>
            <img class="img-circle clear pull-right" <?php echo 'style="border: solid 1px '.$Panel_Item_Style.'"'; ?> src="<?php echo (file_exists("./images/projet/img_$id.png"))?"./images/projet/img_$id.png":"./images/projet/none.png"; echo "?".date("s"); ?>" width="50" height="50" alt="" title="<?php echo $row_projet['sigle_projet']; ?>" /> </div>
        </div>
      </div>
      <div class="col-sm-12">
        <div class="col-sm-3">
          <div class="project-label">Signature</div>
          <b><small><?php echo date_reg($row_projet['date_signature'],"/"); ?></small></b> </div>
        <div class="col-sm-4">
          <div class="project-label">Financement</div>
          <b><small><?php echo $row_projet['modalite_financement']; ?></small></b> </div>
        <div class="col-sm-5">
          <div class="project-label" align="center">Budget (<em>$US</em>)
              <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1 /*&& $_SESSION["structure"]==$row_projet['agence_lead']*/) echo do_link("","","Coût du projet ".$row_projet['sigle_projet'],"","edit","./","pull-right","get_content('projet_budget.php','id=$id','modal-body_add',this.title,'iframe');",1,"",$nfile); ?>
          </div>
          <b><small>
            <div style="font-weight: bold;">
              <div align="center"><span class="btn-info">
                <?php  if(isset($projet_cout_array[$id])) echo "&nbsp;&nbsp;<span title=\"".number_format($projet_cout_array[$id], 0, ',', ' ')." USD\">".number_format($projet_cout_array[$id], 0, ',', ' ')."&nbsp;&nbsp;</span>";  else echo ""; ?>
              </span></div>
            </div>
          </small></b> </div>
      </div>
      <div class="col-sm-12">&nbsp;</div>
      <div class="col-sm-12">
        <div class="col-sm-4">
          <div class="project-label">Agence lead</div>
          <b><small><?php echo (isset($row_projet['agence_lead']) && isset($liste_structure_array[$row_projet['agence_lead']]))?"<span title=\"".$liste_structure_arrayV[$row_projet['agence_lead']]."\">".$liste_structure_array[$row_projet['agence_lead']]."</span>":"Aucune"; if(isset($row_projet['agence_lead']) && isset($liste_structure_array[$row_projet['agence_lead']])){ ?>: <strong class="text-info">
            <?php if(isset($projet_cout_agence_array[$id][$row_projet['agence_lead']])) echo "&nbsp;".number_format($projet_cout_agence_array[$id][$row_projet['agence_lead']], 0, ',', ' ')." $&nbsp;"; ?>
            </strong>
            <?php } ?>
          </small></b> </div>
        <div class="col-sm-4">
          <div class="project-label">Autres agences</div>
          <b><small>
            <?php if(isset($row_projet['autres_agences_recipiendaires']) && !empty($row_projet['autres_agences_recipiendaires'])){ $a = explode(",",$row_projet['autres_agences_recipiendaires']); if(count($a)>0){ $c = array(); foreach($a as $b) { if(isset($projet_cout_agence_array[$id][$b]))  $maar=": <strong class=\"text-info\">&nbsp;".number_format($projet_cout_agence_array[$id][$b], 0, ',', ' ')." $</strong>&nbsp;"; else $maar=""; if(isset($liste_structure_array[$b])) $c[]="<span title=\"".$liste_structure_arrayV[$b]."\">".$liste_structure_array[$b]."".$maar.""."</span>"; } echo count($c)>0?implode('; &nbsp;',$c):"Aucun"; } else echo "Aucun"; } else echo "Aucun"; ?>
          </small></b> </div>
        <div class="col-sm-4">
          <div class="project-label">Personnes dédiées&nbsp;
              <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) echo do_link("","","Personnels dédiés au projet ".$row_projet['sigle_projet'],"","edit","./","pull-right","get_content('projet_users.php','id=$id','modal-body_add',this.title,'iframe');",1,"",$nfile); ?>
          </div>
          <small>
            <div style="font-weight: bold;"><span class=" ">
              <?php $a = array(); if(isset($projet_user_array[$id])) $a = explode(",",$projet_user_array[$id]); if(count($a)>0){ $c = array(); foreach($a as $b){ if(isset($User_array[$b])) $c[]="<span title=\"".$User_array[$b]."\">".$Nuser_array[$b]."</span>"; } echo implode('; ',$c); } else echo "Aucune"; ?>
            </span></div>
          </small> </div>
      </div>
      <div class="row">&nbsp;</div>
    </div>
    <div class="panel-footer contact-panel" style="padding: 0px 15px;">
      <div class="row">
        <div class="col-md-4 border-right">
          <div class="contact-stat"><span>Date de demarrage : </span> <strong><?php echo date_reg($row_projet['date_demarrage'],"/"); ?></strong></div>
        </div>
        <div class="col-md-4 border-right">
          <div class="contact-stat"><span>Durée : </span> <strong>
            <?php $nombreMois = $row_projet['duree'];$annees = intval($nombreMois / 12);$mois = intval(($nombreMois % 12)); echo "$annees an".($annees>1?"s":"").($mois>0?" $mois mois":""); ?>
          </strong></div>
        </div>
        <div class="col-md-4">
          <?php if(isset($tableauTache[$id]) && isset($Nactivite_array[$id]) && $Nactivite_array[$id]>0) $tauxP=$tableauTache[$id]/$Nactivite_array[$id];  else $tauxP=0;?>
          <div class="project-label contact-stat-span" align="left">Avancement (<strong>
            <?php if($tauxP>0) echo number_format($tauxP, 2, ',', ' '); ?>
            %</strong>)</div>
          <div class="progress m-t-xs full progress-small">
            <?php if($tauxP>0) {if($tauxP<30) $np="danger"; elseif($tauxP<75) $np="warning"; elseif($tauxP>75) $np="success";  ?>
            <div style="width: <?php echo $tauxP; ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $tauxP; ?>" role="progressbar" class=" progress-bar progress-bar-<?php echo $np; ?>"> </div>
            <?php } else echo "non démarré";?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>