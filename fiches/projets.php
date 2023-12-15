<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
if (!isset ($_SESSION["id"])) {
    header(sprintf("Location: %s", "./login.php"));  exit();
}
include_once 'api/configuration.php';
$config = new Config;   

extract($_GET); $statut = isset($statut)?$statut:0;
$dir = "./images/projet/";
if(!is_dir($dir)) mkdir($dir);
$url_site='./map/projet/'; //Adresse où se trouve le fichier upload.
$extensions_autorisees=array('shp'); //Extensions autorisées ,'csv'
if(!is_dir($url_site)) mkdir($url_site);
if ((isset($id_sup) && !empty($id_sup))) {

    $insertSQL = $db->prepare('DELETE FROM t_projets WHERE id_projet=:id_projet');
    $Result1 = $insertSQL->execute(array(':id_projet' => $id_sup));

    $insertGoTo = $_SERVER['PHP_SELF'];
    //Suppression du logo
    if($Result1 && file_exists($dir."img_".($id_sup).".jpg"))
    {
        unlink($dir."img_".($id_sup).".jpg");
    }
    //fin
    //Suppression du shapfile
    if($Result1 && file_exists($url_site.($id_sup).".shp"))
    {
        unlink($url_site.($id_sup).".shp");
    }
    //fin
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
}
//Statut
if ((isset($id_actif) && !empty($id_actif))) {

    $statut = isset($statut)?$statut:1;
    $insertSQL = $db->prepare('UPDATE t_projets SET statut=:statut WHERE id_projet=:id_projet');
    $Result1 = $insertSQL->execute(array(':statut' => $statut, ':id_projet' => $id_actif));

    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    $insertGoTo .= "&statut=$statut";
    header(sprintf("Location: %s", $insertGoTo)); exit();
}

extract($_POST);
if ((isset($MM_form)) && ($MM_form == "form1"))
{ //projets
    $date=date("Y-m-d H:i:s"); $personnel = $_SESSION["id"];

  if ((isset($MM_insert)) && $MM_insert == "MM_insert") {

      $insertSQL = $db->prepare('INSERT INTO t_projets (programme, `code_projet`,  `sigle_projet`, nom_abrege,  `intitule_projet`,  `date_signature`,  `date_demarrage`,  `modalite_financement`,  `nom_fonds_fidicuaire`,  `agence_lead`,  `autres_agences_recipiendaires`, `autres_partenaires_execution`, bailleur, `fenetre_pbf`, `zone`,  `nature`,  `duree`,  `processus_consultation`,  `pourcentage_budget_genre`,  `description_marqueur_genre`,  `domaine_intervention_prioritaire`,  `niveau_risque`,  `objectif_odd`, `couleur_shp`, `description_projet`, `enregistrer_par`) VALUES (:programme, :code_projet, :sigle_projet, :nom_abrege, :intitule_projet, :date_signature, :date_demarrage, :modalite_financement, :nom_fonds_fidicuaire, :agence_lead, :autres_agences_recipiendaires, :autres_partenaires_execution, :bailleur, :fenetre_pbf, :zone, :nature, :duree, :processus_consultation, :pourcentage_budget_genre, :description_marqueur_genre, :domaine_intervention_prioritaire, :niveau_risque, :objectif_odd, :couleur, :description_projet, :enregistrer_par)');
      $Result1 = $insertSQL->execute(array(
        ':programme' => $_SESSION["programme"],
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
        ':description_marqueur_genre' => $description_marqueur_genre,
        ':domaine_intervention_prioritaire' => $domaine_intervention_prioritaire,
        ':niveau_risque' => $niveau_risque,
        ':objectif_odd' => $objectif_odd,
        ':couleur' => $couleur,
        ':description_projet' => $description_projet,
        ':enregistrer_par' => $personnel
      ));

      $id = $db->lastInsertId();
      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
      if($Result1 && isset($_FILES['fichier']['name']))
      {
        $ext = substr(strrchr($_FILES['fichier']['name'], "."), 1);
        if(in_array($ext,$extensions_autorisees))
        {
            if(isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0)
            {
                $inputFileName=$url_site.$id.".shp";
                move_uploaded_file($_FILES['fichier']['tmp_name'],$inputFileName);
                $insertGoTo .= "&import=ok";
            }
            else $insertGoTo .= "&import=no";
        } else $insertGoTo .= "&import=no";
      } else $insertGoTo .= "&import=no";
      header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($MM_delete) && intval($MM_delete)>0)) {
    $id = $MM_delete;
    $insertSQL = $db->prepare('DELETE FROM t_projets WHERE id_projet=:id_projet');
    $Result1 = $insertSQL->execute(array(':id_projet' => $id));

    $insertGoTo = $_SERVER['PHP_SELF'];
    //Suppression du logo
    if($Result1 && file_exists($dir."img_".($id).".jpg"))
    {
        unlink($dir."img_".($id).".jpg");
    }
    //fin
    //Suppression du shapfile
    if($Result1 && file_exists($url_site.($id).".shp"))
    {
        unlink($url_site.($id).".shp");
    }
    //fin
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($MM_update) && intval($MM_update)>0)) {
    $id = $MM_update;
    $insertSQL = $db->prepare('UPDATE t_projets SET programme=:programme, code_projet=:code_projet, sigle_projet=:sigle_projet, nom_abrege=:nom_abrege, intitule_projet=:intitule_projet, date_signature=:date_signature, date_demarrage=:date_demarrage, modalite_financement=:modalite_financement, nom_fonds_fidicuaire=:nom_fonds_fidicuaire, agence_lead=:agence_lead, autres_agences_recipiendaires=:autres_agences_recipiendaires, autres_partenaires_execution=:autres_partenaires_execution,  bailleur=:bailleur, fenetre_pbf=:fenetre_pbf, zone=:zone, nature=:nature, duree=:duree, processus_consultation=:processus_consultation, pourcentage_budget_genre=:pourcentage_budget_genre, description_marqueur_genre=:description_marqueur_genre, domaine_intervention_prioritaire=:domaine_intervention_prioritaire, niveau_risque=:niveau_risque, objectif_odd=:objectif_odd, couleur_shp=:couleur, description_projet=:description_projet, date_modification=:date_modification, modifier_par=:modifier_par WHERE id_projet=:id_projet');
      $Result1 = $insertSQL->execute(array(
	    ':programme' => $_SESSION["programme"],
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
        ':description_marqueur_genre' => $description_marqueur_genre,
        ':domaine_intervention_prioritaire' => $domaine_intervention_prioritaire,
        ':niveau_risque' => $niveau_risque,
        ':objectif_odd' => $objectif_odd,
        ':couleur' => $couleur,
        ':description_projet' => $description_projet,
        ':date_modification' => $date,
        ':modifier_par' => $personnel,
        ':id_projet' => $id
      ));

    $insertGoTo = (isset($page))?$page:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    if($Result1 && isset($_FILES['fichier']['name']))
    {
        $ext = substr(strrchr($_FILES['fichier']['name'], "."), 1);
        if(in_array($ext,$extensions_autorisees))
        {
                if(isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0)
                {
                        $inputFileName=$url_site.$id.".shp";
                        move_uploaded_file($_FILES['fichier']['tmp_name'],$inputFileName);
                        $insertGoTo .= "&import=ok";
                }
                else $insertGoTo .= "&import=no";
        } else $insertGoTo .= "&import=no";
    } else $insertGoTo .= "&import=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

if (isset($MM_form) && $MM_form == "form0")
{
    //if (isset($MM_update)) {

        include "api/class.upload.php";
        $handle = new upload($_FILES['photo']);
        if ($handle->uploaded && !empty($id))
        {
            //resize to 250 px
            $handle->file_new_name_body = 'img_'.$id;
            $handle->image_resize = true;
            $handle->image_x = 250;
            $handle->image_y = 250;
            $handle->file_auto_rename = true;
            $handle->image_ratio = true;
            $handle->image_convert = 'jpg';
            $handle->file_overwrite = true;
            $handle->process('./images/projet/');   /*
            if ($handle->processed)
            {
                $img_full_name = $handle->file_dst_name_body.".".$handle->file_dst_name_ext;
            }  */
            //terminé
            $handle->clean();
        }
        $insertGoTo = (isset($page))?$page:$_SERVER['PHP_SELF'];
        if($handle->processed) $insertGoTo .= "?insert=ok";
        else $insertGoTo .= "?insert=no";
        header(sprintf("Location: %s", $insertGoTo));  exit();
    //}
}
$onglet_array = array(0=>"Projets en cours",1=>"Projets clôturés");
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <title><?php print $config->sitename;?></title>
    <link rel="shortcut icon" type="image/ico" href="<?php print $config->icon_folder;?>/favicon.ico" />
    <meta name="keywords" content="<?php print $config->MetaKeys;?>" />
    <meta name="description" content="<?php print $config->MetaDesc;?>" />
    <meta name="author" content="<?php print $config->MetaAuthor;?>" />

    <!-- Vendor styles -->
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.css" />
    <link rel="stylesheet" href="vendor/metisMenu/dist/metisMenu.css" />
    <link rel="stylesheet" href="vendor/animate.css/animate.css" />
    <link rel="stylesheet" href="vendor/bootstrap/dist/css/bootstrap.css" />
    <link rel="stylesheet" href="vendor/sweetalert/lib/sweet-alert.css" />
    <link rel="stylesheet" href="vendor/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" />
    <link rel="stylesheet" href="vendor/select2-3.5.2/select2.css" />
    <link rel="stylesheet" href="vendor/summernote/dist/summernote.css" />
    <link rel="stylesheet" href="vendor/bootstrap-colorpicker/bootstrap-colorpicker.min.css" />
    <link rel="stylesheet" href="vendor/select2-bootstrap/select2-bootstrap.css" />
    <link rel="stylesheet" href="vendor/datatables.net-bs/css/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" href="vendor/datatables.net-bs/css/responsive.dataTables.min.css" />
    <link rel="stylesheet" href="vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css" />

    <!-- App styles -->
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/helper.css" />

    <!-- App custom styles -->
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/style_fst.css">

    <!-- Vendor scripts -->
    <script src="vendor/jquery/dist/jquery.min.js"></script>
    <script src="vendor/jquery-ui/jquery-ui.min.js"></script>
    <script src="vendor/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="vendor/jquery-flot/jquery.flot.js"></script>
    <script src="vendor/jquery-flot/jquery.flot.resize.js"></script>
    <script src="vendor/jquery-flot/jquery.flot.pie.js"></script>
    <script src="vendor/flot.curvedlines/curvedLines.js"></script>
    <script src="vendor/jquery.flot.spline/index.js"></script>
    <script src="vendor/metisMenu/dist/metisMenu.min.js"></script>
    <script src="vendor/iCheck/icheck.min.js"></script>
    <script src="vendor/peity/jquery.peity.min.js"></script>
    <script src="vendor/sparkline/index.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="vendor/select2-3.5.2/select2.min.js"></script>
    <script type="text/javascript" src="vendor/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
    <script src="vendor/summernote/dist/summernote.min.js"></script>
    <script src="vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="vendor/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.fr.min.js"></script>

    <!-- DataTables -->
    <script src="vendor/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="vendor/datatables.net-bs/js/dataTables.responsive.min.js"></script>
    <!-- DataTables buttons scripts -->
    <script src="vendor/pdfmake/build/pdfmake.min.js"></script>
    <script src="vendor/pdfmake/build/vfs_fonts.js"></script>
    <script src="vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>

    <!-- App scripts -->
    <script src="scripts/homer.js"></script>
</head>
<body class="fixed-navbar fixed fixed-footer sidebar-scroll">
    <?php require_once "./theme_components/header.php"; ?>
    <?php require_once "./theme_components/main-menu.php"; ?>
<!-- Main Wrapper -->
<div id="wrapper">
<?php $vprojet=1; require_once "./theme_components/sub-header.php"; ?>
    <div class="content animate-panel">
        <div class="row">
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

        </div>
    </div>
    <?php require_once "./theme_components/footer.php"; ?>
</div>

</body>
</html>