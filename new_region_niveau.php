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

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_activite = "SELECT * FROM ".$database_connect_prefix."region_config WHERE ".$_SESSION["clp_where"]." LIMIT 1";
  $liste_activite  = mysql_query($query_liste_activite , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_activite  = mysql_fetch_assoc($liste_activite);
  $totalRows_liste_activite  = mysql_num_rows($liste_activite);

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "SELECT * FROM ".$database_connect_prefix."region_config WHERE ".$_SESSION["clp_where"]." LIMIT 1";
  $entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);
  $libelle = array();
  if($totalRows_entete>0){ $libelle=explode(",",$row_entete["libelle"]);}

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
</style>
<script>
	$(document).ready(function() {
		// validate the comment form when it is submitted
		$(".form-horizontal").validate();

        //$(".modal-dialog", window.parent.document).width(840);
        <?php if(count($libelle)<6){ ?>
        var $addlink = $('<a href="#" id="add_input" class="btn btn-mini"><img src="images/plus.gif"> Ajouter un niveau</a><br>');

	   $("#add_lnk").append($addlink);
       <?php } ?>

        var $container= $('#mtable');

 var index= 0; var nbrch = <?php echo count($libelle); ?>;
       <?php if(!isset($libelle[0])){ ?>
	   if(index == 0){
		  add_input($container);
	   }
       <?php } ?>
       <?php if(count($libelle)<6){ ?>
	   $addlink.click(function(e){
	     if(nbrch<6)
		   add_input($container);
          if(nbrch<6) nbrch++;
		   e.preventDefault();
		   return false;
	   });
       <?php } ?>  


	   function add_input($container,num,lib_v,nodel,niv){
	    	       var $prototype=$(' <tr><td colspan="2">'+'Niveau '+(index+1)+'    '
                           +'<input placeholder="Libellé" required="required" type="text" id="form_lib_'+index+'" size="30" name="form[lib]['+index+']" value="'+((lib_v)?lib_v:'')+'" /><input type="hidden" id="form_niveau_'+index+'" size="30" name="form[niveau]['+index+']" value="'+(index+1)+'" />');

           if(!lib_v || (index==niv && niv>0)){
		   delete_input($prototype,((index)?index:''));  }
		   $container.append($prototype);

		   index++;
		   return false;
	   }


	  function delete_input($prototype,$id){
		  var $deletelink=$('<a href="#" id="delete_input_'+index+'"><img src="images/delete.png" width="20" height="20" /></a>');
		   $prototype.append($deletelink);
		   $deletelink.click(function(){
			  if($prototype.remove()) nbrch--;
           if($id){ form2.form_elem_del.value=form2.form_elem_del.value+$id+","; }
			  //e.preventDefault();
			  return false;
		   });
	  }

 <?php  $i=0;
 foreach($libelle as $lib){ if(!empty($lib)){  ?>

 add_input($container,"<?php echo $i; ?>","<?php echo $lib; ?>","nodel","<?php echo count($libelle)-1; ?>");

 <?php $i++; } } ?>

	});

</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification niveau":"Nouveau niveau"?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="add_lnk"></span></h4></div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <!--<tr valign="top">
      <td>
        <div class="form-group">
          <label for="nomtable" class="col-md-3 control-label">Nom <span class="required">*</span></label>
          <div class="col-md-8">
            <input placeholder="Sans espace et accents" required="required" class="form-control required" type="text" <?php if(isset($id)) echo 'disabled="disabled"'; ?> name="nomtable" id="nomtable" value="<?php echo ((isset($id)))?substr($id,6):"";?>" size="5" />
          </div>
        </div>
      </td>
      <td>
        <div class="form-group">
          <label for="lnomtable" class="col-md-3 control-label">Libellé <?php echo $libelle[$id]; ?><span class="required">*</span></label>
          <div class="col-md-8">
            <input placeholder="Sans espace et accents" required="required" class="form-control required" type="text"  name="lnomtable" id="lnomtable" value="<?php echo $lib_nom_fich; ?>" size="5" />
          </div>
        </div>
      </td>
    </tr>-->
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
<?php } ?>