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



//if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y"); if(isset($_GET['cp'])) {$cp=$_GET['cp'];} else $cp=0;


if(isset($_GET['code_modele'])) { $code_modele = $_GET['code_modele']; }$code_modele = "Test";

if(isset($_GET['id_modele'])) { $modele = $_GET['id_modele']; }else $modele = 1;



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



$annee = (isset($_GET["annee"]))?intval($_GET["annee"]):date("Y");



$lien = $lien1 = $_SERVER['PHP_SELF'];

$lien .= "?id_modele=$modele&code_modele=$code_modele";

$lien1 .= "?id_modele=$modele&code_modele=$code_modele";

if(isset($_GET["id_mar"])) { $id=$_GET["id_mar"];} else $id=0; ;
$query_edit_marche = "SELECT * FROM ".$database_connect_prefix."plan_marche WHERE id_marche='$id'";
       try{
    $edit_marche = $pdar_connexion->prepare($query_edit_marche);
    $edit_marche->execute();
    $row_edit_marche = $edit_marche ->fetch();
    $totalRows_edit_marche = $edit_marche->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$meth=$row_edit_marche['methode'];
$modele=$row_edit_marche['modele_marche'];
/*
if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sup_etape = sprintf("DELETE from ".$database_connect_prefix."etape_marche WHERE id_etape=%s",
                         GetSQLValueString($id, "text"));
  $Result1 = mysql_query_ruche($query_sup_etape, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
 }*/

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))

{   $id=$_POST['id'];

  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

 $duree_prevue=$_POST['duree'];
  $intitule=$_POST['intitule'];
  $code=$_POST['code'];
  $id_etape=$_POST['id_etape'];



  //suppression
 // mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_sup_cible_indicateur = "DELETE FROM ".$database_connect_prefix."etape_plan_marche WHERE marche=$id";
  	    try{
    $Result1 = $pdar_connexion->prepare($query_sup_cible_indicateur);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  foreach ($id_etape as $key => $value)

  {


	  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."etape_plan_marche (marche, idetape, code_etape, intitule_etape, duree_prevue, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, '$personnel', '$date')",

						GetSQLValueString($id, "int"),
						GetSQLValueString($id_etape[$key], "int"),
						GetSQLValueString($code[$key], "int"),
						GetSQLValueString($intitule[$key], "text"),
						GetSQLValueString($duree_prevue[$key], "int"));
                       // GetSQLValueString(, "int"));
	    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

    }

}

/*if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))

{

    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];



  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."etape_marche (modele_concerne, code, intitule, duree, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, '$personnel', '$date')",

						GetSQLValueString($modele, "int"),
						GetSQLValueString($_POST['code'], "int"),
						GetSQLValueString($_POST['intitule'], "text"),
                      //  GetSQLValueString($_POST['description'], "text"),
                        GetSQLValueString($_POST['duree'], "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $insertGoTo = $_SERVER['PHP_SELF'];
  $insertGoTo .= "?id_modele=$modele&code_modele=$code_modele";
  if ($Result1) $insertGoTo .= "&insert=ok";
  else $insertGoTo .= "&insert=no";
  header(sprintf("Location: %s", $insertGoTo));
}
}*/

$tm=0;
$query_liste_zone = "SELECT marche, idetape as id_etape, code_etape as code, intitule_etape as intitule, duree_prevue as duree FROM ".$database_connect_prefix."etape_plan_marche where 	marche=$id ORDER BY code_etape asc";
       try{
    $liste_zone = $pdar_connexion->prepare($query_liste_zone);
    $liste_zone->execute();
    $row_liste_zone = $liste_zone ->fetchAll();
    $totalRows_liste_zone = $liste_zone->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if(!$totalRows_liste_zone) {
$tm=1;
$query_liste_zone = "SELECT * FROM ".$database_connect_prefix."etape_marche where 	modele_concerne=$modele ORDER BY code asc";
       try{
    $liste_zone = $pdar_connexion->prepare($query_liste_zone);
    $liste_zone->execute();
    $row_liste_zone = $liste_zone ->fetchAll();
    $totalRows_liste_zone = $liste_zone->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}


$query_edit_modele = "SELECT * FROM ".$database_connect_prefix."modele_marche where id_modele='$modele' ORDER BY code asc";
       try{
    $edit_modele = $pdar_connexion->prepare($query_edit_modele);
    $edit_modele->execute();
    $row_edit_modele = $edit_modele ->fetch();
    $totalRows_edit_modele = $edit_modele->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$libelle_modele=$row_edit_modele['code']."- ".$row_edit_modele['categorie']." (".$row_edit_modele['methode_concerne'].")";

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

	  $(".modal-dialog", window.parent.document).width(800);

		// validate the comment form when it is submitted

		$("#form1").validate();

<?php if(!isset($_GET['add'])) { ?>

$(".dataTable").dataTable({"iDisplayLength": -1});



<?php } ?>

<?php //if(isset($_GET['add'])) { ?>

        $("#ui-datepicker-div").remove();

        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});

<?php //} ?>

	});

function check_proportion(){

  var p = <?php echo (isset($proportion) && !empty($proportion))?$proportion:0;  ?>;

  if(document.form1.proportion.value><?php echo (isset($proportion) && !empty($proportion))?$proportion:0;  ?>){ document.form1.proportion.value=<?php echo (isset($proportion) && !empty($proportion))?$proportion:0;  ?>; }

}

</script>

<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {

  border-spacing: 0px !important; border-collapse: collapse; font-size: small;

} .table tbody tr td {vertical-align: middle; }

#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}

.dataTables_length, .dataTables_info { float: left;} .dataTables_paginate, .dataTables_filter { float: right;}

.dataTables_length, .dataTables_paginate { display: none;}



</style>

</head>

<body>
<?php if(!isset($_GET['add'])) { ?>
<div>

<div class="widget box ">

 <div class="widget-header"> <h4><i class="icon-reorder"></i>  <strong>Mod&egrave;le de passation: </strong><span class="Style18">

 </span><?php echo $libelle_modele; ?> </h4>

<div>

<strong><u>Intitul&eacute;</u>:<span class="Style14">

<?php if (isset ($row_edit_marche['intitule'])) echo $row_edit_marche['intitule'];?>

</span><br />

<u>Date d&eacute;but pr&eacute;vue </u>:

<?php if (isset ($row_edit_marche['date_prevue'])) echo date("d/m/y", strtotime($row_edit_marche['date_prevue']));?>

<br />
<u>Co&ucirc;t pr&eacute;vu (USD)</u>:

<?php if (isset ($row_edit_marche['montant_usd'])) echo  number_format($row_edit_marche['montant_usd'], 0, ',', ' ');?>
</strong></div>

 </div>

<div class="widget-content">
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1) && !check_user_auth('page_edit',"categorie_marche.php")) { ?>
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">
<?php } ?>

<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >

    <?php $t=0;  if($totalRows_liste_zone>0) { ?>

    <tr class="titrecorps2">

  <td width="5%">Ordre</td>


  <td><div align="center" class="Style31"><strong>Libell&eacute;</strong></div></td>
    <td nowrap="nowrap"><div align="center"><strong>Dur&eacute;e (J) </strong></div></td>
    <td width="17%"><strong>Date pr&eacute;vue</strong> </td>
    <?php //if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
    <!--<td width="5%">Supr.</td>-->
    <?php //} ?>
    </tr>

 <?php $p1="j"; $t=0; $i=0; $duree_totale=0; $etape_0="00-00-0000"; $duree_suivie=0; $etape_0s="00-00-0000";  foreach($row_liste_zone as $row_liste_zone){   $i++; 
	$date_start = $row_edit_marche['date_prevue'];
	//if(isset($tm) && ($tm==1)) { 
	$duree_totale=$duree_totale+$row_liste_zone['duree']; 
	//} else {$duree_et= }
	
	?>

    <tr <?php //if($i%2==0) echo 'bgcolor="#D2E2B1"';  $i=$i+1; $t=$t+1;?>>

<td><span class="">
        <input name='code[]' class="form-control" type="text" size="1" readonly="yes"  value="<?php echo $i;//$row_liste_zone['code']; ?>"/>
      </span></td>


<td><div align="center" class="Style31">

<input name='intitule[]' class="form-control" type="text" size="5"  value="<?php echo $row_liste_zone['intitule']; ?>"/>
<input name="id_etape[]" type="hidden" size="5" value="<?php echo $row_liste_zone['id_etape']; ?>"/>

 </div></td>


    <td width="7%"><div align="center"><span class="Style31">
     <input name='duree[]' class="form-control" type="text" size="1"  value="<?php echo $row_liste_zone['duree']; ?>"/>
    </span></div></td>
    <td><span class="Style31">
	

 <input name='dats[]' class="form-control datepicker" type="text" size="1"   value="<?php echo date("d/m/Y", strtotime('+'.$duree_totale.'days', strtotime($date_start))); ?>"/>

    </span></td>
    <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>

       <!--<td align="center">
<?php //echo do_link("",$lien."&id_sup=".$row_liste_zone['id_etape'],"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette etape ?');",0,"margin:0px 5px;","categorie_marche.php"); ?>
<a onClick="return confirm('Voulez vous vraiment suppimer cette etape ?');" href="<?php echo $lien."&id_sup=".$row_liste_zone['id_etape'].""; ?>" title="Supprimer l'étape" ><img align="center" src='./images/delete.png' width='20' height='20' alt='Supprimer' style="margin:0px 5px 0px 0px;"></a></td>-->

      <?php } ?>
    </tr>

    <?php }  ?>

    <?php } else echo "<h3>Aucune &eacute;tape disponible</h3>" ;?>
  </table>
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1) && !check_user_auth('page_edit',"categorie_marche.php")) { ?>
<div class="form-actions">

<?php if(isset($_GET["id_mar"])){ ?>
<input type="hidden" name="id" value="<?php echo $id; ?>" />

  <?php } ?>

  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />

  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">



<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">

  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->

</div>

</form>
<?php } ?>
</div>

</div>

</div>

<?php //} elseif(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)){ ?>

<?php } ?>


<?php include_once 'modal_add.php'; ?>

</body>

</html>