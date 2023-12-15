<?php
   ///////////////////////////////////////////////
  /*                 SSE                       */
 /*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////

  session_start();
  include_once 'system/configuration.php';

  if(isset($_POST['identifiant']) && isset($_POST['password']))
  {
    $l=htmlentities($_POST['identifiant']); $p=md5(htmlentities($_POST['password']));
    $pss=htmlentities($_POST['password']); //$s=htmlentities($_POST['structure']);
    $page = (isset($_POST['page']))?$_POST['page']:"./";

    $query_clp = "SELECT P.*, U.nom_ugl as nom_structure, U.code_ugl as code_structure FROM ".$database_connect_prefix."personnel P, ".$database_connect_prefix."ugl U WHERE P.id_personnel="."'$l' and P.structure=U.code_ugl";
    try{
        $clp = $pdar_connexion->prepare($query_clp);
        $clp->execute();
        $row_clp = $clp ->fetch();
        $totalRows_clp = $clp->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    if($totalRows_clp==1)
    {
      $date=date("Y-m-d H:i:s"); $personnel=$row_clp['N'];
      $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."connecter (personnel, session_id, date_connexion) VALUES ('$personnel', '".session_id()."', '$date')");
        try{
            $Result1 = $pdar_connexion->prepare($insertSQL);
            $Result1->execute();
        }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $projet = explode("|",$row_clp['projet']);

      //Se souvenir
      if(!isset($_POST['remember']))
      $_SESSION["clp_remember"] = 1;
      $_SESSION['clp_loggedAt'] = time();
      //Fin
      $_SESSION["clp_id"] = htmlentities($row_clp['id_personnel']);
      $_SESSION["clp_departement"] = htmlentities($row_clp['departement']);
      $_SESSION["clp_nom"] = htmlentities($row_clp['nom']);
      $_SESSION["clp_prenom"] = htmlentities($row_clp['prenom']);
      $_SESSION["clp_fonction"] = htmlentities($row_clp['fonction']);
      $_SESSION["clp_mail"] = htmlentities($row_clp['email']);
      $_SESSION["clp_contact"] = htmlentities($row_clp['contact']);
      $_SESSION["clp_structure"] = htmlentities($row_clp['code_structure']);
      $_SESSION["clp_structure_nom"] = htmlentities($row_clp['nom_structure']);
      $_SESSION["clp_structure_sigle"] = htmlentities($row_clp['sigle']);
      $_SESSION["clp_niveau"] = htmlentities($row_clp['niveau']);
      $_SESSION["clp_n"] = htmlentities($row_clp['N']);
      $_SESSION["clp_zone"] = htmlentities($row_clp['region']);
      $_SESSION["clp_user_projet"] = $row_clp['projet'];
      $_SESSION["clp_projet"] = (!empty($row_clp['projet_active']))?$row_clp['projet_active']:$projet[0];
      //$_SESSION["clp_where"] = " structure='".(!empty($_SESSION['clp_structure'])?$_SESSION['clp_structure']:0)."' and projet='".(!empty($_SESSION['clp_projet'])?$_SESSION['clp_projet']:0)."'";
      $_SESSION["clp_where"] = "projet='".$_SESSION['clp_projet']."'";
      if(empty($row_clp['projet_active']))
      {
        $id=$_SESSION['clp_n'];
        $insertSQL = sprintf("UPDATE ".$database_connect_prefix."personnel SET projet_active=%s WHERE N=%s",
                             GetSQLValueString($projet[0], "text"),
                             GetSQLValueString($id, "int"));
        try{
            $Result1 = $pdar_connexion->prepare($insertSQL);
            $Result1->execute();
        }catch(Exception $e){ die(mysql_error_show_message($e)); }
      }

      //if($row_clp['projet_active']!="")
      //{
        $mySqlQuery = "SELECT * FROM ".$database_connect_prefix."projet where code_projet='".$row_clp['projet_active']."'";
        try{
            $qh = $pdar_connexion->prepare($mySqlQuery);
            $qh->execute();
            $data = $qh ->fetch();
            $totalRows_clp = $qh->rowCount();
        }catch(Exception $e){ die(mysql_error_show_message($e)); }
        $_SESSION["clp_projet_sigle"] = ($data['sigle_projet']);
        $_SESSION["clp_projet_nom"] = ($data['intitule_projet']);;
        $_SESSION["annee_debut_projet"] = ($data['annee_debut']);
        $_SESSION["annee_fin_projet"] = ($data['annee_fin']);
        $_SESSION["clp_projet_ugl"] = ($data['ugl']);

        $mySqlQuery = "SELECT code_ugl FROM ".$database_connect_prefix."ugl ";
        try{
            $qh = $pdar_connexion->prepare($mySqlQuery);
            $qh->execute();
            $data = $qh ->fetchAll();
            $totalRows_clp = $qh->rowCount();
        }catch(Exception $e){ die(mysql_error_show_message($e)); }
        $ugl = "";
        if($totalRows_clp>0)
        {
          foreach($data as $data){ $ugl.=$data["code_ugl"].'|'; }

          $mySqlQuery = "SELECT * FROM ".$database_connect_prefix."ugl where code_ugl='".$_SESSION['clp_structure']."'";
          try{
            $qh = $pdar_connexion->prepare($mySqlQuery);
            $qh->execute();
            $data = $qh ->fetch();
            $totalRows_clp = $qh->rowCount();
          }catch(Exception $e){ die(mysql_error_show_message($e)); }
          if(isset($data["abrege_ugl"])) $_SESSION["clp_ugl"]=$data["abrege_ugl"];

        }
        $_SESSION["clp_projet_ugl"] = $ugl;
      //}
      $query_auth = "SELECT * FROM ".$database_connect_prefix."user_access WHERE id_personnel=".$_SESSION["clp_n"];
        try{
            $auth = $pdar_connexion->prepare($query_auth);
            $auth->execute();
            $row_auth = $auth ->fetchAll();
            $totalRows_auth = $auth->rowCount();
        }catch(Exception $e){ die(mysql_error_show_message($e)); }
      if($totalRows_auth>0)
      {
        $_SESSION["clp_page_edit"] = $row_auth["page_edit"];
        $_SESSION["clp_page_verif"] = $row_auth["page_verif"];
        $_SESSION["clp_page_valid"] = $row_auth["page_valid"];
        $_SESSION["clp_page_interd"] = $row_auth["page_interd"];
      }
      else
      {
        $_SESSION["clp_page_edit"] = "";
        $_SESSION["clp_page_verif"] = "";
        $_SESSION["clp_page_valid"] = "";
        $_SESSION["clp_page_interd"] = "";
      }

      header(sprintf("Location: %s", $page."?statut=ok")); exit;
    }
    else
    {
      header(sprintf("Location: %s", $page."?statut=no")); exit;
    }
  }
  else { header(sprintf("Location: %s", "./")); exit; }

?>