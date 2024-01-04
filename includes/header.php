<?php if(!isset($_SESSION["clp_id"])) include_once "includes/header_simple.php"; else { ?>
<?php //if(!isset($_SESSION)) session_start();
 /* include_once 'system/configuration.php';
  if(!isset($config)) $config = new Config;
//Auto logout function
if (isset($_SESSION["clp_id"]) && isset($_SESSION["clp_remember"]))
{
	$hasSessionExpired = checkIfTimedOut();
	if($hasSessionExpired)
	{
        if(!headers_sent())
        {
          header(sprintf("Location: %s", "./logout.php?identifiant=".$_SESSION["clp_id"]));
          exit;
        }
        else
        {
          ?>
          <script>
          document.location.href = "<?php echo "./logout.php?identifiant=".$_SESSION["clp_id"]; ?>";
          </script>
          <h1 align="center">Session expir&eacute;e</h1>
          <?php exit;
        }
	}
	else
	{
		$_SESSION['clp_loggedAt']= time();// update last accessed time
		//showLoggedIn();
	}
}

function checkIfTimedOut()
{
  $config = new Config;
  include_once $config->sys_folder.'/database/db_connexion.php';
  $current = time();// take the current time
  $diff = $current - $_SESSION['clp_loggedAt'];
  if($diff > $config->maxtime)
  {
  return true;
  }
  else
  {
  return false;
  $_SESSION['clp_loggedAt']= time();// update last accessed time
  }
}
//Fin

  if(isset($_SESSION["clp_id"]))
  {
    //Authorisation
    $page = explode('|',$_SESSION["clp_page_interd"]); $page_principal = $page;
    if(in_array($nfile,$page))
    {
      if(!headers_sent())
      {
        header(sprintf("Location: %s", "./unauthorize.php?page=".$nfile));
        exit;
      }
      else
      {
        ?>
        <script>
        document.location.href = "<?php echo "./unauthorize.php?page=".$nfile; ?>";
        </script>
        <?php
      }
    }
  }*/
?>
<div class="container" style="padding-left: 0px;padding-right: 0px;"> <ul class="nav navbar-nav"><li class="nav-toggle"><a href="javascript:void(0);" title="Afficher/Masquer le volet navigation" style="padding: 18px;"><i class="icon-reorder"></i></a></li> </ul> <a class="navbar-brand" href="./" title="Allez &agrave; l'accueil"> <img src="images/Ruche_logo_menu2.png" alt="logo" /><strong><?php //print $config->shortname; ?></strong></a><a href="#" class="toggle-sidebar" title="Afficher le volet navigation"> <i class="icon-reorder"></i> </a> <span class="textlong_special" style="text-transform: uppercase;font-weight: bold;">Système de suivi-évaluation</span><span class="clear h0">&nbsp;</span>

<?php if(isset($_SESSION["clp_id"])){
$pdata = array(); $tmp = explode('|',$_SESSION["clp_user_projet"]);
foreach($tmp as $tmp1){ if(!empty($tmp1)) $pdata[]="'$tmp1'"; }
$tmp = implode(',',$pdata); $user_projet = (substr($tmp, -1)==",")?substr($tmp, 0, -1):$tmp;
$tmp = (!empty($user_projet))?"code_projet in ($user_projet) and ":"";
$mySqlQuery = "SELECT * FROM ".$database_connect_prefix."projet WHERE $tmp 1=1  ORDER BY code_projet";
try{
    $qh = $pdar_connexion->prepare($mySqlQuery);
    $qh->execute();
    $data = $qh ->fetchAll();
    $num = $qh->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>

<ul class="nav navbar-nav navbar-right" >
<li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Choix du Projet"> <i class="icon-database gray"></i> <span class="gray1"><i class="icon-caret-down small"></i></span> </a> <ul class="dropdown-menu extended notification" style=" max-height: 200px;overflow-y: auto;"> <!--<li class="title"> <p><?php //if(!empty($_SESSION["clp_programmes_2qc_actif"])){ ?>"<b><?php //echo $_SESSION["clp_programmes_2qc_actif"]; ?></b>" est s&eacute;lectionn&eacute;<?php //} else { ?>Veuillez s&eacute;lectionner un Projet<?php //} ?></p> </li>-->
<?php
$editFormAction = $_SERVER['PHP_SELF'];
$currentPage = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
    if($num>0){ foreach($data as $data){ ?>
<li class="<?php echo ($data["code_projet"]==$_SESSION["clp_projet"])?"active":""; ?>"> <a href="<?php echo "./projet_swither.php?id=".$data["code_projet"]."&page=$editFormAction"; ?>"> <span class="photo"><img src="<?php  echo (is_file("./images/projet/img_".$data["code_projet"].".jpg"))?'./images/projet/img_'.$data["code_projet"].".jpg":'./images/projet/none.png'; ?>" alt=""></span> <span class="subject"> <span class="from"> <?php echo $data['sigle_projet']; ?></span> <span class="time" style="margin-top: -15px;">P&eacute;riode <?php echo $data['annee_debut']." - ".$data['annee_fin']; ?></span> </span> <span class="text"> <?php echo $data["intitule_projet"]; ?> </span> </a> </li>
<?php } } else { ?>
<li> <a href="javascript:void(0);"> <span><center>Pas de Projets</center></span> </a> </li>
<?php } ?>
<li class="footer"> <a href="./projets.php">Voir tous les Projets</a> </li> </ul> </li>
<?php include_once 'includes/online_users.php'; ?>
<?php include_once 'includes/notification_pop.php'; ?>
<li>
    <form class="sidebar-search" action="./resultat.php" method="get" style="margin-top:10px;"> <div class="input-box"> <button type="submit" class="submit" style="position:absolute;right:3px;top:17px;background: none;border: none;color:#45A461"> <i class="icon-search"></i> </button> <span> <input type="text" style="border:none;background-color: #279848;color:#FFF;padding:1px 5px!important;" name="q" placeholder="Recherche..." value="<?php echo (isset($_GET['q']) && !empty($_GET['q']))?utf8_decode($_GET['q']):""; ?>"> </span> </div> </form>
</li>
<li class="dropdown user"> <a href="#" class="dropdown-toggle dropdown-user" data-toggle="dropdown"> <i class="icon-male"></i> <span class="username"><?php echo $_SESSION["clp_nom"]." ".$_SESSION["clp_prenom"]; ?></span> <i class="icon-caret-down small"></i> </a>
  <ul class="dropdown-menu">
    <li><a class="<?php echo (isset($nfile) && $nfile == "user_profile.php")?'current':''; ?>" href="./user_profile.php"><i class="icon-user"></i> Mon Profil</a></li>
    <li><a class="<?php echo (isset($nfile) && $nfile == "my_folder.php")?'current':''; ?>" href="./my_folder.php"><i class="icon-folder"></i> Mes documents</a></li>
    <li><a class="<?php echo (isset($nfile) && $nfile == "gestion_mot_passe.php")?'current':''; ?>" href="./gestion_mot_passe.php"><i class="icon-tasks"></i> Changer mot de passe</a></li>
    <!--<li><a class="<?php echo (isset($nfile) && $nfile == "mise_a_jour.php")?'current':''; ?>" href="./mise_a_jour.php"><i class="icon-refresh"></i> Mise &agrave; jour <span class="badge label-success" id="notif_update_num"></span></a></li>-->
    <li class="divider"></li>
    <li><a class="<?php //echo (isset($nfile) && $nfile == "logout.php")?'current':''; ?>" href="./logout.php"><i class="icon-key"></i> Se d&eacute;connecter</a></li>
  </ul>
</li>
<!--<li class="dropdown" title="Aide"> <a href="#" class="project-switcher-btn dropdown-toggle" style="margin-top: -5px;margin-bottom: -5px;"> <i class="icon-question-circle" style="font-size: 30px;"></i><i class="icon-caret-down small"></i></a></li> -->
</ul>
</div>

<div class="modal fade" id="myModal1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"> <button title="Fermer" type="button" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;</button> <h4 class="modal-title">Session Utilisateur - Aide</h4> </div>
      <div class="modal-body" id="modal-body"> <p class="dancing-dots-text" align="center" style="padding:5px; vertical-align: middle;" >Chargement du contenu en cours<span><span>&bull;</span><span>&bull;</span><span>&bull;</span></span></p> </div>
      <!--<div class="modal-footer"> <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button></div>-->
      </div>
  </div>
</div>

<?php } ?>

<?php } ?>