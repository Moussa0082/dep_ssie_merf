<?php

///////////////////////////////////////////////

/*                 SSE                       */

/*	Conception & Développement: BAMASOFT */

///////////////////////////////////////////////

session_start();

include_once 'system/configuration.php';

$config = new Config;



if (!isset ($_SESSION["clp_id"])) {

    header(sprintf("Location: %s", "./"));

    exit;

}

include_once $config->sys_folder . "/database/db_connexion.php";



$personnel = $_SESSION["clp_id"];

$date = date("Y-m-d");

$nfile="projets.php";



extract($_GET); $statut = isset($statut)?$statut:0;



//if(isset($search)) $q_search = str_replace("'","\'",$search);

//$query = (isset($search) && !empty($search))?" and (P.code_projet LIKE '%$q_search%' OR P.sigle_projet LIKE '%$q_search%' OR P.intitule_projet LIKE '%$q_search%' OR P.nom_fonds_fidicuaire LIKE '%$q_search%' OR P.objectif_odd LIKE '%$q_search%' OR P.resultat_undaf LIKE '%$q_search%')":"";

//projets

//$query_entete = $db ->prepare('SELECT nombre FROM t_config_cadre_resultat_projet WHERE projet=:projet LIMIT 1'); $query_entete->execute(array(':projet' => isset($id)?$id:0));



/*$query_projet = $db ->prepare('SELECT * FROM liste_projet P WHERE actif=:actif and LIKE "%$q_search% ORDER BY code_projet asc');

$query_projet->execute(array(':actif' => isset($statut)?$statut:0));

$row_projet = $query_projet ->fetchAll();

$totalRows_projet = $query_projet->rowCount();*/



$query_liste_projet = "SELECT * FROM projet P WHERE actif=$statut  ORDER BY code_projet asc";

      	try{

    $liste_projet = $pdar_connexion->prepare($query_liste_projet);

    $liste_projet->execute();

    $row_projet = $liste_projet ->fetchAll();

    $totalRows_projet = $liste_projet->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }





$liste_structure_array = array();  $liste_structure_arrayV = $row_liste_structure = $projet_user_array = array();



//structures

/*$query_structure = $db ->prepare('SELECT id_partenaire as id_structure, nom_partenaire as nom_structure, sigle_partenaire as sigle FROM t_partenaires WHERE FIND_IN_SET(:type_partenaire,type_partenaire)');

$query_structure->execute(array(':type_partenaire' => 1));

$row_liste_structure = $query_structure ->fetchAll();

$totalRows_liste_structure = $query_structure->rowCount();

$liste_structure_array = array();  $liste_structure_arrayV = array();

if($totalRows_liste_structure>0){  foreach($row_liste_structure as $row_liste_structure){

$liste_structure_arrayV[$row_liste_structure["id_structure"]]=$row_liste_structure["nom_structure"];

$liste_structure_array[$row_liste_structure["id_structure"]]=$row_liste_structure["sigle"];

} }



//Projet users

$query_projet_user = $db ->prepare('SELECT * FROM t_projet_users');

$query_projet_user->execute();

$row_projet_user = $query_projet_user ->fetchAll();

$totalRows_projet_user = $query_projet_user->rowCount();

$projet_user_array = array();

if($totalRows_projet_user>0){  foreach($row_projet_user as $row_projet_user){

if(!isset($projet_user_array[$row_projet_user["projet_up"]])) $projet_user_array[$row_projet_user["projet_up"]]=$row_projet_user["personnel_up"]; else $projet_user_array[$row_projet_user["projet_up"]].=",".$row_projet_user["personnel_up"];

} }



//Users

//if(isset($_SESSION["type_fonction"]) && $_SESSION["type_fonction"]==100)

$query_personnel = $db ->prepare('SELECT P.*, F.fonction, S.sigle_partenaire as sigle, S.nom_partenaire as nom_structure, S.id_partenaire as id_structure FROM t_users P, t_fonction F, t_partenaires S WHERE FIND_IN_SET(:type_partenaire,S.type_partenaire) and F.structure=S.id_partenaire and F.id_fonction=P.fonction ORDER BY S.sigle_partenaire desc');

//else $query_personnel = $db ->prepare('SELECT P.*, F.fonction FROM t_users P, t_fonction F WHERE F.id_fonction=P.fonction and P.fonction and F.type_fonction='.$_SESSION["type_fonction"].' ORDER BY date_enregistrement desc');

$query_personnel->execute(array(':type_partenaire' => 1));

$row_personnel = $query_personnel ->fetchAll();

$totalRows_personnel = $query_personnel->rowCount();

$User_array = $Nuser_array = array();

if($totalRows_personnel>0){  foreach($row_personnel as $row_personnel1){

$User_array[$row_personnel1["id_user"]]=$row_personnel1["fonction"]." (".$row_personnel1["sigle"].")";

$Nuser_array[$row_personnel1["id_user"]]=$row_personnel1["nom"]." ".$row_personnel1["prenom"];

} }







$query_liste_cout_decaisse = $db ->prepare('SELECT t_ptba.projet, SUM( if(taux_dollars_jour>0, montant_decaisse/taux_dollars_jour,0) ) AS montant FROM t_suivi_decaissement_ptba, t_ptba WHERE id_ptba=activite_ptba  GROUP BY t_ptba.projet');

$query_liste_cout_decaisse->execute();

$row_liste_cout_decaisse = $query_liste_cout_decaisse ->fetchAll();

$totalRows_liste_cout_decaisse = $query_liste_cout_decaisse->rowCount();

$tableauCoutDecaisse=array();

if($totalRows_liste_cout_decaisse>0){  foreach($row_liste_cout_decaisse as $row_liste_cout_decaisse){

$tableauCoutDecaisse[$row_liste_cout_decaisse["projet"]]=$row_liste_cout_decaisse["montant"];

} }



$query_liste_tache = $db ->prepare('select sum(taux_niveau) as taux_projet, projet from (SELECT t_ptba.projet, code_activite_ptba, avg(taux_tache) as taux_niveau FROM ((SELECT sum(D.proportion) as taux_tache, G.id_activite  FROM t_type_tache D inner join t_groupe_tache G on D.id_groupe_tache=G.id_groupe_tache   WHERE G.valider="oui" Group BY id_activite) as taux inner join t_ptba on id_ptba=id_activite) group by code_activite_ptba, t_ptba.projet) as tache_projet group by projet');

$query_liste_tache->execute();

$row_liste_tache = $query_liste_tache ->fetchAll();

$totalRows_liste_tache = $query_liste_tache->rowCount();

$tableauTache=array();

if($totalRows_liste_tache>0){  foreach($row_liste_tache as $row_liste_tache){

$tableauTache[$row_liste_tache["projet"]]=$row_liste_tache["taux_projet"];

} }*/



/*$query_entete = $db ->prepare('SELECT nombre FROM t_config_cadre_resultat_projet WHERE projet=:projet LIMIT 1');

$query_entete->execute(array(':projet' => isset($id)?$id:0));

$row_entete = $query_entete ->fetch();

$totalRows_entete = $query_entete->rowCount();

$code_len = 0;

if($totalRows_entete>0) $code_len = $row_entete["nombre"];*/



/*$query_liste_activite = $db ->prepare('SELECT count(code) as nbact, A.projet FROM t_cadre_resultat A, t_config_cadre_resultat_projet N  WHERE A.projet=N.projet and A.niveau=N.nombre  group by A.projet asc');

$query_liste_activite->execute();

$row_liste_activite = $query_liste_activite ->fetchAll();

$totalRows_liste_activite = $query_liste_activite->rowCount();

$Nactivite_array = array();

if($totalRows_liste_activite>0){  foreach($row_liste_activite as $row_liste_activite){

$Nactivite_array[$row_liste_activite["projet"]]=$row_liste_activite["nbact"];

} }*/


//Montant projet bailleur

//$query_projet_cout = $db ->prepare('SELECT * FROM t_repartition_projet_budget order by projet_bud');
$query_liste_part = "SELECT count(bailleur) as nbbail, sum(montant) as partb, type_part.projet FROM ".$database_connect_prefix."partenaire, ".$database_connect_prefix."type_part WHERE code=bailleur  GROUP BY type_part.projet";
try{
    $liste_part = $pdar_connexion->prepare($query_liste_part);
    $liste_part->execute();
    $row_liste_part = $liste_part ->fetchAll();
    $totalRows_liste_part = $liste_part->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_liste_part>0){ foreach($row_liste_part as $row_liste_part){ 
$bailleur[$row_liste_part["projet"]]=$row_liste_part["partb"]; 
//$cbailleur[$row_liste_part["id_partenaire"]]=$row_liste_part["partb"];
} 
}

$onglet_array = array(0=>"Projets en cours",1=>"Projets clôturés");

$Panel_Item_Style="#090";

?>

<style>

.contact-stat span, .contact-stat-span {

    font-size: 10px;

    font-weight: 500;

    display: block;

    color: #9d9fa2;

    text-transform: uppercase;

}

.hpanel .panel-body {

    background: #fff;

    border: 1px solid #eaeaea;

    border-radius: 2px;

    padding: 20px;

    position: relative;

}

.btn-xs {

    padding: 2px 3px;

    font-size: 11px;

}

</style>

<script>

$().ready(function() {

    $().ready(function(){$(".projects-list<?php echo $statut; ?>").attr("style","display:none;");});

var oTable = $('#mtable<?php echo $statut; ?>').dataTable( {

dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>t<'row'<'col-sm-6'i><'col-sm-6'p>>",

"columnDefs": [{ targets: 'no-sort', orderable: false }],

responsive: true,

//"lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "Tout"] ],

//"pageLength": -1, paging: false,

"bAutoWidth": false,

buttons: [{extend: 'copy',className: 'btn-sm'},{extend: 'csv',title: $('#sub-title').text().trim(), className: 'btn-sm'},{extend: 'pdf', title: $('#sub-title').text().trim(), className: 'btn-sm'},{extend: 'print', title: $('#sub-title').text().trim(),className: 'btn-sm'}]

    });

});

</script>



<div class="row0">

<?php if(isset($search) && !empty($search)) { ?><legend style="text-align: center;" class="<?php echo $Text_Style; ?>">Résultat de la recherche pour : "<i><?php echo $search; ?></i>"</legend><?php } ?>

<?php if($totalRows_projet>0) { ?>

<div class="projects<?php echo $statut; ?>" style="padding-top:15px;overflow: hidden;">

<?php $i=0; $row_projet_array = $row_projet; foreach($row_projet as $row_projet) { $id = $row_projet['code_projet']; ?>

<div class="col-lg-6" style="margin-bottom: 10px;">

                <div class="hpanel" style="border-top: 2px solid <?php echo $Panel_Item_Style; ?>!important;">

                    <div class="panel-body" style="padding: 15px 0;<?php echo ($row_projet['statut']==0)?"":"background-color: #DCDCDC;"; ?>">

                        <div class="row0" style="text-align: left">

                            <div class="col-sm-7">

                                <h4 style="margin-top: 0px;"><a href="javascript:void(0);"><span style="color:#000;"><?php  if(isset($row_projet['nom_abrege'])) echo $row_projet['nom_abrege']; else echo $row_projet['sigle_projet']; ?></span><br><span style="font-size: 10px;text-transform: uppercase;"><?php echo $row_projet['programme']; ?></span></a></h4>

                                <p><?php echo $row_projet['intitule_projet']; ?></p>

                            </div>



                            <div class="col-sm-5 project-info">

                                <div class="project-action" align="right">

                                    <div class="btn-group" style="font-size: 18px">



<?php //echo do_link_modern("","./projet_details.php?id=".$id,"Afficher","","view","./","btn btn-xs btn-default","",0,"",$nfile); ?>

                                    </div>

                                </div>

                                <div class="project-value m-t-md">

                                    <!-- <img class="img-circle m-b" <?php //echo 'style="border: solid 1px '.$Panel_Item_Style.'"'; ?> src="<?php //echo (file_exists("./images/projet/img_$id.jpg"))?"./images/projet/img_$id.jpg":"./images/projet/none.png"; ?>" width="130" height="130" alt="">-->

                                   <h5 class="text-warning">

                                  <?php if(isset($tableauCoutDecaisse[$id]) && isset($projet_cout_array[$id]) && $projet_cout_array[$id]>0) {

										echo "Décaissement: ".number_format(100*$tableauCoutDecaisse[$id]/$projet_cout_array[$id], 0, ',', ' ')." %";} ?> 

                                    </h5>



                                </div>

                            </div>

                        </div>



                        <div class="col-sm-12">

                            <div class="col-sm-3">

                                    <div class="project-label">Démarage</div>

                                    <b><small><?php echo $row_projet['annee_debut']; ?></small></b>

                            </div>

                            <div class="col-sm-4">

                                    <div class="project-label">Bailleurs</div>

                                    <b><small><?php echo $row_liste_part['modalite_financement']; ?></small></b>

                            </div>

                            <div class="col-sm-5">

                                    <div class="project-label" align="center">Budget (<em>FCFA</em>)</div>

                                    <b><small><div style="font-weight: bold;">

                                        <div align="center"><span class="btn-info">

                                            <?php  if(isset($bailleur[$id])) echo "&nbsp;&nbsp;<span title=\"".number_format($bailleur[$id], 0, ',', ' ')." USD\">".number_format($bailleur[$id], 0, ',', ' ')."&nbsp;&nbsp;</span>";  else echo ""; ?>

                                            </span></div>

                                    </div>

                                    </small></b>

                            </div>



                        </div>

                        <div class="col-sm-12">&nbsp;</div>



                        <div class="col-sm-12">

                            <div class="col-sm-4">

                            <div class="project-label">Clôture</div>

                            <b><small><?php echo $row_projet['annee_fin'];?></small></b>

                            </div>

                            <div class="col-sm-8">

                            <div class="project-label">Structures partenaires</div>

                            <b><small><?php if(isset($row_projet['autres_agences_recipiendaires']) && !empty($row_projet['autres_agences_recipiendaires'])){ $a = explode(",",$row_projet['autres_agences_recipiendaires']); if(count($a)>0){ $c = array(); foreach($a as $b) { if(isset($projet_cout_agence_array[$id][$b]))  $maar=": <strong class=\"text-info\">&nbsp;".number_format($projet_cout_agence_array[$id][$b], 0, ',', ' ')." $</strong>&nbsp;"; else $maar=""; if(isset($liste_structure_array[$b])) $c[]="<span title=\"".$liste_structure_arrayV[$b]."\">".$liste_structure_array[$b]."".$maar.""."</span>"; } echo count($c)>0?implode('; &nbsp;',$c):"Aucun"; } else echo "Aucun"; } else echo "Aucun"; ?></small></b>

                            </div>

                         

                            </div>

                        <div class="row">&nbsp;</div>

                    </div>

                    <div class="panel-footer contact-panel" style="padding: 0px 15px;">

                    <div class="row">

                        <div class="col-md-4 border-right"> <div class="contact-stat"><span>Durée : </span> <strong><?php $nombreMois = $row_projet['duree'];$annees = intval($row_projet['annee_fin']-$row_projet['annee_debut']);$mois = intval(($nombreMois % 12)); echo "$annees ans"; ?></strong></div> </div>

						

                        <div class="col-md-4 border-right"> <div class="contact-stat"><span>D&eacute;caissement (%): </span> <strong><?php $nombreMois = $row_projet['duree'];$annees = intval($nombreMois / 12);$mois = intval(($nombreMois % 12)); echo "$annees an".($annees>1?"s":"").($mois>0?" $mois mois":""); ?></strong></div> </div>

						

						<div class="col-md-4"> 

						<?php if(isset($tableauTache[$id]) && isset($Nactivite_array[$id]) && $Nactivite_array[$id]>0) $tauxP=$tableauTache[$id]/$Nactivite_array[$id];  else $tauxP=0;?>

						 <div class="project-label contact-stat-span" align="left">Avancement (<strong><?php if($tauxP>0) echo number_format($tauxP, 2, ',', ' '); ?>%</strong>)</div>

                                        <div class="progress m-t-xs full progress-small">

				

							

				<?php if($tauxP>0) {if($tauxP<30) $np="danger"; elseif($tauxP<75) $np="warning"; elseif($tauxP>75) $np="success";  ?>

                                            <div style="width: <?php echo $tauxP; ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo $tauxP; ?>" role="progressbar" class=" progress-bar progress-bar-<?php echo $np; ?>"> </div>

											<?php } else echo "non démarré";?>

                                        </div>  </div>

                        </div>

                    </div>

            </div>

</div>

<?php $i++; if($i%2==0) echo "<div class='clear h0'>&nbsp;</div>"; } ?></div>

<!--Liste-->

<div class="hpanel projects-list<?php echo $statut; ?>" ><div class="panel-body1">



<table class="table table-striped table-bordered table-hover table-responsive table-checkable table-tabletools table-colvis datatable dataTable" id="mtable<?php echo $statut; ?>" >

<thead>

<tr>

<th>Code</th>

<th>Sigle</th>

<th>Intitul&eacute;</th>

<th>Année de  demarrage </th>

<th>Durée (année) </th>

<th><span title="Modalité de financement">Bailleurs</span></th>

<!--<th>Type de fonds fidicuiare</th>-->

<th>Coût&nbsp;(FCFA)</th>

<th>Niveau d'avancement </th>

<th>Taux de décaissement </th>

<?php //if(isset($_SESSION['niveau']) && $_SESSION['niveau']==1) { ?>

<th width="80" class="no-sort">Actions</th>

<?php //} ?>
</tr>
</thead>

<tbody role="alert" aria-live="polite" aria-relevant="all" class="">

<?php $i=0; foreach($row_projet_array as $row_projet) { $id = $row_projet['code_projet']; ?>

<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">

<td class=" "><?php echo $row_projet['code_projet']; ?></td>

<td class=" "><?php echo $row_projet['sigle_projet']; ?></td>

<td class=" "><?php echo $row_projet['intitule_projet']; ?></td>

<td class=" "><?php echo $row_projet['annee_debut']; ?></td>

<td class=" "><?php  echo $row_projet['annee_fin']-$row_projet['annee_debut']; ?></td>

<td class=" "><?php echo $row_projet['modalite_financement']; ?></td>

<!--<td class=" "><?php //echo $row_projet['type_fonds_fidicuiare']; ?></td>-->

<td nowrap="nowrap" class=" ">

<small>
<div style="font-weight: bold;"><span class=" ">

<?php  if(isset($projet_cout_array[$id])) echo "&nbsp;&nbsp;<span title=\"".$projet_cout_array[$id]." USD\">".$projet_cout_array[$id]."&nbsp;&nbsp;</span>";  else echo ""; ?>

</span></div>
</small>
 <?php  if(isset($bailleur[$id])) echo "&nbsp;&nbsp;<span title=\"".number_format($bailleur[$id], 0, ',', ' ')." USD\">".number_format($bailleur[$id], 0, ',', ' ')."&nbsp;&nbsp;</span>";  else echo ""; ?>
<?php //if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) echo do_link("","","Coût du projet ".$row_projet['sigle_projet'],"","edit","./","pull-right","get_content('projet_budget.php','id=$id','modal-body_add',this.title,'iframe');",1,"",$nfile); ?></td>

<td class=" ">&nbsp;</td>

<td class=" ">&nbsp;</td>

<?php //if(isset($_SESSION['niveau']) && $_SESSION['niveau']==1) { ?>

<td class=" " align="center">

<!--<a href="./prodoc_gen.php?id=<?php echo $id; ?>" class="" title="Télécharger le document projet" style="margin:0px 5px;"><i class="fa fa-id-card" alt="Document" title="Télécharger le document projet" style="font-size: 22px;color: #000;"></i></a>-->



<?php echo do_link("","./projet_details.php?id=".$id,"Afficher","","view","./","","",0,"margin:0px 5px;",$nfile);



?></td>

<?php //} ?>
</tr>

<?php } ?>
</tbody></table>

</div></div>

<?php } else{ ?>

      

            <h1 align="center">Pas de <?php echo (isset($search) && !empty($search))?"résultat":" ".(isset($onglet_array[$statut])?$onglet_array[$statut]:"projet saisi dans ce programme"); ?> !</h1>



<?php } ?>

</div>