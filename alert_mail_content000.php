<?php  //ini_set("display_errors",1);
if(!isset($config))
{
  //include_once $path.'system/configuration.php';
  $config = new Config;
}
//header('Content-Type: text/html; charset=UTF-8');

//include_once $path.$config->sys_folder . "/database/db_connexion.php";
$annee = date("Y");
$tableauMois=array('01'=>'Janvier','02'=>'F&eacute;vrier','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Ao&ucirc;t','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'D&eacute;cembre');
if(!isset($tableau_projet))
{
  //projet
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_projet = "SELECT * FROM ".$database_connect_prefix."projet' order by annee_debut asc ";
  $liste_projet  = mysql_query($query_liste_projet , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_projet  = mysql_fetch_assoc($liste_projet);
  $totalRows_liste_projet  = mysql_num_rows($liste_projet);
  $tableau_projet = array();
  if($totalRows_liste_projet>0){ do{
  $tableau_projet[$row_liste_projet["code_projet"]] = array("sigle"=>$row_liste_projet["sigle_projet"],"intitule"=>$row_liste_projet["intitule_projet"]);
  }while($row_liste_projet  = mysql_fetch_assoc($liste_projet));  }
}

if(isset($_GET["nom"]) && isset($_GET["projet"]))
{
//activite PTBA
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = "SELECT * FROM ".$database_connect_prefix."ptba";
$act  = mysql_query($query_act , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_act  = mysql_fetch_assoc($act);
$totalRows_act  = mysql_num_rows($act);
$tableau_activite = array();
if($totalRows_act>0){ do{
if(!isset($tableau_activite[$row_act["projet"]][$row_act["code_activite_ptba"]]))
$tableau_activite[$row_act["projet"]][$row_act["code_activite_ptba"]] = $row_act["intitule_activite_ptba"];
}while($row_act  = mysql_fetch_assoc($act));  }  */

$fonction = $_GET["fonction"]; $id_personnel1 = $_GET["id_personnel"]; $structure=$_GET["structure"];
$id = $_GET["id"];
$nom = $_GET["nom"];
$email = $_GET["email"];
$liste_projet_array = explode("|",$_GET["projet"]);
number_format(0, 0, ',', ' ');

if(filter_var(trim($email), FILTER_VALIDATE_EMAIL)){ //Valide email

/*foreach($liste_projet_array as $projet_code){
  if(!empty($projet_code) && isset($tableau_projet[$projet_code])){
$sigle = $tableau_projet[$projet_code]["sigle"]; $projet = $tableau_projet[$projet_code]["intitule"];*/
if(isset($liste_projet_array)){ if(1==1){
$total = 0;
//Mail DANO
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_ad = "SELECT * FROM ".$database_connect_prefix."mail_dno where statut=0 and projet='".$projet_code."' and expediteur<>'MAILER-DAEMON@vps28353.lws-hosting.com'";
$ad = mysql_query($query_ad, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_ad = mysql_fetch_assoc($ad);
$total_row_ad=mysql_num_rows($ad); if($fonction!="Coordo") $total=$total_row_ad;
//DANO en instance juste for Coordo
if($fonction=="Coordo")
{
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_dano = "SELECT distinct ".$database_connect_prefix."dno.* FROM ".$database_connect_prefix."dno where ".$database_connect_prefix."dno.projet='".$projet_code."' and traitement=1 ORDER BY numero desc";
  $dano = mysql_query($query_dano, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_dano = mysql_fetch_assoc($dano);
  $total_row_dano=mysql_num_rows($dano); $total+=$total_row_dano;
}   */

//Workflow
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_dossier = "SELECT * FROM ".$database_connect_prefix."workflow where traitement=0 ";
$query_liste_dossier .= " and expediteur='".$fonction."' and `read`=0 and projet='".$projet_code."' ORDER BY date_dossier desc";
$liste_dossier  = mysql_query($query_liste_dossier , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_dossier = mysql_fetch_assoc($liste_dossier);
$totalRows_liste_dossier = mysql_num_rows($liste_dossier);  $total+=$totalRows_liste_dossier;   */

//Dernier traitement dossier
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_last_traitement_dossier ="SELECT s.*, w.nom FROM ".$database_connect_prefix."suivi_workflow s, ".$database_connect_prefix."workflow w WHERE s.`read`=0 and s.destinataire='".$fonction."' and s.numero=w.numero and w.projet='".$projet_code."' order by id_suivi desc";
$last_traitement_dossier = mysql_query($query_last_traitement_dossier, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_last_traitement_dossier = mysql_fetch_assoc($last_traitement_dossier);
$totalRows_last_traitement_dossier = mysql_num_rows($last_traitement_dossier); $total+=$totalRows_last_traitement_dossier;
                                    */
//Recommandations Mission supervision
mysql_select_db($database_pdar_connexion, $pdar_connexion);  // and r.projet='".$projet_code."' and r.structure='".$structure."'
$query_rec_supervision = "select objet,mission,recommandation,numero,ref_no,date_buttoir FROM ".$database_connect_prefix."recommandation_mission r, ".$database_connect_prefix."mission_supervision ms where responsable_interne=".GetSQLValueString($id_personnel1, "text")." and ms.code_ms=mission and DATEDIFF(now(),date_buttoir)>=-3 and (not exists (select SUM(proportion) FROM ".$database_connect_prefix."mission_plan WHERE id_recommandation=code_rec GROUP BY code_rec) ) order by ref_no";
$rec_supervision  = mysql_query($query_rec_supervision , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_rec_supervision = mysql_fetch_assoc($rec_supervision);
$totalRows_rec_supervision  = mysql_num_rows($rec_supervision);
if($totalRows_rec_supervision>0) $total+= $totalRows_rec_supervision;

//Tache Mission supervision
mysql_select_db($database_pdar_connexion, $pdar_connexion);//and m.date_reelle is null   and ms.projet='".$projet_code."'
$query_tache_supervision = "select ms.objet,r.mission,r.recommandation, r.ref_no, r.numero, m.* FROM ".$database_connect_prefix."recommandation_mission r, ".$database_connect_prefix."mission_plan m, ".$database_connect_prefix."mission_supervision ms where r.responsable_interne=".GetSQLValueString($id_personnel1, "text")." and r.id_recommandation=m.code_rec  and ms.code_ms=r.mission and DATEDIFF(now(),m.date_prevue)>=-3 and m.valider=0 order by r.ref_no";
$tache_supervision  = mysql_query($query_tache_supervision , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_tache_supervision = mysql_fetch_assoc($tache_supervision);
$totalRows_tache_supervision  = mysql_num_rows($tache_supervision);
if($totalRows_tache_supervision>0) $total+= $totalRows_tache_supervision;

//Tache PTBA            
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_tache = "select g.* FROM ".$database_connect_prefix."groupe_tache g, ".$database_connect_prefix."suivi_tache s where g.annee<='$annee' and g.projet='".$projet_code."' and g.responsable='".$fonction."' and (g.id_groupe_tache=s.id_tache OR id_groupe_tache not in (select s.id_tache FROM ".$database_connect_prefix."suivi_tache s where s.valider=0)) and g.id_activite=s.id_activite and s.valider=0 and FIND_IN_SET('".date("m")."',g.periode) ORDER BY g.code_tache ASC";
$tache  = mysql_query($query_tache , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_tache = mysql_fetch_assoc($tache);
$totalRows_tache  = mysql_num_rows($tache);
if($totalRows_tache>0) $total+= $totalRows_tache;     */

//Agenda public/perso
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_agenda = "SELECT * FROM ".$database_connect_prefix."agenda_perso where (`type`='public' and expediteur=".GetSQLValueString($id_personnel1, "text").") OR (id_personnel=".GetSQLValueString($id_personnel1, "text")." and `type`='private') and valider=0 and DATEDIFF(now(),debut)>=-3 ORDER BY debut desc";
$liste_agenda  = mysql_query($query_liste_agenda , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_agenda = mysql_fetch_assoc($liste_agenda);
$totalRows_liste_agenda = mysql_num_rows($liste_agenda);  $total+=$totalRows_liste_agenda;

if($total>0){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print $config->sitename;?></title>
</head>
<body style="background-image: url(http://ruche-parsat.org/images/ruche.png); background-position: bottom right; background-repeat: no-repeat;">
<style type="text/css">
BODY {font-family:verdana, arial, helvetica, sans-serif;font-size:12px;color:#000000;}
.nofollow {text-decoration: none;}
.h1 { color: #000; padding-bottom: 0px; margin-bottom: 0px;}
table, table tr td { border-collapse: collapse;}
HR.end {width:100%;margin-top:10px;margin-bottom:0px;border-left:#fff;border-right:#fff;border-top:#fff;border-bottom:2px dotted #ccc;}
.subHeadingEoa {font-family:verdana, arial, helvetica, sans-serif;font-size:15px;font-weight:bold;color:#000000;}
.heading {font-family:verdana, arial, helvetica, sans-serif;font-size:18px;font-weight:bold;color:#003366;}
.Footer {font-family:verdana, arial, helvetica, sans-serif;font-size:11px;color:#aaaaaa;}
</style>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody>
<tr valign="top">
<td align="center"><a class="nofollow" rel="nofollow" title="PARSAT" target="_blank" href="http://ruche-parsat.org/"><img src="http://ruche-parsat.org/images/bg1.png" border="0" alt="PARSAT" width="150" height="60"></a>
<a class="nofollow" rel="nofollow" target="_blank" href="http://ruche-parsat.org/"><img src="http://ruche-parsat.org/images/bg3.png" border="0" alt="PARSAT" width="250" height="60" usemap="#m_slogo"></a>
<map name="m_slogo" id="m_slogo">
<area shape="rect" coords="50,0,100,60" href="http://www.ifad.org/" target="_blank" title="FIDA - IFAD" alt="FIDA" />
<area shape="rect" coords="50,0,150,60" href="http://www.gef.org/" target="_blank" title="GEF" alt="GEF" />
<!--<area shape="rect" coords="0,0,200,60" href="http://www.niger.ng/" target="_blank" title="République du Niger" alt="NG" />-->
</map>
</td>
</tr>
</tbody>
</table>

<h2 style="width: 98%;" align="center" class="h1"><!--PROJET --><?php //echo $sigle." : ".(isset($config->sitename)?$config->sitename:"PNF"); ?>Projet d’Amélioration de Résilience des Systèmes Agricoles au Tchad (PARSAT) </h2>

<hr style="width: 98%;" color="#008000" />
<div style="width: 98%; margin: auto; padding: auto;">
<div class="heading" align="center"><?php echo ($total==0)?"":"Vous avez $total notification(s) en tout dans la base de données du PARSAT. <a href='http://ruche-parsat.org/' target='_blank' title='Lien vers la base de données PARSAT'>Cliquez ici</a> pour acceder à la base de données du PARSAT"; ?></div>
<br><br>
<?php echo "<h2 style='margin: 0px; padding: 0px;'>Bonjour, $nom. Voici les alertes du SISE vous concernant</h2>"; ?>

<?php /*if($total_row_ad>0 && $fonction!="Coordo"){ ?>
<h2 style="margin: 0px 0 5px 0; padding: 5px 0 0 0; border-bottom: solid 1px; color: #808080;">Section Courrier DANO</h2>
<table width="100%" border="1" cellspacing="0" class="" >
  <thead>
  <tr bgcolor="#E4E4E4">
    <th align="center">N&deg;</th>
    <th align="center">Objet</th>
    <th align="center">Exp&eacute;diteur</th>
    <th align="center">Date</th>
  </tr>
  </thead>
  <tbody>
<?php $i = 1; do { ?>
<tr>
<td align="center"><?php echo $i; ?></td>
<td><?php echo $row_ad["objet"]; ?></td>
<td><?php echo $row_ad["expediteur"]; ?></td>
<td align="center"><?php echo date_reg($row_ad["date"],'/'); ?></td>
</tr>
<?php $i++; } while($row_ad = mysql_fetch_assoc($ad)); ?>
</tbody></table>
<?php }*/ ?>
<!--//DANO en instance only for Coordo-->
<?php if(isset($total_row_dano) && $total_row_dano>0 && $fonction=="Coordo"){/* ?>
<h2 style="margin: 0px 0 5px 0; padding: 5px 0 0 0; border-bottom: solid 1px; color: #808080;">Section Courrier DANO</h2>
<table width="100%" border="1" cellspacing="0" class="">
  <thead>
  <tr bgcolor="#E4E4E4">
    <th align="center">N&deg; DANO</th>
    <th align="center">Objet</th>
    <th align="center">Exp&eacute;diteur</th>
    <th align="center">Date</th>
  </tr>
  </thead>
  <tbody>
<?php $i = 1; do { ?>
<tr>
<td align="center"><?php echo $row_dano["numero"]; ?></td>
<td><?php echo $row_dano["objet"]; ?></td>
<td><?php echo $row_dano["expediteur"]; ?></td>
<td align="center"><?php echo date_reg($row_dano["date_initialisation"],'/'); ?></td>
</tr>
<?php $i++; } while($row_dano = mysql_fetch_assoc($dano)); ?>
</tbody></table>
<?php */} ?>
<?php if($totalRows_tache_supervision>0 || $totalRows_rec_supervision>0){ //Tache Dilligence ?>
<h2 style="margin: 0px 0 5px 0; padding: 5px 0 0 0; border-bottom: solid 1px; color: #808080;">Section Dilligence</h2>
<table width="100%" border="1" cellspacing="0" class="">
  <thead>
  <tr bgcolor="#E4E4E4">
    <!--<th align="center">Mission</th>-->
    <th align="center">N&deg;</th>
    <th align="center">Recommandation</th>
    <th align="center">T&acirc;che</th>
    <th align="center">Date prévue</th>
  </tr>
  </thead>
  <tbody>
<?php if($totalRows_rec_supervision>0){ $i = 1; do { ?>
<tr>
<!--<td><?php echo $row_rec_supervision["objet"]."(<b>".$row_rec_supervision["mission"]."</b>)"; ?></td>-->
<td align="center"><?php echo (!empty($row_rec_supervision["ref_no"]))?$row_rec_supervision["ref_no"]:$row_rec_supervision["numero"]; ?></td>
<td><?php echo $row_rec_supervision["recommandation"]; ?></td>
<td>Aucune t&acirc;che planifi&eacute;e<?php //echo $row_rec_supervision["phase"]; ?></td>
<td align="center"><?php echo date_reg($row_rec_supervision["date_buttoir"],'/'); ?></td>
</tr>
<?php $i++; }while($row_rec_supervision = mysql_fetch_assoc($rec_supervision)); } ?>
<?php if($totalRows_tache_supervision>0){ $i = 1; do { ?>
<tr>
<!--<td><?php echo $row_tache_supervision["objet"]."(<b>".$row_tache_supervision["mission"]."</b>)"; ?></td>-->
<td align="center"><?php echo (!empty($row_tache_supervision["ref_no"]))?$row_tache_supervision["ref_no"]:$row_tache_supervision["numero"]; ?></td>
<td><?php echo $row_tache_supervision["recommandation"]; ?></td>
<td><?php echo $row_tache_supervision["phase"]; ?></td>
<td align="center"><?php echo date_reg($row_tache_supervision["date_prevue"],'/'); ?></td>
</tr>
<?php $i++; }while($row_tache_supervision = mysql_fetch_assoc($tache_supervision)); } ?>
</tbody></table>
<?php } ?>
<?php  $wf = 0;
//Workflow et suivi
if(isset($totalRows_liste_dossier) && $totalRows_liste_dossier>0){ $wf = 1; ?>
<!--<h2 style="margin: 0px 0 5px 0; padding: 5px 0 0 0; border-bottom: solid 1px; color: #808080;">Section WORKFLOW</h2>
<table width="100%" border="1" cellspacing="0" class="">
  <thead>
  <tr bgcolor="#E4E4E4">
    <th align="center">Dossier N&deg;</th>
    <th align="center">Objet</th>
    <th align="center">Destinataire</th>
    <th align="center">Exp&eacute;diteur</th>
    <th align="center">Date</th>
  </tr>
  </thead>
  <tbody>
<?php $i = 1; do { //Workflow ?>
<tr>
<td align="center"><?php echo $row_liste_dossier["numero"]; ?></td>
<td><?php echo $row_liste_dossier["message"]; ?></td>
<td><?php echo $row_liste_dossier["nom"]; ?></td>
<td><?php echo $row_liste_dossier["expediteur"]; ?></td>
<td align="center"><?php echo date_reg($row_liste_dossier["date_enregistrement"],'/'); ?></td>
</tr>
<?php $i++; } while($row_liste_dossier = mysql_fetch_assoc($liste_dossier)); ?>
</tbody></table>-->
<?php } ?>
<?php if(isset($totalRows_last_traitement_dossier) && $totalRows_last_traitement_dossier>0){ ?>
<?php if($wf==0){ ?>

<?php } ?>
<?php $i = 1; do { //suivi ?>
<!--<tr>
<td align="center"><?php echo $row_last_traitement_dossier["numero"]; ?></td>
<td><?php echo $row_last_traitement_dossier["message"]; ?></td>
<td><?php echo $row_last_traitement_dossier["nom"]; ?></td>
<td><?php echo $row_last_traitement_dossier["expediteur"]; ?></td>
<td align="center"><?php echo date_reg($row_last_traitement_dossier["date_enregistrement"],'/'); ?></td>
</tr>-->
<?php $i++; } while($row_last_traitement_dossier = mysql_fetch_assoc($last_traitement_dossier)); ?>
<!--</tbody></table> -->
<?php } ?>
<?php if(isset($totalRows_tache) && $totalRows_tache>0){/* //Tache PTBA ?>
<h2 style="margin: 0px 0 5px 0; padding: 5px 0 0 0; border-bottom: solid 1px; color: #808080;">Section PTBA</h2>
<table width="100%" border="1" cellspacing="0" class="">
  <thead>
  <tr bgcolor="#E4E4E4">
    <th align="center">N&deg; Ordre</th>
    <th align="center">Activit&eacute;</th>
    <th align="center">T&acirc;che</th>
    <th align="center">P&eacute;riode</th>
  </tr>
  </thead>
  <tbody>
<?php $i = 1; do { ?>
<tr>
<td align="center"><?php echo $row_tache["code_tache"]; ?></td>
<td><?php echo (isset($tableau_activite[$projet_code][$row_tache["code_activite"]]))?$tableau_activite[$projet_code][$row_tache["code_activite"]]:$row_tache["code_activite"]; echo "<br /><b>PTBA ".$row_tache["annee"]."</b>"; ?></td>
<td><?php echo $row_tache["intitule_tache"]; ?></td>
<td align="center"><?php $a = explode(",",$row_tache["periode"]); foreach($a as $b){ echo (isset($tableauMois[$b]))?$tableauMois[$b].", ":"NaN, "; } ?></td>
</tr>
<?php $i++; }while($row_tache = mysql_fetch_assoc($tache)); ?>
</tbody></table>

<?php */} ?>

<?php if($totalRows_liste_agenda>0){ //Agenda ?>
<h2 style="margin: 0px 0 5px 0; padding: 5px 0 0 0; border-bottom: solid 1px; color: #808080;">Section Agenda</h2>
<table width="100%" border="1" cellspacing="0" class="">
  <thead>
  <tr bgcolor="#E4E4E4">
    <th align="center">N&deg;</th>
    <th align="center">Titre</th>
    <th align="center">Description</th>
    <th align="center">Date d&eacute;but</th>
    <th align="center">Date butoire</th>
  </tr>
  </thead>
  <tbody>
<?php $i = 1; do { ?>
<tr>
<td align="center"><?php echo $i; ?></td>
<td><?php echo $row_liste_agenda["titre"]; ?></td>
<td><?php echo $row_liste_agenda["description"]; ?></td>
<td align="center"><?php echo date_reg($row_liste_agenda["debut"],'/'); ?></td>
<td align="center"><?php echo date_reg($row_liste_agenda["fin"],'/'); ?></td>
</tr>
<?php $i++; }while($row_liste_agenda = mysql_fetch_assoc($liste_agenda)); ?>
</tbody></table>
<?php } ?>

<br><br>Cordialement,<br><br><i>Ruche PARSAT</i><hr class="end">
<p class="Footer">Veuillez ne pas répondre à cet email. Pour obtenir de l'aide, <a rel="nofollow" target="_blank" href="http://ruche-parsat.org">connectez-vous</a> à votre compte et cliquez sur le lien Aide en haut à droite de chaque page de la base de données.</p>
<br><span class="Footer">Copyright © 2016 PARSAT. Tous droits réservés.<br>Projet d’Amélioration de Résilience des Systèmes Agricoles au Tchad (PARSAT) du Ministère de l’Agriculture, BP 35 Mongo – Tchad, <br />Tél: +235 66 27 35 49<br />Tél: / +235 99 27 35 49<br />Email: <a href="mailto:alert@ruche-parsat.org">alert@ruche-parsat.org</a></span>
</div>
</body>

</html>
<?php } } ?>
<?php  } ?>
<?php } //else echo "<h2>email '$email' est invalide!</h2>";
} ?>