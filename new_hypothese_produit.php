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

$cmp = (isset($_GET["cmp"]) && !empty($_GET["cmp"]))?($_GET["cmp"]):'';
$id_cp = (isset($_GET["resultat"]) && intval($_GET["resultat"])>0)?intval($_GET["resultat"]):'';
$prd = (isset($_GET["prd"]) && intval($_GET["prd"])>0)?intval($_GET["prd"]):'';

if ((isset($_GET["id_sup"]) && intval($_GET["id_sup"])>0)) {
  $id = intval($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from hypothese_produit WHERE id_hypothese=%s",
                       GetSQLValueString($id, "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = "./cadre_logique_edit.php";
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if(isset($_GET["id"]) && intval($_GET["id"])>0)
{
  $id=intval($_GET["id"]);
  
    $query_liste_hypothese = "SELECT * FROM hypothese_produit WHERE id_hypothese=$id ";
try{
    $liste_hypothese = $pdar_connexion->prepare($query_liste_hypothese);
    $liste_hypothese->execute();
    $row_liste_hypothese = $liste_hypothese ->fetch();
    $totalRows_liste_hypothese = $liste_hypothese->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
    /*
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_effet = "SELECT resultat.* FROM resultat, indicateur_resultat where id_resultat=resultat ";
  $query_liste_effet .= (!empty($id_os))?" and composante=$id_os ":'';
  $query_liste_effet .= " group by id_resultat ";
  $liste_effet  = mysql_query($query_liste_effet , $pdar_connexion) or die(mysql_error());
  $row_liste_effet  = mysql_fetch_assoc($liste_effet);
  $totalRows_liste_effet  = mysql_num_rows($liste_effet);  */
}

$query_liste_prd  = "SELECT * FROM produit where id_produit='$prd'";
//echo $query_liste_prd ; exit;
try{
    $liste_prd  = $pdar_connexion->prepare($query_liste_prd );
    $liste_prd ->execute();
    $row_liste_prd = $liste_prd  ->fetchAll();
    $totalRows_liste_prd  = $liste_prd ->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0)?"Modification":"Nouvelle"; echo " Hypoth&egrave;se Produit";?></h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form15" id="form15" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

<!--    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="code" class="col-md-3 control-label">Code <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="text" name="code" id="code" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_hypothese['code']; ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>-->
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="produit" class="col-md-3 control-label">Produit <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="produit1" id="produit1" class="form-control required" >
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_prd>0) {  foreach($row_liste_prd as $row_liste_prd){ ?>
              <option value="<?php echo $row_liste_prd['id_produit']; ?>" <?php if(!empty($prd)) {if ($row_liste_prd['id_produit']==$prd) {echo "SELECTED";}} ?>><?php echo $row_liste_prd['intitule_produit']; ?></option>
              <?php }} ?>
            </select>
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="nom" class="col-md-3 control-label">Intitul&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" name="nom" id="nom" cols="25" rows="5"><?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo $row_liste_hypothese['intitule_hypothese']; ?></textarea>
          </div>
        </div>      </td>
    </tr>
    <tr><td><br /></td></tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cet hypoth&egrave;se ?',<?php echo intval($_GET["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form16" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

<script type="text/javascript" >
//get_content('menu_effet.php','id=<?php //echo $cmp.'&id_s='.$id_cp; ?>','resultat','');
//get_content('menu_produit.php','id=<?php //echo $id_cp.'&id_s='.((isset($row_liste_hypothese["produit"]))?$row_liste_hypothese["produit"]:$prd); ?>','produit','');
</script>

</div> </div>