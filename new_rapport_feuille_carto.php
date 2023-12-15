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
//header('Content-Type: text/html; charset=UTF-8');

$interdit_array = array("classeur","LKEY","projet","structure","id_personnel","date_enregistrement","modifier_le","modifier_par","etat");

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]);
  $query_liste_feuille = "SELECT * FROM ".$database_connect_prefix."requete_carte_config WHERE id='$id' /*and projet='".$_SESSION["clp_projet"]."'*/ ";
try{
    $liste_feuille = $pdar_connexion->prepare($query_liste_feuille);
    $liste_feuille->execute();
    $row_liste_feuille = $liste_feuille ->fetch();
    $totalRows_liste_feuille = $liste_feuille->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  /*
  echo "classeur=".$row_liste_feuille["classeur"];
  echo "</br>feuille=".$row_liste_feuille["feuille"];
  echo "</br>colonne=".$row_liste_feuille["colonne"];
  exit;*/
}
//WHERE ".$_SESSION["clp_where"]."
$query_liste_classeur = "SELECT * FROM ".$database_connect_prefix."t_classeur where Id_Projet='".$_SESSION["clp_projet"]."'";
try{
    $liste_classeur = $pdar_connexion->prepare($query_liste_classeur);
    $liste_classeur->execute();
    $row_liste_classeur = $liste_classeur ->fetchAll();
    $totalRows_liste_classeur = $liste_classeur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$mode_calcul = array("SOMME","MOYENNE","COMPTER");
?>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
        $(".modal-dialog", window.parent.document).width(750);
        $(".colorpicker").remove();
        $(".bs-colorpicker").colorpicker();
        $(".colorpicker").attr("style","z-index:10060");
        $(".select2-select-00").select2({allowClear:true});
	});
</script>

<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i><?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification":"Nouveau"; ?></h4>
</div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="classeur" class="col-md-4 control-label">Nom de la requ&ecirc;te <span class="required">*</span></label>
          <div class="col-md-8">
            <input name="intitule" id="intitule" class="form-control required" value="<?php echo (isset($row_liste_feuille["intitule"]))?$row_liste_feuille["intitule"]:""; ?>" />
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="classeur" class="col-md-4 control-label">Classeur <span class="required">*</span></label>
          <div class="col-md-8">
            <select name="classeur" id="classeur" class="full-width-fix select2-select-00 required" onchange="get_content('sous_menu_feuille_colonne_classeur.php','id='+this.value,'colonne',''); get_content('sous_menu_feuille_colonne_classeur.php','id='+this.value,'colonneV',''); get_content('sous_menu_feuille_colonne_classeur.php','id='+this.value,'show_colonne','');" data-placeholder="S&eacute;lectionnez" >
              <option></option>
              <?php if($totalRows_liste_classeur>0){ foreach($row_liste_classeur as $row_liste_classeur){   ?>
              <option value="<?php echo $row_liste_classeur['Code_Classeur'];?>" <?php if (isset($row_liste_feuille['classeur']) && $row_liste_classeur['Code_Classeur']==$row_liste_feuille['classeur']) {echo "SELECTED";} ?> ><?php echo $row_liste_classeur['Libelle_Classeur'];?></option>
                <?php  } } ?>
            </select>
          </div>
        </div>      </td>
    </tr>
    <!--<tr valign="top">
      <td>
        <div class="form-group">
          <label for="feuille" class="col-md-4 control-label">Feuille <span class="required">*</span></label>
          <div class="col-md-8">
            <select name="feuille" id="feuille" class="form-control required" onchange="get_content('sous_menu_feuille_colonne_classeur.php','id='+this.value,'colonne',''); get_content('sous_menu_feuille_colonne_classeur.php','id='+this.value,'colonneV','');" >
              <option value="">Selectionnez</option>
            </select>
          </div>
        </div>
      </td>
    </tr>-->
  
    <tr>
      <td colspan="2">
        <div class="form-group">
          <label for="couleur" class="col-md-12 control-label">Colonne coordonnées <span class="required">*</span></label>
          <div class="col-md-12">
            <select multiple="multiple" name="colonneV[]" id="colonneV" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez" > <option></option>
<?php
if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($row_liste_feuille["colonneV"]))
{
  $id=($_GET["id"]); $id_s=$row_liste_feuille["colonneV"];
   //echo '<option value="">'.$id.'</option>';

$query_liste_cp = "SELECT * FROM requete_carte_config WHERE id=$id";
  	   try{
    $liste_cp = $pdar_connexion->prepare($query_liste_cp);
    $liste_cp->execute();
    $row_liste_cp = $liste_cp ->fetch();
    $totalRows_liste_cp = $liste_cp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if(isset($row_liste_cp["classeur"])) $id=$row_liste_cp["classeur"];

  //select all classeur
$query_liste_cp = "SHOW tables";
  	   try{
    $liste_cp = $pdar_connexion->prepare($query_liste_cp);
    $liste_cp->execute();
    $row_liste_cp = $liste_cp ->fetchAll();
    $totalRows_liste_cp = $liste_cp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$table_array0=array();
if($totalRows_liste_cp>0) {
foreach($row_liste_cp as $row_liste_cp1){    if(strchr($row_liste_cp1["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!="" && strchr($row_liste_cp1["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!=$database_connect_prefix."fiche_config" && strchr($row_liste_cp1["Tables_in_$database_pdar_connexion"],"fiche_".$id."_details_")!=""){   $table_array0[]=$row_liste_cp1["Tables_in_$database_pdar_connexion"];   }
} }


if(count($table_array0)>0){
foreach($table_array0 as $id){

  $query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$id'";
	   try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $nomcol = "";
  if($totalRows_entete>0){ $libelle=explode("|",$row_entete["Libelle_Classeur"]); $nomcol=$row_entete["nom"]; }
  foreach($libelle as $llib1)
  {
    $lib=explode("=",$llib1);
    $libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";
  }

  $query_liste_feuille_get = "DESCRIBE ".$database_connect_prefix."$id "; 
  	   try{
    $liste_feuille_get = $pdar_connexion->prepare($query_liste_feuille_get);
    $liste_feuille_get->execute();
    $row_liste_feuille_get = $liste_feuille_get ->fetchAll();
    $totalRows_liste_feuille_get = $liste_feuille_get->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  if($totalRows_liste_feuille_get>0)
  { ?>

    <?php echo "<optgroup label='".$nomcol."'>"; foreach($row_liste_feuille_get as $row_liste_feuille_get){   if(!in_array($row_liste_feuille_get['Field'],$interdit_array)){  ?>
    <option value="<?php echo $row_liste_feuille_get['Field']."/".$id; ?>" <?php $tem=$row_liste_feuille_get['Field']."/".$id; if(isset($id_s) && !empty($id_s)) $id_s1=explode(";",$id_s); else $id_s1=array(); if (in_array($tem,$id_s1)) {echo 'selected="selected"';} ?>><?php
if($row_liste_feuille_get['Field']!="annee")
//if($row_liste_feuille_get['Field']!="annee")
echo $row_liste_feuille_get['Field']." => <small>".((isset($libelle_array[$row_liste_feuille_get['Field']])/* && isset($libelle_array[$id])*/)?($libelle_array[$row_liste_feuille_get['Field']])/*." => ".utf8_encode($libelle_array[$id])*/:$row_liste_feuille_get['Field'])."</small>"/*." => ".$nomcol*/;
/*else
echo (isset($libelle_array[$id]))?utf8_encode("Année")." => ".utf8_encode($libelle_array[$id]):utf8_encode("Année")." => ".$id;*/
 ?></option>
  <?php }   }
  }
}      }

}
 ?>
            </select>
          </div>
        </div>      </td>
    </tr>

    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="show_colonne" class="col-md-12 control-label">Autres colonnes &agrave; afficher </label>
          <div class="col-md-12">
            <select multiple="multiple" name="show_colonne[]" id="show_colonne" class="full-width-fix select2-select-00" data-placeholder="S&eacute;lectionnez" >
              <option></option>
              <?php   echo $row_liste_feuille["show_colonne"];
if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]); if(isset($row_liste_feuille["show_colonne"])) $id_s=$row_liste_feuille["show_colonne"];
   //echo '<option value="">'.$id.'</option>';

$query_liste_cp = "SELECT * FROM requete_carte_config WHERE id=$id";
  	   try{
    $liste_cp = $pdar_connexion->prepare($query_liste_cp);
    $liste_cp->execute();
    $row_liste_cp = $liste_cp ->fetch();
    $totalRows_liste_cp = $liste_cp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if(isset($row_liste_cp["classeur"])) $id=$row_liste_cp["classeur"];

  //select all classeur
$query_liste_cp = "SHOW tables";
  	   try{
    $liste_cp = $pdar_connexion->prepare($query_liste_cp);
    $liste_cp->execute();
    $row_liste_cp = $liste_cp ->fetchAll();
    $totalRows_liste_cp = $liste_cp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$table_array0=array();
if($totalRows_liste_cp>0) {
 foreach($row_liste_cp as $row_liste_cp1){   if(strchr($row_liste_cp1["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!="" && strchr($row_liste_cp1["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!=$database_connect_prefix."fiche_config" && strchr($row_liste_cp1["Tables_in_$database_pdar_connexion"],"fiche_".$id."_details_")!=""){   $table_array0[]=$row_liste_cp1["Tables_in_$database_pdar_connexion"];   }
} }


if(count($table_array0)>0){
foreach($table_array0 as $id){
  $query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$id'";
  	   try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$nomcol = "";
  if($totalRows_entete>0){ $libelle=explode("|",$row_entete["libelle"]); $nomcol=$row_entete["nom"]; }
  foreach($libelle as $llib1)
  {
    $lib=explode("=",$llib1);
    $libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";
  }

  $query_liste_feuille_get = "DESCRIBE ".$database_connect_prefix."$id ";
    	   try{
    $liste_feuille_get = $pdar_connexion->prepare($query_liste_feuille_get);
    $liste_feuille_get->execute();
    $row_liste_feuille_get = $liste_feuille_get ->fetchAll();
    $totalRows_liste_feuille_get = $liste_feuille_get->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  if($totalRows_liste_feuille_get>0)
  { ?>
              <?php echo "<optgroup label='".$nomcol."'>"; foreach($row_liste_feuille_get as $row_liste_feuille_get){   if(!in_array($row_liste_feuille_get['Field'],$interdit_array)){  ?>
              <option value="<?php echo $row_liste_feuille_get['Field']."/".$id."/".((isset($libelle_array[$row_liste_feuille_get['Field']]))?($libelle_array[$row_liste_feuille_get['Field']]):$row_liste_feuille_get['Field']); ?>" <?php $tem=$row_liste_feuille_get['Field']."/".$id; if(isset($id_s) && !empty($id_s)) $id_s1=explode(";",$id_s); else $id_s1=array(); if (in_array($tem,$id_s1)) {echo 'selected="selected"';} ?>>
              <?php
//if($row_liste_feuille_get['Field']!="annee")
echo $row_liste_feuille_get['Field']." => <small>".((isset($libelle_array[$row_liste_feuille_get['Field']])/* && isset($libelle_array[$id])*/)?($libelle_array[$row_liste_feuille_get['Field']])/*." => ".utf8_encode($libelle_array[$id])*/:$row_liste_feuille_get['Field'])."</small>"/*." => ".$nomcol*/;
/*else
echo (isset($libelle_array[$id]))?utf8_encode("Année")." => ".utf8_encode($libelle_array[$id]):utf8_encode("Année")." => ".$id;*/
 ?>
              </option>
              <?php } } echo "</optgroup>";
  }
}      }

}
 ?>
            </select>
          </div>
        </div>      </td>
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