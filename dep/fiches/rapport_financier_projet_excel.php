<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
if (!isset ($_SESSION["id"])) {
    //header(sprintf("Location: %s", "./login.php"));
    exit();
}
require_once 'api/Fonctions.php';
require_once 'api/essentiel.php';
header('Content-Type: text/html; charset=UTF-8');

function cellColor($cells,$color){
    global $objPHPExcel;
    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}
extract($_GET);
if(isset($annee) && intval($annee)>0) $annee=intval($annee); else $annee=date("Y");
if(isset($cmp) && intval($cmp)>0) $cmp = intval($cmp); else $cmp = 0;
$query_liste_projet = $db ->prepare('SELECT * FROM t_projets P WHERE P.id_projet=:id_projet');
$query_liste_projet->execute(array(':id_projet' => isset($_SESSION['projet'])?$_SESSION['projet']:0));
$row_liste_projet = $query_liste_projet ->fetch();
$totalRows_liste_projet = $query_liste_projet->rowCount();
$query_act = $db ->prepare('SELECT * FROM t_ptba WHERE annee=:annee and projet=:projet ORDER BY code_activite_ptba asc');
$query_act->execute(array(':annee' => $annee,':projet' => isset($_SESSION['projet'])?$_SESSION['projet']:0));
$row_act = $query_act ->fetchAll();
$totalRows_act = $query_act->rowCount();
//Montant projet bailleur
$query_liste_cout_saisi = $db ->prepare('SELECT sum(montant_act) as montant_act, id_ptba  FROM t_cout_activite_partenaire, t_ptba where activite_bud=id_ptba and  annee=:annee and projet=:projet group by id_ptba');
$query_liste_cout_saisi->execute(array(':annee' => $annee,':projet' => isset($_SESSION['projet'])?$_SESSION['projet']:0));
$row_liste_cout_saisi = $query_liste_cout_saisi ->fetchAll();
$totalRows_liste_cout_saisi = $query_liste_cout_saisi->rowCount();
$tableauCoutSaisi = array();
if($totalRows_liste_cout_saisi>0){  foreach($row_liste_cout_saisi as $row_liste_cout_saisi){
$tableauCoutSaisi[$row_liste_cout_saisi["id_ptba"]]=$row_liste_cout_saisi["montant_act"]; } }
$query_entete = $db ->prepare('SELECT libelle,nombre FROM t_config_cadre_resultat_projet WHERE projet=:projet LIMIT 1');
$query_entete->execute(array(':projet' => isset($_SESSION['projet'])?$_SESSION['projet']:0));
$row_entete = $query_entete ->fetch();
$totalRows_entete = $query_entete->rowCount();
$code_len = 0; $code_libelle = array();
if($totalRows_entete>0){ $code_len = $row_entete["nombre"]; $code_libelle = explode(",",$row_entete["libelle"]); }

$query_liste_activite_1 = $db ->prepare('SELECT * FROM t_cadre_resultat WHERE projet=:projet order by niveau,parent');//niveau=:niveau and
$query_liste_activite_1->execute(array(/*':niveau' => $code_len,*/':projet' => isset($_SESSION['projet'])?$_SESSION['projet']:0));
$row_liste_activite_1 = $query_liste_activite_1 ->fetchAll();
$totalRows_liste_activite_1 = $query_liste_activite_1->rowCount();
$libelle_cmp_array = $libelle_cmp_all_array = $libelle_cmp_all_id_array = array();
if($totalRows_liste_activite_1>0){  foreach($row_liste_activite_1 as $row_liste_activite_1){
if($row_liste_activite_1["niveau"]==$code_len) $libelle_cmp_array[$row_liste_activite_1["code"]] = $row_liste_activite_1["code"]." ".$row_liste_activite_1["intitule"]; $libelle_cmp_all_array[$row_liste_activite_1["code"]] = array($row_liste_activite_1["code"],$row_liste_activite_1["intitule"],$row_liste_activite_1["parent"],$row_liste_activite_1["id_cadre_resultat"],$row_liste_activite_1["niveau"],$row_liste_activite_1["budget_activite"]); $libelle_cmp_all_id_array[$row_liste_activite_1["id_cadre_resultat"]] = array($row_liste_activite_1["code"],$row_liste_activite_1["intitule"],$row_liste_activite_1["parent"],$row_liste_activite_1["id_cadre_resultat"],$row_liste_activite_1["niveau"],$row_liste_activite_1["budget_activite"]); } }

$query_liste_cadre_strategique = $db ->prepare('SELECT C.* FROM t_indicateur_cadre_resultat C WHERE C.projet=:projet and C.code_cr IN (SELECT T.id_cadre_resultat FROM t_cadre_resultat T WHERE T.projet=:projet and T.niveau=C.niveau) order by C.niveau,C.code_cr');
$query_liste_cadre_strategique->execute(array(':projet' => isset($_SESSION['projet'])?$_SESSION['projet']:0));
$row_liste_cadre_strategique = $query_liste_cadre_strategique ->fetchAll();
$totalRows_liste_cadre_strategique = $query_liste_cadre_strategique->rowCount();
$libelle_cmp_indicateur_array = array();
if($totalRows_liste_cadre_strategique>0){ foreach($row_liste_cadre_strategique as $row_liste_cadre_strategique1){ $libelle_cmp_indicateur_array[$row_liste_cadre_strategique1["code_cr"]][] = $row_liste_cadre_strategique1; } }

$tableauMois= array('T1','T2','T3','T4');

$query_liste_prestataire = $db ->prepare('SELECT * FROM t_structures');
$query_liste_prestataire->execute();
$row_liste_prestataire = $query_liste_prestataire ->fetchAll();
$totalRows_liste_prestataire = $query_liste_prestataire->rowCount();
$acteur_array =$Nacteur_array = array();
if($totalRows_liste_prestataire>0){  foreach($row_liste_prestataire as $row_liste_prestataire){
    $acteur_array[$row_liste_prestataire["id_structure"]] = $row_liste_prestataire["sigle"];
    $Nacteur_array[$row_liste_prestataire["id_structure"]] = $row_liste_prestataire["nom_structure"]; } }

$query_liste_partenaire = $db ->prepare('SELECT * FROM t_partenaires');
$query_liste_partenaire->execute();
$row_liste_partenaire = $query_liste_partenaire ->fetchAll();
$totalRows_liste_partenaire = $query_liste_partenaire->rowCount();
$partenaire_array = array();
if($totalRows_liste_partenaire>0){  foreach($row_liste_partenaire as $row_liste_partenaire1){
$partenaire_array[$row_liste_partenaire1["id_partenaire"]] = $row_liste_partenaire1["sigle_partenaire"];
$Npartenaire_array[$row_liste_partenaire1["id_partenaire"]] = $row_liste_partenaire1["nom_partenaire"]; } }

$query_liste_sous_prefecture = $db ->prepare('SELECT id_sous_prefecture,nom_sous_prefecture FROM t_sous_prefecture order by nom_sous_prefecture');
$query_liste_sous_prefecture->execute();
$row_liste_sous_prefecture = $query_liste_sous_prefecture ->fetchAll();
$totalRows_liste_sous_prefecture = $query_liste_sous_prefecture->rowCount();
$sous_prefecture_array = array();
if($totalRows_liste_sous_prefecture>0){  foreach($row_liste_sous_prefecture as $row_liste_partenaire1){
$sous_prefecture_array[$row_liste_partenaire1["id_sous_prefecture"]] = $row_liste_partenaire1["nom_sous_prefecture"]; } }

//Bailleurs
$query_liste_bailleur = $db ->prepare('SELECT * FROM t_partenaires WHERE FIND_IN_SET(:type_partenaire,type_partenaire)  ORDER BY nom_partenaire asc');
$query_liste_bailleur->execute(array(':type_partenaire' => 4));
$row_liste_bailleur = $query_liste_bailleur ->fetchAll();
$totalRows_liste_bailleur = $query_liste_bailleur->rowCount();

//Montant projet bailleur tranche
$query_projet_tranche = $db ->prepare('SELECT montant, bailleur_bud, structure_bud, tranche FROM t_repartition_projet_budget WHERE projet_bud=:id_projet group by bailleur_bud,structure_bud,tranche');
$query_projet_tranche->execute(array(':id_projet' => $row_liste_projet['id_projet']));
$row_projet_tranche = $query_projet_tranche ->fetchAll();
$totalRows_projet_tranche = $query_projet_tranche->rowCount();
$tranche_array = $tranche_agence_array = $projet_tranche_array = array();
if($totalRows_projet_tranche>0){ $i=1; foreach($row_projet_tranche as $row_projet_tranche1){
    $tranche_array[$row_projet_tranche1["bailleur_bud"]][$row_projet_tranche1["structure_bud"]][$row_projet_tranche1["tranche"]] = $row_projet_tranche1["montant"];
    if(!isset($tranche_agence_array[$row_projet_tranche1["structure_bud"]]))
    $tranche_agence_array[$row_projet_tranche1["structure_bud"]] = isset($liste_structure_array[$row_projet_tranche1["structure_bud"]])?$liste_structure_array[$row_projet_tranche1["structure_bud"]]:"-";
    $projet_tranche_array[$row_projet_tranche1["tranche"]] = "";
} }
if(count($projet_tranche_array)<=0) $tableauTranche=array(1); else $tableauTranche = array_keys($projet_tranche_array);

setlocale (LC_TIME, 'fr_FR.utf8','fra');
include_once('./libs/PHPExcel/PHPExcel.php');
$objPHPExcel = new PHPExcel();
$col = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","R","S","T","U","V","W","X","Y","Z"); $ligne_limit = 20; $ActiveSheet=0;  $titre = 'Tableau_de_suivi';
$a = explode(",",$row_liste_projet['agence_lead'].(!empty($row_liste_projet['autres_agences_recipiendaires'])?",".$row_liste_projet['autres_agences_recipiendaires']:"")); if(count($a)>0){ $i=0; foreach($a as $b){ $titre.="_".(isset($partenaire_array[$b])?$partenaire_array[$b]:$b); $i++; } } $nbr_agence = $i;
$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM
        )
    ),
    'alignment' => array(
    //'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    //'shrinkToFit'=>true,
    //'wrap' => true // retour à la ligne automatique
    )
);
//$objPHPExcel->createSheet();
$objPHPExcel->getProperties()->setCreator("BAMASOFT NETWORK")
                ->setLastModifiedBy("RUCHE")
                ->setTitle("Rapport Financier Projet")
                ->setSubject("Rapport Financier Projet")
                ->setDescription("Rapport Financier Projet, generated by BAMASOFT NETWORK, RUCHE")
                ->setKeywords("Rapport Financier Projet")
                ->setCategory("Rapport Financier Projet");
$objPHPExcel->setActiveSheetIndex($ActiveSheet);
$objPHPExcel->getActiveSheet()->setTitle("Budget par Agence");
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Annexe D - Budget du projet PBF");
$objPHPExcel->getActiveSheet()->setCellValue('A3', "Note: S'il s'agit de revision de projet, veuillez inclure colonnes additionnelles pour montrer le changement.");
$objPHPExcel->getActiveSheet()->setCellValue('A5', "Tableau 1 - Budget du projet PBF par resultat, produit et activite");
/*$a = explode(",",$row_liste_projet['agence_lead'].",".$row_liste_projet['autres_agences_recipiendaires']); if(count($a)>0){ $i=1; foreach($a as $b){
if(file_exists("./images/partenaire/img_$b.jpg")){ $column = $col[14-$i];
$cell = "1"; $objDrawing = new PHPExcel_Worksheet_Drawing();$objDrawing->setName('PNUD');$objDrawing->setDescription('RUCHE');$objDrawing->setPath("./images/partenaire/img_$b.jpg");$objDrawing->setOffsetX(25);$objDrawing->setOffsetY(10);$objDrawing->setCoordinates($column.$cell);$objDrawing->setWidth(100);/*$objDrawing->setHeight(100);*//*$objDrawing->setResizeProportional(true);$objDrawing->setWorksheet($objPHPExcel->getActiveSheet()); $i++; } } }*/
ob_start(); ?>
<style>table{display: none;}</style>
<table class="table table-striped table-bordered table-hover table-responsive table-checkable table-tabletools table-colvis dataTable" style="display:none;" id="mtable" border="1" >
<thead>
<tr>
<th width="31.42" height="31.42">Nombre de resultat / produit</th>
<th width="31.42">Formulation du resultat/ produit/ activite</th>
<?php if(count($a)>0){ $i=0; foreach($a as $b){ ?>
<th width="32.14">Budget par agence recipiendiaire en USD<br><?php echo (isset($partenaire_array[$b])?$partenaire_array[$b]:$b); ?></th>
<?php $i++; } } ?>
<th width="32.14">Pourcentage du budget pour chaque produit ou activite reserve pour action directe sur le genre (cas echeant) </th>
<th width="32.14">Niveau de depense/ engagement actuel en USD (a remplir au moment des rapports de projet)</th>
<th width="32.14">Notes quelconque le cas echeant (.e.g sur types des entrants ou justification du budget)</th>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="" >
<?php
$where = " niveau =1";
$wh = "";
$projet = $row_liste_projet["id_projet"];
$query_entete = $db ->prepare('SELECT * FROM t_config_cadre_resultat_projet WHERE projet=:projet LIMIT 1');
$query_entete->execute(array(':projet' => $projet));
$row_entete = $query_entete ->fetch();
$totalRows_entete = $query_entete->rowCount();
$libelle = array();
if($totalRows_entete>0){ $libelle=explode(",",$row_entete["libelle"]);
if(isset($libelle[0]) && !empty($libelle[0])){
$query_liste_activite = $db ->prepare('SELECT * FROM t_cadre_resultat WHERE niveau =1 and projet=:projet ORDER BY niveau,code ASC');
$query_liste_activite->execute(array(':projet' => $projet));
$row_liste_activite = $query_liste_activite ->fetchAll();
$totalRows_liste_activite = $query_liste_activite->rowCount();
} $n = count($libelle);
$t=0; $i=0; if(isset($totalRows_liste_activite) && $totalRows_liste_activite>0) {
function trace_tr($niveau,$j,$n,$libelle,$libelle1,$nbr_agence)
{
    $data = "";
    $data .= "<tr>";
    //for($k=0;$k<$j;$k++){ $data .= "<td width='30' align='right'>&nbsp;</td>"; }
    $data .= "<td ".(($niveau==0 && $j>=0)?"colspan='".(5+$nbr_agence)."' color='66CCFF'":"").">".($niveau+1==$n?"":"<b>").$libelle." ".$libelle1["code"]." : ".$libelle1["intitule"].($niveau+1==$n?"":"</b>")."</td>";
    if($niveau!=0 || $j<0){
    $data .= "<td align='right'> </td>";
    for($i=1;$i<=$nbr_agence;$i++)
    $data .= "<td align='right'> </td>";
    $data .= "<td align='right'> </td>";
    $data .= "<td align='right'> </td>";
    $data .= "<td align='right'> </td>"; }
$data .= "</tr>";
    return $data;
}
//$niveau_indent limite = 6;
$niveau_indent = $n;   $k = 0;

$query_liste_activite_1 = $db ->prepare('SELECT * FROM t_cadre_resultat WHERE '.$where.' '.$wh.' and projet=:projet ORDER BY niveau,code ASC');
$query_liste_activite_1->execute(array(':projet' => $projet));
$row_liste_activite_1 = $query_liste_activite_1 ->fetchAll();
$totalRows_liste_activite_1 = $query_liste_activite_1->rowCount();
foreach($row_liste_activite_1 as $row_liste_activite_1)
{
    $niveau_indent = $n; $k = $j = 0;
    if($niveau_indent-$j>0)
    {
        $code_1 = $row_liste_activite_1["code"]; $id_1 = $row_liste_activite_1["id_cadre_resultat"];
        //traitement ici
        echo  trace_tr($k,$j,$n,$libelle[$k],$row_liste_activite_1,$nbr_agence);

        $query_liste_activite_2 = $db ->prepare('SELECT * FROM t_cadre_resultat WHERE niveau=:niveau and parent=:parent and projet=:projet ORDER BY niveau,code ASC');
        $query_liste_activite_2->execute(array(':niveau' => $j+2,':parent' => $id_1,':projet' => $projet));
        $row_liste_activite_2 = $query_liste_activite_2 ->fetchAll();
        $totalRows_liste_activite_2 = $query_liste_activite_2->rowCount();
        if($totalRows_liste_activite_2>0) { foreach($row_liste_activite_2 as $row_liste_activite_2)
        {
            $j=1; $k=1;
            if($niveau_indent-$j>0)
            {
                $code_2 = $row_liste_activite_2["code"]; $id_2 = $row_liste_activite_2["id_cadre_resultat"];
                //traitement ici
                echo  trace_tr($k,$j,$n,$libelle[$k],$row_liste_activite_2,$nbr_agence);

                $query_liste_activite_3 = $db ->prepare('SELECT * FROM t_cadre_resultat WHERE niveau=:niveau and parent=:parent and projet=:projet ORDER BY niveau,code ASC');
                $query_liste_activite_3->execute(array(':niveau' => $j+2,':parent' => $id_2,':projet' => $projet));
                $row_liste_activite_3 = $query_liste_activite_3 ->fetchAll();
                $totalRows_liste_activite_3 = $query_liste_activite_3->rowCount();
                if($totalRows_liste_activite_3>0) { foreach($row_liste_activite_3 as $row_liste_activite_3)
                {
                    if($niveau_indent-$j>0)
                    {
                        $j=2; $k=2;
                        $code_3 = $row_liste_activite_3["code"]; $id_3 = $row_liste_activite_3["id_cadre_resultat"];
                        //traitement ici
                        echo  trace_tr($k,$j,$n,$libelle[$k],$row_liste_activite_3,$nbr_agence);
                        $query_liste_activite_4 = $db ->prepare('SELECT * FROM t_cadre_resultat WHERE niveau=:niveau and parent=:parent and projet=:projet ORDER BY niveau,code ASC');
                        $query_liste_activite_4->execute(array(':niveau' => $j+2,':parent' => $id_3,':projet' => $projet));
                        $row_liste_activite_4 = $query_liste_activite_4 ->fetchAll();
                        $totalRows_liste_activite_4 = $query_liste_activite_4->rowCount();
                        if($totalRows_liste_activite_4>0) { foreach($row_liste_activite_4 as $row_liste_activite_4)
                        {
                            if($niveau_indent-$j>0)
                            {
                                $j=3; $k=3;
                                $code_4 = $row_liste_activite_4["code"]; $id_4 = $row_liste_activite_4["id_cadre_resultat"];
                                //traitement ici
                                echo  trace_tr($k,$j,$n,$libelle[$k],$row_liste_activite_4,$nbr_agence);

                                $query_liste_activite_5 = $db ->prepare('SELECT * FROM t_cadre_resultat WHERE niveau=:niveau and parent=:parent and projet=:projet ORDER BY niveau,code ASC');
                                $query_liste_activite_5->execute(array(':niveau' => $j+2,':parent' => $id_4,':projet' => $projet));
                                $row_liste_activite_5 = $query_liste_activite_5 ->fetchAll();
                                $totalRows_liste_activite_5 = $query_liste_activite_5->rowCount();
                                if($totalRows_liste_activite_5>0) { foreach($row_liste_activite_5 as $row_liste_activite_5)
                                {
                                    if($niveau_indent-$j>0)
                                    {
                                        $j=4; $k=4;
                                        $code_5 = $row_liste_activite_5["code"]; $id_5 = $row_liste_activite_5["id_cadre_resultat"];
                                        //traitement ici
                                        echo  trace_tr($k,$j,$n,$libelle[$k],$row_liste_activite_5,$nbr_agence);
                                        $query_liste_activite_6 = $db ->prepare('SELECT * FROM t_cadre_resultat WHERE niveau=:niveau and parent=:parent and projet=:projet ORDER BY niveau,code ASC');
                                        $query_liste_activite_6->execute(array(':niveau' => $j+2,':parent' => $id_5,':projet' => $projet));
                                        $row_liste_activite_6 = $query_liste_activite_6 ->fetchAll();
                                        $totalRows_liste_activite_6 = $query_liste_activite_6->rowCount();
                                        if($totalRows_liste_activite_6>0) { foreach($row_liste_activite_6 as $row_liste_activite_6)
                                        {
                                            //activite limite ici à niveau 6
                                            $code_6 = $row_liste_activite_6["code"];
                                            $id_6 = $row_liste_activite_6["id_cadre_resultat"];
                                            //traitement ici
                                            echo  trace_tr($k,$j,$n,$libelle[$k],$row_liste_activite_6,$nbr_agence);

                                        } }
                                    }
                                } }
                            }
                        } }
                    }
                } }
            }
        } }
        $niveau_indent = $n; $k = $j = 0;  $row_liste_activite_1["intitule"]="";
        $code_1 = $row_liste_activite_1["code"]; $id_1 = $row_liste_activite_1["id_cadre_resultat"];
        //traitement ici
        echo  trace_tr($k,-1,$n,"Total $ pour ".$libelle[$k],$row_liste_activite_1,$nbr_agence);
    }
}
} ?>
<tr>
<td gras="1">Coordination et M&E</td>
<td gras="1"> </td>
<?php if(count($a)>0){ $i=0; foreach($a as $b){ ?>
<th gras="1"><?php echo (isset($partenaire_array[$b])?$partenaire_array[$b]:$b); ?></th>
<?php $i++; } } ?>
<td gras="1"> </td>
<td gras="1"> </td>
<td gras="1"> </td>
</tr>
<tr>
<td gras="1" color="A6E7EA">SOUS TOTAL DU BUDGET DE PROJET :</td>
<td gras="1" color="A6E7EA"> </td>
<?php if(count($a)>0){ $i=0; foreach($a as $b){ ?>
<th gras="1" color="A6E7EA"><?php echo (isset($partenaire_array[$b])?$partenaire_array[$b]:$b); ?></th>
<?php $i++; } } ?>
<td gras="1" color="A6E7EA"> </td>
<td gras="1" color="A6E7EA"> </td>
<td gras="1" color="A6E7EA"> </td>
</tr>
<tr>
<td gras="1">Couts indirects (7%) :</td>
<td gras="1"> </td>
<?php if(count($a)>0){ $i=0; foreach($a as $b){ ?>
<th gras="1"><?php echo (isset($partenaire_array[$b])?$partenaire_array[$b]:$b); ?></th>
<?php $i++; } } ?>
<td gras="1"> </td>
<td gras="1"> </td>
<td gras="1"> </td>
</tr>
<tr>
<td gras="1" color="A6E7EA">BUDGET TOTAL DU PROJET :</td>
<td gras="1" color="A6E7EA"> </td>
<?php if(count($a)>0){ $i=0; foreach($a as $b){ ?>
<th gras="1" color="A6E7EA"><?php echo (isset($partenaire_array[$b])?$partenaire_array[$b]:$b); ?></th>
<?php $i++; } } ?>
<td gras="1" color="A6E7EA"> </td>
<td gras="1" color="A6E7EA"> </td>
<td gras="1" color="A6E7EA"> </td>
</tr>
<?php } else{ ?>
<tr>
<td gras="1" <?php echo 'color="A6E7EA" align="center" colspan="'.(12).'"'; ?>>Aucun cadre de résultat programmé</td>
</tr>
<?php } ?>
</tbody></table>
<?php $page = ob_get_contents(); ob_end_clean();
include('./libs/PHPExcel/PHPExcel/php_excel.php');
$html = str_get_html($page); $table = $html->find('table[id="mtable"]',0); $rows = $html->find('tr'); $k=7;
foreach ($rows as $row) { $j=0;
        foreach ($row->children() as $cell) { //$attr = $cell->attr; echo "$col[$j]$k ->".$cell->plaintext."->".((isset($attr["colspan"]) && $attr["colspan"]>1)?"colspan=".$attr["colspan"]:'').((isset($attr["rowspan"]) && $attr["rowspan"]>1)?"rowspan=".$attr["rowspan"]:'')."\t";
$objPHPExcel->getActiveSheet()->getStyle("$col[$j]$k")->applyFromArray($styleArray);
if(isset($rowspan_array[$j]) && $rowspan_array[$j][0]==$j){ if($rowspan_array[$j][1]>=$k){  $rowspan = 1; while(isset($rowspan_array[$j]) && $rowspan_array[$j][0]==$j){if(isset($rowspan_array[$j]) && $rowspan_array[$j][0]==$j && $rowspan_array[$j][1]==$k){ unset($rowspan_array[$j]); } $j++;} } else $rowspan = 0; } else $rowspan = 0;  if($rowspan==1){ /*echo $rowspan." colonne $j, ligne $k".utf8_encode($cell->plaintext); exit;*/ } //echo "$col[$j]$k".utf8_encode($cell->plaintext)."\t";
//$objPHPExcel->getActiveSheet()->getStyle("$col[$j]$k")->applyFromArray($styleArray);
//if($rowspan==0)
$objPHPExcel->getActiveSheet()->getStyle("$col[$j]$k")->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->setCellValue("$col[$j]$k", ($cell->plaintext));
$attr = $cell->attr;//if($rowspan==0) echo "$col[$j]$k ->".$cell->plaintext."\t";
if($cell->tag=="th"){ cellColor("$col[$j]$k",'CCCCCC'); $objPHPExcel->getActiveSheet()->getStyle("$col[$j]$k")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); $objPHPExcel->getActiveSheet()->getStyle("$col[$j]$k")->getFont()->setBold(true); }
/*if($j==0) $objPHPExcel->getActiveSheet()->getColumnDimension("$col[$j]$k")->setWidth(30.71); else
$objPHPExcel->getActiveSheet()->getColumnDimension("$col[$j]")->setAutoSize(true); */
if(isset($attr["height"])){ $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn("$col[$j]")->setAutoSize(false);$objPHPExcel->getActiveSheet()->getRowDimension($k)->setRowHeight($attr["height"]); } //else $objPHPExcel->getActiveSheet()->getRowDimension($k)->setRowHeight(-1);
if(isset($attr["width"])){ $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn("$col[$j]")->setAutoSize(false);$objPHPExcel->getActiveSheet()->getColumnDimension("$col[$j]")->setWidth($attr["width"]); } else $objPHPExcel->getActiveSheet()->getStyle("$col[$j]1:$col[$j]".$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
if(isset($attr["format"])){ $objPHPExcel->getActiveSheet()->getStyle("$col[$j]")->getNumberFormat()->setFormatCode($attr["format"]); }
if(isset($attr["color"])) cellColor("$col[$j]$k",$attr["color"]); 
$objPHPExcel->getActiveSheet()->getStyle("$col[$j]$k")->getAlignment()->setVERTICAL(PHPExcel_Style_Alignment::VERTICAL_CENTER);
if(isset($attr["gras"])) $objPHPExcel->getActiveSheet()->getStyle("$col[$j]$k")->getFont()->setBold(true);
if(isset($attr["align"])) $objPHPExcel->getActiveSheet()->getStyle("$col[$j]$k")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
if(isset($attr["colspan"]) && $attr["colspan"]>1){
$objPHPExcel->getActiveSheet()->mergeCells($col[$j].$k.":".$col[$j+$attr["colspan"]-1].$k);$objPHPExcel->getActiveSheet()->getStyle($col[$j].$k.":".$col[$j+$attr["colspan"]-1].$k)->applyFromArray($styleArray); $j+= $attr["colspan"]-1; }
if(isset($attr["rowspan"]) && $attr["rowspan"]>1){
$objPHPExcel->getActiveSheet()->mergeCells($col[$j].$k.":".$col[$j].($k+$attr["rowspan"]-1)); $objPHPExcel->getActiveSheet()->getStyle($col[$j].$k.":".$col[$j].($k+$attr["rowspan"]-1))->applyFromArray($styleArray);
$rowspan_array[$j] = array($j,$k+$attr["rowspan"]-1); /*print_r($rowspan_array); exit;*/$objPHPExcel->getActiveSheet()->getStyle($col[$j].$k.":".$col[$j].($k+$attr["rowspan"]-1))->applyFromArray($styleArray); }
//if($rowspan==0)
//$objPHPExcel->getActiveSheet()->getStyle("$col[$j]1:$col[$j]".$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
//$objPHPExcel->getActiveSheet()->getRowDimension($k)->setRowHeight(-1);
if(isset($rowspan_array[$j]) && $rowspan_array[$j][0]==$j && $rowspan_array[$j][1]==$k){ unset($rowspan_array[$j]); }
$j++; //if($rowspan>0) $rowspan--; if($rowspan<0) $rowspan=0;
        }
    /*if($k==2){
    $objPHPExcel->getActiveSheet()->getStyle("A$k:".$col[($j-1)]."$k")->getFont()->setBold(true);
    cellColor("A$k:".$col[($j-1)]."$k",'EEEEEE');
    }*/ //echo "<br>";
$k++; }   //exit;

$ligne_limit=$k+1; $k=0;
for($i=1;$i<=6;$i++){
$objPHPExcel->getActiveSheet()->getStyle($col[0].$i)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->mergeCells($col[0].$i.":".$col[5+$nbr_agence].$i); }
$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle("A3")->getFont()->setSize(8);
$objPHPExcel->getActiveSheet()->getStyle("A5")->getFont()->setSize(8);
/*$objPHPExcel->getActiveSheet()->getStyle("A1:".$col[5+$nbr_agence]."6")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);*/
$objPHPExcel->getActiveSheet()->getStyle("A1:".$col[5+$nbr_agence]."6")->getAlignment()->setVERTICAL(PHPExcel_Style_Alignment::VERTICAL_CENTER);
//$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(-1);
$objPHPExcel->createSheet();$ActiveSheet++;
$objPHPExcel->setActiveSheetIndex($ActiveSheet);
$objPHPExcel->getActiveSheet()->setTitle("Budget par catégorie");
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Tableau 2 - Budget de projet PBF par categorie de cout de l'ONU");
$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('A3', "Note: S'il s'agit d'une revision budgetaire, veuillez inclure des colonnes additionnelles pour montrer les changements");
$objPHPExcel->getActiveSheet()->getStyle("A3")->getFont()->setSize(8);
$objPHPExcel->getActiveSheet()->getStyle("A3")->getFont()->setBold(true);
for($i=1;$i<=4;$i++){
//$objPHPExcel->getActiveSheet()->getStyle($col[0].$i)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->mergeCells($col[0].$i.":".$col[1+(count($tableauTranche)*count($a))+count($tableauTranche)].$i); } ?>
<table class="table table-striped table-bordered table-hover table-responsive table-checkable table-tabletools table-colvis dataTable" style="display:none;" id="mtable1" border="1" >
<thead>
<tr>
<th rowspan="2" width="31.42">CATEGORIES</th>
<?php if(count($a)>0){ $i=0; foreach($a as $b){ ?>
<th colspan="<?php echo count($tableauTranche); ?>" width="31.42">Agence Recipiendiaire<br><?php echo (isset($partenaire_array[$b])?$partenaire_array[$b]:$b); ?></th>
<?php $i++; } } ?>
<?php if(count($tableauTranche)>0){ foreach($tableauTranche as $b){ ?>
<th rowspan="2" width="31.42">Total<br>Tranche <?php echo $b; ?></th>
<?php } } ?>
<th rowspan="2" width="31.42">TOTAL PROJET</th>
</tr>
<tr>
<?php if(count($a)>0){ $i=0; foreach($a as $b){ if(count($tableauTranche)>0){ foreach($tableauTranche as $c){ ?>
<th width="31.42">Tranche <?php echo $c; ?><br>(70%)</th>
<?php } } $i++; } } ?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="" >
<?php
//Catégorie de dépense
$total_l=0; $aa = $a;
$total_c=array();
$query_liste_cat_dep = $db ->prepare('SELECT * FROM t_categorie_depense  GROUP BY code');
$query_liste_cat_dep->execute(/*array(':projet' => isset($_SESSION['projet'])?$_SESSION['projet']:0)*/);
$row_liste_cat_dep = $query_liste_cat_dep ->fetchAll();
$totalRows_liste_cat_dep = $query_liste_cat_dep->rowCount();

//Montant projet bailleur
$query_projet_cout = $db ->prepare('SELECT * FROM t_repartition_budget_categorie WHERE projet_bud=:id_projet');
$query_projet_cout->execute(array(':id_projet' => isset($_SESSION['projet'])?$_SESSION['projet']:0));
$row_projet_cout = $query_projet_cout ->fetchAll();
$totalRows_projet_cout = $query_projet_cout->rowCount();
$projet_cout_array  =$projet_tranche_array  = array();
if($totalRows_projet_cout>0){  foreach($row_projet_cout as $row_projet_cout){
$projet_cout_array[$row_projet_cout["categorie_bud"]][$row_projet_cout["tranche"]][$row_projet_cout["agence_bud"]]=$row_projet_cout["montant"];
if(!empty($row_projet_cout["montant"])) $projet_tranche_array[$row_projet_cout["tranche"]] = "";
} }  $cout_indirect = $total=array(); $i=0;
foreach($row_liste_cat_dep as $row_liste_cat_dep) { $j=0; $c = array();  $id = $row_liste_cat_dep['id_categorie']; $code = $row_liste_cat_dep['code']; $siglec = $row_liste_cat_dep['nom_categorie']; if(!preg_match("#coûts indirects|indirects#si",strtolower($row_liste_cat_dep['nom_categorie']))){ ?>
<tr>
<td><?php echo $code.". $siglec"; ?></td>
<?php if(count($aa)>0){ foreach($aa as $bb){ if(count($tableauTranche)>0){ foreach($tableauTranche as $cc){ ?>
<td><?php if(isset($projet_cout_array[$id][$cc][$bb])){ echo $projet_cout_array[$id][$cc][$bb]; $total_l += $projet_cout_array[$id][$cc][$bb];
if(!isset($total_c[$id])) $total_c[$id]=$projet_cout_array[$id][$cc][$bb]; else $total_c[$id] += $projet_cout_array[$id][$cc][$bb];
if(!isset($total_t[$cc])) $total_t[$cc]=$projet_cout_array[$id][$cc][$bb]; else $total_t[$cc] += $projet_cout_array[$id][$cc][$bb];
if(!isset($total_at[$bb][$cc])) $total_at[$bb][$cc]=$projet_cout_array[$id][$cc][$bb]; else $total_at[$bb][$cc] += $projet_cout_array[$id][$cc][$bb];  } ?></td>
<?php } } } } ?>
<?php if(count($tableauTranche)>0){ foreach($tableauTranche as $bb1){  if(!isset($total_gt[$bb1])) $total_gt[$bb1]=0; ?>
<td><?php if(isset($total_t[$bb1])) {echo $total_t[$bb1]; $total_gt[$bb1]+=$total_t[$bb1];} ?></td>
<?php } } ?>
<td><?php if(isset($total_c[$id])) echo $total_c[$id]; ?></td>
</tr>
<?php $i++; } else $cout_indirect=$row_liste_cat_dep; } ?>
<tr>
<td gras="1" color="A6E7EA">Sous-total</td>
<?php if(count($aa)>0){ foreach($aa as $bbi){ if(count($tableauTranche)>0){ foreach($tableauTranche as $cci){ ?>
<td color="A6E7EA"><?php echo isset($total_at[$bbi][$cci])?$total_at[$bbi][$cci]:0; ?></td>
<?php } } } } ?>
<?php if(count($tableauTranche)>0){$stg=0; foreach($tableauTranche as $bb1){ ?>
<td color="A6E7EA"> <?php if(isset($total_gt[$bb1])) {echo $total_gt[$bb1]; $stg=$stg+$total_gt[$bb1];} ?></td>
<?php }} ?>
<td color="A6E7EA"><?php echo $stg; ?> </td>
</tr>
<?php if(count($cout_indirect)>0){ $row_liste_cat_dep = $cout_indirect; $j=0; $c = array(); $id = $row_liste_cat_dep['id_categorie']; $code = $row_liste_cat_dep['code']; $siglec = $row_liste_cat_dep['nom_categorie'];  ?>
<tr>
<td><?php echo $code.". $siglec"; ?></td>
<?php if(count($aa)>0){ foreach($aa as $bf){ if(count($tableauTranche)>0){ foreach($tableauTranche as $cf){ ?>
<td><?php if(isset($projet_cout_array[$id][$cf][$bf])){ echo $projet_cout_array[$id][$cf][$bf];
if(!isset($total_c[$id])) $total_c[$id]=$projet_cout_array[$id][$cf][$bf]; else $total_c[$id] += $projet_cout_array[$id][$cf][$bf];
if(!isset($total_t[$cf])) $total_t[$cf]=$projet_cout_array[$id][$cf][$bf]; else $total_t[$cf] += $projet_cout_array[$id][$cf][$bf];
if(!isset($total_ati[$bf][$cf])) $total_ati[$bf][$cf]=$projet_cout_array[$id][$cf][$bf]; else $total_ati[$bf][$cf] += $projet_cout_array[$id][$cf][$bf];
} ?></td>
<?php } } } } ?>
<?php if(count($tableauTranche)>0){ foreach($tableauTranche as $b){ ?>
<td><?php if(isset($total_t[$b])) echo $total_t[$b]; ?></td>
<?php } } ?>
<td><?php if(isset($total_c[$id])) echo $total_c[$id]; ?></td>
</tr>
<?php ?>
<?php } ?>
<tr>
<td gras="1" color="A6E7EA">TOTAL</td>
<?php if(count($aa)>0){ foreach($aa as $bf1){ if(count($tableauTranche)>0){ foreach($tableauTranche as $cf1){ ?>
<td gras="1" color="A6E7EA"><?php if(isset($total_ati[$bf1][$cf1])) $ttci=$total_ati[$bf1][$cf1]; else $ttci=0; $ttct1=isset($total_at[$bf1][$cf1])?($total_at[$bf1][$cf1]):0;
echo $ttct1+$ttci; ?></td>
<?php }}}} ?>
<?php $tftcg=0; if(count($tableauTranche)>0){ foreach($tableauTranche as $b){ ?>
<td gras="1" color="A6E7EA"><?php if(isset($total_gt[$b])) {$ttct=$total_gt[$b];} else $ttct=0; if(isset($total_t[$b])) $ttcif=$total_t[$b]; else $ttcif=0;
echo $ttct+$ttcif; $tftcg=$tftcg+$ttct+$ttcif; ?></td>
<?php }} ?>
<td gras="1" color="A6E7EA"><?php if(isset($tftcg) && $tftcg>0) echo $tftcg; ?></td>
</tr>
</tbody>
</table>
<?php $page = ob_get_contents(); ob_end_clean();
$html = str_get_html($page); $table = $html->find('table[id="mtable1"]',0); $rows = $html->find('tr'); $k=5;
foreach ($rows as $row) { $j=0;
        foreach ($row->children() as $cell) { //$attr = $cell->attr; echo "$col[$j]$k ->".$cell->plaintext."->".((isset($attr["colspan"]) && $attr["colspan"]>1)?"colspan=".$attr["colspan"]:'').((isset($attr["rowspan"]) && $attr["rowspan"]>1)?"rowspan=".$attr["rowspan"]:'')."\t";
$objPHPExcel->getActiveSheet()->getStyle("$col[$j]$k")->applyFromArray($styleArray);
if(isset($rowspan_array[$j]) && $rowspan_array[$j][0]==$j){ if($rowspan_array[$j][1]>=$k){  $rowspan = 1; while(isset($rowspan_array[$j]) && $rowspan_array[$j][0]==$j){if(isset($rowspan_array[$j]) && $rowspan_array[$j][0]==$j && $rowspan_array[$j][1]==$k){ unset($rowspan_array[$j]); } $j++;} } else $rowspan = 0; } else $rowspan = 0;  if($rowspan==1){ /*echo $rowspan." colonne $j, ligne $k".utf8_encode($cell->plaintext); exit;*/ } //echo "$col[$j]$k".utf8_encode($cell->plaintext)."\t";
//$objPHPExcel->getActiveSheet()->getStyle("$col[$j]$k")->applyFromArray($styleArray);
//if($rowspan==0)
$objPHPExcel->getActiveSheet()->getStyle("$col[$j]$k")->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->setCellValue("$col[$j]$k", ($cell->plaintext));
$attr = $cell->attr;//if($rowspan==0) echo "$col[$j]$k ->".$cell->plaintext."\t";
if($cell->tag=="th"){ cellColor("$col[$j]$k",'CCCCCC'); $objPHPExcel->getActiveSheet()->getStyle("$col[$j]$k")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); $objPHPExcel->getActiveSheet()->getStyle("$col[$j]$k")->getFont()->setBold(true); }
/*if($j==0) $objPHPExcel->getActiveSheet()->getColumnDimension("$col[$j]$k")->setWidth(30.71); else
$objPHPExcel->getActiveSheet()->getColumnDimension("$col[$j]")->setAutoSize(true); */
if(isset($attr["height"])){ $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn("$col[$j]")->setAutoSize(false);$objPHPExcel->getActiveSheet()->getRowDimension($k)->setRowHeight($attr["height"]); } //else $objPHPExcel->getActiveSheet()->getRowDimension($k)->setRowHeight(-1);
if(isset($attr["width"])){ $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn("$col[$j]")->setAutoSize(false);$objPHPExcel->getActiveSheet()->getColumnDimension("$col[$j]")->setWidth($attr["width"]); } else $objPHPExcel->getActiveSheet()->getStyle("$col[$j]1:$col[$j]".$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
if(isset($attr["format"])){ $objPHPExcel->getActiveSheet()->getStyle("$col[$j]")->getNumberFormat()->setFormatCode($attr["format"]); }
if(isset($attr["color"])) cellColor("$col[$j]$k",$attr["color"]);
$objPHPExcel->getActiveSheet()->getStyle("$col[$j]$k")->getAlignment()->setVERTICAL(PHPExcel_Style_Alignment::VERTICAL_CENTER);
if(isset($attr["gras"])) $objPHPExcel->getActiveSheet()->getStyle("$col[$j]$k")->getFont()->setBold(true);
if(isset($attr["align"])) $objPHPExcel->getActiveSheet()->getStyle("$col[$j]$k")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
if(isset($attr["colspan"]) && $attr["colspan"]>1){
$objPHPExcel->getActiveSheet()->mergeCells($col[$j].$k.":".$col[$j+$attr["colspan"]-1].$k);$objPHPExcel->getActiveSheet()->getStyle($col[$j].$k.":".$col[$j+$attr["colspan"]-1].$k)->applyFromArray($styleArray); $j+= $attr["colspan"]-1; }
if(isset($attr["rowspan"]) && $attr["rowspan"]>1){
$objPHPExcel->getActiveSheet()->mergeCells($col[$j].$k.":".$col[$j].($k+$attr["rowspan"]-1)); $objPHPExcel->getActiveSheet()->getStyle($col[$j].$k.":".$col[$j].($k+$attr["rowspan"]-1))->applyFromArray($styleArray);
$rowspan_array[$j] = array($j,$k+$attr["rowspan"]-1); /*print_r($rowspan_array); exit;*/$objPHPExcel->getActiveSheet()->getStyle($col[$j].$k.":".$col[$j].($k+$attr["rowspan"]-1))->applyFromArray($styleArray); }
//if($rowspan==0)
//$objPHPExcel->getActiveSheet()->getStyle("$col[$j]1:$col[$j]".$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);
//$objPHPExcel->getActiveSheet()->getRowDimension($k)->setRowHeight(-1);
if(isset($rowspan_array[$j]) && $rowspan_array[$j][0]==$j && $rowspan_array[$j][1]==$k){ unset($rowspan_array[$j]); }
$j++; //if($rowspan>0) $rowspan--; if($rowspan<0) $rowspan=0;
        }
    /*if($k==2){
    $objPHPExcel->getActiveSheet()->getStyle("A$k:".$col[($j-1)]."$k")->getFont()->setBold(true);
    cellColor("A$k:".$col[($j-1)]."$k",'EEEEEE');
    }*/ //echo "<br>";
$k++; }   //exit;

$ligne_limit=$k+1; $k=0;
/*$objPHPExcel->getActiveSheet()->setCellValue('A'.($ligne_limit), "Montant mobilisé auprès de PBSO");
$objPHPExcel->getActiveSheet()->setCellValue('A'.($ligne_limit+2), "Montant total engagé");
$objPHPExcel->getActiveSheet()->setCellValue('A'.($ligne_limit+4), "Montant total décaissé");
$objPHPExcel->setActiveSheetIndexByName('Worksheet');
$sheetIndex = $objPHPExcel->getActiveSheetIndex();
$objPHPExcel->removeSheetByIndex($sheetIndex);*/
/*$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('A14', "TITRE DU PROJET : ".strtoupper($_SESSION["projet_nom"]));*/
//Init
$objPHPExcel->setActiveSheetIndex(0);
/*foreach($col as $a=>$b){ if($a<14)
$objPHPExcel->getActiveSheet()->getColumnDimension($b)->setAutoSize(true); }
$objPHPExcel->getActiveSheet()->getStyle($col[0]."1:".$col[13].$ligne_limit.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);*/

require_once './libs/PHPExcel/PHPExcel/IOFactory.php';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// If you want to output e.g. a PDF file, simply do:
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
$objWriter->save('./telechargements/'.$titre.'.xlsx');
// Option 2 : fichier à télécharger par le navigateur
header('Content-Disposition: attachment;filename="'.$titre.'.xlsx"');
$objWriter->save('php://output');
//echo $valeur;  exit;
?>