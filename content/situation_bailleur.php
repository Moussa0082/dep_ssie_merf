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
//liste methode
$query_liste_convention = "SELECT * FROM ".$database_connect_prefix."type_part WHERE  projet='".$_SESSION["clp_projet"]."' order by code_type ";
  try{
    $liste_convention = $pdar_connexion->prepare($query_liste_convention);
    $liste_convention->execute();
    $row_liste_convention = $liste_convention ->fetchAll();
    $totalRows_liste_convention = $liste_convention->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
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
//mysql_free_result($liste_annee);
if(isset($annee_c)) $annee_c=$annee_c; else $annee_c=date("Y");
$query_liste_cout = "SELECT SUM( if(cout_prevu>0, cout_prevu,0) ) as prevu, SUM( if(cout_realise>0, cout_realise,0) ) as realise, SUM( if(cout_engage>0, cout_engage,0)) AS engage, annee, code  FROM ".$database_connect_prefix."code_convention where ".$database_connect_prefix."code_convention.projet='".$_SESSION["clp_projet"]."' group by annee, code";
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
 $prevu_array[$row_liste_cout["annee"]][$row_liste_cout["code"]]=$row_liste_cout["prevu"];
 $realise_array[$row_liste_cout["annee"]][$row_liste_cout["code"]]=$row_liste_cout["realise"];
 $engage_array[$row_liste_cout["annee"]][$row_liste_cout["code"]]=$row_liste_cout["engage"];
  }}
	$query_liste_bailleur = "SELECT intitule, code_type from ".$database_connect_prefix."type_part where ".$database_connect_prefix."type_part.projet='".$_SESSION["clp_projet"]."' ORDER BY code_type asc";
	  try{
    $liste_bailleur = $pdar_connexion->prepare($query_liste_bailleur);
    $liste_bailleur->execute();
    $row_liste_bailleur = $liste_bailleur ->fetchAll();
    $totalRows_liste_bailleur = $liste_bailleur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
	$bailleur_array = array();
    if($totalRows_liste_bailleur>0){
foreach($row_liste_bailleur as $row_liste_bailleur){ $bailleur_array[$row_liste_bailleur["code_type"]]=$row_liste_bailleur["code_type"].": ".$row_liste_bailleur["intitule"];  }}
$query_liste_region = "SELECT * FROM partenaire";
  try{
    $liste_region = $pdar_connexion->prepare($query_liste_region);
    $liste_region->execute();
    $row_liste_region = $liste_region ->fetchAll();
    $totalRows_liste_region = $liste_region->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$region_array = array();
if($totalRows_liste_region>0){
foreach($row_liste_region as $row_liste_region){ $region_array[intval($row_liste_region["code"])]=$row_liste_region["sigle"]; }}
$query_liste_categorie= "SELECT code, nom_categorie FROM ".$database_connect_prefix."categorie_depense order by convention_concerne, code";
  try{
    $liste_categorie = $pdar_connexion->prepare($query_liste_categorie);
    $liste_categorie->execute();
    $row_liste_categorie = $liste_categorie ->fetchAll();
    $totalRows_liste_categorie = $liste_categorie->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$commune_array = array();
if($totalRows_liste_categorie>0){
foreach($row_liste_categorie as $row_liste_categorie){ $commune_array[intval($row_liste_categorie["code"])]=$row_liste_categorie["nom_categorie"]; }}
$taux_annuel=0;
$diff=0;

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

      <div class="tabbable tabbable-custom" >
        <ul class="nav nav-tabs" >
		   <?php $j = 0; foreach($tableauAnnee as $anp){ ?>
          <li title="Ann&eacute;e <?php echo $anp; ?>" class="<?php echo ($anp==$annee_c || (!in_array($anp,$tableauAnnee) && $j==0))?"active":""; ?>"><a href="#tab_stb_<?php echo $j; ?>" data-toggle="tab"> <?php if(isset($version_array[$anp])) echo $version_array[$anp]; else echo $anp; ?></a></li>
          <?php $j++; } ?>
        </ul>
        <div class="tab-content">
		 <?php $j = 0;foreach($tableauAnnee as $anp){ ?>
          <div class="tab-pane <?php echo ($anp==$annee_c || (!in_array($anp,$tableauAnnee) && $j==0))?"active":""; ?>" id="tab_stb_<?php echo $j; ?>">
<div class="col-md-6">
            <div class="scroller">
          <table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable" align="center" >

      <thead>
        <tr>
          <td><div align="left"><strong>Convention</strong></div></td>
          <td><div align="left"><strong>Pr&eacute;vu</strong></div></td>
          <td><div align="left"><strong>D&eacute;caiss&eacute;</strong></div></td>
		  <td><div align="left"><strong>Engag&eacute;</strong></div></td>
		   <td><b>%Exe</b></td>

        </tr>
      </thead>
      <?php if($totalRows_liste_convention>0) {$i=$T0=$T1=$T2=0;  foreach($row_liste_convention as $row_liste_convention1){ $totreal=0; //$id = $row_liste_convention['sigle']; ?>
      <tr>
        <td><div align="left">  <?php echo $row_liste_convention1['code_type'].": ".$row_liste_convention1['intitule']; ?></div></td>
        <td nowrap="nowrap"><div align="right"><?php if(isset($prevu_array[$anp][$row_liste_convention1["code_type"]]) && $prevu_array[$anp][$row_liste_convention1["code_type"]]>0){ echo number_format($prevu_array[$anp][$row_liste_convention1["code_type"]], 0, ',', ' '); $T0+=$prevu_array[$anp][$row_liste_convention1["code_type"]]; } ?></div></td>
        <td nowrap="nowrap" align="right"><?php if(isset($realise_array[$anp][$row_liste_convention1["code_type"]]) && $realise_array[$anp][$row_liste_convention1["code_type"]]>0) {echo number_format($realise_array[$anp][$row_liste_convention1["code_type"]], 0, ',', ' '); $totreal=$realise_array[$anp][$row_liste_convention1["code_type"]]; $T1+=$realise_array[$anp][$row_liste_convention1["code_type"]]; } ?></td>
        <td nowrap="nowrap" align="right"><?php if(isset($engage_array[$anp][$row_liste_convention1["code_type"]]) && $engage_array[$anp][$row_liste_convention1["code_type"]]>0) {echo number_format($engage_array[$anp][$row_liste_convention1["code_type"]], 0, ',', ' '); $totreal=$totreal+$engage_array[$anp][$row_liste_convention1["code_type"]]; $T2+=$engage_array[$anp][$row_liste_convention1["code_type"]];} ?></td>
		<td nowrap="nowrap" align="right"><?php if(isset($prevu_array[$anp][$row_liste_convention1["code_type"]]) && $prevu_array[$anp][$row_liste_convention1["code_type"]]>0 && isset($realise_array[$anp][$row_liste_convention1["code_type"]])) echo number_format(100*$realise_array[$anp][$row_liste_convention1["code_type"]]/$prevu_array[$anp][$row_liste_convention1["code_type"]], 2, ',', ' ')."%"; ?></td>
      </tr>
      <?php } ?>
      <tr>
        <td><div align="left"><b>Total</b></div></td>
        <td nowrap="nowrap"><div align="left"><b><?php echo number_format($T0, 0, ',', ' ');  ?></b></div></td>
        <td nowrap="nowrap" align="right"><b><?php echo number_format($T1, 0, ',', ' ');  ?></b></td>
        <td nowrap="nowrap" align="right"><b><?php echo number_format($T2, 0, ',', ' ');  ?></b></td>
		<td nowrap="nowrap" align="right"><b><?php  if($T0>0) echo number_format(100*$T1/$T0, 2, ',', ' ')."%"; ?></b></td>
      </tr>
      <?php } ?>
    </table>  </div>
</div>
<div class="col-md-6">
<?php
/*$tableauCp = array();
$tableauCoutCp = array();
$annee=$anp;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
//$query_taux_annee = "SELECT SUM( if(cout_prevu>0, cout_prevu,0) ) AS taux FROM ".$database_connect_prefix."code_analytique where  annee=$annee";
$query_taux_annee = "SELECT SUM( if(cout_prevu>0, cout_prevu,0) ) as taux  FROM ".$database_connect_prefix."code_convention where ".$database_connect_prefix."code_convention.projet='".$_SESSION["clp_projet"]."' and annee=$annee";
$taux_annee  = mysql_query_ruche($query_taux_annee , $pdar_connexion) or die(mysql_error());
$row_taux_annee  = mysql_fetch_assoc($taux_annee);
$totalRows_taux_annee  = mysql_num_rows($taux_annee);
mysql_select_db($database_pdar_connexion, $pdar_connexion);
//$query_liste_cp = "SELECT SUM( if(cout_prevu>0, cout_prevu,0) ) as ct, right(code_categorie,2) as cp  FROM ".$database_connect_prefix."code_analytique where ".$database_connect_prefix."code_analytique.projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."code_analytique.code_activite_ptba in(select code_activite_ptba from ".$database_connect_prefix."ptba where ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' and annee=$annee) and annee=$annee group by cp";
$query_liste_cp = "SELECT SUM( if(cout_prevu>0, cout_prevu,0) ) as ct, code as cp  FROM ".$database_connect_prefix."code_convention where ".$database_connect_prefix."code_convention.projet='".$_SESSION["clp_projet"]."' and annee=$annee and code!='Code' and code!='fichiers' group by cp";
$liste_cp  = mysql_query_ruche($query_liste_cp , $pdar_connexion) or die(mysql_error());
$row_liste_cp  = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp  = mysql_num_rows($liste_cp);
$taux_annuel=$row_taux_annee['taux'];
$cout=0;
//if($totalRows_liste_cp>0 && $taux_annuel>0){
$cout=$row_liste_cp['ct'];*/
?>
	

</div>
          </div>
          <?php $j++; } ?>
        </div>
      </div>