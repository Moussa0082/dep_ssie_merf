<?php
   ///////////////////////////////////////////////
  /*                 SSE                       */
 /*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////

 // session_start();
  include_once 'system/configuration.php';
  $config = new Config;
  //exit();
 if(date("m")<4) $annee = date("Y")-1; else  $annee = date("Y");
 $ugl=$_SESSION["clp_structure"];
if($_SESSION["clp_structure"]=='01') {$cmp=$ugl="%";} else  {$cmp =$ugl=$_SESSION["clp_structure"];}

if(isset($_GET['cmp'])) {$ugl=$cmp = $_GET['cmp'];}

$uglprojet=str_replace("|",",",$_SESSION["clp_projet_ugl"]);

$query_liste_ugl = "SELECT distinct code_ugl, abrege_ugl, nom_ugl FROM ".$database_connect_prefix."ugl, projet where  FIND_IN_SET(code_ugl,'".$uglprojet."')   order by code_ugl";
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
$query_liste_indicateur_ref = "SELECT intitule_indicateur as intitule_ref_ind, id_ref_ind, requete_sql, unite, numfeuille, numclasseur  FROM  referentiel_indicateur, indicateur_produit_cmr where referentiel=id_ref_ind and cle=1  and 	type_ref_ind=1 and projet_prd='".$_SESSION["clp_projet"]."' ORDER BY type_ref_ind, code_ref_ind";
try{
    $liste_indicateur_ref = $pdar_connexion->prepare($query_liste_indicateur_ref);
    $liste_indicateur_ref->execute();
    $row_liste_indicateur_ref = $liste_indicateur_ref ->fetchAll();
    $totalRows_liste_indicateur_ref = $liste_indicateur_ref->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$query_cible_indicateur = "SELECT referentiel, sum(valeur_cible) as valeur_cible, avg(valeur_cible) as valeur_ciblem  FROM   ".$database_connect_prefix."cible_cmr_produit, indicateur_produit_cmr where id_indicateur=indicateur_produit   and projet_prd='".$_SESSION["clp_projet"]."' group by referentiel";
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
$query_liste_version = "SELECT * FROM ".$database_connect_prefix."version_ptba where (version_ptba!='Initiale' or id_version_ptba in (select max(ptba.annee) from ptba where projet='".$_SESSION["clp_projet"]."' )) ORDER BY date_validation asc";
try{
    $liste_version = $pdar_connexion->prepare($query_liste_version);
    $liste_version->execute();
    $row_liste_version = $liste_version ->fetchAll();
    $totalRows_liste_version = $liste_version->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$TableauVersionP = array(); $version_array = array();
if($totalRows_liste_version>0){ foreach($row_liste_version as $row_liste_version){
$max_version=$row_liste_version["id_version_ptba"];
$TableauVersionP[]=$row_liste_version["id_version_ptba"]."<>".$row_liste_version["version_ptba"]."<>".$row_liste_version["annee_ptba"];
$version_array[$row_liste_version["version_ptba"]] = $row_liste_version["id_version_ptba"];
 } }
if(isset($_GET['version'])) {$versiona=$_GET['version'];} elseif($totalRows_liste_version>0) $versiona=$max_version; else  $versiona=1;
?>
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
<div class="widget box box_projet">
<div class="widget-header1"> <center><h4><?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><!--<form name="form" id="form" method="get" action="" class="pull-left">
         UG :&nbsp;
<select name="cmp" onchange="form.submit();" style="background-color: #FFFF00; padding: 0px;" class="btn p11">
<?php //if($totalRows_liste_ugl>0){  foreach($row_liste_ugl as $row_liste_ugl){ ?>
<option value="<?php //echo $row_liste_ugl["code_ugl"]; ?>" <?php //if((isset($_GET["cmp"]) && $row_liste_ugl["code_ugl"]==$_GET["cmp"]) || $row_liste_ugl["code_ugl"]==$cmp) echo "selected='SELECTED'"; ?>><?php //echo $row_liste_ugl["code_ugl"].": ".$row_liste_ugl["abrege_ugl"]; ?></option>
<?php //} } ?>
<option value="%" <?php //if((!isset($_GET["cmp"]) || "%"==$_GET["cmp"])) echo "selected='SELECTED'"; ?>>Toutes</option>
</select>
<input type="hidden" name="annee" value="<?php echo $annee; ?>" />
</form>--><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>
<div style="padding-top:20px;">

<div class="col-md-6">
<div class="widget box ">
<div class="widget-header"> <h4><i class="icon-reorder"></i> R&eacute;sultats</h4>
</div>
<div id="madiv"> 
<table class="table table-striped table-hover" id="mtable">
  <thead>
    <tr>
      <th>Indicateur</th>
 <!--<?php //foreach($tableauRegion as $vregion){?><th align="center"><?php //$aregion = explode('<>',$vregion); $iregion = $aregion[0]; echo $aregion[1]; ?> </th> <?php //} ?>-->
      <th class="center">Total Pr&eacute;vu</th>
      <th class="center">Total R&eacute;alis&eacute;</th>
      <th class="center"> (%)</th>
    </tr>
  </thead>
  <?php if($totalRows_liste_indicateur_ref>0){$it=0; foreach($row_liste_indicateur_ref as $row_liste_indicateur_ref){ $tinf=""; $valind_tot=0; $valind=0; $query_liste_val_ref_g=$query_liste_val_ref=""; $idreff=$row_liste_indicateur_ref["id_ref_ind"];
//".$_SESSION["clp_where"]." and
$query_liste_val_ref = $row_liste_indicateur_ref["requete_sql"];
$query_liste_val_ref =str_replace("crp", "%", $query_liste_val_ref);
$query_liste_val_ref =str_replace("prdc", $_SESSION["clp_projet"], $query_liste_val_ref);
//$query_liste_val_ref.="where ";
//echo $ugl;
//echo $query_liste_val_ref;
try{
    $liste_val_ref = $pdar_connexion->prepare($query_liste_val_ref);
    $liste_val_ref->execute();
    $row_liste_val_ref = $liste_val_ref ->fetch();
    $totalRows_liste_val_ref = $liste_val_ref->rowCount();
}catch(Exception $e){ /*die(mysql_error_show_message($e));*/ }
 if ($liste_val_ref)
    {
      // Traitement de l'erreur
if(isset($row_liste_val_ref["val"])) $valind=$row_liste_val_ref["val"]; else $valind=0;
} 
$nclass=$row_liste_indicateur_ref["numclasseur"];
$nfeuil=$row_liste_indicateur_ref["numfeuille"];
 ?>
  <tbody>
    <tr>
      <td><?php  echo $row_liste_indicateur_ref["intitule_ref_ind"]; ?></td>
      <!-- <?php /*foreach($tableauRegion as $vregion){
	   $aregion = explode('<>',$vregion); $iregion = $aregion[0];
	   //".$_SESSION["clp_where"]." and
$query_liste_val_ref = $row_liste_indicateur_ref["requete_sql"];
$query_liste_val_ref =str_replace("crp", $iregion, $query_liste_val_ref);
$query_liste_val_ref =str_replace("prdc", $_SESSION["clp_projet"], $query_liste_val_ref);
try{
    $query_liste_val_ref = $pdar_connexion->prepare($query_liste_val_ref);
    $query_liste_val_ref->execute();
    $row_liste_val_ref = $query_liste_val_ref ->fetchAll();
    $totalRows_liste_val_ref = $query_liste_val_ref->rowCount();
}catch(Exception $e){/* /*die(mysql_error_show_message($e));*/ /*}*/
	   ?>
	   
	   <td align="center" > 
	   
	   <a style="display: block;" href="./fiches_dynamiques.php?<?php //echo "cmp=$iregion&id=$nclass&feuille=$nfeuil&annee=$annee"; ?>" title="Plus de d&eacute;tails"><?php  //if(isset($row_liste_val_ref["val"])) echo $row_liste_val_ref["val"]; ?></a>
	   
	    </td> <?php //} ?>-->
      <td nowrap="nowrap" class="center"><div align="right">
        <?php if(isset($cible_array[$row_liste_indicateur_ref["id_ref_ind"]])) echo number_format($cible_array[$row_liste_indicateur_ref["id_ref_ind"]], 0, ',', ' '); ?>
      </div></td>
      <td class="center"><div align="right"><b style="color: #000; display:block;  background-color: #D2E2B1;"> 
	  
	   <a style="display: block;" href="./fiches_dynamiques.php?<?php $at="%25"; echo "cmp=$at&id=$nclass&feuille=$nfeuil&annee=$annee"; ?>" title="<?php echo $row_liste_indicateur_ref["intitule_ref_ind"]; ?>"><?php  echo number_format(floatval($valind), 0, ',', ' '); ?></a>


	  
	   </b></div></td>
      <td class="center"><div align="center">
        <b style="color: #000; display:block;  color:#CC3300;">
        <?php if(isset($cible_array[$row_liste_indicateur_ref["id_ref_ind"]]) && $cible_array[$row_liste_indicateur_ref["id_ref_ind"]]>0) echo number_format((floatval($valind)/floatval($cible_array[$row_liste_indicateur_ref["id_ref_ind"]]))*100, 0, ',', ' ')."%"; else echo "Pas de prevision"; $row_liste_val_ref["val"]=""; ?>
      </b></div></td>
    </tr>
  </tbody>
  <?php $it++; } } else { ?>
  <tr>
    <th colspan="5"><div align="center">Aucun indicateur selectionn&eacute;</div></th>
  </tr>
  <?php } ?>
</table>
</div>
<div class="clear h0">&nbsp;</div>

</div></div>

<div class="col-md-6">
<div class="tabbable tabbable-custom" >
  <ul class="nav nav-tabs" >
    <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>
   <li title="" class="<?php echo ($aversionP[0]==$versiona)?"active":""; ?>"><a href="#tab_feed_<?php echo $aversionP[0]; ?>" data-toggle="tab"><?php echo $aversionP[2]." ".$aversionP[1]; ?></a></li>
              <?php } ?>
  </ul>
  <div class="tab-content">
  <?php //for($j=$_SESSION["annee_debut_projet"];$j<=date("Y");$j++){ if($j<=$_SESSION["annee_fin_projet"]) {?>
    <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>
  <div class="tab-pane <?php echo ($aversionP[0]==$versiona)?"active":""; ?>" id="tab_feed_<?php echo $aversionP[0]; ?>" data-target="./index_execution_ptba_composante.php?annee=<?php echo $aversionP[0]; ?>&cmp=<?php echo $cmp; ?>&vers=<?php echo $aversionP[2]; ?>" >
  </div>
  <?php } ?>
  </div>
</div>
</div>

<div class="clear h0">&nbsp;</div>

<?php  //and ".$database_connect_prefix."mission_supervision.projet='".$_SESSION["clp_projet"]."'
$query_edit_ms = "SELECT code_ms, id_mission, ".$database_connect_prefix."mission_supervision.type, debut, fin, objet FROM ".$database_connect_prefix."mission_supervision, ".$database_connect_prefix."recommandation_mission where id_mission=mission  order by debut desc limit 1";
try{
    $edit_ms = $pdar_connexion->prepare($query_edit_ms);
    $edit_ms->execute();
    $row_edit_ms = $edit_ms ->fetch();
    $totalRows_edit_ms = $edit_ms->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
/*echo $totalRows_edit_ms;
exit;
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rubrique = "select * from ".$database_connect_prefix."suivi_recommandation_mission order by recommandation asc";
$liste_rubrique = mysql_query($query_liste_rubrique, $pdar_connexion) or die(mysql_error());
$row_liste_rubrique = mysql_fetch_assoc($liste_rubrique);
$totalRows_liste_rubrique = mysql_num_rows($liste_rubrique);
$tableau_stat = array();
if($totalRows_liste_rubrique>0){  do{
  $tableau_stat[$row_liste_rubrique["recommandation"]]=$row_liste_rubrique["statut"]; }while($row_liste_rubrique = mysql_fetch_assoc($liste_rubrique));
} */
if(isset($row_edit_ms['id_mission'])) $id=$row_edit_ms['id_mission']; else $id=0;
$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."recommandation_mission, ".$database_connect_prefix."rubrique_projet where mission='$id'  and rubrique=code_rub ORDER BY code_rub asc, numero asc";
try{
    $liste_rec = $pdar_connexion->prepare($query_liste_rec);
    $liste_rec->execute();
    $row_liste_rec = $liste_rec ->fetchAll();
    $totalRows_liste_rec = $liste_rec->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$non_execute = $non_entame = $encours = $execute = 0;
 // $query_suivi_plan_ms = "SELECT sum(proportion) as texrecms, code_rec  FROM ".$database_connect_prefix."mission_plan where  valider=1 group by code_rec";
   $query_suivi_plan_ms = "SELECT sum(proportion) as texrecms, code_rec  FROM ".$database_connect_prefix."mission_plan, ".$database_connect_prefix."recommandation_mission  where code_rec=id_recommandation and mission='$id' and valider=1 group by code_rec";
try{
    $suivi_plan_ms = $pdar_connexion->prepare($query_suivi_plan_ms);
    $suivi_plan_ms->execute();
    $row_suivi_plan_ms = $suivi_plan_ms ->fetchAll();
    $totalRows_suivi_plan_ms = $suivi_plan_ms->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $prop_tab = array();
  if($totalRows_suivi_plan_ms>0){ foreach($totalRows_suivi_plan_ms as $totalRows_suivi_plan_ms){
    $prop_tab[$row_suivi_plan_ms["code_rec"]]=$row_suivi_plan_ms["texrecms"];
     }
  }
////statut gestion
$t=0; if($totalRows_liste_rec>0) { foreach($row_liste_rec as $row_liste_rec){  $code_ms=$row_liste_rec["id_recommandation"]; $cd=$row_liste_rec["id_recommandation"];
if(isset($prop_tab[$cd])){ if($prop_tab[$cd]<100) $encours++; else $execute++; } elseif(date("Y-m-d")>$row_liste_rec['date_buttoir'] && $row_liste_rec['type']!="Continu") $non_execute++; else $non_entame++;
 }
}
?>

<div class="col-md-4">
<div class="widget box ">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php  if(isset($row_edit_ms['type'])) echo $row_edit_ms['type']." du ".implode('/',array_reverse(explode('-',$row_edit_ms['debut']))); else echo "Situation de la derni&egrave;re mission de supervision"; ?></h4></div>

<div class="widget-content no-padding" style="display: block;">
<?php if(isset($id)) {?>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }
.left {text-align: left; } .center {text-align: center; } .right {text-align: right; }
.table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
</style>

<div align="center">
<u><b>Objet</b></u>&nbsp;&nbsp;:&nbsp;<?php if(isset($row_edit_ms['objet'])) echo (substr($row_edit_ms['objet'],0, 170)." ...");?>
</div>
<table class="table table-striped table-hover" id="mtable">
  <thead>
      <tr>
          <th>Statut</th>
          <th class="center">Nombre</th>
          <th class="center">Pourcentage (%)</th>
      </tr>
  </thead>
  <tbody>
      <tr>
          <td>Partiellement mise en oeuvre</td>
          <td class="center"><?php echo $encours;  ?></td>
<?php $bg="";
if(isset($totalRows_liste_rec) && $totalRows_liste_rec>0 && (($encours/$totalRows_liste_rec)*100)>=0 && (($encours/$totalRows_liste_rec)*100)<40) $bg= "color:red;";
elseif(isset($totalRows_liste_rec) && $totalRows_liste_rec>0 && (($encours/$totalRows_liste_rec)*100)>=40 && (($encours/$totalRows_liste_rec)*100)<70) $bg= "color:#FF9933;";
elseif(isset($totalRows_liste_rec) && $totalRows_liste_rec>0 && (($encours/$totalRows_liste_rec)*100)>70) $bg= "color:green;"; ?>
          <td class="center" style="<?php echo $bg; ?>"><?php echo (isset($totalRows_liste_rec) && $totalRows_liste_rec>0)?number_format(($encours/$totalRows_liste_rec)*100, 0, ',', ' ')."%":"0%";  ?></td>
      </tr>
      <tr>
          <td>Mise en oeuvre</td>
          <td class="center"><?php echo $execute;  ?></td>
<?php $bg="";
if(isset($totalRows_liste_rec) && $totalRows_liste_rec>0 && (($execute/$totalRows_liste_rec)*100)>=0 && (($execute/$totalRows_liste_rec)*100)<40) $bg= "color:red;";
elseif(isset($totalRows_liste_rec) && $totalRows_liste_rec>0 && (($execute/$totalRows_liste_rec)*100)>=40 && (($execute/$totalRows_liste_rec)*100)<70) $bg= "color:#FF9933;";
elseif(isset($totalRows_liste_rec) && $totalRows_liste_rec>0 && (($execute/$totalRows_liste_rec)*100)>70) $bg= "color:green;"; ?>
          <td class="center" style="<?php echo $bg; ?>"><?php echo (isset($totalRows_liste_rec) && $totalRows_liste_rec>0)?number_format(($execute/$totalRows_liste_rec)*100, 0, ',', ' ')."%":"0%";  ?></td>
      </tr>
      <tr>
          <td>Non ex&eacute;cut&eacute;</td>
          <td class="center"><?php echo $non_execute;  ?></td>
<?php $bg="";
if(isset($totalRows_liste_rec) && $totalRows_liste_rec>0 && (($non_execute/$totalRows_liste_rec)*100)>=0 && (($non_execute/$totalRows_liste_rec)*100)<40) $bg= "color:red;";
elseif(isset($totalRows_liste_rec) && $totalRows_liste_rec>0 && (($non_execute/$totalRows_liste_rec)*100)>=40 && (($non_execute/$totalRows_liste_rec)*100)<70) $bg= "color:#FF9933;";
elseif(isset($totalRows_liste_rec) && $totalRows_liste_rec>0 && (($non_execute/$totalRows_liste_rec)*100)>70) $bg= "color:green;"; ?>
          <td class="center" style="<?php echo $bg; ?>"><?php echo (isset($totalRows_liste_rec) && $totalRows_liste_rec>0)?number_format(($non_execute/$totalRows_liste_rec)*100, 0, ',', ' ')."%":"0%";  ?></td>
      </tr>
      <tr>
          <td>D&eacute;lai d'ex&eacute;cution non &eacute;chu</td>
          <td class="center"><?php echo $non_entame;  ?></td>
<?php $bg="";
if(isset($totalRows_liste_rec) && $totalRows_liste_rec>0 && (($non_entame/$totalRows_liste_rec)*100)>=0 && (($non_entame/$totalRows_liste_rec)*100)<40) $bg= "color:red;";
elseif(isset($totalRows_liste_rec) && $totalRows_liste_rec>0 && (($non_entame/$totalRows_liste_rec)*100)>=40 && (($non_entame/$totalRows_liste_rec)*100)<70) $bg= "color:#FF9933;";
elseif(isset($totalRows_liste_rec) && $totalRows_liste_rec>0 && (($non_entame/$totalRows_liste_rec)*100)>70) $bg= "color:green;"; ?>
          <td class="center" style="<?php echo $bg; ?>"><?php echo (isset($totalRows_liste_rec) && $totalRows_liste_rec>0)?number_format(($non_entame/$totalRows_liste_rec)*100, 0, ',', ' ')."%":"0%";  ?></td>
      </tr>
  </tbody>
  <thead>
      <tr>
          <th>Total</th>
          <th class="center"><?php echo (isset($totalRows_liste_rec) && $totalRows_liste_rec>0)?$totalRows_liste_rec:0;  ?></th>
          <th class="center"><?php echo (isset($totalRows_liste_rec) && $totalRows_liste_rec>0)?number_format(100, 0, ',', ' ')."%":"0%";  ?></th>
      </tr>
  </thead>
</table>
</div>
<?php }  ?>
</div>
</div>
<div class="col-md-4">
<div class="widget box ">
<div class="widget-header"> <h4><i class="icon-reorder"></i> PTBA consolidé</h4>
</div>
<div class="widget-content" id="second_tab">
<div class="tabbable tabbable-custom" >
  <ul class="nav nav-tabs" >
  
   <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>
   <li title="" class="<?php echo ($aversionP[0]==$versiona)?"active":""; ?>"><a href="#tab_feed2_<?php echo $aversionP[0]; ?>" data-toggle="tab"><?php echo $aversionP[2]." ".$aversionP[1]; ?></a></li>
              <?php } ?>
			  
  </ul>
  <div class="tab-content">
 
    <?php //for($j=$_SESSION["annee_debut_projet"];$j<=date("Y");$j++){ if($j<=$_SESSION["annee_fin_projet"]) {?>
    <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>
  <div class="tab-pane <?php echo ($aversionP[0]==$versiona)?"active":""; ?>" id="tab_feed2_<?php echo $aversionP[0]; ?>" data-target="./index_execution_ptba_consolide.php?annee=<?php echo $aversionP[0]; ?>&cmp=<?php echo $cmp; ?>&vers=<?php echo $aversionP[2]; ?>" >
  </div>
  <?php } ?>
  </div>
</div>
</div></div>
</div>

 
	
	<div class="col-md-4">

<?php  include("graph_mp_region_cercle.php"); ?>
</div>
 
<div class="clear h0">&nbsp;</div>
<div class="col-md-8">
<div class="widget-content" id="first_tab">
<div class="tabbable tabbable-custom" >
  <ul class="nav nav-tabs" >
  <?php for($j=$_SESSION["annee_debut_projet"];$j<=date("Y");$j++){ if($j<=$_SESSION["annee_fin_projet"]) {?>
    <li title="" class="<?php echo ($j==$annee)?"active":""; ?>"><a href="#tab_ant<?php echo $j; ?>" data-toggle="tab"><?php echo $j; ?></a></li>
  <?php }} ?>
  </ul>
  <div class="tab-content">
  <?php for($j=$_SESSION["annee_debut_projet"];$j<=date("Y");$j++){ if($j<=$_SESSION["annee_fin_projet"]) {?>
  <div class="tab-pane <?php echo ($j==$annee)?"active":""; ?>" id="tab_ant<?php echo $j; ?>" data-target="./index_execution_indicateur_antenne.php?annee=<?php echo $j; ?>" >
  </div>
  <?php }} ?>
  </div>
</div></div>
</div>
<div class="clear h0">&nbsp;</div>
 </div>
  </div>