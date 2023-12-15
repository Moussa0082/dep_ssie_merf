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



//header('Content-Type: text/html; charset=ISO-8859-15');







if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else $annee=date("Y");



$dir = './attachment/mission_atelier/';







//fonction calcul nb jour



function NbJours($debut, $fin) {



  $tDeb = explode("-", $debut);



  $tFin = explode("-", $fin);



  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);



  return(($diff / 86400)+1);



}



/*mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_respo_ugl = "SELECT id_personnel, fonction FROM ".$database_connect_prefix."personnel where structure='".$_SESSION["clp_structure"]."' and projet like '%".$_SESSION["clp_structure"]."|%' ";

$liste_respo_ugl  = mysql_query_ruche($query_liste_respo_ugl , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_liste_respo_ugl  = mysql_fetch_assoc($liste_respo_ugl );

$totalRows_liste_respo_ugl  = mysql_num_rows($liste_respo_ugl );

$respo_ugl=array();

if($totalRows_liste_respo_ugl>0){ do{ $respo_ugl[$row_liste_respo_ugl["id_personnel"]]=$row_liste_respo_ugl["fonction"];  }while($row_liste_respo_ugl  = mysql_fetch_assoc($liste_respo_ugl ));  } */



$query_act = "SELECT * FROM ".$database_connect_prefix."contrat_prestation where projet='".$_SESSION["clp_projet"]."' and YEAR(debut)='$annee' order by code_marche, numero_marche desc ";
try{
    $act = $pdar_connexion->prepare($query_act);
    $act->execute();
    $row_act = $act ->fetchAll();
    $totalRows_act = $act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


$query_liste_decompte = "SELECT contrat, sum(montant_soumis) as decompte FROM ".$database_connect_prefix."suivi_contrat group by contrat";
try{
    $liste_decompte = $pdar_connexion->prepare($query_liste_decompte);
    $liste_decompte->execute();
    $row_liste_decompte = $liste_decompte ->fetchAll();
    $totalRows_liste_decompte = $liste_decompte->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$decompte_array = array();
if($totalRows_liste_decompte>0){  foreach($row_liste_decompte as $row_liste_decompte){
  $decompte_array[$row_liste_decompte["contrat"]] = $row_liste_decompte["decompte"];
}  }

$query_liste_decaissement = "SELECT contrat, sum(montant_decaisse) as decaissement FROM ".$database_connect_prefix."suivi_decaissement group by contrat";
try{
    $liste_decaissement = $pdar_connexion->prepare($query_liste_decaissement);
    $liste_decaissement->execute();
    $row_liste_decaissement = $liste_decaissement ->fetchAll();
    $totalRows_liste_decaissement = $liste_decaissement->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$decaissement_array = array();
if($totalRows_liste_decaissement>0){   foreach($row_liste_decaissement as $row_liste_decaissement){
  $decaissement_array[$row_liste_decaissement["contrat"]] = $row_liste_decaissement["decaissement"];
}  }

$query_liste_avenant = "SELECT contrat, sum(montant) as avenant FROM ".$database_connect_prefix."suivi_avenant group by contrat";
try{
    $liste_avenant = $pdar_connexion->prepare($query_liste_avenant);
    $liste_avenant->execute();
    $row_liste_avenant = $liste_avenant ->fetchAll();
    $totalRows_liste_avenant = $liste_avenant->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$avenant_array = array();
if($totalRows_liste_avenant>0){  foreach($row_liste_avenant as $row_liste_avenant){
  $avenant_array[$row_liste_avenant["contrat"]] = $row_liste_avenant["avenant"];
}  }

$query_liste_activite_1 = "SELECT code_marche, intitule FROM ".$database_connect_prefix."plan_marche";
try{
    $liste_activite_1 = $pdar_connexion->prepare($query_liste_activite_1);
    $liste_activite_1->execute();
    $row_liste_activite_1 = $liste_activite_1 ->fetchAll();
    $totalRows_liste_activite_1 = $liste_activite_1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$cmp_array = array();
if($totalRows_liste_activite_1>0){  foreach($row_liste_activite_1 as $row_liste_activite_1){
  $cmp_array[$row_liste_activite_1["code_marche"]] = $row_liste_activite_1["intitule"];
}  }



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



       // sDom:"<'row'<'dataTables_header clearfix'<'col-md-7'lT><'col-md-5'Cf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",



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



//echo do_link("","","Nouveau contrat","<i class=\"icon-plus\"> Nouveau contrat </i>","simple","./","pull-right p11","get_content('new_contrat_prestation.php','annee=$annee','modal-body_add',this.title);",1,"","gestion_contrat_prestation.php");



?>


<!--
<a onclick="get_content('new_contrat_prestation.php','<?php echo "annee=$annee"; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Contrats de prestation" class="pull-right p11" dir=""><i class="icon-plus"> Nouveau contrat </i></a>

-->

<?php } ?>


<table class="table table-striped table-bordered table-hover table-responsive table-tabletools table-colvis datatable dataTable" id="mtable<?php echo $annee; ?>" aria-describedby="DataTables_Table_0_info">

<thead>



<tr role="row">

  <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >R&eacute;f</th>



<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Objet</div></th>



<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Prestataire</div></th>


<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >March&eacute; / lot </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">

  <div align="center">Date pr&eacute;vue  </div>

</div></th>



<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Contrat</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Montant 

  <div class="firstcapitalize"></div></th>




<th nowrap="nowrap" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >D&eacute;caissement</th>
<th nowrap="nowrap" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Avenant </th>

<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Rapport</div></th>-->



<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>



<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="80">Actions</th>



<?php } ?>
</tr>
</thead>



<tbody role="alert" aria-live="polite" aria-relevant="all" class="">



<?php $i=0; if($totalRows_act>0) { foreach($row_act as $row_act){ $id = $row_act["id_contrat"];   ?>



<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">

  <td><div align="left" class="bs-popover" data-trigger="hover" data-html="true" data-placement="right" data-content="<?php if(isset($cmp_array[$row_act['code_marche']])) echo $cmp_array[$row_act['code_marche']]; ?>" data-original-title="R&eacute;f <?php if(isset($row_act['code_marche']) && $row_act['code_marche']!="RAS") echo "(".$row_act['code_marche'].")"; else echo "(-)";  ?>"> <?php if(isset($cmp_array[$row_act['code_marche']])) {echo strlen($cmp_array[$row_act['code_marche']])>50?$row_act['code_marche'].": ".substr($cmp_array[$row_act['code_marche']],0, 50)." &hellip;+":$row_act['code_marche'].": ".$cmp_array[$row_act['code_marche']];} ?>&nbsp;<em></em></div></td>



<td><?php echo $row_act['lieu'];  ?></td>



<td><div align="left"><b>

  <?php  echo str_replace("|","; ",$row_act['prestataire'])."/ "; //$al = explode("|",$row_act['lieu']); if(count($al)>0){ foreach($al as $bl)   echo "$bl;&nbsp;"; }  ?>

</b>
    <strong style="color:#CC0000">
    <?php if(isset($row_act["responsable"])) echo $row_act["responsable"];  ?>
    </strong></div></td>


<td><?php echo $row_act['numero_marche'];  ?>/ <strong><?php echo $row_act['numero_lot'];  ?></strong></td>
<td nowrap="nowrap"><div align="center"><span class="Style4">



<?php $jour = NbJours($row_act['debut'],$row_act['fin']); 

 echo date('d-m-Y', strtotime($row_act['debut'])); echo "<br/>(<b>$jour jr".(($jour)>1?'s':'')."</b>)";

//echo date_reg($row_act['debut'],"/")." au<br />".date_reg($row_act['fin'],"/")."&nbsp;(<b>$jour jour".(($jour)>1?'s':'')."</b>)";  ?>



</span></div></td>



<td><div align="center"><?php if(isset($row_act["contrat"]) && file_exists($dir.$row_act["contrat"])) echo "<a href='./download_file.php?file=".$dir.$row_act["contrat"]."' title='T&eacute;l&eacute;charger ".$row_act["contrat"]."' ><img src=\"./images/download.png\" width=\"20\" height=\"20\" alt=\"T&eacute;l&eacute;charger les contrat\" title=\"T&eacute;l&eacute;charger le contrat\"></a>"; else "NaN"; ?>
  </div></td>
<td nowrap="nowrap"><div align="right"><strong><?php echo number_format($row_act['montant_contrat'], 0, ',', ' ');  ?></strong></div></td>

<td align="center"><a onclick="get_content('edit_suivi_decaissement.php','<?php echo "numero=$id&annee=$annee"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Suivi des d&eacute;caissements du contrat N&ordm;<?php echo $row_act["numero_marche"]; ?>" class="" dir="">  <?php if(isset($decaissement_array[$id])) echo number_format($decaissement_array[$id], 0, ',', ' '); else echo "Suivre"; ?>  </a><strong><em><?php if(isset($row_act['montant_contrat']) && $row_act['montant_contrat']>0 && isset($decaissement_array[$id])) echo "</br>(".number_format(100*$decaissement_array[$id]/$row_act['montant_contrat'], 0, ',', ' ')."%)";  ?></em></strong></td>
<td align="center">
<a onclick="get_content('edit_suivi_avenant.php','<?php echo "numero=$id&annee=$annee"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Suivi des avenant du contrat N&ordm;<?php echo $row_act["numero_marche"]; ?>" class="" dir="">  <?php if(isset($avenant_array[$id])) echo "<b>".count($avenant_array[$id])."</b> (".number_format($avenant_array[$id], 0, ',', ' ').")"; else echo "Aucun"; ?></a>
<!--<strong><em>
<?php //if(isset($row_act['montant_contrat']) && $row_act['montant_contrat']>0 && isset($avenant_array[$id])) echo "</br>(".number_format(100*$avenant_array[$id]/$row_act['montant_contrat'], 0, ',', ' ')."%)";  ?>
</em></strong> --></td>

<!--<td align="center"><?php if(isset($row_act["rapport"]) && file_exists($dir.$row_act["rapport"])) echo "<a href='./download_file.php?file=".$dir.$row_act["rapport"]."' title='T&eacute;l&eacute;charger ".$row_act["rapport"]."' ><img src=\"./images/download.png\" width=\"20\" height=\"20\" alt=\"T&eacute;l&eacute;charger le rapport\" title=\"T&eacute;l&eacute;charger le rapport\"></a>"; ?>



<a href="#myModal_add" data-backdrop="static" data-keyboard="false" data-toggle="modal" title="Ajout de rapport de mission" onclick="get_content('new_mission_terrain.php','<?php echo "id=$id&annee=$annee&rapport=1"; ?>','modal-body_add',this.title);" style=""><?php if(isset($row_act["rapport"]) && file_exists($dir.$row_act["rapport"])) echo "Modifier"; else echo "Ajouter"; ?></a>



<?php //} ?></td>-->                      


<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) { ?>
<td align="center" nowrap="nowrap" class=" ">

<?php

echo do_link("","","Modifier contrat","","edit","./","","get_content('new_contrat_prestation.php','id=".$id."&annee=".$annee."','modal-body_add',this.title);",1,"margin:0px 5px 0 0; ","gestion_contrat_prestation.php");

echo do_link("","./gestion_contrat_prestation.php?id_sup=".$id."&annee=".$annee,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce contrat ?');",0,"margin:0px 0 0 5px;","gestion_contrat_prestation.php");

?></td>
<?php } ?>
</tr>



<?php $i++; }  } ?>
</tbody></table>

<?php include 'modal_add.php'; ?>