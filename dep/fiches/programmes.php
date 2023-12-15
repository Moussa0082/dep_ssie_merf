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

extract($_GET);
if ((isset($id_sup) && !empty($id_sup))) {

    $insertSQL = $db->prepare('DELETE FROM t_programmes WHERE id_programme=:id_programme');
    $Result1 = $insertSQL->execute(array(':id_programme' => $id_sup));

    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
}

extract($_POST);
if ((isset($MM_form)) && ($MM_form == "form1"))
{ //Programmes
    $date=date("Y-m-d"); $personnel = $_SESSION["id"];

  if ((isset($MM_insert)) && $MM_insert == "MM_insert") {

      $insertSQL = $db->prepare('INSERT INTO t_programmes (code_programme, nom_programme, sigle_programme, pays, vision, objectif, date_debut, date_fin, budget_estimatif, type_programme, enregistrer_par) VALUES (:code_programme, :nom_programme, :sigle_programme, :pays, :vision, :objectif, :date_debut, :date_fin, :budget_estimatif, :type_programme, :enregistrer_par)');
      $Result1 = $insertSQL->execute(array(
        ':code_programme' => $code_programme,
        ':nom_programme' => $nom_programme,
        ':sigle_programme' => $sigle_programme,
        ':pays' => $pays,
        ':vision' => $vision,
        ':objectif' => $objectif,
        ':date_debut' => implode('-',array_reverse(explode('/',$date_debut))),
        ':date_fin' => implode('-',array_reverse(explode('/',$date_fin))),
        ':budget_estimatif' => isset($budget_estimatif)?$budget_estimatif:0,
        ':type_programme' => $type_programme,
        ':enregistrer_par' => $personnel
      ));

      //$id = $db->lastInsertId();
      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
      header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($MM_delete) && intval($MM_delete)>0)) {
    $id = $MM_delete;
    $insertSQL = $db->prepare('DELETE FROM t_programmes WHERE id_programme=:id_programme');
    $Result1 = $insertSQL->execute(array(':id_programme' => $id));

    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($MM_update) && intval($MM_update)>0)) {
    $id = $MM_update;
    $insertSQL = $db->prepare('UPDATE t_programmes SET code_programme=:code_programme, nom_programme=:nom_programme, sigle_programme=:sigle_programme, pays=:pays, vision=:vision, objectif=:objectif, date_debut=:date_debut, date_fin=:date_fin, budget_estimatif=:budget_estimatif, type_programme=:type_programme, date_modification=:date_modification, modifier_par=:modifier_par WHERE id_programme=:id_programme');
      $Result1 = $insertSQL->execute(array(
        ':code_programme' => $code_programme,
        ':nom_programme' => $nom_programme,
        ':sigle_programme' => $sigle_programme,
        ':pays' => $pays,
        ':vision' => $vision,
        ':objectif' => $objectif,
        ':date_debut' => implode('-',array_reverse(explode('/',$date_debut))),
        ':date_fin' => implode('-',array_reverse(explode('/',$date_fin))),
        ':budget_estimatif' => isset($budget_estimatif)?$budget_estimatif:0,
        ':type_programme' => $type_programme,
        ':date_modification' => $date,
        ':modifier_par' => $personnel,
        ':id_programme' => $id
      ));

    $insertGoTo = (isset($page))?$page:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

$type_programme = array('Nouveau','En cours','Clôturé');
$statut_programme = array('Programme(s) cadre(s)','Autre(s) programme(s)');
$devise_programme = "$ (US)";


//Montant projet bailleur
$query_projet_cout = $db ->prepare('SELECT sum(montant) as montant, programme FROM t_repartition_projet_budget, t_projets where id_projet=projet_bud group by programme');
$query_projet_cout->execute();
$row_projet_cout = $query_projet_cout ->fetchAll();
$totalRows_projet_cout = $query_projet_cout->rowCount();
$projet_cout_array = array();
if($totalRows_projet_cout>0){  foreach($row_projet_cout as $row_projet_cout){
$projet_cout_array[$row_projet_cout["programme"]]=$row_projet_cout["montant"]; 
} }
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
    <link rel="stylesheet" href="vendor/datatables.net-bs/css/dataTables.bootstrap.min.css" />
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
    <script src="vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="vendor/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.fr.min.js"></script>

    <!-- DataTables -->
    <script src="vendor/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
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
} .table tbody tr td {vertical-align: middle; } .project-label small {font-weight: 100; }.type_prog{margin : -10px 0 10px 0;}
</style>

<script>
$("#mbreadcrumb").html(<?php $link = ""; if(isset($_SESSION['niveau']) && $_SESSION['niveau']==0){ if(isset($_SESSION['niveau']) && $_SESSION['niveau']==0) $link .= '<div class="btn-circle-zone">'.do_link("","","Ajout de Programme","<span title='Nouveau Programme' class='glyphicon glyphicon-plus'></span>","simple","./","btn btn-success btn-circle mgr-5","get_content('new_programme.php','','modal-body_add',this.title);",1,"",$nfile);
$link .= '</div>';
echo GetSQLValueString($link, "text"); } ?>);
</script>

<?php
//structures
//if(isset($_SESSION["niveau"]) && $_SESSION["niveau"]==0){
$query_programme = $db ->prepare('SELECT * FROM t_programmes P WHERE P.type_programme=:type_programme ORDER BY date_enregistrement desc');
$query_programme->execute(array(':type_programme' => 0));//}
/*else{
$query_programme = $db ->prepare('SELECT * FROM t_programmes P WHERE P.type_programme=:type_programme and P.id_programme IN (SELECT Pp.programme FROM t_projets Pp, t_projet_users D WHERE Pp.id_projet=D.projet_up and D.structure_up=:structure and FIND_IN_SET(:user,D.personnel_up) ) ORDER BY date_enregistrement desc');
$query_programme->execute(array(':type_programme' => 0,':structure' => $_SESSION["structure"],':user' => $_SESSION["id"]));
}*/
$row_programme = $query_programme ->fetchAll();
$totalRows_programme = $query_programme->rowCount();
?>

<?php if($totalRows_programme>0) { ?><legend style="text-align: center;" class="<?php echo $Text_Style; ?>"><?php echo $statut_programme[0]; ?></legend>
<div class="row projects">
<?php $i=0; foreach($row_programme as $row_programme) { $id = $row_programme['id_programme']; ?>
<div class="col-lg-6">
                <div class="hpanel" style="border-top: 2px solid <?php echo $Panel_Item_Style; ?>!important;">
                    <div class="panel-body" style="<?php echo $row_programme['type_programme']==2?"background-color: #DCDCDC;":""; ?>">
                       
                        <div class="row" style="text-align: left">
                            <div class="col-sm-8">
                                <h4><a href=""><?php echo $row_programme['code_programme']; ?> : <?php echo $row_programme['sigle_programme']; ?></a></h4>
                                <p><span class="project-label">Nom</span> : <?php echo $row_programme['nom_programme']; ?></p>
                                <p><span class="project-label">Objectif</span> : <?php echo $row_programme['objectif']; ?></p>
                                <!--<p><span class="project-label">Vision</span> : <?php //echo $row_programme['vision']; ?></p>-->
                                <p><span class="project-label">Pays</span> : <?php echo $row_programme['pays']; ?></p>
                            </div>
                            <div class="col-sm-4 project-info">
                                <div class="project-action">
                                    <div class="btn-group" style="font-size: 15px">
<?php if(isset($_SESSION['niveau']) && $_SESSION['niveau']==0) {

echo do_link("","","Modifier programme ".$row_programme['sigle_programme'],"","edit","./","btn btn-xs btn-default","get_content('./new_programme.php','id=$id','modal-body_add',this.title);",1,"",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id,"Supprimer","","del","./","btn btn-xs btn-default","return confirm('Voulez-vous vraiment supprimer ce programme ".$row_programme['sigle_programme']."');",0,"",$nfile);
} ?>
                                    </div>
                                </div>
                                <div class="project-value">
                                    <h4 class="project-label">Budget mobilisé</h4>
                                <h4 class="<?php echo $Text_Style; ?>">
                                 <?php if(isset($projet_cout_array[$id])) echo number_format($projet_cout_array[$id], 0, ',', ' ')." $devise_programme"; ?>
                                </h4>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="project-label">Date de début</div>
                                        <small><?php echo date_reg($row_programme['date_debut'],"/"); ?></small>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="project-label">Date de fin</div>
                                        <small><?php echo date_reg($row_programme['date_fin'],"/"); ?></small>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="project-label">Budget estimatif</div>
                                        <small class="<?php echo $Text_Style; ?>"><b><?php echo $row_programme['budget_estimatif']>0?number_format($row_programme['budget_estimatif'], 0, ',', ' ')." $devise_programme":''; ?></b></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer"></div>
            </div>
</div>
<?php } ?></div><div class="clear h0">&nbsp;</div><?php }

//structures
//if(isset($_SESSION["niveau"]) && $_SESSION["niveau"]==0){
$query_programme = $db ->prepare('SELECT * FROM t_programmes P WHERE P.type_programme=:type_programme ORDER BY date_enregistrement desc');
$query_programme->execute(array(':type_programme' => 1));//}
/*else{
$query_programme = $db ->prepare('SELECT * FROM t_programmes P WHERE P.type_programme=:type_programme and P.id_programme IN (SELECT Pp.programme FROM t_projets Pp, t_projet_users D WHERE Pp.id_projet=D.projet_up and D.structure_up=:structure and FIND_IN_SET(:user,D.personnel_up) ) ORDER BY date_enregistrement desc');
$query_programme->execute(array(':type_programme' => 1,':structure' => $_SESSION["structure"],':user' => $_SESSION["id"]));
}*/
$row_programme = $query_programme ->fetchAll();
$totalRows_programme1 = $query_programme->rowCount();
?>

<?php if($totalRows_programme1>0) { ?><legend style="text-align: center;" class="<?php echo $Text_Style; ?>"><?php echo $statut_programme[1]; ?></legend>
<div class="row projects">
<?php $i=0; foreach($row_programme as $row_programme) { $id = $row_programme['id_programme']; ?>
<div class="col-lg-6">
                <div class="hpanel" style="border-top: 2px solid <?php echo $Panel_Item_Style; ?>!important;">
                    <div class="panel-body" style="<?php echo $row_programme['type_programme']==2?"background-color: #DCDCDC;":""; ?>">
                        
                        <div class="row" style="text-align: left">
                            <div class="col-sm-8">
                                <h4><a href=""><?php echo $row_programme['code_programme']; ?> : <?php echo $row_programme['sigle_programme']; ?></a></h4>
                                <p><span class="project-label">Nom</span> : <?php echo $row_programme['nom_programme']; ?></p>
                                <p><span class="project-label">Objectif</span> : <?php echo $row_programme['objectif']; ?></p>
                                <!--<p><span class="project-label">Vision</span> : <?php //echo $row_programme['vision']; ?></p>-->
                                <p><span class="project-label">Pays</span> : <?php echo $row_programme['pays']; ?></p>
                            </div>
                            <div class="col-sm-4 project-info">
                                <div class="project-action">
                                    <div class="btn-group" style="font-size: 15px">
<?php if(isset($_SESSION['niveau']) && $_SESSION['niveau']==0) {

echo do_link("","","Modifier programme ".$row_programme['sigle_programme'],"","edit","./","","get_content('./new_programme.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce programme ".$row_programme['sigle_programme']."');",0,"margin:0px 5px;",$nfile);
} ?>
                                    </div>
                                </div>
                                <div class="project-value">&nbsp;</div>
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="project-label">Date de début</div>
                                        <small><?php echo date_reg($row_programme['date_debut'],"/"); ?></small>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="project-label">Date de fin</div>
                                        <small><?php echo date_reg($row_programme['date_fin'],"/"); ?></small>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="project-label">Statut</div>
                                        <small><?php echo isset($statut_programme[$row_programme['type_programme']])?$statut_programme[$row_programme['type_programme']]:"NaN"; ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer"></div>
            </div>
</div>
<?php } ?></div><?php }

if($totalRows_programme<=0 && $totalRows_programme1<=0){ ?>
<div class="col-md-12 col-lg-12">
    <div <?php echo 'class="hpanel '.$Panel_Style.'"'; ?>>
        <div class="panel-heading">
            <div class="panel-tools">
                <a class="showhide"><i class="fa fa-chevron-up"></i></a>
            </div>
          <span class="text-primary"><i class="fa fa-reorder"></i> Programmes</span>
        </div>
        <div class="panel-body">
            <h1 align="center">Aucune programme saisi !</h1>
        </div>
    </div>
</div>
<?php } ?>


        </div>
    </div>
    <?php require_once "./theme_components/footer.php"; ?>
</div>

</body>
</html>