<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
 // header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
header('Content-Type: text/html; charset=UTF-8');

$plog=$_SESSION["clp_id"];
$date=date("Y-m-d");
if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else $annee=date("Y");
//if(isset($_GET['cp'])) {$cp=$_GET['cp'];} else $cp=0;
if(isset($_GET['cmp'])) $cmp = ($_GET['cmp']);

// $query_act = "SELECT *   FROM ".$database_connect_prefix."plan_marche where periode='$annee' and projet='".$_SESSION["clp_projet"]."'  order by  code_marche asc";
if($cmp!="0")  $query_act = "SELECT * FROM ".$database_connect_prefix."plan_marche where  periode like '%$annee%' and projet='".$_SESSION["clp_projet"]."' and categorie='$cmp' ";
else $query_act = "SELECT * FROM ".$database_connect_prefix."plan_marche where  periode like '%$annee%' and projet='".$_SESSION["clp_projet"]."' ";
$query_act .= " order by code_marche asc";
       try{
    $act = $pdar_connexion->prepare($query_act);
    $act->execute();
    $row_act = $act ->fetchAll();
    $totalRows_act = $act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_categorie = "SELECT * FROM ".$database_connect_prefix."categorie_marche ORDER BY code_categorie asc";
       try{
    $liste_categorie = $pdar_connexion->prepare($query_liste_categorie);
    $liste_categorie->execute();
    $row_liste_categorie = $liste_categorie ->fetchAll();
    $totalRows_liste_categorie = $liste_categorie->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


//mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_modele = "SELECT * FROM ".$database_connect_prefix."modele_marche ORDER BY code asc";
           try{
    $liste_modele = $pdar_connexion->prepare($query_liste_modele);
    $liste_modele->execute();
    $row_liste_modele = $liste_modele ->fetchAll();
    $totalRows_liste_modele = $liste_modele->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$modele_array = array();
if($totalRows_liste_modele>0) { foreach($row_liste_modele as $row_liste_modele){  
$modele_array[$row_liste_modele["id_modele"]]=$row_liste_modele['code'];  }  }
?>
<!-- Site contenu ici -->
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; width: 100%!important;
} .table tbody tr td {vertical-align: middle; }
</style>
<script>
$('#myModal_add').remove();
$().ready(function() {
//$('a[data-toggle="modal"]').modal();
var oTable = $('#mtable<?php echo $annee; ?>').dataTable( {
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ -1 ] }
        ],
       // sDom:"<'row'<'dataTables_header clearfix'<'col-md-7'lT><'col-md-5'Cf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
        //oTableTools:{aButtons:["copy","print","csv","xls",{"sExtends": "pdf","sPdfOrientation": "landscape"}],sSwfPath:"./swf/copy_csv_xls_pdf.swf"},
       // "aaSorting": [],
        //"aLengthMenu":[[25, 50, 100, 200, -1],[25, 50, 100, 200, "TOUS1"]],
        "iDisplayLength": -1,
        paging: false
    });
});
</script>

<form name="form<?php echo $annee; ?>" id="form<?php echo $annee; ?>" method="get" action="<?php echo "plan_marche.php?annee=".$annee; ?>" class="pull-left">
<select name="cmp" onchange="form<?php echo $annee; ?>.submit();" style="background-color: #FFFF00; padding: 7px; width: 150px;" class="btn p11">
  <option value="">-- Cat&eacute;gories --</option>
                            <?php if($totalRows_liste_categorie>0){ foreach($row_liste_categorie as $row_liste_categorie1){   ?>
  <option value="<?php echo $row_liste_categorie1['code_categorie'];?>"><?php echo $row_liste_categorie1['code_categorie'].": ".$row_liste_categorie1['nom_categorie']; ?></option>
                            <?php }  } ?>
</select>
</form>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<?php

  echo do_link("","","Ajout de march&eacute; au PPM '$annee'","<i class=\"icon-plus\"> Nouveau march&eacute; </i>","simple","../","btn btn-sm btn-warning pull-right p11","get_content('modal_content/edit_ppm.php','annee=$annee','modal-body_add',this.title);",1,"","plan_marche.php");

?>
<!--<a onclick="get_content('modal_content/edit_ppm.php','<?php echo "&annee=".$annee."#os"; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Ajout de march&eacute; '<?php echo $annee;?>'" class="btn btn-sm btn-warning pull-right p11" dir=""><i class="icon-plus"> Nouveau march&eacute; </i></a>-->
<?php }?>
<div class="clear">&nbsp;</div>

<table class="table table-striped table-bordered table-hover table-responsive  datatable dataTable" id="mtable<?php echo $annee; ?>" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">S/Cp</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Intitul&eacute; du march&eacute;</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><div align="center">Types/ Cat&eacute;gorie </div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Types de passation </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Num. R&eacute;f </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Nb. Lots</th>
<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Nb. March&eacute;s </th>-->
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Co&ucirc;t pr&eacute;vu (USD)</th>
<th align="center" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Pr&eacute;vu</th>

<th align="center" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><div align="center">R&eacute;alis&eacute;</div></th>
<?php //if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) {?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="90">Actions</th>
<?php //} ?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="">
<?php if(isset($totalRows_act) && $totalRows_act>0) { $i=0; foreach($row_act as $row_act){  if(in_array($annee, explode(",", $row_act['periode']))){ ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo "<strong>".$row_act['composante']."</strong> "; ?></td>
<td class=" "><?php echo "<strong>".$row_act['code_marche'].":</strong> ".$row_act['intitule']; ?></td>
<td class=" "><div align="center"><?php if(isset($modele_array[$row_act["modele_marche"]])) echo $modele_array[$row_act["modele_marche"]]; else echo $row_act['categorie']; ?></div></td>
<td class=" "><div align="center"><?php echo $row_act['methode']; ?></div></td>
<td class=" "><center>
<?php
echo do_link("","","N&deg; d'Appel d'offre du March&eacute; ".$row_act['code_marche'],"<span id='stat_$annee".$row_act['id_marche']."'>".(!empty($row_act['nao'])?$row_act['nao']:"Ajouter")."</span>","simple","./","","get_content('modal_content/edit_ppm_num.php','id_mar=".$row_act['id_marche']."&annee=".$annee."#os','modal-body_add',this.title,'iframe');",1,"margin:0px 5px 0 0; ","plan_marche.php");
?></center></td>
<td class=" "><div align="center"><?php echo $row_act['lot']; ?></div></td>
<!--<td class=" "><div align="center"><?php echo $row_act['nb_marche']; ?></div></td>-->
<td nowrap="nowrap" class=" "><div align="right">
  <?php  echo number_format($row_act["montant_usd"], 0, ',', ' ');  ?>
</div></td>
<td class=" " align="center"><a onclick="get_content('./etape_prevu_marche.php','<?php echo "id_mar=".$row_act['id_marche']."&code_act=".$row_act['code_marche']."&annee=".$annee."#os"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="<?php echo "Planification du March&eacute; Num. ".str_replace("'","\'",$row_act['code_marche']);?>" class="thickbox" dir="">Pr&eacute;vu</a></td>

<td class=" " align="center">
<?php
echo do_link("","","Suivi des Ech&eacute;ances du March&eacute; Num: ".$row_act['code_marche'],"R&eacute;alis&eacute;","simple","./","","get_content('suivi_plan_etape_marche.php','id_mar=".$row_act['id_marche']."&code_act=".$row_act['code_marche']."&annee=".$annee."#os','modal-body_add',this.title,'iframe');",1,"margin:0px 5px 0 0; ","plan_marche.php");
?>
<!--<a onclick="get_content('./suivi_plan_etape_marche.php','<?php echo "id_mar=".$row_act['id_marche']."&code_act=".$row_act['code_marche']."&annee=".$annee."#os"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="<?php echo "Suivi des Ech&eacute;ances du March&eacute; Num. ".str_replace("'","\'",$row_act['code_marche']);?>" class="thickbox" dir="">R&eacute;alis&eacute;</a>--></td>


<td class=" " align="center">
<?php
echo do_link("","","Modifier March&eacute; ".$row_act['code_marche'],"","edit","./","","get_content('modal_content/edit_ppm.php','id_mar=".$row_act['id_marche']."&annee=".$annee."#os','modal-body_add',this.title);",1,"margin:0px 5px 0 0; ","plan_marche.php");

echo do_link("","./plan_marche.php?id_sup_act=".$row_act['id_marche']."&annee=".$annee,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce march&eacute; ?');",0,"margin:0px 0 0 5px;","plan_marche.php");
?></td>
</tr>
<?php } ?>
<?php $i++; } } ?>
</tbody></table>
<?php include 'modal_add.php'; ?>