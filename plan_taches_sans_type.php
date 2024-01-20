<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y"); if(isset($_GET['cp'])) {$cp=$_GET['cp'];} else $cp=0;
if(isset($_GET['id_act'])) { $id_act = $_GET['id_act']; }
if(isset($_GET['code_act'])) {$code_activite = $_GET['code_act'];} else $code_activite="";
if(isset($_GET["id"])) { $id=$_GET["id"];} else $id=0;
function frenchMonthName($monthnum) {
      $armois=array("", "Jan", "Fév", "Mars", "Avril", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc");
      if ($monthnum>0 && $monthnum<13) {
          return $armois[$monthnum];
      } else {
          return $monthnum;
      }
  }

$editFormAction = $_SERVER['PHP_SELF'];
/*if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}*/
$page = $_SERVER['PHP_SELF'];

$lien = $lien1 = $_SERVER['PHP_SELF'];
$lien .= "?annee=$annee&id_act=$id_act";
$lien1 .= "?annee=$annee&id_act=$id_act";

/*$query_max_jalon = "SELECT id_jalon, code FROM jalon_activite, groupe_tache where  id_jalon=entite and id_activite=$id_act ORDER BY code desc limit 1";
    	   try{
    $max_jalon = $pdar_connexion->prepare($query_max_jalon);
    $max_jalon->execute();
    $row_max_jalon = $max_jalon ->fetch();
    $totalRows_max_jalon = $max_jalon->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if(isset($row_max_jalon['id_jalon'])) $max_jalon_id=$row_max_jalon['id_jalon']; else $max_jalon_id=0;*/
// echo "plan taches sans type"; exit();


if(isset($_GET["id"])) { $id=$_GET["id"];
$query_edit_tache = "SELECT * FROM ".$database_connect_prefix."groupe_tache WHERE id_groupe_tache='$id' and id_activite='$id_act'";
    	   try{
    $edit_tache = $pdar_connexion->prepare($query_edit_tache);
    $edit_tache->execute();
    $row_edit_tache = $edit_tache ->fetch();
    $totalRows_edit_tache = $edit_tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$pcent = 100+$row_edit_tache["proportion"];
$jalon_courant = $row_edit_tache["entite"];
} else { $id=0; $jalon_courant = 0;}

/*$query_liste_jalon = "SELECT id_jalon, code, intitule_jalon, proportion FROM jalon_activite where  id_jalon not in (select entite from groupe_tache where id_activite=$id_act and id_groupe_tache!=$id) AND (id_jalon>$max_jalon_id OR id_jalon =$jalon_courant)   ORDER BY code asc";
    	   try{
    $liste_jalon = $pdar_connexion->prepare($query_liste_jalon);
    $liste_jalon->execute();
    $row_liste_jalon = $liste_jalon ->fetchAll();
    $totalRows_liste_jalon = $liste_jalon->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
//echo $query_liste_jalon;

$query_liste_jalonp = "SELECT id_jalon, code, intitule_jalon, proportion FROM jalon_activite ORDER BY code asc";
    	   try{
    $liste_jalonp = $pdar_connexion->prepare($query_liste_jalonp);
    $liste_jalonp->execute();
    $row_liste_jalonp = $liste_jalonp ->fetchAll();
    $totalRows_liste_jalonp = $liste_jalonp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); } 
$proportion_array=$proportion_p_array=array(); 
 if($totalRows_liste_jalonp>0) { foreach($row_liste_jalonp as $row_liste_jalonp){  
 $proportion_array[$row_liste_jalonp["id_jalon"]] = $row_liste_jalonp["proportion"];
 } }*/
  

if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
$query_sup_tache = sprintf("DELETE from ".$database_connect_prefix."groupe_tache WHERE id_groupe_tache=%s and id_activite='$id_act'",
                         GetSQLValueString($id, "text"));
			     try{
    $Result1 = $pdar_connexion->prepare($query_sup_tache);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $lien;
  if ($Result1) $insertGoTo .= "&del=ok"; else $insertGoTo .= "&del=no";
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
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
/*$refjalonp=$_POST['jalon'];
			 $query_max_jalonu = "SELECT sum(groupe_tache.proportion) as proportion FROM jalon_activite, groupe_tache where  id_jalon=entite and id_activite=$id_act and id_groupe_tache<'$refjalonp'";
			          try{
    $max_jalonu = $pdar_connexion->prepare($query_max_jalonu);
    $max_jalonu->execute();
    $row_max_jalonu = $max_jalonu ->fetch();
    $totalRows_max_jalonu = $max_jalonu->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if(isset($row_max_jalonu['proportion']) && $row_max_jalonu['proportion']>0) $taux_refjalonp=$row_max_jalonu['proportion']; else $taux_refjalonp=0;
if($_POST['n_lot']<1) $_POST['n_lot']=1; if(isset($proportion_array[$_POST['jalon']])) $propor=$proportion_array[$_POST['jalon']]-$taux_refjalonp; else $propor=0;*/
// if($totalRows_liste_act>0){  do{
 // $cmp_array[$row_liste_activite_1["code"]] = $row_liste_activite_1["intitule"];
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."groupe_tache (projet, annee, id_activite, intitule_tache, proportion, code_tache, date_debut, date_fin, n_lot, entite, observation, responsable, id_personnel, date_enregistrement) VALUES (%s, %s,  %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, '$personnel', '$date')",
                       // GetSQLValueString($_POST['jalon'], "int"),
						GetSQLValueString($_SESSION["clp_projet"], "text"),
						 GetSQLValueString($annee, "int"),
						GetSQLValueString($id_act, "int"),
						GetSQLValueString($_POST['intitule_tache'], "text"),
						  GetSQLValueString($_POST['proportion'], "double"),
                        GetSQLValueString($_POST['code_tache'], "int"),
					    GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_debut']))), "date"),
					    GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_fin']))), "date"),
                        //GetSQLValueString($_POST['cout_tache'], "double"),
                        GetSQLValueString($_POST['n_lot'], "int"),
						GetSQLValueString(0/*$_POST['jalon']*/, "int"),
                        GetSQLValueString("-", "text"),
                        GetSQLValueString($_POST['responsable'], "text"));

			     try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
 // }while($row_liste_act = mysql_fetch_assoc($liste_act));  }

  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok&annee=$annee&id_act=$id_act";
  else $insertGoTo .= "?insert=no&annee=$annee&id_act=$id_act";
  header(sprintf("Location: %s", $insertGoTo));
}

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."groupe_tache WHERE id_groupe_tache=%s and id_activite='$id_act'",
                         GetSQLValueString($id, "int"));
			     try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
	
		/*$query_sup_suivi_tache1 = "DELETE FROM suivi_tache WHERE id_tache='$id'";
			     try{
    $Result1 = $pdar_connexion->prepare($query_sup_suivi_tache1);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }*/
  	 
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok&annee=$annee&id_act=$id_act";
    else $insertGoTo .= "?del=no&annee=$annee&id_act=$id_act";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['MM_update'];

/*$refjalonp=$_POST['jalon'];
			 $query_max_jalonu = "SELECT sum(groupe_tache.proportion) as proportion FROM jalon_activite, groupe_tache where  id_jalon=entite and id_activite=$id_act and id_groupe_tache<'$refjalonp'";
			          try{
    $max_jalonu = $pdar_connexion->prepare($query_max_jalonu);
    $max_jalonu->execute();
    $row_max_jalonu = $max_jalonu ->fetch();
    $totalRows_max_jalonu = $max_jalonu->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if(isset($row_max_jalonu['proportion']) && $row_max_jalonu['proportion']>0) $taux_refjalonp=$row_max_jalonu['proportion']; else $taux_refjalonp=0;
if($_POST['n_lot']<1) $_POST['n_lot']=1; if(isset($proportion_array[$_POST['jalon']])) $propor=$proportion_array[$_POST['jalon']]-$taux_refjalonp; else $propor=0;*/

	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."groupe_tache SET  intitule_tache=%s, proportion=%s, code_tache=%s, date_debut=%s, date_fin=%s, n_lot=%s,  responsable=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_groupe_tache='$c' and id_activite='$id_act'",
					  // GetSQLValueString($_POST['jalon'], "int"),
					   GetSQLValueString($_POST['intitule_tache'], "text"),
					   GetSQLValueString($_POST['proportion'], "double"),
					   GetSQLValueString($_POST['code_tache'], "int"),
					   GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_debut']))), "date"),
					   GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_fin']))), "date"),
					  // GetSQLValueString($_POST['cout_tache'], "double"),
                       GetSQLValueString($_POST['n_lot'], "int"),
					  // GetSQLValueString($_POST['jalon'], "int"),
                       GetSQLValueString($_POST['responsable'], "text"));

			     try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?update=ok&annee=$annee&id_act=$id_act";
  else $insertGoTo .= "?update=no&annee=$annee&id_act=$id_act";
  header(sprintf("Location: %s", $insertGoTo));
}
}
//activite
$query_act = "SELECT * FROM ".$database_connect_prefix."ptba where id_ptba='$id_act' and annee=$annee and projet='".$_SESSION["clp_projet"]."'";
    	   try{
    $act = $pdar_connexion->prepare($query_act);
    $act->execute();
    $row_act = $act ->fetch();
    $totalRows_act = $act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$code_act=$row_act['code_activite_ptba'];
$type_activite=substr($row_act['code_activite_ptba'],0, 3);
$mois_act = $row_act['debut'];
//$acteur_act=$row_act['acteur_conserne'];

 $query_liste_responsable = "SELECT distinct fonction, id_personnel, nom, prenom FROM ".$database_connect_prefix."personnel where projet like '%".$_SESSION["clp_projet"]."%' and id_personnel!='admin'";
    	   try{
    $liste_responsable = $pdar_connexion->prepare($query_liste_responsable);
    $liste_responsable->execute();
    $row_liste_responsable = $liste_responsable ->fetchAll();
    $totalRows_liste_responsable = $liste_responsable->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ta = "SELECT * FROM type_activite  ORDER BY type_activite asc";
$liste_ta  = mysql_query($query_liste_ta , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_ta  = mysql_fetch_assoc($liste_ta);
$totalRows_liste_ta  = mysql_num_rows($liste_ta);*/

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_mois1= "SELECT * FROM mois where num_mois>('$trimestre_courant'*3-3) and num_mois<=('$trimestre_courant'*3+1) order by num_mois";
$liste_mois1 = mysql_query($query_liste_mois1, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_mois1 = mysql_fetch_assoc($liste_mois1);
$totalRows_liste_mois1 = mysql_num_rows($liste_mois1);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_mois= "SELECT * FROM mois where num_mois>('$trimestre_courant'*3-3) and num_mois<('$trimestre_courant'*3+1) order by num_mois";
$liste_mois = mysql_query($query_liste_mois, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
	$tableauMois=array();
	while($ligne=mysql_fetch_assoc($liste_mois)){$tableauMois[]=$ligne['num_mois']."<>".$ligne['abrege'];}
	mysql_free_result($liste_mois);*/

// $code_act=$row_act['id_ptba'];

//query tache
$query_tache = "select * FROM ".$database_connect_prefix."groupe_tache where id_activite='$id_act' ORDER BY code_tache ASC";
  

try{
    $tache = $pdar_connexion->prepare($query_tache);
    $tache->execute();
    $row_tache = $tache ->fetchAll();
    $totalRows_tache = $tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }



$pcent = 100;

$query_total_proportion = "SELECT sum(proportion) as total FROM ".$database_connect_prefix."groupe_tache WHERE id_activite='$id_act'";
    	   try{
    $total_proportion = $pdar_connexion->prepare($query_total_proportion);
    $total_proportion->execute();
    $row_total_proportion = $total_proportion ->fetch();
    $totalRows_total_proportion = $total_proportion->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$proportion=100;
if(isset($row_total_proportion["total"]) && $row_total_proportion["total"]>0){ $proportion=$pcent-$row_total_proportion["total"]; }
//echo $proportion;

$tableauMois=array('01<>Jan<>J','02<>Fev<>F','03<>Mars<>M','04<>Avril<>A','05<>Mai<>M','06<>Juin<>J','07<>Juil<>J','08<>Aout<>A','09<>Sep<>S','10<>Oct<>O','11<>Nov<>N','12<>Déc<>D');


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
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>
<script type="text/javascript" src="plugins/noty/layouts/top.js"></script>
<script type="text/javascript" src="plugins/noty/themes/default.js"></script>
<script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<?php if(!isset($_GET['add'])) { ?>
<script type="text/javascript" src="plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>
<script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>
<?php } ?>
<script>
	$().ready(function() {
	  $(".modal-dialog", window.parent.document).width(780);
		// validate the comment form when it is submitted
		$("#form1").validate();
<?php if(!isset($_GET['add'])) { ?>
$(".dataTable").dataTable({"iDisplayLength": -1});

<?php } ?>
<?php if(isset($_GET['add'])) { ?>
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
<?php } ?>
	});
function check_proportion(){
  var p = <?php echo $proportion;  ?>;
  if(document.form1.proportion.value><?php echo $proportion;  ?>){ document.form1.proportion.value=<?php echo $proportion;  ?>; }
}
</script>
<style>
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}
.dataTables_length, .dataTables_info { float: left;} .dataTables_paginate, .dataTables_filter { float: right;}
.dataTables_length, .dataTables_paginate { display: none;}

.Style1 {
	font-size: 16px;
	font-weight: bold;
}
</style>
</head>
<body>
<?php if(!isset($_GET['add'])) { ?>
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i>  <strong>Tâches à faire <?php echo "(".$mois_act.")";?></strong> </h4>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==0){ ?>
<?php if($row_total_proportion["total"]<100){  ?>
<a href="<?php echo $lien1."&add=1"; ?>" class="pull-right p11" title="Ajout une suivi de t&acirc;ches" ><i class="icon-plus"> Nouvelle t&acirc;che </i></a>
<?php } ?>
<?php } ?>
</div>
<div class="widget-content">
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >
            <thead>
                <tr>
                  <td>T&acirc;ches</td>
                  <td><div align="center" title="Proportion">P%</div></td>
                  <td>Lot</td>
                  <td>D&eacute;but</td>
                  <td>Fin</td>
                 
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==0)) { ?>
                  <td align="center" width="80" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
			            <?php $t=0; if($totalRows_tache>0) { $p1="j"; $sp=0; $i=0; foreach($row_tache as $row_tache){ $i=$i+1; $t=$t+1;  ?>

      <tr>
        <td ><span class="Style12" title="<?php if(isset($row_tache['entite']) && $row_tache['entite']==0) echo substr($database_connect_prefix,0,-1);
elseif(isset($row_tache['entite']) && $row_tache['entite']==1) echo "Prestataire"; echo " /".$row_tache['responsable'];  ?>"><?php echo $row_tache['code_tache'].": ".$row_tache['intitule_tache']; if(isset($row_tache['cout_tache']) && $row_tache['cout_tache']>0) echo " <br/>=> <u>".number_format($row_tache['cout_tache'], 0, ',', ' ')."</u>"; $sp=$sp+$row_tache['proportion']; ?></span></td>
      <?php $ts=0; $mois_cum = "";  ?>
      <td align="center" title="Proportion"><?php echo $row_tache['proportion'];  ?>&nbsp;%        </td>
      <td align="center" title="Début"><?php echo $row_tache['n_lot'];  ?></td>
      <td align="center" title="Début"><?php echo implode('/',array_reverse(explode('-',$row_tache['date_debut'])));  ?></td>
      <td align="center" title="Fin"><?php echo  implode('/',array_reverse(explode('-',$row_tache['date_fin'])));  ?></td>
      
      <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==0)) { ?>
       <td align="center"><a href="<?php echo $lien."&id=".$row_tache['id_groupe_tache']."&add=1"; ?>" title="Modifier la tâche" ><img align="center" src='./images/edit.png' width='20' height='20' alt='Modifier' style="margin:0px 5px 0px 0px;"></a>
<a onClick="return confirm('Voulez vous vraiment suppimer cette t&acirc;che ?');" href="<?php echo $lien."&id_sup=".$row_tache['id_groupe_tache'].""; ?>" title="Supprimer la tâche" ><img align="center" src='./images/delete.png' width='20' height='20' alt='Supprimer' style="margin:0px 5px 0px 0px;"></a></td>
      <?php } ?>
      </tr>
    <?php } ?>
  <?php } else { ?> <tr><td align="center" colspan="<?php echo (isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==0))?count($tableauMois)+3:count($tableauMois)+2; ?>"><h2>Aucune t&acirc;che !</h2></td></tr><?php } ?>
  </table>

</div></div>
</div>
<?php } else { ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification de t&acirc;che":"Nouvelle t&acirc;che"; ?></h4>
<a href="<?php echo $lien1; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">

<form action="<?php echo $lien1; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="intitule_tache" class="col-md-3 control-label">T&acirc;che <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="2" type="text" name="intitule_tache" id="intitule_tache"><?php if(isset($_GET['id'])) echo $row_edit_tache['intitule_tache']; ?></textarea>
          </div>
        </div>      </td>
    </tr>
	
    <tr valign="top">
      <td>
      <div class="form-group">
          <label for="code_tache" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="required" type="text" name="code_tache" id="code_tache" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_tache['code_tache']; ?>" size="10" />
          </div>
        </div>      </td>
      
	   <td>
        <div class="form-group">
          <label for="proportion" class="col-md-4 control-label">Proportion (%) <span class="required">*</span></label>
          <div class="col-md-8">
           <input class="required" type="text" style="text-align:center" name="proportion" id="proportion" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_tache['proportion']; //else echo "1"; ?>" size="10" />
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td>  <div class="form-group">
          <label for="responsable" class="col-md-4 control-label">Responsable <span class="required">*</span></label>
          <div class="col-md-8">
            <select name="responsable" id="responsable" class="form-control required">
            <option value="">Selectionnez</option>
              <?php if($totalRows_liste_responsable>0) { foreach($row_liste_responsable as $row_liste_responsable1){   ?>
              <option value="<?php echo $row_liste_responsable1['id_personnel']?>"<?php if(isset($row_edit_tache['responsable'])) {if (!(strcmp($row_liste_responsable1['id_personnel'], $row_edit_tache['responsable']))) {echo "SELECTED";} } ?>><?php echo $row_liste_responsable1['fonction']." (".$row_liste_responsable1['nom']." ".$row_liste_responsable1['prenom'].")";?></option>
              <?php } } ?>
            </select>
          </div>
        </div>      </td>
      <td>
       <div class="form-group">
          <label for="n_lot" class="col-md-4 control-label">Nombre de lot  <span class="required">*</span></label>
          <div class="col-md-8">
            <input class="required" type="text" style="text-align:center" name="n_lot" id="n_lot" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_tache['n_lot']; else echo "1"; ?>" size="10" />
          </div>
        </div>      </td>
    </tr>
       <tr valign="top">
      <td bgcolor="#FFFF33">  <div class="form-group">
          <label for="date_debut" class="col-md-4 control-label">Date d ébut <span class="required">*</span></label>
          <div class="col-md-8">
            <input class="form-control datepicker required"  type="text" name="date_debut" id="date_debut"  value="<?php if(isset($row_edit_tache['date_debut'])) echo implode('/',array_reverse(explode('-',$row_edit_tache['date_debut']))); else echo date("d/m/Y"); ?>" size="10"  />
          </div>
        </div>      </td>
      <td bgcolor="#FFFF33">
        <div class="form-group">
          <label for="entite" class="col-md-4 control-label">Date fin<span class="required">*</span></label>
          <div class="col-md-8">
          <input class="form-control datepicker required"  type="text" name="date_fin" id="date_fin"  value="<?php if(isset($row_edit_tache['date_fin'])) echo implode('/',array_reverse(explode('-',$row_edit_tache['date_fin']))); else echo date("d/m/Y"); ?>" size="10"  />
          </div>
        </div>      </td>
    </tr>
       <tr valign="top">
         <td colspan="2"><div align="center"><span class="Style1"><?php echo "(".$mois_act.")";?></span></div></td>
        </tr>
</table>
<div class="form-actions"> 
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo ($_GET["id"]); ?>" />
<?php } ?>
  <input type="hidden" name="maxid" value="<?php echo $max_jalon_id; ?>" />
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
<a href="<?php echo $lien1; ?>" class="btn pull-right" title="Annuler" >Annuler</a>
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==0)) { ?>
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