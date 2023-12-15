<?php $nfile=$_SERVER['PHP_SELF'];
      $nfile= substr($nfile, strripos($nfile, "/")+1) ; 
      $nfile=trim($nfile,'.php');
      echo $nfile; ?>
      