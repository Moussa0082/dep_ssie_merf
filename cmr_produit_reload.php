<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"]) || !isset($_GET['id'])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}

include_once $config->sys_folder . "/database/db_connexion.php";
header('Content-Type: text/html; charset=UTF-8');
$id_produit=$_GET['id'];

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_res = "SELECT id_produit, code_produit, intitule_produit, code_resultat, id_resultat, intitule_resultat  FROM resultat, produit, activite_projet WHERE activite_projet.projet='".$_SESSION["clp_projet"]."' and code=composante and id_resultat=effet and id_produit=$id_produit order by code_resultat, code_produit ";
$liste_res  = mysql_query($query_liste_res, $pdar_connexion) or die(mysql_error());
$row_liste_res  = mysql_fetch_assoc($liste_res);
$totalRows_liste_res  = mysql_num_rows($liste_res);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind, mode_calcul FROM referentiel_indicateur";
$liste_ind_ref  = mysql_query($query_liste_ind_ref , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref);
$totalRows_liste_ind_ref  = mysql_num_rows($liste_ind_ref);
$liste_ind_ref_array = array();
$unite_ind_ref_array = array();
$mode_calcul_ind_ref_array = array();
do{  $liste_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]] = $row_liste_ind_ref["intitule_ref_ind"];
 $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];
 $mode_calcul_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["mode_calcul"];
}while($row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref));


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cible_ind_ref = " SELECT referentiel, sum(valeur_cible) as valeur_cible FROM   cible_cmr_produit, indicateur_produit_cmr, referentiel_indicateur  where id_indicateur=indicateur_produit and id_ref_ind=referentiel  AND mode_calcul =  'Unique' and zone in(select id_region from region) group by indicateur_produit ";
$cible_ind_ref  = mysql_query($query_cible_ind_ref , $pdar_connexion) or die(mysql_error());
$row_cible_ind_ref = mysql_fetch_assoc($cible_ind_ref);
$totalRows_cible_ind_ref  = mysql_num_rows($cible_ind_ref);
$cible_ind_ref_array = array();
do{  $cible_ind_ref_array[$row_cible_ind_ref["referentiel"]] = $row_cible_ind_ref["valeur_cible"];
}while($row_cible_ind_ref = mysql_fetch_assoc($cible_ind_ref));



mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_somme_ind_ref = " SELECT indicateur_ref, indicateur_prd, sum(valeur_cible) as valeur_cible FROM   cible_cmr_produit, indicateur_produit_cmr, referentiel_indicateur, calcul_indicateur_simple_ref  where id_indicateur=indicateur_produit and id_ref_ind=referentiel  and  FIND_IN_SET( id_ref_ind, indicateur_simple )
AND mode_calcul =  'Unique' and zone in(select id_region from region) group by indicateur_prd,indicateur_ref ";
$somme_ind_ref  = mysql_query($query_somme_ind_ref , $pdar_connexion) or die(mysql_error());
$row_somme_ind_ref = mysql_fetch_assoc($somme_ind_ref);
$totalRows_somme_ind_ref  = mysql_num_rows($somme_ind_ref);
$somme_ind_ref_array = array();

do{  $somme_ind_ref_array[$row_somme_ind_ref["indicateur_ref"]] = $row_somme_ind_ref["valeur_cible"];
}while($row_somme_ind_ref = mysql_fetch_assoc($somme_ind_ref));

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_moyenne_ind_ref = " SELECT indicateur_ref ,  avg(valeur_cible) as valeur_cible
					FROM (SELECT referentiel, id_indicateur, indicateur_ref, sum(valeur_cible) as valeur_cible FROM   cible_cmr_produit, indicateur_produit_cmr, referentiel_indicateur, calcul_indicateur_simple_ref  where id_indicateur=indicateur_produit and id_ref_ind=referentiel  and  FIND_IN_SET( id_ref_ind, indicateur_simple )
AND mode_calcul =  'Unique' and zone in(select id_region from region) group by id_indicateur, indicateur_ref, referentiel)  AS alias_sr group by indicateur_ref ";
$moyenne_ind_ref  = mysql_query($query_moyenne_ind_ref , $pdar_connexion) or die(mysql_error());
$row_moyenne_ind_ref = mysql_fetch_assoc($moyenne_ind_ref);
$totalRows_moyenne_ind_ref  = mysql_num_rows($moyenne_ind_ref);
$moyenne_ind_ref_array = array();

do{  $moyenne_ind_ref_array[$row_moyenne_ind_ref["indicateur_ref"]] = $row_moyenne_ind_ref["valeur_cible"];
}while($row_moyenne_ind_ref = mysql_fetch_assoc($moyenne_ind_ref));


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ratio = "SELECT indicateur_ref, numerateur, denominateur FROM ratio_indicateur_ref order by indicateur_ref";
$liste_ind_ratio  = mysql_query($query_liste_ind_ratio , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ratio = mysql_fetch_assoc($liste_ind_ratio);
$totalRows_liste_ind_ratio  = mysql_num_rows($liste_ind_ratio);
$liste_num_ratio_array = array();
$liste_deno_ratio_array = array();
do{
 $liste_num_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = $row_liste_ind_ratio["numerateur"];
  $liste_deno_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = $row_liste_ind_ratio["denominateur"];
}while($row_liste_ind_ratio = mysql_fetch_assoc($liste_ind_ratio));

?>                                      
<table border="0" width="100%" cellspacing="0" class="table table-striped table-bordered table-responsive">
                    <?php         
				    $id_prd=$row_liste_res['id_produit'];
				    mysql_select_db($database_pdar_connexion, $pdar_connexion);
					$query_ind = "SELECT * FROM indicateur_produit, indicateur_produit_cmr where  id_indicateur_produit=indicateur_prd and produit='$id_prd' order by code_iprd asc, code_irprd";
					$ind  = mysql_query($query_ind , $pdar_connexion) or die(mysql_error());
					$row_ind  = mysql_fetch_assoc($ind);
					$totalRows_ind  = mysql_num_rows($ind);
				  ?>

                   <?php if($totalRows_ind>0) { if(isset($unite_ind_ref_array[$row_ind["referentiel"]])) $unite = $unite_ind_ref_array[$row_ind["referentiel"]]; else  $unite=""; ?>
                <thead>
                    <tr>
                      <th width="60%"><font size="2">Indicateurs de produit</font></th>
                      <th width="10%"><font size="2"> Unit&eacute;</font></th>
                      <th width="10%"><font size="2">Cible DCP</font></th>
                      <th width="10%"><strong><font size="2">Cible CMR</font></strong></th>
                      <!--<th width="10%"><strong><font size="2">R&eacute;vis&eacute;e</font></strong></th>-->
                    </tr>
                </thead>
                    <?php  $tbcount=0; $pp="j"; $cible_deno=$cible_num=0; $i=0; do { ?>
					 <?php if($pp!=$row_ind['id_indicateur_produit']) {?>
          <tr bgcolor="#BED694">
            <td colspan="7" align="center" bgcolor="#CCCCCC"><div align="left" class="Style4"><strong><font size="2">

                      <?php  if($pp!=$row_ind['id_indicateur_produit']){echo $row_ind['intitule_indicateur_produit']; }$pp=$row_ind['id_indicateur_produit']; ?></font>
                        </strong></div></td>
            </tr>
          <?php } ?>
					<tr <?php if(isset($mode_calcul_ind_ref_array[$row_ind["referentiel"]]) &&  $mode_calcul_ind_ref_array[$row_ind["referentiel"]]!='Unique') echo 'bgcolor="#ECF0DF"'; else echo 'bgcolor="#FFFFFF"'; $i=$i+1;?> >
                      <td <?php echo (!isset($liste_ind_ref_array[$row_ind['referentiel']]))?'style="color:#FF0000"':''; ?>><div align="left" class="Style22">
                     <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
                      <a onclick="get_content('edit_indicateur_prdcmr.php','id=<?php echo $row_ind['id_indicateur']; ?>&prd=<?php echo $row_liste_res['id_produit']; ?>&iframe=1','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" title="Modification d'indicateur de produit" class="thickbox Add"  dir=""><span class="Style17">.&nbsp;</span><?php echo $row_ind['intitule_indicateur']; ?></a>
					  <?php } else { ?>
<span class="Style17">.&nbsp;</span><?php echo $row_ind['intitule_indicateur']; ?>
<?php } ?></div></td>
                      <td ><div align="center" class="Style21"><span class="Style22">
                        <?php if(isset($unite_ind_ref_array[$row_ind["referentiel"]])) $unite = $unite_ind_ref_array[$row_ind["referentiel"]]; else  $unite=""; echo $unite; ?>
                      </span></div></td>
                      <td><div align="center"><span class="Style21">
                        <?php

					  $cible_cmr = $row_ind['cible_cmr'];
						if(trim(strtolower($cible_cmr))==0 &&  $unite=="Oui/Non") echo "Oui";
						elseif(trim(strtolower($cible_cmr))==1 && $unite=="Oui/Non") echo "Non";
						elseif(trim(strtolower($cible_cmr))==-1) echo "n/a";
				        else echo $cible_cmr;
					   ?>
                      </span></div></td>

                      <?php if(isset($_SESSION['clp_niveau'])) {?>
                      <td>
                        <div align="center"><?php if(isset($mode_calcul_ind_ref_array[$row_ind["referentiel"]]) && $mode_calcul_ind_ref_array[$row_ind["referentiel"]]=="Unique") {?><a onclick="get_content('edit_cible_cmr_produit.php','id=<?php echo $row_ind['id_indicateur']; ?>&prd=<?php echo $row_liste_res['id_produit']; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" title="Valeurs cibles annuelles" class="thickbox Add"  dir=""><strong><?php if(isset($cible_ind_ref_array[$row_ind["referentiel"]]) && ((isset($unite_ind_ref_array[$row_ind['referentiel']]) && $unite_ind_ref_array[$row_ind['referentiel']]!="%") || !isset($unite_ind_ref_array[$row_ind['referentiel']]))) echo $cible_ind_ref_array[$row_ind["referentiel"]]; elseif(isset($unite_ind_ref_array[$row_ind['referentiel']]) && $unite_ind_ref_array[$row_ind['referentiel']]=="%") echo "Cibles (%)"; else echo "Cibles annuelles"; ?></strong></a>
						<?php

						} elseif(isset($mode_calcul_ind_ref_array[$row_ind["referentiel"]]) && $mode_calcul_ind_ref_array[$row_ind["referentiel"]]=="Somme")
						 {
						 if(isset($somme_ind_ref_array[$row_ind["referentiel"]])) echo $somme_ind_ref_array[$row_ind["referentiel"]];
						 } elseif(isset($mode_calcul_ind_ref_array[$row_ind["referentiel"]]) && $mode_calcul_ind_ref_array[$row_ind["referentiel"]]=="Ratio" && isset($liste_num_ratio_array[$row_ind["referentiel"]]) && isset($liste_deno_ratio_array[$row_ind["referentiel"]]))
						 {
						 //cas ou numerateur est une somme
						 if(isset($mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]] =="Somme" && isset($somme_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) )  $cible_num=$somme_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]];

						  //cas ou denominateur est une somme
						 if(isset($mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]] =="Somme" && isset($somme_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) )   $cible_deno=$somme_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]];

						  //cas ou num est unique
						  if(isset($mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]] =="Unique" && isset($cible_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) ) $cible_num=$cible_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]];

						  //cas ou deno est unique
						  if(isset($mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]] =="Unique" && isset($cible_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) )  $cible_deno=$cible_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]];

						   //cas ou numerateur est une moyenne
						 if(isset($mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]] =="Moyenne" && isset($moyenne_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) )  $cible_num=$moyenne_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]];

						  //cas ou denominateur est une moyenne
						 if(isset($mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]] =="Moyenne" && isset($moyenne_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) )   $cible_deno=$moyenne_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]];


						 //if() echo $somme_ind_ref_array[$row_ind["referentiel"]];
						 if($cible_deno!=0) echo number_format(100*$cible_num/$cible_deno, 2, ',', ' ');

						 $cible_num=$cible_deno=0;
						 }
						  elseif(isset($mode_calcul_ind_ref_array[$row_ind["referentiel"]]) && $mode_calcul_ind_ref_array[$row_ind["referentiel"]]=="Moyenne")
						 {
						 if(isset($moyenne_ind_ref_array[$row_ind["referentiel"]])) echo number_format($moyenne_ind_ref_array[$row_ind["referentiel"]], 2, ',', ' ');
						 }	else echo "N/A";


						?> </div></td>
<!--                      <td><div align="center"><span class="Style21">
                        <?php  /*

					  $cible_rmp = $row_ind['cible_rmp'];
						if(trim(strtolower($cible_rmp))==0 &&  $unite=="Oui/Non") echo "Oui";
						elseif(trim(strtolower($cible_rmp))==1 && $unite=="Oui/Non") echo "Non";
						elseif(trim(strtolower($cible_rmp))==-1) echo "n/a";
				        else echo $cible_rmp; */
					   ?>
                      </span></div></td>-->
					  <?php }?>
					</tr>

                    <?php } while ($row_ind = mysql_fetch_assoc($ind)); ?>
                    <?php } ?>
                </table>