<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & D�veloppement: SEYA SERVICES */
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
if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y");
if(isset($_GET['cp'])) {$cp=$_GET['cp'];}


if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=$_GET["id"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_activite = "DESCRIBE $id";
  $liste_activite  = mysql_query($query_liste_activite , $pdar_connexion) or die(mysql_error());
  $row_liste_activite  = mysql_fetch_assoc($liste_activite);
  $totalRows_liste_activite  = mysql_num_rows($liste_activite);

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "SELECT * FROM fiche_config WHERE `table`='$id'";
  $entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);
  $entete_array = array(); $choix_array = array(); $libelle = array();
  if($totalRows_entete>0){ $entete_array=explode(",",$row_entete["show"]); $libelle=explode(",",$row_entete["libelle"]);
  if(!empty($row_entete["choix"])){ foreach(explode(",",$row_entete["choix"]) as $elem){ if(!empty($elem)){  $a=explode(";",$elem); $choix_array[$a[0]]=""; for($i=1;$i<count($a);$i++){ $choix_array[$a[0]].=(!empty($a[$i]))?$a[$i].";":""; } }   }  }
   }

$count = count($libelle)-2;
$count = explode("=",$libelle[$count]);
$lib_nom_fich = "";
if(isset($count[1]))
$lib_nom_fich = $count[1];
elseif(isset($count[0]))
$lib_nom_fich = $count[0];

}

?>
<script type="text/javascript" src="bootstrap/js/jquery.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
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
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();

        $(".modal-dialog", window.parent.document).width(840);

        var $addlink = $('<a href="#" id="add_input" class="btn btn-mini"><img src="images/plus.gif"> Ajouter un champ</a><br>');

	   $("#add_lnk").append($addlink);

        var $container= $('#mtable');

 var index= 0;
       <?php if(!isset($id)){ ?>
	   if(index == 0){
		  add_input($container);
	   }
       <?php } ?>
	   $addlink.click(function(e){
		   add_input($container);
		   //e.preventDefault();
		   return false;
	   });


	   function add_input($container,nom_v,type_v,estnull_v,show_v,choix_v,lib_v){
	    	       var $prototype=$(' <tr><td colspan="2">'+((nom_v)?'<input required="required" type="hidden" name="form[old]['+index+']" value="'+nom_v+'" />':'')+'<input placeholder="Nom du champ" required="required" type="text" id="form_nom_'+index+'" size="5" name="form[nom]['+index+']" value="'+((nom_v)?nom_v:'')+'" />'
                           +'<input placeholder="Libell�" required="required" type="text" id="form_lib_'+index+'" size="15" name="form[lib]['+index+']" value="'+((lib_v)?lib_v:'')+'" />'
	    	    	       +'&nbsp;<select onchange="afficher_choix('+index+',this.value);" id="form_type_'+index+'" name="form[type]['+index+']">'
	    	    	       +'<option value="TEXT" '+((type_v=="text")?'selected="selected"':'')+' >TEXT</option>'
	    	    	       +'<option value="INT" '+((type_v=="int(11)")?'selected="selected"':'')+' >INT</option>'
                           +'<option value="DATE" '+((type_v=="date")?'selected="selected"':'')+' >DATE</option>'
                           +'<option value="DOUBLE" '+((type_v=="double")?'selected="selected"':'')+' >DOUBLE</option>'
                           +'<option value="VARCHAR(1000)" '+((type_v=="varchar(1000)")?'selected="selected"':'')+' >CHOIX</option>'
	    	    	       +'</select>'
	    	    	       +'&nbsp;<select id="form_estnull_'+index+'" name="form[estnull]['+index+']">'
	    	    	       +'<option value="" '+((estnull_v=="")?'selected="selected"':'')+' >Facultatif</option>'
	    	    	       +'<option value="NOT NULL" '+((estnull_v=="NOT NULL")?'selected="selected"':'')+' >Obligatoire</option>'
	    	    	       +'</select>&nbsp;'
                           +'&nbsp;<select id="form_show_'+index+'" name="form[show]['+index+']">'
	    	    	       +'<option value="1" '+((show_v==1)?'selected="selected"':'')+' >Afficher</option>'
	    	    	       +'<option value="0" '+((show_v==0)?'selected="selected"':'')+' >Ne pas afficher</option>'
	    	    	       +'</select>&nbsp;<input class="'+((type_v=="varchar(1000)")?"show1":"hide1")+'" placeholder="   S�parateur ;           " type="text" id="form_choix_'+index+'" name="form[choix]['+index+']" value="'+((choix_v)?choix_v:'')+'" /></td></tr>');


		   delete_input($prototype,((nom_v)?nom_v:''));
		   $container.append($prototype);

		   // ajout d'un autocompletion sur le champs du choix des type
		   /*var deps = ['INT','VARCHAR','CHAR','DOUBLE','DATE', 'DATETIME', 'YEAR', 'TEXT']; //
		   $("input[id^='form_type']").typeahead({source: deps}); */

		   index++;
		   return false;
	   }


	  function delete_input($prototype,$id){
		  var $deletelink=$('<a href="#" id="delete_input_'+index+'"><img src="images/delete.png" /></a>');
		   $prototype.append($deletelink);
		   $deletelink.click(function(){
			  $prototype.remove();
           if($id){ form1.form_elem_del.value=form1.form_elem_del.value+$id+","; }
			  //e.preventDefault();
			  return false;
		   });
	  }

 <?php
 if($totalRows_liste_activite>0){ $i=0; do{
if(isset($libelle[$i])){
$lib=explode("=",$libelle[$i]);
$libelle_array[$lib[0]]=isset($lib[1])?$lib[1]:"ND";   }

 if($row_liste_activite["Field"]!="LKEY" && $row_liste_activite["Field"]!="annee" && $row_liste_activite["Field"]!="fiche"){    ?>

 add_input($container,"<?php echo $row_liste_activite['Field']; ?>","<?php echo $row_liste_activite['Type']; ?>","<?php echo ($row_liste_activite['Null']=='YES')?'':'NOT NULL'; ?>","<?php echo (in_array($row_liste_activite['Field'],$entete_array))?1:0; ?>","<?php echo (isset($choix_array[$row_liste_activite['Field']]))?substr($choix_array[$row_liste_activite['Field']],0,strlen($choix_array[$row_liste_activite['Field']])-1):''; ?>","<?php if(isset($libelle_array[$row_liste_activite['Field']])) echo $libelle_array[$row_liste_activite['Field']]; ?>");

 <?php } $i++; }while($row_liste_activite  = mysql_fetch_assoc($liste_activite)); } ?>

	});

function afficher_choix(id,val){  form_name="form_choix_"+id;
  if(val=="VARCHAR(1000)"){ document.getElementById(form_name).className="show1"; }
  else{ document.getElementById(form_name).className="hide1"; }
}
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) { ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification fiche":"Nouvelle fiche"?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="add_lnk"></span></h4></div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr valign="top">
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
          <label for="lnomtable" class="col-md-3 control-label">Libell� <?php echo (isset($id) && isset($libelle[$id]))?$libelle[$id]:''; ?><span class="required">*</span></label>
          <div class="col-md-8">
            <input placeholder="Sans espace et accents" required="required" class="form-control required" type="text"  name="lnomtable" id="lnomtable" value="<?php echo (isset($lib_nom_fich))?$lib_nom_fich:''; ?>" size="5" />
          </div>
        </div>
      </td>
    </tr>

</table>
<div class="form-actions">
<input type="hidden" id="form_elem_del" name="form_elem_del" value="" />
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo isset($_GET["id"])?$_GET["id"]:""; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2)) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette fiche ?','<?php echo isset($_GET["id"])?$_GET["id"]:""; ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>