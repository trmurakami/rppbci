<?php

// Set directory to ROOT
chdir('../');
// Include essencial files
require 'inc/functions.php';

/* Exibir erros */
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);


$query["query"]["query_string"]["query"] = '_exists_:doi doi:1*';

$params = [];
$params["index"] = $index;
$params["size"] = 1;
$params["body"] = $query;

$cursor = $client->search($params);

foreach ($cursor["hits"]["hits"] as $r) {

    print("<pre>".print_r($r, true)."</pre>");

    $clientCrossref = new RenanBr\CrossRefClient();
    $clientCrossref->setUserAgent('GroovyBib/1.1 (https://bdpi.usp.br/; mailto:tiago.murakami@dt.sibi.usp.br)');
    $exists = $clientCrossref->exists('works/'.$r["_source"]["doi"].'');
    var_dump($exists);
    if ($exists == true) {
        $work = $clientCrossref->request('works/'.$r["_source"]["doi"].'');
        echo "<br/><br/><br/><br/>";
        $body["doc"]["USP"]["crossref"] = $work;
        $body["doc_as_upsert"] = true;
        $resultado_crossref = elasticsearch::store_record($r["_id"], $type, $body);
        print_r($resultado_crossref);
        sleep(11);
        ob_flush();
        flush();          
    } else {
        $body["doc"]["USP"]["crossref"]["notFound"] = true;
        $body["doc_as_upsert"] = true;
        $resultado_crossref = elasticsearch::store_record($r["_id"], $type, $body);
        print_r($resultado_crossref);
        sleep(2);
        ob_flush();
        flush();
    }    

}

?>