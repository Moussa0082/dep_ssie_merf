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
//header('Content-Type: text/html; charset=UTF-8');

if(isset($_GET["id"]) && intval($_GET["id"])>0)
{
    $id=intval($_GET["id"]);
    $query_liste_personnel = "SELECT * FROM ".$database_connect_prefix."personnel WHERE N=$id ";
    try{
        $listepersonnel = $pdar_connexion->prepare($query_liste_personnel);
        $listepersonnel->execute();
        $row_liste_personnel = $listepersonnel ->fetch();
        $totalRows_liste_personnel = $listepersonnel->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
}

//Authorisation
$query_auth = "SELECT * FROM ".$database_connect_prefix."user_access WHERE id_personnel=".$row_liste_personnel["N"];
try{
    $auth = $pdar_connexion->prepare($query_auth);
    $auth->execute();
    $row_auth = $auth ->fetch();
    $totalRows_auth = $auth->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

function print_menu($MENU,$MENU_TITLE,$page,$page_edit,$page_verif,$page_valid,$i)
{
  $resultat = ''; $page = explode('|',$page); $page_edit = explode('|',$page_edit);
  $page_verif = explode('|',$page_verif); $page_valid = explode('|',$page_valid);
  if(is_array($MENU))
  {
    $resultat .= '<tr><td><a href="javascript:void(0);" > <i class="icon-'.((is_array($MENU_TITLE))?$MENU_TITLE[1]:'').'"></i> <b>'.((is_array($MENU_TITLE))?$MENU_TITLE[0]:'').'</b> </a></td>';
    $resultat .= '
<td align="center"><input id="checkId_edit'.$i.'" type="checkbox" class="btn" onclick="check_all(\'edit_'.$i.'\',this);uncheck_all(\'auth_'.$i.'\',this);" /></td>
<td align="center"><input id="checkId_verif'.$i.'" type="checkbox" class="btn" onclick="check_all(\'verif_'.$i.'\',this);uncheck_all(\'auth_'.$i.'\',this);" /></td>
<td align="center"><input id="checkId_valid'.$i.'" type="checkbox" class="btn" onclick="check_all(\'valid_'.$i.'\',this);uncheck_all(\'auth_'.$i.'\',this);" /></td>
<td align="center"><input id="checkId'.$i.'" type="checkbox" class="btn" onclick="check_all(\'auth_'.$i.'\',this);uncheck_all(\'edit_'.$i.'\',this);uncheck_all(\'verif_'.$i.'\',this);uncheck_all(\'valid_'.$i.'\',this);" /></td></tr>';
    foreach($MENU as $a => $b)
    {  if(is_array($b)){ foreach($b as $b0=>$b1){ $b2=$b1;  break; } unset($b); $b=$b2;}
      $resultat .= '<tr id="'.substr($a, 0, 3).'"><td><i class="icon-angle-right"></i> '.((!is_array($b))?$b:"ND").'</td>
      <td align="center" class="edit_'.$i.'"><input name="page_edit[]" id="edit_'.$i.'" type="checkbox" '.((is_array($page_edit) && in_array($a,$page_edit)?"checked='checked'":"")).' class="btn" value="'.$a.'" onclick="uncheck_this2(this,\''.substr($a, 0, 3).'\');" /></td>
      <td align="center" class="verif_'.$i.'"><input name="page_verif[]" id="verif_'.$i.'" type="checkbox" '.((is_array($page_verif) && in_array($a,$page_verif)?"checked='checked'":"")).' class="btn" value="'.$a.'" onclick="uncheck_this2(this,\''.substr($a, 0, 3).'\');" /></td>
      <td align="center" class="valid_'.$i.'"><input name="page_valid[]" id="valid_'.$i.'" type="checkbox" '.((is_array($page_valid) && in_array($a,$page_valid)?"checked='checked'":"")).' class="btn" value="'.$a.'" onclick="uncheck_this2(this,\''.substr($a, 0, 3).'\');" /></td>
      <td align="center" class="auth_'.$i.'"><input name="auth[]" id="auth_'.$i.'" type="checkbox" '.((is_array($page) && in_array($a,$page)?"checked='checked'":"")).' class="btn" value="'.$a.'" onclick="uncheck_this1(this,\''.substr($a, 0, 3).'\');" /></td> </tr>';
    }
  }
  return $resultat;
}

?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && intval($_GET["id"])>0 && $totalRows_auth>0)?"Modification d'autorisation":"Nouvel autorisation"?></h4> </div>
<div class="widget-content">
<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">

<table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable " align="center" >
  <tr class="titrecorps2">
    <td><div align="left"><strong>Pages</strong></div></td>
    <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
    <td width="80" align="center"><strong>Edition</strong></td>
    <td width="80" align="center"><strong>V&eacute;rification</strong></td>
    <td width="80" align="center"><strong>Validation</strong></td>
    <td width="80" align="center"><strong>Interdiction</strong></td>
    <?php } ?>
  </tr>
  <?php if(is_array($MENU)) { global $j; $j=1; foreach($MENU as $a=>$b) { echo print_menu($b,$MENU_TITLE[$a],$row_auth["page_interd"],$row_auth["page_edit"],$row_auth["page_verif"],$row_auth["page_valid"],$a); } } ?>
</table>

<div class="form-actions">
  <input name="id_personnel" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0) echo intval($_GET["id"]); ?>" size="32" alt="">
  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0 && $totalRows_auth>0) echo "Modifier"; else echo "Enregistrer" ; ?>" />
  <input name="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0 && $totalRows_auth>0) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && intval($_GET["id"])>0 && $totalRows_auth>0) echo ($row_auth["id"]); else echo "MM_insert" ; ?>" size="32" alt="">
<?php if(isset($_GET["id"]) && intval($_GET["id"])>0 && $totalRows_auth>0) { ?>
<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">
<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer les droits ?',<?php echo ($row_auth["id"]); ?>);" class="btn btn-danger pull-left" value="Supprimer" />
<?php } ?>
<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">
  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->
</div>
</form>

</div> </div>