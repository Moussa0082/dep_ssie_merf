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
////header('Content-Type: text/html; charset=UTF-8');

$interdit_array = array("classeur","LKEY","projet","structure","id_personnel","date_enregistrement","modifier_le","modifier_par","etat");

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_feuille = "SELECT * FROM ".$database_connect_prefix."rapport_fiche_config WHERE id='$id' and projet='".$_SESSION["clp_projet"]."' ";
  $liste_feuille  = mysql_query($query_liste_feuille , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_feuille = mysql_fetch_assoc($liste_feuille);
  $totalRows_liste_feuille  = mysql_num_rows($liste_feuille);
  /*
  echo "classeur=".$row_liste_feuille["classeur"];
  echo "</br>feuille=".$row_liste_feuille["feuille"];
  echo "</br>colonne=".$row_liste_feuille["colonne"];
  exit;*/
}
//WHERE ".$_SESSION["clp_where"]."
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_classeur = "SELECT * FROM ".$database_connect_prefix."classeur ";
$liste_classeur = mysql_query($query_liste_classeur, $pdar_connexion) or die(mysql_error());
$row_liste_classeur = mysql_fetch_assoc($liste_classeur);
$totalRows_liste_classeur = mysql_num_rows($liste_classeur);

$mode_calcul = array("SOMME","MOYENNE","COMPTER");
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form3").validate();
        $(".modal-dialog", window.parent.document).width(750);
        $(".colorpicker").remove();
        $(".bs-colorpicker").colorpicker();
        $(".colorpicker").attr("style","z-index:10060");
        <?php if(isset($_GET["id"]) && !empty($_GET["id"])){ ?>
        //get_content('menu_feuille.php','id=<?php echo $row_liste_feuille["classeur"]."&id_s=".$row_liste_feuille["feuille"]; ?>','feuille','');
        //get_content('menu_feuille_colonne_annee.php','id=<?php echo $row_liste_feuille["classeur"]."&id_s=".$row_liste_feuille["colonne"]; ?>','colonne','');
        //get_content('menu_feuille_colonne_annee.php','id=<?php echo $row_liste_feuille["classeur"]."&id_s=".$row_liste_feuille["colonneV"]; ?>','colonneV','');
        //get_content('menu_feuille_colonne_annee.php','id=<?php echo $row_liste_feuille["classeur"]."&id_s="; ?>','show_colonne','');
        <?php } ?>
        $(".select2-select-00").select2({allowClear:true});
	});
</script>

<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification":"Nouveau"; ?></h4>
</div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="classeur" class="col-md-4 control-label">Nom du rapport <span class="required">*</span></label>
          <div class="col-md-8">
            <input name="intitule" id="intitule" class="form-control required" value="<?php echo (isset($row_liste_feuille["intitule"]))?$row_liste_feuille["intitule"]:""; ?>"  >
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="classeur" class="col-md-4 control-label">Classeur <span class="required">*</span></label>
          <div class="col-md-8">
            <select name="classeur" id="classeur" class="form-control  required" onchange="get_content('menu_feuille_colonne_annee.php','id='+this.value,'colonne',''); get_content('menu_feuille_colonne_annee.php','id='+this.value,'colonneV',''); get_content('menu_feuille_colonne_annee.php','id='+this.value,'show_colonne','');" >
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_classeur>0){ do { ?>
              <option value="<?php echo $row_liste_classeur['id_classeur'];?>" <?php if (isset($row_liste_feuille['classeur']) && $row_liste_classeur['id_classeur']==$row_liste_feuille['classeur']) {echo "SELECTED";} ?> ><?php echo utf8_encode($row_liste_classeur['libelle']);?></option>
                <?php  } while ($row_liste_classeur = mysql_fetch_assoc($liste_classeur)); } ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <!--<tr valign="top">
      <td>
        <div class="form-group">
          <label for="feuille" class="col-md-4 control-label">Feuille <span class="required">*</span></label>
          <div class="col-md-8">
            <select name="feuille" id="feuille" class="form-control required" onchange="get_content('menu_feuille_colonne_annee.php','id='+this.value,'colonne',''); get_content('menu_feuille_colonne_annee.php','id='+this.value,'colonneV','');" >
              <option value="">Selectionnez</option>
            </select>
          </div>
        </div>
      </td>
    </tr>-->
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="colonne" class="col-md-4 control-label">R&eacute;grouper par <span class="required">*</span></label>
          <div class="col-md-8">
            <select name="colonne" id="colonne" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez" >
<?php
if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($row_liste_feuille["colonne"]))
{
  $id=($_GET["id"]); $id_s=$row_liste_feuille["colonne"];
   //echo '<option value="">'.$id.'</option>';

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SELECT * FROM rapport_fiche_config WHERE id=$id";
$liste_cp = mysql_query($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);
if(isset($row_liste_cp["classeur"])) $id=$row_liste_cp["classeur"];

  //select all classeur
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SHOW tables";
$liste_cp = mysql_query($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);

$table_array0=array();
if($totalRows_liste_cp>0) {
do {  if(strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!="" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!=$database_connect_prefix."fiche_config" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche_".$id."_details_")!=""){   $table_array0[]=$row_liste_cp["Tables_in_$database_pdar_connexion"];   }
} while ($row_liste_cp = mysql_fetch_assoc($liste_cp));
$rows = mysql_num_rows($liste_cp);
if($rows > 0) {
mysql_data_seek($liste_cp, 0);
$row_liste_cp = mysql_fetch_assoc($liste_cp);
}}


if(count($table_array0)>0){
foreach($table_array0 as $id){

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$id'";
  $entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);

  if($totalRows_entete>0){ $libelle=explode("|",$row_entete["libelle"]); }
  foreach($libelle as $llib1)
  {
    $lib=explode("=",$llib1);
    $libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";
  }

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_feuille_get = "DESCRIBE ".$database_connect_prefix."$id ";
  $liste_feuille_get  = mysql_query($query_liste_feuille_get , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_feuille_get  = mysql_fetch_assoc($liste_feuille_get);
  $totalRows_liste_feuille_get  = mysql_num_rows($liste_feuille_get);
  if($totalRows_liste_feuille_get>0)
  { ?>

    <?php  do { if(!in_array($row_liste_feuille_get['Field'],$interdit_array)){  ?>
    <option value="<?php echo $row_liste_feuille_get['Field']."/".$id; ?>" <?php $tem=$row_liste_feuille_get['Field']."/".$id; if(isset($id_s) && !empty($id_s)) $id_s1=explode(";",$id_s); else $id_s1=array(); if (in_array($tem,$id_s1)) {echo 'selected="selected"';} ?>><?php
if($row_liste_feuille_get['Field']!="annee")
echo (isset($libelle_array[$row_liste_feuille_get['Field']]) && isset($libelle_array[$id]))?utf8_encode($libelle_array[$row_liste_feuille_get['Field']])." => ".utf8_encode($libelle_array[$id]):$row_liste_feuille_get['Field']." => ".$id;
else
echo (isset($libelle_array[$id]))?utf8_encode("Année")." => ".utf8_encode($libelle_array[$id]):utf8_encode("Année")." => ".$id;
 ?></option>
  <?php }   }while($row_liste_feuille_get  = mysql_fetch_assoc($liste_feuille_get));
  }
}      }

}
 ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <div class="form-group">
          <label for="couleur" class="col-md-4 control-label">Colonne valeur <span class="required">*</span></label>
          <div class="col-md-8">
            <select name="colonneV" id="colonneV" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez" >
<?php
if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($row_liste_feuille["colonneV"]))
{
  $id=($_GET["id"]); $id_s=$row_liste_feuille["colonneV"];
   //echo '<option value="">'.$id.'</option>';

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SELECT * FROM rapport_fiche_config WHERE id=$id";
$liste_cp = mysql_query($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);
if(isset($row_liste_cp["classeur"])) $id=$row_liste_cp["classeur"];

  //select all classeur
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SHOW tables";
$liste_cp = mysql_query($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);

$table_array0=array();
if($totalRows_liste_cp>0) {
do {  if(strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!="" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!=$database_connect_prefix."fiche_config" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche_".$id."_details_")!=""){   $table_array0[]=$row_liste_cp["Tables_in_$database_pdar_connexion"];   }
} while ($row_liste_cp = mysql_fetch_assoc($liste_cp));
$rows = mysql_num_rows($liste_cp);
if($rows > 0) {
mysql_data_seek($liste_cp, 0);
$row_liste_cp = mysql_fetch_assoc($liste_cp);
}}


if(count($table_array0)>0){
foreach($table_array0 as $id){

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$id'";
  $entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);

  if($totalRows_entete>0){ $libelle=explode("|",$row_entete["libelle"]); }
  foreach($libelle as $llib1)
  {
    $lib=explode("=",$llib1);
    $libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";
  }

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_feuille_get = "DESCRIBE ".$database_connect_prefix."$id ";
  $liste_feuille_get  = mysql_query($query_liste_feuille_get , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_feuille_get  = mysql_fetch_assoc($liste_feuille_get);
  $totalRows_liste_feuille_get  = mysql_num_rows($liste_feuille_get);
  if($totalRows_liste_feuille_get>0)
  { ?>

    <?php  do { if(!in_array($row_liste_feuille_get['Field'],$interdit_array)){  ?>
    <option value="<?php echo $row_liste_feuille_get['Field']."/".$id; ?>" <?php $tem=$row_liste_feuille_get['Field']."/".$id; if(isset($id_s) && !empty($id_s)) $id_s1=explode(";",$id_s); else $id_s1=array(); if (in_array($tem,$id_s1)) {echo 'selected="selected"';} ?>><?php
if($row_liste_feuille_get['Field']!="annee")
echo (isset($libelle_array[$row_liste_feuille_get['Field']]) && isset($libelle_array[$id]))?utf8_encode($libelle_array[$row_liste_feuille_get['Field']])." => ".utf8_encode($libelle_array[$id]):$row_liste_feuille_get['Field']." => ".$id;
else
echo (isset($libelle_array[$id]))?utf8_encode("Année")." => ".utf8_encode($libelle_array[$id]):utf8_encode("Année")." => ".$id;
 ?></option>
  <?php }   }while($row_liste_feuille_get  = mysql_fetch_assoc($liste_feuille_get));
  }
}      }

}
 ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <div class="form-group">
          <label for="couleur" class="col-md-4 control-label">Mode de calcul <span class="required">*</span></label>
          <div class="col-md-8">
            <select name="mode" id="mode" class="form-control required" >
              <option value="">Selectionnez</option>
              <option value="somme" <?php if(isset($row_liste_feuille['mode']) && $row_liste_feuille['mode']=="somme") echo 'selected="selected"'; ?>>Somme</option>
              <option value="moyenne" <?php if(isset($row_liste_feuille['mode']) && $row_liste_feuille['mode']=="moyenne") echo 'selected="selected"'; ?>>Moyenne</option>
              <option value="compter" <?php if(isset($row_liste_feuille['mode']) && $row_liste_feuille['mode']=="compter") echo 'selected="selected"'; ?>>Compter</option>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="show_colonne" class="col-md-4 control-label">Autres colonnes &agrave; afficher </label>
          <div class="col-md-8">
            <select multiple="multiple" name="show_colonne[]" id="show_colonne" class="full-width-fix select2-select-00" data-placeholder="S&eacute;lectionnez" >
<?php   echo $row_liste_feuille["show_colonne"];
if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]); if(isset($row_liste_feuille["show_colonne"])) $id_s=$row_liste_feuille["show_colonne"];
   //echo '<option value="">'.$id.'</option>';

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SELECT * FROM rapport_fiche_config WHERE id=$id";
$liste_cp = mysql_query($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);
if(isset($row_liste_cp["classeur"])) $id=$row_liste_cp["classeur"];

  //select all classeur
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SHOW tables";
$liste_cp = mysql_query($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);

$table_array0=array();
if($totalRows_liste_cp>0) {
do {  if(strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!="" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!=$database_connect_prefix."fiche_config" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche_".$id."_details_")!=""){   $table_array0[]=$row_liste_cp["Tables_in_$database_pdar_connexion"];   }
} while ($row_liste_cp = mysql_fetch_assoc($liste_cp));
$rows = mysql_num_rows($liste_cp);
if($rows > 0) {
mysql_data_seek($liste_cp, 0);
$row_liste_cp = mysql_fetch_assoc($liste_cp);
}}


if(count($table_array0)>0){
foreach($table_array0 as $id){

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$id'";
  $entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);

  if($totalRows_entete>0){ $libelle=explode("|",$row_entete["libelle"]); }
  foreach($libelle as $llib1)
  {
    $lib=explode("=",$llib1);
    $libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";
  }

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_feuille_get = "DESCRIBE ".$database_connect_prefix."$id ";
  $liste_feuille_get  = mysql_query($query_liste_feuille_get , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_feuille_get  = mysql_fetch_assoc($liste_feuille_get);
  $totalRows_liste_feuille_get  = mysql_num_rows($liste_feuille_get);
  if($totalRows_liste_feuille_get>0)
  { ?>

    <?php  do { if(!in_array($row_liste_feuille_get['Field'],$interdit_array)){  ?>
    <option value="<?php echo $row_liste_feuille_get['Field']."/".$id; ?>" <?php $tem=$row_liste_feuille_get['Field']."/".$id; if(isset($id_s) && !empty($id_s)) $id_s1=explode(";",$id_s); else $id_s1=array(); if (in_array($tem,$id_s1)) {echo 'selected="selected"';} ?>><?php
if($row_liste_feuille_get['Field']!="annee")
echo (isset($libelle_array[$row_liste_feuille_get['Field']]) && isset($libelle_array[$id]))?utf8_encode($libelle_array[$row_liste_feuille_get['Field']])." => ".utf8_encode($libelle_array[$id]):$row_liste_feuille_get['Field']." => ".$id;
else
echo (isset($libelle_array[$id]))?utf8_encode("Année")." => ".utf8_encode($libelle_array[$id]):utf8_encode("Année")." => ".$id;
 ?></option>
  <?php }   }while($row_liste_feuille_get  = mysql_fetch_assoc($liste_feuille_get));
  }
}      }

}
 ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <div class="form-group">
          <label for="critere_in" class="col-md-4 control-label">Crit&egrave;re &agrave; inclure </label>
          <div class="col-md-8">
            <input type="text" placeholder="Separateur ;" name="critere_in" id="critere_in" class="form-control" value="<?php echo (isset($row_liste_feuille["critere_in"]))?$row_liste_feuille["critere_in"]:""; ?>" >
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <div class="form-group">
          <label for="critere_not_in" class="col-md-4 control-label">Crit&egrave;re &agrave; exclure </label>
          <div class="col-md-8">
            <input type="text" placeholder="Separateur ;" name="critere_not_in" id="critere_not_in" class="form-control" value="<?php echo (isset($row_liste_feuille["critere_not_in"]))?$row_liste_feuille["critere_not_in"]:""; ?>" >
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo ($_GET["id"]); ?>" />
<?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
  <?php if(isset($id) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2)) { ?>
  <input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
  <input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
  <?php } ?>
  <input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php }  ?>