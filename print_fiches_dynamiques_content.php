<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////

$cp=$feuille;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SHOW tables";
$liste_cp = mysql_query($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);

$entete_array = $libelle = array();

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = "SELECT * FROM $cp WHERE annee=$annee and ".$_SESSION["clp_where"];
$act  = mysql_query($query_act , $pdar_connexion) or die(mysql_error());
$row_act  = mysql_fetch_assoc($act);
$totalRows_act  = mysql_num_rows($act);

$tab = substr($cp,strlen($database_connect_prefix));

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$tab'";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete); 

if($totalRows_entete>0){ $choix_array = array(); $nomT=$row_entete["nom"]; $note=$row_entete["note"]; $entete_array=explode("|",$row_entete["show"]); $libelle=explode("|",$row_entete["libelle"]);
$intitule=$row_entete["intitule"]; $colonne=$row_entete["colonnes"]; $lignetotal=$row_entete["lignetotal"]; $colnum=$row_entete["colnum"];
if(!empty($row_entete["choix"])){ foreach(explode("|",$row_entete["choix"]) as $elem){ if(!empty($elem)){  $a=explode(";",$elem); $choix_array[$a[0]]=""; for($i=1;$i<count($a);$i++){ $choix_array[$a[0]].=(!empty($a[$i]))?$a[$i].";":""; } }   }  } }

$count = count($libelle)-2;
$count = explode("=",$libelle[$count]);
$lib_nom_fich = "";
if(isset($count[1]))
$lib_nom_fich = $count[1];
elseif(isset($count[0]))
$lib_nom_fich = $count[0];

if(empty($lib_nom_fich)) $lib_nom_fich = $cp;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "DESCRIBE $cp";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);
$num=0;
if($totalRows_entete>0){ do{ if(in_array($row_entete["Field"],$entete_array)) $num++; }while($row_entete  = mysql_fetch_assoc($entete));  }

$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}

$libelle_array = array();
foreach($libelle as $a) { $b = explode('=',$a); if(isset($b[0])) $libelle_array[$b[0]]=(isset($b[1]))?$b[1]:"ND"; }

if($num>0){
echo "<h3 style='padding:5px;margin-top:0px;background-color:#f9f9f9;'><u>$nomT</u> : $lib_nom_fich</h3>";
?>
<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="2" cellspadding="2" class="table table-striped table-bordered table-hover table-responsive">
<?php if(!empty($intitule) && !empty($colonne)){ ?>
<thead>
<tr role="row" bgcolor="#EBEBEB">
<?php
if($colnum==1){ ?>
<th rowspan="2" class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize" align="center">N&deg;</div></th>
<?php }
$colonnes = $intitules = $colo_show = array();
$col = explode('|',$colonne);
$intitules = explode('|',$intitule);
foreach($col as $a){ $b = explode(';',$a); foreach($b as $c) if(!in_array($c,$colonnes) && !empty($c)) array_push($colonnes,$c); }
if($totalRows_entete>0){ $i=$k=0; do{
/*if(isset($libelle[$k])){
$lib=explode("=",$libelle[$k]);
$libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";   } */
if(!in_array($row_entete["Field"],$colonnes) && $row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array)){  $colo_show[]=$row_entete["Field"]; ?>
<th rowspan="2" class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize" align="center"><?php echo (isset($libelle_array[$row_entete["Field"]]))?$libelle_array[$row_entete["Field"]]:str_replace("_"," ",$row_entete["Field"]); ?></div></th>
<?php }elseif($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array)){
if(isset($col[$i])){ $b = explode(';',$col[$i]); $colspan = count($b)-1; }
if($colspan>0){ ?>
<th colspan="<?php echo $colspan; ?>" class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize" align="center"><?php echo (isset($intitules[$i]))?$intitules[$i]:((isset($libelle_array[$row_entete["Field"]]))?$libelle_array[$row_entete["Field"]]:""); ?></div></th>
<?php } $i++; for($j=1;$j<$colspan;$j++) $row_entete = mysql_fetch_assoc($entete);  } $k++; }while($row_entete  = mysql_fetch_assoc($entete)); }
$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}  ?>
</tr>

<!--second ligne-->
<tr role="row" bgcolor="#EBEBEB">
<?php
if($totalRows_entete>0){ $i=0; do{

/*if(isset($libelle[$i])){
$lib=explode("=",$libelle[$i]);
$libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";   }*/
if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array)) $i++;
if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array) && !in_array($row_entete["Field"],$colo_show)){ ?>
<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize" align="center"><?php echo (isset($libelle_array[$row_entete["Field"]]))?$libelle_array[$row_entete["Field"]]:str_replace("_"," ",$row_entete["Field"]); ?></div></th>
<?php }  }while($row_entete  = mysql_fetch_assoc($entete)); }
$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}  ?>

</tr>
</thead>
<?php }else{ ?>
<thead>
<tr role="row" bgcolor="#EBEBEB">
<?php
if($colnum==1){ ?>
<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize" align="center">N&deg;</div></th>
<?php }
if($totalRows_entete>0){ $i=0; do{

/*if(isset($libelle[$i])){
$lib=explode("=",$libelle[$i]);
$libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";   } */

if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array)){ ?>
<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize" align="center"><?php echo (isset($libelle_array[$row_entete["Field"]]))?$libelle_array[$row_entete["Field"]]:str_replace("_"," ",$row_entete["Field"]); ?></div></th>
<?php $i++; }  }while($row_entete  = mysql_fetch_assoc($entete)); }
$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}  ?>

</tr></thead>
<?php } ?>

<?php
// si ligne total on crée les variables
if($lignetotal==1){ $Ltotal = array();
if($totalRows_entete>0){ do{  if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array) && $i>0){
 ?>
<?php $Ltotal[$row_entete["Field"]]=0;  ?>
<?php  }  }while($row_entete  = mysql_fetch_assoc($entete));
$rows = mysql_num_rows($entete);
  if($rows > 0) {
  mysql_data_seek($entete, 0);
  $row_entete = mysql_fetch_assoc($entete);
  }
    }
}   $i = isset($k)?$k:$i;
?>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="">
<?php if($totalRows_act>0) { $i=0;  do { $id_data = $row_act['LKEY'];
foreach($choix_array as $Col=>$Val)
{
  $somme[$Col]=$produit[$Col]=$moyenne[$Col]=$rapport[$Col]=$difference[$Col]=$compteur[$Col]=0;
  $tem[$Col]=0;
}
?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<?php if($colnum==1){ ?>
<td><div align="center"><?php echo $i+1; ?></div></td>
<?php }
if($totalRows_entete>0){  do{  if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array)){
if(strtolower($row_entete["Field"])=="village" && intval($row_act[$row_entete["Field"]])>0){ $village=$row_act[$row_entete["Field"]];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_region = "SELECT nom_village,nom_commune FROM ".$database_connect_prefix."commune, ".$database_connect_prefix."village WHERE commune=code_commune and code_village='$village'";
$region = mysql_query($query_region, $pdar_connexion) or die(mysql_error());
$row_region = mysql_fetch_assoc($region);
$totalRows_region = mysql_num_rows($region);
$lib_vill = $row_region["nom_commune"]." / ".$row_region["nom_village"];
mysql_free_result($region);
}
//gestion des formules calculées
if(strtolower($row_entete["Type"])!="varchar(1000)")
{ foreach($choix_array as $Col=>$Val){ $chx[$Col]=explode(";",$Val);
  if(in_array($row_entete["Field"],$chx[$Col])){
  $somme[$Col]+=doubleval($row_act[$row_entete["Field"]]);
  $difference[$Col]=($difference[$Col]==0)?doubleval($row_act[$row_entete["Field"]]):$difference[$Col]-doubleval($row_act[$row_entete["Field"]]);
  $produit[$Col]=($produit[$Col]==0 && $tem[$Col]==0)?doubleval($row_act[$row_entete["Field"]]):$produit[$Col]*doubleval($row_act[$row_entete["Field"]]);
  $rapport[$Col]=($rapport[$Col]==0 && $tem[$Col]==0)?doubleval($row_act[$row_entete["Field"]]):((doubleval($row_act[$row_entete["Field"]])>0)?$rapport[$Col]/doubleval($row_act[$row_entete["Field"]]):$rapport[$Col]);
  $moyenne[$Col]++; $tem[$Col]++;
  $compteur[$Col]++;
  }       }

}
 ?>
<td class=" "><?php if(strtolower($row_entete["Type"])=="date") echo date_reg($row_act[$row_entete["Field"]],"/");
elseif(strtolower($row_entete["Type"])=="varchar(1001)"){ echo $somme[$row_entete["Field"]]; if(isset($Ltotal[$row_entete["Field"]])) $Ltotal[$row_entete["Field"]]+=$somme[$row_entete["Field"]];}
elseif(strtolower($row_entete["Type"])=="varchar(1002)"){ echo $difference[$row_entete["Field"]]; if(isset($Ltotal[$row_entete["Field"]])) $Ltotal[$row_entete["Field"]]+=$difference[$row_entete["Field"]];}
elseif(strtolower($row_entete["Type"])=="varchar(1003)"){ echo $produit[$row_entete["Field"]]; if(isset($Ltotal[$row_entete["Field"]])) $Ltotal[$row_entete["Field"]]+=$produit[$row_entete["Field"]];}
elseif(strtolower($row_entete["Type"])=="varchar(1004)"){ echo number_format($rapport[$row_entete["Field"]], 2, '.', ' '); if(isset($Ltotal[$row_entete["Field"]])) $Ltotal[$row_entete["Field"]]+=$rapport[$row_entete["Field"]];}
elseif(strtolower($row_entete["Type"])=="varchar(1005)"){ echo ($moyenne[$row_entete["Field"]]>0)?number_format($somme[$row_entete["Field"]]/$moyenne[$row_entete["Field"]], 2, '.', ' '):"ND"; if(isset($Ltotal[$row_entete["Field"]])) $Ltotal[$row_entete["Field"]]+=$moyenne[$row_entete["Field"]]; }
elseif(strtolower($row_entete["Type"])=="varchar(1006)"){ echo $row_act[$row_entete["Field"]]; if(isset($Ltotal[$row_entete["Field"]])) $Ltotal[$row_entete["Field"]]++; }
elseif(strtolower($row_entete["Type"])=="varchar(1007)"){
if(!empty($row_act[$row_entete["Field"]]))
{
  $dir = './attachment/fiches_dynamiques/';
  $feuille_tmp = str_replace($database_connect_prefix,"",$cp);
  $a = explode("_details",$feuille_tmp);
  $sdir = $dir.$a[0];
  $sdir = $sdir."/details".$a[1];
  $sdir .= "/";
  $a = explode('|',$row_act[$row_entete["Field"]]);
  foreach($a as $b)
  if(!empty($b) && file_exists($sdir.$b))
  {
    echo "<a style='display:block;' href=\"$sdir$b\" target='_blank' title='T&eacute;l&eacute;charger' alt='$b'>$b</a><!--&nbsp;&nbsp;&nbsp;-->";
  }
}
  if(isset($Ltotal[$row_entete["Field"]])) $Ltotal[$row_entete["Field"]]++;
}
elseif(strtolower($row_entete["Type"])=="varchar(1008)"){ echo '<a href="javascript:void(0);" style="display: block; width:60px!important; heigth:60px!important; background-color:'.$row_act[$row_entete["Field"]].'!important">'.$row_act[$row_entete["Field"]].'</a>'; if(isset($Ltotal[$row_entete["Field"]])) $Ltotal[$row_entete["Field"]]++; }
else{ echo (strtolower($row_entete["Field"])=="village" && isset($row_region["nom_village"]) && isset($lib_vill))?$lib_vill:$row_act[$row_entete["Field"]]; if(isset($Ltotal[$row_entete["Field"]])) $Ltotal[$row_entete["Field"]]+=$row_act[$row_entete["Field"]]; }  unset($lib_vill); ?></td>
<?php } }while($row_entete  = mysql_fetch_assoc($entete));
$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}
} ?>

</tr>
<?php $i++; } while ($row_act = mysql_fetch_assoc($act));
//total
if($lignetotal==1){   ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" " align='center'><b>Total<?php echo ($colnum==1)?" (".(number_format($totalRows_act, 0, '', ' ')).")":""; ?></b></td>
<?php
if($totalRows_entete>0){ $i=0; do{  if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array) && $i>0){
 ?>
<td class=" "><?php
if(strtolower($row_entete["Type"])=="varchar(1004)" || strtolower($row_entete["Type"])=="varchar(1005)" || strtolower($row_entete["Type"])=="double")
echo (isset($Ltotal[$row_entete["Field"]]) && $Ltotal[$row_entete["Field"]]>0)?number_format($Ltotal[$row_entete["Field"]], 2, '.', ' '):"";
elseif(strtolower($row_entete["Type"])=="varchar(1001)" || strtolower($row_entete["Type"])=="varchar(1002)" || strtolower($row_entete["Type"])=="varchar(1003)" || strtolower($row_entete["Type"])=="varchar(1006)" || strtolower($row_entete["Type"])=="int(11)")
echo (isset($Ltotal[$row_entete["Field"]]) && $Ltotal[$row_entete["Field"]]>0)?number_format($Ltotal[$row_entete["Field"]], 0, '', ' '):"";
elseif(strtolower($row_entete["Type"])=="varchar(1008)" ||strtolower($row_entete["Type"])=="varchar(1007)" || strtolower($row_entete["Type"])=="varchar(1000)" || strtolower($row_entete["Type"])=="date" || strtolower($row_entete["Field"])=="village") echo "";
else
echo (isset($Ltotal[$row_entete["Field"]]) && $Ltotal[$row_entete["Field"]]>0)?$Ltotal[$row_entete["Field"]]:"";  ?></td>
<?php  }elseif($i==0 && $colnum!=1) $row_entete  = mysql_fetch_assoc($entete); $i++; }while($row_entete  = mysql_fetch_assoc($entete));
$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}
} ?>
</tr>
<?php }
 } else { $colspan = ($colnum==1)?$i+1:$i; echo "<td colspan='".($colspan+1)."' class='' align='center'><h3 align='center'>Aucune donn&eacute;es &agrave; afficher dans cette feuille !</h3></td>"; } ?>
</tbody></table><?php }else echo "<h3 align='center'>Aucune colonne &agrave; afficher dans la fiche ".$lib_nom_fich."!</h3>"; ?>
<?php echo (!empty($note))?"<div style='padding:5px;margin-top:0px;background-color:#f9f9f9;'>$note</div>":""; ?>