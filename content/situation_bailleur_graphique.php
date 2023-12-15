<?php



///////////////////////////////////////////////



/*                 SSE                       */



/*	Conception & Développement: SEYA SERVICES */



///////////////////////////////////////////////



session_start();



$path = '../';



include_once $path.'system/configuration.php';



$config = new Config;



       /*



if (!isset ($_SESSION["clp_id"])) {



  header(sprintf("Location: %s", "./"));



  exit;



} */



header('Content-Type: text/html; charset=ISO-8859-15');

//liste methode

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_convention = "SELECT * FROM ".$database_connect_prefix."type_part WHERE  projet='".$_SESSION["clp_projet"]."' ";
$liste_convention  = mysql_query_ruche($query_liste_convention , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_convention  = mysql_fetch_assoc($liste_convention);
$totalRows_liste_convention  = mysql_num_rows($liste_convention);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_annee = "SELECT distinct annee FROM ".$database_connect_prefix."ptba order by annee asc";
$liste_annee = mysql_query_ruche($query_liste_annee, $pdar_connexion) or die(mysql_error());
$tableauAnnee=array();
while($ligne=mysql_fetch_assoc($liste_annee)){$tableauAnnee[]=$ligne['annee']; $annee_c=$ligne['annee'];}

mysql_free_result($liste_annee);
if(isset($annee_c)) $annee_c=$annee_c; else $annee_c=date("Y");

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cout = "SELECT SUM( if(cout_prevu>0, cout_prevu,0) ) as prevu, SUM( if(cout_realise>0, cout_realise,0) ) as realise, SUM( if(cout_engage>0, cout_engage,0)) AS engage, annee, code  FROM ".$database_connect_prefix."code_convention where ".$database_connect_prefix."code_convention.projet='".$_SESSION["clp_projet"]."' and structure='".$_SESSION["clp_structure"]."' group by annee, code";
$liste_cout = mysql_query_ruche($query_liste_cout, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_cout = mysql_fetch_assoc($liste_cout);
$totalRows_liste_cout = mysql_num_rows($liste_cout);

$prevu_array = array();
$realise_array = array();
$engage_array = array();

if($totalRows_liste_cout>0){
do{
 $prevu_array[$row_liste_cout["annee"]][$row_liste_cout["code"]]=$row_liste_cout["prevu"];
 $realise_array[$row_liste_cout["annee"]][$row_liste_cout["code"]]=$row_liste_cout["realise"];
 $engage_array[$row_liste_cout["annee"]][$row_liste_cout["code"]]=$row_liste_cout["engage"];
  }
while($row_liste_cout  = mysql_fetch_assoc($liste_cout));}



 mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$query_liste_bailleur = "SELECT intitule, code_type from ".$database_connect_prefix."type_part ORDER BY code_type asc";
	$liste_bailleur = mysql_query_ruche($query_liste_bailleur, $pdar_connexion) or die(mysql_error());
	$row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
	$totalRows_liste_bailleur = mysql_num_rows($liste_bailleur);
	$bailleur_array = array();
    if($totalRows_liste_bailleur>0){
	 do{ $bailleur_array[$row_liste_bailleur["code_type"]]=$row_liste_bailleur["code_type"].": ".$row_liste_bailleur["intitule"];  }
	while($row_liste_bailleur  = mysql_fetch_assoc($liste_bailleur));}


?>







      <div class="tabbable tabbable-custom" >



        <ul class="nav nav-tabs" >

		   <?php $j = 0; foreach($tableauAnnee as $anp){ ?>



          <li title="Ann&eacute;e <?php echo $anp; ?>" class="<?php echo ($anp==$annee_c || (!in_array($anp,$tableauAnnee) && $j==0))?"active":""; ?>"><a href="#tab_stb_<?php echo $j; ?>" data-toggle="tab"> <?php echo $anp; ?></a></li>



          <?php $j++; } ?>



        </ul>



        <div class="tab-content">



		 <?php $j = 0;foreach($tableauAnnee as $anp){ ?>

          <div class="tab-pane <?php echo ($anp==$annee_c || (!in_array($anp,$tableauAnnee) && $j==0))?"active":""; ?>" id="tab_stb_<?php echo $j; ?>">



<div class="col-md-6">



            <div class="scroller">



          <table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable" align="center" >







      <thead>



        <tr>



          <td><div align="left"><strong>Convention</strong></div></td>



          <td><div align="left"><strong>Pr&eacute;vu</strong></div></td>



          <td><div align="left"><strong>D&eacute;caiss&eacute;</strong></div></td>



		  <td><div align="left"><strong>Engag&eacute;</strong></div></td>



		   <td>%Exe</td>







        </tr>



      </thead>



      <?php if($totalRows_liste_convention>0) {$i=0;  do { $totreal=0; //$id = $row_liste_convention['sigle']; ?>



      <tr>



        <td><div align="left">  <?php echo $row_liste_convention['code_type'].": ".$row_liste_convention['intitule']; ?></div></td>



        <td nowrap="nowrap"><div align="left"><?php if(isset($prevu_array[$anp][$row_liste_convention["code_type"]]) && $prevu_array[$anp][$row_liste_convention["code_type"]]>0) echo number_format($prevu_array[$anp][$row_liste_convention["code_type"]], 0, ',', ' '); ?></div></td>



        <td nowrap="nowrap"><?php if(isset($realise_array[$anp][$row_liste_convention["code_type"]]) && $realise_array[$anp][$row_liste_convention["code_type"]]>0) {echo number_format($realise_array[$anp][$row_liste_convention["code_type"]], 0, ',', ' '); $totreal=$realise_array[$anp][$row_liste_convention["code_type"]];} ?></td>



        <td nowrap="nowrap"><?php if(isset($engage_array[$anp][$row_liste_convention["code_type"]]) && $engage_array[$anp][$row_liste_convention["code_type"]]>0) {echo number_format($engage_array[$anp][$row_liste_convention["code_type"]], 0, ',', ' '); $totreal=$totreal+$engage_array[$anp][$row_liste_convention["code_type"]];} ?></td>



		<td nowrap="nowrap"><?php if(isset($prevu_array[$anp][$row_liste_convention["code_type"]]) && $prevu_array[$anp][$row_liste_convention["code_type"]]>0 && isset($realise_array[$anp][$row_liste_convention["code_type"]])) echo number_format(100*$realise_array[$anp][$row_liste_convention["code_type"]]/$prevu_array[$anp][$row_liste_convention["code_type"]], 2, ',', ' '); ?></td>

      </tr>



      <?php }



	  while ($row_liste_convention = mysql_fetch_assoc($liste_convention));



	  $rows = mysql_num_rows($liste_convention);



if($rows > 0) {



mysql_data_seek($liste_convention, 0);



$row_liste_convention = mysql_fetch_assoc($liste_convention);



} ?>







      <?php } ?>



    </table>  </div>



</div>



<div class="col-md-6">



<?php



$tableauCp = array();



$tableauCoutCp = array();



$annee=$anp;







mysql_select_db($database_pdar_connexion, $pdar_connexion);



$query_taux_annee = "SELECT SUM( if(cout_prevu>0, cout_prevu,0) ) AS taux FROM ".$database_connect_prefix."code_analytique where  annee=$annee";



$taux_annee  = mysql_query_ruche($query_taux_annee , $pdar_connexion) or die(mysql_error());



$row_taux_annee  = mysql_fetch_assoc($taux_annee);



$totalRows_taux_annee  = mysql_num_rows($taux_annee);







mysql_select_db($database_pdar_connexion, $pdar_connexion);



$query_liste_cp = "SELECT SUM( if(cout_prevu>0, cout_prevu,0) ) as ct, right(code_categorie,2) as cp  FROM ".$database_connect_prefix."code_analytique where ".$database_connect_prefix."code_analytique.projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."code_analytique.code_activite_ptba in(select code_activite_ptba from ".$database_connect_prefix."ptba where ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' and annee=$annee) and annee=$annee group by cp";



$liste_cp  = mysql_query_ruche($query_liste_cp , $pdar_connexion) or die(mysql_error());



$row_liste_cp  = mysql_fetch_assoc($liste_cp);



$totalRows_liste_cp  = mysql_num_rows($liste_cp);







$taux_annuel=$row_taux_annee['taux'];



$cout=0;



//if($totalRows_liste_cp>0 && $taux_annuel>0){



$cout=$row_liste_cp['ct'];



?>



		<script type="text/javascript">



$(function () {



    var chart;







    $(document).ready(function () {



        //if($('#container<?php echo $j; ?>').html()==""){



        $('#container<?php echo $j; ?>').html('');



    	// Build the chart



        $('#container<?php echo $j; ?>').highcharts({



            chart: {



                plotBackgroundColor: null,



                plotBorderWidth: null,



                plotShadow: false



            },



            title: {



                text: 'Part de financement des activités  en <?php echo "$annee";  ?>'



            },



            tooltip: {



        	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br /><u>Montant total</u>: <b><?php echo number_format($taux_annuel, 0, ',', ' ');  ?> Fcfa</b>'



            },



            plotOptions: {



                pie: {



                    allowPointSelect: true,



                    cursor: 'pointer',



                    dataLabels: {



                        enabled: true,



                        format: '<b>{point.name}</b>: {point.percentage:.0f} %'



                    },



                    showInLegend: true



                }



            },



            series: [{



                type: 'pie',



                name: 'Part',



                data: [



<?php



$data = "";



if($totalRows_liste_cp>0) {do {



	$const=$taux_annuel;







    $nombre = ($const>0)?$row_liste_cp['ct']:0;



	//if(isset($row_liste_cp['ct'])) $cout=



	$data .= "['".$row_liste_cp['cp']."',  ".$nombre."],";



} while ($row_liste_cp = mysql_fetch_assoc($liste_cp)); }



echo substr($data,0,strlen($data)-1);



  ?>



                ]



            }]



        });  //}



    });







});



		</script>



<div id="container<?php echo $j; ?>" style="min-width: 450px; height: 400px; margin: 0 auto"></div>



G&eacute;n&eacute;r&eacute; le <?php echo date("d/m/Y")." &agrave; ".date("H:i:s");  ?>



<?php //} ?>



</div>



          </div>



          <?php $j++; } ?>



        </div>



      </div>