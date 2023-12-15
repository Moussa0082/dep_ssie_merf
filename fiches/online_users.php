
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

//Utilisateur connecté
$query_personnel = $db->query("SELECT * FROM t_users, t_connecter where statut=0 and user_id=id_user and date_deconnexion='0000-00-00 00:00:00' and id_user<>'".$_SESSION['id']."' GROUP BY id_user ORDER BY `date_connexion` desc");
$row_personnel = $query_personnel ->fetchAll();
$totalRows_personnel = $query_personnel->rowCount();


$liste_niveau_array = array('Aucun','Utilisateur','Visiteur');

$q = $db->query("SELECT F.*, S.sigle, S.nom_structure, T.nom_type_fonction FROM t_fonction F, t_structures S, t_type_fonction T WHERE F.structure=S.id_structure and F.type_fonction=T.id_type_fonction");
$row_fonction = $q ->fetchAll();
$totalRows_fonction = $q->rowCount();
$fonction_array = $fonction_desc_array = array();
if($totalRows_fonction>0){  foreach($row_fonction as $row_fonction){
  $fonction_array[$row_fonction["id_fonction"]]=$row_fonction["fonction"]." (".$row_fonction["sigle"].")";
  $fonction_desc_array[$row_fonction["id_fonction"]]=$row_fonction["description"]." (".$row_fonction["nom_structure"].")";
   }
}

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
    <link rel="stylesheet" href="vendor/select2-3.5.2/select2.css" />
    <link rel="stylesheet" href="vendor/select2-bootstrap/select2-bootstrap.css" />
    <link rel="stylesheet" href="vendor/datatables.net-bs/css/responsive.dataTables.min.css" />
    <link rel="stylesheet" href="vendor/datatables.net-bs/css/dataTables.bootstrap.min.css" />

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
<?php require_once "./theme_components/sub-header.php"; ?>
    <div class="content animate-panel">
        <div class="row">
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
</style>
<div class="col-md-12 col-lg-12">
    <div <?php echo 'class="hpanel '.$Panel_Style.'"'; ?>>
        <div class="panel-heading">
            <div class="panel-tools">
                <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                <!-- <a class="closebox"><i class="fa fa-times"></i></a>-->
            </div>
          <span class="text-primary"><i class="fa fa-reorder"></i> Utilisateurs connect&eacute;s</span>
        </div>
        <div class="panel-body">

<script>
$("#search").hide();
</script>

<table class="table table-striped table-bordered table-hover table-responsive table-checkable table-tabletools table-colvis datatable dataTable " >
<thead>
<tr>
<th>Pr&eacute;nom</th>
<th>Nom</th>
<th>Login</th>
<th>Structure</th>
<th>Contact</th>
<th>Date connexion </th>
<th>Date d&eacute;connection </th>
<th>Dur&eacute;e</th>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="">
<?php if($totalRows_personnel>0) { $i=0; foreach($row_personnel as $row_personnel) { $id = $row_personnel['id_user']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo $row_personnel['prenom']; ?></td>
<td class=" "><?php echo $row_personnel['nom']; ?></td>
<td class=" "><?php echo $row_personnel['login']; ?></td>
<td class=" " title="<?php echo (isset($fonction_desc_array[$row_personnel["fonction"]]))?$fonction_desc_array[$row_personnel["fonction"]]:'-'; ?>" > <?php echo (isset($fonction_array[$row_personnel["fonction"]]))?$fonction_array[$row_personnel["fonction"]]:'-'; ?></td>
<td class=" "><?php echo $row_personnel['contact']; ?></td>
<td class=" "><?php echo date_reg($row_personnel['date_connexion'],"/",1); ?></td>
<td class=" "><?php if($row_personnel['date_deconnexion']!="0000-00-00 00:00:00") echo date_reg($row_personnel['date_deconnexion'],"/",1); ?></td>
<td class=" "><?php echo date_reg($row_personnel['date_connexion'],'/',1,1); ?></td>
</tr>
<?php $i++; } } ?>
</tbody></table>

        </div>
    </div>
</div>


        </div>
    </div>
    <?php require_once "./theme_components/footer.php"; ?>
</div>

</body>
</html>