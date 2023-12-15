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
 // mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_unite = "SELECT * FROM ".$database_connect_prefix."maillon WHERE id_maillon=$id ";
  try{
    $liste_unite = $pdar_connexion->prepare($query_liste_unite);
    $liste_unite->execute();
    $row_liste_unite = $liste_unite ->fetch();
    $totalRows_liste_unite = $liste_unite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  
  $query_listespm = "SELECT * FROM ".$database_connect_prefix."speculation_maillon WHERE maillon=$id ";
	
	          try{
    $listespm = $pdar_connexion->prepare($query_listespm);
    $listespm->execute();
    $row_listespm = $listespm ->fetchAll();
    $totalRows_listespm = $listespm->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

 $tableausp=array();
  if($totalRows_listespm) {
  foreach($row_listespm as $row_listespm){
	$tableausp[]=$row_listespm['speculation'];}
	}

}
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification maillon":"Nouveau maillon"?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form5" id="form5" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="libelle" class="col-md-3 control-label">Maillon <span class="required">*</span></label>
          <div class="col-md-9">
         <input class="form-control required" type="text" name="libelle" id="libelle" value="<?php if(isset($row_liste_unite['libelle'])) echo $row_liste_unite['libelle']; ?>" size="32" />
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">  
      <td><table align="center" width="100%">
        <tr bgcolor="#FFFFCC">
          <td align="center" width="150">Sp&eacute;culation</td>
          <!--<td align="center">&nbsp;</td>-->
        </tr>
        <?php
$query_liste_spec = "SELECT * FROM speculation  order by filiere";
	          try{
    $liste_spec = $pdar_connexion->prepare($query_liste_spec);
    $liste_spec->execute();
    $row_liste_spec = $liste_spec ->fetchAll();
    $totalRows_liste_spec = $liste_spec->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if($totalRows_liste_spec>0){  foreach($row_liste_spec as $row_liste_spec){ $p=$row_liste_spec["id_speculation"];
 ?>
        <tr>
          <td align="left"><label for="<?php echo $row_liste_spec["libelle"]; ?>" class="control-label"><?php echo $row_liste_spec["libelle"]; ?></label>

          <input name='sepc[]' id='<?php echo $row_liste_spec["libelle"]; ?>' type="checkbox"   <?php if(isset($row_liste_unite['libelle'])) { if(in_array($p, $tableausp, TRUE)) echo "checked"; }?> size="5" value="<?php echo $row_liste_spec["id_speculation"]; ?>" class=""/></td>
          <td align="center"></td>
        </tr>
        <?php }  } ?>
      </table></td>
    </tr>
   
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ce maillon ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form5" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>