<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
header('Content-Type: text/html; charset=UTF-8');

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2")) $mod=1;
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
<link href="<?php print $config->theme_folder; ?>/plugins/datatables_bootstrap.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder; ?>/plugins/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $config->theme_folder; ?>/plugins/wysiwyg-color.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>
<script type="text/javascript" src="plugins/noty/layouts/top.js"></script>
<script type="text/javascript" src="plugins/noty/themes/default.js"></script>
<script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="plugins/pickadate/picker.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
<script>
	$(document).ready(function() {
		// validate the comment form when it is submitted
		//$("#form2").validate();
        $(".modal-dialog", window.parent.document).width(840);
        <?php if(isset($mod)){ ?>
        $(".close", window.parent.document).click();
        <?php } ?>
	});
</script>
</head>
<body>
<?php
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{
  $id_ind=$_POST['id']; $prd=$_POST['prd'];
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  $annee=$_POST['annee'];
  $valind=$_POST['valind'];
  $id_ugl=$_POST['id_ugl'];
  //suppression

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  //$idzone=$id_zone[$key];
  $query_sup_cible_indicateur = "DELETE FROM cible_cmr_produit WHERE indicateur_produit=$id_ind";
  $Result1 = mysql_query($query_sup_cible_indicateur, $pdar_connexion) or die(mysql_error());


  // `indicateur` int(11) NOT NULL,   `mois` int(11) DEFAULT NULL,  `cible` float DEFAULT '0',
  foreach ($id_ugl as $key => $value)
  {
  	if(isset($valind[$key]) && $valind[$key]!=NULL) {
  	if(trim(strtolower($valind[$key]=="oui"))) $valind[$key] = "0";
  elseif(trim(strtolower($valind[$key]=="non"))) $valind[$key] = "1";

    $insertSQL = sprintf("INSERT INTO cible_cmr_produit  (indicateur_produit, zone, annee, valeur_cible, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, '$personnel', '$date')",
  					   GetSQLValueString($id_ind, "int"),
  					     GetSQLValueString($id_ugl[$key], "int"),
  					   GetSQLValueString($annee[$key], "text"),
  					   GetSQLValueString($valind[$key], "double"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
      }
    }   $mod=1;
    /*$insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?insert=ok&id_ind=$id_ind"; else $insertGoTo .= "?insert=no&id_ind=$id_ind";
    header(sprintf("Location: %s", $insertGoTo)); */
 ?>
  <script type="text/javascript">
  $("#acharger<?php echo $prd; ?>", window.parent.document).html(get_content('cmr_produit_reload.php','id=<?php echo $prd; ?>','acharger<?php echo $prd; ?>','','',1));
  //$(".close", window.parent.document).click();
  </script>
  <?php //exit(0);
}
if(!isset($mod)){
$prd = (isset($_GET["prd"]) && !empty($_GET["prd"]))?intval($_GET["prd"]):0;
if(isset($_GET["id"])){ $id_ind=$_GET['id']; // $annee=$_GET['annee'];
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$query_indicateur_courant = "SELECT * FROM indicateur_produit_cmr, produit, indicateur_produit where id_indicateur_produit=indicateur_prd and id_produit=produit and id_indicateur=$id_ind";
	$indicateur_courant  = mysql_query($query_indicateur_courant , $pdar_connexion) or die(mysql_error());
	$row_indicateur_courant  = mysql_fetch_assoc($indicateur_courant);
	$totalRows_indicateur_courant  = mysql_num_rows($indicateur_courant);

			mysql_select_db($database_pdar_connexion, $pdar_connexion);
			$query_cible_indicateur = "SELECT id_cible_cr, zone, annee, valeur_cible FROM   cible_cmr_produit where indicateur_produit='$id_ind'";
			$cible_indicateur  = mysql_query($query_cible_indicateur , $pdar_connexion) or die(mysql_error());
			$row_cible_indicateur = mysql_fetch_assoc($cible_indicateur );
			$totalRows_cible_indicateur = mysql_num_rows($cible_indicateur );
			$cible_array = array();
            if($totalRows_cible_indicateur>0){  do{ $cible_array[$row_cible_indicateur["zone"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_cible"]; }
			while($row_cible_indicateur  = mysql_fetch_assoc($cible_indicateur));}

			mysql_select_db($database_pdar_connexion, $pdar_connexion);
			//$ind_courant=$row_ind['id_indicateur_tache'];
			$query_cible_tindicateur = "SELECT sum(valeur_cible) as cible_total, zone FROM   cible_cmr_produit where indicateur_produit='$id_ind' group by zone";
			$cible_tindicateur  = mysql_query($query_cible_tindicateur , $pdar_connexion) or die(mysql_error());
			$row_cible_tindicateur = mysql_fetch_assoc($cible_tindicateur );
			$totalRows_cible_tindicateur = mysql_num_rows($cible_tindicateur );
			$tcible_array = array();
            if($totalRows_cible_tindicateur>0){  do{ $tcible_array[$row_cible_tindicateur["zone"]]=$row_cible_tindicateur["cible_total"]; }
			while($row_cible_tindicateur  = mysql_fetch_assoc($cible_tindicateur));}
  }
//inservtion valeur cible
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_phase = "SELECT min(annee_debut) as anneedebut, max(annee_fin) as anneefin FROM phase";
$liste_phase = mysql_query($query_liste_phase, $pdar_connexion) or die(mysql_error());
$row_liste_phase = mysql_fetch_assoc($liste_phase);
$totalRows_liste_phase = mysql_num_rows($liste_phase);

$an1p=$row_liste_phase['anneedebut'];
$an2p=$row_liste_phase['anneefin'];*/

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_annee = "SELECT * FROM annee ORDER BY annee";
$liste_annee  = mysql_query($query_liste_annee , $pdar_connexion) or die(mysql_error());
$row_liste_annee  = mysql_fetch_assoc($liste_annee );
$totalRows_liste_annee  = mysql_num_rows($liste_annee );

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_tab_annee= "SELECT annee FROM annee ORDER BY annee";
$liste_tab_annee = mysql_query($query_liste_tab_annee, $pdar_connexion) or die(mysql_error());
$tableauAnnee=array();
while($ligne=mysql_fetch_assoc($liste_tab_annee)){$tableauAnnee[]=$ligne['annee'];}
mysql_free_result($liste_tab_annee);*/
for($j=$_SESSION["annee_debut_projet"];$j<=$_SESSION["annee_fin_projet"];$j++) $tableauAnnee[]=$j;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_zone = "SELECT * FROM ugl ORDER BY code_ugl";
$liste_zone  = mysql_query($query_liste_zone , $pdar_connexion) or die(mysql_error());
$row_liste_zone  = mysql_fetch_assoc($liste_zone );
$totalRows_liste_zone  = mysql_num_rows($liste_zone );

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind FROM referentiel_indicateur";
$liste_ind_ref  = mysql_query($query_liste_ind_ref , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref);
$totalRows_liste_ind_ref  = mysql_num_rows($liste_ind_ref);
$liste_ind_ref_array = array();
$unite_ind_ref_array = array();
do{  $liste_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]] = $row_liste_ind_ref["intitule_ref_ind"]; $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];
}while($row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref));


?>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
<div class="widget box">
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">

 <div align="left"><span class="Style5"><span class="Style20"><?php echo $row_indicateur_courant['intitule_produit'];?></span></span><span class="Style13"> <br />
  Indicateur produit : </span><span class="Style5"><span class="Style18"><?php echo $row_indicateur_courant['intitule_indicateur_produit'];?></span></span><br />
   <strong>Indicateur CMR :</strong> <span class="Style22"><?php echo $row_indicateur_courant['intitule_indicateur'];?></span></div>
 <table width="100%" border="0" align="center" cellspacing="0">
  <tr>
    <td valign="top"><div  id="special">
      <p align="center">Valeurs cibles annuelles par Entit&eacute; de gestion <u><span class="Style19">

    <?php

					  $cible_cmr = $row_indicateur_courant['cible_cmr'];
						if(trim(strtolower($cible_cmr))==0 && isset($unite_ind_ref_array[$row_indicateur_courant['referentiel']]) && $unite_ind_ref_array[$row_indicateur_courant['referentiel']]=="Oui/Non") echo "Oui";
						//elseif(trim(strtolower($cible_cmr))==1 && isset($unite_ind_ref_array[$row_indicateur_courant['referentiel']]) && $row_indprd['unite_cmr']=="Oui/Non") echo "Non";
						elseif(trim(strtolower($cible_cmr))==-1) echo "n/a";
				        else echo $cible_cmr;
					   ?>
   <?php echo " (".((isset($unite_ind_ref_array[$row_indicateur_courant['referentiel']])?$unite_ind_ref_array[$row_indicateur_courant['referentiel']]:"")).")";?></span></u></p>
      </div></td>
    </tr>
  <tr>
    <td valign="top">
      <div align="center">


        <table border="0" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive datatable ">
          <?php $t=0;  if($totalRows_liste_zone>0) { ?>
          <thead>
          <tr class="titrecorps2">
		   <th ><div align="left" class="Style13"><strong>Entit&eacute; de gestion</strong></div>                </th>
            <th ><div align="left" class="Style13">Valeur</div>                </th>
                                     <?php foreach($tableauAnnee as $imois){?>
                      <th ><div align="center" class="Style31"><strong>
				      <?php
					   // $amois = explode('<>',$vmois);
					  // $imois = $vannee;
						 echo $imois; ?>
                  </strong></div></th>
                       <?php } ?>
            </tr>
           </thead>
          <?php $p1="j"; $t=0; $i=0;do { ?>


          <tr <?php /*if($i%2==0) echo 'bgcolor="#D2E2B1"';*/  $i=$i+1; $t=$t+1;?>>
		   <td><div align="left" class="Style13"><u><span class="Style5"><?php echo " <strong>".$row_liste_zone['nom_ugl']."</strong>"; ?></span></u></div>                </td>
            <td ><strong><span class="Style18">
			<?php //if(isset($tcible_array[$row_liste_zone['id_zone']])) echo $tcible_array[$row_liste_zone['id_zone']];?>
			 <?php if(isset($unite_ind_ref_array[$row_indicateur_courant['referentiel']]) && trim($unite_ind_ref_array[$row_indicateur_courant['referentiel']])=="Oui/Non"){
                            if(isset($tcible_array[$row_liste_zone['id_ugl']]) && $tcible_array[$row_liste_zone['id_ugl']]==0) echo "Oui";
                            elseif(isset($tcible_array[$row_liste_zone['id_ugl']])) echo "Non";
						  }
                          elseif(isset($unite_ind_ref_array[$row_indicateur_courant['referentiel']]) && isset($tcible_array[$row_liste_zone['id_ugl']])&& $unite_ind_ref_array[$row_indicateur_courant['referentiel']]!="%") echo number_format($tcible_array[$row_liste_zone['id_ugl']], 0, ',', ' '); ?>
						  </span></strong>			</td>
            <?php foreach($tableauAnnee as $iannee){?>
                      <td><div align="center" class="Style31">

						<input name='valind[]' style="text-align:center" type="text" size="5"  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']>3) echo "disabled"; ?> value="<?php
						 if(isset($cible_array[$row_liste_zone['id_ugl']][$iannee]))
						 {
						 if(trim($unite_ind_ref_array[$row_indicateur_courant['referentiel']])=="Oui/Non"){
						    if($cible_array[$row_liste_zone['id_ugl']][$iannee]==0) echo "Oui";
                            else echo "Non"; }

						 else echo $cible_array[$row_liste_zone['id_ugl']][$iannee];}

						  ?>" class="form-control"/>
                          <input name="annee[]" type="hidden" size="5" value="<?php echo $iannee; ?>"/>
                          <input name="id_ugl[]" type="hidden" size="5" value="<?php echo $row_liste_zone['id_ugl']; ?>"/>
					  </div></td>
                       <?php } ?>
          </tr>
          <?php } while ($row_liste_zone = mysql_fetch_assoc($liste_zone)); ?>
          <?php } else echo "<h3>Aucune zone disponible</h3>" ;?>
        </table>
      </div>
      </td></tr>
     </table>                     
<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
  <input type="hidden" name="prd" value="<?php echo $prd; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">

<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } } ?>
</body>
</html>