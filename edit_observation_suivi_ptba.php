<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
$path = "../";
include_once $path.'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $path.$config->sys_folder . "/database/db_connexion.php";
////header('Content-Type: text/html; charset=UTF-8');

/*$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}*/

$page = $_SERVER['PHP_SELF'];
if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else $annee=date("Y");
if(isset($_GET["id_act"])){ $id_act=$_GET['id_act'];}
if(isset($_GET['ugl'])) { $ugl = $_GET['ugl']; }

if(isset($_GET["id_act"])) { //$id=$_GET["id"];
$query_edit_obact = "SELECT observation_ptba.observation, observation_ptba.executant, ptba.statut FROM ".$database_connect_prefix."ptba, observation_ptba WHERE id_ptba=ptba and id_ptba='$id_act'";
  try{
    $edit_obact = $pdar_connexion->prepare($query_edit_obact);
    $edit_obact->execute();
    $row_edit_obact = $edit_obact ->fetch();
    $totalRows_edit_obact = $edit_obact->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$executact=$obseract = "";
if($totalRows_edit_obact>0){  
  $executact=$row_edit_obact["executant"];
    $obseract=$row_edit_obact["observation"];
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{
  if ((isset($_POST["MM_update"])) && !empty($_POST["MM_update"])) {
  
    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
    $insertSQL1 = sprintf("UPDATE ".$database_connect_prefix."ptba SET statut=%s WHERE id_ptba='$id_act'",
  					   GetSQLValueString($_POST['statut'], "text"));
  try{
    $Result1 = $pdar_connexion->prepare($insertSQL1);
    $Result1->execute();
	$idactfrits = $db->lastInsertId();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
  if(isset($totalRows_edit_obact) && $totalRows_edit_obact>0) 
{  
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."observation_ptba SET observation=%s, executant=%s WHERE ptba='$id_act'",
  					   GetSQLValueString($_POST['observation'], "text"),
  					   GetSQLValueString($_POST['executant'], "text"));
	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
	$idactfrits = $db->lastInsertId();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
	}
	else
	{
	
	 $insertSQL = sprintf("INSERT INTO observation_ptba  (ptba, observation, executant, id_personnel, date_enregistrement) VALUES (%s, %s, %s, '$personnel', '$date')",

                      
					 GetSQLValueString($id_act, "text"),
					  GetSQLValueString($_POST['observation'], "text"),
  					   GetSQLValueString($_POST['executant'], "text"));
    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
	$idactfrits = $db->lastInsertId();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
	
	}
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    $insertGoTo .= "&id_act=$id_act&annee=$annee";
    header(sprintf("Location: %s", $insertGoTo));
  }
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href='<?php print $path.$config->theme_folder; ?>/css.css' rel='stylesheet' type='text/css'>
<link href="<?php print $path.$config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $path.$config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $path.$config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $path.$config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $path.$config->theme_folder; ?>/login.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php print $path.$config->theme_folder; ?>/fontawesome/font-awesome.min.css">
  <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php print $path.$config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
</head>

<body>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Observations</h4> </div>
<div class="widget-content" style="display: block;">
<form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3" enctype="multipart/form-data">
<table border="0" id="" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="code" class="col-md-9 control-label">Ex&eacute;cutant <span class="required">*</span></label>
          <div class="col-md-11">
          <textarea name="executant" cols="35" rows="2" class="form-control required"><?php if(isset($_GET['id_act'])) echo $executact; else echo "RAS"; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="code" class="col-md-9 control-label">Observation </label>
          <div class="col-md-11">
          <textarea name="observation" cols="35" rows="3" class="form-control "><?php if(isset($_GET['id_act'])) echo $obseract; else echo "RAS"; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td valign="top">
        <div class="form-group">
          <label for="statut" class="col-md-9 control-label">Statut <span class="required">*</span></label>
          <div class="col-md-11">
            <select name="statut" id="statut" class="form-control required" >
              <!--<option value="">Selectionnez</option> -->
              <option value="auto" >G&eacute;r&eacute; par le syst&egrave;me</option>
<!--              <option value="En cours" <?php if(isset($row_edit_obact['statut']) && $row_edit_obact['statut']=="En cours") {echo "SELECTED";} ?>>En cours</option>
              <option value="Non entam&eacute;e" <?php if(isset($row_edit_obact['statut']) && $row_edit_obact['statut']=="Non entamée") {echo "SELECTED";} ?>>Non entam&eacute;e</option>-->
              <option value="Report&eacute;" <?php if(isset($row_edit_obact['statut']) && $row_edit_obact['statut']=="Reporté") {echo "SELECTED";} ?>>Report&eacute;</option>
              <option value="Incertain" <?php if(isset($row_edit_obact['statut']) && $row_edit_obact['statut']=="Incertain") {echo "SELECTED";} ?>>Incertain</option>
              <option value="Annul&eacute;" <?php if(isset($row_edit_obact['statut']) && $row_edit_obact['statut']=="Annulé") {echo "SELECTED";} ?>>Annul&eacute;</option>
            </select>                                  
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id_act"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id_act"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id_act"])) echo $_GET["id_act"]; else echo "MM_insert" ; ?>" size="32" alt="">
<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
        </form>
</div>
  </div>
</body>

</html>