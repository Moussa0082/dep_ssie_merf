<?php if(!isset($_SESSION)) session_start();
  include_once 'system/configuration.php';
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
  }

/*//Taux exécution
$annee_en_cours = date("Y"); $taux=0;
if(isset($_SESSION["clp_structure"]) && isset($_SESSION["clp_projet"]) && !empty($_SESSION["clp_structure"]) && !empty($_SESSION["clp_projet"])){
$query_liste_cout = "SELECT SUM( if(cout_prevu>0, cout_prevu,0) ) as prevu, SUM( if(cout_realise>0, cout_realise,0) ) as realise, SUM( if(cout_engage>0, cout_engage,0)) AS engage, annee, code  FROM ".$database_connect_prefix."code_activite where ".$database_connect_prefix."code_activite.projet='".$_SESSION["clp_projet"]."'";
try{
    $liste_cout = $pdar_connexion->prepare($query_liste_cout);
    $liste_cout->execute();
    $row_liste_cout = $liste_cout ->fetchAll();
    $totalRows_liste_cout = $liste_cout->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$prevu_array = $realise_array = $engage_array = 0;

if($totalRows_liste_cout>0){
foreach($row_liste_cout as $row_liste_cout){
 $prevu_array+=$row_liste_cout["prevu"];
 $realise_array+=$row_liste_cout["realise"];
 $engage_array+=$row_liste_cout["engage"];
  }
}
$taux = ($prevu_array>0)?$realise_array/$prevu_array:0; $taux = $taux*100;
}*/
?>
<style>
.dropdown-menu>li>a.current {
    background: #090;
    color: #fff;
    filter: none;
}
</style>
<div class="container"> <ul class="nav navbar-nav"><li class="nav-toggle"><a href="javascript:void(0);" title=""><i class="icon-reorder"></i></a></li> </ul> <a class="navbar-brand" href="./"> <img src="assets/img/logo.png" alt="logo"/> <strong><?php print $config->shortname; ?></strong></a> <a href="#" class="toggle-sidebar bs-tooltip" data-placement="bottom" data-original-title="Afficher/Masquer le volet navigation"> <i class="icon-reorder"></i> </a>

<span class="textlong_special" href="javascript:void(0);"> <strong><font size="3"><?php print $config->sitetititle; echo " <div class='textlong'> ".$config->siteshortname."</div>"; ?></font></strong></span>

<?php if(isset($_SESSION["clp_id"])){
/*$pdata = array(); $tmp = explode('|',$_SESSION["clp_user_projet"]);
foreach($tmp as $tmp1){ if(!empty($tmp1)) $pdata[]="'$tmp1'"; }
$tmp = implode(',',$pdata); $user_projet = (substr($tmp, -1)==",")?substr($tmp, 0, -1):$tmp;
$tmp = (!empty($user_projet))?"code_projet in ($user_projet) and ":"";
$mySqlQuery = "SELECT * FROM ".$database_connect_prefix."projet WHERE $tmp 1=1  ORDER BY code_projet";
try{
    $qh = $pdar_connexion->prepare($mySqlQuery);
    $qh->execute();
    $data = $qh ->fetchAll();
    $num = $qh->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); } */
$num=0;
?>

<ul class="nav navbar-nav navbar-right">
<!--<li class=""><a href="#" class="sp_header"> <b style="color: yellow;"><span>Taux de d&eacute;caissement&nbsp; (<?php echo (isset($_SESSION["clp_projet"]))?$_SESSION["clp_projet_sigle"]:"ND"; ?>)&nbsp;</span><span class="sp_yellow"><?php echo number_format($taux, 0, ' ', ' ')." %"; ?></span></b> </a></li> -->
<!--<li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-database"></i> <span>Projets</span> </a> <ul class="dropdown-menu extended notification"> <li class="title"> <p><?php if(isset($_SESSION["clp_projet"])){ ?>Le projet "<b><?php echo $_SESSION["clp_projet_sigle"]; ?></b>" est sélectionné<?php } else { ?>Veuillez sélectionner un projet<?php } ?></p> </li>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
$currentPage = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
    if($num>0){
      foreach($data as $data){ ?>
<li class="<?php echo ($data["code_projet"]==$_SESSION["clp_projet"])?"active":""; ?>"> <a href="<?php echo ($data["code_projet"]==$_SESSION["clp_projet"])?"javascript:void(0);":"./projet_swither.php?id=".$data["code_projet"]."&page=$editFormAction"; ?>"> <span class="photo"><img src="<?php  echo (is_file("./images/projet/img_".$data["code_projet"].".jpg"))?'./images/projet/img_'.$data["code_projet"].".jpg":'./images/projet/none.png'; ?>" alt=""></span> <span class="subject"> <span class="from"><?php echo $data["sigle_projet"]; ?></span> <span class="time">Code <?php echo $data["code_projet"]; ?></span> </span> <span class="text"> <?php echo $data["intitule_projet"]; ?> </span> </a> </li>
<?php } } else { ?>
<li> <a href="javascript:void(0);"> <span><center>Pas de guichet</center></span> </a> </li>
<?php }if($num>1){ ?>
<li class="footer"> <a href="./projets.php">Voir tous les guichets</a> </li> <?php } ?></ul> </li>-->
<?php //include_once 'includes/online_users.php'; ?>
<?php //include_once 'includes/notification_pop.php'; ?>
<li class="dropdown user"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-top: 4px;padding-bottom: 4px;text-align: center;line-height: 21px;"> <i class="icon-male"></i> <span class="username"><?php echo $_SESSION["clp_nom"]."<br>".$_SESSION["clp_prenom"]; ?></span> <i class="icon-caret-down small"></i> </a>
  <ul class="dropdown-menu">
    <li><a class="<?php echo (isset($nfile) && $nfile == "user_profile.php")?'current':''; ?>" href="./user_profile.php"><i class="icon-user"></i> Mon Profil</a></li>
    <li><a class="<?php echo (isset($nfile) && $nfile == "gestion_mot_passe.php")?'current':''; ?>" href="./gestion_mot_passe.php"><i class="icon-tasks"></i> Changer mot de passe</a></li>
    <li class="divider"></li>
    <li><a class="<?php //echo (isset($nfile) && $nfile == "logout.php")?'current':''; ?>" href="./logout.php"><i class="icon-key"></i> Se déconnecter</a></li>
  </ul>
</li>
<!--<li class="dropdown"> <a href="#" class="project-switcher-btn dropdown-toggle"> <i class="icon-fa-th-large"></i> <span>Aides</span> </a></li>-->
</ul>
</div>

<div id="project-switcher" class="container project-switcher">
<div id="scrollbar"> <div class="handle"></div> </div>
  <div id="frame">
    <ul class="project-list">
    <li class="<?php //echo (in_array('aide',$getfile) && stristr($getfile[1], "d=1") == true)?'current':''; ?>"> <a onclick="get_help('aide.php','aide&d=1','modal-body');" data-toggle="modal" href="#myModal1"> <span class="image"><i class="icon-desktop"></i></span> <span style="color: white !important;" class="title">1. Accueil</span> </a> </li>
<?php $i=2; foreach($MENU as $a=>$b){ ?>
    <li class="<?php //echo (in_array('aide',$getfile) && stristr($getfile[1], "d=1") == true)?'current':''; ?>"> <a onclick="get_help('aide.php','<?php echo "aide&d=$a"; ?>','modal-body');" data-toggle="modal" href="#myModal1" data-backdrop="static" data-keyboard="false"> <span class="image"><i class="icon-<?php if(isset($MENU_TITLE[$a][1])) echo $MENU_TITLE[$a][1]; ?>"></i></span> <span style="color: white !important;" class="title"><?php if(isset($MENU_TITLE[$a][0])) echo $i.". ".$MENU_TITLE[$a][0]; ?></span> </a> </li>
<?php $i++; } ?>
    </ul>
  </div>
</div>
<div class="modal fade" id="myModal1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"> <button title="Fermer" type="button" class="close" data-dismiss="modal" aria-hidden="true">&nbsp;</button> <h4 class="modal-title">Session Utilisateur - Aide</h4> </div>
      <div class="modal-body" id="modal-body"> Chargement du contenu… </div>
      <div class="modal-footer"> <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button></div>
      </div>
  </div>
</div>

<?php } ?>