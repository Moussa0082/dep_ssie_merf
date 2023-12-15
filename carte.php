<?php
if (isset ($_GET["Code"])) 
{?>
<iframe <?php echo 'src="https://'.$_SERVER['HTTP_HOST'].'/fiches/libs/dompdf/generation_carte.php?Code='.$_GET["Code"].'"'; ?>  style="border: 1px; height: 90vh" width="100%" allow="fullscreen" high></iframe>
<?php }
?>
