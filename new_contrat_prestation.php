<?php



///////////////////////////////////////////////



/*                 SSE                       */



/*	Conception & Développement: SEYA SERVICES */



///////////////////////////////////////////////



session_start();



include_once 'system/configuration.php';



$config = new Config;





/*function strrevpos($instr, $needle)

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



if(isset($_GET['annee'])) $annee=intval($_GET['annee']); else $annee = date("Y");







if(isset($_GET["id"]) && !empty($_GET["id"]))

{

  $id=($_GET["id"]);

  $query_liste_activite = "SELECT * FROM ".$database_connect_prefix."contrat_prestation WHERE id_contrat='$id'";
  try{
    $liste_activite = $pdar_connexion->prepare($query_liste_activite);
    $liste_activite->execute();
    $row_liste_activite = $liste_activite ->fetch();
    $totalRows_liste_activite = $liste_activite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

}


/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_entete = "SELECT nombre FROM ".$database_connect_prefix."niveau_config WHERE ".$_SESSION["clp_where"]." LIMIT 1";

$entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_entete  = mysql_fetch_assoc($entete);

$totalRows_entete  = mysql_num_rows($entete);

$code_len = $row_entete["nombre"];*/



$query_liste_marche = "SELECT code_marche, intitule FROM ".$database_connect_prefix."plan_marche, ".$database_connect_prefix."version_plan_marche where ".$database_connect_prefix."plan_marche.periode=".$database_connect_prefix."version_plan_marche.id_version order by code_marche";
  try{
    $liste_marche = $pdar_connexion->prepare($query_liste_marche);
    $liste_marche->execute();
    $row_liste_marche = $liste_marche ->fetchAll();
    $totalRows_liste_marche = $liste_marche->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$activite_array = array();
$query_liste_do = "SELECT distinct fonction FROM ".$database_connect_prefix."personnel";
  try{
    $liste_do = $pdar_connexion->prepare($query_liste_do);
    $liste_do->execute();
    $row_liste_do = $liste_do ->fetchAll();
    $totalRows_liste_do = $liste_do->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }



$query_liste_ugl = "SELECT * FROM ".$database_connect_prefix."ugl  order by code_ugl asc";
  try{
    $liste_ugl = $pdar_connexion->prepare($query_liste_ugl);
    $liste_ugl->execute();
    $row_liste_ugl = $liste_ugl ->fetchAll();
    $totalRows_liste_ugl = $liste_ugl->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

/*
$query_type_marche = "SELECT * FROM ".$database_connect_prefix."type_marche  order by type_marche asc";
  try{
    $type_marche = $pdar_connexion->prepare($query_type_marche);
    $type_marche->execute();
    $row_type_marche = $type_marche ->fetchAll();
    $totalRows_type_marche = $type_marche->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }*/



//liste village

$query_liste_village = "SELECT definition, sigle FROM ".$database_connect_prefix."secteur_prive  order by sigle asc";
  try{
    $liste_village = $pdar_connexion->prepare($query_liste_village);
    $liste_village->execute();
    $row_liste_village = $liste_village ->fetchAll();
    $totalRows_liste_village = $liste_village->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }



//Max num
/*
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_max = "SELECT code_mission as max FROM ".$database_connect_prefix."ateliers order by id_atelier desc limit 1";
$edit_max = mysql_query_ruche($query_edit_max, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_max = mysql_fetch_assoc($edit_max);
//$totalRows_edit_max = mysql_num_rows($edit_max);
$max = (isset($row_edit_max["max"]) && after_last('0',$row_edit_max["max"])>0)?after_last('0',$row_edit_max["max"])+1:1;
if(intval($max)<10) $max="00".intval($max); elseif(intval($max)<100) $max="0".intval($max);*/

?>





<script>

	$().ready(function() {

		// validate the comment form when it is submitted

		$("#form1").validate();

        $("#ui-datepicker-div").remove();

        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});

$(".select2-select-00").select2({allowClear:true});

//$("#prestataire").select2({tags:[<?php //echo $datal; ?>]});

//$("#participants").select2({tags:[<?php //echo $datam; ?>]});



});

</script>

<style>

#mtable2 .dataTables_length, #mtable2 .dataTables_info { float: left; font-size: 10px;}

#mtable2 .dataTables_length, #mtable2 .dataTables_paginate, .DTTT, .ColVis { display: none;}

@media(min-width:558px){.col-md-12 {width: 100%;}.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}

.ui-datepicker-append {display: none;}

.select2-container-multi .select2-choices { margin-top: -5px!important; border: none;}

#s2id_code_marche .select2-choice, #s2id_numero .select2-choice { margin-top: -6px!important;}

textarea#message, textarea#observation { height: 400px; }

</style>

<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<4) { ?>



<?php if(!isset($_GET['rapport'])) { ?>





<div class="widget box">



<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">
  <table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:10px;">
    <tr>
      <td colspan="2" valign="top"><div class="form-group">

          <label for="code_marche" class="col-md-12 control-label">March&eacute;s <span class="required">*</span></label>

          <div class="col-md-12">

            <select name="code_marche" id="code_marche" class="form-control select2-select-00 required" data-placeholder="S&eacute;lectionnez Activit&eacute;">

              <option value="">Selectionnez</option>

              <option value="RAS" <?php if (isset($row_liste_activite["code_marche"]) && $row_liste_activite['code_marche']=="RAS") {echo "SELECTED";} ?>>Non-d&eacute;finie</option>

              <?php if($totalRows_liste_marche>0){ foreach($row_liste_marche as $row_liste_marche){ ?>

              <option value="<?php echo $row_liste_marche['code_marche']; ?>" <?php if (isset($row_liste_activite["code_marche"]) && $row_liste_marche['code_marche']==$row_liste_activite["code_marche"]) {echo "SELECTED";} ?>><?php echo $row_liste_marche['code_marche'].": ".$row_liste_marche['intitule']; ?></option>

                <?php  }  } ?>
            </select>
          </div>



      </div></td>
    </tr>



    <tr>



      <td colspan="2" valign="middle"><div class="form-group">



          <label for="contrat" class="col-md-4 control-label">Copie du Contrat <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && !empty($row_liste_activite['contrat'])) echo "&nbsp;&nbsp;&nbsp;<a href='./download_file.php?file=".$dir.$row_liste_activite["contrat"]."' title='T&eacute;l&eacute;charger ".$row_liste_activite["contrat"]."' ><img src=\"./images/download.png\" width=\"20\" height=\"20\" alt=\"T&eacute;l&eacute;charger le Contrat\" title=\"T&eacute;l&eacute;charger le Contrat\"></a>"; else echo '<span class="required">*</span>'; ?></label>



          <div class="col-md-8">



            <input class="form-control <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && !empty($row_liste_activite['contrat'])) echo ""; ?>" type="file" name="contrat" id="contrat" value="" size="32" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/pdf,application/vnd.ms-word,image/jpeg,.doc,.docx,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed"  />
          </div>



      </div></td>
    </tr>



    <tr>
      <td width="47%" valign="top"><div class="form-group">
          <label for="numero_marche" class="col-md-12 control-label">N&deg; du march&eacute; <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea name="numero_marche" rows="1" class="form-control required" id="numero_marche"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['numero_marche'];?></textarea>
          </div>
      </div></td>
 <td width="53%" valign="top"><div class="form-group">
          <label for="numero_lot" class="col-md-12 control-label">N&deg; du lot <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea name="numero_lot" rows="1" class="form-control required" id="numero_lot"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['numero_lot'];?></textarea>
          </div>
      </div></td>
    </tr>
<!--
    <tr>

      <td valign="top" colspan="2"><div class="form-group">



          <label for="moyen_transport" class="col-md-4 control-label">Types de marchés <span class="required">*</span></label>



          <div class="col-md-8"><span class="col-md-12">
            <select name="type_marche" id="type_marche" class="form-control required">
              <option value="">Selectionnez</option>
              <?php //if($totalRows_type_marche>0) { foreach($row_liste_activite as $row_liste_activite){ ?>
              <option <?php //if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite["type_marche"]==$row_type_marche['type_marche']) {echo "SELECTED";} ?> value="<?php //echo $row_type_marche['type_marche'];?>"><?php //echo  $row_type_marche['type_marche'];?></option>
              <?php //} } ?>
            </select>
            </span></div>



      </div></td>

     

    </tr>-->



    <tr valign="top">



      <td valign="top"><div class="form-group">



          <label for="responsable" class="col-md-12 control-label">Port&eacute; par  <span class="required">*</span></label>



          <div class="col-md-12">

            <select name="donneur_ordre" id="donneur_ordre" class="form-control required">

              <option value="">Selectionnez</option>

              <?php if($totalRows_liste_ugl>0) { foreach($row_liste_ugl as $row_liste_ugl){ ?>

              <option <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite["donneur_ordre"]==$row_liste_ugl['abrege_ugl']) {echo "SELECTED";} ?> value="<?php echo $row_liste_ugl['abrege_ugl'];?>"><?php echo  $row_liste_ugl['nom_ugl'];?></option>

              <?php } } ?>
            </select>
          </div>



      </div></td>



      <td valign="top"><div class="form-group">



          <label for="responsable" class="col-md-12 control-label">Responsable de suivi  <span class="required">*</span></label>



          <div class="col-md-12">



            <select name="responsable" id="responsable" class="form-control required">



              <option value="">Selectionnez</option>



            <?php if($totalRows_liste_do>0) { foreach($row_liste_do as $row_liste_do){ ?>

<option <?php if(isset($_GET["id"]) && !empty($_GET["id"]) && $row_liste_activite["responsable"]==$row_liste_do['fonction']) {echo "SELECTED";} ?> value="<?php echo $row_liste_do['fonction'];?>"><?php echo  $row_liste_do['fonction'];?></option>

  <?php } } ?>
            </select>
          </div>



      </div></td>
    </tr>







	 <tr>



      <td valign="top"><div class="form-group">



          <label for="debut" class="col-md-12 control-label">Date de d&eacute;but  <span class="required">*</span></label>



          <div class="col-md-6">



             <input type="text" class="form-control datepicker required" name="debut" id="debut" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_liste_activite['debut']))))); else echo date("d/m/Y"); ?>">
          </div>



      </div></td>



      <td valign="top"><div class="form-group">



          <label for="fin" class="col-md-12 control-label">Date de fin  <span class="required">*</span></label>



          <div class="col-md-6">



             <input type="text" class="form-control datepicker required" name="fin" id="fin" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_liste_activite['fin']))))); else echo date("d/m/Y"); ?>">
          </div>



      </div></td>
    </tr>

	 <tr>



      <td valign="top"> <div class="form-group">
          <label for="prestataire" class="col-md-12 control-label">Prestataires<span class="required">*</span></label>
          <div class="col-md-12">
            <textarea name="prestataire" rows="1" class="form-control required" id="prestataire"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['prestataire'];?></textarea>
          </div>
      </div></td>

 <td valign="top"><div class="form-group">
          <label for="montant_contrat" class="col-md-12 control-label">Montant <span class="required">*</span></label>
          <div class="col-md-6">
            <input name="montant_contrat" type="text" class="form-control required" id="montant_contrat" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['montant_contrat'];?>" />
          </div>
      </div></td>
    </tr>

	   
	     <tr>
      <td colspan="2" valign="top"><div class="form-group">
          <label for="lieu" class="col-md-12 control-label">Objet du contrat <span class="required">*</span></label>
          <div class="col-md-12">
           <textarea name="lieu" rows="1" class="form-control required" id="lieu"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['lieu'];?></textarea>
          </div>
      </div></td>
 </tr>
	     <tr>
	       <td colspan="2" valign="top"><div class="form-group">
          <label for="observations" class="col-md-12 control-label">Observations<span class="required">*</span></label>
          <div class="col-md-12">
            <textarea name="observations" rows="1" class="form-control required" id="observations"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['observation'];?></textarea>
          </div>
      </div></td>
        </tr>
  </table>



<div class="form-actions">



<input name="annee" id="annee" type="hidden" value="<?php if(isset($_GET["annee"])) echo $_GET["annee"];?>" size="32" alt="">

<!--<input name="code_mission" id="code_mission" type="hidden" value="<?php  echo $max; ?>" size="32" alt="" />-->



  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "Modifier";else echo "Enregistrer";?>" />



  <input name="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo "MM_update";else echo "MM_insert";?>" type="hidden" value="<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]);else echo "MM_insert";?>" size="32" alt="">



<?php if (isset ($_GET["id"]) && !empty($_GET["id"])) {?>



<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">



<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cet at&eacute;lier ?','<?php echo ($_GET["id"]);?>');" class="btn btn-danger pull-left" value="Supprimer" />



<?php }?>







  <input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">



</div>



</form>







</div>



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