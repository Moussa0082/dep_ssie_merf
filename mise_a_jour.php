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

function run_sql_file($location, $database_pdar_connexion, $pdar_connexion){
    //load file
    $commands = file_get_contents($location);

    //delete comments
    $lines = explode("\n",$commands);
    $commands = '';
    foreach($lines as $line){
        $line = trim($line);
        if( $line && !startsWith($line,'--') ){
            $commands .= $line . "\n";
        }
    }

    //convert to array
    $commands = explode(";", $commands);

    //run commands
    $total = $success = 0;
    foreach($commands as $command){
        if(trim($command)){
            try{
                $Result1 = $pdar_connexion->prepare($command);
                $Result1->execute(); $success++; $total++;
            }catch(Exception $e){ /*die(mysql_error_show_message($e));*/ }
        }
    }

    //return number of successful queries and total number of queries found
    return array(
        "success" => $success,
        "total" => $total
    );
}


// Here's a startsWith function
function startsWith($haystack, $needle){
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

class ExtendedZip extends ZipArchive {

    // Member function to add a whole file system subtree to the archive
    public function addTree($dirname, $localname = '') {
        if ($localname)
            $this->addEmptyDir($localname);
        $this->_addTree($dirname, $localname);
    }

    // Internal function, to recurse
    protected function _addTree($dirname, $localname) {
        $dir = opendir($dirname);
        while ($filename = readdir($dir)) {
            // Discard . and ..
            if ($filename == '.' || $filename == '..')
                continue;

            // Proceed according to type
            $path = $dirname . '/' . $filename;
            $localpath = $localname ? ($localname . '/' . $filename) : $filename;
            if (is_dir($path)) {
                // Directory: add & recurse
                $this->addEmptyDir($localpath);
                $this->_addTree($path, $localpath);
            }
            else if (is_file($path)) {
                // File: just add
                $this->addFile($path/*, iconv("ISO-8859-1", "CP437", $localpath)*/);
            }
        }
        closedir($dir);
    }

    // Helper function
    public static function zipTree($dirname, $zipFilename, $flags = 0, $localname = '') {
        $zip = new self();
        $zip->open($zipFilename, $flags);
        $zip->addTree($dirname, $localname);
        $zip->close();
    }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{ //Software update
    $date=date("Y-m-d");
    $insertGoTo = $_SERVER['PHP_SELF'];

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert") && isset($_POST['archive']) && is_array($_POST['archive'])) {
      include_once "./phpMyDumper.php";
      ExtendedZip::zipTree("./", './update/software/bck/sys--backup-'.time().'-'.(md5("fichier")).'.zip', ZipArchive::CREATE);

      $yyyymmdd_mmss  = date("Y_m_d_H_i");
      $path      = './update/software/bck/';
      $filename  = $path."RUCHE_SAUVEGARDE_".$yyyymmdd_mmss.".zip"; // Filename of dump, default: "dump.php"
      $compress  = true; // Dump as a compressed file, default: false
      $dump = new phpMyDumper($database_pdar_connexion,$pdar_connexion,$filename,$compress,1);
      $dump->dropTable = true; // Dump DROP TABLE statement, default: true
      $dump->createTable = true; // Dump CREATE TABLE statement, default: true
      $dump->tableData = true; // Dump table data, default: true
      $dump->expInsert = false; // Dump expanded INSERT statements, default: false
      $dump->hexValue = false; // Dump strings as hex values, default: false
      $dump->phpMyAdmin = true; // Formats dump file like phpMyAdmin export, default: true
      //$dump->utf8 = false; // Uses UTF-8 connection with MySQL server, default: true
      $dump->autoincrement = false; // Dump AUTO_INCREMENT statement using older MySQL servers, default: false
      $dump->doDump();

      $zip = new ZipArchive;
      $res = $zip->open('./update/software/'.$_POST['archive'][0]);
      if ($res === TRUE) {
        //Preparation du dossier sql
        $projectsListIgnore = array ('.','..');
        $handle=opendir("./sql/");
        $t = 0;
        while ($file = readdir($handle))
        {
          $ext = substr(strrchr($file, '.'),1);
          $filename = "./sql/".$file;
          if (!in_array($file,$projectsListIgnore) && file_exists($filename))
          {
            unlink($filename);
          }
        }
        closedir($handle);
        //extration
        $zip->extractTo('./');
        $zip->close();
        //traitement aditionnelle des sql
        $handle=opendir("./sql/");
        $t = 0;
        while ($file = readdir($handle))
        {
          $ext = substr(strrchr($file, '.'),1);
          $filename = "./sql/".$file;
          if (!in_array($file,$projectsListIgnore) && $ext=="sql")
          {
            run_sql_file($filename, $database_pdar_connexion, $pdar_connexion);
          }
        }
        closedir($handle);

        $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."software_update (package, id_personnel, date_enregistrement) VALUES (%s, %s, '$date')",
                             GetSQLValueString($_POST['archive'][0], "text"),
                             GetSQLValueString($_SESSION["clp_id"], "int"));
        try{
            $Result1 = $pdar_connexion->prepare($insertSQL);
            $Result1->execute();
        }catch(Exception $e){ die(mysql_error_show_message($e)); }

        if ($Result1) $insertGoTo .= "?extra=ok&insert=ok"; else $insertGoTo .= "?extra=ok&insert=no";

      } else {
        $insertGoTo .= "?extra=no";
      }
      header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{ //Data update
    $date=date("Y-m-d");
    $insertGoTo = $_SERVER['PHP_SELF'];  $dir = "./update/";

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert") && isset($_POST['archive']) && is_array($_POST['archive'])) {
      include_once "./phpMyDumper.php";

      foreach($_POST['archive'] as $archive)
      {
        $ext = substr(strrchr($archive, '.'),1);
        switch($ext)
        {
          case "zip": //fichiers
  if(file_exists($dir.'data/'.$archive))
  {
    //Backup
    ExtendedZip::zipTree("./attachment/", $dir.'software/bck/attachment-backup-'.time().'-'.(md5("fichier")).'.zip', ZipArchive::CREATE);
    $zip = new ZipArchive;
    $res = $zip->open($dir.'data/'.$archive);
    if ($res === TRUE) {
      //extration
      $zip->extractTo('./');
      $zip->close();
      unlink($dir.'data/'.$archive);
    }
  }
          break;

          case "gzip": //sql
  if(file_exists($dir.'data/'.$archive))
  {
    //Backup
    $yyyymmdd_mmss  = date("Y_m_d_H_i");
    $path      = './update/software/bck/';
    $filename  = $path."RUCHE_SAUVEGARDE_".$yyyymmdd_mmss.".zip"; // Filename of dump, default: "dump.php"
    $compress  = true; // Dump as a compressed file, default: false
    $dump = new phpMyDumper($database_pdar_connexion,$pdar_connexion,$filename,$compress,1);
    $dump->dropTable = true; // Dump DROP TABLE statement, default: true
    $dump->createTable = true; // Dump CREATE TABLE statement, default: true
    $dump->tableData = true; // Dump table data, default: true
    $dump->expInsert = false; // Dump expanded INSERT statements, default: false
    $dump->hexValue = false; // Dump strings as hex values, default: false
    $dump->phpMyAdmin = true; // Formats dump file like phpMyAdmin export, default: true
    //$dump->utf8 = false; // Uses UTF-8 connection with MySQL server, default: true
    $dump->autoincrement = false; // Dump AUTO_INCREMENT statement using older MySQL servers, default: false
    $dump->doDump();

    $lines = gzfile($dir.'data/'.$archive);
    foreach ($lines as $line) {
        //echo $line;
      //convert to array
      $commands = explode(";", $line);

      //run commands
      $total = $success = 0;
      foreach($commands as $command){
          if(trim($command)){
              try{
                    $Result1 = $pdar_connexion->prepare($command);
                    $Result1->execute(); $success++; $total++;
                }catch(Exception $e){ /*die(mysql_error_show_message($e));*/ }
          }
      }
    }
    if($total>0)
    {
      unlink($dir.'data/'.$archive);
      $a = file_get_contents("http://ruche-psac.org/sse_".substr($database_connect_prefix,0,strlen($database_connect_prefix)-1)."/update_data.php?sup=1");
    }
  }
          break;

          default:
          break;

        }
      }
      header(sprintf("Location: %s", $insertGoTo."?update=ok&"));  exit();
  }
}

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
 <script>
function reload_update()
{
  if($("#zone_update_sys").html()=='<h2 align="center">Connexion en cours...</h2>')
   get_content_distant("http://ruche-psac.org/sse_<?php echo substr($database_connect_prefix,0,strlen($database_connect_prefix)-1); ?>/update_data.php","","zone_update_sys","","","",1);
   else clearInterval(timer);
}   var timer;
 $(document).ready(function(){App.init();Plugins.init();FormComponents.init();
 if($("#zone_update_sys").html()=='<h2 align="center">Connexion en cours...</h2>'){ reload_update();
   timer = setInterval(reload_update,10000);  }
});
 </script>
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
<?php include_once 'modal_add.php'; ?>
<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Mise &agrave; jours du syst&egrave;me</h4>
</div>
<div class="widget-content" id="zone_update_sys"><?php echo '<h2 align="center">Connexion en cours...</h2>'; ?></div> </div>

<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i> Mise &agrave; jour de l'application RUCHE</h4> <div class="toolbar no-padding"> <div class="btn-group"> <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span> </div> </div> </div>

 <div class="widget-content" style="display: block;">
<?php
//Verification
$query_auth = "SELECT * FROM ".$database_connect_prefix."software_update ORDER BY date_enregistrement desc limit 1";
try{
    $auth = $pdar_connexion->prepare($query_auth);
    $auth->execute();
    $row_auth = $auth ->fetch();
    $totalRows_auth = $auth->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_auth>0)
echo "Date de derni&egrave;re mise &agrave; jour (package : '<b>".$row_auth["package"]."</b>') : ".implode('/',array_reverse(explode('-',$row_auth["date_enregistrement"])));
else echo "Aucune mise &agrave; jour install&eacute;e !";
?>
<div class="clear">&nbsp;</div>
<div style="width: 50%; margin: auto; padding: auto;">
  <input title="Mise &agrave; jour de l'application RUCHE" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" data-backdrop="static" data-keyboard="false" name="software_update" type="button" class="btn btn-success btn-block" value="V&eacute;rifier" onclick="get_content('software_update.php','','modal-body_add',this.title);" />
</div>

 </div>

</div>

<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>