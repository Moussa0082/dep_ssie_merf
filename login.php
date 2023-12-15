<?php
   ///////////////////////////////////////////////
  /*                 SSE                       */
 /*	Conception & Développement: BAMASOFT */         
///////////////////////////////////////////////

  //session_start();
  include_once 'system/configuration.php';
  if(!isset($config)) $config = new Config;
  include_once $config->sys_folder."/database/db_connexion.php";
  /*
  include_once $config->sys_folder."/database/credential.php";
  include_once $config->sys_folder."/database/essentiel.php";
  */

//Textes d'accueil
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_texte_accueil = "SELECT * FROM ".$database_connect_prefix."texte_accueil ORDER BY id_texte_accueil asc";
$liste_texte_accueil  = mysql_query_ruche($query_liste_texte_accueil , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_texte_accueil = mysql_fetch_assoc($liste_texte_accueil);
$totalRows_liste_texte_accueil  = mysql_num_rows($liste_texte_accueil);

//
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_collecte = sprintf("SELECT id_ref_ind ,intitule_ref_ind, code_ref_ind, unite, seuil_min, seuil_max,domaine, type_representation, echelle, responsable, count(id_periode) as  periode, type_ref_ind FROM referentiel_indicateur r, periode_indicateur p WHERE p.ref_indicateur=r.code_ref_ind and `type_representation`!='tt' group by intitule_ref_ind, code_ref_ind, unite, seuil_min, seuil_max,domaine, type_representation, echelle, responsable");
$liste_collecte = mysql_query_ruche($query_liste_collecte, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_collecte = mysql_fetch_assoc($liste_collecte);
$totalRows_liste_collecte = mysql_num_rows($liste_collecte);   */
?>
<!--<script src="<?php print $config->script_folder; ?>/new_highcharts.js"></script>
<script>
$(function () {
    $('.flexslider').flexslider({
    animation: "slide"
    });
    $(".scroller_special").each(function(){$(this).slimScroll({size:"7px",opacity:"0.2",position:"right",height:"320px",alwaysVisible:"1"==$(this).attr("data-always-visible")?!0:!1,railVisible:"1"==$(this).attr("data-rail-visible")?!0:!1,disableFadeOut:!0})})
    //$('.flexslider .flex-direction-nav').css({visibility:'hidden'});
    $('[data-pages="mot_dg_box"]').mot_dg_box({mot_dg_boxField:'#overlay-mot_dg_box',closeButton:'.overlay-close'});
});
</script>-->
<style>/*
.flex-direction-nav {visibility: hidden;}.slides li div .widget-content {max-height: 280px;overflow: auto;}.flex-control-nav {bottom: -58px;}.feeds li {background-color: #FFF;}.title_1 {display: block;font-size: 11px;padding: 3px;}.title_2 {height: 22px;text-overflow: ellipsis;white-space: nowrap;overflow-x: hidden;}.value {font-size: 15px;font-weight: 600;overflow: hidden;}#accueil .col-md-3, #accueil .col-md-6, #accueil .col-md-12 {padding-left: 5px!important;padding-right: 5px!important;}#mot_dg> h1:first-of-type, #mot_dg> h2:first-of-type, #mot_dg> h3:first-of-type {margin-top: 0;}.page-header,.p_top_5, #content, body{background-clip: initial;background-image: url(./images/fond.png)!important;background-attachment: fixed;background-size: cover;background-repeat: repeat;}.login .box {border-bottom: 5px solid #090;}.crumbs{display:none;}.special_div{padding-left: 5px!important;padding-right: 5px!important;}.m_auto{margin:0 auto!important;width:fit-content;}.special_div{width: 33%!important;float: left;}@media(max-width:767px){.box1,.box3{display: none!important;}.box2{display: !important;}.special_div{width: 100%!important;float: left;}}@media(min-width:768px) and (max-width:979px){.box3{display: none!important;}.box1,.box2{display: !important;}.special_div{width: 49%!important;float: left;}} */
</style>

<!--<div class="logo m_auto" style="padding: 15px 10px; background-color: #FF0;" align="center"><?php //print $config->sitetititle; ?> - <strong><?php //print $config->siteshortname; ?></strong> </div>
<div class="special_div box1" style="padding-top: 10px;">
<div class="widget box ">
<div class="widget-content" style="padding: 0px;">
<div class="flexslider hide_befor_load" style="height: 261px; padding: 0px; border: 0px;">
  <ul class="slides">
  <?php /*if($totalRows_liste_collecte>0){ $tab_par_ind_array = array();  do {
    $unit=$datep=$periodes= ""; $valind=0; $query_liste_val_ref=""; $code_indicateur=$row_liste_collecte['code_ref_ind']; $id_indicateur=$row_liste_collecte['id_ref_ind'];
  if(isset($row_liste_collecte['unite']) && $row_liste_collecte['unite']!="Nbre") $unit=" ".$row_liste_collecte['unite'];

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_composante = sprintf("SELECT * FROM periode_indicateur where ref_indicateur=".GetSQLValueString($code_indicateur, "text"))." order by date_validation desc";
$liste_composante  = mysql_query_ruche($query_liste_composante , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_composante  = mysql_fetch_assoc($liste_composante);
$totalRows_liste_composante  = mysql_num_rows($liste_composante);

$last_id=$row_liste_composante["id_periode"];
$last_p=$row_liste_composante["periode_collecte"];
$last_val=$row_liste_composante["valeur_periode"];
$last_source=$row_liste_composante["source_donnees"];
$precede_val=$precede_p=$precede_id="";

  if($totalRows_liste_composante>0){ do{
  if($precede_id=="" && $row_liste_composante["id_periode"]!=$last_id) $precede_id=$row_liste_composante["id_periode"];
  if($precede_p=="" && $row_liste_composante["id_periode"]!=$last_id) $precede_p=$row_liste_composante["periode_collecte"];
  if($precede_val=="" && $row_liste_composante["id_periode"]!=$last_id) $precede_val=$row_liste_composante["valeur_periode"];
}while($row_liste_composante = mysql_fetch_assoc($liste_composante));}


$echellec=$row_liste_collecte["echelle"]; $typer=$row_liste_collecte["type_representation"]; $codeind=$row_liste_collecte["code_ref_ind"];
if($echellec=="01" && $typer=="vr" && $last_id!="")
{
mysql_select_db($database_pdar_connexion, $pdar_connexion);
if($precede_id!="") $query_liste_classe = "SELECT  SUM(if(periode=$last_id, valeur_periode,0)) as last_val,   SUM(if(periode=$precede_id, valeur_periode,0)) as precede_val FROM resultat_nationale";
else $query_liste_classe = "SELECT  SUM(if(periode=$last_id, valeur_periode,0)) as last_val,   '' as precede_val FROM resultat_nationale";
$liste_classe  = mysql_query_ruche($query_liste_classe , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_classe = mysql_fetch_assoc($liste_classe);
$totalRows_liste_classe  = mysql_num_rows($liste_classe);
$last_val = $row_liste_classe['last_val'];
$precede_val = $row_liste_classe['precede_val'];
}
elseif($echellec!="01" && $last_id!="")
{
mysql_select_db($database_pdar_connexion, $pdar_connexion);
if($precede_id!="") $query_liste_classe_r = "SELECT  SUM(if(periode=$last_id, valeur_periode,0)) as last_val,   SUM(if(periode=$precede_id, valeur_periode,0)) as precede_val FROM resultat_region";
else $query_liste_classe_r = "SELECT  SUM(if(periode=$last_id, valeur_periode,0)) as last_val,   '' as precede_val FROM resultat_region";
$liste_classe_r  = mysql_query_ruche($query_liste_classe_r , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_classe_r = mysql_fetch_assoc($liste_classe_r);
$totalRows_liste_classe_r  = mysql_num_rows($liste_classe_r);
$last_val = $row_liste_classe_r['last_val'];
$precede_val = $row_liste_classe_r['precede_val'];
}

if($last_val!="") $tab_par_ind_array[$id_indicateur]=number_format($last_val, (strrchr($last_val,'.')?3:0), ',', ' ')."".$unit." en ".$last_p;
if($row_liste_collecte['type_ref_ind']==1 && $last_val!=0){ ?>
<li>
      <div class="title_1" style="padding: 10px;" > <div style="height: 16px;text-overflow: ellipsis;white-space: nowrap;overflow-x: hidden;"><?php  if(isset($row_liste_collecte['intitule_ref_ind'])) echo "<b>".$row_liste_collecte['code_ref_ind']."</b> : ".$row_liste_collecte['intitule_ref_ind']; ?> <?php  //echo " val actu=".$last_val." val prece=".$precede_val; ?> </div><div style="height: 16px;text-overflow: ellipsis;white-space: nowrap;overflow-x: hidden;"><?php echo "Periode concern&eacute;e : "; ?><b><?php  if(isset($last_p)) echo $last_p; ?></b></div><div style="height: 16px;text-overflow: ellipsis;white-space: nowrap;overflow-x: hidden;"><?php echo "Source de donn&eacute;es : "; ?><b><u><?php  echo $last_source; ?></u></b></div></div>
      <div class="value" style="float: right; margin-top: -40px; text-align:center; padding: 0 10px;" ><a style="display: block;" href="javascript:void(0);" title="Plus de d&eacute;tails"><?php echo "Chiffre cl&eacute; : "; ?><?php if(isset($last_val)) echo number_format($last_val, (strrchr($last_val,'.')?3:0), ',', ' '); ?> <?php  if(isset($row_liste_collecte['unite']) && $row_liste_collecte['unite']!="Nbre") echo $row_liste_collecte['unite']; ?></br></a><?php   if(isset($precede_val) && $precede_val!="" && isset($last_val)) { if($last_val!=0) $tauxevol=number_format(abs(100*($last_val-$precede_val)/$precede_val), 2, ',', ' '); ; if($precede_val<=$last_val) echo "<i style=\"color:#00CC33\">+".$tauxevol."%</i>"; else echo "<i style=\"color:#FF0000\">-".$tauxevol."%</i>";} ?></div>
<?php
$row_liste_patient['unite']=$row_liste_collecte["unite"];
if($echellec=="01" && $typer=="va") include("graph_indicateur_nn_va.php");
elseif($echellec=="01" && $typer=="vr") include("graph_indicateur_nn_vr.php");
elseif($echellec=="02" && ($typer=="va")) include("graph_indicateur_nr_va.php");
elseif($echellec=="02" && ($typer=="vr")) include("graph_indicateur_nr_vr.php");
elseif($echellec!="01" && $echellec!="02" && ($typer=="va")) include("graph_indicateur_nz_va.php");
elseif($echellec!="01" && $echellec!="02" && ($typer=="vr")) include("graph_indicateur_nz_vr.php");
elseif($echellec!="01" && ($typer=="tt")) include("tableau_nr_tt.php");
?>
</li>
<?php } }while($row_liste_collecte = mysql_fetch_assoc($liste_collecte));}*/  ?>
</ul>
  </div>
</div>

</div>
</div>-->
<div class="login special_div box2">
<div class="box">
  <div class="content"> <form name="login_form" class="form-vertical login-form" action="./connexion.php" method="post"> <h3 class="form-title" style="margin-bottom: 2px;">Se connecter &agrave; votre compte</h3><h6 style="margin-top: 2px; color: red;" align="center"><a style="color: red; text-align: center; font-weight: bold;" href="mailto:aedd@environnement.gov.ml?subject=Solliciter la cr&eacute;ation d'un compte&body=Merci d'&eacute;crire votre demande ici" title="R&eacute;diger votre demande adress&eacute;e &agrave; l'AEDD par mail">Solliciter la cr&eacute;ation d'un compte</a></h6> <div class="alert fade in alert-danger" style="display: none;"> <i class="icon-remove close" data-dismiss="alert"></i> Entrez un identifiant et un mot de passe. </div> <div class="form-group"> <div class="input-icon"> <i class="icon-user"></i> <input type="text" name="identifiant" is="identifiant" class="form-control" placeholder="Identifiant" <?php echo (isset($_GET["identifiant"]))?"":'autofocus="autofocus"'; ?> data-rule-required="true" data-msg-required="Veuillez entrer votre nom d'utilisateur." value="<?php echo (isset($_GET["identifiant"]))?$_GET["identifiant"]:""; ?>"/> </div> </div> <div class="form-group"> <div class="input-icon"> <i class="icon-lock"></i> <input type="password" name="password" id="password" class="form-control" placeholder="Mot de passe" data-rule-required="true" data-msg-required="Veuillez entrer votre mot de passe." <?php echo (isset($_GET["identifiant"]))?'autofocus="autofocus"':""; ?>/> </div> </div><div class="form-actions"> <label class="checkbox pull-left"><input type="checkbox" class="uniform" name="remember" checked="checked"> Rester connect&eacute;</label> <button type="submit" class="submit btn btn-success pull-right"> Se connecter <i class="icon-angle-right"></i> </button> </div> <h6 style="margin-top: 2px; color: red;" align="center"><a style="color: red; text-align: center; font-weight: bold;" href="mailto:aedd@environnement.gov.ml?subject=Suggestions&body=Merci d'&eacute;crire vos suggestions ici" title="R&eacute;diger votre commentaire adress&eacute;e &agrave; l'AEDD par mail">Envoyer un commentaire &agrave; l'administrateur</a></h6> </form></div>
  <div class="inner-box"> <div class="content"> <i class="icon-remove close hide-default"></i> <a href="#" class="forgot-password-link">Mot de passe oubli&eacute;?</a> <form class="form-vertical forgot-password-form hide-default" action="./resetpassword.php" method="post"> <div class="form-group"> <div class="input-icon"> <i class="icon-envelope"></i> <input type="text" name="email" id="email" class="form-control" placeholder="Entrez votre addresse mail" data-rule-required="true" data-rule-email="true" data-msg-required="Veuillez entrer une adresse mail valide."/> </div> </div> <button type="submit" class="submit btn btn-default btn-block"> R&eacute;initialiser le mot de passe </button> </form> <div class="forgot-password-done hide-default"> <i class="icon-ok success-icon"></i> <span>Envoi du mail en cours...</span> </div> </div> </div>
</div>
</div>
<!--<div class="special_div box3" style="padding-top: 10px;">
<?php /*if($totalRows_liste_texte_accueil>0){ ?>
<div class="widget ">
<div class="widget-content scroller_special" style="padding: 0px;" >
<div class="panel-group" id="msg_box">
<?php $i=1; do{ $id=$row_liste_texte_accueil["id_texte_accueil"]; ?>
<div class="panel panel-default"> <div class="panel-heading"> <h3 class="panel-title"> <a class="accordion-toggle" style="display: block;" data-toggle="collapse" data-parent="#msg_box" href="#msg_box_content<?php echo $i; ?>"> <?php echo $row_liste_texte_accueil["titre"]; ?> </a> </h3> </div> <div id="msg_box_content<?php echo $i; ?>" class="panel-collapse collapse <?php echo $i==1?"in":""; ?>"> <div class="panel-body"> <?php if(isset($row_liste_texte_accueil["intro"]) && file_exists('./images/bailleur/img_'.$id.'.jpg')) { ?><img hspace="5" src="<?php echo './images/bailleur/img_'.$id.'.jpg'; ?>" align='left' width='100' height='100' alt='preview'><?php } ?><?php echo trimAll_simple($row_liste_texte_accueil["intro"],200).'...<div class="clear h0">&nbsp;</div><div style="text-align: right;"><a onclick="get_content(\'texte_accueil_content.php\',\'id='.$id.'\',\'modal-mot_dg_box\',this.title,\'\');" href="javascript:void(0);" title="Afficher l\'article complet" data-toggle="mot_dg_box">Lire la suite...</a></div>'; //./texte_accueil.php?id='.$id.' ?> <div class="clear h0">&nbsp;</div> </div> </div> </div>
<?php $i++; }while($row_liste_texte_accueil = mysql_fetch_assoc($liste_texte_accueil)); ?>
</div></div></div>
<div class="overlay hide" data-pages="mot_dg_box">
<div class="overlay-content has-results m-t-20">
<div class="container-fluid">
<a href="#" class="close-icon-light overlay-close text-black fs-16"><i class="icon-close"></i></a>
</div>
<div class="container-fluid" id="modal-mot_dg_box"></div>
</div>
</div>
<?php }*/ ?>
</div>-->
<!--<div class="footer clear m_auto" algin="center" > <a href="javascript:void(0);" class=""><h1 style="text-align:  center; color: black;"><?php //print $config->sitename; ?></h1></a> </div> -->
<div class="overlay hide" data-pages="mot_dg_box">
<div class="overlay-content has-results m-t-20">
<div class="container-fluid">
<a href="#" class="close-icon-light overlay-close text-black fs-16"><i class="icon-close"></i></a>
</div>
<div class="container-fluid" id="modal-mot_dg_box"></div>
</div>
</div>