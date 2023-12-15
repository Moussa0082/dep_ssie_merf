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
//header('Content-Type: text/html; charset=ISO-8859-15');

$plog=$_SESSION["clp_id"];
$date=date("Y-m-d");
if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else $annee=date("Y");
if(isset($_GET['version'])) {$version=intval($_GET['version']);} else $version=1;
if(isset($_GET['actc'])) {$actc=$_GET['actc'];} else $actc=0;
/*
if(isset($_GET['cmp'])) {$scp=$_GET['cmp'];} else $scp=0;
$cmp = 0;
if(isset($_GET['cmp']) && intval($_GET['cmp'])>0) $cmp = intval($_GET['cmp']);*/
if(isset($_GET['cmp']) && !empty($_GET['cmp'])) $cmp = $_GET['cmp']; else $cmp="";
// Verrou PTBA
if(isset($_GET["verou"])) { //$id=$_GET["id_sup_tache"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_verrou_ptba = "UPDATE annee SET verrou='oui' WHERE annee='$annee'";
$Result1 = mysql_query_ruche($query_verrou_ptba, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok&annee=$annee"; else $insertGoTo .= "?insert=no&annee=$annee";
  mysql_free_result($Result1);
  header(sprintf("Location: %s", $insertGoTo));
}
  $query_entete = "SELECT libelle,code_number, nombre FROM niveau_config WHERE projet='".$_SESSION["clp_projet"]."' LIMIT 1"; 
  try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $niveau = $row_entete["nombre"];
  $code_len = explode(',',$row_entete["code_number"]);
  $libelle=explode(",",$row_entete["libelle"]);
//echo  $query_entete;
$query_act = "SELECT ptba.*, 1 as tr FROM ptba where ptba.annee ='$annee' and projet='".$_SESSION["clp_projet"]."'";
if(isset($_GET['cmp']) && !empty($_GET['cmp'])) $query_act .= " and left(code_activite_ptba,'".$code_len[1]."') LIKE '%$cmp%'";
$query_act .= " order by  tr, code_activite_ptba asc";
try{
    $act = $pdar_connexion->prepare($query_act);
    $act->execute();
    $row_act = $act ->fetchAll();
    $totalRows_act = $act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//echo $query_act; echo "<br>"; echo $_GET['cmp'];


$query_liste_cout_saisi = "SELECT activite, SUM( if(montant>0, montant,0) ) AS montant  FROM part_bailleur where  annee=$annee and projet='".$_SESSION["clp_projet"]."'  group by activite";
	try{
    $liste_cout_saisi = $pdar_connexion->prepare($query_liste_cout_saisi);
    $liste_cout_saisi->execute();
    $row_liste_cout_saisi = $liste_cout_saisi ->fetchAll();
    $totalRows_liste_cout_saisi = $liste_cout_saisi->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableauCoutSaisi = array();
 if($totalRows_liste_cout_saisi>0) { foreach($row_liste_cout_saisi as $row_liste_cout_saisi){  
$tableauCoutSaisi[$row_liste_cout_saisi["activite"]] = $row_liste_cout_saisi["montant"];
 } }
	$query_liste_cout = "SELECT code_activite_ptba, SUM( if(cout_prevu>0, cout_prevu,0) ) AS cout FROM code_analytique WHERE  code_categorie<>code_activite_ptba and annee=$annee group by `code_activite_ptba` order by code_activite_ptba";
		try{
    $liste_cout = $pdar_connexion->prepare($query_liste_cout);
    $liste_cout->execute();
    $row_liste_cout = $liste_cout ->fetchAll();
    $totalRows_liste_cout = $liste_cout->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$cout_array = array();
 if($totalRows_liste_cout>0) { foreach($row_liste_cout as $row_liste_cout){  
$cout_array[$row_liste_cout["code_activite_ptba"]] = $row_liste_cout["cout"];
 } }



 /* mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "SELECT libelle,code_number FROM niveau_config WHERE ".$_SESSION["clp_where"]." LIMIT 1";
  $entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);
  $code_len = explode(',',$row_entete["code_number"]);
  $libelle=explode(",",$row_entete["libelle"]);

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_activite_1 = "SELECT code,intitule FROM activite_projet WHERE niveau=$cmp+1 and ".$_SESSION["clp_where"]." ";
  $liste_activite_1  = mysql_query_ruche($query_liste_activite_1 , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_activite_1  = mysql_fetch_assoc($liste_activite_1 );
  $totalRows_liste_activite_1  = mysql_num_rows($liste_activite_1 );
  $cmp_array = array();
  if($totalRows_liste_activite_1>0){  do{
    $cmp_array[$row_liste_activite_1["code"]] = $row_liste_activite_1["intitule"];
  }while($row_liste_activite_1 = mysql_fetch_assoc($liste_activite_1));  }*/


 /*if($totalRows_entete>0){
  $where = "niveau=$niveau";
  $query_liste_activite_pa = "SELECT code, intitule FROM activite_projet WHERE $where and projet='".$_SESSION["clp_projet"]."'"; 
  		try{
    $liste_activite_pa = $pdar_connexion->prepare($query_liste_activite_pa);
    $liste_activite_pa->execute();
    $row_liste_activite_pa = $liste_activite_pa ->fetchAll();
    $totalRows_liste_activite_pa = $liste_activite_pa->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$libelle_analytique_array = array();
 if($totalRows_liste_activite_pa>0) { foreach($row_liste_activite_pa as $row_liste_activite_pa){  
$libelle_analytique_array[$row_liste_activite_pa["code"]] = $row_liste_activite_pa["intitule"];
 } }
}
 $query_enteteb = "SELECT libelle,code_number, nombre FROM niveau_budget_config WHERE projet='".$_SESSION["clp_projet"]."' LIMIT 1";
    		try{
    $enteteb = $pdar_connexion->prepare($query_enteteb);
    $enteteb->execute();
    $row_enteteb = $enteteb ->fetch();
    $totalRows_enteteb = $enteteb->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $niveaub = $row_enteteb["nombre"];
  $code_lenb = explode(',',$row_enteteb["code_number"]);
  $libelleb=explode(",",$row_enteteb["libelle"]);
  
  
if($totalRows_enteteb>0){
  $whereb = "niveau=$niveaub";
  $query_liste_activite_pab = "SELECT code, intitule FROM plan_budgetaire WHERE $whereb and projet='".$_SESSION["clp_projet"]."'"; 
  						try{
    $liste_activite_pab = $pdar_connexion->prepare($query_liste_activite_pab);
    $liste_activite_pab->execute();
    $row_liste_activite_pab = $liste_activite_pab ->fetchAll();
    $totalRows_liste_activite_pab = $liste_activite_pab->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$libelle_analytique_arrayb = array();
 if($totalRows_liste_activite_pab>0) { foreach($row_liste_activite_pab as $row_liste_activite_pab){  
$libelle_analytique_arrayb[$row_liste_activite_pab["code"]] = $row_liste_activite_pab["intitule"];
 } }
}*/


//$tableauMois= array('T1','T2','T3','T4');
//$tableauMois= array('J','F','M','A','M','J','J','A','S','O','N','D');
//$tableauMois=array('01<>Jan<>J','02<>Fev<>F','03<>Mars<>M','04<>Avril<>A','05<>Mai<>M','06<>Juin<>J','07<>Juil<>J','08<>Aout<>A','09<>Sep<>S','10<>Oct<>O','11<>Nov<>N','12<>DEC<>D');
$tableauMois=array('01<>Jan<>J','02<>Fev<>F','03<>Mar<>M','04<>Avr<>A','05<>Mai<>M','06<>Juin<>J','07<>Juil<>J','08<>Aout<>A','09<>Sep<>S','10<>Oct<>O','11<>Nov<>N','12<>Dec<>D');

                                      //where structure='".$_SESSION["clp_structure"]."'
$query_liste_prestataire = "SELECT * FROM acteur order by code_acteur ";
   try{
    $liste_prestataire = $pdar_connexion->prepare($query_liste_prestataire);
    $liste_prestataire->execute();
    $row_liste_prestataire = $liste_prestataire ->fetchAll();
    $totalRows_liste_prestataire = $liste_prestataire->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$acteur_array = array(); 
 if($totalRows_liste_prestataire>0) { foreach($row_liste_prestataire as $row_liste_prestataire){  
    $acteur_array[] = $row_liste_prestataire["id_acteur"]."!!".$row_liste_prestataire["nom_acteur"];
 } }
 
/*
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$query_liste_ntache = "SELECT count(id_groupe_tache) as nbt, max(proportion) as propor, id_activite  FROM groupe_tache, ptba WHERE  id_groupe_tache in (select tache from tache_ugl) and id_ptba=id_activite and ptba.annee=$annee and ptba.projet='".$_SESSION["clp_projet"]."' group by `id_activite`";
	$liste_ntache = mysql_query_ruche($query_liste_ntache, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
	$row_liste_ntache = mysql_fetch_assoc($liste_ntache);
	$totalRows_liste_ntache = mysql_num_rows($liste_ntache);
	$ntache_array = array();
	$proportiontache_array = array();
    if($totalRows_liste_ntache>0){
	 do{ 
	 $ntache_array[$row_liste_ntache["id_activite"]]=$row_liste_ntache["nbt"];
	  $proportiontache_array[$row_liste_ntache["id_activite"]]=$row_liste_ntache["propor"];
	  }
	while($row_liste_ntache  = mysql_fetch_assoc($liste_ntache));}*/
	
	$query_liste_nindicateur = "SELECT count(id_indicateur_tache) as nbt, id_activite  FROM indicateur_tache, ptba WHERE  id_ptba=id_activite and ptba.annee=$annee and ptba.projet='".$_SESSION["clp_projet"]."' group by `id_activite`";
	   try{
    $liste_nindicateur = $pdar_connexion->prepare($query_liste_nindicateur);
    $liste_nindicateur->execute();
    $row_liste_nindicateur = $liste_nindicateur ->fetchAll();
    $totalRows_liste_nindicateur = $liste_nindicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$nindicateur_array = array(); 
 if($totalRows_liste_nindicateur>0) { foreach($row_liste_nindicateur as $row_liste_nindicateur){  
	 $nindicateur_array[$row_liste_nindicateur["id_activite"]]=$row_liste_nindicateur["nbt"]; 
 } }
	
//gestion revision
  $query_liste_mission = "SELECT * FROM ".$database_connect_prefix."version_ptba where id_version_ptba='$annee'";  
  	   try{
    $liste_mission = $pdar_connexion->prepare($query_liste_mission);
    $liste_mission->execute();
    $row_liste_mission = $liste_mission ->fetch();
    $totalRows_liste_mission = $liste_mission->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $an_version=$row_liste_mission['annee_ptba'];
  
 // echo $row_liste_mission['statut_version'];  exit; // echo $query_liste_mission;
  
  $query_liste_activiter  = "SELECT * FROM ".$database_connect_prefix."version_ptba WHERE  annee_ptba='$an_version' and id_version_ptba!='$annee'  ";
	   try{
    $liste_activiter = $pdar_connexion->prepare($query_liste_activiter);
    $liste_activiter->execute();
    $row_liste_activiter = $liste_activiter ->fetch();
    $totalRows_liste_activiter = $liste_activiter->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
 if($totalRows_liste_activiter>0  && $row_liste_activiter['version_ptba']!="Initiale") { $vautre=$row_liste_activiter['id_version_ptba']; $actr="add"; $textactr="R&eacute;vision";}
 
  

$query_nb_tache_act = "select sum(proportion) as nbtache, id_activite FROM groupe_tache GROUP BY id_activite ASC";
   try{
  $nb_tache_act = $pdar_connexion->prepare($query_nb_tache_act);
    $nb_tache_act->execute();
    $row_nb_tache_act = $nb_tache_act ->fetchAll();
    $totalRows_nb_tache_act = $nb_tache_act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$nb_tache_act_array = array(); 
 if($totalRows_nb_tache_act>0) { foreach($row_nb_tache_act as $row_nb_tache_act){  
	  $nb_tache_act_array[$row_nb_tache_act["id_activite"]]=$row_nb_tache_act["nbtache"]; 
 } }
	
	
$query_liste_activite_2 = "SELECT code,intitule FROM activite_projet WHERE niveau=2 and projet='".$_SESSION["clp_projet"]."'  order by code";
   try{
  $liste_activite_2 = $pdar_connexion->prepare($query_liste_activite_2);
    $liste_activite_2->execute();
    $row_liste_activite_2 = $liste_activite_2 ->fetchAll();
    $totalRows_liste_activite_2 = $liste_activite_2->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$cmp_val = array(); 
 if($totalRows_liste_activite_2>0) { foreach($row_liste_activite_2 as $row_liste_activite_21){  
$cmp_val[$row_liste_activite_21["code"]] = $row_liste_activite_21["intitule"];
 } }
 
?>
<!-- Site contenu ici -->               
<style>
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; width: 100%!important;
} .table tbody tr td {vertical-align: middle; }
.Style1 {font-size: 12px}
.Style2 {font-size: 11px}
</style>
<script>
$().ready(function() {
//$('a[data-toggle="modal"]').modal();
var oTable = $('#mtable<?php echo $annee; ?>').dataTable( {
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ -1 ] }
        ],
        "aaSorting": [],
		 //"iDisplayLength": -1,
        paging: false
    });
});
</script>

<form name="form<?php echo $annee; ?>" id="form<?php echo $annee; ?>" method="get" action="<?php echo "plan_ptba.php?version=".$version; ?>" class="pull-left"> <?php echo ((isset($libelle[1]) && !empty($libelle[1])))?$libelle[1]:"S/Composantes" ?> 
<select name="cmp" onchange="form<?php echo $annee; ?>.submit();" style="background-color: #FFFF00; padding: 7px; " class="btn p11">
  <option value="">-- Selectionnez une Sous-composante--</option>
  <?php if($totalRows_liste_activite_2>0){  foreach($row_liste_activite_2 as $row_liste_activite_2){ ?>
<option value="<?php echo $row_liste_activite_2["code"]; ?>" <?php if(isset($_GET["cmp"]) && $row_liste_activite_2["code"]==$_GET["cmp"]) echo "selected='SELECTED'"; ?>><?php echo $row_liste_activite_2["code"].": ".substr($row_liste_activite_2['intitule'],0, 70)."..."; ?></option>
  <?php } } ?>
 <option value="%">-- Toutes --</option>
</select>
<input type="hidden" name="annee" value="<?php echo $annee; ?>" />
</form>
<?php  if(isset($_SESSION["clp_niveau"]) && $_SESSION["clp_niveau"]==0 && isset($row_liste_mission['statut_version']) && $row_liste_mission['statut_version']==0) { ?>
<?php
$verspta=$an_version." ".$row_liste_mission['version_ptba'];
  echo do_link("","","Ajout d'Activit&eacute; au PTBA '$verspta'","<i class=\"icon-plus\"> Nouvelle activit&eacute; </i>","simple","../","btn btn-sm btn-warning pull-right p11","get_content('modal_content/new_activite_ptba.php','annee=$annee','modal-body_add',this.title);",1,"","plan_ptba.php");
?>
<?php } ?>
<a onclick="get_content('modal_content/graphique_ptba_cp.php','<?php echo "&annee=".$annee; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false"  title="Graphique par <?php echo ((isset($libelle[0]) && !empty($libelle[0])))?$libelle[0]."s":"Composantes" ?>" class="thickbox Add pull-right p11"  dir=""><img src="images/b_chart.png" width="16" height="16" /><?php echo ((isset($libelle[0]) && !empty($libelle[0])))?$libelle[0]:"Composantes" ?></a>
<a onclick="get_content('modal_content/graphique_ptba_convention.php','<?php echo "&annee=".$annee; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Graphique par Conventions" class="thickbox Add pull-right p11"  dir=""><img src="images/b_chart.png" width="16" height="16" />Bailleurs </a>
<?php if(isset($_SESSION['clp_id']) &&  $_SESSION["clp_id"] == "admin"){ ?>
<?php
//if(isset($actr) && isset($totalRows_act) && $totalRows_act>0) 
  echo do_link("","","R&eacute;vision du PTBA '$annee'","R&eacute;vision","simple","../","btn btn-sm pull-left p3","get_content('modal_content/revision_ptba.php','annee=$annee&actr=$actr&autrever=$vautre','modal-body_add',this.title);",1,"","plan_ptba.php");
}
?>
<div class="clear">&nbsp;</div>

<table  class="table table-striped table-bordered table-hover table-responsive  table-colvis datatable dataTable" id="mtable<?php echo $annee; ?>"  aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">

<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">
  Code </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Activit&eacute;s PTBA</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><div align="center">Responsable</div></th>
<?php foreach($tableauMois as $vmois){  
$amois = explode('<>',$vmois);
$imois = $amois[2];
?>
<th class="" role="" tabindex="0" aria-controls="DataTables_Table_0" aria-label=""><?php echo $imois; ?> </th>
<?php } ?>
<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Acteurs</th>  -->
<th align="center" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><div align="center">T&acirc;ches</div></th>
<th align="center" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><div align="center">Indicateurs</div></th>
<th align="center" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><div align="center">Co&ucirc;ts</div></th>
<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Co&ucirc;t (FCFA)</th> -->
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==0) && isset($row_liste_mission['statut_version']) && $row_liste_mission['statut_version']==0) {?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="">Actions</th>
<?php } ?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="">
              <?php if($totalRows_act>0) { $i=0; foreach($row_act as $row_act){   
   if(isset($row_act['isous_composante']) && $row_act['isous_composante']=="0") $lientache="plan_taches_sans_type.php"; else $lientache="plan_taches.php"; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" ">&nbsp;<a onclick="#','','',,'');" title="<?php //echo (isset($libelle_analytique_array[$row_act["code_activite_ptba"]]))?$libelle_analytique_array[$row_act["code_activite_ptba"]]:"";?>" class=""  dir=""><?php echo "<strong>".$row_act['code_activite_ptba']."</strong> "; ?></a></td>


<td class=" " <?php if($row_act['id_ptba']==$actc) {?> style="background-color:#FFFF33" <?php } ?>><?php echo $row_act['intitule_activite_ptba']; ?></td>
<td class=" ">
 <?php 
  if(isset($row_act['responsable']) && $row_act['responsable']!=" ") echo $row_act['responsable']."- "; 

 $actc = explode(",", $row_act['acteur_conserne']);
 
 
 foreach($acteur_array as $vacteur){ 
 
 $aacteur = explode('!!',$vacteur);
$iacteur = $aacteur[0];
  ?>
<?php echo (in_array($iacteur, $actc, TRUE))?$aacteur[1]."/ ":''; ?>
 <?php }
 
 $a = explode(",", $row_act['debut']);
  ?></td>
<?php $i=1; foreach($tableauMois as $vmois){
$amois = explode('<>',$vmois);
$imois = $amois[1];

?>
<td class=" " style="padding: 0; background-color:<?php if(in_array($imois, $a, TRUE)) echo "#CCCCCC"; ?>"><a style="display: block; background-color:<?php if(in_array($imois, $a, TRUE)) echo "#CCCCCC"; ?> "><b class="hidden"><?php echo (in_array($imois, $a, TRUE))?1:''; ?></b>&nbsp;</a></td>
  <?php $i++;}
  
  if(isset($tableauCoutSaisi[$row_act["id_ptba"]]))  $cout_saisi=$tableauCoutSaisi[$row_act["id_ptba"]]; else $cout_saisi="";
$acco = explode(",", $row_act['region']);
    //if(isset($cout_array[$row_act["code_activite_ptba"]]))  $cout_importe=$cout_array[$row_act["code_activite_ptba"]]; else $cout_importe="";  
   ?>
<td class=" " align="center" style="width:10px">
<a onclick="get_content('./<?php echo $lientache; ?>','<?php echo "cat=".$row_act['isous_composante']."&id_act=".$row_act['id_ptba']."&code_act=".$row_act['code_activite_ptba']."&annee=".$annee."#os"; ?>','modal-body_add','<?php echo $row_act['code_activite_ptba'].": ".str_replace("'","\'",$row_act['intitule_activite_ptba']); ?>','iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false"  title="" class="thickbox" dir="">T&acirc;ches <br /><?php if(isset($nb_tache_act_array[$row_act["id_ptba"]])) echo "(".$nb_tache_act_array[$row_act["id_ptba"]]." %)";?></a></td>
<td class=" " align="center"><a onclick="get_content('./plan_indicateurs.php','<?php echo "id_act=".$row_act['id_ptba']."&code_act=".$row_act['code_activite_ptba']."&annee=".$annee."#os"; ?>','modal-body_add','<?php echo $row_act['code_activite_ptba'].": ".str_replace("'","\'",$row_act['intitule_activite_ptba']); ?>','iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="" class="thickbox" dir=""><?php if(isset($nindicateur_array[$row_act["id_ptba"]])) echo $nindicateur_array[$row_act["id_ptba"]]." planifi&eacute;(s)"; else echo "Ajouter";?></a></td>

<td align="center" nowrap="nowrap" class=" ">
<a onclick="get_content('./part_financement.php','<?php echo "id_act=".$row_act['id_ptba']."&code_act=".$row_act['code_activite_ptba']."&annee=".$annee."#os"; ?>','modal-body_add','<?php echo $row_act['code_activite_ptba'].": ".str_replace("'","\'",$row_act['intitule_activite_ptba']); ?>','iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="" class="thickbox" dir=""><span id="cout_<?php echo $annee.$row_act['id_ptba'];  ?>" ><?php if($cout_saisi!="")  echo number_format($cout_saisi, 0, ',', ' ') ; else echo "Co&ucirc;ts"; ?></span></a><?php //} ?></td>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==0 && isset($row_liste_mission['statut_version']) && $row_liste_mission['statut_version']==0){ ?>
<td align="center" nowrap="nowrap" class=" ">
<?php
echo do_link("","","Modifier Activit&eacute; ".$row_act['code_activite_ptba'],"","edit","./","","get_content('modal_content/new_activite_ptba.php','iact=".$row_act['id_ptba']."&id_act=".$row_act['id_ptba']."&annee=".$annee."#os','modal-body_add',this.title);",1,"margin:0px 5px 0 0; ","plan_ptba.php");

echo do_link("","./plan_ptba.php?id_sup_act=".$row_act['id_ptba']."&annee=".$annee,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette activit&eacute; ?');",0,"margin:0px 0 0 5px;","plan_ptba.php");
?>
</td>
<?php } ?>
</tr>
<?php $i++; } } ?>
</tbody></table>
<?php //include 'modal_add.php'; ?>