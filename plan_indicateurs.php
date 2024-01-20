<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & D�veloppement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;
/*
if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}*/
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=ISO-8859-15');


//if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y"); if(isset($_GET['cp'])) {$cp=$_GET['cp'];} else $cp=0;
/*if(isset($_GET['id_act'])) { $id_act = $_GET['id_act']; }
if(isset($_GET["id"])) { $id=$_GET["id"];} else $id=0;*/

if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y");

//if(isset($_GET['tab'])) $tab=$_GET['tab']; else $tab=1;

if(isset($_GET['code_act'])) {$code_act = $_GET['code_act'];} else $code_act = 0;

if(isset($_GET['id_act'])) {$id_act = $_GET['id_act'];} else $id_act="";


$editFormAction = $_SERVER['PHP_SELF'];
/*if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}*/
$page = $_SERVER['PHP_SELF'];

$lien = $lien1 = $_SERVER['PHP_SELF'];
$lien .= "?id_act=$id_act";
$lien1 .= "?id_act=$id_act";

$id=0;
if(isset($_GET["id"])) { $id=$_GET["id"];
$query_edit_tache = "SELECT * FROM ".$database_connect_prefix."indicateur_tache WHERE id_indicateur_tache='$id'";
  try{
    $edit_tache = $pdar_connexion->prepare($query_edit_tache);
    $edit_tache->execute();
    $row_edit_tache = $edit_tache ->fetch();
    $totalRows_edit_tache = $edit_tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//$query_edit_data_cible = "SELECT * FROM cible_indicateur_pa where indicateur_ppa='$id'";
 $query_liste_dotation_convention = "SELECT * FROM cible_indicateur_trimestre where indicateur='$id'";

 try{
    $liste_dotation_convention = $pdar_connexion->prepare($query_liste_dotation_convention);
    $liste_dotation_convention->execute();
    $row_liste_dotation_convention = $liste_dotation_convention ->fetchAll();
    $totalRows_liste_dotation_convention = $liste_dotation_convention->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableauConventionDotation=array();
if($totalRows_liste_dotation_convention>0){  foreach($row_liste_dotation_convention as $row_liste_dotation_convention){  
$tableauConventionDotation[$row_liste_dotation_convention["region"]][$row_liste_dotation_convention["trimestre"]]=$row_liste_dotation_convention["cible"];


 }  }
 
} 

//print_r($cible);
$query_cible_ind_an = "SELECT sum(cible) as valeur_cible, indicateur, trimestre   FROM cible_indicateur_trimestre, indicateur_tache where indicateur=id_indicateur_tache and id_activite='$id_act' group by indicateur, trimestre";
  try{
    $cible_ind_an = $pdar_connexion->prepare($query_cible_ind_an);
    $cible_ind_an->execute();
    $row_cible_ind_an = $cible_ind_an ->fetchAll();
    $totalRows_cible_ind_an = $cible_ind_an->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$cible_an=array();
if($totalRows_cible_ind_an>0){ foreach($row_cible_ind_an as $row_cible_ind_an){
$cible_an[$row_cible_ind_an["trimestre"]][$row_cible_ind_an["indicateur"]]=$row_cible_ind_an["valeur_cible"];
//$cible[$row_edit_data_cible["annee"]][0]=$row_edit_data_cible["annee"];

 }  }
 

if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
$query_sup_tache = sprintf("DELETE from ".$database_connect_prefix."indicateur_tache WHERE id_indicateur_tache=%s",
                         GetSQLValueString($id, "text"));
       try{
    $Result1 = $pdar_connexion->prepare($query_sup_tache);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    
  	$query_sup_tache1 = "DELETE FROM cible_indicateur_trimestre WHERE indicateur='$id'";
		  //  mysql_select_db($database_pdar_connexion, $pdar_connexion);
           try{
    $Result11 = $pdar_connexion->prepare($query_sup_tache1);
    $Result11->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
	 

  $insertGoTo = $lien;
  if ($Result) $insertGoTo .= "&del=ok"; else $insertGoTo .= "&del=no";
  header(sprintf("Location: %s", $lien)); exit();
}

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_act = "SELECT * FROM ".$database_connect_prefix."ptba where annee=$annee and projet='".$_SESSION["clp_projet"]."'";
$liste_act  = mysql_query($query_liste_act , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_act  = mysql_fetch_assoc($liste_act);
$totalRows_liste_act  = mysql_num_rows($liste_act);*/
//$code_act=$row_act['code_activite_ptba'];


if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
	
	$cle=date("ymdis").$_SESSION['clp_n'];
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
// if(isset($proportion_array[$_POST['jalon']])) $propor=$proportion_array[$_POST['jalon']]; else $propor=0;
// if($totalRows_liste_act>0){  do{
 // $cmp_array[$row_liste_activite_1["code"]] = $row_liste_activite_1["intitule"];
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."indicateur_tache (intitule_indicateur_tache, id_activite, code_indicateur_ptba, indicateur_cr, unite, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, '$personnel', '$date')",
                       // GetSQLValueString($_SESSION["clp_projet"], "text"),
						GetSQLValueString($_POST['intitule_indicateur_ppa'], "text"),
						GetSQLValueString($id_act, "int"),
						GetSQLValueString($_POST['type_ind'], "text"),
						GetSQLValueString($_POST['referentiel'], "int"),
                        GetSQLValueString($_POST['unite'], "text"));

       try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
 // }while($row_liste_act = mysql_fetch_assoc($liste_act));  }

 // $c=$cle;
   $idactins = $db->lastInsertId();
   $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
     $annee=$_POST['annee'];
  $valind=$_POST['valind'];
  $id_region=$_POST['id_region'];
  //taches ugls mois
      $query_sup_cible_indicateur = "DELETE FROM cible_indicateur_trimestre WHERE indicateur='$idactins'";
      try{
    $Result1 = $pdar_connexion->prepare($query_sup_cible_indicateur);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
  foreach ($id_region as $key => $value)

  {

  	if(isset($valind[$key]) && $valind[$key]!=NULL) {

  $insertSQL = sprintf("INSERT INTO cible_indicateur_trimestre  (indicateur, region, trimestre,  cible, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, '$personnel', '$date')",

					    GetSQLValueString($idactins, "text"),
					   GetSQLValueString($id_region[$key], "text"),
  					   GetSQLValueString($annee[$key], "text"),
  					  // GetSQLValueString($annee, "int"),
  					   GetSQLValueString($valind[$key], "double"));

    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

      }

    }
	
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok&id_act=$id_act";
  else $insertGoTo .= "?insert=no&id_act=$id_act";
  header(sprintf("Location: %s", $insertGoTo));
}

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."indicateur_tache WHERE id_indicateur_tache=%s",
                         GetSQLValueString($id, "text"));

        try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
	$query_sup_tache1 = "DELETE FROM cible_indicateur_trimestre WHERE indicateur='$id'";
		   // mysql_select_db($database_pdar_connexion, $pdar_connexion);
        try{
    $Result1 = $pdar_connexion->prepare($query_sup_tache1);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
	 

    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok&id_act=$id_act";
    else $insertGoTo .= "?del=no&id_act=$id_act";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['MM_update'];

  //taches ugls mois
  $insertSQL1 = "DELETE FROM cible_indicateur_trimestre WHERE indicateur='$c'";
    try{
    $Result1 = $pdar_connexion->prepare($insertSQL1);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

 
	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."indicateur_tache SET intitule_indicateur_tache=%s, code_indicateur_ptba=%s, indicateur_cr=%s, unite=%s, etat='MODIFIE', modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur_tache='$c'",
					   GetSQLValueString($_POST['intitule_indicateur_ppa'], "text"), 
					   GetSQLValueString($_POST['type_ind'], "int"),
					   GetSQLValueString($_POST['referentiel'], "int"),
                       GetSQLValueString($_POST['unite'], "text"));

    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  

 $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
     $annee=$_POST['annee'];
  $valind=$_POST['valind'];
  $id_region=$_POST['id_region'];
  //taches ugls mois
    /*  $query_sup_cible_indicateur = "DELETE FROM cible_indicateur_pa WHERE indicateur_ppa='$idactins'";
      try{
    $Result1 = $pdar_connexion->prepare($query_sup_cible_indicateur);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }*/
  
  foreach ($id_region as $key => $value)

  {

  	if(isset($valind[$key]) && $valind[$key]!=NULL) {

  $insertSQL = sprintf("INSERT INTO cible_indicateur_trimestre  (indicateur, region, trimestre,  cible, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, '$personnel', '$date')",

					    GetSQLValueString($c, "text"),
					   GetSQLValueString($id_region[$key], "text"),
  					   GetSQLValueString($annee[$key], "text"),
  					  // GetSQLValueString($annee, "int"),
  					   GetSQLValueString($valind[$key], "double"));

    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

      }

    }
	
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?update=ok&id_act=$id_act";
  else $insertGoTo .= "?update=no&id_act=$id_act";
  header(sprintf("Location: %s", $insertGoTo));
}
}
//activite
 /* $query_edit_act = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE  code='$id_act' and projet='".$_SESSION["clp_projet"]."'";
    try{
    $edit_act = $pdar_connexion->prepare($query_edit_act);
    $edit_act->execute();
    $row_edit_act = $edit_act ->fetch();
    $totalRows_edit_act = $edit_act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }*/

$query_tache = "select * FROM ".$database_connect_prefix."indicateur_tache where id_activite='$id_act'";
try{
    $tache = $pdar_connexion->prepare($query_tache);
    $tache->execute();
    $row_tache = $tache ->fetchAll();
    $totalRows_tache = $tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//$pcent = 100;



//echo $proportion;

$tableauMois=array('01<>Jan<>J','02<>Fev<>F','03<>Mars<>M','04<>Avril<>A','05<>Mai<>M','06<>Juin<>J','07<>Juil<>J','08<>Aout<>A','09<>Sep<>S','10<>Oct<>O','11<>Nov<>N','12<>D�c<>D');

  for($j=1;$j<=4;$j++)
  {$tableauAnnee[]=$j."<>".$j; }

$query_liste_unite = "SELECT * FROM unite_indicateur order by id_unite";
try{
    $liste_unite = $pdar_connexion->prepare($query_liste_unite);
    $liste_unite->execute();
    $row_liste_unite = $liste_unite ->fetchAll();
    $totalRows_liste_unite = $liste_unite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


$query_act = "SELECT * FROM ".$database_connect_prefix."ptba where id_ptba='$id_act'";
    	   try{
    $act = $pdar_connexion->prepare($query_act);
    $act->execute();
    $row_act = $act ->fetch();
    $totalRows_act = $act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$code_act=$row_act['code_activite_ptba'];

//$query_liste_zone = "SELECT * FROM ".$database_connect_prefix."ugl ORDER BY code_ugl";
$ugl_projet = str_replace("|",",",$row_act["pde"]);//implode(",",(explode("|", $_SESSION["clp_projet_ugl"]));
/*
$query_liste_zone= "SELECT code_pde, nom_pde FROM ".$database_connect_prefix."pde where FIND_IN_SET( code_pde, '".$ugl_projet."' ) order by code_pde";
			try{
    $liste_zone = $pdar_connexion->prepare($query_liste_zone);
    $liste_zone->execute();
    $row_liste_zone = $liste_zone ->fetchAll();
    $totalRows_liste_zone = $liste_zone->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }*/

//$query_liste_bailleur = "SELECT * FROM ".$database_connect_prefix."ugl ORDER BY code_ugl";
//$ugl_projet = str_replace("|",",",$row_act["pde"]);//implode(",",(explode("|", $_SESSION["clp_projet_ugl"]));
$ugl_projet = str_replace("|",",",$row_act["region"]);
//$query_liste_bailleur= "SELECT code_pde, nom_pde FROM ".$database_connect_prefix."pde where FIND_IN_SET( code_pde, '".$ugl_projet."' ) order by code_pde";
$query_liste_bailleur= "SELECT code_ugl as code_pde, nom_ugl as nom_pde FROM ".$database_connect_prefix."ugl where FIND_IN_SET( code_ugl, '".$ugl_projet."' ) order by code_ugl";
			try{
    $liste_bailleur = $pdar_connexion->prepare($query_liste_bailleur);
    $liste_bailleur->execute();
    $row_liste_bailleur = $liste_bailleur ->fetchAll();
    $totalRows_liste_bailleur = $liste_bailleur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//$query_liste_referentiel = "SELECT * FROM referentiel_indicateur order by id_ref_ind";
//if(isset($_GET['id_ind'])) $id_ind=$_GET['id_ind']; else $id_ind=0; if(isset($_GET['ad_ind'])) $ex_ind=$_GET['ad_ind']; else $ex_ind=0;
//$query_liste_referentiel = "SELECT * FROM ".$database_connect_prefix."referentiel_indicateur where type_ref_ind=1 and mode_calcul='Unique' and mode_suivi=1 order by intitule_ref_ind ASC";
$query_liste_referentiel = "select * FROM ".$database_connect_prefix."plan_pluri_indicateur where activite_pa='$code_act'";

    	   try{
    $liste_referentiel = $pdar_connexion->prepare($query_liste_referentiel);
    $liste_referentiel->execute();
    $row_liste_referentiel = $liste_referentiel ->fetchAll();
    $totalRows_liste_referentiel = $liste_referentiel->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//$query_liste_dotation_convention = "SELECT * FROM cible_indicateur_pa where  activite='$id_act' and annee=$annee and projet='".$_SESSION["clp_projet"]."' order by type_part";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

<!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->

<link rel="stylesheet" type="text/css" href="<?php print $config->theme_folder;?>/plugins/jquery-ui.css"/>

<link href="<?php print $config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>

<link href="<?php print $config->theme_folder;?>/responsive.css" rel="stylesheet" type="text/css"/>

<link href="<?php print $config->theme_folder;?>/icons.css" rel="stylesheet" type="text/css"/>

<link href='<?php print $config->theme_folder;?>/css.css' rel='stylesheet' type='text/css'>

<link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">

<link href="<?php print $config->theme_folder; ?>/plugins/datatables_bootstrap.css" rel="stylesheet" type="text/css"/>

<link href="<?php print $config->theme_folder; ?>/plugins/select2.css" rel="stylesheet" type="text/css"/>



<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>

<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>

<script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>

<script type="text/javascript" src="plugins/noty/layouts/top.js"></script>

<script type="text/javascript" src="plugins/noty/themes/default.js"></script>

<script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>

<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>

<script type="text/javascript" src="plugins/pickadate/picker.js"></script>

<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>

<script type="text/javascript" src="plugins/select2/select2.min.js"></script>

<script type="text/javascript">

$(document).ready(function() {

  $(".modal-dialog", window.parent.document).width(800);


<?php if(isset($_GET['add'])) { ?>

        $("#ui-datepicker-div").remove();

        $(".modal-dialog", window.parent.document).width(800);

        $(".select2-select-00").select2({allowClear:true});

<?php } ?>


  });

</script>

<style>
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {

  border-spacing: 0px !important; border-collapse: collapse; font-size: small;

} .table tbody tr td {vertical-align: middle; }

#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}

.dataTables_length, .dataTables_info { float: left; font-size: 10px;}

.dataTables_length, .dataTables_paginate { display: none;}



@media(min-width:558px){.col-md-12 {width: 100%;}.col-md-9 {width: 75%;}.col-md-8 {width: 70%;}.col-md-4 {width: 30%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}

.table1 {  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
}
</style>
</head>
<body>
<?php if(!isset($_GET['add'])) { ?>
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i>  <strong> Cibles trimestrielle par indicateurs</strong></h4>
   <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==0){ ?>
<?php //if($row_total_proportion["total"]<100){  ?>
<a href="<?php echo $lien1."&add=1"; ?>" class="pull-right p11" title="Ajout une suivi de t&acirc;ches" ><i class="icon-plus"> Nouvel indicateur </i></a>
<?php //} ?>
<?php } ?>
</div>
<div class="widget-content">
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >
            <thead>
                <tr>
                  <td>Indicateurs</td>
                  <td><div align="center" title="Proportion">Unit&eacute;</div></td>
				           <?php
foreach($tableauAnnee as $van1){  $augla = explode('<>',$van1);     $iugla = $augla[0];
 ?>	
                  <td><div align="center"> <?php echo "Trimestre ".$iugla; ?>	</div></td>
				           <?php } ?>	
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==0)) { ?>
                  <td align="center" width="80" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
 <?php $t=0;  if($totalRows_tache>0) {
   $p1="j"; $sp=0; $i=0; foreach($row_tache as $row_tache){  $i=$i+1; $t=$t+1; ?>
      <tr>
        <td ><span class="Style12"><?php echo $row_tache['intitule_indicateur_tache'];  ?></span></td>
        <td align="center" title="Proportion"><?php echo $row_tache['unite'];  ?> </td>
         <?php
foreach($tableauAnnee as $van1){  $augla = explode('<>',$van1);     $iugla = $augla[0];
 ?>	
                  <td><div align="center"> <?php if(isset($cible_an[$iugla][$row_tache["id_indicateur_tache"]])) echo $cible_an[$iugla][$row_tache["id_indicateur_tache"]]; //else echo  ?>	</div></td>
				           <?php } ?>
        <?php $ts=0; $mois_cum = "";  ?>
     
      
      <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==0)) { ?>
       <td align="center"><a href="<?php echo $lien."&id=".$row_tache['id_indicateur_tache']."&add=1"; ?>" title="Modifier la t�che" ><img align="center" src='./images/edit.png' width='20' height='20' alt='Modifier' style="margin:0px 5px 0px 0px;"></a>
<a onClick="return confirm('Voulez vous vraiment suppimer cette t&acirc;che ?');" href="<?php echo $lien."&id_sup=".$row_tache['id_indicateur_tache'].""; ?>" title="Supprimer la t�che" ><img align="center" src='./images/delete.png' width='20' height='20' alt='Supprimer' style="margin:0px 5px 0px 0px;"></a></td>
      <?php } ?>
      </tr>
    <?php }  ?>
  <?php } else { ?> <tr><td align="center" colspan="<?php echo (isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1))?count($tableauMois)+3:count($tableauMois)+2; ?>"><h2>Aucun indicateur !</h2></td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    </tr><?php } ?>
  </table>

</div></div>
</div>
<?php } else { ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification indicateur":"Nouvelle indicateur"; ?> </h4>
<a href="<?php echo $lien1; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">

<form action="<?php echo $lien1; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="intitule_indicateur_ppa" class="col-md-3 control-label">Indicateur <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="2" type="text" name="intitule_indicateur_ppa" id="intitule_indicateur_ppa"><?php if(isset($_GET['id'])) echo $row_edit_tache['intitule_indicateur_tache']; ?></textarea>
          </div>
        </div>      </td>
    </tr>
	
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="unite" class="col-md-4 control-label">Unit&eacute; de mesure <span class="required">*</span></label>
          <div class="col-md-8">
              <select name="unite" id="unite" class="form-control required" >
              <option value="">Selectionnez</option>
			   <?php  if($totalRows_liste_unite>0) { foreach($row_liste_unite as $row_liste_unite){ ?>
              <option value="<?php echo $row_liste_unite['unite']; ?>" <?php if (isset($row_edit_tache['unite']) && $row_liste_unite['unite']==$row_edit_tache['unite']) {echo "SELECTED";} ?>><?php echo $row_liste_unite['unite']; ?></option>
              <?php }} ?>
            </select>
            </div>
        </div></td>
	   <td> 
		 <div class="form-group">
          <label for="type_ind" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-9">
            <input name="type_ind" type="text" class="form-control required" id="type_ind" value="<?php if(isset($_GET['id'])) echo $row_edit_tache['code_indicateur_ptba']; ?>" size="5" />
          </div>
        </div> 
		</td>
    </tr>
   <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="referentiel" class="col-md-3 control-label">R&eacute;f&eacute;rentiel <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="referentiel" id="referentiel" class="full-width-fix select2-select-00 required">

            <option value="">Selectionnez</option>
            <option value="0" <?php if(isset($_GET['id'])) {if (!(strcmp(0, $row_edit_tache['indicateur_cr']))) {echo "SELECTED";} } ?>>Non-d&eacute;finie</option>
            <?php if($totalRows_liste_referentiel>0) { foreach($row_liste_referentiel as $row_liste_referentiel){   ?>

                <option value="<?php echo $row_liste_referentiel['id_plan'];?>"<?php if(isset($_GET['id'])) {if (!(strcmp($row_liste_referentiel['id_plan'], $row_edit_tache['indicateur_cr']))) {echo "SELECTED";} } ?>><?php echo substr($row_liste_referentiel['intitule_indicateur_ppa'],0, 70)." (".$row_liste_referentiel['unite'].")";?></option>

                <?php

            }  }  ?>

            </select>
          </div>
        </div>      </td>
    </tr>
	   <tr valign="top">
	    
	     <td colspan="2" bgcolor="#CCCCCC">Valeur cible par ann&eacute;e </td>
        </tr>
	 <tr valign="top">
	   <td colspan="2"><table align="center" width="100%">
        
	
 
         <tr>
           <td align="left">&nbsp;</td>
		       <?php  foreach($row_liste_bailleur as $row_liste_bailleur1){  ?>
           <td align="left"><?php  echo $row_liste_bailleur1['nom_pde'];  ?></td>
		     <?php  }  ?>
         </tr>  
		        <?php foreach($tableauAnnee as $van1){  $augla = explode('<>',$van1);     $iugla = $augla[0];  ?>	
         <tr>
         
           <td align="left"><input checked="checked" disabled="disabled" type="checkbox" id="projet" name="projet" value="" />
               <?php echo "Trimestre ".$iugla; ?></td>
			    <?php  foreach($row_liste_bailleur as $row_liste_bailleur2){  ?>
                <td align="left"><span class="Style31">
                  <input name='valind[]' class="form-control" type="text" size="10"  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']>3) echo "disabled"; ?> value="<?php

 if(isset($tableauConventionDotation[$row_liste_bailleur2['code_pde']][$iugla]))

 {  echo $tableauConventionDotation[$row_liste_bailleur2['code_pde']][$iugla];} ?>"/>
                  </span>
                 				  
				   <input name="annee[]" type="hidden" size="5" value="<?php echo $augla[0]; ?>"/>

  <input name="id_region[]" type="hidden" size="5" value="<?php echo $row_liste_bailleur2['code_pde']; ?>"/>				  </td><?php  } ?>
         </tr>
         <?php }  ?>
		 
       </table></td>
	   </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo ($_GET["id"]); ?>" />
<?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
<a href="<?php echo $lien1; ?>" class="btn pull-right" title="Annuler" >Annuler</a>
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2)) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette t&acirc;che ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>
<?php include_once 'modal_add.php'; ?>
</body>
</html>