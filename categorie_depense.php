<?php

///////////////////////////////////////////////

/*                 SSE                       */

/*	Conception & DÃ©veloppement: SEYA SERVICES */

///////////////////////////////////////////////

session_start();

include_once 'system/configuration.php';

$config = new Config;



if (!isset ($_SESSION["clp_id"])) {

  header(sprintf("Location: %s", "./"));

  exit;

}

include_once $config->sys_folder . "/database/db_connexion.php";



if ((isset($_GET["id_sup"]) && !empty($_GET["id_sup"]))) {

  $id = ($_GET["id_sup"]);

  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."categorie_depense WHERE id_categorie=%s",

                       GetSQLValueString($id, "int"));



  try{

        $Result1 = $pdar_connexion->prepare($insertSQL);

        $Result1->execute();

  }catch(Exception $e){ die(mysql_error_show_message($e)); }



  $insertGoTo = $_SERVER['PHP_SELF'];

  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";

  header(sprintf("Location: %s", $insertGoTo)); exit();

}



//import

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form0"))

{

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert"))

  {

    $poids_max=2048576; //Poids maximal du fichier en octets

    $extensions_autorisees=array('xls','xlsx'); //Extensions autorisées ,'csv'

    $url_site='./attachment/'; //Adresse où se trouve le fichier upload.

    $page = $_SERVER['PHP_SELF'];

    $ext = substr(strrchr($_FILES['fichier']['name'], "."), 1);



    if(in_array($ext,$extensions_autorisees))

    {

      if($_FILES['fichier']['size']>$poids_max)

      {

        $message='Un ou plusieurs fichiers sont trop lourds !';

        echo $message;

      }

      elseif(isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0)

      {

        $inputFileName=$url_site.$_FILES['fichier']['name'];

        move_uploaded_file($_FILES['fichier']['tmp_name'],$inputFileName);



        require_once('Classes/PHPExcel.php');

        try {

            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);

            $objReader = PHPExcel_IOFactory::createReader($inputFileType);

            $objPHPExcel = $objReader->load($inputFileName);

        } catch (Exception $e) {

            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)

            . '": ' . $e->getMessage());

        }



        $query_sup_import_annee = sprintf("DELETE from ".$database_connect_prefix."categorie_depense where projet='".$_SESSION["clp_projet"]."'"/*" WHERE ".(($niveau1==-1)?"":" niveau=".GetSQLValueString(intval($_GET["niveau"])+1, "int")." and ")."structure=%s and projet=%s",

                             GetSQLValueString($_SESSION['clp_structure'], "text"),

                             GetSQLValueString($_SESSION['clp_projet'], "text")*/);

        try{

            $Result1 = $pdar_connexion->prepare($query_sup_import_annee);

            $Result1->execute();

        }catch(Exception $e){ /*die(mysql_error_show_message($e));*/ }



        //  Get worksheet dimensions

        $sheet = $objPHPExcel->getSheet(0);

        $highestRow = $sheet->getHighestRow();

        $highestColumn = $sheet->getHighestColumn();



        for ($row = 5; $row <= $highestRow; $row++)

        {

            //  Read a row of data into an array

            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,

            NULL, TRUE, FALSE);

            if($rowData[0][2]!='Code' && (!empty($rowData[0][2]) || !empty($rowData[0][3])))

            {

              $convention = (!empty($rowData[0][2]))?$rowData[0][2]:$convention;

              $code = trim($rowData[0][3]);

              if(!empty($rowData[0][3]))

              {

               $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."categorie_depense (code, nom_categorie, convention_concerne, projet) VALUES (%s, %s, %s, %s)",

                           GetSQLValueString($code, "text"),

                           GetSQLValueString(utf8_decode(trim($rowData[0][5])), "text"),

                           GetSQLValueString(trim($convention), "text"),

						   GetSQLValueString($_SESSION["clp_projet"], "text"));

                try{

                    $Result1 = $pdar_connexion->prepare($insertSQL);

                    $Result1->execute();

                }catch(Exception $e){ /*die(mysql_error_show_message($e));*/ }

                //echo $code." - <font size='1'>".$insertSQL."</font><br />";

              }

            }

          }

          unlink($inputFileName);

          if($Result1) $insertGoTo = $page."?import=ok";

          else $insertGoTo = $page."?import=no";

          $insertGoTo .= (isset($_GET["niveau"]))?"&niveau=".intval($_GET["niveau"]):"";

          header(sprintf("Location: %s", $insertGoTo)); exit();

        }

    }

    else

    {

      $insertGoTo = $page."?import=no";

      header(sprintf("Location: %s", $insertGoTo)); exit();

    }

  }

}



if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))

{ //categorie_depense

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {

    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."categorie_depense (code, nom_categorie, convention_concerne, projet) VALUES (%s, %s, %s, %s)",

                    GetSQLValueString($_POST['code'], "text"),

                    GetSQLValueString($_POST['nom_categorie'], "text"),

                    GetSQLValueString($_POST['convention'], "text"),

                    GetSQLValueString($_SESSION["clp_projet"], "text"));



    try{

        $Result1 = $pdar_connexion->prepare($insertSQL);

        $Result1->execute();

    }catch(Exception $e){ die(mysql_error_show_message($e)); }



    $insertGoTo = $_SERVER['PHP_SELF'];

    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";

    header(sprintf("Location: %s", $insertGoTo));  exit();

  }



  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {

    $id = ($_POST["MM_delete"]);

    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."categorie_depense WHERE id_categorie=%s",

                         GetSQLValueString($id, "int"));



    try{

        $Result1 = $pdar_connexion->prepare($insertSQL);

        $Result1->execute();

    }catch(Exception $e){ die(mysql_error_show_message($e)); }



    $insertGoTo = $_SERVER['PHP_SELF'];

    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";

    header(sprintf("Location: %s", $insertGoTo)); exit();

  }



  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {

    $id = ($_POST["MM_update"]);

    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."categorie_depense SET code=%s, nom_categorie=%s, convention_concerne=%s WHERE id_categorie=%s",

                         GetSQLValueString($_POST['code'], "text"),

                         GetSQLValueString($_POST['nom_categorie'], "text"),

                         GetSQLValueString($_POST['convention'], "text"),

                         GetSQLValueString($id, "int"));



    try{

        $Result1 = $pdar_connexion->prepare($insertSQL);

        $Result1->execute();

    }catch(Exception $e){ die(mysql_error_show_message($e)); }



    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];

    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";

    header(sprintf("Location: %s", $insertGoTo));  exit();

  }

}

/*

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))

{

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

$categoried=$_POST['code'];

$dotation=$_POST['dotation'];

$convention=$_POST['convention'];

//$trimestre=$_POST['trimestre'];

//suppression

//foreach ($convention as $key => $value)

//{

  mysql_select_db($database_pdar_connexion, $pdar_connexion);

 // $idin=$id_ind[$key];

  $query_sup_cible_indicateur = "DELETE FROM ".$database_connect_prefix."categorie_depense_convention WHERE categorie_depense='$categoried' and projet='".$_SESSION["clp_projet"]."'";

  $Result1 = mysql_query($query_sup_cible_indicateur, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

//}



// `indicateur` int(11) NOT NULL,   `mois` int(11) DEFAULT NULL,  `cible` float DEFAULT '0',

foreach ($convention as $key => $value)

{

	if(isset($dotation[$key]) && $dotation[$key]!=NULL) {

  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."categorie_depense_convention  (projet, categorie_depense, convention,dotation_initiale, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, '$personnel', '$date')",

                       GetSQLValueString($_SESSION["clp_projet"], "text"),

					   GetSQLValueString($_POST['code'], "text"),

					   GetSQLValueString($convention[$key], "text"),

					   GetSQLValueString($dotation[$key], "double"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);

  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    }

  }

 $insertGoTo = $page;

    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";

    header(sprintf("Location: %s", $insertGoTo)); exit(0);

}

  */

  /*if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {

    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['MM_update'];

    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."categorie_depense SET convention_concerne=%s, modifier_le='$date', modifier_par='$personnel' WHERE  code=%s",

                      GetSQLValueString(implode('|',$_POST['convention'])."|", "text"),                     

                      GetSQLValueString($_POST['code'], "text"));



    mysql_select_db($database_pdar_connexion, $pdar_connexion);

    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    $insertGoTo = $page;

    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";

    header(sprintf("Location: %s", $insertGoTo)); exit(0);

  }

}*/





//categorie_depense

$query_liste_cat_dep = "SELECT id_part as id_categorie, code_type as code, intitule as nom_categorie, code_type as convention_concerne FROM ".$database_connect_prefix."type_part where ".$database_connect_prefix."type_part.projet='".$_SESSION["clp_projet"]."' order by code ";

try{

    $liste_cat_dep = $pdar_connexion->prepare($query_liste_cat_dep);

    $liste_cat_dep->execute();

    $row_liste_cat_dep = $liste_cat_dep ->fetchAll();

    $totalRows_liste_cat_dep = $liste_cat_dep->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }



//les conventions

$query_liste_convention = "SELECT * FROM ".$database_connect_prefix."categorie_depense where projet='".$_SESSION["clp_projet"]."' ";

try{

    $liste_convention = $pdar_connexion->prepare($query_liste_convention);

    $liste_convention->execute();

    $row_liste_convention = $liste_convention ->fetchAll();

    $totalRows_liste_convention = $liste_convention->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }

$liste_id_categorie_array = $liste_liste_convention_array = $liste_liste_convention_arrayV = array();

if($totalRows_liste_convention>0){ foreach($row_liste_convention as $row_liste_convention){

//if(!isset($liste_liste_convention_array[$row_liste_convention["convention_concerne"]]))

$liste_id_categorie_array[$row_liste_convention["id_categorie"]] = $row_liste_convention["code"];

$liste_liste_convention_array[$row_liste_convention["convention_concerne"]][$row_liste_convention["id_categorie"]] = $row_liste_convention["nom_categorie"];

$liste_liste_convention_arrayV[$row_liste_convention["convention_concerne"]][] = $row_liste_convention["nom_categorie"];

} }

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

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

<body>

 <header class="header navbar navbar-fixed-top" role="banner">

    <?php include_once("includes/header.php"); ?>

 </header>

<div id="container">

    <div id="sidebar" class="sidebar-fixed">

        <div id="sidebar-content">

            <?php include_once("includes/menu_top.php"); ?>

        </div>

        <div id="divider" class="resizeable"></div>

    </div>



    <div id="content">

        <div class="container">

            <div class="crumbs">

                <?php include_once("includes/sous_menu.php"); ?>

            </div>

        <div class="page-header">

            <div class="p_top_5">

<!-- Site contenu ici -->

<?php include_once 'modal_add.php'; ?>

<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {

  border-spacing: 0px !important; border-collapse: collapse;

} .table tbody tr td {vertical-align: middle; }



</style>

<!--<div class="page-header">

<div class="page-title"><h3>Mon profil</h3></div>

</div> -->

<div class="widget box">

<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i> Cat&eacute;gorie de d&eacute;penses</h4>

<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==0){ ?>

<?php

//echo do_link("","","Importation d&eacute;puis un format excel","<i class=\"icon-plus\"> Importer </i>","","./","pull-right p11","get_content('import.php','id=categorie_depense&niveau=-1','modal-body_add',this.title);",1,"",$nfile);



echo do_link("","","Ajout de cat&eacute;gorie de d&eacute;penses","<i class=\"icon-plus\"> Nouvelle cat&eacute;gorie de d&eacute;penses </i>","","./","pull-right p11","get_content('new_categorie_depense.php','','modal-body_add',this.title);",1,"",$nfile);

?>

<?php } ?>

</div>

<div class="widget-content" style="display: block;">



<div class="col-md-6">

<div class="widget box">

<div class="widget-header" title="Structure de la cat&eacute;gorie de d&eacute;pense"> <h4><i class="icon-reorder"></i> Structure de la cat&eacute;gorie de d&eacute;pense</h4> </div>

<div class="widget-content">



<table border="1" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive">



<?php

function trace_tr($j,$n,$libelle,$nfile="")

{

  $activitep_array = array(); $id = $libelle['id_categorie']; $code = $libelle['code'];

  $data = "";

  $data .= "<tr>";

  for($k=0;$k<$j;$k++){ $data .= "<td width='30' align='right'>&nbsp;</td>"; }

  //$data .= "<td colspan='".($n-$j)."'><b>".$code." :</b> ".$libelle["nom_categorie"]."</td>";

  if($j==0)

  {

    $data .= "<td colspan='".($n-$j+2)."'><b>".$code." :</b> ".$libelle["nom_categorie"]."</td>";

  }

  else

  {

    $data .= "<td colspan='".($n-$j)."'><b>".$code." :</b> ".$libelle["nom_categorie"]."</td>";

    if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==0){

    $data .= "<td width='90' align='center'>";

    $data .= do_link("","","Modifier Cat&eacute;gorie de d&eacute;penses","","edit","./","","get_content('new_categorie_depense.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

    $data .= do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette cat&eacute;gorie de d&eacute;penses ?');",0,"margin:0px 5px;",$nfile);

    $data .= "</td>";

    }

  }

  $data .= "</tr>";

  return $data;

}



if($totalRows_liste_cat_dep>0){ foreach($row_liste_cat_dep as $row_liste_cat_dep1){

  $a = $row_liste_cat_dep1["convention_concerne"];

  //traitement ici

  echo trace_tr(0,1,$row_liste_cat_dep1);

  if(isset($liste_liste_convention_array[$a])){

  foreach($liste_liste_convention_array[$a] as $b=>$c)

  {

    $data = array('id_categorie'=>$b,'code'=>$liste_id_categorie_array[$b],'nom_categorie'=>$c);

    //traitement ici

    echo trace_tr(1,2,$data,$nfile);

  }      }

}

}



?>

  </tr>

</table>

<div class="clear h0">&nbsp;</div>

</div>

 </div>



</div>

<div class="clear h0">&nbsp;</div>



</div> </div>

</div>

<!-- Fin Site contenu ici -->

            </div>

        </div>



        </div>

    </div>

    <?php include_once("includes/footer.php"); ?>

</div>

</body>

</html>