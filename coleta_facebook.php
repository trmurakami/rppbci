<!DOCTYPE html>
<?php

    require 'inc/config.php'; 
    require 'inc/functions.php';
    $query["query"]["query_string"]["query"] = "-_exists_:facebook AND ano:[2016 TO 2017]";
    $query['sort'] = [
        ['ano.keyword' => ['order' => 'desc']],
    ];      

    $params = [];
    $params["index"] = $index;
    $params["type"] = $type;
    $params["size"] = 30;
    $params["body"] = $query;

    $cursor = $client->search($params);
    $total = $cursor["hits"]["total"];

    echo 'Faltam: '.$total.'<br/><br/>';

    foreach ($cursor["hits"]["hits"] as $r) {        

        Facebook::facebook_data($r["_source"]['relation'], $r["_id"]);
        sleep(90);

    }   
    



?>