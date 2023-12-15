<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////

$query_liste_part = "SELECT * FROM ".$database_connect_prefix."type_part WHERE bailleur='$code' and projet='".$_SESSION["clp_projet"]."' ";
try{
    $liste_part = $pdar_connexion->prepare($query_liste_part);
    $liste_part->execute();
    $row_liste_part = $liste_part ->fetchAll();
    $totalRows_liste_part = $liste_part->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>

<?php if($totalRows_liste_part>0) { $i=0; foreach($row_liste_part as $row_liste_part){ $idC = $row_liste_part['id_part']; $code1 = $row_liste_part['code_type']; ?>
<p style="cursor: pointer; color: yellow;" <?php
if(isset($_SESSION["clp_niveau"]) && $_SESSION["clp_niveau"]==0 ){
echo do_link("","","","","edit","./","","get_content('new_convention.php','id=$idC','modal-body_add',this.title);",1,"",$nfile,1);
}
?> href="#myModal_add" data-toggle="modal" title="<?php echo "Modifier Convention ".$row_liste_part['intitule']; ?>"><?php echo $code1."\t".$row_liste_part['intitule']." ===> <b>".number_format($row_liste_part['montant'], 0, ',', ' ')."</b>"; ?></p>
<?php } } ?>