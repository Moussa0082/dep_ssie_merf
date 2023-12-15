<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
//session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  echo "<h1>Une erreur s'est produite !</h1>";
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
header('Content-Type: text/html; charset=UTF-8');

$annee=(isset($_GET['annee']))?$_GET['annee']:date("Y");
$array_indic = array("OUI/NON","texte");
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_ms = "SELECT code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=1 and ".$_SESSION["clp_where"]." ORDER BY code asc";
$edit_ms = mysql_query_ruche($query_edit_ms, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_ms = mysql_fetch_assoc($edit_ms);
$totalRows_edit_ms = mysql_num_rows($edit_ms);

mysql_select_db($database_pdar_connexion, $pdar_connexion); //code_activite='$code_act' and annee=$annee and
$query_indicateur = "SELECT * FROM ".$database_connect_prefix."indicateur_tache where annee=$annee and projet='".$_SESSION["clp_projet"]."' ORDER BY code_indicateur_ptba asc";
$indicateur  = mysql_query_ruche($query_indicateur , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_indicateur = mysql_fetch_assoc($indicateur);
$totalRows_indicateur  = mysql_num_rows($indicateur);
$indicateur_array = $unite_array = array();
if($totalRows_indicateur>0) { do {
$indicateur_array[$row_indicateur["id_activite"]][$row_indicateur["id_indicateur_tache"]] = $row_indicateur;
$unite_array[$row_indicateur["id_indicateur_tache"]] = $row_indicateur["unite"];
} while ($row_indicateur = mysql_fetch_assoc($indicateur));  }

//semestre precedent
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cible_indicateur = "SELECT * FROM ".$database_connect_prefix."cible_indicateur_trimestre where annee=$annee and projet='".$_SESSION["clp_projet"]."' ";
$cible_indicateur  = mysql_query_ruche($query_cible_indicateur , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_cible_indicateur = mysql_fetch_assoc($cible_indicateur );
$totalRows_cible_indicateur = mysql_num_rows($cible_indicateur );
$tableau_cible_indicateur_array = array();
  if($totalRows_cible_indicateur>0){  do{
    if(!isset($tableau_cible_indicateur_array[$row_cible_indicateur["indicateur"]][$row_cible_indicateur["trimestre"]]))
    $tableau_cible_indicateur_array[$row_cible_indicateur["indicateur"]][$row_cible_indicateur["trimestre"]] = $row_cible_indicateur[(!in_array($unite_array[$row_cible_indicateur["indicateur"]],$array_indic))?"cible":"cible_txt"];
    else
    $tableau_cible_indicateur_array[$row_cible_indicateur["indicateur"]][$row_cible_indicateur["trimestre"]] += $row_cible_indicateur[(!in_array($unite_array[$row_cible_indicateur["indicateur"]],$array_indic))?"cible":"cible_txt"];
  }while($row_cible_indicateur = mysql_fetch_assoc($cible_indicateur));  }


  //PTBA
/*  $appendice_array =array();
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_appendice4 = "SELECT intitule_indicateur_tache,code_activite_ptba,intitule_activite_ptba,id_indicateur_tache, unite FROM ".$database_connect_prefix."ptba, ".$database_connect_prefix."indicateur_tache where code_activite_ptba=code_activite and code_activite_ptba='$code_act' and ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' ORDER BY code_activite_ptba,intitule_indicateur_tache";
  $appendice4  = mysql_query_ruche($query_appendice4 , $pdar_connexion) or die(mysql_error());
  $row_appendice4  = mysql_fetch_assoc($appendice4);
  $totalRows_appendice4  = mysql_num_rows($appendice4);
  if($totalRows_appendice4>0){ do{
  $appendice_array[$row_appendice4["id_indicateur_tache"]]=$row_appendice4["intitule_indicateur_tache"]."|".$row_appendice4["unite"];  }while($row_appendice4  = mysql_fetch_assoc($appendice4)); }  */

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_classeur = "SELECT * FROM ".$database_connect_prefix."classeur ";
$liste_classeur = mysql_query_ruche($query_liste_classeur, $pdar_connexion) or die(mysql_error());
$row_liste_classeur = mysql_fetch_assoc($liste_classeur);
$totalRows_liste_classeur = mysql_num_rows($liste_classeur);
$liste_classeur_array = $classeur_color_array = array();
if($totalRows_liste_classeur>0){  do{
//$liste_classeur_array[$row_liste_classeur["id_classeur"]]=$row_liste_classeur["libelle"];
$classeur_color_array[$row_liste_classeur["id_classeur"]]=$row_liste_classeur["couleur"];
}while($row_liste_classeur  = mysql_fetch_assoc($liste_classeur));  }

//dynamique
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT * FROM ".$database_connect_prefix."indicateur_config WHERE projet='".$_SESSION["clp_projet"]."' and type='PTBA' and ind in (SELECT id_indicateur_tache FROM ".$database_connect_prefix."ptba, ".$database_connect_prefix."indicateur_tache where id_ptba=id_activite and ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."')";
$entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);

$cmr_realise = $indicateur_dynamique = array();
$mode_calcul = array("SOMME"=>"SUM","MOYENNE"=>"AVG","COMPTER"=>"COUNT");
if($totalRows_entete>0){ do{ $id=$row_entete["ind"]; $col =trim($row_entete["col"]); $table = $row_entete["id_fiche"]; $table1 = substr($table,0,strlen($table)-8); $tmp = explode('_',$table); $classeur = intval($tmp[1]); $feuille = $table;
$indicateur_dynamique[$id]["feuille"] = $database_connect_prefix.$feuille;
$indicateur_dynamique[$id]["classeur"] = $classeur;
if(isset($indicateur_dynamique[$id]["lib"])) $indicateur_dynamique[$id]["lib"] = "";
list($indicateur_dynamique[$id]["lib"],$indicateur_dynamique[$id]["unite"]) = ($row_entete['type']=="CMR" && isset($cmr_array[$row_entete['ind']]))?explode('|',$cmr_array[$row_entete['ind']]):(($row_entete['type']=="PTBA" && isset($appendice_array[$row_entete['ind']]))?explode('|',$appendice_array[$row_entete['ind']]):'NaN');
if(isset($indicateur_dynamique[$id]["color"])) $indicateur_dynamique[$id]["color"] = "";
$indicateur_dynamique[$id]["color"] = !empty($row_entete['couleur'])?$row_entete['couleur']:(isset($classeur_color_array[$classeur])?$classeur_color_array[$classeur]:'');
$type=""; $formule = (!empty($row_entete['mode_calcul']) && isset($mode_calcul[$row_entete['mode_calcul']]))?$mode_calcul[$row_entete['mode_calcul']]:$mode_calcul["SOMME"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "DESCRIBE `".$database_connect_prefix."$table`";
$liste_cp = mysql_query_ruche($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);
if($totalRows_liste_cp>0){ do{ if($row_liste_cp["Field"]==$col) $type=$row_liste_cp["Type"]; }while($row_liste_cp = mysql_fetch_assoc($liste_cp)); }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
if(strchr($table,"_details")!="")
{
  //`$table`.annee=$annee and  group by departement.region   ".$database_connect_prefix."validation_fiche.niveau1=1 and ".$database_connect_prefix."validation_fiche.niveau2=1 and
  $query_data = "SELECT $formule(`".$database_connect_prefix."$table`.$col) as nb FROM `".$database_connect_prefix."$table`,".$database_connect_prefix."validation_fiche WHERE ".$database_connect_prefix."validation_fiche.id_lkey=`".$database_connect_prefix."$table`.LKEY and ".$database_connect_prefix."validation_fiche.nom_fiche='".$database_connect_prefix."$table'";
  $data  = mysql_query_ruche($query_data , $pdar_connexion);
  if($data){
  $row_data  = mysql_fetch_assoc($data);
  $totalRows_data  = mysql_num_rows($data);
  if($totalRows_data>0){ do{
    if(isset($indicateur_dynamique[$id]["val"])) $indicateur_dynamique[$id]["val"] += $row_data["nb"];
    else $indicateur_dynamique[$id]["val"] = $row_data["nb"]; }while($row_data  = mysql_fetch_assoc($data)); }       }
}
   }while($row_entete  = mysql_fetch_assoc($entete)); }


unset($taux_progress,$taux,$tauxG,$tauxT);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ptba = "SELECT unite, id_indicateur_tache, intitule_indicateur_tache, code_indicateur_ptba, ".$database_connect_prefix."indicateur_tache.id_activite FROM ".$database_connect_prefix."indicateur_tache, ".$database_connect_prefix."cible_indicateur_trimestre where ".$database_connect_prefix."indicateur_tache.id_activite=".$database_connect_prefix."cible_indicateur_trimestre.id_activite and ".$database_connect_prefix."indicateur_tache.projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."cible_indicateur_trimestre.projet='".$_SESSION["clp_projet"]."' and id_indicateur_tache=indicateur group by ".$database_connect_prefix."indicateur_tache.id_activite,id_indicateur_tache";
$liste_ind_ptba  = mysql_query_ruche($query_liste_ind_ptba , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ptba  = mysql_fetch_assoc($liste_ind_ptba);
$totalRows_liste_ind_ptba  = mysql_num_rows($liste_ind_ptba);

//Valeur cible
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cible = "SELECT sum(cible) as valeur_cible, avg(cible) as valeur_cible_avg, id_activite, indicateur FROM ".$database_connect_prefix."cible_indicateur_trimestre where projet='".$_SESSION["clp_projet"]."' and projet='".$_SESSION["clp_projet"]."' group by id_activite,indicateur";
$cible  = mysql_query_ruche($query_cible , $pdar_connexion) or die(mysql_error());
$row_cible  = mysql_fetch_assoc($cible);
$totalRows_cible  = mysql_num_rows($cible);
$row_cible_ind = array();
if($totalRows_cible>0){ do{ $id = $row_cible["indicateur"]; $id_act = $row_cible["id_activite"];
  if(isset($row_cible_ind["sum"][$id_act][$id])) $row_cible_ind["sum"][$id_act][$id] += $row_cible["valeur_cible"];
  else $row_cible_ind["sum"][$id_act][$id] = $row_cible["valeur_cible"];
  if(isset($row_cible_ind["avg"][$id_act][$id])) $row_cible_ind["avg"][$id_act][$id] += $row_cible["valeur_cible_avg"];
  else $row_cible_ind["avg"][$id_act][$id] = $row_cible["valeur_cible_avg"];
}while($row_cible  = mysql_fetch_assoc($cible)); }

//Valeur reelle
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_valeur_suivi_ind = "SELECT sum(valeur_suivi) as valeur_reelle, avg(valeur_suivi) as valeur_reelle_avg, indicateur  FROM ".$database_connect_prefix."suivi_indicateur_tache where projet='".$_SESSION["clp_projet"]."' group by indicateur ";
$valeur_suivi_ind  = mysql_query_ruche($query_valeur_suivi_ind , $pdar_connexion) or die(mysql_error());
$row_valeur_suivi_ind  = mysql_fetch_assoc($valeur_suivi_ind);
$totalRows_valeur_suivi_ind  = mysql_num_rows($valeur_suivi_ind);
$row_suivi_ind = array();
if($totalRows_valeur_suivi_ind>0){ do{ $id = $row_valeur_suivi_ind["indicateur"];
  if(isset($row_suivi_ind["sum"][$id])) $row_suivi_ind["sum"][$id] += $row_valeur_suivi_ind["valeur_reelle"];
  else $row_suivi_ind["sum"][$id] = $row_valeur_suivi_ind["valeur_reelle"];
  if(isset($row_suivi_ind["avg"][$id])) $row_suivi_ind["avg"][$id] += $row_valeur_suivi_ind["valeur_reelle_avg"];
  else $row_suivi_ind["avg"][$id] = $row_valeur_suivi_ind["valeur_reelle_avg"];
}while($row_valeur_suivi_ind  = mysql_fetch_assoc($valeur_suivi_ind)); }

//Valeur reelle textuel
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_valeur_suivi_ind = "SELECT (valeur_txt) as valeur_reelle, indicateur  FROM ".$database_connect_prefix."suivi_indicateur_tache where projet='".$_SESSION["clp_projet"]."' and valeur_txt is not null group by indicateur ";
$valeur_suivi_ind  = mysql_query_ruche($query_valeur_suivi_ind , $pdar_connexion) or die(mysql_error());
$row_valeur_suivi_ind  = mysql_fetch_assoc($valeur_suivi_ind);
$totalRows_valeur_suivi_ind  = mysql_num_rows($valeur_suivi_ind);
//$row_suivi_ind = array();
if($totalRows_valeur_suivi_ind>0){ do{ $id = $row_valeur_suivi_ind["indicateur"];
  $row_suivi_ind["sum"][$id] = $row_valeur_suivi_ind["valeur_reelle"];
}while($row_valeur_suivi_ind  = mysql_fetch_assoc($valeur_suivi_ind)); }

$tauxG=$tauxT=$taux_tache=$valeur_tache=array(); $taux_progress = $tauxGG = 0;

if($totalRows_liste_ind_ptba>0) {$m=0;do {
//Cible
$id_ind=$row_liste_ind_ptba['id_activite'];
$id_ind_tache=$row_liste_ind_ptba['id_indicateur_tache'];
$unite=$row_liste_ind_ptba['unite']; $fn = ($unite=="%")?'avg':'sum';
if(!isset($tauxG[$id_ind])) $tauxG[$id_ind] = 0; if(!isset($tauxT[$id_ind])) $tauxT[$id_ind] = 0;
if(!isset($taux_tache[$id_ind_tache])) $taux_tache[$id_ind_tache] = 0;
if(!isset($valeur_tache[$id_ind_tache])) $valeur_tache[$id_ind_tache] = 0;
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cible_ind = "SELECT $fn(cible) as valeur_cible FROM ".$database_connect_prefix."cible_indicateur_trimestre where indicateur='$id_ind_tache' and projet='".$_SESSION["clp_projet"]."' and id_activite='$id_ind' and projet='".$_SESSION["clp_projet"]."' group by indicateur";
$cible_ind  = mysql_query_ruche($query_cible_ind , $pdar_connexion) or die(mysql_error());
$row_cible_ind  = mysql_fetch_assoc($cible_ind);
$totalRows_cible_ind  = mysql_num_rows($cible_ind);  */
//suivi indicateur
if(in_array($id_ind_tache,array_keys($indicateur_dynamique)))
{
  $ind_dyn = $indicateur_dynamique[$id_ind_tache];
  $row_suivi_ind['valeur_reelle'] = $ind_dyn["val"];
  $totalRows_suivi_ind = 1;
}
else
{
  /*mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_suivi_ind = "SELECT sum(valeur_suivi) as valeur_reelle  FROM ".$database_connect_prefix."suivi_indicateur_tache where indicateur='$id_ind_tache' and projet='".$_SESSION["clp_projet"]."' group by indicateur ";
  $suivi_ind  = mysql_query_ruche($query_suivi_ind , $pdar_connexion) or die(mysql_error());
  $row_suivi_ind  = mysql_fetch_assoc($suivi_ind);
  $totalRows_suivi_ind  = mysql_num_rows($suivi_ind);  */
  $totalRows_suivi_ind = 1;
  $row_suivi_ind['valeur_reelle'] = $row_suivi_ind["sum"][$id_ind_tache];
  if(in_array($unite,$array_indic)){ $totalRows_liste_ind_ptba--; $tauxT[$id_ind]--; }
}

if(in_array($unite,$array_indic)){ $valeur_tache[$id_ind_tache] = $row_suivi_ind["sum"][$id_ind_tache]; }
else $valeur_tache[$id_ind_tache] += $row_suivi_ind['valeur_reelle'];
$taux = 0;
/*if(isset($row_cible_ind['valeur_cible']) && $row_cible_ind['valeur_cible']>0  && $totalRows_suivi_ind>0) {$taux=100*$row_suivi_ind['valeur_reelle']/$row_cible_ind['valeur_cible']; $taux = ($taux>100)?100:$taux;  }*/
if(isset($row_cible_ind[$fn][$id_ind][$id_ind_tache]) && $row_cible_ind[$fn][$id_ind][$id_ind_tache]>0  && $totalRows_suivi_ind>0) {$taux=100*$row_suivi_ind['valeur_reelle']/$row_cible_ind[$fn][$id_ind][$id_ind_tache]; $taux = ($taux>100)?100:$taux;  }
$tauxG[$id_ind] += $taux; $tauxGG+=$taux; $taux_tache[$id_ind_tache] = $taux;
$tauxT[$id_ind] += 1;
} while ($row_liste_ind_ptba = mysql_fetch_assoc($liste_ind_ptba));}
if($totalRows_liste_ind_ptba>0) $taux_progress = $tauxGG/$totalRows_liste_ind_ptba;
?>
<style>#sp_hr {margin:0px; }
.r_float{float: right;}
.Style11 { font-weight: bold;color: #FFFFFF;}
.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; } .marquer{background: #FFFF00!important; }
</style>

<div class="well well-sm"><strong>Chronogramme des acivit&eacute;s du PTBA <?php echo "$annee"; ?></strong></div>

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-bordered table-responsive">
            <!--<thead> -->
            <tr bgcolor="<?php echo (!isset($_GET["down"]))?'':'#E4E4E4'; ?>">
              <td rowspan="2" align="left">ACTIVITES</td>
              <!--<td rowspan="2" align="center"><b>N&deg;&nbsp;</b></td>-->
              <td rowspan="2" align="left">INDICATEUR</td>
              <td rowspan="2" align="center">UNITE</td>
              <td rowspan="2" align="center">TAUX (%)</td>
              <!--<td colspan="4" ><center>VALEUR REELLE</center></td> -->
              <td colspan="3" align="center">TOTAL</td>
            </tr>
            <tr bgcolor="<?php echo (!isset($_GET["down"]))?'':'#E4E4E4'; ?>">
              <?php //for($j=1;$j<=4;$j++){ ?>
              <!--<td align="center"><center>Trimestre <?php echo $j; ?></center></td>-->
              <?php //} ?>
              <td align="center">VALEUR CIBLE</td>
              <td align="center">VALEUR REELLE</td>
              <td align="center">TAUX (%)</td>
            </tr>
            <!--</thead>-->
<?php  if($totalRows_edit_ms>0) { do {
$code = $row_edit_ms['code'];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."ptba where projet='".$_SESSION["clp_projet"]."' and annee='$annee' and code_activite_ptba like '$code%' ORDER By code_activite_ptba asc";
$liste_rec = mysql_query_ruche($query_liste_rec, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_rec = mysql_fetch_assoc($liste_rec);
$totalRows_liste_rec = mysql_num_rows($liste_rec);
if($totalRows_liste_rec>0) {$i=0; $t=0; $p2=$p1="j"; ?>
            <tr bgcolor="#BED694">
              <td colspan="7" align="center" style="background-color: #BED694;">
                <b><?php echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?></b>
              </td>
            </tr>
<?php $row=""; do { $code_act = $row_liste_rec['code_activite_ptba']; $id_act = $row_liste_rec['id_ptba'];
if(isset($indicateur_array[$id_act])){ $k=0;
foreach($indicateur_array[$id_act] as $a=>$b){ $total = 0; $div = 0; ?>
            <tr>
<?php if(/*$row*/$k==0/*$b["code_indicateur_ptba"]*/){ ?>
<td rowspan="<?php echo count($indicateur_array[$id_act]); ?>"><?php echo "<b>".$row_liste_rec['code_activite_ptba'].'</b> : '.$row_liste_rec['intitule_activite_ptba']; ?></td>
<?php } ?>
<!--<td><?php //echo $b['code_indicateur_ptba']; ?></td>-->
<td><?php echo $b['intitule_indicateur_tache']; ?></td>
<td><?php echo $b['unite']; ?></td>
<td align="center" ><?php echo $taux_tache[$b['id_indicateur_tache']]." %"; ?></td>
<?php for($j=1;$j<=4;$j++){ if(!in_array($b['unite'],$array_indic) && isset($tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j])) $total+= $tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j]; ?>
<!--<td width="50" valign="middle" align="center"><?php if(isset($tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j])) echo $tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j]; if(!empty($tableau_cible_indicateur_array[$b['id_indicateur_tache']][$j])) $div++; ?></td> -->
<?php } ?>
<td valign="middle" align="center"><?php $total = ($b['unite']=="%")?$total/$div:$total; echo (!in_array($b['unite'],$array_indic))?$total:'-'; ?></td>
<td valign="middle" align="center" ><?php echo $valeur_tache[$b['id_indicateur_tache']]; ?></td>
<?php if($k==0){ ?>
<td rowspan="<?php echo count($indicateur_array[$id_act]); ?>" valign="middle" align="center" ><?php echo $tauxG[$id_act]/$tauxT[$id_act]." %"; ?></td>
<?php } ?>
            </tr>
            <?php $row=$b["code_indicateur_ptba"]; $k++; } } ?>
<tr class="even">
  <td colspan="7"><div align="center" style="background-color:#CCCCCC; height: 2px;">&nbsp;</div></td>
</tr>
			<?php } while ($row_liste_rec= mysql_fetch_assoc($liste_rec)); ?>
            <?php } else { ?>
<!--            <tr>
              <td colspan="7"><div align="center"><span class="Style4"><em><strong>Aucune activit&eacute; enregistr&eacute;e dans la composante <?php //echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?> ! </strong></em></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span></div></td>
            </tr>-->
            <?php }  ?>
      <?php } while ($row_edit_ms = mysql_fetch_assoc($edit_ms)); } else { ?>
      <tr>
        <td colspan="7" align="center"><strong><em>Aucune composante trouv&eacute;e!</em></strong></td>
      </tr>
      <?php } ?>
</table>