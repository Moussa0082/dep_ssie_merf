<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
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

if(isset($_GET["id"])) { $id=$_GET["id"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_data = "SELECT * FROM indicateur_sygri1_projet where id_indicateur_sygri_niveau1_projet='$id'";
$edit_data = mysql_query($query_edit_data, $pdar_connexion) or die(mysql_error());
$row_edit_data = mysql_fetch_assoc($edit_data);
$totalRows_edit_data = mysql_num_rows($edit_data);
}else $id=0;

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_referentiel = "SELECT * FROM referentiel_indicateur where id_ref_ind not in (select referentiel from indicateur_sygri1_projet where id_indicateur_sygri_niveau1_projet!=$id and projet='".$_SESSION["clp_projet"]."') and type_ref_ind=1 order by code_ref_ind";
$liste_referentiel = mysql_query($query_liste_referentiel, $pdar_connexion) or die(mysql_error());
$row_liste_referentiel = mysql_fetch_assoc($liste_referentiel);
$totalRows_liste_referentiel = mysql_num_rows($liste_referentiel);*/


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_sygri = "SELECT * FROM liste_indicateur_sygri where id_indicateur_sygri_fida not in (select id_sygri from indicateur_sygri1_projet where id_indicateur_sygri_niveau1_projet!=$id and projet='".$_SESSION["clp_projet"]."') and niveau_sygri=1";
$liste_sygri = mysql_query($query_liste_sygri, $pdar_connexion) or die(mysql_error());
$row_liste_sygri = mysql_fetch_assoc($liste_sygri);
$totalRows_liste_sygri = mysql_num_rows($liste_sygri);


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_gp = "SELECT id_groupe, nom_groupe from groupe_indicateur order by code_groupe";
$liste_gp  = mysql_query($query_liste_gp , $pdar_connexion) or die(mysql_error());
$row_liste_gp = mysql_fetch_assoc($liste_gp);
$totalRows_liste_gp  = mysql_num_rows($liste_gp);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_scp = "SELECT * FROM activite_projet WHERE  niveau=2 and activite_projet.projet='".$_SESSION["clp_projet"]."'";
$liste_scp  = mysql_query($query_liste_scp , $pdar_connexion) or die(mysql_error());
$row_liste_scp = mysql_fetch_assoc($liste_scp);
$totalRows_liste_scp  = mysql_num_rows($liste_scp);


?>
<script>
	$().ready(function() {
		$("#form1").validate();
		 $(".modal-dialog", window.parent.document).width(700);
        $(".select2-select-00").select2({allowClear:true});
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});});

</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification Indicateurs SYGRI 1<sup>er</sup> Niveau":"Nouveau Indicateurs SYGRI 1<sup>er</sup> Niveau"?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="ordre" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-4">
            <input class="form-control required" type="text" name="ordre" id="ordre" value="<?php echo (isset($row_edit_data['ordre']))?$row_edit_data['ordre']:""; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
 <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="scomposante" class="col-md-3 control-label">Sous composante <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="scomposante" id="scomposante" class="form-control required" >
              <option value="">Selectionnez</option>
              <?php do { ?>
              <option value="<?php echo $row_liste_scp['code']; ?>" <?php if (isset($row_edit_data['scomposante']) && $row_liste_scp['code']==$row_edit_data['scomposante']) {echo "SELECTED";} ?>><?php echo $row_liste_scp['code'].": ".$row_liste_scp['intitule']; ?></option>
              <?php }while($row_liste_scp = mysql_fetch_assoc($liste_scp)); ?>
			   <option value="0">Non definie</option>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
       		 <div class="form-group">
          <label for="id_sygri" class="col-md-3 control-label">Indicateur SYGRI <span class="required">*</span></label>
          <div class="col-md-9">
             <select name="id_sygri" id="id_sygri" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez un indicateur">
              <option></option>
         <option value="0" <?php if (isset($row_edit_data["id_sygri"]) && $row_edit_data['id_sygri']=="0") {echo "SELECTED";} ?>>Non-d&eacute;finie</option>
              <?php if($totalRows_liste_sygri>0){ do { ?>
              <option value="<?php echo $row_liste_sygri['id_indicateur_sygri_fida']; ?>" <?php if (isset($row_edit_data["id_sygri"]) && $row_liste_sygri['id_indicateur_sygri_fida']==$row_edit_data["id_sygri"]) {echo "SELECTED";} ?>><?php echo $row_liste_sygri['intitule_indicateur_sygri_fida']; ?></option>
                <?php  } while ($row_liste_sygri = mysql_fetch_assoc($liste_sygri)); } ?>
            </select>
          </div>
        </div>
      </td>
    </tr>

   
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="cible_projet" class="col-md-5 control-label">Cible DCP <span class="required">*</span></label>
          <div class="col-md-5">
            <input class="form-control required" type="text" name="cible_projet" id="cible_projet" value="<?php if(isset($_GET['id']))  {if($row_edit_data['cible_projet']==0 && isset($unite) &&  $unite=="Oui/Non") echo "Oui"; elseif($row_edit_data['cible_projet']==1 && isset($unite) && $unite=="Oui/Non") echo "Non"; elseif($row_edit_data['cible_projet']==-1) echo "n/a"; else echo $row_edit_data['cible_projet']; } else echo "N/A"; ?>" size="32" />
          </div>
        </div>
      </td>
      <td>
        <div class="form-group">
          <label for="cible_rmp" class="col-md-5 control-label">Cible R&eacute;vue <span class="required">*</span></label>
          <div class="col-md-5">
            <input class="form-control required" type="text" name="cible_rmp" id="cible_rmp" value="<?php echo (isset($row_edit_data['cible_rmp']))?$row_edit_data['cible_rmp']:"N/A"; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>


</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && intval($_GET["id"])>0 && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2) ) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer l\'indicateur ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>