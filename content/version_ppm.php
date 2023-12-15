<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
$path = './';
include_once $path.'system/configuration.php';
$config = new Config;

$query_liste_version = "SELECT * FROM ".$database_connect_prefix."version_plan_marche ORDER BY date_version asc";
           try{
    $liste_version = $pdar_connexion->prepare($query_liste_version);
    $liste_version->execute();
    $row_liste_version = $liste_version ->fetchAll();
    $totalRows_liste_version = $liste_version->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

       /*
if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
} */
if(!isset($version)) $version=0;
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $a = explode('&',$_SERVER['QUERY_STRING']);
  foreach($a as $b=>$c) if(strchr($c,'version')!="") unset($a[$b]);
  $a = implode('&',$a);
 // $editFormAction .= "?" . htmlentities($a);
}
?>
<style type="text/css">
<!--
#demo-container{padding:2px 15px 2 15px;/*background:#67A897;*/}
ul#simple-menu{list-style-type:none;width:100%;position:relative;height:20px;font-family:"Trebuchet MS",Arial,sans-serif;font-size:13px;font-weight:bold;margin:0;padding:11px 0 0 0;}
ul#simple-menu li{display:block;float:left;margin:0 0 0 4px;height:20px;}
ul#simple-menu li.left{margin:0;}
ul#simple-menu li a{display:block;float:left;color:#fff;background:#4A6867;line-height:20px;text-decoration:none;padding:0 17px 0 18px;height:20px;}
ul#simple-menu li a.right{padding-right:19px;}
ul#simple-menu li a:hover{background:#2E4560;}
ul#simple-menu li a.current{color:#FFF;background:#ff0000;}
ul#simple-menu li a.current:hover{color:#FFF;background:#ff0000;}
-->
</style>

<div id="demo-container">

<?php if($totalRows_liste_version>0) {?>
<ul id="simple-menu">
 <?php  foreach($row_liste_version as $row_liste_version){ $j=$row_liste_version['id_version']; ?>
<li><a href="<?php echo $editFormAction."&version=$j"; ?>" title="<?php echo "Version du ".implode('/',array_reverse(explode('-',$row_liste_version['date_version'])))?>" <?php if(isset($version) && $version==$row_liste_version['id_version']) echo "class=\"current\""; ?>><?php if(isset($row_liste_version['date_version'])) echo $row_liste_version['numero_version'];?></a></li>
<?php }  ?>
</ul>
<?php } ?>
</div>

