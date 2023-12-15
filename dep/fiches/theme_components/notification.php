<?php
if(!isset($_SESSION)) session_start();
$path = (isset($_GET["path"]))?$_GET["path"]:"./";
require_once $path.'api/Fonctions.php';
require_once $path.'theme_components/theme_style.php';
/*include_once $path.'api/configuration.php';
if(!headers_sent())
{
  $config = new Config;
  //header('Content-Type: text/html; charset=ISO-8859-15');
}*/

$total = $totalRows_tache = $totalRows_last_traitement_dossier = $totalRows_liste_dossier = $totalRows_tache_supervision = $total_row_dano = $total_row_ad = 0;/*
//Indicateurs referentiels
mysql_select_db($database_pdar_connexion, $pdar_connexion);//where etat='".$_SESSION["structure"]."'
$query_indicateur = "SELECT * FROM referentiel_indicateur ORDER BY date_enregistrement,modifier_le desc";
$indicateur = mysql_query($query_indicateur, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_indicateur = mysql_fetch_assoc($indicateur);
$total_row_indicateur=mysql_num_rows($indicateur); $total+=$total_row_indicateur;
      /*
//Workflow
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_dossier = "SELECT * FROM ".$database_connect_prefix."workflow where traitement=0 ";
$query_liste_dossier .= " and expediteur='".$_SESSION["fonction"]."' and `read`=0 and projet='".$_SESSION["projet"]."' ORDER BY date_dossier desc";
$liste_dossier  = mysql_query($query_liste_dossier , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_dossier = mysql_fetch_assoc($liste_dossier);
$totalRows_liste_dossier = mysql_num_rows($liste_dossier);  $total+=$totalRows_liste_dossier;

//Dernier traitement dossier
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_last_traitement_dossier ="SELECT s.*, w.nom FROM ".$database_connect_prefix."suivi_workflow s, ".$database_connect_prefix."workflow w WHERE s.`read`=0 and s.destinataire='".$_SESSION["fonction"]."' and s.numero=w.numero and w.projet='".$_SESSION["projet"]."' order by id_suivi desc";
$last_traitement_dossier = mysql_query($query_last_traitement_dossier, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_last_traitement_dossier = mysql_fetch_assoc($last_traitement_dossier);
$totalRows_last_traitement_dossier = mysql_num_rows($last_traitement_dossier); $total+=$totalRows_last_traitement_dossier;

//Tache Mission supervision
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_tache_supervision = "select r.id_recommandation, r.recommandation, r.ref_no, r.mission, year(ms.debut) as annee, m.* FROM ".$database_connect_prefix."recommandation_mission r, ".$database_connect_prefix."mission_plan m, ".$database_connect_prefix."mission_supervision ms where r.responsable_interne='".$_SESSION["id"]."' and r.id_recommandation=m.code_rec and ms.projet='".$_SESSION["projet"]."' and ms.code_ms=r.mission and m.date_reelle is null and m.valider=0 GROUP BY r.id_recommandation order by m.ordre";
$tache_supervision  = mysql_query_ruche($query_tache_supervision , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_tache_supervision = mysql_fetch_assoc($tache_supervision);
$totalRows_tache_supervision  = mysql_num_rows($tache_supervision);
if($totalRows_tache_supervision>0) $total+= $totalRows_tache_supervision;

//Tache PTBA
mysql_select_db($database_pdar_connexion, $pdar_connexion); //code_activite='$code_act' and annee=$annee and
$query_tache = "select * FROM ".$database_connect_prefix."groupe_tache where annee='".date("Y")."' and projet='".$_SESSION["projet"]."' and responsable='".$_SESSION["fonction"]."' ORDER BY code_tache ASC";
$tache  = mysql_query_ruche($query_tache , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_tache = mysql_fetch_assoc($tache);
$totalRows_tache  = mysql_num_rows($tache);
if($totalRows_tache>0) $total++;   */
?>
<?php if(!isset($_GET["notif_zone"])) { ?>
<li class="dropdown" id="notif_zone">
<?php } ?>
<a class="dropdown-toggle label-menu-corner" href="#" data-toggle="dropdown" title="<?php echo ($total==0)?"Aucune notification disponible":"Vous avez $total notification".($total>1?"s":""); ?>">
<div class="" ><i class="pe-7s-speaker <?php echo $Text_Style; ?>"></i> <?php echo isset($Widgets_name)?$Widgets_name:""; ?><span class="label label-danger" id="notif_title_num"><?php echo ($total==0)?'':$total; ?></span></div></a>
  <ul class="dropdown-menu hdropdown notifications animated flipInX" id="notif_pane">
    <div class="title" id="notif_title_valu">Vous <?php echo ($total==0)?"n'":""; ?>avez <?php echo ($total==0)?"Aucune notification":"$total notification".($total>1?"s":""); ?></div>
  <li class="scrollerNotification">
    <ul style="list-style: outside none none; margin: 0px; padding: 0px;">
<?php $first = 0; if(isset($total_row_indicateur) && $total_row_indicateur>0){ if($first>0) echo '<li class="divider"></li>'; do { $first++; ?>
<li class="" title="<?php echo (!empty($row_indicateur["modifier_le"])?"Modification d'indicateur":"Nouveau indicateur"); ?>"> <a href="./suivi_referentiel.php?id=<?php echo $row_indicateur["id_ref_ind"]; ?>"> <span class="photo"><!--<img src="./images/mail_warning.png" width="15" height="15" alt="">--></span> <span class="time" style="margin-top:-15px!important;"><?php echo date_reg((!empty($row_indicateur["modifier_le"])?$row_indicateur["modifier_le"]:$row_indicateur["date_enregistrement"]).date(" H:i:s"),'/',1,1); ?></span><span class="subject icon-twitch"> <span class="from">Code : <?php echo $row_indicateur["code_ref_ind"]; ?></span> </span> <span class="text"> <?php echo $row_indicateur["intitule_ref_ind"]; ?> </span> </a> </li>
<?php  } while($row_indicateur = mysql_fetch_assoc($indicateur)); } ?>
<?php if($totalRows_tache_supervision>0){ do { $first++; //Tache Dilligence ?>
<li class=""> <a href="./mission_supervision.php?click=<?php echo $row_tache_supervision["id_recommandation"]."&code_ms=".$row_tache_supervision["mission"]."&annee=".$row_tache_supervision["annee"]; ?>"> <span class="photo"><!--<img src="./images/mail_warning.png" width="15" height="15" alt="">--></span> <span class="time" style="margin-top:-15px!important;"><?php echo date_reg($row_tache_supervision["date_prevue"].date(" H:i:s"),'/',1,1); ?></span><span class="subject icon-exchange"> <span class="from"><?php echo $row_tache_supervision["recommandation"]; ?></span> </span> <span class="text"> <?php echo $row_tache_supervision["phase"]; ?> </span> </a> </li>
<?php  } while($row_tache_supervision = mysql_fetch_assoc($tache_supervision)); } ?>
<?php //Workflow et suivi
if($totalRows_liste_dossier>0){ if($first>0) echo '<li class="divider"></li>'; do { $first++; //Workflow ?>
<li class=""> <a href="./workflow.php?show=<?php echo $row_liste_dossier["id_dossier"]; ?>&doc=1"> <span class="photo"><!--<img src="./images/mail_warning.png" width="15" height="15" alt="">--></span> <span class="time" style="margin-top:-15px!important;"><?php echo date_reg($row_liste_dossier["date_enregistrement"],'/',1,1); ?></span><span class="subject icon-exchange"> <span class="from"><?php echo $row_liste_dossier["nom"]; ?></span> </span> <span class="text" title="<?php echo (isset($fonction_array[$row_liste_dossier["expediteur"]]))?$fonction_array[$row_liste_dossier["expediteur"]]:$row_liste_dossier["expediteur"]; ?>"> <?php echo $row_liste_dossier["expediteur"]; ?> </span> </a> </li>
<?php } while($row_liste_dossier = mysql_fetch_assoc($liste_dossier)); }
if($totalRows_last_traitement_dossier>0){ if($first>0) echo '<li class="divider"></li>'; do { $first++; //suivi ?>
<li class=""> <a href="./workflow.php?show=<?php echo $row_last_traitement_dossier["id_suivi"]; ?>&suivi=1"> <span class="photo"><!--<img src="./images/mail_warning.png" width="15" height="15" alt="">--></span> <span class="time" style="margin-top:-15px!important;"><?php echo date_reg($row_last_traitement_dossier["date_enregistrement"],'/',1,1); ?></span><span class="subject icon-exchange"> <span class="from"><?php echo $row_last_traitement_dossier["nom"]; ?></span> </span> <span class="text" title="<?php echo (isset($fonction_array[$row_last_traitement_dossier["expediteur"]]))?$fonction_array[$row_last_traitement_dossier["expediteur"]]:$row_last_traitement_dossier["expediteur"]; ?>"> <?php echo $row_last_traitement_dossier["expediteur"]; ?> </span> </a> </li>
<?php } while($row_liste_dossier = mysql_fetch_assoc($liste_dossier)); } ?>
<?php if($totalRows_tache>0){ $first++; //Tache PTBA ?>
<li class=""> <a href="./print_taches_activite_ptba.php?annee=<?php echo date("Y")."&responsable=1"; ?>"> <span class="photo"><!--<img src="./images/mail_warning.png" width="15" height="15" alt="">--></span> <span class="time" style="margin-top:-15px!important;">ce mois-ci</span><span class="subject icon-cubes"> <span class="from">T&acirc;ches PTBA</span> </span> <span class="text"> Vous avez une ou plusieur t&acirc;che(s) </span> </a> </li>
<?php } ?>
</ul></li>
  </ul>
<?php if(!isset($_GET["notif_zone"])) { ?>
</li>
<?php } ?>
<script type="text/javascript">
$('#notif_title_num').html('<?php echo ($total==0)?"":$total; ?>');
$('#notif_title_valu').html('Vous avez <?php echo ($total==0)?"Aucune notification":"$total notification".($total>1?"s":""); ?>');
<?php if($total>5){ ?>$(function () {$(".scrollerNotification").slimScroll({height: "400px",wheelStep: 7});});<?php } ?>
</script>
<style>
#notif_pane {
    width: 300px;
}

.photo {
    float: left;
}
.photo img{
    width: 50px;
    margin-right: 5px;
}

.from1, .text1 {
  float: right!important;
  width: 190px!important;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space:nowrap;
}
.dropdown-menu.notifications li {
        padding: 8px 5px!important;
}
.dropdown-menu.notifications li .from {
        font-size: 13px;
        font-weight: 600;
}
.dropdown-menu.notifications li .time {
        font-weight: 300;
        position: absolute;
        right: 5px;
        color: #adadad;
        font-size: 11px;
        padding-top: 3px;
}
.dropdown-menu.notifications li .text {
        display: block;
        white-space: normal;
        font-size: 12px;
        line-height: 20px;
        padding-top: 1px;
        font-weight: normal;
}
.dropdown-menu.notifications li.active {  
        background: yellow;
        color:#000;
}
.dropdown-menu.notifications li.active a {
        background: yellow;
        color:#000;
}
</style>