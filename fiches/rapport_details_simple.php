<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
if (!isset ($_SESSION["clp_id"])) {
    header(sprintf("Location: %s", "./login.php"));  exit();
}
include_once 'api/configuration.php';
$config = new Config;

require_once 'api/Fonctions.php';
require_once 'theme_components/theme_style.php';

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
    #header, #menu, .bar_header, .showhide, .small-header, .footer, #Type_Graphique {display: none}
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
<script type="text/javascript">
    var feuille_active=1;
</script>
 <div class="content">
<div  style="background-color: white">

<?php require_once 'requires/formulaire_insertion_123.php';  ?>
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

$Exp_GROUB_BY = explode(".", $row5["Group_By"]);
$Exp_VALEUR = explode(".", $row5["Valeur"]);

/*foreach (FC_Rechercher_Code("SELECT * FROM t_feuille_ligne WHERE(Code_Feuille=".$row5["Code_Feuille"]." AND Nom_Ligne='".$row5["Group_By"]."')") as $row6) 
{echo "<th>".$row6["Nom_Ligne"]."</th> <script> var Nom_Ligne='".$row6["Nom_Ligne"]."'; </script>";}

foreach (FC_Rechercher_Code("SELECT * FROM t_feuille_ligne WHERE(Code_Feuille=".$row5["Code_Feuille"]." AND Nom_Ligne='".$row5["Valeur"]."')") as $row7) 
{echo "<th>".$row7["Nom_Ligne"]."</th>";}*/

foreach (FC_Rechercher_Code("SELECT `t_feuille_ligne`.`Nom_Ligne` FROM `t_feuille_ligne` INNER JOIN `t_feuille` ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE (t_feuille.Table_Feuille = '".str_replace("v", "t", $Exp_GROUB_BY[0])."' AND t_feuille_ligne.Nom_Collone = '".$Exp_GROUB_BY[1]."')") as $row6){echo "<th>".$row6["Nom_Ligne"]."</th> <script> var Nom_Ligne='".$row6["Nom_Ligne"]."'; </script>";}

foreach (FC_Rechercher_Code("SELECT `t_feuille_ligne`.`Nom_Ligne` FROM `t_feuille_ligne` INNER JOIN `t_feuille` ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE (t_feuille.Table_Feuille = '".str_replace("v", "t", $Exp_VALEUR[0])."' AND t_feuille_ligne.Nom_Collone = '".$Exp_VALEUR[1]."')") as $row6){echo "<th>".$row6["Nom_Ligne"]."</th>";}

echo '</tr>'; 
$indd=0; $indu=0; $i=0;
$compte=0;

try
{$Res = FC_Rechercher_Code("SELECT * FROM ".$row5['Nom_View']);

if($Res!=null){
  foreach ($Res as $row8) 
{echo "<tr>"; echo "<td>".$row8[0]."</td>"; echo "<td>".number_format($row8[1],0, '',' ')."</td>"; echo "</tr>"; $compte++;}
}

}
catch(Exception $e){}
    echo '</table></div>

            </div>
            <div class="panel-footer">
              '.$compte.' ligne(s)  
            </div>
        </div>
    </div>';
?>

<?php
     echo '<div class="col-lg-6 graph_div">
        <div class="hpanel">
            <div class="panel-heading">
               Graphique 
               <select class="form-control" id="Type_Graphique" style="width:200px"><option value="1">Camembert</option><option value="2">Courbe</option><option value="3">Histogramme</option></select>
               <div class="panel-tools" style="margin-top:-5px; ">
                
                    
                    <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                   <!-- <a class="closebox"><i class="fa fa-times"></i></a>-->
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive"> 
                <div id="container_graph" style="height: 400px"></div>';



try
{/*$Script_Js='var data3 = [
            { label: "Bamako", data: 16, color: "rgb(25,50,75)", },
            { label: "Sikasso", data: 6, color: "lightblue", },
            { label: "Kayes", data: 22, color: "yellow", },
            { label: "Kidal", data: 32, color: "darkred", }
        ];';*/
$Script_Js="";
$Script_Js2="";
$C1=0;
$C2=50;
$C3=100;
$Res4=FC_Rechercher_Code("SELECT * FROM ".$row5['Nom_View']);
if($Res4 != null){
  foreach($Res4 as $row9) 

{$C1=(($C1+75)%256);
 $C2=(($C2+29)%256);
 $C3=(($C3+59)%256);
  $Script_Js.='["'.$row9[0].'", '.number_format($row9[1],0, '','').' ],';
  $Script_Js2.=number_format($row9[1],0, '','').' ,';
}}
substr($Script_Js, 0, strlen($Script_Js)-1);

substr($Script_Js2, 0, strlen($Script_Js2)-1);

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

Camembert();

document.getElementById("Type_Graphique").addEventListener("change", Changer_Graph);
function Changer_Graph()
{
    switch(document.getElementById('Type_Graphique').value)
    {
        case '1' : Camembert() ; break;
        case '2' : Courbe(); break;
        case '3' : Histogramme(); break;
    }

}

function Camembert()
{Highcharts.chart('container_graph', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45,
            beta: 0
        }
    },
    title: {
        text: ''
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            depth: 35,
            dataLabels: {
                enabled: true,
                format: '{point.name}'
            }
        }
    },
    series: [{
        type: 'pie',
        name: '',
        data: [
        <?php  echo $Script_Js;  ?>
        ]
    }]
});}

function Courbe()
{
Highcharts.chart('container_graph', {
    chart: {
        type: 'area'
    },
    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        allowDecimals: false,
        labels: {
            formatter: function () {
                return this.value; // clean, unformatted number for year
            }
        }
    },
    yAxis: {
        title: {
            text: ''
        },
        labels: {
            formatter: function () {
                return this.value;
            }
        }
    },
    tooltip: {
        pointFormat: '{series.name} had stockpiled <b>{point.y:,.0f}</b><br/>warheads in {point.x}'
    },
    plotOptions: {
        area: {
            pointStart: 0,
            marker: {
                enabled: false,
                symbol: 'circle',
                radius: 2,
                states: {
                    hover: {
                        enabled: true
                    }
                }
            }
        }
    },
    series: [{
        name: Nom_Ligne,
        data: [
            <?php  echo $Script_Js2;  ?>
        ]
    }]
});
}

function Histogramme()
{
    Highcharts.chart('container_graph', {
    chart: {
        type: 'column'
    },
    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        type: 'category',
        labels: {
            rotation: -45,
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: ''
        }
    },
    legend: {
        enabled: false
    },
    tooltip: {
        pointFormat: '{point.y:.1f}'
    },
    series: [{
        name: '',
        data: [
           <?php  echo $Script_Js;  ?>
        ],
        dataLabels: {
            enabled: true,
            rotation: 0,
            color: '#FFFFFF',
            align: 'right',
            format: '{point.y:.1f}', // one decimal
            y: 10, // 10 pixels down from the top
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    }]
});
}


        </script>

</body>
</html>