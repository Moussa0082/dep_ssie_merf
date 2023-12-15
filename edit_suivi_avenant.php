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
//header('Content-Type: text/html; charset=ISO-8859-15');

$dir = './attachment/avenant_contrat/';

function NbJours($debut, $fin)
{
  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);
  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) -
          mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
  return(($diff / 86400)+1);
}

if(isset($_GET["numero"])){ $numero=$_GET['numero'];}
if(isset($_GET["mod"])) $mod="&mod=1"; $mod="";
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

if ((isset($_GET["id_sup"]) && intval($_GET["id_sup"])>0)) {
  $id = intval($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."suivi_avenant WHERE id_suivi=%s",
                       GetSQLValueString($id, "int"));

        try{
            $Result1 = $pdar_connexion->prepare($insertSQL);
            $Result1->execute();
        }catch(Exception $e){ die(mysql_error_show_message($e)); }
		  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  $insertGoTo .= "&numero=$numero&mod=1";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $personnel=$_SESSION['clp_id']; $date = date("Y-m-d");
      $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."suivi_avenant (contrat, numero_avenant, montant, date_avenant, documents, date_enregistrement, id_personnel) VALUES (%s, %s, %s, %s, %s, '$date', '$personnel')",
    					    GetSQLValueString($_POST['numero'], "int"),
                          GetSQLValueString($_POST['numero_avenant'], "text"),
						  GetSQLValueString($_POST['montant'], "double"),
						  GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_avenant']))), "date"),
                         // GetSQLValueString($_POST['message'], "text"),
                          GetSQLValueString($_POST['documents'], "text"));

        try{
            $Result1 = $pdar_connexion->prepare($insertSQL);
            $Result1->execute();
        }catch(Exception $e){ die(mysql_error_show_message($e)); }
		   

    $insertGoTo = $_SERVER['PHP_SELF'];
    if($Result1) $insertGoTo = "?insert=ok"; else $insertGoTo = $page."?insert=no";
    $insertGoTo .= "&numero=$numero&annee=$annee&mod=1";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
      $id = $_POST["MM_delete"];
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."suivi_avenant WHERE id_suivi=%s",
                           GetSQLValueString($id, "text"));

        try{
            $Result1 = $pdar_connexion->prepare($insertSQL);
            $Result1->execute();
        }catch(Exception $e){ die(mysql_error_show_message($e)); }
		      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      $insertGoTo .= "&numero=$numero&annee=$annee&mod=1";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }                                                   

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];  $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."suivi_avenant SET numero_avenant=%s, montant=%s, date_avenant=%s, documents=%s, modifier_par='$personnel', modifier_le='$date' WHERE id_suivi=%s",
  					   // GetSQLValueString($_POST['numero'], "int"),
                        GetSQLValueString($_POST['numero_avenant'], "text"),
						GetSQLValueString($_POST['montant'], "double"),
						GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_avenant']))), "date"),
                       // GetSQLValueString($_POST['message'], "text"),
                        GetSQLValueString($_POST['documents'], "text"),
                        GetSQLValueString($id, "int"));

        try{
            $Result1 = $pdar_connexion->prepare($insertSQL);
            $Result1->execute();
        }catch(Exception $e){ die(mysql_error_show_message($e)); }
		
    $insertGoTo = $_SERVER['PHP_SELF'];
    if($Result1) $insertGoTo = "?update=ok"; else $insertGoTo = $page."?update=no";
    $insertGoTo .= "&numero=$numero&annee=$annee&mod=1";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}


$query_dossier = "SELECT * FROM ".$database_connect_prefix."contrat_prestation WHERE id_contrat='$numero' LIMIT 1";
    	   try{
    $dossier = $pdar_connexion->prepare($query_dossier);
    $dossier->execute();
    $row_dossier = $dossier ->fetch();
    $totalRows_dossier = $dossier->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]);
  $query_edit_suivi_dossier = "SELECT * FROM ".$database_connect_prefix."suivi_avenant WHERE id_suivi='$id' ";
    	   try{
    $edit_suivi_dossier = $pdar_connexion->prepare($query_edit_suivi_dossier);
    $edit_suivi_dossier->execute();
    $row_edit_suivi_dossier = $edit_suivi_dossier ->fetch();
    $totalRows_edit_suivi_dossier = $edit_suivi_dossier->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}

$query_sdossier = "SELECT * FROM  ".$database_connect_prefix."suivi_avenant where contrat='$numero' order by id_suivi desc";
  	   try{
    $sdossier = $pdar_connexion->prepare($query_sdossier);
    $sdossier->execute();
    $row_sdossier = $sdossier ->fetchAll();
    $totalRows_sdossier = $sdossier->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_activite_1 = "SELECT code_marche, intitule FROM ".$database_connect_prefix."plan_marche";
  	   try{
    $liste_activite_1 = $pdar_connexion->prepare($query_liste_activite_1);
    $liste_activite_1->execute();
    $row_liste_activite_1 = $liste_activite_1 ->fetchAll();
    $totalRows_liste_activite_1 = $liste_activite_1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$cmp_array = array();
if($totalRows_liste_activite_1>0){  foreach($row_liste_activite_1 as $row_liste_activite_1){  
  $cmp_array[$row_liste_activite_1["code_marche"]] = $row_liste_activite_1["intitule"];
}  }
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
        //$(".wysiwyg").each(function(){$(this).wysihtml5({parser: function(html) {return html;}});});
        //$(".wysihtml5-toolbar").each(function(){$(this).addClass('hidden');});
        //$(".wysihtml5-toolbar-edit").each(function(){$(this).attr('style','cursor:pointer;');$(this).click(function(){$(".wysihtml5-toolbar").toggleClass('hidden');});});
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
<style>
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}
.dataTables_length, .dataTables_info { float: left; font-size: 10px;}
.dataTables_length, .dataTables_paginate { display: none;}

@media(min-width:558px){.col-md-12 {width: 100%;}.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-6 {width: 50%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}
</style>
</head>
<body>
<?php if(!isset($_GET['add']) || ($row_dossier['traitement']==1 && !isset($_GET['nac']))) { ?>
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> March&eacute; N&deg;: <?php echo $row_dossier['code_marche']; ?></h4> 
 <?php if(isset($row_dossier['traitement']) && $row_dossier['traitement']==0) {  ?>
<a href="<?php echo $_SERVER['PHP_SELF']."?add=1&numero=$numero"; ?>" class="pull-right p11" title="Ajouter une nouvel avenant " ><i class="icon-plus"> Nouvel avenant </i></a>
<?php } ?>
</div>
 <div class="widget-content">
<div><b>Num&eacute;ro de Lot: <?php  echo $row_dossier['responsable']; ?></b></div>
<div><b>Port&eacute; par: <?php  echo $row_dossier['donneur_ordre']; ?></b></div>
<div><b>Objet : <?php if(isset($cmp_array[$row_dossier['code_marche']])) echo $cmp_array[$row_dossier['code_marche']]; else echo $row_dossier['code_marche']; ?></b></div> 
<div><b>P&eacute;riode pr&eacute;vue  : <?php echo date_reg($row_dossier['debut'],"/")." au ".date_reg($row_dossier['fin'],"/"); ?>&nbsp;====>&nbsp;<?php echo NbJours($row_dossier['debut'], $row_dossier['fin'])." Jours"; ?> </b></div>
<br />

<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >
            <thead>
                <tr>
                  <td><strong>Num&eacute;ro</strong></td>
                  <td><div align="center"><strong>Date</strong></div></td>
                  <td><div align="center"><strong>Montant</strong></div></td>
                  <td><div align="center"><strong>Pi&egrave;ces jointes</strong></div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1) && $row_dossier['traitement']==0) { ?>
                  <td align="center" width="80" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
                <?php if($totalRows_sdossier>0) {$i=0; $total_dec=0; foreach($row_sdossier as $row_sdossier){ $id = $row_sdossier['id_suivi']; ?>
                <tr>
                  <td><strong><?php echo $row_sdossier['numero_avenant']; ?></strong>
                    <div align="left"></div></td>
                  <td><div align="center"><?php echo date_reg($row_sdossier['date_avenant'],"/"); ?></div></td>
                  <td><div align="right"><?php echo number_format($row_sdossier['montant'], 0, ',', ' '); $total_dec=$total_dec+$row_sdossier['montant']; ?></div></td>
                  <td>
                    <div align="center">
                      <?php
echo do_link("","","Contenu du traitement","Aper&ccedil;u","","./","","get_content('body_suivi_avenant.php','id=$id&suivi=1','modal-body_add',this.title);",1,"",$nfile);


 $a = explode('|',$row_sdossier["documents"]);
  echo "&nbsp;&nbsp;<b>(".(count($a)-1)." fichier".((count($a)-1>1)?'s':'').")</b>";
 ?>
                    </div>
                  </td>
<?php if($row_dossier['traitement']==0 && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) {  ?>
<td align="center" nowrap="nowrap">
<?php
echo do_link("",$_SERVER['PHP_SELF']."?id=$id&numero=$numero&add=1&nac=1","Modifier avenant","","edit","./","","",0,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id."&numero=$numero","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cet avenant ?');",0,"margin:0px 5px;",$nfile); ?>
</td>
<?php } ?>
</tr>
                <?php $i++; }  ?>
                <?php } else { ?>
<tr>
  <td align="center" colspan="<?php echo (isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1))?5:4; ?>">Aucun r&eacute;sultat !</td></tr>
                <?php } ?>
      </table>

</div></div>
</div>
<?php } else { ?>
<?php if(isset($_SESSION['clp_niveau'])) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> March&eacute; N&deg;: <?php echo $row_dossier['code_marche']; ?></h4><b> (Port&eacute; par: 
 <?php  echo $row_dossier['donneur_ordre']; ?>)</b>
<a href="<?php echo $_SERVER['PHP_SELF']."?numero=$numero"; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>

<div class="widget-content">
<form action="<?php echo $_SERVER['PHP_SELF']."?numero=$numero"; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">

<input class="form-control required" type="hidden" name="numero" id="numero" value="<?php echo $numero; ?>" size="32" />

  <table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:10px;">
	 <tr valign="top">
      <td colspan="2">
      <div class="form-group">
          <label for="numero_avenant" class="col-md-3 control-label">N&deg; Avenant <span class="required">*</span> </label>
          <div class="col-md-6">
            <input name="numero_avenant" type="text" class="form-control required" id="numero_avenant" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_suivi_dossier['numero_avenant']; ?>" size="15" />
          </div>
      </div>
      </td>
    </tr>
	 <tr valign="top">
      <td colspan="2">
      <div class="form-group">
          <label for="date_avenant" class="col-md-3 control-label">Date <span class="required">*</span> </label>
          <div class="col-md-6">
            <input type="text" class="form-control datepicker required" name="date_avenant" id="date_avenant" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_edit_suivi_dossier['date_avenant']))))); else echo date("d/m/Y"); ?>">
          </div>
      </div>
      </td>
    </tr>
	 <tr valign="top">
      <td colspan="2">
      <div class="form-group">
          <label for="montant_soumis" class="col-md-3 control-label">Montant <span class="required">*</span> </label>
          <div class="col-md-6">
            <input name="montant" type="text" class="form-control required" id="montant" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_suivi_dossier['montant']; ?>" size="15" />
          </div>
      </div>
      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
      <div class="form-group">
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
      </div>
      </td>
    </tr>
  </table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "Modifier";else echo "Enregistrer";?>" />
  <input name="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "MM_update";else echo "MM_insert";?>" type="hidden" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]);else echo "MM_insert";?>" size="32" alt="">
<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) {?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce avenant ?','<?php echo ($_GET["id"]);?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php }?>
  <input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
</div>
</form>

</div>
</div>
<?php } ?>
<?php } ?>
<?php include_once 'modal_add.php'; ?>
</body>
</html>