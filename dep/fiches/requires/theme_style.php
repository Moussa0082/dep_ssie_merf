<?php
$Panel_Style="";
$Boutton_Style="";
$Text_Style="";
$Label_Style="";
$Panel_Item_Style="";
$Panel_Index="1";
foreach (FC_Rechercher_Code('SELECT * FROM v_style WHERE Style_Par_Defaut=1') AS $row1)
{$row1['Attribut_Style']=="classe menu table"? $Panel_Style=$row1['Valeur_Style']:"";
 $row1['Attribut_Style']=="classe menu-item table"? $Panel_Item_Style=$row1['Valeur_Style']:"";
 $Panel_Index=$row1['Style_Index'];
}
$Boutton_Style=str_replace("panel", "btn", $Panel_Style);
$Text_Style=str_replace("panel", "text", $Panel_Style);
$Label_Style=str_replace("panel", "label", $Panel_Style);
?>