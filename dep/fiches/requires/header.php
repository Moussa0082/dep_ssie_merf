<!-- Header -->
<script src="js/jquery.js"></script>
<style type="text/css">
    @media (max-width: 910px) {#nom_systeme2{position: absolute; font-size: 18px} #nom_systeme1{display: none}}
    @media (max-width: 769px) {#nom_systeme2{position: absolute; margin-top: 20px; font-size: 18px; margin-left: 10px;} #nom_systeme1{display: none}}
    @media (min-width: 910px) {#nom_systeme1{position: absolute; margin-top: 13px;} #nom_systeme2{display: none}}
    @media (min-width: 1005px) {#nom_systeme1{position: absolute; margin-top: 13px; font-size: 20px} #nom_systeme2{display: none}}
    @media (max-width: 430px) {#nom_systeme2{position: absolute; margin-top: 20px; font-size: 14px; margin-left: 10px;} #nom_systeme1{display: none}}
</style>
<form action="#" method="POST" accept-charset="utf-8" id="form_Theme"><input type="hidden" name="theme" id="input_hidden_theme"></form>
<script type="text/javascript">
    
    function Changer_Theme(d){document.getElementById("input_hidden_theme").value=d;
$.ajax({url:"traitement_jquery/theme_traitement.php", method:"POST", data:$('#form_Theme').serialize(), success:function (data) {if(data==''){
var Url=window.location.pathname.substring(1); window.location.href=Url;}else {}}});}
</script>
<div id="header" style="position: fixed; z-index: 1; width: 100%; margin-top: -10px">
   <!-- <div class="color-line"></div>-->
    <div id="logo" class="light-version">
        <span>
            <a href="menu.php" title="Accueil" style="text-decoration: none"><font style="font-size: 18px; color: orange; font-family: tahoma; font-weight: bold;">R</font><font style="font-size: 12px; color: black;">UCHE</font></a>
        </span>
    </div>
    <nav role="navigation">
        <div class="header-link hide-menu"><i class="fa fa-bars"></i></div>
        <div class="small-logo">
            <span class="text-primary"><a href="menu.php" title="Accueil" style="text-decoration: none"><font style="font-size: 18px; color: orange; font-family: tahoma; font-weight: bold;">R</font><font style="font-size: 12px; color: black;">UCHE</font></a></span>
        </div>
<span class="text-danger" id="nom_systeme1">Système de suivi-evaluation STPBF</span>
<span class="text-danger" id="nom_systeme2">Système de suivi-evaluation STPBF</span>
        <div class="mobile-menu">
            <button type="button" class="navbar-toggle mobile-menu-toggle" data-toggle="collapse" data-target="#mobile-collapse">
                <i class="fa fa-chevron-down"></i>
            </button>
            <div class="collapse mobile-navbar" id="mobile-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <a class="" href="#"><i class="pe-7s-pen <?php echo $Text_Style; ?>"></i> Thèmes</a>
                    </li>
                    <li>
                        <a class="" href="#"><i class="pe-7s-upload pe-7s-pin  text-info"></i> Widgets</a>
                    </li>
                    <li>
                        <a class="" href="#"><i class="pe-7s-bell  text-danger"></i> Notifications</a>
                    </li>
                    <li>
                        <a class="" href="/"><i class="pe-7s-upload pe-7s-lock  text-primary"></i> Deconnexion</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="navbar-right">
            <form role="search" class="navbar-form-custom" method="post" action="#">
            <div class="form-group">
                <input type="text" placeholder="Rechercher ..." class="form-control" name="search">
            </div>
        </form>
            <ul class="nav navbar-nav no-borders">
                <li class="dropdown">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                        <i class="pe-7s-pen  <?php echo $Text_Style; ?>"></i>
                    </a>

                    <div class="dropdown-menu hdropdown bigmenu animated flipInX">
                        <form action="#" method="POST" accept-charset="utf-8">
                        <table>
                            <tbody>
                            <tr>
                                <td>
                                    <a href="#" onclick="Changer_Theme(1)" class="theme_radio_btn">
                                        <div style="width: 50%; height: 20px;" class="btn btn-default "></div>
                                        <i class="text-info"></i>
                                        <h5>Thème 1</h5>
                                    </a>
                                </td>
                                <td>
                                    <a href="#" onclick="Changer_Theme(2)" class="theme_radio_btn">
                                        <div style="width: 50%; height: 20px;" class="btn btn-primary "></div>
                                        <i class="text-info"></i>
                                        <h5>Thème 2</h5>
                                    </a>
                                </td>
                                <td>
                                    <a href="#" onclick="Changer_Theme(3)" class="theme_radio_btn">
                                        <div style="width: 50%; height: 20px;" class="btn btn-success "></div>
                                        <i class="text-info"></i>
                                        <h5>Thème 3</h5>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" onclick="Changer_Theme(4)" class="theme_radio_btn">
                                        <div style="width: 50%; height: 20px;" class="btn btn-info "></div>
                                        <i class="text-info"></i>
                                        <h5>Thème 4</h5>
                                    </a>
                                </td>
                                <td>
                                    <a href="#" onclick="Changer_Theme(5)" class="theme_radio_btn">
                                        <div style="width: 50%; height: 20px;" class="btn btn-warning "></div>
                                        <i class="text-info"></i>
                                        <h5>Thème 5</h5>
                                    </a>
                                </td>
                                <td>
                                    <a href="#" onclick="Changer_Theme(6)" class="theme_radio_btn">
                                        <div style="width: 50%; height: 20px;" class="btn btn-danger "></div>
                                        <i class="text-info"></i>
                                        <h5>Thème 6</h5>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        
                        </form>
                    </div>
                </li>
                <li>
                    <a href="#" id="sidebar" class="right-sidebar-toggle">
                        <i class="pe-7s-upload pe-7s-pin  text-info"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle label-menu-corner" href="#" data-toggle="dropdown">
                        <i class="pe-7s-bell  text-danger"></i>
                        <span class="label label-danger">+99</span>
                    </a>
                    <ul class="dropdown-menu hdropdown animated flipInX">
                        <div class="title">
                        101 nouveaux messages
                        </div>
                        
                        <li>
                            <a>
                                2 boites e-mail créées.
                            </a>
                        </li>
                        <li>
                            <a>
                                7 nouveaux widgets ajoutés
                            </a>
                        </li>
                        <li class="summary"><a href="#">Tout voir</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="/">
                        <i class="pe-7s-upload pe-7s-lock  text-primary"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>
<script type="text/javascript">
Theme_radio_btn_Liste=document.getElementsByClassName("theme_radio_btn");
<?php echo 'Theme_radio_btn_Liste['.($Panel_Index-1).'].style.backgroundColor = \'beige\'';?>
</script>