<?php
if(!isset($_SESSION)) session_start();
$path = (isset($_GET["path"]))?$_GET["path"]:"./";
include_once $path.'system/configuration.php';
if(!headers_sent())
{
  $config = new Config;
  //header('Content-Type: text/html; charset=ISO-8859-15');
}

include_once $path.$config->sys_folder . "/database/db_connexion.php";

if(isset($_SESSION["clp_id"]))
{
  if(!isset($page_principal) || !is_array($page_principal)) $page_principal = "";

  function print_menu($nfile,$MENU,$lnk,$MENU_TITLE,$key,$page_principal)
  {
    $resultat = $resultat1 = '';
    if(is_array($MENU))
    {
      $resultat1 = '<li class="'.((array_key_exists($nfile,$MENU) || $nfile==$MENU_TITLE[2])?'current':'').'"> <a href="./'.$lnk.'"> <i class="icon-'.((is_array($MENU_TITLE))?$MENU_TITLE[1]:'').'"></i> '.((is_array($MENU_TITLE))?$MENU_TITLE[0]:'').' <span class="label label-success pull-right">'.(count($MENU)).'</span> </a>
      <ul class="sub-menu" style="display: none;">';
   $i = 0; foreach($MENU as $a => $b) { if(is_array($b)) $tmp = array_values($b);
       $resultat .= ((is_array($page_principal) && !in_array($a,$page_principal)) || !is_array($page_principal))?'<li class="'.(($nfile==$a || (is_array($b) && in_array($nfile,array_keys($b))) )?'current':'').'"> <a class="'.(($nfile==$a || (is_array($b) && in_array($nfile,array_keys($b))))?'current':'').'" href="./'.$a.'"> <i class="icon-angle-right"></i> '.(is_array($b)?$tmp[0]:$b).' </a> </li>':'';
        if($nfile==$a || (is_array($b) && in_array($nfile,array_keys($b)))) $resultat1 = '<li class="current"> <a href="./'.$lnk.'"> <i class="icon-'.((is_array($MENU_TITLE))?$MENU_TITLE[1]:'').'"></i> '.((is_array($MENU_TITLE))?$MENU_TITLE[0]:'').' <!--<span class="label label-success pull-right">'.(count($MENU)).'</span>--> </a>
            <ul class="sub-menu" style="display: none;">';
    $i++;
    }
       $resultat .= '</ul></li>';
    }
    return $resultat1.$resultat;
  }  }
?>
<!--<form class="sidebar-search" action="./resultat.php" method="get"> <div class="input-box"> <button type="submit" class="submit"> <i class="icon-search"></i> </button> <span> <input type="text" name="q" placeholder="Recherche..." value="<?php //echo (isset($_GET['q']) && !empty($_GET['q']))?utf8_decode($_GET['q']):""; ?>"> </span> </div> </form> -->
<div align="center" class="sidebar_header"><a href="./" title="Allez à l'accueil"><img src="images/Ruche_logo_menu1.png" alt="logo"/></a></div>
<?php if(isset($_SESSION["clp_id"])) { ?>
<ul id="nav" style="margin-bottom: 0px;margin-top:110px;">
<?php //if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1))
foreach($MENU as $a => $b) { echo print_menu($nfile,$b,$MENU_TITLE[$a][2],$MENU_TITLE[$a], $a, $page_principal); } ?>
</ul>
<?php } else{ ?>
<div align="center" style="margin-bottom: 0px;margin-top:90px;"><h1 style="white-space:normal;overflow: visible; color:#129233;"><?php print $config->siteshortname; ?></h1>
<img src="./images/logo-projet.jpg" alt="logo" style="height: 150px;"><h2 style="white-space:normal;overflow: visible; color:#45A543;"><?php print $config->siteshortdescription; ?></h2>
</div>
<?php } ?>
<br>
<a href="#" class="toggle-sidebar toggle-sidebar-bottom" title="Masquer le volet navigation"> <i class="icon-play" style="margin-right:5px;"></i> Réduire le menu </a>
<div class="clear">&nbsp;</div>
<div align="center"><a href="javascript:void(0);">Avec le soutien de :</a></div>
<div align="center" class="partenaire_zone"><img src="./images/bg3.png" alt="img" id="slogo" usemap="#m_slogo"></div>
<!--<map name="m_slogo" id="m_slogo">
<area shape="rect" coords="17,90,97,165" href="https://www.ifad.org/fr" target="_blank" title="FIDA" alt="FIDA" />
<area shape="rect" coords="143,90,220,165" href="https://www.ifad.org/fr" target="_blank" title="FIDA" alt="FIDA" />
<area shape="rect" coords="113,27,250,85" href="https://www.ifad.org/" target="_blank" title="FIDA" alt="FIDA" />
<area shape="rect" coords="8,34,105,78" href="http://www.agriculture.gouv.tg/" target="_blank" title="Ministère de l'emploi" alt="MEIS" />
</map>-->
<div align="center" class="pull-left sidebar-style" style="margin-left: 10px;"><a onclick="get_content('content_popup.php','id=4','modal-mot_dg_box',this.title,'');" href="javascript:void(0);" title="Conditions d'utilisation" data-toggle="mot_dg_box" class="sidebar-style">Conditions <br>d'utilisation</a></div>
<div align="center" class="pull-right sidebar-style" style="margin-right: 20px;"><a onclick="get_content('content_popup.php','id=1','modal-mot_dg_box',this.title,'');" href="javascript:void(0);" title="Présentation MERF" data-toggle="mot_dg_box" class="sidebar-style">Présentation <br>du Système</a></div>
<div class="clear">&nbsp;</div><br>
<p align="center" style="color:#FFF;"><font size="-1">Copyright &copy; 2023</font><br>
<span>Tous droits r&eacute;serv&eacute;s - <a target="_blank" href="<?php print $config->siteurl; ?>" title="<?php print $config->sitetititle; ?>" class="footer"><?php print $config->siteshortname; ?></a></span><br>
<span>Conception : <a target="_blank" href="http://www.cosit-mali.com" title="Conception & D&eacute;veloppement" class="footer">COSIT</a></span></p>
<style>.sidebar-style {border-radius: 5px;padding: 3px;background-color: #47A544;color: #FFF;}.form-group .select2-container { position: relative; z-index: 2; float: left; width: 100%; margin-bottom: 0; display: table; table-layout: fixed;}</style>
<script type="text/javascript">
$("#nav li.current").addClass("open");
$("#nav li.current ul.sub-menu:first").attr("style","display:block");
$("#nav li.current ul.sub-menu li.current").removeClass("open");
$("#nav li.current ul.sub-menu li.current ul.sub-menu").attr("style","display:block");
//$(".toggle-sidebar").addClass("mystyle");
$(function () {
    $('[data-pages="mot_dg_box"]').mot_dg_box({mot_dg_boxField:'#overlay-mot_dg_box',closeButton:'.overlay-close'});
});
</script>