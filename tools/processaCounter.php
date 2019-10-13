#!/usr/bin/php
<?php
include 'inc/config.php';
include 'inc/functions.php';

while( $line = fgets(STDIN) ) {
    //$line = str_replace('"','',$line);
    $lineArray = explode(",",$line);

    $query["query"]["query_string"]["query"] = "doi:$lineArray[17]"; 
    $params = [];
    $params["index"] = $index;
    $params["type"] = $type;
    $params["size"] = 10;
    $params["body"] = $query; 
    $cursor = $client->search($params);


    $counterArray["assoc_type"] = str_replace('"','',$lineArray[1]);
    $counterArray["context_id"] = str_replace('"','',$lineArray[2]);
    $counterArray["issue_id"] = str_replace('"','',$lineArray[3]);
    $counterArray["submission_id"] = str_replace('"','',$lineArray[4]);
    $counterArray["assoc_id"] = str_replace('"','',$lineArray[5]);
    $counterArray["day"] = str_replace('"','',$lineArray[6]);
    $counterArray["month"] = str_replace('"','',$lineArray[7]);
    $counterArray["file_type"] = str_replace('"','',$lineArray[8]);
    $counterArray["country_id"] = str_replace('"','',$lineArray[9]);
    $counterArray["region"] = str_replace('"','',$lineArray[10]);
    $counterArray["city"] = str_replace('"','',$lineArray[11]);
    $counterArray["metric_type"] = str_replace('"','',$lineArray[12]);
    $counterArray["metric"] = (int)str_replace('"','',$lineArray[13]);
    $counterArray["doi"] = str_replace('"','',$lineArray[17]);

    //print_r($counterArray["submission_id"]);

    if ($cursor["hits"]["total"] == 1) {
        if (isset($cursor["hits"]["hits"][0]["_source"]["counter"])) {
            $body["doc"]["counter"] = $cursor["hits"]["hits"][0]["_source"]["counter"];
        }
        $body["doc"]["counter"][] = $counterArray;
        //print_r($body);
        $updateResult = elasticsearch::elastic_update($cursor["hits"]["hits"][0]["_id"], $type, $body);
        print_r($updateResult);
    }

    unset ($cursor);    
    unset ($params);
    unset ($counterArray);
    unset ($body);
}

?>