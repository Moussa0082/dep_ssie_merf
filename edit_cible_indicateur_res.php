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

if(isset($_GET['id_ind'])) { $id_ind = $_GET['id_ind']; }

if(isset($_GET['code_act'])) {$code_activite = $_GET['code_act'];} else $code_activite="";



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

$lien .= "?annee=$annee";

$lien1 .= "?annee=$annee";



if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))

{   $id_ind=$_POST['id']; $prd=$_POST['prd'];

  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

  $annee=$_POST['annee'];

  $valind=$_POST['valind'];

 // $id_region=$_POST['id_region'];



  //suppression



       $insertSQL = sprintf("DELETE from cible_cmr_resultat WHERE indicateur_resultat=%s",
                           GetSQLValueString($id_ind, "int"));
	   try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }



 //echo "je suis la";

  // `indicateur` int(11) NOT NULL,   `mois` int(11) DEFAULT NULL,  `cible` float DEFAULT '0',

  foreach ($annee as $key => $value)

  {

  	if(isset($valind[$key]) && $valind[$key]!=NULL) {

  	if(trim(strtolower($valind[$key]=="oui"))) $valind[$key] = "0";

  elseif(trim(strtolower($valind[$key]=="non"))) $valind[$key] = "1";



    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."cible_cmr_resultat  (indicateur_resultat, annee, valeur_cible, id_personnel, date_enregistrement) VALUES (%s, %s, %s, '$personnel', '$date')",

  					   GetSQLValueString($id_ind, "text"),

  					   //  GetSQLValueString($id_region[$key], "int"),

  					   GetSQLValueString($annee[$key], "text"),

  					   GetSQLValueString($valind[$key], "double"));

		   try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  

      }

    }

    /*$insertGoTo = $_SERVER['PHP_SELF'];

      if ($Result1) $insertGoTo .= "?insert=ok&id_ind=$id_ind"; else $insertGoTo .= "?insert=no&id_ind=$id_ind";

    header(sprintf("Location: %s", $insertGoTo)); 



  <script type="text/javascript">

  $("#acharger<?php echo $prd; ?>", window.parent.document).html(get_content('cmr_resultat_reload.php','id=<?php echo $prd; ?>','acharger<?php echo $prd; ?>','','',1));

  $(".close", window.parent.document).click();

  </script>

  <?php exit(0);*/ 

}

if(isset($_GET["id_ind"])){ $id_ind=$_GET['id_ind']; // $annee=$_GET['annee'];

   /* mysql_select_db($database_pdar_connexion, $pdar_connexion);

	$query_indicateur_courant = "SELECT * FROM indicateur_resultat_cmr, resultat, indicateur_resultat where id_indicateur_resultat=indicateur_prd and id_resultat=resultat and id_indicateur=$id_ind";

	$indicateur_courant  = mysql_query_ruche($query_indicateur_courant , $pdar_connexion) or die(mysql_error());

	$row_indicateur_courant  = mysql_fetch_assoc($indicateur_courant);

	$totalRows_indicateur_courant  = mysql_num_rows($indicateur_courant);*/




			$query_cible_indicateur = "SELECT id_cible_cr, annee, valeur_cible FROM   ".$database_connect_prefix."cible_cmr_resultat where indicateur_resultat='$id_ind'";

						try{
    $cible_indicateur = $pdar_connexion->prepare($query_cible_indicateur);
    $cible_indicateur->execute();
    $row_cible_indicateur = $cible_indicateur ->fetchAll();
    $totalRows_cible_indicateur = $cible_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
			$cible_array = array();
			 if($totalRows_cible_indicateur>0) { foreach($row_cible_indicateur as $row_cible_indicateur){  
            $cible_array[$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_cible"]; }}



			/*mysql_select_db($database_pdar_connexion, $pdar_connexion);

			//$ind_courant=$row_ind['id_indicateur_tache'];

			$query_cible_tindicateur = "SELECT sum(valeur_cible) as cible_total FROM   ".$database_connect_prefix."cible_cmr_resultat where indicateur_resultat='$id_ind' group by zone";

			$cible_tindicateur  = mysql_query_ruche($query_cible_tindicateur , $pdar_connexion) or die(mysql_error());

			$row_cible_tindicateur = mysql_fetch_assoc($cible_tindicateur );

			$totalRows_cible_tindicateur = mysql_num_rows($cible_tindicateur );

			$tcible_array = array();

            if($totalRows_cible_tindicateur>0){  do{ $tcible_array[$row_cible_tindicateur["zone"]]=$row_cible_tindicateur["cible_total"]; }

			while($row_cible_tindicateur  = mysql_fetch_assoc($cible_tindicateur));}*/

  }





/*mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_zone = "SELECT * FROM ".$database_connect_prefix."ugl ORDER BY code_ugl";

$liste_zone  = mysql_query_ruche($query_liste_zone , $pdar_connexion) or die(mysql_error());

$row_liste_zone  = mysql_fetch_assoc($liste_zone );

$totalRows_liste_zone  = mysql_num_rows($liste_zone );*/

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

<?php if(isset($_GET['add'])) { ?>

        $("#ui-datepicker-div").remove();

        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});

<?php } ?>

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

<div>

<div class="widget box ">

 <div class="widget-header"> <h4><i class="icon-reorder"></i>  <strong>Valeurs cibles annuelles 

 </strong><span class="Style18"></span> </h4>

 </div>

<div class="widget-content">

<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">

<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >

    <?php $t=0; // if($totalRows_liste_zone>0) { ?>

    <tr class="titrecorps2">


  <?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>

  <td><div align="center" class="Style31"><strong><?php echo $i; ?></strong></div></td><?php } ?>

    </tr>

    <?php $p1="j"; $t=0; $i=0;//do { ?>

    <tr <?php //if($i%2==0) echo 'bgcolor="#D2E2B1"';  $i=$i+1; $t=$t+1;?>>


      <?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>

<td><div align="center" class="Style31">

<input name='valind[]' class="form-control" type="text" size="5"  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']>3) echo "disabled"; ?> value="<?php

 if(isset($cible_array[$i]))

 {

 if(isset($row_indicateur_courant['referentiel']) && isset($unite_ind_ref_array[$row_indicateur_courant['referentiel']]) && trim($unite_ind_ref_array[$row_indicateur_courant['referentiel']])=="Oui/Non"){

    if($cible_array[$i]==0) echo "Oui"; else echo "Non"; } else echo $cible_array[$i];} ?>"/>

  <input name="annee[]" type="hidden" size="5" value="<?php echo $i; ?>"/>


 </div></td>

<?php } ?>

    </tr>

    <?php //} while ($row_liste_zone = mysql_fetch_assoc($liste_zone)); ?>

    <?php // } else echo "<h3>Aucune zone disponible</h3>" ;?>

  </table>

<div class="form-actions">

<?php if(isset($_GET["id_ind"])){ ?>

  <input type="hidden" name="id" value="<?php echo $_GET["id_ind"]; ?>" />

  <input type="hidden" name="prd" value="<?php echo $prd; ?>" />

  <?php } ?>

  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />

  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">



<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">

  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->

</div>

</form></div>

</div>

</div>



<?php include_once 'modal_add.php'; ?>

</body>

</html>