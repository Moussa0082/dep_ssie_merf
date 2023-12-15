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







if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else $annee=date("Y");



$dir = './attachment/mission_atelier/';







//fonction calcul nb jour



function NbJours($debut, $fin) {



  $tDeb = explode("-", $debut);



  $tFin = explode("-", $fin);



  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);



  return(($diff / 86400)+1);



}



$query_liste_respo_ugl = "SELECT * FROM ".$database_connect_prefix."ugl  order by code_ugl asc";
                 try{
    $liste_respo_ugl = $pdar_connexion->prepare($query_liste_respo_ugl);
    $liste_respo_ugl->execute();
    $row_liste_respo_ugl = $liste_respo_ugl ->fetchAll();
    $totalRows_liste_respo_ugl = $liste_respo_ugl->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$respo_ugl=array();
if($totalRows_liste_respo_ugl>0){ foreach($row_liste_respo_ugl as $row_liste_respo_ugl){  $respo_ugl[$row_liste_respo_ugl["id_ugl"]]=$row_liste_respo_ugl["nom_ugl"];  }  } 



$query_act = "SELECT * FROM ateliers where projet='".$_SESSION["clp_projet"]."' and YEAR(debut)='$annee' order by id_atelier desc ";
                 try{
    $act = $pdar_connexion->prepare($query_act);
    $act->execute();
    $row_act = $act ->fetchAll();
    $totalRows_act = $act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


//liste village

$query_liste_village = "SELECT code_commune,nom_commune FROM commune  order by code_commune asc";
                 try{
    $liste_village = $pdar_connexion->prepare($query_liste_village);
    $liste_village->execute();
    $row_liste_village = $liste_village ->fetchAll();
    $totalRows_liste_village = $liste_village->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$village_array = array();
if($totalRows_liste_village>0){  foreach($row_liste_village as $row_liste_village){ 
  $village_array[$row_liste_village["nom_commune"]] = $row_liste_village["nom_commune"];

}  }





$query_liste_activite_1 = "SELECT code_activite_ptba,intitule_activite_ptba FROM ptba WHERE  projet='".$_SESSION["clp_projet"]."' and annee='$annee'";
                 try{
    $liste_activite_1 = $pdar_connexion->prepare($query_liste_activite_1);
    $liste_activite_1->execute();
    $row_liste_activite_1 = $liste_activite_1 ->fetchAll();
    $totalRows_liste_activite_1 = $liste_activite_1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$cmp_array = array();

if($totalRows_liste_activite_1>0){   foreach($row_liste_activite_1 as $row_liste_activite_1){ 

  $cmp_array[$row_liste_activite_1["code_activite_ptba"]] = $row_liste_activite_1["intitule_activite_ptba"];

} }



?>



<!-- Site contenu ici -->



<style>

#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {



  border-spacing: 0px !important; border-collapse: collapse; width: 100%!important;



} .table tbody tr td {vertical-align: middle; }



</style>



 <style>



.firstcapitalize:first-letter{



  text-transform: capitalize;



}



 </style>



<script>



$('#myModal_add').remove();



$().ready(function() {



$(".bs-popover").popover();

//$('a[data-toggle="modal"]').modal();



var oTable = $('#mtable<?php echo $annee; ?>').dataTable( {



        "aoColumnDefs": [



            { "bSortable": false, "aTargets": [ -1 ] }



        ],



        //sDom:"<'row'<'dataTables_header clearfix'<'col-md-7'lT><'col-md-5'Cf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",



       // oTableTools:{aButtons:["copy","print","csv","xls",{"sExtends": "pdf","sPdfOrientation": "landscape"}],sSwfPath:"./swf/copy_csv_xls_pdf.swf"},



        "aaSorting": [],



        //"aLengthMenu":[[25, 50, 100, 200, -1],[25, 50, 100, 200, "TOUS1"]],



        "iDisplayLength": -1,



        paging: false



    });



});



</script>











<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>



<?php



//echo do_link("","","Nouvel at&eacute;lier","<i class=\"icon-plus\"> Nouvel </i>","","./","pull-right p11","get_content('new_atelier.php','annee=$annee','modal-body_add',this.title);",1,"",$nfile);



?>



<a onclick="get_content('new_mission_terrain.php','<?php echo "annee=$annee"; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Missions de terrain" class="pull-right p11" dir=""><i class="icon-plus"> Nouvelle Mission </i></a>



<?php } ?>



<div class="clear">&nbsp;</div>



<table class="table table-striped table-bordered table-hover table-responsive  table-colvis datatable dataTable" id="mtable<?php echo $annee; ?>" aria-describedby="DataTables_Table_0_info">



<thead>



<tr role="row">

  <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >R&eacute;f</th>



<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Objet</div></th>



<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Lieu</div></th>



<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Moyen de<br/> transport </th>

<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">

  <div align="center">P&eacute;riode <br />

    (D&eacute;but - Fin)</div>

</div></th>



<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Initi&eacute;e par </div></th>



<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Participants 

  <div class="firstcapitalize"></div></th>



<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">TDR</div></th>



<!--<th nowrap="nowrap" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Ordre de<br/>

  mission</th>-->

<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Rapport</div></th>



<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>



<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="80">Actions</th>



<?php } ?>

</tr>

</thead>



<tbody role="alert" aria-live="polite" aria-relevant="all" class="">



<?php $i=0; if($totalRows_act>0) { foreach($row_act as $row_act){  $id = $row_act["id_atelier"];   ?>



<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">

  <td><div align="left"><strong><?php echo $row_act['code_mission'];  ?></strong></div></td>



<td><div align="left" class="bs-popover" data-trigger="hover" data-html="true" data-placement="right" data-content="<?php echo $row_act['objectif']; ?>" data-original-title="Objectif <?php if(isset($row_act['code_activite']) && $row_act['code_activite']!="RAS") echo "(".$row_act['code_activite'].")"; else echo "(-)";  ?>"> <?php echo strlen($row_act['objectif'])>50?substr($row_act['objectif'],0, 50)." &hellip;+":$row_act['objectif']; ?>&nbsp;<em><?php //if(isset($row_act['code_activite']) && $row_act['code_activite']!="RAS") echo "(".$row_act['code_activite'].")";  ?></em></div></td>



<td><div align="left"><b>

  <?php $al = explode(",",$row_act['lieu']); if(count($al)>0){ foreach($al as $bl) echo isset($village_array[$bl])?$village_array[$bl].";&nbsp;":""; }  ?>

</b></div></td>



<td><?php echo $row_act['moyen_transport'];  ?></td>

<td><div align="center"><span class="Style4">



<?php $jour = NbJours($row_act['debut'],$row_act['fin']); 

if(date('m', strtotime($row_act['debut']))==date('m', strtotime($row_act['fin']))) echo date('d', strtotime($row_act['debut']))." au ".date('d-m-Y', strtotime($row_act['fin'])); else echo date('d-m', strtotime($row_act['debut']))." au ".date('d-m-Y', strtotime($row_act['fin'])); echo "&nbsp;(<b>$jour jour".(($jour)>1?'s':'')."</b>)";

//echo date_reg($row_act['debut'],"/")." au<br />".date_reg($row_act['fin'],"/")."&nbsp;(<b>$jour jour".(($jour)>1?'s':'')."</b>)";  ?>



</span></div></td>



<td><div align="left"><?php if(isset($respo_ugl[$row_act["responsable"]])) echo $respo_ugl[$row_act["responsable"]]; else echo $row_act["responsable"]; ?></div></td>



<td><div align="left"><b><?php $a = explode(",",$row_act['participants']); if(count($a)>0){ foreach($a as $b)   echo "<a href='./template_word/ordre_mission_vierge.php?fonction=$b&id=$id&numero=".$row_act["code_mission"]."' title='T&eacute;l&eacute;charger l ordre de mmission de ".$b."' >$b</a>; &nbsp;"; } ?></b></div></td>



<td align="center"><?php if(isset($row_act["tdr"]) && file_exists($dir.$row_act["tdr"])) echo "<a href='./download_file.php?file=".$dir.$row_act["tdr"]."' title='T&eacute;l&eacute;charger ".$row_act["tdr"]."' ><img src=\"./images/download.png\" width=\"20\" height=\"20\" alt=\"T&eacute;l&eacute;charger les TDR\" title=\"T&eacute;l&eacute;charger les TDR\"></a>"; else "NaN"; ?></td>



<!--<td align="center">

<a onclick="get_content('edit_suivi_om.php','<?php echo "numero=$id&annee=$annee"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Suivi des ordres de la mission N&ordm;<?php echo $row_act["code_mission"]; ?>" class="" dir="">  Suivi OM </a>

</td>-->

<td align="center"><?php if(isset($row_act["rapport"]) && file_exists($dir.$row_act["rapport"])) echo "<a href='./download_file.php?file=".$dir.$row_act["rapport"]."' title='T&eacute;l&eacute;charger ".$row_act["rapport"]."' ><img src=\"./images/download.png\" width=\"20\" height=\"20\" alt=\"T&eacute;l&eacute;charger le rapport\" title=\"T&eacute;l&eacute;charger le rapport\"></a>"; ?>



<a href="#myModal_add" data-backdrop="static" data-keyboard="false" data-toggle="modal" title="Ajout de rapport de mission" onclick="get_content('new_mission_terrain.php','<?php echo "id=$id&annee=$annee&rapport=1"; ?>','modal-body_add',this.title);" style=""><?php if(isset($row_act["rapport"]) && file_exists($dir.$row_act["rapport"])) echo "Modifier"; else echo "Ajouter"; ?></a>



<?php //} ?></td>



<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>



<td align="center" nowrap="nowrap" class=" ">

<?php

echo do_link("","","Modifier Mission","","edit","./","","get_content('new_mission_terrain.php','id=".$id."&annee=".$annee."','modal-body_add',this.title);",1,"margin:0px 5px 0 0; ","gestion_mission_terrain.php");



echo do_link("","./gestion_mission_terrain.php?id_sup=".$id."&annee=".$annee,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette mission ?');",0,"margin:0px 0 0 5px;","gestion_mission_terrain.php");

?>

<!--<a href="#myModal_add" data-backdrop="static" data-keyboard="false" data-toggle="modal" title="Mission externe" onclick="get_content('new_mission_terrain.php','<?php echo "id=$id&annee=$annee"; ?>','modal-body_add',this.title);" style=""><img src="./images/edit.png" width="20" height="20" alt="Modifier" title="Modifier"></a>



<a href="./gestion_mission_terrain.php?<?php echo "id_sup=$id&annee=$annee"; ?>" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cette mission ?');" style="margin:0px 5px; 0px"><img src="./images/delete.png" width="20" height="20" alt="Supprimer" title="Supprimer"></a>-->

</td>



<?php } ?>

</tr>



<?php $i++; }  } ?>

</tbody></table>







<?php include 'modal_add.php'; ?>