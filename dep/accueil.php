<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & DÃ©veloppement: BAMASOFT */
///////////////////////////////////////////////
  include_once 'system/configuration.php';
  $config = new Config;

$personnel = $_SESSION["clp_id"];  ini_set("display_errors",1);
$date = date("Y-m-d");
extract($_GET); $statut = isset($statut)?$statut:0;

$dir = "./images/projet/";
if(!is_dir($dir)) mkdir($dir);
if ((isset($id_sup) && !empty($id_sup))) {

    $insertSQL = $db->prepare('DELETE FROM projets WHERE id_projet=:id_projet');
    $Result1 = $insertSQL->execute(array(':id_projet' => $id_sup));

    $insertGoTo = $_SERVER['PHP_SELF'];
    //Suppression du logo
    if($Result1 && file_exists($dir."img_".($id_sup).".jpg"))
    {
        unlink($dir."img_".($id_sup).".jpg");
    }
    //fin
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
}
//Statut
if ((isset($id_actif) && !empty($id_actif))) {

    $statut = isset($statut)?$statut:1;
    $insertSQL = $db->prepare('UPDATE projets SET statut=:statut WHERE id_projet=:id_projet');
    $Result1 = $insertSQL->execute(array(':statut' => $statut, ':id_projet' => $id_actif));

    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    $insertGoTo .= "&statut=$statut";
    header(sprintf("Location: %s", $insertGoTo)); exit();
}

extract($_POST);
if ((isset($MM_form)) && ($MM_form == "form1"))
{ //projets
    $date=date("Y-m-d H:i:s"); $bailleur=array("FIDA");  //$personnel = $_SESSION["id"];

  if ((isset($MM_insert)) && $MM_insert == "MM_insert") {

      $insertSQL = $db->prepare('INSERT INTO projets (programme, `code_projet`,  `sigle_projet`, nom_abrege,  `intitule_projet`,  `date_signature`,  `date_demarrage`,  `modalite_financement`,  `nom_fonds_fidicuaire`,  `agence_lead`,  `autres_agences_recipiendaires`, `autres_partenaires_execution`, bailleur, `fenetre_pbf`, `zone`,  `nature`,  `duree`,  `processus_consultation`,  `pourcentage_budget_genre`, `description_projet`, `enregistrer_par`) VALUES (:programme, :code_projet, :sigle_projet, :nom_abrege, :intitule_projet, :date_signature, :date_demarrage, :modalite_financement, :nom_fonds_fidicuaire, :agence_lead, :autres_agences_recipiendaires, :autres_partenaires_execution, :bailleur, :fenetre_pbf, :zone, :nature, :duree, :processus_consultation, :pourcentage_budget_genre, :description_projet, :enregistrer_par)');
      $Result1 = $insertSQL->execute(array(
        ':programme' => $programme,
		':code_projet' => $code_projet,
        ':sigle_projet' => $sigle_projet,
		':nom_abrege' => $nom_abrege,
        ':intitule_projet' => $intitule_projet,
        ':date_signature' => implode('-',array_reverse(explode('/',$date_signature))),
        ':date_demarrage' => implode('-',array_reverse(explode('/',$date_demarrage))),
        ':modalite_financement' => $modalite_financement,
        //':type_fonds_fidicuiare' => $type_fonds_fidicuiare,
        ':nom_fonds_fidicuaire' => $nom_fonds_fidicuaire,
        ':agence_lead' => $agence_lead,
        ':autres_agences_recipiendaires' => implode(',',$autres_agences_recipiendaires),
        ':autres_partenaires_execution' => implode(',',$autres_partenaires_execution),
		':bailleur' => implode(',',$bailleur),
		':fenetre_pbf' => implode(',',$fenetre_pbf),
        ':zone' => $zone,
        ':nature' => $nature,
        ':duree' => $duree,
        ':processus_consultation' => $processus_consultation,
        ':pourcentage_budget_genre' => $pourcentage_budget_genre,
        /*':description_marqueur_genre' => $description_marqueur_genre,
        ':domaine_intervention_prioritaire' => $domaine_intervention_prioritaire,
        ':niveau_risque' => $niveau_risque,
        ':objectif_odd' => $objectif_odd,
        ':couleur' => $couleur,
        ':zone_localite' => implode(',',$zone_localite),
        ':thematique' => implode(',',$thematique), */
        ':description_projet' => $description_projet,
        ':enregistrer_par' => $personnel
      ));

      $id = $db->lastInsertId();
      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";

      header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($MM_delete) && intval($MM_delete)>0)) {
    $id = $MM_delete;
    $insertSQL = $db->prepare('DELETE FROM projets WHERE id_projet=:id_projet');
    $Result1 = $insertSQL->execute(array(':id_projet' => $id));

    $insertGoTo = $_SERVER['PHP_SELF'];
    //Suppression du logo
    if($Result1 && file_exists($dir."img_".($id).".jpg"))
    {
        unlink($dir."img_".($id).".jpg");
    }
    //fin
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($MM_update) && intval($MM_update)>0)) {
    $id = $MM_update;
    $insertSQL = $db->prepare('UPDATE projets SET programme=:programme, code_projet=:code_projet, sigle_projet=:sigle_projet, nom_abrege=:nom_abrege, intitule_projet=:intitule_projet, date_signature=:date_signature, date_demarrage=:date_demarrage, modalite_financement=:modalite_financement, nom_fonds_fidicuaire=:nom_fonds_fidicuaire, agence_lead=:agence_lead, autres_agences_recipiendaires=:autres_agences_recipiendaires, autres_partenaires_execution=:autres_partenaires_execution,  bailleur=:bailleur, fenetre_pbf=:fenetre_pbf, zone=:zone, nature=:nature, duree=:duree, processus_consultation=:processus_consultation, pourcentage_budget_genre=:pourcentage_budget_genre, description_projet=:description_projet, date_modification=:date_modification, modifier_par=:modifier_par WHERE id_projet=:id_projet');
      $Result1 = $insertSQL->execute(array(
	    ':programme' => $programme,
        ':code_projet' => $code_projet,
        ':sigle_projet' => $sigle_projet,
		':nom_abrege' => $nom_abrege,
        ':intitule_projet' => $intitule_projet,
        ':date_signature' => implode('-',array_reverse(explode('/',$date_signature))),
        ':date_demarrage' => implode('-',array_reverse(explode('/',$date_demarrage))),
        ':modalite_financement' => $modalite_financement,
       // ':type_fonds_fidicuiare' => $type_fonds_fidicuiare,
        ':nom_fonds_fidicuaire' => $nom_fonds_fidicuaire,
        ':agence_lead' => $agence_lead,
        ':autres_agences_recipiendaires' => implode(',',$autres_agences_recipiendaires),
        ':autres_partenaires_execution' => implode(',',$autres_partenaires_execution),
     	':bailleur' => implode(',',$bailleur),
		':fenetre_pbf' => implode(',',$fenetre_pbf),
        ':zone' => $zone,
        ':nature' => $nature,
        ':duree' => $duree,
        ':processus_consultation' => $processus_consultation,
        ':pourcentage_budget_genre' => $pourcentage_budget_genre,
        /*':description_marqueur_genre' => $description_marqueur_genre,
        ':domaine_intervention_prioritaire' => $domaine_intervention_prioritaire,
        ':niveau_risque' => $niveau_risque,
        ':objectif_odd' => $objectif_odd,
        ':couleur' => $couleur,
        ':zone_localite' => implode(',',$zone_localite),
        ':thematique' => implode(',',$thematique), */
        ':description_projet' => $description_projet,
        ':date_modification' => $date,
        ':modifier_par' => $personnel,
        ':id_projet' => $id
      ));

    $insertGoTo = (isset($page))?$page:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";

    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

if (isset($MM_form) && $MM_form == "form0")
{
    //if (isset($MM_update)) {

        include "./includes/class.upload.php";
        $handle = new upload($_FILES['photo']);
        if ($handle->uploaded && !empty($id))
        {
            //resize to 250 px
            $handle->file_new_name_body = 'img_'.$id;
            $handle->image_resize = true;
            $handle->file_max_size = 10240;
            $handle->image_x = 50;
            $handle->image_y = 50;
            $handle->file_auto_rename = true;
            $handle->image_ratio = true;
            $handle->image_convert = 'png';
            $handle->file_overwrite = true;
            $handle->process('./images/projet/');   /*
            if ($handle->processed)
            {
                $img_full_name = $handle->file_dst_name_body.".".$handle->file_dst_name_ext;
            }  */
            //terminÃ©
            $handle->clean();
        }         
        $insertGoTo = (isset($page))?$page:$_SERVER['PHP_SELF'];
        if($handle->processed) $insertGoTo .= "?insert=ok";
        else $insertGoTo .= "?insert=no";
        header(sprintf("Location: %s", $insertGoTo));  exit();
    //}
}

$onglet_array = array(0=>"Projets en cours",1=>"Projets cl&ocirc;tur&eacute;s");
?>



<!-- Site contenu ici -->
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Projets </h4>
<?php
echo do_link("view_switcher","javascript:void(0);","Affichage Liste/Grille","<span id='view_switcher_span' title='Affichage Liste/Grille' class='glyphicon glyphicon-th'></span>","","./","pull-right p11","$('#view_switcher_span').toggleClass('glyphicon-th');$('#view_switcher_span').toggleClass('glyphicon-th-list');$('.projects$statut').toggle();$('.projects-list$statut').toggle();",0,"",$nfile); ?>
</div>
<div class="widget-content" style="display: block;">
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
</style>

<script>
$().ready(function() {
        init_tabs();
});

function show_tab(tab) {
        //if (!tab.html()) {
                tab.load(tab.attr('data-target'));
        //}
}

function init_tabs() {
        show_tab($('.tab-pane.active'));
        $('a[data-toggle="tab"]').click('show', function(e) {
                tab = $('#' + $(e.target).attr('href').substr(1));
                show_tab(tab);
        });
}
</script>

<div class="tabbable tabbable-custom" >
    <ul class="nav nav-tabs" >
    <?php $k=1; foreach($onglet_array as $j=>$l){ ?>
        <li title="" class="<?php echo $j==$statut?"active":""; ?>"><a href="#tab_feed_<?php echo $j; ?>" data-toggle="tab"><?php echo $l; ?></a></li>
    <?php $k++; } ?>
    </ul>
    <div class="tab-content" style="background-color: #FFF;padding-left:15px;padding-right:15px;">
    <?php $k=1; foreach($onglet_array as $j=>$l){ ?>
    <div class="tab-pane <?php echo $j==$statut?"active":""; ?>" id="tab_feed_<?php echo $j; ?>" data-target="./projets_content.php?statut=<?php echo $j.(isset($search)?"&search=$search":''); ?>"></div>
    <?php $k++; }  ?>
    </div>
</div>

<div class="clear h0">&nbsp;</div>
</div>
<!-- Fin Site contenu ici -->
            </div>
        </div>
        </div>
        </div>
    </div> <?php include_once 'modal_add.php'; ?>

