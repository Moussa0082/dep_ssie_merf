<?php
if(isset($_SESSION["clp_id"]))
{
  if(!isset($config)) $config = new Config;
  function print_menu_position($nfile,$MENU,$MENU_TITLE)
  {
    $resultat = $nom = $lien = ''; $t = $te = 0; $id = array();  $id[0] = "None"; $id[1] = 1;
    if($nfile=='index.php')
    $resultat .= '<li class="current"> <i class="icon-home"></i><a href="javascript:void(0);" title="">Accueil</a> </li>';
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
       $resultat .= ($t==0)?'<li> <i class="icon-'.$MENU_TITLE[$id[1]][1].'"></i> <a href="./'.$MENU_TITLE[$id[1]][2].'">'.$MENU_TITLE[$id[1]][0].'</a> </li> <li class="current"> <a href="javascript:void(0);" title="">'.$nom.'</a> </li>':'<li> <i class="icon-'.$MENU_TITLE[$id[1]][1].'"></i> <a href="./'.$MENU_TITLE[$id[1]][2].'">'.$MENU_TITLE[$id[1]][0].'</a> </li> <!--<li class=""> <a href="javascript:void(0);" title="">'.$id[0].'</a> </li>--> <li class="current"> <a href="javascript:void(0);" title="">'.$nom.'</a> </li>';
    }
    if($te==0)
    $resultat = '<li class="current"> <i class="icon-home"></i><a href="javascript:void(0);" title="">Accueil</a> </li>';
    return $resultat;
  }
  $smenu = print_menu_position($nfile,$MENU,$MENU_TITLE);
   ?>
<ul id="breadcrumbs" class="breadcrumb"> <?php echo $smenu; ?></ul>
<div class="h0 breadcrumb_show"></div>
<ul class="crumb-buttons">
<li><a class="<?php echo ($nfile=="index.php")?'active':''; ?>" href="./" title="Accueil"><span class="sp_show">&nbsp;</span><i class="icon-home"></i><!--<span class="sp_hide">Accueil</span>--></a></li>
<?php $max=1; foreach($MENU as $idMenu=>$txtMenu) if($max<$idMenu) $max=$idMenu; for($j=$max; $j>0; $j--){ if(isset($MENU[$j])){ ?>
<li><a class="<?php echo (array_key_exists($nfile,$MENU[$j]) || $nfile==$MENU_TITLE[$j][2])?'active':''; ?>" href="<?php echo (isset($MENU_TITLE[$j][2]))?"./".$MENU_TITLE[$j][2]:'#'; ?>" title="<?php echo $MENU_TITLE[$j][0]; ?>"><span class="sp_show">&nbsp;</span><i class="icon-<?php echo (isset($MENU_TITLE[$j][1]))?$MENU_TITLE[$j][1]:''; ?>"></i><!--<span class="sp_hide"><?php echo $MENU_TITLE[$j][0]; ?></span>--></a></li>
<?php } } ?>
</ul>
<div class="clear h0"></div>
<?php } ?>