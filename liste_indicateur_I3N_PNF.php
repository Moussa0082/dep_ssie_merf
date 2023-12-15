<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";

 if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Indicateurs_I3N_PNF.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Indicateurs_I3N_PNF.rtf"); } ?>
<?php
 
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind, code_ref_ind FROM referentiel_indicateur";
$liste_ind_ref  = mysql_query($query_liste_ind_ref , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref);
$totalRows_liste_ind_ref  = mysql_num_rows($liste_ind_ref);
$liste_ind_ref_array = array();
$unite_ind_ref_array = array();
do{  $liste_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]] = $row_liste_ind_ref["intitule_ref_ind"]." (<strong>".$row_liste_ind_ref["code_ref_ind"]."</strong>)"; $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];
}while($row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref));

// liste sous programme
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_sous_programme = "SELECT id_sous_programme, intitule_sous_programme, code_sp FROM sous_programme order by code_sp";
$liste_sous_programme  = mysql_query($query_liste_sous_programme , $pdar_connexion) or die(mysql_error());
$row_liste_sous_programme = mysql_fetch_assoc($liste_sous_programme);
$totalRows_liste_sous_programme  = mysql_num_rows($liste_sous_programme);
$liste_sous_programme_array = array();
do{  $liste_sous_programme_array[$row_liste_sous_programme["id_sous_programme"]] = $row_liste_sous_programme["code_sp"].": ".$row_liste_sous_programme["intitule_sous_programme"];
}while($row_liste_sous_programme = mysql_fetch_assoc($liste_sous_programme)); */
?>
<?php
  function getTabLimit($L1=0, $L2=30, $L3=0, $inData, $id = "", $autre = "", $database_pdar_connexion, $pdar_connexion)
  {
    $chearch_name = "";
    $not_first = false;
    $mySqlQuery = "SELECT * FROM  indicateur_i3n, produit_i3n  where id_produit_i3n=produit_i3n";

    if(!empty($autre))
    $mySqlQuery .= " AND ".$autre;
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $qh = mysql_query($mySqlQuery, $pdar_connexion) or die(mysql_error());
    $data = mysql_fetch_assoc($qh);
    $num = mysql_num_rows($qh);
    $retData["tr"] = $num;

    $L2 = (isset($_GET['pm']) && intval($_GET['pm']) > 0)?intval($_GET['pm']):$L2;
    $L1 = (isset($_GET['pn']) && intval($_GET['pn']) > 0)?intval($_GET['pn']):$L1;
    $startRow = $L1 * $L2;
    if(isset($_GET['q']))
    $filtre =($_GET['q']=="*")?"":$_GET['q']; else $filtre="";
    $totalPages = ceil($num/$L2)-1;

    $mySqlQuery = "SELECT * FROM  indicateur_i3n, produit_i3n  where id_produit_i3n=produit_i3n";
    if(!empty($autre))
    $mySqlQuery .= " AND ".$autre;
    if(is_array($inData) && count($inData) > 0 && !empty($filtre))
    {
      $mySqlQuery .= " AND ( ";

      foreach($inData as $a => $b)
      {
        if(!empty($filtre))//if(!empty($b))
        {
          if($not_first == false)
          {
            $mySqlQuery .= sprintf(" $a LIKE %s ",GetSQLValueString("%".$filtre."%", "text"));//$a." LIKE \"%".$filtre."%\" ";
            $not_first = true;
          }
          else
          {
            $mySqlQuery .= sprintf(" OR $a LIKE %s ",GetSQLValueString("%".$filtre."%", "text")); //" OR ".$a." LIKE \"%".$filtre."%\" ";
          }
        }
      }
      $mySqlQuery .= ") ";
    }
    $L3 = ($L2 == 1)?0:$L3;
    $L3 = (isset($_GET['pm']) && intval($_GET['pm']) == 0)?0:$L3;
    $maxRows =  (isset($_GET['pm']) && intval($_GET['pm']) == 0)?1:$L2;
    $totalPages = ($L2 == 1)?0:$totalPages;
    $totalPages = (isset($_GET['pm']) && intval($_GET['pm']) == 0)?0:$totalPages;
    if(!empty($filtre)) $mySqlQuery1 = $mySqlQuery;
    $mySqlQuery .= ($L3 > 0)?" ORDER BY code_produit_i3n, code_indicateur_i3n ASC LIMIT $startRow, $L2":" ORDER BY code_produit_i3n, code_indicateur_i3n ASC";

    if(!empty($filtre))
    {
    $qh = mysql_query($mySqlQuery, $pdar_connexion) or die(mysql_error());
    $data = mysql_fetch_assoc($qh);
    $num = mysql_num_rows($qh);
    $totalPages = ceil($num/$L2)-1;
    }

    $qh = mysql_query($mySqlQuery, $pdar_connexion) or die(mysql_error());
    $data = mysql_fetch_assoc($qh);
    $num = mysql_num_rows($qh);
    $retData["nbr"] = $num;

    $i = 0;
    if($num>0){
      do{
        $retData[$i]["id_produit_i3n"] = $data["id_produit_i3n"];
        $retData[$i]["id_indicateur_i3n"] = $data["id_indicateur_i3n"];
        $retData[$i]["sous_programme"] = $data["sous_programme"];
        $retData[$i]["intitule_produit_i3n"] = $data["intitule_produit_i3n"];
        $retData[$i]["code_produit_i3n"] = $data["code_produit_i3n"];
        $retData[$i]["referentiel"] = $data["referentiel"];
        $retData[$i]["code_indicateur_i3n"] = $data["code_indicateur_i3n"];
        $retData[$i]["intitule_indicateur_i3n"] = $data["intitule_indicateur_i3n"];
        $retData[$i]["description"] = $data["description"];

        $i++;
      } while($data = mysql_fetch_assoc($qh));
    }

    $currentPage = $_SERVER["PHP_SELF"].'?pm='.$L2.'&';
    $currentPage .= (!empty($filtre))?'q='.$filtre.'&':'';
   // $retData["pr"] = getLimitPaginationRight($currentPage,$totalPages,$L2,$L1,$filtre,$chearch_name,1);
    //$retData["pl"] = getLimitPaginationLeft($currentPage,$totalPages,$L2,$L1,$filtre,$chearch_name);

    return $retData;
  }
  $ii=0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php if(!isset($_GET["down"])){  ?>
<head>
  <title><?php print $config->sitename; ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="keywords" content="<?php print $config->MetaKeys; ?>" />
  <meta name="description" content="<?php print $config->MetaDesc; ?>" />
  <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
  <meta name="author" content="<?php print $config->MetaAuthor; ?>" />
  <!--<meta charset="utf-8">-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->
  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/login.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->
  <!--[if IE 8]><link href="<?php print $config->theme_folder; ?>/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->
  <link href='<?php print $config->theme_folder; ?>/css.css' rel='stylesheet' type='text/css'>
  <!--<link rel="stylesheet" href="<?php print $config->theme_folder; ?>/table.css" type="text/css" > -->
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/lodash.compat.min.js"></script>
  <!--[if lt IE 9]><script src="<?php print $config->script_folder; ?>/libs/html5shiv.js"></script><![endif]-->
  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>
  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/breakpoints.js"></script>
  <script type="text/javascript" src="plugins/respond/respond.min.js"></script>
  <script type="text/javascript" src="plugins/cookie/jquery.cookie.min.js"></script>
  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.min.js"></script>
  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>
  <!--[if lt IE 9]><script type="text/javascript" src="plugins/flot/excanvas.min.js"></script><![endif]-->
  <!--<script type="text/javascript" src="plugins/sparkline/jquery.sparkline.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.tooltip.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.resize.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.time.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.growraf.min.js"></script>
  <script type="text/javascript" src="plugins/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
  <script type="text/javascript" src="plugins/daterangepicker/moment.min.js"></script>
  <script type="text/javascript" src="plugins/daterangepicker/daterangepicker.js"></script>-->
  <script type="text/javascript" src="plugins/blockui/jquery.blockUI.min.js"></script>
  <script type="text/javascript" src="plugins/pickadate/picker.js"></script>
  <script type="text/javascript" src="plugins/pickadate/picker.date.js"></script>
  <script type="text/javascript" src="plugins/pickadate/picker.time.js"></script>
  <script type="text/javascript" src="plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
  <script type="text/javascript" src="plugins/fullcalendar/fullcalendar.min.js"></script>
  <script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>
  <script type="text/javascript" src="plugins/noty/layouts/top.js"></script>
  <script type="text/javascript" src="plugins/noty/themes/default.js"></script>
  <script type="text/javascript" src="plugins/uniform/jquery.uniform.min.js"></script>
  <script type="text/javascript" src="plugins/select2/select2.min.js"></script>
  <script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>
  <script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/app.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/plugins.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/plugins.form-components.js"></script>
<!--
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/custom.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/pages_calendar.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_filled_blue.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_simple.js"></script>-->
 <script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
 <script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/login.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/myscript.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/ui_general.js"></script>
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>
</head>
<?php }  ?>
<body>
 <header class="header navbar navbar-fixed-top" role="banner">
    <?php if(!isset($_GET["down"])) include_once("includes/header.php"); ?>
 </header>
<div id="container">
    <div id="sidebar" class="sidebar-fixed">
        <div id="sidebar-content">
            <?php if(!isset($_GET["down"])) include_once("includes/menu_top.php"); ?>
        </div>
        <div id="divider" class="resizeable"></div>
    </div>

    <div id="content">
        <div class="container">
            <div class="crumbs">
                <?php if(!isset($_GET["down"])) include_once("includes/sous_menu.php"); ?>
            </div>
        <div class="page-header">
            <div class="p_top_5">
<!-- Site contenu ici -->
<style>#sp_hr {margin:0px; }
.r_float{float: right;}
.Style1{color: white;}
.titrecorps2 {
	background-color: #D2E2B1;
}

</style>
<div class="contenu">
  <div id="msg" align="center" class="red"></div>
  <?php if(!isset($_GET["down"])){  ?>
  <div class="r_float"><a href="s_parametrage.php" class="button">Retour</a></div>
  <div class="r_float" style="margin-right: 10px;"><a href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
  <div class="r_float" style="margin-right: 10px;"><a href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="clear h0">
<?php } ?></div>
<br />

<table width="100%" border="0" align="center"  cellpadding="0" cellspacing="0" >
  <tr>
    <td><table width="100%" border="1" cellpadding="0" cellspacing="0">
      <thead class="liste_style">
        <tr>
          <th align="left" class="titrecorps2"><strong>Code </strong></th>
          <th align="center" class="titrecorps2"><strong>Indicateur i3N</strong></th>
          <th align="center" class="titrecorps2"><strong>Unit&eacute;</strong></th>
         
          <th align="center" class="titrecorps2"><strong>Référentiel</strong></th>
          
        </tr>
      </thead>
      <tbody class="liste_style">
        <?php
$keyword = array("sous_programme"=>"","code_produit_i3n"=>"","intitule_produit_i3n"=>"","referentiel"=>"","code_indicateur_i3n"=>"","intitule_indicateur_i3n"=>"","description"=>"");
$num = getTabLimit(0, 50, 1, $keyword, "", "", $database_pdar_connexion , $pdar_connexion);
$p1 = "j"; $p11= "j";
if ($num["nbr"] == 0)
{
  echo "<tr class=''><td colspan='7' align='center'><h1>Aucun r&eacute;sultat !</h1></td></tr>";
}
else
{
  for ($i = 0; $i < $num["nbr"]; $i++)
  {
?>
        <?php  if($p11!=$num[$i]['sous_programme']) {/*?>
        <tr bgcolor="#B1CD78">
          <td colspan="6" align="center"><div align="left" class="Style4"><strong>
              <?php  if($p11!=$num[$i]['sous_programme']) {echo $liste_sous_programme_array[$num[$i]['sous_programme']]; }$p11=$num[$i]['sous_programme'];  ?>
          </strong></div></td>
        </tr>
        <?php */} ?>
        <?php if($p1!=$num[$i]['id_produit_i3n']) {?>
        <tr bgcolor="#D2E2B1">
          <td colspan="6" align="center" bgcolor="#D2E2B1"><div align="left" class="Style4"><strong>
              <?php if($p1!=$num[$i]['id_produit_i3n']) {echo $num[$i]['intitule_produit_i3n'];}
                      $p1=$num[$i]['id_produit_i3n']; ?>
          </strong></div></td>
        </tr>
        <?php } ?>
        <tr title="<?php echo $num[$i]['description'];  ?>" <?php if($ii%2==0) echo 'bgcolor="#ECF0DF"'; else echo 'bgcolor="#FFFFFF"';?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='<?php if($ii%2==0) echo '#ECF0DF'; else echo '#FFFFFF'; $ii=$ii+1 ?>';">
          <td align="center" <?php echo (!isset($liste_ind_ref_array[$num[$i]["referentiel"]]))?'style="color:#FF0000"':''; ?>><div align="left"> <?php echo $num[$i]['code_indicateur_i3n']; ?></div></td>
          <td><div align="left">&nbsp;&nbsp;<?php echo $num[$i]['intitule_indicateur_i3n']; ?></div></td>
          <td align="center"><div align="center">&nbsp;&nbsp;
                <?php if(isset($unite_ind_ref_array[$num[$i]["referentiel"]])) echo $unite_ind_ref_array[$num[$i]["referentiel"]]; ?>
          </div></td>
        
          <td><?php if(isset($liste_ind_ref_array[$num[$i]["referentiel"]])) echo $liste_ind_ref_array[$num[$i]["referentiel"]]; ?></td>
         
        </tr>
        <?php }
} ?>
      </tbody>
      <tfoot>
       
      </tfoot>
    </table></td>
  </tr>
</table>
<!-- Fin Site contenu ici -->

            </div>

        </div>



        </div>

    </div>

    <?php include_once ("includes/footer.php");?>

</div>

</body>

</html>