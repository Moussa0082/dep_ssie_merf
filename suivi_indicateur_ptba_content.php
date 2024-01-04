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
if(isset($_GET['version'])) {$version=($_GET['version']);} else $version=1;

if(isset($_GET['cmp']) && !empty($_GET['cmp'])) $cmp = $_GET['cmp']; else $cmp="";
/*
if(isset($_GET["cmp"]) && $_GET["cmp"]!=0) {$cmp=$_GET["cmp"]; 
 $whercible="and cible_indicateur_trimestre.region like '$cmp'"; $whersuivi="and suivi_indicateur_tache.ugl like '$cmp'"; } else {$wheract=""; $whercible=$whersuivi="";}*/
$wheract=""; $whercible=$whersuivi="";
$array_indic = array("OUI/NON","texte");
$uglprojet=str_replace("|",",",$_SESSION["clp_projet_ugl"]);

$query_liste_ugl = "SELECT distinct code_ugl, abrege_ugl FROM ".$database_connect_prefix."ugl   order by code_ugl";
  try{
    $liste_ugl = $pdar_connexion->prepare($query_liste_ugl);
    $liste_ugl->execute();
    $row_liste_ugl = $liste_ugl ->fetchAll();
    $totalRows_liste_ugl = $liste_ugl->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


$query_total_proportion = "SELECT ROUND(SUM(if(n_lot>0, proportion*jalon/n_lot,0))) as total, id_activite FROM ".$database_connect_prefix."groupe_tache WHERE  valider=1   GROUP BY id_activite";
  try{
    $total_proportion = $pdar_connexion->prepare($query_total_proportion);
    $total_proportion->execute();
    $row_total_proportion = $total_proportion ->fetchAll();
    $totalRows_total_proportion = $total_proportion->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $prop_tab=array();
 if($totalRows_total_proportion>0) { foreach($row_total_proportion as $row_total_proportion){  
$prop_tab[$row_total_proportion["id_activite"]] = $row_total_proportion["total"];
} }


$query_entete = "SELECT libelle,code_number FROM niveau_config WHERE projet='".$_SESSION["clp_projet"]."' LIMIT 1";
try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$code_len = explode(',',$row_entete["code_number"]);
$libelle=explode(",",$row_entete["libelle"]);
$limit = count($libelle)-1;

$query_liste_activite_1 = "SELECT code,intitule FROM activite_projet WHERE niveau=$limit and projet='".$_SESSION["clp_projet"]."' ";
  try{
    $liste_activite_1 = $pdar_connexion->prepare($query_liste_activite_1);
    $liste_activite_1->execute();
    $row_liste_activite_1 = $liste_activite_1 ->fetchAll();
    $totalRows_liste_activite_1 = $liste_activite_1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $cmp_array=array();
 if($totalRows_liste_activite_1>0) { foreach($row_liste_activite_1 as $row_liste_activite_1){  
$cmp_array[$row_liste_activite_1["code"]] = $row_liste_activite_1["intitule"];
} }


$query_liste_activite_2 = "SELECT code,intitule FROM activite_projet WHERE niveau=2 and projet='".$_SESSION["clp_projet"]."'  order by code";
  try{
    $liste_activite_2 = $pdar_connexion->prepare($query_liste_activite_2);
    $liste_activite_2->execute();
    $row_liste_activite_2 = $liste_activite_2 ->fetchAll();
    $totalRows_liste_activite_2 = $liste_activite_2->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $cmp_val=array();
 if($totalRows_liste_activite_2>0) { foreach($row_liste_activite_2 as $row_liste_activite_21){  
$cmp_val[$row_liste_activite_21["code"]] = $row_liste_activite_21["intitule"];
} }



//Cible
$cible_indicateur_array = $valeur=$nbr =$indicateur= array();
$query_cible_ind = "SELECT indicateur,SUM(cible) as valeur_cible FROM ".$database_connect_prefix."cible_indicateur_trimestre where  indicateur in (SELECT id_indicateur_tache FROM ".$database_connect_prefix."indicateur_tache where indicateur_cr<>0 and annee='$annee' and projet='".$_SESSION["clp_projet"]."') group by indicateur";
  try{
    $cible_ind = $pdar_connexion->prepare($query_liste_activite_1);
    $cible_ind->execute();
    $row_cible_ind = $cible_ind ->fetchAll();
    $totalRows_cible_ind = $cible_ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $cible_indicateur_array=array();
 if($totalRows_cible_ind>0) { foreach($cible_ind as $cible_ind){  
if(!isset($cible_indicateur_array[$row_cible_ind["indicateur"]]))
$cible_indicateur_array[$row_cible_ind["indicateur"]] = $row_cible_ind["valeur_cible"];
else $cible_indicateur_array[$row_cible_ind["indicateur"]] += $row_cible_ind["valeur_cible"];
} }


// Taux indicateurs
$query_liste_taux_ind_ptba = "SELECT id_ptba as code, code_activite_ptba, avg(if(tcible>0, 100*tsuivi/tcible,0)) as taux_niveau FROM (SELECT ptba.id_ptba, code_activite_ptba, indicateur_tache.id_indicateur_tache, sum(cible_indicateur_trimestre.cible) AS tcible FROM ptba INNER JOIN (indicateur_tache INNER JOIN  cible_indicateur_trimestre ON indicateur_tache.id_indicateur_tache= cible_indicateur_trimestre.indicateur ) ON ptba.id_ptba = indicateur_tache.id_activite where ptba.annee='$annee' and cible>0 $whercible and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY ptba.id_ptba, code_activite_ptba, indicateur_tache.id_indicateur_tache) AS cible LEFT JOIN (SELECT indicateur_tache.id_indicateur_tache, SUM(suivi_indicateur_tache.valeur_suivi) AS tsuivi FROM ptba INNER JOIN (indicateur_tache LEFT JOIN suivi_indicateur_tache ON indicateur_tache.id_indicateur_tache= suivi_indicateur_tache.indicateur) ON ptba.id_ptba = indicateur_tache.id_activite where ptba.annee='$annee' $whersuivi and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY indicateur_tache.id_indicateur_tache) AS suivi ON cible.id_indicateur_tache= suivi.id_indicateur_tache GROUP BY id_ptba";
  try{
    $liste_taux_ind_ptba = $pdar_connexion->prepare($query_liste_taux_ind_ptba);
    $liste_taux_ind_ptba->execute();
    $row_liste_taux_ind_ptba = $liste_taux_ind_ptba ->fetchAll();
    $totalRows_liste_taux_ind_ptba = $liste_taux_ind_ptba->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $indicateur=array();
 if($totalRows_liste_taux_ind_ptba>0) { foreach($row_liste_taux_ind_ptba as $row_liste_taux_ind_ptba){  
if(!isset($cible_indicateur_array[$row_liste_taux_ind_ptba["code"]])) $indicateur[$row_liste_taux_ind_ptba["code"]]=$row_liste_taux_ind_ptba["taux_niveau"];
  if(isset($valeur[$row_liste_taux_ind_ptba["code"]]) && isset($nbr[$row_liste_taux_ind_ptba["code"]]))
  $indicateur[$row_liste_taux_ind_ptba["code"]] = (($row_liste_taux_ind_ptba["taux_niveau"])+$valeur[$row_liste_taux_ind_ptba["code"]])/($nbr[$row_liste_taux_ind_ptba["code"]]+1);
} }

   // }

$query_act = "SELECT * FROM ptba where ptba.annee='$annee' and projet='".$_SESSION["clp_projet"]."' ";
if(isset($_GET['cmp']) && !empty($_GET['cmp'])) $query_act .= "and left(code_activite_ptba,'".$code_len[1]."') LIKE '%$cmp%'";
$query_act .= " order by  code_activite_ptba asc";
try{
    $act = $pdar_connexion->prepare($query_act);
    $act->execute();
    $row_act = $act ->fetchAll();
    $totalRows_act = $act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//echo $query_act;
//exit;


number_format(0, 0, ',', ' ');

$query_liste_prestataire = "SELECT * FROM acteur order by code_acteur ";
    try{
    $liste_prestataire = $pdar_connexion->prepare($query_liste_prestataire);
    $liste_prestataire->execute();
    $row_liste_prestataire = $liste_prestataire ->fetchAll();
    $totalRows_liste_prestataire = $liste_prestataire->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $acteur_array=array();
 if($totalRows_liste_prestataire>0) { foreach($row_liste_prestataire as $row_liste_prestataire){  
    $acteur_array[] = $row_liste_prestataire["id_acteur"]."!!".$row_liste_prestataire["nom_acteur"];
} }
?>

<!-- Site contenu ici -->

<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {

  border-spacing: 0px !important; border-collapse: collapse; width: 100%!important;

} .table tbody tr td {vertical-align: middle; }

</style>

<script>

$('#myModal_add').remove();

$().ready(function() {

//$('a[data-toggle="modal"]').modal();

var oTable = $('#mtable<?php echo $annee; ?>').dataTable( {

        "aoColumnDefs": [

            { "bSortable": false, "aTargets": [ -1 ] }

        ],

      //  sDom:"<'row'<'dataTables_header clearfix'<'col-md-7'lT><'col-md-5'Cf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",

       // oTableTools:{aButtons:["copy","print","csv","xls",{"sExtends": "pdf","sPdfOrientation": "landscape"}],sSwfPath:"./swf/copy_csv_xls_pdf.swf"},

        "aaSorting": [],

        //"aLengthMenu":[[25, 50, 100, 200, -1],[25, 50, 100, 200, "TOUS1"]],

        "iDisplayLength": -1,

        paging: false

    });

});

</script>
<form name="form<?php echo $annee; ?>" id="form<?php echo $annee; ?>" method="get" action="<?php echo "suivi_indicateur_ptba.php?version=".$version; ?>" class="pull-left"> <?php echo ((isset($libelle[1]) && !empty($libelle[1])))?$libelle[1]:"S/Composantes" ?> 
<select name="cmp" onchange="form<?php echo $annee; ?>.submit();" style="background-color: #FFFF00; padding: 7px; " class="btn p11">
  <option value="">-- Selectionnez une Sous-composante--</option>
  <?php if($totalRows_liste_activite_2>0){  foreach($row_liste_activite_2 as $row_liste_activite_2){ ?>
<option value="<?php echo $row_liste_activite_2["code"]; ?>" <?php if(isset($_GET["cmp"]) && $row_liste_activite_2["code"]==$_GET["cmp"]) echo "selected='SELECTED'"; ?>><?php echo $row_liste_activite_2["code"].": ".substr($row_liste_activite_2['intitule'],0, 70)."..."; ?></option>
  <?php } } ?>
 <option value="%">-- Toutes --</option>
</select>
<input type="hidden" name="annee" value="<?php echo $annee; ?>" />
</form>

<table class="table table-striped table-bordered table-hover table-responsive table-colvis datatable dataTable" id="mtable<?php echo $annee; ?>" aria-describedby="DataTables_Table_0_info">

<thead>

<tr role="row">
  <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Code</th>

  <!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><?php echo (isset($libelle[count($libelle)-2]))?$libelle[count($libelle)-2]:""; ?></th>-->

<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><?php echo (isset($libelle[count($libelle)-1]))?$libelle[count($libelle)-1]:"Activit&eacute;s"; ?></th>

<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Acteurs</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Période</th>
<th align="center" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" width="150">Coût</th>
<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" width="150"><div align="center">&nbsp;Co&ucirc;ts&nbsp;</div></th>-->
<th align="center" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" width="150"><div align="center">T&acirc;ches</div>  <div align="center"></div>  <div align="center"></div></th>

<th align="center" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" width="150"><div align="center">Indicateurs</div>  <div align="center"></div>  <div align="center"></div></th>

<th align="center" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><div align="center">Observations</div>  <div align="center"></div>  <div align="center"></div></th>
</tr>
</thead>

<tbody role="alert" aria-live="polite" aria-relevant="all" class="">

<?php if(isset($totalRows_act) && $totalRows_act>0) { $i=0; foreach($row_act as $row_act) {

 $c = substr($row_act['code_activite_ptba'],0,((isset($code_len[$limit-1])?$code_len[$limit-1]:0))); $id_act=$row_act['id_ptba']; $code_act = $row_act['code_activite_ptba'];  ?>

<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
  <td class=" "><?php echo "<strong>".$row_act['code_activite_ptba'].":</strong> "; ?></td>

<!--<td class=" "><font size="1"><?php echo (isset($cmp_array[$c]))?$cmp_array[$c]:""; ?></font></td>-->

<td class=" "><?php echo $row_act['intitule_activite_ptba']; ?></td>

<td class=" ">  <?php 
//if($row_act['acteur_conserne']=="0") echo "<strong>&nbsp;".strtoupper(substr($database_connect_prefix,0,-1))."</strong> "; elseif(isset($acteur_array[$row_act["acteur_conserne"]])) echo "<strong>".$acteur_array[$row_act["acteur_conserne"]]."</strong> ";
 if(isset($row_act['responsable']) && $row_act['responsable']!=" ") echo $row_act['responsable']."- "; ?>
<span style="font-size: 10px">
 <?php 
 $actc = explode(",", $row_act['acteur_conserne']);
 
 
 foreach($acteur_array as $vacteur){ 
 
 $aacteur = explode('!!',$vacteur);

$iacteur = $aacteur[0];
  ?>
<?php echo (in_array($iacteur, $actc, TRUE))?$aacteur[1]."/ ":''; ?>
 <?php }
 
 $ap = explode(",", $row_act['debut']);
  ?></span></td>
<td class=" " nowrap="nowrap"><?php if(count($ap)>0) echo $ap[0]; if(isset($ap[1])) echo " - ".$ap[1];?></td>
<td class=" " nowrap="nowrap"><?php 
unset($taux_progressc,$tauxc);$ttc=0; $maxc=0; $idmaxc=0; $tauxc = 0; $prev=$reals=0;
$percentc=100;
  if(isset($row_act['cout_cfa']) && $row_act['cout_cfa']>0 && isset($decaissement_array[$id_act])){ $prev=$row_act['cout_cfa']; $reals=$decaissement_array[$id_act]; $taux_progressc = 100*$decaissement_array[$id_act]/$row_act['cout_cfa']; $percentc = $taux_progressc; }else { $percentc=100; $taux_progressc = 0; }
//if(isset($tableauCoutSaisi[$row_act["code_budget"]]))  echo number_format($tableauCoutSaisi[$row_act["code_budget"]], 0, ',', ' ')." %" ; else echo "-"; ?>

<a onclick="get_content('suivi_decaissement_ptba.php','<?php echo "id_act=".$row_act['id_ptba']."&code_act=$code_act&annee=$annee"; ?>','modal-body_add','<?php echo str_replace("'","\'",$row_act['intitule_activite_ptba']); ?>','iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="'<?php echo "Pr&eacute;vu: ".number_format($prev, 0, ',', ' ')." /D&eacute;caiss&eacute;: ".number_format($reals, 0, ',', ' ');?>'" class="thickbox" dir="">

<?php 

if($taux_progressc<39) $color = "danger";
elseif($taux_progressc<69) $color = "warning";
elseif($taux_progressc>=70) $color = "success"; 
//else $color = "danger";

?>

<span id="label1c_<?php echo $id_act; ?>" >
<div class="progress" align="center"> <div  class="progress-bar progress-bar-<?php echo $color; ?>" style="width: <?php echo $percentc; ?>%; text-align:center"><?php echo (isset($taux_progressc) && $taux_progressc>0)?number_format($taux_progressc, 0, ',', ' ')." %":"Suivre"; unset($taux_progressc); ?></div> </div>
</span></a>
</td>
<td class=" ">

<?php //suivi tache

 
$query_src_financement = "SELECT SUM( if(montant>0, montant,0) ) AS montant  FROM part_bailleur where  activite=$id_act";

try{
    $src_financement = $pdar_connexion->prepare($query_src_financement);
    $src_financement->execute();
    $row_src_financement = $src_financement ->fetch();
    $totalRows_src_financement = $src_financement->rowCount();
	}catch(Exception $e){ die(mysql_error_show_message($e)); }
$financement_total=$financement_maep = 0;
 if($totalRows_src_financement>0) { 
/*foreach($row_src_financement as $row_src_financement1){  */
  $financement_total = $financement_total+$row_src_financement["montant"];
  $financement_maep = $financement_maep+doubleval($row_src_financement["montant"]);
   }

  //  taux fin recuperation 

$color = "danger";

$tauxp=0;

 if(isset($prop_tab[$id_act]))

 { $tauxp=$prop_tab[$id_act];

 if($tauxp<39) $color = "danger";
elseif($tauxp<69) $color = "warning";
 elseif($tauxp>=70) $color = "success";

    } elseif(isset($prop_stat) && in_array($id_act,$prop_stat)){ $prop_tab[$id_act] = 0; $color = "warning"; } ?>

<div> <a style="display: block;" onclick="get_content('suivi_plan_taches.php','<?php echo "cat=".$row_act['isous_composante']."&id_act=$id_act&code_act=$code_act&annee=$annee&cmp=$cmp"; ?>','modal-body_add','<?php echo $row_act['code_activite_ptba'].": ".str_replace("'","\'",$row_act['intitule_activite_ptba']); ?>','iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Niveau d'avancement '<?php if(isset($prop_tab[$id_act])) echo $prop_tab[$id_act]; else echo "0"; ?> %'" class="thickbox" dir="">

<span id="stat_<?php echo $annee.$row_act['id_ptba'];  ?>" >

<div class="progress"> <div class="progress-bar progress-bar-<?php echo $color; ?>" style="width: <?php if(isset($prop_tab[$id_act]) && $prop_tab[$id_act]>0){ echo $prop_tab[$id_act]; } else echo "100"; ?>%"><?php if(isset($prop_tab[$id_act])) echo $prop_tab[$id_act]." %"; else echo "Non entam&eacute;e"; ?></div> </div>
</span></a> </div></td>

<td class=" " align="center">
<?php unset($taux_progress,$taux);$tt=0; $max=0; $idmax=0; $taux = 0;
$percent=100;
  if(isset($indicateur[$row_act["id_ptba"]]) && $indicateur[$row_act["id_ptba"]]>0){ $taux_progress = $indicateur[$row_act["id_ptba"]]; $percent = $taux_progress; }else { $percent=100; $taux_progress = 0; } ?>
<a onclick="get_content('new_suivi_indicateur_ptba.php','<?php echo "id_act=".$row_act['id_ptba']."&code_act=$code_act&annee=$annee&cmp=$cmp"; ?>','modal-body_add','<?php echo $row_act['code_activite_ptba'].": ".str_replace("'","\'",$row_act['intitule_activite_ptba']); ?>','iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="<?php echo "Taux d'&eacute;x&eacute;cution: ".number_format($taux_progress, 2, ',', ' ')."%";?>" class="thickbox" dir="">

<?php 

if($taux_progress<39) $color = "danger";
elseif($taux_progress<69) $color = "warning";
elseif($taux_progress>=70) $color = "success";
//echo (isset($taux_progress) && $taux_progress>0)?"<span dir='ras' id='label1_".$row_act['id_ptba']."'>".number_format($taux_progress, 0, ',', ' ')."%</span>":"<span id='label1_".$row_act['id_ptba']."' style='color:black;text-decoration:underline;color:blue;'>Suivre</span>"; ?>

<span id="label1_<?php echo $row_act['id_ptba']; ?>" >
<div class="progress"> <div class="progress-bar progress-bar-<?php echo $color; ?>" style="width: <?php echo $percent; ?>%"><?php echo (isset($taux_progress) && $taux_progress>0)?number_format($taux_progress, 0, ',', ' ')." %":"Suivre"; unset($taux_progress); ?></div> </div>
</span></a></td>

<td class=" "><a onclick="get_content('modal_content/edit_observation_suivi_ptba.php','<?php echo "id_act=".$row_act['id_ptba']."&ugl=$cmp&annee=$annee"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="'<?php echo str_replace("'","\'",$row_act['intitule_activite_ptba']); ?>'" class="thickbox" dir=""><span id="statut_<?php echo $annee.$row_act['id_ptba'];  ?>" class="simple">  <?php if($row_act['statut']=='auto' || empty($row_act['statut'])){ if($tauxp==0 && $annee==date("Y")) echo "Non entam&eacute;e"; elseif($tauxp>0 && $tauxp<100) echo "En cours"; elseif($tauxp>=100) echo "Ex&eacute;cut&eacute;e"; else echo "Non ex&eacute;cut&eacute;e"; } else echo $row_act['statut']; ?></span><?php unset($tauxp); ?></a></td>
</tr>

<?php $i++; } } ?>
</tbody></table>

 <script>
 $(document).ready(function() {
    var tauxArrayJson = localStorage.getItem("taux_array");
    var tauxArray = JSON.parse(tauxArrayJson);
    // ... (code pour afficher les taux)
    // ... (suite du code)
tauxArray.forEach(function(element) {
    // Construction du code HTML pour la ligne
    var html = '<tr><td>' + element.id_act + '</td><td><div class="progress"> <div class="progress-bar progress-bar-' + element.color + '" style="width:' + number_format(element.taux_progressc, 0, ',', ' ') + '%">' + (((element.taux_progressc > 0) ? number_format(element.taux_progressc, 0, ',', ' ') + ' %' : 'Suivre')) + '</div> </div></td></tr>';

    // Ajout de la ligne au tableau
    $("#table-body").append(html);
});
});
 </script>

<?php include 'modal_add.php'; ?>