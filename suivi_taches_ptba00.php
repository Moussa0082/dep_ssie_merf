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
//header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else $annee=date("Y");
if(isset($_GET['id_act'])) { $id_act = $_GET['id_act']; }
if(isset($_GET['code_act'])) { $code_act = $_GET['code_act']; }

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$page = $_SERVER['PHP_SELF'];

$lien = $lien1 = $_SERVER['PHP_SELF'];
$lien .= "?id_act=$id_act&annee=$annee&code_act=$code_act";
$lien1 .= "?id_act=$id_act&annee=$annee&code_act=$code_act";

if ((isset($_GET["id_sup"]) && !empty($_GET["id_sup"]))) {
  $id = intval($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."suivi_tache WHERE tache=%s",
                       GetSQLValueString($id, "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  if ($Result1) $lien .= "&del=ok"; else $lien .= "&del=no";
  $lien .= "&mod=1";
  header(sprintf("Location: %s", $lien)); exit();
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
if(isset($_POST['valide']) && $_POST['valide']=="non")
{
  $_POST['valide']=$_POST['valide'];
  $_POST['observations']="";
  $_POST['realisation']="00-00-0000";
}
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_tache = "select code_activite FROM ".$database_connect_prefix."groupe_tache where id_groupe_tache='".$_POST["id_tache"]."' and annee='$annee' and projet='".$_SESSION["clp_projet"]."' ";
$tache  = mysql_query($query_tache , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_tache = mysql_fetch_assoc($tache);
//$totalRows_tache  = mysql_num_rows($tache);
$code_activite=$row_tache['code_activite'];
$key = date("ymdis").$_SESSION['clp_n'];

    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."suivi_tache (id_suivi, activite_ptba, tache, date_suivi, observation, projet, id_personnel, date_enregistrement) VALUES ('$key', %s, %s, %s, %s, %s, '$personnel', '$date')",

  			    GetSQLValueString($code_activite, "text"),
                GetSQLValueString($_POST['id_tache'], "int"),
                GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_suivi']))), "date"),
                GetSQLValueString($_POST['observations'], "text"),
                GetSQLValueString($_SESSION["clp_projet"], "text"));

  	  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  	  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

  if ($Result1) $lien .= "&insert=ok"; else $lien .= "&insert=no";
  $lien .= "&mod=1";
  header(sprintf("Location: %s", $lien));
}

//activite
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = "SELECT * FROM ".$database_connect_prefix."ptba where id_ptba='$id_act' and annee='$annee' and projet='".$_SESSION["clp_projet"]."'";
$act  = mysql_query($query_act , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_act  = mysql_fetch_assoc($act);
$totalRows_act  = mysql_num_rows($act);
$code_act=$row_act['code_activite_ptba'];
$type_activite=substr($row_act['code_activite_ptba'],0, 3);
//$mois_act = explode(",", $row_act['debut']);
//$acteur_act=$row_act['acteur_conserne'];

//query tache
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_tache = "select * FROM ".$database_connect_prefix."groupe_tache where code_activite='$code_act' and annee='$annee' and projet='".$_SESSION["clp_projet"]."' ORDER BY code_tache ASC";
$tache  = mysql_query($query_tache , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_tache = mysql_fetch_assoc($tache);
$totalRows_tache  = mysql_num_rows($tache);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_suivi = "SELECT id_groupe_tache, date_suivi, observation FROM ".$database_connect_prefix."suivi_tache, ".$database_connect_prefix."groupe_tache where tache=id_groupe_tache and code_activite='$code_act' and ".$database_connect_prefix."suivi_tache.projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."groupe_tache.projet='".$_SESSION["clp_projet"]."' ORDER BY code_tache ASC";
$suivi  = mysql_query($query_suivi , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_suivi = mysql_fetch_assoc($suivi);
$totalRows_suivi  = mysql_num_rows($suivi);
$tableauSuivi=array();
if($totalRows_suivi>0){  do{
$tableauSuivi[$row_suivi["id_groupe_tache"]]=array($row_suivi["date_suivi"],$row_suivi["observation"]);
}while($row_suivi  = mysql_fetch_assoc($suivi));  }

$pcent = 100;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_total_proportion = "SELECT SUM(proportion) as total FROM ".$database_connect_prefix."groupe_tache WHERE code_activite='$code_act' and annee=$annee and projet='".$_SESSION["clp_projet"]."'";
$total_proportion = mysql_query($query_total_proportion, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_total_proportion = mysql_fetch_assoc($total_proportion);
$totalRows_total_proportion = mysql_num_rows($total_proportion);
$proportion=100;
if(isset($row_total_proportion["total"]) && $row_total_proportion["total"]>0){ $proportion=$pcent-$row_total_proportion["total"]; }
//echo $proportion;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_last_prop = "SELECT id_suivi FROM ".$database_connect_prefix."suivi_tache, ".$database_connect_prefix."groupe_tache where tache=id_groupe_tache and ".$database_connect_prefix."suivi_tache.projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."groupe_tache.projet='".$_SESSION["clp_projet"]."' ORDER BY id_suivi desc limit 1";
$last_prop  = mysql_query($query_last_prop , $pdar_connexion) or die(mysql_error());
$row_last_prop  = mysql_fetch_assoc($last_prop);
$totalRows_last_prop  = mysql_num_rows($last_prop);
if(isset($row_last_prop['id_suivi'])) {$last_prop_id=$row_last_prop['id_suivi'];} else {$last_prop_id=0;}

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
<script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>
<script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>
<script>
	$().ready(function() {
	  $(".modal-dialog", window.parent.document).width(780);
		// validate the comment form when it is submitted
		$(".row-border").validate();
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
<?php if(isset($_GET['mod'])) { ?>
        // reload parent frame
        $(".close", window.parent.document).click(function(){
          //window.parent.location.reload();
get_content('suivi_indicateur_ptba_reload.php','<?php echo "id=$id_act&annee=$annee&l=1"; ?>','label_<?php echo $id_act; ?>','','',1);
get_content('suivi_indicateur_ptba_reload.php','<?php echo "id=$id_act&annee=$annee&l=2"; ?>','statut_<?php echo $id_act; ?>','','',1);
        });
        $("button[data-dismiss='modal']", window.parent.document).click(function(){
          //window.parent.location.reload();
get_content('suivi_indicateur_ptba_reload.php','<?php echo "id=$id_act&annee=$annee&l=1"; ?>','label_<?php echo $id_act; ?>','','',1);
get_content('suivi_indicateur_ptba_reload.php','<?php echo "id=$id_act&annee=$annee&l=2"; ?>','statut_<?php echo $id_act; ?>','','',1);
        });
<?php } ?>
	});
</script>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}
.dataTables_length, .dataTables_info { float: left;} .dataTables_paginate, .dataTables_filter { float: right;}
.dataTables_length, .dataTables_paginate { display: none;}
.help-block{ display: none; }
</style>
</head>
<body>

<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i>  <strong>Suivi des Tâches </strong><span class="Style18"></span><?php echo $row_act['debut']." ".$annee; ?> </h4>
</div>
<div class="widget-content">
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >
            <thead>
                <tr>
                    <td>N&deg;</td>
                  <td>T&acirc;ches</td>
                  <td><div align="center" title="Proportion">Proportion %</div></td>
                  <td><div align="center" title="Proportion">Date r&eacute;alisation</div></td>
                  <td><div align="center" title="Proportion">Observation</div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center" width="80" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
 <?php $t=0;  if($totalRows_tache>0) {
   $p1="j"; $sp=0; $i=0;do { $i=$i+1; $t=$t+1; ?>
<form action="<?php echo $editFormAction; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form3" id="form3<?php echo $i; ?>" novalidate="novalidate">
      <tr>
        <td ><span class="Style12" title=""><?php echo $row_tache['code_tache']; ?></span></td>
      <?php $ts=0; $mois_cum = "";  ?>
        <td ><span class="Style12" title="<?php if(isset($row_tache['entite']) && $row_tache['entite']==0) echo substr($database_connect_prefix,0,-1);
elseif(isset($row_tache['entite']) && $row_tache['entite']==1) echo "Prestataire"; echo " /".$row_tache['responsable'];  ?>"><?php echo $row_tache['intitule_tache']; if(isset($row_tache['cout_tache']) && $row_tache['cout_tache']>0) echo " <br/>=> <u>".number_format($row_tache['cout_tache'], 0, ',', ' ')."</u>"; $sp=$sp+$row_tache['proportion']; ?></span></td>
      <?php $ts=0; $mois_cum = "";  ?>
      <td align="center" title="Proportion"><?php echo $row_tache['proportion'];  ?>&nbsp;%</td>
                <td align="center"><span class="l_float">
                  <input name="date_suivi" type="text" class="datepicker form-control required" <?php if(isset($tableauSuivi[$row_tache['id_groupe_tache']])) { ?>  disabled="disabled" <?php } ?> value="<?php echo (isset($tableauSuivi[$row_tache['id_groupe_tache']][0]) && $tableauSuivi[$row_tache['id_groupe_tache']][0]!="0000-00-00")?implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($tableauSuivi[$row_tache['id_groupe_tache']][0]))))):date("d/m/Y"); ?>" size="8" />
                </span></td>
                <td align="center"><span class="l_float">
                  <textarea name="observations" <?php if(isset($tableauSuivi[$row_tache['id_groupe_tache']])) { ?>  disabled="disabled" <?php } ?> cols="20" rows="1" class="form-control required"><?php if(isset($tableauSuivi[$row_tache['id_groupe_tache']][1])) echo $tableauSuivi[$row_tache['id_groupe_tache']][1]; else echo "RAS";?></textarea>
                </span></td>
                <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                <td align="center">
                  <input type="submit" name="Submit" <?php if(isset($tableauSuivi[$row_tache['id_groupe_tache']])) { ?>  disabled="disabled" <?php } ?> <?php if(isset($tableauSuivi[$row_tache['id_groupe_tache']][0]) && isset($tableauSuivi[$row_tache['id_groupe_tache']][1]) && $tableauSuivi[$row_tache['id_groupe_tache']][0]!="0000-00-00" && !empty($tableauSuivi[$row_tache['id_groupe_tache']][1])) echo "style=\"color:#990000\""; ?>   value="<?php if(isset($tableauSuivi[$row_tache['id_groupe_tache']][0]) && isset($tableauSuivi[$row_tache['id_groupe_tache']][1]) && $tableauSuivi[$row_tache['id_groupe_tache']][0]!="0000-00-00" && !empty($tableauSuivi[$row_tache['id_groupe_tache']][1])) echo "Annuler"; else echo "Valider"; ?>" class="btn btn-success" />

<?php if(isset($tableauSuivi[$row_tache['id_groupe_tache']])) echo do_link("",$lien."&id_sup=".$row_tache['id_groupe_tache'],"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette suivi ?');",0,"margin:0px 5px;",$nfile); ?>
				<div align="center">
<input name="id_tache" type="hidden" size="5" class="required" value="<?php echo $row_tache['id_groupe_tache']; ?>"/>
<input name="id_act" type="hidden" size="5" class="required" value="<?php echo $id_act; ?>"/>
<input name="code_act" type="hidden" size="5" class="required" value="<?php echo $code_act; ?>"/>
<input name="annee" type="hidden" size="5" class="required" value="<?php echo $annee; ?>"/>
<input type="hidden" name="<?php echo "MM_insert";  ?>" value="form3" />
<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
				</div>
                </td>
                <?php } ?>
      </tr>
</form>
    <?php }while ($row_tache = mysql_fetch_assoc($tache)); ?>
  <?php }else { ?> <tr><td align="center" colspan="<?php echo (isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1))?6:5; ?>"><h2>Aucun suivi !</h2></td></tr><?php } ?>
  </table>

</div></div>
</div>
<?php include_once 'modal_add.php'; ?>
</body>
</html>