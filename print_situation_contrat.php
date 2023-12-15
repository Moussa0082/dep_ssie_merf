<?php

///////////////////////////////////////////////

/*                 SSE                       */

/*	Conception & DÃƒÂ©veloppement: SEYA SERVICES */

///////////////////////////////////////////////

session_start();

include_once 'system/configuration.php';

$config = new Config;



if (!isset ($_SESSION["clp_id"])) {

  header(sprintf("Location: %s", "./"));

  exit;

}

include_once $config->sys_folder . "/database/db_connexion.php";

if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){

//header("Content-Type: application/vnd.ms-excel charset=ISO-8859-15'");

header("Expires: 0");

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header("content-disposition: attachment;filename=tableau_suivi_dano.xls"); }

else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){

header("Content-Type: application/vnd.ms-word charset=ISO-8859-15'");

header("Expires: 0");

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header("content-disposition: attachment;filename=tableau_suivi_dano.doc"); }



if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else {$annee=0;}
$cmp = 0;
if(isset($_GET['cmp']) && intval($_GET['cmp'])>0) $cmp = intval($_GET['cmp']);



//fonction calcul nb jour

function NbJours($debut, $fin) {

  $tDeb = explode("-", $debut);

  $tFin = explode("-", $fin);

  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);

  return (($diff / 86400)+1);

}

$editFormAction1 = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction1 .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//liste situation marchÃ©
 $query_liste_montant_contrat = "SELECT sum(montant_contrat) as montant   FROM ".$database_connect_prefix."contrat_prestation where  projet='".$_SESSION["clp_projet"]."'"; 
             try{
    $liste_montant_contrat = $pdar_connexion->prepare($query_liste_montant_contrat);
    $liste_montant_contrat->execute();
    $row_liste_montant_contrat = $liste_montant_contrat ->fetch();
    $totalRows_liste_montant_contrat = $liste_montant_contrat->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  
  if($totalRows_liste_montant_contrat>0){$montant_contrat=$row_liste_montant_contrat["montant"];} else $montant_contrat=0;
  
$liste_mois=array('1','2','3','4','5','6','7','8','9','10','11','12');
$tableauMois=array('1<>Jan<>J','2<>Fev<>F','3<>Mars<>M','4<>Avril<>A','5<>Mai<>M','6<>Juin<>J','7<>Juil<>J','8<>Aout<>A','9<>Sep<>S','10<>Oct<>O','11<>Nov<>N','12<>Déc<>D');

 $query_liste_montant_decompte = "SELECT sum(montant_decaisse) as montant_decompte, month(date_action) as mois   FROM ".$database_connect_prefix."contrat_prestation, ".$database_connect_prefix."suivi_decaissement where  projet='".$_SESSION["clp_projet"]."' and id_contrat=".$database_connect_prefix."suivi_decaissement.contrat  group by mois"; 
               try{
    $liste_montant_decompte = $pdar_connexion->prepare($query_liste_montant_decompte);
    $liste_montant_decompte->execute();
    $row_liste_montant_decompte = $liste_montant_decompte ->fetchAll();
    $totalRows_liste_montant_decompte = $liste_montant_decompte->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $Decompte_mois_array = array();
   //$Decompte_mois_array2 = array();
 $M_decompte_array = array();
if($totalRows_liste_montant_decompte>0){ foreach($row_liste_montant_decompte as $row_liste_montant_decompte){
$M_decompte_array[]=$row_liste_montant_decompte["mois"]; 
 $Decompte_mois_array[$row_liste_montant_decompte["mois"]]=$row_liste_montant_decompte["montant_decompte"]; 
 // $Decompte_mois_array2[$row_liste_montant_decompte["mois"]]=$row_liste_montant_decompte["montant_decompte"]; 
} }

 
 //} 
 $v=0; foreach($M_decompte_array as $moisd){
 if($montant_contrat>0) {
  $v=$v+100*$Decompte_mois_array[$moisd]/$montant_contrat;
  $Decompte_mois_array[$moisd]=$v; 
  } else  $Decompte_mois_array[$moisd]=0;
        }
 $Nom_mois_array = array();		
   foreach($tableauMois as $vmois){
		$amois = explode('<>',$vmois); 
		$Nom_mois_array[$amois[0]]=$amois[1];
     } 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<?php if(!isset($_GET["down"])){  ?>

<head>

  <title><?php print $config->sitename; ?></title>

  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />

  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

  <meta name="keywords" content="<?php print $config->MetaKeys; ?>" />

  <meta name="description" content="<?php print $config->MetaDesc; ?>" />

  <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->

  <meta name="author" content="<?php print $config->MetaAuthor; ?>" />

  <!--<meta charset="utf-8">-->

  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>



  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->

  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder; ?>/login.css" rel="stylesheet" type="text/css"/>

  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">


  <link href='<?php print $config->theme_folder; ?>/css.css' rel='stylesheet' type='text/css'>


  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>

  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>

  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/lodash.compat.min.js"></script>


  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>

  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>

  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>

  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/breakpoints.js"></script>

  <script type="text/javascript" src="plugins/respond/respond.min.js"></script>

  <script type="text/javascript" src="plugins/cookie/jquery.cookie.min.js"></script>

  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.min.js"></script>

  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>

  <script type="text/javascript" src="plugins/blockui/jquery.blockUI.min.js"></script>

  <script type="text/javascript" src="plugins/pickadate/picker.js"></script>

  <script type="text/javascript" src="plugins/pickadate/picker.date.js"></script>

  <script type="text/javascript" src="plugins/pickadate/picker.time.js"></script>

  <script type="text/javascript" src="plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>

  <script type="text/javascript" src="plugins/fullcalendar/fullcalendar.min.js"></script>

  <script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>

  <script type="text/javascript" src="plugins/noty/layouts/top.js"></script>

  <script type="text/javascript" src="plugins/noty/themes/default.js"></script>

  <script type="text/javascript" src="plugins/uniform/jquery.uniform.min.js"></script>

  <script type="text/javascript" src="plugins/select2/select2.min.js"></script>

  <script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>

  <script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>

  <script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/app.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/plugins.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/plugins.form-components.js"></script>

<!--

  <script type="text/javascript" src="<?php print $config->
script_folder; ?>/custom.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/pages_calendar.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_filled_blue.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_simple.js"></script>-->

 <script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>

 <script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder; ?>/login.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder; ?>/myscript.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/ui_general.js"></script>

 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>
 
 		<script type="text/javascript">
$(function () {
    $('#container_graph').highcharts({
        chart: {
            type: 'line'
        },
        title: {
            text: 'Evolution taux de realisation'
        },
        subtitle: {
            text: 'Par mois'
        },
        xAxis: {
		
            categories: [ <?php foreach($M_decompte_array as $c) if(isset($Nom_mois_array[$c])) echo "'".$Nom_mois_array[$c]."'".","; else echo $c.",";  ?> ]
        },
        yAxis: {
		   min: 0,



                max: 100,
            title: {
                text: '( Decomptes )'
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: true
				
            }
        },
        series: [{



                name: '<?php echo ("Décompte menusuel"); ?>',



                data: [<?php foreach($Decompte_mois_array as $c) echo number_format($c, 0, ',', ' ').",";  ?>]



            }]
    });
});

		</script>

</head>

<?php }  ?>

<body>

 <header class="header navbar navbar-fixed-top" role="banner">

    <?php if(!isset($_GET["down"])) include_once("includes/header.php"); ?>

 </header>

<div id="container">

    <div id="sidebar" class="sidebar-fixed">

        <div id="sidebar-content">

            <?php if(!isset($_GET["down"])) include_once("includes/menu_top.php"); ?>

        </div>

        <div id="divider" class="resizeable"></div>

    </div>



    <div id="content">

        <div class="container">

            <div class="crumbs">

                <?php if(!isset($_GET["down"])) include_once("includes/sous_menu.php"); ?>

            </div>

        <div class="page-header">

            <div class="p_top_5">

<!-- Site contenu ici -->

<style>#sp_hr {margin:0px; }

.r_float{float: right;}

.Style11 { font-weight: bold;color: #FFFFFF;}

.well {margin-bottom: 5px;}

#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {

  border-spacing: 0px !important; border-collapse: collapse; font-size: small;

} .table tbody tr td {vertical-align: middle; }

</style>

<div class="contenu">

<?php if(!isset($_GET["down"])){  ?>

<div class="well well-sm r_float"><div class="r_float"><a href="./s_programmation.php" class="button">Retour</a></div>

<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction1."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>

<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction1."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>

<!--<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format PDF" href="<?php echo $editFormAction1."&down=1&t=p"; ?>" class="button"><img src="./images/pdf.png" width='20' height='20' alt='Modifier' /></a></div>--></div>

<div class="clear h0">&nbsp;</div>

<?php } else { ?>



<center><?php //include "./includes/print_header.php"; ?></center>



<?php } ?>

<?php /*foreach($M_decompte_array as $moisd){
 
 echo "Mois=".$moisd." et Valeur=".$Decompte_mois_array[$moisd]."</br>";
        } */?>
<?php //foreach($M_decompte_array as $c) if(isset($Nom_mois_array[$c])) echo $Nom_mois_array[$c].","; else echo $c.",";  ?>
 <script src="<?php print $config->script_folder; ?>/highcharts.js"></script>

  <script src="<?php print $config->script_folder; ?>/modules/exporting.js"></script>

<div id="container_graph" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
</div>
            </div>

       
        </div>
        </div>

    </div>   <?php if(!isset($_GET["down"])) include_once 'modal_add.php'; ?>

    <?php include_once("includes/footer.php"); ?>

</div>



</body>

</html>