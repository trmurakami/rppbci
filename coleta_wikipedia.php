<!DOCTYPE html>
<?php

    include('inc/config.php'); 
    include('inc/functions.php');
    $query["query"]["query_string"]["query"] = "-_exists_:wikipedia";    
    $query['sort'] = [
        ['datePublished.keyword' => ['order' => 'desc']],
    ];      

    $params = [];
    $params["index"] = $index;
    $params["type"] = $type;
    $params["size"] = 50;
    $params["body"] = $query;

    $cursor = $client->search($params);
    $total = $cursor["hits"]["total"];

    echo 'Faltam: '.$total.'<br/><br/>';

    foreach ($cursor["hits"]["hits"] as $r) {

        foreach ($r["_source"]['relation'] as $url) {
            $result_wikipedia = metrics::get_wikipedia(str_replace("https://","",str_replace("http://","",$url)));
            //print_r($result_wikipedia);
            if (count($result_wikipedia["query"]["exturlusage"]) > 0) {
                $body["doc"]["wikipedia"] = $result_wikipedia;
            }
            $body["doc"]["wikipedia"]["data_coleta"] = date("Ymd");
            $body["doc_as_upsert"] = true;             
            $result_am = elasticsearch::elastic_update($r['_id'],$type,$body);
            print_r($result_am);
            echo '<br/><br/>';        
        }       
        //print_r($body);
        echo '<br/><br/>';

        //sleep(5);


    }   
    



?>