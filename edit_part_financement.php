<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"]) || !isset($_GET["bailleur"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
header('Content-Type: text/html; charset=UTF-8');

$bailleur = intval($_GET["bailleur"]);

$editFormAction = $_SERVER['PHP_SELF'];
$currentPage = $_SERVER['PHP_SELF']."?bailleur=$bailleur";
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if(isset($_GET["id_sup_tp"])) { $id=$_GET["id_sup_tp"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sup_part = "DELETE FROM ".$database_connect_prefix."type_part WHERE id_part='$id'";
$Result1 = mysql_query($query_sup_part, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$currentPage;
  if ($Result1) $insertGoTo .= "&del=ok"; else $insertGoTo .= "&del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."type_part (code_type, bailleur, intitule, montant, date_accord, observation, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, '$personnel', '$date')",

                         GetSQLValueString($_POST['code_type'], "text"),
                         GetSQLValueString($_POST['bailleur'], "int"),
  					   GetSQLValueString($_POST['intitule'], "text"),
  					   GetSQLValueString($_POST['montant'], "double"),
                         GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_accord']))), "date"),
  					   GetSQLValueString($_POST['observation'], "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = $currentPage;
    if ($Result1) $insertGoTo .= "&insert=ok"; else $insertGoTo .= "&insert=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id'];
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."type_part SET code_type=%s, bailleur=%s, intitule=%s, montant=%s, date_accord=%s, observation=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_part='$c'",

                         GetSQLValueString($_POST['code_type'], "text"),
                         GetSQLValueString($_POST['bailleur'], "int"),
  					   GetSQLValueString($_POST['intitule'], "text"),
  					   GetSQLValueString($_POST['montant'], "double"),
                         GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_accord']))), "date"),
  					   GetSQLValueString($_POST['observation'], "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = $currentPage;
    if ($Result1) $insertGoTo .= "&insert=ok"; else $insertGoTo .= "&insert=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
      $id = intval($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."type_part WHERE id_part=%s",
                           GetSQLValueString($id, "int"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
      $insertGoTo = $currentPage;
      if ($Result1) $insertGoTo .= "&del=ok"; else $insertGoTo .= "&del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }
}

if(isset($_GET["id"]) && intval($_GET["id"])>0)
{
  $id=intval($_GET["id"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_bailleur = "SELECT * FROM ".$database_connect_prefix."type_part WHERE id_part=$id ";
  $liste_bailleur  = mysql_query($query_liste_bailleur , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_bailleur  = mysql_fetch_assoc($liste_bailleur);
  $totalRows_liste_bailleur  = mysql_num_rows($liste_bailleur);
}

?>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>

<script>
$("#ui-datepicker-div").remove();
	$(document).ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
	});
</script>
<?php if(!isset($_GET['add'])) { ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Convention</h4>
<?php echo do_link("","edit_part_financement.php?bailleur=$bailleur&add=1","Ajouter un financement","Ajouter un financement","","./","pull-right p11","",0,"",$nfile); ?>
</div>
<div class="widget-content">
<table id="" align="center" cellspacing="0" width="100%" border="0" class="table table-striped table-bordered table-hover table-responsive dataTable">
<?php
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_bailleur= "SELECT * FROM type_part WHERE bailleur=$bailleur ";
$liste_bailleur  = mysql_query($query_liste_bailleur , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_bailleur  = mysql_fetch_assoc($liste_bailleur);
$totalRows_liste_bailleur  = mysql_num_rows($liste_bailleur);
if($totalRows_liste_bailleur>0){ ?>
<thead>
    <tr>
      <td><div align="left"><strong>Code</strong></div></td>
      <td><div align="left"><strong>Description</strong></div></td>
      <td><div align="left"><strong>Montant</strong></div></td>
      <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?> <td align="center" width="80"><strong>Actions</strong></td> <?php } ?>
    </tr>
</thead>
<?php do{ if($row_liste_bailleur['intitule']!=""){ $id_b = $row_liste_bailleur['id_part']; ?>
<tr>
<td align="center"><div align="left"><?php  echo  $row_liste_bailleur['code_type']; ?></div></td>
<td align="center"><div align="left"><?php  echo  $row_liste_bailleur['intitule']; ?></div></td>
<td align="right"><div align="left"><?php  echo  number_format($row_liste_bailleur['montant'], 0, ',', ' '); ?> Ouguiya</div></td>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<td align="center">
<?php
echo do_link("","edit_part_financement.php?bailleur=$bailleur&id=$id_b&add=1","Modifier financement","","edit","./","","",0,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?bailleur=$bailleur&id_sup_tp=".$id_b,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce financement ?');",0,"margin:0px 5px;",$nfile);
?>
</td>
<?php } ?>
</tr>
                          <?php    }

                           } while($row_liste_bailleur  = mysql_fetch_assoc($liste_bailleur));

                         }else echo '<tr bgcolor="#ECF000" colspan="3"><td align="center">Aucune contribution!</td></tr>'; ?>
</table>
  <div class="pied" align="center"><?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?><br />
<?php
echo do_link("","edit_part_financement.php?bailleur=$bailleur&add=1","Ajouter un financement","Ajouter un financement","","./","","",0,"",$nfile);
?>
<div class="clear">&nbsp;</div>
</div>
</div> </div>
<?php } } else{ ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)? "Modifier":"Nouveau"; ?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

    <tr valign="top">
      <td valign="middle" colspan="2">
        <div class="form-group">
          <label for="code_type" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="code_type" id="code_type" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_bailleur['code_type']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td valign="middle" colspan="2">
        <div class="form-group">
          <label for="intitule" class="col-md-3 control-label">Intitul&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="intitule" id="intitule" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_bailleur['intitule']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="montant" class="col-md-3 control-label">Montant (USD) <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="montant" id="montant" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_bailleur['montant']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="date_accord" class="col-md-3 control-label">Date de l'accord <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control inlinepicker required" type="text" name="date_accord" id="date_accord" value="<?php if(isset($_GET["id"])) echo implode('/',array_reverse(explode('-',$row_liste_bailleur['date_accord'])));  else echo date("d/m/Y"); ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
<!--    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="observation" class="col-md-3 control-label">Observation </label>
          <div class="col-md-9">
            <input class="form-control" type="text" name="observation" id="observation" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0)echo $row_liste_bailleur['observation']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>-->
</table>
<div class="form-actions">
  <input type="hidden" name="bailleur" value="<?php if(isset($_GET["bailleur"])) echo $_GET["bailleur"]; else echo 1; ?>" />
  <?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
  <?php } ?>
<?php
echo do_link("","edit_part_financement.php?bailleur=$bailleur","Annuler","Annuler","","./","btn pull-right","",0,"",$nfile);
?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">

<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette contribution de bailleur ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />

<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>