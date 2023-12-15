<?php

/**
 * renvoie le script de la creation de la table
 * @param string $xmlfile
 * @param string $type
 * @return string            
 */
  function xmltobase($xmlfile, $type= null){
  	// lecture du fichier xml
  	if(!file_exists($xmlfile)){
  		echo "error...";
  				return ;
  	}
  $xml=simplexml_load_file($xmlfile);
  $table= $xml->table;
  
  $nomtable=$table["nom"];
 
  $fields= "";

  $debut= true;

  foreach ($table->field as $field){
 	
 	if($debut)
 		$debut= false;
 	else
 		$fields.=",";
 	
 	$nom= $field["nom"]; // nom du champ
 	$fields.=" {$nom} ";
 	
 	$type= $field["type"]; 	// type du champ 	
 	$fields.=" {$type} ";
 	
 	if(isset($field["estnull"])){ // champ null ou non
 	    $estnull= $field["estnull"];
 	    $fields.=" {$estnull} ";
 	}
 	
 	$fields.="\n";
  	 
 }
 

  return "CREATE TABLE IF NOT EXISTS {$nomtable} (LKEY BIGINT NOT NULL UNIQUE, {$fields} )";
 	
 } 
 
 
 /**
  * retourne un tableau content le formulaire en entier (qu'on peut afficher d'un seul coup)
  * les labels (le nom des champs du formulaire), les inputs (les widgets inputs des champs du formulaire)
  * les labels et inputs sont utilisé lorsque vous souhaite personnalisé l'affichage du formulaire
  * @param string $xmlfile
  * @param array $ligne: indique les valeur par defaut des champs
  * @return array
  */
 
 function xmltoform($xmlfile, $ligne= null){
 	if(!file_exists($xmlfile)){
 		echo "error...";
 		return ;
 	}
 	
 	
 	$xml=simplexml_load_file($xmlfile);
 	$table= $xml->table;
 	$nomtable=$table["nom"];
 	
 	
 	$fields= ""; // contient le form en entier
 	$labels= array(); // contient les widgets labels
 	$inputs= array(); // contient les widgets inputs
 	
 			
 	foreach ($table->field as $field){
 		$option="";
 		
 		
 		$nom= $field["nom"];
 		if($nom!="LKEY"){ // on ne genere pas le input de la clé
 		
 		if(!is_null($ligne) &&  isset($ligne["{$nom}"])){
 			$option.="value=\"{$ligne["{$nom}"]}\"";
 		}else{
 			$option.="value=\"\"";
 		}
 		
 		if(isset($field["estnull"])){
 			$option.="required=\"required\""; // champs obligatoire
 		}else{
 			$option.="";
 		}
 		
 		$labels["{$nom}"]= "<label>{$nom}</label>";
 		$inputs["{$nom}"]= "<input type=\"text\" name=\"{$nom}\" id=\"{$nomtable}_{$nom}\" {$option} />";
 	
 		$fields.="<div><label>{$nom}</label><div><input type=\"text\" name=\"{$nom}\" id=\"{$nomtable}_{$nom}\" {$option} /></div></div>";
 	}
 	}
 	return array("labels"=>$labels, "inputs"=>$inputs, "fields"=>$fields);
 }
 
 
 /**
  * retourne true si le doc xml est bien crée
  * @param string $tablebdd
  * @param resource $idcom
  * @param resource $idbase
  * @return boolean
  */
 
 function tabletoxml($tablebdd, $idcom){
 
 	if(!$idcom)
 	{
 		echo "Connexion Impossible à la base ".$idcom–>errorInfo();;
 	}
 	
 	/*
 	 requête pour lister les tables de la base
 	retourne Field, Type, Null,Key,	Default, Extra
 	*/
 	$q="SHOW COLUMNS FROM ".$tablebdd; 
 	
 	
 	$rs= $idcom->query($q); // executer la requête
 	if($rs){
 		// commence la construction du doc xml
 		$chxml="<?xml version=\"1.0\" encoding=\"iso-8859-1\" standalone=\"yes\"?>\n";
 		$chxml.="<tables>\n";
 		$chxml.="<table>\n";
 		while($lignes= $rs->fetch(PDO::FETCH_ASSOC)){
 			// chaque ligne de $lignes correspond à un champs
 			$nom= $lignes['Field']; // nom du champs
 			$type= $lignes['Type']; // type
 			$isnull= $lignes['Null']; // est null (NO) ou not null (YES)
 			$default= $lignes['Default'];
 	
 			/***
 			 * constructuction des champs fields
 			 */
 			$chxml.="<field nom=\"{$nom}\" type=\"{$type}\"";
 			
 			if ($isnull=="YES")
 				$chxml.=" esnull=\"NOT NULL\"";
 			$chxml.="></field>\n";
 	
 		}
 	
 		$chxml.="</table>\n";
 		$chxml.="</tables>";
 		$bool= file_put_contents('xmltable.xml', $chxml);
 		return $bool;
 	}
 }
 
 
 function getData($idcon, $tablebdd){
 	$lignes; // contien les données de la table
 	$nomcolonnes; // contient le nom des colonnes de la table
 	if(!$idcon)
 	{
 		echo "Connexion Impossible à la base ".$idcom–>errorInfo();;
 	}
 	
 	
 	$q="SELECT * FROM ".$tablebdd; 	
 	
 	$rs= $idcon->query($q); // executer la requête
 	return $rs->fetchAll(PDO::FETCH_ASSOC);
 }
 
 
 
 