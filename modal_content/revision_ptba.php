<?php

///////////////////////////////////////////////

/*                 SSE                       */

/*	Conception & Développement: SEYA SERVICES */

///////////////////////////////////////////////

session_start();

$path = '../';

include_once $path.'system/configuration.php';

$config = new Config;



if (!isset ($_SESSION["clp_id"])) {

  //header(sprintf("Location: %s", "./"));

  exit;

}

include_once $path.$config->sys_folder . "/database/db_connexion.php";

//header('Content-Type: text/html; charset=ISO-8859-15');



if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=0;

if(isset($_GET['autrever'])) {$autrever=$_GET['autrever'];} else $autrever=0;

if(isset($_GET["actr"]) && $_GET["actr"]=="add") { $actr=$_GET["actr"];  $textactr=$_GET["actr"]="Valider"; }
elseif(isset($_GET["actr"]) && $_GET["actr"]=="sup") { $actr=$_GET["actr"];  $textactr=$_GET["actr"]="Supprimer de la version révisée"; }
//echo $id_cp;

$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {

  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);

}
/*$annee=2033;
$autrever=2035;*/



$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];


 $ugl_projet = str_replace("|",",",$_SESSION["clp_projet_ugl"]);//implode(",",(explode("|", $_SESSION["clp_projet_ugl"]));

if(isset($annee))

{
 // $query_liste_region_concerne= "SELECT code_ugl, nom_ugl FROM ".$database_connect_prefix."ugl where FIND_IN_SET( code_ugl, '".$ugl_projet."' )";
 /* $query_liste_region_concerne = "SELECT * FROM ".$database_connect_prefix."ptba where annee='$annee' and projet='".$_SESSION["clp_projet"]."' and intitule_activite_ptba not in (select intitule_activite_ptba from ptba where annee='$autrever ' and projet='".$_SESSION["clp_projet"]."') order by code_activite_ptba";
     try{
  $liste_region_concerne = $pdar_connexion->prepare($query_liste_region_concerne);
    $liste_region_concerne->execute();
    $row_liste_region_concerne = $liste_region_concerne ->fetchAll();
    $totalRows_liste_region_concerne = $liste_region_concerne->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }*/
    

 // $query_liste_region_concerne= "SELECT code_ugl, nom_ugl FROM ".$database_connect_prefix."ugl where FIND_IN_SET( code_ugl, '".$ugl_projet."' )";
 // $query_liste_activite = "SELECT * FROM ".$database_connect_prefix."ptba where annee='$annee' and projet='".$_SESSION["clp_projet"]."' and intitule_activite_ptba not in (select intitule_activite_ptba from ptba where annee='$autrever' and projet='".$_SESSION["clp_projet"]."') order by code_activite_ptba"; 
    $query_liste_activite = "SELECT * FROM ".$database_connect_prefix."ptba where annee=1 and projet='".$_SESSION["clp_projet"]."' order by code_activite_ptba"; 
       try{
  $liste_activite = $pdar_connexion->prepare($query_liste_activite);
    $liste_activite->execute();
    $row_liste_activite = $liste_activite ->fetchAll();
    $totalRows_liste_activite = $liste_activite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  
}

 
  
 // echo  $query_liste_activite;   exit;

/*$activite_array = array();

if($totalRows_liste_activite>0){ do{

  $activite_array[$row_liste_activite["code"]] = $row_liste_activite["intitule"];

}while($row_liste_activite = mysql_fetch_assoc($liste_activite));

    $rows = mysql_num_rows($liste_activite);

    if($rows > 0) {

        mysql_data_seek($liste_activite, 0);

  	  $row_liste_activite = mysql_fetch_assoc($liste_activite);

    }

}*/



//$tableauMois= array('T1','T2','T3','T4');
//$tableauMois= array('J','F','M','A','M','J','J','A','S','O','N','D');
$tableauMois=array('01<>Jan<>J','02<>Fev<>F','03<>Mars<>M','04<>Avril<>A','05<>Mai<>M','06<>Juin<>J','07<>Juil<>J','08<>Aout<>A','09<>Sep<>S','10<>Oct<>O','11<>Nov<>N','12<>DEC<>D');


 //foreach($regionconcerne_array as $brc) { echo $brc; }

 //$brc=explode("|",$regionconcerne_array);

 //explode("|", $regionconcerne_array)
//if(isset( $totalRows_liste_activite) &&  $totalRows_liste_activite>0) echo $row_liste_activite['version_ptba'];
//exit;

$query_liste_version = "SELECT * FROM ".$database_connect_prefix."version_ptba  ORDER BY annee_ptba desc, date_validation desc";
           try{
    $liste_version = $pdar_connexion->prepare($query_liste_version);
    $liste_version->execute();
    $row_liste_version = $liste_version ->fetchAll();
    $totalRows_liste_version = $liste_version->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$TableauVersionP = array(); $version_array = array();
if($totalRows_liste_version>0){foreach($row_liste_version as $row_liste_version){ 
$max_version=$row_liste_version["id_version_ptba"];
$TableauVersionP[]=$row_liste_version["id_version_ptba"]."<>".$row_liste_version["version_ptba"]."<>".$row_liste_version["annee_ptba"];
$version_array[$row_liste_version["id_version_ptba"]] = $row_liste_version["annee_ptba"]." ".$row_liste_version["version_ptba"];
 } }
 
   $query_liste_urgp= "SELECT * FROM ".$database_connect_prefix."ugl order by code_ugl";

	   try{
    $liste_urgp = $pdar_connexion->prepare($query_liste_urgp);
    $liste_urgp->execute();
    $row_liste_urgp = $liste_urgp ->fetchAll();
    $totalRows_liste_urgp = $liste_urgp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableauRegion = array(); 
 if($totalRows_liste_urgp>0) { foreach($row_liste_urgp as $row_liste_urgp){  
	 $tableauRegion[$row_liste_urgp['code_ugl']]=$row_liste_urgp['nom_ugl'];
 } }
?>

<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>
  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/breakpoints.js"></script>
  <script type="text/javascript" src="plugins/cookie/jquery.cookie.min.js"></script>
  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.min.js"></script>
  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>
  <script type="text/javascript" src="plugins/blockui/jquery.blockUI.min.js"></script>

  <script type="text/javascript" src="plugins/select2/select2.min.js"></script>

 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>
<script>

function show_tab(tab) {
    if (!tab.html()) {
        tab.load(tab.attr('data-target'));
    }
}

function init_tabs() {
    var a = $('.tab-pane.active');
    a.each(function(){ show_tab($(this)); })
    $('a[data-toggle="tab"]').click('show', function(e) {
        tab = $('#' + $(e.target).attr('href').substr(1));
        show_tab(tab);
    });
}

$(function() {
    init_tabs();
});
	$().ready(function() {

		// validate the comment form when it is submitted

		$(".form-horizontal").validate();

        $(".modal-dialog", window.parent.document).width(700);

        $(".select2-select-00").select2({allowClear:true});

	});

!function(e){function t(t){var o=e("#"+t.view+" option").size(),r=e("#"+t.storage+" option").size();e("#"+t.counter).text("Affichage de "+o+"/"+(o+r))}function o(o){var i,a=o.index;i=n[a].useFilters?e("#"+o.filter).val().toString().toLowerCase():"",e("#"+o.view+" option").filter(function(t){var o=e(this).text().toString().toLowerCase();return-1==o.indexOf(i)}).appendTo("#"+o.storage),e("#"+o.storage+" option").filter(function(t){var o=e(this).text().toString().toLowerCase();return-1!=o.indexOf(i)}).appendTo("#"+o.view);try{e("#"+o.view+" option").removeAttr("selected")}catch(u){}n[a].useSorting&&r(o),n[a].useCounters&&t(o)}function r(t){var o=e("#"+t.view+" option");o.sort(l[t.index]),e("#"+t.view).empty().append(o)}function i(o){e("#"+o.filter).val(""),e("#"+o.storage+" option").appendTo("#"+o.view);try{e("#"+o.view+" option").removeAttr("selected")}catch(i){}n[o.index].useSorting&&r(o),n[o.index].useCounters&&t(o)}var n=new Array,a=new Array,u=new Array,s=new Array,l=new Array;e.configureBoxes=function(c){var x=n.push({box1View:"sous_secteur",box1Storage:"sous_secteurStorage",box1Filter:"sous_secteurFilter",box1Clear:"sous_secteurClear",box1Counter:"sous_secteurCounter",box2View:"domaine",box2Storage:"domaineStorage",box2Filter:"domaineFilter",box2Clear:"domaineClear",box2Counter:"domaineCounter",box3View:"indicateur",box3Storage:"indicateurStorage",box3Filter:"indicateurFilter",box3Clear:"indicateurClear",box3Counter:"indicateurCounter",to1:"to1",allTo1:"allTo1",to2:"to2",allTo2:"allTo2",transferMode:"move",sortBy:"text",useFilters:!0,useCounters:!0,useSorting:!0,selectOnSubmit:!0});x--,e.extend(n[x],c),a.push({view:n[x].box1View,storage:n[x].box1Storage,filter:n[x].box1Filter,clear:n[x].box1Clear,counter:n[x].box1Counter,index:x}),u.push({view:n[x].box2View,storage:n[x].box2Storage,filter:n[x].box2Filter,clear:n[x].box2Clear,counter:n[x].box2Counter,index:x}),s.push({view:n[x].box3View,storage:n[x].box3Storage,filter:n[x].box3Filter,clear:n[x].box3Clear,counter:n[x].box3Counter,index:x}),"text"==n[x].sortBy?l.push(function(e,t){var o=e.text.toLowerCase(),r=t.text.toLowerCase();return r>o?-1:o>r?1:0}):l.push(function(e,t){var o=e.value.toLowerCase(),r=t.value.toLowerCase();return r>o?-1:o>r?1:0}),n[x].useFilters&&(e("#"+a[x].filter).keyup(function(){o(a[x])}),e("#"+u[x].filter).keyup(function(){o(u[x])}),e("#"+s[x].filter).keyup(function(){o(s[x])}),e("#"+a[x].clear).click(function(){i(a[x])}),e("#"+u[x].clear).click(function(){i(u[x])}),e("#"+s[x].clear).click(function(){i(s[x])})),n[x].useCounters&&(t(a[x]),t(u[x]),t(s[x])),n[x].useSorting&&(r(a[x]),r(u[x]),r(s[x])),e("#"+a[x].storage+",#"+u[x].storage+",#"+s[x].storage).css("display","none"),n[x].selectOnSubmit&&e("#"+n[x].box2View).closest("form").submit(function(){e("#"+n[x].box2View).children("option").attr("selected","selected")})}}(jQuery);
</script>



<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id_act"]) && intval($_GET["id_act"])>0)?"Transfert":"Transfert"; echo " activit&eacute; de PTBA ".$autrever; ?></h4> </div>

<div class="widget-content">

<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form1rev" id="form1rev" novalidate="novalidate">

<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
   
    <tr valign="top">
      <td colspan="2"> <div class="form-group">
        
          <div class="col-md-12">
            <input type="text" id="sous_secteurFilter" class="form-control box-filter" placeholder="Recherche..."><button type="button" id="sous_secteurClear" class="filter">&nbsp;</button>
            <select name="sous_secteur[]"  size="6" id="sous_secteur" class="form-control required multiple" onchange="get_content('menu_domaine.php','id='+this.value+'&select=1','domaine','','','',1);"  multiple>
			   
      <?php foreach($row_liste_activite as $row_liste_activite){   ?>
              <option title="<?php echo $row_liste_activite["intitule_activite_ptba"]; ?>" value="<?php echo $row_liste_activite['id_ptba']; ?>"><?php echo (strlen($row_liste_activite["intitule_activite_ptba"])>80)?$row_liste_activite["code_activite_ptba"].": ".substr($row_liste_activite["intitule_activite_ptba"],0,80)."...":" <b>".$row_liste_activite['code_activite_ptba']." </b>: ".$row_liste_activite['intitule_activite_ptba'];   if(isset($tableauRegion[$row_liste_activite['region']])) echo " (".$tableauRegion[$row_liste_activite['region']].")"; ?></option>
			   <?php }   ?>
            </select>
            <span id="sous_secteurCounter" class="count-label"></span>
            <select id="sous_secteurStorage" class="hidden"></select>
          </div>
        </div></td>
    </tr>
    <tr valign="top">
      <td width="5%">De&nbsp;&nbsp;</td>
      <td><select name="anneeini" id="anneeini" class="full-width-fix select2-select-00 required">
<option value="<?php echo $annee; ?>"><?php if(isset($version_array[$annee])) echo $version_array[$annee];?></option>
</select></td>
    </tr>
    <tr valign="top">
      <td>Vers &nbsp;&nbsp;</td>
      <td><select name="anneerev" id="anneerev" class="full-width-fix select2-select-00 required">
<option value=" ">Selectionnez</option>
 <?php foreach($TableauVersionP as $vversionP){ $aversionP = explode('<>',$vversionP); ?>
<option value="<?php echo $aversionP[0]; ?>"><?php echo  $aversionP[2]." ".$aversionP[1];?></option>
              <?php }  ?>
</select></td>
    </tr>
</table>

<div class="form-actions">

<input name="annee" id="annee" type="hidden" value="<?php echo intval($annee); ?>" size="32" alt="">
<input name="autrever" id="autrever" type="hidden" value="<?php echo intval($autrever); ?>" size="32" alt="">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php echo $textactr; ?>" />
  <input name="<?php if(isset($_GET["id_act"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id_act"])) echo $_GET["id_act"]; else echo "MM_insert" ; ?>" size="32" alt="">
<input name="MM_form" id="MM_form" type="hidden" value="form1rev" size="32" alt="">
</div>

</form>

</div>
</div>