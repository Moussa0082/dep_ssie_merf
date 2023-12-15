<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"]) || !isset($_GET['classeur'])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');

$date=date("Y-m-d");
if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else {$annee=date("Y");}
if(isset($_GET['classeur']) && intval($_GET['classeur'])>0) {$cp=$_GET['classeur'];}else unset($cp);
$interdit_array = array("classeur","LKEY","annee","projet","structure","id_personnel","date_enregistrement","modifier_le","modifier_par","etat");
$dir = './attachment/fiches_dynamiques/';

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=$_GET["id"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_activite = "DESCRIBE ".$database_connect_prefix."$id";
  $liste_activite  = mysql_query_ruche($query_liste_activite , $pdar_connexion) or die(mysql_error());
  $row_liste_activite  = mysql_fetch_assoc($liste_activite);
  $totalRows_liste_activite  = mysql_num_rows($liste_activite);

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table`='$id'";
  $entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error());
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);
  $entete_array = array(); $choix_array = array(); $libelle = array();
  if($totalRows_entete>0){ $nomT=$row_entete["nom"]; $note=$row_entete["note"]; $lignetotal=$row_entete["lignetotal"]; $colnum=$row_entete["colnum"]; $detail_sexe=$row_entete["detail_sexe"]; $nombre=$row_entete["nombre"]; $entete_array=explode("|",$row_entete["show"]); $libelle=explode("|",$row_entete["libelle"]); if(file_exists($dir."icon_".$id.".jpg")) $icon=$dir."icon_".$id.".jpg";
  if(!empty($row_entete["choix"])){ foreach(explode("|",$row_entete["choix"]) as $elem){ if(!empty($elem)){  $a=explode(";",$elem); $choix_array[$a[0]]=""; for($i=1;$i<count($a);$i++){ $choix_array[$a[0]].=(!empty($a[$i]))?$a[$i].";":""; } }   }  }
   $lib_nom_fich0 = isset($libelle[count($libelle)-2])?explode('=',$libelle[count($libelle)-2]):"";
   $lib_nom_fich = isset($lib_nom_fich0[1])?$lib_nom_fich0[1]:"";

   }
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SELECT * FROM ".$database_connect_prefix."classeur WHERE id_classeur=$cp";
$liste_cp = mysql_query_ruche($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);
if($totalRows_liste_cp<=0) exit;

//toutes les fiches
$lib_nom_fich_array = array();
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cfg = "SELECT * FROM ".$database_connect_prefix."fiche_config WHERE `table` LIKE 'fiche_".$cp."_details_%'";
$cfg  = mysql_query_ruche($query_cfg , $pdar_connexion) or die(mysql_error());
$row_cfg  = mysql_fetch_assoc($cfg);
$totalRows_cfg  = mysql_num_rows($cfg);

if($totalRows_cfg>0){ do{
  $cfg_array=explode("|",$row_cfg["show"]); $libelleF=explode("|",$row_cfg["libelle"]);

$count = count($libelleF)-2;
$count = explode("=",$libelleF[$count]);

if(isset($count[1]))
$lib_nom_fich_array[$row_cfg["table"]] = $count[1];
elseif(isset($count[0]))
$lib_nom_fich_array[$row_cfg["table"]] = $count[0];

  }while($row_cfg  = mysql_fetch_assoc($cfg));
}

//dernière feuille numéro
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SHOW tables";
$liste_cp = mysql_query_ruche($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);

$table_array=array();
if($totalRows_liste_cp>0) {
do {  if(strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!="" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],$database_connect_prefix."fiche")!=$database_connect_prefix."fiche_config" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"details")!=""){   $table_array[]=$row_liste_cp["Tables_in_$database_pdar_connexion"];   }
} while ($row_liste_cp = mysql_fetch_assoc($liste_cp));
$rows = mysql_num_rows($liste_cp);
if($rows > 0) {
mysql_data_seek($liste_cp, 0);
$row_liste_cp = mysql_fetch_assoc($liste_cp);
}}

$numero=1;
foreach($lib_nom_fich_array as $idf=>$nf){
$vfich=$database_connect_prefix.$idf;  while(in_array($vfich,$table_array)){ $numero++; $vfich=$database_connect_prefix.$idf.$numero; }  }

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
select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input {
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
select {
  width: 100px;
}

</style>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$(document).ready(function() {
		// validate the comment form when it is submitted
		$("#form3").validate();

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
		   e.preventDefault();
		   return false;
	   });


	   function add_input($container,nom_v,type_v,estnull_v,show_v,choix_v,lib_v){
//1001 SOMME - 1002 DIFFERENCE - 1003 PRODUIT - 1004 RAPPORT - 1005 MOYENNE - 1006 COMPTER
	    	       var $prototype=$(' <tr><td colspan="2">'+((nom_v)?'<input required="required" type="hidden" name="form[old]['+index+']" value="'+nom_v+'" />':'')+'<input title="Ne doit pas comporter d\'espace ni de caractères spéciaux" placeholder="Nomduchamp" required="required" type="text" id="form_nom_'+index+'" size="10" name="form[nom]['+index+']" value="'+((nom_v)?nom_v:'')+'" />'
                           +'&nbsp;&nbsp;<input placeholder="Libellé" required="required" type="text" id="form_lib_'+index+'" size="15" name="form[lib]['+index+']" value="'+((lib_v)?lib_v:'')+'" />'
	    	    	       +'&nbsp;&nbsp;<select onchange="afficher_choix('+index+',this.value);" id="form_type_'+index+'" name="form[type]['+index+']">'
	    	    	       +'<option value="TEXT" '+((type_v=="text")?'selected="selected"':'')+' >TEXT</option>'
	    	    	       +'<option value="INT" '+((type_v=="int(11)")?'selected="selected"':'')+' >INT</option>'
                           +'<option value="DATE" '+((type_v=="date")?'selected="selected"':'')+' >DATE</option>'
                           +'<option value="DOUBLE" '+((type_v=="double")?'selected="selected"':'')+' >DOUBLE</option>'
                           +'<option value="VARCHAR(1000)" '+((type_v=="varchar(1000)")?'selected="selected"':'')+' >CHOIX</option>'
                           +'<option value="VARCHAR(1001)" '+((type_v=="varchar(1001)")?'selected="selected"':'')+' >SOMME</option>'
                           +'<option value="VARCHAR(1002)" '+((type_v=="varchar(1002)")?'selected="selected"':'')+' >DIFFERENCE</option>'
                           +'<option value="VARCHAR(1003)" '+((type_v=="varchar(1003)")?'selected="selected"':'')+' >PRODUIT</option>'
                           +'<option value="VARCHAR(1004)" '+((type_v=="varchar(1004)")?'selected="selected"':'')+' >RAPPORT</option>'
                           +'<option value="VARCHAR(1005)" '+((type_v=="varchar(1005)")?'selected="selected"':'')+' >MOYENNE</option>'
                           +'<option value="VARCHAR(1006)" '+((type_v=="varchar(1006)")?'selected="selected"':'')+' >COMPTER</option>'
                           +'<option value="VARCHAR(1007)" '+((type_v=="varchar(1007)")?'selected="selected"':'')+' >FILE</option>'
                           +'<option value="VARCHAR(1008)" '+((type_v=="varchar(1008)")?'selected="selected"':'')+' >COULEUR</option>'
	    	    	       +'</select>'
	    	    	       +'&nbsp;&nbsp;<select id="form_estnull_'+index+'" name="form[estnull]['+index+']">'
	    	    	       +'<option value="" '+((estnull_v=="")?'selected="selected"':'')+' >Facultatif</option>'
	    	    	       +'<option value="NOT NULL" '+((estnull_v=="NOT NULL")?'selected="selected"':'')+' >Obligatoire</option>'
	    	    	       +'</select>&nbsp;'
                           +'&nbsp;&nbsp;<select id="form_show_'+index+'" name="form[show]['+index+']">'
                           +'<option value="1" '+((show_v==1)?'selected="selected"':'')+' >Afficher</option>'
	    	    	       +'<option value="0" '+((show_v==0)?'selected="selected"':'')+' >Ne pas afficher</option>'
	    	    	       +'</select>&nbsp;&nbsp;<input class="'+((type_v=="varchar(1000)" || type_v=="varchar(1001)" || type_v=="varchar(1002)" || type_v=="varchar(1003)" || type_v=="varchar(1004)" || type_v=="varchar(1005)" || type_v=="varchar(1007)")?"show1":"hide1")+'" title="Séparateur ;" size="13" placeholder="   Séparateur ;           " type="text" id="form_choix_'+index+'" name="form[choix]['+index+']" value="'+((choix_v)?choix_v:'')+'" /></td></tr>');


		   delete_input($prototype,((nom_v)?nom_v:''));
		   $container.append($prototype);

		   // ajout d'un autocompletion sur le champs du choix des type
		   /*var deps = ['INT','VARCHAR','CHAR','DOUBLE','DATE', 'DATETIME', 'YEAR', 'TEXT']; //
		   $("input[id^='form_type']").typeahead({source: deps});*/

		   index++;
		   return false;
	   }


	  function delete_input($prototype,$id){
		  var $deletelink=$('<a href="#" id="delete_input_'+index+'"><img src="images/delete.png" /></a>');
		   $prototype.append($deletelink);
		   $deletelink.click(function(){
			  $prototype.remove();
           if($id){ form3.form_elem_del.value=form3.form_elem_del.value+$id+","; }
			  //e.preventDefault();
			  return false;
		   });
	  }

 <?php
 if(isset($totalRows_liste_activite) && $totalRows_liste_activite>0){ $i=0; do{
if(isset($libelle[$i])){
$lib=explode("=",$libelle[$i]);
$libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"";   }

 if($row_liste_activite["Field"]!="LKEY" && !in_array($row_liste_activite["Field"],$interdit_array)){    ?>

 add_input($container,"<?php echo $row_liste_activite['Field']; ?>","<?php echo $row_liste_activite['Type']; ?>","<?php echo ($row_liste_activite['Null']=='YES')?'':'NOT NULL'; ?>","<?php echo (in_array($row_liste_activite['Field'],$entete_array))?1:0; ?>",<?php echo (isset($choix_array[$row_liste_activite['Field']]))?GetSQLValueString(substr($choix_array[$row_liste_activite['Field']],0,strlen($choix_array[$row_liste_activite['Field']])-1),'text'):"''"; ?>,<?php if(isset($libelle_array[$row_liste_activite['Field']])) echo GetSQLValueString($libelle_array[$row_liste_activite['Field']], 'text'); else echo "''"; ?>);

 <?php } $i++; }while($row_liste_activite  = mysql_fetch_assoc($liste_activite)); } ?>

	});

function afficher_choix(id,val){  form_name="form_choix_"+id;
  if(val=="VARCHAR(1000)" || val=="VARCHAR(1001)" || val=="VARCHAR(1002)" || val=="VARCHAR(1003)" || val=="VARCHAR(1004)" || val=="VARCHAR(1005)" || type_v=="varchar(1007)"){ document.getElementById(form_name).className="show1"; }
  else{ document.getElementById(form_name).className="hide1"; }
}
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification de la feuille":"Nouvelle feuille"?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="add_lnk"></span></h4></div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
<tr valign="top">
      <td>
        <div class="form-group">
          <label for="nomtable" class="col-md-3 control-label">Nom <span class="required">*</span></label>
          <div class="col-md-9">
            <input type="text" required="required" class="form-control required" name="nomtable" id="nomtable" value="<?php echo (isset($nomT))?$nomT:""; ?>" />
          </div>
        </div>
      </td>
      <td>
        <div class="form-group">
          <label for="lnomtable" class="col-md-3 control-label">Libellé <span class="required">*</span></label>
          <div class="col-md-9">
            <textarea rows="1" placeholder="" required="required" class="form-control required" name="lnomtable" id="lnomtable"><?php echo (isset($lib_nom_fich))?$lib_nom_fich:""; ?></textarea>
          </div>
        </div>
      </td>
</tr>
<tr valign="top">
      <td>
        <div class="form-group">
          <label for="nombre" class="col-md-3 control-label">Nbr. ligne (impr.) <span class="required">*</span></label>
          <div class="col-md-3">
            <input type="text" required="required" class="form-control required" name="nombre" id="nombre" value="<?php echo (isset($nombre))?$nombre:"10"; ?>" />
          </div>
        </div>
        <div class="">
          <label for="icon" class="col-md-2 control-label">Icon (pour carte) </label>
          <div class="col-md-4">
          <div id="photo_prev">
          <?php if(isset($icon) && file_exists($icon)) { ?>
          <img src="<?php echo $icon; ?>" width='20' height='20' alt='preview'>
          <?php } ?>
          </div>
            <input type="file" class="form-control" name="icon" id="icon" value="" data-style="fileinput" alt='preview' onchange="readImgURL(this,'photo_prev',20,20);" size="32" accept="image/x-png, image/gif, image/jpeg" />
          </div>
        </div>
      </td>
      <td>
        <div class="form-group">
          <label for="note" class="col-md-3 control-label">Note </label>
          <div class="col-md-9">
            <textarea rows="1" placeholder="" class="form-control" name="note" id="note"><?php echo (isset($note))?$note:""; ?></textarea>
          </div>
        </div>
      </td>
</tr>
<tr valign="top">
      <td colspan="2">
        <div class="">
          <label for="lignetotal" class="col-md-2 control-label">Ligne Total</label>
          <div class="col-md-1">
          <input type="checkbox" class="" name="lignetotal" id="lignetotal" <?php echo (isset($lignetotal) && $lignetotal==1)?'checked="checked"':""; ?> value="" />
          </div>
        </div>
        <div class="">
          <label for="colnum" class="col-md-2 control-label">Colonne n&deg;</label>
          <div class="col-md-1">
          <input type="checkbox" class="" name="colnum" id="colnum" <?php echo (isset($colnum) && $colnum==1)?'checked="checked"':""; ?> value="" />
          </div>
        </div>
        <div class="">
          <label for="detail_sexe" class="col-md-2 control-label" title='Les champs de nom "datenaissace" de type date et "sexe" doivent exister'>Synth&egrave;se sexe</label>
          <div class="col-md-1">
          <input type="checkbox" class="" name="detail_sexe" id="detail_sexe" <?php echo (isset($detail_sexe) && $detail_sexe==1)?'checked="checked"':""; ?> value="" title='Les champs de nom "datenaissace" de type date et "sexe" doivent exister' />
          </div>
        </div>
      </td>
      <td>
        <!--<div class="form-group">
          <label for="note" class="col-md-3 control-label"></label>
          <div class="col-md-9">
          </div>
        </div>-->
      </td>
    </tr>
</table>
<input type="hidden" name="nomvtable" class="form-control required" value="<?php if(isset($cp)) echo $cp."_details_".$numero; ?>" />
<div class="form-actions">
<input type="hidden" id="form_elem_del" name="form_elem_del" value="" />
<input type="hidden" name="classeur" value="<?php echo $_GET["classeur"]; ?>" />
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2)) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette feuille ?','<?php echo $_GET["id"]; ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->

<div class="clear pull-left p11"><div><b><u>NB</u> :</b> les mots clés comme <b>nom du champ</b> sont utiles</div>
<div><b>village</b> : donne le choix des localités | <b>sexe</b> : le genre homme femme | <b>datenaissance</b> : Date naissance | <b>shp</b> : Sahpe File pour la cartographie | <b>gpx</b> : GPX format des GPS pour la cartographie | <b>longitude</b> : Coordonnée longitude | <b>latitude</b> : Coordonnée latitude</div></div>
<div class="clear pull-left" style="color: red"><div><b>Les mots clés interdits :</b></div>
<b>classeur, LKEY, annee, projet, structure, id_personnel, date_enregistrement, modifier_le, modifier_par, etat</b>
</div>
</div>
</form>

</div> </div>
<?php } ?>