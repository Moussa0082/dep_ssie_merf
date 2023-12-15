<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));*
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=ISO-8859-15');

if(isset($_GET["id"]))
{
  $id=$_GET["id"];
//  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $textsql=$query_liste_mpartenaire = "SELECT * FROM ".$database_connect_prefix."acteur WHERE id_acteur=$id ";

try{
    $liste_mpartenaire = $pdar_connexion->prepare($query_liste_mpartenaire);
    $liste_mpartenaire->execute();
    $row_liste_mpartenaire = $liste_mpartenaire ->fetch();
    $totalRows_liste_mpartenaire = $liste_mpartenaire->rowCount();
	}catch(Exception $e){ die(mysql_error_show_message($e)); }
}

$query_liste_type = "SELECT * FROM ".$database_connect_prefix."type_partenaire ORDER BY id_type_partenaire asc";
try{
    $liste_type = $pdar_connexion->prepare($query_liste_type);
    $liste_type->execute();
    $row_liste_type = $liste_type ->fetchAll();
    $totalRows_liste_type = $liste_type->rowCount();
	}catch(Exception $e){ die(mysql_error_show_message($e)); }
	
	 $query_liste_responsable = "SELECT distinct fonction, id_personnel, nom, prenom FROM ".$database_connect_prefix."personnel where /*projet like '%".$_SESSION["clp_projet"]."%' and*/ id_personnel!='admin'";
    	   try{
    $liste_responsable = $pdar_connexion->prepare($query_liste_responsable);
    $liste_responsable->execute();
    $row_liste_responsable = $liste_responsable ->fetchAll();
    $totalRows_liste_responsable = $liste_responsable->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
        $(".wysiwyg").each(function(){$(this).wysihtml5({parser: function(html) {return html;}});});
	});
</script>

<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]))?"Modification partenaire":"Nouveau partenaire"; ?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr>
      <td valign="top" width="50%">
        <div class="form-group">
          <label for="code" class="col-md-9 control-label">Code <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control required" type="text" name="code" id="code" value="<?php echo (isset($row_liste_mpartenaire['code_acteur']))?$row_liste_mpartenaire['code_acteur']:""; ?>" size="32" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_mpartenaire['code_acteur']."'"; ?>) check_code('verif_code.php?t=acteur&','w=code_acteur='+this.value+'','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>          </div>
        </div>      </td>
      <td valign="top" width="50%">
        <div class="form-group">
          <label for="nom" class="col-md-9 control-label">Sigle <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control required" type="text" name="nom" id="nom" value="<?php  if(isset($_GET["id"])) echo $row_liste_mpartenaire['nom_acteur']; ?>" size="32" />
          </div>
        </div>      </td>
    </tr>
	<tr>
      <td valign="top">
        <div class="form-group">
          <label for="adresse_partenaire" class="col-md-9 control-label">Adresse <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control required" type="text" name="adresse_partenaire" id="adresse_partenaire" value="<?php  if(isset($_GET["id"])) echo $row_liste_mpartenaire['adresse_partenaire']; ?>" size="40" />
                 </div>
        </div>      </td>
      <td valign="top">
        <div class="form-group">
          <label for="contact_partenaire" class="col-md-9 control-label">Contact <!--<span class="required">*</span>--></label>
          <div class="col-md-12">
            <input class="form-control " type="text" name="contact_partenaire" id="contact_partenaire" value="<?php  if(isset($_GET["id"])) echo $row_liste_mpartenaire['contact_partenaire']; ?>" size="32" />
          </div>
        </div>      </td>
    </tr>
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="site_web" class="col-md-9 control-label">Site web <!--<span class="required">*</span>--></label>
          <div class="col-md-12">
            <input class="form-control url" type="text" name="site_web" id="site_web" value="<?php  if(isset($_GET["id"])) echo $row_liste_mpartenaire['site_web']; ?>" size="40" />
                 </div>
        </div>      </td>
      <td valign="top">
        <div class="form-group">
          <label for="email_partenaire" class="col-md-9 control-label">Email <!--<span class="required">*</span>--></label>
          <div class="col-md-12">
            <input class="form-control email" type="text" name="email_partenaire" id="email_partenaire" value="<?php  if(isset($_GET["id"])) echo $row_liste_mpartenaire['email_partenaire']; ?>" size="32" />
          </div>
        </div>      </td>
    </tr>
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="type_partenaire" class="col-md-9 control-label">Type de partenaire <span class="required">*</span></label>
          <div class="col-md-12">
      <select name="type_partenaire" id="type_partenaire" class="form-control required">
          <option title="Choisissez un type" value="" <?php if(isset($row_liste_mpartenaire['type_partenaire']) && $row_liste_mpartenaire['type_partenaire']=="") echo 'selected="selected"'; ?>>--Choisissez--</option>
      <?php foreach($row_liste_type as $row_liste_type){  ?>
              <option title="<?php echo $row_liste_type["description"]; ?>" value="<?php echo $row_liste_type['id_type_partenaire']; ?>" <?php if(isset($row_liste_mpartenaire['type_partenaire']) && $row_liste_mpartenaire['type_partenaire']==$row_liste_type['id_type_partenaire']) echo 'selected="selected"'; ?>><?php echo $row_liste_type['nom_type_partenaire']; ?></option>
         <?php }   ?>
            </select>
              </div>
        </div>      </td>
      <td valign="top">
        <div class="form-group">
          <label for="map_partenaire" class="col-md-9 control-label">Lien cartographique <!--<span class="required">*</span>--></label>
          <div class="col-md-12">
            <input class="form-control " type="text" name="map_partenaire" id="map_partenaire" value="<?php  if(isset($_GET["id"])) echo $row_liste_mpartenaire['map_partenaire']; ?>" size="32" />
          </div>
        </div>        </td>
      </tr>
    <tr>
      <td colspan="2" valign="top">  <div class="form-group">
          <label for="point_focal" class="col-md-9 control-label">Point focal <span class="required">*</span></label>
          <div class="col-md-12">
      <select name="point_focal" id="point_focal" class="form-control required">
          <option title="Choisissez un PF" value="" <?php if(isset($row_liste_mpartenaire['point_focal']) && $row_liste_mpartenaire['point_focal']=="") echo 'selected="selected"'; ?>>--Choisissez--</option>
      <?php if($totalRows_liste_responsable>0) { foreach($row_liste_responsable as $row_liste_responsable1){   ?>
              <option value="<?php echo $row_liste_responsable1['id_personnel']?>"<?php if(isset($row_liste_mpartenaire['point_focal'])) {if (!(strcmp($row_liste_responsable1['id_personnel'], $row_liste_mpartenaire['point_focal']))) {echo "SELECTED";} } ?>><?php echo $row_liste_responsable1['fonction']." (".$row_liste_responsable1['nom']." ".$row_liste_responsable1['prenom'].")";?></option>
              <?php } } ?>
            </select>
              </div>
        </div>  </td>
      </tr>
    <tr>
      <td colspan="2">
        <div class="form-group">
          <label for="description" class="col-md-9 control-label">Description <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control required wysiwyg" name="description" id="description" rows="3" cols="25"><?php if(isset($_GET["id"])) echo $row_liste_mpartenaire['description']; //echo $textsql; ?>
            </textarea>
          </div>
        </div>      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce partenaire ?','<?php echo $_GET["id"]; ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>