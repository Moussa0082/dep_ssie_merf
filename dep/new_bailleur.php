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
//header('Content-Type: text/html; charset=UTF-8');

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
//$id_fiche=$_GET['id_fiche']; //$rec=$_GET['rec'];$annee=$_GET['annee'];
//insertion des plans

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  //if($_POST['proportion']>$_POST['tmax']) $_POST['proportion']=$_POST['tmax'];

$insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."partenaire (code, sigle, definition, adresse_mail, id_personnel) VALUES (%s, %s, %s, %s, '$personnel')",
  			   GetSQLValueString($_POST['code'], "text"),
  			   GetSQLValueString($_POST['sigle'], "text"),
  			   GetSQLValueString($_POST['definition'], "text"),
               GetSQLValueString($_POST['adresse_mail'], "text"));

    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
		
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";

  header(sprintf("Location: %s", $insertGoTo)); exit();
}

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
      $id = $_POST["MM_delete"];
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."partenaire WHERE id_partenaire=%s",
                           GetSQLValueString($id, "int"));

      try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
      }catch(Exception $e){ die(mysql_error_show_message($e)); }

      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];  $id = ($_POST["MM_update"]);
             
  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."partenaire SET code=%s, sigle=%s, definition=%s, adresse_mail=%s WHERE id_partenaire=%s",
  			   GetSQLValueString($_POST['code'], "text"),
  			   GetSQLValueString($_POST['sigle'], "text"),
  			   GetSQLValueString($_POST['definition'], "text"),
               GetSQLValueString($_POST['adresse_mail'], "text"),
               GetSQLValueString($id, "int"));
			  
  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=ok";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}
}

if(isset($_GET["id_sup_pd"])) { $ids=$_GET["id_sup_pd"];
$query_sup_loc= "DELETE FROM ".$database_connect_prefix."partenaire WHERE id_partenaire='$ids'";
try{
    $Result1 = $pdar_connexion->prepare($query_sup_loc);
    $Result1->execute();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if(isset($_GET["id"])) { $id=$_GET["id"];
$query_edit_exploitant = "SELECT * FROM ".$database_connect_prefix."partenaire WHERE id_partenaire='$id'";
try{
    $edit_exploitant = $pdar_connexion->prepare($query_edit_exploitant);
    $edit_exploitant->execute();
    $row_edit_exploitant = $edit_exploitant ->fetch();
    $totalRows_edit_exploitant = $edit_exploitant->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}

$query_plan_dec = "SELECT * FROM ".$database_connect_prefix."partenaire  order by code";
try{
    $plan_dec = $pdar_connexion->prepare($query_plan_dec);
    $plan_dec->execute();
    $row_plan_dec = $plan_dec ->fetchAll();
    $totalRows_plan_dec = $plan_dec->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
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
  <script type="text/javascript" src="plugins/select2/select2.min.js"></script>
<script>

	$().ready(function() {

		// validate the comment form when it is submitted

		$("#form0").validate();

        $("#tabs").tabs();

        $(".modal-dialog", window.parent.document).width(780);

        $(".select2-select-00").select2({allowClear:true});

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
Liste des partenaires financiers
</span></strong></strong></h4>
  <div class="toolbar no-padding"><?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) {?>
<a href="<?php echo $_SERVER['PHP_SELF']."?show=1"; ?>" title="Ajout site pare-feu " class="pull-right p11"><i class="icon-plus"> Ajouter </i></a><?php } ?>
</div></div>

<div class="widget-content">
  <table style="border-collapse: collapse;" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
    <thead>
      <tr role="row">
        <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Code </th>
        <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Sigle</th>
        <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div align="center">D&eacute;nomination</div></th>
        <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Adresse mail </th>
        <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
        <th width="90" align="center" class="" role="" tabindex="0" aria-controls="" aria-label=""><center>Actions</center></th>
<?php } ?>
      </tr>
    </thead>
    <tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
      <?php $t = 0;if ($totalRows_plan_dec > 0) {$p1 = "j";$t = 0;$i = 0; foreach($row_plan_dec as $row_plan_dec){?>
      <tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
        <td ><span class="Style5"><strong>
          <?php  echo  $row_plan_dec["code"];?>
        </strong></span></td>
        <td ><strong>
          <?php  echo  $row_plan_dec["sigle"];?>
        </strong></td>
        <td><span class="Style5"><?php echo $row_plan_dec['definition']; ?></span></td>
        <td><span class="Style5"><?php echo $row_plan_dec['adresse_mail'];  ?></span></td>
        <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
        <td align="center">
<?php
echo do_link("",$_SERVER['PHP_SELF']."?id=".$row_plan_dec['id_partenaire']."&show=1","Modifier site pare feu","","edit","./","","",0,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup_pd=".$row_plan_dec['id_partenaire'],"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce bailleur ?');",0,"margin:0px 5px;",$nfile);
?>        </td>
        <?php }?>
      </tr>
<?php } }else echo "<tr><td colspan='".(((isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1))?6:5)."' align='center'>Aucune donn&eacute;e!</td></tr>"?>
    </tbody>
  </table>

  </div>
</div>
<?php } else{ ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php if(isset($_GET['id'])) echo "Modifier Bailleur"; else echo "Nouveau Bailleur" ; ?></h4>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="<?php echo $editFormAction; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">
  <table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:10px;">
   
      <tr>
      <td valign="top"><div class="form-group">
          <label for="code" class="col-md-10 control-label">Code <span class="required">*</span></label>
          <div class="col-md-11">
            <input class="form-control required" type="text" name="code" id="code" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_exploitant['code']; ?>" size="32" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_edit_exploitant['code']."'"; ?>) check_code('verif_code.php?t=partenaire&','w=code='+this.value+' ','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div></td>
       <td valign="top"> <div class="form-group">
          <label for="sigle" class="col-md-10 control-label">Sigle <span class="required">*</span></label>
          <div class="col-md-11">
            <input class="form-control required" type="text" name="sigle" id="sigle" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_exploitant['sigle']; ?>" size="32" />
          </div>
        </div></td>
    </tr>
	
    <tr>
      <td valign="top"> <div class="form-group">
          <label for="definition" class="col-md-10 control-label">D&eacute;nomination <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control required" name="definition" id="definition" rows="2" cols="25"><?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?$row_edit_exploitant['definition']:''; ?></textarea>
          </div>
        </div></td>
       <td valign="top"> <div class="form-group">
          <label for="adresse_mail" class="col-md-10 control-label">Adresse mail <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control email required" type="text" name="adresse_mail" id="adresse_mail" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_exploitant['adresse_mail']; ?>" size="32" />
          </div>
        </div></td>
    </tr>
  </table>
  <div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
  <a title="Annuler" href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-default pull-right">Annuler</a>
<?php if(isset($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cet exploitant ?','<?php echo $_GET["id"]; ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
  <input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
</div>
</form>

</div> </div>
		<?php } ?>
<?php // } ?>
</body>
</html>