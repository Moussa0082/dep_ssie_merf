<?php



///////////////////////////////////////////////



/*                 SSE                       */



/*	Conception & Développement: SEYA SERVICES */



///////////////////////////////////////////////



session_start();



include_once 'system/configuration.php';



$config = new Config;




/*
function strrevpos($instr, $needle)

{

    $rev_pos = strpos (strrev($instr), strrev($needle));

    if ($rev_pos===false) return false;

    else return strlen($instr) - $rev_pos - strlen($needle);

};



function after_last ($this, $inthat)

    {

        if (!is_bool(strrevpos($inthat, $this)))

        return substr($inthat, strrevpos($inthat, $this)+strlen($this));

    };*/



if (!isset ($_SESSION["clp_id"])) {



  //header(sprintf("Location: %s", "./"));



  exit;



}



include_once $config->sys_folder . "/database/db_connexion.php";



//header('Content-Type: text/html; charset=ISO-8859-15');







$dir = './attachment/mission_atelier/';



if(isset($_GET['annee'])) $annee=($_GET['annee']); else $annee = date("Y");







if(isset($_GET["id"]) && !empty($_GET["id"]))

{

  $id=($_GET["id"]);

  $query_liste_activite = "SELECT * FROM liste_op WHERE id_op='$id'";
     try{
    $liste_activite = $pdar_connexion->prepare($query_liste_activite);
    $liste_activite->execute();
    $row_liste_activite = $liste_activite ->fetch();
    $totalRows_liste_activite = $liste_activite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

}


/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_entete = "SELECT nombre FROM niveau_config WHERE ".$_SESSION["clp_where"]." LIMIT 1";

$entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_entete  = mysql_fetch_assoc($entete);

$totalRows_entete  = mysql_num_rows($entete);

$code_len = $row_entete["nombre"];*/


/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_act = "SELECT * FROM activite_projet WHERE ".$_SESSION["clp_where"]." and niveau='$code_len'";
$liste_act = mysql_query_ruche($query_liste_act, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_act = mysql_fetch_assoc($liste_act);
$totalRows_liste_act = mysql_num_rows($liste_act);
$activite_array = array();*/



/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_respo = "SELECT id_personnel, nom, prenom FROM personnel where structure='".$_SESSION["clp_structure"]."' and projet like '%".$_SESSION["clp_structure"]."|%' ";
$liste_respo  = mysql_query_ruche($query_liste_respo , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_respo  = mysql_fetch_assoc($liste_respo);
$totalRows_liste_respo  = mysql_num_rows($liste_respo);*/

/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_perso = "SELECT distinct fonction FROM personnel where structure='".$_SESSION["clp_structure"]."' and projet like '%".$_SESSION["clp_structure"]."|%' and fonction!='Administrateur' ";
$liste_perso = mysql_query_ruche($query_liste_perso, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$tableauFonct=array();
while($ligne=mysql_fetch_assoc($liste_perso)){$tableauFonct[]=$ligne['fonction'];}



mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_do = "SELECT distinct fonction FROM personnel where fonction!='Administrateur' ";

$liste_do = mysql_query_ruche($query_liste_do, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_liste_do = mysql_fetch_assoc($liste_do);

$totalRows_liste_do = mysql_num_rows($liste_do);



mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_responsable = "SELECT * FROM ugl order by abrege_ugl asc";

$liste_responsable = mysql_query_ruche($query_liste_responsable, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_liste_responsable = mysql_fetch_assoc($liste_responsable);

$totalRows_liste_responsable = mysql_num_rows($liste_responsable);*/



//liste village
/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_village = "SELECT code_village,nom_village FROM village  order by code_village asc";

$liste_village = mysql_query_ruche($query_liste_village, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_liste_village = mysql_fetch_assoc($liste_village);

$totalRows_liste_village = mysql_num_rows($liste_village);*/


//liste village
 $query_ugl_user = "SELECT * FROM ugl where code_ugl='".$_SESSION["clp_structure"]."'";
   try{
    $ugl_user = $pdar_connexion->prepare($query_ugl_user);
    $ugl_user->execute();
    $row_ugl_user = $ugl_user ->fetch();
    $totalRows_ugl_user = $ugl_user->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
 $cercle_projet = str_replace("|",",",$row_ugl_user["region_concerne"]);

$query_liste_village1 = "SELECT  code_village, nom_village, nom_commune FROM village, commune where code_commune=commune and FIND_IN_SET(departement, '".$cercle_projet."' )  order by nom_commune asc";
     try{
    $liste_village1 = $pdar_connexion->prepare($query_liste_village1);
    $liste_village1->execute();
    $row_liste_village1 = $liste_village1 ->fetchAll();
    $totalRows_liste_village1 = $liste_village1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//echo $query_liste_village1 ; exit;
//faitiere
$query_liste_faitiere = "SELECT * FROM ".$database_connect_prefix."fiche_ong ";
     try{
    $liste_faitiere = $pdar_connexion->prepare($query_liste_faitiere);
    $liste_faitiere->execute();
    $row_liste_faitiere = $liste_faitiere ->fetchAll();
    $totalRows_liste_faitiere = $liste_faitiere->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


//spéculation
$query_liste_speculation = "SELECT id_sp_maillon, speculation.libelle as speculation, maillon.libelle as maillon  FROM ".$database_connect_prefix."speculation, speculation_maillon, maillon where id_speculation=speculation and id_maillon=maillon order by   maillon.libelle,  speculation.libelle";
     try{
    $liste_speculation = $pdar_connexion->prepare($query_liste_speculation);
    $liste_speculation->execute();
    $row_liste_speculation = $liste_speculation ->fetchAll();
    $totalRows_liste_speculation = $liste_speculation->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


//Maillon
$query_liste_maillon = "SELECT * FROM ".$database_connect_prefix."maillon ";
     try{
    $liste_maillon = $pdar_connexion->prepare($query_liste_maillon);
    $liste_maillon->execute();
    $row_liste_maillon = $liste_maillon ->fetchAll();
    $totalRows_liste_maillon = $liste_maillon->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


//imf
$query_liste_imf = "SELECT * FROM ".$database_connect_prefix."imf ";
     try{
    $liste_imf = $pdar_connexion->prepare($query_liste_imf);
    $liste_imf->execute();
    $row_liste_imf = $liste_imf ->fetchAll();
    $totalRows_liste_imf = $liste_imf->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


?>





<script>

	$().ready(function() {

		// validate the comment form when it is submitted
 $(".modal-dialog", window.parent.document).width(800);
		$("#form1").validate();

        $(".select2-select-00").select2({allowClear:true});

        $("#ui-datepicker-div").remove();

        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});

});

</script>

<style>

#mtable2 .dataTables_length, #mtable2 .dataTables_info { float: left; font-size: 10px;}

#mtable2 .dataTables_length, #mtable2 .dataTables_paginate, .DTTT, .ColVis { display: none;}

@media(min-width:558px){.col-md-12 {width: 100%;}.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}

.ui-datepicker-append {display: none;}

textarea#message, textarea#observation { height: 400px; }

</style>
</style>
<style type="text/css"> 
#madiv{height:450px;overflow:auto} 
</style>

<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<4) { ?>



<?php if(!isset($_GET['rapport'])) { ?>





<div class="widget box">

<div class="widget-content scroller">



<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">

<div id="madiv">

  <table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:10px;">

      <tr>
        <td valign="top"><div class="form-group">
 <label for="sigle_op" class="col-md-12 control-label">Sigle groupement <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea name="sigle_op" rows="1" class="form-control required" id="sigle_op"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['sigle_op'];?></textarea>
             </div>
      </div></td>
        <td colspan="2" valign="top"><div class="form-group">
          <label for="nom_op" class="col-md-12 control-label">Nom du groupement </label>
          <div class="col-md-12">
            <textarea name="nom_op" rows="1" class="form-control required" id="nom_op"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['nom_op'];?></textarea>
          </div>
      </div></td>
        <td valign="top"><div class="form-group">
          <label for="village" class="col-md-12 control-label">Village si&egrave;ge <span class="required">*</span></label>
          <div class="col-md-12">
            <select name="village" id="village" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez un village">
              <option value="">Aucune</option>
              <?php if($totalRows_liste_village1>0){ $expl = (isset($row_liste_activite["village"]) && !empty($row_liste_activite["village"]))?explode(',',$row_liste_activite["village"]):array(); foreach($row_liste_village1 as $row_liste_village11){   ?>
              <option value="<?php echo $row_liste_village11['code_village']; ?>" <?php if(in_array($row_liste_village11['code_village'],$expl)) {echo "SELECTED";} ?>><?php echo $row_liste_village11['nom_village']."/ ".$row_liste_village11['nom_commune']; ?></option>
                <?php  }  } 
				?>
            </select>
          </div>
      </div></td>
      </tr>
      <tr>
        <td colspan="2" valign="top">	  <div class="form-group">
          <label for="villages_associes" class="col-md-12 control-label">Villages associ&eacute;s  </label>
          <div class="col-md-12">
            <select name="villages_associes[]" id="villages_associes" class="full-width-fix select2-select-00" data-placeholder="S&eacute;lectionnez un village" multiple>
              <option value="">Aucune</option>
              <?php if($totalRows_liste_village1>0){ $expl1 = (isset($row_liste_activite["villages_associes"]) && !empty($row_liste_activite["villages_associes"]))?explode(',',$row_liste_activite["villages_associes"]):array(); foreach($row_liste_village1 as $row_liste_village1){  ?>
              <option value="<?php echo $row_liste_village1['code_village']; ?>" <?php if(in_array($row_liste_village1['code_village'],$expl1)) {echo "SELECTED";} ?>><?php echo $row_liste_village1['nom_village']; ?></option>
              <?php  }  }
			 ?>
              </select>
            </div>
        </div></td>
        <td valign="top"><div class="form-group">
 <label for="adresse" class="col-md-12 control-label">Adresse<span class="required">*</span></label>
          <div class="col-md-12">
 <textarea name="adresse" rows="1" class="form-control required" id="adresse"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['adresse'];?></textarea>  
            </div>
      </div></td>
        <td valign="top"><div class="form-group">
 <label for="personne_ressource" class="col-md-12 control-label">Personnes ressources<span class="required">*</span></label>
          <div class="col-md-12">
            <textarea name="personne_ressource" rows="1" class="form-control required" id="personne_ressource"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['personne_ressource'];?></textarea>  
            </div>
      </div></td>
      </tr>
      <tr>
        <td valign="top"><div class="form-group">
 <label for="contact" class="col-md-12 control-label">Contact<span class="required">*</span></label>
          <div class="col-md-12">
            <textarea name="contact" rows="1" class="form-control required" id="contact"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['contact'];?></textarea>  
            </div>
      </div></td>
      <td colspan="2" valign="top" bgcolor="#FFFFCC"><div class="form-group">
 <label for="old_village" class="col-md-12 control-label">Old Village<span class="required">*</span></label>
          <div class="col-md-12" style="font-size:16px"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['old_village'];?></div>
      </div></td>
        <!--  <td valign="top" bgcolor="#FFFFCC"><div class="form-group">
    <label for="nb_jeune" class="col-md-12 control-label">Nb de jeunes <span class="required">*</span></label>
          <div class="col-md-12">
            <input name="nb_jeune" type="text" class="form-control required" id="nb_jeune" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['nb_jeune'];?>" />
          </div>
      </div></td>-->
      </tr>
   <tr style="background-color:#CCCCCC; font-size:14px; font:bold">
     <td colspan="4" valign="top">  (<i>Informations administratives</i>) </td>
     </tr>
   <tr>
     <td valign="top"><div class="form-group">
    <label for="date_creation" class="col-md-12 control-label">Date de cr&eacute;ation <span class="required">*</span></label>
          <div class="col-md-12">
            <input type="text" class="form-control datepicker required" name="date_creation" id="date_creation" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_liste_activite['date_creation']))))); else echo date("d/m/Y"); ?>" />
          </div>
      </div></td>
     <td valign="top"><div class="form-group">
    <label for="type_organisation" class="col-md-12 control-label">Type de groupement    <span class="required">*</span></label>
          <div class="col-md-12">
            <select name="type_organisation" id="type_organisation" class="form-control required">
              <option value="">Selectionnez</option>
              <option value="Mixte" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite["type_organisation"]=="Mixte") {echo "SELECTED";} ?>>Mixte</option>
              <option value="Femme" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite["type_organisation"]=="Femme") {echo "SELECTED";} ?>>Femme</option>
			  <!--<option value="Homme" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite["type_organisation"]=="Homme") {echo "SELECTED";} ?>>Homme</option>-->
			  <option value="Jeunes" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite["type_organisation"]=="Jeunes") {echo "SELECTED";} ?>>Jeunes</option>
            </select>
          </div>
      </div></td>
     <td valign="top"><div class="form-group">
    <label for="existence_legale" class="col-md-12 control-label">Existence l&eacute;gale   <span class="required">*</span></label>
          <div class="col-md-12">
            <select name="existence_legale" id="existence_legale" class="form-control required">
              <option value="">Selectionnez</option>
              <option value="Non" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite["existence_legale"]=="Non") {echo "SELECTED";} ?>>Non</option>
              <option value="Oui" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite["existence_legale"]=="Oui") {echo "SELECTED";} ?>>Oui</option>
            </select>
          </div>
      </div></td>
     <td valign="top"><div class="form-group">
    <label for="date_creation" class="col-md-12 control-label">Date du r&eacute;c&eacute;piss&eacute;s    <span class="required"></span></label>
          <div class="col-md-12">
           <input type="text" class="form-control datepicker required" name="date_creation" id="date_creation" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_liste_activite['date_creation']))))); else echo date("d/m/Y"); ?>" />
          </div>
      </div></td>
   </tr>
   <tr>
     <td valign="top"><div class="form-group">
         <label for="faitiere" class="col-md-12 control-label">ONG responsable </label>
         <div class="col-md-12">
           <select name="faitiere[]" id="faitiere" class="full-width-fix select2-select-00" data-placeholder="S&eacute;lectionnez une ONG">
             <option value="">Aucune</option>
             <?php if($totalRows_liste_faitiere>0){ $expl = (isset($row_liste_activite["faitiere"]) && !empty($row_liste_activite["faitiere"]))?explode(',',$row_liste_activite["faitiere"]):array(); foreach($row_liste_faitiere as $row_liste_faitiere){ ?>
             <option value="<?php echo $row_liste_faitiere['id_ong']; ?>" <?php if(in_array($row_liste_faitiere['id_ong'],$expl)) {echo "SELECTED";} ?>><?php echo $row_liste_faitiere['sigle_ong']; ?></option>
             <?php  } } ?>
           </select>
         </div>
     </div></td>
     <td valign="top"><div class="form-group">
         <label for="nom_imf" class="col-md-12 control-label">SFD </label>
         <div class="col-md-12">
           <select name="nom_imf[]" id="nom_imf" class="full-width-fix select2-select-00" data-placeholder="S&eacute;lectionnez une IF" multiple="multiple">
             <option value="">Aucune</option>
             <?php if($totalRows_liste_imf>0){ $expl = (isset($row_liste_activite["nom_imf"]) && !empty($row_liste_activite["nom_imf"]))?explode(',',$row_liste_activite["nom_imf"]):array(); foreach($row_liste_imf as $row_liste_imf){ ?>
             <option value="<?php echo $row_liste_imf['id_imf']; ?>" <?php if(in_array($row_liste_imf['id_imf'],$expl)) {echo "SELECTED";} ?>><?php echo $row_liste_imf['sigle']; ?></option>
             <?php  }  } ?>
           </select>
         </div>
     </div></td>
     <td valign="top"><div class="form-group">
       <label for="numero_compte1" class="col-md-12 control-label">Num&eacute;ro de compte 1<span class="required">*</span></label>
       <div class="col-md-12">
         <textarea name="numero_compte1" rows="1" class="form-control required" id="numero_compte1"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['numero_compte1']; else echo "-";?></textarea>
         </div>
     </div></td>
     <td valign="top"><div class="form-group">
       <label for="numero_compte2" class="col-md-12 control-label">Num&eacute;ro compte 2</label>
       <div class="col-md-12">
         <textarea name="numero_compte2" rows="1" class="form-control" id="numero_compte2"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['numero_compte2']; else echo "-";?></textarea>
         </div>
     </div></td>
   </tr>
   <tr>
     <td colspan="4" valign="top"><div class="form-group">
         <label for="speculation" class="col-md-12 control-label">Activit&eacute; principale <span class="required">*</span></label>
         <div class="col-md-12">
           <select name="speculation[]" id="speculation" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez une activit&eacute;" multiple="multiple">
             <option value="">Aucune</option>
             <?php if($totalRows_liste_speculation>0){ $expl = (isset($row_liste_activite["speculation"]) && !empty($row_liste_activite["speculation"]))?explode(',',$row_liste_activite["speculation"]):array(); foreach($row_liste_speculation as $row_liste_speculation){ ?>
             <option value="<?php echo $row_liste_speculation['id_sp_maillon']; ?>" <?php if(in_array($row_liste_speculation['id_sp_maillon'],$expl)) {echo "SELECTED";} ?>><?php echo $row_liste_speculation['maillon']." de ".$row_liste_speculation['speculation']; ?></option>
             <?php  }  } ?>
           </select>
         </div>
     </div></td>
     </tr>
   <tr>
     <td colspan="2" valign="top"><div class="form-group">
       <label for="observations" class="col-md-12 control-label">Observations</label>
       <div class="col-md-12">
         <textarea name="observations" rows="1" class="form-control" id="observations"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['observation']." ".$row_liste_activite['village']." /".$row_liste_activite['speculation']; else echo "-";?></textarea>
         </div>
     </div></td>
     <td valign="top"><div class="form-group">
 <label for="date_collecte" class="col-md-12 control-label">Date de remplissage <span class="required">*</span></label>
          <div class="col-md-12">
            <input type="text" class="form-control datepicker required" name="date_collecte" id="date_collecte" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_liste_activite['date_collecte']))))); else echo date("d/m/Y"); ?>" />
          </div>
      </div></td>
     <td valign="top"><div class="form-group">
 <label for="nom_collecteur" class="col-md-12 control-label">Nom collecteur</label>
          <div class="col-md-12">
            <textarea name="nom_collecteur" rows="1" class="form-control" id="nom_collecteur"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['nom_collecteur']; else echo "-";?></textarea>
          </div>
      </div></td>
   </tr>
  </table>

</div>

<div class="form-actions">



<input name="annee" id="annee" type="hidden" value="<?php if(isset($_GET["annee"])) echo $_GET["annee"];?>" size="32" alt="">

<input name="code_mission" id="code_mission" type="hidden" value="<?php  echo $max; ?>" size="32" alt="" />



  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "Modifier";else echo "Enregistrer";?>" />



  <input name="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "MM_update";else echo "MM_insert";?>" type="hidden" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]);else echo "MM_insert";?>" size="32" alt="">



<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) {?>



<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">



<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette fiche ?','<?php echo ($_GET["id"]);?>');" class="btn btn-danger pull-left" value="Supprimer" />



<?php }?>







  <input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">



</div>



</form>







</div> </div>



<?php } else { //Rapport ?>



<div class="widget box">



<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php if(isset($_GET['id'])) echo "Ajout de rapport"; ?></h4></div>



<div class="widget-content">



<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">



  <table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:10px;">



    <tr>



      <td colspan="2" valign="middle"><div class="form-group">



          <label for="rapport" class="col-md-4 control-label">Rapport <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && !empty($row_liste_activite['rapport'])) echo ""; else echo '<span class="required">*</span>'; ?></label>



          <div class="col-md-8">



            <input class="form-control <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && !empty($row_liste_activite['rapport'])) echo ""; else echo "required"; ?>" type="file" name="rapport" id="rapport" value="" size="32" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/pdf,application/vnd.ms-word,image/jpeg,.doc,.docx,.zip,.rar"  />



          </div>



      </div></td>



    </tr>

 <tr>



      <td valign="top"><div class="form-group">



          <label for="date_aller" class="col-md-12 control-label">Date aller effective <span class="required">*</span></label>



          <div class="col-md-12">



             <input type="text" class="form-control datepicker required" name="date_aller" id="date_aller" value="<?php if(isset($row_liste_activite['date_aller'])) echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_liste_activite['date_aller']))))); else echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_liste_activite['debut']))))); ?>">



          </div>



      </div></td>



      <td valign="top"><div class="form-group">



          <label for="date_retour" class="col-md-12 control-label">Date retour effective  <span class="required">*</span></label>



          <div class="col-md-12">



             <input type="text" class="form-control datepicker required" name="date_retour" id="date_retour" value="<?php if(isset($row_liste_activite['date_retour'])) echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_liste_activite['date_retour']))))); else echo date("d/m/Y"); ?>">



          </div>



      </div></td>



    </tr>

    <tr valign="top">



      <td colspan="2"><div class="form-group">



          <label for="observation" class="col-md-12 control-label">Observation </label>



          <div class="col-md-12"></div>



      </div></td>



    </tr>



  </table>



<div class="form-actions">



<input name="annee" id="annee" type="hidden" value="<?php if(isset($_GET["annee"])) echo $_GET["annee"];?>" size="32" alt="">



  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "Modifier";else echo "Enregistrer";?>" />



  <input name="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "MM_update";else echo "MM_insert";?>" type="hidden" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]);else echo "MM_insert";?>" size="32" alt="">







  <input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">



</div>



</form>







</div> </div>



<?php } } ?>