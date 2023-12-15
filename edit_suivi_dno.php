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

function ReplaceEncodage($txt)
{
    $carimap = array(utf8_encode("é"), utf8_encode("è"), utf8_encode("ê"), utf8_encode("ë"), utf8_encode("ç"), utf8_encode("à"), utf8_encode("&nbsp;"), utf8_encode("À"), utf8_encode("É"), utf8_encode("'"), utf8_encode("oe"), utf8_encode(""));
    $carhtml = array("é", "è", "ê", "ë", "ç", "à", "&nbsp;", "À", "É", "'", "oe", "");
    $txt = str_replace($carimap, $carhtml, $txt);

    return $txt;
}

if(isset($_GET["dno"])){ $dno=$_GET['dno'];} $annee=$_GET['annee'];
if(isset($_GET["mod"])) $mod="&mod=1"; $mod="";
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

if ((isset($_GET["id_sup"]) && intval($_GET["id_sup"])>0)) {
  $id = intval($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."suivi_dno WHERE id_suivi=%s",
                       GetSQLValueString($id, "int"));


       	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  $insertGoTo .= "&dno=$dno&annee=$annee&mod=1";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{ //Suivi
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."suivi_dno (dno, date_phase, phase, observation, documents, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, '$personnel', '$date')",
                         GetSQLValueString($dno, "text"),
                         GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_phase']))), "date"),
                         GetSQLValueString($_POST['phase'], "text"),
                         GetSQLValueString($_POST['observation'], "text"),
                         GetSQLValueString($_POST['documents'], "text"));


       	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    //Traitement de document
    $doc = explode('|',$_POST['documents']); $doc2 = array();
    foreach($doc as $doc1){ if(!empty($doc1)) $doc2[] = $dir.$doc1; }
    $link = implode("|",$doc2);
    //if($Result1) mysql_query("DOC".$link, $pdar_connexion,1);
    //Fin Traitement de document

    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    $insertGoTo .= "&dno=$dno&annee=$annee&mod=1";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."suivi_dno WHERE id_suivi=%s",
                         GetSQLValueString($id, "int"));


       	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    $insertGoTo .= "&dno=$dno&annee=$annee&mod=1";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."suivi_dno SET  date_phase=%s, phase=%s, observation=%s, documents=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_suivi=%s",
                         GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_phase']))), "date"),
                         GetSQLValueString($_POST['phase'], "text"),
                         GetSQLValueString($_POST['observation'], "text"),
                         GetSQLValueString($_POST['documents'], "text"),
                         GetSQLValueString($id, "int"));


       	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

    //Traitement de document
    $doc = explode('|',$_POST['documents']); $doc2 = array();
    foreach($doc as $doc1){ if(!empty($doc1)) $doc2[] = $dir.$doc1; }
    $link = implode("|",$doc2);
    //if($Result1) mysql_query("DOC".$link, $pdar_connexion,1);
    //Fin Traitement de document

    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    $insertGoTo .= "&dno=$dno&annee=$annee&mod=1";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form4"))
{
  $annee = (isset($_POST["annee"]))?$_POST["annee"]:date("Y");
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $personnel=$_SESSION['clp_id']; $date=date("Y-m-d");
//Send mail
    $query_liste_bailleur = "SELECT adresse_mail FROM ".$database_connect_prefix."partenaire WHERE dno=1 and code=".GetSQLValueString($_POST['destinataire'], "text");
	
	                 try{
    $liste_bailleur = $pdar_connexion->prepare($query_liste_bailleur);
    $liste_bailleur->execute();
    $row_liste_bailleur = $liste_bailleur ->fetch();
   // $totalRows_liste_activite = $liste_activite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

    if($liste_bailleur)
    {
      $path="./phpmailer/";
      $sample = "./phpmailer/contents_dno.html";
      $dir = './attachment/dano/';
      $recipients = $attachment = array();
      $recipients[$row_liste_bailleur["adresse_mail"]] = "";
      $replay = "dano@psac-ci.org";
      $From = $_SESSION["clp_mail"];
      $sujet = $_POST['objet'];
      if(!isset($_POST["documents"])) unset($attachment);
      $a = explode("|",$_POST["documents"]);
      if(count($a)>0)
      {
        foreach($a as $b)
        {
          if(!empty($b)) array_push($attachment,$dir.$b);
        }
      }
      $message = trim(ReplaceEncodage($_POST['message']));
      $fichier = "./phpmailer/template.html";
      $template = "./phpmailer/template.html";
      ob_start(); // turn on output buffering
      include($sample);
      $content = ob_get_contents(); // get the contents of the output buffer
      ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering


      $content = str_replace('{titre}',$sujet,$content);
      $content = str_replace('{contenu}',$message,$content);
      // Assurons nous que le fichier est accessible en écriture
      if (is_writable($template)) {
          if (!$handle = fopen($template, 'w')) {
               echo "Impossible d'ouvrir le fichier ($template)";
               exit;
          }
          // Ecrivons quelque chose dans notre fichier.
          if (fwrite($handle, trim($content)) === FALSE) {
              echo "Impossible d'écrire dans le fichier ($template)";
              exit;
          }
          //echo "L'écriture de () dans le fichier ($template) a réussi";
          fclose($handle);
      } else {
          //echo "Le fichier $template n'est pas accessible en écriture."; exit();
      }
      $fichier = $template;

      include("./phpmailer/send_mail.php");
      if (isset($msg_sent))
      {

  //Insertion
      $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."suivi_dno (dno, date_phase, phase, observation, documents, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, '$personnel', '$date')",
                           GetSQLValueString($dno, "text"),
                           GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_phase']))), "date"),
                           GetSQLValueString($_POST['phase'], "text"),
                           GetSQLValueString($_POST['message'], "text"),
                           GetSQLValueString($_POST['documents'], "text"));

       	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
      if($Result1)
      {
        $insertSQL = sprintf("UPDATE ".$database_connect_prefix."dno SET traitement=0 WHERE numero=%s",
                             GetSQLValueString($dno, "text"));

        	  try{
    $Result2 = $pdar_connexion->prepare($insertSQL);
    $Result2->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
      }

      $insertGoTo = $_SERVER['PHP_SELF'];
      if($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
      //$insertGoTo .= "&dno=$dno&annee=$annee&send=$mail";
      $insertGoTo .= "&dno=$dno&annee=$annee&mod=1";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }
    else
    {
      $insertGoTo = $_SERVER['PHP_SELF'];
      $insertGoTo .= "?insert=no";
      $insertGoTo .= "&dno=$dno&annee=$annee&mail=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }
  }
  else
  {
    $insertGoTo = $_SERVER['PHP_SELF'];
    $insertGoTo .= "?insert=no";
    $insertGoTo .= "&dno=$dno&annee=$annee";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
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
    $insertGoTo .= (isset($_GET["msg"]))?"&msg=1":'';
    $insertGoTo .= "&dno=$dno&id=".$_GET["id"]."&add=1&mod=1";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  else
  {
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    $insertGoTo .= (isset($_GET["msg"]))?"&msg=1":'';
    $insertGoTo .= "&dno=$dno&id=".$_GET["id"]."&add=1&mod=1";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]);
  if(isset($_GET["msg2"]) && $_GET["msg2"]==1)
  $query_edit_suivi_dno = "SELECT * FROM ".$database_connect_prefix."suivi_dno WHERE dno='$id' and phase='Renvoi au bailleur' ORDER BY id_suivi desc LIMIT 1";
  elseif(isset($_GET["msg"]) && $_GET["msg"]==1)
  $query_edit_suivi_dno = "SELECT date_initialisation as date_phase, ".$database_connect_prefix."dno.* FROM ".$database_connect_prefix."dno WHERE numero='$id'";
  else
  $query_edit_suivi_dno = "SELECT * FROM ".$database_connect_prefix."suivi_dno WHERE id_suivi='$id'";
                     try{
    $edit_suivi_dno = $pdar_connexion->prepare($query_edit_suivi_dno);
    $edit_suivi_dno->execute();
    $row_edit_suivi_dno = $edit_suivi_dno ->fetch();
    $totalRows_edit_suivi_dno = $edit_suivi_dno->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}
else
{
  $query_liste_sdno = "SELECT * FROM ".$database_connect_prefix."suivi_dno where dno='$dno' ORDER BY date_phase desc";
                   try{
    $liste_sdno = $pdar_connexion->prepare($query_liste_sdno);
    $liste_sdno->execute();
    $row_liste_sdno = $liste_sdno ->fetchAll();
    $totalRows_liste_sdno = $liste_sdno->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}

$query_edits_dno = "SELECT * FROM ".$database_connect_prefix."dno WHERE numero='$dno'";
                 try{
    $edits_dno = $pdar_connexion->prepare($query_edits_dno);
    $edits_dno->execute();
    $row_edits_dno = $edits_dno ->fetch();
    $totalRows_edits_dno = $edits_dno->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//$id=$row_liste_conv['id_dno'];
$query_edit_date_ano = "SELECT dno, phase, date_phase FROM ".$database_connect_prefix."suivi_dno where dno='$dno'";
                 try{
    $edit_date_ano = $pdar_connexion->prepare($query_edit_date_ano); 
    $edit_date_ano->execute();
    $row_edit_date_ano = $edit_date_ano ->fetchAll();
    $totalRows_edit_date_ano = $edit_date_ano->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if($totalRows_edit_date_ano>0){  foreach($row_edit_date_ano as $row_edit_date_ano){
  if($row_edit_date_ano["phase"]=="ANO") $v_date_ano=$row_edit_date_ano["date_phase"];
  if($row_edit_date_ano["phase"]=="Envoi au bailleur") $v_date_envoi=$row_edit_date_ano["date_phase"];
    if($row_edit_date_ano["phase"]=="Objection du bailleur") $v_date_rejet=$row_edit_date_ano["date_phase"];
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
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="plugins/bootstrap-wysihtml5/wysihtml5.min.js"></script>
<script type="text/javascript" src="plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.min.js"></script>
<?php //if((!isset($_GET['add']) && isset($_GET['doc'])) || (isset($_GET['add']) && !isset($_GET['doc']))) { ?>
<script type="text/javascript" src="plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>
<script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>
<?php //} ?>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form3").validate();
        $(".modal-dialog", window.parent.document).width(700);
        //$("#send_mail").click();
<?php if(isset($_GET['mod'])) { ?>
        // reload parent frame
        $(".close", window.parent.document).click(function(){window.parent.location.reload();});
        $("button[data-dismiss='modal']", window.parent.document).click(function(){window.parent.location.reload();});
<?php } ?>
        $(".wysiwyg").each(function(){$(this).wysihtml5({parser: function(html) {return html;}});});
        $(".wysihtml5-toolbar").each(function(){$(this).addClass('hidden');});
        $(".wysihtml5-toolbar-edit").each(function(){$(this).attr('style','cursor:pointer;');$(this).click(function(){$(".wysihtml5-toolbar").toggleClass('hidden');});});
<?php if((!isset($_GET['add']) && isset($_GET['doc'])) || (isset($_GET['add']) && !isset($_GET['doc']))) { ?>
$(".dataTable").dataTable({"iDisplayLength": -1});

<?php } ?>
<?php if(isset($_GET['add'])) { ?>
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
<?php } ?>
	});
</script>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}
.dataTables_length, .dataTables_info { float: left; font-size: 10px;}
.dataTables_length, .dataTables_paginate { display: none;}

@media(min-width:558px){.col-md-12 {width: 100%;}.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}
</style>
</head>
<body>
<?php if(isset($_GET['mail']) && $_GET['mail']=="no") { ?>
<h3 align="center" style="color:red;">Une erreur s'est produite lors de l'envoi du mail !  </h3>
<?php exit;} ?>
<?php if(!isset($_GET['add'])) { ?>
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> <?php  if(isset($row_edits_dno['date_initialisation'])) echo "Reçu le ".date_reg($row_edits_dno['date_initialisation'],"/")."&nbsp;";
if(isset($v_date_envoi)) $denvoi=$v_date_envoi; else $denvoi=date("Y-m-d"); if (isset($row_edits_dno['date_initialisation'])&& isset($denvoi)) { $Nombres_joursm = NbJours($row_edits_dno['date_initialisation'], $denvoi);
if($denvoi>=$row_edits_dno['date_initialisation']) {echo "=> ".number_format($Nombres_joursm, 0, ',', ' ')." J";}else echo "??";} ?>&nbsp;&nbsp;
<?php  if(isset($v_date_envoi)) echo "Envoyé le ".date_reg($v_date_envoi,"/")."&nbsp;";
if(isset($v_date_ano)) $dtano=$v_date_ano; elseif(isset($v_date_rejet)) $dtano=$v_date_rejet; else $dtano=date("Y-m-d"); if (isset($v_date_envoi)) { $Nombres_joursm = NbJours($v_date_envoi, $dtano);
if($dtano>=$v_date_envoi) {echo "=> ".number_format($Nombres_joursm, 0, ',', ' ')." J";}else echo "??";} ?></h4>
 <?php if(!isset($v_date_ano) && !isset($v_date_rejet)) {  ?>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<a href="<?php echo $_SERVER['PHP_SELF']."?add=1&dno=$dno&annee=$annee"; ?>" class="pull-right p11" title="Ajout une suivi de la DNO" ><i class="icon-plus"> Ajouter </i></a>
<?php } ?>
<?php } ?>
</div>
<div class="widget-content">
<?php /*if(isset($_GET["send"])){ ?>
<!--<a id="send_mail" onclick="$('.modal-body').html('<h1 align=\'center\'>Envoi du mail en cours&middot;&middot;&middot;<br /></h1>');get_content('phpmailer/mail_dno.php','<?php echo "adresse=".str_replace('\'',"\'",str_replace('<>',"&",$_GET["send"])); ?>|','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Envoyer un mail des DANO" class="pull-right p11 hidden" dir="">&nbsp;</a> -->
<?php } */ ?>

<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >
            <thead>
                <tr>
                  <td><div align="left"><strong>Phase</strong></div></td>
                  <td><div align="left"><strong>Date</strong></div></td>
                  <td><div align="left"><strong>Contenu</strong></div></td>
                  <td><div align="left"><strong>Documents</strong></div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center" width="80" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
                <?php if($totalRows_liste_sdno>0) {$i=0;foreach($row_liste_sdno as $row_liste_sdno){ $id = $row_liste_sdno['id_suivi']; ?>
                <tr>
                  <td><div align="left"><?php echo $row_liste_sdno['phase']; ?></div></td>
                  <td><div align="left"><?php echo date_reg($row_liste_sdno['date_phase'],"/"); ?></div></td>
                  <td><div align="left"><?php
echo do_link("msg_$id","","Contenu de la DANO","Aper&ccedil;u","","./","","get_content('body_mail_dno.php','id=$id&dano=1','modal-body_add',this.title,'iframe');",1,"",$nfile)
//echo $row_liste_sdno['observation']; ?></div>
				  <?php /*if(!empty($row_liste_sdno['observation'])) {?>
<a onclick="get_content('edit_contenu_suivi_dno.php','id=<?php echo $id; ?>&annee=<?php echo $annee; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Contenu du suivi de la DANO <?php echo $row_liste_conv['numero']; ?>" class="thickbox Add"  dir=""> <?php echo ($row_liste_sdno['observation']); ?> </a>  <?php  } else echo "Editer"; */ ?>
</td>
<td align="center"><?php if(!empty($row_liste_sdno['documents'])) {?>
<a onclick="get_content('edit_suivi_dno_documents.php','id=<?php echo $id; ?>&annee=<?php echo $annee; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Documents de la DNO <?php //echo $row_liste_conv['numero']; ?>" class="thickbox Add"  dir="">Afficher <?php  $a = explode('|',$row_liste_sdno['documents']); echo (count($a)>0)?"(".(count($a)-1).")":"0"; ?> </a>  <?php  } else echo "-";  ?>
 </td>
				   <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<td align="center" nowrap="nowrap">
 <?php //if(!isset($v_date_ano) && !isset($v_date_rejet)) {  ?>
<a href="<?php echo $_SERVER['PHP_SELF']."?id=$id&add=1&annee=$annee&dno=$dno"; ?>" title="Modifier suivi" style="margin:0px 5px;"><img src="./images/edit.png" width="20" height="20" alt="Modifier" title="Modifier"></a>
 <?php //} ?>
 <?php if($row_liste_sdno['phase']!="Envoi au bailleur" || intval($totalRows_liste_sdno)==1) {  ?>
<?php
echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id."&annee=$annee&dno=$dno","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette suivi ?');",0,"margin:0px 5px;",$nfile);
?>
</td>
                   <?php } ?>
				   <?php } ?>
	    </tr>
                <?php }  ?>
                <?php } else { ?>
<tr><td align="center" colspan="<?php echo (isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1))?5:4; ?>">Aucun r&eacute;sultat !</td></tr>
                <?php } ?>
              </table>

</div></div>
</div>
<?php } else { ?>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php  if(isset($row_edits_dno['date_initialisation'])) echo "Reçu le ".date_reg($row_edits_dno['date_initialisation'],"/")."&nbsp;";
if(isset($v_date_envoi)) $denvoi=$v_date_envoi; else $denvoi=date("Y-m-d"); if (isset($row_edits_dno['date_initialisation'])&& isset($denvoi)) { $Nombres_joursm = NbJours($row_edits_dno['date_initialisation'], $denvoi);
if($denvoi>=$row_edits_dno['date_initialisation']) {echo "=> ".number_format($Nombres_joursm, 0, ',', ' ')." J";}else echo "??";} ?>&nbsp;&nbsp;
<?php  if(isset($v_date_envoi)) echo "Envoyé: ".date_reg($v_date_envoi,"/")."&nbsp;";
if(isset($v_date_ano)) $dtano=$v_date_ano; elseif(isset($v_date_rejet)) $dtano=$v_date_rejet; else $dtano=date("Y-m-d"); if (isset($v_date_envoi)) { $Nombres_joursm = NbJours($v_date_envoi, $dtano);
if($dtano>=$v_date_envoi) {echo "=> ".number_format($Nombres_joursm, 0, ',', ' ')." J";}else echo "??";} ?></h4>
<?php if(!isset($_GET["msg"])){?>
<a href="<?php echo $_SERVER['PHP_SELF']."?dno=$dno&annee=$annee$mod"; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
<?php } ?>
</div>
<?php if(isset($_GET["msg"]) && $_GET["msg"]==1){//Envoi au bailleur

//$totalRows_ptba = mysql_num_rows($ptba);

?>
<div class="widget-content">
<form action="<?php echo $_SERVER['PHP_SELF']."?dno=$dno&annee=$annee$mod"; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form4" id="form3" novalidate="novalidate">

<input class="form-control required" type="hidden" name="date_phase" id="date_phase" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',$row_edit_suivi_dno['date_phase']))); else echo date("d/m/Y"); ?>" size="32" />
<input class="form-control required" type="hidden" name="phase" id="phase" value="<?php if(isset($_GET["msg2"]) && $_GET["msg2"]==1) echo 'Renvoi au bailleur'; else echo 'Envoi au bailleur'; ?>" size="32" />
<input class="form-control" type="hidden" name="observation" id="observation" value="RAS" size="32" />
<input class="form-control required" type="hidden" name="destinataire" id="destinataire" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo (isset($_GET["msg2"]) && $_GET["msg2"]==1)?$row_edits_dno['destinataire']:$row_edit_suivi_dno['destinataire']; ?>" size="32" />

<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
	<tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="objet" class="col-md-3 control-label">Objet <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" id="objet" name="objet" cols="25" rows="2"><?php if(isset($_GET["msg"]) && !empty($_GET["msg"])) echo $row_edits_dno['objet'];?></textarea>
          </div>
        </div>
      </td>
    </tr>
	<tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="message" class="col-md-12 control-label">Message <span class="required">*</span> <span class="pull-right wysihtml5-toolbar-edit">Edition (Affich./Masquer)</span></label>
          <div class="col-md-12">
            <textarea class="form-control wysiwyg required" id="message" name="message" rows="10" cols="25">
			<?php
            echo "Numéro Identifiant : <b>P11MB9308</b><br />Numéros du Don et du Crédit : <b>Don IDAH8750 et Crédit IDA5297</b><br />Numéro de la Convention d'affectation : <b>AFD N° CCI 1368 01 M</b><br />N°Demande : <b>".(isset($_GET["id"]) && !empty($_GET["id"])?$_GET["id"]:'')."</b><br />";
            if(isset($libelle))
            {
              for($i=1;$i<=$number;$i++){ if(isset($libelle[$i-1])) echo $libelle[$i-1]." : <b>".(isset($niveau_array[$i])?$niveau_array[$i]:"")."</b><br />";}
            }
            echo "Bénéficiaires :  <b>$beneficiaire</b><br />";
			/*elseif(isset($_GET["msg"]) && !empty($_GET["msg"]))
			echo "Type de requête: ".$row_edit_suivi_dno['type_requete']."</br>".$row_edit_suivi_dno['observation_ptba']."</br>".$row_edit_suivi_dno['observation_ppm']."</br>".$row_edit_suivi_dno['message'];*/
            //echo "Composante concernée: <b>$composante</b><br />Sous-composante : <b>$scomposante</b><br />Activité spécifique dans le PTBA : <b>Contrôle des travaux de pistes</b><br />Bénéficiaires :  <b>Communautés rurales, APROMAC, AIPH, AGEROUTE</b><br />";
            echo $row_edit_suivi_dno['message'];
            if(isset($_GET["msg2"]) && $_GET["msg2"]==1)
            echo $row_edit_suivi_dno['observation'];            
            ?>
			</textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="middle">
      <td>
        <div class="form-group">
          <div class="col-md-12">
<div>
<?php $c = "";
if(!empty($row_edit_suivi_dno["documents"]))
{
  $dir = './attachment/dano/';
  $a = explode('|',$row_edit_suivi_dno["documents"]);
  echo "<b>Pi&egrave;ces jointes : <span id='documents_zone'>";
  foreach($a as $b)
  if(!empty($b))
  {
    echo "<a style='' href='./download_file.php?file=$dir$b' title='Télécharger' alt='$b'>$b</a>&nbsp;&nbsp;&nbsp;";
    $c .= $b.'|';
  }
  echo "</span></b>";
  //echo "<div style='clear:both; height:0px;'><hr></div>";
}else  echo "<b>Pi&egrave;ces jointes : <span id='documents_zone'>Aucun</span></b>";
?>
</div>
<input type="hidden" id="documents" name="documents" value="<?php echo $c; ?>" />
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="Envoyer au Bailleur" />
  <input name="MM_insert" type="hidden" value="MM_insert" size="32" alt="">
  <input name="MM_form" id="MM_form" type="hidden" value="form4" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div>
<?php }else{ ?>
<div class="widget-content">
<form action="<?php echo $_SERVER['PHP_SELF']."?dno=$dno&annee=$annee$mod"; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
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
<option value="Envoi au bailleur" <?php if(isset($_GET['id']) && ($row_edit_suivi_dno['phase']=="Envoi au bailleur" || (isset($_GET["msg"]) && $_GET["msg"]==1))) echo 'selected="selected"'; ?>>Envoi au bailleur</option>

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
          <label for="observation" class="col-md-12 control-label">Observations <span class="required">*</span> <span class="pull-right wysihtml5-toolbar-edit">Edition (Affich./Masquer)</span></label>
          <div class="col-md-12">
            <textarea class="form-control wysiwyg required" id="observation" name="observation" rows="7" cols="25"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_suivi_dno['observation'];?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="middle">
      <td>
        <div class="form-group">
<?php //echo do_link("","","Ajout de fichier","Ajout de fichier","","./","pull-right","get_content('new_document.php','dir=$dir&page=".$_SERVER['PHP_SELF']."','modal-body_add',this.title);",1,"",$nfile); ?>
          <div class="col-md-12">
<div>
<?php $c = "";
echo "<b>".do_link("","","Pi&egrave;ces jointes","Pi&egrave;ces jointes","","./","","get_content('list_attachments.php','dir=$dir&doc=documents&page=".$_SERVER['PHP_SELF']."','modal-body_add',this.title,'iframe');",1,"",$nfile);
if(!empty($row_edit_suivi_dno["documents"]))
{
$dir = './attachment/dano/';
$a = explode('|',$row_edit_suivi_dno["documents"]);
echo " : <span id='documents_zone'>".(count($a)-1)." fichier".((count($a)-1>1)?'s':'')."</span></b>";
foreach($a as $b)
if(!empty($b)) { //echo "<a style='' href='./download_file.php?file=$dir$b' title='T&eacute;l&eacute;charger' alt='$b'>$b</a>&nbsp;&nbsp;&nbsp;";
$c .= $b.'|'; }
//echo "<div style='clear:both; height:0px;'><hr></div>";
}else  echo " : <span id='documents_zone'>Aucun</span></b>";
?>
</div>
<input type="hidden" id="documents" name="documents" value="<?php echo $c; ?>" />
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
<a href="<?php echo $_SERVER['PHP_SELF']."?annee=$annee&dno=$dno$mod"; ?>" class="btn pull-right" title="Annuler" >Annuler</a>
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2)) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette suivi ?','<?php echo intval($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div>
<?php } ?> </div>
<?php } ?>
<?php } ?>
<?php include_once 'modal_add.php'; ?>
</body>
</html>