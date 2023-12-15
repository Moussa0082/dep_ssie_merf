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



if(isset($_GET["id"]) && !empty($_GET["id"]))

{

  $id=($_GET["id"]);

  $query_liste_cat_dep = "SELECT * FROM ".$database_connect_prefix."categorie_depense WHERE id_categorie='$id' ";

  try{

        $liste_cat_dep = $pdar_connexion->prepare($query_liste_cat_dep);

        $liste_cat_dep->execute();

        $row_liste_cat_dep = $liste_cat_dep ->fetch();

        $totalRows_liste_cat_dep = $liste_cat_dep->rowCount();

  }catch(Exception $e){ die(mysql_error_show_message($e)); }

}

//Bailleurs

$query_liste_convention = "SELECT * FROM ".$database_connect_prefix."type_part ORDER BY intitule desc";

try{

    $liste_convention = $pdar_connexion->prepare($query_liste_convention);

    $liste_convention->execute();

    $row_liste_convention = $liste_convention ->fetchAll();

    $totalRows_liste_convention = $liste_convention->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }

?>

<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>

<script>

	$().ready(function() {

		// validate the comment form when it is submitted

		$("#form2").validate();

	});

</script>

<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification Cat&eacute;gorie de d&eacute;penses":"Nouvelle Cat&eacute;gorie de d&eacute;penses"?></h4> </div>

<div class="widget-content">

<form action="" class="form-horizontal row-border" method="post" enctype="multipart/form-data" name="form2" id="form2" novalidate="novalidate">

<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

    <tr valign="top">

      <td>

        <div class="form-group">

          <label for="code" class="col-md-3 control-label">Code <span class="required">*</span></label>

          <div class="col-md-9">

            <input class="form-control required" type="text" name="code" id="code" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_cat_dep['code']; ?>" size="32" />

          </div>

        </div>

      </td>

    </tr>

    <tr valign="top">

      <td>

        <div class="form-group">

          <label for="nom_categorie" class="col-md-3 control-label">Nom de la Cat&eacute;gorie <span class="required">*</span></label>

          <div class="col-md-9">

            <textarea class="form-control required" name="nom_categorie" id="nom_categorie" cols="25" rows="5"><?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo $row_liste_cat_dep['nom_categorie']; ?></textarea>

          </div>

        </div>

      </td>

    </tr>

    <tr valign="top">

      <td>

        <div class="form-group">

          <label for="convention" class="col-md-3 control-label">Convention <span class="required">*</span></label>

          <div class="col-md-9">

            <select name="convention" id="convention" class="form-control required" >

              <option value="">Selectionnez</option>

              <?php if($totalRows_liste_convention>0) { foreach($row_liste_convention as $row_liste_convention){ ?>

              <option value="<?php echo $row_liste_convention['code_type']; ?>" <?php if (isset($row_liste_cat_dep['convention_concerne']) && $row_liste_convention['code_type']==$row_liste_cat_dep['convention_concerne']) {echo "SELECTED";} ?>><?php echo $row_liste_convention['code_type'].": ".$row_liste_convention['intitule']; ?></option>

              <?php } } ?>

            </select>

          </div>

        </div>

      </td>

    </tr>

</table>

<div class="form-actions">

  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />

  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">

<?php if(isset($_GET["id"]) && !empty($_GET["id"])) { ?>

<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">

<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette cat&eacute;gorie de d&eacute;penses ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />

<?php } ?>

<input name="MM_form" id="MM_form" type="hidden" value="form2" size="32" alt="">

  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->

</div>

</form>



</div> </div>