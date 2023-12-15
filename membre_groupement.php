<?php    
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
$path = './';
include_once $path.'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $path.$config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=ISO-8859-15');

//fonction calcul nb jour


function NbJours($debut, $fin) {
  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);
  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) -
          mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
  return(($diff / 86400)+1);
}

$editFormAction = $_SERVER['PHP_SELF'];
/*if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}*/

$page = $_SERVER['PHP_SELF'];
$id_fiche=$_GET['id_fiche']; //$rec=$_GET['rec'];$annee=$_GET['annee'];
//insertion des plans

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  //if($_POST['proportion']>$_POST['tmax']) $_POST['proportion']=$_POST['tmax'];
$id_fiche=$_POST['id_fiche'];
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."membre_groupement (groupement, nom_prenom, age, sexe, niveau_etude, poste_occupe, id_personnel, date_enregistrement) VALUES (%s, %s, %s,%s, %s, %s,'$personnel', '$date')",
              GetSQLValueString($_POST['id_fiche'], "text"),
              GetSQLValueString($_POST['nom_prenom'], "text"),
              GetSQLValueString($_POST['age'], "int"),
			  GetSQLValueString($_POST['sexe'], "text"),
              GetSQLValueString($_POST['niveau_etude'], "text"),
              GetSQLValueString($_POST['poste_occupe'], "text"));
 try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
      $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?id_fiche=$id_fiche&insert=ok"; else $insertGoTo .= "?id_fiche=$id_fiche&insert=no";

  header(sprintf("Location: %s", $insertGoTo)); exit();
}

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
      $id = $_POST["MM_delete"];
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."membre_groupement WHERE id_membre=%s",
                           GetSQLValueString($id, "int"));

 try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
        $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?id_fiche=$id_fiche&del=ok"; else $insertGoTo .= "?id_fiche=$id_fiche&del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];  $id = ($_POST["MM_update"]);
  $id_fiche=$_POST['id_fiche'];
  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."membre_groupement SET   nom_prenom=%s, age=%s, sexe=%s, niveau_etude=%s, poste_occupe=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_membre='$id'",
             
              // GetSQLValueString($id_fiche, "text"),
              GetSQLValueString($_POST['nom_prenom'], "text"),
              GetSQLValueString($_POST['age'], "int"),
			  GetSQLValueString($_POST['sexe'], "text"),
              GetSQLValueString($_POST['niveau_etude'], "text"),
              GetSQLValueString($_POST['poste_occupe'], "text"));
 try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?update=ok&id_fiche=$id_fiche"; else $insertGoTo .= "?update=ok&id_fiche=$id_fiche";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}
}

if(isset($_GET["id_sup_pd"])) { $ids=$_GET["id_sup_pd"];
//mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sup_loc= "DELETE FROM ".$database_connect_prefix."membre_groupement WHERE id_membre='$ids'";
 try{
        $Result1 = $pdar_connexion->prepare($query_sup_loc);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?id_fiche=$id_fiche&del=ok"; else $insertGoTo .= "?id_fiche=$id_fiche&del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if(isset($_GET["id"])) { $id=$_GET["id"]; 
$query_edit_exploitant = "SELECT * FROM ".$database_connect_prefix."membre_groupement WHERE id_membre='$id'";
          try{
    $edit_exploitant = $pdar_connexion->prepare($query_edit_exploitant);
    $edit_exploitant->execute();
    $row_edit_exploitant = $edit_exploitant ->fetch();
    $totalRows_edit_exploitant = $edit_exploitant->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}
  $query_edit_recm = "SELECT * FROM ".$database_connect_prefix."liste_op WHERE  id_op='$id_fiche'";
          try{
    $edit_recm = $pdar_connexion->prepare($query_edit_recm);
    $edit_recm->execute();
    $row_edit_recm = $edit_recm ->fetch();
    $totalRows_edit_recm = $edit_recm->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  
$query_plan_dec = "SELECT * FROM ".$database_connect_prefix."membre_groupement WHERE  groupement='$id_fiche' order by nom_prenom";
        try{
    $plan_dec = $pdar_connexion->prepare($query_plan_dec);
    $plan_dec->execute();
    $row_plan_dec = $plan_dec ->fetchAll();
    $totalRows_plan_dec = $plan_dec->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_theme = "SELECT * FROM niveau_etude order by id_niveau";
        try{
    $liste_theme = $pdar_connexion->prepare($query_liste_theme);
    $liste_theme->execute();
    $row_liste_theme = $liste_theme ->fetchAll();
    $totalRows_liste_theme = $liste_theme->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


$query_liste_village1 = "SELECT  code_village, nom_village, nom_commune FROM village, commune where code_commune=commune  order by nom_commune asc";
     try{
    $liste_village1 = $pdar_connexion->prepare($query_liste_village1);
    $liste_village1->execute();
    $row_liste_village1 = $liste_village1 ->fetchAll();
    $totalRows_liste_village1 = $liste_village1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $liste_village_array = array();
  if($totalRows_liste_village1>0){   foreach($row_liste_village1 as $row_liste_village1){ 
    $liste_village_array[$row_liste_village1["code_village"]] = $row_liste_village1["nom_village"];
  }  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>
<script type="text/javascript" src="plugins/noty/layouts/top.js"></script>
<script type="text/javascript" src="plugins/noty/themes/default.js"></script>
<script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>
<script type="text/javascript">
$().ready(function() {
<?php if(!isset($_GET["show"])){ ?>
        $(".modal-dialog", window.parent.document).width(800);
<?php }else{ ?>
        $(".modal-dialog", window.parent.document).width(800);
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
<?php } ?>
});
</script>
<style>
@media(min-width:558px){.col-md-12 {width: 100%;}.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}
</style>
</head>
<body>
<?php if(!isset($_GET["show"])){ ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i><strong><strong><span class="Style14">
<?php  echo "Membre du groupement d&eacute;muni";?>
</span></strong></strong></h4>
  <div class="toolbar no-padding"><?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) {?>
<a href="<?php echo $_SERVER['PHP_SELF']."?id_fiche=$id_fiche&show=1"; ?>" title="Ajout de bénéficiaire Direct " class="pull-right p11"><i class="icon-plus"> Ajouter </i></a><?php } ?>
</div></div>

<div class="widget-content">
<div>
<strong><u>Nom du groupement </u>:<span class="Style14" style="color:#990000">
<?php if (isset ($row_edit_recm['nom_op'])) echo $row_edit_recm['nom_op'];?>
</span></strong>&nbsp;&nbsp; <strong><br />
<u>Village </u>:
<?php if (isset($liste_village_array[$row_edit_recm['village']])) echo $liste_village_array[$row_edit_recm['village']];?>
<br />
<u>Type </u>
:
<?php if (isset ($row_edit_recm['type_organisation'])) echo $row_edit_recm['type_organisation'];?>
<br />
<u>Date</u>:
<span class="Style4">
<?php if(isset($row_edit_recm['date_creation']) && $row_edit_recm['date_creation']!="0000-00-00") echo date_reg($row_edit_recm['date_creation'],"/"); else echo "--"; ?>
</span>
</strong></div>

  <table style="border-collapse: collapse;" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
    <thead>
      <tr role="row">
        <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Nom et pr&eacute;noms </th>
        <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div align="center">Age</div></th>
        <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div align="center">Sexe</div></th>
        <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div align="center">Niveau d'&eacute;tude</div></th>
        <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Poste occup&eacute; </th>
        <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
        <th width="80" align="center" class="" role="" tabindex="0" aria-controls="" aria-label=""><center>Actions</center></th>
<?php } ?>
      </tr>
    </thead>
    <tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
      <?php $t = 0;if ($totalRows_plan_dec > 0) {$p1 = "j";$t = 0;$i = 0; foreach($row_plan_dec as $row_plan_dec){ ?>
      <tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
        <td ><?php echo $row_plan_dec['nom_prenom']; ?></td>
        <td><div align="center"><?php echo number_format($row_plan_dec['age'], 0, ',', ' '); ?></div></td>
        <td><div align="center"><?php echo $row_plan_dec['sexe']; ?></div></td>
        <td><div align="center"><?php echo $row_plan_dec['niveau_etude']; ?></div></td>
        <td><?php echo $row_plan_dec['poste_occupe']; ?></td>
        <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
        <td align="center">
<?php
echo do_link("",$_SERVER['PHP_SELF']."?id_fiche=$id_fiche&id=".$row_plan_dec['id_membre']."&show=1","Modifier suivi","","edit","./","","",0,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_fiche=$id_fiche&id_sup_pd=".$row_plan_dec['id_membre'],"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce membre ?');",0,"margin:0px 5px;",$nfile);
?>        </td>
        <?php }?>
      </tr>
<?php }  ?>

      <?php }else echo "<tr><td colspan='".(((isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1))?12:12)."' align='center'>Aucune donn&eacute;e!</td></tr>"?>
    </tbody>
  </table>

  </div>
</div>

<?php } else{ ?>

<?php //if(isset($tolp) && $tolp<100 || (isset($_GET["id"]))){
  //if(isset($_GET["id"])) $tolp=$tolp-$row_edit_plan['proportion']; ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php if(isset($_GET['id'])) echo $row_edit_recm['nom_op']; else echo $row_edit_recm['nom_op'] ; ?></h4>
<a href="<?php echo $_SERVER['PHP_SELF']."?id_fiche=$id_fiche"; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="<?php echo $editFormAction; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">
  <table border="0" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:10px;">
   
    <tr>
      <td valign="top"><div class="form-group">
          <label for="nom_prenom" class="col-md-12 control-label">Nom et pr&eacute;noms <span class="required">*</span></label>
          <div class="col-md-6">
            <input name="nom_prenom" type="text" class="form-control required" id="nom_prenom" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_exploitant['nom_prenom'];?>" />
          </div>
      </div></td>
      <td valign="top"><div class="form-group">
          <label for="sexe" class="col-md-12 control-label">Sexe<span class="required">*</span></label>
          <div class="col-md-6">
            <select name="sexe" id="sexe" class="full-width-fix select2-select-00 required" data-placeholder="Sexe">
              <option value="">Choisissez</option>
              <option value="M" <?php if (isset($row_edit_exploitant["sexe"]) && "M"==$row_edit_exploitant["sexe"]) {echo "SELECTED";} ?>>M</option>
              <option value="F" <?php if (isset($row_edit_exploitant["sexe"]) && "F"==$row_edit_exploitant["sexe"]) {echo "SELECTED";} ?>>F</option>
            </select>
          </div>
      </div></td>
      <td valign="top"><div class="form-group">
          <label for="age" class="col-md-12 control-label">Age (ans) <span class="required">*</span></label>
          <div class="col-md-6">
            <input name="age" type="text" class="form-control required" id="age" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_exploitant['age'];?>" />
          </div>
      </div></td>
    </tr>
    <tr>
      <td valign="top"><div class="form-group">  <label for="niveau_etude" class="col-md-12 control-label">Niveau d'&eacute;tude <span class="required">*</span></label>
       <div class="col-md-12">
         <select name="niveau_etude" id="niveau_etude" class="full-width-fix select2-select-00 required" data-placeholder="niveau_etude">
           <option value="">Choisissez</option>
           <?php if($totalRows_liste_theme>0){  foreach($row_liste_theme as $row_liste_theme){  ?>
           <option value="<?php echo $row_liste_theme['categorie']; ?>" <?php if (isset($row_edit_exploitant["niveau_etude"]) && $row_liste_theme['categorie']==$row_edit_exploitant["niveau_etude"]) {echo "SELECTED";} ?>><?php echo $row_liste_theme['categorie']; ?></option>
           <?php  }  } ?>
         </select>
       </div>
      </div></td>
      <td valign="top"><div class="form-group">
          <label for="poste_occupe" class="col-md-12 control-label">Poste occup&eacute;<span class="required">*</span></label>
          <div class="col-md-6">
            <select name="poste_occupe" id="poste_occupe" class="full-width-fix select2-select-00 required" data-placeholder="Sexe">
              <option value="">Choisissez</option> 
			  <option value="Membre simple" <?php if (isset($row_edit_exploitant["poste_occupe"]) && "Membre simple"==$row_edit_exploitant["poste_occupe"]) {echo "SELECTED";} ?>>Membre simple</option>
         <option value="Président" <?php if (isset($row_edit_exploitant["poste_occupe"]) && "Président"==$row_edit_exploitant["poste_occupe"]) {echo "SELECTED";} ?>>Pr&eacute;sident</option>
              <option value="Secrétaire administratif" <?php if (isset($row_edit_exploitant["poste_occupe"]) && "Secrétaire administratif"==$row_edit_exploitant["poste_occupe"]) {echo "SELECTED";} ?>>Secr&eacute;taire administratif</option>
			  <option value="Trésorier" <?php if (isset($row_edit_exploitant["poste_occupe"]) && "Trésorier"==$row_edit_exploitant["poste_occupe"]) {echo "SELECTED";} ?>>Tr&eacute;sorier</option>
<option value="Chargé de relation avec les SFD" <?php if (isset($row_edit_exploitant["poste_occupe"]) && "Chargé de relation avec les SFD"==$row_edit_exploitant["poste_occupe"]) {echo "SELECTED";} ?>>Charg&eacute; de relation avec les SFD</option>
<option value="Chargé de l'organisation" <?php if (isset($row_edit_exploitant["poste_occupe"]) && "Chargé de l'organisation"==$row_edit_exploitant["poste_occupe"]) {echo "SELECTED";} ?>>Charg&eacute; de l'organisation</option>
<option value="Président du CS" <?php if (isset($row_edit_exploitant["poste_occupe"]) && "Président du CS"==$row_edit_exploitant["poste_occupe"]) {echo "SELECTED";} ?>>Pr&eacute;sident du CS</option>
<option value="1er membre du CS" <?php if (isset($row_edit_exploitant["poste_occupe"]) && "1er membre du CS"==$row_edit_exploitant["poste_occupe"]) {echo "SELECTED";} ?>>1er membre du CS</option>
<option value="2eme membre du CS" <?php if (isset($row_edit_exploitant["poste_occupe"]) && "2eme membre du CS"==$row_edit_exploitant["poste_occupe"]) {echo "SELECTED";} ?>>2eme membre du CS</option>
            </select>
          </div>
      </div></td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"></tr><tr><td colspan="2"></td>
    <tr><td colspan="2"></tr>
  </table>
  <div class="form-actions">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"])) echo $_GET["id"]; else echo "MM_insert" ; ?>" size="32" alt="">
  <a title="Annuler" href="<?php echo $_SERVER['PHP_SELF']."?id_fiche=$id_fiche"; ?>" class="btn btn-default pull-right">Annuler</a>
<?php if(isset($_GET["id"])) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cet exploitant ?','<?php echo $_GET["id"]; ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
  <input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
  <input name="id_fiche" id="id_fiche" type="hidden" value="<?php echo $id_fiche; ?>" size="32" alt="">
</div>
</form>

</div> </div>
		<?php } ?>
<?php // } ?>
</body>
</html>