<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
//include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
//header('Content-Type: text/html; charset=UTF-8');
//Région
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_region = "SELECT * FROM ".$database_connect_prefix."region ";
$region = mysql_query($query_region, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_region = mysql_fetch_assoc($region);
$totalRows_region = mysql_num_rows($region);

if(isset($_GET["id"]) && !empty($_GET["id"])) { $com = $row_edit_act[$row_liste_table["Field"]];
//village
if($com>0){
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_village = "SELECT * FROM ".$database_connect_prefix."village WHERE code_village='$com'";
$village = mysql_query($query_village, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_village = mysql_fetch_assoc($village);
$totalRows_village = mysql_num_rows($village);
if(isset($row_village["commune"]) && !empty($row_village["commune"])){
$comm=$row_village["commune"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_village = "SELECT * FROM ".$database_connect_prefix."village WHERE commune='$comm'";
$village = mysql_query($query_village, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_village = mysql_fetch_assoc($village);
$totalRows_village = mysql_num_rows($village); } }

};  ?>
    <tr>
      <td width="50%">
        <div class="form-group">
          <label for="region" class="col-md-12 control-label">Région</label>
          <div class="col-md-12">
            <select name="region" id="region" class="form-control" onchange="get_content('menu_departement.php','id='+this.value,'departement','');">
            <option value="">Selectionnez</option>
      <?php do{ ?>
              <option value="<?php echo $row_region['code_region']; ?>" <?php if(isset($row_liste_retrait['region']) && $row_liste_retrait['region']==$row_region['code_region']) echo 'selected="selected"'; ?>><?php echo $row_region['nom_region']; ?></option> <?php } while ($row_region = mysql_fetch_assoc($region));  ?>
            </select>
          </div>
        </div>
      </td>
      <td width="50%">
        <div class="form-group">
          <label for="departement" class="col-md-12 control-label">D&eacute;partement </label>
          <div class="col-md-12">
            <select name="departement" id="departement" class="form-control" onchange="get_content('menu_sous_prefecture.php','id='+this.value,'commune',''); " >
              <option value="">Selectionnez</option>
            </select>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td>
        <div class="form-group">
          <label for="commune" class="col-md-12 control-label">Commune </label>
          <div class="col-md-12">
            <select name="commune" id="commune" class="form-control" onchange="get_content('menu_village.php','id='+this.value,'village','');" >

            </select>
          </div>
        </div>
      </td>
      <td>
        <div class="form-group">
          <label for="village" class="col-md-12 control-label">Village <span class="required">*</span></label>
          <div class="col-md-12">
            <select name="village" id="village" class="form-control required" >
            <?php if(isset($totalRows_village) && $totalRows_village>0){ do{ ?>
              <option value="<?php echo $row_village['code_village']; ?>" <?php if($com==$row_village['code_village']) echo 'selected="selected"'; ?>><?php echo $row_village['nom_village']; ?></option> <?php } while ($row_village = mysql_fetch_assoc($village)); }  ?>
            </select>
            <input name="field_name[]" id="field_name[]" type="hidden" value="village" size="32" alt="">
          </div>
        </div>
      </td>
    </tr>