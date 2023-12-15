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

$personnel = $_SESSION["clp_id"];
$date = date("Y-m-d");
//categorie de marches
if (isset($_GET["id_sup"])) {
  $id = ($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."categorie_marche WHERE code_categorie=%s",
                       GetSQLValueString($id, "text"));
  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{ //Fonction
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."categorie_marche (nom_categorie, code_categorie, id_personnel, date_enregistrement) VALUES (%s, %s,'$personnel', '$date')",
						  GetSQLValueString($_POST['nom_categorie'], "text"),
					   GetSQLValueString($_POST['code_categorie'], "text"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo));
  }

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
    $id = ($_POST["MM_delete"]);

//$query_sup_categorie = "DELETE FROM ".$database_connect_prefix."categorie_marche WHERE code_categorie='$id'";

 $insertSQL = sprintf("DELETE from ".$database_connect_prefix."categorie_marche WHERE code_categorie=%s",
                         GetSQLValueString($id, "text"));
    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }

    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if (isset($_POST["MM_update"]) && !empty($_POST["MM_update"])) {
    $id = ($_POST["MM_update"]);

  	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."categorie_marche SET nom_categorie=%s, code_categorie=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE code_categorie='$id'",
					   GetSQLValueString($_POST['nom_categorie'], "text"),
					   GetSQLValueString($_POST['code_categorie'], "text"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo));
  }
}

//methode de passation de marches
if (isset($_GET["id_supm"])) {
  $id = ($_GET["id_supm"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."methode_marche WHERE sigle=%s",
                       GetSQLValueString($id, "text"));
  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2m"))
{ //Fonction
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."methode_marche (sigle, description, id_personnel, date_enregistrement) VALUES (%s, %s,'$personnel', '$date')",
						  GetSQLValueString($_POST['sigle'], "text"),
					   GetSQLValueString($_POST['description'], "text")//,
					   );
  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo));
  }
  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."methode_marche WHERE sigle=%s",
                         GetSQLValueString($id, "text"));
    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }

    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
  if (isset($_POST["MM_update"]) && !empty($_POST["MM_update"])) {
    $id = ($_POST["MM_update"]);
  	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."methode_marche SET sigle=%s, description=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE sigle='$id'",
					   GetSQLValueString($_POST['sigle'], "text"),
					   GetSQLValueString($_POST['description'], "text")//,
					   );
  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo));
  }
}
//groupe d'etape de passation de marches
if (isset($_GET["id_supge"])) {
  $id = ($_GET["id_supge"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."groupe_etape WHERE id_groupe=%s",
                       GetSQLValueString($id, "text"));
  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2ge"))
{ //Fonction
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."groupe_etape (code_groupe, num_groupe, categorie_groupe, libelle_groupe, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s,'$personnel', '$date')",
						 
                GetSQLValueString($_POST['code_groupe'], "text"),
				 GetSQLValueString($_POST['num_groupe'], "int"),
                GetSQLValueString(implode(',',$_POST['categorie']), "text"),
                GetSQLValueString($_POST['libelle_groupe'], "text")//,
                );
  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo));
  }
  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."groupe_etape WHERE id_groupe=%s",
                         GetSQLValueString($id, "text"));
    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }

    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
  if (isset($_POST["MM_update"]) && !empty($_POST["MM_update"])) {
    $id = ($_POST["MM_update"]);
  	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."groupe_etape SET code_groupe=%s, num_groupe=%s, categorie_groupe=%s, libelle_groupe=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_groupe='$id'",
					   GetSQLValueString($_POST['code_groupe'], "text"),
					      GetSQLValueString($_POST['num_groupe'], "int"),
					     GetSQLValueString(implode(',',$_POST['categorie']), "text"),
					   GetSQLValueString($_POST['libelle_groupe'], "text")//,
					   );
  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo));
  }
}

//Etape de passation de marches
if (isset($_GET["id_supe"])) {
  $id = ($_GET["id_supe"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."etape_marche WHERE code=%s",
                       GetSQLValueString($id, "text"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2e"))
{ //Fonction
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."etape_marche (code, intitule, description, modele_concerne, duree, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s,'$personnel', '$date')",
                GetSQLValueString($_POST['code'], "text"),
                GetSQLValueString($_POST['intitule'], "text"),
                GetSQLValueString($_POST['description'], "text"),
                GetSQLValueString(implode(',',$_POST['modele']), "text"),
                GetSQLValueString(implode(',',$_POST['duree']), "text"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo));
  }

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."etape_marche WHERE code=%s",
                         GetSQLValueString($id, "text"));
    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }

    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if (isset($_POST["MM_update"]) && !empty($_POST["MM_update"])) {
    $id = ($_POST["MM_update"]);
  	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."etape_marche SET code=%s, intitule=%s, description=%s, modele_concerne=%s, duree=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE code='$id'",
					   GetSQLValueString($_POST['code'], "text"),
					   GetSQLValueString($_POST['intitule'], "text"),
					   GetSQLValueString($_POST['description'], "text"),
					   GetSQLValueString(implode(',',$_POST['modele']), "text"),
                       GetSQLValueString(implode(',',$_POST['duree']), "text"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo));
  }
}

//Situation de passation de marches
if (isset($_GET["id_sups"])) {
  $id = ($_GET["id_sups"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."situation_marche WHERE code=%s",
                       GetSQLValueString($id, "text"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2s"))
{ //Fonction
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."situation_marche (code, intitule, etape_concerne, id_personnel, date_enregistrement) VALUES (%s, %s, %s,'$personnel', '$date')",
                    GetSQLValueString($_POST['code'], "text"),
                    GetSQLValueString($_POST['intitule'], "text"),
                    GetSQLValueString(implode(', ',$_POST['etape']), "text"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo));
  }

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."situation_marche WHERE code=%s",
                         GetSQLValueString($id, "text"));
    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }

    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if (isset($_POST["MM_update"]) && !empty($_POST["MM_update"])) {
    $id = ($_POST["MM_update"]);
  	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."situation_marche SET code=%s, intitule=%s, etape_concerne=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE code='$id'",
                    GetSQLValueString($_POST['code'], "text"),
                    GetSQLValueString($_POST['intitule'], "text"),
                    GetSQLValueString(implode(', ',$_POST['etape']), "text"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo));
  }
}
//Modele de passation de marches

if (isset($_GET["id_supsm"])) {
  $id = ($_GET["id_supsm"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."modele_marche WHERE code=%s",
                       GetSQLValueString($id, "text"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2sm"))
{ //Fonction
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
if(intval($_POST['montant_min'])>intval($_POST['montant_max']) && intval($_POST['montant_max'])>0) {$max=$_POST['montant_min']; $min=$_POST['montant_max'];} else {$max=$_POST['montant_max']; $min=$_POST['montant_min'];}
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."modele_marche (code, categorie, methode_concerne, examen, montant_min, montant_max, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s,'$personnel', '$date')",
                    GetSQLValueString($_POST['code'], "text"),
                    GetSQLValueString($_POST['categorie'], "text"),
                    GetSQLValueString(implode(', ',$_POST['methode']), "text"),
                    GetSQLValueString($_POST['examen'], "text"),
                    GetSQLValueString($min, "double"),
                    GetSQLValueString($max, "double"));
  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo));
  }

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."modele_marche WHERE code=%s",
                         GetSQLValueString($id, "text"));
    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }

    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if (isset($_POST["MM_update"]) && !empty($_POST["MM_update"])) {
    $id = ($_POST["MM_update"]);
if(intval($_POST['montant_min'])>intval($_POST['montant_max']) && intval($_POST['montant_max'])>0) {$max=$_POST['montant_min']; $min=$_POST['montant_max'];} else {$max=$_POST['montant_max']; $min=$_POST['montant_min'];}
  	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."modele_marche SET code=%s, categorie=%s, methode_concerne=%s, examen=%s, montant_min=%s, montant_max=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE code='$id'",
					       GetSQLValueString($_POST['code'], "text"),
						   GetSQLValueString($_POST['categorie'], "text"),
					       GetSQLValueString(implode(', ',$_POST['methode']), "text"),
						   GetSQLValueString($_POST['examen'], "text"),
						   GetSQLValueString($min, "double"),
						   GetSQLValueString($max, "double"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo));
  }
}
//insertion version
if (isset($_GET["id_supsv"])) {
  $id = ($_GET["id_supsv"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."version_plan_marche WHERE id_version=%s",
                       GetSQLValueString($id, "int"));
  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2sv"))
{ //Fonction
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
      $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."version_plan_marche (numero_version, date_version, id_personnel, date_enregistrement) VALUES (%s, %s,'$personnel', '$date')",
    						   GetSQLValueString($_POST['numero_version'], "text"),
    						   GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_version']))), "date"));
      try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
      }catch(Exception $e){ die(mysql_error_show_message($e)); }

      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
      header(sprintf("Location: %s", $insertGoTo));
  }
  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."version_plan_marche WHERE id_version=%s",
                         GetSQLValueString($id, "int"));
    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }

    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
  if (isset($_POST["MM_update"]) && !empty($_POST["MM_update"])) {
    $id = ($_POST["MM_update"]);
  	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."version_plan_marche SET numero_version=%s, date_version=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_version='$id'",
					      GetSQLValueString($_POST['numero_version'], "text"),
						    GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_version']))), "date"));
  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo));
  }
}

//Liste catégorie
$query_liste_categorie = "SELECT * FROM ".$database_connect_prefix."categorie_marche ORDER BY nom_categorie asc";
try{
    $liste_categorie = $pdar_connexion->prepare($query_liste_categorie);
    $liste_categorie->execute();
    $row_liste_categorie = $liste_categorie ->fetchAll();
    $totalRows_liste_categorie = $liste_categorie->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//liste methode
$query_liste_methode = "SELECT * FROM ".$database_connect_prefix."methode_marche ORDER BY sigle asc";
try{
    $liste_methode = $pdar_connexion->prepare($query_liste_methode);
    $liste_methode->execute();
    $row_liste_methode = $liste_methode ->fetchAll();
    $totalRows_liste_methode = $liste_methode->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//Etapes marchés
$query_liste_etape = "SELECT count(id_etape) as netape, modele_concerne FROM ".$database_connect_prefix."etape_marche group by modele_concerne ORDER BY code asc";
try{
    $liste_etape = $pdar_connexion->prepare($query_liste_etape);
    $liste_etape->execute();
    $row_liste_etape = $liste_etape ->fetchAll();
    $totalRows_liste_etape = $liste_etape->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$nb_etape_array = array();
if($totalRows_liste_etape>0){ foreach($row_liste_etape as $row_liste_etape){
 $nb_etape_array[$row_liste_etape["modele_concerne"]]=$row_liste_etape["netape"];
} }

//Etatpes
$query_liste_g_etape = "SELECT * FROM ".$database_connect_prefix."groupe_etape  ORDER BY num_groupe asc, code_groupe asc";
try{
    $liste_g_etape = $pdar_connexion->prepare($query_liste_g_etape);
    $liste_g_etape->execute();
    $row_liste_g_etape = $liste_g_etape ->fetchAll();
    $totalRows_liste_g_etape = $liste_g_etape->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//liste situation marché
$query_liste_situation = "SELECT * FROM ".$database_connect_prefix."situation_marche ORDER BY code asc";
try{
    $liste_situation = $pdar_connexion->prepare($query_liste_situation);
    $liste_situation->execute();
    $row_liste_situation = $liste_situation ->fetchAll();
    $totalRows_liste_situation = $liste_situation->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//Modele
$query_liste_modele = "SELECT * FROM ".$database_connect_prefix."modele_marche ORDER BY code asc";
try{
    $liste_modele = $pdar_connexion->prepare($query_liste_modele);
    $liste_modele->execute();
    $row_liste_modele = $liste_modele ->fetchAll();
    $totalRows_liste_modele = $liste_modele->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//Version
$query_liste_version = "SELECT * FROM ".$database_connect_prefix."version_plan_marche ORDER BY date_version desc";
try{
    $liste_version = $pdar_connexion->prepare($query_liste_version);
    $liste_version->execute();
    $row_liste_version = $liste_version ->fetchAll();
    $totalRows_liste_version = $liste_version->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
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
  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->
theme_folder; ?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->
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
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Types et m&eacute;thodes de passation des march&eacute;s</h4>
</div>
<div style="padding-top:20px;">

<div class="col-md-6">
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> Types de march&eacute;s</h4>
   <div class="toolbar no-padding"><div class="btn-group"><span class="btn btn-xs widget-collapse pull-right"><i class="icon-angle-down"></i></span>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
echo do_link("","","Edition des types de march&eacute;s","<i class=\"icon-plus\"> Ajouter </i>","","./","pull-right p11","get_content('new_categorie_marche.php','','modal-body_add',this.title);",1,"",$nfile);
?>

<?php } ?>
</div></div>
</div>
<div class="widget-content scroller">
 <table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" align="center" >
      <thead>
        <tr>
          <td><div align="left"><strong>Code</strong></div></td>
          <td><div align="left"><strong>Libell&eacute;</strong></div></td>
          <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
          <td align="center" width="80" ><strong>Actions</strong></td>
          <?php } ?>
        </tr>
      </thead>
      <?php if($totalRows_liste_categorie>0) {$i=0; foreach($row_liste_categorie as $row_liste_categorie){ $id = $row_liste_categorie['code_categorie']; ?>
      <tr>
        <td><div align="left"><?php echo $row_liste_categorie['code_categorie']; ?></div></td>
        <td><div align="left"><?php echo $row_liste_categorie['nom_categorie']; ?></div></td>
        <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
        <td align="center"><?php
echo do_link("","","Modifier un type de march&eacute;s","","edit","./","","get_content('new_categorie_marche.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce type ?');",0,"margin:0px 5px;",$nfile);
?>          </td>
        <?php } ?>
      </tr>
      <?php } } ?>
    </table>
    </div>
</div></div>
<div class="col-md-6">
<div class="widget box ">
<div class="widget-header">
 <h4><i class="icon-reorder"></i>M&eacute;thodes de passation</h4>
 <div class="toolbar no-padding"><div class="btn-group"><span class="btn btn-xs widget-collapse pull-right"><i class="icon-angle-down"></i></span>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
echo do_link("","","Edition des m&eacute;thodes de passation","<i class=\"icon-plus\"> Ajouter </i>","","./","pull-right p11","get_content('new_methode_passation.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div></div>
</div>
<div class="widget-content scroller">
<table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" align="center" >
      <thead>
        <tr>
          <td><div align="left"><strong>Code</strong></div></td>
          <td><div align="left"><strong>Libell&eacute;</strong></div></td>

          <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
          <td align="center" width="80" ><strong>Actions</strong></td>
          <?php } ?>
        </tr>
      </thead>
      <?php if($totalRows_liste_methode>0) {$i=0; foreach($row_liste_methode as $row_liste_methode){ $id = $row_liste_methode['sigle']; ?>
      <tr>
        <td><div align="left"><?php echo $row_liste_methode['sigle']; ?></div></td>
        <td><div align="left"><?php echo $row_liste_methode['description']; ?></div></td>

        <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
        <td align="center"><?php
echo do_link("","","Modifier une m&eacute;thode de passation","","edit","./","","get_content('new_methode_passation.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_supm=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette m&eacute;thode ?');",0,"margin:0px 5px;",$nfile);
?>          </td>
        <?php } ?>
      </tr>
      <?php } } ?>
    </table>
    </div>
</div></div>
<div class="col-md-12">
<div class="widget box ">
<div class="widget-header">
 <h4><i class="icon-reorder"></i> Mod&egrave;le de passation </h4>
 <div class="toolbar no-padding"><div class="btn-group"><span class="btn btn-xs widget-collapse pull-right"><i class="icon-angle-down"></i></span>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
echo do_link("","","Modeles de passation de march&eacute;s","<i class=\"icon-plus\"> Ajouter </i>","","./","pull-right p11","get_content('new_modele_marche.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div></div>
</div>
<div class="widget-content scroller">
<table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" align="center" >
      <thead>
        <tr>
          <td><div align="left"><strong>Code</strong></div></td>
          <td><div align="left"><strong>Type </strong></div></td>
          <td><div align="left"><strong>M&eacute;thode </strong></div></td>
          <td><div align="left"><strong>Revue (seuil)</strong>  </div></td>
          <td><strong>Etapes</strong></td>
          <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
          <td align="center" width="80" ><strong>Actions</strong></td>
          <?php } ?>
        </tr>
      </thead>
      <?php if($totalRows_liste_modele>0) {$i=0; foreach($row_liste_modele as $row_liste_modele){ $id = $row_liste_modele['code'];  ?>
      <tr>
        <td><div align="left"><?php echo $row_liste_modele['code'];  ?></div></td>
        <td ><?php echo $row_liste_modele['categorie']; ?></td>
        <td ><?php echo $row_liste_modele['methode_concerne']; ?></td>
        <td width="30%"><?php echo $row_liste_modele['examen']; ?> 
          <strong>
          <?php if(intval($row_liste_modele['montant_min'])>0 && intval($row_liste_modele['montant_max'])>0) {echo " (".$row_liste_modele['montant_min']." < et < ".$row_liste_modele['montant_max'].")";} elseif(intval($row_liste_modele['montant_min'])>0) { echo " (> ".$row_liste_modele['montant_min'].")";} elseif(intval($row_liste_modele['montant_max'])>0) { echo " (< ".$row_liste_modele['montant_max'].")";}  ?>
          </strong></td>
        <td ><div align="center"><a onclick="get_content('./liste_etape_modele_ppm.php','<?php echo "id_modele=".$row_liste_modele['id_modele']."&code_modele=".$row_liste_modele['code']; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Etapes de passation par mod&egrave;le" class="thickbox" dir="">
          <?php if(isset($nb_etape_array[$row_liste_modele["id_modele"]])) echo "(".$nb_etape_array[$row_liste_modele["id_modele"]].") "; ?>
          D&eacute;tails</a></div></td>
        <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
        <td align="center" nowrap="nowrap"><?php
echo do_link("","","Modifier un modele de passation","","edit","./","","get_content('new_modele_marche.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_supsm=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce modele ?');",0,"margin:0px 5px;",$nfile);
?>          </td>
        <?php } ?>
      </tr>
      <?php } } ?>
    </table>
    </div>
</div></div>
<div class="col-md-6">
<div class="widget box ">
<div class="widget-header">
 <h4><i class="icon-reorder"></i> Situation des march&eacute;s </h4><div class="toolbar no-padding"><div class="btn-group"><span class="btn btn-xs widget-collapse pull-right"><i class="icon-angle-down"></i></span>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
echo do_link("","","Edition des situation des march&eacute;s","<i class=\"icon-plus\"> Ajouter </i>","","./","pull-right p11","get_content('new_situation_marche.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div></div>
</div>
<div class="widget-content scroller">
<table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" align="center" >
      <thead>
        <tr>
          <td><div align="left"><strong>Code</strong></div></td>
          <td><div align="left"><strong>Libell&eacute;</strong></div></td>
          <td><div align="left"><strong>Etapes concern&eacute;es </strong></div></td>
          <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
          <td align="center" width="80" ><strong>Actions</strong></td>
          <?php } ?>
        </tr>
      </thead>
      <?php if($totalRows_liste_situation>0) {$i=0;  foreach($row_liste_situation as $row_liste_situation){ $nec=0; $id = $row_liste_situation['code'];  ?>
      <tr>
        <td><div align="left"><?php echo $row_liste_situation['code'];  ?></div></td>
        <td><div align="left"><?php echo $row_liste_situation['intitule']; ?></div></td>
        <td ><?php
	if(!empty($row_liste_situation['etape_concerne'])) $nec = explode(", ", $row_liste_situation['etape_concerne']); 	//$lcategorie=implode("','", $lc);
	if(!empty($row_liste_situation['etape_concerne'])) echo count($nec); else echo "-";//$row_liste_situation['etape_concerne'];
 ?></td>
        <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
        <td align="center"><?php
echo do_link("","","Modifier une situation","","edit","./","","get_content('new_situation_marche.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sups=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette situation ?');",0,"margin:0px 5px;",$nfile);
?>          </td>
        <?php } ?>
      </tr>
      <?php } } ?>
    </table>
    </div>
</div></div>
<div class="col-md-6">
<div class="widget box ">
<div class="widget-header">
 <h4><i class="icon-reorder"></i> Version de PPM </h4>
 <div class="toolbar no-padding"><div class="btn-group"><span class="btn btn-xs widget-collapse pull-right"><i class="icon-angle-down"></i></span>
<?php
if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) {
echo do_link("","","Edition des version de PPM","<i class=\"icon-plus\"> Ajouter </i>","","./","pull-right p11","get_content('new_version_ppm.php','','modal-body_add',this.title);",1,"",$nfile);
}
?>
</div></div>
</div>
<div class="widget-content">
<table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" align="center" >
      <thead>
        <tr>
          <th><div align="left"><strong>Date</strong></div></th>
          <th><div align="center"><strong>Version</strong></div></th>
          <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
          <th align="center" width="80" ><strong>Actions</strong></th>
          <?php } ?>
        </tr>
      </thead>
      <?php if($totalRows_liste_version>0) {$i=0; foreach($row_liste_version as $row_liste_version){ $id = $row_liste_version['id_version'];  ?>
      <tr>
        <td><div align="left"><?php echo implode('/',array_reverse(explode('-',$row_liste_version['date_version'])));  ?></div></td>
        <td><div align="center"><?php echo $row_liste_version['numero_version']; ?></div></td>
        <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
        <td align="center"><?php  if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) {
echo do_link("","","Modifier une version de passation","","edit","./","","get_content('new_version_ppm.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);
echo do_link("",$_SERVER['PHP_SELF']."?id_supsv=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette version ?');",0,"margin:0px 5px;",$nfile);
}
?>          </td>
        <?php } ?>
      </tr>
      <?php } } ?>
    </table>
    </div>
</div></div>
<div class="col-md-12">
<div class="widget box ">
<div class="widget-header">
 <h4><i class="icon-reorder"></i> Listes des &eacute;tapes de passation </h4>
 <div class="toolbar no-padding"><div class="btn-group"><span class="btn btn-xs widget-collapse pull-right"><i class="icon-angle-down"></i></span>
<?php
if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) {
echo do_link("","","Edition des étapes de PPM","<i class=\"icon-plus\"> Ajouter </i>","","./","pull-right p11","get_content('new_groupe_etape.php','','modal-body_add',this.title);",1,"",$nfile);
}
?>
</div></div>
</div>
<div class="widget-content">
<table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" align="center" >
      <thead>
        <tr>
          <th><div align="left"><strong>Type</strong></div></th>
          <th>N°</th>
          <th><div align="left"><strong>Code</strong></div></th>
          <th><div align="center"><strong>Etape</strong></div></th>
          <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
          <th align="center" width="80" ><strong>Actions</strong></th>
          <?php } ?>
        </tr>
      </thead>
      <?php if($totalRows_liste_g_etape>0) {$i=0; foreach($row_liste_g_etape as $row_liste_g_etape){ $id = $row_liste_g_etape['id_groupe'];  ?>
      <tr>
        <td><?php echo $row_liste_g_etape['categorie_groupe']; ?></td>
        <td><?php echo $row_liste_g_etape['num_groupe']; ?></td>
        <td><div align="left"><?php echo $row_liste_g_etape['code_groupe']; ?></div></td>
        <td><div align="left"><?php echo $row_liste_g_etape['libelle_groupe']; ?></div></td>
        <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
        <td align="center"><?php  if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) {
echo do_link("","","Modifier une etape de passation","","edit","./","","get_content('new_groupe_etape.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);
echo do_link("",$_SERVER['PHP_SELF']."?id_supge=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette etape ?');",0,"margin:0px 5px;",$nfile);
}
?>          </td>
        <?php } ?>
      </tr>
      <?php } } ?>
    </table>
    </div>
</div></div>


<div class="clear h0">&nbsp;</div>

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