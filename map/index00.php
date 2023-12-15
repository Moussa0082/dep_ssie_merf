<?php 
/* Conçue & Développée par:
    *****  *****  *****  *****  *****  *****  *****
    *      *   *  *      *      *   *  *        *
    *****  *   *  *****  *****  *   *  *****    *
    *      *   *  *          *  *   *  *        *
    *      *****  *      *****  *****  *        *
*/
session_start();
$path = '../';
include_once $path . 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"])) {
//header(sprintf("Location: %s", "./"));
  //exit;
}
include_once $path . $config->sys_folder . "/database/db_connexion.php";

function RandomCouleur(){
$r=dechex(rand(0,255));
$v=dechex(rand(0,255));
$b=dechex(rand(0,255));
return "#".$r.$v.$b;
}

 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print $config->sitename; ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $path.$config->FaveIcone; ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="keywords" content="<?php print $config->MetaKeys; ?>" />
  <meta name="description" content="<?php print $config->MetaDesc; ?>" />
  <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
  <meta name="author" content="<?php print $config->MetaAuthor; ?>" />
  <!--<meta charset="utf-8">-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous"><link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>

    <!--[if lte IE 8]><link rel="stylesheet" href="//cdn.leafletjs.com/leaflet-0.7.2/leaflet.ie.css" /><![endif]-->

<link rel="stylesheet" href="css/leaflet-sidebar.css" />
<link rel="stylesheet" href="css/styl.css" />
        
                           
<script type="text/javascript" src="data/natural.js"></script>
<script type="text/javascript" src="data/tourist.js"></script>
<script type="text/javascript" src="data/shopy.js"></script>
<script type="text/javascript" src="data/departs.js"></script>
<script type="text/javascript" src="data/lc2000.js"></script>
<script type="text/javascript" src="data/potencial2.js"></script>
<script type="text/javascript" src="data/lang.js"></script>
<script type="text/javascript" src="data/pes.js"></script>
<script src="data/pays.geojson"></script>
                       <!--[cluster-->    
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.1/dist/leaflet.css" />
  <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
  
  <link rel="stylesheet" href="examples/leaflet.groupedlayercontrol.css" />
<script src="examples/leaflet.groupedlayercontrol.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
  <script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>
  <script src="js/leaflet.browser.print.js"></script>
  <script src="js/leaflet.browser.print.utils.js"></script> 
  <script src="js/leaflet.browser.print.sizes.js"></script>    
  <link rel="stylesheet" href="https://ppete2.github.io/Leaflet.PolylineMeasure/Leaflet.PolylineMeasure.css" />
  <script src="https://ppete2.github.io/Leaflet.PolylineMeasure/Leaflet.PolylineMeasure.js"></script>
  <link rel="stylesheet" href="js/MarkerCluster.css" />
<link rel="stylesheet" href="js/MarkerCluster.Default.css" />  
<script type="text/javascript" src="js/leaflet.markercluster.js"></script>
<script type="text/javascript" src="leaflet.shapefile/catiline.js"> </script>
<script type="text/javascript" src="leaflet.shapefile/leaflet.shpfile.js"> </script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script type="text/javascript" src="js/labeledCircle-src.js" > </script> 
<script src="js/leaflet-sidebar.js"></script>
<link rel="stylesheet" href="js/Control.MiniMap.css" />
  <script src="js/Control.MiniMap.js" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" href="js/leaflet-openweathermap.css" />
  <script type="text/javascript" src="js/leaflet-openweathermap.js"> </script>
<script type="text/javascript" src="js/lightbox.js" > </script>
<link rel="stylesheet" href="css/lightbox.css" />


<script src="styles/bootstrap/dist/js/popper.js"></script>
<script src="styles/bootstrap/dist/js/bootstrap.min.js"></script>        
<script src="styles/bootstrap/dist/js/docs.min.js"></script>
<script src="styles/metisMenu/dist/metisMenu.min.js"></script>      
<style>
    body {
        padding: 0;
        margin: 0;
    }

    html, body, #map {
        height: 100%;
        font: 10pt "Helvetica Neue", Arial, Helvetica, sans-serif;
    }
    .menu_point{cursor: pointer;}
    .item_point{display: none;}
    input[type=checkbox]{display: none;}
    .leaflet-tooltip-pane .text {
  color: red; 
  font-weight: bold;
  background: transparent;
  border:0;
  box-shadow: none;
  font-size:1em;
}
.leaflet-tooltip-pane {z-index: 1000;}
</style>
</head>
<body> 
<?php if (!isset($_GET['coord'])): ?> 
<?php endif ?>             
    <div id="sidebar" class="sidebar collapsed">
        <!-- Nav tabs -->
        <div class="sidebar-tabs">
            <ul role="tablist">
                <li><a href="#vrstvy" role="tab" style="margin-top: 10px;"><i class="fa fa-bars"></i></a></li>
                <!--li><a href="#infos" role="tab"><i class="fas fa-info"></i></a></li>
                <li><a href="#formular" role="tab"><i class="fab fa-wpforms"></i></a></li-->
            </ul>
        </div>
        <!-- Tab panes -->
        <div class="sidebar-content">
            <div class="sidebar-pane" id="vrstvy">
                <h1 class="sidebar-header" id="tlac">
                   
         AgriFARM <a href="../" ><img src="img/ruche.png"  width="50" height="34" alt="Retour au système"/> </a>
                    <span class="sidebar-close"><i class="fa fa-caret-left"></i></span>
                       </h1>
                    <div id="seznamvrstev"> 
                     </div>    
              
            </div>        
        </div>
    </div>
    
    <div id="map" class="sidebar-map"></div>
    <script>
<?php if (isset($_GET['coord']) && !empty($_GET['coord'])) { ?> 
     // Create the map
var map = new L.map('map', {
    center: new L.LatLng(<?php echo $_GET['coord']; ?> ,95),
    zoom: 12,
    maxZoom: 15,
    zoomControl: false, 
    layers: pod,hotels
});
<?php }else{ ?> 
       // Create the map
var map = new L.map('map', {
    //center: new L.LatLng(14.36, -14.47 ,95),
	    center: new L.LatLng(10.573602, -11.889172 ,95),
    zoom: 8,
    maxZoom: 15,
    zoomControl: false, 
    layers: pod,hotels
});
<?php } ?>
  //var countriesLayer=L.geoJson(countries).addTo(map);
  //map.fitBounds(countriesLayer.getBounds());
  L.control.zoom({
     position:'topright'
}).addTo(map);

<?php 
$query_zone_shp="SELECT * FROM t_zone";
  try{
    $liste_zone_shp = $pdar_connexion->prepare($query_zone_shp);
    $liste_zone_shp->execute();
    $zone_shp=$liste_zone_shp->fetchAll();
    $totalRows_zone_shp=$liste_zone_shp->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

$tbl_nom_zone_shp=array();$tbl_file_zone_shp=array();$tbl_color_zone_shp=array();$tbl_titre_zone_shp=array();$tbl_gps_zone_shp=array();$tbl_affiche_zone_shp=array();
if ($totalRows_zone_shp>0) {
    foreach ($zone_shp as $zone_shp) {
        $tbl_nom_zone_shp[]=$zone_shp['nom_zone'];
        $tbl_file_zone_shp[]=$zone_shp['shapefile'];
        $tbl_titre_zone_shp[]=$zone_shp['titre'];
        $tbl_color_zone_shp[]=$zone_shp['couleur'];
        $tbl_gps_zone_shp[]=$zone_shp['coord_gps'];
        $tbl_affiche_zone_shp[]=$zone_shp['afficher_par_defaut'];
    }
}

  $query_liste_feuille="SELECT * FROM t_feuille";
  try{
    $liste_feuille = $pdar_connexion->prepare($query_liste_feuille);
    $liste_feuille->execute();
    $feuil=$liste_feuille->fetchAll();
    $totalRows_liste_feuille=$liste_feuille->rowCount();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

$data='';$tbl_feuil=array();$tbl_feuil_lib=array();
if ($totalRows_liste_feuille>0) {  
  foreach ($feuil as $feuil) {
$sql_verif='SELECT * FROM information_schema.TABLES WHERE (TABLE_SCHEMA = \'agrifarm\') AND (TABLE_NAME = \''.$feuil['Table_Feuille'].'\')';
try{
        $verif=$pdar_connexion->prepare($sql_verif);
        $verif->execute();
        $tbl_exist=$verif->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
if ($tbl_exist>0) {
   $sql_op='SELECT * FROM '.$feuil['Table_Feuille'].' WHERE LG IS NOT NULL AND LT IS NOT NULL';
     try{
        $liste_op=$pdar_connexion->prepare($sql_op);
        $liste_op->execute();
        $op=$liste_op->fetchAll();
        $total_op=$liste_op->rowCount();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
  if ($total_op>0) {
        $nom_tbl=str_replace('t_', 'fiche_', $feuil['Table_Feuille']);
        $tbl_feuil[]=$nom_tbl;
        $tbl_feuil_lib[]=$feuil['Nom_Feuille'];
        $data.='var '.$nom_tbl.'ss = {
              "type": "FeatureCollection",
              "name": "'.$feuil['Nom_Feuille'].'",
              "crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } },
              "features": [';
        foreach ($op as $op) {
          $gps=($op['LG']).', '.($op['LT']);
          //$data.='{ "type": "Feature","geometry": {"type": "Point","coordinates": ['.$gps.']},"properties":  { "text":"'.$feuil['Nom_Feuille'].'","labelPosition": ['.$gps.'],"radius":15,"name": "'.$feuil['Nom_Feuille'].'","description": "","icon": "js/theme/default/markers/2.png","couleur":"#3399FF" } },';
          $data.='{ "type": "Feature", "properties": { "id": 2165, "osm_id": "35346197", "z_order": 0, "'.$nom_tbl.'": "'.$feuil['Nom_Feuille'].'", "building": null, "name": "'.$feuil['Nom_Feuille'].'", "popup": "'.$feuil['Nom_Feuille'].'", "addr_house": null, "addr_hou_1": null, "addr_stree": null, "addr_city": null, "addr_state": null, "addr_postc": null, "addr_place": null, "addr_count": null, "addr_hamle": null, "addr_subur": null, "addr_subdi": null, "addr_distr": null, "addr_provi": null, "website": null, "contact_we": null, "phone": null, "contact_ph": null, "opening_ho": null, "brand": null, "OBJECTID": 1 }, "geometry": { "type": "Point", "coordinates": [ '.$gps.' ] } },';
        }
      $data=substr($data, 0, strlen($data)-1);
      $data.=']
};';
    }
  }
}
}
 echo $data;
/*for ($i=0; $i < count($tbl_feuil); $i++) { 
echo'
var class_'.$tbl_feuil[$i].' = new L.geoJson('.$tbl_feuil[$i].', {
    pointToLayer: function(feature, latlng) {
        return new L.LabeledCircleMarker(
  feature.geometry.coordinates.slice().reverse(),
  feature, {
    markerOptions: {color:\'#050\'}
  });
    },
    onEachFeature: function(feature, layer) {
        layer.bindTooltip(feature.properties.name,{className: \'myCSSClass\'});
        layer.bindPopup(feature.properties.description);
    }
})';
if ($i==0) {echo '.addTo(map);';}else{echo ';';}
 }*/
 ?>
     //icon create
<?php for ($i=1; $i <= 17; $i++) { 
  echo "var icon".$i." = L.icon({
    iconUrl: 'img/marker/".$i.".png',
    iconSize: [30, 30],
    iconAnchor: [10, 15],
});";
} ?>
       var gifticon = L.icon({
    iconUrl: 'img/marker/1.png',
    iconSize: [30, 30],
    iconAnchor: [10, 15],
});
         var bayicon = L.icon({
    iconUrl: 'img/bay.png',
    iconSize: [30, 30],
    iconAnchor: [10, 15],
}); 
        var museumicon = L.icon({
    iconUrl: 'https://image.flaticon.com/icons/png/512/236/236981.png',
    iconSize: [30, 30],
    iconAnchor: [10, 15],
}); 
        var hotelicon = L.icon({
    iconUrl: 'img/hotel_i_2.png',
    iconSize: [30, 30],
    iconAnchor: [10, 15],
});

var schoolicon = L.icon({
    iconUrl: 'img/marker/1.png',
    iconSize: [30, 30],
    iconAnchor: [10, 15],
}); 
var comicon = L.icon({
    iconUrl: 'img/com-icon.png',
    iconSize: [30, 30],
    iconAnchor: [10, 15],
}); 
var transicon = L.icon({
    iconUrl: 'img/transformer-icon.png',
    iconSize: [30, 30],
    iconAnchor: [10, 15],
});   
var prodicon = L.icon({
    iconUrl: 'img/producer-icon.png',
    iconSize: [30, 30],
    iconAnchor: [10, 15],
});      //icon create


var departly= L.geoJson(departs, {   filter: departe,
    pointToLayer: function (feature, latlng,) {
        return L.marker(latlng, {icon: gifticon });
    },
    onEachFeature: function(feature, layer) {
  layer.bindTooltip( feature.properties.name,{className: 'myCSSClass'});
  }
});

     function departe(departs) {
  if (departs.properties.departement === "ville") return true
}
 
      var hotels= L.geoJson(tourist, {   filter: museumf,
    pointToLayer: function (feature, latlng,) {
        return L.marker(latlng, {icon: museumicon });
    },
    onEachFeature: function(feature, layer) {
  layer.bindTooltip( feature.properties.name,{className: 'myCSSClass'});
  }
});
     function museumf(tourist) {
  if (tourist.properties.tourism === "museum") return true
}
    
      var hostels= L.geoJson(tourist, {   filter: hot,
    pointToLayer: function (feature, latlng,) {
        return L.marker(latlng, {icon: hotelicon });
    } ,
    onEachFeature: function(feature, layer) {
  layer.bindTooltip(  feature.properties.name,{className: 'myCSSClass'});
  }
});
   function hot(tourist) {
  if (tourist.properties.tourism === "hostel") return true
}
<?php 
$var_fonction='';
for ($i=0; $i < count($tbl_feuil); $i++) { 
  $var_fonction.='var class_'.$tbl_feuil[$i].'=L.geoJson('.$tbl_feuil[$i].'ss, {filter: '.$tbl_feuil[$i].'s,pointToLayer: function (feature, latlng,) {
        return L.marker(latlng, {icon: icon'.rand(1,17).' });
    },
    onEachFeature: function(feature, layer) {
  layer.bindTooltip( feature.properties.name,{className: \'myCSSClass\'});
   //layer.bindPopup(feature.properties.name);
  }
});';
$var_fonction.='function '.$tbl_feuil[$i].'s('.$tbl_feuil[$i].'ss){if ('.$tbl_feuil[$i].'ss.properties.'.$tbl_feuil[$i].' === "'.$tbl_feuil_lib[$i].'") return true
}';
}
echo $var_fonction; ?>         
        // layer tourist potencial
      function getColors(d) {
    return  d > 250  ? '#bd0026' :
        d > 150  ? '#f03b20' :
        d > 90  ? '#fd8d3c' :
        d > 50   ? '#feb24c' :
        d > 20   ? '#fed976' :
        d > 0   ? '#ffffb2' :
              '#FFEDA0';
  }

  function stylepot(feature) {
    return {
      weight: 1,
      opacity: 1,
      color: 'white',
      dashArray: '1',
      fillOpacity: 0.9,
      fillColor: getColors(feature.properties.gridcode)
    };
  }
     
    
    var potenciall =L.geoJSON(potencial2, {style: stylepot,
  onEachFeature: function (feature, layer) {
    layer.bindTooltip('<h3>'+feature.properties.gridcode+'</h3>',{className: 'myCSSClass'});
  }
});

     function getColor(d) {
    return  d > 70  ? '#045a8d' :
        d > 50  ? '#2b8cbe' :
        d > 40  ? '#74a9cf' :
        d > 25   ? '#a6bddb' :
        d > 15   ? '#d0d1e6' :
        d > 0   ? '#f1eef6' :
              '#FFEDA0';
  }

  function stylelang(feature) {
    return {
      weight: 0.5,
      opacity: 1,
      color: 'white',
      dashArray: '1',
      fillOpacity:1,
      fillColor: getColor(feature.properties.gridcode)
    };
  }
     
    
    var lang =L.geoJSON(lang, {style: stylelang,
  onEachFeature: function (feature, layer) {
    layer.bindTooltip('<h3>'+feature.properties.gridcode+'</h3>',{className: 'myCSSClass'});
  }
});

var highway = L.geoJson(pes, {color: "#ff7800", weight: 8, opacity: .5}).bindPopup("<h3>Turn on ortophoto 2000 and 2012!</h3>",{className: 'myCSSClass'});

  //basemaps  WMS
              
                var minis=L.tileLayer('http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'});
        
              
              var pod=L.tileLayer('http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'});
        var googleHybrid = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}',{
                maxZoom: 30,
                subdomains:['mt0','mt1','mt2','mt3']
            });
          
 var theMarker = {};

  map.on('click',function(e){
    lat = e.latlng.lat;
    lon = e.latlng.lng;

    //console.log("You clicked the map at LAT: "+ lat+" and LONG: "+lon );
        //Clear existing marker, 

        if (theMarker != undefined) {
              map.removeLayer(theMarker);
        };

    //Add a marker to show where you clicked.
     theMarker = L.marker([lat,lon]).addTo(map);  
});
 function updateMarker(lat,lon) {
    console.log("You clicked the map at LAT: "+ lat+" and LONG: "+lon );
        //Clear existing marker, 

        if (theMarker != undefined) {
              map.removeLayer(theMarker);
        };

    //Add a marker to show where you clicked.
     theMarker = L.marker([lat,lon]).addTo(map);
  }       
              var mapycz2 =  L.tileLayer('http://m{s}.mapserver.mapy.cz/base-m/{z}-{x}-{y}',{ident:'mapycz',attribution:'&copy;Seznam.cz a.s., | &copy;OpenStreetMap <a href="http://mapy.cz"><img class="print" target="_blank" src="//api.mapy.cz/img/api/logo.png" style="cursor: pointer; position:relative;top: 5px;"></a>',maxZoom:20,subdomains:"1234"});
              var baseMap = new L.TileLayer('http://{s}.tiles.mapbox.com/v3/gvenech.m13knc8e/{z}/{x}/{y}.png'); 
              var mapycz =  L.tileLayer('http://m{s}.mapserver.mapy.cz/base-m/{z}-{x}-{y}',{ident:'mapycz',attribution:'&copy;Seznam.cz a.s., | &copy;OpenStreetMap <a href="http://mapy.cz"><img class="print" target="_blank" src="//api.mapy.cz/img/api/logo.png" style="cursor: pointer; position:relative;top: 5px;"></a>',maxZoom:20,subdomains:"1234"});    
                mapycz.addTo(map);
          //wms historic
                var wms2006 = L.tileLayer.wms('http://www.ign.es/wms/pnoa-historico?', {
                    layers: 'PNOA2006'
                       });
                var wms2012 = L.tileLayer.wms('http://www.ign.es/wms/pnoa-historico?', {
                  layers: 'PNOA2012'
                       });
               var wms2000 = L.tileLayer.wms('http://www.ign.es/wms/pnoa-historico?', {
                  layers: 'SIGPAC'
                       });
                      
                      
                           var LeafIcon = L.Icon.extend({
    options: {
      
      iconSize:     [30, 35],
      
      iconAnchor:   [1, 30],
      shadowAnchor: [4, 62],
      popupAnchor:  [-3, -76]
    }
  });

    var icon1 = new LeafIcon({iconUrl: 'img/hiso.png'}),
    redIcon = new LeafIcon({iconUrl: 'http://icons.iconarchive.com/icons/paomedia/small-n-flat/1024/gift-icon.png'}),
    koleje = new LeafIcon({iconUrl: 'dum.png'}),
    pirat = new LeafIcon({iconUrl: 'pirat.png'}),
    katedra = new LeafIcon({iconUrl: 'http://new.uss.upol.cz/wp-content/uploads/2014/10/UpolLOgo.png'});




  var port= L.marker([39.56226137431428,2.638392448425293],{icon: icon1}).bindPopup("<h3>Turn on ortophoto 2000 and 2012!</h3> <h2 style='color:rgb(220,31,37)'; 'text-align: center';>Port de Palma </h2> <img src=img/port.jpg  width=300 height=230 >  ");

                      
                        var wms2015 = L.tileLayer.wms('http://ideib.caib.es/pub_ideib/public/IMATGES_OR2015_R25/MapServer/WMSServer?');
                       
   
             var prec= L.OWM.precipitationClassic = L.OWM.precipitationClassic({showLegend: false, opacity: 0.5,appId: '8b816162ce03197c15265e47b0149f36'});
            var city = L.OWM.current({intervall: 5,showOwmStationLink: true,minZoom:2, lang: 'en', appId:"8b816162ce03197c15265e47b0149f36"});
// Shapefiles Sao Tome
<?php 
for ($i=0; $i < count($tbl_file_zone_shp); $i++) { 
      $namereg=($tbl_nom_zone_shp[$i]);
      $namereg=str_replace("'", "", $namereg);
      $namereg=str_replace("’", "", $namereg);
      $namereg=str_replace("-", "", $namereg);
      $namereg=str_replace("ê", "e", $namereg);
      $namereg=str_replace("ô", "o", $namereg);
      $namereg=str_replace("è", "e", $namereg);
      $namereg=str_replace("é", "e", $namereg);
      $namereg=str_replace(" ", "", $namereg);
      $namereg=trim($namereg);
      $namereg=strtoupper($namereg);
      $titre=!empty($tbl_titre_zone_shp[$i])?'"<a href=\"?coord='.$tbl_gps_zone_shp[$i].'&region='.$namereg.'\">'.$tbl_titre_zone_shp[$i].'</a>"':'Object.keys(feature.properties).map(function(k) {
            return k + ": " + feature.properties[k];
          }';
      $couleur=!empty($tbl_color_zone_shp[$i])?$tbl_color_zone_shp[$i]:RandomCouleur();
      //$zoom_reg=!empty($tbl_gps_zone_shp[$i])?'map.setView(['.$tbl_gps_zone_shp[$i].'],7);':'';
      echo 'var shpfile_'.$namereg.' = new L.Shapefile(\'leaflet.shapefile/'.$tbl_file_zone_shp[$i].'\', {
      onEachFeature: function(feature, layer) {
        if (feature.properties) {
          layer.setStyle({
              color: "'.$couleur.'",
              opacity: 2,
              fillColor: "'.$couleur.'",
              fillOpacity: 0.4
          });
          layer.bindPopup('.$titre.');
          layer.on(\'click\', function() { layer.openPopup(); });
          //layer.on(\'mouseout\', function() { layer.closePopup(); });
        }
      }
    });';
    if (!empty($tbl_affiche_zone_shp[$i]) && $tbl_affiche_zone_shp[$i]=='Oui' && !isset($_GET['region'])) {
        echo 'shpfile_'.$namereg.'.addTo(map);';
    }
    echo 'shpfile_'.$namereg.'.bringToFront();
    shpfile_'.$namereg.'.once("data:loaded", function() {
      console.log("finished loaded shapefile '.$namereg.'");
    });';
    }
     if (isset($_GET['region']) || !empty($_GET['region'])) {echo 'shpfile_'.$_GET['region'].'.addTo(map);shpfile_'.$_GET['region'].'.bringToFront();';}
 ?>
      var baseMaps = {
     "Open Street Map":mapycz,
    "Google Hybrid" : googleHybrid
    };

    var groupedOverlays = { 
  "<b class='menu_point' style=color:rgb(220,31,37);>FICHES DYNAMIQUES<b class='fa fa-chevron-down' id='act'></b></b> <br>": {
    <?php 

    for ($i=0; $i < count($tbl_feuil); $i++) {
      echo'"<b class=\'item_point\'>'.addslashes($tbl_feuil_lib[$i]).' </b>": class_'.$tbl_feuil[$i].',';
    }/**/
     ?>
    },
    "<b class='menu_point' style=color:rgb(220,31,37);>COUCHES DE DONNEES <b class='fa fa-chevron-down' id='act'></b></b> <br>": {    <?php 

    for ($i=0; $i < count($tbl_file_zone_shp); $i++) { 
      $namereg=($tbl_nom_zone_shp[$i]);
      $namereg=str_replace("'", "", $namereg);
      $namereg=str_replace("’", "", $namereg);
      $namereg=str_replace("-", "", $namereg);
      $namereg=str_replace("ê", "e", $namereg);
      $namereg=str_replace("ô", "o", $namereg);
      $namereg=str_replace("è", "e", $namereg);
      $namereg=str_replace("é", "e", $namereg);
      $namereg=str_replace(" ", "", $namereg);
      $namereg=trim($namereg);
      $namereg=strtoupper($namereg); 
      echo'"<b class=\'item_point\'>'.addslashes($tbl_nom_zone_shp[$i]).' </b>": shpfile_'.$namereg.',';
    }/**/
     ?>
    },
};
var sidebar = L.control.sidebar('sidebar').addTo(map);
sidebar.open('vrstvy');
   var panel= L.control.groupedLayers(baseMaps,groupedOverlays,{collapsed:false}).addTo(map);
    var htmlObject = panel.getContainer();
      var a = document.getElementById('seznamvrstev')
      function setParent(el, newParent){
        newParent.appendChild(el);
      }
      setParent(htmlObject, a);
             
var homebutton= L.easyButton('fa-home fa-lg', function()
{map.setView([14.36, -14.47],7);}, 'home position',{ position: 'topright'});
   // center: new L.LatLng(14.36, -14.47,95),

homebutton.addTo(map);


 /*
var customControl =  L.Control.extend({

  options: {
    position: 'topright'
  },

  onAdd: function (map) {
    var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');

    container.style.backgroundColor = 'white';     
    container.style.backgroundImage = "url(img/icon_1.png)"
    container.style.backgroundSize = "25px 28px";
    container.style.width = '26px';
    container.style.height = '26px';
    
    container.style.zIndex = '2000000';

    container.onclick = function(){map.setView([39.56226137431428, 2.638392448425293],15);  map.addLayer( port); map.addLayer( wms2000);
    }

    return container;
  }
});
    
  map.addControl(new customControl());
  */


   

//var position1= L.easyButton('<img src="img/icon_1.png" style="width:25px";>', function()

//{map.setView([39.56226137431428, 2.638392448425293],15);  map.addLayer( port);}, 'port',{ position: 'topright'});
//position1.addTo(map);
                  
  
   
           
           
            
            
          map.on('click', function(e) {
    $('#latInput').val(e.latlng.lat);
    $('#lngInput').val(e.latlng.lng);
    updateMarker(e.latlng.lat, e.latlng.lng);
    <?php 
    for ($i=0; $i < count($tbl_ind); $i++) { 
      echo'class_ind_'.$tbl_ind[$i].'.bringToFront();';
    }
     ?>
});  
 
var updateMarkerByInputs = function() {
  return updateMarker( $('#latInput').val() , $('#lngInput').val());
}
$('#latInput').on('input', updateMarkerByInputs);
$('#lngInput').on('input', updateMarkerByInputs);
L.control.scale({position: 'bottomright', maxWidth:150, metric:true}).addTo(map);
var tisk= L.control.browserPrint({position: 'topright'}).addTo(map);
var miniMap = new L.Control.MiniMap(minis, { toggleDisplay: true, width:120, height:120, zoomLevelOffset:-4.5 }).addTo(map);
L.Control.geocoder().addTo(map);

 var cpt=0;
$('.menu_point').on('click', function() {
  cpt++;
  if (cpt%2==0) {
  var ind=$(this).index();  
  //alert($(this).parent().parent().parent().children().next().find('*').html());
 $(this).parent().parent().parent().children().next().find('*').hide();
}else{
  $(this).parent().parent().parent().children().next().find('*').show();
}
});              
       
        
    </script>
    
    
    =
</body>
</html>
     
        