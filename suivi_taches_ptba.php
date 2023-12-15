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

if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else $annee=date("Y");
if(isset($_GET['id_act'])) { $id_act = $_GET['id_act']; }
if(isset($_GET['code_act'])) { $code_act = $_GET['code_act']; }
$dir = './attachment/ptba/';
//if(!is_dir($dir)) mkdir($dir);

$editFormAction = $_SERVER['PHP_SELF'];
/*if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}*/
$page = $_SERVER['PHP_SELF'];
//insertion des plans
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=intval($_POST['id_groupe_tache']); $id_act = $_POST['id_act']; $code_act = $_POST['code_act'];

//echo intval($_POST['id_groupe_tache']);
if(isset($_POST['Annuler'])){
//livrable='', phase_realiser=null, date_reelle=null, observation=null
  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."groupe_tache SET valider=0, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_groupe_tache='$c'");

}elseif(isset($_POST['Envoyer'])){
$link = "";
  if ((isset($_FILES['fichier1']['name']))) {
    $ext_autorisees=array('docx','doc','pdf', 'xls', 'xlsx', 'jpeg', 'jpg', 'zip', 'rar'); //Extensions autoris&eacute;es
    $Result1 = false; $link = "";
    $ext = substr(strrchr($_FILES['fichier1']['name'], "."), 1);
    if(in_array($ext,$ext_autorisees))
    {
      $Result2 = move_uploaded_file($_FILES['fichier1']['tmp_name'],
      $dir.$_FILES['fichier1']['name']);
      if($Result2) $link = $_FILES['fichier1']['name'];
      if($Result2) mysql_query_ruche("DOC".$dir.$link, $pdar_connexion,1);
    }
  }
  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."groupe_tache SET ".(!empty($link)?"livrable=".GetSQLValueString($link, "text").", ":"")." ".(!empty($_POST['date_reelle'])?"date_reelle=".GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_reelle']))), "date").", valider=".(isset($_POST['terminer'])?1:0).",":"")." observation=%s,  etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_groupe_tache='$c'",
                       //GetSQLValueString($_POST['phase_realiser'], "text"),
   					   //GetSQLValueString($link, "text"),
                       //GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_reelle']))), "date"),
                    GetSQLValueString($_POST['observation'], "text"));
}
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  $insertGoTo .= "?id_act=$id_act&code_act=$code_act&annee=$annee";
  if($Result1) $insertGoTo .= "&update=ok&mod=1"; else $insertGoTo .= "&update=no";
  header(sprintf("Location: %s", $insertGoTo)); echo $insertGoTo; exit;
}

//activite
$query_act = "SELECT * FROM ".$database_connect_prefix."ptba where id_ptba='$id_act' and annee='$annee' and projet='".$_SESSION["clp_projet"]."'";
        try{
    $act = $pdar_connexion->prepare($query_act);
    $act->execute();
    $row_act = $act ->fetch();
    $totalRows_act = $act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$code_act=$row_act['code_activite_ptba'];

//query tache
$query_tache = "select * FROM ".$database_connect_prefix."groupe_tache where id_activite='$id_act' and annee='$annee' and projet='".$_SESSION["clp_projet"]."' ORDER BY code_tache ASC";
       try{
    $tache = $pdar_connexion->prepare($query_tache);
    $tache->execute();
    $row_tache = $tache ->fetchAll();
    $totalRows_tache = $tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_total_proportion = "SELECT ROUND(max(s.proportion)) as total, id_groupe_tache FROM ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."suivi_tache s WHERE id_groupe_tache=id_tache and groupe_tache.id_activite='$id_act' and s.valider=1 and annee='$annee'  GROUP BY s.id_tache";
          try{
    $total_proportion = $pdar_connexion->prepare($query_total_proportion);
    $total_proportion->execute();
    $row_total_proportion = $total_proportion ->fetchAll();
    $totalRows_total_proportion = $total_proportion->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$realiser_array = array(); $proportion=0;
if($totalRows_total_proportion>0) { foreach($row_total_proportion as $row_total_proportion){  
$proportion+=$row_total_proportion["total"]; $realiser_array[$row_total_proportion["id_groupe_tache"]] = $row_total_proportion["total"]; 
  }  }
                                  
$query_entete = "SELECT libelle,code_number FROM ".$database_connect_prefix."niveau_config WHERE projet='".$_SESSION["clp_projet"]."' LIMIT 1";
       try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$code_len = explode(',',$row_entete["code_number"]);
$libelle=explode(",",$row_entete["libelle"]);
$limit = count($libelle)-1;

$query_liste_activite_1 = "SELECT code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=$limit and projet='".$_SESSION["clp_projet"]."' ";
          try{
    $liste_activite_1 = $pdar_connexion->prepare($query_liste_activite_1);
    $liste_activite_1->execute();
    $row_liste_activite_1 = $liste_activite_1 ->fetchAll();
    $totalRows_liste_activite_1 = $liste_activite_1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$cmp_array = array();
if($totalRows_liste_activite_1>0) { foreach($row_liste_activite_1 as $row_liste_activite_1){  
  $cmp_array[$row_liste_activite_1["code"]] = $row_liste_activite_1["intitule"];
  }  }
number_format(0, 0, ',', ' ');
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
<script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<script type="text/javascript">
	$().ready(function() {
	  $(".modal-dialog", window.parent.document).width(800);
		// validate the comment form when it is submitted
		$(".row-border").validate();
	});
</script>
<style type="text/css">
<!--
.Style2 {font-weight: bold} .help-block{display: none}
thead { vertical-align: middle;}
-->
</style>
</head>
<body>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i>  <strong>Suivi des T&acirc;ches </strong> </h4>
  </div>
<div class="widget-content">
<div>
<strong><u><?php echo (isset($libelle[count($libelle)-2]))?$libelle[count($libelle)-2]:""; ?></u> : <?php $c = substr($row_act['code_activite_ptba'],0,((isset($code_len[$limit-1])?$code_len[$limit-1]:0))); echo (isset($cmp_array[$c]))?$cmp_array[$c]:""; ?></strong><br />
<strong><u><?php echo (isset($libelle[count($libelle)-1]))?$libelle[count($libelle)-1]:"Activit&eacute;s"; ?></u>: <?php echo "<strong>".$row_act['code_activite_ptba'].":</strong> ".$row_act['intitule_activite_ptba']; ?></strong><br />
<strong><u>Responsable</u> : <?php if (isset($row_tache['responsable'])) echo $row_tache['responsable']; ?></strong><br />
<strong><u>P&eacute;riode</u> </strong>: <?php echo $row_act['debut']; ?>
</div><br />

<table style="border-collapse: collapse;" class="table table-striped table-bordered table-hover table-responsive datatable  hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
              <thead>
                <tr role="row">
                  <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" width="40" >N&deg;</th>
                  <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >T&acirc;che</th>
                  <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" width="40" >Proportion</th>
                  <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" width="100" >Nbr Lot </th>
                  <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" width="100" align="center">R&eacute;alis&eacute; </th>
                    <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
                    <th width="80" align="center"><strong>Actions</strong></th>
                    <?php } ?>
                </tr>
              </thead>
              <tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
              <?php $t = 0;if ($totalRows_tache > 0) { $p1 = "j";$t = $tprop=0;$i = 0; foreach($row_tache as $row_tache){   $id_tache = $row_tache['id_groupe_tache']; ?>
			  <?php 
			 $query_max_jalonu = "SELECT groupe_tache.proportion FROM jalon_activite, groupe_tache where  id_jalon=jalon and id_activite=$id_act and id_groupe_tache<'$id_tache' ORDER BY code desc limit 1";
			          try{
    $max_jalonu = $pdar_connexion->prepare($query_max_jalonu);
    $max_jalonu->execute();
    $row_max_jalonu = $max_jalonu ->fetch();
    $totalRows_max_jalonu = $max_jalonu->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

			if(isset($row_max_jalonu['proportion'])) $max_jalon_idu=$row_max_jalonu['proportion'];  else $max_jalon_idu=0; ?>
                <tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
                  <td ><?php if(isset($row_tache['code_tache'])) echo $row_tache['code_tache']; ?></td>
                  <td><?php echo $row_tache['intitule_tache']; ?></td>
                  <td align="center"><?php echo "<b>".number_format($row_tache['proportion'], 0, ',', ' ')." %</b>";  ?></td>
                  <td align="center"><?php echo "<b>".number_format($row_tache['n_lot'], 0, ',', ' ')."</b>";  ?></td>
                  <td align="center" id="state_<?php echo $id_tache; ?>"><?php echo (isset($realiser_array[$id_tache]) && ($realiser_array[$id_tache]+1)>=($row_tache['proportion']-$max_jalon_idu))?"<b>OUI</b>":"NON"; ?></td>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
                  <td align="center">
                    <?php
                    echo do_link("","","Suivre la t&acirc;che","Suivre","","./","","get_content('suivi_taches_ptba_content.php','id=$id_tache&id_act=$id_act&code_act=$code_act&annee=$annee','modal-body_add',this.title,'iframe');",1,"margin:0px 5px;",$nfile);
                    ?>
                  </td>
<?php } ?>
                </tr>
				 <?php $i++; } ?>
                <?php } else echo "<tr><td colspan='".((isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1)?5:4)."' align='center'>Aucune donn&eacute;e!</td></tr>";//".(/*((isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1))?6:5*/)." ?>
              </tbody>

            </table>
<p>Niveau d'avancement: <?php if(isset($proportion)) echo "<span id='taux_zone'>".number_format($proportion, 0, ',', ' ')."</span> %"; ?></p>

</div> </div>

<?php $color = "danger"; //$tauxp = 0;
if($proportion>0) {
  $tauxp=$proportion;
  if($tauxp<100) $color = "warning";
  elseif($tauxp>=100) $color = "success";
} elseif(isset($proportion) && $proportion>0){ $tauxp = $proportion; $color = "warning"; }
?>
<script type="text/javascript">
<?php //if($proportion>0) { ?>
$().ready(function() {
        // reload parent frame
        $(".close", window.parent.document).click(function(){
          //window.parent.location.reload();
          $("#stat_<?php echo $annee.$id_act; ?>", window.parent.document).html('<div class="progress"> <div class="progress-bar progress-bar-<?php echo $color; ?>" style="width: <?php if(isset($tauxp)){ echo number_format($tauxp, 0, ',', ' '); } else echo "100"; ?>%"><?php if((isset($tauxp) && $tauxp>0)) echo number_format($tauxp, 0, ',', ' ')." %"; else echo "Non entam&eacute;e"; ?></div> </div>');
          $("#statut_<?php echo $annee.$id_act; ?>", window.parent.document).html('<?php if(isset($tauxp)){ if($tauxp==0 && $annee==date("Y")) echo "Non entam&eacute;e"; elseif($tauxp>0 && $tauxp<100) echo "En cours"; elseif($tauxp>=100) echo "Ex&eacute;cut&eacute;e"; else echo "Non ex&eacute;cut&eacute;e"; } else echo "Non entam&eacute;e"; ?>');
        });
        $("button[data-dismiss='modal']", window.parent.document).click(function(){
          //window.parent.location.reload();
          $("#stat_<?php echo $annee.$id_act; ?>", window.parent.document).html('<div class="progress"> <div class="progress-bar progress-bar-<?php echo $color; ?>" style="width: <?php if(isset($tauxp)){ echo number_format($tauxp, 0, ',', ' '); } else echo "100"; ?>%"><?php if((isset($tauxp) && $tauxp>0)) echo number_format($tauxp, 0, ',', ' ')." %"; else echo "Non entam&eacute;e"; ?></div> </div>');
          $("#statut_<?php echo $annee.$id_act; ?>", window.parent.document).html('<?php if(isset($tauxp)){ if($tauxp==0 && $annee==date("Y")) echo "Non entam&eacute;e"; elseif($tauxp>0 && $tauxp<100) echo "En cours"; elseif($tauxp>=100) echo "Ex&eacute;cut&eacute;e"; else echo "Non ex&eacute;cut&eacute;e"; } else echo "Non entam&eacute;e"; ?>');
        });
});
<?php //} ?>
</script>
<?php include_once $path.'modal_add.php'; ?>
</body>
</html>