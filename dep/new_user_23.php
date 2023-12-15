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
//header('Content-Type: text/html; charset=ISO-8859-15');

if(isset($_GET["id"]) && intval($_GET["id"])>0)
{
  $id=intval($_GET["id"]);
  $query_liste_personnel = "SELECT * FROM ".$database_connect_prefix."personnel WHERE N=$id ";
  try{
        $listepersonnel = $pdar_connexion->prepare($query_liste_personnel);
        $listepersonnel->execute();
        $row_liste_personnel = $listepersonnel ->fetch();
        $totalRows_liste_personnel = $listepersonnel->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }}

//Departement
$query_fonction = "SELECT * FROM ".$database_connect_prefix."fonction ";
try{
    $fonction = $pdar_connexion->prepare($query_fonction);
    $fonction->execute();
    $row_fonction = $fonction ->fetchAll();
    $totalRows_fonction = $fonction->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//Structure
$query_structure = "SELECT * FROM ".$database_connect_prefix."ugl ";
try{
    $structure = $pdar_connexion->prepare($query_structure);
    $structure->execute();
    $row_structure = $structure ->fetchAll();
    $totalRows_structure = $structure->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); } 
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification d'utilisateur":"Nouvel utilisateur"?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="id" class="col-md-10 control-label">Identifiant <span class="required">*</span></label>
          <div class="col-md-11">
          <input type="text" <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?'readonly="readonly"':''; ?> name="id_personnel" id="id" value="<?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_liste_personnel['id_personnel']:''; ?>" size="32" class="form-control required" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_personnel['id_personnel']."'"; ?>) check_code('verif_code.php?t=personnel&','w=id_personnel='+this.value+' ','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div>
      </td>
      <td valign="top">
        <div class="form-group">
          <label for="password" class="col-md-10 control-label">Mot de passe </label>
          <div class="col-md-12">
            <input type="password" name="password" id="password" value="" size="32" alt="*" class="form-control " />
          </div>
        </div>
      </td>
   </tr>

    <tr>
	<tr>
      <td valign="top">
        <div class="form-group">
          <label for="titre" class="col-md-10 control-label">Titre <span class="required">*</span></label>
          <div class="col-md-11">
            <select name="titre" id="titre" class="form-control required" >
              <option value="M" <?php if (isset($row_liste_personnel['titre']) && !(strcmp("M", $row_liste_personnel['titre']))) {echo "SELECTED";}  ?>>Monsieur</option>
              <option value="Mme" <?php if (isset($row_liste_personnel['titre']) && !(strcmp("Mme", $row_liste_personnel['titre']))) {echo "SELECTED";}  ?>>Madame</option>
			  <option value="Mlle" <?php if (isset($row_liste_personnel['titre']) && !(strcmp("Mlle", $row_liste_personnel['titre']))) {echo "SELECTED";}  ?>>Mademoiselle</option>
			  <option value="Pr" <?php if (isset($row_liste_personnel['titre']) && !(strcmp("Pr", $row_liste_personnel['titre']))) {echo "SELECTED";}  ?>>Professeur</option>
			  <option value="Dr" <?php if (isset($row_liste_personnel['titre']) && !(strcmp("Dr", $row_liste_personnel['titre']))) {echo "SELECTED";}  ?>>Docteur</option>
            </select>
          </div>
        </div>
    </td>
      <td valign="top"> <div class="form-group">
          <label for="fonction" class="col-md-10 control-label">Fonction <span class="required">*</span></label>
          <div class="col-md-11">
            <select name="fonction" id="fonction" class="form-control required">
      <?php foreach($row_fonction as $row_fonction){ ?>
              <option title="<?php echo $row_fonction["description"]; ?>" value="<?php echo $row_fonction['fonction']; ?>" <?php if(isset($row_liste_personnel['fonction']) && $row_liste_personnel['fonction']==$row_fonction['fonction']) echo 'selected="selected"'; ?>><?php echo $row_fonction['fonction']; ?></option> <?php }   ?>
            </select>
          </div>
        </div>
       
    </td>
    </tr>
      <td valign="top">
        <div class="form-group">
          <label for="nom" class="col-md-10 control-label">Nom <span class="required">*</span></label>
          <div class="col-md-11">
            <input class="form-control required input-datepicker" type="text" name="nom" id="nom" value="<?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_liste_personnel['nom']:''; ?>" size="32" />
          </div>
        </div>
      </td>
      <td valign="top">
        <div class="form-group">
          <label for="prenom" class="col-md-10 control-label">Prenom(s) <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control required" type="text" name="prenom" id="prenom" value="<?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_liste_personnel['prenom']:''; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>

    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="contact" class="col-md-10 control-label">Contact <span class="required">*</span></label>
          <div class="col-md-11">
            <input class="form-control required" type="text" value="<?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_liste_personnel['contact']:''; ?>" name="contact" id="contact" size="32"/>
          </div>
        </div>
      </td>
      <td valign="top">
        <div class="form-group">
          <label for="mail" class="col-md-10 control-label">Email <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control email required" type="text" value="<?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_liste_personnel['email']:''; ?>" name="email" id="mail" size="32"/>
          </div>
        </div>
      </td>
    </tr>
    <tr>
<!--      <td valign="top">
        <div class="form-group">
          <label for="structure" class="col-md-10 control-label">Structure <span class="required">*</span></label>
          <div class="col-md-11">
            <select name="structure" id="structure" class="form-control required" onchange="get_content('menu_projet.php','id='+this.value+'<?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"id_s=".$row_liste_personnel['projet']:''; ?>','projet_id','');">
            <option value="">Selectionnez</option>
      <?php /*do{ ?>
              <option value="<?php echo $row_structure['code_structure']; ?>" <?php if($row_liste_personnel['structure']==$row_structure['code_structure']) echo 'selected="selected"'; ?>><?php echo $row_structure['nom_structure']; ?></option> <?php } while ($row_structure = mysql_fetch_assoc($structure));*/  ?>
            </select>
          </div>
        </div>
    </td>-->
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="projet" class="col-md-10 control-label">Guichets </label>
          <div class="col-md-11" id="projet_id">
          </div>
        </div>
    </td>
    </tr>
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="structure" class="col-md-10 control-label">Unit&eacute; de gestion  <span class="required">*</span></label>
          <div class="col-md-11">
            <select name="structure" id="structure" class="form-control required">
			    <option title="Choisissez une unité de gestion" value="" <?php if(isset($row_liste_personnel['structure']) && $row_liste_personnel['structure']=="") echo 'selected="selected"'; ?>>--Choisissez--</option>
      <?php foreach($row_structure as $row_structure){ ?>
              <option title="<?php echo $row_structure["nom_ugl"]; ?>" value="<?php echo $row_structure['code_ugl']; ?>" <?php if(isset($row_liste_personnel['structure']) && $row_liste_personnel['structure']==$row_structure['code_ugl']) echo 'selected="selected"'; ?>><?php echo $row_structure['nom_ugl']; ?></option>
			   <?php }   ?>
            </select>
          </div>
        </div>
    </td>
      <td valign="top">
        <div class="form-group">
          <label for="niveau" class="col-md-10 control-label">Niveau d'acc&egrave;s <span class="required">*</span></label>
          <div class="col-md-11">
            <select name="niveau" id="niveau" class="form-control required" >
              <option value="2" <?php if (isset($row_liste_personnel['niveau']) && !(strcmp("2", $row_liste_personnel['niveau']))) {echo "SELECTED";}  ?>>Visiteur</option>
              <option value="1" <?php if (isset($row_liste_personnel['niveau']) && !(strcmp("1", $row_liste_personnel['niveau']))) {echo "SELECTED";}  ?>>Edition</option>
            </select>
          </div>
        </div>
    </td>
    </tr>
    <tr>
      <td colspan="2" valign="top">
        <div class="form-group">
          <label for="description_fonction" class="col-md-10 control-label">Description </label>
          <div class="col-md-12">
            <textarea name="description_fonction" cols="25" rows="1" class="form-control" id="description_fonction"><?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?$row_liste_personnel['description_fonction']:''; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cet acteur ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

<script type="text/javascript" >
get_content('menu_projet.php','id=<?php echo ((isset($row_liste_personnel["structure"]))?$row_liste_personnel["structure"]:$_SESSION["clp_structure"]).'&id_s='.((isset($row_liste_personnel["projet"]))?$row_liste_personnel["projet"]:""); ?>','projet_id','');
</script>

</div> </div>