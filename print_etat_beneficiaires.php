<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}

include_once $config->sys_folder . "/database/db_connexion.php";

if(isset($_GET['annee']) && !empty($_GET["annee"])) {$annee=intval($_GET['annee']);} else {$annee=date("Y");}
if(isset($_GET["cmp"]) && !empty($_GET["cmp"])) $cmp = $_GET["cmp"];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
} else $editFormAction .= "?";

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_loc = "SELECT T0.code_region, T0.nom_region, T1.code_departement, T1.nom_departement, T2.code_commune, T2.nom_commune, T3.* FROM region T0, departement T1, commune T2, village T3 WHERE T1.region=T0.code_region and T2.departement=T1.code_departement and T3.commune=T2.code_commune group by T2.code_commune";
$loc = mysql_query($query_loc , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_loc = mysql_fetch_assoc($loc);
$totalRows_loc = mysql_num_rows($loc);
$localite_array = $region_array = array(); $localite = array("region","departement","commune"/*,"village"*/);
if($totalRows_loc>0){
do{
  foreach($localite as $a)
  {
    if(!isset($localite_array[$a])) $localite_array[$a] = array();
    $localite_array[$a][$row_loc["code_$a"]] = $row_loc["nom_$a"];
  }
  $region_array[$row_loc["code_region"]][$row_loc["code_departement"]][$row_loc["code_commune"]] = 0;
}while($row_loc = mysql_fetch_assoc($loc)); }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SHOW tables";
$liste_cp = mysql_query($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);

$list_fiche = $table_array = $my_array = $REG_TOTAL = $DEP_TOTAL = $COM_TOTAL = array(); $table_sexe=array("M","F");
$table_sexe_menage=array("homme","femme");
if($totalRows_liste_cp>0) {
do {  if(strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!="" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!="fiche_config"){  $table_array[]=$row_liste_cp["Tables_in_$database_pdar_connexion"];
}
} while ($row_liste_cp = mysql_fetch_assoc($liste_cp)); }  $list_fiche = $table_array;

if(isset($cmp) && !empty($cmp) && in_array($cmp,$table_array)){ unset($table_array); $table_array[] = $cmp; }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config ";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);

if($totalRows_entete>0){ do{ $cp = $row_entete["table"]; $nomT0[$cp]=$row_entete["nom"]; $note0[$cp]=$row_entete["note"]; $entete_array0[$cp]=explode("|",$row_entete["show"]); $libelle0[$cp]=explode("|",$row_entete["libelle"]);
$intitule0[$cp]=$row_entete["intitule"]; $colonne0[$cp]=$row_entete["colonnes"]; $lignetotal0[$cp]=$row_entete["lignetotal"]; $colnum0[$cp]=$row_entete["colnum"]; $detail_sexe0[$cp]=$row_entete["detail_sexe"]; $detail_menage0[$cp]=$row_entete["detail_menage"];
$count = count($libelle0[$cp])-2;
$count = explode("=",$libelle0[$cp][$count]);
$lib_nom_fich0[$cp] = "";
if(isset($count[1]))
$lib_nom_fich0[$cp] = $count[1];
elseif(isset($count[0]))
$lib_nom_fich0[$cp] = $count[0];
if(empty($lib_nom_fich0[$cp])) $lib_nom_fich0[$cp] = $cp;
}while ($row_entete  = mysql_fetch_assoc($entete)); }

foreach($table_array as $cp){
/*$entete_array = $libelle = array(); */$tmp = explode("_",$cp); $calsseur_id = intval($tmp[1]);
$tab = substr($cp,strlen($database_connect_prefix));

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$tab'";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);

if($totalRows_entete>0){ $choix_array = array(); $nomT=$row_entete["nom"]; $note=$row_entete["note"]; $entete_array=explode("|",$row_entete["show"]); $libelle=explode("|",$row_entete["libelle"]);
$intitule=$row_entete["intitule"]; $colonne=$row_entete["colonnes"]; $lignetotal=$row_entete["lignetotal"]; $colnum=$row_entete["colnum"]; $detail_sexe=$row_entete["detail_sexe"]; $detail_menage=$row_entete["detail_menage"];
if(!empty($row_entete["choix"])){ foreach(explode("|",$row_entete["choix"]) as $elem){ if(!empty($elem)){  $a=explode(";",$elem); $choix_array[$a[0]]=""; for($i=1;$i<count($a);$i++){ $choix_array[$a[0]].=(!empty($a[$i]))?$a[$i].";":""; } }   }  }

$count = count($libelle)-2;
$count = explode("=",$libelle[$count]);
$lib_nom_fich = "";
if(isset($count[1]))
$lib_nom_fich = $count[1];
elseif(isset($count[0]))
$lib_nom_fich = $count[0];
}

if(empty($lib_nom_fich)) $lib_nom_fich = $cp;  */
$lib_nom_fich = $lib_nom_fich0[$cp];
$nomT=$nomT0[$cp];
$note=$note0[$cp];
$entete_array=$entete_array0[$cp];
$libelle=$libelle0[$cp];
$intitule=$intitule0[$cp];
$colonne=$colonne0[$cp];
$lignetotal=$lignetotal0[$cp];
$colnum=$colnum0[$cp];
$detail_sexe = $detail_sexe0[$cp];
$detail_menage=$detail_menage0[$cp]; //$choix_array = $choix_array0[$cp];

$Djeune = (date('Y')-24).date("-01-01"); $Dadulte = (date('Y')-15).date("-12-31");

if($detail_menage==1 && in_array("homme",$entete_array) && in_array("femme",$entete_array) && in_array("jhomme",$entete_array) && in_array("jfemme",$entete_array))
{
  $my_array[$cp]["classeur"]=$calsseur_id; $my_array[$cp]["feuille"]=$lib_nom_fich;
  foreach($table_sexe as $cle=>$sexe)
  {
  mysql_select_db($database_pdar_connexion, $pdar_connexion);//, sum($cp.nbrmenage) as nbrmenage
$query_act = "SELECT sum($cp.$table_sexe_menage[$cle]) as total, ".$database_connect_prefix."commune.code_commune, ".$database_connect_prefix."departement.code_departement, ".$database_connect_prefix."region.code_region FROM $cp, ".$database_connect_prefix."region, ".$database_connect_prefix."departement, ".$database_connect_prefix."commune, ".$database_connect_prefix."village WHERE ".$_SESSION["clp_where"]." and ".$database_connect_prefix."region.code_region=".$database_connect_prefix."departement.region and ".$database_connect_prefix."departement.code_departement=".$database_connect_prefix."commune.departement and ".$database_connect_prefix."commune.code_commune=".$database_connect_prefix."village.commune and ".$database_connect_prefix."village.code_village=$cp.village group by $cp.village,".$database_connect_prefix."village.commune,".$database_connect_prefix."commune.departement,".$database_connect_prefix."departement.region"; 
  $act  = mysql_query($query_act , $pdar_connexion) or die(mysql_error());
  $row_act  = mysql_fetch_assoc($act);
  $totalRows_act  = mysql_num_rows($act);
  if($totalRows_act>0){ do {
      if(!in_array($row_act["code_region"],$REG_TOTAL)) array_push($REG_TOTAL,$row_act["code_region"]);
      if(!in_array($row_act["code_departement"],$DEP_TOTAL)) array_push($DEP_TOTAL,$row_act["code_departement"]);
      if(!in_array($row_act["code_commune"],$COM_TOTAL)) array_push($COM_TOTAL,$row_act["code_commune"]);

    if(!isset($REGION[$sexe]["region"][$row_act["code_region"]])) $REGION[$sexe]["region"][$row_act["code_region"]] = $row_act["total"];
    else $REGION[$sexe]["region"][$row_act["code_region"]] += $row_act["total"];
    if(!isset($REGION[$sexe]["departement"][$row_act["code_departement"]])) $REGION[$sexe]["departement"][$row_act["code_departement"]] = $row_act["total"];
    else $REGION[$sexe]["departement"][$row_act["code_departement"]] += $row_act["total"];

    if(!isset($REGION[$sexe]["commune"][$row_act["code_commune"]])) $REGION[$sexe]["commune"][$row_act["code_commune"]] = $row_act["total"];
    else $REGION[$sexe]["commune"][$row_act["code_commune"]] += $row_act["total"];

    /*if(!isset($REGION_MENAGE[$sexe]["region"][$row_act["code_region"]]))
    $REGION_MENAGE[$sexe]["region"][$row_act["code_region"]] = $row_act["nbrmenage"];
    else $REGION_MENAGE[$sexe]["region"][$row_act["code_region"]] += $row_act["nbrmenage"];
    if(!isset($REGION_MENAGE[$sexe]["departement"][$row_act["code_departement"]]))
    $REGION_MENAGE[$sexe]["departement"][$row_act["code_departement"]] = $row_act["nbrmenage"];
    else $REGION_MENAGE[$sexe]["departement"][$row_act["code_departement"]] += $row_act["nbrmenage"];
    if(!isset($REGION_MENAGE[$sexe]["commune"][$row_act["code_commune"]]))
    $REGION_MENAGE[$sexe]["commune"][$row_act["code_commune"]] = $row_act["nbrmenage"];
    else $REGION_MENAGE[$sexe]["commune"][$row_act["code_commune"]] += $row_act["nbrmenage"];  */
    } while ($row_act  = mysql_fetch_assoc($act));
  }

  mysql_select_db($database_pdar_connexion, $pdar_connexion); //, sum($cp.nbrmenage) as nbrmenage
  $query_act = "SELECT sum($cp.j$table_sexe_menage[$cle]) as total, ".$database_connect_prefix."commune.code_commune, ".$database_connect_prefix."departement.code_departement, ".$database_connect_prefix."region.code_region FROM $cp, ".$database_connect_prefix."region, ".$database_connect_prefix."departement, ".$database_connect_prefix."commune, ".$database_connect_prefix."village WHERE ".$_SESSION["clp_where"]." and ".$database_connect_prefix."region.code_region=".$database_connect_prefix."departement.region and ".$database_connect_prefix."departement.code_departement=".$database_connect_prefix."commune.departement and ".$database_connect_prefix."commune.code_commune=".$database_connect_prefix."village.commune and ".$database_connect_prefix."village.code_village=$cp.village group by $cp.village,".$database_connect_prefix."village.commune,".$database_connect_prefix."commune.departement,".$database_connect_prefix."departement.region";
  $act  = mysql_query($query_act , $pdar_connexion) or die(mysql_error());
  $row_act  = mysql_fetch_assoc($act);
  $totalRows_act  = mysql_num_rows($act);
  if($totalRows_act>0){
  do {
    if(!isset($REGION_JEUNE[$sexe]["region"][$row_act["code_region"]])) $REGION_JEUNE[$sexe]["region"][$row_act["code_region"]] = $row_act["total"];
    else $REGION_JEUNE[$sexe]["region"][$row_act["code_region"]] += $row_act["total"];
    if(!isset($REGION_JEUNE[$sexe]["departement"][$row_act["code_departement"]])) $REGION_JEUNE[$sexe]["departement"][$row_act["code_departement"]] = $row_act["total"];
    else $REGION_JEUNE[$sexe]["departement"][$row_act["code_departement"]] += $row_act["total"];
    if(!isset($REGION_JEUNE[$sexe]["commune"][$row_act["code_commune"]])) $REGION_JEUNE[$sexe]["commune"][$row_act["code_commune"]] = $row_act["total"];
    else $REGION_JEUNE[$sexe]["commune"][$row_act["code_commune"]] += $row_act["total"];

    /*if(!isset($REGION_MENAGE[$sexe]["region"][$row_act["code_region"]]))
    $REGION_MENAGE[$sexe]["region"][$row_act["code_region"]] = $row_act["nbrmenage"];
    else $REGION_MENAGE[$sexe]["region"][$row_act["code_region"]] += $row_act["nbrmenage"];
    if(!isset($REGION_MENAGE[$sexe]["departement"][$row_act["code_departement"]]))
    $REGION_MENAGE[$sexe]["departement"][$row_act["code_departement"]] = $row_act["nbrmenage"];
    else $REGION_MENAGE[$sexe]["departement"][$row_act["code_departement"]] += $row_act["nbrmenage"];
    if(!isset($REGION_MENAGE[$sexe]["commune"][$row_act["code_commune"]]))
    $REGION_MENAGE[$sexe]["commune"][$row_act["code_commune"]] = $row_act["nbrmenage"];
    else $REGION_MENAGE[$sexe]["commune"][$row_act["code_commune"]] += $row_act["nbrmenage"];  */
  } while ($row_act  = mysql_fetch_assoc($act)); }
    }
//Menage
if(in_array("nbrmenage",$entete_array))
{
  foreach($table_sexe as $cle=>$sexe)
  {
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = "SELECT sum($cp.nbrmenage) as nbrmenage, ".$database_connect_prefix."commune.code_commune, ".$database_connect_prefix."departement.code_departement, ".$database_connect_prefix."region.code_region FROM $cp, ".$database_connect_prefix."region, ".$database_connect_prefix."departement, ".$database_connect_prefix."commune, ".$database_connect_prefix."village WHERE sexe='$sexe' and ".$_SESSION["clp_where"]." and ".$database_connect_prefix."region.code_region=".$database_connect_prefix."departement.region and ".$database_connect_prefix."departement.code_departement=".$database_connect_prefix."commune.departement and ".$database_connect_prefix."commune.code_commune=".$database_connect_prefix."village.commune and ".$database_connect_prefix."village.code_village=$cp.village group by $cp.village,".$database_connect_prefix."village.commune,".$database_connect_prefix."commune.departement,".$database_connect_prefix."departement.region";
  $act  = mysql_query($query_act , $pdar_connexion) or die(mysql_error());
  $row_act  = mysql_fetch_assoc($act);
  $totalRows_act  = mysql_num_rows($act);
  if($totalRows_act>0){ do {
    if(!isset($REGION_MENAGE[$sexe]["region"][$row_act["code_region"]]))
    $REGION_MENAGE[$sexe]["region"][$row_act["code_region"]] = $row_act["nbrmenage"];
    else $REGION_MENAGE[$sexe]["region"][$row_act["code_region"]] += $row_act["nbrmenage"];
    if(!isset($REGION_MENAGE[$sexe]["departement"][$row_act["code_departement"]]))
    $REGION_MENAGE[$sexe]["departement"][$row_act["code_departement"]] = $row_act["nbrmenage"];
    else $REGION_MENAGE[$sexe]["departement"][$row_act["code_departement"]] += $row_act["nbrmenage"];
    if(!isset($REGION_MENAGE[$sexe]["commune"][$row_act["code_commune"]]))
    $REGION_MENAGE[$sexe]["commune"][$row_act["code_commune"]] = $row_act["nbrmenage"];
    else $REGION_MENAGE[$sexe]["commune"][$row_act["code_commune"]] += $row_act["nbrmenage"];
    } while ($row_act  = mysql_fetch_assoc($act));
  }
  }
}
else
{
  foreach($table_sexe as $sexe)
  {
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = "SELECT count(LKEY) as total, ".$database_connect_prefix."commune.code_commune, ".$database_connect_prefix."departement.code_departement, ".$database_connect_prefix."region.code_region FROM $cp, ".$database_connect_prefix."region, ".$database_connect_prefix."departement, ".$database_connect_prefix."commune, ".$database_connect_prefix."village WHERE ".$_SESSION["clp_where"]." and sexe='$sexe'  and ".$database_connect_prefix."region.code_region=".$database_connect_prefix."departement.region and ".$database_connect_prefix."departement.code_departement=".$database_connect_prefix."commune.departement and ".$database_connect_prefix."commune.code_commune=".$database_connect_prefix."village.commune and ".$database_connect_prefix."village.code_village=$cp.village group by $cp.village,".$database_connect_prefix."village.commune,".$database_connect_prefix."commune.departement,".$database_connect_prefix."departement.region";
  $act  = mysql_query($query_act , $pdar_connexion) or die(mysql_error());
  $row_act  = mysql_fetch_assoc($act);
  $totalRows_act  = mysql_num_rows($act);
  if($totalRows_act>0){ do {
    if(!isset($REGION_MENAGE[$sexe]["region"][$row_act["code_region"]]))
    $REGION_MENAGE[$sexe]["region"][$row_act["code_region"]] = $row_act["total"];
    else $REGION_MENAGE[$sexe]["region"][$row_act["code_region"]]+= $row_act["total"];
    if(!isset($REGION_MENAGE[$sexe]["departement"][$row_act["code_departement"]]))
    $REGION_MENAGE[$sexe]["departement"][$row_act["code_departement"]] = $row_act["total"];
    else $REGION_MENAGE[$sexe]["departement"][$row_act["code_departement"]]+= $row_act["total"];
    if(!isset($REGION_MENAGE[$sexe]["commune"][$row_act["code_commune"]]))
    $REGION_MENAGE[$sexe]["commune"][$row_act["code_commune"]] = $row_act["total"];
    else $REGION_MENAGE[$sexe]["commune"][$row_act["code_commune"]]+= $row_act["total"];
    } while ($row_act  = mysql_fetch_assoc($act));
  }
}
}
    }
elseif($detail_sexe==1 && in_array("datenaissance",$entete_array) && in_array("sexe",$entete_array))
{
  $my_array[$cp]["classeur"]=$calsseur_id; $my_array[$cp]["feuille"]=$lib_nom_fich;
foreach($table_sexe as $sexe)
{
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = "SELECT count(sexe) as total, ".$database_connect_prefix."commune.code_commune, ".$database_connect_prefix."departement.code_departement, ".$database_connect_prefix."region.code_region FROM $cp, ".$database_connect_prefix."region, ".$database_connect_prefix."departement, ".$database_connect_prefix."commune, ".$database_connect_prefix."village WHERE ".$_SESSION["clp_where"]." and sexe='$sexe'  and ".$database_connect_prefix."region.code_region=".$database_connect_prefix."departement.region and ".$database_connect_prefix."departement.code_departement=".$database_connect_prefix."commune.departement and ".$database_connect_prefix."commune.code_commune=".$database_connect_prefix."village.commune and ".$database_connect_prefix."village.code_village=$cp.village group by $cp.village,".$database_connect_prefix."village.commune,".$database_connect_prefix."commune.departement,".$database_connect_prefix."departement.region";
  $act  = mysql_query($query_act , $pdar_connexion) or die(mysql_error());
  $row_act  = mysql_fetch_assoc($act);
  $totalRows_act  = mysql_num_rows($act);
  if($totalRows_act>0){ do {
      if(!in_array($row_act["code_region"],$REG_TOTAL)) array_push($REG_TOTAL,$row_act["code_region"]);
      if(!in_array($row_act["code_departement"],$DEP_TOTAL)) array_push($DEP_TOTAL,$row_act["code_departement"]);
      if(!in_array($row_act["code_commune"],$COM_TOTAL)) array_push($COM_TOTAL,$row_act["code_commune"]);

    if(!isset($REGION[$sexe]["region"][$row_act["code_region"]])) $REGION[$sexe]["region"][$row_act["code_region"]] = $row_act["total"];
    else $REGION[$sexe]["region"][$row_act["code_region"]] += $row_act["total"];
    if(!isset($REGION[$sexe]["departement"][$row_act["code_departement"]])) $REGION[$sexe]["departement"][$row_act["code_departement"]] = $row_act["total"];
    else $REGION[$sexe]["departement"][$row_act["code_departement"]] += $row_act["total"];

    if(!isset($REGION[$sexe]["commune"][$row_act["code_commune"]])) $REGION[$sexe]["commune"][$row_act["code_commune"]] = $row_act["total"];
    else $REGION[$sexe]["commune"][$row_act["code_commune"]] += $row_act["total"];

    if(!isset($REGION_MENAGE[$sexe]["region"][$row_act["code_region"]]))
    $REGION_MENAGE[$sexe]["region"][$row_act["code_region"]] = 1;
    else $REGION_MENAGE[$sexe]["region"][$row_act["code_region"]]++;
    if(!isset($REGION_MENAGE[$sexe]["departement"][$row_act["code_departement"]]))
    $REGION_MENAGE[$sexe]["departement"][$row_act["code_departement"]] = 1;
    else $REGION_MENAGE[$sexe]["departement"][$row_act["code_departement"]]++;
    if(!isset($REGION_MENAGE[$sexe]["commune"][$row_act["code_commune"]]))
    $REGION_MENAGE[$sexe]["commune"][$row_act["code_commune"]] = 1;
    else $REGION_MENAGE[$sexe]["commune"][$row_act["code_commune"]]++;
    } while ($row_act  = mysql_fetch_assoc($act));
  }

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_act = "SELECT count(sexe) as total, ".$database_connect_prefix."commune.code_commune, ".$database_connect_prefix."departement.code_departement, ".$database_connect_prefix."region.code_region FROM $cp, ".$database_connect_prefix."region, ".$database_connect_prefix."departement, ".$database_connect_prefix."commune, ".$database_connect_prefix."village WHERE ".$_SESSION["clp_where"]." and sexe='$sexe' and datenaissance between '$Djeune' and '$Dadulte' and ".$database_connect_prefix."region.code_region=".$database_connect_prefix."departement.region and ".$database_connect_prefix."departement.code_departement=".$database_connect_prefix."commune.departement and ".$database_connect_prefix."commune.code_commune=".$database_connect_prefix."village.commune and ".$database_connect_prefix."village.code_village=$cp.village group by $cp.village,".$database_connect_prefix."village.commune,".$database_connect_prefix."commune.departement,".$database_connect_prefix."departement.region";
  $act  = mysql_query($query_act , $pdar_connexion) or die(mysql_error());
  $row_act  = mysql_fetch_assoc($act);
  $totalRows_act  = mysql_num_rows($act);
  if($totalRows_act>0){
  do {
    if(!isset($REGION_JEUNE[$sexe]["region"][$row_act["code_region"]])) $REGION_JEUNE[$sexe]["region"][$row_act["code_region"]] = $row_act["total"];
    else $REGION_JEUNE[$sexe]["region"][$row_act["code_region"]] += $row_act["total"];
    if(!isset($REGION_JEUNE[$sexe]["departement"][$row_act["code_departement"]])) $REGION_JEUNE[$sexe]["departement"][$row_act["code_departement"]] = $row_act["total"];
    else $REGION_JEUNE[$sexe]["departement"][$row_act["code_departement"]] += $row_act["total"];
    if(!isset($REGION_JEUNE[$sexe]["commune"][$row_act["code_commune"]])) $REGION_JEUNE[$sexe]["commune"][$row_act["code_commune"]] = $row_act["total"];
    else $REGION_JEUNE[$sexe]["commune"][$row_act["code_commune"]] += $row_act["total"];

    /*if(!isset($REGION_MENAGE[$sexe]["region"][$row_act["code_region"]]))
    $REGION_MENAGE[$sexe]["region"][$row_act["code_region"]] = 1;
    else $REGION_MENAGE[$sexe]["region"][$row_act["code_region"]]++;
    if(!isset($REGION_MENAGE[$sexe]["departement"][$row_act["code_departement"]]))
    $REGION_MENAGE[$sexe]["departement"][$row_act["code_departement"]] = 1;
    else $REGION_MENAGE[$sexe]["departement"][$row_act["code_departement"]]++;
    if(!isset($REGION_MENAGE[$sexe]["commune"][$row_act["code_commune"]]))
    $REGION_MENAGE[$sexe]["commune"][$row_act["code_commune"]] = 1;
    else $REGION_MENAGE[$sexe]["commune"][$row_act["code_commune"]]++;*/
  } while ($row_act  = mysql_fetch_assoc($act)); }
  }
}
}

foreach($region_array as $r=>$dep)
{
  $r;
  foreach($dep as $d=>$com)
  {
    foreach($com as $c=>$val)
    {
      if(!in_array($c,$COM_TOTAL)) unset($region_array[$r][$d][$c]);
    }
    if(!in_array($d,$DEP_TOTAL)) unset($region_array[$r][$d]);
  }
  if(!in_array($r,$REG_TOTAL)) unset($region_array[$r]);
}

if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=etat_beneficiaires.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=etat_beneficiaires.doc"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="p"){

require_once('./tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$PDF_HEADER_TITLE = "B&eacute;n&eacute;ficiaires";
$PDF_HEADER_STRING = utf8_encode("B&eacute;n&eacute;ficiaires");

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Ruche');
$pdf->SetTitle($PDF_HEADER_TITLE);
$pdf->SetSubject($PDF_HEADER_STRING);
$pdf->SetKeywords('PDF, Etat, B&eacute;n&eacute;ficiaires, B&eacute;n&eacute;ficiaires');

// set default header data //PDF_HEADER_LOGO
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE, $PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}
// set font
//$pdf->SetFont('dejavusans', '', 10);
$pdf->AddPage();

  ob_start(); // turn on output buffering
  /*$_GET["id"]="0001";
  $_GET["down"]=5; */
  include("./print_etat_beneficiaires_pdf.php");
  $content = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering

$html = utf8_encode($content);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('etat_beneficiaires.pdf', 'D');
/*
include("pdf/mpdf.php");
$mpdf=new mPDF('win-1252','A4-L','','',15,10,16,10,10,10);//A4 page in portrait for landscape add -L.
$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetDisplayMode('fullpage');
ob_start();
include "print_recommandation_mission_pdf.php";
$html = ob_get_contents();
ob_end_clean();
$mpdf->WriteHTML($html);
$mpdf->Output();
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Recommandation_mission.pdf"); */
exit;

 } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php if(!isset($_GET["down"])){  ?>
<head>
  <title><?php print $config->sitename; ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->
  <!--[if IE 8]><link href="<?php print $config->theme_folder; ?>/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->
  <link href='<?php print $config->theme_folder; ?>/css.css' rel='stylesheet' type='text/css'>
  <!--<link rel="stylesheet" href="<?php print $config->theme_folder; ?>/table.css" type="text/css" > -->
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/lodash.compat.min.js"></script>
  <!--[if lt IE 9]><script src="<?php print $config->script_folder; ?>/libs/html5shiv.js"></script><![endif]-->
  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>
  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/breakpoints.js"></script>
  <script type="text/javascript" src="plugins/respond/respond.min.js"></script>
  <script type="text/javascript" src="plugins/cookie/jquery.cookie.min.js"></script>
  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.min.js"></script>
  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>
  <!--[if lt IE 9]><script type="text/javascript" src="plugins/flot/excanvas.min.js"></script><![endif]-->
  <!--<script type="text/javascript" src="plugins/sparkline/jquery.sparkline.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.tooltip.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.resize.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.time.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.growraf.min.js"></script>
  <script type="text/javascript" src="plugins/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
  <script type="text/javascript" src="plugins/daterangepicker/moment.min.js"></script>
  <script type="text/javascript" src="plugins/daterangepicker/daterangepicker.js"></script>-->
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
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/custom.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/pages_calendar.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_filled_blue.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_simple.js"></script>-->
 <script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
 <script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/login.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/myscript.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/ui_general.js"></script>
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>
</head>
<?php } ?>
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
.r_float{float: right;} .l_float{float: left;}
.Style11 { font-weight: bold;color: #FFFFFF;}
.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
</style>
<style>
.title {
  text-align: left!important;
  height: 60px;
  color: #000000 !important;
}
.value {
  text-align: right;
}
</style>
<!-- Site contenu ici -->
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; width: 100%!important;
} .table tbody tr td {vertical-align: middle; } tfoot tr th {text-align: right; }
</style>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
} else $editFormAction .= "?";
?>
<?php if(!isset($_GET["down"])){  ?>
<div class="widget box box_projet">
<div class="widget-header1"> <center><h4><?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>
<?php }  ?>

<?php if(!isset($_GET["down"])){
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_fiche = "SELECT `table`,nom FROM ".$database_connect_prefix."fiche_config WHERE detail_sexe=1 OR detail_menage=1 ";
$fiche  = mysql_query($query_fiche , $pdar_connexion) or die(mysql_error());
$row_fiche  = mysql_fetch_assoc($fiche);
$totalRows_fiche  = mysql_num_rows($fiche);
?>
<div class="well well-sm l_float"><div class="l_float" style="padding: 7px;"><strong>Tableau des b&eacute;n&eacute;ficaires : </strong></div>
<form name="form_beneficiaire" id="form_beneficiaire" method="get" action="<?php echo $editFormAction; ?>" class="pull-right">
<select name="cmp" onchange="form_beneficiaire.submit();" style="background-color: #FFFF00; padding: 7px; width: 150px;" class="">
  <option value="">-- Tout --</option>
  <?php if($totalRows_fiche>0){ do{ ?>
<option value="<?php echo $row_fiche['table']; ?>" <?php if(isset($_GET["cmp"]) && $row_fiche['table']==$_GET["cmp"]) echo "selected='SELECTED'"; ?>><?php echo $row_fiche['nom']; ?></option>
  <?php }while($row_fiche  = mysql_fetch_assoc($fiche)); } ?>
</select>
</form>
</div>
<div class="well well-sm r_float"><div class="r_float"><a href="./s_suivi_resultat.php" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<!--<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format PDF" href="<?php echo $editFormAction."&down=1&t=p"; ?>" class="button"><img src="./images/pdf.png" width='20' height='20' alt='Modifier' /></a></div>--></div>
<div class="clear h0">&nbsp;</div>
<?php } else { ?>

<center><?php //include "./includes/print_header.php"; ?></center>

<?php } ?>

<?php if(isset($_GET["cmp"]) && !empty($_GET["cmp"])) echo "<div class='well well-sm'><h3>".(isset($my_array[$cmp]["feuille"])?$my_array[$cmp]["feuille"]:$cmp)."</h3></div>";
$jeune_homme = $homme = $jeune_femme = $femme = $total = $jeune = 0;
if(count($table_array)>0){ ?>
<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="1" class="table table-striped table-bordered table-responsive">
  <thead>
    <tr>
      <th rowspan="2" colspan="3"><center>Localit&eacute;s</center></th>
      <th rowspan="2"><center>M&eacute;nage</center></th>
      <th colspan="2"><center>Homme</center></th>
      <th colspan="2"><center>Femme</center></th>
      <th colspan="2"><center>Total</center></th>
      <th rowspan="2"><center>Source</center></th>
    </tr>
    <tr>
      <th><center>Jeune<br />(15-24 ans)</center></th>
      <th><center>Total</center></th>
      <th><center>Jeune<br />(15-24 ans)</center></th>
      <th><center>Total</center></th>
      <th><center>Jeune<br />(15-24 ans)</center></th>
      <th><center>Total</center></th>
    </tr>
  </thead>
  <tbody>
<?php $k=$rw=$l=0; $datatable = ""; $total_array = array();
$Gj_reg = $Gt_reg = $GjM = $GtM = $GjF = $GtF = $Gnm = 0;
foreach($region_array as $r=>$dep)
{
  $l=-1;
  $j_reg = $t_reg = $jM = $tM = $jF = $tF = $n_m = 0;
  $data = '<tr><td align="left" rowspan="%R%">'.$localite_array["region"][$r].'</td>';
  foreach($dep as $d=>$com)
  {
    $l++;
    $data .= '<td align="left" rowspan="%D%">'.$localite_array["departement"][$d].'</td>'; $i=count($com);
    foreach($com as $c=>$val)
    {
      $data .= '<td align="left">'.$localite_array["commune"][$c].'</td>'; $i--;
      $data .= '<td align="right">%VMC%</td>';
      foreach($table_sexe as $sexe)
      {
        $data .= '<td align="right">'.number_format(((isset($REGION_JEUNE[$sexe]["commune"][$c]))?$REGION_JEUNE[$sexe]["commune"][$c]:0), 0, '', ' ').'</td>';
        $data .= '<td align="right">'.number_format(((isset($REGION[$sexe]["commune"][$c]))?$REGION[$sexe]["commune"][$c]:0), 0, '', ' ').'</td>';
      }
      /*$jM += (isset($REGION_JEUNE["M"]["commune"][$c]))?$REGION_JEUNE["M"]["commune"][$c]:0;
      $jF += (isset($REGION_JEUNE["F"]["commune"][$c]))?$REGION_JEUNE["F"]["commune"][$c]:0;
      $tM += (isset($REGION["M"]["commune"][$c]))?$REGION["M"]["commune"][$c]:0;
      $tF += (isset($REGION["F"]["commune"][$c]))?$REGION["F"]["commune"][$c]:0;   */
      $m_com = ((isset($REGION_MENAGE["M"]["commune"][$c]))?$REGION_MENAGE["M"]["commune"][$c]:0)+((isset($REGION_MENAGE["F"]["commune"][$c]))?$REGION_MENAGE["F"]["commune"][$c]:0);
      $j_com = ((isset($REGION_JEUNE["M"]["commune"][$c]))?$REGION_JEUNE["M"]["commune"][$c]:0)+((isset($REGION_JEUNE["F"]["commune"][$c]))?$REGION_JEUNE["F"]["commune"][$c]:0);
      $t_com = ((isset($REGION["M"]["commune"][$c]))?$REGION["M"]["commune"][$c]:0)+((isset($REGION["F"]["commune"][$c]))?$REGION["F"]["commune"][$c]:0);
      //$j_dep = $j_com; $t_dep = $t_com;
      $data .= '<td align="right">'.number_format($j_com, 0, '', ' ').'</td>';
      $data .= '<td align="right">'.number_format($t_com, 0, '', ' ').'</td>';
      if($k==0) $data .= '<td align="left" rowspan="%S%">%SV%</td>'; $k++;
      $data .= ($i>0)?'</tr><tr>':'</tr>';
      $data = str_replace("%VMC%",number_format($m_com, 0, '', ' '),$data);
    }
    $data .= '<tr><td colspan="2" align="left"><b>Total '.$localite_array["departement"][$d].'</b></td>';
    $data .= '<td align="right">%VMD%</td>';
    foreach($table_sexe as $sexe)
    {
      $data .= '<td align="right">'.number_format(((isset($REGION_JEUNE[$sexe]["departement"][$d]))?$REGION_JEUNE[$sexe]["departement"][$d]:0), 0, '', ' ').'</td>';
      $data .= '<td align="right">'.number_format(((isset($REGION[$sexe]["departement"][$d]))?$REGION[$sexe]["departement"][$d]:0), 0, '', ' ').'</td>';
    }
    $m_dep = ((isset($REGION_MENAGE["M"]["departement"][$d]))?$REGION_MENAGE["M"]["departement"][$d]:0)+((isset($REGION_MENAGE["F"]["departement"][$d]))?$REGION_MENAGE["F"]["departement"][$d]:0);
    $jM += (isset($REGION_JEUNE["M"]["departement"][$d]))?$REGION_JEUNE["M"]["departement"][$d]:0;
    $jF += (isset($REGION_JEUNE["F"]["departement"][$d]))?$REGION_JEUNE["F"]["departement"][$d]:0;
    $tM += (isset($REGION["M"]["departement"][$d]))?$REGION["M"]["departement"][$d]:0;
    $tF += (isset($REGION["F"]["departement"][$d]))?$REGION["F"]["departement"][$d]:0;
    $j_dep = ((isset($REGION_JEUNE["M"]["departement"][$d]))?$REGION_JEUNE["M"]["departement"][$d]:0)+((isset($REGION_JEUNE["F"]["departement"][$d]))?$REGION_JEUNE["F"]["departement"][$d]:0);
    $t_dep = ((isset($REGION["M"]["departement"][$d]))?$REGION["M"]["departement"][$d]:0)+((isset($REGION["F"]["departement"][$d]))?$REGION["F"]["departement"][$d]:0);

    $data .= '<td align="right">'.number_format($j_dep, 0, '', ' ').'</td>';
    $data .= '<td align="right">'.number_format($t_dep, 0, '', ' ').'</td></tr>';
    $j_reg += $j_dep; $t_reg += $t_dep; $n_m += $m_dep;
    $Gnm += $m_dep;
    $GjM += (isset($REGION_JEUNE["M"]["departement"][$d]))?$REGION_JEUNE["M"]["departement"][$d]:0;
    $GjF += (isset($REGION_JEUNE["F"]["departement"][$d]))?$REGION_JEUNE["F"]["departement"][$d]:0;
    $Gj_reg += ((isset($REGION_JEUNE["M"]["departement"][$d]))?$REGION_JEUNE["M"]["departement"][$d]:0)+((isset($REGION_JEUNE["F"]["departement"][$d]))?$REGION_JEUNE["F"]["departement"][$d]:0);
    $GtM += (isset($REGION["M"]["departement"][$d]))?$REGION["M"]["departement"][$d]:0;
    $GtF += (isset($REGION["F"]["departement"][$d]))?$REGION["F"]["departement"][$d]:0;
    $Gt_reg += ((isset($REGION["M"]["departement"][$d]))?$REGION["M"]["departement"][$d]:0)+((isset($REGION["F"]["departement"][$d]))?$REGION["F"]["departement"][$d]:0);
    $data = str_replace("%D%",count($com),$data);
    $data = str_replace("%VMD%",number_format($m_dep, 0, '', ' '),$data);
  }
  $data .= '<tr><td colspan="3" align="left"><b>Total '.$localite_array["region"][$r].'</b></td>';
  $data .= '<td align="right">%VMR%</td>';
  $data .= '<td align="right">'.number_format($jM, 0, '', ' ').'</td>';
  $data .= '<td align="right">'.number_format($tM, 0, '', ' ').'</td>';
  $data .= '<td align="right">'.number_format($jF, 0, '', ' ').'</td>';
  $data .= '<td align="right">'.number_format($tF, 0, '', ' ').'</td>';

  $data .= '<td align="right">'.number_format($j_reg, 0, '', ' ').'</td>';
  $data .= '<td align="right">'.number_format($t_reg, 0, '', ' ').'</td></tr>';
  $data = str_replace("%R%",count($com)+count($dep)+$l,$data);
  $data = str_replace("%VMR%",number_format($n_m, 0, '', ' '),$data);
  $datatable .= $data; $rw+=count($com)+count($dep)+$l;
  //$GjM += $jM; $GtM += $tM; $GjF += $jF; $GtF += $tF; $Gj_reg += $jM+$jF; $Gt_reg += $tM+$tF;
}
$datatable = str_replace("%S%",$rw+count($region_array),$datatable); $data = "<ul class='list_fiche'>";
foreach($my_array as $a=>$b)
{
  $data .= '<li><a style="display: block;" href="./fiches_dynamiques.php?id='.$b["classeur"].'&feuille='.$a.'&annee=" title="Plus de d&eacute;tails">'.$b["feuille"].'</a></li>';
}
$data .= '</ul>';
$datatable = str_replace("%SV%",$data,$datatable);
echo $datatable;
?>
  </tbody>
  <tfoot>
  <tr>
    <th align="right" colspan="3">TOTAL</th>
    <th align="right"><?php echo number_format($Gnm, 0, '', ' '); ?></th>
    <th align="right"><?php echo number_format($GjM, 0, '', ' '); ?></th>
    <th align="right"><?php echo number_format($GtM, 0, '', ' '); ?></th>
    <th align="right"><?php echo number_format($GjF, 0, '', ' '); ?></th>
    <th align="right"><?php echo number_format($GtF, 0, '', ' '); ?></th>
    <th align="right"><?php echo number_format($Gj_reg, 0, '', ' '); ?></th>
    <th align="right"><?php echo number_format($Gt_reg, 0, '', ' '); ?></th>
    <th align="left"></th>
  </tr>
  </tfoot>
</table>
<?php } ?>
</div>
<!-- Fin Site contenu ici -->
<?php if(!isset($_GET["down"])){  ?>
            </div>
        </div>
<?php }  ?>

        </div>
    </div>   <?php if(!isset($_GET["down"])) include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>

</body>
</html>