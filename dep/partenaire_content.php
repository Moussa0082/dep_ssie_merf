<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET['type'])) {$type=intval($_GET['type']);} else $type=1;

$query_liste_partenaire = "SELECT * FROM ".$database_connect_prefix."acteur where type_partenaire='$type' order by code_acteur ";
try{
    $liste_partenaire = $pdar_connexion->prepare($query_liste_partenaire);
    $liste_partenaire->execute();
    $row_liste_partenaire = $liste_partenaire ->fetchAll();
    $totalRows_liste_partenaire = $liste_partenaire->rowCount();
	}catch(Exception $e){ die(mysql_error_show_message($e)); }
	

$query_liste_type = "SELECT * FROM ".$database_connect_prefix."type_partenaire ORDER BY id_type_partenaire asc";
try{
    $liste_type = $pdar_connexion->prepare($query_liste_type);
    $liste_type->execute();
    $row_liste_type = $liste_type ->fetchAll();
    $totalRows_liste_type = $liste_type->rowCount();
	}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableau_TypePartenaire=array();
if($totalRows_liste_type>0){
foreach($row_liste_type as $row_liste_type){ 
$tableau_TypePartenaire[$row_liste_type['id_type_partenaire']]=$row_liste_type['nom_type_partenaire'];
} }
?>

<!-- Site contenu ici -->
<style>
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; width: 100%!important;
} .table tbody tr td {vertical-align: middle; }
.table1 {  border-spacing: 0px !important; border-collapse: collapse; width: 100%!important;
}
</style>
<script>
$('#myModal_add').remove();
$().ready(function() {
//$('a[data-toggle="modal"]').modal();
var oTable = $('#mtable<?php echo $type; ?>').dataTable( {
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

<div class="clear h0">&nbsp;</div>

<table class="table table-striped table-bordered table-hover table-responsive table-tabletools table-colvis datatable dataTable" id="mtable<?php echo $type;?>" aria-describedby="DataTables_Table_0_info">
  <thead>
    <tr role="row">
      <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Code</th>
      <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Sigle</th>
      <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Adresse</th>
      <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Contact</th>
      <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Map</th>
      <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Description</th>
      <!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Cat&eacute;gorie </th>-->
      <?php if(isset($_SESSION['clp_id']) && $_SESSION['clp_id']=='admin'){ ?>
      <th class="" role="" tabindex="0" aria-controls="" aria-label="" width="80">Actions</th>
      <?php } ?>
    </tr>
  </thead>
  <tbody role="alert" aria-live="polite" aria-relevant="all">
    <?php if($totalRows_liste_partenaire>0) { $i=0; foreach($row_liste_partenaire as $row_liste_partenaire){  $id = $row_liste_partenaire['id_acteur']; $code = $row_liste_partenaire['code_acteur']; ?>
    <tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
      <td class=" "><?php echo $code; ?></td>
      <td class=" "><?php echo $row_liste_partenaire['nom_acteur']; ?></td>
      <td class=" "><?php echo $row_liste_partenaire['adresse_partenaire']; ?>
          <?php if(isset($row_liste_partenaire['site_web'])) { ?>
          <a class="" href="http://<?php echo $row_liste_partenaire['site_web']; ?>" target="_blank" title="<?php echo $row_liste_partenaire['site_web']; ?>"> <em> &gt;&gt; Site web</em></a>
          <?php } ?>
          <?php if(isset($row_liste_partenaire['email_partenaire'])) { ?>
          <a class="" title="<?php echo $row_liste_partenaire['email_partenaire']; ?>" href="mailto:<?php echo $row_liste_partenaire['email_partenaire']; ?>" > <em> &gt;&gt; Email</em></a>
          <?php } ?></td>
      <td class=" "><?php echo $row_liste_partenaire['contact_partenaire']; ?></td>
      <td class=" "><?php if(isset($row_liste_partenaire['map_partenaire']) && !empty($row_liste_partenaire['map_partenaire'])) { ?>
          <a class="" href="<?php echo $row_liste_partenaire['map_partenaire']; ?>" target="_blank"> Afficher</a>
          <?php } ?></td>
      <td class=" "><?php echo strip_tags($row_liste_partenaire['description']); ?></td>
      <?php if(isset($_SESSION['clp_id']) && $_SESSION['clp_id']=='admin'){ ?>
      <td align="center" nowrap="nowrap" class=" "><?php
echo do_link("","","Modifier structure ou partenaire ".$row_liste_partenaire['nom_partenaire'],"","edit","./","","get_content('new_partenaire.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("","partenaires.php?id_sup=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette structure ".$row_liste_partenaire['nom_partenaire']."');",0,"margin:0px 5px;",$nfile);
?></td>
      <?php } ?>
    </tr>
    <?php } } ?>
  </tbody>
</table>
<?php include 'modal_add.php'; ?>