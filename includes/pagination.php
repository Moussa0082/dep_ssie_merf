<?php

function getLimitPagination($currentPage,$totalPages,$maxRows,$pageNum,$filtre,$chearch_name = "")
{
 $url_pageMax = $url_pageNum = $url_page = $url = "";

    if (!empty($currentPage))
    {
      $params = explode("&", $currentPage);
      list($url,$a) = explode("?", $currentPage);
      if (count($params) > 1)
      {
        $newParams = $newParams1 = $newParams2 = array();
        foreach ($params as $param)
        {
          if (stristr($param, "pm") == false)
          {
            array_push($newParams, $param);
          }
          if (stristr($param, "pn") == false)
          {
            array_push($newParams1, $param);
          }
          if (stristr($param, "q") == false)
          {
            array_push($newParams2, $param);
          }
          if (stristr($param, "date") == false)
          {
            array_push($newParams2, $param);
          }
          if (stristr($param, "date1") == false)
          {
            array_push($newParams2, $param);
          }
        }
        if (count($newParams) != 0)
        {
          $url_pageMax .= htmlentities(implode("&", $newParams));
        }
        if (count($newParams1) != 0)
        {
          $url_pageNum .= htmlentities(implode("&", $newParams1));
        }
        if (count($newParams2) != 0)
        {
          $url_page .= htmlentities(implode("&", $newParams2));
        }
      }
    }

 $ret = "";
    $ret .='<div id="searchContainer" class="r_float clearfix">'.
    '<form name="searchForm" id="searchForm" action="'.$url_page.'" onsubmit="return false;">'.
    '<div id="searchInputContainer">'.
    '<input type="text" name="filtre" key="searchSubmit" value="'.$filtre.'" id="searchText" maxlength="256" title="Recherche par '.$chearch_name.'" /></div>'.
    '<div id="searchButtons">'.
 '<button id="searchSubmit" value="&nbsp;" title="Recherche" onclick="gotopagecherche(\'searchText\',\''.$url_page.'\');" ></button>';
 if(!empty($filtre))
 $ret .= '<button id="searchSubmitCancel" value="&nbsp;" title="Annuler" onclick="gotourl(\''.$url.'\');" ></button>';
    $ret .= '</div>'.
    '<div id="" style="display: table-cell;"><label for="pm">Affichage #</label>'.
    '<select name="pm" id="pm" size="1" onchange="gotopage(this,\''.$url_pageMax.'\');">'.
    '<option value="5"'; $ret .= ($maxRows==5)?'selected="selected"':''; $ret .='>5</option>'.
    '<option value="10" '; $ret .= ($maxRows==10)?'selected="selected"':''; $ret .='>10</option>'.
    '<option value="15" '; $ret .= ($maxRows==15)?'selected="selected"':''; $ret .='>15</option>'.
    '<option value="20" '; $ret .= ($maxRows==20)?'selected="selected"':''; $ret .='>20</option>'.
    '<option value="25" '; $ret .= ($maxRows==25)?'selected="selected"':''; $ret .='>25</option>'.
    '<option value="30" '; $ret .= ($maxRows==30)?'selected="selected"':''; $ret .='>30</option>'.
    '<option value="50" '; $ret .= ($maxRows==50)?'selected="selected"':''; $ret .='>50</option>'.
    '<option value="100" '; $ret .= ($maxRows==100)?'selected="selected"':''; $ret .='>100</option>'.
    '<option '; $ret .= ($maxRows==1)?'selected="selected"':''; $ret .='>Tout</option>'.
    '</select>'.
    '<label for="pn">Page n&ordm;</label>'.
    '<select name="pn" id="pn" size="1" onchange="gotopage(this,\''.$url_pageNum.'\');">';
    for($j=0;$j<=$totalPages;$j++)
    {
      $ret .= '<option '; $ret .= ($pageNum==$j)?'selected="selected"':'';
      $ret .= 'value="'.$j.'">'.($j+1).'</option>';
    }
    $ret .= '</select>'.
    $ret .= '<label for=""><a href="javascript:void(0)" title="Changer le mode d\'affichage" class="" onclick="toggle_element(\'grid_style\',\'liste_style\');">Grille / Liste</a></label>';
    $ret .= '<label for="">';
    $ret .= ($pageNum > 0)?'&laquo;&laquo; <a href="'.$currentPage.'pn=0" title="Début" class="">Début</a>':'<span class="grey">&laquo;&laquo; Début</span>';
    $ret .='</label>'.
    '<label for="">';
     $ret .= ($pageNum > 0)?'&laquo; <a href="'.$currentPage.'pn='.max(0, $pageNum - 1).'" title="Précedent" class="">Précedent</a>':'<span class="grey">&laquo; Précedent</span>';
    $ret .='</label>'.
    '<label for="">';
    $ret .= ($pageNum+1).'/'.($totalPages+1);
    $ret .= '</label>'.
    '<label for="">';
    $ret .= ($pageNum < $totalPages)?'<a href="'.$currentPage.'pn='.min($totalPages, $pageNum + 1).'" title="Suivant" class="">Suivant </a>&raquo;':'<span class="grey">Suivant &raquo;</span>';
    $ret .='</label>'.
    '<label for="">';
    $ret .= ($pageNum < $totalPages)?'<a href="'.$currentPage.'pn='.$totalPages.'" title="Fin" class="">Fin </a>&raquo;&raquo;':'<span class="grey">Fin &raquo;&raquo;</span>';
    $ret .='</label>'.
    '</div>'.
    '</form></div><div class="clear h0">&nbsp;</div>';

    return $ret;
}

function getLimitPaginationLeft($currentPage,$totalPages,$maxRows,$pageNum,$filtre,$chearch_name = "")
{
  $url_pageMax = $url_pageNum = $url_page = $url = "";

  if (!empty($currentPage))
  {
    $params = explode("&", $currentPage);
    list($url,$a) = explode("?", $currentPage);
    if (count($params) > 1)
    {
      $newParams = $newParams1 = $newParams2 = $newParams3 = array();
      foreach ($params as $param)
      {
        if (stristr($param, "pm") == false)
        {
          array_push($newParams, $param);
        }
        if (stristr($param, "pn") == false)
        {
          array_push($newParams1, $param);
        }
        if (stristr($param, "q") == false)
        {
          array_push($newParams2, $param);
        }
        if (stristr($param, "date"))
        {
          array_push($newParams3, $param);
        }
      }
      $url_pageMax = (count($newParams) == 0)?htmlentities(implode("&", $newParams)):$currentPage;
      $url_pageNum = (count($newParams1) == 0)?htmlentities(implode("&", $newParams1)):$currentPage;
      $url_page = (count($newParams2) == 0)?htmlentities(implode("&", $newParams2)):$currentPage;
      $url_page_all = (count($newParams3) > 0)?htmlentities(implode("&", $newParams3)):$currentPage;
    }
  }

  $ret = '<ul class="l_float mgt2">';
  $ret .='<li><div class="sfsearchBox" style="margin-right: 40px;"><!--<div id="searchContainer" class="r_float clearfix">'.
  '<form name="searchForm" id="searchForm" action="'.$url_page.'" onsubmit="return false;">-->'.
  '<div id="searchInputContainer">'.
  '<input type="text" name="filtre" key="searchSubmit" value="'.$filtre.'" id="searchText" class="sfsearchTxt" maxlength="256" title="Recherche par '.$chearch_name.'" placeholder="Recherche par '.$chearch_name.'" /></div><input value="Search" id="searchButton" class="sfsearchSubmit" type="submit" onclick="gotopagecherche(\'searchText\',\''.$url_page.'\');">'.
 (!empty($filtre)?'<input value="Search" id="searchButtonCancel" class="sfsearchSubmitCancel" type="button" onclick="gotourl(\''.$url.'\');">':'').
  '<!--<button id="searchSubmit" value="&nbsp;" title="Recherche" onclick="gotopagecherche(\'searchText\',\''.$url_page.'\');" ></button>-->';
  /*if(!empty($filtre))
  $ret .= '<button id="searchSubmitCancel" value="&nbsp;" title="Annuler" onclick="gotourl(\''.$url.'\');" ></button>'; */
  $ret .= '</div>'.
  '<!--</form></div><div class="clear h0">&nbsp;</div>--></li>';
  $ret .= '</ul>';
  $ret .= '<ul class="l_float mgt2">'.
  '<li>&nbsp;</li>'.
  '<li class="mgt"><label for="pm">Affichage #</label>&nbsp;'.
  '<select name="pm" id="pm" size="1" onchange="gotopage(this,\''.$url_pageMax.'\');">'.
  '<option value="5"'; $ret .= ($maxRows==5)?'selected="selected"':''; $ret .='>5</option>'.
  '<option value="10" '; $ret .= ($maxRows==10)?'selected="selected"':''; $ret .='>10</option>'.
  '<option value="15" '; $ret .= ($maxRows==15)?'selected="selected"':''; $ret .='>15</option>'.
  '<option value="20" '; $ret .= ($maxRows==20)?'selected="selected"':''; $ret .='>20</option>'.
  '<option value="25" '; $ret .= ($maxRows==25)?'selected="selected"':''; $ret .='>25</option>'.
  '<option value="30" '; $ret .= ($maxRows==30)?'selected="selected"':''; $ret .='>30</option>'.
  '<option value="50" '; $ret .= ($maxRows==50)?'selected="selected"':''; $ret .='>50</option>'.
  '<option value="100" '; $ret .= ($maxRows==100)?'selected="selected"':''; $ret .='>100</option>'.
  '<option '; $ret .= ($maxRows==1)?'selected="selected"':''; $ret .='>Tout</option>'.
  '</select></li>'.
  '<li class="mgt"><label for="pn">Page n&ordm;</label>&nbsp;'.
  '<select name="pn" id="pn" size="1" onchange="gotopage(this,\''.$url_pageNum.'\');">';
  for($j=0;$j<=$totalPages;$j++)
  {
    $ret .= '<option '; $ret .= ($pageNum==$j)?'selected="selected"':'';
    $ret .= 'value="'.$j.'">'.($j+1).'</option>';
  }
  $ret .= '</select></li>';
  $ret .= '</ul>';

  return $ret;
}

function getLimitPaginationRight($currentPage,$totalPages,$maxRows,$pageNum,$filtre,$chearch_name = "",$active_grid = 0)
{
  $ret = "<ul class='r_float spmgt10'>".
  '<li>';
  if($active_grid == 0)
  {
    $ret .= '<label for=""><a href="javascript:void(0)" title="Changer le mode d\'affichage" class="" onclick="toggle_element(\'grid_style\',\'liste_style\');">Grille / Liste</a></label>';
  }
  $ret .= ($pageNum > 0)?'<a href="'.$currentPage.'pn=0" title="Début" class=""><span>&laquo;&laquo;</span></a>':'<a href="javascript:void(0);" title="Début" class="gray1"><span>&laquo;&laquo;</span></a>';
  $ret .='</li>'.
  '<li>';
   $ret .= ($pageNum > 0)?'<a href="'.$currentPage.'pn='.max(0, $pageNum - 1).'" title="Précedent" class=""><span>&laquo;</span></a>':'<a href="javascript:void(0);" title="Précedent" class="gray1"><span>&laquo;</span></a>';
  $ret .='</li>'.
  '<li class="mgt"><span class="active">'.($pageNum+1).' sur '.($totalPages+1).'</span></li>'.
  '<li>';
  $ret .= ($pageNum < $totalPages)?'<a href="'.$currentPage.'pn='.min($totalPages, $pageNum + 1).'" title="Suivant" class=""><span>&raquo;</span></a>':'<a href="javascript:void(0);" title="Suivant" class="gray1"><span>&raquo;</span></a>';
  $ret .='</li>'.
  '<li>';
  $ret .= ($pageNum < $totalPages)?'<a href="'.$currentPage.'pn='.$totalPages.'" title="Fin" class=""><span>&raquo;&raquo;</span></a>':'<a href="javascript:void(0);" title="Fin" class="gray1"><span>&raquo;&raquo;</span></a>';
  $ret .='</li>'.
  '</ul>';

  return $ret;
}

?>