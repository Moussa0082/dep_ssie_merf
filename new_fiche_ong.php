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



if(isset($_GET['annee'])) $annee=intval($_GET['annee']); else $annee = date("Y");







if(isset($_GET["id"]) && !empty($_GET["id"]))

{

  $id=($_GET["id"]);
  $query_liste_activite = "SELECT * FROM fiche_ong WHERE id_ong='$id'";
   try{
    $liste_activite = $pdar_connexion->prepare($query_liste_activite);
    $liste_activite->execute();
    $row_liste_activite = $liste_activite ->fetch();
    $totalRows_liste_activite = $liste_activite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

}


 if($_SESSION['clp_id']=='admin') $query_liste_act = "SELECT * FROM ugl order by code_ugl";

else $query_liste_act = "SELECT * FROM ugl where code_ugl='".$_SESSION["clp_structure"]."' order by code_ugl";
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



/*
$query_liste_do = "SELECT distinct fonction FROM personnel where fonction!='Administrateur' ";
   try{
    $liste_do = $pdar_connexion->prepare($query_liste_do);
    $liste_do->execute();
    $row_liste_do = $liste_do ->fetchAll();
    $totalRows_liste_do = $liste_do->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }*/


/*
$query_liste_responsable = "SELECT * FROM ugl order by abrege_ugl asc";
   try{
    $liste_responsable = $pdar_connexion->prepare($query_liste_responsable);
    $liste_responsable->execute();
    $row_liste_responsable = $liste_responsable ->fetchAll();
    $totalRows_liste_responsable = $liste_responsable->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }*/



//liste village
/*
$query_liste_village = "SELECT code_village,nom_village FROM village  order by code_village asc";
   try{
    $liste_village = $pdar_connexion->prepare($query_liste_village);
    $liste_village->execute();
    $row_liste_village = $liste_village ->fetchAll();
    $totalRows_liste_village = $liste_village->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }*/
 if($_SESSION['clp_id']=='admin') $query_liste_cercle = "SELECT code_departement,nom_departement FROM departement   order by code_departement asc";
else 
{
 $query_liste_region = "SELECT * FROM ugl where code_ugl='".$_SESSION["clp_structure"]."'";
   try{
    $liste_region = $pdar_connexion->prepare($query_liste_region);
    $liste_region->execute();
    $row_liste_region = $liste_region ->fetch();
    $totalRows_liste_region = $liste_region->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
 $cercle_projet = str_replace("|",",",$row_liste_region["region_concerne"]);//implode(",",(explode("|", $_SESSION["clp_projet_ugl"]));
//liste village
$query_liste_cercle = "SELECT code_departement,nom_departement FROM departement where FIND_IN_SET(code_departement, '".$cercle_projet."' )  order by code_departement asc";
}
   try{
    $liste_cercle = $pdar_connexion->prepare($query_liste_cercle);
    $liste_cercle->execute();
    $row_liste_cercle = $liste_cercle ->fetchAll();
    $totalRows_liste_cercle = $liste_cercle->rowCount();
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
<style type="text/css"> 
#madiv{height:400px;overflow:auto} 
</style> 

<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<4) { ?>



<?php if(!isset($_GET['rapport'])) { ?>





<div class="widget box">



<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php if(isset($_GET['id'])) echo "Modifier ONG <u>".$row_liste_activite['nom_ong']."</u>"; else echo "Ajouter une ONG"; ?></h4></div>



<div  class="widget-content scroller">



<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">

<div id="madiv">

  <table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:10px;">


   <tr>
      <td valign="top"><div class="form-group">
 <label for="code_ugl" class="col-md-12 control-label">Unit&eacute;s de gestion<span class="required">*</span></label>
 <div class="col-md-12">
   <select name="code_ugl" id="code_ugl" class="full-width-fix select2-select-00 required" data-placeholder="Escolha a code">
     <option value="">Choisissez</option>
         <?php if($totalRows_liste_act>0){ foreach($row_liste_act as $row_liste_act){  ?>
     <option value="<?php echo $row_liste_act['code_ugl']; ?>" <?php if (isset($row_liste_activite["code_ugl"]) && $row_liste_act['code_ugl']==$row_liste_activite["code_ugl"]) {echo "SELECTED";} ?>><?php echo $row_liste_act['code_ugl'].": ".$row_liste_act['nom_ugl']; ?></option>
     <?php  }  } ?>
   </select>
 </div>
      </div></td>
      <td valign="top"><div class="form-group">  <label for="structure_organisatrice" class="col-md-12 control-label">Nom de l'ONG   <span class="required">*</span></label>
       <div class="col-md-12">
         <textarea name="nom_ong" rows="1" class="form-control required" id="nom_ong"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['nom_ong'];?></textarea>
         </div>
      </div></td>
   </tr>

   <tr>
     <td valign="top"><div class="form-group">  <label for="sigle_ong" class="col-md-12 control-label">Sigle de l'ONG</label>
       <div class="col-md-12">
         <textarea name="sigle_ong" rows="1" class="form-control" id="sigle_ong"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['sigle_ong'];?></textarea>
         </div>
      </div></td>
     <td valign="top"><div class="form-group">
      <label for="date_creation" class="col-md-12 control-label">Date de cr&eacute;ation </label>
          <div class="col-md-12">
            <input type="text" class="form-control datepicker" name="date_creation" id="date_creation" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_liste_activite['date_creation']))))); else echo date("d/m/Y"); ?>" />
          </div> 
      </div></td>
   </tr>
   <tr>
     <td valign="top"><div class="form-group">
         <label for="adresse_ong" class="col-md-12 control-label">Adresse ONG<span class="required">*</span></label>
         <div class="col-md-12">
           <textarea name="adresse_ong" rows="1" class="form-control required" id="adresse_ong"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['adresse_ong'];?>
   </textarea>
         </div>
     </div></td>
     <td valign="top"><div class="form-group">
         <label for="contact_ong" class="col-md-12 control-label">Contact de l'ONG <span class="required">*</span></label>
         <div class="col-md-12">
           <textarea name="contact_ong" rows="1" class="form-control required" id="contact_ong"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['contact_ong'];?>
   </textarea>
         </div>
     </div></td>
   </tr>
   <tr>
     <td colspan="2" valign="top"><div class="form-group">  <label for="cercle_couvert" class="col-md-12 control-label">Cercles couverts <span class="required">*</span></label>
       <div class="col-md-12"> 
        <select name="cercle_couvert[]" id="cercle_couvert" class="full-width-fix select2-select-00 required" data-placeholder="cercle_couvert" multiple>
           <option value="">Choisissez un cercle</option>
           <?php if($totalRows_liste_cercle>0){ $expl = (isset($row_liste_activite["cercle_couvert"]) && !empty($row_liste_activite["cercle_couvert"]))?explode(',',$row_liste_activite["cercle_couvert"]):array(); foreach($row_liste_cercle as $row_liste_cercle){  ?>
           <option value="<?php echo $row_liste_cercle['code_departement']; ?>" <?php if(in_array($row_liste_cercle['code_departement'],$expl)) {echo "SELECTED";} ?>><?php echo $row_liste_cercle['nom_departement']; ?></option>
           <?php  }  } ?> 
         </select>
       </div>
      </div></td>
     </tr>
   <tr style="background-color:#CCCCCC">
     <td colspan="2" valign="top">&nbsp;</td>
     </tr>
   <tr>
     <td valign="top"><div class="form-group">
         <label for="nom_responsable" class="col-md-12 control-label">Nom du responsable <span class="required">*</span></label>
         <div class="col-md-12">
           <textarea name="nom_responsable" rows="1" class="form-control required" id="nom_responsable"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['nom_responsable'];?></textarea>
         </div>
     </div></td>
     <td valign="top"><div class="form-group">  <label for="sexe" class="col-md-12 control-label">Sexe <span class="required">*</span></label>
       <div class="col-md-12">
         <select name="sexe" id="sexe" class="full-width-fix select2-select-00 required" data-placeholder="Theme">
           <option value="M">M</option>
            <option value="F">F</option>
         </select>
       </div>
      </div></td>
     </tr>
   <tr>
     <td valign="top"><div class="form-group">  <label for="email" class="col-md-12 control-label">Email</label>
       <div class="col-md-12">
        <textarea name="email" rows="1" class="form-control" id="email"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['email'];?></textarea>
         </div>
      </div></td>
     <td valign="top"><div class="form-group">  <label for="telephone" class="col-md-12 control-label">T&eacute;l&eacute;phone</label>
       <div class="col-md-12">
       <textarea name="telephone" rows="1" class="form-control" id="telephone"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['telephone'];?></textarea>
         </div>
      </div></td>
   </tr>
   <tr style="background-color:#CCCCCC">
     <td colspan="2" valign="top">&nbsp;</td>
     </tr>
   <!-- <tr style="background-color:#CCCCCC">
     <td colspan="4" valign="top"><div align="center"><strong>B&eacute;n&eacute;ficiaires  par cat&eacute;gorie </strong></div></td>
     </tr>
   <tr>
     <td valign="middle"><div class="form-group">
         <label for="nbre_beneficiaire" class="col-md-12 control-label">
         <div align="right">Personnel de l'<strong>UNAC</strong> </div>
       </label>
         <div class="col-md-12"></div>
     </div></td>
     <td valign="top"><div class="form-group">
         <label for="h_unac" class="col-md-12 control-label">Hommes </label>
         <div class="col-md-12">
           <textarea name="h_unac" rows="1" class="form-control" id="h_unac"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['h_unac'];?></textarea>
         </div>
     </div></td>
     <td valign="top"><div class="form-group">
         <label for="f_unac" class="col-md-12 control-label">Femmes </label>
         <div class="col-md-12">
           <textarea name="f_unac" rows="1" class="form-control" id="f_unac"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['f_unac'];?></textarea>
         </div>
     </div></td>
     <td valign="top"><div class="form-group">
         <label for="j_unac" class="col-md-12 control-label">Jeunes </label>
         <div class="col-md-12">
           <textarea name="j_unac" rows="1" class="form-control" id="j_unac"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['j_unac'];?></textarea>
         </div>
     </div></td>
   </tr>
   <tr>
     <td valign="middle" bgcolor="#F0F0F0"><div class="form-group">  <label for="nbre_beneficiaire" class="col-md-12 control-label">
     <div align="right">Personnel des <strong>URAC</strong> </div>
     </label>
     <div class="col-md-12"></div>
      </div></td>
     <td valign="top" bgcolor="#F0F0F0"><div class="form-group">  <label for="h_urac" class="col-md-12 control-label">Hommes   </label>
       <div class="col-md-12">
         <textarea name="h_urac" rows="1" class="form-control" id="h_urac"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['h_urac'];?></textarea>
         </div>
      </div></td>
     <td valign="top" bgcolor="#F0F0F0"><div class="form-group">  <label for="f_urac" class="col-md-12 control-label">Femmes   </label>
       <div class="col-md-12">
         <textarea name="f_urac" rows="1" class="form-control" id="f_urac"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['f_urac'];?></textarea>
         </div>
      </div></td>
     <td valign="top" bgcolor="#F0F0F0"><div class="form-group">  <label for="j_urac" class="col-md-12 control-label">Jeunes   </label>
       <div class="col-md-12">
         <textarea name="j_urac" rows="1" class="form-control" id="j_urac"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['j_urac'];?></textarea>
         </div>
      </div></td>
   </tr>
   <tr>
     <td valign="middle"><div class="form-group">
         <label for="nbre_beneficiaire" class="col-md-12 control-label">
         <div align="right">Membres <strong>CRV</strong> </div>
         </label>
         <div class="col-md-12"></div>
     </div></td>
     <td valign="top"><div class="form-group">
         <label for="h_crv" class="col-md-12 control-label">Hommes </label>
         <div class="col-md-12">
           <textarea name="h_crv" rows="1" class="form-control" id="h_crv"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['h_crv'];?></textarea>
         </div>
     </div></td>
     <td valign="top"><div class="form-group">
         <label for="f_crv" class="col-md-12 control-label">Femmes </label>
         <div class="col-md-12">
           <textarea name="f_crv" rows="1" class="form-control" id="f_crv"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['f_crv'];?></textarea>
         </div>
     </div></td>
     <td valign="top"><div class="form-group">
         <label for="j_crv" class="col-md-12 control-label">Jeunes </label>
         <div class="col-md-12">
           <textarea name="j_crv" rows="1" class="form-control" id="j_crv"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['j_crv'];?></textarea>
         </div>
     </div></td>
   </tr>
   <tr>
     <td valign="middle" bgcolor="#F0F0F0"><div class="form-group">
         <label for="nbre_beneficiaire" class="col-md-12 control-label">
         <div align="right">Personnel  <strong>SIE</strong> </div>
         </label>
         <div class="col-md-12"></div>
     </div></td>
     <td valign="top" bgcolor="#F0F0F0"><div class="form-group">
         <label for="h_sie" class="col-md-12 control-label">Hommes </label>
         <div class="col-md-12">
           <textarea name="h_sie" rows="1" class="form-control" id="h_sie"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['h_sie'];?></textarea>
         </div>
     </div></td>
     <td valign="top" bgcolor="#F0F0F0"><div class="form-group">
         <label for="f_sie" class="col-md-12 control-label">Femmes </label>
         <div class="col-md-12">
           <textarea name="f_sie" rows="1" class="form-control" id="f_sie"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['f_sie'];?></textarea>
         </div>
     </div></td>
     <td valign="top" bgcolor="#F0F0F0"><div class="form-group">
         <label for="j_sie" class="col-md-12 control-label">Jeunes </label>
         <div class="col-md-12">
           <textarea name="j_sie" rows="1" class="form-control" id="j_sie"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['j_sie'];?></textarea>
         </div>
     </div></td>
   </tr>
   <tr>
     <td valign="middle"><div class="form-group">
         <label for="nbre_beneficiaire" class="col-md-12 control-label">
         <div align="right">Jeunes entreprenuers</div>
         </label>
         <div class="col-md-12"></div>
     </div></td>
     <td valign="top"><div class="form-group">
         <label for="h_je" class="col-md-12 control-label">Hommes </label>
         <div class="col-md-12">
           <textarea name="h_je" rows="1" class="form-control" id="h_je"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['h_je'];?></textarea>
         </div>
     </div></td>
     <td valign="top"><div class="form-group">
         <label for="f_je" class="col-md-12 control-label">Femmes </label>
         <div class="col-md-12">
           <textarea name="f_je" rows="1" class="form-control" id="f_je"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['f_je'];?></textarea>
         </div>
     </div></td>
     <td valign="top">&nbsp;</td>
   </tr>
   <tr>
     <td valign="middle" bgcolor="#F0F0F0"><div class="form-group">
         <label for="nbre_beneficiaire" class="col-md-12 control-label">
         <div align="right">Personnel des <strong>EMF</strong> </div>
         </label>
         <div class="col-md-12"></div>
     </div></td>
     <td valign="top" bgcolor="#F0F0F0"><div class="form-group">
         <label for="h_emf" class="col-md-12 control-label">Hommes </label>
         <div class="col-md-12">
           <textarea name="h_emf" rows="1" class="form-control" id="h_emf"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['h_emf'];?></textarea>
         </div>
     </div></td>
     <td valign="top" bgcolor="#F0F0F0"><div class="form-group">
         <label for="f_emf" class="col-md-12 control-label">Femmes </label>
         <div class="col-md-12">
           <textarea name="f_emf" rows="1" class="form-control" id="f_emf"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['f_emf'];?></textarea>
         </div>
     </div></td>
     <td valign="top" bgcolor="#F0F0F0"><div class="form-group">
         <label for="j_emf" class="col-md-12 control-label">Jeunes </label>
         <div class="col-md-12">
           <textarea name="j_emf" rows="1" class="form-control" id="j_emf"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['j_emf'];?></textarea>
         </div>
     </div></td>
   </tr>
   <tr>
     <td valign="middle"><div class="form-group">
         <label for="nbre_beneficiaire" class="col-md-12 control-label">
         <div align="right">Autres</div>
         </label>
         <div class="col-md-12"></div>
     </div></td>
     <td valign="top"><div class="form-group">
         <label for="h_autres" class="col-md-12 control-label">Hommes </label>
         <div class="col-md-12">
           <textarea name="h_autres" rows="1" class="form-control" id="h_autres"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['h_autres'];?></textarea>
         </div>
     </div></td>
     <td valign="top"><div class="form-group">
         <label for="f_autres" class="col-md-12 control-label">Femmes </label>
         <div class="col-md-12">
           <textarea name="f_autres" rows="1" class="form-control" id="f_autres"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['f_autres'];?></textarea>
         </div>
     </div></td>
     <td valign="top"><div class="form-group">
         <label for="j_autres" class="col-md-12 control-label">Jeunes </label>
         <div class="col-md-12">
           <textarea name="j_autres" rows="1" class="form-control" id="j_autres"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['j_autres'];?></textarea>
         </div>
     </div></td>
   </tr>
   <tr style="background-color:#CCCCCC">
     <td colspan="3" valign="top">&nbsp;</td>
   </tr>-->
   <tr>
     <td colspan="2" valign="top"><div class="form-group">  <label for="observations" class="col-md-12 control-label">Observations/commentaires  </label>
       <div class="col-md-12">
         <textarea name="observations" rows="1" class="form-control" id="observations"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['observation'];?></textarea>
         </div>
      </div></td>
     </tr>
<tr><td></tr>
 <tr>
      <td valign="top"><div class="form-group">  <label for="lieu_elaboration" class="col-md-12 control-label">Lieu de collecte  </label>
          <div class="col-md-12">
            <textarea name="lieu_elaboration" rows="1" class="form-control" id="lieu_elaboration"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['lieu_elaboration'];?></textarea>
          </div>
      </div></td>
      <td valign="top"><div class="form-group">
      <label for="date_collecte" class="col-md-12 control-label">Date de collecte des donn&eacute;es </label>
          <div class="col-md-12">
            <input type="text" class="form-control datepicker" name="date_collecte" id="date_collecte" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',date("d/m/Y",strtotime($row_liste_activite['date_collecte']))))); else echo date("d/m/Y"); ?>" />
          </div> 
      </div></td>
    </tr>
 <tr>
   <td valign="top"><div class="form-group">  <label for="nom_collecteur" class="col-md-12 control-label">Nom du collecteur  </label>
          <div class="col-md-12">
            <textarea name="nom_collecteur" rows="1" class="form-control" id="nom_collecteur"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['nom_collecteur'];?></textarea>
          </div>
      </div></td>
   <td valign="top"><div class="form-group">  <label for="fonction" class="col-md-12 control-label">Fonction du collecteur  </label>
          <div class="col-md-12">
            <textarea name="fonction" rows="1" class="form-control" id="fonction"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_activite['fonction'];?></textarea>
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


<?php } } ?>