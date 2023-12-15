<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
$path = "./";
include_once $path.'system/configuration.php';
$config = new Config;

if (!isset($_SESSION["clp_id"]) || !isset($_GET["id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $path.$config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');
$annee=(isset($_GET['annee']))?$_GET['annee']:date("Y");
if(isset($_GET["id"]))
{
  $id_act = intval($_GET["id"]);
  $query_act = "SELECT * FROM ".$database_connect_prefix."ptba where annee='$annee' and projet='".$_SESSION["clp_projet"]."' and id_ptba='$id_act' ";
  $query_act .= " order by code_activite_ptba asc";
        try{
    $act = $pdar_connexion->prepare($query_act);
    $act->execute();
    $row_act = $act ->fetch();
    $totalRows_act = $act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  //Calcul
  $statut_act = array();
  if(isset($totalRows_act) && $totalRows_act>0) {
  $id_act=$row_act['id_ptba']; $code_act = $row_act['code_activite_ptba'];
  //suivi tache
  $query_suivi_tache = "SELECT suivi_tache.proportion as valeur_suivi, id_tache
  FROM ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."suivi_tache where id_groupe_tache=id_tache and id_activite='$id_act' and ".$database_connect_prefix."groupe_tache.projet='".$_SESSION["clp_projet"]."' and suivi_tache.observation is not null GROUP BY id_tache"; 
          try{
    $suivi_tache = $pdar_connexion->prepare($query_suivi_tache);
    $suivi_tache->execute();
    $row_suivi_tache = $suivi_tache ->fetchAll();
    $totalRows_suivi_tache = $suivi_tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $taux_tache = $taux_progress = 0;
  $ttt=0; $maxt=0; $idmaxt=0; if($totalRows_suivi_tache>0) { foreach($row_suivi_tache as $row_suivi_tache){  
  $taux_tache+=$row_suivi_tache["valeur_suivi"];
  }  }
  if(isset($taux_tache) && $taux_tache>0 && $totalRows_suivi_tache>0) {
  $ttt=$taux_tache; $taux_progress = $ttt; $stat = $ttt; }

  if($totalRows_suivi_tache>=0) { $taux=$ttt;
  if (isset($taux_progress) && $taux_progress>0 && $taux_progress<=100) $percent = $taux_progress;
  elseif (isset($taux_progress) && $taux_progress>100) $percent = 100;
  else $percent = 0;
  //Taux tache
  if(isset($_GET["l"]) && $_GET["l"]==1){ ?>
<script type="text/javascript">
$("#<?php echo "label_".intval($_GET["id"]); ?>", window.parent.document).html("<?php echo $percent; ?>");
</script>
  <?php exit; } //echo $percent;
  unset($taux_progress);

//Indicateur
if(isset($_GET["l"]) && $_GET["l"]==3){
unset($taux_progress,$taux);        //and ".$database_connect_prefix."cible_indicateur_trimestre.projet='".$_SESSION["clp_projet"]."'
$query_liste_ind_ptba = "SELECT id_indicateur_tache, intitule_indicateur_tache, id_activite FROM ".$database_connect_prefix."indicateur_tache, ".$database_connect_prefix."cible_indicateur_trimestre where id_indicateur_tache=indicateur and id_activite='$id_act'  group by id_indicateur_tache, code_indicateur_ptba";
        try{
    $liste_ind_ptba = $pdar_connexion->prepare($query_liste_ind_ptba);
    $liste_ind_ptba->execute();
    $row_liste_ind_ptba = $liste_ind_ptba ->fetchAll();
    $totalRows_liste_ind_ptba = $liste_ind_ptba->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tauxG=0; $taux_progress = 0;

if($totalRows_liste_ind_ptba>0) {$m=0; foreach($row_liste_ind_ptba as $row_liste_ind_ptba){  
//Cible
$id_ind=$row_liste_ind_ptba['code_indicateur_ptba'];    //and projet='".$_SESSION["clp_projet"]."'  and projet='".$_SESSION["clp_projet"]."'
$id_ind_tache=$row_liste_ind_ptba['id_indicateur_tache'];
$query_cible_ind = "SELECT SUM(cible) as valeur_cible FROM ".$database_connect_prefix."cible_indicateur_trimestre where indicateur='$id_ind_tache'  group by indicateur";
        try{
    $cible_ind = $pdar_connexion->prepare($query_cible_ind);
    $cible_ind->execute();
    $row_cible_ind = $cible_ind ->fetch();
    $totalRows_cible_ind = $cible_ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
//suivi indicateur
$query_suivi_ind = "SELECT sum(valeur_suivi) as valeur_reelle  FROM ".$database_connect_prefix."suivi_indicateur_tache where indicateur='$id_ind_tache' group by indicateur ";
        try{
    $suivi_ind = $pdar_connexion->prepare($query_suivi_ind);
    $suivi_ind->execute();
    $row_suivi_ind = $suivi_ind ->fetch();
    $totalRows_suivi_ind = $suivi_ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$tt=0; $max=0; $idmax=0; $taux = 0;
if(isset($row_cible_ind['valeur_cible']) && $row_cible_ind['valeur_cible']>0  && $totalRows_suivi_ind>0) {$taux=100*$row_suivi_ind['valeur_reelle']/$row_cible_ind['valeur_cible']; }
$tauxG+=($taux>100)?100:$taux;
} } if($totalRows_liste_ind_ptba>0) $taux_progress = $tauxG/$totalRows_liste_ind_ptba;

if (isset($taux_progress) && $taux_progress>0 && $taux_progress<=100) $percent = $taux_progress;
elseif (isset($taux_progress) && $taux_progress>100) $percent = 100;
else $percent = 0;
$data = (isset($taux_progress) && $taux_progress>0)?"<span dir='ras' id='label1_".$row_act['id_ptba']."'>".number_format($taux_progress, 0, ',', ' ')."%":"<span id='label' style='color:black;text-decoration:underline;color:blue;'>Suivre</span>"; unset($taux_progress);
?>
<script type="text/javascript">
$("#<?php echo "label1_".intval($_GET["id"]); ?>", window.parent.document).html("<?php echo $data; ?>");
</script>
<?php }

  if(isset($stat)){ if($stat==0 && $annee==date("Y")) $statut_act[$code_act]="Non entam&eacute;e"; elseif($stat>0 && $stat<100) $statut_act[$code_act]="En cours"; elseif($stat>=100) $statut_act[$code_act]="Ex&eacute;cut&eacute;e"; else $statut_act[$code_act]="Non ex&eacute;cut&eacute;e"; } else $statut_act[$code_act]="Non entam&eacute;e";
  //Observation
  if(isset($_GET["l"]) && $_GET["l"]==2){ ?>
<script type="text/javascript">
$("#<?php echo "statut_".intval($_GET["id"]); ?>", window.parent.document).html("<?php echo $statut_act[$code_act]; ?>");
</script>
  <?php exit; } //echo $statut_act[$code_act];
  unset($stat);
    }
  }
}
?>