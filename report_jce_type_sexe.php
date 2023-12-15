<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & DÃƒÂ©veloppement: SEYA SERVICES */
///////////////////////////////////////////////
//session_start();
include_once 'system/configuration.php';
$config = new Config;

/*if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}*/
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');
$array_indic = array("OUI/NON","texte");
//number_format(0, 0, ',', ' ');
?>
<?php
$tab_cout =$tache =$indicateur = array();
if(isset($_GET["annee"])) $annee = intval($_GET["annee"]); else $annee = date("Y")-1;
if(isset($_GET["vers"])) $anneevers = intval($_GET["vers"]); else $anneevers = date("Y");
if(isset($_GET["cmp"])) $cmp = $_GET["cmp"]; else $cmp = "%";

 /* $query_liste_versiona = "SELECT * FROM ".$database_connect_prefix."version_ptba ORDER BY annee_ptba desc, date_validation desc limit 1";
try{
    $liste_versiona = $pdar_connexion->prepare($query_liste_versiona);
    $liste_versiona->execute();
    $row_liste_versiona = $liste_versiona ->fetchAll();
    $totalRows_liste_versiona = $liste_versiona->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$TableauVersionPA = array(); //$version_array = array();
if($totalRows_liste_versiona>0){ foreach($row_liste_versiona as $row_liste_versiona){
$TableauVersionPA[$row_liste_versiona["id_version_ptba"]]=$row_liste_versiona["annee_ptba"]." ".$row_liste_versiona["version_ptba"];
$annee=$row_liste_versiona["id_version_ptba"];
$anneevers=$row_liste_versiona["annee_ptba"];
 } }
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_activite = "SELECT id,code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=1 and projet='2000000882' order by code asc";
$liste_activite  = mysql_query_ruche($query_liste_activite, $pdar_connexion) or die(mysql_error());
$row_liste_activite  = mysql_fetch_assoc($liste_activite);
$totalRows_liste_activite  = mysql_num_rows($liste_activite);
$composante = array();
$id_composante = array();
if($totalRows_liste_activite>0){
  do{
    $composante[$row_liste_activite["code"]] = "'".((isset($libelle[0])?$libelle[0]:"Composante"))." ".$row_liste_activite["code"]."',";
    $id_composante[$row_liste_activite["code"]]=$row_liste_activite["id"];
	 $tab_cout[$row_liste_activite["code"]]=$tache[$row_liste_activite["code"]]=$indicateur[$row_liste_activite["code"]]=0;
  }while($row_liste_activite  = mysql_fetch_assoc($liste_activite));
}
$rows = mysql_num_rows($liste_activite);*/
	// $taux_cout=$taux_tache=$taux_indicateur=0;
   
//Nombre d'activités
//if(isset($nb_code[0])){
  $query_liste_actpa = "SELECT count(distinct col23) as nb_bene, SUM(if(col6 = 'Homme', 1, 0)) as nb_h, SUM(if(col6 = 'Femme', 1, 0)) as nb_f, SUM(if(col20= 'Oui', 1, 0)) as nb_contrat, SUM(if(col7>15 && col7<46, 1, 0)) as nb_j, Login FROM t_1646237257 group by Login";
try{
    $liste_actpa = $pdar_connexion->prepare($query_liste_actpa);
    $liste_actpa->execute();
    $row_liste_actpa = $liste_actpa ->fetchAll();
    $totalRows_liste_actpa = $liste_actpa->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tab_contrat =$tab_BeneType=$tab_BeneH =$tab_BeneF=$tab_BeneJ =$tab_Type = array();
if($totalRows_liste_actpa>0){ foreach($row_liste_actpa as $row_liste_actpa){
	 $tab_BeneH[$row_liste_actpa["Login"]]=$row_liste_actpa["nb_h"];
 	 $tab_BeneType[$row_liste_actpa["Login"]]=$row_liste_actpa["nb_bene"];
	 $tab_BeneF[$row_liste_actpa["Login"]]=$row_liste_actpa["nb_f"];
 	 $tab_BeneJ[$row_liste_actpa["Login"]]=$row_liste_actpa["nb_j"];
	 $tab_contrat[$row_liste_actpa["Login"]]=$row_liste_actpa["nb_contrat"];
 	 $tab_Type[$row_liste_actpa["Login"]]=$row_liste_actpa["Login"];
} }//suivi du budget
//if(isset($cmp) && $cmp=='%') {
/*$query_liste_couta = "SELECT SUM( if(cout_prevu>0, cout_prevu,0) ) AS prevu, SUM( if(cout_realise>0, cout_realise,0) ) AS realise, SUM( if(cout_engage>0, cout_engage,0) ) AS engage FROM ".$database_connect_prefix."code_activite WHERE ".$database_connect_prefix."code_activite.projet='2000000882'  and annee=$annee and code!='Code' and code!='fichiers'";
try{
    $liste_couta = $pdar_connexion->prepare($query_liste_couta);
    $liste_couta->execute();
    $row_liste_couta = $liste_couta ->fetchAll();
    $totalRows_liste_couta = $liste_couta->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_liste_couta>0){ foreach($row_liste_couta as $row_liste_couta){
if($row_liste_couta["prevu"]>0) $taux_cout=100*($row_liste_couta["realise"]/$row_liste_couta["prevu"]);
} }*/

?>
<!DOCTYPE HTML>
<html>
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>B&eacute;n&eacute;ficiaire par p&ocirc;le et par type</title>
		  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
		  
		  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>
		  
  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder; ?>/login.css" rel="stylesheet" type="text/css"/>

  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
          <link href='<?php print $config->theme_folder; ?>/css.css' rel='stylesheet' type='text/css'>
        <style type="text/css">
<!--
.table {  border-spacing: 0px !important; border-collapse: collapse;
}
-->
        </style>
</head>
	<body>
	<table class="table table-striped table-hover" id="mtable">
      <thead>
        <tr>
          <th colspan="8"><div align="center">Nombre de JCE par animateur et par sexe </div></th>
        </tr>
        <tr>
          <th>&nbsp;</th>
		    <?php //foreach($tab_Pole as $a1=>$b1){ ?>
          <th class="center"> <div align="right">Hommes</div>          </th>
		    <th class="center"><div align="right">Femmes</div></th>
		    <th class="center"><div align="right">Dont Jeunes </div></th>
		    <?php //} ?>
          <th class="center"><div align="right">Total (H+F) </div></th>
          <th class="center">Disposant d'un contrat </th>
        </tr>
      </thead>
      <tbody>
	    <?php foreach($tab_Type as $a=>$b){ ?>
        <tr>
          <td> <?php echo $b  ?></td>
            <?php //foreach($tab_Pole as $a1=>$b1){ ?>
          <td nowrap class="center"> <div align="right">
            <?php if(isset($tab_BeneH[$b])) echo number_format($tab_BeneH[$b], 0, ',', ' '); ?>
          </div></td>
		    <td nowrap class="center"><div align="right">
              <?php if(isset($tab_BeneF[$b])) echo number_format($tab_BeneF[$b], 0, ',', ' '); ?>
            </div></td>
		    <td nowrap class="center"><div align="right">
		      <?php if(isset($tab_BeneJ[$b])) echo number_format($tab_BeneJ[$b], 0, ',', ' '); ?>
		    </div></td>
		    <?php //} ?>
          <td nowrap class="center"><div align="right">
            <?php if(isset($tab_BeneType[$b])) echo number_format($tab_BeneType[$b], 0, ',', ' '); ?>
          </div></td>
          <td nowrap class="center"><div align="right">
              <?php if(isset($tab_contrat[$b])) echo number_format($tab_contrat[$b], 0, ',', ' '); ?>
          </div></td>
        </tr>
		  <?php } ?>
      </tbody>
     
        <tr>
          <th>Total</th>
            <?php $totalgb=0; //foreach($tab_Pole as $a1=>$b1){ ?>
          <th nowrap class="center"><div align="right">
            <?php if(array_sum($tab_BeneH)>0) {echo number_format(array_sum($tab_BeneH), 0, ',', ' ');$totalgb+=array_sum($tab_BeneH); } ?>
          </div></th>
		    <th nowrap class="center"><div align="right">
		      <?php if(array_sum($tab_BeneF)>0) {echo number_format(array_sum($tab_BeneF), 0, ',', ' ');$totalgb+=array_sum($tab_BeneF); } ?>
		    </div></th>
		    <th nowrap class="center"><div align="right">
		      <?php if(array_sum($tab_BeneJ)>0) {echo number_format(array_sum($tab_BeneJ), 0, ',', ' '); } ?>
		    </div></th>
		    <?php //} ?>
          <th nowrap class="center"><div align="right">
            <?php echo number_format($totalgb, 0, ',', ' '); ?>
          </div></th>
          <th nowrap class="center"><div align="right">
              <?php if(array_sum($tab_contrat)>0) {echo number_format(array_sum($tab_contrat), 0, ',', ' '); } ?>
          </div></th>
        </tr>
    </table>
	</body>
</html>