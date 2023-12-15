<?php require_once '../../api/Fonctions.php'; ?>



<?php if(isset($_GET) AND !empty($_GET["Code"])) :

foreach (FC_Rechercher_Code("SELECT * FROM `t_1612875542` WHERE `Id` = '".trim($_GET["Code"])."'") as $key) {
file_get_contents("http://".$_SERVER["HTTP_HOST"]."/fiches/libs/qrcode/index.php?data=".urlencode($key["col0"])."&level=H&size=10&complete_name=code_".str_replace(" ", "", $key["Id"]).".png");

?>
<!DOCTYPE html>
<html>
<head>
  <title>Carte</title>
  <link rel="stylesheet" href="../../vendor/bootstrap/dist/css/bootstrap.css" />
</head>
<body>

          <div style="height: auto; max-width:380px; margin: 0 auto;">
           <div class="carte">
            <table width="100%" style="border:1px solid #CCC;">
             <tr>
                
                <td align="left" style="vertical-align: top;">
                  <strong><?php echo $key["col6"]; ?></strong> <?php echo $key["col7"]; ?>
                  <br>
                  <strong>Date de naissance :</strong> <?php echo $key["col9"]; ?>
                  <br>
                  <strong>Sexe :</strong>
                   <?php echo $key["col8"]; ?> / <?php echo $key["col10"]; ?>
                   <br>
                  <strong>Contact :</strong>
                   <?php echo $key["col12"]; ?>
                   <br>
                  <strong>Vilage :</strong>
                   <?php echo $key["col5"]; ?>
                </td>
                <td width="1px"></td>
                <td style="text-align: left; width: 95px; padding-right: 5px;padding-top: 5px;" >
              <div class="photo">
                <?php if (file_exists('../../pieces/'.$key["col14"]) AND is_file('../../pieces/'.$key["col14"])){
                  echo '<img src="../../pieces/'.$key["col14"].'" width="90px" alt="Photo" height="95px" style="padding-top: 5px">';
                } ?>
                
              </div>  
              </td>
             </tr>
             <tr style="background-color: #F5F5DC;">
               <td colspan="3" style="height: 30px!important">
                <p style="width: 100%; height: 25px; padding-left: 5px;padding-top: 2px; color: maroon;padding: 0; margin: 0; padding-top: 5px;" > <font style="font-size: 14px;"> Code :</font> <span style=" font-size: 12px;"><?php echo $key["col0"]; ?></span></p>
              </td>
             </tr>
             <tr>
                <td colspan="2" style="padding-left: 5px;margin-top: -10px"><font style="font-weight:bold;font-size: 14px;">Type de bénéficiaire</font><br>
                  <i style="font-weight:bold;font-size: 12px;color: #D76230; font-family: tahoma; text-align: left;margin-top: -10px"> <?php echo $key["col13"]; ?></i>
              </td> 
              <td rowspan="2" style="text-align: right; width: 65px; padding-right: 10px;" >
                <?php if (file_exists('../../pieces/code_'.str_replace(" ", "", $key["Id"]).'.png') AND is_file('../../pieces/code_'.str_replace(" ", "", $key["Id"]).'.png')){
                  echo '<img src="../../pieces/code_'.str_replace(" ", "", $key["Id"]).'.png" width="60px" alt="Code" height="60px" style="padding-top: 5px">';
                } ?>
                
             </tr>
             <tr>
                <td colspan="2" align="center"><i>&copy; <?php echo date('Y'); ?></i></td>
             </tr>
            </table>
</body>
</html>
<?php } endif; ?>