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
////header('Content-Type: text/html; charset=UTF-8');

$dir = './attachment/supervision/';
//if(!is_dir($dir)) mkdir($dir);
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

if ((isset($_GET["id_sup_mission"]) && !empty($_GET["id_sup_mission"]))) {
  $id = ($_GET["id_sup_mission"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."mission_supervision WHERE code_ms=%s",
                       GetSQLValueString($id, "text"));

  	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}
                                        
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{ //Fonction
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."mission_supervision (code_ms, type, objet, resume, debut, fin, observation, projet, date_enregistrement, id_personnel) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, '$date', '$personnel')",
                          GetSQLValueString($_POST['code_ms'], "text"),
						 GetSQLValueString($_POST['type'], "text"),
                         GetSQLValueString($_POST['objet'], "text"),
                         GetSQLValueString($_POST['resume'], "text"),
                         GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['debut']))), "date"),
                         GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['fin']))), "date"),
                         GetSQLValueString($_POST['observation'], "text"),
                        GetSQLValueString($_SESSION["clp_projet"], "text"));

		  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."mission_supervision WHERE code_ms=%s",
                         GetSQLValueString($id, "text"));

		  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
    $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."mission_supervision SET code_ms=%s, type=%s, objet=%s, resume=%s, debut=%s, fin=%s, observation=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date'  WHERE code_ms=%s",
                          GetSQLValueString($_POST['code_ms'], "text"),
						 GetSQLValueString($_POST['type'], "text"),
                         GetSQLValueString($_POST['objet'], "text"),
                         GetSQLValueString($_POST['resume'], "text"),
                         GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['debut']))), "date"),
                         GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['fin']))), "date"),
                         GetSQLValueString($_POST['observation'], "text"),
                         GetSQLValueString($id, "text"));

	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form4"))
{ //Upload file
  if ((isset($_FILES['fichier']['name'])) && count($_FILES['fichier']['name'])>0 && isset($_POST["id"])) {
    $ext_autorisees=array('docx','doc','pdf', 'xls', 'xlsx', 'jpeg', 'jpg', 'zip', 'rar'); //Extensions autorisées
    $url_site = $dir;//'./attachment/dano/';
    $Result1 = false; $link = array();
    $id = ($_POST["id"]);
    for($i=0;$i<count($_FILES['fichier']['name']);$i++)
    {
      $ext = substr(strrchr($_FILES['fichier']['name'][$i], "."), 1);
      if(in_array($ext,$ext_autorisees))
      {
        $Result1 = move_uploaded_file($_FILES['fichier']['tmp_name'][$i],
        $url_site.$_FILES['fichier']['name'][$i]);
        if($Result1) array_push($link,$url_site.$_FILES['fichier']['name'][$i]);
      }
    }
    if($Result1){
    mysql_query_ruche("DOC".implode('|',$link), $pdar_connexion,1);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."mission_supervision SET document=".GetSQLValueString(implode('|',$link), "text").", etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE code_ms='$id'");
	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  else
  {
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
else
{
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo));  exit();
}
}

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]);     //and projet='".$_SESSION["clp_projet"]."'
  $query_liste_mission = "SELECT * FROM ".$database_connect_prefix."mission_supervision WHERE code_ms='$id'  ";
   try{
    $liste_mission = $pdar_connexion->prepare($query_liste_mission);
    $liste_mission->execute();
    $row_liste_mission = $liste_mission ->fetch();
    $totalRows_liste_mission = $liste_mission->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}
else
{                  //where projet='".$_SESSION["clp_projet"]."'
  //Mission supervision
  $query_liste_mission = "SELECT * FROM ".$database_connect_prefix."mission_supervision ";
     try{
    $liste_mission1 = $pdar_connexion->prepare($query_liste_mission);
    $liste_mission1->execute();
    $row_liste_mission1 = $liste_mission1 ->fetchAll();
    $totalRows_liste_mission1 = $liste_mission1->rowCount();
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
<?php if(!isset($_GET['document'])) { ?>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<?php } ?>
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
        $(".modal-dialog", window.parent.document).width(700);
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
<?php if(!isset($_GET['add']) && !isset($_GET["document"])) { ?>
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> Missions de recommandation</h4>
   <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<a href="<?php echo $_SERVER['PHP_SELF']."?add=1"; ?>" class="pull-right p11" title="Ajout une mission" ><i class="icon-plus"> Nouvelle mission </i></a>
<?php } ?>
</div>
<div class="widget-content">
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >
            <thead>
                <tr>
                  <td rowspan="2"><div align="left"><strong>Code</strong></div></td>
                  <td rowspan="2"><div align="left"><strong>Objet</strong></div></td>
                  <!--<td rowspan="2"><div align="left"><strong>Resum&eacute;</strong></div></td>-->
                  <td rowspan="2"><div align="left"><strong>Type</strong></div></td>
                  <td colspan="2"><div align="center"><strong>P&eacute;riode</strong></div></td>
                  <td rowspan="2"><div align="left"><strong>Documents</strong></div></td>
                  <!--<td rowspan="2"><div align="left"><strong>Observations</strong></div></td>-->
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center" width="90" rowspan="2" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
                <tr>
                  <td width="90"><div align="center"><strong>D&eacute;but</strong></div></td>
                  <td width="90"><div align="center"><strong>Fin</strong></div></td>
                </tr>
            </thead>
                <?php if($totalRows_liste_mission1>0) {$i=0; foreach($row_liste_mission1 as $row_liste_mission1){   $id = $row_liste_mission1['code_ms']; ?>
                <tr>
                  <td><?php echo $row_liste_mission1['code_ms']; ?></td>
                  <td><div align="left"><?php echo $row_liste_mission1['objet']; ?></div></td>
                  <!--<td><div align="left"><?php //echo $row_liste_mission1['resume']; ?></div></td>-->
                  <td><div align="left"><?php echo $row_liste_mission1['type']; ?></div></td>
                  <td><div align="left"><?php echo $row_liste_mission1['debut']; ?></div></td>
                  <td><div align="left"><?php echo $row_liste_mission1['fin']; ?></div></td>
                  <td><div align="left">
<?php $titre = "Ajouter"; $titre1 = "Ajout"; if(isset($row_liste_mission1["document"]) && !empty($row_liste_mission1["document"])){ $a = explode("|",$row_liste_mission1["document"]); $j=1; foreach($a as $file){ if(file_exists($file)){ $name = substr(strrchr($file, "/"), 1); echo "<a href='./download_file.php?file=".$file."' title='".$name."' style='display:block;' >Fichier ".$j."</a>"; $j++; } } $titre = "Modifier"; $titre1 = "Modification"; } ?>
<div align="center"><a href="#myModal_add" data-backdrop="static" data-keyboard="false" data-toggle="modal" title="<?php echo $titre1; ?> de document de mission" onclick="get_content('./new_mission.php','<?php echo "id=$id&document=1"; ?>','modal-body_add',this.title);" style=""><?php echo $titre; ?></a></div>
                  </div></td>
                 <!-- <td><div align="left"><?php //echo $row_liste_mission1['observation']; ?></div></td>-->
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<td class=" " align="center">
<?php
echo do_link("",$_SERVER['PHP_SELF']."?id=$id&add=1","Modifier Mission ".$id,"","edit","./","","",1,"margin:0px 5px;",'mission_supervision.php');

echo do_link("",$_SERVER['PHP_SELF']."?id_sup_mission=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette mission ?');",0,"margin:0px 5px;",'mission_supervision.php');
?></td>
<!--<td align="center">
<a href="<?php echo $_SERVER['PHP_SELF']."?id=$id&add=1"; ?>" title="Modifier mission" style="margin:0px 5px;"><img src="./images/edit.png" width="20" height="20" alt="Modifier" title="Modifier"></a>
<a href="<?php echo $_SERVER['PHP_SELF']."?id_sup_mission=".$id; ?>" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cette mission ?');" style="margin:0px 5px;"><img src="./images/delete.png" width="20" height="20" alt="Supprimer" title="Supprimer"></a> </td>-->
<?php } ?>
				  </tr>
                <?php }  ?>
                <?php }else{ ?>
                <td colspan="<?php echo (isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1))?8:7; ?>"><h2 align="center">Aucune mission disponible !</h2></td>
                <?php } ?>
              </table>

</div></div>
</div>
<?php include 'modal_add.php'; ?>
<?php } elseif(isset($_GET["document"])) { //Transfert de fichier ?>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification de mission":"Nouvelle de mission"; ?></h4>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form4" id="form3" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr>
      <td valign="middle">
        <div class="form-group">
          <label for="fichier" class="col-md-5 control-label">Documents <span class="required">*</span></label>
          <div class="col-md-6">
            <input class="form-control required" type="file" name="fichier[]" id="fichier" value="" size="32" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/pdf,application/vnd.ms-word,image/jpeg,.doc,.docx,.zip,.rar" multiple />
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo ($_GET["id"]); ?>" />
<?php } ?>
<?php if(isset($_GET["annee"])){ ?>
  <input type="hidden" name="annee" value="<?php echo intval($_GET["annee"]); ?>" />
<?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
<?php if(!isset($_GET['add2'])) { ?>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn pull-right" title="Annuler" >Annuler</a>
<?php } ?>
  <input name="MM_insert" type="hidden" value="MM_insert" size="32" alt="">
<input name="MM_form" id="MM_form" type="hidden" value="form4" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } } else{ ?>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification de mission":"Nouvelle de mission"; ?></h4>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
 <tr valign="top">
      <td>
        <div class="form-group" id="code_zone">
          <label for="code_ms" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-9">
            <input <?php echo isset($row_liste_mission['code_ms'])?'readonly="readonly"':""; ?> class="form-control required" type="text" name="code_ms" id="code_ms" value="<?php echo isset($row_liste_mission['code_ms'])?$row_liste_mission['code_ms']:""; ?>" size="32" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_mission['code_ms']."'"; ?>) check_code('verif_code.php?t=mission_supervision&','w=code_ms='+this.value+' and projet=<?php echo $_SESSION["clp_projet"]; ?>','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group" id="code_zone">
          <label for="code" class="col-md-3 control-label">Objet <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="objet" id="objet" value="<?php echo isset($row_liste_mission['objet'])?$row_liste_mission['objet']:""; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="resume" class="col-md-3 control-label">Resum&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="3" type="text" name="resume" id="resume"><?php echo isset($row_liste_mission['resume'])?$row_liste_mission['resume']:""; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="type" class="col-md-3 control-label">Type <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="type" id="type" class="form-control required" >
              <option value="">Selectionnez</option>
              <option value="Supervision" <?php if(isset($_GET['id']) && $row_liste_mission['type']=="Supervision") echo 'selected="selected"'; ?>>Supervision</option>
              <option value="Comit&eacute; de pilotage" <?php if(isset($_GET['id']) && $row_liste_mission['type']=="Comité de pilotage") echo 'selected="selected"'; ?>>Comit&eacute; de pilotage</option>
              <option value="Appui ponctuel" <?php if(isset($_GET['id']) && $row_liste_mission['type']=="Appui ponctuel") echo 'selected="selected"'; ?>>Appui ponctuel</option>
              <option value="Suivi ministeriel" <?php if(isset($_GET['id']) && $row_liste_mission['type']=="Suivi ministeriel") echo 'selected="selected"'; ?>>Suivi ministeriel</option>
              <option value="Audit" <?php if(isset($_GET['id']) && $row_liste_mission['type']=="Audit") echo 'selected="selected"'; ?>>Audit</option>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="debut" class="col-md-3 control-label">Date de D&eacute;but <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control datepicker required" type="text" name="debut" id="debut" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',$row_liste_mission['debut']))); else echo date("d/m/Y"); ?>" size="32" />
          </div>
        <div class="col-md-6">
          <label for="date_buttoir" class="col-md-5 control-label">Date de fin <span class="required">*</span></label>
          <div class="col-md-7">
            <input class="form-control datepicker required" type="text" name="fin" id="fin" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',$row_liste_mission['fin']))); else echo date("d/m/Y"); ?>" size="10" />
          </div>
        </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="observation" class="col-md-3 control-label">Observations </label>
          <div class="col-md-9">
            <textarea class="form-control " cols="200" rows="3" type="text" name="observation" id="observation"><?php echo isset($row_liste_mission['observation'])?$row_liste_mission['observation']:""; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo ($_GET["id"]); ?>" />
<?php } ?>
<?php if(isset($_GET["annee"])){ ?>
  <input type="hidden" name="annee" value="<?php echo intval($_GET["annee"]); ?>" />
<?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
<?php if(!isset($_GET['add2'])) { ?>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn pull-right" title="Annuler" >Annuler</a>
<?php } ?>
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(!isset($_GET['add2']) && isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2)) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette mission ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>
<?php } ?>