<?php  ini_set("display_errors",1);
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();  $path = '../';
include_once $path.'system/configuration.php';
$config = new Config;
       /*
if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
} */
include_once $path.$config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');
header('Content-Type: text/html; charset=ISO-8859-15');

/*function ReplaceImap($txt)
{
    $carimap = array("=E9", "=E8", "=EA", "=C3=AB", "=C3=A7", "=E0", "=20", "=C3=80", "=C3=89", "=92", "=9C", "=");
    $carhtml = array("é", "è", "ê", "ë", "ç", "à", "&nbsp;", "À", "É", "'", "oe", "");
    $txt = str_replace($carimap, $carhtml, $txt);

    return $txt;
}   */

function getBody($uid, $imap) {
    $body = get_part($imap, $uid, "TEXT/HTML");
    // if HTML body is empty, try getting text body
    if ($body == "") {
        $body = get_part($imap, $uid, "TEXT/PLAIN");
    }
    return $body;
}

function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false) {
    if (!$structure) {
           $structure = imap_fetchstructure($imap, $uid, FT_UID);
    }
    if ($structure) {
        if ($mimetype == get_mime_type($structure)) {
            if (!$partNumber) {
                $partNumber = 1;
            }
            $text = imap_fetchbody($imap, $uid, $partNumber, FT_UID);
            switch ($structure->encoding) {
                case 3: return imap_base64($text);
                case 4: return imap_qprint($text);
                default: return $text;
           }
       }

        // multipart
        if ($structure->type == 1) {
            foreach ($structure->parts as $index => $subStruct) {
                $prefix = "";
                if ($partNumber) {
                    $prefix = $partNumber . ".";
                }
                $data = get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
                if ($data) {
                    return $data;
                }
            }
        }
    }
    return false;
}

function get_mime_type($structure) {
    $primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");

    if ($structure->subtype) {
       return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
    }
    return "TEXT/PLAIN";
}

function quotedPrintableDecode($input)
{
// Remove soft line breaks
$input = preg_replace("/=\r?\n/", '', $input);
// Replace encoded characters
$cb = create_function('$matches', ' return chr(hexdec($matches[0]));');
$input = preg_replace_callback( '/=([a-f0-9]{2})/i', $cb, $input);
return $input;
}

function getMsg($mbox,$mid) {
  // input $mbox = IMAP stream, $mid = message id
  // output all the following:
  global $htmlmsg,$plainmsg,$charset,$attachments;
  // the message may in $htmlmsg, $plainmsg, or both
  $htmlmsg = $plainmsg = $charset = '';
  $attachments = array();

  // HEADER
  $h = imap_header($mbox,$mid);
  // add code here to get date, from, to, cc, subject...

  // BODY
  $s = imap_fetchstructure($mbox,$mid);
  if (empty($s->parts))  // not multipart
    getMsgPart($mbox,$mid,$s,0);  // no part-number, so pass 0
  else {  // multipart: iterate through each part
    foreach ($s->parts as $partno0=>$p)
      getMsgPart($mbox,$mid,$p,$partno0+1);
  }
}

function getMsgPart($mbox,$mid,$p,$partno) {
  // $partno = '1', '2', '2.1', '2.1.3', etc if multipart, 0 if not multipart
  global $htmlmsg,$plainmsg,$charset,$attachments;

  // DECODE DATA
  $data = ($partno)?
    imap_fetchbody($mbox,$mid,$partno):  // multipart
    imap_body($mbox,$mid);  // not multipart
  // Any part may be encoded, even plain text messages, so check everything.
  if ($p->encoding==4)
    $data = quoted_printable_decode($data);
  elseif ($p->encoding==3)
    $data = base64_decode($data);
  // no need to decode 7-bit, 8-bit, or binary

  // PARAMETERS
  // get all parameters, like charset, filenames of attachments, etc.
  $params = array();
  if ($p->ifparameters)
    foreach ($p->parameters as $x)
      $params[ strtolower( $x->attribute ) ] = $x->value;
  if ($p->ifdparameters)
    foreach ($p->dparameters as $x)
      $params[ strtolower( $x->attribute ) ] = $x->value;
/*
  // ATTACHMENT
  // Any part with a filename is an attachment,
  // so an attached text file (type 0) is not mistaken as the message.
  if (!empty($params['filename']) || !empty($params['name'])) {
    // filename may be given as 'Filename' or 'Name' or both
    $filename = (!empty($params['filename']))? $params['filename'] : $params['name'];
    // filename may be encoded, so see imap_mime_header_decode()
    $attachments[$filename] = $data;  // this is a problem if two files have same name
  } */

  // TEXT
  /*else*/if ($p->type==0 && $data) {
    // Messages may be split in different parts because of inline attachments,
    // so append parts together with blank row.
    if ($p->ifsubtype && strtolower($p->subtype)=='plain')
      $plainmsg .= trim($data) ."\n\n";
    else
      $htmlmsg .= $data ."<br><br>";
    $charset = $params['charset'];  // assume all parts are same charset
  }

  // EMBEDDED MESSAGE
  // Many bounce notifications embed the original message as type 2,
  // but AOL uses type 1 (multipart), which is not handled here.
  // There are no PHP functions to parse embedded messages,
  // so this just appends the raw source to the main message.
  elseif ($p->type==2 && $data) {
    $plainmsg .= trim($data) ."\n\n";
  }

  // SUBPART RECURSION
  if (!empty($p->parts)) {
    foreach ($p->parts as $partno0=>$p2)
      getMsgPart($mbox,$mid,$p2,$partno.'.'.($partno0+1));  // 1.2, 1.2.1, etc.
  }
}

set_time_limit(4000);

// Connect to gmail
$imapPath = '{mail.prodaf.net:143/novalidate-cert}INBOX';
$username = 'info@prodaf.net';
$password = 'programmefida1'; $password_hash = md5($password);
$inbox = imap_open($imapPath,$username,$password) or die('Cannot connect to Mail: ' . imap_last_error());
$emails = imap_search($inbox,'UNSEEN');  //FROM "psac",UNSEEN,ON "2015-08-25"

$output = '';
$carimap1 = array(utf8_encode("é"), utf8_encode("è"), utf8_encode("ê"), utf8_encode("ë"), utf8_encode("ç"), utf8_encode("à"), utf8_encode("&nbsp;"), utf8_encode("À"), utf8_encode("É"), utf8_encode("'"), utf8_encode("oe"));
if(count($emails)<=0 || !is_array($emails)) $output = "NO email";
else
{ $j=0;
  foreach($emails as $mail) {  /*if($j==2) break;*/ $j++;
  //Pieces jointes
  $structure = imap_fetchstructure($inbox,$mail);
   $attachments = array();
     if(isset($structure->parts) && count($structure->parts)) {
       for($i = 0; $i < count($structure->parts); $i++) {
         $attachments[$i] = array(
            'is_attachment' => false,
            'filename' => '',
            'name' => '',
            'attachment' => '');

         if($structure->parts[$i]->ifdparameters) {
           foreach($structure->parts[$i]->dparameters as $object) {
             if(strtolower($object->attribute) == 'filename') {
               $attachments[$i]['is_attachment'] = true;
               $attachments[$i]['filename'] = ($object->value);
             }
           }
         }

           if($structure->parts[$i]->ifparameters) {
             foreach($structure->parts[$i]->parameters as $object) {
               if(strtolower($object->attribute) == 'name') {
                 $attachments[$i]['is_attachment'] = true;
                 $attachments[$i]['name'] = ($object->value);
               }
             }
           }

           if($attachments[$i]['is_attachment']) {
             $attachments[$i]['attachment'] = imap_fetchbody($inbox, $mail, $i+1);
             if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
               $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
             }
             elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
               $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
             }
           }
         }
       }
    //enregistrement de la piece jointe
    $attach = "";
    $dir = '../attachment/dano/'; $dir1 = '../attachment/dano/mails/';
    if(count($attachments)!=0){
        foreach($attachments as $at){
            if(isset($at['is_attachment']) && $at['is_attachment']==1){
              $file = mb_decode_mimeheader($at['filename']);
$clean_name = strtr($file, array('Š' => 'S','Ž' => 'Z','š' => 's','ž' => 'z','Ÿ' => 'Y','À' => 'A','Á' => 'A','Â' => 'A','Ã' => 'A','Ä' => 'A','Å' => 'A','Ç' => 'C','È' => 'E','É' => 'E','Ê' => 'E','Ë' => 'E','Ì' => 'I','Í' => 'I','Î' => 'I','Ï' => 'I','Ñ' => 'N','Ò' => 'O','Ó' => 'O','Ô' => 'O','Õ' => 'O','Ö' => 'O','Ø' => 'O','Ù' => 'U','Ú' => 'U','Û' => 'U','Ü' => 'U','Ý' => 'Y','à' => 'a','á' => 'a','â' => 'a','ã' => 'a','ä' => 'a','å' => 'a','ç' => 'c','è' => 'e','é' => 'e','ê' => 'e','ë' => 'e','ì' => 'i','í' => 'i','î' => 'i','ï' => 'i','ñ' => 'n','ò' => 'o','ó' => 'o','ô' => 'o','õ' => 'o','ö' => 'o','ø' => 'o','ù' => 'u','ú' => 'u','û' => 'u','ü' => 'u','ý' => 'y','ÿ' => 'y'));
                file_put_contents($dir.$clean_name, $at['attachment']);
                copy($dir.$clean_name,$dir1.$clean_name);
                //file_put_contents($dir1.$clean_name, $at['attachment']);
                $attach .= $clean_name."|";
                }
            }
    }
    //else $attach = "";

  $headerInfo = imap_headerinfo($inbox,$mail);
  $from=$headerInfo->from;
  $timestamp=strtotime($headerInfo->date);
  $output .= "Message n&deg; $j $mail <br/>";
  $objet = empty($headerInfo->subject)?"RAS":quotedPrintableDecode($headerInfo->subject);
  $objet = iconv_mime_decode($objet,0, "ISO-8859-1//TRANSLIT");
  //$objet = iconv("utf-8", 'iso-8859-1//TRANSLIT//IGNORE', $objet);
  $output .= "Objet : ".$objet.'<br/>';
  $de = iconv_mime_decode($from[0]->personal,0, "ISO-8859-1//TRANSLIT");
  if($from[0]->mailbox!='MAILER-DAEMON')
  {
  $output .= "Message de : ".$de." [".$from[0]->mailbox."@".$from[0]->host."]"."<br>";
  $output .= "Date : ".date("Y/m/d H:i:s", $timestamp).'<br/>';
  $output .= "Attachements : ".((!empty($attach))?$attach:"Aucun").'<br/>';
  //$ln = (!empty($attach))?"1.2":2; //if($j==2) $ln="1.2";
    getMsg($inbox, $mail);

    $text_part = $plainmsg; // text portion of the email
    $html_part = $htmlmsg; // html portion of the email

    // check for text portion first

    /*if ($msg_text == '')
    { */
      // text portion is empty, check html portion
      $msg_text = trim($htmlmsg);
      if ($msg_text == '')
      {
        // no text or html portion auto-detected, check manually
        $msg_text = imap_body($inbox, $mail); // get the entire raw message
        // check for quoted-printable encoding with possible boundary markers and headers at the top
        $chunks = explode("\n", trim($msg_text));

        if (count($chunks) > 1) // if there are multiple lines
        {
          $quoted_printable = false;
          if (strpos($chunks[0], '--') === 0) // if the first line is a boundary marker (starts with '--')
          {
            array_shift($chunks); // remove the first line
            if (strpos($chunks[count($chunks) - 1], '--') === 0) // check the last line
            {
              array_pop($chunks); // remove that too
            }
          }
          if (strpos(strtolower($chunks[0]), 'content-type') === 0)
            array_shift($chunks); // remove the first line if it's a content-type header
          if (strpos(strtolower($chunks[0]), 'content-transfer-encoding') === 0)
          {
            if (strpos(strtolower($chunks[0]), 'quoted-printable'))
              $quoted_printable = true; // this email was sent using quoted-printable encoding
            array_shift($chunks); // remove the content-transfer-encoding header
          }
          $msg_text = implode("\n", $chunks); // put the remaining lines back together
          if ($quoted_printable) $msg_text = quoted_printable_decode($msg_text);
          $msg_text = ($msg_text);
        }
      }
    //}
    if($htmlmsg=='') $msg_text = trim(strip_tags($plainmsg, '<a>'));

  if(!empty($attach)){ $msg = getBody($mail,$inbox); /*
    //$overview = imap_fetch_overview($inbox,$mail,0);
    $structure = imap_fetchstructure($inbox, $mail);
    $msg = imap_fetchbody($inbox, $mail, "1.1");
    if (preg_match('/^([a-zA-Z0-9]{76} )+[a-zA-Z0-9]{76}$/', $msg))
    {
      $msg = base64_decode($msg);
    }
    //$msg = imap_fetchbody($inbox, $mail, "1.2");
    //$msg = $message;  */
  }
  else $msg = $msg_text;
  $message = empty($msg)?"RAS":quotedPrintableDecode(($msg));
  if(preg_match('!!u', $message))
  $message = iconv("utf-8", 'iso-8859-1//TRANSLIT//IGNORE', $message);
  $output .= "Message : ".$message;
  $output .= " <hr>";

  //traitement dans la BD
  $personnel=$_SESSION['clp_id']; $date=date("Y-m-d"); $projet = "01"; $dno = "";
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."mail_dno (projet, dno, expediteur, objet, message, attachments, date, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, '$personnel', '$date')",
  			   GetSQLValueString($projet, "text"),
			   GetSQLValueString($dno, "text"),
 			   GetSQLValueString($from[0]->mailbox."@".$from[0]->host, "text"),
               GetSQLValueString($objet, "text"),
               GetSQLValueString($message, "text"),
               GetSQLValueString($attach, "text"),
			   GetSQLValueString(date("Y-m-d H:i:s", $timestamp), "date"),
 			   GetSQLValueString($personnel, "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  }
  }
}
echo $output;
// colse the connection
imap_expunge($inbox);
imap_close($inbox);
?>