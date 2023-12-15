<?php
if(!isset($_SESSION)) session_start();
$path = (isset($_GET["path"]))?$_GET["path"]:"./";
include_once $path.'system/configuration.php';
if(!headers_sent())
{
  $config = new Config;
  //header('Content-Type: text/html; charset=ISO-8859-15');
}

include_once $path.$config->sys_folder . "/database/db_connexion.php";

$query_fonction = "SELECT * FROM ".$database_connect_prefix."fonction ";
try{
    $fonction = $pdar_connexion->prepare($query_fonction);
    $fonction->execute();
    $row_fonction = $fonction ->fetchAll();
    $totalRows_fonction = $fonction->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$fonction_array = array();
if($totalRows_fonction>0){ foreach($row_fonction as $row_fonction){
  $fonction_array[$row_fonction["fonction"]]=$row_fonction["description"];
   }}

$total = 0;
//Utilisateur connectÃ©

$query_ad = "SELECT * FROM ".$database_connect_prefix."personnel, ".$database_connect_prefix."connecter where statut=0 and personnel=N and date_deconnexion='0000-00-00 00:00:00' and N<>'".$_SESSION['clp_n']."' GROUP BY N ORDER BY `date_connexion` desc";
try{
    $ad = $pdar_connexion->prepare($query_ad);
    $ad->execute();
    $row_ad = $ad ->fetchAll();
    $total_row_ad = $ad->rowCount(); $total=$total_row_ad;
}catch(Exception $e){ die(mysql_error_show_message($e)); }
//Update connected user
$query_update = "UPDATE ".$database_connect_prefix."connecter SET date_c=now(), date_deconnexion='0000-00-00 00:00:00' where session_id='".session_id()."' and personnel='".$_SESSION['clp_n']."'";
try{
    $update = $pdar_connexion->prepare($query_update);
    $update->execute();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
//Update connected user force deconnexion
$query_update1 = "UPDATE ".$database_connect_prefix."connecter SET date_deconnexion=now() where TIMEDIFF(now(),date_connexion)>='24:00:00'";
try{
    $update1 = $pdar_connexion->prepare($query_update1);
    $update1->execute();
}catch(Exception $e){ /*die(mysql_error_show_message($e));*/ }
?>
<?php if(!isset($_GET["user_zone"])) { ?>
<li class="dropdown" id="user_zone">
<?php } ?>
<a href="javacript:void(0);" class="dropdown-toggle" data-toggle="dropdown" style="padding: 3px 19px;" title="<?php echo ($total==0)?"Aucun utilisateur en ligne":"Il y'a $total connect&eacute; en ce moment"; ?>"> <div class="" id="user_title_num"><div align="center" style="margin:0px; font-weight: bold; color:yellow"><?php echo ($total==0)?"Aucun":$total; ?></div><div align="center" style="margin:0px;">Connect&eacute;</div></div> </a>
<ul class="dropdown-menu extended notification" id="notif_pane">
<li class="title"> <p><b><?php echo ($total==0)?"Aucun":$total; ?></b> utilisateur(s) connect&eacute;</p> </li>
  <li class="dropdown scrollerUser">
    <ul style="list-style: outside none none; margin: 0px; padding: 0px;">
<?php if($total_row_ad>0){ foreach($row_ad as $row_ad){ $avatar_link = (!empty($row_ad["avatar"]))?$row_ad["avatar"]:"";  ?>
<li> <a href="javascript:void(0);"> <span class="photo"><img src="<?php echo (!empty($row_ad["avatar"]) && file_exists($path.$avatar_link))?$avatar_link:"./images/avatar/none.png"; ?>" alt=""/></span> <span class="subject"> <span class="from from1"><?php echo $row_ad["prenom"]." ".$row_ad["nom"]; ?></span> <span class="time" style="margin-top:-15px!important;"><?php echo date_reg($row_ad["date_connexion"],'/',1,1); ?></span> </span> <span class="text text1" title="<?php echo (isset($fonction_array[$row_ad["fonction"]]))?$fonction_array[$row_ad["fonction"]]:$row_ad["fonction"]; ?>"> <?php echo $row_ad["fonction"]; ?></span> <div class="clear h0">&nbsp;</div></a> </li>
<?php } } ?>
</ul></li>
  <li class="dropdown User">
    <ul class="extended" style="list-style: outside none none; margin: 0px; padding: 0px;">
<li class="footer"> <a href="./online_users.php">Voir tous les connect&eacute;s</a> </li>
</ul></li>
  </ul>
<?php if(!isset($_GET["user_zone"])) { ?>
</li>
<?php } ?>
<script type="text/javascript" src="<?php echo $path; ?>plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script type="text/javascript">
//var a = jQuery('#notif_title_valu');
jQuery('#user_title_num').html('<div align="center" style="margin:0px; font-weight: bold; color:yellow;"><?php echo ($total==0)?"Aucun":$total; ?></div><div align="center" style="margin:0px;">Connect&eacute;</div>');
<?php if($total_row_ad>4){ ?>
$(function () {
    $(".scrollerUser").slimscroll({
                            height: "300px",
                            wheelStep: 7
                        });
});
<?php } ?>
</script>
<style>
.scrollerUser ul > li > a, .User ul > li > a {
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: 400;
    line-height: 1.42857;
    color: #333;
    white-space: nowrap;
}
.scrollerUser ul > li > a:hover, .scrollerUser ul > li > a:focus, .User ul > li > a:hover, .User ul > li > a:focus {
    background: #090 none repeat scroll 0% 0%;
    color: #FFF;
    filter: none;
}
.extended li.footer a {
    background-color: #f9f9f9;
    color: #6f6f6f;
    padding: 8px;
}
.from1, .text1 {
  float: right!important;
  width: 190px!important;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space:nowrap;
}
</style>