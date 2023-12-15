<?php

   ///////////////////////////////////////////////

  /*                 SSE                       */

 /*	Conception & Développement: BAMASOFT */

///////////////////////////////////////////////



 // session_start();

  include_once 'system/configuration.php'; ini_set("display_errors",0);

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

$query_liste_indicateur_ref = "SELECT intitule_indicateur as intitule_ref_ind, id_indicateur as id_ref_ind, unite_cmr as unite, referentiel FROM  indicateur_produit_cmr where cle=1 and projet_prd='".$_SESSION["clp_projet"]."' ORDER BY  code_irprd";

try{

    $liste_indicateur_ref = $pdar_connexion->prepare($query_liste_indicateur_ref);

    $liste_indicateur_ref->execute();

    $row_liste_indicateur_ref = $liste_indicateur_ref ->fetchAll();

    $totalRows_liste_indicateur_ref = $liste_indicateur_ref->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_cible_indicateur = "SELECT indicateur_produit as referentiel, sum(valeur_cible) as valeur_cible, avg(valeur_cible) as valeur_ciblem  FROM   ".$database_connect_prefix."cible_cmr_produit, indicateur_produit_cmr where id_indicateur=indicateur_produit   and projet_prd='".$_SESSION["clp_projet"]."' group by referentiel";

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



$query_liste_version = "SELECT * FROM ".$database_connect_prefix."version_ptba where (version_ptba!='Initiale' or date_validation in (select max(date_validation) from version_ptba)) ORDER BY date_validation asc";

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



$query_liste_code_ref = "SELECT code_ref_ind, id_ref_ind, unite FROM referentiel_indicateur order by code_ref_ind";

try{

    $liste_code_ref = $pdar_connexion->prepare($query_liste_code_ref);

    $liste_code_ref->execute();

    $row_liste_code_ref = $liste_code_ref ->fetchAll();

    $totalRows_liste_code_ref = $liste_code_ref->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }

$liste_code_ref_array = array();

 if($totalRows_liste_code_ref>0) { foreach($row_liste_code_ref as $row_liste_code_ref){  

 $liste_code_ref_array[$row_liste_code_ref["id_ref_ind"]] = $row_liste_code_ref["unite"];

}}



  $query_real_indicateur = "SELECT indicateur_cr, annee, sum(valeur_suivi) as valeur_suivi FROM   suivi_indicateur_cmr WHERE projet='".$_SESSION['clp_projet']."' group by indicateur_cr";

    try{

    $real_indicateur = $pdar_connexion->prepare($query_real_indicateur);

    $real_indicateur->execute();

    $row_real_indicateur = $real_indicateur ->fetchAll();

    $totalRows_real_indicateur = $real_indicateur->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }

$liste_realind_array = array();

 if($totalRows_liste_code_ref>0) { foreach($row_real_indicateur as $row_real_indicateur){  

 $liste_realind_array[$row_real_indicateur["indicateur_cr"]] = $row_real_indicateur["valeur_suivi"];

}}



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

    $('.flexslider').flexslider({

    animation: "slide"

    });

});



</script>

<style>

.flex-direction-nav {

  visibility: hidden;

}

.slides li div .widget-content {

  max-height: 280px;

  overflow: auto;

}

.flex-control-nav {

    bottom: -58px;

}

.feeds li {

  /*background-color: #FFF;*/

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

.title_1 {

    display: block;

    font-size: 11px;

    padding: 3px;

}

.value {

    font-size: 15px;

    font-weight: 600;

    overflow: hidden;

}

#accueil .col-md-3, #accueil .col-md-6, #accueil .col-md-12 {

    padding-left: 5px!important;

    padding-right: 5px!important;

}

#mot_dg> h1:first-of-type, #mot_dg> h2:first-of-type, #mot_dg> h3:first-of-type {

  margin-top: 0;

}

.multiple {

    height: 300px;

    margin-top: 15px;

    position: relative;

}

.box-filter {

    width: 100%;

    font-size: 12px!important;

    min-height: 30px;

    margin-bottom: 0!important;

    -webkit-box-sizing: border-box;

    -moz-box-sizing: border-box;

    box-sizing: border-box;

}

.filter {

    position: absolute;

    right: 18px;

    top: 4px;

    font-size: 12px;

    background: 0;

    border: 0;

    color: gray;

}

</style>

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



<div style="padding-top:10px;">

<div class="col-md-6">

<div class="widget box ">

<div class="widget-content" style="padding: 0px;">

<div class="flexslider hide_befor_load" style="height: 240px; padding: 0px; border: 0px;">

  <ul class="slides">

<li>

<?php $_GET["chart_name"]=1; include("graph_total_op_region.php"); ?>

</li>

<li>

<?php $_GET["chart_name"]=2; include("graph_annee_type_demande.php"); ?>

</li>

<li>

<?php $_GET["chart_name"]=3; include("graph_demande_region_annee.php"); ?>

</li>

</ul>

  </div>

</div>



</div></div>



<div class="col-md-3">

<div class="widget box ">

<div class="widget-content" id="filiere_container_box">



<div id="filiere_container" style="max-width: 310px;  height: 280px;  margin: 0 auto"></div>

<script type="text/javascript">

//var text = '<?php echo $liste_filiere_array; ?>';

var text_value = '<?php echo $liste_filiere_id_array; ?>';

text_value = text_value.split(/[,\,]+/g).map(function(item) { return item.trim(); });

//var lines = text.split(/[,\,]+/g),

data = [<?php $data=""; foreach($liste_filiere_array as $a=>$b) $data .= "{ name: \"$b\", weight: ".((isset($liste_filiere_valeur_array[$a]) && intval($liste_filiere_valeur_array[$a])>0)?intval($liste_filiere_valeur_array[$a]):1)." },"; echo !empty($data)?substr($data,0,-1):$data; ?>];



data = [{ name: "Arachide", weight: 209702673 },{ name: "Maraîchage", weight: 528401661 },{ name: "Volaille", weight: 195335787 },{ name: "Matériel agricole", weight: 99303666 },{ name: "Mixte", weight: 20670760 },{ name: "Aquacole", weight: 17772420 },{ name: "Gingembre", weight: 14839000 },{ name: "Riz", weight: 91096270 },{ name: "Niébé", weight: 8744094 },{ name: "Manioc", weight: 6213350 },{ name: "Intrants agricoles", weight: 11382528 },{ name: "Plateforme multifonctionnelle", weight: 4459745159 },{ name: "Commerce de céréales", weight: 59745159 },{ name: "Patate douce", weight: 7539750 },{ name: "Mil", weight: 19902750 },{ name: "Bissap", weight: 2499700 },{ name: "Pastèque", weight: 475500 }];



Highcharts.seriesTypes.wordcloud.prototype.deriveFontSize = function(relativeWeight) {

    var maxFontSize =30;

    // Will return a fontSize between 0px and 15px.

    let size =  Math.floor(maxFontSize * relativeWeight);

    return size < 2 ? 10 : size < 5 ? 15: size < 10 ? 20:size < 20 ? 25:35;

	 //return if(size < 2) 10; else if(size < 10)  20; else if(size < 100)  30; else 100;

};

Highcharts.chart('filiere_container', {

 plotOptions: {

        series: {

            cursor: 'pointer',

            point: {

                events: {

                    click: function () {

                       var valeur = text_value[this.category]; var valeur_click = this.category;

                        $("#filiere_container_box_a").unbind('click');

                        $("#filiere_container_box_a").attr('title','Fili&egrave;re : '+this.name);

                        $("#filiere_container_box_a").on("click",function(){get_content('./content/resultat_filiere_accueil.php','id='+valeur+'&click='+valeur_click,'modal-body_add',this.title);});

                        $("#filiere_container_box_a").click();

                    }

                }

            }

        }

    },

	chart: {

    "type": "wordcloud",

    	margin: [-1, 0, -1, -1]

  },

    series: [{

        type: 'wordcloud',

		rotation: {

    	from: 0,

      to: 0

    },

        data: data,

        name: 'Coûts des projets'

    }],

   title: { text: '' },

   credits: {

			enabled: true,

			href: '#',

			text: 'RUCHE : <?php echo date("d/m/Y H:i"); ?>',

			style: {

			cursor: 'pointer',

			color: '#6633FF',

			fontSize: '10px',

			margin: '10px'

			}

		 },

   });

</script>

<a id="filiere_container_box_a" href="#myModal_add" data-backdrop="static" data-keyboard="false" data-toggle="modal" class="hidden" title="D&eacute;tails sur la fili&egrave;re" onclick=""></a>

<div class="clear h0">&nbsp;</div>

</div></div>

</div>

<div class="col-md-3">

<div class="widget box ">

<div class="widget-content" style="padding: 0px;">

<?php $_GET["chart_name"]=4; include("graph_mp_region_cercle.php"); ?>

<div class="clear h0">&nbsp;</div>

</div></div>

</div>



<div class="clear h0">&nbsp;</div>

<div class="col-md-6">

<div class="widget box ">

<div class="widget-header"> <h4><i class="icon-reorder"></i> R&eacute;alisations</h4>

</div>

<div id="madiv"> 

<table class="table table-striped table-hover" id="mtable">

  <thead>

    <tr>

      <th>Indicateur</th>

     <!--  <?php //foreach($tableauRegion as $vregion){?>  <th align="center" > <?php //$aregion = explode('<>',$vregion); $iregion = $aregion[0]; echo $aregion[1]; ?> </th> <?php //} ?>-->

      <th class="center">Total Pr&eacute;vu</th>

      <th class="center">Total R&eacute;alis&eacute;</th>

      <th class="center"> (%)</th>

    </tr>

  </thead>

  <?php if($totalRows_liste_indicateur_ref>0){$it=0; foreach($row_liste_indicateur_ref as $row_liste_indicateur_ref){ $tinf=""; $valind_tot=0; $valind=0;  $idreff=$row_liste_indicateur_ref["id_ref_ind"];



 ?>

  <tbody>

    <tr>

      <td><?php  echo $row_liste_indicateur_ref["intitule_ref_ind"]; if(isset($liste_code_ref_array[$row_liste_indicateur_ref["referentiel"]])) echo " <b><em>(".$liste_code_ref_array[$row_liste_indicateur_ref["referentiel"]].")</em></b>"; ?></td>

   

	  

      <td nowrap="nowrap" class="center"><div align="right">

        <?php if(isset($cible_array[$row_liste_indicateur_ref["id_ref_ind"]])) echo number_format($cible_array[$row_liste_indicateur_ref["id_ref_ind"]], 0, ',', ' '); ?>

      </div></td>

      <td class="center"><div align="right"><b style="color: #000; display:block;  background-color: #D2E2B1;"> 

	  

	   <a style="display: block;" href="./fiches_dynamiques.php?<?php $at="%25"; echo "cmp=$at&id=$nclass&feuille=$nfeuil&annee=$annee"; ?>" title="<?php echo $row_liste_indicateur_ref["intitule_ref_ind"]; ?>"><?php  if(isset($liste_realind_array[$row_liste_indicateur_ref["referentiel"]])) {echo number_format(floatval($liste_realind_array[$row_liste_indicateur_ref["referentiel"]]), 0, ',', ' '); $valind=$liste_realind_array[$row_liste_indicateur_ref["referentiel"]]; }?></a>





	  

	   </b></div></td>

      <td class="center"><div align="center">

        <b style="color: #000; display:block;  color:#CC3300;">

        <?php if(isset($cible_array[$row_liste_indicateur_ref["id_ref_ind"]]) && $cible_array[$row_liste_indicateur_ref["id_ref_ind"]]>0){ if($valind>0) echo number_format((floatval($valind)/floatval($cible_array[$row_liste_indicateur_ref["id_ref_ind"]]))*100, 0, ',', ' ')."%"; else echo "-"; } else echo "Pas de prevision"; $row_liste_val_ref["val"]=""; ?>

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

 <!-- <ul class="nav nav-tabs" >

  <?php //for($j=$_SESSION["annee_debut_projet"];$j<=date("Y");$j++){ if($j<=$_SESSION["annee_fin_projet"]) {?>

    <li title="" class="<?php //echo ($j==$annee)?"active":""; ?>"><a href="#tab_cp_<?php //echo $j; ?>" data-toggle="tab"><?php //echo $j; ?></a></li>

  <?php //}} ?>

  </ul>-->

  

     <ul class="nav nav-tabs" >

    <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>

   <li title="" class="<?php echo ($aversionP[0]==$versiona)?"active":""; ?>"><a href="#tab_cp_<?php echo $aversionP[0]; ?>" data-toggle="tab"><?php echo $aversionP[2]." ".$aversionP[1]; ?></a></li>

              <?php } ?>

  </ul>



 <!-- <div class="tab-content">

  <?php //for($j=$_SESSION["annee_debut_projet"];$j<=date("Y");$j++){ if($j<=$_SESSION["annee_fin_projet"]) {?>

  <div class="tab-pane <?php //echo ($j==$annee)?"active":""; ?>" id="tab_cp_<?php //echo $j; ?>" data-target="./index_execution_ptba_composante.php?annee=<?php //echo $j; ?>" >

  </div>

  <?php //}} ?>-->

  

   <div class="tab-content">

    <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>

  <div class="tab-pane <?php echo ($aversionP[0]==$versiona)?"active":""; ?>" id="tab_cp_<?php echo $aversionP[0]; ?>" data-target="./index_execution_ptba_composante.php?annee=<?php echo $aversionP[0]; ?>&cmp=<?php echo $cmp; ?>&vers=<?php echo $aversionP[2]; ?>" >

  </div>

  <?php } ?>

  </div>





</div></div>



<div class="clear h0">&nbsp;</div>



<?php  //and ".$database_connect_prefix."mission_supervision.projet='".$_SESSION["clp_projet"]."'

$query_edit_ms = "SELECT code_ms, id_mission, ".$database_connect_prefix."mission_supervision.type, debut, fin, objet FROM ".$database_connect_prefix."mission_supervision, ".$database_connect_prefix."recommandation_mission where id_mission=mission  order by debut desc limit 1";

try{

    $edit_ms = $pdar_connexion->prepare($query_edit_ms);

    $edit_ms->execute();

    $row_edit_ms = $edit_ms ->fetch();

    $totalRows_edit_ms = $edit_ms->rowCount();

}catch(Exception $e){ /*die(mysql_error_show_message($e));*/ }



if(isset($row_edit_ms['id_mission'])) $id=$row_edit_ms['id_mission']; else $id=0;

$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."recommandation_mission, ".$database_connect_prefix."rubrique_projet where mission='$id'  and rubrique=code_rub ORDER BY code_rub asc, numero asc";

try{

    $liste_rec = $pdar_connexion->prepare($query_liste_rec);

    $liste_rec->execute();

    $row_liste_rec = $liste_rec ->fetchAll();

    $totalRows_liste_rec = $liste_rec->rowCount();

}catch(Exception $e){ /*die(mysql_error_show_message($e));*/ }

$non_execute = $non_entame = $encours = $execute = 0;

 // $query_suivi_plan_ms = "SELECT sum(proportion) as texrecms, code_rec  FROM ".$database_connect_prefix."mission_plan where  valider=1 group by code_rec";

   $query_suivi_plan_ms = "SELECT sum(proportion) as texrecms, code_rec  FROM ".$database_connect_prefix."mission_plan, ".$database_connect_prefix."recommandation_mission  where code_rec=id_recommandation and mission='$id' and valider=1 group by code_rec";

try{

    $suivi_plan_ms = $pdar_connexion->prepare($query_suivi_plan_ms);

    $suivi_plan_ms->execute();

    $row_suivi_plan_ms = $suivi_plan_ms ->fetchAll();

    $totalRows_suivi_plan_ms = $suivi_plan_ms->rowCount();

}catch(Exception $e){ /*die(mysql_error_show_message($e));*/ }

  $prop_tab = array();

  if($totalRows_suivi_plan_ms>0){ foreach($row_suivi_plan_ms as $row_suivi_plan_ms){

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

<div class="widget-content" id="second_tab">

<div class="tabbable tabbable-custom" >

  <ul class="nav nav-tabs" >

   <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>

   <li title="" class="<?php echo ($aversionP[0]==$versiona)?"active":""; ?>"><a href="#tab_global_<?php echo $aversionP[0]; ?>" data-toggle="tab"><?php echo $aversionP[2]." ".$aversionP[1]; ?></a></li>

              <?php } ?>

  </ul>

  <div class="tab-content">

    <?php //for($j=$_SESSION["annee_debut_projet"];$j<=date("Y");$j++){ if($j<=$_SESSION["annee_fin_projet"]) {?>

    <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>

  <div class="tab-pane <?php echo ($aversionP[0]==$versiona)?"active":""; ?>" id="tab_global_<?php echo $aversionP[0]; ?>" data-target="./index_execution_ptba_consolide.php?annee=<?php echo $aversionP[0]; ?>&cmp=<?php echo $cmp; ?>&vers=<?php echo $aversionP[2]; ?>" >

  </div>

  <?php } ?>

  </div>

</div></div>

</div>



 

	

	<div class="col-md-4">



<?php  include("graph_mp_region_cercle.php"); ?>

</div>

 

<div class="clear h0">&nbsp;</div>



  </div>