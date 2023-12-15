<?php

///////////////////////////////////////////////

/*                 SSE                       */

/*	Conception & Développement: BAMASOFT */

///////////////////////////////////////////////

session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  exit;
}

include_once $config->sys_folder . "/database/db_connexion.php";

////header('Content-Type: text/html; charset=UTF-8');



if(isset($_GET['id_ind'])) $id_ind = $_GET['id_ind']; else $id_ind=0;// || $_GET['ad_sta'];

if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else $annee=date("Y");

if(isset($_GET['id_act'])) { $id_act = $_GET['id_act']; }

if(isset($_GET['cmp'])) { $cmp = $_GET['cmp']; }

$page1="";

//if(isset($_GET['ug'])) $ug = $_GET["ug"];



$editFormAction = $_SERVER['PHP_SELF'];
/*
if (isset($_SERVER['QUERY_STRING'])) {

  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);

}*/



$page = $_SERVER['PHP_SELF'];



$array_indic = array("OUI/NON","texte");



$query_liste_unite = "SELECT * FROM ".$database_connect_prefix."indicateur_tache WHERE id_indicateur_tache=".GetSQLValueString($id_ind, "int");
  try{
    $liste_unite = $pdar_connexion->prepare($query_liste_unite);
    $liste_unite->execute();
    $row_liste_unite = $liste_unite ->fetch();
    $totalRows_liste_unite = $liste_unite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$unite = (isset($row_liste_unite["unite"]))?strtoupper($row_liste_unite["unite"]):"";   
//suivi indicateur



//insertion suivi indicateur

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
$query_liste_unite = "SELECT unite FROM ".$database_connect_prefix."indicateur_tache WHERE id_indicateur_tache=".GetSQLValueString($_POST['id_ind'], "text");
  try{
    $liste_unite = $pdar_connexion->prepare($query_liste_unite);
    $liste_unite->execute();
    $row_liste_unite = $liste_unite ->fetch();
    $totalRows_liste_unite = $liste_unite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$unite = (isset($row_liste_unite["unite"]))?strtoupper($row_liste_unite["unite"]):"";                                       

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $key = date("ymdis").$_SESSION['clp_n'];
	   if(isset($unite) && $unite=="OUI/NON") {if(strtoupper($_POST['valeur_suivi'])=="OUI") {$_POST['valeur_suivi']=1; } else $_POST['valeur_suivi']=0;} 
			  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."suivi_indicateur_tache (id_suivi, ugl, indicateur, date_suivi, valeur_suivi, commune, personnel, date_enregistrement) VALUES ('$key', %s, %s, %s, %s, %s, '$personnel', '$date')",

						   GetSQLValueString($cmp, "text"),
						   GetSQLValueString($_POST['id_ind'], "text"),
                           GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_suivi']))), "date"),
						   GetSQLValueString($_POST['valeur_suivi'], "double"),
						   GetSQLValueString($_POST['commune'], "text"));
    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $_SERVER['PHP_SELF']."?id_act=$id_act&cmp=$cmp&annee=$annee&id_ind=$id_ind";
  if ($Result1) $insertGoTo .= "&insert=ok"; else $insertGoTo .= "&insert=no";
  $insertGoTo .= "&mod=1";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);

}





//update suivi indicateur

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
$query_liste_unite = "SELECT unite FROM ".$database_connect_prefix."indicateur_tache WHERE id_indicateur_tache=".GetSQLValueString($_POST['id_ind'], "text");
  try{
    $liste_unite = $pdar_connexion->prepare($query_liste_unite);
    $liste_unite->execute();
    $row_liste_unite = $liste_unite ->fetch();
    $totalRows_liste_unite = $liste_unite->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$unite = (isset($row_liste_unite["unite"]))?strtoupper($row_liste_unite["unite"]):"";
	   if(isset($unite) && $unite=="OUI/NON") {if(strtoupper($_POST['valeur_suivi'])=="OUI") {$_POST['valeur_suivi']=1; } else $_POST['valeur_suivi']=0;} 

				     $insertSQL = sprintf("UPDATE ".$database_connect_prefix."suivi_indicateur_tache SET indicateur=%s, date_suivi=%s, valeur_suivi=%s, commune=%s WHERE id_suivi=%s",
   					   GetSQLValueString($_POST['id_ind'], "text"),
                       GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_suivi']))), "date"),
					   GetSQLValueString($_POST['valeur_suivi'], "double"),
					   GetSQLValueString($_POST['commune'], "text"),
                       GetSQLValueString($_POST['id_suivi'], "text"));
      try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $_SERVER['PHP_SELF']."?id_act=$id_act&cmp=$cmp&annee=$annee&id_ind=$id_ind";
  $insertGoTo = $_SERVER['PHP_SELF']."?id_act=$id_act&cmp=$cmp&annee=$annee&id_ind=$id_ind";
  if ($Result1) $insertGoTo .= "&update=ok"; else $insertGoTo .= "&update=no";
  $insertGoTo .= "&mod=1";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
}

$query_act = "SELECT * FROM ".$database_connect_prefix."ptba where id_ptba='$id_act' and projet='".$_SESSION["clp_projet"]."' and annee='$annee'";
  try{
    $act = $pdar_connexion->prepare($query_act);
    $act->execute();
    $row_act = $act ->fetch();
    $totalRows_act = $act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$ug = isset($row_act["region"])?$row_act["region"]:0;



if(isset($_GET["id_sup_ind"]))

{

  $id=$_GET["id_sup_ind"];

  $query_sup_ind = "DELETE FROM ".$database_connect_prefix."suivi_indicateur_tache WHERE id_suivi='$id'";
    try{
    $Result1 = $pdar_connexion->prepare($query_sup_ind);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $_SERVER['PHP_SELF']."?id_act=$id_act&cmp=$cmp&annee=$annee&id_ind=$id_ind";

  if ($Result1) $insertGoTo .= "&del=ok"; else $insertGoTo .= "&del=no";

  $insertGoTo .= "&mod=1";

  header(sprintf("Location: %s", $insertGoTo));

}



if(isset($_GET["iden"]))

{

  $id=$_GET["iden"];

  $query_edit_ind1 = "SELECT * FROM ".$database_connect_prefix."suivi_indicateur_tache WHERE id_suivi='$id'";
    try{
    $edit_ind1 = $pdar_connexion->prepare($query_edit_ind1);
    $edit_ind1->execute();
    $row_edit_ind1 = $edit_ind1 ->fetch();
    $totalRows_edit_ind1 = $edit_ind1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


  $indic = $row_edit_ind1["indicateur"];
  $query_edit_ind = "SELECT unite FROM ".$database_connect_prefix."indicateur_tache where id_indicateur_tache='$indic'"; 
    try{
    $edit_ind = $pdar_connexion->prepare($query_edit_ind);
    $edit_ind->execute();
    $row_edit_ind = $edit_ind ->fetch();
    $totalRows_edit_ind = $edit_ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $unite = (isset($row_edit_ind["unite"]))?strtoupper($row_edit_ind["unite"]):"";
}

else

{

  $query_edit_ind = "SELECT * FROM ".$database_connect_prefix."indicateur_tache where id_activite='$id_act' ORDER BY code_indicateur_ptba asc";
      try{
    $edit_ind = $pdar_connexion->prepare($query_edit_ind);
    $edit_ind->execute();
    $row_edit_ind = $edit_ind ->fetchAll();
    $totalRows_edit_ind = $edit_ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

}
/*
$query_liste_pde = "SELECT * FROM ".$database_connect_prefix."pde  ORDER BY nom_pde asc";
      try{
    $liste_pde = $pdar_connexion->prepare($query_liste_pde);
    $liste_pde->execute();
    $row_liste_pde = $liste_pde ->fetch();
    $totalRows_liste_pde = $liste_pde->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$pde_array = array();
 if($totalRows_liste_pde>0) { foreach($row_liste_pde as $row_liste_pde1){  
  $pde_array[$row_liste_pde1["code_pde"]] = $row_liste_pde1["nom_pde"];
} }*/

  //and id_region=$ug

$query_liste_commune = "SELECT code_commune, nom_commune FROM ".$database_connect_prefix."commune  ORDER BY nom_commune asc";
      try{
    $liste_commune = $pdar_connexion->prepare($query_liste_commune);
    $liste_commune->execute();
    $row_liste_commune = $liste_commune ->fetchAll();
    $totalRows_liste_commune = $liste_commune->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$commune_array = array();
 if($totalRows_liste_commune>0) { foreach($row_liste_commune as $row_liste_commune1){  
  $commune_array[$row_liste_commune1["code_commune"]] = $row_liste_commune1["nom_commune"];
} }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"

    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">



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

<link href="<?php print $config->theme_folder; ?>/plugins/datatables_bootstrap.css" rel="stylesheet" type="text/css"/>

<link href="<?php print $config->theme_folder; ?>/plugins/select2.css" rel="stylesheet" type="text/css"/>



<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>

<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>

<script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>

<script type="text/javascript" src="plugins/noty/layouts/top.js"></script>

<script type="text/javascript" src="plugins/noty/themes/default.js"></script>

<script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>

<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>

<script type="text/javascript" src="plugins/pickadate/picker.js"></script>

<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>

<script type="text/javascript" src="plugins/select2/select2.min.js"></script>

<script type="text/javascript">

$(document).ready(function() {

  $(".modal-dialog", window.parent.document).width(700);

  //get_content('suivi_indicateur_ptba_reload.php','<?php //echo "id=$id_act&ug=$ug&idcl=$idcl&cp=$cp"; ?>','acharger<?php //echo $id_act; ?>','','',1);

<?php if(isset($_GET['add'])) { ?>

        $("#ui-datepicker-div").remove();

        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});

        $(".modal-dialog", window.parent.document).width(600);

        $(".select2-select-00").select2({allowClear:true});

<?php } ?>

<?php if(isset($_GET['mod'])) { ?>

        // reload parent frame

        $(".close", window.parent.document).click(function(){

          //window.parent.location.reload();

get_content('suivi_indicateur_ptba_reload.php','<?php echo "id=$id_act&annee=$annee&l=3"; ?>','label1_<?php echo $id_act; ?>','','',1);

        });

        $("button[data-dismiss='modal']", window.parent.document).click(function(){

          //window.parent.location.reload();

get_content('suivi_indicateur_ptba_reload.php','<?php echo "id=$id_act&annee=$annee&l=3"; ?>','label1_<?php echo $id_act; ?>','','',1);

        });

<?php } ?>

  });

</script>

<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {

  border-spacing: 0px !important; border-collapse: collapse; font-size: small;

} .table tbody tr td {vertical-align: middle; }

#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}

.dataTables_length, .dataTables_info { float: left; font-size: 10px;}

.dataTables_length, .dataTables_paginate { display: none;}



@media(min-width:558px){.col-md-12 {width: 100%;}.col-md-9 {width: 75%;}.col-md-8 {width: 70%;}.col-md-4 {width: 30%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}

</style>

</head>

<body>

<?php if(isset($_GET['id_ind']) && isset($_GET["add"])) {

if(isset($_GET['id_ind'])) $id_ind = $_GET['id_ind']; else $id_ind=0; ?>

<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo $row_liste_unite["intitule_indicateur_tache"];?></h4>

<a href="<?php echo (isset($_GET['page']))?$_GET['page'].$page1:$_SERVER['PHP_SELF']."?id_act=$id_act&cmp=$cmp&annee=$annee&id_ind=$id_ind"; ?>" class="pull-right p11" title="Annuler" >Annuler </a>

</div>

<div class="widget-content">

<form action="<?php echo $_SERVER['PHP_SELF']."?id_act=$id_act&cmp=$cmp&annee=$annee&id_ind=$id_ind"; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form4" id="form3" novalidate="novalidate">

<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

	<tr valign="top">

      <td>
        <div class="form-group">
          <label for="commune" class="col-md-4 control-label">Commune  <span class="required">*</span></label>
          <div class="col-md-8">
            <select name="commune" id="commune" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez">       
 <option value="0000" <?php if(isset($_GET["iden"]) && $row_edit_ind1['commune']=="0000") echo 'SELECTED="selected"'; ?> >Toutes</option> 
<?php  foreach($row_liste_commune as $row_liste_commune){   ?>
    <option value="<?php echo $row_liste_commune['code_commune']?>" <?php if(isset($_GET["iden"]) && $row_liste_commune['code_commune']==$row_edit_ind1['commune']) echo 'SELECTED="selected"'; ?> ><?php echo $row_liste_commune['nom_commune']?></option>
    <?php }  ?>
            </select>
          </div>
        </div>
      </td>

    </tr>


    <tr valign="top">

      <td>

        <div class="form-group">

          <label for="date_suivi" class="col-md-4 control-label">Date de suivi <span class="required">*</span></label>

          <div class="col-md-3">

            <input class="form-control datepicker required" type="text" name="date_suivi" id="date_suivi" value="<?php if(isset($_GET["iden"])) echo implode('/',array_reverse(explode('-',$row_edit_ind1['date_suivi']))); else echo date("d/m/Y"); ?>" size="32" />

          </div>

        </div>

      </td>

    </tr>

    <tr valign="top">

      <td>

        <div class="form-group">

          <label for="valeur_suivi" class="col-md-4 control-label">R&eacute;sultat (<?php if(isset($unite)) echo $unite; ?>) <span class="required">*</span></label>

          <div class="col-md-3">

            <input class="form-control required" type="text" name="valeur_suivi" id="valeur_suivi" value="<?php if(isset($_GET["iden"]) && strtoupper($unite)!="OUI/NON") echo $row_edit_ind1['valeur_suivi']; elseif(isset($_GET["iden"]) && $row_edit_ind1['valeur_suivi']==1) echo "Oui"; else echo "";  ?>" size="32" />

          </div>

        </div>

      </td>

    </tr>


</table>

<div class="form-actions">

<?php if(isset($_GET['iden'])) { ?>

<input type="hidden" name="id_suivi" value="<?php echo $_GET["iden"];  ?>" />

<?php }  ?>

<input type="hidden" name="id_ind" value="<?php echo (isset($_GET['iden']))?$row_edit_ind1['indicateur']:$id_ind;  ?>" />

<input type="hidden" name="id_act" value="<?php echo $id_act;  ?>" />

<input type="hidden" name="ug" value="<?php echo $ug;  ?>" />

<input name="Envoyer" type="submit" class="btn btn-success pull-right" value="<?php echo "Enregistrer" ; ?>" />

<input type="hidden" name="<?php if(isset($_GET['iden'])) echo "MM_update"; else echo "MM_insert";  ?>" value="form2" />

</div>

</form>



</div> </div>

<?php } else { ?>

<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i> Suivi des indicateurs</h4> <div class="toolbar no-padding"></div> </div>

<div class="widget-content" style="display: block;">

<?php if($totalRows_edit_ind>0){ foreach($row_edit_ind as $row_edit_ind){ ?>

<table border="0" align="left" cellspacing="0" cellpadding="0" width="100%">

  <tr bgcolor="#CCCCCC" >

<td colspan="2" style="font-weight: bold;"><?php echo $row_edit_ind['intitule_indicateur_tache']; echo " (".$row_edit_ind['unite'].")";?><span class="Style18">

<?php $id_ind=$row_edit_ind['code_indicateur_ptba']; $id_ind_tache=$row_edit_ind['id_indicateur_tache']; $unite = $row_edit_ind['unite']; $fn = ($unite=="%")?'avg':'sum';

$query_cible_ind = "SELECT $fn(cible) as valeur_cible FROM ".$database_connect_prefix."cible_indicateur_trimestre where indicateur='$id_ind_tache' group by indicateur";
      try{
    $cible_ind = $pdar_connexion->prepare($query_cible_ind);
    $cible_ind->execute();
    $row_cible_ind = $cible_ind ->fetch();
    $totalRows_cible_ind = $cible_ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//echo $query_cible_ind;

 $valc = ($totalRows_cible_ind>0)?$row_cible_ind['valeur_cible']:0; $cible_total = $valc; //} else { $valc = ($totalRows_cible_ind>0)?$row_cible_ind['cible_txt']:'';  $cible_total = 0; }

if(strtoupper($unite)=="OUI/NON"){ if($valc==1) $valc="Oui"; else $valc="Non";}

 echo " (<span style='color:#CC0000'>".$valc."</span>)";?></span></td>

<?php

//suivi indicateur

$query_suivi_ind = "SELECT * FROM indicateur_tache where id_indicateur_tache='$id_ind_tache'";
      try{
    $suivi_ind = $pdar_connexion->prepare($query_suivi_ind);
    $suivi_ind->execute();
    $row_suivi_ind = $suivi_ind ->fetchAll();
    $totalRows_suivi_ind = $suivi_ind->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$total = 0;


 ?>

<td align="right" height="30">
<?php //if(1>0){ if (isset ($_SESSION["clp_niveau"]) && ($cmp!='%')) {?>
<?php //if(($total < $valc)){ ?>
<a href="<?php echo $_SERVER['PHP_SELF']."?id_act=$id_act&cmp=$cmp&annee=$annee&id_ind=$id_ind_tache&add=true"; ?>" title="Ajout d'Indicateur" class="simple"><div style="padding:5px 0;">&nbsp;&nbsp;<img src='./images/plus.gif' width='15' height='15' alt='Ajouter'>&nbsp;&nbsp;</div></a>
<?php  //} ?>
</td>

  </tr>

                          <tr >

                            <td colspan="3" ><?php if($totalRows_suivi_ind>0) {?>


<?php $tt=0; $max=0; $idmax=0;  foreach($row_suivi_ind as $row_suivi_ind){  
 ?>

<?php if(1==1) {    ?>
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >

<thead>

<tr>

<td>Commune  </td>

<td>Date suivi</td>
<td>Valeur</td>
<?php if (isset($_SESSION["clp_niveau"]) &&  $cmp!='%') {?>
<td align="center">Actions</td>
<?php } ?>
</tr>
</thead>
<?php
$id_ind_tache=$row_suivi_ind["id_indicateur_tache"];
$query_suivi_ind0 = "SELECT * FROM ".$database_connect_prefix."suivi_indicateur_tache,indicateur_tache where indicateur='$id_ind_tache' and id_indicateur_tache=indicateur  order by id_suivi asc ";
      try{
    $suivi_ind0 = $pdar_connexion->prepare($query_suivi_ind0);
    $suivi_ind0->execute();
    $row_suivi_ind0 = $suivi_ind0 ->fetchAll();
    $totalRows_suivi_ind0 = $suivi_ind0->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_suivi_ind0>0){  foreach($row_suivi_ind0 as $row_suivi_ind0){  
?>
<tr>

<td><?php if(isset($commune_array[$row_suivi_ind0["commune"]])) echo $commune_array[$row_suivi_ind0["commune"]]; else echo " ";?></td>
<td align="center"><?php echo date_reg($row_suivi_ind0['date_suivi'],"/"); ?></td>

<td align="<?php echo (!in_array($unite,$array_indic))?'right':''; ?>">

<?php if(strtoupper($unite)!="OUI/NON"){ echo $row_suivi_ind0['valeur_suivi']; $vals = $row_suivi_ind0['valeur_suivi'];  $tt=$tt+$row_suivi_ind0['valeur_suivi']; } else { if($row_suivi_ind0['valeur_suivi']==1) echo "Oui"; else echo "Non"; $vals = $row_suivi_ind0['valeur_suivi'];  $tt=$tt+$row_suivi_ind0['valeur_suivi']; } ?></td>

<?php if (isset ($_SESSION["clp_niveau"]) &&  $cmp!='%') {?>
<td align="center">



<a href="<?php echo $_SERVER['PHP_SELF']."?id_act=$id_act&cmp=$cmp&annee=$annee&id_ind=$id_ind_tache&add=true&iden=".$row_suivi_ind0['id_suivi']; ?>" title="Ajout d'Indicateur" class="simple" style="margin-right:5px;"><img src='./images/edit.png' width='20' height='20' alt='Modifier'></a>

<a href="<?php echo $_SERVER['PHP_SELF']."?id_sup_ind=".$row_suivi_ind0['id_suivi']."&id_act=$id_act&cmp=$cmp&id_ind=$id_ind_tache&annee=$annee";?>" onClick="return confirm('Voulez-vous vraiment supprimer le suivi du <?php echo implode('-',array_reverse(explode('-',$row_suivi_ind0['date_suivi'])));?>?');" /><img src="./images/delete.png" width="20" height="20"/></a>

</td><?php  } ?></tr>

<?php  }}   ?></table>
<?php } } ?>





                            <?php } ?></td>



                          </tr>

<tr><td colspan="2">&nbsp;</td></tr>

</table>

<?php } }else echo "<h3 align='center'>Aucun indicateur planifié</h3>"; ?>

</div>

<div class="clear h0"></div>

 </div>

<?php } ?>

</body>

</html>



