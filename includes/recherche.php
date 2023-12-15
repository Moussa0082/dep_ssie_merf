<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & DÃ©veloppement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
$path = "../";
include_once $path.'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $path.$config->sys_folder . "/database/db_connexion.php";
header('Content-Type: text/html; charset=UTF-8');

$colors=explode(",","#7cb5ec,#434348,#90ed7d,#f7a35c,#8085e9,#f15c80,#e4d354,#2b908f,#f45b5b,#91e8e1");
$q = isset($_GET["q"])?$_GET["q"]:"";
if(!empty($q) && (isset($_GET["indic"]) && $_GET["indic"]=="true")){
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_recherche = sprintf("SELECT r.id_ref_ind, r.code_ref_ind, r.intitule_ref_ind, r.echelle, r.domaine, s.nom_domaine, s.id_domaine FROM referentiel_indicateur r, domaine_activite s WHERE s.code_domaine=r.domaine and (r.intitule_ref_ind LIKE %s OR r.code_ref_ind LIKE %s)",GetSQLValueString("%$q%", "text"),GetSQLValueString("%$q%", "text"));
$liste_recherche  = mysql_query($query_liste_recherche , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_recherche  = mysql_fetch_assoc($liste_recherche);
$totalRows_liste_recherche= mysql_num_rows($liste_recherche); } else $totalRows_liste_recherche=0;
if(!empty($q) && (isset($_GET["docs"]) && $_GET["docs"]=="true")){
$dir = (isset($_GET['dir']) && !empty($_GET['dir']))?$_GET['dir']:'../attachment/';
$d = $dir; $files = $files_dir = $docs = $row_liste_recherche_docs = array(); $totalRows_liste_recherche_docs = 0;
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($d));
//$files = array();
foreach ($rii as $file) {
    if ($file->isDir()){
        continue;
    }
    $file = str_replace("\\","/",$file->getPathname());
    $a = explode("/",$file);
    if(stristr($a[count($a)-1],$q)){
      $totalRows_liste_recherche_docs++; $row_liste_recherche_docs[] = $a[count($a)-1];  unset($a[count($a)-1]);
      $a[0] = "."; $files[] = implode("/",$a)."/"; $files_dir[] = "./liste_document.php?"; unset($a[0],$a[1]); $docs[] = implode('</span><span style="margin-right:10px;"><i class="icon-folder" style="margin-right:5px;"></i>',$a)."</span>";
    }
}
$d = '../documents/'.$_SESSION['clp_id'].'/'; //$files = $docs = $row_liste_recherche_docs = array(); $totalRows_liste_recherche_docs = 0;
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($d));
//$files = array();
foreach ($rii as $file) {
    if ($file->isDir()){
        continue;
    }
    $file = str_replace("\\","/",$file->getPathname());
    $a = explode("/",$file);
    if(stristr($a[count($a)-1],$q)){
      $totalRows_liste_recherche_docs++; $row_liste_recherche_docs[] = $a[count($a)-1];  unset($a[count($a)-1]);
      $a[0] = "."; $files[] = implode("/",$a)."/"; $files_dir[] = "./my_folder.php?"; unset($a[0],$a[1]); $docs[] = implode('</span><span style="margin-right:10px;"><i class="icon-folder" style="margin-right:5px;"></i>',$a)."</span>";
    }
}
} else $totalRows_liste_recherche_docs=0;
?>
<style>.separe_folder>span+span:before {font-family: FontAwesome;font-weight: normal;font-style: normal;text-decoration: inherit;-webkit-font-smoothing: antialiased;content: "\f105";margin-right: 5px;}.large-text-special {font-size: 45px!important;}</style>
<p class="bold"><center><h3>R&eacute;sultat de la recherche</h3></center></p>
<?php if((isset($_GET["indic"]) && $_GET["indic"]=="true")){ ?>
<h3 style="border-bottom: solid 1px #090;" onclick='$("#indic").toggle("slide", { direction: "up" }, 500);'>Dans indicateurs <small>(Cliquez pour afficher/masquer)</small> :</h3>
<div class="row" class="indic" id="indic">
<?php if($totalRows_liste_recherche>0){ ?>
<?php $i=0; do{ ?>
<div class="col-md-6" style="overflow: hidden;">
<div class="thumbnail-wrapper d48 circular text-white inline m-t-10 m-r-10" style="background-color: <?php /*$a = rand(0,count($colors)-1); */echo $colors[$i]; ?>"><a href="./suivi_referentiel.php?<?php echo /*"sous_secteur=".$row_liste_recherche["id_sous_secteur"]."&domaine=".$row_liste_recherche["domaine"].*/"&indicateur=".$row_liste_recherche["id_ref_ind"]; ?>"><div>&nbsp;</div></a></div>
<div class="p-l-10 inline p-t-5 pull-left col-md-10" style="white-space: nowrap;text-overflow: ellipsis;"><a href="./suivi_referentiel.php?<?php echo /*"sous_secteur=".$row_liste_recherche["id_sous_secteur"]."&domaine=".$row_liste_recherche["domaine"].*/"&indicateur=".$row_liste_recherche["id_ref_ind"]; ?>">
<h5 class="m-b-5"><span class="semi-bold result-name"><?php echo "<b>".$row_liste_recherche["code_ref_ind"]."</b> : ".$row_liste_recherche["intitule_ref_ind"]." (<i style='font-size:x-small;'>Echelle : <b>".$row_liste_recherche["echelle"]."</b></i>)"; ?></span></h5>
<p class="hint-text">dans "<b><?php echo $row_liste_recherche["nom_domaine"]; ?></b>"</p>
</a></div>
</div>
<?php $i++; if($i==count($colors)-1) $i=0; }while($row_liste_recherche  = mysql_fetch_assoc($liste_recherche)); ?>
<?php }else{ ?><h1 align="center" style="color:red;">Aucun r&eacute;sultat !</h1><?php } ?>
</div>
<?php } ?>
<?php if((isset($_GET["docs"]) && $_GET["docs"]=="true")){ ?>
<h3 style="border-bottom: solid 1px #090;" onclick='$("#docs").toggle("slide", { direction: "up" }, 500);'>Dans documents <small>(Cliquez pour afficher/masquer)</small> :</h3>
<div class="row" class="docs" id="docs">
<?php if($totalRows_liste_recherche_docs>0){ ?>
<?php $i=0; foreach($row_liste_recherche_docs as $j=>$name){ ?>
<div class="col-md-6" style="overflow: hidden;">
<div class="thumbnail-wrapper d48 circular text-white inline m-t-10 m-r-10"><a href="./liste_document.php?<?php echo "dir=".$files[$j]."&link=".$name; ?>"><div><i class="icon icon-file large-text-special"></i></div></a></div>
<div class="p-l-10 inline p-t-5 pull-left col-md-10" style="white-space: nowrap;text-overflow: ellipsis;"><a href="<?php echo $files_dir[$j]."dir=".$files[$j]."&link=".$name; ?>">
<h5 class="m-b-5"><span class="semi-bold result-name"><?php echo $name; ?></span></h5>
<p class="hint-text separe_folder"><span style="margin-right:10px;"><i class="icon-folder" style="margin-right:5px;"></i><?php echo $docs[$j]; ?></span></p>
</a></div>
</div>
<?php $i++; if($i==count($colors)-1) $i=0; } ?>
<?php }else{ ?><h1 align="center" style="color:red;">Aucun r&eacute;sultat !</h1><?php } ?>
</div>
<?php } ?>