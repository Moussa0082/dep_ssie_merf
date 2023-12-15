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

if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else {$annee=date("Y");}

//fonction calcul nb jour
function NbJours($debut, $fin) {
  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);
  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
  return(($diff / 86400)+1);
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_conv = "SELECT distinct ".$database_connect_prefix."dno.* FROM ".$database_connect_prefix."dno where year(".$database_connect_prefix."dno.date_initialisation)=$annee and ".$database_connect_prefix."dno.projet='".$_SESSION["clp_projet"]."' and traitement=1 ORDER BY numero desc";
$liste_conv = mysql_query($query_liste_conv, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_conv = mysql_fetch_assoc($liste_conv);
$totalRows_liste_conv = mysql_num_rows($liste_conv);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_bailleur = "SELECT * FROM ".$database_connect_prefix."partenaire WHERE dno=1 ";
$liste_bailleur = mysql_query($query_liste_bailleur, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
$totalRows_liste_bailleur = mysql_num_rows($liste_bailleur);
$destinateur_array = array();
if($totalRows_liste_bailleur>0){ do{
  $destinateur_array[$row_liste_bailleur["code"]] = $row_liste_bailleur["sigle"];
}while($row_liste_bailleur = mysql_fetch_assoc($liste_bailleur)); }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_ano = "SELECT dno, phase, date_phase FROM ".$database_connect_prefix."suivi_dno order by dno, date_phase, id_suivi asc";
$edit_ano = mysql_query($query_edit_ano, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_ano = mysql_fetch_assoc($edit_ano);
$totalRows_edit_ano = mysql_num_rows($edit_ano);
$tableau_date_envoi = array();
$tableau_date_ano = array();
$tableau_date_rejet = array();
$tableau_date_renvoi = array();
$tableau_date_retour = array();
if($totalRows_edit_ano>0){  do{
  if($row_edit_ano["phase"]=="ANO") $tableau_date_ano[$row_edit_ano["dno"]]=$row_edit_ano["date_phase"];
  if($row_edit_ano["phase"]=="Envoi au bailleur") $tableau_date_envoi[$row_edit_ano["dno"]]=$row_edit_ano["date_phase"];
    if($row_edit_ano["phase"]=="Objection du bailleur") $tableau_date_rejet[$row_edit_ano["dno"]]=$row_edit_ano["date_phase"];
	 if($row_edit_ano["phase"]=="Renvoi au bailleur") $tableau_date_renvoi[$row_edit_ano["dno"]]=$row_edit_ano["date_phase"];
	  if($row_edit_ano["phase"]=="Retour du bailleur") $tableau_date_retour[$row_edit_ano["dno"]]=$row_edit_ano["date_phase"];

   }while($row_edit_ano = mysql_fetch_assoc($edit_ano));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_activite = "SELECT id_ptba, code_activite_ptba, intitule_activite_ptba FROM ".$database_connect_prefix."ptba where annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."'  ORDER BY code_activite_ptba asc";
$liste_activite   = mysql_query($query_liste_activite , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_activite   = mysql_fetch_assoc($liste_activite);
$totalRows_liste_activite   = mysql_num_rows($liste_activite);
$activite_array1 = array();
if($totalRows_liste_activite>0){ do{
  $activite_array1[$row_liste_activite["code_activite_ptba"]] = $row_liste_activite["intitule_activite_ptba"];
  //echo  $activite_array1[$row_liste_activite["code_activite_ptba"]];
}while($row_liste_activite = mysql_fetch_assoc($liste_activite)); }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_phase_dano = "select * from ".$database_connect_prefix."suivi_dno order by dno, date_phase, id_suivi asc";
$liste_phase_dano = mysql_query($query_liste_phase_dano, $pdar_connexion) or die(mysql_error());
$row_liste_phase_dano = mysql_fetch_assoc($liste_phase_dano);
$totalRows_liste_phase_dano = mysql_num_rows($liste_phase_dano);
$tableau_phase_dano= array();
$tableau_date_phase_dano= array();
if($totalRows_liste_phase_dano>0){  do{ //if(!isset($tableau_obs[$row_liste_rubrique["dno"]])) $tableau_obs[$row_liste_rubrique["dno"]]="";
  $tableau_phase_dano[$row_liste_phase_dano["dno"]]=$row_liste_phase_dano["phase"];
   $tableau_date_phase_dano[$row_liste_phase_dano["dno"]]=$row_liste_phase_dano["date_phase"];
  }while($row_liste_phase_dano = mysql_fetch_assoc($liste_phase_dano));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rubrique = "select * from ".$database_connect_prefix."suivi_dno order by dno, date_phase desc";
$liste_rubrique = mysql_query($query_liste_rubrique, $pdar_connexion) or die(mysql_error());
$row_liste_rubrique = mysql_fetch_assoc($liste_rubrique);
$totalRows_liste_rubrique = mysql_num_rows($liste_rubrique);
$tableau_stat = $tableau_obs = $tableau_persp = array();
if($totalRows_liste_rubrique>0){  do{ if(!isset($tableau_obs[$row_liste_rubrique["dno"]])) $tableau_obs[$row_liste_rubrique["dno"]]="";
  $tableau_stat[$row_liste_rubrique["dno"]]=$row_liste_rubrique["phase"];
  $tableau_persp[$row_liste_rubrique["dno"]]=$row_liste_rubrique["observation"];
  $tableau_obs[$row_liste_rubrique["dno"]].="<u>".implode('-',array_reverse(explode('-',$row_liste_rubrique["date_phase"])))."</u>: (<b>".$row_liste_rubrique["phase"]."</b>)<i> ".$row_liste_rubrique["observation"]."&nbsp;    </br></i>"; }while($row_liste_rubrique = mysql_fetch_assoc($liste_rubrique));
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

<a href="#myModal_add" data-backdrop="static" data-keyboard="false" data-toggle="modal" class="pull-right p11" title="Edition des DANO" onclick="get_content('edit_dno.php','annee=<?php echo $annee; ?>','modal-body_add',this.title,'iframe');" style="margin-top:-5px;"><i class="icon-plus"> Nouvelle DANO </i></a>
<div class="clear h0">&nbsp;</div>

<table class="table table-striped table-bordered table-hover table-responsive table-tabletools table-colvis datatable dataTable" id="mtable<?php echo $annee; ?>" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">N&deg;</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Type de requ&ecirc;te </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Objet</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"> Reception </th>
<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Envoi</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Retour</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Renvoi</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Statut</th>-->
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Envoi/renvoi</th>
<?php if(!isset($_GET["dano_id"])){ ?>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"> ANO</th>
<?php } ?>
<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Objection</th>-->
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Dur&eacute;e (J)</th>
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) {?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="100">Actions</th>
<?php }?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="">
<?php if($totalRows_liste_conv>0) { $i=0; do { $id = $row_liste_conv['numero'];
 $denvoi=date("Y-m-d");
/* if(isset($tableau_date_renvoi[$row_liste_conv['numero']])) {$denvoi=$tableau_date_renvoi[$row_liste_conv['numero']]; }
  elseif(isset($tableau_date_envoi[$row_liste_conv['numero']])) $denvoi=$tableau_date_envoi[$row_liste_conv['numero']];
   else $denvoi=date("Y-m-d"); 
   if(isset($tableau_phase_dano[$id]) && $tableau_phase_dano[$id]=='Retour du bailleur') {$daterecp=$tableau_date_phase_dano[$id]; $denvoi=date("Y-m-d"); }
   elseif(isset($tableau_date_retour[$row_liste_conv['numero']])) {$daterecp=$tableau_date_retour[$row_liste_conv['numero']]; }
   else $daterecp=$row_liste_conv['date_initialisation'];
   if($denvoi>=$daterecp)$Nombres_jourse = NbJours($daterecp, $denvoi); else $Nombres_jourse="  ???";*/
?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo $row_liste_conv['numero']; ?></td>
<td class=" "><div  title=" <?php if(isset($activite_array1[$row_liste_conv["code_activite"]])) echo $activite_array1[$row_liste_conv["code_activite"]]; else echo "Aucune activite de PTBA liee"; ?>"><?php if(isset($row_liste_conv["type_requete"])) echo $row_liste_conv["type_requete"]; ?> <?php echo (isset($activite_array1[$row_liste_conv["code_activite"]]))?" (<em>=>".$row_liste_conv["code_activite"]."</em>)":''; ?>  <?php echo (isset($destinateur_array[$row_liste_conv['destinataire']]))?" (<em>".$destinateur_array[$row_liste_conv['destinataire']]."</em>)":''; ?></div></td>
<td class=" "><?php echo $row_liste_conv['objet']; ?></td>
<td class=" "><?php if(isset($tableau_date_retour[$id])) echo date_reg($tableau_date_retour[$id],'/'); else echo date_reg($row_liste_conv['date_initialisation'],'/'); ?></td>
<!--
<td class=" "><?php if(isset($tableau_date_envoi[$id])) echo date_reg($tableau_date_envoi[$id],'/'); ?></td>
<td class=" "><?php if(isset($tableau_date_retour[$id])) echo date_reg($tableau_date_retour[$id],'/'); ?></td>
<td class=" "><?php if(isset($tableau_date_renvoi[$id])) echo date_reg($tableau_date_renvoi[$id],'/'); ?></td>



<td class=" "><?php if(isset($tableau_phase_dano[$id]) && isset($tableau_date_phase_dano[$id])) echo $tableau_phase_dano[$id]; ?></td>
-->
<td class=" ">
<?php 
if(isset($tableau_phase_dano[$id]) && ($tableau_phase_dano[$id])=="Retour du bailleur")
{ if(date("Y-m-d")>=$tableau_date_phase_dano[$id] && NbJours($tableau_date_phase_dano[$id], date("Y-m-d"))>3) echo " <span style=\"background-color:#FF0000; color:#FFFFFF; display:block;\"><span class=\"hidden\">1</span>En attente de renvoi</div>"; elseif(date("Y-m-d")<$tableau_date_phase_dano[$id]) echo " <span style=\"color:#FF0000;\">?...?</div>"; else echo "En attente de renvoi";
 }
elseif(isset($tableau_phase_dano[$id]) && ($tableau_phase_dano[$id])=="Renvoi au bailleur")
{
 echo date_reg($tableau_date_phase_dano[$id],'/'); $denvoi=$tableau_date_phase_dano[$id];
 }
elseif(isset($tableau_phase_dano[$id]) && ($tableau_phase_dano[$id])=="Envoi au bailleur")
{ echo date_reg($tableau_date_phase_dano[$id],'/'); $denvoi=$tableau_date_phase_dano[$id];
}
elseif(isset($tableau_phase_dano[$id]) && ($tableau_phase_dano[$id]=="ANO" || $tableau_phase_dano[$id]=="Objection du bailleur") )
{
if(isset($tableau_date_renvoi[$row_liste_conv['numero']])) {$denvoi=$tableau_date_renvoi[$row_liste_conv['numero']]; }
  elseif(isset($tableau_date_envoi[$row_liste_conv['numero']])) $denvoi=$tableau_date_envoi[$row_liste_conv['numero']];
 echo date_reg($denvoi,'/');
}
else 
{ if(date("Y-m-d")>=$row_liste_conv['date_initialisation'] && NbJours($row_liste_conv['date_initialisation'], date("Y-m-d"))>3) echo " <span style=\"background-color:#FF0000; color:#FFFFFF; display:block;\"><span class=\"hidden\">2</span>En attente d'envoi</div>"; elseif(date("Y-m-d")<$row_liste_conv['date_initialisation']) echo " <span style=\"color:#FF0000;\">?...?</div>"; else echo "En attente d'envoi";
 }
 ?></td>
 <?php
if(isset($tableau_date_ano[$row_liste_conv['numero']])) $dano=$tableau_date_ano[$row_liste_conv['numero']]; elseif(isset($tableau_date_rejet[$row_liste_conv['numero']])) $dano=$tableau_date_rejet[$row_liste_conv['numero']]; else $dano=date("Y-m-d"); if (isset($denvoi)) { $Nombres_jours = NbJours($denvoi, $dano);}?>
<?php if(!isset($_GET["dano_id"])){ ?>
<td class=" ">
<?php
// if(isset($tableau_date_ano[$id])) echo date_reg($tableau_date_ano[$id],'/');  elseif(isset($tableau_date_rejet[$id])) echo date_reg($tableau_date_rejet[$id],'/'); 

if(isset($tableau_date_ano[$id]) ) echo "<div align=\"center\" style=\" background-color:#00FF33; \">".date_reg($tableau_date_ano[$row_liste_conv['numero']],'/')."</div>"; elseif(isset($tableau_date_rejet[$row_liste_conv['numero']]) ) { echo "<div style=\" background-color:#FF0000; color:#FFFFFF;\">".date_reg($tableau_date_rejet[$row_liste_conv['numero']],'/')."</div>";}  elseif($Nombres_jours>4) echo "<div align=\"center\" style=\" background-color:#FF0000;color:#FFFFFF; \"><span class=\"hidden\">1</span>-</div>"; elseif($Nombres_jours>3 && $Nombres_jours<5) echo "<div align=\"center\" style=\" background-color:##FFCC00; \">-</div>"; else echo "<div align=\"center\">-</div>"; ?></td>
<?php } ?>
<!--<td class=" "><?php if(isset($tableau_date_ano[$id])) echo date_reg($tableau_date_ano[$id],'/'); ?></td>-->
<td class=" "><div align="center">
<!--<a onclick="get_content('edit_suivi_dno.php','dno=<?php echo $id; ?>&annee=<?php echo $annee; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" title="Suivi de la DANO N&deg; <?php echo $row_liste_conv['numero']; ?>" class="thickbox Add"  dir=""><?php /* // Affiche 2
if(isset($tableau_date_ano[$row_liste_conv['numero']]) || isset($tableau_date_rejet[$row_liste_conv['numero']])) { if($dano>=$denvoi) {echo number_format($Nombres_jours, 0, ',', ' ')." Jrs";} elseif($dano<$denvoi) {echo "<div style=\"width: 40%; background-color:#FF0000; color:#FFFFFF;\">?...?</div>";}} else echo "Suivre";*/ ?></a>-->
<?php if(isset($row_liste_conv['date_initialisation'])){ $Nombres_joursm = NbJours($row_liste_conv['date_initialisation'], date("Y-m-d")); echo number_format($Nombres_joursm, 0, ',', ' ')." J"; } ?>
</div></td>


<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) {?>
<td align="center" nowrap="nowrap" class=" ">
<?php   
if(!isset($tableau_date_envoi[$row_liste_conv['numero']]) && !isset($tableau_date_renvoi[$row_liste_conv['numero']])){
//echo do_link("","","Envoyer DANO ".$row_liste_conv['numero'],"","send_message","./","","get_content('edit_suivi_dno.php','id=$id&annee=$annee&dno=$id&add=1&msg=1','modal-body_add',this.title,'iframe');",1,"margin:0px 5px 0px 0px;",$nfile);
?>
<a href="#myModal_add" data-backdrop="static" data-keyboard="false" data-toggle="modal" title="Envoyer DANO <?php echo $row_liste_conv['numero']; ?>" onclick="get_content('edit_suivi_dno.php','<?php echo "id=$id&annee=$annee&dno=$id&add=1&msg=1"; ?>','modal-body_add',this.title,'iframe');" style="margin:0px 5px 0px 0px;"><img src="./images/send_message.png" width="20" height="20" alt="Envoyer" title="Envoyer"></a>
<?php }
elseif(isset($tableau_phase_dano[$row_liste_conv["numero"]]) && $tableau_phase_dano[$row_liste_conv["numero"]]=="Renvoi au bailleur"){
//echo do_link("","","Envoyer DANO ".$row_liste_conv['numero'],"","send_message","./","","get_content('edit_suivi_dno.php','id=$id&annee=$annee&dno=$id&add=1&msg=1&msg2=1','modal-body_add',this.title,'iframe');",1,"margin:0px 5px 0px 0px;",$nfile);
?>
<a href="#myModal_add" data-backdrop="static" data-keyboard="false" data-toggle="modal" title="Envoyer DANO <?php echo $row_liste_conv['numero']; ?>" onclick="get_content('edit_suivi_dno.php','<?php echo "id=$id&annee=$annee&dno=$id&add=1&msg=1&msg2=1"; ?>','modal-body_add',this.title,'iframe');" style="margin:0px 5px 0px 0px;"><img src="./images/send_message.png" width="20" height="20" alt="Envoyer" title="Envoyer"></a>
<?php }

//echo do_link("","","Edition des DANO ".$row_liste_conv['numero'],"","edit","./","","get_content('edit_dno.php','id=".$id."&annee=".$annee."','modal-body_add',this.title,'iframe');",1,"margin:0px 5px;",$nfile);

//echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id."&annee=".$annee,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette DANO ?');",0,"margin:0px 5px 0px 0px;",$nfile);
?>
<a href="#myModal_add" data-backdrop="static" data-keyboard="false" data-toggle="modal" title="Edition des DANO <?php echo $row_liste_conv['numero']; ?>" onclick="get_content('edit_dno.php','<?php echo "id=$id&annee=$annee"; ?>','modal-body_add',this.title,'iframe');" style="margin:0px 5px;"><img src="./images/edit.png" width="20" height="20" alt="Modifier" title="Modifier"></a>

<a href="./liste_dno.php?id_sup=<?php echo "$id&annee=$annee"; ?>" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cette DANO ?');" style=""><img src="./images/delete.png" width="20" height="20" alt="Supprimer" title="Supprimer"></a>
</td>
<?php }?>
</tr>
<?php }while($row_liste_conv  = mysql_fetch_assoc($liste_conv)); } ?>
</tbody></table>
<?php include 'modal_add.php'; ?>