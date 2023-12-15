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
//header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_bailleur = "SELECT * FROM ".$database_connect_prefix."partenaire WHERE id_partenaire=".GetSQLValueString($id, "int");
  $liste_bailleur  = mysql_query($query_liste_bailleur , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_bailleur  = mysql_fetch_assoc($liste_bailleur);
  $totalRows_liste_bailleur  = mysql_num_rows($liste_bailleur);

  $bailleur=array();
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_part = "SELECT id_partenaire,count(bailleur) as nbbail FROM ".$database_connect_prefix."partenaire, ".$database_connect_prefix."type_part WHERE code=bailleur GROUP BY bailleur";
  $liste_part  = mysql_query($query_liste_part , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_part  = mysql_fetch_assoc($liste_part );
  $totalRows_liste_part  = mysql_num_rows($liste_part );
  if($totalRows_liste_part>0){ do{ $bailleur[$row_liste_part["id_partenaire"]]=$row_liste_part["nbbail"];  }while($row_liste_part  = mysql_fetch_assoc($liste_part ));  }
}

?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
	});
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) { ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification bailleur":"Nouveau bailleur"?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="code" class="col-md-10 control-label">Code <span class="required">*</span></label>
          <div class="col-md-11">
            <input class="form-control required" type="text" name="code" id="code" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_bailleur['code']; ?>" size="32" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_bailleur['code']."'"; ?>) check_code('verif_code.php?t=partenaire&','w=code='+this.value+' ','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div>
      </td>
      <td valign="top">
        <div class="form-group">
          <label for="sigle" class="col-md-10 control-label">Sigle <span class="required">*</span></label>
          <div class="col-md-11">
            <input class="form-control required" type="text" name="sigle" id="sigle" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_bailleur['sigle']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="definition" class="col-md-10 control-label">D&eacute;nomination <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control required" name="definition" id="definition" rows="2" cols="25"><?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?$row_liste_bailleur['definition']:''; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="adresse_mail" class="col-md-10 control-label">Adresse mail <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control email required" type="text" name="adresse_mail" id="adresse_mail" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_bailleur['adresse_mail']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="dno" class="col-md-2 control-label">DNO <span class="required">*</span></label>
          <div class="col-md-8">
            <label for="dno" class="col-md-1 control-label">Non</label>
            <div class="col-md-2">
            <input type="radio" id="dno" name="dno" value="0" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_bailleur['dno']==0) echo 'checked="checked"'; elseif(!isset($_GET["id"])) echo 'checked="checked"'; ?>></div>
            <label for="dno1" class="col-md-1 control-label">Oui</label>
            <div class="col-md-2">
            <input type="radio" id="dno1" name="dno" value="1" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_bailleur['dno']==1) echo 'checked="checked"'; ?>>&nbsp;
            </div>
          </div>
        </div>
      </td>
    </tr>
<!--    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="description" class="col-md-10 control-label">Type d'appui <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control required" name="description" id="description" rows="2" cols="25"><?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?$row_liste_bailleur['description']:''; ?></textarea>
          </div>
        </div>
      </td>
    </tr>-->
</table>
<div class="form-actions">
    <?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION["clp_niveau"]) && $_SESSION["clp_niveau"]==1 && !isset($bailleur[$row_liste_bailleur['id_partenaire']])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce bailleur ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>