<div class="overlay hide" data-pages="mot_dg_box">
<div class="overlay-content has-results m-t-20">
<div class="container-fluid">
<a href="#" class="close-icon-light overlay-close text-black fs-16"><i class="icon-close"></i></a>
</div>
<div class="container-fluid" id="modal-mot_dg_box"></div>
</div>
</div>
<div class="sidebar-footer fixed-footer">
<?php $colors=explode(",","#7cb5ec,#434348,#2B9915,#f7a35c,#8085e9,#f15c80,#e4d354,#2b908f,#f45b5b,#91e8e1"); ini_set("display_errors",0);
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_partenaire = "SELECT * FROM partenaire order by nom_partenaire";
$liste_partenaire  = mysql_query($query_liste_partenaire , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_partenaire = mysql_fetch_assoc($liste_partenaire);
$totalRows_liste_partenaire  = mysql_num_rows($liste_partenaire);
$liste_partenaire_array=$liste_partenaire_description_array=array();
if($totalRows_liste_partenaire>0) {
do {
$liste_partenaire_array[]=$row_liste_partenaire["nom_partenaire"];
$liste_partenaire_description_array[]=strip_tags($row_liste_partenaire["description"]).(!empty($row_liste_partenaire["description"])?"<br/>":"").(!empty($row_liste_partenaire["adresse_partenaire"])?"<b>Adresse : </b>".$row_liste_partenaire["adresse_partenaire"]."<br/>":"").(!empty($row_liste_partenaire["contact_partenaire"])?"<b>Contact : </b>".$row_liste_partenaire["contact_partenaire"]."<br/>":"").(!empty($row_liste_partenaire["site_web"])?"<b>Site web : </b>".$row_liste_partenaire["site_web"]."<br/>":"").(!empty($row_liste_partenaire["email_partenaire"])?"<b>Email : </b>".$row_liste_partenaire["email_partenaire"]:""); } while ($row_liste_partenaire = mysql_fetch_assoc($liste_partenaire)); }*/
?>
<!--<marquee align="middle" width="100%" onmouseover="this.stop();" onmouseout="this.start();" behavior="scroll" direction="left"><ul style="width: 100%; height: 100%;list-style: none;margin: 0 auto;">
<?php //$i=0; foreach($liste_partenaire_array as $id=>$partenaire){ ?>
<li style="display: inline-block;padding: 0 30px;border-right: 1px solid rgba(0,0,0,0.2);font-size: 20px;font-weight: bold; color: <?php /*$a = rand(0,count($colors)-1); *///echo $colors[$i]; ?> " title="<?php //echo $partenaire; ?>" class="partenaire_pop" data-container="body" data-trigger="hover" data-html="true" data-placement="top" data-content="<?php //echo str_replace('"',"'",$liste_partenaire_description_array[$id]); ?>" data-original-title="<?php //echo $partenaire; ?>"><?php //echo $partenaire; ?></li>
<?php //$i++; if($i==count($colors)-1) $i=0; } ?>
</ul></marquee>-->
</div>
<?php
  $config = new Config;
  include_once $config->sys_folder.'/database/db_connexion.php';
?>
<style>.popover-title{font-weight: bold;}</style>
<script>
$(function () {$(".partenaire_pop").popover();});
</script>
<?php if(isset($_GET['extra'])){ ?>
<script type="text/javascript">
afficher_msg('extra','<?php echo $_GET['extra']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['insert'])){ ?>
<script type="text/javascript">
afficher_msg('insert','<?php echo $_GET['insert']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['del'])){ ?>
<script type="text/javascript">
afficher_msg('del','<?php echo $_GET['del']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['edit'])){ ?>
<script type="text/javascript">
afficher_msg('edit','<?php echo $_GET['edit']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['update'])){ ?>
<script type="text/javascript">
afficher_msg('update','<?php echo $_GET['update']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['import'])){ ?>
<script type="text/javascript">
afficher_msg('import','<?php echo $_GET['import']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['statut'])){ ?>
<script type="text/javascript">
afficher_msg('statut','<?php echo $_GET['statut']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['doublon'])){ ?>
<script type="text/javascript">
afficher_msg('doublon','<?php echo $_GET['doublon']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['auth'])){ ?>
<script type="text/javascript">
afficher_msg('auth','<?php echo $_GET['auth']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['pass'])){ ?>
<script type="text/javascript">
afficher_msg('pass','<?php echo $_GET['pass']; ?>');
</script>
<?php }?>

<?php if(isset($_GET['mail'])){ ?>
<script type="text/javascript">
afficher_msg('mail','<?php echo $_GET['mail']; ?>');
</script>
<?php }?>