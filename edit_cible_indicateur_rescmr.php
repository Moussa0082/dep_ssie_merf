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

$rres = (isset($_GET["res"]) && !empty($_GET["res"]))?intval($_GET["res"]):0;

    $tableauAnnee=array();
for($i=$_SESSION["annee_debut_projet"];$i<=$_SESSION["annee_fin_projet"];$i++) $tableauAnnee[]=$i;


if(isset($_GET["id"])) { $id_ind=$_GET["id"];}
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_indres = "SELECT * FROM indicateur_resultat_cmr, indicateur_resultat where id_indicateur_resultat=indicateur_res and id_indicateur='$id_ind'";
$indres  = mysql_query($query_indres , $pdar_connexion) or die(mysql_error());
$row_indres  = mysql_fetch_assoc($indres);
$totalRows_indres  = mysql_num_rows($indres);
?>
<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form2").validate();
	});
</script>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
<div class="widget box">
<div class="widget-content">
<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">
<table border="0" cellspacing="1" width="100%" class="table table-striped table-bordered table-responsive">

                          <?php if($totalRows_indres>0) { ?>
                           <?php $i=0; $p1="j"; $p11="k"; do { $tcic=0; ?>

                          <tr bgcolor="#ECF000">
                            <td colspan="<?php echo count($tableauAnnee); ?>" align="center" bgcolor="#D2E2B1" ><div align="left" class="Style30"> <u>
                                <?php  echo $row_indres['intitule_indicateur_resultat'];  ?> </u> </div></td>
                          </tr>

                          <tr bgcolor="#ECF000">
                            <td colspan="<?php echo count($tableauAnnee); ?>" align="center" bgcolor="#D2E2B1" ><div align="left" class="Style2">
                                <?php  echo $row_indres['intitule_indicateur_cmr_res']; $i=0; ?>
                             </div></td>
                          </tr>
                          <tr class="titrecorps2" bgcolor="#CCCCCC" >


                            <td align="center" colspan="<?php if(count($tableauAnnee)>0) echo count($tableauAnnee)+2; else echo 1; ?>" >Valeurs cibles </td>
                          </tr>
                          <tr class="titrecorps2" >
                            <?php foreach($tableauAnnee as $anp){?>
                            <td><div align="center"><?php echo $anp; ?></div></td>
                            <?php } ?>
                          </tr>

                            <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; else echo 'bgcolor="#FFFFFF"'; $i=$i+1;?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='<?php if($i%2!=0) echo '#ECF0DF';?>';">


                              <?php foreach($tableauAnnee as $anp){?>
                              <?php


					    $idirescmr=$row_indres['id_indicateur'];


					 // $pan=$row_liste_anneeos2['annee'];
					    mysql_select_db($database_pdar_connexion, $pdar_connexion);
						$query_vares = "SELECT valeur_cible FROM cible_indres_cmr where annee='$anp' and indicateur_rescmr='$idirescmr'";
						$vares = mysql_query($query_vares, $pdar_connexion) or die(mysql_error());
						$row_vares = mysql_fetch_assoc($vares);
						$totalRows_vares = mysql_num_rows($vares);

					  ?>
                              <td ><table align="center">
                                  <tr valign="baseline">
                                    <td>
                                        <input class="form-control" type="text" name="valeur_cible[]" <?php if(!isset($row_vares['valeur_cible'])) {?>style="border-color:#FF0000; text-align:center"<?php }?> value="<?php if(isset($row_vares['valeur_cible'])) echo $row_vares['valeur_cible']; ?>" style="text-align:center" size="8" />                                    </td>
                                  </tr>
                                </table>

                                  <input type="hidden" name="annee[]" value="<?php echo $anp; ?>" />
                                  <input type="hidden" name="ind" value="<?php echo $row_indres['id_indicateur']; ?>" />                              </td>
                              <?php }?>

                            </tr>

                          <?php } while ($row_indres = mysql_fetch_assoc($indres)); mysql_free_result($indres);?>
                          <?php } ?>
                        </table>


<div class="form-actions">
<?php if(isset($_GET["id"])){ ?>
  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />
  <?php } ?>
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">

<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>
<?php } ?>