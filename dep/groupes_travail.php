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

$query_liste_users = "SELECT * FROM ".$database_connect_prefix."personnel ";
  try{
  $liste_users = $pdar_connexion->prepare($query_liste_users);
  $liste_users->execute();
  $row_liste_users = $liste_users ->fetchAll();
  $totalRows_liste_users = $liste_users->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $users_array=array();
if($totalRows_liste_users>0){ foreach($row_liste_users as $row_liste_users){
$users_array[$row_liste_users['N']]=$row_liste_users['prenom']." ".$row_liste_users['nom']." (".(isset($tableau_Partenaire[$row_liste_users['partenaire']])?$tableau_Partenaire[$row_liste_users['partenaire']]:$row_liste_users['partenaire']).")";
} }


    //autre info
        $query_liste_partenaire = "SELECT * FROM ".$database_connect_prefix."acteur order by nom_acteur ";
		  try{
  $liste_partenaire = $pdar_connexion->prepare($query_liste_partenaire);
  $liste_partenaire->execute();
  $row_liste_partenaire = $liste_partenaire ->fetchAll();
  $totalRows_liste_partenaire = $liste_partenaire->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
          $tableau_Partenaire=$tableau_Partenaire_Desc=array();
        if($totalRows_liste_partenaire>0){ foreach($row_liste_partenaire as $row_liste_partenaire){
        $tableau_Partenaire[$row_liste_partenaire['id_acteur']]=$row_liste_partenaire['nom_acteur'];
        $tableau_Partenaire_Desc[$row_liste_partenaire['id_acteur']]=strip_tags($row_liste_partenaire['definition']);
        } }
        //Thematiques
       
        $query_structure = "SELECT * FROM ".$database_connect_prefix."domaine_activite order by code_domaine ";
		 try{
  $structure = $pdar_connexion->prepare($query_structure);
  $structure->execute();
  $row_structure = $structure ->fetchAll();
  $totalRows_structure = $structure->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
        $tableau_Thematique=$tableau_Thematique_Desc=array();
        if($totalRows_structure>0){ foreach($row_structure as $row_structure){
        $tableau_Thematique[$row_structure['id_domaine']]=$row_structure['nom_domaine'];
        $tableau_Thematique_Desc[$row_structure['id_domaine']]=strip_tags($row_structure['nom_domaine']);
        } }
		
if (isset($_GET["id_sup"])) {
  $id = $_GET["id_sup"];
  //Prevar
 
  $query_clp = "SELECT 'TO' as Type, email, CONCAT(prenom,' ',nom) as NOM FROM ".$database_connect_prefix."personnel WHERE FIND_IN_SET(N, (SELECT secretaire FROM ".$database_connect_prefix."groupes_travail WHERE id_groupes_travail=".GetSQLValueString($id, "text").")) AND email IS NOT NULL UNION SELECT 'CC' as Type, email_partenaire, nom_acteur as NOM FROM ".$database_connect_prefix."acteur WHERE FIND_IN_SET(id_acteur, (SELECT partenaire FROM ".$database_connect_prefix."groupes_travail WHERE id_groupes_travail=".GetSQLValueString($id, "text").")) AND email_partenaire IS NOT NULL";  
  	 try{
  $clp = $pdar_connexion->prepare($query_clp);
  $clp->execute();
  $row_clp = $clp ->fetchAll();
  $totalRows_clp = $clp->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
  $email_to = $email_cc = array();
  if($totalRows_clp>0)
  {
    foreach($row_clp as $row_clp){ if($row_clp["Type"]=="TO" && filter_var(trim($row_clp["email"]), FILTER_VALIDATE_EMAIL)) $email_to[$row_clp["email"]]=$row_clp["NOM"]; elseif(filter_var(trim($row_clp["email"]), FILTER_VALIDATE_EMAIL)) $email_cc[$row_clp["email"]]=$row_clp["NOM"]; }while($row_clp = mysql_fetch_assoc($clp));
    //info groupes
   
    $query_liste_texte_accueil = "SELECT * FROM ".$database_connect_prefix."groupes_travail WHERE id_groupes_travail=".GetSQLValueString($id, "text");
	 	 try{
  $liste_texte_accueil = $pdar_connexion->prepare($query_liste_texte_accueil);
  $liste_texte_accueil->execute();
  $row_liste_texte_accueil = $liste_texte_accueil ->fetch();
  $totalRows_liste_texte_accueil = $liste_texte_accueil->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

    }
  //Fin
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."groupes_travail WHERE id_groupes_travail=%s",
                       GetSQLValueString($id, "text"));

   try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  if($Result1 && $totalRows_clp>0 && $totalRows_liste_texte_accueil>0)
  {
    if(count($email_to)>0 || count($email_cc)>0)
    {
      if(count($email_to)<=0 && count($email_cc)>0) { $email_to = $email_cc; unset($email_cc); }
      $lien = $config->lien;
      $_GET["titre"] = "Suppression d'un groupe de travail - PNIA";
      $partenaire = "";
      $p = (!empty($row_liste_texte_accueil["partenaire"]))?explode(',',$row_liste_texte_accueil["partenaire"]):array(); if(count($p)>0){ $partenaire = "<ul style='margin:0px!important;'>"; foreach($p as $pp) $partenaire .= isset($tableau_Partenaire[$pp])?"<li title=\"".(isset($tableau_Partenaire_Desc[$pp])?$tableau_Partenaire_Desc[$pp]:'')."\">".$tableau_Partenaire[$pp]."</li>":""; $partenaire .= "</ul>"; }
      $thematique = "";
      $t = (!empty($row_liste_texte_accueil["thematiques"]))?explode(',',$row_liste_texte_accueil["thematiques"]):array(); if(count($t)>0){ $thematique = "<ul style='margin:0px!important;'>"; foreach($t as $tt) $thematique .= isset($tableau_Thematique[$tt])?"<li title=\"".(isset($tableau_Thematique_Desc[$tt])?$tableau_Thematique_Desc[$tt]:'')."\">".$tableau_Thematique[$tt]."</li>":""; $thematique .= "</ul>"; }
      $_GET["message"] = "<h3>Chers utilisateurs/partenaires, <h3><h5>Dans le cadre de votre collaboration avec PNIA, le Groupe de travail suivant auquel vous participez viens d'&ecirc;tre supprim&eacute; : </h5><div>Code : <b>".$row_liste_texte_accueil['code_groupes_travail']."</b></div><div>Nom : <b>".$row_liste_texte_accueil['nom_groupes_travail']."</b></div><div>Date de cr&eacute;ation : <b>".(!empty($row_liste_texte_accueil['date_creation'])?date_reg($row_liste_texte_accueil['date_creation'],"/"):"-")."</b></div><div>Statut du groupe : <b>".($row_liste_texte_accueil['actif']==0?"Actif":"Inactif")."</b></div><div>Secr&eacute;taire Technique : <b>".((isset($users_array[$row_liste_texte_accueil["secretaire"]]))?$users_array[$row_liste_texte_accueil["secretaire"]]:"-")."</b></div><div>Th&eacute;matiques concern&eacute;es : <b>".$thematique."</b></div><div>Partenaires : <b>".$partenaire."</b></div>";
      include("./phpmailer/mail_notification.php");
      if (!isset($msg_sent) || $msg_sent!=1) $insertGoTo .= "&send=no"; else $insertGoTo .= "&send=ok";
      //header(sprintf("Location: %s", $insertGoTo)); exit;
    }
  }
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $personnel=$_SESSION['clp_id'];
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."groupes_travail (code_groupes_travail,nom_groupes_travail, date_creation, partenaire, thematiques, secretaire, actif, description, id_personnel) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, '$personnel')",
                        GetSQLValueString($_POST['code_groupes_travail'], "text"),
                        GetSQLValueString($_POST['nom_groupes_travail'], "text"),
                        GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_creation']))), "date"),
					    GetSQLValueString(implode(',',$_POST["partenaire"]), "text"),
                        GetSQLValueString(implode(',',$_POST["thematiques"]), "text"),
                        GetSQLValueString($_POST['secretaire'], "text"),
                        GetSQLValueString($_POST['actif'], "int"),
  					    GetSQLValueString($_POST['description'], "text"));

     try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
    if($Result1) $insertGoTo = $page."?insert=ok"; else $insertGoTo = $page."&insert=no";
    if($Result1)
    {
		  $id = $db->lastInsertId();
      //$id = mysql_insert_id();
     $query_clp = "SELECT 'TO' as Type, email, CONCAT(prenom,' ',nom) as NOM FROM ".$database_connect_prefix."personnel WHERE FIND_IN_SET(N, (SELECT secretaire FROM ".$database_connect_prefix."groupes_travail WHERE id_groupes_travail=".GetSQLValueString($id, "text").")) AND email IS NOT NULL UNION SELECT 'CC' as Type, email_partenaire, nom_acteur as NOM FROM ".$database_connect_prefix."acteur WHERE FIND_IN_SET(id_acteur, (SELECT partenaire FROM ".$database_connect_prefix."groupes_travail WHERE id_groupes_travail=".GetSQLValueString($id, "text").")) AND email_partenaire IS NOT NULL";  
  	 try{
  $clp = $pdar_connexion->prepare($query_clp);
  $clp->execute();
  $row_clp = $clp ->fetchAll();
  $totalRows_clp = $clp->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $email_to = $email_cc = array();
      if($totalRows_clp>0)
      {
        foreach($row_clp as $row_clp){ if($row_clp["Type"]=="TO" && filter_var(trim($row_clp["email"]), FILTER_VALIDATE_EMAIL)) $email_to[$row_clp["email"]]=$row_clp["NOM"]; elseif(filter_var(trim($row_clp["email"]), FILTER_VALIDATE_EMAIL)) $email_cc[$row_clp["email"]]=$row_clp["NOM"]; }while($row_clp = mysql_fetch_assoc($clp));
        //info groupes
         $query_liste_texte_accueil = "SELECT * FROM ".$database_connect_prefix."groupes_travail WHERE id_groupes_travail=".GetSQLValueString($id, "text");
	 	 try{
  $liste_texte_accueil = $pdar_connexion->prepare($query_liste_texte_accueil);
  $liste_texte_accueil->execute();
  $row_liste_texte_accueil = $liste_texte_accueil ->fetch();
  $totalRows_liste_texte_accueil = $liste_texte_accueil->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    

           }
      //Fin
    }
    if($Result1 && $totalRows_clp>0 && $totalRows_liste_texte_accueil>0)
    {
      if(count($email_to)>0 || count($email_cc)>0)
      {
        if(count($email_to)<=0 && count($email_cc)>0) { $email_to = $email_cc; unset($email_cc); }
        $lien = $config->lien;
        $_GET["titre"] = "Ajout d'un groupe de travail - PNIA";
        $partenaire = "";
        $p = (!empty($row_liste_texte_accueil["partenaire"]))?explode(',',$row_liste_texte_accueil["partenaire"]):array(); if(count($p)>0){ $partenaire = "<ul style='margin-bottom:0px!important;'>"; foreach($p as $pp) $partenaire .= isset($tableau_Partenaire[$pp])?"<li title=\"".(isset($tableau_Partenaire_Desc[$pp])?$tableau_Partenaire_Desc[$pp]:'')."\">".$tableau_Partenaire[$pp]."</li>":""; $partenaire .= "</ul>"; }
        $thematique = "";
        $t = (!empty($row_liste_texte_accueil["thematiques"]))?explode(',',$row_liste_texte_accueil["thematiques"]):array(); if(count($t)>0){ $thematique = "<ul style='margin:0px!important;'>"; foreach($t as $tt) $thematique .= isset($tableau_Thematique[$tt])?"<li title=\"".(isset($tableau_Thematique_Desc[$tt])?$tableau_Thematique_Desc[$tt]:'')."\">".$tableau_Thematique[$tt]."</li>":""; $thematique .= "</ul>"; }
        $_GET["message"] = "<h3>Chers utilisateurs/partenaires, <h3><h5>Dans le cadre de votre collaboration avec PNIA, vous venez d'&ecirc;tre affect&eacute; &agrave; un nouveau Groupe de travail : </h5><div>Code : <b>".$row_liste_texte_accueil['code_groupes_travail']."</b></div><div>Nom : <b>".$row_liste_texte_accueil['nom_groupes_travail']."</b></div><div>Date de cr&eacute;ation : <b>".(!empty($row_liste_texte_accueil['date_creation'])?date_reg($row_liste_texte_accueil['date_creation'],"/"):"-")."</b></div><div>Statut du groupe : <b>".($row_liste_texte_accueil['actif']==0?"Actif":"Inactif")."</b></div><div>Secr&eacute;taire Technique : <b>".((isset($users_array[$row_liste_texte_accueil["secretaire"]]))?$users_array[$row_liste_texte_accueil["secretaire"]]:"-")."</b></div><div>Partenaires : <b>".$partenaire."</b></div>";
        include("./phpmailer/mail_notification.php");
        if (!isset($msg_sent) || $msg_sent!=1) $insertGoTo .= "&send=no"; else $insertGoTo .= "&send=ok";
        //header(sprintf("Location: %s", $insertGoTo)); exit;
      }
    }
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }                

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
      $id = $_POST["MM_delete"];
      //Prevar
 $query_clp = "SELECT 'TO' as Type, email, CONCAT(prenom,' ',nom) as NOM FROM ".$database_connect_prefix."personnel WHERE FIND_IN_SET(N, (SELECT secretaire FROM ".$database_connect_prefix."groupes_travail WHERE id_groupes_travail=".GetSQLValueString($id, "text").")) AND email IS NOT NULL UNION SELECT 'CC' as Type, email_partenaire, nom_acteur as NOM FROM ".$database_connect_prefix."acteur WHERE FIND_IN_SET(id_acteur, (SELECT partenaire FROM ".$database_connect_prefix."groupes_travail WHERE id_groupes_travail=".GetSQLValueString($id, "text").")) AND email_partenaire IS NOT NULL";  
  	 try{
  $clp = $pdar_connexion->prepare($query_clp);
  $clp->execute();
  $row_clp = $clp ->fetchAll();
  $totalRows_clp = $clp->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $email_to = $email_cc = array();
  if($totalRows_clp>0)
  {
   foreach($row_clp as $row_clp){ if($row_clp["Type"]=="TO" && filter_var(trim($row_clp["email"]), FILTER_VALIDATE_EMAIL)) $email_to[$row_clp["email"]]=$row_clp["NOM"]; elseif(filter_var(trim($row_clp["email"]), FILTER_VALIDATE_EMAIL)) $email_cc[$row_clp["email"]]=$row_clp["NOM"]; }
    //info groupes

         $query_liste_texte_accueil = "SELECT * FROM ".$database_connect_prefix."groupes_travail WHERE id_groupes_travail=".GetSQLValueString($id, "text");
	 	 try{
  $liste_texte_accueil = $pdar_connexion->prepare($query_liste_texte_accueil);
  $liste_texte_accueil->execute();
  $row_liste_texte_accueil = $liste_texte_accueil ->fetch();
  $totalRows_liste_texte_accueil = $liste_texte_accueil->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
 
  }
  //Fin
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."groupes_travail WHERE id_groupes_travail=%s",
                           GetSQLValueString($id, "text"));

       try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
 $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      if($Result1 && $totalRows_clp>0 && $totalRows_liste_texte_accueil>0)
      {
        if(count($email_to)>0 || count($email_cc)>0)
        {
          if(count($email_to)<=0 && count($email_cc)>0) { $email_to = $email_cc; unset($email_cc); }
          $lien = $config->lien;
          $_GET["titre"] = "Suppression d'un groupe de travail - PNIA";
          $partenaire = "";
          $p = (!empty($row_liste_texte_accueil["partenaire"]))?explode(',',$row_liste_texte_accueil["partenaire"]):array(); if(count($p)>0){ $partenaire = "<ul style='margin:0px!important;'>"; foreach($p as $pp) $partenaire .= isset($tableau_Partenaire[$pp])?"<li title=\"".(isset($tableau_Partenaire_Desc[$pp])?$tableau_Partenaire_Desc[$pp]:'')."\">".$tableau_Partenaire[$pp]."</li>":""; $partenaire .= "</ul>"; }
          $thematique = "";
          $t = (!empty($row_liste_texte_accueil["thematiques"]))?explode(',',$row_liste_texte_accueil["thematiques"]):array(); if(count($t)>0){ $thematique = "<ul style='margin:0px!important;'>"; foreach($t as $tt) $thematique .= isset($tableau_Thematique[$tt])?"<li title=\"".(isset($tableau_Thematique_Desc[$tt])?$tableau_Thematique_Desc[$tt]:'')."\">".$tableau_Thematique[$tt]."</li>":""; $thematique .= "</ul>"; }
          $_GET["message"] = "<h3>Chers utilisateurs/partenaires, <h3><h5>Dans le cadre de votre collaboration avec PNIA, le Groupe de travail suivant auquel vous participez viens d'&ecirc;tre supprim&eacute; : </h5><div>Code : <b>".$row_liste_texte_accueil['code_groupes_travail']."</b></div><div>Nom : <b>".$row_liste_texte_accueil['nom_groupes_travail']."</b></div><div>Date de cr&eacute;ation : <b>".(!empty($row_liste_texte_accueil['date_creation'])?date_reg($row_liste_texte_accueil['date_creation'],"/"):"-")."</b></div><div>Statut du groupe : <b>".($row_liste_texte_accueil['actif']==0?"Actif":"Inactif")."</b></div><div>Secr&eacute;taire Technique : <b>".((isset($users_array[$row_liste_texte_accueil["secretaire"]]))?$users_array[$row_liste_texte_accueil["secretaire"]]:"-")."</b></div><div>Th&eacute;matiques concern&eacute;es : <b>".$thematique."</b></div><div>Partenaires : <b>".$partenaire."</b></div>";
          include("./phpmailer/mail_notification.php");
          if (!isset($msg_sent) || $msg_sent!=1) $insertGoTo .= "&send=no"; else $insertGoTo .= "&send=ok";
          //header(sprintf("Location: %s", $insertGoTo)); exit;
        }
      }
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];  $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."groupes_travail SET code_groupes_travail=%s, nom_groupes_travail=%s, date_creation=%s, partenaire=%s, thematiques=%s, secretaire=%s, actif=%s, description=%s, modifier_par='$personnel', modifier_le='$date' WHERE id_groupes_travail=%s",
                        GetSQLValueString($_POST['code_groupes_travail'], "text"),
                        GetSQLValueString($_POST['nom_groupes_travail'], "text"),
                        GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_creation']))), "date"),
                        GetSQLValueString(implode(',',$_POST["partenaire"]), "text"),
                        GetSQLValueString(implode(',',$_POST["thematiques"]), "text"),
                        GetSQLValueString($_POST['secretaire'], "text"),
                        GetSQLValueString($_POST['actif'], "int"),
  					    GetSQLValueString($_POST['description'], "text"),
                        GetSQLValueString($id, "text"));

     try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

    if($Result1) $insertGoTo = $page."?update=ok"; else $insertGoTo = $page."&update=no";
    if($Result1)
    {
        //Prevar
  $query_clp = "SELECT 'TO' as Type, email, CONCAT(prenom,' ',nom) as NOM FROM ".$database_connect_prefix."personnel WHERE FIND_IN_SET(N, (SELECT secretaire FROM ".$database_connect_prefix."groupes_travail WHERE id_groupes_travail=".GetSQLValueString($id, "text").")) AND email IS NOT NULL UNION SELECT 'CC' as Type, email_partenaire, nom_acteur as NOM FROM ".$database_connect_prefix."acteur WHERE FIND_IN_SET(id_acteur, (SELECT partenaire FROM ".$database_connect_prefix."groupes_travail WHERE id_groupes_travail=".GetSQLValueString($id, "text").")) AND email_partenaire IS NOT NULL";  
  	 try{
  $clp = $pdar_connexion->prepare($query_clp);
  $clp->execute();
  $row_clp = $clp ->fetchAll();
  $totalRows_clp = $clp->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $email_to = $email_cc = array();
  if($totalRows_clp>0)
  {
   foreach($row_clp as $row_clp){ if($row_clp["Type"]=="TO" && filter_var(trim($row_clp["email"]), FILTER_VALIDATE_EMAIL)) $email_to[$row_clp["email"]]=$row_clp["NOM"]; elseif(filter_var(trim($row_clp["email"]), FILTER_VALIDATE_EMAIL)) $email_cc[$row_clp["email"]]=$row_clp["NOM"]; }
    //info groupes
 $query_liste_texte_accueil = "SELECT * FROM ".$database_connect_prefix."groupes_travail WHERE id_groupes_travail=".GetSQLValueString($id, "text");
	 	 try{
  $liste_texte_accueil = $pdar_connexion->prepare($query_liste_texte_accueil);
  $liste_texte_accueil->execute();
  $row_liste_texte_accueil = $liste_texte_accueil ->fetch();
  $totalRows_liste_texte_accueil = $liste_texte_accueil->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  }
  //Fin
    if($totalRows_clp>0 && $totalRows_liste_texte_accueil>0)
      {
        if(count($email_to)>0 || count($email_cc)>0)
        {
          if(count($email_to)<=0 && count($email_cc)>0) { $email_to = $email_cc; unset($email_cc); }
          $lien = $config->lien;
          $_GET["titre"] = "Modification d'un groupe de travail - PNIA";
          $partenaire = "";
          $p = (!empty($row_liste_texte_accueil["partenaire"]))?explode(',',$row_liste_texte_accueil["partenaire"]):array(); if(count($p)>0){ $partenaire = "<ul style='margin:0px!important;'>"; foreach($p as $pp) $partenaire .= isset($tableau_Partenaire[$pp])?"<li title=\"".(isset($tableau_Partenaire_Desc[$pp])?$tableau_Partenaire_Desc[$pp]:'')."\">".$tableau_Partenaire[$pp]."</li>":""; $partenaire .= "</ul>"; }
          $thematique = "";
          $t = (!empty($row_liste_texte_accueil["thematiques"]))?explode(',',$row_liste_texte_accueil["thematiques"]):array(); if(count($t)>0){ $thematique = "<ul style='margin:0px!important;'>"; foreach($t as $tt) $thematique .= isset($tableau_Thematique[$tt])?"<li title=\"".(isset($tableau_Thematique_Desc[$tt])?$tableau_Thematique_Desc[$tt]:'')."\">".$tableau_Thematique[$tt]."</li>":""; $thematique .= "</ul>"; }
          $_GET["message"] = "<h3>Chers utilisateurs/partenaires, <h3><h5>Dans le cadre de votre collaboration avec PNIA, le Groupe de travail suivant auquel vous participez viens d'&ecirc;tre modifi&eacute; : </h5><div>Code : <b>".$row_liste_texte_accueil['code_groupes_travail']."</b></div><div>Nom : <b>".$row_liste_texte_accueil['nom_groupes_travail']."</b></div><div>Date de cr&eacute;ation : <b>".(!empty($row_liste_texte_accueil['date_creation'])?date_reg($row_liste_texte_accueil['date_creation'],"/"):"-")."</b></div><div>Statut du groupe : <b>".($row_liste_texte_accueil['actif']==0?"Actif":"Inactif")."</b></div><div>Secr&eacute;taire Technique : <b>".((isset($users_array[$row_liste_texte_accueil["secretaire"]]))?$users_array[$row_liste_texte_accueil["secretaire"]]:"-")."</b></div><div>Th&eacute;matiques concern&eacute;es : <b>".$thematique."</b></div><div>Partenaires : <b>".$partenaire."</b></div>";
          include("./phpmailer/mail_notification.php");
          if (!isset($msg_sent) || $msg_sent!=1) $insertGoTo .= "&send=no"; else $insertGoTo .= "&send=ok";
          //header(sprintf("Location: %s", $insertGoTo)); exit;
        }
      } }
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}


$query_liste_groupes_travail = "SELECT * FROM ".$database_connect_prefix."groupes_travail order by nom_groupes_travail ";
  try{
  $liste_groupes_travail = $pdar_connexion->prepare($query_liste_groupes_travail);
  $liste_groupes_travail->execute();
  $row_liste_groupes_travail = $liste_groupes_travail ->fetchAll();
  $totalRows_liste_groupes_travail = $liste_groupes_travail->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }


$query_liste_partenaire = "SELECT * FROM ".$database_connect_prefix."acteur ";
  try{
  $liste_partenaire = $pdar_connexion->prepare($query_liste_partenaire);
  $liste_partenaire->execute();
  $row_liste_partenaire = $liste_partenaire ->fetchAll();
  $totalRows_liste_partenaire = $liste_partenaire->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
$tableau_Partenaire=$tableau_Partenaire_Desc=array();
if($totalRows_liste_partenaire>0){ foreach($row_liste_partenaire as $row_liste_partenaire){
$tableau_Partenaire[$row_liste_partenaire['id_acteur']]=$row_liste_partenaire['nom_acteur'];
$tableau_Partenaire_Desc[$row_liste_partenaire['id_acteur']]=strip_tags($row_liste_partenaire['definition']);
} }

$query_liste_thematiques = "SELECT * FROM ".$database_connect_prefix."domaine_activite ";
  try{
  $liste_thematiques = $pdar_connexion->prepare($query_liste_thematiques);
  $liste_thematiques->execute();
  $row_liste_thematiques = $liste_thematiques ->fetchAll();
  $totalRows_liste_thematiques = $liste_thematiques->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

$tableau_Thematique=$tableau_Thematique_Desc=array();
if($totalRows_liste_thematiques>0){ foreach($row_liste_thematiques as $row_liste_thematiques){
$tableau_Thematique[$row_liste_thematiques['id_domaine']]=$row_liste_thematiques['nom_domaine'];
$tableau_Thematique_Desc[$row_liste_thematiques['id_domaine']]=strip_tags($row_liste_thematiques['description_domaine']);
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
<?php include_once("modal_add.php"); ?>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
.menu_head {
  padding: 5px; cursor: pointer; background-color: #060; color: #FFF;
}
</style>
<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Groupes de travail</h4>
<?php if(isset($_SESSION['clp_id']) && $_SESSION['clp_id']=="admin"){ ?>
<?php
echo do_link("","","Ajout de groupe de travail","<i class=\"icon-plus\"> Nouveau groupe de travail </i>","simple","./","pull-right p11","get_content('new_groupes_travail.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div>

<div class="widget-content">
<table class="table table-striped table-bordered table-hover table-responsive table-colvis datatable dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Code</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Date de cr&eacute;ation</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Nom du Groupe</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"
>Partenaires concern&eacute;s</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Th&eacute;matiques concern&eacute;es</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"
>Secr&eacute;taire Technique</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Statut</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Description</th>
<?php if(isset($_SESSION['clp_id']) && $_SESSION['clp_id']=="admin"){ ?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="90">Actions</th>
<?php } ?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php if($totalRows_liste_groupes_travail>0) { $i=0; foreach($row_liste_groupes_travail as $row_liste_groupes_travail){ $id = $row_liste_groupes_travail['id_groupes_travail']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo $row_liste_groupes_travail['code_groupes_travail']; ?></td>
<td class=" "><?php echo !empty($row_liste_groupes_travail['date_creation'])?date_reg($row_liste_groupes_travail['date_creation'],"/"):"-"; ?></td>
<td class=" "><?php echo $row_liste_groupes_travail['nom_groupes_travail']; ?></td>
<td class=" "><?php $p = (!empty($row_liste_groupes_travail["partenaire"]))?explode(',',$row_liste_groupes_travail["partenaire"]):array(); if(count($p)>0){ echo "<ul style='margin-bottom:0px!important;padding-left: 15px!important;'>"; foreach($p as $pp) echo isset($tableau_Partenaire[$pp])?"<li title=\"".(isset($tableau_Partenaire_Desc[$pp])?$tableau_Partenaire_Desc[$pp]:'')."\">".$tableau_Partenaire[$pp]."</li>":""; echo "</ul>"; } ?></td>
<td class=" "><?php $p = (!empty($row_liste_groupes_travail["thematiques"]))?explode(',',$row_liste_groupes_travail["thematiques"]):array(); if(count($p)>0){ echo "<ul style='margin-bottom:0px!important;padding-left: 15px!important;'>"; foreach($p as $pp) echo isset($tableau_Thematique[$pp])?"<li title=\"".(isset($tableau_Thematique_Desc[$pp])?$tableau_Thematique_Desc[$pp]:'')."\">".$tableau_Thematique[$pp]."</li>":""; echo "</ul>"; } ?></td>
<td class=" " title=""><?php echo (isset($users_array[$row_liste_groupes_travail["secretaire"]]))?$users_array[$row_liste_groupes_travail["secretaire"]]:$row_liste_groupes_travail["secretaire"]; ?></td>
<td class=" "><?php echo $row_liste_groupes_travail['actif']==0?"Actif":"Inactif"; ?></td>
<td class=" "><?php echo $row_liste_groupes_travail['description']; ?></td>
<?php if(isset($_SESSION['clp_id']) && $_SESSION['clp_id']=="admin"){ ?>
<td class=" " align="center">
<?php
echo do_link("","","Modifier groupe de travail ".$row_liste_groupes_travail['nom_groupes_travail'],"","edit","./","","get_content('new_groupes_travail.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce groupe de travail ".$row_liste_groupes_travail['nom_groupes_travail']."');",0,"margin:0px 5px;",$nfile);
?></td>
<?php } ?>
</tr>
<?php } } ?>
</tbody></table>
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