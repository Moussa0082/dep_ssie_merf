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

header('Content-Type: text/html; charset=UTF-8');

$dir = './attachment/supervision/';

$plog=$_SESSION["clp_id"];

$date=date("Y-m-d");

if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else $annee=date("Y");

$id_ms = 0;

if(isset($_GET['id_ms']) && intval($_GET['id_ms'])>0) $id_ms = intval($_GET['id_ms']);



// Verrou PTBA





 /*
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_activite_1 = "SELECT code,intitule FROM activite_projet WHERE niveau=$cmp+1 and ".$_SESSION["clp_where"]." ";
  $liste_activite_1  = mysql_query_ruche($query_liste_activite_1 , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_activite_1  = mysql_fetch_assoc($liste_activite_1 );
  $totalRows_liste_activite_1  = mysql_num_rows($liste_activite_1 );
  $cmp_array = array();
  if($totalRows_liste_activite_1>0){  do{
    $cmp_array[$row_liste_activite_1["code"]] = $row_liste_activite_1["intitule"];
  }while($row_liste_activite_1 = mysql_fetch_assoc($liste_activite_1));  }*/




$tableauMois= array('T1','T2','T3','T4');
$tableauMois= array('Jan','Fev','Mar','Avr','Mai','Juin','Juil','Aout','Sep','Oct','Nov','Dec');
$tableauMois2= array('J','F','M','A','M','J','J','A','S','O','N','D');

$query_liste_ms = "SELECT * FROM ".$database_connect_prefix."mission_supervision WHERE year(debut)='$annee'  order by code_ms desc";
 try{
    $liste_ms = $pdar_connexion->prepare($query_liste_ms);
    $liste_ms->execute();
    $row_liste_ms = $liste_ms ->fetchAll();
    $totalRows_liste_ms = $liste_ms->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_code_ref = "SELECT * FROM ".$database_connect_prefix."rubrique_projet order by code_rub";
 try{
    $liste_code_ref = $pdar_connexion->prepare($query_liste_code_ref);
    $liste_code_ref->execute();
    $row_liste_code_ref = $liste_code_ref ->fetchAll();
    $totalRows_liste_code_ref = $liste_code_ref->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_rub_array = array();
$code_rub_array = array();
if($totalRows_liste_code_ref>0){  foreach($row_liste_code_ref as $row_liste_code_ref){  
  $liste_rub_array[$row_liste_code_ref["code_rub"]] = /*$row_liste_code_ref["code_rub"].": ".*/$row_liste_code_ref["nom_rubrique"];
  $code_rub_array[$row_liste_code_ref["code_rub"]] = $row_liste_code_ref["code_rub"];
}}

	
$query_liste_ugl = "SELECT * FROM ".$database_connect_prefix."ugl";
 try{
    $liste_ugl = $pdar_connexion->prepare($query_liste_ugl);
    $liste_ugl->execute();
    $row_liste_ugl = $liste_ugl ->fetchAll();
    $totalRows_liste_ugl = $liste_ugl->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$ugl_respo=array();
if($totalRows_liste_ugl>0){  foreach($row_liste_ugl as $row_liste_ugl){  
$ugl_respo[$row_liste_ugl["code_ugl"]]=$row_liste_ugl["abrege_ugl"];  
}}


$query_liste_respo_ugl = "SELECT id_personnel, fonction FROM ".$database_connect_prefix."personnel";
 try{
    $liste_respo_ugl = $pdar_connexion->prepare($query_liste_respo_ugl);
    $liste_respo_ugl->execute();
    $row_liste_respo_ugl = $liste_respo_ugl ->fetchAll();
    $totalRows_liste_respo_ugl = $liste_respo_ugl->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$respo_ugl=array();
if($totalRows_liste_respo_ugl>0){  foreach($row_liste_respo_ugl as $row_liste_respo_ugl){  
 $respo_ugl[$row_liste_respo_ugl["id_personnel"]]=$row_liste_respo_ugl["fonction"]; 
}}

$query_act = "SELECT * FROM mission_supervision, ".$database_connect_prefix."recommandation_mission where mission=id_mission and mission=$id_ms and year(debut)='$annee' order by ref_no";
 try{
    $act = $pdar_connexion->prepare($query_act);
    $act->execute();
    $row_act = $act ->fetchAll();
    $totalRows_act = $act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


$query_progess = "SELECT sum(proportion) as taux , code_rec FROM ".$database_connect_prefix."mission_plan where valider=1 group by code_rec";
 try{
    $progess = $pdar_connexion->prepare($query_progess);
    $progess->execute();
    $row_progess = $progess ->fetchAll();
    $totalRows_progess = $progess->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$prop_tab = array();
if($totalRows_progess>0){  foreach($row_progess as $row_progess){  
$prop_tab[$row_progess["code_rec"]] = $row_progess["taux"]; }
}


$query_progess = "SELECT count(id_plan) as nb, sum(proportion) as proportion_p , sum(if(valider=1, proportion,0)) as taux , code_rec FROM ".$database_connect_prefix."mission_plan  group by code_rec";
 try{
    $progess = $pdar_connexion->prepare($query_progess);
    $progess->execute();
    $row_progess = $progess ->fetchAll();
    $totalRows_progess = $progess->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$prop_tab = $proportion_tab = array(); 
if($totalRows_progess>0){  foreach($row_progess as $row_progess){  
$prop_tab[$row_progess["code_rec"]] = $row_progess["taux"];
$proportion_tab[$row_progess["code_rec"]] = $row_progess["proportion_p"];
//$prop_tab[$row_progess["code_rec"]] = $row_progess["taux"];
 }
}
?>

<!-- Site contenu ici -->

<style>

#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {

  border-spacing: 0px !important; border-collapse: collapse; width: 100%!important;

} .table tbody tr td {vertical-align: middle; }

.Style1 {font-size: 12px}

.Style2 {font-size: 11px}

.firstcapitalize:first-letter {  text-transform: capitalize;
}
</style>

<script>

$('#myModal_add').remove();

$().ready(function() {

//$('a[data-toggle="modal"]').modal();

var oTable = $('#mtable<?php echo $annee; ?>').dataTable( {

        "aoColumnDefs": [

            { "bSortable": false, "aTargets": [ -1 ] }

        ],

       // sDom:"<'row'<'dataTables_header clearfix'<'col-md-7'lT><'col-md-5'Cf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",

       // oTableTools:{aButtons:["copy","print","csv","xls",{"sExtends": "pdf","sPdfOrientation": "landscape"}],sSwfPath:"./swf/copy_csv_xls_pdf.swf"},

        "aaSorting": [],

        //"aLengthMenu":[[25, 50, 100, 200, -1],[25, 50, 100, 200, "TOUS1"]],

        "iDisplayLength": -1,

        paging: false

    });

});

</script>



<form name="form<?php echo $annee; ?>" id="form<?php echo $annee; ?>" method="get" action="<?php echo "gestion_mission_supervision.php?annee=".$annee; ?>" class="pull-left">

 <select name="id_ms" onchange="form<?php echo $annee; ?>.submit();" style="background-color: #FFFF00; padding: 7px;" class="btn p11">

            <option value="">-- Choisissez une mission --</option>
            <?php
				  if($totalRows_liste_ms>0) {
foreach($row_liste_ms as $row_liste_ms1){  
?>
            <option <?php if(isset($id_ms) && $id_ms==$row_liste_ms1['id_mission']) {echo 'SELECTED="selected"';  $nom=$row_liste_ms1['objet'];}  ?> value="<?php echo  $row_liste_ms1['id_mission']; ?>">
            <?php echo "<b>".$row_liste_ms1['code_ms'].":</b> "; if(isset($row_liste_ms1['type'])) echo $row_liste_ms1['type']." / du ".implode('-',array_reverse(explode('-',$row_liste_ms1['debut'])))." au ".implode('-',array_reverse(explode('-',$row_liste_ms1['fin']))); else echo "du ".implode('-',array_reverse(explode('-',$row_liste_ms1['debut'])))." au ".implode('-',array_reverse(explode('-',$row_liste_ms1['fin'])));?>
            </option>
            <?php
} }
?>
  </select>
  <input type="hidden" name="annee" value="<?php echo $annee; ?>" />

</form>
<div align="right"> <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1 && isset($nom)){ ?>
<?php echo do_link("","","$nom","<i class=\"icon-plus\"> Ajouter une recommandation </i>","","./","pull-right p11","get_content('edit_recommandation.php','mission=$id_ms&annee=$annee','modal-body_add',this.title);",1,"",$nfile); ?> <?php } ?> </div>
<a onclick="get_content('modal_content/graphique_mission_supervision.php','<?php echo "&annee=".$annee."&id_ms=".$id_ms.""; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Graphique par Conventions" class="thickbox Add pull-right p11"  dir=""><img src="images/b_chart.png" width="16" height="16" />Statistiques</a>



<div class="clear">&nbsp;</div>

<table class="table table-striped table-bordered table-hover table-responsive table-tabletools table-colvis datatable dataTable" id="mtable<?php echo $annee; ?>" aria-describedby="DataTables_Table_0_info">
  <thead>
    <tr role="row">
      <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">N&deg;</div></th>
      <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Domaine</div></th>
      <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">R&eacute;f.</div></th>
      <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Recommandations</div></th>
      <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Date buttoir</div></th>
      <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Responsables </div></th>
      <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Plan d' actions </th>
	  
      <!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Rapport</div></th>-->
      <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Statut</div></th>
      <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Observations</div></th>
      <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
      <th class="" role="" tabindex="0" aria-controls="" aria-label="" width="80">Actions</th>
      <?php } ?>
    </tr>
  </thead>
  <tbody role="alert" aria-live="polite" aria-relevant="all" class="">

    <?php $i=0; if(isset($totalRows_act) && $totalRows_act>0) { $r1="j"; foreach($row_act as $row_act){ $id=$row_act["id_recommandation"];   ?>
   <tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
      <td><div align="left" title=""> <?php echo (isset($row_act["ref_no"]) && $row_act["ref_no"]>0)?$row_act["ref_no"]:$i+1;  ?></div></td>
      <td><div align="left" style="font-size:11px" title="<?php if(isset($liste_rub_array[$row_act["rubrique"]]))  echo $liste_rub_array[$row_act['rubrique']]; ?>">
        <?php if(isset($liste_rub_array[$row_act["rubrique"]]))  echo $liste_rub_array[$row_act['rubrique']];  ?>
      </div></td>
      <td valign="middle"><div align="center"><strong><?php echo $row_act['numero']; ?></strong></div></td>
      <td class="Style4"><div align="left" class="Style4"><?php echo $row_act['recommandation']; ?></div></td>
      <td><div align="center"><span class="Style4">
        <?php if(isset($row_act['type']) && $row_act['type']=="Continu") echo "Continu"; else echo date_reg($row_act['date_buttoir'],"/");  ?>
      </span></div></td>
      <td><div align="left" title="<?php if(isset($respo_ugl[$row_act["responsable_interne"]])) echo $respo_ugl[$row_act["responsable_interne"]]; ?>">
        <?php  if(isset($ugl_respo[$row_act["volet_recommandation"]])) echo $ugl_respo[$row_act["volet_recommandation"]]; ?>
        (
        <?php if(isset($respo_ugl[$row_act["responsable_interne"]])) echo $respo_ugl[$row_act["responsable_interne"]]; ?>
        /
        <?php if(isset($row_act['responsable'])) echo $row_act['responsable']; ?>
        )</div></td>
      <td>&nbsp;<a onclick="get_content('./plan_mission_supervision.php','<?php echo "rec=".$row_act['id_recommandation']."&idms=$id_ms&annee=$annee"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add"  title="Plan d'action de suivi de reconmmandation" class="thickbox" dir="">T&acirc;ches</a><a onclick="get_content('./plan_mission_supervision.php','<?php echo "rec=".$row_act['id_recommandation']."&idms=$id_ms&annee=$annee"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add"  title="Plan d'action de suivi de reconmmandation" class="thickbox" dir="">
        <?php if(isset($proportion_tab[$id])) echo " (".$proportion_tab[$id]."%)" ?>
      </a></td>
    
      <td valign="middle" nowrap="nowrap"><?php
$color = "red";
$tauxp=0;

 if(isset($prop_tab[$id]))
 { $tauxp=$prop_tab[$id];
 if($tauxp<100) $color = "#FFD700";
 elseif($tauxp>=100) $color = "green";
    } //else{  $color = "#FFD700"; }//$prop_tab[$id] = 0;
 ?>
          <div> <a id="recommandation_<?php echo $row_act['id_recommandation']; ?>" style="display: block; border: solid 1px; background-color: #E8E8E8" onclick="get_content('suivi_plan_mission_supervision.php','<?php echo "rec=".$row_act['id_recommandation']."&idms=$id_ms&annee=$annee"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" title="Suivre la recommandation" class="thickbox Add"  dir=""><span id="stat_<?php echo $annee.$row_act['id_recommandation'];  ?>" >
            <div style="width: <?php if(isset($prop_tab[$id])) echo $prop_tab[$id]; ?>%; background-color: <?php echo $color; ?>; color:#FFFFFF;">
              <?php if(isset($prop_tab[$id])) echo $prop_tab[$id]." %"; elseif(date("Y-m-d")>$row_act['date_buttoir'] && $row_act['type']!="Continu") echo "Non entam&eacute;e"; else echo "Non &eacute;chu"; ?>
            </div>
          </span></a> </div></td>
      <td><div align="left" style="font-size:11px" > <?php echo $row_act['observation'];  ?></div></td>
      <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
      <td align="center" nowrap="nowrap" class=" "><?php
echo do_link("","",$nom,"","edit","./","","get_content('edit_recommandation.php','id=$id&mission=$id_ms&annee=$annee','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("","gestion_mission_supervision.php?id_sup=$id&id_ms=$id_ms&annee=$annee","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette recommendation ?');",0,"margin:0px 5px;",$nfile);
?></td>
      <?php } ?>
    </tr>
    <?php $i++; } } ?>
  </tbody>
</table>
<?php include 'modal_add.php'; ?>