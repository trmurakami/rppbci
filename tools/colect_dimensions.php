<!DOCTYPE html>
<?php

    require '../inc/config.php'; 
    require '../inc/functions.php';

    $query["query"]["query_string"]["query"] = "+_exists_:doi -_exists_:metrics.dimensions"; 
    $query['sort'] = [
        ['ano.keyword' => ['order' => 'desc']],
    ];    

    $params = [];
    $params["index"] = $index;
    $params["type"] = $type;
    $params["size"] = 50;
    $params["body"] = $query; 

    $cursor = $client->search($params);
    $total = $cursor["hits"]["total"];

    echo 'Quantidade de registros restantes: '.($total - $params["size"]).'';
    echo '<br/><br/>';

    foreach ($cursor["hits"]["hits"] as $r) {

        $dimensionsData = dimensionsAPI($r["_source"]["doi"]);
        
        $body["doc"]["metrics"]["dimensions"] = $dimensionsData;
        $body["doc"]["metrics"]["dimensions"]["date"] = date("Ymd");
        $body["doc_as_upsert"] = true;      
        $resultado_dimensions = elasticsearch::store_record($r["_id"], $type, $body);
        print_r($resultado_dimensions);
    }
?>
