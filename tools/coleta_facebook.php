<!DOCTYPE html>
<?php

    require '../inc/config.php'; 
    require '../inc/functions.php';
    $query["query"]["query_string"]["query"] = "-_exists_:facebook";
    $query['sort'] = [
        ['datePublished.keyword' => ['order' => 'desc']],
    ];      

    $params = [];
    $params["index"] = $index;
    $params["size"] = 20;
    $params["body"] = $query;

    $cursor = $client->search($params);

    //echo 'Faltam: '.$total.'<br/><br/>';

    foreach ($cursor["hits"]["hits"] as $r) {
        //print_r($r["_id"]);
        //echo "<br/>";
        //print_r($r["_source"]['relation']);
        //echo "<br/>";
        //print_r($r["_source"]['doi']);
        //echo "<br/>";        

        //$dois[] = $r["_source"]['doi']; 
        //Facebook::facebook_doi($dois, $r["_id"]); 

        Facebook::facebook_data($r["_source"]['relation'], $r["_id"]);
        sleep(100);

    }

    header("Refresh: 0");
    



?>