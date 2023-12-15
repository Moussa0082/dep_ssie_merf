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

//if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y"); if(isset($_GET['cp'])) {$cp=$_GET['cp'];} else $cp=0;

//if(isset($_GET['code_modele'])) { $code_modele = $_GET['code_modele']; }
//if(isset($_GET['id_type'])) { $cat = $_GET['id_type'];}
if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y"); if(isset($_GET['cp'])) {$cp=$_GET['cp'];} else $cp=0;
if(isset($_GET['id_act'])) { $id_act = $_GET['id_act']; }
if(isset($_GET['cat'])) { $cat = $_GET['cat']; }
if(isset($_GET['code_act'])) {$code_activite = $_GET['code_act'];} else $code_activite="";
if(isset($_GET["id"])) { $id=$_GET["id"];} else $id=0;

function frenchMonthName($monthnum) {
      $armois=array("", "Jan", "Fév", "Mars", "Avril", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc");
      if ($monthnum>0 && $monthnum<13) {
          return $armois[$monthnum];
      } else {
          return $monthnum;
      }
  }

$editFormAction = $_SERVER['PHP_SELF'];
/*
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}*/
$page = $_SERVER['PHP_SELF'];

$annee = (isset($_GET["annee"]))?intval($_GET["annee"]):date("Y");

$lien = $lien1 = $_SERVER['PHP_SELF'];
$lien .= "?cat=$cat";
$lien1 .= "?cat=$cat";

/*if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sup_etape = sprintf("DELETE from ".$database_connect_prefix."type_tache WHERE id_groupe_tache=%s",
                         GetSQLValueString($id, "int"));
  $Result1 = mysql_query_ruche($query_sup_etape, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
 }*/
/* if(isset($_GET["id_act"])) { //$id=$_GET["id"];
$query_edit_obact = "SELECT  ptba.statut, fin FROM ".$database_connect_prefix."ptba WHERE id_ptba='$id_act'";
  try{
    $edit_obact = $pdar_connexion->prepare($query_edit_obact);
    $edit_obact->execute();
    $row_edit_obact = $edit_obact ->fetch();
    $totalRows_edit_obact = $edit_obact->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$statut_act=$row_edit_obact['statut'];
$fin_act=$row_edit_obact['fin'];
}*/

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{   $cat=$_POST['cat'];
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  $proportion=$_POST['proportion'];
  $intitule_tache=$_POST['intitule_tache'];
  $ordre=$_POST['ordre'];
  $id_groupe_tache=$_POST['id_groupe_tache'];
  $id_act=$_POST['id_act'];
    $n_lot=$_POST['n_lot'];
	$date_debut=$_POST['date_debut'];
	$date_fin=$_POST['date_fin'];
  $responsable=$_POST['responsable'];
  //suppression
  $query_sup_cible_indicateur = "DELETE FROM ".$database_connect_prefix."groupe_tache WHERE id_activite='$id_act'";
			     try{
    $Result1 = $pdar_connexion->prepare($query_sup_cible_indicateur);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  foreach ($id_groupe_tache as $key => $value)
if(isset($date_debut[$key]) && !empty($date_debut[$key]) && isset($date_fin[$key]) && !empty($date_fin[$key]) && isset($n_lot[$key]) && !empty($n_lot[$key])) {
  {
  //if(isset($valider[$key])) $valider[$key]=1; else $valider[$key]=0;  if(!isset($observation[$key]) || empty($observation[$key])) $observation[$key]=" ";
	  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."groupe_tache (id_groupe_tache, id_activite, proportion, code_tache, intitule_tache, date_debut, date_fin, responsable, n_lot, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, '$personnel', '$date')",
						GetSQLValueString($id_groupe_tache[$key], "int"),
						GetSQLValueString($id_act, "int"),
						GetSQLValueString($proportion[$key], "int"),
						GetSQLValueString($ordre[$key], "int"),
                        GetSQLValueString($intitule_tache[$key], "text"),
						GetSQLValueString(implode('-',array_reverse(explode('/',$date_debut[$key]))), "date"),
						GetSQLValueString(implode('-',array_reverse(explode('/',$date_fin[$key]))), "date"),
                        GetSQLValueString($responsable[$key], "text"),
						GetSQLValueString($n_lot[$key], "int"));
//echo $categorie[$key];
//exit;
				     try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    }
	}
}
/*
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
   if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."type_tache (type_activite, proportion, ordre, intitule_tache, id_personnel, date_enregistrement) VALUES (%s, %s, %s,  %s, '$personnel', '$date')",
						GetSQLValueString($cat, "int"),
						GetSQLValueString($_POST['proportion'], "int"),
						GetSQLValueString($_POST['ordre'], "int"),
						GetSQLValueString($_POST['intitule_tache'], "text"));
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $insertGoTo = $_SERVER['PHP_SELF'];
  $insertGoTo .= "?id_type=$cat";
  if ($Result1) $insertGoTo .= "&insert=ok";
  else $insertGoTo .= "&insert=no";
  header(sprintf("Location: %s", $insertGoTo));
}
}*/
//if(isset($statut_act) && $statut_act=="Report" && !empty($fin_act))
//$query_liste_zone = "SELECT groupe_tache.*, code_tache as ordre FROM ".$database_connect_prefix."groupe_tache where id_activite='$id_act' ORDER BY ordre asc";
 $query_liste_zone = "select * FROM type_tache where type_activite='$cat' ORDER BY ordre ASC";
    	   try{
    $liste_zone = $pdar_connexion->prepare($query_liste_zone);
    $liste_zone->execute();
    $row_liste_zone = $liste_zone ->fetchAll();
    $totalRows_liste_zone = $liste_zone->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$query_edit_modele = "SELECT * FROM ".$database_connect_prefix."type_activite where id_type='$cat'";
    	   try{
    $edit_modele = $pdar_connexion->prepare($query_edit_modele);
    $edit_modele->execute();
    $row_edit_modele = $edit_modele ->fetch();
    $totalRows_edit_modele = $edit_modele->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$libelle_modele=$row_edit_modele['categorie']."- ".$row_edit_modele['type_activite'];
$cat_ge=$row_edit_modele['categorie'];
//echo $libelle_modele;
//exit;
/*$query_liste_etape = "SELECT sum(proportion) as netape, type_activite FROM type_tache  group by type_activite";
    	   try{
    $liste_etape = $pdar_connexion->prepare($query_liste_etape);
    $liste_etape->execute();
    $row_liste_etape = $liste_etape ->fetchAll();
    $totalRows_liste_etape = $liste_etape->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$nb_etape_array = array();
if($totalRows_liste_etape>0){  foreach($row_liste_etape as $row_liste_etape){
 $nb_etape_array[$row_liste_etape["type_activite"]]=$row_liste_etape["netape"];
}}
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_categorie = "SELECT * FROM ".$database_connect_prefix."groupe_etape where categorie_groupe='$cat_ge' ORDER BY code_groupe asc";
$liste_categorie  = mysql_query_ruche($query_liste_categorie , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_categorie  = mysql_fetch_assoc($liste_categorie);
$totalRows_liste_categorie  = mysql_num_rows($liste_categorie);*/
$query_liste_groupe_tache = "select responsable, id_groupe_tache, date_debut, date_fin, n_lot  FROM ".$database_connect_prefix."groupe_tache where id_activite='$id_act'";
    	   try{
    $liste_groupe_tache = $pdar_connexion->prepare($query_liste_groupe_tache);
    $liste_groupe_tache->execute();
    $row_liste_groupe_tache = $liste_groupe_tache ->fetchAll();
    $totalRows_liste_groupe_tache = $liste_groupe_tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$responsable_array =$date_debut_array =$date_fin_array=$n_lot_array = array();
if($totalRows_liste_groupe_tache>0){ foreach($row_liste_groupe_tache as $row_liste_groupe_tache){
 $responsable_array[$row_liste_groupe_tache["id_groupe_tache"]] = $row_liste_groupe_tache["responsable"];
 $date_debut_array[$row_liste_groupe_tache["id_groupe_tache"]] = $row_liste_groupe_tache["date_debut"];
 $date_fin_array[$row_liste_groupe_tache["id_groupe_tache"]] = $row_liste_groupe_tache["date_fin"];
 $n_lot_array[$row_liste_groupe_tache["id_groupe_tache"]] = $row_liste_groupe_tache["n_lot"];
 }  }
 $query_liste_responsable = "SELECT distinct fonction, id_personnel, nom, prenom FROM ".$database_connect_prefix."personnel where id_personnel!='admin'";
    	   try{
    $liste_responsable = $pdar_connexion->prepare($query_liste_responsable);
    $liste_responsable->execute();
    $row_liste_responsable = $liste_responsable ->fetchAll();
    $totalRows_liste_responsable = $liste_responsable->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
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
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>
<script type="text/javascript" src="plugins/noty/layouts/top.js"></script>
<script type="text/javascript" src="plugins/noty/themes/default.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<?php if(!isset($_GET['add'])) { ?>
<script type="text/javascript" src="plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>
<script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>
<?php } ?>
<script>
	$().ready(function() {
	  $(".modal-dialog", window.parent.document).width(800);
		// validate the comment form when it is submitted
		$("#form2").validate();
<?php if(!isset($_GET['add'])) { ?>
$(".dataTable").dataTable({"iDisplayLength": -1});

<?php } ?>
<?php //if(isset($_GET['add'])) { ?>
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
<?php //} ?>
	});
function check_proportion(){
  var p = <?php echo (isset($proportion) && !empty($proportion))?$proportion:0;  ?>;
  if(document.form1.proportion.value><?php echo (isset($proportion) && !empty($proportion))?$proportion:0;  ?>){ document.form1.proportion.value=<?php echo (isset($proportion) && !empty($proportion))?$proportion:0;  ?>; }
}
</script>
<style>
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}
.dataTables_length, .dataTables_info { float: left;} .dataTables_paginate, .dataTables_filter { float: right;}
.dataTables_length, .dataTables_paginate { display: none;}

.Style2 {font-weight: bold}
</style>
</head>
<body>
<?php if(!isset($_GET['add'])) { ?>
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i>  <strong></strong><span class="Style18">
 </span><?php echo $libelle_modele;//."  ".$cat; ?> </h4>
 </div>
<div class="widget-content">
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1) && !check_user_auth('page_edit',"categorie_marche.php")) { ?>
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">
<?php } ?>
<table border="0" cellspacing="0" class="table table-bordered  table-responsive datatable" align="center" id="mtable" >
    <?php $t=0;  if($totalRows_liste_zone>0) { ?>
    <tr class="titrecorps2">
  <td width="5%">N&deg;</td>

  <td width="25%"><div align="center" class="Style31"><strong>T&acirc;ches</strong></div></td>
    <td width="7%"><div align="center"><strong>Poids  </strong></div></td>
    <td><div align="center"><strong>Date d&eacute;but </strong></div></td>
    <td><div align="center"><strong>Date fin </strong></div></td>
    <td><strong>Responsable</strong></td>
    <td width="10%"><div align="center"><strong>Nb lot </strong></div></td>
    </tr>
    <?php $p1="j"; $t=0;  $pp=$i=0;foreach($row_liste_zone as $row_liste_zone){  $i++;  ?>
    <tr <?php if(isset($suivi_array[$row_liste_zone['id_groupe_tache']]) && $suivi_array[$row_liste_zone['id_groupe_tache']]==1) echo 'bgcolor="#D2E2B1"'; ?>>
<td><span class="">
       <?php echo $row_liste_zone['ordre']; ?>
      </span></td>

<td><div align="center" class="Style31">
  <div align="left"><?php echo $row_liste_zone['intitule_tache']; ?>
      <input name="id_groupe_tache[]" type="hidden" size="5" value="<?php echo $row_liste_zone['id_groupe_tache']; ?>"/>
          <input name="proportion[]" type="hidden" size="5" value="<?php echo $row_liste_zone['proportion']; ?>"/>
          <input name="ordre[]" type="hidden" size="5" value="<?php echo $row_liste_zone['ordre']; ?>"/>
          <input name="intitule_tache[]" type="hidden" size="5" value="<?php echo $row_liste_zone['intitule_tache']; ?>"/>
   </div>
</div></td>

    <td nowrap="nowrap"><div align="center"><span class="Style31 Style2">
      <?php echo $row_liste_zone['proportion']; ?>
    </span><strong>%</strong></div></td>
    <td><div align="center"> <input class="form-control datepicker required"  type="text" name="date_debut[]" id="date_debut[]<?php echo $row_liste_zone["id_groupe_tache"] ?>"  value="<?php if(isset($date_debut_array[$row_liste_zone["id_groupe_tache"]])) echo implode('/',array_reverse(explode('-',$date_debut_array[$row_liste_zone["id_groupe_tache"]]))); //else echo date("d/m/Y"); ?>" size="10"  /></div></td>
    <td><div align="center"> <input class="form-control datepicker required"  type="text" name="date_fin[]" id="date_fin[]<?php echo $row_liste_zone["id_groupe_tache"] ?>"  value="<?php if(isset($date_fin_array[$row_liste_zone["id_groupe_tache"]])) echo implode('/',array_reverse(explode('-',$date_fin_array[$row_liste_zone["id_groupe_tache"]]))); //else echo date("d/m/Y"); ?>" size="10"  /></div></td>
    <td> <div class="form-group">
          <div class="col-md-8">
            <select name="responsable[]" id="responsable[]" class="form-control required">
            <option value="">--</option>
              <?php if($totalRows_liste_responsable>0) { foreach($row_liste_responsable as $row_liste_responsable1){   ?>
              <option value="<?php echo $row_liste_responsable1['id_personnel']?>"<?php if(isset($responsable_array[$row_liste_zone["id_groupe_tache"]])) {if (!(strcmp($row_liste_responsable1['id_personnel'], $responsable_array[$row_liste_zone["id_groupe_tache"]]))) {echo "SELECTED";} } ?>><?php echo $row_liste_responsable1['fonction']." (".$row_liste_responsable1['nom']." ".$row_liste_responsable1['prenom'].")";?></option>
              <?php } } ?>
            </select>
          </div>
        </div></td>
    <td><div align="center">
      <input class="required" type="text" style="text-align:center" name="n_lot[]" id="n_lot[]"  value="<?php if(isset($n_lot_array[$row_liste_zone["id_groupe_tache"]]) && !empty($n_lot_array[$row_liste_zone["id_groupe_tache"]])) echo $n_lot_array[$row_liste_zone["id_groupe_tache"]]; else echo "1"; ?>" size="5" />
    </div></td>
    </tr>
    <?php } ?>
    <?php } else echo "<h3>Aucune tâche saisie</h3>" ;?>
  </table>
<?php if(isset($_SESSION['clp_niveau'])) { ?>
<div class="form-actions">
<?php if(isset($_GET["id_act"])){ ?>
<input type="hidden" name="cat" value="<?php echo $cat; ?>" />
<input type="hidden" name="id_act" value="<?php echo $id_act; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">

<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>
<?php } ?>
</div>
</div>
</div>
<?php } elseif(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)){ ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification d'étape":"Nouvelle étape"; ?></h4>
<a href="<?php echo $lien1; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="<?php echo $lien1; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
     <tr valign="top">
      <td>
      <div class="form-group">
          <label for="code" class="col-md-12 control-label">N&deg; ordre <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control required" type="text" name="ordre" id="ordre" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_etape['ordre']; ?>" size="10" style="width: 90px;" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_edit_etape['ordre']."'"; ?>) check_code('verif_code.php?t=type_tache&','w=ordre='+this.value+' and 	type_activite=<?php echo $cat; ?>','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>          </div>
        </div>      </td>
      <td><div class="form-group">
          <label for="proportion" class="col-md-12 control-label">Proportion (%) <span class="required">*</span></label>
          <div class="col-md-3">
         <input name="proportion" type="text" class="form-control required" id="proportion" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_etape['proportion'];?>" size="25" />
          </div>
        </div> </td>
    </tr>
<tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="intitule_tache" class="col-md-12 control-label">Intitulé de la tâche <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control required" cols="200" rows="2" type="text" name="intitule_tache" id="intitule_tache"><?php if(isset($_GET['id'])) echo $row_edit_etape['intitule_tache']; ?></textarea>
          </div>
        </div>      </td>
    </tr>
<tr valign="top">
  <td colspan="2">&nbsp;</td>
</tr>
</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo ($_GET["id"]); ?>" />
<?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
<a href="<?php echo $lien1; ?>" class="btn pull-right" title="Annuler" >Annuler</a>
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2)) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette &eacute;tape ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>
</div> </div>
<?php } ?>

<?php include_once 'modal_add.php'; ?>
</body>
</html>