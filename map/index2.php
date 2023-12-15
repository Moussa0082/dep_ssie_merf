<?php 
/* Conçue & Développée par:
    *****  *****  *****  *****  *****  *****  *****
    *      *   *  *      *      *   *  *        *
    *****  *   *  *****  *****  *   *  *****    *
    *      *   *  *          *  *   *  *        *
    *      *****  *      *****  *****  *        *
*/
session_start();
$path = '../';
include_once $path . 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"])) {
//header(sprintf("Location: %s", "./"));
  //exit;
}
include_once $path . $config->sys_folder . "/database/db_connexion.php";

$letters = array('\'', ')', '(', '"');
$fruit   = array(' ', ' ', ' ', ' ');
//$text	= 'a p';
//$output  = str_replace($letters, $fruit, $text);

$query_zone_shp="SELECT * FROM t_zone";
  try{
    $liste_zone_shp = $pdar_connexion->prepare($query_zone_shp);
    $liste_zone_shp->execute();
    $zone_shp=$liste_zone_shp->fetchAll();
    $totalRows_zone_shp=$liste_zone_shp->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

$tbl_nom_zone_shp=array();$tbl_file_zone_shp=array();$tbl_color_zone_shp=array();$tbl_titre_zone_shp=array();$tbl_gps_zone_shp=array();$tbl_affiche_zone_shp=array();
if ($totalRows_zone_shp>0) {
    foreach ($zone_shp as $zone_shp) {
        $tbl_nom_zone_shp[]=$zone_shp['nom_zone'];
        $tbl_file_zone_shp[]=$zone_shp['shapefile'];
        $tbl_titre_zone_shp[]=$zone_shp['titre'];
        $tbl_color_zone_shp[]=$zone_shp['couleur'];
        $tbl_gps_zone_shp[]=$zone_shp['coord_gps'];
        $tbl_affiche_zone_shp[]=$zone_shp['afficher_par_defaut'];
    }
}

  $query_liste_feuille="SELECT * FROM t_requete_carte";
  try{
    $liste_feuille = $pdar_connexion->prepare($query_liste_feuille);
    $liste_feuille->execute();
    $feuil=$liste_feuille->fetchAll();
    $totalRows_liste_feuille=$liste_feuille->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

$data='';$tbl_feuil=array();$tbl_feuil_lib=array();
if ($totalRows_liste_feuille>0) {  
  foreach ($feuil as $feuil) {
$sql_verif='SELECT * FROM information_schema.TABLES WHERE (TABLE_SCHEMA = \'agrifarm\') AND (TABLE_NAME = \''.$feuil['Nom_View'].'\')';
try{
        $verif=$pdar_connexion->prepare($sql_verif);
        $verif->execute();
        $tbl_exist=$verif->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
if ($tbl_exist>0) {
   $sql_op='SELECT * FROM '.$feuil['Nom_View'].' WHERE LG IS NOT NULL AND LT IS NOT NULL';
     try{
        $liste_op=$pdar_connexion->prepare($sql_op);
        $liste_op->execute();
        $op=$liste_op->fetchAll();
        $total_op=$liste_op->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
  if ($total_op>0) {
  
  //  echo $total_op; exit;
  	 // $query_liste_chp='select * from t_feuille_ligne where (Code_Feuille = \''.$feuil['fiche_carto'].'\') and Requis="Oui"';
	  //echo $query_liste_chp; exit ;
	    $query_liste_chp = "SELECT t_feuille_ligne.*, t_feuille.Libelle_Feuille, t_feuille.Table_Feuille FROM ".$database_connect_prefix."t_feuille_ligne, t_feuille WHERE t_feuille_ligne.Code_Feuille=t_feuille.Code_Feuille and `Table_Feuille`='".$feuil['fiche_carto']."'";
		 
  try{ 
    $liste_chp = $pdar_connexion->prepare($query_liste_chp,array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $liste_chp->execute();
    $rows_liste_chp = $liste_chp->fetchAll();
    $totalRows_liste_chp=$liste_chp->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  

  
        $nom_tbl=str_replace('t_', 'fiche_', $feuil['fiche_carto']);
        $tbl_feuil[]=$nom_tbl;
        $tbl_feuil_lib[]=$feuil['Nom_Feuille'];
        $data.='var '.$nom_tbl.'ss = {
              "type": "FeatureCollection",
              "name": "'.$feuil['Nom_Feuille'].'",
              "crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } },
              "features": [';
        foreach ($op as $op) {
          $gps=($op['LG']).', '.($op['LT']);
		  
		  		 $nomc=$opnomt=""; 
 /**/ if($totalRows_liste_chp>0){
	  foreach ($rows_liste_chp as $rows_liste_chp1) { /*if(isset($rows_liste_chp1['Requis']) && $rows_liste_chp1['Requis']=='Oui')*/ $nomc=$rows_liste_chp1['Nom_Collone'];
		if(isset($op[$nomc])) { $opnomt.=str_replace($letters, $fruit, $op[$nomc])."  <br/>"; 
echo $opnomt; }
	  }
  }
	
	    }
  }
}
}
}	  
 ?>
        