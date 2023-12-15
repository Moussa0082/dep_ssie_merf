<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
$path = './';
include_once  $path.'system/configuration.php';
$config = new Config;

if (!isset($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once  $path.$config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');

$dir = './attachment/workflow/';

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

if(isset($_GET["id"]) && !empty($_GET["id"]))
{ //New Dossier
  $id=($_GET["id"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_edit_dossier = "SELECT * FROM ".$database_connect_prefix."workflow WHERE id_dossier='$id'";
  $edit_dossier = mysql_query($query_edit_dossier, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_edit_dossier = mysql_fetch_assoc($edit_dossier);
  $totalRows_edit_dossier = mysql_num_rows($edit_dossier);
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_type_r = "SELECT * FROM ".$database_connect_prefix."type_doc_workflow order by code ";
$liste_type_r = mysql_query($query_liste_type_r, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_type_r = mysql_fetch_assoc($liste_type_r);
$totalRows_liste_type_r = mysql_num_rows($liste_type_r);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_max_dossier = "SELECT max(numero)+1 as numero FROM ".$database_connect_prefix."workflow ";
$liste_max_dossier = mysql_query($query_liste_max_dossier, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_max_dossier = mysql_fetch_assoc($liste_max_dossier);
$totalRows_liste_max_dossier = mysql_num_rows($liste_max_dossier);
$num = $row_liste_max_dossier["numero"];
$num = ($totalRows_liste_max_dossier>0 && $num>0)?((strlen($num)==1)?"00$num":((strlen($num)==2)?"0$num":$num)):"001";
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
<script type="text/javascript" src="bootstrap/js/bootstrap-typeahead.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="plugins/bootstrap-wysihtml5/wysihtml5.min.js"></script>
<script type="text/javascript" src="plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.min.js"></script>
<script type="text/javascript" src="plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>
<script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
        $(".wysiwyg").each(function(){$(this).wysihtml5({parser: function(html) {return html;}});});
        $(".wysihtml5-toolbar").each(function(){$(this).addClass('hidden');});
        $(".wysihtml5-toolbar-edit").each(function(){$(this).attr('style','cursor:pointer;');$(this).click(function(){$(".wysihtml5-toolbar").toggleClass('hidden');});});
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
        /*var oTable = $('#mtable1').dataTable( {
                "iDisplayLength": -1,
                paging: false
            });  */
        <?php if(isset($_GET['id']) && isset($row_edit_dossier["expediteur"])){ ?>
        get_content('menu_users.php','id=<?php echo $row_edit_dossier["type_dossier"]."&id_s=".$row_edit_dossier["expediteur"]; ?>','destinataire','');
        <?php } ?>
	});
</script>
<style>
/*#mtable2 .dataTables_length, #mtable2 .dataTables_info { float: left; font-size: 10px;}
#mtable2 .dataTables_length, #mtable2 .dataTables_paginate, .DTTT, .ColVis { display: none;} */
@media(min-width:558px){.col-md-12 {width: 100%;}.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}
.ui-datepicker-append {display: none;}
</style>
</head>
<body>
<div>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Dossier N°: <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_dossier['numero']; else echo $num; ?></h4></div>
<div class="widget-content">
<form target="_parent" action="./workflow.php<?php //echo "?annee=$annee"; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
  <table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:10px;">
    <tr>
      <td colspan="2" valign="top"><div class="form-group">
          <label for="nom" class="col-md-12 control-label">Nom du dossier <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea name="nom" rows="1" class="form-control required" id="nom"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_dossier['nom']; ?></textarea>
          </div>
      </div>      </td>
      </tr>
    <tr valign="top">
      <td valign="top"><div class="form-group">
          <label for="type_dossier" class="col-md-12 control-label">Type de dossier <span class="required">*</span></label>
          <div class="col-md-12">
            <select name="type_dossier" id="type_dossier" class="form-control required" onchange="get_content('menu_users.php','id='+this.value,'destinataire','');">
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_type_r>0){ do { ?>
              <option value="<?php echo $row_liste_type_r['code'];?>" <?php if (isset($row_edit_dossier['type_dossier']) && $row_liste_type_r['code']==$row_edit_dossier['type_dossier']) {echo "SELECTED";} ?> ><?php echo $row_liste_type_r['intitule']; ?></option>
              <?php  } while ($row_liste_type_r = mysql_fetch_assoc($liste_type_r)); } ?>
            </select>
          </div>
      </div>      </td>
        <td valign="top"><div class="form-group">
          <label for="destinataire" class="col-md-12 control-label">Responsable concern&eacute;e <span class="required">*</span></label>
          <div class="col-md-12">
            <select name="destinataire" id="destinataire" class="form-control required">
              <option value="">Selectionnez</option>
            </select>
          </div>
      </div>      </td>
    </tr>
    <tr valign="top">
      <td colspan="2"><div class="form-group">
          <label for="message" class="col-md-12 control-label">Description du dossier <span class="required">*</span> <span class="pull-right wysihtml5-toolbar-edit">Edition (Affich./Masquer)</span></label>
          <div class="col-md-12">
            <textarea class="form-control wysiwyg required" id="message" name="message" rows="3" cols="25"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_dossier['message']; ?></textarea>
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
if(!empty($row_edit_dossier["documents"]))
{
  $a = explode('|',$row_edit_dossier["documents"]);
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
  </table>
  <div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "Modifier";else echo "Enregistrer";?>" />
  <input name="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "MM_update";else echo "MM_insert";?>" type="hidden" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]);else echo "MM_insert";?>" size="32" alt="">
<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) {?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce dossier ?','<?php echo ($_GET["id"]);?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php }?>
  
  <input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <input name="numero" id="numero" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_dossier['numero']; else echo $num; ?>" size="32" alt="" />
  </div>
</form>

</div> </div>

</div><?php include_once $path.'modal_add.php'; ?>
</body>
</html>