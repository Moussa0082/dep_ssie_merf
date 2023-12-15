<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
$path = './';
include_once  $path.'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once  $path.$config->sys_folder . "/database/db_connexion.php";
header('Content-Type: text/html; charset=UTF-8');

$dir = './attachment/dano/';

if(isset($_GET["dno"])){ $dno=$_GET['dno'];} $annee=$_GET['annee'];  //$cp=(isset($_GET["cp"]))?$_GET['cp']:0;
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];


if(isset($_GET["id"]) && !empty($_GET["id"]))
{ //New DANO
  $id=($_GET["id"]);
  $query_edit_dano = "SELECT * FROM ".$database_connect_prefix."dno WHERE numero='$id'";
                   try{
    $edit_dano = $pdar_connexion->prepare($query_edit_dano);
    $edit_dano->execute();
    $row_edit_dano = $edit_dano ->fetch();
    $totalRows_edit_dano = $edit_dano->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}

$query_liste_bailleur = "SELECT * FROM ".$database_connect_prefix."partenaire WHERE dno=1 ";
                 try{
    $liste_bailleur = $pdar_connexion->prepare($query_liste_bailleur);
    $liste_bailleur->execute();
    $row_liste_bailleur = $liste_bailleur ->fetchAll();
    $totalRows_liste_bailleur = $liste_bailleur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


$query_liste_type_r = "SELECT * FROM ".$database_connect_prefix."type_requete_dano order by type_requete ";
                 try{
    $liste_type_r = $pdar_connexion->prepare($query_liste_type_r);
    $liste_type_r->execute();
    $row_liste_type_r = $liste_type_r ->fetchAll();
    $totalRows_liste_type_r = $liste_type_r->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_activite = "SELECT id_ptba, code_activite_ptba, intitule_activite_ptba FROM ".$database_connect_prefix."ptba where annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."'  ORDER BY code_activite_ptba asc";
                 try{
    $liste_activite = $pdar_connexion->prepare($query_liste_activite);
    $liste_activite->execute();
    $row_liste_activite = $liste_activite ->fetchAll();
    $totalRows_liste_activite = $liste_activite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$activite_array1 = array();
if($totalRows_liste_activite>0){ foreach($row_liste_activite as $row_liste_activite){
  $activite_array1[$row_liste_activite["code_activite_ptba"]] = $row_liste_activite["intitule_activite_ptba"];
  //echo  $activite_array1[$row_liste_activite["code_activite_ptba"]];
} }
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
        $(".wysiwyg").each(function(){$(this).wysihtml5({parser: function(html) {return html;}});});
        $(".wysihtml5-toolbar").each(function(){$(this).addClass('hidden');});
        $(".wysihtml5-toolbar-edit").each(function(){$(this).attr('style','cursor:pointer;');$(this).click(function(){$(".wysihtml5-toolbar").toggleClass('hidden');});});
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
.ui-datepicker-append {display: none;}
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
        url: "./ajax_code_analytique.php?path=./",
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
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php if(isset($_GET['id'])) echo $_GET['id'].": ".$row_edit_dano['objet']; else echo "Nouvelle DANO"; ?></h4></div>
<div class="widget-content">
<form target="_parent" action="./liste_dno.php<?php echo "?annee=$annee"; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form4" id="form4" novalidate="novalidate">
  <table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:10px;">
    <tr>
      <td colspan="2" valign="top"><div class="form-group">
          <label for="code_activite" class="col-md-12 control-label">Activit&eacute; <span class="required">*</span></label>
          <div class="col-md-12">
            <input name="code_activite" type="text" class="form-control typeahead required" id="code_activite" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_dano['code_activite'].': '.((isset($activite_array1[$row_edit_dano['code_activite']]))?$activite_array1[$row_edit_dano['code_activite']]:'');  ?>" size="25" />
          </div>
      </div></td>
    </tr>
    <tr>
      <td valign="top"><div class="form-group">
          <label for="numero" class="col-md-12 control-label">Num&eacute;ro <span class="required">*</span></label>
          <div class="col-md-12">
            <input type="text" class="form-control required" name="numero" id="numero" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_dano['numero'];?>" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_acteur['code_acteur']."'"; ?>) check_code('verif_code.php?t=dno&','w=numero='+this.value+' and projet=<?php echo $_SESSION["clp_projet"]; ?>','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
      </div></td>
      <td valign="top"><div class="form-group">
          <label for="destinataire" class="col-md-12 control-label">Destinataire <span class="required">*</span></label>
          <div class="col-md-12">
            <select name="destinataire" id="destinataire" class="form-control required">
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_bailleur>0){ foreach($row_liste_bailleur as $row_liste_bailleur){ ?>
              <option value="<?php echo $row_liste_bailleur['code'];?>" <?php if (isset($row_edit_dano["destinataire"]) && $row_liste_bailleur['code']==$row_edit_dano["destinataire"]) {echo "SELECTED";} ?>><?php echo $row_liste_bailleur['code'].": ".((strlen($row_liste_bailleur['definition']>70)?substr($row_liste_bailleur['definition'],0, 70)." ...":$row_liste_bailleur['definition'])); echo " (".$row_liste_bailleur['sigle'].")";?></option>
              <?php  }  } ?>
            </select>
          </div>
      </div></td>
    </tr>
    <tr valign="top">
      <td valign="top"><div class="form-group">
          <label for="objet" class="col-md-12 control-label">Objet <span class="required">*</span></label>
          <div class="col-md-12">
<textarea class="form-control required" id="objet" name="objet" cols="25" rows="1"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_dano['objet'];?></textarea>
          </div>
      </div></td>
      <td valign="top"><div class="form-group">
          <label for="type_requete" class="col-md-12 control-label">Type de requ&ecirc;te <span class="required">*</span></label>
          <div class="col-md-12">
            <select name="type_requete" id="type_requete" class="form-control required">
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_type_r>0){ foreach($row_liste_type_r as $row_liste_type_r){ ?>
              <option value="<?php echo $row_liste_type_r['type_requete'];?>" <?php if (isset($row_edit_dano['type_requete']) && strcmp($row_liste_type_r['type_requete'], $row_edit_dano['type_requete'])) {echo "SELECTED";} ?> ><?php echo $row_liste_type_r['type_requete']; ?></option>
              <?php  } while ($row_liste_type_r = mysql_fetch_assoc($liste_type_r)); } ?>
            </select>
          </div>
      </div></td>
    </tr>
   
	 <tr>
      <td valign="top"><div class="form-group">
          <label for="numero" class="col-md-12 control-label">Date de reception  <span class="required">*</span></label>
          <div class="col-md-12">
             <input type="text" class="form-control datepicker required" name="date_initialisation" id="date_initialisation" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_edit_dano['date_initialisation']))))); else echo date("d/m/Y"); ?>">
          </div>
      </div></td>
      <td valign="top"><div class="form-group">
          <label for="expediteur" class="col-md-12 control-label">Expediteur <span class="required">*</span></label>
          <div class="col-md-12">
            <select name="expediteur" id="expediteur" class="form-control required">
              <option value="">Selectionnez</option>
			      <option value="DCI" <?php if (isset($row_edit_dano["expediteur"]) && "DCI"==$row_edit_dano["expediteur"]) {echo "SELECTED";} ?>>DCI</option>
				      <option value="ANTENNE" <?php if (isset($row_edit_dano["expediteur"]) && "ANTENNE"==$row_edit_dano["expediteur"]) {echo "SELECTED";} ?>>ANTENNE</option>
            </select>
          </div>
      </div></td>
    </tr>
    <tr>
      <td valign="top"><div class="form-group">
          <label for="observation_ptba" class="col-md-12 control-label">Observation PTBA </label>
          <div class="col-md-12">
            <textarea class="form-control" id="observation_ptba" name="observation_ptba" rows="1" cols="25"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_dano['observation_ptba']; ?></textarea>
          </div>
      </div></td>
      <td valign="top"><div class="form-group">
          <label for="observation_ppm" class="col-md-12 control-label">Observation PPM </label>
          <div class="col-md-12">
            <textarea class="form-control" id="observation_ppm" name="observation_ppm" rows="1" cols="25"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_dano['observation_ppm']; ?></textarea>
          </div>
      </div></td>
    </tr>
	
    <tr valign="top">
      <td colspan="2"><div class="form-group">
          <label for="message" class="col-md-12 control-label">Message <span class="required">*</span> <span class="pull-right wysihtml5-toolbar-edit">Edition (Affich./Masquer)</span></label>
          <div class="col-md-12">
            <textarea class="form-control wysiwyg required" id="message" name="message" rows="3" cols="25"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_dano['message']; ?></textarea>
          </div>
      </div></td>
    </tr>
    <!--    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="observation" class="col-md-3 control-label">Observations </label>
          <div class="col-md-9">
            <textarea class="form-control" name="observation" cols="25" rows="1"><?php //if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_dano['observation'];?></textarea>
          </div>
        </div>
      </td>
    </tr>-->
    <tr valign="top">
      <td><div class="form-group">
          <?php //echo do_link("","","Ajout de fichier","Ajout de fichier","","./","pull-right","get_content('new_document.php','dir=$dir&page=".$_SERVER['PHP_SELF']."','modal-body_add',this.title);",1,"",$nfile); ?>
          <div class="col-md-12">
            <div style="font-size: medium;">
              <?php $c = "";
echo "<b>".do_link("","","Pi&egrave;ces jointes","Pi&egrave;ces jointes","","./","","get_content('list_attachments.php','dir=$dir&doc=documents&page=".$_SERVER['PHP_SELF']."','modal-body_add',this.title,'iframe');",1,"",$nfile);
if(!empty($row_edit_dano["documents"]))
{
  $dir = './attachment/dano/';
  $a = explode('|',$row_edit_dano["documents"]);
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
  <input name="id" id="id" type="hidden" value="<?php if(isset($_GET["id"])) echo ($_GET["id"]);?>" size="32" alt="">
<input name="annee" id="annee" type="hidden" value="<?php if(isset($_GET["annee"])) echo $_GET["annee"];?>" size="32" alt="">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "Modifier";else echo "Enregistrer";?>" />
  <input name="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "MM_update";else echo "MM_insert";?>" type="hidden" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]);else echo "MM_insert";?>" size="32" alt="">
<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) {?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette DANO ?','<?php echo ($_GET["id"]);?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php }?>
  
  <input name="MM_form" id="MM_form" type="hidden" value="form4" size="32" alt="">
</div>
</form>

</div> </div>

</div><?php include_once $path.'modal_add.php'; ?>
</body>
</html>