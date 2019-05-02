<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link rel="stylesheet" href="css/leaflet.css">
        
        <link rel="stylesheet" href="css/Control.OSMGeocoder.css">
        <link rel="stylesheet" href="css/leaflet-measure.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
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
        </style>
        <title>Plan Hidrico - Municipalidad de Corrientes</title>
    </head>
    <body>
        <button type="button" class="btn btn-info salir" id="btnSalir">Salir</button>

        <div id="map"></div>

        <div id="logo-mcc"><img src="../images/escudo-municipalidad.png" alt=""></div>

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
        <script src="js/mostrar-infowindow.js"></script>
        <script>

        var map = L.map('map', {
            zoomControl:true,
            inertia: true,
            maxZoom:17,
            minZoom:1,
            crs: L.CRS.EPSG900913,
            center: [-27.48483,-58.81393],
            zoom: 20
        });

        map.fitBounds([[-27.45664518742547, -58.763208389282234],[-27.504312737195168, -58.87899398803712]]);
        
        var hash = new L.Hash(map);
        
        map.attributionControl.addAttribution(false);
        map.attributionControl.getContainer().innerHTML='<?php echo 'Usuario: ' . (isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '') . ' - '; ?>'+'<a href="http://gis.ciudaddecorrientes.gov.ar" target="_blank">Direccion Gral de SIG</a>';
        
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

        var mantenimientoSumideros = WMS50.getLayer("plan_hidrico:vw_mantenimiento_sumideros");

        var mantenimientoPluviales = WMS50.getLayer("plan_hidrico:vw_mantenimiento_pluviales");

        var lyr_calle = WMS50.getLayer("w_red_vial:vw_ide_calle");

        //var grp_capa_base = L.layerGroup([overlay_CapabaseGIS_0, lyr_calle]);

        //map.addLayer(grp_capa_base);

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
            "Restituci&oacute;n Pluviales": mantenimientoPluviales,
            "Restituci&oacute;n Sumideros": mantenimientoSumideros,
            "calles": lyr_calle
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
    </body>
</html>
