<!-- Navigation -->
<?php
if(!isset($_SESSION)) session_start();
if(!isset($config)) include_once 'api/configuration.php';
if(isset($_SESSION["id"])) { ?>
<aside id="menu">
    <div id="navigation">
        <div class="profile-picture">
            <a href="javascript:void(0);">
                <?php $dir = './images/avatar/'; if(file_exists($dir."profil_".$_SESSION["id"].'.jpg')){ ?>
                <img src="<?php echo $dir."profil_".$_SESSION["id"].'.jpg'; ?>" class="img-circle m-b" alt="logo" width="30" height="30"><?php } else { ?><span class="fa fa-user-circle <?php echo $Text_Style; ?>" style="font-size: 40px;" ></span><?php } ?>
            </a>
            <div class="stats-label text-color">
                <div class="dropdown">
                    <a class="dropdown-toggle" id="profil_dropdown" href="#" data-toggle="dropdown">
                        <span class="font-extra-bold font-uppercase"><?php echo $_SESSION["user_name"]; ?> <b class="caret"></b></span>
                    </a>
                    <ul class="dropdown-menu animated flipInX m-t-xs">

<li><a class="<?php //echo (isset($nfile) && $nfile == "logout.php")?'current':''; ?>" href="./logout.php"><i class="fa fa-key <?php echo $Text_Style; ?>"></i> Se d&eacute;connecter</a></li>
                    </ul>
                </div>
                <!--<div id="sparkline1" class="small-chart m-t-sm"></div>
                <div>
                    <h4 class="font-extra-bold m-b-xs">
                        $260 104,200
                    </h4>
                    <small class="text-muted">Your income from the last year in sales product X.</small>
                </div>-->
            </div>
        </div>
<?php
if(!isset($page_principal) || !is_array($page_principal)) $page_principal = "";

  function print_menu($nfile,$MENU,$lnk,$MENU_TITLE,$key,$page_principal)
  {
    $resultat = $resultat1 = '';
    $j = 0; foreach($MENU as $a => $b) { if(is_array($b)) $tmp = array_values($b);
       if((is_array($page_principal) && !in_array($a,$page_principal) && !empty($b)) || !is_array($page_principal)) $j++;
    }
    if(is_array($MENU))
    {
      $lib = is_array($MENU_TITLE)?$MENU_TITLE[0]:'';
      $resultat1 = '<li class="nav-item '.((array_key_exists($nfile,$MENU) || $nfile==$MENU_TITLE[2])?'active':'').'"> <a href="./'.$lnk.'"> <i class="fa fa-'.((is_array($MENU_TITLE))?$MENU_TITLE[1]:'').'"></i> <span class="nav-label">'.$lib.'</span> <span class="fa arrow"></span><!--<span class="label label-success pull-right">'.(count($MENU)).'</span>--> </a>
      <ul class="nav nav-second-level">';
   $i = 0; foreach($MENU as $a => $b) {
       if(is_array($b)) $tmp = array_values($b); $lib = is_array($b)?$tmp[0]:$b;
       list($lib) = explode('|',$lib);
       $resultat .= ((is_array($page_principal) && !in_array($a,$page_principal)) || !is_array($page_principal))?'<li class="'.(($nfile==$a || (is_array($b) && in_array($nfile,array_keys($b))) )?'active':'').'"> <a class="'.(($nfile==$a || (is_array($b) && in_array($nfile,array_keys($b))))?'active':'').'" href="./'.$a.'"> <span class="nav-label"><span class="fa fa-angle-right"></span> '.$lib.'</span> </a> </li>':'';
        if($nfile==$a || (is_array($b) && in_array($nfile,array_keys($b)))){
            $lib = is_array($MENU_TITLE)?$MENU_TITLE[0]:'';
            $resultat1 = '<li class="active"> <a href="./'.$lnk.'"> <i class="fa fa-'.((is_array($MENU_TITLE))?$MENU_TITLE[1]:'').'"></i> <span class="nav-label">'.$lib.'</span> <!--<span class="label label-success pull-right">'.(count($MENU)).'</span>--> </a>
            <ul class="nav nav-second-level">';
        }
    $i++;
    }
       $resultat .= '</ul></li>';
       if($j==0) $resultat1=$resultat="";
    }
    return $resultat1.$resultat;
  }
?>
        <ul class="nav" id="side-menu">
<?php //if(isset($_SESSION['niveau']) && ($_SESSION['niveau']==1))
foreach($MENU as $a => $b) { echo print_menu($nfile,$b,$MENU_TITLE[$a][2],$MENU_TITLE[$a], $a, $page_principal); } ?>
<li>

</li>
        </ul>
    </div>
</aside>
<?php } ?>