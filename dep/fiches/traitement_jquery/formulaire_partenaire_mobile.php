<div class="row">
<div class="col-sm-12 col-md-12 mb-12">

	<?php
if($_GET){
  extract($_GET);
require_once '../api/Fonctions.php';
if(isset($feuille) AND !empty($feuille))
{
	  
  		
		echo '<input type="hidden" name="feuille" value="'.$feuille.'"><select required name="Partenaire[]" id="" multiple class="form-control selectpicker bts_select " data-live-search="true"5>';
  		foreach (FC_Rechercher_Code('SELECT code_acteur, nom_acteur FROM acteur') as $row4)
  		{
  			echo '<option value="'.$row4["code_acteur"].'" ';

  			foreach (FC_Rechercher_Code("SELECT * FROM t_feuille_partenaire WHERE (Code_Feuille='".$feuille."' AND code='".$row4["code_acteur"]."')") as $row5)
  			{echo ' selected ';}

  			echo '>'.$row4["nom_acteur"].'</option>';

  		}
 echo "</select>";
}
}
?>
</div></div>