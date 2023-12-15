<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');

$date=date("Y-m-d");

  $query_liste_activite = "SELECT * FROM ".$database_connect_prefix."cadre_config_cosop  LIMIT 1";
    try{
    $liste_activite = $pdar_connexion->prepare($query_liste_activite);
    $liste_activite->execute();
    $row_liste_activite = $liste_activite ->fetch();
    $totalRows_liste_activite = $liste_activite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


  $query_entete = "SELECT * FROM ".$database_connect_prefix."cadre_config_cosop  LIMIT 1";
      try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $libelle = array(); $type = array();
  if($totalRows_entete>0){ $libelle=explode(",",$row_entete["libelle"]); $type=array(); }

?>

<style>
.hide1 {
  visibility: hidden;
}
.show1 {
  visibility: visible;
}
.firstcapitalize:first-letter{
  text-transform: capitalize;
}
.form-control {
  width: auto;
  display: inline;
}
</style>
<script>
	$(document).ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();

        //$(".modal-dialog", window.parent.document).width(840);
        <?php if(count($libelle)<4){ ?>
        var $addlink = $('<a href="#" id="add_input" class="btn btn-mini"><img src="images/plus.gif"> Ajouter un niveau</a><br>');

	   $("#add_lnk").append($addlink);
       <?php } ?>

        var $container= $('#mtable');

 var index= 0; var nbrch = <?php echo count($libelle); ?>;
       <?php if(!isset($libelle[0])){ ?>
	   if(index == 0){ //index++;
		  add_input($container,0,'Niveau 1',true);
          add_input($container,1,'Niveau 2',true);
	   }
       <?php } ?>
       <?php if(count($libelle)<4){ ?>
	   $addlink.click(function(e){
	     if(nbrch<4)
		   add_input($container);
          if(nbrch<4) nbrch++;
		   e.preventDefault();
		   return false;
	   });
       <?php } ?>  


	   function add_input($container,num,lib_v,nodel,niv,t){
	    	       var $prototype=$(' <tr><td><label for="form_lib_'+index+'">'+'Niveau '+(index+1)+'&nbsp;</label>'+'<input placeholder="Libellé" required="required" type="text" id="form_lib_'+index+'" size="30" name="form[lib]['+index+']" value="'+((lib_v)?lib_v:'')+'" class="form-control" /><input type="hidden" id="form_niveau_'+index+'" size="30" name="form[niveau]['+index+']" value="'+(index+1)+'" /></td><td>');

           if(!lib_v || (index==niv && niv>1)){
             if(nodel!="")
		   delete_input($prototype,((index)?index:''));  }
		   $container.append($prototype);

		   index++;
		   return false;
	   }


	  function delete_input($prototype,$id){
		  var $deletelink=$('<a href="#" id="delete_input_'+index+'"><img src="images/delete.png" width="20" height="20" /></a>');
		   $prototype.append($deletelink);
		   $deletelink.click(function(){
			  if($prototype.remove()) { index--; nbrch--; }
           if($id){ form2.form_elem_del.value=form2.form_elem_del.value+$id+","; }
			  //e.preventDefault();
			  return false;
		   });
	  }

 <?php  $i=0;
 foreach($libelle as $lib){ if(!empty($lib)){  ?>

 add_input($container,"<?php echo $i; ?>","<?php echo $lib; ?>","<?php echo ($i>1)?true:''; ?>","<?php echo count($libelle)-1; ?>","");

 <?php $i++; } } ?>

	});

</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification niveau":"Nouveau niveau"?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="add_lnk"></span></h4></div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
</table>
<div class="form-actions">
<input type="hidden" id="form_elem_del" name="form_elem_del" value="" />
<?php if(isset($libelle[0])){ ?>
  <input type="hidden" name="id" value="<?php echo isset($libelle[0])?1:""; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($libelle[0]) && !empty($libelle[0])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($libelle[0]) && !empty($libelle[0])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($libelle[0]) && !empty($libelle[0])) echo 1; else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($libelle[0]) && !empty($libelle[0]) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2)) { ?>
<!--<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette fiche ?','<?php echo isset($libelle[0])?$libelle[0]:""; ?>');" class="btn btn-danger pull-left" value="Supprimer" />-->
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">
</div>
</form>

</div> </div>
<?php  } ?>