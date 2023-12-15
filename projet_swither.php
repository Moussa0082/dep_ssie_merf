<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"]) && !isset($_GET["id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}

$editFormAction = $_GET['page'].= "";
if (isset($_SERVER['QUERY_STRING'])) {  $get_data = explode("=",($_SERVER['QUERY_STRING']));
  $i=0;  foreach($get_data as $val){ if($i>=3){ if($i==3) $val=substr($val,strpos($val,"&"));
$editFormAction .= ($i<count($get_data)-1)?$val."=":((strrchr($val,"&")!="")?$val:""); } $i++; }
}     

include_once $config->sys_folder . "/database/db_connexion.php";

if ((isset($_GET["id"]) && $_GET["id"]!='')) {
  $id=$_SESSION['clp_n'];
  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."personnel SET projet_active=%s WHERE N=%s",
                       GetSQLValueString($_GET["id"], "text"),
                       GetSQLValueString($id, "int"));
  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  if($Result1)
  {
    $mySqlQuery = "SELECT * FROM ".$database_connect_prefix."projet where code_projet=".GetSQLValueString($_GET["id"], "text");
    try{
        $qh = $pdar_connexion->prepare($mySqlQuery);
        $qh->execute();
        $data = $qh ->fetch();
        $totalRows_data = $qh->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }

    $_SESSION["clp_projet_sigle"] = ($data['sigle_projet']);
    $_SESSION["clp_projet_nom"] = ($data['intitule_projet']);
    $_SESSION['clp_projet'] = $_GET["id"];
    $_SESSION["clp_where"] = " projet='".$_SESSION['clp_projet']."'";
    $_SESSION["clp_projet_ugl"] = ($data['ugl']);
	$_SESSION["annee_debut_projet"] = ($data['annee_debut']);
    $_SESSION["annee_fin_projet"] = ($data['annee_fin']);

$mySqlQuery = "SELECT * FROM ".$database_connect_prefix."ugl where code_ugl='".$_SESSION['clp_structure']."'";
try{
    $qh = $pdar_connexion->prepare($mySqlQuery);
    $qh->execute();
    $data = $qh ->fetch();
    $mySqlQuery = $qh->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if(isset($data["abrege_ugl"])) $_SESSION["clp_ugl"]=$data["abrege_ugl"];

}

  $insertGoTo = (isset($_GET['page']))?$_GET['page']:"./";
  //if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $editFormAction));  exit();
}

?>