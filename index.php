<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        
        <link rel="stylesheet" href="css/leaflet.css">
        
        <link rel="stylesheet" href="css/Control.OSMGeocoder.css">
        <link rel="stylesheet" href="css/leaflet-measure.css">
        
        <style>
        html, body, #map {
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
        }
        #map { cursor: default; }
        #logo-mcc {
            position: absolute;
            top: 10px;
            left: 128px;
            width: 200px; 
            z-index: 1501;
        }
        #logo-mcc > img { width: 170px; }
        .salir {
            z-index: 1500;
            position: absolute;
            left: 55px;
            top: 10px;
        }
        .leaflet-control-layers{
            opacity:0.85!important;
            filter: alpha(opacity=30); /* para IE8 y posterior */
        }
        #busca-calle {
            position: absolute;
            left: 12px;
            top: 85px;
            z-index: 1000;
        }
        </style>
        <title>Calles - Municipalidad de Corrientes</title>
    </head>
    <body>
        <button type="button" class="btn btn-info salir" id="btnSalir">Salir</button>

        <div id="map"></div>

        <div id="logo-mcc"><img src="../images/escudo-municipalidad.png" alt=""></div>

        <!-- Buscador de calles --> 
        <div id="busca-calle">
            <div class="input-group shadow mb-3">
                <input type="text" class="form-control" placeholder="Nombre de calle" aria-label="Recipient's username" aria-describedby="btnBuscar" id="nombre_calle">
                <div class="input-group-append">
                    <button class="btn btn-outline-info" type="button" id="btnBuscar">Buscar</button>
                </div>
            </div>
        </div>

        <script src="js/leaflet.js"></script>
        <script src="js/leaflet.rotatedMarker.js"></script>
        <script src="js/leaflet.pattern.js"></script>
        <script src="js/leaflet-hash.js"></script>
        <script src="js/Autolinker.min.js"></script>
        <script src="js/rbush.min.js"></script>
        <script src="js/labelgun.min.js"></script>
        <script src="js/labels.js"></script>
        <script src="js/leaflet.wms.js"></script>
        <script src="js/Control.OSMGeocoder.js"></script>
        <script src="js/leaflet-measure.js"></script>
        <script src="js/leaflet-omnivore.min.js"></script>
        <script src="js/mostrar-infowindow.js"></script>

        <script>

        var map = L.map('map', {
            zoomControl:true,
            inertia: true,
            maxZoom:17,
            minZoom:1,
            crs: L.CRS.EPSG900913,
            center: [-27.48483,-58.81393],
            zoom: 17
        });

        map.fitBounds([[-27.45664518742547, -58.763208389282234],[-27.504312737195168, -58.87899398803712]]);
        
        var hash = new L.Hash(map);
        
        map.attributionControl.addAttribution(false);
        map.attributionControl.getContainer().innerHTML='Busqueda de calles - <a href="http://gis.ciudaddecorrientes.gov.ar" target="_blank">Direccion Gral de SIG</a>';
        
        var measureControl = new L.Control.Measure({
            primaryLengthUnit: 'meters',
            secondaryLengthUnit: 'kilometers',
            primaryAreaUnit: 'sqmeters',
            secondaryAreaUnit: 'hectares'
        });
        
        measureControl.addTo(map);
        
        var bounds_group = new L.featureGroup([]);
        
        function setBounds() {
        }

        var overlay_GooglecnSatellite_0 = L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
            opacity: 1.0
        });
        
        var overlay_CapabaseGIS_0 = L.WMS.layer("http://172.25.50.50:8080/geoserver/wms?version=1.1.1&", "wvca", {
            format: 'image/png',
            uppercase: true,
            transparent: true,
            continuousWorld : true,
            tiled: true,
            info_format: 'text/html',
            opacity: 1,
            identify: false,
        });

        map.addLayer(overlay_CapabaseGIS_0);

        var WMS50 = new wms_GIS("http://172.25.50.50:8080/geoserver/wms?", {
            format: 'image/png',
            uppercase: true,
            transparent: true,
            version: '1.1.1',
            continuousWorld : true,
            tiled: true,
            attribution: "Direccion Gral de GIS",
            info_format: 'application/json',
            opacity: 1
        });

       var lyr_calle = WMS50.getLayer("w_red_vial:vw_ide_calle");

       var vw_ide_calle_por_tipo_calzada = WMS50.getLayer("w_red_vial:vw_ide_calle_por_tipo_calzada");

       var osmGeocoder = new L.Control.OSMGeocoder({
            collapsed: false,
            position: 'topleft',
            text: 'Search'
        });

        var baseMaps = {
            "Google Satelite": overlay_GooglecnSatellite_0,
            "Capa base GIS": overlay_CapabaseGIS_0
        };

        L.control.layers(baseMaps,{
            "Calles": lyr_calle,
            "Calles por tipo de calzada": vw_ide_calle_por_tipo_calzada
        },{
            collapsed:false
        }).addTo(map);

        setBounds();

        L.control.scale().addTo(map);

        function fSalir(){
            document.location='salir/';
        }
        document.getElementById('btnSalir').addEventListener('click', function(){document.location='salir/';}, false);
        
        </script>

        <!-- bootstrap -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <script>
            $(document).ready(function(){

                $('#btnBuscar').click(function(){
                   

                    $.ajax( "busca_calle.php", {
                        data: 'nombre_calle=' + document.getElementById('nombre_calle').value,
                        method: 'POST',
                        success: function(response){

                            console.log(response);
                            //console.log(json_decode(response));

                            var myStyle = {
                                "color": "#ff7800",
                                "weight": 5,
                                "opacity": 0.65
                            };
/*
                            var myLines = [{
                                "type": "LineString",
                                "coordinates": [[-58.8352257280014,-27.464359678427],[-58.8350951127033,-27.4631678486613]]
                            }, {
                                "type": "LineString",
                                "coordinates": [[-58.8353568459458,-27.4654565286634],[-58.8352257280014,-27.464359678427]]
                            }];
*/
                            // var c = '[' + response + ']';
                           
                            L.geoJSON(JSON.parse(response), {style: myStyle}).addTo(map);
                            // L.geoJSON(response, {style: myStyle}).addTo(map);
                        }
                    });
                })
            })
        </script>
    </body>
</html>
