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
	});
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Mise &agrave; jour</h4> </div>
<div class="widget-content">
<form action="" class="form-horizontal" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate" onsubmit="return confirm('êtes vous sure de faire la mise à jour du package selectionné ?');">

<table id="mtable" class="table table-striped table-bordered table-hover table-responsive dataTable " align="center" >
  <tr>
    <td valign="middle"><div align="left"><strong>Date</strong></div></td>
    <td valign="middle"><div align="left"><strong>Fichier</strong></div></td>
    <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?> <td width="80" align="center"><strong>Action</strong></td> <?php } ?>
  </tr>
<?php

if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1))
{
  $projectsListIgnore = array ('.','..');
  $handle=opendir("./update/software/");
  $t = 0; $local_contents = array();
  while ($file = readdir($handle))
  {
    $ext = substr(strrchr($file, '.'),1);
    if (!in_array($file,$projectsListIgnore) && $ext=="zip") array_push($local_contents,$file);
  }
  $ftp_server = "ftp.ruche-psac.org";
  // Mise en place d'une connexion basique
  $conn_id = ftp_connect($ftp_server);
  // Identification avec un nom d'utilisateur et un mot de passe
  $login_result = ftp_login($conn_id, "ruche123", "123ruche123");
  // after ftp_login(...)
  ftp_pasv($conn_id, true);
  // Then chdir to the correct directory:
  // Récupération du contenu d'un dossier
  $contents = ftp_nlist($conn_id, "sse_".substr($database_connect_prefix,0,strlen($database_connect_prefix)-1)."/update/software");
  // after ftp_login(...)
  ftp_pasv($conn_id, true);
  //if($contents) echo "Recherche de mise à jour en ligne sur le serveur de PSAC ";
  // Affichage de $contents
  //var_dump($contents);
  foreach($contents as $server_file)
  {
    $ext = substr(strrchr($server_file, '.'),1);
    if ($ext=="zip")
    {
      $local_file = "./update/software/$server_file";
      if(!in_array($server_file,$local_contents))
      {
        if (ftp_get($conn_id, $local_file, "sse_".substr($database_connect_prefix,0,strlen($database_connect_prefix)-1)."/update/software/$server_file", FTP_BINARY)) {
            //echo "Le fichier $local_file a été écris avec succès\n";
        } else {
            //echo "Il y a un problème\n";
        }
      }
    }
  }
}

  $projectsListIgnore = array ('.','..');
  $handle=opendir("./update/software/");
  $t = 0;
  while ($file = readdir($handle))
  {
    $ext = substr(strrchr($file, '.'),1);
    $filename = "./update/software/".$file;
  	if (!in_array($file,$projectsListIgnore) && $ext=="zip")
  	{
  	  $nom = substr($file, 0, -4);
  	  $t = 1;   ?>
  <tr>
    <td><?php echo date("d/m/Y à H:i", filemtime($filename)); ?></td>
    <td><a href="javascript:void(0);"> <i class="icon-cog"></i> <?php echo "$nom"; ?></a></td>
    <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
    <td align="center"><input name="archive[]" id="archive" class="form-control required" type="checkbox" class="btn" value="<?php echo "$file"; ?>" /></td>
    <?php } ?>
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
<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>