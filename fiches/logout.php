<?php
   ///////////////////////////////////////////////
  /*                 SSE                       */
 /*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////

  session_start();

$page = (isset($_GET["page"]))?$_GET["page"]:"./";
if(isset($_SESSION['id']))
{
  $date=date("Y-m-d H:i:s"); $personnel=$_SESSION["id"];
  require_once 'api/db.php';
	$q = $db ->prepare('UPDATE t_connecter SET date_deconnexion=:date_deconnexion WHERE user_id=:personnel and date_deconnexion=:date_deconnexion1');
    $q->execute(array(
            ':date_deconnexion' => $date,
			':personnel' => $personnel,
            ':date_deconnexion1' => NULL));

  foreach($_SESSION as $a=>$b)
  {
    $_SESSION[$a] = null;
    if(isset($_SESSION[$a])) session_unset($_SESSION[$a]);
  }
  if(isset($_GET["identifiant"]))
  header(sprintf("Location: %s", "$page?identifiant=".$_GET["identifiant"]));
  else header(sprintf("Location: %s", "$page?success=true")); exit;
}
else {header(sprintf("Location: %s", "$page")); exit;}
?>