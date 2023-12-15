<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"])) {
//header(sprintf("Location: %s", "./"));
  exit;
}include_once $config->sys_folder . "/database/db_connexion.php";
header('Content-Type: text/html; charset=UTF-8');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset ($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}if (isset ($_GET["annee"])) {
  $annee = $_GET['annee'];
  $rec = $_GET['rec'];
  $idms = $_GET['idms'];
}$page = $_SERVER['PHP_SELF'];
//insertion
if ((isset ($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $date = date("Y-m-d");
  $personnel = $_SESSION['clp_id'];

  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."suivi_recommandation_mission (recommandation, date_execution, statut, observation, perspective, moyen_verification, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s,'$personnel', '$date')", GetSQLValueString($rec, "text"), GetSQLValueString(implode('-', array_reverse(explode('-', $_POST['date_execution']))), "date"), GetSQLValueString($_POST['statut'], "text"), GetSQLValueString($_POST['observation'], "text"), GetSQLValueString($_POST['perspective'], "text"), GetSQLValueString($_POST['moyen_verification'], "text"));
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
//script_sql
  $SQL = $insertSQL;
  $tb = $database_connect_prefix."suivi_recommandation_mission";
  include ("../script_sql.php");
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1)
    $insertGoTo .= "?rec=$rec&idms=$idms&annee=$annee&insert=ok";
  else
    $insertGoTo .= "?rec=$rec&idms=$idms&annee=$annee&insert=no";
  header(sprintf("Location: %s", $insertGoTo));
}if ((isset ($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $date = date("Y-m-d");
  $personnel = $_SESSION['clp_id'];
  $cd = $_GET['idd'];
  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."suivi_recommandation_mission SET  date_execution=%s, statut=%s, observation=%s, perspective=%s, moyen_verification=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_suivi_recommandation='$cd'", GetSQLValueString(implode('-', array_reverse(explode('-', $_POST['date_execution']))), "date"), GetSQLValueString($_POST['statut'], "text"), GetSQLValueString($_POST['observation'], "text"), GetSQLValueString($_POST['perspective'], "text"), GetSQLValueString($_POST['moyen_verification'], "text"));
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
//script_sql
  $SQL = $insertSQL;
  $tb = $database_connect_prefix."suivi_recommandation_mission";
  include ("../script_sql.php");
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1)
    $insertGoTo .= "?rec=$rec&idms=$idms&annee=$annee&update=ok";
  else
    $insertGoTo .= "?rec=$rec&idms=$idms&annee=$annee&update=no";
  header(sprintf("Location: %s", $insertGoTo));
}if (isset ($_GET["cl"])) {
  $insertGoTo = "../new_mission_supervision.php?rec=$rec&idms=$idms&annee=$annee";
  ?>


  <script type="text/javascript">
  parent.location.href = "<?php echo $insertGoTo;?>";
  </script>
    <?php
    exit (0);
  }
  if (isset ($_GET["idd"])) {
    $cd = $_GET["idd"];
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $query_edit_rec = "SELECT * FROM ".$database_connect_prefix."suivi_recommandation_mission WHERE id_suivi_recommandation='$cd'";
    $edit_rec = mysql_query($query_edit_rec, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $row_edit_rec = mysql_fetch_assoc($edit_rec);
    $totalRows_edit_rec = mysql_num_rows($edit_rec);
  }
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_edit_recm = "SELECT * FROM ".$database_connect_prefix."recommandation_mission, ".$database_connect_prefix."mission_supervision WHERE code_ms=mission and  numero='$rec'";
  $edit_recm = mysql_query($query_edit_recm, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_edit_recm = mysql_fetch_assoc($edit_recm);
  $totalRows_edit_recm = mysql_num_rows($edit_recm);
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_rec = "SELECT id_suivi_recommandation, statut, perspective, moyen_verification, date_execution, observation FROM ".$database_connect_prefix."suivi_recommandation_mission where recommandation='$rec' ORDER BY id_suivi_recommandation desc";
  $liste_rec = mysql_query($query_liste_rec, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_rec = mysql_fetch_assoc($liste_rec);
  $totalRows_liste_rec = mysql_num_rows($liste_rec);
  if (isset ($_GET["id_supd"])) {
    $idd = $_GET["id_supd"];
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $query_sup_rec = "DELETE FROM ".$database_connect_prefix."suivi_recommandation_mission WHERE id_suivi_recommandation='$idd'";
    $Result1 = mysql_query($query_sup_rec, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  //script_sql
    $SQL = $query_sup_rec;
    $tb = $database_connect_prefix."suivi_recommandation_mission";
    include ("../script_sql.php");
    $insertGoTo = (isset ($_GET['page'])) ? $_GET['page'] : $_SERVER['PHP_SELF'];
    if ($Result1)
      $insertGoTo .= "?rec=$rec&idms=$idms&annee=$annee&del=ok";
    else
      $insertGoTo .= "?rec=$rec&idms=$idms&annee=$annee&del=no";
    header(sprintf("Location: %s", $insertGoTo));
  }
  ?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->
  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/login.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.date.js"></script>
<script>$(document).ready(function(){Plugins.init();FormComponents.init()});</script>
<script type="text/javascript">
$(".modal-dialog", window.parent.document).width(800);
</script>
</head>
<body>
<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i> Suivi des recommandations</h4>
<div class="toolbar no-padding"><?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) {?>
<a href="<?php echo $_SERVER['PHP_SELF']."?rec=$rec&annee=".$annee."&idms=$idms&show=1"; ?>" title="Ajout de recommandation" class="pull-right p11"><i class="icon-plus"> Ajouter </i></a><?php } ?>
</div></div>

<div class="widget-content" style="display: block;">

    <table border="0" align="center" cellspacing="0" cellpadding="0" width="100%" >


      <tr>


        <td valign="top"><div align="left"><span class="Style2"><strong><strong>


          <u>Mission</u>: <?php if (isset ($row_edit_recm['debut'])) echo "du " . implode('-', array_reverse(explode('-', $row_edit_recm['debut']))) . " au " . implode('-', array_reverse(explode('-', $row_edit_recm['fin'])));?>


        </strong><br /> <u>Recommandation</u>:


                  <span class="Style14">


                  <?php if (isset ($row_edit_recm['recommandation'])) echo $row_edit_recm['recommandation'];?>


                  </span>            <strong><br />


            <u>Date buttoir</u>:


            <?php if (isset ($row_edit_recm['type']) && $row_edit_recm['type'] == "Continu") echo "Continu";else echo date("d/m/y", strtotime($row_edit_recm['date_buttoir']));?>


            <strong><strong>


            Responsable:


            <strong>


            <?php if (isset ($row_edit_recm['responsable'])) echo $row_edit_recm['responsable'];?>


            </strong>            </strong></strong> </strong> </strong></span>


          </div>          <div align="center">


          </div></td>


        </tr> </table>

<?php if(isset($_GET["show"])){ ?>

		<?php if (($_SESSION['clp_niveau'] == 1) && ($_SESSION['clp_niveau'] < 3)) {?>


          <table  border="0" align="center" cellspacing="0">


            <tr>


              <td><div align="center">


                  <form action="<?php echo $editFormAction;?>" method="post" name="form2" id="form2" onSubmit="return verifform(this,4);">


                    <div id="special">


                      <p>


                        <?php if (isset ($_GET['idd'])) echo "Modifier le suivi de recommandation ";else echo "Nouveau suivi de recommandation";?>


                      </p>





                      <table align="center">


                        <tr valign="baseline">


                          <td align="right"><strong><span class="Style2">Date de suivi</span> &nbsp;&nbsp;</strong></td>


                          <td colspan="4" align="right"><div align="left">


                              <input type="text" name="date_execution" value="<?php if (isset ($_GET['idd'])) echo implode('-', array_reverse(explode('-', $row_edit_rec['date_execution'])));else echo date("d-m-Y");?>" size="15" />


                              <span class="Style1">Statut &nbsp;&nbsp;</span><span class="Style2"><strong><strong>


                              <select name="statut">


                                <option value="Réalisé"<?php if (isset ($_GET['idd'])) {if (!(strcmp("Réalisé", $row_edit_rec['statut']))) {echo "SELECTED";}}?>>Réalisé</option>
                                <option value="En cours"<?php if (isset ($_GET['idd'])) {if (!(strcmp("En cours", $row_edit_rec['statut']))) {echo "SELECTED";}}?>>En cours</option>
                     <option value="Non réalisé"<?php if (isset ($_GET['idd'])) {if (!(strcmp("Non réalisé", $row_edit_rec['statut']))) {echo "SELECTED";}}?>>Non réalisé</option>
                                <option value="Non échu"<?php if (isset ($_GET['idd'])) {if (!(strcmp("Non échu", $row_edit_rec['statut']))) {echo "SELECTED";}}?>>Non échu</option>


                              </select>


                          </strong></strong></span></div></td>


                        </tr>





                        <tr valign="baseline">


                          <td align="right" valign="top" ><span class="Style1">Etat actuel &nbsp;&nbsp;</span></td>


                          <td colspan="4" align="right" valign="top" ><div align="left">


                            <textarea name="observation" cols="40" rows="2"><?php if (isset ($_GET['idd'])) echo $row_edit_rec['observation'];?></textarea>


                          </div></td>


                        </tr>


                        <tr valign="baseline">


                          <td align="right" valign="top" ><span class="Style1">Perspectives&nbsp;&nbsp;</span></td>


                          <td colspan="4" align="right" valign="top" ><div align="left">


                            <textarea name="perspective" cols="40" rows="2"><?php if (isset ($_GET['idd'])) echo $row_edit_rec['perspective'];?></textarea>


                          </div></td>


                        </tr>


                        <tr valign="baseline">


                          <td align="right" valign="top" ><span class="Style1">Moyen de v&eacute;rification &nbsp;&nbsp;</span></td>


                          <td colspan="4" align="right" valign="top" >


                              <div align="left">


                                <input name="moyen_verification" type="text" value="<?php if (isset ($_GET['idd'])) echo $row_edit_rec['moyen_verification'];?>" size="32" />


                              </div></td>


                        </tr>


                        <tr valign="baseline">


                          <td  align="right">&nbsp;</td>


                          <td  align="right">


                            <div align="left">

                              <a title="Annuler la modification" href="<?php echo $_SERVER['PHP_SELF']."?rec=$rec&annee=".$annee."&idms=$idms"; ?> " class="btn btn-default pull-right">Annuler  </a>
                              <b class=" pull-right" style="width: 10px;">&nbsp;</b>
                              <input type="submit" class="btn btn-success pull-right" value="<?php if (isset ($_GET['idd'])) echo "Modifier";else echo "Enregistrer";?>" />


                            </div></td>


                          <td colspan="3" align="right" ><div align="left"> </div>


                              <div align="left"> </div></td>


                        </tr>


                      </table>


                      <input type="hidden" name="<?php if (isset ($_GET['idd'])) echo "MM_update";else echo "MM_insert";?>" value="form2" />


                      <input type="hidden" name="annee" value="<?php if (isset ($annee)) {echo $annee;}?>" size="32" />


                    </div>


                  </form>


              </div></td>


            </tr>


          </table>


		  <?php }?>



<?php  }else{ ?>

<table style="border-collapse: collapse;" class="table table-striped table-bordered table-hover table-responsive datatable dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">

<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Date de suivi</th>
<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Statut</th>
<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Moyen de v&eacute;rification</th>
<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Etat actuel</th>
<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Perspectives</th>

<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="60">Edit</th>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="60">Suppr.</th>
</tr>
</thead>

<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
            <?php $t = 0;if ($totalRows_liste_rec > 0) {$p1 = "j";$t = 0;$i = 0;do {?>

<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">

              <td <?php if (isset ($row_liste_rec['statut']) && $row_liste_rec['statut'] != "Mise en oeuvre") {if ($row_edit_recm['date_buttoir'] < $row_liste_rec['date_execution'] && $row_edit_recm['type'] != "Continu") {echo 'bgcolor="#FF0000"';}}?> ><div align="center"><?php echo date("d/m/y", strtotime($row_liste_rec['date_execution']));?></div></td>


              <td><?php echo $row_liste_rec['statut'];?></td>


              <td><?php echo $row_liste_rec['moyen_verification'];?></td>


              <td><?php echo $row_liste_rec['observation'];?></td>


              <td><?php echo $row_liste_rec['perspective'];?></td>


             <?php if (($_SESSION['clp_niveau'] == 1) && ($_SESSION['clp_niveau'] < 3)) {?>


			  <td>


                <div align="center">


                  <?php echo "<a href=" . $_SERVER['PHP_SELF'] . "?rec=$rec&idms=$idms&annee=$annee&show=1&idd=" . $row_liste_rec['id_suivi_recommandation'] . "><img src='images/edit.png' width='20' height='20' alt='Modifier' /></a>" ?>


                  </div>


              </td>


              <td><a href="<?php echo $_SERVER['PHP_SELF'] . "?rec=$rec&idms=$idms&annee=$annee&id_supd=" . $row_liste_rec['id_suivi_recommandation'] . "" ?>" class="Style2" onClick="return confirm('Voulez-vous vraiment supprimer le suivi du: <?php echo $row_liste_rec['date_execution'];?> ?');"><img src="images/delete.png" width="15" border="0"/></a></td>


           <?php }?>

		    </tr>
            <?php } while ($row_liste_rec = mysql_fetch_assoc($liste_rec));?>

            <?php }else echo "<tr><td colspan='7' align='center'>Aucune donn&eacute;e!</td></tr>"?>

</tbody></table>

<?php } ?>
    </div>      </div>

</body>
</html>