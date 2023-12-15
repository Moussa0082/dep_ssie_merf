<?php

///////////////////////////////////////////////

/*                 SSE                       */

/*	Conception & DÃ©veloppement: SEYA SERVICES */

///////////////////////////////////////////////

session_start();

include_once 'system/configuration.php';

$config = new Config;



if (!isset ($_SESSION["clp_id"])) {

  header(sprintf("Location: %s", "./"));

  exit;

}

include_once $config->sys_folder . "/database/db_connexion.php";

if(isset($_GET["acteur"]) && $_GET["acteur"]!="0") {$iactget=$_GET["acteur"]; $wheract="AND FIND_IN_SET('$iactget', acteur_conserne)";} else $wheract="";

 if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){

header("Content-Type: application/vnd.ms-excel");

header("Expires: 0");

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header("content-disposition: attachment;filename=taches_activites_ptba.xls"); }

else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){

header("Content-Type: application/vnd.ms-word");

header("Expires: 0");

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header("content-disposition: attachment;filename=taches_activites_ptba.doc"); }

else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="p"){



require_once('./tcpdf/tcpdf.php');



// create new PDF document

$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$PDF_HEADER_TITLE = "Tâches des activités du PTBA";

$PDF_HEADER_STRING = "Tâches des activités du PTBA";



// set document information

$pdf->SetCreator(PDF_CREATOR);

$pdf->SetAuthor('Ruche');

$pdf->SetTitle($PDF_HEADER_TITLE);

$pdf->SetSubject($PDF_HEADER_STRING);

$pdf->SetKeywords('PDF, mission, Tâches des activités du PTBA');



// set default header data //PDF_HEADER_LOGO

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE, $PDF_HEADER_STRING);



// set header and footer fonts

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));



// set default monospaced font

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);



// set margins

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);



// set auto page breaks

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);



// set image scale factor

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



// set some language-dependent strings (optional)

if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {

	require_once(dirname(__FILE__).'/lang/eng.php');

	$pdf->setLanguageArray($l);

}

// set font

//$pdf->SetFont('dejavusans', '', 10);

$pdf->AddPage();



  ob_start(); // turn on output buffering

  /*$_GET["id"]="0001";

  $_GET["down"]=5; */

  include("./print_taches_activite_ptba_pdf.php");

  $content = ob_get_contents(); // get the contents of the output buffer

  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering



$html = utf8_encode($content);

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('taches_activites_ptba.pdf', 'D');

/*

include("pdf/mpdf.php");

$mpdf=new mPDF('win-1252','A4-L','','',15,10,16,10,10,10);//A4 page in portrait for landscape add -L.

$mpdf->useOnlyCoreFonts = true;    // false is default

$mpdf->SetDisplayMode('fullpage');

ob_start();

include "print_etat_recommandation_mission_pdf.php";

$html = ob_get_contents();

ob_end_clean();

$mpdf->WriteHTML($html);

$mpdf->Output();

header("Expires: 0");

header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

header("content-disposition: attachment;filename=Recommandation_mission.pdf"); */

exit;



 } ?>

<?php

$tableauMois=array('01<>Jan<>J','02<>Fev<>F','03<>Mars<>M','04<>Avril<>A','05<>Mai<>M','06<>Juin<>J','07<>Juil<>J','08<>Aout<>A','09<>Sep<>S','10<>Oct<>O','11<>Nov<>N','12<>D&eacute;c<>D');

$tableauMois2= array('Jan','Fev','Mar','Avr','Mai','Juin','Juil','Aout','Sep','Oct','Nov','Dec');

//$tableauMois2= array('J','F','M','A','M','J','J','A','S','O','N','D');

//$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Ao&ucirc;t","Septembre","Octobre","Novembre","D&eacute;cembre");

$annee=(isset($_GET['annee']))?$_GET['annee']:date("Y");

 //$wheract_periode= $wheract_suivi="";

   $wheract_fin=$wheract_periode= $wheract_suivi="";

if(isset($_GET['trim']) && !empty($_GET['trim'])) $trim = $_GET['trim']; else  $trim='%';

$date_t1=$annee."-03-31"; $date_t2=$annee."-06-30"; $date_t3=$annee."-09-30"; $date_t4=$annee."-12-31";

if($trim=="trim1") {$periode=" (Trimestre 1)"; $wheract_periode="AND groupe_tache.date_fin<='$date_t1'"; $wheract_suivi="AND s.date_reelle<='$date_t1'";}

elseif($trim=="trim2") {$periode=" (Trimestre 2)"; $wheract_periode="AND groupe_tache.date_fin<='$date_t2'";  $wheract_suivi="AND s.date_reelle<='$date_t2'";}

elseif($trim=="trim3") {$periode=" (Trimestre 3)"; $wheract_periode="AND groupe_tache.date_fin<='$date_t3'";  $wheract_suivi="AND s.date_reelle<='$date_t3'";}

elseif($trim=="trim4") {$periode=" (Trimestre 4)"; $wheract_periode="AND groupe_tache.date_fin<='$date_t4'";  $wheract_suivi="AND s.date_reelle<='$date_t4'";}

else $periode=" ";



$nbregi=0;

$query_edit_ms = "SELECT code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=2 and projet='".$_SESSION["clp_projet"]."' ORDER BY code asc";

           try{

    $edit_ms = $pdar_connexion->prepare($query_edit_ms);

    $edit_ms->execute();

    $row_edit_ms = $edit_ms ->fetchAll();

    $totalRows_edit_ms = $edit_ms->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }

$composante_array = array();

  if($totalRows_edit_ms>0){  foreach($row_edit_ms as $row_edit_ms1){ 

   // $Nacteur_array[$row_liste_prestataire["code_ugl"]] = $row_liste_prestataire["nom_ugl"];

	$composante_array[] = $row_edit_ms1["code"]."<>".$row_edit_ms1["intitule"]; $nbregi=$nbregi+1;

  }//while($row_liste_prestataire = mysql_fetch_assoc($liste_prestataire));  

  

 /* $rows = mysql_num_rows($liste_prestataire);

  if($rows > 0) {

      mysql_data_seek($liste_prestataire, 0);

	  $row_liste_prestataire = mysql_fetch_assoc($liste_prestataire);

  }*/}

/*

$nbregi=0;

mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_edit_ms = "SELECT code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=1 and projet='".$_SESSION["clp_projet"]."' ORDER BY code asc";

$edit_ms = mysql_query($query_edit_ms, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_edit_ms = mysql_fetch_assoc($edit_ms);

$totalRows_edit_ms = mysql_num_rows($edit_ms);

$composante_array = array();

  if($totalRows_edit_ms>0){  do{

   // $Nacteur_array[$row_liste_prestataire["code_ugl"]] = $row_liste_prestataire["nom_ugl"];

	$composante_array[] = $row_edit_ms["code"]."<>".$row_edit_ms["intitule"]; $nbregi=$nbregi+1;

  }//while($row_liste_prestataire = mysql_fetch_assoc($liste_prestataire));  

  

  while ($row_edit_ms = mysql_fetch_assoc($edit_ms));

  $rows = mysql_num_rows($edit_ms);

  if($rows > 0) {

      mysql_data_seek($edit_ms, 0);

	  $row_edit_ms = mysql_fetch_assoc($edit_ms);

  }}

  */

 $ugl_projet = str_replace("|",",",$_SESSION["clp_projet_ugl"]);//implode(",",(explode("|", $_SESSION["clp_projet_ugl"]));



//$query_liste_prestataire = "SELECT * FROM ".$database_connect_prefix."ugl order by code_ugl";

$query_liste_prestataire= "SELECT id_acteur as code_ugl, nom_acteur as nom_ugl FROM acteur  order by code_ugl";

           try{

    $liste_prestataire = $pdar_connexion->prepare($query_liste_prestataire);

    $liste_prestataire->execute();

    $row_liste_prestataire = $liste_prestataire ->fetchAll();

    $totalRows_liste_prestataire = $liste_prestataire->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }

$acteur_array = $Nacteur_array= array();

  if($totalRows_liste_prestataire>0){  foreach($row_liste_prestataire as $row_liste_prestataire1){ 

    $Nacteur_array[$row_liste_prestataire1["code_ugl"]] = $row_liste_prestataire1["nom_ugl"];

	$acteur_array[] = $row_liste_prestataire1["code_ugl"]."!!".$row_liste_prestataire1["nom_ugl"];

  } }



//if(isset($_GET["acteur"]) && $_GET["acteur"]!=0) {$iactget=$_GET["acteur"]; $wheract_tache="AND ugl='$iactget'"; } else {$wheract_tache=""; $iactget=0;}

$query_tache = "select id_ptba, id_groupe_tache, groupe_tache.responsable, code_tache, proportion, intitule_tache, date_debut, date_fin, sum(tlot) as lot, count(id_tache_ugl) as crp_c   FROM tache_ugl, ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."ptba where id_groupe_tache=tache and id_ptba=id_activite $wheract_tache $wheract  and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."ptba.annee=$annee group by id_ptba, id_groupe_tache, responsable, code_tache, proportion, intitule_tache, date_debut, date_fin ORDER BY code_tache ASC";

//echo $query_tache;

           try{

    $tache = $pdar_connexion->prepare($query_tache);

    $tache->execute();

    $row_tache = $tache ->fetchAll();

    $totalRows_tache = $tache->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }

//echo $totalRows_tache;

//exit;

$tache_array  = array();

$ttt=0; $maxt=0; $idmaxt=0; if($totalRows_tache>0) { foreach($row_tache as $row_tache){ 

$tache_array[$row_tache["id_ptba"]][$row_tache["id_groupe_tache"]] = array("id_ptba"=>$row_tache["id_ptba"],"responsable"=>$row_tache["responsable"],"id_groupe_tache"=>$row_tache["id_groupe_tache"],"code_tache"=>$row_tache["code_tache"],"proportion"=>$row_tache["proportion"],"intitule_tache"=>$row_tache["intitule_tache"],"date_debut"=>$row_tache["date_debut"],"date_fin"=>$row_tache["date_fin"],"lot"=>$row_tache["lot"]);

}   }



//print_r($tache_array);

//exit;



$query_tache_proportion = "SELECT ROUND(avg(s.proportion)) as total, count(id_suivi) as nlotr, id_groupe_tache FROM ptba,".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."suivi_tache s WHERE id_ptba=id_activite and id_groupe_tache=id_tache and s.valider=1 and ptba.annee='$annee' $wheract_tache $wheract $wheract_suivi  GROUP BY id_groupe_tache";

           try{

    $tache_proportion = $pdar_connexion->prepare($query_tache_proportion);

    $tache_proportion->execute();

    $row_tache_proportion = $tache_proportion ->fetchAll();

    $totalRows_tache_proportion = $tache_proportion->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }



$realiser_tache_array =$lot_tache_array = array(); $proportion=0;

if($totalRows_tache_proportion>0){

foreach($row_tache_proportion as $row_tache_proportion){ 

  $realiser_tache_array[$row_tache_proportion["id_groupe_tache"]] = $row_tache_proportion["total"];

   $lot_tache_array[$row_tache_proportion["id_groupe_tache"]] = $row_tache_proportion["nlotr"];

   } }





$query_total_proportion = "SELECT ROUND(sum(s.proportion)) as total, id_activite FROM ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."suivi_tache s WHERE id_groupe_tache=id_tache  and s.valider=1 $wheract_tache  $wheract_suivi  GROUP BY id_activite";

           try{

    $total_proportion = $pdar_connexion->prepare($query_total_proportion);

    $total_proportion->execute();

    $row_total_proportion = $total_proportion ->fetchAll();

    $totalRows_total_proportion = $total_proportion->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }



$prop_tab = array(); //$proportion=0;

if($totalRows_total_proportion>0){ foreach($row_total_proportion as $row_total_proportion){  $prop_tab[$row_total_proportion["id_activite"]] = $row_total_proportion["total"]; } }

$query_tache_cp = "select id_ptba, count(distinct ugl) as crp_c   FROM tache_ugl, ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."ptba where id_groupe_tache=tache and id_ptba=id_activite and tlot>0 $wheract_tache $wheract and ".$database_connect_prefix."ptba.projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."ptba.annee=$annee group by id_ptba";

           try{

    $tache_cp = $pdar_connexion->prepare($query_tache_cp);

    $tache_cp->execute();

    $row_tache_cp = $tache_cp ->fetchAll();

    $totalRows_tache_cp = $tache_cp->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }



$lot_tache_cp_array = array();

 if($totalRows_tache_cp>0) { foreach($row_tache_cp as $row_tache_cp){

$lot_tache_cp_array[$row_tache_cp["id_ptba"]] =$row_tache_cp["crp_c"] ;

}  }



//$query_liste_taux_tache = "SELECT count(id_groupe_tache) as nb_tache, sum(if(statut!='auto',1,0)) as tache_annule,  left(code_activite_ptba,1) as cp, ugl FROM ptba, groupe_tache, suivi_tache WHERE /*id_ptba=groupe_tache.id_activite and id_groupe_tache=tache and tlot>0 and */ ptba.annee='$annee' $wheract_tache $wheract  and ptba.projet='".$_SESSION["clp_projet"]."' group by cp, ugl";
$query_liste_taux_tache = "SELECT SUM(s.proportion) as proportions,  id_groupe_tache, ugl FROM ptba, groupe_tache,suivi_tache s WHERE id_ptba=groupe_tache.id_activite and id_groupe_tache=id_tache  and s.valider=1  $wheract_tache $wheract $wheract_suivi and ptba.annee='$annee' and ptba.projet='".$_SESSION["clp_projet"]."' group by id_groupe_tache, ugl";
           try{

    $liste_taux_tache = $pdar_connexion->prepare($query_liste_taux_tache);

    $liste_taux_tache->execute();

    $row_liste_taux_tache = $liste_taux_tache ->fetchAll();

    $totalRows_liste_taux_tache = $liste_taux_tache->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }



$nb_tache_cp =$total_tache_array=$total_tache_ug_array=$nb_tache_cp_annule =$total_tache_annule_array= array();

if($totalRows_liste_taux_tache>0){ 

foreach($row_liste_taux_tache as $row_liste_taux_tache){

if(!isset($total_tache_array[$row_liste_taux_tache["cp"]])) $total_tache_array[$row_liste_taux_tache["cp"]]=0;

if(!isset($total_tache_ug_array[$row_liste_taux_tache["ugl"]])) $total_tache_ug_array[$row_liste_taux_tache["ugl"]]=0;

if(!isset($total_tache_annule_array[$row_liste_taux_tache["cp"]])) $total_tache_annule_array[$row_liste_taux_tache["cp"]]=0;



  $nb_tache_cp[$row_liste_taux_tache["cp"]][$row_liste_taux_tache["ugl"]]=$row_liste_taux_tache["nb_tache"]; 

   $nb_tache_cp_annule[$row_liste_taux_tache["cp"]][$row_liste_taux_tache["ugl"]]=$row_liste_taux_tache["tache_annule"]; 

   

  $total_tache_array[$row_liste_taux_tache["cp"]]=$total_tache_array[$row_liste_taux_tache["cp"]]+$row_liste_taux_tache["nb_tache"]; 

    $total_tache_ug_array[$row_liste_taux_tache["ugl"]]=$total_tache_ug_array[$row_liste_taux_tache["ugl"]]+$row_liste_taux_tache["nb_tache"]; 

    $total_tache_annule_array[$row_liste_taux_tache["cp"]]=$total_tache_annule_array[$row_liste_taux_tache["cp"]]+$row_liste_taux_tache["tache_annule"]; 

 } } 

//print_r($total_tache_array);

//exit;



// Taux tache

$query_liste_taux_tache_ptba = "SELECT SUM(s.proportion) as proportions,  id_groupe_tache, ugl FROM ptba, groupe_tache,suivi_tache s WHERE id_ptba=groupe_tache.id_activite and id_groupe_tache=id_tache  and s.valider=1  $wheract_tache $wheract $wheract_suivi and ptba.annee='$annee' and ptba.projet='".$_SESSION["clp_projet"]."' group by id_groupe_tache, ugl";

           try{

    $liste_taux_tache_ptba = $pdar_connexion->prepare($query_liste_taux_tache_ptba);

    $liste_taux_tache_ptba->execute();

    $row_liste_taux_tache_ptba = $liste_taux_tache_ptba ->fetchAll();

    $totalRows_liste_taux_tache_ptba = $liste_taux_tache_ptba->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }



$ttache_tab = array();

if($totalRows_liste_taux_tache_ptba>0){

foreach($row_liste_taux_tache_ptba as $row_liste_taux_tache_ptba){

 $ttache_tab[$row_liste_taux_tache_ptba["id_groupe_tache"]][$row_liste_taux_tache_ptba["ugl"]]=$row_liste_taux_tache_ptba["proportions"];

}} 





//Tache realise

$query_liste_taux_tache1 = "SELECT count(id_groupe_tache) as nb_tache, sum(if(statut!='auto',1,0)) as tache_annule,  left(code_activite_ptba,2) as cp, ugl FROM ptba, groupe_tache, tache_ugl WHERE id_ptba=groupe_tache.id_activite and id_groupe_tache=tache and ptba.annee='$annee' $wheract_tache $wheract  and ptba.projet='".$_SESSION["clp_projet"]."' group by cp, ugl";

           try{

    $liste_taux_tache1 = $pdar_connexion->prepare($query_liste_taux_tache1);

    $liste_taux_tache1->execute();

    $row_liste_taux_tache1 = $liste_taux_tache1 ->fetchAll();

    $totalRows_liste_taux_tache1 = $liste_taux_tache1->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }



$rnb_tache_cp =$rtotal_tache_array=$rtotal_tache_ug_array= array();

if($totalRows_liste_taux_tache1>0){ 

foreach($row_liste_taux_tache1 as $row_liste_taux_tache1){

if(!isset($rtotal_tache_array[$row_liste_taux_tache1["cp"]])) $rtotal_tache_array[$row_liste_taux_tache1["cp"]]=0;

if(!isset($rtotal_tache_ug_array[$row_liste_taux_tache1["ugl"]])) $rtotal_tache_ug_array[$row_liste_taux_tache1["ugl"]]=0;

  $rnb_tache_cp[$row_liste_taux_tache1["cp"]][$row_liste_taux_tache1["ugl"]]=$row_liste_taux_tache1["nb_tache"]; 

    $rtotal_tache_array[$row_liste_taux_tache1["cp"]]=$rtotal_tache_array[$row_liste_taux_tache1["cp"]]+$row_liste_taux_tache1["nb_tache"]; 

    $rtotal_tache_ug_array[$row_liste_taux_tache1["ugl"]]=$rtotal_tache_ug_array[$row_liste_taux_tache1["ugl"]]+$row_liste_taux_tache1["nb_tache"]; 

 } } 







/**/



// Taux tache

$query_liste_taux_tache_act = "SELECT id_ptba, left(code_activite_ptba,2) as cp, tsuivi, lot_cible, cible.id_groupe_tache, cible.ugl FROM 

(SELECT ptba.id_ptba, code_activite_ptba, groupe_tache.id_groupe_tache, ugl, sum(tlot) as lot_cible  FROM ptba INNER JOIN (groupe_tache INNER JOIN tache_ugl ON groupe_tache.id_groupe_tache= tache_ugl.tache ) ON ptba.id_ptba = groupe_tache.id_activite where ptba.annee='$annee' $wheract_tache $wheract and tlot>0 and ptba.projet='".$_SESSION["clp_projet"]."' group by  groupe_tache.id_groupe_tache, ugl, ptba.id_ptba, code_activite_ptba) AS cible

 INNER JOIN (SELECT groupe_tache.id_groupe_tache, max(s.lot) AS tsuivi, ugl FROM ptba INNER JOIN (groupe_tache LEFT JOIN suivi_tache s ON groupe_tache.id_groupe_tache= s.id_tache) ON ptba.id_ptba = groupe_tache.id_activite where ptba.annee='$annee' and s.valider=1 $wheract  $wheract_suivi and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY groupe_tache.id_groupe_tache, ugl) AS suivi ON cible.id_groupe_tache= suivi.id_groupe_tache and cible.ugl= suivi.ugl ";

           try{

    $liste_taux_tache_act = $pdar_connexion->prepare($query_liste_taux_tache_act);

    $liste_taux_tache_act->execute();

    $row_liste_taux_tache_act = $liste_taux_tache_act ->fetchAll();

    $totalRows_liste_taux_tache_act = $liste_taux_tache_act->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }



$rnb_tache_cp =$rtotal_tache_array=$rtotal_tache_ug_array= array();

if($totalRows_liste_taux_tache_act>0){

foreach($row_liste_taux_tache_act as $row_liste_taux_tache_act){

if(!isset($rtotal_tache_array[$row_liste_taux_tache_act["cp"]])) $rtotal_tache_array[$row_liste_taux_tache_act["cp"]]=0;

if(!isset($rtotal_tache_ug_array[$row_liste_taux_tache_act["ugl"]])) $rtotal_tache_ug_array[$row_liste_taux_tache_act["ugl"]]=0;

if(!isset($rnb_tache_cp[$row_liste_taux_tache_act["cp"]][$row_liste_taux_tache_act["ugl"]])) $rnb_tache_cp[$row_liste_taux_tache_act["cp"]][$row_liste_taux_tache_act["ugl"]]=0;



 if($row_liste_taux_tache_act["lot_cible"]==$row_liste_taux_tache_act["tsuivi"])

 {

  $rnb_tache_cp[$row_liste_taux_tache_act["cp"]][$row_liste_taux_tache_act["ugl"]]=$rnb_tache_cp[$row_liste_taux_tache_act["cp"]][$row_liste_taux_tache_act["ugl"]]+1; 

  $rtotal_tache_array[$row_liste_taux_tache_act["cp"]]=$rtotal_tache_array[$row_liste_taux_tache_act["cp"]]+1; 

  $rtotal_tache_ug_array[$row_liste_taux_tache_act["ugl"]]=$rtotal_tache_ug_array[$row_liste_taux_tache_act["ugl"]]+1; 

 }

} } 





// Taux tache

$query_liste_taux_tache_cp = "select avg(if(tauxx>0,tauxx,0)) as taux_cp, left(code_activite_ptba,2) as cp from (SELECT id_ptba, code_activite_ptba, sum(tsuivi)/avg(tcible) as tauxx FROM (SELECT ptba.id_ptba, code_activite_ptba, groupe_tache.id_groupe_tache, count(distinct ugl) AS tcible

FROM ptba INNER JOIN (groupe_tache INNER JOIN tache_ugl ON groupe_tache.id_groupe_tache= tache_ugl.tache

) ON ptba.id_ptba = groupe_tache.id_activite where ptba.annee='$annee' $wheract_tache $wheract  and tlot>0 and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY ptba.id_ptba, code_activite_ptba, groupe_tache.id_groupe_tache) AS cible

left JOIN (SELECT groupe_tache.id_groupe_tache, SUM(s.proportion) AS tsuivi FROM ptba INNER JOIN (groupe_tache

LEFT JOIN suivi_tache s ON groupe_tache.id_groupe_tache= s.id_tache  ) ON ptba.id_ptba = groupe_tache.id_activite where ptba.annee='$annee' $wheract $wheract_suivi and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY groupe_tache.id_groupe_tache) AS suivi ON cible.id_groupe_tache= suivi.id_groupe_tache GROUP BY id_ptba) as r1 group by cp";

           try{

    $liste_taux_tache_cp = $pdar_connexion->prepare($query_liste_taux_tache_cp);

    $liste_taux_tache_cp->execute();

    $row_liste_taux_tache_cp = $liste_taux_tache_cp ->fetchAll();

    $totalRows_liste_taux_tache_cp = $liste_taux_tache_cp->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }



$ttache_cp_tab = array();

if($totalRows_liste_taux_tache_cp>0){

foreach($row_liste_taux_tache_cp as $row_liste_taux_tache_cp){ $ttache_cp_tab[$row_liste_taux_tache_cp["cp"]]=$row_liste_taux_tache_cp["taux_cp"];

} } 



//gestion revision

 /* mysql_select_db($database_pdar_connexion, $pdar_connexion);

  $query_liste_mission = "SELECT * FROM ".$database_connect_prefix."version_ptba WHERE id_version_ptba='$annee'  ";

  $liste_mission  = mysql_query_ruche($query_liste_mission , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

  $row_liste_mission = mysql_fetch_assoc($liste_mission);

  $totalRows_liste_mission  = mysql_num_rows($liste_mission);

  $lib_version_ptba=$row_liste_mission['annee_ptba']." ".$row_liste_mission['version_ptba'];*/

 

// Taux tache

$query_liste_taux_activite_cp = "select  total, left(code_activite_ptba,2) as cp from (SELECT SUM(if(n_lot>0 && valider=1, proportion*jalon/n_lot,0)) as total, id_ptba, code_activite_ptba FROM ptba inner join  groupe_tache ON ptba.id_ptba = groupe_tache.id_activite where ptba.annee='$annee' $wheract and ptba.projet='".$_SESSION["clp_projet"]."' GROUP BY id_ptba, code_activite_ptba) as r1";

           try{

    $liste_taux_activite_cp = $pdar_connexion->prepare($query_liste_taux_activite_cp);

    $liste_taux_activite_cp->execute();

    $row_liste_taux_activite_cp = $liste_taux_activite_cp ->fetchAll();

    $totalRows_liste_taux_activite_cp = $liste_taux_activite_cp->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }



$tactivite_cp_tab =$tactivite_encour_cp_tab = array();

if($totalRows_liste_taux_activite_cp>0){

foreach($row_liste_taux_activite_cp as $row_liste_taux_activite_cp){

if(!isset($tactivite_cp_tab[$row_liste_taux_activite_cp["cp"]])) $tactivite_cp_tab[$row_liste_taux_activite_cp["cp"]]=0;

if(!isset($tactivite_encour_cp_tab[$row_liste_taux_activite_cp["cp"]])) $tactivite_encour_cp_tab[$row_liste_taux_activite_cp["cp"]]=0;

if($row_liste_taux_activite_cp["total"]<100 && $row_liste_taux_activite_cp["total"]>0) $tactivite_encour_cp_tab[$row_liste_taux_activite_cp["cp"]]++; elseif($row_liste_taux_activite_cp["total"]>0) $tactivite_cp_tab[$row_liste_taux_activite_cp["cp"]]++;

} }  



// Taux tache

$query_liste_taux_tache_ptba = "select  count(id_ptba) as nbact, left(code_activite_ptba,2) as cp from (SELECT code_activite_ptba, id_ptba , max(groupe_tache.date_fin) as date_fin  FROM ptba, groupe_tache WHERE id_ptba=groupe_tache.id_activite  $wheract  and ptba.annee='$annee' and ptba.projet='".$_SESSION["clp_projet"]."' group by code_activite_ptba, id_ptba)as r2 where 1=1 $wheract_fin group by cp ";
//echo $query_liste_taux_tache_ptba; exit;
           try{

    $liste_taux_tache_ptba = $pdar_connexion->prepare($query_liste_taux_tache_ptba);

    $liste_taux_tache_ptba->execute();

    $row_liste_taux_tache_ptba = $liste_taux_tache_ptba ->fetchAll();

    $totalRows_liste_taux_tache_ptba = $liste_taux_tache_ptba->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }



$tact_prevu_tab = array();

if($totalRows_liste_taux_tache_ptba>0){

foreach($row_liste_taux_tache_ptba as $row_liste_taux_tache_ptba){

 $tact_prevu_tab[$row_liste_taux_tache_ptba["cp"]]=$row_liste_taux_tache_ptba["nbact"];

}} 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<?php if(!isset($_GET["down"])){  ?>

<head>

  <title><?php print $config->sitename; ?></title>

  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />
  <?php } ?>

  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <?php if(!isset($_GET["down"])){  ?>

  <meta name="keywords" content="<?php print $config->MetaKeys; ?>" />

  <meta name="description" content="<?php print $config->MetaDesc; ?>" />

  <meta name="author" content="<?php print $config->MetaAuthor; ?>" />

  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder; ?>/login.css" rel="stylesheet" type="text/css"/>

  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">

  <link href='<?php print $config->theme_folder; ?>/css.css' rel='stylesheet' type='text/css'>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>

  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>

  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/lodash.compat.min.js"></script>

  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>

  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>

  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>

  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/breakpoints.js"></script>

  <script type="text/javascript" src="plugins/respond/respond.min.js"></script>

  <script type="text/javascript" src="plugins/cookie/jquery.cookie.min.js"></script>

  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.min.js"></script>

  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>

  <script type="text/javascript" src="plugins/blockui/jquery.blockUI.min.js"></script>

  <script type="text/javascript" src="plugins/pickadate/picker.js"></script>

  <script type="text/javascript" src="plugins/pickadate/picker.date.js"></script>

  <script type="text/javascript" src="plugins/pickadate/picker.time.js"></script>

  <script type="text/javascript" src="plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>

  <script type="text/javascript" src="plugins/fullcalendar/fullcalendar.min.js"></script>

  <script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>

  <script type="text/javascript" src="plugins/noty/layouts/top.js"></script>

  <script type="text/javascript" src="plugins/noty/themes/default.js"></script>

  <script type="text/javascript" src="plugins/uniform/jquery.uniform.min.js"></script>

  <script type="text/javascript" src="plugins/select2/select2.min.js"></script>

  <script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>

  <script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>

  <script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/app.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/plugins.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/plugins.form-components.js"></script>

<!--

  <script type="text/javascript" src="<?php print $config->

script_folder; ?>/custom.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/pages_calendar.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_filled_blue.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_simple.js"></script>-->

 <script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>

 <script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder; ?>/login.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder; ?>/myscript.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/ui_general.js"></script>

 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>

</head>

<?php }  ?>

<body>

 <header class="header navbar navbar-fixed-top" role="banner">

    <?php if(!isset($_GET["down"])) include_once("includes/header.php"); ?>

 </header>

<div id="container">

    <div id="sidebar" class="sidebar-fixed">

        <div id="sidebar-content">

            <?php if(!isset($_GET["down"])) include_once("includes/menu_top.php"); ?>

        </div>

        <div id="divider" class="resizeable"></div>

    </div>



    <div id="content">

        <div class="container">

            <div class="crumbs">

                <?php if(!isset($_GET["down"])) include_once("includes/sous_menu.php"); ?>

            </div>

        <div class="page-header">

            <div class="p_top_5">

<!-- Site contenu ici -->

<style>#sp_hr {margin:0px; }

.r_float{float: right;}

.Style11 { font-weight: bold;color: #FFFFFF;}

.well {margin-bottom: 5px;}

#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {

  border-spacing: 0px !important; border-collapse: collapse; font-size: small;

} .table tbody tr td {vertical-align: middle; } .marquer{background: #EBEBEB!important; }

</style>

<div class="contenu">

<?php if(!isset($_GET["down"])){  ?>

<form name="form<?php echo $annee; ?>" id="form<?php //echo $annee; ?>" method="get" action="<?php echo "recap_taches_activite_ptba_scp.php?annee=".$annee; ?>" class="pull-left">
 <select name="acteur" onchange="form<?php echo $annee; ?>.submit();" style="background-color: #FFFF00; padding: 7px;" class="btn p11">
            <option value="">-- Choisissez une partie --</option>
           <?php   if($totalRows_liste_prestataire>0) { foreach($row_liste_prestataire as $row_liste_prestataire){?>
            <option <?php if(isset($id_ms) && $id_ms==$row_liste_prestataire['id_acteur']) {echo 'SELECTED="selected"';  $nom=$row_liste_prestataire['nom_ugl'];}  ?> value="<?php echo  $row_liste_prestataire['code_ugl']; ?>"> <?php echo "<b>".$row_liste_prestataire['nom_ugl']."</b> ";?>
            </option>
            <?php } }?>

 <option value="0">Toutes les activités</option>
  </select>
  <input type="hidden" name="annee" value="<?php echo $annee; ?>" />

</form>



<div class="well well-sm r_float"><div class="r_float"><a href="./s_programmation.php" class="button">Retour</a></div>

<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>

<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>

<div class="r_float" style="margin-right: 20px;"><a target="_blank" title="Imprimer" href="<?php echo $editFormAction."&down=1"; ?>" class="button"><img src="./images/print.png" width='20' height='20' alt='Modifier' /></a></div>

</div>

<div class="clear h0">&nbsp;</div>

<?php } else { ?>



<center><?php //include "./includes/print_header.php"; ?></center>



<?php } ?>

<div class="well well-sm"><strong>RECAPITULATIF DES T&Acirc;CHES ET DES ACTIVITES DU PTBA <?php //echo "$annee"; ?>&nbsp;&nbsp; <span style="background-color:#FFCC33"><?php if(isset($_GET["acteur"]) && $_GET["acteur"]!=0 && isset( $Nacteur_array[$_GET["acteur"]])) echo  "<u>".$Nacteur_array[$_GET["acteur"]]."</u>"; ?> <?php echo $periode; ?></span></strong></div>


<!--
<table width="20%" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive">

  <?php if($totalRows_liste_prestataire>0) {$total_an=$i=0; $t=0; $p2=$p1="j"; ?>

  <thead>

    <tr>

      <th align="center" nowrap="nowrap">&nbsp;</th>

      <th align="center"><div align="left"><strong>Unit&eacute;s de gestion </strong></div></th>

	  

	      <?php foreach($composante_array as $vregion){?>



                <th align="center" colspan="3" >



                  <div align="center">

                    <?php   $aregion = explode('<>',$vregion);  $iregion = $aregion[0]; echo "Composante ".$aregion[0];  ?>

                    </div></th>



                <?php } ?>

				

      

      <th bgcolor="#333333">&nbsp;</th>

      <th colspan="3" bgcolor="#F0F0F0"><div align="center"><strong>R&eacute;capitulatif</strong></div></th>

      </tr>

    <tr>

      <th align="center" nowrap="nowrap">&nbsp;</th>

      <th align="center">&nbsp;</th>

      <?php foreach($composante_array as $vregion){?>

                <th align="center" style="background-color:#FFCC00" ><div align="center">  Réalisées</div></th>

                <th align="center" ><div align="center">  Non Réalisées</div></th>

                <th align="center" ><div align="center">  Total</div></th>



                <?php } ?>

      <th bgcolor="#333333">&nbsp;</th>

      <th align="center" ><div align="center"> R&eacute;alis&eacute;es</div></th>

      <th align="center" ><div align="center"> Non R&eacute;alis&eacute;es</div></th>

      <th align="center" ><div align="center"> Total</div></th>

    </tr>

  </thead>

  <?php foreach($row_liste_prestataire as $row_liste_prestataire){ //if(isset($liste_rub_array[$row_liste_rec['personnel']])) { ?>

  <tr>

    <td><span class="Style4">

      <?php //if(isset($liste_rub_array[$row_liste_rec['personnel']])) echo $liste_rub_array[$row_liste_rec['personnel']]; ?>

    </span></td>

    <td><div align="left"><strong><?php echo $row_liste_prestataire['nom_ugl']; ?></strong></div></td>

   <?php foreach($composante_array as $vregion){?>



                <td align="center" ><div align="center">

                  <?php   $aregion = explode('<>',$vregion);  $iregion = $aregion[0]; if(isset($rnb_tache_cp[$iregion][$row_liste_prestataire['code_ugl']])){ echo  $rnb_tache_cp[$iregion][$row_liste_prestataire['code_ugl']]; $nr=$rnb_tache_cp[$iregion][$row_liste_prestataire['code_ugl']];} else $nr=0; ?>

                </div></td>

                <td align="center" ><div align="center">

                  <?php    if(isset($nb_tache_cp[$iregion][$row_liste_prestataire['code_ugl']])) echo  $nb_tache_cp[$iregion][$row_liste_prestataire['code_ugl']]-$nr;  ?>

                </div></td>

			    <td align="center" > <?php    if(isset($nb_tache_cp[$iregion][$row_liste_prestataire['code_ugl']])) echo  $nb_tache_cp[$iregion][$row_liste_prestataire['code_ugl']];  ?></td>



                <?php } ?>

    <td bgcolor="#333333" style="background-color:#333333">&nbsp;</td>

    <td bgcolor="#F0F0F0"><div align="center"><strong>

      <?php   if(isset($rtotal_tache_ug_array[$row_liste_prestataire['code_ugl']])) {echo  $rtotal_tache_ug_array[$row_liste_prestataire['code_ugl']]; $trug=$rtotal_tache_ug_array[$row_liste_prestataire['code_ugl']];} else $trug=0;  ?>

    </strong></div></td>

    <td bgcolor="#F0F0F0"><div align="center"><strong>

      <?php   if(isset($total_tache_ug_array[$row_liste_prestataire['code_ugl']])) echo  $total_tache_ug_array[$row_liste_prestataire['code_ugl']]-$trug;  ?>

    </strong></div></td>

    <td bgcolor="#F0F0F0"><div align="center"><strong>

      <?php   if(isset($total_tache_ug_array[$row_liste_prestataire['code_ugl']])) echo  $total_tache_ug_array[$row_liste_prestataire['code_ugl']];  ?>

    </strong></div></td>

  </tr>

 

  <?php }  $tgtr=$tgt=$tgta=0; ?>

   <tr>

    <td>&nbsp;</td>

    <td bgcolor="#F0F0F0"><div align="right"><strong>Total</strong></div></td>

    <?php foreach($composante_array as $vregion){ ?>



                 <td align="center" ><div align="center"><strong>

                   <?php   $aregion = explode('<>',$vregion);  $iregion = $aregion[0];

				 if(isset($rtotal_tache_array[$iregion])) {echo  $rtotal_tache_array[$iregion]; $tgtr=$tgtr+$rtotal_tache_array[$iregion]; $trcp=$rtotal_tache_array[$iregion];} else $trcp=0;  ?>

                 </strong></div></td>

                <td align="center" ><div align="center"><strong>

                  <?php   

				 if(isset($total_tache_array[$iregion])) {echo  $total_tache_array[$iregion]-$trcp;}  ?>

                </strong></div></td>

				<td align="center" bgcolor="#F0F0F0" > <strong>

                  <?php   

				 if(isset($total_tache_array[$iregion])) {echo  $total_tache_array[$iregion]; $tgt=$tgt+$total_tache_array[$iregion];}  ?>

                </strong></td>



                <?php } ?>

    <td bgcolor="#333333"><div align="center"></div></td>

    <td bgcolor="#F0F0F0"><div align="center"><strong><?php echo $tgtr; ?></strong></div></td>

    <td bgcolor="#F0F0F0"><div align="center"><strong><?php echo $tgt-$tgtr; ?></strong></div></td>

    <td bgcolor="#F0F0F0"><div align="center"><strong><?php echo $tgt; ?></strong></div></td>

   </tr>

   <tr>

     <td>&nbsp;</td>

     <td><div align="right"><strong>T&acirc;ches annul&eacute;es </strong></div></td>

     <?php foreach($composante_array as $vregion){?>

	 <td colspan="3" > <div align="center" style="background-color:#CCCCCC"><strong>&nbsp;&nbsp;<?php   $aregion = explode('<>',$vregion);  $iregion = $aregion[0];

				 if(isset($total_tache_annule_array[$iregion])) {echo  $total_tache_annule_array[$iregion]; $tgta=$tgta+$total_tache_annule_array[$iregion];} ?>&nbsp;&nbsp;</strong></div></td>



                <?php } ?>

     <td bgcolor="#333333"><div align="center"></div></td>

     <td colspan="3"><div align="center"><strong><?php echo $tgta; ?></strong></div></td>

     </tr>

  <?php } else { ?>

  <tr>

    <td colspan="<?php echo $nbregi+4; ?>"><div align="center"><span class="Style4"><em><strong>Aucune unité de gestion! </strong></em></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span></div></td>

  </tr>

  <?php }  ?>

</table>-->

</div>

<!-- Fin Site contenu ici -->

            </div>

            <p>Tableau suivi des activit&eacute;s </p>

            <table width="20%" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive">

              <?php if($totalRows_edit_ms>0) {$total_an=$i=0; $t=0; $p2=$p1="j"; $tna_prevu=$tnr=$tnencour=0 ?>

              <thead>

                <tr>

                  <th align="center" nowrap="nowrap">&nbsp;</th>

                  <th align="center" nowrap="nowrap">&nbsp;</th>

                  <th colspan="4" align="center" nowrap="nowrap"><div align="center"></div></th>

                  <th align="center" nowrap="nowrap">&nbsp;</th>

                </tr>

                <tr>

                  <th align="center" nowrap="nowrap">&nbsp;</th>

                  <th align="center">Sous/Composante</th>

                  <th align="center" ><div align="center"> Total pr&eacute;vu</div></th>

                  <th align="center" style="background-color:#FFCC00" ><div align="center"> Activit&eacute;s r&eacute;alis&eacute;es</div></th>

                  <th align="center" ><div align="center"> Activit&eacute;s non r&eacute;alis&eacute;es </div></th>

                  <th align="center" ><div align="center"> Non demarr&eacute; </div></th>

                  <th >&nbsp;</th>

                </tr>

              </thead>

              <?php foreach($row_edit_ms as $row_edit_ms){ $na_prevu=$nr=$nencour=0; //if(isset($liste_rub_array[$row_liste_rec['personnel']])) { ?>

              <tr>

                <td><span class="Style4">

                  <?php //if(isset($liste_rub_array[$row_liste_rec['personnel']])) echo $liste_rub_array[$row_liste_rec['personnel']]; ?>

                </span></td>

                <td><div align="left"><strong><?php echo $row_edit_ms['code'].": ".$row_edit_ms['intitule']; ?></strong></div></td>

                <td align="center" ><?php    if(isset($tact_prevu_tab[$row_edit_ms["code"]])) {echo  $tact_prevu_tab[$row_edit_ms["code"]]; $na_prevu=$na_prevu+$tact_prevu_tab[$row_edit_ms["code"]];}  ?></td>

                <td align="center" ><div align="center">

                    <?php if(isset($tactivite_cp_tab[$row_edit_ms["code"]])){ echo  $tactivite_cp_tab[$row_edit_ms["code"]]; $nr=$nr+$tactivite_cp_tab[$row_edit_ms["code"]];} else $nr=0; ?>

                </div></td>

                <td align="center" ><div align="center">

                    <?php if(isset($tactivite_encour_cp_tab[$row_edit_ms["code"]])){ echo  $tactivite_encour_cp_tab[$row_edit_ms["code"]]; $nencour=$nencour+$tactivite_encour_cp_tab[$row_edit_ms["code"]];}  ?>

                </div></td>

                <td align="center" ><?php    echo  $na_prevu-$nr-$nencour; $tna_prevu=$tna_prevu+$na_prevu; $tnr=$tnr+$nr; $tnencour=$tnencour+$nencour;  ?></td>

                <td  >&nbsp;</td>

              </tr>

              <?php }  $tgtr=$tgt=$tgta=0; 

			  

			  ?>

              <tr>

                <td>&nbsp;</td>

                <td bgcolor="#F0F0F0"><div align="right"><strong>Total</strong></div></td>

                <td align="center" ><strong>

                  <?php  echo $tna_prevu  ?>

                </strong></td>

                <td align="center" ><div align="center"><strong>

                    <?php  echo $tnr  ?>

                </strong></div></td>

                <td align="center" ><div align="center"><strong> <?php echo $tnencour;	  ?> </strong></div></td>

                <td align="center" bgcolor="#F0F0F0" ><?php    echo  $tna_prevu-$tnr-$tnencour;  ?></td>

                <td ><div align="center"></div></td>

              </tr>

              <tr>

                <td colspan="7">&nbsp;</td>

              </tr>

              <?php } else { ?>

              <tr>

                <td colspan="<?php echo $nbregi+4; ?>"><div align="center"><span class="Style4"><em><strong>Aucune composante ! </strong></em></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span></div></td>

              </tr>

              <?php }  ?>

            </table>

            <p>&nbsp;</p>

        </div>



        </div>

    </div>   <?php if(!isset($_GET["down"])) include_once 'modal_add.php'; ?>

    <?php include_once("includes/footer.php"); ?>

</div>

                   

</body>

</html>