<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
if (!isset ($_SESSION["id"])) {
    //header(sprintf("Location: %s", "./login.php"));
    exit();
}
require_once 'api/Fonctions.php';
require_once 'api/essentiel.php';
require_once 'theme_components/theme_style.php';
$nfile = "projets.php";

extract($_GET); $statut = isset($statut)?$statut:0;

if(isset($search)) $q_search = str_replace("'","\'",$search);
$query = (isset($search) && !empty($search))?" and (P.code_projet LIKE '%$q_search%' OR P.sigle_projet LIKE '%$q_search%' OR P.intitule_projet LIKE '%$q_search%' OR P.nom_fonds_fidicuaire LIKE '%$q_search%' OR P.objectif_odd LIKE '%$q_search%' OR P.resultat_undaf LIKE '%$q_search%')":"";
//projets
if(isset($_SESSION["niveau"]) && $_SESSION["niveau"]==0)
$query_projet = $db ->prepare('SELECT * FROM t_projets P WHERE programme='.$_SESSION["programme"].' and statut='.$statut.' '.$query.' ORDER BY code_projet asc');
else 
$query_projet = $db ->prepare('SELECT * FROM t_projets P WHERE programme='.$_SESSION["programme"].' and statut='.$statut.' '.$query.' and (P.agence_lead='.$_SESSION["structure"].' or FIND_IN_SET('.$_SESSION["structure"].',autres_agences_recipiendaires)) ORDER BY code_projet asc');
$query_projet->execute();
$row_projet = $query_projet ->fetchAll();
$totalRows_projet = $query_projet->rowCount();

//structures
$query_structure = $db ->prepare('SELECT id_partenaire as id_structure, nom_partenaire as nom_structure, sigle_partenaire as sigle FROM t_partenaires WHERE FIND_IN_SET(:type_partenaire,type_partenaire)');
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

//Montant projet bailleur
$query_projet_cout = $db ->prepare('SELECT * FROM t_repartition_projet_budget order by projet_bud');
$query_projet_cout->execute();
$row_projet_cout = $query_projet_cout ->fetchAll();
$totalRows_projet_cout = $query_projet_cout->rowCount();
$projet_cout_array = $projet_cout_agence_array=array();
if($totalRows_projet_cout>0){  foreach($row_projet_cout as $row_projet_cout){
if(!isset($projet_cout_array[$row_projet_cout["projet_bud"]])) $projet_cout_array[$row_projet_cout["projet_bud"]]=0; 
if(!isset($projet_cout_agence_array[$row_projet_cout["projet_bud"]][$row_projet_cout["structure_bud"]])) $projet_cout_agence_array[$row_projet_cout["projet_bud"]][$row_projet_cout["structure_bud"]]=0; 

$projet_cout_array[$row_projet_cout["projet_bud"]]+=$row_projet_cout["montant"];
$projet_cout_agence_array[$row_projet_cout["projet_bud"]][$row_projet_cout["structure_bud"]]+=$row_projet_cout["montant"];
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
} }

/*$query_entete = $db ->prepare('SELECT nombre FROM t_config_cadre_resultat_projet WHERE projet=:projet LIMIT 1');
$query_entete->execute(array(':projet' => isset($id)?$id:0));
$row_entete = $query_entete ->fetch();
$totalRows_entete = $query_entete->rowCount();
$code_len = 0;
if($totalRows_entete>0) $code_len = $row_entete["nombre"];*/

$query_liste_activite = $db ->prepare('SELECT count(code) as nbact, A.projet FROM t_cadre_resultat A, t_config_cadre_resultat_projet N  WHERE A.projet=N.projet and A.niveau=N.nombre  group by A.projet asc');
$query_liste_activite->execute();
$row_liste_activite = $query_liste_activite ->fetchAll();
$totalRows_liste_activite = $query_liste_activite->rowCount();
$Nactivite_array = array();
if($totalRows_liste_activite>0){  foreach($row_liste_activite as $row_liste_activite){
$Nactivite_array[$row_liste_activite["projet"]]=$row_liste_activite["nbact"];
} }

$onglet_array = array(0=>"Projets en cours",1=>"Projets clôturés");
?>

<script>
$("#mbreadcrumb").html(<?php $link = ""; $link .= '<div class="btn-circle-zone">'.do_link("view_switcher","","Affichage Liste/Grille","<span id='view_switcher_span' title='Affichage Liste/Grille' class='glyphicon glyphicon-th'></span>","","./","btn btn-success btn-circle mgr-5","$('#view_switcher_span').toggleClass('glyphicon-th');$('#view_switcher_span').toggleClass('glyphicon-th-list');$('.projects$statut').toggle();$('.projects-list$statut').toggle();$('.animate-panel').animatePanel();",0,"",$nfile);
if(isset($_SESSION['niveau']) && $_SESSION['niveau']<2) $link .= do_link("","","Ajout de Projet","<span title='Nouveau Projet' class='glyphicon glyphicon-plus'></span>","simple","./","btn btn-success btn-circle mgr-5","get_content('new_projet.php','','modal-body_add',this.title);",1,"",$nfile);
$link .= '</div>';
echo GetSQLValueString($link, "text"); ?>);
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

<div class="row">
<?php if(isset($search) && !empty($search)) { ?><legend style="text-align: center;" class="<?php echo $Text_Style; ?>">Résultat de la recherche pour : "<i><?php echo $search; ?></i>"</legend><?php } ?>
<?php if($totalRows_projet>0) { ?>
<div class="row projects<?php echo $statut; ?>" style="padding-top:15px;">
<?php $i=0; $row_projet_array = $row_projet; foreach($row_projet as $row_projet) { $id = $row_projet['id_projet']; ?>
<div class="col-lg-6">
                <div class="hpanel" style="border-top: 2px solid <?php echo $Panel_Item_Style; ?>!important;">
                    <div class="panel-body" style="padding-bottom: 0px;<?php echo ($row_projet['statut']==0)?"":"background-color: #DCDCDC;"; ?>">
                        <div class="row" style="text-align: left">
                            <div class="col-sm-7">
                                <h4><a href=""><?php echo $row_projet['code_projet']; ?> : <?php  if(isset($row_projet['nom_abrege'])) echo $row_projet['nom_abrege']; else echo $row_projet['sigle_projet']; ?></a></h4>
                                <p><?php echo $row_projet['intitule_projet']; ?></p>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="project-label">Signature</div>
                                        <small><?php echo date_reg($row_projet['date_signature'],"/"); ?></small>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="project-label">Financement</div>
                                        <small><?php echo $row_projet['modalite_financement']; ?></small>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="project-label" align="center">Budget (<em>$US</em>)<?php if(isset($_SESSION['niveau']) && $_SESSION['niveau']==1 && $_SESSION["structure"]==$row_projet['agence_lead']) echo do_link("","","Coût du projet ".$row_projet['sigle_projet'],"","edit","./","pull-right","get_content('projet_budget.php','id=$id','modal-body_add',this.title,'iframe');",1,"",$nfile); ?></div>
                                        <small><div style="font-weight: bold;">
                                          <div align="center"><span class="btn-info">
                                            <?php  if(isset($projet_cout_array[$id])) echo "&nbsp;&nbsp;<span title=\"".number_format($projet_cout_array[$id], 0, ',', ' ')." USD\">".number_format($projet_cout_array[$id], 0, ',', ' ')."&nbsp;&nbsp;</span>";  else echo ""; ?>
                                            </span></div>
                                        </div>
                                        </small>
                                    </div>
                                   
                                </div>
                            </div>

                            <div class="col-sm-5 project-info">
                                <div class="project-action" align="right">
                                    <div class="btn-group" style="font-size: 18px">
<!--<a href="./prodoc_gen.php?id=<?php echo $id; ?>" class="btn btn-xs btn-default" title="Télécharger le document projet"><i class="fa fa-id-card" alt="Document" title="Télécharger le document projet" style="font-size: 22px;color: #000;"></i></a>-->

<?php echo do_link("","./projet_details.php?id=".$id,"Afficher","","view","./","btn btn-xs btn-default","",0,"",$nfile);

if(isset($_SESSION['niveau']) && (($_SESSION['niveau']==1 && $_SESSION["structure"]==$row_projet['agence_lead']) || $_SESSION['niveau']==0 ) ) {

echo do_link("",$nfile."?statut=".($row_projet['statut']==0?1:0)."&id_actif=".$id,"Supprimer","","active","./","btn btn-xs btn-default","return confirm('Voulez-vous vraiment ".($row_projet['statut']==0?"Désactiver":"Activer")." ce projet ".$row_projet['code_projet']."');",0,"",$nfile);

echo do_link("","","Modifier projet ".$row_projet['code_projet'],"","edit","./","btn btn-xs btn-default","get_content('./new_projet.php','id=$id','modal-body_add',this.title);",1,"",$nfile);
if(isset($_SESSION['niveau']) && $_SESSION['niveau']==0) {
echo do_link("",$nfile."?id_sup=".$id,"Supprimer","","del","./","btn btn-xs btn-default","return confirm('Voulez-vous vraiment supprimer ce projet ".$row_projet['code_projet']."');",0,"",$nfile);
}
} ?>
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

                            <div class="col-sm-12">
                            <div class="row col-sm-4">
                            <div class="project-label">Agence lead</div>
                            <small><?php echo (isset($row_projet['agence_lead']) && isset($liste_structure_array[$row_projet['agence_lead']]))?"<span title=\"".$liste_structure_arrayV[$row_projet['agence_lead']]."\">".$liste_structure_array[$row_projet['agence_lead']]."</span>":"-"; ?>: <strong class="text-info"><?php if(isset($projet_cout_agence_array[$id][$row_projet['agence_lead']])) echo "&nbsp;".number_format($projet_cout_agence_array[$id][$row_projet['agence_lead']], 0, ',', ' ')." $&nbsp;"; ?></strong></small>
                            </div>
                            <div class="row col-sm-4">
                            <div class="project-label">Autres agences</div>
                            <small><?php if(isset($row_projet['autres_agences_recipiendaires']) && !empty($row_projet['autres_agences_recipiendaires'])){ $a = explode(",",$row_projet['autres_agences_recipiendaires']); if(count($a)>0){ $c = array(); foreach($a as $b) { if(isset($projet_cout_agence_array[$id][$b]))  $maar=": <strong class=\"text-info\">&nbsp;".number_format($projet_cout_agence_array[$id][$b], 0, ',', ' ')." $</strong>&nbsp;"; else $maar=""; if(isset($liste_structure_array[$b])) $c[]="<span title=\"".$liste_structure_arrayV[$b]."\">".$liste_structure_array[$b]."".$maar.""."</span>"; } echo count($c)>0?implode('; &nbsp;',$c):"Aucun"; } else echo "Aucun"; } else echo "Aucun"; ?></small>
                            </div>
                            <div class="row col-sm-4">
                            <div class="project-label">Personnes dédiées&nbsp;<?php if(isset($_SESSION['niveau']) && $_SESSION['niveau']==1) echo do_link("","","Personnels dédiés au projet ".$row_projet['sigle_projet'],"","edit","./","pull-right","get_content('projet_users.php','id=$id','modal-body_add',this.title,'iframe');",1,"",$nfile); ?></div>
                            <small><div style="font-weight: bold;"><span class=" ">
                                <?php $a = array(); if(isset($projet_user_array[$id])) $a = explode(",",$projet_user_array[$id]); if(count($a)>0){ $c = array(); foreach($a as $b){ if(isset($User_array[$b])) $c[]="<span title=\"".$User_array[$b]."\">".$Nuser_array[$b]."</span>"; } echo implode('; ',$c); } else echo "Aucune"; ?>
                            </span></div>
                            </small>
                            </div>
                            </div>
                        </div>
                        <div class="row">&nbsp;</div>
                    </div>
                    <div class="panel-footer contact-panel" style="padding: 0px 15px;">
                    <div class="row">
                        <div class="col-md-4 border-right"> <div class="contact-stat"><span>Date de demarrage : </span> <strong><?php echo date_reg($row_projet['date_demarrage'],"/"); ?></strong></div> </div>
                        <div class="col-md-4 border-right"> <div class="contact-stat"><span>Durée : </span> <strong><?php $nombreMois = $row_projet['duree'];$annees = intval($nombreMois / 12);$mois = intval(($nombreMois % 12)); echo "$annees an".($annees>1?"s":"").($mois>0?" $mois mois":""); ?></strong></div> </div>
						
						<div class="col-md-4"> 
						<?php if(isset($tableauTache[$id]) && isset($Nactivite_array[$id]) && $Nactivite_array[$id]>0) $tauxP=$tableauTache[$id]/$Nactivite_array[$id];  else $tauxP=0;?>
						 <div class="project-label" align="left">Avancement (<strong><?php if($tauxP>0) echo number_format($tauxP, 2, ',', ' '); ?>%</strong>)</div>
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
<div class="hpanel projects-list<?php echo $statut; ?>" ><div class="panel-body">

<table class="table table-striped table-bordered table-hover table-responsive table-checkable table-tabletools table-colvis datatable dataTable" id="mtable<?php echo $statut; ?>" >
<thead>
<tr>
<th>Code</th>
<th>Sigle</th>
<th>Intitul&eacute;</th>
<th>Date de signature / demarrage </th>
<th>Durée </th>
<th><span title="Modalité de financement">MF</span></th>
<!--<th>Type de fonds fidicuiare</th>-->
<th>Agence lead</th>
<th>Autres agences</th>
<th>Coût&nbsp;(USD)</th>
<th>Personnes dédiées</th>
<?php //if(isset($_SESSION['niveau']) && $_SESSION['niveau']==1) { ?>
<th width="80" class="no-sort">Actions</th>
<?php //} ?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="">
<?php $i=0; foreach($row_projet_array as $row_projet) { $id = $row_projet['id_projet']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo $row_projet['code_projet']; ?></td>
<td class=" "><?php echo $row_projet['sigle_projet']; ?></td>
<td class=" "><?php echo $row_projet['intitule_projet']; ?></td>
<td class=" "><?php echo date_reg($row_projet['date_signature'],"/")."<br>".date_reg($row_projet['date_demarrage'],"/"); ?></td>
<td class=" "><?php $nombreMois = $row_projet['duree'];$annees = intval($nombreMois / 12);$mois = intval(($nombreMois % 12)); echo "$annees an".($annees>1?"s":"").($mois>0?" $mois mois":""); ?></td>
<td class=" "><?php echo $row_projet['modalite_financement']; ?></td>
<!--<td class=" "><?php //echo $row_projet['type_fonds_fidicuiare']; ?></td>-->
<td class=" "><?php echo (isset($row_projet['agence_lead']) && isset($liste_structure_array[$row_projet['agence_lead']]))?"<span title=\"".$liste_structure_arrayV[$row_projet['agence_lead']]."\">".$liste_structure_array[$row_projet['agence_lead']]."</span>":"-"; ?></td>
<td class=" "><?php if(isset($row_projet['autres_agences_recipiendaires']) && !empty($row_projet['autres_agences_recipiendaires'])){ $a = explode(",",$row_projet['autres_agences_recipiendaires']); if(count($a)>0){ $c = array(); foreach($a as $b) { if(isset($liste_structure_array[$b])) $c[]="<span title=\"".$liste_structure_arrayV[$b]."\">".$liste_structure_array[$b]."</span>"; } echo count($c)>0?implode('; &nbsp;',$c):"Aucun"; } else echo "Aucun"; } else echo "Aucun"; ?></td>
<td class=" ">
<small><div style="font-weight: bold;"><span class=" ">
<?php  if(isset($projet_cout_array[$id])) echo "&nbsp;&nbsp;<span title=\"".$projet_cout_array[$id]." USD\">".$projet_cout_array[$id]."&nbsp;&nbsp;</span>";  else echo ""; ?>
</span></div>
</small>
<?php if(isset($_SESSION['niveau']) && (($_SESSION['niveau']==1 && $_SESSION["structure"]==$row_projet['agence_lead']) || $_SESSION['niveau']==0)) echo do_link("","","Coût du projet ".$row_projet['sigle_projet'],"","edit","./","pull-right","get_content('projet_budget.php','id=$id','modal-body_add',this.title,'iframe');",1,"",$nfile); ?>
</td>
<td class=" ">
<small><div style="font-weight: bold;"><span class=" ">
<?php $a = array(); if(isset($projet_user_array[$id])) $a = explode(",",$projet_user_array[$id]); if(count($a)>0){ $c = array(); foreach($a as $b){ if(isset($User_array[$b])) $c[]="<span title=\"".$Nuser_array[$b]."\">".$User_array[$b]."</span>"; } echo implode('; ',$c); } else echo "Aucune"; ?>
</span></div>
</small>
<?php if(isset($_SESSION['niveau']) && $_SESSION['niveau']<2) echo do_link("","","Personnels dédié au projet ".$row_projet['sigle_projet'],"","edit","./","","get_content('projet_users.php','id=$id','modal-body_add',this.title,'iframe');",1,"",$nfile); ?>
</td>
<?php //if(isset($_SESSION['niveau']) && $_SESSION['niveau']==1) { ?>
<td class=" " align="center">
<!--<a href="./prodoc_gen.php?id=<?php echo $id; ?>" class="" title="Télécharger le document projet" style="margin:0px 5px;"><i class="fa fa-id-card" alt="Document" title="Télécharger le document projet" style="font-size: 22px;color: #000;"></i></a>-->

<?php echo do_link("","./projet_details.php?id=".$id,"Afficher","","view","./","","",0,"margin:0px 5px;",$nfile);

if(isset($_SESSION['niveau']) && (($_SESSION['niveau']==1 && $_SESSION["structure"]==$row_projet['agence_lead']) || $_SESSION['niveau']==0)) {
if(isset($_SESSION['niveau']) && $_SESSION['niveau']==0) {
echo do_link("",$nfile."?statut=".($row_projet['statut']==0?1:0)."&id_actif=".$id,"Supprimer","","active","./","","return confirm('Voulez-vous vraiment ".($row_projet['statut']==0?"Désactiver":"Activer")." ce projet ".$row_projet['code_projet']."');",0,"margin:0px 5px;",$nfile);
}
echo do_link("","","Modifier projet ".$row_projet['code_projet'],"","edit","./","","get_content('./new_projet.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);
if(isset($_SESSION['niveau']) && $_SESSION['niveau']==0) {
echo do_link("",$nfile."?id_sup=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce projet ".$row_projet['code_projet']."');",0,"margin:0px 5px;",$nfile);
}
}
?>
</td>
<?php //} ?>
</tr>
<?php } ?>
</tbody></table>
</div></div>
<?php } else{ ?>
<div class="col-md-12 col-lg-12" style="padding-top:15px;">
    <div <?php echo 'class="hpanel '.$Panel_Style.'"'; ?>>
        <div class="panel-heading">
            <div class="panel-tools">
                <a class="showhide"><i class="fa fa-chevron-up"></i></a>
            </div>
          <span class="text-primary"><i class="fa fa-reorder"></i> Projets</span>
        </div>
        <div class="panel-body">
            <h1 align="center">Aucun <?php echo (isset($search) && !empty($search))?"résultat":" ".(isset($onglet_array[$statut])?$onglet_array[$statut]:"projet saisi dans ce programme"); ?> !</h1>
        </div>
    </div>
</div>
<?php } ?>
</div>