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

header('Content-Type: text/html; charset=UTF-8');



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

//$idms=$_GET['idms'];$rec=$_GET['rec'];
$annee=$_GET['annee'];
if(isset($_GET["id_mar"])) { $id=$_GET["id_mar"];} else $id=0;
if(isset($_GET["code_act"])) { $code_act=$_GET["code_act"];} else $code_act=0;
$dir = './attachment/ppm/';
//insertion des plans



  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
/*$date_en_cours=new DateTime(date("Y-m-d"));
$date_en_cours=$date_en_cours->format('Ymd');
$last_date_post=new DateTime(implode('-',array_reverse(explode('/',$_POST['last_date_suivi']))));
$last_date_post=$last_date_post->format('Ymd');
$date_post=new DateTime(implode('-',array_reverse(explode('/',$_POST['date_reelle']))));
$date_post=$date_post->format('Ymd');

if($_POST['last_date_suivi']!="" && $last_date_post<$date_post && $_POST['oetape']<$_POST['last_etape_suivi']) {
//echo "je suis la"; exit;
} elseif(!is_null($_POST['date_reelle']) && $_POST['date_reelle']!="" && $date_post<=$date_en_cours) {*/
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
 // if($_POST['proportion']>$_POST['tmax']) $_POST['proportion']=$_POST['tmax'];

//mysql_select_db($database_pdar_connexion, $pdar_connexion); //etape='".$_POST["etape"]."' and
$query_sup_loc= "DELETE FROM ".$database_connect_prefix."suivi_plan_marche WHERE  marche='".$_POST["marche"]."'";
  	    try{
    $Result1 = $pdar_connexion->prepare($query_sup_loc);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
//if(is_null($_POST['date_reelle']) || $_POST['date_reelle']=="") $_POST['date_reelle']=date("Y-m-d");
//elseif($_POST['last_date_suivi']!="" && $date_post<$last_date_post && $_POST['last_etape_suivi']<$_POST['oetape']) $_POST['date_reelle']=$_POST['last_date_suivi'];
$id_etape = $_POST['etape']; $marche = $_POST['marche']; $date_reelle = $_POST['date_reelle'];
foreach ($id_etape as $key => $value)
{
  //if(!empty($date_reelle[$key])){
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."suivi_plan_marche (marche, etape, date_reelle, id_personnel, date_enregistrement) VALUES (%s, %s, %s,'$personnel', '$date')",

                       GetSQLValueString($_POST['marche'], "int"),
					    GetSQLValueString($value, "int"),
						// GetSQLValueString($annee, "text"),
                       GetSQLValueString(implode('-',array_reverse(explode('/',$date_reelle[$key]))), "date"));

	  	    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    //}
}

//suivi marché
if(intval($_POST['montant_usd'])>0 && !empty($_POST['date_validation'])){
$query_edit_marche = "SELECT * FROM ".$database_connect_prefix."suivi_montant_marche WHERE marche='".$_POST["marche"]."'";
       try{
    $edit_marche = $pdar_connexion->prepare($query_edit_marche);
    $edit_marche->execute();
   // $row_liste_zone = $edit_marche ->fetchAll();
    $totalRows_edit_marche = $edit_marche->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$a = ($totalRows_edit_marche>0)?1:0;

$fichier = "";
//Upload file
  if ((isset($_FILES['fichier']['name'])) && count($_FILES['fichier']['name'])>0) {
    $ext_autorisees=array('docx','doc','pdf', 'xls', 'xlsx', 'jpeg', 'jpg', 'png', 'gif', 'zip', 'rar'); //Extensions autorisées
    $url_site = $dir;
    $Result = false; $link = array();
    for($i=0;$i<count($_FILES['fichier']['name']);$i++)
    {
      $ext = strtolower(substr(strrchr($_FILES['fichier']['name'][$i], "."), 1));
      if(in_array($ext,$ext_autorisees))
      {
        $Result = move_uploaded_file($_FILES['fichier']['tmp_name'][$i],
        $url_site.$_FILES['fichier']['name'][$i]);
        if($Result) array_push($link,$_FILES['fichier']['name'][$i]);
      }
    }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result) $fichier .= implode('|',$link); //else $insertGoTo .= "?insert=no";
  }
  

  $insertSQL = sprintf(($a==0)?"INSERT INTO ".$database_connect_prefix."suivi_montant_marche (marche, montant_usd, proces_verbal, date_validation, etat, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s,'$personnel', '$date')":"UPDATE ".$database_connect_prefix."suivi_montant_marche SET marche=%s, montant_usd=%s, proces_verbal=%s, date_validation=%s, etat=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE marche='$id'",

                       GetSQLValueString($_POST['marche'], "int"),
                       GetSQLValueString($_POST['montant_usd'], "double"),
                       GetSQLValueString($fichier, "text"),
                       GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_validation']))), "date"),
                       GetSQLValueString($_POST['observation'], "text"));


		    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
}
    $insertGoTo = $_SERVER['PHP_SELF'];

    if ($Result1) $insertGoTo .= "?id_mar=$id&annee=$annee&insert=ok";
    else $insertGoTo .= "?id_mar=$id&annee=$annee&insert=no";

    header(sprintf("Location: %s", $insertGoTo)); exit();
}
//}
$query_edit_marche = "SELECT * FROM ".$database_connect_prefix."plan_marche WHERE id_marche='$id'";
       try{
    $edit_marche = $pdar_connexion->prepare($query_edit_marche);
    $edit_marche->execute();
    $row_edit_marche = $edit_marche ->fetch();
    $totalRows_edit_marche = $edit_marche->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$meth=$row_edit_marche['methode'];
$modele=$row_edit_marche['modele_marche'];

$query_edit_suivi_marche = "SELECT * FROM ".$database_connect_prefix."suivi_montant_marche WHERE marche='$id'";
       try{
    $edit_suivi_marche = $pdar_connexion->prepare($query_edit_suivi_marche);
    $edit_suivi_marche->execute();
    $row_edit_suivi_marche = $edit_suivi_marche ->fetch();
    $totalRows_edit_suivi_marche = $edit_suivi_marche->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_modele = "SELECT * FROM ".$database_connect_prefix."modele_marche ORDER BY code asc";
	           try{
    $liste_modele = $pdar_connexion->prepare($query_liste_modele);
    $liste_modele->execute();
    $row_liste_modele = $liste_modele ->fetchAll();
    $totalRows_liste_modele = $liste_modele->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$modele_array = array();
if($totalRows_liste_modele>0) { foreach($row_liste_modele as $row_liste_modele){  
	 $modele_array[$row_liste_modele["id_modele"]]=$row_liste_modele['categorie']." - ".$row_liste_modele['methode_concerne']." - ".$row_liste_modele['examen']; 
}  }


//$query_liste_etape_plan = "SELECT ".$database_connect_prefix."etape_marche.* FROM ".$database_connect_prefix."etape_marche where ".$database_connect_prefix."etape_marche.modele_concerne='$modele'  ORDER BY ".$database_connect_prefix."etape_marche.code asc";
$query_liste_etape_plan = "SELECT marche, idetape as id_etape, code_etape as code, intitule_etape as intitule, duree_prevue as duree FROM ".$database_connect_prefix."etape_plan_marche where 	marche=$id ORDER BY code_etape asc";
       try{
    $liste_etape_plan = $pdar_connexion->prepare($query_liste_etape_plan);
    $liste_etape_plan->execute();
    $row_liste_etape_plan = $liste_etape_plan ->fetchAll();
    $totalRows_liste_etape_plan = $liste_etape_plan->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$dst=0;
$last_date_suivi="";
$last_etape_suivi="";
$query_liste_date_etape = "SELECT ".$database_connect_prefix."suivi_plan_marche.* FROM ".$database_connect_prefix."suivi_plan_marche, ".$database_connect_prefix."etape_plan_marche where  idetape=etape and suivi_plan_marche.marche='$id' order by code_etape";
	           try{
    $liste_date_etape = $pdar_connexion->prepare($query_liste_date_etape);
    $liste_date_etape->execute();
    $row_liste_date_etape = $liste_date_etape ->fetchAll();
    $totalRows_liste_date_etape = $liste_date_etape->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$etape_array = array();
if($totalRows_liste_date_etape>0) { foreach($row_liste_date_etape as $row_liste_date_etape){  
	 $etape_array[$row_liste_date_etape["etape"]]=$row_liste_date_etape['date_reelle']; $last_date_suivi=$row_liste_date_etape['date_reelle']; $last_etape_suivi=$row_liste_date_etape['etape'];

 }  }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->

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
        $(".modal-dialog", window.parent.document).width(800);

		$(".row-border").validate();

        $("#ui-datepicker-div").remove();

        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});

	});

</script>
<style>.p11{
  padding: 2px 5px!important;
}
</style>


</head>

<body>

<?php if(!isset($_GET["show"])){ ?>

<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i><strong><strong><span class="Style14">

<?php if (isset ($row_edit_marche['categorie'])) echo "Type: ".$row_edit_marche['categorie']. " &nbsp;&nbsp;&nbsp;M&eacute;thode: " . $row_edit_marche['methode'];?>

</span></strong></strong></h4>

  <div class="toolbar no-padding"><?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1) && isset($tolp) && $tolp<100) {?>

<a href="<?php echo $_SERVER['PHP_SELF']."?rec=$rec&annee=".$annee."&idms=$idms&show=1"; ?>" title="Ajout de recommandation" class="pull-right p11"><i class="icon-plus"> Ajouter </i></a><?php } ?>

</div></div>



<div class="widget-content">
<form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3" enctype="multipart/form-data">
<table width="100%">
  <tr>
    <td width="70%" valign="top">
<div>

<u>Intitul&eacute;</u>:<span class="Style14"><strong>

<?php if (isset ($row_edit_marche['intitule'])) echo $row_edit_marche['intitule'];?>

</strong></span><br />

<u>Date d&eacute;but pr&eacute;vue </u>:

<strong><?php if (isset ($row_edit_marche['date_prevue'])) echo date("d/m/y", strtotime($row_edit_marche['date_prevue']));?></strong>

<br />
<u>Co&ucirc;t pr&eacute;vu (USD)</u>:

<strong>
<?php if (isset ($row_edit_marche['montant_usd'])) echo  number_format($row_edit_marche['montant_usd'], 0, ',', ' ');?>
</strong>
<br />
<u>Mod&egrave;le</u>:

<strong>
<?php if (isset ($modele_array[$row_edit_marche['modele_marche']])) echo $modele_array[$row_edit_marche['modele_marche']];?>
</strong></div>
    </td>
    <td valign="top" width="30%">
<!--<fieldset><legend>Info march&eacute;</legend>-->
<div class="form-group">
          <label for="montant_usd" class="col-md-3 control-label pull-left">Montant (F CFA): </label>
          <label for="date_validation" class="col-md-3 control-label pull-right">Date fin: </label>
          <div class="col-md-3">
            <input style="width: 80px" class="form-control pull-left" name="montant_usd" id="montant_usd" type="text" value="<?php if(isset($row_edit_suivi_marche['montant_usd'])) { if($row_edit_suivi_marche['montant_usd']>0) echo $row_edit_suivi_marche['montant_usd']; } ?>" size="10" />
<input   class="form-control datepicker required pull-right" type="text" name="date_validation" id="date_validation" value="<?php if(isset($row_edit_suivi_marche['date_validation'])) echo implode('/',array_reverse(explode('-',$row_edit_suivi_marche['date_validation']))); ?>" style="text-align:center; width: 80px" size="32"/>
          </div>
</div>
        <label for="observation" class="col-md-3 control-label">Observations </label>
          <div class="col-md-9">
            <textarea class="form-control" id="observation" name="observation" cols="25" rows="1"><?php if(isset($row_edit_suivi_marche['etat'])) echo $row_edit_suivi_marche['etat'];?></textarea>
          </div>
        <label for="fichier" class="col-md-12 control-label">Fichier &agrave; uploader
<?php if(isset($row_edit_suivi_marche["proces_verbal"]) && !empty($row_edit_suivi_marche["proces_verbal"])){ $file=explode("|",$row_edit_suivi_marche["proces_verbal"]); echo "<div>"; foreach($file as $fichier){ $extension = strtolower(substr(strrchr($fichier, "."), 1)); if(file_exists($dir.$fichier)) { if ($extension=="doc" || $extension=="docx") { echo("<a class='pull-left p11' target='_blank' href='".$dir.$fichier."'><img src='./images/doc.png' width='15'/> </a>"); } elseif ($extension=="xls" || $extension=="xlsx") { echo("<a class='pull-left p11' target='_blank' href='".$dir.$fichier."'><img src='./images/xls.png' width='15'/> </a>");} elseif ($extension=="pdf") { echo("<a class='pull-left p11' target='_blank' href='".$dir.$fichier."'><img src='./images/pdf.png' width='15'/> </a>");} elseif ($extension=="zip") { echo("<a class='pull-left p11' target='_blank' href='".$dir.$fichier."'><img src='./images/zipicon.png' width='15'/> </a>"); } else { echo("<a class='pull-left p11' target='_blank' href='".$dir.$fichier."'><img src='./images/file.png' width='15'/> </a>"); } } } echo "</div>"; } ?>
        </label>
          <div class="col-md-9">
            <input class="form-control" type="file" name="fichier[]" id="fichier" value="" size="32" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/pdf,application/vnd.ms-word,image/jpeg,.doc,.docx,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed" multiple />
          </div>
          <!--<input name="Envoyer" type="submit"  value="Valider" class="btn btn-success pull-right"  />
<input name="<?php echo "MM_insert";?>" type="hidden" value="<?php echo "MM_update"; ?>" size="32" alt="" />
<input name="marche" type="hidden" value="<?php if(isset($_GET["id_mar"])) echo $_GET["id_mar"]; else echo 0; ?>" size="32" alt="" />-->
<!--</form>-->
<!--</fieldset>-->
    </td>
  </tr>
</table>

<!--<form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3" enctype="multipart/form-data">-->
<table width="100%" border="0" align="left" cellspacing="3">
          <?php $i=0; if(isset($totalRows_liste_etape_plan) && $totalRows_liste_etape_plan>0) {?>
          <tr class="titrecorps2" style="background-color: grey; color: white;">
            <td rowspan="2" width="40%"><div align="left"><span class="Style5"><strong>&nbsp;Intitul&eacute; des &eacute;tapes </strong></span></div></td>
            <td rowspan="2" bgcolor="#FFFFFF">&nbsp;</td>
            <td colspan="2"><div align="center"><span class="Style5"><strong>Pr&eacute;vision</strong></span></div></td>
            <td rowspan="2" bgcolor="#FFFFFF">&nbsp;</td>
            <td colspan="2"><div align="center"><b>R&eacute;alisation</b></div></td>
            <td rowspan="2" bgcolor="#FFFFFF"><div align="center"><input name="Envoyer" type="submit"  value="Valider" class="btn btn-success"  /></div></td>
            <!--<td rowspan="2" bgcolor="#FFFFFF">&nbsp;</td>-->
          </tr>
          <tr class="titrecorps2" style="background-color: grey; color: white;">
            <td><div align="center"><strong class="Style5">Date </strong></div></td>
            <td nowrap="nowrap" class="Style7"><div align="center">Dur&eacute;e (j) </div></td>
             <td><div align="center"><strong class="Style5">Date </strong></div></td>
            <td nowrap="nowrap" class="Style7"><div align="center">Ecart (j) </div></td>
          </tr>
          <?php $duree_totale=0; $etape_0="00-00-0000"; $duree_suivie=0; $etape_0s="00-00-0000"; $indd=0;  foreach($row_liste_etape_plan as $row_liste_etape_plan){  
		  if($indd==0) $mine=$row_liste_etape_plan['id_etape']; $indd=1;

	 $date_start = $row_edit_marche['date_prevue'];   $duree_totale=$duree_totale+$row_liste_etape_plan['duree']; ?>

		   <?php
$etape = $row_liste_etape_plan['id_etape'];
$query_liste_duree = "SELECT * FROM ".$database_connect_prefix."suivi_plan_marche where etape='$etape' and marche='$id'";
       try{
    $liste_duree = $pdar_connexion->prepare($query_liste_duree);
    $liste_duree->execute();
    $row_liste_duree = $liste_duree ->fetch();
    $totalRows_liste_duree = $liste_duree->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

 ?>


          <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; else echo 'bgcolor="#F9F9F7"'; ?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='<?php if($i%2==0) echo "#ECF0DF"; else echo "#F9F9F7";?>'">
            <td><div align="left"><span class="Style5"><?php echo $row_liste_etape_plan['intitule']; ?></span></div></td>
            <td nowrap="nowrap" bgcolor="#FFFFFF">&nbsp;</td>
            <td><div align="center"><span class="Style5"><strong><?php echo date("d/m/Y", strtotime('+'.$duree_totale.'days', strtotime($date_start))); ?></strong></span></div></td>
            <td><div align="center"><span class="Style5"><?php echo $row_liste_etape_plan['duree']; ?></span></div></td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#D2E2B1"><input   class="form-control datepicker required" type="text" name="date_reelle[]"  value="<?php if(isset($row_liste_duree['date_reelle'])) echo implode('/',array_reverse(explode('-',$row_liste_duree['date_reelle']))); ?>" style="text-align:center" size="32"/>			</td>
            <td align="center"><div><strong>
              <?php if(isset($row_liste_duree['date_reelle'])) {$nbj=number_format(NbJours(date("Y-m-d", strtotime('+'.$duree_totale.'days', strtotime($date_start))), $row_liste_duree['date_reelle']), 0, ',', ' '); $nbj=$nbj-1;
			  if(0<$nbj) {echo "<span style=\"background-color:#990000; color:#FFFFFF\">&nbsp;&nbsp;".abs($nbj)."&nbsp;&nbsp;</span>";}
			  elseif(0>$nbj){echo "<span style=\"background-color:#006600; color:#FFFFFF\">&nbsp;&nbsp;".abs($nbj)."&nbsp;&nbsp;</span>";}
			  else echo  number_format(abs($nbj), 0, ',', ' ');


			   $dst=$dst+$nbj;} else echo ""; ?>
            </strong></div></td>
            <td bgcolor="#FFFFFF"> <div align="center">
              <input name="Envoyer" type="submit"  value="Valider" class="btn btn-success"  />
            <input name="etape[]" type="hidden" value="<?php if(isset($row_liste_etape_plan["id_etape"])) echo $row_liste_etape_plan["id_etape"]; else echo 0; ?>" size="32" alt="" />
            <!--<input name="oetape[]" type="hidden" value="<?php if(isset($row_liste_etape_plan["code"])) echo $row_liste_etape_plan["code"]; else echo 0; ?>" size="32" alt="" />-->
			  <input name="<?php echo "MM_insert";?>" type="hidden" value="<?php echo "MM_insert"; ?>" size="32" alt="" />
              <input name="marche" type="hidden" value="<?php if(isset($_GET["id_mar"])) echo $_GET["id_mar"]; else echo 0; ?>" size="32" alt="" />
<!--              <input name="last_date_suivi" type="hidden" value="<?php  echo $last_date_suivi;  ?>" size="32" alt="" />
			   <input name="last_etape_suivi" type="hidden" value="<?php  echo $last_etape_suivi;  ?>" size="32" alt="" />-->
              </div></td>
            <!--<td bgcolor="#FFFFFF">&nbsp;</td> -->
            </tr>


          <?php $ld=$row_liste_etape_plan['id_etape'];}  ?>
          <?php //$date_start = date("Y-m-d", strtotime($date_start)); ?>

          <tr>
            <td nowrap="nowrap" bgcolor="#CCCCCC"><div align="right"><span class="Style5"><strong>Dur&eacute;e totale (Jours) </strong></span></div></td>
            <td nowrap="nowrap" bgcolor="#FFFFFF">&nbsp;</td>
            <td colspan="2" nowrap="nowrap" bgcolor="#CCCCCC"><div align="center">
                <div align="center" class="Style6"><strong><?php echo $duree_totale;?></strong></div>
            </div></td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
            <td colspan="2" bgcolor="#CCCCCC"><div align="center"><strong>
			<?php
			if(isset($etape_array[$mine]))
			{if(isset($etape_array[$ld])) $ntjs=NbJours($etape_array[$mine], $etape_array[$ld])-1; else  $ntjs=NbJours($etape_array[$mine], date("Y-m-d"))-1;

			if($duree_totale<$ntjs) {echo "<span style=\"background-color:#990000; color:#FFFFFF\">&nbsp;&nbsp;".number_format(($ntjs), 0, ',', ' ')."&nbsp;&nbsp;</span>";}
			  elseif($duree_totale>$ntjs){echo "<span style=\"background-color:#006600; color:#FFFFFF\">&nbsp;&nbsp;".number_format(($ntjs), 0, ',', ' ')."&nbsp;&nbsp;</span>";}
			  else echo  number_format(($ntjs), 0, ',', ' ');
			}
			//echo $etape_array[$mine];
			?>
			</strong></div></td>
            <td bgcolor="#CCCCCC">&nbsp;</td>
            <!--<td bgcolor="#D2E2B1">&nbsp;</td>-->
          </tr>

          <?php } ?>
      </table>
      <div class="clear h0">&nbsp;</div>
       </form>
</div>

</div>



<?php } else{ ?>

<?php } ?>
</body>

</html>