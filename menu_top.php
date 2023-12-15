<?php
if(!isset($_SESSION)) session_start();
$path = (isset($_GET["path"]))?$_GET["path"]:"./";
include_once $path.'system/configuration.php';
if(!headers_sent())
{
  $config = new Config;
  header('Content-Type: text/html; charset=UTF-8');
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
        if($nfile==$a || (is_array($b) && in_array($nfile,array_keys($b)))) $resultat1 = '<li class="current"> <a href="./'.$lnk.'"> <i class="icon-'.((is_array($MENU_TITLE))?$MENU_TITLE[1]:'').'"></i> '.((is_array($MENU_TITLE))?$MENU_TITLE[0]:'').' <span class="label label-success pull-right">'.(count($MENU)).'</span> </a>
            <ul class="sub-menu" style="display: none;">';
    $i++;
    }
       $resultat .= '</ul></li>';
    }
    return $resultat1.$resultat;
  }
?>
<form class="sidebar-search" action="./resultat.php" method="get"> <div class="input-box"> <button type="submit" class="submit"> <i class="icon-search"></i> </button> <span> <input type="text" name="q" placeholder="Recherche..." value="<?php echo (isset($_GET['q']) && !empty($_GET['q']))?utf8_decode($_GET['q']):""; ?>"> </span> </div> </form>
<ul id="nav">
<?php //if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1))
foreach($MENU as $a => $b) { echo print_menu($nfile,$b,$MENU_TITLE[$a][2],$MENU_TITLE[$a], $a, $page_principal); } ?>
</ul>
<?php } ?>
<div class="sidebar-widget align-center"> <div class="btn-group" data-toggle="buttons" id="theme-switcher"> <label class="btn active"> <input type="radio" name="theme-switcher" data-theme="bright"><i class="icon-sun"></i> Clair </label> <label class="btn"> <input type="radio" name="theme-switcher" data-theme="dark"><i class="icon-moon"></i> Sombre </label> </div></div>

<div align="center"><img src="<?php echo file_exists("./images/structure/bg1.png")?"./images/structure/bg1.png":"./images/logo_parsat.png"; ?>" alt="img"/></div>
<div align="center" style="margin-bottom: 20px;"><img src="./images/bg3.png" alt="img"/ id="slogo" usemap="#m_slogo"></div>
<!--<div align="center"><img src="./images/bg2.png" alt="img"/></div>-->
<map name="m_slogo" id="m_slogo">
<area shape="rect" coords="168,0,250,60" href="https://www.thegef.org/gef/whatisgef" target="_blank" title="FEM" alt="FEM" />
<area shape="rect" coords="102,0,168,60" href="https://ifad.org/" target="_blank" title="FIDA" alt="FIDA" />
<area shape="rect" coords="0,0,102,60" href="https://www.presidence.td/fr.html" target="_blank" title="République du Tchad" alt="République du Tchad" /></map>
<p align="center"><font size="-1">Copyright &copy; 2016</font><br>
<span>Tous droits r&eacute;serv&eacute;s - <a target="_blank" href="<?php print $config->siteurl; ?>" title="Conception & D&eacute;veloppement" class="footer">PARSAT</a></span><br>
<span>Conception : <a target="_blank" href="http://www.bamasoft-mali.org" title="Conception & D&eacute;veloppement" class="footer">BASE</a></span></p>
<script type="text/javascript">
$("#nav li.current").addClass("open");
$("#nav li.current ul.sub-menu:first").attr("style","display:block");
$("#nav li.current ul.sub-menu li.current").removeClass("open");
$("#nav li.current ul.sub-menu li.current ul.sub-menu").attr("style","display:block");
$(".toggle-sidebar").addClass("mystyle");
</script>