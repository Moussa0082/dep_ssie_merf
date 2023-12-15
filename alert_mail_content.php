<?php  //ini_set("display_errors",1);
if(!isset($config))
{
  //include_once $path.'system/configuration.php';
  $config = new Config;
}
//header('Content-Type: text/html; charset=ISO-8859-15');
$rr = array("’", "œ");
$cop   = array("'", "&oelig;"); 
 
//$newphrase = str_replace($healthy, $yummy, $phrase);
//include_once $path.$config->sys_folder . "/database/db_connexion.php";
$anneec = date("Y");
$tableauMois=array('01'=>'Janvier','02'=>'F&eacute;vrier','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Ao&ucirc;t','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'D&eacute;cembre');
if(!isset($tableau_projet))
{
  //projet
  $query_liste_projet = "SELECT * FROM ".$database_connect_prefix."projet' ";
     try{
  $liste_projet = $pdar_connexion->prepare($query_liste_projet);
    $liste_projet->execute();
    $row_liste_projet = $liste_projet ->fetchAll();
    $totalRows_liste_projet = $liste_projet->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $tableau_projet = array();
  if($totalRows_liste_projet>0){  foreach($row_liste_projet as $row_liste_projet){  
  $tableau_projet[$row_liste_projet["code_projet"]] = array("sigle"=>$row_liste_projet["sigle_projet"],"intitule"=>$row_liste_projet["intitule_projet"]);
  } }
}

//type tâche activite PTBA
$query_type_tache = "SELECT * FROM ".$database_connect_prefix."type_tache";
     try{
  $type_tache = $pdar_connexion->prepare($query_type_tache);
    $type_tache->execute();
    $row_type_tache = $type_tache ->fetchAll();
    $totalRows_type_tache = $type_tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableau_type_tache =$tableau_code_tache = array();
if($totalRows_type_tache>0){ foreach($row_type_tache as $row_type_tache){  
$tableau_code_tache[$row_type_tache["id_groupe_tache"]] = $row_type_tache["ordre"];
$tableau_type_tache[$row_type_tache["id_groupe_tache"]] = $row_type_tache["intitule_tache"];
}  }  

//gestion version
 


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

$fonction = $_GET["fonction"]; $id_personnel = $_GET["id_personnel"]; $structure=$_GET["structure"];
$id = $_GET["id"];
$nom = $_GET["nom"];
$email = $_GET["email"];
$liste_projet_array = explode("|",$_GET["projet"]);
//number_format(0, 0, ',', ' ');

if(filter_var(trim($email), FILTER_VALIDATE_EMAIL)){ //Valide email
$total = 0;
//Agenda public/perso
$query_liste_agenda = "SELECT * FROM ".$database_connect_prefix."agenda_perso where expediteur=".GetSQLValueString($id_personnel, "text")." and valider=0 and DATEDIFF(now(),left(fin,10))>=-3 and  year(fin)=$anneec ORDER BY debut desc";
       try{
  $liste_agenda = $pdar_connexion->prepare($query_liste_agenda);
    $liste_agenda->execute();
    $row_liste_agenda = $liste_agenda ->fetchAll();
    $totalRows_liste_agenda = $liste_agenda->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

 if($totalRows_liste_agenda>0){ //Agenda ?>

<h1 style="width: 98%;" align="center" class="h1">Projet d'appui aux investissements agricoles des jeunes (PAIAJ)</h1>
<hr style="width: 98%;" color="#008000" />
<div style="width: 98%; margin: auto; padding: auto;">
<div class="heading" align="center"><?php echo ($total==0)?"":"Vous avez $total notification(s) en tout dans la base de donn&eacute;es PAIAJ. <a href='https://sise-pnper.org/' target='_blank' title='Lien vers la base de donn&eacute;es PAIAJ'>Cliquez ici</a> pour acceder au syst&egrave;me du PAIAJ"; ?></div>
<br><br>
<?php echo "<h2 style='margin: 0px; padding: 0px;'>Bonjour, $nom. Voici les alertes de la base de donn&eacute;es PAIAJ vous concernant</h2>"; ?>
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
<?php $i = 1; foreach($row_liste_agenda as $row_liste_agenda){  ?>
<tr>
<td align="center"><?php echo $i; ?></td>
<td><?php echo utf8_decode(str_replace($rr, $cop, $row_liste_agenda["titre"])); ?></td>
<td><?php echo utf8_decode(str_replace($rr, $cop, $row_liste_agenda["description"])); ?></td>
<td align="center"><?php echo date_reg($row_liste_agenda["debut"],'/'); ?></td>
<td align="center"><?php echo date_reg($row_liste_agenda["fin"],'/'); ?></td>
</tr>
<?php $i++; } ?>
</tbody></table>
<?php } ?>
<?php
foreach($liste_projet_array as $projet_code){
  if(!empty($projet_code) && isset($tableau_projet[$projet_code])){
$sigle = $tableau_projet[$projet_code]["sigle"]; $projet = $tableau_projet[$projet_code]["intitule"];
$total = 0;

  $query_liste_mission = "SELECT * FROM ".$database_connect_prefix."version_ptba where id_version_ptba in (select annee from ptba where projet='$projet_code') order by date_validation desc limit 1";
       try{
  $liste_mission = $pdar_connexion->prepare($query_liste_mission);
    $liste_mission->execute();
    $row_liste_mission = $liste_mission ->fetchAll();
    $totalRows_liste_mission = $liste_mission->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $tableau_version_ptba= array();
if($totalRows_liste_mission>0){ foreach($row_liste_mission as $row_liste_mission){  
$annee = $row_liste_mission["id_version_ptba"];
$tableau_version_ptba[$row_liste_mission["id_version_ptba"]] = $row_liste_mission["annee_ptba"]." ".$row_liste_mission['version_ptba'];
} } 

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
$totalRows_liste_dossier = mysql_num_rows($liste_dossier);  $total+=$totalRows_liste_dossier;

//Dernier traitement dossier
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_last_traitement_dossier ="SELECT s.*, w.nom FROM ".$database_connect_prefix."suivi_workflow s, ".$database_connect_prefix."workflow w WHERE s.`read`=0 and s.destinataire='".$fonction."' and s.numero=w.numero and w.projet='".$projet_code."' order by id_suivi desc";
$last_traitement_dossier = mysql_query($query_last_traitement_dossier, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_last_traitement_dossier = mysql_fetch_assoc($last_traitement_dossier);
$totalRows_last_traitement_dossier = mysql_num_rows($last_traitement_dossier); $total+=$totalRows_last_traitement_dossier; */

//Recommandations Mission supervision
 //mysql_select_db($database_pdar_connexion, $pdar_connexion);OR (exists (select SUM(proportion) FROM ".$database_connect_prefix."mission_plan WHERE id_recommandation=code_rec and valider=1 GROUP BY code_rec) and (select SUM(proportion) FROM ".$database_connect_prefix."mission_plan WHERE id_recommandation=code_rec and valider=1 and proportion<100 GROUP BY code_rec)<100)        and ms.projet='".$projet_code."'
$query_rec_supervision = "SELECT objet, mission, recommandation, numero, ref_no, date_buttoir, ms.debut, ms.fin, ms.type, r.responsable FROM recommandation_mission r, mission_supervision ms WHERE r.responsable_interne='".$id_personnel."' and r.projet =  '$projet_code'
AND ms.id_mission = mission AND DATEDIFF( NOW( ) , date_buttoir ) >= -3 and  year(date_buttoir)=$anneec AND id_recommandation NOT IN (SELECT code_rec FROM ( SELECT code_rec, SUM( proportion ) AS propor
FROM  `mission_plan` WHERE valider =1 GROUP BY code_rec) AS test WHERE propor >99) ORDER BY ms.id_mission, ref_no";
     try{
  $rec_supervision = $pdar_connexion->prepare($query_rec_supervision);
    $rec_supervision->execute();
    $row_rec_supervision = $rec_supervision ->fetchAll();
    $totalRows_rec_supervision = $rec_supervision->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_rec_supervision>0) $total+= $totalRows_rec_supervision;

//echo $query_rec_supervision;
//exit;
 
//Tache Mission supervision
$query_tache_supervision = "select ms.objet,r.mission,r.recommandation, r.ref_no, r.numero, m.* FROM ".$database_connect_prefix."recommandation_mission r, ".$database_connect_prefix."mission_plan m, ".$database_connect_prefix."mission_supervision ms where r.responsable_interne='".$id_personnel."' and r.id_recommandation=m.code_rec  and ms.id_mission=r.mission and DATEDIFF(now(),m.date_prevue)>=-3 and  year(date_prevue)=$anneec and m.valider=0 and r.projet =  '$projet_code' order by ms.id_mission,  r.ref_no";
     try{
  $tache_supervision = $pdar_connexion->prepare($query_tache_supervision);
    $tache_supervision->execute();
    $row_tache_supervision = $tache_supervision ->fetchAll();
    $totalRows_tache_supervision = $tache_supervision->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_tache_supervision>0) $total+= $totalRows_tache_supervision;

//echo $query_tache_supervision;
//exit;

//Tache PTBA            
//$query_tache = "select g.* FROM ".$database_connect_prefix."groupe_tache g, ".$database_connect_prefix."suivi_tache s where g.annee<='$annee' and g.projet='".$projet_code."' and g.responsable='".$fonction."' and (g.id_groupe_tache=s.id_tache OR id_groupe_tache not in (select s.id_tache FROM ".$database_connect_prefix."suivi_tache s where s.valider=0)) and g.id_activite=s.activite_ptba and s.valider=0 and FIND_IN_SET('".date("m")."',g.date_fin) ORDER BY g.code_tache ASC";

$query_tache = "select g.*, p.code_activite_ptba, p.intitule_activite_ptba, p.annee FROM ptba p, groupe_tache g where p.annee='$annee' and p.projet='".$projet_code."' and g.responsable='".$fonction."' and p.id_ptba=g.id_activite and  g.valider!=1 and DATEDIFF(now(),g.date_fin)>=-3 ORDER BY p.code_activite_ptba, `g`.`id_groupe_tache` ASC";

//echo $query_tache; //exit;
     try{
  $tache = $pdar_connexion->prepare($query_tache);
    $tache->execute();
    $row_tache = $tache ->fetchAll();
    $totalRows_tache = $tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_tache_supervision>0) $total+= $totalRows_tache_supervision;

//echo $query_tache;
//exit;

if($totalRows_tache>0) $total+= $totalRows_tache;     

if($total>0){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print $config->sitename;?></title>
</head>
<body style="background-image: url(https://demo.sise-paiaj.org/images/ruche.png); background-position: bottom right; background-repeat: no-repeat;">
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
<td align="center">
<a class="nofollow" rel="nofollow" title="PAIAJ" target="_blank" href="https://demo.sise-paiaj.org/"></a><a class="nofollow" rel="nofollow" target="_blank" href="#"></a>
<a class="nofollow" rel="nofollow" target="_blank" href="https://www.gouv.tg/"><img src="https://demo.sise-paiaj.org/images/bg3.png" border="0" alt="" width="250" height="60" usemap="#m_slogo" /></a>
<map name="m_slogo" id="m_slogo">
  <area shape="rect" coords="50,0,100,60" href="http://www.bad.org/" target="_blank" title="FIDA - IFAD" alt="FIDA" />
  <!--<area shape="rect" coords="0,0,200,60" href="http://www.niger.ng/" target="_blank" title="R&eacute;publique du Niger" alt="NG" />-->
</map>
<a class="nofollow" rel="nofollow" target="_blank" href="https://agriculture.gouv.tg/"></a></td>
</tr>
</tbody>
</table>

<h1 style="width: 98%;" align="center" class="h1">Projet d'appui aux investissements agricoles des jeunes (PAIAJ)</h1>
<hr style="width: 98%;" color="#008000" />
<div style="width: 98%; margin: auto; padding: auto;">
<div class="heading" align="center"><?php echo ($total==0)?"":"Vous avez $total notification(s) en tout dans la base de donn&eacute;es PAIAJ. <a href='https://demo.sise-paiaj.org/' target='_blank' title='Lien vers la base de donn&eacute;es PAIAJ'>Cliquez ici</a> pour acceder au syst&egrave;me du PAIAJ"; ?></div>
<br><br>
<?php echo "<h2 style='margin: 0px; padding: 0px;'>Bonjour, $nom. Voici les alertes de la base de donn&eacute;es PAIAJ vous concernant</h2>"; ?>


<?php if($totalRows_tache_supervision>0 && $totalRows_rec_supervision>0){ //Tache Dilligence ?>
<h2 style="margin: 0px 0 5px 0; padding: 5px 0 0 0; border-bottom: solid 1px; color: #808080;">Section Dilligence</h2>
<table width="100%" border="1" cellspacing="0" class="">
  <thead>
  <tr bgcolor="#E4E4E4">
    <th align="center">Mission</th>
    <th align="center">N&deg;</th>
    <th align="center">Recommandation</th>
    <th align="center">T&acirc;che</th>
    <th align="center">Date pr&eacute;vue</th>
  </tr>
  </thead>
  <tbody>

<?php if($totalRows_tache_supervision>0){ $i = 1; foreach($row_tache_supervision as $row_tache_supervision){ ?>
<tr>
<td><?php echo $row_tache_supervision["objet"]; ?></td>
<td align="center"><?php echo (!empty($row_tache_supervision["ref_no"]))?$row_tache_supervision["ref_no"]:$row_tache_supervision["numero"]; ?></td>
<td><?php echo utf8_decode(str_replace($rr, $cop, $row_tache_supervision["recommandation"])); ?></td>
<td><?php echo utf8_decode(str_replace($rr, $cop, $row_tache_supervision["phase"])); ?></td>
<td align="center"><?php echo date_reg($row_tache_supervision["date_prevue"],'/'); ?></td>
</tr>
<?php $i++; } } ?>
</tbody></table>
<?php } ?>
<?php  $wf = 0; 
//Workflow et suivi

?> <?php if(isset($totalRows_tache) && $totalRows_tache>0){ //Tache PTBA ?>
<h2 style="margin: 0px 0 5px 0; padding: 5px 0 0 0; border-bottom: solid 1px; color: #808080;">Section PTBA</h2>
<table width="100%" border="1" cellspacing="0" class="">
  <thead>
  <tr bgcolor="#E4E4E4">
    <th align="center">Code</th>
    <th align="center">Activit&eacute;</th>
    <th align="center">T&acirc;che</th>
    <th align="center">P&eacute;riode</th>
  </tr>
  </thead>
  <tbody>
<?php $i = 1; foreach($row_tache as $row_tache){ ?>
<tr>
<td align="center"><?php  echo $row_tache["code_activite_ptba"];; ?></td>
<td><?php echo utf8_decode(str_replace($rr, $cop, $row_tache["intitule_activite_ptba"])); echo "<br /><b>PTBA ".$tableau_version_ptba[$row_tache["annee"]]."</b>"; ?></td>
<td><?php if(isset($tableau_type_tache[$row_tache["id_groupe_tache"]])) echo utf8_decode(str_replace($rr, $cop, $tableau_type_tache[$row_tache["id_groupe_tache"]])); else echo $row_tache["id_groupe_tache"]; ?></td>
<td align="center"><?php echo date_reg($row_tache["date_debut"],'/')." - ".date_reg($row_tache["date_fin"],'/'); ?></td>
</tr>
<?php $i++; } ?>
</tbody></table>

<?php } ?>

<br><br>Cordialement,<br><br><i>SISE PAIAJ</i><hr class="end">
<p class="Footer">Veuillez ne pas r&eacute;pondre &aacute; cet email. Les messages re&ccedil;us &aacute; cette adresse ne sont pas lus et ne re&ccedil;oivent donc aucune r&eacute;ponse. Pour obtenir de l'aide, <a rel="nofollow" target="_blank" href="https://demo.sise-paiaj.org/">connectez-vous</a> &aacute; votre compte et cliquez sur le lien aide en haut &aacute; droite de chaque page de RUCHE.</p>
<br><span class="Footer">Copyright &copy; 2021 PAIAJ. Tous droits r&eacute;serv&eacute;s.<br>
Projet d'appui aux investissements agricoles des jeunes du Minist&egrave;re en charge de l'emploi des jeunes, BP xxxx Lom&eacute; – Togo, <br />T&eacute;l: +228 96202945
<br />Email: <a href="mailto:paiaj@ruche-demo.org">paeijsptogo@gmail.com</a></span>
</div>
</body>

</html>
<?php } } ?>
<?php } ?>
<?php } //else echo "<h2>email '$email' est invalide!</h2>";
} ?>