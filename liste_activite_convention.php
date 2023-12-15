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

  $query_liste_resultat = "SELECT * FROM ".$database_connect_prefix."resultat_convention WHERE convention='$code_cv' ";
                try{
    $liste_resultat = $pdar_connexion->prepare($query_liste_resultat);
    $liste_resultat->execute();
    $row_liste_resultat = $liste_resultat ->fetchAll();
    $totalRows_liste_resultat = $liste_resultat->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


if ((isset($_GET["id_sup_res"]) && !empty($_GET["id_sup_res"]))) {
  $id = ($_GET["id_sup_res"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."activite_convention WHERE code_activite_convention=%s",
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
  $code = explode(":",$_POST['code_activite_ptba']);
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."activite_convention (convention, code_resultat, code_activite_ptba, code_activite_convention, intitule_activite_convention, mois,  date_enregistrement, id_personnel) VALUES (%s, %s, %s, %s, %s, %s, '$date', '$personnel')",
                         GetSQLValueString($code_cv, "text"),
                         GetSQLValueString($_POST['code_resultat'], "text"),
						  GetSQLValueString($code[0], "text"),
						  GetSQLValueString($_POST['code_activite_convention'], "text"),
                         GetSQLValueString($_POST['intitule_activite_convention'], "text"),
						  GetSQLValueString(implode('|',$_POST['mois'])."|", "text"));

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
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."activite_convention WHERE code_activite_convention=%s",
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
	 $code = explode(":",$_POST['code_activite_ptba']);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."activite_convention SET code_resultat=%s, code_activite_ptba=%s, code_activite_convention=%s, intitule_activite_convention=%s, mois=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date'  WHERE code_activite_convention=%s",
                         GetSQLValueString($_POST['code_resultat'], "text"),
						  GetSQLValueString($code[0], "text"),
						  GetSQLValueString($_POST['code_activite_convention'], "text"),
                         GetSQLValueString($_POST['intitule_activite_convention'], "text"),
						 GetSQLValueString(implode('|',$_POST['mois'])."|", "text"),    
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
  $query_liste_activite = "SELECT * FROM ".$database_connect_prefix."activite_convention WHERE code_activite_convention='$id' ";
                try{
    $liste_activite = $pdar_connexion->prepare($query_liste_activite1);
    $liste_activite->execute();
    $row_liste_activite = $liste_activite ->fetch();
    $totalRows_liste_activite = $liste_activite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}
else
{
  //Mission supervision
  $query_liste_activite1 = "SELECT * FROM ".$database_connect_prefix."activite_convention WHERE convention='$code_cv' ";
              try{
    $liste_activite1 = $pdar_connexion->prepare($query_liste_activite1);
    $liste_activite1->execute();
    $row_liste_activite1 = $liste_activite1 ->fetchAll();
    $totalRows_liste_activite1 = $liste_activite1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}

  $query_liste_mois= "SELECT * FROM mois order by num_mois";
                try{
    $liste_mois = $pdar_connexion->prepare($query_liste_mois);
    $liste_mois->execute();
    $row_liste_mois = $liste_mois ->fetchAll();
    $totalRows_liste_mois = $liste_mois->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $tableauMois=array();
if($totalRows_liste_mois>0){  foreach($row_liste_mois as $row_liste_mois){
$tableauMois[]=$row_liste_mois['num_mois']."<>".$row_liste_mois['mois']."<>".$row_liste_mois['abrege'];}}

  
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
<script type="text/javascript" src="bootstrap/js/bootstrap-typeahead.min.js"></script>

<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
        $(".modal-dialog", window.parent.document).width(700);
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
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 2px 8px;background: #EBEBEB;}

@media(min-width:558px){.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}} 
</style>
<?php if(!isset($_GET['add'])) { ?>
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> Activit&eacute;s de convention</h4>
   <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<a href="<?php echo $_SERVER['PHP_SELF']."?code_cv=$code_cv&add=1"; ?>" class="pull-right p11" title="Ajout une mission" ><i class="icon-plus"> Nouvelle activit&eacute; </i></a>
<?php } ?>
</div>
<div class="widget-content">
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >
            <thead>
                <tr>
                  <td><div align="left"><strong>R&eacute;sultat</strong></div></td>
                 <!-- <td><strong>Code analytique</strong></td>-->
                  <td><div align="left"><strong>Activit&eacute;</strong></div>                    <div align="center"></div></td>
                 <?php foreach($tableauMois as $vmois){
$amois = explode('<>',$vmois);
$imois = $amois[0]; ?>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><?php echo $amois[2]; ?> </th>
<?php } ?>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center" width="80" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
                <?php if($totalRows_liste_activite1>0) {$i=0; $a1=$r1="j"; foreach($row_liste_activite1 as $row_liste_activite1){ $id = $row_liste_activite1['code_activite_convention']; ?>
                <tr>
				
              <td><div align="center">
	          <?php if($r1!=$row_liste_activite1['code_resultat']) { echo $row_liste_activite1['code_resultat']; } $r1=$row_liste_activite1['code_resultat'];?>
			  </div></td>
                 <!-- <td><div align="center">
	          <?php //if($a1!=$row_liste_activite1['code_activite_ptba']) { echo $row_liste_activite1['code_activite_ptba']; } $a1=$row_liste_activite1['code_activite_ptba'];?>
			  </div></td>-->
                  <td><div align="left"><?php echo $row_liste_activite1['code_activite_convention'].": ".$row_liste_activite1['intitule_activite_convention']; ?></div>
                  <div align="left"></div>                    <div align="left"></div></td>
                 <?php foreach($tableauMois as $vmois){
$amois = explode('<>',$vmois);
$imois = $amois[0];
$a = explode("|", $row_liste_activite1['mois']);
?>
<td class=" "><a style="display: block; background-color:<?php if(in_array($imois, $a, TRUE)) echo "#CCCCCC"; ?> ">&nbsp;</a></td>
  <?php } ?>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<td align="center">
<a href="<?php echo $_SERVER['PHP_SELF']."?id=$id&code_cv=$code_cv&add=1"; ?>" title="Modifier mission" style="margin:0px 5px;"><img src="./images/edit.png" width="20" height="20" alt="Modifier" title="Modifier"></a><a href="<?php echo $_SERVER['PHP_SELF']."?code_cv=$code_cv&id_sup_res=".$id; ?>" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cette activit&eacute; ?');" style="margin:0px 5px;"><img src="./images/delete.png" width="20" height="20" alt="Supprimer" title="Supprimer"></a> </td>

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
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification d'activit&eacute;":"Nouvelle activit&eacute;"; ?></h4>
<a href="<?php echo $_SERVER['PHP_SELF']."?code_cv=$code_cv"; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
       <tr valign="top">
      <td> <div class="form-group">
          <label for="code_resultat" class="col-md-3 control-label">R&eacute;sultat  <span class="required">*</span></label>
          <div class="col-md-9">
           <select name="code_resultat" id="code_resultat" class="form-control required" >
              <?php if($totalRows_liste_resultat>0) { ?>
                                <option value="">-- Choisissez --</option>
                                <?php

foreach($row_liste_resultat as $row_liste_resultat){ $libeller = $row_liste_resultat['intitule_resultat']; $libeller = (strlen($libeller)>50)?substr($libeller,0,50)." ...":$libeller;

?>
                                <option value="<?php echo $row_liste_resultat['code_resultat'];?>"<?php if(isset($_GET['id'])) {if (!(strcmp($row_liste_activite['code_resultat'], $row_liste_resultat['code_resultat']))) {echo "SELECTED";} } ?>><?php echo $row_liste_resultat['code_resultat'].": ".$libeller;?></option>
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
          <label for="code_activite_convention" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="code_activite_convention" id="code_activite_convention" value="<?php echo isset($row_liste_activite['code_activite_convention'])?$row_liste_activite['code_activite_convention']:""; ?>" size="32" />
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="intitule_activite_convention" class="col-md-3 control-label">Activit&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="1" type="text" name="intitule_activite_convention" id="intitule_activite_convention"><?php echo isset($row_liste_activite['intitule_activite_convention'])?$row_liste_activite['intitule_activite_convention']:""; ?></textarea>
          </div>
        </div>      </td>
    </tr>
	<tr valign="top" bgcolor="#CCFFFF">
      <td colspan="2">
        <div class="form-group">
          <label for="seuil" class="col-md-3 control-label">Chronogramme<span class="required">*</span></label>
          <div class="col-md-9">
          <?php
          if(isset($row_liste_activite['mois']) && $row_liste_activite['mois']!="|") $a = explode("|", $row_liste_activite['mois']); ?>
          <table width="100%">
          <tr>
          <?php $i = 1; foreach($tableauMois as $vmois){?>
          <?php
          $amois = explode('<>',$vmois);
          $imois = $amois[0];
          ?>
          <td><label for="mois_<?php echo $i; ?>" class="control-label"><?php if(isset($amois[1])) echo $amois[1]; ?></label>
          <input name='mois[]' id='mois_<?php echo $i; ?>' type="checkbox"   <?php if(isset($row_liste_activite['mois'])) { if(in_array($imois, $a, TRUE)) echo "checked"; }?> size="5" value="<?php if(isset($imois)) echo $imois; ?>"/></td>
          <?php $i++; } ?>
          </tr>
          </table>
          </div>
        </div>
      </td>
    </tr>
   <tr valign="top">
      <td nowrap="nowrap">
        <div class="form-group" id="code_zone">
          <label for="code_activite_ptba" class="col-md-3 control-label">Code analytique <span class="required">*</span></label>
          <div class="col-md-9">
            <input name="code_activite_ptba" type="text" class="form-control typeahead required" id="code_activite_ptba" value="<?php if(isset($row_liste_activite['code_activite_ptba'])) echo $row_liste_activite['code_activite_ptba'];?>" size="25" />
          </div>
        </div>      </td>
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
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette activit&eacute; ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>
<?php } ?>