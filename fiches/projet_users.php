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
if ((isset($id_sup) && !empty($id_sup))) {

    $insertSQL = $db->prepare('DELETE FROM t_projet_users WHERE id_projet_user=:id_projet_user and structure_up=:structure_up');
    $Result1 = $insertSQL->execute(array(':id_projet_user' => $id_sup,':structure_up' => $_SESSION['structure']));

    $insertGoTo = $_SERVER['PHP_SELF']."?id=$id";
    if ($Result1) $insertGoTo .= "&del=ok"; else $insertGoTo .= "&del=no";
    $insertGoTo .= "&mod=1";
    header(sprintf("Location: %s", $insertGoTo)); exit();
}

extract($_POST);
if ((isset($MM_form)) && ($MM_form == "form1"))
{ //Projet users
    $date=date("Y-m-d"); $personnel = $_SESSION["id"];

  if ((isset($MM_insert)) && $MM_insert == "MM_insert") {

    $insertSQL = $db->prepare('DELETE FROM t_projet_users WHERE projet_up=:projet_up and structure_up=:structure_up');
    $Result1 = $insertSQL->execute(array(':projet_up' => $id,':structure_up' => $_SESSION['structure']));

      $insertSQL = $db->prepare('INSERT INTO t_projet_users (projet_up,structure_up,  personnel_up, enregistrer_par) VALUES ( :projet_up,:structure_up, :personnel_up, :enregistrer_par)');
      $Result1 = $insertSQL->execute(array(
        ':projet_up' => $id,
        ':structure_up' => $_SESSION['structure'],
        ':personnel_up' => implode(',',$personnel_up),
        ':enregistrer_par' => $personnel
      ));

    $insertGoTo = $_SERVER['PHP_SELF']."?id=$id";
    if ($Result1) $insertGoTo .= "&del=ok"; else $insertGoTo .= "&del=no";
    $insertGoTo .= "&mod=1";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
}

$query_liste_representant = $db ->prepare('SELECT * FROM t_projet_users WHERE projet_up=:projet_up and structure_up=:structure_up');
$query_liste_representant->execute(array(':projet_up' => $id,':structure_up' => $_SESSION['structure']));
$row_liste_representant = $query_liste_representant ->fetch();
$totalRows_liste_representant = $query_liste_representant->rowCount();
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
         <span class="text-primary"><i class="fa fa-reorder"></i> Personnels dédiés au Projet</span>
        </div>
        <div class="panel-body">

<?php
//users
if(isset($_SESSION["niveau"]) && $_SESSION["niveau"]==0) {
$query_personnel = $db ->prepare('SELECT P.*, F.fonction, S.sigle_partenaire as sigle, S.nom_partenaire as nom_structure, S.id_partenaire as id_structure FROM t_users P, t_fonction F, t_partenaires S WHERE FIND_IN_SET(:type_partenaire,S.type_partenaire) and F.structure=S.id_partenaire and F.id_fonction=P.fonction ORDER BY S.sigle_partenaire desc');
$query_personnel->execute(array(':type_partenaire' => 1));
}
else
{
$query_personnel = $db ->prepare('SELECT P.*, F.fonction, S.sigle_partenaire as sigle, S.nom_partenaire as nom_structure, S.id_partenaire as id_structure FROM t_users P, t_fonction F, t_partenaires S WHERE FIND_IN_SET(:type_partenaire,S.type_partenaire) and F.structure=S.id_partenaire and F.id_fonction=P.fonction and S.id_partenaire=:structure ORDER BY S.sigle_partenaire desc');
$query_personnel->execute(array(':type_partenaire' => 1,':structure' => $_SESSION['structure']));}
$row_personnel = $query_personnel ->fetchAll();
$totalRows_personnel = $query_personnel->rowCount();
?>
<script>
$().ready(function() {
    // validate the comment form when it is submitted
    $("#form1").validate();
    $(".select2-select-00").select2({allowClear:true});
});
</script>
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" class="fixed" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:13px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="personnel_up" class="col-md-12 control-label">Personnels <span class="required">*</span></label>
          <div class="col-md-12">
            <select name="personnel_up[]" id="personnel_up" class="select2-select-00 full-width-fix required" multiple="multiple" style="width: 100%" data-placeholder="S&eacute;lectionnez un ou plusieurs utilisateurs" >
              <option></option>
              <?php $elem = isset($row_liste_representant["personnel_up"])?explode(',',$row_liste_representant["personnel_up"]):array(); $i=0; if($totalRows_personnel>0) { $j=0; foreach($row_personnel as $row_personnel) { ?>
              <option value="<?php echo $row_personnel['id_user']; ?>" <?php if (in_array($row_personnel['id_user'],$elem)) {echo "SELECTED";} ?>><?php echo $row_personnel['prenom']." ".$row_personnel['nom']." (".$row_personnel['fonction'].")"; ?></option>
              <?php } } ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($id)){ ?>
  <input type="hidden" name="id" value="<?php echo $id; ?>" />
<?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($id_edit) && intval($id_edit)>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($id_edit) && intval($id_edit)>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($id_edit) && intval($id_edit)>0) echo ($id_edit); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($id_edit) && intval($id_edit)>0){ ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce personnel de ce projet ?','<?php echo ($id_edit); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div><div class="clear">&nbsp;</div>
</form>
<?php //} ?>
</div> </div>