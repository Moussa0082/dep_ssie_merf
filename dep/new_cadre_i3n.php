<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & D�veloppement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET['niveau']) && intval($_GET['niveau'])>0) { $niveau=intval($_GET['niveau']); } else {$niveau=1;$_GET['niveau']=1;}
$where = " niveau = ".($niveau-1)." ";

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]);
  $query_liste_activite = "SELECT * FROM ".$database_connect_prefix."cadre_i3n WHERE code='$id'";
    try{
    $liste_activite = $pdar_connexion->prepare($query_liste_activite);
    $liste_activite->execute();
    $row_liste_activite = $liste_activite ->fetch();
    $totalRows_liste_activite = $liste_activite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); 
}
}

  $query_entete = "SELECT * FROM ".$database_connect_prefix."cadre_config_i3n  LIMIT 1";
      try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $libelle = array();
  if($totalRows_entete>0){ $libelle=explode(",",$row_entete["libelle"]); $type=array(); }

?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
	});
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification ":"Nouveau "; echo $libelle[$niveau-1]; ?></h4> </div>
<div class="widget-content">

<form action="niveau_i3nn.php"  class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
   <!-- code th debut   -->
   <tr valign="top">
    
      <td>
        <div class="form-group" id="code_zone">
          <label for="code" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-9">
            <input onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_activite['code']."'"; ?>) check_code('verif_code.php?t=cadre_i3n&','w=code='+this.value+' and niveau=<?php echo $niveau; ?> and projet=<?php echo $_SESSION["clp_projet"]; ?>','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" class="form-control required" type="text" name="code" id="code" value="<?php echo isset($row_liste_activite['code'])?$row_liste_activite['code']:""; ?>" size="32" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div>
      </td>
    </tr>
    <!-- fin code  -->

<?php if($niveau>1){
  $query_entete = "SELECT * FROM ".$database_connect_prefix."cadre_config_i3n LIMIT 1";
        try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $libelle1 = array();
  if($totalRows_entete>0){ $libelle1=explode(",",$row_entete["libelle"]);}

  $query_liste_volet = "SELECT * FROM ".$database_connect_prefix."cadre_i3n WHERE niveau=".($niveau-1)." ORDER BY code ASC";
          try{
    $liste_volet = $pdar_connexion->prepare($query_liste_volet);
    $liste_volet->execute();
    $row_liste_volet = $liste_volet ->fetchAll();
    $totalRows_liste_volet = $liste_volet->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="parent" class="col-md-3 control-label"><?php echo (isset($libelle1[$niveau-1]) && !empty($libelle1[$niveau-1]))?$libelle1[$niveau-1]:"Niveau"; ?> <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="parent" id="parent" class="form-control required" >
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_volet>0) {  foreach($row_liste_volet as $row_liste_volet){  ?>
              <option value="<?php echo $row_liste_volet['code']; ?>" <?php if (isset($row_liste_activite['parent']) && $row_liste_volet['code']==$row_liste_activite['parent']) {echo "SELECTED";} ?>><?php echo $row_liste_volet['code'].": ".$row_liste_volet['intitule']; ?></option>
              <?php } } ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
<?php }else echo '<input style="display:none;" class="form-control required" type="text" name="parent" id="parent" value="0" size="32" />'; ?>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="intitule" class="col-md-3 control-label"><?php echo (isset($libelle[$niveau-1]) && !empty($libelle[$niveau-1]))?$libelle[$niveau-1]:"Activit&eacute;"; ?> <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="3" type="text" name="intitule" id="intitule"><?php echo isset($row_liste_activite['intitule'])?$row_liste_activite['intitule']:""; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <!-- budget champ  -->
    <tr valign="top">
  <td>
    <div class="form-group">
      <label for="budget" class="col-md-3 control-label">Budget <span class="required">*</span></label>
      <div class="col-md-9">
        <input class="form-control required" type="number" name="budget" id="budget" value="<?php echo isset($row_liste_activite['budget']) ? $row_liste_activite['budget'] : 00; ?>" size="32" />
      </div>
    </div>
  </td>
</tr>
<!-- fin champ budget  -->
</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
  <?php } ?>
  <input type="hidden" name="niveau" value="<?php echo $niveau; ?>" />
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION['clp_id']) && $_SESSION['clp_id']=="admin"){ ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>