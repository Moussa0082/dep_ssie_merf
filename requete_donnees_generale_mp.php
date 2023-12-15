<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////

//UPDATE `ruche_pask`.`recommandation_mission` SET `mission` = '4' WHERE `recommandation_mission`.`mission` = '02'

session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=ISO-8859-15');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=recap_microprojet.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=recap_microprojet.doc"); }

$dir = './attachment/supervision/';
if(!is_dir($dir)) mkdir($dir);
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];




  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_crp = "SELECT * FROM ugl where code_ugl!='000'   order by code_ugl desc ";
 // $query_liste_mission = "SELECT * FROM ".$database_connect_prefix."mission_supervision WHERE code_ms='$id' and projet='".$_SESSION["clp_projet"]."' ";
  $liste_crp  = mysql_query_ruche($query_liste_crp , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_crp = mysql_fetch_assoc($liste_crp);
  $totalRows_liste_crp  = mysql_num_rows($liste_crp);
  
  //Nombre par CRP et par domaine
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_nb_op = "SELECT COUNT(distinct code_fiche) AS nmp, sum(famille_beneficiaires) as nbene,  sum(financement_global_reel) as cout, domaine_activite, code_crip as code_ugl  from fiche_generale_mp, departement where municipalite=code_departement group by code_ugl, domaine_activite";
$liste_nb_op = mysql_query_ruche($query_liste_nb_op, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_nb_op = mysql_fetch_assoc($liste_nb_op);
$totalRows_liste_nb_op = mysql_num_rows($liste_nb_op);
$nbre_array =$bene_array = $cout_array = array();
$nbre_domaine_array =$bene_domaine_array = $cout_domaine_array = array();
$nbre_crp_array =$bene_crp_array = $cout_crp_array = array();
$nbre_total_array =$bene_total_array = $cout_total_array = 0;
if($totalRows_liste_nb_op>0){  do{
//CRP et domaine d 'activites
   $nbre_array[$row_liste_nb_op["code_ugl"]][$row_liste_nb_op["domaine_activite"]] = $row_liste_nb_op["nmp"];
   $bene_array[$row_liste_nb_op["code_ugl"]][$row_liste_nb_op["domaine_activite"]] = $row_liste_nb_op["nbene"];
   $cout_array[$row_liste_nb_op["code_ugl"]][$row_liste_nb_op["domaine_activite"]] = $row_liste_nb_op["cout"];
	
// domaine d 'activites
if(!isset($nbre_domaine_array[$row_liste_nb_op["domaine_activite"]]))  $nbre_domaine_array[$row_liste_nb_op["domaine_activite"]]=0;
if(!isset($bene_domaine_array[$row_liste_nb_op["domaine_activite"]]))  $bene_domaine_array[$row_liste_nb_op["domaine_activite"]]=0;
if(!isset($cout_domaine_array[$row_liste_nb_op["domaine_activite"]]))  $cout_domaine_array[$row_liste_nb_op["domaine_activite"]]=0;
   $nbre_domaine_array[$row_liste_nb_op["domaine_activite"]] += $row_liste_nb_op["nmp"];
   $bene_domaine_array[$row_liste_nb_op["domaine_activite"]] += $row_liste_nb_op["nbene"];
   $cout_domaine_array[$row_liste_nb_op["domaine_activite"]] += $row_liste_nb_op["cout"];
 
//CRP 
if(!isset($nbre_crp_array[$row_liste_nb_op["code_ugl"]]))  $nbre_crp_array[$row_liste_nb_op["code_ugl"]]=0;
if(!isset($bene_crp_array[$row_liste_nb_op["code_ugl"]]))  $bene_crp_array[$row_liste_nb_op["code_ugl"]]=0;
if(!isset($cout_crp_array[$row_liste_nb_op["code_ugl"]]))  $cout_crp_array[$row_liste_nb_op["code_ugl"]]=0;
   $nbre_crp_array[$row_liste_nb_op["code_ugl"]] += $row_liste_nb_op["nmp"];
   $bene_crp_array[$row_liste_nb_op["code_ugl"]] += $row_liste_nb_op["nbene"];
   $cout_crp_array[$row_liste_nb_op["code_ugl"]] += $row_liste_nb_op["cout"];
   
//total
   $nbre_total_array += $row_liste_nb_op["nmp"];
   $bene_total_array += $row_liste_nb_op["nbene"];
   $cout_total_array += $row_liste_nb_op["cout"];
	
}while($row_liste_nb_op = mysql_fetch_assoc($liste_nb_op));  }
  
  //liste village
/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_village = "SELECT code_village, nom_village, nom_commune, nom_departement, nom_region
FROM village, commune, departement, region WHERE code_commune = commune AND code_departement = departement AND code_region = region ORDER BY  `region`.`nom_region` ASC";
$liste_village = mysql_query_ruche($query_liste_village, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_village = mysql_fetch_assoc($liste_village);
$totalRows_liste_village = mysql_num_rows($liste_village);
$village_array = array();
$commune_array = array();
$departement_array = array();
$region_array = array();
if($totalRows_liste_village>0){  do{
  $village_array[$row_liste_village["code_village"]] = $row_liste_village["nom_village"];
    $commune_array[$row_liste_village["code_village"]] = $row_liste_village["nom_commune"];
    $departement_array[$row_liste_village["code_village"]] = $row_liste_village["nom_departement"];
    $region_array[$row_liste_village["code_village"]] = $row_liste_village["nom_region"];

}while($row_liste_village = mysql_fetch_assoc($liste_village));  }*/

$tab_array = array('Agricultura','Pecuária','Pesca','Form Profissional','AGR Comercio Serviços','AGR Transformação','Habitação','Agua Potável','Saneamento','Educação','Outras Acções Sociais');

?>
<?php if(!isset($_GET["down"])){  ?>
<meta name="viewport" content="width=400, initial-scale=1.0">
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->
<link rel="stylesheet" type="text/css" href="<?php print $config->theme_folder;?>/plugins/jquery-ui.css"/>
<link href="<?php print $config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder;?>/responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder;?>/icons.css" rel="stylesheet" type="text/css"/>
<link href='<?php print $config->theme_folder;?>/css.css' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>
<script type="text/javascript" src="plugins/noty/layouts/top.js"></script>
<script type="text/javascript" src="plugins/noty/themes/default.js"></script>
<script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>
<?php } ?>
<!--<script type="text/javascript" src="plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>
<script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>-->
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form3").validate();
        $(".modal-dialog", window.parent.document).width(1200);
	});
</script>
<style>
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 2px 8px;background: #EBEBEB;}

@media(min-width:558px){.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}
.table1 {  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
}
</style>

<?php if(!isset($_GET["down"])){  ?>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a>&nbsp;&nbsp;&nbsp;<a title="Exporter au format Excel" href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="clear h0">&nbsp;</div>
<?php } ?>
<div>
<div class="widget box ">

 <div class="widget-header"> 

 <h5 align="center">UNIDADE DE COORDENAÇÃO</h5>
 <h5 align="center">Programa de Promoç&auml;o de Oportunidades Socio-Ec&oacute;nomicas Rurais (POSER Cap-Vert)</h5>
 <h5 align="center"><strong>Tableau de bord   : R&eacute;capitulatif des Microproejts </strong></h5>
</div>
<div class="widget-content">
  <table border="1" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >
    <thead>
      <tr>
        <td><div align="left"><strong>CRP</strong></div></td>
        <td>&nbsp;</td>
		<?php foreach($tab_array as $vdomaine){  ?>

 <td><?php echo $vdomaine; ?> </td>
<?php } ?>
       
        <td><div align="center"><strong>Total</strong></div></td>
        </tr>
    </thead>
    <?php  $nj=$nja=$nh=$nf=$ct=$cp=$cb=0; if($totalRows_liste_crp>0) {$i=0; do { //$id = $row_liste_crp['code_ms']; ?>
    <tr>
      <td rowspan="3"><?php  echo $row_liste_crp['nom_ugl']; ?></td>
      <td>Nombre</td>
      <!--<td><div align="left"><?php //echo $row_liste_crp['resume']; ?></div></td>-->
    <?php foreach($tab_array as $vdomaine){  ?>

 
  <td><div align="right">
    <?php if(isset($nbre_array[$row_liste_crp['code_ugl']][$vdomaine])) echo $nbre_array[$row_liste_crp['code_ugl']][$vdomaine]; else echo ""; ?>
  </div></td>
        <?php } ?>
      <td bgcolor="#CCCCCC"><div align="right">
        <?php if(isset($nbre_crp_array[$row_liste_crp['code_ugl']])) echo $nbre_crp_array[$row_liste_crp['code_ugl']]; ?>
      </div></td>
      </tr>
    <tr>
      <td>Montant</td>
      <?php foreach($tab_array as $vdomaine){  ?>

 
  <td><div align="right">
    <?php if(isset($cout_array[$row_liste_crp['code_ugl']][$vdomaine])) echo number_format($cout_array[$row_liste_crp['code_ugl']][$vdomaine], 0, ',', ' '); else echo ""; ?>
  </div></td>
        <?php } ?>
      <td bgcolor="#CCCCCC"><div align="right">
        <?php if(isset($cout_crp_array[$row_liste_crp['code_ugl']])) echo number_format($cout_crp_array[$row_liste_crp['code_ugl']], 0, ',', ' '); ?>
      </div></td>
      </tr>
    <tr>
      <td>B&eacute;n&eacute;ficiaires  </td>
      <?php foreach($tab_array as $vdomaine){  ?>

 
  <td><div align="right">
    <?php if(isset($bene_array[$row_liste_crp['code_ugl']][$vdomaine])) echo $bene_array[$row_liste_crp['code_ugl']][$vdomaine]; else echo ""; ?>
  </div></td>
        <?php } ?>
      <td bgcolor="#CCCCCC"><div align="right">
        <?php if(isset($bene_crp_array[$row_liste_crp['code_ugl']])) echo $bene_crp_array[$row_liste_crp['code_ugl']]; ?>
      </div></td>
      </tr>
   
	  <tr class="even">
  <td colspan="14"><div align="center" style="background-color:#CCCCCC; height: 2px;">&nbsp;</div></td>
</tr>
    <?php } while ($row_liste_crp = mysql_fetch_assoc($liste_crp)); ?>
    <?php }else{ ?>
    <tr>
      <td colspan="4"><h2 align="center">Aucune donn&eacute;e disponible !</h2></td>
      <?php } ?>
    </tr>
    <tr>
      <td rowspan="3"><div align="center"><strong>Total</strong></div>
        <div align="left"></div></td>
      <td>Nombre</td>
     <?php foreach($tab_array as $vdomaine){  ?>

 
  <td><div align="right">
    <?php if(isset($nbre_domaine_array[$vdomaine])) echo $nbre_domaine_array[$vdomaine]; ?> 
  </div></td>
        <?php } ?>
      <td><div align="right" style="background-color:#000000; color:#FFFFFF; font:bold"> <?php if(isset($nbre_total_array)) echo $nbre_total_array; ?></div>
        <div align="right"></div>
        <div align="right"></div>
        <div align="right"></div>
        <div align="right"></div>        <div align="right"></div></td>
      <!--<td><div align="left"><?php //echo $row_liste_crp['resume']; ?></div></td>-->
      </tr>
    <tr>
      <td>Montant</td>
       <?php foreach($tab_array as $vdomaine){  ?>

 
  <td nowrap="nowrap"><div align="right">
    <?php if(isset($cout_domaine_array[$vdomaine])) echo number_format($cout_domaine_array[$vdomaine], 0, ',', ' '); ?>
  </div></td>
        <?php } ?>
      <td nowrap="nowrap"> <div align="right" style="background-color:#000000; color:#FFFFFF; font:bold">
        <?php if(isset($cout_total_array)) echo number_format($cout_total_array, 0, ',', ' '); ?>
      </div></td>
      </tr>
    <tr>
      <td>B&eacute;n&eacute;ficiaires </td>
     <?php foreach($tab_array as $vdomaine){  ?>

 
  <td><div align="right">
    <?php if(isset($bene_domaine_array[$vdomaine])) echo $bene_domaine_array[$vdomaine]; ?>
  </div></td>
        <?php } ?>
      <td> <div align="right" style="background-color:#000000; color:#FFFFFF; font:bold">
        <?php if(isset($bene_total_array)) echo number_format($bene_total_array, 0, ',', ' '); ?>
      </div></td>
      </tr>
  </table>
</div></div>
</div>
<?php if(!isset($_GET["down"])){  ?>
<?php include 'modal_add.php'; ?>
<?php }  ?>

