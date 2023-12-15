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
header('Content-Type: text/html; charset=UTF-8');

$id_file = (isset($_GET["id"]))?$_GET['id']:1;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_sdno = "SELECT year(".$database_connect_prefix."mail_dno.date) as annee, ".$database_connect_prefix."mail_dno.* FROM ".$database_connect_prefix."mail_dno ";
switch($id_file)
{
  default:
  $query_liste_sdno .= " where dno is null  and traitement=0 and expediteur in (SELECT adresse_mail FROM ".$database_connect_prefix."partenaire WHERE dno=1)";
  break;
  case 1:
  $query_liste_sdno .= " where dno is null  and traitement=0 and expediteur in (SELECT adresse_mail FROM ".$database_connect_prefix."partenaire WHERE dno=1)";
  break;
  case 2:
  $query_liste_sdno .= " where dno is null  and traitement=0 and expediteur not in (SELECT adresse_mail FROM ".$database_connect_prefix."partenaire WHERE dno=1)";
  break;
  case 4:
  $query_liste_sdno .= " where traitement=1";
  break;
}
$query_liste_sdno .= " ORDER BY date desc";
$liste_sdno  = mysql_query($query_liste_sdno , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_sdno = mysql_fetch_assoc($liste_sdno);
$totalRows_liste_sdno = mysql_num_rows($liste_sdno);

if($id_file==1)
{
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_bailleur = "SELECT * FROM ".$database_connect_prefix."partenaire WHERE dno=1 ";
  $liste_bailleur = mysql_query($query_liste_bailleur, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
  $totalRows_liste_bailleur = mysql_num_rows($liste_bailleur);
  $destinateur_array = array();
  if($totalRows_liste_bailleur>0){ do{
    $destinateur_array[$row_liste_bailleur["adresse_mail"]] = $row_liste_bailleur["code"];
  }while($row_liste_bailleur = mysql_fetch_assoc($liste_bailleur));
      $rows = mysql_num_rows($liste_bailleur);
      if($rows > 0) {
          mysql_data_seek($liste_bailleur, 0);
    	  $row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
      }
  }
}

?>
<!-- Site contenu ici -->
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; width: 100%!important;
} .table tbody tr td {vertical-align: middle; }
</style>
<script>
$('#myModal_add').remove();
$().ready(function() {
//$('a[data-toggle="modal"]').modal();
var oTable = $('#mtable<?php echo $id_file; ?>').dataTable( {
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ -1 ] }
        ], 
        sDom:"<'row'<'dataTables_header clearfix'<'col-md-7'lT><'col-md-5'Cf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
        oTableTools:{aButtons:["copy","print","csv","xls",{"sExtends": "pdf","sPdfOrientation": "landscape"}],sSwfPath:"./swf/copy_csv_xls_pdf.swf"},
        "aaSorting": [], 
        //"aLengthMenu":[[25, 50, 100, 200, -1],[25, 50, 100, 200, "TOUS1"]],
        <?php if($id_file!=4){ ?>
        "iDisplayLength": -1,
        <?php } ?>
        paging: false
    });
});
</script>

<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive table-tabletools table-colvis datatable dataTable" align="center" id="mtable<?php echo $id_file; ?>" >
            <thead>
                <tr>
                  <td><div align="left"><strong>Date</strong></div></td>
                  <td><div align="left"><strong>Exp&eacute;diteur</strong></div></td>
                  <td><div align="left"><strong>Objet</strong></div></td>
                  <td><div align="left"><strong>Contenu</strong></div></td>
                  <!--<td><div align="left"><strong>Documents</strong></div></td>-->
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1) && $id_file!=4) { ?>
                  <td width="<?php echo ($id_file==2)?260:160; ?>"><div align="center"><strong>Actions</strong></div></td>
                  <?php } ?>
                </tr>
            </thead>
                <?php if($totalRows_liste_sdno>0) {$i=0;do {
                  $id = $row_liste_sdno['id_mail']; $read=$row_liste_sdno['statut']; $annee=$row_liste_sdno['annee'];  ?>
                <tr>
                  <td><div align="left"><?php echo date_reg($row_liste_sdno['date'],'/'); ?></div></td>
                  <td><div align="left"><?php echo ($id_file==1)?$destinateur_array[$row_liste_sdno['expediteur']]:$row_liste_sdno['expediteur']; ?></div></td>
                  <td><?php echo $row_liste_sdno['objet']; ?></td>
                  <td align="center">
<a onclick="get_content('body_mail_dno.php','id=<?php echo $id; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Contenu du message" class="" dir=""> Aper&ccedil;u </a>
                </td>
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1) && $id_file!=4) { ?>
<td align="center"> <?php if($id_file==1 && isset($destinateur_array[$row_liste_sdno["expediteur"]])) { ?>
<a onclick="get_content('edit_courrier_dno.php','<?php echo "add=1&annee=$annee&suivi=$id"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Suivre une DANO" class="" dir=""> Suivre une DANO </a>
<?php //echo do_link("","","Suivre une DANO","Suivre une DANO","","./","","get_content('edit_courrier_dno.php','add=1&annee=$annee&suivi=$id','modal-body_add',this.title,'iframe');",1,"",$nfile);
 } else { ?>
<a onclick="get_content('edit_courrier_dno.php','<?php echo "add=1&annee=$annee&new=$id"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Nouvelle DANO" class="" dir=""> Nouvelle DANO </a>&nbsp;&nbsp;&nbsp;
<a onclick="get_content('edit_courrier_dno.php','<?php echo "add=2&annee=$annee&suivi=$id"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Resoumission DANO" class="" dir=""> Resoumission </a>
<?php //echo do_link("","","Nouvelle DANO","Nouvelle","","./","","get_content('edit_courrier_dno.php','add=1&annee=$annee&new=$id','modal-body_add',this.title,'iframe');",1,"",$nfile);?> &nbsp;&nbsp;&nbsp; <?php //echo do_link("","","Resoumission DANO","Resoumission","","./","","get_content('edit_courrier_dno.php','add=2&annee=$annee&suivi=$id','modal-body_add',this.title,'iframe');",1,"",$nfile);
} ?>&nbsp;&nbsp;&nbsp;
<a href="./courrier_dno.php?id_archive=<?php echo "$id&pane=$id_file"; ?>" title="Archiver" onclick="return confirm('Voulez-vous vraiment archiver ce courrier DANO ?');" style=""><img src="./images/delete.png" width="20" height="20" alt="Archiver" title="Archiver"></a>
 </td>
 <?php } ?>
	    </tr>
                <?php } while ($row_liste_sdno = mysql_fetch_assoc($liste_sdno)); ?>
                <?php } ?>
</table>  <?php include 'modal_add.php'; ?>