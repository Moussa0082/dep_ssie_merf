<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";

$page = $_SERVER['PHP_SELF']; $cmp = "";
if(isset($_GET["cmp"]) && !empty($_GET["cmp"])) $cmp = $_GET["cmp"];
if(isset($_GET["date"])){ $a = str_replace("/","-",$_GET['date']); $b = explode(" ",$a); $b[0] = implode("-",(explode("-",$b[0]))); $date = $b[0];/*implode(' ',$b);*/ }

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $personnel=$_SESSION['clp_id'];
  $a = str_replace("/","-",$_POST['debut']); $b = explode(" ",$a); $date_debut = $b[0]; $b[0] = implode("-",array_reverse(explode("-",$b[0])));
  $debut = implode(' ',$b);
  if(!isset($_POST['all_day']))
  {
    $a = str_replace("/","-",$_POST['fin']); $b = explode(" ",$a); $b[0] = implode("-",array_reverse(explode("-",$b[0])));
    $fin = implode(' ',$b);
  }
  else $fin = "0000-00-00 00:00:00";
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."agenda_perso (titre, description, all_day, debut, fin, couleur, lien, expediteur, `type`, id_personnel) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, 'public', '$personnel')",
                        GetSQLValueString($_POST['titre'], "text"),
                        GetSQLValueString($_POST['description'], "text"),
  					    GetSQLValueString((!isset($_POST['all_day']))?0:1, "int"),
                        GetSQLValueString($debut, "date"),
  					    GetSQLValueString($fin, "date"),
                        GetSQLValueString($_POST['couleur'], "text"),
  					    GetSQLValueString($_POST['lien'], "text"),
                        GetSQLValueString($_POST['expediteur'], "text"));

  	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); } 
    if($Result1) $insertGoTo = $page."?insert=ok";
    else $insertGoTo = $page."&insert=no";
    $insertGoTo .= "&date=$date_debut";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
      $id = $_POST["MM_delete"]; $date_debut = $_POST["date_debut"];
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."agenda_perso WHERE id_agenda=%s",
                           GetSQLValueString($id, "int"));

  	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); } 
        $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      $insertGoTo .= "&date=$date_debut";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if (isset($_POST["MM_archive"]) && !empty($_POST["MM_archive"])) {
      $id = $_POST["MM_archive"]; $date_debut = $_POST["date_debut"];
      $insertSQL = sprintf("UPDATE ".$database_connect_prefix."agenda_perso SET valider=1 WHERE id_agenda=%s",
                           GetSQLValueString($id, "int"));

  	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); } 
        $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
      $insertGoTo .= "&date=$date_debut";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];  $id = ($_POST["MM_update"]);
  $a = str_replace("/","-",$_POST['debut']); $b = explode(" ",$a); $date_debut = $b[0]; $b[0] = implode("-",array_reverse(explode("-",$b[0])));
  $debut = implode(' ',$b);
  if(!isset($_POST['all_day']))
  {
    $a = str_replace("/","-",$_POST['fin']); $b = explode(" ",$a); $b[0] = implode("-",array_reverse(explode("-",$b[0])));
    $fin = implode(' ',$b);
  }
  else $fin = "0000-00-00 00:00:00";
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."agenda_perso SET titre=%s, description=%s, all_day=%s, debut=%s, fin=%s, couleur=%s, lien=%s, expediteur=%s, valider=%s, modifier_par='$personnel', modifier_le='$date' WHERE id_agenda=%s",
                        GetSQLValueString($_POST['titre'], "text"),
                        GetSQLValueString($_POST['description'], "text"),
  					    GetSQLValueString((!isset($_POST['all_day']))?0:1, "int"),
                        GetSQLValueString($debut, "date"),
  					    GetSQLValueString($fin, "date"),
                        GetSQLValueString($_POST['couleur'], "text"),
  					    GetSQLValueString($_POST['lien'], "text"),
                        GetSQLValueString($_POST['expediteur'], "text"),
                        GetSQLValueString($_POST['valider'], "int"),
                        GetSQLValueString($id, "int"));

  	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); } 
    if($Result1) $insertGoTo = $page."?update=ok";
    else $insertGoTo = $page."&update=no";
    $insertGoTo .= "&date=$date_debut";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

$query_liste_responsable = "SELECT * FROM ".$database_connect_prefix."personnel";
             try{
    $liste_responsable = $pdar_connexion->prepare($query_liste_responsable);
    $liste_responsable->execute();
    $row_liste_responsable = $liste_responsable ->fetchAll();
    $totalRows_liste_responsable = $liste_responsable->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
/*$fonction_array = array();
if($totalRows_liste_responsable>0){ do{ $fonction_array[$row_liste_responsable["id_personnel"]] = $row_liste_responsable["fonction"]; }while($row_liste_responsable = mysql_fetch_assoc($liste_responsable));
$rows = mysql_num_rows($liste_responsable);
if($rows > 0) {
mysql_data_seek($liste_responsable, 0);
$row_liste_responsable = mysql_fetch_assoc($liste_responsable);
} } */
$query_liste_event = "SELECT *, DATEDIFF(debut,fin) as date_diff, TIMEDIFF(debut,fin) as time_diff, DATEDIFF(now(),debut) as debut_date_diff, TIMEDIFF(now(),debut) as debut_time_diff, DATEDIFF(now(),fin) as fin_date_diff, TIMEDIFF(now(),fin) as fin_time_diff FROM ".$database_connect_prefix."agenda_perso WHERE `type`='public' ";
             try{
    $liste_event = $pdar_connexion->prepare($query_liste_event);
    $liste_event->execute();
    $row_liste_event = $liste_event ->fetchAll();
    $totalRows_liste_event = $liste_event->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$event_array = array();
if($totalRows_liste_event>0){ foreach($row_liste_event as $row_liste_event){ $debut = explode(" ",$row_liste_event['debut']);
$state = "";
if($row_liste_event['valider']==1) $state = "green";
else
{
  if($row_liste_event['all_day']==1)
  { //Toute la journee
    if($row_liste_event['debut_date_diff']>0){ $state = "red"; }
    elseif($row_liste_event['debut_date_diff']==0){ $state = "orange"; } else $state = "red";
  }
  else
  {
    if($row_liste_event['debut_date_diff']>0)
    {
      if($row_liste_event['fin_date_diff']<=0)
      {
        if(substr($row_liste_event['fin_time_diff'],0,1)=="-") $state = "orange";
        else $state = "red";
      }
      else $state = "red";
    }
    elseif($row_liste_event['debut_date_diff']==0)
    {
      if(substr($row_liste_event['debut_time_diff'],0,1)=="-") $state = "";
      else
      {
        if($row_liste_event['fin_date_diff']<=0)
        {
          if(substr($row_liste_event['fin_time_diff'],0,1)=="-") $state = "orange";
          else $state = "red";
        }
        else $state = "";
      }
    }
  }
}
$description = ((!empty($row_liste_event['expediteur']))?"<b>Auteur : </b>".$row_liste_event['expediteur']."<br />":"")."<b>Description : </b>".$row_liste_event['expediteur']."<br /><b>D&eacute;but :</b> ".date_reg($row_liste_event['debut'],"/",1)."<br /><b>Fin : </b> ".(($row_liste_event['all_day']==1)?"toute la journ&eacute;e":date_reg($row_liste_event['fin'],"/",1));
$event_array[] = "{id:".GetSQLValueString($row_liste_event['id_agenda'], "int").",allDay:".(($row_liste_event['all_day']==1)?'true':'false').",title:".GetSQLValueString($row_liste_event['titre'], "text").",description:".GetSQLValueString($description, "text").",start:".GetSQLValueString((($row_liste_event['all_day']==0)?$row_liste_event['debut']:$debut[0]), "text").(($row_liste_event['all_day']==0)?",end:".GetSQLValueString($row_liste_event['fin'], "text").",":",")."backgroundColor:'".$state."',url:'".(empty($row_liste_event['lien'])?"":$row_liste_event['lien'])."',BorderColor:".($row_liste_event['valider']==1?"'green'":"'red'")."}";
}}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print $config->sitename; ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="keywords" content="<?php print $config->MetaKeys; ?>" />
  <meta name="description" content="<?php print $config->MetaDesc; ?>" />
  <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
  <meta name="author" content="<?php print $config->MetaAuthor; ?>" />
  <!--<meta charset="utf-8">-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->
  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/login.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->
  <!--[if IE 8]><link href="<?php print $config->theme_folder; ?>/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->
  <link href='<?php print $config->theme_folder; ?>/css.css' rel='stylesheet' type='text/css'>
  <link href="plugins/datetimepicker/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>
  <!--<link rel="stylesheet" href="<?php print $config->theme_folder; ?>/table.css" type="text/css" > -->
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/lodash.compat.min.js"></script>
  <!--[if lt IE 9]><script src="<?php print $config->script_folder; ?>/libs/html5shiv.js"></script><![endif]-->
  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>
  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/breakpoints.js"></script>
  <script type="text/javascript" src="plugins/respond/respond.min.js"></script>
  <script type="text/javascript" src="plugins/cookie/jquery.cookie.min.js"></script>
  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.min.js"></script>
  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>
  <!--[if lt IE 9]><script type="text/javascript" src="plugins/flot/excanvas.min.js"></script><![endif]-->
  <!--<script type="text/javascript" src="plugins/sparkline/jquery.sparkline.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.tooltip.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.resize.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.time.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.growraf.min.js"></script>
  <script type="text/javascript" src="plugins/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
  <script type="text/javascript" src="plugins/daterangepicker/moment.min.js"></script>
  <script type="text/javascript" src="plugins/daterangepicker/daterangepicker.js"></script>-->
  <script type="text/javascript" src="plugins/blockui/jquery.blockUI.min.js"></script>
  <script type="text/javascript" src="plugins/pickadate/picker.js"></script>
  <script type="text/javascript" src="plugins/pickadate/picker.date.js"></script>
  <script type="text/javascript" src="plugins/pickadate/picker.time.js"></script>
  <script type="text/javascript" src="plugins/datetimepicker/moment-with-locales.js" charset="ISO-8859-15"></script>
  <script type="text/javascript" src="plugins/datetimepicker/datetimepicker.js"></script>
  <script type="text/javascript" src="plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
  <script type="text/javascript" src="plugins/fullcalendar/fullcalendar.min.js"></script>
  <script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>
  <script type="text/javascript" src="plugins/noty/layouts/top.js"></script>
  <script type="text/javascript" src="plugins/noty/themes/default.js"></script>
  <script type="text/javascript" src="plugins/uniform/jquery.uniform.min.js"></script>
  <script type="text/javascript" src="plugins/select2/select2.min.js"></script>
  <script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>
  <script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/app.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/plugins.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/plugins.form-components.js"></script>
<!--
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/custom.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/pages_calendar.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_filled_blue.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_simple.js"></script>-->
 <script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
 <script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/login.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/myscript.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/ui_general.js"></script>
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>
</head>
<body>
 <header class="header navbar navbar-fixed-top" role="banner">
    <?php include_once("includes/header.php"); ?>
 </header>
<div id="container">
    <div id="sidebar" class="sidebar-fixed">
        <div id="sidebar-content">
            <?php include_once("includes/menu_top.php"); ?>
        </div>
        <div id="divider" class="resizeable"></div>
    </div>

    <div id="content">
        <div class="container">
            <div class="crumbs">
                <?php include_once("includes/sous_menu.php"); ?>
            </div>
        <div class="page-header">
            <div class="p_top_5">
<!-- Site contenu ici -->
<style>
</style>
<script>
"use strict";$(document).ready(function(){var b=new Date();var e=b.getDate();var a=b.getMonth();var f=b.getFullYear();var c={}; var currentLangCode = 'fr'; if($("#calendar").width()<=400){c={left:"title",center:"",right:"prev,next,today"}}else{c={left:"prev,next,today",center:"title",right:"month,agendaWeek,agendaDay"}}$("#calendar").fullCalendar({disableDragging:false,header:c,editable:true,eventLimit: true,
dayClick: function(date, allDay, jsEvent, view) {
  if (!confirm("Voulez-vous ajouter un evenement a cette date ?")) {
            //revertFunc();
        } else
  {  var dd = $.fullCalendar.formatDate(date,"dd/MM/yyyy HH:mm:ss");
$("#msg_00").attr("onclick","get_content('new_event.php','type=public&date="+dd+"','modal-body_add',this.title);");
$("#msg_00").click();
}
            },
eventDrop: function(event, dayDelta, minuteDelta, allDay, revertFunc) {
  if (!confirm("Voulez-vous enregistrer les changements ?")) {
            revertFunc();
        } else
  {
jQuery.ajax({
    url: "./ajax_agenda.php",
    type: "POST",
    //dataType: "json",
    data: ({
      id: event.id,
      day: dayDelta,
      min: minuteDelta,
      allday: allDay,
      //drag: drag
    }),
    success: function(data, textStatus) {
      if (!data)
      {
        revertFunc();
        noty({text:"<strong>Erreur, impossible de mettre a jour !</strong>",type:"error",timeout:2000});
        //alert('Erreur, impossible de mettre a jour!');
        return;
      }
      else
      {
        if(data=="OK")
        noty({text:"<strong>Mis a jour effectuee !</strong>",type:"success",timeout:2000});
        //alert('Mis a jour effectuee !');
        else
        {
          revertFunc();
          noty({text:"<strong>Erreur, impossible de mettre a jour !</strong>",type:"error",timeout:2000});
          //alert('Erreur, impossible de mettre a jour!');
        }
        return;
      }
    },
    error: function() {
      noty({text:"<strong>Erreur, impossible de mettre a jour !</strong>",type:"error",timeout:2000});
      //alert('Erreur, impossible de mettre a jour!');
      revertFunc();
    }
});   }
        },
eventResize: function(event, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view ) {
  if (!confirm("Voulez-vous enregistrer les changements ?")) {
            revertFunc();
        }
  else
  {
    var dd = $.fullCalendar.formatDate(event.end,"yyyy-MM-dd HH:mm:ss");
jQuery.ajax({
    url: "./ajax_agenda.php?fin=1",
    type: "POST",
    //dataType: "json",
    data: ({
      id: event.id,
      date: dd,
      //drag: drag
    }),
    success: function(data, textStatus) {
      if (!data)
      {
        revertFunc();
        noty({text:"<strong>Erreur, impossible de mettre a jour !</strong>",type:"error",timeout:2000});
        //alert('Erreur, impossible de mettre a jour!');
        return;
      }
      else
      {
        if(data=="OK")
        noty({text:"<strong>Mis a jour effectuee !</strong>",type:"success",timeout:2000});
        //alert('Mis a jour effectuee !');
        else
        {
          revertFunc();
          noty({text:"<strong>Erreur, impossible de mettre a jour !</strong>",type:"error",timeout:2000});
          //alert('Erreur, impossible de mettre a jour!');
        }
        return;
      }
    },
    error: function() {
      noty({text:"<strong>Erreur, impossible de mettre a jour !</strong>",type:"error",timeout:2000});
      //alert('Erreur, impossible de mettre a jour!');
      revertFunc();
    }
});
  }
},
eventClick: function(calEvent, jsEvent, view) {
$("#msg_00").attr("onclick","get_content('new_event.php','type=public&id="+calEvent.id+"','modal-body_add',this.title);");
$("#msg_00").click();
},
eventRender: function(event, element) {
          $(element).popover({title: event.title, content: event.description, trigger: 'hover', placement: 'auto top',container: "body", html: true, delay: {show: 100,hide: 100}}).on("show.bs.popover", function(e){ $(this).data("bs.popover").tip().css("width", "600px"); $("[rel=popover]").not(e.target).popover("destroy");$(".popover").remove();  });
        },
events:[<?php echo implode(',',$event_array); ?>],
defaultDate: '<?php if(isset($date)) echo $date; ?>'
});
});
</script>
<div class="widget box ">
 <div class="widget-header"> <h4><span class="pull-left" style="margin-top:5px;"><i class="icon-reorder"></i>Agenda&nbsp;</span>
 <form class="pull-left" name="form38" id="form38" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="width:30%;">
           <select onchange="form38.submit();" name="cmp" id="cmp" class="select2 required" data-placeholder="S&eacute;lectionnez un auteur" >
              <option></option>
              <?php if($totalRows_liste_responsable>0) { foreach($row_liste_responsable as $row_liste_responsable){ ?>
              <option <?php if(isset($_GET["cmp"]) && !empty($_GET["cmp"]) && $_GET["cmp"]==$row_liste_responsable['id_personnel']) {echo "SELECTED";} ?> value="<?php echo $row_liste_responsable['id_personnel'];?>"><?php echo $row_liste_responsable['fonction']." (".$row_liste_responsable['nom']." ".$row_liste_responsable['prenom'].")" //echo $row_liste_responsable['titre']." ".$row_liste_responsable['nom']." ".$row_liste_responsable['prenom']; ?></option>
              <?php } } ?>
            </select>
</form>
 </h4>
<?php //if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
  echo do_link("","","Ajout d'&eacute;v&egrave;nement","Ajout d'&eacute;v&egrave;nement","","./","pull-right p11","get_content('new_event.php','type=public','modal-body_add',this.title);",1,"margin-top:5px;",$nfile);
?>
<?php //} ?>
</div>
<div class="widget-content">
<?php
echo do_link("msg_00","","Evenement","Evenement","","./","hidden","get_content('new_event.php','type=public','modal-body_add',this.title);",1,"",$nfile)
?>
<div id="calendar"></div>
</div>
<!-- Fin Site contenu ici -->
 </div>
            </div>
        </div>

        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>