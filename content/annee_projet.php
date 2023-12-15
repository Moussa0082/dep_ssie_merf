<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
$path = './';
include_once $path.'system/configuration.php';
$config = new Config;
       /*
if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
} */
if(!isset($annee)) $annee=date("Y");
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $a = explode('&',$_SERVER['QUERY_STRING']);
  foreach($a as $b=>$c) if(strchr($c,'annee')!="") unset($a[$b]);
  $a = implode('&',$a);
  $editFormAction .= "?" . htmlentities($a);
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
<?php if(isset($_SESSION["annee_debut_projet"])) {?>
<ul id="simple-menu">
 <?php for($j=$_SESSION["annee_debut_projet"];$j<=$_SESSION["annee_fin_projet"];$j++){ ?>
<li><a href="<?php echo $editFormAction."&annee=$j"; ?>" title="<?php echo "Ann&eacute;e $j"; ?>" <?php if(isset($annee) && $annee==$j) echo "class=\"current\""; ?>><?php echo $j; ?></a></li>
<?php } ?>
</ul>
<?php } else { $annee=date("Y"); ?>
<ul id="simple-menu">
 <li><a href="<?php echo $editFormAction."&annee=$annee"; ?>" title="<?php echo "Ann&eacute;e $annee"; ?>" <?php echo "class=\"current\""; ?>><?php echo $annee; ?></a></li>
</ul>
<?php } ?>
</div>

