<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
$path = './';
include_once $path.'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $path.$config->sys_folder . "/database/db_connexion.php";
header('Content-Type: text/html; charset=UTF-8');

//fonction calcul nb jour


function NbJours($debut, $fin) {
  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);
  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) -
          mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
  return(($diff / 86400)+1);
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$page = $_SERVER['PHP_SELF'];
$idms=$_GET['idms'];$rec=$_GET['rec'];$annee=$_GET['annee'];
//insertion des plans

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  if($_POST['proportion']>$_POST['tmax']) $_POST['proportion']=$_POST['tmax'];

  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."mission_plan (code_rec, ordre, phase, proportion, date_prevue, responsable, observation, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s,'$personnel', '$date')",
            //  GetSQLValueString($idms, "text"),
              GetSQLValueString($rec, "text"),
              GetSQLValueString($_POST['ordre'], "text"),
              GetSQLValueString($_POST['phase'], "text"),
              GetSQLValueString($_POST['proportion'], "text"),
              GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_prevue']))), "date"),
              GetSQLValueString($_POST['responsable'], "text"),
              GetSQLValueString($_POST['observation'], "text"));
		  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?rec=$rec&idms=$idms&annee=$annee&insert=ok"; else $insertGoTo .= "?rec=$rec&idms=$idms&annee=$annee&insert=no";

  header(sprintf("Location: %s", $insertGoTo)); exit();
}

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
      $id = $_POST["MM_delete"];
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."mission_plan WHERE id_plan=%s",
                           GetSQLValueString($id, "int"));

	  	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?rec=$rec&idms=$idms&annee=$annee&del=ok"; else $insertGoTo .= "?rec=$rec&idms=$idms&annee=$annee&del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];  $id = ($_POST["MM_update"]);
  if($_POST['proportion']>$_POST['tmax']) $_POST['proportion']=$_POST['tmax'];
  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."mission_plan SET  phase=%s, proportion=%s, ordre=%s, date_prevue=%s, responsable=%s, observation=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_plan='$id'",
              GetSQLValueString($_POST['phase'], "text"),
              GetSQLValueString($_POST['proportion'], "text"),
              GetSQLValueString($_POST['ordre'], "text"),
              GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_prevue']))), "date"),
              GetSQLValueString($_POST['responsable'], "text"),
              GetSQLValueString($_POST['observation'], "text"));
  	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?update=ok&rec=$rec&idms=$idms&annee=$annee"; else $insertGoTo .= "?update=ok&rec=$rec&idms=$idms&annee=$annee";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}
}

if(isset($_GET["id_sup_pd"])) { $ids=$_GET["id_sup_pd"];
$query_sup_loc= "DELETE FROM ".$database_connect_prefix."mission_plan WHERE id_plan='$ids'";
	  try{
    $Result1 = $pdar_connexion->prepare($query_sup_loc);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?rec=$rec&idms=$idms&annee=$annee&del=ok"; else $insertGoTo .= "?rec=$rec&idms=$idms&annee=$annee&del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if(isset($_GET["id"])) { $id=$_GET["id"]; 
$query_edit_plan = "SELECT * FROM ".$database_connect_prefix."mission_plan WHERE id_plan='$id'";
       try{
    $edit_plan = $pdar_connexion->prepare($query_edit_plan);
    $edit_plan->execute();
    $row_edit_plan = $edit_plan ->fetch();
    $totalRows_edit_plan = $edit_plan->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}
  $query_edit_recm = "SELECT * FROM ".$database_connect_prefix."recommandation_mission, ".$database_connect_prefix."mission_supervision WHERE id_mission=mission and mission='$idms' and id_recommandation='$rec'";
       try{
    $edit_recm = $pdar_connexion->prepare($query_edit_recm);
    $edit_recm->execute();
    $row_edit_recm = $edit_recm ->fetch();
    $totalRows_edit_recm = $edit_recm->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_plan_dec = "SELECT * FROM ".$database_connect_prefix."mission_plan WHERE code_rec='$rec' order by ordre";
       try{
    $plan_dec = $pdar_connexion->prepare($query_plan_dec);
    $plan_dec->execute();
    $row_plan_dec = $plan_dec ->fetchAll();
    $totalRows_plan_dec = $plan_dec->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tolp = 0;
if($totalRows_plan_dec>0){  foreach($row_plan_dec as $row_plan_dec1){  
  $tolp+=$row_plan_dec1['proportion'];;
}}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->
  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>
<script type="text/javascript" src="plugins/noty/layouts/top.js"></script>
<script type="text/javascript" src="plugins/noty/themes/default.js"></script>
<script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>
<script type="text/javascript">
$().ready(function() {
<?php if(!isset($_GET["show"])){ ?>
        $(".modal-dialog", window.parent.document).width(800);
<?php }else{ ?>
        $(".modal-dialog", window.parent.document).width(600);
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
<?php } ?>
});
</script>
<style>
@media(min-width:558px){.col-md-12 {width: 100%;}.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}
</style>
</head>
<body>
<?php if(!isset($_GET["show"])){ ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i><strong><strong><span class="Style14">
<?php if (isset ($row_edit_recm['debut'])) echo $row_edit_recm['type']. " du " . implode('-', array_reverse(explode('-', $row_edit_recm['debut']))) . " au " . implode('-', array_reverse(explode('-', $row_edit_recm['fin'])));?>
</span></strong></strong></h4>
  <div class="toolbar no-padding"><?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1) && isset($tolp) && $tolp<100) {?>
<a href="<?php echo $_SERVER['PHP_SELF']."?rec=$rec&annee=".$annee."&idms=$idms&show=1"; ?>" title="Ajout de recommandation" class="pull-right p11"><i class="icon-plus"> Ajouter </i></a><?php } ?>
</div></div>

<div class="widget-content">
<div>
<strong><u>Recommandation</u>:<span class="Style14">
<?php if (isset ($row_edit_recm['recommandation'])) echo $row_edit_recm['recommandation'];?>
</span><br />
<u>Date buttoir</u>:
<?php if (isset ($row_edit_recm['type']) && $row_edit_recm['type'] == "Continu") echo "Continu";else echo date("d/m/y", strtotime($row_edit_recm['date_buttoir']));?>
<br /><u>Responsable</u>:
<?php if (isset ($row_edit_recm['responsable_interne'])) echo $row_edit_recm['responsable_interne'];?>
</strong></div>

  <table style="border-collapse: collapse;" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
    <thead>
      <tr role="row">
        <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >N&deg;</th>
        <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Actions</th>
        <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div align="center">Proportion</div></th>
        <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Date pr&eacute;vue </th>
        <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Acteur</th>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
        <th align="center" class="" role="" tabindex="0" aria-controls="" aria-label="" width="90"><center>Actions</center></th>
<?php } ?>
      </tr>
    </thead>
    <tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
      <?php $t = 0;if ($totalRows_plan_dec > 0) {$p1 = "j";$t = 0;$i = 0; foreach($row_plan_dec as $row_plan_dec){  ?>
      <tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
        <td ><span class="Style5"><?php echo $row_plan_dec['ordre']; ?></span></td>
        <td><span class="Style5"><?php echo $row_plan_dec['phase']; ?></span></td>
        <td><div align="center"><span class="Style5"><?php echo number_format($row_plan_dec['proportion'], 0, ',', ' '); $tolp=$tolp+$row_plan_dec['proportion']; ?></span></div></td>
        <td><span class="Style5"><?php echo date_reg($row_plan_dec['date_prevue'],'/'); ?></span></td>
        <td ><span class="Style5"><?php echo $row_plan_dec['responsable']; ?></span></td>
        <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
        <td align="center">
<?php
echo do_link("",$_SERVER['PHP_SELF']."?rec=$rec&idms=$idms&annee=$annee&id=".$row_plan_dec['id_plan']."&show=1","Modifier suivi de reconmmandation","","edit","./","","",0,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?rec=$rec&idms=$idms&annee=$annee&id_sup_pd=".$row_plan_dec['id_plan'],"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette suivi de reconmmandation ?');",0,"margin:0px 5px;",$nfile);
?>
        </td>
        <?php }?>
      </tr>
<?php } ?>

      <?php }else echo "<tr><td colspan='".(((isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1))?6:5)."' align='center'>Aucune donn&eacute;e!</td></tr>"?>
    </tbody>
  </table>

  </div>
</div>

<?php } else{ ?>

<?php if(isset($tolp) && $tolp<100 || (isset($_GET["id"]))){
  if(isset($_GET["id"])) $tolp=$tolp-$row_edit_plan['proportion']; ?>
<script>
function check_proportion(valeur){
var taux = <?php echo $tolp; ?>;
var sommes=parseInt(taux)+parseInt(valeur);
if((sommes)>100){ form3.montant.value=100-taux; }
}
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php if(isset($_GET['id'])) echo "Modifier t&acirc;che de reconmmandation"; else echo "Nouvelle t&acirc;che de reconmmandation" ; ?></h4>
<a href="<?php echo $_SERVER['PHP_SELF']."?rec=$rec&annee=".$annee."&idms=$idms"; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="<?php echo $editFormAction; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">

<table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td colspan="2"> <div class="form-group">
          <label for="ordre" class="col-md-3 control-label">N&deg; d'ordre <span class="required">*</span></label>
          <div class="col-md-3">
            <input type="text" class="form-control required" name="ordre" id="ordre" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_plan['ordre']; else echo "00"; ?>" >
          </div>
        </div> </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="phase" class="col-md-3 control-label">T&acirc;che <span class="required">*</span></label>
          <div class="col-md-9">
  <textarea class="form-control required" id="phase" name="phase" cols="25" rows="2"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_plan['phase'];?></textarea>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="responsable" class="col-md-3 control-label">Responsable <span class="required">*</span></label>
          <div class="col-md-9">
  <textarea class="form-control required" id="responsable" name="responsable" cols="25" rows="1"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_plan['responsable'];?></textarea>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="proportion" class="col-md-6 control-label">Proportion (%) <span class="required">*</span></label>
          <div class="col-md-3">
            <input type="text" class="form-control required" name="proportion" id="proportion" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_plan['proportion']; else echo 100-$tolp; ?>" >
          </div>
        </div>      </td>
      <td align="left">
        <div class="form-group">
          <div class="col-md-12">
            <div align="left"><i style="color: red;">Reste: <?php echo 100-$tolp; ?>%</i> </div>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="date_prevue" class="col-md-3 control-label">Date prevue <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control datepicker required" type="text" name="date_prevue" id="date_prevue" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',$row_edit_plan['date_prevue']))); else echo date("d/m/Y"); ?>" size="32" />
          </div>
        </div>      </td>
      </tr>
</table>

<div class="form-actions">
  <input type="hidden" name="tmax" value="<?php if(isset($tolp)) echo 100-$tolp;   ?>" />
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
  <a title="Annuler" href="<?php echo $_SERVER['PHP_SELF']."?rec=$rec&annee=".$annee."&idms=$idms"; ?>" class="btn btn-default pull-right">Annuler</a>
<?php if(isset($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette suivie ?','<?php echo $_GET["id"]; ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
  <input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
</div>
</form>

</div> </div>
		<?php } ?>
<?php } ?>
</body>
</html>