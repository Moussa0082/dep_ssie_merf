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



//header('Content-Type: text/html; charset=ISO-8859-15');


$query_liste_annee = "SELECT distinct annee FROM ".$database_connect_prefix."ptba WHERE projet='".$_SESSION["clp_projet"]."' order by annee asc";
  try{
    $liste_annee = $pdar_connexion->prepare($query_liste_annee);
    $liste_annee->execute();
    $row_liste_annee = $liste_annee ->fetchAll();
    $totalRows_liste_annee = $liste_annee->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableauAnnee=array();
if($totalRows_liste_annee>0){
foreach($row_liste_annee as $row_liste_annee){
$tableauAnnee[]=$row_liste_annee['annee']; $annee_c=$row_liste_annee['annee'];
  }}

if(isset($annee_c)) $annee_c=$annee_c; else $annee_c=date("Y");

$query_liste_cout = "SELECT code_activite_ptba, ".$database_connect_prefix."ptba.annee, SUM( if(montant>0, montant,0) ) AS montant  FROM ".$database_connect_prefix."part_bailleur, ".$database_connect_prefix."ptba  where id_ptba=activite and  ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' group by annee, code_activite_ptba";
  try{
    $liste_cout = $pdar_connexion->prepare($query_liste_cout);
    $liste_cout->execute();
    $row_liste_cout = $liste_cout ->fetchAll();
    $totalRows_liste_cout = $liste_cout->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$prevu_array = array();
if($totalRows_liste_cout>0){
foreach($row_liste_cout as $row_liste_cout){ $prevu_array[$row_liste_cout["annee"]][$row_liste_cout["code_activite_ptba"]]=$row_liste_cout["montant"];  }}


$query_liste_cout2 = "SELECT id_ptba, ".$database_connect_prefix."ptba.annee, SUM( if(montant>0, montant,0) ) AS montant  FROM ".$database_connect_prefix."part_bailleur, ".$database_connect_prefix."ptba  where id_ptba=activite and  ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' group by annee, id_ptba";
  try{
    $liste_cout2 = $pdar_connexion->prepare($query_liste_cout2);
    $liste_cout2->execute();
    $row_liste_cout2 = $liste_cout2 ->fetchAll();
    $totalRows_liste_cout2 = $liste_cout2->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$prevu_array2 = array();
if($totalRows_liste_cout2>0){
foreach($row_liste_cout2 as $row_liste_cout2){ $prevu_array2[$row_liste_cout2["annee"]][$row_liste_cout2["id_ptba"]]=$row_liste_cout2["montant"]; }}

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT * FROM ".$database_connect_prefix."niveau_budget_config WHERE ".$_SESSION["clp_where"]." LIMIT 1";
$entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);
$libelle = array();
$niveau = 1;
if($totalRows_entete>0){ $libelle=explode(",",$row_entete["libelle"]);}*/

$query_liste_version = "SELECT * FROM ".$database_connect_prefix."version_ptba  ORDER BY date_validation asc";
						try{
    $liste_version = $pdar_connexion->prepare($query_liste_version);
    $liste_version->execute();
    $row_liste_version = $liste_version ->fetchAll();
    $totalRows_liste_version = $liste_version->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$TableauVersionP = array(); $version_array = array();
 if($totalRows_liste_version>0) { foreach($row_liste_version as $row_liste_version){  
$max_version=$row_liste_version["id_version_ptba"];
$version_array[$row_liste_version["id_version_ptba"]] = $row_liste_version["annee_ptba"]." ".$row_liste_version["version_ptba"];
 } }
 if(isset($_GET['version'])) {$versiona=$_GET['version'];} elseif($totalRows_liste_version>0) $versiona=$max_version; else  $versiona=1;

?>



    <div class="widget-content">



      <div class="tabbable tabbable-custom" >



        <ul class="nav nav-tabs" >



          <?php //for($j=1;$j<=4;$j++){ ?>



          <?php //$j=0; foreach($tableauAnnee as $anpta){ ?>
<?php //for($j=$_SESSION["annee_debut_projet"];$j<=date("Y");$j++){ if($j<=$_SESSION["annee_fin_projet"]) { $anpta=$j; ?>
 <?php $j=0; foreach($tableauAnnee as $anpta){ ?>

          <li title="Ann&eacute;e <?php echo $anpta; ?>" class="<?php echo ($anpta==$annee_c || (!in_array($anpta,$tableauAnnee) && $j==0))?"active":""; ?>"><a href="#tabtan_feed_<?php echo $j; ?>" data-toggle="tab"> <?php if(isset($version_array[$anpta])) echo $version_array[$anpta]; else echo $anpta; ?></a></li>



         <?php $j++; } ?>


        </ul>



        <div class="tab-content">



          <?php //for($j=$_SESSION["annee_debut_projet"];$j<=date("Y");$j++){ if($j<=$_SESSION["annee_fin_projet"]) { $anpta=$j; ?>

 <?php $j=0; foreach($tableauAnnee as $anpta){ ?>

          <?php //for($j=1;$j<=4;$j++){ ?>



          <div class="tab-pane <?php echo ($anpta==$annee_c || (!in_array($anpta,$tableauAnnee) && $j==0))?"active":""; ?>" id="tabtan_feed_<?php echo $j; ?>">



            <div class="scroller">







<?php


$query_liste_activite_1 = "SELECT id_ptba as type, code_activite_ptba as code, intitule_activite_ptba as libelle, 0 AS prevu, 0 AS realise, 0 AS engage FROM ".$database_connect_prefix."ptba WHERE ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."'  and annee=$anpta and code_activite_ptba not in (SELECT code FROM ".$database_connect_prefix."code_activite WHERE ".$database_connect_prefix."code_activite.projet='".$_SESSION["clp_projet"]."'   and annee=$anpta)
union
SELECT '0' as type, code, libelle, SUM( if(cout_prevu>0, cout_prevu,0) ) AS prevu, SUM( if(cout_realise>0, cout_realise,0) ) AS realise, SUM( if(cout_engage>0, cout_engage,0) ) AS engage FROM ".$database_connect_prefix."code_activite WHERE ".$database_connect_prefix."code_activite.projet='".$_SESSION["clp_projet"]."' and annee=$anpta and code!='Code' and code!='fichiers'
 group by code, libelle ORDER BY code ASC";
  try{
    $liste_activite_1 = $pdar_connexion->prepare($query_liste_activite_1);
    $liste_activite_1->execute();
    $row_liste_activite_1 = $liste_activite_1 ->fetchAll();
    $totalRows_liste_activite_1 = $liste_activite_1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


?>







<table id="example" border="0" align="center" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive dataTable" >



<?php //if(count($libelle)>0 && $niveau<count($libelle)){ ?>



                <thead>



                  <tr>
                    <td >&nbsp;</td>
                    <td >&nbsp;</td>
                    <td colspan="2"><div align="center"><strong>Import&eacute;</strong></div></td>
                    <td><div align="center"></div></td>
                    <td rowspan="2"><div align="center"><strong>Pr&eacute;vision Saisie</strong></div>                      <div align="left"></div></td>
                  </tr>
                  <tr>
                    <td ><strong>Code</strong></td>



                    <!--<td width="120"><strong>Code <?php //echo $libelle[$niveau]; ?></strong></td>-->



                    <td ><strong>Libell&eacute;</strong></td>



                    <td><div align="left"><strong>Pr&eacute;vu</strong></div></td>



                    <td><div align="left"><strong>D&eacute;caiss&eacute;</strong></div></td>



                    <td>&nbsp;</td>
                  </tr>
                </thead>



                <tbody>



<?php if($totalRows_liste_activite_1>0){ $T0=$T1=$T2=0; foreach($row_liste_activite_1 as $row_liste_activite_11){ //$id = $row_liste_activite_1["id"]; $code = $row_liste_activite_1["code"]; $parent = $row_liste_activite_1["parent"]; ?>



                <tr>
                  <td><div align="right" <?php if($row_liste_activite_11["type"]!=0) {?>style="background-color:#FFFF33"<?php } ?>><strong><?php echo $row_liste_activite_11["code"]; ?></strong></div></td>



                    <!--<td><strong><?php echo $row_liste_activite_11["code"]; ?></strong></td>-->



                    <td><strong><?php echo $row_liste_activite_11["libelle"]; ?></strong></td>



                    <td nowrap="nowrap"><div align="right">
                      <?php  echo number_format($row_liste_activite_11["prevu"], 0, ',', ' '); $T0+=$row_liste_activite_11["prevu"]; ?>
                    </div></td>



        <td nowrap="nowrap"><div align="right">
          <?php  echo number_format($row_liste_activite_11["realise"]+$row_liste_activite_11["engage"], 0, ',', ' '); $T1+=$row_liste_activite_11["realise"]+$row_liste_activite_11["engage"]; ?>
        </div></td>



        <td></td>
        <td nowrap="nowrap"> <div align="right" <?php if($row_liste_activite_11["type"]!=0) {?>style="background-color:#FFFF33"<?php } ?>>
		
		<?php if(isset($prevu_array[$anpta][$row_liste_activite_11["code"]]) && $prevu_array[$anpta][$row_liste_activite_11["code"]]>0 && $row_liste_activite_11["type"]=="0"){ $T2+=$prevu_array[$anpta][$row_liste_activite_11["code"]]; echo number_format($prevu_array[$anpta][$row_liste_activite_11["code"]], 0, ',', ' '); }elseif(isset($prevu_array2[$anpta][$row_liste_activite_11["type"]]) && $prevu_array2[$anpta][$row_liste_activite_11["type"]]>0 && $row_liste_activite_11["type"]!=0){ $T2+=$prevu_array2[$anpta][$row_liste_activite_11["type"]]; echo number_format($prevu_array2[$anpta][$row_liste_activite_11["type"]], 0, ',', ' '); } ?>
		
		
         </div></td>
		</tr>



<?php } ?>

<tr>

                    <td colspan="2"><strong>Total</strong></td>



                    <td nowrap="nowrap"><div align="right">
                    <b><?php echo number_format($T0, 0, ',', ' ');  ?>  </b>
                    </div></td>



        <td nowrap="nowrap"><div align="right">
        <b><?php echo number_format($T1, 0, ',', ' ');  ?></b>
        </div></td>


        <td></td>
        <td nowrap="nowrap" align="right"><b><?php echo number_format($T2, 0, ',', ' ');  ?></b></td>
		</tr>

<?php  } ?>
                </tbody>
            </table>



            </div>



          </div>







         <?php $j++; } ?>



        </div></div>



</div>