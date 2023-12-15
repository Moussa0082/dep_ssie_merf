<?php


//session_start();
include_once 'system/configuration.php';
$config = new Config;

/*if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}*/

include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=ISO-8859-15');
$array_indic = array("OUI/NON","texte");

//number_format(0, 0, ',', ' ');



?>



<?php
$tab_cout =$tache =$indicateur = array();
if(isset($_GET["vers"])) $versionN = $_GET["vers"]; else $versionN = "ND";
if(isset($_GET["annee"])) $annee = intval($_GET["annee"]); else $annee = date("Y");
if(isset($_GET["cmp"])) $cmp = $_GET["cmp"]; else $cmp = "%%";

/*if(isset($_GET["cmp"]) && $_GET["cmp"]!=0) {$cmp=$_GET["cmp"]; $wheract_tache="AND ugl like '$cmp'"; } else $wheract_tache="";
if(isset($_GET["cmp"]) && $_GET["cmp"]!=0) {$cmp=$_GET["cmp"];  $whercible="and cible_indicateur_trimestre.region like '$cmp'"; $whersuivi="and suivi_indicateur_tache.ugl like '$cmp'"; } else {$wheract=""; $whercible=$whersuivi="";}*/

 $wheract_tache="";
$whercible=$whersuivi="";


//$rows = mysql_num_rows($liste_activite);


$query_liste_groupes_travail = "SELECT id_projet, code_projet, sigle_projet,annee_debut, annee_fin, ugl FROM diffa.projet order by annee_debut";
 try{
    $liste_groupes_travail = $pdar_connexion->prepare($query_liste_groupes_travail);
    $liste_groupes_travail->execute();
    $row_liste_groupes_travail = $liste_groupes_travail ->fetchAll();
    $totalRows_liste_groupes_travail = $liste_groupes_travail->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

/*$gp_array = array();
if($totalRows_liste_groupes_travail>0){ do { $gp_array[$row_liste_groupes_travail["id_groupes_travail"]] = $row_liste_groupes_travail["nom_groupes_travail"]; } while ($row_liste_groupes_travail = mysql_fetch_assoc($liste_groupes_travail)); }*/ //else $gp_array[] = date("Y");
//typologie de l indicateur
$query_liste_periode = "SELECT sum(cout_realise) as cout, code_activite.projet FROM diffa.code_activite WHERE code!='Code' and code!='fichiers' group by projet";	
 try{
    $liste_periode = $pdar_connexion->prepare($query_liste_periode);
    $liste_periode->execute();
    $row_liste_periode = $liste_periode ->fetchAll();
    $totalRows_liste_periode = $liste_periode->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$valeur_periode_array = array(); 
if($totalRows_liste_periode>0){ foreach($row_liste_periode as $row_liste_periode){
$valeur_periode_array[$row_liste_periode["projet"]]=$row_liste_periode["cout"];
}}
 
$query_liste_type_reunion = "SELECT sum(montant) as cout, projet FROM pnf.partenaire, diffa.type_part WHERE code=bailleur  GROUP BY projet";
 try{
    $liste_type_reunion = $pdar_connexion->prepare($query_liste_type_reunion);
    $liste_type_reunion->execute();
    $row_liste_type_reunion = $liste_type_reunion ->fetchAll();
    $totalRows_liste_type_reunion = $liste_type_reunion->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$cout_p_array = array();
if($totalRows_liste_type_reunion>0){  foreach($row_liste_type_reunion as $row_liste_type_reunion){ $cout_p_array[$row_liste_type_reunion["projet"]] = $row_liste_type_reunion["cout"]; } } //else $gp_array[] = date("Y");

//partenaire
$query_partenaire = "SELECT * FROM pnf.ugl ";
 try{
    $partenaire = $pdar_connexion->prepare($query_partenaire);
    $partenaire->execute();
    $row_partenaire = $partenaire ->fetchAll();
    $totalRows_partenaire = $partenaire->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$tableauUgl=array();
if($totalRows_partenaire>0){ foreach($row_partenaire as $row_partenaire){
$tableauUgl[$row_partenaire['code_ugl']]=$row_partenaire['abrege_ugl'];
} }

/*$query_structure = "SELECT * FROM ".$database_connect_prefix."sous_secteur_activite order by code_sous_secteur ";
 try{
    $structure = $pdar_connexion->prepare($query_structure);
    $structure->execute();
    $row_structure = $structure ->fetchAll();
    $totalRows_structure = $structure->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$tableauDomaine=$tableauDomaineV=array();
if($totalRows_structure>0){ foreach($row_structure as $row_structure){
$tableauDomaine[$row_structure['id_sous_secteur']]=$row_structure['nom_sous_secteur'];
$tableauDomaineV[$row_structure['id_sous_secteur']]=strip_tags($row_structure['description_sous_secteur']);
} }*/
if(!isset($include_data)){
?>



<!DOCTYPE HTML>



<html>



	<head>



        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



		<title><?php echo "Projets et Programmes";      ?></title>

  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/login.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
  <link href="<?php print $config->theme_folder; ?>/plugins/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins/wysiwyg-color.css" rel="stylesheet" type="text/css"/>
  <link href='<?php print $config->theme_folder; ?>/css.css' rel='stylesheet' type='text/css'>


</head>



	<body>


<?php } ?>

<?php if($totalRows_liste_groupes_travail>0){ ?>

<table class="table table-striped table-bordered table-hover table-responsive dataTable v_align" >
<thead>
<tr role="row">
  <th rowspan="2" data-class="expand">  <div align="left">Guichets</div></th>
  <th rowspan="2" data-hide="phone,tablet">P&eacute;riode <br/>et Dur&eacute;e (ans)</th>
  <th rowspan="2" data-class="expand">Unité de<br/> gestion </th>
  <!--<th rowspan="2" data-class="expand">Domaine</th>-->
  <th colspan="3" data-hide="phone,tablet"><div align="center">Co&ucirc;ts (F CFA) </div></th>
  </tr>
<tr role="row">
<th data-hide="phone,tablet"><div align="right">Pr&eacute;vu </div></th>
<th data-hide="phone"><div align="right">D&eacute;caiss&eacute;</div></th>
<th nowrap data-hide="phone">Taux (%) </th>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="">
<?php if($totalRows_liste_groupes_travail>0) { $i=0;  foreach($row_liste_groupes_travail as $row_liste_groupes_travail){ $id = $row_liste_groupes_travail['code_projet']; if(isset($cout_p_array[$id]) || isset($valeur_periode_array[$id])) { ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" ">  <div align="left"><?php echo $row_liste_groupes_travail['sigle_projet']; ?>
  </div></td>
<td class=" "><?php echo $row_liste_groupes_travail['annee_debut']." - ".$row_liste_groupes_travail['annee_fin']; ?>
  <strong>(<?php echo $row_liste_groupes_travail['annee_fin']-$row_liste_groupes_travail['annee_debut']; ?>)</strong></td>
<!--<td class=" "><strong>
  <?php //if(isset($tableauPartenaire[$row_liste_groupes_travail['structure']])) echo $tableauPartenaire[$row_liste_groupes_travail['structure']]?>
</strong></td>-->
<td class=" "><?php $a = explode("|",$row_liste_groupes_travail['ugl']); if(count($a)>0 && !empty($row_liste_groupes_travail['ugl'])){ $c = array(); foreach($a as $b) if(isset($tableauUgl[$b])) $c[]="<span title=\"".$tableauUgl[$b]."\">".$tableauUgl[$b]."</span>;"; if(count($c)>0) echo implode('&nbsp;<br/>',$c); else echo "Aucun"; } else echo "Aucun"; ?></td>
<td nowrap class=" "><div align="right">
  <?php if(isset($cout_p_array[$id])) echo number_format($cout_p_array[$id], 0, ',', ' '); else echo "-"; ?>
</div></td>
<td nowrap class=" "><div align="right">
  <?php if(isset($valeur_periode_array[$id])) echo number_format($valeur_periode_array[$id], 0, ',', ' '); else echo "-"; ?>
</div></td>
<td nowrap class=" "><div align="right">
  <?php if(isset($cout_p_array[$id]) && $cout_p_array[$id]>0 && isset($valeur_periode_array[$id])) echo number_format(100*$valeur_periode_array[$id]/$cout_p_array[$id], 2, ',', ' ')."%"; else echo "-"; ?>
</div></td>
</tr>
<?php }} } ?>
</tbody></table>

<?php } if(!isset($include_data)){  ?>



	</body>



</html>
<?php  } ?> 