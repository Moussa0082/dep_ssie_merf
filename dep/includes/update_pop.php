<?php  //session_start();
$path = (isset($_GET["path"]))?$_GET["path"]:"./";
  include_once $path.'system/configuration.php';
  if(!isset($config)) $config = new Config;
//header('Content-Type: text/html; charset=ISO-8859-15');

include_once $path.$config->sys_folder . "/database/db_connexion.php";

$query_ad = "SELECT * FROM ".$database_connect_prefix."mail_dno where statut=0";
try{
    $ad = $pdar_connexion->prepare($query_ad);
    $ad->execute();
    $row_ad = $ad ->fetchAll();
    $total_row_ad = $ad->rowCount(); $total=$total_row_ad;
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>
    <li><a href="./mise_a_jour.php"><i class="icon-refresh"></i> Mise à jour <span class="badge label-success" id="notif_title_num"><?php $total=3; echo ($total==0)?'':$total; ?></span></a></li>
<?php if(!isset($_GET["notif_zone"])) { ?>
<li class="dropdown" id="notif_zone">
<?php } ?>
<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding: 19px;" title="<?php echo ($total==0)?"":"Vous avez $total notification(s)"; ?>"> <i class="icon-comments-o"></i> <span class="badge" id="notif_title_num"><?php echo ($total==0)?'':$total; ?></span> </a>
  <ul class="dropdown-menu extended notification">
    <li class="title" id="notif_title_valu"> <p><?php echo ($total==0)?"Auncune notification":"$total notification(s)"; ?></p> </li>
<?php if($total_row_ad>0){ foreach($row_ad as $row_ad){ ?>
<li class=""> <a href="./courrier_dno.php?show=<?php echo $row_ad["id_mail"]; ?>"> <span class="photo"><!--<img src="./images/mail_warning.png" width="15" height="15" alt="">--></span> <span class="time" style="margin-top:-15px!important;"><?php $d=explode(' ',$row_ad["date"]); echo date_reg($row_ad["date"],'/',1,1); ?></span><span class="subject icon-envelope"> <span class="from"><?php echo $row_ad["objet"]; ?></span> </span> <span class="text"> <?php echo $row_ad["expediteur"]; ?> </span> </a> </li>
<?php } } ?>
  </ul>
<?php if(!isset($_GET["notif_zone"])) { ?>
</li>
<?php } ?>
<script type="text/javascript">
//var a = jQuery('#notif_title_valu');
jQuery('#notif_title_num').html('<?php echo ($total==0)?"":$total; ?>');
jQuery('#notif_title_valu').html('<p><?php echo ($total==0)?"Auncune notification":"$total nouvelle(s) notification(s)"; ?></p>');
</script>