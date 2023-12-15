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

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_respo_ugl = "SELECT id_personnel, fonction FROM ".$database_connect_prefix."personnel where structure='".$_SESSION["clp_structure"]."' and projet like '%".$_SESSION["clp_structure"]."|%' ";
$liste_respo_ugl  = mysql_query($query_liste_respo_ugl , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_respo_ugl  = mysql_fetch_assoc($liste_respo_ugl );
$totalRows_liste_respo_ugl  = mysql_num_rows($liste_respo_ugl );
$respo_ugl=array();
if($totalRows_liste_respo_ugl>0){ do{ $respo_ugl[$row_liste_respo_ugl["id_personnel"]]=$row_liste_respo_ugl["fonction"];  }while($row_liste_respo_ugl  = mysql_fetch_assoc($liste_respo_ugl ));  }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = "SELECT * FROM ".$database_connect_prefix."ateliers where year(debut)='$annee' ";
$act  = mysql_query($query_act , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_act  = mysql_fetch_assoc($act);
$totalRows_act  = mysql_num_rows($act);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_activite_1 = "SELECT code_activite_ptba,intitule_activite_ptba FROM ".$database_connect_prefix."ptba WHERE projet='".$_SESSION["clp_projet"]."' and annee='$annee'";
$liste_activite_1  = mysql_query($query_liste_activite_1 , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_activite_1  = mysql_fetch_assoc($liste_activite_1 );
$totalRows_liste_activite_1  = mysql_num_rows($liste_activite_1 );
$cmp_array = array();
if($totalRows_liste_activite_1>0){  do{
  $cmp_array[$row_liste_activite_1["code_activite_ptba"]] = $row_liste_activite_1["intitule_activite_ptba"];
}while($row_liste_activite_1 = mysql_fetch_assoc($liste_activite_1));  }
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
//echo do_link("","","Nouvel at&eacute;lier","<i class=\"icon-plus\"> Nouvel </i>","","./","pull-right p11","get_content('new_atelier.php','annee=$annee','modal-body_add',this.title);",1,"",$nfile);
?>
<a onclick="get_content('new_atelier.php','<?php echo "annee=$annee"; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Nouvel at&eacute;lier" class="pull-right p11" dir=""><i class="icon-plus"> Nouvel at&eacute;lier </i></a>
<?php } ?>

<div class="clear">&nbsp;</div>

<table class="table table-striped table-bordered table-hover table-responsive table-tabletools table-colvis datatable dataTable" id="mtable<?php echo $annee; ?>" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Type de <br />mission</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Activit&eacute;</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Objectif</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Lieu</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">P&eacute;riode <br />(D&eacute;but - Fin)</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Responsables</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Participants </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Frais Total<br />(Ouguiya)</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">TDR</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">Rapport</div></th>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="80">Actions</th>
<?php } ?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="">
<?php $i=0; if($totalRows_act>0) { do { $id = $row_act["id_atelier"];   ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td><div align="left"> <?php echo $row_act['type_mission'];  ?></div></td>
<td><div align="left" style="font-size:11px" title="<?php if(isset($cmp_array[$row_act["code_activite"]]))  echo $cmp_array[$row_act['code_activite']]; ?>"> <?php if(isset($cmp_array[$row_act["code_activite"]]))  echo $cmp_array[$row_act['code_activite']]; ?></div></td>
<td><div align="left"> <?php echo $row_act['objectif'];  ?></div></td>
<td><div align="left"> <?php echo $row_act['lieu'];  ?></div></td>
<td><div align="center"><span class="Style4">
<?php $jour = NbJours($row_act['debut'],$row_act['fin']); echo date_reg($row_act['debut'],"/")." au ".date_reg($row_act['fin'],"/")."<br />(<b>$jour jour".(($jour)>1?'s':'')."</b>)";  ?>
</span></div></td>
<td><div align="left" title="<?php if(isset($respo_ugl[$row_act["responsable"]])) echo $respo_ugl[$row_act["responsable"]]; ?>"><?php if(isset($row_act['responsable'])) echo $row_act['responsable']; ?></div></td>
<td><div align="left"> <?php echo $row_act['participants'];  ?></div></td>
<td class="Style4"><div align="left" class="Style4"><?php echo number_format($row_act['montant'],2,'',' ') ; ?></div></td>
<td align="center"><?php if(isset($row_act["tdr"]) && file_exists($dir.$row_act["tdr"])) echo "<a href='./download_file.php?file=".$dir.$row_act["tdr"]."' title='T&eacute;l&eacute;charger ".$row_act["tdr"]."' >T&eacute;l&eacute;charger</a>"; else "NaN"; ?></td>
<td align="center"><?php if(isset($row_act["rapport"]) && file_exists($dir.$row_act["rapport"])) echo "<a href='./download_file.php?file=".$dir.$row_act["rapport"]."' title='T&eacute;l&eacute;charger ".$row_act["rapport"]."' >T&eacute;l&eacute;charger</a>"; else{ ?>
<a href="#myModal_add" data-backdrop="static" data-keyboard="false" data-toggle="modal" title="Ajout de rapport d'at&eacute;lier" onclick="get_content('new_atelier.php','<?php echo "id=$id&annee=$annee&rapport=1"; ?>','modal-body_add',this.title);" style="">Ajouter</a>
<?php } ?></td>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<td align="center" nowrap="nowrap" class=" ">
<a href="#myModal_add" data-backdrop="static" data-keyboard="false" data-toggle="modal" title="Modification d'at&eacute;lier" onclick="get_content('new_atelier.php','<?php echo "id=$id&annee=$annee"; ?>','modal-body_add',this.title);" style=""><img src="./images/edit.png" width="20" height="20" alt="Modifier" title="Modifier"></a>
<a href="./gestion_ateliers.php?<?php echo "id_sup=$id&annee=$annee"; ?>" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cet at&eacute;lier ?');" style="margin:0px 5px; 0px"><img src="./images/delete.png" width="20" height="20" alt="Supprimer" title="Supprimer"></a>
<?php
//echo do_link("","","Modification d'at&eacute;lier","","edit","./","","get_content('new_atelier.php','id=$id&annee=$annee','modal-body_add',this.title);",1,"margin:0px 5px; 0px 0px",$nfile);

//echo do_link("",$_SERVER['PHP_SELF']."?id_sup=$id&annee=$annee","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cet at&eacute;lier ?');",0,"",$nfile);
?></td>
<?php } ?>
</tr>
<?php $i++; } while ($row_act = mysql_fetch_assoc($act)); } ?>
</tbody></table>

<?php include 'modal_add.php'; ?>