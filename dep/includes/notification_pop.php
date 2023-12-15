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
   }
}

$total = 0;
//Mail DANO
$query_ad = "SELECT * FROM ".$database_connect_prefix."mail_dno where statut=0 and projet='".$_SESSION["clp_projet"]."' ORDER BY `date` desc";
try{
    $ad = $pdar_connexion->prepare($query_ad);
    $ad->execute();
    $row_ad = $ad ->fetchAll();
    $total_row_ad = $ad->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($_SESSION["clp_fonction"]!="Coordo") $total=$total_row_ad;
//DANO en instance juste for Coordo
if($_SESSION["clp_fonction"]=="Coordo")
{
    $query_dano = "SELECT distinct ".$database_connect_prefix."dno.* FROM ".$database_connect_prefix."dno where ".$database_connect_prefix."dno.projet='".$_SESSION["clp_projet"]."' and traitement=1 ORDER BY numero desc";
    try{
        $dano = $pdar_connexion->prepare($query_dano);
        $dano->execute();
        $row_dano = $dano ->fetchAll();
        $total_row_dano = $dano->rowCount(); $total+=$total_row_dano;
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
}

//Workflow
$query_liste_dossier = "SELECT * FROM ".$database_connect_prefix."workflow where traitement=0 ";
$query_liste_dossier .= " and expediteur='".$_SESSION["clp_fonction"]."' and `read`=0 and projet='".$_SESSION["clp_projet"]."' ORDER BY date_dossier desc";
try{
    $liste_dossier = $pdar_connexion->prepare($query_ad);
    $liste_dossier->execute();
    $row_liste_dossier = $liste_dossier ->fetchAll();
    $totalRows_liste_dossier = $liste_dossier->rowCount(); $total+=$totalRows_liste_dossier;
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//Dernier traitement dossier
$query_last_traitement_dossier ="SELECT s.*, w.nom FROM ".$database_connect_prefix."suivi_workflow s, ".$database_connect_prefix."workflow w WHERE s.`read`=0 and s.destinataire='".$_SESSION["clp_fonction"]."' and s.numero=w.numero and w.projet='".$_SESSION["clp_projet"]."' order by id_suivi desc";
try{
    $last_traitement_dossier = $pdar_connexion->prepare($query_last_traitement_dossier);
    $last_traitement_dossier->execute();
    $row_last_traitement_dossier = $last_traitement_dossier ->fetchAll();
    $totalRows_last_traitement_dossier = $last_traitement_dossier->rowCount(); $total+=$totalRows_last_traitement_dossier;
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//Tache Mission supervision
$query_tache_supervision = "select r.id_recommandation, r.recommandation, r.ref_no, r.mission, year(ms.debut) as annee, m.* FROM ".$database_connect_prefix."recommandation_mission r, ".$database_connect_prefix."mission_plan m, ".$database_connect_prefix."mission_supervision ms where r.responsable_interne='".$_SESSION["clp_id"]."' and r.id_recommandation=m.code_rec and ms.projet='".$_SESSION["clp_projet"]."' and ms.code_ms=r.mission and m.date_reelle is null and m.valider=0 GROUP BY r.id_recommandation order by m.ordre";
try{
    $tache_supervision = $pdar_connexion->prepare($query_tache_supervision);
    $tache_supervision->execute();
    $row_tache_supervision = $tache_supervision ->fetchAll();
    $totalRows_tache_supervision = $tache_supervision->rowCount(); $total+=$totalRows_tache_supervision;
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//Tache PTBA
//code_activite='$code_act' and annee=$annee and
$query_tache = "select * FROM ".$database_connect_prefix."groupe_tache where annee='".date("Y")."' and projet='".$_SESSION["clp_projet"]."' and responsable='".$_SESSION["clp_fonction"]."' ORDER BY code_tache ASC";
try{
    $tache = $pdar_connexion->prepare($query_tache);
    $tache->execute();
    $row_tache = $tache ->fetchAll();
    $totalRows_tache = $tache->rowCount(); $total+=$totalRows_tache;
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>
<?php if(!isset($_GET["notif_zone"])) { ?>
<li class="dropdown" id="notif_zone">
<?php } ?>
<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding: 17px 19px 18px 19px;" title="<?php echo ($total==0)?"":"Vous avez $total notification(s)"; ?>"> <i class="icon-comments-o"></i> <span class="badge" id="notif_title_num"><?php echo ($total==0)?'':$total; ?></span> </a>
  <ul class="dropdown-menu extended notification" id="notif_pane">
    <li class="title" id="notif_title_valu"> <p><?php echo ($total==0)?"Aucune notification":"$total notification(s)"; ?></p> </li>
  <li class="dropdown scrollerNotification">
    <ul style="list-style: outside none none; margin: 0px; padding: 0px;">
<?php if($total_row_ad>0 && $_SESSION["clp_fonction"]!="Coordo"){ foreach($row_ad as $row_ad) { ?>
<li class=""> <a href="./courrier_dno.php?show=<?php echo $row_ad["id_mail"]; ?>"> <span class="photo"><!--<img src="./images/mail_warning.png" width="15" height="15" alt="">--></span> <span class="time" style="margin-top:-15px!important;"><?php echo date_reg($row_ad["date"],'/',1,1); ?></span><span class="subject icon-envelope"> <span class="from"><?php echo $row_ad["objet"]; ?></span> </span> <span class="text"> <?php echo $row_ad["expediteur"]; ?> </span> </a> </li>
<?php } } ?>
<!--//DANO en instance only for Coordo-->
<?php if(isset($total_row_dano) && $total_row_dano>0 && $_SESSION["clp_fonction"]=="Coordo"){ foreach($row_dano as $row_dano){ ?>
<li class=""> <a href="./courrier_dno.php?pane=3&click=<?php echo $row_dano["numero"]; ?>"> <span class="photo"><!--<img src="./images/mail_warning.png" width="15" height="15" alt="">--></span> <span class="time" style="margin-top:-15px!important;"><?php echo date_reg($row_dano["date_initialisation"].date(" H:i:s"),'/',1,1); ?></span><span class="subject icon-envelope"> <span class="from"><?php echo $row_dano["objet"]; ?></span> </span> <span class="text"> <?php echo $row_dano["expediteur"]; ?> </span> </a> </li>
<?php } } ?>
<?php if($totalRows_tache_supervision>0){ foreach($row_tache_supervision as $row_tache_supervision){ //Tache Dilligence ?>
<li class=""> <a href="./mission_supervision.php?click=<?php echo $row_tache_supervision["id_recommandation"]."&code_ms=".$row_tache_supervision["mission"]."&annee=".$row_tache_supervision["annee"]; ?>"> <span class="photo"><!--<img src="./images/mail_warning.png" width="15" height="15" alt="">--></span> <span class="time" style="margin-top:-15px!important;"><?php echo date_reg($row_tache_supervision["date_prevue"].date(" H:i:s"),'/',1,1); ?></span><span class="subject icon-exchange"> <span class="from"><?php echo $row_tache_supervision["recommandation"]; ?></span> </span> <span class="text"> <?php echo $row_tache_supervision["phase"]; ?> </span> </a> </li>
<?php } } ?>
<?php //Workflow et suivi
if($totalRows_liste_dossier>0){ foreach($row_liste_dossier as $row_liste_dossier){ //Workflow ?>
<li class=""> <a href="./workflow.php?show=<?php echo $row_liste_dossier["id_dossier"]; ?>&doc=1"> <span class="photo"><!--<img src="./images/mail_warning.png" width="15" height="15" alt="">--></span> <span class="time" style="margin-top:-15px!important;"><?php echo date_reg($row_liste_dossier["date_enregistrement"],'/',1,1); ?></span><span class="subject icon-exchange"> <span class="from"><?php echo $row_liste_dossier["nom"]; ?></span> </span> <span class="text" title="<?php echo (isset($fonction_array[$row_liste_dossier["expediteur"]]))?$fonction_array[$row_liste_dossier["expediteur"]]:$row_liste_dossier["expediteur"]; ?>"> <?php echo $row_liste_dossier["expediteur"]; ?> </span> </a> </li>
<?php } }
if($totalRows_last_traitement_dossier>0){ foreach($row_last_traitement_dossier as $row_last_traitement_dossier){ //suivi ?>
<li class=""> <a href="./workflow.php?show=<?php echo $row_last_traitement_dossier["id_suivi"]; ?>&suivi=1"> <span class="photo"><!--<img src="./images/mail_warning.png" width="15" height="15" alt="">--></span> <span class="time" style="margin-top:-15px!important;"><?php echo date_reg($row_last_traitement_dossier["date_enregistrement"],'/',1,1); ?></span><span class="subject icon-exchange"> <span class="from"><?php echo $row_last_traitement_dossier["nom"]; ?></span> </span> <span class="text" title="<?php echo (isset($fonction_array[$row_last_traitement_dossier["expediteur"]]))?$fonction_array[$row_last_traitement_dossier["expediteur"]]:$row_last_traitement_dossier["expediteur"]; ?>"> <?php echo $row_last_traitement_dossier["expediteur"]; ?> </span> </a> </li>
<?php } } ?>
<?php if($totalRows_tache>0){ //Tache PTBA ?>
<li class=""> <a href="./print_taches_activite_ptba.php?annee=<?php echo date("Y")."&responsable=1"; ?>"> <span class="photo"><!--<img src="./images/mail_warning.png" width="15" height="15" alt="">--></span> <span class="time" style="margin-top:-15px!important;">ce mois-ci</span><span class="subject icon-cubes"> <span class="from">T&acirc;ches PTBA</span> </span> <span class="text"> Vous avez une ou plusieur t&acirc;che(s) </span> </a> </li>
<?php } ?>
</ul></li>
  </ul>
<?php if(!isset($_GET["notif_zone"])) { ?>
</li>
<?php } ?>
<script type="text/javascript" src="<?php //echo $path; ?>plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="<?php //echo $path; ?>plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script type="text/javascript">
//var a = jQuery('#notif_title_valu');
jQuery('#notif_title_num').html('<?php echo ($total==0)?"":$total; ?>');
jQuery('#notif_title_valu').html('<p><?php echo ($total==0)?"Aucune notification":"$total nouvelle(s) notification(s)"; ?></p>');
<?php if($total>5){ ?>
$(function () {
    $(".scrollerNotification").slimscroll({
                            height: "400px",
                            wheelStep: 7
                        });
});
<?php } ?>
</script>
<style>
.scrollerNotification ul > li > a {
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: 400;
    line-height: 1.42857;
    color: #333;
    white-space: nowrap;
}
.scrollerNotification ul > li > a:hover, .scrollerNotification ul > li > a:focus {
    background: #090 none repeat scroll 0% 0%;
    color: #FFF;
    filter: none;
}
</style>