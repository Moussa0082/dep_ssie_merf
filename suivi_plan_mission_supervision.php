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

$dir = './attachment/supervision/';
//if(!is_dir($dir)) mkdir($dir);
//fonction calcul nb jour
function NbJours($debut, $fin) {
  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);
  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) -
          mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
  return(($diff / 86400)+1);
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$page = $_SERVER['PHP_SELF'];
$idms=$_GET['idms'];$rec=$_GET['rec'];$annee=$_GET['annee'];
//insertion des plans
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form3")) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=intval($_POST['id_plan']);

//echo intval($_POST['id_plan']);
if(isset($_POST['Annuler'])){
//livrable='', phase_realiser=null, date_reelle=null, observation=null
  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."mission_plan SET valider=0, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_plan='$c'");

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
  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."mission_plan SET phase_realiser=%s, ".(!empty($link)?"livrable=".GetSQLValueString($link, "text").", ":"")." ".(!empty($_POST['date_reelle'])?"date_reelle=".GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_reelle']))), "date").", valider=".(isset($_POST['terminer'])?1:0).",":"")." observation=%s,  etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_plan='$c'",
                       GetSQLValueString($_POST['phase_realiser'], "text"),
   					   //GetSQLValueString($link, "text"),
                       //GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_reelle']))), "date"),
                    GetSQLValueString('RAS', "text"));
}
  	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?update=ok&mod=1&rec=$rec&idms=$idms&annee=$annee&c=$c"; else $insertGoTo .= "?update=ok&rec=$rec&idms=$idms&annee=$annee";
  header(sprintf("Location: %s", $insertGoTo));
}

$query_edit_recm = "SELECT * FROM ".$database_connect_prefix."recommandation_mission, ".$database_connect_prefix."mission_supervision WHERE id_mission=mission and id_recommandation='$rec'";
       try{
    $edit_recm = $pdar_connexion->prepare($query_edit_recm);
    $edit_recm->execute();
    $row_edit_recm = $edit_recm ->fetch();
    $totalRows_edit_recm = $edit_recm->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_plan_dec = "SELECT * FROM ".$database_connect_prefix."mission_plan where code_rec='$rec' order by ordre";
       try{
    $plan_dec = $pdar_connexion->prepare($query_plan_dec);
    $plan_dec->execute();
    $row_plan_dec = $plan_dec ->fetchAll();
    $totalRows_plan_dec = $plan_dec->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$tolp=0;

if(isset($_GET['mod'])) {
  $query_progess = "SELECT code_rec FROM ".$database_connect_prefix."mission_plan where (date_reelle is not null or phase_realiser is not null) and code_ms='$idms' and code_rec='$rec' group by code_rec";
         try{
    $progess = $pdar_connexion->prepare($query_progess);
    $progess->execute();
    $row_progess = $progess ->fetchAll();
    $totalRows_progess = $progess->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $query_suivi_plan_ms = "SELECT sum(proportion) as texrecms, code_rec  FROM ".$database_connect_prefix."mission_plan where code_rec='$rec' and valider=1 group by code_rec order by code_rec";
           try{
    $suivi_plan_ms = $pdar_connexion->prepare($query_suivi_plan_ms);
    $suivi_plan_ms->execute();
    $row_suivi_plan_ms = $suivi_plan_ms ->fetch();
    $totalRows_suivi_plan_ms = $suivi_plan_ms->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $tauxp = 0;
  if($totalRows_suivi_plan_ms>0){
  $color = "red"; $tauxp=$row_suivi_plan_ms["texrecms"];
  if($tauxp<100) $color = "#FFD700";
  elseif($tauxp>=100) $color = "green";
 } elseif(isset($totalRows_progess) && $totalRows_progess>0){ $tauxp = 0; $color = "#FFD700"; }
}
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
        $(".modal-dialog", window.parent.document).width(1000);
        $("#ui-datepicker-div").remove();
        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});
});
</script>
<style type="text/css">
<!--
.help-block{display: none}
.Style1 {
	color: #990000;
	font-weight: bold;
}
-->
</style>
</head>
<body>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i><strong><strong><span class="Style14">
<?php if (isset ($row_edit_recm['debut'])) echo $row_edit_recm['type']. " du " . implode('-', array_reverse(explode('-', $row_edit_recm['debut']))) . " au " . implode('-', array_reverse(explode('-', $row_edit_recm['fin'])));?>
</span></strong></strong></h4>
  </div>
<div class="widget-content">
<div>
<strong><u>Recommandation</u>:<span class="Style14">
<?php if (isset ($row_edit_recm['recommandation'])) echo $row_edit_recm['recommandation'];?>
</span><br />
<u>Date buttoir</u>:
<?php if (isset ($row_edit_recm['type']) && $row_edit_recm['type'] == "Continu") echo "Continu";else echo date("d/m/y", strtotime($row_edit_recm['date_buttoir']));?>
<br /><u>Responsable</u>:
<?php if (isset ($row_edit_recm['responsable'])) echo $row_edit_recm['responsable_interne'];?>
</strong></div><br />

<table style="border-collapse: collapse;" class="table table-striped table-bordered table-hover table-responsive datatable  hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
              <thead>
                <tr role="row">
                  <!--<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" width="40" >N&deg;</th>-->
                  <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >T&acirc;che</th>
                
                  <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" width="100" >Date r&eacute;elle </th>
                  <!--<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" width="150" >Observations </th>-->
                  <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" width="60" ><div align="center">Etat</div></th>
                  <th width="5%" class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >&nbsp;</th>
                  <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" width="60" >&nbsp;</th>
                </tr>
              </thead>
              <tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
              <?php $t = 0;if ($totalRows_plan_dec > 0) {$p1 = "j";$t = $tprop=0;$i = $j = 0; foreach($row_plan_dec as $row_plan_dec){?>
                <tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
					<form action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3" enctype="multipart/form-data">
                  <!--<td ><span class="Style5"><?php //echo $row_plan_dec['ordre']; ?></span></td>-->
                  <td><?php echo $row_plan_dec['phase']; if($row_plan_dec['valider']==1) $tprop+=$row_plan_dec['proportion']; ?><span class="Style1"><?php echo "<b>".number_format($row_plan_dec['proportion'], 0, ',', ' ')."</b>";  ?></span></td>
                 
                  <td><input class="form-control datepicker required" type="text" <?php if(($row_plan_dec['valider'])==1){ ?> disabled="disabled" <?php } ?>  name="date_reelle" <?php if(($row_plan_dec['valider'])==0) {?>style="border-color:#FF0000"<?php }?> 
				  
				  value="<?php if(isset($row_plan_dec['date_reelle'])) echo implode('/',array_reverse(explode('-',$row_plan_dec['date_reelle']))); else echo date("d/m/Y"); ?>" size="10"  /></td>
                  <!--<td><textarea class="form-control " cols="200" rows="2" type="text" name="observation" id="observation" <?php //if($row_plan_dec['valider']==1){ ?> disabled="disabled" <?php //} ?>><?php //echo isset($row_plan_dec['observation'])?$row_plan_dec['observation']:""; ?></textarea></td>-->
                  <td><div align="center"><label for="terminer<?php echo $j; ?>">R&eacute;alis&eacute;e</label><input type="checkbox" name="terminer" id="terminer<?php echo $j; ?>" value="R&eacute;alis&eacute;e" <?php echo ($row_plan_dec['valider']==1)?'checked="checked"':""; ?> <?php if($row_plan_dec['valider']==1){ ?> disabled="disabled" <?php } ?> /></div></td>
                  <td valign="middle"><div align="center">
                    <?php if(isset($row_plan_dec['livrable']) && file_exists($dir.$row_plan_dec['livrable'])) { $rep=$dir; $extension=substr(strrchr($row_plan_dec['livrable'], '.')  ,1); if ($extension=="doc" || $extension=="docx") { echo("<a target='_blank' href='".$rep.$row_plan_dec['livrable']."'><img src='./images/doc.png' width='15'/> </a>"); } elseif ($extension=="xls" || $extension=="xlsx") { echo("<a target='_blank' href='".$rep.$row_plan_dec['livrable']."'><img src='./images/xls.png' width='15'/> </a>");} elseif ($extension=="pdf") { echo("<a target='_blank' href='".$rep.$row_plan_dec['livrable']."'><img src='./images/pdf.png' width='15'/> </a>");} elseif ($extension=="zip") { echo("<a target='_blank' href='".$rep.$row_plan_dec['livrable']."'><img src='./images/zipicon.png' width='15'/> </a>"); } else { echo("<a target='_blank' href='".$rep.$row_plan_dec['livrable']."'><img src='./images/view.png' width='15'/> </a>"); } echo "<br />"; } ?>
<?php //if(!isset($row_plan_dec['livrable']) || $row_plan_dec['valider']==1) { ?>
                    <input <?php if($row_plan_dec['valider']==1){ ?> disabled="disabled" <?php } ?> type="file" name="fichier1" id="fichier1" style="width:140px"   size="10" />
                    <input type="hidden" name="MAX_FILE_SIZE"  value="20485760" />
<?php //} ?>
                  </div></td>
                  <td><div align="right">
                    <input type="hidden" name="<?php echo "MM_insert";  ?>" value="form3" />
                    <input type="hidden" name="id_plan" value="<?php echo $row_plan_dec['id_plan']; ?>" />
					 <input type="hidden" name="val" value="<?php echo "v= ".$row_plan_dec['valider']; ?>" />
                    <?php if($row_plan_dec['valider']==0){ ?>
                    <input name="Envoyer" type="submit"  value="Valider"  />
                    <?php }else{ ?>
                    <input name="Annuler" type="submit"  value="Annuler" style="background-color:#FFFF00" />
                    <?php } ?>
                  </div></td>
                  </form>
                </tr>
				 <?php if($row_plan_dec['valider']==0) $i++; $j++; } ?>
                <?php } else echo "<tr><td colspan='8' align='center'>Aucune donn&eacute;e!</td></tr>";//".(/*((isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1))?6:5*/)." ?>
              </tbody>
            </table>
<p>Niveau d'avancement: <?php if(isset($tprop)) echo number_format($tprop, 0, ',', ' ')." %"; ?></p>

</div> </div>

<?php $color = "red";
if(isset($_GET['mod']) && $tprop>0) {
  $tauxp=$tprop;
  if($tauxp<100) $color = "#FFD700";
  elseif($tauxp>=100) $color = "green";
} elseif(isset($totalRows_progess) && $totalRows_progess>0){ $tauxp = 0; $color = "#FFD700"; }
?>
<script type="text/javascript">
<?php if(isset($_GET['mod'])) { ?>
$().ready(function() {
        // reload parent frame
        $(".close", window.parent.document).click(function(){
          //window.parent.location.reload();
          $("#stat_<?php echo $annee.$rec; ?>", window.parent.document).html('<div style="width: <?php if((isset($tauxp) && $tauxp>0) || isset($totalRows_progess)) echo $tauxp; ?>%; background-color: <?php echo $color; ?>; color:#FFFFFF;"><?php if((isset($tauxp) && $tauxp>0) || isset($totalRows_progess)) echo $tauxp." %"; else echo "Non entam&eacute;e"; ?></div>');
        });
        $("button[data-dismiss='modal']", window.parent.document).click(function(){
          //window.parent.location.reload();
          $("#stat_<?php echo $annee.$rec; ?>", window.parent.document).html('<div style="width: <?php if((isset($tauxp) && $tauxp>0) || isset($totalRows_progess)) echo $tauxp; ?>%; background-color: <?php echo $color; ?>; color:#FFFFFF;"><?php if((isset($tauxp) && $tauxp>0) || isset($totalRows_progess)) echo $tauxp." %"; else echo "Non entam&eacute;e"; ?></div>');
        });
});
<?php } ?>
</script>
</body>
</html>