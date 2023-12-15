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
include_once $path.$config->sys_folder . "/database/db_connexion.php";

mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_l_periode = "SELECT * FROM periode_marche  order by id_periode desc";

$l_periode = mysql_query($query_l_periode, $pdar_connexion) or die(mysql_error());

$row_l_periode = mysql_fetch_assoc($l_periode);

$totalRows_l_periode = mysql_num_rows($l_periode);?>

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

<?php if($totalRows_l_periode>0) {?>

<ul id="simple-menu">

 <?php do { ?>

<li><a href="<?php $_SERVER['PHP_SELF']?>?periode=<?php echo $row_l_periode['id_periode']?>" title="<?php echo "Période ".$row_l_periode['debut']." - ".$row_l_periode['fin'];?>" <?php if(isset($periode) && $periode==$row_l_periode['id_periode']) echo "class=\"current\""; ?>><?php echo $row_l_periode['debut']." - ".$row_l_periode['fin'];?></a></li>

<?php } while ($row_l_periode = mysql_fetch_assoc($l_periode)); ?>

</ul>

<?php }?>

</div>



