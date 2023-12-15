<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & D&eacute;veloppement: BAMASOFT */
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

if(isset($_GET["code_cv"])){ $code_cv=$_GET['code_cv'];} 
$date=date("Y-m-d");

if ((isset($_GET["id_sup_res"]) && !empty($_GET["id_sup_res"]))) {
  $id = ($_GET["id_sup_res"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."resultat_convention WHERE code_resultat=%s",
                       GetSQLValueString($id, "text"));

 try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?code_cv=$code_cv&del=ok"; else $insertGoTo .= "?code_cv=$code_cv&del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{ //Fonction
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."resultat_convention (convention, code_resultat, intitule_resultat,  date_enregistrement, id_personnel) VALUES (%s, %s, %s, '$date', '$personnel')",
                         GetSQLValueString($code_cv, "text"),
                         GetSQLValueString($_POST['code_resultat'], "text"),
                         GetSQLValueString($_POST['intitule_resultat'], "text"));

 try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
      $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?code_cv=$code_cv&insert=ok"; else $insertGoTo .= "?code_cv=$code_cv&insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."resultat_convention WHERE code_resultat=%s",
                         GetSQLValueString($id, "text"));

 try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
      $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?code_cv=$code_cv&del=ok"; else $insertGoTo .= "?code_cv=$code_cv&del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
    $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."resultat_convention SET code_resultat=%s, intitule_resultat=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date'  WHERE code_resultat=%s",
                         GetSQLValueString($_POST['code_resultat'], "text"),
                         GetSQLValueString($_POST['intitule_resultat'], "text"),
                         GetSQLValueString($id, "text"));

 try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?code_cv=$code_cv&update=ok"; else $insertGoTo .= "?code_cv=$code_cv&update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]);
  $query_liste_resultat = "SELECT * FROM ".$database_connect_prefix."resultat_convention WHERE code_resultat='$id' ";
            try{
    $liste_resultat = $pdar_connexion->prepare($query_liste_resultat);
    $liste_resultat->execute();
    $row_liste_resultat = $liste_resultat ->fetch();
    $totalRows_liste_resultat = $liste_resultat->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}
else
{
  //Mission supervision
  $query_liste_resultat = "SELECT * FROM ".$database_connect_prefix."resultat_convention ";
          try{
    $liste_resultat1 = $pdar_connexion->prepare($query_liste_resultat);
    $liste_resultat1->execute();
    $row_liste_resultat1 = $liste_resultat1 ->fetchAll();
    $totalRows_liste_resultat1 = $liste_resultat1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}

?>
<meta name="viewport" content="width=400, initial-scale=1.0">
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

<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form3").validate();
	});
</script>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 8px;background: #EBEBEB;}

@media(min-width:558px){.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}} 
</style>
<?php if(!isset($_GET['add'])) { ?>
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> R&eacute;sultats de convention</h4>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<a href="<?php echo $_SERVER['PHP_SELF']."?code_cv=$code_cv&add=1"; ?>" class="pull-right p11" title="Ajout une mission" ><i class="icon-plus"> Nouveau r&eacute;sultat </i></a>
<?php } ?>
</div>
<div class="widget-content">
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >
            <thead>
                <tr>
                  <td><div align="left"><strong>Code</strong></div></td>
                  <!--<td rowspan="2"><div align="left"><strong>Resum&eacute;</strong></div></td>-->
                  <td><div align="left"><strong>R&eacute;sultat</strong></div>                    <div align="center"></div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center" width="80" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
                <?php if($totalRows_liste_resultat1>0) {$i=0;foreach($row_liste_resultat1 as $row_liste_resultat1){ $id = $row_liste_resultat1['code_resultat']; ?>
                <tr>
                  <td><div align="left"><?php echo $row_liste_resultat1['code_resultat']; ?></div></td>
                  <!--<td><div align="left"><?php //echo $row_liste_resultat1['resume']; ?></div></td>-->
                  <td><div align="left"><?php echo $row_liste_resultat1['intitule_resultat']; ?></div>
                  <div align="left"></div>                    <div align="left"></div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<td align="center">
<a href="<?php echo $_SERVER['PHP_SELF']."?id=$id&code_cv=$code_cv&add=1"; ?>" title="Modifier mission" style="margin:0px 5px;"><img src="./images/edit.png" width="20" height="20" alt="Modifier" title="Modifier"></a><a href="<?php echo $_SERVER['PHP_SELF']."?code_cv=$code_cv&id_sup_res=".$id; ?>" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer ce r&eacute;sultat ?');" style="margin:0px 5px;"><img src="./images/delete.png" width="20" height="20" alt="Supprimer" title="Supprimer"></a> </td>

                   <?php } ?>
				  </tr>
                <?php }  ?>
                <?php } ?>
              </table>

</div></div>
</div>
<?php } else { ?>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification de r&eacute;sultat":"Nouveau r&eacute;sultat"; ?></h4>
<a href="<?php echo $_SERVER['PHP_SELF']."?code_cv=$code_cv"; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group" id="code_zone">
          <label for="code_resultat" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="code_resultat" id="code_resultat" value="<?php echo isset($row_liste_resultat['code_resultat'])?$row_liste_resultat['code_resultat']:""; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="intitule_resultat" class="col-md-3 control-label">R&eacute;sultat <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="3" type="text" name="intitule_resultat" id="intitule_resultat"><?php echo isset($row_liste_resultat['intitule_resultat'])?$row_liste_resultat['intitule_resultat']:""; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
   
   

</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo ($_GET["id"]); ?>" />
<?php } ?>

  <input type="hidden" name="code_cv" value="<?php echo $code_cv; ?>" />

  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
<a href="<?php echo $_SERVER['PHP_SELF']."?code_cv=$code_cv"; ?>" class="btn pull-right" title="Annuler" >Annuler</a>
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2)) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce r&eacute;sultat ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>
<?php } ?>