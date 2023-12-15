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

$dir = './attachment/workflow/';

function NbJours($debut, $fin)
{
  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);
  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) -
          mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
  return(($diff / 86400)+1);
}

if(isset($_GET["numero"])){ $numero=$_GET['numero'];} 
if(isset($_GET["fdest"])){ $fdest=$_GET['fdest'];}
if(isset($_GET["mod"])) $mod="&mod=1"; $mod="";
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

if ((isset($_GET["id_sup"]) && intval($_GET["id_sup"])>0)) {
  $id = intval($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."suivi_workflow WHERE id_suivi=%s",
                       GetSQLValueString($id, "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  $insertGoTo .= "&numero=$numero&mod=1";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $personnel=$_SESSION['clp_id']; $date = date("Y-m-d");
    //Archivage
    if(isset($_POST['destinataire']) && $_POST['destinataire']==1)
    {
      $insertSQL = sprintf("UPDATE ".$database_connect_prefix."workflow SET traitement=1, modifier_par='$personnel', modifier_le='$date' WHERE numero=%s",
                          GetSQLValueString($_POST['numero'], "text"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result2 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    }
   
      $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."suivi_workflow (numero, expediteur, destinataire, message, documents, date_dossier, id_personnel) VALUES (%s, %s, %s, %s, %s, '$date', '$personnel')",
    					    GetSQLValueString($_POST['numero'], "text"),
							 GetSQLValueString($_POST['ldest'], "text"),
                          GetSQLValueString($_POST['destinataire'], "text"),
                          GetSQLValueString($_POST['message'], "text"),
                          GetSQLValueString($_POST['documents'], "text"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
   

    $insertGoTo = $_SERVER['PHP_SELF'];
    if($Result1) $insertGoTo = "?insert=ok"; else $insertGoTo = $page."?insert=no";
    $insertGoTo .= "&numero=$numero&annee=$annee&mod=1";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
      $id = $_POST["MM_delete"];
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."suivi_workflow WHERE id_suivi=%s",
                           GetSQLValueString($id, "text"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      $insertGoTo .= "&numero=$numero&annee=$annee&mod=1";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];  $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."suivi_workflow SET numero=%s, expediteur=%s, destinataire=%s, message=%s, documents=%s, modifier_par='$personnel', modifier_le='$date' WHERE id_suivi=%s",
  					    GetSQLValueString($_POST['numero'], "text"),
						 GetSQLValueString($_POST['ldest'], "text"),
                        GetSQLValueString($_POST['destinataire'], "text"),
                        GetSQLValueString($_POST['message'], "text"),
                        GetSQLValueString($_POST['documents'], "text"),
                        GetSQLValueString($id, "int"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    $insertGoTo = $_SERVER['PHP_SELF'];
    if($Result1) $insertGoTo = "?update=ok"; else $insertGoTo = $page."?update=no";
    $insertGoTo .= "&numero=$numero&annee=$annee&mod=1";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

//Dernier traitement dossier
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_responsable_suivi_dossier ="SELECT destinataire, numero, date_dossier FROM ".$database_connect_prefix."suivi_workflow WHERE numero='$numero' order by id_suivi desc limit 1";
$responsable_suivi_dossier = mysql_query($query_responsable_suivi_dossier, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_responsable_suivi_dossier = mysql_fetch_assoc($responsable_suivi_dossier);
$totalRows_responsable_suivi_dossier = mysql_num_rows($responsable_suivi_dossier);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_dossier = "SELECT * FROM ".$database_connect_prefix."workflow WHERE numero='$numero' LIMIT 1";
$dossier = mysql_query($query_dossier, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_dossier = mysql_fetch_assoc($dossier);
$totalRows_dossier = mysql_num_rows($dossier);
//echo $row_dossier["traitement"];
$expediteur = $row_dossier["expediteur"]; $doc_type = $row_dossier["type_dossier"];

if(isset($row_responsable_suivi_dossier["destinataire"])) $last_respo_doc=$row_responsable_suivi_dossier["destinataire"]; else $last_respo_doc=$expediteur;
if(isset($row_responsable_suivi_dossier["date_dossier"])) $last_date=$row_responsable_suivi_dossier["date_dossier"]; else $last_date=date("Y-m-d");

//echo $last_respo_doc;

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_edit_suivi_dossier = "SELECT * FROM ".$database_connect_prefix."suivi_workflow WHERE id_suivi='$id' ";
  $edit_suivi_dossier = mysql_query($query_edit_suivi_dossier, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_edit_suivi_dossier = mysql_fetch_assoc($edit_suivi_dossier);
  $totalRows_edit_suivi_dossier = mysql_num_rows($edit_suivi_dossier);
}




  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_docworkflow = "SELECT code,responsable_concerne,duree FROM ".$database_connect_prefix."type_doc_workflow WHERE code='$doc_type' LIMIT 1 ";
  $liste_docworkflow  = mysql_query($query_liste_docworkflow , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_docworkflow = mysql_fetch_assoc($liste_docworkflow);
  $totalRows_liste_docworkflow  = mysql_num_rows($liste_docworkflow);
  $docworkflow_array = array(); $fonction=0;
  if($totalRows_liste_docworkflow>0){
    $a = explode('|',$row_liste_docworkflow["responsable_concerne"]);
    foreach($a as $c) $docworkflow_array[] = "'$c'";
    $fonction = implode(',',$docworkflow_array);
  }

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_sdossier = "SELECT * FROM ".$database_connect_prefix."suivi_workflow WHERE numero='$numero' ORDER BY id_suivi desc";
  $sdossier = mysql_query($query_sdossier, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_sdossier = mysql_fetch_assoc($sdossier);
  $totalRows_sdossier = mysql_num_rows($sdossier);
  $s_expediteur = ($totalRows_sdossier>0)?$row_sdossier["destinataire"]:$expediteur;

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  if(isset($_GET["id"]) && !empty($_GET["id"]))
  $query_liste_expediteur = "SELECT fonction, N,nom,prenom FROM ".$database_connect_prefix."personnel WHERE fonction in ($fonction) ";
  else
  $query_liste_expediteur = "SELECT fonction, N,nom,prenom FROM ".$database_connect_prefix."personnel WHERE fonction in ($fonction) and `fonction`<>'$s_expediteur'  ";
  $liste_expediteur = mysql_query($query_liste_expediteur, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_expediteur = mysql_fetch_assoc($liste_expediteur);
  $totalRows_liste_expediteur = mysql_num_rows($liste_expediteur);

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_sdossier = "SELECT * FROM  ".$database_connect_prefix."suivi_workflow  where numero='$numero' order by id_suivi desc";
  $sdossier = mysql_query($query_sdossier, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_sdossier = mysql_fetch_assoc($sdossier);
  $totalRows_sdossier = mysql_num_rows($sdossier);

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_user = "SELECT N,nom,prenom,fonction FROM ".$database_connect_prefix."personnel ";
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
 
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_docworkflow = "SELECT code,intitule,responsable_concerne,duree FROM ".$database_connect_prefix."type_doc_workflow WHERE code='$doc_type' LIMIT 1 ";
  $liste_docworkflow  = mysql_query($query_liste_docworkflow , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_docworkflow = mysql_fetch_assoc($liste_docworkflow);
  $totalRows_liste_docworkflow  = mysql_num_rows($liste_docworkflow);
  $docworkflow_array = $fonction_ar = $docworkflow_type_array = array(); $fonction = 0;
  if($totalRows_liste_docworkflow>0){
    $a = explode('|',$row_liste_docworkflow["responsable_concerne"]);
    $b = explode('|',$row_liste_docworkflow["duree"]);
    foreach($a as $c=>$d){ $docworkflow_array[$d] = $b[$c]; $fonction_ar[] = "'$d'"; }
    $docworkflow_type_array[$row_liste_docworkflow["code"]] = $row_liste_docworkflow["intitule"];

    foreach($a as $c)
    $fonction = implode(',',$fonction_ar);
  }
  $dti=0; foreach($b as $dt) {$dti=$dti+$dt; } ;
   
 

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_responsable = "SELECT distinct fonction, N FROM ".$database_connect_prefix."personnel WHERE fonction in ($fonction) ORDER BY fonction asc";
  $liste_responsable  = mysql_query($query_liste_responsable , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_responsable  = mysql_fetch_assoc($liste_responsable);
  $totalRows_liste_responsable  = mysql_num_rows($liste_responsable);
  $fonction_array = array();
  if($totalRows_liste_responsable>0){ do{ $fonction_array[$row_liste_responsable["fonction"]]=$row_liste_responsable["fonction"]; }while($row_liste_responsable  = mysql_fetch_assoc($liste_responsable)); }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->
<link rel="stylesheet" type="text/css" href="<?php print $config->theme_folder;?>/plugins/jquery-ui.css"/>
<link href="<?php print $config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder;?>/responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder;?>/icons.css" rel="stylesheet" type="text/css"/>
<link href='<?php print $config->theme_folder;?>/css.css' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
<link href="<?php print $config->theme_folder; ?>/plugins/datatables_bootstrap.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder; ?>/plugins/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder; ?>/plugins/wysiwyg-color.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>
<script type="text/javascript" src="plugins/noty/layouts/top.js"></script>
<script type="text/javascript" src="plugins/noty/themes/default.js"></script>
<script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="plugins/bootstrap-wysihtml5/wysihtml5.min.js"></script>
<script type="text/javascript" src="plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.min.js"></script>
<?php //if((!isset($_GET['add']) && isset($_GET['doc'])) || (isset($_GET['add']) && !isset($_GET['doc']))) { ?>
<script type="text/javascript" src="plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>
<script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>
<?php //} ?>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
        $(".modal-dialog", window.parent.document).width(700);
<?php if(isset($_GET['mod'])) { ?>
        // reload parent frame
        $(".close", window.parent.document).click(function(){window.parent.location.reload();});
        $("button[data-dismiss='modal']", window.parent.document).click(function(){window.parent.location.reload();});
<?php } ?>
        $(".wysiwyg").each(function(){$(this).wysihtml5({parser: function(html) {return html;}});});
        $(".wysihtml5-toolbar").each(function(){$(this).addClass('hidden');});
        $(".wysihtml5-toolbar-edit").each(function(){$(this).attr('style','cursor:pointer;');$(this).click(function(){$(".wysihtml5-toolbar").toggleClass('hidden');});});
<?php if((!isset($_GET['add']) && isset($_GET['doc'])) || (isset($_GET['add']) && !isset($_GET['doc']))) { ?>
$(".dataTable").dataTable({"iDisplayLength": -1});

<?php } ?>
<?php if(isset($_GET['add'])) { ?>
        $(".modal-dialog", window.parent.document).width(600);
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
<?php } ?>
	});
</script>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}
.dataTables_length, .dataTables_info { float: left; font-size: 10px;}
.dataTables_length, .dataTables_paginate { display: none;}

@media(min-width:558px){.col-md-12 {width: 100%;}.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}
</style>
</head>
<body>
<?php if(!isset($_GET['add']) || $row_dossier['traitement']==1) { ?>
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> Dossier N°: <?php echo $row_dossier['numero']; ?></h4> <b> (Au niveau de: <?php  echo $last_respo_doc; ?>)</b>
 <?php if(isset($row_dossier['traitement']) && $row_dossier['traitement']==0) {  ?><?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1 && $_SESSION['clp_fonction']==$last_respo_doc){ ?> 
<a href="<?php echo $_SERVER['PHP_SELF']."?add=1&numero=$numero"; ?>" class="pull-right p11" title="Ajout une suivi de document" ><i class="icon-plus"> Nouveau traitement </i></a>
<?php } ?><?php } ?>
</div>
<div class="widget-content">
<div><b>Type : <?php if(isset($docworkflow_type_array[$doc_type])) echo $docworkflow_type_array[$doc_type]; else echo "NaN"; ?></b></div>
<div><b>Libellé : <?php echo $row_dossier['nom']; ?></b></div> 
<div><b>Initié le : <?php echo date_reg($row_dossier['date_dossier'],"/"); ?>&nbsp;&nbsp;&nbsp;&nbsp;=======>&nbsp;<span <?php if(NbJours($row_dossier['date_dossier'], $last_date)>$dti) {?> style="background-color:#FF0000; color:#FFFFFF" <?php } ?> >&nbsp;&nbsp;<?php echo NbJours($row_dossier['date_dossier'], $last_date); ?>&nbsp;&nbsp;</span> /  <?php echo $dti; ?> </b></div>
 <br />

<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >
            <thead>
               
                <tr>
                  <td>&nbsp;</td>
                  <td><div align="left"><strong>Responsable</strong></div></td>
                  <td><div align="center"><strong>R&eacute;ception</strong></div></td>
                  <td><div align="center"><strong>Traitement</strong></div></td>
                  <td><div align="left"><strong>Dur&eacute;e </strong>(<em>jours</em>)</div></td>
                  <td><div align="left"><strong>Contenu</strong></div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1) && $row_dossier['traitement']==0) { ?>
                  <td align="center" width="80" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
                <?php if($totalRows_sdossier>0) {$i=0;do { $id = $row_sdossier['id_suivi']; if($i==0) $date_recu = $row_dossier['date_dossier']; ?>
                <tr>
                  <td><?php echo $row_sdossier['id_suivi']; ?></td>
                    <td><div align="left"><?php echo (isset($row_sdossier['expediteur']))?$row_sdossier['expediteur']:"NaN"; ?></div></td>
                  <td><div align="left"><?php echo date_reg($date_recu,"/"); ?></div></td>
                  <td><div align="left"><?php echo date_reg($row_sdossier['date_dossier'],"/"); ?></div></td>
                  <td><div align="left"><b><?php echo NbJours($date_recu,$row_sdossier['date_dossier']); ?> / <?php if(isset($docworkflow_array[$row_sdossier['expediteur']])) echo $docworkflow_array[$row_sdossier['expediteur']]; else echo "NaN"; ?>
                  </b></div></td>
                  <td><div align="center"><?php
echo do_link("","","Contenu du message","Aper&ccedil;u","","./","","get_content('body_workflow.php','id=$id&suivi=1','modal-body_add',this.title);",1,"",$nfile); ?></div>                </td>
				   <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1 || $_SESSION['clp_fonction']==$row_sdossier['expediteur']) && $row_dossier['traitement']==0) { ?>
<td align="center" nowrap="nowrap">
<?php 
echo do_link("",$_SERVER['PHP_SELF']."?id=$id&numero=$numero&add=1","Modifier suivi","","edit","./","","",0,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id."&numero=$numero","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette suivi ?');",0,"margin:0px 5px;",$nfile); ?></td>
				   <?php } ?>
	    </tr>
                <?php $i++; $date_recu = $row_sdossier['date_dossier']; } while ($row_sdossier = mysql_fetch_assoc($sdossier)); ?>
                <?php } else { ?>
<tr>
  <td align="center">&nbsp;</td>
  <td align="center" colspan="<?php echo (isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1))?6:5; ?>">Aucun r&eacute;sultat !</td></tr>
                <?php } ?>
              </table>

</div></div>
</div>
<?php } else { ?>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Personne responsable: <?php  echo $last_respo_doc; ?></h4>
<a href="<?php echo $_SERVER['PHP_SELF']."?numero=$numero"; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>

<div class="widget-content">
<form action="<?php echo $_SERVER['PHP_SELF']."?numero=$numero"; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">

<input class="form-control required" type="hidden" name="numero" id="numero" value="<?php echo $numero; ?>" size="32" />

  <table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:10px;">
        <tr valign="top">
      <td colspan="2"><div class="form-group">
          <label for="message" class="col-md-12 control-label">Traitement effectu&eacute; <span class="required">*</span> <span class="pull-right wysihtml5-toolbar-edit">Edition (Affich./Masquer)</span></label>
          <div class="col-md-12">
            <textarea class="form-control wysiwyg required" id="message" name="message" rows="3" cols="25"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_suivi_dossier['message']; ?></textarea>
          </div>
      </div></td>
    </tr>
    <tr valign="top">
      <td><div class="form-group">
          <?php //echo do_link("","","Ajout de fichier","Ajout de fichier","","./","pull-right","get_content('new_document.php','dir=$dir&page=".$_SERVER['PHP_SELF']."','modal-body_add',this.title);",1,"",$nfile); ?>
          <div class="col-md-12">
            <div style="font-size: medium;">
              <?php $c = "";
echo "<b>".do_link("","","Pi&egrave;ces jointes","Pi&egrave;ces jointes","","./","","get_content('list_attachments.php','dir=$dir&doc=documents&page=".$_SERVER['PHP_SELF']."','modal-body_add',this.title,'iframe');",1,"",$nfile);
if(!empty($row_edit_suivi_dossier["documents"]))
{
  $a = explode('|',$row_edit_suivi_dossier["documents"]);
  echo " : <span id='documents_zone'>".(count($a)-1)." fichier".((count($a)-1>1)?'s':'')."</span></b>";
  foreach($a as $b)
  if(!empty($b)) { //echo "<a style='' href='./download_file.php?file=$dir$b' title='T&eacute;l&eacute;charger' alt='$b'>$b</a>&nbsp;&nbsp;&nbsp;";
    $c .= $b.'|'; }
  //echo "<div style='clear:both; height:0px;'><hr></div>";
}else  echo " : <span id='documents_zone'>Aucun</span></b>";
?>
            </div>
            <input type="hidden" id="documents" name="documents" value="<?php echo $c; ?>" />
          </div>
      </div></td>
    </tr>
	<tr valign="top">
      <td colspan="2" valign="top">  <div class="form-group">
        <label for="destinataire" class="col-md-12 control-label">Action suivante<span class="required">*</span></label>
        <div class="col-md-12">
          <select name="destinataire" id="destinataire" class="form-control required">
            <option value="">Selectionnez</option>
            <optgroup label="Transférer à">
              <?php if($totalRows_liste_expediteur>0){ do { ?>
              <option value="<?php echo $row_liste_expediteur['fonction'];?>" <?php if (isset($row_edit_suivi_dossier['expediteur']) && $row_liste_expediteur['fonction']==$row_edit_suivi_dossier['expediteur']) {echo "SELECTED";} ?> ><?php echo $row_liste_expediteur['fonction'];//echo $row_liste_expediteur['prenom']." ".$row_liste_expediteur['nom']; ?></option>
              <?php  } while ($row_liste_expediteur = mysql_fetch_assoc($liste_expediteur)); } ?>
              </optgroup>
            <option value="1">Terminer le processus</option>
            </select>
          </div>
      </div></td>
        </tr>
  </table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "Modifier";else echo "Enregistrer";?>" />
  <input name="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "MM_update";else echo "MM_insert";?>" type="hidden" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]);else echo "MM_insert";?>" size="32" alt="">
<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) {?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce suivi de dossier ?','<?php echo ($_GET["id"]);?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php }?>
  <input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <input name="ldest" type="hidden"  value="<?php  echo $last_respo_doc; ?>" />
</div>
</form>

</div>
</div>
<?php } ?>
<?php } ?>
<?php include_once 'modal_add.php'; ?>
</body>
</html>