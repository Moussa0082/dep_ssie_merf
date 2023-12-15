<?php
if(!isset($_SESSION)) session_start();
require_once 'api/Fonctions.php';
require_once 'theme_components/theme_style.php';

//if(isset($_SESSION["niveau"]) && $_SESSION["niveau"]==0){
$query_liste_programme = $db ->prepare('SELECT * FROM t_programmes P where type_programme=:type_programme ORDER BY code_programme asc');
$query_liste_programme->execute(array(':type_programme' => 0));/*}
else{
$query_liste_programme = $db ->prepare('SELECT * FROM t_programmes P WHERE P.id_programme IN (SELECT Pp.programme FROM t_projets Pp, t_projet_users D WHERE Pp.id_projet=D.projet_up and D.structure_up=:structure and FIND_IN_SET(:user,D.personnel_up) ) ORDER BY code_programme asc');
$query_liste_programme->execute(array(':structure' => $_SESSION["structure"],':user' => $_SESSION["id"]));
}*/
$row_liste_programme = $query_liste_programme ->fetchAll();
$totalRows_liste_programme = $query_liste_programme->rowCount();

$total = $totalRows_liste_programme;
?>
<li class="dropdown">
<a class="dropdown-toggle label-menu-corner" href="#" data-toggle="dropdown" title="<?php echo ($total==0)?"Aucun programme cadre disponible":"Vous avez $total programme".($total>1?"s":"")." cadre".($total>1?"s":""); ?>">
<div class="" ><i class="pe-7s-global <?php echo $Text_Style; ?>"></i> <?php echo isset($Widgets_name)?$Widgets_name:""; ?><span class="label label-success" style="<?php echo !isset($Widgets_name)?"margin-top: -12px;":""; ?>" ><?php echo isset($_SESSION["programme_code"])?$_SESSION["programme_code"]:''; ?></span></div></a>
  <ul class="dropdown-menu hdropdown programme animated flipInX" id="liste_programme_filter<?php echo isset($Widgets_name)?1:""; ?>" >
    <div class="title" style="width: 100%;">Vous <?php echo ($total==0)?"n'":""; ?>avez <?php echo ($total==0)?"Aucun programme":"$total programme".($total>1?"s":""); ?></div>
<?php $first = 0; if(isset($row_liste_programme) && $totalRows_liste_programme>0){ ?>
    <li class="scrollerProgramme" style="margin: 0px; padding: 0px;">
        <ul style="list-style: outside none none; margin: 0px; padding: 0px;">
<?php foreach($row_liste_programme as $row_liste_programme) { $first++; ?>
<li class="<?php echo (isset($_SESSION["programme"]) && $_SESSION["programme"]==$row_liste_programme["id_programme"])?"active":""; ?>" title="<?php echo str_replace('"','\"',$row_liste_programme["nom_programme"]); ?>"> <a href="./programme_swither.php?id=<?php echo $row_liste_programme["id_programme"]."&page=".$_SERVER['PHP_SELF']; ?>" style="padding: 5px 0px;display: block;"><span class="time" style="margin-top:-10px!important;"><?php echo $row_liste_programme["pays"]; ?></span><span class="subject pe-7s-global"> <span class="from">Code : <?php echo $row_liste_programme["code_programme"]; ?></span> </span> <span class="text"> <?php echo "<b>".$row_liste_programme["sigle_programme"]."</b> : ".$row_liste_programme["nom_programme"]; ?> </span> </a> </li>
<?php } ?>
</ul></li>
<?php } ?>
    </ul>
</li>
<script type="text/javascript">
<?php if($total>5){ ?>$(function () {$(".scrollerProgramme").slimScroll({height: "400px",wheelStep: 7});});<?php } ?>
</script>
<style>
#liste_programme_filter<?php echo isset($Widgets_name)?1:""; ?> {
    width: 300px;
}
.dropdown-menu.programme li .from {
        font-size: 13px;
        font-weight: 600;
}
.dropdown-menu.programme li .time {
        font-weight: 300;
        position: absolute;
        right: 5px;
        color: #adadad;
        font-size: 11px;
        padding-top: 3px;
}
.dropdown-menu.programme li .text {
        display: block;
        white-space: normal;
        font-size: 12px;
        line-height: 20px;
        padding-top: 1px;
}
.dropdown-menu.programme li.active {
        background: yellow;
        color:#000;
}
.dropdown-menu.programme li.active a {  
        background: yellow;
        color:#000;
}
</style>