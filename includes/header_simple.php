<?php if(!isset($_SESSION)) session_start();
  include_once 'system/configuration.php';
  if(!isset($config)) $config = new Config;
?>
<div class="container" style="padding-left: 0px;padding-right: 0px;"> <ul class="nav navbar-nav"><li class="nav-toggle"><a href="javascript:void(0);" title="Afficher/Masquer le volet navigation" style="padding: 18px;"><i class="icon-reorder"></i></a></li> </ul> <a class="navbar-brand" href="./" title="Allez &agrave; l'accueil"> <img src="images/Ruche_logo_menu2.png" alt="logo"/><strong><?php //print $config->shortname; ?></strong></a><a href="#" class="toggle-sidebar" title="Afficher le volet navigation"> <i class="icon-reorder"></i> </a> <span class="textlong_special" style="text-transform: uppercase;font-weight: bold;">Système de suivi-évaluation</span><span class="clear h0">&nbsp;</span>

<ul class="nav navbar-nav navbar-right" style="background-color: #090;">
<!--<li class="dropdown" title="Aide"> <a href="#" class="project-switcher-btn dropdown-toggle" style="margin-top: -5px;margin-bottom: -5px;"> <i class="icon-question-circle" style="font-size: 30px;"></i><i class="icon-caret-down small"></i></a></li>  -->
</ul>
<img src="images/image_entete1.png" alt="entete" width="300" height="50" align="right" style="margin-right: 20px;" />
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