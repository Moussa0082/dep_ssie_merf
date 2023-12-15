<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
if (!isset ($_SESSION["clp_id"])) {
    header(sprintf("Location: %s", "/"));  exit();
}
include_once 'api/configuration.php';
$config = new Config;
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

    <!-- App scripts -->
    <script src="scripts/homer.js"></script>
</head>
<body class="fixed-navbar fixed-footer sidebar-scroll">
    <?php require_once "./theme_components/header.php"; ?>
    <?php //require_once "./theme_components/main-menu.php"; ?>
<!-- Main Wrapper -->
<div id="wrapper">
<?php //require_once "./theme_components/sub-header.php"; ?>
<?php $i = 5; $menu_show = $MENU[$i]; ?>
<div class="small-header">
    <div class="hpanel">
        <div class="panel-body">
            <div id="hbreadcrumb" class="pull-right">
                <ol class="hbreadcrumb breadcrumb">
                    <li class="active"><span><i class="fa fa-home"></i> <a href="./" title="">Accueil</a> </span></li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                <?php echo $MENU_TITLE[$i][0]; ?>
            </h2>
            <small>Module<?php //echo $smenu_desc; ?></small>
        </div>
    </div>
</div>
    <div class="content animate-panel">
<style>
.hpanel.hgreen .panel-body {
    border-top: 2px solid #62cb31;
}
.project-action{
    margin: -10px 0px 0;
    float: right
}
.panel-footer a{
    display: block;
    text-align: center;
}
</style>
<div class="row ">
    <?php foreach($menu_show as $key=>$val) { if(is_array($val)){ foreach($val as $key=>$val) { if(!in_array($key,$page_principal)){ list($val,$des) = explode('|',$val); ?>
    <div class="col-lg-4">
            <div class="panel <?php echo $Panel_Style; ?>">
                <div class="panel-body">
                    <div class="project-action">
                            <div class="btn-group" style="font-size: 18px">
                                <a href="<?php echo $key; ?>" class="btn btn-xs btn-default" title="Afficher la page"><span class="nav-label glyphicon glyphicon-eye-open text-primary"></span></a>
                            </div>
                    </div>
                    <div class="row" style="text-align: left">
                        <div class="col-sm-12" style="height: 40px;">
                            <h4><a href="<?php echo $key; ?>"><?php echo $val; ?></a></h4>
                        </div>
                        <div class="col-sm-12">
                            <p style="height: 40px;"><?php echo isset($des)?$des:$val; ?></p>
                        </div>
                    </div>
                </div>
                <div class="panel-footer"><a href="<?php echo $key; ?>">Accedez à cette page</a></div>
            </div>
        </div>
<?php } } } else{ if(!in_array($key,$page_principal)){ list($val,$des) = explode('|',$val); ?>
    <div class="col-lg-4">
            <div class="panel <?php echo $Panel_Style; ?>">
                <div class="panel-body">
                    <div class="project-action">
                            <div class="btn-group" style="font-size: 18px">
                                <a href="<?php echo $key; ?>" class="btn btn-xs btn-default" title="Afficher la page"><span class="nav-label glyphicon glyphicon-eye-open text-primary"></span></a>
                            </div>
                    </div>
                    <div class="row" style="text-align: left">
                        <div class="col-sm-12" style="height: 40px;">
                            <h4><a href="<?php echo $key; ?>"><?php echo $val; ?></a></h4>
                        </div>
                        <div class="col-sm-12">
                            <p style="height: 40px;"><?php echo isset($des)?$des:$val; ?></p>
                        </div>
                    </div>
                </div>
                <div class="panel-footer"><a href="<?php echo $key; ?>">Accedez à cette page</a></div>
            </div>
        </div>
<?php } } } ?>
</div></div>
    <?php //require_once "./theme_components/footer.php"; ?>
</div>

</body>
</html>