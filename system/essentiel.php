<?php ini_set("date.timezone","UTC");//if(!session_start()) session_start();

  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
  {
     $theValue = /*(!get_magic_quotes_gpc()) ? */addslashes($theValue);// : $theValue;
    // $theValue = str_replace("'","''",$theValue);

      switch ($theType) {  
        case "text":
          $theValue = ($theValue != "") ? "'" . trim($theValue) . "'" : "NULL";
          break;
        case "long":
        case "int":
          $theValue = ($theValue != "") ? intval(str_replace(' ','',$theValue)) : "NULL";
          break;
        case "double":
          $theValue = ($theValue != "") ? "'" . doubleval(str_replace(',','.',str_replace(' ','',$theValue))) . "'" : "NULL";
          break;
        case "date":
          $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
          break;
        case "defined":
          $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
          break;
        case "toASCIIsimple":
         $string = str_replace('-', '', $theValue); // Replaces all spaces -.
         $string = str_replace('_', '-', $string); // Replaces all spaces -.
         $string = preg_replace('/[^A-za-z0-9\-]/', '', $string); // Removes special chars.
         $theValue = str_replace('-', '_', $string); // Replaces all spaces -.
         break;
        case "toASCII":
         $string = str_replace(' ', '', $theValue); // Replaces all spaces with hyphens.
         $string = str_replace('-', '', $string); // Replaces all spaces -.
         $theValue = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
         break;
      }
      return $theValue;
  }

  function mysql_query_ruche($query,$pdar_connexion,$type=0)
  {
    $personnel=$_SESSION['clp_id']; $allowed = array("INSERT","DELETE","UPDATE");
    $config = new Config;
    if(file_exists($config->sys_folder."/database/db_connexion.php"))
    {
      include $config->sys_folder."/database/db_connexion.php";
    }
    elseif(file_exists("../".$config->sys_folder."/database/db_connexion.php"))
    {
      $path = "../";
      include $path.$config->sys_folder."/database/db_connexion.php";
    }
    if((strchr(strtoupper($query),"INSERT")!="" || strchr(strtoupper($query),"DELETE")!="" || strchr(strtoupper($query),"UPDATE")!="") && in_array(substr(trim(strtoupper($query)),0,6),$allowed))
    {
        $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."ruche_sync (type, code, id_personnel) VALUES (%s, %s, '$personnel')",
      					  GetSQLValueString(1, "int"),
                          GetSQLValueString(2, "text"));
        try{
            $query = $pdar_connexion->prepare($insertSQL);
            $Result1 = $query->execute();
        }catch(Exception $e){ die(mysql_error_show_message($e)); }

        if($Result1){
            try{
                $query = $pdar_connexion->prepare($query);
                return $query->execute();
            }catch(Exception $e){ die(mysql_error_show_message($e)); }
        }
        else die(mysql_error_show_message(mysql_error()));
    }
    elseif(substr(trim(strtoupper($query)),0,3)=="DOC")
    {
        $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."ruche_sync (type, code, id_personnel) VALUES (%s, %s, '$personnel')",
      					  GetSQLValueString(1, "int"),
                          GetSQLValueString(2, "text"));
        try{
            $query = $pdar_connexion->prepare($insertSQL);
            $Result1 = $query->execute();
        }catch(Exception $e){ die(mysql_error_show_message($e)); }
    }
    else{
        try{
            $query = $pdar_connexion->prepare($query);
            return $query->execute();
        }catch(Exception $e){ die(mysql_error_show_message($e)); }
    }
  }

  function redirecting_to($a)
  {
    header(sprintf("Location: %s", $a)); exit;
  }

  function stripHTMLtags($texte)
  {
  	//On retire le code HTML
  	$mots = explode("<",$texte);
  	$texte = "";
  	$nbmots = count($mots);

  	for ($m = 0; $m < $nbmots; $m++)
  		{
  		$mot = $mots[$m];
  		$finbalise = strpos($mot,">",0);
  		if ($finbalise > 0) { $mot = substr($mot,$finbalise+1); }
  		$texte .= "$mot";
  		}

  	return $texte;
  }

  function shrunck($text, $maxLen)
  {
    $temp = $text;
    // Simple truncation
	if(strlen($text) > $maxLen)
    {
	    $temp = substr($text,0,$maxLen).'...';
	}
    return $temp;
  }

  function trimAll($text, $maxLen, $Url="")
  {
    $text = (stripHTMLtags($text));
    $temp = $text;
    // Simple truncation
	if(strlen($text) > $maxLen)
    {
	    $temp = substr($text,0,$maxLen).'...';
        if(!empty($Url))
        $temp .= '<div class="right"><a href="./?id='.intval($Url).'&type=4">Lire la suite &raquo;</a></div>';
	}
    return $temp;
  }

  function trimAll_simple($text, $maxLen)
  {
    $text = (stripHTMLtags($text));
    $temp = $text;
    // Simple truncation
	if(strlen($text) > $maxLen)
    {
	    $temp = substr($text,0,$maxLen);
	}
    return $temp;
  }

  function antislashs()
  {
    //les antislashs
    if (get_magic_quotes_gpc())
    {
      function magicQuotes_awStripslashes(&$value, $key)
      {
        $value = stripslashes($value);
      }
      $gpc = array(&$_GET, &$_POST, &$_REQUEST);
      array_walk_recursive($gpc, 'magicQuotes_awStripslashes');
    }
  }
/*mysql_set_charset('UTF8',$pdar_connexion);
mysql_set_charset('UTF8',$connect_transfert); */
function getRelativeTime($date)
{
	$date_a_comparer = new DateTime($date);
	$date_actuelle = new DateTime("now");

	$intervalle = $date_a_comparer->diff($date_actuelle);

	if ($date_a_comparer > $date_actuelle)
	{
		$prefixe = 'dans ';
	}
	else
	{
		$prefixe = 'il y a ';
	}

	$ans = $intervalle->format('%y');
	$mois = $intervalle->format('%m');
	$jours = $intervalle->format('%d');
	$heures = $intervalle->format('%h');
	$minutes = $intervalle->format('%i');
	$secondes = $intervalle->format('%s');

	if ($ans != 0)
	{
		$relative_date = $prefixe . $ans . ' an' . (($ans > 1) ? 's' : '');
		if ($mois >= 6) $relative_date .= ' et demi';
	}
	elseif ($mois != 0)
	{
		$relative_date = $prefixe . $mois . ' mois';
		if ($jours >= 15) $relative_date .= ' et demi';
	}
	elseif ($jours != 0)
	{
		$relative_date = $prefixe . $jours . ' jour' . (($jours > 1) ? 's' : '');
	}
	elseif ($heures != 0)
	{
		$relative_date = $prefixe . $heures . ' heure' . (($heures > 1) ? 's' : '');
	}
	elseif ($minutes != 0)
	{
		$relative_date = $prefixe . $minutes . ' minute' . (($minutes > 1) ? 's' : '');
	}
	else
	{
		$relative_date = $prefixe . ' quelques secondes';
	}

	return $relative_date;
}

  function date_reg($date,$type,$time=0,$visuel=0)
  {
    $a = explode(' ',$date);
    $T = $a[0];
    if(isset($a[1]))
    $HI = $a[1];
    //list($T,$HI) = explode(' ',$date);
    list($Y,$M,$D) = explode('-',$T);

    if(isset($HI) && !empty($HI))
    {
      list($H,$I,$S) = explode(':',$HI);
    }

    if($visuel==1)
    {
      /*list($T,$H) = explode(' ',$date);
      list($Y,$M,$D) = explode('-',$T);
      list($h,$i,$s) = explode(':',$H);*/
      $DT = mktime($H,$I,$S,$M,$D,$Y);
      $a = getRelativeTime(date("Y-m-d\TH:i:sP",$DT));
      $date = $D.$type.$M.$type.$Y.(($time==1)?' &agrave; '.$H.':'.$I:'');
      return "<span title='$date'>$a</span>";
    }

    $date = $D.$type.$M.$type.$Y.(($time==1)?' &agrave; '.$H.':'.$I:'');
    return $date;
  }

  function reg_img($id,$dim,$ext)
  {
    $img = ''; $ext = ($ext[0] == '.')?$ext:'.'.$ext;
    if($dim)
    $img = (file_exists("images/".$id."_".$dim.$ext))?"images/".$id."_".$dim.$ext:"images/nopic_".$dim.".png";
    else
    $img = (file_exists("images/".$id.$ext))?"images/".$id.$ext:"images/nopic.png";
    return $img;
  }

  function reg_img_complex($id,$dim,$ext,$lien)
  {
    $img = ''; $ext = ($ext[0] == '.')?$ext:'.'.$ext;
    if($dim)
    $img = (file_exists($lien.$id."_".$dim.$ext))?$lien.$id."_".$dim.$ext:"images/nopic_".$dim.".png";
    else
    $img = (file_exists($lien.$id.$ext))?$lien.$id.$ext:"images/nopic.png";
    return $img;
  }

  function CleanTitle($txt = '')
  {
    $txt = trim($txt);
    $txt = str_replace('"',"",$txt);
    $txt = str_replace("'","",$txt);
    $txt = str_replace('?','',$txt);
    return $txt;
  }

  function do_link($id="", $href="",$title="",$val="",$type="",$path="./",$class="",$onclick="",$pop=0,$style="",$page="",$onclickOnly=0)
  {
    $txt = "<a ";
    $txt .= (!empty($id))?"id=\"$id\" ":"";
    $txt .= (!empty($href))?"href=\"$href\" ":((!empty($onclick))?"href=\"#myModal_add\" data-backdrop=\"static\" data-keyboard=\"false\" ":"href=\"javascript:void(0);\" ");
    $txt .= (!empty($onclick) && $pop==1)?"data-toggle='modal' ":"";
    $txt .= (!empty($class))?"class='$class' ":"";
    $txt .= (!empty($title))?"title=\"$title\" ":"";
    $txt .= (!empty($onclick))?"onclick=\"$onclick\" ":"";
    $txt .= (!empty($style))?"style=\"$style\" ":"";
    $txt .= " >";

    switch($type) {
      default:
      //if(check_user_auth('page_edit',$page)) return;
      $img = '';
      break;

      case 'add':
      if(check_user_auth('page_edit',$page)) return;
      $img = '<img src="'.$path.'images/add.png" width="20" height="20" alt="Ajouter" title="Ajouter" />';
      break;

      case 'view':
      //if(check_user_auth('page_edit',$page)) return;
      $img = '<img src="'.$path.'images/view.png" width="20" height="20" alt="Afficher" title="Afficher" />';
      break;

      case 'edit':
      if(check_user_auth('page_edit',$page)) return;
      $img = '<img src="'.$path.'images/edit.png" width="20" height="20" alt="Modifier" title="Modifier" />';
      break;

      case 'del':
      if(check_user_auth('page_edit',$page)) return;
      $img = '<img src="'.$path.'images/delete.png" width="20" height="20" alt="Supprimer" title="Supprimer" />';
      break;

      case 'access':
      if(check_user_auth('page_edit',$page)) return;
      $img = '<img src="'.$path.'images/access.png" width="20" height="20" alt="Droits" title="Droits" />';
      break;

      case 'valid':
      if(check_user_auth('page_valid',$page)) return;
      $img = '<img src="'.$path.'images/valid.png" width="20" height="20" alt="Valider" title="Valider" />';
      break;

      case 'verif':
      if(check_user_auth('page_verif',$page)) return;
      $img = '<img src="'.$path.'images/verif.png" width="20" height="20" alt="V&eacute;rifier" title="V&eacute;rifier" />';
      break;

      case 'import':
      if(check_user_auth('page_edit',$page)) return;
      $img = '<img src="'.$path.'images/img_acht_caisse.png" width="20" height="20" alt="Importation" title="Importer" />';
      break;

      case 'reload':
      if(check_user_auth('page_edit',$page)) return;
      $img = '<img src="'.$path.'images/reload.png" width="20" height="20" alt="Actualiser" title="Actualiser" />';
      break;

      case 'send_message':
      if(check_user_auth('page_edit',$page)) return;
      $img = '<img src="'.$path.'images/send_message.png" width="20" height="20" alt="Envoyer" title="Envoyer" />';
      break;

      case 'download':
      //if(check_user_auth('page_edit',$page)) return;
      $img = '<img src="'.$path.'images/download.png" width="20" height="20" alt="T&eacute;l&eacute;charger" title="T&eacute;l&eacute;charger" />';
      break;
    }
    if($onclickOnly>0) return (!empty($onclick))?"onclick=\"$onclick\" ":"";

    $txt .= $img;
    $txt .= (!empty($val))?$val:"";
    $txt .= "</a>";

    return $txt;
  }

  function check_user_auth($id,$nfile)
  {
    if(isset($_SESSION["clp_$id"]) && !empty($nfile))
    {
      $page = explode('|',$_SESSION["clp_$id"]);
      if(in_array($nfile,$page))
      return true;
      else return false;
    }
    else return false;
  }

  function mysql_error_show_message($error,$href="")
  {
    $msg = "";
    $msg = "<style type='text/css'>.DTTT_print_info{text-align: center;position: fixed;top: 1px;padding: auto;left:20%;font-size: 14px;font-family: 'Lucida Bright',tahoma,verdana,arial,sans-serif;background-repeat: no-repeat;background-position: center left;z-index: 301;margin: auto;width: 60%;color: #FFF;border: solid 1px yellowgreen;background-color: yellowgreen;background-repeat: no-repeat;background-position: center left;}div.DTTT_print_info h6{font-weight:normal;font-size:28px;margin:0px; padding:0px;}</style><div class='DTTT_print_info'><h1>L'erreur suivante s'est produite: <br /><i>&nbsp;".$error."</i></h1>&nbsp; <h2 align='center'><a href='".(!empty($href)?$href:$_SERVER['HTTP_REFERER'])."' />Veuillez recommencez </a> ou Contactez l'administrateur !</b></h2>";
    return $msg;
  }

?>