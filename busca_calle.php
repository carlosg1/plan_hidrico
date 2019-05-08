<?php
require_once('../../conPDO1921681051.php');

$nombre_calle = strtoupper($_POST['nombre_calle']);

// st_transform(st_setsrid(t1.the_geom, 4326), 22185) AS the_geom,
$qry_calle = "SELECT st_asgeojson(ST_Transform(ST_SetSrid(the_geom_calles, 22185), 4326))::json as \"geometry\"
              FROM gismcc.calles 
              WHERE nombre_calles 
              LIKE '%$nombre_calle%'";

$rst_calle = $conPdoPg->query($qry_calle);

//$reg_calle = $rst_calle->fetchAll(PDO::FETCH_ASSOC);

$ret = '';
$c = 0; // contador de ciclos

if($rst_calle->rowCount() == 0){
    echo -1; // no hay resultados
    exit;
}

while( $reg_calle = $rst_calle->fetchObject()){
    if($c > 0) { 

        $ret .= ',';

    }

    $ret .=  $reg_calle->geometry;

    $c++;
}


echo '[' . $ret . ']'; // devuelve un string geojson

exit();
?>