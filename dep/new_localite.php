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



if(isset($_GET['niveau']) && intval($_GET['niveau'])>=0) { $niveau=intval($_GET['niveau']); } else {$niveau=0;$_GET['niveau']=0;}

$where = " niveau = ".($niveau)." ";

$loc = array("region","departement","commune","village");

//$libelle = array("R&eacute;gions","Pr&eacute;ectures","Sous/Pr&eacute;ectures","Villages/Localit&eacute;s");

//$libelle = array("R&eacute;gions","D&eacute;partements","Communes","Villages/Localit&eacute;s");
$libelle = array("R&eacute;gions","Pr&eacute;fectures","Communes","Cantons");




if(isset($_GET["id"]) && !empty($_GET["id"]))

{

  $id=($_GET["id"]);

  $query_liste_activite = "SELECT * FROM ".$database_connect_prefix.$loc[$niveau]." WHERE code_".$loc[$niveau]."=".GetSQLValueString($id, "text");

  try{

    $liste_activite = $pdar_connexion->prepare($query_liste_activite);

    $liste_activite->execute();

    $row_liste_activite = $liste_activite ->fetch();

    $totalRows_liste_activite = $liste_activite->rowCount();

  }catch(Exception $e){ die(mysql_error_show_message($e)); }

}



if($niveau>0)

{

  $query_liste_volet = "SELECT * FROM ".$database_connect_prefix.$loc[$niveau-1]." ORDER BY nom_".$loc[$niveau-1]." ASC";

try{

    $liste_volet = $pdar_connexion->prepare($query_liste_volet);

    $liste_volet->execute();

    $row_liste_volet = $liste_volet ->fetchAll();

    $totalRows_liste_volet = $liste_volet->rowCount();

}catch(Exception $e){ die(mysql_error_show_message($e)); }

}



?>

<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>

<script type="text/javascript" src="plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>

<script>

	$().ready(function() {

		// validate the comment form when it is submitted

		$(".row-border").validate();

        $(".colorpicker").remove();

        $(".bs-colorpicker").colorpicker();

        $(".colorpicker").attr("style","z-index:10060");

	});

</script>

<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>

<div class="widget box">

<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo (isset($_GET["id"]) && !empty($_GET["id"]))?"Modification ":"Nouvel ajout "; ?></h4> </div>

<div class="widget-content">

<form action="" class="row-border" method="post" enctype="multipart/form-data" name="form<?php echo $niveau+1; ?>" id="form<?php echo $niveau+1; ?>" novalidate="novalidate">

<table border="0" id="mtable" align="center" cellspacing="1" cellpadding="0" width="100%" style="font-size:14px;">

    <tr valign="top">

      <td>

        <div class="form-group" id="code_zone">

          <label for="code" class="col-md-3 control-label">Code <span class="required">*</span></label>

          <div class="col-md-9">

            <input onblur="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".$row_liste_activite['code_'.$loc[$niveau]]."'"; if(($niveau)>0) echo " && $('#parent').val()!='".((isset($row_liste_activite[$loc[$niveau-1]]))?$row_liste_activite[$loc[$niveau-1]]:"'+this.value+'")."'"; ?>) check_code('verif_code.php?t=<?php echo $loc[$niveau]; ?>&','w=<?php echo 'code_'.$loc[$niveau]; ?>='+this.value+'<?php if(($niveau)>0) echo " and ".$loc[$niveau-1]."=".((isset($row_liste_activite[$loc[$niveau-1]]))?$row_liste_activite[$loc[$niveau-1]]:"'+this.value+'"); ?> ','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" class="form-control required" type="text" name="code" id="code" value="<?php echo isset($row_liste_activite['code_'.$loc[$niveau]])?$row_liste_activite['code_'.$loc[$niveau]]:""; ?>" size="32" />

<input type="hidden" name="old_code" value="<?php echo isset($row_liste_activite['code_'.$loc[$niveau]])?$row_liste_activite['code_'.$loc[$niveau]]:""; ?>" />

            <span class="help-block h0" id="code_zone_text">&nbsp;</span>

          </div>

        </div>

      </td>

    </tr>

<?php if(($niveau)>0){ ?>

    <tr valign="top">

      <td>

        <div class="form-group">

          <label for="parent" class="col-md-3 control-label"><?php echo (isset($libelle[$niveau-1]) && !empty($libelle[$niveau-1]))?$libelle[$niveau-1]:"Parent"; ?> <span class="required">*</span></label>

          <div class="col-md-9"><!-- onchange="if(this.value!='' <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "&& this.value!='".((isset($row_liste_activite[$loc[$niveau-1]]))?$row_liste_activite[$loc[$niveau-1]]:"")."'"; ?>) check_code('verif_code.php?t=<?php echo $loc[$niveau]; ?>&','w=<?php echo 'code_'.$loc[$niveau]; ?>='+$('#code').val()+'<?php echo " and ".$loc[$niveau-1]."=".((isset($row_liste_activite[$loc[$niveau-1]]))?$row_liste_activite[$loc[$niveau-1]]:"'+this.value+'"); ?> ','code_zone'); <?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "else $('#code_zone_text').html('&nbsp;');"; ?>" class="form-control required" type="text" name="code" id="code" value="<?php echo isset($row_liste_activite['code_'.$loc[$niveau]])?$row_liste_activite['code_'.$loc[$niveau]]:""; ?>" -->

            <select name="parent" id="parent" class="form-control required"  >

              <option value="">Selectionnez</option>

              <?php if($totalRows_liste_volet>0) { foreach($row_liste_volet as $row_liste_volet) { ?>

              <option value="<?php echo $row_liste_volet['code_'.$loc[$niveau-1]]; ?>" <?php if (isset($row_liste_activite[$loc[$niveau-1]]) && $row_liste_volet['code_'.$loc[$niveau-1]]==$row_liste_activite[$loc[$niveau-1]]) {echo "SELECTED";} ?>><?php echo $row_liste_volet['code_'.$loc[$niveau-1]].": ".$row_liste_volet['nom_'.$loc[$niveau-1]]; ?></option>

              <?php } } ?>

            </select>

          </div>

        </div>

      </td>

    </tr>

<?php } ?>

    <tr valign="top">

      <td>

        <div class="form-group">

          <label for="intitule" class="col-md-3 control-label"><?php echo "Nom"; ?> <span class="required">*</span></label>

          <div class="col-md-9">

            <textarea class="form-control required" cols="200" rows="3" type="text" name="intitule" id="intitule"><?php echo isset($row_liste_activite['nom_'.$loc[$niveau]])?$row_liste_activite['nom_'.$loc[$niveau]]:""; ?></textarea>

          </div>

        </div>

      </td>

    </tr>

<?php if(($niveau)==0){ ?>

    <tr valign="top">

      <td>

        <div class="form-group">

          <label for="abrege" class="col-md-3 control-label">Abréviation <?php if($niveau==0){ ?> <span class="required">*</span><?php } ?></label>

          <div class="col-md-9">

            <input class="form-control <?php if($niveau==0) echo "required"; ?>" type="text" name="abrege" id="abrege" value="<?php if(isset($row_liste_activite['abrege_'.$loc[$niveau]])) echo $row_liste_activite['abrege_'.$loc[$niveau]]; ?>" size="32" />

          </div>

        </div>

      </td>

    </tr>

    <tr valign="top">

      <td>

        <div class="form-group">

          <label for="couleur" class="col-md-3 control-label">Couleur <span class="required">*</span></label>

          <div class="col-md-9">

            <input data-colorpicker-guid="1" data-color-format="hex" class="form-control bs-colorpicker required" type="text" name="couleur" id="couleur" value="<?php echo isset($row_liste_activite['couleur'])?$row_liste_activite['couleur']:""; ?>" size="32" />

          </div>

        </div>

      </td>

    </tr>

<?php } ?>

<?php if($niveau==3){ ?>

    <tr valign="top">

      <td>

        <div class="form-group">

          <label class="col-md-3 control-label">Coordonn&eacute;es g&eacute;ographiques </label>

          <div class="col-md-4">

            <label for="longitude" class="col-md-2 control-label">Longitude </label>

            <input class="form-control <?php if($niveau==0) echo "required"; ?>" type="text" name="longitude" id="longitude" value="<?php if(isset($row_liste_activite['longitude'])) echo $row_liste_activite['longitude']; ?>" size="32" />

          </div>

          <div class="col-md-4">

            <label for="latitude" class="col-md-2 control-label">Latitude </label>

            <input class="form-control <?php if($niveau==0) echo "required"; ?>" type="text" name="latitude" id="latitude" value="<?php if(isset($row_liste_activite['latitude'])) echo $row_liste_activite['latitude']; ?>" size="32" />

          </div>

        </div>

      </td>

    </tr>

    <tr valign="top">

      <td>

        <div class="form-group">

          <label class="col-md-3 control-label">Nombre de Population </label>

          <div class="col-md-2">

            <label for="homme" class="col-md-2 control-label">Homme </label>

            <input class="form-control <?php if($niveau==0) echo "required"; ?>" type="text" name="homme" id="homme" value="<?php if(isset($row_liste_activite['homme'])) echo $row_liste_activite['homme']; ?>" size="32" />

          </div>

          <div class="col-md-2">

            <label for="femme" class="col-md-2 control-label">Femme </label>

            <input class="form-control <?php if($niveau==0) echo "required"; ?>" type="text" name="femme" id="femme" value="<?php if(isset($row_liste_activite['femme'])) echo $row_liste_activite['femme']; ?>" size="32" />

          </div>

          <div class="col-md-2">

            <label for="jeune" class="col-md-2 control-label">Jeune </label>

            <input class="form-control" type="text" name="jeune" id="jeune" value="<?php if(isset($row_liste_activite['jeune'])) echo $row_liste_activite['jeune']; ?>" size="32" />

          </div>

          <div class="col-md-2">

            <label for="menage" class="col-md-2 control-label">M&eacute;nage </label>

            <input class="form-control" type="text" name="menage" id="menage" value="<?php if(isset($row_liste_activite['nb_menage'])) echo $row_liste_activite['nb_menage']; ?>" size="32" />

          </div>

        </div>

      </td>

    </tr>

<?php } ?>

</table>

<div class="form-actions">

<?php if(isset($_GET["id"])){ ?>

  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>" />

  <?php } ?>

  <input type="hidden" name="niveau" value="<?php echo $niveau; ?>" />

  <input name="submit" type="submit" class="btn btn-success pull-right" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "Modifier"; else echo "Enregistrer" ; ?>" />

  <input name="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo "MM_update"; else echo "MM_insert" ; ?>" type="hidden" value="<?php if(isset($_GET["id"]) && !empty($_GET["id"])) echo ($_GET["id"]); else echo "MM_insert" ; ?>" size="32" alt="">

<?php if(isset($_GET["id"]) && !empty($_GET["id"]) && isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']<2)) { ?>

<input name="MM_delete" id="MM_delete" type="hidden" value="" size="32" alt="">

<input name="del" type="submit" onclick="return delete_data('MM_delete','Supprimer cette localit&eacute; ?','<?php echo ($_GET["id"]); ?>');" class="btn btn-danger pull-left" value="Supprimer" />

<?php } ?>

<input name="MM_form" id="MM_form" type="hidden" value="form<?php echo $niveau+1; ?>" size="32" alt="">

  <!--<input name="Submit2" type="reset" class="btn btn-success pull-right" value="Initialiser" />-->

</div>

</form>



</div> </div>

<?php } ?>