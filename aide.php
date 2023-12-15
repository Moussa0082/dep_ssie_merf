<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();                                 
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print $config->sitename; ?> - Aide</title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="keywords" content="<?php print $config->MetaKeys; ?>" />
  <meta name="description" content="<?php print $config->MetaDesc; ?>" />
  <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
  <meta name="author" content="<?php print $config->MetaAuthor; ?>" />
  <!--<meta charset="utf-8">-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
</head>
<body style="font-family:'Goudy Old Style', Candara, Helvetica, sans-serif;">
    <table width="100%" align="center">                 
    	<tr>
        	<td align="center" valign="middle"><img style="float: left" src="images/ruche.png" border="0" width="80" height="80" />&nbsp;&nbsp;
        	<h2 style="float: left; margin: 0 5px;">Session Utilisateur - Aide</h2></td>
        </tr>
<?php if(isset($MENU) && isset($MENU_TITLE)) {
  $id = intval($_GET["d"]);
  if($id==0){
?>
        <tr>
        	<td align="justify"><h3><strong><u>1. Accueil</u></strong></h3></td>
        </tr>
        <tr>
        	<td align="justify" style="padding-left:30px; padding-right:10px">
            Cet onglet vous d&eacute;crit bri&egrave;vement la fonction des diff&eacute;rents onglets de votre session. Vous pouvez acc&eacute;der à un onglet en cliquant sur ledit onglet ou sur sa description à l'accueil.
            </td>
        </tr>
        <tr><td><br /></td></tr>
 <?php }else {
if(isset($MENU[$id])){ ?>
        <tr>
        	<td align="justify"><h3><strong><u><?php if(isset($MENU_TITLE[$id][0])) echo $MENU_TITLE[$id][0]; ?></u></strong></h3></td>
        </tr>
 <?php $i=1; $d=0; foreach($MENU[$id] as $a=>$b){  ?>
        <tr>
        	<td align="justify"><h3><i><u><?php echo ($d+1).".$i. "; if(isset($b) && !is_array($b)) echo $b;
            elseif(is_array($b)){ foreach($b as $c) echo $c." - "; } else echo 'NaN';  ?></u></i></h3></td>
        </tr>
        <tr>
        	<td align="justify" style="padding-left:30px; padding-right:10px">
<!--Description ici -->
            </td>
        </tr>
 <?php $i++; $d++; } }else echo "<tr><td><br /></td></tr>";
 } } ?>
  <tr>
     <td>
     	<table width="100%" align="center">
            <tr>
                <td align="left">
                    <p><font size="-1">Copyright &copy; 2016 - Tous droits r&eacute;serv&eacute;s - PNF</font></p>
                </td>
                <td align="right">
                    <p><font size="-2">Conception : <a target="_blank" href="http://www.bamasoft-mali.org/" title="Conception & D&eacute;veloppement" class="footer">BAMASOFT</a></font></p>
                </td>
             </tr>
        </table>
     </td>
  </tr>
</table>
</body>
</html>
