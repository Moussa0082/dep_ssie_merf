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

if(isset($_GET['id'])) { $id_tache = $_GET['id']; }

$dir = './attachment/ptba/';

//if(!is_dir($dir)) mkdir($dir);



$editFormAction = $_SERVER['PHP_SELF'];

/*if (isset($_SERVER['QUERY_STRING'])) {

  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);

}*/

$page = $_SERVER['PHP_SELF'];



$query_total_proportion = "SELECT proportion, n_lot FROM ".$database_connect_prefix."groupe_tache WHERE id_groupe_tache='$id_tache'";
       try{
    $total_proportion = $pdar_connexion->prepare($query_total_proportion);
    $total_proportion->execute();
    $row_total_proportion = $total_proportion ->fetch();
    $totalRows_total_proportion = $total_proportion->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$proportion_total=$row_total_proportion["proportion"];

$lot_total=$row_total_proportion["n_lot"];



$query_total_proportion = "SELECT SUM(proportion) as total FROM ".$database_connect_prefix."suivi_tache WHERE id_tache='$id_tache' and valider=1";
       try{
    $total_proportion = $pdar_connexion->prepare($query_total_proportion);
    $total_proportion->execute();
    $row_total_proportion = $total_proportion ->fetch();
    $totalRows_total_proportion = $total_proportion->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$proportion=0;

if($totalRows_total_proportion>0){ $proportion=$row_total_proportion["total"]; }

if(!isset($row_total_proportion["total"])) $proportion=0;



//insertion des plans

if((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $id_act = $_POST['id_act']; $code_act = $_POST['code_act']; $id = $_POST['id_groupe_tache'];

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

    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."suivi_tache (id_tache, lot, proportion, date_reelle, livrable, valider, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s,'$personnel', '$date')",



		  			   GetSQLValueString($_POST['id_groupe_tache'], "int"),

                      // GetSQLValueString($id_act, "int"),

					   GetSQLValueString($_POST['n_lot'], "int"),

					   GetSQLValueString($_POST['proportion'], "double"),

                       GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_reelle']))), "date"),

					   GetSQLValueString($link, "text"),

                       GetSQLValueString((isset($_POST['terminer'])?1:0), "int"));



  	    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];

  $insertGoTo .= "?id=$id&id_act=$id_act&code_act=$code_act&annee=$annee";

  if($Result1) $insertGoTo .= "&update=ok&mod=1"; else $insertGoTo .= "&update=no";

  header(sprintf("Location: %s", $insertGoTo)); echo $insertGoTo; exit;

}



if ((isset($_POST["MM_update"])) && !empty($_POST["MM_update"])) {

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

$c=intval($_POST['MM_update']); $id_act = $_POST['id_act']; $code_act = $_POST['code_act'];

$id = $_POST['id_groupe_tache'];



//echo intval($_POST['id_groupe_tache']);

if(isset($_POST['Annuler'])){

//livrable='', phase_realiser=null, date_reelle=null, observation=null

  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."suivi_tache SET valider=0, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_suivi='$c'");



}else{

$link = "";

if($lot_total==$_POST['n_lot'] && $proportion_total>$proportion)

//$_POST['proportion'] = $proportion_total-$proportion;

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

  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."suivi_tache SET ".(!empty($link)?"livrable=".GetSQLValueString($link, "text").", ":"")." ".(!empty($_POST['date_reelle'])?"date_reelle=".GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_reelle']))), "date").", valider=".(isset($_POST['terminer'])?1:0).",":"")." lot=%s, proportion=%s, observation=%s,  etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_suivi='$c'",

                       //GetSQLValueString($_POST['phase_realiser'], "text"),

   					   //GetSQLValueString($link, "text"),

                       //GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_reelle']))), "date"),

                  //  GetSQLValueString($id_act, "int"),

                    GetSQLValueString($_POST['n_lot'], "int"),

                    GetSQLValueString($_POST['proportion'], "double"),

                    GetSQLValueString($_POST['observation'], "text"));

}

    	    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];

  $insertGoTo .= "?id=$id&id_act=$id_act&code_act=$code_act&annee=$annee";

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

$query_tache = "select * FROM ".$database_connect_prefix."groupe_tache where id_groupe_tache='$id_tache'";
       try{
    $tache = $pdar_connexion->prepare($query_tache);
    $tache->execute();
    $row_tache = $tache ->fetch();
    $totalRows_tache = $tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }



//query suivi tache

$query_suivi_tache = "select * FROM ".$database_connect_prefix."suivi_tache where id_tache='$id_tache'";
	           try{
    $tache = $pdar_connexion->prepare($query_suivi_tache);
    $tache->execute();
    $row_suivi_tache = $tache ->fetchAll();
    $totalRows_suivi_tache = $tache->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$suivi_array = array();
if($totalRows_suivi_tache>0) { foreach($row_suivi_tache as $row_suivi_tache){  
 $suivi_array[$row_suivi_tache["lot"]] = $row_suivi_tache;
 }  }

$query_entete = "SELECT libelle,code_number FROM ".$database_connect_prefix."niveau_config WHERE projet='".$_SESSION["clp_projet"]."'  LIMIT 1";
       try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$code_len = explode(',',$row_entete["code_number"]);

$libelle=explode(",",$row_entete["libelle"]);

$limit = count($libelle)-1;



$query_liste_activite_1 = "SELECT code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=$limit and projet='".$_SESSION["clp_projet"]."'  ";
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

	  //$(".modal-dialog", window.parent.document).width(800);

		// validate the comment form when it is submitted

		$(".row-border").validate();

        $("#ui-datepicker-div").remove();

        $(".datepicker").datepicker({defaultDate:+7,showOtherMonths:true,autoSize:true,appendText:'<span class="help-block">(jj/mm/aaaa)</span>',dateFormat:"dd/mm/yy"});$(".inlinepicker").datepicker({inline:true,showOtherMonths:true,dateFormat:"dd/mm/yy"});

	});

</script>

<style type="text/css">

<!--

.Style2 {font-weight: bold} .help-block{display: none}

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

<strong><u>P&eacute;riode</u> : <?php echo $row_act['debut']; ?></strong><br />

<strong><u>T&acirc;che</u> : <?php if(isset($row_tache['intitule_tache'])) echo $row_tache['intitule_tache']; echo "<b style='color:green'> (".$row_tache['n_lot']." lot".(($row_tache['n_lot']>1)?"s":"")." ===> ".$row_tache['proportion']."%)</b>"; ?></strong>

</div><br />



<table style="border-collapse: collapse;" class="table table-striped table-bordered table-hover table-responsive datatable  hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">

              <thead>

                <tr role="row">

                  <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" width="40" >Lot</th>

                  <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" width="80" ><center>%</center></th>

                  <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" width="100" >Date r&eacute;elle </th>

                  <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" width="150" >Observations </th>

                  <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" width="60" ><div align="center">Etat</div></th>

<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>

                  <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Fichiers</th>

                  <th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Actions</th>

<?php } ?>

                </tr>

              </thead>

              <tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">

              <?php if($totalRows_tache > 0) { $lot = intval($row_tache['n_lot']); $lot = ($lot==0)?1:$lot;
			  
			  $query_max_jalonu = "SELECT groupe_tache.proportion FROM jalon_activite, groupe_tache where  id_jalon=jalon and id_activite=$id_act and id_groupe_tache<'$id_tache' ORDER BY code desc limit 1";
					
			       try{
    $max_jalonu = $pdar_connexion->prepare($query_max_jalonu);
    $max_jalonu->execute();
    $row_max_jalonu = $max_jalonu ->fetch();
    $totalRows_max_jalonu = $max_jalonu->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

			if(isset($row_max_jalonu['proportion'])) $max_jalon_idu=$row_max_jalonu['proportion'];  else $max_jalon_idu=0;

			   $proportion_tache = ($row_tache['proportion']-$max_jalon_idu)/$lot; for($i=1; $i<=$lot; $i++) { ?>

                <tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">

					<form onsubmit="<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) echo ""; else echo "return false;"; ?>" action="<?php echo $editFormAction; ?>" method="post" name="form3" id="form3" enctype="multipart/form-data">

                  <td align="center"><?php echo $i; ?></td>

                  <td><input size="10" class="form-control required" type="text" <?php if(isset($suivi_array[$i]) && $suivi_array[$i]['valider']==1){ ?> disabled="disabled" <?php } ?>  name="proportion" id="proportion" <?php if(isset($suivi_array[$i]) && $suivi_array[$i]['valider']==0) {?>style="border-color:#FF0000"<?php }?> value="<?php if(isset($suivi_array[$i]) && $suivi_array[$i]['proportion']) echo $suivi_array[$i]['proportion']; else echo $proportion_tache; ?>"  /></td>

                  <td><input class="form-control datepicker required"  <?php if(isset($suivi_array[$i]) && $suivi_array[$i]['valider']==1){ ?> disabled="disabled" <?php } ?> type="text" name="date_reelle" id="date_reelle" <?php if(isset($suivi_array[$i]) && $suivi_array[$i]['valider']==0) {?>style="border-color:#FF0000"<?php }?> value="<?php if(isset($suivi_array[$i]) && $suivi_array[$i]['date_reelle']) echo implode('/',array_reverse(explode('-',$suivi_array[$i]['date_reelle']))); else echo date("d/m/Y"); ?>" size="10"  /></td>

                  <td><textarea class="form-control " cols="200" rows="2" type="text" name="observation" id="observation" <?php if(isset($suivi_array[$i]) && $suivi_array[$i]['valider']==1){ ?> disabled="disabled" <?php } ?>><?php if(isset($suivi_array[$i]) && $suivi_array[$i]['observation']) echo $suivi_array[$i]['observation']; ?></textarea></td>

                  <td><div align="center"><label for="terminer<?php echo $i; ?>">R&eacute;alis&eacute;e</label><input type="checkbox" name="terminer" id="terminer<?php echo $i; ?>" value="R&eacute;alis&eacute;e" <?php echo (isset($suivi_array[$i]) && $suivi_array[$i]['valider']==1)?'checked="checked"':""; ?> <?php if(isset($suivi_array[$i]) && $suivi_array[$i]['valider']==1){ ?> disabled="disabled" <?php } ?> /></div></td>

<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>

                  <td valign="middle"><div align="center">

                    <?php if(isset($suivi_array[$i]) && $suivi_array[$i]['livrable'] && file_exists($dir.$suivi_array[$i]['livrable'])) { $rep=$dir; $extension=substr(strrchr($suivi_array[$i]['livrable'], '.')  ,1); if ($extension=="doc" || $extension=="docx") { echo("<a target='_blank' href='".$rep.$suivi_array[$i]['livrable']."'><img src='./images/doc.png' width='15'/> </a>"); } elseif ($extension=="xls" || $extension=="xlsx") { echo("<a target='_blank' href='".$rep.$suivi_array[$i]['livrable']."'><img src='./images/xls.png' width='15'/> </a>");} elseif ($extension=="pdf") { echo("<a target='_blank' href='".$rep.$suivi_array[$i]['livrable']."'><img src='./images/pdf.png' width='15'/> </a>");} elseif ($extension=="zip") { echo("<a target='_blank' href='".$rep.$suivi_array[$i]['livrable']."'><img src='./images/zipicon.png' width='15'/> </a>"); } else { echo("<a target='_blank' href='".$rep.$suivi_array[$i]['livrable']."'><img src='./images/view.png' width='15'/> </a>"); } echo "<br />"; } ?>

<?php //if(!isset($suivi_array[$i]['livrable']) || $suivi_array[$i]['valider']==1) { ?>

                    <input <?php if(isset($suivi_array[$i]) && $suivi_array[$i]['valider']==1){ ?> disabled="disabled" <?php } ?> type="file" name="fichier1" id="fichier1" style="width:100px;" size="10" />

                    <input type="hidden" name="MAX_FILE_SIZE" value="20485760" />

<?php //} ?>

                  </div></td>

                  <td><div align="right">

                      <input name="<?php if(isset($suivi_array[$i])) echo "MM_update"; else echo "MM_insert";?>" type="hidden" value="<?php if(isset($suivi_array[$i])) echo $suivi_array[$i]["id_suivi"]; else echo "MM_insert"; ?>" size="32" alt="">

                    <input type="hidden" name="id_act" value="<?php echo $row_act['id_ptba']; ?>" />

					<input type="hidden" name="code_act" value="<?php echo $row_act['code_activite_ptba']; ?>" />

                    <input type="hidden" name="id_groupe_tache" value="<?php echo $row_tache['id_groupe_tache']; ?>" />

                    <input type="hidden" name="n_lot" value="<?php echo $i; ?>" />

                    <!--<input type="hidden" name="proportion" value="<?php //echo number_format($proportion_tache, 2, ',', ' '); ?>" /> -->

                    <?php if(!isset($suivi_array[$i]) || $suivi_array[$i]['valider']==0){ ?>

                      <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($suivi_array[$i])) echo "Modifier"; else echo "Enregistrer"; ?>" />

                    <?php }else{ ?>

                    <input name="Annuler" type="submit"  value="Annuler" style="background-color:#FFFF00" />

                    <?php } ?>

                  </div></td>

<?php } ?>

                  </form>

                </tr>

				 <?php } ?>

                <?php } else echo "<tr><td colspan='".(((isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1))?7:5)."' align='center'>Aucune donn&eacute;e!</td></tr>"; ?>

              </tbody>



            </table>

<p>Niveau d'avancement: <?php if(isset($proportion)) echo number_format($proportion, 2, ',', ' ')." %"; ?></p>



</div> </div>



<?php $color = "red"; $tauxp = 0;

/*if(isset($_GET['mod']) && $proportion>0) {

mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_total_proportion = "SELECT SUM(s.proportion) as total FROM ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."suivi_tache s WHERE id_groupe_tache=id_tache and s.id_activite='$id_act' and s.valider=1 and annee='$annee' and ".$database_connect_prefix."groupe_tache.projet='".$_SESSION["clp_projet"]."' GROUP BY s.id_activite";

$total_proportion = mysql_query_ruche($query_total_proportion, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_total_proportion = mysql_fetch_assoc($total_proportion);

$totalRows_total_proportion = mysql_num_rows($total_proportion);

$proportion=0;

if(isset($row_total_proportion["total"]) && $row_total_proportion["total"]>0){ $proportion=$row_total_proportion["total"]; }

}*/

?>

<script type="text/javascript">

<?php if(isset($_GET['mod'])) { ?>

$().ready(function() {

        // reload parent frame

        $(".close", window.parent.document).click(function(){

          window.parent.location.reload();

          //$("#state_<?php echo $id_tache; ?>", window.parent.document).html('<?php echo ($proportion>=$row_tache['proportion'])?"<b>OUI</b>":"NON"; ?>');

        });

        $("button[data-dismiss='modal']", window.parent.document).click(function(){

          window.parent.location.reload();

          //$("#state_<?php echo $id_tache; ?>", window.parent.document).html('<?php echo ($proportion>=$row_tache['proportion'])?"<b>OUI</b>":"NON"; ?>');

        });

});

<?php } ?>

</script>



</body>

</html>