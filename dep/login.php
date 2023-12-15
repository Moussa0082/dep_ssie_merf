<?php
   ///////////////////////////////////////////////
  /*                 SSE                       */
 /*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
  //session_start();
  include_once 'system/configuration.php';
  if(!isset($config)) $config = new Config;
  include_once $config->sys_folder."/database/db_connexion.php";
  /*
  include_once $config->sys_folder."/database/credential.php";
  include_once $config->sys_folder."/database/essentiel.php";
  */
//sous domaines
$row_structure = array(); $protocole = "https://";
$path = "../";
$dir_handle = @opendir($path) or die("Unable to open $path");
while ($file = readdir($dir_handle)) {
  if($file == "." || $file == "..")
    continue;
  if (is_dir($path.$file) && substr_count($file, ".")==2) $row_structure[] = $file;
}
closedir($dir_handle);
?>
<style>.crumbs{display:none;}</style>
<div class="login">
<div class="logo"> <!--<img src="images/logo.jpg" alt="logo"/>--> <strong><?php print $config->siteshortname; ?></strong> </div>
<div class="box">
  <div class="content"> <form class="form-vertical login-form" action="./connexion.php" method="post"> <h3 class="form-title">Se connecter &agrave; votre compte</h3> <div class="alert fade in alert-danger" style="display: none;"> <i class="icon-remove close" data-dismiss="alert"></i> Entrez un identifiant et un mot de passe. </div> <div class="form-group"> <div class="input-icon"> <i class="icon-user"></i> <input type="text" name="identifiant" is="identifiant" class="form-control" placeholder="Identifiant" autofocus="autofocus" data-rule-required="true" data-msg-required="Veuillez entrer votre nom d'utilisateur."/> </div> </div> <div class="form-group"> <div class="input-icon"> <i class="icon-lock"></i> <input type="password" name="password" id="password" class="form-control" placeholder="Mot de passe" data-rule-required="true" data-msg-required="Veuillez entrer votre mot de passe."/> </div> </div> <div class="form-group"> <div class="input-icon"> <i class="icon-project"></i>
<select name="structure" id="structure" class="form-control" placeholder="Identifiant" autofocus="autofocus" data-rule-required="true" data-msg-required="Veuillez selectionner votre structure." >
<!--<option value="">Selectionnez votre domaine</option>-->
<option value="<?php echo $config->base_host; ?>/">MERF (DPSSE)</option>
<?php if(count($row_structure)>0){ foreach($row_structure as $nom_structure) { ?>
<option value="<?php echo $nom_structure; ?>"><?php list($nom_structure)=explode(".",$nom_structure); echo strtoupper(str_replace($protocole,"",$nom_structure)); ?></option>
<?php } } ?>
</select>
 </div> </div> <div class="form-actions"> <label class="checkbox pull-left"><input type="checkbox" class="uniform" name="remember"> Rester connect&eacute;</label> <button type="submit" class="submit btn btn-success pull-right"> Se connecter <i class="icon-angle-right"></i> </button> </div> </form></div>
</div>
<div class="footer"> <a href="javascript:void(0);" class=""><h1><?php print $config->sitename; ?></h1></a> </div>
</div>