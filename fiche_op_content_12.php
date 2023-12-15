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







if(isset($_GET['annee'])) {$annee=($_GET['annee']);} else $annee='0';



$dir = './attachment/fiche_collecte/';







//fonction calcul nb jour



function NbJours($debut, $fin) {



  $tDeb = explode("-", $debut);



  $tFin = explode("-", $fin);



  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);



  return(($diff / 86400)+1);



}



/*mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_respo_ugl = "SELECT id_personnel, fonction FROM personnel where structure='".$_SESSION["clp_structure"]."' and projet like '%".$_SESSION["clp_structure"]."|%' ";

$liste_respo_ugl  = mysql_query_ruche($query_liste_respo_ugl , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_liste_respo_ugl  = mysql_fetch_assoc($liste_respo_ugl );

$totalRows_liste_respo_ugl  = mysql_num_rows($liste_respo_ugl );

$respo_ugl=array();

if($totalRows_liste_respo_ugl>0){ do{ $respo_ugl[$row_liste_respo_ugl["id_personnel"]]=$row_liste_respo_ugl["fonction"];  }while($row_liste_respo_ugl  = mysql_fetch_assoc($liste_respo_ugl ));  } */


$query_act = "SELECT liste_op.*, nom_village, nom_commune FROM liste_op, village, commune where code_village=village and code_commune=commune and departement='$annee'  order by nom_commune, nom_village  desc ";
//$query_act = "SELECT liste_op.* FROM liste_op where projet='".$_SESSION["clp_projet"]."' order by id_op  desc ";
   try{
    $act = $pdar_connexion->prepare($query_act);
    $act->execute();
    $row_act = $act ->fetchAll();
    $totalRows_act = $act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//liste village
$query_liste_com = "SELECT code_commune,nom_commune, departement  FROM commune where departement='$annee'  order by code_commune asc";
//echo $query_liste_com;
   try{
    $liste_com = $pdar_connexion->prepare($query_liste_com);
    $liste_com->execute();
    $row_liste_com = $liste_com ->fetchAll();
    $totalRows_liste_com = $liste_com->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$departement_array =$commune_array = array();
if($totalRows_liste_com>0){  foreach($row_liste_com as $row_liste_com){
  $commune_array[$row_liste_com["code_commune"]] = $row_liste_com["nom_commune"];
  //$departement_array[$row_liste_village["code_commune"]] = $row_liste_village["nom_commune"];
}  }


//spéculation
$query_liste_speculation = "SELECT id_sp_maillon, speculation.libelle as speculation  FROM ".$database_connect_prefix."speculation, speculation_maillon  where id_speculation=speculation  order by   speculation.libelle";
   try{
    $liste_speculation = $pdar_connexion->prepare($query_liste_speculation);
    $liste_speculation->execute();
    $row_liste_speculation = $liste_speculation ->fetchAll();
    $totalRows_liste_speculation = $liste_speculation->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$speculation_array = array();
if($totalRows_liste_speculation>0){  foreach($row_liste_speculation as $row_liste_speculation){
  $speculation_array[$row_liste_speculation["id_sp_maillon"]] = $row_liste_speculation["speculation"];
}  }


//Maillon
$query_liste_maillon= "SELECT id_sp_maillon, maillon.libelle as maillon  FROM ".$database_connect_prefix."maillon, speculation_maillon  where id_maillon=maillon  order by   maillon.libelle";
   try{
    $liste_maillon = $pdar_connexion->prepare($query_liste_maillon);
    $liste_maillon->execute();
    $row_liste_maillon = $liste_maillon ->fetchAll();
    $totalRows_liste_maillon = $liste_maillon->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$maillon_array = array();
if($totalRows_liste_maillon>0){  foreach($row_liste_maillon as $row_liste_maillon){
  $maillon_array[$row_liste_maillon["id_sp_maillon"]] = $row_liste_maillon["maillon"];
}  }

//liste village
$query_liste_village = "SELECT code_village, nom_village, commune  FROM village  order by code_village asc";
   try{
    $liste_village = $pdar_connexion->prepare($query_liste_village);
    $liste_village->execute();
    $row_liste_village = $liste_village ->fetchAll();
    $totalRows_liste_village = $liste_village->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$code_commune_array  =$village_array  = array();
if($totalRows_liste_village>0){  foreach($row_liste_village as $row_liste_village){
  $village_array[$row_liste_village["code_village"]] = $row_liste_village["nom_village"];
  $code_commune_array[$row_liste_village["code_village"]] = $row_liste_village["commune"];
}  }

//membre
$query_liste_village = "SELECT id_ong, sigle_ong  FROM fiche_ong  group by sigle_ong";
   try{
    $liste_village = $pdar_connexion->prepare($query_liste_village);
    $liste_village->execute();
    $row_liste_village = $liste_village ->fetchAll();
    $totalRows_liste_village = $liste_village->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$sigle_ong_array = array();
if($totalRows_liste_village>0){  foreach($row_liste_village as $row_liste_village){
  $sigle_ong_array[$row_liste_village["id_ong"]] = $row_liste_village["sigle_ong"];
}  }


//membre
$query_liste_village = "SELECT count(id_membre) as nbm, sum(if(sexe='F',1,0)) as nbf, groupement  FROM membre_groupement  group by groupement";
   try{
    $liste_village = $pdar_connexion->prepare($query_liste_village);
    $liste_village->execute();
    $row_liste_village = $liste_village ->fetchAll();
    $totalRows_liste_village = $liste_village->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$nb_membre_array  =$femme_array  = array();
if($totalRows_liste_village>0){  foreach($row_liste_village as $row_liste_village){
  $nb_membre_array[$row_liste_village["groupement"]] = $row_liste_village["nbm"];
  $femme_array[$row_liste_village["groupement"]] = $row_liste_village["nbf"];
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



 .Style1 {color: #CC0000}
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
       // "iDisplayLength": -1,
        paging: false
    });
});
</script>











<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>



<?php
$feuille="liste_op";
$idf="id_op";
 echo do_link("","","Importation depuis un format excel","<i class=\"icon-plus\"> Importer </i>","","./","pull-right p11","get_content('import_fiches_statiques.php','feuille=$feuille&idf=$idf&annee=$annee','modal-body_add',this.title);",1,"margin-top:-5px;",$nfile);
 
 // echo do_link("","","Exportation dans un format excel","<i class=\"icon-plus\"> Exporter </i>","","./","pull-right p11","get_content('export_fiches_statitiques.php','feuille=$feuille&idf=$idf&annee=$annee','modal-body_add',this.title);",1,"margin-top:-5px;",$nfile);
 //echo do_link("","./export_fiches_statitiques.php?id=fiche_dynamique&feuille=$feuille&annee=$annee&nom=$feuille","Exportertation sous format excel","<i class=\"icon-plus\"> Exporter </i>","","../","pull-right p11","",0,"margin-top:-5px;",$nfile);


 ?>
 <a onclick="get_content('new_op.php','<?php echo "annee=$annee&region="; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Nouveau groupement" class="pull-right p11" dir=""><i class="icon-plus"> Ajouter groupement  </i></a>
<?php } ?>

<table class="table table-striped table-bordered table-hover table-responsive table-tabletools table-colvis datatable dataTable" id="mtable<?php echo $annee; ?>" aria-describedby="DataTables_Table_0_info">




<thead>



<tr role="row">

  <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Village/Commune</div></th>


<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Nom du groupement </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >&nbsp;</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Personnes ressources </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Date de cr&eacute;ation  </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Type</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >
<?php 
$feuille="membre_groupement";
$idf="id_membre";
 echo do_link("","","Importation des donn&eacute;es d'exploitation depuis un format excel","<i class=\"icon-plus\"> Importer exploitation </i>","","./","pull-right p11","get_content('import_fiches_statiques.php','feuille=$feuille&idf=$idf&annee=$annee','modal-body_add',this.title);",1,"margin-top:-5px;",$nfile);

?></th>

<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >% F </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Existence l&eacute;gale </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >ONG</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Animateur</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Maillons</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Sp&eacute;culations</th>
<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Liste B&eacute;n&eacute;ficiaire directs </th>
<th nowrap="nowrap" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Ordre de<br/>

  mission</th>

<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Rapport</div></th>
-->


<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>



<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="80">Actions</th>



<?php } ?>
</tr>
</thead>



<tbody role="alert" aria-live="polite" aria-relevant="all" class="">




<?php $i=0; if($totalRows_act>0) { foreach($row_act as $row_act){ $id = $row_act["id_op"];   ?>

<?php //if((isset($code_commune_array[$row_act["village"]]) && isset($commune_array[$code_commune_array[$row_act["village"]]]) && $annee!='0') || ((!isset($village_array[$row_act["village"]])) && $annee=='0')) {   ?>

<tr >

  <td><div align="left"><strong><?php if(isset($village_array[$row_act["village"]])) echo $commune_array[$code_commune_array[$row_act["village"]]]." /".$village_array[$row_act["village"]]; else echo " <span style=\"background-color:#FF6600\">Aucun </span>";   ?></strong></div></td>
<td><?php if(isset($row_act['sigle_op'])) echo  "<strong>".$row_act['sigle_op'].":</strong> "; echo $row_act['nom_op'];  ?></td>
<td><strong>
  <span class="Style1">
  <?php  echo $row_act['id_op'];  ?>
  </span> &nbsp;&nbsp;/
  </strong>
  <?php  echo $row_act['old_village'];  ?></td>
<td><?php echo $row_act['personne_ressource']." <br/>(".$row_act['contact'].")";  ?></td>
<td><span class="Style4">
  <?php if($row_act['date_creation']!="0000-00-00") echo date_reg($row_act['date_creation'],"/");  ?>
</span></td>
<td><?php echo $row_act['type_organisation'];  ?></td>
<td><div align="center"><a onclick="get_content('membre_groupement.php','<?php echo "id_fiche=$id"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Membre groupement" class="" dir=""> 
  <?php if(isset($nb_membre_array[$id]) && $nb_membre_array[$id]>0) echo  "&nbsp;&nbsp;".$nb_membre_array[$id]."&nbsp;&nbsp;"; else echo " <span style=\"background-color:#FF6600\">Détail </span>"; ?>
</a></div></td>

<td nowrap="nowrap"><div align="center">
  <?php if(isset($nb_membre_array[$id]) && isset($femme_array[$id]))  echo number_format(100*$femme_array[$id]/$nb_membre_array[$id], 0, ',', ' ')." %"; else echo "-"; ?>
</div></td>
<td><?php echo $row_act['existence_legale'];  ?></td>
<td><strong>
  <span class="Style1">
  <?php  if(isset( $sigle_ong_array[$row_act['faitiere']])) echo  $sigle_ong_array[$row_act['faitiere']] ;  ?>
  </span> 
  </strong></td>
<td><?php echo $row_act['nom_collecteur'];  ?></td>
<td><?php $j=0; if(isset($row_act["speculation"])){ $al = explode(",",$row_act["speculation"]);  if(count($al)>0){foreach($al as $bl){ if(isset($maillon_array[$bl])) {echo $maillon_array[$bl].";<br />"; $j=1;}  } }} if($j==0) echo " <span style=\"background-color:#FF6600\">Aucun </span>";  ?></td>
<td>&nbsp;
  <?php $j=0; if(isset($row_act["speculation"])){ $al = explode(",",$row_act["speculation"]);  if(count($al)>0){foreach($al as $bl){ if(isset($speculation_array[$bl])) {echo $speculation_array[$bl].";<br />"; $j=1;}  } }} if($j==0) echo " <span style=\"background-color:#FF6600\">Aucun </span>";  ?></td>





<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>



<td align="center" nowrap="nowrap" class=" ">

<?php

echo do_link("","","Modifier groupement","","edit","./","","get_content('new_op.php','id=".$id."&annee=".$annee."','modal-body_add',this.title);",1,"margin:0px 5px 0 0; ","fiche_op.php");



echo do_link("","./fiche_op.php?id_sup=".$id."&annee=".$annee,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce groupement ?');",0,"margin:0px 0 0 5px;","fiche_op.php");

?>

<!--<a href="#myModal_add" data-backdrop="static" data-keyboard="false" data-toggle="modal" title="Mission externe" onclick="get_content('new_mission_terrain.php','<?php echo "id=$id&annee=$annee"; ?>','modal-body_add',this.title);" style=""><img src="./images/edit.png" width="20" height="20" alt="Modifier" title="Modifier"></a>



<a href="./gestion_mission_terrain.php?<?php echo "id_sup=$id&annee=$annee"; ?>" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cette mission ?');" style="margin:0px 5px; 0px"><img src="./images/delete.png" width="20" height="20" alt="Supprimer" title="Supprimer"></a>--></td>



<?php } ?>
</tr>
<?php //}  ?>


<?php $i++; }  } ?>
</tbody></table>







<?php include 'modal_add.php'; ?>