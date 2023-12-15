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
//$annee_c=date("Y");
if(isset($annee_c)) $annee_c=$annee_c; else $annee_c=date("Y");


$query_liste_code_budgetp = "
SELECT 'activite' as type, annee,libelle FROM ".$database_connect_prefix."code_activite WHERE code='fichiers'
union 
SELECT 'categorie' as type, annee,libelle FROM ".$database_connect_prefix."code_categorie WHERE code='fichiers'
union
SELECT 'convention' as type, annee,libelle FROM ".$database_connect_prefix."code_convention WHERE code='fichiers'";
  try{
    $liste_code_budgetp = $pdar_connexion->prepare($query_liste_code_budgetp);
    $liste_code_budgetp->execute();
    $row_liste_code_budgetp = $liste_code_budgetp ->fetchAll();
    $totalRows_liste_code_budgetp = $liste_code_budgetp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$fichier_array = array();
if($totalRows_liste_code_budgetp>0){
foreach($row_liste_code_budgetp as $row_liste_code_budgetp){
 $fichier_array[$row_liste_code_budgetp["type"]][$row_liste_code_budgetp["annee"]]=$row_liste_code_budgetp["libelle"];
  }}

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

          <?php //for($j=1;$j<=4;$j++){ ?>

		  <?php //for($j=$_SESSION["annee_debut_projet"];$j<=date("Y");$j++){ if($j<=$_SESSION["annee_fin_projet"]) { $anp=$j; ?>
		   <?php $j=0; foreach($tableauAnnee as $anp){ ?>

          <li title="Ann&eacute;e <?php echo $anp; ?>" class="<?php echo ($anp==$annee_c)?"active":""; ?>"><a href="#tab_imb_<?php echo $j; ?>" data-toggle="tab">  <?php if(isset($version_array[$anp])) echo $version_array[$anp]; else echo $anp; ?><?php //echo $anp; ?></a></li>

          <?php $j++; } ?>

        </ul>

        <div class="tab-content">

		 <?php //for($j=$_SESSION["annee_debut_projet"];$j<=date("Y");$j++){ if($j<=$_SESSION["annee_fin_projet"]) { $anp=$j; ?>
		  <?php $j=0; foreach($tableauAnnee as $anp){ ?>

          <?php //for($j=1;$j<=4;$j++){ ?>

          <div class="tab-pane <?php echo ($anp==$annee_c)?"active":""; ?>" id="tab_imb_<?php echo $j; ?>">



<div class="col-md-6">

<div align="center" style="background-color:#FFFF99"><?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) echo do_link("","","Importation du budget par activt&eacute;","<i class=\"\"> Activit&eacute;s PTBA  </i>","simple","../","p11","get_content('modal_content/import_cout_ptba.php','annee=$anp','modal-body_add',this.title,'iframe');",1,"","analyse_budgetaire.php"); ?><br><?php if(isset($fichier_array['activite'][$anp])){ ?><a href="<?php echo (isset($fichier_array['activite'][$anp]))?substr($fichier_array['activite'][$anp],1):""; ?>"><img src="images/xls.png" width="30" height="30" alt="" style="padding:5px;" /></a><?php } else echo "<h3>Aucun budget import&eacute; </h3>" ?></div>
</div>
</br>
<div class="col-md-6">
<div align="center" style="background-color:#CCFF99"><?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) echo do_link("","","Importation du budget par cat&eacute;gorie","<i class=\"\"> Cat&eacute;gories financi&egrave;res  </i>","simple","../","p11","get_content('modal_content/import_cout_categorie.php','annee=$anp','modal-body_add',this.title,'iframe');",1,"","analyse_budgetaire.php"); ?><br><?php if(isset($fichier_array['categorie'][$anp])){ ?><a href="<?php echo (isset($fichier_array['categorie'][$anp]))?substr($fichier_array['categorie'][$anp],1):""; ?>"><img src="images/xls.png" width="30" height="30" alt="" style="padding:5px;" /></a><?php } else echo "<h3>Aucun budget import&eacute; </h3>" ?></div>
</div>
</br>
<div class="col-md-6">
<div align="center" style="background-color:#99FFFF"><?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) echo do_link("","","Importation du budget par convention","<i class=\"\"> Convention de financement  </i>","simple","../","p11","get_content('modal_content/import_cout_convention.php','annee=$anp','modal-body_add',this.title,'iframe');",1,"","analyse_budgetaire.php"); ?><br><?php if(isset($fichier_array['convention'][$anp])){ ?><a href="<?php echo (isset($fichier_array['convention'][$anp]))?substr($fichier_array['convention'][$anp],1):""; ?>"><img src="images/xls.png" width="30" height="30" alt="" style="padding:5px;" /></a><?php } else echo "<h3>Aucun budget import&eacute; </h3>" ?></div>
</div>

</br>
<div class="col-md-6">
<div align="center" style="background-color:#FFCCCC"><?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) echo do_link("","","Importation du budget par budget","<i class=\"\"> Categorie budgetaire  </i>","simple","../","p11","get_content('modal_content/import_cout_budget.php','annee=$anp','modal-body_add',this.title,'iframe');",1,"","analyse_budgetaire.php"); ?><br><?php if(isset($fichier_array['convention'][$anp])){ ?><a href="<?php echo (isset($fichier_array['convention'][$anp]))?substr($fichier_array['convention'][$anp],1):""; ?>"><img src="images/xls.png" width="30" height="30" alt="" style="padding:5px;" /></a><?php } else echo "<h3>Aucun budget import&eacute; </h3>" ?></div>
</div>

         

          </div>

           <?php $j++; } ?>

        </div>

      </div>