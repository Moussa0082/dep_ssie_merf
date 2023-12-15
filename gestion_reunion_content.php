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
$dir = './attachment/reunion_rencontre/';

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = "SELECT * FROM ".$database_connect_prefix."reunions where year(debut)='$annee' ";
$act  = mysql_query($query_act , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_act  = mysql_fetch_assoc($act);
$totalRows_act  = mysql_num_rows($act);

?>
<!-- Site contenu ici -->
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
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
//$('a[data-toggle="modal"]').modal();
var oTable = $('#mtable<?php echo $annee; ?>').dataTable( {
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ -1 ] }
        ],
        sDom:"<'row'<'dataTables_header clearfix'<'col-md-7'lT><'col-md-5'Cf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
        oTableTools:{aButtons:["copy","print","csv","xls",{"sExtends": "pdf","sPdfOrientation": "landscape"}],sSwfPath:"./swf/copy_csv_xls_pdf.swf"},
        "aaSorting": [],
        //"aLengthMenu":[[25, 50, 100, 200, -1],[25, 50, 100, 200, "TOUS1"]],
        "iDisplayLength": -1,
        paging: false
    });
});
</script>


<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
<?php
//echo do_link("","","Nouvelle r&eacute;union","<i class=\"icon-plus\"> Nouvel </i>","","./","pull-right p11","get_content('new_reunion.php','annee=$annee','modal-body_add',this.title);",1,"",$nfile);
?>
<a onclick="get_content('new_reunion.php','<?php echo "annee=$annee"; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Nouvelle r&eacute;union" class="pull-right p11" dir=""><i class="icon-plus"> Nouvelle r&eacute;union </i></a>
<?php } ?>

<div class="clear">&nbsp;</div>

<table class="table table-striped table-bordered table-hover table-responsive table-tabletools table-colvis datatable dataTable" id="mtable<?php echo $annee; ?>" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Type de <br />r&eacute;union</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Objectif</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Lieu</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Date</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Participants </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Recommandations</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Documents</div></th>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="80">Actions</th>
<?php } ?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="">
<?php $i=0; if($totalRows_act>0) { do { $id = $row_act["id_reunion"];   ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td><div align="left"> <?php echo $row_act['type_reunion'];  ?></div></td>
<td><div align="left"> <?php echo $row_act['objectif'];  ?></div></td>
<td><div align="left"> <?php echo $row_act['lieu'];  ?></div></td>
<td><div align="center"><span class="Style4">
<?php echo date_reg($row_act['debut'],"/");  ?>
</span></div></td>
<td><div align="left"> <?php echo $row_act['participants'];  ?></div></td>
<td><div align="left"> <?php echo $row_act['recommandation'];  ?></div></td>
<td align="center"><?php if(isset($row_act["rapport"]) && file_exists($dir.$row_act["rapport"])) echo "<a href='./download_file.php?file=".$dir.$row_act["rapport"]."' title='T&eacute;l&eacute;charger ".$row_act["rapport"]."' >T&eacute;l&eacute;charger</a>"; else{ ?>
<a href="#myModal_add" data-backdrop="static" data-keyboard="false" data-toggle="modal" title="Ajout de rapport d'at&eacute;lier" onclick="get_content('new_reunion.php','<?php echo "id=$id&annee=$annee&rapport=1"; ?>','modal-body_add',this.title);" style="">Ajouter</a>
<?php } ?></td>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<td align="center" nowrap="nowrap" class=" ">
<a href="#myModal_add" data-backdrop="static" data-keyboard="false" data-toggle="modal" title="Modification de r&eacute;union" onclick="get_content('new_reunion.php','<?php echo "id=$id&annee=$annee"; ?>','modal-body_add',this.title);" style=""><img src="./images/edit.png" width="20" height="20" alt="Modifier" title="Modifier"></a>
<a href="./gestion_ateliers.php?<?php echo "id_sup=$id&annee=$annee"; ?>" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cette r&eacute;union ?');" style="margin:0px 5px; 0px"><img src="./images/delete.png" width="20" height="20" alt="Supprimer" title="Supprimer"></a>
<?php
//echo do_link("","","Modification de r&eacute;union","","edit","./","","get_content('new_reunion.php','id=$id&annee=$annee','modal-body_add',this.title);",1,"margin:0px 5px; 0px 0px",$nfile);

//echo do_link("",$_SERVER['PHP_SELF']."?id_sup=$id&annee=$annee","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cet at&eacute;lier ?');",0,"",$nfile);
?></td>
<?php } ?>
</tr>
<?php $i++; } while ($row_act = mysql_fetch_assoc($act)); } ?>
</tbody></table>

<?php include 'modal_add.php'; ?>