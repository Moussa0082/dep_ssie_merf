<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*  Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

include_once $config->sys_folder . "/database/db_connexion.php";

if(isset($_GET["codeind"]) && !empty($_GET["codeind"]))
{
  $codeind=($_GET["codeind"]);
  $query_liste_meta_donnees = "SELECT * FROM ".$database_connect_prefix."meta_donnees WHERE ref_indicateur = ".GetSQLValueString($codeind, "text");
  try{
        $liste_meta_donnees = $pdar_connexion->prepare($query_liste_meta_donnees);
        $liste_meta_donnees->execute();
        $row_liste_meta_donnees = $liste_meta_donnees ->fetch();
        $totalRows_liste_meta_donnees = $liste_meta_donnees->rowCount();
   }catch(Exception $e){ die(mysql_error_show_message($e)); }
}
?>    
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form4").validate();
        $(".wysiwyg").each(function(){$(this).wysihtml5({parser: function(html) {return html;}});});
        //$(".wysihtml5-toolbar").each(function(){$(this).addClass('hidden');});
        //$(".wysihtml5-toolbar-edit").each(function(){$(this).attr('style','cursor:pointer;');$(this).click(function(){$(".wysihtml5-toolbar").toggleClass('hidden');});});
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
	});
</script>
<style>
.ui-datepicker-append {display: none;}
textarea#message, textarea#observation { height: 200px; }
</style>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($row_liste_meta_donnees['id_meta_donnees']) && !empty($row_liste_meta_donnees['id_meta_donnees']))?"Modification de ":"Nouveau "; ?> m&eacute;ta-donn&eacute;es</h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form4" id="form4" novalidate="novalidate" onsubmit="<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) echo "return true;"; else echo "return false;"; ?>">
<table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
      <tr>
      <td valign="top" width="50%">
        <div class="form-group">
          <label for="source_donnees" class="col-md-12 control-label">Source/ Auteur  <span class="required">*</span></label>
          <div class="col-md-12">
            <input name="source_donnees" type="text" class="form-control required" id="source_donnees" value="<?php echo isset($row_liste_meta_donnees['source_donnees'])?$row_liste_meta_donnees['source_donnees']:""; ?>" size="10" />
          </div>
        </div>
      </td>
      <td valign="top" width="50%">
      <div class="form-group">
          <label for="date_validation" class="col-md-12 control-label">Date d'&eacute;dition <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control datepicker required" type="text" name="date_validation" id="date_validation" value="<?php if(isset($row_liste_meta_donnees['date_validation']) && !empty($row_liste_meta_donnees['date_validation'])) echo implode('/',array_reverse(explode('-',$row_liste_meta_donnees['date_validation']))); else echo date("d/m/Y"); ?>" size="32" />
          </div>
        </div></td>
      </tr>
     <tr>
      <td colspan="2" valign="top">
       <div class="form-group">
          <label for="observation" class="col-md-10 control-label">M&eacute;ta-donn&eacute;es</label>
          <div class="col-md-12">
            <textarea name="observation" cols="40" rows="1" class="form-control required wysiwyg" id="observation"><?php echo (isset($row_liste_meta_donnees['observation']) && !empty($row_liste_meta_donnees['observation']))?$row_liste_meta_donnees['observation']:''; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
</table>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2){ ?>"
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($row_liste_meta_donnees['id_meta_donnees']) && !empty($row_liste_meta_donnees['id_meta_donnees'])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($row_liste_meta_donnees['id_meta_donnees']) && !empty($row_liste_meta_donnees['id_meta_donnees'])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($row_liste_meta_donnees['id_meta_donnees']) && !empty($row_liste_meta_donnees['id_meta_donnees'])) echo $row_liste_meta_donnees['id_meta_donnees']; else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["codeind"])){ ?>
  <input type="hidden" name="ref_indicateur" value="<?php echo ($_GET["codeind"]); ?>" />
<?php } ?>
<?php if(isset($row_liste_meta_donnees['id_meta_donnees']) && !empty($row_liste_meta_donnees['id_meta_donnees'])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce m&eacute;ta-donn&eacute;es ?',<?php echo $row_liste_meta_donnees['id_meta_donnees']; ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form4" size="32" alt="">
</div>
<?php } ?>
</form>
</div> </div>
