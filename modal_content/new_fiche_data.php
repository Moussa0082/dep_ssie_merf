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

$interdit_array = array("classeur","LKEY","annee","projet","structure","id_personnel","date_enregistrement","modifier_le","modifier_par","etat");

if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else {$annee=date("Y");}
if(isset($_GET['feuille'])) {$feuille=$_GET['feuille'];}
if(isset($_GET['classeur'])) {$classeur=$_GET['classeur'];}
//if(isset($_GET['code_scp'])) {$code_scp=$_GET['code_scp'];}
if(isset($_GET["id"])) { $id=$_GET["id"];}
//echo $id_cp;
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SHOW tables";
$liste_cp = mysql_query($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);

$table_array=array();
if($totalRows_liste_cp>0) {
do {  if(strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!="" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!=$database_connect_prefix."fiche_config" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"details")!=""){   $table_array[]=$row_liste_cp["Tables_in_$database_pdar_connexion"];   }
} while ($row_liste_cp = mysql_fetch_assoc($liste_cp));
$rows = mysql_num_rows($liste_cp);
if($rows > 0) {
mysql_data_seek($liste_cp, 0);
$row_liste_cp = mysql_fetch_assoc($liste_cp);
}}

if(isset($_GET["id"]))
{
  $id=$_GET["id"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_edit_act = "SELECT * FROM ".$database_connect_prefix."$feuille where LKEY='$id'";
  $edit_act = mysql_query_ruche($query_edit_act, $pdar_connexion) or die(mysql_error());
  $row_edit_act = mysql_fetch_assoc($edit_act);
  $totalRows_edit_act = mysql_num_rows($edit_act);
}
else $id=-1;

if(isset($feuille) && !empty($feuille)){
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_table = "DESCRIBE ".$database_connect_prefix."$feuille ";
$liste_table = mysql_query_ruche($query_liste_table, $pdar_connexion) or die(mysql_error());
$row_liste_table = mysql_fetch_assoc($liste_table);
$totalRows_liste_table = mysql_num_rows($liste_table);

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$feuille'";
  $entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error());
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);
  $entete_array = array(); $choix_array = $table_feuille = $table_feuille_col = array(); $libelle = array();
  if($totalRows_entete>0){ $nomT=$row_entete["nom"]; $note=$row_entete["note"]; $entete_array=explode("|",$row_entete["show"]); $libelle=explode("|",$row_entete["libelle"]);
  if(!empty($row_entete["choix"])){ foreach(explode("|",$row_entete["choix"]) as $elem){ if(!empty($elem)){  $a=explode(";",$elem); $choix_array[$a[0]]=""; if(isset($a[count($a)-1]) && in_array($a[count($a)-1],$table_array)){ $table_feuille[$a[0]]=$a[count($a)-1]; $table_feuille_col[$a[0]]=$a[1]; } for($i=1;$i<count($a);$i++){ $choix_array[$a[0]].=(!empty($a[$i]))?$a[$i].";":""; } }   }  } }

}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_pdes = "SELECT * FROM ".$database_connect_prefix."pde ORDER BY code_pde";
$liste_pdes = mysql_query_ruche($query_liste_pdes, $pdar_connexion) or die(mysql_error());
$row_liste_pdes = mysql_fetch_assoc($liste_pdes);
$totalRows_liste_pdes = mysql_num_rows($liste_pdes);
$PDE=array();
if($totalRows_liste_pdes>0){
  do{ $PDE[$row_liste_pdes["id_pde"]]=$row_liste_pdes["nom_pde"]; }while($row_liste_pdes = mysql_fetch_assoc($liste_pdes));
}

?>

<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<style>
.firstcapitalize:first-letter{
  text-transform: capitalize;
}
</style>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form2").validate();
        $(".modal-dialog", window.parent.document).width(600);
        $(".colorpicker").remove();
        $(".bs-colorpicker").colorpicker();
        $(".colorpicker").attr("style","z-index:10060");
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
	});
</script>

<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification":"Nouveau"; echo "&nbsp;".$nomT; ?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

<?php $libelle_array=array(); $i=0; foreach ($libelle as $k=>$v){
$a=explode("=",$v); $libelle_array[$i]=(isset($a[1]))?$a[1]:"ND"; $i++;
} if($totalRows_liste_table>0){ $i=0; do{


if($row_liste_table["Field"]!="LKEY" && !in_array($row_liste_table["Field"],$interdit_array)){

//if(!isset($choix_array[$row_liste_table["Field"]])){
  if(strtolower($row_liste_table["Field"])=="village"){ $chemin="../"; include($chemin."village_form.php");  }
  elseif($row_liste_table["Type"]=="varchar(1009)" && isset($table_feuille[$row_liste_table["Field"]]) && in_array($table_feuille[$row_liste_table["Field"]],$table_array)) { $choix_feuille=0;  ?>
<tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="<?php echo $row_liste_table["Field"]; ?>" class="col-md-3 control-label firstcapitalize"><?php echo $libelle_array[$i]; ?>&nbsp;<?php if($row_liste_table["Null"]=="NO"){ ?><span class="required">*</span><?php } ?></label>
          <div class="col-md-9">
            <select name="<?php echo $row_liste_table["Field"]; ?>" id="<?php echo $row_liste_table["Field"]; ?>" class="full-width-fix select2-select-00 <?php if($row_liste_table["Null"]=="NO") echo "required"; ?>"  >
              <option value="">Choisissez</option>
                            <?php
$idFeuille=$table_feuille[$row_liste_table["Field"]];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_activite = "DESCRIBE ".$database_connect_prefix."$idFeuille";
  $liste_activite  = mysql_query($query_liste_activite , $pdar_connexion) or die(mysql_error());
  $row_liste_activite  = mysql_fetch_assoc($liste_activite);
  $totalRows_liste_activite  = mysql_num_rows($liste_activite);
  $les_colonnes_feuille = array();
  do{
  $les_colonnes_feuille[]=$row_liste_activite["Field"];
  }while($row_liste_activite  = mysql_fetch_assoc($liste_activite));

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = "SELECT * FROM $idFeuille WHERE 1=1 and ".$_SESSION["clp_where"];
$act  = mysql_query_ruche($query_act , $pdar_connexion) or die(mysql_error());
$row_act  = mysql_fetch_assoc($act);
$totalRows_act  = mysql_num_rows($act);
if($totalRows_act>0)
{
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_validation = "SELECT * FROM ".$database_connect_prefix."validation_fiche WHERE projet='".$_SESSION["clp_projet"]."' and nom_fiche='$idFeuille'";
  $validation  = mysql_query_ruche($query_validation , $pdar_connexion) or die(mysql_error());
  $row_validation  = mysql_fetch_assoc($validation);
  $totalRows_validation  = mysql_num_rows($validation);
  $data_validate_array = array();
  if($totalRows_validation>0){ do{ $data_validate_array[] = $row_validation["id_lkey"]; }while($row_validation  = mysql_fetch_assoc($validation));  }

do{  if(isset($row_act[$table_feuille_col[$row_liste_table["Field"]]])){ $elem=$row_act[$table_feuille_col[$row_liste_table["Field"]]];   ?>

<option value="<?php echo $elem; ?>"<?php if(isset($row_edit_act[$row_liste_table["Field"]])) {if (!(strcmp($elem, $row_edit_act[$row_liste_table["Field"]]))) {echo "SELECTED";} } ?>><?php echo $elem; ?></option>

<?php } }while($row_act  = mysql_fetch_assoc($act));
}
                              ?>
            </select> <input name="field_name[]" id="field_name[]" type="hidden" value="<?php echo $row_liste_table["Field"]; ?>" size="32" alt="" >
          </div>
        </div>
      </td>
    </tr>
<?php }
  elseif($row_liste_table["Type"]!="varchar(1000)"){
 ?>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="<?php echo $row_liste_table["Field"]; ?>" class="col-md-3 control-label firstcapitalize"><?php echo $libelle_array[$i]; if($row_liste_table["Null"]=="NO"){ ?> <span class="required">*</span><?php } ?></label>
          <div class="col-md-9">
          <input name="field_name[]" id="field_name[]" type="hidden" value="<?php echo $row_liste_table["Field"]; ?>" size="32" alt="" class="form-control <?php if($row_liste_table["Null"]=="NO") echo "required"; ?>">
<?php if($row_liste_table["Field"]=="pde"){ ?>

<select name="<?php echo $row_liste_table["Field"]; ?>" id="<?php echo $row_liste_table["Field"]; ?>" class="form-control <?php if($row_liste_table["Null"]=="NO") echo "required"; ?>"  >
              <option value="">Choisissez</option>
                            <?php $chx=explode(";",$choix_array[$row_liste_table["Field"]]);
foreach($PDE as $idpde=>$elem){ if(!empty($elem)){ $libelle = (strlen($elem)>70)?substr($elem,0,70)."...":$elem; ?>
                            <option value="<?php echo $idpde; ?>"<?php if(isset($row_edit_act[$row_liste_table["Field"]])) {if (!(strcmp($idpde, $row_edit_act[$row_liste_table["Field"]]))) {echo "SELECTED";} } ?>><?php echo $libelle; ?></option>
                            <?php } } ?>
            </select>

<?php }
elseif($row_liste_table["Type"]=="text"){ ?>
            <textarea class="form-control <?php if($row_liste_table["Null"]=="NO") echo "required"; ?>" name="<?php echo $row_liste_table["Field"]; ?>" id="<?php echo $row_liste_table["Field"]; ?>" cols="25" rows="1"><?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_edit_act[$row_liste_table["Field"]];  ?></textarea>
<?php }elseif(strtolower($row_liste_table["Type"])=="varchar(1007)"){ ?>
           <input class="form-control <?php if($row_liste_table["Null"]=="NO") echo "required"; ?>" name="<?php echo $row_liste_table["Field"]; ?>[]" id="<?php echo $row_liste_table["Field"]; ?>" type="file" value="<?php //echo (isset($row_edit_act[$row_liste_table["Field"]]) && !empty($row_edit_act[$row_liste_table["Field"]]))?$row_edit_act[$row_liste_table["Field"]]:""; ?>" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/pdf,application/vnd.ms-word,image/jpeg,.doc,.docx,.zip,.rar,.shp" multiple size="32" alt=""><?php }else{ ?>
           <input class="form-control <?php if($row_liste_table["Null"]=="NO") echo "required"; if(strtolower($row_liste_table["Type"])=="date") echo ' datepicker'; if(strtolower($row_liste_table["Type"])=="varchar(1008)") echo ' bs-colorpicker" data-colorpicker-guid="1" data-color-format="hex'; ?>" <?php if(strtolower($row_liste_table["Type"])=="date") echo 'placeholder="jj/mm/aaaa"'; if(strtolower($row_liste_table["Type"])=="varchar(1008)") echo 'placeholder="#CCCCCC"'; ?> name="<?php echo $row_liste_table["Field"]; ?>" id="<?php echo $row_liste_table["Field"]; ?>" type="text" value="<?php echo (strtolower($row_liste_table["Type"])=="date")?((isset($row_edit_act[$row_liste_table["Field"]]) && !empty($row_edit_act[$row_liste_table["Field"]]))?implode('/',array_reverse(explode('-',$row_edit_act[$row_liste_table["Field"]]))):date("d/m/Y")):((isset($row_edit_act[$row_liste_table["Field"]]))?$row_edit_act[$row_liste_table["Field"]]:""); ?>" size="32" alt=""><?php } ?>
          </div>
        </div>
      </td>

    </tr>
<?php } else {  ?>
<tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="<?php echo $row_liste_table["Field"]; ?>" class="col-md-3 control-label firstcapitalize"><?php echo $libelle_array[$i]; ?>&nbsp;<?php if($row_liste_table["Null"]=="NO"){ ?><span class="required">*</span><?php } ?></label>
          <div class="col-md-9">
            <select name="<?php echo $row_liste_table["Field"]; ?>" id="<?php echo $row_liste_table["Field"]; ?>" class="form-control <?php if($row_liste_table["Null"]=="NO") echo "required"; ?>"  >
              <option value="">Choisissez</option>
                            <?php $chx=explode(";",$choix_array[$row_liste_table["Field"]]);
foreach($chx as $elem){ if(!empty($elem)){ $libelle = (strlen($elem)>70)?substr($elem,0,70)."...":$elem; ?>
                            <option value="<?php echo $elem; ?>"<?php if(isset($row_edit_act[$row_liste_table["Field"]])) {if (!(strcmp($elem, $row_edit_act[$row_liste_table["Field"]]))) {echo "SELECTED";} } ?>><?php echo $libelle; ?></option>
                            <?php } } ?>
            </select> <input name="field_name[]" id="field_name[]" type="hidden" value="<?php echo $row_liste_table["Field"]; ?>" size="32" alt="" >
          </div>
        </div>
      </td>
    </tr>
<?php }
$i++; }  }while($row_liste_table = mysql_fetch_assoc($liste_table));  } ?>
</table>
<div class="form-actions">
<?php if(isset($_GET["classeur"])){  ?>
<input name="field_name[]" id="field_name[]" type="hidden" value="classeur" size="32" alt="">
<input name="classeur" type="hidden" value="<?php echo $classeur; ?>" size="32" alt="">
<?php }  ?>
<input name="feuille" id="feuille" type="hidden" value="<?php echo $feuille; ?>" size="32" alt="">
<?php if(!isset($_GET["id_fiche"]) && isset($_GET["annee"])){  ?>
<input name="field_name[]" id="field_name[]" type="hidden" value="annee" size="32" alt="">
<input name="annee" id="annee" type="hidden" value="<?php echo intval($_GET["annee"]); ?>" size="32" alt="">
<?php }  ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && ($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && ($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && ($_GET["id"])>0) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && ($_GET["id"])>0) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette donn&eacute;e ?',<?php echo ($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php if(isset($choix_feuille)){   ?>
<script>$(".select2-select-00").select2({allowClear:true});</script><?php } ?>