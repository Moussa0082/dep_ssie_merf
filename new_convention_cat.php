<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
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
  $query_liste_cat = "SELECT P.* FROM ".$database_connect_prefix."categorie_depense P WHERE P.code=".GetSQLValueString($_GET["id"],'text')."";
  try{
        $liste_cat = $pdar_connexion->prepare($query_liste_cat);
        $liste_cat->execute();
        $row_liste_cat = $liste_cat ->fetch();
        $totalRows_liste_cat = $liste_cat->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
}

//Bailleurs
$query_liste_convention = "SELECT * FROM ".$database_connect_prefix."type_part order by code_type";
try{
    $liste_convention = $pdar_connexion->prepare($query_liste_convention);
    $liste_convention->execute();
    $row_liste_convention = $liste_convention ->fetchAll();
    $totalRows_liste_convention = $liste_convention->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableauConvention=$tableauConventionV=array();
if($totalRows_liste_convention>0){ foreach($row_liste_convention as $row_liste_convention){
$tableauConventionV[$row_liste_convention["code_type"]]=$row_liste_convention["intitule"];
$tableauConvention[$row_liste_convention["code_type"]]=$row_liste_convention["code_type"]."<>".$row_liste_convention['bailleur'];
} }
//Catégories
$query_liste_dotation_convention = "SELECT convention, dotation_initiale FROM ".$database_connect_prefix."categorie_depense_convention where categorie_depense=".GetSQLValueString($_GET["id"],'text')." and projet='".$_SESSION["clp_projet"]."' order by convention";
try{
    $liste_dotation_convention = $pdar_connexion->prepare($query_liste_dotation_convention);
    $liste_dotation_convention->execute();
    $row_liste_dotation_convention = $liste_dotation_convention ->fetchAll();
    $totalRows_liste_dotation_convention = $liste_dotation_convention->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$tableauConventionDotation=array();
if($totalRows_liste_dotation_convention>0){ foreach($row_liste_dotation_convention as $row_liste_dotation_convention){
$tableauConventionDotation[$row_liste_dotation_convention["convention"]]=$row_liste_dotation_convention["dotation_initiale"];
} }
/*$tableauConvention=$tableauConventionV=array();
while($ligne=mysql_fetch_assoc($liste_convention)){echo $ligne['code_type']; $tableauConvention[]=$ligne['code_type']."<>".$ligne['bailleur']; $tableauConventionV[$ligne['code_type']]=$ligne['intitule'];}*/
?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo ($totalRows_liste_cat>0)?$row_liste_cat['nom_categorie']:"Catégorie non définie"?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">
 <div style="height: 300px; overflow: scroll;">
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive dataTable " align="center" >
<thead>
  <tr>
    <td><div align="left"><strong>Conventions concernées</strong></div></td>
	 <td><div align="left"><strong>Dotation</strong></div></td>
   
  </tr>
</thead>
<tbody class="convention">
  <?php if(is_array($tableauConvention)) { global $j; $j=0; $rg = explode('|',$row_liste_cat['convention_concerne']);
  foreach($tableauConvention as $vconvention) { $aconvention = explode('<>',$vconvention); $iconvention = $aconvention[0];
    echo '<tr><td><span style="font-size:12px"><i class="icon-angle-right"></i> '.$tableauConventionV[$iconvention].'</span></td>'; ?>
	<td><span style="font-size:12px">
	<input name="dotation[]" type="text" class="form-control typeahead required" value= "<?php if(isset($tableauConventionDotation[$iconvention])) echo $tableauConventionDotation[$iconvention]; ?>" size="15" />
	</span><input name="convention[]" type="hidden"  value=" <?php echo  $iconvention ?>" /></td>
    <?php  } } ?>
</tbody>
</table>
</div>
<div class="form-actions">
  <input name="code" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $_GET["id"]; ?>" size="32" alt="">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php echo "Valider" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $totalRows_liste_cat>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $totalRows_liste_cat>0) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
<?php /*if(isset($_GET["id"]) && !empty($_GET["id"]) && $totalRows_liste_convention>0) { ?>
<!--<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer les r&eacute;gions ?','<?php echo $id; ?>');" class="btn btn-danger pull-left" value="Supprimer" />-->
<?php }*/ ?>
<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>