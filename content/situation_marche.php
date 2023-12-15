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
header('Content-Type: text/html; charset=ISO-8859-15');

//liste methode
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_convention = "SELECT * FROM ".$database_connect_prefix."type_part WHERE  projet='".$_SESSION["clp_projet"]."' ";
$liste_convention  = mysql_query_ruche($query_liste_convention , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_convention  = mysql_fetch_assoc($liste_convention);
$totalRows_liste_convention  = mysql_num_rows($liste_convention);

 mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_cat_depense= "SELECT code, nom_categorie FROM ".$database_connect_prefix."categorie_depense order by code";
  $liste_cat_depense = mysql_query_ruche($query_liste_cat_depense, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $tableauCatDepense=array();
  $nbcatdep=0;
  while($lignecat_depense=mysql_fetch_assoc($liste_cat_depense)){$tableauCatDepense[]=$lignecat_depense['code']."<>".$lignecat_depense['nom_categorie']; $nbcatdep=$nbcatdep+1;}
  mysql_free_result($liste_cat_depense);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cout_cat = "SELECT annee, code_categorie, SUM( if(cout_prevu>0, cout_prevu,0) ) AS prevu, SUM( if(cout_realise>0, cout_realise,0) ) AS realise, SUM( if(cout_engage>0, cout_engage,0) ) AS engage FROM ".$database_connect_prefix."code_analytique group by annee, code_categorie";
$liste_cout_cat = mysql_query_ruche($query_liste_cout_cat, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_cout_cat = mysql_fetch_assoc($liste_cout_cat);
$totalRows_liste_cout_cat = mysql_num_rows($liste_cout_cat);
$prevu_cat_array = array();
$realise_cat_array = array();
$engage_cat_array = array();
if($totalRows_liste_cout_cat>0){
do{
 $prevu_cat_array[$row_liste_cout_cat["annee"]][$row_liste_cout_cat["code_categorie"]]=$row_liste_cout_cat["prevu"];
 $realise_cat_array[$row_liste_cout_cat["annee"]][$row_liste_cout_cat["code_categorie"]]=$row_liste_cout_cat["realise"];
 $engage_cat_array[$row_liste_cout_cat["annee"]][$row_liste_cout_cat["code_categorie"]]=$row_liste_cout_cat["engage"];
  }
while($row_liste_cout_cat  = mysql_fetch_assoc($liste_cout_cat));}
?>

<div class="widget-content">
<table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable" align="center" >
      <thead>
        <tr>
          <td rowspan="2"><div align="left"><strong>Code</strong></div></td>
		   <?php foreach($tableauCatDepense as $vcatdepense){?>
                <td colspan="2"><div align="center"><strong>
            <?php
                $avcatdepense = explode('<>',$vcatdepense);
                $ivcatdepense = $avcatdepense[0]; echo $avcatdepense[0];
            ?>
               </strong></div></td>
			    <td rowspan="2">&nbsp;</td>
                <?php } ?>

          <td rowspan="2"><div align="center"><strong>Total d&eacute;caiss&eacute; </strong></div></td>
          <td rowspan="2"><div align="center"><strong>Taux</strong></div></td>
        </tr>

        <tr>
		 <?php foreach($tableauCatDepense as $vcatdepense){?>
               <td><div align="left"><strong>D&eacute;caissement</strong></div></td>
          <td><div align="left"><strong>Taux</strong></div></td>
		   <?php } ?>
          </tr>
      </thead>
      <?php if($totalRows_liste_convention>0) {$i=0;do { $id = $row_liste_convention['code_type'];  ?>

      <tr>
        <td><div align="left"><?php echo $row_liste_convention['code_type'].": ".$row_liste_convention['intitule']; ?></div></td>
		 <?php foreach($tableauCatDepense as $vcatdepense){?>
		  <?php
                $avcatdepense = explode('<>',$vcatdepense);
                $ivcatdepense = $avcatdepense[0];
				$code_catc=$ivcatdepense."".$id;
            ?>
              <td><div align="left"><?php if(isset($realise_cat_array[2015][$code_catc])) echo $realise_cat_array[2015][$code_catc]; ?></div></td>
        <td>&nbsp;</td>
		 <td>&nbsp;</td>
                <?php } ?>

        <td ><?php
	//$lc = explode(",", $row_liste_methode['categorie_concerne']); 	$lcategorie=implode("','", $lc);
	//echo $row_liste_convention['etape_concerne'];
 ?></td>
        <td >&nbsp;</td>
      </tr>
      <?php }
	  while ($row_liste_convention = mysql_fetch_assoc($liste_convention));
	  $rows = mysql_num_rows($liste_convention);
if($rows > 0) {
mysql_data_seek($liste_convention, 0);
$row_liste_convention = mysql_fetch_assoc($liste_convention);
} ?>
                  <?php } ?>
    </table>
    </div>