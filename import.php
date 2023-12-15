<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";

$format["acteur"] = array("Code","Sigle","Lib&eacute;l&eacute;","Cat&eacute;gorie");
$format["bailleur"] = array("Code","Nom");
$format["convention"] = array("Code","Intitul&eacute;","Code Bailleur","Montant","Date");
$format["plan_analytique"] = array("Fichier exporter de TomPro (Tomate)");
$format["plan_budget"] = array("Fichier exporter de TomPro (Tomate)");
$format["categorie_depense"] = array("Fichier exporter de TomPro (Tomate)");
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form0").validate();
	});
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) { ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Importation</h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form0" id="form0" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr>
      <td valign="middle">
        <div class="form-group">
          <label for="fichier" class="col-md-5 control-label">Fichier &agrave; importer <span class="required">*</span></label>
          <div class="col-md-6">
            <input class="form-control required" type="file" name="fichier" id="fichier" value="" size="32" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
          </div>
        </div>
      </td>
    </tr>
    <tr><td colspan="2">
<?php if(isset($format[$_GET["id"]])){ ?>
<h4>Format &agrave; respecter :</h4>
<table id="mtable" class="table table-striped table-bordered table-hover table-responsive dataTable " align="center" >
  <tr>
<?php foreach($format[$_GET["id"]] as $a) { ?>
    <td valign="middle"><div align="left"><strong><?php echo $a; ?></strong></div></td>
<?php } ?>
  </tr>
</table>
    </td></tr>
</table>
<?php } ?>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="Lancer l'Importation" onclick="return confirm('L\'importation &eacute;crasera les données pr&eacute;c&eacute;damment enregistr&eacute;es. Continuez ?');" />
  <input name="MM_insert" type="hidden" value="MM_insert" size="32" alt="">
  <input name="MM_form" id="MM_form" type="hidden" value="<?php if(isset($_GET["form"])) echo $_GET["form"]; else echo "form0" ?>" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
<?php if(isset($_GET["niveau"])){ ?>
  <input type="hidden" name="niveau" value="<?php echo intval($_GET["niveau"]); ?>" />
<?php } ?>
</div>
</form>

</div> </div>
<?php } ?>