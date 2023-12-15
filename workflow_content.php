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

//fonction calcul nb jour
function NbJours($debut, $fin) {
  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);
  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
  return(($diff / 86400)+1);
}

$id_file = (isset($_GET["pane"]))?$_GET["pane"]:1;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_dossier = "SELECT * FROM ".$database_connect_prefix."workflow ";
switch($id_file)
{
  default:
  $query_liste_dossier .= " where traitement=0 ";
  break;
  case 1:
  $query_liste_dossier .= " where traitement=0";
  break;
  case 2:
  $query_liste_dossier .= " where traitement=1";
  break;
}
$query_liste_dossier .= " and projet='".$_SESSION["clp_projet"]."' and structure='".$_SESSION["clp_structure"]."' ORDER BY date_dossier desc";
$liste_dossier  = mysql_query($query_liste_dossier , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_dossier = mysql_fetch_assoc($liste_dossier);
$totalRows_liste_dossier = mysql_num_rows($liste_dossier);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_user = "SELECT N,nom,prenom FROM ".$database_connect_prefix."personnel ";
$liste_user = mysql_query($query_liste_user, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_user = mysql_fetch_assoc($liste_user);
$totalRows_liste_user = mysql_num_rows($liste_user);
$destinateur_array = array();
if($totalRows_liste_user>0){ do{
  $destinateur_array[$row_liste_user["N"]] = $row_liste_user["prenom"]." ".$row_liste_user["nom"];
}while($row_liste_user = mysql_fetch_assoc($liste_user));
    $rows = mysql_num_rows($liste_user);
    if($rows > 0) {
        mysql_data_seek($liste_user, 0);
  	  $row_liste_user = mysql_fetch_assoc($liste_user);
    }
}
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_docworkflow = "SELECT code,intitule,responsable_concerne,duree FROM ".$database_connect_prefix."type_doc_workflow ";
$liste_docworkflow  = mysql_query($query_liste_docworkflow , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_docworkflow = mysql_fetch_assoc($liste_docworkflow);
$totalRows_liste_docworkflow  = mysql_num_rows($liste_docworkflow);
$dureeworkflow_array=$docworkflow_array = $docworkflow_type_array = array();
if($totalRows_liste_docworkflow>0){ do{
$dureeworkflow_array[$row_liste_docworkflow["code"]]=$row_liste_docworkflow["duree"];
  $a = explode('|',$row_liste_docworkflow["responsable_concerne"]);
  // foreach($a as $c=>$d) $docworkflow_array[$row_liste_docworkflow["code"]][$d] = $b[$c];
  $docworkflow_type_array[$row_liste_docworkflow["code"]] = $row_liste_docworkflow["intitule"];
}while($row_liste_docworkflow = mysql_fetch_assoc($liste_docworkflow));
}

 
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_responsable = "SELECT distinct fonction, N FROM ".$database_connect_prefix."personnel ORDER BY fonction asc";
$liste_responsable  = mysql_query($query_liste_responsable , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_responsable  = mysql_fetch_assoc($liste_responsable);
$totalRows_liste_responsable  = mysql_num_rows($liste_responsable);
$fonction_array = array();
if($totalRows_liste_responsable>0){ do{ $fonction_array[$row_liste_responsable["N"]]=$row_liste_responsable["fonction"]; }while($row_liste_responsable  = mysql_fetch_assoc($liste_responsable)); }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_suivi = "SELECT id_suivi,numero,expediteur,date_dossier FROM ".$database_connect_prefix."suivi_workflow ";
$query_liste_suivi .= ($id_file==2)?"":"ORDER BY id_suivi desc";
$liste_suivi  = mysql_query($query_liste_suivi , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_suivi  = mysql_fetch_assoc($liste_suivi);
$totalRows_liste_suivi  = mysql_num_rows($liste_suivi);
$suivi_array = array();
if($totalRows_liste_suivi>0){ do{
  if($id_file==2)
  { //Archive on affiche les autes
    $suivi_array[$row_liste_suivi["numero"]][$row_liste_suivi["id_suivi"]]=array($row_liste_suivi["date_dossier"],$row_liste_suivi["expediteur"]);
  }
  else
  {
    if(!isset($suivi_array[$row_liste_suivi["numero"]])) $suivi_array[$row_liste_suivi["numero"]]=array($row_liste_suivi["date_dossier"],$row_liste_suivi["expediteur"]);
  }
}while($row_liste_suivi  = mysql_fetch_assoc($liste_suivi));
}

//Dernier traitement dossier
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_last_traitement_dossier ="SELECT numero, date_dossier, destinataire FROM ".$database_connect_prefix."suivi_workflow order by id_suivi desc";
$last_traitement_dossier = mysql_query($query_last_traitement_dossier, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_last_traitement_dossier = mysql_fetch_assoc($last_traitement_dossier);
$totalRows_last_traitement_dossier = mysql_num_rows($last_traitement_dossier);
$date_suivi_array = array();
$respo_suivi_array = array();
if($totalRows_last_traitement_dossier>0){ do{
 //$date_suivi_array[$row_last_traitement_dossier["numero"]]=$row_last_traitement_dossier["date_dossier"]);
if(!isset($date_suivi_array[$row_last_traitement_dossier["numero"]])) $date_suivi_array[$row_last_traitement_dossier["numero"]]=$row_last_traitement_dossier["date_dossier"];
if(!isset($respo_suivi_array[$row_last_traitement_dossier["numero"]])) $respo_suivi_array[$row_last_traitement_dossier["numero"]]=$row_last_traitement_dossier["destinataire"];

}while($row_last_traitement_dossier  = mysql_fetch_assoc($last_traitement_dossier));
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
        "iDisplayLength": -1,
        paging: false
    });
});
</script>
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive table-tabletools table-colvis datatable dataTable" align="center" id="mtable<?php echo $id_file; ?>" >
            <thead>
                <tr>
                  <!--<td><div align="left"><strong>Responsable</strong></div></td> -->
                  <td width="80"><div align="left"><strong>N&deg;</strong></div></td>
                  <td><div align="left"><strong>Type</strong></div></td>
                  <td width="50"><div align="left"><strong>Date</strong></div></td>
                  <td><div align="left"><strong>Nom</strong></div></td>
                  <td><div align="left"><strong>Contenu</strong></div></td>
                  <td width="100"><div align="left"><strong>Nbr. jour</strong></div></td>
                  <td><div align="center"><strong>Traitement</strong></div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1) ) { ?>
                  <td width="90"><div align="center"><strong>Actions</strong></div></td>
                  <?php } ?>
                </tr>
            </thead>
         <?php if($totalRows_liste_dossier>0) {$i=0;do { $id = $row_liste_dossier['id_dossier']; $num = $row_liste_dossier['numero']; $fdest=$row_liste_dossier['expediteur']; 
		
		  $b = explode('|', $dureeworkflow_array[$row_liste_dossier["type_dossier"]]);
 $dti=0; foreach($b as $dt) {$dti=$dti+$dt;  } ;
		 
		  ?>
                <tr>
                  <!--<td><div align="left"><img src="images/petit_image/inscription_p.jpg" width="30" height="30" align="left" hspace="5" vspace="5" alt="photo"><?php //echo (isset($destinateur_array[$row_liste_dossier['expediteur']]))?$destinateur_array[$row_liste_dossier['expediteur']]:"NaN"; echo "<br><i>".$fonction_array[$row_liste_dossier['expediteur']]."</i>"; ?></div></td>-->
                  <td><div align="left"><?php echo $num; ?></div></td>
                  <td><div align="left"><?php if(isset($docworkflow_type_array[$row_liste_dossier['type_dossier']])) echo $docworkflow_type_array[$row_liste_dossier['type_dossier']]; else echo "NaN"; ?></div></td>
                  <td><div align="left"><?php echo date_reg($row_liste_dossier['date_dossier'],'/'); ?></div></td>
                  <td><div align="left"><?php echo $row_liste_dossier['nom']; ?></div></td>
                  <td align="center">
<a onclick="get_content('body_workflow.php','id=<?php echo $id; ?>&doc=1','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Contenu du message" class="" dir=""> Aper&ccedil;u </a>
                </td>
                <td><div align="left"><b><span <?php if(isset($date_suivi_array[$row_liste_dossier["numero"]])) $last_date=$date_suivi_array[$row_liste_dossier["numero"]]; else $last_date=date("Y-m-d"); if(NbJours($row_liste_dossier['date_dossier'], $last_date)>$dti) { ?> style="background-color:#FF0000; color:#FFFFFF" <?php } ?> >&nbsp;&nbsp;<?php if(isset($date_suivi_array[$row_liste_dossier["numero"]])) echo NbJours($row_liste_dossier['date_dossier'], $date_suivi_array[$row_liste_dossier["numero"]]); else echo NbJours($row_liste_dossier['date_dossier'], $last_date); ?>&nbsp;&nbsp;</span></b> (<?php echo $dti; ?>)</div></td>
                <td>
                  
                  <div align="center"><a onclick="get_content('edit_suivi_workflow.php','<?php echo "numero=$num&fdest=$fdest"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Suivre de dossier" class="" dir=""> Au niveau de: <?php if(isset($respo_suivi_array[$row_liste_dossier["numero"]])) echo $respo_suivi_array[$row_liste_dossier["numero"]]; else echo $row_liste_dossier['expediteur']; ?> </a>
                    
                  </div></td><?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<td align="center">
<?php if($row_liste_dossier['traitement']==0) { ?>
<a href="#myModal_add" data-backdrop="static" data-keyboard="false" data-toggle="modal" title="Edition de document <?php echo $num; ?>" onclick="get_content('new_workflow.php','<?php echo "id=$id"; ?>','modal-body_add',this.title,'iframe');" style="margin:0px 5px;"><img src="./images/edit.png" width="20" height="20" alt="Modifier" title="Modifier"></a>
<a href="./workflow.php?id_sup=<?php echo "$id"; ?>" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer ce dossier ?');" style=""><img src="./images/delete.png" width="20" height="20" alt="Supprimer" title="Supprimer"></a>
 <?php } ?>
 </td>
 <?php } ?>
	    </tr>
                <?php } while ($row_liste_dossier = mysql_fetch_assoc($liste_dossier)); ?>
                <?php } ?>
</table>  <?php include 'modal_add.php'; ?>