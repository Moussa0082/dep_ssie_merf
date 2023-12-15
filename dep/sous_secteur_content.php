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

////mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_fonction = "SELECT * FROM ".$database_connect_prefix."partenaire ";
//$query_fonction = "SELECT * FROM ".$database_connect_prefix."domaine_activite ";
try{
  $fonction = $pdar_connexion->prepare($query_fonction);
  $fonction->execute();
  $row_fonction = $fonction ->fetchAll();
  $totalRows_fonction = $fonction->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$partenaire_array = $partenaire_desc_array = array();
if($totalRows_fonction>0){  foreach($row_fonction as $row_fonction){
  $partenaire_array[$row_fonction["id_partenaire"]]=$row_fonction["nom_partenaire"];
  $partenaire_desc_array[$row_fonction["id_partenaire"]]=$row_fonction["description"];
   }//while($row_fonction = mysql_fetch_assoc($fonction));
}

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_fonction = "SELECT * FROM ".$database_connect_prefix."domaine_activite ";
$fonction = mysql_query_ruche($query_fonction, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_fonction = mysql_fetch_assoc($fonction);
$totalRows_fonction = mysql_num_rows($fonction);*/

$query_fonction = "SELECT * FROM ".$database_connect_prefix."domaine_activite ";
try{
  $fonction = $pdar_connexion->prepare($query_fonction);
  $fonction->execute();
  $row_fonction = $fonction ->fetchAll();
  $totalRows_fonction = $fonction->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$secteur_array = array();
if($totalRows_fonction>0){   foreach($row_fonction as $row_fonction){
  $p = (!empty($row_fonction["partenaire"]))?explode(',',$row_fonction["partenaire"]):array(); $a = "";
  if(count($p)>0){ foreach($p as $pp) $a .= isset($partenaire_array[$pp])?"<span title=\"".(isset($partenaire_desc_array[$pp])?$partenaire_desc_array[$pp]:'')."\">".$partenaire_array[$pp]."</span>".", ":""; $a = !empty($a)?" (<b>".substr($a,0,-2)."</b>)":$a; }
  if(!isset($secteur_array[$row_fonction["sous_secteur"]]))
  $secteur_array[$row_fonction["sous_secteur"]]=$row_fonction["nom_domaine"].$a;
  else $secteur_array[$row_fonction["sous_secteur"]].="|".$row_fonction["nom_domaine"].$a;
   }//while($row_fonction = mysql_fetch_assoc($fonction));
}
/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_fonction = "SELECT * FROM ".$database_connect_prefix."programmes_ccc ";
$fonction = mysql_query_ruche($query_fonction, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_fonction = mysql_fetch_assoc($fonction);
$totalRows_fonction = mysql_num_rows($fonction);
$programmes_2qc_array = $programmes_2qc_desc_array = array();
if($totalRows_fonction>0){  do{
  $programmes_2qc_array[$row_fonction["id_programmes_2qc"]]="Programme 2QC (".$row_fonction["annee_debut"]." - ".$row_fonction["annee_fin"].")";
  $programmes_2qc_desc_array[$row_fonction["id_programmes_2qc"]]=$row_fonction["objectif"];
   }while($row_fonction = mysql_fetch_assoc($fonction));
}*/
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
var oTable = $('#mtable1').dataTable( {
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
<div class="clear">&nbsp;</div>
<!--<table class="table table-striped table-bordered table-hover table-responsive table-checkable table-tabletools table-colvis datatable dataTable" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">-->
<table class="table table-striped table-bordered table-hover table-responsive table-tabletools table-colvis datatable dataTable" id="mtable1" aria-describedby="DataTables_Table_0_info">
  <thead>
    <tr role="row">
      <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Code</th>
      <th nowrap="nowrap" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Domaines d'intervention </th>
      <!-- <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Programmes </th>-->
      <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Thématiques</th>
      <?php if(isset($_SESSION['clp_id']) && $_SESSION['clp_id']=="admin"){ ?>
      <th class="" role="" tabindex="0" aria-controls="" aria-label="" width="90">Actions</th>
      <?php } ?>
    </tr>
  </thead>
  <tbody role="alert" aria-live="polite" aria-relevant="all" class="">
    <?php
$query_sous_secteur = "SELECT *  FROM sous_secteur_activite order by code_sous_secteur";
try{
  $sous_secteur = $pdar_connexion->prepare($query_sous_secteur);
  $sous_secteur->execute();
  $row_sous_secteur = $sous_secteur ->fetchAll();
  $totalRows_sous_secteur = $sous_secteur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


if($totalRows_sous_secteur>0) { $i=0; foreach($row_sous_secteur as $row_sous_secteur){ $total_an=0; $id=$row_sous_secteur['id_sous_secteur']; 	?>
    <tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
      <td class=" "><?php echo $row_sous_secteur['code_sous_secteur']; ?></td>
      <td class=" "><?php echo $row_sous_secteur['nom_sous_secteur']; ?></td>
     <!-- <td class=" "><?php //$p = (!empty($row_sous_secteur["programmes_2qc"]))?explode(',',$row_sous_secteur["programmes_2qc"]):array(); if(count($p)>0){ echo "<ul style='margin-bottom:0px!important;padding-left: 15px!important;'>"; foreach($p as $pp) echo isset($programmes_2qc_array[$pp])?"<li title=\"".(isset($programmes_2qc_desc_array[$pp])?$programmes_2qc_desc_array[$pp]:'')."\">".$programmes_2qc_array[$pp]."</li>":""; echo "</ul>"; } ?></td>-->
      <td class=" "><?php if(isset($secteur_array[$row_sous_secteur["id_sous_secteur"]])) echo "<ul style='margin-bottom:0px!important;padding-left: 15px!important;'><li>".implode("</li><li>",explode('|',$secteur_array[$row_sous_secteur["id_sous_secteur"]]))."</li></ul>"; ?></td>
         <?php if(isset($_SESSION['clp_id']) && $_SESSION['clp_id']=="admin"){ ?>
	  <td nowrap="nowrap" class=" "><div align="center">
      <?php 
echo do_link("","","Modifier domaines d'intervention","","edit","./","","get_content('new_sous_secteur.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);
echo do_link("","cadre_sectoriel.php?id_supss=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce domaines d\'intervention ?');",0,"margin:0px 5px;",$nfile); //}
?>
    </div></td>
	 <?php }  ?>
    </tr>
    <?php } //while ($row_sous_secteur = mysql_fetch_assoc($sous_secteur));  mysql_free_result($sous_secteur);?>
    <?php }  ?>
  </tbody>
</table>
<?php include_once 'modal_add.php'; ?>