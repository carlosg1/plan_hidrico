<?php
require_once('../../conPDO1921681051.php');

$nombre_calle = strtoupper($_POST['nombre_calle']);

// st_transform(st_setsrid(t1.the_geom, 4326), 22185) AS the_geom,
$qry_calle = "SELECT st_asgeojson(ST_Transform(ST_SetSrid(the_geom_calles, 22185), 4326))::json as \"geometry\"
              FROM gismcc.calles 
              WHERE nombre_calles 
              LIKE '%$nombre_calle%'
              LIMIT 2";

$rst_calle = $conPdoPg->query($qry_calle);

$reg_calle = $rst_calle->fetchAll(PDO::FETCH_ASSOC);

/*
$a = '[{
    "type": "LineString",
    "coordinates": [[5615135.4757464,6962341.25639507],[5615149.626489,6962473.21879052]]
}]';

echo json_encode($a);
*/
echo json_encode($reg_calle);

exit();
?>