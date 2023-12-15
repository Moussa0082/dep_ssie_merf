<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
$path = '../';
include_once $path.'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $path.$config->sys_folder . "/database/db_connexion.php";
header('Content-Type: text/html; charset=ISO-8859-15');

if(isset($_GET["annee"])) { $annee=$_GET["annee"];} else $annee=date("Y");



$poids_max=2048576; //Poids maximal du fichier en octets

$extensions_autorisees=array('rar','doc','pdf', 'zip', 'docx'); //Extensions autorisées

$url_site='./attachment/'; //Adresse où se trouve le fichier upload.php



//require_once('../Connections/pdar_connexion.php');

//include_once $path_racine."configurations.php";

//$config = new MSIConfig();

  $query_entete = "SELECT code_number FROM niveau_config WHERE ".$_SESSION["clp_where"]." LIMIT 1";
    try{
    $entete = $pdar_connexion->prepare($query_entete);
    $entete->execute();
    $row_entete = $entete ->fetch();
    $totalRows_entete = $entete->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  //$niveau = $row_entete["code_number"];
  //$niveau=(explode(",",$niveau ));
//$max_niveau=end(explode(",",$row_entete["code_number"] ));


$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {

  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);

}



if(isset($_GET["annee"])) {$annee=$_GET['annee'];}



$page = $_SERVER['PHP_SELF'];


//import
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1"))
{
echo "Je suis la";
    $poids_max=2048576; //Poids maximal du fichier en octets
    $extensions_autorisees=array('xls','xlsx'); //Extensions autorisées ,'csv'
    $url_site='../attachment/'; //Adresse où se trouve le fichier upload.
    $page = $_SERVER['PHP_SELF'];
    $ext = substr(strrchr($_FILES['fichier']['name'], "."), 1);

    $annee=isset($_POST["annee"])?$_POST["annee"]:date("Y");
    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

    if(in_array($ext,$extensions_autorisees))
    {
      if($_FILES['fichier']['size']>$poids_max)
      {
        $message='Un ou plusieurs fichiers sont trop lourds !';
        echo $message;
      }
      elseif(isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0)
      {
        $inputFileName=$url_site.$_FILES['fichier']['name'];
        move_uploaded_file($_FILES['fichier']['tmp_name'],$inputFileName);

        require_once('../Classes/PHPExcel.php');
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
            . '": ' . $e->getMessage());
        }
       // if(isset($_POST["erase"]) && $_POST["erase"]==1)
        //{
          $query_sup_import_annee = "DELETE FROM code_budget WHERE annee=$annee and projet='".$_SESSION["clp_projet"]."' and structure='".$_POST["crp"]."'";	  
		    try{
    $Result1 = $pdar_connexion->prepare($query_sup_import_annee);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
        //}

        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $code = ""; $i=0;

//nombre de caractere activite
$query_nbr_car = "SELECT libelle,code_number FROM niveau_budget_config WHERE projet='".$_SESSION["clp_projet"]."' LIMIT 1";
  try{
    $nbr_car = $pdar_connexion->prepare($query_nbr_car);
    $nbr_car->execute();
    $row_nbr_car = $nbr_car ->fetch();
    $totalRows_nbr_car = $nbr_car->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if(isset($row_nbr_car["code_number"]))
{
  $code_len = explode(',',$row_nbr_car["code_number"]);
  $libelle=explode(",",$row_nbr_car["libelle"]);
  $nbr_car = $code_len[count($code_len)-1];
}
else $nbr_car = 1;

        for ($row = 0; $row <= $highestRow; $row++)
        {
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
            NULL, TRUE, FALSE);


            if(!empty($rowData[0][0]) && !empty($rowData[0][1]) )
            {
if(strlen(trim($rowData[0][0]))<=$nbr_car && $rowData[0][0]!='Code') $code=trim($rowData[0][0]);

$insertSQL="INSERT INTO code_budget (code, libelle, cout_realise , cout_engage, cout_prevu, annee, structure, projet) VALUES ('".trim($rowData[0][0])."',".GetSQLValueString(trim(utf8_encode($rowData[0][1])),"text").",'".str_replace(" ","",$rowData[0][2])."', '".str_replace(" ","",$rowData[0][3])."', '".str_replace(" ","",$rowData[0][5])."','".$annee."', '".$_POST["crp"]."', '".$_SESSION["clp_projet"]."')";
		    try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
           }      $i++;
          }
          if(!empty($inputFileName))
          {
$insertSQL="INSERT INTO code_budget (code, libelle, cout_realise , cout_engage, cout_prevu, annee, structure, projet) VALUES ('fichiers','$inputFileName',0, 0, 0,'".$annee."', '".$_POST["crp"]."', '".$_SESSION["clp_projet"]."')";
		    try{
    $Result0 = $pdar_connexion->prepare($insertSQL);
    $Result0->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
          }
          //unlink($inputFileName);
          if($Result1) $insertGoTo = $page."?import=ok";
          else $insertGoTo = $page."?import=no";
          $insertGoTo .= "&annee=$annee";
          header(sprintf("Location: %s", $insertGoTo)); exit();
        }
    }
    else
    {
      $insertGoTo = $page."?import=no";
      $insertGoTo .= "&annee=$annee";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }
}





if(isset($_GET["cl"])){

 $insertGoTo = "../annee.php";

 ?>

  <script type="text/javascript">



  parent.location.href = "<?php echo $insertGoTo; ?>";



  </script>

  <?php exit(0);

}

$uglprojet=str_replace("|",",",$_SESSION["clp_projet_ugl"]);

$query_liste_crp = "SELECT * FROM ".$database_connect_prefix."ugl where FIND_IN_SET(code_ugl,'".$uglprojet."')  order by code_ugl";
  try{
    $liste_crp = $pdar_connexion->prepare($query_liste_crp);
    $liste_crp->execute();
    $row_liste_crp = $liste_crp ->fetchAll();
    $totalRows_liste_crp = $liste_crp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"

    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">



<head>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->
<link rel="stylesheet" type="text/css" href="<?php print $path.$config->theme_folder;?>/plugins/jquery-ui.css"/>
<link href="<?php print $path.$config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $path.$config->theme_folder;?>/responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?php print $path.$config->theme_folder;?>/icons.css" rel="stylesheet" type="text/css"/>
<link href='<?php print $path.$config->theme_folder;?>/css.css' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php print $path.$config->theme_folder; ?>/fontawesome/font-awesome.min.css">
<script type="text/javascript" src="<?php print $path.$config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>
<script type="text/javascript" src="plugins/noty/layouts/top.js"></script>
<script type="text/javascript" src="plugins/noty/themes/default.js"></script>
<script type="text/javascript" src="<?php print $path.$config->script_folder;?>/myscript.js"></script>
<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
<script>
	$().ready(function() {
		// validate the comment form when it is submitted
		$("#form1").validate();
	});
</script>
<style>
#mtable2 .dataTables_length, #mtable2 .dataTables_info { float: left; font-size: 10px;}
#mtable2 .dataTables_length, #mtable2 .dataTables_paginate, .DTTT, .ColVis { display: none;}
@media(min-width:558px){.col-md-12 {width: 100%;}.col-md-9 {width: 75%;}.col-md-3 {width: 25%;}.col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {float: left;}}
.ui-datepicker-append {display: none;}
.Style1 {font-weight: bold}
.Style2 {font-size: 14px}
.Style3 {
	font-size: 18px;
	font-weight: bold;
}
</style>
</head>



<body>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i><?php if(isset($_GET['id'])) echo "Joindre le ficher à importer"; else echo "Joindre le ficher à importer" ; ?></h4></div>
<div class="widget-content">

                    <form action="" method="post" name="form1" id="form1"   enctype="multipart/form-data">

                      <div id="special">

                        <p> <a name="nmp" id="nmp"></a>                        </p>

                       

                        <table align="center">
     <tr valign="baseline">
                            <td align="right" valign="top" nowrap="nowrap"><strong>Antenne&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
                            <td colspan="3" align="right" valign="top" nowrap="nowrap"><div class="form-group">

         

          <div class="col-md-9">

            <select name="crp" id="crp" class="full-width-fix select2-select-00 required" data-placeholder="S&eacute;lectionnez une antenne">
              <option value="">Selectionnez</option>
              <?php if($totalRows_liste_crp>0){ foreach($row_liste_crp as $row_liste_crp){ ?>
              <option value="<?php echo $row_liste_crp['code_ugl']; ?>"><?php echo $row_liste_crp['abrege_ugl'].": ".$row_liste_crp['nom_ugl']; ?></option>
                <?php  }  } ?>
            </select>
          </div>
        </div>  </td>
                          </tr>
                          <tr valign="baseline">

                            <td align="right" valign="top" nowrap="nowrap"><span class="Style1"><span class="Style9 Style5">&nbsp;<strong><span class="Style2" style="background-color:#CCCCCC "><strong>Joindre le fichier &agrave; importer &nbsp;</strong></span></strong></span></span></td>

                            <td colspan="3" align="right" valign="top" nowrap="nowrap"><div align="left"> &nbsp;&nbsp;<span class="Style1"><span class="Style9 Style5"><strong><span class="Style2" style="background-color:#CCCCCC "><strong>

                                <input type="file" name="fichier" id="fichier" size="5" />

                                <input type="hidden" name="MAX_FILE_SIZE" value="20485760" />

                            </strong></span></strong></span> </span> </div></td>
                          </tr>

                         

                          <tr valign="baseline">

                            <td colspan="2" align="right" nowrap="nowrap"><div align="right"> </div>

                                <div align="left"> </div>

                                <div align="right"> </div>

                                <div align="left"> </div></td>

                            <td align="right" nowrap="nowrap">

                                <div align="left">

                                  <input name="Envoyer" type="submit" class="inputsubmit" <?php if(!$annee>0) echo 'disabled' ?> value="<?php if(isset($_GET['annee'])) echo "Enregistrer"; else echo "Enregistrer" ; ?>" />
                               </div></td>

                            <td align="right" nowrap="nowrap"></td>
                          </tr>
                        </table>
                        <input type="hidden" name="annee" value="<?php  echo $annee;  ?>" />
                        <input type="hidden" name="<?php  echo "MM_insert";  ?>" value="form1" />
                      </div>
                    </form>
					
					
					<br /><br />
					<?php if(isset($_GET["import"]) && $_GET["import"]=="ok") {?>
					<div align="center" class="Style3" style="background-color:#66CC00; color:#FFFFFF">Importation terminée avec succès.<br /> Vous pouvez fermer cette fénêtre</div>
					<?php } ?>
</div>
</div>
</body>
</html>