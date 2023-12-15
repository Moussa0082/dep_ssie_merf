<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
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
?>
<?php if(isset($_GET["iframe"])){ ?>
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
<style>
@media(min-width:558px){.col-md-12 {width: 100%;}.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}
</style>
</head>
<body>
<?php } else { ?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<?php } ?>

<?php
$rres = (isset($_GET["res"]) && !empty($_GET["res"]))?intval($_GET["res"]):0;

if(isset($_GET["id"])) { $id=$_GET["id"];
$query_edit_ind = "SELECT * FROM indicateur_resultat_cmr WHERE id_indicateur='$id'";
try{
    $edit_ind = $pdar_connexion->prepare($query_edit_ind);
    $edit_ind->execute();
    $row_edit_ind = $edit_ind ->fetch();
    $totalRows_edit_ind = $edit_ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if(isset($row_edit_ind['responsable_collecte'])) $as = explode(",", $row_edit_ind['responsable_collecte']); else $as=array();
$idcl=$row_edit_ind['indicateur_res'];
} else $id=0;
//indicateur resultat
$query_liste_ind_cl = "SELECT id_indicateur_resultat, intitule_indicateur_resultat FROM indicateur_resultat, resultat WHERE resultat.projet='".$_SESSION["clp_projet"]."' and resultat=id_resultat ";
try{
    $liste_ind_cl = $pdar_connexion->prepare($query_liste_ind_cl);
    $liste_ind_cl->execute();
    $row_liste_ind_cl = $liste_ind_cl ->fetchAll();
    $totalRows_liste_ind_cl = $liste_ind_cl->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_gind = "SELECT id_groupe, nom_groupe FROM groupe_indicateur order by nom_groupe";
try{
    $liste_gind = $pdar_connexion->prepare($query_liste_ind_cl);
    $liste_gind->execute();
    $row_liste_gind = $liste_gind ->fetchAll();
    $totalRows_liste_gind = $liste_gind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_acteur = "SELECT id_acteur, nom_acteur FROM acteur order by categorie, nom_acteur";
try{
    $liste_acteur = $pdar_connexion->prepare($query_liste_acteur);
    $liste_acteur->execute();
    $row_liste_acteur = $liste_acteur ->fetchAll();
    $totalRows_liste_acteur = $liste_acteur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_edit_nmind = "SELECT count(id_indicateur)+1 as imax FROM indicateur_resultat_cmr, indicateur_resultat WHERE id_indicateur_resultat=indicateur_res and resultat='$rres'";
try{
    $edit_nmind = $pdar_connexion->prepare($query_edit_nmind);
    $edit_nmind->execute();
    $row_edit_nmind = $edit_nmind ->fetchAll();
    $totalRows_edit_nmind = $edit_nmind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_referentiel = "SELECT * FROM referentiel_indicateur WHERE id_ref_ind not in (select indicateur_resultat_cmr.referentiel from indicateur_resultat_cmr, indicateur_resultat, resultat WHERE  id_resultat=resultat and id_indicateur_resultat=indicateur_res and id_indicateur!=$id)";
try{
    $liste_referentiel = $pdar_connexion->prepare($query_liste_referentiel);
    $liste_referentiel->execute();
    $row_liste_referentiel = $liste_referentiel ->fetchAll();
    $totalRows_liste_referentiel = $liste_referentiel->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


?>
<script>
	$().ready(function() {
		$("#form1").validate();
		 $(".modal-dialog", window.parent.document).width(700);
        $(".select2-select-00").select2({allowClear:true});
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});});

</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification indicateur d'effet du CMR":"Nouveau indicateur d'effet du CMR"?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
        <label for="code_cmr" class="col-md-3 control-label">Code <span class="required">*</span></label>
        <div class="col-md-9">
          <div align="left">
            <input class="form-control required" type="text" name="code_cmr" id="code_cmr" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_edit_ind['code_cmr']; ?>" size="32" />
            </div>
        </div>
        </div>
      </td>
      </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="indicateur" class="col-md-3 control-label">Indicateur CMR <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea name="indicateur" cols="32" rows="1" class="form-control required" id="indicateur"><?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_edit_ind['intitule_indicateur_cmr_res']; ?></textarea>
          </div>
        </div>      
        <label for="code_cmr" class="col-md-5 control-label"></label>        <div class="col-md-5"></div></td>
      </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="referentiel" class="col-md-3 control-label">R&eacute;f&eacute;rentiel <span class="required">*</span></label>
          <div class="col-md-9">
           <select name="referentiel" id="referentiel" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez un indicateur">
              <option></option>
         <option value="0" <?php if (isset($row_edit_ind["referentiel"]) && $row_edit_ind['referentiel']=="0") {echo "SELECTED";} ?>>Non-d&eacute;finie</option>
 			  <?php  if($totalRows_liste_referentiel>0) { foreach($row_liste_referentiel as $row_liste_referentiel){ ?>
              <option value="<?php echo $row_liste_referentiel['id_ref_ind']; ?>" <?php if (isset($row_edit_ind["referentiel"]) && $row_liste_referentiel['id_ref_ind']==$row_edit_ind["referentiel"]) {echo "SELECTED";} ?>><?php echo $row_liste_referentiel['code_ref_ind'].": ".$row_liste_referentiel['intitule_ref_ind']; ?></option>
                <?php  } } ?>
            </select>
          </div>
        </div>      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="indicateur_cl" class="col-md-3 control-label">R&eacute;sultat <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="indicateur_cl" id="indicateur_cl" class="form-control required" >
              <option value="">Selectionnez</option>
   			  <?php  if($totalRows_liste_ind_cl>0) { foreach($row_liste_ind_cl as $row_liste_ind_cl){ ?>
              <option value="<?php echo $row_liste_ind_cl['id_indicateur_resultat']; ?>" <?php if (isset($row_edit_ind['indicateur_res']) && $row_liste_ind_cl['id_indicateur_resultat']==$row_edit_ind['indicateur_res']) {echo "SELECTED";} ?>><?php echo (strlen($row_liste_ind_cl['intitule_indicateur_resultat'])>55)?substr($row_liste_ind_cl['intitule_indicateur_resultat'],0,55)." ...":$row_liste_ind_cl['intitule_indicateur_resultat']; ?></option>
              <?php } } ?>
            </select>
          </div>
        </div>      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="annee_reference" class="col-md-3 control-label">Ann&eacute;e de r&eacute;f&eacute;rence <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" type="text" name="annee_reference" id="annee_reference" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_edit_ind['annee_reference']; ?>" size="32" />
          </div>
          <label for="valeur_reference" class="col-md-3 control-label">Situation de r&eacute;f&eacute;rence <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" type="text" name="valeur_reference" id="valeur_reference" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_edit_ind['valeur_reference']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="cible_dp" class="col-md-3 control-label">Cible DCP <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" type="text" name="cible_dp" id="cible_dp" value="<?php if(isset($_GET['id']))  {if($row_edit_ind['cible_dp']==0 /*&&   $unite=="Oui/Non"*/) echo "Oui"; elseif($row_edit_ind['cible_dp']==1 /*&&  $unite=="Oui/Non"*/) echo "Non"; elseif($row_edit_ind['cible_dp']==-1) echo "n/a"; else echo $row_edit_ind['cible_dp']; } else echo "N/A"; ?>" size="32" />
          </div>
          <label for="cible_rmp" class="col-md-3 control-label">Cible R&eacute;vue <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" type="text" name="cible_rmp" id="cible_rmp" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_edit_ind['cible_rmp']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="intitule_activite" class="col-md-3 control-label">Responsables <span class="required">*</span></label>
          <div class="col-md-9">
            <select class="form-control required" cols="200" rows="3" type="text" name="acteur[]" multiple="multiple" size="5">
	   			  <?php  if($totalRows_liste_acteur>0) { foreach($row_liste_acteur as $row_liste_acteur){ ?>
                                <option value="<?php echo $row_liste_acteur['id_acteur']?>"<?php if(isset($_GET['id'])) {if(in_array($row_liste_acteur['id_acteur'], $as, TRUE)) {echo "SELECTED";} } ?>><?php echo $row_liste_acteur['nom_acteur']?></option>
                                <?php } } ?></select>
          </div>
        </div>      </td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && intval($_GET["id"])>0 && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2) ) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onClick="return delete_data('MM_delete','Supprimer l\'indicateur ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>
<?php if(isset($_GET["iframe"])){ ?>
</body>
</html>
<?php } ?>