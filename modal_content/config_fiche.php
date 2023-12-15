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

if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y"); if(isset($_GET['cp'])) {$cp=$_GET['cp'];} else $cp=0;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$page = $_SERVER['PHP_SELF'];
$mode_calcul = array("SOMME","MOYENNE","COMPTER");

$lien = $lien1 = $lien0 = $lien2 = $lien3 = $_SERVER['PHP_SELF'];
$lien .= "?annee=$annee&cp=$cp&add_lien=ok&add=1";
$lien0 .= "?annee=$annee&cp=$cp&add_lien0=ok&add=1";
$lien2 .= "?annee=$annee&cp=$cp&add_lien2=ok&add=1";
$lien3 .= "?annee=$annee&cp=$cp&add_lien3=ok&add=1";
$lien1 .= "?annee=$annee&cp=$cp";

if(isset($_GET["id_sup"]))
{
  $id=$_GET["id_sup"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_sup_act = "DELETE FROM ".$database_connect_prefix."indicateur_config WHERE id='$id' and projet='".$_SESSION["clp_projet"]."'";
  $Result1 = mysql_query_ruche($query_sup_act, $pdar_connexion) or die(mysql_error());
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok&annee=$annee&cp=$cp";
  else $insertGoTo .= "?del=no&annee=$annee&cp=$cp";
  //header(sprintf("Location: %s", $insertGoTo));
}

if(isset($_GET["id"]) && intval($_GET["id"])>0){
  $id=$_GET["id"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_edit_act = "SELECT * FROM ".$database_connect_prefix."indicateur_config where id='$id'";
  $edit_act = mysql_query_ruche($query_edit_act, $pdar_connexion) or die(mysql_error());
  $row_edit_act = mysql_fetch_assoc($edit_act);
  $totalRows_edit_act = mysql_num_rows($edit_act);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {

$poids_max=2048576; //Poids maximal du fichier en octets
$extensions_autorisees=array('rar','doc','pdf', 'zip', 'docx'); //Extensions autorisées
if(isset($_FILES['icone']) && $_FILES['icone']['error'] == 0 && $_FILES['icone']['size']>$poids_max)
{
$message='Un ou plusieurs fichiers sont trop lourds !';
echo $message;  exit;
}elseif(isset($_FILES['icone']) && $_FILES['icone']['error'] == 0)
{
 $nom1=$_FILES['icone']['name']; $cp0=$_POST['fiche']; $col[1]=$nom1;
 $_POST['indicateur']=$nom1;
 move_uploaded_file($_FILES['icone']['tmp_name'],"../map/js/theme/default/markers/".$nom1);
}

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
if(!isset($_FILES['icone'])){ $col=explode("|",$_POST['colonne']); $cp0=$col[0];   }

  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."indicateur_config (id_fiche, col, type, ind, mode_calcul, couleur, etat,  projet, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, '$personnel', '$date')",
						  GetSQLValueString($cp0, "text"),
                          GetSQLValueString($col[1], "text"),
						  GetSQLValueString($_POST['type'], "text"),
                          GetSQLValueString($_POST['indicateur'], "int"),
                          GetSQLValueString($_POST['mode_calcul'], "text"),
                          GetSQLValueString($_POST['couleur'], "text"),
                          GetSQLValueString($_POST['etat'], "int"),
                          GetSQLValueString($_SESSION["clp_projet"], "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok&annee=$annee&cp=$cp";
  else $insertGoTo .= "?insert=no&annee=$annee&cp=$cp";
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {

$poids_max=2048576; //Poids maximal du fichier en octets
$extensions_autorisees=array('rar','doc','pdf', 'zip', 'docx'); //Extensions autorisées
if(isset($_FILES['icone']) && $_FILES['icone']['error'] == 0 && $_FILES['icone']['size']>$poids_max)
{
$message='Un ou plusieurs fichiers sont trop lourds !';
echo $message;  exit;
}elseif(isset($_FILES['icone']) && $_FILES['icone']['error'] == 0)
{
 $nom1=$_FILES['icone']['name']; $cp0=$_POST['fiche']; $col[1]=$nom1;
 $_POST['indicateur']=$nom1;
 move_uploaded_file($_FILES['icone']['tmp_name'],"../map/js/theme/default/markers/".$nom1);
}

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id'];
if(!isset($_FILES['icone'])){ $col=explode("|",$_POST['colonne']); $cp0=$col[0]; }

	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."indicateur_config SET id_fiche=%s, col=%s, type=%s, ind=%s, mode_calcul=%s, couleur=%s, etat=%s WHERE id='$c'",
					   GetSQLValueString($cp0, "text"),
					   GetSQLValueString($col[1], "text"),
					   GetSQLValueString($_POST['type'], "text"),
                       GetSQLValueString($_POST['indicateur'], "int"),
                       GetSQLValueString($_POST['mode_calcul'], "text"),
                       GetSQLValueString($_POST['couleur'], "text"),
                       GetSQLValueString($_POST['etat'], "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?update=ok&annee=$annee&cp=$cp";
  else $insertGoTo .= "?update=no&annee=$annee&cp=$cp";
  //header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_tache = "select * FROM ".$database_connect_prefix."indicateur_config where id_fiche='$cp' and projet='".$_SESSION["clp_projet"]."' UNION (select * FROM ".$database_connect_prefix."indicateur_config where id_fiche LIKE '".$cp."_details%' and projet='".$_SESSION["clp_projet"]."') ORDER BY type";
$tache  = mysql_query_ruche($query_tache , $pdar_connexion) or die(mysql_error());
$row_tache = mysql_fetch_assoc($tache);
$totalRows_tache  = mysql_num_rows($tache);

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$cp'";
  $entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error());
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);
  $entete_array = array(); $choix_array = array(); $libelle = array();
  if($totalRows_entete>0){ $entete_array=explode("|",$row_entete["show"]); $libelle=explode("|",$row_entete["libelle"]); }

  $libelle_array=array();
  foreach ($libelle as $k=>$v){
  $a=explode("=",$v); if(isset($a[1]) && !empty($a[1])/* && $a[0]!=$cp*/) $libelle_array[$a[0]]=$a[1]; }

if(isset($_GET['add']))
{
  //les détails
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_cp = "SHOW tables";
  $liste_cp = mysql_query_ruche($query_liste_cp, $pdar_connexion) or die(mysql_error());
  $row_liste_cp = mysql_fetch_assoc($liste_cp);
  $totalRows_liste_cp = mysql_num_rows($liste_cp);

  $cp_array=array();
  if($totalRows_liste_cp>0) {
  do { if(strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!="" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!=$database_connect_prefix."fiche_config" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche_".$cp."details_")!=""){  $cp_array[]=$row_liste_cp["Tables_in_$database_pdar_connexion"];  //if(strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="fiche_config" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],$cp."_details")!=""){  $cp_array[]=$row_liste_cp["Tables_in_$database_pdar_connexion"];
  }
  } while ($row_liste_cp = mysql_fetch_assoc($liste_cp));
  $rows = mysql_num_rows($liste_cp);
  if($rows > 0) {
  mysql_data_seek($liste_cp, 0);
  $row_liste_cp = mysql_fetch_assoc($liste_cp);
  }}

  $libelle_array1=array();

  if(count($cp_array)>0){   //les colonnes des détails
  foreach($cp_array as $f){
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$f'";
  $entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error());
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);
  $entete_array = array(); $choix_array = array(); $libelle1 = array();
  if($totalRows_entete>0){ $entete_array=explode("|",$row_entete["show"]); $libelle1=explode("|",$row_entete["libelle"]); }


  foreach ($libelle1 as $k=>$v){
  $a=explode("=",$v); if(isset($a[1])) $libelle_array1[$a[0]]=$a[1]; }  }
  }
}

  //cmr
  $cmr_array =array();
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_cmr = "SELECT * FROM ".$database_connect_prefix."referentiel_indicateur WHERE type_ref_ind=1 and mode_calcul='Unique' and projet='".$_SESSION["clp_projet"]."' order by intitule_ref_ind";
  //$query_cmr = "SELECT * FROM ".$database_connect_prefix."referentiel_indicateur, ".$database_connect_prefix."produit, ".$database_connect_prefix."indicateur_produit, ".$database_connect_prefix."sous_composante WHERE id_produit=produit and indicateur_prd=id_indicateur_produit and sous_composante=id_sous_composante order by code_sous_composante,code_produit";
  $cmr  = mysql_query_ruche($query_cmr , $pdar_connexion) or die(mysql_error());
  $row_cmr  = mysql_fetch_assoc($cmr);
  $totalRows_cmr  = mysql_num_rows($cmr);
  if($totalRows_cmr>0){ do{
  $cmr_array[$row_cmr["id_ref_ind"]]=$row_cmr["intitule_ref_ind"]." (".$row_cmr["unite"].")";  }while($row_cmr  = mysql_fetch_assoc($cmr)); }

  //sygri
  /*$sygri_array =array();
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_sygri = "SELECT * FROM ".$database_connect_prefix."indicateur_sygri1_projet,composante WHERE composante=id_composante order by code_composante,ordre";
  $sygri  = mysql_query_ruche($query_sygri , $pdar_connexion) or die(mysql_error());
  $row_sygri  = mysql_fetch_assoc($sygri);
  $totalRows_sygri  = mysql_num_rows($sygri);
  if($totalRows_sygri>0){ do{
  $sygri_array[$row_sygri["id_indicateur_sygri_niveau1_projet"]]=$row_sygri["indicateur_sygri_niveau1"]." (".$row_sygri["unite"].")";    }while($row_sygri  = mysql_fetch_assoc($sygri)); }  */

  //PTBA
  $appendice_array =array();
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_appendice4 = "SELECT intitule_indicateur_tache,code_activite_ptba,intitule_activite_ptba,id_indicateur_tache FROM ".$database_connect_prefix."ptba, ".$database_connect_prefix."indicateur_tache  where code_activite_ptba=code_activite and ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' ORDER BY code_activite_ptba,intitule_indicateur_tache";
  $appendice4  = mysql_query_ruche($query_appendice4 , $pdar_connexion) or die(mysql_error());
  $row_appendice4  = mysql_fetch_assoc($appendice4);
  $totalRows_appendice4  = mysql_num_rows($appendice4);
  if($totalRows_appendice4>0){ do{
  $appendice_array[$row_appendice4["id_indicateur_tache"]]=$row_appendice4["code_activite_ptba"].": ".$row_appendice4["intitule_indicateur_tache"];  }while($row_appendice4  = mysql_fetch_assoc($appendice4)); }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="<?php print $path; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="<?php print $path; ?>plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->
<link rel="stylesheet" type="text/css" href="<?php print $path.$config->theme_folder;?>/plugins/jquery-ui.css"/>
<link href="<?php print $path.$config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $path.$config->theme_folder;?>/responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $path.$config->theme_folder;?>/icons.css" rel="stylesheet" type="text/css"/>
<link href='<?php print $path.$config->theme_folder;?>/css.css' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php print $path.$config->theme_folder; ?>/fontawesome/font-awesome.min.css">
<link href="<?php print $path.$config->theme_folder; ?>/plugins/datatables_bootstrap.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $path.$config->theme_folder; ?>/plugins/datatables.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $path.$config->theme_folder;?>/plugins/bootstrap-colorpicker.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php print $path.$config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="<?php print $path; ?>plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="<?php print $path; ?>plugins/noty/jquery.noty.js"></script>
<script type="text/javascript" src="<?php print $path; ?>plugins/noty/layouts/top.js"></script>
<script type="text/javascript" src="<?php print $path; ?>plugins/noty/themes/default.js"></script>
<script type="text/javascript" src="<?php print $path.$config->script_folder;?>/myscript.js"></script>
<script type="text/javascript" src="<?php print $path; ?>plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php print $path; ?>plugins/pickadate/picker.js"></script>
<script type="text/javascript" src="<?php print $path; ?>bootstrap/js/bootstrap-typeahead.min.js"></script>
<script type="text/javascript" src="<?php print $path; ?>bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="<?php print $path; ?>plugins/bootstrap-wysihtml5/wysihtml5.min.js"></script>
<script type="text/javascript" src="<?php print $path; ?>plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.min.js"></script>
<script type="text/javascript" src="<?php print $path; ?>plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="<?php print $path; ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php print $path; ?>plugins/datatables/DT_bootstrap.js"></script>
<script type="text/javascript" src="<?php print $path; ?>plugins/datatables/responsive/datatables.responsive.js"></script>
<script type="text/javascript" src="<?php print $path; ?>plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form2").validate();
        $(".modal-dialog", window.parent.document).width(700);
        $(".colorpicker").remove();
        $(".bs-colorpicker").colorpicker();
        $(".colorpicker").attr("style","z-index:10060");
	});
</script>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}
.dataTables_length, .dataTables_info { float: left; font-size: 10px;}
.dataTables_length, .dataTables_paginate { display: none;}
</style>
</head>

<body>

<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Indicateur config</h4>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){
echo do_link("",$lien,"Ajouter un lien CMR","Ajouter un lien CMR","","./","pull-right p11","",1,"",$nfile);
//echo do_link("",$lien0,"Ajouter un lien SYGRI","Ajouter un lien SYGRI","","./","pull-right p11","",1,"",$nfile);
echo do_link("",$lien2,"Ajouter un lien PTBA","Ajouter un lien PTBA","","./","pull-right p11","",1,"",$nfile);
//echo do_link("",$lien3,"Ic&ocirc;ne","Ic&ocirc;ne","","./","pull-right p11","",1,"",$nfile);
 } ?>
</div>
<div class="widget-content">
<?php if(!isset($_GET['add'])){ ?>
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >
            <?php $t=0;  if($totalRows_tache>0) { ?>
            <thead>
            <tr class="titrecorps2">
              <th>Type</th>
              <th>Indicateurs</th>
              <!--<th>Fiche</th>-->
              <th>Colonne</th>
              <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
              <th width="90">Actions</th>
              <?php } ?>
            </tr>
            </thead>
            <tbody>
            <?php $p1="j"; $sp=0; $i=0;do {
            if($row_tache['type']!="PTBA" || isset($appendice_array[$row_tache['ind']])){ ?>
                <tr <?php if($i%2==0) echo 'bgcolor="#D2E2B1"';  $i=$i+1; $t=$t+1;?>>
                <td ><u><span class="Style12"><?php echo $row_tache['type']; ?></span></u></td>
                <td><?php if($row_tache['type']=="CMR"){ echo (isset($cmr_array[$row_tache['ind']]))?$cmr_array[$row_tache['ind']]:"ND"; }elseif($row_tache['type']=="SYGRI"){   echo (isset($sygri_array[$row_tache['ind']]))?$sygri_array[$row_tache['ind']]:"ND"; }elseif($row_tache['type']=="ICONE"){ echo "L'ic&ocirc;ne repr&eacute;sentative sur la carte"; }else{   echo (isset($appendice_array[$row_tache['ind']]))?$appendice_array[$row_tache['ind']]:"ND"; }  ?>&nbsp;</td>
                <!--<td align="center"><?php echo $row_tache['id_fiche'];  ?></td>-->
                <td><?php if(isset($libelle_array[$row_tache['col']])) echo $libelle_array[$row_tache['col']]; elseif(isset($libelle_array1[$row_tache['col']])) echo $libelle_array1[$row_tache['col']]; elseif(isset($libelle_array2[$row_tache['col']])) echo $libelle_array2[$row_tache['col']]; elseif($row_tache['type']=="ICONE"){ $icone_exist = 1; echo '<img src="../map/js/theme/default/markers/'.$row_tache['col'].'" width="21" height="25" alt="" />'; } else echo $row_tache['col'];  ?></td>
                <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
                 <td align="center">
<?php
if($row_tache['type']=="CMR") $lieneidit = $lien."&id=".$row_tache['id']; elseif($row_tache['type']=="SYGRI") $lieneidit = $lien0."&id=".$row_tache['id']; elseif($row_tache['type']=="ICONE") $lieneidit = $lien3."&id=".$row_tache['id']; else $lieneidit = $lien2."&id=".$row_tache['id'];
echo do_link("","$lieneidit","Modifier le lien ","","edit","../","","",1,"margin:0px 5px;",$nfile);

echo do_link("",$lien1."?id_sup=".$row_tache['id'],"Supprimer","","del","../","","return confirm('Voulez-vous vraiment supprimer ce lien ?');",0,"margin:0px 5px;",$nfile);
?>
<!--<a href="<?php if($row_tache['type']=="CMR") echo $lien."&id=".$row_tache['id']; elseif($row_tache['type']=="SYGRI") echo $lien0."&id=".$row_tache['id']; elseif($row_tache['type']=="ICONE") echo $lien3."&id=".$row_tache['id']; else echo $lien2."&id=".$row_tache['id']; ?>" title="Modifier le lien" ><img align="center" src='../images/edit.png' width='20' height='20' alt='Modifier'></a>
<a onclick="return confirm('Voulez vous vraiment suppimer ce lien ?');" href="<?php echo $lien1."&id_sup=".$row_tache['id']; ?>" title="Supprimer le lien" ><img align="center" src='../images/delete.png' width='15' height='15' alt='Supprimer'></a>-->
</td>
                <?php } ?>
                </tr>
              <?php } } while ($row_tache = mysql_fetch_assoc($tache)); ?>
            <?php } else echo "<h3 align='center'>Aucune donn&eacute;e pour cette fiche </h3>" ;?>
            </tbody>
            </table>
<?php }else{ ?>

<div class="widget box">
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<?php if(isset($_GET["add_lien"])) { ?>
<div class="widget-header"> <h4><i class="icon-reorder"></i> R&eacute;f&eacute;rencement indicateur CMR</h4>
<a href="<?php echo $lien1; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="<?php echo $lien1; ?>" method="post" name="form2" id="form2" onsubmit="return verifform(this,1);">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
     <tr valign="top">
      <td>
        <div class="form-group">
          <label for="colonne" class="col-md-11 control-label">Colonne <span class="required">*</span></label>
          <div class="col-md-12">
<select name="colonne" id="colonne" class="form-control required">
                          <optgroup style="background-color:#FFCC66" label="<?php $n=explode("=",$libelle[count($libelle)-2]); echo (isset($n[1]))?$n[1]:$n[0]; ?>"></optgroup>
                          <?php foreach($libelle_array as $c=>$l){   ?>
                          <option <?php if(isset($_GET['id']) && isset($row_edit_act["col"]) && $row_edit_act["col"]==$c) echo 'selected="selected"'; ?> value="<?php echo $cp."|".$c; ?>"><?php echo $l; ?></option>
                        <?php  } ?>
                        <?php if(count($cp_array)>0){   //les colonnes des détails
                          foreach($cp_array as $f){   ?>

<?php
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$f'";
  $entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error());
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);
  $entete_array = array(); $choix_array = array(); $libelle = array();
  if($totalRows_entete>0){ $entete_array=explode("|",$row_entete["show"]); $libelle=explode("|",$row_entete["libelle"]); $nom = $row_entete["nom"]; }

$libelle_array=array();
foreach ($libelle as $k=>$v){
$a=explode("=",$v); if(isset($a[1]) && !empty($a[1]) && $a[0]!=$f) $libelle_array[$a[0]]=$a[1]; }
if(isset($libelle[count($libelle)-1])){ ?>
<optgroup style="background-color:#FFCC66" label="<?php $n=explode("=",$libelle[count($libelle)-1]); echo (isset($n[1]))?$n[1]:$n[0]; ?>"></optgroup>
<?php }
foreach($libelle_array as $c=>$l){  ?>
                          <option <?php if(isset($_GET['id']) && isset($row_edit_act["col"]) && $row_edit_act["col"]==$c) echo 'selected="selected"'; ?> value="<?php echo $f."|".$c; ?>"><?php echo $l; ?></option>
                        <?php } ?>

                         <?php  }
                        } ?>
                </select>
          </div>
        </div>
      </td>
    </tr>
	<tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="indicateur" class="col-md-11 control-label">Indicateurs CMR <span class="required">*</span></label>
          <div class="col-md-12">
<select name="indicateur" id="indicateur" class="form-control required"   >
              <option value="">-- Choisissez --</option>
                               <?php
$tem = "j";
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cmr = "SELECT * FROM ".$database_connect_prefix."referentiel_indicateur WHERE type_ref_ind=1 and mode_calcul='Unique' and projet='".$_SESSION["clp_projet"]."' order by intitule_ref_ind ";
//$query_cmr = "SELECT * FROM ".$database_connect_prefix."indicateur_produit_cmr, ".$database_connect_prefix."produit, ".$database_connect_prefix."indicateur_produit, ".$database_connect_prefix."sous_composante WHERE id_produit=produit and indicateur_prd=id_indicateur_produit and sous_composante=id_sous_composante order by code_sous_composante,code_produit";
$cmr  = mysql_query_ruche($query_cmr , $pdar_connexion) or die(mysql_error());
$row_cmr  = mysql_fetch_assoc($cmr);
$totalRows_cmr  = mysql_num_rows($cmr);
if($totalRows_cmr>0){ do{
  if($tem!=$row_cmr["code_produit"]) echo '<optgroup style="background-color:#FFCC66" label="'.$row_cmr["intitule_produit"].'"></optgroup> ';
              ?>
                                 <option value="<?php echo $row_cmr["id_ref_ind"];?>"<?php if(isset($_GET['id']) && isset($row_edit_act["ind"]) && $row_edit_act["ind"]==$row_cmr["id_ref_ind"]) echo "SELECTED"; ?>><?php echo $row_cmr["code_ref_ind"]." : ".$row_cmr["intitule_ref_ind"]." (".$row_cmr["unite"].")"; ; ?></option>    <?php $tem=$row_cmr["code_produit"];   }while($row_cmr  = mysql_fetch_assoc($cmr));
}     ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
  <?php if(isset($_GET['id'])) {  ?>
  <input type="hidden" name="id" id="id" value="<?php if(isset($_GET['id'])) echo $_GET['id'];  ?>" />
  <?php }  ?>
  <input type="hidden" name="type" id="type" value="CMR" />
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET['id'])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input type="hidden" name="<?php if(isset($_GET['id'])) echo "MM_update"; else echo "MM_insert";  ?>" value="form2" />
</div>
</form>

</div>
<?php } ?>

<?php
if(isset($_GET["add_lien0"])) { ?>
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php if(isset($_GET['id'])) echo "Modifier le lien SYGRI"; else echo "Nouveau lien SYGRI" ; ?></h4>
<a href="<?php echo $lien1; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="<?php echo $lien1; ?>" method="post" name="form2" id="form2" onsubmit="return verifform(this,1);">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
     <tr valign="top">
      <td>
        <div class="form-group">
          <label for="colonne" class="col-md-11 control-label">Colonne <span class="required">*</span></label>
          <div class="col-md-12">
<select name="colonne" id="colonne" class="form-control required">
                          <optgroup style="background-color:#FFCC66" label="<?php $n=explode("=",$libelle[count($libelle)-2]); echo (isset($n[1]))?$n[1]:$n[0]; ?>"></optgroup>
                          <?php foreach($libelle_array as $c=>$l){   ?>
                          <option <?php if(isset($_GET['id']) && isset($row_edit_act["col"]) && $row_edit_act["col"]==$c) echo 'selected="selected"'; ?> value="<?php echo $cp."|".$c; ?>"><?php echo $l; ?></option>
                        <?php  } ?>
                        <?php if(count($cp_array)>0){   //les colonnes des détails
                          foreach($cp_array as $f){   ?>

<?php
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$f'";
  $entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error());
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);
  $entete_array = array(); $choix_array = array(); $libelle = array();
  if($totalRows_entete>0){ $entete_array=explode("|",$row_entete["show"]); $libelle=explode("|",$row_entete["libelle"]); $nom = $row_entete["nom"]; }

$libelle_array=array();
foreach ($libelle as $k=>$v){
$a=explode("=",$v); if(isset($a[1]) && !empty($a[1]) && $a[0]!=$f) $libelle_array[$a[0]]=$a[1]; }
if(isset($libelle[count($libelle)-1])){ ?>
<optgroup style="background-color:#FFCC66" label="<?php $n=explode("=",$libelle[count($libelle)-1]); echo (isset($n[1]))?$n[1]:$n[0]; ?>"></optgroup>
<?php }
foreach($libelle_array as $c=>$l){  ?>
                          <option <?php if(isset($_GET['id']) && isset($row_edit_act["col"]) && $row_edit_act["col"]==$c) echo 'selected="selected"'; ?> value="<?php echo $f."|".$c; ?>"><?php echo $l; ?></option>
                        <?php } ?>

                         <?php  }
                        } ?>
                </select>
          </div>
        </div>
      </td>
    </tr>
	<tr valign="top">
      <td>
        <div class="form-group">
          <label for="indicateur" class="col-md-11 control-label">Indicateurs SYGRI <span class="required">*</span></label>
          <div class="col-md-12">
      <select name="indicateur" id="indicateur" class="form-control required"  >
              <option value="">-- Choisissez --</option>

                               <?php
$tem = "j";
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sygri = "SELECT * FROM ".$database_connect_prefix."indicateur_sygri1_projet, ".$database_connect_prefix."composante WHERE composante=id_composante and projet='".$_SESSION["clp_projet"]."' order by code_composante,ordre";
$sygri  = mysql_query_ruche($query_sygri , $pdar_connexion) or die(mysql_error());
$row_sygri  = mysql_fetch_assoc($sygri);
$totalRows_sygri  = mysql_num_rows($sygri);
if($totalRows_sygri>0){ do{
  if($tem!=$row_sygri["composante"]) echo '<optgroup style="background-color:#FFCC66" label="'.$row_sygri["code_composante"].': '.$row_sygri["intitule_composante"].'"></optgroup> ';
              ?>
                                 <option value="<?php echo $row_sygri["id_indicateur_sygri_niveau1_projet"];?>"<?php if(isset($_GET['id']) && isset($row_edit_act["ind"]) && $row_edit_act["ind"]==$row_sygri["id_indicateur_sygri_niveau1_projet"]) echo "SELECTED";?>><?php echo $row_sygri["indicateur_sygri_niveau1"]." (".$row_sygri["unite"].")"; ; ?></option>    <?php $tem=$row_sygri["composante"];   }while($row_sygri  = mysql_fetch_assoc($sygri));
}
     ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
  <?php if(isset($_GET['id'])) {  ?>
  <input type="hidden" name="id" id="id" value="<?php if(isset($_GET['id'])) echo $_GET['id'];  ?>" />
  <?php }  ?>
  <input type="hidden" name="type" id="type" value="SYGRI" />
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET['id'])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input type="hidden" name="<?php if(isset($_GET['id'])) echo "MM_update"; else echo "MM_insert";  ?>" value="form2" />
</div>
</form>

</div>
<?php } ?>

<?php
if(isset($_GET["add_lien2"])) { ?>
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php if(isset($_GET['id'])) echo "Modifier le lien PTBA"; else echo "Nouveau lien PTBA" ; ?></h4>
<a href="<?php echo $lien1; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="<?php echo $lien1; ?>" method="post" name="form2" id="form2" onsubmit="return verifform(this,1);">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
     <tr valign="top">
      <td>
        <div class="form-group">
          <label for="colonne" class="col-md-11 control-label">Colonne <span class="required">*</span></label>
          <div class="col-md-12">
<select name="colonne" id="colonne" class="form-control required">
                          <optgroup style="background-color:#FFCC66" label="<?php $n=explode("=",$libelle[count($libelle)-2]); echo (isset($n[1]))?$n[1]:$n[0]; ?>"></optgroup>
                          <?php foreach($libelle_array as $c=>$l){   ?>
                          <option <?php if(isset($_GET['id']) && isset($row_edit_act["col"]) && $row_edit_act["col"]==$c) echo 'selected="selected"'; ?> value="<?php echo $cp."|".$c; ?>"><?php echo $l; ?></option>
                        <?php  } ?>
                        <?php if(count($cp_array)>0){   //les colonnes des détails
                          foreach($cp_array as $f){   ?>

<?php
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$f'";
$entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);
$entete_array = array(); $choix_array = array(); $libelle = array();
if($totalRows_entete>0){ $entete_array=explode("|",$row_entete["show"]); $libelle=explode("|",$row_entete["libelle"]); $nom = $row_entete["nom"]; }

$libelle_array=array();
foreach ($libelle as $k=>$v){
$a=explode("=",$v); if(isset($a[1]) && !empty($a[1]) && $a[0]!=$f) $libelle_array[$a[0]]=$a[1]; }
if(isset($libelle[count($libelle)-1])){ ?>
<optgroup style="background-color:#FFCC66" label="<?php $n=explode("=",$libelle[count($libelle)-1]); echo (isset($n[1]))?$n[1]:$n[0]; ?>"></optgroup>
<?php }
foreach($libelle_array as $c=>$l){  ?>
                          <option <?php if(isset($_GET['id']) && isset($row_edit_act["col"]) && $row_edit_act["col"]==$c) echo 'selected="selected"'; ?> value="<?php echo $f."|".$c; ?>"><?php echo $l; ?></option>
                        <?php } ?>

                         <?php  }
                        } ?>
                </select>
          </div>
        </div>
      </td>
    </tr>
	<tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="indicateur" class="col-md-11 control-label">Valeur cible PTBA <?php echo $annee; ?> <span class="required">*</span></label>
          <div class="col-md-12">
<select name="indicateur" id="indicateur" class="form-control required"  >
              <option value="">-- Choisissez --</option>

                               <?php
$tem = "j";
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_ptba = "SELECT intitule_indicateur_tache,code_activite_ptba,intitule_activite_ptba,id_indicateur_tache, ".$database_connect_prefix."indicateur_tache.annee FROM ".$database_connect_prefix."ptba, ".$database_connect_prefix."indicateur_tache  where code_activite_ptba=code_activite and ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY id_activite ORDER BY ".$database_connect_prefix."indicateur_tache.annee,code_activite_ptba,intitule_indicateur_tache";
$ptba  = mysql_query_ruche($query_ptba , $pdar_connexion) or die(mysql_error());
$row_ptba  = mysql_fetch_assoc($ptba);
$totalRows_ptba  = mysql_num_rows($ptba);
if($totalRows_ptba>0){ do{
  if($tem!=$row_ptba["code_activite_ptba"]) echo '<optgroup style="background-color:#FFCC66" label="'.$row_ptba["code_activite_ptba"].': '.$row_ptba["intitule_activite_ptba"].'"></optgroup> ';
              ?>
                                 <option value="<?php echo $row_ptba["id_indicateur_tache"];?>"<?php if(isset($_GET['id']) && isset($row_edit_act["ind"]) && $row_edit_act["ind"]==$row_ptba["id_indicateur_tache"]) echo "SELECTED";?>><?php echo $row_ptba["intitule_indicateur_tache"]; ; ?></option>    <?php $tem=$row_ptba["code_activite_ptba"];   }while($row_ptba  = mysql_fetch_assoc($ptba));
}
     ?>

            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="mode_calcul" class="col-md-3 control-label">Mode de calcul </label>
          <div class="col-md-9">
            <select name="mode_calcul" id="mode_calcul" class="form-control required" >
              <option value="">Selectionnez</option>
              <?php foreach($mode_calcul as $a){ ?>
              <option value="<?php echo $a; ?>" <?php if (isset($row_edit_act['mode_calcul']) && ($a==$row_edit_act['mode_calcul'] || empty($row_edit_act['mode_calcul']))) {echo "SELECTED";} ?> ><?php echo $a; ?></option>
                <?php  } ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="etat" class="col-md-3 control-label">Accueil </label>
          <div class="col-md-9">
            <select name="etat" id="etat" class="form-control required" >
              <option value="0" <?php if (isset($row_edit_act['etat']) && $row_edit_act['etat']==0) {echo "SELECTED";} ?>>Non</option>
              <option value="1" <?php if (isset($row_edit_act['etat']) && $row_edit_act['etat']==1) {echo "SELECTED";} ?>>Oui</option>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <div class="form-group">
          <label for="couleur" class="col-md-3 control-label">Couleur</label>
          <div class="col-md-9">
            <input data-colorpicker-guid="1" data-color-format="hex" class="form-control bs-colorpicker" type="text" name="couleur" id="couleur" value="<?php echo isset($row_edit_act['couleur'])?$row_edit_act['couleur']:""; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
  <?php if(isset($_GET['id'])) {  ?>
  <input type="hidden" name="id" id="id" value="<?php if(isset($_GET['id'])) echo $_GET['id'];  ?>" />
  <?php }  ?>
  <input type="hidden" name="type" id="type" value="PTBA" />
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET['id'])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input type="hidden" name="<?php if(isset($_GET['id'])) echo "MM_update"; else echo "MM_insert";  ?>" value="form2" />
</div>
</form>

</div>
<?php } ?>

<?php
if(isset($_GET["add_lien3"])) { ?>
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php if(isset($_GET['id'])) echo "Modifier Ic&ocirc;ne"; else echo "Nouveau Ic&ocirc;ne" ; ?></h4>
<a href="<?php echo $lien1; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="<?php echo $lien1; ?>" method="post" name="form2" id="form2" onsubmit="return verifform(this,1);">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="icone" class="col-md-11 control-label">Icône Format (25x25 pixel) <span class="required">*</span></label>
          <div class="col-md-12">
          <input class="form-control required" type="file" name="icone" id="icone" value="" />
            <input type="hidden" name="fiche" id="fiche" value="<?php echo $cp; ?>" />
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
  <?php if(isset($_GET['id'])) {  ?>
  <input type="hidden" name="id" id="id" value="<?php if(isset($_GET['id'])) echo $_GET['id'];  ?>" />
  <?php }  ?>
  <input type="hidden" name="type" id="type" value="ICONE" />
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET['id'])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input type="hidden" name="<?php if(isset($_GET['id'])) echo "MM_update"; else echo "MM_insert";  ?>" value="form2" />
</div>
</form>

</div>

<?php } ?>

<?php } ?>
 </div>
 <?php } ?>
</div>
 </div>

</body>
</html>
