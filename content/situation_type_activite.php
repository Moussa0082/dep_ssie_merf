<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
$path = '../';
include_once $path.'system/configuration.php';
$config = new Config;
       /*
if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
} */
//header('Content-Type: text/html; charset=ISO-8859-15');
$query_liste_annee = "SELECT distinct annee FROM ".$database_connect_prefix."ptba WHERE projet='".$_SESSION["clp_projet"]."' order by annee asc";
  try{
    $liste_annee = $pdar_connexion->prepare($query_liste_annee);
    $liste_annee->execute();
    $row_liste_annee = $liste_annee ->fetchAll();
    $totalRows_liste_annee = $liste_annee->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableauAnnee=array();
if($totalRows_liste_annee>0){
foreach($row_liste_annee as $row_liste_annee){
$tableauAnnee[]=$row_liste_annee['annee']; $annee_c=$row_liste_annee['annee'];
  }}
if(isset($annee_c)) $annee_c=$annee_c; else $annee_c=date("Y");
$query_liste_cout = "SELECT annee, left(code,3) as cat, SUM( if(cout_prevu>0, cout_prevu,0) ) AS prevu, SUM( if(cout_realise>0, cout_realise,0) ) AS realise, SUM( if(cout_engage>0, cout_engage,0) ) AS engage FROM ".$database_connect_prefix."code_budget WHERE projet='".$_SESSION["clp_projet"]."' group by annee, cat";
  try{
    $liste_cout = $pdar_connexion->prepare($query_liste_cout);
    $liste_cout->execute();
    $row_liste_cout = $liste_cout ->fetchAll();
    $totalRows_liste_cout = $liste_cout->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$prevu_array = array();
$realise_array = array();
$engage_array = array();
if($totalRows_liste_cout>0){
foreach($row_liste_cout as $row_liste_cout){
 $prevu_array[$row_liste_cout["annee"]][$row_liste_cout["cat"]]=$row_liste_cout["prevu"];
 $realise_array[$row_liste_cout["annee"]][$row_liste_cout["cat"]]=$row_liste_cout["realise"];
 $engage_array[$row_liste_cout["annee"]][$row_liste_cout["cat"]]=$row_liste_cout["engage"];
  }}
$query_entete = "SELECT * FROM ".$database_connect_prefix."niveau_budget_config WHERE projet='".$_SESSION["clp_projet"]."' LIMIT 1";
  try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$libelle = array();
$niveau = 2;
if($totalRows_entete>0){ $libelle=explode(",",$row_entete["libelle"]);}

$query_liste_version = "SELECT * FROM ".$database_connect_prefix."version_ptba  ORDER BY date_validation asc";
try{
    $liste_version = $pdar_connexion->prepare($query_liste_version);
    $liste_version->execute();
    $row_liste_version = $liste_version ->fetchAll();
    $totalRows_liste_version = $liste_version->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$TableauVersionP = array(); $version_array = array();
 if($totalRows_liste_version>0) { foreach($row_liste_version as $row_liste_version){
$max_version=$row_liste_version["id_version_ptba"];
$version_array[$row_liste_version["id_version_ptba"]] = $row_liste_version["annee_ptba"]." ".$row_liste_version["version_ptba"];
 } }
 if(isset($_GET['version'])) {$versiona=$_GET['version'];} elseif($totalRows_liste_version>0) $versiona=$max_version; else  $versiona=1;
?>
    <div class="widget-content">
      <div class="tabbable tabbable-custom" >
        <ul class="nav nav-tabs" >
          <?php //for($j=1;$j<=4;$j++){ ?>
          <?php $j=0; foreach($tableauAnnee as $anpta){ ?>
          <li title="Ann&eacute;e <?php echo $anpta; ?>" class="<?php echo ($anpta==$annee_c || (!in_array($anpta,$tableauAnnee) && $j==0))?"active":""; ?>"><a href="#tabta_feed_<?php echo $j; ?>" data-toggle="tab"> <?php if(isset($version_array[$anpta])) echo $version_array[$anpta]; else echo $anpta; ?></a></li>
          <?php $j++; } ?>
        </ul>
        <div class="tab-content">
          <?php

          $j = 0; foreach($tableauAnnee as $anpta){ ?>
          <?php //for($j=1;$j<=4;$j++){ ?>
          <div class="tab-pane <?php echo ($anpta==$annee_c || (!in_array($anpta,$tableauAnnee) && $j==0))?"active":""; ?>" id="tabta_feed_<?php echo $j; ?>">
            <div class="scroller">

<?php
$where = "niveau=$niveau";
$query_liste_activite_1 = "SELECT * FROM ".$database_connect_prefix."plan_budgetaire WHERE $where and projet='".$_SESSION["clp_projet"]."' ORDER BY niveau,code ASC";
  try{
    $liste_activite_1 = $pdar_connexion->prepare($query_liste_activite_1);
    $liste_activite_1->execute();
    $row_liste_activite_1 = $liste_activite_1 ->fetchAll();
    $totalRows_liste_activite_1 = $liste_activite_1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>

<table id="example" border="0" align="center" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive dataTable" >
<?php //if(count($libelle)>0 && $niveau<count($libelle)){ ?>
                <thead>
                  <tr>
                    <!--<td width="120"><strong>Code <?php //echo $libelle[$niveau]; ?></strong></td>-->
                    <td ><strong>Types d'activités</strong></td>
                    <td><div align="left"><strong>Pr&eacute;vu</strong></div></td>
                    <td><div align="left"><strong>D&eacute;caiss&eacute;</strong></div></td>
                    <td><div align="left"><strong>Engag&eacute;</strong></div></td>
                    <td><b>%Exe</b></td>
                  </tr>
                </thead>
                <tbody>
<?php if($totalRows_liste_activite_1>0){ $T0=$T1=$T2=0; foreach($row_liste_activite_1 as $row_liste_activite_1){ $id = $row_liste_activite_1["id"]; $code = $row_liste_activite_1["code"]; $parent = $row_liste_activite_1["parent"]; ?>
                <tr>
                    <!--<td><strong><?php echo $row_liste_activite_1["code"]; ?></strong></td>-->
        <td><strong><?php echo $row_liste_activite_1["intitule"]; ?></strong></td>
        <td><div align="right"><?php if(isset($prevu_array[$anpta][$row_liste_activite_1["code"]])){ echo number_format($prevu_array[$anpta][$row_liste_activite_1["code"]], 0, '.', ' '); $T0+=$prevu_array[$anpta][$row_liste_activite_1["code"]]; } ?></div></td>
        <td align="right"><?php if(isset($realise_array[$anpta][$row_liste_activite_1["code"]])) {echo number_format($realise_array[$anpta][$row_liste_activite_1["code"]], 0, '.', ' '); $totreal=$realise_array[$anpta][$row_liste_activite_1["code"]]; $T1+=$realise_array[$anpta][$row_liste_activite_1["code"]];} ?></td>
        <td align="right"><?php if(isset($engage_array[$anpta][$row_liste_activite_1["code"]])) {echo number_format($engage_array[$anpta][$row_liste_activite_1["code"]], 0, '.', ' '); $totreal=$totreal+$engage_array[$anpta][$row_liste_activite_1["code"]]; $T2+=$engage_array[$anpta][$row_liste_activite_1["code"]]; } ?></td>
		<td align="right"><?php if(isset($prevu_array[$anpta][$row_liste_activite_1["code"]]) && $prevu_array[$anpta][$row_liste_activite_1["code"]]>0) echo number_format(100*$totreal/$prevu_array[$anpta][$row_liste_activite_1["code"]], 2, ',', ' ')."%"; ?></td>
                </tr>
<?php } ?>
<tr>
      <td align="left"><strong>Total</strong></td>
       <td align="right"><div align="right"><b><?php echo number_format($T0, 0, ',', ' ');  ?></b></div></td>
        <td align="right"><b><?php echo number_format($T1, 0, ',', ' ');  ?></b></td>
        <td align="right"><b><?php echo number_format($T2, 0, ',', ' ');  ?></b></td>
		<td align="right"><b><?php   if($T0>0) echo number_format(100*$T1/$T0, 2, ',', ' ')."%"; ?></b></td>
                </tr>
<?php   } ?>
                </tbody>

            </table>
              </div>
            </div>

          <?php $j++; } ?>
        </div></div>
      </div>