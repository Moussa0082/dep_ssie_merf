<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*  Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
if (!isset ($_SESSION["clp_id"])) {
    header(sprintf("Location: %s", "./login.php"));  exit();
}
include_once 'api/configuration.php';
$config = new Config;

require_once 'api/Fonctions.php';
require_once 'theme_components/theme_style.php';

$Res5 = null;
 $Code_Rapport="";     
      if(isset($_GET['r']) AND !empty($_GET['r']))
      {$Code_Rapport=base64_decode($_GET['r']);
        $Nom_Rapport="";
        $ii=0;
        foreach (FC_Rechercher_Code('SELECT * FROM t_rapport WHERE Code_Rapport=\''.$Code_Rapport.'\'') as $row4) 
        {$ii++; $uuu=0;
          $Nom_Rapport=$row4['Nom_Rapport'];
          
        if($ii==0){header('location:rapports_dynamiques.php');}

      }
    }
      else
        {header('location:rapport_dynamiques.php');}
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <title><?php print $config->sitename;?></title>
    <link rel="shortcut icon" type="image/ico" href="<?php print $config->icon_folder;?>/favicon.ico" />
    <meta name="keywords" content="<?php print $config->MetaKeys;?>" />
    <meta name="description" content="<?php print $config->MetaDesc;?>" />
    <meta name="author" content="<?php print $config->MetaAuthor;?>" />

    <!-- Vendor styles -->
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.css" />
    <link rel="stylesheet" href="vendor/metisMenu/dist/metisMenu.css" />
    <link rel="stylesheet" href="vendor/animate.css/animate.css" />
    <link rel="stylesheet" href="vendor/bootstrap/dist/css/bootstrap.css" />

    <!-- App styles -->
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/helper.css" />
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/style_fst.css">

    <!-- Vendor scripts -->
    <script src="vendor/jquery/dist/jquery.min.js"></script>
    <script src="vendor/jquery-ui/jquery-ui.min.js"></script>
    <script src="vendor/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="vendor/jquery-flot/jquery.flot.js"></script>
    <script src="vendor/jquery-flot/jquery.flot.resize.js"></script>
    <script src="vendor/jquery-flot/jquery.flot.pie.js"></script>
    <script src="vendor/flot.curvedlines/curvedLines.js"></script>
    <script src="vendor/jquery.flot.spline/index.js"></script>
    <script src="vendor/metisMenu/dist/metisMenu.min.js"></script>
    <script src="vendor/iCheck/icheck.min.js"></script>
    <script src="vendor/peity/jquery.peity.min.js"></script>
    <script src="vendor/sparkline/index.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="vendor/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.fr.min.js"></script>

    <!-- DataTables -->
    <script src="vendor/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- DataTables buttons scripts -->
    <script src="vendor/pdfmake/build/pdfmake.min.js"></script>
    <script src="vendor/pdfmake/build/vfs_fonts.js"></script>
    <script src="vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>

    <script src="scripts/new_highcharts.js"></script>
    <script src="scripts/data.js"></script>
    <script src="scripts/drilldown.js"></script>

    <!-- App scripts -->
    <script src="scripts/homer.js"></script>

</head>
<body class="fixed-navbar fixed fixed-footer sidebar-scroll">
    <?php require_once "./theme_components/header.php"; ?>
    <?php //require_once "./theme_components/main-menu.php"; ?>
<!-- Main Wrapper -->
<div id="">
<?php require_once "./theme_components/sub-header.php"; ?>
    <div class="content animate-panel">
        <div class="row">
<script>
$("#search").hide();
$(document).ready(function(){$(".modal").off( "hidden.bs.modal", null );});
$("#mbreadcrumb").html(<?php $link = '<div class="btn-circle-zone"><a href="./rapports_dynamiques.php" class="btn btn-success btn-circle mgr-5" style="transform: rotate(180deg);" title="Retour aux rapports" ><span title="Retour à la liste des rapports" class="glyphicon glyphicon-share-alt"></span></a>';
$link .= do_link("btn_feuille","#add_feuille_modal","Imprimer ce rapport","<span title='Imprimer ce rapport' class='glyphicon glyphicon-print'></span>","simple","./","btn btn-success btn-circle mgr-5","window.print();",1,"",$nfile);
$link .= '</div>';
echo GetSQLValueString($link, "text"); ?>);
</script>

<style type="text/css">
@media print{
    #header, #menu, .bar_header, .showhide, .small-header, .footer {display: none}
    #wrapper{margin :0px; padding: 0px; width: 100%}

    body{margin: 0; padding: 0}
    .contenu{display: table; width: 20cm; margin :0px; padding: 0px}
    .graph_div{break-before: always;}
}
.flot-chart-pie-content {width: 100%; height: 100%; margin: auto;}
.flot-pie-chart {display: block; padding-top: 0px; height: 500px;}
</style>

<?php echo '<script type="text/javascript" charset="utf-8" > var Code_Rapport="'.base64_encode($Code_Rapport).'";</script>'; ?>

<form id="form_perso_feuille_donnees">
</form>

 <div class="content" style="background: #FFF;padding: 0 10px;">

<div  style="background-color: white">
<?php require_once'requires/formulaire_insertion_123.php'; ?>
<div class="tab-content">
    <?php
$ind=0;
    foreach (FC_Rechercher_Code('SELECT * FROM t_rapport WHERE Code_Rapport='.$Code_Rapport) as $row5)
    {$ind++;
?>

<div class="row" style="font-size: 14px" align="left">
    <br>
   <div class="col-lg-2" style="cursor: pointer; display: none"><span class="glyphicon glyphicon-asterisk <?php echo $Text_Style; ?>">
   </span><span class="dropdown label ">
   <a class="dropdown-toggle label-menu-corner <?php echo $Text_Style; ?>" href="#" data-toggle="dropdown" >Imprimer</a>
                   </span>
   </div> 

    <div class="col-lg-2" style="cursor: pointer; display: none"><span class="glyphicon glyphicon-asterisk <?php echo $Text_Style; ?>"></span>
      <span class="dropdown label " >
        <a class="dropdown-toggle label-menu-corner <?php echo $Text_Style; ?>" href="#" data-toggle="dropdown">Affichage sur mobile</a>

    </span></div>

    <div class="col-lg-2"></div> 

    <div class="col-lg-2" style="cursor: pointer; display: none"><span class="glyphicon glyphicon-asterisk <?php echo $Text_Style; ?>"></span><span class=" label <?php echo $Text_Style; ?>" <?php echo 'onclick="Importer_Donnes(\'\')"'; ?>>Importer</span></div>

    <div class="col-lg-2" style="cursor: pointer; display: none"><span class="glyphicon glyphicon-asterisk <?php echo $Text_Style; ?>"></span><span class=" label <?php echo $Text_Style; ?>" <?php echo 'onclick="Telecharger_Fichier_Excel(\'\',\'\')"'; ?>>Exporter</span></div>
<script type="text/javascript">

</script>
    <div class="col-lg-2" style="cursor: pointer; display: none" <?php echo 'onclick="Afficher_Formulaire_Insertion(\'\')"'; ?>><span class="glyphicon glyphicon-asterisk <?php echo $Text_Style; ?>"></span><span class=" label <?php echo $Text_Style; ?>">Nouvelle donnée</span></div>
</div>

<?php
echo '<div class="row"><div class="col-lg-12"><section style="font-size:18px; text-decoration:underline">'.$row5['Nom_Rapport'].'</section></div></div>';
     echo '<div class="row">
    <div class="col-lg-6">
        <div class="hpanel">
            <div class="panel-heading">
               Tableau
               <div class="panel-tools" style="margin-top:-5px">
                
                    
                    <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                   <!-- <a class="closebox"><i class="fa fa-times"></i></a>-->
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                <table cellpadding="1" cellspacing="1" class="table contenu">
<tr style=" background-color:#F1F3F6; text-align: center">';

$COLONNE_X="";
$COLONNE_Y="";
$VALEUR="valeur";

$Exp_COLONNE_Y = explode(".", $row5["Colonne_Y"]);
$Exp_COLONNE_X = explode(".", $row5["Colonne_X"]);

foreach (FC_Rechercher_Code("SELECT `t_feuille_ligne`.`Nom_Ligne` FROM `t_feuille_ligne` INNER JOIN `t_feuille` ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE (t_feuille.Table_Feuille = '".str_replace("v", "t", $Exp_COLONNE_Y[0])."' AND t_feuille_ligne.Nom_Collone = '".$Exp_COLONNE_Y[1]."')") as $row6){echo "<th><sub>".$row6["Nom_Ligne"]."</sub> | ";}

 $COLONNE_Y=$row5["Colonne_Y"];
foreach (FC_Rechercher_Code("SELECT `t_feuille_ligne`.`Nom_Ligne` FROM `t_feuille_ligne` INNER JOIN `t_feuille` ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE (t_feuille.Table_Feuille = '".str_replace("v", "t", $Exp_COLONNE_X[0])."' AND t_feuille_ligne.Nom_Collone = '".$Exp_COLONNE_X[1]."')") as $row6){echo "<sup>".$row6["Nom_Ligne"]."</sup></th>";}

 $COLONNE_X=$row5["Colonne_X"];
/*foreach (FC_Rechercher_Code("SELECT * FROM t_feuille_ligne WHERE(Code_Feuille=".$row5["Code_Feuille"]." AND Nom_Ligne='".$row5["Valeur"]."')") as $row12) 
{$VALEUR=$row12["Nom_Collone"];}*/

try
{
$COLONNE_X_TAB=null;
$COLONNE_Y_TAB=null;
$indd=0; $indu=0; $i=0;
$compte=0;

$Col_Y ="";
$Col_X ="";

$Res1 = FC_Rechercher_Code("SELECT DISTINCT(`$COLONNE_X`) FROM ".$row5['Nom_View']);
if($Res1!=null)
  {foreach ($Res1 as $row10) 
{ echo "<th>".$row10[0]."</th>"; $COLONNE_X_TAB[]=$row10[0]; $Col_X.=" SUM(CASE WHEN `$COLONNE_X` LIKE '".addslashes($row10[0])."' THEN $VALEUR ELSE NULL END) AS ".str_replace(" ", "",addslashes($row10[0]))."_c,"; }}
$Col_X = substr($Col_X, 0, strlen($Col_X)-1);

$Res2 = FC_Rechercher_Code("SELECT DISTINCT(`$COLONNE_Y`) FROM ".$row5['Nom_View']);
if($Res2!=null)
  {foreach ($Res2 as $row11) 
{$COLONNE_Y_TAB[]=$row11[0];}}



echo '</tr>';

$SQL_Code="
SELECT `$COLONNE_Y`,
$Col_X
FROM ".$row5['Nom_View']." GROUP BY `$COLONNE_Y`";
//echo $SQL_Code;
$Res5 = FC_Rechercher_Code($SQL_Code);
if($Res5!= null){

foreach ($Res5 as $key5) {
echo "<tr>"; 
echo "<td>".$key5["$COLONNE_Y"]."</td>";    
for ($i=0; $i<count($COLONNE_X_TAB); $i++) 
{

if($key5[str_replace(" ", "", $COLONNE_X_TAB[$i])."_c"]=="")
         {echo "<td>-</td>";}
else {echo "<td>".number_format($key5[str_replace(" ", "", $COLONNE_X_TAB[$i])."_c"],0, '',' ')."</td>";}
    

}
echo "</tr>";}
}

}
catch(Exception $e){}
    echo '</table></div>

            </div>
            <div class="panel-footer">
              ';
              if($COLONNE_Y_TAB != null){echo count($COLONNE_Y_TAB);}
                else {echo "0";}
              

              echo ' ligne(s)  
            </div>
        </div>
    </div>';
?>


<?php
     echo '<div class="col-lg-6 graph_div">
        <div class="hpanel">
            <div class="panel-heading">
               Graphique
               <div class="panel-tools" style="margin-top:-5px">
                
                    
                    <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                   <!-- <a class="closebox"><i class="fa fa-times"></i></a>-->
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive"> 
<div id="container" style="height: 400px"></div>';



try
{/*$Script_Js='var data3 = [
            { label: "Bamako", data: 16, color: "rgb(25,50,75)", },
            { label: "Sikasso", data: 6, color: "lightblue", },
            { label: "Kayes", data: 22, color: "yellow", },
            { label: "Kidal", data: 32, color: "darkred", }
        ];';*/


$Script_Js="";
/*$C1=0;
$C2=50;
$C3=100;
$Res4=FC_Rechercher_Code("SELECT * FROM ".$row5['Nom_View']);
if($Res4 != null)
{  foreach($Res4 as $row9) 
{$C1=(($C1+75)%256);
 $C2=(($C2+29)%256);
 $C3=(($C3+59)%256);
  //$Script_Js.='{ label: "'.$row9[0].'", data: '.number_format($row9[1],0, '','').', color: "rgba('.$C1.','.$C2.','.$C3.')", },';
}}*/
substr($Script_Js, 0, strlen($Script_Js)-1);

echo '<script type="text/javascript"> var data3 = [ '.$Script_Js.']; </script>';
}
catch(Exception $e){}
    echo '</div>

            </div>
            <div class="panel-footer"> 
            </div>
        </div>
    </div></div>
  </div>';

?>


<?php
    }
    ?>
</div></div>
  </div>

        </div>
    </div>
    <?php //require_once "./theme_components/footer.php"; ?>
</div>

        <script type="text/javascript">

Highcharts.chart('container', {
    chart: {
        type: 'column',
        options3d: {
            enabled: true,
            alpha: 15,
            beta: 15,
            viewDistance: 25,
            depth: 40
        }
    },

    title: {
        text: ''
    },

    xAxis: {

        
        categories: [
        <?php for($i=0; $i<count($COLONNE_X_TAB); $i++){echo "'".$COLONNE_X_TAB[$i]."'";
              if($i==(count($COLONNE_X_TAB)-1)){} else {echo ",";}} 
        ?>
        ],
        labels: {
            skew3d: true,
            style: {
                fontSize: '16px'
            }
        }
    },

    yAxis: {
        allowDecimals: false,
        min: 0,
        title: {
            text: '',
            skew3d: true
        }
    },

    tooltip: {
        headerFormat: '<b>{point.key}</b><br>',
        pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: {point.y} / {point.stackTotal}'
    },

    plotOptions: {
        column: {
            stacking: 'normal',
            depth: 40
        }
    },

    series: [

<?php 

$Res5 = FC_Rechercher_Code($SQL_Code);
if($Res5!= null){

foreach ($Res5 as $key5) {
echo "{name:'".addslashes($key5["$COLONNE_Y"])."', data: [";    
for ($i=0; $i<count($COLONNE_X_TAB); $i++) 
{   
    

    if($key5[$COLONNE_X_TAB[$i]."_c"]=="")
         {echo "0,";}
    else {echo $key5[$COLONNE_X_TAB[$i]."_c"].",";}

}
echo "], stack: 'male'},";}
}
/*
for ($i=0; $i<count($COLONNE_Y_TAB); $i++) 
{echo "{"; 
echo "name:'".$COLONNE_Y_TAB[$i]."', data: [";
for ($j=0; $j<count($COLONNE_X_TAB); $j++) 
{
$Res = FC_Rechercher_Code("SELECT $VALEUR FROM ".$row5['Nom_View']." WHERE ($COLONNE_X='$COLONNE_X_TAB[$j]' AND $COLONNE_Y='$COLONNE_Y_TAB[$i]') GROUP BY $COLONNE_X");
if($Res!=null){
if($Res->rowCount()!=0){foreach ($Res as $row8) {echo $row8[0].", ";}}
else {echo "0, ";}
}
}
echo "], stack: 'male'}";
if($i==(count($COLONNE_Y_TAB)-1)){} else {echo ",";}
}*/

 ?>
 ]

});


        </script>

</body>
</html>