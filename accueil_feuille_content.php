<?php
$colonne_array = $feuille_array = $calsseur_array = $color_array = array();

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_conf = "SELECT h.*, c.couleur as couleur_c FROM ".$database_connect_prefix."referentiel_fiche_config h, ".$database_connect_prefix."classeur c WHERE c.id_classeur=h.classeur and h.etat=1";
$liste_conf = mysql_query($query_liste_conf, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_conf = mysql_fetch_assoc($liste_conf);
$totalRows_liste_conf = mysql_num_rows($liste_conf);
if($totalRows_liste_conf>0){ do{ if(in_array($database_connect_prefix.$row_liste_conf["feuille"],$table_array)){
$feuille = $row_liste_conf["feuille"]; $colonneH = $row_liste_conf["colonne"];  $modeCalcul = $row_liste_conf["mode_calcul"];  $colonneC = $row_liste_conf["colonneC"]; $critere = $row_liste_conf["critere"];
$feuille_array[$feuille][$colonneH] = $colonneH; $calsseur_array[$feuille] = $row_liste_conf["classeur"];
$color_array[$feuille] = (!empty($row_liste_conf["couleur"]))?$row_liste_conf["couleur"]:$row_liste_conf["couleur_c"];

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$feuille'";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);

if($totalRows_entete>0){ $choix_array = array(); $nomT=$row_entete["nom"]; $note=$row_entete["note"]; $entete_array=explode("|",$row_entete["show"]); $libelle=explode("|",$row_entete["libelle"]);
$intitule=$row_entete["intitule"]; $colonne=$row_entete["colonnes"]; $lignetotal=$row_entete["lignetotal"];
if(!empty($row_entete["choix"])){ foreach(explode("|",$row_entete["choix"]) as $elem){ if(!empty($elem)){  $a=explode(";",$elem); $choix_array[$a[0]]=""; for($i=1;$i<count($a);$i++){ $choix_array[$a[0]].=(!empty($a[$i]))?$a[$i].";":""; } }   }  } }
$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}

foreach($libelle as $llib1)
{
  $lib=explode("=",$llib1);
  $libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";
}
$feuille_array[$feuille][$colonneH] = isset($libelle_array[$colonneH])?$libelle_array[$colonneH]:$colonneH;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "DESCRIBE ".$database_connect_prefix."$feuille";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);
$num=0;
if($totalRows_entete>0){ do{ if($row_entete["Field"]==$colonneH) $num++; }while($row_entete  = mysql_fetch_assoc($entete));  }

$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}

// si ligne total on crée les variables
//if($lignetotal==1){
$Ltotal = $Ftotal = array();
if($totalRows_entete>0 && $num>0){ do{  if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array)){
 ?>
<?php $Ltotal[$row_entete["Field"]]=0; $Ftotal[$row_entete["Field"]]=0;  ?>
<?php  }
}while($row_entete  = mysql_fetch_assoc($entete));
$rows = mysql_num_rows($entete);
  if($rows > 0) {
  mysql_data_seek($entete, 0);
  $row_entete = mysql_fetch_assoc($entete);
  }
    }
//}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_validation = "SELECT * FROM ".$database_connect_prefix."validation_fiche WHERE projet='".$_SESSION["clp_projet"]."' and nom_fiche='$feuille'";
  $validation  = mysql_query_ruche($query_validation , $pdar_connexion) or die(mysql_error());
  $row_validation  = mysql_fetch_assoc($validation);
  $totalRows_validation  = mysql_num_rows($validation);
  $data_validate_array = array();
  if($totalRows_validation>0){ do{ $data_validate_array[] = $row_validation["id_lkey"]; }while($row_validation  = mysql_fetch_assoc($validation));  }


$swhere = (!empty($colonneC) && !empty($critere))?" and $colonneC IN ('$critere')":"";
mysql_select_db($database_pdar_connexion, $pdar_connexion); //annee='$annee' and
$query_act = "SELECT * FROM ".$database_connect_prefix."$feuille WHERE projet='".$_SESSION["clp_projet"]."' and structure='$cmp' $swhere ";
$act  = mysql_query($query_act , $pdar_connexion) or die(mysql_error());
$row_act  = mysql_fetch_assoc($act);
$totalRows_act  = mysql_num_rows($act);
?>
<?php $total = $nb = 0; if($totalRows_act>0) { $i=0;  do { $id_data = $row_act['LKEY'];
foreach($choix_array as $Col=>$Val)
{
  $somme[$Col]=$produit[$Col]=$moyenne[$Col]=$rapport[$Col]=$difference[$Col]=0;
  $tem[$Col]=0;
}

if($totalRows_entete>0 && $num>0){  do{  if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array) && in_array($row_act["LKEY"],$data_validate_array)){
if($row_entete["Field"]==$colonneH){ $nb++; $total += doubleval($row_act[$row_entete["Field"]]); }
} }while($row_entete  = mysql_fetch_assoc($entete));
$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}
} ?>
<?php $i++; } while ($row_act = mysql_fetch_assoc($act));
//resultat
if($modeCalcul=="SOMME") $colonne_array[$feuille][$colonneH]=$total;
elseif($modeCalcul=="MOYENNE") $colonne_array[$feuille][$colonneH]=$total/$nb;
elseif($modeCalcul=="COMPTER") $colonne_array[$feuille][$colonneH]=$nb;
}
} }while($row_liste_conf = mysql_fetch_assoc($liste_conf)); } ?>