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



//if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y"); if(isset($_GET['cp'])) {$cp=$_GET['cp'];} else $cp=0;


if(isset($_GET['code_modele'])) { $code_modele = $_GET['code_modele']; }

if(isset($_GET['id_modele'])) { $modele = $_GET['id_modele'];}



function frenchMonthName($monthnum) {

      $armois=array("", "Jan", "Fév", "Mars", "Avril", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc");

      if ($monthnum>0 && $monthnum<13) {

          return $armois[$monthnum];

      } else {

          return $monthnum;

      }

  }



$editFormAction = $_SERVER['PHP_SELF'];

/*if (isset($_SERVER['QUERY_STRING'])) {

  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);

}*/

$page = $_SERVER['PHP_SELF'];



$annee = (isset($_GET["annee"]))?intval($_GET["annee"]):date("Y");



$lien = $lien1 = $_SERVER['PHP_SELF'];

$lien .= "?id_modele=$modele&code_modele=$code_modele";

$lien1 .= "?id_modele=$modele&code_modele=$code_modele";


if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
$query_sup_etape = sprintf("DELETE from ".$database_connect_prefix."etape_marche WHERE id_etape=%s",
                         GetSQLValueString($id, "text"));
      	    try{
    $Result1 = $pdar_connexion->prepare($query_sup_etape);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
 }

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))

{   $modele=$_POST['modele'];

  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

  $duree=$_POST['duree'];
  $intitule=$_POST['intitule'];
  $code=$_POST['code'];
  $id_etape=$_POST['id_etape'];
  $categorie=$_POST['categorie'];

  //suppression
  $query_sup_cible_indicateur = "DELETE FROM ".$database_connect_prefix."etape_marche WHERE modele_concerne=$modele";
    	    try{
    $Result1 = $pdar_connexion->prepare($query_sup_cible_indicateur);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  foreach ($id_etape as $key => $value)

  {
	  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."etape_marche (groupe, modele_concerne, code, intitule, duree, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, '$personnel', '$date')",

						GetSQLValueString($categorie[$key], "int"),
						GetSQLValueString($modele, "int"),
						GetSQLValueString($code[$key], "int"),
						GetSQLValueString($intitule[$key], "text"),
                        GetSQLValueString($duree[$key], "int"));

//echo $categorie[$key];
//exit;
	  	    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

    }

}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))

{

    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];



  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."etape_marche (modele_concerne, groupe, code, intitule, duree, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, '$personnel', '$date')",

						GetSQLValueString($modele, "int"),
						GetSQLValueString($_POST['categorie'], "int"),
						GetSQLValueString($_POST['code'], "int"),
						GetSQLValueString($_POST['intitule'], "text"),
                      //  GetSQLValueString($_POST['description'], "text"),
                        GetSQLValueString($_POST['duree'], "int"));
  	    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
  $insertGoTo = $_SERVER['PHP_SELF'];
  $insertGoTo .= "?id_modele=$modele&code_modele=$code_modele";
  if ($Result1) $insertGoTo .= "&insert=ok";
  else $insertGoTo .= "&insert=no";
  header(sprintf("Location: %s", $insertGoTo));
}
}

$query_liste_zone = "SELECT * FROM ".$database_connect_prefix."etape_marche where 	modele_concerne=$modele ORDER BY code asc";
       try{
    $liste_zone = $pdar_connexion->prepare($query_liste_zone);
    $liste_zone->execute();
    $row_liste_zone = $liste_zone ->fetchAll();
    $totalRows_liste_zone = $liste_zone->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


$query_edit_modele = "SELECT * FROM ".$database_connect_prefix."modele_marche where id_modele='$modele' ORDER BY code asc";
       try{
    $edit_modele = $pdar_connexion->prepare($query_edit_modele);
    $edit_modele->execute();
    $row_edit_modele = $edit_modele ->fetch();
    $totalRows_edit_modele = $edit_modele->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$libelle_modele=$row_edit_modele['code']."- ".$row_edit_modele['categorie']." (".$row_edit_modele['methode_concerne'].")";
$cat_ge=$row_edit_modele['categorie'];


$query_liste_categorie = "SELECT * FROM ".$database_connect_prefix."groupe_etape where categorie_groupe='$cat_ge' ORDER BY code_groupe asc";
       try{
    $liste_categorie = $pdar_connexion->prepare($query_liste_categorie);
    $liste_categorie->execute();
    $row_liste_categorie = $liste_categorie ->fetchAll();
    $totalRows_liste_categorie = $liste_categorie->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

<!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->

<link rel="stylesheet" type="text/css" href="<?php print $config->theme_folder;?>/plugins/jquery-ui.css"/>

<link href="<?php print $config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>

<link href="<?php print $config->theme_folder;?>/responsive.css" rel="stylesheet" type="text/css"/>

<link href="<?php print $config->theme_folder;?>/icons.css" rel="stylesheet" type="text/css"/>

<link href='<?php print $config->theme_folder;?>/css.css' rel='stylesheet' type='text/css'>

<link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">

<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>

<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>

<script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>

<script type="text/javascript" src="plugins/noty/layouts/top.js"></script>

<script type="text/javascript" src="plugins/noty/themes/default.js"></script>

<script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>

<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>

<script type="text/javascript" src="plugins/pickadate/picker.js"></script>

<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>

<?php if(!isset($_GET['add'])) { ?>

<script type="text/javascript" src="plugins/select2/select2.min.js"></script>

<script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>

<script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>

<?php } ?>

<script>

	$().ready(function() {

	  $(".modal-dialog", window.parent.document).width(800);

		// validate the comment form when it is submitted

		$("#form1").validate();

<?php if(!isset($_GET['add'])) { ?>

$(".dataTable").dataTable({"iDisplayLength": -1});



<?php } ?>

<?php if(isset($_GET['add'])) { ?>

        $("#ui-datepicker-div").remove();

        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});

<?php } ?>

	});

function check_proportion(){

  var p = <?php echo (isset($proportion) && !empty($proportion))?$proportion:0;  ?>;

  if(document.form1.proportion.value><?php echo (isset($proportion) && !empty($proportion))?$proportion:0;  ?>){ document.form1.proportion.value=<?php echo (isset($proportion) && !empty($proportion))?$proportion:0;  ?>; }

}

</script>

<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {

  border-spacing: 0px !important; border-collapse: collapse; font-size: small;

} .table tbody tr td {vertical-align: middle; }

#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}

.dataTables_length, .dataTables_info { float: left;} .dataTables_paginate, .dataTables_filter { float: right;}

.dataTables_length, .dataTables_paginate { display: none;}



</style>

</head>

<body>
<?php if(!isset($_GET['add'])) { ?>
<div>

<div class="widget box ">

 <div class="widget-header"> <h4><i class="icon-reorder"></i>  <strong>Mod&egrave;le de passation: </strong><span class="Style18">

 </span><?php echo $libelle_modele."  ".$modele; ?> </h4>

  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php echo do_link("",$lien1."&add=1","Ajout une &eacute;tape dans le modele","<i class=\"icon-plus\"> Nouvelle &eacute;tape </i>","simple","./","pull-right p11","",0,"","categorie_marche.php");  ?>
<!--<a href="<?php echo $lien1."&add=1"; ?>" class="pull-right p11" title="Ajout une suivi de t&acirc;ches" ><i class="icon-plus"> Nouvelle &eacute;tape </i></a>-->

<?php } ?>

 </div>

<div class="widget-content">
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1) && !check_user_auth('page_edit',"categorie_marche.php")) { ?>
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">
<?php } ?>

<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >

    <?php $t=0;  if($totalRows_liste_zone>0) { ?>

    <tr class="titrecorps2">

  <td>Ordre</td>


  <td width="40%"><div align="center" class="Style31"><strong>Libell&eacute;</strong></div></td>
    <td><strong>Dur&eacute;e (J) </strong></td>
    <td width="30%">Etape r&eacute;f&eacute;rentielle </td>
    <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
    <td>Supr.</td>
    <?php } ?>
    </tr>

    <?php $p1="j"; $t=0; $i=0;foreach($row_liste_zone as $row_liste_zone){   $i++; ?>

    <tr <?php //if($i%2==0) echo 'bgcolor="#D2E2B1"';  $i=$i+1; $t=$t+1;?>>

<td><span class="">
        <input name='code[]' class="form-control" type="text" size="1"  value="<?php echo $i;//$row_liste_zone['code']; ?>"/>
      </span></td>


<td><div align="center" class="Style31">

<textarea name="intitule[]" cols="5" class="form-control"><?php echo $row_liste_zone['intitule']; ?></textarea>
<input name="id_etape[]" type="hidden" size="5" value="<?php echo $row_liste_zone['id_etape']; ?>"/>

 </div></td>


    <td><span class="Style31">
      <input name='duree[]' class="form-control" type="text" size="1"  value="<?php echo $row_liste_zone['duree']; ?>"/>
    </span></td>
    <td><select name="categorie[]"  class="form-control required" >
              <option value="0">Selectionnez</option>
              <?php if($totalRows_liste_categorie>0) { foreach($row_liste_categorie as $row_liste_categorie1){   ?>
              <option value="<?php echo $row_liste_categorie1['id_groupe']; ?>" <?php if (isset($row_liste_zone['groupe']) && $row_liste_categorie1['id_groupe']==$row_liste_zone['groupe']) {echo "SELECTED";} ?>><?php echo $row_liste_categorie1['code_groupe'].": ".$row_liste_categorie1['libelle_groupe']; ?></option>
                         <?php } } ?>
            </select></td>
    <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>

       <td align="center">
<?php echo do_link("",$lien."&id_sup=".$row_liste_zone['id_etape'],"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette etape ?');",0,"margin:0px 5px;","categorie_marche.php"); ?>
<!--<a onClick="return confirm('Voulez vous vraiment suppimer cette etape ?');" href="<?php echo $lien."&id_sup=".$row_liste_zone['id_etape'].""; ?>" title="Supprimer l'étape" ><img align="center" src='./images/delete.png' width='20' height='20' alt='Supprimer' style="margin:0px 5px 0px 0px;"></a>--></td>

      <?php } ?>
    </tr>

    <?php }  ?>

    <?php } else echo "<h3>Aucune &eacute;tape disponible</h3>" ;?>
  </table>
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1) && !check_user_auth('page_edit',"categorie_marche.php")) { ?>
<div class="form-actions">

<?php if(isset($_GET["id_modele"])){ ?>
<input type="hidden" name="modele" value="<?php echo $modele; ?>" />

  <?php } ?>

  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />

  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">



<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">

  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->

</div>

</form>
<?php } ?>
</div>

</div>

</div>

<?php } elseif(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)){ ?>

<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification d'étape":"Nouvelle étape"; ?></h4>

<a href="<?php echo $lien1; ?>" class="pull-right p11" title="Annuler" >Annuler </a>

</div>

<div class="widget-content">

<form action="<?php echo $lien1; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form1" id="form1" novalidate="novalidate">

<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

     <tr valign="top">

      <td>

      <div class="form-group">

          <label for="code" class="col-md-12 control-label">N&deg; ordre <span class="required">*</span></label>

          <div class="col-md-12">

            <input class="form-control required" type="text" name="code" id="code" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_etape['code']; ?>" size="10" style="width: 90px;" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_edit_etape['code']."'"; ?>) check_code('verif_code.php?t=etape_marche&','w=code='+this.value+' and 	modele_concerne=<?php echo $modele; ?>','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />

            <span class="help-block h0" id="code_zone_text">&nbsp;</span>          </div>
        </div>      </td>

      <td>&nbsp;</td>
    </tr>

    <tr valign="top">

      <td width="50%">

      <div class="form-group">

          <label for="intitule" class="col-md-12 control-label">Intitul&eacute; <span class="required">*</span></label>

          <div class="col-md-12">

 <textarea class="form-control " id="intitule" name="intitule" cols="25" rows="1"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_etape['intitule'];?></textarea>
          </div>
        </div>      </td>

      <td>

        <div class="form-group">
          <label for="duree" class="col-md-12 control-label">Dur&eacute;e max (jours) </label>
          <div class="col-md-9">
         <input name="duree" type="text" class="form-control " id="duree" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_etape['duree'];?>" size="25" />
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td colspan="2"> <div class="form-group">

          <label for="categorie" class="col-md-12 control-label">Etape r&eacute;f&eacute;rentielle <span class="required">*</span></label>
 <div class="col-md-9">
          <select name="categorie" id="categorie" class="form-control required" >
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_categorie>0) { foreach($row_liste_categorie as $row_liste_categorie){   ?>
              <option value="<?php echo $row_liste_categorie['id_groupe']; ?>" <?php if (isset($row_edit_modele['groupe']) && $row_liste_categorie['id_groupe']==$row_edit_modele['groupe']) {echo "SELECTED";} ?>><?php echo $row_liste_categorie['code_groupe'].": ".$row_liste_categorie['libelle_groupe']; ?></option>
              <?php } } ?>
            </select> </div>
        </div> </td>
      </tr>
<tr valign="top">

      <td colspan="2">

        <div class="form-group">

          <label for="description" class="col-md-12 control-label">Description <span class="required">*</span></label>

          <div class="col-md-12">

            <textarea class="form-control required" cols="200" rows="2" type="text" name="description" id="description"><?php if(isset($_GET['id'])) echo $row_edit_etape['description']; ?></textarea>
          </div>
        </div>      </td>
    </tr>
</table>

<div class="form-actions">

<?php if(isset($_GET["id"])){ ?>

  <input type="hidden" name="id" value="<?php echo ($_GET["id"]); ?>" />

<?php } ?>                                             

  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />

<a href="<?php echo $lien1; ?>" class="btn pull-right" title="Annuler" >Annuler</a>

  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">

<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2)) { ?>

<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">

<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette &eacute;tape ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />

<?php } ?>

<input name="MM_form" id="MM_form" type="hidden" value="form1" size="32" alt="">

  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->

</div>

</form>

</div> </div>

<?php } ?>


<?php include_once 'modal_add.php'; ?>

</body>

</html>