<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
if (!isset ($_SESSION["clp_id"])) {
    header(sprintf("Location: %s", "./login.php"));  exit();
}
include_once 'api/configuration.php';
$config = new Config;

require_once 'api/Fonctions.php';
require_once 'theme_components/theme_style.php';

 $Code_Rapport="";
      if(isset($_GET['r']) AND !empty($_GET['r']))
      {$Code_Rapport=base64_decode($_GET['r']);
        $Nom_Rapport="";
        $Code_Classeur="";
        $Code_Classeur2="";
        $ii=0;
        foreach (FC_Rechercher_Code('SELECT * FROM t_rapport WHERE Code_Rapport=\''.$Code_Rapport.'\'') as $row4)
        {$ii++; $uuu=0;
          $Nom_Rapport=$row4['Nom_Rapport'];

          foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE Code_Feuille=\''.$row4["Code_Feuille"].'\'') as $row5)
        {$Code_Classeur=$row5["Code_Classeur"];}  

          foreach (FC_Rechercher_Code('SELECT * FROM t_feuille WHERE Code_Feuille=\''.$row4["Feuille_Jointure"].'\'') as $row12)
        {$Code_Classeur2=$row12["Code_Classeur"];}
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page title -->
    <title><?php print $config->sitename;?></title>
    <link rel="shortcut icon" type="image/ico" href="<?php print $config->icon_folder;?>/favicon.ico" />
    <meta name="keywords" content="<?php print $config->MetaKeys;?>" />
    <meta name="description" content="<?php print $config->MetaDesc;?>" />
    <meta name="author" content="<?php print $config->MetaAuthor;?>" />

    <!-- Vendor styles -->
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.css" />
    <link rel="stylesheet" href="vendor/metisMenu/dist/metisMenu.css" />
    <link rel="stylesheet" href="vendor/animate.css/animate.css" />
    <link rel="stylesheet" href="vendor/bootstrap/dist/css/bootstrap.css" />

    <!-- App styles -->
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
    <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/helper.css" />
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/style_fst.css">

    <!-- Vendor scripts -->
    <script src="vendor/jquery/dist/jquery.min.js"></script>
    <script src="vendor/jquery-ui/jquery-ui.min.js"></script>
    <script src="vendor/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="vendor/jquery-flot/jquery.flot.js"></script>
    <script src="vendor/jquery-flot/jquery.flot.resize.js"></script>
    <script src="vendor/jquery-flot/jquery.flot.pie.js"></script>
    <script src="vendor/flot.curvedlines/curvedLines.js"></script>
    <script src="vendor/jquery.flot.spline/index.js"></script>
    <script src="vendor/metisMenu/dist/metisMenu.min.js"></script>
    <script src="vendor/iCheck/icheck.min.js"></script>
    <script src="vendor/peity/jquery.peity.min.js"></script>
    <script src="vendor/sparkline/index.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="vendor/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.fr.min.js"></script>

    <!-- DataTables -->
    <script src="vendor/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- DataTables buttons scripts -->
    <script src="vendor/pdfmake/build/pdfmake.min.js"></script>
    <script src="vendor/pdfmake/build/vfs_fonts.js"></script>
    <script src="vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>

    <!-- App scripts -->
    <script src="scripts/homer.js"></script>

</head>
<body class="fixed-navbar fixed fixed-footer sidebar-scroll">
    <?php require_once "./theme_components/header.php"; ?>
    <?php //require_once "./theme_components/main-menu.php"; ?>
<!-- Main Wrapper -->
<div id="">
<?php require_once "./theme_components/sub-header.php"; ?>
    <div class="content animate-panel">
        <div class="row">
<script>
$("#search").hide();
$("#mbreadcrumb").html(<?php $link = ""; if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) $link .= '<div class="btn-circle-zone">'.do_link("btn_rapport","#myModal","Enregistrer ce rapport","<span title='Enregistrer ce rapport' class='glyphicon glyphicon-ok'></span>","simple","./","btn btn-success btn-circle mgr-5","",1,"",$nfile);
$link .= '</div>';
echo GetSQLValueString($link, "text"); } ?>);
</script>

<div style="border:1px solid silver;background: #FFF;padding: 10px;">
<form id="form_rapport_dynamique" action="traitement_jquery\inserer_rapport_simple.php" method="POST">
  <input type="hidden" name="Code_Rapport" id="Code_Rapport" <?php echo 'value="'.base64_encode($row4["Code_Rapport"]).'"'; ?>>


   <div class="row" style="">
      <div class="col-md-8 col-lg-8">
        <label style="float: left;">Nom du rapport</label>
        <input type="text" name="nom_rapport" id="nom_rapport" class="form-control" placeholder="Nom du rapport" <?php echo 'value="'.$row4["Nom_Rapport"].'"'; ?>>
      </div> 
      <div class="col-md-4 col-lg-4">
         <button style="width: 150px" type="button" id="Boutton_Ajouter_Critere" <?php echo 'class="btn '.$Boutton_Style.'"'; ?>>Ajouter un critère</button><br><br>
         <label style="text-align: left;">Element en Colonne</label>
          <select name="colonne_x" id="colonne_x" class="form-control">
      <optgroup label="Feuille 1">
<option value=""></option>    
      <?php 
        
        $Exp_COLONNE_Y = explode(".", $row4["Colonne_Y"]);
        $Exp_COLONNE_X = explode(".", $row4["Colonne_X"]);

        $Liste_1_Col_Val = array();
        $Liste_1_Col_Lab = array();

        $Liste_2_Col_Val = array();
        $Liste_2_Col_Lab = array();
$ind=0;
    foreach (FC_Rechercher_Code('SELECT t_feuille_ligne.*, Table_Feuille FROM `t_feuille_ligne` INNER JOIN `t_feuille` ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE t_feuille_ligne.Code_Feuille='.$row4["Code_Feuille"]) as $row7){
      $Liste_1_Col_Val[] = str_replace("t_", "v_", $row7["Table_Feuille"]).'.'.$row7["Nom_Collone"];
      $Liste_1_Col_Lab[] = $row7["Nom_Ligne"];}

      if(!empty($row4["Feuille_Jointure"])){foreach (FC_Rechercher_Code('SELECT t_feuille_ligne.*, Table_Feuille FROM `t_feuille_ligne` INNER JOIN `t_feuille` ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE t_feuille_ligne.Code_Feuille='.$row4["Feuille_Jointure"]) as $row77){$Liste_2_Col_Val[] = str_replace("t_", "v_", $row77["Table_Feuille"]).'.'.$row77["Nom_Collone"]; 
                $Liste_2_Col_Lab[] = $row77["Nom_Ligne"];}}

    for($i=0; $i<count($Liste_1_Col_Val); $i++){
      echo '<option value="'.$Liste_1_Col_Val[$i].'" class="option_champ_'.str_replace(" ", "", $Liste_1_Col_Lab[$i]).'" id=""';
       if($row4["Colonne_X"] == $Liste_1_Col_Val[$i]){echo " selected ";}
      echo '>'.$Liste_1_Col_Lab[$i].'</option>';}
    echo '</optgroup>';

if(!empty($row4["Feuille_Jointure"])){
      echo '<optgroup label="Feuille 2">';
      for($i=0; $i<count($Liste_2_Col_Val); $i++){
      echo '<option value="'.$Liste_2_Col_Val[$i].'" class="option_champ_'.str_replace(" ", "", $Liste_2_Col_Lab[$i]).'" id=""';
       if($row4["Colonne_X"] == $Liste_2_Col_Val[$i]){echo " selected ";}
      echo '>'.$Liste_2_Col_Lab[$i].'</option>';}
    echo '</optgroup>';
    }
      
     
   
 ?>
 
    </select>
      </div>
   </div><br>
   <div class="row" style="">
      <div class="col-md-8 col-lg-8">
         <div class="row">
            <div class="col-md-6 col-lg-6">
                <label style="float: left;">Classeur 1</label>
        <select class="form-control" name="select_classeur" id="select_classeur">
      <option value=""></option>
      <?php foreach (FC_Rechercher_Code("SELECT * FROM t_classeur WHERE Id_Projet='".$_SESSION['clp_projet']."'") as $row3) 
      {if($Code_Classeur==$row3['Code_Classeur']){echo '<option value="'.$row3["Code_Classeur"].'" selected>'.$row3["Libelle_Classeur"].'</option>';}
      else {echo '<option value="'.$row3["Code_Classeur"].'">'.$row3["Libelle_Classeur"].'</option>';} 
      } ?>
    </select>
            </div> 
            <div class="col-md-6 col-lg-6">
                <label style="float: left;">Classeur 2</label>
      <select class="form-control" name="select_classeur2" id="select_classeur2">
      <option value=""></option>
            <?php foreach (FC_Rechercher_Code("SELECT * FROM t_classeur WHERE Id_Projet='".$_SESSION['clp_projet']."'") as $row3) 
      {if($Code_Classeur2==$row3['Code_Classeur']){echo '<option value="'.$row3["Code_Classeur"].'" selected>'.$row3["Libelle_Classeur"].'</option>';}
      else {echo '<option value="'.$row3["Code_Classeur"].'">'.$row3["Libelle_Classeur"].'</option>';} 
      } ?>
    </select>
            </div>
            <div class="col-md-6 col-lg-6">
                <label style="float: left;">Feuille 1</label>
             <select class="form-control" name="select_feuille" id="select_feuille">
      <option value=""></option>
      <?php foreach (FC_Rechercher_Code("SELECT * FROM t_feuille WHERE (Code_Classeur=$Code_Classeur)") as $row6) 
      {if($row4['Code_Feuille']==$row6['Code_Feuille']){echo '<option value="'.$row6["Code_Feuille"].'" selected>'.$row6["Nom_Feuille"].'</option>';}
      else {echo '<option value="'.$row6["Code_Feuille"].'">'.$row6["Nom_Feuille"].'</option>';} 
      } ?>
    </select>
            </div> 
            <div class="col-md-6 col-lg-6">
                <label style="float: left;">Feuille 2</label>
         <select id="feuille_jointure" name="feuille_jointure" class="form-control">
                      <option value=""></option>
                      <?php 
        foreach (FC_Rechercher_Code("SELECT * FROM t_feuille WHERE Code_Classeur=".$Code_Classeur2) as $row8)
        {
          if($row8["Code_Feuille"]==$row4["Feuille_Jointure"]){echo '<option value="'.$row8["Code_Feuille"].'" selected>'.$row8["Nom_Feuille"].'</option>';}
          else{echo '<option value="'.$row8["Code_Feuille"].'">'.$row8["Nom_Feuille"].'</option>';}
        }
       ?>

    </select> 
            </div> 
            <div class="col-md-6 col-lg-6">
                <label style="float: left;">Colonne à lier</label>
             <select name="attribut_jointure_fp" id="attribut_jointure_fp"  class="form-control" required>

                    <option value=""></option>
  <?php 
        $ind=0;
      foreach (FC_Rechercher_Code('SELECT t_feuille_ligne.*, Table_Feuille FROM `t_feuille_ligne` INNER JOIN `t_feuille` ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE t_feuille_ligne.Code_Feuille='.$row4["Code_Feuille"]) as $row7){
     if($row4["Attribut_Jointure_FP"]== (str_replace("t_", "v_", $row7["Table_Feuille"]).'.'.$row7["Nom_Collone"]))
     {echo '<option value="'.str_replace("t_", "v_", $row7["Table_Feuille"]).'.'.$row7["Nom_Collone"].'" class="option_champ_'.str_replace(" ", "", $row7["Nom_Ligne"]).'" id="" selected>'.$row7["Nom_Ligne"].'</option>';}
   else {echo '<option value="'.str_replace("t_", "v_", $row7["Table_Feuille"]).'.'.$row7["Nom_Collone"].'" class="option_champ_'.str_replace(" ", "", $row7["Nom_Ligne"]).'" id="">'.$row7["Nom_Ligne"].'</option>';}
      $ind++;
   }
 ?>
    </select>
            </div> 
            <div class="col-md-6 col-lg-6">
                <label style="float: left;">Colonne à lier</label>
<select name="attribut_jointure_fs" id="attribut_jointure_fs"  class="form-control" required>

                         <option value=""></option>
  <?php 
        $ind=0;
      foreach (FC_Rechercher_Code('SELECT t_feuille_ligne.*, Table_Feuille FROM `t_feuille_ligne` INNER JOIN `t_feuille` ON (t_feuille_ligne.Code_Feuille = t_feuille.Code_Feuille) WHERE t_feuille_ligne.Code_Feuille='.$row4["Feuille_Jointure"]) as $row7){
     if($row4["Attribut_Jointure_FS"]== (str_replace("t_", "v_", $row7["Table_Feuille"]).'.'.$row7["Nom_Collone"]))
     {echo '<option value="'.str_replace("t_", "v_", $row7["Table_Feuille"]).'.'.$row7["Nom_Collone"].'" class="option_champ_'.str_replace(" ", "", $row7["Nom_Ligne"]).'" id="" selected>'.$row7["Nom_Ligne"].'</option>';}
   else {echo '<option value="'.str_replace("t_", "v_", $row7["Table_Feuille"]).'.'.$row7["Nom_Collone"].'" class="option_champ_'.str_replace(" ", "", $row7["Nom_Ligne"]).'" id="">'.$row7["Nom_Ligne"].'</option>';}
      $ind++;
   }
 ?>
    </select>
            </div>  
         </div> 
      </div>
      <div class="col-md-4 col-lg-4">
        <label style="float: left;">Element en Ligne</label>
  <select name="colonne_y" id="colonne_y" class="form-control">
      <optgroup label="Feuille 1"><option value=""></option>    
      <?php 
        

    for($i=0; $i<count($Liste_1_Col_Val); $i++){
      echo '<option value="'.$Liste_1_Col_Val[$i].'" class="option_champ_'.str_replace(" ", "", $Liste_1_Col_Lab[$i]).'" id=""';
       if($row4["Colonne_Y"] == $Liste_1_Col_Val[$i]){echo " selected ";}
      echo '>'.$Liste_1_Col_Lab[$i].'</option>';}
    echo '</optgroup>';

if(!empty($row4["Feuille_Jointure"])){
      echo '<optgroup label="Feuille 2">';
      for($i=0; $i<count($Liste_2_Col_Val); $i++){
      echo '<option value="'.$Liste_2_Col_Val[$i].'" class="option_champ_'.str_replace(" ", "", $Liste_2_Col_Lab[$i]).'" id=""';
       if($row4["Colonne_Y"] == $Liste_2_Col_Val[$i]){echo " selected ";}
      echo '>'.$Liste_2_Col_Lab[$i].'</option>';}
    echo '</optgroup>';
    }


 ?>
    </select>
         <label style="float: left;"> Colonne de valeur</label>
    <select name="input_valeur" id="input_valeur" class="form-control"><optgroup label="Feuille 1">
                      <?php 
        
    for($i=0; $i<count($Liste_1_Col_Val); $i++){
      echo '<option value="'.$Liste_1_Col_Val[$i].'" class="option_champ_'.str_replace(" ", "", $Liste_1_Col_Lab[$i]).'" id=""';
       if($row4["Valeur"] == $Liste_1_Col_Val[$i]){echo " selected ";}
      echo '>'.$Liste_1_Col_Lab[$i].'</option>';}
    echo '</optgroup>';

if(!empty($row4["Feuille_Jointure"])){
      echo '<optgroup label="Feuille 2">';
      for($i=0; $i<count($Liste_2_Col_Val); $i++){
      echo '<option value="'.$Liste_2_Col_Val[$i].'" class="option_champ_'.str_replace(" ", "", $Liste_2_Col_Lab[$i]).'" id=""';
       if($row4["Valeur"] == $Liste_2_Col_Val[$i]){echo " selected ";}
      echo '>'.$Liste_2_Col_Lab[$i].'</option>';}
    echo '</optgroup>';
    }

 ?>
    </select>
         <label style="float: left;">Opération</label>
      <select id="operation" name="operation" class="form-control">
      <option value=""></option>
     <?php 
        $OPERATION_TAB_V[]="COUNT";$OPERATION_TAB_V[]="SUM";$OPERATION_TAB_V[]="AVG";
        $OPERATION_TAB_L[]="COMPTER";$OPERATION_TAB_L[]="SOMME";$OPERATION_TAB_L[]="MOYENNE";

        for($i=0; $i<count($OPERATION_TAB_V); $i++) {
          if($OPERATION_TAB_V[$i]==$row4["Operation"]){echo '<option value="'.$OPERATION_TAB_V[$i].'" selected>'.$OPERATION_TAB_L[$i].'</option>';}
          else{echo '<option value="'.$OPERATION_TAB_V[$i].'">'.$OPERATION_TAB_L[$i].'</option>';}
        }
       ?>
    </select>
      </div>
   </div>

<br>
<div id="div_criteres" style="background-color:beige; margin:5px; border-radius: 5px; border:1px solid red">
<div class="row"><div class="col-lg-11" align="center"><h2>Critères</h2></div></div>
<?php
$ind=1;
$Nombre_Critere=0;
foreach (FC_Rechercher_Code("SELECT * FROM t_rapport_critere WHERE Code_Rapport=".$row4["Code_Rapport"]) as $row9)
{$Nombre_Critere++;
  echo '<div id="criteres_'.$ind.'">'.'<div class="row"><div class="col-lg-2" align="center"><label></label></div><div class="col-lg-2" align="center"><label>ET / OU</label></div><div class="col-lg-2" align="center"><label>Champ</label></div><div class="col-lg-2" align="center"><label>Condition</label></div><div class="col-lg-2" align="center"><label>Valeur</label></div><div class="col-lg-2" align="center"><label></label></div></div><div class="row"><div class="col-lg-2" align="center"></div><div class="col-lg-2" align="center"><select  id="et_ou_criteres[]" name="et_ou_criteres[]" class="form-control">';
  if($row9["Critere_ET_OU"]=="ET"){echo '<option selected>ET</option><option>OU</option>';}
  else {echo '<option>ET</option><option selected>OU</option>';}

  echo '</select></div><div class="col-lg-2" align="center"><select  id="champ_criteres[]" name="champ_criteres[]" class="form-control">';

foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne INNER JOIN t_feuille ON(t_feuille.Code_Feuille=t_feuille_ligne.Code_Feuille) WHERE t_feuille_ligne.Code_Feuille='.$row4["Code_Feuille"]) as $row10)
{

if((str_replace("t", "v", $row10["Table_Feuille"]).'.'.$row10["Nom_Collone"])==$row9["Critere_Colonne"]){echo '<option value="'.str_replace("t", "v", $row10["Table_Feuille"]).'.'.$row10["Nom_Collone"].'" id="" selected>'.$row10["Nom_Feuille"].'.'.$row10["Nom_Ligne"].'</option>';}
else {echo '<option value="'.str_replace("t", "v", $row10["Table_Feuille"]).'.'.$row10["Nom_Collone"].'" id="">'.$row10["Nom_Feuille"].'.'.$row10["Nom_Ligne"].'</option>';}
}

  foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne INNER JOIN t_feuille ON(t_feuille.Code_Feuille=t_feuille_ligne.Code_Feuille) WHERE t_feuille_ligne.Code_Feuille='.$row4["Feuille_Jointure"]) as $row11)
{
  if((str_replace("t", "v", $row11["Table_Feuille"]).'.'.$row11["Nom_Collone"])==$row9["Critere_Colonne"])
    {echo '<option value="'.str_replace("t", "v", $row11["Table_Feuille"]).'.'.$row11["Nom_Collone"].'" id="" selected>'.$row11["Nom_Feuille"].'.'.$row11["Nom_Ligne"].'</option>';}
  else {echo '<option value="'.str_replace("t", "v", $row11["Table_Feuille"]).'.'.$row11["Nom_Collone"].'" id="">'.$row11["Nom_Feuille"].'.'.$row11["Nom_Ligne"].'</option>';}

}

  echo '</select></div><div class="col-lg-2" align="center"><select  id="condition_criteres[]" name="condition_criteres[]" class="form-control">';

$CRIT_V[]="=";$CRIT_V[]=">";$CRIT_V[]="<";$CRIT_V[]=">=";$CRIT_V[]="<=";$CRIT_V[]="<>";$CRIT_V[]="%x%";$CRIT_V[]="x%";$CRIT_V[]="%x";
$CRIT_L[]="Egal (=)";$CRIT_L[]="Supérieur (&gt;)";$CRIT_L[]="Inférieur (&lt;)";$CRIT_L[]="Supérieur ou égal (&gt;=)";$CRIT_L[]="Inférieur ou égal (&lt;=)";$CRIT_L[]="Différent (!= / &lt;&gt;)";$CRIT_L[]="Contenant (%x%)";$CRIT_L[]="Commençant par (x%)";$CRIT_L[]="Terminant par (%x)";

 for($i=0; $i<count($CRIT_V); $i++) {
          if($CRIT_V[$i]==$row9["Critere_Condition"]){echo '<option value="'.$CRIT_V[$i].'" selected>'.$CRIT_L[$i].'</option>';}
          else{echo '<option value="'.$CRIT_V[$i].'">'.$CRIT_L[$i].'</option>';}
        }


  echo '</select></div><div class="col-lg-2" align="center"><input type="text" name="valeur_criteres[]" id="valeur_criteres[]" placeholder="Valeur" value="'.$row9["Critere_Valeur"].'" class="form-control"></div><div class="col-lg-2" align="center"><a onclick="document.getElementById(\'criteres_'.$ind.'\').innerHTML=\'\'"><span class="glyphicon glyphicon-remove text-danger"></span></a></div></div></div>';
  $ind++;
}

if($Nombre_Critere<=0)
{$Code_Champs="";
  foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne INNER JOIN t_feuille ON(t_feuille.Code_Feuille=t_feuille_ligne.Code_Feuille) WHERE t_feuille_ligne.Code_Feuille='.$row4["Code_Feuille"]) as $row10)
{

$Code_Champs.='<option value="'.str_replace("t", "v", $row10["Table_Feuille"]).'.'.$row10["Nom_Collone"].'" id="">'.$row10["Nom_Feuille"].'.'.$row10["Nom_Ligne"].'</option>';
}

if(!empty($row4["Feuille_Jointure"])){foreach (FC_Rechercher_Code('SELECT * FROM t_feuille_ligne INNER JOIN t_feuille ON(t_feuille.Code_Feuille=t_feuille_ligne.Code_Feuille) WHERE t_feuille_ligne.Code_Feuille='.$row4["Feuille_Jointure"]) as $row11)
{$Code_Champs.='<option value="'.str_replace("t", "v", $row11["Table_Feuille"]).'.'.$row11["Nom_Collone"].'" id="">'.$row11["Nom_Feuille"].'.'.$row11["Nom_Ligne"].'</option>';}}

echo '<script type="text/javascript">
var CHAMPS_FP_FS=champ_criteres=\''.addslashes($Code_Champs).'\';
</script>';

}
else
{ ?> 
<script type="text/javascript">
var champ_criteres=document.getElementById('champ_criteres[]');
var CHAMPS_FP_FS=champ_criteres.innerHTML;
</script>

<?php
}
 ?>
 </div>
<br><br>
<section id="Champs">



</section>


</form>
</div>  

</center>



<script type="text/javascript">
var COLS_FP="";
var COLS_FS="";

document.getElementById("select_classeur").addEventListener("change", function(e){

document.getElementById("operation").value="";

VIDER_DIV_CRITERE();



$("#colonne_x").html("");
$("#colonne_y").html("");
$("#input_valeur").html("");
  document.getElementById("select_classeur2").value="";
  document.getElementById("feuille_jointure").disabled=true; document.getElementById("feuille_jointure").value="";
  document.getElementById("attribut_jointure_fp").disabled=true; document.getElementById("attribut_jointure_fp").value="";
  document.getElementById("attribut_jointure_fs").disabled=true; document.getElementById("attribut_jointure_fs").value="";

  if(document.getElementById("select_classeur").value!="")
    {$.ajax({url:"traitement_jquery/liste_feuille_par_classeur.php", method:"POST", data:$('#form_rapport_dynamique').serialize(), success:function (data) {
    if(data!='')
    {$("#select_feuille").html(data);}
else 
{}}});
 }
  else {$("#select_feuille").html(""); }

});

document.getElementById("select_classeur2").addEventListener("change", function(e){


  document.getElementById("feuille_jointure").value="";
  document.getElementById("attribut_jointure_fp").value="";
  document.getElementById("attribut_jointure_fs").value="";

  if(document.getElementById("select_classeur2").value!="")
    {$.ajax({url:"traitement_jquery/liste_feuille_par_classeur_2.php", method:"POST", data:$('#form_rapport_dynamique').serialize(), success:function (data) {
    if(data!='')
    {$("#feuille_jointure").html(data);}
else 
{}}});
 }
  else {$("#feuille_jointure").html(""); }

});


document.getElementById("select_feuille").addEventListener("change", function(e){
VIDER_DIV_CRITERE();
document.getElementById("colonne_x").value="";
document.getElementById("colonne_y").value="";
document.getElementById("input_valeur").value="";
document.getElementById("operation").value="";
document.getElementById("attribut_jointure_fs").value="";
document.getElementById("attribut_jointure_fp").value="";
document.getElementById("feuille_jointure").value="";
  if(document.getElementById("select_feuille").value!="")
    {$.ajax({url:"traitement_jquery/liste_colonnes_par_feuille.php", method:"POST", data:$('#form_rapport_dynamique').serialize(), success:function (data) {
    if(data!='')
    {

      COLS_FP = data;

      $("#colonne_x").html(COLS_FP + COLS_FS.replace('<option value=""></option>','')); 
     $("#colonne_y").html(COLS_FP + COLS_FS.replace('<option value=""></option>','')); 
     $("#input_valeur").html(COLS_FP + COLS_FS.replace('<option value=""></option>',''));
  
     $("#attribut_jointure_fp").html(COLS_FP);

  document.getElementById("feuille_jointure").disabled=false;



$.ajax({url:"traitement_jquery/liste_champ_critere_par_feuille.php", method:"POST", data:$('#form_rapport_dynamique').serialize(), success:function (data) {
    if(data!=''){CHAMPS_FP_FS=data;} else {}}});


}
else 
{}}});
  document.getElementById("Boutton_Ajouter_Critere").disabled=false;
 }
  else {$("#colonne_x").html(""); $("#colonne_y").html(""); $("#input_valeur").html(""); $("#attribut_jointure_fp").html();
document.getElementById("feuille_jointure").disabled=true; document.getElementById("feuille_jointure").value="";
document.getElementById("Boutton_Ajouter_Critere").disabled=true;
}
});


document.getElementById("feuille_jointure").addEventListener("change", function(e){
  VIDER_DIV_CRITERE();
 if(document.getElementById("feuille_jointure").value!="")
    {document.getElementById("attribut_jointure_fp").disabled=false; document.getElementById("attribut_jointure_fs").disabled=false;
$.ajax({url:"traitement_jquery/liste_colonnes_par_feuille_jointure.php", method:"POST", data:$('#form_rapport_dynamique').serialize(), success:function (data) {
    if(data!='')
    {
      COLS_FS = data;

     $("#colonne_x").html(COLS_FP + COLS_FS.replace('<option value=""></option>','')); 
     $("#colonne_y").html(COLS_FP + COLS_FS.replace('<option value=""></option>','')); 
     $("#input_valeur").html(COLS_FP + COLS_FS.replace('<option value=""></option>',''));
  
     $("#attribut_jointure_fs").html(COLS_FS);

  $.ajax({url:"traitement_jquery/liste_champ_critere_par_feuille.php", method:"POST", data:$('#form_rapport_dynamique').serialize(), success:function (data) {
    if(data!=''){CHAMPS_FP_FS=data;} else {}}});

}
else 
{}}});

}
else{document.getElementById("attribut_jointure_fp").disabled=true; document.getElementById("attribut_jointure_fp").value="";
     document.getElementById("attribut_jointure_fs").disabled=true; document.getElementById("attribut_jointure_fs").value="";
}
});

</script>



<script type="text/javascript">
var class_select_champ= document.getElementsByName("class_select_champ[]");
var champ_input= document.getElementsByName("champ_input[]");
var class_group_by= document.getElementsByClassName("class_group_by");
var class_colonne_valeur= document.getElementsByClassName("class_colonne_valeur");


</script>

<!-- Vendor scripts -->

<script type="text/javascript">
   $(document).ready(function(){
  $(document).on('click','#btn_rapport', function(){
  
if(document.getElementById("nom_rapport").value=="" || document.getElementById("select_feuille").value=="" || document.getElementById("colonne_x").value=="" || document.getElementById("colonne_y").value=="" || document.getElementById("input_valeur").value=="" || document.getElementById("operation").value=="")
{alert("Veuillez renseigner tous les champs");}
else if(document.getElementById("feuille_jointure").value!="" && (document.getElementById("attribut_jointure_fp").value=="" || document.getElementById("attribut_jointure_fs").value=="")){alert("Veuillez renseigner tous les champs");}
else{
var champ_criteres=document.getElementById('champ_criteres[]');
var condition_criteres=document.getElementById('condition_criteres[]');
var valeur_criteres=document.getElementById('valeur_criteres[]');
/*for(i=0; i<champ_criteres.length; i++)
  {if(champ_criteres[i].value!="" && valeur_criteres[i].value==""){alert("Veuillez remplir le champ Valeur"); return ;}}*/

  $.ajax({url:"traitement_jquery/inserer_rapport_croise.php?action=modif", method:"POST", data:$('#form_rapport_dynamique').serialize(), success:function (data) {
    window.location.href="rapports_dynamiques.php";
  }});
  //$('#form_rapport_dynamique').submit();

}
      });

    });

var INDEX=99;

function Ajouter_Critere()
{INDEX++;
  $("#div_criteres").append('<div id="criteres_'+INDEX+'">'+'<div class="row"><div class="col-lg-2" align="center"><label></label></div><div class="col-lg-2" align="center"><label>ET / OU</label></div><div class="col-lg-2" align="center"><label>Champ</label></div><div class="col-lg-2" align="center"><label>Condition</label></div><div class="col-lg-2" align="center"><label>Valeur</label></div><div class="col-lg-2" align="center"><label></label></div></div><div class="row"><div class="col-lg-2" align="center"></div><div class="col-lg-2" align="center"><select  id="et_ou_criteres[]" name="et_ou_criteres[]" class="form-control"><option>ET</option><option>OU</option></select></div><div class="col-lg-2" align="center"><select  id="champ_criteres[]" name="champ_criteres[]" class="form-control">'+CHAMPS_FP_FS+'</select></div><div class="col-lg-2" align="center"><select  id="condition_criteres[]" name="condition_criteres[]" class="form-control"><option value="=">Egal (=)</option><option value=">">Supérieur (&gt;)</option><option value="<">Inférieur (&lt;)</option><option value=">=">Supérieur ou égal (&gt;=)</option><option value="<=">Inférieur ou égal (&lt;=)</option><option value="<>">Différent (!= / &lt;&gt;)</option><option value="%x%">Contenant (%x%)</option><option value="x%">Commençant par (x%)</option><option value="%x">Terminant par (%x)</option></select></div><div class="col-lg-2" align="center"><input type="text" name="valeur_criteres[]" id="valeur_criteres[]" placeholder="Valeur" class="form-control"></div><div class="col-lg-2" align="center"><a onclick="document.getElementById(\'criteres_'+INDEX+'\').innerHTML=\'\'"><span class="glyphicon glyphicon-remove text-danger"></span></a></div></div></div>');
}

document.getElementById("Boutton_Ajouter_Critere").addEventListener("click", Ajouter_Critere);

function VIDER_DIV_CRITERE(){$("#div_criteres").html('<div class="row"><div class="col-lg-11" align="center"><h2>Critères</h2></div></div>'); CHAMPS_FP_FS="";}
</script>

        </div>
    </div>
    <?php //require_once "./theme_components/footer.php"; ?>
</div>

</body>
</html>

<?php }
if($ii==0){header('location:rapports_dynamiques.php');}
    }
      else
        {header('location:rapport_dynamiques.php');} ?>