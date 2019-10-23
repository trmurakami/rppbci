<?php

// Set directory to ROOT
chdir('../');
// Include essencial files
require 'inc/functions.php';

/* Exibir erros */
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);


$query["query"]["query_string"]["query"] = "_exists_:author.organization.name";
//$query["query"]["query_string"]["query"] = "-_exists_:author.organization.tematres";

$params = [];
$params["index"] = $index;
$params["size"] = 200;
$params["_source"] = ["_id","author"];
$params["body"] = $query;

$cursor = $client->search($params);

echo 'Registros faltantes: '.$cursor["hits"]["total"]["value"].'<br/><br/>';

foreach ($cursor["hits"]["hits"] as $r) {

    //print("<pre>".print_r($r["_source"]["author"], true)."</pre>");

    unset($authorArray);
    
    $i = 0;
    foreach ($r["_source"]["author"] as $author) {

        if (isset($author["organization"]["name"])) {
            if (!isset($author["organization"]["tematres"])) {
                print_r($author["organization"]["name"]);
                echo "<br/><br/>";
            }
            $resultTematres = Authorities::tematresQuery($author["organization"]["name"], $tematres_url); 
            if ($resultTematres['foundTerm'] != "ND") {
                $author["organization"]["name"] = $resultTematres['foundTerm'];
                $author["organization"]["tematres"] = true;
            }
        }
        $authorArray[] = $author;
    }

    $body["doc"]["author"] = $authorArray;    
    $body["doc_as_upsert"] = true;
    //print("<pre>".print_r($body, true)."</pre>");     
    $result = Elasticsearch::update($r['_id'], $body);

}

?>