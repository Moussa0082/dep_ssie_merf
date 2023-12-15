<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
$path = '../';
include_once  $path.'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once  $path.$config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');

$dir = '../attachment/dano/';

if(isset($_GET["dno"])){ $dno=$_GET['dno'];} $annee=$_GET['annee'];  //$cp=(isset($_GET["cp"]))?$_GET['cp']:0;
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

if(isset($_GET["suivi"]) && !empty($_GET["suivi"]))
{ //Suivi
  $id=intval($_GET["suivi"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_edit_suivi_dno = "SELECT * FROM ".$database_connect_prefix."mail_dno WHERE id_mail='$id'";
  $edit_suivi_dno = mysql_query($query_edit_suivi_dno, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_edit_suivi_dno = mysql_fetch_assoc($edit_suivi_dno);
  $totalRows_edit_suivi_dno = mysql_num_rows($edit_suivi_dno);
}
elseif(isset($_GET["new"]) && !empty($_GET["new"]))
{ //New DANO
  $id=intval($_GET["new"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_new_dano = "SELECT * FROM ".$database_connect_prefix."mail_dno WHERE id_mail='$id'";
  $new_dano = mysql_query($query_new_dano, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_new_dano = mysql_fetch_assoc($new_dano);
  $totalRows_new_dano = mysql_num_rows($new_dano);
}

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
		$("#form3").validate();
        $("#form4").validate();
        $(".wysiwyg").each(function(){$(this).wysihtml5()});
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
        var oTable = $('#mtable1').dataTable( {
                "iDisplayLength": -1,
                paging: false
            });
	});
</script>
<style>
#mtable2 .dataTables_length, #mtable2 .dataTables_info { float: left; font-size: 10px;}
#mtable2 .dataTables_length, #mtable2 .dataTables_paginate, .DTTT, .ColVis { display: none;}
@media(min-width:558px){.col-md-12 {width: 100%;}.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}
</style>
</head>
<body>
<div>

<script>
	$().ready(function() {
$("input.typeahead").typeahead({
    onSelect: function(item) {
        //console.log(item);
    },
    ajax: {
        url: "./ajax_code_activite_ptba.php?path=./",
        timeout: 300,
        displayField: "title",
        valueField: "id",
        triggerLength: 1,
        method: "GET",
        //loadingClass: "loading-circle",
        preDispatch: function (query) {
            //showLoadingMask(true);
            return {
                search: query
            }
        },
        preProcess: function (data) {
            //showLoadingMask(false);
            if (data.success === false) {
                // Hide the list, there was some error
                return false;
            }
            // We good!
            return data;
        }
    }
});
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Nouvelle DANO</h4></div>
<div class="widget-content">
<form target="_parent" action="./courrier_dno.php<?php echo "?annee=$annee&cp=$cp"; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form4" id="form4" novalidate="novalidate">

<table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="code_activite" class="col-md-3 control-label">Activit&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
             <input name="code_activite" type="text" class="form-control typeahead required" id="code_activite" value="<?php //if(isset($_GET["new"]) && !empty($_GET["new"])) echo $row_edit_contrat['code_activite'].': '.$activite_array1[$row_edit_contrat['code_activite']];  ?>" size="25" />
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="numero" class="col-md-3 control-label">Num&eacute;ro <span class="required">*</span></label>
          <div class="col-md-9">
            <input type="text" class="form-control required" name="numero" value="<?php //if(isset($_GET["new"]) && !empty($_GET["new"])) echo $row_edit_contrat['numero'];?>" >
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="destinataire" class="col-md-3 control-label">Destinataire <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="destinataire" id="destinataire" class="form-control required">
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_bailleur>0){ do { ?>
              <option value="<?php echo $row_liste_bailleur['code'];?>" ><?php echo $row_liste_bailleur['code'].": ".((strlen($row_liste_bailleur['definition']>70)?substr($row_liste_bailleur['definition'],0, 70)." ...":$row_liste_bailleur['definition'])); echo " (".$row_liste_bailleur['sigle'].")";?></option>
                <?php  } while ($row_liste_bailleur = mysql_fetch_assoc($liste_bailleur)); } ?>
            </select>
          </div>
        </div>
      </td>
    </tr>

    <input type="hidden" class="form-control required" name="date_initialisation" id="date_initialisation" value="<?php if(isset($_GET["new"]) && !empty($_GET["new"])) echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_new_dano['date']))))); else echo date("d/m/Y"); ?>" >
	<tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="objet" class="col-md-3 control-label">Objet <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" id="objet" name="objet" cols="25" rows="1"><?php if(isset($_GET["new"]) && !empty($_GET["new"])) echo $row_new_dano['objet'];?></textarea>
          </div>
        </div>
      </td>
    </tr>
	<tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="message" class="col-md-12 control-label">Message <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control wysiwyg required" id="message" name="message" rows="10" cols="25"><?php if(isset($_GET["new"]) && !empty($_GET["new"])) echo $row_new_dano['message'];?></textarea>
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="documents" class="col-md-12 control-label">Documents </label>
          <div class="col-md-12">
<div>
<?php $c = "";
if(!empty($row_new_dano["attachments"]))
{
  $dir = './attachment/dano/';
  $a = explode('|',$row_new_dano["attachments"]);
  echo "<b>Pi&egrave;ces jointes : <span id='documents_zone'>".(count($a)-1)." fichier".((count($a)-1>1)?'s':'')."</span></b> : ";
  foreach($a as $b)
  if(!empty($b)) { //echo "<a style='' href='./download_file.php?file=$dir$b' title='Télécharger' alt='$b'>$b</a>&nbsp;&nbsp;&nbsp;";
    $c .= $b.'|'; }
  //echo "<div style='clear:both; height:0px;'><hr></div>";
}else  echo "<b>Pi&egrave;ces jointes : <span id='documents_zone'>Aucun</span></b>";
?>
</div>
<?php echo do_link("","","Ajout de fichier","Ajout de fichier","","./","pull-right","get_content('add_document.php','dir=$dir&doc=documents&page=".$_SERVER['PHP_SELF']."','modal-body_add',this.title,'iframe');",1,"",$nfile); ?>
<input type="hidden" id="documents" name="documents" value="<?php echo $c; ?>" />
          </div>
        </div>
      </td>
    </tr>
          </div>
        </div>
      </td>
    </tr>
</table>

<div class="form-actions">
  <input name="id_mail" type="hidden" value="<?php echo intval($_GET["new"]); ?>" size="32" alt="">
  <input name="expediteur" type="hidden" value="<?php echo $row_new_dano["expediteur"]; ?>" size="32" alt="">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="Enregistrer" />
  <input name="MM_insert" type="hidden" value="MM_insert" size="32" alt="">
  <input name="MM_form" id="MM_form" type="hidden" value="form4" size="32" alt="">
</div>
</form>

</div> </div>

</div><?php include_once $path.'modal_add.php'; ?>
</body>
</html>