<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"]) || !isset($_GET['id']) || !isset($_GET['feuille'])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
header('Content-Type: text/html; charset=UTF-8');
$cmp = $_SESSION["clp_structure"];
if(isset($_GET['cmp']) && !empty($_GET['cmp'])) $cmp = $_GET['cmp'];
if(isset($_GET['annee']) && intval($_GET['annee'])>0) {$annee=intval($_GET['annee']);} else {$annee=date("Y");}
if(isset($_GET['id'])) {$id=$_GET['id'];}
if(isset($_GET['feuille'])) {$feuille=$_GET['feuille'];}
$cp=$feuille;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SHOW tables";
$liste_cp = mysql_query($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);

$cp_array=array();
if($totalRows_liste_cp>0) {
do {  if(strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="fiche_config"){  $cp_array[]=$row_liste_cp["Tables_in_$database_pdar_connexion"];
}
} while ($row_liste_cp = mysql_fetch_assoc($liste_cp));
$rows = mysql_num_rows($liste_cp);
if($rows > 0) {
mysql_data_seek($liste_cp, 0);
$row_liste_cp = mysql_fetch_assoc($liste_cp);
}}

//if(isset($cp) && !in_array($cp,$cp_array)) unset($cp);

$entete_array = $libelle = array();

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = "SELECT * FROM $cp WHERE annee=$annee and projet='".$_SESSION["clp_projet"]."' and structure like '$cmp' ";
$act  = mysql_query_ruche($query_act , $pdar_connexion) or die(mysql_error());
$row_act  = mysql_fetch_assoc($act);
$totalRows_act  = mysql_num_rows($act);

if($totalRows_act>0)
{
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_validation = "SELECT * FROM ".$database_connect_prefix."validation_fiche WHERE projet='".$_SESSION["clp_projet"]."' and nom_fiche='$cp'";
  $validation  = mysql_query_ruche($query_validation , $pdar_connexion) or die(mysql_error());
  $row_validation  = mysql_fetch_assoc($validation);
  $totalRows_validation  = mysql_num_rows($validation);
  $data_validate_array = array();
  if($totalRows_validation>0){ do{ $data_validate_array[] = $row_validation["id_lkey"]; }while($row_validation  = mysql_fetch_assoc($validation));  }
}

$tab = substr($cp,strlen($database_connect_prefix));

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$tab'";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);

if($totalRows_entete>0){ $choix_array = array(); $nomT=$row_entete["nom"]; $note=$row_entete["note"]; $entete_array=explode("|",$row_entete["show"]); $libelle=explode("|",$row_entete["libelle"]);
$intitule=$row_entete["intitule"]; $colonne=$row_entete["colonnes"]; $lignetotal=$row_entete["lignetotal"]; $colnum=$row_entete["colnum"]; $detail_sexe=$row_entete["detail_sexe"]; $detail_menage=$row_entete["detail_menage"];
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
unset($libelle_array);
foreach($libelle as $a) { $b = explode('=',$a); if(isset($b[0])) $libelle_array[$b[0]]=(isset($b[1]))?$b[1]:"ND"; }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_pdes = "SELECT * FROM ".$database_connect_prefix."pde ORDER BY code_pde";
$liste_pdes = mysql_query_ruche($query_liste_pdes, $pdar_connexion) or die(mysql_error());
$row_liste_pdes = mysql_fetch_assoc($liste_pdes);
$totalRows_liste_pdes = mysql_num_rows($liste_pdes);
$PDE=array();
if($totalRows_liste_pdes>0){
  do{ $PDE[$row_liste_pdes["id_pde"]]=$row_liste_pdes["nom_pde"]; }while($row_liste_pdes = mysql_fetch_assoc($liste_pdes));
}

?>

<!-- Site contenu ici -->
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; width: 100%!important;
} .table tbody tr td {vertical-align: middle; }
</style>
<script>
$('#myModal_add').remove();
$().ready(function() {  /*
//$('a[data-toggle="modal"]').modal();
var oTable = $('#mtable<?php echo $feuille; ?>').dataTable( {
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ -1 ] }
        ],
        sDom:"<'row'<'dataTables_header clearfix'<'col-md-7'lT><'col-md-5'Cf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
        oTableTools:{aButtons:["copy","print","csv","xls",{"sExtends": "pdf","sPdfOrientation": "landscape"}],sSwfPath:"./swf/copy_csv_xls_pdf.swf"},
        "aaSorting": [],
        //"aLengthMenu":[[25, 50, 100, 200, -1],[25, 50, 100, 200, "TOUS1"]],
        "iDisplayLength": -1,
        paging: false
    });  */
    //$(".select2").select2();
});
</script>
<?php
  if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2){
  echo do_link("","","Edition de la feuille","<i class=\"icon-plus\"> Edition de la feuille </i>","","./","pull-left p11","get_content('new_feuille.php','classeur=$id&id=$tab&annee=$annee','modal-body_add',this.title);",1,"margin-top:-5px;",$nfile);
  echo do_link("","","Gestion des en-t&ecirc;tes de la feuille","<i class=\"icon-plus\"> Gestion des en-t&ecirc;tes </i>","","./","pull-left p11","get_content('gestion_feuille.php','classeur=$id&feuille=$tab&annee=$annee','modal-body_add',this.title,'iframe');",1,"margin-top:-5px;",$nfile);

  echo do_link("","./fiches_dynamiques.php?annee=$annee&id=$id&feuille=$tab","Fermer le classeur"," Voir les donn&eacute;es des autres UG ","","./","pull-right p11","",0,"",$nfile);
 echo do_link("","","Edition de contenu","<i class=\"icon-plus\"> Nouvelle donn&eacute;e </i>","","../","pull-right p11","get_content('modal_content/new_fiche_data.php','classeur=$id&feuille=$tab&annee=$annee','modal-body_add',this.title);",1,"margin-top:-5px;",$nfile);
 
  
  }
  echo do_link("","./export_fiches_dynamiques.php?id=fiche_dynamique&classeur=$id&feuille=$tab&annee=$annee&cmp=$cmp&nom=$nomT","Exportertation sous format excel","<i class=\"icon-plus\"> Exporter </i>","","../","pull-right p11","",0,"margin-top:-5px;",$nfile);
  if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2){
  echo do_link("","","Importation d&eacute;puis un format excel","<i class=\"icon-plus\"> Importer </i>","","./","pull-right p11","get_content('import_fiches_dynamiques.php','id=fiche_dynamique&classeur=$id&feuille=$tab&annee=$annee','modal-body_add',this.title);",1,"margin-top:-5px;",$nfile);
  }
?>
<div class="clear h0">&nbsp;</div>

<?php if($num>0){
echo "<h3 style='padding:5px;margin-top:0px;background-color:#f9f9f9;'><u>$lib_nom_fich</u> </h3>";
?>
<form name="form<?php echo $feuille; ?>" action="<?php echo "./fiches_dynamiques.php?validation=ok&id=$id&annee=$annee"; ?>" method="post">
<table class="table table-striped table-bordered table-hover table-checkable table-tabletools table-responsive datatable dataTable" id="mtable<?php echo $feuille; ?>" >
<?php if(!empty($intitule) && !empty($colonne)){ ?>
<thead>
<tr role="row">
<th class="checkbox-column" rowspan="2" > <input type="checkbox" class="uniform" onclick="check_all('table-checkable',this);"> </th>
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

<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<4) {?>
<th class="" rowspan="2" role="" tabindex="0" aria-controls="" aria-label="" width="80" align="center">Actions</th>
<?php } ?>
</tr>

<!--second ligne-->
<tr role="row">
<?php
if($totalRows_entete>0){ $i=0; do{

/*if(isset($libelle[$i])){
$lib=explode("=",$libelle[$i]);
$libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";   } */
//if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array)) $i++;
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
<tr role="row">
<th class="checkbox-column" > <input type="checkbox" class="uniform" onclick="check_all('table-checkable',this);"> </th>
<?php
if($colnum==1){ ?>
<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize" align="center">N&deg;</div></th>
<?php }
if($totalRows_entete>0){ $i=0; do{

if(isset($libelle[$i])){
$lib=explode("=",$libelle[$i]);
$libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";   }

if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array)){ ?>
<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize" align="center"><?php echo (isset($libelle_array[$row_entete["Field"]]))?$libelle_array[$row_entete["Field"]]:str_replace("_"," ",$row_entete["Field"]); ?></div></th>
<?php $i++; }  }while($row_entete  = mysql_fetch_assoc($entete)); }
$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}  ?>

<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="80" align="center">Actions</th>
<?php }?>
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
}  $i = isset($k)?$k:$i;
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
<td class="checkbox-column"><?php if(!in_array($id_data,$data_validate_array)){ ?> <input type="checkbox" name="id_val[]" value="<?php echo $id_data; ?>" class="uniform"> <?php } ?></td>
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
if($totalRows_region>0){ $lib_vill = $row_region["nom_commune"]." / ".$row_region["nom_village"]; }
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
<td class=" "><?php
if(strtolower($row_entete["Field"])=="pde"){ echo (isset($PDE[$row_act[$row_entete["Field"]]]))?$PDE[$row_act[$row_entete["Field"]]]:$row_act[$row_entete["Field"]]; }
elseif(strtolower($row_entete["Type"])=="date") echo date_reg($row_act[$row_entete["Field"]],"/");
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
else{ echo (strtolower($row_entete["Field"])=="village" && isset($row_region["nom_village"]) && isset($lib_vill))?$lib_vill:(((strtolower($row_entete["Field"])=="village" && !isset($lib_vill)))?"<span style='color: #FF9900 '>".$row_act[$row_entete["Field"]]."</span>":$row_act[$row_entete["Field"]]); if(isset($Ltotal[$row_entete["Field"]])) $Ltotal[$row_entete["Field"]]+=$row_act[$row_entete["Field"]]; }  unset($lib_vill); ?></td>
<?php } }while($row_entete  = mysql_fetch_assoc($entete));
$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}
} ?>

<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
<td class=" " align="center">
<?php
echo do_link("","","Modifier","","edit","./","","get_content('modal_content/new_fiche_data.php','id=$id_data&classeur=$id&annee=$annee&feuille=".substr($feuille,strlen($database_connect_prefix))."','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("","./fiches_dynamiques.php?id_sup=$id_data&classeur=$id&annee=$annee&feuille=".substr($feuille,strlen($database_connect_prefix)),"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette ligne ?');",0,"margin:0px 5px;",$nfile);
?>
<!--<a onclick="get_content('modal_content/new_fiche_data.php','<?php echo "id=".$id_data."&classeur=".$id."&annee=".$annee."&feuille=".substr($feuille,strlen($database_connect_prefix)); ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="<?php echo $nomT; ?>" class="thickbox Add"  dir="" style=""><img src="images/edit.png" width='20' height='20' alt='Modifier' /></a>
<a href="./fiches_dynamiques.php<?php echo "?id_sup=".$id_data."&classeur=".$id."&annee=".$annee."&feuille=".substr($feuille,strlen($database_connect_prefix)); ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?');" style="margin:0px 0px 0px 5px;" ><img src="./images/delete.png" width="20" height="20" alt="Supprimer" title="Supprimer"></a>-->
</td>
<?php }?>
</tr>
<?php $i++; } while ($row_act = mysql_fetch_assoc($act));
//total
if($lignetotal==1){     ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" " align='center'><b>Total<?php echo ($colnum==1)?" (".($totalRows_act).")":""; ?></b></td>
<?php if($colnum==1){ ?>
<td class=" " align='center'><b><?php echo number_format($totalRows_act, 0, '', ' '); ?></b></td>
<?php }else{ ?>
<td class=" " align='center'><b><?php //echo number_format($totalRows_act, 0, '', ' '); ?></b></td>
<?php  }
if($totalRows_entete>0){ $i=0; do{  if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array) && $i>0){
 ?>
<td class=" "><?php
if(strtolower($row_entete["Field"])=="longitude" || strtolower($row_entete["Field"])=="latitude" || strtolower($row_entete["Field"])=="shp")
echo "";
elseif(strtolower($row_entete["Type"])=="varchar(1004)" || strtolower($row_entete["Type"])=="varchar(1005)" || strtolower($row_entete["Type"])=="double")
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
<td class=" ">&nbsp;</td>
</tr>
<?php }
 } else { $colspan = ($colnum==1)?$i+1:$i; $colspan += (isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1)?1:0; echo "<td colspan='".($colspan+1)."' class='' align='center'><h3 align='center'>Aucune donn&eacute;es &agrave; afficher dans cette feuille !</h3></td>"; } ?>
</tbody></table>
<?php if($totalRows_act>0) { ?>
<div class="row"> <div class="table-footer"> <div class="col-md-6"> <div class="table-actions"> <label>Pour la s&eacute;lection :</label> <select onchange="if(this.value!=''){if(confirm('Vous confirmez la validation des donn&eacute;es de la s&eacute;lection ?')) form<?php echo $feuille; ?>.submit();}" class="select2" data-minimum-results-for-search="-1" data-placeholder="S&eacute;lection..."> <option></option> <option value="Validate">Valider</option>  </select> </div> </div></div> </div>
<input type="hidden" name="classeur" value="<?php echo $id; ?>">
<input type="hidden" name="feuille" value="<?php echo $feuille; ?>">
<?php } ?>
</form>
<?php }else echo "<h3 align='center'>Aucune colonne &agrave; afficher dans la fiche ".$lib_nom_fich."!</h3>"; ?>
<?php echo (!empty($note))?"<div style='padding:5px;margin-top:0px;background-color:#f9f9f9;'>$note</div>":""; ?>

<?php  /*
if($detail_menage==1 && in_array("homme",$entete_array) && in_array("femme",$entete_array) && in_array("jhomme",$entete_array) && in_array("jfemme",$entete_array))
{
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  if(in_array("nbrmenage",$entete_array))
  $query_nbr = "SELECT (sum(homme)+sum(femme)) as total, sum(nbrmenage) as nbr_menage, sum(homme) as homme, sum(jhomme) as jeune_homme, sum(femme) as femme, sum(jfemme) as jeune_femme FROM $cp WHERE annee=$annee and ".$_SESSION["clp_where"]." ";
  else
  $query_nbr = "SELECT (sum(homme)+sum(femme)) as total, count(LKEY) as nbr_menage, sum(homme) as homme, sum(jhomme) as jeune_homme, sum(femme) as femme, sum(jfemme) as jeune_femme FROM $cp WHERE annee=$annee and ".$_SESSION["clp_where"]." ";
  $nbr  = mysql_query($query_nbr , $pdar_connexion) or die(mysql_error());
  $row_nbr  = mysql_fetch_assoc($nbr);
  $totalRows_nbr  = mysql_num_rows($nbr);
}
elseif($detail_sexe==1 && in_array("datenaissance",$entete_array) && in_array("sexe",$entete_array))
{
  $jeune = (date('Y')-24).date("-01-01"); $adulte = (date('Y')-15).date("-12-31");
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_nbr = "SELECT count(sexe) as total, (SELECT count(sexe) FROM $cp WHERE annee=$annee and ".$_SESSION["clp_where"]." and sexe='M') as homme, (SELECT count(sexe) FROM $cp WHERE annee=$annee and ".$_SESSION["clp_where"]." and sexe='M' and datenaissance between '$jeune' and '$adulte') as jeune_homme, (SELECT count(sexe) FROM $cp WHERE annee=$annee and ".$_SESSION["clp_where"]." and sexe='F') as femme, (SELECT count(sexe) FROM $cp WHERE annee=$annee and ".$_SESSION["clp_where"]." and sexe='F' and datenaissance between '$jeune' and '$adulte') as jeune_femme FROM $cp ";
  $nbr  = mysql_query($query_nbr , $pdar_connexion) or die(mysql_error());
  $row_nbr  = mysql_fetch_assoc($nbr);
  $totalRows_nbr  = mysql_num_rows($nbr);
}    */
if(isset($totalRows_nbr) && $totalRows_nbr>0){
?>
<!--<table width="50%" border="0" align="center" cellspacing="1" class="table table-striped table-bordered table-responsive" style="width: 50%!important">
  <thead>
    <tr>
      <?php if($detail_menage==1){ ?>
      <th rowspan="2"><center>M&eacute;nage</center></th>
      <?php } ?>
      <th colspan="2"><center>Homme</center></th>
      <th colspan="2"><center>Femme</center></th>
      <th colspan="2"><center>Total</center></th>
    </tr>
    <tr>
      <th><center>Jeune<br>(15-24 ans)</center></th>
      <th><center>Total</center></th>
      <th><center>Jeune<br>(15-24 ans)</center></th>
      <th><center>Total</center></th>
      <th><center>Jeune<br>(15-24 ans)</center></th>
      <th><center>Total</center></th>
    </tr>
  </thead>
  <tr>
    <?php if($detail_menage==1){ ?>
    <td align="right"><?php echo number_format($row_nbr["nbr_menage"], 0, '', ' '); ?></td>
    <?php } ?>
    <td align="right"><?php echo number_format($row_nbr["jeune_homme"], 0, '', ' '); ?></td>
    <td align="right"><?php echo number_format($row_nbr["homme"], 0, '', ' '); ?></td>
    <td align="right"><?php echo number_format($row_nbr["jeune_femme"], 0, '', ' '); ?></td>
    <td align="right"><?php echo number_format($row_nbr["femme"], 0, '', ' '); ?></td>
    <td align="right"><?php echo number_format($row_nbr["jeune_homme"]+$row_nbr["jeune_femme"], 0, '', ' '); ?></td>
    <td align="right"><?php echo number_format($row_nbr["total"], 0, '', ' '); ?></td>
  </tr>
</table>-->
<?php } ?>

<?php include 'modal_add.php'; ?>