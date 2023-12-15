<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (strchr($_SERVER["HTTP_REFERER"],"http://www.suivi.prodaf.net/")!="") {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
header('Content-Type: text/html; charset=UTF-8');
$dir = "./update/data/";  if(!is_dir($dir)) mkdir($dir);

function Delete($path)
{
    if (is_dir($path) === true)
    {
        $files = array_diff(scandir($path), array('.', '..'));

        foreach ($files as $file)
        {
            Delete(realpath($path) . '/' . $file);
        }

        return rmdir($path);
    }

    else if (is_file($path) === true)
    {
        return unlink($path);
    }

    return false;
}

class ExtendedZip extends ZipArchive {

    // Member function to add a whole file system subtree to the archive
    public function addTree($dirname, $localname = '') {
        if ($localname)
            $this->addEmptyDir($localname);
        $this->_addTree($dirname, $localname);
    }

    // Internal function, to recurse
    protected function _addTree($dirname, $localname) {
        $dir = opendir($dirname);
        while ($filename = readdir($dir)) {
            // Discard . and ..
            if ($filename == '.' || $filename == '..')
                continue;

            // Proceed according to type
            $path = $dirname . '/' . $filename;
            $localpath = $localname ? ($localname . '/' . $filename) : $filename;
            if (is_dir($path)) {
                // Directory: add & recurse
                $this->addEmptyDir($localpath);
                $this->_addTree($path, $localpath);
            }
            else if (is_file($path)) {
                // File: just add
                $this->addFile($path, iconv("ISO-8859-1", "CP437", $localpath));
            }
        }
        closedir($dir);
    }

    // Helper function
    public static function zipTree($dirname, $zipFilename, $flags = 0, $localname = '') {
        $zip = new self();
        $zip->open($zipFilename, $flags);
        $zip->addTree($dirname, $localname);
        $zip->close();
    }
}

if(isset($_GET["sup"]))
{
  if(file_exists($dir."ids"))
  {
    $fp = fopen( $dir."ids" , "r" );
    $ids = fread($fp, filesize($dir."ids"));
    fclose($fp);
    if(!empty($ids))
    {
      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $query_sync = "DELETE FROM ".$database_connect_prefix."ruche_sync WHERE code is null ";
      $sync = mysql_query($query_sync, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

      $query_sync = "DELETE FROM ".$database_connect_prefix."ruche_sync WHERE id in ($ids 0) ";
      $sync = mysql_query($query_sync, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
      //$row_sync = mysql_fetch_assoc($sync);
      //$totalRows_sync = mysql_num_rows($sync);
      unlink($dir."ids"); unlink($dir."sql.gzip");  unlink($dir."fichiers.zip"); 
    }
  }
  exit;
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sync = "SELECT * FROM ".$database_connect_prefix."ruche_sync WHERE code is not null ";
$sync = mysql_query($query_sync, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_sync = mysql_fetch_assoc($sync);
$totalRows_sync = mysql_num_rows($sync);
if(isset($_GET["down"]))
{
  $data = $fichier = $ids = "";
  if($totalRows_sync>0){
    do{
        if($row_sync["type"]==0) $data.= $row_sync["code"].";";
        else $fichier.= $row_sync["code"]."|";
        $ids .= $row_sync["id"].",";
      }while($row_sync = mysql_fetch_assoc($sync));
      if(!empty($data))
      {
        //Creation du zip des données SQL
        // Save text to file
        $fp = fopen( $dir."ids" , "w" );
        fwrite($fp, $ids);
        fclose($fp);
        //Compression en gzip
        $file = @gzopen($dir."sql.gzip", "w9");
        @gzwrite($file,$data);
        @gzclose($file);
        unlink($dir."data.sql");
      }
      if(!empty($fichier))
      {
        $dirF = $dir.'fichiers/'; if(!is_dir($dirF)) mkdir($dirF);
        //Creation du zip des données fichier
        $file = explode("|",$fichier);
        foreach($file as $f)
        {
          $f1 = $f;
          $f = utf8_encode($f);
          if(!empty($f) && file_exists($f))
          {
            $tmp = substr($f,2,strripos($f,'/')-2);
            //creation des sous repertoires
            $tmp = explode("/",$tmp); $dirT = $dirF;
            foreach($tmp as $t)
            {
              if(!is_dir($dirT.$t)) mkdir($dirT.$t);
              $dirT = $dirT.$t."/";
            }
            copy($f,$dirF.substr($f1,2));
          }
        }
        if(file_exists($dir.'fichiers.zip')) unlink($dir.'fichiers.zip');
        ExtendedZip::zipTree($dirF, $dir.'fichiers.zip', ZipArchive::CREATE);
        Delete($dirF);
      }
  }
}
else
{
  if($totalRows_sync>0){ echo '<b>'.$totalRows_sync.'</b> mise &agrave; jour disponible <div class="clear">&nbsp;</div>';
?>
<div style="width: 50%; margin: auto; padding: auto;">
  <input title="Mise &agrave; jour de donn&eacute;es de l'application" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" data-backdrop="static" data-keyboard="false" name="software_update" type="button" class="btn btn-success btn-block" value="T&eacute;l&eacute;charger et mettre &agrave; jour" onclick="get_content('data_update.php','','modal-body_add',this.title);" />
</div>
<?php }else echo "Aucune mise à jour disponible"; } ?>