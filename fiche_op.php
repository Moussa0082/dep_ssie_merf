<?php

///////////////////////////////////////////////

/*                 SSE                       */

/*	Conception & DÃƒÆ’Ã†â€™Ãƒâ€ Ã¢â‚¬â„¢ÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â©veloppement: SEYA SERVICES */

///////////////////////////////////////////////

session_start();

include_once 'system/configuration.php';

$config = new Config;



if (!isset ($_SESSION["clp_id"])) {

  header(sprintf("Location: %s", "./"));

  exit;

}

include_once $config->sys_folder . "/database/db_connexion.php";

//header('Content-Type: text/html; charset=ISO-8859-15');



if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=0;



$dir = './attachment/fiche_collecte/';

if(!is_dir($dir)) mkdir($dir);

$uglprojet=str_replace("|",",",$_SESSION["clp_projet_ugl"]);

//liste region
 if($_SESSION['clp_id']=='admin') $query_liste_region = "SELECT code_departement,nom_departement FROM departement   order by code_departement asc";
else 
{
 $query_ugl_user = "SELECT * FROM ugl where code_ugl='".$_SESSION["clp_structure"]."'";
   try{
    $ugl_user = $pdar_connexion->prepare($query_ugl_user);
    $ugl_user->execute();
    $row_ugl_user = $ugl_user ->fetch();
    $totalRows_ugl_user = $ugl_user->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
 $cercle_projet = str_replace("|",",",$row_ugl_user["region_concerne"]);
//liste village
$query_liste_region = "SELECT code_departement,nom_departement FROM departement where FIND_IN_SET(code_departement, '".$cercle_projet."' )  order by code_departement asc";
}
   try{
    $liste_region = $pdar_connexion->prepare($query_liste_region);
    $liste_region->execute();
    $row_liste_region = $liste_region ->fetchAll();
    $totalRows_liste_region = $liste_region->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$tab_array = array();

if($totalRows_liste_region>0){  foreach($row_liste_region as $row_liste_region){  
  $tab_array[$row_liste_region["code_departement"]] = $row_liste_region["nom_departement"];
}  }
 $tab_array[0] = "Non attribu&eacute;es";
//echo $query_liste_region  print_r($tab_array); exit;
//liste village
/*$query_liste_village = "SELECT code_commune,nom_commune  FROM commune  order by code_commune asc";
 try{
    $liste_village = $pdar_connexion->prepare($query_liste_village);
    $liste_village->execute();
    $row_liste_village = $liste_village ->fetch();
    $totalRows_liste_village = $liste_village->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$village_array = array();
if($totalRows_liste_village>0){ foreach($row_liste_village as $row_liste_village){ 
  $village_array[$row_liste_village["nom_commune"]] = $row_liste_village["code_commune"];
}  }*/

//import
//import
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form0"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert"))
  {
    $poids_max=2048576; //Poids maximal du fichier en octets
    $extensions_autorisees=array('xls','xlsx'); //Extensions autorisÃƒÂ©es ,'csv'
    $url_site='./attachment/'; //Adresse oÃƒÂ¹ se trouve le fichier upload.
    $page = $_SERVER['PHP_SELF'];
    $ext = substr(strrchr($_FILES['fichier']['name'], "."), 1);

    $feuille = $_POST["feuille"]; 
    $annee=isset($_POST["annee"])?$_POST["annee"]:date("Y");
    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
    $interdit_array = array("classeur",$_POST["idf"],$_POST["ide"],"annee","projet","structure","code_activite","id_personnel","date_enregistrement","modifier_le","modifier_par","etat");

    $query_entete = "DESCRIBE ".$database_connect_prefix."$feuille";
	    	   try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetchAll();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

    $entete_array = array();
    if($totalRows_entete>0){  foreach($row_entete as $row_entete){ 
      $entete_array[$row_entete["Field"]]=$row_entete["Type"]; }
     }

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
        if(isset($_POST["erase"]) && $_POST["erase"]==1)
        {
		
         // mysql_select_db($database_pdar_connexion, $pdar_connexion);
		   if(isset($_POST["vide"]) && $_POST["vide"]>0)   {
		   $nve=$_POST["ide"]; $valie=$_POST["vide"];
          $query_sup_import_annee = "DELETE FROM ".$database_connect_prefix."$feuille WHERE $nve=$valie";
		  }
		  else 
		  {
		 
            $query_sup_import_annee = "DELETE FROM ".$database_connect_prefix."$feuille";
			
		  }
 try{
    $Result1 = $pdar_connexion->prepare($query_sup_import_annee);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }    
      }

       /* mysql_select_db($database_pdar_connexion, $pdar_connexion);
        $query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$feuille' and intitule is null";
        $entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
        //$row_entete  = mysql_fetch_assoc($entete);
        $totalRows_entete  = mysql_num_rows($entete);*/
        $k = 2;
		
        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = $k; $row <= $highestRow; $row++)
        {
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
            NULL, TRUE, FALSE);
            if(!empty($rowData[0][0]) && strtolower(trim($rowData[0][0]))!="total")
            {
              $sql='' ; $titre="";  $key = date("ymdi").(date("s")+$row).$_SESSION['clp_n'];
               //$sql.= GetSQLValueString($key, "text").',';
			   if(isset($_POST["vide"]) && $_POST["vide"]>0)   { $sql.= GetSQLValueString( $_POST["vide"], "int").','; $nve=$_POST["ide"]; $titre.="`$nve`,";}
			  // if(isset($_POST["vide"]) && $_POST["vide"]>0)    $titre.= GetSQLValueString( $_POST["vide"], "int").',';
              $i=0; foreach($entete_array as $name=>$value){
if(!in_array($name,$interdit_array)){
$titre.="`$name`,";
//str_replace("'", "\'", $rowData[0][$i]);
// $village_array[$row_liste_village["nom_pde"]] = $row_liste_village["code_pde"];
 
/*if(isset($entete_array[$name]) && $name=="bassins" && isset($village_array[$rowData[0][$i]])) $sql.= GetSQLValueString(trim(utf8_decode($village_array[$rowData[0][$i]])),"text").',';
elseif(isset($entete_array[$name]) && $name=="bassins" && !isset($village_array[$rowData[0][$i]])) $sql.= GetSQLValueString("ND","text").',';
else*/
if(isset($entete_array[$name]) && (strtolower($entete_array[$name])=="int" || strtolower($entete_array[$name])=="double")) $sql.= GetSQLValueString(trim($rowData[0][$i]), strtolower($entete_array[$name])).',';
elseif(isset($entete_array[$name]) && strtolower($entete_array[$name])!="date") $sql.= GetSQLValueString(trim(utf8_decode(str_replace("'", "\'", $rowData[0][$i]))), "text").','; else{
if(isset($rowData[0][$i]) && !empty($rowData[0][$i]) && strchr(trim($rowData[0][$i]),"/")) $mdate = implode("-",array_reverse(explode("/",trim($rowData[0][$i])))); elseif(isset($rowData[0][$i]) && !empty($rowData[0][$i])) { $timestamp = PHPExcel_Shared_Date::ExcelToPHP(trim($rowData[0][$i])); $mdate = date('Y-m-d', $timestamp); } else $mdate = "0000-00-00"; $sql.= '"'.$mdate.'",'; } $i++; } }
$sql=substr($sql,0,strlen($sql)-1);


 if(isset($_POST["vide"]) && $_POST["vide"]>0)   { 
// mysql_select_db($database_pdar_connexion, $pdar_connexion);
$insertSQL = 'INSERT INTO '.$database_connect_prefix.$feuille.' ('.substr($titre,0,strlen($titre)-1).',`date_enregistrement`,`id_personnel`) VALUES ('.$sql.',"'.$date.'","'.$personnel.'")';
 try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
 } else {
 
 //mysql_select_db($database_pdar_connexion, $pdar_connexion);
$insertSQL = 'INSERT INTO '.$database_connect_prefix.$feuille.' ('.substr($titre,0,strlen($titre)-1).', `date_enregistrement`, `id_personnel`) VALUES ('.$sql.',"'.$date.'","'.$personnel.'")';
 try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
}

//echo $insertSQL."<br />";
           }
          }
          unlink($inputFileName);   //exit;
          if($Result1) $insertGoTo = $page."?import=ok";
          else $insertGoTo = $page."?import=no";
          $insertGoTo .= "&id=$classeur&feuille=$database_connect_prefix"."$feuille&annee=$annee";
          header(sprintf("Location: %s", $insertGoTo)); exit();
        }
    }
    else
    {
      $insertGoTo = $page."?import=no";
      $insertGoTo .= "&id=$classeur&feuille=$database_connect_prefix"."$feuille&annee=$annee";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }
  }
}

if(isset($_GET["id_sup"]))

{

  $id=intval($_GET["id_sup"]);
  $query_sup_act = "DELETE FROM liste_op WHERE id_op='$id'";

try{
    $Result1 = $pdar_connexion->prepare($query_sup_act);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];

  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";

  $insertGoTo .= "&annee=$annee";

 // mysql_free_result($Result1);

  header(sprintf("Location: %s", $insertGoTo));

}



if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))

{ //Atelier

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {

    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; //$lieu = array();

//TDR

  /*$a = explode(',',$_POST['lieu']);  foreach($a as $b){ $c = explode(':',$b); if(isset($c[0]) && !empty($c[0])) $lieu[] = $c[0]; } */

  //$a = explode('/',$_POST['date_collecte']); $annee = isset($a[2])?$a[2]:date("Y");

  $insertSQL = sprintf("INSERT INTO liste_op (sigle_op, nom_op, village, villages_associes, adresse, personne_ressource, contact, date_creation, type_organisation, existence_legale, numero_compte1, numero_compte2, faitiere, nom_imf, speculation, observation, date_collecte, nom_collecteur, structure,  projet, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,  '$personnel', '$date')",
                       GetSQLValueString($_POST['sigle_op'], "text"),
                       GetSQLValueString($_POST['nom_op'], "text"),
					     GetSQLValueString($_POST['village'], "text"),
 					  GetSQLValueString(implode(',',$_POST['villages_associes']), "text"),
					  GetSQLValueString($_POST['adresse'], "text"),
					   GetSQLValueString($_POST['personne_ressource'], "text"),
                       GetSQLValueString($_POST['contact'], "text"),
					 /* GetSQLValueString($_POST['nb_homme'], "int"),
                       GetSQLValueString($_POST['nb_femme'], "int"),
                       GetSQLValueString($_POST['nb_jeune'], "int"),*/
                     GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_creation']))), "date"),       
                      GetSQLValueString($_POST['type_organisation'], "text"),
					  GetSQLValueString($_POST['existence_legale'], "text"),
                       GetSQLValueString($_POST['numero_compte1'], "text"),
					  GetSQLValueString($_POST['numero_compte2'], "text"),
					  GetSQLValueString(implode(',',$_POST['faitiere']), "text"),
                      // GetSQLValueString($_POST['coges'], "text"),
					   // GetSQLValueString($_POST['carte_bancaire'], "text"),
					   GetSQLValueString(implode(',',$_POST['nom_imf']), "text"),
                       GetSQLValueString(implode(',',$_POST['speculation']), "text"),
                       GetSQLValueString($_POST['observations'], "text"),
                        GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_collecte']))), "date"),
                       GetSQLValueString($_POST['nom_collecteur'], "text"),
					  GetSQLValueString($_SESSION["clp_structure"], "text"),
					   GetSQLValueString($_SESSION["clp_projet"], "text"));
try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
      $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";

    $insertGoTo .= "&annee=$annee";

    header(sprintf("Location: %s", $insertGoTo));  exit();


 //FIN TDR

  }




    if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {

    $id = $_POST["MM_delete"];

    $insertSQL = sprintf("DELETE from liste_op WHERE id_op=%s",

                         GetSQLValueString($id, "text"));



try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
    $insertGoTo = $_SERVER['PHP_SELF'];

    if ($Result1){ $insertGoTo .= "?del=ok"; }  else $insertGoTo .= "?del=no";

    $insertGoTo .= "&annee=$annee";

    header(sprintf("Location: %s", $insertGoTo)); exit();

  }



  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {

  $id = $_POST["MM_update"]; $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

   // $link = ""; $Result1 = false; $lieu = array();

  

  /*$a = explode(',',$_POST['lieu']);  foreach($a as $b){ $c = explode(':',$b); if(isset($c[0]) && !empty($c[0])) $lieu[] = $c[0]; }*/

 // $a = explode('/',$_POST['date_collecte']);
 // $annee =substr($_POST['code_fiche'], -4);// isset($a[2])?$a[2]:date("Y"); 

  $insertSQL = sprintf("UPDATE liste_op SET sigle_op=%s, nom_op=%s, village=%s, villages_associes=%s, adresse=%s, personne_ressource=%s, contact=%s, date_creation=%s, type_organisation=%s, existence_legale=%s, numero_compte1=%s, numero_compte2=%s, faitiere=%s, nom_imf=%s, speculation=%s, observation=%s, date_collecte=%s, nom_collecteur=%s, structure=%s, etat='ModifiÃƒÆ’Ã‚Â©', modifier_par='$personnel', modifier_le='$date' WHERE id_op='$id'",

                          GetSQLValueString($_POST['sigle_op'], "text"),
                       GetSQLValueString($_POST['nom_op'], "text"),
					     GetSQLValueString($_POST['village'], "text"),
 					  GetSQLValueString(implode(',',$_POST['villages_associes']), "text"),
					  GetSQLValueString($_POST['adresse'], "text"),
					   GetSQLValueString($_POST['personne_ressource'], "text"),
                       GetSQLValueString($_POST['contact'], "text"),
					 /* GetSQLValueString($_POST['nb_homme'], "int"),
                       GetSQLValueString($_POST['nb_femme'], "int"),
                       GetSQLValueString($_POST['nb_jeune'], "int"),*/
                     GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_creation']))), "date"),       
                      GetSQLValueString($_POST['type_organisation'], "text"),
					  GetSQLValueString($_POST['existence_legale'], "text"),
                       GetSQLValueString($_POST['numero_compte1'], "text"),
					  GetSQLValueString($_POST['numero_compte2'], "text"),
					  GetSQLValueString(implode(',',$_POST['faitiere']), "text"),
                      // GetSQLValueString($_POST['coges'], "text"),
					   // GetSQLValueString($_POST['carte_bancaire'], "text"),
					   GetSQLValueString(implode(',',$_POST['nom_imf']), "text"),
                       GetSQLValueString(implode(',',$_POST['speculation']), "text"),
                       GetSQLValueString($_POST['observations'], "text"),
                        GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_collecte']))), "date"),
                       GetSQLValueString($_POST['nom_collecteur'], "text"),
					    GetSQLValueString($_SESSION["clp_structure"], "text"));



try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }


    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];

    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";

    $insertGoTo .= "&annee=$annee";

    header(sprintf("Location: %s", $insertGoTo)); exit();



}
  }


if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))

{ //Rapport

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {

    $id = $_POST["MM_update"];

    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $Result1 = false;



//TDR



  $insertSQL = sprintf("UPDATE fiche_suivi_fiec SET date_aller=%s, date_retour=%s, observation=%s, ".(($Result1)?" rapport=".GetSQLValueString($link, "text").", ":"")." etat='ModifiÃƒÆ’Ã‚Â©', modifier_par='$personnel', modifier_le='$date' WHERE id_op='$id'",



  					   GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_aller']))), "date"),

                       GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_retour']))), "date"),

					   GetSQLValueString($_POST['observation'], "text"));



try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }


    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];

    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";

    $insertGoTo .= "&annee=$annee";

    header(sprintf("Location: %s", $insertGoTo)); exit();

  }

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

  <title><?php print $config->sitename; ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />
  <!-- <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
  <meta name="keywords" content="<?php print $config->MetaKeys; ?>" />
  <meta name="description" content="<?php print $config->MetaDesc; ?>" />
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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

  <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init();$("#container").addClass("sidebar-closed");});</script>

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

<script>

$().ready(function() {

    init_tabs();

});



function show_tab(tab) {

    if (!tab.html()) {

        tab.load(tab.attr('data-target'));

    }

}



function init_tabs() {

    show_tab($('.tab-pane.active'));

    $('a[data-toggle="tab"]').click('show', function(e) {

        tab = $('#' + $(e.target).attr('href').substr(1));

        show_tab(tab);

    });

}

</script>




<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i><strong>Liste  des groupements </strong></h4>

  <?php echo do_link("","./base_de_donnees.php","Back","<u><i>Retour aux fiches de collecte</i></u>","","./","pull-right p11","",0,"",$nfile); ?>
   <?php
echo do_link("","","Requ&ecirc;te pr&eacute;d&eacute;finie","RECAP : Groupements","","./","pull-right p11","get_content('requete_donnees_generale_op.php','','modal-body_add',this.title,'iframe');",1,"",$nfile);?>


</div>



<div class="widget-content" style="display: block;">



<div class="tabbable tabbable-custom" >

  <ul class="nav nav-tabs" >
  <?php //for($j=$_SESSION["annee_debut_projet"];$j<=date("Y");$j++){ ?>
   <?php $j=0; foreach($tab_array as $a=>$b){ ?>
      <li title="" class="<?php echo ($j==$annee || $j==0)?"active":""; ?>"><a href="#tab_feed_<?php echo $a; ?>" data-toggle="tab"><?php echo $b; ?></a></li>
  <?php $j++; } ?>
  </ul>

  <div class="tab-content">
  <?php $j=0; foreach($tab_array as $a=>$b){ ?>
  <div class="tab-pane <?php echo ($j==$annee || $j==0)?"active":""; ?>" id="tab_feed_<?php echo $a; ?>" data-target="./fiche_op_content.php?annee=<?php echo $a; ?>"></div>
  <?php $j++;}  ?>
  </div>

</div>



</div>



</div>



<!-- Fin Site contenu ici -->

     

        </div>

  </div>

        </div>

    </div>    <?php include_once 'modal_add.php'; ?>

    <?php include_once("includes/footer.php"); ?>

</div>



</body>

</html>