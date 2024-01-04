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
//header('Content-Type: text/html; charset=ISO-8859-15');

//if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y"); if(isset($_GET['cp'])) {$cp=$_GET['cp'];} else $cp=0;

//if(isset($_GET['code_modele'])) { $code_modele = $_GET['code_modele']; }
//if(isset($_GET['id_type'])) { $cat = $_GET['id_type'];}
if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y"); if(isset($_GET['cp'])) {$cp=$_GET['cp'];} else $cp=0;
if(isset($_GET['id_act'])) { $id_act = $_GET['id_act']; }
if(isset($_GET['cat'])) { $cat = $_GET['cat']; }
if(isset($_GET['code_act'])) {$code_activite = $_GET['code_act'];} else $code_activite="";
if(isset($_GET["id"])) { $id=$_GET["id"];} else $id=0;
$dir = './attachment/ptba/';
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
$lien .= "?cat=$cat";
$lien1 .= "?cat=$cat";

/*if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sup_etape = sprintf("DELETE from ".$database_connect_prefix."type_tache WHERE id_groupe_tache=%s",
                         GetSQLValueString($id, "int"));
  $Result1 = mysql_query_ruche($query_sup_etape, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
 }*/
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{
    $cat=$_POST['cat'];
    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
    /*$proportion=$_POST['proportion'];
    $intitule_tache=$_POST['intitule_tache'];
    $ordre=$_POST['ordre'];  */
    $id_groupe_tache=$_POST['id_groupe_tache'];
    $id_act=$_POST['id_act'];
    $valider=$_POST['valider'];
    $n_lot=$_POST['n_lot'];
    $date_reelle=$_POST['date_reelle'];
    $fichier1=$_FILES['fichier1']['name'];
    $fichier1_tmp=$_FILES['fichier1']['tmp_name'];
    $observation=$_POST['observation'];  //var_dump($_POST);exit;
  //echo $fichier1;

 //foreach ($id_groupe_tache as $key => $value)  { echo $valider[$key]."</br>"; } exit;
  //suppression
 /* $query_sup_cible_indicateur = "DELETE FROM ".$database_connect_prefix."groupe_tache WHERE id_activite='$id_act'";
      try{
    $Result1 = $pdar_connexion->prepare($query_sup_cible_indicateur);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }*/
    $ext_autorisees=array('docx','doc','pdf', 'xls', 'xlsx', 'jpeg', 'jpg', 'zip', 'rar', 'png'); //Extensions autoris&eacute;es
  foreach ($id_groupe_tache as $key => $value)
  {
  if(isset($date_reelle[$key]) && !empty($date_reelle[$key]) && isset($n_lot[$key]) && !empty($n_lot[$key])) 
  {
      //if(isset($valider[$key]) && $valider[$key]!=NULL) {
   $link = "";
   if ((isset($fichier1[$key]))) {
    $Result1 = false; $link = "";
    $ext = substr(strrchr($fichier1[$key], "."), 1);
	//echo  $ext ;
    if(in_array($ext,$ext_autorisees))
    {
      $Result2 = move_uploaded_file($fichier1_tmp[$key],$dir.$fichier1[$key]);
      if($Result2) $link[$key] = $fichier1[$key];
     // if($Result2) mysql_query_ruche("DOC".$dir.$link[$key], $pdar_connexion,1);
    }
  }
//echo  $fichier1_tmp[$key]; echo "<br>"; echo $fichier1[$key];  exit;
 // if(isset($valider[$key])) $valider[$key]=1; else $valider[$key]=0;  if(!isset($observation[$key]) || empty($observation[$key])) $observation[$key]=" ";
						
	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."groupe_tache SET  valider=%s, date_reelle=%s, jalon=%s".((isset($link[$key]) && !empty($fichier1[$key]) && isset($valider[$key]))?",  livrable=".GetSQLValueString($fichier1[$key], "text"):(!isset($valider[$key])?",  livrable=null":"")).", observation=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_groupe_tache=%s and id_activite=%s",
					   GetSQLValueString(isset($valider[$key])?1:0, "int"),
					   GetSQLValueString(implode('-',array_reverse(explode('/',isset($date_reelle[$key])?$date_reelle[$key]:date("d/m/Y")))), "date"),
					   GetSQLValueString((isset($n_lot[$key]) && $n_lot[$key]>=1)?$n_lot[$key]:1, "int"),
					   GetSQLValueString($observation[$key], "text"),
                       GetSQLValueString($key, "int"),
                       GetSQLValueString($id_act, "int"));
					   
//echo $categorie[$key];
//exit;
	      try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    //}
}
}
}
/*
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
   if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."type_tache (type_activite, proportion, ordre, intitule_tache, id_personnel, date_enregistrement) VALUES (%s, %s, %s,  %s, '$personnel', '$date')",
						GetSQLValueString($cat, "int"),
						GetSQLValueString($_POST['proportion'], "int"),
						GetSQLValueString($_POST['ordre'], "int"),
						GetSQLValueString($_POST['intitule_tache'], "text"));
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $insertGoTo = $_SERVER['PHP_SELF'];
  $insertGoTo .= "?id_type=$cat";
  if ($Result1) $insertGoTo .= "&insert=ok";
  else $insertGoTo .= "&insert=no";
  header(sprintf("Location: %s", $insertGoTo));
}
}*/
//$query_liste_zone = "SELECT * FROM ".$database_connect_prefix."type_tache where type_activite='$cat' ORDER BY ordre asc";
$query_liste_zone = "select * FROM ".$database_connect_prefix."groupe_tache where id_activite='$id_act' ORDER BY code_tache ASC";
        try{
    $liste_zone = $pdar_connexion->prepare($query_liste_zone);
    $liste_zone->execute();
    $row_liste_zone = $liste_zone ->fetchAll();
    $totalRows_liste_zone = $liste_zone->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$query_edit_modele = "SELECT * FROM ".$database_connect_prefix."type_activite where id_type='$cat'";
        try{
    $edit_modele = $pdar_connexion->prepare($query_edit_modele);
    $edit_modele->execute();
    $row_edit_modele = $edit_modele ->fetch();
    $totalRows_edit_modele = $edit_modele->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$libelle_modele=$row_edit_modele['categorie']."- ".$row_edit_modele['type_activite'];
$cat_ge=$row_edit_modele['categorie'];
/*$query_liste_etape = "SELECT sum(proportion) as netape, type_activite FROM type_tache  group by type_activite";
        try{
    $liste_etape = $pdar_connexion->prepare($query_liste_etape);
    $liste_etape->execute();
    $row_liste_etape = $liste_etape ->fetchAll();
    $totalRows_liste_etape = $liste_etape->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$nb_etape_array = array();
if($totalRows_liste_etape>0){  foreach($row_liste_etape as $row_liste_etape){
 $nb_etape_array[$row_liste_etape["type_activite"]]=$row_liste_etape["netape"];
}}
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_categorie = "SELECT * FROM ".$database_connect_prefix."groupe_etape where categorie_groupe='$cat_ge' ORDER BY code_groupe asc";
$liste_categorie  = mysql_query_ruche($query_liste_categorie , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_categorie  = mysql_fetch_assoc($liste_categorie);
$totalRows_liste_categorie  = mysql_num_rows($liste_categorie);*/
$query_suivi_tache = "select valider, id_groupe_tache, observation, proportion, date_reelle, livrable, jalon, n_lot  FROM ".$database_connect_prefix."groupe_tache where id_activite='$id_act'";
        try{
    $tache = $pdar_connexion->prepare($query_suivi_tache);
    $tache->execute();
    $row_suivi_tache = $tache ->fetchAll();
    $totalRows_suivi_tache = $tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$suivi_array =$observation_array =$date_reelle_array=$livrable_array=$lot_r_array=$lot_p_array=$proportion_array=array();
if($totalRows_suivi_tache>0){ foreach($row_suivi_tache as $row_suivi_tache){
 $suivi_array[$row_suivi_tache["id_groupe_tache"]] = $row_suivi_tache["valider"];
  $date_reelle_array[$row_suivi_tache["id_groupe_tache"]] = $row_suivi_tache["date_reelle"];
 $observation_array[$row_suivi_tache["id_groupe_tache"]] = $row_suivi_tache["observation"];
  $lot_r_array[$row_suivi_tache["id_groupe_tache"]] = $row_suivi_tache["jalon"];
  $lot_p_array[$row_suivi_tache["id_groupe_tache"]] = $row_suivi_tache["n_lot"];
  $proportion_array[$row_suivi_tache["id_groupe_tache"]] = $row_suivi_tache["proportion"];
  $livrable_array[$row_suivi_tache["id_groupe_tache"]] = $row_suivi_tache["livrable"];
 } }
$proportion=0;
foreach($suivi_array as $key=>$val){
if($val==1) { if(isset($lot_r_array[$key]) && $lot_r_array[$key]>0) $proportion+=($proportion_array[$key]*$lot_r_array[$key]/$lot_p_array[$key]);/*if(isset($lot_p_array[$key]) && $lot_p_array[$key]>=1 && isset($lot_r_array[$key]) && $lot_r_array[$key]>=1) $proportion+=($lot_p_array[$key]*$lot_r_array[$key]/$lot_r_array[$key]);*/ }
}
//if($proportion>0) $proportion=($proportion)*100;
if (isset($proportion) && $proportion>0 && $proportion<=100) $percent = $proportion;
elseif (isset($proportion) && $proportion>100) $percent = 100;
else $percent = 0;
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
		$("#form2").validate();
<?php if(!isset($_GET['add'])) { ?>
$(".dataTable").dataTable({"iDisplayLength": -1});

<?php } ?>
<?php //if(isset($_GET['add'])) { ?>
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
<?php //} ?>
	});
function check_proportion(){
  var p = <?php echo (isset($proportion) && !empty($proportion))?$proportion:0;  ?>;
  if(document.form1.proportion.value><?php echo (isset($proportion) && !empty($proportion))?$proportion:0;  ?>){ document.form1.proportion.value=<?php echo (isset($proportion) && !empty($proportion))?$proportion:0;  ?>; }
}

</script>
<style>
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 5px 8px;background: #EBEBEB;}
.dataTables_length, .dataTables_info { float: left;} .dataTables_paginate, .dataTables_filter { float: right;}
.dataTables_length, .dataTables_paginate { display: none;}

.Style1 {
	color: #990000;
	font-weight: bold;
}
</style>
</head>
<body>
<?php if(!isset($_GET['add'])) { ?>
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i>  <strong></strong><span class="Style18">
 </span><?php if(isset($cat) && $cat!="0") echo $libelle_modele."  ".$cat; else echo "Suivi des t&acirc;ches"; ?> </h4>
 </div>
<div class="widget-content">
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<=1) && !check_user_auth('page_edit',"suivi_indicateur_ptba.php")) { ?>
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">
<?php } ?>
<table border="0" cellspacing="0" class="table table-bordered  table-responsive datatable" align="center" id="mtable" >
    <?php $t=0;  if($totalRows_liste_zone>0) { ?>
    <tr class="titrecorps2">
  

  <td rowspan="2" width="25%"><div align="center" class="Style31"><strong>T&acirc;ches</strong></div></td>
    <td rowspan="2" width="8%"><div align="center"><strong>Poids (%) </strong></div></td>
    <td colspan="2" width="20%"><div align="center"><strong>Réalisation</strong></div></td>
    <td rowspan="2" nowrap="nowrap"><div align="center"><strong>Date de suivi </strong><br />
          <span class="help-block">(jj/mm/aaaa)</span></div></td>
    <td rowspan="2"><strong>Sources de v&eacute;rification</strong> </td>
    <td rowspan="2"><strong>Observation</strong></td>
   <!-- <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
    <td>Supr.</td>
    <?php } ?>-->
    </tr>
    <tr class="titrecorps2">
      <td width="10%"><div align="center"><strong>Validée</strong></div></td>
      <td nowrap="nowrap" width="10%"><div align="center"><strong>Lot</strong></div></td>
    </tr>
    <?php $p1="j"; $t=0;  $pp=$i=0;foreach($row_liste_zone as $row_liste_zone){   $i++;  ?>
    <tr <?php if(isset($suivi_array[$row_liste_zone['id_groupe_tache']]) && $suivi_array[$row_liste_zone['id_groupe_tache']]==1) echo 'bgcolor="#D2E2B1"'; ?>>


<td><div align="center" class="Style31">
  <div align="left"> <?php echo $row_liste_zone['code_tache'].": "; ?><?php echo $row_liste_zone['intitule_tache']; ?>
      <input name="id_groupe_tache[<?php echo $row_liste_zone['id_groupe_tache']; ?>]" type="hidden" size="5" value="<?php echo $row_liste_zone['id_groupe_tache']; ?>"/>
          <input name="proportion[<?php echo $row_liste_zone['id_groupe_tache']; ?>]" type="hidden" size="5" value="<?php echo $row_liste_zone['proportion']; ?>"/>
          <input name="ordre[<?php echo $row_liste_zone['id_groupe_tache']; ?>]" type="hidden" size="5" value="<?php echo $row_liste_zone['code_tache']; ?>"/>
          <input name="intitule_tache[<?php echo $row_liste_zone['id_groupe_tache']; ?>]" type="hidden" size="5" value="<?php echo $row_liste_zone['intitule_tache']; ?>"/>
   </div>
</div></td>

    <td><div align="center"><span class="Style31">
      <?php echo $row_liste_zone['proportion']; ?>
    </span></div></td>
    <td nowrap="nowrap"><div align="center">
      <input type="checkbox" name="valider[<?php echo $row_liste_zone['id_groupe_tache']; ?>]" id="valider_<?php echo $row_liste_zone['id_groupe_tache']; ?>" value="<?php echo $row_liste_zone['id_groupe_tache']; ?>" <?php echo (isset($suivi_array[$row_liste_zone['id_groupe_tache']]) && $suivi_array[$row_liste_zone['id_groupe_tache']]==1)?'checked="checked"':""; ?> />
    </div></td>
    <td nowrap="nowrap"><div align="center">
      <input class="required" type="text" style="text-align:center" name="n_lot[<?php echo $row_liste_zone['id_groupe_tache']; ?>]" id="n_lot_<?php echo $row_liste_zone['id_groupe_tache']; ?>"  value="<?php if(isset($lot_r_array[$row_liste_zone["id_groupe_tache"]]) && !empty($lot_r_array[$row_liste_zone["id_groupe_tache"]])) echo $lot_r_array[$row_liste_zone["id_groupe_tache"]]; else echo "1"; ?>" size="2" />
    /<?php if(isset($lot_p_array[$row_liste_zone["id_groupe_tache"]]) && !empty($lot_p_array[$row_liste_zone["id_groupe_tache"]])) echo $lot_p_array[$row_liste_zone["id_groupe_tache"]]; ?> </div></td>
    <td><input class="form-control datepicker"  type="text" name="date_reelle[<?php echo $row_liste_zone['id_groupe_tache']; ?>]" id="date_reelle_<?php echo $row_liste_zone["id_groupe_tache"] ?>" value="<?php if(isset($date_reelle_array[$row_liste_zone["id_groupe_tache"]])) echo implode('/',array_reverse(explode('-',$date_reelle_array[$row_liste_zone["id_groupe_tache"]]))); //else echo date("d/m/Y"); ?>" size="10"  /></td>
    <td><div align="center">
                    <?php if(isset($livrable_array[$row_liste_zone["id_groupe_tache"]]) && $livrable_array[$row_liste_zone["id_groupe_tache"]] && file_exists($dir.$livrable_array[$row_liste_zone["id_groupe_tache"]])) { $rep=$dir; $extension=substr(strrchr($livrable_array[$row_liste_zone["id_groupe_tache"]], '.')  ,1); if ($extension=="doc" || $extension=="docx") { echo("<a target='_blank' href='".$rep.$livrable_array[$row_liste_zone["id_groupe_tache"]]."'><img src='./images/doc.png' width='15'/> </a>"); } elseif ($extension=="xls" || $extension=="xlsx") { echo("<a target='_blank' href='".$rep.$livrable_array[$row_liste_zone["id_groupe_tache"]]."'><img src='./images/xls.png' width='15'/> </a>");} elseif ($extension=="pdf") { echo("<a target='_blank' href='".$rep.$livrable_array[$row_liste_zone["id_groupe_tache"]]."'><img src='./images/pdf.png' width='15'/> </a>");} elseif ($extension=="zip") { echo("<a target='_blank' href='".$rep.$livrable_array[$row_liste_zone["id_groupe_tache"]]."'><img src='./images/zipicon.png' width='15'/> </a>"); } else { echo("<a target='_blank' href='".$rep.$livrable_array[$row_liste_zone["id_groupe_tache"]]."'><img src='./images/view.png' width='15'/> </a>"); } echo "<br />"; } ?>
<?php //if(!isset($suivi_array[$i]['livrable']) || $suivi_array[$i]['valider']==1) { ?>
                    <input  type="file" name="fichier1[<?php echo $row_liste_zone['id_groupe_tache']; ?>]" id="fichier1_<?php echo $row_liste_zone["id_groupe_tache"] ?>" style="width:100px;" size="10" />
                    <input type="hidden" name="MAX_FILE_SIZE[<?php echo $row_liste_zone['id_groupe_tache']; ?>]" value="20485760"/>
<?php //} ?>
                  </div></td>
    <td><textarea class="form-control " cols="30" rows="1" type="text" name="observation[<?php echo $row_liste_zone['id_groupe_tache']; ?>]" id="observation_<?php echo $row_liste_zone['id_groupe_tache']; ?>"><?php if(isset($observation_array[$row_liste_zone['id_groupe_tache']])) echo $observation_array[$row_liste_zone['id_groupe_tache']]; ?></textarea>
	<?php if(isset($suivi_array[$row_liste_zone['id_groupe_tache']]) && $suivi_array[$row_liste_zone['id_groupe_tache']]==1) { ?><?php if(isset($row_liste_zone['n_lot']) && $row_liste_zone['n_lot']>1) $pp=($row_liste_zone['proportion']*$row_liste_zone['jalon']/$row_liste_zone['n_lot'])+$pp;?><?php } ?>	</td>
    <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
      <!-- <td align="center">
<?php //echo do_link("",$lien."&id_sup=".$row_liste_zone['id_groupe_tache'],"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette tâche ?');",0,"margin:0px 5px;","categorie_marche.php"); ?>
<a onClick="return confirm('Voulez vous vraiment suppimer cette etape ?');" href="<?php //echo $lien."&id_sup=".$row_liste_zone['id_etape'].""; ?>" title="Supprimer l'étape" ><img align="center" src='./images/delete.png' width='20' height='20' alt='Supprimer' style="margin:0px 5px 0px 0px;"></a></td>-->
      <?php } ?>
    </tr>

    <?php }  ?>
    <?php } else echo "<h3>Aucune tâche saisie</h3>" ;?>
	   <tr <?php if(isset($suivi_array[$row_liste_zone['id_groupe_tache']]) && $suivi_array[$row_liste_zone['id_groupe_tache']]==1) echo 'bgcolor="#D2E2B1"'; ?>>
      <td colspan="2"><div align="right" class="Style1">Niveau d'avancement  </div></td>
      <td><div align="center" class="Style1"><?php echo $proportion;//$pp; ?> %</div></td>
      <td colspan="4">&nbsp;</td>
      </tr>
  </table>
<?php
$color = "danger"; $tauxp=$proportion; if($tauxp<39) $color = "danger"; elseif($tauxp<69) $color = "warning"; elseif($tauxp>=70) $color = "success";
?>
<script>$("#stat_<?php echo $annee.$id_act;?>",window.parent.document).html('<div class="progress"> <div class="progress-bar progress-bar-<?php echo $color; ?>" style="width: <?php if($tauxp>0){ echo $tauxp; } else echo "100"; ?>%"><?php if($tauxp>0) echo $tauxp." %"; else echo "Non entam&eacute;e"; ?></div> </div>'); </script>
<?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<=1)) { ?>
<div class="form-actions">
<?php if(isset($_GET["id_act"])){ ?>
<input type="hidden" name="cat" value="<?php echo $cat; ?>" />
<input type="hidden" name="id_act" value="<?php echo $id_act; ?>" />
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
            <input class="form-control required" type="text" name="ordre" id="ordre" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_etape['ordre']; ?>" size="10" style="width: 90px;" onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_edit_etape['ordre']."'"; ?>) check_code('verif_code.php?t=type_tache&','w=ordre='+this.value+' and 	type_activite=<?php echo $cat; ?>','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" />
            <span class="help-block h0" id="code_zone_text">&nbsp;</span>          </div>
        </div>      </td>
      <td><div class="form-group">
          <label for="proportion" class="col-md-12 control-label">Proportion (%) <span class="required">*</span></label>
          <div class="col-md-3">
         <input name="proportion" type="text" class="form-control required" id="proportion" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_edit_etape['proportion'];?>" size="25" />
          </div>
        </div> </td>
    </tr>
<tr valign="top">
      <td colspan="2">
        <div class="form-group">
          <label for="intitule_tache" class="col-md-12 control-label">Intitulé de la tâche <span class="required">*</span></label>
          <div class="col-md-12">
            <textarea class="form-control required" cols="200" rows="2" type="text" name="intitule_tache" id="intitule_tache"><?php if(isset($_GET['id'])) echo $row_edit_etape['intitule_tache']; ?></textarea>
          </div>
        </div>      </td>
    </tr>
<tr valign="top">
  <td colspan="2">&nbsp;</td>
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