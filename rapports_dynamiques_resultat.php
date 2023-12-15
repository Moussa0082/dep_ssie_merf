<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"]) || !isset($_GET['id_conf']) || !isset($_GET['feuille'])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";

$editFormAction1 = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction1 .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$table_join = $lescolonneafficher = array();  $show_colonne="";

if(isset($_GET['id_conf']) && intval($_GET['id_conf']>0)){  $id_conf=intval($_GET['id_conf']);
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_conf = "SELECT * FROM ".$database_connect_prefix."rapport_fiche_config WHERE id=$id_conf ";
$liste_conf = mysql_query($query_liste_conf, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_conf = mysql_fetch_assoc($liste_conf);
$totalRows_liste_conf = mysql_num_rows($liste_conf);
$classeur=$row_liste_conf["classeur"];
$temp=explode("/",$row_liste_conf["colonne"]);
$row_liste_conf["colonne"]=$temp[0]; if(isset($temp[1])){ $table_join[$temp[1]]=$temp[1]; $colonneTab=$temp[1]; }
$temp=explode("/",$row_liste_conf["colonneV"]);
$row_liste_conf["colonneV"]=$temp[0]; if(isset($temp[1])){ $table_join[$temp[1]]=$temp[1]; $colonneVTab=$temp[1]; }
$temp0=explode(";",$row_liste_conf["show_colonne"]);
foreach($temp0 as $t){ $temp=explode("/",$t); if(isset($temp[1])) $table_join[$temp[1]]=$temp[1]; $show_colonne.="'".$temp[0]."',";  }
$show_colonne=substr($show_colonne,0,strlen($show_colonne)-1);

//Ajout des autres colonnes à afficher

$temp=explode("/",$row_liste_conf["colonne"]);
$lescolonneafficher[$temp[0]]=$temp[0];

$temp=explode("/",$row_liste_conf["colonneV"]);
$lescolonneafficher[$temp[0]]=$temp[0];

$temp=explode("/",$row_liste_conf["show_colonne"]);
foreach($temp0 as $t){ $temp=explode("/",$t); if(isset($temp[0])) $lescolonneafficher[$temp[0]]=$temp[0];  }

//unité
if(isset($colonneVTab)){
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_unite = "SELECT unite FROM ".$database_connect_prefix."referentiel_fiche_config, referentiel_indicateur WHERE feuille='$colonneVTab' and classeur=$classeur and referentiel=id_ref_ind ";
$liste_unite = mysql_query($query_liste_unite, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_unite = mysql_fetch_assoc($liste_unite);
$totalRows_liste_unite = mysql_num_rows($liste_unite);
if(isset($row_liste_unite["unite"])) $unite = $row_liste_unite["unite"];  }
}



if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Rapports_Dynamiques.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Rapports_Dynamiques.doc"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="p"){

require_once('./tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$PDF_HEADER_TITLE = "Rapports Dynamiques";
$PDF_HEADER_STRING = ((isset($row_liste_conf["intitule"]))?$row_liste_conf["intitule"]:"Rapports Dynamiques");

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Ruche');
$pdf->SetTitle($PDF_HEADER_TITLE);
$pdf->SetSubject($PDF_HEADER_STRING);
$pdf->SetKeywords('PDF, Rapports Dynamiques');

// set default header data //PDF_HEADER_LOGO
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE, $PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}
// set font
//$pdf->SetFont('dejavusans', '', 10);
$pdf->AddPage();

  ob_start(); // turn on output buffering
  /*$_GET["id"]="0001";
  $_GET["down"]=5; */
  include("./rapports_dynamiques_resultat_pdf.php");
  $content = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering

$html = utf8_encode($content);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('rapports_dynamiques.pdf', 'D');

exit;

 }
header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET['annee']) && intval($_GET['annee'])>0) {$annee=intval($_GET['annee']);} else {$annee=date("Y");}
//if(isset($_GET['id'])) {$id=$_GET['idd'];}
//if(isset($_GET['feuille'])) {$feuille=$_GET['feuille'];}
//$cp=$feuille;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SHOW tables";
$liste_cp = mysql_query($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);

$cp_array=array();
if($totalRows_liste_cp>0) {
do {  if(strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="fiche_config"){  $cp_array[]=$row_liste_cp["Tables_in_$database_pdar_connexion"];
}
} while ($row_liste_cp = mysql_fetch_assoc($liste_cp));
$rows = mysql_num_rows($liste_cp);
if($rows > 0) {
mysql_data_seek($liste_cp, 0);
$row_liste_cp = mysql_fetch_assoc($liste_cp);
}}

//if(isset($cp) && !in_array($cp,$cp_array)) unset($cp);

$data_validate_array = array();

 foreach($table_join as $cp){
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_validation = "SELECT * FROM ".$database_connect_prefix."validation_fiche WHERE projet='".$_SESSION["clp_projet"]."' and nom_fiche='$cp'";
  $validation  = mysql_query_ruche($query_validation , $pdar_connexion) or die(mysql_error());
  $row_validation  = mysql_fetch_assoc($validation);
  $totalRows_validation  = mysql_num_rows($validation);

  if($totalRows_validation>0){ do{ $data_validate_array[] = $row_validation["id_lkey"]; }while($row_validation  = mysql_fetch_assoc($validation));  }      }
$data="";
if(count($data_validate_array)>0){  foreach($data_validate_array as $data1) $data .= "'".$data1."',"; }  $data=substr($data,0,strlen($data)-1);

$entete_array = $libelle = array();
$groupe_by=$where_in=$where_not_in="";   $operation="";
if(isset($row_liste_conf["colonne"]) && !empty($row_liste_conf["colonne"])){
$groupe_by=" GROUP BY ".$colonneTab.".".$row_liste_conf["colonne"];
}
if(isset($row_liste_conf["critere_in"]) && !empty($row_liste_conf["critere_in"])){
$temp=explode(";",$row_liste_conf["critere_in"]);
$temp1="";
foreach($temp as $tem0) $temp1.=GetSQLValueString($tem0, "text").",";
$temp1=substr($temp1,0,strlen($temp1)-1);
$where_in=" AND ".$colonneVTab.".".$row_liste_conf["colonne"]." IN(".$temp1.") ";
}
if(isset($row_liste_conf["critere_not_in"]) && !empty($row_liste_conf["critere_not_in"])){
$temp=explode(";",$row_liste_conf["critere_not_in"]);
$temp1="";
foreach($temp as $tem0) $temp1.=GetSQLValueString($tem0, "text").",";
$temp1=substr($temp1,0,strlen($temp1)-1);
$where_not_in=" AND ".$colonneVTab.".".$row_liste_conf["colonne"]." NOT IN(".$temp1.") ";
}

if(isset($row_liste_conf["colonneV"]) && !empty($row_liste_conf["colonneV"])){
if($row_liste_conf["mode"]=="somme"){
    if($row_liste_conf["colonne"]==$row_liste_conf["colonneV"]){
    $operation=" SUM(".$colonneVTab.".".$row_liste_conf["colonneV"].") as ".$row_liste_conf["colonneV"]."ruche "; $lescolonneafficher[$row_liste_conf["colonneV"]."ruche"]=$row_liste_conf["colonneV"]."ruche"; }
    else
    $operation=" SUM(".$row_liste_conf["colonneV"].") as ".$row_liste_conf["colonneV"]." "; }
elseif($row_liste_conf["mode"]=="moyenne"){
    if($row_liste_conf["colonne"]==$row_liste_conf["colonneV"]){
    $operation=" AVG(".$colonneVTab.".".$row_liste_conf["colonneV"].") as ".$row_liste_conf["colonneV"]."ruche "; $lescolonneafficher[$row_liste_conf["colonneV"]."ruche"]=$row_liste_conf["colonneV"]."ruche"; }
    else
    $operation=" AVG(".$colonneVTab.".".$row_liste_conf["colonneV"].") as ".$row_liste_conf["colonneV"]." "; }
elseif($row_liste_conf["mode"]=="compter"){
    if($row_liste_conf["colonne"]==$row_liste_conf["colonneV"]){
    $operation=" COUNT(".$colonneVTab.".".$row_liste_conf["colonneV"].") as ".$row_liste_conf["colonneV"]."ruche "; if(!empty($row_liste_feuille["show_colonne"])) $lescolonneafficher["Nombre ".$row_liste_conf["colonneV"]]=$row_liste_conf["colonneV"]."ruche"; }
    else{
    $operation=" COUNT(".$colonneVTab.".".$row_liste_conf["colonneV"].") as ".$row_liste_conf["colonneV"]."ruche "; $lescolonneafficher["Nombre ".$row_liste_conf["colonneV"]]=$row_liste_conf["colonneV"]."ruche";} }
else{
    if($row_liste_conf["colonne"]==$row_liste_conf["colonneV"]){
    $operation=" SUM(".$colonneVTab.".".$row_liste_conf["colonneV"].") as ".$row_liste_conf["colonneV"]."ruche "; $lescolonneafficher[$row_liste_conf["colonneV"]."ruche"]=$row_liste_conf["colonneV"]."ruche"; }
    else
    $operation=" SUM(".$colonneVTab.".".$row_liste_conf["colonneV"].") as ".$row_liste_conf["colonneV"]." ";
 }
}

$deb=$from=$jointure=""; $whereLKEY=" AND ("; $i=0;
foreach($table_join as $table){  if($i==0) $cp=$table;  $deb.=$table.".*,"; $from.=$table.","; $i++;
//gestion des jointure
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table` = '$table' and choix LIKE '%".substr($table,0,strpos($table,strrchr($table,"_")))."%'";
$entete  = mysql_query($query_entete , $pdar_connexion) ; //or die(mysql_error())
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);
if($totalRows_entete>0){ do{ $ch=$row_entete["choix"]; $tb=$row_entete["table"];
$ch1=explode("|",$ch);
foreach($ch1 as $ch2){ $ch3=explode(";",$ch2);
if(isset($ch3[0]) && isset($ch3[1]) && isset($ch3[2]) && in_array($ch3[2],$cp_array)) $jointure.=" AND ".$tb.".".$ch3[0]."=".$ch3[2].".".$ch3[1];    }
 }while($row_entete  = mysql_fetch_assoc($entete));  }

if(!empty($data)){ $whereLKEY.=" $table.LKEY IN($data) OR "; }
 }
if(!empty($data)) $whereLKEY=substr($whereLKEY,0,strlen($whereLKEY)-4).")";
else $whereLKEY="";
$deb=substr($deb,0,strlen($deb)-1);
$from=substr($from,0,strlen($from)-1);

if(isset($_SESSION["clp_structure"]) && !empty($_SESSION["clp_structure"])){
$etoile = $deb.",".$operation;
if(substr($etoile,strlen($etoile)-1)==",") $etoile=substr($etoile,0,strlen($etoile)-1);
mysql_select_db($database_pdar_connexion, $pdar_connexion);
if($row_liste_conf["colonne"]!="annee" && $row_liste_conf["colonneV"]!="annee")
$query_act = "SELECT $etoile FROM $from WHERE $cp.annee=$annee and $cp.structure='".$_SESSION["clp_structure"]."' and $cp.projet='".$_SESSION["clp_projet"]."' $where_in $where_not_in $jointure $whereLKEY $groupe_by";
else
$query_act = "SELECT $etoile FROM $from WHERE $cp.structure='".$_SESSION["clp_structure"]."' and $cp.projet='".$_SESSION["clp_projet"]."' $where_in $where_not_in $jointure $whereLKEY $groupe_by";   //print_r($query_act); exit;

$act  = mysql_query_ruche($query_act , $pdar_connexion) or die(mysql_error());
$row_act  = mysql_fetch_assoc($act);
$totalRows_act  = mysql_num_rows($act);
}

$entete_array=array(); $choix_array = array();  $num=0; $village_check=0; unset($libelle_array);

foreach($table_join as $cp){
$tab = substr($cp,strlen($database_connect_prefix));

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$tab'";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);

if($totalRows_entete>0){  $nomT=$row_entete["nom"]; $note=$row_entete["note"];  $entete_array_temp=explode("|",$row_entete["show"]); foreach($entete_array_temp as $ent) $entete_array[]=$ent;
if($row_liste_conf["colonne"]=="annee" || $row_liste_conf["colonneV"]=="annee") $entete_array[count($entete_array)]="annee";
$libelle=explode("|",$row_entete["libelle"]);
$intitule=$row_entete["intitule"]; $colonne=$row_entete["colonnes"]; $lignetotal=$row_entete["lignetotal"]; $colnum=$row_entete["colnum"]; $detail_sexe=$row_entete["detail_sexe"]; $detail_menage=$row_entete["detail_menage"];
if(!empty($row_entete["choix"])){ foreach(explode("|",$row_entete["choix"]) as $elem){ if(!empty($elem)){  $a=explode(";",$elem); $choix_array[$a[0]]=""; for($i=1;$i<count($a);$i++){ $choix_array[$a[0]].=(!empty($a[$i]))?$a[$i].";":""; } }   }  } }

$count = count($libelle)-2;
$count = explode("=",$libelle[$count]);
$lib_nom_fich = "";
if(isset($count[1]))
$lib_nom_fich = $count[1];
elseif(isset($count[0]))
$lib_nom_fich = $count[0];

if(empty($lib_nom_fich)) $lib_nom_fich = $cp;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SHOW COLUMNS FROM `$cp` WHERE Field IN ('".$row_liste_conf["colonne"]."','".$row_liste_conf["colonneV"]."' $show_colonne)";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);   //echo $query_entete; exit;

if($totalRows_entete>0){ do{ if(in_array($row_entete["Field"],$entete_array)){ $num++; $lescolonneafficher[$row_entete["Field"]]=$row_entete["Field"]; } if($row_entete["Field"]=="village") $village_check++; }while($row_entete  = mysql_fetch_assoc($entete));  }

$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}

foreach($libelle as $a) { $b = explode('=',$a); if(isset($b[0])) $libelle_array[$b[0]]=(isset($b[1]))?$b[1]:"ND"; }    $libelle_array["annee"]="Ann&eacute;e";

}


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_pdes = "SELECT * FROM ".$database_connect_prefix."pde ORDER BY code_pde";
$liste_pdes = mysql_query_ruche($query_liste_pdes, $pdar_connexion) or die(mysql_error());
$row_liste_pdes = mysql_fetch_assoc($liste_pdes);
$totalRows_liste_pdes = mysql_num_rows($liste_pdes);
$PDE=array();
if($totalRows_liste_pdes>0){
  do{ $PDE[$row_liste_pdes["id_pde"]]=$row_liste_pdes["nom_pde"]; }while($row_liste_pdes = mysql_fetch_assoc($liste_pdes));
}


//graphique data
$groupElement=$dataValue=array();  //print_r($lescolonneafficher); exit;
//print_r($entete_array); exit;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php if(!isset($_GET["down"])){  ?>
<head>
  <title><?php print $row_liste_conf["intitule"]; ?></title>
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
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init();});</script>
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
.Style11 { font-weight: bold;color: #FFFFFF;}
.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
</style>
<script language="JavaScript" type="text/javascript">
$(document).ready(function() {


var oTable = $('#mytable').dataTable();
//Delete the datable object first
if(oTable != null)oTable.fnDestroy();
//Remove all the DOM elements
//$('#mytable').empty();

var oTable = $('#mytable').dataTable( {
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ -1 ] }
        ],
        sDom:"<'row'<'dataTables_header clearfix'<'col-md-7'lT><'col-md-5'Cf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
        oTableTools:{aButtons:["copy","print","csv","xls",{"sExtends": "pdf","sPdfOrientation": "landscape"}],sSwfPath:"./swf/copy_csv_xls_pdf.swf"},
        "aaSorting": [],
        //"aLengthMenu":[[25, 50, 100, 200, -1],[25, 50, 100, 200, "TOUS1"]],
        "iDisplayLength": -1,
        paging: false
    });

} );

</script>

<div class="contenu">


<div class="widget box box_projet">
<?php if(!isset($_GET["down"])){ ?>
<div class="widget-header1"> <center><h4><?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>
<?php } ?>
<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box">
<div class="widget-header"><?php if(!isset($_GET["down"])){ ?> <h4 style="width: 100%"><i class="icon-reorder">&nbsp;Rapport Dynamique</i><div class="r_float"><a href="./rapports_dynamiques.php" class="button">Retour</a></div><?php echo ""; /*if(!isset($_GET["down"])){  ?>
<div class="well well-sm r_float"><div class="r_float"><a href="./s_supervision.php" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction1."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction1."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format PDF" href="<?php echo $editFormAction1."&down=1&t=p"; ?>" class="button"><img src="./images/pdf.png" width='20' height='20' alt='Modifier' /></a></div></div>
<div class="clear h0">&nbsp;</div>
<?php } else { ?>

<center><?php include "./includes/print_header.php"; ?></center>

<?php }   */
if(!isset($_GET["down"]) && $row_liste_conf["colonne"]!="annee" && $row_liste_conf["colonneV"]!="annee") include("content/annee_projet.php"); ?> </h4>
<?php } ?>
</div>

<div class="widget-content" style="display: block;">

<!-- Site contenu ici -->

<?php if($num>0){ //$nomT : $lib_nom_fich
echo "<h3 style='padding:5px;margin-top:0px;background-color:#f9f9f9;'>".((isset($row_liste_conf["intitule"]))?$row_liste_conf["intitule"]:"ND")."</h3>";
?>

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive table-tabletools table-colvis datatable dataTable" id="mytable">
<?php if(1==1){ ?>
<thead>
<tr role="row">

<?php if($village_check>0){
 ?>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize" align="center">R&eacute;gion</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize" align="center">D&eacute;partement</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize" align="center">Commune</div></th>
<?php   }

if($totalRows_entete>0){ $i=0; foreach($lescolonneafficher as $nom_entete=>$row_entete["Field"]){   if($row_entete["Field"]!="village"){

if(isset($libelle[$i])){
$lib=explode("=",$libelle[$i]);
$libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";   }

if($row_entete["Field"]!="LKEY" ){ ?>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize" align="center"><?php if($nom_entete!=$row_entete["Field"]) echo $nom_entete; else echo (isset($libelle_array[$row_entete["Field"]]))?$libelle_array[$row_entete["Field"]]:str_replace("_"," ",$row_entete["Field"]); echo(isset($unite) && $row_liste_conf["colonneV"]==$row_entete["Field"])?" ($unite)":""; ?></div></th>
<?php $i++; } } }

if($row_liste_conf["colonne"]==$row_liste_conf["colonneV"]){    ?>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize" align="center"><?php echo ($row_liste_conf["mode"]=="compter")?"Nombre ".$libelle_array[$row_liste_conf["colonneV"]]:$row_liste_conf["mode"]." ".$libelle_array[$row_liste_conf["colonneV"]]; if(isset($unite) && $row_liste_conf["mode"]!="compter") echo " $unite"; ?></div></th>
<?php }    } ?>

</tr></thead>
<?php }  ?>

<tbody role="alert" aria-live="polite" aria-relevant="all" class="">
<?php if($totalRows_act>0) { $i=0;   do {
//in_array($row_act['LKEY'],$data_validate_array)
$id_data = $row_act['LKEY']; if(1==1){
foreach($choix_array as $Col=>$Val)
{
  $somme[$Col]=$produit[$Col]=$moyenne[$Col]=$rapport[$Col]=$difference[$Col]=$compteur[$Col]=0;
  $tem[$Col]=0;
}

$temoin=0;
?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">

<?php
if($totalRows_entete>=0){  foreach($lescolonneafficher as $nom_entete=>$row_entete["Field"]){ $temoin++;  //&& in_array($row_entete["Field"],$entete_array)
if($row_entete["Field"]!="LKEY" ){
if(strtolower($row_entete["Field"])=="village" && intval($row_act[$row_entete["Field"]])>0){ $village=$row_act[$row_entete["Field"]];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_region = "SELECT nom_village,nom_commune,nom_departement,nom_region FROM ".$database_connect_prefix."commune,".$database_connect_prefix."region, ".$database_connect_prefix."departement, ".$database_connect_prefix."village WHERE region=code_region and departement=code_departement and commune=code_commune and code_village='$village'";
$region = mysql_query($query_region, $pdar_connexion) or die(mysql_error());
$row_region = mysql_fetch_assoc($region);
$totalRows_region = mysql_num_rows($region);
$lib_vill = $row_region["nom_commune"]." / ".$row_region["nom_village"];

  ?>
<td class=" "><?php echo $row_region["nom_region"]; ?></td>
<td class=" "><?php echo $row_region["nom_departement"]; ?></td>
<td class=" "><?php echo $row_region["nom_commune"]; ?></td>
<?php mysql_free_result($region); }
if($temoin==1 && strtolower($row_entete["Field"])!="pde"){ $crochet=$row_act[$row_entete["Field"]]; }
elseif($temoin==1 && strtolower($row_entete["Field"])=="pde"){ $crochet=(isset($PDE[$row_act[$row_entete["Field"]]]))?$PDE[$row_act[$row_entete["Field"]]]:$row_act[$row_entete["Field"]];  }
?>
<?php
if(isset($row_liste_conf["colonne"]) && $row_entete["Field"]==$row_liste_conf["colonne"] && isset($row_region["nom_departement"])){ $groupeElement0=$row_region["nom_departement"]; $groupElement[$row_act[$row_entete["Field"]]]=$row_region["nom_departement"]; }

if(strtolower($row_entete["Field"])!="village"){
if(isset($row_liste_conf["colonne"]) && $row_entete["Field"]==$row_liste_conf["colonne"] && strtolower($row_entete["Field"])!="pde"){ $groupeElement0=$row_act[$row_entete["Field"]]; $groupElement[$row_act[$row_entete["Field"]]]=$row_act[$row_entete["Field"]]; }
elseif(isset($row_liste_conf["colonne"]) && $row_entete["Field"]==$row_liste_conf["colonne"] && strtolower($row_entete["Field"])=="pde"){ $groupeElement0=(isset($PDE[$row_act[$row_entete["Field"]]]))?$PDE[$row_act[$row_entete["Field"]]]:$row_act[$row_entete["Field"]]; $groupElement[$row_act[$row_entete["Field"]]]=(isset($PDE[$row_act[$row_entete["Field"]]]))?$PDE[$row_act[$row_entete["Field"]]]:$row_act[$row_entete["Field"]]; }

if(isset($row_liste_conf["colonneV"]) && $row_entete["Field"]==$row_liste_conf["colonneV"] && isset($row_region["nom_departement"])) $dataValue[$row_region["nom_departement"]][]=$row_act[$row_entete["Field"]];
elseif(isset($row_liste_conf["colonneV"]) && $row_entete["Field"]==$row_liste_conf["colonneV"] && !isset($row_region["nom_departement"]) && isset($crochet)) $dataValue[$crochet][]=$row_act[$row_entete["Field"]];

    ?>
<td class=" "><?php
if(strtolower($row_entete["Field"])=="pde") echo (isset($PDE[$row_act[$row_entete["Field"]]]))?$PDE[$row_act[$row_entete["Field"]]]:$row_act[$row_entete["Field"]];
else{ echo (strtolower($row_entete["Field"])=="village" && isset($row_region["nom_village"]) && isset($lib_vill))?$lib_vill:$row_act[$row_entete["Field"]];  }  unset($lib_vill); ?></td><?php } ?>
<?php } }//while($row_entete  = mysql_fetch_assoc($entete));

if($row_liste_conf["colonne"]==$row_liste_conf["colonneV"]){ ?>
<td class=" "><?php echo $row_act[$row_liste_conf["colonneV"]."ruche"]; ?></td>
<?php } } ?>

<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>

<?php }?>
</tr>
<?php $i++; } } while ($row_act = mysql_fetch_assoc($act));  }  ?>
</tbody></table>


<?php }else echo "<h3 align='center'>Aucune colonne &agrave; afficher dans le rapport ".((isset($row_liste_conf["intitule"]))?$row_liste_conf["intitule"]:"ND")."!</h3>"; ?>

<hr><h3 style="padding:5px;margin-top:0px;background-color:#f9f9f9;">R&eacute;pr&eacute;sentation graphique</h3><br />
<script type="text/javascript">
$(function () {
    $('#container1').highcharts({
        data: {
            table: document.getElementById('datatable')
        },
        chart: {
            type: 'column'
        },
        title: {
            text: "Graphique <?php echo ((isset($row_liste_conf['intitule']))?$row_liste_conf['intitule']:'ND'); ?>"
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Valeurs'
            }
        },
         plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '<b><span style="color:#000000">{point.y:.0f} <?php if(isset($unite)) echo $unite; ?></span></b>'
                        }
                    },
                    column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }

                },
        credits: {
                        enabled: true,
                        href: 'http:#',
                        text: 'RUCHE PNF : <?php echo date("d/m/Y H:i"); ?>',
                        style: {
                        cursor: 'pointer',
                        color: '#6633FF',
                        fontSize: '10px',
                        margin: '10px'
                        }
                     },
        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + '</b><br/>' +
                    this.point.y + ' ' ;//+ this.point.name.toLowerCase()
            }
        }
    });
});
		</script>
<script src="assets/js/highcharts.js"></script>
<script src="assets/js/modules/data.js"></script>
<script src="assets/js/modules/exporting.js"></script>
<script src="assets/js/modules/offline-exporting.js"></script>

<div id="container1" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<?php if(count($groupElement)>0 && count($dataValue)>0){ ?>
<table id="datatable" style="display:none; ">
	<thead>
		<tr>
			<th></th>
            <?php foreach($groupElement as $gp) echo "<th>$gp</th>"; ?>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th><?php echo ((isset($row_liste_conf['intitule']))?$row_liste_conf['intitule']:'ND'); ?></th>
<?php foreach($dataValue as $lib=>$data){  ?>
<?php
/*for($i=0;$i<count($groupElement);$i++){*/ echo "<td>".$data[0]."</td>"; //} ?>
	   <?php  } ?>	</tr>
   </tbody>
</table>
<?php } //print_r($groupElement); ?>
</div>

</div></div>
</div>

<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>    <?php //include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>