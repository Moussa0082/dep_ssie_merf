<?php    
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & D�veloppement: SEYA SERVICES */
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
  $query_liste_activite = "SELECT * FROM ".$database_connect_prefix."recommandation_mission WHERE id_recommandation='$id'";
  $liste_activite  = mysql_query_ruche($query_liste_activite , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_activite  = mysql_fetch_assoc($liste_activite);
  $totalRows_liste_activite  = mysql_num_rows($liste_activite);
}

// query sous composante

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_scp = "SELECT * FROM ".$database_connect_prefix."rubrique_projet order by code_rub";
$liste_scp  = mysql_query_ruche($query_liste_scp , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_scp  = mysql_fetch_assoc($liste_scp);
$totalRows_liste_scp  = mysql_num_rows($liste_scp);

// query  composante

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_respo = "SELECT * FROM ".$database_connect_prefix."personnel";
$liste_respo  = mysql_query_ruche($query_liste_respo , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_respo  = mysql_fetch_assoc($liste_respo);            
$totalRows_liste_respo  = mysql_num_rows($liste_respo);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_volet = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE and niveau=1 ORDER BY code ASC";
$liste_volet  = mysql_query_ruche($query_liste_volet , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_volet  = mysql_fetch_assoc($liste_volet);
$totalRows_liste_volet  = mysql_num_rows($liste_volet);
  
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT * FROM ".$database_connect_prefix."niveau_config WHERE ".$_SESSION["clp_where"]." LIMIT 1";
$entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);
$libelle = $code_len = array();
if($totalRows_entete>0){ $libelle=explode(",",$row_entete["libelle"]); $code_len=explode(",",$row_entete["code_number"]); }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_max = "SELECT max(ref_no)+1 as max FROM ".$database_connect_prefix."recommandation_mission WHERE mission='".$_GET["mission"]."' ";
$liste_max  = mysql_query_ruche($query_liste_max , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_max  = mysql_fetch_assoc($liste_max);
$totalRows_liste_max  = mysql_num_rows($liste_max);
$max = ($row_liste_max["max"]>0)?$row_liste_max["max"]:1;

?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form4").validate();
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
	});
</script>
<style>
.ui-datepicker-append {display: none;}
</style>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<4) {?>
<?php if(!isset($_GET['rapport'])) { ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification recommandation":"Nouvelle recommandation"; ?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2" id="form4" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="type" class="col-md-3 control-label"><?php echo (isset($libelle[0]) && !empty($libelle[0]))?$libelle[0]:"Niveau"; ?>&nbsp;&nbsp; </label>
          <div class="col-md-9">
            <select name="volet_recommandation" id="type" class="form-control " >
              <?php if($totalRows_liste_volet>0) { ?>
                                <option value="">-- Choisissez --</option>
                                <?php do { $libeller = $row_liste_volet['intitule']; $libeller = (strlen($libeller)>50)?substr($libeller,0,50)." ...":$libeller; ?>
                                <option value="<?php echo $row_liste_volet['code'];?>"
								<?php if(isset($_GET['id'])) {if (!(strcmp($row_liste_activite['volet_recommandation'], $row_liste_volet['code']))) {echo "SELECTED";} } ?>>
								<?php echo $row_liste_volet['code'].": ".$libeller;?></option>
                                <?php } while ($row_liste_volet = mysql_fetch_assoc($liste_volet));}  ?>

            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="type" class="col-md-3 control-label">Domaine&nbsp;&nbsp; <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="rubrique" id="type" class="form-control required" >
              <?php if($totalRows_liste_scp>0) { ?>
                                <option value="">-- Choisissez --</option>
                                <?php

do { $libeller = $row_liste_scp['nom_rubrique']; $libeller = (strlen($libeller)>50)?substr($libeller,0,50)." ...":$libeller;

?>
                                <option value="<?php echo $row_liste_scp['code_rub'];?>"<?php if(isset($_GET['id'])) {if (!(strcmp($row_liste_activite['rubrique'], $row_liste_scp['code_rub']))) {echo "SELECTED";} } ?>><?php echo $libeller;?></option>
                                <?php

} while ($row_liste_scp = mysql_fetch_assoc($liste_scp));}
 ?>

            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="numero" class="col-md-3 control-label">R&eacute;f.<span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" type="text" name="numero" id="numero" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['numero']; ?>" size="32" />
          </div>

          <label for="ref_no" class="col-md-3 control-label">Num&eacute;ro<span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" type="text" name="ref_no" id="ref_no" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['ref_no']; else echo $max; ?>" size="32"  />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="recommandation" class="col-md-3 control-label">Recommandation <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="2" type="text" name="recommandation" id="recommandation"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['recommandation']; ?></textarea>
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="type" class="col-md-3 control-label">Type <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="type" id="type" class="form-control required" >
              <option value="">Selectionnez</option>
              <option value="A �ch�ance"<?php if(isset($_GET['id'])){if (!(strcmp("A �ch�ance", $row_liste_activite['type']))) {echo "SELECTED";}} ?>>A &eacute;ch&eacute;ance</option>
                                <option value="Continu"<?php if(isset($_GET['id'])){if (!(strcmp("Continu", $row_liste_activite['type']))) {echo "SELECTED";}} ?>>Continu</option>
<option value="Imm�diat"<?php if(isset($_GET['id'])){if (!(strcmp("Imm�diat", $row_liste_activite['type']))) {echo "SELECTED";}} ?>>Imm�diat</option>
<option value="Imm�diat et Continu"<?php if(isset($_GET['id'])){if (!(strcmp("Imm�diat et Continu", $row_liste_activite['type']))) {echo "SELECTED";}} ?>>Imm�diat et Continu</option>

            </select>
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="date_buttoir" class="col-md-3 control-label">Date buttoir<!-- d'ex&eacute;cution--> <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control datepicker required" type="text" name="date_buttoir" id="date_buttoir" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',$row_liste_activite['date_buttoir']))); else echo date("d/m/Y"); ?>" size="10" />
          </div>
        </div>
      </td>
    </tr>

    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="responsable_interne" class="col-md-3 control-label">Responsable <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="responsable_interne" id="responsable_interne" class="form-control required" >
              <option value="">Selectionnez</option>
              <?php
			if($totalRows_liste_respo>0) { do { ?>
                              <option value="<?php echo $row_liste_respo['id_personnel'];?>"<?php if(isset($_GET['id'])) {if (!(strcmp($row_liste_activite['responsable_interne'], $row_liste_respo['id_personnel']))) {echo "SELECTED";} } ?>><?php echo $row_liste_respo['fonction']." (".$row_liste_respo['nom']." ".$row_liste_respo['prenom'].")";?></option>
                              <?php } while ($row_liste_respo = mysql_fetch_assoc($liste_respo));}  ?>

            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="responsable" class="col-md-3 control-label">Autres responsables <!--<span class="required">*</span>--></label>
          <div class="col-md-9">
            <textarea class="form-control" cols="200" rows="1" type="text" name="responsable" id="responsable"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['responsable']; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="observation" class="col-md-3 control-label">Observations </label>
          <div class="col-md-9">
            <textarea class="form-control " cols="200" rows="3" type="text" name="observation" id="observation"><?php echo isset($row_liste_activite['observation'])?$row_liste_activite['observation']:""; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET["mission"])){ ?>
  <input type="hidden" name="mission" value="<?php echo $_GET["mission"]; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer la recommandation ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>

<?php } else { //Rapport ?>

<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php if(isset($_GET['id'])) echo "Ajout de rapport"; ?></h4></div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form4" id="form4" novalidate="novalidate">
  <table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:10px;">
    <tr>
      <td colspan="2" valign="middle"><div class="form-group">
          <label for="rapport" class="col-md-4 control-label">Rapport <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && !empty($row_liste_activite['rapport'])) echo ""; else echo '<span class="required">*</span>'; ?></label>
          <div class="col-md-8">
            <input class="form-control <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && !empty($row_liste_activite['rapport'])) echo ""; else echo "required"; ?>" type="file" name="rapport" id="rapport" value="" size="32" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/pdf,application/vnd.ms-word,image/jpeg,.doc,.docx,.zip,.rar"  />
          </div>
      </div></td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET["mission"])){ ?>
<input type="hidden" name="mission" value="<?php echo $_GET["mission"]; ?>" />
<?php } ?>
<input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
<input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer le rapport ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form4" size="32" alt="">
<!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>

<?php } ?>

<?php } ?>