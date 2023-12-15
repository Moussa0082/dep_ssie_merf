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
header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]); $id_s=isset($_GET["id_s"])?$_GET["id_s"]:'';
  $interdit_array = array("classeur","LKEY","annee","projet","structure","id_personnel","date_enregistrement","modifier_le","modifier_par","etat");
  if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y");
?>
  <option value="">Selectionnez</option>
<?php
  if($id=="CMR")
  {
    //cmr
    $cmr_array =array();
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $query_cmr = "SELECT * FROM ".$database_connect_prefix."referentiel_indicateur WHERE type_ref_ind=1 and mode_calcul='Unique' and projet='".$_SESSION["clp_projet"]."' and id_ref_ind not in (SELECT ind FROM ".$database_connect_prefix."indicateur_config WHERE projet='".$_SESSION["clp_projet"]."' and type='CMR') order by intitule_ref_ind";
    //$query_cmr = "SELECT * FROM ".$database_connect_prefix."referentiel_indicateur, ".$database_connect_prefix."produit, ".$database_connect_prefix."indicateur_produit, ".$database_connect_prefix."sous_composante WHERE id_produit=produit and indicateur_prd=id_indicateur_produit and sous_composante=id_sous_composante order by code_sous_composante,code_produit";
    $cmr  = mysql_query_ruche($query_cmr , $pdar_connexion) or die(mysql_error());
    $row_cmr  = mysql_fetch_assoc($cmr);
    $totalRows_cmr  = mysql_num_rows($cmr);
    if($totalRows_cmr>0){ do{ ?>
    <option value="<?php echo $row_cmr["id_ref_ind"]; ?>" <?php if($row_cmr["id_ref_ind"]==$id_s) {echo "SELECTED";} ?>><?php echo $row_cmr["code_ref_ind"].": ".$row_cmr["intitule_ref_ind"]." (".$row_cmr["unite"].")"; ?></option>
<?php }while($row_cmr  = mysql_fetch_assoc($cmr)); }
  }

  if($id=="SYGRI")
  {
    //sygri
    /*$sygri_array =array();
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $query_sygri = "SELECT * FROM ".$database_connect_prefix."indicateur_sygri1_projet,composante WHERE composante=id_composante order by code_composante,ordre";
    $sygri  = mysql_query_ruche($query_sygri , $pdar_connexion) or die(mysql_error());
    $row_sygri  = mysql_fetch_assoc($sygri);
    $totalRows_sygri  = mysql_num_rows($sygri);
    if($totalRows_sygri>0){ do{ ?>
    <option value="<?php echo $row_sygri["id_indicateur_sygri_niveau1_projet"]; ?>" <?php if($row_sygri["id_indicateur_sygri_niveau1_projet"]==$id_s) {echo "SELECTED";} ?>><?php echo $row_sygri["indicateur_sygri_niveau1"]." (".$row_sygri["unite"].")"; ?></option>
<?php }while($row_sygri  = mysql_fetch_assoc($sygri)); }  */
  }

  if($id=="PTBA")
  {
    //PTBA
    $appendice_array =array();
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $query_appendice4 = "SELECT intitule_indicateur_tache,code_activite_ptba,intitule_activite_ptba,id_indicateur_tache, ".$database_connect_prefix."indicateur_tache.annee FROM ".$database_connect_prefix."ptba, ".$database_connect_prefix."indicateur_tache  where id_ptba=id_activite "/*and ".$database_connect_prefix."ptba.annee='$annee'*/." and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' "./*GROUP BY id_activite*/" and id_indicateur_tache not in (SELECT ind FROM ".$database_connect_prefix."indicateur_config WHERE projet='".$_SESSION["clp_projet"]."' and type='PTBA') ORDER BY ".$database_connect_prefix."indicateur_tache.annee,code_activite_ptba,intitule_indicateur_tache";
    $appendice4  = mysql_query_ruche($query_appendice4 , $pdar_connexion) or die(mysql_error());
    $row_appendice4  = mysql_fetch_assoc($appendice4);
    $totalRows_appendice4  = mysql_num_rows($appendice4);
    if($totalRows_appendice4>0){ $i=0; do{ ?>
    <?php if($i==0){ ?>
    <optgroup label="<?php echo $row_appendice4["annee"]; ?>">
    <?php } elseif(isset($a) && $a!=$row_appendice4["annee"]){ ?>
    </optgroup><optgroup label="<?php echo $row_appendice4["annee"]; ?>">
    <?php } ?>
    <option value="<?php echo $row_appendice4["id_indicateur_tache"]; ?>" <?php if($row_appendice4["id_indicateur_tache"]==$id_s) {echo "SELECTED";} ?>><?php echo $row_appendice4["code_activite_ptba"].": ".$row_appendice4["intitule_indicateur_tache"]; ?></option>
<?php $i++; $a = $row_appendice4["annee"]; }while($row_appendice4  = mysql_fetch_assoc($appendice4)); }
?>  </optgroup> <?php } } ?>