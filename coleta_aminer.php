<!DOCTYPE html>
<?php

    include('inc/config.php'); 
    include('inc/functions.php');
    $query["query"]["query_string"]["query"] = "-_exists_:aminer";    
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

    echo 'Faltam: '.$total.'<br/><br/>';

    foreach ($cursor["hits"]["hits"] as $r) {
        if ($r["_source"]["titulo"] == "Editorial") {
            echo "Editorial";
            $update_aminer["doc"]["aminer"]["date"] = date("Ymd");
            $update_aminer["doc_as_upsert"] = true;
            $result_aminer = elasticsearch::elastic_update($r['_id'],$type,$update_aminer);
            print_r($result_aminer);
            unset($update_aminer);
        } else {           
            $aminer = metrics::get_aminer($r["_source"]["titulo"]);
            if(count($aminer["result"]) > 0 ){
                similar_text($r["_source"]["titulo"], $aminer["result"][0]["title"], $percent);
                echo 'Percentual de: '.$percent.'';
                if ($percent > 90) {                            
                    $update_aminer["doc"]["aminer"] = $aminer["result"][0];
                    $update_aminer["doc"]["aminer"]["date"] = date("Ymd");
                    $update_aminer["doc_as_upsert"] = true;
                    $result_aminer = elasticsearch::elastic_update($r['_id'],$type,$update_aminer);
                    print_r($result_aminer);
                    unset($update_aminer);
                } else {
                $update_aminer["doc"]["aminer"]["date"] = date("Ymd");
                $update_aminer["doc_as_upsert"] = true;
                $result_aminer = elasticsearch::elastic_update($r['_id'],$type,$update_aminer);
                print_r($result_aminer);
                unset($update_aminer);
                }
            } else {
                $update_aminer["doc"]["aminer"]["date"] = date("Ymd");
                $update_aminer["doc_as_upsert"] = true;
                $result_aminer = elasticsearch::elastic_update($r['_id'],$type,$update_aminer);
                print_r($result_aminer);
                unset($update_aminer);
            }
        }    
        //print_r($body);
        echo '<br/><br/>';

        //sleep(5);


    }   
    



?>