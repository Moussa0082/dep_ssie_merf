<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
if (isset ($_SESSION["id"])) {
    header(sprintf("Location: %s", "./"));
    exit;
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

    <!-- App styles -->
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/helper.css" />
    <link rel="stylesheet" href="styles/style_login.css">
    <link rel="stylesheet" href="vendor/sweetalert/lib/sweet-alert.css">

    <!-- Vendor scripts -->
    <script src="vendor/jquery/dist/jquery.min.js"></script>
    <script src="vendor/jquery-ui/jquery-ui.min.js"></script>
    <script src="vendor/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="vendor/metisMenu/dist/metisMenu.min.js"></script>
    <script src="vendor/iCheck/icheck.min.js"></script>
    <script src="vendor/sparkline/index.js"></script>
    <script src="vendor/sweetalert/lib/sweet-alert.js"></script>

    <!-- App scripts -->
    <script src="scripts/homer.js"></script>

</head>
<body class="blank">

<!--[if lt IE 7]>
<p class="alert alert-danger">Vous utilisez un navigateur <strong> obsolète </ strong>. Veuillez <a href="http://browsehappy.com/"> mettre à niveau votre navigateur </a> pour améliorer votre expérience.</p>
<![endif]-->

<div class="color-line"></div>

<div class="login-container">
    <div class="row">
        <div class="col-md-12">
<br>
<center>
            <div class="clear h0">&nbsp;</div>
            <!--<hr style="width: 95%; height: 1px; background-color: #3399FF;">-->
            <div class="hpanel hpanel_login">
                <div class="col-md-4"></div>
                <div class="panel-body col-md-4">
                    <h1>Authentification</h1>
                        <form action="./connexion.php" id="loginForm" method="post">
                            <div class="form-group">
                                <label class="control-label" for="identifiant">Identifiant <span class="form-required" title="Ce champ est requis.">*</span></label>
                                <input type="text" placeholder="" title="Saisissez votre Identifiant" required="" value="<?php echo isset($_GET["identifiant"])?$_GET["identifiant"]:''; ?>" name="identifiant" id="identifiant" class="form-control">
                                <span class="help-block small">Saisissez votre Identifiant</span>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="password">Mot de passe <span class="form-required" title="Ce champ est requis.">*</span></label>
                                <input type="password" title="Saisissez votre Mot de passe" placeholder="" required="" value="" name="password" id="password" class="form-control">
                                <span class="help-block small">Saisissez votre Mot de passe</span>
                            </div>
                            <div class="checkbox">
                                <input type="checkbox" class="i-checks" name="remember" checked>
                                     Se souvenir de moi <i class="small">(s'il s'agit d'un ordinateur privé)</i>
                            </div>
                            <a id="resetpassword" class="btn col-md-5" href="javascript:void(0);" style="color:red;">Mot de passe oublié ?</a>
                            <span class="col-md-2">&nbsp;</span>
                            <button class="btn btn-success col-md-5">Login</button>
<script>
$(document).ready(function(){
$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green"});
});
document.querySelector('#resetpassword').onclick = function(){
swal({
        title: "Recuperation de passe",
        text: '<br /><form action="./resetpassword.php" method="post" id="resetpassword" name="resetpassword" onkeypress="return event.keyCode != 13;">'
        + '<input id="email" autofocus minlength="3" class="form-control wedding-input-text wizard-input-pad required" type="text" name="email" placeholder="Saisissez votre email">'
        + '</form>',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Ok',
        cancelButtonText: "Annuler",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm){
        if (isConfirm){
            var a = $("#email").val();
            if(check_email(a))
            {
                swal('Email valide', 'Envoi du mail en cours... <form action="./resetpassword.php" method="post" id="resetpassword" name="resetpassword" style="display:none;" onkeypress="return event.keyCode != 13;" ><input id="email" autofocus minlength="3" class="form-control wedding-input-text wizard-input-pad required" type="text" name="email" placeholder="Saisissez votre email"><input id="resetpassword_btn" type="submit" value="Envoyer"></form>', "success");
                $("#email").val(a); $('#resetpassword_btn').click();
            }
            else
            {
                swal("Email invalide", "Annulation...)", "error");
            }
        }
    }); $("#email").focus();
function check_email(a){
    return /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(a);
}
};
</script>
<style>#axle_bogie_border{width: auto!important;height: 70px;}</style>
                        </form>
                </div>
                </center>
                <div class="clear h0">&nbsp;</div>
            </div>
        </div>
    </div>
    <div>&nbsp;</div>
    <!--<h4 align="center"><?php //print $config->shortname; ?></h4>-->
    <?php //require_once "./theme_components/footer.php"; ?>
</div>
<div class="clear h0">&nbsp;</div>

</body>
</html>