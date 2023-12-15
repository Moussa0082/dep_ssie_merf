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

$dir = './attachment/';
//if(!is_dir($dir)) mkdir($dir);
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

if ((isset($_GET["id_sup_mission"]) && !empty($_GET["id_sup_mission"]))) {
  $id = ($_GET["id_sup_mission"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."version_ptba WHERE id_version_ptba=%s",
                       GetSQLValueString($id, "text"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }
                                        
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{ //Fonction
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."version_ptba (annee_ptba, version_ptba, date_validation, observation, projet, date_enregistrement, id_personnel) VALUES (%s, %s, %s, %s, %s, '$date', '$personnel')",
                          GetSQLValueString($_POST['annee_ptba'], "int"),
						 GetSQLValueString($_POST['version_ptba'], "text"),
                         GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_validation']))), "date"),
                         GetSQLValueString($_POST['observation'], "text"),
                        GetSQLValueString($_SESSION["clp_projet"], "text"));

   try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."version_ptba WHERE id_version_ptba=%s",
                         GetSQLValueString($id, "text"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
    $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."version_ptba SET annee_ptba=%s, version_ptba=%s, date_validation=%s, observation=%s, statut_version=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date'  WHERE id_version_ptba=%s",
                          GetSQLValueString($_POST['annee_ptba'], "text"),
						 GetSQLValueString($_POST['version_ptba'], "text"),
                         GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_validation']))), "date"),
                         GetSQLValueString($_POST['observation'], "text"),
						  GetSQLValueString($_POST['statut_version'], "int"),
                         GetSQLValueString($id, "text"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?date=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
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
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."version_ptba SET document=".GetSQLValueString(implode('|',$link), "text").", etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_version_ptba='$id'");

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
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
  $query_liste_mission = "SELECT * FROM ".$database_connect_prefix."version_ptba WHERE id_version_ptba='$id'  ";
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
  $query_liste_mission1 = "SELECT * FROM ".$database_connect_prefix."version_ptba order by annee_ptba desc, date_validation desc ";
    	   try{
    $liste_mission1 = $pdar_connexion->prepare($query_liste_mission1);
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
<style>
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 2px 8px;background: #EBEBEB;}

@media(min-width:558px){.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}
</style>
<?php if(!isset($_GET['add']) && !isset($_GET["document"])) { ?>
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> Version PTBA</h4>
   <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==0){ ?>
<?php echo do_link("",$_SERVER['PHP_SELF']."?add=1","Ajout une version","<i class='icon-plus'> Nouvelle version </i>","simple","./","pull-right p11","",0,"","plan_ptba.php"); ?>
<?php } ?>
</div>
<div class="widget-content">
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >
            <thead>
                <tr>
                  <td><div align="left"><strong>Ann&eacute;e</strong></div></td>
                  <td><div align="left"><strong>Version</strong></div></td>
                  <!--<td rowspan="2"><div align="left"><strong>Resum&eacute;</strong></div></td>-->
                  <td><div align="center"><strong>Date de validation</strong></div></td>
                  <td><div align="left"><strong>Documents</strong></div></td>
                  <td><div align="left"><strong>Observations</strong></div></td>
                  <td><strong>Statut</strong></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==0)) { ?>
                  <td align="center" width="90" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
                <?php 
				 if($totalRows_liste_mission1>0) { foreach($row_liste_mission1 as $row_liste_mission1){ $i=0; $id = $row_liste_mission1['id_version_ptba']; ?>
                <tr >
                  <td ><div align="center"><?php echo $row_liste_mission1['annee_ptba']; ?></div></td>
                  <td><div align="left"><?php echo $row_liste_mission1['version_ptba']; ?></div></td>
                  <td><div align="left"><?php echo date_reg($row_liste_mission1['date_validation'],"/"); ?></div></td>
                  <td><div align="left">
<?php $titre = "Ajouter"; $titre1 = "Ajout"; if(isset($row_liste_mission1["document"]) && !empty($row_liste_mission1["document"])){ $a = explode("|",$row_liste_mission1["document"]); $j=1; foreach($a as $file){ if(file_exists($file)){ $name = substr(strrchr($file, "/"), 1); echo "<a href='./download_file.php?file=".$file."' title='".$name."' style='display:block;' >Fichier ".$j."</a>"; $j++; } } $titre = "Modifier"; $titre1 = "Modification"; } ?>
<div align="center">
<?php
//echo do_link("",$_SERVER['PHP_SELF']."?id=$id&document=1","$titre1 de document de mission","$titre","simple","./","","",0,"","mission_supervision.php");
echo do_link("","","$titre1 de document de PTBA","$titre","simple","./","","get_content('new_version_ptba.php','id=$id&document=1','modal-body_add',this.title);",1,"",'plan_ptba.php');
?>
                  </div></td>
                  <td><div align="left"><?php echo $row_liste_mission1['observation']; ?></div></td>
                  <td><div align="center">
                    <?php if(isset($row_liste_mission1['statut_version']) && $row_liste_mission1['statut_version']==2) { ?>
                    <img src="./images/valid.png"  alt="En cours">
                    <?php } elseif(isset($row_liste_mission1['statut_version']) && $row_liste_mission1['statut_version']==1) {  ?>
                    <img src="./images/access.png"  alt="Vérrouillée">
                  <?php }  ?></div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==0)) { ?>
<td class=" " align="center">
<?php
echo do_link("",$_SERVER['PHP_SELF']."?id=$id&add=1","Modifier version PTBA ".$id,"","edit","./","","",1,"margin:0px 5px;",'mission_supervision.php');

echo do_link("",$_SERVER['PHP_SELF']."?id_sup_mission=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette version ?');",0,"margin:0px 5px;",'plan_ptba.php');
?></td>
<?php } ?>
	    </tr>
                <?php }  ?>
                <?php }else{ ?>
                <td colspan="<?php echo (isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==0))?8:7; ?>"><h2 align="center">Aucune version disponible !</h2></td>
                <?php } ?>
              </table>

</div></div>
</div>
<?php include 'modal_add.php'; ?>
<?php } elseif(isset($_GET["document"])) { //Transfert de fichier ?>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==0) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification":"Nouvelle"; ?></h4>
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
            <input class="form-control required" type="file" name="fichier[]" id="fichier" value="" size="32" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/pdf,application/vnd.ms-word,image/jpeg,.doc,.docx,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed" multiple />
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
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==0) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification de mission":"Nouvelle de mission"; ?></h4>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
     <tr valign="top">
      <td>
        <div class="form-group">
			  <label for="annee_ptba" class="col-md-3 control-label">Ann&eacute;e <span class="required">*</span></label>
          <div class="col-md-3">
            <select name="annee_ptba" id="annee_ptba" class="form-control required" >
              <option value="">Selectionnez</option>
<?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
    <option value="<?php echo $i; ?>" <?php if(isset($_GET["id"]) && $i==$row_liste_mission['annee_ptba']) echo 'SELECTED="selected"'; ?> ><?php echo $i; ?></option>
<?php } ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
   
    <tr valign="top">
      <td>
        <div class="form-group">
			  <label for="type" class="col-md-3 control-label">Version <span class="required">*</span></label>
          <div class="col-md-3">
            <select name="version_ptba" id="version_ptba" class="form-control required" >
              <option value="">Selectionnez</option>
              <option value="Initiale" <?php if(isset($_GET['id']) && $row_liste_mission['version_ptba']=="Initiale") echo 'selected="selected"'; ?>>Initiale</option>
              <option value="R&eacute;vis&eacute;e" <?php if(isset($_GET['id']) && $row_liste_mission['version_ptba']=="Révisée") echo 'selected="selected"'; ?>>R&eacute;vis&eacute;e</option>
            </select>
          </div>
        </div>
      </td>
    </tr>
	<?php if(isset($_GET['id'])) {; ?>
	   <tr valign="top">
      <td bgcolor="#FFCC33">
        <div class="form-group">
			  <label for="type" class="col-md-3 control-label">Statut <span class="required">*</span></label>
          <div class="col-md-3">
            <select name="statut_version" id="statut_version" class="form-control required" >
              <option value="">Selectionnez</option>
              <option value="0" <?php if(isset($_GET['id']) && $row_liste_mission['statut_version']=="0") echo 'selected="selected"'; ?>>En cours</option>
              <option value="2" <?php if(isset($_GET['id']) && $row_liste_mission['statut_version']=="2") echo 'selected="selected"'; ?>>Validée</option>
              <option value="1" <?php if(isset($_GET['id']) && $row_liste_mission['statut_version']=="1") echo 'selected="selected"'; ?>>Archivée</option>

            </select>
          </div>
        </div>
      </td>
    </tr>
	<?php } ?>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="date_validation" class="col-md-3 control-label">Date de validation <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control datepicker required" type="text" name="date_validation" id="date_validation" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',$row_liste_mission['date_validation']))); else echo date("d/m/Y"); ?>" size="32" />
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
<?php if(!isset($_GET['add2']) && isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==0)) { ?>
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