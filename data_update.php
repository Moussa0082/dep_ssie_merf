<?php ini_set("display_errors",1); ini_set("error_reporting","E_ERROR");
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
header('Content-Type: text/html; charset=UTF-8');

?>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
</style>
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();
        get_content_distant("http://ruche-psac.org/sse_<?php echo substr($database_connect_prefix,0,strlen($database_connect_prefix)-1); ?>/update_data.php","down=1","","","","",1);
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Mise &agrave; jour</h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate" onsubmit="return confirm('êtes vous sure de faire la mise à jour du package selectionné ?');">

<table id="mtable" class="table table-striped table-bordered table-hover table-responsive dataTable " align="center" >
  <tr>
    <td valign="middle"><div align="left"><strong>Date</strong></div></td>
    <td valign="middle"><div align="left"><strong>Fichier</strong></div></td>
    <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?> <!--<td width="80" align="center"><strong>Action</strong></td>--> <?php } ?>
  </tr>
<?php

if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1))
{
  $projectsListIgnore = array ('.','..');
  $dir = "./update/data/";  if(!is_dir($dir)) mkdir($dir);
  $handle=opendir($dir);
  $t = 0; $local_contents = array();
  while ($file = readdir($handle))
  {
    if(file_exists($dir.$file)) unlink($dir.$file);
    /*$ext = substr(strrchr($file, '.'),1);
    if (!in_array($file,$projectsListIgnore) && $ext=="zip") array_push($local_contents,$file);*/
  }
  $ftp_server = "ftp.ruche-psac.org";
  // Mise en place d'une connexion basique
  $conn_id = ftp_connect($ftp_server);
  // Identification avec un nom d'utilisateur et un mot de passe
  $login_result = ftp_login($conn_id, "ruche123", "123ruche123");
  // Then chdir to the correct directory:
  // after ftp_login(...)
  ftp_pasv($conn_id, true);
  // Récupération du contenu d'un dossier
  //$contents = ftp_nlist($conn_id, "sse_".substr($database_connect_prefix,0,strlen($database_connect_prefix)-1)."/update/data");
  //if($contents) echo "Recherche de mise à jour en ligne sur le serveur de PSAC ";
  // Affichage de $contents
  //var_dump($contents);
  ftp_get($conn_id, $dir."sql.gzip", "sse_".substr($database_connect_prefix,0,strlen($database_connect_prefix)-1)."/update/data/sql.gzip", FTP_BINARY);
  ftp_get($conn_id, $dir."fichiers.zip", "sse_".substr($database_connect_prefix,0,strlen($database_connect_prefix)-1)."/update/data/fichiers.zip", FTP_BINARY);
}

  $projectsListIgnore = array ('.','..');
  $handle=opendir($dir);
  $t = 0;
  while ($file = readdir($handle))
  {
    $ext = substr(strrchr($file, '.'),1);
    $filename = $dir.$file;
  	if (!in_array($file,$projectsListIgnore) && ($ext=="zip" || $ext=="gzip"))
  	{
  	  $nom = substr($file, 0, -strlen($ext)-1);
  	  $t = 1;   ?>
  <tr>
    <td><?php echo date("d/m/Y à H:i", filemtime($filename)); ?></td>
    <td><a href="javascript:void(0);"> <i class="icon-cog"></i> <?php echo "$nom"; ?></a>
    <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
    <input name="archive[]" id="archive" class="form-control required" type="hidden" class="btn" checked="checked" value="<?php echo "$file"; ?>" />
    <?php } ?>
    </td>
  </tr>
<?php
  	}
  }
  closedir($handle);
?>
<!--  <tr>
    <td>05/11/2015</td>
    <td><a href="javascript:void(0);"> <i class="icon-cog"></i> Archive2</a></td>
    <td align="center"><input name="archive[]" id="archive" class="form-control required" type="checkbox" class="btn" value="archive1.zip" /></td>
  </tr>-->
</table>

<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="installer cette Mise &agrave; jour" />
  <input name="MM_insert" type="hidden" value="MM_insert" size="32" alt="">
<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>