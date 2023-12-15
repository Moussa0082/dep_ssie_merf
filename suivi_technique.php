<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
?>

<?php
$plog=$_SESSION["clp_id"];
$date=date("Y-m-d");
if(isset($_SESSION['annee']) && !isset($_GET['annee'])) {$annee=$_SESSION['annee'];}
elseif(isset($_GET['annee'])) {$annee=$_GET['annee']; $_SESSION['annee']=$annee;}
elseif(!isset($_GET['annee']) && isset($_SESSION['annee'])) $annee=$_SESSION['annee'];
else $annee=date("Y");

if(isset($_SESSION["cp"]) && !isset($_GET['cp'])){$cp=$_SESSION["cp"];}
elseif(isset($_GET['cp'])){$cp=$_GET['cp']; $_SESSION["cp"]=$cp; }
elseif(!isset($_GET['cp']) && isset($_SESSION['cp'])) $_GET['cp']=$cp;


if(isset($_GET["id_sup"]))
{
  $id=$_GET["id_sup"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_sup_act = "DELETE FROM $cp WHERE LKEY='$id'";
  $Result1 = mysql_query($query_sup_act, $pdar_connexion) or die(mysql_error());
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok&annee=$annee&cp=$cp";
  else $insertGoTo .= "?del=no&annee=$annee&cp=$cp";
  mysql_free_result($Result1);
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{ //ptba

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {   $id_cp=$_POST["id_cp"];
	$form= $_POST['form'];
	//var_dump($form);

	$xmlfile= "table.xml";

	$nomtable= strtolower(trim(str_replace(" ","",$_POST['nomtable'])));
	$noms= $form['nom'];
    $lnom = $_POST['lnomtable'];
    $lib= $form['lib'];
	$types= $form['type'];
    $choix= $form['choix'];
	$estnulls= array();
	if(isset($form['estnull']))
	    $estnulls= $form['estnull'];

    $show= array();
	if(isset($form['show']))
	    $show= $form['show'];



	$chxml= "<?xml version=\"1.0\" encoding=\"iso-8859-1\" standalone=\"yes\"?>\n";
	$chxml.="<tables>";

	$chxml.= "<table nom=\"fiche_{$nomtable}\">\n";

    $cfg = $cfg_choix = $libelle = "";
    $array_type = array('INT','VARCHAR','CHAR','DOUBLE','DATE', 'DATETIME', 'YEAR', 'TEXT');

	for($i= 0; $i< count($noms); $i++){
		$nom= trim(str_replace(" ","",$noms[$i]));
		$type= $types[$i];

        if(!in_array($type,$array_type)) $type = "TEXT";

		if(isset($estnulls[$i])	)
		   $estnull= $estnulls[$i];
		else $estnull="";

        if(isset($show[$i])	&& $show[$i]==1)
		   $cfg .= $nom.",";

        if(isset($choix[$i]) && !empty($choix[$i]))
		   $cfg_choix .= $nom.";".$choix[$i].",";


		$chxml.="<field nom=\"{$nom}\" type=\"{$type}\"";
		if ($estnull!="")
			$chxml.=" estnull=\"{$estnull}\"";
		$chxml.="></field>\n";

        $libelle.=$nom."=".$lib[$i].",";
	}

    $libelle .= $nomtable."=".$lnom.",";

    $chxml.="<field nom=\"annee\" type=\"INT\" estnull=\"NOT NULL\"></field>\n";

	$chxml.="</table>";
	$chxml.="</tables>";
	// tester si le fichier existe
	//if(!file_exists($xmlfile)){ // la table n'existe pas
		// on écrit le contenu xml dans le fichier
		$bool= file_put_contents($xmlfile, $chxml);
		if($bool){

			include_once 'XmlBase.php';

			$q= xmltobase($xmlfile); //$q contient la script de création de la table


		}


$personnel=$_SESSION['clp_id'];
    $insertSQL = $q;
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());


    $insertSQL2 = "INSERT INTO fiche_config (`table`,`show`,choix, libelle) VALUES('fiche_$nomtable','$cfg','$cfg_choix',\"$libelle\")";
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result2 = mysql_query($insertSQL2, $pdar_connexion) or die(mysql_error());

    $insertGoTo = $_SERVER['PHP_SELF']; $id_cp="fiche_$nomtable";
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    $insertGoTo .= "&annee=$annee&cp=$id_cp";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

    if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = $_POST["MM_delete"];
    $insertSQL = "DROP table `$id`";

    $insertSQL1 = sprintf("DELETE from fiche_config WHERE `table`=%s",
                         GetSQLValueString($id, "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result0 = mysql_query($insertSQL1, $pdar_connexion) or die(mysql_error());

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1){ unset($_SESSION["cp"]);  $insertGoTo .= "?del=ok"; }  else $insertGoTo .= "?del=no";
    $insertGoTo .= "&annee=$annee";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $id = $_POST["MM_update"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "DESCRIBE $id";
  $entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);
  $entete_array = array(); $i=0;
  if($totalRows_entete>0){
    do{ if($i>0)
    $entete_array[]=$row_entete["Field"]; $i++;
    }while($row_entete  = mysql_fetch_assoc($entete));
  }

  $form= $_POST['form'];

	$nomtable= $id;
	$noms= $form['nom'];
    $lnom = $_POST['lnomtable'];
    $lib= $form['lib'];
	$types= $form['type'];
    $choix= $form['choix'];
    $old= $form['old'];
	$estnulls= array();
	if(isset($form['estnull']))
	    $estnulls= $form['estnull'];

    $show= array();
	if(isset($form['show']))
	    $show= $form['show'];

    $cfg = ""; $cfg_choix = ""; $libelle = ""; $sqlAdd = $sqlAlter = $sqlDel = "ALTER TABLE `$nomtable` ";
    $array_type = array('INT','VARCHAR(1000)','CHAR','DOUBLE','DATE', 'DATETIME', 'YEAR', 'TEXT');
    $array_new=array();

if(isset($_POST["form_elem_del"])){   $array_new=explode(",",$_POST["form_elem_del"]);
foreach($array_new as $elem){  if(!empty($elem)) $sqlDel .=" DROP  `$elem`,"; }  }

    if(strlen($sqlDel)>=14){
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $ResultDel = mysql_query("".substr($sqlDel,0,strlen($sqlDel)-1)."", $pdar_connexion);
    }

	for($i= 0; $i< count($noms); $i++){
		$nom= trim(str_replace(" ","",$noms[$i]));
		$type= $types[$i];
        if(!in_array($type,$array_type)) $type = "TEXT";

		if(isset($estnulls[$i])	)
		   $estnull= $estnulls[$i];
		else $estnull="";

        if(isset($show[$i])	&& $show[$i]==1)
		   $cfg .= $nom.",";

        if(isset($choix[$i]) && !empty($choix[$i]) && $choix[$i]!=$nom){
		   $cfg_choix .= $nom.";".$choix[$i].",";  }


  if($old[$i]!=$nom && $entete_array[$i]!="annee"){
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $ResultCh = mysql_query("ALTER TABLE `$nomtable` CHANGE  `".$old[$i]."` `$nom` $type $estnull", $pdar_connexion);

  }elseif($old[$i]==$nom && $entete_array[$i]!="annee"){
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $ResultCh = mysql_query("ALTER TABLE `$nomtable` CHANGE  `".$old[$i]."` `$nom` $type $estnull", $pdar_connexion);

  }elseif(!isset($old[$i])){
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $ResultAd = mysql_query("ALTER TABLE `$nomtable` ADD `$nom` $type $estnull AFTER ".$entete_array[count($noms)-2]."", $pdar_connexion);
  }

    $libelle.=$nom."=".$lib[$i].",";
	}

    $libelle .= $id."=".$lnom.",";


    $insertSQL2 = sprintf("DELETE from fiche_config WHERE `table`=%s",GetSQLValueString($nomtable, "text"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result21 = mysql_query($insertSQL2, $pdar_connexion);

    $insertSQL22 = "INSERT INTO fiche_config (`table`,`show`,choix,libelle) VALUES('$nomtable','$cfg','$cfg_choix',\"$libelle\")";
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result22 = mysql_query($insertSQL22, $pdar_connexion);



    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($ResultCh || $ResultAd || $Result22) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    $insertGoTo .= "&annee=$annee&cp=$nomtable";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{  $cp = $_POST["id_cp"];  $annee=$_POST["annee"];
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $sql='' ; $titre="";

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "DESCRIBE $cp";
  $entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);
  $entete_array = array();
  if($totalRows_entete>0){ do{
    $entete_array[$row_liste_table["Field"]]=$row_liste_table["Type"]; }while($row_entete  = mysql_fetch_assoc($entete));
   }

  if(isset($_POST["field_name"])){ $i=0; foreach($_POST["field_name"] as $name){ $titre.="`$name`,"; $sql.=(isset($entete_array[$name]) && strtolower($entete_array[$name])!="date")?'"'.$_POST[$name].'",':'"'.implode('-',array_reverse(explode('-',$_POST[$name]))).'",'; $i++; }  }
  $sql=substr($sql,0,strlen($sql)-1);
    $insertSQL = 'INSERT INTO '.$cp.' ('.substr($titre,0,strlen($titre)-1).') VALUES ('.$sql.')';

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok&cp=$cp&annee=$annee"; else $insertGoTo .= "?insert=no&cp=$cp&annee=$annee";
    header(sprintf("Location: %s", $insertGoTo));
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=intval($_POST["MM_update"]); $sql='' ;

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "DESCRIBE $cp";
  $entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);
  $entete_array = array();
  if($totalRows_entete>0){ do{
    $entete_array[$row_entete["Field"]]=$row_entete["Type"]; }while($row_entete  = mysql_fetch_assoc($entete));
   }

  if(isset($_POST["field_name"])){ foreach($_POST["field_name"] as $name) $sql.=(isset($entete_array[$name]) && strtolower($entete_array[$name])!="date")?$name.'='.GetSQLValueString($_POST[$name], "text").',':$name.'='.GetSQLValueString(implode('-',array_reverse(explode('-',$_POST[$name]))), "date").',';  }
  $sql=substr($sql,0,strlen($sql)-1);
  	$insertSQL = "UPDATE $cp SET $sql WHERE LKEY=$c";

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok&cp=$cp&annee=$annee"; else $insertGoTo .= "?update=no&cp=$cp&annee=$annee";
    header(sprintf("Location: %s", $insertGoTo));
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
      $id = intval($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from $cp WHERE LKEY=%s",
                           GetSQLValueString($id, "int"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok&cp=$cp&annee=$annee"; else $insertGoTo .= "?del=no&cp=$cp&annee=$annee";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{ //ptba

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {   $id_cp=$_POST["id_cp"];
	$form= $_POST['form'];
	//var_dump($form);

	$xmlfile= "table.xml";

	$nomtable= strtolower(trim(str_replace(" ","",$_POST['nomtable'])));
	$noms= $form['nom'];
    $lib= $form['lib'];
	$types= $form['type'];
    $choix= $form['choix'];
	$estnulls= array();
	if(isset($form['estnull']))
	    $estnulls= $form['estnull'];

    $show= array();
	if(isset($form['show']))
	    $show= $form['show'];



	$chxml= "<?xml version=\"1.0\" encoding=\"iso-8859-1\" standalone=\"yes\"?>\n";
	$chxml.="<tables>";

	$chxml.= "<table nom=\"fiche_{$nomtable}\">\n";

    $cfg = $cfg_choix = $libelle = "";
    $array_type = array('INT','VARCHAR','CHAR','DOUBLE','DATE', 'DATETIME', 'YEAR', 'TEXT');

	for($i= 0; $i< count($noms); $i++){
		$nom= trim(str_replace(" ","",$noms[$i]));
		$type= $types[$i];

        if(!in_array($type,$array_type)) $type = "TEXT";

		if(isset($estnulls[$i])	)
		   $estnull= $estnulls[$i];
		else $estnull="";

        if(isset($show[$i])	&& $show[$i]==1)
		   $cfg .= $nom.",";

        if(isset($choix[$i]) && !empty($choix[$i]))
		   $cfg_choix .= $nom.";".$choix[$i].",";


		$chxml.="<field nom=\"{$nom}\" type=\"{$type}\"";
		if ($estnull!="")
			$chxml.=" estnull=\"{$estnull}\"";
		$chxml.="></field>\n";

        $libelle.=$nom."=".$lib[$i].",";
	}

    $chxml.="<field nom=\"fiche\" type=\"INT\" estnull=\"NOT NULL\"></field>\n";
    //$chxml.="<field nom=\"annee\" type=\"INT\" estnull=\"NOT NULL\"></field>\n";

	$chxml.="</table>";
	$chxml.="</tables>";
	// tester si le fichier existe
	//if(!file_exists($xmlfile)){ // la table n'existe pas
		// on écrit le contenu xml dans le fichier
		$bool= file_put_contents($xmlfile, $chxml);
		if($bool){

			include_once 'XmlBase.php';

			$q= xmltobase($xmlfile); //$q contient la script de création de la table


		}


$personnel=$_SESSION['clp_id'];
    $insertSQL = $q;
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());

    $insertSQL2 = "INSERT INTO fiche_config (`table`,`show`,choix, libelle) VALUES('fiche_$nomtable','$cfg','$cfg_choix', \"$libelle\")";
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result2 = mysql_query($insertSQL2, $pdar_connexion) or die(mysql_error());

    $insertGoTo = $_SERVER['PHP_SELF']; $id_cp=$new_nomtable=substr("fiche_".$nomtable,0,strlen("fiche_".$nomtable)-8);
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    $insertGoTo .= "&annee=$annee&cp=$id_cp";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = $_POST["MM_delete"];
    $insertSQL = "DROP table `$id`";

    $insertSQL1 = sprintf("DELETE from fiche_config WHERE `table`=%s",
                         GetSQLValueString($id, "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result0 = mysql_query($insertSQL1, $pdar_connexion) or die(mysql_error());

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF']; $new_nomtable=substr($id,0,strlen($id)-8);
    if ($Result1){ unset($_SESSION["cp"]); $insertGoTo .= "?del=ok"; }  else $insertGoTo .= "?del=no";
    $insertGoTo .= "&annee=$annee&cp=$new_nomtable";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $id = $_POST["MM_update"];
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "DESCRIBE $id";
  $entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);
  $entete_array = array(); $i=0;
  if($totalRows_entete>0){
    do{ if($i>0)
    $entete_array[]=$row_entete["Field"]; $i++;
    }while($row_entete  = mysql_fetch_assoc($entete));
  }

  $form= $_POST['form'];

	$nomtable= $id;
	$noms= $form['nom'];
    $lib= $form['lib'];
	$types= $form['type'];
    $choix= $form['choix'];
	$estnulls= array();
    $old= $form['old'];
	if(isset($form['estnull']))
	    $estnulls= $form['estnull'];

    $show= array();
	if(isset($form['show']))
	    $show= $form['show'];

    $cfg = ""; $cfg_choix = ""; $libelle = ""; $sqlAdd = $sqlAlter = $sqlDel = "ALTER TABLE `$nomtable` ";
    $array_type = array('INT','VARCHAR(1000)','CHAR','DOUBLE','DATE', 'DATETIME', 'YEAR', 'TEXT');
    $array_new=array();

if(isset($_POST["form_elem_del"])){   $array_new=explode(",",$_POST["form_elem_del"]);
foreach($array_new as $elem){  if(!empty($elem)) $sqlDel .=" DROP  `$elem`,"; }  }

    if(strlen($sqlDel)>=14){
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $ResultDel = mysql_query("".substr($sqlDel,0,strlen($sqlDel)-1)."", $pdar_connexion);
    }

	for($i= 0; $i< count($noms); $i++){
		$nom= trim(str_replace(" ","",$noms[$i]));
		$type= $types[$i];
        if(!in_array($type,$array_type)) $type = "TEXT";

		if(isset($estnulls[$i])	)
		   $estnull= $estnulls[$i];
		else $estnull="";

        if(isset($show[$i])	&& $show[$i]==1)
		   $cfg .= $nom.",";

        if(isset($choix[$i]) && !empty($choix[$i]) && $choix[$i]!=$nom){
		   $cfg_choix .= $nom.";".$choix[$i].",";  }

  if($old[$i]!=$nom && $entete_array[$i]!="fiche"){
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $ResultCh = mysql_query("ALTER TABLE `$nomtable` CHANGE  `".$old[$i]."` `$nom` $type $estnull", $pdar_connexion);

  }elseif($old[$i]==$nom && $entete_array[$i]!="annee"){
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $ResultCh = mysql_query("ALTER TABLE `$nomtable` CHANGE  `".$old[$i]."` `$nom` $type $estnull", $pdar_connexion);

  }elseif(!isset($old[$i])){
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $ResultAd = mysql_query("ALTER TABLE `$nomtable` ADD `$nom` $type $estnull AFTER ".$entete_array[count($noms)-2]."", $pdar_connexion);
  }

  $libelle.=$nom."=".$lib[$i].",";

  }


	}

    $insertSQL2 = sprintf("DELETE from fiche_config WHERE `table`=%s",GetSQLValueString($nomtable, "text"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result21 = mysql_query($insertSQL2, $pdar_connexion);

    $insertSQL22 = "INSERT INTO fiche_config (`table`,`show`,choix,libelle) VALUES('$nomtable','$cfg','$cfg_choix',\"$libelle\")";
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result22 = mysql_query($insertSQL22, $pdar_connexion);

    $new_nomtable=substr($nomtable,0,strlen($nomtable)-8);
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($ResultCh || $ResultAd || $Result22) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    $insertGoTo .= "&annee=$annee&cp=$new_nomtable";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }



mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SHOW tables";
$liste_cp = mysql_query($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);

$cp_array=array();
if($totalRows_liste_cp>0) {
do {  if(strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="fiche_config"){  $cp_array[]=$row_liste_cp["Tables_in_$database_pdar_connexion"];
}
} while ($row_liste_cp = mysql_fetch_assoc($liste_cp));
$rows = mysql_num_rows($liste_cp);
if($rows > 0) {
mysql_data_seek($liste_cp, 0);
$row_liste_cp = mysql_fetch_assoc($liste_cp);
}}

if(isset($cp) && !in_array($cp,$cp_array)) unset($cp);

$entete_array = $libelle = array();

if(isset($cp) && !empty($cp))
{
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = "SELECT * FROM $cp WHERE annee=$annee";
$act  = mysql_query($query_act , $pdar_connexion) or die(mysql_error());
$row_act  = mysql_fetch_assoc($act);
$totalRows_act  = mysql_num_rows($act);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT * FROM fiche_config WHERE `table`='$cp'";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);

if($totalRows_entete>0){ $entete_array=explode(",",$row_entete["show"]); $libelle=explode(",",$row_entete["libelle"]); }

$count = count($libelle)-2;
$count = explode("=",$libelle[$count]);
$lib_nom_fich = "";
if(isset($count[1]))
$lib_nom_fich = $count[1];
elseif(isset($count[0]))
$lib_nom_fich = $count[0];

if(empty($lib_nom_fich)) $lib_nom_fich = $cp;

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "DESCRIBE $cp";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);
$num=0;
if($totalRows_entete>0){ do{ if(in_array($row_entete["Field"],$entete_array)) $num++; }while($row_entete  = mysql_fetch_assoc($entete));  }

$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}

}

//toutes les fiches
$lib_nom_fich_array = $table_array = array();
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cfg = "SELECT * FROM fiche_config WHERE `table` NOT LIKE '%_details'";
$cfg  = mysql_query($query_cfg , $pdar_connexion) or die(mysql_error());
$row_cfg  = mysql_fetch_assoc($cfg);
$totalRows_cfg  = mysql_num_rows($cfg);

if($totalRows_cfg>0){ do{
  $table_array[] = $row_cfg["table"];
  $cfg_array=explode(",",$row_cfg["show"]); $libelleF=explode(",",$row_cfg["libelle"]);

$count = count($libelleF)-2;
$count = explode("=",$libelleF[$count]);

if(isset($count[1]))
$lib_nom_fich_array[$row_cfg["table"]] = $count[1];
elseif(isset($count[0]))
$lib_nom_fich_array[$row_cfg["table"]] = $count[0];

  }while($row_cfg  = mysql_fetch_assoc($cfg));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print $config->sitename; ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="keywords" content="<?php print $config->MetaKeys; ?>" />
  <meta name="description" content="<?php print $config->MetaDesc; ?>" />
  <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
  <meta name="author" content="<?php print $config->MetaAuthor; ?>" />
  <!--<meta charset="utf-8">-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->
  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/login.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->
  <!--[if IE 8]><link href="<?php print $config->theme_folder; ?>/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->
  <link href='<?php print $config->theme_folder; ?>/css.css' rel='stylesheet' type='text/css'>
  <!--<link rel="stylesheet" href="<?php print $config->theme_folder; ?>/table.css" type="text/css" > -->
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/lodash.compat.min.js"></script>
  <!--[if lt IE 9]><script src="<?php print $config->script_folder; ?>/libs/html5shiv.js"></script><![endif]-->
  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>
  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/breakpoints.js"></script>
  <script type="text/javascript" src="plugins/respond/respond.min.js"></script>
  <script type="text/javascript" src="plugins/cookie/jquery.cookie.min.js"></script>
  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.min.js"></script>
  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>
  <!--[if lt IE 9]><script type="text/javascript" src="plugins/flot/excanvas.min.js"></script><![endif]-->
  <!--<script type="text/javascript" src="plugins/sparkline/jquery.sparkline.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.tooltip.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.resize.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.time.min.js"></script>
  <script type="text/javascript" src="plugins/flot/jquery.flot.growraf.min.js"></script>
  <script type="text/javascript" src="plugins/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
  <script type="text/javascript" src="plugins/daterangepicker/moment.min.js"></script>
  <script type="text/javascript" src="plugins/daterangepicker/daterangepicker.js"></script>-->
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
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/custom.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/pages_calendar.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_filled_blue.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_simple.js"></script>-->
 <script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
 <script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/login.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/myscript.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/ui_general.js"></script>
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>
 <style>
.firstcapitalize:first-letter{
  text-transform: capitalize;
}
</style>
</head>
<body>
 <header class="header navbar navbar-fixed-top" role="banner">
    <?php include_once("includes/header.php"); ?>
 </header>
<div id="container">
    <div id="sidebar" class="sidebar-fixed">
        <div id="sidebar-content">
            <?php include_once("includes/menu_top.php"); ?>
        </div>
        <div id="divider" class="resizeable"></div>
    </div>

    <div id="content">
        <div class="container">
            <div class="crumbs">
                <?php include_once("includes/sous_menu.php"); ?>
            </div>
        <div class="page-header">
            <div class="p_top_5">
<!-- Site contenu ici -->
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
</style>
<script type="text/javascript">
var Plugins=function(){
var m=function(){if($.fn.dataTable){$.extend(true,$.fn.dataTable.defaults,{oLanguage:{sSearch:""},sDom:"<'row'<'dataTables_header clearfix'<'col-md-6'l><'col-md-6'f>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",iDisplayLength:5,fnDrawCallback:function(){if($.fn.uniform){$(":radio.uniform, :checkbox.uniform").uniform()}if($.fn.select2){$(".dataTables_length select").select2({minimumResultsForSearch:"-1"})}var o=$(this).closest(".dataTables_wrapper").find("div[id$=_filter] input");if(o.parent().hasClass("input-group")){return}o.addClass("form-control");o.wrap('<div class="input-group"></div>');o.parent().prepend('<span class="input-group-addon"><i class="icon-search"></i></span>')}});$.fn.dataTable.defaults.iDisplayLength=-1;$.fn.dataTable.defaults.aLengthMenu=[[5,10,25,50,100,-1],[5,10,25,50,100,"Tout"]];$(".datatable").each(function(){var w=$(this);var y={};var s=w.data("datatable");if(typeof s!="undefined"){$.extend(true,y,s)}var x=w.data("displayLength");if(typeof x!="undefined"){$.extend(true,y,{iDisplayLength:x})}var r=w.data("horizontalWidth");if(typeof r!="undefined"){$.extend(true,y,{sScrollX:"100%",sScrollXInner:r,bScrollCollapse:true})}if(w.hasClass("table-checkable")){$.extend(true,y,{aoColumnDefs:[{bSortable:false,aTargets:[0]}]})}if(w.hasClass("table-tabletools")){$.extend(true,y,{sDom:"<'row'<'dataTables_header clearfix'<'col-md-4'l><'col-md-8'Tf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",oTableTools:{aButtons:["copy","print","csv","xls","pdf"],sSwfPath:"plugins/datatables/tabletools/swf/copy_csv_xls_pdf.swf"}})}if(w.hasClass("table-colvis")){$.extend(true,y,{sDom:"<'row'<'dataTables_header clearfix'<'col-md-6'l><'col-md-6'Cf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",oColVis:{buttonText:"Columns <i class='icon-angle-down'></i>",iOverlayFade:0}})}if(w.hasClass("table-tabletools")&&w.hasClass("table-colvis")){$.extend(true,y,{sDom:"<'row'<'dataTables_header clearfix'<'col-md-6'l><'col-md-6'TCf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",})}if(w.hasClass("table-checkable")&&w.hasClass("table-colvis")){$.extend(true,y,{oColVis:{aiExclude:[0]}})}if(w.hasClass("table-responsive")){var q;var p={tablet:1024,phone:480};var t=$.fn.dataTable.defaults.fnDrawCallback;$.extend(true,y,{bAutoWidth:false,fnPreDrawCallback:function(){if(!q){q=new ResponsiveDatatablesHelper(this,p)}},fnRowCallback:function(C,B,A,z){q.createExpandIcon(C)},fnDrawCallback:function(z){t.apply(this,z);q.respond()}})}var v=w.data("datatableFunction");if(typeof v!="undefined"){$.extend(true,y,window[v]())}if(w.hasClass("table-columnfilter")){var u={};var o=w.data("columnfilter");if(typeof o!="undefined"){$.extend(true,u,o)}$(this).dataTable(y).columnFilter(u);w.find(".filter_column").each(function(){var z=w.data("columnfilterSelect2");if(typeof z!="undefined"){$(this).children("input").addClass("form-control");$(this).children("select").addClass("full-width-fix").select2({placeholderOption:"first",allowClear:true})}else{$(this).children("input, select").addClass("form-control")}})}else{$(this).dataTable(y)}})}}
return{init:function(){m();}}}();
/*
$('#DataTables_Table_0').dataTable({
    paging: false
});
$(document).ready(function() {
    $('#DataTables_Table_0').dataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, 100, 200, 300, "Tous"]]
    } );
} ); */
</script>
                <table width="100%"  border="0" align="left" cellspacing="2" cellpadding="2">
                 <tr bgcolor="">
                   <td valign="middle" nowrap="nowrap"><div align="left">
                     <?php include("content/annee_ptba.php"); ?></div></td>
                      <td align="right" width="150"><div align="right"><?php if(isset($cp) && !empty($cp)){ ?><a onclick="get_content('new_details_fiche.php','<?php echo "&annee=".$annee; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Cr&eacute;ation des d&eacute;tails de la fiche" class="thickbox Add"  dir="">Cr&eacute;er d&eacute;tails fiche</a><?php } ?></div></td>
                      <td align="right" width="100"><div align="right"><a onclick="get_content('new_fiche.php','<?php echo "&annee=".$annee; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Cr&eacute;ation d'une nouvelle fiche" class="thickbox Add"  dir="">Cr&eacute;er une fiche</a></div></td>
                    </tr>
                 <tr bgcolor="">
                   <td colspan="3" valign="middle" nowrap="nowrap"><div align="right">
                     <form name="form2" id="form2" method="get" action="" class="contenuh1">
                       <table   border="0" cellspacing="2">
                         <tr>
                           <th nowrap="nowrap" scope="col"><input type="hidden" name="annee" value="<?php echo $annee; ?>" /></th>
                           <th nowrap="nowrap" scope="col">Fiche:
                             <select name="cp" style=" ">
                               <option value="">-- Choisissez --</option>

                               <?php   $table_array=array();
				  if($totalRows_liste_cp>0) {
				do { $table_array[]=$row_liste_cp["Tables_in_$database_pdar_connexion"];  if(strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="fiche_config" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"details")==""){
				?>
                                 <option value="<?php echo $row_liste_cp["Tables_in_$database_pdar_connexion"];?>"<?php if(isset($cp)) {if (!(strcmp($cp, $row_liste_cp["Tables_in_$database_pdar_connexion"]))) {echo "SELECTED";} } ?>><?php echo (isset($lib_nom_fich_array[$row_liste_cp["Tables_in_$database_pdar_connexion"]]))?$lib_nom_fich_array[$row_liste_cp["Tables_in_$database_pdar_connexion"]]:substr($row_liste_cp["Tables_in_$database_pdar_connexion"],6); ?></option>
                               <?php  }
			} while ($row_liste_cp = mysql_fetch_assoc($liste_cp));
			  $rows = mysql_num_rows($liste_cp);
			  if($rows > 0) {
				  mysql_data_seek($liste_cp, 0);
				  $row_liste_cp = mysql_fetch_assoc($liste_cp);
			  }}


          //else echo '<optgroup label="Aucune fiche disponible"></optgroup>'; ?>
                             </select></th>
                           <th scope="col"><input type="submit" name="Submit" value="Rechercher" style="color:#FF0000 " /></th>
                         </tr>
                       </table>
                     </form>
                   </div></td>
                   </tr>
				   <?php
				 if(isset($cp)) {//requete groupe d'activite
                                ?>
                </table>

<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box">
<div class="widget-header"> <h4 style="width: 49%"><i class="icon-reorder"></i><strong><a onclick="get_content('new_fiche.php','<?php echo "id=".$cp."&annee=".$annee; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add"  title="Modification <?php echo $lib_nom_fich;?>" class="thickbox" dir=""><?php echo $lib_nom_fich;?></a></strong>&nbsp;&nbsp;<?php if(in_array($cp."_details",$table_array)){ ?>|&nbsp;<strong><a onclick="get_content('new_details_fiche.php','<?php echo "id=".$cp."_details&annee=".$annee; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add"  title="Modification <?php echo $lib_nom_fich." d&eacute;tails";?>" class="thickbox" dir=""><?php echo $lib_nom_fich." d&eacute;tails";?></a></strong><?php } ?><b>&nbsp;|&nbsp;<a href="imprime_fiche.php?<?php echo "id=".$cp."&annee=".$annee; ?>" title="Impression de la fiche <?php echo $lib_nom_fich;?>">Imprimer</a></b></h4><h4 align="right" style="width: 49%"><?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<4 && isset($cp) && !empty($cp)) {?><a onclick="get_content('modal_content/new_fiche_data.php','<?php echo "id_cp=".$cp."&annee=".$annee."#os"; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Ajout" dir=""><i class="icon-plus"> Ajouter <?php //echo substr($cp,6); ?> </i></a><?php }?></h4>

<?php include_once 'modal_add.php'; ?>

</div>
<div class="widget-content" style="overflow: auto;">
<?php if($num>0){ ?>
<table class="table table-striped table-bordered table-hover table-responsive datatable dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<?php if($totalRows_entete>0){ $i=0; do{

if(isset($libelle[$i])){
$lib=explode("=",$libelle[$i]);
$libelle_array[$lib[0]]=(isset($lib[1]))?$lib[1]:"ND";   }

if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array)){  ?>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize"><?php echo (isset($libelle_array[$row_entete["Field"]]))?$libelle_array[$row_entete["Field"]]:str_replace("_"," ",$row_entete["Field"]); ?></div></th>
<?php } $i++; }while($row_entete  = mysql_fetch_assoc($entete)); }
$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}

if(isset($table_array) && in_array($cp."_details",$table_array)){ ?>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize">D&eacute;tails</div></th>
<?php } ?>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<4) {?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="60">Edit</th>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="60">Suppr.</th>
<?php }?>
</tr>
</thead>

<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php $i=0; if($totalRows_act>0) { do { $id = $row_act['LKEY']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<?php if($totalRows_entete>0){ do{ if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array)){
if(strtolower($row_entete["Field"])=="village" && intval($row_act[$row_entete["Field"]])>0){ $village=$row_act[$row_entete["Field"]];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_region = "SELECT nom_village,nom_commune FROM commune,village WHERE commune=code_commune and code_village='$village'";
$region = mysql_query($query_region, $pdar_connexion) or die(mysql_error());
$row_region = mysql_fetch_assoc($region);
$totalRows_region = mysql_num_rows($region);
$lib_vill = $row_region["nom_commune"]." / ".$row_region["nom_village"];
mysql_free_result($region);
}
 ?>
<td class=" "><?php if(strtolower($row_entete["Type"])=="date") echo implode('-',array_reverse(explode('-',$row_act[$row_entete["Field"]]))); else echo (strtolower($row_entete["Field"])=="village" && isset($row_region["nom_village"]) && isset($lib_vill))?$lib_vill:$row_act[$row_entete["Field"]]; unset($lib_vill); ?></td>
<?php } }while($row_entete  = mysql_fetch_assoc($entete));
$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}
} ?>

<?php if(isset($table_array) && in_array($cp."_details",$table_array)){ ?>
<td class=" "><a onclick="get_content('suivi_technique_details.php','<?php echo "id_fiche=".$row_act['LKEY']."&cp=".$cp."_details&id_cp=".$cp."_details&annee=".$annee."#os"; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" title="Les d&eacute;tails de <?php echo $lib_nom_fich; ?>" class="thickbox Add"  dir="">Suivre</a></td>
<?php } ?>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<4) {?>
<td class=" " align="center"><a onclick="get_content('modal_content/new_fiche_data.php','<?php echo "&id=".$row_act['LKEY']."&id_cp=".$cp."&annee=".$annee."#os"; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="<?php echo $lib_nom_fich; ?>" class="thickbox Add"  dir=""><img src="images/edit.png" width='20' height='20' alt='Modifier' /></a></td>
<td class=" " align="center"><a href="<?php echo $_SERVER['PHP_SELF']."?&id_sup=".$row_act['LKEY']."&ad_act=".$cp."&annee=".$annee."&cp=".$cp; ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?');" /><img src="images/delete.png" width="15" border="0"/></a></td>
<?php }?>
</tr>
<?php $i++; } while ($row_act = mysql_fetch_assoc($act)); } ?>
</tbody></table><?php }else echo "<h3 align='center'>Aucune colonne &agrave; afficher dans la fiche ".$lib_nom_fich."!</h3>" ?>

</div> </div>


				   <?php } else {?>
                  <h3 align="center" class="Style5">Veuillez s&eacute;lectionnez une fiche !!! </h3>
                  <?php } ?>


<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>

<?php if(isset($_GET['acteur'])){ ?>
<script type="text/javascript">
show_tab('amontrer<?php echo $_GET["acteur"]; ?>');;
</script>
<?php }?>
</body>
</html>