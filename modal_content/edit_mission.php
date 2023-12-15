<?php

session_start();



require_once('../Connections/pdar_connexion.php');

//include_once $path_racine."configurations.php";

//$config = new MSIConfig();

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 

{

  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;



  switch ($theType) {

    case "text":

      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";

      break;    

    case "long":

    case "int":

      $theValue = ($theValue != "") ? intval($theValue) : "NULL";

      break;

    case "double":

      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";

      break;

    case "date":

      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";

      break;

    case "defined":

      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;

      break;

  }

  return $theValue;

}



$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {

  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);

}





$page = $_SERVER['PHP_SELF'];

if(isset($_GET['annee'])) $annee=$_GET['annee'];

//insertion

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $id=$_POST['idc'];

  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."mission_supervision (id_mission, debut, fin, type, observation, etat, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, '$personnel', '$date')",

                       GetSQLValueString($_POST['idc'], "int"),

					   GetSQLValueString(implode('-',array_reverse(explode('-',$_POST['debut']))), "date"),

					   GetSQLValueString(implode('-',array_reverse(explode('-',$_POST['fin']))), "date"),

                       GetSQLValueString($_POST['type'], "text"),

		               GetSQLValueString($_POST['observation'], "text"),

					   GetSQLValueString($_POST['resume'], "text"));



  mysql_select_db($database_pdar_connexion, $pdar_connexion);

  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    $annee=$_POST['annee'];

    $insertGoTo = "../new_mission_supervision.php";

  if ($Result1) $insertGoTo .= "?insert=ok&annee=$annee"; else $insertGoTo .= "?insert=no&annee=$annee";

    ?>

  <script type="text/javascript">

  parent.location.href = "<?php echo $insertGoTo; ?>";

  </script>

  <?php exit(0);

}



if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_GET['id'];

  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."mission_supervision SET type=%s, debut=%s, fin=%s, observation=%s, etat=%s, modifier_par='$personnel', modifier_le='$date' WHERE id_mission='$c'",

                       GetSQLValueString($_POST['type'], "text"),

					   GetSQLValueString(implode('-',array_reverse(explode('-',$_POST['debut']))), "date"),

					   GetSQLValueString(implode('-',array_reverse(explode('-',$_POST['fin']))), "date"),

                       GetSQLValueString($_POST['observation'], "text"),

					   GetSQLValueString($_POST['resume'], "text"));

					   



  mysql_select_db($database_pdar_connexion, $pdar_connexion);

  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

  $annee=$_POST['annee'];

    $insertGoTo = "../new_mission_supervision.php";

  if ($Result1) $insertGoTo .= "?insert=ok&annee=$annee"; else $insertGoTo .= "?insert=no&annee=$annee";

    ?>

  <script type="text/javascript">

  parent.location.href = "<?php echo $insertGoTo; ?>";

  </script>

  <?php exit(0);

}







if(isset($_GET["cl"])){ 

 $insertGoTo = "../new_mission_supervision.php?annee=$annee";

 ?>

  <script type="text/javascript">



  parent.location.href = "<?php echo $insertGoTo; ?>";



  </script>

  <?php exit(0);

}



if(isset($_GET["id"])) { $id=$_GET["id"];

mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_edit_ms = "SELECT * FROM ".$database_connect_prefix."mission_supervision WHERE id_mission='$id'";

$edit_ms = mysql_query($query_edit_ms, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_edit_ms = mysql_fetch_assoc($edit_ms);

$totalRows_edit_ms = mysql_num_rows($edit_ms);

}



mysql_select_db($database_pdar_connexion, $pdar_connexion);

$mois=date("m");

$an=date("y");

$query_max_id = "SELECT max(mission_supervision.id_mission) as max ".$database_connect_prefix."FROM mission_supervision";

$max_id = mysql_query($query_max_id, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_max_id = mysql_fetch_assoc($max_id);

$totalRows_max_id = mysql_num_rows($max_id);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"

    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">



<head>

  <title><?php echo $config->site_name; ?></title>

  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<link rel="stylesheet" href="../css/cbcscbindex.css" type="text/css" >

<link rel="stylesheet" href="../css/css.css" type="text/css" >

<script type="text/javascript" src="../script/function.php"></script>

<script src="../script/jquery-latest.js" type="text/javascript"></script>

<script type="text/javascript" src="../script/iepngfix_tilebg.js"></script>

  <style type="text/css">

<!--

.Style1 {	font-size: 12px;

	font-weight: bold;

}

.Style2 {font-size: 12px}

.Style17 {

	font-size: 10px;

	font-style: italic;

}

.Style18 {color: #FF0000}

-->

  </style>

</head>



<body>

  <div id="corps">



<div id="msg" align="center" class="red"></div>



<div id="special">

  <div id="msg1" align="center" class="red"></div>

  <div align="center">

    <table border="0" align="center" cellspacing="5" cellpadding="0" width="100%" >

      <tr>

        <td><div align="center">

            <table  border="0" align="center" cellspacing="0">

              <tr>

                <td><div align="center">

                    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onSubmit="return verifform(this,1);">

                      <div id="special">

                        <p>

                          <?php if(isset($_GET['id'])) echo "Modifier la Mission ou le CP"; else echo "Ajouter une mission ou un comit&eacute; de pilotage" ; ?>

                        </p>

                        <table align="center">

                          <tr valign="baseline">

                            <td colspan="2" align="right" nowrap="nowrap" bgcolor="#CCFFFF"><span class="Style2"><strong>Type</strong></span></td>

                            <td align="right" nowrap="nowrap" bgcolor="#CCFFFF"><div align="left"><strong>

                                <select name="type">

                                    <option value="">-- Choisissez --</option>

                                    <option value="Supervision" <?php if(isset($_GET['id']) && $row_edit_ms['type']=="Supervision") echo 'selected="selected"'; ?>>Supervision</option>

                                    <option value="Comit&eacute; de pilotage" <?php if(isset($_GET['id']) && $row_edit_ms['type']=="Comit&eacute; de pilotage") echo 'selected="selected"'; ?>>Comit&eacute; de pilotage</option>

                                    <option value="Appui ponctuel" <?php if(isset($_GET['id']) && $row_edit_ms['type']=="Appui ponctuel") echo 'selected="selected"'; ?>>Appui ponctuel</option>

                                    <option value="Suivi ministeriel" <?php if(isset($_GET['id']) && $row_edit_ms['type']=="Suivi ministeriel") echo 'selected="selected"'; ?>>Suivi ministeriel</option>

                                    <option value="Audit" <?php if(isset($_GET['id']) && $row_edit_ms['type']=="Audit") echo 'selected="selected"'; ?>>Audit</option>

                                </select>

                            </strong></div></td>

                            </tr>

                          <tr valign="baseline">

                            <td rowspan="2" align="right" valign="middle" nowrap="nowrap" bgcolor="#CCFFFF"><span class="Style2"><strong>P&eacute;riode</strong></span></td>

                            <td align="right" nowrap="nowrap" bgcolor="#CCFFFF"><span class="Style2"><strong>D&eacute;but</strong></span></td>

                            <td align="right" nowrap="nowrap" bgcolor="#CCFFFF"><div align="left">

                              <input type="text" name="debut" value="<?php if(isset($_GET['id'])) echo implode('-',array_reverse(explode('-',$row_edit_ms['debut'])));  else echo date("d-m-Y"); ?>" size="10" />

                            </div></td>

                            </tr>

                          <tr valign="baseline">

                            <td align="right" nowrap="nowrap" bgcolor="#CCFFFF"><span class="Style2"><strong>Fin</strong></span></td>

                            <td align="right" nowrap="nowrap" bgcolor="#CCFFFF"><div align="left">

                              <input type="text" name="fin" value="<?php if(isset($_GET['id'])) echo implode('-',array_reverse(explode('-',$row_edit_ms['fin'])));  else echo date("d-m-Y"); ?>" size="10" />

                            </div></td>

                            </tr>

                          <tr valign="baseline">

                            <td colspan="2" align="right" valign="top" nowrap="nowrap" bgcolor="#CCFFFF"><span class="Style2"><strong>Objet</strong></span></td>

                            <td align="right" nowrap="nowrap" bgcolor="#CCFFFF"><div align="left">

                              <textarea name="observation" cols="30" rows="2" style="background-color:#CCCCCC " <?php if(isset($_GET['id'])) {?><?php }?>><?php if(isset($_GET['id'])) echo $row_edit_ms['observation'];?></textarea>

                            </div></td>

                            </tr>

                          <tr valign="baseline">

                            <td colspan="2" align="right" valign="top" nowrap="nowrap" bgcolor="#CCFFFF"><div align="right"><span class="Style2"><strong>Resum&eacute;</strong></span></div></td>

                            <td align="right" nowrap="nowrap" bgcolor="#CCFFFF"><div align="center"><span class="Style2"></span></div>

                                <div align="center"><span class="Style2">

                                  <textarea name="resume" cols="30" rows="2" style="background-color:#CCCCCC " <?php if(isset($_get['id'])) {?><?php }?>="<?php if(isset($_GET['id'])) {?><?php }?>"><?php if(isset($_GET['id'])) echo $row_edit_ms['etat']; else echo "RAS";?></textarea>

                                </span></div></td>

                            </tr>

                          <tr valign="baseline">

                            <td colspan="2" align="right" nowrap="nowrap"><span class="Style2"></span>                              <div align="left">                            </div></td>

                            <td align="right" nowrap="nowrap">

                              <div align="left">

                                <input type="submit" class="inputsubmit" value="<?php if(isset($_GET['id'])) echo "Modifier"; else echo "Enregistrer" ; ?>" />

                              </div></td>

                            </tr>

                        </table>

                        <input type="hidden" name="<?php if(isset($_GET['id'])) echo "MM_update"; else echo "MM_insert";  ?>" value="form1" />

                        <input type="hidden" name="idc" value="<?php if(isset($row_max_id['max'])) {echo 1+$row_max_id['max'];} else {echo 1;} ?>" size="32" />

                        <input type="hidden" name="annee" value="<?php if(isset($annee)) {echo $annee;} ?>" size="32" />

</div>

                    </form>

                </div></td>

              </tr>

            </table>

        </div></td>

      </tr>

      <tr>

                            <td align="center" nowrap="nowrap"><div align="center">
                              <input name="Submit" type="reset" class="inputsubmit" value="Quitter" onClick="parent.tb_remove();" />
                            </div></td>

      </tr>



      <tr>

        <td><hr /></td>

      </tr>



    </table>

  </div>

</div>

  </div>

  </br>

<?php if(isset($_GET['insert'])){ ?>

<script type="text/javascript">

afficher_msg('insert','<?php echo $_GET['insert']; ?>');

</script>

<?php }?>



<?php if(isset($_GET['update'])){ ?>

<script type="text/javascript">

afficher_msg('update','<?php echo $_GET['update']; ?>');

</script>

<?php }?>



<?php if(isset($_GET['del'])){ ?>

<script type="text/javascript">

afficher_msg('del','<?php echo $_GET['del']; ?>');

</script>

<?php }?>



<?php if(isset($_GET['statut'])){ ?>

<script type="text/javascript">

afficher_msg('statut','<?php echo $_GET['statut']; ?>');

</script>

<?php }?>

</body>



</html>