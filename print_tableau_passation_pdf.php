<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
//session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  echo "<h1>Une erreur s'est produite !</h1>";
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";

//if(isset($_GET['periode'])) {$periode=$_GET['periode'];} else $periode=0;
//if(isset($_GET['cat'])) {$cat=$_GET['cat'];} else $cat="*";

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_last_periode = "SELECT max(id_periode) as periode FROM periode_marche";
$last_periode = mysql_query($query_last_periode, $pdar_connexion) or die(mysql_error());
$row_last_periode = mysql_fetch_assoc($last_periode);
$totalRows_last_periode = mysql_num_rows($last_periode);

if(isset($row_last_periode['periode']) && $row_last_periode['periode']>0 && !isset($_GET['periode'])) $periode=$row_last_periode['periode'];


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_categorie = "SELECT * FROM categorie_marche ORDER BY nom_categorie asc";
$liste_categorie  = mysql_query($query_liste_categorie , $pdar_connexion) or die(mysql_error());
$row_liste_categorie  = mysql_fetch_assoc($liste_categorie);
$totalRows_liste_categorie  = mysql_num_rows($liste_categorie);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_methode = "SELECT * FROM methode_marche";
$liste_methode  = mysql_query($query_liste_methode , $pdar_connexion) or die(mysql_error());
$row_liste_methode  = mysql_fetch_assoc($liste_methode);
$totalRows_liste_methode  = mysql_num_rows($liste_methode);
$methode_array = array();
if($totalRows_liste_methode>0){ do{ $methode_array[$row_liste_methode["id_methode"]]=$row_liste_methode["sigle"]; }while($row_liste_methode  = mysql_fetch_assoc($liste_methode));  }


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_bailleur = "SELECT * FROM partenaire ORDER BY sigle asc";
$liste_bailleur  = mysql_query($query_liste_bailleur , $pdar_connexion) or die(mysql_error());
$row_liste_bailleur  = mysql_fetch_assoc($liste_bailleur);
$totalRows_liste_bailleur  = mysql_num_rows($liste_bailleur);

?>

<style type="text/css">
<!--
.Style4 {color: #990000}
body {
	background-color: #FFF;
}
.Style5 {font-size: 12px}
.Style2 {	font-size: 13px;
	font-style: italic;
	font-weight: bold;
}
.Style10 {	font-size: 13px;
	font-weight: bold;
}
.Style11 {font-size: 13px}
.Style13 {font-size: x-small}
.Style15 {
	font-size: 12px;
	color: #000000;
	font-weight: bold;
}
.Style30 {font-weight: bold}
.Style31 {font-size: 14px}
.Style32 {color: #FFFFFF}
.Style34 {font-size: 12px; font-weight: bold; }
-->
</style>

<?php if(isset($_SESSION['clp_id'])) {

if($totalRows_liste_categorie>0){
do{
    $cat=$_GET['cat']=$row_liste_categorie["code_categorie"];

	mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$query_la_categorie = "SELECT * FROM categorie_marche WHERE code_categorie='$cat' ORDER BY nom_categorie asc ";
	$la_categorie  = mysql_query($query_la_categorie , $pdar_connexion) or die(mysql_error());
	$row_la_categorie  = mysql_fetch_assoc($la_categorie);
	$totalRows_la_categorie  = mysql_num_rows($la_categorie);

	mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$result=mysql_query("SELECT distinct id_etape AS DP, intitule FROM etape_marche where categorie='$cat' ORDER BY ordre, id_etape asc") or die (mysql_error());
	$etape=array();
	$titreEtape=array();
	while($ligne=mysql_fetch_assoc($result)){$etape[]=$ligne['DP']; $titreEtape[]=$ligne['intitule'];}
	mysql_free_result($result);

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$sql="SELECT * FROM periode_marche INNER JOIN plan_marche ON periode_marche.id_periode = plan_marche.periode inner join etape_plan_marche on etape_plan_marche.marche=plan_marche.id_marche where id_periode='$periode' and plan_marche.categorie='$cat' ORDER BY id_marche DESC";
	$liste_nmp=mysql_query($sql, $pdar_connexion) or die(mysql_error());
	$row_liste_nmp  = mysql_fetch_assoc($liste_nmp);
	$totalRows_liste_nmp  = mysql_num_rows($liste_nmp);

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$sql="SELECT count( id_etape ) AS nb,libelle_groupe FROM etape_marche,groupe_etape where groupe=id_groupe and categorie='$cat' GROUP BY groupe ORDER BY ordre asc ";
	$liste_gp=mysql_query($sql, $pdar_connexion) or die(mysql_error());
	$row_liste_gp  = mysql_fetch_assoc($liste_gp);
	$totalRows_liste_gp  = mysql_num_rows($liste_gp);
    $etapeGp=array();
    $titreGp=""; $i=0;
	do{  $i+=$row_liste_gp['nb'];
    $titreGp.='<td align="center" valign="middle" colspan="'.($row_liste_gp['nb']*2).'">'.$row_liste_gp['libelle_groupe'].'</td>';
    /*if($j!=$row_liste_gp['groupe']) { if($i==1) $i=2; else $i=1; $j=$row_liste_gp['groupe']; } else $i++; $etapeGp[$row_liste_gp['groupe']]=$i; $titreGp[$row_liste_gp['groupe']]=$row_liste_gp['libelle_groupe'];*/ }while($row_liste_gp=mysql_fetch_assoc($liste_gp));

if($i<count($titreEtape))
$titreGp.='<td align="center" valign="middle" colspan="'.((count($titreEtape)-$i+5)*2).'">&nbsp;</td>';
else
$titreGp.='<td align="center" valign="middle" colspan="'.(5).'">&nbsp;</td>';

echo "<h3 style='padding:0px; margin:0px;' align='center'>".$row_liste_categorie["nom_categorie"]."</h3>";
			?>
<table width="100%" border="1" align="center" cellspacing="0">

  <tr>
    <td style="border: solid 1px;" valign="top"><?php if($totalRows_liste_nmp>0) {?>
        <table width="100%" border="1" align="center" cellspacing="3">
          <tr class="titrecorps2">
            <td rowspan="2"><?php echo $row_la_categorie['nom_categorie'];?></td>
            <td rowspan="2">Nb <br />Lot</td>
            <td rowspan="2">M&eacute;thode</td>
            <td rowspan="2">Pr&eacute;vu<br />R&eacute;alis&eacute;</td>
            <td rowspan="2">Montant (Ouguiya)</td>
            <td rowspan="2">Montant (US)</td>

            <?php echo $titreGp; /*$nbcol=10; $nee=0; $num=0; $l=1; foreach($titreEtape as $te){ $num++; if(isset($etapeGp[$l]) && $num==$etapeGp[$l]){ $col=$num*2; ?>
            <!--<td align="center" valign="middle" colspan="<?php echo ($l==count($etapeGp))?$col+5:$col; ?>"><?php echo $titreGp[$l]; ?></td>-->
            <?php $l++;  $num=0; } $nbcol++; }*/?>



          </tr>
          <tr class="titrecorps2">
            <?php $nee=0; foreach($titreEtape as $te){ $nbcol++;?>
            <td align="center" valign="middle" colspan="2"><?php echo $te; $nee=$nee+1; ?></td>
            <?php }?>
            <td>Dur&eacute;e (J) </td>
            <td>Dur&eacute;e (M) </td>
            <td>Date de fin d'ex&eacute;cution </td>
            <td>Date de d&eacute;caissement </td>
            <td>Source de financement </td>
          </tr>
          <?php $j=0; $p1="j"; $nombreJ = array(); do { ?>

          <tr bgcolor="#FFFFFF">
            <td rowspan="2" valign="middle"><?php echo $row_liste_nmp['intitule']; $mr=$row_liste_nmp['id_marche']; $methode=(isset($methode_array[$row_liste_nmp['methode']]))?$methode_array[$row_liste_nmp['methode']]:""; ?></td>
            <td rowspan="2"><?php echo $row_liste_nmp['lot']; ?></td>
            <td rowspan="2" align="center"><?php echo $methode; ?></td>
            <td height="50%" bgcolor="#506429" style="color: white;">Pr&eacute;vu</td>
            <td nowrap="nowrap" align="center"><?php echo number_format($row_liste_nmp['montant_local'], 0, ',', ' '); ?></td>
            <td nowrap="nowrap" align="center"><?php echo number_format($row_liste_nmp['montant_usd'], 0, ',', ' '); ?></td>

            <?php $num = 1;     $methode=$row_liste_nmp['methode'];
            foreach($etape as $item){
			//plan planification
    		 mysql_select_db($database_pdar_connexion, $pdar_connexion);
            if($num!=1)
			$query_liste_date = "SELECT * FROM etape_marche inner join methode_etape on id_etape=etapei where categorie='$cat' and id_etape=$item and methodei=$methode ORDER BY ordre asc";
            else
            $query_liste_date = "SELECT * FROM etape_plan_marche where etape='$item' and marche='$mr'";
			$liste_date  = mysql_query($query_liste_date , $pdar_connexion) or die(mysql_error());
			$row_liste_date  = mysql_fetch_assoc($liste_date);
			$totalRows_liste_date  = mysql_num_rows($liste_date);
            //if($row_liste_nmp['methode']=="CF") echo $query_liste_date."<br />";

			//min et max de la planification
			 mysql_select_db($database_pdar_connexion, $pdar_connexion);
			$query_m_date = "SELECT min(date_prevue) as min, max(date_prevue) as max FROM etape_plan_marche where marche='$mr'";
			$m_date  = mysql_query($query_m_date , $pdar_connexion) or die(mysql_error());
			$row_m_date  = mysql_fetch_assoc($m_date);
			$totalRows_m_date  = mysql_num_rows($m_date);
            if(!isset($row_liste_date['duree']) && isset($row_liste_date['duree_etape'])) $row_liste_date['duree']=$row_liste_date['duree_etape'];
			?>
            <td align="center" valign="middle">
            <?php if(isset($row_liste_date['date_prevue'])){ echo date("d/m/y", strtotime($row_liste_date['date_prevue'])); $date_debut = $row_liste_date['date_prevue']; if($num==1) $date_deb = $row_liste_date['date_prevue']; }
            elseif(isset($row_liste_date['duree'])) { $date_next = strtotime($date_debut." +".$row_liste_date['duree']."days"); $date_debut = date("Y-m-d",$date_next); echo date("d/m/y",$date_next); $date_fin = date("Y-m-d",$date_next);  }
            else echo "N/A"; ?>
            </td>
			<td>&nbsp;</td>
            <?php $num++; }?>
            <td nowrap="nowrap" style="color:#000" align="center">
                <?php if(isset($date_deb) && isset($date_fin)) {
                  $Nombres_jours = NbJours($date_deb, $date_fin); $nombreJ[$row_liste_nmp['id_marche']]=$Nombres_jours-1;
                  echo number_format($Nombres_jours-1, 0, ',', ' ');} ?>
            </td>
            <td nowrap="nowrap" style="color:#000" align="center">
                <?php if(isset($date_deb) && isset($date_fin)) {
                  $Nombres_jours = NbJours($date_deb, $date_fin);
                  echo number_format(($Nombres_jours-1)/30, 0, ',', ' ');} ?>
            </td>
            <td nowrap="nowrap" style="color:#000" align="center">
                <?php if(isset($date_fin)) { $nbj = $Nombres_jours-1; echo date("d/m/Y",(strtotime($date_fin." +".$nbj."days"))); $fin_execution = date("Y-m-d",(strtotime($date_fin." +".$nbj."days")));  } ?>
            </td>
            <td nowrap="nowrap" style="color:#000" align="center">
                <?php if(isset($fin_execution)) { echo frenchMonthName(date("n",(strtotime($fin_execution." +45days")))).date("-Y",(strtotime($fin_execution." +45days")));  } ?>
            </td>
            <td rowspan="2" align="center"><?php if(isset($row_liste_nmp['partenaire'])) $as = explode(",", $row_liste_nmp['partenaire']); else $as=array();
            if($totalRows_liste_bailleur>0) { $bailleur = "";
			   do {  if(in_array($row_liste_bailleur['id_partenaire'], $as, TRUE)) { $bailleur .= $row_liste_bailleur['sigle'].",";}
			   }while ($row_liste_bailleur = mysql_fetch_assoc($liste_bailleur)); echo substr($bailleur,0,strlen($bailleur)-1);
      $rows = mysql_num_rows($liste_bailleur);
      if($rows > 0) {
      mysql_data_seek($liste_bailleur, 0);
	  $row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
  }  } ?></td>
          </tr>
          <tr>
            <td height="50%" bgcolor="#FF99FF" >R&eacute;alis&eacute;</td>
            <?php
	  //Montant reel
			 mysql_select_db($database_pdar_connexion, $pdar_connexion);
			$query_montant_reel = "SELECT * FROM suivi_montant_marche where marche='$mr'";
			$montant_reel  = mysql_query($query_montant_reel , $pdar_connexion) or die(mysql_error());
			$row_montant_reel  = mysql_fetch_assoc($montant_reel);
			$totalRows_montant_reel  = mysql_num_rows($montant_reel);
	  ?>
            <td nowrap="nowrap" bgcolor="#fff" class="Style5" align="center"><span <?php if(isset($row_montant_reel['montant_local']) && $row_liste_nmp['montant_local']<$row_montant_reel['montant_local']) { echo "style=\"color:#FF0000\"";} else {echo "style=\"color:#339900\"";}?> >
                <?php if(isset($row_montant_reel['montant_local']) && $row_montant_reel['montant_local']>0) echo number_format($row_montant_reel['montant_local'], 0, ',', ' '); else echo "-"; ?>
            </span></td>
            <td nowrap="nowrap" bgcolor="#fff" class="Style5" align="center">
                <?php if(isset($row_montant_reel['montant_usd']) && $row_montant_reel['montant_usd']>0) echo number_format($row_montant_reel['montant_usd'], 0, ',', ' '); else echo "-"; ?>
            </td>
            <?php $num = 1; $cum = 0;
            foreach($etape as $item){
            $where = "etape='$item'";
			//plan suivi
			 mysql_select_db($database_pdar_connexion, $pdar_connexion);
			$query_liste_sdate = "SELECT * FROM suivi_plan_marche where $where and marche='$mr'";
			$liste_sdate  = mysql_query($query_liste_sdate , $pdar_connexion) or die(mysql_error());
			$row_liste_sdate  = mysql_fetch_assoc($liste_sdate);
			$totalRows_liste_sdate  = mysql_num_rows($liste_sdate);  //if($row_liste_nmp['methode']) echo $item." - ".$query_liste_sdate;

			//min et max du suvi
    		 mysql_select_db($database_pdar_connexion, $pdar_connexion);
			$query_m_dates = "SELECT min(date_reelle) as min, max(date_reelle) as max FROM suivi_plan_marche where marche='$mr'";
			$m_dates  = mysql_query($query_m_dates , $pdar_connexion) or die(mysql_error());
			$row_m_dates  = mysql_fetch_assoc($m_dates);
			$totalRows_m_dates  = mysql_num_rows($m_dates);

			//plan planification
			 mysql_select_db($database_pdar_connexion, $pdar_connexion);
			$query_liste_date2 = "SELECT * FROM etape_plan_marche where $where and marche='$mr'";
			$liste_date2  = mysql_query($query_liste_date2 , $pdar_connexion) or die(mysql_error());
			$row_liste_date2  = mysql_fetch_assoc($liste_date2);
			$totalRows_liste_date2  = mysql_num_rows($liste_date2);
			?>
            <td align="center" bgcolor="#fff" valign="middle" nowrap="nowrap" <?php $dj=date("Y-m-d"); if(isset($row_liste_sdate['date_reelle']) && isset($row_liste_date2['date_prevue']) && $row_liste_sdate['date_reelle']<=$row_liste_date2['date_prevue']) { $color= "#00FF33";} elseif(isset($row_liste_sdate['date_reelle']) && isset($row_liste_date2['date_prevue']) && $row_liste_sdate['date_reelle']>$row_liste_date2['date_prevue']) { $color= "#FF0000";} elseif(!isset($row_liste_sdate['date_reelle']) && isset($row_liste_date2['date_prevue']) && $dj>=$row_liste_date2['date_prevue']) { $color= "#FF0000";} elseif(!isset($row_liste_date2['date_prevue'])) { $color= "";} ?>>
              <span <?php if($color!="" && isset($row_liste_sdate['date_reelle'])) echo 'style="background-color:'.$color.'; border: dashed '.$color.' 1px;"'; ?>><?php if(isset($row_liste_sdate['date_reelle'])) echo date("d/m/y", strtotime($row_liste_sdate['date_reelle'])); else echo "-";
              if($num==1) { $last_date = $row_liste_sdate['date_reelle']; $date_deb = $row_liste_sdate['date_reelle']; }
              ?></span>
            </td>
			<td><?php if($num==1) echo "&nbsp;"; elseif(isset($row_liste_sdate['date_reelle'])){ $cum++; echo "<div align='center' style='background-color:yellow;'>"; echo ($num==2)?NbJours($last_date,$row_liste_sdate['date_reelle'])-1:NbJours($last_date,$row_liste_sdate['date_reelle']); $last_date = $row_liste_sdate['date_reelle']; echo "</div>"; }  ?></td>
            <?php $num++; if(isset($row_liste_sdate['date_reelle'])) $date_fin = $row_liste_sdate['date_reelle']; else unset($date_fin); }?>
            <td bgcolor="#fff" align="center" nowrap="nowrap"  <?php echo (isset($date_deb)  && isset($date_fin) && $nombreJ[$row_liste_nmp['id_marche']]<(NbJours($date_deb, $date_fin)-1))?'style="color:#FF0000"':'style="color:#00C427"'; ?>>
              <?php
              if(isset($date_deb)  && isset($date_fin)) { $Nombres_jours = NbJours($date_deb, $date_fin);
              echo (($Nombres_jours-1)>0)?number_format($Nombres_jours-1, 0, ',', ' '):""; }
              ?></td>
            <td bgcolor="#fff" nowrap="nowrap" align="center"><?php if(isset($date_deb)  && isset($date_fin)) { echo (($Nombres_jours-1)>0)?number_format(($Nombres_jours-1)/30, 0, ',', ' '):""; } ?></td>
            <td bgcolor="#fff" nowrap="nowrap" ><div align="center"><b><?php if(isset($date_fin)) { $nbj = $Nombres_jours-1; echo date("d/m/Y",(strtotime($date_fin." +".$nbj."days"))); $fin_execution = date("Y-m-d",(strtotime($date_fin." +".$nbj."days")));  } ?></b></div></td>
            <td bgcolor="#fff" nowrap="nowrap" align="center"><?php if(isset($fin_execution) && isset($date_fin)) { echo frenchMonthName(date("n",(strtotime($fin_execution." +45days")))).date("-Y",(strtotime($fin_execution." +45days")));  } ?> </td>
          </tr>
         <!--<tr bgcolor="#FFFFFF">
            <td colspan="<?php echo $nbcol;  ?>" align="center"><hr id="sp_hr" /></td>
          </tr>-->
          <?php unset($date_debut); } while ($row_liste_nmp = mysql_fetch_assoc($liste_nmp)); ?>
        </table>
      <?php } else echo "<h1 align='center'>Aucun march&eacute; !</h1>"; ?>
    </td>
  </tr>
 </table>
<?php   }while($row_liste_categorie  = mysql_fetch_assoc($liste_categorie));   }  ?>
 <?php } ?>