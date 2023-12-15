<?php

require_once '../api/Fonctions.php';

 $Code_Rapport="";     
      if(isset($_GET['r']) AND !empty($_GET['r']))
      {//$Code_Rapport=base64_decode($_GET['r']);

  		$Code_Rapport=$_GET['r'];

        $Nom_Rapport="";
        $ii=0;
        foreach (FC_Rechercher_Code('SELECT * FROM t_rapport WHERE Code_Rapport='.$Code_Rapport) as $row4) 
        {$ii++; $uuu=0;
          $Nom_Rapport=$row4['Nom_Rapport'];
?>

<?php
$Fichier="Rapport_".date('d_m_Y_h_i_s').".doc";
header("Content-type: application/vnd.ms-word;charset=utf-8");
header("Content-Disposition: attachment;Filename=".$Fichier);

if($row4['Type_Rapport']=="SIMPLE")
{
  echo "Rapport : ".$Nom_Rapport;

  echo '<table cellpadding="1" cellspacing="1" style="width:100%" border="1">
<tr style=" background-color:#F1F3F6; text-align: center">';

$Total=0;
$Exp_GROUB_BY = explode(".", $row4["Group_By"]);
$Exp_VALEUR = explode(".", $row4["Valeur"]);

/*foreach (FC_Rechercher_Code("SELECT * FROM t_feuille_ligne WHERE(Code_Feuille=".$row5["Code_Feuille"]." AND Nom_Ligne='".$row5["Group_By"]."')") as $row6) 
{echo "<th>".$row6["Nom_Ligne"]."</th> <script> var Nom_Ligne='".$row6["Nom_Ligne"]."'; </script>";}

foreach (FC_Rechercher_Code("SELECT * FROM t_feuille_ligne WHERE(Code_Feuille=".$row5["Code_Feuille"]." AND Nom_Ligne='".$row5["Valeur"]."')") as $row7) 
{echo "<th>".$row7["Nom_Ligne"]."</th>";}*/

foreach (FC_Rechercher_Code("SELECT `t_feuille_ligne`.`Nom_Ligne` FROM `t_feuille_ligne` INNER JOIN `t_feuille` ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE (t_feuille.Table_Feuille = '".str_replace("v", "t", $Exp_GROUB_BY[0])."' AND t_feuille_ligne.Nom_Collone = '".$Exp_GROUB_BY[1]."')") as $row6){echo "<th>".$row6["Nom_Ligne"]."</th> <script> var Nom_Ligne='".$row6["Nom_Ligne"]."'; </script>";}

foreach (FC_Rechercher_Code("SELECT `t_feuille_ligne`.`Nom_Ligne` FROM `t_feuille_ligne` INNER JOIN `t_feuille` ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE (t_feuille.Table_Feuille = '".str_replace("v", "t", $Exp_VALEUR[0])."' AND t_feuille_ligne.Nom_Collone = '".$Exp_VALEUR[1]."')") as $row6){echo "<th>".$row6["Nom_Ligne"]."</th>";}

echo '</tr>'; 
$indd=0; $indu=0; $i=0;
$compte=0;

try
{$Res = FC_Rechercher_Code("SELECT * FROM ".$row4['Nom_View']);

if($Res!=null){
  foreach ($Res as $row8) 
{echo "<tr>"; echo "<td>".$row8[0]."</td>"; echo "<td>".number_format($row8[1],0, '',' ')."</td>"; echo "</tr>"; $compte++; $Total+=$row8[1];}
}

}
catch(Exception $e){}
echo "<tr>"; echo "<td><strong>TOTAL</strong></td>"; echo "<td><strong>".number_format($Total,0, '',' ')."</strong></td>"; echo "</tr>";
    echo '</table>';
}

else {
echo "Rapport : ".$Nom_Rapport;
echo '<table cellpadding="1" cellspacing="1" width="100%" border="1">
<tr style=" background-color:#F1F3F6; text-align: center">';

$COLONNE_X="";
$COLONNE_Y="";
$VALEUR="valeur";

$Exp_COLONNE_Y = explode(".", $row4["Colonne_Y"]);
$Exp_COLONNE_X = explode(".", $row4["Colonne_X"]);

foreach (FC_Rechercher_Code("SELECT `t_feuille_ligne`.`Nom_Ligne` FROM `t_feuille_ligne` INNER JOIN `t_feuille` ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE (t_feuille.Table_Feuille = '".str_replace("v", "t", $Exp_COLONNE_Y[0])."' AND t_feuille_ligne.Nom_Collone = '".$Exp_COLONNE_Y[1]."')") as $row6){echo "<th><sub>".$row6["Nom_Ligne"]."</sub> | ";}

 $COLONNE_Y=$row4["Colonne_Y"];
foreach (FC_Rechercher_Code("SELECT `t_feuille_ligne`.`Nom_Ligne` FROM `t_feuille_ligne` INNER JOIN `t_feuille` ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE (t_feuille.Table_Feuille = '".str_replace("v", "t", $Exp_COLONNE_X[0])."' AND t_feuille_ligne.Nom_Collone = '".$Exp_COLONNE_X[1]."')") as $row6){echo "<sup>".$row6["Nom_Ligne"]."</sup></th>";}

 $COLONNE_X=$row4["Colonne_X"];
/*foreach (FC_Rechercher_Code("SELECT * FROM t_feuille_ligne WHERE(Code_Feuille=".$row5["Code_Feuille"]." AND Nom_Ligne='".$row5["Valeur"]."')") as $row12) 
{$VALEUR=$row12["Nom_Collone"];}*/

try
{
$COLONNE_X_TAB=null;
$COLONNE_Y_TAB=null;
$indd=0; $indu=0; $i=0;
$compte=0;

$Col_Y ="";
$Col_X ="";

$Res1 = FC_Rechercher_Code("SELECT DISTINCT(`$COLONNE_X`) FROM ".$row4['Nom_View']);
if($Res1!=null)
  {foreach ($Res1 as $row10) 
{ echo "<th>".$row10[0]."</th>"; $COLONNE_X_TAB[]=$row10[0]; $Col_X.=" SUM(CASE WHEN `$COLONNE_X` LIKE '".addslashes($row10[0])."' THEN $VALEUR ELSE NULL END) AS ".str_replace(" ", "",addslashes($row10[0]))."_c,"; }
echo '<th style="text-align:right"><strong>TOTAL</strong></th>';
}
$Col_X = substr($Col_X, 0, strlen($Col_X)-1);

$Res2 = FC_Rechercher_Code("SELECT DISTINCT(`$COLONNE_Y`) FROM ".$row4['Nom_View']);
if($Res2!=null)
  {foreach ($Res2 as $row11) 
{$COLONNE_Y_TAB[]=$row11[0];}}


echo '</tr>';

$SQL_Code="
SELECT `$COLONNE_Y`,
$Col_X
FROM ".$row4['Nom_View']." GROUP BY `$COLONNE_Y`";
//echo $SQL_Code;
$Totaux=array_fill (0, count($COLONNE_X_TAB) , '0');
$Res5 = FC_Rechercher_Code($SQL_Code);
if($Res5!= null){
$Total=0;
foreach ($Res5 as $key5) {
$Total=0;

echo "<tr>"; 
echo "<td>".$key5["$COLONNE_Y"]."</td>";    
for ($i=0; $i<count($COLONNE_X_TAB); $i++) 
{

if($key5[str_replace(" ", "", $COLONNE_X_TAB[$i])."_c"]=="")
         {echo "<td>-</td>";}
else {echo "<td>".number_format($key5[str_replace(" ", "", $COLONNE_X_TAB[$i])."_c"],0, '',' ')."</td>"; $Total+=$key5[str_replace(" ", "", $COLONNE_X_TAB[$i])."_c"];
$Totaux[$i]+=$key5[str_replace(" ", "", $COLONNE_X_TAB[$i])."_c"];
}    

}
echo '<td style=" background-color:#F1F3F6; text-align:right"><strong>'.$Total.'</strong></td>';
echo "</tr>";}
}
$TOTAUX=0;
echo '<tr style=" background-color:#F1F3F6;">';
echo "<td><strong>TOTAL</strong></td>";
for ($i=0; $i<count($COLONNE_X_TAB); $i++) 
{echo "<td><strong>".$Totaux[$i]."</strong></td>"; $TOTAUX+=$Totaux[$i];}
echo '<td style="text-align:right"><strong>'.$TOTAUX.'</strong></td>';
echo "</tr>";

}
catch(Exception $e){}
    echo '</table>';
}

 ?>

<?php
        
      }
      if($ii==0){header('location:../rapports_dynamiques.php');}
    }
      else
        {header('location:../rapport_dynamiques.php');}
?>






