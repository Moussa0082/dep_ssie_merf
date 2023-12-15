<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
if (!isset ($_SESSION["id"]) || !isset ($_GET["id"])) {
    //header(sprintf("Location: %s", "./login.php"));
    exit();
}
include_once 'api/configuration.php';
include_once 'api/essentiel.php';
require_once 'api/Fonctions.php';
require_once 'theme_components/theme_style.php';
$config = new Config;

$nfile = "projets.php";

extract($_GET);
/*if ((isset($id_sup) && !empty($id_sup))) {

    $insertSQL = $db->prepare('DELETE FROM t_projet_users WHERE id_projet_user=:id_projet_user');
    $Result1 = $insertSQL->execute(array(':id_projet_user' => $id_sup));

    $insertGoTo = $_SERVER['PHP_SELF']."?id=$id";
    if ($Result1) $insertGoTo .= "&del=ok"; else $insertGoTo .= "&del=no";
    $insertGoTo .= "&mod=1";
    header(sprintf("Location: %s", $insertGoTo)); exit();
}*/

extract($_POST);
if ((isset($MM_form)) && ($MM_form == "form1") && isset($_POST['id_categorie']) && !empty($_POST['id_categorie']))
{ //Programmes
    $date=date("Y-m-d"); $personnel = $_SESSION["id"];
  $id=$_POST['id']; $id_categorie=$_POST['id_categorie'];
 // $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
 $tranche=$_POST['tranche'];
  $budget=$_POST['budget'];
  //$id_partenaire=$_POST['id_partenaire'];
 // if ((isset($MM_insert)) && $MM_insert == "MM_insert") {
  
    $insertSQL = $db->prepare('DELETE FROM t_repartition_budget_categorie WHERE projet_bud=:projet_bud');
    $Result1 = $insertSQL->execute(array(':projet_bud' => isset($_SESSION['projet'])?$_SESSION['projet']:0));
	
	 foreach ($id_categorie as $key => $value)
  {
  	if(isset($budget[$key]) && $budget[$key]!=NULL) {
     // list($cadre_representant_programme,$cadre_niveau) = explode("|",$cadre_representant_programme);
      $insertSQL = $db->prepare('INSERT INTO t_repartition_budget_categorie (projet_bud,categorie_bud,tranche,montant, enregistrer_par) VALUES ( :projet_bud,:categorie_bud, :tranche, :montant, :enregistrer_par)');
      $Result1 = $insertSQL->execute(array(
        ':projet_bud' => isset($_SESSION['projet'])?$_SESSION['projet']:0,
       // ':bailleur_bud' => $id_partenaire[$key],
        ':categorie_bud' => $id_categorie[$key], 
        ':tranche' => $tranche[$key],
        ':montant' => $budget[$key],
        ':enregistrer_par' => $personnel
      ));
  }
}
$insertGoTo = $_SERVER['PHP_SELF']."?id=$id";
if ($Result1) $insertGoTo .= "&add=ok"; else $insertGoTo .= "&add=no";
$insertGoTo .= "&mod=1";
header(sprintf("Location: %s", $insertGoTo)); exit();
}
/*
$query_projet_en_cour = $db ->prepare('SELECT * FROM t_projets WHERE id_projet=:id_projet');
$query_projet_en_cour->execute(array(':id_projet' => $id));
$row_projet_en_cour = $query_projet_en_cour ->fetch();
$totalRows_projet_en_cour = $query_projet_en_cour->rowCount();

$bailleur_projet=explode(",",$row_projet_en_cour['bailleur']);
$structure_projet=explode(",",$row_projet_en_cour['agence_lead'].",".$row_projet_en_cour['autres_agences_recipiendaires']);

$query_liste_structure = $db ->prepare('SELECT id_partenaire as id_structure, nom_partenaire as nom_structure, sigle_partenaire as sigle FROM t_partenaires WHERE FIND_IN_SET(:type_partenaire,type_partenaire) ORDER BY sigle_partenaire asc');
$query_liste_structure->execute(array(':type_partenaire' => 1));
//if(isset($id_edit) && !empty($id_edit))
$row_liste_structure = $query_liste_structure ->fetchAll();
$totalRows_liste_structure = $query_liste_structure->rowCount();*/

//Tranche bailleur
$query_projet_cout = $db ->prepare('SELECT * FROM t_repartition_projet_budget WHERE projet_bud=:id_projet');
$query_projet_cout->execute(array(':id_projet' => isset($_SESSION['projet'])?$_SESSION['projet']:0));
$row_projet_cout = $query_projet_cout ->fetchAll();
$totalRows_projet_cout = $query_projet_cout->rowCount();
$projet_tranche_array = array();
if($totalRows_projet_cout>0){  foreach($row_projet_cout as $row_projet_cout){
if(!empty($row_projet_cout["montant"])) $projet_tranche_array[$row_projet_cout["tranche"]] = "";
} }

//Montant projet bailleur
$query_projet_cout = $db ->prepare('SELECT * FROM t_repartition_budget_categorie WHERE projet_bud=:id_projet');
$query_projet_cout->execute(array(':id_projet' => isset($_SESSION['projet'])?$_SESSION['projet']:0));
$row_projet_cout = $query_projet_cout ->fetchAll();
$totalRows_projet_cout = $query_projet_cout->rowCount();
$projet_cout_array  = array();
if($totalRows_projet_cout>0){  foreach($row_projet_cout as $row_projet_cout){
$projet_cout_array[$row_projet_cout["categorie_bud"]][$row_projet_cout["tranche"]]=$row_projet_cout["montant"];
} }

//Bailleurs
/*$query_liste_bailleur = $db ->prepare('SELECT * FROM t_partenaires WHERE FIND_IN_SET(:type_partenaire,type_partenaire)  ORDER BY nom_partenaire asc');
$query_liste_bailleur->execute(array(':type_partenaire' => 4));
$row_liste_bailleur = $query_liste_bailleur ->fetchAll();
$totalRows_liste_bailleur = $query_liste_bailleur->rowCount();*/

$query_liste_categorie = $db ->prepare('SELECT * FROM  t_categorie_depense  ORDER BY code ASC');
$query_liste_categorie->execute(/*array(':projet' => isset($_SESSION['projet'])?$_SESSION['projet']:0)*/);
$row_liste_categorie = $query_liste_categorie ->fetchAll();
$totalRows_liste_categorie = $query_liste_categorie->rowCount();

/*foreach($row_liste_bailleur as $row_liste_bailleur1){ if (in_array($row_liste_bailleur1['id_partenaire'],$bailleur_projet)) { } } */
if(count($projet_tranche_array)<=0) $tableauAnnee=array(1); else $tableauAnnee = array_keys($projet_tranche_array);
if(isset($add_tranche)) array_push($tableauAnnee,count($tableauAnnee)+1);
?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" type="image/ico" href="<?php print $config->icon_folder;?>/favicon.ico" />
    <meta name="keywords" content="<?php print $config->MetaKeys;?>" />
    <meta name="description" content="<?php print $config->MetaDesc;?>" />
    <meta name="author" content="<?php print $config->MetaAuthor;?>" />

    <!-- Vendor styles -->
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.css" />
    <link rel="stylesheet" href="vendor/metisMenu/dist/metisMenu.css" />
    <link rel="stylesheet" href="vendor/animate.css/animate.css" />
    <link rel="stylesheet" href="vendor/bootstrap/dist/css/bootstrap.css" />
    <link rel="stylesheet" href="vendor/datatables.net-bs/css/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" href="vendor/select2-3.5.2/select2.css" />
    <link rel="stylesheet" href="vendor/select2-bootstrap/select2-bootstrap.css" />

    <!-- App styles -->
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/helper.css" />

    <!-- App custom styles -->
    <link rel="stylesheet" href="styles/style.css">

<!-- Vendor scripts -->
    <script src="vendor/jquery/dist/jquery.min.js"></script>
    <script src="vendor/jquery-ui/jquery-ui.min.js"></script>
    <script src="vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="vendor/select2-3.5.2/select2.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>

    <!-- DataTables -->
    <script src="vendor/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- DataTables buttons scripts
    <script src="vendor/pdfmake/build/pdfmake.min.js"></script>
    <script src="vendor/pdfmake/build/vfs_fonts.js"></script>
    <script src="vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script> -->

    <!-- App scripts -->
    <script src="scripts/homer.js"></script>

<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 8px;background: #EBEBEB;}
</style>
<script>
$().ready(function() {
<?php if(isset($_GET['mod'])) { ?>
    // reload parent frame
    $(".close", window.parent.document).click(function(){
        parent.location.reload();
    });
    $("button[data-dismiss='modal']", window.parent.document).click(function(){
        parent.location.reload();
    });
<?php } ?>
});
</script>

<div <?php echo 'class="hpanel '.$Panel_Style.'"'; ?>>
        <div class="panel-heading">
         <span class="text-primary"><i class="fa fa-reorder"></i> Coût du Projet cat&eacute;gorie de d&eacute;penses et par tranche ($)</span>
         <!--<a href="<?php //echo $_SERVER['PHP_SELF']."?add_tranche=1&id=".$id; ?>" class="pull-right p11" title="Ajout plus de tranche" ><span class="fa fa-plus"></span> Tranche</a>-->
        </div>
        <div class="panel-body">

<script>
$().ready(function() {
    // validate the comment form when it is submitted
    $("#form1").validate();
    $(".select2-select-00").select2({allowClear:true});
    $(".modal-dialog", window.parent.document).width(780);
});
</script>
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
  <table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive  ">
    <?php $t=$colspan=0;  if($totalRows_liste_categorie>0) { ?>
    <thead>
      <tr class="titrecorps2">
        <th ><div align="left" class="Style13"><strong>Cat&eacute;gories de d&eacute;penses</strong></div></th>
        <!--<th ><div align="left" class="Style13">Valeur</div></th>-->
        <?php //if($totalRows_liste_structure>0) { $i=0; foreach($row_liste_structure as $row_liste_structure1){ if (in_array($row_liste_structure1['id_structure'],$structure_projet))
		$tranche=0; foreach($tableauAnnee as $tranche1){ $tranche++; ?>
        <th ><div align="center" class="Style31"><strong><?php echo "Tranche ".$tranche; ?></strong></div></th>
        <?php $colspan++; } //} } ?>
        <th><div align="center"><strong>Total</strong></div></th>
      </tr>
    </thead>
    <tbody>
	  <?php $total_c = $tableauMois = array(); //$tableauMois =array('0');
     // if($totalRows_liste_categorie>0) {
	   foreach($tableauAnnee as $tranche1){$total_c[$tranche1] = 0; $tableauMois[$tranche1] = $tranche1; } //}
      //$tranche=0;foreach($tableauAnnee as $tranche1){ $tranche++; ?>

    <?php $p1="j"; $t=0; $i=0; 

	 foreach($row_liste_categorie as $row_liste_categorie1){ //if (in_array($row_liste_categorie['id_partenaire'],$bailleur_projet)) { ?>
	
    <tr <?php /*if($i%2==0) echo 'bgcolor="#D2E2B1"';*/  $i=$i+1; $t=$t+1;?>>
      <td><div align="left" class="Style13"><?php echo $row_liste_categorie1['code'].": ".$row_liste_categorie1['nom_categorie']; ?></div></td>
      <?php //if($totalRows_liste_structure>0) {  foreach($row_liste_structure as $row_liste_structure1){ if (in_array($row_liste_structure1['id_structure'],$structure_projet)) { 
	  
	 $total_l = 0; $tranche=0;foreach($tableauAnnee as $tranche1){ $tranche++;?>
      <td><div align="center" class="Style31">
          <input name='budget[]' style="text-align:center" type="text" size="5"  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']!=1) echo "disabled"; ?> value="<?php if(isset($projet_cout_array[$row_liste_categorie1['id_categorie']][$tranche])){ echo $projet_cout_array[$row_liste_categorie1['id_categorie']][$tranche]; $total_l += $projet_cout_array[$row_liste_categorie1['id_categorie']][$tranche]; $total_c[$tranche] += $projet_cout_array[$row_liste_categorie1['id_categorie']][$tranche]; } ?>" class="form-control"/>
          <input name="id_categorie[]" type="hidden" size="5" value="<?php echo $row_liste_categorie1['id_categorie']; ?>"/>
          <!--<input name="id_partenaire[]" type="hidden" size="5" value="<?php //echo $row_liste_bailleur1['id_partenaire']; ?>"/>-->
		  <input name="tranche[]" type="hidden" size="5" value="<?php echo $tranche; ?>"/>
      </div></td>
      <?php } ?>
      <td align="right"><?php echo (isset($total_l) && $total_l>0)?number_format($total_l, 0, ',', ' '):"-"; ?></td>
    </tr>
    <?php }//}  ?>
	 
	  
    <?php } //} //else echo "<h3>Aucune zone disponible</h3>" ;?>
    </tbody><tfoot>
    <tr class="titrecorps2">
        <th><div align="left" class="Style13"><strong>Total</strong></div></th>
        <?php  if(isset($tableauMois) && count($tableauMois)>0) {foreach($tableauMois as $i=>$k){ ?>
        <td ><div align="right"><strong><?php echo isset($total_c[$i])?number_format($total_c[$i], 0, ',', ' '):"-"; ?></strong></div></td>
    <?php  }} ?>
        <td nowrap="nowrap"><div align="right"><strong><?php echo (isset($total_c) && count($total_c)>0)?number_format(array_sum($total_c), 0, ',', ' '):"-"; ?></strong></div></td>
    </tr>
    </tfoot>
  </table>
  <div class="form-actions" align="center">
<?php if(isset($id)){ ?>
  <input type="hidden" name="id" value="<?php echo $id; ?>" />
<?php } ?>
  <input name="submit" type="submit" class="btn btn-success btn-block" value="<?php if(isset($id_edit) && intval($id_edit)>0) echo "Modifier"; else echo "Enregistrer" ; ?>" style="width:60%;" />
  <input name="<?php if(isset($id_edit) && intval($id_edit)>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($id_edit) && intval($id_edit)>0) echo ($id_edit); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($id_edit) && intval($id_edit)>0){ ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce budget de ce projet ?','<?php echo ($id_edit); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div><div class="clear">&nbsp;</div>
</form>
<?php //} ?>
</div> </div>