<?php 
if(isset($_GET['Code_Rapport']) AND !empty($_GET['Code_Rapport']))	
{extract($_GET);
	require_once '../api/Fonctions.php';
	?>


 <div class="content">
<div  style="background-color: white">

<?php require_once '../requires/formulaire_insertion_123.php';  ?>
<div class="tab-content">
    <?php
$ind=0;
    foreach (FC_Rechercher_Code('SELECT * FROM t_rapport_indicateur INNER JOIN t_rapport_article ON (t_rapport_indicateur.Code_Rapport=t_rapport_article.Code_Rapport) WHERE (t_rapport_article.Code_Article='.$Code_Rapport.')') as $row5)
    {$ind++;
    	PC_Enregistrer_Code("UPDATE t_rapport_article SET Vue = (Vue+1) WHERE Code_Article = ".$Code_Rapport);
        foreach (FC_Rechercher_Code('SELECT * FROM t_indicateur_cadre_resultat WHERE (id_indicateur_cr='.$row5["Indicateur"].')') as $row45)
{echo '<span class="text-primary">'.$row45['intitule_indicateur_cr'].'</span>';} 
    if(empty($row5["Group_By"]))
 { $Res3=FC_Rechercher_Code('SELECT * FROM '.$row5["Nom_View"]); 
    if($Res3!=null)
    	{foreach ($Res3 as $row46)
    {echo '<strong>'.number_format($row46[0],0, '',' ').'</strong>';}
    }
}
else{

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


foreach (FC_Rechercher_Code("SELECT * FROM t_feuille_ligne WHERE(Code_Feuille=".$row5["Code_Feuille"]." AND Nom_Ligne='".$row5["Group_By"]."')") as $row6) 
{echo "<th>".$row6["Nom_Ligne"]."</th> <script> var Nom_Ligne='".$row6["Nom_Ligne"]."'; </script>";}

foreach (FC_Rechercher_Code("SELECT * FROM t_feuille_ligne WHERE(Code_Feuille=".$row5["Code_Feuille"]." AND Nom_Ligne='".$row5["Valeur"]."')") as $row7) 
{echo "<th>".$row7["Nom_Ligne"]."</th>";}

echo '</tr>'; 
$indd=0; $indu=0; $i=0;
$compte=0;
$TOTAL=0;
try
{$Res = FC_Rechercher_Code("SELECT * FROM ".$row5['Nom_View']);

if($Res!=null){
  foreach ($Res as $row8) 
{echo "<tr>"; echo "<td>".$row8[0]."</td>"; echo "<td>".number_format($row8[1],0, '',' ')."</td>"; echo "</tr>"; $compte++; $TOTAL+=$row8[1];}
}
echo '<tr style="font-size:18px; font-weight:bold">'; echo "<td>TOTAL</td>"; echo "<td>".number_format($TOTAL,0, '',' ')."</td>"; echo "</tr>";

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
$Res2=FC_Rechercher_Code("SELECT * FROM ".$row5['Nom_View']);
if($Res2!=null){
  foreach($Res2 as $row9) 
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
    }}
    ?>
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
<?php } ?>
 
