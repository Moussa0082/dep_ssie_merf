<?php
if(!isset($_SESSION)) session_start();
require_once 'api/Fonctions.php';
require_once 'theme_components/theme_style.php';

$query_liste_projet = $db ->prepare('SELECT distinct P.id_projet, P.code_projet, P.sigle_projet, P.nom_abrege, P.intitule_projet, (SELECT CONCAT(sigle_partenaire,"|",nom_partenaire) FROM t_partenaires WHERE FIND_IN_SET(:type_partenaire,type_partenaire) and id_partenaire=P.agence_lead LIMIT 1) as agence_lead FROM t_projets P, t_projet_users D WHERE P.programme=:programme and P.id_projet=D.projet_up  '.((isset($_SESSION['niveau']) && $_SESSION['niveau']!=0)?'and D.structure_up='.$_SESSION["structure"]:'').'  '.((isset($_SESSION['niveau']) && $_SESSION['niveau']!=0)?'and FIND_IN_SET('.$_SESSION["id"].',D.personnel_up)':'').' ORDER BY P.code_projet asc'
/*'SELECT P.id_projet, P.code_projet, P.sigle_projet, P.intitule_projet, (SELECT CONCAT(sigle,"|",nom_structure) FROM t_structures WHERE id_structure=P.agence_lead LIMIT 1) as agence_lead FROM t_projets P WHERE 1=1  ORDER BY P.code_projet asc'*/);
$query_liste_projet->execute(array(':type_partenaire' => 1,':programme' => isset($_SESSION['programme'])?$_SESSION['programme']:0));
$row_liste_projet = $query_liste_projet ->fetchAll();
$totalRows_liste_projet = $query_liste_projet->rowCount();

$total = $totalRows_liste_projet;
?>
<li class="dropdown">
<a class="dropdown-toggle label-menu-corner" href="#" data-toggle="dropdown" title="<?php echo ($total==0)?"Aucun projet disponible":"Vous avez $total projet".($total>1?"s":""); ?>">
<div class="" ><i class="pe-7s-server <?php echo $Text_Style; ?>"></i> <?php echo isset($Widgets_name)?$Widgets_name:""; ?><span class="label label-success" style="<?php echo !isset($Widgets_name)?"margin-top: 27px;":""; ?>" ><?php echo isset($_SESSION["projet_sigle"])?$_SESSION["projet_sigle"]:''; ?></span></div></a>
    <ul class="dropdown-menu hdropdown projet animated flipInX" id="liste_projet_filter<?php echo isset($Widgets_name)?1:""; ?>" >
        <div class="title" style="width: 100%;">Vous <?php echo ($total==0)?"n'":""; ?>avez <?php echo ($total==0)?"Aucun projet":"$total projet".($total>1?"s":""); ?></div>
<?php $first = 0; if(isset($row_liste_projet) && $totalRows_liste_projet>0){ ?>
<input type="text" class="form-control input-sm" id="filter_projet<?php echo isset($Widgets_name)?1:""; ?>" placeholder="Recherche..." style="border-color: #fff!important;">
    <li class="scrollerProjet" style="margin: 0px; padding: 0px;">
        <ul style="list-style: outside none none; margin: 0px; padding: 0px;">
<?php foreach($row_liste_projet as $row_liste_projet) { $first++; ?>
<li class="<?php echo (isset($_SESSION["projet"]) && $_SESSION["projet"]==$row_liste_projet["id_projet"])?"active":""; ?>" title="<?php echo str_replace('"','\"',$row_liste_projet["intitule_projet"]); ?>"> <a href="./projet_swither.php?id=<?php echo $row_liste_projet["id_projet"]."&page=".$_SERVER['PHP_SELF']; ?>" style="padding: 5px 0px;display: block;"><span class="time" style="margin-top:-10px!important;"><?php if(isset($row_liste_projet['agence_lead']) && !empty($row_liste_projet['agence_lead'])){ list($sigle,$nom_structure)=explode('|',$row_liste_projet['agence_lead']); echo "Lead : <span title=\"".$nom_structure."\">".$sigle."</span>";} else echo "-"; ?></span><span class="subject pe-7s-server"> <span class="from">Code : <?php echo $row_liste_projet["code_projet"]; ?></span> </span> <span class="text"> <?php echo "<b>".$row_liste_projet["sigle_projet"]."</b> : "?><?php if(isset($row_liste_projet["nom_abrege"]) && !empty($row_liste_projet["nom_abrege"])) echo $row_liste_projet["nom_abrege"]; else echo $row_liste_projet["intitule_projet"]; ?> </span> </a> </li>
<?php } ?>
</ul></li>
<?php } ?>
    </ul>
</li>
<script type="text/javascript">
<?php if($total>5){ ?>$(function () {$(".scrollerProjet").slimScroll({height: "400px",wheelStep: 7});});<?php } ?>
</script>
<style>
#liste_projet_filter<?php echo isset($Widgets_name)?1:""; ?> {
    width: 300px;
}
.dropdown-menu.projet li .from {
        font-size: 13px;
        font-weight: 600;
}
.dropdown-menu.projet li .time {
        font-weight: 300;
        position: absolute;
        right: 5px;
        color: #adadad;
        font-size: 11px;
        padding-top: 3px;
}
.dropdown-menu.projet li .text {
        display: block;
        white-space: normal;
        font-size: 12px;
        line-height: 20px;
        padding-top: 1px;
}
.dropdown-menu.projet li.active {
        background: yellow;
        color:#000;
}
.dropdown-menu.projet li.active a {
        background: yellow;
        color:#000;
}
</style>