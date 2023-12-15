<?php
require_once 'required/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();
 
if(isset($_GET["Code"]) AND !empty($_GET["Code"]))
{
$html = file_get_contents("http://".$_SERVER["HTTP_HOST"]."/fiches/libs/dompdf/generation_carte.php?Code=".$_GET["Code"]);
$dompdf->loadHtml($html);
 
// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');
 
// Render the HTML as PDF
$dompdf->render();
 
// Output the generated PDF (1 = download and 0 = preview)
$dompdf->stream("Carte_".$_GET["Code"],array("Attachment"=>1));
}
?>