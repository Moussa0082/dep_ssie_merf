<?php
if(isset($_GET["file"]) && !empty($_GET["file"]) && file_exists($_GET["file"]))
{
  $file = $_GET["file"];
  $ext = substr(strrchr($file, "."), 1);
  switch ($ext)
  {
    /* Defaut */
    default:
    header("Content-Type: application/force-download");
    break;
    /* Excel file */
    case 'xls':
    case 'xlsx':
    header("Content-Type: application/vnd.ms-excel");
    break;
    /* Word file */
    case 'doc':
    case 'docx':
    header("Content-Type: application/vnd.ms-word");
    break;
    /* PDF file */
    case 'pdf':
    header("Content-Type: application/pdf");
    break;
    /* JPG file */
    case 'jpg':
    case 'jpeg':
    header("Content-Type: image/jpg");
    break;
  }
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("content-disposition: attachment;filename=".$file);
  $ext_refusees=array('php','php5','css','js', 'html'); //Extensions refusées
  if(!in_array($ext,$ext_refusees))
  readfile($file);
} else
{
  $file = $_GET["file"];
  $name = substr(strrchr($file, "/"), 1);
  echo "<h1 align='center'>Impossible de lire de fichier<br>'$name' !</h1>";
}
?>