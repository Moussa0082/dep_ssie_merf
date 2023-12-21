<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & D�veloppement: BAMASOFT */
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


if(isset($_GET['id_ind'])) $id_ind = $_GET['id_ind']; else $id_ind=0;// || $_GET['ad_sta'];

if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else $annee=date("Y");

//if(isset($_GET['id_act'])) { $id_act = $_GET['id_act']; }

if(isset($_GET['id_act'])) { $id_act =$code_act = $_GET['id_act']; }

$page1="";

if(isset($_GET['cmp'])) $cmp = $_GET["cmp"];

$dir = './attachment/ptba/';
if(!is_dir($dir)) mkdir($dir);
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

if ((isset($_GET["id_sup_mission"]) && !empty($_GET["id_sup_mission"]))) {
  $id = ($_GET["id_sup_mission"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."decaissement_activite WHERE id_decaissement=%s",
                       GetSQLValueString($id, "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
    $insertGoTo = $_SERVER['PHP_SELF']."?id_act=$id_act&code_act=$code_act&annee=$annee";
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}
  
  // echo "je suis la";   exit;
                                         
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{ //Fonction
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."decaissement_activite (annee_act, id_activite, source_financement, commune,  date_collecte, statut, cout_realise, numero_facture, projet, date_enregistrement, id_personnel) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, '$date', '$personnel')",
                         GetSQLValueString($annee, "int"),
					     GetSQLValueString($id_act, "text"),
						 GetSQLValueString($_POST['source_financement'], "text"),
						 GetSQLValueString($_POST['commune'], "text"),
                        GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_validation']))), "date"),
                         GetSQLValueString($_POST['statut'], "text"),
                         GetSQLValueString($_POST["cout_realise"], "int"),
						GetSQLValueString($_POST["numero_facture"], "text"),
                         GetSQLValueString($_SESSION["clp_projet"], "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
      $insertGoTo = $_SERVER['PHP_SELF']."?id_act=$id_act&code_act=$code_act&annee=$annee";
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."decaissement_activite WHERE id_decaissement=%s",
                         GetSQLValueString($id, "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
      $insertGoTo = $_SERVER['PHP_SELF']."?id_act=$id_act&code_act=$code_act&annee=$annee";
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
    $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."decaissement_activite SET source_financement=%s, commune=%s, date_collecte=%s, statut=%s, cout_realise=%s, numero_facture=%s, etat='MODIFIE', modifier_par='$personnel', modifier_le='$date'  WHERE id_decaissement=%s",
                          GetSQLValueString($_POST['source_financement'], "text"),
						 GetSQLValueString($_POST['commune'], "text"),
                         GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_validation']))), "date"),
                         GetSQLValueString($_POST['statut'], "text"),
                         GetSQLValueString($_POST["cout_realise"], "int"),
						GetSQLValueString($_POST["numero_facture"], "text"),
                         GetSQLValueString($id, "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
  
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF']."?id_act=$id_act&code_act=$code_act&annee=$annee";
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
/**/
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form4"))
{ //Upload file
  if ((isset($_FILES['fichier']['name'])) && count($_FILES['fichier']['name'])>0 && isset($_POST["id"])) {
    $ext_autorisees=array('docx','doc','pdf', 'xls', 'xlsx', 'jpeg', 'jpg', 'zip', 'rar'); //Extensions autoris�es
    $url_site = $dir;//'./attachment/dano/';
    $Result1 = false; $link = array();
    $id = ($_POST["id"]);
    for($i=0;$i<count($_FILES['fichier']['name']);$i++)
    {
      $ext = substr(strrchr($_FILES['fichier']['name'][$i], "."), 1);
      if(in_array($ext,$ext_autorisees))
      {
        $Result1 = move_uploaded_file($_FILES['fichier']['tmp_name'][$i],
        $url_site.$_FILES['fichier']['name'][$i]);
        if($Result1) array_push($link,$url_site.$_FILES['fichier']['name'][$i]);
      }
    }
    if($Result1){
    mysql_query_ruche("DOC".implode('|',$link), $pdar_connexion,1);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."decaissement_activite SET document=".GetSQLValueString(implode('|',$link), "text").", etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_decaissement='$id'");

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
      $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF']."?id_act=$id_act&code_act=$code_act&annee=$annee";
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  else
  {
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF']."?id_act=$id_act&code_act=$code_act&annee=$annee";
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
else
{
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF']."?id_act=$id_act&code_act=$code_act&annee=$annee";
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo));  exit();
}
}

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]);     //and projet='".$_SESSION["clp_projet"]."'
  $query_liste_mission = "SELECT * FROM ".$database_connect_prefix."decaissement_activite WHERE id_decaissement='$id'  ";
    try{
    $liste_mission = $pdar_connexion->prepare($query_liste_mission);
    $liste_mission->execute();
    $row_liste_mission = $liste_mission ->fetch();
    $totalRows_liste_mission = $liste_mission->rowCount();
	}catch(Exception $e){ die(mysql_error_show_message($e)); }
}
else
{                  //where projet='".$_SESSION["clp_projet"]."'
  //Mission supervision
  $query_liste_mission = "SELECT * FROM ".$database_connect_prefix."decaissement_activite WHERE id_activite='$id_act' and annee_act=$annee order by date_collecte desc, commune desc ";
  try{
    $liste_mission1 = $pdar_connexion->prepare($query_liste_mission);
    $liste_mission1->execute();
    $row_liste_mission1 = $liste_mission1 ->fetchAll();
    $totalRows_liste_mission1 = $liste_mission1->rowCount();
	}catch(Exception $e){ die(mysql_error_show_message($e)); }
}

// Exemple de requête pour récupérer les partenaires depuis la table "partenaire"
$query_partenaires = "SELECT id_partenaire, sigle FROM partenaire";
$result_partenaires = $pdar_connexion->query($query_partenaires);
// Vérifiez si la requête a réussi
if ($result_partenaires) {
    $row_liste_partenaires = $result_partenaires->fetchAll(PDO::FETCH_ASSOC);
}

$query_liste_departement = "SELECT code_ugl as code_departement, abrege_ugl as nom_departement FROM ".$database_connect_prefix."ugl  ORDER BY code_ugl asc";
// $query_liste_baillers = "SELECT code as cd, nom as nm FROM ".$database_connect_prefix."partenaire  ORDER BY code asc";
try{
    $liste_departement = $pdar_connexion->prepare($query_liste_departement);
    // $liste_bailleurs = $pdar_connexion->prepare($query_liste_baillers);
    $liste_departement->execute();
    // $liste_bailleurs->execute();
    $row_liste_departement = $liste_departement ->fetchAll();
    // $row_liste_bailleurs = $liste_bailleurs ->fetchAll();
    $totalRows_liste_departement = $liste_departement->rowCount();
    // $totalRows_liste_bailleurs = $liste_bailleurs->rowCount();
	}catch(Exception $e){ die(mysql_error_show_message($e)); }
$departement_array = array();
// $bailleurs_array = array();
 if($totalRows_liste_departement>0) { 
foreach($row_liste_departement as $row_liste_departement1){ 
  $departement_array[$row_liste_departement1["code_departement"]] = $row_liste_departement1["nom_departement"];
}
}
/**/
//$query_src_financement = "SELECT montant, observation, type_part FROM ".$database_connect_prefix."part_bailleur where activite=$id_act  ORDER BY type_part asc";
//$query_src_financement = "select * FROM ".$database_connect_prefix."ptba where code_activite_ptba!='$code_act' and code_activite_ptba like '$code_act%' and annee=$annee and projet='".$_SESSION["clp_projet"]."' ORDER BY code_activite_ptba ASC";
$query_src_financement = "SELECT SUM( if(montant>0, montant,0) ) AS montant  FROM part_bailleur where  activite=$id_act";

try{
    $src_financement = $pdar_connexion->prepare($query_src_financement);
    $src_financement->execute();
    $row_src_financement = $src_financement ->fetch();
    $totalRows_src_financement = $src_financement->rowCount();
	}catch(Exception $e){ die(mysql_error_show_message($e)); }
$financement_total=$financement_maep = 0;
 if($totalRows_src_financement>0) { 
/*foreach($row_src_financement as $row_src_financement1){  */
  $financement_total = $financement_total+$row_src_financement["montant"];
  $financement_maep = $financement_maep+doubleval($row_src_financement["montant"]);
   } //}

   // Fonction pour générer le matricule
   function generateMatricule() {
    // Obtenez l'année actuelle
    $currentYear = date("Y");
    $month = date("m");
    $day = date("d");
    //  initialisation de la variable de session à 1
    // $_SESSION['index'] = 1;
    // // Incrémentez l'index stocké en session
    // $_SESSION['index'] = isset($_SESSION['index']) ? ($_SESSION['index']+1) : $_SESSION['index'] = 1;
    // // $inc  = $_SESSION['index'];
    if (!isset($_SESSION['index'])) {
    $_SESSION['index'] = 1;
} else {
    // Incrémentez l'index stocké en session
    $_SESSION['index']++;
}
    // Concaténez les éléments pour former le matricule (DM + année + mois + jour + index)
    $matricule =  $currentYear . $month . $day . $_SESSION['index'];
    
    // Retournez le matricule généré
    return $matricule;
  }

  $matricule =  generateMatricule();

?>
<meta name="viewport" content="width=400, initial-scale=1.0"><head>
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
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>

<script type="text/javascript">

$(document).ready(function() {

  $(".modal-dialog", window.parent.document).width(700);

  get_content('suivi_indicateur_ptba_reload.php','<?php echo "id=$id_act&ug=$ug&idcl=$idcl&cp=$cp"; ?>','acharger<?php echo $id_act; ?>','','',1);

<?php if(isset($_GET['add'])) { ?>

        $("#ui-datepicker-div").remove();

        $(".modal-dialog", window.parent.document).width(600);

        $(".select2-select-00").select2({allowClear:true});
		 $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});

<?php } ?>

  });

</script>


<style>
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
#mtable.table>thead>tr>th,.table>thead>tr>td {padding: 2px 8px;background: #EBEBEB;}

@media(min-width:558px){.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}
</style>
</head>
<?php if(!isset($_GET['add']) && !isset($_GET["document"])) { ?>
<div>
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> Suivi du d&eacute;caissement</h4>
   <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==0){ ?>
<?php echo do_link("",$_SERVER['PHP_SELF']."?id_act=$id_act&code_act=$code_act&annee=$annee&add=1","Nouveau d&eacute;caissement","<i class='icon-plus'> Nouveau d&eacute;caissement </i>","simple","./","pull-right p11","",0,"","plan_ptba.php"); ?>
<?php } ?>
</div>
<div class="widget-content">
<table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable" align="center" id="mtable" >
            <thead>
                <tr>
                  <td><div align="left"><strong>Source de financement </strong></div></td>
                  <td><div align="left"><strong>Site</strong></div></td>
                  <!--<td rowspan="2"><div align="left"><strong>Resum&eacute;</strong></div></td>-->
                  <td><div align="center"><strong>Date</strong></div></td>
                  <td><strong>D&eacute;caiss&eacute; </br>(F CFA) </strong></td>
                  <td><strong>Engag&eacute; </br>(F CFA) </strong></td>
                  <td><div align="left"><strong>Documents</strong></div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==0)) { ?>
                  <td align="center" width="90" ><strong>Acti ons</strong></td>
                  <?php } ?>
                </tr>
            </thead>
                <?php $totaldecmaep=$totaldec=0; if($totalRows_liste_mission1>0) {$i=0;  foreach($row_liste_mission1 as $row_liste_mission1){  $id = $row_liste_mission1['id_decaissement']; ?>
                <tr >
                  <td ><div align="center"><?php echo $row_liste_mission1['source_financement']; ?></div></td>
                  <td><div align="left"><?php if(isset( $departement_array[$row_liste_mission1['commune']])) echo  $departement_array[$row_liste_mission1['commune']]; ?></div></td>
                  <td><div align="left"><?php echo date_reg($row_liste_mission1['date_collecte'],"/"); ?></div></td>
                  <td nowrap="nowrap"><div align="right"><?php if($row_liste_mission1['statut']==0) {
                    echo number_format($row_liste_mission1['cout_realise'], 0, ',', ' ');  //number_format pour mettre des espaces entre les nombre ex: 0 : 1 000 000
                    $totaldec=$totaldec+$row_liste_mission1['cout_realise'];
                    //  $totaldecmaep=$totaldecmaep+$row_liste_mission1['cout_maep'];
                    if(isset($row_liste_mission1['cout_maep'])) {
                      $totaldecmaep = $totaldecmaep + $row_liste_mission1['cout_maep'];
                  }
                     } ?></div></td>
                  
                  <td nowrap="nowrap"><div align="right">
                    <?php if($row_liste_mission1['statut']!=0) echo number_format($row_liste_mission1['cout_realise'], 0, ',', ' '); ?>
                  </div></td>
                  <td><div align="left">
<?php $titre = "Ajouter"; $titre1 = "Ajout"; if(isset($row_liste_mission1["document"]) && !empty($row_liste_mission1["document"])){ $a = explode("|",$row_liste_mission1["document"]); $j=1; foreach($a as $file){ if(file_exists($file)){ $name = substr(strrchr($file, "/"), 1); echo "<a href='./download_file.php?file=".$file."' title='".$name."' style='display:block;' >Fichier ".$j."</a>"; $j++; } } $titre = "Modifier"; $titre1 = "Modification"; } ?>
<div align="center">
<?php
//echo do_link("",$_SERVER['PHP_SELF']."?id=$id&document=1","$titre1 de document de mission","$titre","simple","./","","",0,"","mission_supervision.php");
echo do_link("","","$titre1 de document de PTBA","$titre","simple","./","","get_content('suivi_decaissement_ptba.php','id_act=$id_act&code_act=$code_act&annee=$annee&id=$id&document=1','modal-body_add',this.title);",1,"",'plan_ptba.php');
?>
                  </div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==0)) { ?>
<td align="center" nowrap="nowrap" class=" ">
<?php
echo do_link("",$_SERVER['PHP_SELF']."?id_act=$id_act&code_act=$code_act&annee=$annee&id=$id&add=1","Modifier dé caissement PTBA ".$id,"","edit","./","","",1,"margin:0px 5px;",'suivi_decaissement_ptba.php');

echo do_link("",$_SERVER['PHP_SELF']."?id_act=$id_act&code_act=$code_act&annee=$annee&id_sup_mission=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette d&eacute;pense ?');",0,"margin:0px 5px;",'plan_ptba.php');
?></td>
<?php } ?>
	    </tr>
                <?php }  ?>
	
                <?php }else{ ?>
                 <tr> <td colspan="7"><h4 align="center">Aucun d&eacute;caissement effectu&eacute; !</h4></td>  </tr>
                <?php } ?>
							 <tr> <td colspan="6"><td/>  </tr>
				  <tr> <td colspan="3"><div align="right"><strong>Total d&eacute;caiss&eacute;&nbsp;&nbsp; </strong></div></td>  
				    <td nowrap="nowrap"><div align="right"><?php echo number_format($totaldec, 0, ',', ' '); ?></div></td>
				    <td colspan="2" bgcolor="#F0F0F0"><div align="right"><strong>&nbsp;&nbsp; </strong></div></td>
				    <td nowrap="nowrap" bgcolor="#F0F0F0"><div align="center"></div></td>
				  </tr>
				  <tr>
				    <td><div align="right"><strong>Total Pr&eacute;vu </strong></div></td>
				    <td nowrap="nowrap"><div align="center"><?php echo number_format($financement_total, 0, ',', ' '); ?></div></td>
				    <td nowrap="nowrap"><strong>Taux:</strong> <strong class="label-info" style="color:#FFFFFF">&nbsp;&nbsp;<?php if($financement_total>0) echo number_format(100*$totaldec/$financement_total, 2, ',', ' ')." %"; ?>&nbsp;&nbsp;</strong></td>
				    <td colspan="3" nowrap="nowrap">&nbsp;</td>
				    <td nowrap="nowrap" bgcolor="#F0F0F0">&nbsp;</td>
				  </tr>
              </table>

</div></div>
</div>
<?php include 'modal_add.php'; ?>
<?php } elseif(isset($_GET["document"])) { //Transfert de fichier ?>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification":"Nouvelle"; ?></h4>
<a href="<?php echo $_SERVER['PHP_SELF']."?id_act=$id_act&code_act=$code_act&annee=$annee"; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form4" id="form3" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
    <tr>
      <td valign="middle">
        <div class="form-group">
          <label for="fichier" class="col-md-5 control-label">Documents <span class="required">*</span></label>
          <div class="col-md-6">
            <input class="form-control required" type="file" name="fichier[]" id="fichier" value="" size="32" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/pdf,application/vnd.ms-word,image/jpeg,.doc,.docx,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed" multiple />
          </div>
        </div>
      </td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo ($_GET["id"]); ?>" />
<?php } ?>
<?php if(isset($_GET["annee"])){ ?>
  <input type="hidden" name="annee" value="<?php echo intval($_GET["annee"]); ?>" />
<?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
<?php if(!isset($_GET['add2'])) { ?>
<a href="<?php echo $_SERVER['PHP_SELF']."?id_act=$id_act&code_act=$code_act&annee=$annee"; ?>" class="btn pull-right" title="Annuler" >Annuler</a>
<?php } ?>
  <input name="MM_insert" type="hidden" value="MM_insert" size="32" alt="">
<input name="MM_form" id="MM_form" type="hidden" value="form4" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } } else{ ?>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification décaissement ":"Nouveau décaissement"; ?></h4>
<a href="<?php echo $_SERVER['PHP_SELF']."?id_act=$id_act&code_act=$code_act&annee=$annee"; ?>" class="pull-right p11" title="Annuler" >Annuler </a>
</div>
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form3" id="form3" novalidate="novalidate">
<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">
     
    <!-- test  -->
    <tr valign="top">
    <td>
        <div class="form-group">
            <label for="partenaire" class="col-md-3 control-label">Partenaire <span class="required">*</span></label>
            <div class="col-md-6">
              <!-- Utilisez la liste des partenaires dans le formulaire -->
<select name="source_financement" id="source_financement" style="width: 200px;">
    <option value=""> </option>
    <?php 
        // Vérifiez si $row_liste_partenaires est défini
        if (isset($row_liste_partenaires) && is_array($row_liste_partenaires)) {
            foreach ($row_liste_partenaires as $partenaire) {
                if (isset($_POST['source_financement']) && $partenaire['source_financement'] == $_POST['source_financement']) {
                    echo '<option value="' . $partenaire['source_financement'] . '" selected>' . $partenaire['sigle'] . '</option>';
                } else {
                    echo '<option value="' . $partenaire['sigle'] . '">' . $partenaire['sigle'] . '</option>';
                }
            }
        }
    ?>
</select>
            </div>
        </div>
    </td>
</tr>
    <!-- test fin  -->
    <!-- ancien -->
    <tr valign="top">
      <td>
        <div class="form-group">
			  <label for="type" class="col-md-3 control-label">Site <span class="required">*</span></label>
          <div class="col-md-6">
		              <select name="commune" id="commune" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez un site">

            <option></option>

          <?php foreach($row_liste_departement as $row_liste_departement){ ?>

  <option value="<?php echo $row_liste_departement['code_departement']?>" <?php if(isset($_GET["id"]) && $row_liste_departement['code_departement']==$row_liste_mission['commune']) echo 'SELECTED="selected"'; ?> ><?php echo $row_liste_departement['nom_departement']?></option>

    <?php }  ?>
    </select>
          </div>
        </div>     
       </td>
    </tr>

    <tr valign="top">
      <td>
        <div class="form-group">
          <label for="date_validation" class="col-md-3 control-label">Date de décaissement <span class="required">*</span></label>
          <div class="col-md-3">
            <input class="form-control datepicker required" type="text" name="date_validation" id="date_validation" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo implode('/',array_reverse(explode('-',$row_liste_mission['date_collecte']))); else echo date("d/m/Y"); ?>" size="32" />
          </div>
        </div>      </td>
    </tr>
    <tr valign="top">
      <td><div class="form-group">
          <label for="cout_realise" class="col-md-3 control-label">Montant total <span class="required">*</span></label>
          <div class="col-md-3">
<input name="cout_realise" type="text" class="form-control required" id="cout_realise" value="<?php echo isset($row_liste_mission['cout_realise'])?$row_liste_mission['cout_realise']:""; ?>" />
          </div>
        </div>  </td>
    </tr>
     <tr valign="top">
      <td><div class="form-group">
          <label for="numero_facture" class="col-md-3 control-label">Référence de l'Opération <span class="required">*</span></label>
          <div class="col-md-9">
<input name="numero_facture" type="text" class="form-control required" id="numero_facture" value="<?php echo isset($row_liste_mission['numero_facture'])?$row_liste_mission['numero_facture']: $matricule; ?>" />
          </div>
        </div>  </td>
    </tr>
     <tr valign="top">
      <td bgcolor="#FFCC33">
        <div class="form-group">
			  <label for="statut" class="col-md-3 control-label">Type de l'op&eacute;ration <span class="required">*</span></label>
          <div class="col-md-3">
            <select name="statut" id="statut" class="form-control required" >
              <option value="">Selectionnez</option>
              <option value="0" <?php if(isset($_GET['id']) && $row_liste_mission['statut']=="0") echo 'selected="selected"'; ?>>Réalisé</option>
              <option value="1" <?php if(isset($_GET['id']) && $row_liste_mission['statut']=="1") echo 'selected="selected"'; ?>>Engagé</option>
            </select>
          </div>
        </div>  
          </td>
    </tr>
</table>
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo ($_GET["id"]); ?>" />
<?php } ?>
<?php if(isset($_GET["annee"])){ ?>
  <input type="hidden" name="annee" value="<?php echo intval($_GET["annee"]); ?>" />
<?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />
<?php if(!isset($_GET['add2'])) { ?>
<a href="<?php echo $_SERVER['PHP_SELF']."?id_act=$id_act&code_act=$code_act&annee=$annee"; ?>" class="btn pull-right" title="Annuler" >Annuler</a>
<?php } ?>
  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(!isset($_GET['add2']) && isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2)) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette DEPENSE ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form3" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>
<?php } ?>
<script type="text/javascript">

$(document).ready(function() {

<?php // ?>
<?php
 if($financement_total>0) {$taux_progressc= 100*$totaldec/$financement_total;} else {$taux_progressc=0; }
if (isset($taux_progressc) && $taux_progressc>0 && $taux_progressc<=100) $percentc = $taux_progressc;
elseif (isset($taux_progressc) && $taux_progressc>100) $taux_progressc=$percentc = 100;
else $percentc = 100;

$taux_progressc;
if($taux_progressc<39) $color = "danger";
elseif($taux_progressc<69) $color = "warning";
elseif($taux_progressc>=70) $color = "success";
?>

        // reload parent frame

        $(".close", window.parent.document).click(function(){
            $("#label1c_<?php echo $id_act; ?>", window.parent.document).html('<div class="progress"> <div class="progress-bar progress-bar-<?php echo $color; ?>" style="width:<?php echo number_format($percentc, 0, ',', ' '); ?>%"><?php echo (((isset($taux_progressc) && $taux_progressc>0))?number_format($taux_progressc, 0, ',', ' ')." %":"Suivre"); ?></div> </div>');
        });

        $("button[data-dismiss='modal']", window.parent.document).click(function(){
            $("#label1c_<?php echo $id_act; ?>", window.parent.document).html('<div class="progress"> <div class="progress-bar progress-bar-<?php echo $color; ?>" style="width:<?php echo number_format($percentc, 0, ',', ' '); ?>%"><?php echo (((isset($taux_progressc) && $taux_progressc>0))?number_format($taux_progressc, 0, ',', ' ')." %":"Suivre"); ?></div> </div>');
        });
<?php //} ?>
  });
</script>