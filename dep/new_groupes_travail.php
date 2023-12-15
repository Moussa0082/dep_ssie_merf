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

if(isset($_GET["id"]))
{
  $id=$_GET["id"];
 
  $query_liste_groupes_travail = "SELECT * FROM ".$database_connect_prefix."groupes_travail WHERE id_groupes_travail='$id' ";
  try{
  $liste_groupes_travail = $pdar_connexion->prepare($query_liste_groupes_travail);
  $liste_groupes_travail->execute();
  $row_liste_groupes_travail = $liste_groupes_travail ->fetch();
  $totalRows_liste_groupes_travail = $liste_groupes_travail->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
}

   //autre info
        $query_liste_partenaire = "SELECT * FROM ".$database_connect_prefix."partenaire order by sigle ";
		  try{
  $liste_partenaire = $pdar_connexion->prepare($query_liste_partenaire);
  $liste_partenaire->execute();
  $row_liste_partenaire = $liste_partenaire ->fetchAll();
  $totalRows_partenaire = $liste_partenaire->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
         /* $tableau_Partenaire=$tableau_Partenaire_Desc=array();
        if($totalRows_liste_partenaire>0){ foreach($row_liste_partenaire as $row_liste_partenaire){
        $tableau_Partenaire[$row_liste_partenaire['id_partenaire']]=$row_liste_partenaire['sigle'];
        $tableau_Partenaire_Desc[$row_liste_partenaire['id_partenaire']]=strip_tags($row_liste_partenaire['definition']);
        } }*/

$query_liste_users = "SELECT * FROM ".$database_connect_prefix."personnel ";
  try{
  $liste_users = $pdar_connexion->prepare($query_liste_users);
  $liste_users->execute();
  $row_liste_users = $liste_users ->fetchAll();
  $totalRows_liste_users = $liste_users->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

//Structure
        $query_structure = "SELECT * FROM ".$database_connect_prefix."domaine_activite order by code_domaine ";
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
        $(".select2-select-00").select2({allowClear:true});
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'',dateFormat:"dd/mm/yy"});
	});
</script>

<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]))?"Modification groupe de travail":"Nouveau groupe de travail"; ?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr>
      <td valign="top" width="50%">
        <div class="form-group">
          <label for="code_groupes_travail" class="col-md-10 control-label">Code <span class="required">*</span></label>
          <div class="col-md-12">
            <input style="width: 80px;" class="form-control required" type="text" name="code_groupes_travail" id="code_groupes_travail" value="<?php echo (isset($row_liste_groupes_travail['code_groupes_travail']))?$row_liste_groupes_travail['code_groupes_travail']:""; ?>" size="32" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_groupes_travail['code_groupes_travail']."'"; ?>) check_code('verif_code.php?t=groupes_travail&','w=code_groupes_travail='+this.value+'','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div>
      </td>
      <td valign="top" width="50%">
        <div class="form-group">
          <label for="date_creation" class="col-md-10 control-label">Date de cr&eacute;ation <span class="required">*</span></label>
          <div class="col-md-12">
            <input style="width: 80px;" class="form-control datepicker " type="text" name="date_creation" id="date_creation" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo implode('/',array_reverse(explode('-',$row_liste_groupes_travail['date_creation']))); else echo date("d/m/Y"); ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td valign="top" colspan="2">
        <div class="form-group">
          <label for="nom_groupes_travail" class="col-md-10 control-label">Nom du groupe <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control" name="nom_groupes_travail" id="nom_groupes_travail" rows="1" cols="25"><?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_groupes_travail['nom_groupes_travail']; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td valign="top" colspan="2">
       <div class="form-group">
          <label for="partenaire" class="col-md-10 control-label">Partenaires concern&eacute;s <span class="required">*</span></label>
          <div class="col-md-12">
            <select id="partenaire" name="partenaire[]" class="select2-select-00 col-md-12 full-width-fix required" multiple size="5">
              <?php if($totalRows_partenaire>0){ $elem = explode(',',$row_liste_groupes_travail["partenaire"]); foreach($row_liste_partenaire as $row_partenaire){ ?>
              <option value="<?php echo $row_partenaire['id_partenaire']; ?>" <?php if (isset($row_liste_groupes_travail["partenaire"]) && in_array($row_partenaire['id_partenaire'],$elem)) {echo "SELECTED";} ?>><?php echo $row_partenaire['sigle']; ?></option>
                <?php  } } ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td valign="top" colspan="2">
       <div class="form-group">
          <label for="thematiques" class="col-md-10 control-label">Th&eacute;matiques concern&eacute;es <span class="required">*</span></label>
          <div class="col-md-12">
            <select id="thematiques" name="thematiques[]" class="select2-select-00 col-md-12 full-width-fix " multiple size="5">
              <?php if($totalRows_structure>0){ $elem = explode(',',$row_liste_groupes_travail["thematiques"]); foreach($row_structure as $row_structure){ ?>
              <option value="<?php echo $row_structure['id_domaine']; ?>" <?php if (isset($row_liste_groupes_travail["thematiques"]) && in_array($row_structure['id_domaine'],$elem)) {echo "SELECTED";} ?>><?php echo $row_structure['nom_domaine']; ?></option>
                <?php  }  } ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="secretaire" class="col-md-10 control-label">Secr&eacute;taire technique <span class="required">*</span></label>
          <div class="col-md-12">
            <select id="secretaire" name="secretaire" class="select2-select-00 full-width-fix required" >
              <?php if($totalRows_liste_users>0){ foreach($row_liste_users as $row_liste_users){  ?>
              <option value="<?php echo $row_liste_users['id_personnel']; ?>" <?php if (isset($row_liste_groupes_travail["secretaire"]) && $row_liste_users['id_personnel']==$row_liste_groupes_travail["secretaire"]) {echo "SELECTED";} ?>><?php echo $row_liste_users['prenom']." ".$row_liste_users['nom']." (".(isset($tableau_Partenaire[$row_liste_users['partenaire']])?$tableau_Partenaire[$row_liste_users['partenaire']]:$row_liste_users['partenaire']).")"; ?></option>
                <?php  } } ?>
            </select>
          </div>
        </div>
      </td>
      <td valign="top" width="50%">
        <div class="form-group">
          <label for="actif" class="col-md-10 control-label">Statut du groupe <span class="required">*</span></label>
          <div class="col-md-6">
            <label for="actif" class="control-label">Actif </label>&nbsp;&nbsp;<input type="radio" name="actif" id="actif" value="0" <?php if (isset($row_liste_groupes_travail['actif']) && !(strcmp("0", $row_liste_groupes_travail['actif']))) {echo 'checked="checked"';} if(!isset($_GET["id"])) echo 'checked="checked"';  ?> >
          </div>
          <div class="col-md-6">
            <label for="actif1" class="control-label">Inactif </label>&nbsp;&nbsp;<input type="radio" name="actif" id="actif1" value="1" <?php if (isset($row_liste_groupes_travail['actif']) && !(strcmp("1", $row_liste_groupes_travail['actif']))) {echo 'checked="checked"';}  ?>>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <div class="form-group">
          <label for="description" class="col-md-10 control-label">Description </label>
          <div class="col-md-12">
            <textarea class="form-control" name="description" id="description" rows="2" cols="25"><?php if(isset($_GET["id"])) echo $row_liste_groupes_travail['description']; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce groupe de travail ?','<?php echo $_GET["id"]; ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>