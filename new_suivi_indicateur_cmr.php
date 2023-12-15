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
////header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET['id_ind'])) $id_ind = $_GET['id_ind']; else $id_ind=0;
if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else $annee=date("Y");
if(isset($_GET['code_act'])) { $code_act = $_GET['code_act']; }
$page1="";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$page = $_SERVER['PHP_SELF'];

$array_indic = array("OUI/NON","texte");

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_ind = "SELECT * FROM ".$database_connect_prefix."referentiel_indicateur where code_ref_ind='$code_act'";
$edit_ind = mysql_query_ruche($query_edit_ind , $pdar_connexion) or die(mysql_error());
$row_edit_ind = mysql_fetch_assoc($edit_ind);
$totalRows_edit_ind = mysql_num_rows($edit_ind);
$unite = (isset($row_edit_ind["unite"]))?strtoupper($row_edit_ind["unite"]):"";

//insertion suivi indicateur
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
$dir = './attachment/cmr/';
if(!is_dir($dir)) mkdir($dir);
$Result2 = false; $link = array();
for($j=0;$j<count($_FILES["fichier"]['name']);$j++)
{
  $ext = substr(strrchr($_FILES["fichier"]['name'][$j], "."), 1);
  //if(in_array($ext,$ext_autorisees))
  //{
    $new_name = $_FILES["fichier"]['name'][$j];
    $Result2 = move_uploaded_file($_FILES["fichier"]['tmp_name'][$j],
    $dir.$new_name);
    if($Result2) array_push($link,$new_name);
    if($Result2) mysql_query_ruche("DOC".$dir.$new_name, $pdar_connexion,1);
  //}
}
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."suivi_indicateur_cmr (indicateur_cr, annee, date_reelle, ".(in_array($unite,$array_indic)?'valeur_txt':'valeur_suivi').", observation, document, commune, projet, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, '$personnel', '$date')",
            GetSQLValueString($_POST['id_ind'], "text"),
            GetSQLValueString($_POST['annee'], "int"),
            GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_reelle']))), "date"),
            GetSQLValueString($_POST['valeur_suivi'], (in_array($unite,$array_indic)?'text':'double')),
            GetSQLValueString($_POST['observation'], "text"),
            GetSQLValueString((($Result2)?implode("|",$link):""), "text"),
            GetSQLValueString($_POST['commune'], "text"),
            GetSQLValueString($_SESSION["clp_projet"], "text"));

	  mysql_select_db($database_pdar_connexion, $pdar_connexion);
	  $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error());

  $insertGoTo = $_SERVER['PHP_SELF']."?code_act=$code_act&annee=$annee&id_ind=$id_ind";
  if ($Result1) $insertGoTo .= "&insert=ok"; else $insertGoTo .= "&insert=no";
  $insertGoTo .= "&mod=1";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
}


//update suivi indicateur
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
$dir = './attachment/cmr/';
if(!is_dir($dir)) mkdir($dir);
$Result2 = false; $link = array();
for($j=0;$j<count($_FILES["fichier"]['name']);$j++)
{
  $ext = substr(strrchr($_FILES["fichier"]['name'][$j], "."), 1);
  //if(in_array($ext,$ext_autorisees))
  //{
    $new_name = $_FILES["fichier"]['name'][$j];
    $Result2 = move_uploaded_file($_FILES["fichier"]['tmp_name'][$j],
    $dir.$new_name);
    if($Result2) array_push($link,$new_name);
    if($Result2) mysql_query_ruche("DOC".$dir.$new_name, $pdar_connexion,1);
  //}
}
  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."suivi_indicateur_cmr SET indicateur_cr=%s, annee=%s, date_reelle=%s, ".(in_array($unite,$array_indic)?'valeur_txt':'valeur_suivi')."=%s, observation=%s, document=%s, commune=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_suivi=%s",
        GetSQLValueString($_POST['id_ind'], "text"),
        GetSQLValueString($_POST['annee'], "int"),
        GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_reelle']))), "date"),
        GetSQLValueString($_POST['valeur_suivi'], (in_array($unite,$array_indic)?'text':'double')),
        GetSQLValueString($_POST['observation'], "text"),
        GetSQLValueString((($Result2)?implode("|",$link):""), "text"),
        GetSQLValueString($_POST['commune'], "text"),
        GetSQLValueString($_POST['id_suivi'], "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error());

  $insertGoTo = $_SERVER['PHP_SELF']."?code_act=$code_act&annee=$annee&id_ind=$id_ind";
  if ($Result1) $insertGoTo .= "&update=ok"; else $insertGoTo .= "&update=no";
  $insertGoTo .= "&mod=1";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
}

//Cible indicateur à sommer
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cible_indicateur = "SELECT indicateur_cr, annee, sum(valeur_cible) as valeur_cible, avg(valeur_cible) as valeur_ciblem  FROM   ".$database_connect_prefix."cible_cmr WHERE indicateur_cr='$code_act' group by annee, indicateur_cr";
$cible_indicateur  = mysql_query_ruche($query_cible_indicateur , $pdar_connexion) or die(mysql_error());
$row_cible_indicateur = mysql_fetch_assoc($cible_indicateur );
$totalRows_cible_indicateur = mysql_num_rows($cible_indicateur );
$cible_array = array();
$ciblem_array = array(); $total_cible = 0;
if($totalRows_cible_indicateur>0){
 do{
  $cible_array[$row_cible_indicateur["indicateur_cr"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_cible"];
  $ciblem_array[$row_cible_indicateur["indicateur_cr"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_ciblem"];
  if(!empty($row_cible_indicateur["valeur_cible"])) $total_cible = $row_cible_indicateur["valeur_cible"];
   }
while($row_cible_indicateur  = mysql_fetch_assoc($cible_indicateur));}

$cible_val_array = $cible_val_txt_array = array();
if(isset($row_edit_ind["id_ref_ind"]))
{
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_cible_indicateur = "SELECT indicateur_cr, annee, sum(valeur_suivi) as valeur_suivi, valeur_txt FROM   ".$database_connect_prefix."suivi_indicateur_cmr WHERE indicateur_cr='".$row_edit_ind["id_ref_ind"]."' and projet='".$_SESSION['clp_projet']."' group by annee, indicateur_cr";
  $cible_indicateur  = mysql_query_ruche($query_cible_indicateur , $pdar_connexion) or die(mysql_error());
  $row_cible_indicateur = mysql_fetch_assoc($cible_indicateur );
  $totalRows_cible_indicateur = mysql_num_rows($cible_indicateur );
  if($totalRows_cible_indicateur>0){
   do{
     if(!in_array($unite,$array_indic))
     {
       if(!isset($cible_val_array[$row_cible_indicateur["annee"]])) $cible_val_array[$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_suivi"];
       else $cible_val_array[$row_cible_indicateur["annee"]]+=$row_cible_indicateur["valeur_suivi"];
     }
     else
     {
       $cible_val_txt_array[$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_txt"];
     }
  }while($row_cible_indicateur  = mysql_fetch_assoc($cible_indicateur));}
}

if(isset($_GET["id_sup_ind"]))
{
  $id=$_GET["id_sup_ind"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_sup_ind = "DELETE FROM ".$database_connect_prefix."suivi_indicateur_cmr WHERE id_suivi='$id'";
  $Result1 = mysql_query_ruche($query_sup_ind, $pdar_connexion) or die(mysql_error());
  $insertGoTo = $_SERVER['PHP_SELF']."?code_act=$code_act&annee=$annee&id_ind=$id_ind";
  if ($Result1) $insertGoTo .= "&del=ok"; else $insertGoTo .= "&del=no";
  $insertGoTo .= "&mod=1";
  header(sprintf("Location: %s", $insertGoTo));
}

if(isset($_GET["iden"]))
{
  $id=$_GET["iden"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_edit_ind1 = "SELECT * FROM ".$database_connect_prefix."suivi_indicateur_cmr WHERE id_suivi='$id'";
  $edit_ind1  = mysql_query_ruche($query_edit_ind1 , $pdar_connexion) or die(mysql_error());
  $row_edit_ind1  = mysql_fetch_assoc($edit_ind1);
  $totalRows_edit_ind1  = mysql_num_rows($edit_ind1);
}

mysql_select_db($database_pdar_connexion, $pdar_connexion); //and code_region in ($ug)
$query_liste_commune = "SELECT code_commune, nom_commune FROM ".$database_connect_prefix."region, ".$database_connect_prefix."departement, ".$database_connect_prefix."commune WHERE code_region=region and code_departement=departement ORDER BY nom_commune asc";
$liste_commune  = mysql_query_ruche($query_liste_commune , $pdar_connexion) or die(mysql_error());
$row_liste_commune = mysql_fetch_assoc($liste_commune);
$totalRows_liste_commune = mysql_num_rows($liste_commune);
$commune_array = array();
do{
  $commune_array[$row_liste_commune["code_commune"]] = $row_liste_commune["nom_commune"];
}while($row_liste_commune = mysql_fetch_assoc($liste_commune));
$rows = mysql_num_rows($liste_commune);
if($rows > 0) {
mysql_data_seek($liste_commune, 0);
$row_liste_commune = mysql_fetch_assoc($liste_commune);
}
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
  $(".modal-dialog", window.parent.document).width(800);
<?php if(isset($_GET['add'])) { ?>
        $(".modal-dialog", window.parent.document).width(700);
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
        $(".modal-dialog", window.parent.document).width(600);
        $(".select2-select-00").select2({allowClear:true});
<?php } ?>
<?php if(isset($_GET['mod'])) { for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ if(!in_array($unite,$array_indic)){ if(isset($cible_val_array[$i])){ ?>
        $("#ind_<?php echo $i."_".$code_act; ?>", window.parent.document).html("<?php echo number_format($cible_val_array[$i], 0, ',', ' '); ?>");
<?php } }else{ if(isset($cible_val_text_array[$i])){ ?>
        $("#ind_<?php echo $i."_".$code_act; ?>", window.parent.document).html("<?php echo number_format($cible_val_text_array[$i], 0, ',', ' '); ?>");
<?php } } } ?>
        // reload parent frame
        //$(".close", window.parent.document).click(function(){
          //window.parent.location.reload();
//get_content('suivi_indicateur_ptba_reload.php','<?php echo "id=$code_act&annee=$annee&l=3"; ?>','label1_<?php echo $code_act; ?>','','',1);
        //});
        /*$("button[data-dismiss='modal']", window.parent.document).click(function(){
          //window.parent.location.reload();
        }); */
<?php } ?>
  });
</script>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td, td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}
.dataTables_length, .dataTables_info { float: left; font-size: 10px;}
.dataTables_length, .dataTables_paginate, span.ui-datepicker-append { display: none!important;}

@media(min-width:558px){.col-md-12 {width: 100%;}.col-md-9 {width: 75%;}.col-md-8 {width: 70%;}.col-md-4 {width: 30%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}
</style>
</head>
<body>
<?php if(isset($_GET['id_ind']) && isset($_GET["add"])) {
if(isset($_GET['id_ind'])) $id_ind = $_GET['id_ind']; else $id_ind=0; ?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Nouveau suivi d'indicateur</h4>
<a href="<?php echo (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF']."?code_act=$code_act&annee=$annee&id_ind=$id_ind"; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="<?php echo $_SERVER['PHP_SELF']."?code_act=$code_act&annee=$annee&id_ind=$id_ind"; ?>" class="row-border" method="post" enctype="multipart/form-data" name="form4" id="form3" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="5" cellpadding="0" width="100%" style="font-size:14px;">
	<tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="commune" class="col-md-4 control-label">Commune <span class="required">*</span></label>
          <div class="col-md-8">
            <select name="commune" id="commune" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez une Commune">
            <option></option>
<!--  <option value="" <?php //if(isset($_GET["iden"]) && $row_edit_ind1['commune']==0) echo 'SELECTED="selected"'; ?> >-- Choisissez --</option> -->
<?php do { ?>
    <option value="<?php echo $row_liste_commune['code_commune']; ?>" <?php if(isset($_GET["iden"]) && $row_liste_commune['code_commune']==$row_edit_ind1['commune']) echo 'SELECTED="selected"'; ?> ><?php echo $row_liste_commune['nom_commune']?></option>
    <?php } while ($row_liste_commune = mysql_fetch_assoc($liste_commune));
$rows = mysql_num_rows($liste_commune);
if($rows > 0) {
mysql_data_seek($liste_commune, 0);
$row_liste_commune = mysql_fetch_assoc($liste_commune);
} ?>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td width="50%">
        <div class="form-group">
          <label for="annee" class="col-md-11 control-label">Ann&eacute;e <span class="required">*</span></label>
          <div class="col-md-12">
            <select name="annee" id="annee" class="form-control required" >
            <option value="">-- Choisissez --</option>
<?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
    <option value="<?php echo $i; ?>" <?php if(isset($_GET["iden"]) && $i==$row_edit_ind1['annee']) echo 'SELECTED="selected"'; ?> ><?php echo $i; ?></option>
<?php } ?>
            </select>
          </div>
        </div>
      </td>
      <td width="50%">
        <div class="form-group">
          <label for="date_reelle" class="col-md-11 control-label">Date de suivi <span class="required">*</span></label>
          <div class="col-md-12">
            <input class="form-control datepicker required" type="text" name="date_reelle" id="date_reelle" value="<?php if(isset($_GET["iden"])) echo implode('/',array_reverse(explode('-',$row_edit_ind1['date_reelle']))); else echo date("d/m/Y"); ?>" size="32" />
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="valeur_suivi" class="col-md-11 control-label">Valeur mesurée  <span class="required">*</span></label>
          <div class="col-md-12">
<?php if(!in_array($unite,$array_indic)){ ?>
            <input class="form-control required" type="text" name="valeur_suivi" id="valeur_suivi" value="<?php if(isset($_GET["iden"])) echo $row_edit_ind1['valeur_suivi']; ?>" size="32" />
<?php } else { ?>
            <textarea class="form-control required" name="valeur_suivi" id="valeur_suivi" cols="25" rows="2"><?php if(isset($_GET["iden"])) echo $row_edit_ind1['valeur_txt']; ?></textarea>
<?php } ?>
          </div>
        </div>
      </td>
      <td>
        <div class="form-group">
          <label for="observation" class="col-md-11 control-label">Observation </label>
          <div class="col-md-12">
            <textarea class="form-control" name="observation" id="observation" cols="25" rows="2"><?php if(isset($_GET["iden"])) echo $row_edit_ind1['observation']; ?></textarea>
          </div>
        </div>
      </td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="fichier" class="col-md-4 control-label">Documents </label>
          <div class="col-md-8">
            <input class="form-control" type="file" name="fichier[]" id="fichier" value="" size="32" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/pdf,application/vnd.ms-word,image/jpeg,.doc,.docx,.zip,.rar" multiple />
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET['iden'])) { ?>
<input type="hidden" name="id_suivi" value="<?php echo $_GET["iden"];  ?>" />
<?php }  ?>
<input type="hidden" name="id_ind" value="<?php echo (isset($_GET['iden']))?$row_edit_ind1['indicateur_cr']:$id_ind;  ?>" />
<input name="Envoyer" type="submit" class="btn btn-success pull-right" value="<?php echo "Enregistrer" ; ?>" />
<input type="hidden" name="<?php if(isset($_GET['iden'])) echo "MM_update"; else echo "MM_insert";  ?>" value="form2" />
</div>
</form>

</div> </div>
<?php } else { ?>

<?php
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT `table`, nom FROM ".$database_connect_prefix."fiche_config";
$entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);
$feuille_array = array();
if($totalRows_entete>0){ do{ $feuille_array[$database_connect_prefix.$row_entete["table"]] = $row_entete["nom"]; }while($row_entete  = mysql_fetch_assoc($entete)); }
  //PTBA
/*  $appendice_array =array();
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_appendice4 = "SELECT intitule_indicateur_cmr,code_activite_ptba,intitule_activite_ptba,id_indicateur_cmr, unite FROM ".$database_connect_prefix."ptba, ".$database_connect_prefix."indicateur_cmr where code_activite_ptba=code_activite and code_activite_ptba='$code_act' and ".$database_connect_prefix."ptba.annee='$annee' and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' ORDER BY code_activite_ptba,intitule_indicateur_cmr";
  $appendice4  = mysql_query_ruche($query_appendice4 , $pdar_connexion) or die(mysql_error());
  $row_appendice4  = mysql_fetch_assoc($appendice4);
  $totalRows_appendice4  = mysql_num_rows($appendice4);
  if($totalRows_appendice4>0){ do{
  $appendice_array[$row_appendice4["id_indicateur_cmr"]]=$row_appendice4["intitule_indicateur_cmr"]."|".$row_appendice4["unite"];  }while($row_appendice4  = mysql_fetch_assoc($appendice4)); }  */

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_classeur = "SELECT * FROM ".$database_connect_prefix."classeur ";
$liste_classeur = mysql_query_ruche($query_liste_classeur, $pdar_connexion) or die(mysql_error());
$row_liste_classeur = mysql_fetch_assoc($liste_classeur);
$totalRows_liste_classeur = mysql_num_rows($liste_classeur);
$liste_classeur_array = $classeur_color_array = array();
if($totalRows_liste_classeur>0){  do{
$liste_classeur_array[$row_liste_classeur["id_classeur"]]=$row_liste_classeur["libelle"];
$classeur_color_array[$row_liste_classeur["id_classeur"]]=$row_liste_classeur["couleur"];
}while($row_liste_classeur  = mysql_fetch_assoc($liste_classeur));  }

//dynamique
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = sprintf("SELECT * FROM ".$database_connect_prefix."referentiel_indicateur WHERE projet=%s and classeur is not null and feuille is not null and colonne is not null and code_ref_ind='$code_act' ORDER BY code_ref_ind",
    GetSQLValueString($_SESSION['clp_projet'], "text"));
$entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);

$cmr_realise = $indicateur_dynamique = array();
$mode_calcul = array("SOMME"=>"SUM","MOYENNE"=>"AVG","COMPTER"=>"COUNT");
if($totalRows_entete>0){ do{ $id=$row_entete["id_ref_ind"]; $col =trim($row_entete["colonne"]); $table = $row_entete["feuille"]; $classeur = $row_entete["classeur"]; $feuille = $table;
$indicateur_dynamique[$id]["feuille"] = $database_connect_prefix.$feuille;
$indicateur_dynamique[$id]["classeur"] = $classeur;
if(isset($indicateur_dynamique[$id]["lib"])) $indicateur_dynamique[$id]["lib"] = "";
list($indicateur_dynamique[$id]["lib"],$indicateur_dynamique[$id]["unite"]) = (isset($cmr_array[$row_entete['code_ref_ind']])?explode('|',$cmr_array[$row_entete['code_ref_ind']]):'NaN');
if(isset($indicateur_dynamique[$id]["color"])) $indicateur_dynamique[$id]["color"] = "";
$indicateur_dynamique[$id]["color"] = !empty($row_entete['couleur'])?$row_entete['couleur']:(isset($classeur_color_array[$classeur])?$classeur_color_array[$classeur]:'');
$type=""; $formule = (!empty($row_entete['mode_calcul_fiche']) && isset($mode_calcul[$row_entete['mode_calcul_fiche']]))?$mode_calcul[$row_entete['mode_calcul_fiche']]:$mode_calcul["SOMME"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "DESCRIBE `".$database_connect_prefix."$table`";
$liste_cp = mysql_query_ruche($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);
if($totalRows_liste_cp>0){ do{ if($row_liste_cp["Field"]==$col) $type=$row_liste_cp["Type"]; }while($row_liste_cp = mysql_fetch_assoc($liste_cp)); }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
if(strchr($table,"_details")!="")
{
  //`$table`.annee=$annee and  group by departement.region   ".$database_connect_prefix."validation_fiche.niveau1=1 and ".$database_connect_prefix."validation_fiche.niveau2=1 and
  $query_data = "SELECT $formule(`".$database_connect_prefix."$table`.$col) as nb, `".$database_connect_prefix."$table`.annee FROM `".$database_connect_prefix."$table`,".$database_connect_prefix."validation_fiche WHERE ".$database_connect_prefix."validation_fiche.id_lkey=`".$database_connect_prefix."$table`.LKEY and ".$database_connect_prefix."validation_fiche.nom_fiche='".$database_connect_prefix."$table' GROUP BY `".$database_connect_prefix."$table`.annee";
  $data  = mysql_query_ruche($query_data , $pdar_connexion);
  if($data){
  $row_data  = mysql_fetch_assoc($data);
  $totalRows_data  = mysql_num_rows($data);
  if($totalRows_data>0){ do{
    if(isset($indicateur_dynamique[$id]["val"][$row_data["annee"]])) $indicateur_dynamique[$id]["val"][$row_data["annee"]] += $row_data["nb"];
    else $indicateur_dynamique[$id]["val"][$row_data["annee"]] = $row_data["nb"]; }while($row_data  = mysql_fetch_assoc($data)); }       }
}
   }while($row_entete  = mysql_fetch_assoc($entete)); }
?>

<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Suivi des indicateurs</h4> <div class="toolbar no-padding"></div> </div>
<div class="widget-content" style="display: block;">
<?php if($totalRows_edit_ind>0){ do{ ?>
<table border="0" align="center" cellspacing="0" cellpadding="0" width="100%">
  <tr bgcolor="#CCCCCC" >
<td colspan="2" style="font-weight: bold;"><?php echo $row_edit_ind['code_ref_ind']." : ".$row_edit_ind['intitule_ref_ind']; ?><span class="Style18">
<?php $id_ind=$row_edit_ind['code_ref_ind']; $id_ind_cmr=$row_edit_ind['id_ref_ind']; $unite = $row_edit_ind['unite']; $fn = ($unite=="%")?'avg':'sum';
$valc = $total_cible; echo " (<span style='color:#CC0000'>".number_format($valc, 0, ',', ' ')." $unite</span>)";?></span></td>
<?php
//suivi indicateur
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_suivi_ind = "SELECT * FROM ".$database_connect_prefix."suivi_indicateur_cmr where indicateur_cr='$id_ind_cmr' and projet='".$_SESSION["clp_projet"]."' order by id_suivi asc";
$suivi_ind  = mysql_query_ruche($query_suivi_ind , $pdar_connexion) or die(mysql_error());
$row_suivi_ind  = mysql_fetch_assoc($suivi_ind);
$totalRows_suivi_ind  = mysql_num_rows($suivi_ind);
$total = 0;
if($totalRows_suivi_ind>0){ do{
if(!in_array($unite,$array_indic)){ $total+= $row_suivi_ind['valeur_suivi']; } else { $total1 = -1; }
}while($row_suivi_ind  = mysql_fetch_assoc($suivi_ind));
$rows = mysql_num_rows($suivi_ind);
if($rows > 0) {
mysql_data_seek($suivi_ind, 0);
$row_suivi_ind = mysql_fetch_assoc($suivi_ind);
}
}
 ?>
<td align="right">
<?php if(!in_array($id_ind_cmr,array_keys($indicateur_dynamique))){ if (isset ($_SESSION["clp_niveau"]) && $_SESSION["clp_niveau"]==1) {?>
<?php //if(($total < $valc) || (in_array($unite,$array_indic) && !isset($total1))){ ?>
<a href="<?php echo $_SERVER['PHP_SELF']."?code_act=$code_act&annee=$annee&id_ind=$id_ind_cmr&add=true"; ?>" title="Ajout d'Indicateur" class="simple"><div style="padding:5px 0;">&nbsp;&nbsp;<img src='./images/plus.gif' width='15' height='15' alt='Ajouter'>&nbsp;&nbsp;</div></a>
<?php } //}
} ?>
</td>
  </tr>
                          <tr >
                            <td colspan="3" >
<?php if($totalRows_suivi_ind>0 || in_array($id_ind_cmr,array_keys($indicateur_dynamique))) { ?>
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >
<thead>
<tr>
<?php if(in_array($id_ind_cmr,array_keys($indicateur_dynamique))) { ?>
<td>Classeur </td>
<td>Feuille</td>
<td>Ann&eacute;e</td>
<td>Valeur</td>
<td align="center">Actions</td>
<?php }else{ ?>
<td>Commune </td>
<td>Date suivi</td>
<td>Ann&eacute;e</td>
<td>Valeur</td>
<td>Observation</td>
<td>Documents</td>
<td align="center">Actions</td>
<?php } ?>
</tr>
</thead>
<?php if(in_array($id_ind_cmr,array_keys($indicateur_dynamique)))
{ $ind_dyn = $indicateur_dynamique[$id_ind_cmr]; $tt=0; $max=0; $idmax=0;
if(isset($ind_dyn["val"]) && count($ind_dyn["val"])>0){ $j=0; foreach($ind_dyn["val"] as $an=>$val){
?>
<tr>
<?php if($j==0){ ?>
<td rowspan="<?php echo count($ind_dyn["val"]); ?>"><?php if(isset($liste_classeur_array[$ind_dyn["classeur"]])) echo $liste_classeur_array[$ind_dyn["classeur"]]; else echo "NaN"; ?></td>
<td rowspan="<?php echo count($ind_dyn["val"]); ?>"><?php if(isset($feuille_array[$ind_dyn["feuille"]])) echo $feuille_array[$ind_dyn["feuille"]]; else echo "NaN"; ?></td>
<?php } ?>
<td align="center"><?php echo $an; ?></td>
<td align="right"><div align="center"><?php echo $val; $vals = $val;  $tt=$tt+$val; ?></div></td>
<td align="center">-
<?php /*if (isset ($_SESSION["clp_niveau"]) && ($_SESSION["clp_niveau"]<4) && ($_SESSION["clp_zone"]==$ug || $_SESSION["clp_niveau"]==1)) {?>
<a href="<?php echo $_SERVER['PHP_SELF']."?code_act=$code_act&annee=$annee&id_ind=$id_ind&add=true&iden=".$row_suivi_ind['id_suivi']; ?>" title="Ajout d'Indicateur" class="simple" style="margin-right:5px;"><img src='./images/edit.png' width='20' height='20' alt='Modifier'></a>
<a href="<?php echo $_SERVER['PHP_SELF']."?id_sup_ind=".$row_suivi_ind['id_suivi']."&code_act=$code_act&id_ind=$id_ind&annee=$annee";?>" onClick="return confirm('Voulez-vous vraiment supprimer le suivi du <?php echo implode('-',array_reverse(explode('-',$row_suivi_ind['date_reelle'])));?>?');" /><img src="./images/delete.png" width="20" height="20"/></a>
<?php }*/ ?>
</td></tr>
<?php $j++; } } } else { ?>
<?php $tt=0; $max=0; $idmax=0; do { ?>
<tr>
<td><?php if(isset($commune_array[$row_suivi_ind["commune"]])) echo $commune_array[$row_suivi_ind["commune"]]; else echo " ";?></td>
<td align="center"><?php echo date_reg($row_suivi_ind['date_reelle'],"/"); ?></td>
<td align="center"><?php echo $row_suivi_ind['annee']; ?></td>
<td align="<?php echo (!in_array($unite,$array_indic))?'right':''; ?>">
<?php if(!in_array($unite,$array_indic)){ echo $row_suivi_ind['valeur_suivi']; $vals = $row_suivi_ind['valeur_suivi'];  $tt=$tt+$row_suivi_ind['valeur_suivi']; } else { echo $row_suivi_ind['valeur_txt']; $vals = $row_suivi_ind['valeur_suivi'];  $tt=$tt+$row_suivi_ind['valeur_suivi']; } ?>
</td>
<td align="left"><?php echo $row_suivi_ind['observation']; ?></td>
<td align="left">
<?php
if(!empty($row_suivi_ind["document"]))
{
  $dir = './attachment/cmr/';
  if(!is_dir($dir)) mkdir($dir);
  $a = explode('|',$row_suivi_ind["document"]);
  foreach($a as $b)
  if(!empty($b) && file_exists($dir.$b))
  {
    echo "<a style='display:block;' href=\"$dir$b\" target='_blank' title='T&eacute;l&eacute;charger' alt='$b'>$b</a><!--&nbsp;&nbsp;&nbsp;-->";
  }
}
?></td>
<td align="center" width="90">
<?php if(isset ($_SESSION["clp_niveau"]) && ($_SESSION["clp_niveau"]==1)) { ?>
<a href="<?php echo $_SERVER['PHP_SELF']."?code_act=$code_act&annee=$annee&id_ind=$id_ind&add=true&iden=".$row_suivi_ind['id_suivi']; ?>" title="Ajout d'Indicateur" class="simple" style="margin-right:5px;"><img src='./images/edit.png' width='20' height='20' alt='Modifier'></a>
<a href="<?php echo $_SERVER['PHP_SELF']."?id_sup_ind=".$row_suivi_ind['id_suivi']."&code_act=$code_act&id_ind=$id_ind&annee=$annee";?>" onClick="return confirm('Voulez-vous vraiment supprimer le suivi du <?php echo implode('-',array_reverse(explode('-',$row_suivi_ind['date_reelle'])));?>?');" /><img src="./images/delete.png" width="20" height="20"/></a>
<?php } ?>
</td></tr>
<?php } while ($row_suivi_ind = mysql_fetch_assoc($suivi_ind)); ?>
    </table>
    <?php } ?></td>
  </tr>
<!--<tr><td colspan="2">&nbsp;</td></tr> -->
</table>
<div class="clear h0"></div>
<?php } echo '<div class="">&nbsp;</div>'; }while($row_edit_ind  = mysql_fetch_assoc($edit_ind)); }else echo "<h3 align='center'>Aucun indicateur planifié</h3>"; ?>
<div class="clear h0"></div> </div>
 </div>
<?php } ?>
</body>
</html>

