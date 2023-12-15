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

$as=array();
if(isset($_GET["id"]))
{
    $id=($_GET["id"]);
    $query_edit_situation = "SELECT * FROM ".$database_connect_prefix."situation_marche WHERE code='$id'";
    try{
        $edit_situation = $pdar_connexion->prepare($query_edit_situation);
        $edit_situation->execute();
        $row_edit_situation = $edit_situation ->fetch();
        $totalRows_edit_situation = $edit_situation->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    if(isset($row_edit_situation['etape_concerne'])) $as = explode(", ", $row_edit_situation['etape_concerne']);
}
//Modeles
$query_liste_etape = "SELECT * FROM modele_marche, ".$database_connect_prefix."etape_marche where id_modele=modele_concerne  ORDER BY modele_marche.code, etape_marche.code asc";
try{
    $liste_etape = $pdar_connexion->prepare($query_liste_etape);
    $liste_etape->execute();
    $row_liste_etape = $liste_etape ->fetchAll();
    $totalRows_liste_etape = $liste_etape->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//Etapes
$query_liste_e_situation = "SELECT etape_concerne FROM ".$database_connect_prefix."situation_marche ";
try{
    $liste_e_situation = $pdar_connexion->prepare($query_liste_e_situation);
    $liste_e_situation->execute();
    $row_liste_e_situation = $liste_e_situation ->fetchAll();
    $totalRows_liste_e_situation = $liste_e_situation->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
//$codee_array = array();
$codeet_array ="";
if($totalRows_liste_e_situation>0){ foreach($row_liste_e_situation as $row_liste_e_situation){
 //$codee_array[]=$row_liste_e_situation["etape_concerne"].",";
 $codeet_array=$codeet_array.",".$row_liste_e_situation["etape_concerne"];
 }
}
$a=explode(',',$codeet_array); //echo $codeet_array; //$a=explode(',',$codeet_array);

//Modeles
$query_edit_modele = "SELECT * FROM ".$database_connect_prefix."modele_marche  ORDER BY code asc";
try{
    $edit_modele = $pdar_connexion->prepare($query_edit_modele);
    $edit_modele->execute();
    $row_edit_modele = $edit_modele ->fetchAll();
    $totalRows_edit_modele = $edit_modele->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
//$libelle_modele=$row_edit_modele['code']."- ".$row_edit_modele['categorie']." (".$row_edit_modele['methode_concerne'].")";
$libelle_modele_array ="";
if($totalRows_edit_modele>0){ foreach($row_edit_modele as $row_edit_modele){
 $libelle_modele_array[$row_edit_modele['id_modele']]=$row_edit_modele['code'];
 } }
?>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
  $(".modal-dialog", window.parent.document).width(800);
		$(".form-horizontal").validate();
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]))?"Modification":"Nouvel ajout"?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2s" id="form2s" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="code" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control required" name="code" id="code" type="text" value="<?php if(isset($_GET['id'])) echo $row_edit_situation['code'];  ?>" size="10" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_edit_situation['code']."'"; ?>) check_code('verif_code.php?t=situation_marche&','w=code='+this.value+' ','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">  
      <td>
        <div class="form-group">
          <label for="intitule" class="col-md-3 control-label">Libell&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" name="intitule" id="intitule" cols="32" rows="2"><?php if(isset($_GET['id'])) echo $row_edit_situation['intitule'];  ?></textarea>
          </div>
        </div>      </td>
    </tr>
   
    <tr valign="top">  
      <td>
        <div class="form-group">
          <label for="etape" class="col-md-3 control-label">Etapes concernées <span class="required">*</span></label>
          <div class="col-md-9">
<div style="height: 300px;overflow:scroll;">
          <?php if($totalRows_liste_etape>0) {  $p1="j"; foreach($row_liste_etape as $row_liste_etape){
		 if($p1!=$row_liste_etape['modele_concerne']) {?>
         <strong>  <u>
                      <?php if(isset($libelle_modele_array[$row_liste_etape['modele_concerne']])) echo $libelle_modele_array[$row_liste_etape['modele_concerne']]."</br>"; else echo "N/A</br>";
                      $p1=$row_liste_etape['modele_concerne']; ?>
                     </u> </strong>  
          <?php } 
		  
		   if(!in_array($row_liste_etape['id_etape'],$a) || in_array($row_liste_etape['id_etape'],$as)) { ?> <input <?php if(isset($_GET['id'])) { if(in_array($row_liste_etape['id_etape'], $as, TRUE)) echo 'checked="checked"'; } ?> type="checkbox" name="etape[]" id="etape_<?php echo $row_liste_etape['id_etape']; ?>" value="<?php echo $row_liste_etape['id_etape']; ?>" title="<?php echo $row_liste_etape['id_etape'].": ".substr($row_liste_etape['intitule'],0, 80); ?>"  />&nbsp;<label for="etape_<?php echo $row_liste_etape['id_etape']; ?>"><?php echo $row_liste_etape['code'].": ".substr($row_liste_etape['intitule'],0, 80); ?></label><br />
                        <?php } } } ?>
</div>
          </div>
        </div>      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette étape; ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form2s" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>