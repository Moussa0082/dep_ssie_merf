<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"]) || !isset($_GET['classeur']) || !isset($_GET['feuille'])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET['classeur']) && intval($_GET['classeur'])>0) $classeur=$_GET['classeur'];
if(isset($_GET['feuille']) && $_GET['feuille']) $feuille=$_GET['feuille'];
$interdit_array = array("classeur","LKEY","annee","projet","structure","id_personnel","date_enregistrement","modifier_le","modifier_par","etat");

  //list cfg
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_mission = "SELECT intitule,colonnes FROM ".$database_connect_prefix."fiche_config where `table`='".$feuille."'";
  $liste_config  = mysql_query($query_liste_mission , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_config = mysql_fetch_assoc($liste_config);
  $totalRows_liste_config  = mysql_num_rows($liste_config);

if ((isset($_GET["id_sup"])) && $totalRows_liste_config>0) {
    $id = ($_GET["id_sup"]); $colonnes = "";
    $col = explode('|',$row_liste_config["colonnes"]);  $int = explode('|',$row_liste_config["intitule"]);
    foreach($int as $a=>$b){ if($a==$id) unset($int[$a]); }
    foreach($col as $a=>$b){ if($a==$id) unset($col[$a]); }
    $intitule = implode('|',$int); $colonnes = implode('|',$col);

    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."fiche_config SET intitule=%s, colonnes=%s WHERE `table`=%s",
                         GetSQLValueString($intitule, "text"),
                         GetSQLValueString($colonnes, "text"),
                         GetSQLValueString($feuille, "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok&mod=1"; else $insertGoTo .= "?del=no";
  $insertGoTo .= "&classeur=$classeur&feuille=$feuille";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_colonne = "DESCRIBE ".$database_connect_prefix."$feuille";
  $liste_colonne  = mysql_query($query_liste_colonne , $pdar_connexion) or die(mysql_error());
  $row_liste_colonne  = mysql_fetch_assoc($liste_colonne);
  $totalRows_liste_colonne  = mysql_num_rows($liste_colonne);

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert") && $totalRows_liste_config>0) {
    $col = $row_liste_config["colonnes"];  $int = $row_liste_config["intitule"];
    $colonnes = $col;
    $intitule = $int.$_POST["intitule"]."|";
    if($totalRows_liste_colonne>0){ do{ if(($row_liste_colonne["Field"]==$_POST["debut"] || isset($tem)) && isset($_POST["fin"]) && $_POST["fin"]>=0 && !in_array($row_liste_colonne["Field"],$interdit_array)){ $colonnes .= $row_liste_colonne["Field"].";"; $tem=0; $_POST["fin"]--; } }while($row_liste_colonne  = mysql_fetch_assoc($liste_colonne)); }
    $colonnes .= '|';
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."fiche_config SET intitule=%s, colonnes=%s WHERE `table`=%s",
                         GetSQLValueString($intitule, "text"),
                         GetSQLValueString($colonnes, "text"),
                         GetSQLValueString($feuille, "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok&mod=1"; else $insertGoTo .= "?insert=no";
    $insertGoTo .= "&classeur=$classeur&feuille=$feuille";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($_POST["MM_delete"])) && $totalRows_liste_config>0) {
    $id = ($_POST["MM_delete"]); $colonnes = "";
    $col = explode('|',$row_liste_config["colonnes"]);  $int = explode('|',$row_liste_config["intitule"]);
    foreach($int as $a=>$b){ if($a==$id) unset($int[$a]); }
    foreach($col as $a=>$b){ if($a==$id) unset($col[$a]); }
    $intitule = implode('|',$int); $colonnes = implode('|',$col);

    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."fiche_config SET intitule=%s, colonnes=%s WHERE `table`=%s",
                         GetSQLValueString($intitule, "text"),
                         GetSQLValueString($colonnes, "text"),
                         GetSQLValueString($feuille, "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok&mod=1"; else $insertGoTo .= "?del=no";
    $insertGoTo .= "&classeur=$classeur&feuille=$feuille";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"])) && $totalRows_liste_config>0) {
    $id = ($_POST["MM_update"]); $colonnes = "";
    $col = explode('|',$row_liste_config["colonnes"]);  $int = explode('|',$row_liste_config["intitule"]);
    foreach($int as $a=>$b){ if($a==$id) $int[$a] = $_POST["intitule"]; }
    if($totalRows_liste_colonne>0){ do{ if(($row_liste_colonne["Field"]==$_POST["debut"] || isset($tem)) && isset($_POST["fin"]) && $_POST["fin"]>=0 && !in_array($row_liste_colonne["Field"],$interdit_array)){ $colonnes .= $row_liste_colonne["Field"].";"; $tem=0; $_POST["fin"]--; } }while($row_liste_colonne  = mysql_fetch_assoc($liste_colonne)); }
    foreach($col as $a=>$b){ if($a==$id) $col[$a] = $colonnes; }
    $intitule = implode('|',$int); $colonnes = implode('|',$col);

    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."fiche_config SET intitule=%s, colonnes=%s WHERE `table`=%s",
                         GetSQLValueString($intitule, "text"),
                         GetSQLValueString($colonnes, "text"),
                         GetSQLValueString($feuille, "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok&mod=1"; else $insertGoTo .= "?update=no";
    $insertGoTo .= "&classeur=$classeur&feuille=$feuille";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

?>                                                          
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
<meta name="viewport" content="width=400, initial-scale=1.0">
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->
<link rel="stylesheet" type="text/css" href="<?php print $config->theme_folder;?>/plugins/jquery-ui.css"/>
<link href="<?php print $config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder;?>/responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder;?>/icons.css" rel="stylesheet" type="text/css"/>
<link href='<?php print $config->theme_folder;?>/css.css' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>
<script type="text/javascript" src="plugins/noty/layouts/top.js"></script>
<script type="text/javascript" src="plugins/noty/themes/default.js"></script>
<script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>

<!--<script type="text/javascript" src="plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>
<script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>-->
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form3").validate();
        $(".modal-dialog", window.parent.document).width(600);
<?php if(isset($_GET['mod'])) { ?>
        // reload parent frame
        $(".close", window.parent.document).click(function(){window.parent.location.reload();});
        $("button[data-dismiss='modal']", window.parent.document).click(function(){window.parent.location.reload();});
<?php } ?>
	});
</script>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 2px 8px;background: #EBEBEB;}

@media(min-width:558px){.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}
</style>
<?php if(!isset($_GET['add'])) { ?>
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> Fusionnement</h4>
   <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<a href="<?php echo $_SERVER['PHP_SELF']."?add=1&classeur=$classeur&feuille=$feuille"; ?>" class="pull-right p11" title="Ajout une fusion" ><i class="icon-plus"> Nouvelle fusion </i></a>
<?php } ?>
</div>
<div class="widget-content">
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >
            <thead>
                <tr>
                  <td><div align="left"><strong>Intitul&eacute;</strong></div></td>
                  <td><div align="left"><strong>Colonnes</strong></div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center" width="80" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
                <?php if($totalRows_liste_config>0 && !empty($row_liste_config['intitule']) && !empty($row_liste_config['colonnes'])) {$i=0;
                $a = explode('|',$row_liste_config['intitule']);
                $b = explode('|',$row_liste_config['colonnes']);
                foreach($a as $c=>$d){ if(!empty($d)){ $id = $c; ?>
                <tr>
                  <td><?php echo $d; ?></td>
                  <td><div align="left"><?php echo implode(' - ',explode(';',$b[$c])); ?></div></td>
				   <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<td align="center">
<!--<a href="<?php echo $_SERVER['PHP_SELF']."?id=$id&add=1&classeur=$classeur&feuille=$feuille"; ?>" title="Modifier fusion" style="margin:0px 5px;"><img src="./images/edit.png" width="20" height="20" alt="Modifier" title="Modifier"></a>--><a href="<?php echo $_SERVER['PHP_SELF']."?id_sup=$id&classeur=$classeur&feuille=$feuille"; ?>" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cette fusion ?');" style="margin:0px 5px;"><img src="./images/delete.png" width="20" height="20" alt="Supprimer" title="Supprimer"></a> </td>

                   <?php } ?>
				  </tr>
                <?php } } ?>
                <?php } else echo "<tr><td colspan='3' align='center'><h2>Aucun enregistrement !</h2></td></tr>"; ?>
              </table>

</div></div>
</div>
<?php } else {
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_colonne = "DESCRIBE ".$database_connect_prefix."$feuille";
  $liste_colonne  = mysql_query($query_liste_colonne , $pdar_connexion) or die(mysql_error());
  $row_liste_colonne  = mysql_fetch_assoc($liste_colonne);
  $totalRows_liste_colonne  = mysql_num_rows($liste_colonne);
  if(isset($id)){
  $a = explode('|',$row_liste_config['intitule']);
  $b = explode('|',$row_liste_config['colonnes']);
  $c = isset($b[$id])?$b[$id]:"";
  if(!empty($c)) $c = explode(';',$c); else $c = array(); }
?>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification de fusionnement":"Nouvelle fusionnement"; ?></h4>
<a href="<?php echo $_SERVER['PHP_SELF']."?classeur=$classeur&feuille=$feuille"; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="<?php echo $_SERVER['PHP_SELF']."?classeur=$classeur&feuille=$feuille"; ?>" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="intitule" class="col-md-3 control-label">Intitul&eacute; <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea class="form-control required" cols="200" rows="3" type="text" name="intitule" id="intitule"><?php echo (isset($id) && isset($a[$id]))?$a[$id]:""; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="debut" class="col-md-3 control-label">A partir de <span class="required">*</span></label>
          <div class="col-md-9">
            <select name="debut" id="debut" class="form-control required" >
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_colonne>0){
              $colonnes = array(); $temoin = $k = 0;
              if(!isset($_GET["id"])){ if(empty($row_liste_config["colonnes"])) $temoin = 1;
              $col = explode('|',$row_liste_config["colonnes"]);
              foreach($col as $a){ $b = explode(';',$a); foreach($b as $c) if(!in_array($c,$colonnes) && !empty($c)) array_push($colonnes,$c); }
              }
              do{ if(isset($colonnes[count($colonnes)-1]) && $colonnes[count($colonnes)-1]==$row_liste_colonne["Field"]) $temoin = 1;
              if(!in_array($row_liste_colonne["Field"],$interdit_array) && !in_array($row_liste_colonne["Field"],$colonnes) && (isset($id) || $temoin==1)){ $k++; ?>
              <option value="<?php echo $row_liste_colonne["Field"]; ?>" <?php if(isset($c[0]) && isset($_GET['id']) && $row_liste_colonne['Field']==$c[0]) echo 'selected="selected"'; ?>><?php echo $row_liste_colonne["Field"]; ?></option>
              <?php } }while($row_liste_colonne  = mysql_fetch_assoc($liste_colonne)); } ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
<script>
$().ready(function() {
$("#slider-range-min").slider({range:"min",value:1,min:1,max:<?php echo "$k"; ?>,slide:function(a,b){$("#fin").val(b.value);$("#slider-range-min-amount").text(b.value)}});
});
</script>
        <div class="form-group" id="code_zone">
          <label for="fin" class="col-md-3 control-label">Nombre de colonne <span class="required">*</span></label>
          <div class="col-md-9">
            <input class="form-control required" type="hidden" name="fin" id="fin" value="<?php echo (isset($c) && (count($c)-2)>0)?count($c)-2:"1"; ?>" size="32" />
<div class="slider-controls slider-value-top"> Valeur : <span id="slider-range-min-amount"><?php echo (isset($c) && (count($c)-2)>0)?count($c)-2:"1"; ?></span> </div> <div id="slider-range-min"></div>
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo ($_GET["id"]); ?>" />
<?php } ?>
<?php if(isset($_GET["annee"])){ ?>
  <input type="hidden" name="annee" value="<?php echo intval($_GET["annee"]); ?>" />
<?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
<?php if(!isset($_GET['add2'])) { ?>
<a href="<?php echo $_SERVER['PHP_SELF']."?classeur=$classeur&feuille=$feuille"; ?>" class="btn pull-right" title="Annuler" >Annuler</a>
<?php } ?>
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($id) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2)) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette fusion ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
    <?php } ?>
  <?php } ?>
<?php } ?>