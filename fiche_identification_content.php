<?php

///////////////////////////////////////////////



/*                 SSE                       */



/*	Conception & Développement: SEYA SERVICES */



///////////////////////////////////////////////



session_start();



include_once 'system/configuration.php';



$config = new Config;







if (!isset ($_SESSION["clp_id"])) {



  header(sprintf("Location: %s", "./"));



  exit;



}



include_once $config->sys_folder . "/database/db_connexion.php";



//header('Content-Type: text/html; charset=ISO-8859-15');








if(isset($_GET['annee'])) {$annee=intval($_GET['annee']);} else $annee=date("Y");



$dir = './attachment/fiche_collecte/';







//fonction calcul nb jour



function NbJours($debut, $fin) {



  $tDeb = explode("-", $debut);



  $tFin = explode("-", $fin);



  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);



  return(($diff / 86400)+1);



}



/*mysql_select_db($database_pdar_connexion, $pdar_connexion);

$query_liste_respo_ugl = "SELECT id_personnel, fonction FROM personnel where structure='".$_SESSION["clp_structure"]."' and projet like '%".$_SESSION["clp_structure"]."|%' ";

$liste_respo_ugl  = mysql_query_ruche($query_liste_respo_ugl , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

$row_liste_respo_ugl  = mysql_fetch_assoc($liste_respo_ugl );

$totalRows_liste_respo_ugl  = mysql_num_rows($liste_respo_ugl );

$respo_ugl=array();

if($totalRows_liste_respo_ugl>0){ do{ $respo_ugl[$row_liste_respo_ugl["id_personnel"]]=$row_liste_respo_ugl["fonction"];  }while($row_liste_respo_ugl  = mysql_fetch_assoc($liste_respo_ugl ));  } */



   /*for($j=$_SESSION["annee_debut_projet"];$j<=date("Y");$j++)
   { 
   $tableauAnnee[]=$j;
   }*/

if(isset($annee_c)) $annee_c=$annee_c; else $annee_c=date("Y");
if(isset($_GET['r'])) $ugmp=$_GET['r']; else $ugmp='000';






//liste village

$query_liste_village = "SELECT *  FROM commune where departement='$ugmp'  order by code_commune asc";
 try{
    $liste_village = $pdar_connexion->prepare($query_liste_village);
    $liste_village->execute();
    $row_liste_village = $liste_village ->fetchAll();
    $totalRows_liste_village = $liste_village->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$village_array =$code_commune_array = array();
if($totalRows_liste_village>0){ foreach($row_liste_village as $row_liste_village){ 
  $village_array[$row_liste_village["nom_commune"]] = $row_liste_village["nom_commune"];
    $tableauAnnee[$row_liste_village["code_commune"]] = $row_liste_village["nom_commune"];
//$annee_c =$row_liste_village["code_commune"];
  
}  }


//liste village
$query_liste_com = "SELECT code_village,nom_village  FROM village where left(code_village,1)='$ugmp'  order by code_village asc";
 try{
    $liste_com = $pdar_connexion->prepare($query_liste_com);
    $liste_com->execute();
    $row_liste_com = $liste_com ->fetchAll();
    $totalRows_liste_com = $liste_com->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$departement_array =$commune_array = array();
if($totalRows_liste_com>0){  foreach($row_liste_com as $row_liste_com){ 
  $commune_array[$row_liste_com["code_village"]] = $row_liste_com["nom_village"];
}  }

//print_r( $commune_array); exit;


function  Validation_identification ($niveau,$ids,$pdar_connexion){
    $date=date("Y-m-d");

    if (count($ids) > 1){

        for ($k = 0;$k < count($ids);$k++){
            $stat = "UPDATE t_1646217521  SET Stat=".$niveau." WHERE Id= ".$ids[$k];
            try{
                $status_c = $pdar_connexion->prepare($stat);
                $status_c->execute();
            }catch(Exception $e){ die(mysql_error_show_message($e)); }



            if ($niveau == 2){

              $validation  = sprintf("INSERT INTO validation_step  (nom_table,id_fiche,ong,date_validation_ong) VALUES (%s, %s, %s, '$date')",
                  GetSQLValueString("t_1646217521", "text"),
                  GetSQLValueString($ids[$k], "int"),
                  GetSQLValueString($_SESSION['N'], "int"));
            }
            else{

                $validation  = "UPDATE validation_step SET pf=".$_SESSION['N'].", SET date_validation_pf=".$date."WHERE id_fiche= ".$ids[$k];
            }


            try{
                $valid = $pdar_connexion->prepare($validation);
                $valid->execute();

                return true;
            }catch(Exception $e){ die(mysql_error_show_message($e)); }


        }
    }

    else{

        $stat = "UPDATE t_1646217521  SET Stat=".$niveau." WHERE Id= ".$ids[0];
              try{
                  $status_c = $pdar_connexion->prepare($stat);
                  $status_c->execute();
              }catch(Exception $e){ die(mysql_error_show_message($e)); }


        if ($niveau == 2){

            $validation  = sprintf("INSERT INTO validation_step  (nom_table,id_fiche,ong,date_validation_ong) VALUES (%s, %s, %s, '$date')",
                GetSQLValueString("t_1646217521", "text"),
                GetSQLValueString($ids[0], "int"),
                GetSQLValueString($_SESSION['N'], "int"));
        }
        else{

            $validation  = "UPDATE validation_step SET pf=".$_SESSION['N'].", SET date_validation_pf=".$date."WHERE id_fiche= ".$ids[0];
        }



        try{
            $valid = $pdar_connexion->prepare($validation);
            $valid->execute();

            return true;
        }catch(Exception $e){ die(mysql_error_show_message($e)); }
    }

}


$status_ = "SELECT * FROM validation_step  where nom_table='t_1646217521'";

try{
    $status_c = $pdar_connexion->prepare($status_);
    $status_c->execute();
    $status_check = $status_c ->fetchAll();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


function check_statut_ ($id,$status_check){
    $output_ong = array();
    $output_pf = array();
    for ($k = 0; $k < count($status_check);$k++){
        if ($status_check[$k]['id_fiche']  ==  $id){
            if (!is_null($status_check[$k]['ong']) && is_null($status_check[$k]['pf'])){
                array_push($output_ong,$status_check[$k]);
            }
            else if (!is_null($status_check[$k]['ong']) && !is_null($status_check[$k]['pf'])){
                array_push($output_pf,$status_check[$k]);
            }
        }
    }
    if (count($output_ong) > 0 && count($output_pf)){
         $output = [
                  'ong'=> $output_ong,
                  'pf' => $output_pf
               ];

         return $output;
    }else{
        return false;
    }

}

$niv = 0;


if ( $_SESSION['clp_id'] == "Ongnord" ||  $_SESSION['clp_id'] == "Ongsud"){
    $niv = 2;
}else if ($_SESSION['clp_fonction'] == "PFSE"){
   $niv = 1;

}
if (isset($_POST['ident']) && !empty($_POST['ident'])){
    var_dump($_POST['ident']);

    die;

    if (Validation_identification(1,$_GET['ident'],$pdar_connexion)){
        echo "true";
        die;
    }else{
        echo "false";
        die;
    }
}


//var_dump($_SESSION);
//
//die;

?>

<style type="text/css">
<!--
.firstcapitalize:first-letter {


  text-transform: capitalize;
}
.table {  border-spacing: 0px !important; border-collapse: collapse; width: 100%!important;
}
-->
</style>




<div class="widget-content">
      <div class="tabbable tabbable-custom" >
        <ul class="nav nav-tabs" >
          <?php //for($j=1;$j<=4;$j++){ ?>
          <?php $j=0; /*foreach($tableauAnnee as $anpta){ */  foreach($tableauAnnee as $anpta=>$b){?>
          <li title="Ann&eacute;e <?php echo $anpta; ?>" class="<?php echo ($anpta==$annee_c || (!in_array($anpta,$tableauAnnee) && $j==0))?"active":""; ?>"><a href="#tabta_feed_<?php echo $anpta.$ugmp; ?>" data-toggle="tab"><?php if(isset( $village_array[$anpta])) echo  $village_array[$anpta]; else echo $b; ?></a></li>
          <?php $j++; } ?>
        </ul>
        <div class="tab-content">
<?php  $j = 0; /*foreach($tableauAnnee as $anpta){ */ foreach($tableauAnnee as $anpta=>$b){
    $query_act = "SELECT t_1646217521.* FROM t_1646217521  where col2 like '$b' and col2!='N/A'  order by col3 desc ";
	//echo $query_act;
		 try{
    $act = $pdar_connexion->prepare($query_act);
    $act->execute();
    $row_act = $act ->fetchAll();

    $totalRows_act = $act->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

     <script>

$().ready(function() {
$(".bs-popover").popover();
//$('a[data-toggle="modal"]').modal();
 $('#mtable<?php echo $anpta.$ugmp; ?>').dataTable();
});
</script>


    <script  type="text/javascript">

        function isOnScreen(el) {
            return (el.offsetParent !== null);
        }


        var allValues = []


        $("#selectAll").on('change',function(){

            allValues = []
            var checkboxes = document.getElementsByName('id_val');

            if (this.checked) {
                checkboxes.checked = true ;
                for (var i = 0; i < checkboxes.length; i++) {
                    if (isOnScreen(document.getElementsByName('id_val')[i]))
                    {
                        allValues.push( checkboxes[i].value)
                    }
                }
            }
            else {
                checkboxes.checked = false ;
                allValues = []
            }


            $('#selectAll').on('click', function() {
                clearInterval(this);

            });

        });


    </script>

    <script>
        var checkedId = []


        $('#validateSelect').on('click',function(){
            console.log(checkedId)



        })


        $('#validateAll').on('click',()=>{

         if (checkedId.length != 0){
             $.ajax({
                 url : "",
                 type : "get",
                 data : {
                     ident : checkedId
                 },
                 success:function (msg) {
                     if (msg == 'true'){
                         window.location.reload(true)
                     }else{
                         alert("Cette action n'est pas autorisé")
                     }
                 }
             })
         }

        })


        function validateAll(){
            if (v.checked){
                // $.ajax({
                //     url "",
                //     type : "get",
                //     data : {
                //         ident : v
                //     },
                //     success:function (msg) {
                //         if (msg == 'true'){
                //             window.location.reload(true)
                //         }else{
                //             alert("Cette action n'est pas autorisé")
                //         }
                //     }
                // })
            }else{
                return false;
            }
        }
    </script>




<?php //for($j=1;$j<=4;$j++){ ?>
 <div class="tab-pane <?php echo ($anpta==$annee_c || (!in_array($anpta,$tableauAnnee) && $j==0))?"active":""; ?>" id="tabta_feed_<?php echo $anpta.$ugmp; ?>">
     <div class="scroller">
<div class="clear">&nbsp;</div>


         <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
             <div class="row">
                 <div class="col-lg-offset-8 col-lg-4">
                     <div class="pull-right">
                         <button class="btn btn-sm btn-success" id="validateSelect" >Valider</button>
                         <button class="btn btn-sm btn-success" id="validateAll">Valider tout</button>
                     </div>

                 </div>
             </div>
         <?php } ?>

         <br/>
<!--         <p class="pull-right">Fonction : --><?//= $_SESSION['clp_fonction'] ?><!--</p>-->

         <table class="table table-striped table-bordered table-hover table-responsive table-tabletools table-colvis datatable dataTable" id="mtable<?php echo $anpta.$ugmp; ?>" aria-describedby="DataTables_Table_0_info">

  <thead>
    <tr>
      <th  width="10">
          <input type="checkbox"   id="selectAll" >
      </th>
      <th> Statut</th>
      <th>N&deg;</th>
      <th>Canton <?php echo $ugmp; ?></th>
      <th>Localité</th>
      <th>Prénoms et Nom </th>
      <th>Sexe</th>
      <th>Age</th>
      <th>Niveau étude </th>
      <th>Télephone</th>
      <th>Chaine de valeur </th>
      <th> Code PEI </th>
      <th> JCE associé </th>

      <!--<th>Liste B&eacute;n&eacute;ficiaire directs </th>
<th nowrap="nowrap">Ordre de<br/>

  mission</th>

<th><div class="firstcapitalize">Rapport</div></th>
-->
      <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
      <th class="" role="" tabindex="0" aria-controls="" aria-label="" width="80">Actions</th>
      <?php } ?>
    </tr>
  </thead>
  <tbody role="alert" aria-live="polite" aria-relevant="all" class="">
    <?php $i=0; if($totalRows_act>0) { foreach($row_act as $row_act){  $id = $row_act["Id"];   ?>
    <tr >
    <td class="checkbox-column">
    <input type="checkbox" name="id_val"   onchange="
    if (this.checked){
                checkedId.push(this.value)
            }else{
                checkedId.splice(checkedId.findIndex(k => k === this.value), 1);
            }

    console.log(checkedId)

" value="<?php echo $id; ?>" class="uniform checkbox-input" />
     </td>
        <td>
            <?php
            $ischeck = check_statut_($id,$status_check);

              if (!$ischeck){
                ?>
                      <b class="badge badge-danger">Non Validé</b>
                <?php
                    }else {

                  var_dump($ischeck);
                  die;
                 ?>
                <b class="btn btn-sm btn-success">validé</b>
                <?php
                 }
                ?>
        </td>
      <td><?php echo $i+1;  ?></td>
      <td><strong><?php /*if(isset($commune_array[$row_act['col0']])) echo  $commune_array[$row_act['col0']]; else*/ echo $row_act['col3'];  ?></strong></td>
      <td><div align="left"><?php echo $row_act['col4'];  ?></div></td>
      <td><?php echo $row_act['col5'];  ?></td>
      <td><?php echo $row_act['col6'];  ?></td>
      <td><?php echo $row_act['col7']; ?></td>
      <td><?php echo $row_act['col8']; ?></td>
      <td><?php echo $row_act['col9']; ?></br><?php echo $row_act['col10']; ?></td>
      <td><?php echo $row_act['col11']; ?></td>
      <td><?php echo $row_act['col18']; ?></td>
        <td>

        </td>
      <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<td align="center" nowrap="nowrap" class=" "><?php
if(isset($row_act['code_ugl']) && $row_act['code_ugl']==$_SESSION["clp_structure"] || $_SESSION['clp_id']=='admin') {
echo do_link("","","Fiche identification des jeunes","","edit","./","","get_content('new_fiche_jeune.php','id=".$id."&annee=".$annee."','modal-body_add',this.title);",1,"margin:0px 5px 0 0; ","fiche_ong.php");
echo do_link("","./fiche_identification.php?id_sup=".$id."&annee=".$annee,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce jeune ?');",0,"margin:0px 0 0 5px;","fiche_identification.php");
}

?>
</td>

      <?php } ?>
    </tr>
    <?php $i++; }  } ?>
  </tbody>
</table>


</div>



</div>





          <?php $j++; } ?>
      </div>
      </div>
    </div>


