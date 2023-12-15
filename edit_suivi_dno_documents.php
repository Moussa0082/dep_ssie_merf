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

if(isset($_GET["dno"])){ $dno=$_GET['dno'];} $annee=$_GET['annee'];  $cp=(isset($_GET["cp"]))?$_GET['cp']:0;
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=intval($_GET["id"]);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_edit_suivi_dno = "SELECT * FROM ".$database_connect_prefix."suivi_dno WHERE id_suivi='$id'";
  $edit_suivi_dno = mysql_query($query_edit_suivi_dno, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_edit_suivi_dno = mysql_fetch_assoc($edit_suivi_dno);
  $totalRows_edit_suivi_dno = mysql_num_rows($edit_suivi_dno);
}

/* formatage de la taille */
function formatSize($s) {
  /* unités */
  $u = array('octets','Ko','Mo','Go','To');
  /* compteur de passages dans la boucle */
  $i = 0;
  /* nombre à afficher */
  $m = 0;
  /* division par 1024 */
  while($s >= 1) {
    $m = $s;
    $s /= 1024;
    $i++;
  }
  if(!$i) $i=1;
  $d = explode(".",$m);
  /* s'il y a des décimales */
  if($d[0] != $m) {
    $m = number_format($m, 2, ",", " ");
  }
  return $m." ".$u[$i-1];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>

<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}
</style>
</head>
<body>
<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $totalRows_edit_suivi_dno==1)
{ //documentation section  ?>

<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> Documents de la DNO <?php echo "'".$row_edit_suivi_dno["dno"]."' du ".date_reg($row_edit_suivi_dno["date_phase"],'/'); ?></h4>
</div>
<div class="widget-content">
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable dataTable" align="center" id="mtable" >
            <thead>
                <tr>
                  <td><strong>Nom du fichier</strong></td>
                  <td width="50"><strong>Date</strong></td>
                  <td width="80"><strong>Taile</strong></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center" width="90" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
                <?php $d = explode('|',$row_edit_suivi_dno["documents"]);
                $dir = './attachment/dano/';
                foreach($d as $file){
                      if(is_file($dir.$file) && !in_array($file, array("index.php","."))){
                        //$name = substr(strrchr($file, "/"), 1);
                ?>
                <tr>
                  <td><?php echo $file; ?></td>
                  <td><?php echo date("d/m/Y à H:i:s", filemtime($dir.$file)); ?></td>
                  <td align="right"><?php echo formatSize(filesize($dir.$file)); ?></td>
				   <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
<td align="center">
<a href="./download_file.php?file=<?php echo $dir.$file; ?>" title="Télécharger" style="margin:0px 5px;"><img src="./images/download.png" width="20" height="20" alt="Télécharger" title="Télécharger"></a>
 </td>
                   <?php } ?>
				  </tr>
                <?php } } ?>
              </table>
</div></div>
</div>
<?php }elseif($totalRows_edit_suivi_dno!=1) {//Pas de documents ?>
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> Documents de la DNO <?php if(isset($row_edit_suivi_dno["dno"])) echo $row_edit_suivi_dno["dno"]." du ".date_reg($row_edit_suivi_dno["date_phase"]); ?></h4>
</div>
<div class="widget-content">
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable dataTable" align="center" id="mtable" >
            <thead>
                <tr>
                  <td><strong>Nom du fichier</strong></td>
                  <td width="50"><strong>Date</strong></td>
                  <td width="80"><strong>Taile</strong></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center" width="90" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
                <tr>
                  <td colspan="<?php echo (isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1))?4:3; ?>">Aucun document attaché !</td>
				</tr>
              </table>
</div></div>
</div>
<!--FIN Documentation       -->
<?php } ?>

</body>
</html>