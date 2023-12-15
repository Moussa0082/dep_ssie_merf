<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;
//echo $_GET["id"];
if (!isset ($_SESSION["clp_id"]) && isset($_GET["id"]) && !empty($_GET["id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=$_GET["id"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_region = "SELECT * FROM ".$database_connect_prefix."convention  WHERE code_convention=".GetSQLValueString($_GET["id"],'text')."";
  $listeregion  = mysql_query($query_liste_region , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_region  = mysql_fetch_assoc($listeregion);
  $totalRows_listeregion  = mysql_num_rows($listeregion);
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_region= "SELECT abrege_region ,nom_region,code_region FROM ".$database_connect_prefix."region  order by nom_region asc";
$liste_region = mysql_query($query_liste_region, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$totalRows_liste_region  = mysql_num_rows($liste_region);
$tableauRegion=$tableauRegionV=array();
while($ligne=mysql_fetch_assoc($liste_region)){$tableauRegion[]=$ligne['code_region']."<>".$ligne['abrege_region']; $tableauRegionV[$ligne['code_region']]=$ligne['nom_region'];}

?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]) && $totalRows_liste_region>0)?"Modification de la zone d'intervention":"Nouvelle de la zone d'intervention"?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">

<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive dataTable " align="center" >
<thead>
  <tr>
    <td><div align="left"><strong>R&eacute;gion</strong></div></td>
    <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
    <td width="80" align="center"><strong>Choix</strong><br>
    <input id="checkId" type="checkbox" class="btn" onclick="check_all('region',this);" />
    </td>
    <?php } ?>
  </tr>
</thead>
<tbody class="region">
  <?php if(is_array($tableauRegion)) { global $j; $j=1; $rg = explode('|',$row_liste_region['region_concerne']);
  foreach($tableauRegion as $vregion) { $aregion = explode('<>',$vregion); $iregion = $aregion[0];
    echo '<tr><td><i class="icon-angle-right"></i> '.$tableauRegionV[$iregion].'</td>
      '.((isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1))?'<td align="center" class=""><input name="region[]" type="checkbox" '.((is_array($rg) && in_array($iregion,$rg)?"checked='checked'":"")).' class="btn" value="'.$iregion.'" /></td>':'');
    } } ?>
</tbody>
</table>

<div class="form-actions">
  <input name="convention" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $_GET["id"]; ?>" size="32" alt="">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php echo "Valider" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $totalRows_listeregion>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $totalRows_listeregion>0) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
<?php /*if(isset($_GET["id"]) && !empty($_GET["id"]) && $totalRows_liste_region>0) { ?>
<!--<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer les r&eacute;gions ?','<?php echo $id; ?>');" class="btn btn-danger pull-left" value="Supprimer" />-->
<?php }*/ ?>
<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>