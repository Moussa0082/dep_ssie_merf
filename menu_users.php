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

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
  $id=($_GET["id"]); $id_s=isset($_GET["id_s"])?$_GET["id_s"]:0;
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_docworkflow = "SELECT responsable_concerne FROM ".$database_connect_prefix."type_doc_workflow WHERE code='$id' ";
  $liste_docworkflow  = mysql_query($query_liste_docworkflow , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_docworkflow = mysql_fetch_assoc($liste_docworkflow);
  $totalRows_liste_docworkflow  = mysql_num_rows($liste_docworkflow);
  $docworkflow_array = array();
  if($totalRows_liste_docworkflow>0){
    $c = array();
    $a = explode('|',$row_liste_docworkflow["responsable_concerne"]);
    foreach($a as $b) $c[] = "'$b'"; $a = implode(',',$c);
  } else $a = 0;
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_user = "SELECT N,nom,prenom,fonction FROM ".$database_connect_prefix."personnel where fonction in ($a) ";
  $liste_user  = mysql_query($query_liste_user , $pdar_connexion) or die(mysql_error());
  $row_liste_user  = mysql_fetch_assoc($liste_user);
  $totalRows_liste_user  = mysql_num_rows($liste_user);
  if($totalRows_liste_user>0)
  { ?>
    <option value="">Selectionnez</option>
    <?php do { ?>
    <option value="<?php echo $row_liste_user['fonction']; ?>" <?php if($row_liste_user['fonction']==$id_s) {echo "SELECTED";} ?>><?php echo $row_liste_user['fonction'];//$row_liste_user['prenom']." ".$row_liste_user['nom']; ?></option>
  <?php }while($row_liste_user  = mysql_fetch_assoc($liste_user));
  }
}

?>