<?php

///////////////////////////////////////////////

/*                 SSE                       */

/*	Conception & D�veloppement: SEYA SERVICES */

///////////////////////////////////////////////

session_start();

include_once 'system/configuration.php';

$config = new Config;

//echo $_GET["id"];

if (!isset ($_SESSION["clp_id"]) && isset($_GET["code_act"]) && !empty($_GET["id"])) {

  //header(sprintf("Location: %s", "./"));

  exit;

}

include_once $config->sys_folder . "/database/db_connexion.php";

header('Content-Type: text/html; charset=UTF-8');




if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y"); //if(isset($_GET['cp'])) {$cp=$_GET['cp'];} else $cp=0;

if(isset($_GET['code_act'])) { $code_act = $_GET['code_act']; }

if(isset($_GET['id_act'])) {$id_act = $_GET['id_act'];} else $id_act="0";



if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))

{

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

//$code_act=$_POST['activite'];

$montant=$_POST['montant'];

$convention=$_POST['convention'];

//$trimestre=$_POST['trimestre'];

//suppression

//foreach ($convention as $key => $value)

//{

 // $idin=$id_ind[$key];

  $query_sup_cible_indicateur = "DELETE FROM part_bailleur WHERE activite='$id_act' and annee=$annee and projet='".$_SESSION["clp_projet"]."'"; 
      try{
    $Result1 = $pdar_connexion->prepare($query_sup_cible_indicateur);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
//}



// `indicateur` int(11) NOT NULL,   `mois` int(11) DEFAULT NULL,  `cible` float DEFAULT '0',

foreach ($convention as $key => $value)

{

	if(isset($montant[$key]) && $montant[$key]!=NULL) {

  $insertSQL = sprintf("INSERT INTO part_bailleur  (projet, activite, annee, type_part, montant, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, '$personnel', '$date')",

                       GetSQLValueString($_SESSION["clp_projet"], "text"),
                     //  GetSQLValueString($_SESSION["clp_structure"], "text"),
					    GetSQLValueString($id_act, "text"),

					 //  GetSQLValueString($code_act, "text"),

					    GetSQLValueString($annee, "int"),

					   GetSQLValueString($convention[$key], "text"),

					   GetSQLValueString($montant[$key], "double"));  
   try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

    }

  }
 $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?insert=ok&mod=1&annee=$annee&code_act=$code_act&id_act=$id_act";
  else $insertGoTo .= "?insert=no&annee=$annee&code_act=$code_act&id_act=$id_act";
    header(sprintf("Location: %s", $insertGoTo)); exit(0);

}


$query_liste_convention = "SELECT * FROM type_part WHERE projet='".$_SESSION["clp_projet"]."' order by code_type";
	   try{
    $liste_convention = $pdar_connexion->prepare($query_liste_convention);
    $liste_convention->execute();
    $row_liste_convention = $liste_convention ->fetchAll();
    $totalRows_liste_convention = $liste_convention->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableauConvention=$tableauConventionV=array();
 if($totalRows_liste_convention>0) { foreach($row_liste_convention as $row_liste_convention){  
$tableauConventionV[$row_liste_convention["code_type"]]=$row_liste_convention["intitule"];
$tableauConvention[$row_liste_convention["code_type"]]=$row_liste_convention["code_type"]."<>".$row_liste_convention['bailleur'];
 } }


$query_liste_dotation_convention = "SELECT type_part, montant FROM part_bailleur where  activite='$id_act' and annee=$annee and projet='".$_SESSION["clp_projet"]."' order by type_part";
	   try{
    $liste_dotation_convention = $pdar_connexion->prepare($query_liste_dotation_convention);
    $liste_dotation_convention->execute();
    $row_liste_dotation_convention = $liste_dotation_convention ->fetchAll();
    $totalRows_liste_dotation_convention = $liste_dotation_convention->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableauConventionDotation=array();
 if($totalRows_liste_dotation_convention>0) { foreach($row_liste_dotation_convention as $row_liste_dotation_convention){  
$tableauConventionDotation[$row_liste_dotation_convention["type_part"]]=$row_liste_dotation_convention["montant"];
 } }
 


/*mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_code_budget = "SELECT left(code_categorie,2) as cat, sum(`cout_prevu`) as cout_tot, sum(cout_realise) as realise_tot FROM ".$database_connect_prefix."code_analytique WHERE code_activite_ptba='$code_act' and annee=$annee group by cat";

$liste_code_budget   = mysql_query_ruche($query_liste_code_budget , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_liste_code_budget   = mysql_fetch_assoc($liste_code_budget );

$totalRows_liste_code_budget   = mysql_num_rows($liste_code_budget );

$tableauConventionCout=array();

if($totalRows_liste_code_budget>0){  do{

$tableauConventionCout[$row_liste_code_budget["cat"]]=$row_liste_code_budget["cout_tot"];

}while($row_liste_code_budget  = mysql_fetch_assoc($liste_code_budget));  } */



if(isset($_GET['mod']))

{

  $query_liste_cout_saisi = "SELECT activite, SUM( if(montant>0, montant,0) ) AS montant  FROM part_bailleur where activite='$id_act' and annee=$annee and projet='".$_SESSION["clp_projet"]."' group by activite";
  
  	   try{
    $liste_cout_saisi = $pdar_connexion->prepare($query_liste_cout_saisi);
    $liste_cout_saisi->execute();
    $row_liste_cout_saisi = $liste_cout_saisi ->fetchAll();
    $totalRows_liste_cout_saisi = $liste_cout_saisi->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableauCoutSaisi=array();
 if($totalRows_liste_cout_saisi>0) { foreach($row_liste_cout_saisi as $row_liste_cout_saisi){  
  $tableauCoutSaisi[$row_liste_cout_saisi["activite"]]=$row_liste_cout_saisi["montant"];
 } }

  if(isset($tableauCoutSaisi[$id_act]))  $cout_saisi=$tableauCoutSaisi[$id_act]; else $cout_saisi="";

}


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

<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>

<script>

	$().ready(function() {

		// validate the comment form when it is submitted

		$("#form0").validate();

        $("#tabs").tabs();

        $(".modal-dialog", window.parent.document).width(600);

<?php if(isset($_GET['mod']) && $cout_saisi!="") { ?>

        // reload parent frame

        $(".close", window.parent.document).click(function(){

          //window.parent.location.reload();

          $("#cout_<?php echo $annee.$id_act; ?>", window.parent.document).html('<?php echo number_format($cout_saisi, 0, ',', ' '); ?>');

        });

        $("button[data-dismiss='modal']", window.parent.document).click(function(){

          //window.parent.location.reload();

          $("#cout_<?php echo $annee.$id_act; ?>", window.parent.document).html('<?php echo number_format($cout_saisi, 0, ',', ' '); ?>');

        });

<?php } ?>

	});

</script>

<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {

  border-spacing: 0px !important; border-collapse: collapse; font-size: small;

} .table tbody tr td {vertical-align: middle; }

#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}

@media(min-width:558px){.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}

</style>

</head>

<body>

<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i> Planification du budget</h4></div>

<div class="widget-content">

<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">

 <div align="center" style="  height: 350px; overflow: scroll;">

<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive dataTable " align="center" >

<thead>

  <tr>

    <td><div align="left"><strong>Convention</strong></div></td>

	 <td><div align="center"><strong>Montant SE </strong></div></td>

   

    <!-- <td><div align="center"><strong>Montant import&eacute; </strong></div></td>-->

  </tr>

</thead>

<tbody class="convention">

  <?php if(is_array($tableauConvention)) { global $j; $j=0; //$rg = explode('|',$row_liste_cat['convention_concerne']);

  foreach($tableauConvention as $vconvention) { $aconvention = explode('<>',$vconvention); $iconvention = $aconvention[0];

    echo '<tr><td ><span style="font-size:12px"><i class="icon-angle-right"></i> '.$tableauConventionV[$iconvention].'</span></td>'; ?>

	<td align="center">

	<input name="montant[]" type="text" style="text-align:right"  value= "<?php if(isset($tableauConventionDotation[$iconvention])) echo number_format($tableauConventionDotation[$iconvention], 0, ',', ' '); ?>" size="15" class="form-control" />

	<input name="convention[]" type="hidden"  value=" <?php echo  $iconvention ?>" />

    </td>

	<!--<td align="center"><span style="font-size:12px"><?php //if(isset($tableauConventionCout[$iconvention])) echo number_format($tableauConventionCout[$iconvention], 0, ',', ' '); ?></span></td>-->

    <?php  } } ?>

</tbody>

</table>

</div>

<div class="form-actions">

  <input name="activite" type="hidden" value="<?php if(isset($_GET["code_act"]) && !empty($_GET["code_act"])) echo $_GET["code_act"]; ?>" size="32" alt="">

    <input name="id_act" type="hidden" value="<?php if(isset($_GET["id_act"]) && !empty($_GET["id_act"])) echo $_GET["id_act"]; ?>" size="32" alt="">

  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php echo "Valider" ; ?>" />

  <input name="<?php if(isset($_GET["id_act"]) && !empty($_GET["id_act"]) && $totalRows_liste_dotation_convention>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id_act"]) && !empty($_GET["id_act"]) && $totalRows_liste_dotation_convention>0) echo $_GET["id_act"]; else echo "MM_insert" ; ?>" size="32" alt="">

<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">

</div>

</form>



</div> </div>

</body>

</html>