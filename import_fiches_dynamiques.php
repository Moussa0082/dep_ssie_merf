<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"]) || !isset($_GET['classeur']) || !isset($_GET['feuille'])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
header('Content-Type: text/html; charset=UTF-8');

$interdit_array = array("classeur","LKEY","annee","projet","structure","id_personnel","date_enregistrement","modifier_le","modifier_par","etat");

if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else {$annee=date("Y");}
if(isset($_GET['feuille'])) {$feuille=$_GET['feuille'];}
if(isset($_GET['classeur'])) {$classeur=$_GET['classeur'];}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$feuille'";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);

if($totalRows_entete>0){ $choix_array = array(); $nomT=$row_entete["nom"]; $note=$row_entete["note"]; $entete_array=explode("|",$row_entete["show"]); $libelle=explode("|",$row_entete["libelle"]);
$intitule=$row_entete["intitule"]; $colonne=$row_entete["colonnes"];
if(!empty($row_entete["choix"])){ foreach(explode("|",$row_entete["choix"]) as $elem){ if(!empty($elem)){  $a=explode(";",$elem); $choix_array[$a[0]]=""; for($i=1;$i<count($a);$i++){ $choix_array[$a[0]].=(!empty($a[$i]))?$a[$i].";":""; } }   }  } }

$count = count($libelle)-2;
$count = explode("=",$libelle[$count]);
$lib_nom_fich = "";
if(isset($count[1]))
$lib_nom_fich = $count[1];
elseif(isset($count[0]))
$lib_nom_fich = $count[0];

if(empty($lib_nom_fich)) $lib_nom_fich = $feuille;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "DESCRIBE ".$database_connect_prefix."$feuille";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);
$num=0;
if($totalRows_entete>0){ do{ if(in_array($row_entete["Field"],$entete_array)) $num++; }while($row_entete  = mysql_fetch_assoc($entete));  }

$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);

$libelle_array = array();
foreach($libelle as $a) { $b = explode('=',$a); if(isset($b[0])) $libelle_array[$b[0]]=(isset($b[1]))?$b[1]:"ND"; }
}
?>
<style>
#form0 th { font-size:10px; }
</style>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form0").validate();
        $(".modal-dialog", window.parent.document).width(840);
	});
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) { ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Importation</h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form0" id="form0" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr>
      <td valign="middle">
        <div class="form-group">
          <label for="fichier" class="col-md-12 control-label">Fichier &agrave; importer <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control required" type="file" name="fichier" id="fichier" value="" size="32" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
          </div>
        </div>
      </td>
      <td valign="middle">
        <div class="form-group">
          <label for="erase" class="col-md-12 control-label">Option sur les données existantes <span class="required">*</span></label>
          <div class="col-md-6">
            <select name="erase" id="erase" class="form-control required" onchange="a=this.value;">
              <option value="0">Conserver</option>
              <option value="1">Ecraser</option>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr><td colspan="2">

<h4>Format &agrave; respecter :</h4>
<table id="mtable" class="table table-striped table-bordered table-hover table-responsive dataTable " align="center" >
<?php if(!empty($intitule) && !empty($colonne)){ ?>
<thead>
<tr role="row">
<?php
$colonnes = $intitules = $colo_show = array();
$col = explode('|',$colonne);
$intitules = explode('|',$intitule);
foreach($col as $a){ $b = explode(';',$a); foreach($b as $c) if(!in_array($c,$colonnes) && !empty($c)) array_push($colonnes,$c); }
if($totalRows_entete>0){ $i=$k=0; do{
/*if(isset($libelle[$k])){
$lib=explode("=",$libelle[$k]);
$libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";   } */
if(!in_array($row_entete["Field"],$colonnes) && $row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array)){  $colo_show[]=$row_entete["Field"]; ?>
<th rowspan="2" class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize" align="center"><?php echo (isset($libelle_array[$row_entete["Field"]]))?$libelle_array[$row_entete["Field"]]:str_replace("_"," ",$row_entete["Field"]); ?></div></th>
<?php }elseif($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array)){
if(isset($col[$i])){ $b = explode(';',$col[$i]); $colspan = count($b)-1; }
if($colspan>0){ ?>
<th colspan="<?php echo $colspan; ?>" class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize" align="center"><?php echo (isset($intitules[$i]))?$intitules[$i]:((isset($libelle_array[$row_entete["Field"]]))?$libelle_array[$row_entete["Field"]]:""); ?></div></th>
<?php } $i++; for($j=1;$j<$colspan;$j++) $row_entete = mysql_fetch_assoc($entete);  } $k++; }while($row_entete  = mysql_fetch_assoc($entete)); }
$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}  ?>
</tr>

<!--second ligne-->
<tr role="row">
<?php
if($totalRows_entete>0){ $i=0; do{

/*if(isset($libelle[$i])){
$lib=explode("=",$libelle[$i]);
$libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";   } */
if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array)) $i++;
if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array) && !in_array($row_entete["Field"],$colo_show)){ ?>
<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize" align="center"><?php echo (isset($libelle_array[$row_entete["Field"]]))?$libelle_array[$row_entete["Field"]]:str_replace("_"," ",$row_entete["Field"]); ?></div></th>
<?php }  }while($row_entete  = mysql_fetch_assoc($entete)); }
$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}  ?>

</tr>
</thead>
<?php }else{ ?>
<thead>
<tr role="row">
<?php
if($totalRows_entete>0){ $i=0; do{

/*if(isset($libelle[$i])){
$lib=explode("=",$libelle[$i]);
$libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";   } */

if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array)){ ?>
<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize" align="center"><?php echo (isset($libelle_array[$row_entete["Field"]]))?$libelle_array[$row_entete["Field"]]:str_replace("_"," ",$row_entete["Field"]); ?></div></th>
<?php $i++; }  }while($row_entete  = mysql_fetch_assoc($entete)); }
$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}  ?>
</tr></thead>
<?php } ?>
</table>
    </td></tr>
</table>

<div class="form-actions">
  <input name="classeur" type="hidden" value="<?php echo $classeur; ?>" size="32" alt="">
  <input name="feuille" type="hidden" value="<?php echo $feuille; ?>" size="32" alt="">
  <input name="annee" type="hidden" value="<?php echo $annee; ?>" size="32" alt="">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="Lancer l'Importation" onclick="if(a==1) {return confirm('L\'importation &eacute;crasera les données pr&eacute;c&eacute;damment enregistr&eacute;es. Continuez ?'); }" />
  <input name="MM_insert" type="hidden" value="MM_insert" size="32" alt="">
  <input name="MM_form" id="MM_form" type="hidden" value="<?php if(isset($_GET["form"])) echo $_GET["form"]; else echo "form0" ?>" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>