<?php

session_start();
include_once 'system/configuration.php';
$config = new Config;


$pei_query = "SELECT *  FROM t_1646217521  WHERE col11 = 'Soja' " ;
try{
    $pei_quer = $pdar_connexion->prepare($pei_query);
    $pei_quer->execute();
    $Soja = $pei_quer ->fetchAll();
}catch(Exception $e){ die(mysql_error_show_message($e)); }



$pei_query = "SELECT *  FROM t_1646217521  WHERE col11 = 'Maïs' " ;
try{
    $pei_quer = $pdar_connexion->prepare($pei_query);
    $pei_quer->execute();
    $mais = $pei_quer ->fetchAll();
}catch(Exception $e){ die(mysql_error_show_message($e)); }



$pei_query = "SELECT *  FROM t_1646217521  WHERE col11 = 'Manioc' " ;
try{
    $pei_quer = $pdar_connexion->prepare($pei_query);
    $pei_quer->execute();
    $manioc = $pei_quer ->fetchAll();
}catch(Exception $e){ die(mysql_error_show_message($e)); }



$pei_query = "SELECT *  FROM t_1646217521  WHERE col11 = 'Elevage' " ;
try{
    $pei_quer = $pdar_connexion->prepare($pei_query);
    $pei_quer->execute();
    $elevages = $pei_quer ->fetchAll();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


//var_dump($Soja);
//
//die;

?>

<?php


if (isset($_GET['code'])){

    $code = $_GET['code'];
    $pei_query = "SELECT *  FROM t_1646217521  WHERE col18 = '$code' " ;
    try{
        $pei_quer = $pdar_connexion->prepare($pei_query);
        $pei_quer->execute();
        $pei = $pei_quer ->fetch();

        die(json_encode($pei));

    }catch(Exception $e){ die(mysql_error_show_message($e)); }


}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php print $config->sitename;?></title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/css/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.1/css/buttons.bootstrap.min.css" type="text/css" />
    <style>
        .bottom__space{
            margin-bottom: 100px;
        }
    </style>

</head>

<body>

<nav class="navbar  navbar-fixed-top" style="background: #168E37">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#" style="color: #fff">SYSTÈME DE SUIVI-ÉVALUATION</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <div class="navbar-form navbar-right">
                <div class="form-group">
                    <input type="text" placeholder="Chercher PEI" class="form-control" id="input"  onkeyup="searchPei(this.value)">
                </div>
            </div>
        </div><!--/.navbar-collapse -->
    </div>
</nav>



<div class="jumbotron">
    <div class="container">
       <h2>Identification des PEI</h2>
    </div>
</div>


<div id="primaryObject">
    <div class="container">
        <div class="col-md-12">
            <div >
                <div class="col-lg-offset-3 col-lg-5">
                    <div class="form-group">
                        <label for="">Région</label>
                        <select name="" id="regionsearch" class="form-control">
                            <option value="">-</option>
                            <option value="tout">Tout</option>
                            <option value="Maritime">Maritime</option>
                            <option value="Plateaux">Plateaux</option>
                            <option value="Centrale">Centrale</option>
                            <option value="Kara">Kara</option>
                            <option value="Savane">Savane</option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group" style="margin-top: 25px;">
                        <button class="btn btn-success" onclick="filterWith()">Filtrer</button>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-lg-offset-5" id="errorSearch"></div>
        </div>
    </div>
    <div class="container" style="margin-top: 30px;">

        <div class="row">
            <div class="col-md-12">
                <h3>PEI Elevage</h3>
                <hr/>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>PEI</th>
                        <th>Région</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  foreach ($elevages as $elevage): ?>
                        <tr>
                            <td><?=  $elevage['col5'] ?></td>
                            <td><?=  $elevage['col0'] ?></td>
                        </tr>
                    <?php  endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="col-md-12">
                <h3>PEI Manioc</h3>
                <hr/>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>PEI</th>
                        <th>Région</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  foreach ($manioc as $ma): ?>
                        <tr>
                            <td><?=  $ma['col5'] ?></td>
                            <td><?=  $ma['col0'] ?></td>
                        </tr>
                    <?php  endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="col-md-12">
                <h3>PEI Maïs</h3>
                <hr/>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>PEI</th>
                        <th>Région</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  foreach ($mais as $ma): ?>
                        <tr>
                            <td><?=  $ma['col5'] ?></td>
                            <td><?=  $ma['col0'] ?></td>
                        </tr>
                    <?php  endforeach; ?>
                    </tbody>
                </table>
            </div>


            <div class="col-md-12">
                <h3>PEI SOJA</h3>
                <hr/>
                <table class="table table-striped" id="soja">
                    <thead>
                    <tr>
                        <th>PEI</th>
                        <th>Région</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  foreach ($Soja as $soj): ?>
                        <tr>
                            <td><?=  $soj['col5'] ?></td>
                            <td><?=  $soj['col0'] ?></td>
                        </tr>
                    <?php  endforeach; ?>
                    </tbody>
                </table>
            </div>




        </div>

    </div>
</div>

<div  class="container" id="searchObject" style="display:none;">
    <div class="row">
        <div class="col-md-12">
            <h3>Résultat <small onclick="closeSearch()" style="cursor:pointer;float: right;"><small class="text-danger">Fermer</small></small></h3>
            <hr/>
            <table class="table table-striped" >
                <thead>
                <tr>
                    <th>PEI</th>
                    <th>Région</th>
                    <th>Préfecture</th>
                    <th>Commune</th>
                    <th>Canton</th>
                    <th>Village</th>
                     <th>Sexe</th>
                     <th>Age</th>
                </tr>
                </thead>
                <tbody id="searchContent">

                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="bottom__space"></div>



<script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<!-- Responsive extension -->
<!--<script src="https://cdn.datatables.net/responsive/2.1.0/js/responsive.bootstrap.min.js"></script>-->
<!-- Buttons extension -->
<script src="//cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.1/js/buttons.bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js"></script>

<script >
    var dataTable = $('.table').DataTable({

        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
        },

        "bLengthChange": false,

        buttons: [
            {
                extend: 'excel',
                text: 'Exporter',
                className: 'btn-sm btn-flat',
            },
        ],
        // dom: "<'row'<'col-md-3'l><'col-md-12 text-center'B><'col-md-3'f>>" +
        //     "<'row'<'col-md-12'tr>>" +
        //     "<'row'<'col-md-5'i><'col-md-7'p>>",
        // drawCallback: function(settings) {
        //     if (!$('.datatable').parent().hasClass('table-responsive')) {
        //         $('.datatable').wrap("<div class='table-responsive'></div>");
        //     }
        // }


    });
</script>


<script>

    function filterWith() {
        $('#errorSearch').html()
        var reg = $('#regionsearch').val()
        if (reg != "") {
            if (reg == 'tout'){
                dataTable.search("").draw()
            }else{
                dataTable.search(reg).draw()
            }

        }
        else {$('#errorSearch').html("<small class='text-danger'>Veuillez choisir la région</small>")}
    }


    function searchPei(v) {
        if (v != ""){
            $('#primaryObject').hide('slow').delay(1000)
            $('#searchObject').show('slow').delay(1000)
             $.ajax({
                 url : "front_page.php",
                 type : "get",
                  data : {
                     code : v
                  },
                 success:function (msg) {
                     var contentHTML

                     console.log(msg)
                     if (JSON.parse(msg) != ""){

                         var content = JSON.parse(msg)
                         contentHTML = `
                              <tr>
                                 <td>${content.col5}</td>
                                 <td>${content.col0}</td>
                                 <td>${content.col1}</td>
                                 <td>${content.col2}</td>
                                 <td>${content.col3}</td>
                                 <td>${content.col4}</td>
                                 <td>${content.col6}</td>
                                 <td>${content.col7}</td>
                             </tr>
                            `
                     }

                     else{
                         contentHTML = `<tr><td colspan="8" style="text-align: center">Aucun PEI trouvé</td></tr>`
                     }


                     $('#searchContent').html(contentHTML)
                 }

             })
        }else {
            $('#searchObject').hide('slow').delay(1000)
            $('#primaryObject').show('slow').delay(1000)
        }
    }



    function closeSearch(){
        $('#input').val("")
        searchPei("")
    }
</script>
</body>
</html>
