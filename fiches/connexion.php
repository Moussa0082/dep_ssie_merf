<?php
   ///////////////////////////////////////////////
  /*                 SSE                       */
 /*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////

  session_start();
  include_once 'api/configuration.php';
  include_once 'api/essentiel.php';

  if(isset($_POST))
  {
    extract($_POST);
    $page = (isset($page))?$page:"./";
    require_once 'api/db.php';

    $q = $db ->prepare('SELECT P.*, S.id_partenaire as id_structure, S.sigle_partenaire as sigle, S.nom_partenaire as nom_structure FROM t_users P, t_fonction F, t_partenaires S WHERE P.login=:id_personnel AND P.pass=:pass and FIND_IN_SET(:type_partenaire,S.type_partenaire) and F.structure=S.id_partenaire  and P.fonction=F.id_fonction and P.statut=0');
    $q->execute(array(
            ':id_personnel' => $identifiant,
            ':pass' => md5($password),
            ':type_partenaire' => 1));
    $Result = $q ->fetch();
    if($q && $q->rowCount()>0)
    {
      $date=date("Y-m-d H:i:s"); $personnel=$Result['id_user'];
      //Log
      $q1 = $db->prepare('INSERT INTO t_connecter (user_id, session_id, date_connexion) VALUES (:personnel, :session_id, :date_connexion)');
        $q1 -> execute(array(
            ':personnel' => $personnel,
            ':session_id' => session_id(),
            ':date_connexion' => $date
        ));

      //Se souvenir
      if(!isset($remember))
      $_SESSION["remember"] = 1;
      $_SESSION['loggedAt'] = time();
      //Fin
      $_SESSION["id"] = ($Result['id_user']);
      $_SESSION["login"] = ($Result['login']);
      $_SESSION["titre"] = ($Result['titre']);
      $_SESSION["nom"] = ($Result['nom']);
      $_SESSION["prenom"] = ($Result['prenom']);
      $_SESSION["user_name"] = $_SESSION["prenom"]." ".strtoupper($_SESSION["nom"]);
      $_SESSION["fonction"] = ($Result['fonction']);
     // $_SESSION["type_fonction"] = ($Result['type_fonction']);
     // $_SESSION["type_fonction_nom"] = ($Result['nom_type_fonction']);
      $_SESSION["structure"] = ($Result['structure_concerne']);
      $_SESSION["structure_sigle"] = ($Result['sigle']);
      $_SESSION["structure_nom"] = ($Result['nom_structure']);
      $_SESSION["mail"] = ($Result['email']);
      $_SESSION["contact"] = ($Result['contact']);
      $_SESSION["niveau"] = ($Result['niveau']);

      //Programme
      if(intval($Result['programme_active'])>0)
      {
        //if(isset($_SESSION["niveau"]) && $_SESSION["niveau"]==0){
        $query_liste_programme = $db ->prepare('SELECT * FROM t_programmes P WHERE P.id_programme=:id_programme');
        $query_liste_programme->execute(array(':id_programme' => $Result['programme_active']));
        /*}else{
        $query_liste_programme = $db ->prepare('SELECT * FROM t_programmes P WHERE P.id_programme=:id_programme and P.id_programme IN (SELECT Pp.programme FROM t_projets Pp, t_projet_users D WHERE Pp.id_projet=D.projet_up and D.structure_up=:structure and FIND_IN_SET(:user,D.personnel_up) )');
        $query_liste_programme->execute(array(':id_programme' => $Result['programme_active'],':structure' => $_SESSION["structure"],':user' => $_SESSION["id"]));
        }*/
        $row_liste_programme = $query_liste_programme ->fetch();
        $totalRows_liste_programme = $query_liste_programme->rowCount();

        if($totalRows_liste_programme > 0)
        {
          $_SESSION["programme"] = $row_liste_programme['id_programme'];
          $_SESSION["programme_code"] = ($row_liste_programme["sigle_programme"]);
          $_SESSION["programme_sigle"] = ($row_liste_programme["sigle_programme"]);
          $_SESSION["programme_nom"] = ($row_liste_programme["nom_programme"]);
          $_SESSION["programme_vision"] = ($row_liste_programme["vision"]);
          $_SESSION["programme_objectif"] = ($row_liste_programme['objectif']);
          $_SESSION["programme_date_debut"] = $row_liste_programme['date_debut'];
          $_SESSION["programme_date_fin"] = $row_liste_programme['date_fin'];
          $_SESSION["programme_actif"] = ("Programme ".$row_liste_programme["sigle_programme"]." (".date_reg($row_liste_programme['date_debut'],"/")." au ".date_reg($row_liste_programme['date_fin'],"/").")");
          $_SESSION["where"] = " programme='".$_SESSION['programme']."'";
        }
      }
      //Projet
      if(intval($Result['projet_active'])>0)
      {
        $query_liste_projet = $db ->prepare('SELECT P.* FROM t_projets P, t_projet_users D WHERE P.programme=:programme and P.id_projet=D.projet_up and D.structure_up=:structure and FIND_IN_SET(:user,D.personnel_up) and P.id_projet=:id_projet');
        $query_liste_projet->execute(array(':programme' => isset($_SESSION['programme'])?$_SESSION['programme']:0,':structure' => $_SESSION["structure"],':user' => $_SESSION["id"],':id_projet' => $Result['projet_active']));
        $row_liste_projet = $query_liste_projet ->fetch();
        $totalRows_liste_projet = $query_liste_projet->rowCount();

        if($totalRows_liste_projet > 0)
        {
            $insertSQL = $db->prepare('UPDATE t_users SET projet_active=:projet_active WHERE id_user=:id_user');
            $Result1 = $insertSQL->execute(
            array(':projet_active' => $Result['projet_active'],':id_user' => $_SESSION['id']
            ));
            if($Result1)
            {
                $_SESSION["projet"] = $row_liste_projet['id_projet'];
                $_SESSION["projet_code"] = ($row_liste_projet["code_projet"]);
                $_SESSION["projet_sigle"] = ($row_liste_projet["sigle_projet"]);
                $_SESSION["projet_nom"] = ($row_liste_projet["intitule_projet"]);
                $_SESSION["projet_agence_lead"] = ($row_liste_projet["agence_lead"]);
                $_SESSION["projet_autres_agence"] = ($row_liste_projet["autres_agences_recipiendaires"]);
                $_SESSION["autres_partenaires_execution"] = ($row_liste_projet["autres_partenaires_execution"]);
                $_SESSION["projet_duree"] = ($row_liste_projet['duree']);
                $_SESSION["projet_date_demarrage"] = $row_liste_projet['date_demarrage'];
                $_SESSION["projet_date_signature"] = $row_liste_projet['date_signature'];
                $_SESSION["projet_actif"] = ("Projet ".$row_liste_projet["code_projet"]." (".$row_liste_projet['sigle_projet'].")");
                $_SESSION["where_p"] = " projet='".$_SESSION['projet']."'";
            }
        }
        else
        {
            $insertSQL = $db->prepare('UPDATE t_users SET projet_active=:projet_active WHERE id_user=:id_user');
            $Result1 = $insertSQL->execute(
            array(':projet_active' => 0,':id_user' => $_SESSION['id']
            ));
        }
      }

      //Log
      $q2 = $db->prepare('SELECT * FROM t_users_access WHERE id_user=:personnel');
        $q2 -> execute(array(
            ':personnel' => $_SESSION["id"]
        ));
      $Result1 = $q2 ->fetch();
      if($q2->rowCount()>0)
      {
        $_SESSION["page_edit"] = $Result1["page_edit"];
        $_SESSION["page_verif"] = $Result1["page_verif"];
        $_SESSION["page_valid"] = $Result1["page_valid"];
        $_SESSION["page_interd"] = $Result1["page_interd"];
      }
      else
      {
        $_SESSION["page_edit"] = "";
        $_SESSION["page_verif"] = "";
        $_SESSION["page_valid"] = "";
        $_SESSION["page_interd"] = "";
      }

      header(sprintf("Location: %s", $page."?statut=ok")); exit;
    }
    else
    {
      header(sprintf("Location: %s", "./login.php?statut=no")); exit;
    }
  }
  else { header(sprintf("Location: %s", "./?statut=no")); exit; }

?>