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
header('Content-Type: text/html; charset=UTF-8');

$dir = './attachment/dano/';

function NbJours($debut, $fin)
{
  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);
  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) -
          mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
  return(($diff / 86400)+1);
}

if(isset($_GET["dno"])){ $dno=$_GET['dno'];} $annee=$_GET['annee'];  $cp=(isset($_GET["cp"]))?$_GET['cp']:0;
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

if ((isset($_GET["id_sup"]) && intval($_GET["id_sup"])>0)) {
  $id = intval($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."suivi_dno WHERE id_suivi=%s",
                       GetSQLValueString($id, "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  $insertGoTo .= "&dno=$dno&annee=$annee&cp=$cp";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{ //SUivi DANO
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."suivi_dno (dno, date_phase, phase, observation, documents, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, '$personnel', '$date')",
                         GetSQLValueString($dno, "text"),
                         GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_phase']))), "date"),
                         GetSQLValueString($_POST['phase'], "text"),
                         GetSQLValueString($_POST['observation'], "text"),
                         GetSQLValueString(implode("|",$_POST['documents']), "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    $insertGoTo .= "&dno=$dno&annee=$annee&cp=$cp";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."suivi_dno WHERE id_suivi=%s",
                         GetSQLValueString($id, "int"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    $insertGoTo .= "&dno=$dno&annee=$annee&cp=$cp";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."suivi_dno SET  date_phase=%s, phase=%s, observation=%s, documents=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_suivi=%s",
                         GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_phase']))), "date"),
                         GetSQLValueString($_POST['phase'], "text"),
                         GetSQLValueString($_POST['observation'], "text"),
                         GetSQLValueString(implode("|",$_POST['documents']), "text"),
                         GetSQLValueString($id, "int"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    $insertGoTo .= "&dno=$dno&annee=$annee&cp=$cp";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form4"))
{
  $annee = (isset($_POST["annee"]))?$_POST["annee"]:date("Y");
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $personnel=$_SESSION['clp_id']; $date=date("Y-m-d");
  $code = explode(":",$_POST['code_activite']);
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."dno (code_activite, numero, destinataire, date_initialisation, objet, observation, projet, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s,%s,'$personnel', '$date')",

		  			   GetSQLValueString($code[0], "text"),
					   GetSQLValueString($_POST['numero'], "text"),
   					   GetSQLValueString($_POST['destinataire'], "text"),
                       GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_initialisation']))), "date"),
					   GetSQLValueString($_POST['objet'], "text"),
   					   GetSQLValueString($_POST['observation'], "text"),
					   GetSQLValueString($_SESSION["clp_projet"], "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    /*mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $query_liste_bailleur = "SELECT adresse_mail FROM ".$database_connect_prefix."partenaire WHERE dno=1 and code=".GetSQLValueString($_POST['destinataire'], "text");
    $liste_bailleur = mysql_query($query_liste_bailleur, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
    $mail = $row_liste_bailleur["adresse_mail"];
    $mail .= "&replay=dano@psac-ci.org";
    $mail .= "&titre=".$_POST['objet'];
    $mail .= "&attachment=".implode('|',$_POST['documents']);
    $template = "./phpmailer/template.html";
    $handle = fopen($template, 'w');
    fwrite($handle, trim($_POST['message']));    */

    $insertGoTo = $_SERVER['PHP_SELF'];
    if($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    //$insertGoTo .= "&dno=$dno&annee=$annee&cp=$cp&send=$mail";
    $insertGoTo .= "&dno=$dno&annee=$annee&cp=$cp";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{ //Upload file
  if ((isset($_FILES['fichier']['name'])) && count($_FILES['fichier']['name'])>0) {
    $ext_autorisees=array('docx','doc','pdf', 'xls', 'xlsx', 'jpeg', 'jpg', 'zip', 'rar'); //Extensions autorisées
    $url_site = $dir;//'./attachment/dano/';
    $Result1 = false; $link = array();
    for($i=0;$i<count($_FILES['fichier']['name']);$i++)
    {
      $ext = substr(strrchr($_FILES['fichier']['name'][$i], "."), 1);
      if(in_array($ext,$ext_autorisees))
      {
        $Result1 = move_uploaded_file($_FILES['fichier']['tmp_name'][$i],
        $url_site.$_FILES['fichier']['name'][$i]);
        if($Result1) array_push($link,$_FILES['fichier']['name'][$i]);
      }
    }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok&link=".implode('|',$link); else $insertGoTo .= "?insert=no";
    $insertGoTo .= "&add=1";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  else
  {
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    $insertGoTo .= "&add=1";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

if(isset($_GET["id"]) && !empty($_GET["id"]))
{ //Edit DANO
  $id=intval($_GET["id"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_edit_suivi_dno = "SELECT * FROM ".$database_connect_prefix."suivi_dno WHERE id_suivi='$id'";
  $edit_suivi_dno = mysql_query($query_edit_suivi_dno, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_edit_suivi_dno = mysql_fetch_assoc($edit_suivi_dno);
  $totalRows_edit_suivi_dno = mysql_num_rows($edit_suivi_dno);
}
elseif(isset($_GET["suivi"]) && !empty($_GET["suivi"]))
{ //Suivi
  $id=intval($_GET["suivi"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_edit_suivi_dno = "SELECT * FROM ".$database_connect_prefix."suivi_dno WHERE id_suivi='$id'";
  $edit_suivi_dno = mysql_query($query_edit_suivi_dno, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_edit_suivi_dno = mysql_fetch_assoc($edit_suivi_dno);
  $totalRows_edit_suivi_dno = mysql_num_rows($edit_suivi_dno);
}
elseif(isset($_GET["new"]) && !empty($_GET["new"]))
{ //New DANO
  $id=intval($_GET["new"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_new_dano = "SELECT * FROM ".$database_connect_prefix."mail_dno WHERE id_mail='$id'";
  $new_dano = mysql_query($query_new_dano, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_new_dano = mysql_fetch_assoc($new_dano);
  $totalRows_new_dano = mysql_num_rows($new_dano);
}
else
{ //Liste DANO
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_sdno = "SELECT * FROM ".$database_connect_prefix."mail_dno where traitement=0 and dno is null ORDER BY date desc";
  $liste_sdno  = mysql_query($query_liste_sdno , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_sdno = mysql_fetch_assoc($liste_sdno);
  $totalRows_liste_sdno = mysql_num_rows($liste_sdno);
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edits_dno = "SELECT * FROM ".$database_connect_prefix."dno WHERE numero='$dno'";
$edits_dno = mysql_query($query_edits_dno, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edits_dno = mysql_fetch_assoc($edits_dno);
$totalRows_edits_dno = mysql_num_rows($edits_dno);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
//$id=$row_liste_conv['id_dno'];
$query_edit_date_ano = "SELECT dno, phase, date_phase FROM ".$database_connect_prefix."suivi_dno where dno='$dno'";
$edit_date_ano = mysql_query($query_edit_date_ano, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_date_ano = mysql_fetch_assoc($edit_date_ano);
$totalRows_edit_date_ano = mysql_num_rows($edit_date_ano);

if($totalRows_edit_date_ano>0){  do{
  if($row_edit_date_ano["phase"]=="ANO") $v_date_ano=$row_edit_date_ano["date_phase"];
  if($row_edit_date_ano["phase"]=="Envoi au bailleur") $v_date_envoi=$row_edit_date_ano["date_phase"];
    if($row_edit_date_ano["phase"]=="Objection du bailleur") $v_date_rejet=$row_edit_date_ano["date_phase"];
   }while($row_edit_date_ano = mysql_fetch_assoc($edit_date_ano));
}
//echo $v_date_rejet;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_bailleur = "SELECT * FROM ".$database_connect_prefix."partenaire WHERE dno=1 ";
$liste_bailleur = mysql_query($query_liste_bailleur, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
$totalRows_liste_bailleur = mysql_num_rows($liste_bailleur);
$destinateur_array = array();
if($totalRows_liste_bailleur>0){ do{
  $destinateur_array[$row_liste_bailleur["adresse_mail"]] = $row_liste_bailleur["code"];
}while($row_liste_bailleur = mysql_fetch_assoc($liste_bailleur));
    $rows = mysql_num_rows($liste_bailleur);
    if($rows > 0) {
        mysql_data_seek($liste_bailleur, 0);
  	  $row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
    }
}
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
<link href="<?php print $config->theme_folder; ?>/plugins/datatables_bootstrap.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder; ?>/plugins/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder; ?>/plugins/wysiwyg-color.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>
<script type="text/javascript" src="plugins/noty/layouts/top.js"></script>
<script type="text/javascript" src="plugins/noty/themes/default.js"></script>
<script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap-typeahead.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="plugins/bootstrap-wysihtml5/wysihtml5.min.js"></script>
<script type="text/javascript" src="plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.min.js"></script>
<?php if((!isset($_GET['add'])) || isset($_GET["suivi"]) || isset($_GET["new"])) { ?>
<script type="text/javascript" src="plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>
<script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>
<?php } ?>
<script>
$().ready(function() {
		// validate the comment form when it is submitted
		$("#form3").validate();
        $("#form4").validate();
        $(".wysiwyg").each(function(){$(this).wysihtml5({parser: function(html) {return html;}});});
<?php if((!isset($_GET['add']))) { ?>
        $(".modal-dialog", window.parent.document).width(700);
        $("#send_mail").click();
        var oTable = $('#mtable').dataTable( {
                "iDisplayLength": -1,
                paging: false
            });
<?php } ?>
<?php if(isset($_GET['add'])) { ?>
        $(".modal-dialog", window.parent.document).width(600);
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
        var oTable = $('#mtable').dataTable( {
                "iDisplayLength": -1,
                paging: false
            });
<?php } ?>
});
</script>
<style>
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}
.dataTables_length, .dataTables_info { float: left; font-size: 10px;}
.dataTables_length, .dataTables_paginate, .DTTT, .ColVis { display: none;}

@media(min-width:558px){.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}
.wysihtml5-sandbox{ width: 100%!important;}

</style>
</head>
<body>
<?php if(!isset($_GET['add'])) { ?>
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> Courriers DANO</h4>
 <?php if(!isset($v_date_ano) && !isset($v_date_rejet)) {  ?>
  <?php /*if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<!--<a href="<?php echo $_SERVER['PHP_SELF']."?add=1&dno=$dno&annee=$annee"; ?>" class="pull-right p11" title="Ajout une suivi de la DNO" ><i class="icon-plus"> Ajouter </i></a>  -->
<?php }*/ ?>
<?php } ?>
</div>
<div class="widget-content">
<?php /*if(isset($_GET["send"])){ ?>
<a id="send_mail" onclick="$('.modal-body').html('<h1 align=\'center\'>Envoi du mail en cours&middot;&middot;&middot;<br /></h1>');get_content('phpmailer/mail_dno.php','adresse=<?php echo $_GET["send"]; ?>|','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Envoyer un mail des DANO" class="pull-right p11 hidden" dir="">&nbsp;</a>
<?php }*/ ?>
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable dataTable" align="center" id="mtable" >
            <thead>
                <tr>
                  <td><div align="left"><strong>Date</strong></div></td>
                  <td><div align="left"><strong>Exp&eacute;diteur</strong></div></td>
                  <td><div align="left"><strong>Objet</strong></div></td>
                  <td><div align="left"><strong>Contenu</strong></div></td>
                  <!--<td><div align="left"><strong>Documents</strong></div></td>-->
                  <td><div align="center"><strong>Actions</strong></div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <?php } ?>
                </tr>
            </thead>
                <?php if($totalRows_liste_sdno>0) {$i=0;do {
                  $id = $row_liste_sdno['id_mail']; $read=$row_liste_sdno['statut'];  ?>
                <tr>
                  <td><div align="left"><?php echo date_reg($row_liste_sdno['date'],"/"); ?></div></td>
                  <td><div align="left"><?php echo $row_liste_sdno['expediteur']; ?></div></td>
                  <td><?php echo $row_liste_sdno['objet']; ?></td>
                  <td>  <?php if(!empty($row_liste_sdno['observation'])) {?>
<a onclick="get_content('edit_contenu_suivi_dno.php','id=<?php echo $id; ?>&annee=<?php echo $annee; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Contenu du suivi de la DANO <?php echo $row_liste_conv['numero']; ?>" class="thickbox Add"  dir=""> <?php  echo $row_liste_sdno['observation']; ?> </a>  <?php  } else
echo do_link("","","Contenu du message","Editer","","./","pull-right p11","get_content('body_mail_dno.php','id=$id','modal-body_add',this.title,'iframe');",1,"",$nfile)."<div align='center' style='color: #ADADAD;font-size: 11px;'>".(($read==0)?"Non lu":"Lu")."</div>";
//echo "Editer";  ?></td>
<!--<td align="center"><?php if(!empty($row_liste_sdno['documents'])) {?>
<a onclick="get_content('edit_suivi_dno_documents.php','id=<?php echo $id; ?>&annee=<?php echo $annee; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Documents de la DNO <?php echo $row_liste_conv['numero']; ?>" class="thickbox Add"  dir="">Afficher <?php  $a = explode('|',$row_liste_sdno['documents']); echo (count($a)>0)?"(".count($a).")":"0"; ?> </a>  <?php  } else echo "-";  ?> </td>-->

<td align="center"> <?php if(isset($destinateur_array[$row_liste_sdno["expediteur"]])) { ?>
<?php
echo do_link("",$_SERVER['PHP_SELF']."?add=1&annee=$annee&dno=$dno&suivi=$id","Suivre une DANO","Suivre une DANO","","./","","",0,"margin:0px 5px;",$nfile);
?>
<!--<a href="<?php echo $_SERVER['PHP_SELF']."?id=$id&add=1&annee=$annee&dno=$dno"; ?>" title="Suivre une DANO" style="margin:0px 5px;">Suivre une DANO</a>   -->
 <?php  } else { ?>
 <?php
echo do_link("",$_SERVER['PHP_SELF']."?add=1&annee=$annee&dno=$dno&new=$id","Nouvelle DANO","Nouvelle DANO","","./","","",0,"margin:0px 5px;",$nfile);
?>
<!-- <a href="<?php echo $_SERVER['PHP_SELF']."?id=$id&add=1&annee=$annee&dno=$dno"; ?>" title="Nouvelle DANO" style="margin:0px 5px;">Nouvelle DANO</a>  -->
 <?php  }  ?>
 </td>
				   <?php /*if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<td align="center" nowrap="nowrap">

 <?php if($row_liste_sdno['phase']!="Envoi au bailleur" || intval($totalRows_liste_sdno)==1) {  ?>
<?php
echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id."&annee=$annee&dno=$dno","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette suivi ?');",0,"margin:0px 5px;",$nfile);
?>
<!--<a href="<?php echo $_SERVER['PHP_SELF']."?id_sup=".$id."&annee=$annee&dno=$dno"; ?>" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cette suivi ?');" style="margin:0px 5px;"><img src="./images/delete.png" width="20" height="20" alt="Supprimer" title="Supprimer"></a> --></td>
                   <?php } ?>
				   <?php } */?>
	    </tr>
                <?php } while ($row_liste_sdno = mysql_fetch_assoc($liste_sdno)); ?>
                <?php } ?>
              </table>

</div></div>
</div>
<?php } elseif(isset($_GET["suivi"])) { //suivie section ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Suivi de DANO<?php  /*if(isset($row_edits_dno['date_initialisation'])) echo "Reçu: ".date_reg($row_edits_dno['date_initialisation'],"/")."&nbsp;";
if(isset($v_date_envoi)) $denvoi=$v_date_envoi; else $denvoi=date("Y-m-d"); if (isset($row_edits_dno['date_initialisation'])&& isset($denvoi)) { $Nombres_joursm = NbJours($row_edits_dno['date_initialisation'], $denvoi);
if($denvoi>=$row_edits_dno['date_initialisation']) {echo "=> ".number_format($Nombres_joursm, 0, ',', ' ')." J";}else echo "??";} ?>&nbsp;&nbsp;
<?php  if(isset($v_date_envoi)) echo "Envoyé: ".date_reg($v_date_envoi,"/")."&nbsp;";
if(isset($v_date_ano)) $dtano=$v_date_ano; elseif(isset($v_date_rejet)) $dtano=$v_date_rejet; else $dtano=date("Y-m-d"); if (isset($v_date_envoi)) { $Nombres_joursm = NbJours($v_date_envoi, $dtano);
if($dtano>=$v_date_envoi) {echo "=> ".number_format($Nombres_joursm, 0, ',', ' ')." J";}else echo "??";}*/ ?></h4>
<a href="<?php echo $_SERVER['PHP_SELF']."?dno=$dno&annee=$annee&cp=$cp"; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="<?php echo $_SERVER['PHP_SELF']."?dno=$dno&annee=$annee&cp=$cp"; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">
<table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="date_phase" class="col-md-3 control-label">Date de suivi <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control datepicker required" type="text" name="date_phase" id="date_phase" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',$row_edit_suivi_dno['date_phase']))); else echo date("d/m/Y"); ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="phase" class="col-md-3 control-label">Phase <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="phase" id="phase" class="form-control required" >
              <option value="">Selectionnez</option>
              <option value=""<?php if(isset($_GET['id'])){if (!(strcmp("", $row_edit_suivi_dno['phase']))) {echo "SELECTED";}} ?>>-- Choisissez --</option>
 <?php if(!isset($v_date_envoi)) {  ?>
<option value="Envoi au bailleur" <?php if(isset($_GET['id']) && $row_edit_suivi_dno['phase']=="Envoi au bailleur") echo 'selected="selected"'; ?>>Envoi au bailleur</option>

 <?php } elseif(isset($v_date_envoi) && isset($_GET['id']) && $row_edit_suivi_dno['phase']=="Envoi au bailleur") {  ?>
<option value="Envoi au bailleur" <?php if(isset($_GET['id']) && $row_edit_suivi_dno['phase']=="Envoi au bailleur") echo 'selected="selected"'; ?>>Envoi au bailleur</option>

 <?php } elseif((isset($v_date_ano) || isset($v_date_rejet))  && isset($_GET['id']) && ($row_edit_suivi_dno['phase']=="ANO" || $row_edit_suivi_dno['phase']=="Objection du bailleur")) {  ?>
 <option value="ANO" <?php if(isset($_GET['id']) && $row_edit_suivi_dno['phase']=="ANO") echo 'selected="selected"'; ?>>ANO du bailleur</option>
<option value="Objection du bailleur" <?php if(isset($_GET['id']) && $row_edit_suivi_dno['phase']=="Objection du bailleur") echo 'selected="selected"'; ?>>Objection du bailleur</option>
 <?php } else { ?>
   <option value="Retour du bailleur" <?php if(isset($_GET['id']) && $row_edit_suivi_dno['phase']=="Retour du bailleur") echo 'selected="selected"'; ?>>Retour du bailleur</option>
   <option value="Renvoi au bailleur" <?php if(isset($_GET['id']) && $row_edit_suivi_dno['phase']=="Renvoi au bailleur") echo 'selected="selected"'; ?>>Renvoi au bailleur</option>
     <?php if(!isset($v_date_ano) && !isset($v_date_rejet)) {  ?>
    <option value="ANO" <?php if(isset($_GET['id']) && $row_edit_suivi_dno['phase']=="ANO") echo 'selected="selected"'; ?>>ANO du bailleur</option>
<option value="Objection du bailleur" <?php if(isset($_GET['id']) && $row_edit_suivi_dno['phase']=="Objection du bailleur") echo 'selected="selected"'; ?>>Objection du bailleur</option>
<?php }   ?>
 <?php }   ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="observation" class="col-md-3 control-label">Observations <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="3" type="text" name="observation" id="observation"><?php if(isset($_GET['id'])) echo $row_edit_suivi_dno['observation']; else echo "RAS"; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="documents" class="col-md-3 control-label">Documents </label>
<?php echo do_link("","","Ajout de fichier","Ajout de fichier","","./","pull-right","get_content('new_document.php','dir=$dir&page=".$_SERVER['PHP_SELF']."','modal-body_add',this.title);",1,"",$nfile); ?>
          <div class="col-md-9">
<div style="height: 150px;overflow:scroll;">
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable dataTable" align="center" id="mtable" >
            <thead style="display: none;">
                <tr>
                  <td><div align="left"><strong>Phase</strong></div></td>
                </tr>
            </thead>
                <?php $d = "./attachment/dano/";
                if($handle = opendir($d)) { $i=0;
                    while($file = readdir($handle)) {
                      if(is_file($d.$file) && !in_array($file, array("index.php","."))){
                        $fichier = $d.$file;
                        $as = (isset($_GET['id']))?explode("|", $row_edit_suivi_dno['documents']):array();
                ?>
                <tr>
                  <td>
<input <?php if(is_array($as) && in_array($fichier, $as, TRUE)) echo 'checked="checked"'; ?>  type="checkbox" id="methode_<?php echo $i; ?>" name="documents[]" value="<?php echo $fichier; ?>" /><label for="methode_<?php echo $i; ?>" title="<?php echo $file; ?>"><?php echo $file; ?></label>
                  </td>
<!--                  <td><?php //echo date("d/m/Y à H:i:s", filemtime($d.$file)); ?></td>
                  <td align="right"><?php //echo formatSize(filesize($d.$file)); ?></td>-->
				</tr>
                <?php $i++; } } closedir($handle); } ?>
              </table>
<div class="clear h0">&nbsp;</div>
</div>
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo intval($_GET["id"]); ?>" />
<?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
<a href="<?php echo $_SERVER['PHP_SELF']."?annee=$annee&dno=$dno&cp=$cp"; ?>" class="btn pull-right" title="Annuler" >Annuler</a>
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2)) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette suivi ?','<?php echo intval($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } else { //Nouvelle DANO Section ?>
<script>
	$().ready(function() {
$("input.typeahead").typeahead({
    onSelect: function(item) {
        //console.log(item);
    },
    ajax: {
        url: "./ajax_code_activite_ptba.php?path=./",
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
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Nouvelle DANO</h4>
<a href="<?php echo $_SERVER['PHP_SELF']."?dno=$dno&annee=$annee&cp=$cp"; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="<?php echo $_SERVER['PHP_SELF']."?dno=$dno&annee=$annee&cp=$cp"; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form4" id="form4" novalidate="novalidate">

<table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="code_activite" class="col-md-3 control-label">Activit&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
             <input name="code_activite" type="text" class="form-control typeahead required" id="code_activite" value="<?php //if(isset($_GET["new"]) && !empty($_GET["new"])) echo $row_edit_contrat['code_activite'].': '.$activite_array1[$row_edit_contrat['code_activite']];  ?>" size="25" />
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="numero" class="col-md-3 control-label">Num&eacute;ro <span class="required">*</span></label>
          <div class="col-md-9">
            <input type="text" class="form-control required" name="numero" value="<?php //if(isset($_GET["new"]) && !empty($_GET["new"])) echo $row_edit_contrat['numero'];?>" >
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="destinataire" class="col-md-3 control-label">Destinataire <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="destinataire" id="destinataire" class="form-control required">
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_bailleur>0){ do { ?>
              <option value="<?php echo $row_liste_bailleur['code'];?>" <?php //if (isset($row_new_dano["expediteur"]) && $row_liste_bailleur['code']==$destinateur_array[$row_new_dano["expediteur"]]) {echo "SELECTED";} ?>><?php echo $row_liste_bailleur['code'].": ".((strlen($row_liste_bailleur['definition']>70)?substr($row_liste_bailleur['definition'],0, 70)." ...":$row_liste_bailleur['definition'])); echo " (".$row_liste_bailleur['sigle'].")";?></option>
                <?php  } while ($row_liste_bailleur = mysql_fetch_assoc($liste_bailleur)); } ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
<!--    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="date_initialisation" class="col-md-3 control-label">Date de reception <span class="required">*</span></label>
          <div class="col-md-9">
            <input type="text" class="form-control datepicker required" name="date_initialisation" id="date_initialisation" value="<?php //if(isset($_GET["new"]) && !empty($_GET["new"])) echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_new_dano['date']))))); else echo date("d/m/Y"); ?>" >
          </div>
        </div>
      </td>
    </tr>-->
    <input type="hidden" class="form-control required" name="date_initialisation" id="date_initialisation" value="<?php if(isset($_GET["new"]) && !empty($_GET["new"])) echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_new_dano['date']))))); else echo date("d/m/Y"); ?>" >
	<tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="objet" class="col-md-3 control-label">Objet <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" id="objet" name="objet" cols="25" rows="1"><?php if(isset($_GET["new"]) && !empty($_GET["new"])) echo $row_new_dano['objet'];?></textarea>
          </div>
        </div>
      </td>
    </tr>
	<tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="message" class="col-md-12 control-label">Message <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control wysiwyg required" id="message" name="message" rows="10" cols="25"><?php if(isset($_GET["new"]) && !empty($_GET["new"])) echo $row_new_dano['message'];?></textarea>
          </div>
        </div>
      </td>
    </tr>
<!--    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="observation" class="col-md-3 control-label">Observations </label>
          <div class="col-md-9">
            <textarea class="form-control" name="observation" cols="25" rows="1"><?php //if(isset($_GET["new"]) && !empty($_GET["new"])) echo $row_edit_contrat['observation'];?></textarea>
          </div>
        </div>
      </td>
    </tr>-->
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="documents" class="col-md-12 control-label">Documents </label>
<?php echo do_link("","","Ajout de fichier","Ajout de fichier","","./","pull-right","get_content('new_document.php','dir=$dir&page=".$_SERVER['PHP_SELF']."','modal-body_add',this.title);",1,"",$nfile); ?>
          <div class="col-md-12">
<div style="height: 150px;overflow:scroll;">
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable dataTable" align="center" id="mtable" >
            <thead style="display: none;">
                <tr>
                  <td><div align="left"><strong>Phase</strong></div></td>
                </tr>
            </thead>
                <?php $d = "./attachment/dano/";
                if($handle = opendir($d)) { $i=0;
                    while($file = readdir($handle)) {
                      if(is_file($d.$file) && !in_array($file, array("index.php","."))){
                        $fichier = $d.$file;
                        $as = (isset($_GET["new"]) && !empty($_GET["new"]))?explode("|", $row_new_dano['attachments']):array();
                ?>
                <tr>
                  <td>
<input <?php if(is_array($as) && in_array($file, $as, TRUE)) echo 'checked="checked"'; ?>  type="checkbox" id="methode_<?php echo $i; ?>" name="documents[]" value="<?php echo $fichier; ?>" /><label for="methode_<?php echo $i; ?>" title="<?php echo $file; ?>"><?php echo $file; ?></label>
                  </td>
<!--                  <td><?php //echo date("d/m/Y à H:i:s", filemtime($d.$file)); ?></td>
                  <td align="right"><?php //echo formatSize(filesize($d.$file)); ?></td>-->
				</tr>
                <?php $i++; } } closedir($handle); } ?>
              </table>
<div class="clear h0">&nbsp;</div>
</div>
          </div>
        </div>
      </td>
    </tr>
</table>

<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="Enregistrer & envoyé au bailleur" />
  <a href="<?php echo $_SERVER['PHP_SELF']."?annee=$annee&dno=$dno&cp=$cp"; ?>" class="btn pull-right" title="Annuler" >Annuler</a>
  <input name="MM_insert" type="hidden" value="MM_insert" size="32" alt="">
  <input name="MM_form" id="MM_form" type="hidden" value="form4" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>
<?php include_once 'modal_add.php'; ?>
</body>
</html>