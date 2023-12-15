<?php
if(!isset($_SESSION)) session_start();
if(!isset($config)) include_once 'api/configuration.php';
if(!isset($config)) $config = new Config;
if(isset($_SESSION["clp_id"]))
{
  function print_menu_position($nfile,$MENU,$MENU_TITLE)
  {
    $resultat = $nom = $lien = ''; $t = $te = 0; $id = array();  $id[0] = "None"; $id[1] = 1;
    if($nfile=='index.php')
    $resultat .= '<li class="current"> <i class="fa fa-home"></i><a href="javascript:void(0);" title="">Accueil</a> </li>';
    elseif(is_array($MENU))
    {
      foreach($MENU as $MENU_key => $MENU_val) {
       foreach($MENU_val as $a => $b) { if(is_array($b)) { $tmp = array_values($b);
        foreach($b as $c => $d) {
            if($nfile==$c){ $nom .= isset($b[$c])?$b[$c]:$tmp[0]; $id[0] = $a; $id[1] = $MENU_key; $t = $te = 1;  }
            }
       } else {
         if($nfile==$a){ $nom .= $b; $id[0] = $b; $id[1] = $MENU_key; $te = 1; }
        }
       }
      }
      $nomd = explode('|',$nom); $nomc = $nomd[0];

    }
    if($te==0)
    {
        $nomc = "Accueil"; $nomd[1] = "Bienvenue dans la base de données de la coordination des Projets PBF/PACOP";
        $resultat = '<li class="active"><span><i class="fa fa-home"></i> <a href="./" title="">'.$nomc.'</a> </span></li>';
    }
    return $resultat."|".(isset($nomd[1])?$nomd[1]."<>".(isset($nomc)?$nomc:''):(isset($nomc)?$nomc:''));
  }
?>
<div class="small-header">
    <div class="hpanel">
        <div class="panel-body" style="padding: 8px 25px;">
            <div id="mbreadcrumb" class="pull-left">&nbsp;</div>
            <div id="hbreadcrumb" class="pull-right">
                <ol class="hbreadcrumb breadcrumb">
                    <?php $smenu = print_menu_position($nfile,$MENU,$MENU_TITLE); list($smenu,$smenu_title) = explode('|',$smenu); echo $smenu; list($smenu_desc,$smenu_title) = (isset($smenu_title))?explode('<>',$smenu_title):explode('<>',"<>"); ?>
                </ol>
            </div>
            <h2 class="font-light m-b-xs" id="sub-title">
                <?php echo $smenu_title; ?>
            </h2>
            <small id="sub-desc"><?php echo $smenu_desc; ?></small>
            <!--<ul class="nav navbar-nav no-borders">
                <?php //include 'theme_components/online_users.php'; ?>
                <?php //include 'theme_components/notification.php'; ?>
            </ul>-->
        </div>
		<?php if(!isset($vprojet) || $vprojet!=1) { ?>

		<?php  } ?>
    </div>
</div>
<?php } ?>