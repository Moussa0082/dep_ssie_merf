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

if(isset($_GET["code_cv"])){ $code_cv=$_GET['code_cv'];} 
$date=date("Y-m-d");

  $query_liste_activite = "SELECT * FROM ".$database_connect_prefix."activite_convention WHERE convention='$code_cv' ";
                    try{
    $liste_activite = $pdar_connexion->prepare($query_liste_activite);
    $liste_activite->execute();
    $row_liste_activite = $liste_activite ->fetchAll();
    $totalRows_liste_activite = $liste_activite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  
  //Indicateur PTBA
  $query_liste_indicateur_ptba = "SELECT * FROM ".$database_connect_prefix."indicateur_tache";
                      try{
    $liste_indicateur_ptba = $pdar_connexion->prepare($query_liste_indicateur_ptba);
    $liste_indicateur_ptba->execute();
    $row_liste_indicateur_ptba = $liste_indicateur_ptba ->fetchAll();
    $totalRows_liste_indicateur_ptba = $liste_indicateur_ptba->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


if ((isset($_GET["id_sup_res"]) && !empty($_GET["id_sup_res"]))) {
  $id = ($_GET["id_sup_res"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."indicateur_convention WHERE code_indicateur_convention=%s",
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
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."indicateur_convention (convention, code_activite, code_indicateur_convention, intitule_indicateur_convention, valeur_cible, indicateur_ptba,  date_enregistrement, id_personnel) VALUES (%s, %s, %s, %s, %s, %s, '$date', '$personnel')",
                         GetSQLValueString($code_cv, "text"),
                         GetSQLValueString($_POST['code_activite'], "text"),
						 GetSQLValueString($_POST['code_indicateur_convention'], "text"),
                         GetSQLValueString($_POST['intitule_indicateur_convention'], "text"),
						 GetSQLValueString($_POST['valeur_cible'], "double"),
						 GetSQLValueString($_POST['indicateur_ptba'], "text"));

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
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."indicateur_convention WHERE code_indicateur_convention=%s",
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
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."indicateur_convention SET code_activite=%s, code_indicateur_convention=%s, intitule_indicateur_convention=%s, valeur_cible=%s, indicateur_ptba=%s,  etat='Modifié', modifier_par='$personnel', modifier_le='$date'  WHERE code_indicateur_convention=%s",
                         GetSQLValueString($_POST['code_activite'], "text"),
						 GetSQLValueString($_POST['code_indicateur_convention'], "text"),
                         GetSQLValueString($_POST['intitule_indicateur_convention'], "text"),
						 GetSQLValueString($_POST['valeur_cible'], "double"), 
						 GetSQLValueString($_POST['indicateur_ptba'], "text"),
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
  $query_liste_indicateur = "SELECT * FROM ".$database_connect_prefix."indicateur_convention WHERE code_indicateur_convention='$id' ";
                    try{
    $liste_indicateur = $pdar_connexion->prepare($query_liste_indicateur);
    $liste_indicateur->execute();
    $row_liste_indicateur = $liste_indicateur ->fetch();
    $totalRows_liste_indicateur = $liste_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}
else
{
  //Mission supervision
  $query_liste_indicateur1 = "SELECT * FROM ".$database_connect_prefix."indicateur_convention WHERE convention='$code_cv' ";
                  try{
    $liste_indicateur1 = $pdar_connexion->prepare($query_liste_indicateur1);
    $liste_indicateur1->execute();
    $row_liste_indicateur1 = $liste_indicateur1 ->fetchAll();
    $totalRows_liste_indicateur1 = $liste_indicateur1->rowCount();
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
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>

<!--<script type="text/javascript" src="plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>
<script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>-->
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form3").validate();
<?php if(isset($_GET['add'])) { ?>
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
        //$("#mtable").dataTable();
<?php } ?>
	});
</script>
<style>
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 2px 8px;background: #EBEBEB;}

@media(min-width:558px){.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}} 
.Style2 {font-size: 12px}
</style>
<?php if(!isset($_GET['add'])) { ?>
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> Indicateurs de convention</h4>
   <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<a href="<?php echo $_SERVER['PHP_SELF']."?code_cv=$code_cv&add=1"; ?>" class="pull-right p11" title="Ajout une mission" ><i class="icon-plus"> Nouveau indicateur </i></a>
<?php } ?>
</div>
<div class="widget-content">
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >
            <thead>
                <tr>
                  <td><div align="left"><strong>Activit&eacute;</strong></div>                    </td>
                  <td><div align="left"><strong>Indicateur</strong></div>                    <div align="center"></div></td>
                  <td><div align="center"><strong>Valeur cible </strong></div></td>
                  <td><strong>R&eacute;sultat li&eacute; </strong></td>
                  <!--<td rowspan="2"><div align="left"><strong>Resum&eacute;</strong></div></td>-->
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center" width="80" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
                <?php if($totalRows_liste_indicateur1>0) {$i=0; $a1=$r1="j"; foreach($row_liste_indicateur1 as $row_liste_indicateur1){ $id = $row_liste_indicateur1['code_indicateur_convention']; ?>
                <tr>
				
              <td><div align="center">
	          <?php if($a1!=$row_liste_indicateur1['code_activite']) { echo $row_liste_indicateur1['code_activite']; } $a1=$row_liste_indicateur1['code_activite'];?>
			    </div></td>
                  <td><div align="left"><?php echo $row_liste_indicateur1['code_indicateur_convention'].": ".$row_liste_indicateur1['intitule_indicateur_convention']; ?></div>
                  <div align="left"></div>                    <div align="left"></div></td>
                  <td><div align="center"><?php echo $row_liste_indicateur1['valeur_cible']; ?></div></td>
                  <td><span class="Style2"><?php echo $row_liste_indicateur1['indicateur_ptba'].": ".$row_liste_indicateur1['indicateur_ptba']; ?></span></td>
                  <!--<td><div align="left"><?php //echo $row_liste_activite1['resume']; ?></div></td>-->
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<td align="center">
<a href="<?php echo $_SERVER['PHP_SELF']."?id=$id&code_cv=$code_cv&add=1"; ?>" title="Modifier mission" style="margin:0px 5px;"><img src="./images/edit.png" width="20" height="20" alt="Modifier" title="Modifier"></a><a href="<?php echo $_SERVER['PHP_SELF']."?code_cv=$code_cv&id_sup_res=".$id; ?>" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cet indicateur ?');" style="margin:0px 5px;"><img src="./images/delete.png" width="20" height="20" alt="Supprimer" title="Supprimer"></a> </td>

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
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification d'activité":"Nouvelle activité"; ?></h4>
<a href="<?php echo $_SERVER['PHP_SELF']."?code_cv=$code_cv"; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td> <div class="form-group">
          <label for="code_activite" class="col-md-3 control-label">Activit&eacute;  <span class="required">*</span></label>
          <div class="col-md-9">
           <select name="code_activite" id="code_activite" class="form-control required" >
              <?php if($totalRows_liste_activite>0) { ?>
                                <option value="">-- Choisissez --</option>
                                <?php

foreach($row_liste_activite as $row_liste_activite){ $libeller = $row_liste_activite['intitule_activite_convention']; $libeller = (strlen($libeller)>50)?substr($libeller,0,50)." ...":$libeller;

?>
                                <option value="<?php echo $row_liste_activite['code_activite_convention'];?>"<?php if(isset($_GET['id'])) {if (!(strcmp($row_liste_indicateur['code_activite'], $row_liste_activite['code_activite_convention']))) {echo "SELECTED";} } ?>><?php echo $row_liste_activite['code_activite_convention'].": ".$libeller;?></option>
                                <?php

} }
 ?>
            </select>
          </div>
        </div></td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group" id="code_zone">
          <label for="code_indicateur_convention" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="code_indicateur_convention" id="code_indicateur_convention" value="<?php echo isset($row_liste_indicateur['code_indicateur_convention'])?$row_liste_indicateur['code_indicateur_convention']:""; ?>" size="32" />
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="intitule_indicateur_convention" class="col-md-3 control-label">Indicateur <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="1" type="text" name="intitule_indicateur_convention" id="intitule_indicateur_convention"><?php echo isset($row_liste_indicateur['intitule_indicateur_convention'])?$row_liste_indicateur['intitule_indicateur_convention']:""; ?></textarea>
          </div>
        </div>      </td>
    </tr>
  <tr valign="top">
      <td>
        <div class="form-group">
          <label for="valeur_cible" class="col-md-3 control-label">Valeur cible <span class="required">*</span></label>
          <div class="col-md-9">
            <input name="valeur_cible" type="text" class="form-control required" id="valeur_cible" value="<?php echo isset($row_liste_indicateur['valeur_cible'])?$row_liste_indicateur['valeur_cible']:""; ?>" size="5" />
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td> <div class="form-group">
          <label for="code_activite" class="col-md-3 control-label">Indicateur PTBA  </label>
          <div class="col-md-9">
           <select name="indicateur_ptba" id="indicateur_ptba" class="form-control" >
              <?php if($totalRows_liste_indicateur_ptba>0) { ?>
                                <option value="">-- Choisissez --</option>
                                <?php

foreach($row_liste_indicateur_ptba as $row_liste_indicateur_ptba){ $libeller = $row_liste_indicateur_ptba['intitule_indicateur_tache']; $libeller = (strlen($libeller)>50)?substr($libeller,0,50)." ...":$libeller;

?>
                                <option value="<?php echo $row_liste_indicateur_ptba['code_indicateur_ptba'];?>"<?php if(isset($_GET['id'])) {if (!(strcmp($row_liste_indicateur['indicateur_ptba'], $row_liste_indicateur_ptba['code_indicateur_ptba']))) {echo "SELECTED";} } ?>><?php echo  $row_liste_indicateur_ptba['code_indicateur_ptba'].": ".$libeller;?></option>
                                <?php

} }
 ?>
            </select>
          </div>
        </div></td>
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
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette activité ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>
<?php } ?>