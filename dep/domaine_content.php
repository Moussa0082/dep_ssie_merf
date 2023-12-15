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
//mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_fonction = "SELECT * FROM ".$database_connect_prefix."sous_secteur_activite ";
try{
  $fonction = $pdar_connexion->prepare($query_fonction);
  $fonction->execute();
  $row_fonction = $fonction ->fetchAll();
  $totalRows_fonction = $fonction->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$secteur_array = array();
if($totalRows_fonction>0){  foreach($row_fonction as $row_fonction){
  $secteur_array[$row_fonction["id_sous_secteur"]]=$row_fonction["nom_sous_secteur"];
   }//while($row_fonction = mysql_fetch_assoc($fonction));
}

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
var oTable = $('#mtable2').dataTable( {
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
<table class="table table-striped table-bordered table-hover table-responsive table-tabletools table-colvis datatable dataTable" id="mtable2" aria-describedby="DataTables_Table_0_info">
  <thead>
    <tr role="row">
      <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Code</th>
      <th nowrap="nowrap" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Domaines</th>
      <th nowrap="nowrap" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Th&eacute;matiques</th>
 <?php if(isset($_SESSION['clp_id']) && $_SESSION['clp_id']=="admin"){ ?>     
	 <th width="90" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Actions </th>
 <?php } ?>
	</tr>
  </thead>
  <tbody role="alert" aria-live="polite" aria-relevant="all" class="">
    <?php
$query_domaine = "SELECT * FROM domaine_activite order by code_domaine";
try{
  $domaine = $pdar_connexion->prepare($query_domaine);
  $domaine->execute();
  $row_domaine = $domaine ->fetchAll();
  $totalRows_domaine = $domaine->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>
    <?php if($totalRows_domaine>0) { ?>


    <?php $i=0; foreach($row_domaine as $row_domaine){ $total_an=0; $id=$row_domaine['id_domaine']; 	?>
    <tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
      <td class=" "><?php echo $row_domaine['code_domaine']; ?></td>
      <td class=" "><?php echo $row_domaine['nom_domaine']; ?></td>
      <td class=" "><?php if(isset($secteur_array[$row_domaine["sous_secteur"]])) echo $secteur_array[$row_domaine["sous_secteur"]]; ?>&nbsp;</td>
       <?php if(isset($_SESSION['clp_id']) && $_SESSION['clp_id']=="admin"){  ?>
	  <td nowrap="nowrap" class=" "><div align="center">
      <?php 
echo do_link("","","Th&eacute;matique","","edit","./","","get_content('new_domaine.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);
echo do_link("","cadre_sectoriel.php?id_supd=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce Th&egrave;me ".$row_domaine['code_domaine']."');",0,"margin:0px 5px;",$nfile);
?>
    </div></td>
	<?php }  ?>
    </tr>
    <?php } //while ($row_domaine = mysql_fetch_assoc($domaine));  mysql_free_result($domaine);?>
    <?php }  ?>
  </tbody>
</table>
<?php include_once 'modal_add.php'; ?>