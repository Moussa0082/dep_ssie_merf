<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
$path = '../';
include_once $path.'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $path.$config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y");
if(isset($_GET['id_cp'])) {$id_cp=$_GET['id_cp'];} else $id_cp=0;
//if(isset($_GET['code_scp'])) {$code_scp=$_GET['code_scp'];}
if(isset($_GET["id"])) { $id=$_GET["id"];}
//echo $id_cp;
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

if(isset($_GET["id"]))
{
  $id=$_GET["id"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_edit_act = "SELECT * FROM $id_cp where LKEY='$id'";
  $edit_act = mysql_query($query_edit_act, $pdar_connexion) or die(mysql_error());
  $row_edit_act = mysql_fetch_assoc($edit_act);
  $totalRows_edit_act = mysql_num_rows($edit_act);
}
else $id=-1;

if(isset($id_cp) && !empty($id_cp)){
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_table = "DESCRIBE $id_cp ";
$liste_table = mysql_query($query_liste_table, $pdar_connexion) or die(mysql_error());
$row_liste_table = mysql_fetch_assoc($liste_table);
$totalRows_liste_table = mysql_num_rows($liste_table);

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "SELECT * FROM fiche_config WHERE `table`='$id_cp'";
  $entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);
  $entete_array = array(); $choix_array = array(); $libelle = array();
  if($totalRows_entete>0){ $entete_array=explode(",",$row_entete["show"]); $libelle=explode(",",$row_entete["libelle"]);
  if(!empty($row_entete["choix"])){ foreach(explode(",",$row_entete["choix"]) as $elem){ if(!empty($elem)){  $a=explode(";",$elem); $choix_array[$a[0]]=""; for($i=1;$i<count($a);$i++){ $choix_array[$a[0]].=(!empty($a[$i]))?$a[$i].";":""; } }   }  } }

}

?>

<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<style>
.firstcapitalize:first-letter{
  text-transform: capitalize;
}
</style>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
	});
</script>

<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification":"Nouveau"; echo "&nbsp;".str_replace("_"," ",substr($id_cp,6)); ?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

<?php $libelle_array=array(); $i=0; foreach ($libelle as $k=>$v){
$a=explode("=",$v); $libelle_array[$i]=(isset($a[1]))?$a[1]:"ND"; $i++;
} if($totalRows_liste_table>0){ $i=0; do{


if($row_liste_table["Field"]!="LKEY" && $row_liste_table["Field"]!="annee" && $row_liste_table["Field"]!="fiche"){

if(!isset($choix_array[$row_liste_table["Field"]])){
  if(strtolower($row_liste_table["Field"])=="village"){ $chemin="../"; include($chemin."village_form.php");  }else{
 ?>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="<?php echo $row_liste_table["Field"]; ?>" class="col-md-3 control-label firstcapitalize"><?php
echo $libelle_array[$i];
          //echo (isset($libelle_array[$row_liste_table["Field"]]))?$libelle_array[$row_liste_table["Field"]]:str_replace("_"," ",$row_liste_table["Field"]);
if($row_liste_table["Null"]=="NO"){ ?> <span class="required">*</span><?php } ?></label>
          <div class="col-md-9">
          <input name="field_name[]" id="field_name[]" type="hidden" value="<?php echo $row_liste_table["Field"]; ?>" size="32" alt="">    <?php if($row_liste_table["Type"]=="text"){ ?>
            <textarea class="form-control <?php if($row_liste_table["Null"]=="NO") echo "required"; ?>" name="<?php echo $row_liste_table["Field"]; ?>" cols="25" rows="1"><?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_edit_act[$row_liste_table["Field"]];  ?></textarea>
<?php }else{ ?>
           <input <?php if(strtolower($row_liste_table["Type"])=="date") echo 'placeholder="00-00-0000"'; ?> name="<?php echo $row_liste_table["Field"]; ?>" type="text" value="<?php echo (strtolower($row_liste_table["Type"])=="date")?implode('-',array_reverse(explode('-',$row_edit_act[$row_liste_table["Field"]]))):$row_edit_act[$row_liste_table["Field"]]; ?>" size="32" alt=""><?php } ?>
          </div>
        </div>
      </td>

    </tr>
<?php }  }else{  ?>

<tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="<?php echo $row_liste_table["Field"]; ?>" class="col-md-3 control-label firstcapitalize"><?php
          echo $libelle_array[$i];
          //echo (isset($libelle_array[$row_liste_table["Field"]]))?$libelle_array[$row_liste_table["Field"]]:str_replace("_"," ",$row_liste_table["Field"]); ?>&nbsp;<span class="required">*</span></label>
          <div class="col-md-9">
            <select name="<?php echo $row_liste_table["Field"]; ?>" class="form-control required"  >
              <option value="">Choisissez</option>
                            <?php $chx=explode(";",$choix_array[$row_liste_table["Field"]]);
foreach($chx as $elem){ if(!empty($elem)){ $libelle = (strlen($elem)>70)?substr($elem,0,70)."...":$elem; ?>
                            <option value="<?php echo $elem; ?>"<?php if(isset($row_edit_act[$row_liste_table["Field"]])) {if (!(strcmp($elem, $row_edit_act[$row_liste_table["Field"]]))) {echo "SELECTED";} } ?>><?php echo $libelle; ?></option>
                            <?php } } ?>
            </select> <input name="field_name[]" id="field_name[]" type="hidden" value="<?php echo $row_liste_table["Field"]; ?>" size="32" alt="">
          </div>
        </div>
      </td>
    </tr>

<?php }
$i++; }  }while($row_liste_table = mysql_fetch_assoc($liste_table));  } ?>
</table>
<div class="form-actions">
<?php if(isset($_GET["id_fiche"])){  ?>
<input name="field_name[]" id="field_name[]" type="hidden" value="fiche" size="32" alt="">
<input name="fiche" type="hidden" value="<?php echo $_GET["id_fiche"]; ?>" size="32" alt="">
<?php }  ?>
<input name="id_cp" id="id_cp" type="hidden" value="<?php echo $_GET["id_cp"]; ?>" size="32" alt="">
<?php if(!isset($_GET["id_fiche"]) && isset($_GET["annee"])){  ?>
<input name="field_name[]" id="field_name[]" type="hidden" value="annee" size="32" alt="">
<input name="annee" id="annee" type="hidden" value="<?php echo intval($_GET["annee"]); ?>" size="32" alt="">
<?php }  ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette donn&eacute;e ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>