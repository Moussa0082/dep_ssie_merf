<?php



///////////////////////////////////////////////



/*                 SSE                       */



/*	Conception & Développement: SEYA SERVICES */



///////////////////////////////////////////////



session_start();



include_once 'system/configuration.php';



$config = new Config;





function strrevpos($instr, $needle)

{

    $rev_pos = strpos (strrev($instr), strrev($needle));

    if ($rev_pos===false) return false;

    else return strlen($instr) - $rev_pos - strlen($needle);

};



/*function after_last ($this, $inthat)

    {

        if (!is_bool(strrevpos($inthat, $this)))

        return substr($inthat, strrevpos($inthat, $this)+strlen($this));

    };*/



if (!isset ($_SESSION["clp_id"])) {



  //header(sprintf("Location: %s", "./"));



  exit;



}



include_once $config->sys_folder . "/database/db_connexion.php";



//header('Content-Type: text/html; charset=UTF-8');







$dir = './attachment/mission_atelier/';



if(isset($_GET['annee'])) $annee=intval($_GET['annee']); else $annee = date("Y");







if(isset($_GET["id"]) && !empty($_GET["id"]))

{

  $id=($_GET["id"]);

  $query_liste_activite = "SELECT * FROM ateliers WHERE id_atelier='$id'";
   try{
    $liste_activite = $pdar_connexion->prepare($query_liste_activite);
    $liste_activite->execute();
    $row_liste_activite = $liste_activite ->fetch();
    $totalRows_liste_activite = $liste_activite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

}



$query_entete = "SELECT nombre FROM niveau_config WHERE projet='".$_SESSION["clp_projet"]."' LIMIT 1";
   try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$code_len = $row_entete["nombre"];


$query_liste_act = "SELECT * FROM activite_projet WHERE projet='".$_SESSION["clp_projet"]."' and niveau='$code_len'";
   try{
    $liste_act = $pdar_connexion->prepare($query_liste_act);
    $liste_act->execute();
    $row_liste_act = $liste_act ->fetchAll();
    $totalRows_liste_act = $liste_act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$activite_array = array();



/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_respo = "SELECT id_personnel, nom, prenom FROM personnel where structure='".$_SESSION["clp_structure"]."' and projet like '%".$_SESSION["clp_structure"]."|%' ";
$liste_respo  = mysql_query_ruche($query_liste_respo , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_respo  = mysql_fetch_assoc($liste_respo);
$totalRows_liste_respo  = mysql_num_rows($liste_respo);*/


$query_liste_do = "SELECT distinct fonction FROM personnel where fonction!='Administrateur' ";
   try{
    $liste_do = $pdar_connexion->prepare($query_liste_do);
    $liste_do->execute();
    $row_liste_do = $liste_do ->fetchAll();
    $totalRows_liste_do = $liste_do->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }



$query_liste_responsable = "SELECT * FROM ugl order by abrege_ugl asc";
   try{
    $liste_responsable = $pdar_connexion->prepare($query_liste_responsable);
    $liste_responsable->execute();
    $row_liste_responsable = $liste_responsable ->fetchAll();
    $totalRows_liste_responsable = $liste_responsable->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }



//liste village

$query_liste_village = "SELECT code_commune,nom_commune FROM commune  order by code_commune asc";
   try{
    $liste_village = $pdar_connexion->prepare($query_liste_village);
    $liste_village->execute();
    $row_liste_village = $liste_village ->fetchAll();
    $totalRows_liste_village = $liste_village->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }



//Max num

$query_edit_max = "SELECT code_mission as max FROM ateliers order by id_atelier desc limit 1";
   try{
    $edit_max = $pdar_connexion->prepare($query_edit_max);
    $edit_max->execute();
    $row_edit_max = $edit_max ->fetch();
    $totalRows_edit_max = $edit_max->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$max = (isset($row_edit_max["max"]) and $row_edit_max["max"]>0)?$row_edit_max["max"]+1:1;

if(intval($max)<10) $max="00".intval($max); elseif(intval($max)<100) $max="0".intval($max);

?>



<script>

	$().ready(function() {

		// validate the comment form when it is submitted

		$(".form-horizontal").validate();

        $(".modal-dialog", window.parent.document).width(700);

        $(".select2-select-00").select2({allowClear:true});

	});

</script>



<style>

#mtable2 .dataTables_length, #mtable2 .dataTables_info { float: left; font-size: 10px;}

#mtable2 .dataTables_length, #mtable2 .dataTables_paginate, .DTTT, .ColVis { display: none;}

@media(min-width:558px){.col-md-12 {width: 100%;}.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}

.ui-datepicker-append {display: none;}

textarea#message, textarea#observation { height: 400px; }

</style>

<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<4) { ?>



<?php if(!isset($_GET['rapport'])) { ?>





<div class="widget box">



<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php if(isset($_GET['id'])) echo "Modifier la mission <u>".$row_liste_activite['code_mission']."</u>"; else echo "Ajouter la mission <u>".$max."</u>"; ?></h4></div>



<div class="widget-content scroller">



<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">



  <table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:10px;">



    <tr>



      <td colspan="2" valign="top"><div class="form-group">



          <label for="code_activite" class="col-md-12 control-label">Activit&eacute; <span class="required">*</span></label>



          <div class="col-md-12">

            <select name="code_activite" id="code_activite" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez Activit&eacute;">
              <option></option>
         <option value="RAS" <?php if (isset($row_liste_activite["code_activite"]) && $row_liste_activite['code_activite']=="RAS") {echo "SELECTED";} ?>>Non-d&eacute;finie</option>
              <?php if($totalRows_liste_act>0){ foreach($row_liste_act as $row_liste_act){  ?>
              <option value="<?php echo $row_liste_act['code']; ?>" <?php if (isset($row_liste_activite["code_activite"]) && $row_liste_act['code']==$row_liste_activite["code_activite"]) {echo "SELECTED";} ?>><?php echo $row_liste_act['code'].": ".$row_liste_act['intitule']; ?></option>
                <?php  }  } ?>
            </select>

          </div>



      </div></td>

    </tr>



    <tr>



      <td colspan="2" valign="middle"><div class="form-group">



          <label for="tdr" class="col-md-4 control-label">TDR <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && !empty($row_liste_activite['tdr'])) echo "&nbsp;&nbsp;&nbsp;<a href='./download_file.php?file=".$dir.$row_liste_activite["tdr"]."' title='T&eacute;l&eacute;charger ".$row_liste_activite["tdr"]."' ><img src=\"./images/download.png\" width=\"20\" height=\"20\" alt=\"T&eacute;l&eacute;charger les TDR\" title=\"T&eacute;l&eacute;charger les TDR\"></a>"; else echo '<span class="required">*</span>'; ?></label>



          <div class="col-md-8">



            <input class="form-control <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && !empty($row_liste_activite['tdr'])) echo ""; else echo "required"; ?>" type="file" name="tdr" id="tdr" value="" size="32" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/pdf,application/vnd.ms-word,image/jpeg,.doc,.docx,.zip,.rar"  />

          </div>



      </div></td>

    </tr>



    <tr>



      <td colspan="2" valign="top"><div class="form-group">



          <label for="objectif" class="col-md-12 control-label">Objet <span class="required">*</span></label>



          <div class="col-md-12">



            <textarea name="objectif" rows="1" class="form-control required" id="objectif"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['objectif'];?></textarea>

          </div>



      </div></td>

    </tr>

    <tr>

      <td valign="top" colspan="2"><div class="form-group">



          <label for="moyen_transport" class="col-md-4 control-label">Moyens de transport <span class="required">*</span></label>



          <div class="col-md-8">

            <select name="moyen_transport" id="moyen_transport" class="form-control required">

              <option value="">Selectionnez</option>

              <option value="Projet" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite["moyen_transport"]=="Projet") {echo "SELECTED";} ?>>V&eacute;hicule(s) Projet</option>

              <option value="Projet et Location" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite["moyen_transport"]=="Projet et Location") {echo "SELECTED";} ?>>V&eacute;hicule(s) du Projet et de location</option>

              <option value="Location" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite["moyen_transport"]=="Location") {echo "SELECTED";} ?>>V&eacute;hicule(s) de location</option>

			   <option value="Avion" <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite["moyen_transport"]=="Avion") {echo "SELECTED";} ?>>Voyage &agrave; l'&eacute;tranger</option>

            </select>

          </div>



      </div></td>

     

    </tr>



    <tr valign="top">



      <td valign="top"><div class="form-group">



          <label for="responsable" class="col-md-12 control-label">Initi&eacute;e par  <span class="required">*</span></label>



          <div class="col-md-12">

            <select name="responsable" id="responsable" class="form-control required">

              <option value="">Selectionnez</option>

              <?php if($totalRows_liste_responsable>0) { foreach($row_liste_responsable as $row_liste_responsable){  ?>

              <option <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite["responsable"]==$row_liste_responsable['id_ugl']) {echo "SELECTED";} ?> value="<?php echo $row_liste_responsable['id_ugl'];?>"><?php echo  $row_liste_responsable['nom_ugl'];?></option>

              <?php } } ?>

            </select>

          </div>



      </div></td>



      <td valign="top"><div class="form-group">



          <label for="responsable" class="col-md-12 control-label">Donneur d'ordre  <span class="required">*</span></label>



          <div class="col-md-12">



            <select name="donneur_ordre" id="donneur_ordre" class="form-control required">



              <option value="">Selectionnez</option>



            <?php if($totalRows_liste_do>0) { foreach($row_liste_do as $row_liste_do1){ ?>

<option <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite["donneur_ordre"]==$row_liste_do1['fonction']) {echo "SELECTED";} ?> value="<?php echo $row_liste_do1['fonction'];?>"><?php echo  $row_liste_do1['fonction'];?></option>

  <?php } } ?>

            </select>

          </div>



      </div></td>

    </tr>







	 <tr>



      <td valign="top"><div class="form-group">



          <label for="debut" class="col-md-12 control-label">Date de d&eacute;but  <span class="required">*</span></label>



          <div class="col-md-12">



             <input type="text" class="form-control datepicker required" name="debut" id="debut" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_liste_activite['debut']))))); else echo date("d/m/Y"); ?>">

          </div>



      </div></td>



      <td valign="top"><div class="form-group">



          <label for="fin" class="col-md-12 control-label">Date de fin  <span class="required">*</span></label>



          <div class="col-md-12">



             <input type="text" class="form-control datepicker required" name="fin" id="fin" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_liste_activite['fin']))))); else echo date("d/m/Y"); ?>">

          </div>



      </div></td>

    </tr>

	 <tr>



      <td valign="top" colspan="2"> <div class="form-group">

          <label for="lieu" class="col-md-2 control-label">Lieu <span class="required">*</span></label>

          <div class="col-md-10">

            <select name="lieu[]" id="lieu" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez un lieu" multiple>

              <option></option>

              <option value="">Selectionnez</option>

              <?php if($totalRows_liste_village>0){ $expl = (isset($row_liste_activite["lieu"]) && !empty($row_liste_activite["lieu"]))?explode(',',$row_liste_activite["lieu"]):array(); foreach($row_liste_village as $row_liste_village){ ?>

              <option value="<?php echo $row_liste_village['nom_commune']; ?>" <?php if(in_array($row_liste_village['nom_commune'],$expl)) {echo "SELECTED";} ?>><?php echo $row_liste_village['nom_commune']; ?></option>

                <?php  }  } ?>

            </select>

          </div>

        </div>

      </td>

    </tr>

	 <tr>
	   <td valign="top" colspan="2"><div class="form-group">
          <label for="participants" class="col-md-2 control-label">Participants<span class="required">*</span></label>
          <div class="col-md-10">
            <select name="participants[]" id="participants" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez un participant" multiple>
              <option></option>
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_do>0){ $expl = (isset($row_liste_activite["participants"]) && !empty($row_liste_activite["participants"]))?explode(',',$row_liste_activite["participants"]):array(); foreach($row_liste_do as $row_liste_do){ ?>
              <option value="<?php echo $row_liste_do['fonction']; ?>" <?php if(in_array($row_liste_do['fonction'],$expl)) {echo "SELECTED";} ?>><?php echo $row_liste_do['fonction']; ?></option>
                <?php  }} ?>
            </select>
          </div>
      </div></td>
	  	   </tr>



  </table>



<div class="form-actions">



<input name="annee" id="annee" type="hidden" value="<?php if(isset($_GET["annee"])) echo $_GET["annee"];?>" size="32" alt="">

<input name="code_mission" id="code_mission" type="hidden" value="<?php  echo $max; ?>" size="32" alt="" />



  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "Modifier";else echo "Enregistrer";?>" />



  <input name="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "MM_update";else echo "MM_insert";?>" type="hidden" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]);else echo "MM_insert";?>" size="32" alt="">



<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) {?>



<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">



<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cet at&eacute;lier ?','<?php echo ($_GET["id"]);?>');" class="btn btn-danger pull-left" value="Supprimer" />



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



          <div class="col-md-12">



            <textarea class="form-control" id="observation" name="observation" rows="1"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['observation']; ?></textarea>



          </div>



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