<?php                   
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET['date'])){ $date = $_GET['date']; }

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]);
  $query_liste_agenda = "SELECT * FROM ".$database_connect_prefix."agenda_perso WHERE id_agenda='$id'"; 
               try{
    $liste_agenda = $pdar_connexion->prepare($query_liste_agenda);
    $liste_agenda->execute();
    $row_liste_agenda = $liste_agenda ->fetch();
    $totalRows_liste_agenda = $liste_agenda->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  if($totalRows_liste_agenda>0)
  {
    $a = str_replace("/","-",$row_liste_agenda['debut']); $b = explode(" ",$a); $date_debut = $b[0]; $b[0] = implode("/",array_reverse(explode("-",$b[0])));
      $debut = implode(' ',$b);
      if($row_liste_agenda['all_day']==0)
      {
        $a = str_replace("/","-",$row_liste_agenda['fin']); $b = explode(" ",$a); $b[0] = implode("/",array_reverse(explode("-",$b[0])));
        $fin = implode(' ',$b);
      }
      else $fin = "0000/00/00 00:00:00";
  }
}
if(isset($_GET["type"]) && !empty($_GET["type"]))
{
  $query_liste_responsable = "SELECT * FROM ".$database_connect_prefix."personnel";
                 try{
    $liste_responsable = $pdar_connexion->prepare($query_liste_responsable);
    $liste_responsable->execute();
    $row_liste_responsable = $liste_responsable ->fetchAll();
    $totalRows_liste_responsable = $liste_responsable->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}

?>

<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
        $(".colorpicker").remove();
        $(".bs-colorpicker").colorpicker();
        $(".colorpicker").attr("style","z-index:10060");
        $('.datetimepicker').datetimepicker({format: 'DD/MM/YYYY HH:mm:ss',locale: 'fr'});
        statement('all_day','fin');
});
function statement(a,b)
{
  a = $("#"+a); b = $("#"+b);
  if(a.prop("checked")){ b.prop("readonly", true); /*b.val('');*/ b.removeClass("required"); }
  else{ b.prop("readonly", false); b.addClass("required"); }
}
</script>
<style>

</style>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $_SESSION['clp_id']!=$row_liste_agenda['id_personnel'] && $row_liste_agenda['type']=="public"){ echo "Cr&eacute;&eacute; par : <b>".$row_liste_agenda['id_personnel']."</b>"; } elseif(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier l'evenement"; else echo "Ajouter d'evenement"; ?></h4></div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
  <table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:10px;">
	 <tr>
      <td valign="top" colspan="2">
      <div class="form-group">
          <label for="titre" class="col-md-12 control-label">Titre  <span class="required">*</span></label>
          <div class="col-md-12">
             <input type="text" class="form-control required" name="titre" id="titre" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_agenda['titre']; ?>" />
          </div>
      </div>
      </td>
    </tr>
    <tr>
      <td colspan="2" valign="top">
      <div class="form-group">
          <label for="description" class="col-md-12 control-label">Description </label>
          <div class="col-md-12">
            <textarea name="description" rows="2" class="form-control" id="description"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_agenda['description'];?></textarea>
          </div>
      </div>
      </td>
    </tr>
<?php if(isset($_GET["type"]) && !empty($_GET["type"])){ ?>
    <tr>
      <td valign="top" colspan="2">
      <div class="form-group">
          <label for="expediteur" class="col-md-12 control-label">Auteur  <span class="required">*</span></label>
          <div class="col-md-12">
            <select name="expediteur" id="expediteur" class="form-control required">
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_responsable>0) { foreach($row_liste_responsable as $row_liste_responsable){ ?>
              <option <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_agenda["expediteur"]==$row_liste_responsable['id_personnel']) {echo "SELECTED";} ?> value="<?php echo $row_liste_responsable['id_personnel'];?>"><?php echo $row_liste_responsable['fonction']." (".$row_liste_responsable['nom']." ".$row_liste_responsable['prenom'].")" //echo $row_liste_responsable['titre']." ".$row_liste_responsable['nom']." ".$row_liste_responsable['prenom']; ?></option>
              <?php } } ?>
            </select>
          </div>
      </div>
      </td>
    </tr>
<?php } ?>

    <tr>
      <td valign="top" width="50%">
      <div class="form-group">
          <label for="debut" class="col-md-3 control-label">D&eacute;but <span class="required">*</span></label>
          <div class="col-md-8">
             <input type="text" class="form-control datetimepicker required" name="debut" id="debut" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $debut; elseif(isset($date)) echo $date; else echo date("d/m/Y  H:i:s"); ?>">
          </div>
          <div class="clear h0">&nbsp;</div>
          <label for="all_day" class="col-md-9 control-label">Journ&eacute;e enti&egrave;re? </label>
          <div class="col-md-2">
             <input onchange="statement('all_day','fin');" type="checkbox" name="all_day" id="all_day" value="all_day" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_agenda['all_day']==1) echo 'checked="checked"'; ?>>
          </div>
      </div>
      </td>
      <td valign="top">
      <div class="form-group">
          <label for="fin" class="col-md-3 control-label">Fin <span class="required">*</span></label>
          <div class="col-md-8">
             <input <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_agenda['all_day']==1) echo 'readonly="readonly"'; ?> type="text" class="form-control datetimepicker required" name="fin" id="fin" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $fin; else echo date("d/m/Y H:i:s"); ?>">
          </div>
      </div>
      </td>
    </tr>
<!--    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="couleur" class="col-md-3 control-label">Couleur</label>
          <div class="col-md-8">
            <input data-colorpicker-guid="1" data-color-format="hex" class="form-control bs-colorpicker" type="text" name="couleur" id="couleur" value="<?php echo isset($row_liste_agenda['couleur'])?$row_liste_agenda['couleur']:""; ?>" size="32" />
          </div>
        </div>
      </td>
      <td valign="top">
      <div class="form-group">
          <label for="lien" class="col-md-3 control-label">Lien </label>
          <div class="col-md-8">
             <input type="text" class="form-control url" name="lien" id="lien" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_agenda['lien']; ?>">
          </div>
      </div>
      </td>
    </tr>-->
  </table>
<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_agenda['type']=="public"){ ?>
<div class="form-actions">
  <input name="date_debut" id="date_debut" type="hidden" value="<?php echo (isset($date_debut))?$date_debut:date("Y-m-d"); ?>" size="32" alt="">
<?php if($_SESSION['clp_id']==$row_liste_agenda['id_personnel'] || $_SESSION['clp_id']==$row_liste_agenda['expediteur']){ ?>
<?php if($row_liste_agenda['valider']==0){ ?>
<input name="del" type="submit" onclick="return delete_data('MM_archive','Archiver cet evenement ?','<?php echo ($_GET["id"]);?>');" class="btn btn-success pull-left" value="Archiver" style="margin-right:5px;" />
<input name="MM_archive" id="MM_archive" type="hidden" value="" size="32" alt="">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "Modifier";else echo "Enregistrer";?>" />
<?php } ?>
<?php if($_SESSION['clp_id']==$row_liste_agenda['id_personnel']){ ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cet evenement ?','<?php echo ($_GET["id"]);?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<?php } ?>
</div>

<?php } elseif(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_agenda['type']=="private"){ ?>
<div class="form-actions">
  <input name="date_debut" id="date_debut" type="hidden" value="<?php echo (isset($date_debut))?$date_debut:date("Y-m-d"); ?>" size="32" alt="">
<?php if($_SESSION['clp_id']==$row_liste_agenda['id_personnel']){ ?>
<?php if($row_liste_agenda['valider']==0){ ?>
<input name="del" type="submit" onclick="return delete_data('MM_archive','Archiver cet evenement ?','<?php echo ($_GET["id"]);?>');" class="btn btn-success pull-left" value="Archiver" style="margin-right:5px;" />
<input name="MM_archive" id="MM_archive" type="hidden" value="" size="32" alt="">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "Modifier";else echo "Enregistrer";?>" />
<?php } ?>
<?php if (isset ($_GET["id"]) && !empty($_GET["id"]) && ($_SESSION['clp_id']==$row_liste_agenda['id_personnel'])){ ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cet evenement ?','<?php echo ($_GET["id"]);?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<?php } ?>
</div>

<?php }else{ ?>
<div class="form-actions">
  <input name="date_debut" id="date_debut" type="hidden" value="<?php echo (isset($date_debut))?$date_debut:date("Y-m-d"); ?>" size="32" alt="">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "Modifier";else echo "Enregistrer";?>" />
</div>
<?php } ?>
  <input name="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "MM_update";else echo "MM_insert";?>" type="hidden" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]);else echo "MM_insert";?>" size="32" alt="">
  <input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
</form>

</div> </div>